@extends('layouts.kepala.app')

@section('content')
    <div class="p-6">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-blue-700">
                Laporan Peminjaman Buku
            </h1>
            <a href="{{ route('kepala.laporan.export-pdf') }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md transition">
                <i class="fas fa-file-pdf text-lg"></i>
                Export PDF
            </a>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow">

            <div class="overflow-x-auto">
                <table class="w-full text-sm">

                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="p-3">No</th>
                            <th class="p-3">Nama</th>
                            <th class="p-3">Judul Buku</th>
                            <th class="p-3">Tanggal Pinjam</th>
                            <th class="p-3">Tanggal Kembali</th>
                            <th class="p-3">Status</th>
                        </tr>
                    </thead>

                    <tbody class="text-center">

                        @forelse($laporan as $i => $data)
                            <tr class="border-t">
                                <td class="p-3">{{ $i + 1 }}</td>
                                <td>{{ $data->anggota->name ?? '-' }}</td>
                                <td>{{ $data->buku->judul }}</td>
                                <td>{{ $data->tanggal_pinjam }}</td>
                                <td>{{ $data->tanggal_kembali ?? '-' }}</td>
                                <td>
                                    <span
                                        class="px-3 py-1 text-white text-xs rounded-full
                                {{ $data->status == 'dipinjam' ? 'bg-yellow-500' : 'bg-green-500' }}">
                                        {{ $data->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-gray-500">
                                    Belum ada data laporan
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>

    </div>
@endsection
