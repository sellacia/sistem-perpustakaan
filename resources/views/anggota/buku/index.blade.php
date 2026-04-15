@extends('layouts.anggota.app')

@section('content')
<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-[32px] bg-gradient-to-r from-cyan-600 via-sky-600 to-blue-700 px-6 py-8 text-white shadow-xl lg:flex-row lg:items-end lg:justify-between">
        <div class="max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-100">Katalog Buku</p>
            <h1 class="mt-3 text-3xl font-bold">Temukan buku yang siap Anda pinjam.</h1>
            <p class="mt-3 text-sm leading-7 text-cyan-50/90">Halaman katalog dibuat lebih rapi agar pencarian buku, detail, dan pengajuan pinjam terasa lebih nyaman.</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm backdrop-blur-sm">
            <p class="text-xs uppercase tracking-[0.18em] text-cyan-100/70">Buku Tersedia</p>
            <p class="mt-2 text-2xl font-bold">{{ count($buku) }}</p>
        </div>
    </section>

    @if(session('success'))<div data-auto-dismiss class="transform rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm transition duration-300"><div class="flex items-center gap-3"><i class="fas fa-circle-check"></i><span>{{ session('success') }}</span></div></div>@endif
    @if(session('error'))<div data-auto-dismiss class="transform rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm transition duration-300"><div class="flex items-center gap-3"><i class="fas fa-circle-exclamation"></i><span>{{ session('error') }}</span></div></div>@endif

    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('anggota.buku') }}" class="relative">
            <i class="fas fa-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" name="search" placeholder="Cari judul, pengarang, atau kode buku..." value="{{ request('search') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-700 outline-none transition focus:border-sky-300 focus:bg-white focus:ring-4 focus:ring-sky-100">
        </form>
    </section>

    <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($buku as $item)
        <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
            <div class="aspect-[4/5] overflow-hidden bg-slate-100">
                <img src="{{ $item->cover ? asset('storage/' . $item->cover) : 'https://via.placeholder.com/300x500' }}" class="h-full w-full object-cover">
            </div>
            <div class="p-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">{{ $item->judul }}</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ $item->pengarang }}</p>
                    </div>
                    <span class="rounded-full {{ $item->stok > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }} px-3 py-1 text-xs font-semibold">{{ $item->stok > 0 ? 'Tersedia' : 'Kosong' }}</span>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-3 text-sm text-slate-600">
                    <div class="rounded-2xl bg-slate-50 p-3"><p class="text-xs uppercase tracking-[0.16em] text-slate-400">Kode</p><p class="mt-1 font-semibold text-slate-800">{{ $item->kode_buku }}</p></div>
                    <div class="rounded-2xl bg-slate-50 p-3"><p class="text-xs uppercase tracking-[0.16em] text-slate-400">Stok</p><p class="mt-1 font-semibold text-slate-800">{{ $item->stok }} buku</p></div>
                    <div class="col-span-2 rounded-2xl bg-slate-50 p-3"><p class="text-xs uppercase tracking-[0.16em] text-slate-400">Penerbit</p><p class="mt-1 font-semibold text-slate-800">{{ $item->penerbit }}</p></div>
                </div>
                <div class="mt-5 flex gap-2">
                    <a href="{{ route('anggota.buku.detail', $item->id) }}" class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Detail</a>
                    @if ($item->stok > 0)
                    <form action="{{ route('anggota.pinjam', $item->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" data-confirm data-confirm-title="Ajukan peminjaman?" data-confirm-message="Pengajuan buku {{ $item->judul }} akan dikirim ke petugas untuk diproses." class="w-full rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">Pinjam</button>
                    </form>
                    @else
                    <button disabled class="flex-1 rounded-2xl bg-slate-200 px-4 py-3 text-sm font-semibold text-slate-500">Tidak Tersedia</button>
                    @endif
                </div>
            </div>
        </article>
        @empty
        <div class="col-span-full rounded-3xl border border-slate-200 bg-white px-6 py-16 text-center text-slate-400 shadow-sm">Belum ada buku yang cocok dengan pencarian Anda.</div>
        @endforelse
    </section>
</div>
@endsection
