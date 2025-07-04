<?php

namespace App\Http\Controllers;

use App\Models\ProdukRequest;
use Illuminate\Http\Request;
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
            $query->where('nama_produk', 'like', '%'.$request->search.'%');
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Paginate results
        $produkRequests = $query->latest()->paginate(10);

        // Append query parameters to pagination links
        $produkRequests->appends($request->query());

        return view('produk-request.index', compact('produkRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('produk-request.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'produk_requests' => 'required|array|min:1',
            'produk_requests.*.nama_produk' => 'required|string|max:255',
            'produk_requests.*.harga_estimasi' => 'required|numeric|min:0',
            'produk_requests.*.deskripsi' => 'nullable|string|max:1000',
        ], [
            'produk_requests.required' => 'Minimal harus ada satu produk request.',
            'produk_requests.*.nama_produk.required' => 'Nama produk harus diisi.',
            'produk_requests.*.harga_estimasi.required' => 'Harga estimasi harus diisi.',
            'produk_requests.*.harga_estimasi.numeric' => 'Harga estimasi harus berupa angka.',
            'produk_requests.*.harga_estimasi.min' => 'Harga estimasi tidak boleh kurang dari 0.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            foreach ($request->produk_requests as $produkData) {
                ProdukRequest::create([
                    'nama_produk' => $produkData['nama_produk'],
                    'harga_estimasi' => $produkData['harga_estimasi'],
                    'deskripsi' => $produkData['deskripsi'] ?? null,
                    'status' => 'pending',
                    // 'user_id' => auth()->id(), // Uncomment jika ada sistem auth
                ]);
            }

            return redirect()->route('produk-request.index')
                ->with('success', 'Produk request berhasil ditambahkan! Total: '.count($request->produk_requests).' item.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage())
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
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage())
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
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
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

        try {
            $produkRequest->update([
                'status' => $request->status,
                'catatan_admin' => $request->catatan_admin,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status berhasil diupdate!',
                    'status_badge' => $produkRequest->status_badge,
                ]);
            }

            return redirect()->route('produk-request.index')
                ->with('success', 'Status produk request berhasil diperbarui.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Terjadi kesalahan: '.$e->getMessage()], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
