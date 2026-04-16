@extends('layouts.kepala.app')

@section('content')
    <div class="space-y-6">

        <!-- HEADER LAPORAN -->
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-300">
            Laporan
        </p>

    </div>

    <!-- TOMBOL EXPORT PDF -->
    <a href="{{ route('kepala.laporan.export-pdf') }}"
        class="inline-flex items-center gap-2 rounded-2xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-700">
        <i class="fas fa-file-pdf"></i> Export PDF
    </a>

    </section>

    <!-- SECTION TABEL LAPORAN -->
    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">

        <!-- HEADER -->
        <div class="border-b border-slate-100 px-6 py-5">
            <h2 class="text-lg font-bold text-slate-900">
                Rekap Peminjaman
            </h2>
        </div>

        <!-- WRAPPER TABLE -->
        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <!-- HEADER TABLE -->
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Judul Buku</th>
                        <th class="px-6 py-4">Tanggal Pinjam</th>
                        <th class="px-6 py-4">Tanggal Kembali</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>

                <!-- BODY TABLE -->
                <tbody class="divide-y divide-slate-100">

                    <!-- LOOP DATA LAPORAN -->
                    @forelse($laporan as $i => $data)
                        <tr class="transition hover:bg-slate-50">

                            <!-- Nomor -->
                            <td class="px-6 py-4 text-slate-500">
                                {{ $i + 1 }}
                            </td>

                            <!-- Nama anggota -->
                            <td class="px-6 py-4 font-semibold text-slate-900">
                                {{ $data->anggota->name ?? ($data->user->name ?? '-') }}
                            </td>

                            <!-- Judul buku -->
                            <td class="px-6 py-4 text-slate-700">
                                {{ $data->buku->judul ?? '-' }}
                            </td>

                            <!-- Tanggal pinjam -->
                            <td class="px-6 py-4 text-slate-600">
                                {{ \Carbon\Carbon::parse($data->tanggal_pinjam)->format('d M Y') }}
                            </td>

                            <!-- Tanggal kembali -->
                            <td class="px-6 py-4 text-slate-600">

                                <!-- Jika sudah dikembalikan tampilkan tanggal -->
                                {{ $data->display_tanggal_kembali
                                    ? \Carbon\Carbon::parse($data->display_tanggal_kembali)->format('d M Y')
                                    : '-' }}
                            </td>

                            <!-- STATUS -->
                            <td class="px-6 py-4 text-center">

                                <!-- Badge status -->
                                <span
                                    class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold
                                    {{ $data->status == 'dipinjam'
                                        ? 'bg-amber-50 text-amber-700 border-amber-200'
                                        : 'bg-emerald-50 text-emerald-700 border-emerald-200' }}">

                                    {{ ucfirst($data->status) }}
                                </span>
                            </td>

                        </tr>

                    @empty
                        <!-- Jika tidak ada data -->
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                Belum ada data laporan.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </section>
    </div>
@endsection
