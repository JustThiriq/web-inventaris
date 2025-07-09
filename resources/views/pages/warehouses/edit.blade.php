@extends('adminlte::page')

@section('title', 'Edit Lokasi Rak')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="fas fa-edit"></i> Edit Lokasi Rak: {{ $warehouse->name }}
                            </h4>
                            <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('components.flash-message')

                        <form action="{{ route('warehouses.update', $warehouse->id) }}" method="POST"
                            id="editWarehouseForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0">Informasi Lokasi Rak</h5>
                                        </div>
                                        <div class="card-body">
                                            @include('pages.warehouses.form.warehouse', [
                                                'statuses' => ['active', 'inactive'],
                                                'warehouse' => $warehouse,
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

                            <!-- Warehouse Info Summary -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="card card-outline card-info">
                                        <div class="card-header">
                                            <h5 class="mb-0">
                                                <i class="fas fa-info-circle"></i> Informasi Tambahan
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <strong>Dibuat pada:</strong><br>
                                                    <span
                                                        class="text-muted">{{ $warehouse->created_at->format('d F Y, H:i') }}</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>ID Lokasi Rak:</strong><br>
                                                    <span class="text-muted">#{{ $warehouse->id }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <a href="{{ route('warehouses.index') }}" class="btn btn-secondary mr-2">
                                                <i class="fas fa-times"></i> Batal
                                            </a>
                                            <button type="button" class="btn btn-outline-primary mr-2" id="previewBtn">
                                                <i class="fas fa-search"></i> Preview
                                            </button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-save"></i> Update Lokasi Rak
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

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="previewModalLabel">
                        <i class="fas fa-eye"></i> Preview Perubahan Lokasi Rak
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary"><i class="fas fa-warehouse"></i> Informasi Lokasi Rak</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Nama Lokasi Rak:</strong></td>
                                    <td id="preview_name">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Lokasi:</strong></td>
                                    <td id="preview_location">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Manager:</strong></td>
                                    <td id="preview_manager">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary"><i class="fas fa-phone"></i> Kontak & Status</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Kontak:</strong></td>
                                    <td id="preview_phone">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td id="preview_status">-</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                    <button type="button" class="btn btn-success" id="saveFromPreview">
                        <i class="fas fa-save"></i> Update Lokasi Rak
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            $(document).ready(function() {
                // Phone number formatting
                $('#phone').on('input', function() {
                    let value = $(this).val().replace(/\D/g, '');
                    if (value.startsWith('0')) {
                        $(this).val(value);
                    } else if (value.startsWith('62')) {
                        $(this).val('+' + value);
                    }
                });

                // Preview functionality
                $('#previewBtn').click(function() {
                    updatePreview();
                    $('#previewModal').modal('show');
                });

                function updatePreview() {
                    $('#preview_name').text($('#name').val() || '-');
                    $('#preview_location').text($('#location').val() || '-');
                    $('#preview_manager').text($('#manager_name').val() || '-');
                    $('#preview_phone').text($('#phone').val() || '-');

                    let statusText = $('#status option:selected').text();
                    let statusClass = $('#status').val() === 'active' ? 'success' : 'secondary';
                    $('#preview_status').html('<span class="badge badge-' + statusClass + '">' + statusText +
                        '</span>');
                }

                // Save from preview
                $('#saveFromPreview').click(function() {
                    $('#previewModal').modal('hide');
                    $('#editWarehouseForm').submit();
                });

                // Form validation
                $('#editWarehouseForm').on('submit', function(e) {
                    let name = $('#name').val().trim();
                    let location = $('#location').val().trim();
                    let manager = $('#manager_name').val().trim();
                    let phone = $('#phone').val().trim();

                    if (!name || !location || !manager || !phone) {
                        e.preventDefault();
                        alert('Semua field yang wajib harus diisi!');
                        return false;
                    }

                    // Show loading
                    $('button[type="submit"]').prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
                });

                // Auto-focus on first input
                $('#name').focus();
            });
        </script>
    @endpush
@endsection
