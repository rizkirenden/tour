@extends('layouts.app')

@section('title', 'Paket Hotel - Arrum Tour')
@section('page-title', 'Paket Hotel')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500">Master Data</span>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500 font-medium">Paket Hotel</span>
    </li>
@endsection

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h5 class="text-sm font-semibold text-gray-700">Daftar Paket Hotel</h5>
                <p class="text-xs text-gray-400 mt-0.5">Kelola paket hotel untuk setiap produk</p>
            </div>
            <a href="{{ route('master.paket-hotel.create') }}"
                class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                <i class="fas fa-plus mr-2"></i> Tambah Paket Hotel
            </a>
        </div>

        <div class="p-6">
            <div class="flex flex-wrap gap-3 mb-4">
                <div class="relative flex-1 max-w-xs">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" id="search" placeholder="Cari paket hotel..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                </div>
                <div class="relative max-w-xs">
                    <select id="filter_produk"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm appearance-none bg-white">
                        <option value="">Semua Produk</option>
                        @foreach ($produkOptions as $produk)
                            <option value="{{ $produk->id_produk }}">{{ $produk->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>
                <button onclick="applyFilter()"
                    class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>
                <button onclick="resetFilter()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <i class="fas fa-undo mr-2"></i> Reset
                </button>
            </div>

            <div id="table-container">
                @include('paket-hotels.table')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function applyFilter() {
            const search = document.getElementById('search').value;
            const produkId = document.getElementById('filter_produk').value;
            let url = `{{ route('master.paket-hotel.index') }}?ajax=true`;
            if (search) url += `&search=${search}`;
            if (produkId) url += `&produk_id=${produkId}`;

            fetch(url)
                .then(response => response.text())
                .then(html => document.getElementById('table-container').innerHTML = html);
        }

        function resetFilter() {
            document.getElementById('search').value = '';
            document.getElementById('filter_produk').value = '';
            applyFilter();
        }

        let timeout = null;
        document.getElementById('search').addEventListener('keyup', function() {
            clearTimeout(timeout);
            timeout = setTimeout(applyFilter, 400);
        });

        document.getElementById('filter_produk').addEventListener('change', function() {
            applyFilter();
        });
    </script>
@endpush
