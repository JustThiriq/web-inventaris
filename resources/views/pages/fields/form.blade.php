<!-- Field Name -->
<div class="form-group">
    <label for="name">Nama Bidang <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{ $field?->name ?? old('name') }}" placeholder="Masukkan nama Bidang" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>