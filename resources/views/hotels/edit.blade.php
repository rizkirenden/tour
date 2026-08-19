@extends('layouts.app')

@section('title', 'Edit Hotel - Arrum Tour')
@section('page-title', 'Edit Hotel')

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
        <span class="text-gray-500 font-medium">Edit</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Form Edit Hotel</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Edit informasi hotel</p>
                </div>
                <a href="{{ route('master.hotel.show', $hotel->id_hotel) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <i class="fas fa-eye mr-2"></i> Lihat Detail
                </a>
            </div>

            <form action="{{ route('master.hotel.update', $hotel->id_hotel) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kode Hotel <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kode_hotel" value="{{ old('kode_hotel', $hotel->kode_hotel) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm uppercase"
                                placeholder="Contoh: HOT-001" required>
                            @error('kode_hotel')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Hotel <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_hotel" value="{{ old('nama_hotel', $hotel->nama_hotel) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: Grand Makkah Hotel" required>
                            @error('nama_hotel')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Lokasi</label>
                            <input type="text" name="lokasi" value="{{ old('lokasi', $hotel->lokasi) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: Jarak 500m dari Masjidil Haram">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Hotel</label>
                            <input type="text" name="tipe_hotel" value="{{ old('tipe_hotel', $hotel->tipe_hotel) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: Luxury, Premium, Business">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Bintang</label>
                            <select name="bintang"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                <option value="">Pilih Bintang</option>
                                <option value="1" {{ old('bintang', $hotel->bintang) == 1 ? 'selected' : '' }}>⭐
                                </option>
                                <option value="2" {{ old('bintang', $hotel->bintang) == 2 ? 'selected' : '' }}>⭐⭐
                                </option>
                                <option value="3" {{ old('bintang', $hotel->bintang) == 3 ? 'selected' : '' }}>⭐⭐⭐
                                </option>
                                <option value="4" {{ old('bintang', $hotel->bintang) == 4 ? 'selected' : '' }}>⭐⭐⭐⭐
                                </option>
                                <option value="5" {{ old('bintang', $hotel->bintang) == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Kamar</label>
                            <input type="text" name="tipe_kamar" value="{{ old('tipe_kamar', $hotel->tipe_kamar) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: Deluxe Suite">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Kapasitas</label>
                            <input type="number" name="kapasitas" value="{{ old('kapasitas', $hotel->kapasitas) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: 2">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Per Malam</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                            <input type="number" name="harga_per_malam"
                                value="{{ old('harga_per_malam', $hotel->harga_per_malam) }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="0">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Negara</label>
                            <input type="text" name="negara" value="{{ old('negara', $hotel->negara) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: Arab Saudi">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Kota</label>
                            <input type="text" name="kota" value="{{ old('kota', $hotel->kota) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: Makkah">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Fasilitas</label>
                        <textarea name="fasilitas" rows="3"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                            placeholder="Contoh: WiFi, AC, TV, Kulkas, Kolam Renang">{{ old('fasilitas', $hotel->fasilitas) }}</textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('master.hotel.index') }}"
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
