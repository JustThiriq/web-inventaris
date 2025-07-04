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
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                    name="name" value="{{ old('name', isset($user) ? $user?->name : '') }}"
                    placeholder="Masukkan nama lengkap" required>
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
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    name="email" value="{{ old('email', isset($user) ? $user?->email : '') }}"
                    placeholder="user@example.com" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @if (isset($user))
                @if ($user->email_verified_at)
                    <small class="form-text text-success">
                        <i class="fas fa-check-circle"></i> Email terverifikasi pada
                        {{ $user->email_verified_at->format('d/m/Y H:i') }}
                    </small>
                @else
                    <small class="form-text text-warning">
                        <i class="fas fa-exclamation-triangle"></i> Email belum diverifikasi
                    </small>
                @endif
            @endif
        </div>

        <div class="form-group">
            <label for="phone">Nomor Telepon</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                </div>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                    name="phone" value="{{ old('phone', isset($user) ? $user?->phone : '') }}"
                    placeholder="08xxxxxxxxxx">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- User Info Display -->
        @if (isset($user) && $user->exists)
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
        @endif
    </div>
</div>
