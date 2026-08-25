{{-- resources/views/departures/show.blade.php --}}

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
                <div class="flex items-center gap-2">
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
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-yellow-600">
                                {{ $departure->total_pendapatan_bersih_formatted }}</p>
                            <p class="text-sm text-gray-500">Total Pendapatan Bersih</p>
                            <p class="text-sm text-green-600">{{ $departure->laba_bersih_formatted }} Laba</p>
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

                <!-- Grid Informasi -->
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
                                    <i class="fas fa-plus mr-1"></i> Atur Maskapai & Harga
                                </button>
                            </div>
                        </div>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">Domestik Berangkat</dt>
                                <dd class="font-medium text-gray-700 text-right">
                                    <div>{{ $departure->maskapaiDomestikBerangkat->nama_maskapai ?? '-' }}</div>
                                    <div class="text-xs text-yellow-600 font-bold">
                                        {{ $departure->harga_maskapai_domestik_berangkat_formatted }}
                                    </div>
                                </dd>
                            </div>
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">Domestik Pulang</dt>
                                <dd class="font-medium text-gray-700 text-right">
                                    <div>{{ $departure->maskapaiDomestikPulang->nama_maskapai ?? '-' }}</div>
                                    <div class="text-xs text-yellow-600 font-bold">
                                        {{ $departure->harga_maskapai_domestik_pulang_formatted }}
                                    </div>
                                </dd>
                            </div>
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">Internasional Berangkat</dt>
                                <dd class="font-medium text-gray-700 text-right">
                                    <div>{{ $departure->maskapaiInternasionalBerangkat->nama_maskapai ?? '-' }}</div>
                                    <div class="text-xs text-yellow-600 font-bold">
                                        {{ $departure->harga_maskapai_internasional_berangkat_formatted }}
                                    </div>
                                </dd>
                            </div>
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">Internasional Pulang</dt>
                                <dd class="font-medium text-gray-700 text-right">
                                    <div>{{ $departure->maskapaiInternasionalPulang->nama_maskapai ?? '-' }}</div>
                                    <div class="text-xs text-yellow-600 font-bold">
                                        {{ $departure->harga_maskapai_internasional_pulang_formatted }}
                                    </div>
                                </dd>
                            </div>
                            <div class="flex justify-between text-sm font-semibold border-t border-gray-200 pt-2 mt-2">
                                <dt class="text-gray-700">Total Harga Maskapai</dt>
                                <dd class="text-yellow-600">{{ $departure->total_harga_maskapai_formatted }}</dd>
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
                                    <i class="fas fa-plus mr-1"></i> Atur Hotel & Tipe Kamar
                                </button>
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

                        @if ($departure->hotelMekkah)
                            <div class="mb-4">
                                <div class="flex items-center justify-between">
                                    <h5 class="text-sm font-semibold text-gray-700">{{ $departure->hotel_mekkah_nama }}
                                    </h5>
                                    <span
                                        class="text-sm font-bold text-yellow-600">{{ $departure->total_harga_hotel_mekkah_formatted }}</span>
                                </div>
                                @if ($hotelMekkahDetails->count() > 0)
                                    <div class="grid grid-cols-1 gap-2 mt-2">
                                        @foreach ($hotelMekkahDetails as $detail)
                                            <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                <p class="text-sm font-medium text-gray-700">{{ $detail->tipe_kamar }}</p>
                                                <p class="text-xs text-gray-500">{{ $detail->jumlah_kamar }} Kamar ×
                                                    {{ $detail->harga_per_malam_formatted }} ×
                                                    {{ $detail->durasi_menginap }} Malam</p>
                                                <p class="text-sm font-bold text-yellow-600">
                                                    {{ $detail->total_harga_formatted }}</p>
                                                @if ($detail->catatan)
                                                    <p class="text-xs text-gray-400 mt-1">{{ $detail->catatan }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if ($departure->hotelMadinah)
                            <div class="mb-4">
                                <div class="flex items-center justify-between">
                                    <h5 class="text-sm font-semibold text-gray-700">{{ $departure->hotel_madinah_nama }}
                                    </h5>
                                    <span
                                        class="text-sm font-bold text-yellow-600">{{ $departure->total_harga_hotel_madinah_formatted }}</span>
                                </div>
                                @if ($hotelMadinahDetails->count() > 0)
                                    <div class="grid grid-cols-1 gap-2 mt-2">
                                        @foreach ($hotelMadinahDetails as $detail)
                                            <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                <p class="text-sm font-medium text-gray-700">{{ $detail->tipe_kamar }}</p>
                                                <p class="text-xs text-gray-500">{{ $detail->jumlah_kamar }} Kamar ×
                                                    {{ $detail->harga_per_malam_formatted }} ×
                                                    {{ $detail->durasi_menginap }} Malam</p>
                                                <p class="text-sm font-bold text-yellow-600">
                                                    {{ $detail->total_harga_formatted }}</p>
                                                @if ($detail->catatan)
                                                    <p class="text-xs text-gray-400 mt-1">{{ $detail->catatan }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if ($departure->hotelTransit)
                            <div>
                                <div class="flex items-center justify-between">
                                    <h5 class="text-sm font-semibold text-gray-700">{{ $departure->hotel_transit_nama }}
                                    </h5>
                                    <span
                                        class="text-sm font-bold text-yellow-600">{{ $departure->total_harga_hotel_transit_formatted }}</span>
                                </div>
                                @if ($hotelTransitDetails->count() > 0)
                                    <div class="grid grid-cols-1 gap-2 mt-2">
                                        @foreach ($hotelTransitDetails as $detail)
                                            <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                <p class="text-sm font-medium text-gray-700">{{ $detail->tipe_kamar }}</p>
                                                <p class="text-xs text-gray-500">{{ $detail->jumlah_kamar }} Kamar ×
                                                    {{ $detail->harga_per_malam_formatted }} ×
                                                    {{ $detail->durasi_menginap }} Malam</p>
                                                <p class="text-sm font-bold text-yellow-600">
                                                    {{ $detail->total_harga_formatted }}</p>
                                                @if ($detail->catatan)
                                                    <p class="text-xs text-gray-400 mt-1">{{ $detail->catatan }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if (!$departure->hotelMekkah && !$departure->hotelMadinah && !$departure->hotelTransit)
                            <div class="text-center py-6">
                                <div
                                    class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-hotel text-gray-300 text-xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm">Belum ada data hotel</p>
                                <p class="text-gray-400 text-xs mt-1">Klik tombol "Atur Hotel & Tipe Kamar" untuk
                                    menambahkan hotel</p>
                            </div>
                        @endif
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

                        @if ($departure->produk && $departure->produk->include_tur && $departure->produk->paketTour)
                            @php
                                $tourHotels = $departure->departurePaketTourHotels->sortBy('urutan');
                            @endphp

                            @if ($tourHotels->count() > 0)
                                <div class="grid grid-cols-1 gap-3 max-h-60 overflow-y-auto pr-1">
                                    @foreach ($tourHotels as $item)
                                        <div
                                            class="bg-white rounded-lg p-3 border border-gray-200 hover:shadow-md transition-shadow duration-200">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2">
                                                        <span
                                                            class="inline-flex items-center justify-center w-6 h-6 bg-yellow-100 text-yellow-600 text-xs font-bold rounded-full flex-shrink-0">
                                                            {{ $loop->iteration }}
                                                        </span>
                                                        <div>
                                                            <p class="text-sm font-semibold text-gray-800 truncate">
                                                                {{ $item->hotel->nama_hotel }}
                                                            </p>
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
                                                    <p class="text-xs text-gray-400">
                                                        {{ $item->jumlah_kamar }} kamar ×
                                                        {{ $item->harga_per_malam_formatted }} ×
                                                        {{ $item->durasi_menginap }} malam
                                                    </p>
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
                                        class="text-lg font-bold text-yellow-600">{{ $departure->total_harga_paket_tour_hotel_formatted }}</span>
                                </div>
                            @else
                                <div class="text-center py-6">
                                    <div
                                        class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-umbrella-beach text-gray-300 text-xl"></i>
                                    </div>
                                    <p class="text-gray-500 text-sm">Belum ada hotel tour yang diatur</p>
                                    <p class="text-gray-400 text-xs mt-1">Klik tombol "Atur Hotel Tour" untuk mengatur
                                        hotel
                                        tour</p>
                                </div>
                            @endif

                            <!-- Informasi Paket Tour -->
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
                                    <div class="flex items-center justify-between text-sm col-span-2">
                                        <span class="text-gray-500">Harga Tour / Orang</span>
                                        <span
                                            class="font-medium text-yellow-600">{{ $departure->produk->paketTour->harga_per_orang_formatted ?? '-' }}</span>
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
                                <button onclick="openJamaahModal()"
                                    class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs rounded-lg hover:bg-yellow-600 transition">
                                    <i class="fas fa-plus mr-1"></i> Atur Daftar Jamaah
                                </button>
                            </div>
                        </div>

                        @if ($departure->jamaahs->count() > 0)
                            <div class="max-h-48 overflow-y-auto">
                                <ul class="divide-y divide-gray-200">
                                    @foreach ($departure->jamaahs as $jamaah)
                                        <li class="py-2 flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">{{ $jamaah->nama_lengkap }}
                                                </p>
                                                <p class="text-xs text-gray-400">{{ $jamaah->produk_paket }}</p>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="text-xs text-gray-500">{{ $jamaah->status_pembayaran }}</span>
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
                        @else
                            <div class="text-center py-6">
                                <div
                                    class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-users text-gray-300 text-xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm">Belum ada jamaah terdaftar</p>
                                <p class="text-gray-400 text-xs mt-1">Klik tombol "Atur Daftar Jamaah" untuk menambahkan
                                    jamaah</p>
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
                                    <i class="fas fa-plus mr-1"></i> Atur Perlengkapan Departure
                                </button>
                            </div>
                        </div>

                        @php
                            $perlengkapanList = $departure->departurePerlengkapan;
                            $totalJamaah = $departure->jamaahs->count();
                        @endphp

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

                                    <div
                                        class="bg-white rounded-lg p-3 border border-gray-200 hover:shadow-md transition-shadow duration-200">
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
                                                            {{ $perlengkapan->harga_satuan_formatted }}
                                                        </p>
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
                                                        <span class="text-yellow-600">{{ $belumTerima }} belum</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                                <div class="h-1.5 rounded-full transition-all duration-500 {{ $progressPercent >= 100 ? 'bg-green-500' : 'bg-yellow-500' }}"
                                                    style="width: {{ $progressPercent }}%"></div>
                                            </div>
                                        </div>

                                        <div class="mt-2 pt-2 border-t border-gray-100 flex items-center justify-between">
                                            <span class="text-xs text-gray-500">Total Harga</span>
                                            <span
                                                class="text-sm font-bold text-yellow-600">{{ $perlengkapan->total_harga_formatted }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div
                                    class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-box text-gray-300 text-xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm">Belum ada perlengkapan yang ditambahkan</p>
                                <p class="text-gray-400 text-xs mt-1">Klik tombol "Atur Perlengkapan Departure" untuk
                                    menambahkan perlengkapan
                                </p>
                            </div>
                        @endif
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

                        @php
                            $jenisTransaksiList = $departure->departureJenisTransaksis;
                            $totalJamaah = $departure->jamaahs->count();
                        @endphp

                        @if ($jenisTransaksiList->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto pr-1">
                                @foreach ($jenisTransaksiList as $item)
                                    <div
                                        class="bg-white rounded-lg p-3 border border-gray-200 hover:shadow-md transition-shadow duration-200">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <div
                                                        class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                        <i class="fas fa-tag text-purple-600 text-xs"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-800 truncate">
                                                            {{ $item->jenisTransaksi->nama }}
                                                        </p>
                                                        <p class="text-xs text-gray-400">
                                                            {{ $item->harga_satuan_formatted }} / jamaah
                                                        </p>
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

                                        <div class="mt-2 pt-2 border-t border-gray-100 flex items-center justify-between">
                                            <span class="text-xs text-gray-500">Total ({{ $totalJamaah }} jamaah)</span>
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
                                <p class="text-gray-400 text-xs mt-1">Klik tombol "Atur Jenis Transaksi" untuk menambahkan
                                    jenis
                                    transaksi</p>
                            </div>
                        @endif
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

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                <p class="text-xs text-gray-500">Pendapatan Kotor</p>
                                <p class="text-sm font-bold text-blue-600">
                                    {{ $departure->total_pendapatan_kotor_formatted }}</p>
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
                                <p class="text-xs text-gray-500">HPP</p>
                                <p class="text-sm font-bold text-purple-600">{{ $departure->total_hpp_formatted }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                <p class="text-xs text-gray-500">Laba Bersih</p>
                                <p
                                    class="text-lg font-bold {{ $departure->laba_bersih > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $departure->laba_bersih_formatted }}
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
                    <button type="button" onclick="window.print()"
                        class="px-4 py-2 bg-gray-100 rounded-lg">Cetak</button>
                    <button onclick="recalculate({{ $departure->id_departure }})"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg">Recalculate</button>
                    <a href="{{ route('transaksional.departure.index') }}"
                        class="px-4 py-2 bg-gray-200 rounded-lg">Kembali</a>
                    <a href="{{ route('transaksional.departure.edit', $departure->id_departure) }}"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg">Edit Dasar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL PAKET TOUR HOTEL -->
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
                        Hotel yang tersedia diambil dari daftar hotel pada paket tour.
                    </p>
                    <div class="flex items-center gap-4 mt-2 text-xs text-blue-600">
                        <span>Paket Tour: <strong>{{ $departure->produk->paketTour->kota_tujuan ?? '-' }}</strong></span>
                        <span>Durasi: <strong>{{ $departure->produk->paketTour->durasi_hari ?? 0 }} Hari</strong></span>
                        <span>Harga/Org:
                            <strong>{{ $departure->produk->paketTour->harga_per_orang_formatted ?? '-' }}</strong></span>
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
                                                        @if ($hotel->tipe_hotel)
                                                            · {{ $hotel->tipe_hotel }}
                                                        @endif
                                                    </p>
                                                </div>
                                                <span class="text-sm text-gray-400">
                                                    <i class="fas fa-door-open mr-1"></i>
                                                    <span id="kamarCount-{{ $loop->index }}">Memuat...</span>
                                                </span>
                                            </div>

                                            <!-- Tipe Kamar dari Model Kamar - Load dengan AJAX -->
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
                                                <div id="kamarError-{{ $loop->index }}"
                                                    class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg hidden">
                                                    <p class="text-xs text-red-600">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                                        Gagal memuat tipe kamar.
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-3">
                                                <div>
                                                    <label class="text-xs text-gray-500 font-medium">Harga per
                                                        Malam</label>
                                                    <div class="relative">
                                                        <span
                                                            class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                                        <input type="number"
                                                            name="paket_tour_hotels[{{ $loop->index }}][harga_per_malam]"
                                                            value="{{ $existing->harga_per_malam ?? ($hotel->harga_per_malam ?? 0) }}"
                                                            class="hotel-tour-price w-full pl-8 pr-2 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                                            min="0" {{ !$isChecked ? 'disabled' : '' }}>
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
    <!-- MODAL MASKAPAI -->
    <!-- ========================================== -->
    <div id="maskapaiModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800">Tambah Maskapai & Harga</h5>
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
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm">
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
                            <input type="number" name="harga_maskapai_domestik_berangkat"
                                value="{{ $departure->harga_maskapai_domestik_berangkat }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm" min="0">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Maskapai Domestik Pulang</label>
                        <select name="id_maskapai_domestik_pulang"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm">
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
                            <input type="number" name="harga_maskapai_domestik_pulang"
                                value="{{ $departure->harga_maskapai_domestik_pulang }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm" min="0">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Maskapai Internasional
                            Berangkat</label>
                        <select name="id_maskapai_internasional_berangkat"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm">
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
                            <input type="number" name="harga_maskapai_internasional_berangkat"
                                value="{{ $departure->harga_maskapai_internasional_berangkat }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm" min="0">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Maskapai Internasional Pulang</label>
                        <select name="id_maskapai_internasional_pulang"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm">
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
                            <input type="number" name="harga_maskapai_internasional_pulang"
                                value="{{ $departure->harga_maskapai_internasional_pulang }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm" min="0">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeMaskapaiModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">Simpan</button>
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
                <h5 class="text-lg font-semibold text-gray-800">Tambah Hotel & Tipe Kamar</h5>
                <button onclick="closeHotelModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('transaksional.departure.update-hotel', $departure->id_departure) }}" method="POST"
                class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Hotel Mekkah</label>
                    <select name="id_hotel_mekkah" id="id_hotel_mekkah"
                        class="hotel-select w-full px-4 py-2 border border-gray-200 rounded-xl text-sm"
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
                        class="hotel-select w-full px-4 py-2 border border-gray-200 rounded-xl text-sm"
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
                        class="hotel-select w-full px-4 py-2 border border-gray-200 rounded-xl text-sm"
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
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL JAMAAH -->
    <!-- ========================================== -->
    <div id="jamaahModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800">Tambah Daftar Jamaah</h5>
                <button onclick="closeJamaahModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('transaksional.departure.update-jamaah', $departure->id_departure) }}" method="POST"
                class="p-6">
                @csrf
                @method('PUT')

                <div
                    class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-60 overflow-y-auto border border-gray-200 rounded-xl p-4">
                    @php $selectedJamaahs = $departure->jamaahs->pluck('id_jamaah')->toArray(); @endphp
                    @forelse($jamaahs as $jamaah)
                        <label
                            class="flex items-center p-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                            <input type="checkbox" name="jamaah_ids[]" value="{{ $jamaah->id_jamaah }}"
                                {{ in_array($jamaah->id_jamaah, $selectedJamaahs) ? 'checked' : '' }}
                                class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                            <div class="ml-2">
                                <p class="text-sm font-medium text-gray-700">{{ $jamaah->nama_lengkap }}</p>
                                <p class="text-xs text-gray-400">{{ $jamaah->produk_paket }}</p>
                            </div>
                        </label>
                    @empty
                        <p class="text-sm text-gray-400 col-span-3 text-center py-4">Tidak ada jamaah yang tersedia</p>
                    @endforelse
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 mt-4">
                    <button type="button" onclick="closeJamaahModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">Simpan</button>
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
                            <p class="text-sm text-gray-400 col-span-2 text-center py-4">
                                Semua perlengkapan sudah ditambahkan.
                            </p>
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
    <!-- MODAL JENIS TRANSAKSI -->
    <!-- ========================================== -->
    <div id="jenisTransaksiModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800">Tambah Jenis Transaksi ke Departure</h5>
                <button onclick="closeJenisTransaksiModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('transaksional.departure.update-jenis-transaksi', $departure->id_departure) }}"
                method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Jenis Transaksi <span
                            class="text-red-500">*</span></label>
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-60 overflow-y-auto border border-gray-200 rounded-xl p-3">
                        @forelse($jenisTransaksiOptions as $jenis)
                            <label
                                class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                                <input type="checkbox" name="id_jenis_transaksi[]" value="{{ $jenis->id_jenis }}"
                                    class="jenis-transaksi-checkbox w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500 mt-1">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-700">{{ $jenis->nama }}</p>
                                        <span class="text-xs text-gray-400">{{ $jenis->keterangan ?? '' }}</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 mt-2">
                                        <div>
                                            <label class="text-xs text-gray-500">Harga Satuan (per Jamaah)</label>
                                            <div class="relative">
                                                <span
                                                    class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                                <input type="number" name="harga_satuan[{{ $jenis->id_jenis }}]"
                                                    class="jenis-harga w-full pl-8 pr-2 py-1 border border-gray-200 rounded text-sm"
                                                    placeholder="0" min="0" value="0">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Catatan</label>
                                            <input type="text" name="catatan[{{ $jenis->id_jenis }}]"
                                                class="jenis-catatan w-full px-2 py-1 border border-gray-200 rounded text-sm"
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
                        Centang semua jenis transaksi yang ingin ditambahkan sekaligus.
                        Harga akan dikalikan dengan jumlah jamaah ({{ $departure->jamaahs->count() }} orang)
                    </p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Default Harga Satuan</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                <input type="number" name="default_harga_satuan" id="default_harga_satuan"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Isi untuk semua" min="0" value="0">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Default Catatan</label>
                            <input type="text" name="default_catatan" id="default_catatan"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Catatan untuk semua">
                        </div>
                    </div>
                    <div class="flex items-center gap-3 mt-3">
                        <button type="button" onclick="fillAllHarga()"
                            class="text-sm text-blue-500 hover:text-blue-700">
                            <i class="fas fa-fill-drip mr-1"></i> Isi Semua Harga dengan Default
                        </button>
                        <button type="button" onclick="fillAllCatatan()"
                            class="text-sm text-blue-500 hover:text-blue-700">
                            <i class="fas fa-fill-drip mr-1"></i> Isi Semua Catatan dengan Default
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
                        <input type="number" name="harga_satuan" id="edit_harga_satuan" required min="0"
                            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
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
            const errorElement = document.getElementById('kamarError-' + index);
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

                        // Set selected value jika ada
                        const item = document.querySelector(`.hotel-tour-item[data-index="${index}"]`);
                        if (item) {
                            const existingTipe = item.querySelector('input[name$="[tipe_kamar]"]');
                            if (existingTipe && existingTipe.value) {
                                selectElement.value = existingTipe.value;
                            }
                        }
                    } else {
                        selectElement.innerHTML =
                            '<option value="">-- Tidak ada tipe kamar --</option>';
                        countElement.textContent = '0 tipe kamar';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    loadingElement.classList.add('hidden');
                    errorElement.classList.remove('hidden');
                    selectElement.innerHTML =
                        '<option value="">-- Gagal memuat data --</option>';
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

            // Jenis Transaksi Checkbox
            document.querySelectorAll('.jenis-transaksi-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const parent = this.closest('label');
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
        });

        // ============================================
        // MASKAPAI FUNCTIONS
        // ============================================

        function openMaskapaiModal() {
            document.getElementById('maskapaiModal').classList.remove('hidden');
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
                    `
                    <p class="text-sm text-gray-400 col-span-2 text-center py-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        Silakan pilih hotel terlebih dahulu untuk melihat tipe kamar
                    </p>
                `;
                return;
            }

            container.innerHTML =
                `
                <p class="text-sm text-gray-400 col-span-2 text-center py-4">
                    <i class="fas fa-spinner fa-spin mr-2"></i>
                    Memuat tipe kamar...
                </p>
            `;

            fetch(`/transaksional/get-kamars-by-hotel-with-selected/${hotelId}/${departureId}`)
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = data.html;
                    container.querySelectorAll('.kamar-checkbox, .kamar-jumlah, .kamar-harga, .kamar-durasi')
                        .forEach(function(el) {
                            el.addEventListener('change', function() {
                                calculateHotelTotal(containerId, totalId);
                            });
                            el.addEventListener('keyup', function() {
                                calculateHotelTotal(containerId, totalId);
                            });
                        });
                    calculateHotelTotal(containerId, totalId);
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.innerHTML =
                        `
                        <p class="text-sm text-red-500 col-span-2 text-center py-4">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            Gagal memuat tipe kamar. Silakan coba lagi.
                        </p>
                    `;
                });
        }

        function calculateHotelTotal(containerId, totalId) {
            const container = document.getElementById(containerId);
            let total = 0;
            container.querySelectorAll('.kamar-item').forEach(function(item) {
                const checkbox = item.querySelector('.kamar-checkbox');
                if (checkbox && checkbox.checked) {
                    const jumlah = parseInt(item.querySelector('.kamar-jumlah').value) || 0;
                    const harga = parseInt(item.querySelector('.kamar-harga').value) || 0;
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
        }

        function closeJamaahModal() {
            document.getElementById('jamaahModal').classList.add('hidden');
        }

        function removeJamaah(departureId, jamaahId) {
            if (confirm('Yakin ingin menghapus jamaah ini dari keberangkatan?')) {
                document.getElementById('remove-jamaah-form-' + departureId + '-' + jamaahId).submit();
            }
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
                `
                <div class="flex items-center justify-center py-12">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin text-3xl text-yellow-500 mb-3"></i>
                        <p class="text-gray-500">Memuat data...</p>
                    </div>
                </div>
            `;

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
                        `
                        <div class="text-center py-12">
                            <i class="fas fa-exclamation-circle text-3xl text-red-500 mb-3"></i>
                            <p class="text-gray-500">Gagal memuat data. Silakan coba lagi.</p>
                        </div>
                    `;
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
            document.getElementById('edit_harga_satuan').value = currentHarga;

            modal.classList.remove('hidden');
        }

        function closeEditJenisTransaksiModal() {
            document.getElementById('editJenisTransaksiModal').classList.add('hidden');
        }

        function fillAllHarga() {
            const defaultHarga = document.getElementById('default_harga_satuan').value;
            if (!defaultHarga || defaultHarga == '0') {
                alert('Silakan isi default harga satuan terlebih dahulu!');
                return;
            }
            document.querySelectorAll('.jenis-harga').forEach(function(input) {
                input.value = defaultHarga;
            });
        }

        function fillAllCatatan() {
            const defaultCatatan = document.getElementById('default_catatan').value;
            document.querySelectorAll('.jenis-catatan').forEach(function(input) {
                input.value = defaultCatatan;
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
    </script>
@endpush
