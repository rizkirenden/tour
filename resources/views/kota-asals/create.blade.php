@extends('layouts.app')

@section('title', 'Tambah Kota Asal - Arrum Tour')
@section('page-title', 'Tambah Kota Asal')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.kota-asal.index') }}" class="text-gray-500 hover:text-yellow-600">Master Data</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.kota-asal.index') }}" class="text-gray-500 hover:text-yellow-600">Kota Asal</a>
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
                <h5 class="text-sm font-semibold text-gray-700">Form Tambah Kota Asal</h5>
            </div>

            <form action="{{ route('master.kota-asal.store') }}" method="POST">
                @csrf

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Kota <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_kota" value="{{ old('nama_kota') }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: Jakarta" required>
                            @error('nama_kota')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Provinsi</label>
                            <input type="text" name="provinsi" value="{{ old('provinsi') }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: DKI Jakarta">
                            @error('provinsi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Pulau</label>
                            <select name="pulau"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                <option value="">Pilih Pulau</option>
                                <option value="Jawa" {{ old('pulau') == 'Jawa' ? 'selected' : '' }}>Jawa</option>
                                <option value="Sumatera" {{ old('pulau') == 'Sumatera' ? 'selected' : '' }}>Sumatera
                                </option>
                                <option value="Kalimantan" {{ old('pulau') == 'Kalimantan' ? 'selected' : '' }}>Kalimantan
                                </option>
                                <option value="Sulawesi" {{ old('pulau') == 'Sulawesi' ? 'selected' : '' }}>Sulawesi
                                </option>
                                <option value="Bali" {{ old('pulau') == 'Bali' ? 'selected' : '' }}>Bali</option>
                                <option value="Nusa Tenggara" {{ old('pulau') == 'Nusa Tenggara' ? 'selected' : '' }}>Nusa
                                    Tenggara</option>
                                <option value="Maluku" {{ old('pulau') == 'Maluku' ? 'selected' : '' }}>Maluku</option>
                                <option value="Papua" {{ old('pulau') == 'Papua' ? 'selected' : '' }}>Papua</option>
                            </select>
                            @error('pulau')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Bandara Terdekat</label>
                            <input type="text" name="bandara_terdekat" value="{{ old('bandara_terdekat') }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: Soekarno-Hatta">
                            @error('bandara_terdekat')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('master.kota-asal.index') }}"
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
