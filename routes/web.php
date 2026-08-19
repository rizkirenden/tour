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

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Master Data Routes
Route::prefix('master')->name('master.')->group(function () {
    Route::resource('produk', ProdukPaketController::class);
    Route::patch('produk/{id}/toggle-status', [ProdukPaketController::class, 'toggleStatus'])->name('produk.toggle-status');

    Route::resource('metode-pembayaran', MetodePembayaranController::class);
    Route::patch('metode-pembayaran/{id}/toggle-status', [MetodePembayaranController::class, 'toggleStatus'])->name('metode-pembayaran.toggle-status');

    Route::resource('hotel', HotelController::class);
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
