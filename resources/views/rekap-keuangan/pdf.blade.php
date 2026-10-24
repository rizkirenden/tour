<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Rekap Keuangan</title>
    <style>
        * {
            font-family: DejaVu Sans, sans-serif;
        }

        body {
            font-size: 9px;
            color: #222;
        }

        h1 {
            font-size: 14px;
            margin: 0 0 2px 0;
        }

        h2 {
            font-size: 11px;
            margin: 14px 0 4px 0;
        }

        .sub {
            font-size: 9px;
            color: #666;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        th,
        td {
            padding: 3px 4px;
            border: 1px solid #ccc;
            vertical-align: top;
        }

        th {
            background: #f0f0f0;
            font-size: 8px;
            text-transform: uppercase;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .green {
            color: #0a7a3e;
            font-weight: bold;
        }

        .red {
            color: #b91c1c;
            font-weight: bold;
        }

        .grand td {
            background: #eef;
            font-weight: bold;
        }

        .kpi td {
            background: #f8f9fb;
            font-weight: bold;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <h1>REKAP KEUANGAN</h1>
    <div class="sub">
        Dicetak: {{ $tanggal_cetak }} · Oleh: {{ $dicetak_oleh }}
        @if (!empty($filters['dari_tanggal']))
            · Dari: {{ $filters['dari_tanggal'] }}
        @endif
        @if (!empty($filters['sampai_tanggal']))
            · Sampai: {{ $filters['sampai_tanggal'] }}
        @endif
    </div>

    {{-- KPI --}}
    <table class="kpi">
        <tr>
            <td style="width:33%;">TOTAL KEUNTUNGAN DEPARTURE<br>
                <span class="green" style="font-size:12px;">Rp
                    {{ number_format($departure['total_keuntungan'], 0, ',', '.') }}</span>
            </td>
            <td style="width:33%;">TOTAL PENGELUARAN LAIN-LAIN<br>
                <span class="red" style="font-size:12px;">Rp
                    {{ number_format($pengeluaran['total'], 0, ',', '.') }}</span>
            </td>
            <td style="width:34%;">TOTAL BERSIH<br>
                <span class="{{ $totalBersih >= 0 ? 'green' : 'red' }}" style="font-size:12px;">
                    {{ $totalBersih >= 0 ? '+' : '−' }} Rp {{ number_format(abs($totalBersih), 0, ',', '.') }}
                </span>
            </td>
        </tr>
        <tr>
            <td>{{ $departure['departure_count'] }} departure · {{ $departure['jamaah_count'] }} jamaah</td>
            <td>{{ $pengeluaran['items']->count() }} item</td>
            <td>Keuntungan − Pengeluaran</td>
        </tr>
    </table>

    {{-- A. KEUNTUNGAN DEPARTURE --}}
    <h2>A. KEUNTUNGAN DEPARTURE</h2>
    <table>
        <thead>
            <tr>
                <th style="width:11%;">Kode</th>
                <th style="width:24%;">Keberangkatan</th>
                <th style="width:6%;">Jamaah</th>
                <th style="width:14%;">Pendapatan</th>
                <th style="width:14%;">Pengeluaran</th>
                <th style="width:14%;">Keuntungan</th>
                <th style="width:7%;">Margin</th>
                <th style="width:10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($departure['rows'] as $dep)
                @php $profit = $dep->keuntungan >= 0; @endphp
                <tr>
                    <td>{{ $dep->kode_keberangkatan }}</td>
                    <td>
                        <b>{{ $dep->nama_keberangkatan }}</b><br>
                        <span style="color:#666;">{{ $dep->produk_paket }}</span>
                    </td>
                    <td class="text-center">{{ $dep->jamaah_terdaftar }}</td>
                    <td class="text-right green">{{ number_format($dep->total_pendapatan_bersih, 0, ',', '.') }}</td>
                    <td class="text-right red">{{ number_format($dep->total_pengeluaran, 0, ',', '.') }}</td>
                    <td class="text-right {{ $profit ? 'green' : 'red' }}">
                        {{ $profit ? '+' : '−' }} {{ number_format(abs($dep->keuntungan), 0, ',', '.') }}
                    </td>
                    <td class="text-right">{{ $dep->margin_laba_formatted }}</td>
                    <td class="text-center">{{ $dep->perjalanan_selesai ? 'Selesai' : 'Berjalan' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class="grand">
            <tr>
                <td colspan="5" class="text-right">TOTAL KEUNTUNGAN DEPARTURE</td>
                <td class="text-right green">{{ number_format($departure['total_keuntungan'], 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    {{-- B. PENGELUARAN LAIN-LAIN --}}
    <h2>B. PENGELUARAN LAIN-LAIN (MANUAL)</h2>
    <table>
        <thead>
            <tr>
                <th style="width:10%;">Tanggal</th>
                <th style="width:25%;">Nama Pengeluaran</th>
                <th style="width:15%;">Kategori</th>
                <th style="width:30%;">Keterangan</th>
                <th style="width:20%;">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengeluaran['items'] as $item)
                <tr>
                    <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                    <td><b>{{ $item->nama_pengeluaran }}</b></td>
                    <td>{{ $item->kategori ?? '-' }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                    <td class="text-right red">{{ number_format($item->nominal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class="grand">
            <tr>
                <td colspan="4" class="text-right">TOTAL PENGELUARAN LAIN-LAIN</td>
                <td class="text-right red">{{ number_format($pengeluaran['total'], 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- C. RINGKASAN PER KATEGORI --}}
    @if ($perKategori->count() > 0)
        <h2>C. RINGKASAN PENGELUARAN PER KATEGORI</h2>
        <table>
            <thead>
                <tr>
                    <th style="width:60%;">Kategori</th>
                    <th style="width:40%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($perKategori as $pk)
                    <tr>
                        <td>{{ $pk->kategori ?? '(Tanpa kategori)' }}</td>
                        <td class="text-right red">{{ number_format($pk->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- D. HASIL AKHIR --}}
    <h2>D. HASIL AKHIR</h2>
    <table>
        <tr>
            <td style="width:60%;" class="text-right">Total Keuntungan Departure</td>
            <td class="text-right green">Rp {{ number_format($departure['total_keuntungan'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-right">Dikurangi Pengeluaran Lain-lain</td>
            <td class="text-right red">− Rp {{ number_format($pengeluaran['total'], 0, ',', '.') }}</td>
        </tr>
        <tr class="grand">
            <td class="text-right">TOTAL BERSIH</td>
            <td class="text-right {{ $totalBersih >= 0 ? 'green' : 'red' }}" style="font-size:12px;">
                {{ $totalBersih >= 0 ? '+' : '−' }} Rp {{ number_format(abs($totalBersih), 0, ',', '.') }}
            </td>
        </tr>
    </table>
</body>

</html>
