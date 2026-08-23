@extends('layouts.app')

@section('title', 'Edit Keluarga - Arrum Tour')
@section('page-title', 'Edit Keluarga')

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
        <span class="text-gray-500 font-medium">Edit</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Form Edit Keluarga</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Edit data keluarga dan anggota</p>
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
                    <!-- Informasi Keluarga -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-home text-yellow-500 mr-2"></i> Informasi Keluarga
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kode Keluarga
                                </label>
                                <input type="text" value="{{ $keluarga->kode_keluarga }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl bg-gray-100 text-sm cursor-not-allowed"
                                    readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Kepala Keluarga <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_kepala_keluarga"
                                    value="{{ old('nama_kepala_keluarga', $keluarga->nama_kepala_keluarga) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    required>
                                @error('nama_kepala_keluarga')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Telepon</label>
                                <input type="text" name="telepon" value="{{ old('telepon', $keluarga->telepon) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="08xxxxxxxxxx">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
                                <input type="text" name="alamat" value="{{ old('alamat', $keluarga->alamat) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kota Asal</label>
                                <select name="kota_asal" id="kota_asal"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                    <option value="">-- Pilih Kota Asal --</option>
                                    @foreach ($kotaAsals as $kota)
                                        <option value="{{ $kota->nama_kota }}" data-pulau="{{ $kota->pulau }}"
                                            data-bandara="{{ $kota->bandara_terdekat }}"
                                            {{ old('kota_asal', $keluarga->kota_asal) == $kota->nama_kota ? 'selected' : '' }}>
                                            {{ $kota->nama_kota }} - {{ $kota->provinsi ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Pulau</label>
                                <input type="text" name="pulau" id="pulau"
                                    value="{{ old('pulau', $keluarga->pulau) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl bg-gray-100 text-sm cursor-not-allowed"
                                    readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Bandara Keberangkatan</label>
                                <input type="text" name="bandara_keberangkatan" id="bandara_keberangkatan"
                                    value="{{ old('bandara_keberangkatan', $keluarga->bandara_keberangkatan) }}"
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
                                            {{ old('bulan_keberangkatan', $keluarga->bulan_keberangkatan) == $i ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tahun Keberangkatan</label>
                                <input type="number" name="tahun_keberangkatan"
                                    value="{{ old('tahun_keberangkatan', $keluarga->tahun_keberangkatan ?? date('Y')) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    min="2000" max="{{ date('Y') + 10 }}">
                            </div>
                        </div>
                    </div>

                    <!-- Produk & Agent -->
                    <div class="border-b border-gray-200 pb-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-box text-yellow-500 mr-2"></i> Produk & Agent
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Produk Paket <span class="text-red-500">*</span>
                                </label>
                                <select name="produk_paket" id="produk_paket"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($produkPakets as $produk)
                                        <option value="{{ $produk->nama_produk }}" data-harga="{{ $produk->harga_dasar }}"
                                            {{ old('produk_paket', $keluarga->produk_paket) == $produk->nama_produk ? 'selected' : '' }}>
                                            {{ $produk->kode_produk }} - {{ $produk->nama_produk }}
                                            ({{ $produk->durasi_hari }} Hari)
                                            - {{ $produk->harga_dasar_formatted }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('produk_paket')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Agent</label>
                                <input type="text" name="agent" value="{{ old('agent', $keluarga->agent) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Nama Agent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Fee Agent</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                    <input type="number" name="fee_agent"
                                        value="{{ old('fee_agent', $keluarga->fee_agent) }}"
                                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                        placeholder="0">
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Diskon</label>
                                <select name="id_diskon" id="id_diskon"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                    <option value="">-- Tanpa Diskon --</option>
                                    @foreach ($diskons as $diskon)
                                        <option value="{{ $diskon->id_diskon }}"
                                            data-persen="{{ $diskon->persen_diskon }}"
                                            {{ old('id_diskon', $keluarga->id_diskon) == $diskon->id_diskon ? 'selected' : '' }}>
                                            {{ $diskon->kode_diskon }} - {{ $diskon->nama_diskon }}
                                            ({{ $diskon->persen_diskon_formatted }})
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
                                <input type="text" name="keterangan_diskon"
                                    value="{{ old('keterangan_diskon', $keluarga->keterangan_diskon) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Catatan diskon (opsional)">
                            </div>
                        </div>
                    </div>

                    <!-- Anggota Keluarga -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-users text-yellow-500 mr-2"></i> Anggota Keluarga
                                <span class="ml-2 text-xs text-gray-400">(minimal 1 anggota)</span>
                            </h6>
                            <button type="button" onclick="addAnggota()"
                                class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm font-medium">
                                <i class="fas fa-plus mr-2"></i> Tambah Anggota
                            </button>
                        </div>

                        <div id="anggotaContainer">
                            @php
                                $anggotaData =
                                    old('jamaahs') ??
                                    $keluarga->jamaahs
                                        ->map(function ($jamaah) {
                                            return [
                                                'id_jamaah' => $jamaah->id_jamaah,
                                                'nama_lengkap' => $jamaah->nama_lengkap,
                                                'hubungan_keluarga' => $jamaah->hubungan_keluarga,
                                                'is_kepala_keluarga' => $jamaah->is_kepala_keluarga,
                                                'jenis_kelamin' => $jamaah->jenis_kelamin,
                                                'telepon' => $jamaah->telepon,
                                                'nomor_paspor' => $jamaah->nomor_paspor,
                                                'tempat_lahir' => $jamaah->tempat_lahir,
                                                'tanggal_lahir' => $jamaah->tanggal_lahir
                                                    ? $jamaah->tanggal_lahir->format('Y-m-d')
                                                    : null,
                                                'produk_paket' => $jamaah->produk_paket,
                                                'foto_ktp' => $jamaah->foto_ktp,
                                                'foto_vaksin' => $jamaah->foto_vaksin,
                                                'foto_visa' => $jamaah->foto_visa,
                                            ];
                                        })
                                        ->toArray();

                                if (empty($anggotaData)) {
                                    $anggotaData = [
                                        [
                                            'id_jamaah' => '',
                                            'nama_lengkap' => '',
                                            'hubungan_keluarga' => '',
                                            'is_kepala_keluarga' => true,
                                            'jenis_kelamin' => '',
                                            'telepon' => '',
                                            'nomor_paspor' => '',
                                            'tempat_lahir' => '',
                                            'tanggal_lahir' => '',
                                            'produk_paket' => '',
                                            'foto_ktp' => null,
                                            'foto_vaksin' => null,
                                            'foto_visa' => null,
                                        ],
                                    ];
                                }
                            @endphp

                            @foreach ($anggotaData as $index => $anggota)
                                <div class="anggota-row bg-gray-50 rounded-xl p-4 mb-3 border border-gray-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-sm font-medium text-gray-700">Anggota
                                            #{{ $index + 1 }}</span>
                                        <button type="button" onclick="removeAnggota(this)"
                                            class="text-red-500 hover:text-red-700 text-sm">
                                            <i class="fas fa-times"></i> Hapus
                                        </button>
                                    </div>
                                    <input type="hidden" name="jamaahs[{{ $index }}][id_jamaah]"
                                        value="{{ $anggota['id_jamaah'] ?? '' }}">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                Nama Lengkap <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="jamaahs[{{ $index }}][nama_lengkap]"
                                                value="{{ $anggota['nama_lengkap'] ?? '' }}"
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
                                                        {{ ($anggota['hubungan_keluarga'] ?? '') == $option ? 'selected' : '' }}>
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
                                                    {{ ($anggota['jenis_kelamin'] ?? '') == 'L' ? 'selected' : '' }}>
                                                    Laki-laki</option>
                                                <option value="P"
                                                    {{ ($anggota['jenis_kelamin'] ?? '') == 'P' ? 'selected' : '' }}>
                                                    Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Telepon</label>
                                            <input type="text" name="jamaahs[{{ $index }}][telepon]"
                                                value="{{ $anggota['telepon'] ?? '' }}"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Nomor
                                                Paspor</label>
                                            <input type="text" name="jamaahs[{{ $index }}][nomor_paspor]"
                                                value="{{ $anggota['nomor_paspor'] ?? '' }}"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Tempat
                                                Lahir</label>
                                            <input type="text" name="jamaahs[{{ $index }}][tempat_lahir]"
                                                value="{{ $anggota['tempat_lahir'] ?? '' }}"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal
                                                Lahir</label>
                                            <input type="date" name="jamaahs[{{ $index }}][tanggal_lahir]"
                                                value="{{ $anggota['tanggal_lahir'] ?? '' }}"
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                        </div>
                                        <div class="flex items-center gap-4 pt-2">
                                            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                                <input type="checkbox"
                                                    name="jamaahs[{{ $index }}][is_kepala_keluarga]"
                                                    value="1"
                                                    {{ $anggota['is_kepala_keluarga'] ?? false ? 'checked' : '' }}
                                                    class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                                                <span>Kepala Keluarga</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3 pt-3 border-t border-gray-200">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Foto KTP</label>
                                            @if (!empty($anggota['foto_ktp']) && Storage::disk('public')->exists($anggota['foto_ktp']))
                                                <div class="mb-1">
                                                    <img src="{{ Storage::url($anggota['foto_ktp']) }}" alt="KTP"
                                                        class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                                </div>
                                            @endif
                                            <input type="file" name="jamaahs[{{ $index }}][foto_ktp]"
                                                accept="image/*"
                                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                            <p class="text-xs text-gray-400 mt-1">Max 2MB (JPG, PNG)</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Foto Vaksin</label>
                                            @if (!empty($anggota['foto_vaksin']) && Storage::disk('public')->exists($anggota['foto_vaksin']))
                                                <div class="mb-1">
                                                    <img src="{{ Storage::url($anggota['foto_vaksin']) }}" alt="Vaksin"
                                                        class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                                </div>
                                            @endif
                                            <input type="file" name="jamaahs[{{ $index }}][foto_vaksin]"
                                                accept="image/*"
                                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                            <p class="text-xs text-gray-400 mt-1">Max 2MB (JPG, PNG)</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Foto Visa</label>
                                            @if (!empty($anggota['foto_visa']) && Storage::disk('public')->exists($anggota['foto_visa']))
                                                <div class="mb-1">
                                                    <img src="{{ Storage::url($anggota['foto_visa']) }}" alt="Visa"
                                                        class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                                </div>
                                            @endif
                                            <input type="file" name="jamaahs[{{ $index }}][foto_visa]"
                                                accept="image/*"
                                                class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                            <p class="text-xs text-gray-400 mt-1">Max 2MB (JPG, PNG)</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan Tambahan</label>
                        <textarea name="catatan_tambahan" rows="2"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">{{ old('catatan_tambahan', $keluarga->catatan_tambahan) }}</textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4">
                        <a href="{{ route('transaksional.keluarga.index') }}"
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
        let anggotaIndex = {{ count($anggotaData) }};

        document.getElementById('kota_asal').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('pulau').value = selectedOption.dataset.pulau || '';
            document.getElementById('bandara_keberangkatan').value = selectedOption.dataset.bandara || '';
        });

        function addAnggota() {
            const container = document.getElementById('anggotaContainer');
            const row = document.createElement('div');
            row.className = 'anggota-row bg-gray-50 rounded-xl p-4 mb-3 border border-gray-200';
            row.innerHTML = `
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-700">Anggota #${anggotaIndex + 1}</span>
                    <button type="button" onclick="removeAnggota(this)"
                        class="text-red-500 hover:text-red-700 text-sm">
                        <i class="fas fa-times"></i> Hapus
                    </button>
                </div>
                <input type="hidden" name="jamaahs[${anggotaIndex}][id_jamaah]" value="">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="jamaahs[${anggotaIndex}][nama_lengkap]"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Hubungan Keluarga</label>
                        <select name="jamaahs[${anggotaIndex}][hubungan_keluarga]"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                            <option value="">-- Pilih Hubungan --</option>
                            @foreach ($hubunganOptions as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Kelamin</label>
                        <select name="jamaahs[${anggotaIndex}][jenis_kelamin]"
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
                        <input type="text" name="jamaahs[${anggotaIndex}][telepon]"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nomor Paspor</label>
                        <input type="text" name="jamaahs[${anggotaIndex}][nomor_paspor]"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Tempat Lahir</label>
                        <input type="text" name="jamaahs[${anggotaIndex}][tempat_lahir]"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                        <input type="date" name="jamaahs[${anggotaIndex}][tanggal_lahir]"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                    </div>
                    <div class="flex items-center gap-4 pt-2">
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="jamaahs[${anggotaIndex}][is_kepala_keluarga]" value="1"
                                class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                            <span>Kepala Keluarga</span>
                        </label>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3 pt-3 border-t border-gray-200">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Foto KTP</label>
                        <input type="file" name="jamaahs[${anggotaIndex}][foto_ktp]" accept="image/*"
                            class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                        <p class="text-xs text-gray-400 mt-1">Max 2MB (JPG, PNG)</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Foto Vaksin</label>
                        <input type="file" name="jamaahs[${anggotaIndex}][foto_vaksin]" accept="image/*"
                            class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                        <p class="text-xs text-gray-400 mt-1">Max 2MB (JPG, PNG)</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Foto Visa</label>
                        <input type="file" name="jamaahs[${anggotaIndex}][foto_visa]" accept="image/*"
                            class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                        <p class="text-xs text-gray-400 mt-1">Max 2MB (JPG, PNG)</p>
                    </div>
                </div>
            `;
            container.appendChild(row);
            anggotaIndex++;
        }

        function removeAnggota(button) {
            const row = button.closest('.anggota-row');
            const container = document.getElementById('anggotaContainer');
            if (container.children.length > 1) {
                row.remove();
                const rows = container.querySelectorAll('.anggota-row');
                rows.forEach((r, idx) => {
                    const label = r.querySelector('.text-sm.font-medium');
                    if (label) {
                        label.textContent = `Anggota #${idx + 1}`;
                    }
                });
            } else {
                alert('Minimal harus ada 1 anggota keluarga!');
            }
        }

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
