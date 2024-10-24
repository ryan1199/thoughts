<?php

use App\Http\Middleware\EnsureThoughtIsOpen;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'thought.is_open' => EnsureThoughtIsOpen::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/thoughts/*/replies/*')) {
                return response()->json([
                    'message' => 'Replies record not found.'
                ], 404);
            }
            if ($request->is('api/thoughts/*')) {
                return response()->json([
                    'message' => 'Thought record not found.'
                ], 404);
            }
            if ($request->is('api/users/*/notifications/*')) {
                return response()->json([
                    'message' => 'Notification record not found.'
                ], 404);
            }
            if ($request->is('api/users/*')) {
                return response()->json([
                    'message' => 'User record not found.'
                ], 404);
            }
        });
    })->create();
