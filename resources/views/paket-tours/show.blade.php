@extends('layouts.app')

@section('title', 'Detail Paket Umroh Plus - Arrum Tour')
@section('page-title', 'Detail Paket Umroh Plus')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.paket-tour.index') }}" class="text-gray-500 hover:text-yellow-600">Master Data</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.paket-tour.index') }}" class="text-gray-500 hover:text-yellow-600">Paket Umroh Plus</a>
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
                    <h5 class="text-sm font-semibold text-gray-700">Detail Paket Umroh Plus</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Informasi lengkap paket umroh plus</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('master.paket-tour.edit', $paketTour->id_paket_tour) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('master.paket-tour.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="p-6">
                <!-- Header -->
                <div
                    class="bg-gradient-to-r from-yellow-50 to-yellow-100/50 rounded-xl p-6 mb-6 border border-yellow-200/50">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h2 class="text-2xl font-bold text-gray-800">{{ $paketTour->kota_tujuan ?? 'Umroh Plus' }}
                                </h2>
                                <span class="px-3 py-1 bg-green-500 text-white text-xs font-medium rounded-full">
                                    Paket Umroh Plus
                                </span>
                            </div>
                            <p class="text-gray-500 text-sm mt-1">
                                <i class="fas fa-map-marker-alt mr-1"></i> {{ $paketTour->negara ?? '-' }}
                            </p>
                            <p class="text-gray-500 text-sm">
                                <i class="fas fa-clock mr-1"></i> {{ $paketTour->durasi_hari ?? '-' }} Hari
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-yellow-600">{{ $paketTour->total_harga_hotel_formatted }}</p>
                            <p class="text-sm text-gray-500">Total Harga Hotel</p>
                        </div>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-yellow-500 mr-2"></i> Informasi Umroh Plus
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Kota Tujuan</dt>
                                <dd class="font-medium text-gray-700">{{ $paketTour->kota_tujuan ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Negara</dt>
                                <dd class="font-medium text-gray-700">{{ $paketTour->negara ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Durasi</dt>
                                <dd class="font-medium text-gray-700">{{ $paketTour->durasi_hari ?? '-' }} Hari</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-dollar-sign text-yellow-500 mr-2"></i> Total Harga Hotel
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Total Harga Hotel</dt>
                                <dd class="font-medium text-gray-700">{{ $paketTour->total_harga_hotel_formatted }}</dd>
                            </div>
                            <div class="flex justify-between text-sm border-t border-gray-200 pt-2 mt-2">
                                <dt class="text-gray-600 font-medium">Jumlah Hotel</dt>
                                <dd class="font-bold text-yellow-600">{{ $paketTour->hotels->count() }} Hotel</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Daftar Hotel -->
                <div class="mt-6 bg-gray-50 rounded-xl p-5 border border-gray-100">
                    <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-hotel text-yellow-500 mr-2"></i> Daftar Hotel
                        <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                            {{ $paketTour->hotels->count() }} Hotel
                        </span>
                    </h6>

                    @if ($paketTour->hotels->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Nama Hotel</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Kota</th>
                                        <th
                                            class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Bintang</th>
                                        <th
                                            class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Harga/Malam</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($paketTour->hotels as $index => $hotel)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-3 py-2 text-gray-600">{{ $index + 1 }}</td>
                                            <td class="px-3 py-2 font-medium text-gray-700">{{ $hotel->nama_hotel }}</td>
                                            <td class="px-3 py-2 text-gray-600">{{ $hotel->kota ?? '-' }}</td>
                                            <td class="px-3 py-2 text-center">
                                                @if ($hotel->bintang)
                                                    <span class="text-yellow-500">
                                                        @for ($i = 1; $i <= $hotel->bintang; $i++)
                                                            <i class="fas fa-star text-xs"></i>
                                                        @endfor
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 text-xs">-</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-right font-medium text-gray-700">
                                                {{ $hotel->harga_per_malam_formatted }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-100">
                                    <tr>
                                        <td colspan="4" class="px-3 py-2 text-right font-semibold text-gray-700">Total
                                        </td>
                                        <td class="px-3 py-2 text-right font-bold text-yellow-600">
                                            {{ $paketTour->total_harga_hotel_formatted }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-hotel text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm">Tidak ada hotel yang terdaftar untuk paket umroh plus ini</p>
                        </div>
                    @endif
                </div>

                <!-- Deskripsi -->
                @if ($paketTour->deskripsi)
                    <div class="mt-6 bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-align-left text-yellow-500 mr-2"></i> Deskripsi
                        </h6>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $paketTour->deskripsi }}</p>
                    </div>
                @endif

                <!-- Info Sistem -->
                <div class="mt-6 bg-gray-50 rounded-xl p-5 border border-gray-100">
                    <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-clock text-yellow-500 mr-2"></i> Informasi Sistem
                    </h6>
                    <dl class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <dt class="text-gray-500">Dibuat pada</dt>
                            <dd class="font-medium text-gray-700">{{ $paketTour->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div class="flex justify-between text-sm">
                            <dt class="text-gray-500">Terakhir diupdate</dt>
                            <dd class="font-medium text-gray-700">{{ $paketTour->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-end gap-3">
                    <button type="button" onclick="window.print()"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        <i class="fas fa-print mr-2"></i> Cetak
                    </button>
                    <a href="{{ route('master.paket-tour.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <a href="{{ route('master.paket-tour.edit', $paketTour->id_paket_tour) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit Paket Umroh Plus
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
