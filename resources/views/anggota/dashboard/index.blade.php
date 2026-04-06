@extends('layouts.anggota.app')

@section('content')
    <h1 class="text-2xl font-bold text-blue-600 mb-4">Dashboard</h1>

    <div class="grid grid-cols-3 gap-6 mb-6">

    <!-- Buku Dipinjam -->
    <div class="flex items-center gap-4 p-5 rounded-xl shadow-sm bg-gradient-to-r from-green-100 to-green-300">
        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-green-200 text-green-700">
            <i data-lucide="book-open" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-gray-700 text-sm">Buku Sedang Dipinjam</p>
            <h2 class="text-xl font-bold">2 Buku</h2>
        </div>
    </div>

    <!-- Terlambat -->
    <div class="flex items-center gap-4 p-5 rounded-xl shadow-sm bg-gradient-to-r from-yellow-100 to-yellow-300">
        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-yellow-200 text-yellow-700">
            <i data-lucide="alert-triangle" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-gray-700 text-sm">Buku Terlambat</p>
            <h2 class="text-xl font-bold">1 Buku</h2>
        </div>
    </div>

    <!-- Denda -->
    <div class="flex items-center gap-4 p-5 rounded-xl shadow-sm bg-gradient-to-r from-red-100 to-red-300">
        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-red-200 text-red-700">
            <i data-lucide="alert-circle" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-gray-700 text-sm">Denda</p>
            <h2 class="text-xl font-bold">1 Buku</h2>
        </div>
    </div>

</div>

    <div class="bg-gray-300 border border-gray-500 rounded p-5">

        <!-- Title -->
        <div class="flex items-center gap-3 mb-3">
            <div
                class="w-7 h-7 flex items-center justify-center rounded-full border border-gray-700 text-gray-700 text-sm font-bold">
                i
            </div>
            <h3 class="font-semibold text-gray-800 text-lg">
                Informasi Aturan Peminjaman
            </h3>
        </div>

        <!-- Content -->
        <ol class="list-decimal ml-8 text-sm text-gray-900 space-y-1 leading-snug">

            <li>
                Lama peminjaman buku maksimal 7 hari sejak buku dipinjam.
            </li>

            <li>
                Buku wajib dikembalikan sebelum melewati tanggal batas pengembalian.
            </li>

            <li>
                Keterlambatan pengembalian buku akan dikenakan denda sebesar Rp2.000 per hari untuk setiap buku.
            </li>

            <li>
                Anggota wajib menjaga kondisi buku yang dipinjam dan tidak diperbolehkan merusak atau menghilangkan buku.
            </li>

            <li>
                Jika buku hilang atau rusak, anggota wajib mengganti buku sesuai ketentuan perpustakaan.
            </li>

        </ol>

    </div>
@endsection

