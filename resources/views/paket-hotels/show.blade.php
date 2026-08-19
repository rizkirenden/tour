@extends('layouts.app')

@section('title', 'Detail Paket Hotel - Arrum Tour')
@section('page-title', 'Detail Paket Hotel')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.paket-hotel.index') }}" class="text-gray-500 hover:text-yellow-600">Master Data</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.paket-hotel.index') }}" class="text-gray-500 hover:text-yellow-600">Paket Hotel</a>
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
                    <h5 class="text-sm font-semibold text-gray-700">Detail Paket Hotel</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Informasi lengkap paket hotel</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('master.paket-hotel.edit', $paketHotel->id_paket_hotel) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('master.paket-hotel.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div
                    class="bg-gradient-to-r from-yellow-50 to-yellow-100/50 rounded-xl p-6 mb-6 border border-yellow-200/50">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h2 class="text-2xl font-bold text-gray-800">{{ $paketHotel->produk->nama_produk ?? '-' }}
                                </h2>
                                {!! $paketHotel->default_badge !!}
                            </div>
                            <p class="text-gray-500 text-sm mt-1">
                                <i class="fas fa-hotel mr-1"></i> {{ $paketHotel->hotel->nama_hotel ?? '-' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-yellow-600">{{ $paketHotel->harga_per_orang_formatted }}</p>
                            <p class="text-sm text-gray-500">per orang</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-yellow-500 mr-2"></i> Informasi Paket Hotel
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">ID Paket Hotel</dt>
                                <dd class="font-medium text-gray-700">#{{ $paketHotel->id_paket_hotel }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Produk</dt>
                                <dd class="font-medium text-gray-700">{{ $paketHotel->produk->nama_produk ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Hotel</dt>
                                <dd class="font-medium text-gray-700">{{ $paketHotel->hotel->nama_hotel ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Kode Hotel</dt>
                                <dd class="font-medium text-gray-700">{{ $paketHotel->hotel->kode_hotel ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Urutan</dt>
                                <dd class="font-medium text-gray-700">{{ $paketHotel->urutan ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-dollar-sign text-yellow-500 mr-2"></i> Detail Harga & Status
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Tipe Penginapan</dt>
                                <dd class="font-medium text-gray-700">{{ $paketHotel->tipe_penginapan ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Harga Per Orang</dt>
                                <dd class="font-medium text-gray-700">{{ $paketHotel->harga_per_orang_formatted }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Status Default</dt>
                                <dd>
                                    <span
                                        class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $paketHotel->adalah_default ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        <i
                                            class="fas {{ $paketHotel->adalah_default ? 'fa-check-circle' : 'fa-circle' }} mr-1.5 text-xs"></i>
                                        {{ $paketHotel->adalah_default ? 'Ya' : 'Tidak' }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 md:col-span-2">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-clock text-yellow-500 mr-2"></i> Informasi Sistem
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Dibuat pada</dt>
                                <dd class="font-medium text-gray-700">{{ $paketHotel->created_at->format('d/m/Y H:i') }}
                                </dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Terakhir diupdate</dt>
                                <dd class="font-medium text-gray-700">{{ $paketHotel->updated_at->format('d/m/Y H:i') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-end gap-3">
                    <button type="button" onclick="window.print()"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        <i class="fas fa-print mr-2"></i> Cetak
                    </button>
                    <a href="{{ route('master.paket-hotel.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <a href="{{ route('master.paket-hotel.edit', $paketHotel->id_paket_hotel) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit Paket Hotel
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
