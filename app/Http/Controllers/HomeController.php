<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;    // to use predefined auth process
use App\Pointtransaction;                         // to use the PointTransaction class
use App\User;                         // to use the User class
use App\Voucher;                         // to use the User class
use App\Shop;
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

        // get the google plus link for the specific shop
        $shopDetails = Shop::where('id', $shop)->get();

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
            case 'instagram': return redirect()->away('https://www.instagram.com/happimobiles/');
            case 'google': return redirect()->away((string)$shopDetails[0]->gplus_link);
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
          if ($userData['points'] >= env('POINT_MAX')) {
            $all_voucher = $this->voucherPool();
            $count = Voucher::count();
            $selected_voucher = $all_voucher[$count];
            User::where('id', $userData->id)->update(['redeme'=>1]);
            Voucher::create([
              'voucher' => $selected_voucher,
              'user_id' => $userData->id,
            ]);
            Mail::to($userData['email'])->send(new VoucherMail($selected_voucher));
          }
          return redirect($shop.'/home');
        }


    }

    // all the voucher code in csv formate
    public function voucherPool()
    {
      return ['HAPi8#','HAPO$V','HAPycX','HAPrqS','HAP7NP','HAP@Yv','HAP4w3','HAPD%q','HAPRY8','HAPS1f','HAPMP@','HAPIjU','HAPgpO','HAPXSg','HAPtAs','HAPRAr','HAPkGL','HAPjrH','HAP@Fb','HAPxr#','HAPi2E','HAPPsr','HAPT8Q','HAPUUG','HAP#1W','HAP5X%','HAPx5h','HAPZ7W','HAPYqy','HAPVRg','HAPtvl','HAP$HH','HAP@zk','HAPP6Z','HAP0rG','HAPN9L','HAPAk&','HAPgQo','HAPPxR','HAPWFi','HAPg15','HAPsW$','HAPp5e','HAPazT','HAPlCM','HAPLfV','HAPw$F','HAPAiP','HAP6m9','HAPT5T','HAPGhg','HAP4M8','HAPPdL','HAPMwS','HAPJTq','HAPnyU','HAPg4@','HAPcbj','HAP80U','HAPNXv','HAPXU3','HAPf1O','HAPh9J','HAP&qB','HAPPhS','HAP5e0','HAPQrX','HAP%8j','HAPhEn','HAPHzf','HAPFls','HAP$Mo','HAPefC','HAP2uu','HAPmw%','HAPJqF','HAPUDR','HAPI7Z','HAPRBe','HAPB8H','HAP1Zh','HAPbz8','HAPY4p','HAPgjr','HAPwzy','HAP&&B','HAP9Tn','HAPPQ$','HAPpNO','HAP%jz','HAP$SK','HAPSgl','HAP2vR','HAP8rM','HAPXEW','HAPp1U','HAPPnV','HAPvMg','HAPe8i','HAPlY@','HAPnWP','HAPIbE','HAPbaD','HAP819','HAP4xo','HAPBgF','HAPvm$','HAPzE8','HAP3Di','HAPW7J','HAPC&S','HAPbsv','HAPHGb','HAP0co','HAPyYg','HAPZNo','HAPr&n','HAPNai','HAPt#e','HAPCG$','HAPMCr','HAPqtX','HAPVMr','HAP5@6','HAPyrc','HAPl4t','HAPCMx','HAPKTd','HAPdsw','HAPDL#','HAPWuc','HAPBgL','HAPgDF','HAPFEN','HAPVOv','HAPAQ$','HAP4#U','HAPscO','HAPN56','HAP53S','HAPCpU','HAPU%r','HAPLM9','HAPZvS','HAPwG#','HAPuJk','HAPlAP','HAPZba','HAPs5L','HAPCnG','HAPEWD','HAPIYz','HAP$Y9','HAPGBL','HAP3b1','HAPs5#','HAPG@h','HAPZhT','HAP3Hh','HAP#WR','HAPpD7','HAPLz5','HAPzV1','HAPmTj','HAPOxy','HAPNwu','HAPQL6','HAPxzd','HAP&S5','HAP$3q','HAPpNk','HAP1eJ','HAPFYC','HAPU9b','HAPY$w','HAPvr1','HAPvN@','HAPSD$','HAPJpB','HAPL#m','HAP2xf','HAPWFZ','HAPx3W','HAPMVC','HAPM#d','HAPqb1','HAPx5M','HAP2O0','HAPiD$','HAP6rX','HAPq0T','HAPTkW','HAP1nL','HAPKFa','HAPCui','HAPFCB','HAP#ei','HAPrHn','HAPeyc','HAPfFv','HAPo8H','HAPK@k','HAPZNb','HAPNbD','HAPN1r','HAPgw4','HAP2Ok','HAPKGk','HAPZa2','HAPrDm','HAPPpx','HAPsS3','HAPStV','HAPAri','HAPrWB','HAPjPx','HAPDAD','HAP%JC','HAPsCz','HAPYXJ','HAPHtF','HAPo2w','HAPd0#','HAPsFF','HAP&kI','HAPT&%','HAPQxa','HAPv01','HAP6M0','HAPJv%','HAPWyi','HAPGCN','HAP#M0','HAP&op','HAPrvw','HAP5Jw','HAPiqx','HAP&$o','HAPnu0','HAP0MS','HAPDKc','HAP1h@','HAPtv$','HAPhHw','HAP$mG','HAPE86','HAPWr8','HAPSPA','HAP2rk','HAP24O','HAP7xl','HAPxak','HAP5F$','HAP@Ym','HAP&Wm','HAPBa8','HAPx3e','HAPQAh','HAPkyE','HAPgAr','HAPx%@','HAPkPV','HAPZur','HAPi$$','HAPnP1','HAPROr','HAPtVw','HAPt3B','HAPdDM','HAPmgd','HAPbvA','HAPHBL','HAPlpa','HAPhCT','HAPzUZ','HAPCHj','HAPKI4','HAPlYt','HAP40U','HAPWy1','HAPYHg','HAPJuT','HAPL%z','HAP$xY','HAP2Yx','HAPRI4','HAPAEv','HAP#bW','HAPedj','HAPsaz','HAPd28','HAPan1','HAPII6','HAP@AW','HAPqDU','HAPc1t','HAPv55','HAPnCz','HAPTsA','HAPhT7','HAPKCj','HAPDYW','HAPVs5','HAPWsH','HAPkTj','HAP@xn','HAPvcC','HAPrFa','HAPeTS','HAPzJO','HAPwg2','HAP90%','HAPyla','HAP7IY','HAP5sf','HAP@$X','HAPhs2','HAPnC$','HAP531','HAP65B','HAP0rB','HAPJm$','HAP3Ky','HAPf#p','HAPTtM','HAPGl0','HAPW7r','HAP%A2','HAPUdv','HAP@#q','HAPkLv','HAP7Mz','HAP9&f','HAPcFo','HAP4OO','HAPoeX','HAPUZn','HAP1Ms','HAPT3k','HAPJvI','HAP5vR','HAP3aN','HAPnik','HAPnM9','HAPooe','HAPxqk','HAPlc1','HAPN#E','HAP2P8','HAPlul','HAPj8o','HAPhHL','HAPSPw','HAPxq9','HAPplg','HAPz#%','HAPMMH','HAPjTI','HAP08J','HAPeYb','HAPVaN','HAP8j4','HAPNJo','HAP7&6','HAPxkM','HAP4Bu','HAPLxw','HAP$iW','HAPy84','HAPLwj','HAPbsu','HAP31y','HAPSXx','HAPECp','HAPQUm','HAPEye','HAPzhO','HAPInK','HAP@Zm','HAPFLT','HAPqdo','HAPXHE','HAP5FA','HAP73e','HAPrEH','HAPkF5','HAPR8O','HAPbjp','HAP$&6','HAPdHg','HAPdP%','HAPT6%','HAPgK@','HAPa%x','HAPc9W','HAPH4R','HAPxw$','HAPrAa','HAP6er','HAPtNH','HAPiw8','HAPqr0','HAPk@h','HAPmqT','HAPVcq','HAP2w#','HAPvnG','HAPI@x','HAPlw%','HAPbew','HAP9xQ','HAPdW9','HAP%sM','HAPupw','HAPKax','HAP@Ur','HAPWvN','HAPyOU','HAPDKy','HAP#c0','HAPUqx','HAPOh0','HAPz#K','HAPDa1','HAP0bL','HAPynU','HAPiVF','HAPTDS','HAPmse','HAPmWO','HAPNV6','HAP2V6','HAPuWv','HAPiF#','HAPCdu','HAPWUb','HAPvK0','HAPyzW','HAPdYv','HAPqTP','HAPRz9','HAPWDH','HAPHHn','HAPj6X','HAPhD5','HAPk6r','HAP5CA','HAPHGk','HAPi#b','HAP0sa','HAPTV9','HAPi2Y','HAPSh0','HAPV@R','HAPOga','HAPSnB','HAPnJX','HAP4eT','HAP9Sq','HAPYPx','HAPCZH','HAPn8A','HAPbXC','HAP&Wh','HAP&Uo','HAPnst','HAPuoN','HAPYIO','HAPEF1','HAPZS$','HAPhbR','HAPSDm','HAP@jU','HAPQOn','HAPmDj','HAP&Di','HAPGP0','HAPVNY','HAPBpi','HAPCq5','HAPaiK','HAP$@4','HAPCd7','HAPd5i','HAP$Xl','HAPmle','HAPbvP','HAPB@W','HAPuZL','HAPP13','HAP6EZ','HAPDON','HAP@GM','HAPIEQ','HAPUC7','HAPIS3','HAPDp5','HAP2A6','HAPUY0','HAPuN3','HAPA7@','HAPDx&','HAPF1E','HAPfPy','HAP%4N','HAPYg#','HAPKMn','HAPoZR','HAPaEN','HAP6kZ','HAPpBo','HAPgkK','HAPyYu','HAPWSO','HAPvgD','HAPQf3','HAPAFc','HAPSyt','HAPr&V','HAPuZp','HAPyr5','HAPBE3','HAPE6X','HAPXYV','HAPmpL','HAPg#m','HAPGqT','HAP3Xl','HAP078','HAPyJ5','HAP#D#','HAPdTQ','HAPQgw','HAPBKw','HAPNrx','HAPJbj','HAPU&4','HAP#u#','HAP5Wt','HAPkr0','HAPB9G','HAPmTI','HAPQX2','HAPxrx','HAP1b9','HAPbDW','HAPiAN','HAP8VL','HAP4DK','HAPPHa','HAPJEU','HAP1SP','HAPVzb','HAPmUR','HAPSia','HAP%@&','HAP6Sr','HAPzOZ','HAPtUQ','HAPbZO','HAPvg$','HAP3zJ','HAPThV','HAPyOd','HAPNU@','HAPzpC','HAPf@5','HAPwsC','HAPPdY','HAPQvs','HAPTqg','HAPS7U','HAPBMu','HAPumw','HAPK0r','HAPCzq','HAPi1L','HAPkbh','HAPXJh','HAPqVW','HAPQh$','HAPqOB','HAPibO','HAP9HC','HAP2wd','HAP@io','HAPLbO','HAP893','HAPmHi','HAPuIX','HAPq8V','HAPg75','HAPTw5','HAPO$9','HAPiZB','HAPkZl','HAP5WT','HAPa6b','HAPb5C','HAPqtj','HAPHcs','HAPGwu','HAPXBe','HAPbFM','HAPtRj','HAPqEn','HAPHx2','HAP$PY','HAPQvn','HAPklT','HAPu6P','HAP3@G','HAP4@5','HAPPe#','HAPRNE','HAPeA0','HAPPEi','HAPCUh','HAPOA2','HAPuIC','HAPQ&&','HAPIP#','HAPNyj','HAPOHJ','HAPdoS','HAPMC7','HAP6Yz','HAPDj0','HAPp9E','HAP7c@','HAPfw2','HAPtDE','HAPg@d','HAPVQ#','HAPyed','HAPTFl','HAPzOC','HAPI8J','HAP@$4','HAPc6u','HAPmhi','HAPdFu','HAPv&S','HAP12M','HAPslC','HAPr3M','HAPt6F','HAP1fq','HAPIBU','HAP2Nd','HAPdL9','HAP8MJ','HAPKKU','HAPH5B','HAP8@C','HAPqFl','HAPx0A','HAP@nA','HAPyUz','HAPJWQ','HAPiHl','HAP&mx','HAPYFw','HAPiS5','HAPQ1P','HAPZqJ','HAPJxj','HAPsID','HAPyzr','HAPdfI','HAPnJt','HAPcWH','HAPtg#','HAPkh%','HAP69l','HAPUoI','HAPxPG','HAPpcH','HAPIHx','HAPgJH','HAPhH5','HAPxIR','HAPRL7','HAPgzj','HAPd#p','HAPsRL','HAPFjt','HAP4ox','HAP9I&','HAPafs','HAPaV#','HAPBNQ','HAPb#b','HAPPfo','HAP&gv','HAPYuC','HAPOwy','HAPPi6','HAP%XV','HAPtCg','HAPGmG','HAPEUV','HAPhyV','HAPVDo','HAPz5k','HAP1Xh','HAP6zz','HAPfWs','HAP5KE','HAPE1c','HAPERT','HAPSWu','HAPtT4','HAP4XW','HAPOtv','HAPYmi','HAP9Xk','HAPb4r','HAPA@%','HAPVTc','HAPz#o','HAPvZM','HAPqFR','HAPfpJ','HAP8aI','HAPTL4','HAPLIM','HAPmad','HAPbcV','HAPQ4J','HAP&GF','HAPEcE','HAP%uE','HAPVWg','HAPonK','HAPuxj','HAPuJR','HAPom2','HAP3EA','HAPz@D','HAPfsD','HAP3ov','HAPb6p','HAPUnh','HAPig#','HAPsS6','HAPc6F','HAPQj%','HAPlFJ','HAP3cH','HAPxDk','HAPOTn','HAPHad','HAPn#m','HAPdyr','HAP9pi','HAPd&J','HAPO8#','HAP6Jg','HAPSqp','HAPD#f','HAPr6T','HAPm1n','HAPrnx','HAPPzP','HAPdsG','HAPOsn','HAPUD9','HAPBEy','HAPiP#','HAPChj','HAP10w','HAPJ4q','HAPM1W','HAPNPM','HAPlPe','HAPq1G','HAPByr','HAP9e@','HAPD4a','HAPj7S','HAPzdy','HAP3MJ','HAP&@q','HAP#R0','HAPXhq','HAPtB7','HAPB0e','HAPhDP','HAPgbi','HAPFDQ','HAPKUe','HAPTf1','HAPb8g','HAPYCe','HAP5pc','HAPOc1','HAPXQg','HAP$NL','HAPzdU','HAPBOI','HAPUN&','HAPxA#','HAPVFL','HAPwdq','HAPfkT','HAPH17','HAPUgi','HAPoYZ','HAP@GB','HAPpX3','HAP47j','HAP8@@','HAPzD$','HAPLAs','HAPPP#','HAP3tA','HAP$ZI','HAPm7x','HAPvTr','HAPamv','HAPEh0','HAPLvx','HAPSIc','HAP$7H','HAPmEe','HAPKqR','HAPCK2','HAPblE','HAPmji','HAP&y@','HAP1Mj','HAP4u7','HAPgDL','HAPeso','HAP&cC','HAPTlI','HAPrK7','HAPSM4','HAPD2j','HAPjm0','HAPN3X','HAPiHD','HAPCGf','HAP0fO','HAPe9H','HAPHcb','HAPV#Q','HAP1RG','HAPLKA','HAP%V2','HAPAPD','HAPpsX','HAPEd2','HAPbFh','HAPR9Y','HAPVUC','HAP#Np','HAPo#n','HAPKUK','HAPw%g','HAPovx','HAPH@2','HAPqwh','HAPgP9','HAPuaQ','HAP$yp','HAPh@4','HAPkX4','HAPczd','HAP8cE','HAPm5R','HAPvd8','HAPJ@A','HAPA3L','HAPmZ4','HAPPUA','HAPEjV','HAPblK','HAPKPO','HAPXLC','HAPtcD','HAPN&%','HAPSHE','HAPdH6','HAPqQF','HAP5fr','HAPosc','HAPgjN','HAPUAG','HAPnMo','HAPGf3','HAPXbE','HAPrkW','HAPly%','HAPqKP','HAPtAb','HAP9yS','HAPLx1','HAPTov','HAPn16','HAPKhA','HAPEAc','HAPclw','HAPePY','HAPPqj','HAP9SD','HAP4Sx','HAPPbf','HAP2$$','HAPTDC','HAPztz','HAPRqE','HAPIWD','HAPr&w','HAPYGg','HAPGp5','HAPfc@','HAPmrT','HAP#UL','HAPFqg','HAPX0k','HAPqbW','HAPtmP','HAP&XS','HAPbOS','HAPUpS','HAPWLK','HAPzi6','HAP14w','HAP%rU','HAPMbR','HAPSY&','HAPwgp','HAPZ7U','HAPogB','HAPf6o','HAPo7F','HAP1TE','HAPUq#','HAPRg%','HAPITg','HAPkxb','HAPM3A','HAP#AL','HAP#@@','HAPQ9b','HAPemx','HAP6g&','HAPxjK','HAPDN9','HAPO5y','HAP@Yd','HAPN2A','HAPhSJ','HAP&xJ','HAP9o6','HAP%q2','HAPKxf','HAPJmd','HAP6pE','HAPbd4','HAPVx1','HAP6%j','HAPKu7','HAPk4Q','HAPUA8','HAPTRp','HAPYpY','HAPxrH','HAP4ke','HAPqVo','HAPhgR','HAPNUc','HAPw2Y','HAP#7M','HAPpT%','HAPJKy','HAPnJe','HAPcO0','HAP3qA','HAPCp3','HAPWVN','HAPer1','HAP86g','HAPx44','HAP&07','HAPGRY','HAPxec','HAP4pC','HAPkB4','HAPKDg','HAPno6','HAPJjW','HAPtxu','HAPxjE','HAPkCl','HAPqyi','HAPdZ$','HAPGv2'];
    }

}
