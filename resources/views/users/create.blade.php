@extends('layouts.app')

@section('title', 'Tambah User Baru')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Tambah User Baru</h4>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                               id="password" name="password" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Minimal 6 karakter</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control"
                                               id="password_confirmation" name="password_confirmation" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">Role <span class="text-danger">*</span></label>
                                    <select class="form-control @error('role') is-invalid @enderror"
                                            id="role" name="role" required>
                                        <option value="">Pilih Role</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                            Admin Warehouse
                                        </option>
                                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>
                                            User
                                        </option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input"
                                               id="is_active" name="is_active" value="1"
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            User Aktif
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        User nonaktif tidak dapat login ke sistem
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Role Description -->
                        <div class="alert alert-info" id="roleDescription" style="display: none;">
                            <strong>Deskripsi Role:</strong>
                            <div id="roleDescriptionText"></div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan User
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle password visibility
    $('#togglePassword').click(function() {
        let passwordField = $('#password');
        let type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    $('#togglePasswordConfirm').click(function() {
        let passwordField = $('#password_confirmation');
        let type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    // Role description
    $('#role').change(function() {
        let role = $(this).val();
        let description = '';

        if (role === 'admin') {
            description = `
                <ul class="mb-0">
                    <li>Dapat mengelola semua data barang dan stok</li>
                    <li>Dapat menerima dan menolak permintaan barang</li>
                    <li>Dapat mengelola user dan role</li>
                    <li>Dapat melihat semua laporan dan aktivitas</li>
                    <li>Dapat generate dan print barcode</li>
                </ul>
            `;
        } else if (role === 'user') {
            description = `
                <ul class="mb-0">
                    <li>Dapat melihat data barang dan stok</li>
                    <li>Dapat membuat permintaan barang</li>
                    <li>Dapat melihat status permintaan sendiri</li>
                    <li>Dapat scan barcode untuk cek stok</li>
                </ul>
            `;
        }

        if (description) {
            $('#roleDescriptionText').html(description);
            $('#roleDescription').show();
        } else {
            $('#roleDescription').hide();
        }
    });

    // Trigger change on page load if role is selected
    if ($('#role').val()) {
        $('#role').trigger('change');
    }

    // Form validation
    $('form').submit(function(e) {
        let password = $('#password').val();
        let passwordConfirm = $('#password_confirmation').val();

        if (password !== passwordConfirm) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak sama!');
            $('#password_confirmation').focus();
            return false;
        }
    });
});
</script>
@endpush
@endsection
