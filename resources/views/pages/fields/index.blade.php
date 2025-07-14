@extends('adminlte::page')

@section('title', 'Manajemen Bidang')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex w-100 align-items-center justify-content-between">
                            <h4 class="card-title mb-0">
                                Manajemen Bidang
                            </h4>
                            <a href="{{ route('fields.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Bidang
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Flash Messages --}}
                        @include('components.flash-message')



                        {{-- Searchable --}}
                        @include('components.searchable', ['route' => 'fields.index'])

                        <!-- Categories Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Bidang</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($fields as $index => $item)
                                        <tr>
                                            <td>{{ $fields->firstItem() + $index }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('fields.edit', $item) }}"
                                                        class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('fields.destroy', $item) }}"
                                                        method="POST" class="d-inline"
                                                        id="delete-form-{{ $item->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus"
                                                        onclick="event.preventDefault(); if(confirm('Yakin ingin menghapus Bidang ini?')) { document.getElementById('delete-form-{{ $item->id }}').submit(); }">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data Bidang.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @include('components.pagination', ['pagination' => $fields])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
