<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerificationToken;
use App\Models\User;
use App\Notifications\EmailVerified;
use Illuminate\Http\Request;

class ConfirmEmailVerificationRequest extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if ($request->route('token') != null) {
            $email_verification_token = EmailVerificationToken::where('token', $request->route('token'))->first();
            if ($email_verification_token == null) {
                return response()->json([
                    'message' => 'Invalid token.',
                ], 404);
            } else {
                $user = User::where('email', $email_verification_token->email)->first();
                $user->update([
                    'email_verified_at' => now(),
                ]);
                $email_verification_token->delete();
                $user->notify(new EmailVerified());
                return response()->json([
                    'message' => 'Email verified',
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'Missing required token',
            ], 400);
        }
        // get email and token
        // check email and token exists in EmailVerificationToken
        // if no, return error
        // if yes, update user email_verified_at, delete EmailVerificationToken, and send an email with message email successfully verified
    }
}
