<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Denda;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PengembalianController extends Controller
{
    public function index()
    {
        $pinjam = Peminjaman::with('buku')
            ->where('nama', Auth::user()->name)
            ->where('status', 'dipinjam')
            ->get();

        return view('anggota.pengembalian.index', compact('pinjam'));
    }

    public function proses(Request $request)
    {
        $request->validate([
            'pinjam_id' => 'required',
            'tanggal_kembali' => 'required|date'
        ]);

        $pinjam = Peminjaman::findOrFail($request->pinjam_id);

        if ($pinjam->status != 'dipinjam') {
            return back()->with('error', 'Sudah dikembalikan!');
        }

        $kembali = Carbon::parse($request->tanggal_kembali);
        $batas = Carbon::parse($pinjam->tanggal_wajib_kembali);

        $terlambat = 0;
        $denda = 0;

        if ($kembali->gt($batas)) {
            $terlambat = $kembali->diffInDays($batas);
            $denda = $terlambat * 2000;

            if ($denda > 0) {
                \App\Models\Denda::create([
                    'peminjaman_id' => $pinjam->id,
                    'terlambat' => $terlambat,
                    'total_denda' => $denda,
                    'status' => 'belum_bayar'
                ]);
            }
        }

        $pinjam->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => $kembali
        ]);

        $buku = Buku::find($pinjam->buku_id);
        if ($buku) {
            $buku->increment('stok');
        }

        return back()->with('success', 'Pengembalian berhasil!');
    }
}
