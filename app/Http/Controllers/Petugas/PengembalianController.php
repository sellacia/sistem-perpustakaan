<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku;

class PengembalianController extends Controller
{
    // tampilkan data pengembalian
    public function index()
    {
        $pinjam = \App\Models\Peminjaman::with('buku', 'anggota')
            ->whereIn('status', ['dikembalikan', 'terlambat'])  // Include terlambat juga
            ->latest()
            ->get();

        return view('petugas.pengembalian.index', compact('pinjam'));
    }

    public function konfirmasi($id)
    {
        $pinjam = Peminjaman::with('buku')->findOrFail($id);

        // Stok sudah ditambahkan secara otomatis pada saat anggota memulangkan buku (dikembalikan)
        // Jadi kita hanya perlu mengubah statusnya jadi selesai saja.

        $pinjam->update([
            'status' => 'selesai'
        ]);

        return back()->with('success', 'Pengembalian dikonfirmasi!');
    }
}
