<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with('buku')
            ->where('anggota_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('anggota.peminjaman.index', compact('peminjaman'));
    }

    public function store(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);
        $anggotaId = Auth::id();

        $pinjamanAktif = Peminjaman::where('anggota_id', $anggotaId)
            ->borrowingInProgress()
            ->count();

        // CEK STOK
        if ($buku->stok <= 0) {
            return redirect()->route('anggota.buku')
                ->with('error', 'Stok buku habis!');
        }

        if ($pinjamanAktif >= 3) {
            return redirect()->route('anggota.buku')
                ->with('error', 'Maksimal peminjaman hanya 3 buku aktif dalam waktu bersamaan.');
        }

        $sudahMengajukanBukuIni = Peminjaman::where('anggota_id', $anggotaId)
            ->where('buku_id', $id)
            ->borrowingInProgress()
            ->exists();

        if ($sudahMengajukanBukuIni) {
            return redirect()->route('anggota.buku')
                ->with('error', 'Buku ini sudah ada dalam daftar pinjaman aktif Anda.');
        }

        // SIMPAN (status masih menunggu)
        Peminjaman::create([
            'nama' => Auth::user()->nama ?? Auth::user()->name,
            'anggota_id' => $anggotaId,
            'buku_id' => $id,
            'tanggal_pinjam' => now(),
            'tanggal_wajib_kembali' => now()->addDays(7),
            'status' => 'menunggu'
        ]);

        // DIHAPUS: tidak mengurangi stok di sini

        return redirect()->route('anggota.buku')
            ->with('success', 'Pengajuan peminjaman berhasil!');
    }
}
