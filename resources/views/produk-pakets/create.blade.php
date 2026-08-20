@extends('layouts.app')

@section('title', 'Tambah Produk - Arrum Tour')
@section('page-title', 'Tambah Produk Paket')

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
        <span class="text-gray-500 font-medium">Tambah</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h5 class="text-sm font-semibold text-gray-700">Form Tambah Produk</h5>
                <p class="text-xs text-gray-400 mt-0.5">Lengkapi data produk paket dengan benar</p>
            </div>

            <form action="{{ route('master.produk.store') }}" method="POST" id="produkForm">
                @csrf

                <div class="p-6 space-y-6">
                    <!-- ============================================ -->
                    <!-- 1. INFORMASI DASAR PRODUK -->
                    <!-- ============================================ -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-yellow-500 mr-2"></i> Informasi Dasar
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kode Produk <span class="text-gray-400 text-xs">(opsional)</span>
                                </label>
                                <input type="text" name="kode_produk" value="{{ old('kode_produk') }}"
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
                                <input type="text" name="kategori" value="{{ old('kategori') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Contoh: Executive, Premium, dll">
                                @error('kategori')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Produk <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_produk" value="{{ old('nama_produk') }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: Umroh Executive 12 Hari" required>
                            @error('nama_produk')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                            <textarea name="deskripsi" rows="3"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Deskripsikan paket ini...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- 2. HARGA PRODUK -->
                    <!-- ============================================ -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-money-bill-wave text-yellow-500 mr-2"></i> Detail Harga
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Harga Dasar <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                    <input type="number" name="harga_dasar" value="{{ old('harga_dasar') }}"
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
                                    <input type="number" name="harga_visa" value="{{ old('harga_visa', 0) }}"
                                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                        placeholder="0">
                                </div>
                                @error('harga_visa')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Handling</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                    <input type="number" name="harga_handling" value="{{ old('harga_handling', 0) }}"
                                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                        placeholder="0">
                                </div>
                                @error('harga_handling')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Muthowwif</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                    <input type="number" name="harga_muthowwif" value="{{ old('harga_muthowwif', 0) }}"
                                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                        placeholder="0">
                                </div>
                                @error('harga_muthowwif')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- 3. DURASI -->
                    <!-- ============================================ -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-clock text-yellow-500 mr-2"></i> Detail Durasi
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Durasi Hari <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="durasi_hari" value="{{ old('durasi_hari') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Total" required>
                                @error('durasi_hari')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Durasi Mekkah</label>
                                <input type="number" name="durasi_mekkah" value="{{ old('durasi_mekkah', 4) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    min="0">
                                @error('durasi_mekkah')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Durasi Madinah</label>
                                <input type="number" name="durasi_madinah" value="{{ old('durasi_madinah', 4) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    min="0">
                                @error('durasi_madinah')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Durasi Transit</label>
                                <input type="number" name="durasi_transit" value="{{ old('durasi_transit', 1) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    min="0">
                                @error('durasi_transit')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- 4. HOTEL DEFAULT -->
                    <!-- ============================================ -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-hotel text-yellow-500 mr-2"></i> Hotel Default
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Hotel Mekkah</label>
                                <select name="hotel_mekkah_default"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                    <option value="">-- Pilih Hotel --</option>
                                    @foreach ($hotels->where('kota', 'Mekkah') as $hotel)
                                        <option value="{{ $hotel->id_hotel }}"
                                            {{ old('hotel_mekkah_default') == $hotel->id_hotel ? 'selected' : '' }}>
                                            {{ $hotel->nama_hotel }} - {{ $hotel->bintang }}★
                                        </option>
                                    @endforeach
                                </select>
                                @error('hotel_mekkah_default')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Hotel Madinah</label>
                                <select name="hotel_madinah_default"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                    <option value="">-- Pilih Hotel --</option>
                                    @foreach ($hotels->where('kota', 'Madinah') as $hotel)
                                        <option value="{{ $hotel->id_hotel }}"
                                            {{ old('hotel_madinah_default') == $hotel->id_hotel ? 'selected' : '' }}>
                                            {{ $hotel->nama_hotel }} - {{ $hotel->bintang }}★
                                        </option>
                                    @endforeach
                                </select>
                                @error('hotel_madinah_default')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Hotel Transit</label>
                                <select name="hotel_transit_default"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                    <option value="">-- Pilih Hotel --</option>
                                    @foreach ($hotels->where('kota', 'Transit') as $hotel)
                                        <option value="{{ $hotel->id_hotel }}"
                                            {{ old('hotel_transit_default') == $hotel->id_hotel ? 'selected' : '' }}>
                                            {{ $hotel->nama_hotel }} - {{ $hotel->bintang }}★
                                        </option>
                                    @endforeach
                                </select>
                                @error('hotel_transit_default')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- 5. PERLENGKAPAN INCLUDE -->
                    <!-- ============================================ -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-box text-yellow-500 mr-2"></i> Perlengkapan Include
                            <span class="ml-2 text-xs text-gray-400">(Pilih perlengkapan yang termasuk dalam paket)</span>
                        </h6>

                        <div class="space-y-3">
                            <div id="perlengkapanContainer">
                                @php
                                    $oldPerlengkapans = old('perlengkapans', []);
                                    $perlengkapanCount = count($oldPerlengkapans) > 0 ? count($oldPerlengkapans) : 1;
                                @endphp

                                @if (count($oldPerlengkapans) > 0)
                                    @foreach ($oldPerlengkapans as $index => $item)
                                        <div
                                            class="perlengkapan-row bg-gray-50 rounded-lg p-3 border border-gray-200 mb-3">
                                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Pilih
                                                        Perlengkapan</label>
                                                    <select name="perlengkapans[{{ $index }}][id_perlengkapan]"
                                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                                        <option value="">-- Pilih Perlengkapan --</option>
                                                        @foreach ($perlengkapans as $p)
                                                            <option value="{{ $p->id_perlengkapan }}"
                                                                {{ ($item['id_perlengkapan'] ?? '') == $p->id_perlengkapan ? 'selected' : '' }}>
                                                                {{ $p->nama_perlengkapan }} - {{ $p->kategori }} -
                                                                {{ $p->harga_satuan_formatted }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error("perlengkapans.{$index}.id_perlengkapan")
                                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-xs font-medium text-gray-600 mb-1">Kuantitas</label>
                                                    <input type="number"
                                                        name="perlengkapans[{{ $index }}][kuantitas]"
                                                        value="{{ $item['kuantitas'] ?? 1 }}"
                                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                        min="1" placeholder="1">
                                                    @error("perlengkapans.{$index}.kuantitas")
                                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-xs font-medium text-gray-600 mb-1">Catatan</label>
                                                    <input type="text"
                                                        name="perlengkapans[{{ $index }}][catatan]"
                                                        value="{{ $item['catatan'] ?? '' }}"
                                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                        placeholder="Catatan (opsional)">
                                                    @error("perlengkapans.{$index}.catatan")
                                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="flex items-end">
                                                    <button type="button" onclick="removePerlengkapanRow(this)"
                                                        class="w-full px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium">
                                                        <i class="fas fa-trash mr-1"></i> Hapus
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <!-- Default 1 row -->
                                    <div class="perlengkapan-row bg-gray-50 rounded-lg p-3 border border-gray-200">
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Pilih
                                                    Perlengkapan</label>
                                                <select name="perlengkapans[0][id_perlengkapan]"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                                    <option value="">-- Pilih Perlengkapan --</option>
                                                    @foreach ($perlengkapans as $p)
                                                        <option value="{{ $p->id_perlengkapan }}">
                                                            {{ $p->nama_perlengkapan }} - {{ $p->kategori }} -
                                                            {{ $p->harga_satuan_formatted }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-600 mb-1">Kuantitas</label>
                                                <input type="number" name="perlengkapans[0][kuantitas]" value="1"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                    min="1" placeholder="1">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Catatan</label>
                                                <input type="text" name="perlengkapans[0][catatan]"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                    placeholder="Catatan (opsional)">
                                            </div>
                                            <div class="flex items-end">
                                                <button type="button" onclick="removePerlengkapanRow(this)"
                                                    class="w-full px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium">
                                                    <i class="fas fa-trash mr-1"></i> Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <button type="button" onclick="addPerlengkapanRow()"
                                class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm font-medium">
                                <i class="fas fa-plus mr-2"></i> Tambah Perlengkapan
                            </button>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- 6. OPSI LAINNYA -->
                    <!-- ============================================ -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-cogs text-yellow-500 mr-2"></i> Opsi Lainnya
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Include Tur</label>
                                <select name="include_tur" id="include_tur"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                    <option value="0" {{ old('include_tur', 0) == 0 ? 'selected' : '' }}>Tidak
                                    </option>
                                    <option value="1" {{ old('include_tur', 0) == 1 ? 'selected' : '' }}>Ya</option>
                                </select>
                                @error('include_tur')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                                <select name="is_active"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                    <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('is_active', 1) == 0 ? 'selected' : '' }}>Tidak Aktif
                                    </option>
                                </select>
                                @error('is_active')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Keberangkatan</label>
                                <select name="status_keberangkatan_id"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                    <option value="">-- Pilih Status --</option>
                                    @foreach ($statusKeberangkatans as $status)
                                        <option value="{{ $status->id_status }}"
                                            {{ old('status_keberangkatan_id') == $status->id_status ? 'selected' : '' }}>
                                            {{ $status->nama_status }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status_keberangkatan_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- 7. PILIH PAKET TOUR -->
                    <!-- ============================================ -->
                    <div id="paketTourSection" class="border-b border-gray-200 pb-4" style="display: none;">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-route text-yellow-500 mr-2"></i> Pilih Paket Tour
                            <span class="ml-2 text-xs text-gray-400">(Pilih tour yang sudah tersedia)</span>
                        </h6>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Paket Tour</label>
                            <select name="paket_tour_id" id="paket_tour_id"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                <option value="">-- Pilih Paket Tour --</option>
                                @foreach ($paketTours as $tour)
                                    <option value="{{ $tour->id_paket_tour }}"
                                        {{ old('paket_tour_id') == $tour->id_paket_tour ? 'selected' : '' }}>
                                        {{ $tour->kota_tujuan ?? 'Tour' }} - {{ $tour->negara ?? '' }}
                                        ({{ $tour->durasi_hari ?? 0 }} Hari)
                                        - {{ $tour->harga_per_orang_formatted }}
                                    </option>
                                @endforeach
                            </select>
                            @error('paket_tour_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-400 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Pilih paket tour yang sudah tersedia di menu Paket Tour
                            </p>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- BUTTONS -->
                    <!-- ============================================ -->
                    <div class="flex items-center justify-end gap-3 pt-4">
                        <a href="{{ route('master.produk.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors text-sm font-medium">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 transition-colors text-sm font-medium shadow-sm hover:shadow">
                            <i class="fas fa-save mr-2"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const includeTurSelect = document.getElementById('include_tur');
            const paketTourSection = document.getElementById('paketTourSection');
            const paketTourSelect = document.getElementById('paket_tour_id');

            function togglePaketTour() {
                if (includeTurSelect.value === '1') {
                    paketTourSection.style.display = 'block';
                } else {
                    paketTourSection.style.display = 'none';
                    if (paketTourSelect) {
                        paketTourSelect.value = '';
                    }
                }
            }

            if (includeTurSelect) {
                includeTurSelect.addEventListener('change', togglePaketTour);
                togglePaketTour();
            }
        });

        let perlengkapanIndex = {{ count(old('perlengkapans', [0])) }};

        function addPerlengkapanRow() {
            const container = document.getElementById('perlengkapanContainer');
            const row = document.createElement('div');
            row.className = 'perlengkapan-row bg-gray-50 rounded-lg p-3 border border-gray-200 mb-3';
            row.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Pilih Perlengkapan</label>
                    <select name="perlengkapans[${perlengkapanIndex}][id_perlengkapan]"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                        <option value="">-- Pilih Perlengkapan --</option>
                        @foreach ($perlengkapans as $p)
                            <option value="{{ $p->id_perlengkapan }}">
                                {{ $p->nama_perlengkapan }} - {{ $p->kategori }} - {{ $p->harga_satuan_formatted }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Kuantitas</label>
                    <input type="number" name="perlengkapans[${perlengkapanIndex}][kuantitas]"
                        value="1"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                        min="1" placeholder="1">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Catatan</label>
                    <input type="text" name="perlengkapans[${perlengkapanIndex}][catatan]"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                        placeholder="Catatan (opsional)">
                </div>
                <div class="flex items-end">
                    <button type="button" onclick="removePerlengkapanRow(this)"
                        class="w-full px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>
            </div>
        `;
            container.appendChild(row);
            perlengkapanIndex++;
        }

        function removePerlengkapanRow(button) {
            const row = button.closest('.perlengkapan-row');
            const container = document.getElementById('perlengkapanContainer');
            if (container.children.length > 1) {
                row.remove();
            } else {
                alert('Minimal harus ada 1 perlengkapan!');
            }
        }
    </script>
@endpush
