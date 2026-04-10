@extends('layouts.petugas.app')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-blue-700 tracking-tight">Manajemen Denda</h2>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-100">
                        <th class="py-4 px-6 font-semibold">Anggota</th>
                        <th class="py-4 px-6 font-semibold">Buku</th>
                        <th class="py-4 px-6 font-semibold">Keterangan</th>
                        <th class="py-4 px-6 font-semibold">Jumlah Denda</th>
                        <th class="py-4 px-6 font-semibold">Status</th>
                        <th class="py-4 px-6 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($data as $d)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-6 font-medium text-gray-800">
                            {{ $d->peminjaman->anggota->name ?? ($d->peminjaman->nama ?? '-') }}
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-600">
                            {{ $d->peminjaman->buku->judul ?? '-' }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="bg-red-50 text-red-600 px-3 py-1 rounded-full text-xs font-bold">
                                Terlambat {{ $d->terlambat }} Hari
                            </span>
                        </td>
                        <td class="py-4 px-6 font-bold text-gray-800">
                            Rp {{ number_format($d->jumlah_denda, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-6">
                            @if ($d->status == 'belum_bayar')
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Belum Bayar
                                </span>
                            @else
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Lunas
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            @if ($d->status == 'belum_bayar')
                                <form action="{{ route('petugas.denda.bayar', $d->id) }}" method="GET" class="form-konfirmasi">
                                    <button type="button" class="btn-konfirmasi bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-1.5 rounded-lg text-xs transition shadow-sm">
                                        Konfirmasi Lunas
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400 text-xs italic font-semibold text-green-600">Terbayar Lunas</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-gray-400">
                            <div class="flex flex-col items-center">
                                <i data-lucide="info" class="w-12 h-12 mb-2 opacity-20"></i>
                                <p>Tidak ada data denda yang tercatat.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const konfirmButtons = document.querySelectorAll('.btn-konfirmasi');
        konfirmButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.form-konfirmasi');
                
                Swal.fire({
                    title: 'Konfirmasi Pembayaran?',
                    text: 'Pastikan anggota benar-benar telah menyerahkan uang denda!',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Sudah Bayar',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#16a34a', 
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
