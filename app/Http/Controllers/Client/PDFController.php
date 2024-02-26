<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\CourtCheckMasterV1;
use App\Models\CourtCheckV1;
class PDFController extends Controller
{
    /**
     * Export PDF full Report
     *
     * @return \Illuminate\Http\Response
     */
    public function exportsFullReport(Request $request)
    {
        $user_id=Auth::user()->id;

        $business_id = Auth::user()->business_id;

        $parent_id = Auth::user()->parent_id;

        $report_id = $request->segment(5);
        
        $report_type = $request->segment(6);

        $pdf =new PDF;
        // echo $report_id; die('tested');
        $data = [];
        //get report items
        $report_id = base64_decode($report_id);

        $report_items = DB::table('report_items as ri')
        ->select('ri.*','s.name as service_name','s.id as service_id','s.type_name')  
        ->join('services as s','s.id','=','ri.service_id')
        ->where(['ri.report_id'=>$report_id,'is_report_output'=>'1']) 
        ->orderBy('s.sort_number','asc')
        ->orderBy('ri.service_item_order','asc')
        ->get(); 

        $path = public_path().'/uploads/report-data/'.$user_id.'/';
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        if (File::exists($path)) 
        {
            File::cleanDirectory($path);
        }

        // get candidate_id
        $report_data = DB::table('reports')->select('candidate_id','verifier_name','verifier_email','verifier_designation','generated_at','created_at','is_manual_mark')->where(['id'=>$report_id])->first(); 

        $candidate =  DB::table('candidate_reinitiates as u')
                       ->select('u.id','u.display_id','u.business_id','u.client_emp_code','u.entity_code','u.first_name','u.last_name','u.name','u.email','u.phone','r.id as report_id','r.created_at','r.approval_status_id','r.sla_id','cs.title as sla_name','u.name','r.status as report_status','r.is_report_complete','r.report_complete_created_at','r.is_manual_mark','r.revised_date')  
                       ->leftjoin('reports as r','r.candidate_id','=','u.id')
                       ->join('customer_sla as cs','cs.id','=','r.sla_id')
                       ->where(['r.id'=>$report_id]) 
                       ->first();

        $jaf = DB::table('jaf_form_data')->select('form_data_all')->where(['candidate_id'=>$report_data->candidate_id])->first(); 

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

                        if($report_type!=null && $report_type!='')
                        {
                            if(stripos($report_type,'Interim')!==false)
                            {
                                $status = 'Interim Report';
                            }
                            if(stripos($report_type,'Supplementary')!==false)
                            {
                                $status = 'Supplementary Report';
                            }
                            else if(stripos($report_type,'Final')!==false)
                            {
                                $status = 'Final Report';
                            }
                        }
                        else
                        {
                            if(stripos($candidate->report_status,'interim')!==false)
                            {
                                $status = 'Interim Report';
                            }
                            else if(stripos($candidate->report_status,'completed')!==false)
                            {
                                $status = 'Final Report';
                            }
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
        
        $pdf = PDF::loadView('clients.candidates.pdf.report', compact('data','report_items','report_data','data','jaf','candidate','parent_id','business_id','user_id'),[],[
            'title' => 'Report',
            'margin_top' => 20,
            'margin-header'=>20,
            'margin_bottom' =>25,
            'margin_footer'=>5,
            
          ] ); 
         

        return $pdf->download($file_name);

    }

    /**
     * Export PDF full Report
     *
     * @return \Illuminate\Http\Response
     */
    public function previewReport(Request $request)
    {
        $user_id=Auth::user()->id;

        $business_id = Auth::user()->business_id;

        $parent_id = Auth::user()->parent_id;

        $report = $request->id; 
        // dd($report_id);
        $pdf =new PDF;
        // echo $report_id; die('tested');
        $data = [];
        //get report items
        $report_id = base64_decode($report);
        // dd($report_id);
        $report_items = DB::table('report_items as ri')
        ->select('ri.*','s.name as service_name','s.id as service_id','s.type_name')  
        ->join('services as s','s.id','=','ri.service_id')
        ->where(['ri.report_id'=>$report_id,'is_report_output'=>'1']) 
        ->orderBy('s.sort_number','asc')
        ->orderBy('ri.service_item_order','asc')
        ->get(); 

        // get candidate_id
        $report_data = DB::table('reports')->select('candidate_id','verifier_name','verifier_email','verifier_designation','generated_at','created_at','is_manual_mark')->where(['id'=>$report_id])->first(); 
        // dd($report_data);
        $candidate =    DB::table('candidate_reinitiates as u')
                       ->select('u.id','u.display_id','u.business_id','u.client_emp_code','u.entity_code','u.first_name','u.last_name','u.name','u.email','u.phone','r.id as report_id','r.created_at','r.approval_status_id','r.sla_id','cs.title as sla_name','u.name','r.status as report_status','r.is_report_complete','r.report_complete_created_at','r.is_manual_mark','r.revised_date')  
                       ->leftjoin('reports as r','r.candidate_id','=','u.id')
                       ->join('customer_sla as cs','cs.id','=','r.sla_id')
                       ->where(['r.id'=>$report_id]) 
                       ->first();
        
        $path = public_path().'/uploads/report-data/'.$user_id.'/';

        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        if (File::exists($path)) 
        {
            File::cleanDirectory($path);
        }

        $jaf = DB::table('jaf_form_data')->select('form_data_all')->where(['candidate_id'=>$report_data->candidate_id])->first(); 

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
        
        $pdf = PDF::loadView('clients.candidates.pdf.report', compact('data','report_items','report_data','jaf','candidate','parent_id','business_id','user_id'),[],[
            'title' => 'Report',
            'margin_top' => 20,
            'margin-header'=>20,
            'margin_bottom' =>25,
            'margin_footer'=>5,
            
          ]); 
         

        // return $pdf->stream('previw_Report.pdf');

        return $pdf->stream($file_name);

    }

     /**
     * Export PDF file - Aadhar
     *
     * @return \Illuminate\Http\Response
     */
    public function aadharExportReport($id)
    {
        
        $data = DB::table('aadhar_check_masters')->where(['id'=>$id])->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.aadhar', compact('data') );
  
        return $pdf->download('aadhar-1.pdf');
    }

     /**
     * Export PDF file - PAN
     *
     * @return \Illuminate\Http\Response
     */
    public function panExportReport($id)
    {
        
        $data = DB::table('pan_check_masters')->where(['id'=>$id])->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.pan', compact('data') );
  
        return $pdf->download('pan-1.pdf');
    }

    /**
     * Export PDF file - Voter ID
     *
     * @return \Illuminate\Http\Response
     */
    public function voterIDExportReport($id)
    {
        
        $data = DB::table('voter_id_check_masters')->where(['id'=>$id])->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.voter-id', compact('data') );
  
        return $pdf->download('bcd-voter-1.pdf');
    }

    /**
     * Export PDF file - RC
     *
     * @return \Illuminate\Http\Response
     */
    public function rcExportReport($id)
    {
        
        $data = DB::table('rc_check_masters')->where(['id'=>$id])->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.rc', compact('data') );
  
        return $pdf->download('bcd-RC-1.pdf');
    }

    /**
     * Export PDF file - DL
     *
     * @return \Illuminate\Http\Response
     */
    public function dlExportReport($id)
    {
        
        $data = DB::table('dl_check_masters')->where(['id'=>$id])->first();

        $pdf = PDF::loadView('admin.verifications.pdf.dl', compact('data') );
  
        return $pdf->download('bcd-dl.pdf');
    }

    /**
     * Export PDF file - Passport
     *
     * @return \Illuminate\Http\Response
     */
    public function passportExportReport($id)
    {
        
        $data = DB::table('passport_check_masters')->where(['id'=>$id])->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.passport', compact('data') );
  
        return $pdf->download('bcd-passport.pdf');
    }

    /**
     * Export PDF file - GSTIN
     *
     * @return \Illuminate\Http\Response
     */
    public function gstinExportReport($id)
    {
        
        $data = DB::table('gst_check_masters')->where(['id'=>$id])->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.gstin', compact('data') );
  
        return $pdf->download('bcd-gst.pdf');
    }

    /**
     * Export PDF file - Bannk
     *
     * @return \Illuminate\Http\Response
     */
    public function bankExportReport($id)
    {
        
        $data = DB::table('bank_account_check_masters')->where(['id'=>$id])->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.bank-verification', compact('data') );
  
        return $pdf->download('bcd-bank-account-verification.pdf');
    }

    /**
     * Export PDF file - Aadhar
     *
     * @return \Illuminate\Http\Response
     */
    public function advanceAadharExportReport($id)
    {
        
        $data = DB::table('aadhar_check_v2s')->where(['id'=>$id])->first();
        // dd($data);
        $pdf = PDF::loadView('admin.verifications.pdf.v2_aadhar_report', compact('data') );
  
        return $pdf->download('advanceAadhar-1.pdf');

        // return view('admin.verifications.pdf.v2_aadhar_report', compact('data'));
    }

    /**
     * Export PDF file - Telecom
     *
     * @return \Illuminate\Http\Response
     */
    public function telecomExportReport($id)
    {
        
        $data = DB::table('telecom_check_master')->where(['id'=>$id])->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.telecom', compact('data') );
  
        return $pdf->download('telecom-1.pdf');
    }

    /**
     * Export PDF file - Ecourt
     *
     * @return \Illuminate\Http\Response
     */
    public function ecourtExportReport($id)
    {
        
        $master_data = DB::table('e_court_check_masters')->where(['id'=>$id])->latest()->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.e-court', compact('master_data') );
  
        return $pdf->download('bcd-e-court-verification.pdf');
    }

    /**
     * Export PDF file - UPI
     *
     * @return \Illuminate\Http\Response
     */
    public function upiExportReport($id)
    {
        
        $data = DB::table('upi_check_masters')->where(['id'=>$id])->latest()->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.upi', compact('data') );
  
        return $pdf->download('bcd-upi-verification.pdf');
    }

    /**
     * Export PDF file - CIN
     *
     * @return \Illuminate\Http\Response
     */
    public function cinExportReport($id)
    {
        
        $master_data = DB::table('cin_check_masters')->where(['id'=>$id])->latest()->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.cin', compact('master_data') );
  
        return $pdf->download('bcd-cin-verification.pdf');
    }

     /**
     * Export PDF file - Court Check V1
     *
     * @return \Illuminate\Http\Response
     */
    public function courtCheckV1ExportReport($id)
    {
        
        $master_data = CourtCheckMasterV1::from('court_check_masters_v1')->where(['id'=>$id])->latest()->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.court_check_v1', compact('master_data'));
  
        return $pdf->download('bcd-court-check-v1-verification.pdf');
    }

    /**
     * Export PDF file - SLA
     *
     * @return \Illuminate\Http\Response
     */
    public function PDFgenerate($id)
    {
        // $user_id = Auth::user()->id;
        $file_name='';
        $candidate_id=base64_decode($id);
        // dd($candidate_id);

        $candidate = DB::table('candidate_reinitiates as u')
         ->select('u.id','u.business_id','u.parent_id','u.client_emp_code','u.entity_code','u.first_name','u.last_name','u.name','u.email','u.phone','u.dob','u.aadhar_number','u.father_name','u.gender','u.digital_signature','u.display_id','u.name')  
         ->where(['u.id'=>$candidate_id]) 
         ->first(); 
        // $sla_data = DB::table('customer_sla')->where(['id'=>$id])->first();
        
        // $sla_service_items = DB::table('customer_sla_items as cs')
        //                         ->select('cs.id','cs.service_id')
        //                         ->where(['cs.sla_id'=>$id])
        //                         ->get();

        $jaf_items = DB::table('jaf_form_data as jf')
        ->select('jf.id','jf.form_data_all','jf.form_data','jf.check_item_number','jf.address_type','jf.insufficiency_notes','jf.is_insufficiency','jf.is_api_checked','jf.verification_status','jf.verified_at','s.name as service_name','s.id as service_id','s.verification_type','s.type_name')
        ->join('services as s','s.id','=','jf.service_id')
        ->where(['jf.candidate_id'=>$candidate_id])
        ->get();
        // dd($jaf_items);
        // echo '<pre>';print_r($jaf_items);
        // die;
        // $sla_items = DB::select("SELECT sla_id, GROUP_CONCAT(DISTINCT service_id) AS alot_services FROM `job_sla_items` WHERE candidate_id = $candidate_id");

        $pdf = PDF::loadView('clients.candidates.pdf.pdf-jaf', compact('candidate','jaf_items'));

        $file_name='jaf-'.$candidate->name.'-'.$candidate->display_id.'.pdf';
  
        return $pdf->download($file_name);
    }

    public function billingDetailsPDF(Request $request,$id)
    {
        $file_name='';
        $business_id=Auth::user()->business_id;

        $parent_id=Auth::user()->parent_id;

        $customers=DB::table('users as u')
                ->select('u.*','ub.company_name','ub.gst_number','ub.tin_number','ub.address_line1 as business_address','ub.city_name','ub.state_name','ub.country_name','ub.zipcode','ub.phone as business_phone','ub.website','ub.email as business_email','ub.hsn_or_sac','ub.company_short_name','ub.bank_name','ub.account_number','ub.ifsc_code')
                ->join('user_businesses as ub','ub.business_id','=','u.id')
                ->where('u.id',$parent_id)
                ->first();

        $billing_id=base64_decode($id);

        $bill=DB::table('billings')->where('id',$billing_id)->first();

        $billing_detail_candidate=DB::table('billing_items as bi')
                                        ->DISTINCT('bi.candidate_id')
                                        ->select('bi.candidate_id','bi.business_id',DB::raw('sum(bi.quantity) as total_quantity'),DB::raw('sum(bi.additional_charges) as total_additional_charges'),DB::raw('sum(bi.final_total_check_price) as total_check_price'))
                                        ->groupBy('bi.candidate_id')
                                        ->where(['bi.billing_id'=>$billing_id])
                                        ->whereNotNull('bi.candidate_id');

        $billing_detail_candidate=$billing_detail_candidate->get();

        $billing_detail_api = DB::table('billing_items as bi')
                                ->select('bi.business_id',DB::raw('sum(bi.quantity) as total_quantity'),DB::raw('sum(bi.additional_charges) as total_additional_charges'),DB::raw('sum(bi.final_total_check_price) as total_check_price'))
                                ->groupBy('bi.candidate_id')
                                ->where(['bi.billing_id'=>$billing_id])
                                ->whereNull('bi.candidate_id');
        
        $billing_detail_api=$billing_detail_api->get();

        // dd($billing_detail_api);

        // $items=DB::table('billing_items as bi')
        //     ->select('bi.*','s.verification_type')
        //     ->join('services as s','s.id','=','bi.service_id')
        //     ->where(['bi.billing_id'=>$billing_id])
        //     ->orderBy('bi.service_id','asc')
        //     ->get();

        $pdf = PDF::loadView('clients.billing.pdf.invoice', compact('billing_detail_candidate','billing_detail_api','bill','customers') );

        $file_name=$bill->invoice_id.date('Ymdhis').'.pdf';

        // return $pdf->stream($file_name);

        return $pdf->download($file_name);

        // return view('clients.billing.pdf.invoice',compact('items','bill','customers'));
    }

    public function billingPreviewPDF(Request $request,$id)
    {
        $file_name='';
        $business_id=Auth::user()->business_id;

        $parent_id=Auth::user()->parent_id;

        $customers=DB::table('users as u')
                ->select('u.*','ub.company_name','ub.gst_number','ub.tin_number','ub.address_line1 as business_address','ub.city_name','ub.state_name','ub.country_name','ub.zipcode','ub.phone as business_phone','ub.website','ub.email as business_email','ub.hsn_or_sac','ub.company_short_name','ub.bank_name','ub.account_number','ub.ifsc_code')
                ->join('user_businesses as ub','ub.business_id','=','u.id')
                ->where('u.id',$parent_id)
                ->first();

        $billing_id=base64_decode($id);

        $bill=DB::table('billings')->where('id',$billing_id)->first();

        $billing_detail_candidate=DB::table('billing_items as bi')
                                        ->DISTINCT('bi.candidate_id')
                                        ->select('bi.candidate_id','bi.business_id',DB::raw('sum(bi.quantity) as total_quantity'),DB::raw('sum(bi.additional_charges) as total_additional_charges'),DB::raw('sum(bi.final_total_check_price) as total_check_price'))
                                        ->groupBy('bi.candidate_id')
                                        ->where(['bi.billing_id'=>$billing_id])
                                        ->whereNotNull('bi.candidate_id');

        $billing_detail_candidate=$billing_detail_candidate->get();

        $billing_detail_api = DB::table('billing_items as bi')
                                ->select('bi.business_id',DB::raw('sum(bi.quantity) as total_quantity'),DB::raw('sum(bi.additional_charges) as total_additional_charges'),DB::raw('sum(bi.final_total_check_price) as total_check_price'))
                                ->groupBy('bi.candidate_id')
                                ->where(['bi.billing_id'=>$billing_id])
                                ->whereNull('bi.candidate_id');
        
        $billing_detail_api=$billing_detail_api->get();

        // dd($billing_detail_api);

        // $items=DB::table('billing_items as bi')
        //     ->select('bi.*','s.verification_type')
        //     ->join('services as s','s.id','=','bi.service_id')
        //     ->where(['bi.billing_id'=>$billing_id])
        //     ->orderBy('bi.service_id','asc')
        //     ->get();

        $pdf = PDF::loadView('clients.billing.pdf.invoice', compact('billing_detail_candidate','billing_detail_api','bill','customers') );

        $file_name=$bill->invoice_id.date('Ymdhis').'.pdf';

        return $pdf->stream($file_name);

        // return view('clients.billing.pdf.invoice',compact('items','bill','customers'));
    }
}
