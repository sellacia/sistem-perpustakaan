<!-- SIDEBAR -->
<div
    class="fixed left-0 top-0 z-50 flex h-screen w-72 flex-col overflow-hidden bg-[radial-gradient(circle_at_top,_rgba(56,189,248,0.28),_transparent_32%),linear-gradient(180deg,_#0f172a_0%,_#111827_48%,_#172554_100%)] text-white shadow-2xl">

    <!-- HEADER SIDEBAR -->
    <div class="border-b border-white/10 px-6 py-6">

        <div class="flex items-center gap-3">

            <div
                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 backdrop-blur overflow-hidden">
                <img src="{{ asset('assets/images/logo.jpeg') }}" class="w-full h-full object-cover">
            </div>
            <!-- NAMA APLIKASI -->
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-cyan-200/70">
                    Perpustakaan
                </p>
                <h1 class="mt-1 text-lg font-bold">
                    Digital
                </h1>
            </div>

        </div>
    </div>

    <!-- MENU -->
    <ul class="flex-1 space-y-1 overflow-y-auto px-4 pb-4 text-sm">

        @php
            // Data menu sidebar
            $menus = [
                [
                    'label' => 'Dashboard',
                    'icon' => 'fa-house',
                    'route' => route('anggota.dashboard'),
                    'active' => request()->is('anggota/dashboard'),
                ],
                [
                    'label' => 'Katalog Buku',
                    'icon' => 'fa-book',
                    'route' => route('anggota.buku'),
                    'active' => request()->is('anggota/buku*'),
                ],
                [
                    'label' => 'Riwayat Peminjaman',
                    'icon' => 'fa-clock-rotate-left',
                    'route' => route('anggota.peminjaman'),
                    'active' => request()->is('anggota/peminjaman*'),
                ],
                [
                    'label' => 'Pengembalian',
                    'icon' => 'fa-rotate-left',
                    'route' => route('anggota.pengembalian'),
                    'active' => request()->is('anggota/pengembalian*'),
                ],
                [
                    'label' => 'Riwayat & Denda',
                    'icon' => 'fa-receipt',
                    'route' => route('anggota.riwayat'),
                    'active' => request()->is('anggota/riwayat*'),
                ],
            ];
        @endphp

        <!-- LOOP MENU -->
        @foreach ($menus as $menu)
            <li>

                <!-- LINK MENU -->
                <a href="{{ $menu['route'] }}"
                    class="group flex items-center justify-between rounded-2xl px-4 py-3 transition
                    {{ $menu['active']
                        ? 'bg-white text-slate-900 shadow-lg shadow-cyan-950/20'
                        : 'text-slate-200 hover:bg-white/8 hover:text-white' }}">

                    <!-- ICON + LABEL -->
                    <span class="flex items-center gap-3 font-medium">

                        <!-- ICON -->
                        <i
                            class="fas {{ $menu['icon'] }}
                            {{ $menu['active'] ? 'text-sky-600' : 'text-cyan-200/80 group-hover:text-cyan-200' }}">
                        </i>

                        <!-- TEXT -->
                        {{ $menu['label'] }}
                    </span>

                    <!-- DOT ACTIVE -->
                    @if ($menu['active'])
                        <span class="h-2.5 w-2.5 rounded-full bg-sky-500"></span>
                    @endif

                </a>
            </li>
        @endforeach
    </ul>

    <!-- FOOTER (LOGOUT) -->
    <div class="border-t border-white/10 p-4">

        <!-- FORM LOGOUT -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <!-- BUTTON LOGOUT -->
            <button type="submit" data-confirm data-confirm-title="Logout sekarang?"
                data-confirm-message="Sesi anggota akan diakhiri dan Anda kembali ke halaman login."
                class="flex w-full items-center justify-center gap-3 rounded-2xl bg-white/10 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/15">

                <!-- ICON -->
                <i class="fas fa-right-from-bracket text-cyan-200"></i>

                <!-- TEXT -->
                Logout
            </button>
        </form>

    </div>
</div>
