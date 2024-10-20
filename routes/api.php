<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/login', App\Http\Controllers\API\Auth\Login::class)->name('auth.login');
Route::post('/register', App\Http\Controllers\API\Auth\Register::class)->name('auth.register');
Route::post('/logout', App\Http\Controllers\API\Auth\Logout::class)->middleware('auth:sanctum')->name('auth.logout');
Route::post('/request-password-reset', App\Http\Controllers\API\Auth\RequestPasswordReset::class)->middleware(['throttle:api/request-password-reset'])->name('auth.request-password-reset');
Route::post('/confirm-password-reset-request/{token?}', App\Http\Controllers\API\Auth\ConfirmPasswordResetRequest::class)->name('auth.confirm-password-reset-request');
Route::post('/request-email-verification', App\Http\Controllers\API\Auth\RequestEmailVerification::class)->middleware(['throttle:api/request-email-verification'])->name('auth.request-email-verification');
Route::post('/confirm-email-verification-request/{token?}', App\Http\Controllers\API\Auth\ConfirmEmailVerificationRequest::class)->name('auth.confirm-email-verification-request');
Route::apiResource('/thoughts', App\Http\Controllers\API\Thought\ThoughtController::class);
Route::fallback(function () {
    return response()->json([
        'message' => 'Resource not found.',
    ], 404);
});