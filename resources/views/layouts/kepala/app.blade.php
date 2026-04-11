<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kepala Perpustakaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gray-100">

<div class="flex">

    <!-- SIDEBAR -->
    @include('layouts.kepala.sidebar')

    <!-- CONTENT -->
    <div class="flex-1 ml-64">

        <!-- HEADER -->
        @include('layouts.kepala.header')

        <!-- MAIN -->
        <div class="p-6">
            @yield('content')
        </div>

    </div>

</div>

</body>
</html>
