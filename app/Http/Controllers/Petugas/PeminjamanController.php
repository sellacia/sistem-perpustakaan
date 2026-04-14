<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Denda;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Auto-update status terlambat & buat denda
        $peminjamanTelat = Peminjaman::whereIn('status', ['dipinjam', 'terlambat', 'menunggu'])
            ->where('tanggal_wajib_kembali', '<', $today)
            ->whereNull('tanggal_kembali')
            ->get();

        foreach ($peminjamanTelat as $pinjam) {
            $pinjam->update(['status' => 'terlambat']);

            $batas     = Carbon::parse($pinjam->tanggal_wajib_kembali);
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

        $data = Peminjaman::with(['buku', 'anggota', 'dendaData'])->latest()->get();
        return view('petugas.peminjaman.index', compact('data'));
    }

    public function setujui($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        $buku   = Buku::find($pinjam->buku_id);

        if ($buku && $buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis!');
        }

        $pinjam->update([
            'status'               => 'dipinjam',
            'tanggal_wajib_kembali' => now()->addDays(7),
        ]);

        return back()->with('success', 'Peminjaman disetujui!');
    }

    public function tolak($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        $pinjam->update(['status' => 'ditolak']);

        // Kembalikan stok karena stok dikurangi saat pengajuan
        $buku = Buku::find($pinjam->buku_id);
        if ($buku) {
            $buku->increment('stok');
        }

        return back()->with('success', 'Peminjaman ditolak!');
    }

    public function kembalikan($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        if (!in_array($pinjam->status, ['dipinjam', 'terlambat'])) {
            return back()->with('error', 'Status peminjaman tidak valid untuk pengembalian!');
        }

        $today = Carbon::today();
        $wajib = Carbon::parse($pinjam->tanggal_wajib_kembali);

        $terlambat = 0;
        $jumlahDenda = 0;

        if ($today->gt($wajib)) {
            $terlambat   = (int) $wajib->diffInDays($today);
            $jumlahDenda = $terlambat * 2000;
        }

        $pinjam->update([
            'status'          => 'dikembalikan',
            'tanggal_kembali' => $today,
            'terlambat'       => $terlambat,
            'denda'           => $jumlahDenda,
        ]);

        // Kembalikan stok
        $buku = Buku::find($pinjam->buku_id);
        if ($buku) {
            $buku->increment('stok');
        }

        // Simpan denda jika ada
        if ($jumlahDenda > 0) {
            Denda::updateOrCreate(
                ['peminjaman_id' => $pinjam->id],
                [
                    'terlambat'    => $terlambat,
                    'jumlah_denda' => $jumlahDenda,
                    'status'       => 'belum_bayar',
                ]
            );
        }

        return back()->with('success', 'Buku berhasil dikembalikan!');
    }
}
