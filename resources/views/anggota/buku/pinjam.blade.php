@extends('layouts.anggota.app')

@section('content')

<div class="p-6 max-w-2xl mx-auto">

    <h2 class="text-xl font-semibold text-blue-600 mb-4">Form Pinjam Buku</h2>

    <div class="bg-white p-6 rounded-xl shadow">

        <form action="{{ route('buku.pinjam', $buku->id) }}" method="POST">
            @csrf

            <!-- NAMA -->
            <div class="mb-4">
                <label class="block mb-1">Nama</label>
                <input type="text" name="nama"
                    class="w-full border rounded p-2"
                    placeholder="Masukkan nama" required>
            </div>

            <!-- BUKU -->
            <div class="mb-4">
                <label class="block mb-1">Buku yang di pinjam</label>
                <input type="text"
                    value="{{ $buku->judul }}"
                    class="w-full border rounded p-2 bg-gray-100"
                    readonly>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-3 mt-6">
                <a href="/buku" class="bg-gray-300 px-4 py-2 rounded">
                    Batal
                </a>

                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                    Konfirmasi
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
