@extends('layouts.app')

@section('title', 'Edit Produk - Arrum Tour')
@section('page-title', 'Edit Produk Paket')

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
        <span class="text-gray-500 font-medium">Edit</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Form Edit Produk</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Edit informasi produk paket</p>
                </div>
                <a href="{{ route('master.produk.show', $produk->id_produk) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <i class="fas fa-eye mr-2"></i> Lihat Detail
                </a>
            </div>

            <form action="{{ route('master.produk.update', $produk->id_produk) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <!-- Informasi Dasar -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kode Produk <span class="text-gray-400 text-xs">(opsional)</span>
                            </label>
                            <input type="text" name="kode_produk" value="{{ old('kode_produk', $produk->kode_produk) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Otomatis jika kosong">
                            @error('kode_produk')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kategori <span class="text-gray-400 text-xs">(opsional)</span>
                            </label>
                            <input type="text" name="kategori" value="{{ old('kategori', $produk->kategori) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: Executive, Premium, dll">
                            @error('kategori')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama Produk <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                            placeholder="Contoh: Umroh Executive 12 Hari" required>
                        @error('nama_produk')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                        <textarea name="deskripsi" rows="3"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                            placeholder="Deskripsikan paket ini...">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga & Durasi -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Harga Dasar <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                <input type="number" name="harga_dasar"
                                    value="{{ old('harga_dasar', $produk->harga_dasar) }}"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="0" required>
                            </div>
                            @error('harga_dasar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Visa</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                <input type="number" name="harga_visa"
                                    value="{{ old('harga_visa', $produk->harga_visa) }}"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="0">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Durasi Hari <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="durasi_hari" value="{{ old('durasi_hari', $produk->durasi_hari) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: 12" required>
                            @error('durasi_hari')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Detail Durasi -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Durasi Mekkah</label>
                            <input type="number" name="durasi_mekkah"
                                value="{{ old('durasi_mekkah', $produk->durasi_mekkah) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Durasi Madinah</label>
                            <input type="number" name="durasi_madinah"
                                value="{{ old('durasi_madinah', $produk->durasi_madinah) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Durasi Transit</label>
                            <input type="number" name="durasi_transit"
                                value="{{ old('durasi_transit', $produk->durasi_transit) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                        </div>
                    </div>

                    <!-- Hotel Default -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Hotel Mekkah Default</label>
                            <input type="text" name="hotel_mekkah_default"
                                value="{{ old('hotel_mekkah_default', $produk->hotel_mekkah_default) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Nama hotel">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Hotel Madinah Default</label>
                            <input type="text" name="hotel_madinah_default"
                                value="{{ old('hotel_madinah_default', $produk->hotel_madinah_default) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Nama hotel">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Hotel Transit Default</label>
                            <input type="text" name="hotel_transit_default"
                                value="{{ old('hotel_transit_default', $produk->hotel_transit_default) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Nama hotel">
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Kapasitas Kamar</label>
                            <select name="kapasitas_kamar_default"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                <option value="3"
                                    {{ old('kapasitas_kamar_default', $produk->kapasitas_kamar_default) == 3 ? 'selected' : '' }}>
                                    3 Orang</option>
                                <option value="4"
                                    {{ old('kapasitas_kamar_default', $produk->kapasitas_kamar_default) == 4 ? 'selected' : '' }}>
                                    4 Orang</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Multiple Hotel</label>
                            <select name="multiple_hotel_enabled"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                <option value="0"
                                    {{ old('multiple_hotel_enabled', $produk->multiple_hotel_enabled) == 0 ? 'selected' : '' }}>
                                    Tidak</option>
                                <option value="1"
                                    {{ old('multiple_hotel_enabled', $produk->multiple_hotel_enabled) == 1 ? 'selected' : '' }}>
                                    Ya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Include Tur</label>
                            <select name="include_tur"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                <option value="0"
                                    {{ old('include_tur', $produk->include_tur) == 0 ? 'selected' : '' }}>Tidak</option>
                                <option value="1"
                                    {{ old('include_tur', $produk->include_tur) == 1 ? 'selected' : '' }}>Ya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                            <select name="is_active"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                <option value="1" {{ old('is_active', $produk->is_active) == 1 ? 'selected' : '' }}>
                                    Aktif</option>
                                <option value="0" {{ old('is_active', $produk->is_active) == 0 ? 'selected' : '' }}>
                                    Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Muthowwif</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                            <input type="number" name="harga_muthowwif"
                                value="{{ old('harga_muthowwif', $produk->harga_muthowwif) }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="0">
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('master.produk.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors text-sm font-medium">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 transition-colors text-sm font-medium shadow-sm hover:shadow">
                            <i class="fas fa-save mr-2"></i> Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
