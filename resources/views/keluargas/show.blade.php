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

                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                <span class="text-xs text-gray-500">
                                    <i class="fas fa-plane text-blue-500 mr-1"></i> Keberangkatan:
                                </span>
                                @if ($keluarga->bulan_keberangkatan && $keluarga->tahun_keberangkatan)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                        {{ $keluarga->bulan_keberangkatan_nama }} {{ $keluarga->tahun_keberangkatan }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif

                                @if ($keluarga->bulan_harga && $keluarga->tahun_harga)
                                    @if (
                                        $keluarga->bulan_harga != $keluarga->bulan_keberangkatan ||
                                            $keluarga->tahun_harga != $keluarga->tahun_keberangkatan)
                                        <span class="text-xs text-gray-500 ml-2">
                                            <i class="fas fa-tag text-green-500 mr-1"></i> Harga dari:
                                        </span>
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                                            {{ $keluarga->bulan_harga_nama }} {{ $keluarga->tahun_harga }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400 italic ml-2">(harga sama dengan
                                            keberangkatan)</span>
                                    @endif
                                @endif
                            </div>

                            <p class="text-gray-500 text-sm mt-1">
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
                            <p class="text-sm text-red-600">Diskon: -{{ $keluarga->total_diskon_formatted }}</p>
                            <p class="text-sm text-green-600">{{ $keluarga->total_dibayar_formatted }} dibayar</p>
                            <p class="text-sm text-red-500">{{ $keluarga->sisa_tagihan_formatted }} sisa</p>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Keuangan -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
                    <div class="bg-rose-50 rounded-xl p-4 border border-rose-200 text-center">
                        <p class="text-xs text-gray-500">Total Diskon</p>
                        <p class="text-xl font-bold text-rose-600">- {{ $keluarga->total_diskon_formatted }}</p>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4 border border-green-200 text-center">
                        <p class="text-xs text-gray-500">Total Tagihan</p>
                        <p class="text-xl font-bold text-gray-800">
                            {{ $keluarga->total_tagihan_setelah_diskon_formatted }}</p>
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
                                            NIK</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Nama Ayah</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Pekerjaan</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            JK</th>
                                        {{-- ✅ NEW: Kolom Status Kawin --}}
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Status Kawin</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Telepon</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            WhatsApp</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            TTL</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Alamat</th>
                                        <th
                                            class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Harga Produk</th>
                                        <th
                                            class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Passport</th>
                                        <th
                                            class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Kotor</th>
                                        <th
                                            class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Diskon</th>
                                        <th
                                            class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Tagihan</th>
                                        <th
                                            class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Dibayar</th>
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
                                                <p class="text-xs text-gray-400">{{ $jamaah->hubungan_keluarga ?? '-' }}
                                                </p>
                                            </td>
                                            <td class="px-3 py-2 text-gray-600 text-xs">{{ $jamaah->nik ?? '-' }}</td>
                                            <td class="px-3 py-2 text-gray-600 text-xs">{{ $jamaah->nama_ayah ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-gray-600 text-xs">{{ $jamaah->pekerjaan ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                @if ($jamaah->jenis_kelamin == 'L')
                                                    <span
                                                        class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs">L</span>
                                                @elseif ($jamaah->jenis_kelamin == 'P')
                                                    <span
                                                        class="px-2 py-0.5 bg-pink-100 text-pink-700 rounded text-xs">P</span>
                                                @else
                                                    <span class="text-xs text-gray-400">-</span>
                                                @endif
                                            </td>
                                            {{-- ✅ NEW: Kolom Status Kawin dengan badge berwarna --}}
                                            <td class="px-3 py-2 text-center">
                                                {!! $jamaah->status_kawin_badge !!}
                                            </td>
                                            <td class="px-3 py-2 text-gray-600 text-xs">{{ $jamaah->telepon ?? '-' }}</td>
                                            <td class="px-3 py-2 text-gray-600 text-xs">{{ $jamaah->wa ?? '-' }}</td>
                                            <td class="px-3 py-2 text-gray-600 text-xs">
                                                {{ $jamaah->tempat_lahir ?? '-' }}
                                                @if ($jamaah->tanggal_lahir)
                                                    <br><span
                                                        class="text-gray-400">{{ $jamaah->tanggal_lahir->format('d/m/Y') }}</span>
                                                @endif
                                            </td>

                                            {{-- Alamat Lengkap --}}
                                            <td class="px-3 py-2 text-gray-600 text-xs max-w-xs">
                                                <div class="text-gray-700">{{ $jamaah->alamat ?? '-' }}</div>
                                                @if ($jamaah->kelurahan || $jamaah->kecamatan || $jamaah->kota_kabupaten)
                                                    <div class="text-gray-500 mt-0.5 text-[11px]">
                                                        @if ($jamaah->kelurahan)
                                                            Kel. {{ $jamaah->kelurahan }}
                                                        @endif
                                                        @if ($jamaah->kecamatan)
                                                            , Kec. {{ $jamaah->kecamatan }}
                                                        @endif
                                                        @if ($jamaah->kota_kabupaten)
                                                            , {{ $jamaah->kota_kabupaten }}
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>

                                            <td class="px-3 py-2 text-right text-gray-600">
                                                {{ $jamaah->harga_produk_formatted }}
                                            </td>
                                            <td class="px-3 py-2 text-right text-purple-600">
                                                {{ $jamaah->total_pembuatan_passport_formatted }}
                                            </td>
                                            <td class="px-3 py-2 text-right text-gray-800">
                                                {{ $jamaah->total_tagihan_sebelum_diskon_formatted }}
                                            </td>
                                            <td class="px-3 py-2 text-right text-red-600">
                                                {{ $jamaah->total_diskon_formatted }}
                                            </td>
                                            <td class="px-3 py-2 text-right font-bold text-gray-800">
                                                {{ $jamaah->total_tagihan_setelah_diskon_formatted }}
                                            </td>
                                            <td class="px-3 py-2 text-right text-green-600">
                                                {{ $jamaah->total_dibayar_formatted }}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                {!! $jamaah->status_pembayaran_badge !!}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                <a href="{{ route('transaksional.jamaah.show', $jamaah->id_jamaah) }}"
                                                    class="text-blue-500 hover:text-blue-700 text-sm"
                                                    title="Detail Jamaah">
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

                <!-- Detail Pembuatan Passport Per Jamaah -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-passport text-yellow-500 mr-2"></i> Detail Pembuatan Passport Per Jamaah
                    </h6>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                        @php $hasPendampinganData = false; @endphp
                        @foreach ($keluarga->jamaahs as $jamaah)
                            @if ($jamaah->is_pembuatan_passport || $jamaah->pendampingan_fee > 0 || $jamaah->pendampingan_fee_petugas > 0)
                                @php $hasPendampinganData = true; @endphp
                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-semibold text-gray-700">
                                            <i class="fas fa-user text-yellow-500 mr-1"></i>
                                            {{ $jamaah->nama_lengkap }}
                                        </span>
                                        @if ($jamaah->is_kepala_keluarga)
                                            <span
                                                class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded text-xs font-medium">
                                                ★ Kepala Keluarga
                                            </span>
                                        @endif
                                    </div>

                                    <dl class="space-y-1.5 text-sm">
                                        <div class="flex justify-between">
                                            <dt class="text-gray-500">Ada Pembuatan Passport?</dt>
                                            <dd class="font-medium">{!! $jamaah->is_pembuatan_passport_badge !!}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-gray-500">Pembayaran Passport</dt>
                                            <dd class="font-medium text-green-600">
                                                {{ $jamaah->pendampingan_fee_formatted }}
                                            </dd>
                                        </div>
                                        @if ($jamaah->is_pembuatan_passport)
                                            <div class="flex justify-between">
                                                <dt class="text-gray-500">Pendapatan Perusahaan</dt>
                                                <dd class="font-medium text-purple-600">
                                                    {{ $jamaah->pendampingan_fee_petugas_formatted }}
                                                </dd>
                                            </div>
                                        @endif
                                        <div class="flex justify-between pt-1.5 border-t border-gray-100">
                                            <dt class="text-gray-600 font-medium">Total Pembuatan Passport</dt>
                                            <dd class="font-bold text-blue-600">
                                                {{ $jamaah->total_pembuatan_passport_formatted }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            @endif
                        @endforeach

                        @if (!$hasPendampinganData)
                            <div class="col-span-full text-center py-4">
                                <p class="text-sm text-gray-400">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Belum ada data pembuatan passport untuk jamaah dalam keluarga ini
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- UPGRADE HOTEL KELUARGA --}}
                @if (
                    $keluarga->upgradeHotels->count() > 0 ||
                        $keluarga->jamaahs->sum(fn($j) => $j->upgradeHotels->sum('total_harga')) > 0)
                    <div class="bg-orange-50 rounded-xl p-5 border border-orange-200 mb-6">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center justify-between">
                            <span>
                                <i class="fas fa-hotel text-orange-500 mr-2"></i> Upgrade Hotel & Kamar
                            </span>
                            <span class="text-xs bg-orange-100 text-orange-800 px-2 py-0.5 rounded-full">
                                Total: {{ $keluarga->total_upgrade_hotel_formatted }}
                            </span>
                        </h6>

                        {{-- Ringkasan --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                            <div class="bg-white rounded-lg p-3 border border-orange-200 text-center">
                                <p class="text-xs text-gray-500">Upgrade Keluarga</p>
                                <p class="text-sm font-bold text-orange-600">
                                    {{ $keluarga->total_upgrade_hotel_formatted }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-blue-200 text-center">
                                <p class="text-xs text-gray-500">Jumlah Jamaah</p>
                                <p class="text-sm font-bold text-blue-600">{{ $keluarga->jamaahs->count() }} Orang</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-purple-200 text-center">
                                <p class="text-xs text-gray-500">Per Jamaah</p>
                                <p class="text-sm font-bold text-purple-600">
                                    {{ $keluarga->upgrade_hotel_per_jamaah_formatted }}</p>
                            </div>
                        </div>

                        {{-- Detail Upgrade Keluarga --}}
                        @if ($keluarga->upgradeHotels->count() > 0)
                            <div class="mb-4">
                                <h6 class="text-xs font-semibold text-gray-600 mb-2 flex items-center">
                                    <i class="fas fa-users mr-1 text-orange-500"></i> Upgrade Hotel Keluarga (dibagi rata)
                                </h6>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm bg-white rounded-lg overflow-hidden">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">
                                                    #</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">
                                                    Hotel</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">
                                                    Tipe Kamar</th>
                                                <th
                                                    class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase">
                                                    Jumlah</th>
                                                <th
                                                    class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase">
                                                    Harga/Malam</th>
                                                <th
                                                    class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase">
                                                    Malam</th>
                                                <th
                                                    class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase">
                                                    Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach ($keluarga->upgradeHotels as $idx => $upgrade)
                                                <tr>
                                                    <td class="px-3 py-2 text-gray-500">{{ $idx + 1 }}</td>
                                                    <td class="px-3 py-2">
                                                        <span
                                                            class="font-medium text-gray-700">{{ $upgrade->hotel->nama_hotel ?? '-' }}</span>
                                                        <p class="text-xs text-gray-400">{{ $upgrade->hotel->kota ?? '' }}
                                                        </p>
                                                    </td>
                                                    <td class="px-3 py-2 text-gray-600">{{ $upgrade->tipe_kamar ?? '-' }}
                                                    </td>
                                                    <td class="px-3 py-2 text-right text-gray-600">
                                                        {{ $upgrade->jumlah_kamar }}</td>
                                                    <td class="px-3 py-2 text-right text-gray-600">
                                                        Rp {{ number_format($upgrade->harga_per_malam, 0, ',', '.') }}
                                                    </td>
                                                    <td class="px-3 py-2 text-right text-gray-600">
                                                        {{ $upgrade->durasi_menginap }}</td>
                                                    <td class="px-3 py-2 text-right font-bold text-orange-600">
                                                        Rp {{ number_format($upgrade->total_harga, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-orange-100 border-t-2 border-orange-300">
                                            <tr>
                                                <td colspan="6" class="px-3 py-2 text-right font-bold text-gray-700">
                                                    TOTAL KELUARGA</td>
                                                <td class="px-3 py-2 text-right font-bold text-orange-700">
                                                    {{ $keluarga->total_upgrade_hotel_formatted }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        @endif

                        {{-- Detail Upgrade Personal per Jamaah --}}
                        @php
                            $jamaahWithUpgrade = $keluarga->jamaahs->filter(fn($j) => $j->upgradeHotels->count() > 0);
                        @endphp

                        @if ($jamaahWithUpgrade->count() > 0)
                            <div class="mt-4 pt-4 border-t border-orange-200">
                                <h6 class="text-xs font-semibold text-gray-600 mb-2 flex items-center">
                                    <i class="fas fa-user mr-1 text-blue-500"></i> Upgrade Personal per Jamaah
                                </h6>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                                    @foreach ($jamaahWithUpgrade as $jamaah)
                                        <div class="bg-white rounded-lg p-3 border border-blue-200">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="font-semibold text-gray-700 text-sm">
                                                    <i class="fas fa-user text-blue-500 mr-1"></i>
                                                    {{ $jamaah->nama_lengkap }}
                                                </span>
                                                <span class="text-xs font-bold text-blue-600">
                                                    {{ $jamaah->total_upgrade_hotel_formatted }}
                                                </span>
                                            </div>
                                            <ul class="space-y-1">
                                                @foreach ($jamaah->upgradeHotels as $upgrade)
                                                    <li class="flex justify-between text-xs text-gray-600">
                                                        <span>
                                                            {{ $upgrade->hotel->nama_hotel ?? '-' }} -
                                                            {{ $upgrade->tipe_kamar }}
                                                            ({{ $upgrade->jumlah_kamar }}x{{ $upgrade->durasi_menginap }}
                                                            mlm)
                                                        </span>
                                                        <span class="font-medium text-orange-600">
                                                            Rp {{ number_format($upgrade->total_harga, 0, ',', '.') }}
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mt-3 pt-3 border-t border-orange-200">
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Upgrade hotel keluarga dibagi rata ke semua jamaah. Upgrade personal hanya dibebankan ke
                                jamaah masing-masing.
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Riwayat Pembayaran Keluarga -->
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
                                            class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Dibuat Oleh</th>
                                        <th
                                            class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($transaksis as $index => $transaksi)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-3 py-2 text-gray-400 text-xs">{{ $loop->iteration }}</td>
                                            <td class="px-3 py-2 text-gray-600 whitespace-nowrap">
                                                {{ $transaksi->tanggal_transaksi ? date('d M Y', strtotime($transaksi->tanggal_transaksi)) : '-' }}
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
                                            <td class="px-3 py-2 text-center text-xs text-gray-500">
                                                {{ $transaksi->created_by ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                <button type="button"
                                                    onclick="confirmDeleteTransaksiKeluarga({{ $transaksi->id_transaksi }})"
                                                    class="text-red-500 hover:text-red-700 transition-colors duration-200"
                                                    title="Hapus Transaksi">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                                <form id="delete-transaksi-keluarga-form-{{ $transaksi->id_transaksi }}"
                                                    action="{{ route('transaksional.keluarga.hapus-transaksi', $transaksi->id_transaksi) }}"
                                                    method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
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
                    <a href="{{ route('transaksional.keluarga.cetak_pembayaran', $keluarga->id_keluarga) }}"
                        target="_blank"
                        class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-print mr-2"></i> Cetak PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDeleteTransaksiKeluarga(id) {
            if (confirm(
                    'Yakin ingin menghapus transaksi pembayaran ini?\n\n' +
                    'Data yang dihapus:\n' +
                    '- Jumlah pembayaran jamaah akan dikurangi\n' +
                    '- Sisa tagihan jamaah akan dihitung ulang\n' +
                    '- Status pembayaran keluarga akan diperbarui\n' +
                    '- Bukti pembayaran akan dihapus dari storage'
                )) {
                document.getElementById('delete-transaksi-keluarga-form-' + id).submit();
            }
        }
    </script>
@endpush
