<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        $username = $request->input($this->username());
        $password = $request->input('password');
        //假设字段是 email
        if ($this->guard()->attempt([
            'email' => $username,
            'password' => $password],
            $request->filled('remember'))
        ) {
            activity('登录日志')->withProperties(['username' => $username])->log('登录成功');
            return $this->sendLoginResponse($request);
        }

        //假设字段是 mobile
        if ($this->guard()->attempt(['tel' => $username, 'password' => $password], $request->filled('remember'))) {
            activity('登录日志')->withProperties(['username' => $username])->log('登录成功');
            return $this->sendLoginResponse($request);
        }

        //假设字段是 username
        if ($this->guard()->attempt(['username' => $username, 'password' => $password], $request->filled('remember'))) {
            activity('登录日志')->withProperties(['username' => $username])->log('登录成功');
            return $this->sendLoginResponse($request);
        }
        activity('登录日志')->withProperties(['username' => $username])->log('登录失败');
        return $this->sendFailedLoginResponse($request);
    }
}
