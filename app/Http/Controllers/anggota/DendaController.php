<?php

namespace App\Http\Controllers\anggota;

use App\Http\Controllers\Controller;
use App\Models\Denda;

class DendaController extends Controller
{
    public function index()
    {
        // ambil dari tabel denda (BUKAN peminjaman lagi)
        $data = Denda::with('peminjaman.buku')->latest()->get();

        return view('anggota.denda.index', compact('data'));
    }

    public function bayar($id)
    {
        $denda = Denda::findOrFail($id);

        // 🔥 update status jadi sudah
        $denda->status = 'sudah';
        $denda->save();

        return redirect('/denda')->with('success', 'Denda berhasil dibayar!');
    }
}
