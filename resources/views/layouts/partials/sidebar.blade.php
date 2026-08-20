<aside
    class="fixed inset-y-0 left-0 z-50 bg-gradient-to-b from-yellow-600 to-yellow-700 text-white shadow-lg overflow-y-auto w-64">
    <!-- Brand -->
    <div
        class="sticky top-0 bg-yellow-600/95 z-10 flex items-center justify-between h-16 px-4 border-b border-yellow-500/50">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-plane-departure text-white text-sm"></i>
            </div>
            <span class="text-lg font-bold tracking-wide text-white">Arrum Tour</span>
        </div>
    </div>

    <!-- Menu -->
    <nav class="px-3 py-4">
        <ul class="space-y-1">
            <li>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-yellow-500/50 text-white' : 'text-yellow-100 hover:bg-yellow-500/30 hover:text-white' }}">
                    <i class="fas fa-chart-pie w-5 text-center text-sm"></i>
                    <span class="ml-3 text-sm font-medium">Dashboard</span>
                </a>
            </li>

            <li x-data="{ open: {{ request()->routeIs('master.*') ? 'true' : 'false' }} }">
                <a @click="open = !open"
                    class="flex items-center justify-between px-3 py-2.5 rounded-lg cursor-pointer transition-all duration-200 {{ request()->routeIs('master.*') ? 'bg-yellow-500/50 text-white' : 'text-yellow-100 hover:bg-yellow-500/30 hover:text-white' }}">
                    <div class="flex items-center">
                        <i class="fas fa-database w-5 text-center text-sm"></i>
                        <span class="ml-3 text-sm font-medium">Master Data</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                        :class="open ? 'rotate-180' : ''"></i>
                </a>
                <ul x-show="open" class="ml-4 mt-1 space-y-1">
                    <li>
                        <a href="{{ route('master.produk.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('master.produk.*') ? 'bg-yellow-500/50 text-white' : 'text-yellow-200 hover:bg-yellow-500/30 hover:text-white' }}">
                            <i class="fas fa-box w-5 text-center text-xs"></i>
                            <span class="ml-3">Produk Paket</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.metode-pembayaran.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('master.metode-pembayaran.*') ? 'bg-yellow-500/50 text-white' : 'text-yellow-200 hover:bg-yellow-500/30 hover:text-white' }}">
                            <i class="fas fa-credit-card w-5 text-center text-xs"></i>
                            <span class="ml-3">Metode Pembayaran</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.kategori-pengeluaran.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('master.kategori-pengeluaran.*') ? 'bg-yellow-500/50 text-white' : 'text-yellow-200 hover:bg-yellow-500/30 hover:text-white' }}">
                            <i class="fas fa-tags w-5 text-center text-xs"></i>
                            <span class="ml-3">Kategori Pengeluaran</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.status-keberangkatan.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('master.status-keberangkatan.*') ? 'bg-yellow-500/50 text-white' : 'text-yellow-200 hover:bg-yellow-500/30 hover:text-white' }}">
                            <i class="fas fa-flag-checkered w-5 text-center text-xs"></i>
                            <span class="ml-3">Status Keberangkatan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.jenis-transaksi.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('master.jenis-transaksi.*') ? 'bg-yellow-500/50 text-white' : 'text-yellow-200 hover:bg-yellow-500/30 hover:text-white' }}">
                            <i class="fas fa-exchange-alt w-5 text-center text-xs"></i>
                            <span class="ml-3">Jenis Transaksi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.hotel.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('master.hotel.*') ? 'bg-yellow-500/50 text-white' : 'text-yellow-200 hover:bg-yellow-500/30 hover:text-white' }}">
                            <i class="fas fa-hotel w-5 text-center text-xs"></i>
                            <span class="ml-3">Hotel</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.kota-asal.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('master.kota-asal.*') ? 'bg-yellow-500/50 text-white' : 'text-yellow-200 hover:bg-yellow-500/30 hover:text-white' }}">
                            <i class="fas fa-city w-5 text-center text-xs"></i>
                            <span class="ml-3">Kota Asal</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.maskapai.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('master.maskapai.*') ? 'bg-yellow-500/50 text-white' : 'text-yellow-200 hover:bg-yellow-500/30 hover:text-white' }}">
                            <i class="fas fa-plane w-5 text-center text-xs"></i>
                            <span class="ml-3">Maskapai</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.diskon.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('master.diskon.*') ? 'bg-yellow-500/50 text-white' : 'text-yellow-200 hover:bg-yellow-500/30 hover:text-white' }}">
                            <i class="fas fa-tags w-5 text-center text-xs"></i>
                            <span class="ml-3">Diskon</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.perlengkapan.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('master.perlengkapan.*') ? 'bg-yellow-500/50 text-white' : 'text-yellow-200 hover:bg-yellow-500/30 hover:text-white' }}">
                            <i class="fas fa-box w-5 text-center text-xs"></i>
                            <span class="ml-3">Perlengkapan</span>
                        </a>
                    </li>
                    {{-- 
                    <li>
                        <a href="{{ route('master.paket-hotel.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('master.paket-hotel.*') ? 'bg-yellow-500/50 text-white' : 'text-yellow-200 hover:bg-yellow-500/30 hover:text-white' }}">
                            <i class="fas fa-bed w-5 text-center text-xs"></i>
                            <span class="ml-3">Paket Hotel</span>
                        </a>
                    </li>
                    --}}
                    <li>
                        <a href="{{ route('master.paket-tour.index') }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('master.paket-tour.*') ? 'bg-yellow-500/50 text-white' : 'text-yellow-200 hover:bg-yellow-500/30 hover:text-white' }}">
                            <i class="fas fa-route w-5 text-center text-xs"></i>
                            <span class="ml-3">Paket Tour</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="#"
                    class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-yellow-100 hover:bg-yellow-500/30 hover:text-white">
                    <i class="fas fa-calendar-check w-5 text-center text-sm"></i>
                    <span class="ml-3 text-sm font-medium">Keberangkatan</span>
                </a>
            </li>

            <li>
                <a href="#"
                    class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-yellow-100 hover:bg-yellow-500/30 hover:text-white">
                    <i class="fas fa-users w-5 text-center text-sm"></i>
                    <span class="ml-3 text-sm font-medium">Jamaah</span>
                </a>
            </li>

            <li x-data="{ open: false }">
                <a @click="open = !open"
                    class="flex items-center justify-between px-3 py-2.5 rounded-lg cursor-pointer transition-all duration-200 text-yellow-100 hover:bg-yellow-500/30 hover:text-white">
                    <div class="flex items-center">
                        <i class="fas fa-money-bill-wave w-5 text-center text-sm"></i>
                        <span class="ml-3 text-sm font-medium">Transaksi</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                        :class="open ? 'rotate-180' : ''"></i>
                </a>
                <ul x-show="open" class="ml-4 mt-1 space-y-1">
                    <li>
                        <a href="#"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 text-yellow-200 hover:bg-yellow-500/30 hover:text-white">
                            <i class="fas fa-arrow-down w-5 text-center text-xs"></i>
                            <span class="ml-3">Pemasukan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 text-yellow-200 hover:bg-yellow-500/30 hover:text-white">
                            <i class="fas fa-arrow-up w-5 text-center text-xs"></i>
                            <span class="ml-3">Pengeluaran</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="#"
                    class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-yellow-100 hover:bg-yellow-500/30 hover:text-white">
                    <i class="fas fa-file-invoice w-5 text-center text-sm"></i>
                    <span class="ml-3 text-sm font-medium">Invoice</span>
                </a>
            </li>

            <li>
                <a href="#"
                    class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-yellow-100 hover:bg-yellow-500/30 hover:text-white">
                    <i class="fas fa-history w-5 text-center text-sm"></i>
                    <span class="ml-3 text-sm font-medium">Audit Log</span>
                </a>
            </li>

            <li x-data="{ open: false }">
                <a @click="open = !open"
                    class="flex items-center justify-between px-3 py-2.5 rounded-lg cursor-pointer transition-all duration-200 text-yellow-100 hover:bg-yellow-500/30 hover:text-white">
                    <div class="flex items-center">
                        <i class="fas fa-chart-bar w-5 text-center text-sm"></i>
                        <span class="ml-3 text-sm font-medium">Laporan</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                        :class="open ? 'rotate-180' : ''"></i>
                </a>
                <ul x-show="open" class="ml-4 mt-1 space-y-1">
                    <li>
                        <a href="#"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 text-yellow-200 hover:bg-yellow-500/30 hover:text-white">
                            <i class="fas fa-chart-pie w-5 text-center text-xs"></i>
                            <span class="ml-3">Keuangan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition-all duration-200 text-yellow-200 hover:bg-yellow-500/30 hover:text-white">
                            <i class="fas fa-user-chart w-5 text-center text-xs"></i>
                            <span class="ml-3">Jamaah</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Logout Section - Paling Bawah -->
    <div class="absolute bottom-0 left-0 right-0 px-3 py-4 bg-gradient-to-t from-yellow-700/90 to-transparent">
        <ul class="space-y-1">
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-red-300 hover:bg-red-500/30 hover:text-white">
                    <i class="fas fa-sign-out-alt w-5 text-center text-sm"></i>
                    <span class="ml-3 text-sm font-medium">Logout</span>
                </a>
                <form id="logout-form" action="#" method="POST" class="hidden">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</aside>
