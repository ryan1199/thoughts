<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\PasswordResetRequest;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Notifications\RequestForPasswordReset;
use Illuminate\Support\Str;

class RequestPasswordReset extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PasswordResetRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();
        if ($user == null) {
            return response()->json([
                'message' => 'The provided email address is not exists in our records.',
            ], 404);
        } else {
            $password_reset_token = PasswordResetToken::where('email', $user->email)->first();
            $token = Str::random(100);
            if ($password_reset_token == null) {
                $password_reset_token = PasswordResetToken::create([
                    'email' => $user->email,
                    'token' => $token,
                ]);
            } else {
                if ($password_reset_token->created_at->isToday()) {
                    $password_reset_token->token = $token;
                    $password_reset_token->save();
                }
            }
            $url = route('auth.confirm-password-reset-request', $token);
            $user->notify(new RequestForPasswordReset($url, $user->name, $user->email));
            return response()->json([
                'message' => 'Password reset request has been sent to your email address.',
            ], 200);
        }
    }
}
