{{-- resources/views/jamaahs/pdf-riwayat.blade.php --}}
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
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            padding: 12px;
            background: #fff;
        }

        /* ==========================================
           HEADER
           ========================================== */
        .header {
            text-align: center;
            border-bottom: 3px double #D4A017;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }

        .header .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 2px;
        }

        .header .logo-img {
            max-height: 40px;
            width: auto;
        }

        .header .logo-text {
            font-size: 22px;
            font-weight: bold;
            color: #D4A017;
        }

        .header .subtitle {
            font-size: 12px;
            color: #666;
            margin-top: 2px;
        }

        .header .company-info {
            font-size: 9px;
            color: #555;
            margin-top: 3px;
            line-height: 1.5;
        }

        .header .line {
            width: 80px;
            height: 2px;
            background: #D4A017;
            margin: 5px auto 0;
        }

        /* ==========================================
           HALAMAN LOGO - FULL PAGE
           ========================================== */
        .logo-page {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
            padding: 20px;
        }

        .logo-page .logo-big {
            max-height: 300px;
            max-width: 300px;
            width: auto;
            height: auto;
            margin-bottom: 30px;
            object-fit: contain;
        }

        .logo-page .company-name-big {
            font-size: 36px;
            font-weight: bold;
            color: #D4A017;
            letter-spacing: 3px;
            margin-bottom: 10px;
        }

        .logo-page .company-address-big {
            font-size: 14px;
            color: #555;
            line-height: 1.8;
        }

        .logo-page .company-phone-big {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
        }

        .logo-page .separator-line {
            width: 100px;
            height: 3px;
            background: #D4A017;
            margin: 15px auto;
        }

        /* ==========================================
           TABLES
           ========================================== */
        .info-table,
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            overflow: hidden;
        }

        .info-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        .info-table tr:last-child td {
            border-bottom: none;
        }

        .info-table .label {
            width: 130px;
            font-weight: 600;
            color: #666;
            font-size: 10px;
            background: #f8f9fa;
        }

        .info-table .value {
            color: #333;
            font-weight: 500;
            font-size: 11px;
        }

        /* ==========================================
           SUMMARY TABLE
           ========================================== */
        .summary-table td {
            padding: 10px 12px;
            text-align: center;
            border-right: 1px solid #e9ecef;
            width: 33.33%;
        }

        .summary-table td:last-child {
            border-right: none;
        }

        .summary-table .label {
            font-size: 9px;
            color: #888;
            text-transform: uppercase;
            font-weight: 600;
            display: block;
        }

        .summary-table .value {
            font-size: 18px;
            font-weight: 700;
            display: block;
            margin-top: 2px;
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
           RIWAYAT TABLE
           ========================================== */
        .table-wrapper {
            border: 1px solid #e9ecef;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 14px;
        }

        .table-wrapper .table-header {
            background: #D4A017;
            padding: 6px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-wrapper .table-header h3 {
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            margin: 0;
        }

        .table-wrapper .table-header .count {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 9px;
        }

        table.riwayat {
            width: 100%;
            border-collapse: collapse;
        }

        table.riwayat thead {
            background: #f8f9fa;
        }

        table.riwayat th {
            padding: 6px 8px;
            text-align: left;
            font-size: 8px;
            text-transform: uppercase;
            color: #666;
            font-weight: 700;
            border-bottom: 2px solid #e9ecef;
        }

        table.riwayat td {
            padding: 6px 8px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
            font-size: 9px;
        }

        table.riwayat tbody tr:last-child td {
            border-bottom: none;
        }

        /* ==========================================
           BADGES
           ========================================== */
        .badge-status,
        .badge-jenis,
        .badge-metode {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 8px;
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

        .badge-bank {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-ewallet {
            background: #fae8ff;
            color: #6b21a8;
        }

        /* ==========================================
           FOOTER TABLE
           ========================================== */
        .table-footer {
            background: #f8f9fa;
            padding: 6px 14px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            border-top: 2px solid #D4A017;
        }

        .table-footer .total-label {
            font-weight: 600;
            color: #555;
            font-size: 10px;
            margin-right: 20px;
        }

        .table-footer .total-amount {
            font-weight: 700;
            color: #28a745;
            font-size: 14px;
        }

        /* ==========================================
           SYARAT DAN KETENTUAN
           ========================================== */
        .syarat-wrapper {
            border: 1px solid #e9ecef;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 14px;
        }

        .syarat-wrapper .syarat-header {
            background: #2c3e50;
            padding: 6px 14px;
        }

        .syarat-wrapper .syarat-header h3 {
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            margin: 0;
        }

        .syarat-content {
            padding: 10px 14px;
            background: #fafafa;
        }

        .syarat-content ol {
            padding-left: 18px;
            margin: 0;
            font-size: 8px;
            line-height: 1.6;
            color: #444;
        }

        .syarat-content ol li {
            margin-bottom: 1px;
        }

        .syarat-content ul {
            padding-left: 18px;
            margin: 1px 0 0 0;
            list-style-type: disc;
            font-size: 8px;
            color: #444;
        }

        /* ==========================================
           LAMPIRAN BUKTI
           ========================================== */
        .page-break {
            page-break-after: always;
        }

        .lampiran-header {
            text-align: center;
            padding: 12px 0 10px;
            border-bottom: 2px solid #D4A017;
            margin-bottom: 14px;
        }

        .lampiran-header h2 {
            color: #D4A017;
            font-size: 16px;
            font-weight: 700;
        }

        .lampiran-header p {
            color: #888;
            font-size: 10px;
            margin-top: 2px;
        }

        .lampiran-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .lampiran-item {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
        }

        .lampiran-item .lampiran-info {
            font-size: 8px;
            color: #666;
            margin-bottom: 4px;
        }

        .lampiran-item .lampiran-info strong {
            color: #333;
        }

        .lampiran-item .lampiran-image {
            margin: 4px 0;
        }

        .lampiran-item .lampiran-image img {
            max-width: 100%;
            max-height: 120px;
            border-radius: 4px;
            border: 1px solid #eee;
            object-fit: contain;
        }

        .lampiran-item .lampiran-file {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .lampiran-item .lampiran-file .icon {
            font-size: 24px;
            display: block;
        }

        .lampiran-item .lampiran-file .filename {
            font-size: 8px;
            color: #666;
            margin-top: 2px;
        }

        .lampiran-item .lampiran-amount {
            font-size: 12px;
            font-weight: 700;
            color: #28a745;
            margin-top: 4px;
        }

        .lampiran-item .lampiran-keterangan {
            font-size: 8px;
            color: #888;
            margin-top: 2px;
        }

        .lampiran-item .lampiran-date {
            font-size: 7px;
            color: #999;
        }

        /* ==========================================
           FOOTER - PAGE
           ========================================== */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            border-top: 2px solid #D4A017;
            margin-top: 8px;
            font-size: 8px;
            color: #666;
        }

        .footer-table td {
            padding: 6px 10px;
            vertical-align: middle;
        }

        .footer-table .footer-left {
            text-align: left;
            font-weight: 600;
            color: #D4A017;
            font-size: 10px;
            width: 30%;
        }

        .footer-table .footer-left .company {
            color: #D4A017;
            font-weight: 700;
        }

        .footer-table .footer-left .separator {
            color: #ddd;
            margin: 0 4px;
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
            font-size: 7px;
        }

        .footer-table .footer-right {
            text-align: right;
            width: 40%;
        }

        .footer-table .footer-right .date {
            color: #555;
            font-weight: 500;
            font-size: 9px;
        }

        .footer-table .footer-right .separator {
            color: #ddd;
            margin: 0 4px;
        }

        .footer-table .footer-right .note {
            color: #bbb;
            font-size: 7px;
        }

        /* ==========================================
           UTILITY
           ========================================== */
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

        /* ==========================================
           PAGE MARGIN
           ========================================== */
        @page {
            margin: 10px;
            margin-bottom: 40px;
        }

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
    HALAMAN 1: LOGO FULL PAGE
    ========================================== -->
    @php
        $logoPath = public_path('assets/logo.PNG');
        $logoBase64 = '';
        $logoExists = false;
        if (file_exists($logoPath) && filesize($logoPath) < 200000) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
            $logoExists = true;
        }
    @endphp

    <div class="logo-page">
        @if ($logoExists && $logoBase64)
            <img src="{{ $logoBase64 }}" alt="Logo PT. BERKAH ARRUM HARAMAIN" class="logo-big">
        @else
            <div style="font-size:60px; color:#D4A017; font-weight:bold; margin-bottom:30px;">🏛️</div>
        @endif
        <div class="company-name-big">PT. BERKAH ARRUM HARAMAIN</div>
        <div class="separator-line"></div>
        <div class="company-address-big">
            Jl. Munif Rahman 2, No. 33, Kota Palu<br>
            Sulawesi Tengah, Indonesia
        </div>
        <div class="company-phone-big">
            Telp: 082237100071 - 082215127565<br>
            Email: arrumtourpalu@gmail.com
        </div>
        <div style="margin-top:20px; font-size:12px; color:#999;">
            Dokumen Resmi - Riwayat Pembayaran Jamaah
        </div>
    </div>

    <!-- ==========================================
    PAGE BREAK - HALAMAN BERIKUTNYA
    ========================================== -->
    <div class="page-break"></div>

    <!-- ==========================================
    HEADER DENGAN LOGO KECIL
    ========================================== -->
    <div class="header">
        <div class="logo-container">
            @if ($logoBase64)
                <img src="{{ $logoBase64 }}" alt="Logo PT. BERKAH ARRUM HARAMAIN" class="logo-img">
            @endif
            <div class="logo-text">PT. BERKAH ARRUM HARAMAIN</div>
        </div>
        <div class="subtitle">Riwayat Pembayaran Jamaah</div>
        <div class="company-info">
            Jl. Munif Rahman 2, No. 33, Kota Palu | Sulawesi Tengah, Indonesia
            <br>
            Telp: 082237100071 - 082215127565 | Email: arrumtourpalu@gmail.com
        </div>
        <div class="line"></div>
    </div>

    <!-- ==========================================
    INFO JAMAHAH
    ========================================== -->
    <table class="info-table">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="value"><strong>{{ $jamaah->nama_lengkap }}</strong></td>
            <td class="label">ID Keberangkatan</td>
            <td class="value">{{ $jamaah->id_keberangkatan ?? '-' }}</td>
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
                @if ($jamaah->catatan_tambahan)
                    <span style="margin-left:8px; font-size:9px; color:#888;">
                        <i>Catatan: {{ $jamaah->catatan_tambahan }}</i>
                    </span>
                @endif
            </td>
        </tr>
    </table>

    <!-- ==========================================
    SUMMARY
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
                    <th style="width:5%;">#</th>
                    <th style="width:15%;">Tanggal</th>
                    <th style="width:20%;">Metode</th>
                    <th style="width:15%;">Jenis</th>
                    <th style="width:20%;text-align:right;">Jumlah</th>
                    <th style="width:25%;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaksis as $index => $transaksi)
                    <tr>
                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                        <td>{{ $transaksi->tanggal_transaksi_formatted ?? ($transaksi->tanggal_transaksi ? date('d/m/Y', strtotime($transaksi->tanggal_transaksi)) : '-') }}
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
                        <td>{{ $transaksi->keterangan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted" style="padding:15px;">
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
    LAMPIRAN BUKTI PEMBAYARAN (HALAMAN BARU)
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
                        $transaksi->tanggal_transaksi_formatted ??
                        ($transaksi->tanggal_transaksi ? date('d/m/Y', strtotime($transaksi->tanggal_transaksi)) : '-');
                    $jumlah = number_format($transaksi->jumlah_bayar, 0, ',', '.');
                    $jenis = $transaksi->jenisTransaksi->nama ?? '-';
                @endphp
                <div class="lampiran-item">
                    <div class="lampiran-info">
                        <strong>#{{ $loop->iteration }}</strong> - {{ $jenis }}
                        <div class="lampiran-date">{{ $tanggal }}</div>
                    </div>

                    @if ($isImage && $buktiBase64)
                        <div class="lampiran-image">
                            <img src="{{ $buktiBase64 }}" alt="Bukti {{ $loop->iteration }}">
                        </div>
                    @elseif($isPdf)
                        <div class="lampiran-file">
                            <span class="icon">📄 PDF</span>
                            <div class="filename">{{ Str::limit($buktiName, 25) }}</div>
                            <div style="font-size: 7px; color: #999; margin-top: 2px;">File PDF</div>
                        </div>
                    @else
                        <div class="lampiran-file">
                            <span class="icon">📎 FILE</span>
                            <div class="filename">{{ Str::limit($buktiName, 25) }}</div>
                            <div style="font-size: 7px; color: #999; margin-top: 2px;">File</div>
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
    SYARAT DAN KETENTUAN (HALAMAN BARU)
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
                <li>Pihak travel berhak membatalkan keberangkatan jika terjadi kondisi tertentu.</li>
                <li>Dana jamaah akan dikembalikan atau dialihkan ke jadwal berikutnya sesuai kesepakatan.</li>
                <li>Perubahan jadwal oleh jamaah dapat dilakukan sesuai ketersediaan.</li>
                <li>Perubahan dari pihak travel akan diinformasikan kepada jamaah secepatnya.</li>
                <li>Jamaah wajib melengkapi dokumen (paspor, vaksin, dll) sesuai ketentuan.</li>
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
    FOOTER
    ========================================== -->
    <table class="footer-table">
        <tr>
            <td class="footer-left">
                <span class="company">PT. BERKAH ARRUM HARAMAIN</span>
                <span class="separator">|</span>
                <span class="printed-by">Dicetak oleh: {{ $dicetak_oleh ?? (auth()->user()->name ?? 'System') }}</span>
            </td>
            <td class="footer-center"></td>
            <td class="footer-right">
                <span class="date">{{ $tanggal_cetak ?? date('d/m/Y H:i') }}</span>
                <span class="separator">|</span>
                <span class="note">Dokumen sah</span>
            </td>
        </tr>
    </table>

</body>

</html>
