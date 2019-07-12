<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;    // to use predefined auth process
use App\Pointtransaction;                         // to use the PointTransaction class
use App\User;                         // to use the User class
use App\Voucher;                         // to use the User class
use App\Mail\VoucherMail;
use Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, $shop)
    {
        // get user data
        $userData = Auth::user();

        // voucher exist for the given user
        if (Voucher::where('user_id', $userData->id)->count())
        {
          // get voucher code
          $voucher = Voucher::where('user_id', $userData->id)->get();

          return view('home',[
                      'shop' => $shop,
                      'voucher' => $voucher[0]->voucher,
                    ]);
        }
        else
        {
          return view('home',[
                      'shop' => $shop,
                    ]);
        }

    }

    public function redirectToProvider($shop, $provider)
    {
        session()->regenerate();
        session(['shop' => $shop]);

        // get user data
        $userData = Auth::user();

        $points = 0;

        if(!$userData[$provider.'_like'])
        {
            switch($provider)
            {
                case 'facebook': $points = env('POINT_FACEBOOK'); break;
                case 'instagram': $points = env('POINT_INSTAGRAM'); break;
                case 'google': $points = env('POINT_GOOGLE'); break;
                case 'youtube': $points = env('POINT_YOUTUBE'); break;
            }
        }

        $this->updatePoints($points,"like ".$provider);

        User::where('id', $userData->id)->update([$provider.'_like'=>1]);

        switch($provider)
        {
            case 'facebook': return redirect()->away('https://www.facebook.com/HappiMobiles1/');
            case 'instagram': return redirect()->away(' https://www.instagram.com/happimobiles/');
            case 'google': return redirect()->away(' https://www.instagram.com/happimobiles/');
            case 'youtube': return redirect()->away('https://www.youtube.com/channel/UCFlcLoM3mfOA1-2GYdXm50g');
        }

    }

    // Obtain the data from provider.
    public function handleProviderCallback($provider)
    {
        $shop = session('shop');
    }

    public function updatePoints($points,$remarks="none")
    {
      // get user data
      $userData = Auth::user();
      // calcualte the latest points
      $user_points = (int)$userData->points + $points;
      // update the points for the user
      User::where('id', $userData->id)->update(['points'=>$user_points]);
      // log the transcastion in the table
      $type = "Credit";
      if($points < 0)
      {
        $type = "Debit";
      }

      Pointtransaction::create([
          'user_id'    => $userData->id,
          'type'    => $type,
          'points'   => $points,
          'remarks' => $remarks,
      ]);

    }

    public function providerStatus($shop, $provider)
    {
      // get user data
      $userData = Auth::user();

      return (string) $userData[$provider.'_like'];
    }

    public function RedemePoints(Request $request, $shop)
    {
        // get user data
        $userData = Auth::user();

        if(!$userData['redeme'])
        {
          if ($userData['points'] == env('POINT_MAX')) {
            $all_voucher = $this->voucherPool();
            $count = Voucher::count();
            $selected_vooucher = $all_voucher[$count];
            // User::where('id', $userData->id)->update(['redeme'=>1]);
            // Voucher::create([
            //   'voucher' => $selected_vooucher,
            //   'user_id' => $userData->id,
            // ]);
            Mail::to($userData['email'])->send(new VoucherMail());
          }
          return redirect($shop.'/home');
        }


    }

    // all the voucher code in csv formate
    public function voucherPool()
    {
      return ['HAPY26','HAPyU7','HAPKcG','HAP8g5','HAPAOO','HAP2p1','HAPmIo','HAP7b9','HAPMFq','HAPS6S'];
    }

}
