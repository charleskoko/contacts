<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {
    Route::post('/register', [AuthenticationController::class, 'register'])->name('user_register');
    Route::post('/login', [AuthenticationController::class, 'login'])->name('user_login');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [AuthenticationController::class, 'logout'])->name('user_logout');

        Route::group(['prefix' => 'contact'], function () {
            Route::get('/', [ContactController::class, 'index'])->name('contact_index');
            Route::post('/', [ContactController::class, 'store'])->name('contact_store');
            Route::get('/{contact}', [ContactController::class, 'show'])->middleware('contactOwner')->name('contact_show');
            Route::patch('/{contact}', [ContactController::class, 'update'])->middleware('contactOwner')->name('contact_update');
            Route::delete('/{contact}', [ContactController::class, 'destroy'])->middleware('contactOwner')->name('contact_destroy');
        });
    });

});
