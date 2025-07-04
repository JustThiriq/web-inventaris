<!-- Warehouse Name -->
<div class="form-group">
    <label for="name">Nama Gudang <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{ old('name', isset($warehouse) ? $warehouse->name : '') }}" placeholder="Masukkan nama gudang" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="form-text text-muted">Nama harus unik untuk setiap gudang</small>
</div>

<!-- Location -->
<div class="form-group">
    <label for="location">Lokasi <span class="text-danger">*</span></label>
    <textarea class="form-control @error('location') is-invalid @enderror" id="location" name="location" rows="3"
        placeholder="Masukkan alamat lokasi gudang" required>{{ old('location', isset($warehouse) ? $warehouse->location : '') }}</textarea>
    @error('location')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<!-- Status -->
<div class="form-group">
    <label for="status">Status</label>
    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
        @foreach ($statuses as $status)
            <option value="{{ $status }}"
                {{ old('status', isset($warehouse) ? $warehouse->status : '') == $status ? 'selected' : '' }}>
                {{ ucfirst($status) }}
            </option>
        @endforeach
    </select>
    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
