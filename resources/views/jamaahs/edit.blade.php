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
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_lengkap"
                                    value="{{ old('nama_lengkap', $jamaah->nama_lengkap) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    required>
                                @error('nama_lengkap')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin</label>
                                <select name="jenis_kelamin"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
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
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Telepon</label>
                                <input type="text" name="telepon" value="{{ old('telepon', $jamaah->telepon) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir"
                                    value="{{ old('tempat_lahir', $jamaah->tempat_lahir) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir"
                                    value="{{ old('tanggal_lahir', $jamaah->tanggal_lahir ? $jamaah->tanggal_lahir->format('Y-m-d') : '') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Paspor</label>
                                <input type="text" name="nomor_paspor"
                                    value="{{ old('nomor_paspor', $jamaah->nomor_paspor) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
                            <textarea name="alamat" rows="2"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">{{ old('alamat', $jamaah->alamat) }}</textarea>
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
                                        <option value="{{ $produk->nama_produk }}" data-kode="{{ $produk->kode_produk }}"
                                            {{ old('produk_paket', $jamaah->produk_paket) == $produk->nama_produk ? 'selected' : '' }}>
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Bulan Keberangkatan</label>
                                <select name="bulan_keberangkatan"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                    <option value="">Pilih Bulan</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}"
                                            {{ old('bulan_keberangkatan', $jamaah->bulan_keberangkatan) == $i ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tahun Keberangkatan</label>
                                <input type="number" name="tahun_keberangkatan"
                                    value="{{ old('tahun_keberangkatan', $jamaah->tahun_keberangkatan ?? date('Y')) }}"
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
                                    <option value="VIP"
                                        {{ old('jenis_pendampingan', $jamaah->jenis_pendampingan) == 'VIP' ? 'selected' : '' }}>
                                        VIP</option>
                                    <option value="Premium"
                                        {{ old('jenis_pendampingan', $jamaah->jenis_pendampingan) == 'Premium' ? 'selected' : '' }}>
                                        Premium</option>
                                    <option value="Reguler"
                                        {{ old('jenis_pendampingan', $jamaah->jenis_pendampingan) == 'Reguler' ? 'selected' : '' }}>
                                        Reguler</option>
                                    <option value="Ekonomi"
                                        {{ old('jenis_pendampingan', $jamaah->jenis_pendampingan) == 'Ekonomi' ? 'selected' : '' }}>
                                        Ekonomi</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Agent</label>
                                <input type="text" name="agent" value="{{ old('agent', $jamaah->agent) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Nama Agent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Fee Agent</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                    <input type="number" name="fee_agent"
                                        value="{{ old('fee_agent', $jamaah->fee_agent) }}"
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
                                            {{ old('id_diskon', $jamaah->id_diskon) == $diskon->id_diskon ? 'selected' : '' }}>
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
                                <input type="text" name="keterangan_diskon"
                                    value="{{ old('keterangan_diskon', $jamaah->keterangan_diskon) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Catatan diskon (opsional)">
                            </div>
                        </div>
                    </div>

                    <!-- Upload Foto -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-images text-yellow-500 mr-2"></i> Upload Dokumen
                            <span class="ml-2 text-xs text-gray-400">(opsional)</span>
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Foto KTP</label>
                                @if ($jamaah->foto_ktp_url)
                                    <div class="mb-2">
                                        <img src="{{ $jamaah->foto_ktp_url }}" alt="Foto KTP"
                                            class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                        <p class="text-xs text-gray-400 mt-1">Foto saat ini</p>
                                    </div>
                                @endif
                                <input type="file" name="foto_ktp" id="foto_ktp" accept="image/*"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100"
                                    onchange="previewImage(this, 'preview_ktp')">
                                <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                                <div id="preview_ktp" class="mt-2 hidden">
                                    <img src="#" alt="Preview KTP"
                                        class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                </div>
                                @error('foto_ktp')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Foto Vaksin</label>
                                @if ($jamaah->foto_vaksin_url)
                                    <div class="mb-2">
                                        <img src="{{ $jamaah->foto_vaksin_url }}" alt="Foto Vaksin"
                                            class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                        <p class="text-xs text-gray-400 mt-1">Foto saat ini</p>
                                    </div>
                                @endif
                                <input type="file" name="foto_vaksin" id="foto_vaksin" accept="image/*"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100"
                                    onchange="previewImage(this, 'preview_vaksin')">
                                <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                                <div id="preview_vaksin" class="mt-2 hidden">
                                    <img src="#" alt="Preview Vaksin"
                                        class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                </div>
                                @error('foto_vaksin')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Foto Visa</label>
                                @if ($jamaah->foto_visa_url)
                                    <div class="mb-2">
                                        <img src="{{ $jamaah->foto_visa_url }}" alt="Foto Visa"
                                            class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                        <p class="text-xs text-gray-400 mt-1">Foto saat ini</p>
                                    </div>
                                @endif
                                <input type="file" name="foto_visa" id="foto_visa" accept="image/*"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100"
                                    onchange="previewImage(this, 'preview_visa')">
                                <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                                <div id="preview_visa" class="mt-2 hidden">
                                    <img src="#" alt="Preview Visa"
                                        class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                </div>
                                @error('foto_visa')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
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
        // Preview image function
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.classList.remove('hidden');
                    preview.querySelector('img').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
            }
        }

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
        });
    </script>
@endpush
