@extends('layouts.app')

@section('title', 'Detail Produk - Arrum Tour')
@section('page-title', 'Detail Produk Paket')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.produk.index') }}" class="text-gray-500 hover:text-yellow-600">Master Data</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.produk.index') }}" class="text-gray-500 hover:text-yellow-600">Produk Paket</a>
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
                    <h5 class="text-sm font-semibold text-gray-700">Detail Produk</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Informasi lengkap produk paket</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('master.produk.edit', $produk->id_produk) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('master.produk.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="p-6">
                <!-- Header Produk -->
                <div
                    class="bg-gradient-to-r from-yellow-50 to-yellow-100/50 rounded-xl p-6 mb-6 border border-yellow-200/50">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h2 class="text-2xl font-bold text-gray-800">{{ $produk->nama_produk }}</h2>
                                <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-medium rounded-full">
                                    {{ $produk->kategori ?? 'Umum' }}
                                </span>
                                {!! $produk->status_badge !!}
                            </div>
                            @if ($produk->include_tur)
                                <p class="text-xs text-blue-600 mt-1">
                                    <i class="fas fa-route mr-1"></i> Include Tur:
                                    {{ $produk->paketTour->kota_tujuan ?? '-' }}
                                </p>
                            @endif
                        </div>
                        <div class="text-right">
                            <!-- Tampilkan Total Harga -->
                            <p class="text-3xl font-bold text-yellow-600">{{ $produk->total_harga_formatted }}</p>
                            <p class="text-xs text-gray-400">Total Harga</p>
                            <div class="text-xs text-gray-500 mt-1">
                                <span class="block">Harga Dasar: {{ $produk->harga_dasar_formatted }}</span>
                                @if ($produk->include_tur)
                                    <span class="block">Harga Tour: {{ $produk->harga_tour_formatted }}</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500">{{ $produk->durasi_hari }} Hari</p>
                        </div>
                    </div>
                </div>

                <!-- Grid Informasi -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Informasi Dasar -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-yellow-500 mr-2"></i> Informasi Dasar
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Kategori</dt>
                                <dd class="font-medium text-gray-700">{{ $produk->kategori ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Nama Produk</dt>
                                <dd class="font-medium text-gray-700">{{ $produk->nama_produk }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Include Tur</dt>
                                <dd class="font-medium text-gray-700">{!! $produk->include_tur_badge !!}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Harga Dasar</dt>
                                <dd class="font-medium text-yellow-600">{{ $produk->harga_dasar_formatted }}</dd>
                            </div>
                            <div class="flex justify-between text-sm font-semibold border-t border-gray-200 pt-2 mt-2">
                                <dt class="text-gray-700">Total Harga</dt>
                                <dd class="font-bold text-yellow-600">{{ $produk->total_harga_formatted }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Durasi Detail -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-clock text-yellow-500 mr-2"></i> Detail Durasi
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Durasi Perjalanan</dt>
                                <dd class="font-medium text-gray-700">{{ $produk->durasi_perjalanan_formatted }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Durasi Mekkah</dt>
                                <dd class="font-medium text-gray-700">{{ $produk->durasi_mekkah ?? 0 }} Hari</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Durasi Madinah</dt>
                                <dd class="font-medium text-gray-700">{{ $produk->durasi_madinah ?? 0 }} Hari</dd>
                            </div>
                            <div class="flex justify-between text-sm font-medium border-t border-gray-200 pt-2 mt-2">
                                <dt class="text-gray-600">Total Durasi</dt>
                                <dd class="text-yellow-600">{{ $produk->durasi_hari }} Hari</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Detail Harga -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 lg:col-span-2">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-calculator text-yellow-500 mr-2"></i> Detail Harga
                        </h6>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500">Harga Dasar</p>
                                    <p class="text-lg font-bold text-yellow-600">{{ $produk->harga_dasar_formatted }}</p>
                                </div>
                                <div class="text-center p-3 bg-blue-50 rounded-lg">
                                    <p class="text-xs text-gray-500">Harga Tour</p>
                                    <p class="text-lg font-bold text-blue-600">{{ $produk->harga_tour_formatted }}</p>
                                </div>
                                <div class="text-center p-3 bg-green-50 rounded-lg border-2 border-green-200">
                                    <p class="text-xs text-gray-500">Total Harga</p>
                                    <p class="text-lg font-bold text-green-600">{{ $produk->total_harga_formatted }}</p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 text-center mt-3">
                                <i class="fas fa-info-circle mr-1"></i>
                                Total harga = Harga Dasar + Harga Tour (Otomatis dihitung saat menyimpan)
                            </p>
                        </div>
                    </div>

                    <!-- Paket Tour -->
                    @if ($produk->include_tur && $produk->paketTour)
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 lg:col-span-2">
                            <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-route text-yellow-500 mr-2"></i> Paket Tour
                            </h6>
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 text-sm">
                                    <div>
                                        <span class="text-gray-500">Kota Tujuan:</span>
                                        <span
                                            class="font-medium text-gray-700 block">{{ $produk->paketTour->kota_tujuan ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Negara:</span>
                                        <span
                                            class="font-medium text-gray-700 block">{{ $produk->paketTour->negara ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Durasi:</span>
                                        <span
                                            class="font-medium text-gray-700 block">{{ $produk->paketTour->durasi_hari ?? '-' }}
                                            Hari</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Harga Tour:</span>
                                        <span
                                            class="font-medium text-blue-600 block">{{ $produk->harga_tour_formatted }}</span>
                                    </div>
                                </div>
                                @if ($produk->paketTour->deskripsi)
                                    <div class="mt-2 text-sm text-gray-600 border-t border-gray-100 pt-2">
                                        <span class="text-gray-500">Deskripsi:</span>
                                        {{ $produk->paketTour->deskripsi }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Hotel dari Paket Tour -->
                    @if ($produk->include_tur && $produk->paketTour && $produk->paketTour->hotels->count() > 0)
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 lg:col-span-2">
                            <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-hotel text-yellow-500 mr-2"></i> Daftar Hotel dalam Paket Tour
                                <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                                    {{ $produk->paketTour->hotels->count() }} Hotel
                                </span>
                            </h6>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">No</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">Nama Hotel
                                            </th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">Kota</th>
                                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-500">Bintang
                                            </th>
                                            <th class="px-3 py-2 text-right text-xs font-semibold text-gray-500">
                                                Harga/Malam</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($produk->paketTour->hotels as $index => $hotel)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-3 py-2 text-gray-600">{{ $index + 1 }}</td>
                                                <td class="px-3 py-2 font-medium text-gray-700">{{ $hotel->nama_hotel }}
                                                </td>
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
                                            <td colspan="4" class="px-3 py-2 text-right font-semibold text-gray-700">
                                                Total Hotel</td>
                                            <td class="px-3 py-2 text-right font-bold text-yellow-600">
                                                {{ $produk->paketTour->total_harga_hotel_formatted }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Flyer -->
                    @if ($produk->flyer_url)
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 lg:col-span-2">
                            <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-image text-yellow-500 mr-2"></i> Flyer Produk
                            </h6>
                            <div class="flex justify-center">
                                <img src="{{ $produk->flyer_url }}" alt="Flyer {{ $produk->nama_produk }}"
                                    class="max-w-full max-h-96 rounded-lg shadow-md border border-gray-200">
                            </div>
                            <div class="mt-3 text-center flex gap-2 justify-center">
                                <a href="{{ $produk->flyer_url }}" target="_blank"
                                    class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium">
                                    <i class="fas fa-external-link-alt mr-2"></i> Lihat Full Size
                                </a>
                                <a href="{{ $produk->flyer_url }}" download
                                    class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm font-medium">
                                    <i class="fas fa-download mr-2"></i> Download
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Deskripsi -->
                    @if ($produk->deskripsi)
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 lg:col-span-2">
                            <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-align-left text-yellow-500 mr-2"></i> Deskripsi
                            </h6>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $produk->deskripsi }}</p>
                        </div>
                    @endif
                </div>

                <!-- Aksi Bawah -->
                <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-end gap-3">
                    <button type="button" onclick="window.print()"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        <i class="fas fa-print mr-2"></i> Cetak
                    </button>
                    <a href="{{ route('master.produk.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <a href="{{ route('master.produk.edit', $produk->id_produk) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit Produk
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
