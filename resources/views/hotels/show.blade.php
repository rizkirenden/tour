@extends('layouts.app')

@section('title', 'Detail Hotel - Arrum Tour')
@section('page-title', 'Detail Hotel')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.hotel.index') }}" class="text-gray-500 hover:text-yellow-600">Master Data</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.hotel.index') }}" class="text-gray-500 hover:text-yellow-600">Hotel</a>
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
                    <h5 class="text-sm font-semibold text-gray-700">Detail Hotel</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Informasi lengkap hotel dan tipe kamar</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('master.hotel.edit', $hotel->id_hotel) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('master.hotel.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="p-6">
                <!-- Header Hotel -->
                <div
                    class="bg-gradient-to-r from-yellow-50 to-yellow-100/50 rounded-xl p-6 mb-6 border border-yellow-200/50">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h2 class="text-2xl font-bold text-gray-800">{{ $hotel->nama_hotel }}</h2>
                            </div>
                            <p class="text-gray-500 text-sm mt-1">
                                <i class="fas fa-map-marker-alt mr-1"></i> {{ $hotel->kota }}, {{ $hotel->negara }}
                            </p>
                            <p class="text-gray-500 text-sm">
                                <i class="fas fa-star mr-1"></i> {{ $hotel->bintang_text }}
                            </p>
                            @if ($hotel->tipe_hotel)
                                <p class="text-gray-500 text-sm">
                                    <i class="fas fa-building mr-1"></i> {{ $hotel->tipe_hotel }}
                                </p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <span
                                class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                <i class="fas fa-door-open mr-1"></i>
                                {{ $hotel->kamars->count() }} Tipe Kamar
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Grid Informasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informasi Hotel -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-yellow-500 mr-2"></i> Informasi Hotel
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Nama Hotel</dt>
                                <dd class="font-medium text-gray-700">{{ $hotel->nama_hotel }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Lokasi</dt>
                                <dd class="font-medium text-gray-700">{{ $hotel->lokasi ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Tipe Hotel</dt>
                                <dd class="font-medium text-gray-700">{{ $hotel->tipe_hotel ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Bintang</dt>
                                <dd class="font-medium text-gray-700">{{ $hotel->bintang_text }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Total Tipe Kamar</dt>
                                <dd class="font-medium text-gray-700">{{ $hotel->kamars->count() }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Lokasi & Sistem -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-map-pin text-yellow-500 mr-2"></i> Lokasi & Sistem
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Negara</dt>
                                <dd class="font-medium text-gray-700">{{ $hotel->negara ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Kota</dt>
                                <dd class="font-medium text-gray-700">{{ $hotel->kota ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Dibuat pada</dt>
                                <dd class="font-medium text-gray-700">{{ $hotel->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Terakhir diupdate</dt>
                                <dd class="font-medium text-gray-700">{{ $hotel->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Fasilitas Hotel -->
                    @if ($hotel->fasilitas)
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 md:col-span-2">
                            <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-wifi text-yellow-500 mr-2"></i> Fasilitas Hotel
                            </h6>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $fasilitas = explode(',', $hotel->fasilitas);
                                @endphp
                                @foreach ($fasilitas as $item)
                                    <span
                                        class="inline-flex px-3 py-1 bg-white border border-gray-200 rounded-full text-xs text-gray-700">
                                        <i class="fas fa-check-circle text-yellow-500 mr-1.5 text-xs"></i>
                                        {{ trim($item) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Daftar Tipe Kamar -->
                    @if ($hotel->kamars->isNotEmpty())
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 md:col-span-2">
                            <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-door-open text-yellow-500 mr-2"></i>
                                Daftar Tipe Kamar
                                <span class="ml-2 text-xs text-gray-400 font-normal">({{ $hotel->kamars->count() }}
                                    tipe)</span>
                            </h6>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">No
                                            </th>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">
                                                Tipe Kamar</th>
                                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">
                                                Kapasitas</th>
                                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">
                                                Jumlah</th>
                                            <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">
                                                Harga/Malam</th>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">
                                                Fasilitas</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($hotel->kamars as $index => $kamar)
                                            <tr class="hover:bg-white transition-colors">
                                                <td class="px-4 py-2 text-gray-500 text-xs">{{ $index + 1 }}</td>
                                                <td class="px-4 py-2 font-medium text-gray-700">{{ $kamar->tipe_kamar }}
                                                </td>
                                                <td class="px-4 py-2 text-center">
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs">
                                                        {{ $kamar->kapasitas }} orang
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2 text-center">
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs">
                                                        {{ $kamar->jumlah_kamar }} kamar
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2 text-right font-medium text-gray-700">
                                                    @if ($kamar->harga_per_malam)
                                                        Rp {{ number_format($kamar->harga_per_malam, 0, ',', '.') }}
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2 text-sm text-gray-600">
                                                    @if ($kamar->fasilitas_kamar)
                                                        <div class="flex flex-wrap gap-1">
                                                            @php
                                                                $fasilitasKamar = explode(',', $kamar->fasilitas_kamar);
                                                            @endphp
                                                            @foreach ($fasilitasKamar as $item)
                                                                <span
                                                                    class="inline-flex px-2 py-0.5 bg-gray-200 text-gray-600 rounded text-xs">
                                                                    {{ trim($item) }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Tombol Aksi -->
                <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-end gap-3">
                    <button type="button" onclick="window.print()"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        <i class="fas fa-print mr-2"></i> Cetak
                    </button>
                    <a href="{{ route('master.hotel.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <a href="{{ route('master.hotel.edit', $hotel->id_hotel) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit Hotel
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
