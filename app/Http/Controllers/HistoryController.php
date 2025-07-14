<?php

namespace App\Http\Controllers;

use App\Models\ProdukRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProdukRequest::query();


        $user = auth()->user();
        if ($user->role->slug === 'user') {
            $query->where('user_id', $user->id);
        } else if ($user->role->slug === 'warehouse') {
            if ($user->bidang) {
                $query->join('users', 'users.id', '=', 'user_id')
                    ->where('users.bidang_id', $user->bidang->id);
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->notPending(); // Default to not pending if no status is provided
        }

        // Paginate results
        $produkRequests = $query
            ->select('produk_requests.*')
            ->orderBy('produk_requests.created_at', 'desc')
            ->paginate(10);

        // Append query parameters to pagination links
        $produkRequests->appends($request->query());

        return view('history.index', compact('produkRequests'));
    }
}
