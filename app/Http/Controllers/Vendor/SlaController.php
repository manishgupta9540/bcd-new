<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SlaController extends Controller
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
        $sla = DB::table('vendor_slas as sla')
                ->select('sla.*','u.first_name','u.last_name','vb.company_name')
                ->join('users as u','u.id','=','sla.business_id')
                ->join('vendor_businesses as vb','vb.business_id','=','sla.business_id')
                ->where(['u.business_id'=>Auth::user()->business_id])
                ->orderBy('sla.id','desc')
                ->get();

                // dd($sla);
        return view('vendor.sla.index', compact('sla'));
    }
}
