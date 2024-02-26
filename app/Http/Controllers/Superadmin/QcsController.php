<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
Use DB;

class QcsController extends Controller
{
   
   public function report_mis(Request $request)
    {
    	$qcs_data = DB::table('jobs as j')
              ->select('j.id','j.title','j.total_candidates','j.created_at','j.created_by','j.status','s.name as verification_type')
              ->join('services as s','s.id','=','j.sla_id')
              ->paginate(5);   

              return view('superadmin.qcs.index', compact('qcs_data'))
              ->with('i', ($request->input('page', 1) - 1) * 5);;
     }


    public function show(Request $request){
      $job_id = $request->id;

      $candidate_details = Db::table('users as c')
                         ->select('c.id','j.title','c.business_id','c.name','c.email','c.phone','itm.created_at')  
                         ->leftjoin('job_items as itm','c.id','=','itm.candidate_id')
                         ->leftjoin('jobs AS j','itm.job_id','=','j.id')
                         ->where(['itm.job_id'=>$job_id]) 
                         ->get(); 
                         
                 return view('superadmin.qcs.ajax_job_details', compact('candidate_details'));
    }

    //qc form details
    public function confirmationQc($id)
    {     
      $candidate = Db::table('users as c')
                      ->select('c.id','c.name','c.phone','addrs.full_address','addrs.period_stay_from','addrs.period_stay_to','addrs.address_type','addrs.selfi_photo','addrs.address_proof_photo_1')
                      ->leftjoin('address_verifications AS addrs','c.id','=','addrs.candidate_id')
                      ->where(['c.id'=>$id])
                      ->first();
    	       return view('superadmin.qcs.confirmationQc',compact('candidate'));    	
    } 

    //
    public function store(Request $request){

      $data = 
      [
        'client_id'=>Auth::user()->id,
        'candidate_id'=>$request->input('candidate_id'),
        'qc_decision'=>$request->input('qc_decision'),
        'qc_comment'=>$request->input('qc_comment'),        
        'created_at'=> date('Y-m-d H:i:s')
      ];
      
      DB::table('qc_decision_status')->insert($data);
       return redirect()
            ->route('/report_mis')
            ->with('success', 'QC Decision Saved Successfully.');

    }
}
