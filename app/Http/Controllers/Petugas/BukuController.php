<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::withCount('peminjamanAktif')->latest()->get();
        return view('petugas.buku.index', compact('buku'));
    }

    public function create()
    {
        return view('petugas.buku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_buku' => 'required',
            'judul' => 'required',
            'pengarang' => 'required',
            'penerbit' => 'required',
            'tahun' => 'required',
            'stok' => 'required|integer',
            'status' => 'required'
        ]);

        Buku::create([
            'kode_buku' => $request->kode_buku,
            'judul' => $request->judul,
            'pengarang' => $request->pengarang,
            'penerbit' => $request->penerbit,
            'tahun' => $request->tahun,
            'stok' => $request->stok,
            'status' => $request->status,
        ]);

        return redirect('/petugas/buku')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        return view('petugas.buku.edit', compact('buku'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_buku' => 'required',
            'judul' => 'required',
            'pengarang' => 'required',
            'penerbit' => 'required',
            'tahun' => 'required',
            'stok' => 'required|integer',
            'status' => 'required'
        ]);

        $buku = Buku::findOrFail($id);

        $buku->update([
            'kode_buku' => $request->kode_buku,
            'judul' => $request->judul,
            'pengarang' => $request->pengarang,
            'penerbit' => $request->penerbit,
            'tahun' => $request->tahun,
            'stok' => $request->stok,
            'status' => $request->status,
        ]);

        return redirect('/petugas/buku')->with('success', 'Buku berhasil diupdate!');
    }

    public function show($id)
    {
        $buku = Buku::findOrFail($id);
        return view('petugas.buku.detail', compact('buku'));
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->peminjamanAktif()->exists() || $buku->status === 'dipinjam') {
            return redirect('/petugas/buku')->with('error', 'Buku tidak bisa dihapus karena masih sedang dipinjam atau masih dalam proses pengembalian.');
        }

        $buku->delete();

        return redirect('/petugas/buku')->with('success', 'Buku berhasil dihapus!');
    }
}
