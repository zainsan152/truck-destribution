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

Route::get('logout', function () {
    // Retrieve the currently authenticated user
    $user = auth()->user();

    // Check if the user is logged in
    if ($user) {
        // Reset the remember_token in the database
        $user->remember_token = null;
        $user->save();
    }

    // Perform the actual logout
    auth()->logout();

    // Flush the session
    Session()->flush();

    // Redirect to the root route
    return Redirect::to('/');
})->name('logout');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('clients', [\App\Http\Controllers\system\ClientController::class, 'index'])->name('clients');
Route::post('store-client', [\App\Http\Controllers\system\ClientController::class, 'store'])->name('client.store');
Route::post('update-client', [\App\Http\Controllers\system\ClientController::class, 'update'])->name('client.update');
Route::get('show-client', [\App\Http\Controllers\system\ClientController::class, 'show'])->name('client.get');
Route::delete('delete-client', [\App\Http\Controllers\system\ClientController::class, 'destroy'])->name('client.destroy');

Route::get('ots', [\App\Http\Controllers\system\OtController::class, 'index'])->name('ots');
//Route::post('ots', [\App\Http\Controllers\system\DistributionController::class, 'planify_distribution'])->name('distribution.planify');

Route::get('distributions', [\App\Http\Controllers\system\DistributionController::class, 'index'])->name('distributions');
Route::get('distributionsplanifiees', [\App\Http\Controllers\system\DistributionController::class, 'distributionsplanifiees'])->name('distributionsplanifiees');
Route::get('distribution-details/{id}', [\App\Http\Controllers\system\DistributionController::class, 'details'])->name('distributions.details');
Route::post('import-distributions', [\App\Http\Controllers\system\DistributionController::class, 'import'])->name('distributions.import');
Route::get('planning', [\App\Http\Controllers\system\DistributionController::class, 'planning'])->name('planning');
Route::post('planify-distribution', [\App\Http\Controllers\system\DistributionController::class, 'planify_distribution'])->name('distribution.planify');
Route::delete('delete-distribution', [\App\Http\Controllers\system\DistributionController::class, 'delete_distribution'])->name('distribution.delete');
Route::get('edit-distribution-lines/{id}', [\App\Http\Controllers\system\DistributionController::class, 'edit_distribution_lines'])->name('lines.edit');
Route::delete('delete-distribution-lines', [\App\Http\Controllers\system\DistributionController::class, 'delete_distribution_lines'])->name('lines.delete');
Route::get('get-distribution-line', [\App\Http\Controllers\system\DistributionController::class, 'get_distribution_line'])->name('line.get');
Route::post('update-distribution-line', [\App\Http\Controllers\system\DistributionController::class, 'update_distribution_line'])->name('line.update');


Route::get('arrivals', [\App\Http\Controllers\system\ArrivalController::class, 'index'])->name('arrivals');
Route::post('import-arrivals', [\App\Http\Controllers\system\ArrivalController::class, 'import'])->name('arrivals.import');
Route::post('store-arrivals', [\App\Http\Controllers\system\ArrivalController::class, 'store_arrival'])->name('arrivals.stores');
Route::post('update-arrivals', [\App\Http\Controllers\system\ArrivalController::class, 'update_arrival'])->name('arrivals.update');
Route::get('arrival-details/{arrival_id}', [\App\Http\Controllers\system\ArrivalController::class, 'details'])->name('arrivals.details');
Route::delete('delete-arrival', [\App\Http\Controllers\system\ArrivalController::class, 'delete_arrival'])->name('arrival.delete');
Route::post('valider-bae', [\App\Http\Controllers\system\ArrivalController::class, 'validerBae'])->name('validerBae');

Route::get('arrivals-levee', [\App\Http\Controllers\system\ArrivalController::class, 'main_levee'])->name('arrivals.main_levee');
Route::post('valider-taxation', [\App\Http\Controllers\system\ArrivalController::class, 'validerTaxation'])->name('validerTaxation');

Route::get('arrivals-taxation', [\App\Http\Controllers\system\ArrivalController::class, 'taxation'])->name('arrivals.taxation');
Route::post('valider-ot', [\App\Http\Controllers\system\ArrivalController::class, 'validerOT'])->name('validerOT');

Route::get('arrivals-ot', [\App\Http\Controllers\system\ArrivalController::class, 'ot'])->name('arrivals.ot');

Auth::routes();
