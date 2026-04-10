@extends('layouts.anggota.app')

@section('content')
<div class="p-6">

    <!-- SEARCH -->
    <div class="bg-white p-4 rounded-xl shadow mb-6">
        <form method="GET" action="{{ route('anggota.buku') }}">
            <div class="flex items-center bg-gray-100 px-4 py-2 rounded-lg">
                <input type="text" name="search" placeholder="Cari Daftar Buku"
                    class="bg-transparent outline-none w-full" value="{{ request('search') }}">
            </div>
        </form>
    </div>

    <!-- GRID BUKU -->
    <div class="grid grid-cols-3 gap-6">

        @php $total = count($buku); @endphp

        @foreach ($buku as $item)

        <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition text-center">

            <!-- COVER -->
            <img src="{{ asset('storage/' . $item->gambar) }}" class="mx-auto h-48 object-cover mb-4 rounded">

            <!-- DETAIL -->
            <div class="text-left text-sm space-y-1">
                <p class="text-blue-600 font-semibold">Judul : {{ $item->judul }}</p>
                <p>Kode Buku : {{ $item->kode_buku }}</p>
                <p>Pengarang : {{ $item->pengarang }}</p>
                <p>Penerbit : {{ $item->penerbit }}</p>
                <p>Tahun : {{ $item->tahun }}</p>
                <p>Stok : {{ $item->stok }} Buku</p>

                <p>Status :
                    @if ($item->stok > 0)
                        <span class="text-green-600 font-semibold">Tersedia</span>
                    @else
                        <span class="text-red-500 font-semibold">Tidak Tersedia</span>
                    @endif
                </p>
            </div>

            <!-- BUTTON -->
            <div class="mt-4 flex justify-center gap-2">

                <!-- ✅ FIX DETAIL -->
                <a href="{{ route('anggota.buku.detail', $item->id) }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                    Detail
                </a>

                @if ($item->stok > 0)
                    <!-- ✅ FIX PINJAM DENGAN SWEETALERT2 -->
                    <form action="{{ route('anggota.pinjam', $item->id) }}" method="POST" class="inline form-pinjam">
                        @csrf
                        <button type="button" class="btn-pinjam bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded text-xs">
                            Pinjam Buku
                        </button>
                    </form>
                @else
                    <button disabled class="bg-gray-400 text-white px-3 py-1 rounded text-xs">
                        Tidak Tersedia
                    </button>
                @endif

            </div>

        </div>

        @endforeach

        @for ($i = $total; $i < 3; $i++)
            <div class="bg-white p-5 rounded-xl shadow opacity-40 text-center">
                <div class="h-48 bg-gray-200 rounded mb-4"></div>
                <p class="text-gray-400 text-sm">Belum ada buku</p>
            </div>
        @endfor

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const pinjamButtons = document.querySelectorAll('.btn-pinjam');
        pinjamButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.form-pinjam');
                
                Swal.fire({
                    title: 'Konfirmasi Peminjaman',
                    text: 'Apakah Anda yakin ingin meminjam buku ini? Data akan segera diteruskan ke petugas.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Pinjam',
                    cancelButtonText: 'Batal',
                    background: '#ffffff',
                    color: '#1e3a8a', // Dark blue text
                    confirmButtonColor: '#2563eb', // Blue-600
                    cancelButtonColor: '#9ca3af', // Gray-400
                    customClass: {
                        popup: 'rounded-2xl border border-blue-100 shadow-xl',
                        title: 'text-xl font-bold text-blue-700',
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
