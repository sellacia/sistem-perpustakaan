@extends('layouts.kepala.app')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">

        <!-- HEADER -->
        <div class="flex items-center justify-between gap-4">
            <div>
                <!-- Label halaman -->
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-600">
                    Detail Buku
                </p>

                <!-- Judul buku -->
                <h1 class="mt-2 text-3xl font-bold text-slate-900">
                    {{ $buku->judul }}
                </h1>
            </div>

            <!-- Tombol kembali -->
            <a href="{{ route('kepala.buku.index') }}"
                class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- GRID UTAMA -->
        <div class="grid gap-6 lg:grid-cols-[0.85fr_1.15fr]">

            <!-- BAGIAN COVER -->
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="aspect-[4/5] overflow-hidden bg-slate-100">

                    <!-- Jika ada cover -->
                    @if ($buku->cover)
                        <img src="{{ $buku->cover ? asset('assets/images/' . basename($buku->cover)) : 'https://via.placeholder.com/300x400' }}"
                            class="w-full h-full object-cover">
                    @else
                        <!-- Jika tidak ada gambar -->
                        <div class="flex h-full items-center justify-center text-slate-400">
                            Tidak ada gambar
                        </div>
                    @endif

                </div>
            </div>

            <!-- BAGIAN DETAIL -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                <!-- KODE + STATUS -->
                <div class="flex items-center gap-3">

                    <!-- Kode buku -->
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                        {{ $buku->kode_buku }}
                    </span>

                    <!-- Status buku -->
                    <span
                        class="rounded-full {{ $buku->stok > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }} px-3 py-1 text-xs font-semibold">
                        {{ $buku->status }}
                    </span>
                </div>

                <!-- INFORMASI BUKU -->
                <div class="mt-6 grid gap-4 md:grid-cols-2">

                    <!-- Pengarang -->
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-[0.16em] text-slate-400">Pengarang</p>
                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $buku->pengarang }}
                        </p>
                    </div>

                    <!-- Penerbit -->
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-[0.16em] text-slate-400">Penerbit</p>
                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $buku->penerbit }}
                        </p>
                    </div>

                    <!-- Tahun -->
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-[0.16em] text-slate-400">Tahun</p>
                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $buku->tahun }}
                        </p>
                    </div>

                    <!-- Stok -->
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-[0.16em] text-slate-400">Stok</p>
                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $buku->stok }} buku
                        </p>
                    </div>
                </div>
                </div>

            </div>
        </div>
    </div>
@endsection
