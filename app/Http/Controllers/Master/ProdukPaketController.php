<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
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

        if ($request->ajax()) {
            return view('produk-pakets.table', compact('data'));
        }

        return view('produk-pakets.index', compact('data'));
    }

    public function create()
    {
        return view('produk-pakets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_produk' => 'nullable|string|max:20|unique:produk_pakets,kode_produk',
            'nama_produk' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga_dasar' => 'required|integer|min:0',
            'hotel_mekkah_default' => 'nullable|string|max:100',
            'hotel_madinah_default' => 'nullable|string|max:100',
            'hotel_transit_default' => 'nullable|string|max:100',
            'multiple_hotel_enabled' => 'nullable|boolean',
            'include_tur' => 'nullable|boolean',
            'kapasitas_kamar_default' => 'nullable|integer|in:3,4',
            'durasi_mekkah' => 'nullable|integer|min:0',
            'durasi_madinah' => 'nullable|integer|min:0',
            'durasi_transit' => 'nullable|integer|min:0',
            'durasi_hari' => 'required|integer|min:1',
            'harga_visa' => 'nullable|integer|min:0',
            'harga_handling' => 'nullable|integer|min:0',
            'harga_muthowwif' => 'nullable|integer|min:0',
            'kategori' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['multiple_hotel_enabled'] = $request->boolean('multiple_hotel_enabled', false);
        $validated['include_tur'] = $request->boolean('include_tur', false);
        $validated['is_active'] = $request->boolean('is_active', true);

        $produk = $this->service->create($validated);

        return redirect()->route('master.produk.index')
            ->with('success', "Produk paket '{$produk->nama_produk}' berhasil ditambahkan!");
    }

    public function show($id)
    {
        $produk = $this->service->getById($id);
        return view('produk-pakets.show', compact('produk'));
    }

    public function edit($id)
    {
        $produk = $this->service->getById($id);
        return view('produk-pakets.edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kode_produk' => ['nullable', 'string', 'max:20', Rule::unique('produk_pakets', 'kode_produk')->ignore($id, 'id_produk')],
            'nama_produk' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga_dasar' => 'required|integer|min:0',
            'hotel_mekkah_default' => 'nullable|string|max:100',
            'hotel_madinah_default' => 'nullable|string|max:100',
            'hotel_transit_default' => 'nullable|string|max:100',
            'multiple_hotel_enabled' => 'nullable|boolean',
            'include_tur' => 'nullable|boolean',
            'kapasitas_kamar_default' => 'nullable|integer|in:3,4',
            'durasi_mekkah' => 'nullable|integer|min:0',
            'durasi_madinah' => 'nullable|integer|min:0',
            'durasi_transit' => 'nullable|integer|min:0',
            'durasi_hari' => 'required|integer|min:1',
            'harga_visa' => 'nullable|integer|min:0',
            'harga_handling' => 'nullable|integer|min:0',
            'harga_muthowwif' => 'nullable|integer|min:0',
            'kategori' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['multiple_hotel_enabled'] = $request->boolean('multiple_hotel_enabled', false);
        $validated['include_tur'] = $request->boolean('include_tur', false);
        $validated['is_active'] = $request->boolean('is_active', true);

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

    /**
     * Toggle status produk (Aktif/Nonaktif)
     */
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
