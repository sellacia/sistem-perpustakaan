@extends('layouts.kepala.app')

@section('content')
    <div class="space-y-6">

        <!-- TOMBOL TAMBAH PETUGAS -->
        <a href="{{ route('kepala.petugas.create') }}"
            class="inline-flex items-center gap-2 self-start rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-sky-700 shadow-sm transition hover:bg-slate-100">
            <i class="fas fa-user-plus"></i> Tambah Petugas
        </a>
    </div>

    </section>

    <!-- SECTION TABEL PETUGAS -->
    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">

        <!-- HEADER -->
        <div class="border-b border-slate-100 px-6 py-5">

            <!-- Judul -->
            <h2 class="text-lg font-bold text-slate-900">
                Daftar Petugas
            </h2>

            <!-- Deskripsi -->
            <p class="text-sm text-slate-500">
                Edit dan hapus akun petugas dari tabel yang lebih rapi.
            </p>
        </div>

        <!-- WRAPPER TABLE -->
        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <!-- HEADER TABLE -->
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

                <!-- BODY TABLE -->
                <tbody class="divide-y divide-slate-100">

                    <!-- LOOP DATA PETUGAS -->
                    @forelse ($petugas as $i => $p)
                        <tr class="transition hover:bg-slate-50">

                            <!-- Nomor urut -->
                            <td class="px-6 py-4 text-slate-500">
                                {{ $i + 1 }}
                            </td>

                            <!-- Nama -->
                            <td class="px-6 py-4 font-semibold text-slate-900">
                                {{ $p->name }}
                            </td>

                            <!-- Username -->
                            <td class="px-6 py-4 text-slate-700">
                                {{ $p->username }}
                            </td>

                            <!-- No Telp -->
                            <td class="px-6 py-4 text-slate-700">
                                {{ $p->no_telp }}
                            </td>

                            <!-- Alamat -->
                            <td class="px-6 py-4 text-slate-600">
                                {{ $p->alamat }}
                            </td>

                            <!-- AKSI -->
                            <td class="px-6 py-4">

                                <div class="flex flex-wrap items-center justify-center gap-2">

                                    <!-- Tombol edit -->
                                    <a href="{{ route('kepala.petugas.edit', $p->id) }}"
                                        class="rounded-xl bg-amber-500 px-3 py-2 text-xs font-semibold text-white transition hover:bg-amber-600">
                                        Edit
                                    </a>

                                    <!-- Form hapus -->
                                    <form action="{{ route('kepala.petugas.destroy', $p->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')

                                        <!-- Tombol hapus -->
                                        <button type="submit" data-confirm data-confirm-title="Hapus petugas?"
                                            data-confirm-message="Akun petugas {{ $p->name }} akan dihapus permanen dari sistem."
                                            class="rounded-xl bg-rose-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-rose-700">
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty
                        <!-- Jika tidak ada data -->
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                Belum ada data petugas.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </section>
    </div>
@endsection
