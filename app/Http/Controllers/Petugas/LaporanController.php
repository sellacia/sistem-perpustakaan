<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $mulai = $request->mulai;
        $sampai = $request->sampai;

        $data = Peminjaman::with(['user', 'buku', 'denda'])
            ->when($mulai && $sampai, function ($query) use ($mulai, $sampai) {
                return $query->whereBetween('tanggal_pinjam', [$mulai, $sampai]);
            })->get();

        return view('petugas.laporan.index', compact('data'));
    }
}
