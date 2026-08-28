@extends('layouts.app')

@section('title', 'Detail Keluarga - Arrum Tour')
@section('page-title', 'Detail Keluarga')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.keluarga.index') }}" class="text-gray-500 hover:text-yellow-600">Transaksional</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.keluarga.index') }}" class="text-gray-500 hover:text-yellow-600">Keluarga</a>
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
                    <h5 class="text-sm font-semibold text-gray-700">Detail Keluarga</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Informasi lengkap keluarga</p>
                </div>
                <div class="flex items-center gap-2">
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
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-yellow-600">
                                {{ $keluarga->total_tagihan_setelah_diskon_formatted }}</p>
                            <p class="text-sm text-gray-500">Total Tagihan</p>
                            <p class="text-sm text-green-600">{{ $keluarga->total_dibayar_formatted }} dibayar</p>
                        </div>
                    </div>
                </div>

                <!-- Informasi Keluarga -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <p class="text-xs text-gray-500">Agent</p>
                        <p class="text-sm font-medium text-gray-700">{{ $keluarga->agent ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <p class="text-xs text-gray-500">Fee Agent</p>
                        <p class="text-sm font-medium text-gray-700">{{ $keluarga->fee_agent_formatted ?? 'Rp 0' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <p class="text-xs text-gray-500">Diskon</p>
                        <p class="text-sm font-medium text-gray-700">
                            @if ($keluarga->diskon)
                                {{ $keluarga->diskon->nama_diskon }}
                                <span class="text-yellow-600">(-{{ $keluarga->diskon->nilai_diskon_formatted }})</span>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Daftar Jamaah -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                    <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-users text-yellow-500 mr-2"></i> Daftar Jamaah
                        <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                            {{ $keluarga->jamaahs->count() }} Jamaah
                        </span>
                    </h6>

                    @if ($keluarga->jamaahs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200">
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
                                            class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            JK</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Kota Asal</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Telepon</th>
                                        <th
                                            class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Tagihan</th>
                                        <th
                                            class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($keluarga->jamaahs as $index => $jamaah)
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
                                            <td class="px-3 py-2 text-center text-gray-600">
                                                {{ $jamaah->jenis_kelamin_label }}</td>
                                            <td class="px-3 py-2 text-gray-600">{{ $jamaah->kota_asal ?? '-' }}</td>
                                            <td class="px-3 py-2 text-gray-600">{{ $jamaah->telepon ?? '-' }}</td>
                                            <td class="px-3 py-2 text-right font-medium text-gray-800">
                                                {{ $jamaah->total_tagihan_setelah_diskon_formatted }}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                {!! $jamaah->status_pembayaran_badge !!}
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
                            <p class="text-sm text-gray-400">Tidak ada jamaah dalam keluarga ini</p>
                        </div>
                    @endif
                </div>

                <!-- Catatan -->
                @if ($keluarga->catatan_tambahan)
                    <div class="mt-6 bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-align-left text-yellow-500 mr-2"></i> Catatan Tambahan
                        </h6>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $keluarga->catatan_tambahan }}</p>
                    </div>
                @endif

                <!-- Informasi Sistem -->
                <div class="mt-6 bg-gray-50 rounded-xl p-5 border border-gray-100">
                    <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-calendar-alt text-yellow-500 mr-2"></i> Informasi Sistem
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex justify-between text-sm">
                            <dt class="text-gray-500">Dibuat Pada</dt>
                            <dd class="font-medium text-gray-700">
                                {{ $keluarga->created_at ? $keluarga->created_at->format('d M Y H:i') : '-' }}</dd>
                        </div>
                        <div class="flex justify-between text-sm">
                            <dt class="text-gray-500">Terakhir Diperbarui</dt>
                            <dd class="font-medium text-gray-700">
                                {{ $keluarga->updated_at ? $keluarga->updated_at->format('d M Y H:i') : '-' }}</dd>
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
                        <i class="fas fa-edit mr-2"></i> Edit Keluarga
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
