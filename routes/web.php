<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\TagihanAirController;

Route::get('/', [AuthController::class, 'showlogin'])->name('login');
Route::get('/login', [AuthController::class, 'showlogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

//Route Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'role:Pengurus,Petugas Lapangan,Pelanggan,Mitra Pembayaran']);

//Route Pengurus PAMSIMAS
Route::middleware(['role:Pengurus'])->group(function(){
    Route::resource('/pengguna', App\Http\Controllers\PenggunaController::class);
    Route::resource('/keuangan/akun', App\Http\Controllers\AccountController::class);
    Route::resource('/keuangan/jurnal', App\Http\Controllers\JurnalController::class);
    Route::resource('/keuangan/laporan', App\Http\Controllers\LaporanController::class);
});

Route::get('/cetak-rekening/{id}', [TagihanAirController::class, 'cetakPDF'])->name('cetak.rekening')->middleware(['auth', 'role:Pengurus,Pelanggan,Mitra Pembayaran']);

//Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')
    ->middleware(['auth', 'role:Pengurus,Petugas Lapangan,Pelanggan,Mitra Pembayaran']);

//Route Petugas Lapangan, pelanggan dan Pengurus PAMSIMAS
Route::resource('/penggunaan_air', App\Http\Controllers\PenggunaanAirController::class)
    ->middleware(['auth', 'role:Pengurus,Petugas Lapangan,Pelanggan']);

//Route tagihan air
Route::get('/tagihan_air', [TagihanAirController::class, 'index'])->name('tagihan_air.index')
    ->middleware(['auth', 'role:Pengurus,Pelanggan,Mitra Pembayaran']);

//Route daftar pelanggan
Route::get('/pelanggan', [PenggunaController::class, 'list_pelanggan'])->name('list_pelanggan')->middleware(['auth', 'role:Pengurus,Petugas Lapangan,Mitra Pembayaran']);

Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('index.pembayaran')->middleware(['auth', 'role:Pelanggan,Mitra Pembayaran,Pengurus']);

//Data API Meteran awal pelanggan
Route::get('api/meter-terakhir/{penggunaID}', function ($penggunaId){
    $lastMeter = \App\Models\PenggunaanAir::where('penggunas_id', $penggunaId)
        ->orderByDesc('tanggal_catat')
        ->orderByDesc('bulan')
        ->orderByDesc('tahun')
        ->value('meter_baca_akhir');
    return response()->json([
        'meter_baca_akhir' => $lastMeter ?? 0
    ]);
});

//API scan barcode
// Route::get('/api/pelanggan/{uuid}', function($uuid){
//     $user = \App\Models\Pengguna::find($uuid);
//     if($user){
//         return response()->json([
//             'id' => $user->id,
//             'nama' => $user->nama
//         ]);
//     } else {
//         return response()->json([]);
//     }
// });
