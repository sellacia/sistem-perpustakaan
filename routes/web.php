<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

// ===== ANGGOTA =====
use App\Http\Controllers\anggota\DashboardController as DashboardAnggotaController;
use App\Http\Controllers\anggota\BukuController as BukuAnggotaController;
use App\Http\Controllers\anggota\PengembalianController;
use App\Http\Controllers\anggota\DendaController;

// ===== PETUGAS =====
use App\Http\Controllers\Petugas\DashboardController as DashboardPetugasController;
use App\Http\Controllers\Petugas\BukuController as BukuPetugasController;


// ================= LOGIN =================
Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);


// ================= REGISTER =================
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);


// ================= DASHBOARD (ANGGOTA) =================
Route::get('/dashboard', [DashboardAnggotaController::class, 'index'])->name('dashboard');


// ================= BUKU (ANGGOTA) =================
Route::get('/buku', [BukuAnggotaController::class, 'index'])->name('buku');
Route::get('/buku/{id}', [BukuAnggotaController::class, 'show'])->name('buku.detail');


// ================= PINJAM =================
Route::get('/pinjam/{id}', [BukuAnggotaController::class, 'formPinjam'])->name('buku.formPinjam');
Route::post('/pinjam/{id}', [BukuAnggotaController::class, 'prosesPinjam'])->name('buku.pinjam');


// ================= PENGEMBALIAN =================
Route::get('/pengembalian', [PengembalianController::class, 'index']);
Route::post('/pengembalian/proses', [PengembalianController::class, 'proses'])->name('pengembalian.proses');


// ================= DENDA =================
Route::get('/denda', [DendaController::class, 'index']);
Route::post('/denda/{id}/bayar', [DendaController::class, 'bayar'])->name('denda.bayar');


// ================= DASHBOARD (PETUGAS) =================
Route::get('/dashboard-petugas', [DashboardPetugasController::class, 'index']);


// ================= BUKU (PETUGAS) =================
Route::prefix('petugas')->group(function () {
    Route::resource('buku', BukuPetugasController::class);
});
Route::get('/petugas/buku/{id}', [BukuPetugasController::class, 'show']);
Route::get('/petugas/buku/{id}/edit', [BukuPetugasController::class, 'edit']);
Route::put('/petugas/buku/{id}', [BukuPetugasController::class, 'update']);
Route::delete('/petugas/buku/{id}', [BukuPetugasController::class, 'destroy']);

// ================= LOGOUT =================
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');
