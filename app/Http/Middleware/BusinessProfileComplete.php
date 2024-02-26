<?php

namespace App\Http\Middleware;
use DB;
use Auth;
use Closure;

class BusinessProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
      // Please note here complete = 1 means user has completed his profile 

        $business_id = Auth::user()->business_id; 

        $business = DB::table('users')->select('business_id','user_type','parent_id','is_business_data_completed')->where(['business_id'=>$business_id])->first();

          if($business->is_business_data_completed == 0) { 
            // echo "now redirect here to complete business data!";
            return redirect(route('/business-info'));
          }

        return $next($request);
    }

}
