@extends('layouts.app')

@section('title', 'Riwayat Reset Diskon - Arrum Tour')
@section('page-title', 'Riwayat Reset Diskon')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.diskon.index') }}" class="text-gray-500 hover:text-yellow-600">Master Data</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.diskon.index') }}" class="text-gray-500 hover:text-yellow-600">Diskon</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500 font-medium">Riwayat Reset</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Riwayat Reset Diskon</h5>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Riwayat reset untuk diskon <strong>{{ $diskon->nama_diskon }}</strong>
                        <span class="ml-2 bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full text-xs">
                            Total Reset: {{ $diskon->reset_count }} kali
                        </span>
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('master.diskon.show', $diskon->id_diskon) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail
                    </a>
                    <a href="{{ route('master.diskon.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-list mr-2"></i> Daftar Diskon
                    </a>
                </div>
            </div>

            <div class="p-6">
                @if ($riwayats->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        #</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Tanggal Reset</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Nama Diskon</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Nilai</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Kuota Sebelum</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Kuota Baru</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Digunakan</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Catatan</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Direset Oleh</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Reset Ke</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($riwayats as $index => $riwayat)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-4 py-3 text-gray-500">{{ $riwayats->firstItem() + $index }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $riwayat->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="font-medium text-gray-800">{{ $riwayat->nama_diskon }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-right font-bold text-yellow-600">
                                            {{ 'Rp ' . number_format($riwayat->nilai_diskon, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="text-sm text-gray-600">{{ $riwayat->kuota ?? '∞' }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span
                                                class="text-sm font-medium text-green-600">{{ $riwayat->kuota_baru ?? '∞' }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="text-sm text-gray-600">{{ $riwayat->sudah_digunakan }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 text-sm max-w-xs truncate">
                                            {{ $riwayat->catatan ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 text-sm">
                                            {{ $riwayat->direset_oleh ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span
                                                class="inline-flex px-2.5 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs font-mono font-semibold">
                                                #{{ $riwayat->reset_ke }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 px-2">
                        {{ $riwayats->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                                <i class="fas fa-history text-gray-300 text-3xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada riwayat reset</p>
                            <p class="text-gray-400 text-sm mt-1">Diskon ini belum pernah direset</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
