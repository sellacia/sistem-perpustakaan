@extends('layouts.petugas.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-600">Profil Anggota</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">{{ $anggota->name }}</h1>
            <p class="mt-2 max-w-2xl text-sm text-slate-500">Detail anggota untuk membantu petugas memverifikasi identitas dan melakukan tindak lanjut peminjaman.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('petugas.anggota.edit', $anggota->id) }}" class="inline-flex items-center gap-2 rounded-2xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-600">
                <i class="fas fa-pen"></i> Edit
            </a>
            <a href="{{ route('petugas.anggota.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.3fr_0.7fr]">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="grid gap-5 md:grid-cols-2">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Nama Lengkap</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">{{ $anggota->name }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Username</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">{{ $anggota->username }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Nomor Telepon</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">{{ $anggota->no_telp }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Role</p>
                    <p class="mt-2 text-lg font-semibold capitalize text-slate-900">{{ $anggota->role }}</p>
                </div>
            </div>

            <div class="mt-5 rounded-2xl bg-slate-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Alamat</p>
                <p class="mt-2 text-sm leading-7 text-slate-700">{{ $anggota->alamat }}</p>
            </div>
        </div>

        <div class="rounded-3xl bg-gradient-to-br from-sky-600 via-cyan-600 to-teal-500 p-6 text-white shadow-lg">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/15 text-2xl font-bold shadow-inner">
                {{ strtoupper(substr($anggota->name, 0, 1)) }}
            </div>
            <h2 class="mt-6 text-xl font-bold">Ringkasan Akun</h2>
            <p class="mt-2 text-sm text-white/80">Gunakan halaman ini untuk verifikasi cepat sebelum menyetujui peminjaman atau memperbarui data anggota.</p>
            <div class="mt-8 space-y-4 rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur-sm">
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-white/60">Dibuat</p>
                    <p class="mt-1 text-sm font-semibold">{{ optional($anggota->created_at)->format('d M Y H:i') ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-white/60">Update Terakhir</p>
                    <p class="mt-1 text-sm font-semibold">{{ optional($anggota->updated_at)->format('d M Y H:i') ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
