<?php

namespace App\Http\Controllers;

use App\Models\ProdukSatuan;
use App\Models\KonversiSatuan;
use App\Models\Produk;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KonversiSatuanController extends Controller
{
    public function index()
    {
        $produk = Produk::all();
        $satuan = Satuan::all();
        $konversi = KonversiSatuan::with('produk', 'dariSatuan', 'keSatuan')->get();
        return view('konversi_satuan.index', compact('konversi', 'produk', 'satuan'));
    }

    public function create()
    {
        $produkSatuan = ProdukSatuan::with('produk', 'satuan')->get();
        return view('konversi_satuan.create', compact('produkSatuan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'dari_satuan_id' => 'required|exists:satuans,id',
            'ke_satuan_id' => 'required|exists:satuans,id',
            'jumlah_konversi' => 'required|integer|min:1',
            // Asumsi: stok_dipotong adalah 1 ball, stok_ditambah adalah 20 pcs
        ]);
        
        $stok_dipotong = $request->input('jumlah_konversi');
        $stok_ditambah = $request->input('jumlah_konversi') * 20;

        KonversiSatuan::create([
            'produk_id' => $request->produk_id,
            'dari_satuan_id' => $request->dari_satuan_id,
            'ke_satuan_id' => $request->ke_satuan_id,
            'jumlah_konversi' => $request->jumlah_konversi,
            'stok_dipotong' => $stok_dipotong,
            'stok_ditambah' => $stok_ditambah,
            'requested_by' => Auth::id(),
        ]);

        return redirect()->route('konversi_satuan.index')->with('success', 'Konversi diajukan, menunggu persetujuan pemilik');
    }

    // Metode untuk mengajukan konversi dari "ball" ke "pcs", mengambil stok dari tabel 'produks'
    public function storeKonversiBallToPcs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'produk_id' => 'required|exists:produks,id',
            'jumlah_ball_to_pcs' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Cari produk dan pastikan stoknya cukup
        $produk = Produk::findOrFail($request->produk_id);
        if ($produk->stok < $request->jumlah_ball_to_pcs) {
            return redirect()->back()->with('error', 'Stok ball pada produk tidak mencukupi untuk konversi.');
        }

        // Cari ID satuan 'ball' dan 'pcs'
        $satuanBall = Satuan::where('nama_satuan', 'ball')->first();
        $satuanPcs = Satuan::where('nama_satuan', 'pcs')->first();

        // Pastikan satuan "ball" dan "pcs" ada di database
        if (!$satuanBall || !$satuanPcs) {
            return redirect()->back()->with('error', 'Satuan "ball" atau "pcs" tidak ditemukan.');
        }

        // Tentukan jumlah konversi (asumsi 1 ball = 20 pcs)
        $jumlahBall = $request->jumlah_ball_to_pcs;
        $jumlahPcs = $jumlahBall * 20;

        // Buat entri konversi baru dengan status 'pending'
        KonversiSatuan::create([
            'produk_id' => $request->produk_id,
            'dari_satuan_id' => $satuanBall->id,
            'ke_satuan_id' => $satuanPcs->id,
            'jumlah_konversi' => $jumlahBall,
            'stok_dipotong' => $jumlahBall,
            'stok_ditambah' => $jumlahPcs,
            'requested_by' => Auth::id(),
            'status' => 'pending',
        ]);

        return redirect()->route('konversi_satuan.index')->with('success', 'Permohonan konversi Ball ke Pcs berhasil diajukan.');
    }

    // Pemilik approve konversi
    public function approve($id)
    {
        $konversi = KonversiSatuan::findOrFail($id);

        if ($konversi->status !== 'pending') {
            return back()->with('error', 'Konversi sudah diproses');
        }

        // Ambil produk utama untuk mengurangi stok
        $produk = Produk::findOrFail($konversi->produk_id);
        
        // Validasi stok produk utama
        if ($produk->stok < $konversi->stok_dipotong) {
            return back()->with('error', 'Stok ball tidak mencukupi pada produk utama.');
        }
        
        // Kurangi stok dari produk utama
        $produk->stok -= $konversi->stok_dipotong;
        $produk->save();

        // Cari ProdukSatuan tujuan (produk_id + ke_satuan_id)
        $tujuan = ProdukSatuan::where('produk_id', $konversi->produk_id)
            ->where('satuan_id', $konversi->ke_satuan_id)
            ->first();

        if ($tujuan) {
            // Jika sudah ada stok tujuan, tambah stok
            $tujuan->stok += $konversi->stok_ditambah;
            $tujuan->save();
        } else {
            // Jika belum ada, buat data produk satuan baru
            ProdukSatuan::create([
                'produk_id' => $konversi->produk_id,
                'satuan_id' => $konversi->ke_satuan_id,
                'harga' => 0, // Harga bisa disesuaikan di sini
                'stok' => $konversi->stok_ditambah,
            ]);
        }

        // Update status konversi
        $konversi->status = 'disetujui';
        $konversi->approved_by = Auth::id(); // pastikan kolom ini ada di DB dan model
        $konversi->save();

        return back()->with('success', 'Konversi disetujui & stok diperbarui');
    }

    // Halaman persetujuan pemilik
    public function persetujuan()
    {
        $data = KonversiSatuan::with('produk', 'dariSatuan', 'keSatuan')
            ->where('status', 'pending')
            ->get();

        return view('konversi_satuan.persetujuan', compact('data'));
    }

    public function reject($id)
    {
        $konversi = KonversiSatuan::findOrFail($id);

        if ($konversi->status !== 'pending') {
            return back()->with('error', 'Konversi sudah diproses');
        }

        $konversi->status = 'ditolak';
        $konversi->save();

        return back()->with('success', 'Konversi berhasil ditolak');
    }

    public function destroy($id)
    {
        KonversiSatuan::destroy($id);
        return back()->with('success', 'Data konversi dihapus');
    }
}
