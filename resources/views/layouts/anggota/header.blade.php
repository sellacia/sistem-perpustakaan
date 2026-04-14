<div class="flex items-center gap-3">
    <span>{{ auth()->user()->name ?? 'Anggota' }}</span>
    <div class="w-8 h-8 bg-red-400 rounded-full flex items-center justify-center text-white text-sm font-bold">
        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
    </div>
</div>
