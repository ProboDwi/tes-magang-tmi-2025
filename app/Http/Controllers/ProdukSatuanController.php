<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ProdukSatuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdukSatuanController extends Controller
{
    /**
     * Menampilkan daftar semua ProdukSatuan.
     */
    public function index()
    {
        // Mengambil semua data ProdukSatuan dari database
        $produkSatuan = ProdukSatuan::all();

        $produk = Produk::all();
        // Mengembalikan view dengan data produk satuans
        return view('produk_satuan.index', compact('produkSatuan', 'produk'));
    }

    /**
     * Menampilkan form untuk membuat ProdukSatuan baru.
     */
    public function create()
    {
        // Mengambil semua data Produk untuk dropdown di form
        $produk = Produk::all();
        return view('produk_satuan.create', compact('produk'));
    }

    /**
     * Menyimpan ProdukSatuan yang baru dibuat ke database
     * dan mengurangi stok produk induk sebanyak 1, atau
     * menambahkan stok jika produk satuan sudah ada.
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk dari request
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'satuan' => 'nullable|string|max:255',
        ]);

        // Menggunakan transaksi database untuk memastikan operasi berhasil atau gagal bersama-sama.
        DB::beginTransaction();

        try {
            // Mencari produk induk
            $produk = Produk::findOrFail($request->produk_id);

            // Memastikan stok produk induk tidak kosong
            if ($produk->stok <= 0) {
                throw new \Exception('Stok produk induk tidak mencukupi.');
            }

            // Cari apakah produk satuan dengan produk_id dan satuan yang sama sudah ada
            $existingProdukSatuan = ProdukSatuan::where('produk_id', $request->produk_id)
                ->where('satuan', $request->satuan)
                ->first();

            if ($existingProdukSatuan) {
                // Jika sudah ada, tambahkan stoknya
                $existingProdukSatuan->increment('stok', $request->stok);
                // Mengurangi stok produk induk sebanyak 1
                $produk->decrement('stok', 1);
                $message = 'Stok Produk Satuan berhasil ditambahkan.';
            } else {
                // Jika belum ada, buat record ProdukSatuan baru
                $produkSatuan = new ProdukSatuan();
                $produkSatuan->produk_id = $request->produk_id;
                $produkSatuan->harga = $request->harga;
                $produkSatuan->stok = $request->stok;
                $produkSatuan->save();
                // Mengurangi stok produk induk sebanyak 1
                $produk->decrement('stok', 1);
                $message = 'Produk Satuan berhasil ditambahkan dan stok produk induk telah dikurangi.';
            }

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            // Redirect dengan pesan sukses
            return redirect()->route('produk_satuan.index')->with('success', $message);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Redirect dengan pesan error
            return redirect()->back()->with('error', 'Gagal menambahkan Produk Satuan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan detail dari ProdukSatuan tertentu.
     */
    public function show(ProdukSatuan $produkSatuan)
    {
        return view('produk_satuan.show', compact('produkSatuan'));
    }

    /**
     * Menampilkan form untuk mengedit ProdukSatuan tertentu.
     */
    public function edit(ProdukSatuan $produkSatuan)
    {
        $produk = Produk::all();
        return view('produk_satuan.edit', compact('produkSatuan', 'produk'));
    }

    /**
     * Memperbarui ProdukSatuan tertentu di database.
     */
    public function update(Request $request, ProdukSatuan $produkSatuan)
    {
        // Validasi data
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'satuan' => 'nullable|string|max:255',
        ]);

        // Update data produk satuan
        $produkSatuan->update($request->all());

        return redirect()->route('produk_satuan.index')->with('success', 'Produk Satuan berhasil diperbarui.');
    }

    /**
     * Menghapus ProdukSatuan dari database dan mengembalikan stok ke produk induk.
     */
    public function destroy(ProdukSatuan $produkSatuan)
    {
        DB::beginTransaction();
        try {
            // Dapatkan produk induk terkait
            $produk = Produk::findOrFail($produkSatuan->produk_id);

            // Tambahkan kembali stok produk satuan ke produk induk sebanyak 1
            $produk->increment('stok', 1);

            // Hapus produk satuan
            $produkSatuan->delete();

            DB::commit();
            return redirect()->route('produk_satuan.index')->with('success', 'Produk Satuan berhasil dihapus dan stok produk induk telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus Produk Satuan: ' . $e->getMessage());
        }
    }

    /**
     * Mendapatkan harga produk satuan berdasarkan produk_id
     * untuk autofill di form.
     */
    public function getHargaByProduk(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
        ]);

        $produkSatuan = ProdukSatuan::where('produk_id', $request->produk_id)->first();

        if ($produkSatuan) {
            return response()->json(['harga' => $produkSatuan->harga]);
        }

        return response()->json(['harga' => null]);
    }
}
