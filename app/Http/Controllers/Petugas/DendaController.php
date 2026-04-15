<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Denda;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class DendaController extends Controller
{
    private function effectiveStatus(Denda $denda): string
    {
        $statusPeminjaman = $denda->peminjaman?->status_denda;

        if (in_array($statusPeminjaman, ['menunggu_konfirmasi', 'sudah_bayar'], true)) {
            return $statusPeminjaman;
        }

        return $denda->status;
    }

    public function index()
    {
        $this->generateDenda();

        $data = Denda::with(['peminjaman.buku', 'peminjaman.anggota'])
            ->orderByRaw("FIELD(status, 'menunggu_konfirmasi', 'belum_bayar', 'sudah_bayar')")
            ->orderByDesc('created_at')
            ->get();

        $data->each(function ($denda) {
            $effectiveStatus = $this->effectiveStatus($denda);

            if ($denda->status !== $effectiveStatus) {
                $denda->update(['status' => $effectiveStatus]);
                $denda->status = $effectiveStatus;
            }
        });

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
            if ($pinjam->status === 'dipinjam' || $pinjam->status === 'terlambat') {
                $pinjam->update(['status' => 'terlambat']);
            }

            $hasilDenda = $pinjam->hitungDenda($today);
            $terlambat = $hasilDenda['terlambat'];

            if ($terlambat <= 0) continue;

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

    public function bayar($id)
    {
        try {
            $denda = Denda::findOrFail($id);

            if ($denda->status === 'sudah_bayar') {
                return back()->with('success', 'Denda ini sudah lunas sebelumnya.');
            }

            $denda->update(['status' => 'sudah_bayar']);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengonfirmasi pembayaran denda.');
        }

        $denda->peminjaman?->update([
            'status_denda' => 'sudah_bayar',
            'status' => $denda->peminjaman && in_array($denda->peminjaman->status, ['dikembalikan', 'terlambat'], true)
                ? 'selesai'
                : $denda->peminjaman->status,
        ]);

        return back()->with('success', 'Pembayaran denda berhasil dikonfirmasi!');
    }

    public function cetak($id)
    {
        $denda = Denda::with(['peminjaman.buku', 'peminjaman.anggota'])->findOrFail($id);

        $pdf = Pdf::loadView('petugas.denda.struk', compact('denda'))
            ->setPaper('a5', 'portrait');

        return $pdf->stream('struk-denda-' . $denda->id . '.pdf');
    }
}
