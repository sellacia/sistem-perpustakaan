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

        <!-- CONTAINER -->
        <div class="bg-gray-100 p-6 rounded-2xl">

            <!-- GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ($buku as $item)
                    <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition flex flex-col">

                        <!-- COVER -->
                        <div class="w-full aspect-[3/4] overflow-hidden rounded-xl mb-4">
                            <img src="{{ $item->cover ? asset('assets/images/' . $item->cover) : 'https://via.placeholder.com/300x500' }}"
                                class="w-full h-full object-cover">
                        </div>

                        <!-- INFO -->
                        <h3 class="text-blue-600 font-semibold text-base mb-2">
                            {{ $item->judul }}
                        </h3>

                        <div class="text-sm text-gray-700 space-y-1 flex-1">
                            <p><b>Kode:</b> {{ $item->kode_buku }}</p>
                            <p><b>Pengarang:</b> {{ $item->pengarang }}</p>
                            <p><b>Penerbit:</b> {{ $item->penerbit }}</p>
                            <p><b>Tahun:</b> {{ $item->tahun }}</p>
                            <p><b>Stok:</b> {{ $item->stok }} Buku</p>

                            <p><b>Status:</b>
                                @if ($item->stok > 0)
                                    <span class="text-green-600 font-semibold">Tersedia</span>
                                @else
                                    <span class="text-red-500 font-semibold">Tidak Tersedia</span>
                                @endif
                            </p>
                        </div>

                        <!-- BUTTON -->
                        <div class="flex gap-2 mt-5">

                            <a href="{{ route('anggota.buku.detail', $item->id) }}"
                                class="flex-1 text-center bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">
                                Selengkapnya
                            </a>

                            @if ($item->stok > 0)
                                <form action="{{ route('anggota.pinjam', $item->id) }}" method="POST"
                                    class="flex-1 form-pinjam">
                                    @csrf
                                    <button type="button"
                                        class="w-full btn-pinjam bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600">
                                        Pinjam
                                    </button>
                                </form>
                            @else
                                <button disabled class="flex-1 bg-gray-400 text-white py-2 rounded-lg">
                                    Tidak Tersedia
                                </button>
                            @endif

                        </div>

                    </div>
                @endforeach
                @php
                    $total = count($buku);
                @endphp
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
