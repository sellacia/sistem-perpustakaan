<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PengembalianController;

// LOGIN
Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);

// REGISTER
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

// DASHBOARD
Route::get('/dashboard', function () {
    return "Berhasil login 🎉";
});
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// BUKU
Route::get('/buku', [BukuController::class, 'index'])->name('buku');
Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.detail');
Route::get('/buku/{id}/pinjam', [BukuController::class, 'formPinjam']);
Route::post('/buku/{id}/pinjam', [BukuController::class, 'prosesPinjam']);

// PENGEMBALIAN
Route::get('/pengembalian', [PengembalianController::class, 'index']);
Route::post('/pengembalian/proses', [PengembalianController::class, 'proses']);
