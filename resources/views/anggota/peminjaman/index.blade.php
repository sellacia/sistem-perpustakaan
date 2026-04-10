@extends('layouts.anggota.app')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-blue-700 tracking-tight">Riwayat Peminjaman Buku</h2>
    </div>

    @if ($peminjaman->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-100">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="inbox" class="w-10 h-10 text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Belum ada riwayat peminjaman!</h3>
            <p class="text-gray-500 mt-1">Kamu belum pernah meminjam buku. Yuk, mulai membaca!</p>
            <a href="{{ route('anggota.buku') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                Jelajahi Buku
            </a>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-100">
                            <th class="py-4 px-6 font-semibold">Buku</th>
                            <th class="py-4 px-6 font-semibold">Tgl Pinjam</th>
                            <th class="py-4 px-6 font-semibold">Batas Kembali</th>
                            <th class="py-4 px-6 font-semibold">Dikembalikan</th>
                            <th class="py-4 px-6 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($peminjaman as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    <img src="{{ asset('storage/' . $item->buku->gambar) }}" alt="Cover" class="w-12 h-16 object-cover rounded shadow-sm border border-gray-200">
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm">{{ $item->buku->judul }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->buku->pengarang }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($item->tanggal_wajib_kembali)->format('d M Y') }}
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-700">
                                @if($item->tanggal_kembali)
                                    {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if ($item->status == 'menunggu')
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                        <i data-lucide="clock" class="w-3 h-3 inline-block mr-1"></i> Menunggu Persetujuan
                                    </span>
                                @elseif ($item->status == 'dipinjam')
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                        <i data-lucide="book-open" class="w-3 h-3 inline-block mr-1"></i> Sedang Dipinjam
                                    </span>
                                @elseif ($item->status == 'dikembalikan')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                        <i data-lucide="check-circle" class="w-3 h-3 inline-block mr-1"></i> Selesai
                                    </span>
                                @elseif ($item->status == 'ditolak')
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                        <i data-lucide="x-circle" class="w-3 h-3 inline-block mr-1"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
