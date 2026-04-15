<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anggota</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-slate-100 text-slate-800">
<div>
    @include('layouts.anggota.sidebar')

    <div class="min-h-screen lg:ml-72">
        @include('layouts.anggota.header')

        <div class="p-6">
            @yield('content')
        </div>
    </div>
</div>

<div id="action-modal" class="hidden fixed inset-0 z-[100] items-center justify-center bg-slate-950/55 p-4">
    <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
        <div class="flex items-start gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-100 text-sky-600">
                <i class="fas fa-circle-question"></i>
            </div>
            <div class="flex-1">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Konfirmasi Aksi</p>
                <h3 id="action-modal-title" class="mt-2 text-lg font-bold text-slate-900">Lanjutkan proses?</h3>
                <p id="action-modal-message" class="mt-2 text-sm leading-6 text-slate-500">Pastikan aksi ini sudah sesuai.</p>
            </div>
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <button type="button" id="action-modal-cancel" class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
            <button type="button" id="action-modal-confirm" class="rounded-2xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700">Ya, lanjutkan</button>
        </div>
    </div>
</div>

<script>
(() => {
    const actionModal = document.getElementById('action-modal');
    const confirmButton = document.getElementById('action-modal-confirm');
    const cancelButton = document.getElementById('action-modal-cancel');
    const titleNode = document.getElementById('action-modal-title');
    const messageNode = document.getElementById('action-modal-message');
    let pendingAction = null;

    const closeActionModal = () => {
        actionModal.classList.add('hidden');
        actionModal.classList.remove('flex');
        pendingAction = null;
    };

    const openActionModal = (title, message, action) => {
        titleNode.textContent = title || 'Lanjutkan proses?';
        messageNode.textContent = message || 'Pastikan aksi ini sudah sesuai.';
        pendingAction = action;
        actionModal.classList.remove('hidden');
        actionModal.classList.add('flex');
    };

    confirmButton.addEventListener('click', () => {
        if (pendingAction) pendingAction();
        closeActionModal();
    });

    cancelButton.addEventListener('click', closeActionModal);
    actionModal.addEventListener('click', (event) => {
        if (event.target === actionModal) closeActionModal();
    });

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-confirm]');
        if (!trigger) return;

        event.preventDefault();
        const title = trigger.dataset.confirmTitle;
        const message = trigger.dataset.confirmMessage;
        const href = trigger.getAttribute('href');
        const form = trigger.closest('form');

        openActionModal(title, message, () => {
            if (form && (trigger.tagName === 'BUTTON' || trigger.type === 'submit')) {
                form.submit();
                return;
            }
            if (href) window.location.href = href;
        });
    });

    document.querySelectorAll('[data-auto-dismiss]').forEach((alert) => {
        setTimeout(() => {
            alert.classList.add('opacity-0', '-translate-y-2');
            setTimeout(() => alert.remove(), 250);
        }, 3200);
    });
})();
</script>
</body>
</html>
