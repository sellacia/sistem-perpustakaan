<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Denda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('petugas.dashboard.index', [
            'totalBuku' => Buku::count(),
            'totalAnggota' => User::where('role', 'anggota')->count(),
            'dipinjam' => Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])->count(),
            'terlambat' => Peminjaman::where('status', 'terlambat')->count(),
            'denda' => Denda::where('status', 'sudah_bayar')->sum('jumlah_denda')
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
