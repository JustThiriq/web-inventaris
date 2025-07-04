<!-- Category Name -->
<div class="form-group">
    <label for="name">Nama Kategori <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{ $category?->name ?? old('name') }}" placeholder="Masukkan nama kategori" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<!-- Category Description -->
<div class="form-group">
    <label for="description">Deskripsi <span class="text-danger">*</span></label>
    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
        placeholder="Masukkan deskripsi kategori" required>{{ $category?->description ?? old('description') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
