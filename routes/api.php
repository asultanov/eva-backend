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
});
