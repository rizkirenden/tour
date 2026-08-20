@extends('layouts.app')

@section('title', 'Edit Paket Tour - Arrum Tour')
@section('page-title', 'Edit Paket Tour')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.paket-tour.index') }}" class="text-gray-500 hover:text-yellow-600">Master Data</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.paket-tour.index') }}" class="text-gray-500 hover:text-yellow-600">Paket Tour</a>
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
                <h5 class="text-sm font-semibold text-gray-700">Form Edit Paket Tour</h5>
                <p class="text-xs text-gray-400 mt-0.5">Edit data paket tour</p>
            </div>

            <form action="{{ route('master.paket-tour.update', $paketTour->id_paket_tour) }}" method="POST"
                id="paketTourForm">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <!-- Informasi Tour -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Kota Tujuan</label>
                            <input type="text" name="kota_tujuan"
                                value="{{ old('kota_tujuan', $paketTour->kota_tujuan) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: Mekkah, Madinah">
                            @error('kota_tujuan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Negara</label>
                            <input type="text" name="negara" value="{{ old('negara', $paketTour->negara) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: Arab Saudi">
                            @error('negara')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Durasi Hari</label>
                            <input type="number" name="durasi_hari"
                                value="{{ old('durasi_hari', $paketTour->durasi_hari) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="12" min="1">
                            @error('durasi_hari')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                        <textarea name="deskripsi" rows="3"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                            placeholder="Deskripsikan paket tour ini...">{{ old('deskripsi', $paketTour->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga Per Orang -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Per Orang</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                            <input type="number" name="harga_per_orang"
                                value="{{ old('harga_per_orang', $paketTour->harga_per_orang) }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="0">
                        </div>
                        @error('harga_per_orang')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hotel Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h6 class="text-sm font-semibold text-gray-700">Daftar Hotel</h6>
                                <p class="text-xs text-gray-400">Tambahkan hotel yang digunakan dalam tour ini</p>
                            </div>
                            <button type="button" onclick="addHotelRow()"
                                class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm font-medium">
                                <i class="fas fa-plus mr-2"></i> Tambah Hotel
                            </button>
                        </div>

                        <div id="hotelContainer">
                            @php
                                $hotelData =
                                    old('hotels') ??
                                    $paketTour->hotels
                                        ->map(function ($hotel) {
                                            return [
                                                'id_hotel' => $hotel->id_hotel,
                                                'durasi_menginap' => $hotel->pivot->durasi_menginap ?? 1,
                                                'harga_hotel' => $hotel->pivot->harga_hotel ?? 0,
                                                'urutan' => $hotel->pivot->urutan ?? 0,
                                                'catatan' => $hotel->pivot->catatan ?? '',
                                            ];
                                        })
                                        ->toArray();
                            @endphp

                            @foreach ($hotelData as $index => $hotel)
                                <div class="hotel-row bg-gray-50 rounded-xl p-4 mb-3 border border-gray-200">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Pilih Hotel</label>
                                            <select name="hotels[{{ $index }}][id_hotel]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                                <option value="">Pilih Hotel</option>
                                                @foreach ($hotels as $h)
                                                    <option value="{{ $h->id_hotel }}"
                                                        {{ ($hotel['id_hotel'] ?? '') == $h->id_hotel ? 'selected' : '' }}>
                                                        {{ $h->nama_hotel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Durasi Menginap
                                                (Malam)</label>
                                            <input type="number" name="hotels[{{ $index }}][durasi_menginap]"
                                                value="{{ $hotel['durasi_menginap'] ?? 1 }}"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                min="1" placeholder="1">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Harga Hotel</label>
                                            <input type="number" name="hotels[{{ $index }}][harga_hotel]"
                                                value="{{ $hotel['harga_hotel'] ?? 0 }}"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                placeholder="0">
                                        </div>
                                        <div class="flex items-end">
                                            <input type="hidden" name="hotels[{{ $index }}][urutan]"
                                                value="{{ $index + 1 }}">
                                            <button type="button" onclick="removeHotelRow(this)"
                                                class="w-full px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium">
                                                <i class="fas fa-trash mr-1"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Catatan</label>
                                        <input type="text" name="hotels[{{ $index }}][catatan]"
                                            value="{{ $hotel['catatan'] ?? '' }}"
                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                            placeholder="Catatan khusus untuk hotel ini (opsional)">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('master.paket-tour.index') }}"
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

    @push('scripts')
        <script>
            let hotelIndex = {{ count($hotelData) }};

            function addHotelRow() {
                const container = document.getElementById('hotelContainer');
                const row = document.createElement('div');
                row.className = 'hotel-row bg-gray-50 rounded-xl p-4 mb-3 border border-gray-200';
                row.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Pilih Hotel</label>
                        <select name="hotels[${hotelIndex}][id_hotel]"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                            <option value="">Pilih Hotel</option>
                            @foreach ($hotels as $h)
                                <option value="{{ $h->id_hotel }}">{{ $h->nama_hotel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Durasi Menginap (Malam)</label>
                        <input type="number" name="hotels[${hotelIndex}][durasi_menginap]"
                            value="1"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                            min="1" placeholder="1">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Harga Hotel</label>
                        <input type="number" name="hotels[${hotelIndex}][harga_hotel]"
                            value="0"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                            placeholder="0">
                    </div>
                    <div class="flex items-end">
                        <input type="hidden" name="hotels[${hotelIndex}][urutan]" value="${hotelIndex + 1}">
                        <button type="button" onclick="removeHotelRow(this)"
                            class="w-full px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium">
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                    </div>
                </div>
                <div class="mt-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Catatan</label>
                    <input type="text" name="hotels[${hotelIndex}][catatan]"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                        placeholder="Catatan khusus untuk hotel ini (opsional)">
                </div>
            `;
                container.appendChild(row);
                hotelIndex++;
            }

            function removeHotelRow(button) {
                const row = button.closest('.hotel-row');
                const container = document.getElementById('hotelContainer');
                if (container.children.length > 1) {
                    row.remove();
                    // Reindex urutan
                    const rows = container.querySelectorAll('.hotel-row');
                    rows.forEach((r, idx) => {
                        const urutanInput = r.querySelector('input[name*="[urutan]"]');
                        if (urutanInput) {
                            urutanInput.value = idx + 1;
                        }
                    });
                } else {
                    alert('Minimal harus ada 1 hotel!');
                }
            }
        </script>
    @endpush
@endsection
