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

});


