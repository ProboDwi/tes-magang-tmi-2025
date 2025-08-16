<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        $satuan = Satuan::all();
        return view('satuan.index', compact('satuan'));
    }

    public function create()
    {
        return view('satuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'isi_per_satuan' => 'required|numeric|min:1',
        ]);

        Satuan::create($request->only('nama'));

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $satuan = Satuan::findOrFail($id);
        return view('satuan.edit', compact('satuan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'isi_per_satuan' => 'required|numeric|min:1',
        ]);

        $satuan = Satuan::findOrFail($id);
        $satuan->update($request->only('nama'));

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil diperbarui');
    }

    public function destroy($id)
    {
        Satuan::destroy($id);
        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil dihapus');
    }
}
