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
            <a href="/kepala/dashboard"
                class="flex items-center gap-3 p-3 rounded-lg transition text-white
                {{ request()->is('kepala/dashboard') ? 'bg-blue-800 shadow-md' : 'hover:bg-blue-600' }}">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
        </li>

        <!-- Data Buku -->
        <li>
            <a href="/kepala/buku"
                class="flex items-center gap-3 p-3 rounded-lg transition text-white
                {{ request()->is('kepala/buku*') ? 'bg-blue-800 shadow-md' : 'hover:bg-blue-600' }}">
                <i class="fas fa-book"></i>
                Katalog Buku
            </a>
        </li>

        <!-- Laporan -->
        <li>
            <a href="/kepala/laporan"
                class="flex items-center gap-3 p-3 rounded-lg transition text-white
                {{ request()->is('kepala/laporan*') ? 'bg-blue-800 shadow-md' : 'hover:bg-blue-600' }}">
                <i class="fas fa-file-alt"></i>
                Laporan
            </a>
        </li>

        <!-- Data Petugas -->
        <li>
            <a href="{{ route('kepala.petugas.index') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition text-white
        {{ request()->routeIs('kepala.petugas.index') ? 'bg-blue-800 shadow-md' : 'hover:bg-blue-600' }}">

                <i class="fas fa-user-tie"></i>
                <span>Data Petugas</span>
            </a>
        </li>

    </ul>

    <!-- LOGOUT -->
    <div class="p-4 border-t border-blue-400">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 p-3 rounded-lg hover:bg-red-500">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </button>
        </form>
    </div>

</div>
