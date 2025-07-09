<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\KategoriImport;
use Maatwebsite\Excel\Facades\Excel;

class KategoriImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new KategoriImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data kategori berhasil diimpor.');
    }
}
