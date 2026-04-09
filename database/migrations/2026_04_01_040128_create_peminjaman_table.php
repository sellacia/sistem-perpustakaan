<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();

            $table->string('nama');

            $table->foreignId('buku_id')
                  ->constrained('buku')
                  ->onDelete('cascade');

            // TANGGAL
            $table->date('tanggal_pinjam');
            $table->date('tanggal_wajib_kembali'); // ganti dari batas_kembali
            $table->date('tanggal_kembali')->nullable();

            // DENDA
            $table->integer('terlambat')->default(0);
            $table->integer('denda')->default(0);
            $table->enum('status_denda', ['belum_bayar', 'sudah_bayar'])
                  ->default('belum_bayar');

            // STATUS
            $table->enum('status', [
                'dipinjam',
                'dikembalikan',
                'selesai',
                'ditolak'
            ])->default('dipinjam');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
