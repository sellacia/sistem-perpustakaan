<?php

namespace App\Http\Controllers\anggota;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('anggota.dashboard.index', [
            'sedangDipinjam' => 2,
            'bukuTerlambat' => 1,
            'totalDenda' => 1
        ]);
    }
}
