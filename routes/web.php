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

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Master Data Routes
Route::prefix('master')->name('master.')->group(function () {
    Route::resource('produk', ProdukPaketController::class);
    Route::patch('produk/{id}/toggle-status', [ProdukPaketController::class, 'toggleStatus'])->name('produk.toggle-status');
    Route::patch('produk/{id}/update-status-keberangkatan', [ProdukPaketController::class, 'updateStatusKeberangkatan'])->name('produk.update-status-keberangkatan');

    Route::resource('metode-pembayaran', MetodePembayaranController::class);
    Route::patch('metode-pembayaran/{id}/toggle-status', [MetodePembayaranController::class, 'toggleStatus'])->name('metode-pembayaran.toggle-status');

    Route::resource('hotel', HotelController::class);
      // Routes untuk manage kamar
    Route::prefix('hotel/{hotelId}/kamar')->name('hotel.kamar.')->group(function() {
        Route::get('/', [HotelController::class, 'kamarIndex'])->name('index');
        Route::post('/', [HotelController::class, 'kamarStore'])->name('store');
        Route::put('/{kamarId}', [HotelController::class, 'kamarUpdate'])->name('update');
        Route::delete('/{kamarId}', [HotelController::class, 'kamarDestroy'])->name('destroy');
    });
    Route::resource('kategori-pengeluaran', KategoriPengeluaranController::class);
    Route::resource('status-keberangkatan', StatusKeberangkatanController::class);
    Route::resource('jenis-transaksi', JenisTransaksiController::class);
    Route::resource('kota-asal', KotaAsalController::class);
    Route::resource('maskapai', MaskapaiController::class);
    Route::resource('diskon', DiskonController::class);
    Route::resource('perlengkapan', PerlengkapanController::class);
    Route::resource('paket-hotel', PaketHotelController::class);
    Route::resource('paket-tour', PaketTourController::class);
});

// Transaksional Routes
Route::prefix('transaksional')->name('transaksional.')->group(function () {
    Route::resource('keluarga', KeluargaController::class);
    Route::get('keluarga/{id}/pembayaran', [KeluargaController::class, 'pembayaran'])->name('keluarga.pembayaran');
    Route::post('keluarga/{id}/bayar', [KeluargaController::class, 'bayar'])->name('keluarga.bayar');

    Route::resource('jamaah', JamaahController::class);
    Route::get('jamaah/{id}/pembayaran', [JamaahController::class, 'pembayaran'])->name('jamaah.pembayaran');
    Route::post('jamaah/{id}/bayar', [JamaahController::class, 'bayar'])->name('jamaah.bayar');
    Route::delete('jamaah/bukti/{id}', [JamaahController::class, 'hapusBukti'])->name('jamaah.hapus-bukti');
    Route::delete('jamaah/transaksi/{id}', [JamaahController::class, 'hapusTransaksi'])->name('jamaah.hapus-transaksi');
});
