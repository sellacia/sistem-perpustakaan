@extends('layouts.kepala.app')

@section('content')
    <div class="p-6">

        <!-- TITLE -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-blue-700">Katalog Buku</h1>
        </div>

        <!-- SEARCH -->
        <div class="mb-6">
            <input type="text" placeholder="Cari Buku"
                class="w-full p-3 rounded-lg border focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <!-- CONTAINER -->
        <div class="bg-gray-100 p-6 rounded-2xl">

            <!-- GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ($buku as $item)
                    <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition flex flex-col">

                        <!-- COVER -->
                        <img src="{{ $item->cover ?? 'https://via.placeholder.com/150' }}"
                            class="h-52 w-full object-cover rounded-xl mb-4">

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
                            <p><b>Status:</b> {{ $item->status }}</p>
                        </div>

                        <!-- BUTTON DETAIL ONLY -->
                        <div class="mt-5">
                            <a href="/kepala/buku/{{ $item->id }}"
                                class="block text-center bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">
                                Lihat Detail
                            </a>
                        </div>

                    </div>
                @endforeach

            </div>

        </div>

    </div>
@endsection
