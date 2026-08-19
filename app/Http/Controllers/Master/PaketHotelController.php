<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\PaketHotel;
use App\Models\ProdukPaket;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaketHotelController extends Controller
{
    public function index(Request $request)
    {
        $query = PaketHotel::with(['produk', 'hotel']);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('produk', function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%");
            })->orWhereHas('hotel', function($q) use ($search) {
                $q->where('nama_hotel', 'like', "%{$search}%");
            });
        }

        if ($request->has('produk_id') && !empty($request->produk_id)) {
            $query->where('id_produk', $request->produk_id);
        }

        $data = $query->orderBy('id_produk')->orderBy('urutan')->paginate(10);
        $produkOptions = ProdukPaket::where('is_active', true)->orderBy('nama_produk')->get();
        $hotelOptions = Hotel::orderBy('nama_hotel')->get();

        if ($request->ajax()) {
            return view('paket-hotels.table', compact('data', 'produkOptions', 'hotelOptions'));
        }

        return view('paket-hotels.index', compact('data', 'produkOptions', 'hotelOptions'));
    }

    public function create()
    {
        $produkOptions = ProdukPaket::where('is_active', true)->orderBy('nama_produk')->get();
        $hotelOptions = Hotel::orderBy('nama_hotel')->get();
        return view('paket-hotels.create', compact('produkOptions', 'hotelOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_produk' => 'required|exists:produk_pakets,id_produk',
            'id_hotel' => 'required|exists:hotels,id_hotel',
            'urutan' => 'nullable|integer|min:1',
            'adalah_default' => 'nullable|boolean',
            'tipe_penginapan' => 'nullable|string|max:50',
            'harga_per_orang' => 'nullable|integer|min:0',
        ]);

        // Jika default, set semua default lainnya menjadi false
        if ($request->boolean('adalah_default')) {
            PaketHotel::where('id_produk', $request->id_produk)
                ->where('adalah_default', true)
                ->update(['adalah_default' => false]);
        }

        $validated['adalah_default'] = $request->boolean('adalah_default', false);

        $paketHotel = PaketHotel::create($validated);

        return redirect()->route('master.paket-hotel.index')
            ->with('success', "Paket hotel untuk produk '{$paketHotel->produk->nama_produk}' berhasil ditambahkan!");
    }

    public function show($id)
    {
        $paketHotel = PaketHotel::with(['produk', 'hotel'])->findOrFail($id);
        return view('paket-hotels.show', compact('paketHotel'));
    }

    public function edit($id)
    {
        $paketHotel = PaketHotel::findOrFail($id);
        $produkOptions = ProdukPaket::where('is_active', true)->orderBy('nama_produk')->get();
        $hotelOptions = Hotel::orderBy('nama_hotel')->get();
        return view('paket-hotels.edit', compact('paketHotel', 'produkOptions', 'hotelOptions'));
    }

    public function update(Request $request, $id)
    {
        $paketHotel = PaketHotel::findOrFail($id);

        $validated = $request->validate([
            'id_produk' => 'required|exists:produk_pakets,id_produk',
            'id_hotel' => 'required|exists:hotels,id_hotel',
            'urutan' => 'nullable|integer|min:1',
            'adalah_default' => 'nullable|boolean',
            'tipe_penginapan' => 'nullable|string|max:50',
            'harga_per_orang' => 'nullable|integer|min:0',
        ]);

        // Jika default, set semua default lainnya menjadi false
        if ($request->boolean('adalah_default')) {
            PaketHotel::where('id_produk', $request->id_produk)
                ->where('id_paket_hotel', '!=', $id)
                ->where('adalah_default', true)
                ->update(['adalah_default' => false]);
        }

        $validated['adalah_default'] = $request->boolean('adalah_default', false);

        $paketHotel->update($validated);

        return redirect()->route('master.paket-hotel.index')
            ->with('success', "Paket hotel berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $paketHotel = PaketHotel::findOrFail($id);
        $nama = $paketHotel->produk->nama_produk . ' - ' . $paketHotel->hotel->nama_hotel;
        $paketHotel->delete();

        return redirect()->route('master.paket-hotel.index')
            ->with('success', "Paket hotel '{$nama}' berhasil dihapus!");
    }
}
