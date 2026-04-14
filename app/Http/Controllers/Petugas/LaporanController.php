<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Denda;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $this->generateDenda();

        $mulai  = $request->mulai;
        $sampai = $request->sampai;

        $data = Peminjaman::with(['anggota', 'buku', 'dendaData'])
            ->when($mulai && $sampai, function ($q) use ($mulai, $sampai) {
                $q->whereBetween('tanggal_pinjam', [$mulai, $sampai]);
            })
            ->whereIn('status', ['dipinjam', 'dikembalikan', 'selesai', 'terlambat', 'menunggu'])
            ->orderByDesc('tanggal_pinjam')
            ->get();

        return view('petugas.laporan.index', compact('data'));
    }

    public function exportPdf(Request $request)
    {
        $this->generateDenda();

        $mulai  = $request->mulai;
        $sampai = $request->sampai;

        $data = Peminjaman::with(['anggota', 'buku', 'dendaData'])
            ->when($mulai && $sampai, function ($q) use ($mulai, $sampai) {
                $q->whereBetween('tanggal_pinjam', [$mulai, $sampai]);
            })
            ->whereIn('status', ['dipinjam', 'dikembalikan', 'selesai', 'terlambat', 'menunggu'])
            ->orderByDesc('tanggal_pinjam')
            ->get();

        $pdf = Pdf::loadView('petugas.laporan.cetak', compact('data'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-peminjaman-' . now()->format('d-m-Y') . '.pdf');
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
}
