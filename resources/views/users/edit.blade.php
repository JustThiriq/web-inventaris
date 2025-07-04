@extends('adminlte::page')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-user-edit"></i> Edit User: {{ $user->name }}
                        </h4>
                        <div>
                            <a href="{{ route('users.show', $user) }}" class="btn btn-info mr-2">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </a>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h5><i class="fas fa-exclamation-triangle"></i> Terdapat kesalahan:</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('users.update', $user) }}" method="POST" id="editUserForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <!-- Personal Information Section -->
                                <div class="card card-outline card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="fas fa-user"></i> Informasi Personal
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name" class="required">Nama Lengkap</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" 
                                                       class="form-control @error('name') is-invalid @enderror" 
                                                       id="name" 
                                                       name="name" 
                                                       value="{{ old('name', $user->name) }}" 
                                                       placeholder="Masukkan nama lengkap"
                                                       required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="email" class="required">Email</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                </div>
                                                <input type="email" 
                                                       class="form-control @error('email') is-invalid @enderror" 
                                                       id="email" 
                                                       name="email" 
                                                       value="{{ old('email', $user->email) }}" 
                                                       placeholder="user@example.com"
                                                       required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            @if($user->email_verified_at)
                                                <small class="form-text text-success">
                                                    <i class="fas fa-check-circle"></i> Email terverifikasi pada {{ $user->email_verified_at->format('d/m/Y H:i') }}
                                                </small>
                                            @else
                                                <small class="form-text text-warning">
                                                    <i class="fas fa-exclamation-triangle"></i> Email belum diverifikasi
                                                </small>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="phone">Nomor Telepon</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                </div>
                                                <input type="text" 
                                                       class="form-control @error('phone') is-invalid @enderror" 
                                                       id="phone" 
                                                       name="phone" 
                                                       value="{{ old('phone', $user->phone) }}" 
                                                       placeholder="08xxxxxxxxxx">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- User Info Display -->
                                        <div class="form-group">
                                            <label>Informasi Akun</label>
                                            <div class="bg-light p-3 rounded">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <small class="text-muted">ID User:</small><br>
                                                        <strong>#{{ $user->id }}</strong>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted">Dibuat:</small><br>
                                                        <strong>{{ $user->created_at->format('d/m/Y H:i') }}</strong>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-6">
                                                        <small class="text-muted">Last Login:</small><br>
                                                        <strong>{{ $user->last_login ? $user->last_login->format('d/m/Y H:i') : 'Belum pernah login' }}</strong>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted">Update Terakhir:</small><br>
                                                        <strong>{{ $user->updated_at->format('d/m/Y H:i') }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <!-- Account Settings Section -->
                                <div class="card card-outline card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="fas fa-cog"></i> Pengaturan Akun
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="role_id" class="required">Role</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                                </div>
                                                <select class="form-control @error('role_id') is-invalid @enderror" 
                                                        id="role_id" 
                                                        name="role_id" 
                                                        required
                                                        {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                                    <option value="">Pilih Role</option>
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}" 
                                                                {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                                            {{ ucfirst($role->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if($user->id === auth()->id())
                                                    <input type="hidden" name="role_id" value="{{ $user->role_id }}">
                                                @endif
                                                @error('role_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            @if($user->id === auth()->id())
                                                <small class="form-text text-info">
                                                    <i class="fas fa-info-circle"></i> Anda tidak dapat mengubah role diri sendiri
                                                </small>
                                            @endif
                                        </div>

                                        <!-- Password Change Section -->
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="change_password">
                                                <label class="custom-control-label" for="change_password">
                                                    Ganti Password
                                                </label>
                                            </div>
                                        </div>

                                        <div id="passwordFields" style="display: none;">
                                            <div class="form-group">
                                                <label for="password">Password Baru</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                    </div>
                                                    <input type="password" 
                                                           class="form-control @error('password') is-invalid @enderror" 
                                                           id="password" 
                                                           name="password" 
                                                           placeholder="Kosongkan jika tidak ingin mengganti">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                            <i class="fas fa-eye" id="passwordIcon"></i>
                                                        </button>
                                                    </div>
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <small class="form-text text-muted">
                                                    Password minimal 8 karakter dengan kombinasi huruf dan angka
                                                </small>
                                            </div>

                                            <div class="form-group">
                                                <label for="password_confirmation">Konfirmasi Password Baru</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                    </div>
                                                    <input type="password" 
                                                           class="form-control" 
                                                           id="password_confirmation" 
                                                           name="password_confirmation" 
                                                           placeholder="Ulangi password baru">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                                            <i class="fas fa-eye" id="passwordConfirmIcon"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" 
                                                       class="custom-control-input" 
                                                       id="is_active" 
                                                       name="is_active" 
                                                       value="1" 
                                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                                       {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                                <label class="custom-control-label" for="is_active">
                                                    Status Aktif
                                                </label>
                                            </div>
                                            @if($user->id === auth()->id())
                                                <input type="hidden" name="is_active" value="1">
                                                <small class="form-text text-info">
                                                    <i class="fas fa-info-circle"></i> Anda tidak dapat menonaktifkan akun sendiri
                                                </small>
                                            @else
                                                <small class="form-text text-muted">
                                                    User akan dapat login jika status aktif dicentang
                                                </small>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" 
                                                       class="custom-control-input" 
                                                       id="send_notification_email" 
                                                       name="send_notification_email" 
                                                       value="1" 
                                                       {{ old('send_notification_email') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="send_notification_email">
                                                    Kirim Email Notifikasi Perubahan
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">
                                                Kirim email notifikasi kepada user tentang perubahan akun
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <!-- Action Buttons -->
                        <div class="row mt-3">
                            <div class="col-12">
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
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .required::after {
        content: " *";
        color: red;
    }
    
    .card-outline {
        border-top: 3px solid;
    }
    
    .card-outline.card-primary {
        border-top-color: #007bff;
    }
    
    .card-outline.card-info {
        border-top-color: #17a2b8;
    }
    
    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #ced4da;
    }
    
    .custom-control-label::before {
        border-color: #007bff;
    }
    
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
    
    .text-success {
        color: #28a745 !important;
    }
    
    .text-warning {
        color: #ffc107 !important;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle password fields visibility
    $('#change_password').change(function() {
        if ($(this).is(':checked')) {
            $('#passwordFields').slideDown();
            $('#password').attr('required', true);
            $('#password_confirmation').attr('required', true);
        } else {
            $('#passwordFields').slideUp();
            $('#password').attr('required', false).val('');
            $('#password_confirmation').attr('required', false).val('');
        }
    });

    // Show password fields if there are validation errors
    @if($errors->has('password') || $errors->has('password_confirmation'))
        $('#change_password').prop('checked', true);
        $('#passwordFields').show();
        $('#password').attr('required', true);
        $('#password_confirmation').attr('required', true);
    @endif

    // Toggle password visibility
    $('#togglePassword').click(function() {
        const passwordField = $('#password');
        const passwordIcon = $('#passwordIcon');
        
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            passwordIcon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            passwordIcon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    $('#togglePasswordConfirm').click(function() {
        const passwordField = $('#password_confirmation');
        const passwordIcon = $('#passwordConfirmIcon');
        
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            passwordIcon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            passwordIcon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Form validation
    $('#editUserForm').on('submit', function(e) {
        const changePassword = $('#change_password').is(':checked');
        
        if (changePassword) {
            const password = $('#password').val();
            const passwordConfirm = $('#password_confirmation').val();
            
            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak sama!');
                return false;
            }
            
            if (password.length > 0 && password.length < 8) {
                e.preventDefault();
                alert('Password minimal 8 karakter!');
                return false;
            }
        }
        
        // Show loading state
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengupdate...');
    });

    // Email validation
    $('#email').on('blur', function() {
        const email = $(this).val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            $(this).addClass('is-invalid');
            if (!$(this).siblings('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">Format email tidak valid</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').remove();
        }
    });

    // Phone number formatting
    $('#phone').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 0 && !value.startsWith('0')) {
            value = '0' + value;
        }
        $(this).val(value);
    });

    // Form reset confirmation
    $('button[type="reset"]').on('click', function(e) {
        if (!confirm('Yakin ingin mereset semua perubahan?')) {
            e.preventDefault();
        } else {
            // Reset password change checkbox and hide fields
            $('#change_password').prop('checked', false);
            $('#passwordFields').hide();
            $('#password').attr('required', false);
            $('#password_confirmation').attr('required', false);
        }
    });

    // Warn user about unsaved changes
    let formChanged = false;
    $('#editUserForm input, #editUserForm select, #editUserForm textarea').on('change input', function() {
        formChanged = true;
    });

    $(window).on('beforeunload', function() {
        if (formChanged) {
            return 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman ini?';
        }
    });

    $('#editUserForm').on('submit', function() {
        formChanged = false;
    });
});
</script>
@endpush
@endsection