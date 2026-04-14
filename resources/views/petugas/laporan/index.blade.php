@extends('layouts.petugas.app')

@section('content')
<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Laporan</h1>
            <p class="text-sm text-gray-500 mt-1">Laporan data peminjaman buku perpustakaan</p>
        </div>
        <a href="{{ route('petugas.laporan.export-pdf') }}?mulai={{ request('mulai') }}&sampai={{ request('sampai') }}"
            target="_blank"
            class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl font-medium text-sm transition shadow-sm">
            <i class="fas fa-file-pdf"></i> Cetak Laporan PDF
        </a>
    </div>

    {{-- Filter Tanggal --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <h2 class="text-sm font-semibold text-gray-700 mb-3">
            <i class="fas fa-filter mr-2 text-blue-500"></i>Filter Periode
        </h2>
        <form method="GET" action="{{ route('petugas.laporan') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs text-gray-500 mb-1.5 font-medium">Tanggal Mulai</label>
                <input type="date" name="mulai" value="{{ request('mulai') }}"
                    class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1.5 font-medium">Tanggal Selesai</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}"
                    class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition">
                    <i class="fas fa-search mr-1"></i>Tampilkan
                </button>
                @if(request('mulai') || request('sampai'))
                <a href="{{ route('petugas.laporan') }}"
                    class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Info periode jika filter aktif --}}
    @if(request('mulai') && request('sampai'))
    <div class="flex items-center gap-2 text-sm bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl">
        <i class="fas fa-calendar-check text-blue-500"></i>
        Menampilkan data dari <strong>{{ \Carbon\Carbon::parse(request('mulai'))->isoFormat('D MMMM Y') }}</strong>
        sampai <strong>{{ \Carbon\Carbon::parse(request('sampai'))->isoFormat('D MMMM Y') }}</strong>
        – <strong>{{ $data->count() }}</strong> data ditemukan
    </div>
    @endif

    {{-- Ringkasan --}}
    @php
        $totalDenda = $data->sum(fn($d) => $d->dendaData->jumlah_denda ?? 0);
        $selesai = $data->whereIn('status', ['selesai', 'dikembalikan'])->count();
        $terlambat = $data->where('status', 'terlambat')->count();
        $aktif = $data->whereIn('status', ['dipinjam', 'menunggu'])->count();
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-gray-800">{{ $data->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Transaksi</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-green-600">{{ $selesai }}</p>
            <p class="text-xs text-gray-500 mt-1">Selesai</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-red-600">{{ $terlambat }}</p>
            <p class="text-xs text-gray-500 mt-1">Terlambat</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-xl font-bold text-orange-600">Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Denda</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Detail Laporan</h2>
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
                    @forelse ($data as $d)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-5 py-3 font-medium text-gray-800">
                            {{ $d->anggota->name ?? '-' }}
                        </td>
                        <td class="px-5 py-3 text-gray-700 max-w-[180px]">
                            <div class="truncate" title="{{ $d->buku->judul ?? '-' }}">
                                {{ $d->buku->judul ?? '-' }}
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-600">
                            {{ \Carbon\Carbon::parse($d->tanggal_pinjam)->format('d M Y') }}
                        </td>
                        <td class="px-5 py-3 text-gray-600">
                            {{ \Carbon\Carbon::parse($d->tanggal_wajib_kembali)->format('d M Y') }}
                        </td>
                        <td class="px-5 py-3 text-gray-600">
                            {{ $d->tanggal_kembali ? \Carbon\Carbon::parse($d->tanggal_kembali)->format('d M Y') : '-' }}
                        </td>
                        <td class="px-5 py-3 text-center">
                            @if ($d->status == 'selesai')
                                <span class="bg-green-50 text-green-700 border border-green-200 px-2.5 py-1 rounded-full text-xs font-semibold">Selesai</span>
                            @elseif ($d->status == 'dikembalikan')
                                <span class="bg-indigo-50 text-indigo-700 border border-indigo-200 px-2.5 py-1 rounded-full text-xs font-semibold">Dikembalikan</span>
                            @elseif ($d->status == 'terlambat')
                                <span class="bg-red-50 text-red-700 border border-red-200 px-2.5 py-1 rounded-full text-xs font-semibold">Terlambat</span>
                            @elseif ($d->status == 'dipinjam')
                                <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-1 rounded-full text-xs font-semibold">Dipinjam</span>
                            @elseif ($d->status == 'menunggu')
                                <span class="bg-yellow-50 text-yellow-700 border border-yellow-200 px-2.5 py-1 rounded-full text-xs font-semibold">Menunggu</span>
                            @else
                                <span class="bg-gray-100 text-gray-600 border border-gray-200 px-2.5 py-1 rounded-full text-xs font-semibold">{{ ucfirst($d->status) }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            @if ($d->dendaData && $d->dendaData->jumlah_denda > 0)
                                <span class="font-semibold text-red-600">
                                    Rp {{ number_format($d->dendaData->jumlah_denda, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                            <i class="fas fa-file-alt text-4xl mb-3 block opacity-30"></i>
                            Tidak ada data laporan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
