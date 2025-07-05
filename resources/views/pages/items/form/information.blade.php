<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Informasi Dasar</h5>
    </div>
    <div class="card-body">
        <!-- Item Code -->
        <div class="form-group">
            <label for="code">Kode Item <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code"
                    name="code" value="{{ old('code', $item->code ?? '') }}" placeholder="Masukkan kode item"
                    required>
                <div class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary" id="generateCode"
                        title="Generate Kode Baru">
                        <i class="fas fa-magic"></i>
                    </button>
                </div>
            </div>
            @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted">
                Kode harus unik untuk setiap item
                @if (($item->code ?? '') !== old('code', $item->code ?? ''))
                    <br><span class="text-info">Kode asli: {{ $item->code ?? '-' }}</span>
                @endif
            </small>
        </div>

        <!-- Item Name -->
        <div class="form-group">
            <label for="name">Nama Item <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                value="{{ old('name', $item->name ?? '') }}" placeholder="Masukkan nama item" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
