@extends('layouts.app')

@section('title', 'Detail Keberangkatan - Arrum Tour')
@section('page-title', 'Detail Keberangkatan')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.departure.index') }}" class="text-gray-500 hover:text-yellow-600">Transaksional</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.departure.index') }}" class="text-gray-500 hover:text-yellow-600">Keberangkatan</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500 font-medium">Detail</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Detail Keberangkatan</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Kelola data keberangkatan secara bertahap</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <a href="{{ route('transaksional.departure.edit', $departure->id_departure) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit Dasar
                    </a>
                    <button onclick="recalculate({{ $departure->id_departure }})"
                        class="inline-flex items-center px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium">
                        <i class="fas fa-sync-alt mr-2"></i> Recalculate
                    </button>
                    <a href="{{ route('transaksional.departure.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="px-6 pt-4">
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-gray-700">Progress:</span>
                    <div class="flex-1 bg-gray-200 rounded-full h-2.5">
                        <div class="h-2.5 rounded-full {{ $departure->progress_color }}"
                            style="width: {{ $departure->progress_percentage }}%"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700">{{ $departure->progress_percentage }}%</span>
                </div>
            </div>

            <!-- Header -->
            <div class="p-6">
                <div
                    class="bg-gradient-to-r from-yellow-50 to-yellow-100/50 rounded-xl p-6 mb-6 border border-yellow-200/50">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h2 class="text-2xl font-bold text-gray-800">{{ $departure->nama_keberangkatan }}</h2>
                                <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-medium rounded-full">
                                    {{ $departure->kode_keberangkatan }}
                                </span>
                                {!! $departure->status_badge !!}
                            </div>
                            <p class="text-gray-500 text-sm mt-1">
                                <i class="fas fa-box mr-1"></i> Produk: {{ $departure->produk_paket }}
                            </p>
                            <p class="text-gray-500 text-sm">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                {{ $departure->tanggal_keberangkatan->format('d F Y') }} -
                                {{ $departure->tanggal_kepulangan->format('d F Y') }}
                            </p>
                            @if ($departure->bulan_keberangkatan)
                                <p class="text-gray-500 text-sm">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Bulan Keberangkatan:
                                    {{ date('F', mktime(0, 0, 0, $departure->bulan_keberangkatan, 1)) }}
                                    {{ $departure->tahun_keberangkatan }}
                                </p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-yellow-600">
                                {{ $departure->total_pendapatan_bersih_formatted }}</p>
                            <p class="text-sm text-gray-500">Total Pendapatan Bersih</p>
                            <p class="text-sm text-green-600">{{ $departure->keuntungan_formatted }} Laba</p>
                            <p class="text-xs text-gray-400">Margin: {{ $departure->margin_laba }}%</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Kuota</span>
                            <span class="font-medium text-gray-700">{{ $departure->jamaah_terdaftar }} /
                                {{ $departure->kuota }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                            <div class="bg-yellow-500 h-2 rounded-full transition-all duration-500"
                                style="width: {{ $departure->kuota > 0 ? ($departure->jamaah_terdaftar / $departure->kuota) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grid Detail -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- ========================================== -->
                    <!-- MASKAPAI & HARGA -->
                    <!-- ========================================== -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-plane text-yellow-500 mr-2"></i> Maskapai & Harga
                            </h6>
                            <div class="flex items-center gap-2">
                                {!! $departure->maskapai_status_badge !!}
                                <button onclick="openMaskapaiModal()"
                                    class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs rounded-lg hover:bg-yellow-600 transition">
                                    <i class="fas fa-plus mr-1"></i> Atur Maskapai
                                </button>
                            </div>
                        </div>

                        <!-- SEARCH MASKAPAI -->
                        <div class="mb-3">
                            <div class="relative">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" id="searchMaskapai" placeholder="Cari maskapai..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            </div>
                        </div>

                        <dl class="space-y-3" id="maskapaiList">
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2 maskapai-item"
                                data-nama="domestik berangkat">
                                <dt class="text-gray-500">Domestik Berangkat</dt>
                                <dd class="font-medium text-gray-700 text-right">
                                    <div>{{ $departure->maskapaiDomestikBerangkat->nama_maskapai ?? '-' }}</div>
                                    <div class="text-xs text-yellow-600 font-bold">
                                        {{ $departure->harga_maskapai_domestik_berangkat_formatted }}</div>
                                </dd>
                            </div>
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2 maskapai-item"
                                data-nama="domestik pulang">
                                <dt class="text-gray-500">Domestik Pulang</dt>
                                <dd class="font-medium text-gray-700 text-right">
                                    <div>{{ $departure->maskapaiDomestikPulang->nama_maskapai ?? '-' }}</div>
                                    <div class="text-xs text-yellow-600 font-bold">
                                        {{ $departure->harga_maskapai_domestik_pulang_formatted }}</div>
                                </dd>
                            </div>
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2 maskapai-item"
                                data-nama="internasional berangkat">
                                <dt class="text-gray-500">Internasional Berangkat</dt>
                                <dd class="font-medium text-gray-700 text-right">
                                    <div>{{ $departure->maskapaiInternasionalBerangkat->nama_maskapai ?? '-' }}</div>
                                    <div class="text-xs text-yellow-600 font-bold">
                                        {{ $departure->harga_maskapai_internasional_berangkat_formatted }}</div>
                                </dd>
                            </div>
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2 maskapai-item"
                                data-nama="internasional pulang">
                                <dt class="text-gray-500">Internasional Pulang</dt>
                                <dd class="font-medium text-gray-700 text-right">
                                    <div>{{ $departure->maskapaiInternasionalPulang->nama_maskapai ?? '-' }}</div>
                                    <div class="text-xs text-yellow-600 font-bold">
                                        {{ $departure->harga_maskapai_internasional_pulang_formatted }}</div>
                                </dd>
                            </div>
                            <div class="flex justify-between text-sm font-semibold border-t border-gray-200 pt-2 mt-2">
                                <dt class="text-gray-700">Total Harga Maskapai</dt>
                                <dd class="text-lg font-bold text-blue-600">
                                    {{ $departure->total_harga_maskapai_formatted }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- ========================================== -->
                    <!-- HOTEL & TIPE KAMAR -->
                    <!-- ========================================== -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-hotel text-yellow-500 mr-2"></i> Hotel & Tipe Kamar
                            </h6>
                            <div class="flex items-center gap-2">
                                {!! $departure->hotel_status_badge !!}
                                <button onclick="openHotelModal()"
                                    class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs rounded-lg hover:bg-yellow-600 transition">
                                    <i class="fas fa-plus mr-1"></i> Atur Hotel
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="relative">
                                <i
                                    class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" id="searchHotel" placeholder="Cari hotel..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            </div>
                        </div>

                        @php
                            $hotelMekkahDetails = $departure->hotelMekkahDetails;
                            $hotelMadinahDetails = $departure->hotelMadinahDetails;
                            $hotelTransitDetails = $departure->hotelDetails->whereNotIn('id_hotel', [
                                $departure->id_hotel_mekkah,
                                $departure->id_hotel_madinah,
                            ]);
                        @endphp

                        <div id="hotelList">
                            @if ($departure->hotelMekkah || $departure->hotelMadinah || $departure->hotelTransit)
                                @if ($departure->hotelMekkah)
                                    <div class="mb-4 hotel-item"
                                        data-nama="{{ strtolower($departure->hotel_mekkah_nama) }}">
                                        <div class="flex items-center justify-between">
                                            <h5 class="text-sm font-semibold text-gray-700">
                                                {{ $departure->hotel_mekkah_nama }}</h5>
                                            <span
                                                class="text-sm font-bold text-yellow-600">{{ $departure->total_harga_hotel_mekkah_formatted }}</span>
                                        </div>
                                        @if ($hotelMekkahDetails->count() > 0)
                                            <div class="grid grid-cols-1 gap-2 mt-2">
                                                @foreach ($hotelMekkahDetails as $detail)
                                                    <div class="bg-white rounded-lg p-3 border border-gray-200 hotel-detail-item"
                                                        data-hotel="mekkah">
                                                        <p class="text-sm font-medium text-gray-700">
                                                            {{ $detail->tipe_kamar }}</p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ $detail->jumlah_kamar }} Kamar ×
                                                            {{ $detail->harga_per_malam_formatted }} ×
                                                            {{ $detail->durasi_menginap }} Malam
                                                        </p>
                                                        <p class="text-sm font-bold text-yellow-600">
                                                            {{ $detail->total_harga_formatted }}</p>
                                                        @if ($detail->catatan)
                                                            <p class="text-xs text-gray-400 mt-1">{{ $detail->catatan }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                @if ($departure->hotelMadinah)
                                    <div class="mb-4 hotel-item"
                                        data-nama="{{ strtolower($departure->hotel_madinah_nama) }}">
                                        <div class="flex items-center justify-between">
                                            <h5 class="text-sm font-semibold text-gray-700">
                                                {{ $departure->hotel_madinah_nama }}</h5>
                                            <span
                                                class="text-sm font-bold text-yellow-600">{{ $departure->total_harga_hotel_madinah_formatted }}</span>
                                        </div>
                                        @if ($hotelMadinahDetails->count() > 0)
                                            <div class="grid grid-cols-1 gap-2 mt-2">
                                                @foreach ($hotelMadinahDetails as $detail)
                                                    <div class="bg-white rounded-lg p-3 border border-gray-200 hotel-detail-item"
                                                        data-hotel="madinah">
                                                        <p class="text-sm font-medium text-gray-700">
                                                            {{ $detail->tipe_kamar }}</p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ $detail->jumlah_kamar }} Kamar ×
                                                            {{ $detail->harga_per_malam_formatted }} ×
                                                            {{ $detail->durasi_menginap }} Malam
                                                        </p>
                                                        <p class="text-sm font-bold text-yellow-600">
                                                            {{ $detail->total_harga_formatted }}</p>
                                                        @if ($detail->catatan)
                                                            <p class="text-xs text-gray-400 mt-1">{{ $detail->catatan }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                @if ($departure->hotelTransit)
                                    <div class="mb-4 hotel-item"
                                        data-nama="{{ strtolower($departure->hotel_transit_nama) }}">
                                        <div class="flex items-center justify-between">
                                            <h5 class="text-sm font-semibold text-gray-700">
                                                {{ $departure->hotel_transit_nama }}</h5>
                                            <span
                                                class="text-sm font-bold text-yellow-600">{{ $departure->total_harga_hotel_transit_formatted }}</span>
                                        </div>
                                        @if ($hotelTransitDetails->count() > 0)
                                            <div class="grid grid-cols-1 gap-2 mt-2">
                                                @foreach ($hotelTransitDetails as $detail)
                                                    <div class="bg-white rounded-lg p-3 border border-gray-200 hotel-detail-item"
                                                        data-hotel="transit">
                                                        <p class="text-sm font-medium text-gray-700">
                                                            {{ $detail->tipe_kamar }}</p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ $detail->jumlah_kamar }} Kamar ×
                                                            {{ $detail->harga_per_malam_formatted }} ×
                                                            {{ $detail->durasi_menginap }} Malam
                                                        </p>
                                                        <p class="text-sm font-bold text-yellow-600">
                                                            {{ $detail->total_harga_formatted }}</p>
                                                        @if ($detail->catatan)
                                                            <p class="text-xs text-gray-400 mt-1">{{ $detail->catatan }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="mt-3 pt-3 border-t border-gray-200 flex items-center justify-between">
                                    <span class="text-sm font-semibold text-gray-700">Total Semua Hotel</span>
                                    <span
                                        class="text-lg font-bold text-blue-600">{{ $departure->total_harga_hotel_all_formatted }}</span>
                                </div>
                            @else
                                <div class="text-center py-6">
                                    <div
                                        class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-hotel text-gray-300 text-xl"></i>
                                    </div>
                                    <p class="text-gray-500 text-sm">Belum ada data hotel</p>
                                    <p class="text-gray-400 text-xs mt-1">Klik tombol "Atur Hotel" untuk menambahkan hotel
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- HOTEL TOUR (PAKET TOUR) -->
                    <!-- ========================================== -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-umbrella-beach text-yellow-500 mr-2"></i> Hotel Tour (Paket Tour)
                                <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                                    {{ $departure->departurePaketTourHotels->count() }} hotel
                                </span>
                            </h6>
                            <div class="flex items-center gap-2">
                                @if ($departure->produk && $departure->produk->include_tur && $departure->produk->paketTour)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                        <i class="fas fa-check mr-1"></i> Include Tour
                                    </span>
                                    <button onclick="openPaketTourHotelModal()"
                                        class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs rounded-lg hover:bg-yellow-600 transition">
                                        <i class="fas fa-plus mr-1"></i> Atur Hotel Tour
                                    </button>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-500">
                                        <i class="fas fa-minus mr-1"></i> Tanpa Tour
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- SEARCH HOTEL TOUR -->
                        <div class="mb-3">
                            <div class="relative">
                                <i
                                    class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" id="searchHotelTour" placeholder="Cari hotel tour..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            </div>
                        </div>

                        @if ($departure->produk && $departure->produk->include_tur && $departure->produk->paketTour)
                            @php
                                $tourHotels = $departure->departurePaketTourHotels->sortBy('urutan');
                            @endphp

                            <div id="hotelTourList">
                                @if ($tourHotels->count() > 0)
                                    <div class="grid grid-cols-1 gap-3 max-h-60 overflow-y-auto pr-1">
                                        @foreach ($tourHotels as $item)
                                            <div class="bg-white rounded-lg p-3 border border-gray-200 hover:shadow-md transition-shadow duration-200 hotel-tour-item"
                                                data-nama="{{ strtolower($item->hotel->nama_hotel) }}"
                                                data-kota="{{ strtolower($item->hotel->kota ?? '') }}">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-2">
                                                            <span
                                                                class="inline-flex items-center justify-center w-6 h-6 bg-yellow-100 text-yellow-600 text-xs font-bold rounded-full flex-shrink-0">
                                                                {{ $loop->iteration }}
                                                            </span>
                                                            <div>
                                                                <p class="text-sm font-semibold text-gray-800 truncate">
                                                                    {{ $item->hotel->nama_hotel }}</p>
                                                                <p class="text-xs text-gray-400">
                                                                    {{ $item->hotel->kota ?? '-' }}
                                                                    @if ($item->tipe_kamar)
                                                                        · {{ $item->tipe_kamar }}
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right flex-shrink-0 ml-2">
                                                        <p class="text-sm font-bold text-yellow-600">
                                                            {{ $item->total_harga_formatted }}</p>
                                                        <p class="text-xs text-gray-400">{{ $item->jumlah_kamar }} kamar ×
                                                            {{ $item->harga_per_malam_formatted }} ×
                                                            {{ $item->durasi_menginap }} malam</p>
                                                    </div>
                                                </div>
                                                @if ($item->catatan)
                                                    <p class="text-xs text-gray-400 mt-1">{{ $item->catatan }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-3 pt-3 border-t border-gray-200 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-gray-700">Total Hotel Tour</span>
                                        <span
                                            class="text-lg font-bold text-blue-600">{{ $departure->total_harga_paket_tour_hotel_formatted }}</span>
                                    </div>
                                @else
                                    <div class="text-center py-6">
                                        <div
                                            class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-umbrella-beach text-gray-300 text-xl"></i>
                                        </div>
                                        <p class="text-gray-500 text-sm">Belum ada hotel tour yang diatur</p>
                                        <p class="text-gray-400 text-xs mt-1">Klik tombol "Atur Hotel Tour" untuk mengatur
                                            hotel tour</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Informasi Paket Tour (Tanpa Harga) -->
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500">Paket Tour</span>
                                        <span
                                            class="font-medium text-gray-700">{{ $departure->produk->paketTour->kota_tujuan ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500">Durasi Tour</span>
                                        <span
                                            class="font-medium text-gray-700">{{ $departure->produk->paketTour->durasi_hari ?? 0 }}
                                            Hari</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div
                                    class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-umbrella-beach text-gray-300 text-xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm">Produk ini tidak termasuk tour</p>
                                <p class="text-gray-400 text-xs mt-1">Hotel tour hanya tersedia untuk produk dengan include
                                    tour</p>
                            </div>
                        @endif
                    </div>

                    <!-- ========================================== -->
                    <!-- DAFTAR JAMAAH -->
                    <!-- ========================================== -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-users text-yellow-500 mr-2"></i> Daftar Jamaah
                                <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                                    {{ $departure->jamaahs->count() }} Jamaah
                                </span>
                            </h6>
                            <div class="flex items-center gap-2">
                                {!! $departure->jamaah_status_badge !!}

                                <!-- Tombol Sync Jamaah -->
                                <form
                                    action="{{ route('transaksional.departure.sync-jamaahs', $departure->id_departure) }}"
                                    method="POST"
                                    onsubmit="return confirm('Mencari jamaah baru yang memenuhi kriteria (Lunas, bulan/tahun sesuai, dan belum terdaftar di departure lain). Lanjutkan?')">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 bg-purple-500 text-white text-xs rounded-lg hover:bg-purple-600 transition"
                                        title="Cari jamaah baru yang tersedia">
                                        <i class="fas fa-sync-alt mr-1"></i> Sync Jamaah
                                    </button>
                                </form>

                                <button onclick="openJamaahModal()"
                                    class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs rounded-lg hover:bg-yellow-600 transition">
                                    <i class="fas fa-plus mr-1"></i> Atur Daftar Jamaah
                                </button>
                            </div>
                        </div>

                        <!-- Search Jamaah di View -->
                        <div class="mb-3">
                            <div class="relative">
                                <i
                                    class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" id="searchJamaah" placeholder="Cari jamaah..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            </div>
                        </div>

                        @if ($departure->jamaahs->count() > 0)
                            <div class="max-h-48 overflow-y-auto" id="jamaahList">
                                <ul class="divide-y divide-gray-200" id="jamaahItems">
                                    @foreach ($departure->jamaahs as $jamaah)
                                        <li class="py-2 flex items-center justify-between jamaah-item"
                                            data-name="{{ strtolower($jamaah->nama_lengkap) }}">
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">{{ $jamaah->nama_lengkap }}
                                                </p>
                                                <p class="text-xs text-gray-400">{{ $jamaah->produk_paket }}</p>
                                                @if ($jamaah->agent)
                                                    <p class="text-xs text-purple-600">
                                                        <i class="fas fa-user-tie mr-1"></i>
                                                        {{ $jamaah->agent->nama_agent ?? 'Agent' }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="text-xs text-gray-500">{{ $jamaah->status_pembayaran }}</span>
                                                <span
                                                    class="text-xs text-green-600">{{ $jamaah->total_dibayar_formatted }}</span>
                                                <button
                                                    onclick="removeJamaah({{ $departure->id_departure }}, {{ $jamaah->id_jamaah }})"
                                                    class="text-red-500 hover:text-red-700 text-xs">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <form
                                                    id="remove-jamaah-form-{{ $departure->id_departure }}-{{ $jamaah->id_jamaah }}"
                                                    action="{{ route('transaksional.departure.remove-jamaah', [$departure->id_departure, $jamaah->id_jamaah]) }}"
                                                    method="POST" class="hidden">
                                                    @csrf @method('DELETE')
                                                </form>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-semibold text-gray-700">Total Pendapatan dari Jamaah</span>
                                    <span
                                        class="text-lg font-bold text-green-600">{{ $departure->total_pendapatan_kotor_formatted }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm text-gray-500 mt-1">
                                    <span>Total Jamaah: {{ $departure->jamaahs->count() }} orang</span>
                                    <span>Rata-rata per Jamaah:
                                        {{ $departure->total_pendapatan_kotor > 0 && $departure->jamaahs->count() > 0 ? 'Rp ' . number_format($departure->total_pendapatan_kotor / $departure->jamaahs->count(), 0, ',', '.') : 'Rp 0' }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-6">
                                <div
                                    class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-users text-gray-300 text-xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm">Belum ada jamaah terdaftar</p>
                                <p class="text-gray-400 text-xs mt-1">Klik tombol "Sync Jamaah" untuk mencari jamaah baru,
                                    atau klik "Atur Daftar Jamaah" untuk memilih manual</p>
                            </div>
                        @endif
                    </div>

                    <!-- ========================================== -->
                    <!-- PERLENGKAPAN DEPARTURE -->
                    <!-- ========================================== -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-box text-yellow-500 mr-2"></i> Perlengkapan Departure
                                <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                                    {{ $departure->departurePerlengkapan->count() }} item
                                </span>
                                @php
                                    $totalItems = $departure->departurePerlengkapan->sum(function ($p) {
                                        return $p->perlengkapanJamaahs->count();
                                    });
                                    $totalReceived = $departure->departurePerlengkapan->sum(function ($p) {
                                        return $p->perlengkapanJamaahs
                                            ->where('status_terima', 'Sudah Diterima')
                                            ->count();
                                    });
                                @endphp
                                <span class="ml-2 text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">
                                    {{ $totalReceived }} / {{ $totalItems }} diterima
                                </span>
                            </h6>
                            <div class="flex items-center gap-2">
                                {!! $departure->perlengkapan_status_badge !!}
                                <button onclick="openPerlengkapanModal()"
                                    class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs rounded-lg hover:bg-yellow-600 transition">
                                    <i class="fas fa-plus mr-1"></i> Atur Perlengkapan
                                </button>
                            </div>
                        </div>

                        <!-- SEARCH PERLENGKAPAN -->
                        <div class="mb-3">
                            <div class="relative">
                                <i
                                    class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" id="searchPerlengkapan" placeholder="Cari perlengkapan..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            </div>
                        </div>

                        @php
                            $perlengkapanList = $departure->departurePerlengkapan;
                            $totalJamaah = $departure->jamaahs->count();
                        @endphp

                        <div id="perlengkapanList">
                            @if ($perlengkapanList->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto pr-1">
                                    @foreach ($perlengkapanList as $perlengkapan)
                                        @php
                                            $totalPerItem = $perlengkapan->perlengkapanJamaahs->count();
                                            $sudahTerima = $perlengkapan->perlengkapanJamaahs
                                                ->where('status_terima', 'Sudah Diterima')
                                                ->count();
                                            $belumTerima = $totalPerItem - $sudahTerima;
                                            $progressPercent =
                                                $totalPerItem > 0 ? round(($sudahTerima / $totalPerItem) * 100) : 0;
                                        @endphp

                                        <div class="bg-white rounded-lg p-3 border border-gray-200 hover:shadow-md transition-shadow duration-200 perlengkapan-item"
                                            data-nama="{{ strtolower($perlengkapan->perlengkapan->nama_perlengkapan) }}">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2">
                                                        <div
                                                            class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                            <i class="fas fa-box text-yellow-600 text-xs"></i>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-semibold text-gray-800 truncate">
                                                                {{ $perlengkapan->perlengkapan->nama_perlengkapan }}</p>
                                                            <p class="text-xs text-gray-400">
                                                                {{ $perlengkapan->jumlah_per_jamaah }} per jamaah ·
                                                                {{ $perlengkapan->harga_satuan_formatted }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-1 ml-2 flex-shrink-0">
                                                    <button onclick="openPerlengkapanDetailModal({{ $perlengkapan->id }})"
                                                        class="p-1 text-blue-500 hover:bg-blue-50 rounded transition"
                                                        title="Lihat Detail">
                                                        <i class="fas fa-eye text-xs"></i>
                                                    </button>
                                                    <button
                                                        onclick="removePerlengkapan({{ $departure->id_departure }}, {{ $perlengkapan->id }})"
                                                        class="p-1 text-red-500 hover:bg-red-50 rounded transition"
                                                        title="Hapus">
                                                        <i class="fas fa-trash text-xs"></i>
                                                    </button>
                                                    <form
                                                        id="remove-perlengkapan-form-{{ $departure->id_departure }}-{{ $perlengkapan->id }}"
                                                        action="{{ route('transaksional.departure.remove-perlengkapan', [$departure->id_departure, $perlengkapan->id]) }}"
                                                        method="POST" class="hidden">
                                                        @csrf @method('DELETE')
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="mt-2">
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-gray-500">Progress penerimaan</span>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-green-600">{{ $sudahTerima }} diterima</span>
                                                        @if ($belumTerima > 0)
                                                            <span class="text-yellow-600">{{ $belumTerima }}
                                                                belum</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                                    <div class="h-1.5 rounded-full transition-all duration-500 {{ $progressPercent >= 100 ? 'bg-green-500' : 'bg-yellow-500' }}"
                                                        style="width: {{ $progressPercent }}%"></div>
                                                </div>
                                            </div>

                                            <div
                                                class="mt-2 pt-2 border-t border-gray-100 flex items-center justify-between">
                                                <span class="text-xs text-gray-500">Total Harga</span>
                                                <span
                                                    class="text-sm font-bold text-yellow-600">{{ $perlengkapan->total_harga_formatted }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-3 pt-3 border-t border-gray-200 flex items-center justify-between">
                                    <span class="text-sm font-semibold text-gray-700">Total Semua Perlengkapan</span>
                                    <span
                                        class="text-lg font-bold text-purple-600">{{ $departure->total_harga_perlengkapan_formatted }}</span>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div
                                        class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-box text-gray-300 text-xl"></i>
                                    </div>
                                    <p class="text-gray-500 text-sm">Belum ada perlengkapan yang ditambahkan</p>
                                    <p class="text-gray-400 text-xs mt-1">Klik tombol "Atur Perlengkapan" untuk menambahkan
                                        perlengkapan</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- JENIS TRANSAKSI -->
                    <!-- ========================================== -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-receipt text-yellow-500 mr-2"></i> Jenis Transaksi
                                <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                                    {{ $departure->departureJenisTransaksis->count() }} item
                                </span>
                            </h6>
                            <div class="flex items-center gap-2">
                                <button onclick="openJenisTransaksiModal()"
                                    class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs rounded-lg hover:bg-yellow-600 transition">
                                    <i class="fas fa-plus mr-1"></i> Atur Jenis Transaksi
                                </button>
                            </div>
                        </div>

                        <!-- Search Jenis Transaksi di View -->
                        <div class="mb-3">
                            <div class="relative">
                                <i
                                    class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" id="searchJenisTransaksi" placeholder="Cari jenis transaksi..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            </div>
                        </div>

                        @php
                            $jenisTransaksiList = $departure->departureJenisTransaksis;
                            $totalJamaah = $departure->jamaahs->count();
                        @endphp

                        <div id="jenisTransaksiList">
                            @if ($jenisTransaksiList->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto pr-1">
                                    @foreach ($jenisTransaksiList as $item)
                                        <div class="bg-white rounded-lg p-3 border border-gray-200 hover:shadow-md transition-shadow duration-200 jenis-transaksi-item"
                                            data-nama="{{ strtolower($item->jenisTransaksi->nama) }}">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2">
                                                        <div
                                                            class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                            <i class="fas fa-tag text-purple-600 text-xs"></i>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-semibold text-gray-800 truncate">
                                                                {{ $item->jenisTransaksi->nama }}</p>
                                                            <p class="text-xs text-gray-400">
                                                                {{ $item->harga_satuan_formatted }} / jamaah</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-1 ml-2 flex-shrink-0">
                                                    <button
                                                        onclick="editJenisTransaksiHarga({{ $departure->id_departure }}, {{ $item->id_jenis_transaksi }}, '{{ $item->harga_satuan }}')"
                                                        class="p-1 text-blue-500 hover:bg-blue-50 rounded transition"
                                                        title="Edit Harga">
                                                        <i class="fas fa-edit text-xs"></i>
                                                    </button>
                                                    <button
                                                        onclick="removeJenisTransaksi({{ $departure->id_departure }}, {{ $item->id_jenis_transaksi }})"
                                                        class="p-1 text-red-500 hover:bg-red-50 rounded transition"
                                                        title="Hapus">
                                                        <i class="fas fa-trash text-xs"></i>
                                                    </button>
                                                    <form
                                                        id="remove-jenis-transaksi-form-{{ $departure->id_departure }}-{{ $item->id_jenis_transaksi }}"
                                                        action="{{ route('transaksional.departure.remove-jenis-transaksi', [$departure->id_departure, $item->id_jenis_transaksi]) }}"
                                                        method="POST" class="hidden">
                                                        @csrf @method('DELETE')
                                                    </form>
                                                </div>
                                            </div>
                                            <div
                                                class="mt-2 pt-2 border-t border-gray-100 flex items-center justify-between">
                                                <span class="text-xs text-gray-500">Total ({{ $totalJamaah }}
                                                    jamaah)</span>
                                                <span
                                                    class="text-sm font-bold text-purple-600">{{ $item->total_harga_formatted }}</span>
                                            </div>

                                            @if ($item->catatan)
                                                <p class="text-xs text-gray-400 mt-1">{{ $item->catatan }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-3 pt-3 border-t border-gray-200 flex items-center justify-between">
                                    <span class="text-sm font-semibold text-gray-700">Total Semua Jenis Transaksi</span>
                                    <span
                                        class="text-lg font-bold text-purple-600">{{ $departure->total_jenis_transaksi_formatted }}</span>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div
                                        class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-receipt text-gray-300 text-xl"></i>
                                    </div>
                                    <p class="text-gray-500 text-sm">Belum ada jenis transaksi yang ditambahkan</p>
                                    <p class="text-gray-400 text-xs mt-1">Klik tombol "Atur Jenis Transaksi" untuk
                                        menambahkan jenis transaksi</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- AGENT & JAMAAH -->
                    <!-- ========================================== -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 lg:col-span-2">
                        <div class="flex items-center justify-between mb-4">
                            <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-user-tie text-purple-500 mr-2"></i> Agent & Jamaah
                                <span class="ml-2 text-xs bg-purple-100 text-purple-800 px-2 py-0.5 rounded-full">
                                    {{ $departure->jamaahs->filter(function ($jamaah) {return !empty($jamaah->agent_name);})->count() }}
                                    dengan Agent
                                </span>
                                <span class="ml-2 text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                                    Total {{ $departure->jamaahs->count() }} Jamaah
                                </span>
                            </h6>
                        </div>

                        <!-- Search Agent -->
                        <div class="mb-3">
                            <div class="relative">
                                <i
                                    class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" id="searchAgent" placeholder="Cari agent atau jamaah..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                            </div>
                        </div>

                        @php
                            // Ambil semua jamaah yang memiliki agent_name (tidak kosong)
                            $jamaahsWithAgent = $departure->jamaahs->filter(function ($jamaah) {
                                return !empty($jamaah->agent_name) && trim($jamaah->agent_name) !== '';
                            });

                            // Group by agent_name
                            $groupedByAgent = $jamaahsWithAgent->groupBy(function ($jamaah) {
                                return $jamaah->agent_name ?? 'Agent Tidak Diketahui';
                            });

                            $jamaahsWithoutAgent = $departure->jamaahs->filter(function ($jamaah) {
                                return empty($jamaah->agent_name) || trim($jamaah->agent_name) === '';
                            });
                        @endphp

                        <div id="agentList">
                            @if ($jamaahsWithAgent->count() > 0)
                                <!-- Statistik Agent -->
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                                    <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                        <p class="text-xs text-gray-500">Total Agent</p>
                                        <p class="text-lg font-bold text-purple-600">{{ $groupedByAgent->count() }}</p>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                        <p class="text-xs text-gray-500">Dengan Agent</p>
                                        <p class="text-lg font-bold text-blue-600">{{ $jamaahsWithAgent->count() }}</p>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                        <p class="text-xs text-gray-500">Tanpa Agent</p>
                                        <p class="text-lg font-bold text-orange-500">{{ $jamaahsWithoutAgent->count() }}
                                        </p>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                        <p class="text-xs text-gray-500">Total Jamaah</p>
                                        <p class="text-lg font-bold text-gray-700">{{ $departure->jamaahs->count() }}</p>
                                    </div>
                                </div>

                                <!-- Tabel Agent & Jamaah -->
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm border border-gray-200 rounded-lg">
                                        <thead class="bg-purple-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-semibold text-purple-700">Agent
                                                </th>
                                                <th class="px-4 py-2 text-left text-xs font-semibold text-purple-700">Nama
                                                    Jamaah</th>
                                                <th class="px-4 py-2 text-left text-xs font-semibold text-purple-700">
                                                    Produk</th>
                                                <th class="px-4 py-2 text-center text-xs font-semibold text-purple-700">
                                                    Status Pembayaran</th>
                                                <th class="px-4 py-2 text-right text-xs font-semibold text-purple-700">Fee
                                                    Agent</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach ($groupedByAgent as $agentName => $jamaahs)
                                                @foreach ($jamaahs as $jamaah)
                                                    <tr class="hover:bg-gray-50 transition-colors agent-row"
                                                        data-agent="{{ strtolower($agentName) }}"
                                                        data-name="{{ strtolower($jamaah->nama_lengkap) }}">
                                                        <td class="px-4 py-2.5">
                                                            @if ($loop->first)
                                                                <span
                                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs font-medium">
                                                                    <i class="fas fa-user-tie text-purple-500"></i>
                                                                    {{ $agentName }}
                                                                </span>
                                                                <span
                                                                    class="text-xs text-gray-400 ml-1">({{ $jamaahs->count() }}
                                                                    jamaah)</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 py-2.5 font-medium text-gray-800">
                                                            {{ $jamaah->nama_lengkap }}</td>
                                                        <td class="px-4 py-2.5 text-gray-600">{{ $jamaah->produk_paket }}
                                                        </td>
                                                        <td class="px-4 py-2.5 text-center">
                                                            <span
                                                                class="px-2 py-0.5 rounded-full text-xs font-medium
                                            {{ $jamaah->status_pembayaran == 'Lunas'
                                                ? 'bg-green-100 text-green-700'
                                                : ($jamaah->status_pembayaran == 'DP'
                                                    ? 'bg-yellow-100 text-yellow-700'
                                                    : ($jamaah->status_pembayaran == 'Setoran'
                                                        ? 'bg-blue-100 text-blue-700'
                                                        : 'bg-red-100 text-red-700')) }}">
                                                                {{ $jamaah->status_pembayaran }}
                                                            </span>
                                                        </td>
                                                        <td class="px-4 py-2.5 text-right font-medium text-purple-600">
                                                            @if ($loop->first)
                                                                Rp
                                                                {{ number_format($jamaah->fee_agent ?? 0, 0, ',', '.') }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <!-- Baris Total per Agent -->
                                                <tr class="bg-purple-50/50 border-t border-purple-200">
                                                    <td colspan="4"
                                                        class="px-4 py-2 text-right text-sm font-medium text-purple-700">
                                                        Total Fee Agent {{ $agentName }}
                                                    </td>
                                                    <td class="px-4 py-2 text-right font-bold text-purple-700">
                                                        Rp {{ number_format($jamaahs->sum('fee_agent'), 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-purple-100 border-t-2 border-purple-300">
                                            <tr>
                                                <td colspan="4"
                                                    class="px-4 py-3 text-right font-semibold text-gray-700">
                                                    <i class="fas fa-calculator mr-2 text-purple-500"></i>
                                                    Total Keseluruhan Fee Agent
                                                </td>
                                                <td class="px-4 py-3 text-right font-bold text-purple-700 text-lg">
                                                    Rp
                                                    {{ number_format($jamaahsWithAgent->sum('fee_agent'), 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Daftar Jamaah Tanpa Agent -->
                                @if ($jamaahsWithoutAgent->count() > 0)
                                    <div class="mt-4">
                                        <p class="text-sm font-medium text-gray-600 mb-2 flex items-center gap-2">
                                            <i class="fas fa-user text-gray-400"></i>
                                            Jamaah Tanpa Agent
                                            <span class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full">
                                                {{ $jamaahsWithoutAgent->count() }} jamaah
                                            </span>
                                        </p>
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-sm border border-gray-200 rounded-lg">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th
                                                            class="px-4 py-2 text-left text-xs font-semibold text-gray-500">
                                                            Nama Jamaah</th>
                                                        <th
                                                            class="px-4 py-2 text-left text-xs font-semibold text-gray-500">
                                                            Produk</th>
                                                        <th
                                                            class="px-4 py-2 text-center text-xs font-semibold text-gray-500">
                                                            Status Pembayaran</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100">
                                                    @foreach ($jamaahsWithoutAgent as $jamaah)
                                                        <tr class="hover:bg-gray-50 transition-colors no-agent-row"
                                                            data-name="{{ strtolower($jamaah->nama_lengkap) }}">
                                                            <td class="px-4 py-2.5 font-medium text-gray-800">
                                                                {{ $jamaah->nama_lengkap }}</td>
                                                            <td class="px-4 py-2.5 text-gray-600">
                                                                {{ $jamaah->produk_paket }}</td>
                                                            <td class="px-4 py-2.5 text-center">
                                                                <span
                                                                    class="px-2 py-0.5 rounded-full text-xs font-medium
                                                {{ $jamaah->status_pembayaran == 'Lunas'
                                                    ? 'bg-green-100 text-green-700'
                                                    : ($jamaah->status_pembayaran == 'DP'
                                                        ? 'bg-yellow-100 text-yellow-700'
                                                        : ($jamaah->status_pembayaran == 'Setoran'
                                                            ? 'bg-blue-100 text-blue-700'
                                                            : 'bg-red-100 text-red-700')) }}">
                                                                    {{ $jamaah->status_pembayaran }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <!-- Jika tidak ada jamaah dengan agent -->
                                <div class="text-center py-8">
                                    <div
                                        class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-user-tie text-gray-300 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">Belum ada jamaah yang memiliki agent</p>
                                    <p class="text-gray-400 text-sm mt-1">Semua jamaah terdaftar tanpa agent</p>
                                    @if ($departure->jamaahs->count() > 0)
                                        <p class="text-xs text-gray-400 mt-2">
                                            Total jamaah terdaftar: <strong>{{ $departure->jamaahs->count() }}</strong>
                                            orang
                                        </p>
                                        <!-- Tampilkan semua jamaah tanpa agent dalam tabel -->
                                        <div class="mt-4 text-left overflow-x-auto">
                                            <table class="w-full text-sm border border-gray-200 rounded-lg">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th
                                                            class="px-4 py-2 text-left text-xs font-semibold text-gray-500">
                                                            Nama Jamaah</th>
                                                        <th
                                                            class="px-4 py-2 text-left text-xs font-semibold text-gray-500">
                                                            Produk</th>
                                                        <th
                                                            class="px-4 py-2 text-center text-xs font-semibold text-gray-500">
                                                            Status Pembayaran</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100">
                                                    @foreach ($departure->jamaahs as $jamaah)
                                                        <tr class="hover:bg-gray-50 transition-colors">
                                                            <td class="px-4 py-2.5 font-medium text-gray-800">
                                                                {{ $jamaah->nama_lengkap }}</td>
                                                            <td class="px-4 py-2.5 text-gray-600">
                                                                {{ $jamaah->produk_paket }}</td>
                                                            <td class="px-4 py-2.5 text-center">
                                                                <span
                                                                    class="px-2 py-0.5 rounded-full text-xs font-medium
                                                {{ $jamaah->status_pembayaran == 'Lunas'
                                                    ? 'bg-green-100 text-green-700'
                                                    : ($jamaah->status_pembayaran == 'DP'
                                                        ? 'bg-yellow-100 text-yellow-700'
                                                        : ($jamaah->status_pembayaran == 'Setoran'
                                                            ? 'bg-blue-100 text-blue-700'
                                                            : 'bg-red-100 text-red-700')) }}">
                                                                    {{ $jamaah->status_pembayaran }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- CATATAN -->
                    <!-- ========================================== -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-sticky-note text-yellow-500 mr-2"></i> Catatan
                            </h6>
                            <div class="flex items-center gap-2">
                                {!! $departure->catatan_status_badge !!}
                                <button onclick="openCatatanModal()"
                                    class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs rounded-lg hover:bg-yellow-600 transition">
                                    <i class="fas fa-plus mr-1"></i> Tambah
                                </button>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-gray-200 min-h-[60px]">
                            <p class="text-sm text-gray-600">{{ $departure->catatan ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- KEUANGAN -->
                    <!-- ========================================== -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 lg:col-span-2">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-money-bill-wave text-yellow-500 mr-2"></i> Ringkasan Keuangan
                        </h6>

                        <!-- Detail Pengeluaran -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                <p class="text-xs text-gray-500">Total Pendapatan</p>
                                <p class="text-sm font-bold text-blue-600">{{ $departure->total_pendapatan_formatted }}
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                <p class="text-xs text-gray-500">Diskon</p>
                                <p class="text-sm font-bold text-red-600">{{ $departure->total_diskon_formatted }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                <p class="text-xs text-gray-500">Pendapatan Bersih</p>
                                <p class="text-sm font-bold text-yellow-600">
                                    {{ $departure->total_pendapatan_bersih_formatted }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                <p class="text-xs text-gray-500">Total Pengeluaran</p>
                                <p class="text-sm font-bold text-purple-600">
                                    {{ $departure->total_pengeluaran_formatted }}</p>
                            </div>
                        </div>



                        <!-- Keuntungan -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                <p class="text-xs text-gray-500">Keuntungan</p>
                                <p
                                    class="text-lg font-bold {{ $departure->keuntungan > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $departure->keuntungan_formatted }}
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                <p class="text-xs text-gray-500">Margin Laba</p>
                                <p
                                    class="text-lg font-bold {{ $departure->margin_laba > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $departure->margin_laba_formatted }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Sistem -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 lg:col-span-2">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-calendar-alt text-yellow-500 mr-2"></i> Informasi Sistem
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Dibuat Pada</dt>
                                <dd class="font-medium text-gray-700">{{ $departure->created_at->format('d M Y H:i') }}
                                </dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Terakhir Diperbarui</dt>
                                <dd class="font-medium text-gray-700">{{ $departure->updated_at->format('d M Y H:i') }}
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aksi Bawah -->
                <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-end gap-3">
                    <!-- Tombol Sinkronisasi Ulang (Perlengkapan & Jenis Transaksi) -->
                    <form action="{{ route('transaksional.departure.sync-all', $departure->id_departure) }}"
                        method="POST"
                        onsubmit="return confirm('Sinkronisasi akan memperbarui semua perlengkapan dan jenis transaksi dengan jumlah jamaah terbaru ({{ $departure->jamaahs->count() }} jamaah). Lanjutkan?')">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition text-sm font-medium shadow-sm hover:shadow">
                            <i class="fas fa-sync-alt mr-2"></i> Sinkronisasi Ulang
                        </button>
                    </form>
                    <a href="{{ route('transaksional.departure.index') }}"
                        class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">Kembali</a>
                    <a href="{{ route('transaksional.departure.edit', $departure->id_departure) }}"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">Edit Dasar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL ATUR HOTEL TOUR -->
    <!-- ========================================== -->
    <div id="paketTourHotelModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-5xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-umbrella-beach text-yellow-500 mr-2"></i>
                    Atur Hotel Tour
                </h5>
                <button onclick="closePaketTourHotelModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('transaksional.departure.update-paket-tour-hotel', $departure->id_departure) }}"
                method="POST" class="p-6 space-y-6" id="paketTourHotelForm">
                @csrf
                @method('PUT')

                <input type="hidden" name="id_paket_tour" value="{{ $departure->produk->paket_tour_id ?? '' }}">

                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        Atur hotel-hotel yang termasuk dalam paket tour untuk keberangkatan ini.
                    </p>
                    <div class="flex items-center gap-4 mt-2 text-xs text-blue-600">
                        <span>Paket Tour: <strong>{{ $departure->produk->paketTour->kota_tujuan ?? '-' }}</strong></span>
                        <span>Durasi: <strong>{{ $departure->produk->paketTour->durasi_hari ?? 0 }} Hari</strong></span>
                    </div>
                </div>

                <div id="paketTourHotelsContainer">
                    @php
                        $tourHotels = $departure->departurePaketTourHotels->sortBy('urutan');
                        $availableHotels = $departure->produk->paketTour->hotels ?? collect();
                    @endphp

                    @if ($availableHotels->count() > 0)
                        <div class="space-y-3">
                            @foreach ($availableHotels as $index => $hotel)
                                @php
                                    $existing = $tourHotels->where('id_hotel', $hotel->id_hotel)->first();
                                    $isChecked = $existing ? true : false;
                                    $hargaPerMalam = $existing
                                        ? $existing->harga_per_malam
                                        : $hotel->harga_per_malam ?? 0;
                                @endphp
                                <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow duration-200 hotel-tour-item"
                                    data-hotel-id="{{ $hotel->id_hotel }}" data-index="{{ $loop->index }}">
                                    <div class="flex items-start gap-4">
                                        <div class="flex items-start gap-2 pt-1">
                                            <input type="checkbox"
                                                name="paket_tour_hotels[{{ $loop->index }}][id_hotel]"
                                                value="{{ $hotel->id_hotel }}" {{ $isChecked ? 'checked' : '' }}
                                                class="hotel-tour-checkbox w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500 mt-1">
                                            <span
                                                class="inline-flex items-center justify-center w-6 h-6 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">
                                                {{ $loop->iteration }}
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="font-semibold text-gray-800">{{ $hotel->nama_hotel }}</p>
                                                    <p class="text-sm text-gray-500">{{ $hotel->kota ?? '-' }}
                                                        @if ($hotel->bintang)
                                                            · {{ $hotel->bintang_text }}
                                                        @endif
                                                    </p>
                                                </div>
                                                <span class="text-sm text-gray-400">
                                                    <i class="fas fa-door-open mr-1"></i>
                                                    <span id="kamarCount-{{ $loop->index }}">Memuat...</span>
                                                </span>
                                            </div>

                                            <div class="mt-3">
                                                <label class="text-xs text-gray-500 font-medium">Tipe Kamar</label>
                                                <select name="paket_tour_hotels[{{ $loop->index }}][tipe_kamar]"
                                                    id="tipeKamar-{{ $loop->index }}"
                                                    class="hotel-tour-tipe w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                                    {{ !$isChecked ? 'disabled' : '' }}>
                                                    <option value="">-- Pilih Tipe Kamar --</option>
                                                </select>
                                                <div id="kamarLoading-{{ $loop->index }}"
                                                    class="mt-1 text-xs text-gray-400 hidden">
                                                    <i class="fas fa-spinner fa-spin mr-1"></i> Memuat tipe kamar...
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-3">
                                                <div>
                                                    <label class="text-xs text-gray-500 font-medium">Harga per
                                                        Malam</label>
                                                    <div class="relative">
                                                        <span
                                                            class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                                        <input type="text"
                                                            name="paket_tour_hotels[{{ $loop->index }}][harga_per_malam_display]"
                                                            id="harga_per_malam_display_{{ $loop->index }}"
                                                            value="{{ $hargaPerMalam > 0 ? 'Rp ' . number_format($hargaPerMalam, 0, ',', '.') : 'Rp 0' }}"
                                                            class="hotel-tour-price w-full pl-8 pr-2 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 hotel-tour-harga"
                                                            placeholder="Rp 0"
                                                            oninput="formatRupiahHotelTour(this, {{ $loop->index }})"
                                                            onblur="formatRupiahHotelTour(this, {{ $loop->index }})"
                                                            {{ !$isChecked ? 'disabled' : '' }}>
                                                        <input type="hidden"
                                                            name="paket_tour_hotels[{{ $loop->index }}][harga_per_malam]"
                                                            id="harga_per_malam_{{ $loop->index }}"
                                                            value="{{ $hargaPerMalam }}">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="text-xs text-gray-500 font-medium">Durasi
                                                        Menginap</label>
                                                    <input type="number"
                                                        name="paket_tour_hotels[{{ $loop->index }}][durasi_menginap]"
                                                        value="{{ $existing->durasi_menginap ?? 1 }}"
                                                        class="hotel-tour-durasi w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                                        min="1" {{ !$isChecked ? 'disabled' : '' }}>
                                                </div>
                                                <div>
                                                    <label class="text-xs text-gray-500 font-medium">Jumlah Kamar</label>
                                                    <input type="number"
                                                        name="paket_tour_hotels[{{ $loop->index }}][jumlah_kamar]"
                                                        value="{{ $existing->jumlah_kamar ?? 1 }}"
                                                        class="hotel-tour-kamar w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                                        min="1" {{ !$isChecked ? 'disabled' : '' }}>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <label class="text-xs text-gray-500 font-medium">Catatan</label>
                                                <input type="text"
                                                    name="paket_tour_hotels[{{ $loop->index }}][catatan]"
                                                    value="{{ $existing->catatan ?? '' }}"
                                                    class="hotel-tour-catatan w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                                    placeholder="Catatan (opsional)" {{ !$isChecked ? 'disabled' : '' }}>
                                            </div>
                                            @if ($existing)
                                                <div
                                                    class="mt-2 flex items-center justify-end bg-yellow-50 rounded-lg px-3 py-1.5 border border-yellow-200">
                                                    <span class="text-sm font-medium text-gray-600 mr-2">Total:</span>
                                                    <span
                                                        class="text-sm font-bold text-yellow-600">{{ $existing->total_harga_formatted }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-700">
                                    <i class="fas fa-calculator mr-2 text-yellow-500"></i>
                                    Total Seluruh Hotel Tour
                                </span>
                                <span class="text-lg font-bold text-yellow-600" id="totalPaketTourHotelPreview">
                                    {{ $departure->total_harga_paket_tour_hotel_formatted }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-xl border border-gray-200">
                            <i class="fas fa-hotel text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 font-medium">Belum ada hotel yang terdaftar untuk paket tour ini</p>
                            <p class="text-gray-400 text-sm mt-1">Silakan tambahkan hotel pada paket tour terlebih dahulu
                            </p>
                            <button type="button" onclick="closePaketTourHotelModal()"
                                class="inline-block mt-3 px-4 py-2 bg-gray-500 text-white text-sm rounded-lg hover:bg-gray-600 transition">
                                <i class="fas fa-times mr-2"></i> Tutup
                            </button>
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closePaketTourHotelModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                        <i class="fas fa-save mr-2"></i> Simpan Hotel Tour
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL MASKAPAI & HARGA -->
    <!-- ========================================== -->
    <div id="maskapaiModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-plane text-yellow-500 mr-2"></i>
                    Tambah Maskapai & Harga
                </h5>
                <button onclick="closeMaskapaiModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('transaksional.departure.update-maskapai', $departure->id_departure) }}"
                method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Maskapai Domestik Berangkat</label>
                        <select name="id_maskapai_domestik_berangkat"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">-- Pilih --</option>
                            @foreach ($maskapaiOptions as $maskapai)
                                @if ($maskapai->is_domestik)
                                    <option value="{{ $maskapai->id_maskapai }}"
                                        {{ $departure->id_maskapai_domestik_berangkat == $maskapai->id_maskapai ? 'selected' : '' }}>
                                        {{ $maskapai->nama_maskapai }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Domestik Berangkat</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                            <input type="text" name="harga_maskapai_domestik_berangkat_display"
                                id="harga_maskapai_domestik_berangkat_display"
                                value="{{ $departure->harga_maskapai_domestik_berangkat ? 'Rp ' . number_format($departure->harga_maskapai_domestik_berangkat, 0, ',', '.') : 'Rp 0' }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 maskapai-harga"
                                placeholder="Rp 0"
                                oninput="formatRupiahMaskapai(this, 'harga_maskapai_domestik_berangkat')"
                                onblur="formatRupiahMaskapai(this, 'harga_maskapai_domestik_berangkat')">
                            <input type="hidden" name="harga_maskapai_domestik_berangkat"
                                id="harga_maskapai_domestik_berangkat"
                                value="{{ $departure->harga_maskapai_domestik_berangkat }}">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Maskapai Domestik Pulang</label>
                        <select name="id_maskapai_domestik_pulang"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">-- Pilih --</option>
                            @foreach ($maskapaiOptions as $maskapai)
                                @if ($maskapai->is_domestik)
                                    <option value="{{ $maskapai->id_maskapai }}"
                                        {{ $departure->id_maskapai_domestik_pulang == $maskapai->id_maskapai ? 'selected' : '' }}>
                                        {{ $maskapai->nama_maskapai }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Domestik Pulang</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                            <input type="text" name="harga_maskapai_domestik_pulang_display"
                                id="harga_maskapai_domestik_pulang_display"
                                value="{{ $departure->harga_maskapai_domestik_pulang ? 'Rp ' . number_format($departure->harga_maskapai_domestik_pulang, 0, ',', '.') : 'Rp 0' }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 maskapai-harga"
                                placeholder="Rp 0" oninput="formatRupiahMaskapai(this, 'harga_maskapai_domestik_pulang')"
                                onblur="formatRupiahMaskapai(this, 'harga_maskapai_domestik_pulang')">
                            <input type="hidden" name="harga_maskapai_domestik_pulang"
                                id="harga_maskapai_domestik_pulang"
                                value="{{ $departure->harga_maskapai_domestik_pulang }}">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Maskapai Internasional
                            Berangkat</label>
                        <select name="id_maskapai_internasional_berangkat"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">-- Pilih --</option>
                            @foreach ($maskapaiOptions as $maskapai)
                                @if ($maskapai->is_internasional)
                                    <option value="{{ $maskapai->id_maskapai }}"
                                        {{ $departure->id_maskapai_internasional_berangkat == $maskapai->id_maskapai ? 'selected' : '' }}>
                                        {{ $maskapai->nama_maskapai }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Internasional Berangkat</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                            <input type="text" name="harga_maskapai_internasional_berangkat_display"
                                id="harga_maskapai_internasional_berangkat_display"
                                value="{{ $departure->harga_maskapai_internasional_berangkat ? 'Rp ' . number_format($departure->harga_maskapai_internasional_berangkat, 0, ',', '.') : 'Rp 0' }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 maskapai-harga"
                                placeholder="Rp 0"
                                oninput="formatRupiahMaskapai(this, 'harga_maskapai_internasional_berangkat')"
                                onblur="formatRupiahMaskapai(this, 'harga_maskapai_internasional_berangkat')">
                            <input type="hidden" name="harga_maskapai_internasional_berangkat"
                                id="harga_maskapai_internasional_berangkat"
                                value="{{ $departure->harga_maskapai_internasional_berangkat }}">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Maskapai Internasional Pulang</label>
                        <select name="id_maskapai_internasional_pulang"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">-- Pilih --</option>
                            @foreach ($maskapaiOptions as $maskapai)
                                @if ($maskapai->is_internasional)
                                    <option value="{{ $maskapai->id_maskapai }}"
                                        {{ $departure->id_maskapai_internasional_pulang == $maskapai->id_maskapai ? 'selected' : '' }}>
                                        {{ $maskapai->nama_maskapai }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Internasional Pulang</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                            <input type="text" name="harga_maskapai_internasional_pulang_display"
                                id="harga_maskapai_internasional_pulang_display"
                                value="{{ $departure->harga_maskapai_internasional_pulang ? 'Rp ' . number_format($departure->harga_maskapai_internasional_pulang, 0, ',', '.') : 'Rp 0' }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 maskapai-harga"
                                placeholder="Rp 0"
                                oninput="formatRupiahMaskapai(this, 'harga_maskapai_internasional_pulang')"
                                onblur="formatRupiahMaskapai(this, 'harga_maskapai_internasional_pulang')">
                            <input type="hidden" name="harga_maskapai_internasional_pulang"
                                id="harga_maskapai_internasional_pulang"
                                value="{{ $departure->harga_maskapai_internasional_pulang }}">
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        Pastikan harga maskapai sudah termasuk pajak dan biaya lainnya.
                    </p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeMaskapaiModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL HOTEL -->
    <!-- ========================================== -->
    <div id="hotelModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-hotel text-yellow-500 mr-2"></i>
                    Tambah Hotel & Tipe Kamar
                </h5>
                <button onclick="closeHotelModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('transaksional.departure.update-hotel', $departure->id_departure) }}" method="POST"
                class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- SEARCH HOTEL -->
                <div class="mb-4">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="searchHotelModal" placeholder="Cari hotel..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Hotel Mekkah</label>
                    <select name="id_hotel_mekkah" id="id_hotel_mekkah"
                        class="hotel-select w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                        data-departure="{{ $departure->id_departure }}" data-container="kamarContainerMekkah"
                        data-total="totalHotelMekkah">
                        <option value="">-- Pilih --</option>
                        @foreach ($hotelOptions as $hotel)
                            @if ($hotel->kota == 'Mekkah')
                                <option value="{{ $hotel->id_hotel }}"
                                    {{ $departure->id_hotel_mekkah == $hotel->id_hotel ? 'selected' : '' }}>
                                    {{ $hotel->nama_hotel }} ({{ $hotel->bintang_text }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <div id="kamarContainerMekkah" class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3"></div>
                    <div class="mt-2 text-sm text-gray-500">
                        Total: <span id="totalHotelMekkah" class="font-bold text-yellow-600">Rp 0</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Hotel Madinah</label>
                    <select name="id_hotel_madinah" id="id_hotel_madinah"
                        class="hotel-select w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                        data-departure="{{ $departure->id_departure }}" data-container="kamarContainerMadinah"
                        data-total="totalHotelMadinah">
                        <option value="">-- Pilih --</option>
                        @foreach ($hotelOptions as $hotel)
                            @if ($hotel->kota == 'Madinah')
                                <option value="{{ $hotel->id_hotel }}"
                                    {{ $departure->id_hotel_madinah == $hotel->id_hotel ? 'selected' : '' }}>
                                    {{ $hotel->nama_hotel }} ({{ $hotel->bintang_text }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <div id="kamarContainerMadinah" class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3"></div>
                    <div class="mt-2 text-sm text-gray-500">
                        Total: <span id="totalHotelMadinah" class="font-bold text-yellow-600">Rp 0</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Hotel Transit</label>
                    <select name="id_hotel_transit" id="id_hotel_transit"
                        class="hotel-select w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                        data-departure="{{ $departure->id_departure }}" data-container="kamarContainerTransit"
                        data-total="totalHotelTransit">
                        <option value="">-- Pilih --</option>
                        @foreach ($hotelOptions as $hotel)
                            @if ($hotel->kota != 'Mekkah' && $hotel->kota != 'Madinah')
                                <option value="{{ $hotel->id_hotel }}"
                                    {{ $departure->id_hotel_transit == $hotel->id_hotel ? 'selected' : '' }}>
                                    {{ $hotel->nama_hotel }} ({{ $hotel->bintang_text }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <div id="kamarContainerTransit" class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3"></div>
                    <div class="mt-2 text-sm text-gray-500">
                        Total: <span id="totalHotelTransit" class="font-bold text-yellow-600">Rp 0</span>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeHotelModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL JAMAAH DENGAN SEARCH -->
    <!-- ========================================== -->
    <div id="jamaahModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-users text-yellow-500 mr-2"></i>
                    Tambah Daftar Jamaah
                    <span class="ml-2 text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full" id="syncBadge">
                        @if (session()->has('sync_jamaah_' . $departure->id_departure))
                            <i class="fas fa-sync-alt mr-1"></i>
                            {{ count(session('sync_jamaah_' . $departure->id_departure, [])) }} baru
                        @endif
                    </span>
                </h5>
                <button onclick="closeJamaahModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('transaksional.departure.update-jamaah', $departure->id_departure) }}" method="POST"
                class="p-6">
                @csrf
                @method('PUT')

                <!-- SEARCH JAMAAH -->
                <div class="mb-4">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="searchJamaahModal"
                            placeholder="Cari jamaah berdasarkan nama, produk, atau status..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                    </div>
                    <div class="flex items-center gap-4 mt-2 text-xs text-gray-400">
                        <span><i class="fas fa-info-circle mr-1"></i> Ketik untuk mencari jamaah</span>
                        <span id="jamaahCountModal" class="font-medium text-gray-600">{{ $jamaahs->count() }} jamaah
                            tersedia</span>
                        @if (session()->has('sync_jamaah_' . $departure->id_departure))
                            @php
                                $syncIds = session('sync_jamaah_' . $departure->id_departure, []);
                                $syncCount = count($syncIds);
                            @endphp
                            @if ($syncCount > 0)
                                <span class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full">
                                    <i class="fas fa-sync-alt mr-1"></i> {{ $syncCount }} jamaah baru dari sync
                                </span>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-60 overflow-y-auto border border-gray-200 rounded-xl p-4"
                    id="jamaahModalList">
                    @php
                        $selectedJamaahs = $departure->jamaahs->pluck('id_jamaah')->toArray();
                        // Ambil ID jamaah dari session sync
                        $syncIds = session('sync_jamaah_' . $departure->id_departure, []);
                    @endphp
                    @forelse($jamaahs as $jamaah)
                        <label
                            class="jamaah-modal-item flex items-center p-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer
                            {{ in_array($jamaah->id_jamaah, $syncIds) ? 'border-purple-300 bg-purple-50' : '' }}"
                            data-name="{{ strtolower($jamaah->nama_lengkap) }}"
                            data-produk="{{ strtolower($jamaah->produk_paket) }}"
                            data-status="{{ strtolower($jamaah->status_pembayaran) }}">
                            <input type="checkbox" name="jamaah_ids[]" value="{{ $jamaah->id_jamaah }}"
                                {{ in_array($jamaah->id_jamaah, $selectedJamaahs) ? 'checked' : '' }}
                                class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                            <div class="ml-2 overflow-hidden">
                                <p class="text-sm font-medium text-gray-700 truncate">{{ $jamaah->nama_lengkap }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $jamaah->produk_paket }}</p>
                                <p class="text-xs">
                                    <span
                                        class="px-1.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $jamaah->status_pembayaran == 'Lunas'
                                        ? 'bg-green-100 text-green-700'
                                        : ($jamaah->status_pembayaran == 'DP'
                                            ? 'bg-yellow-100 text-yellow-700'
                                            : ($jamaah->status_pembayaran == 'Setoran'
                                                ? 'bg-blue-100 text-blue-700'
                                                : 'bg-red-100 text-red-700')) }}">
                                        {{ $jamaah->status_pembayaran }}
                                    </span>
                                    @if (in_array($jamaah->id_jamaah, $syncIds))
                                        <span class="ml-1 text-xs text-purple-600">
                                            <i class="fas fa-sync-alt"></i> baru
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </label>
                    @empty
                        <p class="text-sm text-gray-400 col-span-3 text-center py-4">
                            Tidak ada jamaah yang tersedia untuk bulan/tahun keberangkatan ini.
                            <br><span class="text-xs">Pastikan jamaah sudah lunas dan memiliki bulan/tahun keberangkatan
                                yang sesuai.</span>
                        </p>
                    @endforelse
                </div>

                <div
                    class="mt-4 flex items-center justify-between text-sm bg-blue-50 rounded-lg px-4 py-2.5 border border-blue-200">
                    <div class="text-blue-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        Centang jamaah yang akan dimasukkan ke keberangkatan ini
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="selectAllJamaah()"
                            class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                            <i class="fas fa-check-double mr-1"></i> Pilih Semua
                        </button>
                        <button type="button" onclick="deselectAllJamaah()"
                            class="text-red-600 hover:text-red-800 text-xs font-medium">
                            <i class="fas fa-times mr-1"></i> Hapus Semua
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 mt-4">
                    <button type="button" onclick="closeJamaahModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL PERLENGKAPAN -->
    <!-- ========================================== -->
    <div id="perlengkapanModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800">Tambah Perlengkapan ke Departure</h5>
                <button onclick="closePerlengkapanModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('transaksional.departure.update-perlengkapan', $departure->id_departure) }}"
                method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Perlengkapan <span
                            class="text-red-500">*</span></label>
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-60 overflow-y-auto border border-gray-200 rounded-xl p-3">
                        @forelse($perlengkapanOptions as $perlengkapan)
                            <label
                                class="flex items-center p-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                                <input type="checkbox" name="id_perlengkapan[]"
                                    value="{{ $perlengkapan->id_perlengkapan }}"
                                    class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                                <div class="ml-2">
                                    <p class="text-sm font-medium text-gray-700">{{ $perlengkapan->nama_perlengkapan }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $perlengkapan->harga_satuan_formatted }} /
                                        {{ $perlengkapan->satuan ?? 'unit' }}</p>
                                </div>
                            </label>
                        @empty
                            <p class="text-sm text-gray-400 col-span-2 text-center py-4">Semua perlengkapan sudah
                                ditambahkan.</p>
                        @endforelse
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah per Jamaah</label>
                        <input type="number" name="jumlah_per_jamaah" value="1"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                            min="1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan</label>
                        <input type="text" name="keterangan"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                            placeholder="Catatan (opsional)">
                    </div>
                </div>

                <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        Perlengkapan akan diberikan ke <strong>SEMUA</strong> jamaah yang terdaftar di departure ini.
                    </p>
                    <p class="text-xs text-blue-500 mt-1">
                        Jumlah jamaah saat ini: <strong>{{ $departure->jamaahs->count() }}</strong> orang
                    </p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closePerlengkapanModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                        <i class="fas fa-save mr-2"></i> Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL TAMBAH JENIS TRANSAKSI KE DEPARTURE -->
    <!-- ========================================== -->
    <div id="jenisTransaksiModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-receipt text-yellow-500 mr-2"></i>
                    Tambah Jenis Transaksi ke Departure
                </h5>
                <button onclick="closeJenisTransaksiModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('transaksional.departure.update-jenis-transaksi', $departure->id_departure) }}"
                method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <!-- SEARCH JENIS TRANSAKSI -->
                <div class="mb-4">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="searchJenisTransaksiModal"
                            placeholder="Cari jenis transaksi berdasarkan nama atau keterangan..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                    </div>
                    <div class="flex items-center gap-4 mt-2 text-xs text-gray-400">
                        <span><i class="fas fa-info-circle mr-1"></i> Ketik untuk mencari jenis transaksi</span>
                        <span id="jenisTransaksiCountModal"
                            class="font-medium text-gray-600">{{ $jenisTransaksiOptions->count() }} jenis transaksi
                            tersedia</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Jenis Transaksi <span
                            class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto border border-gray-200 rounded-xl p-3"
                        id="jenisTransaksiModalList">
                        @forelse($jenisTransaksiOptions as $jenis)
                            <label
                                class="jenis-transaksi-modal-item flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer"
                                data-nama="{{ strtolower($jenis->nama) }}"
                                data-keterangan="{{ strtolower($jenis->keterangan ?? '') }}">
                                <input type="checkbox" name="id_jenis_transaksi[]" value="{{ $jenis->id_jenis }}"
                                    class="jenis-transaksi-checkbox w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500 mt-1">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-700">{{ $jenis->nama }}</p>
                                        <span class="text-xs text-gray-400">{{ $jenis->kode ?? '' }}</span>
                                    </div>
                                    @if ($jenis->keterangan)
                                        <p class="text-xs text-gray-400">{{ $jenis->keterangan }}</p>
                                    @endif
                                    <div class="grid grid-cols-2 gap-2 mt-2">
                                        <div>
                                            <label class="text-xs text-gray-500">Harga Satuan (per Jamaah)</label>
                                            <div class="relative">
                                                <span
                                                    class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                                <input type="text"
                                                    name="harga_satuan_display[{{ $jenis->id_jenis }}]"
                                                    id="harga_satuan_display_{{ $jenis->id_jenis }}"
                                                    class="jenis-harga w-full pl-8 pr-2 py-1.5 border border-gray-200 rounded text-sm rupiah-input"
                                                    placeholder="Rp 0"
                                                    oninput="formatRupiahJenisTransaksi(this, 'harga_satuan_{{ $jenis->id_jenis }}')"
                                                    onblur="formatRupiahJenisTransaksi(this, 'harga_satuan_{{ $jenis->id_jenis }}')"
                                                    value="Rp 0">
                                                <input type="hidden" name="harga_satuan[{{ $jenis->id_jenis }}]"
                                                    id="harga_satuan_{{ $jenis->id_jenis }}" value="0">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Catatan</label>
                                            <input type="text" name="catatan[{{ $jenis->id_jenis }}]"
                                                class="jenis-catatan w-full px-2 py-1.5 border border-gray-200 rounded text-sm"
                                                placeholder="Opsional">
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @empty
                            <p class="text-sm text-gray-400 col-span-2 text-center py-4">
                                Semua jenis transaksi sudah ditambahkan.
                            </p>
                        @endforelse
                    </div>
                    <p class="text-xs text-gray-400 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Centang jenis transaksi yang ingin ditambahkan. Harga akan dikalikan dengan jumlah jamaah
                        ({{ $departure->jamaahs->count() }} orang)
                    </p>
                </div>

                <!-- Default Value -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Default Harga Satuan</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                <input type="text" name="default_harga_satuan_display"
                                    id="default_harga_satuan_display"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm rupiah-input"
                                    placeholder="Rp 0" oninput="formatRupiahJenisTransaksi(this, 'default_harga_satuan')"
                                    onblur="formatRupiahJenisTransaksi(this, 'default_harga_satuan')" value="Rp 0">
                                <input type="hidden" name="default_harga_satuan" id="default_harga_satuan"
                                    value="0">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Default Catatan</label>
                            <input type="text" name="default_catatan" id="default_catatan"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Catatan untuk semua">
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 mt-3">
                        <button type="button" onclick="fillAllHarga()"
                            class="text-sm text-blue-500 hover:text-blue-700">
                            <i class="fas fa-fill-drip mr-1"></i> Isi Semua Harga
                        </button>
                        <button type="button" onclick="fillAllCatatan()"
                            class="text-sm text-blue-500 hover:text-blue-700">
                            <i class="fas fa-fill-drip mr-1"></i> Isi Semua Catatan
                        </button>
                        <button type="button" onclick="selectAllJenisTransaksi()"
                            class="text-sm text-green-500 hover:text-green-700">
                            <i class="fas fa-check-double mr-1"></i> Pilih Semua
                        </button>
                        <button type="button" onclick="deselectAllJenisTransaksi()"
                            class="text-sm text-red-500 hover:text-red-700">
                            <i class="fas fa-times mr-1"></i> Hapus Semua
                        </button>
                    </div>
                </div>

                <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        Jenis transaksi akan ditambahkan ke <strong>SEMUA</strong> jamaah yang terdaftar di departure ini.
                    </p>
                    <p class="text-xs text-blue-500 mt-1">
                        Jumlah jamaah saat ini: <strong>{{ $departure->jamaahs->count() }}</strong> orang
                    </p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeJenisTransaksiModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                        <i class="fas fa-save mr-2"></i> Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL EDIT HARGA JENIS TRANSAKSI -->
    <!-- ========================================== -->
    <div id="editJenisTransaksiModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800">Edit Harga Jenis Transaksi</h5>
                <button onclick="closeEditJenisTransaksiModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="editJenisTransaksiForm" method="POST" class="p-6">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Satuan (per Jamaah) <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                        <input type="text" name="harga_satuan_display" id="edit_harga_satuan_display"
                            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm rupiah-input"
                            placeholder="Rp 0" oninput="formatRupiahJenisTransaksi(this, 'edit_harga_satuan')"
                            onblur="formatRupiahJenisTransaksi(this, 'edit_harga_satuan')">
                        <input type="hidden" name="harga_satuan" id="edit_harga_satuan" value="0">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">
                        Jumlah jamaah saat ini: {{ $departure->jamaahs->count() }} orang
                    </p>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 mt-4">
                    <button type="button" onclick="closeEditJenisTransaksiModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL CATATAN -->
    <!-- ========================================== -->
    <div id="catatanModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-2xl w-full">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800">Tambah Catatan</h5>
                <button onclick="closeCatatanModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('transaksional.departure.update-catatan', $departure->id_departure) }}"
                method="POST" class="p-6">
                @csrf
                @method('PUT')
                <textarea name="catatan" rows="5"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                    placeholder="Catatan tambahan untuk keberangkatan ini...">{{ $departure->catatan }}</textarea>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 mt-4">
                    <button type="button" onclick="closeCatatanModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL DETAIL PERLENGKAPAN -->
    <!-- ========================================== -->
    <div id="perlengkapanDetailModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800" id="perlengkapanDetailTitle">Detail Perlengkapan</h5>
                <button onclick="closePerlengkapanDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-80px)]" id="perlengkapanDetailContent">
                <div class="flex items-center justify-center py-12">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin text-3xl text-yellow-500 mb-3"></i>
                        <p class="text-gray-500">Memuat data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="recalculate-form" action="{{ route('transaksional.departure.recalculate', $departure->id_departure) }}"
        method="POST" class="hidden">
        @csrf
    </form>
@endsection

@push('scripts')
    <script>
        // ============================================
        // SEARCH FUNCTIONS
        // ============================================

        document.addEventListener('DOMContentLoaded', function() {
            // Search Maskapai
            const searchMaskapai = document.getElementById('searchMaskapai');
            if (searchMaskapai) {
                searchMaskapai.addEventListener('keyup', function() {
                    const search = this.value.toLowerCase();
                    document.querySelectorAll('.maskapai-item').forEach(function(item) {
                        const name = item.dataset.nama || '';
                        if (name.includes(search)) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }

            // Search Hotel
            const searchHotel = document.getElementById('searchHotel');
            if (searchHotel) {
                searchHotel.addEventListener('keyup', function() {
                    const search = this.value.toLowerCase();
                    document.querySelectorAll('.hotel-item').forEach(function(item) {
                        const name = item.dataset.nama || '';
                        if (name.includes(search)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }

            // Search Hotel Tour
            const searchHotelTour = document.getElementById('searchHotelTour');
            if (searchHotelTour) {
                searchHotelTour.addEventListener('keyup', function() {
                    const search = this.value.toLowerCase();
                    document.querySelectorAll('.hotel-tour-item').forEach(function(item) {
                        const name = item.dataset.nama || '';
                        const kota = item.dataset.kota || '';
                        if (name.includes(search) || kota.includes(search)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }

            // Search Perlengkapan
            const searchPerlengkapan = document.getElementById('searchPerlengkapan');
            if (searchPerlengkapan) {
                searchPerlengkapan.addEventListener('keyup', function() {
                    const search = this.value.toLowerCase();
                    document.querySelectorAll('.perlengkapan-item').forEach(function(item) {
                        const name = item.dataset.nama || '';
                        if (name.includes(search)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }

            // Search Jamaah
            const searchJamaah = document.getElementById('searchJamaah');
            if (searchJamaah) {
                searchJamaah.addEventListener('keyup', function() {
                    const search = this.value.toLowerCase();
                    document.querySelectorAll('.jamaah-item').forEach(function(item) {
                        const name = item.dataset.name || '';
                        if (name.includes(search)) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }

            // Search Jenis Transaksi
            const searchJenisTransaksi = document.getElementById('searchJenisTransaksi');
            if (searchJenisTransaksi) {
                searchJenisTransaksi.addEventListener('keyup', function() {
                    const search = this.value.toLowerCase();
                    document.querySelectorAll('.jenis-transaksi-item').forEach(function(item) {
                        const name = item.dataset.nama || '';
                        if (name.includes(search)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }

            // Search Jamaah di Modal
            const searchJamaahModal = document.getElementById('searchJamaahModal');
            if (searchJamaahModal) {
                searchJamaahModal.addEventListener('keyup', function() {
                    const search = this.value.toLowerCase();
                    let visibleCount = 0;
                    document.querySelectorAll('.jamaah-modal-item').forEach(function(item) {
                        const name = item.dataset.name || '';
                        const produk = item.dataset.produk || '';
                        const status = item.dataset.status || '';
                        if (name.includes(search) || produk.includes(search) || status.includes(
                                search)) {
                            item.style.display = 'flex';
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    document.getElementById('jamaahCountModal').textContent = visibleCount +
                        ' jamaah tersedia';
                });
            }

            // Search Jenis Transaksi di Modal
            const searchJenisTransaksiModal = document.getElementById('searchJenisTransaksiModal');
            if (searchJenisTransaksiModal) {
                searchJenisTransaksiModal.addEventListener('keyup', function() {
                    const search = this.value.toLowerCase();
                    let visibleCount = 0;
                    document.querySelectorAll('.jenis-transaksi-modal-item').forEach(function(item) {
                        const nama = item.dataset.nama || '';
                        const keterangan = item.dataset.keterangan || '';
                        if (nama.includes(search) || keterangan.includes(search)) {
                            item.style.display = 'flex';
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    document.getElementById('jenisTransaksiCountModal').textContent = visibleCount +
                        ' jenis transaksi tersedia';
                });
            }

            // ============================================
            // AGENT SEARCH
            // ============================================
            const searchAgent = document.getElementById('searchAgent');
            if (searchAgent) {
                searchAgent.addEventListener('keyup', function() {
                    const search = this.value.toLowerCase();

                    document.querySelectorAll('.agent-group-item').forEach(function(item) {
                        const agentName = item.dataset.agent || '';
                        const jamaahs = item.querySelectorAll('.agent-group-content .grid .flex');
                        let hasMatch = false;

                        if (agentName.includes(search)) {
                            hasMatch = true;
                        } else {
                            jamaahs.forEach(function(jamaah) {
                                const name = jamaah.querySelector('.text-sm.font-medium')
                                    ?.textContent
                                    ?.toLowerCase() || '';
                                if (name.includes(search)) {
                                    hasMatch = true;
                                }
                            });
                        }

                        if (hasMatch || search === '') {
                            item.style.display = 'block';
                            if (search !== '') {
                                const content = item.querySelector('.agent-group-content');
                                const icon = item.querySelector('.fa-chevron-down');
                                if (content) content.classList.remove('hidden');
                                if (icon) icon.style.transform = 'rotate(180deg)';
                            }
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }
        });

        // ============================================
        // AGENT GROUP TOGGLE FUNCTIONS
        // ============================================

        function toggleAgentGroup(element) {
            const parent = element.closest('.agent-group-item');
            const content = parent.querySelector('.agent-group-content');
            const icon = element.querySelector('.fa-chevron-down');

            if (content) {
                if (content.classList.contains('hidden')) {
                    content.classList.remove('hidden');
                    if (icon) icon.style.transform = 'rotate(180deg)';
                } else {
                    content.classList.add('hidden');
                    if (icon) icon.style.transform = 'rotate(0deg)';
                }
            }
        }

        function toggleNoAgentList(element) {
            const parent = element.closest('.bg-white');
            const content = parent.querySelector('.no-agent-content');
            const icon = element.querySelector('.fa-chevron-down');

            if (content) {
                if (content.classList.contains('hidden')) {
                    content.classList.remove('hidden');
                    if (icon) icon.style.transform = 'rotate(180deg)';
                } else {
                    content.classList.add('hidden');
                    if (icon) icon.style.transform = 'rotate(0deg)';
                }
            }
        }

        // ============================================
        // PAKET TOUR HOTEL FUNCTIONS
        // ============================================

        function openPaketTourHotelModal() {
            document.getElementById('paketTourHotelModal').classList.remove('hidden');
            calculateTotalPaketTourHotel();
            loadAllKamars();
        }

        function closePaketTourHotelModal() {
            document.getElementById('paketTourHotelModal').classList.add('hidden');
        }

        function loadAllKamars() {
            document.querySelectorAll('.hotel-tour-item').forEach(function(item) {
                const hotelId = item.dataset.hotelId;
                const index = item.dataset.index;
                loadKamarsForHotelTour(hotelId, index);
            });
        }

        function loadKamarsForHotelTour(hotelId, index) {
            const selectElement = document.getElementById('tipeKamar-' + index);
            const loadingElement = document.getElementById('kamarLoading-' + index);
            const countElement = document.getElementById('kamarCount-' + index);

            if (!hotelId) {
                selectElement.innerHTML = '<option value="">-- Pilih Hotel Terlebih Dahulu --</option>';
                countElement.textContent = '0 tipe kamar';
                return;
            }

            loadingElement.classList.remove('hidden');
            selectElement.disabled = true;

            fetch(`/transaksional/get-kamars-by-hotel/${hotelId}`)
                .then(response => response.json())
                .then(data => {
                    loadingElement.classList.add('hidden');
                    selectElement.disabled = false;

                    if (data.length > 0) {
                        let options = '<option value="">-- Pilih Tipe Kamar --</option>';
                        data.forEach(function(kamar) {
                            let label = kamar.tipe_kamar;
                            if (kamar.kapasitas) {
                                label += ' (Kapasitas: ' + kamar.kapasitas + ' orang)';
                            }
                            if (kamar.fasilitas_kamar) {
                                label += ' - ' + kamar.fasilitas_kamar.substring(0, 30);
                            }
                            options += '<option value="' + kamar.tipe_kamar + '">' + label + '</option>';
                        });
                        selectElement.innerHTML = options;
                        countElement.textContent = data.length + ' tipe kamar';

                        const item = document.querySelector(`.hotel-tour-item[data-index="${index}"]`);
                        if (item) {
                            const existingTipe = item.querySelector('input[name$="[tipe_kamar]"]');
                            if (existingTipe && existingTipe.value) {
                                selectElement.value = existingTipe.value;
                            }
                        }
                    } else {
                        selectElement.innerHTML = '<option value="">-- Tidak ada tipe kamar --</option>';
                        countElement.textContent = '0 tipe kamar';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    loadingElement.classList.add('hidden');
                    selectElement.innerHTML = '<option value="">-- Gagal memuat data --</option>';
                    selectElement.disabled = false;
                    countElement.textContent = 'Error';
                });
        }

        function calculateTotalPaketTourHotel() {
            let total = 0;
            document.querySelectorAll('.hotel-tour-item').forEach(function(item) {
                const checkbox = item.querySelector('.hotel-tour-checkbox');
                if (checkbox && checkbox.checked) {
                    const price = parseInt(item.querySelector('.hotel-tour-price').value) || 0;
                    const durasi = parseInt(item.querySelector('.hotel-tour-durasi').value) || 1;
                    const kamar = parseInt(item.querySelector('.hotel-tour-kamar').value) || 1;
                    total += price * durasi * kamar;
                }
            });
            document.getElementById('totalPaketTourHotelPreview').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        // ============================================
        // MASKAPAI FUNCTIONS
        // ============================================

        function openMaskapaiModal() {
            document.getElementById('maskapaiModal').classList.remove('hidden');
            // Inisialisasi format rupiah untuk semua input harga di modal maskapai
            document.querySelectorAll('.maskapai-harga').forEach(function(input) {
                const hiddenInput = document.getElementById(input.id.replace('display', ''));
                let value = 0;
                if (hiddenInput) {
                    value = parseInt(hiddenInput.value) || 0;
                }
                if (value > 0) {
                    input.value = 'Rp ' + value.toLocaleString('id-ID');
                } else {
                    input.value = 'Rp 0';
                }
            });
        }

        function closeMaskapaiModal() {
            document.getElementById('maskapaiModal').classList.add('hidden');
        }

        // ============================================
        // HOTEL FUNCTIONS
        // ============================================

        function openHotelModal() {
            document.getElementById('hotelModal').classList.remove('hidden');
            loadKamarsWithSelected('id_hotel_mekkah', 'kamarContainerMekkah', 'totalHotelMekkah');
            loadKamarsWithSelected('id_hotel_madinah', 'kamarContainerMadinah', 'totalHotelMadinah');
            loadKamarsWithSelected('id_hotel_transit', 'kamarContainerTransit', 'totalHotelTransit');
        }

        function closeHotelModal() {
            document.getElementById('hotelModal').classList.add('hidden');
        }

        function loadKamarsWithSelected(hotelSelectId, containerId, totalId) {
            const hotelId = document.getElementById(hotelSelectId).value;
            const container = document.getElementById(containerId);
            const departureId = {{ $departure->id_departure }};

            if (!hotelId) {
                container.innerHTML =
                    '<p class="text-sm text-gray-400 col-span-2 text-center py-4">Silakan pilih hotel terlebih dahulu untuk melihat tipe kamar</p>';
                return;
            }

            container.innerHTML =
                '<p class="text-sm text-gray-400 col-span-2 text-center py-4"><i class="fas fa-spinner fa-spin mr-2"></i> Memuat tipe kamar...</p>';

            fetch(`/transaksional/get-kamars-by-hotel-with-selected/${hotelId}/${departureId}`)
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = data.html;

                    container.querySelectorAll('.kamar-checkbox').forEach(function(checkbox) {
                        checkbox.addEventListener('change', function() {
                            calculateHotelTotal(containerId, totalId);
                            const parent = this.closest('.kamar-item');
                            const inputs = parent.querySelectorAll('input:not(.kamar-checkbox)');
                            inputs.forEach(function(input) {
                                input.disabled = !checkbox.checked;
                                if (!checkbox.checked) {
                                    input.classList.add('opacity-50', 'bg-gray-50');
                                } else {
                                    input.classList.remove('opacity-50', 'bg-gray-50');
                                }
                            });
                        });
                        checkbox.dispatchEvent(new Event('change'));
                    });

                    container.querySelectorAll('.kamar-jumlah, .kamar-harga, .kamar-durasi').forEach(function(input) {
                        input.addEventListener('change', function() {
                            calculateHotelTotal(containerId, totalId);
                        });
                        input.addEventListener('keyup', function() {
                            calculateHotelTotal(containerId, totalId);
                        });
                    });

                    calculateHotelTotal(containerId, totalId);
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.innerHTML =
                        '<p class="text-sm text-red-500 col-span-2 text-center py-4">Gagal memuat tipe kamar. Silakan coba lagi.</p>';
                });
        }

        function calculateHotelTotal(containerId, totalId) {
            const container = document.getElementById(containerId);
            let total = 0;

            container.querySelectorAll('.kamar-item').forEach(function(item) {
                const checkbox = item.querySelector('.kamar-checkbox');
                if (checkbox && checkbox.checked) {
                    const jumlah = parseInt(item.querySelector('.kamar-jumlah').value) || 0;
                    const hargaInput = item.querySelector('.kamar-harga');
                    let harga = 0;
                    if (hargaInput) {
                        const rawValue = hargaInput.value.replace(/^Rp\s*/, '').replace(/\./g, '');
                        harga = parseInt(rawValue) || 0;
                    }
                    const durasi = parseInt(item.querySelector('.kamar-durasi').value) || 1;
                    total += jumlah * harga * durasi;
                }
            });

            document.getElementById(totalId).textContent = 'Rp ' + total.toLocaleString('id-ID');
            return total;
        }

        // ============================================
        // JAMAAH FUNCTIONS
        // ============================================

        function openJamaahModal() {
            document.getElementById('jamaahModal').classList.remove('hidden');
            document.getElementById('searchJamaahModal').value = '';
            document.querySelectorAll('.jamaah-modal-item').forEach(function(item) {
                item.style.display = 'flex';
            });
            document.getElementById('jamaahCountModal').textContent = '{{ $jamaahs->count() }} jamaah tersedia';
        }

        function closeJamaahModal() {
            document.getElementById('jamaahModal').classList.add('hidden');
        }

        function removeJamaah(departureId, jamaahId) {
            if (confirm('Yakin ingin menghapus jamaah ini dari keberangkatan?')) {
                document.getElementById('remove-jamaah-form-' + departureId + '-' + jamaahId).submit();
            }
        }

        function selectAllJamaah() {
            document.querySelectorAll('#jamaahModalList .jamaah-modal-item input[type="checkbox"]').forEach(function(
                checkbox) {
                checkbox.checked = true;
            });
        }

        function deselectAllJamaah() {
            document.querySelectorAll('#jamaahModalList .jamaah-modal-item input[type="checkbox"]').forEach(function(
                checkbox) {
                checkbox.checked = false;
            });
        }

        // ============================================
        // PERLENGKAPAN FUNCTIONS
        // ============================================

        function openPerlengkapanModal() {
            document.getElementById('perlengkapanModal').classList.remove('hidden');
        }

        function closePerlengkapanModal() {
            document.getElementById('perlengkapanModal').classList.add('hidden');
        }

        function removePerlengkapan(departureId, departurePerlengkapanId) {
            if (confirm('Yakin ingin menghapus perlengkapan ini dari departure?')) {
                document.getElementById('remove-perlengkapan-form-' + departureId + '-' + departurePerlengkapanId)
                    .submit();
            }
        }

        function openPerlengkapanDetailModal(perlengkapanId) {
            const modal = document.getElementById('perlengkapanDetailModal');
            const content = document.getElementById('perlengkapanDetailContent');

            content.innerHTML =
                `<div class="flex items-center justify-center py-12"><div class="text-center"><i class="fas fa-spinner fa-spin text-3xl text-yellow-500 mb-3"></i><p class="text-gray-500">Memuat data...</p></div></div>`;

            modal.classList.remove('hidden');

            fetch(`/transaksional/get-perlengkapan-detail/${perlengkapanId}`)
                .then(response => response.json())
                .then(data => {
                    content.innerHTML = data.html;
                    document.getElementById('perlengkapanDetailTitle').textContent = 'Detail: ' + data
                        .nama_perlengkapan;
                })
                .catch(error => {
                    console.error('Error:', error);
                    content.innerHTML =
                        `<div class="text-center py-12"><i class="fas fa-exclamation-circle text-3xl text-red-500 mb-3"></i><p class="text-gray-500">Gagal memuat data. Silakan coba lagi.</p></div>`;
                });
        }

        function closePerlengkapanDetailModal() {
            document.getElementById('perlengkapanDetailModal').classList.add('hidden');
        }

        function toggleStatusJamaah(departurePerlengkapanId, jamaahId, currentStatus) {
            const newStatus = currentStatus === 'Sudah Diterima' ? 'Belum Diterima' : 'Sudah Diterima';
            document.getElementById('status_input_' + departurePerlengkapanId + '_' + jamaahId).value = newStatus;
            document.getElementById('status-jamaah-form-' + departurePerlengkapanId + '-' + jamaahId).submit();
        }

        // ============================================
        // JENIS TRANSAKSI FUNCTIONS
        // ============================================

        function openJenisTransaksiModal() {
            document.getElementById('jenisTransaksiModal').classList.remove('hidden');
            document.getElementById('searchJenisTransaksiModal').value = '';
            document.querySelectorAll('.jenis-transaksi-modal-item').forEach(function(item) {
                item.style.display = 'flex';
            });
            document.getElementById('jenisTransaksiCountModal').textContent =
                '{{ $jenisTransaksiOptions->count() }} jenis transaksi tersedia';

            // Inisialisasi format rupiah untuk semua input harga
            document.querySelectorAll('#jenisTransaksiModalList .jenis-harga').forEach(function(input) {
                const hiddenId = input.id.replace('display_', '');
                const hiddenInput = document.getElementById(hiddenId);
                let value = 0;
                if (hiddenInput) {
                    value = parseInt(hiddenInput.value) || 0;
                }
                if (value > 0) {
                    input.value = 'Rp ' + value.toLocaleString('id-ID');
                } else {
                    input.value = 'Rp 0';
                }
            });

            // Inisialisasi default harga
            const defaultHargaDisplay = document.getElementById('default_harga_satuan_display');
            const defaultHarga = document.getElementById('default_harga_satuan');
            if (defaultHargaDisplay && defaultHarga) {
                let value = parseInt(defaultHarga.value) || 0;
                if (value > 0) {
                    defaultHargaDisplay.value = 'Rp ' + value.toLocaleString('id-ID');
                } else {
                    defaultHargaDisplay.value = 'Rp 0';
                }
            }
        }

        function closeJenisTransaksiModal() {
            document.getElementById('jenisTransaksiModal').classList.add('hidden');
        }

        function removeJenisTransaksi(departureId, jenisTransaksiId) {
            if (confirm('Yakin ingin menghapus jenis transaksi ini dari departure?')) {
                document.getElementById('remove-jenis-transaksi-form-' + departureId + '-' + jenisTransaksiId).submit();
            }
        }

        function editJenisTransaksiHarga(departureId, jenisTransaksiId, currentHarga) {
            const modal = document.getElementById('editJenisTransaksiModal');
            const form = document.getElementById('editJenisTransaksiForm');

            form.action = '/transaksional/departure/' + departureId + '/jenis-transaksi/' + jenisTransaksiId + '/harga';

            const displayInput = document.getElementById('edit_harga_satuan_display');
            const hiddenInput = document.getElementById('edit_harga_satuan');

            if (displayInput && hiddenInput) {
                const number = parseInt(currentHarga) || 0;
                displayInput.value = 'Rp ' + number.toLocaleString('id-ID');
                hiddenInput.value = number;
            }

            modal.classList.remove('hidden');
        }

        function closeEditJenisTransaksiModal() {
            document.getElementById('editJenisTransaksiModal').classList.add('hidden');
        }

        function fillAllHarga() {
            const defaultHargaDisplay = document.getElementById('default_harga_satuan_display');
            const defaultHarga = document.getElementById('default_harga_satuan');

            if (!defaultHarga.value || defaultHarga.value == '0') {
                alert('Silakan isi default harga satuan terlebih dahulu!');
                return;
            }

            document.querySelectorAll('#jenisTransaksiModalList .jenis-harga').forEach(function(input) {
                if (!input.disabled) {
                    input.value = defaultHargaDisplay.value;
                    const hiddenId = input.id.replace('display_', '');
                    const hiddenInput = document.getElementById(hiddenId);
                    if (hiddenInput) {
                        hiddenInput.value = defaultHarga.value;
                    }
                }
            });
        }

        function fillAllCatatan() {
            const defaultCatatan = document.getElementById('default_catatan').value;
            document.querySelectorAll('#jenisTransaksiModalList .jenis-catatan').forEach(function(input) {
                if (!input.disabled) {
                    input.value = defaultCatatan;
                }
            });
        }

        function selectAllJenisTransaksi() {
            document.querySelectorAll('#jenisTransaksiModalList .jenis-transaksi-modal-item input[type="checkbox"]')
                .forEach(function(checkbox) {
                    checkbox.checked = true;
                    checkbox.dispatchEvent(new Event('change'));
                });
        }

        function deselectAllJenisTransaksi() {
            document.querySelectorAll('#jenisTransaksiModalList .jenis-transaksi-modal-item input[type="checkbox"]')
                .forEach(function(checkbox) {
                    checkbox.checked = false;
                    checkbox.dispatchEvent(new Event('change'));
                });
        }

        // ============================================
        // CATATAN FUNCTIONS
        // ============================================

        function openCatatanModal() {
            document.getElementById('catatanModal').classList.remove('hidden');
        }

        function closeCatatanModal() {
            document.getElementById('catatanModal').classList.add('hidden');
        }

        // ============================================
        // RECALCULATE
        // ============================================

        function recalculate(id) {
            if (confirm('Yakin ingin menghitung ulang data keuangan?')) {
                document.getElementById('recalculate-form').submit();
            }
        }

        // ============================================
        // FORMAT RUPIAH HOTEL TOUR FUNCTION
        // ============================================

        function formatRupiahHotelTour(input, index) {
            let value = input.value.replace(/[^,\d]/g, '');
            value = value.replace(/^Rp\s*/, '');
            value = value.replace(/\./g, '');
            value = value.replace(/,/g, '.');

            if (value === '' || isNaN(value)) {
                value = '0';
            }

            const number = parseInt(value);
            const formatted = 'Rp ' + number.toLocaleString('id-ID');

            input.value = formatted;

            const hiddenInput = document.getElementById('harga_per_malam_' + index);
            if (hiddenInput) {
                hiddenInput.value = number;
            }

            calculateTotalPaketTourHotel();
        }

        // ============================================
        // FORMAT RUPIAH MASKAPAI FUNCTION
        // ============================================

        function formatRupiahMaskapai(input, hiddenId) {
            let value = input.value.replace(/[^,\d]/g, '');
            value = value.replace(/^Rp\s*/, '');
            value = value.replace(/\./g, '');
            value = value.replace(/,/g, '.');

            if (value === '' || isNaN(value)) {
                value = '0';
            }

            const number = parseInt(value);
            const formatted = 'Rp ' + number.toLocaleString('id-ID');

            input.value = formatted;

            const hiddenInput = document.getElementById(hiddenId);
            if (hiddenInput) {
                hiddenInput.value = number;
            }
        }

        // ============================================
        // FORMAT RUPIAH JENIS TRANSAKSI FUNCTION
        // ============================================

        function formatRupiahJenisTransaksi(input, hiddenId) {
            let value = input.value.replace(/[^,\d]/g, '');
            value = value.replace(/^Rp\s*/, '');
            value = value.replace(/\./g, '');
            value = value.replace(/,/g, '.');

            if (value === '' || isNaN(value)) {
                value = '0';
            }

            const number = parseInt(value);
            const formatted = 'Rp ' + number.toLocaleString('id-ID');

            input.value = formatted;

            const hiddenInput = document.getElementById(hiddenId);
            if (hiddenInput) {
                hiddenInput.value = number;
            }
        }

        // ============================================
        // FORMAT RUPIAH HOTEL FUNCTION
        // ============================================

        function formatRupiahHotel(input, kamarId) {
            let value = input.value.replace(/[^,\d]/g, '');
            value = value.replace(/^Rp\s*/, '');
            value = value.replace(/\./g, '');
            value = value.replace(/,/g, '.');

            if (value === '' || isNaN(value)) {
                value = '0';
            }

            const number = parseInt(value);
            const formatted = 'Rp ' + number.toLocaleString('id-ID');

            input.value = formatted;

            const hiddenInput = document.getElementById('kamar_harga_' + kamarId);
            if (hiddenInput) {
                hiddenInput.value = number;
            }

            const container = input.closest('.kamar-item').closest('.grid');
            if (container) {
                const containerId = container.id;
                const totalId = containerId.replace('kamarContainer', 'totalHotel');
                calculateHotelTotal(containerId, totalId);
            }
        }

        // ============================================
        // INITIAL EVENT LISTENERS
        // ============================================

        document.addEventListener('DOMContentLoaded', function() {
            // Hotel Tour Checkbox
            document.querySelectorAll('.hotel-tour-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const parent = this.closest('.hotel-tour-item');
                    const inputs = parent.querySelectorAll('input:not(.hotel-tour-checkbox)');

                    inputs.forEach(function(input) {
                        input.disabled = !checkbox.checked;
                        if (!checkbox.checked) {
                            input.classList.add('opacity-50', 'bg-gray-50');
                        } else {
                            input.classList.remove('opacity-50', 'bg-gray-50');
                        }
                    });

                    calculateTotalPaketTourHotel();
                });
                checkbox.dispatchEvent(new Event('change'));
            });

            document.querySelectorAll('.hotel-tour-price, .hotel-tour-durasi, .hotel-tour-kamar').forEach(function(
                input) {
                input.addEventListener('input', function() {
                    calculateTotalPaketTourHotel();
                });
                input.addEventListener('change', function() {
                    calculateTotalPaketTourHotel();
                });
            });

            // Hotel Select
            document.querySelectorAll('.hotel-select').forEach(function(select) {
                select.addEventListener('change', function() {
                    const containerId = this.dataset.container;
                    const totalId = this.dataset.total;
                    loadKamarsWithSelected(this.id, containerId, totalId);
                });
            });

            // Jenis Transaksi Checkbox di Modal
            document.querySelectorAll('.jenis-transaksi-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const parent = this.closest('.jenis-transaksi-modal-item');
                    if (parent) {
                        const inputs = parent.querySelectorAll(
                            'input[type="number"], input[type="text"]');
                        inputs.forEach(function(input) {
                            input.disabled = !checkbox.checked;
                            if (!checkbox.checked) {
                                input.classList.add('opacity-50', 'bg-gray-50');
                            } else {
                                input.classList.remove('opacity-50', 'bg-gray-50');
                            }
                        });
                    }
                });
                checkbox.dispatchEvent(new Event('change'));
            });

            // Close modal on click outside
            document.querySelectorAll('.fixed.inset-0.bg-black\\/50').forEach(function(modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.classList.add('hidden');
                    }
                });
            });

            // Inisialisasi agent groups default: collapsed
            document.querySelectorAll('.agent-group-content').forEach(function(content) {
                content.classList.add('hidden');
            });
            document.querySelectorAll('.no-agent-content').forEach(function(content) {
                content.classList.add('hidden');
            });
        });
        // ============================================
        // SYNC JAMAAH FUNCTION
        // ============================================

        function syncJamaah() {
            const departureId = {{ $departure->id_departure }};
            const modalList = document.getElementById('jamaahModalList');
            const modal = document.getElementById('jamaahModal');

            // Tampilkan loading di modal
            modal.classList.remove('hidden');
            modalList.innerHTML = `
        <div class="col-span-3 text-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-purple-500 mb-2"></i>
            <p class="text-gray-500">Mencari jamaah baru...</p>
        </div>
    `;

            // Update count
            document.getElementById('jamaahCountModal').textContent = 'Memuat...';
            document.getElementById('searchJamaahModal').value = '';

            // Kirim request
            fetch('{{ route('transaksional.departure.sync-jamaahs', $departure->id_departure) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update modal list dengan jamaah baru
                        modalList.innerHTML = data.html;
                        document.getElementById('jamaahCountModal').textContent = data.count + ' jamaah tersedia';

                        // Tampilkan pesan sukses
                        if (data.count > 0) {
                            // Gunakan toast atau alert
                            if (typeof toastr !== 'undefined') {
                                toastr.success(data.message);
                            } else {
                                // Tampilkan pesan di modal
                                const infoDiv = document.createElement('div');
                                infoDiv.className = 'mt-2 text-sm text-green-600 font-medium';
                                infoDiv.innerHTML = '<i class="fas fa-check-circle mr-1"></i> ' + data.message;
                                const parentDiv = document.getElementById('jamaahModalList').parentNode;
                                const existingInfo = parentDiv.querySelector('.sync-info');
                                if (existingInfo) existingInfo.remove();
                                parentDiv.insertBefore(infoDiv, document.getElementById('jamaahModalList'));
                            }
                        } else {
                            if (typeof toastr !== 'undefined') {
                                toastr.info(data.message);
                            }
                        }
                    } else {
                        modalList.innerHTML = `
                <div class="col-span-3 text-center py-8">
                    <i class="fas fa-exclamation-circle text-2xl text-red-500 mb-2"></i>
                    <p class="text-gray-500">${data.message}</p>
                </div>
            `;
                        document.getElementById('jamaahCountModal').textContent = '0 jamaah tersedia';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalList.innerHTML = `
            <div class="col-span-3 text-center py-8">
                <i class="fas fa-exclamation-circle text-2xl text-red-500 mb-2"></i>
                <p class="text-gray-500">Gagal memuat data. Silakan coba lagi.</p>
            </div>
        `;
                    document.getElementById('jamaahCountModal').textContent = 'Error';
                });
        }
    </script>
@endpush
