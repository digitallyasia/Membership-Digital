<?php

namespace App\Http\Controllers\Organization\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating teacher users for the application and
    | redirecting them to your teacher dashboard.
    |
    */
    /*
     * This trait has all the login throttling functionality.
     */
    use ThrottlesLogins;
    /**
     * Max login attempts allowed.
     */
    public $maxAttempts = 5;
    /**
     * Number of minutes to lock the login.
     */
    public $decayMinutes = 3;

    /**
     * Only guests for "teacher" guard are allowed except
     * for logout.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:organization')->except('logout');
    }

    /**
     * Show the login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Login the teacher.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
        $this->validator($request);
        //check if the user has too many login attempts.
        if ($this->hasTooManyLoginAttempts($request)) {
            //Fire the lockout event
            $this->fireLockoutEvent($request);
            //redirect the user back after lockout.
            return $this->sendLockoutResponse($request);
        }
        //attempt login.
        if (Auth::guard('organization')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            //Authenticated, redirect to the intended route
            //if available else teacher dashboard.
            return redirect()
                ->intended(route('organization.dashboard'));
        }
        //keep track of login attempts from the user.
        $this->incrementLoginAttempts($request);
        //Authentication failed, redirect back with input.
        return $this->loginFailed();
    }

    /**
     * Logout the teacher.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::guard('organization')->logout();
        return redirect()
            ->route('organization.login');
    }

    /**
     * Validate the form data.
     *
     * @param \Illuminate\Http\Request $request
     * @return
     */
    private function validator(Request $request)
    {
        //validation rules.
        $rules = [
            'email'    => 'required|email|exists:organizations|min:5|max:191',
            'password' => 'required|string|min:8|max:255',
        ];
        //custom validation error messages.
        $messages = [
            'email.exists' => 'These credentials do not match our records.',
        ];
        //validate the request.
        $request->validate($rules, $messages);
    }

    /**
     * Redirect back after a failed login.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function loginFailed()
    {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Login failed, please try again!');
    }

    /**
     * Username used in ThrottlesLogins trait.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }
}
