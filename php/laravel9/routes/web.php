<?php

use App\Http\Controllers\BayarController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\TransportasiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('pengguna', PenggunaController::class);
Route::resource('transportasi', TransportasiController::class);
Route::resource('bayar', BayarController::class);
Route::resource('tiket', TiketController::class);
