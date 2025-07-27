@extends('adminlte::page')

@section('title', 'Edit Item')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-edit"></i> Edit Item: {{ $item->name }}
                            </h4>
                            <div>
                                <a href="{{ route('items.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('components.flash-message')

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
                                {{-- <div class="col-md-3">
                                    <strong>Total Request:</strong> {{ $item->item_requests->count() }} request
                                </div> --}}
                                {{-- <div class="col-md-3">
                                    <strong>Status Stok:</strong>
                                    <span class="badge {{ $item->badgeLevel }}">{{ $item->badgeLabel }}</span>
                                </div> --}}
                            </div>
                        </div>

                        <form action="{{ route('items.update', $item) }}" method="POST" id="itemForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-12">
                                    @include('pages.items.form.information')
                                    @include('pages.items.form.category-warehouse')
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">

                                    @include('pages.items.form.stock')
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <div>
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Adjustment Modal -->
    <div class="modal fade" id="stockAdjustmentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Penyesuaian Stok</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Stok Saat Ini: <strong>{{ $item->current_stock ?? 0 }}</strong></label>
                    </div>
                    <div class="form-group">
                        <label for="adjustment_type">Jenis Penyesuaian:</label>
                        <select class="form-control" id="adjustment_type">
                            <option value="add">Tambah Stok (+)</option>
                            <option value="subtract">Kurangi Stok (-)</option>
                            <option value="set">Set Stok Langsung</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="adjustment_value">Nilai:</label>
                        <input type="number" class="form-control" id="adjustment_value" min="0"
                            placeholder="0">
                    </div>
                    <div class="form-group">
                        <label>Hasil: <span id="adjustment_result" class="font-weight-bold">-</span></label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="applyAdjustment">Terapkan</button>
                </div>
            </div>
        </div>
    </div>

    @push('js')
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
                    if (confirm('Generate kode baru? Kode lama akan diganti.')) {
                        let timestamp = Date.now().toString().slice(-6);
                        let randomNum = Math.floor(Math.random() * 100).toString().padStart(2, '0');
                        let autoCode = 'ITM' + timestamp + randomNum;
                        $('#code').val(autoCode);
                        updateStockStatus();
                    }
                });

                // Clear barcode
                $('#clearBarcode').click(function() {
                    if (confirm('Hapus barcode?')) {
                        $('#barcode').val('');
                    }
                });

                // Reset to original values
                $('#resetBtn').click(function() {
                    if (confirm('Reset semua field ke nilai asli?')) {
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

                    if (type === 'add') {
                        result = currentStock + value;
                    } else if (type === 'subtract') {
                        result = Math.max(0, currentStock - value);
                    } else if (type === 'set') {
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
                    if (confirm(
                            `Saran stok minimum: ${suggested} (20% dari stok saat ini). Gunakan nilai ini?`)) {
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
                        statusHtml =
                            '<span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> Stok Rendah</span>';
                    } else if (currentStock <= (minStock * 1.5)) {
                        statusHtml =
                            '<span class="badge badge-warning"><i class="fas fa-exclamation-circle"></i> Stok Peringatan</span>';
                    } else {
                        statusHtml =
                            '<span class="badge badge-success"><i class="fas fa-check"></i> Stok Normal</span>';
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
                    $('#preview_category').text(categoryText === 'Pilih Jenis' ? '-' : categoryText);

                    let warehouseText = $('#warehouse_id option:selected').text();
                    $('#preview_warehouse').text(warehouseText === 'Pilih Lokasi Rak' ? '-' : warehouseText);

                    $('#preview_min_stock').text($('#min_stock').val() || '0');
                    $('#preview_current_stock').text($('#current_stock').val() || '0');
                    $('#preview_stock_status').html($('#stockStatus').html());

                    // Show changes
                    let changes = [];
                    Object.keys(originalValues).forEach(key => {
                        let current = $('#' + key).val() || '';
                        let original = originalValues[key] || '';
                        if (current !== original) {
                            let fieldName = key.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                            changes.push(
                                `<li><strong>${fieldName}:</strong> "${original}" → "${current}"</li>`);
                        }
                    });

                    if (changes.length > 0) {
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
                    $('button[type="submit"]').prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin"></i> Memperbarui...');
                });

                // Initialize
                updateStockStatus();
            });
        </script>
    @endpush
@endsection
