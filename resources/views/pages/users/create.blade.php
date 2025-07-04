@extends('adminlte::page')

@section('title', 'Tambah User Baru')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex w-100 align-items-center justify-content-between">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-plus"></i> Tambah Pengguna Baru
                            </h4>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('components.flash-message')

                        <form action="{{ route('users.store') }}" method="POST" id="addUserForm">
                            @csrf

                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-6">
                                    @include('pages.users.form.form-information')
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">
                                    @include('pages.users.form.form-setting')
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end">
                                        <div>
                                            <a href="{{ route('users.index') }}" class="btn btn-secondary mr-2">
                                                <i class="fas fa-times"></i> Batal
                                            </a>
                                            <button type="submit" class="btn btn-primary bg-success">
                                                <i class="fas fa-save"></i> Tambah Pengguna
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

            .custom-control-input:checked~.custom-control-label::before {
                background-color: #007bff;
                border-color: #007bff;
            }
        </style>
    @endpush

    @push('js')
        <script>
            $(document).ready(function() {
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
                $('#addUserForm').on('submit', function(e) {
                    const password = $('#password').val();
                    const passwordConfirm = $('#password_confirmation').val();

                    if (password !== passwordConfirm) {
                        e.preventDefault();
                        alert('Password dan konfirmasi password tidak sama!');
                        return false;
                    }

                    if (password.length < 8) {
                        e.preventDefault();
                        alert('Password minimal 8 karakter!');
                        return false;
                    }

                    // Show loading state
                    $('#submitBtn').prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
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

                // Auto-generate username from email (optional)
                $('#email').on('blur', function() {
                    const email = $(this).val();
                    if (email && !$('#username').val()) {
                        const username = email.split('@')[0];
                        $('#username').val(username);
                    }
                });

                // Form reset confirmation
                $('button[type="reset"]').on('click', function(e) {
                    if (!confirm('Yakin ingin mereset semua data form?')) {
                        e.preventDefault();
                    }
                });
            });
        </script>
    @endpush
@endsection
