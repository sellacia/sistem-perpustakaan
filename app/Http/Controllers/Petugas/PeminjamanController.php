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

        $data = Peminjaman::with(['buku', 'anggota', 'denda'])->latest()->get();
        return view('petugas.peminjaman.index', compact('data'));
    }

    public function setujui($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        // ambil buku
        $buku = Buku::find($pinjam->buku_id);

        // cek stok lagi (biar aman)
        if ($buku && $buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis!');
        }

        // update status jadi dipinjam
        $pinjam->update([
            'status' => 'dipinjam',
            'tanggal_wajib_kembali' => now()->addDays(3)
        ]);

        // Stok sudah dikurangi semenjak peminjaman dari anggota,
        // jadi kita hapus decrement di sini.

        return back()->with('success', 'Peminjaman disetujui!');
    }

    public function tolak($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        $pinjam->update([
            'status' => 'ditolak'
        ]);

        // Karena stok dikurangi diawal submit, bila ditolak kita wajib mengembalikan stoknya.
        $buku = Buku::find($pinjam->buku_id);
        if ($buku) {
            $buku->increment('stok');
        }

        return back()->with('success', 'Peminjaman ditolak!');
    }

    public function kembalikan($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        $today = Carbon::today();
        $wajib = Carbon::parse($pinjam->tanggal_wajib_kembali);

        $terlambat = 0;
        $denda = 0;

        // hitung denda
        if ($today->gt($wajib)) {
            $terlambat = $today->diffInDays($wajib);
            $denda = $terlambat * 2000;
        }

        // update peminjaman
        $pinjam->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => $today
        ]);

        // balikin stok
        $buku = Buku::find($pinjam->buku_id);
        if ($buku) {
            $buku->increment('stok');
        }

        // simpan ke tabel DENDA (kalau ada)
        if ($denda > 0) {
            Denda::create([
                'peminjaman_id' => $pinjam->id,
                'terlambat' => $terlambat,
                'jumlah_denda' => $denda,
                'status' => 'belum_bayar'
            ]);
        }

        return back()->with('success', 'Buku berhasil dikembalikan!');
    }
}
