<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PengembalianController extends Controller
{
    public function index()
    {
        $pinjam = Peminjaman::with('buku')
            ->where('anggota_id', Auth::id())
            ->whereIn('status', ['dipinjam', 'terlambat'])  // Include terlambat juga
            ->get();

        return view('anggota.pengembalian.index', compact('pinjam'));
    }

    public function proses(Request $request)
    {
        $request->validate([
            'pinjam_id' => 'required'
        ]);

        $pinjam = Peminjaman::where('anggota_id', Auth::id())->findOrFail($request->pinjam_id);

        if (!in_array($pinjam->status, ['dipinjam', 'terlambat'])) {
            return back()->with('error', 'Sudah dikembalikan atau status tidak valid!');
        }

        $kembali = Carbon::today();
        $batas = Carbon::parse($pinjam->tanggal_wajib_kembali);

        $terlambat = 0;
        $denda = 0;

        if ($kembali->gt($batas)) {
            $terlambat = $kembali->diffInDays($batas);
            $denda = $terlambat * 2000;  // Ubah dari 1000 menjadi 2000 agar konsisten

            if ($denda > 0) {
                \App\Models\Denda::updateOrCreate(
                    ['peminjaman_id' => $pinjam->id],
                    [
                        'terlambat' => $terlambat,
                        'jumlah_denda' => $denda,
                        'status' => 'belum_bayar'
                    ]
                );
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
