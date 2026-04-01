@extends('layouts.app')

@section('content')

<div class="p-6 max-w-4xl mx-auto">

    <!-- TITLE -->
    <h2 class="text-2xl font-bold text-blue-600 mb-6">Pengembalian Buku</h2>

    <div class="bg-white p-6 rounded-2xl shadow">

        <h3 class="text-lg font-semibold text-gray-700 mb-6">Form Pengembalian</h3>

        <form>

            <!-- NAMA -->
            <div class="mb-4">
                <label class="block text-sm mb-1 text-gray-600">Nama</label>
                <input type="text" value="upi"
                    class="w-full border rounded-lg p-3 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- JUDUL -->
            <div class="mb-4">
                <label class="block text-sm mb-1 text-gray-600">Judul Buku</label>
                <input type="text" value="Cantik Itu Luka"
                    class="w-full border rounded-lg p-3 bg-gray-50">
            </div>

            <!-- TANGGAL -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm mb-1 text-gray-600">Tanggal Pinjam</label>
                    <input type="text" value="17-02-2026"
                        class="w-full border rounded-lg p-3 bg-gray-50">
                </div>

                <div>
                    <label class="block text-sm mb-1 text-gray-600">Batas Kembali</label>
                    <input type="text" value="20-02-2026"
                        class="w-full border rounded-lg p-3 bg-gray-50">
                </div>
            </div>

            <!-- TANGGAL KEMBALI -->
            <div class="mb-4">
                <label class="block text-sm mb-1 text-gray-600">Tanggal Kembali</label>
                <input type="text" value="22-02-2026"
                    class="w-full border rounded-lg p-3 bg-gray-50">
            </div>

            <!-- INFO BOX -->
            <div class="grid grid-cols-2 gap-4 mb-6">

                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Terlambat</p>
                    <p class="text-lg font-semibold text-red-600">2 Hari</p>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Denda</p>
                    <p class="text-lg font-semibold text-yellow-600">Rp 4.000</p>
                </div>

            </div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-3">
                <a href="/buku" class="bg-gray-300 px-4 py-2 rounded-lg">
                    Batal
                </a>

                <button class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700">
                    Konfirmasi
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
