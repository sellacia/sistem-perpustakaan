@extends('layouts.kepala.app')

@section('content')

<div class="grid grid-cols-3 gap-6">

    {{-- TOTAL DENDA --}}
    <div class="bg-gradient-to-r from-red-100 to-pink-200 p-5 rounded-xl shadow">
        <p class="text-gray-600">Total Denda</p>
        <h2 class="text-2xl font-bold">Rp 4.000</h2>
    </div>

    {{-- BUKU DIPINJAM --}}
    <div class="bg-gradient-to-r from-yellow-100 to-orange-200 p-5 rounded-xl shadow">
        <p class="text-gray-600">Buku Dipinjam</p>
        <h2 class="text-2xl font-bold">2 Buku</h2>
    </div>

    {{-- TERLAMBAT --}}
    <div class="bg-gradient-to-r from-purple-100 to-pink-200 p-5 rounded-xl shadow">
        <p class="text-gray-600">Buku Terlambat</p>
        <h2 class="text-2xl font-bold">1 Buku</h2>
    </div>

</div>

@endsection
