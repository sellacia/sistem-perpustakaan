@extends('layouts.anggota.app')

@section('content')
<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-[32px] bg-gradient-to-r from-slate-900 via-indigo-900 to-sky-700 px-6 py-8 text-white shadow-xl lg:flex-row lg:items-end lg:justify-between">
        <div class="max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-sky-200">Riwayat Peminjaman</p>
            <h1 class="mt-3 text-3xl font-bold">Pantau semua pengajuan dan status pinjaman Anda.</h1>
            <p class="mt-3 text-sm leading-7 text-slate-200">Tampilan riwayat kini sejalan dengan dashboard petugas agar informasi lebih mudah dipahami.</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm backdrop-blur-sm"><p class="text-xs uppercase tracking-[0.18em] text-sky-100/70">Total Riwayat</p><p class="mt-2 text-2xl font-bold">{{ $peminjaman->count() }}</p></div>
    </section>
    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-5"><h2 class="text-lg font-bold text-slate-900">Daftar Peminjaman</h2><p class="text-sm text-slate-500">Status pinjaman akan terus diperbarui sesuai proses petugas.</p></div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                    <tr><th class="px-6 py-4">Buku</th><th class="px-6 py-4">Tgl Pinjam</th><th class="px-6 py-4">Batas Kembali</th><th class="px-6 py-4">Dikembalikan</th><th class="px-6 py-4 text-center">Status</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($peminjaman as $item)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-6 py-4"><div><p class="font-semibold text-slate-900">{{ $item->buku->judul ?? '-' }}</p><p class="text-xs text-slate-500">{{ $item->buku->pengarang ?? '-' }}</p></div></td>
                        <td class="px-6 py-4 text-slate-600">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ \Carbon\Carbon::parse($item->tanggal_wajib_kembali)->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $item->display_tanggal_kembali ? \Carbon\Carbon::parse($item->display_tanggal_kembali)->format('d M Y') : '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            @php $badgeMap = ['menunggu' => 'bg-amber-50 text-amber-700 border-amber-200','dipinjam' => 'bg-sky-50 text-sky-700 border-sky-200','dikembalikan' => 'bg-emerald-50 text-emerald-700 border-emerald-200','ditolak' => 'bg-rose-50 text-rose-700 border-rose-200','terlambat' => 'bg-rose-50 text-rose-700 border-rose-200','selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200']; @endphp
                            <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $badgeMap[$item->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">{{ ucfirst($item->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada riwayat peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
