@extends('layouts.app')

@section('title', 'Tambah Keberangkatan - Arrum Tour')
@section('page-title', 'Tambah Keberangkatan')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.departure.index') }}" class="text-gray-500 hover:text-yellow-600">Transaksional</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.departure.index') }}" class="text-gray-500 hover:text-yellow-600">Keberangkatan</a>
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
                <h5 class="text-sm font-semibold text-gray-700">Form Tambah Keberangkatan</h5>
                <p class="text-xs text-gray-400 mt-0.5">Isi informasi dasar terlebih dahulu</p>
            </div>

            <form action="{{ route('transaksional.departure.store') }}" method="POST">
                @csrf

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Produk Paket <span class="text-red-500">*</span>
                            </label>
                            <select name="id_produk"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($produkOptions as $produk)
                                    <option value="{{ $produk->id_produk }}"
                                        {{ old('id_produk') == $produk->id_produk ? 'selected' : '' }}>
                                        {{ $produk->nama_produk }} ({{ $produk->durasi_hari }} Hari)
                                    </option>
                                @endforeach
                            </select>
                            @error('id_produk')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Keberangkatan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_keberangkatan" value="{{ old('nama_keberangkatan') }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: Batch 1 Januari 2026" required>
                            @error('nama_keberangkatan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Tanggal Keberangkatan <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_keberangkatan" value="{{ old('tanggal_keberangkatan') }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                required>
                            @error('tanggal_keberangkatan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Tanggal Kepulangan <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_kepulangan" value="{{ old('tanggal_kepulangan') }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                required>
                            @error('tanggal_kepulangan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Bulan dan Tahun Keberangkatan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Bulan Keberangkatan
                            </label>
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
                            <p class="text-xs text-gray-400 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Digunakan untuk filter jamaah yang akan ditambahkan ke keberangkatan ini
                            </p>
                            @error('bulan_keberangkatan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Tahun Keberangkatan
                            </label>
                            <input type="number" name="tahun_keberangkatan"
                                value="{{ old('tahun_keberangkatan', date('Y')) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                min="2000" max="{{ date('Y') + 10 }}">
                            <p class="text-xs text-gray-400 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Digunakan untuk filter jamaah yang akan ditambahkan ke keberangkatan ini
                            </p>
                            @error('tahun_keberangkatan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kuota <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="kuota" value="{{ old('kuota', 0) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                min="1" required>
                            @error('kuota')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Keberangkatan</label>
                            <select name="id_status"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                <option value="">-- Pilih Status --</option>
                                @foreach ($statusOptions as $status)
                                    <option value="{{ $status->id_status }}"
                                        {{ old('id_status') == $status->id_status ? 'selected' : '' }}>
                                        {{ $status->nama_status }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('transaksional.departure.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors text-sm font-medium">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 transition-colors text-sm font-medium shadow-sm hover:shadow">
                            <i class="fas fa-save mr-2"></i> Simpan & Lanjutkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
