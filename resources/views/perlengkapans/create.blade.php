@extends('layouts.app')

@section('title', 'Tambah Perlengkapan - Arrum Tour')
@section('page-title', 'Tambah Perlengkapan')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.perlengkapan.index') }}" class="text-gray-500 hover:text-yellow-600">Master Data</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.perlengkapan.index') }}" class="text-gray-500 hover:text-yellow-600">Perlengkapan</a>
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
                <h5 class="text-sm font-semibold text-gray-700">Form Tambah Perlengkapan</h5>
            </div>

            <form action="{{ route('master.perlengkapan.store') }}" method="POST" id="perlengkapanForm">
                @csrf

                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama Perlengkapan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_perlengkapan" value="{{ old('nama_perlengkapan') }}"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                            placeholder="Contoh: Koper Besar" required>
                        @error('nama_perlengkapan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                        <textarea name="deskripsi" rows="3"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                            placeholder="Deskripsikan perlengkapan ini...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Harga Satuan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                <input type="text" name="harga_satuan" id="harga_satuan"
                                    value="{{ old('harga_satuan') }}"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="0" oninput="formatRupiah(this)" required>
                            </div>
                            @error('harga_satuan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Satuan</label>
                            <select name="satuan"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                <option value="">Pilih Satuan</option>
                                <option value="Pcs" {{ old('satuan') == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                                <option value="Set" {{ old('satuan') == 'Set' ? 'selected' : '' }}>Set</option>
                                <option value="Pair" {{ old('satuan') == 'Pair' ? 'selected' : '' }}>Pasang</option>
                                <option value="Unit" {{ old('satuan') == 'Unit' ? 'selected' : '' }}>Unit</option>
                                <option value="Buah" {{ old('satuan') == 'Buah' ? 'selected' : '' }}>Buah</option>
                                <option value="Lembar" {{ old('satuan') == 'Lembar' ? 'selected' : '' }}>Lembar</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
                            <select name="kategori"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoriOptions as $key => $value)
                                    <option value="{{ $key }}" {{ old('kategori') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('master.perlengkapan.index') }}"
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

    @push('scripts')
        <script>
            // Format Rupiah
            function formatRupiah(element) {
                let value = element.value.replace(/[^,\d]/g, '');
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                element.value = value;
            }

            // Form submit - clean harga_satuan
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('perlengkapanForm');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        const input = document.getElementById('harga_satuan');
                        if (input) {
                            input.value = input.value.replace(/\./g, '');
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
