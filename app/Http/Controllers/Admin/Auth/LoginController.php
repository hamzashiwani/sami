<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Admin\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /*
         * Logged in user should not be able to visit login page, unless logged out
         *
         * It's redirection is managed from App > Http > Middleware > RedirectIfAuthenticated
         */
        $this->middleware('guest:admin')->except('logout');
        /*parent::__construct();*/
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {

        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    protected function credentials(Request $request)
    {
        // return $request->only($this->username(), 'password');
        return [
            'email'     => $request->{$this->username()},
            'password'  => $request->password,
            'is_active' => 1
        ];
    }

    public function logout(Request $request)
    {
        /*
         * Here, admin guard is being called by $this->guard()
         */
        $this->guard()->logout();

        // $request->session()->invalidate();

        return redirect()->route('admin.auth.login');
    }

    /**
     * Handle forgot password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function forgotPassword(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.forgot-password');
    }
}
