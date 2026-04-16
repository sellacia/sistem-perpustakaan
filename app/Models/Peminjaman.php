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
        'status',
        'kondisi',
        'alasan_tolak',
    ];

    protected $appends = ['display_tanggal_kembali'];

    // ─── Konstanta Status ──────────────────────────────────────────────────────

    public const STATUS_MENUNGGU      = 'menunggu';
    public const STATUS_DIPINJAM      = 'dipinjam';
    public const STATUS_TERLAMBAT     = 'terlambat';
    public const STATUS_DIKEMBALIKAN  = 'dikembalikan';
    public const STATUS_SELESAI       = 'selesai';
    public const STATUS_DITOLAK       = 'ditolak';

    /** Status yang dianggap "sedang aktif" (buku belum ada di rak) */
    public const ACTIVE_BORROW_STATUSES = [
        self::STATUS_MENUNGGU,
        self::STATUS_DIPINJAM,
        self::STATUS_TERLAMBAT,
    ];

    // ─── Relasi ───────────────────────────────────────────────────────────────

    public function anggota()
    {
        return $this->belongsTo(User::class, 'anggota_id');
    }

    /** Alias agar bisa akses lewat $item->user */
    public function user()
    {
        return $this->belongsTo(User::class, 'anggota_id');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    /**
     * Relasi ke denda. Nama "dendaData" bukan "denda" untuk menghindari
     * konflik dengan kolom 'denda' di tabel peminjaman.
     */
    public function dendaData()
    {
        return $this->hasOne(\App\Models\Denda::class, 'peminjaman_id', 'id');
    }

    // ─── Accessor ─────────────────────────────────────────────────────────────

    /**
     * Tanggal kembali yang ditampilkan:
     * - Jika tanggal_kembali ada → pakai itu
     * - Jika status selesai/dikembalikan tapi tanggal_kembali null → pakai updated_at
     */
    public function getDisplayTanggalKembaliAttribute()
    {
        if ($this->tanggal_kembali) {
            return $this->tanggal_kembali;
        }

        if (in_array($this->status, ['dikembalikan', 'selesai']) && $this->updated_at) {
            return $this->updated_at->toDateString();
        }

        return null;
    }

    // ─── Scope ────────────────────────────────────────────────────────────────

    public function scopeBorrowingInProgress($query)
    {
        return $query->whereIn('status', self::ACTIVE_BORROW_STATUSES);
    }

    // ─── Business Logic ───────────────────────────────────────────────────────

    /**
     * Hitung denda berdasarkan:
     * 1. Denda keterlambatan: Rp 2.000 / hari
     * 2. Denda kondisi buku:
     *    - Rusak  → +Rp 50.000
     *    - Hilang → +Rp 100.000
     *
     * @param  Carbon|null  $tanggalAcuan  Tanggal pengembalian (default: hari ini)
     * @return array{ terlambat: int, denda_terlambat: int, denda_kondisi: int, jumlah_denda: int }
     */
    public function hitungDenda(?Carbon $tanggalAcuan = null): array
    {
        $tanggalAcuan = $tanggalAcuan ? Carbon::parse($tanggalAcuan) : Carbon::today();

        // Denda kondisi buku
        $dendaKondisi = match ($this->kondisi) {
            'rusak'  => 50000,
            'hilang' => 100000,
            default  => 0,
        };

        // Jika tidak ada batas waktu → hanya denda kondisi
        if (!$this->tanggal_wajib_kembali) {
            return [
                'terlambat'       => 0,
                'denda_terlambat' => 0,
                'denda_kondisi'   => $dendaKondisi,
                'jumlah_denda'    => $dendaKondisi,
            ];
        }

        $batas          = Carbon::parse($this->tanggal_wajib_kembali)->startOfDay();
        $tanggalKembali = $this->tanggal_kembali
            ? Carbon::parse($this->tanggal_kembali)->startOfDay()
            : $tanggalAcuan->startOfDay();

        // Tidak terlambat
        if ($tanggalKembali->lte($batas)) {
            return [
                'terlambat'       => 0,
                'denda_terlambat' => 0,
                'denda_kondisi'   => $dendaKondisi,
                'jumlah_denda'    => $dendaKondisi,
            ];
        }

        $hariTerlambat  = (int) $batas->diffInDays($tanggalKembali);
        $dendaTerlambat = $hariTerlambat * 2000;

        return [
            'terlambat'       => $hariTerlambat,
            'denda_terlambat' => $dendaTerlambat,
            'denda_kondisi'   => $dendaKondisi,
            'jumlah_denda'    => $dendaTerlambat + $dendaKondisi,
        ];
    }
}
