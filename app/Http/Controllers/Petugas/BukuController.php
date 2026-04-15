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
            'kode_buku' => 'required|unique:buku,kode_buku',
            'judul' => 'required',
            'pengarang' => 'required',
            'penerbit' => 'required',
            'tahun' => 'required',
            'kategori' => 'nullable',
            'deskripsi' => 'nullable',
            'stok' => 'required|integer',
            'status' => 'required',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        // upload cover (PAKAI PUBLIC)
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $namaFile = time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('assets/images'), $namaFile);

            $data['cover'] = $namaFile;
        }

        Buku::create($data);

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
            'kode_buku' => 'required|unique:buku,kode_buku,' . $id,
            'judul' => 'required',
            'pengarang' => 'required',
            'penerbit' => 'required',
            'tahun' => 'required',
            'kategori' => 'nullable',
            'deskripsi' => 'nullable',
            'stok' => 'required|integer',
            'status' => 'required',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $buku = Buku::findOrFail($id);

        $data = $request->all();

        // upload cover baru
        if ($request->hasFile('cover')) {

            // hapus cover lama
            if ($buku->cover && file_exists(public_path('assets/images/' . $buku->cover))) {
                unlink(public_path('assets/images/' . $buku->cover));
            }

            $file = $request->file('cover');
            $namaFile = time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('assets/images'), $namaFile);

            $data['cover'] = $namaFile;
        }

        $buku->update($data);

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
            return redirect('/petugas/buku')->with('error', 'Buku tidak bisa dihapus karena masih dipinjam.');
        }

        // hapus cover juga
        if ($buku->cover && file_exists(public_path('assets/images/' . $buku->cover))) {
            unlink(public_path('assets/images/' . $buku->cover));
        }

        $buku->delete();

        return redirect('/petugas/buku')->with('success', 'Buku berhasil dihapus!');
    }
}
