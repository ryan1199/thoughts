<?php

namespace App\Http\Middleware;

use App\Models\Thought;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureThoughtIsOpen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->route('thought') != null) {
            if ($request->route('thought')->open == 'Open') {
                return $next($request);
            } else {
                return response()->json([
                   'message' => 'Thought is closed.'
                ], 403);
            }
        } else {
            return response()->json([
                'message' => 'Thought not found.'
            ], 404);
        }
    }
}
