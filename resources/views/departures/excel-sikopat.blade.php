<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        /* Border hanya untuk tabel yang diberi class "bordered" */
        table.bordered th,
        table.bordered td {
            border: 0.5pt solid #000000;
            padding: 4px 6px;
            vertical-align: top;
        }

        table.bordered th {
            background-color: #1E3A8A;
            color: #FFFFFF;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }

        .subtitle {
            font-size: 11px;
            font-weight: bold;
            text-align: center;
        }

        .info-label {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .belum {
            color: #DC2626;
            font-style: italic;
        }

        .teks-nik {
            mso-number-format: "\@";
            text-align: center;
        }
    </style>
</head>

<body>

    {{-- ================= HEADER (tanpa border) ================= --}}
    <table>
        <tr>
            <td colspan="18" class="title">MANIFEST JAMAAH SIKOPAT</td>
        </tr>
        <tr>
            <td colspan="18" class="subtitle">Sistem Kopi Data Jamaah - Data Lengkap</td>
        </tr>
        <tr>
            <td colspan="18"></td>
        </tr>

        <tr>
            <td colspan="3" class="info-label">Nama Keberangkatan</td>
            <td colspan="6">: {{ $departure->nama_keberangkatan }}</td>
            <td colspan="3" class="info-label">Kode Keberangkatan</td>
            <td colspan="6">: {{ $departure->kode_keberangkatan }}</td>
        </tr>
        <tr>
            <td colspan="3" class="info-label">Produk Paket</td>
            <td colspan="6">: {{ $departure->produk_paket }}</td>
            <td colspan="3" class="info-label">Status</td>
            <td colspan="6">: {{ $departure->status_nama }}</td>
        </tr>
        <tr>
            <td colspan="3" class="info-label">Tanggal Berangkat</td>
            <td colspan="6">:
                {{ $departure->tanggal_keberangkatan ? $departure->tanggal_keberangkatan->format('d/m/Y') : '-' }}</td>
            <td colspan="3" class="info-label">Tanggal Pulang</td>
            <td colspan="6">:
                {{ $departure->tanggal_kepulangan ? $departure->tanggal_kepulangan->format('d/m/Y') : '-' }}</td>
        </tr>
        <tr>
            <td colspan="3" class="info-label">Total Jamaah</td>
            <td colspan="6">: {{ $totalJamaah }} (L: {{ $totalLakiLaki }}, P: {{ $totalPerempuan }})</td>
            <td colspan="3" class="info-label">Dicetak</td>
            <td colspan="6">: {{ $tanggal_cetak }}</td>
        </tr>
        <tr>
            <td colspan="18"></td>
        </tr>
    </table>

    {{-- ================= TABEL DATA JAMAAH (dengan border) ================= --}}
    <table class="bordered" border="1" cellspacing="0" cellpadding="4">
        <thead>
            <tr>
                <th style="width:35px; border:0.5pt solid #000000;">No</th>
                <th style="width:150px; border:0.5pt solid #000000;">Nama</th>
                <th style="width:35px; border:0.5pt solid #000000;">Gender</th>
                <th style="width:110px; border:0.5pt solid #000000;">NIK</th>
                <th style="width:80px; border:0.5pt solid #000000;">Tempat Lahir</th>
                <th style="width:70px; border:0.5pt solid #000000;">Tanggal Lahir</th>
                <th style="width:110px; border:0.5pt solid #000000;">No Passport</th>
                <th style="width:70px; border:0.5pt solid #000000;">Passport Dibuat</th>
                <th style="width:90px; border:0.5pt solid #000000;">Tanggal Terbit Passport</th>
                <th style="width:90px; border:0.5pt solid #000000;">Tanggal Berakhir Passport</th>
                <th style="width:90px; border:0.5pt solid #000000;">Diterbitkan Di</th>
                <th style="width:100px; border:0.5pt solid #000000;">Hubungan</th>
                <th style="width:160px; border:0.5pt solid #000000;">Alamat</th>
                <th style="width:80px; border:0.5pt solid #000000;">Provinsi</th>
                <th style="width:100px; border:0.5pt solid #000000;">Kota/Kabupaten</th>
                <th style="width:80px; border:0.5pt solid #000000;">Kecamatan</th>
                <th style="width:80px; border:0.5pt solid #000000;">Kelurahan</th>
                <th style="width:90px; border:0.5pt solid #000000;">Pekerjaan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse ($jamaahList as $jamaah)
                <tr>
                    <td class="text-center" style="border:0.5pt solid #000000;">{{ $no++ }}</td>
                    <td style="border:0.5pt solid #000000;">
                        @if ($jamaah->is_kepala_keluarga)
                            <strong>{{ $jamaah->nama_lengkap }}</strong>
                        @else
                            {{ $jamaah->nama_lengkap }}
                        @endif
                    </td>
                    <td class="text-center" style="border:0.5pt solid #000000;">
                        {{ $jamaah->jenis_kelamin === 'L' ? 'L' : 'P' }}</td>

                    {{-- NIK - paksa teks --}}
                    <td class="teks-nik" style="border:0.5pt solid #000000;">{{ $jamaah->nik ?? '-' }}</td>

                    <td style="border:0.5pt solid #000000;">{{ $jamaah->tempat_lahir ?? '-' }}</td>
                    <td class="text-center" style="border:0.5pt solid #000000;">{{ $jamaah->tanggal_lahir_formatted }}
                    </td>

                    {{-- No Passport - paksa teks --}}
                    <td class="teks-nik" style="border:0.5pt solid #000000;">{{ $jamaah->nomor_paspor ?? '-' }}</td>

                    {{-- Passport Dibuat: Sudah / Belum --}}
                    <td class="text-center" style="border:0.5pt solid #000000;">
                        @if (!empty($jamaah->nomor_paspor))
                            Sudah
                        @else
                            <span class="belum">Belum</span>
                        @endif
                    </td>

                    {{-- Tanggal Terbit Passport --}}
                    <td class="text-center" style="border:0.5pt solid #000000;">
                        @if ($jamaah->paspor_terbit)
                            {{ $jamaah->paspor_terbit->format('d/m/Y') }}
                        @else
                            <span class="belum">Belum</span>
                        @endif
                    </td>

                    {{-- Tanggal Berakhir Passport --}}
                    <td class="text-center" style="border:0.5pt solid #000000;">
                        @if ($jamaah->paspor_expired)
                            {{ $jamaah->paspor_expired->format('d/m/Y') }}
                        @else
                            <span class="belum">Belum</span>
                        @endif
                    </td>

                    {{-- Diterbitkan Di --}}
                    <td class="text-center" style="border:0.5pt solid #000000;">
                        @if (!empty($jamaah->paspor_diterbitkan_di))
                            {{ $jamaah->paspor_diterbitkan_di }}
                        @else
                            <span class="belum">Belum</span>
                        @endif
                    </td>

                    <td class="text-center" style="border:0.5pt solid #000000;">
                        {{ $jamaah->hubungan_keluarga ?? '-' }}</td>
                    <td style="border:0.5pt solid #000000;">{{ $jamaah->alamat ?? '-' }}</td>
                    <td style="border:0.5pt solid #000000;">{{ $jamaah->provinsi ?? '-' }}</td>
                    <td style="border:0.5pt solid #000000;">{{ $jamaah->kota_kabupaten ?? '-' }}</td>
                    <td style="border:0.5pt solid #000000;">{{ $jamaah->kecamatan ?? '-' }}</td>
                    <td style="border:0.5pt solid #000000;">{{ $jamaah->kelurahan ?? '-' }}</td>
                    <td style="border:0.5pt solid #000000;">{{ $jamaah->pekerjaan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="18" class="text-center" style="border:0.5pt solid #000000;">Tidak ada data jamaah.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ================= REKAP KELUARGA (dengan border) ================= --}}
    <br>
    <table class="bordered" border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td colspan="5" class="subtitle" style="background-color:#DBEAFE; border:0.5pt solid #000000;">REKAP
                KELUARGA</td>
        </tr>
        <tr>
            <th style="width:35px; border:0.5pt solid #000000;">No</th>
            <th style="width:200px; border:0.5pt solid #000000;">Nama Keluarga</th>
            <th style="width:180px; border:0.5pt solid #000000;">Kepala Keluarga</th>
            <th style="width:80px; border:0.5pt solid #000000;">Jumlah</th>
            <th style="width:400px; border:0.5pt solid #000000;">Anggota</th>
        </tr>
        @php $noK = 1; @endphp
        @foreach ($groupedByKeluarga as $keluargaId => $anggota)
            <tr>
                <td class="text-center" style="border:0.5pt solid #000000;">{{ $noK++ }}</td>
                <td style="border:0.5pt solid #000000;">
                    {{ $anggota->first()->keluarga->nama_keluarga ?? 'Mandiri' }}</td>
                <td style="border:0.5pt solid #000000;">
                    @php $kepala = $anggota->firstWhere('is_kepala_keluarga', true); @endphp
                    {{ $kepala->nama_lengkap ?? '-' }}
                </td>
                <td class="text-center" style="border:0.5pt solid #000000;">{{ $anggota->count() }}</td>
                <td style="border:0.5pt solid #000000;">{{ $anggota->pluck('nama_lengkap')->implode(', ') }}</td>
            </tr>
        @endforeach
    </table>

    {{-- ================= FOOTER (tanpa border) ================= --}}
    <br>
    <table>
        <tr>
            <td colspan="18" style="border:none;text-align:center;font-size:9px;">
                Dicetak dari SIKOPAT pada {{ $tanggal_cetak }}
            </td>
        </tr>
    </table>

</body>

</html>
