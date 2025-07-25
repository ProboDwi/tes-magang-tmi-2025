@extends('adminlte::page')

@section('title', 'Edit Transaksi')

@section('content_header')
    <h1>Edit Transaksi</h1>
@endsection

@section('content')
@include('components.preloader')

    <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Produk</label>
            <select name="produk_id" id="produk" class="form-control" required>
                @foreach ($produk as $item)
                    <option value="{{ $item->id }}"
                        data-harga="{{ $item->harga }}"
                        {{ $item->id == $transaksi->produk_id ? 'selected' : '' }}>
                        {{ $item->nama_produk }} - Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Jumlah</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" min="1"
                value="{{ old('jumlah', $transaksi->jumlah) }}" required>
        </div>

        <div class="form-group">
            <label>Total Harga</label>
            <input type="text" id="total_harga" class="form-control" readonly>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection

@section('js')
<script>
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
</script>
@endsection
