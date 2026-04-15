@extends('layouts.anggota.app')

@section('content')
<div class="space-y-6">
    <section class="overflow-hidden rounded-[32px] bg-gradient-to-r from-slate-900 via-sky-900 to-cyan-700 px-6 py-8 text-white shadow-xl">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-2xl">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-200">Dashboard Anggota</p>
                <h1 class="mt-3 text-3xl font-bold leading-tight lg:text-4xl">Kelola pinjaman buku dan pantau denda Anda dalam satu tempat.</h1>
                <p class="mt-3 text-sm leading-7 text-slate-200">Semua informasi utama untuk anggota sudah diringkas agar status pinjaman, keterlambatan, dan pengembalian mudah dipantau.</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm backdrop-blur-sm">
                <p class="text-xs uppercase tracking-[0.18em] text-cyan-100/70">Hari Ini</p>
                <p class="mt-2 font-semibold">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</p>
            </div>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-3">
        @foreach([
            ['label' => 'Sedang Dipinjam', 'value' => $sedangDipinjam, 'icon' => 'fa-book-open-reader', 'tone' => 'from-emerald-500 to-teal-500'],
            ['label' => 'Terlambat', 'value' => $bukuTerlambat, 'icon' => 'fa-clock', 'tone' => 'from-amber-500 to-orange-500'],
            ['label' => 'Denda Aktif', 'value' => 'Rp ' . number_format($totalDenda, 0, ',', '.'), 'icon' => 'fa-wallet', 'tone' => 'from-rose-500 to-red-500'],
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

    <section class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Aturan Peminjaman</p>
            <h2 class="mt-2 text-xl font-bold text-slate-900">Panduan singkat untuk anggota</h2>
            <ol class="mt-5 space-y-4 text-sm leading-7 text-slate-600">
                <li>Durasi peminjaman maksimal 7 hari sejak buku disetujui petugas.</li>
                <li>Buku harus dikembalikan sebelum melewati tanggal wajib kembali.</li>
                <li>Keterlambatan pengembalian dikenakan denda Rp2.000 per hari per buku.</li>
                <li>Anggota wajib menjaga buku tetap dalam kondisi baik.</li>
                <li>Jika buku hilang atau rusak, penggantian mengikuti ketentuan perpustakaan.</li>
            </ol>
        </div>
        <div class="rounded-3xl bg-slate-900 p-6 text-white shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Akses Cepat</p>
            <div class="mt-5 grid gap-3">
                <a href="{{ route('anggota.buku') }}" class="flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold transition hover:bg-white/10">Jelajahi Buku <i class="fas fa-chevron-right text-xs"></i></a>
                <a href="{{ route('anggota.pengembalian') }}" class="flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold transition hover:bg-white/10">Ajukan Pengembalian <i class="fas fa-chevron-right text-xs"></i></a>
                <a href="{{ route('anggota.riwayat') }}" class="flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold transition hover:bg-white/10">Lihat Riwayat & Denda <i class="fas fa-chevron-right text-xs"></i></a>
            </div>
        </div>
    </section>
</div>
@endsection
