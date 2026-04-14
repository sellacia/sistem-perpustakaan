@extends('layouts.petugas.app')

@section('content')
<div class="p-6 space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Proses Peminjaman</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola permintaan dan status peminjaman buku</p>
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

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        @php
            $statusCount = $data->groupBy('status');
        @endphp
        @foreach ([
            'menunggu'    => ['label' => 'Menunggu',     'color' => 'yellow'],
            'dipinjam'    => ['label' => 'Dipinjam',     'color' => 'blue'],
            'terlambat'   => ['label' => 'Terlambat',    'color' => 'red'],
            'dikembalikan'=> ['label' => 'Dikembalikan', 'color' => 'indigo'],
            'selesai'     => ['label' => 'Selesai',      'color' => 'green'],
        ] as $s => $info)
        <div class="bg-white rounded-xl border border-gray-100 p-3 flex items-center gap-3 shadow-sm">
            <span class="text-xl font-bold text-{{ $info['color'] }}-600">{{ $statusCount->get($s, collect())->count() }}</span>
            <span class="text-xs text-gray-500 font-medium">{{ $info['label'] }}</span>
        </div>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Daftar Peminjaman</h2>
            <span class="text-xs text-gray-400">{{ $data->count() }} data</span>
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
                        <th class="px-5 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($data as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-5 py-3 font-medium text-gray-800">
                            {{ $item->anggota->name ?? ($item->anggota->nama ?? '-') }}
                        </td>
                        <td class="px-5 py-3 text-gray-700 max-w-[180px]">
                            <div class="truncate" title="{{ $item->buku->judul ?? '-' }}">
                                {{ $item->buku->judul ?? '-' }}
                            </div>
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

                        {{-- Status Badge --}}
                        <td class="px-5 py-3 text-center">
                            @if ($item->status == 'menunggu')
                                <span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 border border-yellow-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span> Menunggu
                                </span>
                            @elseif ($item->status == 'dipinjam')
                                <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Dipinjam
                                </span>
                            @elseif ($item->status == 'terlambat')
                                <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 border border-red-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> Terlambat
                                </span>
                            @elseif ($item->status == 'dikembalikan')
                                <span class="inline-flex items-center gap-1 bg-indigo-50 text-indigo-700 border border-indigo-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Dikembalikan
                                </span>
                            @elseif ($item->status == 'selesai')
                                <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 border border-green-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Selesai
                                </span>
                            @elseif ($item->status == 'ditolak')
                                <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 border border-gray-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Ditolak
                                </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-5 py-3 text-center">
                            @if ($item->status == 'menunggu')
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="/petugas/peminjaman/setujui/{{ $item->id }}"
                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition"
                                        title="Setujui Peminjaman">
                                        <i class="fas fa-check mr-1"></i>Setujui
                                    </a>
                                    <a href="/petugas/peminjaman/tolak/{{ $item->id }}"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition"
                                        title="Tolak Peminjaman">
                                        <i class="fas fa-times mr-1"></i>Tolak
                                    </a>
                                </div>
                            @elseif (in_array($item->status, ['dipinjam', 'terlambat']))
                                <a href="/petugas/peminjaman/kembalikan/{{ $item->id }}"
                                    class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition"
                                    title="Proses Pengembalian"
                                    onclick="return confirm('Proses pengembalian buku ini?')">
                                    <i class="fas fa-undo mr-1"></i>Kembalikan
                                </a>
                            @elseif ($item->status == 'dikembalikan')
                                <a href="{{ route('petugas.pengembalian') }}"
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                    Konfirmasi →
                                </a>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                            <i class="fas fa-inbox text-4xl mb-3 block opacity-30"></i>
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
