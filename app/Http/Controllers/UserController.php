<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }

        // Search by name or email
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        $users = $query->latest()->paginate(10);
        $roles = Role::get(); // Assuming you have a Role model

        return view('pages.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all(); // Assuming you have a Role model

        return view('pages.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'sometimes|boolean',
        ]);
        // check if the password confirmation is set
        if (isset($validated['password_confirmation'])) {
            if ($validated['password'] !== $validated['password_confirmation']) {
                return redirect()->back()->withErrors(['password' => 'Password confirmation does not match.']);
            }
            // Remove password confirmation from validated data
            unset($validated['password_confirmation']);
        }

        // Map the password to the model's column (password_hash)
        $validated['password'] = Hash::make($validated['password']);

        // Checkbox may not be present so default to false if not set
        $validated['is_active'] = $request->has('is_active');

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();

        return view('pages.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'email', 'role_id', 'phone', 'is_active']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.edit', $user)->with('success', 'User berhasil diupdate!');
    }

    /**
     * Remove (deactivate) the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting the current logged-in user.
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        // Soft delete by deactivating
        $user->update(['is_active' => false]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dinonaktifkan.');
    }

    /**
     * Activate user.
     */
    public function activate(User $user)
    {
        $user->update(['is_active' => true]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diaktifkan.');
    }

    /**
     * Get users data for API/AJAX.
     */
    public function getData(Request $request)
    {
        $users = User::select(['id', 'name', 'email', 'role_id', 'is_active', 'created_at'])
            ->when($request->role_id, function ($query, $role) {
                return $query->where('role_id', $role);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10);

        return response()->json($users);
    }
}
