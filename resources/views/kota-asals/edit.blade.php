@extends('layouts.app')

@section('title', 'Edit Kota Asal - Arrum Tour')
@section('page-title', 'Edit Kota Asal')

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
        <span class="text-gray-500 font-medium">Edit</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Form Edit Kota Asal</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Edit informasi kota asal</p>
                </div>
                <a href="{{ route('master.kota-asal.show', $kota->id_kota) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <i class="fas fa-eye mr-2"></i> Lihat Detail
                </a>
            </div>

            <form action="{{ route('master.kota-asal.update', $kota->id_kota) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Kota <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_kota" value="{{ old('nama_kota', $kota->nama_kota) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: Jakarta" required>
                            @error('nama_kota')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Provinsi</label>
                            <input type="text" name="provinsi" value="{{ old('provinsi', $kota->provinsi) }}"
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
                                <option value="Jawa" {{ old('pulau', $kota->pulau) == 'Jawa' ? 'selected' : '' }}>Jawa
                                </option>
                                <option value="Sumatera" {{ old('pulau', $kota->pulau) == 'Sumatera' ? 'selected' : '' }}>
                                    Sumatera</option>
                                <option value="Kalimantan"
                                    {{ old('pulau', $kota->pulau) == 'Kalimantan' ? 'selected' : '' }}>Kalimantan</option>
                                <option value="Sulawesi" {{ old('pulau', $kota->pulau) == 'Sulawesi' ? 'selected' : '' }}>
                                    Sulawesi</option>
                                <option value="Bali" {{ old('pulau', $kota->pulau) == 'Bali' ? 'selected' : '' }}>Bali
                                </option>
                                <option value="Nusa Tenggara"
                                    {{ old('pulau', $kota->pulau) == 'Nusa Tenggara' ? 'selected' : '' }}>Nusa Tenggara
                                </option>
                                <option value="Maluku" {{ old('pulau', $kota->pulau) == 'Maluku' ? 'selected' : '' }}>
                                    Maluku</option>
                                <option value="Papua" {{ old('pulau', $kota->pulau) == 'Papua' ? 'selected' : '' }}>Papua
                                </option>
                            </select>
                            @error('pulau')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Bandara Terdekat</label>
                            <input type="text" name="bandara_terdekat"
                                value="{{ old('bandara_terdekat', $kota->bandara_terdekat) }}"
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
                            <i class="fas fa-save mr-2"></i> Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
