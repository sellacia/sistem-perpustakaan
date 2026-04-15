@extends('layouts.petugas.app')

@section('content')
<div class="space-y-6">
    @php
        $statusCount = $data->groupBy('status');
    @endphp

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
                <h2 class="text-lg font-bold text-slate-900">Daftar Peminjaman</h2>
            </div>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $data->count() }} transaksi</span>
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
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($data as $item)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-6 py-4 text-slate-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-slate-900">{{ $item->anggota->name ?? '-' }}</p>
                            <p class="text-xs text-slate-500">{{ $item->anggota->username ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-700">{{ $item->buku->judul ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $item->tanggal_wajib_kembali ? \Carbon\Carbon::parse($item->tanggal_wajib_kembali)->format('d M Y') : '-' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $item->display_tanggal_kembali ? \Carbon\Carbon::parse($item->display_tanggal_kembali)->format('d M Y') : '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $badgeMap = [
                                    'menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'dipinjam' => 'bg-sky-50 text-sky-700 border-sky-200',
                                    'terlambat' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    'dikembalikan' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'ditolak' => 'bg-slate-100 text-slate-600 border-slate-200',
                                ];
                            @endphp
                            <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $badgeMap[$item->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">{{ ucfirst($item->status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($item->status == 'menunggu')
                            <div class="flex flex-wrap items-center justify-center gap-2">
                                <a href="{{ route('petugas.peminjaman.setujui', $item->id) }}" data-confirm data-confirm-title="Setujui peminjaman?" data-confirm-message="Peminjaman untuk {{ $item->anggota->name ?? 'anggota' }} akan disetujui dan deadline kembali otomatis dibuat 7 hari." class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-emerald-700">
                                    <i class="fas fa-check"></i> Setujui
                                </a>
                                <a href="{{ route('petugas.peminjaman.tolak', $item->id) }}" data-confirm data-confirm-title="Tolak peminjaman?" data-confirm-message="Pengajuan ini akan ditolak dan stok buku akan dikembalikan ke sistem." class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-rose-700">
                                    <i class="fas fa-xmark"></i> Tolak
                                </a>
                            </div>
                            @elseif (in_array($item->status, ['dipinjam', 'terlambat']))
                            <a href="{{ route('petugas.peminjaman.kembalikan', $item->id) }}" data-confirm data-confirm-title="Proses pengembalian?" data-confirm-message="Sistem akan mencatat pengembalian buku ini dan menghitung denda jika ada keterlambatan." class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-indigo-700">
                                <i class="fas fa-rotate-left"></i> Kembalikan
                            </a>
                            @elseif ($item->status == 'dikembalikan')
                            <a href="{{ route('petugas.pengembalian') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                Lanjut Konfirmasi
                            </a>
                            @else
                            <span class="text-xs font-semibold text-slate-300">Tidak ada aksi</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-400">Belum ada data peminjaman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
