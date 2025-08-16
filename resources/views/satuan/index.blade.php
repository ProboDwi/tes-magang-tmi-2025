@extends('adminlte::page')

@section('title', 'Data Satuan')

@section('content_header')
<h1 class="mb-4">Data Satuan</h1>
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
                <i class="fas fa-plus"></i> Tambah Satuan
            </button>
            {{-- Kalau ada export --}}
            @include('export.export')
        </div>

        <div class="table-responsive">
            <table class="table table-bordered dataTable table-striped dataTable w-100">
                <thead class="text-nowrap">
                    <tr>
                        <th>Aksi</th>
                        <th>No</th>
                        <th>Nama Satuan</th>
                        <th>Isi Per Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($satuan as $i => $row)
                    <tr>
                        <td class="text-nowrap">
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEdit{{ $row->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('satuan.destroy', $row->id) }}" method="POST" style="display:inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row->nama }}</td>
                        <td>{{ $row->isi_per_satuan }}</td>
                    </tr>

                    {{-- Modal Edit --}}
                    <div class="modal fade" id="modalEdit{{ $row->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('satuan.update', $row->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Satuan</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Nama Satuan</label>
                                            <input type="text" name="nama" class="form-control" value="{{ $row->nama }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Isi Per Satuan</label>
                                            <input type="text" name="isi_per_satuan" class="form-control" value="{{ $row->isi_per_satuan }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <x-btn-submit id="btn-submit-satuan" text="Update" />
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
            <form action="{{ route('satuan.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Satuan</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Satuan</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Isi Per Satuan</label>
                        <input type="text" name="isi_per_satuan" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-btn-submit id="btn-submit-satuan" text="Simpan" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
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
