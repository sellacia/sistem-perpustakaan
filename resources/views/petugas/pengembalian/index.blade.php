@extends('layouts.petugas.app')

@section('content')
<div class="p-6 space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Proses Pengembalian</h1>
        <p class="text-sm text-gray-500 mt-1">Konfirmasi pengembalian buku dari anggota</p>
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

    {{-- Info Box --}}
    <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-200 text-blue-700 rounded-xl text-sm">
        <i class="fas fa-info-circle text-blue-500 mt-0.5 shrink-0"></i>
        <div>
            <p class="font-medium">Petunjuk</p>
            <p class="text-blue-600 mt-0.5">Halaman ini menampilkan buku yang sudah dikembalikan/terlambat. Klik <strong>Konfirmasi</strong> untuk menyelesaikan proses pengembalian.</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Daftar Pengembalian</h2>
            <span class="text-xs text-gray-400">{{ $pinjam->count() }} data</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">No</th>
                        <th class="px-5 py-3 text-left font-semibold">Nama Anggota</th>
                        <th class="px-5 py-3 text-left font-semibold">Judul Buku</th>
                        <th class="px-5 py-3 text-left font-semibold">Tgl Pinjam</th>
                        <th class="px-5 py-3 text-left font-semibold">Batas Kembali</th>
                        <th class="px-5 py-3 text-left font-semibold">Tgl Dikembalikan</th>
                        <th class="px-5 py-3 text-center font-semibold">Status</th>
                        <th class="px-5 py-3 text-right font-semibold">Denda</th>
                        <th class="px-5 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($pinjam as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-5 py-3 font-medium text-gray-800">
                            {{ $item->anggota->name ?? '-' }}
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
                        <td class="px-5 py-3 text-center">
                            @if ($item->status == 'dikembalikan')
                                <span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 border border-yellow-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span> Menunggu Konfirmasi
                                </span>
                            @elseif ($item->status == 'terlambat')
                                <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 border border-red-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> Terlambat
                                </span>
                            @elseif ($item->status == 'selesai')
                                <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 border border-green-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Selesai
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            @if ($item->dendaData && $item->dendaData->jumlah_denda > 0)
                                <span class="font-semibold text-red-600">
                                    Rp {{ number_format($item->dendaData->jumlah_denda, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-center">
                            @if ($item->status == 'dikembalikan')
                                <button type="button"
                                    onclick="openModal({{ $item->id }}, '{{ addslashes($item->anggota->name ?? '-') }}', '{{ addslashes($item->buku->judul ?? '-') }}')"
                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                    <i class="fas fa-check mr-1"></i>Konfirmasi
                                </button>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-12 text-center text-gray-400">
                            <i class="fas fa-check-double text-4xl mb-3 block opacity-30"></i>
                            Tidak ada data pengembalian yang perlu dikonfirmasi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi --}}
<div id="modal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
        <div class="text-center mb-4">
            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-check text-green-500 text-xl"></i>
            </div>
            <h2 class="font-bold text-lg text-gray-800">Konfirmasi Pengembalian</h2>
            <p class="text-sm text-gray-500 mt-1">Apakah pengembalian ini sudah selesai?</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-4 mb-5 space-y-2">
            <div class="flex items-center gap-2 text-sm">
                <i class="fas fa-user text-gray-400 w-4"></i>
                <span class="text-gray-500">Anggota:</span>
                <span class="font-semibold text-gray-800" id="namaAnggota"></span>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <i class="fas fa-book text-gray-400 w-4"></i>
                <span class="text-gray-500">Buku:</span>
                <span class="font-semibold text-gray-800" id="judulBuku"></span>
            </div>
        </div>
        <form id="formKonfirmasi" method="POST">
            @csrf
            <div class="flex gap-3">
                <button type="button" onclick="closeModal()"
                    class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium text-sm transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium text-sm transition">
                    <i class="fas fa-check mr-1"></i>Konfirmasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id, nama, buku) {
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('namaAnggota').innerText = nama;
    document.getElementById('judulBuku').innerText = buku;
    document.getElementById('formKonfirmasi').action = `/petugas/pengembalian/${id}/konfirmasi`;
}
function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}
</script>
@endsection
