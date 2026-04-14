@extends('layouts.petugas.app')

@section('content')
    <h1 class="text-2xl font-bold text-blue-600 mb-4">Dashboard</h1>

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
                <h2 class="text-xl font-bold text-gray-900">Rp {{ number_format($denda ?? 0, 0, ',', '.') }}</h2>
            </div>
        </div>

    </div>

    <div class="border-2 border-gray-200 rounded-2xl p-4 bg-white shadow">
        <h3 class="text-lg font-bold text-blue-600 mb-4"> Peminjaman Terbaru</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <!-- HEADER -->
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

                <!-- BODY -->
                <tbody class="text-center">
                    @forelse ($data as $item)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3">{{ $loop->iteration }}</td>
                            <td class="p-3">{{ $item->user->name ?? '-' }}</td>
                            <td class="p-3">{{ $item->buku->judul }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d-m-Y') }}</td>
                            <td class="p-3">{{ $item->buku->kode_buku ?? '-' }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($item->tanggal_wajib_kembali)->format('d-m-Y') }}</td>
                            <td class="p-3">
                                {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d-m-Y') : '-' }}
                            </td>
                            <td class="p-3">
                                @if ($item->status == 'terlambat')
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Terlambat
                                    </span>
                                @elseif ($item->status == 'dikembalikan')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Selesai
                                    </span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Dipinjam
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 font-bold text-red-600">
                                Rp {{ number_format($item->denda->jumlah_denda ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-4 text-center text-gray-500">
                                Belum ada peminjaman
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
