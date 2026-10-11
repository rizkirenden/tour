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
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 12px;
            color: #333;
            padding: 15px;
            background: #fff;
        }

        /* ==========================================
           HEADER DENGAN LOGO KIRI + INFO (SEJAJAR)
           ========================================== */
        .header {
            width: 100%;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 3px double #D4A017;
        }

        .header-container {
            width: 100%;
            overflow: hidden;
        }

        .header-logo-left {
            float: left;
            width: 90px;
            margin-right: 15px;
            padding-top: 37px;
        }

        .header-logo-left .logo-img {
            max-height: 85px;
            max-width: 90px;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
        }

        .header-content {
            margin-left: 105px;
            text-align: left;
        }

        .header-content .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #1a1a1a;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            line-height: 1.2;
        }

        .header-detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-detail-table td {
            padding: 1px 0;
            font-size: 10px;
            color: #333;
            line-height: 1.5;
            vertical-align: top;
            text-align: left;
            white-space: nowrap;
        }

        .header-detail-table td.detail-label {
            width: 60px;
            font-weight: bold;
            color: #000;
            padding-right: 4px;
        }

        .header-detail-table td.detail-value {
            color: #333;
        }

        .header-line {
            width: 100%;
            height: 3px;
            background: #D4A017;
            margin-top: 8px;
            clear: both;
        }

        /* ==========================================
           INFO TABLE
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

        /* ==========================================
           KEBERANGKATAN BADGES
           ========================================== */
        .keb-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            background: #dbeafe;
            color: #1e40af;
            margin-right: 4px;
            margin-bottom: 2px;
        }

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
           FOOTER
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
           TTD & CAP
           ========================================== */
        .ttd-cap-wrapper {
            width: 100%;
            margin-top: 30px;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .ttd-cap-table {
            width: 100%;
            border-collapse: collapse;
        }

        .ttd-cap-spacer {
            width: 55%;
        }

        .ttd-cap-content {
            width: 45%;
            text-align: center;
            vertical-align: top;
        }

        .ttd-cap-location {
            font-size: 11px;
            color: #333;
            margin-bottom: 4px;
        }

        .ttd-cap-company {
            font-size: 11px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 10px;
        }

        .ttd-cap-signature-area {
            position: relative;
            height: 110px;
            width: 100%;
            margin-bottom: 8px;
        }

        /* TTD di TENGAH */
        .ttd-cap-signature-area .ttd-image {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-height: 90px;
            max-width: 150px;
            z-index: 2;
        }

        /* Cap geser kanan, overlap ~30% dengan TTD */
        .ttd-cap-signature-area .cap-image {
            position: absolute;
            top: 55%;
            left: 60%;
            transform: translate(-50%, -50%);
            max-height: 100px;
            max-width: 100px;
            opacity: 0.85;
            z-index: 1;
        }

        .ttd-cap-name {
            font-size: 11px;
            color: #1a1a1a;
            border-top: 1px solid #333;
            padding-top: 4px;
            margin-top: 4px;
        }

        .ttd-cap-jabatan {
            font-size: 10px;
            color: #666;
            margin-top: 2px;
        }

        /* ==========================================
           PAGE MARGIN
           ========================================== */
        @page {
            margin: 12px;
            margin-bottom: 45px;
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
         LEMBAR 1: INVOICE
         ========================================== -->

    <!-- HEADER DENGAN LOGO KIRI + INFO -->
    <div class="header">
        <div class="header-container">

            {{-- LOGO DI KIRI --}}
            <div class="header-logo-left">
                @if (!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" alt="Logo" class="logo-img">
                @else
                    <div style="font-size:50px; color:#D4A017; font-weight:bold;">🏛️</div>
                @endif
            </div>

            {{-- INFO PERUSAHAAN --}}
            <div class="header-content">
                <div class="company-name">PT. BERKAH ARRUM HARAMAIN</div>

                <table class="header-detail-table">
                    <tr>
                        <td class="detail-label">Alamat</td>
                        <td class="detail-value">: Jl. Munif Rahman 2. No. 33. Kota Palu, Sulawesi Tengah, Indonesia
                        </td>
                    </tr>
                    <tr>
                        <td class="detail-label">Telepon</td>
                        <td class="detail-value">: 082237100071 - 082215127565</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Email</td>
                        <td class="detail-value">: arrumtourpalu@gmail.com</td>
                    </tr>
                </table>
            </div>

        </div>

        <div class="header-line"></div>
    </div>

    <!-- INFO JAMAAH -->
    @php
        $bulanNama = [
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

        $statusColors = [
            'Belum Bayar' => 'badge-belum',
            'DP' => 'badge-dp',
            'Setoran' => 'badge-setoran',
            'Lunas' => 'badge-lunas',
        ];

        $keberangkatanSorted = $jamaah->keberangkatan->sortBy([
            'pivot.tahun_keberangkatan',
            'pivot.bulan_keberangkatan',
        ]);
    @endphp

    <table class="info-table">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="value"><strong>{{ $jamaah->nama_lengkap }}</strong></td>
            <td class="label">ID Keberangkatan</td>
            <td class="value"><strong>{{ $jamaah->id_keberangkatan ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td class="label">Produk Paket</td>
            <td class="value">{{ $jamaah->produk_paket }}</td>
            <td class="label">Kota Asal</td>
            <td class="value">{{ $jamaah->kota_asal ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Bulan Keberangkatan</td>
            <td class="value" colspan="3">
                @forelse ($keberangkatanSorted as $keb)
                    <span class="keb-badge">
                        {{ $bulanNama[$keb->pivot->bulan_keberangkatan] ?? '-' }}
                        {{ $keb->pivot->tahun_keberangkatan ?? '-' }}
                    </span>
                @empty
                    @if ($jamaah->bulan_keberangkatan && $jamaah->tahun_keberangkatan)
                        <span class="keb-badge">
                            {{ $bulanNama[$jamaah->bulan_keberangkatan] ?? '-' }}
                            {{ $jamaah->tahun_keberangkatan }}
                        </span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                @endforelse
            </td>
        </tr>
        <tr>
            <td class="label">Status Pembayaran</td>
            <td class="value">
                <span class="badge-status {{ $statusColors[$jamaah->status_pembayaran] ?? 'badge-belum' }}">
                    {{ $jamaah->status_pembayaran }}
                </span>
            </td>
            <td class="label">Catatan</td>
            <td class="value">
                <span style="font-size:11px; color:#888;">
                    <i>{{ $jamaah->catatan_tambahan ?? '-' }}</i>
                </span>
            </td>
        </tr>
    </table>

    <!-- SUMMARY -->
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

    <!-- TABLE RIWAYAT PEMBAYARAN -->
    <div class="table-wrapper">
        <div class="table-header">
            <h3>Riwayat Pembayaran</h3>
            <span class="count">{{ $transaksis->count() }} Transaksi</span>
        </div>

        <table class="riwayat">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 15%;">Tanggal</th>
                    <th style="width: 22%;">Metode</th>
                    <th style="width: 15%;">Jenis</th>
                    <th style="width: 20%; text-align: right;">Jumlah</th>
                    <th style="width: 23%;">Keterangan</th>
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
                        <td colspan="6" class="text-center text-muted" style="padding: 20px;">
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
         LEMBAR 2: SYARAT DAN KETENTUAN
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
         LEMBAR 3: LAMPIRAN BUKTI PEMBAYARAN
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
                            <div style="font-size: 8px; color: #999; margin-top: 3px;">File PDF</div>
                        </div>
                    @else
                        <div class="lampiran-file">
                            <span class="icon">📎 FILE</span>
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
         TANDA TANGAN & CAP PERUSAHAAN
         ========================================== -->
    @if (!empty($ttdBase64) || !empty($capBase64))
        <div class="ttd-cap-wrapper">
            <table class="ttd-cap-table">
                <tr>
                    <td class="ttd-cap-spacer"></td>
                    <td class="ttd-cap-content">
                        <div class="ttd-cap-location">
                            Palu, {{ $tanggal_cetak ? date('d F Y', strtotime($tanggal_cetak)) : date('d F Y') }}
                        </div>
                        <div class="ttd-cap-company">PT. BERKAH ARRUM HARAMAIN</div>

                        <div class="ttd-cap-signature-area">
                            @if (!empty($capBase64))
                                <img src="{{ $capBase64 }}" alt="Cap" class="cap-image">
                            @endif
                            @if (!empty($ttdBase64))
                                <img src="{{ $ttdBase64 }}" alt="TTD" class="ttd-image">
                            @endif
                        </div>

                        <div class="ttd-cap-name">
                            <strong>{{ $ttdNama ?? '(...................................)' }}</strong>
                        </div>
                        <div class="ttd-cap-jabatan">
                            {{ $ttdJabatan ?? 'Direktur' }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    @endif

    <!-- FOOTER -->
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
