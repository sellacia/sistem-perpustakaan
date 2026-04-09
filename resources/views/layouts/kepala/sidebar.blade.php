<div class="w-64 h-screen text-white flex flex-col
bg-gradient-to-b from-blue-700 to-blue-500
fixed top-0 left-0">

    <!-- LOGO -->
    <div class="p-5 flex items-center gap-3 border-b border-blue-400">
        <i data-lucide="book-open" class="w-7 h-7"></i>
        <h2 class="text-lg font-semibold leading-tight">
            Perpustakaan<br>Digital
        </h2>
    </div>

    <!-- MENU -->
    <div class="flex-1 p-4 space-y-3 text-sm">

        <!-- Dashboard -->
        <a href="/kepala/dashboard"
            class="flex items-center gap-3 p-3 rounded-lg transition
            {{ request()->is('kepala/dashboard') ? 'bg-white/20' : 'hover:bg-white/10' }}">

            <i data-lucide="home" class="w-5 h-5"></i>
            <span>Dashboard</span>
        </a>

        <!-- Riwayat Peminjaman -->
        <a href="#"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/10 transition">

            <i data-lucide="file-text" class="w-5 h-5 opacity-80"></i>
            <span class="opacity-80">Riwayat Peminjaman</span>
        </a>

        <!-- Riwayat Pengembalian -->
        <a href="#"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/10 transition">

            <i data-lucide="file-text" class="w-5 h-5 opacity-80"></i>
            <span class="opacity-80">Riwayat Pengembalian</span>
        </a>

        <!-- 🔥 Buku -->
        <a href="/kepala/buku"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/10 transition">

            <i data-lucide="book" class="w-5 h-5 opacity-80"></i>
            <span class="opacity-80">Buku</span>
        </a>

        <!-- 🔥 Tambah Petugas -->
        <a href="/kepala/petugas/create"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/10 transition">

            <i data-lucide="user-plus" class="w-5 h-5 opacity-80"></i>
            <span class="opacity-80">Tambah Petugas</span>
        </a>

    </div>

    <!-- LOGOUT -->
    <div class="p-4 border-t border-blue-400">
        <a href="/logout"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/10 transition">

            <i data-lucide="log-out" class="w-5 h-5 opacity-80"></i>
            <span class="opacity-80">Logout</span>
        </a>
    </div>

</div>

<script>
    lucide.createIcons();
</script>
