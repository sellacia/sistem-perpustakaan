<div class="bg-white shadow p-4 flex justify-between items-center">

    <h1 class="font-semibold"></h1>

    <div class="flex items-center gap-2">
        <span>{{ auth()->user()->name ?? 'Petugas' }}</span>
        <div class="w-8 h-8 bg-red-400 rounded-full flex items-center justify-center text-white text-sm font-bold">
            {{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}
        </div>
    </div>

</div>
