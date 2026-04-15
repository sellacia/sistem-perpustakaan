@extends('layouts.anggota.app')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-600">Detail Buku</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">{{ $buku->judul }}</h1>
            <p class="mt-2 text-sm text-slate-500">Lihat informasi lengkap buku sebelum mengajukan peminjaman.</p>
        </div>
        <a href="{{ route('anggota.buku') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-[0.85fr_1.15fr]">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="aspect-[4/5] overflow-hidden bg-slate-100">
                <img src="{{ $buku->cover ? asset('assets/images/' . $buku->cover) : 'https://via.placeholder.com/300x500' }}" class="h-full w-full object-cover">
            </div>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="rounded-full {{ $buku->stok > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }} px-3 py-1 text-xs font-semibold">{{ $buku->stok > 0 ? 'Tersedia' : 'Tidak Tersedia' }}</span>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $buku->kode_buku }}</span>
            </div>
            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl bg-slate-50 p-4"><p class="text-xs uppercase tracking-[0.16em] text-slate-400">Pengarang</p><p class="mt-2 font-semibold text-slate-900">{{ $buku->pengarang }}</p></div>
                <div class="rounded-2xl bg-slate-50 p-4"><p class="text-xs uppercase tracking-[0.16em] text-slate-400">Penerbit</p><p class="mt-2 font-semibold text-slate-900">{{ $buku->penerbit }}</p></div>
                <div class="rounded-2xl bg-slate-50 p-4"><p class="text-xs uppercase tracking-[0.16em] text-slate-400">Tahun</p><p class="mt-2 font-semibold text-slate-900">{{ $buku->tahun }}</p></div>
                <div class="rounded-2xl bg-slate-50 p-4"><p class="text-xs uppercase tracking-[0.16em] text-slate-400">Stok</p><p class="mt-2 font-semibold text-slate-900">{{ $buku->stok }} buku</p></div>
            </div>
            <div class="mt-5 rounded-2xl bg-slate-50 p-4">
                <p class="text-xs uppercase tracking-[0.16em] text-slate-400">Deskripsi</p>
                <p class="mt-2 text-sm leading-7 text-slate-600">{{ $buku->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
            </div>
            <div class="mt-6 flex gap-3">
                @if ($buku->stok > 0)
                <form action="{{ route('anggota.pinjam', $buku->id) }}" method="POST">
                    @csrf
                    <button type="submit" data-confirm data-confirm-title="Ajukan peminjaman?" data-confirm-message="Pengajuan buku {{ $buku->judul }} akan dikirim ke petugas untuk diproses." class="rounded-2xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">Pinjam Buku</button>
                </form>
                @else
                <button disabled class="rounded-2xl bg-slate-200 px-5 py-3 text-sm font-semibold text-slate-500">Tidak Tersedia</button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
