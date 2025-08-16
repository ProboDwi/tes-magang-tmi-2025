@extends('adminlte::page')

@section('title', 'Data Produk')

@section('content_header')
<h1 class="mb-4">Data Produk</h1>
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
        {{-- Tombol Tambah dan Export --}}
        <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreate">
                <i class="fas fa-plus"></i> Tambah Produk
            </button>
            @include('export.export')
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable w-100" id="produk-table">
                <thead class="text-nowrap">
                    <tr>
                        <th>Aksi</th>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produk as $item)
                    <tr>
                        <td class="text-nowrap">
                            <button 
                                class="btn btn-warning btn-sm fa fa-edit btn-edit" 
                                data-id="{{ $item->id }}"
                                data-nama="{{ $item->nama_produk }}"
                                data-harga="{{ $item->harga }}"
                                data-stok="{{ $item->stok }}"
                                data-toggle="modal" 
                                data-target="#modalEdit">
                            </button>

                            <form action="{{ route('produk.destroy', $item->id) }}" method="POST" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin hapus?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama_produk }}</td>
                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>{{ $item->stok }}</td>
                        <td>{{ $item->satuan }}</td>
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
            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('produk.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" name="harga" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <x-btn-submit text="Simpan" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Produk</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input type="text" name="nama_produk" id="edit_nama_produk" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" name="harga" id="edit_harga" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" name="stok" id="edit_stok" class="form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-btn-submit text="Update" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // Alert hilang otomatis
    setTimeout(() => {
        const alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);

    // Fill data di modal edit
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const nama = this.dataset.nama;
            const harga = this.dataset.harga;
            const stok = this.dataset.stok;

            document.getElementById('edit_nama_produk').value = nama;
            document.getElementById('edit_harga').value = harga;
            document.getElementById('edit_stok').value = stok;
            document.getElementById('formEdit').action = `/produk/${id}`;
        });
    });
</script>
@endsection
