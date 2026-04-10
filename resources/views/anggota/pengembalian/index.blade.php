@extends('layouts.anggota.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold text-blue-700 mb-6 tracking-tight">Pengembalian Buku</h2>

    @if ($pinjam->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-100 mt-4">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="book-check" class="w-10 h-10 text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Semua Beres!</h3>
            <p class="text-gray-500 mt-1">Kamu tidak memiliki buku pinjaman yang belum dikembalikan.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach ($pinjam as $item)
                @php
                    $wajib = \Carbon\Carbon::parse($item->tanggal_wajib_kembali);
                    $hariIni = \Carbon\Carbon::today();
                    $terlambat = $hariIni->gt($wajib) ? $hariIni->diffInDays($wajib) : 0;
                    $denda = $terlambat * 2000;
                @endphp

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow relative overflow-hidden">
                    
                    @if($terlambat > 0)
                        <!-- Indikator Keterlambatan -->
                        <div class="absolute top-0 right-0 bg-red-100 text-red-600 text-xs font-bold px-3 py-1 rounded-bl-xl border-b border-l border-red-200">
                            Terlambat {{ $terlambat }} Hari
                        </div>
                    @endif

                    <div class="flex gap-4">
                        <img src="{{ asset('storage/' . $item->buku->gambar) }}" class="w-24 h-36 object-cover rounded-lg shadow-sm border border-gray-200" alt="Buku">
                        <div class="flex-1 space-y-2">
                            <h3 class="text-lg font-bold text-gray-800 leading-tight">{{ $item->buku->judul }}</h3>
                            <p class="text-sm text-gray-600"><span class="font-medium">Pinjam:</span> {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</p>
                            <p class="text-sm text-gray-600"><span class="font-medium">Wajib Kembali:</span> <span class="{{ $terlambat > 0 ? 'text-red-600 font-bold' : '' }}">{{ $wajib->format('d M Y') }}</span></p>

                            @if($denda > 0)
                                <div class="mt-2 bg-yellow-50 px-3 py-1.5 rounded text-sm text-yellow-800 border border-yellow-200 inline-block font-semibold">
                                    Denda: Rp {{ number_format($denda, 0, ',', '.') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-5 flex justify-end">
                        <form action="{{ route('anggota.pengembalian.proses') }}" method="POST" class="form-kembali">
                            @csrf
                            <input type="hidden" name="pinjam_id" value="{{ $item->id }}">
                            <button type="button" class="btn-kembali bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg text-sm w-full transition shadow-sm border border-blue-600 focus:ring focus:ring-blue-200 flex items-center justify-center gap-2">
                                <i data-lucide="corner-down-left" class="w-4 h-4"></i> Kembalikan Buku
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const kembaliButtons = document.querySelectorAll('.btn-kembali');
        kembaliButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.form-kembali');
                
                Swal.fire({
                    title: 'Kembalikan Buku?',
                    text: 'Pastikan kondisi fisik buku masih sama baiknya seperti saat Anda meminjamnya.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kembalikan',
                    cancelButtonText: 'Batal',
                    background: '#ffffff',
                    color: '#1f2937', 
                    confirmButtonColor: '#2563eb', 
                    cancelButtonColor: '#9ca3af',
                    customClass: {
                        popup: 'rounded-2xl border border-gray-100 shadow-xl',
                        title: 'text-xl font-bold text-gray-800',
                        confirmButton: 'rounded-lg px-6 py-2 font-semibold',
                        cancelButton: 'rounded-lg px-6 py-2 font-semibold'
                    }
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
