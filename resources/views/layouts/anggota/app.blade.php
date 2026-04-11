<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Anggota</title>
</head>

<body>
    <div class="flex">
        @vite('resources/css/app.css')

        <!-- SIDEBAR -->
        <div class="w-64 h-screen bg-blue-700 text-white fixed">
            @include('layouts.anggota.sidebar')
        </div>

        <!-- CONTENT -->
        <div class="ml-64 flex-1 min-w-0 overflow-x-hidden">

            <!-- HEADER -->
            <div class="bg-white p-4 shadow flex justify-end">
                @include('layouts.anggota.header')
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
        <script>
            function openLogoutModal() {
                document.getElementById('logoutModal').classList.remove('hidden');
            }

            function closeLogoutModal() {
                document.getElementById('logoutModal').classList.add('hidden');
            }
        </script>

    </div>

</body>

</html>
