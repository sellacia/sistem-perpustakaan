@extends('layouts.anggota.app')

@section('content')
<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-[32px] bg-gradient-to-r from-slate-900 via-slate-800 to-slate-700 px-6 py-8 text-white shadow-xl lg:flex-row lg:items-end lg:justify-between">
        <div class="max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-300">Riwayat & Denda</p>
            <h1 class="mt-3 text-3xl font-bold">Lihat status transaksi dan pembayaran denda Anda.</h1>
            <p class="mt-3 text-sm leading-7 text-slate-300">Halaman ini disusun ulang agar status, nominal denda, dan aksi pembayaran lebih mudah dipantau.</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm backdrop-blur-sm"><p class="text-xs uppercase tracking-[0.18em] text-slate-300/70">Total Catatan</p><p class="mt-2 text-2xl font-bold">{{ $data->count() }}</p></div>
    </section>
    @if(session('success'))<div data-auto-dismiss class="transform rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm transition duration-300"><div class="flex items-center gap-3"><i class="fas fa-circle-check"></i><span>{{ session('success') }}</span></div></div>@endif
    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-5"><h2 class="text-lg font-bold text-slate-900">Daftar Riwayat</h2><p class="text-sm text-slate-500">Jika ada denda belum dibayar, kirim permintaan konfirmasi pembayaran dari sini.</p></div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-500"><tr><th class="px-6 py-4">Buku</th><th class="px-6 py-4">Pinjam / Batas</th><th class="px-6 py-4 text-center">Status</th><th class="px-6 py-4">Denda</th><th class="px-6 py-4 text-center">Aksi</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($data as $item)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-6 py-4"><p class="font-semibold text-slate-900">{{ $item->buku->judul ?? '-' }}</p><p class="text-xs text-slate-500">ID Pinjam #{{ $item->id }}</p></td>
                        <td class="px-6 py-4 text-slate-600"><p>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</p><p class="mt-1 font-medium {{ $item->status == 'terlambat' ? 'text-rose-600' : '' }}">{{ \Carbon\Carbon::parse($item->tanggal_wajib_kembali)->format('d M Y') }}</p></td>
                        <td class="px-6 py-4 text-center">@php $badgeMap = ['terlambat' => 'bg-rose-50 text-rose-700 border-rose-200','dipinjam' => 'bg-sky-50 text-sky-700 border-sky-200','dikembalikan' => 'bg-amber-50 text-amber-700 border-amber-200','selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200','menunggu' => 'bg-amber-50 text-amber-700 border-amber-200','ditolak' => 'bg-slate-100 text-slate-600 border-slate-200']; @endphp <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $badgeMap[$item->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">{{ ucfirst($item->status) }}</span></td>
                        <td class="px-6 py-4">
                            @if($item->dendaData)
                                <p class="font-semibold text-slate-900">Rp {{ number_format($item->dendaData->jumlah_denda, 0, ',', '.') }}</p>
                                <p class="mt-1 text-xs font-semibold {{ $item->dendaData->status == 'belum_bayar' ? 'text-rose-600' : ($item->dendaData->status == 'menunggu_konfirmasi' ? 'text-amber-600' : 'text-emerald-600') }}">{{ $item->dendaData->status == 'belum_bayar' ? 'Belum dibayar' : ($item->dendaData->status == 'menunggu_konfirmasi' ? 'Menunggu konfirmasi petugas' : 'Sudah dibayar') }}</p>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($item->dendaData && $item->dendaData->status == 'belum_bayar')
                                <form action="{{ route('anggota.riwayat.bayar', $item->dendaData->id) }}" method="POST" class="inline">@csrf
                                    <button type="submit" data-confirm data-confirm-title="Ajukan konfirmasi pembayaran?" data-confirm-message="Setelah Anda membayar, petugas perlu memverifikasi supaya denda dinyatakan lunas." class="rounded-2xl bg-rose-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-rose-700">Saya Sudah Bayar</button>
                                </form>
                            @elseif ($item->dendaData && $item->dendaData->status == 'menunggu_konfirmasi')
                                <span class="inline-flex rounded-2xl border border-amber-200 bg-amber-50 px-4 py-2 text-xs font-semibold text-amber-700">Menunggu Konfirmasi</span>
                            @else
                                <span class="text-xs font-semibold text-slate-300">Tidak ada aksi</span>
                            @endif
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
