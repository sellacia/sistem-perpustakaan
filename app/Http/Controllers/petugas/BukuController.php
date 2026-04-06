<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    public function store(Request $request)
    {
        Buku::create([
            'kode_buku' => $request->kode_buku,
            'judul' => $request->judul,
            'pengarang' => $request->pengarang,
            'penerbit' => $request->penerbit,
            'tahun' => $request->tahun,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'status' => $request->status,
        ]);

        return redirect('/petugas/buku')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $buku->update([
            'kode_buku' => $request->kode_buku,
            'judul' => $request->judul,
            'pengarang' => $request->pengarang,
            'penerbit' => $request->penerbit,
            'tahun' => $request->tahun,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'status' => $request->status,
        ]);

        return redirect('/petugas/buku')->with('success', 'Buku berhasil diupdate!');
    }

    public function index()
    {
        $buku = Buku::all();
        return view('petugas.dashboard.buku.index', compact('buku'));
    }

    public function create()
    {
        return view('petugas.dashboard.buku.create');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        return view('petugas.dashboard.buku.edit', compact('buku'));
    }

    // INI YANG DIPERBAIKI (dari detail → show)
    public function show($id)
    {
        $buku = Buku::findOrFail($id);
        return view('petugas.dashboard.buku.detail', compact('buku'));
    }
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect('/petugas/buku')->with('success', 'Buku berhasil dihapus!');
    }
}
