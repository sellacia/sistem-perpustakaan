@extends('layouts.petugas.app')

@section('content')
    <h2 class="text-xl font-bold text-blue-600 mb-6">Daftar Buku</h2>

    <div class="bg-white p-6 rounded-xl shadow-md flex gap-6">

        <!-- COVER -->
        <div>
            @if ($buku->cover)
                <div class="w-40 aspect-[3/4] bg-gray-100 overflow-hidden rounded">
                    <img src="{{ $buku->cover ? asset('assets/images/' . $buku->cover) : 'https://via.placeholder.com/200x300' }}"
                        class="w-full h-full object-cover">
                </div>
            @else
                <div class="w-40 h-56 bg-gray-200 flex items-center justify-center rounded">
                    Tidak ada gambar
                </div>
            @endif
        </div>

        <!-- DETAIL -->
        <div class="flex-1 text-sm">

            <p><b>Kode Buku :</b> {{ $buku->kode_buku }}</p>
            <p><b>Pengarang :</b>
                <span class="text-blue-600">{{ $buku->pengarang }}</span>
            </p>
            <p><b>Penerbit :</b>
                <span class="text-blue-600">{{ $buku->penerbit }}</span>
            </p>
            <p><b>Tahun :</b>
                <span class="text-blue-600">{{ $buku->tahun }}</span>
            </p>

            <p class="mt-3"><b>Deskripsi :</b></p>
            <p class="text-gray-700 leading-relaxed">
                {{ $buku->deskripsi ?? 'Tidak ada deskripsi' }}
            </p>

            <!-- BUTTON -->
            <div class="mt-6 text-right">
                <a href="{{ route('petugas.buku.index') }}"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Kembali
                </a>
            </div>

        </div>

    </div>
@endsection
