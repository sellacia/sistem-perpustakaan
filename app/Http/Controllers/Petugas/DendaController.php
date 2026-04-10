<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Denda;
use Carbon\Carbon;

class DendaController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Otomatis cek peminjaman yang telat di seluruh sistem
        $peminjamanTelat = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_wajib_kembali', '<', $today)
            ->get();

        foreach ($peminjamanTelat as $pinjam) {
            $pinjam->update(['status' => 'terlambat']);
            
            $batas = Carbon::parse($pinjam->tanggal_wajib_kembali);
            $terlambat = $today->diffInDays($batas);

            Denda::updateOrCreate(
                ['peminjaman_id' => $pinjam->id],
                [
                    'terlambat' => $terlambat,
                    'jumlah_denda' => $terlambat * 2000,
                    'status' => 'belum_bayar'
                ]
            );
        }

        $data = Denda::with('peminjaman.buku','peminjaman.anggota')
            ->latest()
            ->get();

        return view('petugas.denda.index', compact('data'));
    }

    public function bayar($id)
    {
        $denda = Denda::findOrFail($id);

        $denda->update([
            'status' => 'sudah_bayar'
        ]);

        return back()->with('success', 'Pembayaran denda berhasil dikonfirmasi!');
    }
}
