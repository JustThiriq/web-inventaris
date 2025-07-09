@extends('adminlte::page')

@section('title', 'Manajemen Jenis')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex w-100 align-items-center justify-content-between">
                            <h4 class="card-title mb-0">
                                Manajemen Ketegori
                            </h4>
                            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Ketegori
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Flash Messages --}}
                        @include('components.flash-message')



                        {{-- Searchable --}}
                        @include('components.searchable', ['route' => 'categories.index'])

                        <!-- Categories Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Jenis</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $index => $category)
                                        <tr>
                                            <td>{{ $categories->firstItem() + $index }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('categories.edit', $category) }}"
                                                        class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('categories.destroy', $category) }}"
                                                        method="POST" class="d-inline"
                                                        id="delete-form-{{ $category->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus"
                                                        onclick="event.preventDefault(); if(confirm('Yakin ingin menghapus Jenis ini?')) { document.getElementById('delete-form-{{ $category->id }}').submit(); }">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data Jenis.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @include('components.pagination', ['pagination' => $categories])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
