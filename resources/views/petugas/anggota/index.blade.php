@extends('layouts.petugas.app')

@section('content')
<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-[32px] bg-gradient-to-r from-cyan-600 via-sky-600 to-blue-700 px-6 py-8 text-white shadow-xl lg:flex-row lg:items-end lg:justify-between">
        <div class="max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-100">Data Anggota</p>
            <h1 class="mt-3 text-3xl font-bold">Pusat pengelolaan anggota perpustakaan.</h1>
            <p class="mt-3 text-sm leading-7 text-cyan-50/90">Tambahkan, cek detail, edit, dan hapus data anggota dari satu halaman kerja yang lebih nyaman dipakai.</p>
        </div>
        <a href="{{ route('petugas.anggota.create') }}" class="inline-flex items-center gap-2 self-start rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-sky-700 shadow-sm transition hover:bg-slate-100">
            <i class="fas fa-user-plus"></i> Tambah Anggota
        </a>
    </section>

    @if(session('success'))
    <div data-auto-dismiss class="transform rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm transition duration-300">
        <div class="flex items-center gap-3">
            <i class="fas fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div data-auto-dismiss class="transform rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm transition duration-300">
        <div class="flex items-center gap-3">
            <i class="fas fa-circle-exclamation"></i>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <section class="grid gap-4 md:grid-cols-3">
        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Total Anggota</p>
            <p class="mt-4 text-3xl font-bold text-slate-900">{{ $anggota->count() }}</p>
        </article>
        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Kontak Tersedia</p>
            <p class="mt-4 text-3xl font-bold text-slate-900">{{ $anggota->filter(fn($item) => filled($item->no_telp))->count() }}</p>
        </article>
        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Data Lengkap</p>
            <p class="mt-4 text-3xl font-bold text-slate-900">{{ $anggota->filter(fn($item) => filled($item->no_telp) && filled($item->alamat))->count() }}</p>
        </article>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-4 border-b border-slate-100 px-6 py-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Daftar Anggota</h2>
                <p class="text-sm text-slate-500">Klik aksi untuk melihat detail, memperbarui, atau menghapus data anggota.</p>
            </div>
            <div class="relative w-full max-w-sm">
                <i class="fas fa-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input id="anggotaSearch" type="text" placeholder="Cari nama, username, nomor telepon..." class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-700 outline-none transition focus:border-sky-300 focus:bg-white focus:ring-4 focus:ring-sky-100">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Username</th>
                        <th class="px-6 py-4">No Telp</th>
                        <th class="px-6 py-4">Alamat</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="anggotaTableBody" class="divide-y divide-slate-100">
                    @forelse($anggota as $index => $a)
                    <tr class="anggota-row transition hover:bg-slate-50" data-search="{{ strtolower($a->name . ' ' . $a->username . ' ' . $a->no_telp . ' ' . $a->alamat) }}">
                        <td class="px-6 py-4 text-slate-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-slate-900">{{ $a->name }}</p>
                            <p class="text-xs text-slate-500">ID #{{ $a->id }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-700">{{ $a->username }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ $a->no_telp }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $a->alamat }}</td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap items-center justify-center gap-2">
                                <a href="{{ route('petugas.anggota.show', $a->id) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                                    <i class="fas fa-eye text-sky-500"></i> Detail
                                </a>
                                <a href="{{ route('petugas.anggota.edit', $a->id) }}" class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-3 py-2 text-xs font-semibold text-white transition hover:bg-amber-600">
                                    <i class="fas fa-pen"></i> Edit
                                </a>
                                <form action="{{ route('petugas.anggota.destroy', $a->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" data-confirm data-confirm-title="Hapus anggota?" data-confirm-message="Data anggota {{ $a->name }} akan dihapus permanen dari sistem." class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-rose-700">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">Data anggota belum tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
    const anggotaSearch = document.getElementById('anggotaSearch');
    const anggotaRows = Array.from(document.querySelectorAll('.anggota-row'));

    if (anggotaSearch) {
        anggotaSearch.addEventListener('input', function () {
            const keyword = this.value.trim().toLowerCase();
            anggotaRows.forEach((row) => {
                row.style.display = row.dataset.search.includes(keyword) ? '' : 'none';
            });
        });
    }
</script>
@endsection
