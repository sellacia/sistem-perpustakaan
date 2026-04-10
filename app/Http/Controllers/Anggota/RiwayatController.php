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
                // Update status ke terlambat
                $pinjam->update(['status' => 'terlambat']);
                
                // Cek apakah data denda sudah ada
                $dendaExist = Denda::where('peminjaman_id', $pinjam->id)->exists();
                if (!$dendaExist) {
                    $terlambat = $today->diffInDays($batas);
                    Denda::create([
                        'peminjaman_id' => $pinjam->id,
                        'terlambat' => $terlambat,
                        'jumlah_denda' => $terlambat * 2000,
                        'status' => 'belum_bayar'
                    ]);
                }
            }
        }

        // Ambil SEMUA riwayat peminjaman user ini
        $data = Peminjaman::with(['buku', 'denda'])
            ->where('anggota_id', $userId)
            ->whereIn('status', ['dipinjam', 'dikembalikan', 'selesai', 'terlambat', 'ditolak'])
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
