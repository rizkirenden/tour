{{-- blade-formatter-disable --}}
@extends('layouts.app')

@section('title', 'Edit Keluarga / Kelompok - Arrum Tour')
@section('page-title', 'Edit Keluarga / Kelompok')

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
        <span class="text-gray-500 font-medium">Edit</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Form Edit Keluarga / Kelompok</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Edit data keluarga / kelompok dan jamaah</p>
                </div>
                <a href="{{ route('transaksional.keluarga.show', $keluarga->id_keluarga) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <i class="fas fa-eye mr-2"></i> Lihat Detail
                </a>
            </div>

            <form action="{{ route('transaksional.keluarga.update', $keluarga->id_keluarga) }}" method="POST"
                id="keluargaForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <!-- Informasi Keluarga / Kelompok -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-users text-yellow-500 mr-2"></i> Informasi Keluarga / Kelompok
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kode Keluarga / Kelompok</label>
                                <input type="text" value="{{ $keluarga->kode_keluarga }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl bg-gray-100 text-sm cursor-not-allowed"
                                    readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Keluarga / Kelompok <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_keluarga"
                                    value="{{ old('nama_keluarga', $keluarga->nama_keluarga) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Contoh: Keluarga Bapak Ahmad" required>
                                @error('nama_keluarga')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
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
                                            {{ old('produk_paket', $keluarga->produk_paket) == $produk->nama_produk ? 'selected' : '' }}>
                                            {{ $produk->nama_produk }} ({{ $produk->durasi_hari }} Hari)
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
                                class="bg-gray-50 rounded-xl p-4 border border-gray-200 text-center hidden">
                                <p class="text-sm text-gray-400">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Pilih produk paket terlebih dahulu
                                </p>
                            </div>

                            <div id="step-1-container" class="{{ $keluarga->produk_paket ? '' : 'hidden' }}">
                                <label class="block text-xs font-medium text-gray-600 mb-1.5">
                                    <i class="fas fa-calendar text-yellow-500 mr-1"></i>
                                    Langkah 1: Pilih Tahun Keberangkatan
                                </label>
                                <select id="filter-tahun"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                    <option value="">-- Memuat... --</option>
                                </select>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Pilih tahun terlebih dahulu untuk melihat bulan keberangkatan yang tersedia
                                </p>
                            </div>

                            <div id="step-2-container" class="hidden mt-3">
                                <label class="block text-xs font-medium text-gray-600 mb-1.5">
                                    <i class="fas fa-plane text-yellow-500 mr-1"></i>
                                    Langkah 2: Pilih Bulan Keberangkatan
                                </label>
                                <div id="keberangkatan-container" class="space-y-3">
                                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 text-center">
                                        <p class="text-sm text-gray-400">
                                            <i class="fas fa-spinner fa-spin mr-1"></i> Memuat...
                                        </p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Pilih salah satu keberangkatan. Harga bisa diambil dari bulan/tahun berbeda.
                                </p>
                            </div>

                            {{-- Hidden input statis (backward compat) --}}
                            <input type="hidden" name="bulan_keberangkatan" id="bulan_keberangkatan_hidden"
                                value="{{ old('bulan_keberangkatan', $keluarga->bulan_keberangkatan) }}">
                            <input type="hidden" name="tahun_keberangkatan" id="tahun_keberangkatan_hidden"
                                value="{{ old('tahun_keberangkatan', $keluarga->tahun_keberangkatan) }}">
                            <input type="hidden" name="id_produk_keberangkatan" id="id_produk_keberangkatan_hidden"
                                value="{{ old('id_produk_keberangkatan', $keluarga->id_produk_keberangkatan) }}">
                            <input type="hidden" name="bulan_harga" id="bulan_harga_hidden"
                                value="{{ old('bulan_harga', $keluarga->bulan_harga) }}">
                            <input type="hidden" name="tahun_harga" id="tahun_harga_hidden"
                                value="{{ old('tahun_harga', $keluarga->tahun_harga) }}">
                            <input type="hidden" name="id_produk_harga_bulanan" id="id_produk_harga_bulanan_hidden"
                                value="{{ old('id_produk_harga_bulanan', $keluarga->id_produk_harga_bulanan) }}">

                            {{-- ✅ Wrapper untuk hidden input DINAMIS keberangkatan_data --}}
                            <div id="keberangkatan-data-hidden-wrapper"></div>

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
                                    <input type="text" name="agent" value="{{ old('agent', $keluarga->agent) }}"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                        placeholder="Nama Agent (opsional)">
                                    @error('agent')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Fee Agent Total Keluarga
                                    </label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                        <input type="text" name="fee_agent" id="fee_agent"
                                            value="{{ old('fee_agent', number_format($keluarga->fee_agent, 0, ',', '.')) }}"
                                            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                            placeholder="0" oninput="formatRupiah(this); updateFeeAgentPerJamaah();"
                                            onfocus="handleZeroFocus(this)" onblur="handleZeroBlur(this)">
                                    </div>
                                    @error('fee_agent')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div id="feeAgentPerJamaahInfo"
                                class="mt-3 bg-white rounded-lg p-3 border border-blue-200 hidden">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                                    <div>
                                        <span class="text-gray-500 text-xs">Total Fee Agent</span>
                                        <p class="font-bold text-blue-700" id="infoTotalFeeAgent">Rp 0</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 text-xs">Jumlah Jamaah</span>
                                        <p class="font-bold text-blue-700" id="infoJumlahJamaahFee">0</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 text-xs">Fee per Orang</span>
                                        <p class="font-bold text-green-700" id="infoFeeAgentPerOrang">Rp 0</p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Fee agent akan dibagi rata ke setiap jamaah.
                                </p>
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
                                            {{ old('id_diskon', $keluarga->id_diskon) == $diskon->id_diskon ? 'selected' : '' }}>
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
                                <input type="text" name="keterangan_diskon"
                                    value="{{ old('keterangan_diskon', $keluarga->keterangan_diskon) }}"
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
                                    @php
                                        $existingUpgrades = $keluarga->upgradeHotels->where(
                                            'id_hotel',
                                            $hotel->id_hotel,
                                        );
                                        $isChecked = $existingUpgrades->count() > 0;
                                    @endphp
                                    <div class="hotel-upgrade-item border border-gray-200 rounded-lg"
                                        data-hotel-id="{{ $hotel->id_hotel }}" data-scope="keluarga">
                                        <label
                                            class="flex items-center gap-2 p-2 cursor-pointer hover:bg-gray-50 rounded-t-lg">
                                            <input type="checkbox"
                                                class="upgrade-hotel-checkbox w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500"
                                                data-hotel-id="{{ $hotel->id_hotel }}" data-scope="keluarga"
                                                onchange="toggleHotelDetailKeluarga({{ $hotel->id_hotel }})"
                                                {{ $isChecked ? 'checked' : '' }}>
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
                                            class="{{ $isChecked ? '' : 'hidden' }} border-t border-gray-100 p-2 space-y-2">
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
                                <span id="totalUpgradeKeluarga"
                                    class="text-sm font-bold text-orange-600">{{ $keluarga->total_upgrade_hotel_formatted }}</span>
                            </div>

                            <div class="mt-1 text-xs text-gray-500 text-right">
                                Per Jamaah: <span id="perJamaahUpgradeKeluarga"
                                    class="font-medium text-orange-600">{{ $keluarga->upgrade_hotel_per_jamaah_formatted }}</span>
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
                        </div>

                        <div id="jamaahError" class="hidden mb-3 bg-red-50 border border-red-200 rounded-xl p-3">
                            <p class="text-sm text-red-600 flex items-start">
                                <i class="fas fa-exclamation-triangle mt-0.5 mr-2"></i>
                                <span id="jamaahErrorText">Lengkapi semua data jamaah yang wajib diisi.</span>
                            </p>
                        </div>

                        <div id="jamaahContainer">
                            @foreach ($jamaahData as $index => $jamaah)
                                @php
                                    $jamaahUpgradeList = $jamaah['upgrade_hotels'] ?? [];
                                    $isPassport = ($jamaah['is_pembuatan_passport'] ?? 0) == 1;
                                    $jamaahUpgradeTotal = $jamaah['upgrade_hotels'] ?? [];
                                    $totalJamaahUp = 0;
                                    foreach ($jamaahUpgradeTotal as $up) {
                                        $totalJamaahUp +=
                                            (int) ($up['harga_per_pax'] ?? 0) * (int) ($up['jumlah_pax'] ?? 1);
                                    }
                                @endphp
                                <div class="jamaah-row bg-gray-50 rounded-xl p-4 mb-3 border border-gray-200"
                                    id="jamaah-row-{{ $index }}">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-sm font-medium text-gray-700">Jamaah #{{ $index + 1 }}</span>
                                        <button type="button" onclick="removeJamaah(this)"
                                            class="text-red-500 hover:text-red-700 text-sm">
                                            <i class="fas fa-times"></i> Hapus
                                        </button>
                                    </div>
                                    <input type="hidden" name="jamaahs[{{ $index }}][id_jamaah]"
                                        value="{{ $jamaah['id_jamaah'] ?? '' }}">

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
                                                Status Kawin <span class="text-red-500">*</span>
                                            </label>
                                            <select name="jamaahs[{{ $index }}][status_kawin]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                                required>
                                                <option value="">-- Pilih --</option>
                                                <option value="Belum Kawin"
                                                    {{ ($jamaah['status_kawin'] ?? '') == 'Belum Kawin' ? 'selected' : '' }}>
                                                    Belum Kawin</option>
                                                <option value="Kawin"
                                                    {{ ($jamaah['status_kawin'] ?? '') == 'Kawin' ? 'selected' : '' }}>
                                                    Kawin</option>
                                                <option value="Cerai Hidup"
                                                    {{ ($jamaah['status_kawin'] ?? '') == 'Cerai Hidup' ? 'selected' : '' }}>
                                                    Cerai Hidup</option>
                                                <option value="Cerai Mati"
                                                    {{ ($jamaah['status_kawin'] ?? '') == 'Cerai Mati' ? 'selected' : '' }}>
                                                    Cerai Mati</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
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
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                Tanggal Lahir
                                            </label>
                                            <input type="date" name="jamaahs[{{ $index }}][tanggal_lahir]"
                                                value="{{ $jamaah['tanggal_lahir'] ?? '' }}"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                        </div>
                                    </div>

                                    {{-- ALAMAT DOMISILI --}}
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
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Alamat</label>
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
                                                <input type="text" name="jamaahs[{{ $index }}][kota_kabupaten]"
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
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Kelurahan /
                                                    Desa</label>
                                                <input type="text" name="jamaahs[{{ $index }}][kelurahan]"
                                                    value="{{ $jamaah['kelurahan'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                    placeholder="Kelurahan/Desa">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- KEBERANGKATAN --}}
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

                                    {{-- PASSPORT & DOKUMEN --}}
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <h6 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                                            <i class="fas fa-passport text-yellow-500 mr-1.5"></i>
                                            Passport & Dokumen
                                        </h6>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Nomor
                                                    Paspor</label>
                                                <input type="text" name="jamaahs[{{ $index }}][nomor_paspor]"
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

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                                    Tanggal Terbit Paspor
                                                </label>
                                                <input type="date" name="jamaahs[{{ $index }}][paspor_terbit]"
                                                    value="{{ $jamaah['paspor_terbit'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                                    Tanggal Berakhir Paspor
                                                </label>
                                                <input type="date" name="jamaahs[{{ $index }}][paspor_expired]"
                                                    value="{{ $jamaah['paspor_expired'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                                    Diterbitkan Di
                                                </label>
                                                <input type="text"
                                                    name="jamaahs[{{ $index }}][paspor_diterbitkan_di]"
                                                    value="{{ $jamaah['paspor_diterbitkan_di'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                    placeholder="Kota/Negara">
                                            </div>
                                        </div>

                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <div class="mb-3">
                                                <label class="block text-xs font-medium text-gray-600 mb-1.5">
                                                    Apakah ada Pembuatan Passport? <span class="text-red-500">*</span>
                                                </label>

                                                <div class="inline-flex rounded-lg overflow-hidden border border-gray-300">
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
                                                    Pilih <b>YA</b> jika ada pendapatan perusahaan. Pilih <b>TIDAK</b>
                                                    jika jamaah sudah punya passport.
                                                </p>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                                        Pembayaran Passport
                                                        <span class="text-gray-400 text-xs">(boleh kosong)</span>
                                                    </label>
                                                    <div class="relative">
                                                        <span
                                                            class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                                        <input type="text"
                                                            name="jamaahs[{{ $index }}][pendampingan_fee]"
                                                            value="{{ isset($jamaah['pendampingan_fee']) ? number_format($jamaah['pendampingan_fee'], 0, ',', '.') : '0' }}"
                                                            oninput="formatRupiah(this); updateTotalPendampingan({{ $index }})"
                                                            onfocus="handleZeroFocus(this)" onblur="handleZeroBlur(this)"
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
                                                            value="{{ isset($jamaah['pendampingan_fee_petugas']) ? number_format($jamaah['pendampingan_fee_petugas'], 0, ',', '.') : '0' }}"
                                                            oninput="formatRupiah(this); updateTotalPendampingan({{ $index }})"
                                                            onfocus="handleZeroFocus(this)" onblur="handleZeroBlur(this)"
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

                                    {{-- UPGRADE HOTEL JAMAAH --}}
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <h6 class="text-xs font-semibold text-gray-700 flex items-center">
                                                <i class="fas fa-hotel text-orange-500 mr-1.5"></i>
                                                Upgrade Hotel & Kamar Jamaah
                                                <span class="ml-2 text-[10px] text-gray-400 font-normal">(opsional
                                                    · khusus jamaah ini)</span>
                                            </h6>
                                            <button type="button" onclick="toggleUpgradeJamaah({{ $index }})"
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
                                                    @php
                                                        $jamaahUpgrades = collect($jamaahUpgradeList)->where(
                                                            'id_hotel',
                                                            $hotel->id_hotel,
                                                        );
                                                        $isCheckedJamaah = $jamaahUpgrades->count() > 0;
                                                    @endphp
                                                    <div class="hotel-upgrade-item border border-gray-200 rounded-lg"
                                                        data-hotel-id="{{ $hotel->id_hotel }}"
                                                        data-scope="jamaah-{{ $index }}">
                                                        <label
                                                            class="flex items-center gap-2 p-2 cursor-pointer hover:bg-gray-50 rounded-t-lg">
                                                            <input type="checkbox"
                                                                class="upgrade-hotel-checkbox w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500"
                                                                data-hotel-id="{{ $hotel->id_hotel }}"
                                                                data-scope="jamaah-{{ $index }}"
                                                                onchange="toggleHotelDetailJamaah({{ $index }}, {{ $hotel->id_hotel }})"
                                                                {{ $isCheckedJamaah ? 'checked' : '' }}>
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
                                                            class="{{ $isCheckedJamaah ? '' : 'hidden' }} border-t border-gray-100 p-2 space-y-2">
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
                                                    class="text-sm font-bold text-orange-600">
                                                    Rp {{ number_format($totalJamaahUp, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- File Upload -->
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-3 pt-3 border-t border-gray-200">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">File KTP/KK</label>
                                            @if (!empty($jamaah['file_ktp_kk']) && Storage::disk('public')->exists($jamaah['file_ktp_kk']))
                                                <div class="mb-1">
                                                    @php $ext = strtolower(pathinfo($jamaah['file_ktp_kk'], PATHINFO_EXTENSION)); @endphp
                                                    @if ($ext == 'pdf')
                                                        <i class="fas fa-file-pdf text-2xl text-red-500"></i>
                                                    @else
                                                        <img src="{{ Storage::url($jamaah['file_ktp_kk']) }}"
                                                            alt="KTP"
                                                            class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                                    @endif
                                                    <p class="text-xs text-gray-400">File saat ini</p>
                                                </div>
                                            @endif
                                            <input type="file" name="jamaahs[{{ $index }}][file_ktp_kk]"
                                                accept=".pdf,.jpg,.jpeg,.png"
                                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                            <p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">File Vaksin</label>
                                            @if (!empty($jamaah['file_vaksin']) && Storage::disk('public')->exists($jamaah['file_vaksin']))
                                                <div class="mb-1">
                                                    @php $ext = strtolower(pathinfo($jamaah['file_vaksin'], PATHINFO_EXTENSION)); @endphp
                                                    @if ($ext == 'pdf')
                                                        <i class="fas fa-file-pdf text-2xl text-red-500"></i>
                                                    @else
                                                        <img src="{{ Storage::url($jamaah['file_vaksin']) }}"
                                                            alt="Vaksin"
                                                            class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                                    @endif
                                                    <p class="text-xs text-gray-400">File saat ini</p>
                                                </div>
                                            @endif
                                            <input type="file" name="jamaahs[{{ $index }}][file_vaksin]"
                                                accept=".pdf,.jpg,.jpeg,.png"
                                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                            <p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">File Visa</label>
                                            @if (!empty($jamaah['file_visa']) && Storage::disk('public')->exists($jamaah['file_visa']))
                                                <div class="mb-1">
                                                    @php $ext = strtolower(pathinfo($jamaah['file_visa'], PATHINFO_EXTENSION)); @endphp
                                                    @if ($ext == 'pdf')
                                                        <i class="fas fa-file-pdf text-2xl text-red-500"></i>
                                                    @else
                                                        <img src="{{ Storage::url($jamaah['file_visa']) }}"
                                                            alt="Visa"
                                                            class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                                    @endif
                                                    <p class="text-xs text-gray-400">File saat ini</p>
                                                </div>
                                            @endif
                                            <input type="file" name="jamaahs[{{ $index }}][file_visa]"
                                                accept=".pdf,.jpg,.jpeg,.png"
                                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                            <p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">File Paspor</label>
                                            @if (!empty($jamaah['file_paspor']) && Storage::disk('public')->exists($jamaah['file_paspor']))
                                                <div class="mb-1">
                                                    @php $ext = strtolower(pathinfo($jamaah['file_paspor'], PATHINFO_EXTENSION)); @endphp
                                                    @if ($ext == 'pdf')
                                                        <i class="fas fa-file-pdf text-2xl text-red-500"></i>
                                                    @else
                                                        <img src="{{ Storage::url($jamaah['file_paspor']) }}"
                                                            alt="Paspor"
                                                            class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                                    @endif
                                                    <p class="text-xs text-gray-400">File saat ini</p>
                                                </div>
                                            @endif
                                            <input type="file" name="jamaahs[{{ $index }}][file_paspor]"
                                                accept=".pdf,.jpg,.jpeg,.png"
                                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                            <p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 flex justify-center">
                            <button type="button" onclick="addJamaah()"
                                class="inline-flex items-center px-6 py-2.5 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm font-medium shadow-sm">
                                <i class="fas fa-plus mr-2"></i> Tambah Jamaah
                            </button>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan Tambahan</label>
                        <textarea name="catatan_tambahan" rows="2"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">{{ old('catatan_tambahan', $keluarga->catatan_tambahan) }}</textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4">
                        <a href="{{ route('transaksional.keluarga.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors text-sm font-medium">
                            Batal
                        </a>
                        <button type="submit" id="btnSubmit"
                            class="px-4 py-2 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 transition-colors text-sm font-medium shadow-sm hover:shadow">
                            <i class="fas fa-save mr-2"></i> Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @php
        $existingUpgradeKeluarga = $keluarga->upgradeHotels
            ->map(function ($u) {
                return [
                    'id_hotel'      => $u->id_hotel,
                    'id_kamar'      => $u->id_kamar,
                    'tipe_kamar'    => $u->tipe_kamar,
                    'harga_per_pax' => (int) $u->harga_per_pax,
                    'jumlah_pax'    => (int) $u->jumlah_pax,
                ];
            })
            ->values()
            ->toArray();

        $existingUpgradeJamaah = $keluarga->jamaahs
            ->mapWithKeys(function ($j) {
                return [
                    $j->id_jamaah => $j->upgradeHotels
                        ->map(function ($u) {
                            return [
                                'id_hotel'      => $u->id_hotel,
                                'id_kamar'      => $u->id_kamar,
                                'tipe_kamar'    => $u->tipe_kamar,
                                'harga_per_pax' => (int) $u->harga_per_pax,
                                'jumlah_pax'    => (int) $u->jumlah_pax,
                            ];
                        })
                        ->values()
                        ->toArray(),
                ];
            })
            ->toArray();
    @endphp

    @push('scripts')
        <script>
            var globalKeberangkatanList = [];
            var globalHargaBulananList = [];

            window.__KELUARGA_DATA__ = {
                hotelOptions: @json($hotelOptions),
                hubunganOptions: @json($hubunganOptions),
                kotaAsals: @json($kotaAsals),
            };

            var EXCLUDE_JAMAAH_IDS = @json($keluarga->jamaahs->pluck('id_jamaah')->toArray());
            var OLD_ID_HARGA = parseInt("{{ old('id_produk_harga_bulanan', $keluarga->id_produk_harga_bulanan) ?: 0 }}") || 0;
            var OLD_ID_KEBERANGKATAN = parseInt(
                "{{ old('id_produk_keberangkatan', $keluarga->id_produk_keberangkatan) ?: 0 }}") || 0;

            var EXISTING_UPGRADE_KELUARGA = {{ Illuminate\Support\Js::from($existingUpgradeKeluarga) }};
            var EXISTING_UPGRADE_JAMAAH = {{ Illuminate\Support\Js::from($existingUpgradeJamaah) }};

            var INITIAL_ID_KEBERANGKATAN = parseInt(document.getElementById('id_produk_keberangkatan_hidden')?.value || 0) || 0;
            var INITIAL_ID_HARGA = parseInt(document.getElementById('id_produk_harga_bulanan_hidden')?.value || 0) || 0;
            var INITIAL_BULAN_KEBERANGKATAN = parseInt(document.getElementById('bulan_keberangkatan_hidden')?.value || 0) || 0;
            var INITIAL_TAHUN_KEBERANGKATAN = parseInt(document.getElementById('tahun_keberangkatan_hidden')?.value || 0) || 0;

            if (INITIAL_ID_KEBERANGKATAN > 0) OLD_ID_KEBERANGKATAN = INITIAL_ID_KEBERANGKATAN;
            if (INITIAL_ID_HARGA > 0) OLD_ID_HARGA = INITIAL_ID_HARGA;

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

            function hitungSubtotalPax(item, defaultPax) {
                const cb = item.querySelector('.kamar-checkbox');
                if (!cb || !cb.checked) return 0;

                const hargaPerPax = parseInt((item.querySelector('.kamar-harga-pax').value || '')
                    .replace(/^Rp\s*/, '').replace(/\./g, '')) || 0;
                const jumlahPax = parseInt(item.querySelector('.kamar-jumlah-pax').value) || defaultPax;

                const subtotal = hargaPerPax * jumlahPax;

                const previewEl = item.querySelector('.subtotal-value');
                if (previewEl) previewEl.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');

                return subtotal;
            }

            function injectKeberangkatanHiddenInputs(k, selectedHargaId) {
                var wrapper = document.getElementById('keberangkatan-data-hidden-wrapper');
                if (!wrapper) {
                    wrapper = document.createElement('div');
                    wrapper.id = 'keberangkatan-data-hidden-wrapper';
                    var form = document.getElementById('keluargaForm');
                    if (form) form.appendChild(wrapper);
                }

                if (!k) {
                    wrapper.innerHTML = '';
                    return;
                }

                var hargaSel = document.querySelector('.harga-bulanan-select');
                var selectedOpt = (hargaSel && hargaSel.value)
                    ? hargaSel.options[hargaSel.selectedIndex]
                    : null;

                var hargaBulananId = selectedHargaId || (selectedOpt ? hargaSel.value : '');

                var bulanHarga, tahunHarga, harga;
                if (hargaBulananId && selectedOpt) {
                    bulanHarga = selectedOpt.dataset.bulan;
                    tahunHarga = selectedOpt.dataset.tahun;
                    harga = selectedOpt.dataset.harga;
                } else {
                    bulanHarga = k.bulan;
                    tahunHarga = k.tahun;
                    harga = k.harga_default;
                }

                var bulanKeberangkatan = parseInt(k.bulan);
                var tahunKeberangkatan = parseInt(k.tahun);

                wrapper.innerHTML = '' +
                    '<input type="hidden" name="keberangkatan_data[0][id_produk_keberangkatan]" value="' + k.id + '">' +
                    '<input type="hidden" name="keberangkatan_data[0][id_produk_harga_bulanan]" value="' + (hargaBulananId || '') + '">' +
                    '<input type="hidden" name="keberangkatan_data[0][bulan_keberangkatan]" value="' + bulanKeberangkatan + '">' +
                    '<input type="hidden" name="keberangkatan_data[0][tahun_keberangkatan]" value="' + tahunKeberangkatan + '">' +
                    '<input type="hidden" name="keberangkatan_data[0][bulan_harga]" value="' + bulanHarga + '">' +
                    '<input type="hidden" name="keberangkatan_data[0][tahun_harga]" value="' + tahunHarga + '">' +
                    '<input type="hidden" name="keberangkatan_data[0][harga]" value="' + harga + '">';
            }

            function updateFeeAgentPerJamaah() {
                var feeAgentInput = document.getElementById('fee_agent');
                var container = document.getElementById('feeAgentPerJamaahInfo');
                var elTotal = document.getElementById('infoTotalFeeAgent');
                var elJumlah = document.getElementById('infoJumlahJamaahFee');
                var elPerOrang = document.getElementById('infoFeeAgentPerOrang');

                if (!feeAgentInput) return;

                var totalFee = parseInt(feeAgentInput.value.replace(/\./g, '')) || 0;
                var jumlahJamaah = document.querySelectorAll('.jamaah-row').length;
                var feePerOrang = jumlahJamaah > 0 ? Math.round(totalFee / jumlahJamaah) : 0;

                if (totalFee > 0 && jumlahJamaah > 0) {
                    container.classList.remove('hidden');
                    elTotal.textContent = 'Rp ' + totalFee.toLocaleString('id-ID');
                    elJumlah.textContent = jumlahJamaah + ' orang';
                    elPerOrang.textContent = 'Rp ' + feePerOrang.toLocaleString('id-ID');
                } else {
                    container.classList.add('hidden');
                }
            }

            let jamaahIndex = {{ count($jamaahData) }};

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

            function validateJamaahForm() {
                var labels = {
                    nama_lengkap: 'Nama Lengkap',
                    nik: 'NIK',
                    nama_ayah: 'Nama Ayah',
                    pekerjaan: 'Pekerjaan',
                    jenis_kelamin: 'Jenis Kelamin',
                    status_kawin: 'Status Kawin',
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

                if (!produkPaket.value) {
                    placeholder.classList.remove('hidden');
                    resetHiddenInputs();
                    return;
                }

                placeholder.classList.add('hidden');
                step1Container.classList.remove('hidden');
                tahunSelect.innerHTML = '<option value="">-- Memuat... --</option>';

                var url =
                    `{{ route('transaksional.keluarga.keberangkatan-by-produk') }}?produk_paket=${encodeURIComponent(produkPaket.value)}`;
                if (EXCLUDE_JAMAAH_IDS && EXCLUDE_JAMAAH_IDS.length > 0) {
                    url += `&exclude_jamaah_ids=${EXCLUDE_JAMAAH_IDS.join(',')}`;
                }

                fetch(url)
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

                        var selectedTahun = null;
                        if (INITIAL_ID_KEBERANGKATAN > 0) {
                            var matchK = globalKeberangkatanList.find(k => parseInt(k.id) === INITIAL_ID_KEBERANGKATAN);
                            if (matchK) selectedTahun = parseInt(matchK.tahun);
                        }
                        if (!selectedTahun && INITIAL_TAHUN_KEBERANGKATAN > 0 && tahunList.includes(
                                INITIAL_TAHUN_KEBERANGKATAN)) {
                            selectedTahun = INITIAL_TAHUN_KEBERANGKATAN;
                        }
                        if (!selectedTahun && tahunList.length > 0) {
                            selectedTahun = tahunList[0];
                        }

                        if (selectedTahun) {
                            tahunSelect.value = selectedTahun;
                            renderKeberangkatanByTahun(selectedTahun);
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

                var selectedKeberangkatan = null;
                if (INITIAL_ID_KEBERANGKATAN > 0) {
                    selectedKeberangkatan = filtered.find(k => parseInt(k.id) === INITIAL_ID_KEBERANGKATAN) || null;
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
                    var currentIdHarga = INITIAL_ID_HARGA > 0 ? INITIAL_ID_HARGA : '';
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
                            var isOldSel = (parseInt(currentIdHarga) === OLD_ID_HARGA);
                            currentKuotaColor = (match2.is_full && !isOldSel) ? 'text-red-600' :
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
                    document.getElementById('keberangkatan-select').value = selectedKeberangkatan.id;
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
                    injectKeberangkatanHiddenInputs(null);
                    return;
                }

                var k = globalKeberangkatanList.find(item => parseInt(item.id) === parseInt(selectedId));
                if (!k) {
                    wrapper.innerHTML = '';
                    return;
                }

                var currentIdHarga = INITIAL_ID_HARGA > 0 ? INITIAL_ID_HARGA : '';

                document.getElementById('id_produk_keberangkatan_hidden').value = k.id;
                document.getElementById('bulan_keberangkatan_hidden').value = k.bulan;
                document.getElementById('tahun_keberangkatan_hidden').value = k.tahun;

                if (parseInt(selectedId) !== INITIAL_ID_KEBERANGKATAN) {
                    currentIdHarga = '';
                    document.getElementById('id_produk_harga_bulanan_hidden').value = '';
                    document.getElementById('bulan_harga_hidden').value = k.bulan;
                    document.getElementById('tahun_harga_hidden').value = k.tahun;
                }

                var currentKuotaText = k.kuota_default_formatted;
                var currentKuotaColor = k.kuota_default_is_full ? 'text-red-600' : 'text-blue-600';
                var currentHarga = parseInt(k.harga_default || 0);
                var currentBulanHarga = k.bulan;
                var currentTahunHarga = k.tahun;

                if (currentIdHarga) {
                    var match = globalHargaBulananList.find(h => parseInt(h.id) === parseInt(currentIdHarga));
                    if (match) {
                        currentHarga = parseInt(match.harga);
                        currentBulanHarga = match.bulan;
                        currentTahunHarga = match.tahun;
                        currentKuotaText = match.kuota_formatted;
                        var isOldSel = (parseInt(currentIdHarga) === OLD_ID_HARGA);
                        currentKuotaColor = (match.is_full && !isOldSel) ? 'text-red-600' :
                            (match.kuota_sisa !== -1 && match.kuota_sisa <= 3 ? 'text-orange-600' : 'text-blue-600');
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
                if (hargaSel) {
                    hargaSel.addEventListener('change', handleHargaBulananChange);
                    hargaSel.value = currentIdHarga || '';
                }

                updateHargaInfo(currentBulanHarga, currentTahunHarga, currentHarga);

                injectKeberangkatanHiddenInputs(k, currentIdHarga);
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

                var isOldSelection = (parseInt(hargaBulananId || 0) === OLD_ID_HARGA && OLD_ID_HARGA > 0);

                if (isFull && !isOldSelection) {
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
                } else if (isFull && !isOldSelection) {
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

                injectKeberangkatanHiddenInputs(k, hargaBulananId);
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
                injectKeberangkatanHiddenInputs(null);

                INITIAL_ID_KEBERANGKATAN = 0;
                INITIAL_ID_HARGA = 0;
                INITIAL_BULAN_KEBERANGKATAN = 0;
                INITIAL_TAHUN_KEBERANGKATAN = 0;
            }

            function getBulanName(bulan) {
                const namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];
                return namaBulan[parseInt(bulan) - 1] || bulan;
            }

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

                const jumlahJamaah = document.querySelectorAll('.jamaah-row').length;

                container.querySelectorAll('.kamar-jumlah-pax').forEach(input => {
                    input.value = jumlahJamaah;
                });

                let total = 0;
                container.querySelectorAll('.hotel-upgrade-item').forEach(hotelItem => {
                    const hotelCheckbox = hotelItem.querySelector('.upgrade-hotel-checkbox');
                    if (!hotelCheckbox || !hotelCheckbox.checked) return;

                    let subtotalHotel = 0;
                    hotelItem.querySelectorAll('.kamar-item').forEach(item => {
                        subtotalHotel += hitungSubtotalPax(item, jumlahJamaah);
                    });

                    const hotelId = hotelItem.dataset.hotelId;
                    const subtotalEl = container.querySelector(`.subtotal-hotel-keluarga-${hotelId}`);
                    if (subtotalEl) subtotalEl.textContent = 'Rp ' + subtotalHotel.toLocaleString('id-ID');

                    total += subtotalHotel;
                });

                const totalEl = document.getElementById('totalUpgradeKeluarga');
                if (totalEl) totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');

                const perJamaah = jumlahJamaah > 0 ? Math.round(total / jumlahJamaah) : 0;
                const perEl = document.getElementById('perJamaahUpgradeKeluarga');
                if (perEl) perEl.textContent = 'Rp ' + perJamaah.toLocaleString('id-ID');
            }

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

                container.querySelectorAll('.kamar-jumlah-pax').forEach(input => {
                    input.value = 1;
                });

                let total = 0;
                container.querySelectorAll('.hotel-upgrade-item').forEach(hotelItem => {
                    const hotelCheckbox = hotelItem.querySelector('.upgrade-hotel-checkbox');
                    if (!hotelCheckbox || !hotelCheckbox.checked) return;

                    let subtotalHotel = 0;
                    hotelItem.querySelectorAll('.kamar-item').forEach(item => {
                        subtotalHotel += hitungSubtotalPax(item, 1);
                    });

                    const hotelId = hotelItem.dataset.hotelId;
                    const subtotalEl = container.querySelector(`.subtotal-hotel-jamaah-${jamaahIndex}-${hotelId}`);
                    if (subtotalEl) subtotalEl.textContent = 'Rp ' + subtotalHotel.toLocaleString('id-ID');

                    total += subtotalHotel;
                });

                const totalEl = document.getElementById('totalUpgradeJamaah-' + jamaahIndex);
                if (totalEl) totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
            }

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

                        const jumlahJamaah = document.querySelectorAll('.jamaah-row').length;
                        const defaultPax = scope === 'keluarga' ? jumlahJamaah : 1;

                        container.innerHTML = '<div class="grid grid-cols-1 gap-2">' + data.html + '</div>' +
                            `<div class="mt-1 flex items-center justify-between bg-orange-50 rounded px-2 py-1">
                                <span class="text-[10px] font-medium text-gray-600">Subtotal hotel ini:</span>
                                <span class="text-xs font-bold text-orange-600 subtotal-hotel-${scope}-${hotelId}">Rp 0</span>
                            </div>`;

                        container.querySelectorAll('.kamar-jumlah-pax').forEach(input => {
                            input.value = defaultPax;
                        });

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

                        container.querySelectorAll('.kamar-harga-pax').forEach(input => {
                            input.addEventListener('change', () => {
                                updateUpgradeHiddenInputs(scope, hotelId, container);
                                recalcUpgradeScope(scope);
                            });
                            input.addEventListener('keyup', () => {
                                updateUpgradeHiddenInputs(scope, hotelId, container);
                                recalcUpgradeScope(scope);
                            });
                        });

                        restoreExistingUpgrades(scope, hotelId, container);
                    })
                    .catch(err => {
                        console.error(err);
                        container.innerHTML =
                            '<div class="text-xs text-red-500 text-center py-2">Gagal memuat kamar.</div>';
                    });
            }

            function restoreExistingUpgrades(scope, hotelId, container) {
                let existingList = [];

                if (scope === 'keluarga') {
                    existingList = EXISTING_UPGRADE_KELUARGA;
                } else if (scope.startsWith('jamaah-')) {
                    const idx = parseInt(scope.replace('jamaah-', ''));
                    const jamaahRow = document.getElementById('jamaah-row-' + idx);
                    if (jamaahRow) {
                        const idJamaahInput = jamaahRow.querySelector('input[name$="[id_jamaah]"]');
                        const idJamaah = idJamaahInput ? idJamaahInput.value : null;
                        if (idJamaah && EXISTING_UPGRADE_JAMAAH[idJamaah]) {
                            existingList = EXISTING_UPGRADE_JAMAAH[idJamaah];
                        }
                    }
                }

                const matchExisting = (existingList || []).filter(u => parseInt(u.id_hotel) === parseInt(hotelId));

                if (matchExisting.length === 0) {
                    updateUpgradeHiddenInputs(scope, hotelId, container);
                    recalcUpgradeScope(scope);
                    return;
                }

                container.querySelectorAll('.kamar-item').forEach(item => {
                    const cb = item.querySelector('.kamar-checkbox');
                    if (!cb) return;

                    const match = matchExisting.find(u => parseInt(u.id_kamar) === parseInt(cb.value));
                    if (match) {
                        cb.checked = true;

                        const jumlahPaxInput = item.querySelector('.kamar-jumlah-pax');
                        const hargaPaxInput  = item.querySelector('.kamar-harga-pax');
                        if (jumlahPaxInput) jumlahPaxInput.value = match.jumlah_pax ?? 1;
                        if (hargaPaxInput)  hargaPaxInput.value = 'Rp ' + parseInt(match.harga_per_pax || 0).toLocaleString('id-ID');

                        const inputs = item.querySelectorAll('input:not(.kamar-checkbox)');
                        inputs.forEach(input => {
                            input.disabled = false;
                            input.classList.remove('opacity-50', 'bg-gray-50');
                        });

                        const previewEl = item.querySelector('.subtotal-value');
                        if (previewEl) {
                            const subtotal = (match.harga_per_pax || 0) * (match.jumlah_pax || 1);
                            previewEl.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
                        }
                    }
                });

                updateUpgradeHiddenInputs(scope, hotelId, container);
                recalcUpgradeScope(scope);
            }

            function recalcUpgradeScope(scope) {
                if (scope === 'keluarga') {
                    updateTotalUpgradeKeluarga();
                } else if (scope.startsWith('jamaah-')) {
                    const idx = parseInt(scope.replace('jamaah-', ''));
                    updateTotalUpgradeJamaah(idx);
                }
            }

            function updateUpgradeHiddenInputs(scope, hotelId, container) {
                removeUpgradeHiddenInputs(scope, hotelId);

                let parentForm = null;
                let fieldPrefix = '';
                let defaultPax = 1;

                if (scope === 'keluarga') {
                    parentForm = document.getElementById('keluargaForm');
                    fieldPrefix = 'upgrade_hotels';
                    defaultPax = document.querySelectorAll('.jamaah-row').length;
                } else {
                    const idx = parseInt(scope.replace('jamaah-', ''));
                    parentForm = document.getElementById('jamaah-row-' + idx);
                    fieldPrefix = 'jamaahs[' + idx + '][upgrade_hotels]';
                    defaultPax = 1;
                }

                if (!parentForm) return;

                let subtotal = 0;

                container.querySelectorAll('.kamar-item').forEach(item => {
                    const cb = item.querySelector('.kamar-checkbox');
                    if (!cb || !cb.checked) return;

                    const hargaPerPax = parseInt((item.querySelector('.kamar-harga-pax').value || '')
                        .replace(/^Rp\s*/, '').replace(/\./g, '')) || 0;
                    const jumlahPax = parseInt(item.querySelector('.kamar-jumlah-pax').value) || defaultPax;
                    const itemTotal = hargaPerPax * jumlahPax;
                    subtotal += itemTotal;

                    var uniqueKey = fieldPrefix + '[' + hotelId + '_' + cb.value + ']';
                    var tipeKamar = cb.dataset.tipe || '';

                    var grp = document.createElement('div');
                    grp.className = 'upgrade-hidden-group';
                    grp.dataset.scope = scope;
                    grp.dataset.hotelId = hotelId;

                    grp.innerHTML =
                        '<input type="hidden" name="' + uniqueKey + '[id_hotel]" value="' + hotelId + '">' +
                        '<input type="hidden" name="' + uniqueKey + '[id_kamar]" value="' + cb.value + '">' +
                        '<input type="hidden" name="' + uniqueKey + '[tipe_kamar]" value="' + tipeKamar + '">' +
                        '<input type="hidden" name="' + uniqueKey + '[jumlah_pax]" value="' + jumlahPax + '">' +
                        '<input type="hidden" name="' + uniqueKey + '[harga_per_pax]" value="' + hargaPerPax + '">';

                    parentForm.appendChild(grp);
                });

                var subtotalSelector = '.subtotal-hotel-' + scope + '-' + hotelId;
                const subtotalEl = container.querySelector(subtotalSelector);
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

                var selector = '.upgrade-hidden-group[data-scope="' + scope + '"][data-hotel-id="' + hotelId + '"]';
                parentForm.querySelectorAll(selector).forEach(el => el.remove());
            }

            function addJamaah() {
                const container = document.getElementById('jamaahContainer');
                const row = document.createElement('div');
                row.className = 'jamaah-row bg-gray-50 rounded-xl p-4 mb-3 border border-gray-200';

                const idx = jamaahIndex;
                row.id = 'jamaah-row-' + idx;

                var hotelUpgradeOptions = '';
                var hotelList = window.__KELUARGA_DATA__.hotelOptions || [];
                hotelList.forEach(function(hotel) {
                    var hotelId = hotel.id_hotel;
                    var namaHotel = hotel.nama_hotel || '';
                    var kota = hotel.kota || '-';
                    var bintang = hotel.bintang_text || '';

                    hotelUpgradeOptions +=
                        '<div class="hotel-upgrade-item border border-gray-200 rounded-lg" ' +
                        'data-hotel-id="' + hotelId + '" ' +
                        'data-scope="jamaah-' + idx + '">' +
                        '<label class="flex items-center gap-2 p-2 cursor-pointer hover:bg-gray-50 rounded-t-lg">' +
                        '<input type="checkbox" ' +
                        'class="upgrade-hotel-checkbox w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500" ' +
                        'data-hotel-id="' + hotelId + '" ' +
                        'data-scope="jamaah-' + idx + '" ' +
                        'onchange="toggleHotelDetailJamaah(' + idx + ', ' + hotelId + ')">' +
                        '<div class="flex-1">' +
                        '<p class="text-sm font-medium text-gray-700">' + namaHotel + '</p>' +
                        '<p class="text-xs text-gray-500">' +
                        '<i class="fas fa-map-marker-alt mr-1"></i> ' + kota +
                        (bintang ? ' · ' + bintang : '') +
                        '</p>' +
                        '</div>' +
                        '</label>' +
                        '<div id="hotelDetailContainer-' + idx + '-' + hotelId + '" ' +
                        'class="hidden border-t border-gray-100 p-2 space-y-2">' +
                        '<div class="text-xs text-gray-400 text-center py-2">' +
                        '<i class="fas fa-spinner fa-spin mr-1"></i> Memuat tipe kamar...' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                });

                var hubunganOptsHtml = '';
                var hubunganList = window.__KELUARGA_DATA__.hubunganOptions || [];
                hubunganList.forEach(function(opt) {
                    hubunganOptsHtml += '<option value="' + opt + '">' + opt + '</option>';
                });

                var kotaAsalOptsHtml = '';
                var kotaList = window.__KELUARGA_DATA__.kotaAsals || [];
                kotaList.forEach(function(kota) {
                    kotaAsalOptsHtml +=
                        '<option value="' + kota.nama_kota + '" ' +
                        'data-pulau="' + (kota.pulau || '') + '" ' +
                        'data-bandara="' + (kota.bandara_terdekat || '') + '">' +
                        kota.nama_kota + ' - ' + (kota.provinsi || '') +
                        '</option>';
                });

                row.innerHTML =
                    '<div class="flex items-center justify-between mb-3">' +
                    '<span class="text-sm font-medium text-gray-700">Jamaah #' + (idx + 1) + '</span>' +
                    '<button type="button" onclick="removeJamaah(this)" class="text-red-500 hover:text-red-700 text-sm">' +
                    '<i class="fas fa-times"></i> Hapus' +
                    '</button>' +
                    '</div>' +
                    '<input type="hidden" name="jamaahs[' + idx + '][id_jamaah]" value="">' +

                    '<div class="grid grid-cols-1 md:grid-cols-3 gap-3">' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>' +
                    '<input type="text" name="jamaahs[' + idx + '][nama_lengkap]" ' +
                    'class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required>' +
                    '</div>' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">NIK <span class="text-red-500">*</span></label>' +
                    '<input type="text" name="jamaahs[' + idx + '][nik]" ' +
                    'class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" ' +
                    'placeholder="16 digit NIK" required>' +
                    '</div>' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">Nama Ayah <span class="text-red-500">*</span></label>' +
                    '<input type="text" name="jamaahs[' + idx + '][nama_ayah]" ' +
                    'class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required>' +
                    '</div>' +
                    '</div>' +

                    '<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">Pekerjaan <span class="text-red-500">*</span></label>' +
                    '<input type="text" name="jamaahs[' + idx + '][pekerjaan]" ' +
                    'class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required>' +
                    '</div>' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>' +
                    '<select name="jamaahs[' + idx + '][jenis_kelamin]" ' +
                    'class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required>' +
                    '<option value="">-- Pilih --</option>' +
                    '<option value="L">Laki-laki</option>' +
                    '<option value="P">Perempuan</option>' +
                    '</select>' +
                    '</div>' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">Status Kawin <span class="text-red-500">*</span></label>' +
                    '<select name="jamaahs[' + idx + '][status_kawin]" ' +
                    'class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required>' +
                    '<option value="">-- Pilih --</option>' +
                    '<option value="Belum Kawin">Belum Kawin</option>' +
                    '<option value="Kawin">Kawin</option>' +
                    '<option value="Cerai Hidup">Cerai Hidup</option>' +
                    '<option value="Cerai Mati">Cerai Mati</option>' +
                    '</select>' +
                    '</div>' +
                    '</div>' +

                    '<div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">WhatsApp <span class="text-red-500">*</span></label>' +
                    '<input type="text" name="jamaahs[' + idx + '][wa]" ' +
                    'class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" ' +
                    'placeholder="08xxxxxxxxxx" required>' +
                    '</div>' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">Tempat Lahir <span class="text-red-500">*</span></label>' +
                    '<input type="text" name="jamaahs[' + idx + '][tempat_lahir]" ' +
                    'class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required>' +
                    '</div>' +
                    '</div>' +

                    '<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Lahir</label>' +
                    '<input type="date" name="jamaahs[' + idx + '][tanggal_lahir]" ' +
                    'class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">' +
                    '</div>' +
                    '</div>' +

                    '<div class="mt-3 pt-3 border-t border-gray-200">' +
                    '<h6 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">' +
                    '<i class="fas fa-map-marker-alt text-yellow-500 mr-1.5"></i> Alamat Domisili' +
                    '</h6>' +
                    '<div class="grid grid-cols-1 md:grid-cols-2 gap-3">' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">Hubungan Keluarga</label>' +
                    '<select name="jamaahs[' + idx + '][hubungan_keluarga]" ' +
                    'class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">' +
                    '<option value="">-- Pilih Hubungan --</option>' + hubunganOptsHtml +
                    '</select>' +
                    '</div>' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">Alamat</label>' +
                    '<input type="text" name="jamaahs[' + idx + '][alamat]" ' +
                    'class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm" ' +
                    'placeholder="Jl. / RT / RW / No.">' +
                    '</div>' +
                    '</div>' +
                    '<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">Kota / Kabupaten</label>' +
                    '<input type="text" name="jamaahs[' + idx + '][kota_kabupaten]" ' +
                    'class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm" ' +
                    'placeholder="Kota/Kabupaten">' +
                    '</div>' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">Kecamatan</label>' +
                    '<input type="text" name="jamaahs[' + idx + '][kecamatan]" ' +
                    'class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm" ' +
                    'placeholder="Kecamatan">' +
                    '</div>' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">Kelurahan / Desa</label>' +
                    '<input type="text" name="jamaahs[' + idx + '][kelurahan]" ' +
                    'class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm" ' +
                    'placeholder="Kelurahan/Desa">' +
                    '</div>' +
                    '</div>' +
                    '</div>' +

                    '<div class="mt-3 pt-3 border-t border-gray-200">' +
                    '<div class="bg-blue-50 rounded-lg p-3 border border-blue-100">' +
                    '<h6 class="text-xs font-semibold text-blue-700 mb-2 flex items-center">' +
                    '<i class="fas fa-plane text-blue-500 mr-1.5"></i> Keberangkatan' +
                    '</h6>' +
                    '<div class="grid grid-cols-1 md:grid-cols-3 gap-3">' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">Kota Asal</label>' +
                    '<select name="jamaahs[' + idx + '][kota_asal]" ' +
                    'class="kota-asal-select w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm bg-white">' +
                    '<option value="">-- Pilih Kota Asal --</option>' + kotaAsalOptsHtml +
                    '</select>' +
                    '</div>' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">Pulau</label>' +
                    '<input type="text" name="jamaahs[' + idx + '][pulau]" ' +
                    'class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm cursor-not-allowed" readonly placeholder="Otomatis">' +
                    '</div>' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">Bandara Keberangkatan</label>' +
                    '<input type="text" name="jamaahs[' + idx + '][bandara_keberangkatan]" ' +
                    'class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm cursor-not-allowed" readonly placeholder="Otomatis">' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +

                    '<div class="mt-3 pt-3 border-t border-gray-200">' +
                    '<h6 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">' +
                    '<i class="fas fa-passport text-yellow-500 mr-1.5"></i> Passport & Dokumen' +
                    '</h6>' +
                    '<div class="grid grid-cols-1 md:grid-cols-2 gap-3">' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">Nomor Paspor</label>' +
                    '<input type="text" name="jamaahs[' + idx + '][nomor_paspor]" ' +
                    'class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">' +
                    '</div>' +
                    '<div>' +
                    '<label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer mt-6">' +
                    '<input type="checkbox" name="jamaahs[' + idx + '][is_kepala_keluarga]" value="1" ' +
                    'class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">' +
                    '<span>Kepala Keluarga / Kelompok</span>' +
                    '</label>' +
                    '</div>' +
                    '</div>' +
                    '<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Terbit Paspor</label>' +
                    '<input type="date" name="jamaahs[' + idx + '][paspor_terbit]" ' +
                    'class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">' +
                    '</div>' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Berakhir Paspor</label>' +
                    '<input type="date" name="jamaahs[' + idx + '][paspor_expired]" ' +
                    'class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">' +
                    '</div>' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">Diterbitkan Di</label>' +
                    '<input type="text" name="jamaahs[' + idx + '][paspor_diterbitkan_di]" ' +
                    'class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm" ' +
                    'placeholder="Kota/Negara">' +
                    '</div>' +
                    '</div>' +

                    '<div class="mt-3 pt-3 border-t border-gray-200">' +
                    '<div class="mb-3">' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1.5">' +
                    'Apakah ada Pembuatan Passport? <span class="text-red-500">*</span>' +
                    '</label>' +
                    '<div class="inline-flex rounded-lg overflow-hidden border border-gray-300">' +
                    '<button type="button" onclick="setPembuatanPassportKeluarga(' + idx + ', 1)" ' +
                    'id="btnYa-' + idx + '" ' +
                    'class="px-6 py-2 text-sm font-medium bg-white text-gray-600 hover:bg-gray-50 transition-colors">' +
                    '<i class="fas fa-check mr-1"></i> YA' +
                    '</button>' +
                    '<button type="button" onclick="setPembuatanPassportKeluarga(' + idx + ', 0)" ' +
                    'id="btnTidak-' + idx + '" ' +
                    'class="px-6 py-2 text-sm font-medium bg-red-500 text-white transition-colors border-l border-gray-300">' +
                    '<i class="fas fa-times mr-1"></i> TIDAK' +
                    '</button>' +
                    '</div>' +
                    '<input type="hidden" name="jamaahs[' + idx + '][is_pembuatan_passport]" ' +
                    'id="is_pembuatan_passport-' + idx + '" value="0">' +
                    '<p class="text-xs text-gray-400 mt-1">' +
                    '<i class="fas fa-info-circle mr-1"></i>' +
                    'Pilih <b>YA</b> jika ada pendapatan perusahaan. Pilih <b>TIDAK</b> jika jamaah sudah punya passport.' +
                    '</p>' +
                    '</div>' +
                    '<div class="grid grid-cols-1 md:grid-cols-2 gap-3">' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">' +
                    'Pembayaran Passport <span class="text-gray-400 text-xs">(boleh kosong)</span>' +
                    '</label>' +
                    '<div class="relative">' +
                    '<span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>' +
                    '<input type="text" name="jamaahs[' + idx + '][pendampingan_fee]" value="0" ' +
                    'oninput="formatRupiah(this); updateTotalPendampingan(' + idx + ')" ' +
                    'onfocus="handleZeroFocus(this)" onblur="handleZeroBlur(this)" ' +
                    'class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm" ' +
                    'placeholder="0">' +
                    '</div>' +
                    '</div>' +
                    '<div id="feePetugasContainer-' + idx + '" class="hidden">' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">' +
                    'Pendapatan Perusahaan <span class="text-gray-400 text-xs">(fee petugas)</span>' +
                    '</label>' +
                    '<div class="relative">' +
                    '<span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>' +
                    '<input type="text" name="jamaahs[' + idx + '][pendampingan_fee_petugas]" ' +
                    'id="pendampingan_fee_petugas-' + idx + '" value="0" ' +
                    'oninput="formatRupiah(this); updateTotalPendampingan(' + idx + ')" ' +
                    'onfocus="handleZeroFocus(this)" onblur="handleZeroBlur(this)" ' +
                    'class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm" ' +
                    'placeholder="0">' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="mt-2 text-xs text-gray-500">' +
                    'Total Pembuatan Passport: ' +
                    '<span class="font-bold text-blue-600" id="total-pendampingan-' + idx + '">Rp 0</span> ' +
                    '<span class="text-gray-400">(Pembayaran + Pendapatan Perusahaan)</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +

                    '<div class="mt-3 pt-3 border-t border-gray-200">' +
                    '<div class="flex items-center justify-between mb-2">' +
                    '<h6 class="text-xs font-semibold text-gray-700 flex items-center">' +
                    '<i class="fas fa-hotel text-orange-500 mr-1.5"></i>' +
                    'Upgrade Hotel & Kamar Jamaah' +
                    '<span class="ml-2 text-[10px] text-gray-400 font-normal">(opsional · khusus jamaah ini)</span>' +
                    '</h6>' +
                    '<button type="button" onclick="toggleUpgradeJamaah(' + idx + ')" ' +
                    'class="text-xs text-blue-500 hover:text-blue-700 font-medium">' +
                    '<i class="fas fa-plus mr-1"></i> Atur Hotel' +
                    '</button>' +
                    '</div>' +
                    '<div id="upgradeJamaahContainer-' + idx + '" ' +
                    'class="hidden bg-orange-50 rounded-lg p-3 border border-orange-200">' +
                    '<p class="text-xs text-orange-700 mb-2">' +
                    '<i class="fas fa-info-circle mr-1"></i>' +
                    'Biaya upgrade ini hanya dibebankan ke jamaah ini.' +
                    '</p>' +
                    '<div class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg bg-white p-2 space-y-2">' +
                    hotelUpgradeOptions +
                    '</div>' +
                    '<div class="mt-2 flex items-center justify-between bg-white rounded-lg border border-orange-200 px-3 py-2">' +
                    '<span class="text-xs font-medium text-gray-700">' +
                    '<i class="fas fa-calculator mr-1 text-orange-500"></i>' +
                    'Total Upgrade Jamaah:' +
                    '</span>' +
                    '<span id="totalUpgradeJamaah-' + idx + '" class="text-sm font-bold text-orange-600">Rp 0</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +

                    '<div class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-3 pt-3 border-t border-gray-200">' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">File KTP/KK</label>' +
                    '<input type="file" name="jamaahs[' + idx + '][file_ktp_kk]" accept=".pdf,.jpg,.jpeg,.png" ' +
                    'class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">' +
                    '<p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>' +
                    '</div>' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">File Vaksin</label>' +
                    '<input type="file" name="jamaahs[' + idx + '][file_vaksin]" accept=".pdf,.jpg,.jpeg,.png" ' +
                    'class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">' +
                    '<p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>' +
                    '</div>' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">File Visa</label>' +
                    '<input type="file" name="jamaahs[' + idx + '][file_visa]" accept=".pdf,.jpg,.jpeg,.png" ' +
                    'class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">' +
                    '<p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>' +
                    '</div>' +
                    '<div>' +
                    '<label class="block text-xs font-medium text-gray-600 mb-1">File Paspor</label>' +
                    '<input type="file" name="jamaahs[' + idx + '][file_paspor]" accept=".pdf,.jpg,.jpeg,.png" ' +
                    'class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">' +
                    '<p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>' +
                    '</div>' +
                    '</div>';

                container.appendChild(row);

                var kotaAsalSelect = row.querySelector('.kota-asal-select');
                if (kotaAsalSelect) {
                    kotaAsalSelect.addEventListener('change', function() {
                        var selectedOption = this.options[this.selectedIndex];
                        var pulauInput = row.querySelector('input[name$="[pulau]"]');
                        var bandaraInput = row.querySelector('input[name$="[bandara_keberangkatan]"]');
                        if (pulauInput) pulauInput.value = selectedOption.dataset.pulau || '';
                        if (bandaraInput) bandaraInput.value = selectedOption.dataset.bandara || '';
                    });
                }

                jamaahIndex++;

                document.querySelectorAll('#upgradeKeluargaContainer .kamar-jumlah-pax').forEach(input => {
                    input.value = document.querySelectorAll('.jamaah-row').length;
                });

                hitungDiskonKelompok();
                updateTotalUpgradeKeluarga();
                updateFeeAgentPerJamaah();
            }

            function removeJamaah(button) {
                const row = button.closest('.jamaah-row');
                const container = document.getElementById('jamaahContainer');
                if (container.children.length > 1) {
                    row.remove();
                    const rows = container.querySelectorAll('.jamaah-row');
                    rows.forEach((r, idx) => {
                        const label = r.querySelector('.text-sm.font-medium');
                        if (label) label.textContent = 'Jamaah #' + (idx + 1);
                    });

                    document.querySelectorAll('#upgradeKeluargaContainer .kamar-jumlah-pax').forEach(input => {
                        input.value = document.querySelectorAll('.jamaah-row').length;
                    });

                    hitungDiskonKelompok();
                    updateTotalUpgradeKeluarga();
                    updateFeeAgentPerJamaah();
                } else {
                    alert('Minimal harus ada 1 jamaah!');
                }
            }

            document.getElementById('filter-tahun').addEventListener('change', function() {
                renderKeberangkatanByTahun(this.value);
            });

            document.getElementById('produk_paket').addEventListener('change', function() {
                OLD_ID_HARGA = 0;
                OLD_ID_KEBERANGKATAN = 0;
                INITIAL_ID_KEBERANGKATAN = 0;
                INITIAL_ID_HARGA = 0;
                INITIAL_BULAN_KEBERANGKATAN = 0;
                INITIAL_TAHUN_KEBERANGKATAN = 0;
                loadKeberangkatan();
            });

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
                updateFeeAgentPerJamaah();

                setTimeout(function() {
                    if (EXISTING_UPGRADE_KELUARGA.length > 0) {
                        document.querySelectorAll(
                            '#upgradeKeluargaContainer .upgrade-hotel-checkbox:checked').forEach(function(
                            cb) {
                            cb.dispatchEvent(new Event('change'));
                        });
                    }

                    document.querySelectorAll('.jamaah-row').forEach(function(row, idx) {
                        const jamaahCheckboxes = row.querySelectorAll(
                            '#upgradeJamaahContainer-' + idx + ' .upgrade-hotel-checkbox:checked');

                        if (jamaahCheckboxes.length > 0) {
                            const container = document.getElementById('upgradeJamaahContainer-' + idx);
                            if (container) container.classList.remove('hidden');

                            jamaahCheckboxes.forEach(function(cb) {
                                cb.dispatchEvent(new Event('change'));
                            });
                        }
                    });
                }, 300);

                const form = document.getElementById('keluargaForm');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        if (!validateJamaahForm()) {
                            e.preventDefault();
                            return false;
                        }

                        ['fee_agent'].forEach(function(id) {
                            const input = document.getElementById(id);
                            if (input) input.value = input.value.replace(/\./g, '');
                        });
                        document.querySelectorAll(
                            '[name$="[pendampingan_fee]"], [name$="[pendampingan_fee_petugas]"]').forEach(
                            function(input) {
                                if (input.value) input.value = input.value.replace(/\./g, '');
                            });

                        var selectKeberangkatan = document.getElementById('keberangkatan-select');
                        if (selectKeberangkatan && selectKeberangkatan.value) {
                            var k = globalKeberangkatanList.find(function(item) {
                                return parseInt(item.id) === parseInt(selectKeberangkatan.value);
                            });
                            var selectedIdKeberangkatan = parseInt(selectKeberangkatan.value);
                            var hargaSel = document.querySelector('.harga-bulanan-select');
                            var selectedHargaFull = false;

                            if (hargaSel && hargaSel.value) {
                                var opt = hargaSel.options[hargaSel.selectedIndex];
                                var optId = parseInt(hargaSel.value);
                                selectedHargaFull = (opt.dataset.isFull === '1') && (optId !== OLD_ID_HARGA);
                            }

                            var isOldKeberangkatan = (selectedIdKeberangkatan === OLD_ID_KEBERANGKATAN);
                            var keberangkatanFull = (k && k.kuota_default_is_full) && !isOldKeberangkatan;

                            if (keberangkatanFull || selectedHargaFull) {
                                e.preventDefault();
                                alert(
                                    '⚠️ Kuota bulan/tahun yang dipilih sudah PENUH!\n\nSilakan pilih bulan/tahun lain.'
                                );
                                return false;
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection

{{-- blade-formatter-enable --}}
