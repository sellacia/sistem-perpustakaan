@extends('layouts.kepala.app')

@section('content')
    <div class="p-6">

        <!-- TITLE -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-blue-700">Data Petugas</h1>

            <a href="{{ route('kepala.petugas.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow">
                + Tambah Petugas
            </a>
        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-600">
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
                    @foreach ($petugas as $i => $p)
                        <tr class="border-t">
                            <td class="p-3">{{ $i + 1 }}</td>
                            <td class="p-3">{{ $p->name }}</td>
                            <td class="p-3">{{ $p->username }}</td>
                            <td class="p-3">{{ $p->no_telp }}</td>
                            <td class="p-3">{{ $p->alamat }}</td>
                            <td class="p-3 text-center space-x-2">
                                <a href="{{ route('kepala.petugas.edit', $p->id) }}"
                                    class="bg-yellow-400 px-3 py-1 rounded text-white">
                                    Edit
                                </a>
                                <form action="{{ route('kepala.petugas.destroy', $p->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin mau hapus?')"
                                        class="bg-red-500 px-3 py-1 rounded text-white">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
