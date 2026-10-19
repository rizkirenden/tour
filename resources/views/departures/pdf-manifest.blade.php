<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manifest Keberangkatan - {{ $departure->nama_keberangkatan }}</title>
    <style>
        @page {
            margin: 15mm 12mm 15mm 12mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 9px;
            color: #1f2937;
            line-height: 1.3;
        }

        /* ==============================
           HEADER
           ============================== */
        .header {
            width: 100%;
            border-bottom: 2px solid #1f2937;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
            padding: 0;
        }

        .header-side {
            width: 70px;
        }

        .header-title {
            text-align: center;
        }

        .header-title h1 {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .header-title h2 {
            font-size: 12px;
            font-weight: normal;
            color: #4b5563;
            margin: 2px 0 0 0;
        }

        /* ==============================
           INFO KEBERANGKATAN
           ============================== */
        .info-box {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 10px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 2px 4px;
            vertical-align: top;
            font-size: 9px;
        }

        .info-table .label {
            color: #92400e;
            font-weight: 600;
            width: 110px;
        }

        .info-table .value {
            color: #1f2937;
            font-weight: 500;
        }

        .info-table .colon {
            width: 8px;
            color: #92400e;
        }

        /* ==============================
           SUMMARY
           ============================== */
        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px 0;
            margin-bottom: 10px;
        }

        .summary-table td {
            padding: 6px 8px;
            text-align: center;
            border-radius: 4px;
            font-size: 8px;
            width: 25%;
        }

        .summary-total {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            color: #92400e;
        }

        .summary-l {
            background: #dbeafe;
            border: 1px solid #93c5fd;
            color: #1e40af;
        }

        .summary-p {
            background: #fce7f3;
            border: 1px solid #f9a8d4;
            color: #9d174d;
        }

        .summary-family {
            background: #e0e7ff;
            border: 1px solid #a5b4fc;
            color: #3730a3;
        }

        .summary-label {
            font-size: 7px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 2px;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
        }

        /* ==============================
           TABLE MANIFEST
           ============================== */
        .manifest-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            margin-bottom: 10px;
        }

        .manifest-table thead th {
            background: #1f2937;
            color: white;
            padding: 5px 4px;
            text-align: center;
            font-weight: 600;
            border: 1px solid #374151;
            font-size: 7.5px;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }

        .manifest-table tbody td {
            padding: 4px 4px;
            border: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        .manifest-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .manifest-table tbody tr:hover {
            background: #fef3c7;
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

        .font-mono {
            font-family: 'DejaVu Sans Mono', monospace;
        }

        .font-bold {
            font-weight: bold;
        }

        .nama {
            font-weight: 600;
            color: #111827;
            font-size: 8.5px;
        }

        .nik {
            font-size: 7px;
            color: #6b7280;
            margin-top: 1px;
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 8px;
            font-size: 7px;
            font-weight: 600;
        }

        .badge-l {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-p {
            background: #fce7f3;
            color: #9d174d;
        }

        .badge-family {
            background: #e0e7ff;
            color: #3730a3;
        }

        .badge-mandiri {
            background: #dcfce7;
            color: #166534;
        }

        .badge-kepala {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fbbf24;
        }

        /* ==============================
           FOOTER
           ============================== */
        .footer {
            margin-top: 20px;
            width: 100%;
        }

        .info-footer {
            text-align: left;
            font-size: 7px;
            color: #6b7280;
            padding-top: 8px;
        }

        /* Metadata cetak (di bawah) */
        .print-meta {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px dashed #d1d5db;
            font-size: 7px;
            color: #6b7280;
            text-align: left;
        }

        .print-meta span {
            display: inline-block;
            margin-right: 20px;
        }

        .print-meta strong {
            color: #374151;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 70px;
            color: rgba(0, 0, 0, 0.03);
            font-weight: bold;
            z-index: -1;
            letter-spacing: 8px;
        }
    </style>
</head>

<body>

    {{-- WATERMARK --}}
    <div class="watermark">{{ strtoupper(explode(' ', $departure->nama_keberangkatan)[0] ?? 'MANIFEST') }}</div>

    {{-- HEADER --}}
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-side"></td>
                <td class="header-title">
                    <h1>MANIFEST KEBERANGKATAN</h1>
                    <h2>{{ $departure->nama_keberangkatan }}</h2>
                </td>
                <td class="header-side"></td>
            </tr>
        </table>
    </div>

    {{-- INFO KEBERANGKATAN --}}
    <div class="info-box">
        <table class="info-table">
            <tr>
                <td class="label">Kode Keberangkatan</td>
                <td class="colon">:</td>
                <td class="value font-mono">{{ $departure->kode_keberangkatan }}</td>
                <td class="label">Produk Paket</td>
                <td class="colon">:</td>
                <td class="value">{{ $departure->produk_paket }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Berangkat</td>
                <td class="colon">:</td>
                <td class="value">{{ $departure->tanggal_keberangkatan->translatedFormat('d F Y') }}</td>
                <td class="label">Tanggal Kembali</td>
                <td class="colon">:</td>
                <td class="value">{{ $departure->tanggal_kepulangan->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Bulan Keberangkatan</td>
                <td class="colon">:</td>
                <td class="value">
                    @if ($departure->bulan_keberangkatan)
                        {{ \Carbon\Carbon::create()->month($departure->bulan_keberangkatan)->translatedFormat('F') }}
                    @else
                        -
                    @endif
                </td>
                <td class="label">Tahun Keberangkatan</td>
                <td class="colon">:</td>
                <td class="value">{{ $departure->tahun_keberangkatan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Kuota</td>
                <td class="colon">:</td>
                <td class="value">{{ $departure->kuota }} orang</td>
                <td class="label">Status</td>
                <td class="colon">:</td>
                <td class="value">{!! strip_tags($departure->status_badge) !!}</td>
            </tr>
        </table>
    </div>

    {{-- SUMMARY --}}
    <table class="summary-table">
        <tr>
            <td class="summary-total">
                <div class="summary-label">Total Jamaah</div>
                <div class="summary-value">{{ $totalJamaah }}</div>
            </td>
            <td class="summary-l">
                <div class="summary-label">Laki-laki</div>
                <div class="summary-value">{{ $totalLakiLaki }}</div>
            </td>
            <td class="summary-p">
                <div class="summary-label">Perempuan</div>
                <div class="summary-value">{{ $totalPerempuan }}</div>
            </td>
            <td class="summary-family">
                <div class="summary-label">Keluarga / Kelompok</div>
                <div class="summary-value">
                    {{ $groupedByKeluarga->filter(fn($g) => $g->first()->id_keluarga != null)->count() }}</div>
            </td>
        </tr>
    </table>

    {{-- TABLE MANIFEST --}}
    <table class="manifest-table">
        <thead>
            <tr>
                <th style="width: 22px;">No</th>
                <th style="width: auto;">Nama Lengkap</th>
                <th style="width: 30px;">JK</th>
                <th style="width: 65px;">Tempat Lahir</th>
                <th style="width: 55px;">Tgl Lahir</th>
                <th style="width: 70px;">No. Passport</th>
                <th style="width: 55px;">Tgl Terbit</th>
                <th style="width: 55px;">Tgl Berakhir</th>
                <th style="width: 75px;">Diterbitkan Di</th>
                <th style="width: 55px;">Hubungan</th>
                <th style="width: 55px;">Sumber</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($jamaahList as $jamaah)
                <tr>
                    <td class="text-center font-bold">{{ $no++ }}</td>
                    <td>
                        <div class="nama">{{ $jamaah->nama_lengkap }}</div>
                        @if ($jamaah->nik)
                            <div class="nik">NIK: {{ $jamaah->nik }}</div>
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($jamaah->jenis_kelamin == 'L')
                            <span class="badge badge-l">L</span>
                        @elseif ($jamaah->jenis_kelamin == 'P')
                            <span class="badge badge-p">P</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">{{ $jamaah->tempat_lahir ?? '-' }}</td>
                    <td class="text-center">
                        {{ $jamaah->tanggal_lahir ? $jamaah->tanggal_lahir->format('d/m/Y') : '-' }}</td>
                    <td class="text-center font-mono">{{ $jamaah->nomor_paspor ?? '-' }}</td>
                    <td class="text-center">
                        {{ $jamaah->paspor_terbit ? $jamaah->paspor_terbit->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">
                        {{ $jamaah->paspor_expired ? $jamaah->paspor_expired->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $jamaah->paspor_diterbitkan_di ?? '-' }}</td>
                    <td class="text-center">
                        @if ($jamaah->id_keluarga)
                            <span class="badge badge-family">
                                {{ $jamaah->hubungan_keluarga ?? 'Keluarga' }}
                            </span>
                            @if ($jamaah->is_kepala_keluarga)
                                <div style="margin-top: 1px;">
                                    <span class="badge badge-kepala">KEPALA</span>
                                </div>
                            @endif
                        @else
                            <span class="text-gray-500">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($jamaah->id_keluarga && $jamaah->keluarga)
                            <span class="badge badge-family">
                                <i>{{ $jamaah->keluarga->nama_keluarga }}</i>
                            </span>
                            <div style="font-size: 6.5px; color: #6b7280; margin-top: 1px;">
                                {{ $jamaah->keluarga->kode_keluarga }}
                            </div>
                        @else
                            <span class="badge badge-mandiri">Mandiri</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- =============================================
         REKAP KELUARGA / KELOMPOK
         ============================================= --}}
    @php
        $keluargaGroup = $jamaahList->filter(fn($j) => $j->id_keluarga != null)->groupBy('id_keluarga');
    @endphp

    @if ($keluargaGroup->count() > 0)
        <div style="margin-top: 12px;">
            <div
                style="background: #1f2937; color: white; padding: 5px 10px; font-size: 9px; font-weight: bold; border-radius: 3px 3px 0 0;">
                <i>REKAP KELUARGA / KELOMPOK</i>
            </div>
            <table class="manifest-table" style="margin-bottom: 0;">
                <thead>
                    <tr>
                        <th style="width: 25px;">No</th>
                        <th style="width: 180px;">Nama Keluarga / Kelompok</th>
                        <th style="width: 100px;">Kode</th>
                        <th style="width: 70px;">Jumlah Anggota</th>
                        <th style="width: auto;">Daftar Nama Anggota</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($keluargaGroup as $idKeluarga => $anggota)
                        @php $keluarga = $anggota->first()->keluarga; @endphp
                        <tr>
                            <td class="text-center font-bold">{{ $loop->iteration }}</td>
                            <td><strong>{{ $keluarga->nama_keluarga ?? '-' }}</strong></td>
                            <td class="text-center font-mono">{{ $keluarga->kode_keluarga ?? '-' }}</td>
                            <td class="text-center"><strong>{{ $anggota->count() }} orang</strong></td>
                            <td>{{ $anggota->pluck('nama_lengkap')->implode(', ') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- =============================================
         REKAP JAMAAH MANDIRI
         ============================================= --}}
    @php
        $jamaahMandiri = $jamaahList->filter(fn($j) => $j->id_keluarga == null);
    @endphp

    @if ($jamaahMandiri->count() > 0)
        <div style="margin-top: 12px;">
            <div
                style="background: #166534; color: white; padding: 5px 10px; font-size: 9px; font-weight: bold; border-radius: 3px 3px 0 0;">
                <i>REKAP JAMAAH MANDIRI (TANPA KELUARGA)</i>
            </div>
            <table class="manifest-table" style="margin-bottom: 0;">
                <thead>
                    <tr>
                        <th style="width: 25px;">No</th>
                        <th style="width: auto;">Nama Lengkap</th>
                        <th style="width: 80px;">NIK</th>
                        <th style="width: 70px;">No. Passport</th>
                        <th style="width: 80px;">Telepon</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jamaahMandiri as $jamaah)
                        <tr>
                            <td class="text-center font-bold">{{ $loop->iteration }}</td>
                            <td><strong>{{ $jamaah->nama_lengkap }}</strong></td>
                            <td class="text-center font-mono">{{ $jamaah->nik ?? '-' }}</td>
                            <td class="text-center font-mono">{{ $jamaah->nomor_paspor ?? '-' }}</td>
                            <td class="text-center">{{ $jamaah->telepon ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- FOOTER --}}
    <div class="footer">
        <div class="info-footer">
            <p><strong>Catatan:</strong></p>
            <p>1. Manifest ini dibuat otomatis oleh sistem.</p>
            <p>2. Data jamaah sesuai dengan yang terdaftar di keberangkatan.</p>
            <p>3. Pastikan semua data passport masih berlaku minimal 6 bulan.</p>
            <p style="margin-top: 8px;">
                Total Jamaah: <strong>{{ $totalJamaah }} orang</strong>
                (L: {{ $totalLakiLaki }} · P: {{ $totalPerempuan }})
            </p>
        </div>
    </div>

    {{-- =============================================
         METADATA CETAK (DICETAK & OLEH) — DI BAWAH
         ============================================= --}}
    <div class="print-meta">
        <span><strong>Dicetak:</strong> {{ $tanggal_cetak }}</span>
        <span><strong>Oleh:</strong> {{ $dicetak_oleh }}</span>
    </div>

</body>

</html>
