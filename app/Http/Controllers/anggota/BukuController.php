<?php

namespace App\Http\Controllers\anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->search);

        if ($search) {
            $buku = Buku::where('judul', 'like', "%$search%")
                ->orWhere('pengarang', 'like', "%$search%")
                ->orWhere('kode_buku', 'like', "%$search%")
                ->get();
        } else {
            $buku = Buku::all();
        }

        return view('anggota.buku.index', compact('buku')); //  FIX
    }

    public function show($id)
    {
        $buku = Buku::findOrFail($id);
        return view('anggota.buku.detail', compact('buku')); //  FIX
    }

    public function formPinjam($id)
    {
        $buku = Buku::findOrFail($id);
        return view('anggota.buku.pinjam', compact('buku')); //  FIX
    }

    public function prosesPinjam(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->stok <= 0) {
            return redirect('/buku')->with('error', 'Stok habis!');
        }

        $buku->stok -= 1;

        if ($buku->stok == 0) {
            $buku->status = 'dipinjam';
        }

        $buku->save();

        return redirect('/buku')->with('success', 'Buku berhasil dipinjam!');
    }
}
