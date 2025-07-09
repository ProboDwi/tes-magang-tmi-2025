@extends('adminlte::page')

@section('title', 'Edit Produk')

@section('content_header')
    <h1>Edit Produk</h1>
@endsection

@section('content')
@if (session('success'))
<div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
@endif
    <form action="{{ route('produk.update', $produk) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" value="{{ old('nama_produk', $produk->nama_produk) }}">
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <select name="kategori_id" class="form-control">
                @foreach ($kategori as $item)
                    <option value="{{ $item->id }}" {{ $item->id == $produk->kategori_id ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" value="{{ old('harga', $produk->harga) }}">
        </div>

        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" value="{{ old('stok', $produk->stok) }}">
        </div>

        <button class="btn btn-primary">Update</button>
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
