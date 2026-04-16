@extends('layouts.petugas.app')

@section('content')
<div class="space-y-6">

    {{-- NOTIFIKASI --}}
    @if(session('success'))
    <div data-auto-dismiss class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm">
        <div class="flex items-center gap-3"><i class="fas fa-circle-check"></i><span>{{ session('success') }}</span></div>
    </div>
    @endif
    @if(session('error'))
    <div data-auto-dismiss class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm">
        <div class="flex items-center gap-3"><i class="fas fa-circle-exclamation"></i><span>{{ session('error') }}</span></div>
    </div>
    @endif

    {{-- TABEL --}}
    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Daftar Pengembalian</h2>
                <p class="text-xs text-slate-400 mt-1">Buku yang sudah dikembalikan anggota, menunggu konfirmasi petugas.</p>
            </div>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                {{ $pinjam->count() }} data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Anggota</th>
                        <th class="px-6 py-4">Buku</th>
                        <th class="px-6 py-4">Tgl Pinjam</th>
                        <th class="px-6 py-4">Batas Kembali</th>
                        <th class="px-6 py-4">Tgl Dikembalikan</th>
                        <th class="px-6 py-4 text-center">Kondisi</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Total Denda</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($pinjam as $item)
                    @php
                        $denda       = $item->dendaData;
                        $totalDenda  = $denda?->jumlah_denda ?? 0;
                        $statusDenda = $denda?->status ?? 'sudah_bayar';
                    @endphp
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-6 py-4 text-slate-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $item->anggota->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ $item->buku->judul ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ \Carbon\Carbon::parse($item->tanggal_wajib_kembali)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}
                        </td>

                        {{-- KONDISI --}}
                        <td class="px-6 py-4 text-center">
                            @if($item->kondisi === 'baik')
                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold border-emerald-200 bg-emerald-50 text-emerald-700">
                                    ✅ Baik
                                </span>
                            @elseif($item->kondisi === 'rusak')
                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold border-amber-200 bg-amber-50 text-amber-700">
                                    ⚠️ Rusak
                                </span>
                            @elseif($item->kondisi === 'hilang')
                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold border-rose-200 bg-rose-50 text-rose-700">
                                    ❌ Hilang
                                </span>
                            @else
                                <span class="text-slate-400 text-xs">-</span>
                            @endif
                        </td>

                        {{-- STATUS PEMINJAMAN --}}
                        <td class="px-6 py-4 text-center">
                            @if($item->status === 'selesai')
                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold border-emerald-200 bg-emerald-50 text-emerald-700">
                                    Selesai
                                </span>
                            @else
                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold border-amber-200 bg-amber-50 text-amber-700">
                                    Menunggu Konfirmasi
                                </span>
                            @endif
                        </td>

                        {{-- TOTAL DENDA --}}
                        <td class="px-6 py-4 text-right">
                            @if($totalDenda > 0)
                                <p class="font-bold {{ $statusDenda === 'sudah_bayar' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    Rp {{ number_format($totalDenda, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-slate-400 mt-1">
                                    @if($statusDenda === 'sudah_bayar') Lunas
                                    @elseif($statusDenda === 'menunggu_konfirmasi') Menunggu konfirmasi
                                    @else Belum bayar
                                    @endif
                                </p>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="px-6 py-4 text-center">
                            @if($item->status === 'dikembalikan')
                                @php
                                    $dendaBelumLunas = $denda && in_array($denda->status, ['belum_bayar', 'menunggu_konfirmasi']);
                                @endphp

                                @if($dendaBelumLunas)
                                    <span class="inline-flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-700">
                                        <i class="fas fa-wallet"></i> Tunggu Denda Lunas
                                    </span>
                                @else
                                    <form action="{{ route('petugas.pengembalian.konfirmasi', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-emerald-700">
                                            <i class="fas fa-check"></i> Konfirmasi Selesai
                                        </button>
                                    </form>
                                @endif
                            @elseif($item->status === 'selesai')
                                <span class="text-xs font-semibold text-emerald-500">
                                    <i class="fas fa-circle-check"></i> Selesai
                                </span>
                            @else
                                <span class="text-xs text-slate-300">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-slate-400">
                            Tidak ada data pengembalian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
