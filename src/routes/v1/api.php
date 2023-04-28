<?php

use App\Http\Controllers\V1\Auth\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth:api', 'isAdmin'],
    'as' => 'admin.',
], function (): void {
    Route::controller(AdminAuthController::class)->group(function (): void {
        Route::post('create', 'create')->name('create')->withoutMiddleware(['auth:api', 'isAdmin']);
        Route::post('login', 'login')->name('login')->withoutMiddleware(['auth:api', 'isAdmin']);
        Route::get('logout', 'logout')->name('logout');
    });
});
