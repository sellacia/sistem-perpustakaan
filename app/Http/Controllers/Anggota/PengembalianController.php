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
    /**
     * Tampilkan daftar buku yang sedang dipinjam oleh anggota.
     */
    public function index()
    {
        $pinjam = Peminjaman::with('buku')
            ->where('anggota_id', Auth::id())
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->get();

        return view('anggota.pengembalian.index', compact('pinjam'));
    }

    /**
     * Anggota mengajukan pengembalian buku.
     * Kondisi buku default 'baik' — petugas yang nanti update kondisi saat menerima buku fisik.
     */
    public function proses(Request $request)
    {
        $request->validate([
            'pinjam_id' => 'required|integer',
        ]);

        $pinjam = Peminjaman::where('anggota_id', Auth::id())
            ->findOrFail($request->pinjam_id);

        if (!in_array($pinjam->status, ['dipinjam', 'terlambat'])) {
            return back()->with('error', 'Buku ini tidak bisa diajukan pengembalian sekarang.');
        }

        $tanggalKembali = Carbon::today();
        $batas          = Carbon::parse($pinjam->tanggal_wajib_kembali);

        // Hitung denda keterlambatan (kondisi masih 'baik' karena belum dicek petugas)
        $hariTerlambat = $tanggalKembali->gt($batas)
            ? (int) $batas->diffInDays($tanggalKembali)
            : 0;
        $dendaTerlambat = $hariTerlambat * 2000;

        // Update peminjaman: status dikembalikan, kondisi default baik
        // Denda final baru akan ditetapkan petugas saat menerima buku fisik
        $pinjam->kondisi        = 'baik'; // default; petugas bisa ubah via kembalikan()
        $pinjam->tanggal_kembali = $tanggalKembali;
        $pinjam->status          = 'dikembalikan';
        $pinjam->terlambat       = $hariTerlambat;
        $pinjam->denda           = $dendaTerlambat;
        $pinjam->status_denda    = $dendaTerlambat > 0 ? 'belum_bayar' : 'sudah_bayar';
        $pinjam->save();

        // Buat record denda jika terlambat
        if ($dendaTerlambat > 0) {
            \App\Models\Denda::updateOrCreate(
                ['peminjaman_id' => $pinjam->id],
                [
                    'terlambat'    => $hariTerlambat,
                    'jumlah_denda' => $dendaTerlambat,
                    'status'       => 'belum_bayar',
                ]
            );
        } else {
            \App\Models\Denda::where('peminjaman_id', $pinjam->id)->delete();
        }

        // Kembalikan stok (kondisi baik sementara sampai petugas cek)
        $buku = Buku::find($pinjam->buku_id);
        if ($buku) {
            $buku->increment('stok');
            $buku->refresh()->syncStatus();
        }

        return back()->with('success', 'Pengajuan pengembalian berhasil! Petugas akan memverifikasi kondisi buku.');
    }
}
