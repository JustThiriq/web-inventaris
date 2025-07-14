<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Pemesanan;
use App\Models\ProdukRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pemesanan::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Paginate results
        $items = $query
            ->pending()
            ->latest()->paginate(10);

        // Append query parameters to pagination links
        $items->appends($request->query());

        return view('pemesanan.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $requestNumber = 'Auto generated'; // Generate next request number
        $suppliers = Supplier::all(); // Assuming you have a Supplier model
        $warehouses = Item::select('*')->distinct()->get(); // Get distinct warehouses

        return view('pemesanan.create', compact('requestNumber', 'suppliers', 'warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_po' => 'required',
            'no_wo' => 'required',
            'tanggal_pemesanan' => 'required',
            'tanggal_kedatangan' => 'required',
            'tanggal_dipakai' => 'required',
            'supplier_id' => 'required',
            'produk_requests.*.item_id' => 'required|string|max:255',
            'produk_requests.*.jumlah' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Generate next request number
        $currentMax = Pemesanan::withTrashed()->max('id') ?? 0; // Get the current max ID or 0 if no records exist
        // Increment by 1 to get the next request number
        $currentMax += 1;
        $requestNumber = sprintf('RQ-%04d', $currentMax);
        $request->merge([
            'request_number' => $requestNumber,
            'user_id' => Auth::id(), // Assuming you have user authentication
        ]);

        DB::beginTransaction();
        try {
            $pemesanan = Pemesanan::create($request->all());
            if (! $pemesanan) {
                throw new \Exception('Gagal membuat pemesanan.');
            }

            $items = [];
            foreach ($request->produk_requests as $produk) {
                $items[] = array_merge($produk, [
                    'pemesanan_id' => $pemesanan->id,
                ]);
            }

            $success = $pemesanan->details()->createMany($items);
            if (! $success) {
                throw new \Exception('Gagal menambahkan detail pemesanan.');
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('pemesanan.index')
                ->with('success', 'pemesanan berhasil ditambahkan! Total: ' . count($request->produk_requests) . ' item.');
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
    public function show(Pemesanan $pemesanan)
    {
        return view('pemesanan.show', compact('pemesanan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pemesanan $pemesanan)
    {

        $suppliers = Supplier::all(); // Assuming you have a Supplier model
        $warehouses = Item::select('*')->distinct()->get(); // Get distinct warehouses

        return view('pemesanan.edit', compact('pemesanan', 'suppliers', 'warehouses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pemesanan $pemesanan)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'no_po' => 'required',
            'no_wo' => 'required',
            'tanggal_pemesanan' => 'required',
            'tanggal_kedatangan' => 'required',
            'tanggal_dipakai' => 'required',
            'supplier_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $payload = $validator->validated();
            $payload['status'] = 'pending';
            $pemesanan->update($payload);

            return redirect()->route('pemesanan.index')
                ->with('success', 'pemesanan berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pemesanan $pemesanan)
    {
        try {
            $pemesanan->delete();

            return redirect()->route('pemesanan.index')
                ->with('success', 'pemesanan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update status of the pemesanan.
     */
    public function updateStatus(Request $request, Pemesanan $pemesanan)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:belum_diambil,sudah_diambil',
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
            $pemesanan->update([
                'status' => $request->status,
            ]);

            // If the status is 'sudah_diambil', increment stock for consumable items
            if ($request->status === 'sudah_diambil') {
                foreach ($pemesanan->details as $detail) {
                    $item = Item::find($detail->item_id);
                    if ($item && $item->isConsumable()) {
                        $item->incrementStock($detail->jumlah);
                    }
                }
            }

            DB::commit();

            return redirect()->route('pemesanan.index')
                ->with('success', 'Status pemesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
