<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Denda;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DebugController extends Controller
{
    public function dendaDebug()
    {
        $today = Carbon::today();

        // Cek peminjaman yang harus terlambat
        $peminjamanBelumKembali = Peminjaman::where(function($query) {
                $query->where('status', 'dipinjam')
                      ->orWhere('status', 'terlambat')
                      ->orWhere('status', 'menunggu');
            })
            ->where('tanggal_wajib_kembali', '<', $today)
            ->whereNull('tanggal_kembali')
            ->get();

        $peminjamanKembaliTelat = Peminjaman::whereColumn('tanggal_kembali', '>', 'tanggal_wajib_kembali')
            ->whereNotNull('tanggal_kembali')
            ->get();

        $peminjamanTelat = $peminjamanBelumKembali->merge($peminjamanKembaliTelat);

        echo "<h2>Debug Info - Hari ini: " . $today->format('Y-m-d') . "</h2>";

        echo "<h3>Peminjaman yang seharusnya terlambat (" . count($peminjamanTelat) . "):</h3>";
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>ID</th><th>Anggota ID</th><th>Status</th><th>Tgl Pinjam</th><th>Tgl Wajib</th><th>Tgl Kembali</th></tr>";
        foreach ($peminjamanTelat as $p) {
            echo "<tr>";
            echo "<td>{$p->id}</td>";
            echo "<td>{$p->anggota_id}</td>";
            echo "<td>{$p->status}</td>";
            echo "<td>{$p->tanggal_pinjam}</td>";
            echo "<td>{$p->tanggal_wajib_kembali}</td>";
            echo "<td>{$p->tanggal_kembali}</td>";
            echo "</tr>";
        }
        echo "</table>";

        echo "<h3>Denda di Database (" . Denda::count() . " total):</h3>";
        $denda = Denda::with(['peminjaman.anggota', 'peminjaman.buku'])->get();
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>ID</th><th>Peminjaman ID</th><th>Anggota</th><th>Buku</th><th>Terlambat</th><th>Jumlah</th><th>Status</th></tr>";
        foreach ($denda as $d) {
            $anggota = $d->peminjaman && $d->peminjaman->anggota ? $d->peminjaman->anggota->name : 'N/A';
            $buku = $d->peminjaman && $d->peminjaman->buku ? $d->peminjaman->buku->judul : 'N/A';
            echo "<tr>";
            echo "<td>{$d->id}</td>";
            echo "<td>{$d->peminjaman_id}</td>";
            echo "<td>{$anggota}</td>";
            echo "<td>{$buku}</td>";
            echo "<td>{$d->terlambat}</td>";
            echo "<td>{$d->jumlah_denda}</td>";
            echo "<td>{$d->status}</td>";
            echo "</tr>";
        }
        echo "</table>";

        echo "<h3>Progress:</h3>";
        echo "<p><a href='/petugas/denda'>← Kembali ke Kelola Denda</a></p>";
    }
}
