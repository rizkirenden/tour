@extends('layouts.app')

@section('title', 'Edit Diskon - Arrum Tour')
@section('page-title', 'Edit Diskon')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.diskon.index') }}" class="text-gray-500 hover:text-yellow-600">Master Data</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.diskon.index') }}" class="text-gray-500 hover:text-yellow-600">Diskon</a>
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
                    <h5 class="text-sm font-semibold text-gray-700">Form Edit Diskon</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Edit informasi diskon</p>
                </div>
                <a href="{{ route('master.diskon.show', $diskon->id_diskon) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <i class="fas fa-eye mr-2"></i> Lihat Detail
                </a>
            </div>

            <form action="{{ route('master.diskon.update', $diskon->id_diskon) }}" method="POST" id="diskonForm">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama Diskon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_diskon" value="{{ old('nama_diskon', $diskon->nama_diskon) }}"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                            placeholder="Contoh: Promo Ramadhan" required>
                        @error('nama_diskon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nilai Diskon <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                <input type="text" name="nilai_diskon" id="nilai_diskon"
                                    value="{{ old('nilai_diskon', number_format($diskon->nilai_diskon, 0, ',', '.')) }}"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="0" oninput="formatRupiah(this)" required>
                            </div>
                            @error('nilai_diskon')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-400 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Masukkan nilai diskon dalam Rupiah (contoh: 2.000.000)
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Berlaku Untuk Produk</label>
                            <select name="berlaku_untuk_produk"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                @foreach ($produkOptions as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ old('berlaku_untuk_produk', $diskon->berlaku_untuk_produk) == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Kuota</label>
                            <input type="number" name="kuota" value="{{ old('kuota', $diskon->kuota) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Kosongkan jika unlimited">
                            <p class="text-xs text-gray-400 mt-1">Biarkan kosong untuk unlimited</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Sudah Digunakan</label>
                            <input type="number" name="sudah_digunakan"
                                value="{{ old('sudah_digunakan', $diskon->sudah_digunakan) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="0" min="0">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('master.diskon.index') }}"
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

    @push('scripts')
        <script>
            // Format Rupiah
            function formatRupiah(element) {
                let value = element.value.replace(/[^,\d]/g, '');
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                element.value = value;
            }

            // Form submit - clean nilai_diskon
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('diskonForm');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        const input = document.getElementById('nilai_diskon');
                        if (input) {
                            input.value = input.value.replace(/\./g, '');
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
