@extends('layouts.anggota.app')

@section('content')
        </section>
        <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-bold text-slate-900">Daftar Peminjaman</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Buku</th>
                            <th class="px-6 py-4">Tgl Pinjam</th>
                            <th class="px-6 py-4">Batas Kembali</th>
                            <th class="px-6 py-4">Dikembalikan</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($peminjaman as $item)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $item->buku->judul ?? '-' }}</p>
                                        <p class="text-xs text-slate-500">{{ $item->buku->pengarang ?? '-' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ \Carbon\Carbon::parse($item->tanggal_wajib_kembali)->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $item->display_tanggal_kembali ? \Carbon\Carbon::parse($item->display_tanggal_kembali)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php $badgeMap = ['menunggu' => 'bg-amber-50 text-amber-700 border-amber-200','dipinjam' => 'bg-sky-50 text-sky-700 border-sky-200','dikembalikan' => 'bg-emerald-50 text-emerald-700 border-emerald-200','ditolak' => 'bg-rose-50 text-rose-700 border-rose-200','terlambat' => 'bg-rose-50 text-rose-700 border-rose-200','selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200']; @endphp
                                    <span
                                        class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $badgeMap[$item->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">{{ ucfirst($item->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada riwayat
                                    peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
