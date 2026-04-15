<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;

class PengembalianController extends Controller
{
    private function sinkronkanStatusPengembalian(Peminjaman $pinjam): void
    {
        $statusDenda = $pinjam->dendaData->status ?? $pinjam->status_denda;

        if (in_array($statusDenda, ['sudah_bayar'], true)) {
            $pinjam->update([
                'status_denda' => 'sudah_bayar',
                'status' => 'selesai',
            ]);
            return;
        }

        if (in_array($statusDenda, ['menunggu_konfirmasi'], true)) {
            $pinjam->update(['status_denda' => 'menunggu_konfirmasi']);
            return;
        }

        if ($pinjam->status === 'terlambat' && $pinjam->tanggal_kembali) {
            $pinjam->update(['status' => 'dikembalikan']);
        }
    }

    public function index()
    {
        $pinjam = Peminjaman::with(['buku', 'anggota', 'dendaData'])
            ->whereIn('status', ['dikembalikan', 'terlambat', 'selesai'])
            ->latest()
            ->get();

        $pinjam->each(function ($item) {
            $this->sinkronkanStatusPengembalian($item);
            $item->refresh();
        });

        $pinjam = $pinjam->filter(fn($item) => in_array($item->status, ['dikembalikan', 'selesai'], true))->values();

        return view('petugas.pengembalian.index', compact('pinjam'));
    }

    public function konfirmasi($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        if ($pinjam->status !== 'dikembalikan') {
            return back()->with('error', 'Pengembalian ini tidak dapat dikonfirmasi.');
        }

        $pinjam->update([
            'status' => ($pinjam->dendaData && $pinjam->dendaData->status === 'belum_bayar') ? 'dikembalikan' : 'selesai',
        ]);

        if ($pinjam->dendaData && $pinjam->dendaData->status === 'belum_bayar') {
            return back()->with('error', 'Denda belum dikonfirmasi. Selesaikan pembayaran denda terlebih dahulu.');
        }

        return back()->with('success', 'Pengembalian dikonfirmasi!');
    }
}
