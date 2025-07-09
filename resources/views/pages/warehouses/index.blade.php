@extends('adminlte::page')

@section('title', 'Manajemen Lokasi Rak')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Manajemen Lokasi Rak</h4>
                            <a href="{{ route('warehouses.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Lokasi Rak
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('components.flash-message')

                        @include('components.searchable', [
                            'route' => 'warehouses.index',
                            'placeholder' => 'Cari nama Lokasi Rak, lokasi, atau manager...',
                        ])

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Lokasi Rak</th>
                                        <th>Lokasi</th>
                                        <th>Manager</th>
                                        <th>Kontak</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($warehouses as $index => $warehouse)
                                        <tr>
                                            <td>{{ $warehouses->firstItem() + $index }}</td>
                                            <td>{{ $warehouse->name }}</td>
                                            <td>{{ $warehouse->location }}</td>
                                            <td>{{ $warehouse->manager_name }}</td>
                                            <td>{{ $warehouse->phone }}</td>
                                            <td>{{ $warehouse->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('warehouses.edit', $warehouse) }}"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('warehouses.destroy', $warehouse) }}"
                                                        method="POST" class="d-inline"
                                                        id="delete-form-{{ $warehouse->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="event.preventDefault(); if(confirm('Yakin ingin menghapus Lokasi Rak ini?')) {
                                                            document.getElementById('delete-form-{{ $warehouse->id }}').submit();
                                                        }">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                                <br>Tidak ada data Lokasi Rak
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Enhanced Pagination -->
                        @include('components.pagination', ['pagination' => $warehouses])
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('css')
        <style>
            .datatable-info {
                color: #6c757d;
                font-size: 0.875rem;
                line-height: 2.5;
            }
        </style>
    @endpush
@endsection
