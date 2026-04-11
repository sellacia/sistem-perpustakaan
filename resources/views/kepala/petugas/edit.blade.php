@extends('layouts.kepala.app')

@section('content')
    <div class="p-6">

        <!-- TITLE -->
        <h1 class="text-2xl font-bold text-blue-600 mb-4">
            Edit Petugas
        </h1>

        <!-- CARD FULL -->
        <div class="bg-white rounded-2xl shadow p-6">

            <h2 class="text-blue-500 font-semibold mb-4">
                Form Edit Petugas
            </h2>

            <form action="{{ route('kepala.petugas.update', $petugas->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama -->
                <div class="mb-4">
                    <label class="block text-sm mb-1">Nama</label>
                    <input type="text" name="name" value="{{ $petugas->name }}"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                </div>

                <!-- Username -->
                <div class="mb-4">
                    <label class="block text-sm mb-1">Username</label>
                    <input type="text" name="username" value="{{ $petugas->username }}"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                </div>

                <!-- GRID 2 KOLOM -->
                <div class="grid md:grid-cols-2 gap-4 mb-4">

                    <div>
                        <label class="block text-sm mb-1">No Telp</label>
                        <input type="text" name="no_telp" value="{{ $petugas->no_telp }}"
                            class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Alamat</label>
                        <input type="text" name="alamat" value="{{ $petugas->alamat }}"
                            class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400">
                    </div>

                </div>

                <!-- BUTTON -->
                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('kepala.petugas.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded-lg">
                        Batal
                    </a>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
                        Update
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
