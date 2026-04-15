<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    // Nama tabel (karena bukan "bukus")
    protected $table = 'buku';

    // Biar bisa insert/update
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
        'deskripsi'
    ];

    // Kalau gak pakai created_at & updated_at
    public $timestamps = false;

    public function peminjaman()
    {
        return $this->hasMany(\App\Models\Peminjaman::class, 'buku_id');
    }

    public function peminjamanAktif()
    {
        return $this->hasMany(\App\Models\Peminjaman::class, 'buku_id')
            ->whereIn('status', ['menunggu', 'dipinjam', 'terlambat', 'dikembalikan']);
    }

    public function syncStatus(): void
    {
        $statusBaru = $this->peminjamanAktif()->exists() || $this->stok <= 0 ? 'dipinjam' : 'tersedia';

        if ($this->status !== $statusBaru) {
            $this->forceFill(['status' => $statusBaru])->save();
        }
    }
}
