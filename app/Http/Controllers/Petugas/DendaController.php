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
        // Otomatis cek peminjaman yang telat di seluruh sistem
        $this->generateDenda();

        // Ambil semua denda dengan relasi yang lengkap
        $data = Denda::with(['peminjaman.buku', 'peminjaman.anggota'])
            ->orderBy('status', 'asc')  // belum_bayar duluan (a sebelum s)
            ->orderByDesc('created_at')
            ->get();

        return view('petugas.denda.index', compact('data'));
    }

    private function generateDenda()
    {
        // BELUM DIKEMBALIKAN TAPI SUDAH LEWAT DEADLINE
        $peminjamanBelumKembali = Peminjaman::where(function($query) {
                $query->where('status', 'dipinjam')
                      ->orWhere('status', 'terlambat')
                      ->orWhere('status', 'menunggu');
            })
            ->where('tanggal_wajib_kembali', '<', Carbon::today())
            ->whereNull('tanggal_kembali')
            ->get();

        // DIKEMBALIKAN TAPI TERLAMBAT (tanggal_kembali > tanggal_wajib_kembali)
        $peminjamanKembaliTelat = Peminjaman::whereColumn('tanggal_kembali', '>', 'tanggal_wajib_kembali')
            ->whereNotNull('tanggal_kembali')
            ->get();

        $peminjamanTelat = $peminjamanBelumKembali->merge($peminjamanKembaliTelat);

        foreach ($peminjamanTelat as $pinjam) {
            // Update status jika belum terlambat
            if ($pinjam->status != 'terlambat') {
                $pinjam->update(['status' => 'terlambat']);
            }

            // Hitung hari terlambat
            $batas = Carbon::parse($pinjam->tanggal_wajib_kembali);

            // Jika sudah dikembalikan, hitung sampai tanggal kembali
            if ($pinjam->tanggal_kembali) {
                $kembali = Carbon::parse($pinjam->tanggal_kembali);
                $terlambat = $batas->diffInDays($kembali);
            } else {
                // Jika belum dikembalikan, hitung sampai hari ini
                $terlambat = $batas->diffInDays(Carbon::today());
            }

            Denda::updateOrCreate(
                ['peminjaman_id' => $pinjam->id],
                [
                    'terlambat' => $terlambat,
                    'jumlah_denda' => $terlambat * 2000,
                    'status' => 'belum_bayar'
                ]
            );
        }
    }

    public function bayar($id)
    {
        try {
            $denda = Denda::findOrFail($id);

            // Update status ke sudah_bayar
            $denda->update([
                'status' => 'sudah_bayar'
            ]);

            // Return JSON response
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran denda berhasil dikonfirmasi!',
                'status' => $denda->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
