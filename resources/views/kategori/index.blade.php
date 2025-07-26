@extends('adminlte::page')

@section('title', 'Data Kategori')

@section('content_header')
<h1 class="mb-4">Data Kategori</h1>
@endsection

@section('content')
@include('components.preloader')

@if (session('success'))
<div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
@endif



{{-- Tabel Data --}}
<div class="card">
    <div class="card-body">
        {{-- Tombol Tambah, Import, Export --}}
        <div class="mb-3 d-flex flex-wrap gap-2 justify-content-between align-items-center">

            <a href="{{ route('kategori.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Kategori
            </a>

            <form action="{{ route('kategori.import') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
                @csrf
                <input type="file" name="file" class="form-control" required style="max-width: 200px;">
                <button class="btn btn-success"><i class="fas fa-file-import"></i> Import</button>
            </form>

            @include('export.export')

        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable w-100" id="kategori-table">
                <thead class="text-nowrap">
                    <tr>
                        <th>Aksi</th>
                        <th>No</th>
                        <th>Nama Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kategori as $item)
                    <tr>
                        <td class="text-nowrap">
                            <a href="{{ route('kategori.edit', $item->id) }}" class="btn btn-warning btn-sm fa fa-edit"></a>
                            <form action="{{ route('kategori.destroy', $item->id) }}" method="POST" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin hapus?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama }}</td>
                        
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