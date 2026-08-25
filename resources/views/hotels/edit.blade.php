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
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-sm font-semibold text-gray-700">Form Edit Hotel</h5>
                        <p class="text-xs text-gray-400 mt-0.5">Edit data hotel dan tipe kamar</p>
                    </div>
                    <a href="{{ route('master.hotel.show', $hotel->id_hotel) }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-eye mr-2"></i> Lihat Detail
                    </a>
                </div>
            </div>

            <form action="{{ route('master.hotel.update', $hotel->id_hotel) }}" method="POST" id="hotelForm">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <!-- Data Hotel -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Lokasi</label>
                            <input type="text" name="lokasi" value="{{ old('lokasi', $hotel->lokasi) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: Jarak 500m dari Masjidil Haram">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Hotel</label>
                            <input type="text" name="tipe_hotel" value="{{ old('tipe_hotel', $hotel->tipe_hotel) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: Luxury, Premium, Business">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Bintang</label>
                            <select name="bintang"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                <option value="">Pilih Bintang</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}"
                                        {{ old('bintang', $hotel->bintang) == $i ? 'selected' : '' }}>
                                        {{ str_repeat('⭐', $i) }}
                                    </option>
                                @endfor
                            </select>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kota <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kota" value="{{ old('kota', $hotel->kota) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: Mekkah, Madinah, atau Transit" required>
                            @error('kota')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <div class="mt-1.5 flex flex-wrap gap-2">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Mekkah</span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Madinah</span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Transit</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Fasilitas Hotel</label>
                        <textarea name="fasilitas" rows="2"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                            placeholder="Pisahkan dengan koma (contoh: WiFi, AC, Restoran, Kolam Renang)">{{ old('fasilitas', $hotel->fasilitas) }}</textarea>
                    </div>

                    <!-- Multiple Kamar Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h6 class="text-sm font-semibold text-gray-700">
                                    <i class="fas fa-door-open text-yellow-500 mr-2"></i> Tipe Kamar & Kapasitas
                                </h6>
                                <p class="text-xs text-gray-400 mt-0.5">Tambahkan atau edit tipe kamar untuk hotel ini</p>
                            </div>
                            <button type="button" onclick="addKamar()"
                                class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium">
                                <i class="fas fa-plus mr-1.5"></i> Tambah Kamar
                            </button>
                        </div>

                        @error('kamars')
                            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-lg text-sm mb-3">
                                {{ $message }}
                            </div>
                        @enderror

                        <div id="kamar-container">
                            @forelse($hotel->kamars as $index => $kamar)
                                <div class="kamar-item bg-gray-50 rounded-xl p-4 mb-3 border border-gray-200">
                                    <input type="hidden" name="kamars[{{ $index }}][id_kamar]"
                                        value="{{ $kamar->id_kamar }}">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                Tipe Kamar <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="kamars[{{ $index }}][tipe_kamar]"
                                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                                value="{{ old("kamars.$index.tipe_kamar", $kamar->tipe_kamar) }}" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                Kapasitas <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" name="kamars[{{ $index }}][kapasitas]"
                                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                                value="{{ old("kamars.$index.kapasitas", $kamar->kapasitas) }}"
                                                min="1" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Fasilitas
                                                Kamar</label>
                                            <input type="text" name="kamars[{{ $index }}][fasilitas_kamar]"
                                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                                value="{{ old("kamars.$index.fasilitas_kamar", $kamar->fasilitas_kamar) }}"
                                                placeholder="AC, TV, WiFi, Bathub">
                                        </div>
                                    </div>
                                    <button type="button" onclick="removeKamar(this)"
                                        class="mt-2 text-red-500 hover:text-red-700 text-sm font-medium">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                </div>
                            @empty
                                <div class="kamar-item bg-gray-50 rounded-xl p-4 mb-3 border border-gray-200">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                Tipe Kamar <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="kamars[0][tipe_kamar]"
                                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                                placeholder="Deluxe Suite" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                Kapasitas <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" name="kamars[0][kapasitas]"
                                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                                placeholder="2" min="1" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Fasilitas
                                                Kamar</label>
                                            <input type="text" name="kamars[0][fasilitas_kamar]"
                                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                                placeholder="AC, TV, WiFi, Bathub">
                                        </div>
                                    </div>
                                    <button type="button" onclick="removeKamar(this)"
                                        class="mt-2 text-red-500 hover:text-red-700 text-sm font-medium">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                </div>
                            @endforelse
                        </div>
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

@push('scripts')
    <script>
        let kamarIndex = {{ $hotel->kamars->count() > 0 ? $hotel->kamars->count() : 1 }};

        function addKamar() {
            const container = document.getElementById('kamar-container');
            const newItem = document.createElement('div');
            newItem.className = 'kamar-item bg-gray-50 rounded-xl p-4 mb-3 border border-gray-200';
            newItem.style.animation = 'fadeIn 0.3s ease';
            newItem.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            Tipe Kamar <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kamars[${kamarIndex}][tipe_kamar]"
                            class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                            placeholder="Deluxe Suite" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            Kapasitas <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="kamars[${kamarIndex}][kapasitas]"
                            class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                            placeholder="2" min="1" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Fasilitas Kamar</label>
                        <input type="text" name="kamars[${kamarIndex}][fasilitas_kamar]"
                            class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                            placeholder="AC, TV, WiFi, Bathub">
                    </div>
                </div>
                <button type="button" onclick="removeKamar(this)"
                    class="mt-2 text-red-500 hover:text-red-700 text-sm font-medium">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
            `;
            container.appendChild(newItem);
            kamarIndex++;
        }

        function removeKamar(button) {
            const items = document.querySelectorAll('.kamar-item');
            if (items.length > 1) {
                const item = button.closest('.kamar-item');
                item.style.animation = 'fadeOut 0.3s ease';
                setTimeout(() => {
                    item.remove();
                }, 300);
            } else {
                alert('Minimal harus ada 1 tipe kamar!');
            }
        }

        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes fadeOut {
                from { opacity: 1; transform: translateY(0); }
                to { opacity: 0; transform: translateY(-10px); }
            }
        `;
        document.head.appendChild(style);

        document.getElementById('hotelForm').addEventListener('submit', function(e) {
            const kamarItems = document.querySelectorAll('.kamar-item');
            if (kamarItems.length === 0) {
                e.preventDefault();
                alert('Minimal harus ada 1 tipe kamar!');
                return false;
            }
            return true;
        });
    </script>
@endpush
