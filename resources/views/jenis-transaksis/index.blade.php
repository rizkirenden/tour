@extends('layouts.app')

@section('title', 'Jenis Transaksi - Arrum Tour')
@section('page-title', 'Jenis Transaksi')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500">Master Data</span>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500 font-medium">Jenis Transaksi</span>
    </li>
@endsection

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h5 class="text-sm font-semibold text-gray-700">Daftar Jenis Transaksi</h5>
                <p class="text-xs text-gray-400 mt-0.5">Kelola semua jenis transaksi</p>
            </div>
            <a href="{{ route('master.jenis-transaksi.create') }}"
                class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                <i class="fas fa-plus mr-2"></i> Tambah Jenis
            </a>
        </div>

        <div class="p-6">
            <div class="flex gap-3 mb-4">
                <div class="relative flex-1 max-w-xs">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" id="search" placeholder="Cari jenis transaksi..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                </div>
                <button onclick="applyFilter()"
                    class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>
            </div>

            <div id="table-container">
                @include('jenis-transaksis.table')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function applyFilter() {
            const search = document.getElementById('search').value;
            fetch(`{{ route('master.jenis-transaksi.index') }}?search=${search}&ajax=true`)
                .then(response => response.text())
                .then(html => document.getElementById('table-container').innerHTML = html);
        }

        let timeout = null;
        document.getElementById('search').addEventListener('keyup', function() {
            clearTimeout(timeout);
            timeout = setTimeout(applyFilter, 400);
        });
    </script>
@endpush
