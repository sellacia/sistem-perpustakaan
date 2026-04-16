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

        $peminjamanTelat = Peminjaman::whereIn('status', ['dipinjam', 'terlambat', 'menunggu'])
            ->where('tanggal_wajib_kembali', '<', $today)
            ->whereNull('tanggal_kembali')
            ->get();

        foreach ($peminjamanTelat as $pinjam) {
            if ($pinjam->status === 'dipinjam' || $pinjam->status === 'terlambat') {
                $pinjam->update(['status' => 'terlambat']);
            }

            $hasilDenda = $pinjam->hitungDenda($today);
            $terlambat = $hasilDenda['terlambat'];

            if ($terlambat > 0) {
                $statusDendaSaatIni = in_array($pinjam->status_denda, ['menunggu_konfirmasi', 'sudah_bayar'], true)
                    ? $pinjam->status_denda
                    : ($pinjam->dendaData->status ?? $pinjam->status_denda ?? 'belum_bayar');

                $pinjam->update([
                    'terlambat' => $terlambat,
                    'denda' => $hasilDenda['jumlah_denda'],
                    'status_denda' => $statusDendaSaatIni,
                ]);

                Denda::updateOrCreate(
                    ['peminjaman_id' => $pinjam->id],
                    [
                        'terlambat'    => $terlambat,
                        'jumlah_denda' => $hasilDenda['jumlah_denda'],
                        'status'       => $statusDendaSaatIni,
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

        if ($pinjam->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman ini sudah diproses sebelumnya.');
        }

        $pinjamanAktifAnggota = Peminjaman::where('anggota_id', $pinjam->anggota_id)
            ->borrowingInProgress()
            ->count();

        if ($pinjamanAktifAnggota > 3) {
            return back()->with('error', 'Anggota ini sudah mencapai batas maksimal 3 buku aktif.');
        }

        if ($buku && $buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis!');
        }

        $pinjam->update([
            'status'               => 'dipinjam',
            'tanggal_wajib_kembali' => now()->addDays(7),
            'tanggal_kembali'      => null,
        ]);

        //  TAMBAHAN (INI DOANG YANG DIUBAH)
        if ($buku) {
            $buku->decrement('stok');
            $buku->refresh()->syncStatus();
        }

        return back()->with('success', 'Peminjaman disetujui!');
    }
    public function tolak($id)
    {
        $pinjam = Peminjaman::with('dendaData')->findOrFail($id);

        if ($pinjam->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman ini sudah diproses sebelumnya.');
        }

        $alasan = [];

        // CEK: masih pinjam buku lain
        $masihPinjam = Peminjaman::where('anggota_id', $pinjam->anggota_id)
            ->where('status', 'dipinjam')
            ->exists();

        if ($masihPinjam) {
            $alasan[] = 'Masih memiliki buku yang belum dikembalikan';
        }

        //  CEK: ada denda
        if ($pinjam->dendaData && $pinjam->dendaData->jumlah > 0) {
            $alasan[] = 'Memiliki tunggakan denda';
        }

        $alasanText = implode(', ', $alasan);

        //  update
        $pinjam->update([
            'status' => 'ditolak',
            'alasan_tolak' => $alasanText ?: 'Tidak memenuhi syarat'
        ]);

        //  balikin stok
        $buku = Buku::find($pinjam->buku_id);
        if ($buku) {
            $buku->increment('stok');
            $buku->refresh()->syncStatus();
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
        $hasilDenda = $pinjam->hitungDenda($today);
        $terlambat = $hasilDenda['terlambat'];
        $jumlahDenda = $hasilDenda['jumlah_denda'];

        $pinjam->update([
            'status'          => 'dikembalikan',
            'tanggal_kembali' => $today,
            'terlambat'       => $terlambat,
            'denda'           => $jumlahDenda,
            'status_denda'    => $jumlahDenda > 0 ? 'belum_bayar' : 'sudah_bayar',
        ]);

        // Kembalikan stok
        $buku = Buku::find($pinjam->buku_id);
        if ($buku) {
            $buku->increment('stok');
            $buku->refresh()->syncStatus();
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
        } else {
            Denda::where('peminjaman_id', $pinjam->id)->delete();
        }

        return redirect()->route('petugas.pengembalian')
            ->with('success', 'Buku dikembalikan & denda masuk ke kelola denda');
    }
}
