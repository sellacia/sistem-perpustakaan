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
        @if (session('success'))
            <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
                <i class="fas fa-check-circle text-green-500"></i>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
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

        {{-- Grid Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6" id="bukuGrid">
            @forelse ($buku as $item)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col group hover:shadow-md transition-all duration-300 buku-card"
                    data-judul="{{ $item->judul }}" data-pengarang="{{ $item->pengarang }}"
                    data-kode="{{ $item->kode_buku }}">
                    {{-- Cover Image --}}
                    <div class="relative aspect-[3/4] overflow-hidden bg-gray-100">
                        @if ($item->cover)
                            <img
                                src="{{ $item->cover ? asset('assets/images/' . basename($item->cover)) : 'https://via.placeholder.com/300x400' }}">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                <i class="fas fa-book text-5xl mb-2"></i>
                                <span class="text-xs">No Cover</span>
                            </div>
                        @endif

                        {{-- Status Badge --}}
                        <div class="absolute top-3 right-3">
                            @if ($item->status == 'tersedia')
                                <span
                                    class="bg-green-500/90 backdrop-blur-sm text-white px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                    Tersedia
                                </span>
                            @else
                                <span
                                    class="bg-amber-500/90 backdrop-blur-sm text-white px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                    Dipinjam
                                </span>
                            @endif
                        </div>

                        {{-- Hover Actions Overlay --}}
                        <div
                            class="absolute inset-0 bg-black/40 flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <a href="/petugas/buku/{{ $item->id }}"
                                class="w-9 h-9 bg-white text-indigo-600 rounded-full flex items-center justify-center hover:bg-indigo-50 transition shadow-lg"
                                title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="/petugas/buku/{{ $item->id }}/edit"
                                class="w-9 h-9 bg-white text-amber-500 rounded-full flex items-center justify-center hover:bg-amber-50 transition shadow-lg"
                                title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            @if (($item->peminjaman_aktif_count ?? 0) > 0 || $item->status === 'dipinjam')
                                <span
                                    class="w-9 h-9 bg-white/50 text-gray-400 rounded-full flex items-center justify-center cursor-not-allowed shadow-lg"
                                    title="Buku sedang dipinjam">
                                    <i class="fas fa-lock"></i>
                                </span>
                            @else
                                <button
                                    onclick="bukaModal('{{ $item->id }}', '{{ addslashes($item->judul) }}', '{{ $item->kode_buku }}')"
                                    class="w-9 h-9 bg-white text-red-500 rounded-full flex items-center justify-center hover:bg-red-50 transition shadow-lg"
                                    title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-4 flex flex-col flex-grow">
                        <div class="text-[10px] font-mono text-gray-400 mb-1 uppercase tracking-widest">
                            {{ $item->kode_buku }}</div>
                        <h3 class="font-bold text-gray-800 text-sm line-clamp-2 leading-tight mb-1 min-h-[2.5rem]"
                            title="{{ $item->judul }}">
                            {{ $item->judul }}
                        </h3>
                        <p class="text-xs text-gray-500 truncate mb-3">{{ $item->pengarang }}</p>

                        <div class="mt-auto pt-3 border-t border-gray-50 flex items-center justify-between text-[11px]">
                            <div class="text-gray-400">
                                <i class="far fa-calendar-alt mr-1"></i> {{ $item->tahun }}
                            </div>
                            <div class="font-bold {{ $item->stok > 0 ? 'text-blue-600' : 'text-red-500' }}">
                                Stok: {{ $item->stok }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center text-gray-400">
                    <div class="bg-gray-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-book-open text-3xl opacity-20"></i>
                    </div>
                    <p class="font-medium">Belum ada koleksi buku</p>
                    <p class="text-sm">Silahkan tambah buku baru untuk memulai</p>
                </div>
            @endforelse
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
                <button onclick="tutupModal()"
                    class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium text-sm transition">
                    Batal
                </button>
                <form id="formHapus" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium text-sm transition">
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
        document.getElementById('searchInput').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.buku-card').forEach(card => {
                const title = card.getAttribute('data-judul').toLowerCase();
                const author = card.getAttribute('data-pengarang').toLowerCase();
                const code = card.getAttribute('data-kode').toLowerCase();

                if (title.includes(q) || author.includes(q) || code.includes(q)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
        const searchInput = document.getElementById('searchInput');
        const bukuCards = document.querySelectorAll('.buku-card');

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();

            bukuCards.forEach(card => {
                const searchData = card.getAttribute('data-search');
                if (searchData.includes(query)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
@endsection
