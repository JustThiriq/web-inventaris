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
                <select class="form-control @error('role_id') is-invalid @enderror" id="role_id" name="role_id"
                    required {{ isset($user) && $user->id === auth()->id() ? 'disabled' : '' }}>
                    <option value="">Pilih Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}"
                            {{ old('role_id', isset($user) ? $user->role_id : '') == $role->id ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
                @if (isset($user) && $user->id === auth()->id())
                    <input type="hidden" name="role_id" value="{{ isset($user) ? $user->role_id : '' }}">
                @endif
                @error('role_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @if (isset($user) && $user->id === auth()->id())
                <small class="form-text text-info">
                    <i class="fas fa-info-circle"></i> Anda tidak dapat mengubah role diri sendiri
                </small>
            @endif
        </div>

        <!-- Password Change Section -->
        @if (isset($user))
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="change_password">
                    <label class="custom-control-label" for="change_password">
                        Ganti Password
                    </label>
                </div>
            </div>
        @endif

        <div id="passwordFields" @if (isset($user)) style="display: none;" @endif>
            <div class="form-group">
                <label for="password">Password Baru</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    </div>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" placeholder="Kosongkan jika tidak ingin mengganti">
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
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
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
                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
                    {{ old('is_active', isset($user) ? $user->is_active : '') ? 'checked' : '' }}
                    {{ isset($user) && $user->id === auth()->id() ? 'disabled' : '' }}>
                <label class="custom-control-label" for="is_active">
                    Status Aktif
                </label>
            </div>
            @if (isset($user) && $user->id === auth()->id())
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
                <input type="checkbox" class="custom-control-input" id="send_notification_email"
                    name="send_notification_email" value="1" {{ old('send_notification_email') ? 'checked' : '' }}>
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
