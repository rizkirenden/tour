<header class="bg-white border-b border-gray-200">
    <div class="flex items-center justify-between px-4 md:px-6 py-3">

        <!-- Page Title -->
        <div class="flex items-center space-x-4">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600 hover:text-yellow-600">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <h2 class="text-lg font-semibold text-gray-800">
                @yield('page-title', 'Dashboard')
            </h2>
        </div>

        <!-- Notification -->
        <div class="relative" x-data="{ open: false }">

            <!-- Notification Button -->
            <button type="button" @click="open = !open"
                class="relative text-gray-500 hover:text-yellow-600 focus:outline-none">

                <i class="fas fa-bell text-xl"></i>

                <!-- Badge -->
                <span
                    class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                    3
                </span>
            </button>

            <!-- Notification Dropdown -->
            <div x-show="open" x-cloak x-transition @click.outside="open = false"
                class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-xl border border-gray-200 z-50">

                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200">
                    <h4 class="font-semibold text-gray-800">
                        Notifikasi
                    </h4>

                    <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Notifications -->
                <div class="max-h-64 overflow-y-auto">

                    <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50 border-b border-gray-100">

                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5 mr-3 flex-shrink-0"></div>

                        <div>
                            <p class="text-sm text-gray-800">
                                Validasi pembayaran menunggu
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                2 menit lalu
                            </p>
                        </div>
                    </a>

                    <a href="#" class="flex items-start px-4 py-3 hover:bg-gray-50 border-b border-gray-100">

                        <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5 mr-3 flex-shrink-0"></div>

                        <div>
                            <p class="text-sm text-gray-800">
                                Pembayaran baru masuk
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                3 jam lalu
                            </p>
                        </div>
                    </a>

                </div>

            </div>
        </div>

    </div>
</header>
