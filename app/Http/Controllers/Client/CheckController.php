<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class CheckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $business_id = Auth::user()->business_id;
        $query = DB::table('users as u')
        ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
        ->join('job_items as j','j.candidate_id','=','u.id')        
        ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'is_deleted'=>'0','j.jaf_status'=>'pending']);

        //
        $services = DB::table('services')
        ->select('name','id')
        ->where(['status'=>'1'])
        ->get();

        $checkResults = [];

        foreach ($services as $key => $value) {
            
            $completed = DB::table('jaf_form_data as jf')
            ->join('users as u','u.id','=','jf.candidate_id')
            ->where(['jf.service_id'=> $value->id, 'u.business_id'=>Auth::user()->business_id,'jf.verification_status'=>'success'])
            ->count();

            $pending = DB::table('jaf_form_data as jf')
            ->join('users as u','u.id','=','jf.candidate_id')
            ->where(['jf.service_id'=> $value->id, 'jf.business_id'=>Auth::user()->business_id,'jf.verification_status'=>null])
            ->count();
            $insuff = DB::table('jaf_form_data as jf')
            ->join('users as u','u.id','=','jf.candidate_id')
            ->where(['jf.service_id'=> $value->id, 'jf.business_id'=>Auth::user()->business_id,'jf.is_insufficiency'=>'1'])
            ->count();

            $checkResults[] = ['check_id'=> $value->id,'check_name'=> $value->name, 'completed'=>$completed, 'pending'=> $pending,'insuff'=>$insuff]; 
                
        }
       
        $items =    $query->get(); 

        return view('clients.checks.index',compact('items','checkResults'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
