<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Admin\FilterController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Роуты пользователя
|
*/

Route::get('/', function () {
    return view('front.home.home');
});

Route::group(
    ['middleware' => 'auth'], function () {

    Route::controller(FilterController::class)->group(function () {
        Route::post('/save-filter', 'saveFilter')->name('admin.saveFilter')->withoutMiddleware('VerifyCsrf');
        Route::get('/clear-filter', 'clearFilter')->name('admin.clearFilter');
    });
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/get-code', 'getCode')->name('guest.getCode')->withoutMiddleware('VerifyCsrf');
    Route::post('/check-code', 'checkCode')->name('guest.checkCode')->withoutMiddleware('VerifyCsrf');
});
