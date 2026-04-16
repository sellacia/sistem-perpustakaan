<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Denda;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * Daftar semua peminjaman. Update status 'terlambat' otomatis di sini.
     */
    public function index()
    {
        $today = Carbon::today();

        // Auto-update status: jika belum kembali dan sudah lewat batas → terlambat
        Peminjaman::whereIn('status', ['dipinjam'])
            ->where('tanggal_wajib_kembali', '<', $today)
            ->whereNull('tanggal_kembali')
            ->update(['status' => 'terlambat']);

        $data = Peminjaman::with(['buku', 'anggota', 'dendaData'])->latest()->get();

        return view('petugas.peminjaman.index', compact('data'));
    }

    /**
     * Petugas menyetujui pengajuan peminjaman anggota.
     */
    public function setujui($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        $buku   = Buku::find($pinjam->buku_id);

        if ($pinjam->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman ini sudah diproses sebelumnya.');
        }

        // Cek batas 3 buku aktif
        $pinjamanAktifAnggota = Peminjaman::where('anggota_id', $pinjam->anggota_id)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->count();

        if ($pinjamanAktifAnggota >= 3) {
            return back()->with('error', 'Anggota ini sudah mencapai batas maksimal 3 buku aktif.');
        }

        // Cek stok buku
        if (!$buku || $buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis!');
        }

        // Setujui: set status dipinjam & tanggal wajib kembali 7 hari dari sekarang
        $pinjam->update([
            'status'               => 'dipinjam',
            'tanggal_pinjam'       => now(),
            'tanggal_wajib_kembali' => now()->addDays(7),
            'tanggal_kembali'      => null,
        ]);

        // Kurangi stok
        $buku->decrement('stok');
        $buku->refresh()->syncStatus();

        return back()->with('success', 'Peminjaman disetujui!');
    }

    /**
     * Petugas menolak pengajuan peminjaman anggota.
     */
    public function tolak($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        if ($pinjam->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman ini sudah diproses sebelumnya.');
        }

        $pinjam->update([
            'status'       => 'ditolak',
            'alasan_tolak' => 'Ditolak oleh petugas',
        ]);

        return back()->with('success', 'Peminjaman ditolak.');
    }
}
