@extends('layouts.app')

@section('title', 'Detail Keluarga / Kelompok - Arrum Tour')
@section('page-title', 'Detail Keluarga / Kelompok')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.keluarga.index') }}" class="text-gray-500 hover:text-yellow-600">Transaksional</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.keluarga.index') }}" class="text-gray-500 hover:text-yellow-600">Keluarga /
            Kelompok</a>
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
                    <h5 class="text-sm font-semibold text-gray-700">Detail Keluarga / Kelompok</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Informasi lengkap keluarga / kelompok dan riwayat pembayaran</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <a href="{{ route('transaksional.keluarga.edit', $keluarga->id_keluarga) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('transaksional.keluarga.pembayaran', $keluarga->id_keluarga) }}"
                        class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-money-bill-wave mr-2"></i> Bayar
                    </a>
                    <a href="{{ route('transaksional.keluarga.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="p-6">
                <!-- Header -->
                <div
                    class="bg-gradient-to-r from-yellow-50 to-yellow-100/50 rounded-xl p-6 mb-6 border border-yellow-200/50">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h2 class="text-2xl font-bold text-gray-800">{{ $keluarga->nama_keluarga }}</h2>
                                <span class="px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded-full">
                                    {{ $keluarga->kode_keluarga }}
                                </span>
                                {!! $keluarga->status_pembayaran_badge !!}
                            </div>
                            <p class="text-gray-500 text-sm mt-1">
                                <i class="fas fa-calendar-alt mr-1"></i> Keberangkatan:
                                @if ($keluarga->bulan_keberangkatan)
                                    {{ date('F', mktime(0, 0, 0, $keluarga->bulan_keberangkatan, 1)) }}
                                    {{ $keluarga->tahun_keberangkatan }}
                                @else
                                    -
                                @endif
                            </p>
                            <p class="text-gray-500 text-sm">
                                <i class="fas fa-box mr-1"></i> Produk: {{ $keluarga->produk_paket ?? '-' }}
                            </p>
                            <p class="text-gray-500 text-sm">
                                <i class="fas fa-user-tie mr-1"></i> Agent: {{ $keluarga->agent ?? '-' }}
                                <span class="ml-2 text-yellow-600">{{ $keluarga->fee_agent_formatted ?? 'Rp 0' }}</span>
                            </p>
                            @if ($keluarga->catatan_tambahan)
                                <p class="text-gray-500 text-sm mt-1">
                                    <i class="fas fa-sticky-note mr-1"></i> Catatan: {{ $keluarga->catatan_tambahan }}
                                </p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-yellow-600">
                                {{ $keluarga->total_tagihan_setelah_diskon_formatted }}</p>
                            <p class="text-sm text-gray-500">Total Tagihan</p>
                            <p class="text-sm text-green-600">{{ $keluarga->total_dibayar_formatted }} dibayar</p>
                            <p class="text-sm text-red-500">{{ $keluarga->sisa_tagihan_formatted }} sisa</p>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Keuangan -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-green-50 rounded-xl p-4 border border-green-200 text-center">
                        <p class="text-xs text-gray-500">Total Tagihan</p>
                        <p class="text-xl font-bold text-gray-800">{{ $keluarga->total_tagihan_setelah_diskon_formatted }}
                        </p>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 text-center">
                        <p class="text-xs text-gray-500">Total Dibayar</p>
                        <p class="text-xl font-bold text-blue-600">{{ $keluarga->total_dibayar_formatted }}</p>
                    </div>
                    <div class="bg-red-50 rounded-xl p-4 border border-red-200 text-center">
                        <p class="text-xs text-gray-500">Sisa Tagihan</p>
                        <p class="text-xl font-bold text-red-600">{{ $keluarga->sisa_tagihan_formatted }}</p>
                    </div>
                    <div class="bg-purple-50 rounded-xl p-4 border border-purple-200 text-center">
                        <p class="text-xs text-gray-500">Total Transaksi</p>
                        <p class="text-xl font-bold text-purple-600">{{ $transaksis->count() }}</p>
                    </div>
                </div>

                <!-- Daftar Jamaah dengan Rekap Pembayaran -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-users text-yellow-500 mr-2"></i> Daftar Jamaah & Rekap Pembayaran
                        <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                            {{ $keluarga->jamaahs->count() }} Jamaah
                        </span>
                    </h6>

                    @if ($keluarga->jamaahs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200 bg-gray-100">
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            #</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Nama Jamaah</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Hubungan</th>
                                        <th
                                            class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Tagihan</th>
                                        <th
                                            class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Dibayar</th>
                                        <th
                                            class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Sisa</th>
                                        <th
                                            class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Transaksi</th>
                                        <th
                                            class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($keluarga->jamaahs as $index => $jamaah)
                                        @php
                                            $rekap = $rekapJamaah[$jamaah->id_jamaah] ?? [];
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-3 py-2 text-gray-600">{{ $loop->iteration }}</td>
                                            <td class="px-3 py-2">
                                                <span class="font-medium text-gray-700">{{ $jamaah->nama_lengkap }}</span>
                                                @if ($jamaah->is_kepala_keluarga)
                                                    <span
                                                        class="ml-1 px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded text-xs">Kepala</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-gray-600">{{ $jamaah->hubungan_keluarga ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-right font-medium text-gray-800">
                                                {{ $jamaah->total_tagihan_setelah_diskon_formatted }}
                                            </td>
                                            <td class="px-3 py-2 text-right text-green-600">
                                                {{ $jamaah->total_dibayar_formatted }}
                                            </td>
                                            <td class="px-3 py-2 text-right text-red-500">
                                                {{ $jamaah->sisa_tagihan_formatted }}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                {!! $jamaah->status_pembayaran_badge !!}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                                                    {{ $rekap['transaksi_count'] ?? 0 }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                <a href="{{ route('transaksional.jamaah.show', $jamaah->id_jamaah) }}"
                                                    class="text-blue-500 hover:text-blue-700 text-sm" title="Detail Jamaah">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-400">Tidak ada jamaah dalam keluarga / kelompok ini</p>
                        </div>
                    @endif
                </div>

                <!-- Riwayat Pembayaran Keluarga / Kelompok -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center justify-between">
                        <span>
                            <i class="fas fa-history text-yellow-500 mr-2"></i> Riwayat Pembayaran Keluarga / Kelompok
                        </span>
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                            {{ $transaksis->count() }} Transaksi
                        </span>
                    </h6>

                    @if ($transaksis->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200 bg-gray-100">
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            #</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Tanggal</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Jamaah</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Bank</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Jenis</th>
                                        <th
                                            class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Jumlah</th>
                                        <th
                                            class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Bukti</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Keterangan</th>
                                        <th
                                            class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Dibuat Oleh</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($transaksis as $index => $transaksi)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-3 py-2 text-gray-400 text-xs">{{ $loop->iteration }}</td>
                                            <td class="px-3 py-2 text-gray-600 whitespace-nowrap">
                                                {{ $transaksi->tanggal_transaksi ? date('d M Y', strtotime($transaksi->tanggal_transaksi)) : '-' }}
                                                <br>
                                                <span
                                                    class="text-xs text-gray-400">{{ $transaksi->created_at ? date('H:i', strtotime($transaksi->created_at)) : '' }}</span>
                                            </td>
                                            <td class="px-3 py-2">
                                                <span class="text-sm font-medium text-gray-700">
                                                    {{ $transaksi->jamaah->nama_lengkap ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2">
                                                <span class="text-xs font-medium text-gray-700">
                                                    {{ $transaksi->metodePembayaran->kode_bank ?? '-' }}
                                                </span>
                                                <p class="text-xs text-gray-400">
                                                    {{ $transaksi->metodePembayaran->nama_bank ?? '-' }}
                                                </p>
                                            </td>
                                            <td class="px-3 py-2">
                                                <span
                                                    class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $transaksi->jenisTransaksi->kode == 'DP'
                                        ? 'bg-yellow-100 text-yellow-700'
                                        : ($transaksi->jenisTransaksi->kode == 'LUNAS'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-blue-100 text-blue-700') }}">
                                                    {{ $transaksi->jenisTransaksi->nama ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-right font-medium text-gray-800">
                                                {{ $transaksi->jumlah_bayar_formatted }}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                @if ($transaksi->bukti_pembayaran)
                                                    <a href="{{ Storage::url($transaksi->bukti_pembayaran) }}"
                                                        target="_blank"
                                                        class="text-blue-500 hover:text-blue-700 text-xs inline-flex items-center gap-1">
                                                        <i class="fas fa-eye"></i> Lihat
                                                    </a>
                                                @else
                                                    <span class="text-xs text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-gray-500 text-sm max-w-xs truncate">
                                                {{ $transaksi->keterangan ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-center text-xs text-gray-500">
                                                {{ $transaksi->created_by ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-3 block"></i>
                            <p class="text-sm text-gray-400">Belum ada riwayat pembayaran untuk keluarga / kelompok ini</p>
                            <a href="{{ route('transaksional.keluarga.pembayaran', $keluarga->id_keluarga) }}"
                                class="mt-3 inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm">
                                <i class="fas fa-plus mr-2"></i> Tambah Pembayaran
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Informasi Sistem -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                    <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-calendar-alt text-yellow-500 mr-2"></i> Informasi Sistem
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex justify-between text-sm">
                            <dt class="text-gray-500">Dibuat Pada</dt>
                            <dd class="font-medium text-gray-700">
                                {{ $keluarga->created_at ? $keluarga->created_at->format('d M Y H:i') : '-' }}
                            </dd>
                        </div>
                        <div class="flex justify-between text-sm">
                            <dt class="text-gray-500">Terakhir Diperbarui</dt>
                            <dd class="font-medium text-gray-700">
                                {{ $keluarga->updated_at ? $keluarga->updated_at->format('d M Y H:i') : '-' }}
                            </dd>
                        </div>
                    </div>
                </div>

                <!-- Aksi Bawah -->
                <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-end gap-3">
                    <button type="button" onclick="window.print()"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        <i class="fas fa-print mr-2"></i> Cetak
                    </button>
                    <a href="{{ route('transaksional.keluarga.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <a href="{{ route('transaksional.keluarga.edit', $keluarga->id_keluarga) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit Keluarga / Kelompok
                    </a>
                    <a href="{{ route('transaksional.keluarga.pembayaran', $keluarga->id_keluarga) }}"
                        class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-money-bill-wave mr-2"></i> Bayar
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
