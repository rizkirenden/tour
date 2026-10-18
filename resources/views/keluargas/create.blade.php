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

                                        <!-- Baris 4 -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
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
                                                    placeholder="Alamat lengkap">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Kota
                                                    Asal</label>
                                                <select name="jamaahs[{{ $index }}][kota_asal]"
                                                    class="kota-asal-select w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
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
                                        </div>

                                        <!-- Baris 5 -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Nomor
                                                    Paspor</label>
                                                <input type="text" name="jamaahs[{{ $index }}][nomor_paspor]"
                                                    value="{{ $jamaah['nomor_paspor'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Pulau</label>
                                                <input type="text" name="jamaahs[{{ $index }}][pulau]"
                                                    value="{{ $jamaah['pulau'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-100 text-sm cursor-not-allowed"
                                                    readonly>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Bandara
                                                    Keberangkatan</label>
                                                <input type="text"
                                                    name="jamaahs[{{ $index }}][bandara_keberangkatan]"
                                                    value="{{ $jamaah['bandara_keberangkatan'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-100 text-sm cursor-not-allowed"
                                                    readonly>
                                            </div>
                                        </div>

                                        <!-- Baris 6: Kepala Keluarga -->
                                        <div class="mt-3">
                                            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                                <input type="checkbox"
                                                    name="jamaahs[{{ $index }}][is_kepala_keluarga]"
                                                    value="1"
                                                    {{ $jamaah['is_kepala_keluarga'] ?? false ? 'checked' : '' }}
                                                    class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                                                <span>Kepala Keluarga / Kelompok</span>
                                            </label>
                                        </div>

                                        <!-- Pembuatan Passport (TOMBOL YA/TIDAK) -->
                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            @php
                                                $isPassport = ($jamaah['is_pembuatan_passport'] ?? 0) == 1;
                                            @endphp
                                            <div class="mb-3">
                                                <label class="block text-xs font-medium text-gray-600 mb-1.5">
                                                    Apakah ada Pembuatan Passport? <span class="text-red-500">*</span>
                                                </label>

                                                {{-- TOMBOL YA / TIDAK --}}
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
                                                            value="{{ $jamaah['pendampingan_fee_petugas'] ?? 0 }}"
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
                                                <span class="text-gray-400">(Pembayaran + Pendapatan Perusahaan)</span>
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

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Hubungan
                                                Keluarga</label>
                                            <select name="jamaahs[0][hubungan_keluarga]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                                <option value="">-- Pilih Hubungan --</option>
                                                @foreach ($hubunganOptions as $option)
                                                    <option value="{{ $option }}">{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Alamat</label>
                                            <input type="text" name="jamaahs[0][alamat]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                placeholder="Alamat lengkap">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Kota Asal</label>
                                            <select name="jamaahs[0][kota_asal]"
                                                class="kota-asal-select w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
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
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Nomor
                                                Paspor</label>
                                            <input type="text" name="jamaahs[0][nomor_paspor]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Pulau</label>
                                            <input type="text" name="jamaahs[0][pulau]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-100 text-sm cursor-not-allowed"
                                                readonly>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Bandara
                                                Keberangkatan</label>
                                            <input type="text" name="jamaahs[0][bandara_keberangkatan]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-100 text-sm cursor-not-allowed"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                            <input type="checkbox" name="jamaahs[0][is_kepala_keluarga]" value="1"
                                                checked
                                                class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                                            <span>Kepala Keluarga / Kelompok</span>
                                        </label>
                                    </div>

                                    <!-- Pembuatan Passport (TOMBOL YA/TIDAK) - default TIDAK -->
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
                                            <span class="font-bold text-blue-600" id="total-pendampingan-0">Rp 0</span>
                                            <span class="text-gray-400">(Pembayaran + Pendapatan Perusahaan)</span>
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
                let value = element.value.replace(/[^,\d]/g, '');
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                element.value = value;
            }

            function handleZeroFocus(element) {
                if (element.value === '0' || element.value === '') {
                    element.value = '';
                }
            }

            function handleZeroBlur(element) {
                if (element.value === '' || element.value === '0') {
                    element.value = '0';
                }
                formatRupiah(element);
            }

            let jamaahIndex = {{ count(old('jamaahs', [0])) }};

            // ==========================================
            // SET PEMBUATAN PASSPORT (TOMBOL YA/TIDAK)
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
                    // Aktifkan tombol YA
                    btnYa.className = 'px-6 py-2 text-sm font-medium bg-green-500 text-white transition-colors';
                    btnTidak.className =
                        'px-6 py-2 text-sm font-medium bg-white text-gray-600 hover:bg-gray-50 transition-colors border-l border-gray-300';
                    if (container) {
                        container.classList.remove('hidden');
                    }
                } else {
                    // Aktifkan tombol TIDAK
                    btnYa.className = 'px-6 py-2 text-sm font-medium bg-white text-gray-600 hover:bg-gray-50 transition-colors';
                    btnTidak.className =
                        'px-6 py-2 text-sm font-medium bg-red-500 text-white transition-colors border-l border-gray-300';
                    if (container) {
                        container.classList.add('hidden');
                    }
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
                    Math.round(totalDiskon / jumlahJamaah) :
                    0;

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

                var oldKeberangkatan = document.getElementById('id_produk_keberangkatan_hidden').value;
                var oldHargaBulanan = document.getElementById('id_produk_harga_bulanan_hidden').value;
                var html = '';

                filtered.forEach(function(k) {
                    var isSelected = oldKeberangkatan && parseInt(oldKeberangkatan) === parseInt(k.id);
                    var isChecked = isSelected ? 'checked' : '';
                    var currentKuotaText = 'Tidak Terbatas';
                    var currentKuotaColor = 'text-gray-500';
                    var currentIdHarga = k.id_harga_default;

                    if (isSelected && oldHargaBulanan) {
                        currentIdHarga = oldHargaBulanan;
                        var hargaMatch = globalHargaBulananList.find(h => parseInt(h.id) === parseInt(oldHargaBulanan));
                        if (hargaMatch) {
                            currentKuotaText = hargaMatch.kuota_formatted;
                            currentKuotaColor = hargaMatch.is_full ? 'text-red-600' : (hargaMatch.kuota_sisa !== -1 &&
                                hargaMatch.kuota_sisa <= 3 ? 'text-orange-600' : 'text-blue-600');
                        }
                    } else {
                        currentKuotaText = k.kuota_default_formatted;
                        currentKuotaColor = k.kuota_default_is_full ? 'text-red-600' : 'text-blue-600';
                    }

                    var optionsHtml = '<option value="">-- Sama dengan keberangkatan (' + k.bulan_formatted +
                        ') --</option>';
                    globalHargaBulananList.forEach(function(h) {
                        var selected = (parseInt(h.id) === parseInt(currentIdHarga)) ? 'selected' : '';
                        var isFull = h.is_full ? true : false;
                        var disabledAttr = isFull ? 'disabled' : '';
                        var kuotaLabel = h.is_full ? 'PENUH (' + h.kuota + ')' : h.kuota_formatted;
                        var styleAttr = isFull ? 'style="color:#ef4444;font-style:italic;"' : '';
                        optionsHtml += '<option value="' + h.id +
                            '" data-bulan="' + h.bulan +
                            '" data-tahun="' + h.tahun +
                            '" data-harga="' + h.harga +
                            '" data-kuota="' + (h.kuota || 0) +
                            '" data-is-full="' + (isFull ? '1' : '0') + '" ' +
                            selected + ' ' + disabledAttr + ' ' + styleAttr + '>' +
                            h.bulan_formatted + ' - ' + h.harga_formatted +
                            ' (Kuota: ' + kuotaLabel + ')' + (isFull ? ' ⛔' : '') + '</option>';
                    });

                    var radioDisabled = k.kuota_default_is_full ? 'disabled' : '';
                    var radioWarning = k.kuota_default_is_full ?
                        '<p class="text-xs text-red-600 font-medium mt-1"><i class="fas fa-ban mr-1"></i>Kuota PENUH</p>' :
                        '';

                    html += `
                        <div class="keberangkatan-item bg-white rounded-xl border border-gray-200 overflow-hidden" data-keberangkatan-id="${k.id}">
                            <div class="flex items-start gap-3 p-3">
                                <input type="radio"
                                       name="keberangkatan_radio"
                                       class="keberangkatan-radio mt-1 w-4 h-4 text-yellow-500 focus:ring-yellow-500"
                                       value="${k.id}"
                                       data-keberangkatan-id="${k.id}"
                                       data-bulan="${k.bulan}"
                                       data-tahun="${k.tahun}"
                                       data-harga-default="${k.harga_default}"
                                       data-kuota-default="${k.kuota_default || 0}"
                                       data-kuota-default-is-full="${k.kuota_default_is_full ? '1' : '0'}"
                                       ${isChecked}
                                       ${radioDisabled}>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-700">
                                        <i class="fas fa-plane text-blue-500 mr-1"></i>
                                        ${k.keberangkatan_formatted}
                                    </p>
                                    <p class="text-xs text-gray-500">Harga default: ${k.harga_default_formatted}</p>
                                    ${radioWarning}
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-400">Harga dipilih:</p>
                                    <p class="text-sm font-bold text-green-600 harga-display-${k.id}">Rp ${k.harga_default.toLocaleString('id-ID')}</p>
                                    <p class="text-xs ${currentKuotaColor} mt-0.5 kuota-display-${k.id}">
                                        <i class="fas fa-users text-[10px]"></i> Kuota: ${currentKuotaText}
                                    </p>
                                </div>
                            </div>

                            <div class="keberangkatan-harga-section border-t border-gray-100 bg-gray-50 p-3" style="display: ${isSelected ? 'block' : 'none'};">
                                <label class="block text-xs font-medium text-gray-600 mb-1.5">
                                    <i class="fas fa-tag text-yellow-500 mr-1"></i>
                                    Harga diambil dari bulan/tahun:
                                </label>
                                <select class="harga-bulanan-select w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500"
                                        data-keberangkatan-id="${k.id}">
                                    ${optionsHtml}
                                </select>
                                <div class="flex items-center justify-between mt-1">
                                    <p class="text-xs text-gray-400">
                                        <i class="fas fa-info-circle mr-1"></i> Pilih harga dari bulan/tahun berbeda (opsional)
                                    </p>
                                    <p class="text-xs font-medium kuota-info-${k.id} ${currentKuotaColor}">
                                        <i class="fas fa-users mr-1"></i>
                                        Kuota: <span class="kuota-info-val-${k.id}">${currentKuotaText}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    `;
                });

                container.innerHTML = html;
                document.querySelectorAll('.keberangkatan-radio').forEach(radio => radio.addEventListener('change',
                    handleKeberangkatanChange));
                document.querySelectorAll('.harga-bulanan-select').forEach(sel => sel.addEventListener('change',
                    handleHargaBulananChange));

                if (oldKeberangkatan) {
                    var checked = document.querySelector('.keberangkatan-radio:checked');
                    if (checked) handleKeberangkatanChange({
                        target: checked
                    });
                }
            }

            function handleKeberangkatanChange(e) {
                var radio = e.target;
                if (!radio) return;
                var selectedId = radio.dataset.keberangkatanId;
                document.getElementById('id_produk_keberangkatan_hidden').value = selectedId;
                document.getElementById('bulan_keberangkatan_hidden').value = radio.dataset.bulan;
                document.getElementById('tahun_keberangkatan_hidden').value = radio.dataset.tahun;

                document.querySelectorAll('.keberangkatan-item').forEach(item => {
                    var itemId = item.dataset.keberangkatanId;
                    var hargaSection = item.querySelector('.keberangkatan-harga-section');
                    hargaSection.style.display = (itemId === selectedId) ? 'block' : 'none';
                });
                updateHargaDisplay(selectedId);
            }

            function handleHargaBulananChange(e) {
                var sel = e.target;
                var keberangkatanId = sel.dataset.keberangkatanId;
                var item = sel.closest('.keberangkatan-item');
                var radio = item.querySelector('.keberangkatan-radio');
                var selectedOption = sel.options[sel.selectedIndex];
                var hargaBulananId = sel.value;
                var bulanHarga, tahunHarga, harga, kuota, isFull;

                if (hargaBulananId) {
                    bulanHarga = selectedOption.dataset.bulan;
                    tahunHarga = selectedOption.dataset.tahun;
                    harga = selectedOption.dataset.harga;
                    kuota = parseInt(selectedOption.dataset.kuota) || 0;
                    isFull = selectedOption.dataset.isFull === '1';
                } else {
                    bulanHarga = radio.dataset.bulan;
                    tahunHarga = radio.dataset.tahun;
                    harga = radio.dataset.hargaDefault;
                    kuota = parseInt(radio.dataset.kuotaDefault) || 0;
                    isFull = radio.dataset.kuotaDefaultIsFull === '1';
                }

                if (isFull) {
                    alert('⚠️ Kuota untuk bulan/tahun ini sudah PENUH!');
                    sel.value = '';
                    return;
                }

                if (hargaBulananId) {
                    document.getElementById('id_produk_harga_bulanan_hidden').value = hargaBulananId;
                    document.getElementById('bulan_harga_hidden').value = bulanHarga;
                    document.getElementById('tahun_harga_hidden').value = tahunHarga;
                    var bulanName = getBulanName(bulanHarga);
                    document.getElementById('hargaBulanTahun').textContent = bulanName + ' ' + tahunHarga;
                    document.getElementById('hargaProdukDisplay').textContent = 'Rp ' + parseInt(harga).toLocaleString('id-ID');
                    document.getElementById('hargaInfo').classList.remove('hidden');
                } else {
                    document.getElementById('id_produk_harga_bulanan_hidden').value = '';
                    document.getElementById('bulan_harga_hidden').value = bulanHarga;
                    document.getElementById('tahun_harga_hidden').value = tahunHarga;
                    updateHargaDisplay(keberangkatanId);
                }

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

                var kuotaDisplay = item.querySelector('.kuota-display-' + keberangkatanId);
                if (kuotaDisplay) {
                    kuotaDisplay.className = 'text-xs mt-0.5 kuota-display-' + keberangkatanId + ' ' + kuotaColor;
                    kuotaDisplay.innerHTML = '<i class="fas fa-users text-[10px]"></i> Kuota: ' + kuotaText;
                }
                var kuotaInfoVal = item.querySelector('.kuota-info-val-' + keberangkatanId);
                if (kuotaInfoVal) {
                    kuotaInfoVal.textContent = kuotaText;
                    kuotaInfoVal.className = kuotaColor;
                }
            }

            function updateHargaDisplay(keberangkatanId) {
                var radio = document.querySelector('.keberangkatan-radio[data-keberangkatan-id="' + keberangkatanId + '"]');
                if (radio) {
                    var harga = radio.dataset.hargaDefault;
                    var bulanName = getBulanName(radio.dataset.bulan);
                    document.getElementById('hargaBulanTahun').textContent = bulanName + ' ' + radio.dataset.tahun;
                    document.getElementById('hargaProdukDisplay').textContent = 'Rp ' + parseInt(harga).toLocaleString('id-ID');
                    document.getElementById('hargaInfo').classList.remove('hidden');
                    document.getElementById('bulan_harga_hidden').value = radio.dataset.bulan;
                    document.getElementById('tahun_harga_hidden').value = radio.dataset.tahun;
                    document.getElementById('id_produk_harga_bulanan_hidden').value = '';
                }
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

            function addJamaah() {
                const container = document.getElementById('jamaahContainer');
                const row = document.createElement('div');
                row.className = 'jamaah-row bg-gray-50 rounded-xl p-4 mb-3 border border-gray-200';
                row.id = 'jamaah-row-' + jamaahIndex;
                row.innerHTML = `
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium text-gray-700">Jamaah #${jamaahIndex + 1}</span>
                        <button type="button" onclick="removeJamaah(this)" class="text-red-500 hover:text-red-700 text-sm">
                            <i class="fas fa-times"></i> Hapus
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="jamaahs[${jamaahIndex}][nama_lengkap]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">NIK <span class="text-red-500">*</span></label>
                            <input type="text" name="jamaahs[${jamaahIndex}][nik]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                placeholder="16 digit NIK" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Nama Ayah <span class="text-red-500">*</span></label>
                            <input type="text" name="jamaahs[${jamaahIndex}][nama_ayah]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Pekerjaan <span class="text-red-500">*</span></label>
                            <input type="text" name="jamaahs[${jamaahIndex}][pekerjaan]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="jamaahs[${jamaahIndex}][jenis_kelamin]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required>
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Telepon <span class="text-red-500">*</span></label>
                            <input type="text" name="jamaahs[${jamaahIndex}][telepon]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">WhatsApp <span class="text-red-500">*</span></label>
                            <input type="text" name="jamaahs[${jamaahIndex}][wa]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah"
                                placeholder="08xxxxxxxxxx" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                            <input type="text" name="jamaahs[${jamaahIndex}][tempat_lahir]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                            <input type="date" name="jamaahs[${jamaahIndex}][tanggal_lahir]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Hubungan Keluarga</label>
                            <select name="jamaahs[${jamaahIndex}][hubungan_keluarga]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                <option value="">-- Pilih Hubungan --</option>
                                @foreach ($hubunganOptions as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Alamat</label>
                            <input type="text" name="jamaahs[${jamaahIndex}][alamat]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                placeholder="Alamat lengkap">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Kota Asal</label>
                            <select name="jamaahs[${jamaahIndex}][kota_asal]"
                                class="kota-asal-select w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                <option value="">-- Pilih Kota Asal --</option>
                                @foreach ($kotaAsals as $kota)
                                    <option value="{{ $kota->nama_kota }}" data-pulau="{{ $kota->pulau }}"
                                        data-bandara="{{ $kota->bandara_terdekat }}">
                                        {{ $kota->nama_kota }} - {{ $kota->provinsi ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Nomor Paspor</label>
                            <input type="text" name="jamaahs[${jamaahIndex}][nomor_paspor]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Pulau</label>
                            <input type="text" name="jamaahs[${jamaahIndex}][pulau]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-100 text-sm cursor-not-allowed" readonly>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Bandara Keberangkatan</label>
                            <input type="text" name="jamaahs[${jamaahIndex}][bandara_keberangkatan]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-100 text-sm cursor-not-allowed" readonly>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="jamaahs[${jamaahIndex}][is_kepala_keluarga]" value="1"
                                class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                            <span>Kepala Keluarga / Kelompok</span>
                        </label>
                    </div>

                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">
                                Apakah ada Pembuatan Passport? <span class="text-red-500">*</span>
                            </label>

                            <div class="inline-flex rounded-lg overflow-hidden border border-gray-300">
                                <button type="button" onclick="setPembuatanPassportKeluarga(${jamaahIndex}, 1)"
                                    id="btnYa-${jamaahIndex}"
                                    class="px-6 py-2 text-sm font-medium bg-white text-gray-600 hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-check mr-1"></i> YA
                                </button>
                                <button type="button" onclick="setPembuatanPassportKeluarga(${jamaahIndex}, 0)"
                                    id="btnTidak-${jamaahIndex}"
                                    class="px-6 py-2 text-sm font-medium bg-red-500 text-white transition-colors border-l border-gray-300">
                                    <i class="fas fa-times mr-1"></i> TIDAK
                                </button>
                            </div>
                            <input type="hidden" name="jamaahs[${jamaahIndex}][is_pembuatan_passport]"
                                id="is_pembuatan_passport-${jamaahIndex}" value="0">

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
                                    <input type="text" name="jamaahs[${jamaahIndex}][pendampingan_fee]" value="0"
                                        oninput="formatRupiah(this); updateTotalPendampingan(${jamaahIndex})"
                                        onfocus="handleZeroFocus(this)" onblur="handleZeroBlur(this)"
                                        class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                        placeholder="0">
                                </div>
                            </div>
                            <div id="feePetugasContainer-${jamaahIndex}" class="hidden">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Pendapatan Perusahaan <span class="text-gray-400 text-xs">(fee petugas)</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                    <input type="text" name="jamaahs[${jamaahIndex}][pendampingan_fee_petugas]"
                                        id="pendampingan_fee_petugas-${jamaahIndex}" value="0"
                                        oninput="formatRupiah(this); updateTotalPendampingan(${jamaahIndex})"
                                        onfocus="handleZeroFocus(this)" onblur="handleZeroBlur(this)"
                                        class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                        placeholder="0">
                                </div>
                            </div>
                        </div>

                        <div class="mt-2 text-xs text-gray-500">
                            Total Pembuatan Passport:
                            <span class="font-bold text-blue-600" id="total-pendampingan-${jamaahIndex}">Rp 0</span>
                            <span class="text-gray-400">(Pembayaran + Pendapatan Perusahaan)</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-3 pt-3 border-t border-gray-200">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">File KTP/KK</label>
                            <input type="file" name="jamaahs[${jamaahIndex}][file_ktp_kk]" accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                            <p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">File Vaksin</label>
                            <input type="file" name="jamaahs[${jamaahIndex}][file_vaksin]" accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                            <p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">File Visa</label>
                            <input type="file" name="jamaahs[${jamaahIndex}][file_visa]" accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                            <p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">File Paspor</label>
                            <input type="file" name="jamaahs[${jamaahIndex}][file_paspor]" accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                            <p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p>
                        </div>
                    </div>
                `;
                container.appendChild(row);
                jamaahIndex++;
                hitungDiskonKelompok();
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
                } else {
                    alert('Minimal harus ada 1 jamaah!');
                }
            }

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

                        var selectedRadio = document.querySelector('.keberangkatan-radio:checked');
                        if (selectedRadio) {
                            var isFullRadio = selectedRadio.dataset.kuotaDefaultIsFull === '1';
                            var hargaSel = document.querySelector(
                                '.harga-bulanan-select[data-keberangkatan-id="' + selectedRadio.dataset
                                .keberangkatanId + '"]');
                            var selectedHargaFull = false;
                            if (hargaSel && hargaSel.value) {
                                var opt = hargaSel.options[hargaSel.selectedIndex];
                                selectedHargaFull = opt.dataset.isFull === '1';
                            }
                            if (isFullRadio || selectedHargaFull) {
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
