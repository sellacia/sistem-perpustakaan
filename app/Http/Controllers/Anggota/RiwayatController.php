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
        $today  = Carbon::today();

        // Auto-update status terlambat untuk yang belum kembali
        Peminjaman::where('anggota_id', $userId)
            ->where('status', 'dipinjam')
            ->where('tanggal_wajib_kembali', '<', $today)
            ->whereNull('tanggal_kembali')
            ->update(['status' => 'terlambat']);

        $data = Peminjaman::with(['buku', 'dendaData'])
            ->where('anggota_id', $userId)
            ->latest()
            ->get();

        return view('anggota.riwayat.index', compact('data'));
    }

    /**
     * Anggota mengajukan konfirmasi pembayaran denda.
     */
    public function bayar($id)
    {
        $denda = Denda::whereHas('peminjaman', function ($query) {
            $query->where('anggota_id', Auth::id());
        })->findOrFail($id);

        if ($denda->status === 'sudah_bayar') {
            return redirect()->route('anggota.riwayat')
                ->with('success', 'Denda ini sudah lunas.');
        }

        if ($denda->status === 'menunggu_konfirmasi') {
            return redirect()->route('anggota.riwayat')
                ->with('info', 'Permintaan konfirmasi pembayaran sudah dikirim. Menunggu petugas.');
        }

        $denda->update(['status' => 'menunggu_konfirmasi']);
        $denda->peminjaman?->update(['status_denda' => 'menunggu_konfirmasi']);

        return redirect()->route('anggota.riwayat')
            ->with('success', 'Pengajuan pembayaran dikirim. Silakan hubungi petugas untuk konfirmasi pelunasan.');
    }
}
