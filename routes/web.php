<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WelcomeController;
use App\Models\User;
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

Route::get('/', [WelcomeController::class, 'index']);

Route::resource('level', LevelController::class);
Route::prefix('level')->controller(LevelController::class)->group(function () {
    Route::post('list', 'list')->name('level.list');
});

Route::resource('user', UsersController::class);
Route::prefix('user')->controller(UsersController::class)->group(function () {
    Route::post('list', 'list')->name('user.list');
});

Route::resource('kategori', KategoriController::class);
Route::prefix('kategori')->controller(KategoriController::class)->group(function () {
    Route::post('list', 'list')->name('kategori.list');
});
