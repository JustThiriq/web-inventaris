@extends('adminlte::page')

@section('title', 'Tambah Item')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-plus"></i> Tambah Item Baru
                    </h4>
                    <a href="{{ route('items.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h5><i class="icon fas fa-ban"></i> Validation Error!</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('items.store') }}" method="POST" id="itemForm">
                        @csrf
                        
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Informasi Dasar</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Item Code -->
                                        <div class="form-group">
                                            <label for="code">Kode Item <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" 
                                                       class="form-control @error('code') is-invalid @enderror" 
                                                       id="code" 
                                                       name="code" 
                                                       value="{{ old('code') }}" 
                                                       placeholder="Masukkan kode item"
                                                       required>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary" id="generateCode" title="Generate Kode Otomatis">
                                                        <i class="fas fa-magic"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @error('code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Kode harus unik untuk setiap item</small>
                                        </div>

                                        <!-- Item Name -->
                                        <div class="form-group">
                                            <label for="name">Nama Item <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" 
                                                   name="name" 
                                                   value="{{ old('name') }}" 
                                                   placeholder="Masukkan nama item"
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Barcode -->
                                        <div class="form-group">
                                            <label for="barcode">Barcode</label>
                                            <div class="input-group">
                                                <input type="text" 
                                                       class="form-control @error('barcode') is-invalid @enderror" 
                                                       id="barcode" 
                                                       name="barcode" 
                                                       value="{{ old('barcode') }}" 
                                                       placeholder="Masukkan barcode (opsional)">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary" id="scanBarcode" title="Scan Barcode">
                                                        <i class="fas fa-barcode"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @error('barcode')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Barcode akan digunakan untuk scanning</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Kategori & Lokasi</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Category -->
                                        <div class="form-group">
                                            <label for="category_id">Kategori</label>
                                            <div class="input-group">
                                                <select class="form-control @error('category_id') is-invalid @enderror" 
                                                        id="category_id" 
                                                        name="category_id">
                                                    <option value="">Pilih Kategori</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-primary" id="addCategory" title="Tambah Kategori Baru">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Warehouse -->
                                        <div class="form-group">
                                            <label for="warehouse_id">Gudang</label>
                                            <div class="input-group">
                                                <select class="form-control @error('warehouse_id') is-invalid @enderror" 
                                                        id="warehouse_id" 
                                                        name="warehouse_id">
                                                    <option value="">Pilih Gudang</option>
                                                    @foreach($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                            {{ $warehouse->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-primary" id="addWarehouse" title="Tambah Gudang Baru">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @error('warehouse_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Manajemen Stok</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Min Stock -->
                                        <div class="form-group">
                                            <label for="min_stock">Stok Minimum</label>
                                            <div class="input-group">
                                                <input type="number" 
                                                       class="form-control @error('min_stock') is-invalid @enderror" 
                                                       id="min_stock" 
                                                       name="min_stock" 
                                                       value="{{ old('min_stock', 0) }}" 
                                                       min="0" 
                                                       placeholder="0">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">pcs</span>
                                                </div>
                                            </div>
                                            @error('min_stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Stok minimum untuk peringatan</small>
                                        </div>

                                        <!-- Current Stock -->
                                        <div class="form-group">
                                            <label for="current_stock">Stok Awal</label>
                                            <div class="input-group">
                                                <input type="number" 
                                                       class="form-control @error('current_stock') is-invalid @enderror" 
                                                       id="current_stock" 
                                                       name="current_stock" 
                                                       value="{{ old('current_stock', 0) }}" 
                                                       min="0" 
                                                       placeholder="0">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">pcs</span>
                                                </div>
                                            </div>
                                            @error('current_stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Jumlah stok saat ini</small>
                                        </div>

                                        <!-- Stock Status Preview -->
                                        <div class="form-group">
                                            <label>Status Stok Preview:</label>
                                            <div id="stockStatus" class="mt-2">
                                                <span class="badge badge-secondary">Belum ada data</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <button type="button" class="btn btn-info" id="previewBtn">
                                                    <i class="fas fa-eye"></i> Preview
                                                </button>
                                            </div>
                                            <div>
                                                <a href="{{ route('items.index') }}" class="btn btn-secondary mr-2">
                                                    <i class="fas fa-times"></i> Batal
                                                </a>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save"></i> Simpan Item
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Preview Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Dasar</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Kode:</strong></td><td id="preview_code">-</td></tr>
                            <tr><td><strong>Nama:</strong></td><td id="preview_name">-</td></tr>
                            <tr><td><strong>Barcode:</strong></td><td id="preview_barcode">-</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Kategori & Lokasi</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Kategori:</strong></td><td id="preview_category">-</td></tr>
                            <tr><td><strong>Gudang:</strong></td><td id="preview_warehouse">-</td></tr>
                        </table>
                        
                        <h6>Stok</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Stok Minimum:</strong></td><td id="preview_min_stock">-</td></tr>
                            <tr><td><strong>Stok Awal:</strong></td><td id="preview_current_stock">-</td></tr>
                            <tr><td><strong>Status:</strong></td><td id="preview_stock_status">-</td></td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="saveFromPreview">
                    <i class="fas fa-save"></i> Simpan Item
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Generate auto code
    $('#generateCode').click(function() {
        let timestamp = Date.now().toString().slice(-6);
        let randomNum = Math.floor(Math.random() * 100).toString().padStart(2, '0');
        let autoCode = 'ITM' + timestamp + randomNum;
        $('#code').val(autoCode);
        updateStockStatus();
    });

    // Barcode scanner placeholder
    $('#scanBarcode').click(function() {
        alert('Fitur scan barcode akan segera tersedia!');
    });

    // Add category placeholder
    $('#addCategory').click(function() {
        let categoryName = prompt('Masukkan nama kategori baru:');
        if (categoryName) {
            alert('Fitur tambah kategori akan segera tersedia!\nKategori: ' + categoryName);
        }
    });

    // Add warehouse placeholder
    $('#addWarehouse').click(function() {
        let warehouseName = prompt('Masukkan nama gudang baru:');
        if (warehouseName) {
            alert('Fitur tambah gudang akan segera tersedia!\nGudang: ' + warehouseName);
        }
    });

    // Update stock status when values change
    $('#min_stock, #current_stock').on('input', function() {
        updateStockStatus();
    });

    function updateStockStatus() {
        let minStock = parseInt($('#min_stock').val()) || 0;
        let currentStock = parseInt($('#current_stock').val()) || 0;
        let statusHtml = '';

        if (currentStock <= minStock) {
            statusHtml = '<span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> Stok Rendah</span>';
        } else if (currentStock <= (minStock * 1.5)) {
            statusHtml = '<span class="badge badge-warning"><i class="fas fa-exclamation-circle"></i> Stok Peringatan</span>';
        } else {
            statusHtml = '<span class="badge badge-success"><i class="fas fa-check"></i> Stok Normal</span>';
        }

        $('#stockStatus').html(statusHtml);
    }

    // Preview functionality
    $('#previewBtn').click(function() {
        updatePreview();
        $('#previewModal').modal('show');
    });

    function updatePreview() {
        $('#preview_code').text($('#code').val() || '-');
        $('#preview_name').text($('#name').val() || '-');
        $('#preview_barcode').text($('#barcode').val() || '-');
        
        let categoryText = $('#category_id option:selected').text();
        $('#preview_category').text(categoryText === 'Pilih Kategori' ? '-' : categoryText);
        
        let warehouseText = $('#warehouse_id option:selected').text();
        $('#preview_warehouse').text(warehouseText === 'Pilih Gudang' ? '-' : warehouseText);
        
        $('#preview_min_stock').text($('#min_stock').val() || '0');
        $('#preview_current_stock').text($('#current_stock').val() || '0');
        $('#preview_stock_status').html($('#stockStatus').html());
    }

    // Save from preview
    $('#saveFromPreview').click(function() {
        $('#previewModal').modal('hide');
        $('#itemForm').submit();
    });

    // Form validation
    $('#itemForm').on('submit', function(e) {
        let code = $('#code').val().trim();
        let name = $('#name').val().trim();

        if (!code) {
            e.preventDefault();
            alert('Kode item harus diisi!');
            $('#code').focus();
            return false;
        }

        if (!name) {
            e.preventDefault();
            alert('Nama item harus diisi!');
            $('#name').focus();
            return false;
        }

        // Show loading
        $('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
    });

    // Initialize stock status
    updateStockStatus();
});
</script>
@endpush
@endsection