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

    <!-- SECTION RIWAYAT -->
    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">

        <!-- HEADER -->
        <div class="border-b border-slate-100 px-6 py-5">
            <h2 class="text-lg font-bold text-slate-900">Daftar Riwayat</h2>
        </div>

        <!-- WRAPPER TABLE -->
        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <!-- HEADER TABLE -->
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Buku</th>
                        <th class="px-6 py-4">Pinjam / Batas</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Kondisi</th>
                        <th class="px-6 py-4">Denda</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <!-- BODY TABLE -->
                <tbody class="divide-y divide-slate-100">

                    <!-- LOOP DATA -->
                    @forelse ($data as $item)
                        <tr class="transition hover:bg-slate-50">

                            <!-- DATA BUKU -->
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-900">
                                    {{ $item->buku->judul ?? '-' }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    ID Pinjam #{{ $item->id }}
                                </p>
                            </td>

                            <!-- TANGGAL -->
                            <td class="px-6 py-4 text-slate-600">

                                <!-- Tanggal pinjam -->
                                <p>
                                    {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}
                                </p>

                                <!-- Batas kembali (merah jika terlambat) -->
                                <p class="mt-1 font-medium {{ $item->status == 'terlambat' ? 'text-rose-600' : '' }}">
                                    {{ \Carbon\Carbon::parse($item->tanggal_wajib_kembali)->format('d M Y') }}
                                </p>
                            </td>

                            <!-- STATUS -->
                            <td class="px-6 py-4 text-center">

                                <!-- Mapping warna status -->
                                @php
                                    $badgeMap = [
                                        'terlambat' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'dipinjam' => 'bg-sky-50 text-sky-700 border-sky-200',
                                        'dikembalikan' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'ditolak' => 'bg-slate-100 text-slate-600 border-slate-200',
                                    ];
                                @endphp

                                <!-- Badge status -->
                                <span
                                    class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold
                                    {{ $badgeMap[$item->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>

                            {{-- KONDISI --}}
                            <td class="px-6 py-4 text-center">
                                @if($item->kondisi === 'baik')
                                    <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold border-emerald-200 bg-emerald-50 text-emerald-700">Baik</span>
                                @elseif($item->kondisi === 'rusak')
                                    <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold border-amber-200 bg-amber-50 text-amber-700">Rusak</span>
                                @elseif($item->kondisi === 'hilang')
                                    <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold border-rose-200 bg-rose-50 text-rose-700">Hilang</span>
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>

                            {{-- DENDA --}}
                            <td class="px-6 py-4">
                                @if ($item->dendaData)
                                    <p class="font-semibold
                                        {{ $item->dendaData->status === 'sudah_bayar' ? 'text-emerald-600' : 'text-rose-600' }}">
                                        Rp {{ number_format($item->dendaData->jumlah_denda, 0, ',', '.') }}
                                    </p>
                                    @if($item->kondisi && in_array($item->kondisi, ['rusak','hilang']))
                                        <p class="text-xs text-slate-400 mt-1">
                                            Termasuk denda
                                            {{ $item->kondisi === 'rusak' ? 'buku rusak (+Rp50.000)' : 'buku hilang (+Rp100.000)' }}
                                        </p>
                                    @endif
                                    <p class="mt-1 text-xs font-semibold
                                        {{ $item->dendaData->status === 'belum_bayar' ? 'text-rose-600'
                                            : ($item->dendaData->status === 'menunggu_konfirmasi' ? 'text-amber-600' : 'text-emerald-600') }}">
                                        {{ $item->dendaData->status === 'belum_bayar' ? 'Belum dibayar'
                                            : ($item->dendaData->status === 'menunggu_konfirmasi' ? 'Menunggu konfirmasi petugas' : 'Sudah dibayar') }}
                                    </p>
                                @else
                                    <span class="text-slate-400">Tidak ada</span>
                                @endif
                            </td>

                            <!-- AKSI -->
                            <td class="px-6 py-4 text-center">

                                <!-- Jika belum bayar -->
                                @if ($item->dendaData && $item->dendaData->status == 'belum_bayar')
                                    <!-- Form konfirmasi bayar -->
                                    <form action="{{ route('anggota.riwayat.bayar', $item->dendaData->id) }}"
                                        method="POST" class="inline">
                                        @csrf

                                        <button type="submit" data-confirm
                                            data-confirm-title="Ajukan konfirmasi pembayaran?"
                                            data-confirm-message="Setelah Anda membayar, petugas perlu memverifikasi supaya denda dinyatakan lunas."
                                            class="rounded-2xl bg-rose-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-rose-700">
                                            Saya Sudah Bayar
                                        </button>
                                    </form>

                                    <!-- Jika menunggu konfirmasi -->
                                @elseif ($item->dendaData && $item->dendaData->status == 'menunggu_konfirmasi')
                                    <span
                                        class="inline-flex rounded-2xl border border-amber-200 bg-amber-50 px-4 py-2 text-xs font-semibold text-amber-700">
                                        Menunggu Konfirmasi
                                    </span>

                                    <!-- Jika tidak ada aksi -->
                                @else
                                    <span class="text-xs font-semibold text-slate-300">
                                        Tidak ada aksi
                                    </span>
                                @endif

                            </td>

                        </tr>

                    @empty
                        <!-- Jika tidak ada data -->
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                Belum ada riwayat peminjaman.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </section>
    </div>
@endsection
