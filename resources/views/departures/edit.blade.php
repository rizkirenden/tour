{{-- resources/views/departures/edit.blade.php --}}

@extends('layouts.app')

@section('title', 'Edit Keberangkatan - Arrum Tour')
@section('page-title', 'Edit Keberangkatan')

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
        <span class="text-gray-500 font-medium">Edit</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h5 class="text-sm font-semibold text-gray-700">Form Edit Keberangkatan</h5>
                <p class="text-xs text-gray-400 mt-0.5">Edit informasi dasar keberangkatan</p>
            </div>

            <form action="{{ route('transaksional.departure.update', $departure->id_departure) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <!-- Informasi Dasar -->
                    <div>
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-yellow-500 mr-2"></i> Informasi Dasar
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Produk Paket <span class="text-red-500">*</span>
                                </label>
                                <select name="id_produk" id="id_produk"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($produkOptions as $produk)
                                        <option value="{{ $produk->id_produk }}"
                                            {{ old('id_produk', $departure->id_produk) == $produk->id_produk ? 'selected' : '' }}>
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
                                <input type="text" name="nama_keberangkatan"
                                    value="{{ old('nama_keberangkatan', $departure->nama_keberangkatan) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Contoh: Batch 1 Januari 2026" required>
                                @error('nama_keberangkatan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tanggal Keberangkatan <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_keberangkatan"
                                    value="{{ old('tanggal_keberangkatan', $departure->tanggal_keberangkatan->format('Y-m-d')) }}"
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
                                <input type="date" name="tanggal_kepulangan"
                                    value="{{ old('tanggal_kepulangan', $departure->tanggal_kepulangan->format('Y-m-d')) }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    required>
                                @error('tanggal_kepulangan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kuota <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="kuota" value="{{ old('kuota', $departure->kuota) }}"
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
                                            {{ old('id_status', $departure->id_status) == $status->id_status ? 'selected' : '' }}>
                                            {{ $status->nama_status }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Sistem -->
                    <div class="border-t border-gray-200 pt-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-calendar-alt text-yellow-500 mr-2"></i> Informasi Sistem
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Kode Keberangkatan</dt>
                                <dd class="font-medium text-gray-700">{{ $departure->kode_keberangkatan }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Jamaah Terdaftar</dt>
                                <dd class="font-medium text-gray-700">{{ $departure->jamaah_terdaftar }} /
                                    {{ $departure->kuota }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Dibuat Pada</dt>
                                <dd class="font-medium text-gray-700">{{ $departure->created_at->format('d M Y H:i') }}
                                </dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Terakhir Diperbarui</dt>
                                <dd class="font-medium text-gray-700">{{ $departure->updated_at->format('d M Y H:i') }}
                                </dd>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('transaksional.departure.show', $departure->id_departure) }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors text-sm font-medium">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail
                        </a>
                        <a href="{{ route('transaksional.departure.index') }}"
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
