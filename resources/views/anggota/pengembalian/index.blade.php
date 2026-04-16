@extends('layouts.anggota.app')

@section('content')
    <!-- ALERT SUCCESS -->
    @if (session('success'))
        <div data-auto-dismiss
            class="transform rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm transition duration-300">
            <div class="flex items-center gap-3">
                <i class="fas fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- ALERT ERROR -->
    @if (session('error'))
        <div data-auto-dismiss
            class="transform rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm transition duration-300">
            <div class="flex items-center gap-3">
                <i class="fas fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- LIST BUKU YANG SEDANG DIPINJAM -->
    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">

        <!-- LOOP DATA PINJAMAN -->
        @forelse ($pinjam as $item)
            @php
                // Ambil tanggal wajib kembali
                $wajib = \Carbon\Carbon::parse($item->tanggal_wajib_kembali);

                // Ambil tanggal hari ini
                $hariIni = \Carbon\Carbon::today();

                // Hitung keterlambatan (jika lewat dari batas)
                $terlambat = $hariIni->gt($wajib) ? $hariIni->diffInDays($wajib) : 0;

                // Hitung denda (Rp 2000 per hari)
                $denda = $terlambat * 2000;
            @endphp

            <!-- CARD PINJAMAN -->
            <article
                class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">

                <!-- HEADER -->
                <div class="flex items-start justify-between gap-3">

                    <!-- INFO BUKU -->
                    <div>
                        <!-- Judul buku -->
                        <p class="text-lg font-bold text-slate-900">
                            {{ $item->buku->judul }}
                        </p>

                        <!-- Tanggal pinjam -->
                        <p class="mt-1 text-sm text-slate-500">
                            Dipinjam {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}
                        </p>
                    </div>

                    <!-- STATUS TERLAMBAT / AMAN -->
                    <span
                        class="rounded-full {{ $terlambat > 0 ? 'border-rose-200 bg-rose-50 text-rose-700' : 'border-emerald-200 bg-emerald-50 text-emerald-700' }} border px-3 py-1 text-xs font-semibold">

                        <!-- Jika terlambat tampilkan jumlah hari -->
                        {{ $terlambat > 0 ? 'Terlambat ' . $terlambat . ' hari' : 'Masih aman' }}
                    </span>
                </div>

                <!-- DETAIL -->
                <div class="mt-5 grid gap-3 text-sm">

                    <!-- BATAS KEMBALI -->
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-[0.16em] text-slate-400">Batas Kembali</p>
                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $wajib->format('d M Y') }}
                        </p>
                    </div>

                    <!-- ESTIMASI DENDA -->
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-[0.16em] text-slate-400">Estimasi Denda</p>

                        <!-- Jika ada denda tampilkan, jika tidak tampilkan 'Tidak ada' -->
                        <p class="mt-2 font-semibold {{ $denda > 0 ? 'text-rose-600' : 'text-slate-900' }}">
                            {{ $denda > 0 ? 'Rp ' . number_format($denda, 0, ',', '.') : 'Tidak ada' }}
                        </p>
                    </div>
                </div>

                <!-- FORM PENGEMBALIAN -->
                <form action="{{ route('anggota.pengembalian.proses') }}" method="POST" class="mt-5">
                    @csrf

                    <!-- Kirim ID pinjaman -->
                    <input type="hidden" name="pinjam_id" value="{{ $item->id }}">

                    <!-- Tombol kirim pengembalian -->
                    <button type="submit" data-confirm data-confirm-title="Ajukan pengembalian?"
                        data-confirm-message="Pengajuan pengembalian untuk buku {{ $item->buku->judul }} akan dikirim ke petugas."
                        class="w-full rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">
                        Kembalikan Buku
                    </button>
                </form>

            </article>

        @empty
            <!-- Jika tidak ada buku -->
            <div
                class="col-span-full rounded-3xl border border-slate-200 bg-white px-6 py-16 text-center text-slate-400 shadow-sm">
                Anda tidak memiliki buku aktif untuk dikembalikan.
            </div>
        @endforelse

    </section>
    </div>
@endsection
