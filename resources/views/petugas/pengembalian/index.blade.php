@extends('layouts.petugas.app')

@section('content')
    <div class="p-6">

        <h1 class="text-2xl font-bold mb-4 text-blue-600">
            Daftar Pengembalian
        </h1>

        <!-- Tabel -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="p-3">ID</th>
                        <th class="p-3">Nama Anggota</th>
                        <th class="p-3">Judul Buku</th>
                        <th class="p-3">Tanggal Pinjam</th>
                        <th class="p-3">Batas Kembali</th>
                        <th class="p-3">Tanggal Kembali</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Denda</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($pinjam as $item)
                        <tr class="border-t">

                            <td class="p-3">{{ $item->id }}</td>
                            <td class="p-3">{{ $item->anggota->name ?? '-' }}</td>
                            <td class="p-3">{{ $item->buku->judul ?? '-' }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d-m-Y') }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($item->tanggal_wajib_kembali)->format('d-m-Y') }}</td>
                            <td class="p-3">{{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d-m-Y') : '-' }}</td>

                            <!-- STATUS -->
                            <td class="p-3">
                                @if ($item->status == 'dikembalikan')
                                    <span class="bg-yellow-400 text-white px-2 py-1 rounded text-xs">
                                        Menunggu
                                    </span>
                                @elseif($item->status == 'selesai')
                                    <span class="bg-green-500 text-white px-2 py-1 rounded text-xs">
                                        Selesai
                                    </span>
                                @endif
                            </td>

                            <!-- DENDA -->
                            <td class="p-3">
                                @if ($item->denda && $item->denda->jumlah_denda > 0)
                                    Rp {{ number_format($item->denda->jumlah_denda, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>

                            <!-- AKSI -->
                            <td class="p-3">
                                @if ($item->status == 'dikembalikan')
                                    <button type="button"
                                        onclick="openModal(
                                            {{ $item->id }},
                                            '{{ addslashes($item->nama ?? ($item->anggota->name ?? '-')) }}',
                                            '{{ addslashes($item->buku->judul ?? '-') }}'
                                        )"
                                        class="bg-green-500 hover:bg-green-600 transition text-white px-3 py-1 rounded">
                                        Konfirmasi
                                    </button>
                                @else
                                        <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center p-4 text-gray-500">
                                Tidak ada data pengembalian
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <!-- MODAL -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">

        <div class="bg-white p-6 rounded-xl w-96">
            <h2 class="text-lg font-bold mb-4">Konfirmasi Pengembalian</h2>

            <p class="mb-2"><b>Nama:</b> <span id="nama"></span></p>
            <p class="mb-2"><b>Buku:</b> <span id="buku"></span></p>

            <form id="formKonfirmasi" method="POST">
                @csrf

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="closeModal()" class="px-3 py-1 bg-gray-300 rounded">
                        Batal
                    </button>

                    <button type="submit" onclick="this.disabled=true; this.form.submit();"
                        class="px-3 py-1 bg-blue-500 text-white rounded">
                        Simpan
                    </button>
                </div>
            </form>
        </div>

    </div>

    <script>
        function openModal(id, nama, buku) {
            document.getElementById('modal').classList.remove('hidden');

            document.getElementById('nama').innerText = nama;
            document.getElementById('buku').innerText = buku;

            document.getElementById('formKonfirmasi').action =
                `/petugas/pengembalian/${id}/konfirmasi`;
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>
@endsection
