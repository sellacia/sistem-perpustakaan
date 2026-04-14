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
        $this->generateDenda();

        $data = Denda::with(['peminjaman.buku', 'peminjaman.anggota'])
            ->orderByRaw("FIELD(status, 'belum_bayar', 'sudah_bayar')")
            ->orderByDesc('created_at')
            ->get();

        return view('petugas.denda.index', compact('data'));
    }

    private function generateDenda()
    {
        $today = Carbon::today();

        $belumKembali = Peminjaman::whereIn('status', ['dipinjam', 'terlambat', 'menunggu'])
            ->where('tanggal_wajib_kembali', '<', $today)
            ->whereNull('tanggal_kembali')
            ->get();

        $kembaliTelat = Peminjaman::whereColumn('tanggal_kembali', '>', 'tanggal_wajib_kembali')
            ->whereNotNull('tanggal_kembali')
            ->get();

        $semua = $belumKembali->merge($kembaliTelat);

        foreach ($semua as $pinjam) {
            if ($pinjam->status !== 'terlambat') {
                $pinjam->update(['status' => 'terlambat']);
            }

            $batas = Carbon::parse($pinjam->tanggal_wajib_kembali);

            if ($pinjam->tanggal_kembali) {
                $terlambat = (int) $batas->diffInDays(Carbon::parse($pinjam->tanggal_kembali));
            } else {
                $terlambat = (int) $batas->diffInDays($today);
            }

            if ($terlambat <= 0) continue;

            // Sync ke kolom di tabel peminjaman
            $pinjam->update([
                'terlambat' => $terlambat,
                'denda' => $terlambat * 2000
            ]);

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

    public function bayar($id)
    {
        try {
            $denda = Denda::findOrFail($id);
            $denda->update(['status' => 'sudah_bayar']);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran denda berhasil dikonfirmasi!',
                'status'  => $denda->status,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
