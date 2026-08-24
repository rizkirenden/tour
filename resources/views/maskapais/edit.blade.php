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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Tipe Penerbangan <span class="text-red-500">*</span>
                            <span class="text-xs text-gray-400 font-normal">(pilih minimal 1)</span>
                        </label>
                        @php
                            $selectedTipes = old(
                                'tipe_penerbangan',
                                $maskapai->tipePenerbangan->pluck('tipe_penerbangan')->toArray(),
                            );
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <label
                                class="flex items-center p-4 border border-gray-200 rounded-xl hover:bg-green-50 transition-colors cursor-pointer {{ in_array('Domestik', $selectedTipes) ? 'border-green-500 bg-green-50' : '' }}">
                                <input type="checkbox" name="tipe_penerbangan[]" value="Domestik"
                                    {{ in_array('Domestik', $selectedTipes) ? 'checked' : '' }}
                                    class="w-4 h-4 text-green-500 border-gray-300 rounded focus:ring-green-500">
                                <div class="ml-3">
                                    <span class="text-sm font-medium text-gray-700">Domestik</span>
                                    <p class="text-xs text-gray-400">Penerbangan dalam negeri</p>
                                </div>
                                <span
                                    class="ml-auto inline-flex px-2.5 py-0.5 bg-green-100 text-green-700 rounded-full text-xs">Domestik</span>
                            </label>

                            <label
                                class="flex items-center p-4 border border-gray-200 rounded-xl hover:bg-blue-50 transition-colors cursor-pointer {{ in_array('Internasional', $selectedTipes) ? 'border-blue-500 bg-blue-50' : '' }}">
                                <input type="checkbox" name="tipe_penerbangan[]" value="Internasional"
                                    {{ in_array('Internasional', $selectedTipes) ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-500 border-gray-300 rounded focus:ring-blue-500">
                                <div class="ml-3">
                                    <span class="text-sm font-medium text-gray-700">Internasional</span>
                                    <p class="text-xs text-gray-400">Penerbangan luar negeri</p>
                                </div>
                                <span
                                    class="ml-auto inline-flex px-2.5 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs">Internasional</span>
                            </label>
                        </div>
                        @error('tipe_penerbangan')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-400 mt-3">
                            <i class="fas fa-info-circle mr-1"></i>
                            Satu maskapai bisa memiliki dua tipe penerbangan (Domestik dan Internasional)
                        </p>
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
