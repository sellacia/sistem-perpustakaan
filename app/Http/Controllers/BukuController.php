<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search'));

        if (!empty($search)) {
            $buku = Buku::where('judul', 'like', "%$search%")
                ->orWhere('pengarang', 'like', "%$search%")
                ->orWhere('kode_buku', 'like', "%$search%")
                ->get();
        } else {
            $buku = Buku::all();
        }

        return view('page.buku.index', compact('buku'));
    }

    public function show($id)
    {
        $buku = Buku::findOrFail($id);
        return view('page.buku.detail', compact('buku'));
    }

    public function formPinjam($id)
    {
        $buku = Buku::findOrFail($id);
        return view('page.buku.pinjam', compact('buku'));
    }

    public function prosesPinjam(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->stok > 0) {
            $buku->stok -= 1;

            if ($buku->stok == 0) {
                $buku->status = 'Dipinjam';
            }

            $buku->save();

            return redirect('/buku')->with('success', 'Buku berhasil dipinjam!');
        } else {
            return redirect('/buku')->with('error', 'Stok habis!');
        }
    }
}
