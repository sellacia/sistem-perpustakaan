<div class="p-5">

    <!-- Logo / Title -->
    <h2 class="text-xl font-bold mb-8">Perpustakaan Digital</h2>

    <!-- Menu -->
    <ul class="space-y-3 text-sm">

        <!-- Dashboard -->
        <a href="/anggota/dashboard"
            class="flex items-center gap-3 p-3 rounded-xl transition
       {{ request()->is('dashboard') ? 'bg-white text-blue-600' : 'text-white hover:bg-blue-500' }}">
            <i data-lucide="home" class="w-5 h-5"></i> <span>Dashboard</span>
        </a>

        <!-- Buku -->
        <a href="/anggota/buku"
            class="flex items-center gap-3 p-3 rounded-xl transition
       {{ request()->is('buku*') ? 'bg-white text-blue-600' : 'text-white hover:bg-blue-500' }}">
            <i data-lucide="book-open" class="w-5 h-5"></i>
            <span>Buku</span>
        </a>

        <!-- Pengembalian -->
        <a href="/anggota/pengembalian"
            class="flex items-center gap-3 p-3 rounded-xl transition
       {{ request()->is('pengembalian*') ? 'bg-white text-blue-600' : 'text-white hover:bg-blue-500' }}">
            <i data-lucide="rotate-ccw" class="w-5 h-5"></i>
            <span>Pengembalian</span>
        </a>

        <!-- Denda -->
        <a href="/anggota/denda"
            class="flex items-center gap-3 p-3 rounded-xl transition
       {{ request()->is('denda*') ? 'bg-white text-blue-600' : 'text-white hover:bg-blue-500' }}">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            <span>Denda</span>
        </a>

        <!-- Logout -->
        <button onclick="alert('klik'); openLogoutModal()"
            class="flex items-center gap-3 p-3 w-full text-left text-white hover:bg-red-500 rounded-xl">

            <i data-lucide="log-out" class="w-5 h-5"></i>
            <span>Logout</span>
        </button>
        <script>
            function openLogoutModal() {
                document.getElementById('logoutModal').classList.remove('hidden');
            }

            function closeLogoutModal() {
                document.getElementById('logoutModal').classList.add('hidden');
            }
        </script>

</div>
