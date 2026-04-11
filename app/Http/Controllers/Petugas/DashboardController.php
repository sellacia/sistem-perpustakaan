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
        $data = Peminjaman::with(['user', 'buku', 'denda'])
            ->latest()
            ->take(5) // ambil 5 data terbaru
            ->get();

        return view('petugas.dashboard.index', [
            'totalBuku' => Buku::count(),
            'totalAnggota' => User::where('role', 'anggota')->count(),
            'dipinjam' => Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])->count(),
            'terlambat' => Peminjaman::where('status', 'terlambat')->count(),
            'denda' => Denda::where('status', 'belum_bayar')->sum('jumlah_denda'),
            'data' => $data
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
