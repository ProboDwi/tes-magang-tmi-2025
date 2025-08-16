@extends('adminlte::page')

@section('title', 'Data Produk Satuan')

@section('content_header')
<h1 class="mb-4">Data Produk Satuan</h1>
@endsection

@section('content')
@include('components.preloader')

@if (session('success'))
<div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
@endif

@if (session('error'))
<div class="alert alert-danger" id="error-alert">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreate">
                <i class="fas fa-plus"></i> Tambah Produk Satuan
            </button>
            @include('export.export')
        </div>

        <div class="table-responsive">
            <table class="table table-bordered dataTable table-striped dataTable w-100">
                <thead class="text-nowrap">
                    <tr>
                        <th>Aksi</th>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produkSatuan as $i => $row)
                    <tr>
                        <td class="text-nowrap">
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEdit{{ $row->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('produk_satuan.destroy', $row->id) }}" method="POST" style="display:inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row->produk->nama_produk }}</td>
                        <td>Rp {{ number_format($row->harga, 0, ',', '.') }}</td>
                        <td>{{ $row->stok }}</td>
                        <td>{{ $row->satuan }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Create --}}
<div class="modal fade" id="modalCreate" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('produk_satuan.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk Satuan</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Produk</label>
                        {{-- Menggunakan variabel $produks yang dikirim dari controller --}}
                        <select name="produk_id" id="produk_id" class="form-control" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($produk as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" name="harga" id="harga" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Stok/pcs</label>
                        <input type="number" name="stok" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-btn-submit id="btn-submit-produk-satuan" text="Simpan" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Loop terpisah untuk Modal Edit --}}
@foreach ($produkSatuan as $row)
<div class="modal fade" id="modalEdit{{ $row->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('produk_satuan.update', $row->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Produk Satuan</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Produk</label>
                        <select name="produk_id" class="form-control" required>
                            <option value="" disabled selected>Pilih Produk</option>
                            @foreach ($produk as $p)
                            <option value="{{ $p->id }}" {{ $p->id == $row->produk_id ? 'selected' : '' }}>
                                {{ $p->nama_produk }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" name="harga" class="form-control" value="{{ $row->harga }}" required>
                    </div>
                    <div class="form-group">
                        <label>Stok/pcs</label>
                        <input type="number" name="stok" class="form-control" value="{{ $row->stok }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-btn-submit id="btn-submit-produk-satuan" text="Update" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalCreate = document.getElementById('modalCreate');
        const produkIdInput = modalCreate.querySelector('#produk_id');
        const hargaInput = modalCreate.querySelector('#harga');

        function fetchHarga() {
            const produkId = produkIdInput.value;

            if (produkId) {
                fetch(`/produk-satuan/get-harga?produk_id=${produkId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.harga !== null) {
                            hargaInput.value = data.harga;
                        } else {
                            hargaInput.value = '';
                        }
                    })
                    .catch(error => {
                        console.error('Ada masalah saat mengambil data harga:', error);
                    });
            } else {
                hargaInput.value = '';
            }
        }

        produkIdInput.addEventListener('change', fetchHarga);
    });

    setTimeout(() => {
        document.querySelectorAll('#success-alert, #error-alert').forEach(el => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 3000);
</script>
@endsection