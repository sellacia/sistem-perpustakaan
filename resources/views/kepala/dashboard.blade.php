@extends('layouts.kepala.app')

@section('content')
   <h1 class="text-2xl font-bold text-blue-600 mb-4">Dashboard</h1>

    <div class="grid md:grid-cols-4 gap-6">

        <!-- Total Buku -->
        <div class="bg-gradient-to-r from-green-200 to-green-300 p-6 rounded-2xl flex items-center gap-5 shadow-md">
            <div class="w-14 h-14 flex items-center justify-center bg-green-100 text-green-600 rounded-full text-xl">
                <i class="fas fa-book"></i>
            </div>
            <div>
                <p class="text-gray-700 text-sm">Total Buku</p>
                <h2 class="text-xl font-bold text-gray-900">
                    {{ $totalBuku }} Buku
                </h2>
            </div>
        </div>

        <!-- Anggota -->
        <div class="bg-gradient-to-r from-blue-200 to-blue-300 p-6 rounded-2xl flex items-center gap-5 shadow-md">
            <div class="w-14 h-14 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full text-xl">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <p class="text-gray-700 text-sm">Jumlah Anggota</p>
                <h2 class="text-xl font-bold text-gray-900">
                    {{ $totalAnggota }} Orang
                </h2>
            </div>
        </div>

        <!-- Dipinjam -->
        <div class="bg-gradient-to-r from-yellow-200 to-yellow-300 p-6 rounded-2xl flex items-center gap-5 shadow-md">
            <div class="w-14 h-14 flex items-center justify-center bg-yellow-100 text-yellow-600 rounded-full text-xl">
                <i class="fas fa-book-open"></i>
            </div>
            <div>
                <p class="text-gray-700 text-sm">Peminjaman Aktif</p>
                <h2 class="text-xl font-bold text-gray-900">
                    {{ $totalPinjam }} Buku
                </h2>
            </div>
        </div>

        <!-- Denda -->
        <div class="bg-gradient-to-r from-red-200 to-red-300 p-6 rounded-2xl flex items-center gap-5 shadow-md">
            <div class="w-14 h-14 flex items-center justify-center bg-red-100 text-red-600 rounded-full text-xl">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <p class="text-gray-700 text-sm">Total Denda</p>
                <h2 class="text-xl font-bold text-gray-900">
                    Rp {{ number_format($totalDenda) }}
                </h2>
            </div>
        </div>

    </div>
@endsection
