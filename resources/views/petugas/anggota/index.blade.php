@extends('layouts.petugas.app')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Data Anggota</h1>

        <a href="{{ route('petugas.anggota.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + Tambah Anggota
        </a>
    </div>

    <!-- NOTIF -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- TABEL -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-3">No</th>
                    <th class="p-3">Nama</th>
                    <th class="p-3">Username</th>
                    <th class="p-3">No Telp</th>
                    <th class="p-3">Alamat</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($anggota as $index => $a)
                <tr class="border-t">
                    <td class="p-3">{{ $index + 1 }}</td>
                    <td class="p-3">{{ $a->name }}</td>
                    <td class="p-3">{{ $a->username }}</td>
                    <td class="p-3">{{ $a->no_telp }}</td>
                    <td class="p-3">{{ $a->alamat }}</td>

                    <td class="p-3 text-center flex justify-center gap-2">
                        <a href="{{ route('petugas.anggota.edit', $a->id) }}"
                            class="bg-yellow-400 px-3 py-1 rounded text-white">
                            Edit
                        </a>

                        <form action="{{ route('petugas.anggota.delete', $a->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 px-3 py-1 rounded text-white">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-3 text-center text-gray-500">
                        Data anggota belum ada
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>

@endsection
