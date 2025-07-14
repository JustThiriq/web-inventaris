<!-- unit Name -->
<div class="form-group">
    <label for="name">Nama Satuan <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{ $unit?->name ?? old('name') }}" placeholder="Masukkan nama Satuan" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>