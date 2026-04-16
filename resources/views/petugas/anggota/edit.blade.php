@extends('layouts.petugas.app')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">

        <!-- HEADER -->
        <div class="flex items-center justify-between gap-4">
            <div>

                <!-- Label -->
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-600">
                    Perbarui Anggota
                </p>

                <!-- Judul -->
                <h1 class="mt-2 text-3xl font-bold text-slate-900">
                    Edit data {{ $anggota->name }}
                </h1>

                <!-- Deskripsi -->
                <p class="mt-2 text-sm text-slate-500">
                    Perbarui profil anggota tanpa mengganggu histori akun yang sudah ada.
                </p>
            </div>

            <!-- Tombol kembali -->
            <a href="{{ route('petugas.anggota.index') }}"
                class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- VALIDASI ERROR -->
        @if ($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 text-sm text-rose-700 shadow-sm">

                <!-- List error -->
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORM EDIT ANGGOTA -->
        <form action="{{ route('petugas.anggota.update', $anggota->id) }}" method="POST"
            class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

            @csrf
            @method('PUT') <!-- Method untuk update -->

            <!-- GRID INPUT -->
            <div class="grid gap-5 md:grid-cols-2">

                <!-- NAMA -->
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Nama Lengkap
                    </label>
                    <input type="text" name="name" value="{{ old('name', $anggota->name) }}"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-amber-300 focus:ring-4 focus:ring-amber-100"
                        required>
                </div>

                <!-- USERNAME -->
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Username
                    </label>
                    <input type="text" name="username" value="{{ old('username', $anggota->username) }}"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-amber-300 focus:ring-4 focus:ring-amber-100"
                        required>
                </div>

                <!-- PASSWORD BARU -->
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Password Baru
                    </label>

                    <!-- Tidak wajib diisi -->
                    <input type="password" name="password"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-amber-300 focus:ring-4 focus:ring-amber-100">

                    <!-- Catatan -->
                    <p class="mt-2 text-xs text-slate-400">
                        Kosongkan jika tidak ingin mengganti password.
                    </p>
                </div>

                <!-- NO TELEPON -->
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        No. Telepon
                    </label>
                    <input type="text" name="no_telp" value="{{ old('no_telp', $anggota->no_telp) }}"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-amber-300 focus:ring-4 focus:ring-amber-100"
                        required>
                </div>

            </div>

            <!-- ALAMAT -->
            <div class="mt-5">
                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Alamat
                </label>

                <textarea name="alamat" rows="4"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-amber-300 focus:ring-4 focus:ring-amber-100"
                    required>{{ old('alamat', $anggota->alamat) }}</textarea>
            </div>

            <!-- TOMBOL AKSI -->
            <div class="mt-6 flex justify-end gap-3">

                <!-- BATAL -->
                <a href="{{ route('petugas.anggota.index') }}"
                    class="rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Batal
                </a>

                <!-- SUBMIT -->
                <button type="submit"
                    class="rounded-2xl bg-amber-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-amber-600">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
@endsection
