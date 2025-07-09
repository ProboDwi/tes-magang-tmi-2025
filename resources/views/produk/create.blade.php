@extends('adminlte::page')

@section('title', 'Tambah Produk')

@section('content_header')
<h1>Tambah Produk</h1>
@endsection

@section('content')
@if (session('success'))
<div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
@endif
<form action="{{ route('produk.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label>Nama Produk</label>
        <input type="text" name="nama_produk" class="form-control" value="{{ old('nama_produk') }}">
    </div>

    <div class="form-group">
        <label>Kategori</label>
        <select name="kategori_id" class="form-control">
            <option value="">-- Pilih Kategori --</option>
            @foreach ($kategori as $item)
            <option value="{{ $item->id }}" {{ old('kategori_id') == $item->id ? 'selected' : '' }}>
                {{ $item->nama }}
            </option>
            @endforeach
        </select>

    </div>

    <div class="form-group">
        <label>Harga</label>
        <input type="number" name="harga" class="form-control" value="{{ old('harga') }}">
    </div>

    <div class="form-group">
        <label>Stok</label>
        <input type="number" name="stok" class="form-control" value="{{ old('stok') }}">
    </div>

    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection

@section('js')
<script>
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