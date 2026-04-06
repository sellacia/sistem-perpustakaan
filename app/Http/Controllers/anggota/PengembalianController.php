<?php

namespace App\Http\Controllers\anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\anggota\Buku;
use App\Models\Denda;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    public function index()
    {
        // ambil SEMUA data peminjaman yang masih dipinjam
        $pinjam = Peminjaman::where('status', 'dipinjam')->first();

        if (!$pinjam) {
            return view('anggota.pengembalian.index', [
                'pinjam' => null,
                'buku' => null,
                'tanggal_pinjam' => null,
                'batas_kembali' => null,
                'tanggal_kembali' => null,
                'terlambat' => 0,
                'denda' => 0
            ]);
        }

        $buku = Buku::find($pinjam->buku_id);

        $tanggal_pinjam = Carbon::parse($pinjam->tanggal_pinjam);
        $batas_kembali = Carbon::parse($pinjam->batas_kembali);
        $tanggal_kembali = Carbon::now();

        $terlambat = 0;
        $denda = 0;

        if ($tanggal_kembali > $batas_kembali) {
            $terlambat = $tanggal_kembali->diffInDays($batas_kembali);
            $denda = $terlambat * 2000;
        }

        return view('anggota.pengembalian.index', compact(
            'pinjam',
            'buku',
            'tanggal_pinjam',
            'batas_kembali',
            'tanggal_kembali',
            'terlambat',
            'denda'
        ));
    }

    public function proses(Request $request)
    {
        // 🔥 VALIDASI
        if (!$request->pinjam_id) {
            return back()->with('error', 'Data peminjaman tidak ditemukan!');
        }

        $pinjam = Peminjaman::findOrFail($request->pinjam_id);

        $today = Carbon::now();
        $batas = Carbon::parse($pinjam->batas_kembali);

        $terlambat = 0;
        $denda = 0;

        if ($today > $batas) {
            $terlambat = $today->diffInDays($batas);
            $denda = $terlambat * 2000;

            // 🔥 CEK BIAR GAK DOUBLE DENDA
            $cek = Denda::where('peminjaman_id', $pinjam->id)->first();

            if (!$cek) {
                Denda::create([
                    'peminjaman_id' => $pinjam->id,
                    'jumlah_denda' => $denda,
                    'terlambat' => $terlambat,
                    'status' => 'belum'
                ]);
            }
        }

        // update peminjaman
        $pinjam->status = 'kembali';
        $pinjam->save();

        // update buku
        $buku = Buku::find($pinjam->buku_id);
        if ($buku) {
            $buku->stok += 1;
            $buku->status = 'tersedia';
            $buku->save();
        }

        return redirect('/pengembalian')->with('success', 'Pengembalian berhasil!');
    }
}
