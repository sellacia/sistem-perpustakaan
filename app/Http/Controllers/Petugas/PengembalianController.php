<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    /**
     * Halaman daftar pengembalian: hanya yang sudah 'dikembalikan' oleh anggota
     * dan menunggu konfirmasi petugas.
     */
    public function index()
    {
        $pinjam = Peminjaman::with(['buku', 'anggota', 'dendaData'])
            ->whereIn('status', ['dikembalikan', 'selesai'])
            ->latest()
            ->get();

        return view('petugas.pengembalian.index', compact('pinjam'));
    }

    /**
     * Petugas memproses pengembalian fisik buku dari anggota.
     * Inilah saat petugas menentukan kondisi buku (baik / rusak / hilang).
     */
    public function kembalikan(Request $request, $id)
    {
        $request->validate([
            'kondisi' => 'required|in:baik,rusak,hilang',
        ]);

        $pinjam = Peminjaman::with('dendaData')->findOrFail($id);

        if (!in_array($pinjam->status, ['dipinjam', 'terlambat'])) {
            return back()->with('error', 'Status peminjaman tidak valid untuk pengembalian.');
        }

        $kondisi = $request->kondisi;

        // 1. Set kondisi & tanggal_kembali ke objek dulu (bukan update DB dulu)
        //    agar hitungDenda() bisa baca $this->kondisi dengan benar.
        $pinjam->kondisi       = $kondisi;
        $pinjam->tanggal_kembali = now();

        // 2. Hitung denda SETELAH kondisi & tanggal_kembali di-set di objek
        $hasilDenda = $pinjam->hitungDenda(now());
        $terlambat  = $hasilDenda['terlambat'];
        $jumlahDenda = $hasilDenda['jumlah_denda'];

        // 3. Simpan semua ke database sekaligus
        $pinjam->status       = 'dikembalikan';
        $pinjam->terlambat    = $terlambat;
        $pinjam->denda        = $jumlahDenda;
        $pinjam->status_denda = $jumlahDenda > 0 ? 'belum_bayar' : 'sudah_bayar';
        $pinjam->save();

        // 4. Buat / update record denda
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
            // Tidak ada denda sama sekali → hapus record denda lama (jika ada)
            Denda::where('peminjaman_id', $pinjam->id)->delete();
        }

        // 5. Kelola stok buku:
        //    - Kondisi BAIK  → stok kembali normal (+1)
        //    - Kondisi RUSAK → stok kembali, tapi buku perlu diperbaiki — petugas
        //      bisa urus manual; untuk sederhananya kita tetap naikkan stok.
        //    - Kondisi HILANG → stok TIDAK dikembalikan (buku hilang)
        $buku = Buku::find($pinjam->buku_id);
        if ($buku) {
            if ($kondisi !== 'hilang') {
                $buku->increment('stok');
            }
            $buku->refresh()->syncStatus();
        }

        return redirect()->route('petugas.pengembalian')
            ->with('success', 'Buku berhasil dikembalikan. ' . ($jumlahDenda > 0 ? 'Denda Rp ' . number_format($jumlahDenda, 0, ',', '.') . ' telah dicatat.' : 'Tidak ada denda.'));
    }

    /**
     * Petugas mengkonfirmasi pengembalian → status jadi 'selesai'.
     * Hanya bisa dikonfirmasi jika denda sudah lunas (atau tidak ada denda).
     */
    public function konfirmasi($id)
    {
        $pinjam = Peminjaman::with('dendaData')->findOrFail($id);

        if ($pinjam->status !== 'dikembalikan') {
            return back()->with('error', 'Pengembalian ini tidak dapat dikonfirmasi.');
        }

        // Cek apakah ada denda yang belum lunas
        $adaDendaBelumLunas = $pinjam->dendaData
            && in_array($pinjam->dendaData->status, ['belum_bayar', 'menunggu_konfirmasi']);

        if ($adaDendaBelumLunas) {
            return back()->with('error', 'Denda belum lunas. Selesaikan pembayaran denda terlebih dahulu sebelum mengkonfirmasi pengembalian.');
        }

        // Tidak ada denda atau denda sudah lunas → selesai
        $pinjam->update([
            'status'       => 'selesai',
            'status_denda' => 'sudah_bayar',
        ]);

        return back()->with('success', 'Pengembalian berhasil dikonfirmasi!');
    }
}
