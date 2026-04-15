<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Buku;
use Carbon\Carbon;

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
        'terlambat',
        'denda',
        'status_denda',
        'status'
    ];

    protected $appends = ['display_tanggal_kembali'];

    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_DIPINJAM = 'dipinjam';
    public const STATUS_TERLAMBAT = 'terlambat';
    public const STATUS_DIKEMBALIKAN = 'dikembalikan';
    public const STATUS_SELESAI = 'selesai';
    public const STATUS_DITOLAK = 'ditolak';

    public const ACTIVE_BORROW_STATUSES = [
        self::STATUS_MENUNGGU,
        self::STATUS_DIPINJAM,
        self::STATUS_TERLAMBAT,
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
    // PENTING: Relasi ini diberi nama dendaData (bukan denda)
    // karena tabel peminjaman punya kolom 'denda' yang akan override relasi jika namanya sama
    public function dendaData()
    {
        return $this->hasOne(\App\Models\Denda::class, 'peminjaman_id', 'id');
    }

    public function getDisplayTanggalKembaliAttribute()
    {
        if ($this->tanggal_kembali) {
            return $this->tanggal_kembali;
        }

        if (in_array($this->status, ['dikembalikan', 'selesai']) && $this->updated_at) {
            return $this->updated_at;
        }

        return null;
    }

    public function scopeBorrowingInProgress($query)
    {
        return $query->whereIn('status', self::ACTIVE_BORROW_STATUSES);
    }

    public function hitungDenda(?Carbon $tanggalAcuan = null): array
    {
        $tanggalAcuan = $tanggalAcuan ?: Carbon::today();

        if (!$this->tanggal_wajib_kembali) {
            return ['terlambat' => 0, 'jumlah_denda' => 0];
        }

        $batas = Carbon::parse($this->tanggal_wajib_kembali);
        $tanggalKembali = $this->tanggal_kembali ? Carbon::parse($this->tanggal_kembali) : $tanggalAcuan;

        if ($tanggalKembali->lte($batas)) {
            return ['terlambat' => 0, 'jumlah_denda' => 0];
        }

        $terlambat = (int) $batas->diffInDays($tanggalKembali);

        return [
            'terlambat' => $terlambat,
            'jumlah_denda' => $terlambat * 2000,
        ];
    }
}
