@extends('layouts.anggota.app')

@section('content')
    <div class="p-6">

        <div class="bg-white border-2 border-blue-500 rounded-xl p-6">

            <div class="flex gap-6">

                <!-- GAMBAR -->
                <img src="{{ asset('storage/' . $buku->cover) }}" class="w-40 h-56 object-cover rounded">

                <!-- DETAIL -->
                <div class="text-sm">

                    <p><b>Kode Buku :</b> {{ $buku->kode_buku }}</p>
                    <p><b>Pengarang :</b> <span class="text-blue-600">{{ $buku->pengarang }}</span></p>
                    <p><b>Penerbit :</b> <span class="text-blue-600">{{ $buku->penerbit }}</span></p>
                    <p><b>Tahun :</b> <span class="text-blue-600">{{ $buku->tahun }}</span></p>

                    <p class="mt-4"><b>Deskripsi :</b></p>
                    <p class="mt-1 text-gray-700">
                        Novel ini menceritakan kisah Dewi Ayu, seorang perempuan cantik yang hidup di kota fiksi bernama
                        Halimunda...
                    </p>

                </div>

            </div>

            <!-- BUTTON -->
            <div class="mt-6 text-right">
                <a href="/buku" class="bg-blue-500 text-white px-4 py-2 rounded text-sm">
                    Kembali
                </a>
            </div>

        </div>

    </div>
@endsection
