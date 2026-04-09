<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kepala Perpustakaan</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-100">

    <div class="flex">

        {{-- SIDEBAR --}}
        @include('layouts.kepala.sidebar')

        <div class="flex-1 ml-64">

            {{-- HEADER --}}
            @include('layouts.kepala.header')

            {{-- CONTENT --}}
            <main class="p-6">
                @yield('content')
            </main>

        </div>

    </div>

</body>
</html>
