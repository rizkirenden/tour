@extends('layouts.app')

@section('title', 'Detail Metode Pembayaran - Arrum Tour')
@section('page-title', 'Detail Metode Pembayaran')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.metode-pembayaran.index') }}" class="text-gray-500 hover:text-yellow-600">Master Data</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.metode-pembayaran.index') }}" class="text-gray-500 hover:text-yellow-600">Metode
            Pembayaran</a>
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
                    <h5 class="text-sm font-semibold text-gray-700">Detail Metode Pembayaran</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Informasi lengkap metode pembayaran</p>
                </div>
                <div class="flex items-center gap-2">
                    @if ($metode->jenis_pembayaran !== 'cash')
                        <a href="{{ route('master.metode-pembayaran.edit', $metode->id_metode) }}"
                            class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                    @endif
                    <a href="{{ route('master.metode-pembayaran.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div
                    class="bg-gradient-to-r from-{{ $metode->color }}-50 to-{{ $metode->color }}-100/50 rounded-xl p-6 mb-6 border border-{{ $metode->color }}-200/50">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <div
                                    class="w-12 h-12 rounded-xl bg-{{ $metode->color }}-100 flex items-center justify-center">
                                    <i class="{{ $metode->icon }} text-{{ $metode->color }}-500 text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-800">{{ $metode->nama_bank }}</h2>
                                    <p class="text-gray-500 text-sm">
                                        <span
                                            class="px-2 py-1 bg-{{ $metode->color }}-100 text-{{ $metode->color }}-700 rounded text-xs font-medium">
                                            {{ ucfirst(str_replace('_', ' ', $metode->jenis_pembayaran)) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            @if ($metode->jenis_pembayaran === 'bank_transfer')
                                <p class="text-xl font-bold text-{{ $metode->color }}-600">{{ $metode->nomor_rekening }}
                                </p>
                                <p class="text-sm text-gray-500">a.n. {{ $metode->atas_nama }}</p>
                            @elseif($metode->jenis_pembayaran === 'e_wallet')
                                <p class="text-xl font-bold text-{{ $metode->color }}-600">{{ $metode->nomor_telepon }}
                                </p>
                                <p class="text-sm text-gray-500">{{ $metode->e_wallet_type }}</p>
                            @else
                                <p class="text-xl font-bold text-{{ $metode->color }}-600">Cash</p>
                                <p class="text-sm text-gray-500">Pembayaran tunai</p>
                            @endif
                            <span
                                class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-medium {{ $metode->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $metode->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="{{ $metode->icon }} text-{{ $metode->color }}-500 mr-2"></i>
                            Informasi {{ ucfirst(str_replace('_', ' ', $metode->jenis_pembayaran)) }}
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Jenis Pembayaran</dt>
                                <dd class="font-medium text-gray-700">
                                    {{ ucfirst(str_replace('_', ' ', $metode->jenis_pembayaran)) }}
                                </dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Kode</dt>
                                <dd class="font-medium text-gray-700">
                                    <span
                                        class="px-2 py-1 bg-{{ $metode->color }}-100 text-{{ $metode->color }}-700 rounded text-xs font-mono">
                                        {{ $metode->kode_bank }}
                                    </span>
                                </dd>
                            </div>
                            @if ($metode->jenis_pembayaran === 'bank_transfer')
                                <div class="flex justify-between text-sm">
                                    <dt class="text-gray-500">Nama Bank</dt>
                                    <dd class="font-medium text-gray-700">{{ $metode->nama_bank }}</dd>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <dt class="text-gray-500">Nomor Rekening</dt>
                                    <dd class="font-medium text-gray-700 font-mono">{{ $metode->nomor_rekening }}</dd>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <dt class="text-gray-500">Atas Nama</dt>
                                    <dd class="font-medium text-gray-700">{{ $metode->atas_nama }}</dd>
                                </div>
                            @elseif($metode->jenis_pembayaran === 'e_wallet')
                                <div class="flex justify-between text-sm">
                                    <dt class="text-gray-500">E-Wallet</dt>
                                    <dd class="font-medium text-gray-700">{{ $metode->e_wallet_type }}</dd>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <dt class="text-gray-500">Nomor Telepon</dt>
                                    <dd class="font-medium text-gray-700">{{ $metode->nomor_telepon }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-yellow-500 mr-2"></i> Informasi Sistem
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Status</dt>
                                <dd>
                                    <span
                                        class="px-2 py-0.5 rounded-full text-xs font-medium {{ $metode->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $metode->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Dibuat pada</dt>
                                <dd class="font-medium text-gray-700">{{ $metode->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Terakhir diupdate</dt>
                                <dd class="font-medium text-gray-700">{{ $metode->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-end gap-3">
                    <button type="button" onclick="window.print()"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        <i class="fas fa-print mr-2"></i> Cetak
                    </button>
                    <a href="{{ route('master.metode-pembayaran.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    @if ($metode->jenis_pembayaran !== 'cash')
                        <a href="{{ route('master.metode-pembayaran.edit', $metode->id_metode) }}"
                            class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                            <i class="fas fa-edit mr-2"></i> Edit Metode
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
