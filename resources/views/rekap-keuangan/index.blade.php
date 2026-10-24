@extends('layouts.app')

@section('title', 'Rekap Keuangan - Arrum Tour')
@section('page-title', 'Rekap Keuangan')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500">Transaksional</span>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500 font-medium">Rekap Keuangan</span>
    </li>
@endsection

@section('content')
    @php
        $fmt = fn($n) => 'Rp ' . number_format((int) $n, 0, ',', '.');
        $isBersihPositif = $totalBersih >= 0;
    @endphp

    <div class="space-y-6">
        {{-- HEADER + FILTER --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Rekap Keuangan</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Keuntungan departure + pengeluaran manual (lain-lain)</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('transaksional.rekap-keuangan.export-pdf', request()->query()) }}"
                        class="inline-flex items-center px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium">
                        <i class="fas fa-file-pdf mr-2"></i> Export PDF
                    </a>
                    <a href="{{ route('transaksional.rekap-keuangan.create') }}"
                        class="inline-flex items-center px-3 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i> Tambah Pengeluaran
                    </a>
                </div>
            </div>

            <div class="p-6">
                <form method="GET" action="{{ route('transaksional.rekap-keuangan.index') }}"
                    class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                        <input type="date" name="dari_tanggal" value="{{ $filters['dari_tanggal'] ?? '' }}"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                        <input type="date" name="sampai_tanggal" value="{{ $filters['sampai_tanggal'] ?? '' }}"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Produk</label>
                        <select name="id_produk"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            <option value="">Semua Produk</option>
                            @foreach ($produkOptions as $p)
                                <option value="{{ $p->id_produk }}"
                                    {{ ($filters['id_produk'] ?? '') == $p->id_produk ? 'selected' : '' }}>
                                    {{ $p->nama_produk }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Status Departure</label>
                        <select name="status_selesai"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            <option value="">Semua</option>
                            <option value="0" {{ ($filters['status_selesai'] ?? '') === '0' ? 'selected' : '' }}>
                                Berjalan</option>
                            <option value="1" {{ ($filters['status_selesai'] ?? '') === '1' ? 'selected' : '' }}>
                                Selesai</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 px-3 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium">
                            <i class="fas fa-search mr-1"></i> Filter
                        </button>
                        <a href="{{ route('transaksional.rekap-keuangan.index') }}"
                            class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- KARTU GRAND TOTAL --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-emerald-50 to-green-100 rounded-xl p-5 border-2 border-emerald-400">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-emerald-700 font-semibold uppercase tracking-wider">Total Keuntungan Departure
                    </p>
                    <i class="fas fa-arrow-up text-emerald-500"></i>
                </div>
                <p class="text-3xl font-extrabold text-emerald-700">{{ $fmt($departure['total_keuntungan']) }}</p>
                <div class="mt-3 pt-3 border-t border-emerald-200 text-xs space-y-1">
                    <div class="flex justify-between"><span class="text-emerald-700">Jumlah Departure</span><span
                            class="font-bold text-emerald-800">{{ $departure['departure_count'] }}</span></div>
                    <div class="flex justify-between"><span class="text-emerald-700">Departure Profit</span><span
                            class="font-bold text-emerald-800">{{ $departure['profit_count'] }}</span></div>
                    <div class="flex justify-between"><span class="text-emerald-700">Departure Rugi</span><span
                            class="font-bold text-red-600">{{ $departure['loss_count'] }}</span></div>
                    <div class="flex justify-between"><span class="text-emerald-700">Total Jamaah</span><span
                            class="font-bold text-emerald-800">{{ $departure['jamaah_count'] }}</span></div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-50 to-rose-100 rounded-xl p-5 border-2 border-red-400">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-red-700 font-semibold uppercase tracking-wider">Total Pengeluaran Lain-lain</p>
                    <i class="fas fa-arrow-down text-red-500"></i>
                </div>
                <p class="text-3xl font-extrabold text-red-700">{{ $fmt($pengeluaran['total']) }}</p>
                <div class="mt-3 pt-3 border-t border-red-200 text-xs space-y-1">
                    <div class="flex justify-between"><span class="text-red-700">Jumlah Item</span><span
                            class="font-bold text-red-800">{{ $pengeluaran['items']->count() }}</span></div>
                    @foreach ($perKategori->take(4) as $pk)
                        <div class="flex justify-between">
                            <span class="text-red-700 truncate">{{ $pk->kategori ?? '(Tanpa kategori)' }}</span>
                            <span class="font-bold text-red-800">{{ $fmt($pk->total) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div
                class="bg-gradient-to-br {{ $isBersihPositif ? 'from-green-100 to-emerald-200 border-green-600' : 'from-red-100 to-rose-200 border-red-600' }} rounded-xl p-5 border-2">
                <div class="flex items-center justify-between mb-2">
                    <p
                        class="text-xs font-semibold uppercase tracking-wider {{ $isBersihPositif ? 'text-green-800' : 'text-red-800' }}">
                        {{ $isBersihPositif ? 'Total Bersih (Untung)' : 'Total Bersih (Rugi)' }}
                    </p>
                    <i
                        class="fas {{ $isBersihPositif ? 'fa-trophy text-green-600' : 'fa-exclamation-triangle text-red-600' }}"></i>
                </div>
                <p class="text-3xl font-extrabold {{ $isBersihPositif ? 'text-green-800' : 'text-red-800' }}">
                    {{ $totalBersih >= 0 ? '+' : '−' }} {{ $fmt(abs($totalBersih)) }}
                </p>
                <div
                    class="mt-3 pt-3 border-t {{ $isBersihPositif ? 'border-green-300' : 'border-red-300' }} text-xs space-y-1">
                    <div class="flex justify-between">
                        <span class="text-gray-700">Keuntungan Departure</span>
                        <span class="font-bold text-emerald-700">{{ $fmt($departure['total_keuntungan']) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-700">− Pengeluaran Lain-lain</span>
                        <span class="font-bold text-red-700">{{ $fmt($pengeluaran['total']) }}</span>
                    </div>
                    <div
                        class="flex justify-between pt-1 border-t {{ $isBersihPositif ? 'border-green-300' : 'border-red-300' }}">
                        <span class="text-gray-800 font-semibold">Total Bersih</span>
                        <span class="font-extrabold {{ $isBersihPositif ? 'text-green-800' : 'text-red-800' }}">
                            {{ $totalBersih >= 0 ? '+' : '−' }} {{ $fmt(abs($totalBersih)) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABEL DEPARTURE --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                    <i class="fas fa-plane text-emerald-500 mr-2"></i> Keuntungan Departure
                    <span class="ml-2 text-xs bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full">
                        {{ $departure['departure_count'] }} departure
                    </span>
                </h6>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Kode</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Keberangkatan
                                </th>
                                <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 uppercase">Jamaah</th>
                                <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 uppercase">Pendapatan
                                </th>
                                <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 uppercase">Pengeluaran
                                </th>
                                <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 uppercase">Keuntungan
                                </th>
                                <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 uppercase">Margin</th>
                                <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($departure['rows'] as $dep)
                                @php $profit = $dep->keuntungan >= 0; @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-3 py-2">
                                        <span
                                            class="inline-flex px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded text-xs font-mono font-semibold">
                                            {{ $dep->kode_keberangkatan }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2">
                                        <p class="font-medium text-gray-800">{{ $dep->nama_keberangkatan }}</p>
                                        <p class="text-xs text-gray-400">{{ $dep->produk_paket }}</p>
                                        <p class="text-[10px] text-gray-400">
                                            {{ $dep->tanggal_keberangkatan->format('d/m/Y') }} -
                                            {{ $dep->tanggal_kepulangan->format('d/m/Y') }}</p>
                                    </td>
                                    <td class="px-3 py-2 text-center font-semibold text-gray-700">
                                        {{ $dep->jamaah_terdaftar }}</td>
                                    <td class="px-3 py-2 text-right font-semibold text-emerald-600">
                                        {{ $dep->total_pendapatan_bersih_formatted }}</td>
                                    <td class="px-3 py-2 text-right font-semibold text-red-600">
                                        {{ $dep->total_pengeluaran_formatted }}</td>
                                    <td
                                        class="px-3 py-2 text-right font-bold {{ $profit ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $profit ? '+' : '−' }} {{ $dep->keuntungan_formatted }}
                                    </td>
                                    <td
                                        class="px-3 py-2 text-right font-semibold {{ $profit ? 'text-green-700' : 'text-red-700' }}">
                                        {{ $dep->margin_laba_formatted }}
                                    </td>
                                    <td class="px-3 py-2 text-center">{!! $dep->perjalanan_selesai_badge !!}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-10 text-center text-gray-500">
                                        <i class="fas fa-inbox text-3xl text-gray-300 mb-2"></i>
                                        <p>Tidak ada data departure</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($departure['rows']->count() > 0)
                            <tfoot class="bg-emerald-50 border-t-2 border-emerald-300">
                                <tr class="font-bold">
                                    <td colspan="5" class="px-3 py-3 text-right text-gray-700">TOTAL KEUNTUNGAN
                                        DEPARTURE</td>
                                    <td class="px-3 py-3 text-right text-emerald-700">
                                        {{ $fmt($departure['total_keuntungan']) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        {{-- TABEL PENGELUARAN LAIN-LAIN --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-2">
                <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                    <i class="fas fa-receipt text-red-500 mr-2"></i> Pengeluaran Lain-lain (Manual)
                    <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full">
                        {{ $pengeluaran['items']->count() }} item · {{ $fmt($pengeluaran['total']) }}
                    </span>
                </h6>
                <a href="{{ route('transaksional.rekap-keuangan.create') }}"
                    class="inline-flex items-center px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i> Tambah Pengeluaran
                </a>
            </div>

            <div class="p-6">
                {{-- filter pengeluaran --}}
                <form method="GET" action="{{ route('transaksional.rekap-keuangan.index') }}"
                    class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
                    <input type="hidden" name="dari_tanggal" value="{{ $filters['dari_tanggal'] ?? '' }}">
                    <input type="hidden" name="sampai_tanggal" value="{{ $filters['sampai_tanggal'] ?? '' }}">
                    <input type="hidden" name="id_produk" value="{{ $filters['id_produk'] ?? '' }}">
                    <input type="hidden" name="status_selesai" value="{{ $filters['status_selesai'] ?? '' }}">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Kategori</label>
                        <select name="kategori_pengeluaran"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoriOptions as $kat)
                                <option value="{{ $kat }}"
                                    {{ ($filters['kategori_pengeluaran'] ?? '') == $kat ? 'selected' : '' }}>
                                    {{ $kat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Cari</label>
                        <input type="text" name="search_pengeluaran"
                            value="{{ $filters['search_pengeluaran'] ?? '' }}" placeholder="Nama / keterangan..."
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                    </div>
                </form>

                @include('rekap-keuangan.table')
            </div>
        </div>
    </div>
@endsection
