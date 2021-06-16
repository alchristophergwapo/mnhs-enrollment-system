<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordAPIController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Send password reset link
     * @param \Illuminate\Http\Request $request
     */

    // public function sendResetLinkEmail(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => 'required|email'
    //     ]);

    //     $emailRes = Password::sendResetLink($credentials);
    //     if ($emailRes) {
            
    //     }
    //     return response($emailRes);
    //     // return $emailRes == Password::RESET_LINK_SENT ? $this->sendResetLinkResponse($request, $emailRes) : $this->sendResetLinkFailedResponse($request, $emailRes);
    // }

    /**
     * Get the response for a successful password reset link
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $response
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response()->json([
            'message' => 'Password reset email sent. Please check your email to continue.',
            'data' => $response
        ]);
    }

    /**
     * Get the response for a successful password reset link
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $response
     * @return \Illuminate\Http\JsonResponse
     */

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json(['message' => 'Email could not be sent to this email address.', 'data' => $response],400);
    }
}
