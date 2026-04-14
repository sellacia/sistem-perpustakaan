<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;

class PengembalianController extends Controller
{
    public function index()
    {
        $pinjam = Peminjaman::with(['buku', 'anggota', 'dendaData'])
            ->whereIn('status', ['dikembalikan', 'terlambat', 'selesai'])
            ->latest()
            ->get();

        return view('petugas.pengembalian.index', compact('pinjam'));
    }

    public function konfirmasi($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        $pinjam->update(['status' => 'selesai']);

        return back()->with('success', 'Pengembalian dikonfirmasi!');
    }
}
