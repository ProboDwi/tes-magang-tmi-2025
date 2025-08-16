@extends('adminlte::page')

@section('title', 'Konversi Satuan')

@section('content_header')
<h1 class="mb-4">Data Konversi Satuan</h1>
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
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">
                <i class="fas fa-plus"></i> Tambah Konversi
            </button>
            @include('export.export')
        </div>

        <div class="table-responsive">
            <table class="table table-bordered dataTable table-hover w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Dari Satuan</th>
                        <th>Ke Satuan</th>
                        <th>Qty</th>
                        <th>Status</th>
                        <th class="text-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($konversi as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $row->produk->nama_produk }}</td>
                            <td>{{ $row->dariSatuan->nama }}</td>
                            <td>{{ $row->keSatuan->nama }}</td>
                            <td>{{ $row->jumlah_konversi }}</td>
                            <td>
                                @if($row->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($row->status == 'approved')
                                    <span class="badge badge-success">Disetujui</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td class="text-nowrap">
                                @if($row->status == 'pending')
                                    <form action="{{ route('konversi_satuan.approve', $row->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        @method('PUT')
                                        <!-- <input type="hidden" name="_method" value="PUT"> -->
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Setujui konversi ini?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('konversi_satuan.reject', $row->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tolak konversi ini?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-secondary btn-sm" disabled><i class="fas fa-ban"></i></button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('konversi_satuan.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Konversi Satuan</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Produk</label>
                        <select name="produk_id" class="form-control" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produk as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Dari Satuan</label>
                        <select name="dari_satuan_id" class="form-control" required>
                            <option value="">-- Pilih Satuan Asal --</option>
                            @foreach($satuan as $s)
                                <option value="{{ $s->id }}">{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ke Satuan</label>
                        <select name="ke_satuan_id" class="form-control" required>
                            <option value="">-- Pilih Satuan Tujuan --</option>
                            @foreach($satuan as $s)
                                <option value="{{ $s->id }}">{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah_konversi" class="form-control" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-btn-submit id="btn-submit-konversi" text="Simpan" />
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
