<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Warehouse;
use App\Models\ProdukRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $product = Item::active()->count();
        $requestItem = ProdukRequest::where('status', 'request')->count();
        $category = Category::count();
        $warehouse = Warehouse::count();


        $boxes = [
            [
                'text' => 'Total Produk',
                'count' => $product,
                'icon' => 'fas fa-box',
                'color' => 'bg-info',
                'link' => route('items.index'),
            ],
            [
                'text' => 'Produk Diminta',
                'count' => $requestItem,
                'icon' => 'fas fa-shopping-cart',
                'color' => 'bg-warning',
                'link' => route('produk-request.index'),
            ],
            [
                'text' => 'Jenis Produk',
                'count' => $category,
                'icon' => 'fas fa-tags',
                'color' => 'bg-success',
                'link' => route('categories.index'),
            ],
            [
                'text' => 'Lokasi Rak Tersedia',
                'count' => $warehouse,
                'icon' => 'fas fa-warehouse',
                'color' => 'bg-danger',
                'link' => route('warehouses.index'),
            ],
        ];

        return view('dashboard', compact('boxes'));
    }
}
