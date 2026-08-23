@extends('layouts.app')

@section('title', 'Data Keluarga - Arrum Tour')
@section('page-title', 'Data Keluarga')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500">Transaksional</span>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500 font-medium">Keluarga</span>
    </li>
@endsection

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h5 class="text-sm font-semibold text-gray-700">Daftar Keluarga</h5>
                <p class="text-xs text-gray-400 mt-0.5">Kelola semua data keluarga</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-500">
                    Total: <span class="font-medium text-gray-700">{{ $data->total() }}</span> keluarga
                </span>
                <a href="{{ route('transaksional.keluarga.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                    <i class="fas fa-plus mr-2"></i> Tambah Keluarga
                </a>
            </div>
        </div>

        <div class="p-6">
            <form method="GET" action="{{ route('transaksional.keluarga.index') }}" id="filterForm" class="mb-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="search" id="search" placeholder="Cari keluarga..."
                            value="{{ request('search') }}"
                            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                    </div>
                    <div>
                        <select name="status_pembayaran" id="filterStatus"
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            <option value="">Semua Status</option>
                            <option value="Belum Bayar"
                                {{ request('status_pembayaran') == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                            <option value="DP" {{ request('status_pembayaran') == 'DP' ? 'selected' : '' }}>DP</option>
                            <option value="Setoran" {{ request('status_pembayaran') == 'Setoran' ? 'selected' : '' }}>
                                Setoran</option>
                            <option value="Lunas" {{ request('status_pembayaran') == 'Lunas' ? 'selected' : '' }}>Lunas
                            </option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                            class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium">
                            <i class="fas fa-search mr-2"></i> Filter
                        </button>
                        <a href="{{ route('transaksional.keluarga.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                            <i class="fas fa-undo mr-2"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <div id="table-container">
                @include('keluargas.table')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function applyFilter() {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            params.set('ajax', 'true');

            fetch(`{{ route('transaksional.keluarga.index') }}?${params.toString()}`)
                .then(response => response.text())
                .then(html => document.getElementById('table-container').innerHTML = html);
        }

        document.getElementById('filterStatus').addEventListener('change', applyFilter);

        let timeout = null;
        document.getElementById('search').addEventListener('keyup', function() {
            clearTimeout(timeout);
            timeout = setTimeout(applyFilter, 400);
        });
    </script>
@endpush
