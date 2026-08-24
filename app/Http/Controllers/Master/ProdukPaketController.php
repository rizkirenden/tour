<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\PaketTour;
use App\Services\ProdukPaketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        if ($request->ajax()) {
            return view('produk-pakets.table', compact('data'));
        }

        return view('produk-pakets.index', compact('data'));
    }

    public function create()
    {
        $paketTours = PaketTour::orderBy('kota_tujuan')->get();
        return view('produk-pakets.create', compact('paketTours'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'include_tur' => 'nullable|boolean',
            'paket_tour_id' => 'nullable|exists:paket_tours,id_paket_tour',
            'harga_dasar' => 'nullable|integer|min:0',
            'durasi_perjalanan' => 'nullable|integer|min:0',
            'durasi_mekkah' => 'nullable|integer|min:0',
            'durasi_madinah' => 'nullable|integer|min:0',
            'kategori' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'flyer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $validated['include_tur'] = $request->boolean('include_tur', false);
        $validated['is_active'] = $request->boolean('is_active', true);

        // Jika include_tur = false, set paket_tour_id menjadi null
        if (!$validated['include_tur']) {
            $validated['paket_tour_id'] = null;
        }

        // Upload flyer
        if ($request->hasFile('flyer')) {
            $file = $request->file('flyer');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('produk-flyers', $filename, 'public');
            $validated['flyer'] = $path;
        }

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
        $paketTours = PaketTour::orderBy('kota_tujuan')->get();

        return view('produk-pakets.edit', compact('produk', 'paketTours'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'include_tur' => 'nullable|boolean',
            'paket_tour_id' => 'nullable|exists:paket_tours,id_paket_tour',
            'harga_dasar' => 'nullable|integer|min:0',
            'durasi_perjalanan' => 'nullable|integer|min:0',
            'durasi_mekkah' => 'nullable|integer|min:0',
            'durasi_madinah' => 'nullable|integer|min:0',
            'kategori' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'flyer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $validated['include_tur'] = $request->boolean('include_tur', false);
        $validated['is_active'] = $request->boolean('is_active', true);

        // Jika include_tur = false, set paket_tour_id menjadi null
        if (!$validated['include_tur']) {
            $validated['paket_tour_id'] = null;
        }

        // Upload flyer
        $produk = $this->service->getById($id);
        if ($request->hasFile('flyer')) {
            if ($produk->flyer && Storage::disk('public')->exists($produk->flyer)) {
                Storage::disk('public')->delete($produk->flyer);
            }

            $file = $request->file('flyer');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('produk-flyers', $filename, 'public');
            $validated['flyer'] = $path;
        }

        $produk = $this->service->update($id, $validated);

        return redirect()->route('master.produk.index')
            ->with('success', "Produk paket '{$produk->nama_produk}' berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $produk = $this->service->getById($id);

        if ($produk->flyer && Storage::disk('public')->exists($produk->flyer)) {
            Storage::disk('public')->delete($produk->flyer);
        }

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
}
