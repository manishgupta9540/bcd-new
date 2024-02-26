<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class HomeController extends Controller
{
    //
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
    public function index() 
    {
        // $business_id=Auth::user()->business_id;
        // dd($array_result);
        
        // print_r(Auth::user()->session_id);
        // $candidate_count =DB::table('users as u')
        // ->DISTINCT('u.id')
        // ->where(['u.user_type'=>'candidate','u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>'0'])
        // ->get();
        // $candidate_count=count($candidate_count);
        // $orders_count=DB::table('guest_verifications as g')
        // ->where(['g.business_id'=> $business_id])
        // ->whereIn('g.status',['success','failed'])
        // ->get();

        // $order_success_count=DB::table('guest_verifications as g')
        // ->where(['g.business_id'=> $business_id])
        // ->whereIn('g.status',['success'])
        // ->count();

        // $order_failed_count=DB::table('guest_verifications as g')
        // ->where(['g.business_id'=> $business_id])
        // ->whereIn('g.status',['failed'])
        // ->count();

        // $orders_count=count($orders_count);

        $business_id=Auth::user()->business_id;

        // $pending_list=DB::table('guest_verifications as g')
        //         ->select('g.*',DB::raw('group_concat(gs.service_id) as services'))
        //         ->join('guest_verification_services as gs','g.id','=','gs.gv_id')
        //         ->where(['g.business_id'=> $business_id])
        //         ->where('g.status',NULL)
        //         ->orderBy('g.id','desc')
        //         ->groupBy('gs.gv_id')
        //         ->limit(5)
        //         ->get();

        // $order_list=DB::table('guest_verifications as g')
        //         ->select('g.*',DB::raw('group_concat(gs.service_id) as services'))
        //         ->join('guest_verification_services as gs','g.id','=','gs.gv_id')
        //         ->where(['g.business_id'=> $business_id])
        //         ->whereIn('g.status',['success','failed'])
        //         ->orderBy('g.id','desc')
        //         ->groupBy('gs.gv_id')
        //         ->limit(5)
        //         ->get();
        
        // $complete_order=DB::table('guest_verifications')
        //                 ->where(['business_id'=>$business_id])
        //                 ->whereIn('status',['success','failed'])
        //                 ->count();
        // // dd($complete_order);
        
        // $total_order=DB::table('guest_verifications')->where('business_id',$business_id)->get();

        // $total_order=count($total_order);
        
        // dd($total_order);


        $pending_list=DB::table('guest_instant_masters as g')
                    ->select('g.*',DB::raw('group_concat(gc.service_id) as services'))
                    ->join('guest_instant_carts as gc','g.id','=','gc.giv_m_id')
                    ->where(['g.business_id'=> $business_id])
                    ->where('g.status',NULL)
                    ->orderBy('g.id','desc')
                    ->groupBy('gc.giv_m_id')
                    ->limit(5)
                    ->get();

        $order_list=DB::table('guest_instant_masters as g')
                    ->select('g.*',DB::raw('group_concat(gc.service_id) as services'))
                    ->join('guest_instant_carts as gc','g.id','=','gc.giv_m_id')
                    ->where(['g.business_id'=> $business_id])
                    ->whereIn('g.status',['success','failed'])
                    ->orderBy('g.id','desc')
                    ->groupBy('gc.giv_m_id')
                    ->limit(5)
                    ->get();

        $complete_order=DB::table('guest_instant_masters')
                        ->where(['business_id'=>$business_id])
                        ->whereIn('status',['success','failed'])
                        ->count();
        // dd($complete_order);
        
        $total_order=DB::table('guest_instant_masters')->where('business_id',$business_id)->get();

        $total_order=count($total_order);

        return view('guest.home',compact('order_list','pending_list','total_order','complete_order'));
    }
}
