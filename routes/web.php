<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
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
    if (Auth::check()) {
        return redirect('/home');
    }
    return view('auth.login');
});

Route::get('logout', function ()
{
    auth()->logout();
    Session()->flush();

    return Redirect::to('/');
})->name('logout');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('clients', [\App\Http\Controllers\system\ClientController::class, 'index'])->name('clients');
Route::post('store-client', [\App\Http\Controllers\system\ClientController::class, 'store'])->name('client.store');
Route::post('update-client', [\App\Http\Controllers\system\ClientController::class, 'update'])->name('client.update');
Route::get('show-client', [\App\Http\Controllers\system\ClientController::class, 'show'])->name('client.get');
Route::delete('delete-client', [\App\Http\Controllers\system\ClientController::class, 'destroy'])->name('client.destroy');

Route::get('distributions', [\App\Http\Controllers\system\DistributionController::class, 'index'])->name('distributions');
Route::get('distribution-details/{id}', [\App\Http\Controllers\system\DistributionController::class, 'details'])->name('distributions.details');
Route::post('import-distributions', [\App\Http\Controllers\system\DistributionController::class, 'import'])->name('distributions.import');
Route::get('planning', [\App\Http\Controllers\system\DistributionController::class, 'planning'])->name('planning');
Route::post('planify-distribution', [\App\Http\Controllers\system\DistributionController::class, 'planify_distribution'])->name('distribution.planify');
Route::delete('delete-distribution', [\App\Http\Controllers\system\DistributionController::class, 'delete_distribution'])->name('distribution.delete');
Route::get('edit-distribution-lines/{id}', [\App\Http\Controllers\system\DistributionController::class, 'edit_distribution_lines'])->name('lines.edit');
Route::delete('delete-distribution-lines', [\App\Http\Controllers\system\DistributionController::class, 'delete_distribution_lines'])->name('lines.delete');
Route::get('get-distribution-line', [\App\Http\Controllers\system\DistributionController::class, 'get_distribution_line'])->name('line.get');
Route::post('update-distribution-line', [\App\Http\Controllers\system\DistributionController::class, 'update_distribution_line'])->name('line.update');


Auth::routes();
