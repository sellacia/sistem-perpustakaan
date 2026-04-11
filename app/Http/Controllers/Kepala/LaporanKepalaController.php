<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanKepalaController extends Controller
{
     public function index()
    {
        $laporan = Peminjaman::with(['user','buku'])
                    ->latest()
                    ->get();

        return view('kepala.laporan', compact('laporan'));
    }

    public function exportPdf()
    {
        $laporan = Peminjaman::with(['user','buku'])
                    ->latest()
                    ->get();

        $pdf = Pdf::loadView('kepala.laporan-pdf', compact('laporan'));

        return $pdf->download('laporan-peminjaman-' . date('d-m-Y') . '.pdf');
    }
}
