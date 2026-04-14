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

    <!-- GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

        @foreach ($pinjam as $item)
            @php
                $wajib = \Carbon\Carbon::parse($item->tanggal_wajib_kembali);
                $hariIni = \Carbon\Carbon::today();
                $terlambat = $hariIni->gt($wajib) ? $hariIni->diffInDays($wajib) : 0;
                $denda = $terlambat * 2000;
            @endphp

            <!-- CARD -->
            <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-100 hover:shadow transition relative text-sm">

                @if ($terlambat > 0)
                    <div class="absolute top-0 right-0 bg-red-100 text-red-600 text-[10px] font-bold px-2 py-1 rounded-bl-lg">
                        Terlambat {{ $terlambat }} Hari
                    </div>
                @endif

                <!-- FLEX -->
                <div class="flex items-center gap-3">

                    <!-- COVER (LEBIH KECIL) -->
                    <div class="w-12 h-16 overflow-hidden rounded border flex-shrink-0">
                        <img src="{{ asset('assets/images/' . $item->buku->cover) }}"
                            class="w-full h-full object-cover">
                    </div>

                    <!-- TEXT -->
                    <div class="flex-1">
                        <h3 class="text-xs font-semibold text-gray-800 leading-tight">
                            {{ $item->buku->judul }}
                        </h3>

                        <p class="text-[11px] text-gray-500">
                            {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M') }}
                            •
                            <span class="{{ $terlambat > 0 ? 'text-red-600 font-semibold' : '' }}">
                                {{ $wajib->format('d M') }}
                            </span>
                        </p>

                        @if ($denda > 0)
                            <p class="text-[10px] text-yellow-700">
                                Rp {{ number_format($denda, 0, ',', '.') }}
                            </p>
                        @endif
                    </div>

                </div>

                <!-- BUTTON -->
                <div class="mt-3">
                    <form action="{{ route('anggota.pengembalian.proses') }}" method="POST" class="form-kembali">
                        @csrf
                        <input type="hidden" name="pinjam_id" value="{{ $item->id }}">
                        <button type="button"
                            class="btn-kembali w-full bg-blue-600 hover:bg-blue-700 text-white py-1.5 rounded-md text-xs flex items-center justify-center gap-1">
                            <i data-lucide="corner-down-left" class="w-3 h-3"></i>
                            Kembalikan
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
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#9ca3af'
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
