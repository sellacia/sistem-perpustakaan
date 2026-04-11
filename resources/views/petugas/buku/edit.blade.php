@extends('layouts.petugas.app')

@section('content')

<h2 class="text-xl font-bold mb-4">Edit Buku</h2>

<form action="/petugas/buku/{{ $buku->id }}" method="POST" class="bg-white p-6 rounded-xl shadow-md">
    @csrf
    @method('PUT')

    <!-- Kode Buku -->
    <div class="mb-4">
        <label class="block mb-1">Kode Buku</label>
        <input type="text" name="kode_buku" value="{{ $buku->kode_buku }}"
            class="w-full border rounded p-2">
    </div>

    <!-- Judul -->
    <div class="mb-4">
        <label class="block mb-1">Judul Buku</label>
        <input type="text" name="judul" value="{{ $buku->judul }}"
            class="w-full border rounded p-2">
    </div>

    <!-- Pengarang & Penerbit -->
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block mb-1">Pengarang</label>
            <input type="text" name="pengarang" value="{{ $buku->pengarang }}"
                class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block mb-1">Penerbit</label>
            <input type="text" name="penerbit" value="{{ $buku->penerbit }}"
                class="w-full border rounded p-2">
        </div>
    </div>

    <!-- Tahun & Kategori -->
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block mb-1">Tahun Terbit</label>
            <input type="text" name="tahun" value="{{ $buku->tahun }}"
                class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block mb-1">Kategori</label>
            <input type="text" name="kategori" value="{{ $buku->kategori }}"
                class="w-full border rounded p-2">
        </div>
    </div>

    <!-- Stok & Status -->
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <label class="block mb-1">Stok</label>
            <input type="number" name="stok" value="{{ $buku->stok }}"
                class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block mb-1">Status</label>
            <select name="status" class="w-full border rounded p-2">
                <option value="tersedia" {{ $buku->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="dipinjam" {{ $buku->status == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
            </select>
        </div>
    </div>

    <!-- BUTTON -->
    <div class="flex justify-end gap-3">
        <a href="{{ route('petugas.buku.index') }}" class="px-4 py-2 bg-gray-300 rounded">Batal</a>
        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">
            Simpan Perubahan
        </button>
    </div>

</form>

@endsection
