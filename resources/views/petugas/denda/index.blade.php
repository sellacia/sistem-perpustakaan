@extends('layouts.petugas.app')

@section('content')
    <div class="p-6">

        <!-- TITLE -->
        <h1 class="text-2xl font-bold text-blue-700 mb-6">Kelola Denda</h1>

        <!-- FILTER -->
        <div class="bg-white p-4 rounded-xl shadow flex items-center gap-4 mb-6">

            <span class="text-sm">Tanggal Pinjam</span>

            <input type="date" class="border rounded-lg px-3 py-2">

            <span>-</span>

            <input type="date" class="border rounded-lg px-3 py-2">

            <div class="ml-auto flex items-center gap-2">
                <input type="text" placeholder="Nama / Buku" class="border rounded-lg px-3 py-2 text-sm">

                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Tampilkan
                </button>
            </div>
        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-xl shadow overflow-hidden">

            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-3">Nama Anggota</th>
                        <th>Judul Buku</th>
                        <th>Terlambat</th>
                        <th>Total Denda</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $d)
                        <tr class="border-t text-center">

                            <!-- NAMA -->
                            <td class="p-3">
                                {{ $d->peminjaman->anggota->nama ?? '-' }}
                            </td>

                            <!-- BUKU -->
                            <td>
                                {{ $d->peminjaman->buku->judul ?? '-' }}
                            </td>

                            <!-- TERLAMBAT -->
                            <td>
                                @if ($d->terlambat > 0)
                                    <span class="bg-red-100 text-red-600 px-3 py-1 rounded-lg font-semibold">
                                        {{ $d->terlambat }} Hari
                                    </span>
                                @else
                                    <span class="bg-green-100 text-green-600 px-3 py-1 rounded-lg">
                                        0 Hari
                                    </span>
                                @endif
                            </td>

                            <!-- TOTAL DENDA -->
                            <td>
                                Rp {{ number_format($d->jumlah_denda ?? ($d->total_denda ?? 0), 0, ',', '.') }}
                            </td>

                            <!-- STATUS -->
                            <td>
                                @if ($d->status == 'belum_bayar')
                                    <span class="bg-red-500 text-white px-3 py-1 rounded-lg">
                                        Belum Bayar
                                    </span>
                                @else
                                    <span class="bg-green-500 text-white px-3 py-1 rounded-lg">
                                        Sudah Bayar
                                    </span>
                                @endif
                            </td>

                            <!-- AKSI -->
                            <td>
                                @if ($d->status == 'belum_bayar')
                                    <form action="/petugas/denda/bayar/{{ $d->id }}" method="GET">
                                        <button class="bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600">
                                            Konfirmasi Bayar
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400">Selesai</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-5 text-center text-gray-400">
                                Tidak ada data denda
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

    </div>
@endsection
