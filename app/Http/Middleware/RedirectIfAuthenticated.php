<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use DB;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if(stripos($guard,'candidate')!==false)
            {
                return redirect()->route('/candidate/candidates/jaf-fill');
            }
            $business_id = Auth::user()->business_id; 
            $account_type = DB::table('users')->select('business_id','user_type','parent_id','is_business_data_completed')->where(['business_id'=>$business_id])->first();
        // echo die("hello");
        // echo $account_type->user_type;
        if($account_type->user_type == 'superadmin'){
            return redirect('/app/home');
        }
        //
        if($account_type->user_type == 'customer'){
            return redirect('/home');
        }
        }

        return $next($request);
    }
}
