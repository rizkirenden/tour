@extends('layouts.app')

@section('title', 'Rekap Pembayaran - Arrum Tour')
@section('page-title', 'Rekap Pembayaran')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.jamaah.index') }}" class="text-gray-500 hover:text-yellow-600">Transaksional</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500 font-medium">Rekap Pembayaran</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto space-y-6">

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Jamaah Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-800">Rekap Jamaah</h3>
                        <p class="text-xs text-gray-400">{{ $summary['jamaah']['total'] }} jamaah terdaftar</p>
                    </div>
                    <a href="{{ route('transaksional.rekap-pembayaran.jamaah') }}"
                        class="ml-auto text-xs text-blue-500 hover:text-blue-700 font-medium">
                        Detail <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="grid grid-cols-3 gap-2 text-xs">
                    <div class="bg-green-50 rounded-lg p-2 border border-green-100">
                        <p class="text-gray-500">Dibayar</p>
                        <p class="font-bold text-green-700">Rp
                            {{ number_format($summary['jamaah']['total_dibayar'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-red-50 rounded-lg p-2 border border-red-100">
                        <p class="text-gray-500">Sisa</p>
                        <p class="font-bold text-red-700">Rp
                            {{ number_format($summary['jamaah']['sisa_tagihan'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-amber-50 rounded-lg p-2 border border-amber-100">
                        <p class="text-gray-500">Kelebihan</p>
                        <p class="font-bold text-amber-700">Rp
                            {{ number_format($summary['jamaah']['sisa_kelebihan'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Keluarga Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-users text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-800">Rekap Keluarga</h3>
                        <p class="text-xs text-gray-400">{{ $summary['keluarga']['total'] }} keluarga terdaftar</p>
                    </div>
                    <a href="{{ route('transaksional.rekap-pembayaran.keluarga') }}"
                        class="ml-auto text-xs text-purple-500 hover:text-purple-700 font-medium">
                        Detail <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="grid grid-cols-3 gap-2 text-xs">
                    <div class="bg-green-50 rounded-lg p-2 border border-green-100">
                        <p class="text-gray-500">Dibayar</p>
                        <p class="font-bold text-green-700">Rp
                            {{ number_format($summary['keluarga']['total_dibayar'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-red-50 rounded-lg p-2 border border-red-100">
                        <p class="text-gray-500">Sisa</p>
                        <p class="font-bold text-red-700">Rp
                            {{ number_format($summary['keluarga']['sisa_tagihan'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-amber-50 rounded-lg p-2 border border-amber-100">
                        <p class="text-gray-500">Kelebihan</p>
                        <p class="font-bold text-amber-700">Rp
                            {{ number_format($summary['keluarga']['sisa_kelebihan'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- MENU AKSES CEPAT --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('transaksional.rekap-pembayaran.jamaah') }}"
                class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-5 text-white shadow-sm hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs opacity-80">Rekap Per</p>
                        <h3 class="text-lg font-bold">Jamaah</h3>
                        <p class="text-xs opacity-80 mt-1">Semua jamaah dengan status pembayaran</p>
                    </div>
                    <i class="fas fa-user text-3xl opacity-40"></i>
                </div>
            </a>

            <a href="{{ route('transaksional.rekap-pembayaran.keluarga') }}"
                class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl p-5 text-white shadow-sm hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs opacity-80">Rekap Per</p>
                        <h3 class="text-lg font-bold">Keluarga</h3>
                        <p class="text-xs opacity-80 mt-1">Ringkasan pembayaran per keluarga</p>
                    </div>
                    <i class="fas fa-users text-3xl opacity-40"></i>
                </div>
            </a>

            <a href="{{ route('transaksional.rekap-pembayaran.transaksi') }}"
                class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-2xl p-5 text-white shadow-sm hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs opacity-80">Log Semua</p>
                        <h3 class="text-lg font-bold">Transaksi</h3>
                        <p class="text-xs opacity-80 mt-1">Riwayat transaksi masuk & keluar</p>
                    </div>
                    <i class="fas fa-history text-3xl opacity-40"></i>
                </div>
            </a>
        </div>

    </div>
@endsection
