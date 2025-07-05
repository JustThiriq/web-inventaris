<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Kategori & Lokasi</h5>
    </div>
    <div class="card-body">
        <!-- Category -->
        <div class="form-group">
            <label for="category_id">Kategori</label>
            <div class="input-group">
                <select class="form-control @error('category_id') is-invalid @enderror" id="category_id"
                    name="category_id">
                    <option value="">Pilih Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $item->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @if (!empty($item->category))
                <small class="form-text text-muted">
                    Kategori saat ini: <span class="badge badge-info">{{ $item->category->name }}</span>
                </small>
            @endif
        </div>

        <!-- Warehouse -->
        <div class="form-group">
            <label for="warehouse_id">Gudang</label>
            <div class="input-group">
                <select class="form-control @error('warehouse_id') is-invalid @enderror" id="warehouse_id"
                    name="warehouse_id">
                    <option value="">Pilih Gudang</option>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}"
                            {{ old('warehouse_id', $item->warehouse_id ?? '') == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error('warehouse_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @if (!empty($item->warehouse))
                <small class="form-text text-muted">
                    Gudang saat ini: <span class="badge badge-secondary">{{ $item->warehouse->name }}</span>
                </small>
            @endif
        </div>
    </div>
</div>
