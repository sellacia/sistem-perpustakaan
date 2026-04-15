@extends('layouts.kepala.app')

@section('content')
    <div class="space-y-6">
        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ([['label' => 'Total Buku', 'value' => $totalBuku, 'icon' => 'fa-book', 'tone' => 'from-emerald-500 to-teal-500'], ['label' => 'Total Anggota', 'value' => $totalAnggota, 'icon' => 'fa-users', 'tone' => 'from-sky-500 to-cyan-500'], ['label' => 'Peminjaman Aktif', 'value' => $totalPinjam, 'icon' => 'fa-book-open-reader', 'tone' => 'from-amber-500 to-orange-500'], ['label' => 'Total Denda', 'value' => 'Rp ' . number_format($totalDenda, 0, ',', '.'), 'icon' => 'fa-wallet', 'tone' => 'from-rose-500 to-red-500']] as $card)
                <article
                    class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">{{ $card['label'] }}
                            </p>
                            <p class="mt-4 text-2xl font-bold text-slate-900">{{ $card['value'] }}</p>
                        </div>
                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br {{ $card['tone'] }} text-white shadow-lg">
                            <i class="fas {{ $card['icon'] }}"></i></div>
                    </div>
                </article>
            @endforeach
        </section>
        <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Peminjaman Terbaru</h2>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Anggota</th>
                            <th class="px-6 py-4">Buku</th>
                            <th class="px-6 py-4">Tgl Pinjam</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($peminjaman as $item)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ $item->user->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $item->buku->judul ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-center"><span
                                        class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $item->status === 'dipinjam' ? 'bg-sky-50 text-sky-700 border-sky-200' : 'bg-slate-100 text-slate-600 border-slate-200' }}">{{ ucfirst($item->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400">Belum ada data peminjaman.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
