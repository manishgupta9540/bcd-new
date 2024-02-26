<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Redirect;


class LoginController extends Controller
{
   use ThrottlesLogins;
    // protected $maxAttempts = 3; // Default is 5
    // protected $decayMinutes = 1; // Default is 1
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
    protected $maxAttempts = 3; // Default is 5
    protected $decayMinutes = 1; // Default is 1
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('guest')->except('logout');
        // $this->middleware('throttle:10,2');
        
    }
 
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        //User Role
        // dd(1);
        $user_id=Auth::user()->id;
        $business_id = Auth::user()->business_id; 

        $account_type = DB::table('users')->select('business_id','user_type','parent_id','is_business_data_completed')->where(['business_id'=>$business_id])->first();
        // echo die("hello");
        // echo $account_type->user_type;
        if($account_type->user_type == 'superadmin'){
            // dd($account_type->user_type);
            return '/app/home';
        }

        if($account_type->user_type == 'customer'){
            return '/home';
        }

        if($account_type->user_type == 'client'){
            return '/my/home';
        }
        if($account_type->user_type == 'vendor'){
            // dd('abc');
            return '/vendor/home';
        }
        if($account_type->user_type == 'guest'){
            return '/verify/home';
        }

        
        // die('die here');
        //Check Role

    }

    


}
