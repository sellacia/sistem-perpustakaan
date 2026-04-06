<?php

namespace App\Http\Controllers\petugas;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('petugas.dashboard.index', [
            'totalBuku' => Buku::count(),
            'totalAnggota' => User::where('role', 'anggota')->count(),
            'dipinjam' => 1,
            'terlambat' => 1,
            'denda' => 200000
        ]);
    }
}
