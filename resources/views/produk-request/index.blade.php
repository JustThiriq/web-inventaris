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
                            @if (auth()->user()->role()?->slug === 'user')
                                <a href="{{ route('produk-request.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Request
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Success/Error Messages --}}
                        @include('components.flash-message')

                        {{-- Filter & Search --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <form method="GET" action="{{ route('produk-request.index') }}">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari nama produk..." value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-4 text-right">
                                <small class="text-muted">
                                    Total: {{ $produkRequests->total() }} request
                                </small>
                            </div>
                        </div>

                        {{-- Data Table --}}
                        @if ($produkRequests->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="25%">Request Number</th>
                                            <th width="15%">Deskripsi</th>
                                            <th width="10%">Status</th>
                                            <th width="10%">Tanggal</th>
                                            @if (auth()->user()->role->slug !== 'user')
                                                <th>Pemesanan</th>
                                            @endif
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($produkRequests as $request)
                                            <tr>
                                                <td>{{ $loop->iteration + ($produkRequests->currentPage() - 1) * $produkRequests->perPage() }}
                                                </td>
                                                <td>
                                                    <strong>{{ $request->request_number }}</strong>
                                                </td>
                                                <td>
                                                    @if ($request->description)
                                                        <small class="text-muted">
                                                            {{ Str::limit($request->description, 50) }}
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
                                                        {{ $request->request_date->format('d F Y') }}<br>
                                                        {{ $request->request_date->format('H:i') }}
                                                    </small>
                                                </td>
                                                @if (auth()->user()->role->slug !== 'user')
                                                    <td>
                                                        @if ($request->pemesanan()->exists())
                                                            <a href="{{ route('pemesanan.edit', $request->pemesanan->id) }}"
                                                                class="btn btn-sm btn-primary">Lihat Pemesanan</a>
                                                        @else
                                                            <span class="badge badge-danger">Belum Ada Pemesanan</span>
                                                        @endif
                                                    </td>
                                                @endif
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        {{-- View Button --}}
                                                        <button type="button" class="btn btn-info btn-sm"
                                                            data-toggle="modal"
                                                            data-target="#detailModal{{ $request->id }}"
                                                            title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        @if ($request->status === 'pending' && auth()->user()?->role?->slug !== 'user')
                                                            {{-- Status Button --}}
                                                            <button type="button" class="btn btn-warning btn-sm"
                                                                data-toggle="modal"
                                                                data-target="#statusModal{{ $request->id }}"
                                                                title="Update Status">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        @endif

                                                        {{-- Delete Button --}}
                                                        <form action="{{ route('produk-request.destroy', $request) }}"
                                                            method="POST" style="display: inline;"
                                                            id="deleteForm{{ $request->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                        <button type="button" class="btn btn-danger btn-sm" title="Hapus"
                                                            onclick="event.preventDefault(); if(confirm('Yakin ingin menghapus request ini?')) { document.getElementById('deleteForm{{ $request->id }}').submit(); }">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            @if ($produkRequests->hasPages())
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="datatable-info">
                                            Menampilkan {{ $produkRequests->firstItem() ?? 0 }} -
                                            {{ $produkRequests->lastItem() ?? 0 }}
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
                                @if ($produkRequests->count() > 0)
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
                                @if (auth()->user()->role()?->slug === 'user')
                                    <a href="{{ route('produk-request.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Request
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($produkRequests as $produkRequest)
        {{-- Detail Modal --}}
        <div class="modal fade" id="detailModal{{ $produkRequest->id }}" tabindex="-1" role="dialog"
            aria-labelledby="detailModalLabel{{ $produkRequest->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document"> {{-- modal-lg untuk tabel lebih lebar --}}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Produk Request</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="30%"><strong>Nomor Request:</strong></td>
                                <td>{{ $produkRequest->request_number }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Request:</strong></td>
                                <td>{{ $produkRequest->request_date->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Deskripsi:</strong></td>
                                <td>{{ $produkRequest->description ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>{!! $produkRequest->status_badge !!}</td>
                            </tr>
                            @if ($produkRequest->admin_notes)
                                <tr>
                                    <td><strong>Catatan Admin:</strong></td>
                                    <td>{{ $produkRequest->admin_notes }}</td>
                                </tr>
                            @endif
                        </table>

                        <hr>

                        <h6><strong>Detail Produk</strong></h6>
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Item</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produkRequest->details as $index => $detail)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $detail->item->name ?? '-' }}</td>
                                        <td>{{ number_format($detail->quantity, 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Modal --}}
        <div class="modal fade" id="statusModal{{ $produkRequest->id }}" tabindex="-1" role="dialog"
            aria-labelledby="statusModalLabel{{ $produkRequest->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="POST" action="{{ route('produk-request.update-status', $produkRequest) }}">
                    @csrf
                    @method('PUT')

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Status</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="approved"
                                        {{ $produkRequest->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected"
                                        {{ $produkRequest->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Catatan Admin (Opsional)</label>
                                <textarea name="admin_notes" class="form-control" rows="3">{{ $produkRequest->admin_notes }}</textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
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
