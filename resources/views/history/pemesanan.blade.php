@extends('adminlte::page')

@section('title', 'Riwayat Pemesanan')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex w-100 align-items-center justify-content-between">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-clipboard-list"></i> Riwayat Pemesanan
                            </h4>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Success/Error Messages --}}
                        @include('components.flash-message')

                        {{-- Filter & Search --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <form method="GET" action="{{ route('pemesanan.index') }}">
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
                                    Total: {{ $items->total() }} request
                                </small>
                            </div>
                        </div>

                        {{-- Data Table --}}
                        @if ($items->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="25%">NO PO</th>
                                            <th width="15%">Tgl Pemesanan</th>
                                            <th width="15%">Tgl Kedatangan</th>
                                            <th width="15%">Tgl Dipakai</th>
                                            <th width="10%">Status</th>
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr>
                                                <td>{{ $loop->iteration + ($items->currentPage() - 1) * $items->perPage() }}
                                                </td>
                                                <td>
                                                    <strong>{{ $item->no_po }}</strong>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $item->tanggal_pemesanan ? $item->tanggal_pemesanan->format('d F Y') : '-' }}
                                                        <br>
                                                        {{ $item->tanggal_pemesanan ? $item->tanggal_pemesanan->format('H:i') : '-' }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $item->tanggal_kedatangan ? $item->tanggal_kedatangan->format('d F Y') : '-' }}
                                                        <br>
                                                        {{ $item->tanggal_kedatangan ? $item->tanggal_kedatangan->format('H:i') : '-' }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $item->tanggal_dipakai ? $item->tanggal_dipakai->format('d F Y') : '-' }}
                                                        <br>
                                                        {{ $item->tanggal_dipakai ? $item->tanggal_dipakai->format('H:i') : '-' }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $item->status_class }}">
                                                        {{ $item->status_label }}
                                                    </span>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            @if ($items->hasPages())
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="datatable-info">
                                            Menampilkan {{ $items->firstItem() ?? 0 }} -
                                            {{ $items->lastItem() ?? 0 }}
                                            dari {{ $items->total() }} total data
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="float-right">
                                            {!! $items->appends(request()->query())->links('pagination::bootstrap-4') !!}
                                        </div>
                                    </div>
                                </div>
                            @else
                                @if ($items->count() > 0)
                                    <div class="text-muted mt-3">
                                        Total {{ $items->count() }} data request
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada Riwayat Pemesanan</h5>
                                <p class="text-muted">Silakan tambah Pemesanan pertama Anda.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($items as $produkRequest)
        {{-- Detail Modal --}}
        <div class="modal fade" id="detailModal{{ $produkRequest->id }}" tabindex="-1" role="dialog"
            aria-labelledby="detailModalLabel{{ $produkRequest->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document"> {{-- modal-lg untuk tabel lebih lebar --}}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Pemesanan</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="30%"><strong>Nomor Request:</strong></td>
                                <td>{{ $produkRequest->request_number }}</td>
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
        {{-- <div class="modal fade" id="statusModal{{ $produkRequest->id }}" tabindex="-1" role="dialog"
            aria-labelledby="statusModalLabel{{ $produkRequest->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="POST" action="{{ route('pemesanan.update-status', $produkRequest) }}">
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
        </div> --}}
    @endforeach

@endsection
