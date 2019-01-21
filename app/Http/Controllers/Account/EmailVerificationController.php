<?php

namespace App\Http\Controllers\Account;

use App\EmailVerification;
use App\Http\Requests\EmailVerification\ResendEmailVerificationRequest;
use App\Http\Requests\EmailVerification\VerifyEmailVerificationRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmailVerificationController extends Controller
{
    public function __construct()
    {
        //$this->middleware('guest');
    }

    /**
     * Creates and saves a new email verification
     *
     * @param ResendEmailVerificationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(ResendEmailVerificationRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User nicht gefunden',
            ], 404);
        }

        if ($user->email_verified_at != null) {
            return response()->json([
                'message' => 'Email bereits bestätigt',
            ], 200);
        }

        $success = $user->sendEmailVerification();

        if ($success) {
            return response()->json([
                'message' => 'Verification Email wurde gesendet.',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Email konnte nicht gesendet werden.',
            ], 500);
        }
    }

    /**
     * Tries to verify a User's account
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(VerifyEmailVerificationRequest $request)
    {
        if ($request->email == null || $request->token == null) {
            return response()->json([
                'message' => 'Link Fehlerhaft.',
            ], 500);
        }

        $email = $request->email;
        $token = $request->token;

        try {
            $user = User::where('email', $email)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User nicht gefunden.',
            ], 404);
        }

        if ($user->email_verified_at != null) {
            return response()->json([
                'message' => 'User already verified.',
            ], 200);
        }

        //Get the potential email verification
        try {
            $verification = EmailVerification::where('user_id', $user->id)->where('token', $token)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Email konnte nicht verifiziert werden. Beantragen Sie eine neue Verifikations-Email oder kontaktieren Sie einen Administrator.',
            ], 500);
        }

        //Check if the token is still valid
        if ($verification->requested_at < Carbon::now()->addMinutes(-120)) {
            //Delete the token from DB
            $verification->delete();
            //Return error
            return response()->json([
                'message' => 'Token abgelaufen. Bitte beantragen Sie einen neuen.',
            ], 403);
        } else {
            //Delete the token from DB
            $verification->delete();
            //Set the account as verified
            $user->email_verified_at = Carbon::now();
            $user->save();
            //Return success message
            return response()->redirectTo('http://landing.shellyfish.de');
        }
    }
}
