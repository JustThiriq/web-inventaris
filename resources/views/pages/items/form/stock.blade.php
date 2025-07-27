<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Manajemen Stok</h5>
        <span>Untuk barang non consumable otomatis akan menjadi 0</span>
    </div>
    <div class="card-body">
        @if (isset($item))
            <!-- Stock Comparison -->
            <div class="row mb-3">
                <div class="col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $item->min_stock ?? 0 }}</h3>
                            <p>Stok Min Saat Ini</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $item->current_stock ?? 0 }}</h3>
                            <p>Stok Aktual Saat Ini</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Current Stock -->
        <div class="form-group">
            <label for="current_stock">Stok Saat Ini</label>
            <div class="input-group">
                <input type="number" class="form-control @error('current_stock') is-invalid @enderror"
                    id="current_stock" name="current_stock"
                    value="{{ old('current_stock', $item->current_stock ?? '') }}" min="0" placeholder="0">
                <div class="input-group-append">
                    <span class="input-group-text">pcs</span>
                    @if (isset($item))
                        <button type="button" class="btn btn-outline-warning" id="stockAdjustment"
                            title="Penyesuaian Stok">
                            <i class="fas fa-calculator"></i>
                        </button>
                    @endif
                </div>
            </div>
            @error('current_stock')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted">
                Stok aktual saat ini
                @php
                    $current = old('current_stock', $item->current_stock ?? 0);
                    $original = $item->current_stock ?? 0;
                    $stockDiff = $current - $original;
                @endphp
                @if ($stockDiff != 0)
                    <br><span class="text-{{ $stockDiff > 0 ? 'success' : 'danger' }}">
                        {{ $stockDiff > 0 ? '+' : '' }}{{ $stockDiff }} dari stok asli
                    </span>
                @endif
            </small>
        </div>

        <!-- Min Stock -->
        <div class="form-group">
            <label for="min_stock">Stok Minimum</label>
            <div class="input-group">
                <input type="number" class="form-control @error('min_stock') is-invalid @enderror" id="min_stock"
                    name="min_stock" value="{{ old('min_stock', $item->min_stock ?? '') }}" min="0"
                    placeholder="0">
                <div class="input-group-append">
                    <span class="input-group-text">pcs</span>
                    <button type="button" class="btn btn-outline-secondary" id="suggestMinStock"
                        title="Saran Stok Minimum">
                        <i class="fas fa-lightbulb"></i>
                    </button>
                </div>
            </div>
            @error('min_stock')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted">Stok minimum untuk peringatan</small>
        </div>

        @if (isset($item))
            <!-- Stock Status Preview -->
            <div class="form-group">
                <label>Status Stok Preview:</label>
                <div id="stockStatus" class="mt-2">
                    <span class="badge badge-secondary">Loading...</span>
                </div>
            </div>
        @endif
    </div>
</div>
