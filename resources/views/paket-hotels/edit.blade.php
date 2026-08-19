@extends('layouts.app')

@section('title', 'Edit Paket Hotel - Arrum Tour')
@section('page-title', 'Edit Paket Hotel')

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
        <span class="text-gray-500 font-medium">Edit</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Form Edit Paket Hotel</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Edit informasi paket hotel</p>
                </div>
                <a href="{{ route('master.paket-hotel.show', $paketHotel->id_paket_hotel) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <i class="fas fa-eye mr-2"></i> Lihat Detail
                </a>
            </div>

            <form action="{{ route('master.paket-hotel.update', $paketHotel->id_paket_hotel) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Produk <span class="text-red-500">*</span>
                            </label>
                            <select name="id_produk"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                required>
                                <option value="">Pilih Produk</option>
                                @foreach ($produkOptions as $produk)
                                    <option value="{{ $produk->id_produk }}"
                                        {{ old('id_produk', $paketHotel->id_produk) == $produk->id_produk ? 'selected' : '' }}>
                                        {{ $produk->kode_produk }} - {{ $produk->nama_produk }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_produk')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Hotel <span class="text-red-500">*</span>
                            </label>
                            <select name="id_hotel"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                required>
                                <option value="">Pilih Hotel</option>
                                @foreach ($hotelOptions as $hotel)
                                    <option value="{{ $hotel->id_hotel }}"
                                        {{ old('id_hotel', $paketHotel->id_hotel) == $hotel->id_hotel ? 'selected' : '' }}>
                                        {{ $hotel->kode_hotel }} - {{ $hotel->nama_hotel }} ({{ $hotel->kota ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_hotel')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Urutan</label>
                            <input type="number" name="urutan" value="{{ old('urutan', $paketHotel->urutan) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="1" min="1">
                            @error('urutan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Penginapan</label>
                            <input type="text" name="tipe_penginapan"
                                value="{{ old('tipe_penginapan', $paketHotel->tipe_penginapan) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: Full Board, Half Board">
                            @error('tipe_penginapan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Per Orang</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                <input type="number" name="harga_per_orang"
                                    value="{{ old('harga_per_orang', $paketHotel->harga_per_orang) }}"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="0">
                            </div>
                            @error('harga_per_orang')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center gap-3 text-sm font-medium text-gray-700">
                            <input type="checkbox" name="adalah_default" value="1"
                                {{ old('adalah_default', $paketHotel->adalah_default) ? 'checked' : '' }}
                                class="w-4 h-4 text-yellow-500 focus:ring-yellow-500 rounded">
                            Jadikan Hotel Default
                            <span class="text-xs text-gray-400 font-normal">(Hanya satu hotel per produk)</span>
                        </label>
                        @error('adalah_default')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('master.paket-hotel.index') }}"
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
