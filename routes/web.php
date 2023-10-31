<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('clients', [\App\Http\Controllers\system\ClientController::class, 'index'])->name('clients');
Route::post('store-client', [\App\Http\Controllers\system\ClientController::class, 'store'])->name('client.store');
Route::post('update-client', [\App\Http\Controllers\system\ClientController::class, 'update'])->name('client.update');
Route::get('show-client', [\App\Http\Controllers\system\ClientController::class, 'show'])->name('client.get');
Route::delete('delete-client', [\App\Http\Controllers\system\ClientController::class, 'destroy'])->name('client.destroy');


Auth::routes();
