@extends('layouts.petugas.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('petugas.buku.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-3">
            <i class="fas fa-arrow-left text-xs"></i> Kembali ke Daftar Buku
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Detail Buku</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Cover & Header --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8 flex items-center gap-6">
            <div class="w-28 h-36 bg-white/20 rounded-xl flex items-center justify-center shrink-0 overflow-hidden">
                @if($buku->cover)
                    <img src="{{ asset('assets/images/' . $buku->cover) }}" class="w-full h-full object-cover rounded-xl">
                @else
                    <i class="fas fa-book text-white/60 text-4xl"></i>
                @endif
            </div>
            <div class="text-white">
                <p class="text-white/60 text-xs font-mono mb-1">{{ $buku->kode_buku }}</p>
                <h2 class="text-xl font-bold leading-snug mb-1">{{ $buku->judul }}</h2>
                <p class="text-white/75 text-sm">{{ $buku->pengarang }}</p>
                <div class="mt-3">
                    @if($buku->status == 'tersedia')
                        <span class="bg-green-400/30 border border-green-300/50 text-white text-xs px-3 py-1 rounded-full font-medium">
                            ✓ Tersedia
                        </span>
                    @else
                        <span class="bg-yellow-400/30 border border-yellow-300/50 text-white text-xs px-3 py-1 rounded-full font-medium">
                            Dipinjam
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Detail Info --}}
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Penerbit</p>
                    <p class="font-semibold text-gray-800">{{ $buku->penerbit }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Tahun Terbit</p>
                    <p class="font-semibold text-gray-800">{{ $buku->tahun }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Stok Tersedia</p>
                    <p class="font-bold text-xl {{ $buku->stok > 0 ? 'text-green-600' : 'text-red-500' }}">{{ $buku->stok }} buku</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Kategori</p>
                    <p class="font-semibold text-gray-800">{{ $buku->kategori ?? '-' }}</p>
                </div>
            </div>

            @if($buku->deskripsi)
            <div class="mt-4 bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-500 mb-2">Deskripsi</p>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $buku->deskripsi }}</p>
            </div>
            @endif

            <div class="flex gap-3 mt-6">
                <a href="/petugas/buku/{{ $buku->id }}/edit"
                    class="flex-1 text-center py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl font-medium text-sm transition">
                    <i class="fas fa-pencil-alt mr-1"></i> Edit Buku
                </a>
                <a href="{{ route('petugas.buku.index') }}"
                    class="flex-1 text-center py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium text-sm transition">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
