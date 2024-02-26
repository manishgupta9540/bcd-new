<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PDF;

class ReportController extends Controller
{
    /**
     * Report List API
     *
     * This API is used for show the Report list based on who logs in (i.e; if the login user belongs to an Admin/COC).
     * 
     * @authenticated
     * 
     * @queryParam  login_id required Login ID for finding the user. Example: XXX
     * 
     * @queryParam  page Page Number to retrieve the record. Example: 2
     * 
     * @response 404 {"status":false,"data":null,"message":"User Not Found!!"}
     * 
     * @response 401 {"status":false,"data":null,"message":"Permission Denied !!"}
     * 
     * @responseFile responses/candidate/reportlist/success.get.json
     * 
     */
    public function index(Request $request)
    {
        $ref_no =$request->reference_number;

        if ($ref_no) {
            
            $users=DB::table('users')->where(['display_id'=>$ref_no])->whereNotIn('user_type',['candidate','guest'])->first();

            if($users!=NULL)
            {
                $business_id=$users->business_id;
                $parent_id=$users->parent_id;
                $user_d=DB::table('users')->where('id',$business_id)->first();
                if($user_d->user_type=='client')
                {
                    $data = DB::table('reports as r')
                            ->select('r.candidate_id','u.name','ub.company_name','u.display_id as reference_number','u.phone as phone_number','cl.title as sla_name','r.status','r.is_report_complete','r.created_at',DB::raw("(CASE WHEN r.is_manual_mark = 1 THEN 'green' WHEN r.is_manual_mark = 2 THEN 'grey' ELSE (CASE WHEN r.approval_status_id = 1 THEN 'red' WHEN r.approval_status_id = 2 THEN 'yellow' WHEN r.approval_status_id = 3 THEN 'orange' ELSE 'green' END) END) as color_code")) 
                            ->join('customer_sla as cl','cl.id','=','r.sla_id')
                            ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                            ->join('user_businesses as ub','r.business_id','=','ub.business_id')
                            ->where(['r.business_id' => $business_id,'r.is_report_complete'=>'1'])
                            ->where('r.status','<>','incomplete')
                            ->orderBy('r.id','desc')
                            ->paginate(10);
                    
                    $response=['status'=>true,'data'=>$data];
                }
                else
                {
                    $response=['status'=>false,'message'=> 'Permission Denied!!'];
                }
            }
            else
            {
                $response=['status'=>false,'message'=> 'Wrong credentials!!'];
            }
        } else {
            $response=['status'=>false,'message'=> 'The reference_number field is required!'];
        }

        return response()->json($response,200); 

    }

    public function exportFullReport(Request $request)
    {
        $candidates_id = $request->candidates_id;
        // Client Reference Number
        $ref_no = $request->reference_number;
        if ( $ref_no) {
            
            $users=DB::table('users')->where(['display_id'=>$ref_no])->first();
            if ($users) {
                  
                $reports=DB::table('reports')
                ->whereIn('candidate_id',$candidates_id)
                ->where('status','<>','incomplete')
                ->where('is_report_complete','1')
                ->get();

                if(count($reports)>0)
                {
                    $zipname = 'reports-'.date('Ymdhis').'.zip';
                    $zip = new \ZipArchive();  

                    $path=public_path().'/uploads/zip/';

                    if (!File::exists($path)) {
                        File::makeDirectory($path, $mode = 0777, true, true);
                    }
                    $zip->open($path.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                    $report_array=$candidate_array=[];
                    foreach($reports as $report)
                    {
                        $report_data = DB::table('reports')->select('id','candidate_id','status','verifier_name','verifier_email','verifier_designation','generated_at','created_at','is_manual_mark')->where(['id'=>$report->id])->first(); 

                        // $report_data=DB::table('reports')->where(['id'=>$report_id,'status'=>'incomplete'])->first();
                        // if($report_data!=NULL)
                        // {
                        //     return response()->json([ 
                        //       'success' => false,
                        //       'status' => 'no'
                        //     ]);      
                        // }
                        
                        if($report_data->status=='completed'|| $report_data->status=='interim'){
                            
                            $data = [];
                            $pdf =new PDF;
                            $report_items = DB::table('report_items as ri')
                            ->select('ri.*','s.name as service_name','s.id as service_id','s.type_name')  
                            ->join('services as s','s.id','=','ri.service_id')
                            ->where(['ri.report_id'=>$report->id,'is_report_output'=>'1']) 
                            ->orderBy('s.sort_number','asc')
                            ->orderBy('ri.service_item_order','asc')
                            ->get(); 

                            // get candidate_id
                            // dd($report_data);
                            $candidate = DB::table('candidate_reinitiates as u')
                                        ->select('u.id','u.display_id','u.business_id','u.client_emp_code','u.entity_code','u.first_name','u.last_name','u.name','u.email','u.phone','r.id as report_id','r.created_at','r.approval_status_id','r.sla_id','cs.title as sla_name','u.parent_id','u.gender','u.dob','u.name','r.status as report_status','r.is_report_complete','r.report_complete_created_at','r.is_manual_mark','r.revised_date')  
                                        ->leftjoin('reports as r','r.candidate_id','=','u.id')
                                        ->join('customer_sla as cs','cs.id','=','r.sla_id')
                                        ->where(['r.id'=>$report->id]) 
                                        ->first();

                            $jaf = DB::table('jaf_form_data')->where(['candidate_id'=>$report_data->candidate_id])->first(); 
                            
                            $file_name = "myBCD-Report-".date('d-m-Y').'-'.$report_data->candidate_id.".pdf";

                            // Check for Report File Renaming

                            $customer = DB::table('user_businesses')
                                                    ->where(['business_id'=>$candidate->business_id,'is_report_file_config'=>1])
                                                    ->whereNotNull('report_file_config_details')
                                                    ->first();

                            if($customer!=NULL)
                            {
                                $file_detail = $customer->report_file_config_details;

                                $file_detail_arr = json_decode($file_detail,true);

                                if($file_detail_arr!=NULL && count($file_detail_arr)>0)
                                {
                                    $file_name = '';

                                    asort($file_detail_arr);

                                    $i=0;

                                    $count = count($file_detail_arr);

                                    foreach($file_detail_arr as $key => $item)
                                    {
                                        if(stripos($key,'reference_no')!==false)
                                        {
                                            $file_name.=$candidate->display_id;
                                        }
                                        else if(stripos($key,'emp_code')!==false)
                                        {
                                            if($candidate->client_emp_code!='' && $candidate->client_emp_code!=null)
                                                $file_name.=$candidate->client_emp_code;
                                        }
                                        else if(stripos($key,'candidate_name')!==false)
                                        {
                                            $file_name.=$candidate->name;
                                        }
                                        else if(stripos($key,'status')!==false)
                                        {
                                            $status = '';

                                            if(stripos($candidate->report_status,'interim')!==false)
                                            {
                                                $status = 'Interim Report';
                                            }
                                            else if(stripos($candidate->report_status,'completed')!==false)
                                            {
                                                $status = 'Final Report';
                                            }
                                            
                                            $file_name.=$status;
                                        }
                                        else if(stripos($key,'date')!==false)
                                        {
                                            $file_name.=date('d-F-Y');
                                        }

                                        if(++$i!=$count)
                                        {
                                            $file_name.=' - ';
                                        }
                                    }

                                    $file_name.='.pdf';
                                }
                            }
                            $path=public_path().'/uploads/pdf/';
                            if (!File::exists($path)) {
                                File::makeDirectory($path, $mode = 0777, true, true);
                            }
                            $pdf = PDF::loadView('clients.candidates.pdf.report', compact('report_data','report_items','data','jaf','candidate'),[],[
                                'title' => 'Report',
                                'margin_top' => 20,
                                'margin-header'=>20,
                                'margin_bottom' =>25,
                                'margin_footer'=>5,
                                
                            ])->save(public_path()."/uploads/pdf/".$file_name); 

                            $path = public_path()."/uploads/pdf/".$file_name;
                            // return $pdf->download("report-".$candidate->id.date('d-m-Y').".pdf");
                            $zip->addFile($path, '/reports/'.basename($path));  
                            $report_array[]=$report_data->id;
                            $candidate_array[]=$report_data->candidate_id;
                        }
                    }

                    $zip->close();
                    $path=public_path().'/uploads/pdf/';
                    if (File::exists($path)) 
                    {
                        File::cleanDirectory($path);
                    }

                    $zip_id=DB::table('zip_logs')->insertGetId([
                        'parent_id' => $users->parent_id,
                        'business_id'  => $users->business_id,
                        'user_id'     =>  $users->id,
                        'report_id'   =>  count($report_array)>0?json_encode($report_array):NULL,
                        'candidate_id'  => count($candidate_array)>0?json_encode($candidate_array):NULL,
                        'zip_name' => $zipname!=""?$zipname:NULL,
                        'created_at'    =>  date('Y-m-d H:i:s'),
                        'updated_at'    =>  date('Y-m-d H:i:s')
                    ]);
            
            
                    $zip_data=DB::table('zip_logs')->where(['id'=>$zip_id])->first();
                    $download_link =  Config::get('app.user_url').'/user/downloadReportZip/'.base64_encode($zip_data->id);
                    // $file = public_path()."/zip/".$zip_data->zip_name;
                    // $headers = array('Content-Type: application/zip');
                    return response()->json([
                        'success' => true,
                        'download_link' => $download_link
                        ]);
                    // return response()->download($file, $zip_data->zip_name,$headers);

                
                    // {{Config::get('app.user_url')}}/user/downloadReportZip/{{$zip_id}}
                        //    echo url('/').'/'.$zipname;

                        // $zip_path=public_path().'/zip/'.$zipname;
                    
                }
                else
                {
                    return response()->json([
                    'success' => false,
                    'status' => 'no'
                    ]);
                } 
            } else {
                return response()->json([
                    'success' => false,
                    'status' => 'no',
                    'message' => 'Wrong credentials!!'
                    ]);
            } 
        }  
        else {
            return response()->json([
                'status'=>false, 
                'message' => 'The reference_number field is required!'
            ]);
        }  
    }

    public function exportReport(Request $request)
    {
        $candidate_id = $request->candidate_id;
        // Client Reference Number
        $ref_no = $request->reference_number;
        if ($ref_no) {
            # code...
        
            $users=DB::table('users')->where(['display_id'=>$ref_no])->first();
            if ($users) {
                $report=DB::table('reports')
                ->where('candidate_id',$candidate_id)
                ->where('is_report_complete','1')
                ->where('status','<>','incomplete')
                ->first();

                if($report)
                {
                
                    $report_array=$candidate_array=[];
                    
                
                        $report_data = DB::table('reports')->select('id','candidate_id','status','verifier_name','verifier_email','verifier_designation','generated_at','created_at','is_manual_mark',DB::raw("(CASE WHEN is_manual_mark = 1 THEN 'green' WHEN is_manual_mark = 2 THEN 'grey' ELSE (CASE WHEN approval_status_id = 1 THEN 'red' WHEN approval_status_id = 2 THEN 'yellow' WHEN approval_status_id = 3 THEN 'orange' ELSE 'green' END) END) as color_code"))->where(['id'=>$report->id])->first(); 
                    // dd($report_data);
                        // $report_data=DB::table('reports')->where(['id'=>$report_id,'status'=>'incomplete'])->first();
                        // if($report_data!=NULL)
                        // {
                        //     return response()->json([ 
                        //       'success' => false,
                        //       'status' => 'no'
                        //     ]);      
                        // }
                        
                        if($report_data->status=='completed'|| $report_data->status=='interim'){
                            
                            $data = [];
                            $pdf =new PDF;
                            $report_items = DB::table('report_items as ri')
                            ->select('ri.*','s.name as service_name','s.id as service_id','s.type_name')  
                            ->join('services as s','s.id','=','ri.service_id')
                            ->where(['ri.report_id'=>$report->id,'is_report_output'=>'1']) 
                            ->orderBy('s.sort_number','asc')
                            ->orderBy('ri.service_item_order','asc')
                            ->get(); 

                            // get candidate_id
                           
                            // var_dump($report_items); die;
                            $candidate = DB::table('candidate_reinitiates as u')
                                        ->select('u.id','u.display_id','u.business_id','u.client_emp_code','u.entity_code','u.first_name','u.last_name','u.name','u.email','u.phone','r.id as report_id','r.created_at','r.approval_status_id','r.sla_id','cs.title as sla_name','u.parent_id','u.gender','u.dob','u.name','r.status as report_status','r.is_report_complete','r.report_complete_created_at','r.is_manual_mark','r.revised_date')  
                                        ->leftjoin('reports as r','r.candidate_id','=','u.id')
                                        ->join('customer_sla as cs','cs.id','=','r.sla_id')
                                        ->where(['r.id'=>$report->id]) 
                                        ->first();

                            $jaf = DB::table('jaf_form_data')->where(['candidate_id'=>$report_data->candidate_id])->first(); 
                            
                            $file_name = "myBCD-Report-".date('d-m-Y').'-'.$report_data->candidate_id.".pdf";

                            // Check for Report File Renaming

                            $customer = DB::table('user_businesses')
                                                    ->where(['business_id'=>$candidate->business_id,'is_report_file_config'=>1])
                                                    ->whereNotNull('report_file_config_details')
                                                    ->first();

                            if($customer!=NULL)
                            {
                                $file_detail = $customer->report_file_config_details;

                                $file_detail_arr = json_decode($file_detail,true);

                                if($file_detail_arr!=NULL && count($file_detail_arr)>0)
                                {
                                    $file_name = '';

                                    asort($file_detail_arr);

                                    $i=0;

                                    $count = count($file_detail_arr);

                                    foreach($file_detail_arr as $key => $item)
                                    {
                                        if(stripos($key,'reference_no')!==false)
                                        {
                                            $file_name.=$candidate->display_id;
                                        }
                                        else if(stripos($key,'emp_code')!==false)
                                        {
                                            if($candidate->client_emp_code!='' && $candidate->client_emp_code!=null)
                                                $file_name.=$candidate->client_emp_code;
                                        }
                                        else if(stripos($key,'candidate_name')!==false)
                                        {
                                            $file_name.=$candidate->name;
                                        }
                                        else if(stripos($key,'status')!==false)
                                        {
                                            $status = '';

                                            if(stripos($candidate->report_status,'interim')!==false)
                                            {
                                                $status = 'Interim Report';
                                            }
                                            else if(stripos($candidate->report_status,'completed')!==false)
                                            {
                                                $status = 'Final Report';
                                            }
                                            
                                            $file_name.=$status;
                                        }
                                        else if(stripos($key,'date')!==false)
                                        {
                                            $file_name.=date('d-F-Y');
                                        }

                                        if(++$i!=$count)
                                        {
                                            $file_name.=' - ';
                                        }
                                    }

                                    $file_name.='.pdf';
                                }
                            }
                            $path=public_path().'/report/link/';
                            if (!File::exists($path)) {
                                File::makeDirectory($path, $mode = 0777, true, true);
                            }
                            $pdf = PDF::loadView('clients.candidates.pdf.report', compact('report_data','report_items','data','jaf','candidate'),[],[
                                'title' => 'Report',
                                'margin_top' => 20,
                                'margin-header'=>20,
                                'margin_bottom' =>25,
                                'margin_footer'=>5,
                                
                            ])->save(public_path()."/report/link/".$file_name); 

                            $path = public_path()."/report/link/".$file_name;
                            // return $pdf->download("report-".$candidate->id.date('d-m-Y').".pdf");
                        
                            $report_array=$report_data->id;
                            $candidate_array=$report_data->candidate_id;
                        
                        }

                    
                    $path=public_path().'/report/link/';

                    $download_link  = url('/').'/report/link/'.$file_name;
                
                        // dd($report_data->status);
                
                    // $download_link =  Config::get('app.user_url').'/user/downloadReportZip/'.base64_encode($zip_data->id);
                    // $file = public_path()."/zip/".$zip_data->zip_name;
                    // $headers = array('Content-Type: application/zip');
                    return response()->json([
                        'success' => true,
                        'download_link' => $download_link,
                        'report_status' => $report_data->status,
                        'colour_code'   => $report_data->color_code,
                        'generated_at'   =>$report_data->generated_at
                        ]);
                    
                }
                else
                {
                    return response()->json([
                    'success' => false,
                    'status' => 'no'
                    ]);
                } 
            } else {
                return response()->json([
                    'success' => false,
                    'status' => 'no',
                    'message' => 'Wrong credentials!!'
                    ]);
            } 
        }
        else{
            return response()->json([
                'status'=>false, 
                'message' => 'The reference_number field is required'
            ]);
        }   
    }
}
