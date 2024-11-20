<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OAuthController;

Route::middleware(['web'])->prefix('auth')->group(function () {
    Route::get('/redirect',[OAuthController::class, 'handleSsoRedirect']);
    Route::get('/callback', [OAuthController::class, 'handleProviderCallback']);
});

Route::prefix('v1')->group(function () {
    Route::post('/register-user', [OAuthController::class, 'postRegisterUser']);
    Route::post('/login-user', [OAuthController::class, 'postHandleUserLogin']);
});


