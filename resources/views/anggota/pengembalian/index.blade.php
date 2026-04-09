@extends('layouts.anggota.app')

@section('content')
    <div class="p-6 max-w-4xl mx-auto">

        <h2 class="text-2xl font-bold text-blue-600 mb-6">Pengembalian Buku</h2>

        <div class="bg-white p-6 rounded-2xl shadow">

            <h3 class="text-lg font-semibold text-gray-700 mb-6">Form Pengembalian</h3>

            @if ($pinjam->isEmpty())
                <div class="text-center text-gray-500 py-10">
                    Tidak ada buku yang sedang dipinjam
                </div>
            @else
                <form action="{{ route('anggota.pengembalian.proses') }}" method="POST">
                    @csrf

                    <!-- 🔥 HIDDEN ID (AMAN DATABASE) -->
                    <input type="hidden" name="pinjam_id" value="{{ $pinjam[0]->id }}">

                    <!-- NAMA -->
                    <div class="mb-4">
                        <label class="block text-sm mb-1 text-gray-600">Nama</label>
                        <input type="text" value="{{ auth()->user()->nama ?? (auth()->user()->name ?? '-') }}" readonly
                            class="w-full border rounded-lg p-3 bg-gray-50">
                    </div>

                    <!-- 🔥 JUDUL BISA DIKETIK -->
                    <div class="mb-4">
                        <label class="block text-sm mb-1 text-gray-600">Judul Buku</label>
                        <input type="text" name="judul_buku" class="w-full border rounded-lg p-3">
                    </div>

                    <!-- 🔥 TANGGAL MANUAL -->
                    <div class="grid grid-cols-2 gap-4 mb-4">

                        <div>
                            <label class="block text-sm mb-1 text-gray-600">Tanggal Pinjam</label>
                            <input type="date" id="tanggal_pinjam" class="w-full border rounded-lg p-3">
                        </div>

                        <div>
                            <label class="block text-sm mb-1 text-gray-600">Tanggal Wajib Kembali</label>
                            <input type="date" id="batas_kembali" class="w-full border rounded-lg p-3">
                        </div>

                    </div>

                    <!-- TANGGAL KEMBALI -->
                    <div class="mb-4">
                        <label class="block text-sm mb-1 text-gray-600">Tanggal Kembali</label>
                        <input type="date" id="tanggal_kembali" name="tanggal_kembali"
                            class="w-full border rounded-lg p-3">
                    </div>

                    <!-- INFO -->
                    <div class="grid grid-cols-2 gap-4 mb-6">

                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500">Terlambat</p>
                            <p id="terlambat_text" class="text-lg font-semibold text-red-600">
                                0 Hari
                            </p>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500">Denda</p>
                            <p id="denda_text" class="text-lg font-semibold text-yellow-600">
                                Rp 0
                            </p>
                        </div>

                    </div>

                    <!-- BUTTON -->
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('anggota.buku') }}" class="bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400">
                            Batal
                        </a>

                        <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700">
                            Konfirmasi
                        </button>
                    </div>

                </form>
            @endif

        </div>
    </div>

    <!-- 🔥 SCRIPT AUTO DENDA -->
    <script>
        const batasInput = document.getElementById('batas_kembali');
        const kembaliInput = document.getElementById('tanggal_kembali');

        function hitungDenda() {
            let batas = batasInput.value ? new Date(batasInput.value) : null;
            let kembali = kembaliInput.value ? new Date(kembaliInput.value) : null;

            if (!batas || !kembali) return;

            let selisih = (kembali - batas) / (1000 * 60 * 60 * 24);

            let terlambat = selisih > 0 ? Math.floor(selisih) : 0;
            let denda = terlambat * 2000;

            document.getElementById('terlambat_text').innerText = terlambat + " Hari";
            document.getElementById('denda_text').innerText = "Rp " + denda.toLocaleString('id-ID');
        }

        batasInput.addEventListener('change', hitungDenda);
        kembaliInput.addEventListener('change', hitungDenda);
    </script>
@endsection
