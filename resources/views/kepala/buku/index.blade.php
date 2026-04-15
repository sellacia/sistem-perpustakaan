@extends('layouts.kepala.app')

@section('content')
    <div class="p-6 space-y-6">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Katalog Buku</h1>
            </div>
            <div class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-sm font-semibold">
                Total: {{ count($buku) }}
            </div>
        </div>

        {{-- SEARCH --}}
        <div class="relative mb-4">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" id="searchInput" placeholder="Cari judul, pengarang, kode buku..."
                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        {{-- GRID CARD --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">

            @forelse ($buku as $item)
                <div class="buku-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col group hover:shadow-md transition"
                    data-search="{{ strtolower($item->judul . ' ' . $item->pengarang . ' ' . $item->kode_buku) }}">

                    {{-- COVER --}}
                    <div class="relative aspect-[3/4] overflow-hidden bg-gray-100">

                        @if ($item->cover)
                            <img src="{{ asset('assets/images/' . basename($item->cover)) }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                <i class="fas fa-book text-5xl mb-2"></i>
                                <span class="text-xs">No Cover</span>
                            </div>
                        @endif

                        {{-- HOVER --}}
                        <div
                            class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            <a href="{{ route('kepala.buku.show', $item->id) }}"
                                class="w-10 h-10 bg-white text-blue-600 rounded-full flex items-center justify-center shadow hover:bg-blue-50">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>

                    </div>

                    {{-- CONTENT --}}
                    <div class="p-4 flex flex-col flex-grow">

                        <div class="text-[10px] font-mono text-gray-400 mb-1 uppercase">
                            {{ $item->kode_buku }}
                        </div>

                        <h3 class="font-bold text-gray-800 text-sm line-clamp-2 mb-1">
                            {{ $item->judul }}
                        </h3>

                        <p class="text-xs text-gray-500 mb-3">
                            {{ $item->pengarang }}
                        </p>

                        <div class="mt-auto pt-3 border-t flex justify-between text-xs">
                            <span class="text-gray-400">{{ $item->tahun }}</span>
                            <span class="font-bold {{ $item->stok > 0 ? 'text-blue-600' : 'text-red-500' }}">
                                Stok: {{ $item->stok }}
                            </span>
                        </div>

                    </div>
                </div>

            @empty
                <div class="col-span-full text-center text-gray-400 py-10">
                    Belum ada data buku
                </div>
            @endforelse

        </div>
    </div>
    <script>
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
