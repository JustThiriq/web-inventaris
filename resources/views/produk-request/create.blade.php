@extends('adminlte::page')

@section('title', 'Tambah Produk Request')

@section('content_header')
    <h1>
        <i class="fas fa-plus-circle"></i> Tambah Produk Request
    </h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus-circle"></i> Form Tambah Produk Request
                    </h3>
                    <a href="{{ route('produk-request.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="card-body">
                {{-- Success Message --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> Terdapat kesalahan:
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- Form Header --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="info-box-content">
                        <span class="info-box-text">
                            <i class="fas fa-info-circle text-info"></i>
                            Anda dapat menambahkan beberapa produk request sekaligus menggunakan form dinamis di bawah ini.
                        </span>
                    </div>
                    <button type="button" onclick="addRow()" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Baris
                    </button>
                </div>

                {{-- Dynamic Form --}}
                <form action="{{ route('produk-request.store') }}" method="POST">
                    @csrf
                    <div id="form-container">
                        {{-- Initial rows berdasarkan old input atau default --}}
                        @php
                            $oldData = old('produk_requests', [
                                ['nama_produk' => '', 'harga_estimasi' => '', 'deskripsi' => '']
                            ]);
                        @endphp

                        @foreach ($oldData as $index => $item)
                            <div class="card card-outline card-primary mb-3 form-row" data-index="{{ $index }}">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-box"></i> Produk Request #{{ $index + 1 }}
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button"
                                                onclick="removeRow(this)"
                                                class="btn btn-tool text-danger"
                                                title="Hapus baris ini">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="nama_produk_{{ $index }}">Nama Produk <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       id="nama_produk_{{ $index }}"
                                                       name="produk_requests[{{ $index }}][nama_produk]"
                                                       class="form-control @error('produk_requests.'.$index.'.nama_produk') is-invalid @enderror"
                                                       placeholder="Masukkan nama produk"
                                                       value="{{ $item['nama_produk'] ?? '' }}"
                                                       required>
                                                @error('produk_requests.'.$index.'.nama_produk')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="harga_estimasi_{{ $index }}">Harga Estimasi <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Rp</span>
                                                    </div>
                                                    <input type="number"
                                                           id="harga_estimasi_{{ $index }}"
                                                           name="produk_requests[{{ $index }}][harga_estimasi]"
                                                           class="form-control @error('produk_requests.'.$index.'.harga_estimasi') is-invalid @enderror"
                                                           placeholder="0"
                                                           min="0"
                                                           step="0.01"
                                                           value="{{ $item['harga_estimasi'] ?? '' }}"
                                                           required>
                                                    @error('produk_requests.'.$index.'.harga_estimasi')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="deskripsi_{{ $index }}">Deskripsi (Opsional)</label>
                                                <textarea name="produk_requests[{{ $index }}][deskripsi]"
                                                          id="deskripsi_{{ $index }}"
                                                          class="form-control @error('produk_requests.'.$index.'.deskripsi') is-invalid @enderror"
                                                          placeholder="Deskripsi singkat produk"
                                                          rows="3">{{ $item['deskripsi'] ?? '' }}</textarea>
                                                @error('produk_requests.'.$index.'.deskripsi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Submit Button --}}
                    <div class="card">
                        <div class="card-footer">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('produk-request.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Simpan Semua Request
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
let rowIndex = {{ count($oldData) }};

function addRow() {
    const container = document.getElementById('form-container');
    const newRow = document.createElement('div');
    newRow.className = 'card card-outline card-primary mb-3 form-row';
    newRow.setAttribute('data-index', rowIndex);

    newRow.innerHTML = `
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-box"></i> Produk Request #${rowIndex + 1}
            </h3>
            <div class="card-tools">
                <button type="button"
                        onclick="removeRow(this)"
                        class="btn btn-tool text-danger"
                        title="Hapus baris ini">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nama_produk_${rowIndex}">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text"
                               id="nama_produk_${rowIndex}"
                               name="produk_requests[${rowIndex}][nama_produk]"
                               class="form-control"
                               placeholder="Masukkan nama produk"
                               required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="harga_estimasi_${rowIndex}">Harga Estimasi <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number"
                                   id="harga_estimasi_${rowIndex}"
                                   name="produk_requests[${rowIndex}][harga_estimasi]"
                                   class="form-control"
                                   placeholder="0"
                                   min="0"
                                   step="0.01"
                                   required>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="deskripsi_${rowIndex}">Deskripsi (Opsional)</label>
                        <textarea name="produk_requests[${rowIndex}][deskripsi]"
                                  id="deskripsi_${rowIndex}"
                                  class="form-control"
                                  placeholder="Deskripsi singkat produk"
                                  rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>
    `;

    container.appendChild(newRow);
    rowIndex++;

    // Focus pada input nama produk yang baru ditambahkan
    newRow.querySelector('input[name*="nama_produk"]').focus();

    // Update card titles
    updateCardTitles();
}

function removeRow(button) {
    const row = button.closest('.form-row');
    const container = document.getElementById('form-container');

    // Jangan hapus jika hanya ada satu baris
    if (container.children.length <= 1) {
        $(document).Toasts('create', {
            class: 'bg-warning',
            title: 'Peringatan',
            subtitle: 'Sistem',
            body: 'Minimal harus ada satu baris produk request.',
            autohide: true,
            delay: 3000
        });
        return;
    }

    row.remove();

    // Reindex semua baris yang tersisa
    reindexRows();
    updateCardTitles();
}

function reindexRows() {
    const rows = document.querySelectorAll('.form-row');
    rows.forEach((row, index) => {
        row.setAttribute('data-index', index);

        // Update name attributes dan IDs
        const inputs = row.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            const id = input.getAttribute('id');

            if (name) {
                const newName = name.replace(/\[\d+\]/, `[${index}]`);
                input.setAttribute('name', newName);
            }

            if (id) {
                const newId = id.replace(/_\d+$/, `_${index}`);
                input.setAttribute('id', newId);
            }
        });

        // Update label for attributes
        const labels = row.querySelectorAll('label');
        labels.forEach(label => {
            const forAttr = label.getAttribute('for');
            if (forAttr) {
                const newFor = forAttr.replace(/_\d+$/, `_${index}`);
                label.setAttribute('for', newFor);
            }
        });
    });

    rowIndex = rows.length;
}

function updateCardTitles() {
    const rows = document.querySelectorAll('.form-row');
    rows.forEach((row, index) => {
        const title = row.querySelector('.card-title');
        if (title) {
            title.innerHTML = `<i class="fas fa-box"></i> Produk Request #${index + 1}`;
        }
    });
}

// Auto-resize textarea
document.addEventListener('input', function(e) {
    if (e.target.tagName.toLowerCase() === 'textarea') {
        e.target.style.height = 'auto';
        e.target.style.height = (e.target.scrollHeight) + 'px';
    }
});

// Initialize tooltips
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});
</script>
@stop

@section('css')
<style>
.form-row {
    transition: all 0.3s ease;
}

.form-row:hover {
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.info-box-content {
    background: #f8f9fa;
    padding: 10px 15px;
    border-radius: 5px;
    border-left: 4px solid #17a2b8;
}

.info-box-text {
    color: #495057;
    font-size: 0.9rem;
}

.card-outline.card-primary {
    border-top: 3px solid #007bff;
}

.btn-tool:hover {
    background: transparent !important;
}

.gap-2 > * {
    margin-right: 0.5rem;
}

.gap-2 > *:last-child {
    margin-right: 0;
}
</style>
@stop
