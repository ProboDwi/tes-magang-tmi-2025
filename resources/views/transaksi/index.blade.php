@extends('adminlte::page')

@section('title', 'Data Transaksi')

@section('content_header')
<h1 class="mb-4">Data Transaksi</h1>
@endsection

@section('content')
@include('components.preloader')

{{-- Notifikasi --}}
@if (session('success'))
<div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
@endif

{{-- DataTable --}}
<div class="card">
    <div class="card-body">

        {{-- Tombol Tambah dan Export --}}
        <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Transaksi
            </a>

            @include('export.export')
        </div>

        {{-- Tabel dibungkus agar responsif --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable w-100" id="transaksi-table">
                <thead class="text-nowrap">
                    <tr>
                        <th>Aksi</th>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Harga Produk</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi as $item)
                    <tr>
                        <td class="text-nowrap">
                            <a href="{{ route('transaksi.edit', $item->id) }}" class="btn btn-warning btn-sm fa fa-edit"></a>
                            <form action="{{ route('transaksi.destroy', $item->id) }}" method="POST" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin hapus transaksi ini?')" class="btn btn-danger btn-sm" ><i class="fa fa-trash"></i></button>
                            </form>
                            <a href="{{ route('transaksi.cetak', $item->id) }}" class="btn btn-sm btn-info" target="_blank">
                                <i class="fa fa-print"></i>
                            </a>
                        </td>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->tanggal }}</td>
                        <td>{{ $item->produk->nama_produk }}</td>
                        <td>Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection

@section('js')
<script>
    // Menghilangkan alert sukses setelah 3 detik
    setTimeout(() => {
        const alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);
</script>
@endsection
