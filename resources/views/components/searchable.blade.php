<div class="mb-3">
    <form action="{{ route($route) }}" method="GET" class="form-inline">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari Kategori"
                value="{{ request('search') }}" aria-label="Cari Kategori">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>
        </div>
    </form>
</div>
