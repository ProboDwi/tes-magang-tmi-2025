<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        h2 { text-align: center; margin-bottom: 0; }
        .info, .footer { margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; }
        .table td { padding: 5px; }
        .total { font-weight: bold; }
        .footer { text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <h2>Toko Eka</h2>
    <hr>

    <div class="info">
        <p><strong>No Transaksi:</strong> #{{ $transaksi->id }}</p>
        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y H:i') }}</p>
    </div>

    <table class="table">
        <tr>
            <td><strong>Produk</strong></td>
            <td>{{ $transaksi->produk->nama_produk }}</td>
        </tr>
        <tr>
            <td><strong>Harga Satuan</strong></td>
            <td>Rp {{ number_format($transaksi->produk->harga, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Jumlah</strong></td>
            <td>{{ $transaksi->jumlah }}</td>
        </tr>
        <tr class="total">
            <td>Total Bayar</td>
            <td>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer">
        <p>Terima kasih telah berbelanja!</p>
    </div>
</body>
</html>
