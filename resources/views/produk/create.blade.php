@extends('adminlte::page')

@section('title', 'Tambah Produk')

@section('content_header')
<h1>Tambah Produk</h1>
@endsection

@section('css')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* Samakan tinggi select2 dengan form-control biasa */
    .select2-container--default .select2-selection--single {
        height: calc(2.375rem + 2px);
        /* sesuai tinggi input Bootstrap/ AdminLTE */
        padding: 0.375rem 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
    }

    /* Samakan posisi teks di tengah vertikal */
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: calc(2.375rem);
    }
</style>
@endsection


@section('content')
@include('components.preloader')

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
        <select name="kategori_id" class="form-control select2">
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

    {{-- Ganti tombol lama --}}
    <x-btn-submit id="btn-submit-produk" text="Simpan" />

    <!-- <button class="btn btn-primary">Simpan</button> -->
    <a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection

@section('js')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "-- Pilih Kategori --",
            allowClear: true
        });

        setTimeout(() => {
            const alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 3000);
    });
</script>
@endsection