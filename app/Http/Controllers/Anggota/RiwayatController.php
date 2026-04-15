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
            $hasilDenda = $pinjam->hitungDenda($today);

            if ($hasilDenda['terlambat'] > 0) {
                $pinjam->update(['status' => 'terlambat']);

                Denda::updateOrCreate(
                    ['peminjaman_id' => $pinjam->id],
                    [
                        'terlambat'    => $hasilDenda['terlambat'],
                        'jumlah_denda' => $hasilDenda['jumlah_denda'],
                        'status'       => 'belum_bayar',
                    ]
                );
            }
        }

        $data = Peminjaman::with(['buku', 'dendaData'])
            ->where('anggota_id', $userId)
            ->whereIn('status', ['dipinjam', 'dikembalikan', 'terlambat', 'ditolak', 'menunggu', 'selesai'])
            ->latest()
            ->get();

        return view('anggota.riwayat.index', compact('data'));
    }

    public function bayar($id)
    {
        $denda = Denda::whereHas('peminjaman', function ($query) {
            $query->where('anggota_id', Auth::id());
        })->findOrFail($id);

        if ($denda->status === 'sudah_bayar') {
            return redirect()->route('anggota.riwayat')->with('success', 'Denda ini sudah ditandai lunas.');
        }

        if ($denda->status === 'menunggu_konfirmasi') {
            return redirect()->route('anggota.riwayat')->with('success', 'Permintaan konfirmasi pembayaran sudah pernah dikirim.');
        }

        $denda->update(['status' => 'menunggu_konfirmasi']);
        $denda->peminjaman?->update(['status_denda' => 'menunggu_konfirmasi']);

        return redirect()->route('anggota.riwayat')->with('success', 'Permintaan pembayaran sudah dicatat. Silakan hubungi petugas untuk konfirmasi pelunasan.');
    }
}
