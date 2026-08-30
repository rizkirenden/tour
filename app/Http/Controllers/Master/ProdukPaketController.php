<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\PaketTour;
use App\Models\ProdukHargaBulanan;
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
            'durasi_perjalanan' => 'nullable|integer|min:0',
            'durasi_mekkah' => 'nullable|integer|min:0',
            'durasi_madinah' => 'nullable|integer|min:0',
            'kategori' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'harga_bulanan' => 'required|array|min:1',
            'harga_bulanan.*.bulan' => 'required|integer|between:1,12',
            'harga_bulanan.*.tahun' => 'required|integer|min:2024',
            'harga_bulanan.*.harga' => 'required|integer|min:0',
            'harga_bulanan.*.flyer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'harga_bulanan.*.is_active' => 'nullable|boolean',
        ]);

        $validated['include_tur'] = $request->boolean('include_tur', false);
        $validated['is_active'] = $request->boolean('is_active', true);

        if (!$validated['include_tur']) {
            $validated['paket_tour_id'] = null;
        }

        // FLYER DIHAPUS DARI SINI (pindah ke harga bulanan)

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
            'durasi_perjalanan' => 'nullable|integer|min:0',
            'durasi_mekkah' => 'nullable|integer|min:0',
            'durasi_madinah' => 'nullable|integer|min:0',
            'kategori' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'harga_bulanan' => 'required|array|min:1',
            'harga_bulanan.*.id' => 'nullable|exists:produk_harga_bulanan,id',
            'harga_bulanan.*.bulan' => 'required|integer|between:1,12',
            'harga_bulanan.*.tahun' => 'required|integer|min:2024',
            'harga_bulanan.*.harga' => 'required|integer|min:0',
            'harga_bulanan.*.flyer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'harga_bulanan.*.is_active' => 'nullable|boolean',
        ]);

        $validated['include_tur'] = $request->boolean('include_tur', false);
        $validated['is_active'] = $request->boolean('is_active', true);

        if (!$validated['include_tur']) {
            $validated['paket_tour_id'] = null;
        }

        // FLYER DIHAPUS DARI SINI (pindah ke harga bulanan)

        $produk = $this->service->update($id, $validated);

        return redirect()->route('master.produk.index')
            ->with('success', "Produk paket '{$produk->nama_produk}' berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $produk = $this->service->getById($id);

        // Hapus semua flyer dari harga bulanan
        foreach ($produk->hargaBulanan as $harga) {
            if ($harga->flyer && Storage::disk('public')->exists($harga->flyer)) {
                Storage::disk('public')->delete($harga->flyer);
            }
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

    public function getPaketTourInfo($id)
    {
        $paketTour = PaketTour::with('hotels')->find($id);
        if (!$paketTour) {
            return response()->json(['error' => 'Paket tour tidak ditemukan'], 404);
        }

        return response()->json([
            'durasi_hari' => $paketTour->durasi_hari ?? 0,
            'kota_tujuan' => $paketTour->kota_tujuan,
            'negara' => $paketTour->negara,
            'deskripsi' => $paketTour->deskripsi,
            'total_harga_hotel' => $paketTour->total_harga_hotel_formatted ?? 'Rp 0',
        ]);
    }

    // ============================================
    // HARGA BULANAN METHODS
    // ============================================

    public function storeHargaBulanan(Request $request, $produkId)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2024',
            'harga' => 'required|integer|min:0',
            'flyer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $produk = $this->service->getById($produkId);

        $exists = ProdukHargaBulanan::where('produk_paket_id', $produkId)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Harga untuk bulan ' . $request->bulan . '/' . $request->tahun . ' sudah ada!');
        }

        $data = [
            'produk_paket_id' => $produkId,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'harga' => $request->harga,
            'is_active' => $request->boolean('is_active', true),
        ];

        // Upload flyer
        if ($request->hasFile('flyer')) {
            $file = $request->file('flyer');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('produk-flyers', $filename, 'public');
            $data['flyer'] = $path;
        }

        $harga = ProdukHargaBulanan::create($data);

        return redirect()->back()
            ->with('success', "Harga untuk " . $harga->bulan_formatted . " {$harga->tahun} berhasil ditambahkan!");
    }

    public function updateHargaBulanan(Request $request, $hargaId)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2024',
            'harga' => 'required|integer|min:0',
            'flyer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $harga = ProdukHargaBulanan::findOrFail($hargaId);

        $exists = ProdukHargaBulanan::where('produk_paket_id', $harga->produk_paket_id)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->where('id', '!=', $hargaId)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Harga untuk bulan ' . $request->bulan . '/' . $request->tahun . ' sudah ada!');
        }

        $data = [
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'harga' => $request->harga,
            'is_active' => $request->boolean('is_active', true),
        ];

        // Upload flyer baru
        if ($request->hasFile('flyer')) {
            // Hapus flyer lama
            if ($harga->flyer && Storage::disk('public')->exists($harga->flyer)) {
                Storage::disk('public')->delete($harga->flyer);
            }

            $file = $request->file('flyer');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('produk-flyers', $filename, 'public');
            $data['flyer'] = $path;
        }

        $harga->update($data);

        return redirect()->back()
            ->with('success', "Harga untuk " . $harga->bulan_formatted . " {$harga->tahun} berhasil diperbarui!");
    }

    public function destroyHargaBulanan($hargaId)
    {
        $harga = ProdukHargaBulanan::findOrFail($hargaId);
        $produk = $harga->produkPaket;

        if ($produk->hargaBulanan()->count() <= 1) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus data terakhir! Minimal 1 data harga per bulan.');
        }

        // Hapus flyer
        if ($harga->flyer && Storage::disk('public')->exists($harga->flyer)) {
            Storage::disk('public')->delete($harga->flyer);
        }

        $label = $harga->bulan_formatted . ' ' . $harga->tahun;
        $harga->delete();

        return redirect()->back()
            ->with('success', "Harga untuk {$label} berhasil dihapus!");
    }

    public function toggleHargaBulanan($hargaId)
    {
        try {
            $harga = ProdukHargaBulanan::findOrFail($hargaId);
            $harga->is_active = !$harga->is_active;
            $harga->save();

            $status = $harga->is_active ? 'diaktifkan' : 'dinonaktifkan';
            $label = $harga->bulan_formatted . ' ' . $harga->tahun;

            return response()->json([
                'success' => true,
                'message' => "Harga untuk {$label} berhasil {$status}!",
                'is_active' => $harga->is_active,
                'badge' => $harga->is_active
                    ? '<span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Aktif</span>'
                    : '<span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Nonaktif</span>'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }
}
