<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        $credentials = array(
            'email' => $request->get('email'),
            'password' => $request->get('passwor'),
            'shop' => 3,
        );

        $user = Student::where('usn', $request->usn);

        if (Auth::attempt($credentials)){
            // Authentication passed...
            return redirect()->intended('dashboard');
        }
        echo "login failed";
    }

    use AuthenticatesUsers {
      login as getlogin;
    }

    public function login() {
      // Do my stuff
      return $this->login()->with('myvar', 'Hello World');
    }

}
