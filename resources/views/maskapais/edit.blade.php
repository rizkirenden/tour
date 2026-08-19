@extends('layouts.app')

@section('title', 'Edit Maskapai - Arrum Tour')
@section('page-title', 'Edit Maskapai')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.maskapai.index') }}" class="text-gray-500 hover:text-yellow-600">Master Data</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.maskapai.index') }}" class="text-gray-500 hover:text-yellow-600">Maskapai</a>
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
                    <h5 class="text-sm font-semibold text-gray-700">Form Edit Maskapai</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Edit informasi maskapai</p>
                </div>
                <a href="{{ route('master.maskapai.show', $maskapai->id_maskapai) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <i class="fas fa-eye mr-2"></i> Lihat Detail
                </a>
            </div>

            <form action="{{ route('master.maskapai.update', $maskapai->id_maskapai) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kode Maskapai <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kode_maskapai"
                                value="{{ old('kode_maskapai', $maskapai->kode_maskapai) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm uppercase"
                                placeholder="Contoh: GA, SV, EK" required>
                            @error('kode_maskapai')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Maskapai <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_maskapai"
                                value="{{ old('nama_maskapai', $maskapai->nama_maskapai) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Contoh: Garuda Indonesia" required>
                            @error('nama_maskapai')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Tipe Penerbangan <span class="text-red-500">*</span>
                        </label>
                        <select name="tipe_penerbangan"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            <option value="">Pilih Tipe</option>
                            <option value="Domestik"
                                {{ old('tipe_penerbangan', $maskapai->tipe_penerbangan) == 'Domestik' ? 'selected' : '' }}>
                                Domestik</option>
                            <option value="Internasional"
                                {{ old('tipe_penerbangan', $maskapai->tipe_penerbangan) == 'Internasional' ? 'selected' : '' }}>
                                Internasional</option>
                        </select>
                        @error('tipe_penerbangan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('master.maskapai.index') }}"
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
