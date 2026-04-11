<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use App\Models\Buku;

class BukuController extends Controller
{
    // KATALOG
    public function index()
    {
        $buku = Buku::latest()->get();

        return view('kepala.buku.index', compact('buku'));
    }

    // DETAIL
    public function show($id)
    {
        $buku = Buku::findOrFail($id);

        return view('kepala.buku.detail', compact('buku'));
    }
}
