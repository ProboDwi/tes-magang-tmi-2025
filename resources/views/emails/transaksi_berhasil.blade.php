<h2>Transaksi Berhasil</h2>

<p>Terima kasih telah melakukan transaksi di Toko Eka.</p>

<table>
    <tr>
        <td><strong>Nama Produk:</strong></td>
        <td>{{ $transaksi->produk->nama_produk }}</td>
    </tr>
    <tr>
        <td><strong>Harga Satuan:</strong></td>
        <td>Rp {{ number_format($transaksi->produk->harga, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td><strong>Jumlah:</strong></td>
        <td>{{ $transaksi->jumlah }}</td>
    </tr>
    <tr>
        <td><strong>Total Harga:</strong></td>
        <td>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td><strong>Tanggal:</strong></td>
        <td>{{ $transaksi->tanggal }}</td>
    </tr>
</table>

<p>Salam, <br>Toko Eka</p>
