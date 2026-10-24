<?php

namespace App\Http\Controllers\Transaksional;

use App\Http\Controllers\Controller;
use App\Services\RekapKeuanganService;
use App\Services\DepartureService;
use App\Models\PengeluaranLainLain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class RekapKeuanganController extends Controller
{
    protected $service;
    protected $departureService;

    public function __construct(RekapKeuanganService $service, DepartureService $departureService)
    {
        $this->service = $service;
        $this->departureService = $departureService;
    }

    private function getFilters(Request $request): array
    {
        return $request->only([
            'dari_tanggal',
            'sampai_tanggal',
            'id_produk',
            'status_selesai',
            'kategori_pengeluaran',
            'search_pengeluaran',
        ]);
    }

    public function index(Request $request)
    {
        $filters = $this->getFilters($request);
        $rekap = $this->service->getRekapLengkap($filters);
        $perKategori = $this->service->getPengeluaranPerKategori($filters);
        $kategoriOptions = $this->service->getKategoriOptions();
        $produkOptions = $this->departureService->getProdukOptions();

        return view('rekap-keuangan.index', [
            'departure'       => $rekap['departure'],
            'pengeluaran'     => $rekap['pengeluaran'],
            'totalBersih'     => $rekap['total_bersih'],
            'perKategori'     => $perKategori,
            'kategoriOptions' => $kategoriOptions,
            'produkOptions'   => $produkOptions,
            'filters'         => $filters,
        ]);
    }

    public function create(Request $request)
    {
        $kategoriOptions = $this->service->getKategoriOptions();

        return view('rekap-keuangan.create', [
            'kategoriOptions' => $kategoriOptions,
            'filters'         => $this->getFilters($request),
        ]);
    }

    public function storePengeluaran(Request $request)
    {
        $raw = preg_replace('/[^\d]/', '', $request->input('nominal'));
        $request->merge(['nominal' => (int) $raw]);

        $validated = $request->validate([
            'tanggal'          => 'required|date',
            'nama_pengeluaran' => 'required|string|max:150',
            'kategori'         => 'nullable|string|max:100',
            'nominal'          => 'required|integer|min:1',
            'keterangan'       => 'nullable|string',
        ]);

        // id_user nullable, aman meski tabel users belum ada
        $validated['id_user'] = Auth::check() ? Auth::id() : null;

        $item = $this->service->addPengeluaran($validated);

        return redirect()
            ->route('transaksional.rekap-keuangan.show', $item->id_pengeluaran_lain)
            ->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function show($id)
    {
        // ⚠️ TIDAK memakai ->with('user') — relasi user belum tersedia
        $item = PengeluaranLainLain::findOrFail($id);

        return view('rekap-keuangan.show', compact('item'));
    }

    public function edit($id)
    {
        $item = PengeluaranLainLain::findOrFail($id);
        $kategoriOptions = $this->service->getKategoriOptions();

        return view('rekap-keuangan.edit', compact('item', 'kategoriOptions'));
    }

    public function updatePengeluaran(Request $request, $id)
    {
        $raw = preg_replace('/[^\d]/', '', $request->input('nominal'));
        $request->merge(['nominal' => (int) $raw]);

        $validated = $request->validate([
            'tanggal'          => 'required|date',
            'nama_pengeluaran' => 'required|string|max:150',
            'kategori'         => 'nullable|string|max:100',
            'nominal'          => 'required|integer|min:1',
            'keterangan'       => 'nullable|string',
        ]);

        $this->service->updatePengeluaran($id, $validated);

        return redirect()
            ->route('transaksional.rekap-keuangan.show', $id)
            ->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroyPengeluaran($id)
    {
        $this->service->deletePengeluaran($id);

        return redirect()
            ->route('transaksional.rekap-keuangan.index')
            ->with('success', 'Pengeluaran berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $filters = $this->getFilters($request);
        $rekap = $this->service->getRekapLengkap($filters);
        $perKategori = $this->service->getPengeluaranPerKategori($filters);

        $pdf = Pdf::loadView('rekap-keuangan.pdf', [
            'departure'     => $rekap['departure'],
            'pengeluaran'   => $rekap['pengeluaran'],
            'totalBersih'   => $rekap['total_bersih'],
            'perKategori'   => $perKategori,
            'filters'       => $filters,
            'tanggal_cetak' => now()->format('d/m/Y H:i'),
            'dicetak_oleh'  => Auth::check() ? Auth::user()->name : 'System',
        ]);

        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'isPhpEnabled' => false,
            'chroot' => public_path(),
        ]);

        return $pdf->download('Rekap_Keuangan_' . date('Ymd_His') . '.pdf');
    }
}