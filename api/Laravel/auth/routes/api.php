<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OAuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:web');


Route::prefix('v1')->group(function () {

    Route::post('/register-user', [OAuthController::class, 'postRegisterUser']);
    Route::post('/login-user', [OAuthController::class, 'postHandleUserLogin']);

});
