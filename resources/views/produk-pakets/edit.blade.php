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
                enctype="multipart/form-data">
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

                    <!-- Harga Dasar -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-money-bill-wave text-yellow-500 mr-2"></i> Harga
                        </h6>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Harga Dasar <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                <input type="number" name="harga_dasar"
                                    value="{{ old('harga_dasar', $produk->harga_dasar) }}"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="0" min="0" required>
                            </div>
                            @error('harga_dasar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-400 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Harga dasar produk per orang
                            </p>
                        </div>
                    </div>

                    <!-- Durasi -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-clock text-yellow-500 mr-2"></i> Detail Durasi
                        </h6>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Durasi Perjalanan <span class="text-gray-400 text-xs">(total hari perjalanan)</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="durasi_perjalanan" id="durasi_perjalanan"
                                    value="{{ old('durasi_perjalanan', $produk->durasi_perjalanan) }}"
                                    oninput="calculateTotalDurasi()"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Contoh: 12" min="0">
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
                                Total durasi akan dihitung otomatis
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

                    <!-- Pilih Paket Tour -->
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
        // Toggle Paket Tour
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
            }
        });

        // Calculate Total Durasi
        function calculateTotalDurasi() {
            const durasiPerjalanan = parseFloat(document.getElementById('durasi_perjalanan').value) || 0;
            const durasiMekkah = parseFloat(document.getElementById('durasi_mekkah').value) || 0;
            const durasiMadinah = parseFloat(document.getElementById('durasi_madinah').value) || 0;

            const total = durasiPerjalanan + durasiMekkah + durasiMadinah;
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

        // Drag and Drop
        document.addEventListener('DOMContentLoaded', function() {
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

            calculateTotalDurasi();
        });
    </script>
@endpush
