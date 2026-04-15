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
        $hasilDenda = $pinjam->hitungDenda($kembali);
        $terlambat = $hasilDenda['terlambat'];
        $denda = $hasilDenda['jumlah_denda'];

        if ($denda > 0) {
            \App\Models\Denda::updateOrCreate(
                ['peminjaman_id' => $pinjam->id],
                [
                    'terlambat' => $terlambat,
                    'jumlah_denda' => $denda,
                    'status' => 'belum_bayar'
                ]
            );
        } else {
            \App\Models\Denda::where('peminjaman_id', $pinjam->id)->delete();
        }

        $pinjam->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => $kembali,
            'terlambat' => $terlambat,
            'denda' => $denda,
            'status_denda' => $denda > 0 ? 'belum_bayar' : 'sudah_bayar',
        ]);

        $buku = Buku::find($pinjam->buku_id);
        if ($buku) {
            $buku->increment('stok');
            $buku->refresh()->syncStatus();
        }

        return back()->with('success', 'Pengembalian berhasil!');
    }
}
