@extends('layouts.petugas.app')

@section('content')
    <!-- JUDUL -->
    <h1 class="text-2xl font-bold text-blue-700 mb-6">
        Laporan Peminjaman
    </h1>

    <!-- CARD -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">

        <!-- HEADER CARD -->
        <div class="px-6 py-4 border-b">
            <h2 class="text-blue-700 font-semibold text-lg">
                Daftar Laporan
            </h2>
        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">

                <!-- HEAD -->
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Buku</th>
                        <th class="px-4 py-3">Tgl Pinjam</th>
                        <th class="px-4 py-3">Tgl Kembali</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3">Denda</th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody class="divide-y">
                    @forelse ($data as $d)
                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-4 py-3">
                               {{ $d->anggota->name ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $d->buku->judul }}
                            </td>

                            <td class="px-4 py-3">
                                {{ \Carbon\Carbon::parse($d->tanggal_pinjam)->format('d-m-Y') }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $d->tanggal_kembali ? \Carbon\Carbon::parse($d->tanggal_kembali)->format('d-m-Y') : '-' }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                @if ($d->status == 'dikembalikan')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Dikembalikan
                                    </span>
                                @elseif ($d->status == 'selesai')
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Selesai
                                    </span>
                                @elseif ($d->status == 'terlambat')
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Terlambat
                                    </span>
                                @elseif ($d->status == 'menunggu')
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Menunggu
                                    </span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        {{ ucfirst($d->status) }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 font-semibold">
                                @if ($d->denda && $d->denda->jumlah_denda)
                                    Rp {{ number_format($d->denda->jumlah_denda, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-4 text-gray-500">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

@endsection
