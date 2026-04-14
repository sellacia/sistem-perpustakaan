@extends('layouts.anggota.app')

@section('content')

<div class="p-6 max-w-2xl mx-auto">

    <h2 class="text-xl font-semibold text-blue-600 mb-4">
        Form Pinjam Buku
    </h2>

    <div class="bg-white p-6 rounded-xl shadow">
        <div class="w-full aspect-[3/4] overflow-hidden rounded-xl mb-4">
    <img src="{{ $item->gambar
        ? asset('storage/' . $item->gambar)
        : 'https://via.placeholder.com/300x400' }}"
        class="w-full h-full object-cover">
</div>

        <!-- ✅ FIX ROUTE -->
        <form action="{{ route('anggota.pinjam', $buku->id) }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block mb-1">Nama</label>
                <input type="text" name="nama"
                    value="{{ auth()->user()->nama ?? '' }}"
                    class="w-full border rounded p-2"
                    required>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Buku</label>
                <input type="text"
                    value="{{ $buku->judul }}"
                    class="w-full border rounded p-2 bg-gray-100"
                    readonly>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <!-- ✅ FIX KEMBALI -->
                <a href="{{ route('anggota.buku') }}"
                    class="bg-gray-300 px-4 py-2 rounded">
                    Batal
                </a>

                <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded">
                    Konfirmasi
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
