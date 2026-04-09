<div class="w-64 h-screen text-white flex flex-col
bg-gradient-to-b from-blue-700 to-blue-500
fixed top-0 left-0 z-50">

    <!-- LOGO -->
    <div class="p-5 flex items-center gap-3 border-b border-blue-400">
        <i class="fas fa-book-open text-2xl"></i>
        <div>
            <h1 class="font-bold leading-4">Perpustakaan</h1>
            <span class="text-sm">Digital</span>
        </div>
    </div>

    <!-- MENU -->
    <ul class="flex-1 p-4 space-y-2 text-sm overflow-y-auto">

        <!-- Dashboard -->
        <li>
            <a href="/petugas/dashboard"
                class="flex items-center gap-3 p-3 rounded-lg transition
                {{ request()->is('petugas/dashboard') ? 'bg-blue-800 text-white shadow-md' : 'hover:bg-blue-600 text-white' }}">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
        </li>

        <!-- Kelola Buku -->
        <li>
            <a href="/petugas/buku"
                class="flex items-center gap-3 p-3 rounded-lg transition
                {{ request()->is('petugas/buku*') ? 'bg-blue-800 text-white shadow-md' : 'hover:bg-blue-600 text-white' }}">
                <i class="fas fa-book"></i>
                Kelola Buku
            </a>
        </li>

        <!-- Peminjaman -->
        <li>
            <a href="/petugas/peminjaman"
                class="flex items-center gap-3 p-3 rounded-lg transition
                {{ request()->is('petugas/peminjaman*') ? 'bg-blue-800 text-white shadow-md' : 'hover:bg-blue-600 text-white' }}">
                <i class="fas fa-hand-holding"></i>
                Proses Peminjaman
            </a>
        </li>

        <!-- Pengembalian -->
        <li>
            <a href="/petugas/pengembalian"
                class="flex items-center gap-3 p-3 rounded-lg transition
                {{ request()->is('petugas/pengembalian*') ? 'bg-blue-800 text-white shadow-md' : 'hover:bg-blue-600 text-white' }}">
                <i class="fas fa-undo"></i>
                Proses Pengembalian
            </a>
        </li>

        <!-- Denda -->
        <li>
            <a href="/petugas/denda"
                class="flex items-center gap-3 p-3 rounded-lg transition
                {{ request()->is('petugas/denda*') ? 'bg-blue-800 text-white shadow-md' : 'hover:bg-blue-600 text-white' }}">
                <i class="fas fa-money-bill-wave"></i>
                Kelola Denda
            </a>
        </li>

        <!-- Laporan -->
        <li>
            <a href="/petugas/laporan"
                class="flex items-center gap-3 p-3 rounded-lg transition
                {{ request()->is('petugas/laporan*') ? 'bg-blue-800 text-white shadow-md' : 'hover:bg-blue-600 text-white' }}">
                <i class="fas fa-file-alt"></i>
                Kelola Laporan
            </a>
        </li>

        <!-- Data Anggota -->
        <li>
            <a href="/petugas/anggota"
                class="flex items-center gap-3 p-3 rounded-lg transition
        {{ request()->is('petugas/anggota*') ? 'bg-blue-800 text-white shadow-md' : 'hover:bg-blue-600 text-white' }}">
                <i class="fas fa-users"></i>
                Data Anggota
            </a>
        </li>

    </ul>

    <!-- LOGOUT -->
    <div class="p-4 border-t border-blue-400">
        <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-600">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </a>
    </div>

</div>
