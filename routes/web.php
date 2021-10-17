<?php

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

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('/facilities', App\Http\Controllers\FacilityController::class);
Route::post('/facilities/createOrUpdate', [App\Http\Controllers\FacilityController::class, 'createOrUpdate'])->name('facilities.createOrUpdate');
Route::resource('/users', App\Http\Controllers\UserController::class);


Route::prefix('verifier')->group(function(){
    Route::name('verifiers.facilities.')->group(function(){
        Route::get('/facilities', [App\Http\Controllers\Verifier\FacilityController::class, 'index'])->name('index');
        Route::get('/facilities/{id}', [App\Http\Controllers\Verifier\FacilityController::class, 'show'])->name('show');
        Route::put('/facilities/{id}/updateVerified', [App\Http\Controllers\Verifier\FacilityController::class, 'updateVerified'])->name('updateVerified');
        Route::put('/facilities/{id}/updateEndorse', [App\Http\Controllers\Verifier\FacilityController::class, 'updateEndorse'])->name('updateEndorse');
        Route::put('/facilities/{id}/updateApproved', [App\Http\Controllers\Verifier\FacilityController::class, 'updateApproved'])->name('updateApproved');

        // certificate
        Route::get('/facilities/{id}/certificate', [App\Http\Controllers\Verifier\FacilityController::class, 'certificate'])->name('certificate');
        Route::post('/facilities/{id}/certificate/create', [App\Http\Controllers\Verifier\FacilityController::class, 'create_certificate'])->name('create_certificate');
    });
});