@extends('layouts.app')

@section('title', 'Pembayaran Jamaah - Arrum Tour')
@section('page-title', 'Pembayaran Jamaah')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.jamaah.index') }}" class="text-gray-500 hover:text-yellow-600">Transaksional</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.jamaah.index') }}" class="text-gray-500 hover:text-yellow-600">Jamaah</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500 font-medium">Pembayaran</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Form Pembayaran</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Lakukan pembayaran untuk jamaah</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    @if ($transaksis->count() > 0)
                        <a href="{{ route('transaksional.jamaah.cetak-pdf', $jamaah->id_jamaah) }}" target="_blank"
                            class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium">
                            <i class="fas fa-file-pdf mr-2"></i> Cetak PDF
                        </a>
                    @endif
                    <a href="{{ route('transaksional.jamaah.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="p-6">
                <!-- Informasi Jamaah -->
                <div
                    class="bg-gradient-to-r from-yellow-50 to-yellow-100/50 rounded-xl p-6 mb-6 border border-yellow-200/50">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h2 class="text-2xl font-bold text-gray-800">{{ $jamaah->nama_lengkap }}</h2>
                                <span class="px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded-full">
                                    {{ $jamaah->id_keberangkatan }}
                                </span>
                                {!! $jamaah->status_pembayaran_badge !!}
                                {!! $jamaah->kelebihan_bayar_badge !!}
                            </div>
                            <p class="text-gray-500 text-sm mt-1">
                                <i class="fas fa-box mr-1"></i> Produk: {{ $jamaah->produk_paket }}
                            </p>
                            <p class="text-gray-500 text-sm">
                                <i class="fas fa-map-marker-alt mr-1"></i> Kota Asal: {{ $jamaah->kota_asal ?? '-' }}
                            </p>
                            <p class="text-gray-500 text-sm">
                                <i class="fab fa-whatsapp mr-1 text-green-500"></i> WhatsApp:
                                @if ($jamaah->wa)
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $jamaah->wa)) }}"
                                        target="_blank"
                                        class="text-green-600 hover:text-green-700 font-medium">{{ $jamaah->wa }}</a>
                                @else
                                    -
                                @endif
                            </p>
                            <p class="text-gray-500 text-sm">
                                <i class="fas fa-heart mr-1"></i> Status Kawin:
                                <span class="font-medium text-gray-700">{{ $jamaah->status_kawin ?? '-' }}</span>
                            </p>
                            <p class="text-gray-500 text-sm">
                                <i class="fas fa-passport mr-1"></i> Paspor: {{ $jamaah->nomor_paspor ?? '-' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-yellow-600">
                                {{ $jamaah->total_tagihan_setelah_diskon_formatted }}</p>
                            <p class="text-sm text-gray-500">Total Tagihan</p>
                        </div>
                    </div>
                </div>

                <!-- Detail Keuangan - 3 Kolom -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-green-50 rounded-xl p-4 border border-green-200 text-center">
                        <p class="text-xs text-gray-500">Total Dibayar</p>
                        <p class="text-xl font-bold text-green-600">{{ $jamaah->total_dibayar_formatted }}</p>
                        @if ($jamaah->total_refund > 0)
                            <p class="text-[10px] text-gray-400 mt-1">
                                Net: {{ $jamaah->net_dibayar_formatted }}
                            </p>
                        @endif
                    </div>
                    <div class="bg-red-50 rounded-xl p-4 border border-red-200 text-center">
                        <p class="text-xs text-gray-500">Sisa Tagihan</p>
                        <p class="text-xl font-bold text-red-600">{{ $jamaah->sisa_tagihan_formatted }}</p>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 text-center">
                        <p class="text-xs text-gray-500">Status</p>
                        <p class="text-xl font-bold text-blue-600">{!! $jamaah->status_pembayaran_badge !!}</p>
                    </div>
                </div>

                {{-- ✅ ALERT KELEBIHAN BAYAR + TOMBOL REFUND --}}
                @if ($jamaah->sisa_kelebihan_bayar > 0)
                    <div class="bg-amber-50 border-2 border-amber-400 rounded-xl p-4 mb-6">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="flex-1 min-w-[250px]">
                                <p class="text-sm font-bold text-amber-800 flex items-center gap-2">
                                    <i class="fas fa-hand-holding-usd text-lg"></i>
                                    ADA KELEBIHAN BAYAR
                                </p>
                                <p class="text-xs text-amber-700 mt-1">
                                    Jamaah sudah membayar melebihi tagihan (kemungkinan pindah produk / harga lebih murah).
                                    Silakan proses pengembalian dana (refund).
                                </p>
                                <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-2 text-xs">
                                    <div class="bg-white rounded p-2 border border-amber-200">
                                        <span class="text-gray-500">Total Dibayar:</span>
                                        <span class="font-bold text-gray-700">{{ $jamaah->total_dibayar_formatted }}</span>
                                    </div>
                                    <div class="bg-white rounded p-2 border border-amber-200">
                                        <span class="text-gray-500">Total Tagihan:</span>
                                        <span
                                            class="font-bold text-gray-700">{{ $jamaah->total_tagihan_setelah_diskon_formatted }}</span>
                                    </div>
                                    <div class="bg-white rounded p-2 border border-amber-300">
                                        <span class="text-gray-500">Sisa Kelebihan:</span>
                                        <span
                                            class="font-bold text-amber-700">{{ $jamaah->sisa_kelebihan_bayar_formatted }}</span>
                                    </div>
                                </div>
                                @if ($jamaah->total_refund > 0)
                                    <p class="text-xs text-gray-500 mt-1">
                                        Sudah di-refund: <strong>{{ $jamaah->total_refund_formatted }}</strong>
                                    </p>
                                @endif
                            </div>
                            <button type="button" onclick="openRefundModal()"
                                class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition text-sm font-medium whitespace-nowrap">
                                <i class="fas fa-undo-alt mr-2"></i> Proses Refund
                            </button>
                        </div>
                    </div>

                    {{-- MODAL REFUND --}}
                    <div id="refundModal"
                        class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
                        <div class="bg-white rounded-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
                            <div
                                class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                                <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-hand-holding-usd text-amber-500 mr-2"></i> Proses Refund
                                </h5>
                                <button type="button" onclick="closeRefundModal()"
                                    class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                            <form action="{{ route('transaksional.jamaah.proses-refund', $jamaah->id_jamaah) }}"
                                method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                                @csrf

                                <div class="bg-amber-50 rounded-lg p-3 border border-amber-200">
                                    <p class="text-xs text-amber-700">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Sisa kelebihan yang bisa di-refund:
                                        <strong
                                            class="text-amber-800">{{ $jamaah->sisa_kelebihan_bayar_formatted }}</strong>
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Metode Pengembalian <span class="text-red-500">*</span>
                                    </label>
                                    <select name="id_metode_pembayaran" required
                                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-sm">
                                        <option value="">-- Pilih Metode --</option>
                                        @foreach ($metodePembayarans as $m)
                                            <option value="{{ $m->id_metode }}">
                                                {{ $m->kode_bank ?? ($m->e_wallet_type ?? $m->jenis_pembayaran) }}
                                                - {{ $m->nama_bank ?? $m->jenis_pembayaran }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Tanggal Refund <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="tanggal_transaksi" value="{{ date('Y-m-d') }}" required
                                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Jumlah Refund <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                        <input type="text" name="jumlah_refund" id="jumlah_refund"
                                            value="{{ number_format($jamaah->sisa_kelebihan_bayar, 0, ',', '.') }}"
                                            required oninput="formatRupiahRefund(this)"
                                            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-lg">
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">
                                        Maksimal: {{ $jamaah->sisa_kelebihan_bayar_formatted }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Bukti Refund <span class="text-xs text-gray-400">(opsional)</span>
                                    </label>
                                    <input type="file" name="bukti_pembayaran" accept=".pdf,.jpg,.jpeg,.png"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan</label>
                                    <textarea name="keterangan" rows="2"
                                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-sm"
                                        placeholder="Contoh: Pengembalian kelebihan bayar karena pindah produk"></textarea>
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                                    <button type="button" onclick="closeRefundModal()"
                                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition">
                                        <i class="fas fa-check-circle mr-2"></i> Proses Refund
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Detail Tagihan -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-file-invoice text-yellow-500 mr-2"></i> Detail Tagihan
                        <span class="ml-2 text-xs text-gray-400">(per orang)</span>
                    </h6>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <p class="text-xs text-gray-500">Harga Produk</p>
                            <p class="text-sm font-bold text-gray-800">
                                {{ $jamaah->harga_produk_formatted }}
                            </p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-purple-200">
                            <p class="text-xs text-gray-500">Pembuatan Passport</p>
                            <p class="text-sm font-bold text-purple-600">
                                {{ $jamaah->total_pembuatan_passport_formatted }}
                            </p>
                            <p class="text-xs text-gray-400">Pembayaran + Pendapatan Perusahaan</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-gray-300">
                            <p class="text-xs text-gray-500">Harga Asli (Kotor)</p>
                            <p class="text-sm font-bold text-gray-800">
                                {{ $jamaah->total_tagihan_sebelum_diskon_formatted }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <p class="text-xs text-gray-500">Diskon</p>
                            <p class="text-sm font-bold text-red-600">{{ $jamaah->total_diskon_formatted }}</p>
                            @if ($jamaah->keterangan_diskon)
                                <p class="text-xs text-gray-400">{{ $jamaah->keterangan_diskon }}</p>
                            @endif
                        </div>
                        <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                            <p class="text-xs text-gray-500">Total Setelah Diskon</p>
                            <p class="text-sm font-bold text-green-700">
                                {{ $jamaah->total_tagihan_setelah_diskon_formatted }}
                            </p>
                        </div>
                    </div>

                    <!-- Agent & Pembuatan Passport -->
                    <div class="border-t border-gray-200 pt-4 mt-2">
                        <p class="text-xs text-gray-500 mb-3 bg-yellow-50 rounded-lg p-2 border border-yellow-200">
                            <i class="fas fa-info-circle text-yellow-600 mr-1"></i>
                            <span class="text-green-600 font-medium">✓</span>
                            Pembuatan Passport <span class="font-medium">SUDAH</span> termasuk dalam total tagihan.
                            Fee Agent <span class="text-red-500 font-medium">TIDAK</span> termasuk.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                                <p class="text-xs font-semibold text-blue-600 mb-2">
                                    <i class="fas fa-user-tie mr-1"></i> Agent
                                </p>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Nama Agent</span>
                                    <span class="font-medium text-gray-700">{{ $jamaah->agent_name_formatted }}</span>
                                </div>
                                <div class="flex justify-between text-sm mt-1">
                                    <span class="text-gray-500">Fee Agent</span>
                                    <span class="font-medium text-blue-600">{{ $jamaah->fee_agent_formatted }}</span>
                                </div>
                            </div>

                            <div class="bg-purple-50 rounded-lg p-3 border border-purple-200">
                                <p class="text-xs font-semibold text-purple-600 mb-2">
                                    <i class="fas fa-passport mr-1"></i> Pembuatan Passport
                                </p>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Ada Pembuatan Passport?</span>
                                    <span>{!! $jamaah->is_pembuatan_passport_badge !!}</span>
                                </div>
                                <div class="flex justify-between text-sm mt-1">
                                    <span class="text-gray-500">Pembayaran Passport</span>
                                    <span
                                        class="font-medium text-green-600">{{ $jamaah->pendampingan_fee_formatted }}</span>
                                </div>
                                @if ($jamaah->is_pembuatan_passport)
                                    <div class="flex justify-between text-sm mt-1">
                                        <span class="text-gray-500">Pendapatan Perusahaan</span>
                                        <span
                                            class="font-medium text-purple-600">{{ $jamaah->pendampingan_fee_petugas_formatted }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between text-sm mt-2 pt-2 border-t border-purple-200">
                                    <span class="text-gray-600 font-medium">Total Passport</span>
                                    <span
                                        class="font-bold text-purple-700">{{ $jamaah->total_pembuatan_passport_formatted }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer total --}}
                    <div
                        class="mt-4 text-sm text-gray-500 border-t border-gray-200 pt-3 flex flex-wrap items-center justify-between gap-2">
                        <span>
                            <span class="font-medium">Harga Produk:</span>
                            <span class="font-bold text-gray-700">{{ $jamaah->harga_produk_formatted }}</span>
                        </span>
                        <span>
                            <span class="font-medium">Passport:</span>
                            <span
                                class="font-bold text-purple-600">{{ $jamaah->total_pembuatan_passport_formatted }}</span>
                        </span>
                        <span>
                            <span class="font-medium">Kotor:</span>
                            <span
                                class="font-bold text-gray-800">{{ $jamaah->total_tagihan_sebelum_diskon_formatted }}</span>
                        </span>
                        <span>
                            <span class="font-medium">Diskon:</span>
                            <span class="font-bold text-red-600">{{ $jamaah->total_diskon_formatted }}</span>
                        </span>
                        <span>
                            <span class="font-medium">Total:</span>
                            <span
                                class="font-bold text-yellow-600">{{ $jamaah->total_tagihan_setelah_diskon_formatted }}</span>
                        </span>
                    </div>
                </div>

                {{-- UPGRADE HOTEL --}}
                @if (
                    $jamaah->upgradeHotels->count() > 0 ||
                        ($jamaah->id_keluarga && $jamaah->keluarga && $jamaah->keluarga->upgradeHotels->count() > 0))
                    <div class="bg-orange-50 rounded-xl p-5 border border-orange-200 mb-6">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-hotel text-orange-500 mr-2"></i> Upgrade Hotel & Kamar
                            <span class="ml-2 text-xs text-gray-400">(sudah termasuk total tagihan)</span>
                        </h6>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                            <div class="bg-white rounded-lg p-3 border border-orange-200 text-center">
                                <p class="text-xs text-gray-500">Upgrade Personal</p>
                                <p class="text-sm font-bold text-orange-600">{{ $jamaah->total_upgrade_hotel_formatted }}
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-purple-200 text-center">
                                <p class="text-xs text-gray-500">Share Keluarga</p>
                                <p class="text-sm font-bold text-purple-600">
                                    Rp
                                    {{ number_format(max(0, $jamaah->total_upgrade_hotel_gabungan - $jamaah->total_upgrade_hotel), 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-blue-200 text-center">
                                <p class="text-xs text-gray-500">Total Gabungan</p>
                                <p class="text-sm font-bold text-blue-600">
                                    {{ $jamaah->total_upgrade_hotel_gabungan_formatted }}</p>
                            </div>
                        </div>

                        @if ($jamaah->upgradeHotels->count() > 0)
                            <div class="overflow-x-auto mb-3">
                                <table class="w-full text-sm bg-white rounded-lg overflow-hidden">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">
                                                Hotel</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">
                                                Tipe Kamar</th>
                                            <th class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase">
                                                Jumlah Pax</th>
                                            <th class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase">
                                                Harga/Pax</th>
                                            <th class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase">
                                                Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($jamaah->upgradeHotels as $upgrade)
                                            <tr>
                                                <td class="px-3 py-2">
                                                    <span
                                                        class="font-medium text-gray-700">{{ $upgrade->hotel->nama_hotel ?? '-' }}</span>
                                                    <p class="text-xs text-gray-400">{{ $upgrade->hotel->kota ?? '' }}</p>
                                                </td>
                                                <td class="px-3 py-2 text-gray-600">{{ $upgrade->tipe_kamar ?? '-' }}</td>
                                                <td class="px-3 py-2 text-right text-gray-600">{{ $upgrade->jumlah_pax }}
                                                    pax</td>
                                                <td class="px-3 py-2 text-right text-gray-600">
                                                    {{ $upgrade->harga_per_pax_formatted }}</td>
                                                <td class="px-3 py-2 text-right font-bold text-orange-600">
                                                    {{ $upgrade->total_harga_formatted }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if ($jamaah->id_keluarga && $jamaah->keluarga && $jamaah->keluarga->upgradeHotels->count() > 0)
                            <div class="bg-purple-100 rounded-lg p-3 border border-purple-300">
                                <p class="text-xs text-purple-700">
                                    <i class="fas fa-users mr-1"></i>
                                    Termasuk share upgrade hotel keluarga:
                                    <span
                                        class="font-bold">{{ $jamaah->keluarga->upgrade_hotel_per_jamaah_formatted }}</span>
                                    (total keluarga {{ $jamaah->keluarga->total_upgrade_hotel_formatted }} ÷
                                    {{ $jamaah->keluarga->jamaahs->count() }} jamaah)
                                </p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Riwayat Transaksi -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-history text-yellow-500 mr-2"></i> Riwayat Pembayaran
                            <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                                {{ $transaksis->count() }} Transaksi
                            </span>
                        </h6>
                        @if ($transaksis->count() > 0)
                            <a href="{{ route('transaksional.jamaah.cetak-pdf', $jamaah->id_jamaah) }}" target="_blank"
                                class="inline-flex items-center px-3 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-xs font-medium">
                                <i class="fas fa-file-pdf mr-1.5"></i> Cetak PDF
                            </a>
                        @endif
                    </div>

                    @if ($transaksis->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            #</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Tanggal</th>
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
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @php
                                        $sortedTransaksis = $transaksis->sortBy('created_at');
                                    @endphp
                                    @foreach ($sortedTransaksis as $index => $transaksi)
                                        @php
                                            $isRefund = ($transaksi->jenis_mutasi ?? 'masuk') === 'keluar';
                                            $rowClass = $isRefund ? 'bg-amber-50/40' : '';
                                            $jenisNama = $transaksi->jenis_nama;
                                            $kodeJenis = $transaksi->jenis_kode;
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition-colors {{ $rowClass }}">
                                            <td class="px-3 py-2 text-gray-400 text-xs">{{ $loop->iteration }}</td>
                                            <td class="px-3 py-2 text-gray-600">
                                                {{ $transaksi->tanggal_transaksi_formatted }}
                                            </td>
                                            <td class="px-3 py-2">
                                                <span class="text-xs font-medium text-gray-700">
                                                    {{ $transaksi->metodePembayaran->kode_bank ?? ($transaksi->metodePembayaran->e_wallet_type ?? $transaksi->metodePembayaran->jenis_pembayaran) }}
                                                </span>
                                                <p class="text-xs text-gray-400">
                                                    {{ $transaksi->metodePembayaran->nama_bank ?? $transaksi->metodePembayaran->jenis_pembayaran }}
                                                </p>
                                            </td>
                                            <td class="px-3 py-2">
                                                @if ($isRefund)
                                                    <span
                                                        class="px-2 py-1 rounded-full text-xs font-bold bg-amber-200 text-amber-800">
                                                        <i class="fas fa-undo-alt mr-1"></i>{{ $jenisNama }}
                                                    </span>
                                                @elseif ($kodeJenis === 'LUNAS')
                                                    <span
                                                        class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                        {{ $jenisNama }}
                                                    </span>
                                                @elseif ($kodeJenis === 'DP')
                                                    <span
                                                        class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                                        {{ $jenisNama }}
                                                    </span>
                                                @elseif ($kodeJenis === 'ANGSURAN')
                                                    <span
                                                        class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                                        {{ $jenisNama }}
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                                        {{ $jenisNama }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td
                                                class="px-3 py-2 text-right font-medium {{ $isRefund ? 'text-amber-600' : 'text-gray-800' }}">
                                                @if ($isRefund)
                                                    <span class="inline-flex items-center gap-1">
                                                        <i class="fas fa-arrow-down text-[10px]"></i>
                                                        −{{ $transaksi->jumlah_bayar_formatted }}
                                                    </span>
                                                @else
                                                    {{ $transaksi->jumlah_bayar_formatted }}
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                @if ($transaksi->bukti_pembayaran_url)
                                                    <a href="{{ $transaksi->bukti_pembayaran_url }}" target="_blank"
                                                        class="text-blue-500 hover:text-blue-700 text-xs">
                                                        <i class="fas fa-eye"></i> Lihat
                                                    </a>
                                                @else
                                                    <span class="text-xs text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-gray-500 text-sm max-w-xs truncate">
                                                {{ $transaksi->keterangan ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                <button onclick="confirmDeleteTransaksi({{ $transaksi->id_transaksi }})"
                                                    class="text-red-500 hover:text-red-700 transition-colors duration-200"
                                                    title="{{ $isRefund ? 'Hapus Refund' : 'Hapus Transaksi' }}">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                                <form id="delete-transaksi-form-{{ $transaksi->id_transaksi }}"
                                                    action="{{ route('transaksional.jamaah.hapus-transaksi', $transaksi->id_transaksi) }}"
                                                    method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-100 border-t-2 border-gray-300">
                                    <tr>
                                        <td colspan="4" class="px-3 py-2 text-right font-bold text-gray-700">
                                            Total Masuk:
                                        </td>
                                        <td class="px-3 py-2 text-right font-bold text-green-700">
                                            {{ $jamaah->total_dibayar_formatted }}
                                        </td>
                                        <td colspan="3"></td>
                                    </tr>
                                    @if ($jamaah->total_refund > 0)
                                        <tr>
                                            <td colspan="4" class="px-3 py-2 text-right font-bold text-gray-700">
                                                Total Refund:
                                            </td>
                                            <td class="px-3 py-2 text-right font-bold text-amber-700">
                                                <i class="fas fa-arrow-down text-[10px] mr-1"></i>
                                                −{{ $jamaah->total_refund_formatted }}
                                            </td>
                                            <td colspan="3"></td>
                                        </tr>
                                        <tr class="bg-amber-100">
                                            <td colspan="4" class="px-3 py-2 text-right font-bold text-gray-800">
                                                NET DITERIMA:
                                            </td>
                                            <td class="px-3 py-2 text-right font-bold text-gray-800">
                                                {{ $jamaah->net_dibayar_formatted }}
                                            </td>
                                            <td colspan="3"></td>
                                        </tr>
                                    @endif
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-400">Belum ada riwayat pembayaran</p>
                        </div>
                    @endif
                </div>

                <!-- Form Pembayaran -->
                <form action="{{ route('transaksional.jamaah.bayar', $jamaah->id_jamaah) }}" method="POST"
                    enctype="multipart/form-data" id="formPembayaran">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Metode Pembayaran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Metode Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <select name="id_metode_pembayaran" id="id_metode_pembayaran"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                required>
                                <option value="">-- Pilih Metode --</option>

                                @php
                                    $bankTransfers = $metodePembayarans->where('jenis_pembayaran', 'bank_transfer');
                                    $cashs = $metodePembayarans->where('jenis_pembayaran', 'cash');
                                    $eWallets = $metodePembayarans->where('jenis_pembayaran', 'e_wallet');
                                @endphp

                                @if ($bankTransfers->count() > 0)
                                    <optgroup label="🏦 Bank Transfer">
                                        @foreach ($bankTransfers as $metode)
                                            <option value="{{ $metode->id_metode }}"
                                                {{ old('id_metode_pembayaran') == $metode->id_metode ? 'selected' : '' }}>
                                                {{ $metode->kode_bank }} - {{ $metode->nama_bank }}
                                                ({{ $metode->nomor_rekening }})
                                                @if ($metode->atas_nama)
                                                    - a/n {{ $metode->atas_nama }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif

                                @if ($cashs->count() > 0)
                                    <optgroup label="💰 Cash / Tunai">
                                        @foreach ($cashs as $metode)
                                            <option value="{{ $metode->id_metode }}"
                                                {{ old('id_metode_pembayaran') == $metode->id_metode ? 'selected' : '' }}>
                                                Cash / Tunai
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif

                                @if ($eWallets->count() > 0)
                                    <optgroup label="📱 E-Wallet">
                                        @foreach ($eWallets as $metode)
                                            <option value="{{ $metode->id_metode }}"
                                                {{ old('id_metode_pembayaran') == $metode->id_metode ? 'selected' : '' }}>
                                                {{ $metode->e_wallet_type }} - {{ $metode->nomor_telepon }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            </select>
                            @error('id_metode_pembayaran')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ✅ Jenis Pembayaran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Jenis Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <select name="id_jenis_pembayaran" id="id_jenis_pembayaran"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                required>
                                <option value="">-- Pilih Jenis Pembayaran --</option>
                                @foreach ($jenisPembayarans as $jenis)
                                    @php $kodeJenis = $jenis->kode; @endphp
                                    <option value="{{ $jenis->id_jenis_pembayaran }}"
                                        {{ old('id_jenis_pembayaran') == $jenis->id_jenis_pembayaran ? 'selected' : '' }}
                                        data-kode="{{ $kodeJenis ?? '' }}">
                                        {{ $jenis->nama }}
                                        @if ($kodeJenis === 'DP')
                                            (Pembayaran Awal)
                                        @elseif ($kodeJenis === 'ANGSURAN')
                                            (Cicilan)
                                        @elseif ($kodeJenis === 'LUNAS')
                                            (Pelunasan)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('id_jenis_pembayaran')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p id="jenisInfo" class="text-xs text-gray-400 mt-2">
                                <i class="fas fa-info-circle mr-1"></i> Pilih jenis pembayaran yang sesuai
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Tanggal Transaksi <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_transaksi" id="tanggal_transaksi"
                                value="{{ old('tanggal_transaksi', date('Y-m-d')) }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                required>
                            @error('tanggal_transaksi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Jumlah Bayar <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                <input type="text" name="jumlah_bayar" id="jumlah_bayar"
                                    value="{{ old('jumlah_bayar') ? number_format(old('jumlah_bayar'), 0, ',', '.') : '' }}"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-lg"
                                    placeholder="0" required oninput="formatRupiah(this)" autocomplete="off">
                            </div>
                            @error('jumlah_bayar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <div class="mt-2 flex gap-2 flex-wrap">
                                <button type="button" onclick="setJumlahBayar(25)"
                                    class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs hover:bg-gray-200 transition">25%</button>
                                <button type="button" onclick="setJumlahBayar(50)"
                                    class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs hover:bg-gray-200 transition">50%</button>
                                <button type="button" onclick="setJumlahBayar(75)"
                                    class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs hover:bg-gray-200 transition">75%</button>
                                <button type="button" onclick="setJumlahBayar(100)"
                                    class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs hover:bg-gray-200 transition">100%</button>
                                <button type="button" onclick="setJumlahBayarSisa()"
                                    class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs hover:bg-yellow-200 transition">Sisa
                                    Tagihan</button>
                            </div>
                            <p id="jumlahInfo" class="text-xs text-gray-400 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Sisa tagihan: <span class="font-bold">{{ $jamaah->sisa_tagihan_formatted }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Upload Bukti Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran"
                                accept="image/*,application/pdf" required
                                class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100"
                                onchange="previewBukti(this)">
                            <p class="text-xs text-gray-400 mt-1">
                                <span class="text-red-500 font-medium">Wajib diisi.</span>
                                Format: JPG, PNG, PDF (Max 2MB)
                            </p>
                            <div id="previewBukti" class="mt-2 hidden">
                                <img id="previewImage" src="#" alt="Preview Bukti"
                                    class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                <p id="previewName" class="text-xs text-gray-500 mt-1"></p>
                            </div>
                            @error('bukti_pembayaran')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan</label>
                            <input type="text" name="keterangan" id="keterangan" value="{{ old('keterangan') }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Catatan pembayaran (opsional)">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 mt-6">
                        <a href="{{ route('transaksional.jamaah.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors text-sm font-medium">
                            Batal </a>
                        <button type="submit"
                            class="px-6 py-3 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 transition-colors text-sm font-medium shadow-sm hover:shadow">
                            <i class="fas fa-check-circle mr-2"></i> Proses Pembayaran </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function formatRupiah(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (value === '') {
                input.value = '';
                return;
            }
            let number = parseInt(value);
            if (isNaN(number)) {
                input.value = '';
                return;
            }
            input.value = number.toLocaleString('id-ID');
        }

        function formatRupiahRefund(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (value === '') {
                input.value = '';
                return;
            }
            let number = parseInt(value);
            if (isNaN(number)) {
                input.value = '';
                return;
            }
            input.value = number.toLocaleString('id-ID');
        }

        function getRawValue(input) {
            return parseInt(input.value.replace(/[^\d]/g, '')) || 0;
        }

        function previewBukti(input) {
            const preview = document.getElementById('previewBukti');
            const image = document.getElementById('previewImage');
            const name = document.getElementById('previewName');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const allowed = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                if (!allowed.includes(file.type)) {
                    alert('Format file harus JPG, PNG, atau PDF!');
                    input.value = '';
                    preview.classList.add('hidden');
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB!');
                    input.value = '';
                    preview.classList.add('hidden');
                    return;
                }
                if (file.type === 'application/pdf') {
                    preview.classList.remove('hidden');
                    image.classList.add('hidden');
                    name.textContent = file.name + ' (PDF)';
                } else {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.classList.remove('hidden');
                        image.classList.remove('hidden');
                        image.src = e.target.result;
                        name.textContent = file.name;
                    };
                    reader.readAsDataURL(file);
                }
            } else {
                preview.classList.add('hidden');
            }
        }

        function confirmDeleteTransaksi(id) {
            if (confirm(
                    'Yakin ingin menghapus transaksi ini?\n\nData yang dihapus:\n- Jumlah pembayaran/refund akan dikembalikan\n- Status pembayaran akan diperbarui otomatis\n- Bukti pembayaran akan dihapus'
                )) {
                document.getElementById('delete-transaksi-form-' + id).submit();
            }
        }

        document.getElementById('id_jenis_pembayaran')?.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const kode = selectedOption.dataset.kode || '';
            const info = document.getElementById('jenisInfo');

            if (kode === 'DP') {
                info.innerHTML =
                    '<i class="fas fa-info-circle mr-1"></i> DP: Pembayaran awal (biasanya minimal 50% dari total tagihan)';
            } else if (kode === 'ANGSURAN') {
                info.innerHTML =
                    '<i class="fas fa-info-circle mr-1"></i> Angsuran: Pembayaran cicilan';
            } else if (kode === 'LUNAS') {
                info.innerHTML =
                    '<i class="fas fa-info-circle mr-1"></i> Lunas: Pembayaran 100% dari sisa tagihan';
            } else {
                info.innerHTML = '<i class="fas fa-info-circle mr-1"></i> Pilih jenis pembayaran yang sesuai';
            }
        });

        function setJumlahBayar(persen) {
            const totalTagihan = {{ $jamaah->total_tagihan_setelah_diskon }};
            const sisaTagihan = {{ max(0, $jamaah->sisa_tagihan) }};
            let jumlah = Math.round((totalTagihan * persen) / 100);
            if (persen === 100) {
                jumlah = sisaTagihan;
            }
            const input = document.getElementById('jumlah_bayar');
            input.value = jumlah.toLocaleString('id-ID');
            document.getElementById('jumlahInfo').innerHTML =
                '<i class="fas fa-info-circle mr-1"></i> ' +
                persen + '% dari total tagihan: <span class="font-bold">Rp ' +
                jumlah.toLocaleString('id-ID') + '</span>';
        }

        function setJumlahBayarSisa() {
            const sisa = {{ max(0, $jamaah->sisa_tagihan) }};
            const input = document.getElementById('jumlah_bayar');
            input.value = sisa.toLocaleString('id-ID');
            document.getElementById('jumlahInfo').innerHTML =
                '<i class="fas fa-info-circle mr-1"></i> ' +
                'Sisa tagihan: <span class="font-bold">Rp ' +
                sisa.toLocaleString('id-ID') + '</span>';
        }

        function openRefundModal() {
            const modal = document.getElementById('refundModal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeRefundModal() {
            const modal = document.getElementById('refundModal');
            if (modal) modal.classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const oldJenis = '{{ old('id_jenis_pembayaran') }}';
            if (oldJenis) {
                const sel = document.getElementById('id_jenis_pembayaran');
                if (sel) {
                    sel.value = oldJenis;
                    sel.dispatchEvent(new Event('change'));
                }
            }

            const jumlahBayarInput = document.getElementById('jumlah_bayar');
            if (jumlahBayarInput.value) {
                formatRupiah(jumlahBayarInput);
            }

            const refundModal = document.getElementById('refundModal');
            if (refundModal) {
                refundModal.addEventListener('click', function(e) {
                    if (e.target === this) closeRefundModal();
                });
            }
        });

        document.getElementById('formPembayaran').addEventListener('submit', function(e) {
            const buktiInput = document.getElementById('bukti_pembayaran');
            if (!buktiInput.files || buktiInput.files.length === 0) {
                e.preventDefault();
                alert('⚠️ Bukti pembayaran WAJIB diupload sebelum memproses pembayaran!');
                buktiInput.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                buktiInput.focus();
                return false;
            }
            const input = document.getElementById('jumlah_bayar');
            const rawValue = getRawValue(input);
            input.value = rawValue;
        });

        const refundForm = document.querySelector('#refundModal form');
        if (refundForm) {
            refundForm.addEventListener('submit', function(e) {
                const jumlahInput = document.getElementById('jumlah_refund');
                const rawValue = getRawValue(jumlahInput);
                const maxRefund = {{ (int) $jamaah->sisa_kelebihan_bayar }};

                if (rawValue <= 0) {
                    e.preventDefault();
                    alert('Jumlah refund harus lebih dari 0.');
                    return false;
                }
                if (rawValue > maxRefund) {
                    e.preventDefault();
                    alert('Jumlah refund melebihi sisa kelebihan bayar (maks: Rp ' + maxRefund.toLocaleString(
                        'id-ID') + ').');
                    return false;
                }
                jumlahInput.value = rawValue;
            });
        }
    </script>
@endpush
