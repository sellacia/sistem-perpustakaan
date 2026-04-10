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

        // Kurangi stok seketika saat diajukan agar tidak dipinjam anggota lain secara bersamaan
        $buku->decrement('stok');

        return redirect()->route('anggota.buku')
            ->with('success', 'Pengajuan peminjaman berhasil!');
    }
}
