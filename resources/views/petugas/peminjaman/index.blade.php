@extends('layouts.petugas.app')

@section('content')
    <div class="p-6">

        <!-- Judul -->
        <h1 class="text-2xl font-bold text-blue-700 mb-6">
            Proses Peminjaman
        </h1>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">

            <!-- Header -->
            <div class="px-6 py-4 border-b">
                <h2 class="text-blue-700 font-semibold text-lg">
                    Daftar Peminjaman
                </h2>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">

                    <!-- Head -->
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Buku</th>
                            <th class="px-4 py-3">Tgl Pinjam</th>
                            <th class="px-4 py-3">Wajib Kembali</th>
                            <th class="px-4 py-3">Tgl Kembali</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <!-- Body -->
                    <tbody class="divide-y">
                        @foreach ($data as $item)
                            <tr class="hover:bg-gray-50 transition">

                                <!-- ID -->
                                <td class="px-4 py-3 font-medium">
                                    {{ $item->id }}
                                </td>

                                <!-- Nama -->
                                <td class="px-4 py-3">
                                    {{ $item->anggota->nama ?? ($item->nama ?? '-') }}
                                </td>

                                <!-- Buku -->
                                <td class="px-4 py-3">
                                    {{ $item->buku->judul ?? '-' }}
                                </td>

                                <!-- Tanggal -->
                                <td class="px-4 py-3">
                                   {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d-m-Y') }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ \Carbon\Carbon::parse($item->tanggal_wajib_kembali)->format('d-m-Y') }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d-m-Y') : '-' }}
                                </td>

                                <!-- STATUS -->
                                <td class="px-4 py-3 text-center">
                                    @if ($item->status == 'menunggu')
                                        <span
                                            class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            Menunggu
                                        </span>
                                    @elseif ($item->status == 'dipinjam')
                                        <span
                                            class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            Dipinjam
                                        </span>
                                    @elseif ($item->status == 'kembali')
                                        <span
                                            class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            Kembali
                                        </span>
                                    @elseif ($item->status == 'ditolak')
                                        <span
                                            class="bg-gray-200 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>

                                <!-- AKSI -->
                                <td class="px-4 py-3 text-center">
                                    @if ($item->status == 'menunggu')
                                        <div class="flex justify-center gap-2">
                                            <a href="/petugas/peminjaman/setujui/{{ $item->id }}"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-xs">
                                                ✔
                                            </a>

                                            <a href="/petugas/peminjaman/tolak/{{ $item->id }}"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs">
                                                ✖
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs italic">
                                            Selesai
                                        </span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

    </div>
@endsection
