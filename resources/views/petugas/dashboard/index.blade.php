@extends('layouts.petugas.app')

@section('content')
<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Petugas</h1>
            <p class="text-sm text-gray-500 mt-1">Selamat datang, {{ auth()->user()->name }}</p>
        </div>
        <div class="text-sm text-gray-500 bg-white border border-gray-200 rounded-lg px-4 py-2 shadow-sm">
            <i class="fas fa-calendar-alt mr-1 text-blue-500"></i>
            {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center shrink-0">
                <i class="fas fa-book text-indigo-600 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Buku</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalBuku }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                <i class="fas fa-users text-blue-600 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Anggota</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalAnggota }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-yellow-100 flex items-center justify-center shrink-0">
                <i class="fas fa-book-open text-yellow-600 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Dipinjam</p>
                <p class="text-2xl font-bold text-gray-800">{{ $dipinjam }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
                <i class="fas fa-clock text-red-600 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Terlambat</p>
                <p class="text-2xl font-bold text-gray-800">{{ $terlambat }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center shrink-0">
                <i class="fas fa-money-bill-wave text-orange-600 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Denda</p>
                <p class="text-lg font-bold text-gray-800">Rp {{ number_format($denda, 0, ',', '.') }}</p>
            </div>
        </div>

    </div>

    {{-- Peminjaman Terbaru --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-history text-blue-500"></i>
                Peminjaman Terbaru
            </h2>
            <a href="{{ route('petugas.peminjaman') }}" class="text-sm text-blue-600 hover:underline">Lihat semua →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">No</th>
                        <th class="px-5 py-3 text-left font-semibold">Nama Anggota</th>
                        <th class="px-5 py-3 text-left font-semibold">Judul Buku</th>
                        <th class="px-5 py-3 text-left font-semibold">Tgl Pinjam</th>
                        <th class="px-5 py-3 text-left font-semibold">Wajib Kembali</th>
                        <th class="px-5 py-3 text-left font-semibold">Tgl Kembali</th>
                        <th class="px-5 py-3 text-center font-semibold">Status</th>
                        <th class="px-5 py-3 text-right font-semibold">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($data as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $item->anggota->name ?? ($item->user->name ?? '-') }}</td>
                        <td class="px-5 py-3 text-gray-700 max-w-[180px] truncate">
                            {{ $item->buku->judul ?? '-' }}
                        </td>
                        <td class="px-5 py-3 text-gray-600">
                            {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}
                        </td>
                        <td class="px-5 py-3 text-gray-600">
                            {{ \Carbon\Carbon::parse($item->tanggal_wajib_kembali)->format('d M Y') }}
                        </td>
                        <td class="px-5 py-3 text-gray-600">
                            {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}
                        </td>
                        <td class="px-5 py-3 text-center">
                            @if ($item->status == 'terlambat')
                                <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 border border-red-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> Terlambat
                                </span>
                            @elseif ($item->status == 'dikembalikan' || $item->status == 'selesai')
                                <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 border border-green-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Selesai
                                </span>
                            @elseif ($item->status == 'dipinjam')
                                <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Dipinjam
                                </span>
                            @elseif ($item->status == 'menunggu')
                                <span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 border border-yellow-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Menunggu
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 border border-gray-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    {{ ucfirst($item->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right font-semibold {{ ($item->dendaData->jumlah_denda ?? 0) > 0 ? 'text-red-600' : 'text-gray-400' }}">
                            @if (($item->dendaData->jumlah_denda ?? 0) > 0)
                                Rp {{ number_format($item->dendaData->jumlah_denda, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-10 text-center text-gray-400">
                            <i class="fas fa-inbox text-3xl mb-2 block"></i>
                            Belum ada data peminjaman
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
