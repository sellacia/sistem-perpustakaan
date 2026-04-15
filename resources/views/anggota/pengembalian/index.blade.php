@extends('layouts.anggota.app')

@section('content')
<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-[32px] bg-gradient-to-r from-emerald-700 via-teal-700 to-cyan-700 px-6 py-8 text-white shadow-xl lg:flex-row lg:items-end lg:justify-between">
        <div class="max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-emerald-100">Pengembalian Buku</p>
            <h1 class="mt-3 text-3xl font-bold">Ajukan pengembalian buku dengan langkah yang lebih jelas.</h1>
            <p class="mt-3 text-sm leading-7 text-emerald-50/90">Cek jatuh tempo, estimasi denda, lalu kirim pengajuan pengembalian langsung dari halaman ini.</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm backdrop-blur-sm"><p class="text-xs uppercase tracking-[0.18em] text-emerald-100/70">Perlu Dikembalikan</p><p class="mt-2 text-2xl font-bold">{{ $pinjam->count() }}</p></div>
    </section>
    @if(session('success'))<div data-auto-dismiss class="transform rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm transition duration-300"><div class="flex items-center gap-3"><i class="fas fa-circle-check"></i><span>{{ session('success') }}</span></div></div>@endif
    @if(session('error'))<div data-auto-dismiss class="transform rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm transition duration-300"><div class="flex items-center gap-3"><i class="fas fa-circle-exclamation"></i><span>{{ session('error') }}</span></div></div>@endif
    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($pinjam as $item)
            @php $wajib = \Carbon\Carbon::parse($item->tanggal_wajib_kembali); $hariIni = \Carbon\Carbon::today(); $terlambat = $hariIni->gt($wajib) ? $hariIni->diffInDays($wajib) : 0; $denda = $terlambat * 2000; @endphp
            <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-start justify-between gap-3">
                    <div><p class="text-lg font-bold text-slate-900">{{ $item->buku->judul }}</p><p class="mt-1 text-sm text-slate-500">Dipinjam {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</p></div>
                    <span class="rounded-full {{ $terlambat > 0 ? 'border-rose-200 bg-rose-50 text-rose-700' : 'border-emerald-200 bg-emerald-50 text-emerald-700' }} border px-3 py-1 text-xs font-semibold">{{ $terlambat > 0 ? 'Terlambat '.$terlambat.' hari' : 'Masih aman' }}</span>
                </div>
                <div class="mt-5 grid gap-3 text-sm">
                    <div class="rounded-2xl bg-slate-50 p-4"><p class="text-xs uppercase tracking-[0.16em] text-slate-400">Batas Kembali</p><p class="mt-2 font-semibold text-slate-900">{{ $wajib->format('d M Y') }}</p></div>
                    <div class="rounded-2xl bg-slate-50 p-4"><p class="text-xs uppercase tracking-[0.16em] text-slate-400">Estimasi Denda</p><p class="mt-2 font-semibold {{ $denda > 0 ? 'text-rose-600' : 'text-slate-900' }}">{{ $denda > 0 ? 'Rp '.number_format($denda,0,',','.') : 'Tidak ada' }}</p></div>
                </div>
                <form action="{{ route('anggota.pengembalian.proses') }}" method="POST" class="mt-5">
                    @csrf
                    <input type="hidden" name="pinjam_id" value="{{ $item->id }}">
                    <button type="submit" data-confirm data-confirm-title="Ajukan pengembalian?" data-confirm-message="Pengajuan pengembalian untuk buku {{ $item->buku->judul }} akan dikirim ke petugas." class="w-full rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">Kembalikan Buku</button>
                </form>
            </article>
        @empty
            <div class="col-span-full rounded-3xl border border-slate-200 bg-white px-6 py-16 text-center text-slate-400 shadow-sm">Anda tidak memiliki buku aktif untuk dikembalikan.</div>
        @endforelse
    </section>
</div>
@endsection
