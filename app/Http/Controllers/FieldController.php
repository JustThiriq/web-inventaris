<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $fields = Bidang::latest()
            ->search($request->input('search'))
            ->paginate(10);

        return view('pages.fields.index', compact('fields'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.fields.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:bidangs,name',
        ]);

        Bidang::create($validated);

        return redirect()->route('fields.index')
            ->with('success', 'Bidang berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bidang $field)
    {
        return view('pages.fields.edit', compact('field'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bidang $field)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:bidangs,name,'.$field->id,
        ]);

        $field->update($validated);

        return redirect()->route('fields.index')
            ->with('success', 'Bidang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bidang $field)
    {
        $field->delete();

        return redirect()->route('fields.index')
            ->with('success', 'Bidang berhasil dihapus.');
    }
}
