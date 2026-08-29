<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\ProdukPaketController;
use App\Http\Controllers\Master\MetodePembayaranController;
use App\Http\Controllers\Master\KategoriPengeluaranController;
use App\Http\Controllers\Master\StatusKeberangkatanController;
use App\Http\Controllers\Master\JenisTransaksiController;
use App\Http\Controllers\Master\HotelController;
use App\Http\Controllers\Master\KotaAsalController;
use App\Http\Controllers\Master\MaskapaiController;
use App\Http\Controllers\Master\DiskonController;
use App\Http\Controllers\Master\PerlengkapanController;
use App\Http\Controllers\Master\PaketHotelController;
use App\Http\Controllers\Master\PaketTourController;
use App\Http\Controllers\Transaksional\JamaahController;
use App\Http\Controllers\Transaksional\KeluargaController;
use App\Http\Controllers\Transaksional\DepartureController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// ============================================
// MASTER DATA ROUTES
// ============================================
Route::prefix('master')->name('master.')->group(function () {
    Route::resource('produk', ProdukPaketController::class);
    Route::patch('produk/{id}/toggle-status', [ProdukPaketController::class, 'toggleStatus'])->name('produk.toggle-status');
    Route::get('get-paket-tour-info/{id}', [ProdukPaketController::class, 'getPaketTourInfo'])->name('get-paket-tour-info');
    Route::patch('produk/{id}/update-status-keberangkatan', [ProdukPaketController::class, 'updateStatusKeberangkatan'])->name('produk.update-status-keberangkatan');

    Route::resource('metode-pembayaran', MetodePembayaranController::class);
    Route::patch('metode-pembayaran/{id}/toggle-status', [MetodePembayaranController::class, 'toggleStatus'])->name('metode-pembayaran.toggle-status');

    Route::resource('hotel', HotelController::class);
    Route::prefix('hotel/{hotelId}/kamar')->name('hotel.kamar.')->group(function () {
        Route::get('/', [HotelController::class, 'kamarIndex'])->name('index');
        Route::post('/', [HotelController::class, 'kamarStore'])->name('store');
        Route::put('/{kamarId}', [HotelController::class, 'kamarUpdate'])->name('update');
        Route::delete('/{kamarId}', [HotelController::class, 'kamarDestroy'])->name('destroy');
    });

    Route::resource('kategori-pengeluaran', KategoriPengeluaranController::class);
    Route::resource('status-keberangkatan', StatusKeberangkatanController::class);
    Route::resource('jenis-transaksi', JenisTransaksiController::class);
    Route::patch('jenis-transaksi/{id}/toggle-status', [JenisTransaksiController::class, 'toggleStatus'])->name('jenis-transaksi.toggle-status');

    Route::resource('kota-asal', KotaAsalController::class);
    Route::resource('maskapai', MaskapaiController::class);
    Route::resource('diskon', DiskonController::class);
    Route::post('diskon/{id}/reset', [DiskonController::class, 'resetDiskon'])->name('diskon.reset');
    Route::get('diskon/{id}/riwayat', [DiskonController::class, 'riwayat'])->name('diskon.riwayat');
    Route::resource('perlengkapan', PerlengkapanController::class);
    Route::resource('paket-hotel', PaketHotelController::class);
    Route::resource('paket-tour', PaketTourController::class);
});

// ============================================
// TRANSAKSIONAL ROUTES
// ============================================
Route::prefix('transaksional')->name('transaksional.')->group(function () {
    // Keluarga
    Route::resource('keluarga', KeluargaController::class);
    Route::get('keluarga/{id}/pembayaran', [KeluargaController::class, 'pembayaran'])->name('keluarga.pembayaran');
    Route::post('keluarga/{id}/bayar', [KeluargaController::class, 'bayar'])->name('keluarga.bayar');

    // Jamaah
    Route::resource('jamaah', JamaahController::class);
    Route::get('jamaah/{id}/pembayaran', [JamaahController::class, 'pembayaran'])->name('jamaah.pembayaran');
    Route::post('jamaah/{id}/bayar', [JamaahController::class, 'bayar'])->name('jamaah.bayar');
    Route::delete('jamaah/bukti/{id}', [JamaahController::class, 'hapusBukti'])->name('jamaah.hapus-bukti');
    Route::delete('jamaah/transaksi/{id}', [JamaahController::class, 'hapusTransaksi'])->name('jamaah.hapus-transaksi');

    // Departure
    Route::resource('departure', DepartureController::class);
    Route::post('departure/{id}/add-jamaah', [DepartureController::class, 'addJamaah'])->name('departure.add-jamaah');
    Route::delete('departure/{departureId}/remove-jamaah/{jamaahId}', [DepartureController::class, 'removeJamaah'])->name('departure.remove-jamaah');
    Route::patch('departure/{id}/update-status', [DepartureController::class, 'updateStatus'])->name('departure.update-status');
    Route::post('departure/{id}/recalculate', [DepartureController::class, 'recalculate'])->name('departure.recalculate');
    Route::post('departure/recalculate-all', [DepartureController::class, 'recalculateAll'])->name('departure.recalculate-all');

    // Route untuk get jamaah by produk (AJAX)
    Route::get('get-jamaah-by-produk/{id_produk}', [DepartureController::class, 'getJamaahByProduk'])
        ->name('get-jamaah-by-produk');

    // ============================================
    // ROUTE UNTUK GET KAMAR BY HOTEL (AJAX)
    // ============================================
    Route::get('get-kamars-by-hotel/{id_hotel}', [DepartureController::class, 'getKamarsByHotel'])
        ->name('get-kamars-by-hotel');

    // Di dalam route group transaksional

    // Update methods (Step by Step)
    Route::put('departure/{id}/update-maskapai', [DepartureController::class, 'updateMaskapai'])->name('departure.update-maskapai');
    Route::put('departure/{id}/update-hotel', [DepartureController::class, 'updateHotel'])->name('departure.update-hotel');
    Route::put('departure/{id}/update-jamaah', [DepartureController::class, 'updateJamaah'])->name('departure.update-jamaah');
    Route::put('departure/{id}/update-catatan', [DepartureController::class, 'updateCatatan'])->name('departure.update-catatan');
    // Di dalam route group transaksional

    // Perlengkapan Departure
    Route::put('departure/{id}/update-perlengkapan', [DepartureController::class, 'updatePerlengkapan'])->name('departure.update-perlengkapan');
    Route::delete('departure/{departureId}/remove-perlengkapan/{departurePerlengkapanId}', [DepartureController::class, 'removePerlengkapan'])->name('departure.remove-perlengkapan');
    Route::patch('departure/{departureId}/toggle-perlengkapan/{departurePerlengkapanId}', [DepartureController::class, 'togglePerlengkapan'])->name('departure.toggle-perlengkapan');
    Route::patch('departure/update-perlengkapan-status-jamaah/{departurePerlengkapanId}/{jamaahId}', [DepartureController::class, 'updatePerlengkapanStatusJamaah'])->name('departure.update-perlengkapan-status-jamaah');
    // Di dalam route group transaksional

    // AJAX Routes
    Route::get('get-jamaah-by-produk/{id_produk}', [DepartureController::class, 'getJamaahByProduk'])->name('get-jamaah-by-produk');
    Route::get('get-kamars-by-hotel-with-selected/{idHotel}/{departureId}', [DepartureController::class, 'getKamarsByHotelWithSelected'])->name('get-kamars-by-hotel-with-selected');

    // Update Methods
    Route::put('departure/{id}/update-maskapai', [DepartureController::class, 'updateMaskapai'])->name('departure.update-maskapai');
    Route::put('departure/{id}/update-hotel', [DepartureController::class, 'updateHotel'])->name('departure.update-hotel');
    Route::put('departure/{id}/update-jamaah', [DepartureController::class, 'updateJamaah'])->name('departure.update-jamaah');
    Route::put('departure/{id}/update-perlengkapan', [DepartureController::class, 'updatePerlengkapan'])->name('departure.update-perlengkapan');
    Route::put('departure/{id}/update-catatan', [DepartureController::class, 'updateCatatan'])->name('departure.update-catatan');

    // Perlengkapan Actions
    Route::delete('departure/{departureId}/remove-perlengkapan/{departurePerlengkapanId}', [DepartureController::class, 'removePerlengkapan'])->name('departure.remove-perlengkapan');
    Route::patch('departure/{departureId}/toggle-perlengkapan/{departurePerlengkapanId}', [DepartureController::class, 'togglePerlengkapan'])->name('departure.toggle-perlengkapan');
    Route::patch('departure/update-perlengkapan-status-jamaah/{departurePerlengkapanId}/{jamaahId}', [DepartureController::class, 'updatePerlengkapanStatusJamaah'])->name('departure.update-perlengkapan-status-jamaah');

    // Jamaah Actions
    Route::post('departure/{id}/add-jamaah', [DepartureController::class, 'addJamaah'])->name('departure.add-jamaah');
    Route::delete('departure/{departureId}/remove-jamaah/{jamaahId}', [DepartureController::class, 'removeJamaah'])->name('departure.remove-jamaah');
    Route::patch('departure/{id}/update-status', [DepartureController::class, 'updateStatus'])->name('departure.update-status');
    Route::post('departure/{id}/recalculate', [DepartureController::class, 'recalculate'])->name('departure.recalculate');
    Route::post('departure/recalculate-all', [DepartureController::class, 'recalculateAll'])->name('departure.recalculate-all');
    // Di dalam route group transaksional
    Route::get('get-perlengkapan-detail/{perlengkapanId}', [DepartureController::class, 'getPerlengkapanDetail'])->name('get-perlengkapan-detail');
    // Jenis Transaksi
    Route::put('departure/{id}/jenis-transaksi', [DepartureController::class, 'updateJenisTransaksi'])
        ->name('departure.update-jenis-transaksi');
    Route::delete(
        'departure/{departureId}/jenis-transaksi/{jenisTransaksiId}',
        [DepartureController::class, 'removeJenisTransaksi']
    )
        ->name('departure.remove-jenis-transaksi');
    Route::patch(
        'departure/{departureId}/jenis-transaksi/{jenisTransaksiId}/harga',
        [DepartureController::class, 'updateJenisTransaksiHarga']
    )
        ->name('departure.update-jenis-transaksi-harga');
    Route::put('departure/{id}/paket-tour-hotel', [DepartureController::class, 'updatePaketTourHotel'])
        ->name('departure.update-paket-tour-hotel');
    Route::get('departure/{id}/paket-tour-hotels', [DepartureController::class, 'getPaketTourHotels'])
        ->name('departure.get-paket-tour-hotels');
    Route::post('departure/{id}/sync-all', [DepartureController::class, 'syncAll'])
        ->name('departure.sync-all');
    Route::post('departure/{id}/sync-jamaahs', [DepartureController::class, 'syncJamaahs'])
        ->name('departure.sync-jamaahs');
});
