<!-- Field Name -->

<div class="form-group">
    <label for="npwp">NPWP <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('npwp') is-invalid @enderror" id="npwp" name="npwp"
        value="{{ $supplier?->npwp ?? old('npwp') }}" placeholder="Masukkan NPWP" required>
    @error('npwp')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="name">Nama Supplier <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{ $supplier?->name ?? old('name') }}" placeholder="Masukkan nama Supplier" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="phone">No HP <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
        value="{{ $supplier?->phone ?? old('phone') }}" placeholder="Masukkan nama Supplier" required>
    @error('phone')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="address">Alamat <span class="text-danger">*</span></label>
    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3"
        placeholder="Masukkan alamat Supplier" required>{{ $supplier?->address ?? old('address') }}</textarea>
    @error('address')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>