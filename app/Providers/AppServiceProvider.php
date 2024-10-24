<?php

namespace App\Providers;

use App\Models\Notification;
use App\Models\Reply;
use App\Models\Thought;
use App\Models\User;
use App\Policies\NotificationPolicy;
use App\Policies\ReplyPolicy;
use App\Policies\ThoughtPolicy;
use App\Policies\UserPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api/request-password-reset', function (Request $request) {
            return Limit::perDay(5)->by($request->ip())->response(function (Request $request) {
                return response()->json([
                    'message' => 'Too many password reset requests. Please try again after a day.',
                ], 429);
            });
        });
        RateLimiter::for('api/request-email-verification', function (Request $request) {
            return Limit::perDay(5)->by($request->ip())->response(function (Request $request) {
                return response()->json([
                    'message' => 'Too many email verification requests. Please try again after a day.',
                ], 429);
            });
        });

        Gate::policy(Thought::class, ThoughtPolicy::class);
        Gate::policy(Reply::class, ReplyPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policies(Notification::class, NotificationPolicy::class);
    }
}
