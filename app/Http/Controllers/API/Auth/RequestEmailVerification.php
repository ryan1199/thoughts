<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\EmailVerificationRequest;
use App\Models\EmailVerificationToken;
use App\Models\User;
use App\Notifications\RequestForEmailVerification;
use Illuminate\Support\Str;

class RequestEmailVerification extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();
        if ($user == null) {
            return response()->json([
                'message' => 'The provided email address is not exists in our records.',
            ], 404);
        } else {
            if ($user->email_verified_at == null) {
                $email_verification_token = EmailVerificationToken::where('email', $user->email)->first();
                $token = Str::random(100);
                if ($email_verification_token == null) {
                    $email_verification_token = EmailVerificationToken::create([
                        'email' => $user->email,
                        'token' => $token,
                    ]);
                } else {
                    if (!$email_verification_token->created_at->isToday()) {
                        $email_verification_token->token = $token;
                        $email_verification_token->save();
                    }
                }
                $url = route('auth.confirm-email-verification-request', $email_verification_token->token);
                $user->notify(new RequestForEmailVerification($url));
                return response()->json([
                    'message' => 'Email verification request has been sent to your email address.',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Email already verified.',
                ], 400);
            }
        }
    }
}
