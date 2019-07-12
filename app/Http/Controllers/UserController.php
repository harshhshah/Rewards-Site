<?php

namespace App\Http\Controllers;

use App\User;                           // to use the user class

use Validator;                          // to use the Validator functions to validate the user input

use Illuminate\Http\Request;
                                        // to use the request function
use App\Http\Requests;

use Illuminate\Support\Facades\Auth;    // to use predefined auth process

use App\Http\Controllers\Controller;    // to use the Controller class

use DB;                                 // to acess to the database

use App\Http\Controllers\View;          // to acess the views functions

use Hash;                               // to access the hashing functons

use Socialite;                          // to use the social login

class UserController extends Controller
{
    // show registration form
    public function ShowRegisterForm(Request $request, $shop)
    {
        return view('auth.register', [
                    'shop'=>$shop
                  ]);
    }

    // register new User
    public function Register(Request $request)
    {
    // Validate the request...
    $validator = Validator::make($request->all(), [
              'name' => 'required|max:255',
              'email' => 'required|email|max:255',
              'password' => 'required|min:8|confirmed',
        ])->validate();

      $authlogin = User::create([
          'name'     => $request->name,
          'email'    => $request->email,
          'password' => bcrypt($request->password),
          'shop'     => $request->shop,
          'provider' => 'email',
          'points'   => 0,
          'google_like' => 0,
          'facebook_like' => 0,
          'instagram_like' => 0,
          'youtube_like' => 0,
          'redeme' => 0,
      ]);

      Auth::login($authlogin, true);

      return redirect($request->shop.'/home');
    }

    // show registration form
    public function ShowLoginForm(Request $request, $shop)
    {
        return view('auth.login', [
                    'shop'=>$shop
                  ]);
    }

    // to login the user into the system
    public function Login(Request $request)
    {
      // Validate the request...
      $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:255',
          ])->validate();
      //do login
      $shop = $request->shop;

      $userExist = User::where('email', $request->email)->where('shop', $request->shop)->where('provider', 'email')->exists();

      if ($userExist)
      {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            $userDetails = User::where('email', $request->email)->where('shop', $request->shop)->where('provider', 'email')->first();
            Auth::login($userDetails, true);
            return redirect( $shop.'/home');
        }
      }

      return redirect($shop.'/login')->withInput();

    }

    // function to logout
    public function Logout(Request $request)
    {
        Auth::logout();
        return redirect( $request->shop.'/login' );
    }

    public function redirectToProvider($shop, $provider)
    {
        session()->regenerate();
        session(['shop' => $shop]);
        return Socialite::driver($provider)->redirect();
    }

    // Obtain the user information from provider.
    public function handleProviderCallback($provider)
    {
        $shop = session('shop');
        $user = Socialite::driver($provider)->user();
        $authUser = $this->findOrCreateUser($user, $provider, $shop);
        Auth::login($authUser, true);
        return redirect( $shop.'/home');
    }

    // If a user has registered before using social auth, return the user else, create a new user object.
    public function findOrCreateUser($user, $provider, $shop)
    {
        $authUser = User::where('provider_id', $user->id)->where('provider', $provider)->where('shop', $shop)->first();
        if ($authUser) {
            return $authUser;
        }

        $authlogin = User::create([
            'name'        => $user->name,
            'email'       => $user->email,
            'shop'        => $shop,
            'provider'    => $provider,
            'provider_id' => $user->id,
            'points'      => 0,
            'google_like' => 0,
            'facebook_like' => 0,
            'instagram_like' => 0,
            'youtube_like' => 0,
            'redeme' => 0,
        ]);

        return $authlogin;
    }
}
