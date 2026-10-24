@extends('layouts.app')

@section('title', 'Detail Pengeluaran - Arrum Tour')
@section('page-title', 'Detail Pengeluaran Lain-lain')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.rekap-keuangan.index') }}" class="text-gray-500 hover:text-yellow-600">Rekap
            Keuangan</a>
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
                    <h5 class="text-sm font-semibold text-gray-700">Detail Pengeluaran Lain-lain</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Informasi lengkap pengeluaran</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <a href="{{ route('transaksional.rekap-keuangan.edit', $item->id_pengeluaran_lain) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <button onclick="confirmHapusPengeluaran({{ $item->id_pengeluaran_lain }})"
                        class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium">
                        <i class="fas fa-trash mr-2"></i> Hapus
                    </button>
                    <a href="{{ route('transaksional.rekap-keuangan.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <form id="delete-form"
                action="{{ route('transaksional.rekap-keuangan.pengeluaran.destroy', $item->id_pengeluaran_lain) }}"
                method="POST" class="hidden">
                @csrf @method('DELETE')
            </form>

            <div class="p-6 space-y-6">
                {{-- CARD NOMINAL --}}
                <div class="bg-gradient-to-r from-red-50 to-rose-100 rounded-xl p-6 border-2 border-red-400">
                    <p class="text-xs text-red-700 font-semibold uppercase tracking-wider mb-1">Nominal Pengeluaran</p>
                    <p class="text-4xl font-extrabold text-red-700">{{ $item->nominal_formatted }}</p>
                    <p class="text-xs text-red-600 mt-2">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        {{ $item->tanggal->format('d F Y') }}
                    </p>
                </div>

                {{-- DETAIL --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <p class="text-xs text-gray-500 mb-1">Nama Pengeluaran</p>
                        <p class="text-base font-semibold text-gray-800">{{ $item->nama_pengeluaran }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <p class="text-xs text-gray-500 mb-1">Kategori</p>
                        @if ($item->kategori)
                            <span class="inline-flex px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                                {{ $item->kategori }}
                            </span>
                        @else
                            <p class="text-sm text-gray-400 italic">-</p>
                        @endif
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 md:col-span-2">
                        <p class="text-xs text-gray-500 mb-1">Keterangan</p>
                        <p class="text-sm text-gray-700">{{ $item->keterangan ?? '-' }}</p>
                    </div>
                </div>

                {{-- INFO SISTEM --}}
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-info-circle text-yellow-500 mr-2"></i> Informasi Sistem
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex justify-between text-sm">
                            <dt class="text-gray-500">Dibuat Pada</dt>
                            <dd class="font-medium text-gray-700">{{ $item->created_at->format('d M Y H:i') }}</dd>
                        </div>
                        <div class="flex justify-between text-sm">
                            <dt class="text-gray-500">Terakhir Diperbarui</dt>
                            <dd class="font-medium text-gray-700">{{ $item->updated_at->format('d M Y H:i') }}</dd>
                        </div>
                    </div>
                </div>

                {{-- BUTTON BAWAH --}}
                <div class="pt-4 border-t border-gray-100 flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('transaksional.rekap-keuangan.index') }}"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors text-sm font-medium">
                        Kembali
                    </a>
                    <a href="{{ route('transaksional.rekap-keuangan.edit', $item->id_pengeluaran_lain) }}"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 transition-colors text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit Pengeluaran
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmHapusPengeluaran(id) {
            if (confirm('Yakin ingin menghapus pengeluaran ini?\n\nTindakan tidak bisa dibatalkan.')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
@endpush
