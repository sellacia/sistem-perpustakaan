<div class="bg-white p-4 shadow flex justify-between items-center">

    <h1 class="text-lg font-semibold text-gray-700"></h1>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-600">
            {{ auth()->user()->name ?? 'Kepala Perpustakaan' }}
        </span>

        <div class="w-8 h-8 bg-red-400 rounded-full flex items-center justify-center text-white text-sm font-bold">
            {{ strtoupper(substr(auth()->user()->name ?? 'K', 0, 1)) }}
        </div>
    </div>

</div>
