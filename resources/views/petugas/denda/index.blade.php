@extends('layouts.petugas.app')

@section('content')
<div class="p-6 space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Kelola Denda</h1>
        <p class="text-sm text-gray-500 mt-1">Manajemen denda keterlambatan pengembalian buku</p>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
        <i class="fas fa-check-circle text-green-500 text-base"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
        <i class="fas fa-exclamation-circle text-red-500 text-base"></i> {{ session('error') }}
    </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @php
            $totalBelumBayar = $data->where('status', 'belum_bayar')->sum('jumlah_denda');
            $totalSudahBayar = $data->where('status', 'sudah_bayar')->sum('jumlah_denda');
            $countBelum = $data->where('status', 'belum_bayar')->count();
        @endphp
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="fas fa-exclamation text-red-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Belum Dibayar</p>
                <p class="font-bold text-lg text-gray-800">{{ $countBelum }} denda</p>
                <p class="text-xs font-semibold text-red-600">Rp {{ number_format($totalBelumBayar, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="fas fa-check text-green-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Sudah Dibayar</p>
                <p class="font-bold text-lg text-gray-800">{{ $data->where('status', 'sudah_bayar')->count() }} denda</p>
                <p class="text-xs font-semibold text-green-600">Rp {{ number_format($totalSudahBayar, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="fas fa-coins text-blue-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Denda</p>
                <p class="font-bold text-lg text-gray-800">{{ $data->count() }} denda</p>
                <p class="text-xs font-semibold text-blue-600">Rp {{ number_format($totalBelumBayar + $totalSudahBayar, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Rincian Denda</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">No</th>
                        <th class="px-5 py-3 text-left font-semibold">Nama Anggota</th>
                        <th class="px-5 py-3 text-left font-semibold">Judul Buku</th>
                        <th class="px-5 py-3 text-center font-semibold">Keterlambatan</th>
                        <th class="px-5 py-3 text-right font-semibold">Jumlah Denda</th>
                        <th class="px-5 py-3 text-center font-semibold">Status</th>
                        <th class="px-5 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($data as $d)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-5 py-3 font-medium text-gray-800">
                            {{ $d->peminjaman->anggota->name ?? ($d->peminjaman->nama ?? '-') }}
                        </td>
                        <td class="px-5 py-3 text-gray-700 max-w-[180px]">
                            <div class="truncate" title="{{ $d->peminjaman->buku->judul ?? '-' }}">
                                {{ $d->peminjaman->buku->judul ?? '-' }}
                            </div>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="bg-orange-50 text-orange-700 border border-orange-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                {{ $d->terlambat }} hari
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right font-bold text-gray-800">
                            Rp {{ number_format($d->jumlah_denda, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3 text-center">
                            @if ($d->status == 'belum_bayar')
                                <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 border border-red-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> Belum Bayar
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 border border-green-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Lunas
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-center">
                            @if ($d->status == 'belum_bayar')
                                <form action="{{ route('petugas.denda.bayar', $d->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Konfirmasi pembayaran denda ini?')">
                                    @csrf
                                    <button type="submit"
                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                        <i class="fas fa-check mr-1"></i>Konfirmasi Lunas
                                    </button>
                                </form>
                            @else
                                <span class="text-green-600 text-xs font-semibold">
                                    <i class="fas fa-check-circle mr-1"></i>Terbayar
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                            <i class="fas fa-smile text-4xl mb-3 block opacity-30"></i>
                            Tidak ada data denda
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
