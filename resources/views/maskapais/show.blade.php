@extends('layouts.app')

@section('title', 'Detail Maskapai - Arrum Tour')
@section('page-title', 'Detail Maskapai')

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
        <span class="text-gray-500 font-medium">Detail</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Detail Maskapai</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Informasi lengkap maskapai</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('master.maskapai.edit', $maskapai->id_maskapai) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('master.maskapai.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div
                    class="bg-gradient-to-r from-yellow-50 to-yellow-100/50 rounded-xl p-6 mb-6 border border-yellow-200/50">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h2 class="text-2xl font-bold text-gray-800">{{ $maskapai->nama_maskapai }}</h2>
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse($maskapai->tipePenerbangan as $tipe)
                                        @if ($tipe->tipe_penerbangan == 'Internasional')
                                            <span
                                                class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Internasional</span>
                                        @else
                                            <span
                                                class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Domestik</span>
                                        @endif
                                    @empty
                                        <span class="text-xs text-gray-400">Tidak ada tipe</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">ID: #{{ $maskapai->id_maskapai }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-yellow-500 mr-2"></i> Informasi Maskapai
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Nama Maskapai</dt>
                                <dd class="font-medium text-gray-700">{{ $maskapai->nama_maskapai }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Tipe Penerbangan</dt>
                                <dd>
                                    <div class="flex flex-wrap gap-1.5 justify-end">
                                        @forelse($maskapai->tipePenerbangan as $tipe)
                                            @if ($tipe->tipe_penerbangan == 'Internasional')
                                                <span
                                                    class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Internasional</span>
                                            @else
                                                <span
                                                    class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Domestik</span>
                                            @endif
                                        @empty
                                            <span class="text-gray-400">-</span>
                                        @endforelse
                                    </div>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-clock text-yellow-500 mr-2"></i> Informasi Sistem
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Dibuat pada</dt>
                                <dd class="font-medium text-gray-700">{{ $maskapai->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Terakhir diupdate</dt>
                                <dd class="font-medium text-gray-700">{{ $maskapai->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-end gap-3">
                    <button type="button" onclick="window.print()"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        <i class="fas fa-print mr-2"></i> Cetak
                    </button>
                    <a href="{{ route('master.maskapai.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <a href="{{ route('master.maskapai.edit', $maskapai->id_maskapai) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit Maskapai
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
