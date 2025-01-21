<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

Route::group(
    ['prefix' => 'api/v1'], function () {

    Route::post('/register', [V1\AuthController::class, 'register']);
    Route::post('/login', [V1\AuthController::class, 'login']);

});


Route::group(
    [
        'prefix' => 'api/v1',
        'middleware' => ['auth:sanctum']
    ], function () {
    Route::controller(V1\AuthController::class)->group(function () {
        Route::get('/get-current-user', 'getCurrentUser');
        Route::get('/logout', 'logout');
    });

    Route::controller(V1\UserProfileController::class)->group(function () {
        Route::post('/update-user-profile-information', 'update');
    });
});

//Роуты не требующие авторизации
Route::group(['prefix' => 'api/v1'], function () {
    Route::controller(V1\DataController::class)->group(function () {
        Route::get('/get-diagnoses-and-therapies', 'getDiagnosesAndTherapies');
        Route::get('/get-diagnoses', 'getDiagnoses');
        Route::get('/get-therapies', 'getTherapies');
    });
});
