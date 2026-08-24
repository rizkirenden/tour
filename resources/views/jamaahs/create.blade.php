@extends('layouts.app')

@section('title', 'Tambah Jamaah - Arrum Tour')
@section('page-title', 'Tambah Jamaah')

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
        <span class="text-gray-500 font-medium">Tambah</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h5 class="text-sm font-semibold text-gray-700">Form Tambah Jamaah</h5>
                <p class="text-xs text-gray-400 mt-0.5">Lengkapi data jamaah dengan benar</p>
            </div>

            <form action="{{ route('transaksional.jamaah.store') }}" method="POST" id="jamaahForm"
                enctype="multipart/form-data">
                @csrf

                <div class="p-6 space-y-6">
                    <!-- Informasi Pribadi -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-user text-yellow-500 mr-2"></i> Informasi Pribadi
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    required>
                                @error('nama_lengkap')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">NIK</label>
                                <input type="text" name="nik" value="{{ old('nik') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="16 digit NIK">
                                @error('nik')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Ayah</label>
                                <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Pekerjaan</label>
                                <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin</label>
                                <select name="jenis_kelamin"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                    <option value="">Pilih</option>
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki
                                    </option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Telepon</label>
                                <input type="text" name="telepon" value="{{ old('telepon') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">WhatsApp</label>
                                <input type="text" name="wa" value="{{ old('wa') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="08xxxxxxxxxx">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
                            <textarea name="alamat" rows="2"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">{{ old('alamat') }}</textarea>
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
                                <input type="text" name="nomor_paspor" value="{{ old('nomor_paspor') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Terbit
                                    Passport</label>
                                <input type="date" name="paspor_terbit" value="{{ old('paspor_terbit') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Berakhir
                                    Passport</label>
                                <input type="date" name="paspor_expired" value="{{ old('paspor_expired') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Diterbitkan Di</label>
                                <input type="text" name="paspor_diterbitkan_di"
                                    value="{{ old('paspor_diterbitkan_di') }}"
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
                                            data-kode="{{ $produk->kode_produk }}"
                                            {{ old('produk_paket') == $produk->nama_produk ? 'selected' : '' }}>
                                            {{ $produk->kode_produk }} - {{ $produk->nama_produk }}
                                            ({{ $produk->durasi_hari }} Hari)
                                        </option>
                                    @endforeach
                                </select>
                                @error('produk_paket')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    ID Keberangkatan <span class="text-gray-400 text-xs">(otomatis)</span>
                                </label>
                                <input type="text" name="id_keberangkatan" id="id_keberangkatan"
                                    value="{{ old('id_keberangkatan') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl bg-gray-100 text-sm cursor-not-allowed"
                                    readonly>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Akan digenerate otomatis berdasarkan produk paket
                                </p>
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
                                            {{ old('kota_asal') == $kota->nama_kota ? 'selected' : '' }}>
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
                                <input type="text" name="pulau" id="pulau" value="{{ old('pulau') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl bg-gray-100 text-sm cursor-not-allowed"
                                    readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Bandara Keberangkatan</label>
                                <input type="text" name="bandara_keberangkatan" id="bandara_keberangkatan"
                                    value="{{ old('bandara_keberangkatan') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl bg-gray-100 text-sm cursor-not-allowed"
                                    readonly>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Bulan Keberangkatan</label>
                                <select name="bulan_keberangkatan"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                    <option value="">Pilih Bulan</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}"
                                            {{ old('bulan_keberangkatan') == $i ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tahun Keberangkatan</label>
                                <input type="number" name="tahun_keberangkatan"
                                    value="{{ old('tahun_keberangkatan', date('Y')) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    min="2000" max="{{ date('Y') + 10 }}">
                            </div>
                        </div>
                    </div>

                    <!-- Pendampingan & Agent -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-handshake text-yellow-500 mr-2"></i> Pendampingan & Agent
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Pendampingan</label>
                                <select name="jenis_pendampingan"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                    <option value="">Pilih</option>
                                    <option value="VIP" {{ old('jenis_pendampingan') == 'VIP' ? 'selected' : '' }}>VIP
                                    </option>
                                    <option value="Premium"
                                        {{ old('jenis_pendampingan') == 'Premium' ? 'selected' : '' }}>Premium</option>
                                    <option value="Reguler"
                                        {{ old('jenis_pendampingan') == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                                    <option value="Ekonomi"
                                        {{ old('jenis_pendampingan') == 'Ekonomi' ? 'selected' : '' }}>Ekonomi</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Agent</label>
                                <input type="text" name="agent" value="{{ old('agent') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Nama Agent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Fee Agent</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                    <input type="number" name="fee_agent" value="{{ old('fee_agent', 0) }}"
                                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                        placeholder="0">
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
                                            {{ old('id_diskon') == $diskon->id_diskon ? 'selected' : '' }}>
                                            {{ $diskon->nama_diskon }}
                                            (-{{ $diskon->nilai_diskon_formatted }})
                                            - Sisa Kuota: {{ $diskon->sisa_kuota }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Diskon akan langsung diterapkan pada tagihan (per orang)
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

                    <!-- Upload Dokumen -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-upload text-yellow-500 mr-2"></i> Upload Dokumen
                            <span class="ml-2 text-xs text-gray-400">(opsional, support gambar/PDF)</span>
                        </h6>

                        <!-- File KTP/KK -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">File KTP/KK</label>
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <input type="file" name="file_ktp_kk" id="file_ktp_kk"
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100"
                                        onchange="previewFile(this, 'preview_ktp_kk')">
                                    <p class="text-xs text-gray-400 mt-1">Format: PDF, JPG, PNG (Max 2MB)</p>
                                </div>
                                <div id="preview_ktp_kk"
                                    class="hidden flex items-center gap-2 px-3 py-2 bg-green-50 rounded-lg border border-green-200">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    <span class="text-sm text-green-700" id="preview_ktp_kk_name"></span>
                                </div>
                            </div>
                            @error('file_ktp_kk')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File Vaksin -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">File Vaksin</label>
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <input type="file" name="file_vaksin" id="file_vaksin"
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100"
                                        onchange="previewFile(this, 'preview_vaksin')">
                                    <p class="text-xs text-gray-400 mt-1">Format: PDF, JPG, PNG (Max 2MB)</p>
                                </div>
                                <div id="preview_vaksin"
                                    class="hidden flex items-center gap-2 px-3 py-2 bg-green-50 rounded-lg border border-green-200">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    <span class="text-sm text-green-700" id="preview_vaksin_name"></span>
                                </div>
                            </div>
                            @error('file_vaksin')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File Visa -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">File Visa</label>
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <input type="file" name="file_visa" id="file_visa" accept=".pdf,.jpg,.jpeg,.png"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100"
                                        onchange="previewFile(this, 'preview_visa')">
                                    <p class="text-xs text-gray-400 mt-1">Format: PDF, JPG, PNG (Max 2MB)</p>
                                </div>
                                <div id="preview_visa"
                                    class="hidden flex items-center gap-2 px-3 py-2 bg-green-50 rounded-lg border border-green-200">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    <span class="text-sm text-green-700" id="preview_visa_name"></span>
                                </div>
                            </div>
                            @error('file_visa')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File Paspor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">File Passport</label>
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <input type="file" name="file_paspor" id="file_paspor"
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100"
                                        onchange="previewFile(this, 'preview_paspor')">
                                    <p class="text-xs text-gray-400 mt-1">Format: PDF, JPG, PNG (Max 2MB)</p>
                                </div>
                                <div id="preview_paspor"
                                    class="hidden flex items-center gap-2 px-3 py-2 bg-green-50 rounded-lg border border-green-200">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    <span class="text-sm text-green-700" id="preview_paspor_name"></span>
                                </div>
                            </div>
                            @error('file_paspor')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
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
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">{{ old('catatan_tambahan') }}</textarea>
                            @error('catatan_tambahan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
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
                            <i class="fas fa-save mr-2"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Preview file function for PDF and Images
        function previewFile(input, previewId) {
            const preview = document.getElementById(previewId);
            const nameSpan = document.getElementById(previewId + '_name');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const ext = file.name.split('.').pop().toLowerCase();
                const isPDF = ext === 'pdf';
                const isImage = ['jpg', 'jpeg', 'png'].includes(ext);

                if (isPDF || isImage) {
                    preview.classList.remove('hidden');
                    if (nameSpan) {
                        nameSpan.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                    }

                    // Jika gambar, tampilkan preview thumbnail
                    if (isImage) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.innerHTML = `
                                <img src="${e.target.result}" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                <span class="text-sm text-green-700 ml-2">${file.name}</span>
                            `;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // PDF
                        preview.innerHTML = `
                            <i class="fas fa-file-pdf text-3xl text-red-500"></i>
                            <span class="text-sm text-green-700 ml-2">${file.name}</span>
                        `;
                    }
                } else {
                    preview.classList.add('hidden');
                    alert('Format file tidak didukung. Gunakan PDF, JPG, atau PNG.');
                    input.value = '';
                }
            } else {
                preview.classList.add('hidden');
                if (nameSpan) {
                    nameSpan.textContent = '';
                }
            }
        }

        // Auto generate ID Keberangkatan berdasarkan produk paket
        document.getElementById('produk_paket').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const kodeProduk = selectedOption.dataset.kode;
            const idKeberangkatan = document.getElementById('id_keberangkatan');

            if (kodeProduk) {
                const tahun = new Date().getFullYear();
                const bulan = String(new Date().getMonth() + 1).padStart(2, '0');
                const random = String(Math.floor(Math.random() * 1000)).padStart(3, '0');
                idKeberangkatan.value = kodeProduk + '-' + tahun + bulan + '-' + random;
            } else {
                idKeberangkatan.value = '';
            }
        });

        // Auto fill pulau dan bandara berdasarkan kota asal
        document.getElementById('kota_asal').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const pulau = selectedOption.dataset.pulau || '';
            const bandara = selectedOption.dataset.bandara || '';

            document.getElementById('pulau').value = pulau;
            document.getElementById('bandara_keberangkatan').value = bandara;
        });

        // Trigger event on page load
        document.addEventListener('DOMContentLoaded', function() {
            const kotaAsal = document.getElementById('kota_asal');
            if (kotaAsal.value) {
                const selectedOption = kotaAsal.options[kotaAsal.selectedIndex];
                document.getElementById('pulau').value = selectedOption.dataset.pulau || '';
                document.getElementById('bandara_keberangkatan').value = selectedOption.dataset.bandara || '';
            }

            const produkPaket = document.getElementById('produk_paket');
            if (produkPaket.value) {
                const selectedOption = produkPaket.options[produkPaket.selectedIndex];
                const kodeProduk = selectedOption.dataset.kode;
                if (kodeProduk) {
                    const tahun = new Date().getFullYear();
                    const bulan = String(new Date().getMonth() + 1).padStart(2, '0');
                    const random = String(Math.floor(Math.random() * 1000)).padStart(3, '0');
                    document.getElementById('id_keberangkatan').value = kodeProduk + '-' + tahun + bulan + '-' +
                        random;
                }
            }
        });
    </script>
@endpush
