@extends('layouts.app')

@section('title', 'Detail Produk - Arrum Tour')
@section('page-title', 'Detail Produk Paket')

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
        <span class="text-gray-500 font-medium">Detail</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Detail Produk</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Informasi lengkap produk paket</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('master.produk.edit', $produk->id_produk) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('master.produk.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="p-6">
                <!-- Header Produk -->
                <div
                    class="bg-gradient-to-r from-yellow-50 to-yellow-100/50 rounded-xl p-6 mb-6 border border-yellow-200/50">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h2 class="text-2xl font-bold text-gray-800">{{ $produk->nama_produk }}</h2>
                                <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-medium rounded-full">
                                    {{ $produk->kategori ?? 'Umum' }}
                                </span>
                                {!! $produk->status_badge !!}
                            </div>
                            @if ($produk->include_tur)
                                <p class="text-xs text-blue-600 mt-1">
                                    <i class="fas fa-route mr-1"></i> Include Tur:
                                    {{ $produk->paketTour->kota_tujuan ?? '-' }}
                                </p>
                            @endif
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="fas fa-clock mr-1"></i> Durasi: {{ $produk->durasi_hari }} Hari
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-400">Total Harga Bulanan</p>
                            <p class="text-lg font-bold text-blue-600">{{ $produk->hargaBulanan->count() }} data</p>
                        </div>
                    </div>
                </div>

                <!-- Grid Informasi -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Informasi Dasar -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-yellow-500 mr-2"></i> Informasi Dasar
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">Kategori</dt>
                                <dd class="font-medium text-gray-700">{{ $produk->kategori ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">Nama Produk</dt>
                                <dd class="font-medium text-gray-700">{{ $produk->nama_produk }}</dd>
                            </div>
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">Include Tur</dt>
                                <dd>{!! $produk->include_tur_badge !!}</dd>
                            </div>
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">Total Harga Bulanan</dt>
                                <dd class="font-medium text-blue-600">{{ $produk->hargaBulanan->count() }} data</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Durasi Detail -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-clock text-yellow-500 mr-2"></i> Detail Durasi
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">Durasi Perjalanan</dt>
                                <dd class="font-medium text-gray-700">{{ $produk->durasi_perjalanan_formatted }}</dd>
                            </div>
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">Durasi Mekkah</dt>
                                <dd class="font-medium text-gray-700">{{ $produk->durasi_mekkah ?? 0 }} Hari</dd>
                            </div>
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">Durasi Madinah</dt>
                                <dd class="font-medium text-gray-700">{{ $produk->durasi_madinah ?? 0 }} Hari</dd>
                            </div>
                            @if ($produk->include_tur)
                                <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                    <dt class="text-gray-500">Durasi Tour</dt>
                                    <dd class="font-medium text-blue-600">{{ $produk->durasi_tour ?? 0 }} Hari</dd>
                                </div>
                            @endif
                            <div class="flex justify-between text-sm font-medium pt-2">
                                <dt class="text-gray-700">Total Durasi</dt>
                                <dd class="font-bold text-yellow-600">{{ $produk->durasi_hari }} Hari</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Harga Bulanan dengan Aksi dan Flyer -->
                <div class="mt-6 bg-gray-50 rounded-xl p-5 border border-gray-100">
                    <div class="flex flex-wrap items-center justify-between mb-4">
                        <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-calendar-alt text-yellow-500 mr-2"></i> Harga Per Bulan
                            <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                                {{ $produk->hargaBulanan->count() }} data
                            </span>
                        </h6>
                        <button type="button" onclick="toggleFormTambah()"
                            class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs rounded-lg hover:bg-yellow-600 transition">
                            <i class="fas fa-plus mr-1"></i> Tambah Harga
                        </button>
                    </div>

                    <!-- Form Tambah Harga -->
                    <div id="formTambahHarga" class="hidden mb-4 p-4 bg-white rounded-lg border border-gray-200">
                        <form action="{{ route('master.produk.harga-bulanan.store', $produk->id_produk) }}" method="POST"
                            enctype="multipart/form-data" id="formTambah">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Bulan <span
                                            class="text-red-500">*</span></label>
                                    <select name="bulan"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500"
                                        required>
                                        <option value="">Pilih Bulan</option>
                                        @for ($b = 1; $b <= 12; $b++)
                                            <option value="{{ $b }}">
                                                {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Tahun <span
                                            class="text-red-500">*</span></label>
                                    <select name="tahun"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500"
                                        required>
                                        <option value="">Pilih Tahun</option>
                                        @for ($y = date('Y'); $y <= date('Y') + 5; $y++)
                                            <option value="{{ $y }}">{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Harga (Rp) <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="harga" id="tambah_harga" oninput="formatRupiah(this)"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500"
                                        placeholder="0" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Flyer</label>
                                    <input type="file" name="flyer" accept="image/*"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                    <p class="text-xs text-gray-400 mt-1">PNG, JPG, JPEG, SVG (max 2MB)</p>
                                </div>
                                <div class="flex items-end gap-2">
                                    <label class="flex items-center gap-1 text-xs">
                                        <input type="checkbox" name="is_active" value="1" checked>
                                        Aktif
                                    </label>
                                    <button type="submit"
                                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm">
                                        <i class="fas fa-save mr-1"></i> Simpan
                                    </button>
                                    <button type="button" onclick="toggleFormTambah()"
                                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Tabel Harga -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Bulan
                                    </th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Tahun
                                    </th>
                                    <th class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Harga
                                    </th>
                                    <th class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Flyer
                                    </th>
                                    <th class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Status
                                    </th>
                                    <th class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100" id="hargaBulananTable">
                                @forelse($produk->hargaBulanan->sortBy(['tahun', 'bulan']) as $index => $harga)
                                    <tr class="hover:bg-gray-50 transition-colors" id="harga-row-{{ $harga->id }}">
                                        <td class="px-3 py-2 text-gray-600">{{ $index + 1 }}</td>
                                        <td class="px-3 py-2 font-medium text-gray-700">{{ $harga->bulan_formatted }}</td>
                                        <td class="px-3 py-2 text-gray-600">{{ $harga->tahun }}</td>
                                        <td class="px-3 py-2 text-right font-bold text-yellow-600">
                                            {{ $harga->harga_formatted }}
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            @if ($harga->flyer_url)
                                                <a href="{{ $harga->flyer_url }}" target="_blank"
                                                    class="inline-flex items-center gap-1 text-blue-500 hover:text-blue-700">
                                                    <i class="fas fa-image"></i>
                                                    <span class="text-xs">Lihat</span>
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <button onclick="toggleHargaBulanan({{ $harga->id }})"
                                                class="status-badge-{{ $harga->id }} transition-all duration-200 cursor-pointer">
                                                <span
                                                    class="px-2 py-1 rounded-full text-xs font-medium
                                                    {{ $harga->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                                    {{ $harga->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </button>
                                            <form id="toggle-harga-form-{{ $harga->id }}"
                                                action="{{ route('master.produk.harga-bulanan.toggle', $harga->id) }}"
                                                method="POST" class="hidden">
                                                @csrf
                                                @method('PATCH')
                                            </form>
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                <button type="button" onclick="openEditModal({{ $harga->id }})"
                                                    class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition">
                                                    <i class="fas fa-edit text-xs"></i>
                                                </button>
                                                <button type="button" onclick="confirmDeleteHarga({{ $harga->id }})"
                                                    class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                                <form id="delete-harga-form-{{ $harga->id }}"
                                                    action="{{ route('master.produk.harga-bulanan.destroy', $harga->id) }}"
                                                    method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-3 py-4 text-center text-gray-400 text-sm">
                                            Belum ada data harga bulanan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paket Tour -->
                @if ($produk->include_tur && $produk->paketTour)
                    <div class="mt-6 bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-route text-yellow-500 mr-2"></i> Paket Tour
                        </h6>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Kota Tujuan</span>
                                    <p class="font-medium text-gray-700">{{ $produk->paketTour->kota_tujuan ?? '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Negara</span>
                                    <p class="font-medium text-gray-700">{{ $produk->paketTour->negara ?? '-' }}</p>
                                </div>
                            </div>
                            @if ($produk->paketTour->deskripsi)
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <span class="text-gray-500 text-sm">Deskripsi</span>
                                    <p class="text-sm text-gray-600 mt-1">{{ $produk->paketTour->deskripsi }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Hotel -->
                @if ($produk->include_tur && $produk->paketTour && $produk->paketTour->hotels->count() > 0)
                    <div class="mt-6 bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-hotel text-yellow-500 mr-2"></i> Daftar Hotel
                            <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                                {{ $produk->paketTour->hotels->count() }} Hotel
                            </span>
                        </h6>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">No
                                        </th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Nama
                                            Hotel</th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Kota
                                        </th>
                                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase">
                                            Bintang</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($produk->paketTour->hotels as $index => $hotel)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-3 py-2 text-gray-600">{{ $index + 1 }}</td>
                                            <td class="px-3 py-2 font-medium text-gray-700">{{ $hotel->nama_hotel }}</td>
                                            <td class="px-3 py-2 text-gray-600">{{ $hotel->kota ?? '-' }}</td>
                                            <td class="px-3 py-2 text-center">
                                                @if ($hotel->bintang)
                                                    <span class="text-yellow-500">
                                                        @for ($i = 1; $i <= $hotel->bintang; $i++)
                                                            <i class="fas fa-star text-xs"></i>
                                                        @endfor
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 text-xs">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Deskripsi -->
                @if ($produk->deskripsi)
                    <div class="mt-6 bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-align-left text-yellow-500 mr-2"></i> Deskripsi
                        </h6>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $produk->deskripsi }}</p>
                    </div>
                @endif

                <!-- Aksi Bawah -->
                <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-end gap-3">
                    <button type="button" onclick="window.print()"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        <i class="fas fa-print mr-2"></i> Cetak
                    </button>
                    <a href="{{ route('master.produk.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <a href="{{ route('master.produk.edit', $produk->id_produk) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit Produk
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Harga -->
    <div id="editHargaModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeEditModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-edit text-yellow-500 mr-2"></i>
                        Edit Harga Bulanan
                    </h3>
                    <button type="button" onclick="closeEditModal()"
                        class="text-gray-400 hover:text-gray-600 transition">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                <form id="editHargaForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Bulan <span class="text-red-500">*</span>
                                </label>
                                <select id="edit_bulan" name="bulan"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"
                                    required>
                                    <option value="">Pilih Bulan</option>
                                    @for ($b = 1; $b <= 12; $b++)
                                        <option value="{{ $b }}">
                                            {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Tahun <span class="text-red-500">*</span>
                                </label>
                                <select id="edit_tahun" name="tahun"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"
                                    required>
                                    <option value="">Pilih Tahun</option>
                                    @for ($y = date('Y'); $y <= date('Y') + 5; $y++)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Harga (Rp) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                <input type="text" id="edit_harga" name="harga" oninput="formatRupiah(this)"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"
                                    placeholder="0" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Flyer
                            </label>
                            @php
                                $currentHarga = $produk->hargaBulanan->firstWhere('id', old('edit_id'));
                            @endphp
                            @if (isset($currentHarga) && $currentHarga && $currentHarga->flyer_url)
                                <div class="mb-2 p-2 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="text-xs text-gray-500 mb-1">Flyer saat ini:</p>
                                    <img src="{{ $currentHarga->flyer_url }}" alt="Flyer"
                                        class="max-h-32 rounded-lg border border-gray-200">
                                </div>
                            @endif
                            <input type="file" id="edit_flyer" name="flyer" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                            <p class="text-xs text-gray-400 mt-1">PNG, JPG, JPEG, SVG (max 2MB). Kosongkan jika tidak ingin
                                mengganti.</p>
                        </div>
                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" id="edit_is_active" name="is_active" value="1"
                                class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                            <label for="edit_is_active" class="text-sm font-medium text-gray-700 cursor-pointer">
                                Aktif
                            </label>
                        </div>
                        <div class="flex gap-3 pt-4 border-t border-gray-100">
                            <button type="button" onclick="closeEditModal()"
                                class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                                <i class="fas fa-times mr-2"></i> Batal
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                                <i class="fas fa-save mr-2"></i> Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function formatRupiah(element) {
            let value = element.value.replace(/[^,\d]/g, '');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            element.value = value;
        }

        function toggleFormTambah() {
            const form = document.getElementById('formTambahHarga');
            form.classList.toggle('hidden');
        }

        function toggleHargaBulanan(id) {
            if (!confirm('Ubah status harga bulanan ini?')) return;

            const form = document.getElementById('toggle-harga-form-' + id);
            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const badgeContainer = document.querySelector(`.status-badge-${id}`);
                        if (badgeContainer) {
                            badgeContainer.innerHTML = data.badge;
                        }
                        showToast('success', data.message);
                    } else {
                        showToast('error', data.message || 'Gagal mengubah status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Terjadi kesalahan saat mengubah status');
                });
        }

        function confirmDeleteHarga(id) {
            if (confirm('Yakin ingin menghapus harga bulanan ini?')) {
                document.getElementById('delete-harga-form-' + id).submit();
            }
        }

        // ============================================
        // EDIT HARGA BULANAN
        // ============================================

        let currentEditId = null;

        function openEditModal(id) {
            currentEditId = id;

            const row = document.getElementById('harga-row-' + id);
            if (!row) {
                console.error('Row not found for id:', id);
                return;
            }

            const cells = row.querySelectorAll('td');
            if (cells.length < 6) {
                console.error('Invalid row structure');
                return;
            }

            const bulanText = cells[1].textContent.trim();
            const tahunText = cells[2].textContent.trim();
            const hargaText = cells[3].textContent.trim().replace('Rp ', '').replace(/\./g, '');
            const statusText = cells[5].querySelector('span')?.textContent.trim() || 'Nonaktif';

            document.getElementById('edit_bulan').value = getBulanFromText(bulanText);
            document.getElementById('edit_tahun').value = tahunText;
            document.getElementById('edit_harga').value = parseInt(hargaText || 0).toLocaleString('id-ID');
            document.getElementById('edit_is_active').checked = statusText === 'Aktif';

            // Set action form dengan ID yang benar
            const form = document.getElementById('editHargaForm');
            const updateUrl = `{{ route('master.produk.harga-bulanan.update', ['hargaId' => '__ID__']) }}`.replace(
                '__ID__', id);
            form.action = updateUrl;

            document.getElementById('editHargaModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            document.getElementById('editHargaModal').classList.add('hidden');
            document.body.style.overflow = '';
            document.getElementById('editHargaForm').action = '';
        }

        function getBulanFromText(text) {
            const bulanMap = {
                'Januari': 1,
                'Februari': 2,
                'Maret': 3,
                'April': 4,
                'Mei': 5,
                'Juni': 6,
                'Juli': 7,
                'Agustus': 8,
                'September': 9,
                'Oktober': 10,
                'November': 11,
                'Desember': 12
            };
            return bulanMap[text.trim()] || '';
        }

        function showToast(type, message) {
            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'fixed top-4 right-4 z-50 space-y-2 max-w-md';
                document.body.appendChild(toastContainer);
            }

            const toast = document.createElement('div');
            toast.className =
                `flex items-center gap-3 p-4 rounded-lg shadow-lg text-white transition-all transform translate-x-full ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            toast.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                <span class="text-sm font-medium">${message}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            `;

            toastContainer.appendChild(toast);
            setTimeout(() => toast.classList.remove('translate-x-full'), 100);
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // ============================================
        // PERBAIKAN: Bersihkan format sebelum submit
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            // Form Edit
            const editForm = document.getElementById('editHargaForm');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    const hargaInput = document.getElementById('edit_harga');
                    if (hargaInput) {
                        hargaInput.value = hargaInput.value.replace(/\./g, '');
                    }
                });
            }

            // Form Tambah
            const tambahForm = document.getElementById('formTambah');
            if (tambahForm) {
                tambahForm.addEventListener('submit', function(e) {
                    const hargaInput = document.getElementById('tambah_harga');
                    if (hargaInput) {
                        hargaInput.value = hargaInput.value.replace(/\./g, '');
                    }
                });
            }

            // Modal click outside
            const modal = document.getElementById('editHargaModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeEditModal();
                    }
                });
            }
        });
    </script>
@endpush
