@extends('layouts.petugas.app')

@section('content')
<div class="space-y-6">

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        @foreach([
            ['label' => 'Total Buku', 'value' => $totalBuku, 'icon' => 'fa-book', 'tone' => 'from-sky-500 to-cyan-500'],
            ['label' => 'Total Anggota', 'value' => $totalAnggota, 'icon' => 'fa-users', 'tone' => 'from-indigo-500 to-blue-500'],
            ['label' => 'Sedang Dipinjam', 'value' => $dipinjam, 'icon' => 'fa-book-open-reader', 'tone' => 'from-amber-500 to-orange-500'],
            ['label' => 'Terlambat', 'value' => $terlambat, 'icon' => 'fa-clock', 'tone' => 'from-rose-500 to-red-500'],
            ['label' => 'Denda Aktif', 'value' => 'Rp ' . number_format($denda, 0, ',', '.'), 'icon' => 'fa-wallet', 'tone' => 'from-emerald-500 to-teal-500'],
        ] as $card)
        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">{{ $card['label'] }}</p>
                    <p class="mt-4 text-2xl font-bold text-slate-900">{{ $card['value'] }}</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br {{ $card['tone'] }} text-white shadow-lg">
                    <i class="fas {{ $card['icon'] }}"></i>
                </div>
            </div>
        </article>
        @endforeach
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.25fr_0.75fr]">
        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Peminjaman Terbaru</h2>
                </div>
                <a href="{{ route('petugas.peminjaman') }}" class="inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Lihat Semua <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Anggota</th>
                            <th class="px-6 py-4">Buku</th>
                            <th class="px-6 py-4">Pinjam</th>
                            <th class="px-6 py-4">Kembali</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($data as $item)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-900">{{ $item->anggota->name ?? ($item->user->name ?? '-') }}</p>
                                <p class="text-xs text-slate-500">{{ $item->anggota->username ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-slate-700">{{ $item->buku->judul ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $badgeMap = [
                                        'menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'dipinjam' => 'bg-sky-50 text-sky-700 border-sky-200',
                                        'terlambat' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'dikembalikan' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'ditolak' => 'bg-slate-100 text-slate-600 border-slate-200',
                                    ];
                                @endphp
                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $badgeMap[$item->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">{{ ucfirst($item->status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold {{ ($item->dendaData->jumlah_denda ?? 0) > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                                {{ ($item->dendaData->jumlah_denda ?? 0) > 0 ? 'Rp ' . number_format($item->dendaData->jumlah_denda, 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada transaksi peminjaman terbaru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Prioritas Hari Ini</p>
                <div class="mt-5 space-y-4">
                    <div class="rounded-2xl bg-rose-50 p-4">
                        <p class="text-sm font-semibold text-rose-700">Peminjaman terlambat</p>
                        <p class="mt-2 text-3xl font-bold text-rose-900">{{ $terlambat }}</p>
                    </div>
                    <div class="rounded-2xl bg-amber-50 p-4">
                        <p class="text-sm font-semibold text-amber-700">Denda belum lunas</p>
                        <p class="mt-2 text-3xl font-bold text-amber-900">Rp {{ number_format($denda, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
