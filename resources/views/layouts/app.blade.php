<div class="flex">
    @vite('resources/css/app.css')

    <!-- SIDEBAR -->
    <div class="w-64 h-screen bg-blue-700 text-white fixed">
        @include('layouts.sidebar')
    </div>

    <!-- CONTENT -->
    <div class="ml-64 flex-1 min-w-0 overflow-x-hidden">

        <!-- HEADER -->
        <div class="bg-white p-4 shadow flex justify-end">
            @include('layouts.header')
        </div>

        <!-- ISI -->
        <div class="p-6 w-full max-w-none">
            @yield('content')
        </div>

        <script src="https://unpkg.com/lucide@latest"></script>
        <script>
            lucide.createIcons();
        </script>

    </div>

</div>
