<?php

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

        Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('index');
        Route::get('/settings/{id}', [App\Http\Controllers\SettingController::class, 'show'])->name('show');
        Route::put('/settings/{id}/certificate', [App\Http\Controllers\SettingController::class, 'storeCertSettings'])->name('storeCertSettings');
    });
});


Route::prefix('verifier')->group(function () {
    Route::name('verifiers.facilities.')->group(function () {
        Route::group(['middleware' => ['auth']], function () {




            Route::get('/email/preview', [App\Http\Controllers\Verifier\FacilityController::class, 'emailPrev']);
            Route::get('/facilities', [App\Http\Controllers\Verifier\FacilityController::class, 'index'])->name('index');
            Route::post('/facilities', [App\Http\Controllers\Verifier\FacilityController::class, 'search'])->name('search');
            Route::get('/facilities/{id}', [App\Http\Controllers\Verifier\FacilityController::class, 'show'])->name('show');
            Route::group(['middleware' => ['role:encoder']], function () {
                // Route::put('/facilities/{id}/certificate/{cert_id}/updatePrepared', [App\Http\Controllers\Verifier\FacilityController::class, 'updatePrepared'])->name('updatePrepared');
                // Route::post('/facilities/{id}/certificate/create', [App\Http\Controllers\Verifier\FacilityController::class, 'create_certificate'])->name('create_certificate');
                // Route::put('/certificate/{id}/edit', [App\Http\Controllers\Verifier\FacilityController::class, 'edit_certificate'])->name('edit_certificate');
            });
            Route::group(['middleware' => ['role:verifier']], function () {
                Route::put('/facilities/{id}/certificate/{cert_id}/updateVerified', [App\Http\Controllers\Verifier\FacilityController::class, 'updateVerified'])->name('updateVerified');
                Route::get('/proficiency-testing', [App\Http\Controllers\ProficiencyTestingController::class, 'index']);
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

// PT

Route::group(['middleware' => ['role:admin|verifier|head']], function () {
    Route::resource('/proficiency-testing', App\Http\Controllers\ProficiencyTestingController::class);
    Route::name('proficiency-testing.')->group(function () {
        Route::get('/proficiency-testing/{id}/applicants', [App\Http\Controllers\ProficiencyTestingController::class, 'applicants'])->name('applicants');
        Route::put('/proficiency-testing/{id}/applicants/{application_id}', [App\Http\Controllers\ProficiencyTestingController::class, 'sendSpecimen'])->name('applicants.sendSpecimen');

        // proceed specimen
        Route::put('/proficiency-testing/{id}/applicants/{application_id}/proceed_specimen', [App\Http\Controllers\ProficiencyTestingController::class, 'proceed_specimen'])->name('applicants.proceed_specimen');

        Route::put('/proficiency-testing/{id}/applicants/{application_id}/verifyPayment', [App\Http\Controllers\ProficiencyTestingController::class, 'verifyPayment'])->name('applicants.verifyPayment');
        Route::put('/proficiency-testing/{id}/applicants/{application_id}/rejectPayment', [App\Http\Controllers\ProficiencyTestingController::class, 'rejectPayment'])->name('applicants.rejectPayment');
        Route::put('/proficiency-testing/{id}/applicants/{application_id}/saveScore', [App\Http\Controllers\ProficiencyTestingController::class, 'saveScore'])->name('saveScore');
        Route::get('/proficiency-testing/{id}/applicants/{application_id}/show', [App\Http\Controllers\ProficiencyTestingController::class, 'showApplication'])->name('applicants.showApplication');
    });
    Route::resource('/announcements', App\Http\Controllers\Admin\AnnouncementController::class);
    Route::put('/announcements/{id}/changeStatus',  [App\Http\Controllers\Admin\AnnouncementController::class, 'changeStatus'])->name('announcements.changeStatus');
});
Route::name('ptApplication.')->group(function () {

    // Certificate Admin Side
    // Create cert
    Route::post('/proficiency-testing/{id}/applicants/{application_id}/certificate/create', [App\Http\Controllers\PtApplicationController::class, 'create_certificate'])->name('create_certificate')->middleware(['role:admin']);
    Route::put('/proficiency-testing/{id}/applicants/{application_id}/certificate/verify', [App\Http\Controllers\PtApplicationController::class, 'verify_certificate'])->name('verify_certificate')->middleware(['role:verifier']);
    Route::put('/proficiency-testing/{id}/applicants/{application_id}/certificate/approve', [App\Http\Controllers\PtApplicationController::class, 'approve_certificate'])->name('approve_certificate')->middleware(['role:head']);
    Route::put('/proficiency-testing/{id}/applicants/{application_id}/certificate/{cert_id}', [App\Http\Controllers\PtApplicationController::class, 'update_certificate'])->name('update_certificate')->middleware(['role:admin']);

    // delete pt application
    Route::delete('/proficiency-testing/{id}/applicants/{application_id}/delete', [App\Http\Controllers\PtApplicationController::class, 'delete_application'])->name('delete_application')->middleware(['role:admin']);
});

Route::group(['middleware' => ['role:Facility']], function () {
    Route::prefix('facility')->group(function () {
        Route::name('proficiency-testing.facility.')->group(function () {
            Route::get('/proficiency-testing', [App\Http\Controllers\Facility\ProficiencyTestingController::class, 'index'])->name('index');
            Route::get('/proficiency-testing/{id}', [App\Http\Controllers\Facility\ProficiencyTestingController::class, 'apply'])->name('apply');
            Route::post('/proficiency-testing/{id}', [App\Http\Controllers\Facility\ProficiencyTestingController::class, 'saveApplication'])->name('saveApplication');
            Route::put('/proficiency-testing/{id}', [App\Http\Controllers\Facility\ProficiencyTestingController::class, 'saveReceipt'])->name('saveReceipt');
            Route::put('/proficiency-testing/{id}/receiveSpecimen', [App\Http\Controllers\Facility\ProficiencyTestingController::class, 'receiveSpecimen'])->name('receiveSpecimen');
            Route::put('/proficiency-testing/{id}/saveResult', [App\Http\Controllers\Facility\ProficiencyTestingController::class, 'saveResult'])->name('saveResult');
        });
        Route::name('facility.')->group(function () {
            Route::get('/profile', [App\Http\Controllers\Facility\FacilityController::class, 'profile'])->name('profile');
            Route::put('/profile/update', [App\Http\Controllers\Facility\FacilityController::class, 'profileUpdate'])->name('profile.update');
            Route::put('/profile/update-password', [App\Http\Controllers\Facility\FacilityController::class, 'profileUpdatePassword'])->name('profile.updatePassword');
        });
    });
});


// Director
Route::resource('/directors', App\Http\Controllers\Admin\DirectorController::class);
