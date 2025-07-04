@extends('adminlte::page')

@section('title', 'Produk Request')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex w-100 align-items-center justify-content-between">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-clipboard-list"></i> Daftar Produk Request
                        </h4>
                        <a href="{{ route('produk-request.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Request
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Success/Error Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    {{-- Filter & Search --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <form method="GET" action="{{ route('produk-request.index') }}">
                                <div class="input-group">
                                    <input type="text"
                                           name="search"
                                           class="form-control"
                                           placeholder="Cari nama produk..."
                                           value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <form method="GET" action="{{ route('produk-request.index') }}">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <select name="status" class="form-control" onchange="this.form.submit()">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </form>
                        </div>
                        <div class="col-md-4 text-right">
                            <small class="text-muted">
                                Total: {{ $produkRequests->total() }} request
                            </small>
                        </div>
                    </div>

                    {{-- Data Table --}}
                    @if($produkRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="25%">Nama Produk</th>
                                        <th width="15%">Harga Estimasi</th>
                                        <th width="25%">Deskripsi</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Tanggal</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($produkRequests as $request)
                                        <tr>
                                            <td>{{ $loop->iteration + ($produkRequests->currentPage() - 1) * $produkRequests->perPage() }}</td>
                                            <td>
                                                <strong>{{ $request->nama_produk }}</strong>
                                            </td>
                                            <td>
                                                <span class="text-success font-weight-bold">
                                                    Rp {{ number_format($request->harga_estimasi, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($request->deskripsi)
                                                    <small class="text-muted">
                                                        {{ Str::limit($request->deskripsi, 50) }}
                                                    </small>
                                                @else
                                                    <em class="text-muted">Tidak ada deskripsi</em>
                                                @endif
                                            </td>
                                            <td>
                                                <div id="status-{{ $request->id }}">
                                                    {!! $request->status_badge !!}
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $request->created_at->format('d/m/Y') }}<br>
                                                    {{ $request->created_at->format('H:i') }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    {{-- View Button --}}
                                                    <button type="button"
                                                            class="btn btn-info btn-sm"
                                                            data-toggle="modal"
                                                            data-target="#detailModal{{ $request->id }}"
                                                            title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                    {{-- Status Button --}}
                                                    <button type="button"
                                                            class="btn btn-warning btn-sm"
                                                            data-toggle="modal"
                                                            data-target="#statusModal{{ $request->id }}"
                                                            title="Update Status">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    {{-- Delete Button --}}
                                                    <form action="{{ route('produk-request.destroy', $request) }}"
                                                          method="POST"
                                                          style="display: inline;"
                                                          onsubmit="return confirm('Yakin ingin menghapus request ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-danger btn-sm"
                                                                title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if($produkRequests->hasPages())
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="datatable-info">
                                        Menampilkan {{ $produkRequests->firstItem() ?? 0 }} - {{ $produkRequests->lastItem() ?? 0 }}
                                        dari {{ $produkRequests->total() }} total data
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-right">
                                        {!! $produkRequests->appends(request()->query())->links('pagination::bootstrap-4') !!}
                                    </div>
                                </div>
                            </div>
                        @else
                            @if($produkRequests->count() > 0)
                                <div class="text-muted mt-3">
                                    Total {{ $produkRequests->count() }} data request
                                </div>
                            @endif
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada produk request</h5>
                            <p class="text-muted">Silakan tambah produk request pertama Anda.</p>
                            <a href="{{ route('produk-request.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Request
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Detail Modals --}}
@foreach($produkRequests as $request)
    {{-- Detail Modal --}}
    <div class="modal fade" id="detailModal{{ $request->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{ $request->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel{{ $request->id }}">Detail Produk Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Nama Produk:</strong></td>
                            <td>{{ $request->nama_produk }}</td>
                        </tr>
                        <tr>
                            <td><strong>Harga Estimasi:</strong></td>
                            <td>Rp {{ number_format($request->harga_estimasi, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Deskripsi:</strong></td>
                            <td>{{ $request->deskripsi ?: 'Tidak ada deskripsi' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>{!! $request->status_badge !!}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Request:</strong></td>
                            <td>{{ $request->created_at->format('d F Y, H:i') }}</td>
                        </tr>
                        @if($request->catatan_admin)
                        <tr>
                            <td><strong>Catatan Admin:</strong></td>
                            <td>{{ $request->catatan_admin }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Update Modal --}}
    <div class="modal fade" id="statusModal{{ $request->id }}" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel{{ $request->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel{{ $request->id }}">Update Status: {{ $request->nama_produk }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="statusForm{{ $request->id }}" onsubmit="updateStatus(event, {{ $request->id }})">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="status{{ $request->id }}">Status</label>
                            <select name="status" id="status{{ $request->id }}" class="form-control" required>
                                <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $request->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $request->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="catatan{{ $request->id }}">Catatan Admin (Opsional)</label>
                            <textarea name="catatan_admin"
                                      id="catatan{{ $request->id }}"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Tambahkan catatan jika diperlukan">{{ $request->catatan_admin }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection

@section('js')
<script>
function updateStatus(event, requestId) {
    event.preventDefault();

    const form = document.getElementById(`statusForm${requestId}`);
    const formData = new FormData(form);
    const status = formData.get('status');
    const catatanAdmin = formData.get('catatan_admin');

    // Show loading
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
    submitBtn.disabled = true;

    fetch(`{{ url('/produk-request') }}/${requestId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            status: status,
            catatan_admin: catatanAdmin
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update status badge in table
            document.getElementById(`status-${requestId}`).innerHTML = data.status_badge;

            // Close modal
            $(`#statusModal${requestId}`).modal('hide');

            // Show success message
            showAlert('success', data.message);
        } else {
            showAlert('danger', data.error || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Terjadi kesalahan jaringan');
    })
    .finally(() => {
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;

    // Insert alert at the top of card-body
    const cardBody = document.querySelector('.card-body');
    cardBody.insertAdjacentHTML('afterbegin', alertHtml);

    // Auto dismiss after 5 seconds
    setTimeout(() => {
        const alert = cardBody.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endsection
