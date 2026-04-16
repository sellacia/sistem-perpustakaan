<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Denda;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class DendaController extends Controller
{
    /**
     * Tampilkan daftar semua denda.
     */
    public function index()
    {
        // Auto-generate denda untuk yang belum kembali dan terlambat
        $this->generateDendaTerlambat();

        $data = Denda::with(['peminjaman.buku', 'peminjaman.anggota'])
            ->orderByRaw("FIELD(status, 'menunggu_konfirmasi', 'belum_bayar', 'sudah_bayar')")
            ->orderByDesc('created_at')
            ->get();

        return view('petugas.denda.index', compact('data'));
    }

    /**
     * Auto-generate denda hanya untuk buku yang BELUM dikembalikan tapi sudah terlambat.
     * Buku yang sudah dikembalikan (rusak/hilang) sudah ditangani di PengembalianController.
     */
    private function generateDendaTerlambat(): void
    {
        $today = Carbon::today();

        // Ambil peminjaman yang belum dikembalikan dan sudah lewat batas
        $terlambat = Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])
            ->where('tanggal_wajib_kembali', '<', $today)
            ->whereNull('tanggal_kembali')
            ->get();

        foreach ($terlambat as $pinjam) {
            // Update status ke terlambat
            if ($pinjam->status !== 'terlambat') {
                $pinjam->update(['status' => 'terlambat']);
            }

            // Kondisi saat ini null (belum dikembalikan) → denda hanya dari keterlambatan
            $hariTerlambat = (int) Carbon::parse($pinjam->tanggal_wajib_kembali)->diffInDays($today);
            $jumlahDenda   = $hariTerlambat * 2000;

            if ($jumlahDenda <= 0) continue;

            // Jangan override status denda yang sudah dibayar/menunggu
            $statusDendaLama = $pinjam->dendaData?->status ?? 'belum_bayar';
            $statusDendaBaru = in_array($statusDendaLama, ['menunggu_konfirmasi', 'sudah_bayar'])
                ? $statusDendaLama
                : 'belum_bayar';

            $pinjam->update([
                'terlambat'    => $hariTerlambat,
                'denda'        => $jumlahDenda,
                'status_denda' => $statusDendaBaru,
            ]);

            Denda::updateOrCreate(
                ['peminjaman_id' => $pinjam->id],
                [
                    'terlambat'    => $hariTerlambat,
                    'jumlah_denda' => $jumlahDenda,
                    'status'       => $statusDendaBaru,
                ]
            );
        }
    }

    /**
     * Petugas mengkonfirmasi pembayaran denda.
     */
    public function bayar($id)
    {
        $denda = Denda::with('peminjaman')->findOrFail($id);

        if ($denda->status === 'sudah_bayar') {
            return back()->with('success', 'Denda ini sudah lunas sebelumnya.');
        }

        // Tandai denda lunas
        $denda->update(['status' => 'sudah_bayar']);

        // Update peminjaman: status_denda lunas, jika sudah dikembalikan → selesai
        if ($denda->peminjaman) {
            $statusPinjam = $denda->peminjaman->status;
            $denda->peminjaman->update([
                'status_denda' => 'sudah_bayar',
                'status'       => $statusPinjam === 'dikembalikan' ? 'selesai' : $statusPinjam,
            ]);
        }

        return back()->with('success', 'Pembayaran denda berhasil dikonfirmasi!');
    }

    /**
     * Cetak struk denda sebagai PDF.
     */
    public function cetak($id)
    {
        $denda = Denda::with(['peminjaman.buku', 'peminjaman.anggota'])->findOrFail($id);

        $pdf = Pdf::loadView('petugas.denda.struk', compact('denda'))
            ->setPaper('a5', 'portrait');

        return $pdf->stream('struk-denda-' . $denda->id . '.pdf');
    }
}
