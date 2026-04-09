<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function store(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        // CEK STOK
        if ($buku->stok <= 0) {
            return redirect()->route('anggota.buku')
                ->with('error', 'Stok buku habis!');
        }

        // SIMPAN (status masih menunggu)
        Peminjaman::create([
            'nama' => Auth::user()->nama ?? Auth::user()->name,
            'anggota_id' => Auth::id(), //  INI PENTING
            'buku_id' => $id,
            'tanggal_pinjam' => now(),
            'tanggal_wajib_kembali' => now()->addDays(3),
            'status' => 'menunggu'
        ]);

        // HAPUS pengurangan stok di sini
        // stok akan dikurangi saat petugas konfirmasi

        return redirect()->route('anggota.buku')
            ->with('success', 'Pengajuan peminjaman berhasil!');
    }
}
