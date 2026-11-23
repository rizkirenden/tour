<?php

namespace App\Exports;

use App\Models\Departure;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ManifestSikopatExport implements FromView, ShouldAutoSize, WithStyles, WithTitle, WithColumnFormatting
{
    protected Departure $departure;

    public function __construct(Departure $departure)
    {
        $this->departure = $departure;
    }

    public function view(): View
    {
        $jamaahList = $this->departure->jamaahs()
            ->with(['keluarga', 'keberangkatan'])
            ->orderBy('is_kepala_keluarga', 'desc')
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        $groupedByKeluarga = $jamaahList->groupBy(function ($jamaah) {
            return $jamaah->id_keluarga ?? 'mandiri_' . $jamaah->id_jamaah;
        });

        return view('departures.excel-sikopat', [
            'departure'          => $this->departure,
            'jamaahList'         => $jamaahList,
            'groupedByKeluarga'  => $groupedByKeluarga,
            'totalJamaah'        => $jamaahList->count(),
            'totalLakiLaki'      => $jamaahList->where('jenis_kelamin', 'L')->count(),
            'totalPerempuan'     => $jamaahList->where('jenis_kelamin', 'P')->count(),
            'tanggal_cetak'      => now()->format('d/m/Y H:i'),
        ]);
    }

    public function title(): string
    {
        return 'Manifest SIKOPAT';
    }

    /**
     * ✅ Paksa kolom NIK & No Passport sebagai TEXT
     * agar tidak berubah jadi scientific notation (7,27103E+15)
     *
     * Posisi kolom:
     *   D = NIK
     *   G = No Passport
     */
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true, 'size' => 11]],
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E3A8A'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ],
        ];
    }
}
