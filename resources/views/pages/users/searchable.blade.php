<div class="row mb-3">
    <div class="col-md-3">
        <select name="role" id="roleFilter" class="form-control">
            <option value="">Semua Role</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                    {{ ucfirst($role->name) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select name="status" id="statusFilter" class="form-control">
            <option value="">Semua Status</option>
            @foreach ($statuses as $key => $status)
                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                    {{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input type="text" name="search" id="searchInput" class="form-control"
                placeholder="Cari nama atau email..." value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <a href="{{ route('users.index') }}" class="btn btn-secondary">
            <i class="fas fa-refresh"></i> Reset
        </a>
    </div>
</div>
