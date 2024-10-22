<?php

use App\Http\Controllers\API\Reply\PinnedReplyController;
use App\Http\Controllers\API\Reply\UnpinnedReplyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', App\Http\Controllers\API\Auth\Login::class)->name('auth.login');
Route::post('/register', App\Http\Controllers\API\Auth\Register::class)->name('auth.register');
Route::post('/logout', App\Http\Controllers\API\Auth\Logout::class)->middleware('auth:sanctum')->name('auth.logout');
Route::post('/request-password-reset', App\Http\Controllers\API\Auth\RequestPasswordReset::class)->middleware(['throttle:api/request-password-reset'])->name('auth.request-password-reset');
Route::post('/confirm-password-reset-request/{token?}', App\Http\Controllers\API\Auth\ConfirmPasswordResetRequest::class)->name('auth.confirm-password-reset-request');
Route::post('/request-email-verification', App\Http\Controllers\API\Auth\RequestEmailVerification::class)->middleware(['throttle:api/request-email-verification'])->name('auth.request-email-verification');
Route::post('/confirm-email-verification-request/{token?}', App\Http\Controllers\API\Auth\ConfirmEmailVerificationRequest::class)->name('auth.confirm-email-verification-request');
Route::middleware(['auth:sanctum', 'thought.is_open'])->group(function () {
    Route::apiResource('thoughts', App\Http\Controllers\API\Thought\ThoughtController::class)->only(['store', 'update', 'destroy'])->withoutMiddleware(['thought.is_open']);
    Route::apiResource('thoughts', App\Http\Controllers\API\Thought\ThoughtController::class)->except(['store', 'update', 'destroy'])->withoutMiddleware(['auth:sanctum', 'thought.is_open']);
    Route::scopeBindings()->group(function () {
        Route::patch('/thoughts/{thought}/replies/{reply}/pinned', PinnedReplyController::class)->withoutMiddleware(['thought.is_open']);
        Route::patch('/thoughts/{thought}/replies/{reply}/unpinned', UnpinnedReplyController::class)->withoutMiddleware(['thought.is_open']);
    });
    Route::apiResource('thoughts.replies', App\Http\Controllers\API\Reply\ReplyController::class)->scoped([
        'thought' => 'id',
        'reply' => 'id',
    ])->only(['store', 'update', 'destroy']);
    Route::apiResource('thoughts.replies', App\Http\Controllers\API\Reply\ReplyController::class)->scoped([
        'thought' => 'id',
        'reply' => 'id',
    ])->except(['store', 'update', 'destroy'])->withoutMiddleware(['auth:sanctum', 'thought.is_open']);
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('users', App\Http\Controllers\API\User\UserContoller::class)->only(['index', 'show', 'update', 'destroy']);
});
Route::fallback(function () {
    return response()->json([
        'message' => 'Resource not found.',
    ], 404);
});