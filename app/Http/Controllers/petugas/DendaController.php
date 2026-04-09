<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Denda;

class DendaController extends Controller
{
    public function index()
    {
        $data = Denda::with('peminjaman.buku','peminjaman.anggota')
            ->latest()
            ->get();

        return view('petugas.denda.index', compact('data'));
    }

    public function bayar($id)
    {
        $denda = Denda::findOrFail($id);

        $denda->update([
            'status' => 'sudah_bayar'
        ]);

        return back()->with('success', 'Denda dibayar');
    }
}
