<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\PaketTour;
use App\Models\Perlengkapan;
use App\Models\StatusKeberangkatan;
use App\Services\ProdukPaketService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProdukPaketController extends Controller
{
    protected $service;

    public function __construct(ProdukPaketService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search']);
        $data = $this->service->getAll($filters);
        $statusKeberangkatans = StatusKeberangkatan::orderBy('nama_status')->get();

        if ($request->ajax()) {
            return view('produk-pakets.table', compact('data', 'statusKeberangkatans'));
        }

        return view('produk-pakets.index', compact('data', 'statusKeberangkatans'));
    }

    public function create()
    {
        $hotels = Hotel::orderBy('nama_hotel')->get();
        $paketTours = PaketTour::orderBy('kota_tujuan')->get();
        $statusKeberangkatans = StatusKeberangkatan::orderBy('nama_status')->get();
        $perlengkapans = Perlengkapan::orderBy('nama_perlengkapan')->get();

        return view('produk-pakets.create', compact('hotels', 'paketTours', 'statusKeberangkatans', 'perlengkapans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_produk' => 'nullable|string|max:20|unique:produk_pakets,kode_produk',
            'nama_produk' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga_dasar' => 'required|integer|min:0',
            'hotel_mekkah_default' => 'nullable|exists:hotels,id_hotel',
            'hotel_madinah_default' => 'nullable|exists:hotels,id_hotel',
            'hotel_transit_default' => 'nullable|exists:hotels,id_hotel',
            'include_tur' => 'nullable|boolean',
            'paket_tour_id' => 'nullable|exists:paket_tours,id_paket_tour',
            'status_keberangkatan_id' => 'nullable|exists:status_keberangkatans,id_status',
            'durasi_mekkah' => 'nullable|integer|min:0',
            'durasi_madinah' => 'nullable|integer|min:0',
            'durasi_transit' => 'nullable|integer|min:0',
            'durasi_hari' => 'required|integer|min:1',
            'harga_visa' => 'nullable|integer|min:0',
            'harga_handling' => 'nullable|integer|min:0',
            'harga_muthowwif' => 'nullable|integer|min:0',
            'kategori' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'perlengkapans' => 'nullable|array',
            'perlengkapans.*.id_perlengkapan' => 'nullable|exists:perlengkapans,id_perlengkapan',
            'perlengkapans.*.kuantitas' => 'nullable|integer|min:1',
            'perlengkapans.*.catatan' => 'nullable|string|max:255',
        ]);

        $validated['include_tur'] = $request->boolean('include_tur', false);
        $validated['is_active'] = $request->boolean('is_active', true);

        // Jika include_tur = false, set paket_tour_id menjadi null
        if (!$validated['include_tur']) {
            $validated['paket_tour_id'] = null;
        }

        // Filter perlengkapan yang memiliki id_perlengkapan
        $perlengkapanData = [];
        if ($request->has('perlengkapans') && is_array($request->perlengkapans)) {
            foreach ($request->perlengkapans as $item) {
                if (!empty($item['id_perlengkapan'])) {
                    $perlengkapanData[] = $item;
                }
            }
        }
        $validated['perlengkapans'] = $perlengkapanData;

        $produk = $this->service->create($validated);

        return redirect()->route('master.produk.index')
            ->with('success', "Produk paket '{$produk->nama_produk}' berhasil ditambahkan!");
    }

    public function show($id)
    {
        $produk = $this->service->getByIdWithRelations($id);
        return view('produk-pakets.show', compact('produk'));
    }

    public function edit($id)
    {
        $produk = $this->service->getByIdWithRelations($id);

        $hotels = Hotel::orderBy('nama_hotel')->get();
        $paketTours = PaketTour::orderBy('kota_tujuan')->get();
        $statusKeberangkatans = StatusKeberangkatan::orderBy('nama_status')->get();
        $perlengkapans = Perlengkapan::orderBy('nama_perlengkapan')->get();

        return view('produk-pakets.edit', compact('produk', 'hotels', 'paketTours', 'statusKeberangkatans', 'perlengkapans'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kode_produk' => ['nullable', 'string', 'max:20', Rule::unique('produk_pakets', 'kode_produk')->ignore($id, 'id_produk')],
            'nama_produk' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga_dasar' => 'required|integer|min:0',
            'hotel_mekkah_default' => 'nullable|exists:hotels,id_hotel',
            'hotel_madinah_default' => 'nullable|exists:hotels,id_hotel',
            'hotel_transit_default' => 'nullable|exists:hotels,id_hotel',
            'include_tur' => 'nullable|boolean',
            'paket_tour_id' => 'nullable|exists:paket_tours,id_paket_tour',
            'status_keberangkatan_id' => 'nullable|exists:status_keberangkatans,id_status',
            'durasi_mekkah' => 'nullable|integer|min:0',
            'durasi_madinah' => 'nullable|integer|min:0',
            'durasi_transit' => 'nullable|integer|min:0',
            'durasi_hari' => 'required|integer|min:1',
            'harga_visa' => 'nullable|integer|min:0',
            'harga_handling' => 'nullable|integer|min:0',
            'harga_muthowwif' => 'nullable|integer|min:0',
            'kategori' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'perlengkapans' => 'nullable|array',
            'perlengkapans.*.id_perlengkapan' => 'nullable|exists:perlengkapans,id_perlengkapan',
            'perlengkapans.*.kuantitas' => 'nullable|integer|min:1',
            'perlengkapans.*.catatan' => 'nullable|string|max:255',
        ]);

        $validated['include_tur'] = $request->boolean('include_tur', false);
        $validated['is_active'] = $request->boolean('is_active', true);

        // Jika include_tur = false, set paket_tour_id menjadi null
        if (!$validated['include_tur']) {
            $validated['paket_tour_id'] = null;
        }

        // Filter perlengkapan yang memiliki id_perlengkapan
        $perlengkapanData = [];
        if ($request->has('perlengkapans') && is_array($request->perlengkapans)) {
            foreach ($request->perlengkapans as $item) {
                if (!empty($item['id_perlengkapan'])) {
                    $perlengkapanData[] = $item;
                }
            }
        }
        $validated['perlengkapans'] = $perlengkapanData;

        $produk = $this->service->update($id, $validated);

        return redirect()->route('master.produk.index')
            ->with('success', "Produk paket '{$produk->nama_produk}' berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $nama = $this->service->delete($id);

        return redirect()->route('master.produk.index')
            ->with('success', "Produk paket '{$nama}' berhasil dihapus!");
    }

    public function toggleStatus($id)
    {
        try {
            $result = $this->service->toggleStatus($id);

            return redirect()->back()
                ->with('success', "Produk paket '{$result['nama']}' berhasil {$result['status']}!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah status produk: ' . $e->getMessage());
        }
    }

    public function updateStatusKeberangkatan(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status_keberangkatan_id' => 'nullable|exists:status_keberangkatans,id_status',
            ]);

            $produk = $this->service->getById($id);
            $produk->update([
                'status_keberangkatan_id' => $validated['status_keberangkatan_id']
            ]);

            return redirect()->back()
                ->with('success', "Status keberangkatan produk '{$produk->nama_produk}' berhasil diperbarui!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupdate status keberangkatan: ' . $e->getMessage());
        }
    }
}
