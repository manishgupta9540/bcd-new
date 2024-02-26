<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    //

     /*** Show the profile data
     * ** @return \Illuminate\Http\Response*/
    
    public function profile()
    {
        $profile = User::find(Auth::user()->id);
        
        $business = DB::table('vendor_businesses')->where(['business_id'=>Auth::user()->id])->first();

        

        return view('vendor.accounts.profile', compact('profile','business'));
    } 
    
    public function business_info()
    {
        $profile = User::find(Auth::user()->id);

        $business_id = Auth::user()->business_id;

        $countries = DB::table('countries')->get();

        $business = DB::table('vendor_businesses as b')
        ->select('b.*')
        ->where(['b.business_id'=>$business_id])
        ->first();

        $states  = DB::table('states')->where(['country_id'=>$business->country_id])->get();

        $cities = DB::table('cities')->where(['state_id'=>$business->state_id])->get();

        // $countries  = DB::table('countries')->get();

        return view('vendor.accounts.business-info', compact('profile','business','countries','states','cities'));
    } 
}
