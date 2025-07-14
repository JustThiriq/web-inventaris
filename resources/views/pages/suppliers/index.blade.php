@extends('adminlte::page')

@section('title', 'Manajemen Supplier')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex w-100 align-items-center justify-content-between">
                            <h4 class="card-title mb-0">
                                Manajemen Supplier
                            </h4>
                            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Supplier
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Flash Messages --}}
                        @include('components.flash-message')



                        {{-- Searchable --}}
                        @include('components.searchable', ['route' => 'suppliers.index'])

                        <!-- Categories Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>NPWP</th>
                                        <th>Nama Supplier</th>
                                        <th>No HP</th>
                                        <th>Alamat</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($suppliers as $index => $item)
                                        <tr>
                                            <td>{{ $suppliers->firstItem() + $index }}</td>
                                            <td>{{ $item->npwp }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->phone }}</td>
                                            <td>{{ $item->address }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('suppliers.edit', $item) }}"
                                                        class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('suppliers.destroy', $item) }}"
                                                        method="POST" class="d-inline"
                                                        id="delete-form-{{ $item->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus"
                                                        onclick="event.preventDefault(); if(confirm('Yakin ingin menghapus Supplier ini?')) { document.getElementById('delete-form-{{ $item->id }}').submit(); }">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data Supplier.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @include('components.pagination', ['pagination' => $suppliers])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
