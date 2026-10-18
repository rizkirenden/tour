@extends('layouts.app')

@section('title', 'Edit Jamaah - Arrum Tour')
@section('page-title', 'Edit Jamaah')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.jamaah.index') }}" class="text-gray-500 hover:text-yellow-600">Transaksional</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.jamaah.index') }}" class="text-gray-500 hover:text-yellow-600">Jamaah</a>
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
                    <h5 class="text-sm font-semibold text-gray-700">Form Edit Jamaah</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Edit data jamaah</p>
                </div>
                <a href="{{ route('transaksional.jamaah.show', $jamaah->id_jamaah) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <i class="fas fa-eye mr-2"></i> Lihat Detail
                </a>
            </div>

            <form action="{{ route('transaksional.jamaah.update', $jamaah->id_jamaah) }}" method="POST" id="jamaahForm"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <!-- Informasi Pribadi -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-user text-yellow-500 mr-2"></i> Informasi Pribadi
                            <span class="ml-2 text-xs text-red-500 font-normal">(semua wajib diisi)</span>
                        </h6>

                        <div id="infoPribadiError" class="hidden mb-3 bg-red-50 border border-red-200 rounded-xl p-3">
                            <p class="text-sm text-red-600 flex items-start">
                                <i class="fas fa-exclamation-triangle mt-0.5 mr-2"></i>
                                <span id="infoPribadiErrorText">Lengkapi semua field Informasi Pribadi yang wajib
                                    diisi.</span>
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap"
                                    value="{{ old('nama_lengkap', $jamaah->nama_lengkap) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm wajib-pribadi"
                                    required>
                                @error('nama_lengkap')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    NIK <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nik" id="nik" value="{{ old('nik', $jamaah->nik) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm wajib-pribadi"
                                    placeholder="16 digit NIK" required>
                                @error('nik')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Ayah <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_ayah" id="nama_ayah"
                                    value="{{ old('nama_ayah', $jamaah->nama_ayah) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm wajib-pribadi"
                                    required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Pekerjaan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="pekerjaan" id="pekerjaan"
                                    value="{{ old('pekerjaan', $jamaah->pekerjaan) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm wajib-pribadi"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <select name="jenis_kelamin" id="jenis_kelamin"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm wajib-pribadi"
                                    required>
                                    <option value="">Pilih</option>
                                    <option value="L"
                                        {{ old('jenis_kelamin', $jamaah->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="P"
                                        {{ old('jenis_kelamin', $jamaah->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Telepon <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="telepon" id="telepon"
                                    value="{{ old('telepon', $jamaah->telepon) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm wajib-pribadi"
                                    placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    WhatsApp <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="wa" id="wa" value="{{ old('wa', $jamaah->wa) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm wajib-pribadi"
                                    placeholder="08xxxxxxxxxx" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tempat Lahir <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="tempat_lahir" id="tempat_lahir"
                                    value="{{ old('tempat_lahir', $jamaah->tempat_lahir) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm wajib-pribadi"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tanggal Lahir <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                    value="{{ old('tanggal_lahir', $jamaah->tanggal_lahir ? $jamaah->tanggal_lahir->format('Y-m-d') : '') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm wajib-pribadi"
                                    required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Alamat <span class="text-red-500">*</span>
                            </label>
                            <textarea name="alamat" id="alamat" rows="2"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm wajib-pribadi"
                                required>{{ old('alamat', $jamaah->alamat) }}</textarea>
                        </div>
                    </div>

                    <!-- Informasi Passport -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-passport text-yellow-500 mr-2"></i> Informasi Passport
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Passport</label>
                                <input type="text" name="nomor_paspor"
                                    value="{{ old('nomor_paspor', $jamaah->nomor_paspor) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Terbit
                                    Passport</label>
                                <input type="date" name="paspor_terbit"
                                    value="{{ old('paspor_terbit', $jamaah->paspor_terbit ? $jamaah->paspor_terbit->format('Y-m-d') : '') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Berakhir
                                    Passport</label>
                                <input type="date" name="paspor_expired"
                                    value="{{ old('paspor_expired', $jamaah->paspor_expired ? $jamaah->paspor_expired->format('Y-m-d') : '') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Diterbitkan Di</label>
                                <input type="text" name="paspor_diterbitkan_di"
                                    value="{{ old('paspor_diterbitkan_di', $jamaah->paspor_diterbitkan_di) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Kota/Negara">
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Produk & Keberangkatan -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-plane text-yellow-500 mr-2"></i> Informasi Produk & Keberangkatan
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Produk Paket <span class="text-red-500">*</span>
                                </label>
                                <select name="produk_paket" id="produk_paket"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    required>
                                    <option value="">-- Pilih Produk Paket --</option>
                                    @foreach ($produkPakets as $produk)
                                        <option value="{{ $produk->nama_produk }}"
                                            data-kode="{{ $produk->kode_produk ?? 'PKT' }}"
                                            {{ old('produk_paket', $jamaah->produk_paket) == $produk->nama_produk ? 'selected' : '' }}>
                                            {{ $produk->nama_produk }}
                                            ({{ $produk->durasi_hari }} Hari)
                                        </option>
                                    @endforeach
                                </select>
                                @error('produk_paket')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">ID Keberangkatan</label>
                                <input type="text" name="id_keberangkatan" id="id_keberangkatan"
                                    value="{{ old('id_keberangkatan', $jamaah->id_keberangkatan) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl bg-gray-100 text-sm cursor-not-allowed"
                                    readonly>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kota Asal <span class="text-red-500">*</span>
                                </label>
                                <select name="kota_asal" id="kota_asal"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    required>
                                    <option value="">-- Pilih Kota Asal --</option>
                                    @foreach ($kotaAsals as $kota)
                                        <option value="{{ $kota->nama_kota }}" data-pulau="{{ $kota->pulau }}"
                                            data-bandara="{{ $kota->bandara_terdekat }}"
                                            {{ old('kota_asal', $jamaah->kota_asal) == $kota->nama_kota ? 'selected' : '' }}>
                                            {{ $kota->nama_kota }} - {{ $kota->provinsi ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kota_asal')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Pulau</label>
                                <input type="text" name="pulau" id="pulau"
                                    value="{{ old('pulau', $jamaah->pulau) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl bg-gray-100 text-sm cursor-not-allowed"
                                    readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Bandara Keberangkatan</label>
                                <input type="text" name="bandara_keberangkatan" id="bandara_keberangkatan"
                                    value="{{ old('bandara_keberangkatan', $jamaah->bandara_keberangkatan) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl bg-gray-100 text-sm cursor-not-allowed"
                                    readonly>
                            </div>
                        </div>

                        <!-- Pilih Tahun Dulu, Baru Keberangkatan -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Bulan & Tahun Keberangkatan <span class="text-red-500">*</span>
                            </label>

                            <div id="placeholder-belum-pilih"
                                class="bg-gray-50 rounded-xl p-4 border border-gray-200 text-center hidden">
                                <p class="text-sm text-gray-400">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Pilih produk paket terlebih dahulu
                                </p>
                            </div>

                            <div id="step-1-container" class="mb-3">
                                <label class="block text-xs font-medium text-gray-600 mb-1.5">
                                    <i class="fas fa-calendar text-yellow-500 mr-1"></i>
                                    Langkah 1: Pilih Tahun
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
                            </div>

                            <p class="text-xs text-gray-400 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Pilih salah satu keberangkatan. Harga bisa diambil dari bulan/tahun berbeda.
                            </p>
                            @error('keberangkatan_data')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Agent & Pendampingan -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-handshake text-yellow-500 mr-2"></i> Agent & Pembuatan passport
                        </h6>

                        <div class="mb-4 bg-blue-50 rounded-xl p-4 border border-blue-100">
                            <h6 class="text-xs font-semibold text-blue-700 mb-3 flex items-center">
                                <i class="fas fa-user-tie text-blue-500 mr-1"></i> Agent
                            </h6>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Agent</label>
                                    <input type="text" name="agent_name"
                                        value="{{ old('agent_name', $jamaah->agent_name) }}"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                        placeholder="Nama Agent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Fee Agent</label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                        <input type="text" name="fee_agent" id="fee_agent"
                                            value="{{ old('fee_agent', number_format($jamaah->fee_agent, 0, ',', '.')) }}"
                                            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                            placeholder="0" oninput="formatRupiah(this)" onfocus="clearZero(this)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                            <h6 class="text-xs font-semibold text-green-700 mb-3 flex items-center">
                                <i class="fas fa-users text-green-500 mr-1"></i> Pembuatan Passport
                            </h6>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nama Pendamping Pembuatan passport
                                    </label>
                                    <input type="text" name="pendampingan_nama" id="pendampingan_nama"
                                        value="{{ old('pendampingan_nama', $jamaah->pendampingan_nama) }}"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                        placeholder="Nama Pendamping (kosongkan jika tidak ada)">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Pembayaran passport
                                    </label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                        <input type="text" name="pendampingan_fee" id="pendampingan_fee"
                                            value="{{ old('pendampingan_fee', number_format($jamaah->pendampingan_fee, 0, ',', '.')) }}"
                                            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                            placeholder="0" oninput="formatRupiah(this); updateTotalPendampingan();"
                                            onfocus="clearZero(this)">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 pt-3 border-t border-green-200">
                                <button type="button" id="btnShowFeePetugas" onclick="showFeePetugas()"
                                    class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm font-medium
                                    {{ $jamaah->pendampingan_fee_petugas > 0 ? 'hidden' : '' }}">
                                    <i class="fas fa-plus mr-2"></i> Tambah Pendapatan Perusahaan
                                </button>
                                <button type="button" id="btnHideFeePetugas" onclick="hideFeePetugas()"
                                    class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium
                                    {{ $jamaah->pendampingan_fee_petugas > 0 ? '' : 'hidden' }}">
                                    <i class="fas fa-times mr-2"></i> Hapus Pendapatan Perusahaan
                                </button>
                            </div>

                            <div id="feePetugasContainer" class="mt-3"
                                style="display: {{ $jamaah->pendampingan_fee_petugas > 0 ? 'block' : 'none' }};">
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                            Pendapatan perusahaan
                                        </label>
                                        <div class="relative">
                                            <span
                                                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                            <input type="text" name="pendampingan_fee_petugas"
                                                id="pendampingan_fee_petugas"
                                                value="{{ old('pendampingan_fee_petugas', number_format($jamaah->pendampingan_fee_petugas, 0, ',', '.')) }}"
                                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                                placeholder="0" oninput="formatRupiah(this); updateTotalPendampingan();"
                                                onfocus="clearZero(this)">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 pt-3 border-t border-green-200">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-700">
                                        <i class="fas fa-calculator text-green-500 mr-1"></i>
                                        Total Pembuatan Passport
                                    </p>
                                    <p class="text-sm font-bold text-blue-600" id="totalPendampinganDisplay">
                                        Rp
                                        {{ number_format($jamaah->pendampingan_fee + $jamaah->pendampingan_fee_petugas, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Diskon -->
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
                                            {{ old('id_diskon', $jamaah->id_diskon) == $diskon->id_diskon ? 'selected' : '' }}>
                                            {{ $diskon->nama_diskon }}
                                            (-{{ $diskon->nilai_diskon_formatted }})
                                            - Sisa Kuota: {{ $diskon->sisa_kuota }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan Diskon</label>
                                <input type="text" name="keterangan_diskon"
                                    value="{{ old('keterangan_diskon', $jamaah->keterangan_diskon) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Catatan diskon (opsional)">
                            </div>
                        </div>
                    </div>

                    <!-- Upload Dokumen -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-upload text-yellow-500 mr-2"></i> Upload Dokumen
                            <span class="ml-2 text-xs text-gray-400">(opsional, support gambar/PDF)</span>
                        </h6>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">File KTP/KK</label>
                            @if ($jamaah->file_ktp_kk_url)
                                <div class="mb-2 flex items-center gap-3">
                                    @if ($jamaah->file_ktp_kk_type == 'pdf')
                                        <i class="fas fa-file-pdf text-2xl text-red-500"></i>
                                    @else
                                        <img src="{{ $jamaah->file_ktp_kk_url }}" alt="KTP/KK"
                                            class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                    @endif
                                    <div>
                                        <p class="text-sm text-gray-600">File saat ini</p>
                                        <a href="{{ $jamaah->file_ktp_kk_url }}" target="_blank"
                                            class="text-xs text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-eye mr-1"></i> Lihat File
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <input type="file" name="file_ktp_kk" id="file_ktp_kk"
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100"
                                        onchange="previewFile(this, 'preview_ktp_kk')">
                                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengganti</p>
                                </div>
                                <div id="preview_ktp_kk"
                                    class="hidden flex items-center gap-2 px-3 py-2 bg-green-50 rounded-lg border border-green-200">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    <span class="text-sm text-green-700" id="preview_ktp_kk_name"></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">File Vaksin</label>
                            @if ($jamaah->file_vaksin_url)
                                <div class="mb-2 flex items-center gap-3">
                                    @if ($jamaah->file_vaksin_type == 'pdf')
                                        <i class="fas fa-file-pdf text-2xl text-red-500"></i>
                                    @else
                                        <img src="{{ $jamaah->file_vaksin_url }}" alt="Vaksin"
                                            class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                    @endif
                                    <div>
                                        <p class="text-sm text-gray-600">File saat ini</p>
                                        <a href="{{ $jamaah->file_vaksin_url }}" target="_blank"
                                            class="text-xs text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-eye mr-1"></i> Lihat File
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <input type="file" name="file_vaksin" id="file_vaksin"
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100"
                                        onchange="previewFile(this, 'preview_vaksin')">
                                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengganti</p>
                                </div>
                                <div id="preview_vaksin"
                                    class="hidden flex items-center gap-2 px-3 py-2 bg-green-50 rounded-lg border border-green-200">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    <span class="text-sm text-green-700" id="preview_vaksin_name"></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">File Visa</label>
                            @if ($jamaah->file_visa_url)
                                <div class="mb-2 flex items-center gap-3">
                                    @if ($jamaah->file_visa_type == 'pdf')
                                        <i class="fas fa-file-pdf text-2xl text-red-500"></i>
                                    @else
                                        <img src="{{ $jamaah->file_visa_url }}" alt="Visa"
                                            class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                    @endif
                                    <div>
                                        <p class="text-sm text-gray-600">File saat ini</p>
                                        <a href="{{ $jamaah->file_visa_url }}" target="_blank"
                                            class="text-xs text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-eye mr-1"></i> Lihat File
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <input type="file" name="file_visa" id="file_visa" accept=".pdf,.jpg,.jpeg,.png"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100"
                                        onchange="previewFile(this, 'preview_visa')">
                                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengganti</p>
                                </div>
                                <div id="preview_visa"
                                    class="hidden flex items-center gap-2 px-3 py-2 bg-green-50 rounded-lg border border-green-200">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    <span class="text-sm text-green-700" id="preview_visa_name"></span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">File Passport</label>
                            @if ($jamaah->file_paspor_url)
                                <div class="mb-2 flex items-center gap-3">
                                    @if ($jamaah->file_paspor_type == 'pdf')
                                        <i class="fas fa-file-pdf text-2xl text-red-500"></i>
                                    @else
                                        <img src="{{ $jamaah->file_paspor_url }}" alt="Passport"
                                            class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                    @endif
                                    <div>
                                        <p class="text-sm text-gray-600">File saat ini</p>
                                        <a href="{{ $jamaah->file_paspor_url }}" target="_blank"
                                            class="text-xs text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-eye mr-1"></i> Lihat File
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <input type="file" name="file_paspor" id="file_paspor"
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100"
                                        onchange="previewFile(this, 'preview_paspor')">
                                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengganti</p>
                                </div>
                                <div id="preview_paspor"
                                    class="hidden flex items-center gap-2 px-3 py-2 bg-green-50 rounded-lg border border-green-200">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    <span class="text-sm text-green-700" id="preview_paspor_name"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan Tambahan -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-sticky-note text-yellow-500 mr-2"></i> Catatan Tambahan
                        </h6>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan</label>
                            <textarea name="catatan_tambahan" rows="2"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">{{ old('catatan_tambahan', $jamaah->catatan_tambahan) }}</textarea>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4">
                        <a href="{{ route('transaksional.jamaah.index') }}"
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
        // ==========================================
        // DATA GLOBAL
        // ==========================================
        var globalKeberangkatanList = [];
        var globalHargaBulananList = [];
        var globalSelectedData = @json(old('keberangkatan_data', $selectedKeberangkatan ?? []));

        // ==========================================
        // FORMAT RUPIAH — kosong tetap kosong, leading zero dihapus
        // ==========================================
        function formatRupiah(element) {
            let raw = element.value.replace(/[^,\d]/g, '');

            if (raw === '') {
                element.value = '';
                return;
            }

            raw = raw.replace(/^0+(?=\d)/, '');

            element.value = raw.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // ==========================================
        // HAPUS '0' saat field difokuskan
        // ==========================================
        function clearZero(element) {
            if (element.value === '0' || element.value === '0.000' || element.value === '') {
                element.value = '';
            }
        }

        // ==========================================
        // VALIDASI INFORMASI PRIBADI
        // ==========================================
        function validateInformasiPribadi() {
            var fields = document.querySelectorAll('.wajib-pribadi');
            var labels = {
                nama_lengkap: 'Nama Lengkap',
                nik: 'NIK',
                nama_ayah: 'Nama Ayah',
                pekerjaan: 'Pekerjaan',
                jenis_kelamin: 'Jenis Kelamin',
                telepon: 'Telepon',
                wa: 'WhatsApp',
                tempat_lahir: 'Tempat Lahir',
                tanggal_lahir: 'Tanggal Lahir',
                alamat: 'Alamat'
            };
            var missing = [];

            fields.forEach(function(field) {
                var val = (field.value || '').trim();
                var isInvalid = !val;

                if (isInvalid) {
                    field.classList.add('border-red-500', 'ring-2', 'ring-red-300');
                    field.classList.remove('border-gray-200');
                    if (labels[field.name]) missing.push(labels[field.name]);
                } else {
                    field.classList.remove('border-red-500', 'ring-2', 'ring-red-300');
                    field.classList.add('border-gray-200');
                }
            });

            var errorBox = document.getElementById('infoPribadiError');
            var errorText = document.getElementById('infoPribadiErrorText');

            if (missing.length > 0) {
                errorText.textContent = 'Field wajib belum diisi: ' + missing.join(', ') + '.';
                errorBox.classList.remove('hidden');

                var firstInvalid = document.querySelector('.wajib-pribadi.border-red-500');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    firstInvalid.focus();
                }
                return false;
            }

            errorBox.classList.add('hidden');
            return true;
        }

        // ==========================================
        // SHOW/HIDE FEE PETUGAS
        // ==========================================
        function showFeePetugas() {
            document.getElementById('feePetugasContainer').style.display = 'block';
            document.getElementById('btnShowFeePetugas').classList.add('hidden');
            document.getElementById('btnHideFeePetugas').classList.remove('hidden');
            document.getElementById('pendampingan_fee_petugas').focus();
            updateTotalPendampingan();
        }

        function hideFeePetugas() {
            document.getElementById('feePetugasContainer').style.display = 'none';
            document.getElementById('btnShowFeePetugas').classList.remove('hidden');
            document.getElementById('btnHideFeePetugas').classList.add('hidden');
            document.getElementById('pendampingan_fee_petugas').value = '0';
            updateTotalPendampingan();
        }

        function updateTotalPendampingan() {
            var feeInput = document.getElementById('pendampingan_fee');
            var petugasInput = document.getElementById('pendampingan_fee_petugas');
            var totalDisplay = document.getElementById('totalPendampinganDisplay');
            var container = document.getElementById('feePetugasContainer');

            if (feeInput && totalDisplay) {
                var fee = parseInt(feeInput.value.replace(/\./g, '')) || 0;
                var petugas = 0;
                if (container.style.display !== 'none') {
                    petugas = parseInt(petugasInput.value.replace(/\./g, '')) || 0;
                }
                totalDisplay.textContent = 'Rp ' + (fee + petugas).toLocaleString('id-ID');
            }
        }

        function previewFile(input, previewId) {
            var preview = document.getElementById(previewId);
            var nameSpan = document.getElementById(previewId + '_name');

            if (input.files && input.files[0]) {
                var file = input.files[0];
                var ext = file.name.split('.').pop().toLowerCase();
                var isPDF = ext === 'pdf';
                var isImage = ['jpg', 'jpeg', 'png'].includes(ext);

                if (isPDF || isImage) {
                    preview.classList.remove('hidden');
                    if (nameSpan) {
                        nameSpan.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                    }
                    if (isImage) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            preview.innerHTML = `
                                <img src="${e.target.result}" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                <span class="text-sm text-green-700 ml-2">${file.name}</span>
                            `;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        preview.innerHTML = `
                            <i class="fas fa-file-pdf text-3xl text-red-500"></i>
                            <span class="text-sm text-green-700 ml-2">${file.name}</span>
                        `;
                    }
                }
            }
        }

        // ==========================================
        // LOAD KEBERANGKATAN (EDIT)
        // ==========================================
        function loadKeberangkatan() {
            var produkPaket = document.getElementById('produk_paket');
            var tahunSelect = document.getElementById('filter-tahun');
            var step1Container = document.getElementById('step-1-container');
            var step2Container = document.getElementById('step-2-container');
            var placeholder = document.getElementById('placeholder-belum-pilih');
            var container = document.getElementById('keberangkatan-container');

            tahunSelect.innerHTML = '<option value="">-- Pilih Tahun --</option>';
            step2Container.classList.add('hidden');
            container.innerHTML = '';

            if (!produkPaket.value) {
                step1Container.classList.add('hidden');
                placeholder.classList.remove('hidden');
                return;
            }

            placeholder.classList.add('hidden');
            step1Container.classList.remove('hidden');
            tahunSelect.innerHTML = '<option value="">-- Memuat... --</option>';

            fetch(
                    `{{ route('transaksional.jamaah.keberangkatan-by-produk') }}?produk_paket=${encodeURIComponent(produkPaket.value)}`)
                .then(res => res.json())
                .then(data => {
                    if (!data.success || !data.keberangkatan || data.keberangkatan.length === 0) {
                        tahunSelect.innerHTML = '<option value="">-- Tidak ada data --</option>';
                        return;
                    }

                    globalKeberangkatanList = data.keberangkatan;
                    globalHargaBulananList = data.harga_bulanan || [];

                    var tahunSet = new Set();
                    globalKeberangkatanList.forEach(function(k) {
                        tahunSet.add(parseInt(k.tahun));
                    });

                    var tahunList = Array.from(tahunSet).sort((a, b) => a - b);

                    tahunSelect.innerHTML = '<option value="">-- Pilih Tahun --</option>';
                    tahunList.forEach(function(tahun) {
                        var option = document.createElement('option');
                        option.value = tahun;
                        option.textContent = tahun;
                        tahunSelect.appendChild(option);
                    });

                    if (globalSelectedData && globalSelectedData.length > 0) {
                        var selectedItem = globalSelectedData[0];
                        var selectedTahun = parseInt(selectedItem.tahun_keberangkatan);

                        if (selectedTahun && tahunList.includes(selectedTahun)) {
                            tahunSelect.value = selectedTahun;
                            renderKeberangkatanByTahun(selectedTahun);
                        } else if (tahunList.length > 0) {
                            tahunSelect.value = tahunList[0];
                            renderKeberangkatanByTahun(tahunList[0]);
                        }
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    tahunSelect.innerHTML = '<option value="">-- Gagal memuat --</option>';
                });
        }

        // ==========================================
        // RENDER KEBERANGKATAN BY TAHUN
        // ==========================================
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
            if (globalSelectedData && globalSelectedData.length > 0) {
                var selectedItem = globalSelectedData[0];
                var oldId = parseInt(selectedItem.id_produk_keberangkatan);

                selectedKeberangkatan = filtered.find(function(k) {
                    return parseInt(k.id) === oldId;
                });
            }

            var html = '';
            filtered.forEach(function(k) {
                var isSelected = selectedKeberangkatan && parseInt(selectedKeberangkatan
                    .id_produk_keberangkatan) === parseInt(k.id);
                var isChecked = isSelected ? 'checked' : '';

                var currentHarga = isSelected ? parseInt(selectedKeberangkatan.harga) : parseInt(k.harga_default);
                var currentIdHarga = isSelected ? (selectedKeberangkatan.id_produk_harga_bulanan || '') : (k
                    .id_harga_default || '');
                var currentBulanHarga = isSelected ? selectedKeberangkatan.bulan_harga : k.bulan;
                var currentTahunHarga = isSelected ? selectedKeberangkatan.tahun_harga : k.tahun;

                var currentKuotaText = 'Tidak Terbatas';
                var currentKuotaColor = 'text-gray-500';
                if (isSelected && selectedKeberangkatan.id_produk_harga_bulanan) {
                    var hargaMatch = globalHargaBulananList.find(function(h) {
                        return parseInt(h.id) === parseInt(selectedKeberangkatan.id_produk_harga_bulanan);
                    });
                    if (hargaMatch) {
                        currentKuotaText = hargaMatch.kuota_formatted;
                        currentKuotaColor = hargaMatch.is_full ? 'text-red-600' :
                            (hargaMatch.kuota_sisa !== -1 && hargaMatch.kuota_sisa <= 3 ? 'text-orange-600' :
                                'text-blue-600');
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
                        ' (Kuota: ' + kuotaLabel + ')' +
                        (isFull ? ' ⛔' : '') +
                        '</option>';
                });

                var radioDisabled = k.kuota_default_is_full ? 'disabled' : '';
                var radioWarning = k.kuota_default_is_full ?
                    '<p class="text-xs text-red-600 font-medium mt-1"><i class="fas fa-ban mr-1"></i>Kuota PENUH — tidak bisa dipilih</p>' :
                    '';

                var hiddenDisabled = isChecked ? '' : 'disabled';

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
                                <p class="text-sm font-bold text-green-600 harga-display-${k.id}">Rp ${currentHarga.toLocaleString('id-ID')}</p>
                                <p class="text-xs ${currentKuotaColor} mt-0.5 kuota-display-${k.id}">
                                    <i class="fas fa-users text-[10px]"></i> Kuota: ${currentKuotaText}
                                </p>
                            </div>
                        </div>

                        <div class="keberangkatan-harga-section border-t border-gray-100 bg-gray-50 p-3" style="display: ${isChecked ? 'block' : 'none'};">
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">
                                <i class="fas fa-tag text-yellow-500 mr-1"></i>
                                Harga diambil dari bulan/tahun:
                            </label>
                            <select class="harga-bulanan-select w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                    data-keberangkatan-id="${k.id}">
                                ${optionsHtml}
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
                        </div>

                        <input type="hidden" class="hidden-id-keberangkatan-${k.id}" name="keberangkatan_data[0][id_produk_keberangkatan]" value="${k.id}" ${hiddenDisabled}>
                        <input type="hidden" class="hidden-id-harga-${k.id}" name="keberangkatan_data[0][id_produk_harga_bulanan]" value="${currentIdHarga || ''}" ${hiddenDisabled}>
                        <input type="hidden" class="hidden-bulan-harga-${k.id}" name="keberangkatan_data[0][bulan_harga]" value="${currentBulanHarga}" ${hiddenDisabled}>
                        <input type="hidden" class="hidden-tahun-harga-${k.id}" name="keberangkatan_data[0][tahun_harga]" value="${currentTahunHarga}" ${hiddenDisabled}>
                        <input type="hidden" class="hidden-harga-${k.id}" name="keberangkatan_data[0][harga]" value="${currentHarga}" ${hiddenDisabled}>
                    </div>
                `;
            });

            container.innerHTML = html;

            document.querySelectorAll('.keberangkatan-radio').forEach(function(radio) {
                radio.addEventListener('change', handleKeberangkatanChange);
            });
            document.querySelectorAll('.harga-bulanan-select').forEach(function(sel) {
                sel.addEventListener('change', handleHargaBulananChange);
            });

            var checkedRadio = document.querySelector('.keberangkatan-radio:checked');
            if (checkedRadio) {
                handleKeberangkatanChange({
                    target: checkedRadio
                });

                var item = checkedRadio.closest('.keberangkatan-item');
                var sel = item.querySelector('.harga-bulanan-select');
                if (sel) {
                    handleHargaBulananChange({
                        target: sel
                    });
                }
            }
        }

        // ==========================================
        // HANDLE KEBERANGKATAN CHANGE
        // ==========================================
        function handleKeberangkatanChange(e) {
            var radio = e.target;
            if (!radio) return;

            var selectedId = radio.dataset.keberangkatanId;

            document.querySelectorAll('.keberangkatan-item').forEach(function(item) {
                var itemId = item.dataset.keberangkatanId;
                var hargaSection = item.querySelector('.keberangkatan-harga-section');
                var hiddenInputs = item.querySelectorAll('input[type="hidden"]');

                if (itemId === selectedId) {
                    hargaSection.style.display = 'block';
                    hiddenInputs.forEach(function(hi) {
                        hi.disabled = false;
                    });
                } else {
                    hargaSection.style.display = 'none';
                    hiddenInputs.forEach(function(hi) {
                        hi.disabled = true;
                    });
                }
            });
        }

        // ==========================================
        // HANDLE HARGA BULANAN CHANGE
        // ==========================================
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
                alert('⚠️ Kuota untuk bulan/tahun ini sudah PENUH!\n\nSilakan pilih bulan/tahun lain.');
                sel.value = '';
                return;
            }

            item.querySelector('.hidden-id-harga-' + keberangkatanId).value = hargaBulananId || '';
            item.querySelector('.hidden-bulan-harga-' + keberangkatanId).value = bulanHarga;
            item.querySelector('.hidden-tahun-harga-' + keberangkatanId).value = tahunHarga;
            item.querySelector('.hidden-harga-' + keberangkatanId).value = harga;

            var display = item.querySelector('.harga-display-' + keberangkatanId);
            if (display) {
                display.textContent = 'Rp ' + parseInt(harga || 0).toLocaleString('id-ID');
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

        // ==========================================
        // EVENT LISTENERS
        // ==========================================
        document.getElementById('filter-tahun').addEventListener('change', function() {
            renderKeberangkatanByTahun(this.value);
        });

        document.getElementById('produk_paket').addEventListener('change', function() {
            globalSelectedData = [];
            loadKeberangkatan();
        });

        document.getElementById('kota_asal').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            document.getElementById('pulau').value = selectedOption.dataset.pulau || '';
            document.getElementById('bandara_keberangkatan').value = selectedOption.dataset.bandara || '';
        });

        // ==========================================
        // LIVE REMOVE WARNING SAAT USER MENGISI
        // ==========================================
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('wajib-pribadi') && e.target.value.trim() !== '') {
                e.target.classList.remove('border-red-500', 'ring-2', 'ring-red-300');
                e.target.classList.add('border-gray-200');
            }
        });

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('wajib-pribadi') && e.target.value.trim() !== '') {
                e.target.classList.remove('border-red-500', 'ring-2', 'ring-red-300');
                e.target.classList.add('border-gray-200');
            }
        });

        // ==========================================
        // DOM CONTENT LOADED
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            var kotaAsal = document.getElementById('kota_asal');
            if (kotaAsal.value) {
                var selectedOption = kotaAsal.options[kotaAsal.selectedIndex];
                document.getElementById('pulau').value = selectedOption.dataset.pulau || '';
                document.getElementById('bandara_keberangkatan').value = selectedOption.dataset.bandara || '';
            }

            var produkPaket = document.getElementById('produk_paket');
            if (produkPaket && produkPaket.value) {
                loadKeberangkatan();
            }

            updateTotalPendampingan();

            var form = document.getElementById('jamaahForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!validateInformasiPribadi()) {
                        e.preventDefault();
                        return false;
                    }

                    var rupiahInputs = ['fee_agent', 'pendampingan_fee', 'pendampingan_fee_petugas'];
                    rupiahInputs.forEach(function(id) {
                        var input = document.getElementById(id);
                        if (input) {
                            var raw = input.value.replace(/\./g, '');
                            input.value = raw === '' ? '0' : raw;
                        }
                    });

                    var selectedRadio = document.querySelector('.keberangkatan-radio:checked');
                    if (selectedRadio) {
                        var isFullRadio = selectedRadio.dataset.kuotaDefaultIsFull === '1';
                        var hargaSel = document.querySelector(
                            '.harga-bulanan-select[data-keberangkatan-id="' + selectedRadio.dataset
                            .keberangkatanId + '"]'
                        );
                        var selectedHargaFull = false;
                        if (hargaSel && hargaSel.value) {
                            var opt = hargaSel.options[hargaSel.selectedIndex];
                            selectedHargaFull = opt.dataset.isFull === '1';
                        }

                        if (isFullRadio || selectedHargaFull) {
                            e.preventDefault();
                            alert(
                                '⚠️ Kuota bulan/tahun yang dipilih sudah PENUH!\n\nSilakan pilih bulan/tahun lain.');
                            return false;
                        }

                        var selectedId = selectedRadio.dataset.keberangkatanId;
                        var selectedItem = document.querySelector(
                            '.keberangkatan-item[data-keberangkatan-id="' + selectedId + '"]'
                        );
                        if (selectedItem) {
                            selectedItem.querySelectorAll('input[type="hidden"]').forEach(function(hi) {
                                hi.disabled = false;
                            });
                        }
                    }
                });
            }
        });
    </script>
@endpush
