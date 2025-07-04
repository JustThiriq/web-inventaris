@extends('adminlte::page')

@section('title', 'Tambah Gudang')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex w-100 align-items-center justify-content-between">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-plus"></i> Tambah Gudang Baru
                            </h4>
                            <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('components.flash-message')

                        <form action="{{ route('warehouses.store') }}" method="POST" id="addWarehouseForm">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0">Informasi Gudang</h5>
                                        </div>
                                        <div class="card-body">
                                            @include('pages.warehouses.form.warehouse', [
                                                'statuses' => ['active', 'inactive'],
                                            ])
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Informasi Manager</h5>
                                        </div>
                                        <div class="card-body">
                                            @include('pages.warehouses.form.manager')
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end">
                                        <div>
                                            <a href="{{ route('warehouses.index') }}" class="btn btn-secondary mr-2">
                                                <i class="fas fa-times"></i> Batal
                                            </a>
                                            <button type="submit" class="btn btn-primary bg-success">
                                                <i class="fas fa-save"></i> Tambah Gudang
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



    @push('js')
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
                    $('#addCategoryForm').submit();
                });

                // Form validation
                $('#addCategoryForm').on('submit', function(e) {
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
                        '<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
                });

                // Initialize stock status
                updateStockStatus();
            });
        </script>
    @endpush
@endsection
