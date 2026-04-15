@extends('layouts.petugas.app')

@section('content')
<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-[32px] bg-gradient-to-r from-emerald-700 via-teal-700 to-cyan-700 px-6 py-8 text-white shadow-xl lg:flex-row lg:items-end lg:justify-between">
        <div class="max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-emerald-100">Proses Pengembalian</p>
            <h1 class="mt-3 text-3xl font-bold">Selesaikan pengembalian buku dengan konfirmasi yang jelas.</h1>
            <p class="mt-3 text-sm leading-7 text-emerald-50/90">Halaman ini membantu petugas memastikan buku yang sudah kembali benar-benar selesai diproses.</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm backdrop-blur-sm">
            <p class="text-xs uppercase tracking-[0.18em] text-emerald-100/70">Perlu Konfirmasi</p>
            <p class="mt-2 text-2xl font-bold">{{ $pinjam->where('status', 'dikembalikan')->count() }}</p>
        </div>
    </section>

    @if(session('success'))
    <div data-auto-dismiss class="transform rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm transition duration-300">
        <div class="flex items-center gap-3"><i class="fas fa-circle-check"></i><span>{{ session('success') }}</span></div>
    </div>
    @endif
    @if(session('error'))
    <div data-auto-dismiss class="transform rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm transition duration-300">
        <div class="flex items-center gap-3"><i class="fas fa-circle-exclamation"></i><span>{{ session('error') }}</span></div>
    </div>
    @endif

    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Daftar Pengembalian</h2>
                <p class="text-sm text-slate-500">Aksi konfirmasi menampilkan alert agar proses lebih aman.</p>
            </div>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $pinjam->count() }} data</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Anggota</th>
                        <th class="px-6 py-4">Buku</th>
                        <th class="px-6 py-4">Tgl Pinjam</th>
                        <th class="px-6 py-4">Batas Kembali</th>
                        <th class="px-6 py-4">Tgl Dikembalikan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Denda</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($pinjam as $item)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-6 py-4 text-slate-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $item->anggota->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ $item->buku->judul ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ \Carbon\Carbon::parse($item->tanggal_wajib_kembali)->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $item->display_tanggal_kembali ? \Carbon\Carbon::parse($item->display_tanggal_kembali)->format('d M Y') : '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $item->status == 'dikembalikan' ? 'border-amber-200 bg-amber-50 text-amber-700' : ($item->status == 'terlambat' ? 'border-rose-200 bg-rose-50 text-rose-700' : 'border-emerald-200 bg-emerald-50 text-emerald-700') }}">
                                {{ $item->status == 'dikembalikan' ? 'Menunggu Konfirmasi' : ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold {{ ($item->dendaData->jumlah_denda ?? 0) > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                            {{ ($item->dendaData->jumlah_denda ?? 0) > 0 ? 'Rp ' . number_format($item->dendaData->jumlah_denda, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($item->status == 'dikembalikan')
                                @if ($item->dendaData && $item->dendaData->status === 'belum_bayar')
                                <span class="inline-flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-700">
                                    <i class="fas fa-wallet"></i> Tunggu Lunas Denda
                                </span>
                                @else
                                <form action="{{ route('petugas.pengembalian.konfirmasi', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" data-confirm data-confirm-title="Konfirmasi pengembalian?" data-confirm-message="Pengembalian buku {{ $item->buku->judul ?? '-' }} atas nama {{ $item->anggota->name ?? '-' }} akan diselesaikan." class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-emerald-700">
                                        <i class="fas fa-check"></i> Konfirmasi
                                    </button>
                                </form>
                                @endif
                            @else
                            <span class="text-xs font-semibold text-slate-300">Tidak ada aksi</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-slate-400">Tidak ada data pengembalian yang perlu dikonfirmasi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
