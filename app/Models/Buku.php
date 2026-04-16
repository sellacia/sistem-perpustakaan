<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';
    public $timestamps = false;

    protected $fillable = [
        'kode_buku',
        'judul',
        'pengarang',
        'penerbit',
        'tahun',
        'kategori',
        'stok',
        'status',
        'cover',
        'deskripsi',
    ];

    public function peminjaman()
    {
        return $this->hasMany(\App\Models\Peminjaman::class, 'buku_id');
    }

    public function peminjamanAktif()
    {
        return $this->hasMany(\App\Models\Peminjaman::class, 'buku_id')
            ->whereIn('status', ['menunggu', 'dipinjam', 'terlambat']);
    }

    /**
     * Sync status buku:
     * - 'tersedia'  → stok > 0 dan tidak ada peminjaman aktif (dipinjam/terlambat)
     * - 'dipinjam'  → stok <= 0 atau ada peminjaman aktif
     *
     * Catatan: status 'dikembalikan' & 'menunggu' tidak dihitung sebagai aktif
     * karena buku sudah ada di rak atau belum disetujui.
     */
    public function syncStatus(): void
    {
        $adaPeminjamanAktif = $this->peminjaman()
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->exists();

        $statusBaru = ($this->stok <= 0 || $adaPeminjamanAktif) ? 'dipinjam' : 'tersedia';

        if ($this->status !== $statusBaru) {
            $this->forceFill(['status' => $statusBaru])->save();
        }
    }
}
