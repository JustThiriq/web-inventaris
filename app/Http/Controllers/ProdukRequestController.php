<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ProdukRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProdukRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProdukRequest::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $user = Auth::user();
        if ($user->role->slug === 'user') {
            $query->where('user_id', $user->id);
        } else if ($user->role->slug === 'warehouse') {
            if ($user->bidang) {
                $query->join('users', 'users.id', '=', 'user_id')
                    ->where('bidang_id', $user->bidang->id);
            }
        }

        // Paginate results
        $produkRequests = $query
            ->pending()
            ->select('produk_requests.*')
            ->orderBy('produk_requests.created_at', 'desc')
            ->paginate(10);

        // Append query parameters to pagination links
        $produkRequests->appends($request->query());

        return view('produk-request.index', compact('produkRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $requestNumber = 'Auto generated'; // Generate next request number

        return view('produk-request.create', compact('requestNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_date' => 'required|date',
            'description' => 'nullable|string',
            'produk_requests' => 'required|array|min:1',
            'produk_requests.*.item_id' => 'required|string|max:255',
            'produk_requests.*.quantity' => 'required|numeric|min:0',
        ], [
            'request_date.required' => 'Tanggal request harus diisi.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'produk_requests.required' => 'Minimal harus ada satu produk request.',
            'produk_requests.*.item_id.required' => 'Item harus diisi.',
            'produk_requests.*.quantity.required' => 'Quantity harus diisi.',
            'produk_requests.*.quantity.numeric' => 'Quantity harus berupa angka.',
            'produk_requests.*.quantity.min' => 'Quantity tidak boleh kurang dari 0.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Generate next request number
        $currentMax = ProdukRequest::withTrashed()->max('id') ?? 0; // Get the current max ID or 0 if no records exist
        // Increment by 1 to get the next request number
        $currentMax += 1;
        $requestNumber = sprintf('RQ-%04d', $currentMax);
        $request->merge([
            'request_number' => $requestNumber,
            'user_id' => Auth::id(), // Assuming you have user authentication
        ]);

        DB::beginTransaction();
        try {
            $productRequests = ProdukRequest::create($request->all());
            if (! $productRequests) {
                throw new \Exception('Gagal membuat produk request.');
            }

            $items = [];
            foreach ($request->produk_requests as $produk) {
                $items[] = array_merge($produk, [
                    'produk_request_id' => $productRequests->id,
                ]);
            }

            $nonconsumableItems = [];
            foreach ($items as $item) {
                $itemModel = Item::find($item['item_id']);

                if (! $itemModel) {
                    throw new \Exception('Item dengan ID ' . $item['item_id'] . ' tidak ditemukan.');
                }

                // Check if the item is consumable
                if ($itemModel->isConsumable()) {
                    // If consumable, check if the quantity is valid
                    if ($item['quantity'] <= 0) {
                        throw new \Exception('Quantity untuk item ' . $itemModel->name . ' harus lebih dari 0.');
                    }
                }

                // If not consumable, add to non-consumable items
                if (! $itemModel->isConsumable()) {
                    $nonconsumableItems[] = [
                        'item_id' => $item['item_id'],
                        'quantity' => $item['quantity'],
                        'produk_request_id' => $productRequests->id,
                    ];
                }
            }

            // if there are non-consumable items, create them as pemesanan
            if (count($nonconsumableItems) > 0) {
                $productRequests->buatPemesanan($nonconsumableItems);
            }



            $success = $productRequests->details()->createMany($items);
            if (! $success) {
                throw new \Exception('Gagal menambahkan detail produk request.');
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('produk-request.index')
                ->with('success', 'Produk request berhasil ditambahkan! Total: ' . count($request->produk_requests) . ' item.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProdukRequest $produkRequest)
    {
        return view('produk-request.show', compact('produkRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProdukRequest $produkRequest)
    {
        return view('produk-request.edit', compact('produkRequest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProdukRequest $produkRequest)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|string|max:255',
            'harga_estimasi' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,approved,rejected',
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $produkRequest->update($validator->validated());

            return redirect()->route('produk-request.index')
                ->with('success', 'Produk request berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProdukRequest $produkRequest)
    {
        try {
            $produkRequest->delete();

            return redirect()->route('produk-request.index')
                ->with('success', 'Produk request berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update status of the produk request.
     */
    public function updateStatus(Request $request, ProdukRequest $produkRequest)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,approved,rejected',
            'catatan_admin' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $produkRequest->update([
                'status' => $request->status,
                'catatan_admin' => $request->catatan_admin,
            ]);

            if ($request->expectsJson()) {
                DB::rollBack();

                return response()->json([
                    'success' => true,
                    'message' => 'Status berhasil diupdate!',
                    'status_badge' => $produkRequest->status_badge,
                ]);
            }

            // if approved, check pemesanan
            if ($request->status === 'approved') {
                // Check if there are pemesanan associated with this produk request
                $pemesananExists = $produkRequest->pemesanan()->where('status', '!=', 'sudah_diambil')->exists();
                if ($pemesananExists) {
                    return redirect()->back()
                        ->with('error', 'Produk request ini memiliki pemesanan item non-consumable yang belum selesai.');
                }
            }

            // get details
            $details = $produkRequest->details;
            foreach ($details as $detail) {
                // decrement stock
                $product = Item::find($detail->item_id);
                if ($product) {
                    if ($request->status === 'approved' && $product->isConsumable()) {
                        $product->decrementStock($detail->quantity);
                    }
                }
            }

            DB::commit();

            return redirect()->route('produk-request.index')
                ->with('success', 'Status produk request berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function print(Request $request, ProdukRequest $produkRequest)
    {
        // dd($produkRequest);
        $pdf = Pdf::loadView('report.pdf.transaction', [
            'transaction' => $produkRequest,
        ]);

        return $pdf->stream();
        // return $pdf->download('invoice.pdf');
    }
}
