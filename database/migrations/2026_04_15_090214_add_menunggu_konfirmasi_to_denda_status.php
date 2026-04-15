<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE denda MODIFY COLUMN status ENUM('belum_bayar', 'menunggu_konfirmasi', 'sudah_bayar') DEFAULT 'belum_bayar'");
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status_denda ENUM('belum_bayar', 'menunggu_konfirmasi', 'sudah_bayar') DEFAULT 'belum_bayar'");
    }

    public function down(): void
    {
        DB::statement("UPDATE denda SET status = 'belum_bayar' WHERE status = 'menunggu_konfirmasi'");
        DB::statement("UPDATE peminjaman SET status_denda = 'belum_bayar' WHERE status_denda = 'menunggu_konfirmasi'");
        DB::statement("ALTER TABLE denda MODIFY COLUMN status ENUM('belum_bayar', 'sudah_bayar') DEFAULT 'belum_bayar'");
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status_denda ENUM('belum_bayar', 'sudah_bayar') DEFAULT 'belum_bayar'");
    }
};
