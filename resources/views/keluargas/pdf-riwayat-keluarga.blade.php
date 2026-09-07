{{-- resources/views/keluargas/pdf-riwayat-keluarga.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Riwayat Pembayaran Keluarga - {{ $keluarga->nama_keluarga }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 12px;
            color: #333;
            padding: 15px;
            background: #fff;
        }

        /* ==========================================
           HEADER WITH LOGO
           ========================================== */
        .header {
            text-align: center;
            border-bottom: 3px double #D4A017;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .header .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 2px;
        }

        .header .logo-img {
            max-height: 50px;
            width: auto;
            object-fit: contain;
        }

        .header .logo-text {
            font-size: 28px;
            font-weight: bold;
            color: #D4A017;
            letter-spacing: 1px;
        }

        .header .subtitle {
            font-size: 13px;
            color: #666;
            margin-top: 2px;
        }

        .header .company-info {
            font-size: 10px;
            color: #555;
            margin-top: 4px;
            line-height: 1.6;
        }

        .header .company-info .separator {
            color: #ccc;
            margin: 0 4px;
        }

        .header .line {
            width: 80px;
            height: 2px;
            background: #D4A017;
            margin: 6px auto 0;
        }

        /* ==========================================
           INFO KELUARGA - TABEL HORIZONTAL
           ========================================== */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
        }

        .info-table td {
            padding: 10px 14px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        .info-table tr:last-child td {
            border-bottom: none;
        }

        .info-table .label {
            width: 140px;
            font-weight: 600;
            color: #666;
            font-size: 11px;
            background: #f8f9fa;
            letter-spacing: 0.3px;
        }

        .info-table .value {
            color: #333;
            font-weight: 500;
            font-size: 12px;
        }

        .info-table .value strong {
            font-weight: 700;
        }

        /* Status Badge */
        .badge-status {
            display: inline-block;
            padding: 3px 16px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-dp {
            background: #fff3cd;
            color: #856404;
        }

        .badge-lunas {
            background: #d4edda;
            color: #155724;
        }

        .badge-setoran {
            background: #cce5ff;
            color: #004085;
        }

        .badge-belum {
            background: #f8d7da;
            color: #721c24;
        }

        /* ==========================================
           SUMMARY - TABEL HORIZONTAL 3 KOLOM
           ========================================== */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
        }

        .summary-table td {
            padding: 14px 16px;
            text-align: center;
            border-right: 1px solid #e9ecef;
            vertical-align: middle;
            width: 33.33%;
        }

        .summary-table td:last-child {
            border-right: none;
        }

        .summary-table .label {
            font-size: 10px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            display: block;
        }

        .summary-table .value {
            font-size: 20px;
            font-weight: 700;
            margin-top: 3px;
            display: block;
        }

        .summary-table .value.total {
            color: #D4A017;
        }

        .summary-table .value.dibayar {
            color: #28a745;
        }

        .summary-table .value.sisa {
            color: #dc3545;
        }

        /* ==========================================
           RINGKASAN PER JAMAHA - TABLE
           ========================================== */
        .jamaah-summary-wrapper {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 18px;
        }

        .jamaah-summary-wrapper .jamaah-header {
            background: #2c3e50;
            padding: 8px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .jamaah-summary-wrapper .jamaah-header h3 {
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .jamaah-summary-wrapper .jamaah-header .count {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 10px;
        }

        table.jamaah-summary {
            width: 100%;
            border-collapse: collapse;
        }

        table.jamaah-summary thead {
            background: #f8f9fa;
        }

        table.jamaah-summary th {
            padding: 8px 10px;
            text-align: left;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #666;
            font-weight: 700;
            border-bottom: 2px solid #e9ecef;
        }

        table.jamaah-summary td {
            padding: 8px 10px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
            font-size: 9px;
        }

        table.jamaah-summary tbody tr:last-child td {
            border-bottom: none;
        }

        table.jamaah-summary tbody tr:hover {
            background: #f8f9fa;
        }

        /* ==========================================
           TABLE RIWAYAT - MODERN
           ========================================== */
        .table-wrapper {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 18px;
        }

        .table-wrapper .table-header {
            background: #D4A017;
            padding: 10px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-wrapper .table-header h3 {
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .table-wrapper .table-header .count {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 10px;
        }

        table.riwayat {
            width: 100%;
            border-collapse: collapse;
        }

        table.riwayat thead {
            background: #f8f9fa;
        }

        table.riwayat th {
            padding: 8px 10px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #666;
            font-weight: 700;
            border-bottom: 2px solid #e9ecef;
        }

        table.riwayat td {
            padding: 8px 10px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
            font-size: 10px;
        }

        table.riwayat tbody tr:last-child td {
            border-bottom: none;
        }

        table.riwayat tbody tr:hover {
            background: #f8f9fa;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .text-muted {
            color: #999;
        }

        /* Badge Jenis Transaksi */
        .badge-jenis {
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 8px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-dp-trans {
            background: #fff3cd;
            color: #856404;
        }

        .badge-lunas-trans {
            background: #d4edda;
            color: #155724;
        }

        .badge-angsur-trans {
            background: #cce5ff;
            color: #004085;
        }

        /* Footer Table */
        .table-footer {
            background: #f8f9fa;
            padding: 8px 16px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            border-top: 2px solid #D4A017;
        }

        .table-footer .total-label {
            font-weight: 600;
            color: #555;
            font-size: 11px;
            margin-right: 30px;
        }

        .table-footer .total-amount {
            font-weight: 700;
            color: #28a745;
            font-size: 15px;
        }

        /* ==========================================
           METODE PEMBAYARAN - TABLE
           ========================================== */
        .metode-wrapper {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 18px;
        }

        .metode-wrapper .metode-header {
            background: #2c3e50;
            padding: 8px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .metode-wrapper .metode-header h3 {
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .metode-wrapper .metode-header .count {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 10px;
        }

        table.metode {
            width: 100%;
            border-collapse: collapse;
        }

        table.metode thead {
            background: #f8f9fa;
        }

        table.metode th {
            padding: 8px 10px;
            text-align: left;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #666;
            font-weight: 700;
            border-bottom: 2px solid #e9ecef;
        }

        table.metode td {
            padding: 8px 10px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
            font-size: 9px;
        }

        table.metode tbody tr:last-child td {
            border-bottom: none;
        }

        table.metode tbody tr:hover {
            background: #f8f9fa;
        }

        .badge-metode {
            display: inline-block;
            padding: 2px 12px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: 600;
        }

        .badge-bank {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-ewallet {
            background: #fae8ff;
            color: #6b21a8;
        }

        /* ==========================================
           SYARAT DAN KETENTUAN
           ========================================== */
        .syarat-wrapper {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 18px;
        }

        .syarat-wrapper .syarat-header {
            background: #2c3e50;
            padding: 8px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .syarat-wrapper .syarat-header h3 {
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .syarat-content {
            padding: 14px 16px;
            background: #fafafa;
        }

        .syarat-content ol {
            padding-left: 20px;
            margin: 0;
            font-size: 9px;
            line-height: 1.8;
            color: #444;
        }

        .syarat-content ol li {
            margin-bottom: 2px;
        }

        .syarat-content ul {
            padding-left: 20px;
            margin: 2px 0 0 0;
            list-style-type: disc;
            font-size: 9px;
            color: #444;
        }

        .syarat-content ul li {
            margin-bottom: 1px;
        }

        /* ==========================================
           FOOTER - TABEL HORIZONTAL DI SETIAP HALAMAN
           ========================================== */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            border-top: 2px solid #D4A017;
            margin-top: 10px;
            font-size: 9px;
            color: #666;
        }

        .footer-table td {
            padding: 8px 10px;
            vertical-align: middle;
        }

        .footer-table .footer-left {
            text-align: left;
            font-weight: 600;
            color: #D4A017;
            font-size: 11px;
            width: 30%;
        }

        .footer-table .footer-left .company {
            color: #D4A017;
            font-weight: 700;
        }

        .footer-table .footer-left .separator {
            color: #ddd;
            font-size: 14px;
            font-weight: 300;
            margin: 0 6px;
        }

        .footer-table .footer-left .printed-by {
            color: #666;
            font-weight: 400;
            font-size: 9px;
        }

        .footer-table .footer-center {
            text-align: center;
            width: 30%;
            color: #bbb;
            font-size: 8px;
        }

        .footer-table .footer-right {
            text-align: right;
            width: 40%;
        }

        .footer-table .footer-right .date {
            color: #555;
            font-weight: 500;
            font-size: 10px;
        }

        .footer-table .footer-right .separator {
            color: #ddd;
            font-size: 14px;
            font-weight: 300;
            margin: 0 6px;
        }

        .footer-table .footer-right .note {
            color: #bbb;
            font-size: 8px;
        }

        /* ==========================================
           LAMPIRAN BUKTI
           ========================================== */
        .page-break {
            page-break-after: always;
        }

        .lampiran-header {
            text-align: center;
            padding: 15px 0 12px;
            border-bottom: 2px solid #D4A017;
            margin-bottom: 18px;
        }

        .lampiran-header h2 {
            color: #D4A017;
            font-size: 18px;
            font-weight: 700;
        }

        .lampiran-header p {
            color: #888;
            font-size: 11px;
            margin-top: 3px;
        }

        .lampiran-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .lampiran-item {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 12px;
            text-align: center;
        }

        .lampiran-item .lampiran-info {
            font-size: 9px;
            color: #666;
            margin-bottom: 6px;
        }

        .lampiran-item .lampiran-info strong {
            color: #333;
        }

        .lampiran-item .lampiran-info .lampiran-date {
            color: #999;
            font-size: 8px;
        }

        .lampiran-item .lampiran-image {
            margin: 6px 0;
        }

        .lampiran-item .lampiran-image img {
            max-width: 100%;
            max-height: 130px;
            border-radius: 6px;
            border: 1px solid #eee;
        }

        .lampiran-item .lampiran-file {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .lampiran-item .lampiran-file .icon {
            font-size: 32px;
            display: block;
        }

        .lampiran-item .lampiran-file .filename {
            font-size: 9px;
            color: #666;
            margin-top: 3px;
        }

        .lampiran-item .lampiran-amount {
            font-size: 13px;
            font-weight: 700;
            color: #28a745;
            margin-top: 6px;
        }

        .lampiran-item .lampiran-keterangan {
            font-size: 8px;
            color: #888;
            margin-top: 2px;
        }

        /* ==========================================
           PAGE MARGIN UNTUK FOOTER
           ========================================== */
        @page {
            margin: 12px;
            margin-bottom: 45px;
        }

        /* CSS Paged Media - Footer di setiap halaman */
        .footer-table {
            position: running(footer);
        }

        @page {
            @bottom-center {
                content: element(footer);
            }
        }
    </style>
</head>

<body>

    <!-- ==========================================
    HEADER WITH LOGO FROM DATABASE
    ========================================== -->
    <div class="header">
        <div class="logo-container">
            @if ($logoExists && $logoBase64)
                <img src="{{ $logoBase64 }}" alt="Logo PT. BERKAH ARRUM HARAMAIN" class="logo-img">
            @else
                <!-- Fallback jika logo tidak ditemukan -->
                <div style="font-size:40px; font-weight:bold; color:#D4A017;">🏛️</div>
            @endif
            <div class="logo-text">PT. BERKAH ARRUM HARAMAIN</div>
        </div>
        <div class="subtitle">Riwayat Pembayaran Keluarga / Kelompok</div>
        <div class="company-info">
            Jl. Munif Rahman 2, No. 33, Kota Palu
            <span class="separator">|</span>
            Sulawesi Tengah, Indonesia
            <br>
            <span style="font-size:9px; color:#666;">
                Telp: 082237100071 - 082215127565
                <span class="separator">|</span>
                Email: arrumtourpalu@gmail.com
            </span>
        </div>
        <div class="line"></div>
    </div>

    <!-- ==========================================
    INFO KELUARGA - TABEL HORIZONTAL
    ========================================== -->
    <table class="info-table">
        <tr>
            <td class="label">Nama Keluarga</td>
            <td class="value"><strong>{{ $keluarga->nama_keluarga }}</strong></td>
            <td class="label">Kode Keluarga</td>
            <td class="value"><strong>{{ $keluarga->kode_keluarga }}</strong></td>
        </tr>
        <tr>
            <td class="label">Produk Paket</td>
            <td class="value">{{ $keluarga->produk_paket }}</td>
            <td class="label">Jumlah Jamaah</td>
            <td class="value"><strong>{{ $keluarga->jamaahs->count() }}</strong> Orang</td>
        </tr>
        <tr>
            <td class="label">Keberangkatan</td>
            <td class="value">
                @if ($keluarga->bulan_keberangkatan)
                    {{ date('F', mktime(0, 0, 0, $keluarga->bulan_keberangkatan, 1)) }}
                    {{ $keluarga->tahun_keberangkatan }}
                @else
                    -
                @endif
            </td>
            <td class="label">Agent</td>
            <td class="value">{{ $keluarga->agent ?? '-' }}
                @if ($keluarga->fee_agent > 0)
                    (Fee: Rp {{ number_format($keluarga->fee_agent, 0, ',', '.') }})
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Status Pembayaran</td>
            <td class="value" colspan="3">
                @php
                    $statusColors = [
                        'Belum Bayar' => 'badge-belum',
                        'DP' => 'badge-dp',
                        'Setoran' => 'badge-setoran',
                        'Lunas' => 'badge-lunas',
                    ];
                @endphp
                <span class="badge-status {{ $statusColors[$keluarga->status_pembayaran] ?? 'badge-belum' }}">
                    {{ $keluarga->status_pembayaran }}
                </span>
                @if ($keluarga->catatan_tambahan)
                    <span style="margin-left:10px; font-size:10px; color:#888;">
                        <i>Catatan: {{ $keluarga->catatan_tambahan }}</i>
                    </span>
                @endif
            </td>
        </tr>
    </table>

    <!-- ==========================================
    SUMMARY - TABEL HORIZONTAL 3 KOLOM
    ========================================== -->
    <table class="summary-table">
        <tr>
            <td>
                <span class="label">Total Tagihan</span>
                <span class="value total">Rp
                    {{ number_format($keluarga->total_tagihan_setelah_diskon, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">Total Dibayar</span>
                <span class="value dibayar">Rp {{ number_format($keluarga->total_dibayar, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">Sisa Tagihan</span>
                <span class="value sisa">Rp {{ number_format($keluarga->sisa_tagihan, 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    <!-- ==========================================
    RINGKASAN PER JAMAAH
    ========================================== -->
    <div class="jamaah-summary-wrapper">
        <div class="jamaah-header">
            <h3>Ringkasan Pembayaran Per Jamaah</h3>
            <span class="count">{{ $keluarga->jamaahs->count() }} Jamaah</span>
        </div>

        <table class="jamaah-summary">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 25%;">Nama Jamaah</th>
                    <th style="width: 12%;">Hubungan</th>
                    <th style="width: 17%; text-align: right;">Tagihan</th>
                    <th style="width: 17%; text-align: right;">Dibayar</th>
                    <th style="width: 17%; text-align: right;">Sisa</th>
                    <th style="width: 12%; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($keluarga->jamaahs as $jamaah)
                    <tr>
                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $jamaah->nama_lengkap }}</strong>
                            @if ($jamaah->is_kepala_keluarga)
                                <span style="color:#D4A017; font-size:8px;"> ★ Kepala</span>
                            @endif
                        </td>
                        <td>{{ $jamaah->hubungan_keluarga ?? '-' }}</td>
                        <td class="text-right">
                            Rp {{ number_format($jamaah->total_tagihan_setelah_diskon, 0, ',', '.') }}
                        </td>
                        <td class="text-right" style="color:#28a745;">
                            Rp {{ number_format($jamaah->total_dibayar, 0, ',', '.') }}
                        </td>
                        <td class="text-right" style="color:#dc3545;">
                            Rp {{ number_format($jamaah->sisa_tagihan, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @php
                                $jStatusColors = [
                                    'Belum Bayar' => 'badge-belum',
                                    'DP' => 'badge-dp',
                                    'Setoran' => 'badge-setoran',
                                    'Lunas' => 'badge-lunas',
                                ];
                            @endphp
                            <span
                                class="badge-status {{ $jStatusColors[$jamaah->status_pembayaran] ?? 'badge-belum' }}"
                                style="font-size:8px; padding:1px 10px;">
                                {{ $jamaah->status_pembayaran }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ==========================================
    TABLE RIWAYAT PEMBAYARAN KELUARGA
    ========================================== -->
    <div class="table-wrapper">
        <div class="table-header">
            <h3>Riwayat Pembayaran Keluarga</h3>
            <span class="count">{{ $transaksis->count() }} Transaksi</span>
        </div>

        <table class="riwayat">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 15%;">Tanggal</th>
                    <th style="width: 25%;">Jamaah</th>
                    <th style="width: 17%;">Metode</th>
                    <th style="width: 15%;">Jenis</th>
                    <th style="width: 23%; text-align: right;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaksis as $index => $transaksi)
                    <tr>
                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                        <td>{{ $transaksi->tanggal_transaksi_formatted ?? ($transaksi->tanggal_transaksi ? date('d/m/Y', strtotime($transaksi->tanggal_transaksi)) : '-') }}
                        </td>
                        <td>
                            <span style="font-weight:600; font-size:9px;">
                                {{ $transaksi->jamaah->nama_lengkap ?? '-' }}
                            </span>
                            @if ($transaksi->jamaah->is_kepala_keluarga ?? false)
                                <span style="color:#D4A017; font-size:7px;"> ★</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $metode = $transaksi->metodePembayaran;
                                if ($metode) {
                                    if ($metode->jenis_pembayaran == 'bank_transfer') {
                                        echo $metode->kode_bank ?? 'Bank';
                                    } elseif ($metode->jenis_pembayaran == 'e_wallet') {
                                        echo $metode->e_wallet_type ?? 'E-Wallet';
                                    } else {
                                        echo 'Cash';
                                    }
                                } else {
                                    echo '-';
                                }
                            @endphp
                        </td>
                        <td>
                            @php
                                $jenisKode = $transaksi->jenisTransaksi->kode ?? '';
                                $jenisClass =
                                    $jenisKode == 'DP'
                                        ? 'badge-dp-trans'
                                        : ($jenisKode == 'LUNAS'
                                            ? 'badge-lunas-trans'
                                            : 'badge-angsur-trans');
                            @endphp
                            <span class="badge-jenis {{ $jenisClass }}">
                                {{ $transaksi->jenisTransaksi->nama ?? '-' }}
                            </span>
                        </td>
                        <td class="text-right"><strong>Rp
                                {{ number_format($transaksi->jumlah_bayar, 0, ',', '.') }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted" style="padding: 20px;">
                            Belum ada riwayat pembayaran untuk keluarga ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="table-footer">
            <span class="total-label">TOTAL PEMBAYARAN KELUARGA</span>
            <span class="total-amount">Rp
                {{ number_format($total_transaksi ?? $keluarga->total_dibayar, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- ==========================================
    DAFTAR METODE PEMBAYARAN (HALAMAN BARU)
    ========================================== -->
    @php
        $filteredMetode = isset($metodePembayarans)
            ? $metodePembayarans->filter(function ($item) {
                return $item->jenis_pembayaran != 'cash';
            })
            : collect();
    @endphp

    @if ($filteredMetode->count() > 0)
        <div class="page-break"></div>

        <div class="metode-wrapper">
            <div class="metode-header">
                <h3>Daftar Metode Pembayaran Tersedia</h3>
                <span class="count">{{ $filteredMetode->count() }} Metode</span>
            </div>

            <table class="metode">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 18%;">Jenis Pembayaran</th>
                        <th style="width: 27%;">Bank / E-Wallet</th>
                        <th style="width: 25%;">Nomor Rekening / Telepon</th>
                        <th style="width: 25%;">Atas Nama</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($filteredMetode as $index => $metode)
                        @php
                            $badgeClass = match ($metode->jenis_pembayaran) {
                                'bank_transfer' => 'badge-bank',
                                'e_wallet' => 'badge-ewallet',
                                default => 'badge-bank',
                            };
                            $jenisLabel = match ($metode->jenis_pembayaran) {
                                'bank_transfer' => 'Bank Transfer',
                                'e_wallet' => 'E-Wallet',
                                default => '-',
                            };
                        @endphp
                        <tr>
                            <td class="text-center text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge-metode {{ $badgeClass }}">
                                    {{ $jenisLabel }}
                                </span>
                            </td>
                            <td>
                                @if ($metode->jenis_pembayaran == 'bank_transfer')
                                    <strong>{{ $metode->nama_bank }}</strong>
                                    <span style="color:#888; font-size:8px;">({{ $metode->kode_bank ?? '-' }})</span>
                                @elseif($metode->jenis_pembayaran == 'e_wallet')
                                    <strong>{{ $metode->e_wallet_type }}</strong>
                                @else
                                    <span style="color:#888;">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($metode->jenis_pembayaran == 'bank_transfer')
                                    {{ $metode->nomor_rekening ?? '-' }}
                                @elseif($metode->jenis_pembayaran == 'e_wallet')
                                    {{ $metode->nomor_telepon ?? '-' }}
                                @else
                                    <span style="color:#888;">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($metode->jenis_pembayaran == 'bank_transfer')
                                    {{ $metode->atas_nama ?? '-' }}
                                @else
                                    <span style="color:#888;">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- ==========================================
    LAMPIRAN BUKTI PEMBAYARAN (HALAMAN TERPISAH)
    ========================================== -->
    @php
        $transaksiDenganBukti = $transaksis->filter(function ($t) {
            return ($t->bukti_exists ?? false) && !empty($t->bukti_base64);
        });
    @endphp

    @if ($transaksiDenganBukti->count() > 0)
        <div class="page-break"></div>

        <div class="lampiran-header">
            <h2>Lampiran Bukti Pembayaran Keluarga</h2>
            <p>{{ $transaksiDenganBukti->count() }} Bukti pembayaran dari {{ $transaksis->count() }} transaksi</p>
        </div>

        <div class="lampiran-grid">
            @foreach ($transaksiDenganBukti as $index => $transaksi)
                @php
                    $isImage = in_array($transaksi->bukti_extension ?? '', [
                        'jpg',
                        'jpeg',
                        'png',
                        'gif',
                        'webp',
                        'bmp',
                    ]);
                    $isPdf = ($transaksi->bukti_extension ?? '') == 'pdf';
                    $buktiBase64 = $transaksi->bukti_base64 ?? '';
                    $buktiName = $transaksi->bukti_name ?? 'file';
                    $tanggal =
                        $transaksi->tanggal_transaksi_formatted ??
                        ($transaksi->tanggal_transaksi ? date('d/m/Y', strtotime($transaksi->tanggal_transaksi)) : '-');
                    $jumlah = number_format($transaksi->jumlah_bayar, 0, ',', '.');
                    $jenis = $transaksi->jenisTransaksi->nama ?? '-';
                    $jamaahName = $transaksi->jamaah->nama_lengkap ?? 'Unknown';
                @endphp
                <div class="lampiran-item">
                    <div class="lampiran-info">
                        <strong>#{{ $loop->iteration }}</strong> - {{ $jenis }}
                        <div style="font-size:8px; color:#666; margin-top:2px;">
                            {{ $jamaahName }}
                        </div>
                        <div class="lampiran-date">{{ $tanggal }}</div>
                    </div>

                    @if ($isImage)
                        <div class="lampiran-image">
                            <img src="{{ $buktiBase64 }}" alt="Bukti {{ $loop->iteration }}">
                        </div>
                    @elseif($isPdf)
                        <div class="lampiran-file">
                            <span class="icon">PDF</span>
                            <div class="filename">{{ Str::limit($buktiName, 25) }}</div>
                            <div style="font-size: 8px; color: #999; margin-top: 3px;">File PDF</div>
                        </div>
                    @else
                        <div class="lampiran-file">
                            <span class="icon">FILE</span>
                            <div class="filename">{{ Str::limit($buktiName, 25) }}</div>
                            <div style="font-size: 8px; color: #999; margin-top: 3px;">File</div>
                        </div>
                    @endif

                    <div class="lampiran-amount">Rp {{ $jumlah }}</div>
                    @if ($transaksi->keterangan)
                        <div class="lampiran-keterangan">{{ $transaksi->keterangan }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- ==========================================
    SYARAT DAN KETENTUAN (HALAMAN TERPISAH)
    ========================================== -->
    <div class="page-break"></div>

    <div class="syarat-wrapper">
        <div class="syarat-header">
            <h3>SYARAT DAN KETENTUAN</h3>
        </div>
        <div class="syarat-content">
            <ol>
                <li>Jamaah wajib melakukan pembayaran sesuai nominal dan tanggal jatuh tempo yang tertera pada invoice.
                </li>
                <li>Pembayaran dianggap sah setelah dana diterima di rekening resmi perusahaan.</li>
                <li>DP yang telah dibayarkan bersifat mengikat dan menjadi bagian dari total biaya paket.</li>
                <li>DP tidak dapat dikembalikan (non-refundable), kecuali dalam kondisi tertentu sesuai kebijakan
                    perusahaan.</li>
                <li>Pelunasan biaya umroh wajib dilakukan maksimal 45 hari sebelum tanggal keberangkatan.</li>
                <li>Pembatalan yang dilakukan oleh jamaah akan dikenakan biaya pembatalan dengan ketentuan:
                    <ul>
                        <li>≥ 30 hari sebelum keberangkatan: potongan 25%.</li>
                        <li>15–29 hari sebelum keberangkatan: potongan 35%.</li>
                        <li>&lt; 14 hari sebelum keberangkatan: tidak ada pengembalian dana 100%.</li>
                    </ul>
                </li>
                <li>Pihak travel berhak membatalkan keberangkatan jika terjadi kondisi tertentu (misalnya kuota tidak
                    terpenuhi atau kebijakan pemerintah).</li>
                <li>Dana jamaah akan dikembalikan atau dialihkan ke jadwal berikutnya sesuai kesepakatan.</li>
                <li>Perubahan jadwal oleh jamaah dapat dilakukan sesuai ketersediaan (jika ada).</li>
                <li>Perubahan dari pihak travel akan diinformasikan kepada jamaah secepatnya.</li>
                <li>Jamaah wajib melengkapi dokumen (paspor, vaksin, dll) sesuai ketentuan yang berlaku.</li>
                <li>Keterlambatan atau ketidaklengkapannya dokumen menjadi tanggung jawab jamaah.</li>
                <li>Keadaan di luar kendali (bencana alam, wabah, kebijakan pemerintah, dll) yang menyebabkan perubahan
                    jadwal atau pembatalan tidak menjadi tanggung jawab penuh pihak travel.</li>
                <li>Jamaah wajib mengikuti aturan perjalanan, termasuk ketentuan maskapai, imigrasi, dan aturan di Arab
                    Saudi.</li>
                <li>Segala pelanggaran menjadi tanggung jawab pribadi jamaah.</li>
                <li>Hal-hal yang belum diatur akan diselesaikan secara musyawarah dan kesepakatan bersama.</li>
            </ol>
        </div>
    </div>

    <!-- ==========================================
    FOOTER - TABEL HORIZONTAL DI SETIAP HALAMAN
    ========================================== -->
    <table class="footer-table">
        <tr>
            <td class="footer-left">
                <span class="company">PT. BERKAH ARRUM HARAMAIN</span>
                <span class="separator">|</span>
                <span class="printed-by">Dicetak oleh:
                    {{ $dicetak_oleh ?? (auth()->user()->name ?? 'System') }}</span>
            </td>
            <td class="footer-center"></td>
            <td class="footer-right">
                <span class="date">{{ $tanggal_cetak ?? date('d/m/Y H:i') }}</span>
                <span class="separator">|</span>
                <span class="note">Dokumen sah sebagai bukti</span>
            </td>
        </tr>
    </table>

</body>

</html>
