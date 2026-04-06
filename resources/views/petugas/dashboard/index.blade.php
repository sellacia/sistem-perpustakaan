@extends('layouts.petugas.app')

@section('content')
   <div class="grid grid-cols-5 gap-6 mb-8">

    <!-- Total Buku -->
    <div class="bg-gradient-to-r from-green-200 to-green-300 p-6 rounded-2xl flex items-center gap-5 shadow-md">
        <div class="w-14 h-14 flex items-center justify-center bg-green-100 text-green-600 rounded-full text-xl">
            <i class="fas fa-book"></i>
        </div>
        <div>
            <p class="text-gray-700 text-sm">Total Buku</p>
            <h2 class="text-xl font-bold text-gray-900">{{ $totalBuku ?? 2 }} Buku</h2>
        </div>
    </div>

    <!-- Total Anggota -->
    <div class="bg-gradient-to-r from-blue-200 to-blue-300 p-6 rounded-2xl flex items-center gap-5 shadow-md">
        <div class="w-14 h-14 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full text-xl">
            <i class="fas fa-user"></i>
        </div>
        <div>
            <p class="text-gray-700 text-sm">Total Anggota</p>
            <h2 class="text-xl font-bold text-gray-900">{{ $totalAnggota ?? 2 }} Orang</h2>
        </div>
    </div>

    <!-- Dipinjam -->
    <div class="bg-gradient-to-r from-yellow-200 to-yellow-300 p-6 rounded-2xl flex items-center gap-5 shadow-md">
        <div class="w-14 h-14 flex items-center justify-center bg-yellow-100 text-yellow-600 rounded-full text-xl">
            <i class="fas fa-book-open"></i>
        </div>
        <div>
            <p class="text-gray-700 text-sm">Buku Dipinjam</p>
            <h2 class="text-xl font-bold text-gray-900">{{ $dipinjam ?? 1 }} Buku</h2>
        </div>
    </div>

    <!-- Terlambat -->
    <div class="bg-gradient-to-r from-purple-200 to-purple-300 p-6 rounded-2xl flex items-center gap-5 shadow-md">
        <div class="w-14 h-14 flex items-center justify-center bg-purple-100 text-purple-600 rounded-full text-xl">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <p class="text-gray-700 text-sm">Buku Terlambat</p>
            <h2 class="text-xl font-bold text-gray-900">{{ $terlambat ?? 1 }} Buku</h2>
        </div>
    </div>

    <!-- Denda -->
    <div class="bg-gradient-to-r from-red-200 to-red-300 p-6 rounded-2xl flex items-center gap-5 shadow-md">
        <div class="w-14 h-14 flex items-center justify-center bg-red-100 text-red-600 rounded-full text-xl">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div>
            <p class="text-gray-700 text-sm">Total Denda</p>
            <h2 class="text-xl font-bold text-gray-900">Rp {{ $denda ?? '200.000' }}</h2>
        </div>
    </div>

</div>

    <div class="border-2 border-white-500 rounded-2xl p-4 bg-white shadow">

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-3">No</th>
                        <th class="p-3">Nama</th>
                        <th class="p-3">Judul Buku</th>
                        <th class="p-3">Tanggal Pinjam</th>
                        <th class="p-3">Kode Buku</th>
                        <th class="p-3">Tanggal Wajib Kembali</th>
                        <th class="p-3">Tanggal Kembali</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Denda</th>
                    </tr>
                </thead>

                <tbody class="text-center">

                    <tr class="border-t">
                        <td class="p-3">1</td>
                        <td>Upi</td>
                        <td>Cantik Itu Luka</td>
                        <td>17-02-2026</td>
                        <td>IUA-245</td>
                        <td>20-02-2026</td>
                        <td>22-02-2026</td>
                        <td>
                            <span class="bg-yellow-400 text-white px-3 py-1 rounded-full text-xs">
                                Terlambat
                            </span>
                        </td>
                        <td>Rp 4.000</td>
                    </tr>

                    <tr class="border-t">
                        <td class="p-3">2</td>
                        <td>Intan</td>
                        <td>Cantik Itu Luka</td>
                        <td>17-02-2026</td>
                        <td>IUA-245</td>
                        <td>20-02-2026</td>
                        <td>-</td>
                        <td>
                            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs">
                                Dipinjam
                            </span>
                        </td>
                        <td>-</td>
                    </tr>

                </tbody>

            </table>
        </div>

    </div>
@endsection
