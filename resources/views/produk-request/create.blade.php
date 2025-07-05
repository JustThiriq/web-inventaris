@extends('adminlte::page')

@section('title', 'Tambah Produk Request')

@section('content')
    <div class="container-fluid pt-3">
        <h3><i class="fas fa-plus-circle"></i> Tambah Produk Request</h3>

        <form action="{{ route('produk-request.store') }}" method="POST">
            @csrf

            @include('components.flash-message')

            <!-- Header Request -->
            <div class="card mb-4">
                <div class="card-body row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nomor Request</label>
                            <input type="text" name="request_number" class="form-control"
                                value="{{ old('request_number', $requestNumber ?? '') }}" required readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal Request</label>
                            <input type="date" name="request_date" class="form-control"
                                value="{{ old('request_date', now()->toDateString()) }}" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Deskripsi (Opsional)</label>
                            <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Detail Produk Request -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong class="w-100">Daftar Produk</strong>
                    <div class="w-100 d-flex justify-content-end">
                        <button type="button" onclick="addRow()" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Tambah Baris
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 40%">Produk</th>
                                    <th>Qty</th>
                                    {{-- <th>Harga Estimasi</th> --}}
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @if (old('produk_requests'))
                                    @foreach (old('produk_requests') as $index => $produk)
                                        <tr data-index="{{ $index }}">
                                            <td>
                                                <div class="input-group">
                                                    <input type="text" name="produk_requests[{{ $index }}][name]"
                                                        class="form-control item-name" placeholder="Scan barcode"
                                                        value="{{ $produk['name'] ?? '' }}" readonly required>
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-info scan-barcode"
                                                            data-index="{{ $index }}">
                                                            <i class="fas fa-barcode"></i>
                                                        </button>
                                                    </div>
                                                    <input type="hidden"
                                                        name="produk_requests[{{ $index }}][item_id]" class="item-id"
                                                        value="{{ $produk['item_id'] ?? '' }}">
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" name="produk_requests[{{ $index }}][quantity]"
                                                    class="form-control" value="{{ $produk['quantity'] ?? '' }}"
                                                    min="0" step="1" required>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="removeRow(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr data-index="0">
                                        <td>
                                            <div class="input-group">
                                                <input type="text" name="produk_requests[0][name]"
                                                    class="form-control item-name" placeholder="Scan barcode" readonly
                                                    required>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-info scan-barcode"
                                                        data-index="0"><i class="fas fa-barcode"></i></button>
                                                </div>
                                                <input type="hidden" name="produk_requests[0][item_id]" class="item-id">
                                            </div>
                                        </td>
                                        <td><input type="number" name="produk_requests[0][quantity]" class="form-control"
                                                min="0" step="1" required></td>
                                        <!-- <td><input type="number" name="produk_requests[0][estimated_price]"
                                                                class="form-control" min="0" step="0.01" required></td> -->
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="mt-3 d-flex justify-content-end">
                <a href="{{ route('produk-request.index') }}" class="btn btn-secondary mr-2">Batal</a>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Request</button>
            </div>
        </form>
    </div>

    <!-- Modal Scan Barcode -->
    <div class="modal fade" id="scanBarcodeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Scan Barcode</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <video id="barcode-video" width="100%" autoplay></video>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        let rowIndex = 1;
        let scanTargetIndex = null;

        function addRow() {
            const tbody = document.getElementById('table-body');
            const tr = document.createElement('tr');
            tr.setAttribute('data-index', rowIndex);
            tr.innerHTML = `
        <td>
            <div class="input-group">
                <input type="text" name="produk_requests[${rowIndex}][name]" class="form-control item-name" placeholder="Scan barcode" readonly required>
                <div class="input-group-append">
                    <button type="button" class="btn btn-info scan-barcode" data-index="${rowIndex}">
                        <i class="fas fa-barcode"></i>
                    </button>
                </div>
                <input type="hidden" name="produk_requests[${rowIndex}][item_id]" class="item-id">
            </div>
        </td>
        <td><input type="number" name="produk_requests[${rowIndex}][quantity]" class="form-control" min="0" step="0.01" required></td>
        <!-- <td><input type="number" name="produk_requests[${rowIndex}][estimated_price]" class="form-control" min="0" step="0.01" required></td> -->
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>`;
            tbody.appendChild(tr);
            rowIndex++;
        }

        function removeRow(button) {
            const row = button.closest('tr');
            const totalRows = document.querySelectorAll('#table-body tr').length;
            if (totalRows > 1) {
                row.remove();
            } else {
                alert('Minimal 1 produk request');
            }
        }

        $(document).on('click', '.scan-barcode', function() {
            scanTargetIndex = $(this).data('index');
            $('#scanBarcodeModal').modal('show');
            startScan();
        });

        function startScan() {
            const video = document.getElementById('barcode-video');
            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'environment'
                    }
                })
                .then(stream => {
                    video.srcObject = stream;
                    const detector = new BarcodeDetector();
                    const interval = setInterval(() => {
                        detector.detect(video).then(codes => {
                            if (codes.length > 0) {
                                const code = codes[0].rawValue;
                                clearInterval(interval);
                                stream.getTracks().forEach(t => t.stop());
                                $('#scanBarcodeModal').modal('hide');

                                fetch(`/items/search-by-barcode/${code}`)
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.success) {
                                            // Check if the item already exists in the table
                                            const existingRow = document.querySelector(
                                                `tr .item-id[value="${data.item.id}"]`
                                            );
                                            console.log(existingRow);
                                            if (existingRow) {
                                                alert('Item sudah ada di daftar produk!');
                                                return;
                                            }

                                            const row = document.querySelector(
                                                `tr[data-index="${scanTargetIndex}"]`);
                                            row.querySelector('.item-name').value = data.item.name;
                                            row.querySelector('.item-id').value = data.item.id;
                                        } else {
                                            alert('Item tidak ditemukan!');
                                        }
                                    });
                            }
                        });
                    }, 1000);
                })
                .catch(err => alert('Tidak bisa akses kamera: ' + err));
        }

        $('#scanBarcodeModal').on('hidden.bs.modal', () => {
            const video = document.getElementById('barcode-video');
            if (video.srcObject) {
                video.srcObject.getTracks().forEach(track => track.stop());
                video.srcObject = null;
            }
        });
    </script>
@endpush
