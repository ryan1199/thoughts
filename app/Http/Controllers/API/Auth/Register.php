<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Register extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {
        $validated = $request->validated();
        $result = false;
        $user = DB::transaction(function () use (&$result, $validated) {
            $user = User::create([
                'name' => $validated['name'],
                'slug' => User::generateSlug(),
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            $result = true;

            return $user;
        });
        if (! $result) {
            return response()->json([
                'message' => 'Failed to register user',
            ], 500);
        } else {
            return (new UserResource($user))->response()->setStatusCode(201);
        }
    }
}
