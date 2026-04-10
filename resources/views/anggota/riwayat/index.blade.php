@extends('layouts.anggota.app')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-blue-700 tracking-tight">Riwayat Peminjaman & Denda</h2>
    </div>

    @if ($data->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-100">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="inbox" class="w-10 h-10 text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Belum ada riwayat!</h3>
            <p class="text-gray-500 mt-1">Kamu belum pernah melakukan aktivitas peminjaman.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-100">
                            <th class="py-4 px-6 font-semibold">Buku</th>
                            <th class="py-4 px-6 font-semibold">Tgl Pinjam / Batas</th>
                            <th class="py-4 px-6 font-semibold">Status</th>
                            <th class="py-4 px-6 font-semibold">Denda</th>
                            <th class="py-4 px-6 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($data as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-800 text-sm">{{ $item->buku->judul ?? '-' }}</p>
                                        <p class="text-xs text-gray-400">ID Pinjam: #{{ $item->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-sm">
                                <p class="text-gray-700"><span class="text-gray-400">Pinjam:</span> {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</p>
                                <p class="text-gray-700 font-medium @if($item->status == 'terlambat') text-red-600 @endif">
                                    <span class="text-gray-400">Batas:</span> {{ \Carbon\Carbon::parse($item->tanggal_wajib_kembali)->format('d M Y') }}
                                </p>
                            </td>
                            <td class="py-4 px-6">
                                @if ($item->status == 'terlambat')
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold animate-pulse">
                                        <i data-lucide="alert-triangle" class="w-3 h-3 inline-block mr-1"></i> TELAT - BAYAR DENDA!
                                    </span>
                                @elseif ($item->status == 'dipinjam')
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        <i data-lucide="book" class="w-3 h-3 inline-block mr-1"></i> Sedang Dipinjam
                                    </span>
                                @elseif ($item->status == 'dikembalikan')
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        <i data-lucide="clock" class="w-3 h-3 inline-block mr-1"></i> Menunggu Konfirmasi Balik
                                    </span>
                                @elseif ($item->status == 'selesai')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        <i data-lucide="check-circle" class="w-3 h-3 inline-block mr-1"></i> Selesai
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold uppercase">
                                        {{ $item->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if($item->denda)
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-800">Rp {{ number_format($item->denda->jumlah_denda, 0, ',', '.') }}</span>
                                        <span class="text-[10px] uppercase font-bold {{ $item->denda->status == 'belum_bayar' ? 'text-red-500' : 'text-green-600' }}">
                                            {{ $item->denda->status == 'sudah_bayar' ? '✅ SUDAH LUNAS' : '⚠️ BELUM BAYAR' }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if ($item->denda && $item->denda->status == 'belum_bayar')
                                    <form action="{{ route('anggota.riwayat.bayar', $item->denda->id) }}" method="POST" class="form-bayar">
                                        @csrf
                                        <button type="button" class="btn-bayar bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded-lg text-xs transition shadow-sm flex items-center gap-2">
                                            <i data-lucide="credit-card" class="w-3 h-3"></i> Bayar Denda
                                        </button>
                                    </form>
                                @elseif($item->status == 'terlambat')
                                    <p class="text-[10px] text-gray-500 italic">Harap segera kembalikan buku!</p>
                                @else
                                    <i data-lucide="minus" class="text-gray-300"></i>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const bayarButtons = document.querySelectorAll('.btn-bayar');
        bayarButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.form-bayar');
                
                Swal.fire({
                    title: 'Bayar Denda?',
                    text: 'Simulasi pembayaran denda. Setelah membayar, tunggu konfirmasi petugas.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Bayar Sekarang',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc2626', 
                    cancelButtonColor: '#6b7280',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection

