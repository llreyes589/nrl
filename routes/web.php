<?php

use App\Http\Controllers\CertificateTemplateController;
use Illuminate\Support\Facades\Route;

// Route::get('testmail', function(){
//     $to_name = 'lester';
//     $to_email = 'clarenista@gmail.com';
//     $data = array('name'=>'nrl', 'body' => 'A test mail');
//     Mail::send('emails.mail', $data, function($message) use ($to_name, $to_email) {
//         $message->to($to_email, $to_name)
//         ->subject('Laravel Test Mail');
//     $message->from('mail.nrldoh@gmail.com','Test Mail');
//     });
// });

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/certificate/{key}/verify/', [App\Http\Controllers\Guest\CertificateController::class, 'verify'])->name('verifyCertificate');
Route::put('/certificate/{key}/verify/', [App\Http\Controllers\Guest\CertificateController::class, 'updateVerifyByFacility'])->name('verifyMyCertificate');
Route::group(['middleware' => ['role_or_permission:admin|create facility', 'auth']], function () {
    //
    Route::resource('/facilities', App\Http\Controllers\FacilityController::class);
    Route::post('/facilities/createOrUpdate', [App\Http\Controllers\FacilityController::class, 'createOrUpdate'])->name('facilities.createOrUpdate');
});
Route::group(['middleware' => ['role_or_permission:admin|create user', 'auth']], function () {
    Route::resource('/users', App\Http\Controllers\UserController::class);
});

Route::name('settings.')->group(function () {
    Route::group(['middleware' => ['auth']], function () {

        Route::resource('certificate-templates', CertificateTemplateController::class);

        Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('index');
        Route::put('/settings/certificate', [App\Http\Controllers\SettingController::class, 'storeCertSettings'])->name('storeCertSettings');
    });
});
Route::group(['middleware' => ['auth']], function () {

    Route::resource('certificate-templates', CertificateTemplateController::class);
});


Route::prefix('verifier')->group(function () {
    Route::name('verifiers.facilities.')->group(function () {
        Route::group(['middleware' => ['auth']], function () {
            Route::get('/email/preview', [App\Http\Controllers\Verifier\FacilityController::class, 'emailPrev']);
            Route::get('/facilities', [App\Http\Controllers\Verifier\FacilityController::class, 'index'])->name('index');
            Route::post('/facilities', [App\Http\Controllers\Verifier\FacilityController::class, 'search'])->name('search');
            Route::get('/facilities/{id}', [App\Http\Controllers\Verifier\FacilityController::class, 'show'])->name('show');
            Route::group(['middleware' => ['role:encoder']], function () {
                Route::put('/facilities/{id}/certificate/{cert_id}/updatePrepared', [App\Http\Controllers\Verifier\FacilityController::class, 'updatePrepared'])->name('updatePrepared');
                Route::post('/facilities/{id}/certificate/create', [App\Http\Controllers\Verifier\FacilityController::class, 'create_certificate'])->name('create_certificate');
                Route::put('/certificate/{id}/edit', [App\Http\Controllers\Verifier\FacilityController::class, 'edit_certificate'])->name('edit_certificate');
            });
            Route::group(['middleware' => ['role:verifier']], function () {
                Route::put('/facilities/{id}/certificate/{cert_id}/updateVerified', [App\Http\Controllers\Verifier\FacilityController::class, 'updateVerified'])->name('updateVerified');
            });

            Route::group(['middleware' => ['role:head']], function () {
                Route::resource('/users', App\Http\Controllers\UserController::class);
                Route::put('/facilities/{id}/certificate/{cert_id}/updateApproved', [App\Http\Controllers\Verifier\FacilityController::class, 'updateApproved'])->name('updateApproved');
                Route::post('/facilities/{id}/certificate/{cert_id}/emailFacility', [App\Http\Controllers\Verifier\FacilityController::class, 'emailFacility'])->name('emailFacility');
            });
        });
    });
});
// certificate
Route::get('/certificate/{key}', [App\Http\Controllers\Verifier\FacilityController::class, 'certificate'])->name('certificate');
