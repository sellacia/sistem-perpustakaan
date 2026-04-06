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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade');
            $table->date('tanggal_pinjam');
            $table->date('batas_kembali');
            $table->enum('status', ['dipinjam', 'kembali'])->default('dipinjam');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
