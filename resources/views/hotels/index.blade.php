@extends('layouts.app')

@section('title', 'Hotel - Arrum Tour')
@section('page-title', 'Hotel')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500">Master Data</span>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500 font-medium">Hotel</span>
    </li>
@endsection

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h5 class="text-sm font-semibold text-gray-700">Daftar Hotel</h5>
                <p class="text-xs text-gray-400 mt-0.5">Kelola semua data hotel dan tipe kamar</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-500">
                    Total: <span class="font-medium text-gray-700">{{ $data->total() }}</span> hotel
                </span>
                <a href="{{ route('master.hotel.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                    <i class="fas fa-plus mr-2"></i> Tambah Hotel
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="flex gap-3 mb-4">
                <div class="relative flex-1 max-w-xs">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" id="search" placeholder="Cari hotel..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
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
                @include('hotels.table')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function applyFilter() {
            const search = document.getElementById('search').value;
            fetch(`{{ route('master.hotel.index') }}?search=${search}&ajax=true`)
                .then(response => response.text())
                .then(html => document.getElementById('table-container').innerHTML = html);
        }

        function resetFilter() {
            document.getElementById('search').value = '';
            applyFilter();
        }

        let timeout = null;
        document.getElementById('search').addEventListener('keyup', function() {
            clearTimeout(timeout);
            timeout = setTimeout(applyFilter, 400);
        });
    </script>
@endpush
@extends('layouts.app')

@section('title', 'Hotel - Arrum Tour')
@section('page-title', 'Hotel')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500">Master Data</span>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500 font-medium">Hotel</span>
    </li>
@endsection

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h5 class="text-sm font-semibold text-gray-700">Daftar Hotel</h5>
                <p class="text-xs text-gray-400 mt-0.5">Kelola semua data hotel dan tipe kamar</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-500">
                    Total: <span class="font-medium text-gray-700">{{ $data->total() }}</span> hotel
                </span>
                <a href="{{ route('master.hotel.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                    <i class="fas fa-plus mr-2"></i> Tambah Hotel
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="flex gap-3 mb-4">
                <div class="relative flex-1 max-w-xs">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" id="search" placeholder="Cari hotel..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
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
                @include('hotels.table')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function applyFilter() {
            const search = document.getElementById('search').value;
            fetch(`{{ route('master.hotel.index') }}?search=${search}&ajax=true`)
                .then(response => response.text())
                .then(html => document.getElementById('table-container').innerHTML = html);
        }

        function resetFilter() {
            document.getElementById('search').value = '';
            applyFilter();
        }

        let timeout = null;
        document.getElementById('search').addEventListener('keyup', function() {
            clearTimeout(timeout);
            timeout = setTimeout(applyFilter, 400);
        });
    </script>
@endpush
