@extends('layouts.petugas.app')

@section('content')
    <div class="p-6 max-w-2xl mx-auto">

        <!-- HEADER -->
        <div class="mb-6">

            <!-- Tombol kembali -->
            <a href="{{ route('petugas.buku.index') }}"
                class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-3">
                <i class="fas fa-arrow-left text-xs"></i> Kembali
            </a>

            <!-- Judul -->
            <h1 class="text-2xl font-bold text-gray-800">
                Tambah Buku
            </h1>

            <!-- Deskripsi -->
            <p class="text-sm text-gray-500 mt-1">
                Isi form di bawah untuk menambahkan buku baru
            </p>
        </div>

        <!-- VALIDASI ERROR -->
        @if ($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm mb-5">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORM TAMBAH BUKU -->
        <form action="/petugas/buku" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">

            @csrf

            <!-- UPLOAD COVER -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Cover Buku
                </label>

                <!-- AREA UPLOAD -->
                <div
                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-xl hover:border-blue-400 transition-colors cursor-pointer group relative">

                    <!-- TEKS UPLOAD -->
                    <div class="space-y-1 text-center">
                        <i class="fas fa-image text-gray-400 text-3xl mb-2 group-hover:text-blue-500 transition-colors"></i>

                        <div class="flex text-sm text-gray-600">
                            <label for="cover"
                                class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">

                                <!-- INPUT FILE -->
                                <input id="cover" name="cover" type="file" accept="image/*"
                                    onchange="previewImage(this)">
                            </label>

                            <p class="pl-1">atau drag and drop</p>
                        </div>

                        <p class="text-xs text-gray-500">
                            PNG, JPG, JPEG up to 2MB
                        </p>
                    </div>

                    <!-- PREVIEW IMAGE -->
                    <img id="preview" class="hidden absolute inset-0 w-full h-full object-cover rounded-xl shadow-sm">

                    <!-- BUTTON HAPUS PREVIEW -->
                    <button type="button" id="remove-preview"
                        class="hidden absolute top-2 right-2 bg-red-500 text-white p-1.5 rounded-full hover:bg-red-600 transition shadow-lg z-10"
                        onclick="removeImage()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- GRID INPUT -->
            <div class="grid grid-cols-2 gap-5">

                <!-- KODE BUKU -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Kode Buku <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kode_buku" value="{{ old('kode_buku') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                        placeholder="Cth: BK001">
                </div>

                <!-- TAHUN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Tahun Terbit <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="tahun" value="{{ old('tahun') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="Cth: 2022">
                </div>
            </div>

            <!-- JUDUL -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Judul Buku <span class="text-red-500">*</span>
                </label>
                <input type="text" name="judul" value="{{ old('judul') }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Masukkan judul buku">
            </div>

            <!-- PENGARANG & PENERBIT -->
            <div class="grid grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Pengarang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="pengarang" value="{{ old('pengarang') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="Nama pengarang">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Penerbit <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="penerbit" value="{{ old('penerbit') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="Nama penerbit">
                </div>
            </div>

            <!-- STOK & STATUS -->
            <div class="grid grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Stok <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="stok" value="{{ old('stok', 1) }}" min="0"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">

                        <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>
                            Tersedia
                        </option>

                        <option value="dipinjam" {{ old('status') == 'dipinjam' ? 'selected' : '' }}>
                            Dipinjam
                        </option>

                    </select>
                </div>
            </div>

            <!-- TOMBOL -->
            <div class="flex justify-end gap-3 pt-2">

                <!-- BATAL -->
                <a href="{{ route('petugas.buku.index') }}"
                    class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition">
                    Batal
                </a>

                <!-- SUBMIT -->
                <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition shadow-sm">
                    <i class="fas fa-save mr-1"></i> Simpan Buku
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // PREVIEW GAMBAR
        function previewImage(input) {
            const preview = document.getElementById('preview');
            const removeBtn = document.getElementById('remove-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    removeBtn.classList.remove('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        // HAPUS PREVIEW
        function removeImage() {
            const input = document.getElementById('cover');
            const preview = document.getElementById('preview');
            const removeBtn = document.getElementById('remove-preview');

            input.value = '';
            preview.src = '';
            preview.classList.add('hidden');
            removeBtn.classList.add('hidden');
        }
    </script>
@endpush
