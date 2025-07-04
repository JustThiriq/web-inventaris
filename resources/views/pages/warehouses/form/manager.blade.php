<!-- Manager Name -->
<div class="form-group">
    <label for="manager_name">Nama Manager <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('manager_name') is-invalid @enderror" id="manager_name"
        name="manager_name" value="{{ old('manager_name', isset($warehouse) ? $warehouse->manager_name : '') }}"
        placeholder="Masukkan nama manager" required>
    @error('manager_name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<!-- Phone Contact -->
<div class="form-group">
    <label for="phone">Kontak <span class="text-danger">*</span></label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-phone"></i></span>
        </div>
        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
            value="{{ old('phone', isset($warehouse) ? $warehouse->phone : '') }}" placeholder="Masukkan nomor telepon"
            required>
    </div>
    @error('phone')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="form-text text-muted">Format: 08xxxxxxxxxx atau +62xxxxxxxxxx</small>
</div>
