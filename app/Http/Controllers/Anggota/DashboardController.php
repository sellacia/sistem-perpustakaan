<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;
use App\Models\Denda;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        $sedangDipinjam = Peminjaman::where('anggota_id', $userId)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->count();

        $bukuTerlambat = Peminjaman::where('anggota_id', $userId)
            ->where('status', 'terlambat')
            ->count();

        $totalDenda = Denda::whereHas('peminjaman', function($q) use ($userId) {
                $q->where('anggota_id', $userId);
            })
            ->where('status', 'belum_bayar')
            ->sum('jumlah_denda');

        return view('anggota.dashboard.index', compact('sedangDipinjam', 'bukuTerlambat', 'totalDenda'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
