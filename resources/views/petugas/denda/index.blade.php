@extends('layouts.petugas.app')

@section('content')
<div class="space-y-6">
    @php
        $resolveStatus = function ($item) {
            return in_array($item->peminjaman->status_denda ?? null, ['menunggu_konfirmasi', 'sudah_bayar'], true)
                ? $item->peminjaman->status_denda
                : $item->status;
        };
        $totalBelumBayar = $data->filter(fn($item) => $resolveStatus($item) === 'belum_bayar')->sum('jumlah_denda');
        $totalMenungguKonfirmasi = $data->filter(fn($item) => $resolveStatus($item) === 'menunggu_konfirmasi')->sum('jumlah_denda');
        $totalSudahBayar = $data->filter(fn($item) => $resolveStatus($item) === 'sudah_bayar')->sum('jumlah_denda');
        $countBelum = $data->filter(fn($item) => $resolveStatus($item) === 'belum_bayar')->count();
        $countMenunggu = $data->filter(fn($item) => $resolveStatus($item) === 'menunggu_konfirmasi')->count();
    @endphp

    @if(session('success'))
    <div data-auto-dismiss class="transform rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm transition duration-300">
        <div class="flex items-center gap-3"><i class="fas fa-circle-check"></i><span>{{ session('success') }}</span></div>
    </div>
    @endif
    @if(session('error'))
    <div data-auto-dismiss class="transform rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm transition duration-300">
        <div class="flex items-center gap-3"><i class="fas fa-circle-exclamation"></i><span>{{ session('error') }}</span></div>
    </div>
    @endif

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Belum Dibayar</p>
            <p class="mt-4 text-3xl font-bold text-slate-900">{{ $countBelum }}</p>
            <p class="mt-1 text-sm font-semibold text-rose-600">Rp {{ number_format($totalBelumBayar, 0, ',', '.') }}</p>
        </article>
        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Menunggu Konfirmasi</p>
            <p class="mt-4 text-3xl font-bold text-slate-900">{{ $countMenunggu }}</p>
            <p class="mt-1 text-sm font-semibold text-amber-600">Rp {{ number_format($totalMenungguKonfirmasi, 0, ',', '.') }}</p>
        </article>
        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Sudah Dibayar</p>
            <p class="mt-4 text-3xl font-bold text-slate-900">{{ $data->where('status', 'sudah_bayar')->count() }}</p>
            <p class="mt-1 text-sm font-semibold text-emerald-600">Rp {{ number_format($totalSudahBayar, 0, ',', '.') }}</p>
        </article>
        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Total Nominal</p>
            <p class="mt-4 text-3xl font-bold text-slate-900">Rp {{ number_format($totalBelumBayar + $totalSudahBayar, 0, ',', '.') }}</p>
            <p class="mt-1 text-sm text-slate-500">{{ $data->count() }} catatan denda</p>
        </article>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-5">
            <h2 class="text-lg font-bold text-slate-900">Rincian Denda</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Anggota</th>
                        <th class="px-6 py-4">Buku</th>
                        <th class="px-6 py-4 text-center">Terlambat</th>
                        <th class="px-6 py-4 text-right">Jumlah</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($data as $d)
                    @php
                        $statusDenda = in_array($d->peminjaman->status_denda ?? null, ['menunggu_konfirmasi', 'sudah_bayar'], true)
                            ? $d->peminjaman->status_denda
                            : $d->status;
                    @endphp
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-6 py-4 text-slate-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $d->peminjaman->anggota->name ?? ($d->peminjaman->nama ?? '-') }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ $d->peminjaman->buku->judul ?? '-' }}</td>
                        <td class="px-6 py-4 text-center"><span class="rounded-full bg-orange-50 px-3 py-1 text-xs font-semibold text-orange-700">{{ $d->terlambat }} hari</span></td>
                        <td class="px-6 py-4 text-right font-bold text-slate-900">Rp {{ number_format($d->jumlah_denda, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $statusDenda == 'belum_bayar' ? 'border-rose-200 bg-rose-50 text-rose-700' : ($statusDenda == 'menunggu_konfirmasi' ? 'border-amber-200 bg-amber-50 text-amber-700' : 'border-emerald-200 bg-emerald-50 text-emerald-700') }}">
                                {{ $statusDenda == 'belum_bayar' ? 'Belum Bayar' : ($statusDenda == 'menunggu_konfirmasi' ? 'Menunggu Konfirmasi' : 'Lunas') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-wrap items-center justify-center gap-2">
                                <a href="{{ route('petugas.denda.cetak', $d->id) }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl border border-sky-200 bg-sky-50 px-3 py-2 text-xs font-semibold text-sky-700 transition hover:bg-sky-100">
                                    <i class="fas fa-print"></i> Cetak
                                </a>
                                @if ($statusDenda == 'menunggu_konfirmasi')
                                <form action="{{ route('petugas.denda.bayar', $d->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" data-confirm data-confirm-title="Konfirmasi pelunasan?" data-confirm-message="Denda untuk {{ $d->peminjaman->anggota->name ?? 'anggota' }} akan ditandai sudah dibayar." class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-emerald-700">
                                        <i class="fas fa-check"></i> Konfirmasi Lunas
                                    </button>
                                </form>
                                @elseif ($statusDenda == 'belum_bayar')
                                <span class="text-xs font-semibold text-slate-400">Menunggu aksi anggota</span>
                                @else
                                <span class="text-xs font-semibold text-emerald-600">Sudah terbayar</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">Tidak ada data denda.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
