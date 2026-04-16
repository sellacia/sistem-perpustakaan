<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DebugController;

// ===== ANGGOTA =====
use App\Http\Controllers\Anggota\DashboardController as DashboardAnggotaController;
use App\Http\Controllers\Anggota\BukuController as BukuAnggotaController;
use App\Http\Controllers\Anggota\PeminjamanController as AnggotaPeminjaman;
use App\Http\Controllers\Anggota\PengembalianController as AnggotaPengembalianController;
use App\Http\Controllers\Anggota\RiwayatController as AnggotaRiwayatController;

// ===== PETUGAS =====
use App\Http\Controllers\Petugas\DashboardController as DashboardPetugasController;
use App\Http\Controllers\Petugas\BukuController as BukuPetugasController;
use App\Http\Controllers\Petugas\PeminjamanController as PetugasPeminjaman;
use App\Http\Controllers\Petugas\PengembalianController as PetugasPengembalianController;
use App\Http\Controllers\Petugas\AnggotaController;
use App\Http\Controllers\Petugas\DendaController as PetugasDendaController;
use App\Http\Controllers\Petugas\LaporanController;
use App\Http\Controllers\Petugas\PengembalianController;


// ===== KEPALA =====
use App\Http\Controllers\Kepala\DashboardController;
use App\Http\Controllers\Kepala\BukuController;
use App\Http\Controllers\Kepala\LaporanKepalaController;
use App\Http\Controllers\Kepala\PetugasController;

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
    Route::prefix('anggota')->middleware('role:anggota')->group(function () {

        Route::get('/dashboard', [DashboardAnggotaController::class, 'index'])->name('anggota.dashboard');
        Route::post('/logout', [DashboardAnggotaController::class, 'logout'])->name('anggota.logout');

        Route::get('/buku', [BukuAnggotaController::class, 'index'])->name('anggota.buku');
        Route::get('/buku/{id}', [BukuAnggotaController::class, 'show'])->name('anggota.buku.detail');

        Route::get('/peminjaman', [AnggotaPeminjaman::class, 'index'])->name('anggota.peminjaman');

        Route::get('/pinjam/{id}', [BukuAnggotaController::class, 'formPinjam'])->name('anggota.pinjam.form');
        Route::post('/pinjam/{id}', [AnggotaPeminjaman::class, 'store'])->name('anggota.pinjam');

        Route::get('/pengembalian', [AnggotaPengembalianController::class, 'index'])->name('anggota.pengembalian');
        Route::post('/pengembalian/proses', [AnggotaPengembalianController::class, 'proses'])->name('anggota.pengembalian.proses');

        //  RIWAYAT ANGGOTA
        Route::get('/riwayat', [AnggotaRiwayatController::class, 'index'])->name('anggota.riwayat');
        Route::post('/riwayat/{id}/bayar', [AnggotaRiwayatController::class, 'bayar'])->name('anggota.riwayat.bayar');
    });


    // ================= PETUGAS =================
    Route::prefix('petugas')->middleware('role:petugas')->group(function () {

        Route::get('/dashboard', [DashboardPetugasController::class, 'index'])->name('petugas.dashboard');
        Route::post('/logout', [DashboardPetugasController::class, 'logout'])->name('petugas.logout');

        Route::resource('buku', BukuPetugasController::class)->names([
            'index' => 'petugas.buku.index',
            'create' => 'petugas.buku.create',
            'store' => 'petugas.buku.store',
            'show' => 'petugas.buku.show',
            'edit' => 'petugas.buku.edit',
            'update' => 'petugas.buku.update',
            'destroy' => 'petugas.buku.destroy',
        ]);

        // PEMINJAMAN
        Route::get('/peminjaman', [PetugasPeminjaman::class, 'index'])->name('petugas.peminjaman');
        Route::get('/peminjaman/setujui/{id}', [PetugasPeminjaman::class, 'setujui'])->name('petugas.peminjaman.setujui');
        Route::get('/peminjaman/tolak/{id}', [PetugasPeminjaman::class, 'tolak'])->name('petugas.peminjaman.tolak');

        // PENGEMBALIAN
        Route::get('/pengembalian', [PetugasPengembalianController::class, 'index'])->name('petugas.pengembalian');
        Route::post('/pengembalian/{id}/kembalikan', [PetugasPengembalianController::class, 'kembalikan'])->name('petugas.pengembalian.kembalikan');
        Route::post('/pengembalian/{id}/konfirmasi', [PetugasPengembalianController::class, 'konfirmasi'])->name('petugas.pengembalian.konfirmasi');

        //  DENDA PETUGAS
        Route::get('/denda', [PetugasDendaController::class, 'index'])->name('petugas.denda');
        Route::post('/denda/bayar/{id}', [PetugasDendaController::class, 'bayar'])->name('petugas.denda.bayar');
        Route::get('/denda/{id}/cetak', [PetugasDendaController::class, 'cetak'])->name('petugas.denda.cetak');

        //  LAPORAN PETUGAS
        Route::get('/laporan', [LaporanController::class, 'index'])
            ->name('petugas.laporan');
        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])
            ->name('petugas.laporan.export-pdf');

        // ANGGOTA PETUGAS
        Route::resource('anggota', AnggotaController::class)->names([
            'index' => 'petugas.anggota.index',
            'create' => 'petugas.anggota.create',
            'store' => 'petugas.anggota.store',
            'show' => 'petugas.anggota.show',
            'edit' => 'petugas.anggota.edit',
            'update' => 'petugas.anggota.update',
            'destroy' => 'petugas.anggota.destroy',
        ]);
    });


    // ================= KEPALA =================
    Route::prefix('kepala')->middleware('role:kepala')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('kepala.dashboard');

        Route::get('/buku', [BukuController::class, 'index'])->name('kepala.buku.index');
        Route::get('/buku/{id}', [BukuController::class, 'show'])->name('kepala.buku.show');

        // LAPORAN
        Route::get('/laporan', [LaporanKepalaController::class, 'index'])->name('kepala.laporan');
        Route::get('/laporan/export-pdf', [LaporanKepalaController::class, 'exportPdf'])->name('kepala.laporan.export-pdf');

        Route::get('/petugas', [PetugasController::class, 'index'])->name('kepala.petugas.index');

        Route::get('/petugas/create', [PetugasController::class, 'create'])->name('kepala.petugas.create');

        Route::post('/petugas', [PetugasController::class, 'store'])->name('kepala.petugas.store');

        Route::get('/petugas/{id}/edit', [PetugasController::class, 'edit'])->name('kepala.petugas.edit');

        Route::put('/petugas/{id}', [PetugasController::class, 'update'])->name('kepala.petugas.update');

        Route::delete('/petugas/{id}', [PetugasController::class, 'destroy'])->name('kepala.petugas.destroy');
    });




    // ================= LOGOUT =================
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/login');
    })->name('logout');

    // ================= DEBUG =================
    Route::get('/debug/denda', [DebugController::class, 'dendaDebug'])->middleware('role:petugas,kepala');
});
