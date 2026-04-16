@extends('layouts.petugas.app')

@section('content')
    <div class="space-y-6">
        @php
            $totalDenda = $data->sum(fn($d) => $d->dendaData->jumlah_denda ?? 0);
            $selesai = $data->whereIn('status', ['selesai', 'dikembalikan'])->count();
            $terlambat = $data->where('status', 'terlambat')->count();
            $aktif = $data->whereIn('status', ['dipinjam', 'menunggu'])->count();
        @endphp

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900">Filter Periode</h2>
            <form method="GET" action="{{ route('petugas.laporan') }}" class="mt-5 flex flex-wrap items-end gap-4">
                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Tanggal
                        Mulai</label>
                    <input type="date" name="mulai" value="{{ request('mulai') }}"
                        class="rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-sky-300 focus:ring-4 focus:ring-sky-100">
                </div>
                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Tanggal
                        Selesai</label>
                    <input type="date" name="sampai" value="{{ request('sampai') }}"
                        class="rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-sky-300 focus:ring-4 focus:ring-sky-100">
                </div>
                <div class="flex gap-3">
                    <button type="submit"
                        class="rounded-2xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">Tampilkan</button>
                    @if (request('mulai') || request('sampai'))
                        <a href="{{ route('petugas.laporan') }}"
                            class="rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Reset</a>
                    @endif
                </div>
            </form>
        </section>

        @if (request('mulai') && request('sampai'))
            <div class="rounded-2xl border border-sky-200 bg-sky-50 px-5 py-4 text-sm text-sky-700 shadow-sm">
                Menampilkan data dari {{ \Carbon\Carbon::parse(request('mulai'))->isoFormat('D MMMM Y') }} sampai
                {{ \Carbon\Carbon::parse(request('sampai'))->isoFormat('D MMMM Y') }}.
            </div>
        @endif

        <section class="grid gap-4 md:grid-cols-4">
            <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Total Transaksi</p>
                <p class="mt-4 text-3xl font-bold text-slate-900">{{ $data->count() }}</p>
            </article>
            <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Aktif</p>
                <p class="mt-4 text-3xl font-bold text-sky-600">{{ $aktif }}</p>
            </article>
            <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Terlambat</p>
                <p class="mt-4 text-3xl font-bold text-rose-600">{{ $terlambat }}</p>
            </article>
            <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Total Denda</p>
                <p class="mt-4 text-2xl font-bold text-amber-600">Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
            </article>
        </section>

        <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-bold text-slate-900">Detail Laporan</h2>
                <a href="{{ route('petugas.laporan.export-pdf') }}?mulai={{ request('mulai') }}&sampai={{ request('sampai') }}"
                    target="_blank"
                    class="inline-flex items-center gap-2 rounded-2xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-700">
                    <i class="fas fa-file-pdf"></i> Cetak PDF
                </a>

            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Anggota</th>
                            <th class="px-6 py-4">Buku</th>
                            <th class="px-6 py-4">Tgl Pinjam</th>
                            <th class="px-6 py-4">Wajib Kembali</th>
                            <th class="px-6 py-4">Tgl Kembali</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($data as $d)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-6 py-4 text-slate-500">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ $d->anggota->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $d->buku->judul ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ \Carbon\Carbon::parse($d->tanggal_pinjam)->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $d->tanggal_wajib_kembali ? \Carbon\Carbon::parse($d->tanggal_wajib_kembali)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $d->display_tanggal_kembali ? \Carbon\Carbon::parse($d->display_tanggal_kembali)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $badgeMap = [
                                            'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'dikembalikan' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                            'terlambat' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            'dipinjam' => 'bg-sky-50 text-sky-700 border-sky-200',
                                            'menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'ditolak' => 'bg-slate-100 text-slate-600 border-slate-200',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $badgeMap[$d->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">{{ ucfirst($d->status) }}</span>
                                </td>
                                <td
                                    class="px-6 py-4 text-right font-semibold {{ ($d->dendaData->jumlah_denda ?? 0) > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                                    {{ ($d->dendaData->jumlah_denda ?? 0) > 0 ? 'Rp ' . number_format($d->dendaData->jumlah_denda, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-400">Tidak ada data laporan
                                    untuk periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
