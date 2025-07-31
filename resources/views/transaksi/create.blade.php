@extends('adminlte::page')

@section('title', 'Tambah Transaksi')

@section('content_header')
<h1>Tambah Transaksi</h1>
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

<form action="{{ route('transaksi.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label>Produk</label>
        <select name="produk_id" id="produk" class="form-control select2" required>
            <option value="">-- Pilih Produk --</option>
            @foreach ($produk as $item)
            <option value="{{ $item->id }}" data-harga="{{ $item->harga }}">
                {{ $item->nama_produk }} - Rp {{ number_format($item->harga, 0, ',', '.') }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Jumlah</label>
        <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" value="1" required>
    </div>

    <div class="form-group">
        <label>Total Harga</label>
        <input type="text" id="total_harga" class="form-control" readonly>
    </div>

    {{-- Ganti tombol lama --}}
    <x-btn-submit id="btn-submit-produk" text="Simpan" />
    <!-- <button class="btn btn-primary">Simpan</button> -->
    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Kembali</a>
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

        const produkSelect = document.getElementById('produk');
        const jumlahInput = document.getElementById('jumlah');
        const totalHargaInput = document.getElementById('total_harga');

        function hitungTotal() {
            const selectedOption = produkSelect.options[produkSelect.selectedIndex];
            const harga = parseInt(selectedOption.dataset.harga || 0);
            const jumlah = parseInt(jumlahInput.value || 0);
            const total = harga * jumlah;

            totalHargaInput.value = isNaN(total) ? '' : 'Rp ' + total.toLocaleString('id-ID');
        }

        produkSelect.addEventListener('change', hitungTotal);
        jumlahInput.addEventListener('input', hitungTotal);

        document.addEventListener('DOMContentLoaded', hitungTotal);

    });
</script>
@endsection