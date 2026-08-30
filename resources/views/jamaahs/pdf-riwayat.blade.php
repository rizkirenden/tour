<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Riwayat Pembayaran - {{ $jamaah->nama_lengkap }}</title>
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
           HEADER
           ========================================== */
        .header {
            text-align: center;
            border-bottom: 3px double #D4A017;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .header .logo {
            font-size: 26px;
            font-weight: bold;
            color: #D4A017;
            letter-spacing: 1px;
        }

        .header .logo span {
            color: #333;
        }

        .header .subtitle {
            font-size: 13px;
            color: #666;
            margin-top: 2px;
        }

        .header .line {
            width: 80px;
            height: 2px;
            background: #D4A017;
            margin: 6px auto 0;
        }

        /* ==========================================
           INFO JAMAHAH - TABEL HORIZONTAL
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
            width: 160px;
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
            padding: 8px 12px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #666;
            font-weight: 700;
            border-bottom: 2px solid #e9ecef;
        }

        table.riwayat td {
            padding: 8px 12px;
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

        /* Bukti */
        .bukti-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
        }

        .bukti-image {
            max-width: 55px;
            max-height: 40px;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            object-fit: cover;
        }

        .bukti-pdf {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            background: #dc3545;
            color: #fff;
            padding: 1px 8px;
            border-radius: 4px;
            font-size: 7px;
            font-weight: 600;
        }

        .bukti-file {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            background: #6c757d;
            color: #fff;
            padding: 1px 8px;
            border-radius: 4px;
            font-size: 7px;
            font-weight: 600;
        }

        .bukti-name {
            font-size: 6px;
            color: #888;
            max-width: 55px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .bukti-empty {
            color: #ccc;
            font-size: 9px;
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
    HEADER
    ========================================== -->
    <div class="header">
        <div class="logo">Arrum <span>Tour</span></div>
        <div class="subtitle">Riwayat Pembayaran Jamaah</div>
        <div class="line"></div>
    </div>

    <!-- ==========================================
    INFO JAMAHAH - TABEL HORIZONTAL
    ========================================== -->
    <table class="info-table">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="value"><strong>{{ $jamaah->nama_lengkap }}</strong></td>
            <td class="label">ID Keberangkatan</td>
            <td class="value">{{ $jamaah->id_keberangkatan }}</td>
        </tr>
        <tr>
            <td class="label">Produk Paket</td>
            <td class="value">{{ $jamaah->produk_paket }}</td>
            <td class="label">Kota Asal</td>
            <td class="value">{{ $jamaah->kota_asal ?? '-' }}</td>
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
                <span class="badge-status {{ $statusColors[$jamaah->status_pembayaran] ?? 'badge-belum' }}">
                    {{ $jamaah->status_pembayaran }}
                </span>
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
                    {{ number_format($jamaah->total_tagihan_setelah_diskon, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">Total Dibayar</span>
                <span class="value dibayar">Rp {{ number_format($jamaah->total_dibayar, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">Sisa Tagihan</span>
                <span class="value sisa">Rp {{ number_format($jamaah->sisa_tagihan, 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    <!-- ==========================================
    TABLE RIWAYAT PEMBAYARAN
    ========================================== -->
    <div class="table-wrapper">
        <div class="table-header">
            <h3>Riwayat Pembayaran</h3>
            <span class="count">{{ $transaksis->count() }} Transaksi</span>
        </div>

        <table class="riwayat">
            <thead>
                <tr>
                    <th style="width: 25px;">#</th>
                    <th style="width: 75px;">Tanggal</th>
                    <th style="width: 95px;">Metode</th>
                    <th style="width: 75px;">Jenis</th>
                    <th style="width: 110px; text-align: right;">Jumlah</th>
                    <th style="width: 100px; text-align: center;">Bukti</th>
                    <th style="width: 100px;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaksis as $index => $transaksi)
                    <tr>
                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                        <td>{{ $transaksi->tanggal_transaksi_formatted ?? $transaksi->tanggal_transaksi->format('d/m/Y') }}
                        </td>
                        <td>
                            @php
                                $metode = $transaksi->metodePembayaran;
                                if ($metode) {
                                    if ($metode->jenis_pembayaran == 'bank_transfer') {
                                        echo $metode->kode_bank . ' - ' . $metode->nama_bank;
                                    } elseif ($metode->jenis_pembayaran == 'e_wallet') {
                                        echo $metode->e_wallet_type . ' - ' . $metode->nomor_telepon;
                                    } else {
                                        echo 'Cash / Tunai';
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
                        <td class="text-center">
                            @php
                                $hasBukti = $transaksi->bukti_exists ?? false;
                                $buktiBase64 = $transaksi->bukti_base64 ?? null;
                                $isImage = in_array($transaksi->bukti_extension ?? '', [
                                    'jpg',
                                    'jpeg',
                                    'png',
                                    'gif',
                                    'webp',
                                    'bmp',
                                ]);
                                $isPdf = ($transaksi->bukti_extension ?? '') == 'pdf';
                                $buktiName = $transaksi->bukti_name ?? '';
                            @endphp

                            @if ($hasBukti && $buktiBase64)
                                <div class="bukti-container">
                                    @if ($isImage)
                                        <img src="{{ $buktiBase64 }}" alt="Bukti" class="bukti-image">
                                        <span class="bukti-name">{{ Str::limit($buktiName, 12) }}</span>
                                    @elseif($isPdf)
                                        <div class="bukti-pdf">PDF</div>
                                        <span class="bukti-name">{{ Str::limit($buktiName, 12) }}</span>
                                    @else
                                        <div class="bukti-file">File</div>
                                        <span class="bukti-name">{{ Str::limit($buktiName, 12) }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="bukti-empty">-</span>
                            @endif
                        </td>
                        <td>{{ $transaksi->keterangan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted" style="padding: 20px;">
                            Belum ada riwayat pembayaran
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="table-footer">
            <span class="total-label">TOTAL PEMBAYARAN</span>
            <span class="total-amount">Rp {{ number_format($total_transaksi, 0, ',', '.') }}</span>
        </div>
    </div>

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
            <h2>Lampiran Bukti Pembayaran</h2>
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
                        $transaksi->tanggal_transaksi_formatted ?? $transaksi->tanggal_transaksi->format('d/m/Y');
                    $jumlah = number_format($transaksi->jumlah_bayar, 0, ',', '.');
                    $jenis = $transaksi->jenisTransaksi->nama ?? '-';
                @endphp
                <div class="lampiran-item">
                    <div class="lampiran-info">
                        <strong>#{{ $loop->iteration }}</strong> - {{ $jenis }}
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
    FOOTER - TABEL HORIZONTAL DI SETIAP HALAMAN
    ========================================== -->
    <table class="footer-table">
        <tr>
            <td class="footer-left">
                <span class="company">Arrum Tour</span>
                <span class="separator">|</span>
                <span class="printed-by">Dicetak oleh: {{ $dicetak_oleh }}</span>
            </td>
            <td class="footer-center"></td>
            <td class="footer-right">
                <span class="date">{{ $tanggal_cetak }}</span>
                <span class="separator">|</span>
                <span class="note">Dokumen sah sebagai bukti</span>
            </td>
        </tr>
    </table>

</body>

</html>
