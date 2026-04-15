<div class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 px-6 py-4 shadow-sm backdrop-blur">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Portal Anggota</p>
            <h1 class="mt-1 text-lg font-bold text-slate-900">Area Member Perpustakaan</h1>
        </div>
        <div class="flex items-center gap-3 self-start rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 lg:self-auto">
            <div class="text-right">
                <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name ?? 'Anggota' }}</p>
                <p class="text-xs text-slate-500">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
            </div>
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-500 to-cyan-500 text-sm font-bold text-white shadow-sm">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
        </div>
    </div>
</div>
