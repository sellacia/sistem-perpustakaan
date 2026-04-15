@extends('layouts.kepala.app')

@section('content')
<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-[32px] bg-gradient-to-r from-cyan-600 via-sky-600 to-blue-700 px-6 py-8 text-white shadow-xl lg:flex-row lg:items-end lg:justify-between">
        <div class="max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-100">Katalog Buku</p>
            <h1 class="mt-3 text-3xl font-bold">Pantau koleksi buku perpustakaan dengan tampilan yang lebih rapi.</h1>
            <p class="mt-3 text-sm leading-7 text-cyan-50/90">Area kepala kini memakai pola visual yang sama dengan dashboard petugas supaya lebih konsisten.</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm backdrop-blur-sm"><p class="text-xs uppercase tracking-[0.18em] text-cyan-100/70">Total Buku</p><p class="mt-2 text-2xl font-bold">{{ count($buku) }}</p></div>
    </section>
    <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($buku as $item)
        <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
            <div class="aspect-[4/5] overflow-hidden bg-slate-100"><img src="{{ $item->cover ? asset('assets/images/' . $item->cover) : 'https://via.placeholder.com/300x500' }}" class="h-full w-full object-cover"></div>
            <div class="p-5">
                <div class="flex items-start justify-between gap-4"><div><h3 class="text-lg font-bold text-slate-900">{{ $item->judul }}</h3><p class="mt-1 text-sm text-slate-500">{{ $item->pengarang }}</p></div><span class="rounded-full {{ $item->stok > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }} px-3 py-1 text-xs font-semibold">{{ $item->stok > 0 ? 'Tersedia' : 'Kosong' }}</span></div>
                <div class="mt-4 grid grid-cols-2 gap-3 text-sm text-slate-600"><div class="rounded-2xl bg-slate-50 p-3"><p class="text-xs uppercase tracking-[0.16em] text-slate-400">Kode</p><p class="mt-1 font-semibold text-slate-800">{{ $item->kode_buku }}</p></div><div class="rounded-2xl bg-slate-50 p-3"><p class="text-xs uppercase tracking-[0.16em] text-slate-400">Stok</p><p class="mt-1 font-semibold text-slate-800">{{ $item->stok }} buku</p></div><div class="col-span-2 rounded-2xl bg-slate-50 p-3"><p class="text-xs uppercase tracking-[0.16em] text-slate-400">Penerbit</p><p class="mt-1 font-semibold text-slate-800">{{ $item->penerbit }}</p></div></div>
                <div class="mt-5"><a href="{{ route('kepala.buku.show', $item->id) }}" class="block rounded-2xl bg-sky-600 px-4 py-3 text-center text-sm font-semibold text-white transition hover:bg-sky-700">Lihat Detail</a></div>
            </div>
        </article>
        @empty
        <div class="col-span-full rounded-3xl border border-slate-200 bg-white px-6 py-16 text-center text-slate-400 shadow-sm">Belum ada data buku.</div>
        @endforelse
    </section>
</div>
@endsection
