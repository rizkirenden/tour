@extends('layouts.app')

@section('title', 'Edit Status Keberangkatan - Arrum Tour')
@section('page-title', 'Edit Status Keberangkatan')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.status-keberangkatan.index') }}" class="text-gray-500 hover:text-yellow-600">Master Data</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.status-keberangkatan.index') }}" class="text-gray-500 hover:text-yellow-600">Status
            Keberangkatan</a>
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
                    <h5 class="text-sm font-semibold text-gray-700">Form Edit Status Keberangkatan</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Edit informasi status keberangkatan</p>
                </div>
                <a href="{{ route('master.status-keberangkatan.show', $status->id_status) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <i class="fas fa-eye mr-2"></i> Lihat Detail
                </a>
            </div>

            <form action="{{ route('master.status-keberangkatan.update', $status->id_status) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama Status <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_status" value="{{ old('nama_status', $status->nama_status) }}"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                            placeholder="Contoh: Direncanakan, Konfirmasi, Lunas" required>
                        @error('nama_status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Warna</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                            @foreach ($warnaOptions as $key => $label)
                                <label
                                    class="flex items-center gap-2 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition {{ old('warna', $status->warna) == $key ? 'border-yellow-500 bg-yellow-50' : '' }}">
                                    <input type="radio" name="warna" value="{{ $key }}"
                                        {{ old('warna', $status->warna) == $key ? 'checked' : '' }}
                                        class="w-4 h-4 text-yellow-500 focus:ring-yellow-500">
                                    <div class="flex items-center gap-2">
                                        <span class="w-4 h-4 rounded-full inline-block"
                                            style="background-color: {{ $key }};"></span>
                                        <span class="text-sm text-gray-700">{{ $label }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('warna')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan</label>
                        <textarea name="keterangan" rows="3"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                            placeholder="Deskripsikan status ini...">{{ old('keterangan', $status->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('master.status-keberangkatan.index') }}"
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
