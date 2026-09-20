<aside
    class="fixed top-3 bottom-3 left-3 z-50 w-64 bg-gradient-to-b from-yellow-600 to-yellow-700 text-white rounded-2xl shadow-xl overflow-hidden border border-yellow-500/30">
    <!-- Brand -->
    <div
        class="sticky top-0 z-10 flex items-center justify-between h-16 px-4 bg-yellow-600/95 backdrop-blur-sm border-b border-yellow-500/40">

        <div class="flex items-center space-x-3">
            <div
                class="w-9 h-9 bg-white/15 rounded-xl flex items-center justify-center border border-white/10 shadow-sm">
                <i class="fas fa-plane-departure text-white text-sm"></i>
            </div>

            <div>
                <span class="block text-base font-bold tracking-wide text-white">
                    Arrum Tour
                </span>
                <span class="block text-[10px] text-yellow-100/70 tracking-wider">
                    UMROH & TRAVEL
                </span>
            </div>
        </div>

    </div>

    <!-- Menu -->
    <nav
        class="h-[calc(100%-64px)] overflow-y-auto px-3 py-4 pb-24 scrollbar-thin scrollbar-thumb-yellow-400/40 scrollbar-track-transparent">

        <ul class="space-y-1">

            <!-- Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200
                {{ request()->routeIs('dashboard')
                    ? 'bg-white/15 text-white shadow-sm'
                    : 'text-yellow-100 hover:bg-white/10 hover:text-white' }}">

                    <i class="fas fa-chart-pie w-5 text-center text-sm"></i>

                    <span class="ml-3 text-sm font-medium">
                        Dashboard
                    </span>
                </a>
            </li>


            <!-- Master Data -->
            <li x-data="{ open: {{ request()->routeIs('master.*') ? 'true' : 'false' }} }">

                <a @click="open = !open"
                    class="flex items-center justify-between px-3 py-2.5 rounded-xl cursor-pointer transition-all duration-200
                {{ request()->routeIs('master.*')
                    ? 'bg-white/15 text-white shadow-sm'
                    : 'text-yellow-100 hover:bg-white/10 hover:text-white' }}">

                    <div class="flex items-center">
                        <i class="fas fa-database w-5 text-center text-sm"></i>

                        <span class="ml-3 text-sm font-medium">
                            Master Data
                        </span>
                    </div>

                    <i class="fas fa-chevron-down text-[10px] transition-transform duration-200"
                        :class="open ? 'rotate-180' : ''"></i>
                </a>

                <ul x-show="open" x-transition class="ml-3 mt-1 space-y-1">

                    <!-- Produk Paket -->
                    <li>
                        <a href="{{ route('master.produk.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200
                        {{ request()->routeIs('master.produk.*')
                            ? 'bg-white/15 text-white'
                            : 'text-yellow-200 hover:bg-white/10 hover:text-white' }}">

                            <i class="fas fa-box w-5 text-center text-xs"></i>
                            <span class="ml-3">Produk Paket</span>
                        </a>
                    </li>

                    <!-- Metode Pembayaran -->
                    <li>
                        <a href="{{ route('master.metode-pembayaran.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200
                        {{ request()->routeIs('master.metode-pembayaran.*')
                            ? 'bg-white/15 text-white'
                            : 'text-yellow-200 hover:bg-white/10 hover:text-white' }}">

                            <i class="fas fa-credit-card w-5 text-center text-xs"></i>
                            <span class="ml-3">Metode Pembayaran</span>
                        </a>
                    </li>

                    <!-- Kategori Pengeluaran -->
                    <li>
                        <a href="{{ route('master.kategori-pengeluaran.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200
                        {{ request()->routeIs('master.kategori-pengeluaran.*')
                            ? 'bg-white/15 text-white'
                            : 'text-yellow-200 hover:bg-white/10 hover:text-white' }}">

                            <i class="fas fa-tags w-5 text-center text-xs"></i>
                            <span class="ml-3">Kategori Pengeluaran</span>
                        </a>
                    </li>

                    <!-- Status Keberangkatan -->
                    <li>
                        <a href="{{ route('master.status-keberangkatan.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200
                        {{ request()->routeIs('master.status-keberangkatan.*')
                            ? 'bg-white/15 text-white'
                            : 'text-yellow-200 hover:bg-white/10 hover:text-white' }}">

                            <i class="fas fa-flag-checkered w-5 text-center text-xs"></i>
                            <span class="ml-3">Status Keberangkatan</span>
                        </a>
                    </li>

                    <!-- Jenis Transaksi -->
                    <li>
                        <a href="{{ route('master.jenis-transaksi.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200
                        {{ request()->routeIs('master.jenis-transaksi.*')
                            ? 'bg-white/15 text-white'
                            : 'text-yellow-200 hover:bg-white/10 hover:text-white' }}">

                            <i class="fas fa-exchange-alt w-5 text-center text-xs"></i>
                            <span class="ml-3">Jenis Transaksi</span>
                        </a>
                    </li>

                    <!-- Hotel -->
                    <li>
                        <a href="{{ route('master.hotel.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200
                        {{ request()->routeIs('master.hotel.*')
                            ? 'bg-white/15 text-white'
                            : 'text-yellow-200 hover:bg-white/10 hover:text-white' }}">

                            <i class="fas fa-hotel w-5 text-center text-xs"></i>
                            <span class="ml-3">Hotel</span>
                        </a>
                    </li>

                    <!-- Kota Asal -->
                    <li>
                        <a href="{{ route('master.kota-asal.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200
                        {{ request()->routeIs('master.kota-asal.*')
                            ? 'bg-white/15 text-white'
                            : 'text-yellow-200 hover:bg-white/10 hover:text-white' }}">

                            <i class="fas fa-city w-5 text-center text-xs"></i>
                            <span class="ml-3">Kota Asal</span>
                        </a>
                    </li>

                    <!-- Maskapai -->
                    <li>
                        <a href="{{ route('master.maskapai.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200
                        {{ request()->routeIs('master.maskapai.*')
                            ? 'bg-white/15 text-white'
                            : 'text-yellow-200 hover:bg-white/10 hover:text-white' }}">

                            <i class="fas fa-plane w-5 text-center text-xs"></i>
                            <span class="ml-3">Maskapai</span>
                        </a>
                    </li>

                    <!-- Diskon -->
                    <li>
                        <a href="{{ route('master.diskon.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200
                        {{ request()->routeIs('master.diskon.*')
                            ? 'bg-white/15 text-white'
                            : 'text-yellow-200 hover:bg-white/10 hover:text-white' }}">

                            <i class="fas fa-percent w-5 text-center text-xs"></i>
                            <span class="ml-3">Diskon</span>
                        </a>
                    </li>

                    <!-- Perlengkapan -->
                    <li>
                        <a href="{{ route('master.perlengkapan.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200
                        {{ request()->routeIs('master.perlengkapan.*')
                            ? 'bg-white/15 text-white'
                            : 'text-yellow-200 hover:bg-white/10 hover:text-white' }}">

                            <i class="fas fa-suitcase w-5 text-center text-xs"></i>
                            <span class="ml-3">Perlengkapan</span>
                        </a>
                    </li>

                    <!-- Paket Tour -->
                    <li>
                        <a href="{{ route('master.paket-tour.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200
                        {{ request()->routeIs('master.paket-tour.*')
                            ? 'bg-white/15 text-white'
                            : 'text-yellow-200 hover:bg-white/10 hover:text-white' }}">

                            <i class="fas fa-route w-5 text-center text-xs"></i>
                            <span class="ml-3">Paket Tour</span>
                        </a>
                    </li>

                    <!-- Setting Dokumen -->
                    <li>
                        <a href="{{ route('master.dokumen.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200
                        {{ request()->routeIs('master.dokumen.*')
                            ? 'bg-white/15 text-white'
                            : 'text-yellow-200 hover:bg-white/10 hover:text-white' }}">

                            <i class="fas fa-file-image w-5 text-center text-xs"></i>
                            <span class="ml-3">Setting Dokumen</span>
                        </a>
                    </li>

                </ul>
            </li>


            <!-- Transaksional -->
            <li x-data="{ open: {{ request()->routeIs('transaksional.*') ? 'true' : 'false' }} }">

                <a @click="open = !open"
                    class="flex items-center justify-between px-3 py-2.5 rounded-xl cursor-pointer transition-all duration-200
                {{ request()->routeIs('transaksional.*')
                    ? 'bg-white/15 text-white shadow-sm'
                    : 'text-yellow-100 hover:bg-white/10 hover:text-white' }}">

                    <div class="flex items-center">
                        <i class="fas fa-exchange-alt w-5 text-center text-sm"></i>

                        <span class="ml-3 text-sm font-medium">
                            Transaksional
                        </span>
                    </div>

                    <i class="fas fa-chevron-down text-[10px] transition-transform duration-200"
                        :class="open ? 'rotate-180' : ''"></i>
                </a>

                <ul x-show="open" x-transition class="ml-3 mt-1 space-y-1">

                    <!-- Jamaah -->
                    <li>
                        <a href="{{ route('transaksional.jamaah.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200
                        {{ request()->routeIs('transaksional.jamaah.*')
                            ? 'bg-white/15 text-white'
                            : 'text-yellow-200 hover:bg-white/10 hover:text-white' }}">

                            <i class="fas fa-user w-5 text-center text-xs"></i>
                            <span class="ml-3">Jamaah</span>
                        </a>
                    </li>

                    <!-- Keluarga -->
                    <li>
                        <a href="{{ route('transaksional.keluarga.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200
                        {{ request()->routeIs('transaksional.keluarga.*')
                            ? 'bg-white/15 text-white'
                            : 'text-yellow-200 hover:bg-white/10 hover:text-white' }}">

                            <i class="fas fa-users w-5 text-center text-xs"></i>
                            <span class="ml-3">Keluarga</span>
                        </a>
                    </li>

                    <!-- Departure -->
                    <li>
                        <a href="{{ route('transaksional.departure.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200
                        {{ request()->routeIs('transaksional.departure.*')
                            ? 'bg-white/15 text-white'
                            : 'text-yellow-200 hover:bg-white/10 hover:text-white' }}">

                            <i class="fas fa-calendar-plus w-5 text-center text-xs"></i>
                            <span class="ml-3">Keberangkatan</span>
                        </a>
                    </li>

                </ul>
            </li>

        </ul>
    </nav>


    <!-- Logout -->
    <div
        class="absolute bottom-0 left-0 right-0 px-3 py-3 bg-gradient-to-t from-yellow-700 via-yellow-700/95 to-transparent">

        <div class="border-t border-yellow-500/30 pt-2">

            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200
            text-red-200 hover:bg-red-500/20 hover:text-white">

                <i class="fas fa-sign-out-alt w-5 text-center text-sm"></i>

                <span class="ml-3 text-sm font-medium">
                    Logout
                </span>
            </a>

            <form id="logout-form" action="#" method="POST" class="hidden">
                @csrf
            </form>

        </div>
    </div>
    ```

</aside>
