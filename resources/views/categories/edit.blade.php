@extends('adminlte::page')

@section('title', 'Edit Item')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-edit"></i> Edit Item: {{ $item->name }}
                    </h4>
                    <div>
                        <a href="{{ route('items.show', $item) }}" class="btn btn-info mr-2">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        <a href="{{ route('items.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
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

                    <!-- Item Info Alert -->
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Informasi Item</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Dibuat:</strong> {{ $item->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="col-md-3">
                                <strong>Terakhir Update:</strong> {{ $item->updated_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="col-md-3">
                                <strong>Total Request:</strong> {{ $item->item_requests->count() }} request
                            </div>
                            <div class="col-md-3">
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
                                <strong>Status Stok:</strong> 
                                <span class="badge {{ $stockBadge }}">{{ $stockText }}</span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('items.update', $item) }}" method="POST" id="itemForm">
                        @csrf
                        @method('PUT')
                        
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
                                                       value="{{ old('code', $item->code) }}" 
                                                       placeholder="Masukkan kode item"
                                                       required>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary" id="generateCode" title="Generate Kode Baru">
                                                        <i class="fas fa-magic"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @error('code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Kode harus unik untuk setiap item
                                                @if($item->code !== old('code', $item->code))
                                                    <br><span class="text-info">Kode asli: {{ $item->code }}</span>
                                                @endif
                                            </small>
                                        </div>

                                        <!-- Item Name -->
                                        <div class="form-group">
                                            <label for="name">Nama Item <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" 
                                                   name="name" 
                                                   value="{{ old('name', $item->name) }}" 
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
                                                       value="{{ old('barcode', $item->barcode) }}" 
                                                       placeholder="Masukkan barcode (opsional)">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary" id="scanBarcode" title="Scan Barcode">
                                                        <i class="fas fa-barcode"></i>
                                                    </button>
                                                    @if($item->barcode)
                                                        <button type="button" class="btn btn-outline-danger" id="clearBarcode" title="Hapus Barcode">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                            @error('barcode')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Barcode akan digunakan untuk scanning
                                                @if($item->barcode && $item->barcode !== old('barcode', $item->barcode))
                                                    <br><span class="text-info">Barcode asli: {{ $item->barcode }}</span>
                                                @endif
                                            </small>
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
                                                        <option value="{{ $category->id }}" 
                                                                {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
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
                                            @if($item->category)
                                                <small class="form-text text-muted">
                                                    Kategori saat ini: <span class="badge badge-info">{{ $item->category->name }}</span>
                                                </small>
                                            @endif
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
                                                        <option value="{{ $warehouse->id }}" 
                                                                {{ old('warehouse_id', $item->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
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
                                            @if($item->warehouse)
                                                <small class="form-text text-muted">
                                                    Gudang saat ini: <span class="badge badge-secondary">{{ $item->warehouse->name }}</span>
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">Manajemen Stok</h5>
                                        <button type="button" class="btn btn-sm btn-outline-info" id="stockHistory" title="Lihat History Stok">
                                            <i class="fas fa-history"></i> History
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <!-- Stock Comparison -->
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <div class="small-box bg-info">
                                                    <div class="inner">
                                                        <h3>{{ $item->min_stock ?? 0 }}</h3>
                                                        <p>Stok Min Saat Ini</p>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="small-box bg-success">
                                                    <div class="inner">
                                                        <h3>{{ $item->current_stock ?? 0 }}</h3>
                                                        <p>Stok Aktual Saat Ini</p>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="fas fa-boxes"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Min Stock -->
                                        <div class="form-group">
                                            <label for="min_stock">Stok Minimum</label>
                                            <div class="input-group">
                                                <input type="number" 
                                                       class="form-control @error('min_stock') is-invalid @enderror" 
                                                       id="min_stock" 
                                                       name="min_stock" 
                                                       value="{{ old('min_stock', $item->min_stock) }}" 
                                                       min="0" 
                                                       placeholder="0">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">pcs</span>
                                                    <button type="button" class="btn btn-outline-secondary" id="suggestMinStock" title="Saran Stok Minimum">
                                                        <i class="fas fa-lightbulb"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @error('min_stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Stok minimum untuk peringatan</small>
                                        </div>

                                        <!-- Current Stock -->
                                        <div class="form-group">
                                            <label for="current_stock">Stok Saat Ini</label>
                                            <div class="input-group">
                                                <input type="number" 
                                                       class="form-control @error('current_stock') is-invalid @enderror" 
                                                       id="current_stock" 
                                                       name="current_stock" 
                                                       value="{{ old('current_stock', $item->current_stock) }}" 
                                                       min="0" 
                                                       placeholder="0">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">pcs</span>
                                                    <button type="button" class="btn btn-outline-warning" id="stockAdjustment" title="Penyesuaian Stok">
                                                        <i class="fas fa-calculator"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @error('current_stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Stok aktual saat ini
                                                @php
                                                    $stockDiff = (old('current_stock', $item->current_stock) ?? 0) - ($item->current_stock ?? 0);
                                                @endphp
                                                @if($stockDiff != 0)
                                                    <br><span class="text-{{ $stockDiff > 0 ? 'success' : 'danger' }}">
                                                        {{ $stockDiff > 0 ? '+' : '' }}{{ $stockDiff }} dari stok asli
                                                    </span>
                                                @endif
                                            </small>
                                        </div>

                                        <!-- Stock Status Preview -->
                                        <div class="form-group">
                                            <label>Status Stok Preview:</label>
                                            <div id="stockStatus" class="mt-2">
                                                <span class="badge badge-secondary">Loading...</span>
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
                                                    <i class="fas fa-eye"></i> Preview Perubahan
                                                </button>
                                                <button type="button" class="btn btn-warning" id="resetBtn">
                                                    <i class="fas fa-undo"></i> Reset ke Asli
                                                </button>
                                            </div>
                                            <div>
                                                <a href="{{ route('items.index') }}" class="btn btn-secondary mr-2">
                                                    <i class="fas fa-times"></i> Batal
                                                </a>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save"></i> Update Item
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

<!-- Preview Changes Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Preview Perubahan Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Data Lama</h6>
                        <table class="table table-sm table-bordered">
                            <tr><td><strong>Kode:</strong></td><td>{{ $item->code }}</td></tr>
                            <tr><td><strong>Nama:</strong></td><td>{{ $item->name }}</td></tr>
                            <tr><td><strong>Barcode:</strong></td><td>{{ $item->barcode ?: '-' }}</td></tr>
                            <tr><td><strong>Kategori:</strong></td><td>{{ $item->category->name ?? '-' }}</td></tr>
                            <tr><td><strong>Gudang:</strong></td><td>{{ $item->warehouse->name ?? '-' }}</td></tr>
                            <tr><td><strong>Stok Min:</strong></td><td>{{ $item->min_stock ?? 0 }}</td></tr>
                            <tr><td><strong>Stok Saat Ini:</strong></td><td>{{ $item->current_stock ?? 0 }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Data Baru</h6>
                        <table class="table table-sm table-bordered">
                            <tr><td><strong>Kode:</strong></td><td id="preview_code">-</td></tr>
                            <tr><td><strong>Nama:</strong></td><td id="preview_name">-</td></tr>
                            <tr><td><strong>Barcode:</strong></td><td id="preview_barcode">-</td></tr>
                            <tr><td><strong>Kategori:</strong></td><td id="preview_category">-</td></tr>
                            <tr><td><strong>Gudang:</strong></td><td id="preview_warehouse">-</td></tr>
                            <tr><td><strong>Stok Min:</strong></td><td id="preview_min_stock">-</td></tr>
                            <tr><td><strong>Stok Saat Ini:</strong></td><td id="preview_current_stock">-</td></tr>
                        </table>
                        
                        <h6>Status Stok Baru</h6>
                        <div id="preview_stock_status">-</div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <h6>Ringkasan Perubahan</h6>
                    <div id="changesSummary" class="alert alert-info">
                        <ul id="changesList"></ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="saveFromPreview">
                    <i class="fas fa-save"></i> Update Kategori
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Original values for reset
    const originalValues = {
        code: '{{ $item->code }}',
        name: '{{ $item->name }}',
        barcode: '{{ $item->barcode }}',
        category_id: '{{ $item->category_id }}',
        warehouse_id: '{{ $item->warehouse_id }}',
        min_stock: '{{ $item->min_stock }}',
        current_stock: '{{ $item->current_stock }}'
    };

    // Generate new code
    $('#generateCode').click(function() {
        if(confirm('Generate kode baru? Kode lama akan diganti.')) {
            let timestamp = Date.now().toString().slice(-6);
            let randomNum = Math.floor(Math.random() * 100).toString().padStart(2, '0');
            let autoCode = 'ITM' + timestamp + randomNum;
            $('#code').val(autoCode);
            updateStockStatus();
        }
    });

    // Clear barcode
    $('#clearBarcode').click(function() {
        if(confirm('Hapus barcode?')) {
            $('#barcode').val('');
        }
    });

    // Reset to original values
    $('#resetBtn').click(function() {
        if(confirm('Reset semua field ke nilai asli?')) {
            Object.keys(originalValues).forEach(key => {
                $('#' + key).val(originalValues[key]);
            });
            updateStockStatus();
        }
    });

    // Stock adjustment
    $('#stockAdjustment').click(function() {
        $('#stockAdjustmentModal').modal('show');
    });

    $('#adjustment_type, #adjustment_value').on('change input', function() {
        calculateAdjustment();
    });

    function calculateAdjustment() {
        let currentStock = {{ $item->current_stock ?? 0 }};
        let type = $('#adjustment_type').val();
        let value = parseInt($('#adjustment_value').val()) || 0;
        let result = currentStock;

        if(type === 'add') {
            result = currentStock + value;
        } else if(type === 'subtract') {
            result = Math.max(0, currentStock - value);
        } else if(type === 'set') {
            result = value;
        }

        $('#adjustment_result').text(result);
    }

    $('#applyAdjustment').click(function() {
        let result = parseInt($('#adjustment_result').text());
        $('#current_stock').val(result);
        $('#stockAdjustmentModal').modal('hide');
        updateStockStatus();
    });

    // Suggest minimum stock
    $('#suggestMinStock').click(function() {
        let currentStock = parseInt($('#current_stock').val()) || 0;
        let suggested = Math.ceil(currentStock * 0.2); // 20% of current stock
        if(confirm(`Saran stok minimum: ${suggested} (20% dari stok saat ini). Gunakan nilai ini?`)) {
            $('#min_stock').val(suggested);
            updateStockStatus();
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

        // Show changes
        let changes = [];
        Object.keys(originalValues).forEach(key => {
            let current = $('#' + key).val() || '';
            let original = originalValues[key] || '';
            if(current !== original) {
                let fieldName = key.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                changes.push(`<li><strong>${fieldName}:</strong> "${original}" → "${current}"</li>`);
            }
        });

        if(changes.length > 0) {
            $('#changesList').html(changes.join(''));
            $('#changesSummary').removeClass('alert-info').addClass('alert-warning');
        } else {
            $('#changesList').html('<li>Tidak ada perubahan</li>');
            $('#changesSummary').removeClass('alert-warning').addClass('alert-info');
        }
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
        $('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memperbarui...');
    });

    // Stock history placeholder
    $('#stockHistory').click(function() {
        alert('Fitur history stok akan segera tersedia!');
    });

    // Initialize
    updateStockStatus();
});
</script>
@endpush
@endsection