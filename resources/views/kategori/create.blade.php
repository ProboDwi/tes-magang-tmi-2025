@extends('adminlte::page')

@section('title', 'Tambah Kategori')

@section('content_header')
    <h1>Tambah Kategori</h1>
@endsection

@section('content')
@if (session('success'))
<div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
@endif

    <form action="{{ route('kategori.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}">
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Kembali</a>
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
