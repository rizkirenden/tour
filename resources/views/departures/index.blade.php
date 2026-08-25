@extends('layouts.app')

@section('title', 'Keberangkatan - Arrum Tour')
@section('page-title', 'Manajemen Keberangkatan')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500">Transaksional</span>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500 font-medium">Keberangkatan</span>
    </li>
@endsection

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h5 class="text-sm font-semibold text-gray-700">Daftar Keberangkatan</h5>
                <p class="text-xs text-gray-400 mt-0.5">Kelola semua jadwal keberangkatan</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="recalculateAll()"
                    class="inline-flex items-center px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium">
                    <i class="fas fa-sync-alt mr-2"></i> Recalculate All
                </button>
                <a href="{{ route('transaksional.departure.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                    <i class="fas fa-plus mr-2"></i> Tambah Keberangkatan
                </a>
            </div>
        </div>

        <div class="p-6">
            <form id="filterForm" class="flex flex-wrap gap-3 mb-4">
                <div class="relative flex-1 max-w-xs">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" id="search" placeholder="Cari keberangkatan..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                </div>
                <div class="relative max-w-xs">
                    <select name="status" id="statusFilter"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                        <option value="">Semua Status</option>
                        @foreach ($statusOptions ?? [] as $status)
                            <option value="{{ $status->id_status }}">{{ $status->nama_status }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>
                <button type="reset" onclick="resetFilter()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <i class="fas fa-undo mr-2"></i> Reset
                </button>
            </form>

            <div id="table-container">
                @include('departures.table')
            </div>
        </div>
    </div>

    <form id="recalculateAllForm" action="{{ route('transaksional.departure.recalculate-all') }}" method="POST"
        class="hidden">
        @csrf
    </form>
@endsection

@push('scripts')
    <script>
        function applyFilter() {
            const search = document.getElementById('search').value;
            const status = document.getElementById('statusFilter').value;
            fetch(`{{ route('transaksional.departure.index') }}?search=${search}&status=${status}&ajax=true`)
                .then(response => response.text())
                .then(html => document.getElementById('table-container').innerHTML = html);
        }

        function resetFilter() {
            document.getElementById('search').value = '';
            document.getElementById('statusFilter').value = '';
            applyFilter();
        }

        function recalculateAll() {
            if (confirm('Yakin ingin menghitung ulang semua data keuangan?')) {
                document.getElementById('recalculateAllForm').submit();
            }
        }

        let timeout = null;
        document.getElementById('search').addEventListener('keyup', function() {
            clearTimeout(timeout);
            timeout = setTimeout(applyFilter, 400);
        });

        document.getElementById('statusFilter').addEventListener('change', applyFilter);
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            applyFilter();
        });
    </script>
@endpush
