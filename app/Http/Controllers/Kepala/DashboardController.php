<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Denda;

class DashboardController extends Controller
{
    public function index()
    {
        return view('kepala.dashboard', [
            'totalBuku' => Buku::count(),

            'totalAnggota' => User::where('role', 'anggota')->count(),

            'totalPinjam' => Peminjaman::where('status', 'dipinjam')->count(),

            //sementara dulu
            'totalDenda' => 0,

            'peminjaman' => Peminjaman::with(['user', 'buku'])
                ->latest()
                ->take(5)
                ->get()
        ]);
    }
}
