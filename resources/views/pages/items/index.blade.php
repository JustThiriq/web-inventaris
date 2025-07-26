@extends('adminlte::page')

@section('title', 'Manajemen Items')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex w-100 align-items-center justify-content-between">
                            <h4 class="card-title mb-0">
                                Manajemen Item
                            </h4>
                            <div>
                                <button class="btn btn-info" data-toggle="modal" data-target="#scanBarcodeModal">
                                    <i class="fas fa-camera"></i>
                                    <span class="d-none d-md-inline">Scan Barcode</span>
                                </button>
                                <a href="{{ route('items.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i>
                                    <span class="d-none d-md-inline">Tambah Item</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Filter & Search -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select name="category_id" id="categoryFilter" class="form-control">
                                    <option value="">Semua Jenis</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="warehouse_id" id="warehouseFilter" class="form-control">
                                    <option value="">Semua Lokasi Rak</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}"
                                            {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" name="search" id="searchInput" class="form-control"
                                        placeholder="Cari kode atau nama item..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <a href="{{ route('items.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-refresh"></i> Reset
                                </a>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="min-width: 10px">#</th>
                                        <th style="min-width: 150px">Stokcode</th>
                                        <th style="min-width: 150px">Deskripsi</th>
                                        <th style="min-width: 150px">Jenis</th>
                                        <th style="min-width: 150px">Lokasi Rak</th>
                                        <th style="min-width: 150px">Barcode</th>
                                        <th style="min-width: 150px">Stok</th>
                                        <th style="min-width: 150px">Satuan</th>
                                        <th style="min-width: 150px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($items as $item)
                                        <tr class="{{ $item->current_stock <= $item->min_stock ? 'table-warning' : '' }}">
                                            <td>{{ $loop->iteration + ($items->currentPage() - 1) * $items->perPage() }}
                                            </td>
                                            <td>
                                                <strong>{{ $item->code }}</strong>
                                            </td>
                                            <td>
                                                {{ $item->name }}
                                            </td>
                                            <td>
                                                @if ($item->category)
                                                    <span class="badge badge-info">{{ $item->category->name }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->warehouse)
                                                    <span class="badge badge-secondary">{{ $item->warehouse->name }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <img src="{{ $item->barcodeUrl }}" alt="Barcode" class="img-fluid"
                                                    style="max-width: 100px;">
                                            </td>
                                            <td>
                                                @if ($item->category->name === 'Non Consumable')
                                                    <span class="text-muted">
                                                        Tidak tersedia
                                                    </span>
                                                @else
                                                    {{ $item->currentStok }}
                                                @endif
                                            </td>

                                            <td>
                                                <span class="badge badge-success" data-toggle="tooltip">
                                                    {{ $item->unit->name ?? '-' }}
                                                </span>
                                            </td>

                                            <td>
                                                <div class="btn-group" role="group">

                                                    {{-- <a href="{{ route('items.show', $item) }}" target="_blank"
                                                        class="btn btn-info btn-sm" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a> --}}

                                                    {{-- print barcode --}}
                                                    <a href="{{ route('items.print-barcode', $item) }}" target="_blank"
                                                        class="btn btn-primary btn-sm" title="Print Barcode">
                                                        <i class="fas fa-print"></i>
                                                    </a>

                                                    <a href="{{ route('items.edit', $item) }}"
                                                        class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <form action="{{ route('items.destroy', $item) }}" method="POST"
                                                        class="d-inline" id="delete-form-{{ $item->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>

                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus"
                                                        onclick="event.preventDefault(); if(confirm('Yakin ingin menghapus item ini?')) {
                                                            document.getElementById('delete-form-{{ $item->id }}').submit();
                                                        }">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center">Tidak ada data item.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <!-- Enhanced Pagination -->
                        @if ($items->hasPages())
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="datatable-info">
                                        Menampilkan {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }}
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
                                    Total {{ $items->count() }} data Lokasi Rak
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal" id="scanBarcodeModal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cari Item</h4>

                    {{-- Close --}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="scanBarcodeForm">
                        <div class="form-group">
                            {{-- camera --}}
                            <video id="video" width="100%" height="auto" autoplay></video>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <canvas id="canvas" style="display: none;"></canvas>

    @push('js')
        <script src="//unpkg.com/javascript-barcode-reader"></script>
        <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>

        <script>
            let useNative = 'BarcodeDetector' in window;
            let barcodeDetector;

            if (useNative) {
                barcodeDetector = new BarcodeDetector();
            }
            const canvas = document.getElementById('canvas');
            const ctx = canvas.getContext('2d');
            const fps = 25;
            let intervalization;
            let alreadyDetected = false;

            $(document).ready(function() {
                // Initialize tooltips
                $('[data-toggle="tooltip"]').tooltip();

                // Initialize barcode scanner
                const video = document.getElementById('video');
                const constraints = {
                    audio: false,
                    video: {
                        facingMode: 'environment',
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        }
                    }
                };

                const callback = (code) => {
                    alreadyDetected = true;
                    const url =
                        `{{ route('items.search-by-barcode', ['code' => '___BARCODE___']) }}`
                        .replace('___BARCODE___', code);
                    console.log('Searching for barcode:', url);
                    fetch(url, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                window.location.href =
                                    `{{ url('items') }}/${data.item.id}/edit`;
                            } else {
                                alert('Item tidak ditemukan dengan barcode: ' + code);
                            }
                            alreadyDetected = false;
                        })
                        .catch(err => {
                            alreadyDetected = false;
                            console.error('Error searching item by barcode:', err);
                            alert('Terjadi kesalahan saat mencari item dengan barcode: ' +
                                code);
                        });
                };


                // Detect code function 
                const detectCode = () => {
                    // console.log(video)
                    // Start detecting codes on to the video element
                    if (useNative) {
                        barcodeDetector?.detect(video)?.then(codes => {
                            // If no codes exit function
                            if (codes.length === 0) return;
                            const code = codes[0];
                            if (alreadyDetected) return;
                            callback(code.rawValue);

                        }).catch(err => {
                            // Log an error if one happens
                            console.error(err);
                        })
                    } else {
                        console.log('Using jsQR fallback');

                        // fallback pakai canvas + jsQR
                        const width = video.videoWidth;
                        const height = video.videoHeight;

                        canvas.width = width;
                        canvas.height = height;
                        ctx.drawImage(video, 0, 0, width, height);
                        const imageData = ctx.getImageData(0, 0, width, height);
                        const code = jsQR(imageData.data, width, height);

                        if (code && code.data) {
                            console.log('Detected code:', code.data);
                            if (alreadyDetected) return;
                            callback(code.data);
                        }

                    }
                }


                // Start the camera
                const onStartCamera = () => {
                    navigator.mediaDevices.getUserMedia(constraints)
                        .then(stream => {
                            video.srcObject = stream;
                            video.onloadedmetadata = () => {
                                video.play();
                                intervalization = setInterval(detectCode, 500);
                            };
                        })
                        .catch(err => {
                            console.error('Error accessing camera: ', err);
                            alert('Tidak dapat mengakses kamera. Pastikan izin kamera sudah diberikan.');
                        });
                };

                // Start when modal is shown
                $('#scanBarcodeModal').on('shown.bs.modal', function() {
                    onStartCamera();
                });

                // Stop the camera when modal is hidden
                $('#scanBarcodeModal').on('hidden.bs.modal', function() {
                    const stream = video.srcObject;
                    if (stream) {
                        const tracks = stream.getTracks();
                        tracks.forEach(track => track.stop());
                    }
                    video.srcObject = null;

                    // clear intervalization
                    clearInterval(intervalization)
                });

                // Filter functionality
                $('#categoryFilter, #warehouseFilter, #stockFilter').change(function() {
                    filterItems();
                });

                $('#searchBtn').click(function() {
                    filterItems();
                });

                $('#searchInput').keypress(function(e) {
                    if (e.which == 13) {
                        filterItems();
                    }
                });

                function filterItems() {
                    let params = new URLSearchParams();

                    let category_id = $('#categoryFilter').val();
                    let warehouse_id = $('#warehouseFilter').val();
                    let low_stock = $('#stockFilter').val();
                    let search = $('#searchInput').val();

                    if (category_id) params.append('category_id', category_id);
                    if (warehouse_id) params.append('warehouse_id', warehouse_id);
                    if (low_stock) params.append('stock', low_stock);
                    if (search) params.append('search', search);

                    window.location.href = '{{ route('items.index') }}?' + params.toString();
                }

                // Stock update modal
                $('#stockModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var itemId = button.data('item-');
                    var itemName = button.data('item-name');
                    var currentStock = button.data('current-stock');

                    var modal = $(this);
                    modal.find('#itemName').val(itemName);
                    modal.find('#currentStockDisplay').val(currentStock);
                    modal.find('#current_stock').val(currentStock);
                    modal.find('#stockForm').attr('action', '/items/' + itemId + '/update-stock');
                });
            });
        </script>
    @endpush
@endsection
