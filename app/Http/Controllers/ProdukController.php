<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::all();
        return view('produk.index', compact('produk'));
    }

    public function show($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.show', compact('produk'));
    }

    public function create()
    {
        // $kategori = Kategori::all();
        return view('produk.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required',
            'harga' => 'required',
            'stok' => 'required',
            // 'kategori_id' => 'required',
        ]);

        $produk = Produk::create([
            'nama_produk' => $request->input('nama_produk'),
            'harga' => $request->input('harga'),
            'stok' => $request->input('stok'),
            // 'kategori_id' => $request->input('kategori_id'),
        ]);

        if ($produk) {
            return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
        } else {
            return redirect()->route('produk.index')->with('error', 'Produk gagal ditambahkan');
        }
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        // $kategori = Kategori::all();
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required',
            'harga' => 'required',
            'stok' => 'required',
            // 'kategori_id' => 'required',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update([
            'nama_produk' => $request->input('nama_produk'),
            'harga' => $request->input('harga'),
            'stok' => $request->input('stok'),
            // 'kategori_id' => $request->input('kategori_id'),
        ]);

        if ($produk) {
            return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui');
        } else {
            return redirect()->route('produk.index')->with('error', 'Produk gagal diperbarui');
        }
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        if ($produk) {
            return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
        } else {
            return redirect()->route('produk.index')->with('error', 'Produk gagal dihapus');
        }
    }
}
