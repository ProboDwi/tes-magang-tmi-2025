@extends('adminlte::page')

@section('title', 'Data Barang Masuk')

@section('content_header')
<h1 class="mb-4">Data Barang Masuk</h1>
@endsection

@section('content')
@include('components.preloader')

@if (session('success'))
<div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
@endif

@if (session('error'))
<div class="alert alert-danger" id="error-alert">{{ session('error') }}</div>
@endif

{{-- Tabel Responsif --}}
<div class="card">
    <div class="card-body">
        {{-- Tombol Tambah --}}
        <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreate">
                <i class="fas fa-plus"></i> Tambah Barang Masuk
            </button>
            @include('export.export')
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable w-100" id="barang-masuk-table">
                <thead class="text-nowrap">
                    <tr>
                        <th>Aksi</th>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Jumlah Barang</th>
                        <th>Harga Beli</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barangMasuk as $item)
                    <tr>
                        <td class="text-nowrap">
                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalShow{{ $item->id }}"><i class="fa fa-eye"></i></button>
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEdit{{ $item->id }}"><i class="fa fa-edit"></i></button>
                            <form action="{{ route('barang_masuk.destroy', $item->id) }}" method="POST" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus data barang masuk ini?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->produk->nama_produk }}</td>
                        <td>{{ $item->jumlah_barang }}</td>
                        <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
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
            <form action="{{ route('barang_masuk.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang Masuk</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Produk</label>
                        <select name="produk_id" class="form-control">
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($produk as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Barang</label>
                        <input type="number" name="jumlah_barang" class="form-control" min="1">
                    </div>
                    <div class="form-group">
                        <label>Harga Beli</label>
                        <input type="number" name="harga_beli" class="form-control" min="0">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Kadaluwarsa</label>
                        <input type="date" name="tanggal_kadaluwarsa" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <x-btn-submit id="btn-submit-produk" text="Simpan" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach ($barangMasuk as $item)
{{-- Modal Show --}}
<div class="modal fade" id="modalShow{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Barang Masuk</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p><strong>Nama Produk:</strong> {{ $item->produk->nama_produk }}</p>
                <p><strong>Jumlah Barang:</strong> {{ $item->jumlah_barang }}</p>
                <p><strong>Harga Beli:</strong> Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</p>
                <p><strong>Total Harga:</strong> Rp {{ number_format($item->total_harga, 0, ',', '.') }}</p>
                <p><strong>Tanggal Kadaluwarsa:</strong> {{ $item->tanggal_kadaluwarsa ?? '-' }}</p>
                <p><strong>Tanggal Masuk:</strong> {{ $item->tanggal_masuk }}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('barang_masuk.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Barang Masuk</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Produk</label>
                        <select name="produk_id" class="form-control">
                            @foreach ($produk as $p)
                            <option value="{{ $p->id }}" {{ $item->produk_id == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_produk }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Barang</label>
                        <input type="number" name="jumlah_barang" class="form-control" value="{{ $item->jumlah_barang }}" min="1">
                    </div>
                    <div class="form-group">
                        <label>Harga Beli</label>
                        <input type="number" name="harga_beli" class="form-control" value="{{ $item->harga_beli }}" min="0">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Kadaluwarsa</label>
                        <input type="date" name="tanggal_kadaluwarsa" class="form-control" value="{{ $item->tanggal_kadaluwarsa }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <x-btn-submit id="btn-submit-produk" text="Update" />
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
    setTimeout(() => {
        document.querySelectorAll('#success-alert, #error-alert').forEach(el => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 3000);
</script>
@endsection
