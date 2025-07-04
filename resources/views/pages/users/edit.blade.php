@extends('adminlte::page')

@section('title', 'Edit User')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-user-edit"></i> Edit User: {{ $user->name }}
                            </h4>
                            <div>
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('components.flash-message')

                        <form action="{{ route('users.update', $user) }}" method="POST" id="editUserForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-6">
                                    @include('pages.users.form.form-information', ['user' => $user])
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">
                                    <!-- Account Settings Section -->
                                    @include('pages.users.form.form-setting', [
                                        'user' => $user,
                                        'roles' => $roles,
                                    ])
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                        </div>
                                        <div>
                                            <a href="{{ route('users.index') }}" class="btn btn-secondary mr-2">
                                                <i class="fas fa-times"></i> Batal
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Update User
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

    @push('js')
        <script>
            console.log('Edit User Page Loaded');
            $(document).ready(function() {
                // Toggle password fields visibility
                $('#change_password').change(function() {
                    console.log('Checkbox changed:', $(this).is(':checked'));
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
                @if ($errors->has('password') || $errors->has('password_confirmation'))
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
                    $('#submitBtn').prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin"></i> Mengupdate...');
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
