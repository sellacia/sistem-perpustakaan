@extends('layouts.anggota.app')

@section('content')
    <div class="p-6">

        <div class="bg-white border-2 border-blue-500 rounded-xl p-6">

            <div class="flex gap-6">

                <img src="{{ $buku->cover ? asset('assets/images/' . $buku->cover) : 'https://via.placeholder.com/200x300' }}"
                    class="w-40 h-56 object-cover rounded">

                <div class="text-sm">
                    <p><b>Judul :</b> <span class="text-blue-600">{{ $buku->judul }}</span></p>
                    <p><b>Kode Buku :</b> {{ $buku->kode_buku }}</p>
                    <p><b>Pengarang :</b> <span class="text-blue-600">{{ $buku->pengarang }}</span></p>
                    <p><b>Penerbit :</b> <span class="text-blue-600">{{ $buku->penerbit }}</span></p>
                    <p><b>Tahun :</b> <span class="text-blue-600">{{ $buku->tahun }}</span></p>
                    <p><b>Stok :</b> <span class="text-blue-600">{{ $buku->stok }} Buku</span></p>

                    <p class="mt-4"><b>Status :</b>
                        @if ($buku->stok > 0)
                            <span class="text-green-600 font-semibold">Tersedia</span>
                        @else
                            <span class="text-red-500 font-semibold">Tidak Tersedia</span>
                        @endif
                    </p>

                    <p class="mt-4"><b>Deskripsi :</b></p>
                    <p class="mt-1 text-gray-700">
                        {{ $buku->deskripsi ?? 'Tidak ada deskripsi' }}
                    </p>

                    <!-- BUTTON PINJAM -->
                    <div class="mt-6 flex gap-2">
                        @if ($buku->stok > 0)
                            <form action="{{ route('anggota.pinjam', $buku->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded">
                                    Pinjam Buku
                                </button>
                            </form>
                        @else
                            <button disabled class="bg-gray-400 text-white px-4 py-2 rounded cursor-not-allowed">
                                Tidak Tersedia
                            </button>
                        @endif

                        <a href="{{ route('anggota.buku') }}"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Kembali
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
