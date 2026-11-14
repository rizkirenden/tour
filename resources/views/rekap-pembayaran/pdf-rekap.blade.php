<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekap Pembayaran - {{ ucfirst($tipe) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 10px;
            color: #333;
            padding: 12px;
        }

        .header {
            width: 100%;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 3px double #D4A017;
        }

        .header-container {
            width: 100%;
            overflow: hidden;
        }

        .header-logo-left {
            float: left;
            width: 70px;
            margin-right: 12px;
            padding-top: 22px;
        }

        .header-logo-left .logo-img {
            max-height: 65px;
            max-width: 70px;
            object-fit: contain;
            display: block;
        }

        .header-content {
            margin-left: 82px;
            text-align: left;
        }

        .header-content .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #1a1a1a;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .header-detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-detail-table td {
            padding: 1px 0;
            font-size: 9px;
            color: #333;
            line-height: 1.4;
            vertical-align: top;
        }

        .header-detail-table td.detail-label {
            width: 55px;
            font-weight: bold;
            color: #000;
            padding-right: 3px;
        }

        .header-line {
            width: 100%;
            height: 3px;
            background: #D4A017;
            margin-top: 6px;
            clear: both;
        }

        .title-block {
            margin-bottom: 10px;
            padding: 8px 12px;
            background: #D4A017;
            border-radius: 6px;
        }

        .title-block h1 {
            font-size: 14px;
            font-weight: bold;
            color: #fff;
            letter-spacing: 0.5px;
        }

        .title-block .subtitle {
            font-size: 9px;
            color: #fff;
            opacity: 0.9;
            margin-top: 2px;
        }

        .info-date-filter {
            margin-bottom: 10px;
            padding: 6px 10px;
            background: #dbeafe;
            border: 1px solid #93c5fd;
            border-radius: 6px;
            font-size: 8.5px;
            color: #1e40af;
        }

        .info-date-filter strong {
            color: #1e3a8a;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            overflow: hidden;
        }

        .summary-table td {
            padding: 10px 12px;
            text-align: center;
            border-right: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .summary-table td:last-child {
            border-right: none;
        }

        .summary-table .label {
            font-size: 8px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            display: block;
        }

        .summary-table .value {
            font-size: 13px;
            font-weight: 700;
            margin-top: 3px;
            display: block;
        }

        .summary-table .value.green {
            color: #28a745;
        }

        .summary-table .value.red {
            color: #dc3545;
        }

        .summary-table .value.amber {
            color: #d97706;
        }

        .summary-table .value.blue {
            color: #2563eb;
        }

        .summary-table .value.gold {
            color: #D4A017;
        }

        .table-wrapper {
            border: 1px solid #e9ecef;
            border-radius: 6px;
            overflow: hidden;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data thead {
            background: #f8f9fa;
        }

        table.data th {
            padding: 6px 8px;
            text-align: left;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #666;
            font-weight: 700;
            border-bottom: 2px solid #e9ecef;
        }

        table.data td {
            padding: 6px 8px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
            font-size: 8.5px;
        }

        table.data tbody tr:last-child td {
            border-bottom: none;
        }

        table.data tbody tr:nth-child(even) {
            background: #fafbfc;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-green {
            color: #28a745;
            font-weight: 600;
        }

        .text-red {
            color: #dc3545;
            font-weight: 600;
        }

        .text-amber {
            color: #d97706;
            font-weight: 600;
        }

        .text-muted {
            color: #999;
        }

        .badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 10px;
            font-size: 7.5px;
            font-weight: 600;
        }

        .badge-mandiri {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-keluarga {
            background: #f3e8ff;
            color: #7c3aed;
        }

        .badge-masuk {
            background: #d4edda;
            color: #155724;
        }

        .badge-refund {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-lunas {
            background: #d4edda;
            color: #155724;
        }

        .badge-dp {
            background: #fff3cd;
            color: #856404;
        }

        .badge-setoran {
            background: #cce5ff;
            color: #004085;
        }

        .badge-belum {
            background: #f8d7da;
            color: #721c24;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
            border-top: 2px solid #D4A017;
            margin-top: 12px;
            font-size: 8px;
            color: #666;
        }

        .footer-table td {
            padding: 6px 8px;
            vertical-align: middle;
        }

        .footer-table .footer-left {
            text-align: left;
            font-weight: 600;
            color: #D4A017;
            font-size: 9px;
            width: 40%;
        }

        .footer-table .footer-left .printed-by {
            color: #666;
            font-weight: 400;
            font-size: 8px;
        }

        .footer-table .footer-right {
            text-align: right;
            width: 60%;
            color: #555;
        }

        .footer-table .footer-right .date {
            font-weight: 500;
            font-size: 8.5px;
        }

        @page {
            margin: 12px;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header">
        <div class="header-container">
            <div class="header-logo-left">
                @if (!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" alt="Logo" class="logo-img">
                @else
                    <div style="font-size:36px; color:#D4A017; font-weight:bold;">🏛️</div>
                @endif
            </div>
            <div class="header-content">
                <div class="company-name">PT. BERKAH ARRUM HARAMAIN</div>
                <table class="header-detail-table">
                    <tr>
                        <td class="detail-label">Alamat</td>
                        <td>: Jl. Munif Rahman 2. No. 33. Kota Palu, Sulawesi Tengah, Indonesia</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Telepon</td>
                        <td>: 082237100071 - 082215127565</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Email</td>
                        <td>: arrumtourpalu@gmail.com</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="header-line"></div>
    </div>

    {{-- TITLE --}}
    <div class="title-block">
        <h1>REKAP PEMBAYARAN — {{ strtoupper($tipe) }}</h1>
        <div class="subtitle">
            Dicetak: {{ $tanggal_cetak }} · Oleh: {{ $dicetak_oleh }}
        </div>
    </div>

    {{-- INFO FILTER TANGGAL --}}
    @if (!empty($hasDateFilter) && $hasDateFilter)
        <div class="info-date-filter">
            📅 <strong>Filter Tanggal Transaksi Aktif:</strong>
            {{ !empty($filters['tanggal_dari']) ? \Carbon\Carbon::parse($filters['tanggal_dari'])->format('d/m/Y') : '...' }}
            s/d
            {{ !empty($filters['tanggal_sampai']) ? \Carbon\Carbon::parse($filters['tanggal_sampai'])->format('d/m/Y') : '...' }}
            — Angka Dibayar/Refund/Sisa dihitung dari transaksi dalam rentang ini.
        </div>
    @endif

    {{-- SUMMARY CARDS --}}
    <table class="summary-table">
        <tr>
            <td>
                <span class="label">Total Jamaah</span>
                <span class="value blue">{{ $summary['jamaah']['total'] }}</span>
            </td>
            <td>
                <span class="label">Total Keluarga</span>
                <span class="value blue">{{ $summary['keluarga']['total'] }}</span>
            </td>
            <td>
                <span class="label">Total Dibayar (Jamaah)</span>
                <span class="value green">Rp
                    {{ number_format($summary['jamaah']['total_dibayar'], 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">Sisa Tagihan (Jamaah)</span>
                <span class="value red">Rp {{ number_format($summary['jamaah']['sisa_tagihan'], 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">Kelebihan (Jamaah)</span>
                <span class="value amber">Rp
                    {{ number_format($summary['jamaah']['sisa_kelebihan'], 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    {{-- TABLE DATA --}}
    <div class="table-wrapper">
        <table class="data">
            <thead>
                @if ($tipe === 'jamaah')
                    <tr>
                        <th style="width:3%;">#</th>
                        <th style="width:20%;">Nama</th>
                        <th style="width:12%;">NIK</th>
                        <th style="width:14%;">Sumber</th>
                        <th style="width:13%;">Produk</th>
                        <th style="width:10%; text-align:right;">Tagihan</th>
                        <th style="width:9%; text-align:right;">Dibayar</th>
                        <th style="width:6%; text-align:right;">Refund</th>
                        <th style="width:8%; text-align:right;">Sisa</th>
                        <th style="width:8%; text-align:right;">Kelebihan</th>
                        <th style="width:7%; text-align:center;">Status</th>
                    </tr>
                @elseif($tipe === 'keluarga')
                    <tr>
                        <th style="width:3%;">#</th>
                        <th style="width:10%;">Kode</th>
                        <th style="width:22%;">Nama Keluarga</th>
                        <th style="width:5%; text-align:center;">Anggota</th>
                        <th style="width:13%;">Produk</th>
                        <th style="width:10%; text-align:right;">Tagihan</th>
                        <th style="width:9%; text-align:right;">Dibayar</th>
                        <th style="width:6%; text-align:right;">Refund</th>
                        <th style="width:8%; text-align:right;">Sisa</th>
                        <th style="width:8%; text-align:right;">Kelebihan</th>
                        <th style="width:6%; text-align:center;">Status</th>
                    </tr>
                @else
                    <tr>
                        <th style="width:3%;">#</th>
                        <th style="width:9%;">Tanggal</th>
                        <th style="width:18%;">Jamaah</th>
                        <th style="width:15%;">Keluarga</th>
                        <th style="width:13%;">Metode</th>
                        <th style="width:12%;">Jenis</th>
                        <th style="width:8%; text-align:center;">Mutasi</th>
                        <th style="width:10%; text-align:right;">Jumlah</th>
                        <th style="width:12%;">Keterangan</th>
                    </tr>
                @endif
            </thead>
            <tbody>
                @forelse($data as $idx => $item)
                    @if ($tipe === 'jamaah')
                        @php
                            $statusClass = match ($item->status_pembayaran) {
                                'Lunas' => 'badge-lunas',
                                'DP' => 'badge-dp',
                                'Setoran' => 'badge-setoran',
                                default => 'badge-belum',
                            };
                        @endphp
                        <tr>
                            <td class="text-center text-muted">{{ $idx + 1 }}</td>
                            <td><strong>{{ $item->nama_lengkap }}</strong></td>
                            <td>{{ $item->nik ?? '-' }}</td>
                            <td>
                                @if ($item->id_keluarga)
                                    <span
                                        class="badge badge-keluarga">{{ $item->keluarga->nama_keluarga ?? '-' }}</span>
                                @else
                                    <span class="badge badge-mandiri">Mandiri</span>
                                @endif
                            </td>
                            <td>{{ $item->produk_paket }}</td>
                            <td class="text-right">
                                {{ number_format($item->total_tagihan_setelah_diskon, 0, ',', '.') }}</td>
                            <td class="text-right text-green">{{ number_format($item->total_dibayar, 0, ',', '.') }}
                            </td>
                            <td class="text-right text-amber">
                                {{ $item->total_refund > 0 ? number_format($item->total_refund, 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-right text-red">{{ number_format($item->sisa_tagihan, 0, ',', '.') }}</td>
                            <td class="text-right text-amber">
                                {{ $item->sisa_kelebihan_bayar > 0 ? number_format($item->sisa_kelebihan_bayar, 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-center"><span
                                    class="badge {{ $statusClass }}">{{ $item->status_pembayaran }}</span></td>
                        </tr>
                    @elseif($tipe === 'keluarga')
                        @php
                            $statusClass = match ($item->status_pembayaran) {
                                'Lunas' => 'badge-lunas',
                                'DP' => 'badge-dp',
                                'Setoran' => 'badge-setoran',
                                default => 'badge-belum',
                            };
                        @endphp
                        <tr>
                            <td class="text-center text-muted">{{ $idx + 1 }}</td>
                            <td><strong>{{ $item->kode_keluarga }}</strong></td>
                            <td>{{ $item->nama_keluarga }}</td>
                            <td class="text-center">{{ $item->jamaahs_count }}</td>
                            <td>{{ $item->produk_paket }}</td>
                            <td class="text-right">
                                {{ number_format($item->total_tagihan_setelah_diskon, 0, ',', '.') }}</td>
                            <td class="text-right text-green">{{ number_format($item->total_dibayar, 0, ',', '.') }}
                            </td>
                            <td class="text-right text-amber">
                                {{ $item->total_refund > 0 ? number_format($item->total_refund, 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-right text-red">{{ number_format($item->sisa_tagihan, 0, ',', '.') }}</td>
                            <td class="text-right text-amber">
                                {{ $item->sisa_kelebihan_bayar > 0 ? number_format($item->sisa_kelebihan_bayar, 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-center"><span
                                    class="badge {{ $statusClass }}">{{ $item->status_pembayaran }}</span></td>
                        </tr>
                    @else
                        @php $isRefund = ($item->jenis_mutasi ?? 'masuk') === 'keluar'; @endphp
                        <tr>
                            <td class="text-center text-muted">{{ $idx + 1 }}</td>
                            <td>{{ $item->tanggal_transaksi ? $item->tanggal_transaksi->format('d/m/Y') : '-' }}</td>
                            <td><strong>{{ $item->jamaah->nama_lengkap ?? '-' }}</strong></td>
                            <td>{{ $item->jamaah->keluarga->nama_keluarga ?? '-' }}</td>
                            <td>{{ $item->metodePembayaran->nama_bank ?? ($item->metodePembayaran->jenis_pembayaran ?? '-') }}
                            </td>
                            <td>{{ $item->jenisPembayaran->nama ?? '-' }}</td>
                            <td class="text-center">
                                @if ($isRefund)
                                    <span class="badge badge-refund">Refund</span>
                                @else
                                    <span class="badge badge-masuk">Masuk</span>
                                @endif
                            </td>
                            <td class="text-right {{ $isRefund ? 'text-amber' : 'text-green' }}">
                                {{ $isRefund ? '−' : '' }}{{ number_format($item->jumlah_bayar, 0, ',', '.') }}
                            </td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted" style="padding: 20px;">
                            Tidak ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- FOOTER --}}
    <table class="footer-table">
        <tr>
            <td class="footer-left">
                PT. BERKAH ARRUM HARAMAIN
                <span class="printed-by">| Dicetak oleh: {{ $dicetak_oleh }}</span>
            </td>
            <td class="footer-right">
                <span class="date">{{ $tanggal_cetak }}</span>
                · Total data: {{ $data->count() }} baris
            </td>
        </tr>
    </table>

</body>

</html>
