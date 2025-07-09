<?php

namespace App\Http\Controllers;

use App\Models\ProdukRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProdukRequest::query();

        // Check if the user is an admin
        if (!Auth::user()->role->isAdmin()) {
            // If not admin, show only user's requests
            $query->where('user_id', Auth::id());
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
            ->latest()->paginate(10);

        // Append query parameters to pagination links
        $produkRequests->appends($request->query());

        return view('history.index', compact('produkRequests'));
    }
}
