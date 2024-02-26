<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
Use DB;

class ReportMISController extends Controller
{
   

  public function report_mis(Request $request)
  {
     $business_id = Auth::user()->business_id;

    	$qcs_data = DB::table('jobs as j')
              ->select('j.id','j.title','j.total_candidates','j.created_at','j.created_by','j.status','j.sla_id')
              ->join('customer_sla as s','s.id','=','j.sla_id')
              ->where(['j.parent_id'=>$business_id])
              ->paginate(15);    

              return view('admin.qcs.report_mis', compact('qcs_data'))
              ->with('i', ($request->input('page', 1) - 1) * 5);;
  }

  //show the the list
  public function show(Request $request){
    
      $job_id = $request->id;

      $business_id = Auth::user()->business_id;

      $candidate_details = Db::table('users as c')
                         ->select('c.id','j.title','c.business_id','c.name','c.email','c.phone','itm.created_at')  
                         ->leftjoin('job_items as itm','c.id','=','itm.candidate_id')
                         ->leftjoin('jobs AS j','itm.job_id','=','j.id')
                         ->where(['itm.job_id'=>$job_id]) 
                         ->get(); 

                 return view('admin.qcs.ajax_job_details', compact('candidate_details'));
  }

  // candidte qc form data
  public function confirmationQc($id)
  {     
      $candidate = Db::table('users as c')
                      ->select('c.id','c.first_name','c.last_name','c.phone','add.full_address','add.address_line1','add.address_line2','add.period_stay_from','add.period_stay_to','add.address_type','add.selfi_photo','add.address_proof_photo_1','add.address_type')
                      ->leftjoin('address_verifications AS add','c.id','=','add.candidate_id')
                      ->where(['c.id'=>$id])
                      ->first();

     // dd($candidate);

    	return view('admin.qcs.confirmationQc',compact('candidate'));    	
  } 


  public function store(Request $request){

    $business_id = Auth::user()->business_id;
      $data = 
      [
        'business_id'=>$business_id,
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
