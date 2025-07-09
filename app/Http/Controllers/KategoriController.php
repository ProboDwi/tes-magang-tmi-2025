<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        return view('kategori.index', compact('kategori'));
    }

    public function show ($id) {
        $kategori = Kategori::findOrFail($id);
        return view('kategori.show', compact('kategori'));
    }

    public function create () {
        $kategori = Kategori::all();
        return view('kategori.create', compact('kategori'));
    }

    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
        ]);

        $kategori = Kategori::create([
            'nama' => $request->input('nama'),
        ]);

        if ($kategori) {
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan');
        } else {
            return redirect()->route('kategori.index')->with('error', 'Kategori gagal ditambahkan');
        }
    }

    public function edit ($id) {
        $kategori = Kategori::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'nama' => $request->input('nama'),
        ]);

        if ($kategori) {
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui');
        } else {
            return redirect()->route('kategori.index')->with('error', 'Kategori gagal diperbarui');
        }
    }

    public function destroy($id) {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        if ($kategori) {
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
        } else {
            return redirect()->route('kategori.index')->with('error', 'Kategori gagal dihapus');
        }
    }
}
