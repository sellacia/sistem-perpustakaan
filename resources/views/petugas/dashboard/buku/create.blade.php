@extends('layouts.petugas.app')

@section('content')

<div class="p-6">

    <h2 class="text-xl font-bold text-blue-700 mb-4">Tambah Buku</h2>

    <form action="/petugas/buku" method="POST" class="bg-white p-6 rounded-xl shadow-md">
        @csrf

        <!-- Kode Buku -->
        <div class="mb-4">
            <label class="block mb-1">Kode Buku</label>
            <input type="text" name="kode_buku"
                class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-400">
        </div>

        <!-- Judul -->
        <div class="mb-4">
            <label class="block mb-1">Judul Buku</label>
            <input type="text" name="judul"
                class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-400">
        </div>

        <!-- Pengarang & Penerbit -->
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block mb-1">Pengarang</label>
                <input type="text" name="pengarang"
                    class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block mb-1">Penerbit</label>
                <input type="text" name="penerbit"
                    class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-400">
            </div>
        </div>

        <!-- Tahun & Kategori -->
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block mb-1">Tahun Terbit</label>
                <input type="text" name="tahun"
                    class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block mb-1">Kategori</label>
                <input type="text" name="kategori"
                    class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-400">
            </div>
        </div>

        <!-- Stok & Status -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block mb-1">Stok</label>
                <input type="number" name="stok"
                    class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block mb-1">Status</label>
                <select name="status" class="w-full border rounded p-2">
                    <option value="tersedia">Tersedia</option>
                    <option value="dipinjam">Dipinjam</option>
                </select>
            </div>
        </div>

        <!-- BUTTON -->
        <div class="flex justify-end gap-3">
            <a href="/petugas/buku"
                class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                Batal
            </a>

            <button type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                Simpan
            </button>
        </div>

    </form>

</div>

@endsection
