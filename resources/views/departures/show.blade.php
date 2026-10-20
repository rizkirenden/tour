@extends('layouts.app')

@section('title', 'Detail Keberangkatan - Arrum Tour')
@section('page-title', 'Detail Keberangkatan')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.departure.index') }}" class="text-gray-500 hover:text-yellow-600">Transaksional</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.departure.index') }}" class="text-gray-500 hover:text-yellow-600">Keberangkatan</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500 font-medium">Detail</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- HEADER -->
            <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Detail Keberangkatan</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Kelola data keberangkatan secara bertahap</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <a href="{{ route('transaksional.departure.edit', $departure->id_departure) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit Dasar
                    </a>
                    <button onclick="recalculate({{ $departure->id_departure }})"
                        class="inline-flex items-center px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium">
                        <i class="fas fa-sync-alt mr-2"></i> Recalculate
                    </button>
                    <a href="{{ route('transaksional.departure.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- PROGRESS BAR -->
            <div class="px-6 pt-4">
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-gray-700">Progress:</span>
                    <div class="flex-1 bg-gray-200 rounded-full h-2.5">
                        <div class="h-2.5 rounded-full {{ $departure->progress_color }}"
                            style="width: {{ $departure->progress_percentage }}%"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700">{{ $departure->progress_percentage }}%</span>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="p-6">
                {{-- HEADER KEBERANGKATAN --}}
                <div
                    class="bg-gradient-to-r from-yellow-50 to-yellow-100/50 rounded-xl p-6 mb-6 border border-yellow-200/50">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h2 class="text-2xl font-bold text-gray-800">{{ $departure->nama_keberangkatan }}</h2>
                                <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-medium rounded-full">
                                    {{ $departure->kode_keberangkatan }}
                                </span>
                                {!! $departure->status_badge !!}
                            </div>
                            <p class="text-gray-500 text-sm mt-1">
                                <i class="fas fa-box mr-1"></i> Produk: {{ $departure->produk_paket }}
                            </p>
                            <p class="text-gray-500 text-sm">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                {{ $departure->tanggal_keberangkatan->format('d F Y') }} -
                                {{ $departure->tanggal_kepulangan->format('d F Y') }}
                            </p>
                            @if ($departure->bulan_keberangkatan)
                                <div
                                    class="mt-2 inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-lg">
                                    <i class="fas fa-filter text-blue-500"></i>
                                    <span class="text-xs text-blue-700">
                                        <strong>Filter Jamaah:</strong>
                                        {{ date('F', mktime(0, 0, 0, $departure->bulan_keberangkatan, 1)) }}
                                        {{ $departure->tahun_keberangkatan }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-yellow-600">
                                {{ $departure->total_pendapatan_bersih_formatted }}</p>
                            <p class="text-sm text-gray-500">Total Pendapatan Bersih</p>
                            <p class="text-sm text-green-600">{{ $departure->keuntungan_formatted }} Laba</p>
                            <p class="text-xs text-gray-400">Margin: {{ $departure->margin_laba }}%</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Kuota</span>
                            <span class="font-medium text-gray-700">{{ $departure->jamaah_terdaftar }} /
                                {{ $departure->kuota }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                            <div class="bg-yellow-500 h-2 rounded-full transition-all duration-500"
                                style="width: {{ $departure->kuota > 0 ? ($departure->jamaah_terdaftar / $departure->kuota) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 1. PENDAPATAN --}}
                <div class="mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-money-bill-wave text-green-500 mr-2"></i> Pendapatan
                    </h6>
                </div>

                {{-- 1. RINGKASAN PENDAPATAN --}}
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-money-bill-wave text-green-500 mr-2"></i> Ringkasan Pendapatan
                    </h6>

                    @php
                        $pendapatanPerusahaanPassport = $departure->total_pendapatan_perusahaan_passport;
                        $pembayaranPassport = $departure->total_pembayaran_passport;
                    @endphp

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                            <p class="text-xs text-gray-500">Pendapatan Kotor</p>
                            <p class="text-sm font-bold text-green-600">{{ $departure->total_pendapatan_kotor_formatted }}
                            </p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                            <p class="text-xs text-gray-500">Total Diskon</p>
                            <p class="text-sm font-bold text-red-600">{{ $departure->total_diskon_formatted }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-purple-200 text-center">
                            <p class="text-xs text-gray-500">Pendapatan Perusahaan Passport</p>
                            <p class="text-sm font-bold text-purple-600">
                                {{ $departure->total_pendapatan_perusahaan_passport_formatted }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                            <p class="text-xs text-gray-500">Total Pendapatan</p>
                            <p class="text-sm font-bold text-blue-600">{{ $departure->total_pendapatan_formatted }}</p>
                        </div>
                    </div>

                    @if ($pendapatanPerusahaanPassport > 0 || $pembayaranPassport > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                            <div class="bg-green-50 rounded-lg p-3 border-2 border-green-300">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-green-700 font-semibold"><i
                                                class="fas fa-arrow-up mr-1"></i>Pendapatan Perusahaan Passport</p>
                                        <p class="text-[10px] text-green-600 mt-0.5">Masuk ke PENDAPATAN (fee petugas dari
                                            jamaah efektif YA)</p>
                                    </div>
                                    <p class="text-lg font-bold text-green-700">Rp
                                        {{ number_format($pendapatanPerusahaanPassport, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="bg-red-50 rounded-lg p-3 border-2 border-red-300">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-red-700 font-semibold"><i
                                                class="fas fa-arrow-down mr-1"></i>Pembayaran Passport</p>
                                        <p class="text-[10px] text-red-600 mt-0.5">Masuk ke PENGELUARAN (biaya pembuatan
                                            passport)</p>
                                    </div>
                                    <p class="text-lg font-bold text-red-700">Rp
                                        {{ number_format($pembayaranPassport, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                            <p class="text-xs text-gray-500">Pendapatan Bersih</p>
                            <p class="text-sm font-bold text-yellow-600">
                                {{ $departure->total_pendapatan_bersih_formatted }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                            <p class="text-xs text-gray-500">Keuntungan</p>
                            <p
                                class="text-lg font-bold {{ $departure->keuntungan > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $departure->keuntungan_formatted }}</p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-green-100 to-emerald-100 rounded-lg p-4 border-2 border-green-400">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div>
                                <p class="text-sm font-bold text-green-800"><i class="fas fa-calculator mr-2"></i>TOTAL
                                    KESELURUHAN PENDAPATAN</p>
                                <p class="text-[10px] text-green-600 mt-0.5">Pendapatan Kotor + Pendapatan Perusahaan
                                    Passport</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xl md:text-2xl font-extrabold text-green-700">
                                    {{ $departure->total_pendapatan_bersih_formatted }}</p>
                                <p class="text-[10px] text-green-600">Pendapatan Bersih Final</p>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-green-300 grid grid-cols-2 md:grid-cols-3 gap-2 text-xs">
                            <div class="flex justify-between">
                                <span class="text-green-700">Kotor:</span>
                                <span
                                    class="font-bold text-green-800">{{ $departure->total_pendapatan_kotor_formatted }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-green-700">Passport Perusahaan:</span>
                                <span
                                    class="font-bold text-purple-600">{{ $departure->total_pendapatan_perusahaan_passport_formatted }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-green-700">Bersih:</span>
                                <span
                                    class="font-bold text-green-800">{{ $departure->total_pendapatan_bersih_formatted }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. FEE PASSPORT & DAFTAR JAMAAH --}}
                @php
                    $bulanNama = [
                        1 => 'Jan',
                        2 => 'Feb',
                        3 => 'Mar',
                        4 => 'Apr',
                        5 => 'Mei',
                        6 => 'Jun',
                        7 => 'Jul',
                        8 => 'Agu',
                        9 => 'Sep',
                        10 => 'Okt',
                        11 => 'Nov',
                        12 => 'Des',
                    ];
                    $bulanFull = [
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember',
                    ];
                    $totalFeePassport = $departure->total_fee_passport;
                    $totalPembayaranPassport = $departure->total_pembayaran_passport;
                    $totalPendapatanPerusahaanPassport = $departure->total_pendapatan_perusahaan_passport;
                    $jamaahDenganPassport = $departure->jamaah_dengan_passport;
                    $jamaahTanpaPassport = $departure->jamaah_tanpa_passport;
                @endphp

                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-6">
                    <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                        <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-passport text-purple-500 mr-2"></i> Fee Passport & Daftar Jamaah
                            <span
                                class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">{{ $departure->jamaahs->count() }}
                                Jamaah</span>
                        </h6>
                        <div class="flex items-center gap-2">
                            {!! $departure->jamaah_status_badge !!}
                            <form action="{{ route('transaksional.departure.sync-jamaahs', $departure->id_departure) }}"
                                method="POST"
                                onsubmit="return confirm('Mencari jamaah baru yang memenuhi kriteria (Lunas, produk sesuai, bulan/tahun sesuai, dan belum terdaftar di departure lain). Lanjutkan?')">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 bg-purple-500 text-white text-xs rounded-lg hover:bg-purple-600 transition"
                                    title="Cari jamaah baru yang tersedia">
                                    <i class="fas fa-sync-alt mr-1"></i> Sync Jamaah
                                </button>
                            </form>
                            <button onclick="openJamaahModal()"
                                class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs rounded-lg hover:bg-yellow-600 transition">
                                <i class="fas fa-plus mr-1"></i> Atur Daftar Jamaah
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
                        <div class="bg-white rounded-lg p-3 border border-purple-200 text-center">
                            <p class="text-xs text-gray-500">Total Fee Passport</p>
                            <p class="text-lg font-bold text-purple-600">Rp
                                {{ number_format($totalFeePassport, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">Bayar + Perusahaan</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-red-200 text-center">
                            <p class="text-xs text-gray-500">→ Pengeluaran</p>
                            <p class="text-lg font-bold text-red-600">Rp
                                {{ number_format($totalPembayaranPassport, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">Pembayaran Passport</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-green-200 text-center">
                            <p class="text-xs text-gray-500">→ Pendapatan</p>
                            <p class="text-lg font-bold text-green-600">Rp
                                {{ number_format($totalPendapatanPerusahaanPassport, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">Pendapatan Perusahaan</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-green-300 text-center">
                            <p class="text-xs text-gray-500">Jamaah Efektif (YA)</p>
                            <p class="text-lg font-bold text-green-700">{{ $jamaahDenganPassport }} orang</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">YA + TIDAK tapi ada bayar</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-blue-200 text-center">
                            <p class="text-xs text-gray-500">Total Jamaah</p>
                            <p class="text-lg font-bold text-gray-700">{{ $departure->jamaahs->count() }} orang</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">TIDAK murni: {{ $jamaahTanpaPassport }} orang</p>
                        </div>
                    </div>

                    <div class="mb-3 flex flex-wrap items-center gap-3 text-xs">
                        <span class="inline-flex items-center gap-1.5"><span
                                class="w-3 h-3 rounded-full bg-green-500"></span><span
                                class="text-gray-600">Lunas</span></span>
                        <span class="inline-flex items-center gap-1.5"><span
                                class="w-3 h-3 rounded-full bg-red-500"></span><span class="text-gray-600">Belum
                                Lunas</span></span>
                        <span class="inline-flex items-center gap-1.5"><span
                                class="w-3 h-3 rounded-full bg-purple-500"></span><span class="text-gray-600">Fee
                                Passport</span></span>
                        <span class="inline-flex items-center gap-1.5"><span
                                class="w-3 h-3 rounded-full bg-green-600"></span><span class="text-gray-600">Pembuatan
                                Passport YA</span></span>
                    </div>

                    <div class="mb-3">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" id="searchJamaah" placeholder="Cari jamaah..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                        </div>
                    </div>

                    @if ($departure->jamaahs->count() > 0)
                        <div class="max-h-96 overflow-y-auto space-y-1.5" id="jamaahList">
                            @foreach ($departure->jamaahs as $jamaah)
                                @php
                                    $isLunas = $jamaah->status_pembayaran == 'Lunas';
                                    if ($isLunas) {
                                        $borderClass = 'border-l-4 border-l-green-500';
                                        $bgClass = 'bg-green-50/60';
                                        $badgeClass = 'bg-green-500 text-white';
                                        $badgeText = 'LUNAS';
                                    } else {
                                        $borderClass = 'border-l-4 border-l-red-500';
                                        $bgClass = 'bg-red-50/60';
                                        $badgeClass = 'bg-red-500 text-white';
                                        $badgeText = 'BELUM LUNAS';
                                    }
                                    $jamaahKeberangkatanInfo = '';
                                    $jamaahHargaInfo = '';
                                    $jamaahHargaLabel = 'Harga';
                                    $jamaahFeePassport = $jamaah->fee_passport;
                                    $jamaahIsPembuatanPassport = $jamaah->is_pembuatan_passport_efektif;
                                    $jamaahPembayaranPassport = $jamaah->pendampingan_fee ?? 0;
                                    $jamaahPendapatanPerusahaan = $jamaah->pendampingan_fee_petugas ?? 0;
                                    $jamaahPilihanAsli = $jamaah->is_pembuatan_passport;

                                    if ($jamaah->keberangkatan && $jamaah->keberangkatan->count() > 0) {
                                        $firstK = $jamaah->keberangkatan->first();
                                        $bulanPivot = $firstK->pivot->bulan_keberangkatan ?? null;
                                        $tahunPivot = $firstK->pivot->tahun_keberangkatan ?? null;
                                        if ($bulanPivot && $tahunPivot) {
                                            $jamaahKeberangkatanInfo =
                                                ($bulanNama[$bulanPivot] ?? '-') . ' ' . $tahunPivot;
                                            $jamaahHargaLabel =
                                                'Harga (' . ($bulanFull[$bulanPivot] ?? '-') . ' ' . $tahunPivot . ')';
                                        }
                                        if ($firstK->pivot->harga_paket ?? null) {
                                            $jamaahHargaInfo =
                                                'Rp ' . number_format($firstK->pivot->harga_paket, 0, ',', '.');
                                        } elseif ($firstK->pivot->harga ?? null) {
                                            $jamaahHargaInfo =
                                                'Rp ' . number_format($firstK->pivot->harga, 0, ',', '.');
                                        }
                                    } elseif ($jamaah->bulan_keberangkatan && $jamaah->tahun_keberangkatan) {
                                        $jamaahKeberangkatanInfo =
                                            ($bulanNama[$jamaah->bulan_keberangkatan] ?? '-') .
                                            ' ' .
                                            $jamaah->tahun_keberangkatan;
                                        $jamaahHargaLabel =
                                            'Harga (' .
                                            ($bulanFull[$jamaah->bulan_keberangkatan] ?? '-') .
                                            ' ' .
                                            $jamaah->tahun_keberangkatan .
                                            ')';
                                    }
                                    if (!$jamaahHargaInfo) {
                                        if ($jamaah->harga_paket ?? null) {
                                            $jamaahHargaInfo = 'Rp ' . number_format($jamaah->harga_paket, 0, ',', '.');
                                        } elseif ($jamaah->total_tagihan ?? null) {
                                            $jamaahHargaInfo =
                                                'Rp ' . number_format($jamaah->total_tagihan, 0, ',', '.');
                                        } elseif ($jamaah->total_tagihan_setelah_diskon ?? null) {
                                            $jamaahHargaInfo =
                                                'Rp ' .
                                                number_format($jamaah->total_tagihan_setelah_diskon, 0, ',', '.');
                                        }
                                    }
                                @endphp
                                <div class="p-3 rounded-lg {{ $borderClass }} {{ $bgClass }} jamaah-item"
                                    data-name="{{ strtolower($jamaah->nama_lengkap) }}"
                                    data-status="{{ strtolower($jamaah->status_pembayaran) }}">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <p class="text-sm font-semibold text-gray-800">{{ $jamaah->nama_lengkap }}
                                                </p>
                                                <span
                                                    class="px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $badgeClass }}">{{ $badgeText }}</span>
                                                @if ($jamaahIsPembuatanPassport)
                                                    <span
                                                        class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-green-600 text-white">
                                                        <i
                                                            class="fas fa-passport mr-0.5"></i>{{ $jamaahPilihanAsli ? 'PASSPORT YA' : 'PASSPORT YA (EFEKTIF)' }}
                                                    </span>
                                                    @if ($jamaahFeePassport > 0)
                                                        <span
                                                            class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-500 text-white">
                                                            FEE PASS {{ number_format($jamaahFeePassport, 0, ',', '.') }}
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500 truncate">{{ $jamaah->produk_paket }}</p>
                                            @if ($jamaahKeberangkatanInfo)
                                                <p class="text-[11px] text-blue-600 mt-0.5"><i
                                                        class="fas fa-plane mr-0.5"></i> Keberangkatan:
                                                    <strong>{{ $jamaahKeberangkatanInfo }}</strong>
                                                </p>
                                            @endif
                                            @if ($jamaahHargaInfo)
                                                <p class="text-[11px] text-emerald-600 font-semibold mt-0.5"><i
                                                        class="fas fa-money-bill-wave mr-0.5"></i>
                                                    {{ $jamaahHargaLabel }}: <strong>{{ $jamaahHargaInfo }}</strong></p>
                                            @endif
                                            @if ($jamaahIsPembuatanPassport)
                                                <p class="text-[11px] text-purple-600 font-semibold mt-0.5"><i
                                                        class="fas fa-passport mr-0.5"></i> Fee Passport: <strong>Rp
                                                        {{ number_format($jamaahFeePassport, 0, ',', '.') }}</strong></p>
                                                @if ($jamaahPembayaranPassport > 0)
                                                    <p class="text-[10px] text-red-600 ml-3">→ Pengeluaran (Bayar): Rp
                                                        {{ number_format($jamaahPembayaranPassport, 0, ',', '.') }}</p>
                                                @endif
                                                @if ($jamaahPendapatanPerusahaan > 0)
                                                    <p class="text-[10px] text-green-600 ml-3">→ Pendapatan (Perusahaan):
                                                        Rp {{ number_format($jamaahPendapatanPerusahaan, 0, ',', '.') }}
                                                    </p>
                                                @endif
                                                @if (!$jamaahPilihanAsli && $jamaahPembayaranPassport > 0)
                                                    <p class="text-[10px] text-yellow-600 ml-3 italic"><i
                                                            class="fas fa-info-circle mr-0.5"></i>Pilihan TIDAK tapi ada
                                                        pembayaran passport → dihitung sebagai YA</p>
                                                @endif
                                            @endif
                                            @if ($jamaah->agent_name)
                                                <p class="text-[11px] text-purple-600 mt-0.5"><i
                                                        class="fas fa-user-tie mr-0.5"></i>{{ $jamaah->agent_name }}</p>
                                            @endif
                                        </div>
                                        <div class="flex flex-col items-end gap-1 flex-shrink-0">
                                            <span
                                                class="text-xs text-green-600 font-medium">{{ $jamaah->total_dibayar_formatted }}</span>
                                            <button
                                                onclick="removeJamaah({{ $departure->id_departure }}, {{ $jamaah->id_jamaah }})"
                                                class="text-red-500 hover:text-red-700 text-xs"
                                                title="Hapus dari keberangkatan"><i class="fas fa-times"></i></button>
                                            <form
                                                id="remove-jamaah-form-{{ $departure->id_departure }}-{{ $jamaah->id_jamaah }}"
                                                action="{{ route('transaksional.departure.remove-jamaah', [$departure->id_departure, $jamaah->id_jamaah]) }}"
                                                method="POST" class="hidden">
                                                @csrf @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-700">Total Pendapatan dari Jamaah</span>
                                <span
                                    class="text-lg font-bold text-green-600">{{ $departure->total_pendapatan_kotor_formatted }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-500 mt-1">
                                <span>Total Jamaah: {{ $departure->jamaahs->count() }} orang</span>
                                <span>Rata-rata per Jamaah:
                                    {{ $departure->total_pendapatan_kotor > 0 && $departure->jamaahs->count() > 0 ? 'Rp ' . number_format($departure->total_pendapatan_kotor / $departure->jamaahs->count(), 0, ',', '.') : 'Rp 0' }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-500 mt-1">
                                <span><i class="fas fa-passport text-purple-500 mr-1"></i>Total Fee Passport
                                    ({{ $jamaahDenganPassport }} jamaah efektif YA)</span>
                                <span class="font-bold text-purple-600">Rp
                                    {{ number_format($totalFeePassport, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-red-600 mt-0.5">
                                <span class="ml-4"><i class="fas fa-arrow-right mr-1"></i>Pembayaran Passport (masuk
                                    pengeluaran)</span>
                                <span class="font-medium">Rp
                                    {{ number_format($totalPembayaranPassport, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-green-600">
                                <span class="ml-4"><i class="fas fa-arrow-right mr-1"></i>Pendapatan Perusahaan (masuk
                                    pendapatan)</span>
                                <span class="font-medium">Rp
                                    {{ number_format($totalPendapatanPerusahaanPassport, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-users text-gray-300 text-xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm">Belum ada jamaah terdaftar</p>
                            <p class="text-gray-400 text-xs mt-1">Klik tombol "Sync Jamaah" atau "Atur Daftar Jamaah" untuk
                                menambahkan</p>
                        </div>
                    @endif
                </div>

                {{-- 3. PENGELUARAN --}}
                <div class="mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-receipt text-red-500 mr-2"></i> Pengeluaran
                    </h6>
                </div>

                {{-- MASKAPAI --}}
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-plane text-yellow-500 mr-2"></i> Maskapai & Harga
                        </h6>
                        <div class="flex items-center gap-2">
                            {!! $departure->maskapai_status_badge !!}
                            <button onclick="openMaskapaiModal()"
                                class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs rounded-lg hover:bg-yellow-600 transition">
                                <i class="fas fa-plus mr-1"></i> Atur Maskapai
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" id="searchMaskapai" placeholder="Cari maskapai..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                        </div>
                    </div>
                    <dl class="space-y-3" id="maskapaiList">
                        <div class="flex justify-between text-sm border-b border-gray-100 pb-2 maskapai-item"
                            data-nama="domestik berangkat">
                            <dt class="text-gray-500">Domestik Berangkat</dt>
                            <dd class="font-medium text-gray-700 text-right">
                                <div>{{ $departure->maskapaiDomestikBerangkat->nama_maskapai ?? '-' }}</div>
                                <div class="text-xs text-yellow-600 font-bold">
                                    {{ $departure->harga_maskapai_domestik_berangkat_formatted }}</div>
                            </dd>
                        </div>
                        <div class="flex justify-between text-sm border-b border-gray-100 pb-2 maskapai-item"
                            data-nama="domestik pulang">
                            <dt class="text-gray-500">Domestik Pulang</dt>
                            <dd class="font-medium text-gray-700 text-right">
                                <div>{{ $departure->maskapaiDomestikPulang->nama_maskapai ?? '-' }}</div>
                                <div class="text-xs text-yellow-600 font-bold">
                                    {{ $departure->harga_maskapai_domestik_pulang_formatted }}</div>
                            </dd>
                        </div>
                        <div class="flex justify-between text-sm border-b border-gray-100 pb-2 maskapai-item"
                            data-nama="internasional berangkat">
                            <dt class="text-gray-500">Internasional Berangkat</dt>
                            <dd class="font-medium text-gray-700 text-right">
                                <div>{{ $departure->maskapaiInternasionalBerangkat->nama_maskapai ?? '-' }}</div>
                                <div class="text-xs text-yellow-600 font-bold">
                                    {{ $departure->harga_maskapai_internasional_berangkat_formatted }}</div>
                            </dd>
                        </div>
                        <div class="flex justify-between text-sm border-b border-gray-100 pb-2 maskapai-item"
                            data-nama="internasional pulang">
                            <dt class="text-gray-500">Internasional Pulang</dt>
                            <dd class="font-medium text-gray-700 text-right">
                                <div>{{ $departure->maskapaiInternasionalPulang->nama_maskapai ?? '-' }}</div>
                                <div class="text-xs text-yellow-600 font-bold">
                                    {{ $departure->harga_maskapai_internasional_pulang_formatted }}</div>
                            </dd>
                        </div>
                        <div class="flex justify-between text-sm font-semibold border-t border-gray-200 pt-2 mt-2">
                            <dt class="text-gray-700">Total Harga Maskapai</dt>
                            <dd class="text-lg font-bold text-blue-600">{{ $departure->total_harga_maskapai_formatted }}
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- HOTEL --}}
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-hotel text-yellow-500 mr-2"></i> Hotel & Tipe Kamar
                        </h6>
                        <div class="flex items-center gap-2">
                            {!! $departure->hotel_status_badge !!}
                            <button onclick="openHotelModal()"
                                class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs rounded-lg hover:bg-yellow-600 transition">
                                <i class="fas fa-plus mr-1"></i> Atur Hotel
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" id="searchHotel" placeholder="Cari hotel..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                        </div>
                    </div>
                    @php
                        $hotelMekkahDetails = $departure->hotelMekkahDetails;
                        $hotelMadinahDetails = $departure->hotelMadinahDetails;
                        $hotelTransitDetails = $departure->hotelDetails->whereNotIn('id_hotel', [
                            $departure->id_hotel_mekkah,
                            $departure->id_hotel_madinah,
                        ]);
                    @endphp
                    <div id="hotelList">
                        @if ($departure->hotelMekkah || $departure->hotelMadinah || $departure->hotelTransit)
                            @if ($departure->hotelMekkah)
                                <div class="mb-4 hotel-item" data-nama="{{ strtolower($departure->hotel_mekkah_nama) }}">
                                    <div class="flex items-center justify-between">
                                        <h5 class="text-sm font-semibold text-gray-700">
                                            {{ $departure->hotel_mekkah_nama }}</h5>
                                        <span
                                            class="text-sm font-bold text-yellow-600">{{ $departure->total_harga_hotel_mekkah_formatted }}</span>
                                    </div>
                                    @if ($hotelMekkahDetails->count() > 0)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                                            @foreach ($hotelMekkahDetails as $detail)
                                                <div class="bg-white rounded-lg p-3 border border-gray-200 hotel-detail-item"
                                                    data-hotel="mekkah">
                                                    <p class="text-sm font-medium text-gray-700">{{ $detail->tipe_kamar }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">{{ $detail->jumlah_kamar }} Kamar ×
                                                        {{ $detail->harga_per_malam_formatted }} ×
                                                        {{ $detail->durasi_menginap }} Malam</p>
                                                    <p class="text-sm font-bold text-yellow-600">
                                                        {{ $detail->total_harga_formatted }}</p>
                                                    @if ($detail->catatan)
                                                        <p class="text-xs text-gray-400 mt-1">{{ $detail->catatan }}</p>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif
                            @if ($departure->hotelMadinah)
                                <div class="mb-4 hotel-item"
                                    data-nama="{{ strtolower($departure->hotel_madinah_nama) }}">
                                    <div class="flex items-center justify-between">
                                        <h5 class="text-sm font-semibold text-gray-700">
                                            {{ $departure->hotel_madinah_nama }}</h5>
                                        <span
                                            class="text-sm font-bold text-yellow-600">{{ $departure->total_harga_hotel_madinah_formatted }}</span>
                                    </div>
                                    @if ($hotelMadinahDetails->count() > 0)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                                            @foreach ($hotelMadinahDetails as $detail)
                                                <div class="bg-white rounded-lg p-3 border border-gray-200 hotel-detail-item"
                                                    data-hotel="madinah">
                                                    <p class="text-sm font-medium text-gray-700">{{ $detail->tipe_kamar }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">{{ $detail->jumlah_kamar }} Kamar ×
                                                        {{ $detail->harga_per_malam_formatted }} ×
                                                        {{ $detail->durasi_menginap }} Malam</p>
                                                    <p class="text-sm font-bold text-yellow-600">
                                                        {{ $detail->total_harga_formatted }}</p>
                                                    @if ($detail->catatan)
                                                        <p class="text-xs text-gray-400 mt-1">{{ $detail->catatan }}</p>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif
                            @if ($departure->hotelTransit)
                                <div class="mb-4 hotel-item"
                                    data-nama="{{ strtolower($departure->hotel_transit_nama) }}">
                                    <div class="flex items-center justify-between">
                                        <h5 class="text-sm font-semibold text-gray-700">
                                            {{ $departure->hotel_transit_nama }}</h5>
                                        <span
                                            class="text-sm font-bold text-yellow-600">{{ $departure->total_harga_hotel_transit_formatted }}</span>
                                    </div>
                                    @if ($hotelTransitDetails->count() > 0)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                                            @foreach ($hotelTransitDetails as $detail)
                                                <div class="bg-white rounded-lg p-3 border border-gray-200 hotel-detail-item"
                                                    data-hotel="transit">
                                                    <p class="text-sm font-medium text-gray-700">
                                                        {{ $detail->tipe_kamar }}</p>
                                                    <p class="text-xs text-gray-500">{{ $detail->jumlah_kamar }} Kamar ×
                                                        {{ $detail->harga_per_malam_formatted }} ×
                                                        {{ $detail->durasi_menginap }} Malam</p>
                                                    <p class="text-sm font-bold text-yellow-600">
                                                        {{ $detail->total_harga_formatted }}</p>
                                                    @if ($detail->catatan)
                                                        <p class="text-xs text-gray-400 mt-1">{{ $detail->catatan }}</p>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif
                            <div class="mt-3 pt-3 border-t border-gray-200 flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-700">Total Semua Hotel</span>
                                <span
                                    class="text-lg font-bold text-blue-600">{{ $departure->total_harga_hotel_all_formatted }}</span>
                            </div>
                        @else
                            <div class="text-center py-6">
                                <div
                                    class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-hotel text-gray-300 text-xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm">Belum ada data hotel</p>
                                <p class="text-gray-400 text-xs mt-1">Klik tombol "Atur Hotel" untuk menambahkan hotel</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- HOTEL TOUR --}}
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-umbrella-beach text-yellow-500 mr-2"></i> Hotel Tour (Paket Tour)
                            <span
                                class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">{{ $departure->departurePaketTourHotels->count() }}
                                hotel</span>
                        </h6>
                        <div class="flex items-center gap-2">
                            @if ($departure->produk && $departure->produk->include_tur && $departure->produk->paketTour)
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700"><i
                                        class="fas fa-check mr-1"></i> Include Tour</span>
                                <button onclick="openPaketTourHotelModal()"
                                    class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs rounded-lg hover:bg-yellow-600 transition"><i
                                        class="fas fa-plus mr-1"></i> Atur Hotel Tour</button>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-500"><i
                                        class="fas fa-minus mr-1"></i> Tanpa Tour</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" id="searchHotelTour" placeholder="Cari hotel tour..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                        </div>
                    </div>
                    @if ($departure->produk && $departure->produk->include_tur && $departure->produk->paketTour)
                        @php $tourHotels = $departure->departurePaketTourHotels->sortBy('urutan'); @endphp
                        <div id="hotelTourList">
                            @if ($tourHotels->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto pr-1">
                                    @foreach ($tourHotels as $item)
                                        <div class="bg-white rounded-lg p-3 border border-gray-200 hover:shadow-md transition-shadow duration-200 hotel-tour-item"
                                            data-nama="{{ strtolower($item->hotel->nama_hotel) }}"
                                            data-kota="{{ strtolower($item->hotel->kota ?? '') }}">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2">
                                                        <span
                                                            class="inline-flex items-center justify-center w-6 h-6 bg-yellow-100 text-yellow-600 text-xs font-bold rounded-full flex-shrink-0">{{ $loop->iteration }}</span>
                                                        <div>
                                                            <p class="text-sm font-semibold text-gray-800 truncate">
                                                                {{ $item->hotel->nama_hotel }}</p>
                                                            <p class="text-xs text-gray-400">
                                                                {{ $item->hotel->kota ?? '-' }}@if ($item->tipe_kamar)
                                                                    · {{ $item->tipe_kamar }}
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-right flex-shrink-0 ml-2">
                                                    <p class="text-sm font-bold text-yellow-600">
                                                        {{ $item->total_harga_formatted }}</p>
                                                    <p class="text-xs text-gray-400">{{ $item->jumlah_kamar }} kamar ×
                                                        {{ $item->harga_per_malam_formatted }} ×
                                                        {{ $item->durasi_menginap }} malam</p>
                                                </div>
                                            </div>
                                            @if ($item->catatan)
                                                <p class="text-xs text-gray-400 mt-1">{{ $item->catatan }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-3 pt-3 border-t border-gray-200 flex items-center justify-between">
                                    <span class="text-sm font-semibold text-gray-700">Total Hotel Tour</span>
                                    <span
                                        class="text-lg font-bold text-blue-600">{{ $departure->total_harga_paket_tour_hotel_formatted }}</span>
                                </div>
                            @else
                                <div class="text-center py-6">
                                    <div
                                        class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-umbrella-beach text-gray-300 text-xl"></i>
                                    </div>
                                    <p class="text-gray-500 text-sm">Belum ada hotel tour yang diatur</p>
                                    <p class="text-gray-400 text-xs mt-1">Klik tombol "Atur Hotel Tour" untuk mengatur
                                        hotel tour</p>
                                </div>
                            @endif
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <div class="grid grid-cols-2 gap-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Paket Tour</span>
                                    <span
                                        class="font-medium text-gray-700">{{ $departure->produk->paketTour->kota_tujuan ?? '-' }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Durasi Tour</span>
                                    <span
                                        class="font-medium text-gray-700">{{ $departure->produk->paketTour->durasi_hari ?? 0 }}
                                        Hari</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-umbrella-beach text-gray-300 text-xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm">Produk ini tidak termasuk tour</p>
                            <p class="text-gray-400 text-xs mt-1">Hotel tour hanya tersedia untuk produk dengan include
                                tour</p>
                        </div>
                    @endif
                </div>

                {{-- PERLENGKAPAN --}}
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-box text-yellow-500 mr-2"></i> Perlengkapan Departure
                            <span
                                class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">{{ $departure->departurePerlengkapan->count() }}
                                item</span>
                            @php
                                $totalItems = $departure->total_perlengkapan_item;
                                $totalReceived = $departure->total_perlengkapan_diterima;
                            @endphp
                            <span
                                class="ml-2 text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">{{ $totalReceived }}
                                / {{ $totalItems }} diterima</span>
                        </h6>
                        <div class="flex items-center gap-2">
                            {!! $departure->perlengkapan_status_badge !!}
                            <button onclick="openPerlengkapanModal()"
                                class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs rounded-lg hover:bg-yellow-600 transition">
                                <i class="fas fa-plus mr-1"></i> Atur Perlengkapan
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" id="searchPerlengkapan" placeholder="Cari perlengkapan..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                        </div>
                    </div>
                    @php
                        $perlengkapanList = $departure->departurePerlengkapan;
                        $totalJamaah = $departure->jamaahs->count();
                    @endphp
                    <div id="perlengkapanList">
                        @if ($perlengkapanList->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto pr-1">
                                @foreach ($perlengkapanList as $perlengkapan)
                                    @php
                                        $totalPerItem = $perlengkapan->perlengkapanJamaahs->count();
                                        $sudahTerima = $perlengkapan->perlengkapanJamaahs
                                            ->where('status_terima', 'Sudah Diterima')
                                            ->count();
                                        $belumTerima = $totalPerItem - $sudahTerima;
                                        $progressPercent =
                                            $totalPerItem > 0 ? round(($sudahTerima / $totalPerItem) * 100) : 0;
                                        $totalDiterima = $perlengkapan->perlengkapanJamaahs
                                            ->where('status_terima', 'Sudah Diterima')
                                            ->sum('total_harga');
                                    @endphp
                                    <div class="bg-white rounded-lg p-3 border border-gray-200 hover:shadow-md transition-shadow duration-200 perlengkapan-item"
                                        data-nama="{{ strtolower($perlengkapan->perlengkapan->nama_perlengkapan) }}">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <div
                                                        class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                        <i class="fas fa-box text-yellow-600 text-xs"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-800 truncate">
                                                            {{ $perlengkapan->perlengkapan->nama_perlengkapan }}</p>
                                                        <p class="text-xs text-gray-400">
                                                            {{ $perlengkapan->jumlah_per_jamaah }} per jamaah ·
                                                            {{ $perlengkapan->harga_satuan_formatted }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-1 ml-2 flex-shrink-0">
                                                <button type="button"
                                                    onclick="openPerlengkapanDetailModal({{ $perlengkapan->id }})"
                                                    class="p-1 text-blue-500 hover:bg-blue-50 rounded transition"
                                                    title="Lihat Detail"><i class="fas fa-eye text-xs"></i></button>
                                                <button type="button"
                                                    onclick="removePerlengkapan({{ $departure->id_departure }}, {{ $perlengkapan->id }})"
                                                    class="p-1 text-red-500 hover:bg-red-50 rounded transition"
                                                    title="Hapus"><i class="fas fa-trash text-xs"></i></button>
                                                <form
                                                    id="remove-perlengkapan-form-{{ $departure->id_departure }}-{{ $perlengkapan->id }}"
                                                    action="{{ route('transaksional.departure.remove-perlengkapan', [$departure->id_departure, $perlengkapan->id]) }}"
                                                    method="POST" class="hidden">
                                                    @csrf @method('DELETE')
                                                </form>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <div class="flex items-center justify-between text-xs">
                                                <span class="text-gray-500">Progress penerimaan</span>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-green-600">{{ $sudahTerima }} diterima</span>
                                                    @if ($belumTerima > 0)
                                                        <span class="text-yellow-600">{{ $belumTerima }} belum</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                                <div class="h-1.5 rounded-full transition-all duration-500 {{ $progressPercent >= 100 ? 'bg-green-500' : 'bg-yellow-500' }}"
                                                    style="width: {{ $progressPercent }}%"></div>
                                            </div>
                                        </div>
                                        <div class="mt-2 pt-2 border-t border-gray-100">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="text-xs text-gray-500">Total Diterima</span>
                                                    <p class="text-[10px] text-gray-400">{{ $sudahTerima }} dari
                                                        {{ $totalPerItem }} item</p>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-sm font-bold text-purple-600">Rp
                                                        {{ number_format($totalDiterima, 0, ',', '.') }}</span>
                                                    <p class="text-[10px] text-gray-400">dari Rp
                                                        {{ number_format($perlengkapan->total_harga, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-sm font-semibold text-gray-700">Total Perlengkapan
                                            Diterima</span>
                                        <p class="text-xs text-gray-400 mt-0.5"><i
                                                class="fas fa-info-circle mr-1"></i>Hanya dihitung dari perlengkapan yang
                                            <strong>sudah diterima</strong> jamaah
                                        </p>
                                    </div>
                                    <span
                                        class="text-lg font-bold text-purple-600">{{ $departure->total_harga_perlengkapan_formatted }}</span>
                                </div>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-xs text-gray-400">Total semua perlengkapan (target):</span>
                                    <span
                                        class="text-xs text-gray-500 font-medium">{{ $departure->total_harga_perlengkapan_all_formatted }}</span>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div
                                    class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-box text-gray-300 text-xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm">Belum ada perlengkapan yang ditambahkan</p>
                                <p class="text-gray-400 text-xs mt-1">Klik tombol "Atur Perlengkapan" untuk menambahkan
                                    perlengkapan</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- SECTION BARU: UPGRADE HOTEL & HARGA ASLI --}}
                {{-- ============================================ --}}
                @php
                    $jamaahWithUpgradePersonal = $departure->jamaahs->filter(fn($j) => $j->upgradeHotels->count() > 0);
                    $keluargaWithUpgrade = collect();
                    $processedKeluargaIds = [];
                    foreach ($departure->jamaahs as $j) {
                        if (!$j->id_keluarga) {
                            continue;
                        }
                        if (in_array($j->id_keluarga, $processedKeluargaIds)) {
                            continue;
                        }
                        $keluarga = $j->keluarga;
                        if ($keluarga && $keluarga->upgradeHotels->count() > 0) {
                            $keluargaWithUpgrade->push($keluarga);
                            $processedKeluargaIds[] = $j->id_keluarga;
                        }
                    }
                    $totalUpgradePersonal = $departure->total_upgrade_hotel_personal;
                    $totalUpgradeKeluarga = $departure->total_upgrade_hotel_keluarga;
                    $totalUpgradeGabungan = $departure->total_upgrade_hotel_gabungan;
                    $hargaAsli = $departure->harga_asli_upgrade_total ?? 0;
                    $selisih = $departure->selisih_upgrade_hotel;
                    $selisihStatus = $departure->selisih_upgrade_hotel_status;
                    $hasUpgrade = $totalUpgradeGabungan > 0;
                @endphp

                @if ($hasUpgrade)
                    <div class="bg-orange-50 rounded-xl p-5 border-2 border-orange-300 mb-6">
                        <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                            <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-hotel text-orange-500 mr-2"></i> Upgrade Hotel & Harga Asli
                                <span
                                    class="ml-2 text-xs bg-orange-100 text-orange-800 px-2 py-0.5 rounded-full">{{ $jamaahWithUpgradePersonal->count() + $keluargaWithUpgrade->count() }}
                                    sumber</span>
                            </h6>
                            <button onclick="openHargaAsliModal()"
                                class="inline-flex items-center px-3 py-1.5 bg-orange-500 text-white text-xs rounded-lg hover:bg-orange-600 transition">
                                <i class="fas fa-edit mr-1"></i> Input Harga Asli (Modal)
                            </button>
                        </div>

                        {{-- Info bantuan --}}
                        <div class="mb-4 bg-white rounded-lg p-3 border border-orange-200">
                            <p class="text-xs text-gray-600">
                                <i class="fas fa-info-circle text-orange-500 mr-1"></i>
                                <strong>Total Upgrade</strong> = yang <strong>dibayar jamaah/keluarga</strong> ke
                                perusahaan.
                                <strong>Harga Asli (Modal)</strong> = harga <strong>modal</strong> upgrade (dibayar ke
                                hotel).
                                <br>
                                <i class="fas fa-arrow-right text-green-500 mr-1"></i>
                                Jika <strong>Dibayar &gt; Modal</strong> → <span
                                    class="text-green-600 font-semibold">PROFIT</span> (masuk pendapatan).
                                <br>
                                <i class="fas fa-arrow-right text-red-500 mr-1"></i>
                                Jika <strong>Dibayar &lt; Modal</strong> → <span
                                    class="text-red-600 font-semibold">MINUS</span> (masuk pengeluaran / nombok).
                            </p>
                        </div>

                        {{-- Ringkasan --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                            <div class="bg-white rounded-lg p-3 border border-orange-200 text-center">
                                <p class="text-xs text-gray-500">Upgrade Personal</p>
                                <p class="text-sm font-bold text-orange-600">Rp
                                    {{ number_format($totalUpgradePersonal, 0, ',', '.') }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Dibayar jamaah</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-purple-200 text-center">
                                <p class="text-xs text-gray-500">Share Keluarga</p>
                                <p class="text-sm font-bold text-purple-600">Rp
                                    {{ number_format($totalUpgradeKeluarga, 0, ',', '.') }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Dibayar keluarga</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-blue-200 text-center">
                                <p class="text-xs text-gray-500">Total Upgrade</p>
                                <p class="text-sm font-bold text-blue-600">
                                    {{ $departure->total_upgrade_hotel_gabungan_formatted }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Total dibayar jamaah</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-gray-300 text-center">
                                <p class="text-xs text-gray-500">Harga Asli (Modal)</p>
                                <p class="text-sm font-bold text-gray-700">
                                    {{ $departure->harga_asli_upgrade_total_formatted }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Dibayar ke hotel</p>
                            </div>
                        </div>

                        {{-- Selisih --}}
                        <div
                            class="mb-4 rounded-lg p-4 border-2 {{ $selisihStatus['status'] === 'profit' ? 'bg-green-50 border-green-400' : ($selisihStatus['status'] === 'minus' ? 'bg-red-50 border-red-400' : 'bg-gray-50 border-gray-300') }}">
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <div>
                                    <p
                                        class="text-sm font-bold flex items-center gap-2 {{ $selisihStatus['status'] === 'profit' ? 'text-green-800' : ($selisihStatus['status'] === 'minus' ? 'text-red-800' : 'text-gray-700') }}">
                                        <i
                                            class="fas {{ $selisihStatus['status'] === 'profit' ? 'fa-arrow-up' : ($selisihStatus['status'] === 'minus' ? 'fa-arrow-down' : 'fa-equals') }}"></i>
                                        Selisih: {{ $selisihStatus['label'] }}
                                    </p>
                                    <p
                                        class="text-xs mt-0.5 {{ $selisihStatus['status'] === 'profit' ? 'text-green-600' : ($selisihStatus['status'] === 'minus' ? 'text-red-600' : 'text-gray-500') }}">
                                        @if ($selisih > 0)
                                            Dibayar jamaah lebih besar dari modal → <strong>masuk pendapatan</strong>
                                        @elseif ($selisih < 0)
                                            Dibayar jamaah lebih kecil dari modal → <strong>masuk pengeluaran (perusahaan
                                                nombok)</strong>
                                        @else
                                            Dibayar jamaah sama dengan modal
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-extrabold {{ $selisihStatus['color'] }}">
                                        {{ $departure->selisih_upgrade_hotel_formatted }}</p>
                                    <p class="text-[10px] text-gray-500">Dibayar − Modal</p>
                                </div>
                            </div>
                        </div>

                        {{-- Detail Upgrade Personal --}}
                        @if ($jamaahWithUpgradePersonal->count() > 0)
                            <div class="mb-3">
                                <h6 class="text-xs font-semibold text-gray-600 mb-2 flex items-center">
                                    <i class="fas fa-user mr-1 text-orange-500"></i> Upgrade Personal Jamaah
                                </h6>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm bg-white rounded-lg overflow-hidden">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">
                                                    Nama Jamaah</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">
                                                    Hotel</th>
                                                <th
                                                    class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">
                                                    Tipe Kamar</th>
                                                <th
                                                    class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase">
                                                    Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach ($jamaahWithUpgradePersonal as $jamaah)
                                                @foreach ($jamaah->upgradeHotels as $upgrade)
                                                    <tr class="hover:bg-gray-50">
                                                        @if ($loop->first)
                                                            <td class="px-3 py-2 font-medium text-gray-700"
                                                                rowspan="{{ $jamaah->upgradeHotels->count() }}">
                                                                {{ $jamaah->nama_lengkap }}</td>
                                                        @endif
                                                        <td class="px-3 py-2">
                                                            {{ $upgrade->hotel->nama_hotel ?? '-' }}
                                                            <p class="text-xs text-gray-400">
                                                                {{ $upgrade->hotel->kota ?? '' }}</p>
                                                        </td>
                                                        <td class="px-3 py-2 text-gray-600">
                                                            {{ $upgrade->tipe_kamar ?? '-' }}</td>
                                                        <td class="px-3 py-2 text-right font-bold text-orange-600">Rp
                                                            {{ number_format($upgrade->total_harga, 0, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-orange-100 border-t-2 border-orange-300">
                                            <tr>
                                                <td colspan="3" class="px-3 py-2 text-right font-bold text-gray-700">
                                                    TOTAL PERSONAL (dibayar jamaah)</td>
                                                <td class="px-3 py-2 text-right font-bold text-orange-700">Rp
                                                    {{ number_format($totalUpgradePersonal, 0, ',', '.') }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        @endif

                        {{-- Detail Upgrade Keluarga --}}
                        @if ($keluargaWithUpgrade->count() > 0)
                            <div class="mt-3 pt-3 border-t border-orange-200">
                                <h6 class="text-xs font-semibold text-gray-600 mb-2 flex items-center">
                                    <i class="fas fa-users mr-1 text-purple-500"></i> Upgrade Hotel Keluarga (Share)
                                </h6>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($keluargaWithUpgrade as $keluarga)
                                        @php
                                            $jumlahKeluarga = $keluarga->jamaahs->count();
                                            $sharePerOrang =
                                                $jumlahKeluarga > 0
                                                    ? (int) round(
                                                        $keluarga->upgradeHotels->sum('total_harga') / $jumlahKeluarga,
                                                    )
                                                    : 0;
                                            $jamaahKeluargaDiDeparture = $departure->jamaahs->where(
                                                'id_keluarga',
                                                $keluarga->id_keluarga,
                                            );
                                            $shareTotalDeparture = $sharePerOrang * $jamaahKeluargaDiDeparture->count();
                                        @endphp
                                        <div class="bg-white rounded-lg p-3 border border-purple-200">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="font-semibold text-gray-700 text-sm">
                                                    <i class="fas fa-users text-purple-500 mr-1"></i>
                                                    {{ $keluarga->nama_keluarga }}
                                                </span>
                                                <span class="text-xs font-bold text-purple-600">Rp
                                                    {{ number_format($shareTotalDeparture, 0, ',', '.') }}</span>
                                            </div>
                                            <ul class="space-y-1 text-xs">
                                                @foreach ($keluarga->upgradeHotels as $upgrade)
                                                    <li class="flex justify-between text-gray-600">
                                                        <span>{{ $upgrade->hotel->nama_hotel ?? '-' }} -
                                                            {{ $upgrade->tipe_kamar }}</span>
                                                        <span class="font-medium text-orange-600">Rp
                                                            {{ number_format($upgrade->total_harga, 0, ',', '.') }}</span>
                                                    </li>
                                                @endforeach
                                                <li
                                                    class="flex justify-between text-purple-700 border-t border-purple-200 pt-1 mt-1">
                                                    <span>Share per orang:</span>
                                                    <span class="font-bold">Rp
                                                        {{ number_format($sharePerOrang, 0, ',', '.') }}</span>
                                                </li>
                                                <li class="flex justify-between text-purple-700">
                                                    <span>Jamaah di departure: {{ $jamaahKeluargaDiDeparture->count() }}
                                                        orang</span>
                                                    <span class="font-bold">Rp
                                                        {{ number_format($shareTotalDeparture, 0, ',', '.') }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- ============================================ --}}
                {{-- SECTION BARU: PEMBAYARAN TAK TERDUGA --}}
                {{-- ============================================ --}}
                <div class="bg-red-50 rounded-xl p-5 border-2 border-red-300 mb-6">
                    <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                        <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i> Pembayaran Tak Terduga
                            <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full">
                                {{ $departure->pembayaranTakTerduga->count() }} item
                            </span>
                        </h6>
                        <button onclick="openPembayaranTakTerdugaModal()"
                            class="inline-flex items-center px-3 py-1.5 bg-red-500 text-white text-xs rounded-lg hover:bg-red-600 transition">
                            <i class="fas fa-plus mr-1"></i> Tambah Pembayaran
                        </button>
                    </div>

                    <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200 mb-4">
                        <p class="text-xs text-yellow-700">
                            <i class="fas fa-info-circle mr-1"></i>
                            Pembayaran ini akan <strong>masuk ke PENGELUARAN</strong> departure.
                            Gunakan untuk biaya tak terduga seperti denda, biaya tambahan, atau pengeluaran lainnya.
                        </p>
                    </div>

                    @if ($departure->pembayaranTakTerduga->count() > 0)
                        <div class="overflow-x-auto mb-3">
                            <table class="w-full text-sm bg-white rounded-lg overflow-hidden">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">#
                                        </th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Nama
                                            Pembayaran</th>
                                        <th class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase">
                                            Total Pembayaran</th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">
                                            Keterangan</th>
                                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($departure->pembayaranTakTerduga as $idx => $ptt)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-3 py-2 text-gray-500">{{ $idx + 1 }}</td>
                                            <td class="px-3 py-2 font-medium text-gray-700">{{ $ptt->nama_pembayaran }}
                                            </td>
                                            <td class="px-3 py-2 text-right font-bold text-red-600">
                                                {{ $ptt->total_pembayaran_formatted }}</td>
                                            <td class="px-3 py-2 text-gray-600 text-xs">{{ $ptt->keterangan ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                <button
                                                    onclick="editPembayaranTakTerduga({{ $ptt->id_pembayaran_tak_terduga }}, '{{ addslashes($ptt->nama_pembayaran) }}', {{ $ptt->total_pembayaran }}, '{{ addslashes($ptt->keterangan ?? '') }}')"
                                                    class="p-1 text-blue-500 hover:bg-blue-50 rounded transition"
                                                    title="Edit">
                                                    <i class="fas fa-edit text-xs"></i>
                                                </button>
                                                <button type="button"
                                                    onclick="removePembayaranTakTerduga({{ $departure->id_departure }}, {{ $ptt->id_pembayaran_tak_terduga }})"
                                                    class="p-1 text-red-500 hover:bg-red-50 rounded transition"
                                                    title="Hapus">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                                <form
                                                    id="remove-ptt-form-{{ $departure->id_departure }}-{{ $ptt->id_pembayaran_tak_terduga }}"
                                                    action="{{ route('transaksional.departure.remove-pembayaran-tak-terduga', [$departure->id_departure, $ptt->id_pembayaran_tak_terduga]) }}"
                                                    method="POST" class="hidden">
                                                    @csrf @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-red-100 border-t-2 border-red-300">
                                    <tr>
                                        <td colspan="2" class="px-3 py-2 text-right font-bold text-gray-700">TOTAL
                                            PEMBAYARAN TAK TERDUGA</td>
                                        <td class="px-3 py-2 text-right font-bold text-red-700">
                                            {{ $departure->total_pembayaran_tak_terduga_formatted }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-exclamation-triangle text-gray-300 text-xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm">Belum ada pembayaran tak terduga</p>
                            <p class="text-gray-400 text-xs mt-1">Klik tombol "Tambah Pembayaran" untuk menambahkan</p>
                        </div>
                    @endif
                </div>

                {{-- JENIS TRANSAKSI --}}
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-receipt text-yellow-500 mr-2"></i> Jenis Transaksi
                            <span
                                class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">{{ $departure->departureJenisTransaksis->count() }}
                                item</span>
                            @php
                                $totalJtItems = $departure->total_jenis_transaksi_item;
                                $totalJtReceived = $departure->total_jenis_transaksi_diterima;
                            @endphp
                            <span
                                class="ml-2 text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">{{ $totalJtReceived }}
                                / {{ $totalJtItems }} diterima</span>
                        </h6>
                        <div class="flex items-center gap-2">
                            <button onclick="openJenisTransaksiModal()"
                                class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs rounded-lg hover:bg-yellow-600 transition">
                                <i class="fas fa-plus mr-1"></i> Atur Jenis Transaksi
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" id="searchJenisTransaksi" placeholder="Cari jenis transaksi..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                        </div>
                    </div>
                    @php
                        $jenisTransaksiList = $departure->departureJenisTransaksis;
                        $totalJamaah = $departure->jamaahs->count();
                    @endphp
                    <div id="jenisTransaksiList">
                        @if ($jenisTransaksiList->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto pr-1">
                                @foreach ($jenisTransaksiList as $item)
                                    @php
                                        $totalPerItem = $item->departureJenisTransaksiJamaahs->count();
                                        $sudahTerima = $item->departureJenisTransaksiJamaahs
                                            ->where('status_terima', 'Sudah Diterima')
                                            ->count();
                                        $belumTerima = $totalPerItem - $sudahTerima;
                                        $progressPercent =
                                            $totalPerItem > 0 ? round(($sudahTerima / $totalPerItem) * 100) : 0;
                                        $totalDiterima = $item->departureJenisTransaksiJamaahs
                                            ->where('status_terima', 'Sudah Diterima')
                                            ->sum('total_harga');
                                    @endphp
                                    <div class="bg-white rounded-lg p-3 border border-gray-200 hover:shadow-md transition-shadow duration-200 jenis-transaksi-item"
                                        data-nama="{{ strtolower($item->jenisTransaksi->nama) }}">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <div
                                                        class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                        <i class="fas fa-tag text-purple-600 text-xs"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-800 truncate">
                                                            {{ $item->jenisTransaksi->nama }}</p>
                                                        <p class="text-xs text-gray-400">Total:
                                                            {{ $item->total_harga_formatted }} · Rp
                                                            {{ number_format($item->harga_satuan, 0, ',', '.') }} / jamaah
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-1 ml-2 flex-shrink-0">
                                                <button type="button"
                                                    onclick="openJenisTransaksiDetailModal({{ $item->id }})"
                                                    class="p-1 text-blue-500 hover:bg-blue-50 rounded transition"
                                                    title="Lihat Detail"><i class="fas fa-eye text-xs"></i></button>
                                                <button type="button"
                                                    onclick="editJenisTransaksiHarga({{ $departure->id_departure }}, {{ $item->id_jenis_transaksi }}, '{{ $item->total_harga }}')"
                                                    class="p-1 text-blue-500 hover:bg-blue-50 rounded transition"
                                                    title="Edit Harga Total"><i class="fas fa-edit text-xs"></i></button>
                                                <button type="button"
                                                    onclick="removeJenisTransaksi({{ $departure->id_departure }}, {{ $item->id_jenis_transaksi }})"
                                                    class="p-1 text-red-500 hover:bg-red-50 rounded transition"
                                                    title="Hapus"><i class="fas fa-trash text-xs"></i></button>
                                                <form
                                                    id="remove-jenis-transaksi-form-{{ $departure->id_departure }}-{{ $item->id_jenis_transaksi }}"
                                                    action="{{ route('transaksional.departure.remove-jenis-transaksi', [$departure->id_departure, $item->id_jenis_transaksi]) }}"
                                                    method="POST" class="hidden">
                                                    @csrf @method('DELETE')
                                                </form>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <div class="flex items-center justify-between text-xs">
                                                <span class="text-gray-500">Progress penerimaan</span>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-green-600">{{ $sudahTerima }} diterima</span>
                                                    @if ($belumTerima > 0)
                                                        <span class="text-yellow-600">{{ $belumTerima }} belum</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                                <div class="h-1.5 rounded-full transition-all duration-500 {{ $progressPercent >= 100 ? 'bg-green-500' : 'bg-yellow-500' }}"
                                                    style="width: {{ $progressPercent }}%"></div>
                                            </div>
                                        </div>
                                        <div class="mt-2 pt-2 border-t border-gray-100">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="text-xs text-gray-500">Total Diterima</span>
                                                    <p class="text-[10px] text-gray-400">{{ $sudahTerima }} dari
                                                        {{ $totalPerItem }} item</p>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-sm font-bold text-purple-600">Rp
                                                        {{ number_format($totalDiterima, 0, ',', '.') }}</span>
                                                    <p class="text-[10px] text-gray-400">dari Rp
                                                        {{ number_format($item->total_harga, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($item->catatan)
                                            <p class="text-xs text-gray-400 mt-1">{{ $item->catatan }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-sm font-semibold text-gray-700">Total Jenis Transaksi
                                            Diterima</span>
                                        <p class="text-xs text-gray-400 mt-0.5"><i
                                                class="fas fa-info-circle mr-1"></i>Hanya dihitung dari jenis transaksi
                                            yang <strong>sudah diterima</strong> jamaah</p>
                                    </div>
                                    <span
                                        class="text-lg font-bold text-purple-600">{{ $departure->total_jenis_transaksi_formatted }}</span>
                                </div>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-xs text-gray-400">Total semua jenis transaksi (target):</span>
                                    <span
                                        class="text-xs text-gray-500 font-medium">{{ $departure->total_jenis_transaksi_all_formatted }}</span>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div
                                    class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-receipt text-gray-300 text-xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm">Belum ada jenis transaksi yang ditambahkan</p>
                                <p class="text-gray-400 text-xs mt-1">Klik tombol "Atur Jenis Transaksi" untuk menambahkan
                                    jenis transaksi</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- AGENT & JAMAAH --}}
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-user-tie text-purple-500 mr-2"></i> Agent & Jamaah
                            <span
                                class="ml-2 text-xs bg-purple-100 text-purple-800 px-2 py-0.5 rounded-full">{{ $departure->jamaahs->filter(function ($jamaah) {return !empty($jamaah->agent_name);})->count() }}
                                dengan Agent</span>
                            <span class="ml-2 text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">Total
                                {{ $departure->jamaahs->count() }} Jamaah</span>
                        </h6>
                    </div>
                    <div class="mb-3">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" id="searchAgent" placeholder="Cari agent atau jamaah..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                        </div>
                    </div>
                    @php
                        $jamaahsWithAgent = $departure->jamaahs->filter(
                            fn($jamaah) => !empty($jamaah->agent_name) && trim($jamaah->agent_name) !== '',
                        );
                        $groupedByAgent = $jamaahsWithAgent->groupBy(
                            fn($jamaah) => $jamaah->agent_name ?? 'Agent Tidak Diketahui',
                        );
                        $jamaahsWithoutAgent = $departure->jamaahs->filter(
                            fn($jamaah) => empty($jamaah->agent_name) || trim($jamaah->agent_name) === '',
                        );
                    @endphp
                    <div id="agentList">
                        @if ($jamaahsWithAgent->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                                <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                    <p class="text-xs text-gray-500">Total Agent</p>
                                    <p class="text-lg font-bold text-purple-600">{{ $groupedByAgent->count() }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                    <p class="text-xs text-gray-500">Dengan Agent</p>
                                    <p class="text-lg font-bold text-blue-600">{{ $jamaahsWithAgent->count() }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                    <p class="text-xs text-gray-500">Tanpa Agent</p>
                                    <p class="text-lg font-bold text-orange-500">{{ $jamaahsWithoutAgent->count() }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                    <p class="text-xs text-gray-500">Total Jamaah</p>
                                    <p class="text-lg font-bold text-gray-700">{{ $departure->jamaahs->count() }}</p>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm border border-gray-200 rounded-lg">
                                    <thead class="bg-purple-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-purple-700">Agent
                                            </th>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-purple-700">Nama
                                                Jamaah</th>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-purple-700">Produk
                                            </th>
                                            <th class="px-4 py-2 text-center text-xs font-semibold text-purple-700">Status
                                                Pembayaran</th>
                                            <th class="px-4 py-2 text-right text-xs font-semibold text-purple-700">Fee
                                                Agent</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($groupedByAgent as $agentName => $jamaahs)
                                            @foreach ($jamaahs as $jamaah)
                                                <tr class="hover:bg-gray-50 transition-colors agent-row"
                                                    data-agent="{{ strtolower($agentName) }}"
                                                    data-name="{{ strtolower($jamaah->nama_lengkap) }}">
                                                    <td class="px-4 py-2.5">
                                                        @if ($loop->first)
                                                            <span
                                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs font-medium">
                                                                <i
                                                                    class="fas fa-user-tie text-purple-500"></i>{{ $agentName }}
                                                            </span>
                                                            <span
                                                                class="text-xs text-gray-400 ml-1">({{ $jamaahs->count() }}
                                                                jamaah)</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-2.5 font-medium text-gray-800">
                                                        {{ $jamaah->nama_lengkap }}</td>
                                                    <td class="px-4 py-2.5 text-gray-600">{{ $jamaah->produk_paket }}
                                                    </td>
                                                    <td class="px-4 py-2.5 text-center">
                                                        <span
                                                            class="px-2 py-0.5 rounded-full text-xs font-medium {{ $jamaah->status_pembayaran == 'Lunas' ? 'bg-green-100 text-green-700' : ($jamaah->status_pembayaran == 'DP' ? 'bg-yellow-100 text-yellow-700' : ($jamaah->status_pembayaran == 'Setoran' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700')) }}">{{ $jamaah->status_pembayaran }}</span>
                                                    </td>
                                                    <td class="px-4 py-2.5 text-right font-medium text-purple-600">
                                                        @if ($loop->first)
                                                            Rp {{ number_format($jamaah->fee_agent ?? 0, 0, ',', '.') }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr class="bg-purple-50/50 border-t border-purple-200">
                                                <td colspan="4"
                                                    class="px-4 py-2 text-right text-sm font-medium text-purple-700">Total
                                                    Fee Agent {{ $agentName }}</td>
                                                <td class="px-4 py-2 text-right font-bold text-purple-700">Rp
                                                    {{ number_format($jamaahs->sum('fee_agent'), 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-purple-100 border-t-2 border-purple-300">
                                        <tr>
                                            <td colspan="4" class="px-4 py-3 text-right font-semibold text-gray-700"><i
                                                    class="fas fa-calculator mr-2 text-purple-500"></i>Total Keseluruhan
                                                Fee Agent</td>
                                            <td class="px-4 py-3 text-right font-bold text-purple-700 text-lg">Rp
                                                {{ number_format($jamaahsWithAgent->sum('fee_agent'), 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            @if ($jamaahsWithoutAgent->count() > 0)
                                <div class="mt-4">
                                    <p class="text-sm font-medium text-gray-600 mb-2 flex items-center gap-2">
                                        <i class="fas fa-user text-gray-400"></i> Jamaah Tanpa Agent
                                        <span
                                            class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full">{{ $jamaahsWithoutAgent->count() }}
                                            jamaah</span>
                                    </p>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-sm border border-gray-200 rounded-lg">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">
                                                        Nama Jamaah</th>
                                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">
                                                        Produk</th>
                                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500">
                                                        Status Pembayaran</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100">
                                                @foreach ($jamaahsWithoutAgent as $jamaah)
                                                    <tr class="hover:bg-gray-50 transition-colors no-agent-row"
                                                        data-name="{{ strtolower($jamaah->nama_lengkap) }}">
                                                        <td class="px-4 py-2.5 font-medium text-gray-800">
                                                            {{ $jamaah->nama_lengkap }}</td>
                                                        <td class="px-4 py-2.5 text-gray-600">{{ $jamaah->produk_paket }}
                                                        </td>
                                                        <td class="px-4 py-2.5 text-center">
                                                            <span
                                                                class="px-2 py-0.5 rounded-full text-xs font-medium {{ $jamaah->status_pembayaran == 'Lunas' ? 'bg-green-100 text-green-700' : ($jamaah->status_pembayaran == 'DP' ? 'bg-yellow-100 text-yellow-700' : ($jamaah->status_pembayaran == 'Setoran' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700')) }}">{{ $jamaah->status_pembayaran }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8">
                                <div
                                    class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-user-tie text-gray-300 text-2xl"></i>
                                </div>
                                <p class="text-gray-500 font-medium">Belum ada jamaah yang memiliki agent</p>
                                <p class="text-gray-400 text-sm mt-1">Semua jamaah terdaftar tanpa agent</p>
                                @if ($departure->jamaahs->count() > 0)
                                    <p class="text-xs text-gray-400 mt-2">Total jamaah terdaftar:
                                        <strong>{{ $departure->jamaahs->count() }}</strong> orang
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- CATATAN --}}
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h6 class="text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-sticky-note text-yellow-500 mr-2"></i> Catatan
                        </h6>
                        <div class="flex items-center gap-2">
                            {!! $departure->catatan_status_badge !!}
                            <button onclick="openCatatanModal()"
                                class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs rounded-lg hover:bg-yellow-600 transition">
                                <i class="fas fa-plus mr-1"></i> Tambah
                            </button>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-gray-200 min-h-[60px]">
                        <p class="text-sm text-gray-600">{{ $departure->catatan ?? '-' }}</p>
                    </div>
                </div>

                {{-- 4. RINCIAN PENGELUARAN --}}
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-list text-red-500 mr-2"></i> Rincian Pengeluaran
                    </h6>
                    @php
                        $totalFeeAgent = $departure->jamaahs->sum('fee_agent');
                        $totalPembayaranPassport = $departure->total_pembayaran_passport;
                    @endphp
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                        <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                            <p class="text-xs text-gray-500">Maskapai</p>
                            <p class="text-sm font-bold text-gray-700">{{ $departure->total_harga_maskapai_formatted }}
                            </p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                            <p class="text-xs text-gray-500">Hotel</p>
                            <p class="text-sm font-bold text-gray-700">{{ $departure->total_harga_hotel_all_formatted }}
                            </p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                            <p class="text-xs text-gray-500">Hotel Tour</p>
                            <p class="text-sm font-bold text-gray-700">
                                {{ $departure->total_harga_paket_tour_hotel_formatted }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                            <p class="text-xs text-gray-500">Perlengkapan</p>
                            <p class="text-sm font-bold text-purple-600">
                                {{ $departure->total_harga_perlengkapan_formatted }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                            <p class="text-xs text-gray-500">Jenis Transaksi</p>
                            <p class="text-sm font-bold text-gray-700">{{ $departure->total_jenis_transaksi_formatted }}
                            </p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-red-200 text-center">
                            <p class="text-xs text-gray-500">Pembayaran Passport</p>
                            <p class="text-sm font-bold text-red-600">Rp
                                {{ number_format($totalPembayaranPassport, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $jamaahDenganPassport }} jamaah efektif YA</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                            <p class="text-xs text-gray-500">Fee Agent</p>
                            <p class="text-sm font-bold text-purple-600">Rp
                                {{ number_format($totalFeeAgent, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-red-200 text-center">
                            <p class="text-xs text-gray-500">Pembayaran Tak Terduga</p>
                            <p class="text-sm font-bold text-red-600">
                                {{ $departure->total_pembayaran_tak_terduga_formatted }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $departure->pembayaranTakTerduga->count() }}
                                item</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                            <p class="text-xs text-gray-500">Jumlah Item</p>
                            <p class="text-sm font-bold text-gray-700">
                                {{ $departure->departurePerlengkapan->count() + $departure->departureJenisTransaksis->count() + $departure->departurePaketTourHotels->count() }}
                                item</p>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-red-100 to-orange-100 rounded-lg p-4 border-2 border-red-400">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div>
                                <p class="text-sm font-bold text-red-800"><i class="fas fa-calculator mr-2"></i>TOTAL
                                    KESELURUHAN PENGELUARAN</p>
                                <p class="text-[10px] text-red-600 mt-0.5">Maskapai + Hotel + Hotel Tour + Perlengkapan +
                                    Jenis Transaksi + Pembayaran Passport + Fee Agent + Pembayaran Tak Terduga</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xl md:text-2xl font-extrabold text-red-700">
                                    {{ $departure->total_pengeluaran_formatted }}</p>
                                <p class="text-[10px] text-red-600">Total Pengeluaran Final</p>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-red-300 grid grid-cols-1 md:grid-cols-2 gap-1 text-xs">
                            <div class="flex justify-between"><span class="text-red-700">Maskapai:</span><span
                                    class="font-bold text-red-800">{{ $departure->total_harga_maskapai_formatted }}</span>
                            </div>
                            <div class="flex justify-between"><span class="text-red-700">Hotel:</span><span
                                    class="font-bold text-red-800">{{ $departure->total_harga_hotel_all_formatted }}</span>
                            </div>
                            <div class="flex justify-between"><span class="text-red-700">Hotel Tour:</span><span
                                    class="font-bold text-red-800">{{ $departure->total_harga_paket_tour_hotel_formatted }}</span>
                            </div>
                            <div class="flex justify-between"><span class="text-red-700">Perlengkapan
                                    (diterima):</span><span
                                    class="font-bold text-red-800">{{ $departure->total_harga_perlengkapan_formatted }}</span>
                            </div>
                            <div class="flex justify-between"><span class="text-red-700">Jenis Transaksi
                                    (diterima):</span><span
                                    class="font-bold text-red-800">{{ $departure->total_jenis_transaksi_formatted }}</span>
                            </div>
                            <div class="flex justify-between"><span class="text-red-700">Pembayaran Passport
                                    ({{ $jamaahDenganPassport }} YA efektif):</span><span
                                    class="font-bold text-red-800">Rp
                                    {{ number_format($totalPembayaranPassport, 0, ',', '.') }}</span></div>
                            <div class="flex justify-between"><span class="text-red-700">Fee
                                    Agent:</span><span class="font-bold text-red-800">Rp
                                    {{ number_format($totalFeeAgent, 0, ',', '.') }}</span></div>
                            <div class="flex justify-between"><span class="text-red-700">Pembayaran Tak
                                    Terduga:</span><span
                                    class="font-bold text-red-800">{{ $departure->total_pembayaran_tak_terduga_formatted }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 5. HASIL PERHITUNGAN --}}
                @php
                    $hasilPendapatan = $departure->total_pendapatan_bersih ?? 0;
                    $hasilPengeluaran = $departure->total_pengeluaran ?? 0;
                    $hasilAkhir = $hasilPendapatan - $hasilPengeluaran;
                    $isProfit = $hasilAkhir >= 0;
                    $persentaseHasil = $hasilPendapatan > 0 ? round(($hasilAkhir / $hasilPendapatan) * 100, 2) : 0;
                @endphp
                <div class="mb-6">
                    <div
                        class="rounded-2xl border-4 {{ $isProfit ? 'border-green-500 bg-gradient-to-br from-green-50 via-emerald-50 to-green-100' : 'border-red-500 bg-gradient-to-br from-red-50 via-rose-50 to-red-100' }} overflow-hidden shadow-lg">
                        <div class="{{ $isProfit ? 'bg-green-500' : 'bg-red-500' }} px-6 py-3">
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                                        <i
                                            class="fas {{ $isProfit ? 'fa-trophy' : 'fa-exclamation-triangle' }} text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h5 class="text-white font-bold text-lg">
                                            {{ $isProfit ? 'KEUNTUNGAN' : 'KERUGIAN' }}</h5>
                                        <p class="text-white/80 text-xs">Hasil Perhitungan: Pendapatan Bersih − Total
                                            Pengeluaran</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/20 rounded-full text-white text-xs font-semibold">
                                        <i class="fas {{ $isProfit ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                                        {{ $isProfit ? 'PROFIT' : 'LOSS' }}
                                        {{ number_format(abs($persentaseHasil), 2) }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="text-center mb-5">
                                <p class="text-xs text-gray-500 mb-1">
                                    {{ $isProfit ? 'Total Keuntungan' : 'Total Kerugian' }}</p>
                                <p
                                    class="text-4xl md:text-5xl font-extrabold {{ $isProfit ? 'text-green-600' : 'text-red-600' }} tracking-tight">
                                    {{ $hasilAkhir >= 0 ? '+' : '−' }} Rp
                                    {{ number_format(abs($hasilAkhir), 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $isProfit ? 'Pendapatan melebihi pengeluaran' : 'Pengeluaran melebihi pendapatan' }}
                                </p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div class="bg-white rounded-xl p-4 border-2 border-green-300 text-center">
                                    <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Pendapatan Bersih
                                    </p>
                                    <p class="text-lg font-bold text-green-600">
                                        {{ $departure->total_pendapatan_bersih_formatted }}</p>
                                    <p class="text-[10px] text-gray-400 mt-1"><i
                                            class="fas fa-arrow-down text-green-500 mr-1"></i>Dari Ringkasan Pendapatan</p>
                                </div>
                                <div class="flex items-center justify-center">
                                    <div
                                        class="w-12 h-12 rounded-full {{ $isProfit ? 'bg-green-500' : 'bg-red-500' }} flex items-center justify-center shadow-md">
                                        <i class="fas fa-minus text-white text-xl"></i>
                                    </div>
                                </div>
                                <div class="bg-white rounded-xl p-4 border-2 border-red-300 text-center">
                                    <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Total Pengeluaran
                                    </p>
                                    <p class="text-lg font-bold text-red-600">
                                        {{ $departure->total_pengeluaran_formatted }}</p>
                                    <p class="text-[10px] text-gray-400 mt-1"><i
                                            class="fas fa-arrow-down text-red-500 mr-1"></i>Dari Rincian Pengeluaran</p>
                                </div>
                            </div>
                            <div class="mt-4 p-3 bg-white rounded-xl border border-gray-200">
                                <div class="flex items-center justify-center flex-wrap gap-2 text-sm">
                                    <span
                                        class="px-2 py-1 bg-green-100 text-green-700 rounded font-semibold">{{ $departure->total_pendapatan_bersih_formatted }}</span>
                                    <span class="text-gray-400 font-bold">−</span>
                                    <span
                                        class="px-2 py-1 bg-red-100 text-red-700 rounded font-semibold">{{ $departure->total_pengeluaran_formatted }}</span>
                                    <span class="text-gray-400 font-bold">=</span>
                                    <span
                                        class="px-3 py-1 {{ $isProfit ? 'bg-green-500' : 'bg-red-500' }} text-white rounded font-bold">{{ $hasilAkhir >= 0 ? '+' : '−' }}
                                        Rp {{ number_format(abs($hasilAkhir), 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-2">
                                <div class="bg-white rounded-lg p-2 border border-gray-200 text-center">
                                    <p class="text-[10px] text-gray-500">Margin Laba</p>
                                    <p class="text-sm font-bold {{ $isProfit ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $departure->margin_laba_formatted }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-2 border border-gray-200 text-center">
                                    <p class="text-[10px] text-gray-500">Rasio Profit</p>
                                    <p class="text-sm font-bold {{ $isProfit ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($persentaseHasil, 2) }}%</p>
                                </div>
                                <div class="bg-white rounded-lg p-2 border border-gray-200 text-center">
                                    <p class="text-[10px] text-gray-500">Total Jamaah</p>
                                    <p class="text-sm font-bold text-gray-700">{{ $departure->jamaahs->count() }} orang
                                    </p>
                                </div>
                                <div class="bg-white rounded-lg p-2 border border-gray-200 text-center">
                                    <p class="text-[10px] text-gray-500">Laba per Jamaah</p>
                                    <p class="text-sm font-bold {{ $isProfit ? 'text-green-600' : 'text-red-600' }}">
                                        @if ($departure->jamaahs->count() > 0)
                                            {{ $hasilAkhir >= 0 ? '+' : '−' }} Rp
                                            {{ number_format(abs($hasilAkhir) / $departure->jamaahs->count(), 0, ',', '.') }}
                                        @else
                                            Rp 0
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div
                                class="mt-4 p-3 rounded-lg {{ $isProfit ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                                <p class="text-xs {{ $isProfit ? 'text-green-700' : 'text-red-700' }}">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    @if ($isProfit)
                                        Keberangkatan ini <strong>menghasilkan keuntungan</strong> sebesar
                                        <strong>{{ $departure->keuntungan_formatted }}</strong> dengan margin laba
                                        <strong>{{ $departure->margin_laba_formatted }}</strong> dari total pendapatan
                                        bersih.
                                    @else
                                        Keberangkatan ini <strong>mengalami kerugian</strong> sebesar <strong>Rp
                                            {{ number_format(abs($hasilAkhir), 0, ',', '.') }}</strong>. Periksa kembali
                                        rincian pengeluaran untuk efisiensi.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- INFORMASI SISTEM --}}
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                    <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-calendar-alt text-yellow-500 mr-2"></i> Informasi Sistem
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex justify-between text-sm">
                            <dt class="text-gray-500">Dibuat Pada</dt>
                            <dd class="font-medium text-gray-700">{{ $departure->created_at->format('d M Y H:i') }}</dd>
                        </div>
                        <div class="flex justify-between text-sm">
                            <dt class="text-gray-500">Terakhir Diperbarui</dt>
                            <dd class="font-medium text-gray-700">{{ $departure->updated_at->format('d M Y H:i') }}</dd>
                        </div>
                    </div>
                </div>

                <!-- Aksi Bawah -->
                <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-end gap-3">
                    <form action="{{ route('transaksional.departure.sync-all', $departure->id_departure) }}"
                        method="POST"
                        onsubmit="return confirm('Sinkronisasi akan memperbarui semua perlengkapan dan jenis transaksi dengan jumlah jamaah terbaru ({{ $departure->jamaahs->count() }} jamaah). Lanjutkan?')">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition text-sm font-medium shadow-sm hover:shadow">
                            <i class="fas fa-sync-alt mr-2"></i> Sinkronisasi Ulang
                        </button>
                    </form>
                    <a href="{{ route('transaksional.departure.index') }}"
                        class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">Kembali</a>
                    <a href="{{ route('transaksional.departure.edit', $departure->id_departure) }}"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">Edit Dasar</a>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL INPUT HARGA ASLI UPGRADE --}}
    <div id="hargaAsliModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-money-bill-wave text-orange-500 mr-2"></i> Input Harga Asli (Modal) Upgrade
                </h5>
                <button onclick="closeHargaAsliModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('transaksional.departure.update-harga-asli-upgrade', $departure->id_departure) }}"
                method="POST" class="p-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Asli (Modal) Upgrade <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                        <input type="text" name="harga_asli_upgrade_total" id="harga_asli_upgrade_total_display"
                            value="{{ ($departure->harga_asli_upgrade_total ?? 0) > 0 ? number_format($departure->harga_asli_upgrade_total, 0, ',', '.') : '' }}"
                            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-lg"
                            placeholder="0" oninput="formatRupiahHargaAsli(this)" autocomplete="off">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Harga asli = harga <strong>modal</strong> upgrade hotel (yang dibayarkan ke hotel).
                        <br>Total upgrade = yang <strong>dibayar jamaah</strong>.
                    </p>
                    <div class="mt-3 bg-gray-50 rounded-lg p-3 border border-gray-200">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Total Upgrade (Dibayar Jamaah):</span>
                            <span
                                class="font-bold text-orange-600">{{ $departure->total_upgrade_hotel_gabungan_formatted }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Selisih (Dibayar − Modal):</span>
                            <span class="font-bold {{ $selisihStatus['color'] }}"
                                id="previewSelisih">{{ $departure->selisih_upgrade_hotel_formatted }}</span>
                        </div>
                        <div class="mt-2 pt-2 border-t border-gray-200">
                            <p class="text-xs text-gray-500" id="previewSelisihLabel">
                                @if ($selisih > 0)
                                    <i class="fas fa-arrow-up text-green-500 mr-1"></i> Akan masuk ke <strong
                                        class="text-green-600">pendapatan</strong> (PROFIT)
                                @elseif ($selisih < 0)
                                    <i class="fas fa-arrow-down text-red-500 mr-1"></i> Akan masuk ke <strong
                                        class="text-red-600">pengeluaran</strong> (nombok)
                                @else
                                    <i class="fas fa-equals text-gray-500 mr-1"></i> Tidak ada selisih
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 mt-4">
                    <button type="button" onclick="closeHargaAsliModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- MODAL PEMBAYARAN TAK TERDUGA --}}
    {{-- ============================================ --}}
    <div id="pembayaranTakTerdugaModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                    <span id="pembayaranTakTerdugaModalTitle">Tambah Pembayaran Tak Terduga</span>
                </h5>
                <button onclick="closePembayaranTakTerdugaModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="pembayaranTakTerdugaForm"
                action="{{ route('transaksional.departure.store-pembayaran-tak-terduga', $departure->id_departure) }}"
                method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="pembayaranTakTerdugaMethod" value="POST">
                <input type="hidden" name="pembayaran_id" id="pembayaranTakTerdugaId" value="">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nama Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_pembayaran" id="ptt_nama_pembayaran"
                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                        placeholder="Contoh: Denda overstay, biaya tambahan, dll" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Total Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                        <input type="text" name="total_pembayaran" id="ptt_total_pembayaran"
                            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 text-lg"
                            placeholder="0" required oninput="formatRupiahPtt(this)" autocomplete="off">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        Akan masuk ke <strong class="text-red-600">pengeluaran</strong> departure
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan</label>
                    <textarea name="keterangan" id="ptt_keterangan" rows="2"
                        class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                        placeholder="Catatan tambahan (opsional)"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closePembayaranTakTerdugaModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit" id="pembayaranTakTerdugaSubmitBtn"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                        <i class="fas fa-save mr-2"></i> <span id="pembayaranTakTerdugaSubmitText">Tambah</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL ATUR HOTEL TOUR --}}
    <div id="paketTourHotelModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-5xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-umbrella-beach text-yellow-500 mr-2"></i> Atur Hotel Tour
                </h5>
                <button onclick="closePaketTourHotelModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('transaksional.departure.update-paket-tour-hotel', $departure->id_departure) }}"
                method="POST" class="p-6 space-y-6" id="paketTourHotelForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id_paket_tour" value="{{ $departure->produk->paket_tour_id ?? '' }}">
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <p class="text-sm text-blue-700"><i class="fas fa-info-circle mr-2"></i>Atur hotel-hotel yang
                        termasuk
                        dalam paket tour untuk keberangkatan ini.</p>
                    <div class="flex items-center gap-4 mt-2 text-xs text-blue-600">
                        <span>Paket Tour: <strong>{{ $departure->produk->paketTour->kota_tujuan ?? '-' }}</strong></span>
                        <span>Durasi: <strong>{{ $departure->produk->paketTour->durasi_hari ?? 0 }} Hari</strong></span>
                    </div>
                </div>
                <div id="paketTourHotelsContainer">
                    @php
                        $tourHotels = $departure->departurePaketTourHotels->sortBy('urutan');
                        $availableHotels = $departure->produk->paketTour->hotels ?? collect();
                    @endphp
                    @if ($availableHotels->count() > 0)
                        <div class="space-y-4">
                            @foreach ($availableHotels as $index => $hotel)
                                @php
                                    $hotelTourItems = $tourHotels->where('id_hotel', $hotel->id_hotel);
                                    $isChecked = $hotelTourItems->count() > 0;
                                @endphp
                                <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow duration-200 hotel-tour-item"
                                    data-hotel-id="{{ $hotel->id_hotel }}" data-index="{{ $loop->index }}">
                                    <div class="flex items-start gap-4">
                                        <div class="flex items-start gap-2 pt-1">
                                            <input type="checkbox"
                                                name="paket_tour_hotels[{{ $loop->index }}][id_hotel]"
                                                value="{{ $hotel->id_hotel }}" {{ $isChecked ? 'checked' : '' }}
                                                class="hotel-tour-checkbox w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500 mt-1">
                                            <span
                                                class="inline-flex items-center justify-center w-6 h-6 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">{{ $loop->iteration }}</span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="font-semibold text-gray-800">{{ $hotel->nama_hotel }}</p>
                                                    <p class="text-sm text-gray-500">{{ $hotel->kota ?? '-' }}
                                                        @if ($hotel->bintang)
                                                            · {{ $hotel->bintang_text }}
                                                        @endif
                                                    </p>
                                                </div>
                                                <span class="text-sm text-gray-400"><i
                                                        class="fas fa-door-open mr-1"></i><span
                                                        id="kamarCount-{{ $loop->index }}">Memuat...</span></span>
                                            </div>
                                            <div id="kamarContainer-{{ $loop->index }}"
                                                class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3"></div>
                                            <div class="mt-2 text-sm text-gray-500">Total: <span
                                                    id="totalHotelTour-{{ $loop->index }}"
                                                    class="font-bold text-yellow-600">Rp 0</span></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-700"><i
                                        class="fas fa-calculator mr-2 text-yellow-500"></i>Total Seluruh Hotel Tour</span>
                                <span class="text-lg font-bold text-yellow-600"
                                    id="totalPaketTourHotelPreview">{{ $departure->total_harga_paket_tour_hotel_formatted }}</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-xl border border-gray-200">
                            <i class="fas fa-hotel text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 font-medium">Belum ada hotel yang terdaftar untuk paket tour ini</p>
                            <p class="text-gray-400 text-sm mt-1">Silakan tambahkan hotel pada paket tour terlebih dahulu
                            </p>
                            <button type="button" onclick="closePaketTourHotelModal()"
                                class="inline-block mt-3 px-4 py-2 bg-gray-500 text-white text-sm rounded-lg hover:bg-gray-600 transition"><i
                                    class="fas fa-times mr-2"></i> Tutup</button>
                        </div>
                    @endif
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closePaketTourHotelModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition"><i
                            class="fas fa-save mr-2"></i> Simpan Hotel Tour</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL MASKAPAI --}}
    <div id="maskapaiModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800 flex items-center"><i
                        class="fas fa-plane text-yellow-500 mr-2"></i> Tambah Maskapai & Harga</h5>
                <button onclick="closeMaskapaiModal()" class="text-gray-400 hover:text-gray-600"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <form action="{{ route('transaksional.departure.update-maskapai', $departure->id_departure) }}"
                method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Maskapai Domestik Berangkat</label>
                        <select name="id_maskapai_domestik_berangkat"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">-- Pilih --</option>
                            @foreach ($maskapaiOptions as $maskapai)
                                @if ($maskapai->is_domestik)
                                    <option value="{{ $maskapai->id_maskapai }}"
                                        {{ $departure->id_maskapai_domestik_berangkat == $maskapai->id_maskapai ? 'selected' : '' }}>
                                        {{ $maskapai->nama_maskapai }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Domestik Berangkat</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                            <input type="text" name="harga_maskapai_domestik_berangkat_display"
                                id="harga_maskapai_domestik_berangkat_display"
                                value="{{ $departure->harga_maskapai_domestik_berangkat ? 'Rp ' . number_format($departure->harga_maskapai_domestik_berangkat, 0, ',', '.') : 'Rp 0' }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 maskapai-harga"
                                placeholder="Rp 0"
                                oninput="formatRupiahMaskapai(this, 'harga_maskapai_domestik_berangkat')"
                                onblur="formatRupiahMaskapai(this, 'harga_maskapai_domestik_berangkat')">
                            <input type="hidden" name="harga_maskapai_domestik_berangkat"
                                id="harga_maskapai_domestik_berangkat"
                                value="{{ $departure->harga_maskapai_domestik_berangkat }}">
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Maskapai Domestik Pulang</label>
                        <select name="id_maskapai_domestik_pulang"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">-- Pilih --</option>
                            @foreach ($maskapaiOptions as $maskapai)
                                @if ($maskapai->is_domestik)
                                    <option value="{{ $maskapai->id_maskapai }}"
                                        {{ $departure->id_maskapai_domestik_pulang == $maskapai->id_maskapai ? 'selected' : '' }}>
                                        {{ $maskapai->nama_maskapai }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Domestik Pulang</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                            <input type="text" name="harga_maskapai_domestik_pulang_display"
                                id="harga_maskapai_domestik_pulang_display"
                                value="{{ $departure->harga_maskapai_domestik_pulang ? 'Rp ' . number_format($departure->harga_maskapai_domestik_pulang, 0, ',', '.') : 'Rp 0' }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 maskapai-harga"
                                placeholder="Rp 0"
                                oninput="formatRupiahMaskapai(this, 'harga_maskapai_domestik_pulang')"
                                onblur="formatRupiahMaskapai(this, 'harga_maskapai_domestik_pulang')">
                            <input type="hidden" name="harga_maskapai_domestik_pulang"
                                id="harga_maskapai_domestik_pulang"
                                value="{{ $departure->harga_maskapai_domestik_pulang }}">
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Maskapai Internasional
                            Berangkat</label>
                        <select name="id_maskapai_internasional_berangkat"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">-- Pilih --</option>
                            @foreach ($maskapaiOptions as $maskapai)
                                @if ($maskapai->is_internasional)
                                    <option value="{{ $maskapai->id_maskapai }}"
                                        {{ $departure->id_maskapai_internasional_berangkat == $maskapai->id_maskapai ? 'selected' : '' }}>
                                        {{ $maskapai->nama_maskapai }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Internasional
                            Berangkat</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                            <input type="text" name="harga_maskapai_internasional_berangkat_display"
                                id="harga_maskapai_internasional_berangkat_display"
                                value="{{ $departure->harga_maskapai_internasional_berangkat ? 'Rp ' . number_format($departure->harga_maskapai_internasional_berangkat, 0, ',', '.') : 'Rp 0' }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 maskapai-harga"
                                placeholder="Rp 0"
                                oninput="formatRupiahMaskapai(this, 'harga_maskapai_internasional_berangkat')"
                                onblur="formatRupiahMaskapai(this, 'harga_maskapai_internasional_berangkat')">
                            <input type="hidden" name="harga_maskapai_internasional_berangkat"
                                id="harga_maskapai_internasional_berangkat"
                                value="{{ $departure->harga_maskapai_internasional_berangkat }}">
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Maskapai Internasional
                            Pulang</label>
                        <select name="id_maskapai_internasional_pulang"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">-- Pilih --</option>
                            @foreach ($maskapaiOptions as $maskapai)
                                @if ($maskapai->is_internasional)
                                    <option value="{{ $maskapai->id_maskapai }}"
                                        {{ $departure->id_maskapai_internasional_pulang == $maskapai->id_maskapai ? 'selected' : '' }}>
                                        {{ $maskapai->nama_maskapai }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Internasional Pulang</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                            <input type="text" name="harga_maskapai_internasional_pulang_display"
                                id="harga_maskapai_internasional_pulang_display"
                                value="{{ $departure->harga_maskapai_internasional_pulang ? 'Rp ' . number_format($departure->harga_maskapai_internasional_pulang, 0, ',', '.') : 'Rp 0' }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 maskapai-harga"
                                placeholder="Rp 0"
                                oninput="formatRupiahMaskapai(this, 'harga_maskapai_internasional_pulang')"
                                onblur="formatRupiahMaskapai(this, 'harga_maskapai_internasional_pulang')">
                            <input type="hidden" name="harga_maskapai_internasional_pulang"
                                id="harga_maskapai_internasional_pulang"
                                value="{{ $departure->harga_maskapai_internasional_pulang }}">
                        </div>
                    </div>
                </div>
                <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                    <p class="text-sm text-blue-700"><i class="fas fa-info-circle mr-2"></i>Pastikan harga maskapai
                        sudah termasuk pajak dan biaya lainnya.</p>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeMaskapaiModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition"><i
                            class="fas fa-save mr-2"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL HOTEL --}}
    <div id="hotelModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800 flex items-center"><i
                        class="fas fa-hotel text-yellow-500 mr-2"></i> Tambah Hotel & Tipe Kamar</h5>
                <button onclick="closeHotelModal()" class="text-gray-400 hover:text-gray-600"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <form action="{{ route('transaksional.departure.update-hotel', $departure->id_departure) }}"
                method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="searchHotelModal" placeholder="Cari hotel..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Hotel Mekkah</label>
                    <select name="id_hotel_mekkah" id="id_hotel_mekkah"
                        class="hotel-select w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                        data-departure="{{ $departure->id_departure }}" data-container="kamarContainerMekkah"
                        data-total="totalHotelMekkah">
                        <option value="">-- Pilih --</option>
                        @foreach ($hotelOptions as $hotel)
                            @if ($hotel->kota == 'Mekkah')
                                <option value="{{ $hotel->id_hotel }}"
                                    {{ $departure->id_hotel_mekkah == $hotel->id_hotel ? 'selected' : '' }}>
                                    {{ $hotel->nama_hotel }} ({{ $hotel->bintang_text }})</option>
                            @endif
                        @endforeach
                    </select>
                    <div id="kamarContainerMekkah" class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3"></div>
                    <div class="mt-2 text-sm text-gray-500">Total: <span id="totalHotelMekkah"
                            class="font-bold text-yellow-600">Rp 0</span></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Hotel Madinah</label>
                    <select name="id_hotel_madinah" id="id_hotel_madinah"
                        class="hotel-select w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                        data-departure="{{ $departure->id_departure }}" data-container="kamarContainerMadinah"
                        data-total="totalHotelMadinah">
                        <option value="">-- Pilih --</option>
                        @foreach ($hotelOptions as $hotel)
                            @if ($hotel->kota == 'Madinah')
                                <option value="{{ $hotel->id_hotel }}"
                                    {{ $departure->id_hotel_madinah == $hotel->id_hotel ? 'selected' : '' }}>
                                    {{ $hotel->nama_hotel }} ({{ $hotel->bintang_text }})</option>
                            @endif
                        @endforeach
                    </select>
                    <div id="kamarContainerMadinah" class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3"></div>
                    <div class="mt-2 text-sm text-gray-500">Total: <span id="totalHotelMadinah"
                            class="font-bold text-yellow-600">Rp 0</span></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Hotel Transit</label>
                    <select name="id_hotel_transit" id="id_hotel_transit"
                        class="hotel-select w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                        data-departure="{{ $departure->id_departure }}" data-container="kamarContainerTransit"
                        data-total="totalHotelTransit">
                        <option value="">-- Pilih --</option>
                        @foreach ($hotelOptions as $hotel)
                            @if ($hotel->kota != 'Mekkah' && $hotel->kota != 'Madinah')
                                <option value="{{ $hotel->id_hotel }}"
                                    {{ $departure->id_hotel_transit == $hotel->id_hotel ? 'selected' : '' }}>
                                    {{ $hotel->nama_hotel }} ({{ $hotel->bintang_text }})</option>
                            @endif
                        @endforeach
                    </select>
                    <div id="kamarContainerTransit" class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3"></div>
                    <div class="mt-2 text-sm text-gray-500">Total: <span id="totalHotelTransit"
                            class="font-bold text-yellow-600">Rp 0</span></div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeHotelModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition"><i
                            class="fas fa-save mr-2"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL JAMAAH --}}
    <div id="jamaahModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800 flex items-center"><i
                        class="fas fa-users text-yellow-500 mr-2"></i> Tambah Daftar Jamaah</h5>
                <button onclick="closeJamaahModal()" class="text-gray-400 hover:text-gray-600"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <form action="{{ route('transaksional.departure.update-jamaah', $departure->id_departure) }}"
                method="POST" class="p-6">
                @csrf
                @method('PUT')
                @if ($departure->produk_paket || ($departure->bulan_keberangkatan && $departure->tahun_keberangkatan))
                    <div class="mb-4 bg-blue-50 border border-blue-200 rounded-xl p-3">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-filter text-blue-500 mt-0.5"></i>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-blue-800">Jamaah ditampilkan berdasarkan filter
                                    keberangkatan:</p>
                                @if ($departure->produk_paket)
                                    <p class="text-xs text-blue-600 mt-1"><i class="fas fa-box mr-1"></i> Produk Paket:
                                        <strong>{{ $departure->produk_paket }}</strong>
                                    </p>
                                @endif
                                @if ($departure->bulan_keberangkatan && $departure->tahun_keberangkatan)
                                    <p class="text-xs text-blue-600 mt-0.5"><i
                                            class="fas fa-calendar-alt mr-1"></i>Bulan:
                                        <strong>{{ date('F', mktime(0, 0, 0, $departure->bulan_keberangkatan, 1)) }}</strong>
                                        · Tahun: <strong>{{ $departure->tahun_keberangkatan }}</strong>
                                    </p>
                                @endif
                                <p class="text-xs text-blue-500 mt-1">Hanya jamaah dengan <strong>produk paket &
                                        bulan/tahun</strong> yang sama yang ditampilkan.</p>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="mb-3 flex flex-wrap items-center gap-3 text-xs">
                    <span class="inline-flex items-center gap-1.5"><span
                            class="w-3 h-3 rounded-full bg-green-500"></span><span
                            class="text-gray-600">Lunas</span></span>
                    <span class="inline-flex items-center gap-1.5"><span
                            class="w-3 h-3 rounded-full bg-red-500"></span><span class="text-gray-600">Belum
                            Lunas</span></span>
                    <span class="inline-flex items-center gap-1.5"><span
                            class="w-3 h-3 rounded-full bg-blue-500"></span><span class="text-gray-600">Sudah
                            Terdaftar</span></span>
                    <span class="inline-flex items-center gap-1.5"><span
                            class="w-3 h-3 rounded-full bg-purple-500"></span><span class="text-gray-600">Fee
                            Passport</span></span>
                </div>
                <div class="mb-4">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="searchJamaahModal"
                            placeholder="Cari jamaah berdasarkan nama, produk, atau status..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                    </div>
                    @php
                        $lunasCount = $jamaahs->where('status_pembayaran', 'Lunas')->count();
                        $belumLunasCount = $jamaahs->where('status_pembayaran', '!=', 'Lunas')->count();
                    @endphp
                    <div class="flex flex-wrap items-center gap-4 mt-2 text-xs">
                        <span class="text-gray-400"><i class="fas fa-info-circle mr-1"></i> Ketik untuk mencari
                            jamaah</span>
                        <span id="jamaahCountModal" class="font-medium">
                            <span class="text-gray-600">{{ $jamaahs->count() }} jamaah tersedia</span>
                            @if ($lunasCount > 0)
                                <span class="text-green-600 font-medium">· {{ $lunasCount }} Lunas</span>
                            @endif
                            @if ($belumLunasCount > 0)
                                <span class="text-red-600 font-bold">· {{ $belumLunasCount }} Belum Lunas</span>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-72 overflow-y-auto border border-gray-200 rounded-xl p-4"
                    id="jamaahModalList">
                    @php
                        $selectedJamaahs = $departure->jamaahs->pluck('id_jamaah')->toArray();
                        $bulanNamaModal = [
                            1 => 'Jan',
                            2 => 'Feb',
                            3 => 'Mar',
                            4 => 'Apr',
                            5 => 'Mei',
                            6 => 'Jun',
                            7 => 'Jul',
                            8 => 'Agu',
                            9 => 'Sep',
                            10 => 'Okt',
                            11 => 'Nov',
                            12 => 'Des',
                        ];
                        $bulanFullModal = [
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember',
                        ];
                    @endphp
                    @forelse($jamaahs as $jamaah)
                        @php
                            $isLunas = $jamaah->status_pembayaran == 'Lunas';
                            $isRegistered = in_array($jamaah->id_jamaah, $selectedJamaahs);
                            $hasPassport = $jamaah->is_pembuatan_passport_efektif;
                            if ($isRegistered) {
                                $borderClass = 'border-2 border-blue-400';
                                $bgClass = 'bg-blue-50';
                                $badgeClass = 'bg-blue-500 text-white';
                                $badgeText = 'TERDAFTAR';
                            } elseif ($isLunas) {
                                $borderClass = 'border-2 border-green-500';
                                $bgClass = 'bg-green-50';
                                $badgeClass = 'bg-green-500 text-white';
                                $badgeText = 'LUNAS';
                            } else {
                                $borderClass = 'border-2 border-red-500';
                                $bgClass = 'bg-red-50';
                                $badgeClass = 'bg-red-500 text-white';
                                $badgeText = 'BELUM LUNAS';
                            }
                            $jamaahKeberangkatanInfo = '';
                            $jamaahHargaInfo = '';
                            $jamaahHargaLabel = 'Harga';
                            $jamaahFeePassport = $jamaah->fee_passport;
                            if ($jamaah->keberangkatan && $jamaah->keberangkatan->count() > 0) {
                                $firstK = $jamaah->keberangkatan->first();
                                $bulanPivot = $firstK->pivot->bulan_keberangkatan ?? null;
                                $tahunPivot = $firstK->pivot->tahun_keberangkatan ?? null;
                                if ($bulanPivot && $tahunPivot) {
                                    $jamaahKeberangkatanInfo =
                                        ($bulanNamaModal[$bulanPivot] ?? '-') . ' ' . $tahunPivot;
                                    $jamaahHargaLabel =
                                        'Harga (' . ($bulanFullModal[$bulanPivot] ?? '-') . ' ' . $tahunPivot . ')';
                                }
                                if ($firstK->pivot->harga_paket ?? null) {
                                    $jamaahHargaInfo = 'Rp ' . number_format($firstK->pivot->harga_paket, 0, ',', '.');
                                } elseif ($firstK->pivot->harga ?? null) {
                                    $jamaahHargaInfo = 'Rp ' . number_format($firstK->pivot->harga, 0, ',', '.');
                                }
                            } elseif ($jamaah->bulan_keberangkatan && $jamaah->tahun_keberangkatan) {
                                $jamaahKeberangkatanInfo =
                                    ($bulanNamaModal[$jamaah->bulan_keberangkatan] ?? '-') .
                                    ' ' .
                                    $jamaah->tahun_keberangkatan;
                                $jamaahHargaLabel =
                                    'Harga (' .
                                    ($bulanFullModal[$jamaah->bulan_keberangkatan] ?? '-') .
                                    ' ' .
                                    $jamaah->tahun_keberangkatan .
                                    ')';
                                if ($jamaah->harga_paket ?? null) {
                                    $jamaahHargaInfo = 'Rp ' . number_format($jamaah->harga_paket, 0, ',', '.');
                                }
                            }
                            if (!$jamaahHargaInfo) {
                                if ($jamaah->total_tagihan ?? null) {
                                    $jamaahHargaInfo = 'Rp ' . number_format($jamaah->total_tagihan, 0, ',', '.');
                                } elseif ($jamaah->total_tagihan_setelah_diskon ?? null) {
                                    $jamaahHargaInfo =
                                        'Rp ' . number_format($jamaah->total_tagihan_setelah_diskon, 0, ',', '.');
                                }
                            }
                        @endphp
                        <label
                            class="jamaah-modal-item relative flex items-start p-2.5 {{ $borderClass }} {{ $bgClass }} rounded-lg hover:shadow-md transition cursor-pointer"
                            data-name="{{ strtolower($jamaah->nama_lengkap) }}"
                            data-produk="{{ strtolower($jamaah->produk_paket) }}"
                            data-status="{{ strtolower($jamaah->status_pembayaran) }}"
                            data-registered="{{ $isRegistered ? '1' : '0' }}"
                            title="Keberangkatan: {{ $jamaahKeberangkatanInfo ?: '-' }}{{ $jamaahHargaInfo ? ' | Harga: ' . $jamaahHargaInfo : '' }}">
                            <span
                                class="absolute -top-2 -right-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ $badgeClass }} shadow-sm whitespace-nowrap">{{ $badgeText }}</span>
                            <input type="checkbox" name="jamaah_ids[]" value="{{ $jamaah->id_jamaah }}"
                                {{ $isRegistered ? 'checked' : '' }}
                                class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500 flex-shrink-0 mt-0.5">
                            <div class="ml-2 overflow-hidden flex-1">
                                <p class="text-sm font-semibold text-gray-800 truncate pr-2">{{ $jamaah->nama_lengkap }}
                                </p>
                                <p class="text-xs text-gray-500 truncate">{{ $jamaah->produk_paket }}</p>
                                @if ($jamaahKeberangkatanInfo)
                                    <p class="text-[10px] text-blue-600 truncate mt-0.5"><i
                                            class="fas fa-plane mr-0.5"></i> Keberangkatan:
                                        {{ $jamaahKeberangkatanInfo }}</p>
                                @endif
                                @if ($jamaahHargaInfo)
                                    <p class="text-[10px] text-emerald-600 font-semibold truncate mt-0.5"><i
                                            class="fas fa-money-bill-wave mr-0.5"></i> {{ $jamaahHargaLabel }}:
                                        {{ $jamaahHargaInfo }}</p>
                                @endif
                                @if ($hasPassport)
                                    <p class="text-[10px] text-green-600 font-semibold truncate mt-0.5">
                                        <i
                                            class="fas fa-passport mr-0.5"></i>{{ $jamaah->is_pembuatan_passport ? 'PASSPORT YA' : 'PASSPORT YA (EFEKTIF)' }}
                                        @if ($jamaahFeePassport > 0)
                                            · Fee: Rp {{ number_format($jamaahFeePassport, 0, ',', '.') }}
                                        @endif
                                    </p>
                                @endif
                                <p class="text-xs mt-1">
                                    <span
                                        class="px-1.5 py-0.5 rounded-full text-[10px] font-medium {{ $jamaah->status_pembayaran == 'Lunas' ? 'bg-green-100 text-green-700' : ($jamaah->status_pembayaran == 'DP' ? 'bg-yellow-100 text-yellow-700' : ($jamaah->status_pembayaran == 'Setoran' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700')) }}">{{ $jamaah->status_pembayaran }}</span>
                                </p>
                            </div>
                        </label>
                    @empty
                        <div class="col-span-3 text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-users text-gray-300 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Tidak ada jamaah yang tersedia</p>
                            <p class="text-gray-400 text-xs mt-1">
                                Tidak ada jamaah dengan produk <strong>{{ $departure->produk_paket }}</strong>
                                @if ($departure->bulan_keberangkatan && $departure->tahun_keberangkatan)
                                    dan keberangkatan
                                    <strong>{{ date('F', mktime(0, 0, 0, $departure->bulan_keberangkatan, 1)) }}
                                        {{ $departure->tahun_keberangkatan }}</strong>
                                @endif
                                yang belum terdaftar di departure lain.
                            </p>
                        </div>
                    @endforelse
                </div>
                <div
                    class="mt-4 flex items-center justify-between text-sm bg-blue-50 rounded-lg px-4 py-2.5 border border-blue-200">
                    <div class="text-blue-700"><i class="fas fa-info-circle mr-2"></i>Centang jamaah yang akan
                        dimasukkan ke keberangkatan ini</div>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="selectAllJamaah()"
                            class="text-blue-600 hover:text-blue-800 text-xs font-medium"><i
                                class="fas fa-check-double mr-1"></i> Pilih Semua</button>
                        <button type="button" onclick="deselectAllJamaah()"
                            class="text-red-600 hover:text-red-800 text-xs font-medium"><i
                                class="fas fa-times mr-1"></i> Hapus Semua</button>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 mt-4">
                    <button type="button" onclick="closeJamaahModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition"><i
                            class="fas fa-save mr-2"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL PERLENGKAPAN --}}
    <div id="perlengkapanModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800">Tambah Perlengkapan ke Departure</h5>
                <button onclick="closePerlengkapanModal()" class="text-gray-400 hover:text-gray-600"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <form action="{{ route('transaksional.departure.update-perlengkapan', $departure->id_departure) }}"
                method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Perlengkapan <span
                            class="text-red-500">*</span></label>
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-60 overflow-y-auto border border-gray-200 rounded-xl p-3">
                        @forelse($perlengkapanOptions as $perlengkapan)
                            <label
                                class="flex items-center p-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                                <input type="checkbox" name="id_perlengkapan[]"
                                    value="{{ $perlengkapan->id_perlengkapan }}"
                                    class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                                <div class="ml-2">
                                    <p class="text-sm font-medium text-gray-700">{{ $perlengkapan->nama_perlengkapan }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $perlengkapan->harga_satuan_formatted }} /
                                        {{ $perlengkapan->satuan ?? 'unit' }}</p>
                                </div>
                            </label>
                        @empty
                            <p class="text-sm text-gray-400 col-span-2 text-center py-4">Semua perlengkapan sudah
                                ditambahkan.</p>
                        @endforelse
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah per Jamaah</label>
                        <input type="number" name="jumlah_per_jamaah" value="1"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                            min="1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan</label>
                        <input type="text" name="keterangan"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                            placeholder="Catatan (opsional)">
                    </div>
                </div>
                <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                    <p class="text-sm text-blue-700"><i class="fas fa-info-circle mr-2"></i>Perlengkapan akan diberikan
                        ke <strong>SEMUA</strong> jamaah yang terdaftar di departure ini.</p>
                    <p class="text-xs text-blue-500 mt-1">Jumlah jamaah saat ini:
                        <strong>{{ $departure->jamaahs->count() }}</strong> orang
                    </p>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closePerlengkapanModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition"><i
                            class="fas fa-save mr-2"></i> Tambah</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL JENIS TRANSAKSI --}}
    <div id="jenisTransaksiModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800 flex items-center"><i
                        class="fas fa-receipt text-yellow-500 mr-2"></i> Tambah Jenis Transaksi ke Departure</h5>
                <button onclick="closeJenisTransaksiModal()" class="text-gray-400 hover:text-gray-600"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <form action="{{ route('transaksional.departure.update-jenis-transaksi', $departure->id_departure) }}"
                method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                @php $totalJamaah = $departure->jamaahs->count(); @endphp
                <div class="mb-4">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="searchJenisTransaksiModal"
                            placeholder="Cari jenis transaksi berdasarkan nama atau keterangan..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                    </div>
                    <div class="flex items-center gap-4 mt-2 text-xs text-gray-400">
                        <span><i class="fas fa-info-circle mr-1"></i> Ketik untuk mencari jenis transaksi</span>
                        <span id="jenisTransaksiCountModal"
                            class="font-medium text-gray-600">{{ $jenisTransaksiOptions->count() }} jenis transaksi
                            tersedia</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Jenis Transaksi <span
                            class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto border border-gray-200 rounded-xl p-3"
                        id="jenisTransaksiModalList">
                        @forelse($jenisTransaksiOptions as $jenis)
                            <label
                                class="jenis-transaksi-modal-item flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer"
                                data-nama="{{ strtolower($jenis->nama) }}"
                                data-keterangan="{{ strtolower($jenis->keterangan ?? '') }}">
                                <input type="checkbox" name="id_jenis_transaksi[]" value="{{ $jenis->id_jenis }}"
                                    class="jenis-transaksi-checkbox w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500 mt-1">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-700">{{ $jenis->nama }}</p>
                                        <span class="text-xs text-gray-400">{{ $jenis->kode ?? '' }}</span>
                                    </div>
                                    @if ($jenis->keterangan)
                                        <p class="text-xs text-gray-400">{{ $jenis->keterangan }}</p>
                                    @endif
                                    <div class="grid grid-cols-2 gap-2 mt-2">
                                        <div>
                                            <label class="text-xs text-gray-500">Harga Total</label>
                                            <div class="relative">
                                                <span
                                                    class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                                <input type="text"
                                                    name="harga_total_display[{{ $jenis->id_jenis }}]"
                                                    id="harga_total_display_{{ $jenis->id_jenis }}"
                                                    class="jenis-harga w-full pl-8 pr-2 py-1.5 border border-gray-200 rounded text-sm rupiah-input"
                                                    placeholder="Rp 0"
                                                    oninput="formatRupiahJenisTransaksi(this, 'harga_total_{{ $jenis->id_jenis }}')"
                                                    onblur="formatRupiahJenisTransaksi(this, 'harga_total_{{ $jenis->id_jenis }}')"
                                                    value="Rp 0">
                                                <input type="hidden" name="harga_total[{{ $jenis->id_jenis }}]"
                                                    id="harga_total_{{ $jenis->id_jenis }}" value="0">
                                            </div>
                                            <p class="text-[10px] text-gray-400 mt-0.5">Dibagi ke {{ $totalJamaah }}
                                                jamaah</p>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Catatan</label>
                                            <input type="text" name="catatan[{{ $jenis->id_jenis }}]"
                                                class="jenis-catatan w-full px-2 py-1.5 border border-gray-200 rounded text-sm"
                                                placeholder="Opsional">
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @empty
                            <p class="text-sm text-gray-400 col-span-2 text-center py-4">Semua jenis transaksi sudah
                                ditambahkan.</p>
                        @endforelse
                    </div>
                    <p class="text-xs text-gray-400 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>Masukkan <strong>harga total</strong> (bukan per jamaah).
                        Sistem otomatis membagi rata ke <strong>{{ $totalJamaah }} jamaah</strong>.
                    </p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Default Harga Total</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                <input type="text" name="default_harga_total_display"
                                    id="default_harga_total_display"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm rupiah-input"
                                    placeholder="Rp 0" oninput="formatRupiahJenisTransaksi(this, 'default_harga_total')"
                                    onblur="formatRupiahJenisTransaksi(this, 'default_harga_total')" value="Rp 0">
                                <input type="hidden" name="default_harga_total" id="default_harga_total"
                                    value="0">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Default Catatan</label>
                            <input type="text" name="default_catatan" id="default_catatan"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Catatan untuk semua">
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 mt-3">
                        <button type="button" onclick="fillAllHarga()"
                            class="text-sm text-blue-500 hover:text-blue-700"><i class="fas fa-fill-drip mr-1"></i> Isi
                            Semua Harga Total</button>
                        <button type="button" onclick="fillAllCatatan()"
                            class="text-sm text-blue-500 hover:text-blue-700"><i class="fas fa-fill-drip mr-1"></i> Isi
                            Semua Catatan</button>
                        <button type="button" onclick="selectAllJenisTransaksi()"
                            class="text-sm text-green-500 hover:text-green-700"><i class="fas fa-check-double mr-1"></i>
                            Pilih Semua</button>
                        <button type="button" onclick="deselectAllJenisTransaksi()"
                            class="text-sm text-red-500 hover:text-red-700"><i class="fas fa-times mr-1"></i> Hapus
                            Semua</button>
                    </div>
                </div>
                <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                    <p class="text-sm text-blue-700"><i class="fas fa-info-circle mr-2"></i>Jenis transaksi akan
                        ditambahkan ke <strong>SEMUA</strong> jamaah yang terdaftar di departure ini.</p>
                    <p class="text-xs text-blue-500 mt-1">Jumlah jamaah saat ini: <strong>{{ $totalJamaah }}</strong>
                        orang</p>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeJenisTransaksiModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition"><i
                            class="fas fa-save mr-2"></i> Tambah</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT HARGA JENIS TRANSAKSI --}}
    <div id="editJenisTransaksiModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800">Edit Harga Total Jenis Transaksi</h5>
                <button onclick="closeEditJenisTransaksiModal()" class="text-gray-400 hover:text-gray-600"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <form id="editJenisTransaksiForm" method="POST" class="p-6">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Total <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                        <input type="text" name="harga_total_display" id="edit_harga_total_display"
                            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm rupiah-input"
                            placeholder="Rp 0" oninput="formatRupiahJenisTransaksi(this, 'edit_harga_total')"
                            onblur="formatRupiahJenisTransaksi(this, 'edit_harga_total')">
                        <input type="hidden" name="harga_total" id="edit_harga_total" value="0">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Jumlah jamaah saat ini: {{ $departure->jamaahs->count() }}
                        orang</p>
                    <p class="text-xs text-gray-400 mt-0.5"><i class="fas fa-info-circle mr-1"></i>Sistem akan otomatis
                        membagi rata ke setiap jamaah</p>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 mt-4">
                    <button type="button" onclick="closeEditJenisTransaksiModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL CATATAN --}}
    <div id="catatanModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-2xl w-full">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800">Tambah Catatan</h5>
                <button onclick="closeCatatanModal()" class="text-gray-400 hover:text-gray-600"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <form action="{{ route('transaksional.departure.update-catatan', $departure->id_departure) }}"
                method="POST" class="p-6">
                @csrf
                @method('PUT')
                <textarea name="catatan" rows="5"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                    placeholder="Catatan tambahan untuk keberangkatan ini...">{{ $departure->catatan }}</textarea>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 mt-4">
                    <button type="button" onclick="closeCatatanModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL DETAIL PERLENGKAPAN --}}
    <div id="perlengkapanDetailModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800" id="perlengkapanDetailTitle">Detail Perlengkapan</h5>
                <button onclick="closePerlengkapanDetailModal()" class="text-gray-400 hover:text-gray-600"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-80px)]" id="perlengkapanDetailContent">
                <div class="flex items-center justify-center py-12">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin text-3xl text-yellow-500 mb-3"></i>
                        <p class="text-gray-500">Memuat data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL JENIS TRANSAKSI --}}
    <div id="jenisTransaksiDetailModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800" id="jenisTransaksiDetailTitle">Detail Jenis Transaksi
                </h5>
                <button onclick="closeJenisTransaksiDetailModal()" class="text-gray-400 hover:text-gray-600"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-80px)]" id="jenisTransaksiDetailContent">
                <div class="flex items-center justify-center py-12">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin text-3xl text-yellow-500 mb-3"></i>
                        <p class="text-gray-500">Memuat data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="recalculate-form" action="{{ route('transaksional.departure.recalculate', $departure->id_departure) }}"
        method="POST" class="hidden">
        @csrf
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchMaskapai = document.getElementById('searchMaskapai');
            if (searchMaskapai) {
                searchMaskapai.addEventListener('keyup', function() {
                    const search = this.value.toLowerCase();
                    document.querySelectorAll('.maskapai-item').forEach(function(item) {
                        const name = item.dataset.nama || '';
                        item.style.display = name.includes(search) ? 'flex' : 'none';
                    });
                });
            }
            const searchHotel = document.getElementById('searchHotel');
            if (searchHotel) {
                searchHotel.addEventListener('keyup', function() {
                    const search = this.value.toLowerCase();
                    document.querySelectorAll('.hotel-item').forEach(function(item) {
                        const name = item.dataset.nama || '';
                        item.style.display = name.includes(search) ? 'block' : 'none';
                    });
                });
            }
            const searchHotelTour = document.getElementById('searchHotelTour');
            if (searchHotelTour) {
                searchHotelTour.addEventListener('keyup', function() {
                    const search = this.value.toLowerCase();
                    document.querySelectorAll('#hotelTourList .hotel-tour-item').forEach(function(item) {
                        const name = item.dataset.nama || '';
                        const kota = item.dataset.kota || '';
                        item.style.display = (name.includes(search) || kota.includes(search)) ?
                            'block' : 'none';
                    });
                });
            }
            const searchPerlengkapan = document.getElementById('searchPerlengkapan');
            if (searchPerlengkapan) {
                searchPerlengkapan.addEventListener('keyup', function() {
                    const search = this.value.toLowerCase();
                    document.querySelectorAll('.perlengkapan-item').forEach(function(item) {
                        const name = item.dataset.nama || '';
                        item.style.display = name.includes(search) ? 'block' : 'none';
                    });
                });
            }
            const searchJamaah = document.getElementById('searchJamaah');
            if (searchJamaah) {
                searchJamaah.addEventListener('keyup', function() {
                    const search = this.value.toLowerCase();
                    document.querySelectorAll('.jamaah-item').forEach(function(item) {
                        const name = item.dataset.name || '';
                        item.style.display = name.includes(search) ? 'block' : 'none';
                    });
                });
            }
            const searchJenisTransaksi = document.getElementById('searchJenisTransaksi');
            if (searchJenisTransaksi) {
                searchJenisTransaksi.addEventListener('keyup', function() {
                    const search = this.value.toLowerCase();
                    document.querySelectorAll('.jenis-transaksi-item').forEach(function(item) {
                        const name = item.dataset.nama || '';
                        item.style.display = name.includes(search) ? 'block' : 'none';
                    });
                });
            }
            const searchJamaahModal = document.getElementById('searchJamaahModal');
            if (searchJamaahModal) {
                searchJamaahModal.addEventListener('keyup', function() {
                    const search = this.value.toLowerCase();
                    let visibleCount = 0,
                        lunasCount = 0,
                        belumLunasCount = 0;
                    document.querySelectorAll('.jamaah-modal-item').forEach(function(item) {
                        const name = item.dataset.name || '';
                        const produk = item.dataset.produk || '';
                        const status = item.dataset.status || '';
                        if (name.includes(search) || produk.includes(search) || status.includes(
                                search)) {
                            item.style.display = 'flex';
                            visibleCount++;
                            if (status === 'lunas') lunasCount++;
                            else belumLunasCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    let countHtml = '<span class="text-gray-600">' + visibleCount +
                        ' jamaah tersedia</span>';
                    if (lunasCount > 0) countHtml += ' <span class="text-green-600 font-medium">· ' +
                        lunasCount + ' Lunas</span>';
                    if (belumLunasCount > 0) countHtml += ' <span class="text-red-600 font-bold">· ' +
                        belumLunasCount + ' Belum Lunas</span>';
                    document.getElementById('jamaahCountModal').innerHTML = countHtml;
                });
            }
            const searchJenisTransaksiModal = document.getElementById('searchJenisTransaksiModal');
            if (searchJenisTransaksiModal) {
                searchJenisTransaksiModal.addEventListener('keyup', function() {
                    const search = this.value.toLowerCase();
                    let visibleCount = 0;
                    document.querySelectorAll('.jenis-transaksi-modal-item').forEach(function(item) {
                        const nama = item.dataset.nama || '';
                        const keterangan = item.dataset.keterangan || '';
                        if (nama.includes(search) || keterangan.includes(search)) {
                            item.style.display = 'flex';
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    document.getElementById('jenisTransaksiCountModal').textContent = visibleCount +
                        ' jenis transaksi tersedia';
                });
            }
            const searchAgent = document.getElementById('searchAgent');
            if (searchAgent) {
                searchAgent.addEventListener('keyup', function() {
                    const search = this.value.toLowerCase();
                    document.querySelectorAll('.agent-row, .no-agent-row').forEach(function(item) {
                        const agent = item.dataset.agent || '';
                        const name = item.dataset.name || '';
                        if (agent.includes(search) || name.includes(search) || search === '') {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }
            document.querySelectorAll('.hotel-tour-item .hotel-tour-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const parent = this.closest('.hotel-tour-item');
                    const index = parent.dataset.index;
                    const hotelId = parent.dataset.hotelId;
                    if (checkbox.checked) {
                        loadKamarsForHotelTourItem(hotelId, index);
                    } else {
                        const container = document.getElementById('kamarContainer-' + index);
                        if (container) container.innerHTML =
                            '<p class="text-sm text-gray-400 col-span-2 text-center py-4">Centang hotel untuk mengatur kamar</p>';
                        const totalEl = document.getElementById('totalHotelTour-' + index);
                        if (totalEl) totalEl.textContent = 'Rp 0';
                        calculateGrandTotalHotelTour();
                    }
                });
            });
            document.querySelectorAll('.hotel-select').forEach(function(select) {
                select.addEventListener('change', function() {
                    const containerId = this.dataset.container;
                    const totalId = this.dataset.total;
                    loadKamarsWithSelected(this.id, containerId, totalId);
                });
            });
            document.querySelectorAll('.jenis-transaksi-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const parent = this.closest('.jenis-transaksi-modal-item');
                    if (parent) {
                        const inputs = parent.querySelectorAll(
                            'input[type="number"], input[type="text"]');
                        inputs.forEach(function(input) {
                            input.disabled = !checkbox.checked;
                            if (!checkbox.checked) input.classList.add('opacity-50',
                                'bg-gray-50');
                            else input.classList.remove('opacity-50', 'bg-gray-50');
                        });
                    }
                });
                checkbox.dispatchEvent(new Event('change'));
            });
            document.querySelectorAll('.fixed.inset-0.bg-black\\/50').forEach(function(modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) this.classList.add('hidden');
                });
            });
        });

        function openPaketTourHotelModal() {
            document.getElementById('paketTourHotelModal').classList.remove('hidden');
            loadAllKamarsForHotelTour();
        }

        function closePaketTourHotelModal() {
            document.getElementById('paketTourHotelModal').classList.add('hidden');
        }

        function loadAllKamarsForHotelTour() {
            document.querySelectorAll('#paketTourHotelModal .hotel-tour-item').forEach(function(item) {
                const hotelId = item.dataset.hotelId;
                const index = item.dataset.index;
                const checkbox = item.querySelector('.hotel-tour-checkbox');
                if (checkbox && checkbox.checked) loadKamarsForHotelTourItem(hotelId, index);
            });
        }

        function loadKamarsForHotelTourItem(hotelId, index) {
            const container = document.getElementById('kamarContainer-' + index);
            const countElement = document.getElementById('kamarCount-' + index);
            const departureId = {{ $departure->id_departure }};
            if (!hotelId || !container) return;
            container.innerHTML =
                '<p class="text-sm text-gray-400 col-span-2 text-center py-4"><i class="fas fa-spinner fa-spin mr-2"></i> Memuat tipe kamar...</p>';
            fetch(`/transaksional/get-kamars-by-hotel-for-tour/${hotelId}/${departureId}`)
                .then(response => response.json())
                .then(data => {
                    let html = data.html.replace(/INDEX/g, index);
                    container.innerHTML = html;
                    if (countElement) countElement.textContent = data.count + ' tipe kamar';
                    container.querySelectorAll('.kamar-checkbox').forEach(function(checkbox) {
                        checkbox.addEventListener('change', function() {
                            calculateHotelTourTotal(index);
                            const parent = this.closest('.kamar-item');
                            const inputs = parent.querySelectorAll('input:not(.kamar-checkbox)');
                            inputs.forEach(function(input) {
                                input.disabled = !checkbox.checked;
                                if (!checkbox.checked) input.classList.add('opacity-50',
                                    'bg-gray-50');
                                else input.classList.remove('opacity-50', 'bg-gray-50');
                            });
                        });
                        checkbox.dispatchEvent(new Event('change'));
                    });
                    container.querySelectorAll('.kamar-jumlah, .kamar-harga, .kamar-durasi').forEach(function(input) {
                        input.addEventListener('change', function() {
                            calculateHotelTourTotal(index);
                        });
                        input.addEventListener('keyup', function() {
                            calculateHotelTourTotal(index);
                        });
                    });
                    calculateHotelTourTotal(index);
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.innerHTML =
                        '<p class="text-sm text-red-500 col-span-2 text-center py-4">Gagal memuat tipe kamar.</p>';
                    if (countElement) countElement.textContent = 'Error';
                });
        }

        function calculateHotelTourTotal(index) {
            const container = document.getElementById('kamarContainer-' + index);
            if (!container) return 0;
            let total = 0;
            container.querySelectorAll('.kamar-item').forEach(function(item) {
                const checkbox = item.querySelector('.kamar-checkbox');
                if (checkbox && checkbox.checked) {
                    const jumlah = parseInt(item.querySelector('.kamar-jumlah').value) || 0;
                    const hargaInput = item.querySelector('.kamar-harga');
                    let harga = 0;
                    if (hargaInput) {
                        const rawValue = hargaInput.value.replace(/^Rp\s*/, '').replace(/\./g, '');
                        harga = parseInt(rawValue) || 0;
                    }
                    const durasi = parseInt(item.querySelector('.kamar-durasi').value) || 1;
                    total += jumlah * harga * durasi;
                }
            });
            const totalElement = document.getElementById('totalHotelTour-' + index);
            if (totalElement) totalElement.textContent = 'Rp ' + total.toLocaleString('id-ID');
            calculateGrandTotalHotelTour();
            return total;
        }

        function calculateGrandTotalHotelTour() {
            let grandTotal = 0;
            document.querySelectorAll('#paketTourHotelModal .hotel-tour-item').forEach(function(item) {
                const checkbox = item.querySelector('.hotel-tour-checkbox');
                if (checkbox && checkbox.checked) {
                    const index = item.dataset.index;
                    const container = document.getElementById('kamarContainer-' + index);
                    if (container) {
                        container.querySelectorAll('.kamar-item').forEach(function(kamarItem) {
                            const kamarCheckbox = kamarItem.querySelector('.kamar-checkbox');
                            if (kamarCheckbox && kamarCheckbox.checked) {
                                const jumlah = parseInt(kamarItem.querySelector('.kamar-jumlah').value) ||
                                    0;
                                const hargaInput = kamarItem.querySelector('.kamar-harga');
                                let harga = 0;
                                if (hargaInput) {
                                    const rawValue = hargaInput.value.replace(/^Rp\s*/, '').replace(/\./g,
                                        '');
                                    harga = parseInt(rawValue) || 0;
                                }
                                const durasi = parseInt(kamarItem.querySelector('.kamar-durasi').value) ||
                                    1;
                                grandTotal += jumlah * harga * durasi;
                            }
                        });
                    }
                }
            });
            const previewEl = document.getElementById('totalPaketTourHotelPreview');
            if (previewEl) previewEl.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
            return grandTotal;
        }

        function formatRupiahHotelTourKamar(input, kamarId, index) {
            let value = input.value.replace(/[^,\d]/g, '');
            value = value.replace(/^Rp\s*/, '').replace(/\./g, '').replace(/,/g, '.');
            if (value === '' || isNaN(value)) value = '0';
            const number = parseInt(value);
            input.value = 'Rp ' + number.toLocaleString('id-ID');
            const hiddenInput = document.getElementById('tour_kamar_harga_' + kamarId + '_' + index);
            if (hiddenInput) hiddenInput.value = number;
            calculateHotelTourTotal(index);
        }

        function openMaskapaiModal() {
            document.getElementById('maskapaiModal').classList.remove('hidden');
            document.querySelectorAll('.maskapai-harga').forEach(function(input) {
                const hiddenInput = document.getElementById(input.id.replace('display', ''));
                let value = 0;
                if (hiddenInput) value = parseInt(hiddenInput.value) || 0;
                input.value = value > 0 ? 'Rp ' + value.toLocaleString('id-ID') : 'Rp 0';
            });
        }

        function closeMaskapaiModal() {
            document.getElementById('maskapaiModal').classList.add('hidden');
        }

        function openHotelModal() {
            document.getElementById('hotelModal').classList.remove('hidden');
            loadKamarsWithSelected('id_hotel_mekkah', 'kamarContainerMekkah', 'totalHotelMekkah');
            loadKamarsWithSelected('id_hotel_madinah', 'kamarContainerMadinah', 'totalHotelMadinah');
            loadKamarsWithSelected('id_hotel_transit', 'kamarContainerTransit', 'totalHotelTransit');
        }

        function closeHotelModal() {
            document.getElementById('hotelModal').classList.add('hidden');
        }

        function loadKamarsWithSelected(hotelSelectId, containerId, totalId) {
            const hotelId = document.getElementById(hotelSelectId).value;
            const container = document.getElementById(containerId);
            const departureId = {{ $departure->id_departure }};
            if (!hotelId) {
                container.innerHTML =
                    '<p class="text-sm text-gray-400 col-span-2 text-center py-4">Silakan pilih hotel terlebih dahulu untuk melihat tipe kamar</p>';
                return;
            }
            container.innerHTML =
                '<p class="text-sm text-gray-400 col-span-2 text-center py-4"><i class="fas fa-spinner fa-spin mr-2"></i> Memuat tipe kamar...</p>';
            fetch(`/transaksional/get-kamars-by-hotel-with-selected/${hotelId}/${departureId}`)
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = data.html;
                    container.querySelectorAll('.kamar-checkbox').forEach(function(checkbox) {
                        checkbox.addEventListener('change', function() {
                            calculateHotelTotal(containerId, totalId);
                            const parent = this.closest('.kamar-item');
                            const inputs = parent.querySelectorAll('input:not(.kamar-checkbox)');
                            inputs.forEach(function(input) {
                                input.disabled = !checkbox.checked;
                                if (!checkbox.checked) input.classList.add('opacity-50',
                                    'bg-gray-50');
                                else input.classList.remove('opacity-50', 'bg-gray-50');
                            });
                        });
                        checkbox.dispatchEvent(new Event('change'));
                    });
                    container.querySelectorAll('.kamar-jumlah, .kamar-harga, .kamar-durasi').forEach(function(input) {
                        input.addEventListener('change', function() {
                            calculateHotelTotal(containerId, totalId);
                        });
                        input.addEventListener('keyup', function() {
                            calculateHotelTotal(containerId, totalId);
                        });
                    });
                    calculateHotelTotal(containerId, totalId);
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.innerHTML =
                        '<p class="text-sm text-red-500 col-span-2 text-center py-4">Gagal memuat tipe kamar.</p>';
                });
        }

        function calculateHotelTotal(containerId, totalId) {
            const container = document.getElementById(containerId);
            let total = 0;
            container.querySelectorAll('.kamar-item').forEach(function(item) {
                const checkbox = item.querySelector('.kamar-checkbox');
                if (checkbox && checkbox.checked) {
                    const jumlah = parseInt(item.querySelector('.kamar-jumlah').value) || 0;
                    const hargaInput = item.querySelector('.kamar-harga');
                    let harga = 0;
                    if (hargaInput) {
                        const rawValue = hargaInput.value.replace(/^Rp\s*/, '').replace(/\./g, '');
                        harga = parseInt(rawValue) || 0;
                    }
                    const durasi = parseInt(item.querySelector('.kamar-durasi').value) || 1;
                    total += jumlah * harga * durasi;
                }
            });
            document.getElementById(totalId).textContent = 'Rp ' + total.toLocaleString('id-ID');
            return total;
        }

        function openJamaahModal() {
            document.getElementById('jamaahModal').classList.remove('hidden');
            document.getElementById('searchJamaahModal').value = '';
            document.querySelectorAll('.jamaah-modal-item').forEach(function(item) {
                item.style.display = 'flex';
            });
            const allItems = document.querySelectorAll('.jamaah-modal-item');
            let lunasCount = 0,
                belumLunasCount = 0;
            allItems.forEach(function(item) {
                if (item.dataset.status === 'lunas') lunasCount++;
                else belumLunasCount++;
            });
            let countHtml = '<span class="text-gray-600">' + allItems.length + ' jamaah tersedia</span>';
            if (lunasCount > 0) countHtml += ' <span class="text-green-600 font-medium">· ' + lunasCount + ' Lunas</span>';
            if (belumLunasCount > 0) countHtml += ' <span class="text-red-600 font-bold">· ' + belumLunasCount +
                ' Belum Lunas</span>';
            document.getElementById('jamaahCountModal').innerHTML = countHtml;
        }

        function closeJamaahModal() {
            document.getElementById('jamaahModal').classList.add('hidden');
        }

        function removeJamaah(departureId, jamaahId) {
            if (confirm('Yakin ingin menghapus jamaah ini dari keberangkatan?')) {
                document.getElementById('remove-jamaah-form-' + departureId + '-' + jamaahId).submit();
            }
        }

        function selectAllJamaah() {
            document.querySelectorAll('#jamaahModalList .jamaah-modal-item input[type="checkbox"]').forEach(function(
                checkbox) {
                checkbox.checked = true;
            });
        }

        function deselectAllJamaah() {
            document.querySelectorAll('#jamaahModalList .jamaah-modal-item input[type="checkbox"]').forEach(function(
                checkbox) {
                checkbox.checked = false;
            });
        }

        function openPerlengkapanModal() {
            document.getElementById('perlengkapanModal').classList.remove('hidden');
        }

        function closePerlengkapanModal() {
            document.getElementById('perlengkapanModal').classList.add('hidden');
        }

        function removePerlengkapan(departureId, departurePerlengkapanId) {
            if (confirm('Yakin ingin menghapus perlengkapan ini dari departure?')) {
                document.getElementById('remove-perlengkapan-form-' + departureId + '-' + departurePerlengkapanId).submit();
            }
        }

        function openPerlengkapanDetailModal(perlengkapanId, refreshOnly = false) {
            const modal = document.getElementById('perlengkapanDetailModal');
            const content = document.getElementById('perlengkapanDetailContent');
            if (!refreshOnly) {
                content.innerHTML =
                    `<div class="flex items-center justify-center py-12"><div class="text-center"><i class="fas fa-spinner fa-spin text-3xl text-yellow-500 mb-3"></i><p class="text-gray-500">Memuat data...</p></div></div>`;
                modal.classList.remove('hidden');
            }
            fetch(`/transaksional/get-perlengkapan-detail/${perlengkapanId}`)
                .then(response => response.json())
                .then(data => {
                    content.innerHTML = data.html;
                    document.getElementById('perlengkapanDetailTitle').textContent = 'Detail: ' + data
                        .nama_perlengkapan;
                    const checkAll = document.getElementById(`perlengkapan-check-all-${perlengkapanId}`);
                    if (checkAll) {
                        checkAll.addEventListener('change', function() {
                            document.querySelectorAll(
                                    `#perlengkapan-jamaah-list-${perlengkapanId} .perlengkapan-jamaah-checkbox`)
                                .forEach(cb => cb.checked = this.checked);
                        });
                    }
                    document.querySelectorAll(
                        `#perlengkapan-jamaah-list-${perlengkapanId} .perlengkapan-jamaah-checkbox`).forEach(cb => {
                        cb.addEventListener('change', function() {
                            const all = document.querySelectorAll(
                                `#perlengkapan-jamaah-list-${perlengkapanId} .perlengkapan-jamaah-checkbox`
                            );
                            const checked = document.querySelectorAll(
                                `#perlengkapan-jamaah-list-${perlengkapanId} .perlengkapan-jamaah-checkbox:checked`
                            );
                            if (checkAll) checkAll.checked = all.length === checked.length;
                        });
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (!refreshOnly) {
                        content.innerHTML =
                            `<div class="text-center py-12"><i class="fas fa-exclamation-circle text-3xl text-red-500 mb-3"></i><p class="text-gray-500">Gagal memuat data. Silakan coba lagi.</p></div>`;
                    }
                });
        }

        function closePerlengkapanDetailModal() {
            document.getElementById('perlengkapanDetailModal').classList.add('hidden');
        }

        function openJenisTransaksiModal() {
            document.getElementById('jenisTransaksiModal').classList.remove('hidden');
            document.getElementById('searchJenisTransaksiModal').value = '';
            document.querySelectorAll('.jenis-transaksi-modal-item').forEach(function(item) {
                item.style.display = 'flex';
            });
            document.getElementById('jenisTransaksiCountModal').textContent =
                '{{ $jenisTransaksiOptions->count() }} jenis transaksi tersedia';
            document.querySelectorAll('#jenisTransaksiModalList .jenis-harga').forEach(function(input) {
                const hiddenId = input.id.replace('display_', '');
                const hiddenInput = document.getElementById(hiddenId);
                let value = 0;
                if (hiddenInput) value = parseInt(hiddenInput.value) || 0;
                input.value = value > 0 ? 'Rp ' + value.toLocaleString('id-ID') : 'Rp 0';
            });
            const defaultHargaDisplay = document.getElementById('default_harga_total_display');
            const defaultHarga = document.getElementById('default_harga_total');
            if (defaultHargaDisplay && defaultHarga) {
                let value = parseInt(defaultHarga.value) || 0;
                defaultHargaDisplay.value = value > 0 ? 'Rp ' + value.toLocaleString('id-ID') : 'Rp 0';
            }
        }

        function closeJenisTransaksiModal() {
            document.getElementById('jenisTransaksiModal').classList.add('hidden');
        }

        function removeJenisTransaksi(departureId, jenisTransaksiId) {
            if (confirm('Yakin ingin menghapus jenis transaksi ini dari departure?')) {
                document.getElementById('remove-jenis-transaksi-form-' + departureId + '-' + jenisTransaksiId).submit();
            }
        }

        function editJenisTransaksiHarga(departureId, jenisTransaksiId, currentHarga) {
            const modal = document.getElementById('editJenisTransaksiModal');
            const form = document.getElementById('editJenisTransaksiForm');
            form.action = '/transaksional/departure/' + departureId + '/jenis-transaksi/' + jenisTransaksiId + '/harga';
            const displayInput = document.getElementById('edit_harga_total_display');
            const hiddenInput = document.getElementById('edit_harga_total');
            if (displayInput && hiddenInput) {
                const number = parseInt(currentHarga) || 0;
                displayInput.value = 'Rp ' + number.toLocaleString('id-ID');
                hiddenInput.value = number;
            }
            modal.classList.remove('hidden');
        }

        function closeEditJenisTransaksiModal() {
            document.getElementById('editJenisTransaksiModal').classList.add('hidden');
        }

        function fillAllHarga() {
            const defaultHargaDisplay = document.getElementById('default_harga_total_display');
            const defaultHarga = document.getElementById('default_harga_total');
            if (!defaultHarga.value || defaultHarga.value == '0') {
                alert('Silakan isi default harga total terlebih dahulu!');
                return;
            }
            document.querySelectorAll('#jenisTransaksiModalList .jenis-harga').forEach(function(input) {
                if (!input.disabled) {
                    input.value = defaultHargaDisplay.value;
                    const hiddenId = input.id.replace('display_', '');
                    const hiddenInput = document.getElementById(hiddenId);
                    if (hiddenInput) hiddenInput.value = defaultHarga.value;
                }
            });
        }

        function fillAllCatatan() {
            const defaultCatatan = document.getElementById('default_catatan').value;
            document.querySelectorAll('#jenisTransaksiModalList .jenis-catatan').forEach(function(input) {
                if (!input.disabled) input.value = defaultCatatan;
            });
        }

        function selectAllJenisTransaksi() {
            document.querySelectorAll('#jenisTransaksiModalList .jenis-transaksi-modal-item input[type="checkbox"]')
                .forEach(function(checkbox) {
                    checkbox.checked = true;
                    checkbox.dispatchEvent(new Event('change'));
                });
        }

        function deselectAllJenisTransaksi() {
            document.querySelectorAll('#jenisTransaksiModalList .jenis-transaksi-modal-item input[type="checkbox"]')
                .forEach(function(checkbox) {
                    checkbox.checked = false;
                    checkbox.dispatchEvent(new Event('change'));
                });
        }

        function openJenisTransaksiDetailModal(jenisTransaksiId, refreshOnly = false) {
            const modal = document.getElementById('jenisTransaksiDetailModal');
            const content = document.getElementById('jenisTransaksiDetailContent');
            if (!refreshOnly) {
                content.innerHTML =
                    `<div class="flex items-center justify-center py-12"><div class="text-center"><i class="fas fa-spinner fa-spin text-3xl text-yellow-500 mb-3"></i><p class="text-gray-500">Memuat data...</p></div></div>`;
                modal.classList.remove('hidden');
            }
            fetch(`/transaksional/get-jenis-transaksi-detail/${jenisTransaksiId}`)
                .then(response => response.json())
                .then(data => {
                    content.innerHTML = data.html;
                    document.getElementById('jenisTransaksiDetailTitle').textContent = 'Detail: ' + data
                        .nama_jenis_transaksi;
                    const checkAll = document.getElementById(`jenis-transaksi-check-all-${jenisTransaksiId}`);
                    if (checkAll) {
                        checkAll.addEventListener('change', function() {
                            document.querySelectorAll(
                                `#jenis-transaksi-jamaah-list-${jenisTransaksiId} .jenis-transaksi-jamaah-checkbox`
                            ).forEach(cb => cb.checked = this.checked);
                        });
                    }
                    document.querySelectorAll(
                            `#jenis-transaksi-jamaah-list-${jenisTransaksiId} .jenis-transaksi-jamaah-checkbox`)
                        .forEach(cb => {
                            cb.addEventListener('change', function() {
                                const all = document.querySelectorAll(
                                    `#jenis-transaksi-jamaah-list-${jenisTransaksiId} .jenis-transaksi-jamaah-checkbox`
                                );
                                const checked = document.querySelectorAll(
                                    `#jenis-transaksi-jamaah-list-${jenisTransaksiId} .jenis-transaksi-jamaah-checkbox:checked`
                                );
                                if (checkAll) checkAll.checked = all.length === checked.length;
                            });
                        });
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (!refreshOnly) {
                        content.innerHTML =
                            `<div class="text-center py-12"><i class="fas fa-exclamation-circle text-3xl text-red-500 mb-3"></i><p class="text-gray-500">Gagal memuat data. Silakan coba lagi.</p></div>`;
                    }
                });
        }

        function closeJenisTransaksiDetailModal() {
            document.getElementById('jenisTransaksiDetailModal').classList.add('hidden');
        }

        const CSRF_TOKEN = '{{ csrf_token() }}';

        function togglePerlengkapanStatusJamaah(departurePerlengkapanId, jamaahId, currentStatus) {
            const newStatus = currentStatus === 'Sudah Diterima' ? 'Belum Diterima' : 'Sudah Diterima';
            fetch(`/transaksional/departure/perlengkapan-status-jamaah/${departurePerlengkapanId}/${jamaahId}/ajax`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status_terima: newStatus
                })
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    openPerlengkapanDetailModal(departurePerlengkapanId, true);
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Gagal', 'error');
                }
            }).catch(err => {
                console.error(err);
                showToast('Terjadi kesalahan', 'error');
            });
        }

        function bulkUpdatePerlengkapanStatus(departurePerlengkapanId, departureId, statusTerima) {
            const checkboxes = document.querySelectorAll(
                `#perlengkapan-jamaah-list-${departurePerlengkapanId} .perlengkapan-jamaah-checkbox:checked`);
            if (checkboxes.length === 0) {
                showToast('Pilih minimal 1 jamaah terlebih dahulu', 'warning');
                return;
            }
            const jamaahIds = Array.from(checkboxes).map(cb => parseInt(cb.value));
            fetch(`/transaksional/departure/${departureId}/perlengkapan/${departurePerlengkapanId}/status-jamaah-bulk`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    jamaah_ids: jamaahIds,
                    status_terima: statusTerima
                })
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    openPerlengkapanDetailModal(departurePerlengkapanId, true);
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Gagal', 'error');
                }
            }).catch(err => {
                console.error(err);
                showToast('Terjadi kesalahan', 'error');
            });
        }

        function toggleJenisTransaksiStatusJamaah(departureJenisTransaksiId, jamaahId, currentStatus) {
            const newStatus = currentStatus === 'Sudah Diterima' ? 'Belum Diterima' : 'Sudah Diterima';
            fetch(`/transaksional/departure/jenis-transaksi-status-jamaah/${departureJenisTransaksiId}/${jamaahId}/ajax`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status_terima: newStatus
                })
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    openJenisTransaksiDetailModal(departureJenisTransaksiId, true);
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Gagal', 'error');
                }
            }).catch(err => {
                console.error(err);
                showToast('Terjadi kesalahan', 'error');
            });
        }

        function bulkUpdateJenisTransaksiStatus(departureJenisTransaksiId, departureId, statusTerima) {
            const checkboxes = document.querySelectorAll(
                `#jenis-transaksi-jamaah-list-${departureJenisTransaksiId} .jenis-transaksi-jamaah-checkbox:checked`);
            if (checkboxes.length === 0) {
                showToast('Pilih minimal 1 jamaah terlebih dahulu', 'warning');
                return;
            }
            const jamaahIds = Array.from(checkboxes).map(cb => parseInt(cb.value));
            fetch(`/transaksional/departure/${departureId}/jenis-transaksi/${departureJenisTransaksiId}/status-jamaah-bulk`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    jamaah_ids: jamaahIds,
                    status_terima: statusTerima
                })
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    openJenisTransaksiDetailModal(departureJenisTransaksiId, true);
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Gagal', 'error');
                }
            }).catch(err => {
                console.error(err);
                showToast('Terjadi kesalahan', 'error');
            });
        }

        function showToast(message, type = 'success') {
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };
            const icons = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };
            const toast = document.createElement('div');
            toast.className =
                `fixed top-4 right-4 z-[9999] px-4 py-3 rounded-lg shadow-lg text-white text-sm font-medium ${colors[type] || colors.success} flex items-center gap-2`;
            toast.innerHTML = `<i class="fas ${icons[type] || icons.success}"></i> ${message}`;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.transition = 'opacity 0.3s';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function openCatatanModal() {
            document.getElementById('catatanModal').classList.remove('hidden');
        }

        function closeCatatanModal() {
            document.getElementById('catatanModal').classList.add('hidden');
        }

        function recalculate(id) {
            if (confirm('Yakin ingin menghitung ulang data keuangan?')) {
                document.getElementById('recalculate-form').submit();
            }
        }

        function formatRupiahMaskapai(input, hiddenId) {
            let value = input.value.replace(/[^,\d]/g, '').replace(/^Rp\s*/, '').replace(/\./g, '').replace(/,/g, '.');
            if (value === '' || isNaN(value)) value = '0';
            const number = parseInt(value);
            input.value = 'Rp ' + number.toLocaleString('id-ID');
            const hiddenInput = document.getElementById(hiddenId);
            if (hiddenInput) hiddenInput.value = number;
        }

        function formatRupiahJenisTransaksi(input, hiddenId) {
            let value = input.value.replace(/[^,\d]/g, '').replace(/^Rp\s*/, '').replace(/\./g, '').replace(/,/g, '.');
            if (value === '' || isNaN(value)) value = '0';
            const number = parseInt(value);
            input.value = 'Rp ' + number.toLocaleString('id-ID');
            const hiddenInput = document.getElementById(hiddenId);
            if (hiddenInput) hiddenInput.value = number;
        }

        function formatRupiahHotel(input, kamarId) {
            let value = input.value.replace(/[^,\d]/g, '').replace(/^Rp\s*/, '').replace(/\./g, '').replace(/,/g, '.');
            if (value === '' || isNaN(value)) value = '0';
            const number = parseInt(value);
            input.value = 'Rp ' + number.toLocaleString('id-ID');
            const hiddenInput = document.getElementById('kamar_harga_' + kamarId);
            if (hiddenInput) hiddenInput.value = number;
            const container = input.closest('.kamar-item').closest('.grid');
            if (container) {
                const containerId = container.id;
                const totalId = containerId.replace('kamarContainer', 'totalHotel');
                calculateHotelTotal(containerId, totalId);
            }
        }

        // ============================================
        // UPGRADE HOTEL — INPUT HARGA ASLI
        // ============================================
        function openHargaAsliModal() {
            document.getElementById('hargaAsliModal').classList.remove('hidden');
        }

        function closeHargaAsliModal() {
            document.getElementById('hargaAsliModal').classList.add('hidden');
        }

        function formatRupiahHargaAsli(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (value === '') {
                input.value = '';
                updatePreviewSelisih(0);
                return;
            }
            let number = parseInt(value);
            if (isNaN(number)) number = 0;
            input.value = number.toLocaleString('id-ID');
            updatePreviewSelisih(number);
        }

        // LOGIKA BARU: Selisih = Dibayar jamaah − Modal
        function updatePreviewSelisih(hargaAsli) {
            const totalUpgrade = {{ $departure->total_upgrade_hotel_gabungan }};
            const selisih = totalUpgrade - hargaAsli; // dibayar − modal
            const selisihEl = document.getElementById('previewSelisih');
            const labelEl = document.getElementById('previewSelisihLabel');
            if (!selisihEl || !labelEl) return;
            if (selisih > 0) {
                selisihEl.textContent = '+ Rp ' + selisih.toLocaleString('id-ID');
                selisihEl.className = 'font-bold text-green-600';
                labelEl.innerHTML =
                    '<i class="fas fa-arrow-up text-green-500 mr-1"></i>Akan masuk ke <strong class="text-green-600">pendapatan</strong> (PROFIT)';
            } else if (selisih < 0) {
                selisihEl.textContent = '− Rp ' + Math.abs(selisih).toLocaleString('id-ID');
                selisihEl.className = 'font-bold text-red-600';
                labelEl.innerHTML =
                    '<i class="fas fa-arrow-down text-red-500 mr-1"></i>Akan masuk ke <strong class="text-red-600">pengeluaran</strong> (nombok)';
            } else {
                selisihEl.textContent = 'Rp 0';
                selisihEl.className = 'font-bold text-gray-500';
                labelEl.innerHTML = '<i class="fas fa-equals text-gray-500 mr-1"></i>Tidak ada selisih';
            }
        }

        // ============================================
        // PEMBAYARAN TAK TERDUGA
        // ============================================
        const PTT_STORE_URL =
            "{{ route('transaksional.departure.store-pembayaran-tak-terduga', $departure->id_departure) }}";
        const PTT_UPDATE_URL =
            "{{ route('transaksional.departure.update-pembayaran-tak-terduga', [$departure->id_departure, '__ID__']) }}";

        function openPembayaranTakTerdugaModal() {
            document.getElementById('pembayaranTakTerdugaModalTitle').textContent = 'Tambah Pembayaran Tak Terduga';
            document.getElementById('pembayaranTakTerdugaForm').action = PTT_STORE_URL;
            document.getElementById('pembayaranTakTerdugaMethod').value = 'POST';
            document.getElementById('pembayaranTakTerdugaId').value = '';
            document.getElementById('ptt_nama_pembayaran').value = '';
            document.getElementById('ptt_total_pembayaran').value = '';
            document.getElementById('ptt_keterangan').value = '';
            document.getElementById('pembayaranTakTerdugaSubmitText').textContent = 'Tambah';
            document.getElementById('pembayaranTakTerdugaModal').classList.remove('hidden');
        }

        function closePembayaranTakTerdugaModal() {
            document.getElementById('pembayaranTakTerdugaModal').classList.add('hidden');
        }

        function editPembayaranTakTerduga(id, nama, total, keterangan) {
            document.getElementById('pembayaranTakTerdugaModalTitle').textContent = 'Edit Pembayaran Tak Terduga';
            document.getElementById('pembayaranTakTerdugaForm').action = PTT_UPDATE_URL.replace('__ID__', id);
            document.getElementById('pembayaranTakTerdugaMethod').value = 'PUT';
            document.getElementById('pembayaranTakTerdugaId').value = id;
            document.getElementById('ptt_nama_pembayaran').value = nama;
            document.getElementById('ptt_total_pembayaran').value = parseInt(total).toLocaleString('id-ID');
            document.getElementById('ptt_keterangan').value = keterangan || '';
            document.getElementById('pembayaranTakTerdugaSubmitText').textContent = 'Update';
            document.getElementById('pembayaranTakTerdugaModal').classList.remove('hidden');
        }

        function removePembayaranTakTerduga(departureId, pembayaranId) {
            if (confirm(
                    'Yakin ingin menghapus pembayaran tak terduga ini?\n\nData akan dihapus dan total pengeluaran departure akan dihitung ulang.'
                    )) {
                document.getElementById('remove-ptt-form-' + departureId + '-' + pembayaranId).submit();
            }
        }

        function formatRupiahPtt(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (value === '') {
                input.value = '';
                return;
            }
            let number = parseInt(value);
            if (isNaN(number)) number = 0;
            input.value = number.toLocaleString('id-ID');
        }

        document.getElementById('pembayaranTakTerdugaForm')?.addEventListener('submit', function(e) {
            const input = document.getElementById('ptt_total_pembayaran');
            const raw = input.value.replace(/[^\d]/g, '');
            input.value = raw || '0';
        });
    </script>
@endpush
