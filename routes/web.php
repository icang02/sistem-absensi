<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KelolaAbsenController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ScanController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/seed', function () {
    return Artisan::call('migrate:fresh --seed');
});

Route::get('/login', [AuthController::class, 'index'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->middleware('guest');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/', [ScanController::class, 'index'])->middleware('auth');
Route::get('/riwayat-absen', [ScanController::class, 'riwayatAbsen'])->middleware('auth')->can('user');

Route::post('/validasi', [ScanController::class, 'validasi'])->middleware('auth')->can('user');

Route::get('/scan', function () {
    return view('scan-absen');
});

Route::get('/profil', [ProfilController::class, 'index'])->middleware('auth');
Route::put('/profil', [ProfilController::class, 'update'])->middleware('auth');

// admin
Route::post('/data-absen', [KelolaAbsenController::class, 'store'])->middleware('auth')->can('admin');
Route::put('/data-absen', [KelolaAbsenController::class, 'update'])->middleware('auth')->can('admin');
Route::delete('/data-absen/{userId}', [KelolaAbsenController::class, 'delete'])->middleware('auth')->can('admin');

Route::put('/waktu-absen', [KelolaAbsenController::class, 'updateWaktuAbsen'])->middleware('auth')->can('admin');
