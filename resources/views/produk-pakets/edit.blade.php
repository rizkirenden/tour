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

            <form action="{{ route('master.produk.update', $produk->id_produk) }}" method="POST"
                enctype="multipart/form-data" id="produkForm">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <!-- Informasi Dasar -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-yellow-500 mr-2"></i> Informasi Dasar
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Produk <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_produk"
                                    value="{{ old('nama_produk', $produk->nama_produk) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Contoh: Umroh Executive 12 Hari" required>
                                @error('nama_produk')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kategori <span class="text-gray-400 text-xs">(opsional)</span>
                                </label>
                                <input type="text" name="kategori" value="{{ old('kategori', $produk->kategori) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Contoh: Executive, Premium, Ekonomi">
                                @error('kategori')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
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

                    <!-- Harga Bulanan (WAJIB) -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-calendar-alt text-yellow-500 mr-2"></i> Harga Per Bulan <span
                                class="text-red-500">*</span>
                            <span class="ml-2 text-xs text-gray-400">(minimal 1 data)</span>
                            <button type="button" onclick="addHargaBulanan()"
                                class="ml-auto text-xs bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600 transition">
                                <i class="fas fa-plus mr-1"></i> Tambah
                            </button>
                        </h6>

                        <div id="hargaBulananList">
                            @php $editIndex = 0; @endphp
                            @foreach ($produk->hargaBulanan as $harga)
                                <div
                                    class="harga-row grid grid-cols-1 md:grid-cols-4 gap-3 mb-2 items-end bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    <input type="hidden" name="harga_bulanan[{{ $editIndex }}][id]"
                                        value="{{ $harga->id }}">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Bulan <span
                                                class="text-red-500">*</span></label>
                                        <select name="harga_bulanan[{{ $editIndex }}][bulan]"
                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                            required>
                                            <option value="">Pilih Bulan</option>
                                            @for ($b = 1; $b <= 12; $b++)
                                                <option value="{{ $b }}"
                                                    {{ $harga->bulan == $b ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Tahun <span
                                                class="text-red-500">*</span></label>
                                        <select name="harga_bulanan[{{ $editIndex }}][tahun]"
                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                            required>
                                            <option value="">Pilih Tahun</option>
                                            @for ($y = date('Y'); $y <= date('Y') + 5; $y++)
                                                <option value="{{ $y }}"
                                                    {{ $harga->tahun == $y ? 'selected' : '' }}>
                                                    {{ $y }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Harga (Rp) <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" name="harga_bulanan[{{ $editIndex }}][harga]"
                                            value="{{ number_format($harga->harga, 0, ',', '.') }}"
                                            oninput="formatRupiah(this)"
                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                            placeholder="0" required>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <label class="flex items-center gap-1 text-xs">
                                            <input type="checkbox" name="harga_bulanan[{{ $editIndex }}][is_active]"
                                                value="1" {{ $harga->is_active ? 'checked' : '' }}>
                                            Aktif
                                        </label>
                                        <button type="button" onclick="removeHargaRow(this)"
                                            class="text-red-500 hover:text-red-700 text-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                @php $editIndex++; @endphp
                            @endforeach
                        </div>

                        <p class="text-xs text-gray-400 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            <span class="text-red-500">* Wajib</span> - Minimal 1 data harga per bulan
                        </p>
                        @error('harga_bulanan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Durasi -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-clock text-yellow-500 mr-2"></i> Detail Durasi
                        </h6>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Durasi Perjalanan
                            </label>
                            <div class="relative">
                                <input type="number" name="durasi_perjalanan" id="durasi_perjalanan"
                                    value="{{ old('durasi_perjalanan', $produk->durasi_perjalanan) }}"
                                    oninput="calculateTotalDurasi()"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Contoh: 2" min="0">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Hari</span>
                            </div>
                            @error('durasi_perjalanan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Durasi Mekkah</label>
                                <div class="relative">
                                    <input type="number" name="durasi_mekkah" id="durasi_mekkah"
                                        value="{{ old('durasi_mekkah', $produk->durasi_mekkah ?? 4) }}"
                                        oninput="calculateTotalDurasi()"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                        min="0">
                                    <span
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Hari</span>
                                </div>
                                @error('durasi_mekkah')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Durasi Madinah</label>
                                <div class="relative">
                                    <input type="number" name="durasi_madinah" id="durasi_madinah"
                                        value="{{ old('durasi_madinah', $produk->durasi_madinah ?? 4) }}"
                                        oninput="calculateTotalDurasi()"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                        min="0">
                                    <span
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Hari</span>
                                </div>
                                @error('durasi_madinah')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Durasi Umroh Plus (Auto dari Paket) -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Durasi Umroh Plus <span class="text-gray-400 text-xs">(otomatis dari paket)</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="durasi_tour" id="durasi_tour"
                                    value="{{ old('durasi_tour', $produk->durasi_tour ?? 0) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl bg-gray-100 cursor-not-allowed text-sm"
                                    readonly disabled>
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Hari</span>
                            </div>
                            <p class="text-xs text-blue-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Durasi umroh plus akan otomatis terisi saat memilih paket
                            </p>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Total Durasi Hari <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="durasi_hari" id="durasi_hari"
                                    value="{{ old('durasi_hari', $produk->durasi_hari) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl bg-gray-100 cursor-not-allowed text-sm"
                                    readonly disabled>
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Hari</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Total durasi = Perjalanan + Mekkah + Madinah + Umroh Plus
                            </p>
                        </div>
                    </div>

                    <!-- Opsi Lainnya -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-cogs text-yellow-500 mr-2"></i> Opsi Lainnya
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Include Umroh Plus</label>
                                <select name="include_tur" id="include_tur"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    onchange="togglePaketTour()">
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
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
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

                    <!-- Pilih Umroh Plus -->
                    <div id="paketTourSection" class="border-b border-gray-200 pb-4"
                        style="display: {{ $produk->include_tur ? 'block' : 'none' }};">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-route text-yellow-500 mr-2"></i> Pilih Umroh Plus
                            <span class="ml-2 text-xs text-gray-400">(Pilih paket umroh plus yang sudah tersedia)</span>
                        </h6>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Umroh Plus</label>
                            <select name="paket_tour_id" id="paket_tour_id"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                onchange="loadPaketTourInfo(this.value)">
                                <option value="">-- Pilih Umroh Plus --</option>
                                @foreach ($paketTours as $tour)
                                    <option value="{{ $tour->id_paket_tour }}"
                                        {{ old('paket_tour_id', $produk->paket_tour_id) == $tour->id_paket_tour ? 'selected' : '' }}>
                                        {{ $tour->kota_tujuan ?? 'Umroh Plus' }} - {{ $tour->negara ?? '' }}
                                        ({{ $tour->durasi_hari ?? 0 }} Hari)
                                    </option>
                                @endforeach
                            </select>
                            @error('paket_tour_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Informasi Umroh Plus yang dipilih -->
                        <div id="paketTourInfo"
                            class="mt-3 bg-blue-50 rounded-lg p-3 border border-blue-200
                            {{ $produk->paket_tour_id ? '' : 'hidden' }}">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-sm">
                                <div>
                                    <span class="text-gray-500">Kota:</span>
                                    <span id="info_kota" class="font-medium text-gray-700">
                                        {{ $produk->paketTour->kota_tujuan ?? '-' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Negara:</span>
                                    <span id="info_negara" class="font-medium text-gray-700">
                                        {{ $produk->paketTour->negara ?? '-' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Durasi Umroh Plus:</span>
                                    <span id="info_durasi" class="font-medium text-blue-600">
                                        {{ ($produk->paketTour->durasi_hari ?? 0) . ' Hari' }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2 text-xs text-gray-500">
                                <span class="text-gray-500">Total Harga Hotel:</span>
                                <span id="info_total_hotel" class="font-medium text-yellow-600">
                                    {{ $produk->paketTour->total_harga_hotel_formatted ?? 'Rp 0' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Flyer -->
                    <div>
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-image text-yellow-500 mr-2"></i> Upload Flyer
                            <span class="ml-2 text-xs text-gray-400">(opsional)</span>
                        </h6>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                File Flyer
                            </label>

                            @if ($produk->flyer_url)
                                <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="text-sm text-gray-600 mb-2">Flyer saat ini:</p>
                                    <img src="{{ $produk->flyer_url }}" alt="Flyer {{ $produk->nama_produk }}"
                                        class="max-h-48 rounded-lg border border-gray-200">
                                    <p class="text-xs text-gray-400 mt-1">{{ basename($produk->flyer) }}</p>
                                </div>
                            @endif

                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-yellow-400 transition-colors cursor-pointer"
                                id="dropzone">
                                <div class="space-y-1 text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="flyer"
                                            class="relative cursor-pointer rounded-md font-medium text-yellow-600 hover:text-yellow-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-yellow-500">
                                            <span>Upload file baru</span>
                                            <input id="flyer" name="flyer" type="file" class="sr-only"
                                                accept="image/*" onchange="previewImage(event)">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG, GIF, SVG up to 2MB</p>
                                    <div id="filePreview" class="hidden mt-3">
                                        <img id="imagePreview" src="#" alt="Preview Flyer"
                                            class="max-h-48 rounded-lg mx-auto border border-gray-200">
                                        <p id="fileName" class="text-sm text-gray-600 mt-2"></p>
                                        <button type="button" onclick="removeFile()"
                                            class="mt-2 text-sm text-red-500 hover:text-red-700">
                                            <i class="fas fa-times mr-1"></i> Hapus file
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('flyer')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-400 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Upload file baru akan menggantikan flyer yang lama
                            </p>
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

@push('scripts')
    <script>
        let hargaIndex = {{ $produk->hargaBulanan->count() }};

        // Format Rupiah
        function formatRupiah(element) {
            let value = element.value.replace(/[^,\d]/g, '');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            element.value = value;
        }

        // Toggle Umroh Plus Section
        function togglePaketTour() {
            const includeTurSelect = document.getElementById('include_tur');
            const paketTourSection = document.getElementById('paketTourSection');
            const paketTourSelect = document.getElementById('paket_tour_id');

            if (includeTurSelect.value === '1') {
                paketTourSection.style.display = 'block';
                if (paketTourSelect.value) {
                    loadPaketTourInfo(paketTourSelect.value);
                }
            } else {
                paketTourSection.style.display = 'none';
                if (paketTourSelect) {
                    paketTourSelect.value = '';
                }
                document.getElementById('paketTourInfo').classList.add('hidden');
                document.getElementById('durasi_tour').value = 0;
                calculateTotalDurasi();
            }
        }

        // Load Umroh Plus Info via AJAX
        function loadPaketTourInfo(tourId) {
            const infoDiv = document.getElementById('paketTourInfo');
            const durasiTourInput = document.getElementById('durasi_tour');

            if (!tourId) {
                infoDiv.classList.add('hidden');
                durasiTourInput.value = 0;
                calculateTotalDurasi();
                return;
            }

            infoDiv.classList.remove('hidden');
            document.getElementById('info_kota').textContent = 'Memuat...';
            document.getElementById('info_negara').textContent = 'Memuat...';
            document.getElementById('info_durasi').textContent = 'Memuat...';
            document.getElementById('info_total_hotel').textContent = 'Memuat...';

            fetch(`/master/get-paket-tour-info/${tourId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('info_kota').textContent = data.kota_tujuan || '-';
                    document.getElementById('info_negara').textContent = data.negara || '-';
                    document.getElementById('info_durasi').textContent = data.durasi_hari + ' Hari';
                    document.getElementById('info_total_hotel').textContent = data.total_harga_hotel || 'Rp 0';

                    durasiTourInput.value = data.durasi_hari || 0;
                    calculateTotalDurasi();
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('info_kota').textContent = 'Error';
                    document.getElementById('info_negara').textContent = 'Error';
                    document.getElementById('info_durasi').textContent = 'Error';
                    document.getElementById('info_total_hotel').textContent = 'Error';
                });
        }

        // Calculate Total Durasi
        function calculateTotalDurasi() {
            const durasiPerjalanan = parseFloat(document.getElementById('durasi_perjalanan').value) || 0;
            const durasiMekkah = parseFloat(document.getElementById('durasi_mekkah').value) || 0;
            const durasiMadinah = parseFloat(document.getElementById('durasi_madinah').value) || 0;
            const durasiTour = parseFloat(document.getElementById('durasi_tour').value) || 0;

            const total = durasiPerjalanan + durasiMekkah + durasiMadinah + durasiTour;
            document.getElementById('durasi_hari').value = Math.round(total);
        }

        // Preview Image
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('filePreview');
                    const imagePreview = document.getElementById('imagePreview');
                    const fileName = document.getElementById('fileName');

                    preview.classList.remove('hidden');
                    imagePreview.src = e.target.result;
                    fileName.textContent = file.name;
                };
                reader.readAsDataURL(file);
            }
        }

        function removeFile() {
            const fileInput = document.getElementById('flyer');
            const preview = document.getElementById('filePreview');

            fileInput.value = '';
            preview.classList.add('hidden');
            document.getElementById('imagePreview').src = '#';
            document.getElementById('fileName').textContent = '';
        }

        // Harga Bulanan Functions
        function addHargaBulanan() {
            const container = document.getElementById('hargaBulananList');
            const row = document.createElement('div');
            row.className =
                'harga-row grid grid-cols-1 md:grid-cols-4 gap-3 mb-2 items-end bg-gray-50 p-3 rounded-lg border border-gray-200';
            row.innerHTML = `
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Bulan <span class="text-red-500">*</span></label>
                    <select name="harga_bulanan[${hargaIndex}][bulan]"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" required>
                        <option value="">Pilih Bulan</option>
                        @for ($b = 1; $b <= 12; $b++)
                            <option value="{{ $b }}">{{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tahun <span class="text-red-500">*</span></label>
                    <select name="harga_bulanan[${hargaIndex}][tahun]"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" required>
                        <option value="">Pilih Tahun</option>
                        @for ($y = date('Y'); $y <= date('Y') + 5; $y++)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="text" name="harga_bulanan[${hargaIndex}][harga]"
                        oninput="formatRupiah(this)"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                        placeholder="0" required>
                </div>
                <div class="flex items-center gap-2">
                    <label class="flex items-center gap-1 text-xs">
                        <input type="checkbox" name="harga_bulanan[${hargaIndex}][is_active]" value="1" checked>
                        Aktif
                    </label>
                    <button type="button" onclick="removeHargaRow(this)"
                        class="text-red-500 hover:text-red-700 text-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            container.appendChild(row);
            hargaIndex++;
        }

        function removeHargaRow(button) {
            const row = button.closest('.harga-row');
            const totalRows = document.querySelectorAll('.harga-row').length;

            if (totalRows <= 1) {
                alert('Minimal harus ada 1 data harga bulanan!');
                return;
            }

            if (confirm('Hapus harga bulanan ini?')) {
                row.remove();
            }
        }

        // Form submit - clean harga
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('produkForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Clean harga bulanan
                    document.querySelectorAll('[name$="[harga]"]').forEach(input => {
                        if (input.value) {
                            input.value = input.value.replace(/\./g, '');
                        }
                    });
                });
            }

            // Drag and Drop
            const dropzone = document.getElementById('dropzone');
            if (dropzone) {
                dropzone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.classList.add('border-yellow-500', 'bg-yellow-50');
                });

                dropzone.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    this.classList.remove('border-yellow-500', 'bg-yellow-50');
                });

                dropzone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('border-yellow-500', 'bg-yellow-50');

                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        const fileInput = document.getElementById('flyer');
                        fileInput.files = files;
                        const event = new Event('change');
                        fileInput.dispatchEvent(event);
                    }
                });
            }

            togglePaketTour();
            calculateTotalDurasi();
        });
    </script>
@endpush
