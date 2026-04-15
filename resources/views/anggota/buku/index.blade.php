@extends('layouts.anggota.app')

@section('content')
    <div class="space-y-6">
        @if (session('success'))
            <div data-auto-dismiss
                class="transform rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm transition duration-300">
                <div class="flex items-center gap-3"><i class="fas fa-circle-check"></i><span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if (session('error'))
            <div data-auto-dismiss
                class="transform rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm transition duration-300">
                <div class="flex items-center gap-3"><i
                        class="fas fa-circle-exclamation"></i><span>{{ session('error') }}</span></div>
            </div>
        @endif

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('anggota.buku') }}" class="relative">
                <i class="fas fa-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" placeholder="Cari judul, pengarang, atau kode buku..."
                    value="{{ request('search') }}"
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-700 outline-none transition focus:border-sky-300 focus:bg-white focus:ring-4 focus:ring-sky-100">
            </form>
        </section>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">

            @forelse ($buku as $item)
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col group hover:shadow-md transition">

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

                        {{-- STATUS --}}
                        <div class="absolute top-3 right-3">
                            @if ($item->stok > 0)
                                <span class="bg-green-500 text-white px-2 py-1 rounded text-xs font-bold">
                                    Tersedia
                                </span>
                            @else
                                <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-bold">
                                    Kosong
                                </span>
                            @endif
                        </div>

                        {{-- HOVER --}}
                        <div
                            class="absolute inset-0 bg-black/40 flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition">

                            {{-- DETAIL --}}
                            <a href="{{ route('anggota.buku.detail', $item->id) }}"
                                class="w-10 h-10 bg-white text-indigo-600 rounded-full flex items-center justify-center shadow hover:bg-indigo-50">
                                <i class="fas fa-eye"></i>
                            </a>

                            {{-- PINJAM --}}
                            @if ($item->stok > 0)
                                <form action="{{ route('anggota.pinjam', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-10 h-10 bg-white text-green-600 rounded-full flex items-center justify-center shadow hover:bg-green-50">
                                        <i class="fas fa-book"></i>
                                    </button>
                                </form>
                            @else
                                <span
                                    class="w-10 h-10 bg-white/50 text-gray-400 rounded-full flex items-center justify-center">
                                    <i class="fas fa-ban"></i>
                                </span>
                            @endif

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
                    Belum ada buku
                </div>
            @endforelse

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
