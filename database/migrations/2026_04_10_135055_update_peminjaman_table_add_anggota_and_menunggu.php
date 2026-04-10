<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->unsignedBigInteger('anggota_id')->nullable()->after('nama');
        });

        // Update Enum to include 'menunggu'
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM('menunggu', 'dipinjam', 'dikembalikan', 'selesai', 'ditolak') DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert enum
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM('dipinjam', 'dikembalikan', 'selesai', 'ditolak') DEFAULT 'dipinjam'");

        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn('anggota_id');
        });
    }
};
