<?php

use App\Http\Controllers\V1\Admin\AdminAuthController;
use App\Http\Controllers\V1\Admin\AdminUserController;
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

    Route::controller(AdminUserController::class)->as('user.')->group(function (): void {
        Route::get('user-listing', 'list')->name('list');
        Route::put('user-edit/{user:uuid}', 'edit')->name('edit');
        Route::delete('user-delete/{user:uuid}', 'delete')->name('delete');
    });
});
