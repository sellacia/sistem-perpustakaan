<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Denda extends Model
{
    use HasFactory;

    protected $table = 'denda';

    protected $fillable = [
        'peminjaman_id',
        'jumlah_denda',
        'terlambat',
        'status'
    ];

    // 🔥 RELASI KE PEMINJAMAN
    public function peminjaman()
    {
        return $this->belongsTo(\App\Models\Peminjaman::class);
    }
}
