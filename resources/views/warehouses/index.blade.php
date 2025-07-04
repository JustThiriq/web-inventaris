@extends('adminlte::page')

@section('title', 'Manajemen Gudang')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Manajemen Gudang</h4>
                        <a href="{{ route('warehouses.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Gudang
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Nama Gudang</th>
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
                                            <a href="{{ route('warehouses.show', $warehouse) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('warehouses.edit', $warehouse) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Yakin ingin menghapus gudang ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                        <br>Tidak ada data gudang
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Enhanced Pagination -->
                    @if($warehouses->hasPages())
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="datatable-info">
                                    Menampilkan {{ $warehouses->firstItem() ?? 0 }} - {{ $warehouses->lastItem() ?? 0 }}
                                    dari {{ $warehouses->total() }} total data
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="float-right">
                                    {!! $warehouses->appends(request()->query())->links('pagination::bootstrap-4') !!}
                                </div>
                            </div>
                        </div>
                    @else
                        @if($warehouses->count() > 0)
                            <div class="text-muted mt-3">
                                Total {{ $warehouses->count() }} data gudang
                            </div>
                        @endif
                    @endif
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