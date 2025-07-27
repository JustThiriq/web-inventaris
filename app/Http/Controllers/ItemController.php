<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    protected function _searchable($query, $request)
    {
        // Filter by category_id
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by warehouse_id
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        // Search by code or name
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by low stock items
        if ($request->filled('stock')) {
            switch ($request->stock) {
                case 'low':
                    $query->whereRaw('current_stock <= min_stock');
                    break;
                case 'warning':
                    $query->whereRaw('current_stock <= min_stock * 2')
                        ->whereRaw('current_stock > min_stock');
                    break;
                case 'high':
                    $query->whereRaw('current_stock > min_stock * 2');
                    break;
                default:
                    // No specific stock filter
                    break;
            }
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Item::active()->with(['category', 'warehouse']);
        $this->_searchable($query, $request);

        $items = $query->latest()->paginate(10);

        // Get categories and warehouses for filter dropdowns
        $categories = Category::all();
        $warehouses = Warehouse::all();

        return view('pages.items.index', compact('items', 'categories', 'warehouses'));
    }

    public function apiSearch(Request $request)
    {
        $query = Item::active()->with(['category', 'warehouse']);
        $this->_searchable($query, $request);

        $query
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->select([
                'items.id',
                DB::raw('CONCAT(items.code, " - ", items.name, " - ", categories.name) as name'),
            ]);

        $items = $query->orderBy('items.created_at', 'desc')->limit(10)->get();

        return response()->json($items);
    }

    public function searchByBarcode($code)
    {
        $item = Item::where('barcode', $code)->first();

        if (! $item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'item' => $item,
        ]);
    }

    public function printBarcode(Item $item)
    {
        // Load relationships defined in the model
        $item->load(['category', 'warehouse']);

        return view('pages.items.print-barcode', compact('item'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $warehouses = Warehouse::all();
        $units = Unit::all(); // Assuming you have a Unit model

        return view('pages.items.create', compact('categories', 'warehouses', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:items,code',
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'unit_id' => 'nullable|exists:units,id',
            'barcode' => 'nullable|string|max:255|unique:items,barcode',
            'min_stock' => 'nullable|integer|min:0',
            'current_stock' => 'nullable|integer|min:0',
        ]);

        // min_stock and current_stock can be set to 0 by default
        if ($request->category_id) {
            $category = Category::find($request->category_id);
            if ($category && strtolower($category->name) !== 'consumable') {
                $validated['min_stock'] = $validated['min_stock'] ?? 0;
                $validated['current_stock'] = $validated['current_stock'] ?? 0;
            } else {
                // If category is consumable, set min_stock and current_stock to null
                $validated['min_stock'] = 0;
                $validated['current_stock'] = 0;
            }
        } else {
            $validated['min_stock'] = $validated['min_stock'] ?? 0;
            $validated['current_stock'] = $validated['current_stock'] ?? 0;
        }

        Item::create($validated);

        return redirect()->route('items.index')
            ->with('success', 'Item berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        // Load relationships defined in the model
        $item->load(['category', 'warehouse', 'unit']);

        return view('pages.items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $categories = Category::all();
        $warehouses = Warehouse::all();
        $units = Unit::all(); // Assuming you have a Unit model

        return view('pages.items.edit', compact('item', 'categories', 'warehouses', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:items,code,' . $item->id,
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'unit_id' => 'nullable|exists:units,id',
            'barcode' => 'nullable|string|max:255|unique:items,barcode,' . $item->id,
            'min_stock' => 'nullable|integer|min:0',
            'current_stock' => 'nullable|integer|min:0',
        ]);

        // min_stock and current_stock can be set to 0 by default
        if ($request->category_id) {
            $category = Category::find($request->category_id);
            if ($category && strtolower($category->name) !== 'consumable') {
                $validated['min_stock'] = $validated['min_stock'] ?? 0;
                $validated['current_stock'] = $validated['current_stock'] ?? 0;
            } else {
                // If category is consumable, set min_stock and current_stock to null
                $validated['min_stock'] = 0;
                $validated['current_stock'] = 0;
            }
        } else {
            $validated['min_stock'] = $validated['min_stock'] ?? 0;
            $validated['current_stock'] = $validated['current_stock'] ?? 0;
        }

        $item->update($validated);

        return redirect()->route('items.index')
            ->with('success', 'Item berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        // Check if item has related item_requests
        if ($item->item_requests()->count() > 0) {
            return redirect()->route('items.index')
                ->with('error', 'Item tidak dapat dihapus karena masih memiliki request terkait.');
        }

        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Item berhasil dihapus.');
    }

    /**
     * Get items with low stock (current_stock <= min_stock)
     */
    public function lowStock()
    {
        $items = Item::with(['category', 'warehouse'])
            ->whereRaw('current_stock <= min_stock')
            ->latest()
            ->paginate(10);

        return view('pages.items.low-stock', compact('items'));
    }

    /**
     * Update stock for an item
     */
    public function updateStock(Request $request, Item $item)
    {
        $validated = $request->validate([
            'current_stock' => 'required|integer|min:0',
        ]);

        $item->update($validated);

        return redirect()->back()
            ->with('success', 'Stock item berhasil diperbarui.');
    }
}
