<!DOCTYPE html>
<html lang="id">

<head>
    <!-- META DASAR -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- JUDUL HALAMAN -->
    <title>Anggota</title>

    <!-- TAILWIND CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FONT AWESOME (ICON) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSRF TOKEN (UNTUK FORM LARAVEL) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-slate-100 text-slate-800">

    <div>

        <!-- SIDEBAR -->
        @include('layouts.anggota.sidebar')

        <!-- CONTENT AREA -->
        <div class="min-h-screen lg:ml-72">

            <!-- HEADER -->
            @include('layouts.anggota.header')

            <!-- MAIN CONTENT -->
            <div class="p-6">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- MODAL KONFIRMASI AKSI -->
    <div id="action-modal" class="hidden fixed inset-0 z-[100] items-center justify-center bg-slate-950/55 p-4">

        <!-- BOX MODAL -->
        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">

            <!-- ICON + TEXT -->
            <div class="flex items-start gap-4">

                <!-- ICON -->
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-100 text-sky-600">
                    <i class="fas fa-circle-question"></i>
                </div>

                <!-- TEXT -->
                <div class="flex-1">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">
                        Konfirmasi Aksi
                    </p>

                    <!-- JUDUL MODAL -->
                    <h3 id="action-modal-title" class="mt-2 text-lg font-bold text-slate-900">
                        Lanjutkan proses?
                    </h3>

                    <!-- PESAN MODAL -->
                    <p id="action-modal-message" class="mt-2 text-sm leading-6 text-slate-500">
                        Pastikan aksi ini sudah sesuai.
                    </p>
                </div>
            </div>

            <!-- TOMBOL -->
            <div class="mt-6 flex justify-end gap-3">

                <!-- BATAL -->
                <button type="button" id="action-modal-cancel"
                    class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Batal
                </button>

                <!-- KONFIRMASI -->
                <button type="button" id="action-modal-confirm"
                    class="rounded-2xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700">
                    Ya, lanjutkan
                </button>
            </div>
        </div>
    </div>

    <script>
        (() => {

            // AMBIL ELEMENT MODAL
            const actionModal = document.getElementById('action-modal');
            const confirmButton = document.getElementById('action-modal-confirm');
            const cancelButton = document.getElementById('action-modal-cancel');
            const titleNode = document.getElementById('action-modal-title');
            const messageNode = document.getElementById('action-modal-message');

            // ACTION YANG AKAN DIJALANKAN
            let pendingAction = null;

            // FUNCTION TUTUP MODAL
            const closeActionModal = () => {
                actionModal.classList.add('hidden');
                actionModal.classList.remove('flex');
                pendingAction = null;
            };

            // FUNCTION BUKA MODAL
            const openActionModal = (title, message, action) => {
                titleNode.textContent = title || 'Lanjutkan proses?';
                messageNode.textContent = message || 'Pastikan aksi ini sudah sesuai.';
                pendingAction = action;
                actionModal.classList.remove('hidden');
                actionModal.classList.add('flex');
            };

            // EVENT KLIK KONFIRMASI
            confirmButton.addEventListener('click', () => {
                if (pendingAction) pendingAction();
                closeActionModal();
            });

            // EVENT BATAL
            cancelButton.addEventListener('click', closeActionModal);

            // KLIK BACKGROUND = TUTUP
            actionModal.addEventListener('click', (event) => {
                if (event.target === actionModal) closeActionModal();
            });

            // HANDLE SEMUA ELEMENT YANG ADA data-confirm
            document.addEventListener('click', (event) => {

                const trigger = event.target.closest('[data-confirm]');
                if (!trigger) return;

                event.preventDefault();

                // AMBIL DATA
                const title = trigger.dataset.confirmTitle;
                const message = trigger.dataset.confirmMessage;
                const href = trigger.getAttribute('href');
                const form = trigger.closest('form');

                // BUKA MODAL
                openActionModal(title, message, () => {

                    // JIKA BUTTON FORM
                    if (form && (trigger.tagName === 'BUTTON' || trigger.type === 'submit')) {
                        form.submit();
                        return;
                    }

                    // JIKA LINK
                    if (href) window.location.href = href;
                });
            });

            // AUTO DISMISS ALERT
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
