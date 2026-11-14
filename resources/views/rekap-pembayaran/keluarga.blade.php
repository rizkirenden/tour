@extends('layouts.app')

@section('title', 'Rekap Keluarga - Arrum Tour')
@section('page-title', 'Rekap Pembayaran Keluarga')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.rekap-pembayaran.index') }}" class="text-gray-500 hover:text-yellow-600">Rekap
            Pembayaran</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500 font-medium">Keluarga</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto space-y-6">

        {{-- INFO BANNER --}}
        <div class="bg-purple-50 border border-purple-200 rounded-xl p-3 flex items-start gap-2">
            <i class="fas fa-users text-purple-500 mt-0.5"></i>
            <div class="text-xs text-purple-700">
                <strong>Rekap ini hanya untuk Jamaah dari Keluarga</strong> (terdaftar sebagai anggota keluarga).
                Untuk jamaah mandiri, lihat di menu <a href="{{ route('transaksional.rekap-pembayaran.jamaah') }}"
                    class="font-semibold underline">Rekap Jamaah</a>.
            </div>
        </div>

        {{-- STAT CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <p class="text-xs text-gray-500">Total Masuk</p>
                <p class="text-xl font-bold text-green-600">Rp {{ number_format($statistik['total_masuk'], 0, ',', '.') }}
                </p>
                <p class="text-xs text-gray-400 mt-1">{{ $statistik['count_masuk'] }} transaksi</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <p class="text-xs text-gray-500">Total Refund</p>
                <p class="text-xl font-bold text-amber-600">Rp {{ number_format($statistik['total_keluar'], 0, ',', '.') }}
                </p>
                <p class="text-xs text-gray-400 mt-1">{{ $statistik['count_keluar'] }} transaksi</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <p class="text-xs text-gray-500">Net Diterima</p>
                <p class="text-xl font-bold text-blue-600">Rp {{ number_format($statistik['net'], 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400 mt-1">Masuk − Refund</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <p class="text-xs text-gray-500">Total Transaksi</p>
                <p class="text-xl font-bold text-gray-800">{{ $statistik['count_masuk'] + $statistik['count_keluar'] }}</p>
                <p class="text-xs text-gray-400 mt-1">Masuk + Keluar</p>
            </div>
        </div>

        {{-- FILTER BAR --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <form method="GET" action="{{ route('transaksional.rekap-pembayaran.keluarga') }}" class="space-y-3">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Cari</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm"
                            placeholder="Nama / Kode Keluarga">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Mutasi</label>
                        <select name="jenis_mutasi" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                            <option value="">Semua</option>
                            <option value="masuk" {{ request('jenis_mutasi') == 'masuk' ? 'selected' : '' }}>Masuk
                            </option>
                            <option value="keluar" {{ request('jenis_mutasi') == 'keluar' ? 'selected' : '' }}>Keluar
                                (Refund)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Produk</label>
                        <select name="produk_paket" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                            <option value="">Semua</option>
                            @foreach ($filterOptions['produk_pakets'] as $p)
                                <option value="{{ $p }}" {{ request('produk_paket') == $p ? 'selected' : '' }}>
                                    {{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Bayar</label>
                        <select name="id_jenis_pembayaran"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                            <option value="">Semua</option>
                            @foreach ($filterOptions['jenis_pembayarans'] as $jp)
                                <option value="{{ $jp->id_jenis_pembayaran }}"
                                    {{ request('id_jenis_pembayaran') == $jp->id_jenis_pembayaran ? 'selected' : '' }}>
                                    {{ $jp->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 text-sm font-medium">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        <a href="{{ route('transaksional.rekap-pembayaran.keluarga') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-medium">Reset</a>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Dari</label>
                        <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Sampai</label>
                        <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    </div>
                    <div class="md:col-span-3 flex items-end gap-2 justify-end flex-wrap">
                        <a href="{{ route('transaksional.rekap-pembayaran.export-csv', array_merge(request()->all(), ['tipe' => 'keluarga'])) }}"
                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 text-sm font-medium">
                            <i class="fas fa-file-csv mr-1"></i> CSV
                        </a>
                        <a href="{{ route('transaksional.rekap-pembayaran.export-pdf', array_merge(request()->all(), ['tipe' => 'keluarga'])) }}"
                            target="_blank"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm font-medium">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                    <i class="fas fa-users text-yellow-500 mr-2"></i> Riwayat Pembayaran Jamaah Keluarga
                    <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                        {{ $data->total() }} Transaksi
                    </span>
                </h6>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Keluarga</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Jamaah</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Bank</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Jenis</th>
                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Mutasi</th>
                            <th class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Jumlah</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Keterangan</th>
                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Bukti</th>
                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($data as $idx => $t)
                            @php $isRefund = ($t->jenis_mutasi ?? 'masuk') === 'keluar'; @endphp
                            <tr class="hover:bg-gray-50 transition {{ $isRefund ? 'bg-amber-50/30' : '' }}">
                                <td class="px-3 py-2 text-gray-500 text-xs">{{ $data->firstItem() + $idx }}</td>
                                <td class="px-3 py-2 text-gray-600 text-xs whitespace-nowrap">
                                    {{ $t->tanggal_transaksi ? $t->tanggal_transaksi->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-3 py-2">
                                    <div class="font-medium text-gray-800 text-xs">
                                        {{ $t->jamaah->keluarga->nama_keluarga ?? '-' }}</div>
                                    <div class="text-xs text-gray-400 font-mono">
                                        {{ $t->jamaah->keluarga->kode_keluarga ?? '-' }}</div>
                                </td>
                                <td class="px-3 py-2">
                                    <div class="font-medium text-gray-800">{{ $t->jamaah->nama_lengkap ?? '-' }}</div>
                                    <div class="text-xs text-gray-400">{{ $t->jamaah->nik ?? '-' }}</div>
                                </td>
                                <td class="px-3 py-2 text-xs">
                                    <div class="font-medium text-gray-700">
                                        {{ $t->metodePembayaran->kode_bank ?? ($t->metodePembayaran->e_wallet_type ?? '-') }}
                                    </div>
                                    <div class="text-gray-400">
                                        {{ $t->metodePembayaran->nama_bank ?? ($t->metodePembayaran->jenis_pembayaran ?? '-') }}
                                    </div>
                                </td>
                                <td class="px-3 py-2">
                                    @if ($isRefund)
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-amber-200 text-amber-800">
                                            <i class="fas fa-undo-alt mr-1"></i>{{ $t->jenis_nama ?? 'Refund' }}
                                        </span>
                                    @elseif (($t->jenis_kode ?? '') === 'LUNAS')
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">{{ $t->jenis_nama ?? '-' }}</span>
                                    @elseif (($t->jenis_kode ?? '') === 'DP')
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">{{ $t->jenis_nama ?? '-' }}</span>
                                    @else
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ $t->jenis_nama ?? '-' }}</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-center">
                                    @if ($isRefund)
                                        <span
                                            class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-200 text-amber-800">
                                            <i class="fas fa-arrow-down text-[10px] mr-1"></i> Refund
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                            <i class="fas fa-arrow-up text-[10px] mr-1"></i> Masuk
                                        </span>
                                    @endif
                                </td>
                                <td
                                    class="px-3 py-2 text-right font-bold {{ $isRefund ? 'text-amber-600' : 'text-green-700' }}">
                                    {{ $isRefund ? '−' : '' }}Rp {{ number_format($t->jumlah_bayar, 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-2 text-xs text-gray-500 max-w-xs truncate">{{ $t->keterangan ?? '-' }}
                                </td>
                                <td class="px-3 py-2 text-center">
                                    @if ($t->bukti_pembayaran)
                                        <a href="{{ asset('storage/' . $t->bukti_pembayaran) }}" target="_blank"
                                            class="text-blue-500 hover:text-blue-700 text-xs">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap">
                                    @if ($t->jamaah && $t->jamaah->keluarga)
                                        <a href="{{ route('transaksional.keluarga.pembayaran', $t->jamaah->keluarga->id_keluarga) }}"
                                            class="text-blue-500 hover:text-blue-700 text-xs" title="Halaman Keluarga">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-3 py-8 text-center text-gray-400">
                                    <i class="fas fa-inbox text-3xl mb-2 block"></i>
                                    Tidak ada transaksi jamaah keluarga
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($data->hasPages())
                <div class="px-4 py-3 border-t border-gray-100">
                    {{ $data->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
