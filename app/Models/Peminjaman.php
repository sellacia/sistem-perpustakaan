<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Buku;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'nama',
        'anggota_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_wajib_kembali',
        'tanggal_kembali',
        'status'
    ];

    // RELASI KE ANGGOTA
    public function anggota()
    {
        return $this->belongsTo(User::class, 'anggota_id');
    }

    // OPTIONAL (BIAR BISA PAKAI $item->user JUGA)
    public function user()
    {
        return $this->belongsTo(User::class, 'anggota_id');
    }

    // RELASI KE BUKU
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
    public function denda()
    {
        return $this->hasOne(\App\Models\Denda::class);
    }
}
