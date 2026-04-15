<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use Illuminate\Support\Facades\Storage;

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

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        Buku::create([
            'kode_buku' => $request->kode_buku,
            'judul' => $request->judul,
            'pengarang' => $request->pengarang,
            'penerbit' => $request->penerbit,
            'tahun' => $request->tahun,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'stok' => $request->stok,
            'status' => $request->status,
            'cover' => $coverPath
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
        
        $data = [
            'kode_buku' => $request->kode_buku,
            'judul' => $request->judul,
            'pengarang' => $request->pengarang,
            'penerbit' => $request->penerbit,
            'tahun' => $request->tahun,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'stok' => $request->stok,
            'status' => $request->status,
        ];

        if ($request->hasFile('cover')) {
            // Hapus cover lama jika ada
            if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
                Storage::disk('public')->delete($buku->cover);
            }
            $data['cover'] = $request->file('cover')->store('covers', 'public');
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
            return redirect('/petugas/buku')->with('error', 'Buku tidak bisa dihapus karena masih sedang dipinjam atau masih dalam proses pengembalian.');
        }

        // Hapus cover jika ada
        if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
            Storage::disk('public')->delete($buku->cover);
        }

        $buku->delete();

        return redirect('/petugas/buku')->with('success', 'Buku berhasil dihapus!');
    }
}
