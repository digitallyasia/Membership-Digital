<?php

namespace App\Http\Controllers\Organization\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $redirectTo = RouteServiceProvider::HOME;

    protected function guard()
    {
        return Auth::guard('organization');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return Inertia::render(
            'Auth/Passwords/Reset',
            [
                'token' => $token,
                'email' => $request->email
            ]
        );
    }

    public function broker()
    {
        return Password::broker('organizations');
    }
}
