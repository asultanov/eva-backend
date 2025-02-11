<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;


Route::group(
    [
        'prefix' => 'admin',
        'middleware' => ['auth']
    ], function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');


    //Пользоватли
    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'users')->name('settings-staff');

        Route::get('/all-users', 'allUsers')->name('admin.allUsers')->middleware('content_type');
        Route::post('/fast-edit', 'fastEdit')->name('admin.fastEdit')->withoutMiddleware('VerifyCsrf');
        Route::post('/delete-user', 'deleteUser')->name('admin.deleteUser');
        Route::get('/user/{user?}', 'specAuth')->name('admin.specAuth');
    });

    Route::controller(TherapyController::class)->group(function () {
        Route::get('/therapies', 'index')->name('guides-therapies');
        Route::get('/get-therapies', 'getTherapies')->name('guides-getTherapies');
        Route::post('/edit-therapies', 'editTherapy')->name('guides-editTherapy')->withoutMiddleware('VerifyCsrf');
        Route::post('/remove-therapies', 'removeTherapy')->name('guides-removeTherapy')->withoutMiddleware('VerifyCsrf');
    });

    Route::controller(DiagnosesController::class)->group(function () {
        Route::get('/diagnoses', 'index')->name('guides-diagnoses');
        Route::get('/get-diagnoses', 'getDiagnoses')->name('guides-getDiagnoses');
        Route::post('/edit-diagnoses', 'editDiagnoses')->name('guides-editDiagnoses')->withoutMiddleware('VerifyCsrf');
        Route::post('/remove-diagnoses', 'removeDiagnoses')->name('guides-removeDiagnoses')->withoutMiddleware('VerifyCsrf');
    });


    Route::controller(MedicamentController::class)->group(callback: function () {
        Route::get('/medicaments', 'index')->name('guides-medicaments');
        Route::get('/get-medicaments', 'getMedicaments')->name('guides-getMedicaments');
        Route::post('/edit-medicaments', 'editMedicament')->name('guides-editMedicament')->withoutMiddleware('VerifyCsrf');
        Route::post('/remove-medicaments', 'removeMedicament')->name('guides-removeMedicament')->withoutMiddleware('VerifyCsrf');
    });


});


