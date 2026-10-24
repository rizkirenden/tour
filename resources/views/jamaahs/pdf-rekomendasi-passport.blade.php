{{-- resources/views/jamaahs/pdf-rekomendasi-passport.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Rekomendasi Passport - {{ $jamaah->nama_lengkap }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 0;
            size: A4 portrait;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', 'Arial', serif;
            font-size: 12px;
            color: #333;
            background: #fff;
            line-height: 1.6;
            width: 100%;
            height: 297mm;
            position: relative;
            overflow: hidden;
        }

        /* ==========================================
           KOP SURAT
           ========================================== */
        .kop-wrapper {
            position: relative;
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .kop-bg {
            display: block;
            width: 100%;
            max-width: 100%;
            height: auto;
            margin: 0;
            padding: 0;
        }

        .kop-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: 100%;
            padding: 5px 20px 0 20px;
            box-sizing: border-box;
        }

        .kop-overlay-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            height: 100%;
        }

        .kop-overlay-table td {
            vertical-align: top;
            padding: 0;
        }

        .kop-logo-cell {
            width: 100px;
            text-align: center;
            vertical-align: top;
            padding-top: 0;
        }

        .kop-logo-cell img {
            max-height: 100px;
            max-width: 110px;
            width: auto;
            height: auto;
            object-fit: contain;
            display: inline-block;
            margin-top: 50px;
            vertical-align: top;
        }

        .kop-text-cell {
            text-align: center;
            vertical-align: top;
            padding-top: 5px;
            padding-left: 4px;
            padding-right: 4px;
            word-wrap: break-word;
        }

        .kop-company-name {
            font-size: 19px;
            font-weight: bold;
            color: #1a1a1a;
            letter-spacing: 0.5px;
            line-height: 1.2;
            margin-bottom: 2px;
        }

        .kop-subtitle {
            font-size: 12px;
            font-weight: bold;
            color: #1a1a1a;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .kop-izin {
            font-size: 11px;
            color: #333;
            margin-bottom: 2px;
        }

        .kop-alamat {
            font-size: 10px;
            color: #333;
            line-height: 1.35;
            word-wrap: break-word;
        }

        .kop-line-bottom {
            width: 100%;
            max-width: 100%;
            height: 3px;
            background: #1a1a1a;
            margin: 0;
        }

        /* ==========================================
           ISI SURAT
           ========================================== */
        .surat-wrapper {
            padding: 15px 40px 180px 40px;
            box-sizing: border-box;
        }

        .surat-content {
            padding: 0;
        }

        .surat-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 12px;
        }

        .surat-info-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .surat-info-table .label {
            width: 70px;
            font-weight: normal;
        }

        .surat-info-table .separator {
            width: 10px;
        }

        .surat-info-table .value {
            font-weight: normal;
        }

        .nomor-manual {
            display: inline-block;
            min-width: 280px;
            border-bottom: 1px dotted #333;
            height: 14px;
        }

        .surat-pembuka {
            margin-bottom: 12px;
            font-size: 12px;
            line-height: 1.6;
        }

        .surat-nama-jamaah {
            text-align: center;
            margin: 14px 0;
        }

        .surat-nama-jamaah .nama {
            font-weight: bold;
            font-size: 14px;
            color: #1a1a1a;
            letter-spacing: 0.5px;
        }

        .surat-paragraf {
            margin-bottom: 12px;
            text-align: justify;
            line-height: 1.7;
            font-size: 12px;
        }

        /* ==========================================
           LIST — pakai list-style: disc (didukung DomPDF)
           ========================================== */
        .surat-list {
            margin: 8px 0 12px 40px;
            font-size: 12px;
            line-height: 1.7;
            list-style: disc;
            /* ← bullet standar, didukung DomPDF */
            padding-left: 20px;
        }

        .surat-list li {
            margin-bottom: 6px;
            text-align: justify;
        }

        .surat-penutup {
            margin-top: 12px;
            font-size: 12px;
            text-align: justify;
            line-height: 1.7;
        }

        /* ==========================================
           TTD
           ========================================== */
        .ttd-wrapper {
            width: 100%;
            margin-top: 22px;
            page-break-inside: avoid;
        }

        .ttd-table {
            width: 100%;
            border-collapse: collapse;
        }

        .ttd-spacer {
            width: 55%;
        }

        .ttd-content {
            width: 45%;
            text-align: center;
            vertical-align: top;
        }

        .ttd-location {
            font-size: 12px;
            color: #333;
            margin-bottom: 2px;
        }

        .ttd-company {
            font-size: 12px;
            font-weight: normal;
            color: #333;
            margin-bottom: 6px;
        }

        .ttd-signature-area {
            position: relative;
            height: 100px;
            width: 100%;
            margin-bottom: 4px;
        }

        .ttd-signature-area .ttd-image {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-height: 85px;
            max-width: 140px;
            z-index: 2;
        }

        .ttd-signature-area .cap-image {
            position: absolute;
            top: 50%;
            left: 55%;
            transform: translate(-50%, -50%);
            max-height: 95px;
            max-width: 95px;
            opacity: 0.85;
            z-index: 1;
        }

        .ttd-name {
            font-size: 12px;
            font-weight: bold;
            color: #1a1a1a;
            text-decoration: underline;
            margin-top: 4px;
        }

        .ttd-jabatan {
            font-size: 11px;
            color: #333;
            margin-top: 2px;
        }

        /* ==========================================
           FOOTER
           ========================================== */
        .footer-wrapper {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            width: 100%;
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            z-index: 999;
        }

        .footer-table-overlay {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            table-layout: fixed;
        }

        .footer-table-overlay td {
            padding: 0;
            margin: 0;
            vertical-align: bottom;
            text-align: center;
        }

        .footer-1 {
            display: block;
            width: 100%;
            height: auto;
            margin: 0;
            padding: 0;
        }

        .footer-2 {
            display: block;
            width: 50%;
            height: auto;
            margin: -30px auto 0 auto;
            padding: 0;
        }
    </style>
</head>

<body>

    <!-- ==========================================
         KOP SURAT
         ========================================== -->
    <div class="kop-wrapper">
        @if (!empty($headerBase64))
            <img src="{{ $headerBase64 }}" alt="Header" class="kop-bg">

            <div class="kop-overlay">
                <table class="kop-overlay-table">
                    <tr>
                        <td class="kop-logo-cell">
                            @if (!empty($logoBase64))
                                <img src="{{ $logoBase64 }}" alt="Logo Kiri">
                            @endif
                        </td>

                        <td class="kop-text-cell">
                            <div class="kop-company-name">PT. BERKAH ARRUM HARAMAIN TOUR</div>
                            <div class="kop-subtitle">PENYELENGGARA UMROH RESMI</div>
                            <div class="kop-izin">IZIN PPIU: 24012400805710003</div>
                            <div class="kop-alamat">Indonesia, Sulawesi Tengah</div>
                            <div class="kop-alamat">
                                Jl. Munifrahman 2 No. 30 Kel. Silae Kec. Ulujadi, Kota Palu (942227)
                            </div>
                            <div class="kop-alamat">
                                Telp : 082148333791, 08114515050 | Email : arrumharamaintravel@gmail.com
                            </div>
                        </td>

                        <td class="kop-logo-cell">
                            @if (!empty($logoBase64))
                                <img src="{{ $logoBase64 }}" alt="Logo Kanan">
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        @else
            <div style="text-align:center; padding: 15px 20px;">
                <div class="kop-company-name" style="font-size:19px; font-weight:bold;">PT. BERKAH ARRUM HARAMAIN TOUR
                </div>
                <div class="kop-subtitle" style="font-size:12px; font-weight:bold;">PENYELENGGARA UMROH RESMI</div>
                <div class="kop-izin" style="font-size:11px;">IZIN PPIU: 24012400805710003</div>
                <div class="kop-alamat" style="font-size:10px;">Indonesia, Sulawesi Tengah</div>
                <div class="kop-alamat" style="font-size:10px;">Jl. Munifrahman 2 No. 30 Kel. Silae Kec. Ulujadi, Kota
                    Palu (942227)</div>
                <div class="kop-alamat" style="font-size:10px;">Telp : 082148333791, 08114515050 | Email :
                    arrumharamaintravel@gmail.com</div>
            </div>
        @endif
        <div class="kop-line-bottom"></div>
    </div>

    <!-- ==========================================
         ISI SURAT
         ========================================== -->
    <div class="surat-wrapper">
        <div class="surat-content">

            <table class="surat-info-table">
                <tr>
                    <td class="label">Nomor</td>
                    <td class="separator">:</td>
                    <td class="value">
                        <span class="nomor-manual">&nbsp;</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">Hal</td>
                    <td class="separator">:</td>
                    <td class="value">Permohonan Pembuatan Surat Rekomendasi Pasport</td>
                </tr>
            </table>

            <div class="surat-pembuka">
                Kepada Yang Terhormat,<br>
                Kantor Imigrasi Kelas I TPI Kota Palu<br>
                Di &ndash; Tempat
            </div>

            <div class="surat-pembuka">
                Assalamualaikum, Wr. Wb.
            </div>

            <div class="surat-paragraf">
                &nbsp;&nbsp;&nbsp;&nbsp;Bersama ini, kami pengurus Travel Arrum Tour PT. Berkah Arrum Haramain dengan
                Izin PPIU 24012400805710003, sebagai Penyelenggara Perjalanan lbadah Umroh &amp; Haji
                mengajukan permohonan pembuatan Passport atas nama :
            </div>

            <div class="surat-nama-jamaah">
                <span class="nama">&bull;&nbsp;&nbsp;{{ strtoupper($jamaah->nama_lengkap) }}</span>
            </div>

            <div class="surat-paragraf">
                &nbsp;&nbsp;&nbsp;&nbsp;Nama yang bersangkutan diatas adalah benar jamaah kami yang sudah terdaftar pada
                jadwal
                perjalanan umroh bersama rombongan perjalanan kami yang insya Allah akan berangkat
                Bulan {{ $bulanKeberangkatan }} {{ $tahunKeberangkatan }}&nbsp;&nbsp;&nbsp;kami bertanggung jawab dan
                menjamin keberangkatan jamaah
                kami dari berangkat hingga kembali ke tanah air, bertanggung jawab dan menjamin
                sepenuhnya terhadap calon jamaah umrah kami dengan memperhatikan hal-hal sebagai
                berikut :
            </div>

            <ul class="surat-list">
                <li>Permohonan Pembuatan Passport kami urus adalah WNI yang sebenarnya akan
                    melakukan perjalanan ke Arab Saudi dalam rangka lbadah Umrah.</li>
                <li>Rombongan calon jamaah umrah yang tidak akan melakukan pelanggaran
                    peraturan keimigrasian berupa penyalahgunaan izin tinggal, tidak melebihi izin
                    tinggalnya (overstay), memalsukan atau membuat passport yang diberikan
                    kepadanya maupun bekerja secara ilegal.</li>
                <li>Apabila terjadi pelanggaran sebagaimana dimaksud, maka izin usaha kami
                    sebagai penyelenggara perjalanan ibadah umrah bersedia dicabut.</li>
            </ul>

            <div class="surat-penutup">
                &nbsp;&nbsp;&nbsp;&nbsp;Demikian Permohonan Pembuatan Paspor ini kami buat untuk digunakan kepada yang
                berwenang, mohon dapat dibantu seperlunya. Atas perhatian dan kerjasama Bapak/Ibu kami
                ucapkan terimakasih.
            </div>

            <div class="surat-penutup">
                Wassalamu'alaikum Wr. Wb.
            </div>

        </div>

        <!-- ==========================================
             TANDA TANGAN & CAP
             ========================================== -->
        <div class="ttd-wrapper">
            <table class="ttd-table">
                <tr>
                    <td class="ttd-spacer"></td>
                    <td class="ttd-content">
                        <div class="ttd-location">
                            Palu, {{ $tanggal_cetak }}
                        </div>
                        <div class="ttd-company">
                            Arrum Tour &amp; Travel Palu
                        </div>

                        <div class="ttd-signature-area">
                            @if (!empty($capBase64))
                                <img src="{{ $capBase64 }}" alt="Cap" class="cap-image">
                            @endif
                            @if (!empty($ttdBase64))
                                <img src="{{ $ttdBase64 }}" alt="TTD" class="ttd-image">
                            @endif
                        </div>

                        <div class="ttd-name">
                            ADITYA AGUNG PERMANA
                        </div>
                        <div class="ttd-jabatan">
                            Pegawai Pelaksana Tugas
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- ==========================================
         FOOTER — GAMBAR FOOTER1 + FOOTER2
         ========================================== -->
    <div class="footer-wrapper">
        <table class="footer-table-overlay">
            <tr>
                <td>
                    @if (!empty($footer1Base64))
                        <img src="{{ $footer1Base64 }}" alt="Footer 1" class="footer-1">
                    @endif
                    @if (!empty($footer2Base64))
                        <img src="{{ $footer2Base64 }}" alt="Footer 2" class="footer-2">
                    @endif
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
