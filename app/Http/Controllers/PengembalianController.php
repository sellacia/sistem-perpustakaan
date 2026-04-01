<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    public function index()
    {
        // contoh ambil buku pertama (sementara)
        $buku = Buku::first();

        $tanggal_pinjam = Carbon::now()->subDays(5);
        $batas_kembali = Carbon::now()->subDays(2);
        $tanggal_kembali = Carbon::now();

        $terlambat = 0;
        $denda = 0;

        if ($tanggal_kembali > $batas_kembali) {
            $terlambat = $tanggal_kembali->diffInDays($batas_kembali);
            $denda = $terlambat * 2000;
        }

        return view('page.pengembalian.index', compact(
            'buku',
            'tanggal_pinjam',
            'batas_kembali',
            'tanggal_kembali',
            'terlambat',
            'denda'
        ));
    }

    public function proses(Request $request)
    {
        $buku = Buku::find($request->buku_id);

        if ($buku) {
            $buku->stok += 1;
            $buku->status = 'Tersedia';
            $buku->save();
        }

        return redirect('/pengembalian')->with('success', 'Buku berhasil dikembalikan!');
    }
}
