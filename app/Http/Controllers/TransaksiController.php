<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransaksiBerhasilMail;
use Barryvdh\DomPDF\Facade\Pdf;


class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::with('produk')->latest()->get();
        return view('transaksi.index', compact('transaksi'));
    }

    public function create()
    {
        $produk = Produk::all();
        return view('transaksi.create', compact('produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'jumlah'    => 'required|integer|min:1',
        ]);

        $produk = Produk::findOrFail($request->produk_id);

        // Cek stok cukup
        if ($produk->stok < $request->jumlah) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        $total = $produk->harga * $request->jumlah;

        // Buat transaksi
        $transaksi = Transaksi::create([
            'produk_id'   => $request->produk_id,
            'jumlah'      => $request->jumlah,
            'total_harga' => $total,
            'tanggal'     => now(),
        ]);

        // Kurangi stok produk
        $produk->decrement('stok', $request->jumlah);

        // Kirim email
        Mail::to('dwiytta1945@gmail.com')->send(new TransaksiBerhasilMail($transaksi));

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan dan stok dikurangi');
    }

    public function edit($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $produk = Produk::all();
        return view('transaksi.edit', compact('transaksi', 'produk'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'jumlah'    => 'required|integer|min:1',
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $produk = Produk::findOrFail($request->produk_id);
        $total = $produk->harga * $request->jumlah;

        $transaksi->update([
            'produk_id'   => $request->produk_id,
            'jumlah'      => $request->jumlah,
            'total_harga' => $total,
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus');
    }

    public function cetakStruk($id)
    {
        $transaksi = Transaksi::with('produk')->findOrFail($id);
        $pdf = Pdf::loadView('transaksi.struk', compact('transaksi'))->setPaper('A5');

        return $pdf->download('struk_transaksi_' . $id . '.pdf');
    }
}
