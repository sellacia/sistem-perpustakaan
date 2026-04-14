@extends('layouts.petugas.app')

@section('content')
<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Buku</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar semua koleksi buku perpustakaan</p>
        </div>
        <a href="/petugas/buku/create"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium text-sm transition shadow-sm">
            <i class="fas fa-plus"></i> Tambah Buku
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
        <i class="fas fa-check-circle text-green-500"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
        <i class="fas fa-exclamation-circle text-red-500"></i>
        {{ session('error') }}
    </div>
    @endif

    {{-- Search --}}
    <div class="relative">
        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
        <input type="text" id="searchInput" placeholder="Cari judul, pengarang, kode buku..."
            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="bukuTable">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">No</th>
                        <th class="px-5 py-3 text-left font-semibold">Kode Buku</th>
                        <th class="px-5 py-3 text-left font-semibold">Judul</th>
                        <th class="px-5 py-3 text-left font-semibold">Pengarang</th>
                        <th class="px-5 py-3 text-left font-semibold">Penerbit</th>
                        <th class="px-5 py-3 text-left font-semibold">Tahun</th>
                        <th class="px-5 py-3 text-center font-semibold">Stok</th>
                        <th class="px-5 py-3 text-center font-semibold">Status</th>
                        <th class="px-5 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50" id="bukuBody">
                    @forelse ($buku as $item)
                    <tr class="hover:bg-gray-50 transition-colors buku-row">
                        <td class="px-5 py-3 text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-5 py-3 font-mono text-xs bg-gray-50 text-gray-700 font-medium">
                            {{ $item->kode_buku }}
                        </td>
                        <td class="px-5 py-3 font-medium text-gray-800 max-w-[200px]">
                            <div class="truncate" title="{{ $item->judul }}">{{ $item->judul }}</div>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $item->pengarang }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $item->penerbit }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $item->tahun }}</td>
                        <td class="px-5 py-3 text-center">
                            <span class="font-bold {{ $item->stok > 0 ? 'text-green-600' : 'text-red-500' }}">
                                {{ $item->stok }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            @if ($item->status == 'tersedia')
                                <span class="bg-green-50 text-green-700 border border-green-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    Tersedia
                                </span>
                            @else
                                <span class="bg-yellow-50 text-yellow-700 border border-yellow-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    Dipinjam
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-center">
                            <div class="inline-flex items-center gap-2">
                                <a href="/petugas/buku/{{ $item->id }}"
                                    class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 border border-indigo-200 px-3 py-1.5 rounded-lg text-xs font-medium transition"
                                    title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/petugas/buku/{{ $item->id }}/edit"
                                    class="bg-yellow-50 text-yellow-600 hover:bg-yellow-100 border border-yellow-200 px-3 py-1.5 rounded-lg text-xs font-medium transition"
                                    title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <button onclick="bukaModal('{{ $item->id }}', '{{ addslashes($item->judul) }}', '{{ $item->kode_buku }}')"
                                    class="bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 px-3 py-1.5 rounded-lg text-xs font-medium transition"
                                    title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-10 text-center text-gray-400">
                            <i class="fas fa-book text-4xl mb-3 block opacity-30"></i>
                            Belum ada data buku
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Modal Hapus --}}
<div id="modalHapus" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
        <div class="text-center mb-4">
            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-trash text-red-500 text-xl"></i>
            </div>
            <h2 class="font-bold text-lg text-gray-800">Hapus Buku?</h2>
            <p class="text-gray-500 text-sm mt-1">Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-3 mb-5 text-center">
            <p class="font-semibold text-gray-800" id="judulBuku"></p>
            <p class="text-xs text-gray-500 mt-1" id="kodeBuku"></p>
        </div>
        <div class="flex gap-3">
            <button onclick="tutupModal()" class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium text-sm transition">
                Batal
            </button>
            <form id="formHapus" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium text-sm transition">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function bukaModal(id, judul, kode) {
    document.getElementById('modalHapus').classList.remove('hidden');
    document.getElementById('modalHapus').classList.add('flex');
    document.getElementById('judulBuku').innerText = judul;
    document.getElementById('kodeBuku').innerText = 'Kode: ' + kode;
    document.getElementById('formHapus').action = '/petugas/buku/' + id;
}
function tutupModal() {
    document.getElementById('modalHapus').classList.add('hidden');
    document.getElementById('modalHapus').classList.remove('flex');
}

// Search
document.getElementById('searchInput').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.buku-row').forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
@endsection
