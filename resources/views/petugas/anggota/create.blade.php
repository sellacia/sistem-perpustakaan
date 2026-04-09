@extends('layouts.petugas.app')

@section('content')

<div class="p-6 max-w-xl">

    <h1 class="text-2xl font-bold mb-6">Tambah Anggota</h1>

    <form action="{{ route('petugas.anggota.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block mb-1">Nama</label>
            <input type="text" name="name" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label class="block mb-1">Username</label>
            <input type="text" name="username" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label class="block mb-1">Password</label>
            <input type="password" name="password" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label class="block mb-1">No Telp</label>
            <input type="text" name="no_telp" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block mb-1">Alamat</label>
            <textarea name="alamat" class="w-full border p-2 rounded"></textarea>
        </div>

        <div class="flex gap-2">
            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Simpan
            </button>

            <a href="{{ route('petugas.anggota') }}"
                class="bg-gray-400 text-white px-4 py-2 rounded">
                Kembali
            </a>
        </div>

    </form>

</div>

@endsection
