<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Notifications\NewPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ConfirmPasswordResetRequest extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if ($request->route('token') != null) {
            $password_reset_token = PasswordResetToken::where('token', $request->route('token'))->first();
            if ($password_reset_token == null) {
                return response()->json([
                    'message' => 'Invalid token.',
                ], 404);
            } else {
                $password = Str::random(8);
                $user = User::where('email', $password_reset_token->email)->first();
                $user->update([
                    'password' => Hash::make($password),
                ]);
                $password_reset_token->delete();
                $user->notify(new NewPassword($password));
                return response()->json([
                    'message' => 'Password reseted successfully',
                    'new_password' => $password
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'Missing required token',
            ], 400);
        }
        // get email and token
        // check email and token exists in PasswordResetToken
        // if no, return error
        // if yes, generate new password, update user with new password, delete PasswordResetToken, and send an email with new password
    }
}
