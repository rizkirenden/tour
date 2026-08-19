<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\PaketTour;
use App\Models\ProdukPaket;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaketTourController extends Controller
{
    public function index(Request $request)
    {
        $query = PaketTour::with('produk');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('produk', function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%");
            })->orWhere('kota_tujuan', 'like', "%{$search}%")
              ->orWhere('negara', 'like', "%{$search}%");
        }

        if ($request->has('produk_id') && !empty($request->produk_id)) {
            $query->where('id_produk', $request->produk_id);
        }

        $data = $query->orderBy('id_produk')->orderBy('durasi_hari')->paginate(10);
        $produkOptions = ProdukPaket::where('is_active', true)->orderBy('nama_produk')->get();

        if ($request->ajax()) {
            return view('paket-tours.table', compact('data', 'produkOptions'));
        }

        return view('paket-tours.index', compact('data', 'produkOptions'));
    }

    public function create()
    {
        $produkOptions = ProdukPaket::where('is_active', true)->orderBy('nama_produk')->get();
        return view('paket-tours.create', compact('produkOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_produk' => 'required|exists:produk_pakets,id_produk',
            'kota_tujuan' => 'nullable|string|max:50',
            'negara' => 'nullable|string|max:50',
            'durasi_hari' => 'nullable|integer|min:1',
            'deskripsi' => 'nullable|string',
            'harga_include' => 'nullable|boolean',
            'harga_tambahan' => 'nullable|integer|min:0',
            'harga_per_orang' => 'nullable|integer|min:0',
        ]);

        $validated['harga_include'] = $request->boolean('harga_include', true);

        $paketTour = PaketTour::create($validated);

        return redirect()->route('master.paket-tour.index')
            ->with('success', "Paket tour untuk produk '{$paketTour->produk->nama_produk}' berhasil ditambahkan!");
    }

    public function show($id)
    {
        $paketTour = PaketTour::with('produk')->findOrFail($id);
        return view('paket-tours.show', compact('paketTour'));
    }

    public function edit($id)
    {
        $paketTour = PaketTour::findOrFail($id);
        $produkOptions = ProdukPaket::where('is_active', true)->orderBy('nama_produk')->get();
        return view('paket-tours.edit', compact('paketTour', 'produkOptions'));
    }

    public function update(Request $request, $id)
    {
        $paketTour = PaketTour::findOrFail($id);

        $validated = $request->validate([
            'id_produk' => 'required|exists:produk_pakets,id_produk',
            'kota_tujuan' => 'nullable|string|max:50',
            'negara' => 'nullable|string|max:50',
            'durasi_hari' => 'nullable|integer|min:1',
            'deskripsi' => 'nullable|string',
            'harga_include' => 'nullable|boolean',
            'harga_tambahan' => 'nullable|integer|min:0',
            'harga_per_orang' => 'nullable|integer|min:0',
        ]);

        $validated['harga_include'] = $request->boolean('harga_include', true);

        $paketTour->update($validated);

        return redirect()->route('master.paket-tour.index')
            ->with('success', "Paket tour berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $paketTour = PaketTour::findOrFail($id);
        $nama = $paketTour->produk->nama_produk . ' - ' . ($paketTour->kota_tujuan ?? 'Tour');
        $paketTour->delete();

        return redirect()->route('master.paket-tour.index')
            ->with('success', "Paket tour '{$nama}' berhasil dihapus!");
    }
}
