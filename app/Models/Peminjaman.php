<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'nama',
        'buku_id',
        'tanggal_pinjam',
        'batas_kembali',
        'status'
    ];

    // 🔥 RELASI KE BUKU
    public function buku()
    {
        return $this->belongsTo(\App\Models\Buku::class, 'buku_id');
    }
}
