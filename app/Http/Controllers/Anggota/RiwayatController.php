<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $today = Carbon::today();

        // Ambil peminjaman yang masih 'dipinjam' tapi sudah lewat batas
        $peminjamanDipinjam = Peminjaman::where('anggota_id', $userId)
            ->where('status', 'dipinjam')
            ->get();

        foreach ($peminjamanDipinjam as $pinjam) {
            $batas = Carbon::parse($pinjam->tanggal_wajib_kembali);
            if ($today->gt($batas)) {
                $pinjam->update(['status' => 'terlambat']);

                $terlambat = (int) $batas->diffInDays($today);
                if ($terlambat > 0) {
                    Denda::updateOrCreate(
                        ['peminjaman_id' => $pinjam->id],
                        [
                            'terlambat'    => $terlambat,
                            'jumlah_denda' => $terlambat * 2000,
                            'status'       => 'belum_bayar',
                        ]
                    );
                }
            }
        }

        // Ambil SEMUA riwayat peminjaman user ini (exclude yang sudah selesai)
        $data = Peminjaman::with(['buku', 'dendaData'])
            ->where('anggota_id', $userId)
            ->whereIn('status', ['dipinjam', 'dikembalikan', 'terlambat', 'ditolak', 'menunggu'])
            ->latest()
            ->get();

        return view('anggota.riwayat.index', compact('data'));
    }

    public function bayar($id)
    {
        $denda = Denda::findOrFail($id);
        $denda->status = 'sudah_bayar';
        $denda->save();

        return redirect()->route('anggota.riwayat')->with('success', 'Denda berhasil dibayar! Menunggu konfirmasi petugas.');
    }
}
