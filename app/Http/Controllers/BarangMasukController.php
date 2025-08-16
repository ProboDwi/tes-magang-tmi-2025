<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Satuan;

class BarangMasukController extends Controller
{
    /**
     * Menampilkan daftar semua barang masuk.
     */
    public function index()
    {
        $produk = Produk::all();
        $barangMasuk = BarangMasuk::with('produk')->get();

        return view('barang_masuk.index', compact('barangMasuk', 'produk'));
    }

    /**
     * Menampilkan detail satu barang masuk.
     */
    public function show($id)
    {
        $barangMasuk = BarangMasuk::with('produk')->findOrFail($id);
        return view('barang_masuk.show', compact('barangMasuk'));
    }

    /**
     * Menampilkan form untuk membuat barang masuk baru.
     */
    public function create()
    {
        $produk = Produk::all();
        
        return view('barang_masuk.create', compact('produk'));
    }

    /**
     * Menyimpan data barang masuk baru ke database.
     */
    public function store(Request $request)
    {
        // Menambahkan validasi untuk field 'satuan'
        $validator = Validator::make($request->all(), [
            'produk_id'     => 'required|exists:produks,id',
            'jumlah_barang' => 'required|integer|min:1',
            'harga_beli'    => 'required|integer|min:0',
            'tanggal_kadaluwarsa' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $jumlahBarang = $request->input('jumlah_barang');
            $hargaBeli = $request->input('harga_beli');
            $totalHarga = $jumlahBarang * $hargaBeli;

            $barangMasuk = BarangMasuk::create([
                'produk_id'     => $request->input('produk_id'),
                'jumlah_barang' => $jumlahBarang,
                'harga_beli'    => $hargaBeli,
                'total_harga'   => $totalHarga,
                'tanggal_masuk' => now()->toDateString(),
                'tanggal_kadaluwarsa' => $request->input('tanggal_kadaluwarsa'),
            ]);

            $produk = Produk::findOrFail($request->input('produk_id'));
            $produk->increment('stok', $jumlahBarang);

            DB::commit();

            return redirect()->route('barang_masuk.index')->with('success', 'Barang masuk berhasil ditambahkan dan stok produk diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('barang_masuk.index')->with('error', 'Barang masuk gagal ditambahkan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk mengedit barang masuk.
     */
    public function edit($id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
        $produk = Produk::all();

        return view('barang_masuk.edit', compact('barangMasuk', 'produk'));
    }

    /**
     * Memperbarui data barang masuk di database.
     */
    public function update(Request $request, $id)
    {
        // Menambahkan validasi untuk field 'satuan'
        $validator = Validator::make($request->all(), [
            'produk_id'     => 'required|exists:produks,id',
            'jumlah_barang' => 'required|integer|min:1',
            'harga_beli'    => 'required|integer|min:0',
            'tanggal_kadaluwarsa' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $barangMasuk = BarangMasuk::findOrFail($id);

            $oldJumlahBarang = $barangMasuk->jumlah_barang;
            $oldProdukId = $barangMasuk->produk_id;

            $newJumlahBarang = $request->input('jumlah_barang');
            $newHargaBeli = $request->input('harga_beli');
            $newProdukId = $request->input('produk_id');
            $newTanggalKadaluwarsa = $request->input('tanggal_kadaluwarsa');
            $newTotalHarga = $newJumlahBarang * $newHargaBeli;

            $barangMasuk->update([
                'produk_id'     => $newProdukId,
                'jumlah_barang' => $newJumlahBarang,
                'harga_beli'    => $newHargaBeli,
                'total_harga'   => $newTotalHarga,
                'tanggal_kadaluwarsa' => $newTanggalKadaluwarsa,
            ]);

            if ($oldProdukId != $newProdukId) {
                $oldProduk = Produk::findOrFail($oldProdukId);
                $oldProduk->decrement('stok', $oldJumlahBarang);

                $newProduk = Produk::findOrFail($newProdukId);
                $newProduk->increment('stok', $newJumlahBarang);
            } else {
                $produk = Produk::findOrFail($newProdukId);
                $stokDifference = $newJumlahBarang - $oldJumlahBarang;
                $produk->increment('stok', $stokDifference);
            }

            DB::commit();

            return redirect()->route('barang_masuk.index')->with('success', 'Barang masuk berhasil diperbarui dan stok produk disesuaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('barang_masuk.index')->with('error', 'Barang masuk gagal diperbarui: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data barang masuk dari database.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $barangMasuk = BarangMasuk::findOrFail($id);
            $jumlahBarang = $barangMasuk->jumlah_barang;
            $produkId = $barangMasuk->produk_id;

            $barangMasuk->delete();

            $produk = Produk::findOrFail($produkId);
            if ($produk->stok >= $jumlahBarang) {
                $produk->decrement('stok', $jumlahBarang);
            } else {
                $produk->stok = 0;
                $produk->save();
            }

            DB::commit();

            return redirect()->route('barang_masuk.index')->with('success', 'Barang masuk berhasil dihapus dan stok produk disesuaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('barang_masuk.index')->with('error', 'Barang masuk gagal dihapus: ' . $e->getMessage());
        }
    }
}