<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

// ===== ANGGOTA =====
use App\Http\Controllers\Anggota\DashboardController as DashboardAnggotaController;
use App\Http\Controllers\Anggota\BukuController as BukuAnggotaController;
use App\Http\Controllers\Anggota\PeminjamanController as AnggotaPeminjaman;
use App\Http\Controllers\Anggota\PengembalianController as AnggotaPengembalianController;
use App\Http\Controllers\Anggota\DendaController as AnggotaDendaController;

// ===== PETUGAS =====
use App\Http\Controllers\Petugas\DashboardController as DashboardPetugasController;
use App\Http\Controllers\Petugas\BukuController as BukuPetugasController;
use App\Http\Controllers\Petugas\PeminjamanController as PetugasPeminjaman;
use App\Http\Controllers\Petugas\PengembalianController as PetugasPengembalianController;
use App\Http\Controllers\Petugas\AnggotaController;
use App\Http\Controllers\Petugas\DendaController as PetugasDendaController;

// ===== KEPALA =====
use App\Http\Controllers\Kepala\DashboardController;


// ================= LOGIN =================
Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);


// ================= REGISTER =================
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);


// ================= ROUTE LOGIN =================
Route::middleware('auth')->group(function () {

    // ================= ANGGOTA =================
    Route::prefix('anggota')->group(function () {

        Route::get('/dashboard', [DashboardAnggotaController::class, 'index'])->name('anggota.dashboard');

        Route::get('/buku', [BukuAnggotaController::class, 'index'])->name('anggota.buku');
        Route::get('/buku/{id}', [BukuAnggotaController::class, 'show'])->name('anggota.buku.detail');

        Route::get('/pinjam/{id}', [BukuAnggotaController::class, 'formPinjam'])->name('anggota.pinjam.form');
        Route::post('/pinjam/{id}', [AnggotaPeminjaman::class, 'store'])->name('anggota.pinjam');

        Route::get('/pengembalian', [AnggotaPengembalianController::class, 'index'])->name('anggota.pengembalian');
        Route::post('/pengembalian/proses', [AnggotaPengembalianController::class, 'proses'])->name('anggota.pengembalian.proses');

        //  DENDA ANGGOTA
        Route::get('/denda', [AnggotaDendaController::class, 'index'])->name('anggota.denda');
        Route::post('/denda/{id}/bayar', [AnggotaDendaController::class, 'bayar'])->name('anggota.denda.bayar');
    });


    // ================= PETUGAS =================
    Route::prefix('petugas')->group(function () {

        Route::get('/dashboard', [DashboardPetugasController::class, 'index'])->name('petugas.dashboard');

        Route::resource('/buku', BukuPetugasController::class);

        // PEMINJAMAN
        Route::get('/peminjaman', [PetugasPeminjaman::class, 'index'])->name('petugas.peminjaman');
        Route::get('/peminjaman/setujui/{id}', [PetugasPeminjaman::class, 'setujui'])->name('petugas.peminjaman.setujui');
        Route::get('/peminjaman/tolak/{id}', [PetugasPeminjaman::class, 'tolak'])->name('petugas.peminjaman.tolak');
        Route::get('/peminjaman/kembalikan/{id}', [PetugasPeminjaman::class, 'kembalikan'])->name('petugas.peminjaman.kembalikan');

        // PENGEMBALIAN
        Route::get('/pengembalian', [PetugasPengembalianController::class, 'index'])->name('petugas.pengembalian');
        Route::post('/pengembalian/{id}/konfirmasi', [PetugasPengembalianController::class, 'konfirmasi'])->name('petugas.pengembalian.konfirmasi');
        Route::get('/petugas/peminjaman/setujui/{id}', [\App\Http\Controllers\Petugas\PeminjamanController::class, 'setujui']);
        Route::get('/petugas/peminjaman/tolak/{id}', [\App\Http\Controllers\Petugas\PeminjamanController::class, 'tolak']);

        //  DENDA PETUGAS
        Route::get('/denda', [PetugasDendaController::class, 'index'])->name('petugas.denda');
        Route::get('/denda/bayar/{id}', [PetugasDendaController::class, 'bayar'])->name('petugas.denda.bayar');

        //  ANGGOTA (FIX ERROR KAMU)
        Route::get('/anggota', [AnggotaController::class, 'index'])->name('petugas.anggota');

        Route::get('/anggota/create', [AnggotaController::class, 'create'])->name('petugas.anggota.create');

        Route::post('/anggota/store', [AnggotaController::class, 'store'])->name('petugas.anggota.store');

        Route::get('/anggota/edit/{id}', [AnggotaController::class, 'edit'])->name('petugas.anggota.edit');

        Route::put('/anggota/update/{id}', [AnggotaController::class, 'update'])->name('petugas.anggota.update');

        Route::delete('/anggota/delete/{id}', [AnggotaController::class, 'destroy'])->name('petugas.anggota.delete');
    });


    // ================= KEPALA =================
    Route::prefix('kepala')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('kepala.dashboard');
    });


    // ================= LOGOUT =================
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/login');
    })->name('logout');
});
