<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use DB;
use Illuminate\Support\Facades\Auth;

class CaseController extends Controller
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
    public function index()
    {
        $business_id = Auth::user()->business_id;

        $cases = DB::table('jobs as j')
              ->select('j.business_id','j.id','j.title','j.total_candidates','j.created_at','j.created_by','j.status','j.sla_id')
              ->where(['business_id'=>$business_id])
              ->get();

        return view('clients.cases.index', compact('cases'));
    }

    
}
