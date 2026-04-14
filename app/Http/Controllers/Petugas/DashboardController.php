<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Denda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Auto-generate denda untuk yang terlambat
        $this->generateDenda();

        $data = Peminjaman::with(['user', 'buku', 'dendaData'])
            ->latest()
            ->take(5)
            ->get();

        return view('petugas.dashboard.index', [
            'totalBuku'    => Buku::count(),
            'totalAnggota' => User::where('role', 'anggota')->count(),
            'dipinjam'     => Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])->count(),
            'terlambat'    => Peminjaman::where('status', 'terlambat')->count(),
            'denda'        => Denda::where('status', 'belum_bayar')->sum('jumlah_denda'),
            'data'         => $data,
        ]);
    }

    private function generateDenda()
    {
        $today = Carbon::today();

        // Belum dikembalikan & sudah lewat deadline
        $belumKembali = Peminjaman::whereIn('status', ['dipinjam', 'terlambat', 'menunggu'])
            ->where('tanggal_wajib_kembali', '<', $today)
            ->whereNull('tanggal_kembali')
            ->get();

        // Sudah dikembalikan tapi terlambat
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
                $kembali   = Carbon::parse($pinjam->tanggal_kembali);
                $terlambat = (int) $batas->diffInDays($kembali);
            } else {
                $terlambat = (int) $batas->diffInDays($today);
            }

            if ($terlambat <= 0) continue;  // Skip jika tidak benar-benar terlambat

            // Sync ke kolom di tabel peminjaman (untuk kompatibilitas)
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
