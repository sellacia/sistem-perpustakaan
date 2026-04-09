<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->search);

        $query = Buku::with('peminjaman')
                    ->where('stok', '>', 0); //  FILTER STOK

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('pengarang', 'like', "%$search%")
                  ->orWhere('kode_buku', 'like', "%$search%");
            });
        }

        $buku = $query->get();

        return view('anggota.buku.index', compact('buku'));
    }

    public function show($id)
    {
        $buku = Buku::findOrFail($id);
        return view('anggota.buku.detail', compact('buku'));
    }

    public function formPinjam($id)
    {
        $buku = Buku::findOrFail($id);
        return view('anggota.buku.pinjam', compact('buku'));
    }

    public function prosesPinjam(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        //  CEGAH STOK MINUS
        if ($buku->stok <= 0) {
            return redirect('/buku')->with('error', 'Stok habis!');
        }

        // kurangi stok
        $buku->decrement('stok');

        // update status
        if ($buku->stok == 0) {
            $buku->status = 'dipinjam';
        } else {
            $buku->status = 'tersedia';
        }

        $buku->save();

        return redirect('/buku')->with('success', 'Buku berhasil dipinjam!');
    }
}
