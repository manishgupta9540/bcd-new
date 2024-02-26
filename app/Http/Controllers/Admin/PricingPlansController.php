<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class PricingPlansController extends Controller
{
    public function price_plan()
    {
    	$active_plan = "";

        $business_id = Auth::user()->business_id;

    	$active_plan = DB::table('subscription_plans as s')
    		->select('s.*')
    		->join('user_subscriptions as us','us.subscription_id','=','s.id')
    		->where(['us.business_id'=>$business_id])
    		->first();

    	$plan_items = DB::table('subscription_plan_items as s')
    		->select('s.*')
    		->join('user_subscriptions as us','us.subscription_id','=','s.id')
    		->where(['business_id'=>$business_id])
			->first();
			
    	return view('admin.price_plan',compact('active_plan'));
    }
}
