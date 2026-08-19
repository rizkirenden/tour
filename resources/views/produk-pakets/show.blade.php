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
                            </div>
                            <p class="text-gray-500 text-sm mt-1">
                                <i class="fas fa-tag mr-1"></i> Kode: {{ $produk->kode_produk ?? 'Tidak ada' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-yellow-600">Rp
                                {{ number_format($produk->harga_dasar, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-500">{{ $produk->durasi_hari }} Hari</p>
                        </div>
                    </div>
                </div>

                <!-- Grid Detail -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Informasi Dasar -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-yellow-500 mr-2"></i> Informasi Dasar
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Kode Produk</dt>
                                <dd class="font-medium text-gray-700">{{ $produk->kode_produk ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Kategori</dt>
                                <dd class="font-medium text-gray-700">{{ $produk->kategori ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Nama Produk</dt>
                                <dd class="font-medium text-gray-700">{{ $produk->nama_produk }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Durasi Hari</dt>
                                <dd class="font-medium text-gray-700">{{ $produk->durasi_hari }} Hari</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Harga -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-money-bill-wave text-yellow-500 mr-2"></i> Detail Harga
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Harga Dasar</dt>
                                <dd class="font-medium text-gray-700">Rp
                                    {{ number_format($produk->harga_dasar, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Harga Visa</dt>
                                <dd class="font-medium text-gray-700">Rp
                                    {{ number_format($produk->harga_visa ?? 0, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Harga Handling</dt>
                                <dd class="font-medium text-gray-700">Rp
                                    {{ number_format($produk->harga_handling ?? 0, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Harga Muthowwif</dt>
                                <dd class="font-medium text-gray-700">Rp
                                    {{ number_format($produk->harga_muthowwif ?? 0, 0, ',', '.') }}</dd>
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
                                <dt class="text-gray-500">Durasi Mekkah</dt>
                                <dd class="font-medium text-gray-700">{{ $produk->durasi_mekkah ?? 0 }} Hari</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Durasi Madinah</dt>
                                <dd class="font-medium text-gray-700">{{ $produk->durasi_madinah ?? 0 }} Hari</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Durasi Transit</dt>
                                <dd class="font-medium text-gray-700">{{ $produk->durasi_transit ?? 0 }} Hari</dd>
                            </div>
                            <div class="flex justify-between text-sm font-medium">
                                <dt class="text-gray-600">Total Durasi</dt>
                                <dd class="text-yellow-600">{{ $produk->durasi_hari }} Hari</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Hotel Default -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-hotel text-yellow-500 mr-2"></i> Hotel Default
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Hotel Mekkah</dt>
                                <dd class="font-medium text-gray-700">{{ $produk->hotel_mekkah_default ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Hotel Madinah</dt>
                                <dd class="font-medium text-gray-700">{{ $produk->hotel_madinah_default ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Hotel Transit</dt>
                                <dd class="font-medium text-gray-700">{{ $produk->hotel_transit_default ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Opsi Lainnya -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 lg:col-span-2">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-cogs text-yellow-500 mr-2"></i> Opsi Lainnya
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500 text-sm">Kapasitas Kamar:</span>
                                <span class="font-medium text-gray-700">{{ $produk->kapasitas_kamar_default ?? 3 }}
                                    Orang</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500 text-sm">Multiple Hotel:</span>
                                <span
                                    class="px-2 py-0.5 rounded-full text-xs font-medium {{ $produk->multiple_hotel_enabled ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $produk->multiple_hotel_enabled ? 'Ya' : 'Tidak' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500 text-sm">Include Tur:</span>
                                <span
                                    class="px-2 py-0.5 rounded-full text-xs font-medium {{ $produk->include_tur ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $produk->include_tur ? 'Ya' : 'Tidak' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500 text-sm">Status:</span>
                                <span
                                    class="px-2 py-0.5 rounded-full text-xs font-medium {{ $produk->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $produk->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>
                        </div>
                    </div>

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
