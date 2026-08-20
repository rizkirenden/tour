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
                                <input type="text" name="kode_produk"
                                    value="{{ old('kode_produk', $produk->kode_produk) }}"
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
                        <div class="mt-4">
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
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                            <textarea name="deskripsi" rows="3"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Deskripsikan paket ini...">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
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
                                @error('harga_visa')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Handling</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                    <input type="number" name="harga_handling"
                                        value="{{ old('harga_handling', $produk->harga_handling) }}"
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
                                    <input type="number" name="harga_muthowwif"
                                        value="{{ old('harga_muthowwif', $produk->harga_muthowwif) }}"
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
                                <input type="number" name="durasi_hari"
                                    value="{{ old('durasi_hari', $produk->durasi_hari) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Total" required>
                                @error('durasi_hari')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Durasi Mekkah</label>
                                <input type="number" name="durasi_mekkah"
                                    value="{{ old('durasi_mekkah', $produk->durasi_mekkah) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                @error('durasi_mekkah')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Durasi Madinah</label>
                                <input type="number" name="durasi_madinah"
                                    value="{{ old('durasi_madinah', $produk->durasi_madinah) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                @error('durasi_madinah')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Durasi Transit</label>
                                <input type="number" name="durasi_transit"
                                    value="{{ old('durasi_transit', $produk->durasi_transit) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
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
                                            {{ old('hotel_mekkah_default', $produk->hotel_mekkah_default) == $hotel->id_hotel ? 'selected' : '' }}>
                                            {{ $hotel->nama_hotel }} - {{ $hotel->bintang }}★ ({{ $hotel->tipe_hotel }})
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
                                            {{ old('hotel_madinah_default', $produk->hotel_madinah_default) == $hotel->id_hotel ? 'selected' : '' }}>
                                            {{ $hotel->nama_hotel }} - {{ $hotel->bintang }}★ ({{ $hotel->tipe_hotel }})
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
                                            {{ old('hotel_transit_default', $produk->hotel_transit_default) == $hotel->id_hotel ? 'selected' : '' }}>
                                            {{ $hotel->nama_hotel }} - {{ $hotel->bintang }}★ ({{ $hotel->tipe_hotel }})
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
                    <!-- 5. OPSI LAINNYA -->
                    <!-- ============================================ -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-cogs text-yellow-500 mr-2"></i> Opsi Lainnya
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Include Tur</label>
                                <select name="include_tur" id="include_tur"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                    <option value="0"
                                        {{ old('include_tur', $produk->include_tur) == 0 ? 'selected' : '' }}>Tidak
                                    </option>
                                    <option value="1"
                                        {{ old('include_tur', $produk->include_tur) == 1 ? 'selected' : '' }}>Ya</option>
                                </select>
                                @error('include_tur')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Produk</label>
                                <select name="is_active"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                    <option value="1"
                                        {{ old('is_active', $produk->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="0"
                                        {{ old('is_active', $produk->is_active) == 0 ? 'selected' : '' }}>Tidak Aktif
                                    </option>
                                </select>
                                @error('is_active')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- 6. STATUS KEBERANGKATAN -->
                    <!-- ============================================ -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-plane-departure text-yellow-500 mr-2"></i> Status Keberangkatan
                        </h6>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Keberangkatan</label>
                            <select name="status_keberangkatan_id"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                <option value="">-- Pilih Status Keberangkatan --</option>
                                @foreach ($statusKeberangkatans as $status)
                                    <option value="{{ $status->id_status }}"
                                        {{ old('status_keberangkatan_id', $produk->status_keberangkatan_id) == $status->id_status ? 'selected' : '' }}>
                                        {{ $status->nama_status }}
                                        @if ($status->warna)
                                            ({{ $status->warna }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('status_keberangkatan_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-400 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Pilih status keberangkatan untuk produk ini
                            </p>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- 7. PILIH PAKET TOUR (Muncul Jika Include Tur = Ya) -->
                    <!-- ============================================ -->
                    <div id="paketTourSection" class="border-b border-gray-200 pb-4"
                        style="display: {{ $produk->include_tur ? 'block' : 'none' }};">
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
                                        {{ old('paket_tour_id', $produk->paket_tour_id) == $tour->id_paket_tour ? 'selected' : '' }}>
                                        {{ $tour->kota_tujuan ?? 'Tour' }} - {{ $tour->negara ?? '' }}
                                        ({{ $tour->durasi_hari ?? 0 }} Hari)
                                        - Rp {{ number_format($tour->harga_per_orang ?? 0, 0, ',', '.') }}
                                        @if ($tour->produk)
                                            ({{ $tour->produk->nama_produk }})
                                        @endif
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

                    <!-- Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4">
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

            // Event listener untuk include_tur
            if (includeTurSelect) {
                includeTurSelect.addEventListener('change', togglePaketTour);
            }
        });
    </script>
@endpush
