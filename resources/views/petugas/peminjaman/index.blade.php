@extends('layouts.petugas.app')

@section('content')
    <div class="space-y-6">
        @php
            $statusCount = $data->groupBy('status');
        @endphp

        {{-- NOTIF --}}
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700">
                <i class="fas fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
                <i class="fas fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        {{-- TABLE --}}
        <section class="rounded-3xl border bg-white shadow-sm">
            <div class="flex justify-between px-6 py-4 border-b">
                <h2 class="font-bold">Daftar Peminjaman</h2>
                <span>{{ $data->count() }} transaksi</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Anggota</th>
                            <th class="px-4 py-3">Buku</th>
                            <th class="px-4 py-3">Tgl Pinjam</th>
                            <th class="px-4 py-3">Wajib Kembali</th>
                            <th class="px-4 py-3">Tgl Kembali</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($data as $item)
                            <tr class="border-t">
                                <td class="px-4 py-3">{{ $loop->iteration }}</td>

                                <td class="px-4 py-3">
                                    {{ $item->anggota->name ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item->buku->judul ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item->tanggal_wajib_kembali ? \Carbon\Carbon::parse($item->tanggal_wajib_kembali)->format('d M Y') : '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item->display_tanggal_kembali ? \Carbon\Carbon::parse($item->display_tanggal_kembali)->format('d M Y') : '-' }}
                                </td>

                                {{-- STATUS --}}
                                <td class="px-4 py-3 text-center">
                                    <span class="text-xs font-semibold">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>

                                {{-- AKSI --}}
                                <td class="px-4 py-3 text-center">
                                    @if ($item->status == 'menunggu')
                                        {{-- SETUJUI --}}
                                        <a href="{{ route('petugas.peminjaman.setujui', $item->id) }}"
                                            class="bg-green-600 text-white px-2 py-1 rounded text-xs">
                                            Setujui
                                        </a>

                                        {{-- TOLAK --}}
                                        <a href="{{ route('petugas.peminjaman.tolak', $item->id) }}"
                                            class="bg-red-600 text-white px-2 py-1 rounded text-xs">
                                            Tolak
                                        </a>
                                    @elseif (in_array($item->status, ['dipinjam', 'terlambat']))
                                        {{-- 🔥 KEMBALIKAN --}}
                                        <button onclick="openModal({{ $item->id }})"
                                            class="bg-indigo-600 text-white px-3 py-1 rounded text-xs">
                                            Kembalikan
                                        </button>
                                    @elseif ($item->status == 'dikembalikan')
                                        <a href="{{ route('petugas.pengembalian') }}"
                                            class="border px-2 py-1 rounded text-xs">
                                            Lanjut Konfirmasi
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">Tidak ada aksi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-6 text-gray-400">
                                    Tidak ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    {{-- 🔥 MODAL --}}
    <div id="modalKembali" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl w-80">

            <h2 class="font-bold mb-4">Kondisi Buku</h2>

            <form id="formKembali" method="POST">
                @csrf
                @method('PUT')

                <select name="kondisi" id="kondisi" class="w-full border px-3 py-2 rounded mb-3">
                    <option value="baik">Baik</option>
                    <option value="rusak">Rusak</option>
                    <option value="hilang">Hilang</option>
                </select>

                <input type="text" id="denda" readonly class="w-full border px-3 py-2 rounded mb-3 bg-gray-100">

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="bg-gray-300 px-3 py-1 rounded">
                        Batal
                    </button>

                    <button type="submit" class="bg-indigo-600 text-white px-3 py-1 rounded">
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection

{{-- SCRIPT --}}
<script>
    function openModal(id) {
        const modal = document.getElementById('modalKembali');
        const form = document.getElementById('formKembali');

        form.action = `/petugas/peminjaman/${id}/kembalikan`;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        document.getElementById('modalKembali').classList.add('hidden');
    }

    // AUTO DENDA
    document.addEventListener('DOMContentLoaded', function() {
        const kondisi = document.getElementById('kondisi');
        const denda = document.getElementById('denda');

        kondisi.addEventListener('change', function() {
            let nilai = 0;

            if (this.value === 'rusak') nilai = 50000;
            if (this.value === 'hilang') nilai = 100000;

            denda.value = 'Rp ' + nilai.toLocaleString('id-ID');
        });
    });
</script>
