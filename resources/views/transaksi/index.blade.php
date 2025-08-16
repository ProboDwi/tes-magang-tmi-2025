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

@if (session('error'))
<div class="alert alert-danger" id="error-alert">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body">

        {{-- Tombol Tambah dan Export --}}
        <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreate">
                <i class="fas fa-plus"></i> Tambah Transaksi
            </button>
            @include('export.export')
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable w-100">
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
                            {{-- Gunakan data yang sesuai untuk modal edit --}}
                            <button class="btn btn-warning btn-sm fa fa-edit btn-edit"
                                data-id="{{ $item->id }}"
                                data-produk="{{ $item->produk_id ?? '' }}"
                                data-produksatuan="{{ $item->produksatuan_id ?? '' }}"
                                data-jumlah="{{ $item->jumlah }}"
                                data-toggle="modal"
                                data-target="#modalEdit">
                            </button>

                            <form action="{{ route('transaksi.destroy', $item->id) }}" method="POST" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin hapus transaksi ini?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                            </form>

                            <a href="{{ route('transaksi.cetak', $item->id) }}" class="btn btn-sm btn-info" target="_blank">
                                <i class="fa fa-print"></i>
                            </a>
                        </td>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->tanggal }}</td>
                        {{-- Tampilkan nama produk dari relasi yang tersedia --}}
                        <td>{{ $item->produk->nama_produk ?? $item->produkSatuan->produk->nama_produk . ' (' . $item->produkSatuan->satuan . ')' }}</td>
                        {{-- Tampilkan harga dari relasi yang tersedia --}}
                        <td>Rp {{ number_format($item->produk->harga ?? $item->produkSatuan->harga, 0, ',', '.') }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalCreate" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('transaksi.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Produk (Satuan)</label>
                        <select name="produksatuan_id" id="produksatuan_create" class="form-control">
                            <option value="">-- Pilih Produk Satuan --</option>
                            @foreach ($produkSatuan as $ps)
                            <option value="{{ $ps->id }}" data-harga="{{ $ps->harga }}" data-stok="{{ $ps->stok }}">
                                {{ $ps->produk->nama_produk }} ({{ $ps->satuan }}) - Rp {{ number_format($ps->harga, 0, ',', '.') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Atau Pilih Produk (tanpa satuan)</label>
                        <select name="produk_id" id="produk_create" class="form-control">
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($produks as $p)
                            <option value="{{ $p->id }}" data-harga="{{ $p->harga }}" data-stok="{{ $p->stok }}">
                                {{ $p->nama_produk }} - Rp {{ number_format($p->harga, 0, ',', '.') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah_create" class="form-control" min="1" value="1" required>
                    </div>
                    <div class="form-group">
                        <label>Total Harga</label>
                        <input type="text" id="total_create" class="form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-btn-submit text="Simpan" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Produk (Satuan)</label>
                        <select name="produksatuan_id" id="produksatuan_edit" class="form-control">
                            <option value="">-- Pilih Produk Satuan --</option>
                            @foreach ($produkSatuan as $ps)
                            <option value="{{ $ps->id }}" data-harga="{{ $ps->harga }}" data-stok="{{ $ps->stok }}">
                                {{ $ps->produk->nama_produk }} ({{ $ps->satuan }}) - Rp {{ number_format($ps->harga, 0, ',', '.') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Atau Pilih Produk (tanpa satuan)</label>
                        <select name="produk_id" id="produk_edit" class="form-control">
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($produks as $p)
                            <option value="{{ $p->id }}" data-harga="{{ $p->harga }}" data-stok="{{ $p->stok }}">
                                {{ $p->nama_produk }} - Rp {{ number_format($p->harga, 0, ',', '.') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah_edit" class="form-control" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Total Harga</label>
                        <input type="text" id="total_edit" class="form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-btn-submit text="Update" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // Alert hilang otomatis
    setTimeout(() => {
        document.querySelectorAll('#success-alert, #error-alert').forEach(alert => {
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 3000);

    // Hitung total modal create
    function hitungTotalCreate() {
        const produkSelect = document.getElementById('produk_create');
        const produkSatuanSelect = document.getElementById('produksatuan_create');
        const jumlahInput = document.getElementById('jumlah_create');
        const totalInput = document.getElementById('total_create');

        let harga = 0;
        let stok = 0;
        let jumlah = parseInt(jumlahInput.value || 0);

        if (produkSatuanSelect.value !== '') {
            const selectedOption = produkSatuanSelect.options[produkSatuanSelect.selectedIndex];
            harga = parseInt(selectedOption.dataset.harga || 0);
            stok = parseInt(selectedOption.dataset.stok || 0);
            produkSelect.disabled = true;
        } else if (produkSelect.value !== '') {
            const selectedOption = produkSelect.options[produkSelect.selectedIndex];
            harga = parseInt(selectedOption.dataset.harga || 0);
            stok = parseInt(selectedOption.dataset.stok || 0);
            produkSatuanSelect.disabled = true;
        } else {
            produkSelect.disabled = false;
            produkSatuanSelect.disabled = false;
        }

        if (jumlah > stok && stok > 0) {
            alert('Jumlah melebihi stok tersedia!');
            jumlah = stok;
            jumlahInput.value = stok;
        }

        totalInput.value = harga && jumlah ? 'Rp ' + (harga * jumlah).toLocaleString('id-ID') : '';
    }
    document.getElementById('produksatuan_create').addEventListener('change', hitungTotalCreate);
    document.getElementById('produk_create').addEventListener('change', hitungTotalCreate);
    document.getElementById('jumlah_create').addEventListener('input', hitungTotalCreate);

    // Hitung total modal edit
    function hitungTotalEdit() {
        const produkSelect = document.getElementById('produk_edit');
        const produkSatuanSelect = document.getElementById('produksatuan_edit');
        const jumlahInput = document.getElementById('jumlah_edit');
        const totalInput = document.getElementById('total_edit');

        let harga = 0;
        let stok = 0;
        let jumlah = parseInt(jumlahInput.value || 0);

        if (produkSatuanSelect.value !== '') {
            const selectedOption = produkSatuanSelect.options[produkSatuanSelect.selectedIndex];
            harga = parseInt(selectedOption.dataset.harga || 0);
            stok = parseInt(selectedOption.dataset.stok || 0);
            produkSelect.disabled = true;
        } else if (produkSelect.value !== '') {
            const selectedOption = produkSelect.options[produkSelect.selectedIndex];
            harga = parseInt(selectedOption.dataset.harga || 0);
            stok = parseInt(selectedOption.dataset.stok || 0);
            produkSatuanSelect.disabled = true;
        } else {
            produkSelect.disabled = false;
            produkSatuanSelect.disabled = false;
        }

        // Tidak ada validasi stok di modal edit karena stok diupdate di controller
        totalInput.value = harga && jumlah ? 'Rp ' + (harga * jumlah).toLocaleString('id-ID') : '';
    }
    document.getElementById('produksatuan_edit').addEventListener('change', hitungTotalEdit);
    document.getElementById('produk_edit').addEventListener('change', hitungTotalEdit);
    document.getElementById('jumlah_edit').addEventListener('input', hitungTotalEdit);

    // Set data ke modal edit
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const produkId = this.dataset.produk;
            const produkSatuanId = this.dataset.produksatuan;
            const jumlah = this.dataset.jumlah;

            document.getElementById('formEdit').action = `/transaksi/${id}`;
            document.getElementById('jumlah_edit').value = jumlah;

            // Reset dan set nilai yang sesuai
            document.getElementById('produk_edit').value = produkId;
            document.getElementById('produksatuan_edit').value = produkSatuanId;
            
            hitungTotalEdit();
        });
    });
</script>
@endsection
