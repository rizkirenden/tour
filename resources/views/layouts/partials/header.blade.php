<header class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-visible">

    <div class="flex items-center justify-between px-5 md:px-6 py-3.5">

        <!-- Left: Page Title -->
        <div class="flex items-center space-x-4 min-w-0">

            <!-- Sidebar Toggle -->
            <button type="button" @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden w-9 h-9
                       flex items-center justify-center
                       rounded-lg
                       text-gray-500
                       hover:bg-yellow-50
                       hover:text-yellow-600
                       transition-all duration-200">

                <i class="fas fa-bars text-lg"></i>

            </button>


            <!-- Title -->
            <div class="min-w-0">

                <h2 class="text-lg font-semibold text-gray-800 truncate">
                    @yield('page-title', 'Dashboard')
                </h2>

                <p class="hidden sm:block text-[11px] text-gray-400 mt-0.5">
                    Arrum Tour Management System
                </p>

            </div>

        </div>


        <!-- Right Header -->
        <div class="flex items-center gap-3 md:gap-5">

            <!-- Real-time WITA Clock -->
            <div x-data="{
                time: '--:--:--',
                date: '',
                interval: null,
            
                init() {
                    this.updateClock();
            
                    this.interval = setInterval(() => {
                        this.updateClock();
                    }, 1000);
                },
            
                updateClock() {
                    const now = new Date();
            
                    this.time = new Intl.DateTimeFormat('id-ID', {
                        timeZone: 'Asia/Makassar',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false
                    }).format(now);
            
                    this.date = new Intl.DateTimeFormat('id-ID', {
                        timeZone: 'Asia/Makassar',
                        weekday: 'long',
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    }).format(now);
                }
            }" class="hidden sm:flex flex-col items-end">


                <!-- Clock -->
                <div class="flex items-center gap-2">

                    <!-- Clock Icon -->
                    <div
                        class="w-7 h-7 rounded-lg
                                bg-yellow-50
                                flex items-center justify-center">

                        <i class="fas fa-clock text-yellow-600 text-xs"></i>

                    </div>


                    <!-- Time -->
                    <span x-text="time"
                        class="text-base md:text-lg
                               font-bold
                               text-gray-800
                               tabular-nums">
                    </span>


                    <!-- WITA -->
                    <span
                        class="text-[10px]
                                 font-bold
                                 px-1.5 py-0.5
                                 rounded-md
                                 bg-yellow-50
                                 text-yellow-700">

                        WITA

                    </span>

                </div>


                <!-- Date -->
                <span x-text="date"
                    class="text-[11px]
                           text-gray-400
                           capitalize
                           mt-0.5">
                </span>

            </div>


            <!-- Notification -->
            <div class="relative" x-data="{ open: false }">

                <!-- Notification Button -->
                <button type="button" @click="open = !open"
                    class="relative w-9 h-9
                           flex items-center justify-center
                           rounded-lg
                           text-gray-500
                           hover:bg-yellow-50
                           hover:text-yellow-600
                           transition-all duration-200"
                    aria-label="Notifikasi" :aria-expanded="open">

                    <i class="fas fa-bell text-base"></i>


                    <!-- Badge -->
                    <span
                        class="absolute top-1 right-1
                                 w-4 h-4
                                 bg-red-500
                                 text-white
                                 text-[9px]
                                 font-bold
                                 rounded-full
                                 flex items-center justify-center
                                 border-2 border-white">

                        3

                    </span>

                </button>


                <!-- Notification Dropdown -->
                <div x-show="open" x-cloak x-transition @click.outside="open = false"
                    @keydown.escape.window="open = false"
                    class="absolute right-0 mt-3
                           w-72 sm:w-80
                           bg-white
                           rounded-xl
                           shadow-xl
                           border border-gray-200
                           z-[100]">


                    <!-- Dropdown Header -->
                    <div
                        class="flex items-center justify-between
                                px-4 py-3.5
                                border-b border-gray-100">

                        <div>

                            <h4 class="font-semibold text-gray-800 text-sm">
                                Notifikasi
                            </h4>

                            <p class="text-[10px] text-gray-400 mt-0.5">
                                Pemberitahuan terbaru
                            </p>

                        </div>


                        <!-- Close -->
                        <button type="button" @click="open = false"
                            class="w-7 h-7
                                   rounded-lg
                                   flex items-center justify-center
                                   text-gray-400
                                   hover:bg-gray-100
                                   hover:text-gray-600">

                            <i class="fas fa-times text-xs"></i>

                        </button>

                    </div>


                    <!-- Notification List -->
                    <div class="max-h-64 overflow-y-auto">


                        <!-- Notification 1 -->
                        <a href="#"
                            class="flex items-start
                                   px-4 py-3
                                   hover:bg-gray-50
                                   transition-colors
                                   border-b border-gray-100">

                            <div
                                class="w-2 h-2
                                        bg-blue-500
                                        rounded-full
                                        mt-1.5 mr-3
                                        flex-shrink-0">
                            </div>

                            <div>

                                <p class="text-sm text-gray-800">
                                    Validasi pembayaran menunggu
                                </p>

                                <p class="text-[11px] text-gray-400 mt-1">
                                    2 menit lalu
                                </p>

                            </div>

                        </a>


                        <!-- Notification 2 -->
                        <a href="#"
                            class="flex items-start
                                   px-4 py-3
                                   hover:bg-gray-50
                                   transition-colors
                                   border-b border-gray-100">

                            <div
                                class="w-2 h-2
                                        bg-green-500
                                        rounded-full
                                        mt-1.5 mr-3
                                        flex-shrink-0">
                            </div>

                            <div>

                                <p class="text-sm text-gray-800">
                                    Pembayaran baru masuk
                                </p>

                                <p class="text-[11px] text-gray-400 mt-1">
                                    3 jam lalu
                                </p>

                            </div>

                        </a>


                        <!-- Notification 3 -->
                        <a href="#"
                            class="flex items-start
                                   px-4 py-3
                                   hover:bg-gray-50
                                   transition-colors">

                            <div
                                class="w-2 h-2
                                        bg-yellow-500
                                        rounded-full
                                        mt-1.5 mr-3
                                        flex-shrink-0">
                            </div>

                            <div>

                                <p class="text-sm text-gray-800">
                                    Data jamaah perlu diperiksa
                                </p>

                                <p class="text-[11px] text-gray-400 mt-1">
                                    5 jam lalu
                                </p>

                            </div>

                        </a>

                    </div>


                    <!-- Dropdown Footer -->
                    <div class="px-4 py-2.5
                                border-t border-gray-100">

                        <a href="#"
                            class="text-xs
                                   font-medium
                                   text-yellow-600
                                   hover:text-yellow-700">

                            Lihat semua notifikasi

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</header>
