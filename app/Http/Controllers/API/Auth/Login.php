<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class Login extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();
        if ($user == null) {
            return response()->json([
                'message' => 'The provided email address is not exists in our records.',
            ], 404);
        } else {
            if ($user->email_verified_at == null) {
                return response()->json([
                    'message' => 'Please verify your email address before logging in.',
                ], 403);
            } else {
                if (Auth::attempt($validated)) {
                    $request->user()->tokens()->delete();
                    return response()->json([
                        'message' => 'Login successfully.',
                        'user' => new UserResource($request->user()),
                        'access_token' => $request->user()->createToken('authToken')->plainTextToken,
                        'token_type' => 'Bearer'
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'The provided credentials do not match our records',
                    ], 401);
                }
            }
        }
    }
}
