<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Denda;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Generate denda otomatis sebelum menampilkan laporan
        $this->generateDenda();

        $mulai = $request->mulai;
        $sampai = $request->sampai;

        // Query dengan refresh relasi denda - ambil SEMUA peminjaman, tidak cuma yang dipinjam
        $data = Peminjaman::with(['anggota', 'buku', 'denda' => function($query) {
                $query->select('id', 'peminjaman_id', 'terlambat', 'jumlah_denda', 'status');
            }])
            ->when($mulai && $sampai, function ($query) use ($mulai, $sampai) {
                return $query->whereBetween('tanggal_pinjam', [$mulai, $sampai]);
            })
            // Ambil yang belum selesai atau yang sudah dikembalikan/selesai
            ->whereIn('status', ['dipinjam', 'dikembalikan', 'selesai', 'terlambat', 'menunggu'])
            ->orderByDesc('tanggal_pinjam')
            ->get();

        return view('petugas.laporan.index', compact('data'));
    }

    private function generateDenda()
    {
        // BELUM DIKEMBALIKAN TAPI SUDAH LEWAT DEADLINE
        $peminjamanBelumKembali = Peminjaman::where(function($query) {
                $query->where('status', 'dipinjam')
                      ->orWhere('status', 'terlambat')
                      ->orWhere('status', 'menunggu');  // tambah untuk coverage lebih baik
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
}

