@extends('layouts.app')

@section('title', 'Tambah Keluarga / Kelompok - Arrum Tour')
@section('page-title', 'Tambah Keluarga / Kelompok')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.keluarga.index') }}" class="text-gray-500 hover:text-yellow-600">Transaksional</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.keluarga.index') }}" class="text-gray-500 hover:text-yellow-600">Keluarga /
            Kelompok</a>
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
                <h5 class="text-sm font-semibold text-gray-700">Form Tambah Keluarga / Kelompok</h5>
                <p class="text-xs text-gray-400 mt-0.5">Lengkapi data keluarga / kelompok dan jamaah</p>
            </div>

            <form action="{{ route('transaksional.keluarga.store') }}" method="POST" id="keluargaForm"
                enctype="multipart/form-data">
                @csrf

                <div class="p-6 space-y-6">
                    <!-- Informasi Keluarga / Kelompok -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-users text-yellow-500 mr-2"></i> Informasi Keluarga / Kelompok
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Keluarga / Kelompok <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_keluarga" value="{{ old('nama_keluarga') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Contoh: Keluarga Bapak Ahmad" required>
                                @error('nama_keluarga')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Produk Paket <span class="text-red-500">*</span>
                                </label>
                                <select name="produk_paket" id="produk_paket"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($produkPakets as $produk)
                                        <option value="{{ $produk->nama_produk }}"
                                            {{ old('produk_paket') == $produk->nama_produk ? 'selected' : '' }}>
                                            {{ $produk->nama_produk }}
                                            ({{ $produk->durasi_hari }} Hari)
                                        </option>
                                    @endforeach
                                </select>
                                @error('produk_paket')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <div id="placeholder-belum-pilih"
                                class="bg-gray-50 rounded-xl p-4 border border-gray-200 text-center">
                                <p class="text-sm text-gray-400">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Pilih produk paket terlebih dahulu
                                </p>
                            </div>

                            <div id="step-1-container" class="mb-3 hidden">
                                <label class="block text-xs font-medium text-gray-600 mb-1.5">
                                    <i class="fas fa-calendar text-yellow-500 mr-1"></i>
                                    Langkah 1: Pilih Tahun Keberangkatan
                                </label>
                                <select id="filter-tahun"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                    <option value="">-- Pilih Tahun --</option>
                                </select>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Pilih tahun terlebih dahulu untuk melihat bulan keberangkatan yang tersedia
                                </p>
                            </div>

                            <div id="step-2-container" class="hidden">
                                <label class="block text-xs font-medium text-gray-600 mb-1.5">
                                    <i class="fas fa-plane text-yellow-500 mr-1"></i>
                                    Langkah 2: Pilih Bulan Keberangkatan
                                </label>
                                <div id="keberangkatan-container" class="space-y-3">
                                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 text-center">
                                        <p class="text-sm text-gray-400">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Pilih tahun terlebih dahulu
                                        </p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Pilih salah satu keberangkatan. Harga bisa diambil dari bulan/tahun berbeda.
                                </p>
                            </div>

                            <input type="hidden" name="bulan_keberangkatan" id="bulan_keberangkatan_hidden"
                                value="{{ old('bulan_keberangkatan') }}">
                            <input type="hidden" name="tahun_keberangkatan" id="tahun_keberangkatan_hidden"
                                value="{{ old('tahun_keberangkatan') }}">
                            <input type="hidden" name="id_produk_keberangkatan" id="id_produk_keberangkatan_hidden"
                                value="{{ old('id_produk_keberangkatan') }}">
                            <input type="hidden" name="bulan_harga" id="bulan_harga_hidden"
                                value="{{ old('bulan_harga') }}">
                            <input type="hidden" name="tahun_harga" id="tahun_harga_hidden"
                                value="{{ old('tahun_harga') }}">
                            <input type="hidden" name="id_produk_harga_bulanan" id="id_produk_harga_bulanan_hidden"
                                value="{{ old('id_produk_harga_bulanan') }}">

                            @error('id_produk_keberangkatan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Info Harga Produk -->
                    <div id="hargaInfo" class="hidden">
                        <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500">Harga Produk untuk</p>
                                    <p class="text-sm font-semibold text-gray-700" id="hargaBulanTahun">-</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">Harga per Orang</p>
                                    <p class="text-xl font-bold text-yellow-600" id="hargaProdukDisplay">Rp 0</p>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-green-200">
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Harga bisa berbeda dengan bulan keberangkatan
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Agent -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-handshake text-yellow-500 mr-2"></i> Agent
                        </h6>
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                            <h6 class="text-xs font-semibold text-blue-700 mb-3 flex items-center">
                                <i class="fas fa-user-tie text-blue-500 mr-1"></i> Agent
                            </h6>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Agent</label>
                                    <input type="text" name="agent" value="{{ old('agent') }}"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                        placeholder="Nama Agent (opsional)">
                                    @error('agent')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Fee Agent</label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                        <input type="text" name="fee_agent" id="fee_agent"
                                            value="{{ old('fee_agent', 0) }}"
                                            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                            placeholder="0" oninput="formatRupiah(this)" onfocus="handleZeroFocus(this)"
                                            onblur="handleZeroBlur(this)">
                                    </div>
                                    @error('fee_agent')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Diskon -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-tags text-yellow-500 mr-2"></i> Diskon
                            <span class="ml-2 text-xs text-gray-400">(total kelompok, dibagi rata per jamaah)</span>
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Diskon</label>
                                <select name="id_diskon" id="id_diskon"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                    <option value="">-- Tanpa Diskon --</option>
                                    @foreach ($diskons as $diskon)
                                        <option value="{{ $diskon->id_diskon }}"
                                            data-nilai="{{ $diskon->nilai_diskon }}"
                                            {{ old('id_diskon') == $diskon->id_diskon ? 'selected' : '' }}>
                                            {{ $diskon->nama_diskon }}
                                            (-{{ $diskon->nilai_diskon_formatted }})
                                            - Sisa Kuota: {{ $diskon->sisa_kuota }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i> Diskon berlaku untuk seluruh jamaah dalam
                                    keluarga / kelompok
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan Diskon</label>
                                <input type="text" name="keterangan_diskon" value="{{ old('keterangan_diskon') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Catatan diskon (opsional)">
                            </div>
                        </div>

                        <div id="infoDiskonContainer"
                            class="mt-3 bg-green-50 rounded-xl p-3 border border-green-200 hidden">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                                <div>
                                    <span class="text-gray-500 text-xs">Total Diskon Kelompok</span>
                                    <p class="font-bold text-rose-700" id="infoTotalDiskon">Rp 0</p>
                                </div>
                                <div>
                                    <span class="text-gray-500 text-xs">Jumlah Jamaah</span>
                                    <p class="font-bold text-blue-700" id="infoJumlahJamaah">0</p>
                                </div>
                                <div>
                                    <span class="text-gray-500 text-xs">Diskon per Orang</span>
                                    <p class="font-bold text-green-700" id="infoDiskonPerOrang">Rp 0</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- UPGRADE HOTEL KELUARGA -->
                    <div class="border-b border-gray-200 pb-4">
                        <div class="flex items-center justify-between mb-2">
                            <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-hotel text-orange-500 mr-2"></i>
                                Upgrade Hotel & Kamar Keluarga
                                <span class="ml-2 text-xs text-gray-400 font-normal">(opsional · dibagi rata ke
                                    semua jamaah)</span>
                            </h6>
                            <button type="button" onclick="toggleUpgradeKeluarga()"
                                class="text-xs text-blue-500 hover:text-blue-700 font-medium">
                                <i class="fas fa-plus mr-1"></i> Atur Hotel
                            </button>
                        </div>

                        <div id="upgradeKeluargaContainer"
                            class="hidden bg-orange-50 rounded-lg p-3 border border-orange-200">
                            <p class="text-xs text-orange-700 mb-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Biaya upgrade ini akan <strong>dibagi rata</strong> ke seluruh jamaah dalam keluarga.
                            </p>

                            <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg bg-white p-2 space-y-2">
                                @foreach ($hotelOptions as $hotel)
                                    <div class="hotel-upgrade-item border border-gray-200 rounded-lg"
                                        data-hotel-id="{{ $hotel->id_hotel }}" data-scope="keluarga">
                                        <label
                                            class="flex items-center gap-2 p-2 cursor-pointer hover:bg-gray-50 rounded-t-lg">
                                            <input type="checkbox"
                                                class="upgrade-hotel-checkbox w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500"
                                                data-hotel-id="{{ $hotel->id_hotel }}" data-scope="keluarga"
                                                onchange="toggleHotelDetailKeluarga({{ $hotel->id_hotel }})">
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-700">{{ $hotel->nama_hotel }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                    {{ $hotel->kota ?? '-' }}
                                                    @if ($hotel->bintang)
                                                        · {{ $hotel->bintang_text }}
                                                    @endif
                                                </p>
                                            </div>
                                        </label>

                                        <div id="hotelDetailContainer-keluarga-{{ $hotel->id_hotel }}"
                                            class="hidden border-t border-gray-100 p-2 space-y-2">
                                            <div class="text-xs text-gray-400 text-center py-2">
                                                <i class="fas fa-spinner fa-spin mr-1"></i> Memuat tipe kamar...
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div
                                class="mt-2 flex items-center justify-between bg-white rounded-lg border border-orange-200 px-3 py-2">
                                <span class="text-xs font-medium text-gray-700">
                                    <i class="fas fa-calculator mr-1 text-orange-500"></i>
                                    Total Upgrade Keluarga:
                                </span>
                                <span id="totalUpgradeKeluarga" class="text-sm font-bold text-orange-600">Rp 0</span>
                            </div>

                            <div class="mt-1 text-xs text-gray-500 text-right">
                                Per Jamaah: <span id="perJamaahUpgradeKeluarga" class="font-medium text-orange-600">Rp
                                    0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Jamaah -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-user text-yellow-500 mr-2"></i> Daftar Jamaah
                                <span class="ml-2 text-xs text-gray-400">(minimal 1 jamaah)</span>
                                <span class="ml-2 text-xs text-red-500 font-normal">(semua wajib diisi)</span>
                            </h6>
                            <button type="button" onclick="addJamaah()"
                                class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm font-medium">
                                <i class="fas fa-plus mr-2"></i> Tambah Jamaah
                            </button>
                        </div>

                        <div id="jamaahError" class="hidden mb-3 bg-red-50 border border-red-200 rounded-xl p-3">
                            <p class="text-sm text-red-600 flex items-start">
                                <i class="fas fa-exclamation-triangle mt-0.5 mr-2"></i>
                                <span id="jamaahErrorText">Lengkapi semua data jamaah yang wajib diisi.</span>
                            </p>
                        </div>

                        <div id="jamaahContainer">
                            @php
                                $oldJamaahs = old('jamaahs', []);
                            @endphp

                            @if (count($oldJamaahs) > 0)
                                @foreach ($oldJamaahs as $index => $jamaah)
                                    <div class="jamaah-row bg-gray-50 rounded-xl p-4 mb-3 border border-gray-200"
                                        id="jamaah-row-{{ $index }}">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-sm font-medium text-gray-700">Jamaah
                                                #{{ $index + 1 }}</span>
                                            <button type="button" onclick="removeJamaah(this)"
                                                class="text-red-500 hover:text-red-700 text-sm">
                                                <i class="fas fa-times"></i> Hapus
                                            </button>
                                        </div>

                                        <!-- Baris 1 -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                                    Nama Lengkap <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="jamaahs[{{ $index }}][nama_lengkap]"
                                                    value="{{ $jamaah['nama_lengkap'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                                    required>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                                    NIK <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="jamaahs[{{ $index }}][nik]"
                                                    value="{{ $jamaah['nik'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                                    placeholder="16 digit NIK" required>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                                    Nama Ayah <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="jamaahs[{{ $index }}][nama_ayah]"
                                                    value="{{ $jamaah['nama_ayah'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                                    required>
                                            </div>
                                        </div>

                                        <!-- Baris 2 -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                                    Pekerjaan <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="jamaahs[{{ $index }}][pekerjaan]"
                                                    value="{{ $jamaah['pekerjaan'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                                    required>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                                    Jenis Kelamin <span class="text-red-500">*</span>
                                                </label>
                                                <select name="jamaahs[{{ $index }}][jenis_kelamin]"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                                    required>
                                                    <option value="">-- Pilih --</option>
                                                    <option value="L"
                                                        {{ ($jamaah['jenis_kelamin'] ?? '') == 'L' ? 'selected' : '' }}>
                                                        Laki-laki</option>
                                                    <option value="P"
                                                        {{ ($jamaah['jenis_kelamin'] ?? '') == 'P' ? 'selected' : '' }}>
                                                        Perempuan</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                                    Telepon <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="jamaahs[{{ $index }}][telepon]"
                                                    value="{{ $jamaah['telepon'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                                    placeholder="08xxxxxxxxxx" required>
                                            </div>
                                        </div>

                                        <!-- Baris 3 -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                                    WhatsApp <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="jamaahs[{{ $index }}][wa]"
                                                    value="{{ $jamaah['wa'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                                    placeholder="08xxxxxxxxxx" required>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                                    Tempat Lahir <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="jamaahs[{{ $index }}][tempat_lahir]"
                                                    value="{{ $jamaah['tempat_lahir'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                                    required>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                                    Tanggal Lahir
                                                </label>
                                                <input type="date" name="jamaahs[{{ $index }}][tanggal_lahir]"
                                                    value="{{ $jamaah['tanggal_lahir'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                            </div>
                                        </div>

                                        {{-- SECTION: ALAMAT DOMISILI --}}
                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <h6 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                                                <i class="fas fa-map-marker-alt text-yellow-500 mr-1.5"></i>
                                                Alamat Domisili
                                            </h6>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Hubungan
                                                        Keluarga</label>
                                                    <select name="jamaahs[{{ $index }}][hubungan_keluarga]"
                                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                                        <option value="">-- Pilih Hubungan --</option>
                                                        @foreach ($hubunganOptions as $option)
                                                            <option value="{{ $option }}"
                                                                {{ ($jamaah['hubungan_keluarga'] ?? '') == $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-xs font-medium text-gray-600 mb-1">Alamat</label>
                                                    <input type="text" name="jamaahs[{{ $index }}][alamat]"
                                                        value="{{ $jamaah['alamat'] ?? '' }}"
                                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                        placeholder="Jl. / RT / RW / No.">
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Kota /
                                                        Kabupaten</label>
                                                    <input type="text"
                                                        name="jamaahs[{{ $index }}][kota_kabupaten]"
                                                        value="{{ $jamaah['kota_kabupaten'] ?? '' }}"
                                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                        placeholder="Kota/Kabupaten">
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-xs font-medium text-gray-600 mb-1">Kecamatan</label>
                                                    <input type="text" name="jamaahs[{{ $index }}][kecamatan]"
                                                        value="{{ $jamaah['kecamatan'] ?? '' }}"
                                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                        placeholder="Kecamatan">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Kelurahan
                                                        / Desa</label>
                                                    <input type="text" name="jamaahs[{{ $index }}][kelurahan]"
                                                        value="{{ $jamaah['kelurahan'] ?? '' }}"
                                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                        placeholder="Kelurahan/Desa">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- SECTION: KEBERANGKATAN --}}
                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <div class="bg-blue-50 rounded-lg p-3 border border-blue-100">
                                                <h6 class="text-xs font-semibold text-blue-700 mb-2 flex items-center">
                                                    <i class="fas fa-plane text-blue-500 mr-1.5"></i>
                                                    Keberangkatan
                                                </h6>

                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1">Kota
                                                            Asal</label>
                                                        <select name="jamaahs[{{ $index }}][kota_asal]"
                                                            class="kota-asal-select w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm bg-white">
                                                            <option value="">-- Pilih Kota Asal --</option>
                                                            @foreach ($kotaAsals as $kota)
                                                                <option value="{{ $kota->nama_kota }}"
                                                                    data-pulau="{{ $kota->pulau }}"
                                                                    data-bandara="{{ $kota->bandara_terdekat }}"
                                                                    {{ ($jamaah['kota_asal'] ?? '') == $kota->nama_kota ? 'selected' : '' }}>
                                                                    {{ $kota->nama_kota }} - {{ $kota->provinsi ?? '' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="block text-xs font-medium text-gray-600 mb-1">Pulau</label>
                                                        <input type="text" name="jamaahs[{{ $index }}][pulau]"
                                                            value="{{ $jamaah['pulau'] ?? '' }}"
                                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm cursor-not-allowed"
                                                            readonly placeholder="Otomatis">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1">Bandara
                                                            Keberangkatan</label>
                                                        <input type="text"
                                                            name="jamaahs[{{ $index }}][bandara_keberangkatan]"
                                                            value="{{ $jamaah['bandara_keberangkatan'] ?? '' }}"
                                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm cursor-not-allowed"
                                                            readonly placeholder="Otomatis">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- SECTION: PASSPORT & DOKUMEN --}}
                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <h6 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                                                <i class="fas fa-passport text-yellow-500 mr-1.5"></i>
                                                Passport & Dokumen
                                            </h6>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Nomor
                                                        Paspor</label>
                                                    <input type="text"
                                                        name="jamaahs[{{ $index }}][nomor_paspor]"
                                                        value="{{ $jamaah['nomor_paspor'] ?? '' }}"
                                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                                </div>
                                                <div>
                                                    <label
                                                        class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer mt-6">
                                                        <input type="checkbox"
                                                            name="jamaahs[{{ $index }}][is_kepala_keluarga]"
                                                            value="1"
                                                            {{ $jamaah['is_kepala_keluarga'] ?? false ? 'checked' : '' }}
                                                            class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                                                        <span>Kepala Keluarga / Kelompok</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Pembuatan Passport -->
                                            <div class="mt-3 pt-3 border-t border-gray-200">
                                                @php
                                                    $isPassport = ($jamaah['is_pembuatan_passport'] ?? 0) == 1;
                                                @endphp
                                                <div class="mb-3">
                                                    <label class="block text-xs font-medium text-gray-600 mb-1.5">
                                                        Apakah ada Pembuatan Passport? <span class="text-red-500">*</span>
                                                    </label>

                                                    <div
                                                        class="inline-flex rounded-lg overflow-hidden border border-gray-300">
                                                        <button type="button"
                                                            onclick="setPembuatanPassportKeluarga({{ $index }}, 1)"
                                                            id="btnYa-{{ $index }}"
                                                            class="px-6 py-2 text-sm font-medium transition-colors
                                                            {{ $isPassport ? 'bg-green-500 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                                                            <i class="fas fa-check mr-1"></i> YA
                                                        </button>
                                                        <button type="button"
                                                            onclick="setPembuatanPassportKeluarga({{ $index }}, 0)"
                                                            id="btnTidak-{{ $index }}"
                                                            class="px-6 py-2 text-sm font-medium transition-colors border-l border-gray-300
                                                            {{ !$isPassport ? 'bg-red-500 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                                                            <i class="fas fa-times mr-1"></i> TIDAK
                                                        </button>
                                                    </div>
                                                    <input type="hidden"
                                                        name="jamaahs[{{ $index }}][is_pembuatan_passport]"
                                                        id="is_pembuatan_passport-{{ $index }}"
                                                        value="{{ $isPassport ? 1 : 0 }}">

                                                    <p class="text-xs text-gray-400 mt-1">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        Pilih <b>YA</b> jika ada pendapatan perusahaan. Pilih
                                                        <b>TIDAK</b> jika jamaah sudah punya passport.
                                                    </p>
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1">
                                                            Pembayaran Passport <span class="text-gray-400 text-xs">(boleh
                                                                kosong)</span>
                                                        </label>
                                                        <div class="relative">
                                                            <span
                                                                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                                            <input type="text"
                                                                name="jamaahs[{{ $index }}][pendampingan_fee]"
                                                                value="{{ $jamaah['pendampingan_fee'] ?? 0 }}"
                                                                oninput="formatRupiah(this); updateTotalPendampingan({{ $index }})"
                                                                onfocus="handleZeroFocus(this)"
                                                                onblur="handleZeroBlur(this)"
                                                                class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                                placeholder="0">
                                                        </div>
                                                    </div>

                                                    <div id="feePetugasContainer-{{ $index }}"
                                                        class="{{ $isPassport ? '' : 'hidden' }}">
                                                        <label class="block text-xs font-medium text-gray-600 mb-1">
                                                            Pendapatan Perusahaan
                                                            <span class="text-gray-400 text-xs">(fee petugas)</span>
                                                        </label>
                                                        <div class="relative">
                                                            <span
                                                                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                                            <input type="text"
                                                                name="jamaahs[{{ $index }}][pendampingan_fee_petugas]"
                                                                id="pendampingan_fee_petugas-{{ $index }}"
                                                                value="{{ $jamaah['pendampingan_fee_petugas'] ?? 0 }}"
                                                                oninput="formatRupiah(this); updateTotalPendampingan({{ $index }})"
                                                                onfocus="handleZeroFocus(this)"
                                                                onblur="handleZeroBlur(this)"
                                                                class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                                placeholder="0">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-2 text-xs text-gray-500">
                                                    Total Pembuatan Passport:
                                                    <span class="font-bold text-blue-600"
                                                        id="total-pendampingan-{{ $index }}">
                                                        Rp
                                                        {{ isset($jamaah['pendampingan_fee']) && isset($jamaah['pendampingan_fee_petugas']) ? number_format($jamaah['pendampingan_fee'] + $jamaah['pendampingan_fee_petugas'], 0, ',', '.') : '0' }}
                                                    </span>
                                                    <span class="text-gray-400">(Pembayaran + Pendapatan
                                                        Perusahaan)</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- SECTION: UPGRADE HOTEL JAMAAH --}}
                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <div class="flex items-center justify-between mb-2">
                                                <h6 class="text-xs font-semibold text-gray-700 flex items-center">
                                                    <i class="fas fa-hotel text-orange-500 mr-1.5"></i>
                                                    Upgrade Hotel & Kamar Jamaah
                                                    <span class="ml-2 text-[10px] text-gray-400 font-normal">(opsional
                                                        · khusus jamaah ini)</span>
                                                </h6>
                                                <button type="button"
                                                    onclick="toggleUpgradeJamaah({{ $index }})"
                                                    class="text-xs text-blue-500 hover:text-blue-700 font-medium">
                                                    <i class="fas fa-plus mr-1"></i> Atur Hotel
                                                </button>
                                            </div>

                                            <div id="upgradeJamaahContainer-{{ $index }}"
                                                class="hidden bg-orange-50 rounded-lg p-3 border border-orange-200">
                                                <p class="text-xs text-orange-700 mb-2">
                                                    <i class="fas fa-info-circle mr-1"></i>
                                                    Biaya upgrade ini hanya dibebankan ke jamaah ini.
                                                </p>

                                                <div
                                                    class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg bg-white p-2 space-y-2">
                                                    @foreach ($hotelOptions as $hotel)
                                                        <div class="hotel-upgrade-item border border-gray-200 rounded-lg"
                                                            data-hotel-id="{{ $hotel->id_hotel }}"
                                                            data-scope="jamaah-{{ $index }}">
                                                            <label
                                                                class="flex items-center gap-2 p-2 cursor-pointer hover:bg-gray-50 rounded-t-lg">
                                                                <input type="checkbox"
                                                                    class="upgrade-hotel-checkbox w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500"
                                                                    data-hotel-id="{{ $hotel->id_hotel }}"
                                                                    data-scope="jamaah-{{ $index }}"
                                                                    onchange="toggleHotelDetailJamaah({{ $index }}, {{ $hotel->id_hotel }})">
                                                                <div class="flex-1">
                                                                    <p class="text-sm font-medium text-gray-700">
                                                                        {{ $hotel->nama_hotel }}</p>
                                                                    <p class="text-xs text-gray-500">
                                                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                                                        {{ $hotel->kota ?? '-' }}
                                                                        @if ($hotel->bintang)
                                                                            · {{ $hotel->bintang_text }}
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            </label>

                                                            <div id="hotelDetailContainer-{{ $index }}-{{ $hotel->id_hotel }}"
                                                                class="hidden border-t border-gray-100 p-2 space-y-2">
                                                                <div class="text-xs text-gray-400 text-center py-2">
                                                                    <i class="fas fa-spinner fa-spin mr-1"></i> Memuat
                                                                    tipe kamar...
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <div
                                                    class="mt-2 flex items-center justify-between bg-white rounded-lg border border-orange-200 px-3 py-2">
                                                    <span class="text-xs font-medium text-gray-700">
                                                        <i class="fas fa-calculator mr-1 text-orange-500"></i>
                                                        Total Upgrade Jamaah:
                                                    </span>
                                                    <span id="totalUpgradeJamaah-{{ $index }}"
                                                        class="text-sm font-bold text-orange-600">Rp 0</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- File Upload -->
                                        <div
                                            class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-3 pt-3 border-t border-gray-200">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">File
                                                    KTP/KK</label>
                                                <input type="file" name="jamaahs[{{ $index }}][file_ktp_kk]"
                                                    accept=".pdf,.jpg,.jpeg,.png"
                                                    class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                                <p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">File
                                                    Vaksin</label>
                                                <input type="file" name="jamaahs[{{ $index }}][file_vaksin]"
                                                    accept=".pdf,.jpg,.jpeg,.png"
                                                    class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                                <p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">File
                                                    Visa</label>
                                                <input type="file" name="jamaahs[{{ $index }}][file_visa]"
                                                    accept=".pdf,.jpg,.jpeg,.png"
                                                    class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                                <p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">File
                                                    Paspor</label>
                                                <input type="file" name="jamaahs[{{ $index }}][file_paspor]"
                                                    accept=".pdf,.jpg,.jpeg,.png"
                                                    class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                                <p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                {{-- Baris default jamaah #1 --}}
                                <div class="jamaah-row bg-gray-50 rounded-xl p-4 mb-3 border border-gray-200"
                                    id="jamaah-row-0">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-sm font-medium text-gray-700">Jamaah #1</span>
                                        <button type="button" onclick="removeJamaah(this)"
                                            class="text-red-500 hover:text-red-700 text-sm">
                                            <i class="fas fa-times"></i> Hapus
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                Nama Lengkap <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="jamaahs[0][nama_lengkap]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                                required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                NIK <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="jamaahs[0][nik]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                                placeholder="16 digit NIK" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                Nama Ayah <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="jamaahs[0][nama_ayah]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                                required>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                Pekerjaan <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="jamaahs[0][pekerjaan]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                                required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                Jenis Kelamin <span class="text-red-500">*</span>
                                            </label>
                                            <select name="jamaahs[0][jenis_kelamin]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                                required>
                                                <option value="">-- Pilih --</option>
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                Telepon <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="jamaahs[0][telepon]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                                placeholder="08xxxxxxxxxx" required>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                WhatsApp <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="jamaahs[0][wa]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                                placeholder="08xxxxxxxxxx" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                Tempat Lahir <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="jamaahs[0][tempat_lahir]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                                required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                Tanggal Lahir
                                            </label>
                                            <input type="date" name="jamaahs[0][tanggal_lahir]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                        </div>
                                    </div>

                                    {{-- SECTION: ALAMAT DOMISILI --}}
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <h6 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                                            <i class="fas fa-map-marker-alt text-yellow-500 mr-1.5"></i>
                                            Alamat Domisili
                                        </h6>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Hubungan
                                                    Keluarga</label>
                                                <select name="jamaahs[0][hubungan_keluarga]"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                                    <option value="">-- Pilih Hubungan --</option>
                                                    @foreach ($hubunganOptions as $option)
                                                        <option value="{{ $option }}">{{ $option }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Alamat</label>
                                                <input type="text" name="jamaahs[0][alamat]"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                    placeholder="Jl. / RT / RW / No.">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Kota /
                                                    Kabupaten</label>
                                                <input type="text" name="jamaahs[0][kota_kabupaten]"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                    placeholder="Kota/Kabupaten">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-600 mb-1">Kecamatan</label>
                                                <input type="text" name="jamaahs[0][kecamatan]"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                    placeholder="Kecamatan">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Kelurahan /
                                                    Desa</label>
                                                <input type="text" name="jamaahs[0][kelurahan]"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                    placeholder="Kelurahan/Desa">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- SECTION: KEBERANGKATAN --}}
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <div class="bg-blue-50 rounded-lg p-3 border border-blue-100">
                                            <h6 class="text-xs font-semibold text-blue-700 mb-2 flex items-center">
                                                <i class="fas fa-plane text-blue-500 mr-1.5"></i>
                                                Keberangkatan
                                            </h6>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Kota
                                                        Asal</label>
                                                    <select name="jamaahs[0][kota_asal]"
                                                        class="kota-asal-select w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm bg-white">
                                                        <option value="">-- Pilih Kota Asal --</option>
                                                        @foreach ($kotaAsals as $kota)
                                                            <option value="{{ $kota->nama_kota }}"
                                                                data-pulau="{{ $kota->pulau }}"
                                                                data-bandara="{{ $kota->bandara_terdekat }}">
                                                                {{ $kota->nama_kota }} - {{ $kota->provinsi ?? '' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-xs font-medium text-gray-600 mb-1">Pulau</label>
                                                    <input type="text" name="jamaahs[0][pulau]"
                                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm cursor-not-allowed"
                                                        readonly placeholder="Otomatis">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Bandara
                                                        Keberangkatan</label>
                                                    <input type="text" name="jamaahs[0][bandara_keberangkatan]"
                                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm cursor-not-allowed"
                                                        readonly placeholder="Otomatis">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- SECTION: PASSPORT & DOKUMEN --}}
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <h6 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                                            <i class="fas fa-passport text-yellow-500 mr-1.5"></i>
                                            Passport & Dokumen
                                        </h6>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Nomor
                                                    Paspor</label>
                                                <input type="text" name="jamaahs[0][nomor_paspor]"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                            </div>
                                            <div>
                                                <label
                                                    class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer mt-6">
                                                    <input type="checkbox" name="jamaahs[0][is_kepala_keluarga]"
                                                        value="1" checked
                                                        class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                                                    <span>Kepala Keluarga / Kelompok</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <div class="mb-3">
                                                <label class="block text-xs font-medium text-gray-600 mb-1.5">
                                                    Apakah ada Pembuatan Passport? <span class="text-red-500">*</span>
                                                </label>

                                                <div class="inline-flex rounded-lg overflow-hidden border border-gray-300">
                                                    <button type="button" onclick="setPembuatanPassportKeluarga(0, 1)"
                                                        id="btnYa-0"
                                                        class="px-6 py-2 text-sm font-medium bg-white text-gray-600 hover:bg-gray-50 transition-colors">
                                                        <i class="fas fa-check mr-1"></i> YA
                                                    </button>
                                                    <button type="button" onclick="setPembuatanPassportKeluarga(0, 0)"
                                                        id="btnTidak-0"
                                                        class="px-6 py-2 text-sm font-medium bg-red-500 text-white transition-colors border-l border-gray-300">
                                                        <i class="fas fa-times mr-1"></i> TIDAK
                                                    </button>
                                                </div>
                                                <input type="hidden" name="jamaahs[0][is_pembuatan_passport]"
                                                    id="is_pembuatan_passport-0" value="0">

                                                <p class="text-xs text-gray-400 mt-1">
                                                    <i class="fas fa-info-circle mr-1"></i>
                                                    Pilih <b>YA</b> jika ada pendapatan perusahaan. Pilih <b>TIDAK</b>
                                                    jika jamaah sudah punya passport.
                                                </p>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                                        Pembayaran Passport <span class="text-gray-400 text-xs">(boleh
                                                            kosong)</span>
                                                    </label>
                                                    <div class="relative">
                                                        <span
                                                            class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                                        <input type="text" name="jamaahs[0][pendampingan_fee]"
                                                            value="0"
                                                            oninput="formatRupiah(this); updateTotalPendampingan(0)"
                                                            onfocus="handleZeroFocus(this)" onblur="handleZeroBlur(this)"
                                                            class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                            placeholder="0">
                                                    </div>
                                                </div>
                                                <div id="feePetugasContainer-0" class="hidden">
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                                        Pendapatan Perusahaan
                                                        <span class="text-gray-400 text-xs">(fee petugas)</span>
                                                    </label>
                                                    <div class="relative">
                                                        <span
                                                            class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                                        <input type="text" name="jamaahs[0][pendampingan_fee_petugas]"
                                                            id="pendampingan_fee_petugas-0" value="0"
                                                            oninput="formatRupiah(this); updateTotalPendampingan(0)"
                                                            onfocus="handleZeroFocus(this)" onblur="handleZeroBlur(this)"
                                                            class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                            placeholder="0">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-2 text-xs text-gray-500">
                                                Total Pembuatan Passport:
                                                <span class="font-bold text-blue-600" id="total-pendampingan-0">Rp
                                                    0</span>
                                                <span class="text-gray-400">(Pembayaran + Pendapatan Perusahaan)</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- SECTION: UPGRADE HOTEL JAMAAH --}}
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <h6 class="text-xs font-semibold text-gray-700 flex items-center">
                                                <i class="fas fa-hotel text-orange-500 mr-1.5"></i>
                                                Upgrade Hotel & Kamar Jamaah
                                                <span class="ml-2 text-[10px] text-gray-400 font-normal">(opsional
                                                    · khusus jamaah ini)</span>
                                            </h6>
                                            <button type="button" onclick="toggleUpgradeJamaah(0)"
                                                class="text-xs text-blue-500 hover:text-blue-700 font-medium">
                                                <i class="fas fa-plus mr-1"></i> Atur Hotel
                                            </button>
                                        </div>

                                        <div id="upgradeJamaahContainer-0"
                                            class="hidden bg-orange-50 rounded-lg p-3 border border-orange-200">
                                            <p class="text-xs text-orange-700 mb-2">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Biaya upgrade ini hanya dibebankan ke jamaah ini.
                                            </p>

                                            <div
                                                class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg bg-white p-2 space-y-2">
                                                @foreach ($hotelOptions as $hotel)
                                                    <div class="hotel-upgrade-item border border-gray-200 rounded-lg"
                                                        data-hotel-id="{{ $hotel->id_hotel }}" data-scope="jamaah-0">
                                                        <label
                                                            class="flex items-center gap-2 p-2 cursor-pointer hover:bg-gray-50 rounded-t-lg">
                                                            <input type="checkbox"
                                                                class="upgrade-hotel-checkbox w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500"
                                                                data-hotel-id="{{ $hotel->id_hotel }}"
                                                                data-scope="jamaah-0"
                                                                onchange="toggleHotelDetailJamaah(0, {{ $hotel->id_hotel }})">
                                                            <div class="flex-1">
                                                                <p class="text-sm font-medium text-gray-700">
                                                                    {{ $hotel->nama_hotel }}</p>
                                                                <p class="text-xs text-gray-500">
                                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                                    {{ $hotel->kota ?? '-' }}
                                                                    @if ($hotel->bintang)
                                                                        · {{ $hotel->bintang_text }}
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </label>

                                                        <div id="hotelDetailContainer-0-{{ $hotel->id_hotel }}"
                                                            class="hidden border-t border-gray-100 p-2 space-y-2">
                                                            <div class="text-xs text-gray-400 text-center py-2">
                                                                <i class="fas fa-spinner fa-spin mr-1"></i> Memuat
                                                                tipe kamar...
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div
                                                class="mt-2 flex items-center justify-between bg-white rounded-lg border border-orange-200 px-3 py-2">
                                                <span class="text-xs font-medium text-gray-700">
                                                    <i class="fas fa-calculator mr-1 text-orange-500"></i>
                                                    Total Upgrade Jamaah:
                                                </span>
                                                <span id="totalUpgradeJamaah-0"
                                                    class="text-sm font-bold text-orange-600">Rp 0</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-3 pt-3 border-t border-gray-200">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">File KTP/KK</label>
                                            <input type="file" name="jamaahs[0][file_ktp_kk]"
                                                accept=".pdf,.jpg,.jpeg,.png"
                                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                            <p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">File Vaksin</label>
                                            <input type="file" name="jamaahs[0][file_vaksin]"
                                                accept=".pdf,.jpg,.jpeg,.png"
                                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                            <p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">File Visa</label>
                                            <input type="file" name="jamaahs[0][file_visa]"
                                                accept=".pdf,.jpg,.jpeg,.png"
                                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                            <p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">File Paspor</label>
                                            <input type="file" name="jamaahs[0][file_paspor]"
                                                accept=".pdf,.jpg,.jpeg,.png"
                                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                            <p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan Tambahan</label>
                        <textarea name="catatan_tambahan" rows="2"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">{{ old('catatan_tambahan') }}</textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4">
                        <a href="{{ route('transaksional.keluarga.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors text-sm font-medium">
                            Batal
                        </a>
                        <button type="submit" id="btnSubmit"
                            class="px-4 py-2 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 transition-colors text-sm font-medium shadow-sm hover:shadow">
                            <i class="fas fa-save mr-2"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            var globalKeberangkatanList = [];
            var globalHargaBulananList = [];

            function formatRupiah(element) {
                let raw = element.value.replace(/[^,\d]/g, '');
                if (raw === '') {
                    element.value = '';
                    return;
                }
                raw = raw.replace(/^0+(?=\d)/, '');
                element.value = raw.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function handleZeroFocus(element) {
                if (element.value === '0' || element.value === '0.000' || element.value === '') {
                    element.value = '';
                }
            }

            function handleZeroBlur(element) {
                if (element.value === '' || element.value === '0') {
                    element.value = '0';
                }
                formatRupiah(element);
            }

            function formatRupiahKamar(input) {
                let value = input.value.replace(/[^,\d]/g, '').replace(/^Rp\s*/, '').replace(/\./g, '');
                if (value === '' || isNaN(value)) value = '0';
                const number = parseInt(value);
                input.value = 'Rp ' + number.toLocaleString('id-ID');
            }

            let jamaahIndex = {{ count(old('jamaahs', [0])) }};

            // ==========================================
            // PEMBUATAN PASSPORT
            // ==========================================
            function setPembuatanPassportKeluarga(index, value) {
                var hidden = document.getElementById('is_pembuatan_passport-' + index);
                var btnYa = document.getElementById('btnYa-' + index);
                var btnTidak = document.getElementById('btnTidak-' + index);
                var container = document.getElementById('feePetugasContainer-' + index);
                var petugasInput = document.getElementById('pendampingan_fee_petugas-' + index);

                if (!hidden || !btnYa || !btnTidak) return;
                hidden.value = value;

                if (value === 1) {
                    btnYa.className = 'px-6 py-2 text-sm font-medium bg-green-500 text-white transition-colors';
                    btnTidak.className =
                        'px-6 py-2 text-sm font-medium bg-white text-gray-600 hover:bg-gray-50 transition-colors border-l border-gray-300';
                    if (container) container.classList.remove('hidden');
                } else {
                    btnYa.className = 'px-6 py-2 text-sm font-medium bg-white text-gray-600 hover:bg-gray-50 transition-colors';
                    btnTidak.className =
                        'px-6 py-2 text-sm font-medium bg-red-500 text-white transition-colors border-l border-gray-300';
                    if (container) container.classList.add('hidden');
                    if (petugasInput) petugasInput.value = '0';
                }
                updateTotalPendampingan(index);
            }

            function updateTotalPendampingan(index) {
                var feeInput = document.querySelector('input[name="jamaahs[' + index + '][pendampingan_fee]"]');
                var petugasInput = document.getElementById('pendampingan_fee_petugas-' + index);
                var totalSpan = document.getElementById('total-pendampingan-' + index);
                var hidden = document.getElementById('is_pembuatan_passport-' + index);

                if (feeInput && totalSpan) {
                    var fee = parseInt(feeInput.value.replace(/\./g, '')) || 0;
                    var petugas = 0;
                    if (hidden && hidden.value === '1' && petugasInput) {
                        petugas = parseInt(petugasInput.value.replace(/\./g, '')) || 0;
                    }
                    totalSpan.textContent = 'Rp ' + (fee + petugas).toLocaleString('id-ID');
                }
            }

            // ==========================================
            // DISKON
            // ==========================================
            function hitungDiskonKelompok() {
                var selectDiskon = document.getElementById('id_diskon');
                if (!selectDiskon) return;

                var container = document.getElementById('infoDiskonContainer');
                var elTotal = document.getElementById('infoTotalDiskon');
                var elJumlah = document.getElementById('infoJumlahJamaah');
                var elPerOrang = document.getElementById('infoDiskonPerOrang');

                var selectedOption = selectDiskon.options[selectDiskon.selectedIndex];
                var totalDiskon = parseInt(selectedOption.dataset.nilai || 0);

                var jumlahJamaah = document.querySelectorAll('.jamaah-row').length;
                var diskonPerOrang = jumlahJamaah > 0 ?
                    Math.round(totalDiskon / jumlahJamaah) : 0;

                if (totalDiskon > 0 && jumlahJamaah > 0) {
                    container.classList.remove('hidden');
                    elTotal.textContent = 'Rp ' + totalDiskon.toLocaleString('id-ID');
                    elJumlah.textContent = jumlahJamaah + ' orang';
                    elPerOrang.textContent = 'Rp ' + diskonPerOrang.toLocaleString('id-ID');
                } else {
                    container.classList.add('hidden');
                }
            }

            // ==========================================
            // VALIDASI
            // ==========================================
            function validateJamaahForm() {
                var labels = {
                    nama_lengkap: 'Nama Lengkap',
                    nik: 'NIK',
                    nama_ayah: 'Nama Ayah',
                    pekerjaan: 'Pekerjaan',
                    jenis_kelamin: 'Jenis Kelamin',
                    telepon: 'Telepon',
                    wa: 'WhatsApp',
                    tempat_lahir: 'Tempat Lahir'
                };
                var missing = [];
                var firstInvalid = null;
                var rows = document.querySelectorAll('.jamaah-row');

                rows.forEach(function(row, idx) {
                    var fields = row.querySelectorAll('.wajib-jamaah');
                    fields.forEach(function(f) {
                        var val = (f.value || '').trim();
                        if (!val) {
                            f.classList.add('border-red-500', 'ring-2', 'ring-red-300');
                            f.classList.remove('border-gray-200');
                            var key = f.name.replace(/jamaahs\[\d+\]\[|\]/g, '');
                            missing.push('Jamaah #' + (idx + 1) + ' - ' + (labels[key] || key));
                            if (!firstInvalid) firstInvalid = f;
                        } else {
                            f.classList.remove('border-red-500', 'ring-2', 'ring-red-300');
                            f.classList.add('border-gray-200');
                        }
                    });
                });

                var errBox = document.getElementById('jamaahError');
                var errText = document.getElementById('jamaahErrorText');

                if (missing.length > 0) {
                    errText.textContent = 'Data jamaah belum lengkap: ' + missing.slice(0, 5).join(', ') +
                        (missing.length > 5 ? ' dan ' + (missing.length - 5) + ' lainnya.' : '.');
                    errBox.classList.remove('hidden');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstInvalid.focus();
                    }
                    return false;
                }
                errBox.classList.add('hidden');
                return true;
            }

            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('wajib-jamaah') && e.target.value.trim() !== '') {
                    e.target.classList.remove('border-red-500', 'ring-2', 'ring-red-300');
                    e.target.classList.add('border-gray-200');
                }
            });

            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('wajib-jamaah') && e.target.value.trim() !== '') {
                    e.target.classList.remove('border-red-500', 'ring-2', 'ring-red-300');
                    e.target.classList.add('border-gray-200');
                }
            });

            // ==========================================
            // LOAD KEBERANGKATAN
            // ==========================================
            function loadKeberangkatan() {
                var produkPaket = document.getElementById('produk_paket');
                var tahunSelect = document.getElementById('filter-tahun');
                var step1Container = document.getElementById('step-1-container');
                var step2Container = document.getElementById('step-2-container');
                var placeholder = document.getElementById('placeholder-belum-pilih');
                var container = document.getElementById('keberangkatan-container');

                tahunSelect.innerHTML = '<option value="">-- Pilih Tahun --</option>';
                step1Container.classList.add('hidden');
                step2Container.classList.add('hidden');
                container.innerHTML = '';
                resetHiddenInputs();

                if (!produkPaket.value) {
                    placeholder.classList.remove('hidden');
                    return;
                }

                placeholder.classList.add('hidden');
                step1Container.classList.remove('hidden');
                tahunSelect.innerHTML = '<option value="">-- Memuat... --</option>';

                fetch(
                        `{{ route('transaksional.keluarga.keberangkatan-by-produk') }}?produk_paket=${encodeURIComponent(produkPaket.value)}`
                    )
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success || !data.keberangkatan || data.keberangkatan.length === 0) {
                            tahunSelect.innerHTML = '<option value="">-- Tidak ada data --</option>';
                            return;
                        }
                        globalKeberangkatanList = data.keberangkatan;
                        globalHargaBulananList = data.harga_bulanan || [];
                        var tahunSet = new Set();
                        globalKeberangkatanList.forEach(k => tahunSet.add(k.tahun));
                        var tahunList = Array.from(tahunSet).sort((a, b) => a - b);
                        tahunSelect.innerHTML = '<option value="">-- Pilih Tahun --</option>';
                        tahunList.forEach(tahun => {
                            var option = document.createElement('option');
                            option.value = tahun;
                            option.textContent = tahun;
                            tahunSelect.appendChild(option);
                        });
                        var oldTahun = document.getElementById('tahun_keberangkatan_hidden').value;
                        if (oldTahun && tahunList.includes(parseInt(oldTahun))) {
                            tahunSelect.value = oldTahun;
                            renderKeberangkatanByTahun(oldTahun);
                        }
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        tahunSelect.innerHTML = '<option value="">-- Gagal memuat --</option>';
                    });
            }

            function renderKeberangkatanByTahun(tahun) {
                var step2Container = document.getElementById('step-2-container');
                var container = document.getElementById('keberangkatan-container');

                if (!tahun) {
                    step2Container.classList.add('hidden');
                    resetHiddenInputs();
                    return;
                }
                step2Container.classList.remove('hidden');
                var filtered = globalKeberangkatanList.filter(k => parseInt(k.tahun) === parseInt(tahun));

                if (filtered.length === 0) {
                    container.innerHTML = `
                        <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-200 text-center">
                            <p class="text-sm text-yellow-600">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Tidak ada keberangkatan di tahun ${tahun}
                            </p>
                        </div>
                    `;
                    return;
                }

                var selectedKeberangkatanId = document.getElementById('id_produk_keberangkatan_hidden').value;
                var selectedHargaBulananId = document.getElementById('id_produk_harga_bulanan_hidden').value;

                var selectedKeberangkatan = null;
                if (selectedKeberangkatanId) {
                    selectedKeberangkatan = filtered.find(k => parseInt(k.id) === parseInt(selectedKeberangkatanId)) || null;
                }

                var options = '<option value="">-- Pilih Keberangkatan --</option>';
                filtered.forEach(function(k) {
                    var isCurrentlySelected = selectedKeberangkatan && parseInt(selectedKeberangkatan.id) === parseInt(k
                        .id);
                    var isFull = k.kuota_default_is_full ? true : false;
                    var label = k.keberangkatan_formatted + ' — ' + k.harga_default_formatted;
                    if (isFull && !isCurrentlySelected) label += ' (KUOTA PENUH)';
                    var disabled = (isFull && !isCurrentlySelected) ? 'disabled' : '';
                    var selected = isCurrentlySelected ? 'selected' : '';
                    options += `<option value="${k.id}" ${selected} ${disabled}>${label}</option>`;
                });

                var hargaPanelHtml = '';
                if (selectedKeberangkatan) {
                    var k = selectedKeberangkatan;
                    var currentIdHarga = selectedHargaBulananId || '';
                    var currentHarga = parseInt(k.harga_default || 0);
                    var currentBulanHarga = k.bulan;
                    var currentTahunHarga = k.tahun;

                    if (currentIdHarga) {
                        var match = globalHargaBulananList.find(h => parseInt(h.id) === parseInt(currentIdHarga));
                        if (match) {
                            currentHarga = parseInt(match.harga);
                            currentBulanHarga = match.bulan;
                            currentTahunHarga = match.tahun;
                        }
                    }

                    var currentKuotaText = k.kuota_default_formatted;
                    var currentKuotaColor = k.kuota_default_is_full ? 'text-red-600' : 'text-blue-600';
                    if (currentIdHarga) {
                        var match2 = globalHargaBulananList.find(h => parseInt(h.id) === parseInt(currentIdHarga));
                        if (match2) {
                            currentKuotaText = match2.kuota_formatted;
                            currentKuotaColor = match2.is_full ? 'text-red-600' :
                                (match2.kuota_sisa !== -1 && match2.kuota_sisa <= 3 ? 'text-orange-600' : 'text-blue-600');
                        }
                    }

                    var hargaOptions = '<option value="">-- Sama dengan keberangkatan (' + k.bulan_formatted + ') --</option>';
                    globalHargaBulananList.forEach(function(h) {
                        var isCur = (parseInt(h.id) === parseInt(currentIdHarga));
                        var sel = isCur ? 'selected' : '';
                        var full = h.is_full ? true : false;
                        var dis = (full && !isCur) ? 'disabled' : '';
                        var kuotaLabel = h.is_full ? 'PENUH (' + h.kuota + ')' : h.kuota_formatted;
                        var styleAttr = full ? 'style="color:#ef4444;font-style:italic;"' : '';
                        hargaOptions += `<option value="${h.id}"
                            data-bulan="${h.bulan}" data-tahun="${h.tahun}"
                            data-harga="${h.harga}" data-kuota="${h.kuota || 0}"
                            data-is-full="${full ? '1' : '0'}"
                            ${sel} ${dis} ${styleAttr}>
                            ${h.bulan_formatted} - ${h.harga_formatted} (Kuota: ${kuotaLabel})${full ? ' ⛔' : ''}
                        </option>`;
                    });

                    hargaPanelHtml = `
                        <div class="keberangkatan-harga-section mt-3 bg-gray-50 rounded-xl p-3 border border-gray-200">
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">
                                <i class="fas fa-tag text-yellow-500 mr-1"></i>
                                Harga diambil dari bulan/tahun:
                            </label>
                            <select class="harga-bulanan-select w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                    data-keberangkatan-id="${k.id}">
                                ${hargaOptions}
                            </select>
                            <div class="flex items-center justify-between mt-1">
                                <p class="text-xs text-gray-400">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Pilih harga dari bulan/tahun berbeda (opsional)
                                </p>
                                <p class="text-xs font-medium kuota-info-${k.id} ${currentKuotaColor}">
                                    <i class="fas fa-users mr-1"></i>
                                    Kuota: <span class="kuota-info-val-${k.id}">${currentKuotaText}</span>
                                </p>
                            </div>
                            <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-200">
                                <p class="text-xs text-gray-500">Harga dipilih:</p>
                                <p class="text-sm font-bold text-green-600 harga-display-${k.id}">
                                    Rp ${parseInt(currentHarga || 0).toLocaleString('id-ID')}
                                </p>
                            </div>
                        </div>
                    `;
                }

                container.innerHTML = `
                    <select id="keberangkatan-select"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                        ${options}
                    </select>
                    <div id="keberangkatan-harga-wrapper" class="mt-3">${hargaPanelHtml}</div>
                `;

                document.getElementById('keberangkatan-select').addEventListener('change', handleKeberangkatanSelectChange);
                var hargaSel = container.querySelector('.harga-bulanan-select');
                if (hargaSel) hargaSel.addEventListener('change', handleHargaBulananChange);

                if (selectedKeberangkatan) {
                    handleKeberangkatanSelectChange({
                        target: {
                            value: selectedKeberangkatan.id
                        }
                    });
                }
            }

            function handleKeberangkatanSelectChange(e) {
                var selectedId = e.target.value;
                var wrapper = document.getElementById('keberangkatan-harga-wrapper');

                if (!selectedId) {
                    wrapper.innerHTML = '';
                    document.getElementById('id_produk_keberangkatan_hidden').value = '';
                    document.getElementById('bulan_keberangkatan_hidden').value = '';
                    document.getElementById('tahun_keberangkatan_hidden').value = '';
                    document.getElementById('id_produk_harga_bulanan_hidden').value = '';
                    document.getElementById('hargaInfo').classList.add('hidden');
                    return;
                }

                var k = globalKeberangkatanList.find(item => parseInt(item.id) === parseInt(selectedId));
                if (!k) {
                    wrapper.innerHTML = '';
                    return;
                }

                document.getElementById('id_produk_keberangkatan_hidden').value = k.id;
                document.getElementById('bulan_keberangkatan_hidden').value = k.bulan;
                document.getElementById('tahun_keberangkatan_hidden').value = k.tahun;
                document.getElementById('id_produk_harga_bulanan_hidden').value = '';
                document.getElementById('bulan_harga_hidden').value = k.bulan;
                document.getElementById('tahun_harga_hidden').value = k.tahun;

                var currentKuotaText = k.kuota_default_formatted;
                var currentKuotaColor = k.kuota_default_is_full ? 'text-red-600' : 'text-blue-600';
                var currentHarga = parseInt(k.harga_default || 0);

                var hargaOptions = '<option value="">-- Sama dengan keberangkatan (' + k.bulan_formatted + ') --</option>';
                globalHargaBulananList.forEach(function(h) {
                    var full = h.is_full ? true : false;
                    var dis = full ? 'disabled' : '';
                    var kuotaLabel = h.is_full ? 'PENUH (' + h.kuota + ')' : h.kuota_formatted;
                    var styleAttr = full ? 'style="color:#ef4444;font-style:italic;"' : '';
                    hargaOptions += `<option value="${h.id}"
                        data-bulan="${h.bulan}" data-tahun="${h.tahun}"
                        data-harga="${h.harga}" data-kuota="${h.kuota || 0}"
                        data-is-full="${full ? '1' : '0'}"
                        ${dis} ${styleAttr}>
                        ${h.bulan_formatted} - ${h.harga_formatted} (Kuota: ${kuotaLabel})${full ? ' ⛔' : ''}
                    </option>`;
                });

                wrapper.innerHTML = `
                    <div class="keberangkatan-harga-section mt-3 bg-gray-50 rounded-xl p-3 border border-gray-200">
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">
                            <i class="fas fa-tag text-yellow-500 mr-1"></i>
                            Harga diambil dari bulan/tahun:
                        </label>
                        <select class="harga-bulanan-select w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                data-keberangkatan-id="${k.id}">
                            ${hargaOptions}
                        </select>
                        <div class="flex items-center justify-between mt-1">
                            <p class="text-xs text-gray-400">
                                <i class="fas fa-info-circle mr-1"></i>
                                Pilih harga dari bulan/tahun berbeda (opsional)
                            </p>
                            <p class="text-xs font-medium kuota-info-${k.id} ${currentKuotaColor}">
                                <i class="fas fa-users mr-1"></i>
                                Kuota: <span class="kuota-info-val-${k.id}">${currentKuotaText}</span>
                            </p>
                        </div>
                        <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-200">
                            <p class="text-xs text-gray-500">Harga dipilih:</p>
                            <p class="text-sm font-bold text-green-600 harga-display-${k.id}">
                                Rp ${currentHarga.toLocaleString('id-ID')}
                            </p>
                        </div>
                    </div>
                `;

                var hargaSel = wrapper.querySelector('.harga-bulanan-select');
                if (hargaSel) hargaSel.addEventListener('change', handleHargaBulananChange);

                updateHargaInfo(k.bulan, k.tahun, currentHarga);
            }

            function handleHargaBulananChange(e) {
                var sel = e.target;
                var wrapper = sel.closest('.keberangkatan-harga-section');
                var selectedOption = sel.options[sel.selectedIndex];
                var hargaBulananId = sel.value;

                var keberangkatanSelect = document.getElementById('keberangkatan-select');
                var keberangkatanId = keberangkatanSelect ? keberangkatanSelect.value : null;
                var k = globalKeberangkatanList.find(item => parseInt(item.id) === parseInt(keberangkatanId));
                if (!k) return;

                var bulanHarga, tahunHarga, harga, kuota, isFull;
                if (hargaBulananId) {
                    bulanHarga = selectedOption.dataset.bulan;
                    tahunHarga = selectedOption.dataset.tahun;
                    harga = selectedOption.dataset.harga;
                    kuota = parseInt(selectedOption.dataset.kuota) || 0;
                    isFull = selectedOption.dataset.isFull === '1';
                } else {
                    bulanHarga = k.bulan;
                    tahunHarga = k.tahun;
                    harga = k.harga_default;
                    kuota = parseInt(k.kuota_default) || 0;
                    isFull = k.kuota_default_is_full;
                }

                if (isFull) {
                    alert('⚠️ Kuota untuk bulan/tahun ini sudah PENUH!\n\nSilakan pilih bulan/tahun lain.');
                    sel.value = '';
                    return;
                }

                document.getElementById('id_produk_harga_bulanan_hidden').value = hargaBulananId || '';
                document.getElementById('bulan_harga_hidden').value = bulanHarga;
                document.getElementById('tahun_harga_hidden').value = tahunHarga;

                var display = wrapper.querySelector('.harga-display-' + k.id);
                if (display) display.textContent = 'Rp ' + parseInt(harga || 0).toLocaleString('id-ID');

                updateHargaInfo(bulanHarga, tahunHarga, harga);

                var kuotaText, kuotaColor;
                if (kuota <= 0) {
                    kuotaText = 'Tidak Terbatas';
                    kuotaColor = 'text-gray-500';
                } else if (isFull) {
                    kuotaText = 'PENUH';
                    kuotaColor = 'text-red-600';
                } else {
                    kuotaText = kuota.toLocaleString('id-ID') + ' Orang';
                    kuotaColor = kuota <= 3 ? 'text-orange-600' : 'text-blue-600';
                }

                var kuotaInfoVal = wrapper.querySelector('.kuota-info-val-' + k.id);
                if (kuotaInfoVal) {
                    kuotaInfoVal.textContent = kuotaText;
                    kuotaInfoVal.className = kuotaColor;
                }
            }

            function updateHargaInfo(bulan, tahun, harga) {
                var bulanName = getBulanName(bulan);
                document.getElementById('hargaBulanTahun').textContent = bulanName + ' ' + tahun;
                document.getElementById('hargaProdukDisplay').textContent = 'Rp ' + parseInt(harga || 0).toLocaleString(
                    'id-ID');
                document.getElementById('hargaInfo').classList.remove('hidden');
            }

            function resetHiddenInputs() {
                document.getElementById('bulan_keberangkatan_hidden').value = '';
                document.getElementById('tahun_keberangkatan_hidden').value = '';
                document.getElementById('id_produk_keberangkatan_hidden').value = '';
                document.getElementById('bulan_harga_hidden').value = '';
                document.getElementById('tahun_harga_hidden').value = '';
                document.getElementById('id_produk_harga_bulanan_hidden').value = '';
                document.getElementById('hargaInfo').classList.add('hidden');
            }

            function getBulanName(bulan) {
                const namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];
                return namaBulan[parseInt(bulan) - 1] || bulan;
            }

            // ==========================================
            // UPGRADE HOTEL KELUARGA
            // ==========================================
            function toggleUpgradeKeluarga() {
                const container = document.getElementById('upgradeKeluargaContainer');
                container.classList.toggle('hidden');
            }

            function toggleHotelDetailKeluarga(hotelId) {
                const checkbox = document.querySelector(
                    `.upgrade-hotel-checkbox[data-scope="keluarga"][data-hotel-id="${hotelId}"]`
                );
                const container = document.getElementById(`hotelDetailContainer-keluarga-${hotelId}`);

                if (!checkbox || !container) return;

                if (checkbox.checked) {
                    container.classList.remove('hidden');
                    loadKamarsUpgrade('keluarga', hotelId, container);
                } else {
                    container.classList.add('hidden');
                    container.innerHTML =
                        '<div class="text-xs text-gray-400 text-center py-2">Pilih hotel untuk memuat kamar</div>';
                    removeUpgradeHiddenInputs('keluarga', hotelId);
                    updateTotalUpgradeKeluarga();
                }
            }

            function updateTotalUpgradeKeluarga() {
                const container = document.getElementById('upgradeKeluargaContainer');
                if (!container) return;

                let total = 0;
                container.querySelectorAll('.hotel-upgrade-item').forEach(hotelItem => {
                    const hotelCheckbox = hotelItem.querySelector('.upgrade-hotel-checkbox');
                    if (!hotelCheckbox || !hotelCheckbox.checked) return;

                    hotelItem.querySelectorAll('.kamar-item').forEach(item => {
                        const cb = item.querySelector('.kamar-checkbox');
                        if (!cb || !cb.checked) return;

                        const jumlah = parseInt(item.querySelector('.kamar-jumlah').value) || 1;
                        const harga = parseInt((item.querySelector('.kamar-harga').value || '')
                            .replace(/^Rp\s*/, '').replace(/\./g, '')) || 0;
                        const durasi = parseInt(item.querySelector('.kamar-durasi').value) || 1;
                        total += jumlah * harga * durasi;
                    });
                });

                const totalEl = document.getElementById('totalUpgradeKeluarga');
                if (totalEl) totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');

                const jumlahJamaah = document.querySelectorAll('.jamaah-row').length;
                const perJamaah = jumlahJamaah > 0 ? Math.round(total / jumlahJamaah) : 0;
                const perEl = document.getElementById('perJamaahUpgradeKeluarga');
                if (perEl) perEl.textContent = 'Rp ' + perJamaah.toLocaleString('id-ID');
            }

            // ==========================================
            // UPGRADE HOTEL JAMAAH
            // ==========================================
            function toggleUpgradeJamaah(index) {
                const container = document.getElementById('upgradeJamaahContainer-' + index);
                if (container) container.classList.toggle('hidden');
            }

            function toggleHotelDetailJamaah(jamaahIndex, hotelId) {
                const checkbox = document.querySelector(
                    `.upgrade-hotel-checkbox[data-scope="jamaah-${jamaahIndex}"][data-hotel-id="${hotelId}"]`
                );
                const container = document.getElementById(`hotelDetailContainer-${jamaahIndex}-${hotelId}`);

                if (!checkbox || !container) return;

                if (checkbox.checked) {
                    container.classList.remove('hidden');
                    loadKamarsUpgrade('jamaah-' + jamaahIndex, hotelId, container);
                } else {
                    container.classList.add('hidden');
                    container.innerHTML =
                        '<div class="text-xs text-gray-400 text-center py-2">Pilih hotel untuk memuat kamar</div>';
                    removeUpgradeHiddenInputs('jamaah-' + jamaahIndex, hotelId);
                    updateTotalUpgradeJamaah(jamaahIndex);
                }
            }

            function updateTotalUpgradeJamaah(jamaahIndex) {
                const container = document.getElementById('upgradeJamaahContainer-' + jamaahIndex);
                if (!container) return;

                let total = 0;
                container.querySelectorAll('.hotel-upgrade-item').forEach(hotelItem => {
                    const hotelCheckbox = hotelItem.querySelector('.upgrade-hotel-checkbox');
                    if (!hotelCheckbox || !hotelCheckbox.checked) return;

                    hotelItem.querySelectorAll('.kamar-item').forEach(item => {
                        const cb = item.querySelector('.kamar-checkbox');
                        if (!cb || !cb.checked) return;

                        const jumlah = parseInt(item.querySelector('.kamar-jumlah').value) || 1;
                        const harga = parseInt((item.querySelector('.kamar-harga').value || '')
                            .replace(/^Rp\s*/, '').replace(/\./g, '')) || 0;
                        const durasi = parseInt(item.querySelector('.kamar-durasi').value) || 1;
                        total += jumlah * harga * durasi;
                    });
                });

                const totalEl = document.getElementById('totalUpgradeJamaah-' + jamaahIndex);
                if (totalEl) totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
            }

            // ==========================================
            // LOAD KAMARS (AJAX) — TANPA PARTIAL
            // ==========================================
            function loadKamarsUpgrade(scope, hotelId, container) {
                container.innerHTML =
                    '<div class="text-xs text-gray-400 text-center py-2"><i class="fas fa-spinner fa-spin mr-1"></i> Memuat tipe kamar...</div>';

                fetch(`{{ url('/transaksional/keluarga/get-kamars-by-hotel') }}/${hotelId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.count === 0) {
                            container.innerHTML =
                                '<div class="text-xs text-gray-400 text-center py-2">Hotel ini belum memiliki data kamar.</div>';
                            return;
                        }

                        container.innerHTML = '<div class="grid grid-cols-1 gap-2">' + data.html + '</div>' +
                            `<div class="mt-1 flex items-center justify-between bg-orange-50 rounded px-2 py-1">
                                <span class="text-[10px] font-medium text-gray-600">Subtotal hotel ini:</span>
                                <span class="text-xs font-bold text-orange-600 subtotal-hotel-${scope}-${hotelId}">Rp 0</span>
                            </div>`;

                        container.querySelectorAll('.kamar-checkbox').forEach(cb => {
                            cb.addEventListener('change', () => {
                                const parent = cb.closest('.kamar-item');
                                const inputs = parent.querySelectorAll('input:not(.kamar-checkbox)');
                                inputs.forEach(input => {
                                    input.disabled = !cb.checked;
                                    input.classList.toggle('opacity-50', !cb.checked);
                                    input.classList.toggle('bg-gray-50', !cb.checked);
                                });
                                updateUpgradeHiddenInputs(scope, hotelId, container);
                                recalcUpgradeScope(scope);
                            });
                        });

                        container.querySelectorAll('.kamar-jumlah, .kamar-harga, .kamar-durasi').forEach(input => {
                            input.addEventListener('change', () => {
                                updateUpgradeHiddenInputs(scope, hotelId, container);
                                recalcUpgradeScope(scope);
                            });
                            input.addEventListener('keyup', () => {
                                updateUpgradeHiddenInputs(scope, hotelId, container);
                                recalcUpgradeScope(scope);
                            });
                        });

                        // Auto-load existing upgrades (edit mode only, tapi aman untuk create)
                        if (typeof restoreExistingUpgrades === 'function') {
                            restoreExistingUpgrades(scope, hotelId, container);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        container.innerHTML =
                        '<div class="text-xs text-red-500 text-center py-2">Gagal memuat kamar.</div>';
                    });
            }

            function recalcUpgradeScope(scope) {
                if (scope === 'keluarga') {
                    updateTotalUpgradeKeluarga();
                } else if (scope.startsWith('jamaah-')) {
                    const idx = parseInt(scope.replace('jamaah-', ''));
                    updateTotalUpgradeJamaah(idx);
                }
            }

            // ==========================================
            // HIDDEN INPUTS UPGRADE
            // ==========================================
            function updateUpgradeHiddenInputs(scope, hotelId, container) {
                removeUpgradeHiddenInputs(scope, hotelId);

                let parentForm = null;
                let fieldPrefix = '';

                if (scope === 'keluarga') {
                    parentForm = document.getElementById('keluargaForm');
                    fieldPrefix = `upgrade_hotels`;
                } else {
                    const idx = parseInt(scope.replace('jamaah-', ''));
                    parentForm = document.getElementById('jamaah-row-' + idx);
                    fieldPrefix = `jamaahs[${idx}][upgrade_hotels]`;
                }

                if (!parentForm) return;

                let subtotal = 0;

                container.querySelectorAll('.kamar-item').forEach(item => {
                    const cb = item.querySelector('.kamar-checkbox');
                    if (!cb || !cb.checked) return;

                    const jumlah = parseInt(item.querySelector('.kamar-jumlah').value) || 1;
                    const harga = parseInt((item.querySelector('.kamar-harga').value || '')
                        .replace(/^Rp\s*/, '').replace(/\./g, '')) || 0;
                    const durasi = parseInt(item.querySelector('.kamar-durasi').value) || 1;
                    subtotal += jumlah * harga * durasi;

                    // Unique key per kamar
                    const uniqueKey = `${fieldPrefix}[${hotelId}_${cb.value}]`;
                    const grp = document.createElement('div');
                    grp.className = 'upgrade-hidden-group';
                    grp.dataset.scope = scope;
                    grp.dataset.hotelId = hotelId;
                    grp.innerHTML = `
                        <input type="hidden" name="${uniqueKey}[id_hotel]" value="${hotelId}">
                        <input type="hidden" name="${uniqueKey}[id_kamar]" value="${cb.value}">
                        <input type="hidden" name="${uniqueKey}[tipe_kamar]" value="${cb.dataset.tipe}">
                        <input type="hidden" name="${uniqueKey}[jumlah_kamar]" value="${jumlah}">
                        <input type="hidden" name="${uniqueKey}[durasi_menginap]" value="${durasi}">
                        <input type="hidden" name="${uniqueKey}[harga_per_malam]" value="${harga}">
                    `;
                    parentForm.appendChild(grp);
                });

                const subtotalEl = container.querySelector(`.subtotal-hotel-${scope}-${hotelId}`);
                if (subtotalEl) subtotalEl.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            }

            function removeUpgradeHiddenInputs(scope, hotelId) {
                let parentForm = null;
                if (scope === 'keluarga') {
                    parentForm = document.getElementById('keluargaForm');
                } else {
                    const idx = parseInt(scope.replace('jamaah-', ''));
                    parentForm = document.getElementById('jamaah-row-' + idx);
                }
                if (!parentForm) return;
                parentForm.querySelectorAll(`.upgrade-hidden-group[data-scope="${scope}"][data-hotel-id="${hotelId}"]`)
                    .forEach(el => el.remove());
            }

            // ==========================================
            // ADD / REMOVE JAMAAH
            // ==========================================
            function addJamaah() {
                const container = document.getElementById('jamaahContainer');
                const row = document.createElement('div');
                row.className = 'jamaah-row bg-gray-50 rounded-xl p-4 mb-3 border border-gray-200';
                row.id = 'jamaah-row-' + jamaahIndex;

                let hotelUpgradeOptions = '';
                @foreach ($hotelOptions as $hotel)
                    hotelUpgradeOptions += `
                        <div class="hotel-upgrade-item border border-gray-200 rounded-lg"
                            data-hotel-id="{{ $hotel->id_hotel }}"
                            data-scope="jamaah-\${jamaahIndex}">
                            <label class="flex items-center gap-2 p-2 cursor-pointer hover:bg-gray-50 rounded-t-lg">
                                <input type="checkbox"
                                    class="upgrade-hotel-checkbox w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500"
                                    data-hotel-id="{{ $hotel->id_hotel }}"
                                    data-scope="jamaah-\${jamaahIndex}"
                                    onchange="toggleHotelDetailJamaah(\${jamaahIndex}, {{ $hotel->id_hotel }})">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700">{{ $hotel->nama_hotel }}</p>
                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ $hotel->kota ?? '-' }}
                                        @if ($hotel->bintang) · {{ $hotel->bintang_text }} @endif
                                    </p>
                                </div>
                            </label>
                            <div id="hotelDetailContainer-\${jamaahIndex}-{{ $hotel->id_hotel }}"
                                class="hidden border-t border-gray-100 p-2 space-y-2">
                                <div class="text-xs text-gray-400 text-center py-2">
                                    <i class="fas fa-spinner fa-spin mr-1"></i> Memuat tipe kamar...
                                </div>
                            </div>
                        </div>
                    `;
                @endforeach

                row.innerHTML = `
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium text-gray-700">Jamaah #\${jamaahIndex + 1}</span>
                        <button type="button" onclick="removeJamaah(this)" class="text-red-500 hover:text-red-700 text-sm">
                            <i class="fas fa-times"></i> Hapus
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="jamaahs[\${jamaahIndex}][nama_lengkap]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">NIK <span class="text-red-500">*</span></label>
                            <input type="text" name="jamaahs[\${jamaahIndex}][nik]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                placeholder="16 digit NIK" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Nama Ayah <span class="text-red-500">*</span></label>
                            <input type="text" name="jamaahs[\${jamaahIndex}][nama_ayah]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Pekerjaan <span class="text-red-500">*</span></label>
                            <input type="text" name="jamaahs[\${jamaahIndex}][pekerjaan]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="jamaahs[\${jamaahIndex}][jenis_kelamin]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required>
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Telepon <span class="text-red-500">*</span></label>
                            <input type="text" name="jamaahs[\${jamaahIndex}][telepon]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">WhatsApp <span class="text-red-500">*</span></label>
                            <input type="text" name="jamaahs[\${jamaahIndex}][wa]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                placeholder="08xxxxxxxxxx" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                            <input type="text" name="jamaahs[\${jamaahIndex}][tempat_lahir]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                            <input type="date" name="jamaahs[\${jamaahIndex}][tanggal_lahir]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <h6 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-map-marker-alt text-yellow-500 mr-1.5"></i>
                            Alamat Domisili
                        </h6>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Hubungan Keluarga</label>
                                <select name="jamaahs[\${jamaahIndex}][hubungan_keluarga]"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                    <option value="">-- Pilih Hubungan --</option>
                                    @foreach ($hubunganOptions as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Alamat</label>
                                <input type="text" name="jamaahs[\${jamaahIndex}][alamat]"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                    placeholder="Jl. / RT / RW / No.">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Kota / Kabupaten</label>
                                <input type="text" name="jamaahs[\${jamaahIndex}][kota_kabupaten]"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                    placeholder="Kota/Kabupaten">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Kecamatan</label>
                                <input type="text" name="jamaahs[\${jamaahIndex}][kecamatan]"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                    placeholder="Kecamatan">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Kelurahan / Desa</label>
                                <input type="text" name="jamaahs[\${jamaahIndex}][kelurahan]"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                    placeholder="Kelurahan/Desa">
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <div class="bg-blue-50 rounded-lg p-3 border border-blue-100">
                            <h6 class="text-xs font-semibold text-blue-700 mb-2 flex items-center">
                                <i class="fas fa-plane text-blue-500 mr-1.5"></i>
                                Keberangkatan
                            </h6>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Kota Asal</label>
                                    <select name="jamaahs[\${jamaahIndex}][kota_asal]"
                                        class="kota-asal-select w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm bg-white">
                                        <option value="">-- Pilih Kota Asal --</option>
                                        @foreach ($kotaAsals as $kota)
                                            <option value="{{ $kota->nama_kota }}" data-pulau="{{ $kota->pulau }}"
                                                data-bandara="{{ $kota->bandara_terdekat }}">
                                                {{ $kota->nama_kota }} - {{ $kota->provinsi ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Pulau</label>
                                    <input type="text" name="jamaahs[\${jamaahIndex}][pulau]"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm cursor-not-allowed" readonly placeholder="Otomatis">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Bandara Keberangkatan</label>
                                    <input type="text" name="jamaahs[\${jamaahIndex}][bandara_keberangkatan]"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm cursor-not-allowed" readonly placeholder="Otomatis">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <h6 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-passport text-yellow-500 mr-1.5"></i>
                            Passport & Dokumen
                        </h6>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Nomor Paspor</label>
                                <input type="text" name="jamaahs[\${jamaahIndex}][nomor_paspor]"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                            </div>
                            <div>
                                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer mt-6">
                                    <input type="checkbox" name="jamaahs[\${jamaahIndex}][is_kepala_keluarga]" value="1"
                                        class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                                    <span>Kepala Keluarga / Kelompok</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <div class="mb-3">
                                <label class="block text-xs font-medium text-gray-600 mb-1.5">
                                    Apakah ada Pembuatan Passport? <span class="text-red-500">*</span>
                                </label>

                                <div class="inline-flex rounded-lg overflow-hidden border border-gray-300">
                                    <button type="button" onclick="setPembuatanPassportKeluarga(\${jamaahIndex}, 1)"
                                        id="btnYa-\${jamaahIndex}"
                                        class="px-6 py-2 text-sm font-medium bg-white text-gray-600 hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-check mr-1"></i> YA
                                    </button>
                                    <button type="button" onclick="setPembuatanPassportKeluarga(\${jamaahIndex}, 0)"
                                        id="btnTidak-\${jamaahIndex}"
                                        class="px-6 py-2 text-sm font-medium bg-red-500 text-white transition-colors border-l border-gray-300">
                                        <i class="fas fa-times mr-1"></i> TIDAK
                                    </button>
                                </div>
                                <input type="hidden" name="jamaahs[\${jamaahIndex}][is_pembuatan_passport]"
                                    id="is_pembuatan_passport-\${jamaahIndex}" value="0">

                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Pilih <b>YA</b> jika ada pendapatan perusahaan. Pilih <b>TIDAK</b> jika jamaah sudah punya passport.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Pembayaran Passport <span class="text-gray-400 text-xs">(boleh kosong)</span></label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                        <input type="text" name="jamaahs[\${jamaahIndex}][pendampingan_fee]" value="0"
                                            oninput="formatRupiah(this); updateTotalPendampingan(\${jamaahIndex})"
                                            onfocus="handleZeroFocus(this)" onblur="handleZeroBlur(this)"
                                            class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                            placeholder="0">
                                    </div>
                                </div>
                                <div id="feePetugasContainer-\${jamaahIndex}" class="hidden">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Pendapatan Perusahaan <span class="text-gray-400 text-xs">(fee petugas)</span></label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                        <input type="text" name="jamaahs[\${jamaahIndex}][pendampingan_fee_petugas]"
                                            id="pendampingan_fee_petugas-\${jamaahIndex}" value="0"
                                            oninput="formatRupiah(this); updateTotalPendampingan(\${jamaahIndex})"
                                            onfocus="handleZeroFocus(this)" onblur="handleZeroBlur(this)"
                                            class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                            placeholder="0">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-2 text-xs text-gray-500">
                                Total Pembuatan Passport:
                                <span class="font-bold text-blue-600" id="total-pendampingan-\${jamaahIndex}">Rp 0</span>
                                <span class="text-gray-400">(Pembayaran + Pendapatan Perusahaan)</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <h6 class="text-xs font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-hotel text-orange-500 mr-1.5"></i>
                                Upgrade Hotel & Kamar Jamaah
                                <span class="ml-2 text-[10px] text-gray-400 font-normal">(opsional · khusus jamaah ini)</span>
                            </h6>
                            <button type="button" onclick="toggleUpgradeJamaah(\${jamaahIndex})"
                                class="text-xs text-blue-500 hover:text-blue-700 font-medium">
                                <i class="fas fa-plus mr-1"></i> Atur Hotel
                            </button>
                        </div>

                        <div id="upgradeJamaahContainer-\${jamaahIndex}"
                            class="hidden bg-orange-50 rounded-lg p-3 border border-orange-200">
                            <p class="text-xs text-orange-700 mb-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Biaya upgrade ini hanya dibebankan ke jamaah ini.
                            </p>

                            <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg bg-white p-2 space-y-2">
                                \${hotelUpgradeOptions}
                            </div>

                            <div class="mt-2 flex items-center justify-between bg-white rounded-lg border border-orange-200 px-3 py-2">
                                <span class="text-xs font-medium text-gray-700">
                                    <i class="fas fa-calculator mr-1 text-orange-500"></i>
                                    Total Upgrade Jamaah:
                                </span>
                                <span id="totalUpgradeJamaah-\${jamaahIndex}" class="text-sm font-bold text-orange-600">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-3 pt-3 border-t border-gray-200">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">File KTP/KK</label>
                            <input type="file" name="jamaahs[\${jamaahIndex}][file_ktp_kk]" accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                            <p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">File Vaksin</label>
                            <input type="file" name="jamaahs[\${jamaahIndex}][file_vaksin]" accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                            <p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">File Visa</label>
                            <input type="file" name="jamaahs[\${jamaahIndex}][file_visa]" accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                            <p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">File Paspor</label>
                            <input type="file" name="jamaahs[\${jamaahIndex}][file_paspor]" accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                            <p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>
                        </div>
                    </div>
                `;
                container.appendChild(row);
                jamaahIndex++;
                hitungDiskonKelompok();
                updateTotalUpgradeKeluarga();
            }

            function removeJamaah(button) {
                const row = button.closest('.jamaah-row');
                const container = document.getElementById('jamaahContainer');
                if (container.children.length > 1) {
                    row.remove();
                    const rows = container.querySelectorAll('.jamaah-row');
                    rows.forEach((r, idx) => {
                        const label = r.querySelector('.text-sm.font-medium');
                        if (label) label.textContent = `Jamaah #${idx + 1}`;
                    });
                    hitungDiskonKelompok();
                    updateTotalUpgradeKeluarga();
                } else {
                    alert('Minimal harus ada 1 jamaah!');
                }
            }

            // ==========================================
            // EVENT LISTENERS
            // ==========================================
            document.getElementById('filter-tahun').addEventListener('change', function() {
                renderKeberangkatanByTahun(this.value);
            });
            document.getElementById('produk_paket').addEventListener('change', loadKeberangkatan);
            document.getElementById('id_diskon').addEventListener('change', hitungDiskonKelompok);

            document.addEventListener('change', function(e) {
                if (e.target && e.target.classList.contains('kota-asal-select')) {
                    const selectedOption = e.target.options[e.target.selectedIndex];
                    const row = e.target.closest('.jamaah-row');
                    if (row) {
                        const pulauInput = row.querySelector('input[name$="[pulau]"]');
                        const bandaraInput = row.querySelector('input[name$="[bandara_keberangkatan]"]');
                        if (pulauInput) pulauInput.value = selectedOption.dataset.pulau || '';
                        if (bandaraInput) bandaraInput.value = selectedOption.dataset.bandara || '';
                    }
                }
            });

            document.addEventListener('input', function(e) {
                if (e.target && e.target.name && e.target.name.includes('pendampingan_fee')) {
                    const index = e.target.name.match(/\d+/)[0];
                    updateTotalPendampingan(parseInt(index));
                }
            });

            // ==========================================
            // DOM READY
            // ==========================================
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.kota-asal-select').forEach(select => {
                    if (select.value) select.dispatchEvent(new Event('change'));
                });
                document.querySelectorAll('.jamaah-row').forEach((row, index) => {
                    updateTotalPendampingan(index);
                });

                var produkPaket = document.getElementById('produk_paket');
                if (produkPaket && produkPaket.value) loadKeberangkatan();

                hitungDiskonKelompok();

                const form = document.getElementById('keluargaForm');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        if (!validateJamaahForm()) {
                            e.preventDefault();
                            return false;
                        }

                        ['fee_agent'].forEach(id => {
                            const input = document.getElementById(id);
                            if (input) input.value = input.value.replace(/\./g, '');
                        });
                        document.querySelectorAll(
                            '[name$="[pendampingan_fee]"], [name$="[pendampingan_fee_petugas]"]').forEach(
                            input => {
                                if (input.value) input.value = input.value.replace(/\./g, '');
                            });

                        var selectKeberangkatan = document.getElementById('keberangkatan-select');
                        if (selectKeberangkatan && selectKeberangkatan.value) {
                            var k = globalKeberangkatanList.find(item => parseInt(item.id) === parseInt(
                                selectKeberangkatan.value));
                            var hargaSel = document.querySelector('.harga-bulanan-select');
                            var selectedHargaFull = false;
                            if (hargaSel && hargaSel.value) {
                                var opt = hargaSel.options[hargaSel.selectedIndex];
                                selectedHargaFull = opt.dataset.isFull === '1';
                            }
                            if ((k && k.kuota_default_is_full) || selectedHargaFull) {
                                e.preventDefault();
                                alert('⚠️ Kuota bulan/tahun yang dipilih sudah PENUH!');
                                return false;
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
