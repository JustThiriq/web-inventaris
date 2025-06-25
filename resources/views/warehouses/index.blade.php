@extends('adminlte::page')

@section('title', 'Manajemen Items')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Manajemen Items</h4>
                    <div>
                        <a href="{{ route('items.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Item
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filter & Search -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select name="category_id" id="categoryFilter" class="form-control">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="warehouse_id" id="warehouseFilter" class="form-control">
                                <option value="">Semua Gudang</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="low_stock" id="stockFilter" class="form-control">
                                <option value="">Semua Stok</option>
                                <option value="true" {{ request('low_stock') == 'true' ? 'selected' : '' }}>Stok Rendah</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="text" name="search" id="searchInput" class="form-control"
                                       placeholder="Cari kode atau nama item..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('items.index') }}" class="btn btn-secondary">
                                <i class="fas fa-refresh"></i> Reset
                            </a>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Kode</th>
                                    <th>Nama Item</th>
                                    <th>Kategori</th>
                                    <th>Gudang</th>
                                    <th>Barcode</th>
                                    <th>Stok Min</th>
                                    <th>Stok Saat Ini</th>
                                    <th>Status Stok</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                <tr class="{{ $item->current_stock <= $item->min_stock ? 'table-warning' : '' }}">
                                    <td>{{ $loop->iteration + ($items->currentPage() - 1) * $items->perPage() }}</td>
                                    <td>
                                        <strong>{{ $item->code }}</strong>
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        @if($item->category)
                                            <span class="badge badge-info">{{ $item->category->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->warehouse)
                                            <span class="badge badge-secondary">{{ $item->warehouse->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->barcode)
                                            <small class="text-monospace">{{ $item->barcode }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-light">{{ $item->min_stock ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">{{ $item->current_stock ?? 0 }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $stockStatus = 'normal';
                                            $stockBadge = 'badge-success';
                                            $stockText = 'Normal';
                                            
                                            if($item->current_stock <= $item->min_stock) {
                                                $stockStatus = 'low';
                                                $stockBadge = 'badge-danger';
                                                $stockText = 'Rendah';
                                            } elseif($item->current_stock <= ($item->min_stock * 1.5)) {
                                                $stockStatus = 'warning';
                                                $stockBadge = 'badge-warning';
                                                $stockText = 'Peringatan';
                                            }
                                        @endphp
                                        <span class="badge {{ $stockBadge }}">
                                            @if($stockStatus == 'low')
                                                <i class="fas fa-exclamation-triangle"></i>
                                            @elseif($stockStatus == 'warning')
                                                <i class="fas fa-exclamation-circle"></i>
                                            @else
                                                <i class="fas fa-check"></i>
                                            @endif
                                            {{ $stockText }}
                                        </span>
                                    </td>
                                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('items.show', $item) }}" class="btn btn-info btn-sm" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('items.edit', $item) }}" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <!-- Quick Stock Update -->
                                            <button type="button" class="btn btn-success btn-sm" title="Update Stok" 
                                                    data-toggle="modal" data-target="#stockModal" 
                                                    data-item-id="{{ $item->id }}" 
                                                    data-item-name="{{ $item->name }}" 
                                                    data-current-stock="{{ $item->current_stock }}">
                                                <i class="fas fa-boxes"></i>
                                            </button>

                                            <form action="{{ route('items.destroy', $item) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus"
                                                        onclick="return confirm('Yakin ingin menghapus item ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center">Tidak ada data item.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Menampilkan {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }}
                            dari {{ $items->total() }} data
                        </div>
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Update Modal -->
<div class="modal fade" id="stockModal" tabindex="-1" role="dialog" aria-labelledby="stockModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="stockForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title" id="stockModalLabel">Update Stok Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="itemName">Nama Item:</label>
                        <input type="text" class="form-control" id="itemName" readonly>
                    </div>
                    <div class="form-group">
                        <label for="currentStockDisplay">Stok Saat Ini:</label>
                        <input type="text" class="form-control" id="currentStockDisplay" readonly>
                    </div>
                    <div class="form-group">
                        <label for="current_stock">Stok Baru: <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="current_stock" name="current_stock" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Stok</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Filter functionality
    $('#categoryFilter, #warehouseFilter, #stockFilter').change(function() {
        filterItems();
    });

    $('#searchBtn').click(function() {
        filterItems();
    });

    $('#searchInput').keypress(function(e) {
        if (e.which == 13) {
            filterItems();
        }
    });

    function filterItems() {
        let params = new URLSearchParams();

        let category_id = $('#categoryFilter').val();
        let warehouse_id = $('#warehouseFilter').val();
        let low_stock = $('#stockFilter').val();
        let search = $('#searchInput').val();

        if (category_id) params.append('category_id', category_id);
        if (warehouse_id) params.append('warehouse_id', warehouse_id);
        if (low_stock) params.append('low_stock', low_stock);
        if (search) params.append('search', search);

        window.location.href = '{{ route("items.index") }}?' + params.toString();
    }

    // Stock update modal
    $('#stockModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var itemId = button.data('item-id');
        var itemName = button.data('item-name');
        var currentStock = button.data('current-stock');
        
        var modal = $(this);
        modal.find('#itemName').val(itemName);
        modal.find('#currentStockDisplay').val(currentStock);
        modal.find('#current_stock').val(currentStock);
        modal.find('#stockForm').attr('action', '/items/' + itemId + '/update-stock');
    });
});
</script>
@endpush
@endsection