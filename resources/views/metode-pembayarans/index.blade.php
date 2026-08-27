@extends('layouts.app')

@section('title', 'Metode Pembayaran - Arrum Tour')
@section('page-title', 'Metode Pembayaran')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500 font-medium">Master Data</span>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500 font-medium">Metode Pembayaran</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h5 class="text-sm font-semibold text-gray-700">Daftar Metode Pembayaran</h5>
                        <p class="text-xs text-gray-400 mt-0.5">Kelola metode pembayaran yang tersedia</p>
                    </div>
                    <a href="{{ route('master.metode-pembayaran.create') }}"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-plus mr-2"></i> Tambah Metode
                    </a>
                </div>
            </div>

            <div class="p-6">
                <!-- Filter -->
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <form action="{{ route('master.metode-pembayaran.index') }}" method="GET"
                        class="flex flex-wrap items-center gap-3">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm w-64"
                                placeholder="Cari metode pembayaran...">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>

                        <select name="jenis_pembayaran"
                            class="px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            <option value="">Semua Jenis</option>
                            <option value="bank_transfer"
                                {{ request('jenis_pembayaran') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer
                            </option>
                            <option value="cash" {{ request('jenis_pembayaran') == 'cash' ? 'selected' : '' }}>Tunai
                            </option>
                            <option value="e_wallet" {{ request('jenis_pembayaran') == 'e_wallet' ? 'selected' : '' }}>
                                E-Wallet</option>
                        </select>

                        <button type="submit"
                            class="px-4 py-2 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 transition text-sm">
                            <i class="fas fa-filter mr-2"></i> Filter
                        </button>

                        @if (request('search') || request('jenis_pembayaran'))
                            <a href="{{ route('master.metode-pembayaran.index') }}"
                                class="text-sm text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times mr-1"></i> Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Table -->
                @include('metode-pembayarans.table')
            </div>
        </div>
    </div>
@endsection
