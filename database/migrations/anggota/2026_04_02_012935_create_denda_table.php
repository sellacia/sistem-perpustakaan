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
        Schema::create('denda', function (Blueprint $table) {
    $table->id();
    $table->foreignId('peminjaman_id')->constrained()->onDelete('cascade');
    $table->integer('jumlah_denda');
    $table->integer('terlambat');
    $table->enum('status', ['belum', 'sudah'])->default('belum');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('denda');
    }
};
