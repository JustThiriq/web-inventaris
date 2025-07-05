@extends('adminlte::page')

@section('title', 'Manajemen Items')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex w-100 align-items-center justify-content-between">
                            <h4 class="card-title mb-0">
                                Manajemen Item
                            </h4>
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
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="warehouse_id" id="warehouseFilter" class="form-control">
                                    <option value="">Semua Gudang</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}"
                                            {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="stock" id="stockFilter" class="form-control">
                                    <option value="">Semua Stok</option>
                                    <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Stok
                                        Rendah</option>
                                    <option value="warning" {{ request('stock') == 'warning' ? 'selected' : '' }}>Stok
                                        Perhatian</option>
                                    <option value="high" {{ request('stock') == 'high' ? 'selected' : '' }}>Stok
                                        Tinggi</option>
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
                                        <th style="min-width: 10px">#</th>
                                        <th style="min-width: 150px">Item</th>
                                        <th style="min-width: 150px">Kategori</th>
                                        <th style="min-width: 150px">Gudang</th>
                                        <th style="min-width: 150px">Barcode</th>
                                        <th style="min-width: 150px">Stok</th>
                                        <th style="min-width: 150px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($items as $item)
                                        <tr class="{{ $item->current_stock <= $item->min_stock ? 'table-warning' : '' }}">
                                            <td>{{ $loop->iteration + ($items->currentPage() - 1) * $items->perPage() }}
                                            </td>
                                            <td>
                                                {{ $item->name }}
                                                <br />
                                                <strong>{{ $item->code }}</strong>
                                            </td>
                                            <td>
                                                @if ($item->category)
                                                    <span class="badge badge-info">{{ $item->category->name }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->warehouse)
                                                    <span class="badge badge-secondary">{{ $item->warehouse->name }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <img src="{{ $item->barcodeUrl }}" alt="Barcode" class="img-fluid"
                                                    style="max-width: 100px;">
                                            </td>
                                            <td>
                                                <span class="badge {{ $item->badgeLevel }}" data-toggle="tooltip"
                                                    title="{{ $item->badgeLabel }}">
                                                    {{ $item->currentStok }} / {{ $item->minStok }}
                                                </span>
                                            </td>

                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('items.edit', $item) }}"
                                                        class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <form action="{{ route('items.destroy', $item) }}" method="POST"
                                                        class="d-inline" id="delete-form-{{ $item->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>

                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus"
                                                        onclick="event.preventDefault(); if(confirm('Yakin ingin menghapus item ini?')) {
                                                            document.getElementById('delete-form-{{ $item->id }}').submit();
                                                        }">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
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
                        <!-- Enhanced Pagination -->
                        @if ($items->hasPages())
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="datatable-info">
                                        Menampilkan {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }}
                                        dari {{ $items->total() }} total data
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-right">
                                        {!! $items->appends(request()->query())->links('pagination::bootstrap-4') !!}
                                    </div>
                                </div>
                            </div>
                        @else
                            @if ($items->count() > 0)
                                <div class="text-muted mt-3">
                                    Total {{ $items->count() }} data gudang
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            $(document).ready(function() {
                // Initialize tooltips
                $('[data-toggle="tooltip"]').tooltip();

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
                    if (low_stock) params.append('stock', low_stock);
                    if (search) params.append('search', search);

                    window.location.href = '{{ route('items.index') }}?' + params.toString();
                }

                // Stock update modal
                $('#stockModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var itemId = button.data('item-');
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
