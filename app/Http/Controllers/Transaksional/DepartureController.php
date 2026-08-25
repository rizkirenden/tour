<?php
// app/Http/Controllers/Transaksional/DepartureController.php

namespace App\Http\Controllers\Transaksional;

use App\Http\Controllers\Controller;
use App\Services\DepartureService;
use App\Models\Departure;
use App\Models\Jamaah;
use App\Models\ProdukPaket;
use App\Models\Kamar;
use App\Models\DepartureJamaah;
use App\Models\DeparturePerlengkapan;
use App\Models\DepartureJenisTransaksi;
use App\Models\JenisTransaksi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartureController extends Controller
{
    protected $service;

    public function __construct(DepartureService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status']);
        $data = $this->service->getAll($filters);

        if ($request->ajax()) {
            return view('departures.table', compact('data'));
        }

        return view('departures.index', compact('data'));
    }

    public function create()
    {
        $produkOptions = $this->service->getProdukOptions();
        $statusOptions = $this->service->getStatusOptions();
        $maskapaiOptions = $this->service->getMaskapaiOptions();
        $hotelOptions = $this->service->getHotelOptions();
        $jamaahs = $this->service->getAvailableJamaahs();

        return view('departures.create', compact(
            'produkOptions',
            'statusOptions',
            'maskapaiOptions',
            'hotelOptions',
            'jamaahs'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_produk' => 'required|exists:produk_pakets,id_produk',
            'nama_keberangkatan' => 'required|string|max:100',
            'tanggal_keberangkatan' => 'required|date',
            'tanggal_kepulangan' => 'required|date|after:tanggal_keberangkatan',
            'kuota' => 'required|integer|min:1',
            'id_status' => 'nullable|exists:status_keberangkatans,id_status',
        ]);

        $departure = $this->service->create($validated);

        return redirect()->route('transaksional.departure.show', $departure->id_departure)
            ->with('success', "Keberangkatan '{$departure->nama_keberangkatan}' berhasil dibuat! Silakan lengkapi data lainnya.");
    }

   public function show($id)
{
    $departure = $this->service->getById($id);
    $maskapaiOptions = $this->service->getMaskapaiOptions();
    $hotelOptions = $this->service->getHotelOptions();
    $jamaahs = $this->service->getAvailableJamaahs($id);
    $perlengkapanOptions = $this->service->getPerlengkapanOptionsForDeparture($id);
    $jenisTransaksiOptions = $this->service->getAvailableJenisTransaksi($id);

    // Load kamars untuk semua hotel yang ada di paket tour
    if ($departure->produk && $departure->produk->paketTour) {
        $departure->produk->paketTour->load('hotels.kamars');
    }

    return view('departures.show', compact(
        'departure',
        'maskapaiOptions',
        'hotelOptions',
        'jamaahs',
        'perlengkapanOptions',
        'jenisTransaksiOptions'
    ));
}

    public function edit($id)
    {
        $departure = $this->service->getById($id);
        $produkOptions = $this->service->getProdukOptions();
        $statusOptions = $this->service->getStatusOptions();

        return view('departures.edit', compact(
            'departure',
            'produkOptions',
            'statusOptions'
        ));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_produk' => 'required|exists:produk_pakets,id_produk',
            'nama_keberangkatan' => 'required|string|max:100',
            'tanggal_keberangkatan' => 'required|date',
            'tanggal_kepulangan' => 'required|date|after:tanggal_keberangkatan',
            'kuota' => 'required|integer|min:1',
            'id_status' => 'nullable|exists:status_keberangkatans,id_status',
        ]);

        $departure = $this->service->update($id, $validated);

        return redirect()->route('transaksional.departure.show', $id)
            ->with('success', "Informasi dasar keberangkatan berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $nama = $this->service->delete($id);

        return redirect()->route('transaksional.departure.index')
            ->with('success', "Keberangkatan '{$nama}' berhasil dihapus!");
    }

    // ==========================================
    // AJAX METHODS
    // ==========================================

    public function getJamaahByProduk($idProduk)
    {
        $produk = ProdukPaket::find($idProduk);

        if (!$produk) {
            return response()->json([
                'html' => '<p class="text-sm text-gray-400 col-span-3 text-center py-4">Produk tidak ditemukan</p>',
                'count' => 0
            ]);
        }

        $subquery = DepartureJamaah::select('id_jamaah')
            ->join('departures', 'departures.id_departure', '=', 'departure_jamaahs.id_departure')
            ->join('status_keberangkatans', 'status_keberangkatans.id_status', '=', 'departures.id_status')
            ->whereIn('status_keberangkatans.nama_status', ['Aktif', 'Berangkat']);

        $jamaahs = Jamaah::where('produk_paket', $produk->nama_produk)
                         ->whereNotIn('id_jamaah', $subquery)
                         ->orderBy('nama_lengkap')
                         ->get();

        $html = '';
        if ($jamaahs->count() > 0) {
            foreach ($jamaahs as $jamaah) {
                $html .= '
                <label class="flex items-center p-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                    <input type="checkbox" name="jamaah_ids[]" value="' . $jamaah->id_jamaah . '"
                        class="jamaah-checkbox w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                    <div class="ml-2">
                        <p class="text-sm font-medium text-gray-700">' . $jamaah->nama_lengkap . '</p>
                        <p class="text-xs text-gray-400">' . $jamaah->produk_paket . ' | ' . $jamaah->status_pembayaran . '</p>
                    </div>
                </label>';
            }
        } else {
            $html = '<p class="text-sm text-gray-400 col-span-3 text-center py-4">
                        Tidak ada jamaah dengan produk ' . $produk->nama_produk . ' yang tersedia.
                        <br><span class="text-xs">Pastikan jamaah belum terdaftar di departure lain yang aktif</span>
                    </p>';
        }

        return response()->json([
            'html' => $html,
            'count' => $jamaahs->count(),
            'produk' => $produk->nama_produk
        ]);
    }

    public function getKamarsByHotel($idHotel)
    {
        $kamars = Kamar::where('id_hotel', $idHotel)
                      ->orderBy('tipe_kamar')
                      ->get();

        return response()->json($kamars);
    }

    public function getKamarsByHotelWithSelected($idHotel, $departureId)
    {
        $hotel = \App\Models\Hotel::find($idHotel);

        if (!$hotel) {
            return response()->json([
                'html' => '<p class="text-sm text-gray-400 col-span-2 text-center py-4">Hotel tidak ditemukan</p>',
                'count' => 0
            ]);
        }

        $departure = $this->service->getById($departureId);

        $selectedKamars = [];
        $details = collect();

        if ($idHotel == $departure->id_hotel_mekkah) {
            $selectedKamars = $departure->hotelMekkahDetails->pluck('id_kamar')->toArray();
            $details = $departure->hotelMekkahDetails;
        } elseif ($idHotel == $departure->id_hotel_madinah) {
            $selectedKamars = $departure->hotelMadinahDetails->pluck('id_kamar')->toArray();
            $details = $departure->hotelMadinahDetails;
        } elseif ($idHotel == $departure->id_hotel_transit) {
            $selectedKamars = $departure->hotelTransitDetails->pluck('id_kamar')->toArray();
            $details = $departure->hotelTransitDetails;
        }

        $kamars = Kamar::where('id_hotel', $idHotel)
                      ->orderBy('tipe_kamar')
                      ->get();

        $html = '';
        if ($kamars->count() > 0) {
            foreach ($kamars as $kamar) {
                $isChecked = in_array($kamar->id_kamar, $selectedKamars) ? 'checked' : '';
                $detail = $details->where('id_kamar', $kamar->id_kamar)->first();

                $jumlah = $detail->jumlah_kamar ?? 1;
                $harga = $detail->harga_per_malam ?? $kamar->harga_per_malam ?? 0;
                $durasi = $detail->durasi_menginap ?? 1;
                $catatan = $detail->catatan ?? '';

                $html .= '
                <div class="kamar-item p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    <div class="flex items-start gap-3">
                        <input type="checkbox" name="kamar_ids[]" value="' . $kamar->id_kamar . '" ' . $isChecked . '
                            class="kamar-checkbox w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500 mt-1">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-gray-700">' . $kamar->tipe_kamar . '</p>
                                <span class="text-xs text-gray-500">Kapasitas: ' . $kamar->kapasitas . ' orang</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mt-2">
                                <div>
                                    <label class="text-xs text-gray-500">Jumlah Kamar</label>
                                    <input type="number" name="kamar_jumlah[' . $kamar->id_kamar . ']" value="' . $jumlah . '"
                                        class="kamar-jumlah w-full px-2 py-1 border border-gray-200 rounded text-sm"
                                        min="1">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Harga/Malam</label>
                                    <input type="number" name="kamar_harga[' . $kamar->id_kamar . ']" value="' . $harga . '"
                                        class="kamar-harga w-full px-2 py-1 border border-gray-200 rounded text-sm"
                                        min="0">
                                </div>
                            </div>
                            <div class="mt-2">
                                <label class="text-xs text-gray-500">Durasi Menginap (Malam)</label>
                                <input type="number" name="kamar_durasi[' . $kamar->id_kamar . ']" value="' . $durasi . '"
                                    class="kamar-durasi w-full px-2 py-1 border border-gray-200 rounded text-sm"
                                    min="1">
                            </div>
                            <div class="mt-2">
                                <label class="text-xs text-gray-500">Catatan</label>
                                <input type="text" name="kamar_catatan[' . $kamar->id_kamar . ']" value="' . $catatan . '"
                                    class="kamar-catatan w-full px-2 py-1 border border-gray-200 rounded text-sm"
                                    placeholder="Catatan untuk tipe kamar ini">
                            </div>
                        </div>
                    </div>
                </div>';
            }
        } else {
            $html = '<p class="text-sm text-gray-400 col-span-2 text-center py-4">Tidak ada tipe kamar untuk hotel ini</p>';
        }

        return response()->json([
            'html' => $html,
            'count' => $kamars->count(),
            'hotel' => $hotel->nama_hotel
        ]);
    }

    public function getPerlengkapanDetail($perlengkapanId)
    {
        $departurePerlengkapan = DeparturePerlengkapan::with([
            'perlengkapan',
            'perlengkapanJamaahs.jamaah'
        ])->findOrFail($perlengkapanId);

        $jamaahs = $departurePerlengkapan->perlengkapanJamaahs;
        $total = $jamaahs->count();
        $sudahTerima = $jamaahs->where('status_terima', 'Sudah Diterima')->count();
        $belumTerima = $total - $sudahTerima;
        $csrfToken = csrf_token();
        $route = route('transaksional.departure.update-perlengkapan-status-jamaah', [$departurePerlengkapan->id, 0]);
        $route = str_replace('/0', '/' . $departurePerlengkapan->id, $route);

        $html = '<div class="mb-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-800">' . $departurePerlengkapan->perlengkapan->nama_perlengkapan . '</p>
                    <p class="text-xs text-gray-400">' . $departurePerlengkapan->jumlah_per_jamaah . ' per jamaah · ' . $departurePerlengkapan->harga_satuan_formatted . '</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-yellow-600">' . $departurePerlengkapan->total_harga_formatted . '</p>
                    <p class="text-xs text-gray-400">Total Harga</p>
                </div>
            </div>
            <div class="mt-2 flex items-center gap-4 text-sm">
                <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i> ' . $sudahTerima . ' Sudah Diterima</span>
                <span class="text-yellow-600"><i class="fas fa-clock mr-1"></i> ' . $belumTerima . ' Belum Diterima</span>
                <span class="text-gray-500">Total: ' . $total . ' jamaah</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                <div class="h-2 rounded-full ' . ($sudahTerima == $total ? 'bg-green-500' : 'bg-yellow-500') . '"
                    style="width: ' . ($total > 0 ? round(($sudahTerima / $total) * 100) : 0) . '%"></div>
            </div>
        </div>
        <div class="border-t border-gray-200 pt-4">
            <p class="text-xs font-medium text-gray-500 mb-3">Daftar Jamaah:</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">';

        foreach ($jamaahs as $item) {
            $statusClass = $item->status_terima == 'Sudah Diterima' ? 'bg-green-50 border-green-200' : 'bg-yellow-50 border-yellow-200';
            $statusIcon = $item->status_terima == 'Sudah Diterima'
                ? '<span class="text-green-500"><i class="fas fa-check-circle"></i></span>'
                : '<span class="text-yellow-500"><i class="fas fa-clock"></i></span>';
            $statusText = $item->status_terima == 'Sudah Diterima'
                ? '<span class="text-green-600 text-xs">Sudah Diterima</span>'
                : '<span class="text-yellow-600 text-xs">Belum Diterima</span>';

            $html .= '
            <div class="flex items-center justify-between p-2 rounded-lg border ' . $statusClass . '">
                <div class="flex items-center gap-2">
                    ' . $statusIcon . '
                    <span class="text-sm font-medium text-gray-700">' . $item->jamaah->nama_lengkap . '</span>
                </div>
                <div class="flex items-center gap-2">
                    ' . $statusText . '
                    <button onclick="toggleStatusJamaah(' . $departurePerlengkapan->id . ', ' . $item->id_jamaah . ', \'' . $item->status_terima . '\')"
                        class="text-blue-500 hover:text-blue-700 text-xs">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <form id="status-jamaah-form-' . $departurePerlengkapan->id . '-' . $item->id_jamaah . '"
                        action="' . $route . '"
                        method="POST" class="hidden">
                        <input type="hidden" name="_token" value="' . $csrfToken . '">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="status_terima" id="status_input_' . $departurePerlengkapan->id . '_' . $item->id_jamaah . '">
                    </form>
                </div>
            </div>';
        }

        $html .= '
            </div>
        </div>';

        return response()->json([
            'html' => $html,
            'nama_perlengkapan' => $departurePerlengkapan->perlengkapan->nama_perlengkapan
        ]);
    }

    // ==========================================
    // UPDATE METHODS (Step by Step)
    // ==========================================

    public function updateMaskapai(Request $request, $id)
    {
        $validated = $request->validate([
            'id_maskapai_domestik_berangkat' => 'nullable|exists:maskapais,id_maskapai',
            'harga_maskapai_domestik_berangkat' => 'nullable|integer|min:0',
            'id_maskapai_domestik_pulang' => 'nullable|exists:maskapais,id_maskapai',
            'harga_maskapai_domestik_pulang' => 'nullable|integer|min:0',
            'id_maskapai_internasional_berangkat' => 'nullable|exists:maskapais,id_maskapai',
            'harga_maskapai_internasional_berangkat' => 'nullable|integer|min:0',
            'id_maskapai_internasional_pulang' => 'nullable|exists:maskapais,id_maskapai',
            'harga_maskapai_internasional_pulang' => 'nullable|integer|min:0',
        ]);

        $departure = $this->service->updateMaskapai($id, $validated);

        return redirect()->route('transaksional.departure.show', $id)
            ->with('success', 'Data maskapai berhasil diperbarui!');
    }

    public function updateHotel(Request $request, $id)
    {
        $validated = $request->validate([
            'id_hotel_mekkah' => 'nullable|exists:hotels,id_hotel',
            'id_hotel_madinah' => 'nullable|exists:hotels,id_hotel',
            'id_hotel_transit' => 'nullable|exists:hotels,id_hotel',
            'kamar_ids' => 'nullable|array',
            'kamar_jumlah' => 'nullable|array',
            'kamar_harga' => 'nullable|array',
            'kamar_durasi' => 'nullable|array',
            'kamar_catatan' => 'nullable|array',
        ]);

        $departure = $this->service->updateHotel($id, $validated);

        return redirect()->route('transaksional.departure.show', $id)
            ->with('success', 'Data hotel berhasil diperbarui!');
    }

    public function updateJamaah(Request $request, $id)
    {
        $validated = $request->validate([
            'jamaah_ids' => 'required|array|min:1',
            'jamaah_ids.*' => 'exists:jamaahs,id_jamaah',
        ]);

        $departure = $this->service->updateJamaah($id, $validated);

        return redirect()->route('transaksional.departure.show', $id)
            ->with('success', 'Daftar jamaah berhasil diperbarui!');
    }

    public function updatePerlengkapan(Request $request, $id)
    {
        $validated = $request->validate([
            'id_perlengkapan' => 'required|array|min:1',
            'id_perlengkapan.*' => 'exists:perlengkapans,id_perlengkapan',
            'jumlah_per_jamaah' => 'nullable|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        try {
            $result = $this->service->addMultiplePerlengkapanToDeparture($id, $validated['id_perlengkapan'], [
                'jumlah_per_jamaah' => $validated['jumlah_per_jamaah'] ?? 1,
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            return redirect()->route('transaksional.departure.show', $id)
                ->with('success', $result['count'] . ' perlengkapan berhasil ditambahkan ke departure!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function removePerlengkapan($departureId, $departurePerlengkapanId)
    {
        $this->service->removePerlengkapanFromDeparture($departureId, $departurePerlengkapanId);

        return redirect()->route('transaksional.departure.show', $departureId)
            ->with('success', 'Perlengkapan berhasil dihapus dari departure!');
    }

    public function togglePerlengkapan($departureId, $departurePerlengkapanId)
    {
        $departurePerlengkapan = $this->service->togglePerlengkapanStatus($departureId, $departurePerlengkapanId);
        $status = $departurePerlengkapan->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('transaksional.departure.show', $departureId)
            ->with('success', "Perlengkapan berhasil {$status}!");
    }

    public function updatePerlengkapanStatusJamaah(Request $request, $departurePerlengkapanId, $jamaahId)
    {
        $validated = $request->validate([
            'status_terima' => 'required|in:Belum Diterima,Sudah Diterima',
        ]);

        $this->service->updatePerlengkapanStatusJamaah($departurePerlengkapanId, $jamaahId, $validated['status_terima']);

        return redirect()->back()
            ->with('success', 'Status penerimaan perlengkapan berhasil diperbarui!');
    }

    // ==========================================
    // JENIS TRANSAKSI METHODS - MULTIPLE
    // ==========================================

    public function updateJenisTransaksi(Request $request, $id)
    {
        $validated = $request->validate([
            'id_jenis_transaksi' => 'required|array|min:1',
            'id_jenis_transaksi.*' => 'exists:jenis_transaksis,id_jenis',
            'harga_satuan' => 'nullable|array',
            'harga_satuan.*' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|array',
            'catatan.*' => 'nullable|string',
        ]);

        try {
            $jenisTransaksiData = [];
            foreach ($validated['id_jenis_transaksi'] as $jenisTransaksiId) {
                $jenisTransaksiData[] = [
                    'id_jenis_transaksi' => $jenisTransaksiId,
                    'harga_satuan' => $validated['harga_satuan'][$jenisTransaksiId] ?? 0,
                    'catatan' => $validated['catatan'][$jenisTransaksiId] ?? null,
                ];
            }

            $result = $this->service->addMultipleJenisTransaksiToDeparture($id, $jenisTransaksiData);

            return redirect()->route('transaksional.departure.show', $id)
                ->with('success', $result['count'] . ' jenis transaksi berhasil ditambahkan ke departure!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function removeJenisTransaksi($departureId, $jenisTransaksiId)
    {
        $this->service->removeJenisTransaksiFromDeparture($departureId, $jenisTransaksiId);

        return redirect()->route('transaksional.departure.show', $departureId)
            ->with('success', 'Jenis transaksi berhasil dihapus!');
    }

    public function updateJenisTransaksiHarga(Request $request, $departureId, $jenisTransaksiId)
    {
        $validated = $request->validate([
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        $this->service->updateJenisTransaksiHarga($departureId, $jenisTransaksiId, $validated['harga_satuan']);

        return redirect()->route('transaksional.departure.show', $departureId)
            ->with('success', 'Harga jenis transaksi berhasil diperbarui!');
    }

    // ==========================================
    // UPDATE CATATAN
    // ==========================================

    public function updateCatatan(Request $request, $id)
    {
        $validated = $request->validate([
            'catatan' => 'nullable|string',
        ]);

        $departure = $this->service->updateCatatan($id, $validated);

        return redirect()->route('transaksional.departure.show', $id)
            ->with('success', 'Catatan berhasil diperbarui!');
    }

    // ==========================================
    // JAMAHA MANAGEMENT
    // ==========================================

    public function addJamaah(Request $request, $id)
    {
        $validated = $request->validate([
            'id_jamaah' => 'required|exists:jamaahs,id_jamaah',
            'catatan' => 'nullable|string',
        ]);

        try {
            $departure = $this->service->addJamaah($id, $validated['id_jamaah'], $validated['catatan'] ?? null);
            return redirect()->route('transaksional.departure.show', $id)
                ->with('success', 'Jamaah berhasil ditambahkan ke keberangkatan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function removeJamaah($departureId, $jamaahId)
    {
        $this->service->removeJamaah($departureId, $jamaahId);

        return redirect()->route('transaksional.departure.show', $departureId)
            ->with('success', 'Jamaah berhasil dihapus dari keberangkatan!');
    }

    // ==========================================
    // STATUS & RECALCULATE
    // ==========================================

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'id_status' => 'required|exists:status_keberangkatans,id_status',
        ]);

        $departure = $this->service->updateStatus($id, $validated['id_status']);

        return redirect()->route('transaksional.departure.show', $id)
            ->with('success', "Status berhasil diubah menjadi '{$departure->statusKeberangkatan->nama_status}'!");
    }

    public function recalculate($id)
    {
        $departure = $this->service->getById($id);
        $departure->recalculate();

        return redirect()->route('transaksional.departure.show', $id)
            ->with('success', 'Perhitungan keuangan berhasil diperbarui!');
    }

    public function recalculateAll()
    {
        $this->service->recalculateAll();

        return redirect()->route('transaksional.departure.index')
            ->with('success', 'Semua perhitungan keuangan berhasil diperbarui!');
    }
    public function updatePaketTourHotel(Request $request, $id)
{
    $validated = $request->validate([
        'id_paket_tour' => 'required|exists:paket_tours,id_paket_tour',
        'paket_tour_hotels' => 'nullable|array',
        'paket_tour_hotels.*.id_hotel' => 'nullable|exists:hotels,id_hotel',
        'paket_tour_hotels.*.harga_per_malam' => 'nullable|integer|min:0',
        'paket_tour_hotels.*.durasi_menginap' => 'nullable|integer|min:1',
        'paket_tour_hotels.*.jumlah_kamar' => 'nullable|integer|min:1',
        'paket_tour_hotels.*.tipe_kamar' => 'nullable|string|max:100',
        'paket_tour_hotels.*.catatan' => 'nullable|string',
    ]);

    $departure = $this->service->updatePaketTourHotel($id, $validated);

    return redirect()->route('transaksional.departure.show', $id)
        ->with('success', 'Data hotel tour berhasil diperbarui!');
}

public function getPaketTourHotels($id)
{
    $hotels = $this->service->getPaketTourHotelsByDeparture($id);
    return response()->json($hotels);
}
}
