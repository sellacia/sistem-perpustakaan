<div id="logoutModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

    <div class="bg-white rounded-lg shadow-lg w-[400px] relative p-6 text-center">

        <button onclick="closeLogoutModal()"
            class="absolute top-2 right-3 text-gray-400 text-xl">
            &times;
        </button>

        <h2 class="text-lg font-semibold mb-6">
            Apakah Anda yakin ingin keluar dari sistem?
        </h2>

        <div class="flex justify-center gap-4">
            <button onclick="closeLogoutModal()"
                class="bg-gray-300 px-6 py-2 rounded">
                Batal
            </button>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="bg-red-600 text-white px-6 py-2 rounded">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
