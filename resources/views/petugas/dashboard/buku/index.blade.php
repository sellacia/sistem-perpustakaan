@extends('layouts.petugas.app')

@section('content')
<div class="p-6">

    <!-- TITLE + BUTTON -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-blue-700">Daftar Buku</h1>

        <a href="/petugas/buku/create"
            class="bg-green-600 text-white px-5 py-2 rounded-lg flex items-center gap-2 shadow hover:bg-green-700">
            <i class="fas fa-plus"></i>
            Tambah Buku
        </a>
    </div>

    <!-- SEARCH -->
    <div class="mb-6">
        <input type="text" placeholder="Cari Buku"
            class="w-full p-3 rounded-lg border focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    <!-- CONTAINER -->
    <div class="bg-gray-100 p-6 rounded-2xl">

        <!-- GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach ($buku as $item)
            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition flex flex-col">

                <!-- COVER -->
                <img src="{{ $item->cover ?? 'https://via.placeholder.com/150' }}"
                    class="h-52 w-full object-cover rounded-xl mb-4">

                <!-- INFO -->
                <h3 class="text-blue-600 font-semibold text-base mb-2">
                    {{ $item->judul }}
                </h3>

                <div class="text-sm text-gray-700 space-y-1 flex-1">
                    <p><b>Kode:</b> {{ $item->kode_buku }}</p>
                    <p><b>Pengarang:</b> {{ $item->pengarang }}</p>
                    <p><b>Penerbit:</b> {{ $item->penerbit }}</p>
                    <p><b>Tahun:</b> {{ $item->tahun }}</p>
                    <p><b>Stok:</b> {{ $item->stok }} Buku</p>
                    <p><b>Status:</b> {{ $item->status }}</p>
                </div>

                <!-- BUTTON -->
                <div class="flex gap-2 mt-5">

                    <a href="/petugas/buku/{{ $item->id }}"
                        class="flex-1 text-center bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">
                        Detail
                    </a>

                    <a href="/petugas/buku/{{ $item->id }}/edit"
                        class="flex-1 text-center bg-yellow-400 text-white py-2 rounded-lg hover:bg-yellow-500">
                        Edit
                    </a>

                    <button
                        onclick="bukaModal('{{ $item->id }}', '{{ $item->judul }}', '{{ $item->kode_buku }}')"
                        class="flex-1 bg-red-500 text-white py-2 rounded-lg hover:bg-red-600">
                        Hapus
                    </button>

                </div>

            </div>
            @endforeach

        </div>

    </div>

</div>

<!-- MODAL HAPUS (CUMA 1, DI LUAR LOOP) -->
<div id="modalHapus" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

    <div class="bg-white p-6 rounded-xl shadow-lg w-[400px] relative">

        <button onclick="tutupModal()" class="absolute top-2 right-3 text-gray-400 text-xl">×</button>

        <div class="text-center mb-3">
            <i class="fas fa-exclamation-triangle text-yellow-400 text-3xl"></i>
        </div>

        <h2 class="text-center font-bold text-lg mb-2">Konfirmasi Penghapusan</h2>

        <p class="text-center text-sm mb-2">
            Apakah Anda yakin ingin menghapus buku:
        </p>

        <p class="text-center font-semibold mb-1" id="judulBuku"></p>
        <p class="text-center text-sm text-gray-500 mb-4" id="kodeBuku"></p>

        <div class="flex justify-center gap-4">
            <button onclick="tutupModal()" class="px-4 py-2 bg-gray-300 rounded">
                Batal
            </button>

            <form id="formHapus" method="POST">
                @csrf
                @method('DELETE')
                <button class="px-4 py-2 bg-red-600 text-white rounded">
                    Ya, Hapus
                </button>
            </form>
        </div>

    </div>

</div>

<!-- SCRIPT -->
<script>
function bukaModal(id, judul, kode) {
    document.getElementById('modalHapus').classList.remove('hidden');
    document.getElementById('modalHapus').classList.add('flex');

    document.getElementById('judulBuku').innerText = '"' + judul + '"';
    document.getElementById('kodeBuku').innerText = 'Kode Buku: ' + kode;

    document.getElementById('formHapus').action = '/petugas/buku/' + id;
}

function tutupModal() {
    document.getElementById('modalHapus').classList.add('hidden');
}
</script>

@endsection
