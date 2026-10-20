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

                        {{-- ✅ NEW: Kota/Kabupaten, Kecamatan, Kelurahan --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kota / Kabupaten <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="kota_kabupaten" id="kota_kabupaten"
                                    value="{{ old('kota_kabupaten', $jamaah->kota_kabupaten) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm wajib-pribadi"
                                    placeholder="Contoh: Kota Bandung / Kab. Sleman" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kecamatan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="kecamatan" id="kecamatan"
                                    value="{{ old('kecamatan', $jamaah->kecamatan) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm wajib-pribadi"
                                    placeholder="Contoh: Cibeunying Kaler" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kelurahan / Desa <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="kelurahan" id="kelurahan"
                                    value="{{ old('kelurahan', $jamaah->kelurahan) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm wajib-pribadi"
                                    placeholder="Contoh: Cigadung" required>
                            </div>
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

                    <!-- Agent & Pembuatan passport -->
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
                                <i class="fas fa-passport text-green-500 mr-1"></i> Pembuatan Passport
                            </h6>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Apakah ada Pembuatan Passport? <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-6">
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="is_pembuatan_passport" value="1"
                                            class="w-4 h-4 text-green-500 focus:ring-green-500"
                                            {{ old('is_pembuatan_passport', $jamaah->is_pembuatan_passport) == '1' ? 'checked' : '' }}
                                            onchange="togglePembuatanPassport()">
                                        <span class="text-sm font-medium text-gray-700">YA</span>
                                    </label>
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="is_pembuatan_passport" value="0"
                                            class="w-4 h-4 text-red-500 focus:ring-red-500"
                                            {{ old('is_pembuatan_passport', $jamaah->is_pembuatan_passport) == '0' ? 'checked' : '' }}
                                            onchange="togglePembuatanPassport()">
                                        <span class="text-sm font-medium text-gray-700">TIDAK</span>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Pilih <b>YA</b> jika ada pendapatan perusahaan dari pembuatan passport.
                                    Pilih <b>TIDAK</b> jika jamaah sudah punya passport (biaya bisa dikosongkan).
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Pembayaran Passport
                                    <span class="text-xs text-gray-400 font-normal">(kosongkan jika jamaah sudah punya
                                        passport)</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                    <input type="text" name="pendampingan_fee" id="pendampingan_fee"
                                        value="{{ old('pendampingan_fee', number_format($jamaah->pendampingan_fee, 0, ',', '.')) }}"
                                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                        placeholder="0" oninput="formatRupiah(this); updateTotalPendampingan();"
                                        onfocus="clearZero(this)">
                                </div>
                            </div>

                            <div id="feePetugasContainer"
                                class="mb-3 {{ old('is_pembuatan_passport', $jamaah->is_pembuatan_passport) == '1' ? '' : 'hidden' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Pendapatan Perusahaan
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                    <input type="text" name="pendampingan_fee_petugas" id="pendampingan_fee_petugas"
                                        value="{{ old('pendampingan_fee_petugas', number_format($jamaah->pendampingan_fee_petugas, 0, ',', '.')) }}"
                                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                        placeholder="0" oninput="formatRupiah(this); updateTotalPendampingan();"
                                        onfocus="clearZero(this)">
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

                    <!-- UPGRADE HOTEL -->
                    <div class="border-b border-gray-200 pb-4">
                        <div class="flex items-center justify-between mb-2">
                            <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-hotel text-orange-500 mr-2"></i>
                                Upgrade Hotel & Kamar
                                <span class="ml-2 text-xs text-gray-400 font-normal">(opsional)</span>
                            </h6>
                            <button type="button" onclick="toggleUpgradeHotel()"
                                class="text-xs text-blue-500 hover:text-blue-700 font-medium">
                                <i class="fas fa-plus mr-1"></i> Atur Hotel
                            </button>
                        </div>

                        <div id="upgradeHotelContainer"
                            class="hidden bg-orange-50 rounded-lg p-3 border border-orange-200">
                            <p class="text-xs text-orange-700 mb-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Biaya upgrade hotel akan ditambahkan ke total tagihan jamaah.
                            </p>

                            <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg bg-white p-2 space-y-2">
                                @foreach ($hotelOptions as $hotel)
                                    @php
                                        $existingUpgrades = collect($selectedUpgradeHotels)->where(
                                            'id_hotel',
                                            $hotel->id_hotel,
                                        );
                                        $isChecked = $existingUpgrades->count() > 0;
                                    @endphp
                                    <div class="hotel-upgrade-item border border-gray-200 rounded-lg"
                                        data-hotel-id="{{ $hotel->id_hotel }}">
                                        <label
                                            class="flex items-center gap-2 p-2 cursor-pointer hover:bg-gray-50 rounded-t-lg">
                                            <input type="checkbox"
                                                class="upgrade-hotel-checkbox w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500"
                                                data-hotel-id="{{ $hotel->id_hotel }}"
                                                onchange="toggleHotelDetail({{ $hotel->id_hotel }})"
                                                {{ $isChecked ? 'checked' : '' }}>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-700">{{ $hotel->nama_hotel }}</p>
                                                <p class="text-xs text-gray-500">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                    {{ $hotel->kota ?? '-' }}
                                                    @if ($hotel->bintang)
                                                        · {{ $hotel->bintang_text }}
                                                    @endif
                                                </p>
                                            </div>
                                        </label>

                                        <div id="hotelDetailContainer-{{ $hotel->id_hotel }}"
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
                                    Total Upgrade Hotel:
                                </span>
                                <span id="totalUpgradeHotel" class="text-sm font-bold text-orange-600">
                                    {{ $jamaah->total_upgrade_hotel_formatted }}
                                </span>
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
        // GLOBAL DATA
        // ==========================================
        var globalKeberangkatanList = [];
        var globalHargaBulananList = [];
        var globalSelectedData = @json(old('keberangkatan_data', $selectedKeberangkatan ?? []));

        var JAMAHA_ID = {{ $jamaah->id_jamaah }};

        var OLD_ID_HARGA = (globalSelectedData && globalSelectedData[0]) ?
            parseInt(globalSelectedData[0].id_produk_harga_bulanan || 0) : 0;
        var OLD_ID_KEBERANGKATAN = (globalSelectedData && globalSelectedData[0]) ?
            parseInt(globalSelectedData[0].id_produk_keberangkatan || 0) : 0;

        // ✅ Existing upgrade hotels untuk auto-restore
        var EXISTING_UPGRADE_HOTELS = @json($selectedUpgradeHotels ?? []);

        // ==========================================
        // FORMAT RUPIAH
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

        function clearZero(element) {
            if (element.value === '0' || element.value === '0.000' || element.value === '') {
                element.value = '';
            }
        }

        function formatRupiahKamar(input) {
            let value = input.value.replace(/[^,\d]/g, '').replace(/^Rp\s*/, '').replace(/\./g, '');
            if (value === '' || isNaN(value)) value = '0';
            const number = parseInt(value);
            input.value = 'Rp ' + number.toLocaleString('id-ID');
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
                alamat: 'Alamat',
                kota_kabupaten: 'Kota / Kabupaten',
                kecamatan: 'Kecamatan',
                kelurahan: 'Kelurahan / Desa'
            };
            var missing = [];

            fields.forEach(function(field) {
                var val = (field.value || '').trim();
                if (!val) {
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
        // TOGGLE PEMBUATAN PASSPORT
        // ==========================================
        function togglePembuatanPassport() {
            var isYa = document.querySelector('input[name="is_pembuatan_passport"]:checked');
            var container = document.getElementById('feePetugasContainer');
            var petugasInput = document.getElementById('pendampingan_fee_petugas');

            if (isYa && isYa.value === '1') {
                container.classList.remove('hidden');
                container.style.display = 'block';
            } else {
                container.classList.add('hidden');
                container.style.display = 'none';
                if (petugasInput) petugasInput.value = '0';
            }
            updateTotalPendampingan();
        }

        function updateTotalPendampingan() {
            var feeInput = document.getElementById('pendampingan_fee');
            var petugasInput = document.getElementById('pendampingan_fee_petugas');
            var totalDisplay = document.getElementById('totalPendampinganDisplay');
            var isYa = document.querySelector('input[name="is_pembuatan_passport"]:checked');

            if (feeInput && totalDisplay) {
                var fee = parseInt(feeInput.value.replace(/\./g, '')) || 0;
                var petugas = 0;
                if (isYa && isYa.value === '1' && petugasInput) {
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
        // LOAD KEBERANGKATAN (EDIT — dengan exclude)
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

            var url =
                `{{ route('transaksional.jamaah.keberangkatan-by-produk') }}?produk_paket=${encodeURIComponent(produkPaket.value)}`;
            if (JAMAHA_ID) {
                url += `&exclude_jamaah_id=${JAMAHA_ID}`;
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
                        var selId = parseInt(selectedItem.id_produk_keberangkatan);
                        var selK = globalKeberangkatanList.find(function(k) {
                            return parseInt(k.id) === selId;
                        });

                        var selectedTahun = null;
                        if (selK) {
                            selectedTahun = parseInt(selK.tahun);
                        } else if (selectedItem.tahun_keberangkatan) {
                            selectedTahun = parseInt(selectedItem.tahun_keberangkatan);
                        }

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
                    </div>`;
                return;
            }

            var selectedKeberangkatan = null;
            var selectedItemData = null;
            if (globalSelectedData && globalSelectedData.length > 0) {
                selectedItemData = globalSelectedData[0];
                var oldId = parseInt(selectedItemData.id_produk_keberangkatan);
                selectedKeberangkatan = filtered.find(function(k) {
                    return parseInt(k.id) === oldId;
                }) || null;
            }

            var options = '<option value="">-- Pilih Keberangkatan --</option>';
            filtered.forEach(function(k) {
                var isCurrentlySelected = selectedKeberangkatan &&
                    parseInt(selectedKeberangkatan.id) === parseInt(k.id);
                var isFull = k.kuota_default_is_full ? true : false;

                var label = k.keberangkatan_formatted + ' — ' + k.harga_default_formatted;
                if (isFull && !isCurrentlySelected) label += ' (KUOTA PENUH)';

                var disabled = (isFull && !isCurrentlySelected) ? 'disabled' : '';
                var selected = isCurrentlySelected ? 'selected' : '';

                options += `<option value="${k.id}" ${selected} ${disabled}>${label}</option>`;
            });

            var hargaPanelHtml = '';
            if (selectedKeberangkatan && selectedItemData) {
                var k = selectedKeberangkatan;
                var currentIdHarga = selectedItemData.id_produk_harga_bulanan || '';
                var currentHarga = parseInt(selectedItemData.harga || k.harga_default || 0);
                var currentBulanHarga = selectedItemData.bulan_harga || k.bulan;
                var currentTahunHarga = selectedItemData.tahun_harga || k.tahun;
                var currentKuotaText = k.kuota_default_formatted;
                var currentKuotaColor = k.kuota_default_is_full ? 'text-red-600' : 'text-blue-600';

                if (currentIdHarga) {
                    var match = globalHargaBulananList.find(function(h) {
                        return parseInt(h.id) === parseInt(currentIdHarga);
                    });
                    if (match) {
                        currentKuotaText = match.kuota_formatted;
                        currentKuotaColor = match.is_full ? 'text-red-600' :
                            (match.kuota_sisa !== -1 && match.kuota_sisa <= 3 ? 'text-orange-600' : 'text-blue-600');
                    }
                }

                var hargaOptions = '<option value="">-- Sama dengan keberangkatan (' + k.bulan_formatted +
                    ') --</option>';
                globalHargaBulananList.forEach(function(h) {
                    var isCurrentlySelected = (parseInt(h.id) === parseInt(currentIdHarga));
                    var sel = isCurrentlySelected ? 'selected' : '';
                    var full = h.is_full ? true : false;

                    var dis = (full && !isCurrentlySelected) ? 'disabled' : '';

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

                hargaPanelHtml = buildHargaPanel(k, hargaOptions, currentHarga, currentKuotaText,
                    currentKuotaColor, currentIdHarga, currentBulanHarga, currentTahunHarga);
            }

            container.innerHTML = `
                <select id="keberangkatan-select"
                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                    ${options}
                </select>
                <div id="keberangkatan-harga-wrapper" class="mt-3">${hargaPanelHtml}</div>
            `;

            document.getElementById('keberangkatan-select').addEventListener('change',
                handleKeberangkatanSelectChange);
            var hargaSel = container.querySelector('.harga-bulanan-select');
            if (hargaSel) hargaSel.addEventListener('change', handleHargaBulananChange);
        }

        function buildHargaPanel(k, hargaOptions, currentHarga, currentKuotaText, currentKuotaColor,
            currentIdHarga, currentBulanHarga, currentTahunHarga) {
            return `
                <div class="keberangkatan-harga-section bg-gray-50 rounded-xl p-3 border border-gray-200">
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
                    <input type="hidden" class="hidden-id-keberangkatan" name="keberangkatan_data[0][id_produk_keberangkatan]" value="${k.id}">
                    <input type="hidden" class="hidden-id-harga" name="keberangkatan_data[0][id_produk_harga_bulanan]" value="${currentIdHarga || ''}">
                    <input type="hidden" class="hidden-bulan-harga" name="keberangkatan_data[0][bulan_harga]" value="${currentBulanHarga}">
                    <input type="hidden" class="hidden-tahun-harga" name="keberangkatan_data[0][tahun_harga]" value="${currentTahunHarga}">
                    <input type="hidden" class="hidden-harga" name="keberangkatan_data[0][harga]" value="${currentHarga}">
                </div>
            `;
        }

        function handleKeberangkatanSelectChange(e) {
            var select = e.target;
            var selectedId = select.value;
            var wrapper = document.getElementById('keberangkatan-harga-wrapper');

            if (!selectedId) {
                wrapper.innerHTML = '';
                return;
            }

            var k = globalKeberangkatanList.find(item => parseInt(item.id) === parseInt(selectedId));
            if (!k) {
                wrapper.innerHTML = '';
                return;
            }

            var currentIdHarga = document.getElementById('id_produk_harga_bulanan_hidden')?.value || '';
            // Note: hidden inputs sekarang class-based, jadi pakai default.

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

            wrapper.innerHTML = buildHargaPanel(k, hargaOptions, currentHarga, currentKuotaText, currentKuotaColor,
                '', k.bulan, k.tahun);

            var hargaSel = wrapper.querySelector('.harga-bulanan-select');
            if (hargaSel) hargaSel.addEventListener('change', handleHargaBulananChange);
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

            wrapper.querySelector('.hidden-id-harga').value = hargaBulananId || '';
            wrapper.querySelector('.hidden-bulan-harga').value = bulanHarga;
            wrapper.querySelector('.hidden-tahun-harga').value = tahunHarga;
            wrapper.querySelector('.hidden-harga').value = harga;

            var display = wrapper.querySelector('.harga-display-' + k.id);
            if (display) display.textContent = 'Rp ' + parseInt(harga || 0).toLocaleString('id-ID');

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
        }

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
        // UPGRADE HOTEL
        // ==========================================
        function toggleUpgradeHotel() {
            const container = document.getElementById('upgradeHotelContainer');
            container.classList.toggle('hidden');
        }

        function toggleHotelDetail(hotelId) {
            const checkbox = document.querySelector(
                `.upgrade-hotel-checkbox[data-hotel-id="${hotelId}"]`
            );
            const container = document.getElementById(`hotelDetailContainer-${hotelId}`);

            if (!checkbox || !container) return;

            if (checkbox.checked) {
                container.classList.remove('hidden');
                loadKamarsUpgrade(hotelId, container);
            } else {
                container.classList.add('hidden');
                container.innerHTML =
                    '<div class="text-xs text-gray-400 text-center py-2">Pilih hotel untuk memuat kamar</div>';
                removeUpgradeHiddenInputs(hotelId);
                updateTotalUpgradeHotel();
            }
        }

        function updateTotalUpgradeHotel() {
            const container = document.getElementById('upgradeHotelContainer');
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

            const totalEl = document.getElementById('totalUpgradeHotel');
            if (totalEl) totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        function loadKamarsUpgrade(hotelId, container) {
            container.innerHTML =
                '<div class="text-xs text-gray-400 text-center py-2"><i class="fas fa-spinner fa-spin mr-1"></i> Memuat tipe kamar...</div>';

            fetch(`{{ url('/transaksional/jamaah/get-kamars-by-hotel') }}/${hotelId}`)
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
                            <span class="text-xs font-bold text-orange-600 subtotal-hotel-${hotelId}">Rp 0</span>
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
                            updateUpgradeHiddenInputs(hotelId, container);
                            updateTotalUpgradeHotel();
                        });
                    });

                    container.querySelectorAll('.kamar-jumlah, .kamar-harga, .kamar-durasi').forEach(input => {
                        input.addEventListener('change', () => {
                            updateUpgradeHiddenInputs(hotelId, container);
                            updateTotalUpgradeHotel();
                        });
                        input.addEventListener('keyup', () => {
                            updateUpgradeHiddenInputs(hotelId, container);
                            updateTotalUpgradeHotel();
                        });
                    });

                    // ✅ Auto-restore existing upgrades
                    restoreExistingUpgrades(hotelId, container);
                })
                .catch(err => {
                    console.error(err);
                    container.innerHTML =
                    '<div class="text-xs text-red-500 text-center py-2">Gagal memuat kamar.</div>';
                });
        }

        function restoreExistingUpgrades(hotelId, container) {
            const matchExisting = (EXISTING_UPGRADE_HOTELS || []).filter(
                u => parseInt(u.id_hotel) === parseInt(hotelId)
            );

            if (matchExisting.length === 0) {
                updateUpgradeHiddenInputs(hotelId, container);
                updateTotalUpgradeHotel();
                return;
            }

            container.querySelectorAll('.kamar-item').forEach(item => {
                const cb = item.querySelector('.kamar-checkbox');
                if (!cb) return;

                const match = matchExisting.find(u => parseInt(u.id_kamar) === parseInt(cb.value));
                if (match) {
                    cb.checked = true;
                    const jumlahInput = item.querySelector('.kamar-jumlah');
                    const hargaInput = item.querySelector('.kamar-harga');
                    const durasiInput = item.querySelector('.kamar-durasi');
                    if (jumlahInput) jumlahInput.value = match.jumlah_kamar;
                    if (hargaInput) hargaInput.value = 'Rp ' + parseInt(match.harga_per_malam).toLocaleString(
                        'id-ID');
                    if (durasiInput) durasiInput.value = match.durasi_menginap;

                    const inputs = item.querySelectorAll('input:not(.kamar-checkbox)');
                    inputs.forEach(input => {
                        input.disabled = false;
                        input.classList.remove('opacity-50', 'bg-gray-50');
                    });
                }
            });

            updateUpgradeHiddenInputs(hotelId, container);
            updateTotalUpgradeHotel();
        }

        function updateUpgradeHiddenInputs(hotelId, container) {
            removeUpgradeHiddenInputs(hotelId);

            const parentForm = document.getElementById('jamaahForm');
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

                const uniqueKey = `upgrade_hotels[${hotelId}_${cb.value}]`;
                const grp = document.createElement('div');
                grp.className = 'upgrade-hidden-group';
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

            const subtotalEl = container.querySelector(`.subtotal-hotel-${hotelId}`);
            if (subtotalEl) subtotalEl.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        }

        function removeUpgradeHiddenInputs(hotelId) {
            const parentForm = document.getElementById('jamaahForm');
            if (!parentForm) return;
            parentForm.querySelectorAll(`.upgrade-hidden-group[data-hotel-id="${hotelId}"]`).forEach(el => el
                .remove());
        }

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

            togglePembuatanPassport();
            updateTotalPendampingan();

            // ✅ Auto-restore upgrade hotel yang sudah tersimpan
            setTimeout(function() {
                if (EXISTING_UPGRADE_HOTELS && EXISTING_UPGRADE_HOTELS.length > 0) {
                    document.querySelectorAll('#upgradeHotelContainer .upgrade-hotel-checkbox:checked')
                        .forEach(function(cb) {
                            cb.dispatchEvent(new Event('change'));
                        });
                }
            }, 300);

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

                    var keberangkatanSelect = document.getElementById('keberangkatan-select');
                    if (keberangkatanSelect && keberangkatanSelect.value) {
                        var k = globalKeberangkatanList.find(item => parseInt(item.id) === parseInt(
                            keberangkatanSelect.value));
                        var selectedIdKeberangkatan = parseInt(keberangkatanSelect.value);
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
