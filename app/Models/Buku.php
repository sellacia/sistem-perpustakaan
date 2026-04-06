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
        'cover'
    ];

    // Kalau gak pakai created_at & updated_at
    public $timestamps = false;
}
