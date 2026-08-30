@extends('layouts.app')

@section('title', 'Tambah Keluarga - Arrum Tour')
@section('page-title', 'Tambah Keluarga')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.keluarga.index') }}" class="text-gray-500 hover:text-yellow-600">Transaksional</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.keluarga.index') }}" class="text-gray-500 hover:text-yellow-600">Keluarga</a>
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
                <h5 class="text-sm font-semibold text-gray-700">Form Tambah Keluarga</h5>
                <p class="text-xs text-gray-400 mt-0.5">Lengkapi data keluarga dan anggota</p>
            </div>

            <form action="{{ route('transaksional.keluarga.store') }}" method="POST" id="keluargaForm"
                enctype="multipart/form-data">
                @csrf

                <div class="p-6 space-y-6">
                    <!-- Informasi Keluarga -->
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Bulan Keberangkatan <span class="text-red-500">*</span>
                                </label>
                                <select name="bulan_keberangkatan" id="bulan_keberangkatan"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    required>
                                    <option value="">Pilih Bulan</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}"
                                            {{ old('bulan_keberangkatan') == $i ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                                @error('bulan_keberangkatan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tahun Keberangkatan <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="tahun_keberangkatan" id="tahun_keberangkatan"
                                    value="{{ old('tahun_keberangkatan', date('Y')) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    min="2000" max="{{ date('Y') + 10 }}" required>
                                @error('tahun_keberangkatan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Info Harga Produk (Auto dari Database) -->
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
                            <div id="hargaWarning" class="mt-2 hidden">
                                <p class="text-xs text-yellow-600">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    <span id="hargaWarningText"></span>
                                </p>
                            </div>
                            <div class="mt-3 pt-3 border-t border-green-200">
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Harga diambil otomatis dari database berdasarkan produk, bulan, dan tahun yang dipilih
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Agent & Pendampingan -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-handshake text-yellow-500 mr-2"></i> Agent & Pendampingan
                        </h6>

                        <!-- Agent Section -->
                        <div class="mb-4 bg-blue-50 rounded-xl p-4 border border-blue-100">
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
                                            placeholder="0" oninput="formatRupiah(this)">
                                        <p class="text-xs text-gray-400 mt-1">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Fee Agent adalah informasi tambahan dan <span
                                                class="text-red-500 font-medium">TIDAK</span> termasuk dalam total tagihan
                                        </p>
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
                                    <i class="fas fa-info-circle mr-1"></i> Diskon berlaku untuk seluruh anggota keluarga
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan Diskon</label>
                                <input type="text" name="keterangan_diskon" value="{{ old('keterangan_diskon') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Catatan diskon (opsional)">
                            </div>
                        </div>
                    </div>

                    <!-- Jamaah -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-user text-yellow-500 mr-2"></i> Daftar Jamaah
                                <span class="ml-2 text-xs text-gray-400">(minimal 1 jamaah)</span>
                            </h6>
                            <button type="button" onclick="addJamaah()"
                                class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm font-medium">
                                <i class="fas fa-plus mr-2"></i> Tambah Jamaah
                            </button>
                        </div>

                        <div id="jamaahContainer">
                            @php
                                $oldJamaahs = old('jamaahs', []);
                            @endphp

                            @if (count($oldJamaahs) > 0)
                                @foreach ($oldJamaahs as $index => $jamaah)
                                    <div class="jamaah-row bg-gray-50 rounded-xl p-4 mb-3 border border-gray-200">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-sm font-medium text-gray-700">Jamaah
                                                #{{ $index + 1 }}</span>
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
                                                <input type="text" name="jamaahs[{{ $index }}][nama_lengkap]"
                                                    value="{{ $jamaah['nama_lengkap'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                    required>
                                            </div>
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
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Jenis
                                                    Kelamin</label>
                                                <select name="jamaahs[{{ $index }}][jenis_kelamin]"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="L"
                                                        {{ ($jamaah['jenis_kelamin'] ?? '') == 'L' ? 'selected' : '' }}>
                                                        Laki-laki</option>
                                                    <option value="P"
                                                        {{ ($jamaah['jenis_kelamin'] ?? '') == 'P' ? 'selected' : '' }}>
                                                        Perempuan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Telepon</label>
                                                <input type="text" name="jamaahs[{{ $index }}][telepon]"
                                                    value="{{ $jamaah['telepon'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                    placeholder="08xxxxxxxxxx">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Kota
                                                    Asal</label>
                                                <select name="jamaahs[{{ $index }}][kota_asal]"
                                                    class="kota-asal-select"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
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
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Alamat</label>
                                                <input type="text" name="jamaahs[{{ $index }}][alamat]"
                                                    value="{{ $jamaah['alamat'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                    placeholder="Alamat lengkap">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Nomor
                                                    Paspor</label>
                                                <input type="text" name="jamaahs[{{ $index }}][nomor_paspor]"
                                                    value="{{ $jamaah['nomor_paspor'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Tempat
                                                    Lahir</label>
                                                <input type="text" name="jamaahs[{{ $index }}][tempat_lahir]"
                                                    value="{{ $jamaah['tempat_lahir'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal
                                                    Lahir</label>
                                                <input type="date" name="jamaahs[{{ $index }}][tanggal_lahir]"
                                                    value="{{ $jamaah['tanggal_lahir'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
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
                                            <div class="flex items-center gap-4 pt-2">
                                                <label
                                                    class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                                    <input type="checkbox"
                                                        name="jamaahs[{{ $index }}][is_kepala_keluarga]"
                                                        value="1"
                                                        {{ $jamaah['is_kepala_keluarga'] ?? false ? 'checked' : '' }}
                                                        class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                                                    <span>Kepala Keluarga</span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Pendampingan (Informasi) -->
                                        <div
                                            class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3 pt-3 border-t border-gray-200">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Nama
                                                    Pendamping</label>
                                                <input type="text"
                                                    name="jamaahs[{{ $index }}][pendampingan_nama]"
                                                    value="{{ $jamaah['pendampingan_nama'] ?? '' }}"
                                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                    placeholder="Nama pendamping">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Fee
                                                    Pendamping</label>
                                                <div class="relative">
                                                    <span
                                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                                    <input type="text"
                                                        name="jamaahs[{{ $index }}][pendampingan_fee]"
                                                        value="{{ $jamaah['pendampingan_fee'] ?? 0 }}"
                                                        oninput="formatRupiah(this)"
                                                        class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                        placeholder="0">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Fee
                                                    Petugas</label>
                                                <div class="relative">
                                                    <span
                                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                                    <input type="text"
                                                        name="jamaahs[{{ $index }}][pendampingan_fee_petugas]"
                                                        value="{{ $jamaah['pendampingan_fee_petugas'] ?? 0 }}"
                                                        oninput="formatRupiah(this)"
                                                        class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                        placeholder="0">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Total Pendampingan per Jamaah -->
                                        <div class="mt-2 text-xs text-gray-500">
                                            Total Pendampingan:
                                            <span class="font-bold text-blue-600"
                                                id="total-pendampingan-{{ $index }}">
                                                Rp
                                                {{ isset($jamaah['pendampingan_fee']) && isset($jamaah['pendampingan_fee_petugas']) ? number_format($jamaah['pendampingan_fee'] + $jamaah['pendampingan_fee_petugas'], 0, ',', '.') : '0' }}
                                            </span>
                                            <span class="text-gray-400">(Fee Pendamping + Fee Petugas)</span>
                                        </div>

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
                                <!-- Default 1 jamaah -->
                                <div class="jamaah-row bg-gray-50 rounded-xl p-4 mb-3 border border-gray-200">
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
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                required>
                                        </div>
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
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis
                                                Kelamin</label>
                                            <select name="jamaahs[0][jenis_kelamin]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                                <option value="">-- Pilih --</option>
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Telepon</label>
                                            <input type="text" name="jamaahs[0][telepon]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                placeholder="08xxxxxxxxxx">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Kota Asal</label>
                                            <select name="jamaahs[0][kota_asal]" class="kota-asal-select"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
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
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Alamat</label>
                                            <input type="text" name="jamaahs[0][alamat]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                placeholder="Alamat lengkap">
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
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Tempat
                                                Lahir</label>
                                            <input type="text" name="jamaahs[0][tempat_lahir]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal
                                                Lahir</label>
                                            <input type="date" name="jamaahs[0][tanggal_lahir]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
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
                                        <div class="flex items-center gap-4 pt-2">
                                            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                                <input type="checkbox" name="jamaahs[0][is_kepala_keluarga]"
                                                    value="1" checked
                                                    class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                                                <span>Kepala Keluarga</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Pendampingan (Informasi) -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3 pt-3 border-t border-gray-200">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Nama
                                                Pendamping</label>
                                            <input type="text" name="jamaahs[0][pendampingan_nama]"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                placeholder="Nama pendamping">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Fee
                                                Pendamping</label>
                                            <div class="relative">
                                                <span
                                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                                <input type="text" name="jamaahs[0][pendampingan_fee]" value="0"
                                                    oninput="formatRupiah(this)"
                                                    class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                    placeholder="0">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Fee Petugas</label>
                                            <div class="relative">
                                                <span
                                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                                <input type="text" name="jamaahs[0][pendampingan_fee_petugas]"
                                                    value="0" oninput="formatRupiah(this)"
                                                    class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                    placeholder="0">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Total Pendampingan -->
                                    <div class="mt-2 text-xs text-gray-500">
                                        Total Pendampingan:
                                        <span class="font-bold text-blue-600" id="total-pendampingan-0">Rp 0</span>
                                        <span class="text-gray-400">(Fee Pendamping + Fee Petugas)</span>
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

                    <!-- Buttons -->
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
            // Format Rupiah
            function formatRupiah(element) {
                let value = element.value.replace(/[^,\d]/g, '');
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                element.value = value;
            }

            let jamaahIndex = {{ count(old('jamaahs', [0])) }};

            // ==========================================
            // AMBIL HARGA PRODUK BERDASARKAN BULAN & TAHUN
            // ==========================================
            function getHargaProduk() {
                const produkPaket = document.getElementById('produk_paket');
                const bulan = document.getElementById('bulan_keberangkatan');
                const tahun = document.getElementById('tahun_keberangkatan');
                const hargaInfo = document.getElementById('hargaInfo');
                const hargaDisplay = document.getElementById('hargaProdukDisplay');
                const hargaBulanTahun = document.getElementById('hargaBulanTahun');
                const warningDiv = document.getElementById('hargaWarning');
                const warningText = document.getElementById('hargaWarningText');
                const btnSubmit = document.getElementById('btnSubmit');

                if (!produkPaket.value || !bulan.value || !tahun.value) {
                    hargaInfo.classList.add('hidden');
                    btnSubmit.disabled = true;
                    btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                    return;
                }

                hargaDisplay.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...';
                hargaInfo.classList.remove('hidden');
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');

                fetch(
                        `{{ route('transaksional.get-harga-produk-by-bulan') }}?produk_paket=${encodeURIComponent(produkPaket.value)}&bulan=${bulan.value}&tahun=${tahun.value}`
                        )
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const bulanName = getBulanName(data.bulan || bulan.value);
                            hargaDisplay.textContent = data.harga_formatted;
                            hargaBulanTahun.textContent = `${bulanName} ${data.tahun || tahun.value}`;

                            if (data.warning) {
                                warningDiv.classList.remove('hidden');
                                warningText.textContent = data.warning;
                            } else {
                                warningDiv.classList.add('hidden');
                            }

                            // Enable submit jika harga ditemukan
                            btnSubmit.disabled = false;
                            btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                        } else {
                            hargaDisplay.textContent = 'Harga tidak tersedia';
                            warningDiv.classList.remove('hidden');
                            warningText.textContent = data.error || 'Tidak ada harga yang tersedia untuk produk ini';
                            btnSubmit.disabled = true;
                            btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        hargaDisplay.textContent = 'Error memuat harga';
                        btnSubmit.disabled = true;
                        btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                    });
            }

            function getBulanName(bulan) {
                const namaBulan = [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];
                return namaBulan[parseInt(bulan) - 1] || bulan;
            }

            // Auto fill pulau dan bandara berdasarkan kota asal
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

            // Update total pendampingan
            document.addEventListener('input', function(e) {
                if (e.target && (e.target.name && e.target.name.includes('pendampingan_fee') ||
                        e.target.name && e.target.name.includes('pendampingan_fee_petugas'))) {
                    const row = e.target.closest('.jamaah-row');
                    if (row) {
                        const index = row.querySelector('input[name$="[nama_lengkap]"]').name.match(/\d+/)[0];
                        const feeInput = row.querySelector(`input[name="jamaahs[${index}][pendampingan_fee]"]`);
                        const petugasInput = row.querySelector(
                            `input[name="jamaahs[${index}][pendampingan_fee_petugas]"]`);
                        const totalSpan = document.getElementById(`total-pendampingan-${index}`);

                        if (feeInput && petugasInput && totalSpan) {
                            const fee = parseInt(feeInput.value.replace(/\./g, '')) || 0;
                            const petugas = parseInt(petugasInput.value.replace(/\./g, '')) || 0;
                            const total = fee + petugas;
                            totalSpan.textContent = 'Rp ' + total.toLocaleString('id-ID');
                        }
                    }
                }
            });

            function addJamaah() {
                const container = document.getElementById('jamaahContainer');
                const row = document.createElement('div');
                row.className = 'jamaah-row bg-gray-50 rounded-xl p-4 mb-3 border border-gray-200';
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
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm" required>
                        </div>
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
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Kelamin</label>
                            <select name="jamaahs[${jamaahIndex}][jenis_kelamin]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Telepon</label>
                            <input type="text" name="jamaahs[${jamaahIndex}][telepon]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm" placeholder="08xxxxxxxxxx">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Kota Asal</label>
                            <select name="jamaahs[${jamaahIndex}][kota_asal]" class="kota-asal-select"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
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
                            <label class="block text-xs font-medium text-gray-600 mb-1">Alamat</label>
                            <input type="text" name="jamaahs[${jamaahIndex}][alamat]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm" placeholder="Alamat lengkap">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Nomor Paspor</label>
                            <input type="text" name="jamaahs[${jamaahIndex}][nomor_paspor]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tempat Lahir</label>
                            <input type="text" name="jamaahs[${jamaahIndex}][tempat_lahir]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                            <input type="date" name="jamaahs[${jamaahIndex}][tanggal_lahir]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
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
                        <div class="flex items-center gap-4 pt-2">
                            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                <input type="checkbox" name="jamaahs[${jamaahIndex}][is_kepala_keluarga]" value="1"
                                    class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                                <span>Kepala Keluarga</span>
                            </label>
                        </div>
                    </div>

                    <!-- Pendampingan (Informasi) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3 pt-3 border-t border-gray-200">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Nama Pendamping</label>
                            <input type="text" name="jamaahs[${jamaahIndex}][pendampingan_nama]"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                placeholder="Nama pendamping">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Fee Pendamping</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                <input type="text" name="jamaahs[${jamaahIndex}][pendampingan_fee]"
                                    value="0" oninput="formatRupiah(this)"
                                    class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                    placeholder="0">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Fee Petugas</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                <input type="text" name="jamaahs[${jamaahIndex}][pendampingan_fee_petugas]"
                                    value="0" oninput="formatRupiah(this)"
                                    class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                    placeholder="0">
                            </div>
                        </div>
                    </div>

                    <!-- Total Pendampingan -->
                    <div class="mt-2 text-xs text-gray-500">
                        Total Pendampingan: 
                        <span class="font-bold text-blue-600" id="total-pendampingan-${jamaahIndex}">Rp 0</span>
                        <span class="text-gray-400">(Fee Pendamping + Fee Petugas)</span>
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
            }

            function removeJamaah(button) {
                const row = button.closest('.jamaah-row');
                const container = document.getElementById('jamaahContainer');
                if (container.children.length > 1) {
                    row.remove();
                    const rows = container.querySelectorAll('.jamaah-row');
                    rows.forEach((r, idx) => {
                        const label = r.querySelector('.text-sm.font-medium');
                        if (label) {
                            label.textContent = `Jamaah #${idx + 1}`;
                        }
                    });
                } else {
                    alert('Minimal harus ada 1 jamaah!');
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Trigger auto fill untuk setiap kota asal yang sudah ada
                document.querySelectorAll('.kota-asal-select').forEach(function(select) {
                    if (select.value) {
                        const event = new Event('change');
                        select.dispatchEvent(event);
                    }
                });

                // Event listener untuk produk, bulan, tahun
                const produkPaket = document.getElementById('produk_paket');
                const bulan = document.getElementById('bulan_keberangkatan');
                const tahun = document.getElementById('tahun_keberangkatan');

                if (produkPaket) {
                    produkPaket.addEventListener('change', getHargaProduk);
                }
                if (bulan) {
                    bulan.addEventListener('change', getHargaProduk);
                }
                if (tahun) {
                    tahun.addEventListener('change', getHargaProduk);
                }

                // Panggil pertama kali jika ada nilai
                if (produkPaket && produkPaket.value && bulan && bulan.value && tahun && tahun.value) {
                    getHargaProduk();
                }

                // Form submit - clean semua input rupiah
                const form = document.getElementById('keluargaForm');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        const rupiahInputs = ['fee_agent'];
                        rupiahInputs.forEach(function(id) {
                            const input = document.getElementById(id);
                            if (input) {
                                input.value = input.value.replace(/\./g, '');
                            }
                        });
                        // Clean pendampingan fee di jamaahs
                        document.querySelectorAll(
                            '[name$="[pendampingan_fee]"], [name$="[pendampingan_fee_petugas]"]').forEach(
                            function(input) {
                                if (input.value) {
                                    input.value = input.value.replace(/\./g, '');
                                }
                            });
                    });
                }
            });
        </script>
    @endpush
@endsection
