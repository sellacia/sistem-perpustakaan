@extends('layouts.anggota.app')

@section('content')
<div class="p-6">

    <h2 class="text-xl text-blue-600 font-semibold mb-4">Denda</h2>

    <div class="bg-white p-6 rounded shadow">

        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">Nama</th> <!-- 🔥 TAMBAHAN -->
                    <th>Judul Buku</th>
                    <th>Terlambat</th>
                    <th>Total Denda</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $item)
                <tr class="text-center border-b">

                    <!-- 🔥 NAMA PEMINJAM -->
                    <td class="p-2">
                        {{ $item->peminjaman->nama ?? '-' }}
                    </td>

                    <!-- JUDUL -->
                    <td>
                        {{ $item->peminjaman->buku->judul ?? '-' }}
                    </td>

                    <!-- TERLAMBAT -->
                    <td>
                        {{ $item->terlambat }} hari
                    </td>

                    <!-- DENDA -->
                    <td>
                        Rp {{ number_format($item->jumlah_denda) }}
                    </td>

                    <!-- STATUS -->
                    <td>
                        @if($item->status == 'belum')
                            <span class="bg-red-500 text-white px-2 py-1 rounded">
                                Belum
                            </span>
                        @else
                            <span class="bg-green-500 text-white px-2 py-1 rounded">
                                Sudah
                            </span>
                        @endif
                    </td>

                    <!-- AKSI -->
                    <td>
                        @if($item->status == 'belum')
                        <form action="{{ route('denda.bayar', $item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded">
                                Bayar
                            </button>
                        </form>
                        @else
                            <span class="text-gray-500">Selesai</span>
                        @endif
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-gray-400 py-4">
                        Tidak ada denda
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>
@endsection
