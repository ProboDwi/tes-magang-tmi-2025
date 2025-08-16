<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ProdukSatuan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransaksiBerhasilMail;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    /**
     * Tampilkan daftar transaksi.
     */
    public function index()
    {
        // Ambil semua produk dan produk satuan untuk dropdown
        $produks = Produk::all();
        $produkSatuan = ProdukSatuan::with('produk')->get();

        // Ambil semua transaksi
        $transaksi = Transaksi::with(['produk', 'produkSatuan.produk'])->latest()->get();
        return view('transaksi.index', compact('transaksi', 'produks', 'produkSatuan'));
    }

    /**
     * Tampilkan form untuk membuat transaksi baru.
     */
    public function create()
    {
        $produks = Produk::all();
        $produkSatuan = ProdukSatuan::with('produk')->get();
        return view('transaksi.create', compact('produks', 'produkSatuan'));
    }

    /**
     * Simpan transaksi baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi, pastikan salah satu dari produk_id atau produksatuan_id diisi
        $request->validate([
            'produk_id'       => 'nullable|exists:produks,id|required_without:produksatuan_id',
            'produksatuan_id' => 'nullable|exists:produk_satuans,id|required_without:produk_id',
            'jumlah'          => 'required|integer|min:1',
        ]);

        $itemHarga = 0;
        $stokTersedia = 0;
        $transaksiData = [
            'jumlah' => $request->jumlah,
            'tanggal' => now(),
        ];

        if ($request->filled('produk_id')) {
            $produk = Produk::findOrFail($request->produk_id);
            if ($produk->stok < $request->jumlah) {
                return back()->with('error', 'Stok produk tidak mencukupi.');
            }
            $itemHarga = $produk->harga;
            $produk->decrement('stok', $request->jumlah);
            $transaksiData['produk_id'] = $produk->id;
        } elseif ($request->filled('produksatuan_id')) {
            $produkSatuan = ProdukSatuan::findOrFail($request->produksatuan_id);
            if ($produkSatuan->stok < $request->jumlah) {
                return back()->with('error', 'Stok produk tidak mencukupi.');
            }
            $itemHarga = $produkSatuan->harga;
            $produkSatuan->decrement('stok', $request->jumlah);
            $transaksiData['produksatuan_id'] = $produkSatuan->id;
        } else {
            return back()->with('error', 'Pilih salah satu produk atau produk satuan.');
        }

        $transaksiData['total_harga'] = $itemHarga * $request->jumlah;

        $transaksi = Transaksi::create($transaksiData);

        // Kirim email
        Mail::to('dwiytta1945@gmail.com')->send(new TransaksiBerhasilMail($transaksi));

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan dan stok dikurangi');
    }

    /**
     * Tampilkan form untuk mengedit transaksi.
     */
    public function edit($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $produks = Produk::all();
        $produkSatuan = ProdukSatuan::with('produk')->get();
        return view('transaksi.edit', compact('transaksi', 'produks', 'produkSatuan'));
    }

    /**
     * Perbarui data transaksi di database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'produk_id'       => 'nullable|exists:produks,id|required_without:produksatuan_id',
            'produksatuan_id' => 'nullable|exists:produk_satuans,id|required_without:produk_id',
            'jumlah'          => 'required|integer|min:1',
        ]);

        $transaksi = Transaksi::findOrFail($id);

        $itemHarga = 0;
        $updateData = [
            'jumlah' => $request->jumlah,
        ];
        
        // Cek item mana yang dipilih
        if ($request->filled('produk_id')) {
            $produk = Produk::findOrFail($request->produk_id);
            $itemHarga = $produk->harga;
            $updateData['produk_id'] = $produk->id;
            $updateData['produksatuan_id'] = null; // Pastikan kolom lain di-null
        } elseif ($request->filled('produksatuan_id')) {
            $produkSatuan = ProdukSatuan::findOrFail($request->produksatuan_id);
            $itemHarga = $produkSatuan->harga;
            $updateData['produksatuan_id'] = $produkSatuan->id;
            $updateData['produk_id'] = null; // Pastikan kolom lain di-null
        }

        $updateData['total_harga'] = $itemHarga * $request->jumlah;

        $transaksi->update($updateData);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui');
    }

    /**
     * Hapus transaksi dari database.
     */
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus');
    }

    /**
     * Cetak struk transaksi.
     */
    public function cetakStruk($id)
    {
        $transaksi = Transaksi::with(['produk', 'produkSatuan.produk'])->findOrFail($id);
        $pdf = Pdf::loadView('transaksi.struk', compact('transaksi'))->setPaper('A5');

        return $pdf->download('struk_transaksi_' . $id . '.pdf');
    }
}
