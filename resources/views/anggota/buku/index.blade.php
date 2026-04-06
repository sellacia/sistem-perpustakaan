@extends('layouts.anggota.app')

@section('content')
    <div class="p-6">

        <!-- SEARCH -->
        <div class="bg-white p-4 rounded-xl shadow mb-6">
            <form method="GET" action="/buku">
                <div class="flex items-center bg-gray-100 px-4 py-2 rounded-lg">
                    <i data-lucide="search" class="w-5 h-5 text-gray-500 mr-2"></i>
                    <input type="text" name="search" placeholder="Cari Daftar Buku"
                        class="bg-transparent outline-none w-full" value="{{ request('search') }}">
                </div>
            </form>
        </div>

        <!-- GRID BUKU -->
        <div class="grid grid-cols-3 gap-6">

            @php
                $total = count($buku);
            @endphp

            @foreach ($buku as $item)
                <div class="bg-white p-5 rounded-xl shadow text-center">

                    <!-- COVER -->
                    <img src="{{ asset('storage/' . $item->gambar) }}" class="mx-auto h-48 object-cover mb-4 rounded">

                    <!-- DETAIL -->
                    <div class="text-left text-sm">
                        <p class="text-blue-600 font-semibold">Judul : {{ $item->judul }}</p>
                        <p>Kode Buku : {{ $item->kode_buku }}</p>
                        <p>Pengarang : {{ $item->pengarang }}</p>
                        <p>Penerbit : {{ $item->penerbit }}</p>
                        <p>Tahun : {{ $item->tahun }}</p>
                        <p>Stok : {{ $item->stok }} Buku</p>
                        <p>Status :
                            <span class="{{ $item->stok > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $item->stok > 0 ? 'Tersedia' : 'Dipinjam' }}
                            </span>
                        </p>
                    </div>

                    <!-- BUTTON -->
                    <div class="mt-4 flex justify-center gap-2">
                        <a href="/buku/{{ $item->id }}" class="bg-blue-500 text-white px-3 py-1 rounded text-xs">
                            Detail
                        </a>

                        <a href="{{ route('buku.formPinjam', $item->id) }}"
                            class="bg-orange-500 text-white px-3 py-1 rounded text-xs">
                            Pinjam Buku
                        </a>
                    </div>

                </div>
            @endforeach

            {{-- TAMBAHAN BIAR SELALU 3 --}}
            @for ($i = $total; $i < 3; $i++)
                <div class="bg-white p-5 rounded-xl shadow opacity-40 text-center">
                    <div class="h-48 bg-gray-200 rounded mb-4"></div>
                    <p class="text-gray-400 text-sm">Belum ada buku</p>
                </div>
            @endfor

        </div>

    </div>
@endsection
