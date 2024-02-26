<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\S3ConfigTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
// use Mail;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PDF;
use Illuminate\Support\Facades\File;
use App\Exports\ApiUsage\AadharExport;
use App\Exports\ApiUsage\PanExport;
use App\Exports\ApiUsage\VoteridExport;
use App\Exports\ApiUsage\RcExport;
use App\Exports\ApiUsage\DrivingExport;
use App\Exports\ApiUsage\PassportExport;
use App\Exports\ApiUsage\BankExport;
use App\Exports\ApiUsage\GstExport;
use App\Exports\ApiUsage\TelecomExport;
use App\Exports\ApiUsage\EcourtExport;
use App\Exports\ApiUsage\UPIExport;
use App\Exports\ApiUsage\CINExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Mail;
class HomeController extends Controller
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
        // $business_id = Auth::user()->business_id;
        $parent_id = Auth::user()->parent_id;
        
        return view('clients.home');
    }

    public function dashboardCardRender(Request $request)
    {
        $business_id = Auth::user()->business_id; 
        // $business_id = Auth::user()->business_id;
        $parent_id = Auth::user()->parent_id;
        // $candidates_counts  = DB::table('candidate_reinitiates')->where(['user_type'=>'candidate','business_id'=>Auth::user()->business_id ,'is_deleted'=>'0'])->get();

        //dd($business_id);
        $candidates_count = DB::table('candidate_reinitiates as u')
        ->DISTINCT('u.id')
        ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id','j.id as job_item_id')      
        ->join('job_items as j','j.candidate_id','=','u.id')            
        ->where(['u.user_type'=>'candidate','u.business_id'=>Auth::user()->business_id,'is_deleted'=>'0'])->count();
    
        $reports = DB::table('reports')->where(['business_id'=>Auth::user()->business_id])->count();

        $pending_report=DB::table('reports')->where(['business_id'=>$business_id,'status'=>'incomplete'])->count();

        $complete_report=DB::table('reports')
                        ->where(['business_id'=>$business_id])
                        ->whereIn('status',['completed','interim'])
                        ->count();
        
        $inactive_candidate =  DB::table('candidate_reinitiates as u')
        ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
        ->join('job_items as j','j.candidate_id','=','u.id')        
        ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'is_deleted'=>'0'])->whereIn('j.jaf_status',['pending','draft'])->count();

        // dd($pending);
       
        $jaf_total_filled = DB::table('candidate_reinitiates as u')
            ->DISTINCT('u.id')
            ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id','j.id as job_item_id')      
            ->join('job_items as j','j.candidate_id','=','u.id') 
            //->join('job_sla_items as jsi','jsi.candidate_id','=','j.candidate_id' )             
            ->where(['u.user_type'=>'candidate','u.business_id'=>Auth::user()->business_id,'is_deleted'=>'0','j.jaf_status'=>'filled'])->count();

            // $inactive_candidate = DB::table('candidate_reinitiates as u')
            // ->DISTINCT('u.id')
            // ->select('u.*','j.sla_id','jsi.jaf_send_to','j.jaf_status','j.job_id','j.candidate_id','jsi.jaf_send_to','j.id as job_item_id')      
            // ->join('job_items as j','j.candidate_id','=','u.id') 
            // ->join('job_sla_items as jsi','jsi.candidate_id','=','j.candidate_id' )             
            // ->where(['u.user_type'=>'candidate','u.business_id'=>Auth::user()->business_id,'is_deleted'=>'0','j.jaf_status'=>'pending'])->count();

        // $jaf_not_filled_by_customers = DB::select("SELECT u.* FROM candidate_reinitiates AS u JOIN jaf_form_data AS jfd ON jfd.candidate_id=u.id JOIN job_sla_items AS jsi ON jsi.candidate_id=u.id WHERE u.business_id=$business_id AND u.user_type='candidate' AND jsi.jaf_send_to = 'customer' AND jfd.form_data=NULL GROUP BY u.id");
        // // dd($jaf_not_filled_by_customers);
        // // var_dump($jaf_not_filled_by_customers);
        // // die;
        // $jaf_send_to_customer = count($jaf_not_filled_by_customers);
        
        // dd($jaf_send_to_customers);
        $jaf_send_to_customers = DB::table('candidate_reinitiates as u')     
        ->join('job_items as j','j.candidate_id','=','u.id')  
        ->join('job_sla_items as jsi','jsi.candidate_id','=','u.id')      
        ->where(['u.user_type'=>'candidate','u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>'0','jsi.jaf_send_to'=>'customer'])
        ->whereIn('j.jaf_status',['filled'])      
        ->groupBy('j.candidate_id')->get();

        $jaf_send_to_customer =count($jaf_send_to_customers);

        $jaf_send_to_cocs = $jaf_send_to_customers = DB::table('candidate_reinitiates as u')     
        ->join('job_items as j','j.candidate_id','=','u.id')  
        ->join('job_sla_items as jsi','jsi.candidate_id','=','u.id')      
        ->where(['u.user_type'=>'candidate','u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>'0','jsi.jaf_send_to'=>'coc'])
        ->whereIn('j.jaf_status',['filled'])            
        ->groupBy('j.candidate_id')->get();
        
        $jaf_send_to_coc = count($jaf_send_to_cocs);

        $jaf_send_to_candidates =$jaf_send_to_customers = DB::table('candidate_reinitiates as u')     
        ->join('job_items as j','j.candidate_id','=','u.id')  
        ->join('job_sla_items as jsi','jsi.candidate_id','=','u.id')      
        ->where(['u.user_type'=>'candidate','u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>'0','jsi.jaf_send_to'=>'candidate'])
        ->whereIn('j.jaf_status',['filled'])            
        ->groupBy('j.candidate_id')->get();

        $jaf_send_to_candidate = count($jaf_send_to_candidates);

        $total_checks = DB::table('candidate_reinitiates as u')
        ->select('u.*','jfd.candidate_id')      
        // ->join('job_items as j','j.candidate_id','=','u.id')  
        ->join('jaf_form_data as jfd','jfd.candidate_id','=','u.id')      
        ->where(['u.user_type'=>'candidate','u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>'0' ])->get();
  
        $total_checks=count($total_checks);

        //dd($total_checks);
       
        $completed_checks = DB::table('candidate_reinitiates as u')  
        ->select('u.*','jfd.candidate_id')  
        ->join('jaf_form_data as jfd','jfd.candidate_id','=','u.id')      
        ->where(['u.user_type'=>'candidate','u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>'0','jfd.verification_status'=>'success'])->get();

        $completed_checks = count($completed_checks);

        //dd($completed_checks);


        $insuff_checks = DB::table('candidate_reinitiates as u')
        ->select('u.*','jfd.candidate_id')      
        // ->join('job_items as j','j.candidate_id','=','u.id')  
        ->join('jaf_form_data as jfd','jfd.candidate_id','=','u.id')      
        ->where(['u.user_type'=>'candidate','u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>'0','jfd.is_insufficiency'=>'1'])->get();

        //dd($insuff_checks);

        $insuff_checks = count($insuff_checks);

        //dd($insuff_checks);

        $incompleted_checks = DB::table('candidate_reinitiates as u') 
                            ->select('u.*','jfd.candidate_id')
                            ->join('jaf_form_data as jfd','jfd.candidate_id','=','u.id')      
                            ->where(['u.user_type'=>'candidate','u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>'0'])
                            ->where(function($q){
                                $q->where('jfd.verification_status','failed')
                                ->orWhereNull('jfd.verification_status');
                            })
                            ->get();

        $incompleted_checks = count($incompleted_checks);

        //dd($incompleted_checks);

        $viewRender = view('clients.home-dashboard',compact('candidates_count','reports','pending_report','complete_report','inactive_candidate','jaf_total_filled','parent_id','business_id','jaf_send_to_customer','jaf_send_to_coc','jaf_send_to_candidate','total_checks','completed_checks','incompleted_checks','insuff_checks'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));
    }

    public function dashboardCandidateCard(Request $request)
    {
        $user_id = Auth::user()->id;

        $business_id = Auth::user()->business_id;

        $parent_id = Auth::user()->parent_id;

        $candidates_count = DB::table('candidate_reinitiates as u')
        ->DISTINCT('u.id')
        ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id','j.id as job_item_id')      
        ->join('job_items as j','j.candidate_id','=','u.id')            
        ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'is_deleted'=>'0'])->count();
        
        $inactive_candidate =  DB::table('candidate_reinitiates as u')
        ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
        ->join('job_items as j','j.candidate_id','=','u.id')        
        ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'is_deleted'=>'0'])->whereIn('j.jaf_status',['pending','draft'])->count();
       
        $jaf_total_filled = DB::table('candidate_reinitiates as u')
            ->DISTINCT('u.id')
            ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id','j.id as job_item_id')      
            ->join('job_items as j','j.candidate_id','=','u.id')            
            ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'is_deleted'=>'0','j.jaf_status'=>'filled'])->count();

        $viewRender = view('clients.dashboard.candidate-card',compact('candidates_count','inactive_candidate','jaf_total_filled'))->render();
        return response()->json(array('success' => true, 'html'=>$viewRender));
    }

    public function dashboardJafCard(Request $request)
    {
        $user_id = Auth::user()->id;

        $business_id = Auth::user()->business_id;

        $parent_id = Auth::user()->parent_id;

        $jaf_total_filled = DB::table('candidate_reinitiates as u')
                            ->DISTINCT('u.id')
                            ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id','j.id as job_item_id')      
                            ->join('job_items as j','j.candidate_id','=','u.id')             
                            ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'is_deleted'=>'0','j.jaf_status'=>'filled'])->count();

        
        $jaf_send_to_customers = DB::table('candidate_reinitiates as u')     
                                ->join('job_items as j','j.candidate_id','=','u.id')  
                                ->join('job_sla_items as jsi','jsi.candidate_id','=','u.id')      
                                ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'u.is_deleted'=>'0','jsi.jaf_send_to'=>'customer'])
                                ->whereIn('j.jaf_status',['filled'])      
                                ->groupBy('j.candidate_id')->get();

        $jaf_send_to_customer =count($jaf_send_to_customers);

        $jaf_send_to_cocs = DB::table('candidate_reinitiates as u')     
                                ->join('job_items as j','j.candidate_id','=','u.id')  
                                ->join('job_sla_items as jsi','jsi.candidate_id','=','u.id')      
                                ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'u.is_deleted'=>'0','jsi.jaf_send_to'=>'coc'])
                                ->whereIn('j.jaf_status',['filled'])            
                                ->groupBy('j.candidate_id')->get();
        
        $jaf_send_to_coc = count($jaf_send_to_cocs);

        $jaf_send_to_candidates = DB::table('candidate_reinitiates as u')     
                                        ->join('job_items as j','j.candidate_id','=','u.id')  
                                        ->join('job_sla_items as jsi','jsi.candidate_id','=','u.id')      
                                        ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'u.is_deleted'=>'0','jsi.jaf_send_to'=>'candidate'])
                                        ->whereIn('j.jaf_status',['filled'])            
                                        ->groupBy('j.candidate_id')->get();

        $jaf_send_to_candidate = count($jaf_send_to_candidates);

        $viewRender = view('clients.dashboard.jaf-card',compact('jaf_total_filled','jaf_send_to_customer','jaf_send_to_coc','jaf_send_to_candidate','parent_id','business_id'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));
    }

    public function dashboardCheckCard(Request $request)
    {
        $user_id = Auth::user()->id;

        $business_id = Auth::user()->business_id;

        $parent_id = Auth::user()->parent_id;

        $total_checks = DB::table('candidate_reinitiates as u')
        ->select('u.*','jfd.candidate_id')      
        ->join('jaf_form_data as jfd','jfd.candidate_id','=','u.id')      
        ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'u.is_deleted'=>'0' ])->get();
  
        $total_checks=count($total_checks);
       
        $completed_checks = DB::table('candidate_reinitiates as u')  
        ->select('u.*','jfd.candidate_id')  
        ->join('jaf_form_data as jfd','jfd.candidate_id','=','u.id')      
        ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'u.is_deleted'=>'0','jfd.verification_status'=>'success'])->get();

        $completed_checks = count($completed_checks);


        $insuff_checks = DB::table('candidate_reinitiates as u')
        ->select('u.*','jfd.candidate_id')      
        ->join('jaf_form_data as jfd','jfd.candidate_id','=','u.id')      
        ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'u.is_deleted'=>'0','jfd.is_insufficiency'=>'1'])->get();

        $insuff_checks = count($insuff_checks);

        $incompleted_checks = DB::table('candidate_reinitiates as u') 
                            ->select('u.*','jfd.candidate_id')
                            ->join('jaf_form_data as jfd','jfd.candidate_id','=','u.id')      
                            ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'u.is_deleted'=>'0'])
                            ->where(function($q){
                                $q->where('jfd.verification_status','failed')
                                ->orWhereNull('jfd.verification_status');
                            })
                            ->get();

        $incompleted_checks = count($incompleted_checks);

        $viewRender = view('clients.dashboard.check-card',compact('total_checks','completed_checks','incompleted_checks','insuff_checks'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));

    }

    public function dashboardReportCard(Request $request)
    {
        $user_id = Auth::user()->id;

        $business_id = Auth::user()->business_id;

        $parent_id = Auth::user()->parent_id;

        $reports = DB::table('reports')->where(['business_id'=>$business_id])->count();

        $pending_report=DB::table('reports')->where(['business_id'=>$business_id,'status'=>'incomplete'])->count();

        $complete_report=DB::table('reports')
                        ->where(['business_id'=>$business_id])
                        ->whereIn('status',['completed','interim'])
                        ->count();

        $viewRender = view('clients.dashboard.report-card',compact('reports','pending_report','complete_report'))->render();
        return response()->json(array('success' => true, 'html'=>$viewRender));
    }

    public function dashboardCheckRender(Request $request)
    {
        $business_id = Auth::user()->business_id; 
        // $business_id = Auth::user()->business_id;
        $parent_id = Auth::user()->parent_id;
        
        $services = DB::table('services')
        ->select('name','id')
        ->where(['status'=>'1'])
        ->where('business_id',NULL)
        ->whereNotIn('type_name',['e_court','gstin'])
        ->orwhere('business_id',$parent_id)
        ->orderBy('sort_number','asc')
        ->get();

        $array_result = [];

        foreach ($services as $key => $value) {
            
            $completed = DB::table('jaf_form_data as jf')
            ->DISTINCT('u.id')
            ->join('candidate_reinitiates as u','u.id','=','jf.candidate_id')
            ->where(['jf.service_id'=> $value->id, 'u.business_id'=>Auth::user()->business_id,'jf.verification_status'=>'success'])
            ->count();

            $pending = DB::table('jaf_form_data as jf')
            ->DISTINCT('u.id')
            ->join('candidate_reinitiates as u','u.id','=','jf.candidate_id')
            ->where(['jf.service_id'=> $value->id,'jf.business_id'=>Auth::user()->business_id,'jf.verification_status'=>null])
            ->count();
            $insuff = DB::table('jaf_form_data as jf')
            ->DISTINCT('u.id')
            ->join('candidate_reinitiates as u','u.id','=','jf.candidate_id')
            ->where(['jf.service_id'=> $value->id,'jf.business_id'=>Auth::user()->business_id,'jf.is_insufficiency'=>'1'])
            ->count();

            $array_result[] = ['check_id'=>$value->id,'check_name'=> $value->name, 'completed'=>$completed, 'pending'=> $pending,'insuff'=>$insuff]; 
                // 
        }

        $viewRender = view('clients.home-check-dashboard',compact('array_result'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));

    }

    /*** Show the profile data
     * ** @return \Illuminate\Http\Response*/
    
    public function profile()
    {
        $profile = User::find(Auth::user()->id);
        
        $business = DB::table('user_businesses')->where(['business_id'=>Auth::user()->id])->first();

        $action_route_count = DB::table('role_permissions')->where(['role_id'=>$profile->role,'status'=>'1','business_id'=>Auth::user()->business_id])->count();         
        $action_route = DB::table('role_permissions')->where(['role_id'=>$profile->role,'status'=>'1','business_id'=>Auth::user()->business_id])->first();        
        $permission  = DB::table('action_masters')->where(['route_group'=>'/my','status'=>'1','parent_id'=>'0'])->get();

        return view('clients.accounts.profile', compact('profile','business','action_route_count','action_route','permission'));
    } 

    public function update_profile(Request $request)
    {
        $id=Auth::user()->id;
        $this->validate($request, [
            'first_name'   => 'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'middle_name'   => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'last_name'     => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'phone'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:11',
        ]
       );
        $phone = preg_replace('/\D/', '', $request->input('phone'));

        // $name = $request->input('first_name').' '.$request->input('middle_name').' '.$request->input('last_name');

        // if($request->input('last_name')==null || $request->input('last_name')=='')
        // {
        //     $name = $request->input('first_name').' '.$request->input('middle_name');
        // }
        // elseif($request->input('middle_name')==null || $request->input('middle_name')=='')
        // {
        //     $name = $request->input('first_name').' '.$request->input('last_name');
        // }

        // if(($request->input('middle_name')==null || $request->input('middle_name')=='') && ($request->input('last_name')==null || $request->input('last_name')==''))
        // {
        //     $name = $request->input('first_name');
        // }

        $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('middle_name').' '.$request->input('last_name')));

        DB::table('users')->where('id',$id)->update([
            'first_name'    =>$request->input('first_name'),
            'middle_name'    =>$request->input('middle_name'),
            'last_name'     =>$request->input('last_name'),
            'name'          =>$name,
            'phone'         =>$phone,
            'updated_at'    => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /*** Show the business info
     * ** @return \Illuminate\Http\Response*/
    
    public function businessInfo()
    {
        $profile = User::find(Auth::user()->id);

        $business_id = Auth::user()->business_id;

        $countries = DB::table('countries')->get();

        $business = DB::table('user_businesses as b') 
        ->select('b.*')
        ->where(['b.business_id'=>$business_id])
        ->first();

        $files = DB::table('user_business_attachments')
        ->select('*')
        ->where(['business_id'=>$business_id,'is_deleted'=>0])
        ->get();

        $states  = DB::table('states')->where(['country_id'=>$business->country_id])->get();

        $cities = DB::table('cities')->where(['state_id'=>$business->state_id])->get();

        // $countries  = DB::table('countries')->get();

        return view('clients.accounts.business-info', compact('profile','business','countries','states','cities','files'));
    } 

    public function updateBusinessInfo(Request $request)
    {
        $business_id=Auth::user()->business_id;

        $is_gst_verified=0;
        $gst_attachment=NULL;

        $countries=DB::table('countries')->where(['id'=>$request->input('country')])->first();

        $states=DB::table('states')->where(['id'=>$request->input('state')])->first();

        $cities=DB::table('cities')->where(['id'=>$request->input('city')])->first();

        $rules = [ 
                    'address'   => 'required',
                    'pincode'   => 'required|integer|digits:6',
                    'city'      => 'required',
                    'state'     => 'required',
                    'company'   => 'required',
                    'business_email'            => 'required|email:rfc,dns',
                    'business_phone_number'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
                    'gst_number'                => 'required_without:gst_exempt|nullable|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/|min:15|max:15',
                    // 'contract_signed_by'        => 'required',
                    'hr_name'                   => 'required|regex:/^[a-zA-Z ]+$/u|min:2|max:255',
                    // 'work_order_date'           => 'required|date',
                    // 'work_operating_date'       => 'required|date|after:work_order_date',
                    'tin_number'    => 'nullable|integer|digits:11',
                    'gst_attachment'=>'nullable|mimes:jpg,jpeg,png,jpg,gif,svg,pdf|max:200000'
                ];

        $this->validate($request, 
            $rules,
            [
                'business_phone_number.regex' => 'Business Phone Number Must be 10-digit Number !!',
                'business_phone_number.min' => 'Business Phone Number Must be 10-digit Number !!',
                'business_phone_number.max' => 'Business Phone Number Must be 10-digit Number !!',
            ]
        );

        $business_phone = preg_replace('/\D/', '', $request->input('business_phone_number'));
        if(strlen($business_phone)!=10)
        {
            return back()->withInput()->withErrors(['business_phone_number'=>['Business Phone Number Must be 10-digit Number !!']]);
        }

        DB::beginTransaction();
        try{
            $t = DB::table('users as u')
                    ->select('u.company_logo','u.company_logo_file_platform','u.digital_signature','ub.gst_attachment','ub.gst_attachment_file_platform')
                    ->join('user_businesses as ub','u.id','=','ub.business_id')
                    ->where(['u.id'=>$business_id])
                    ->first();

            if($t!=NULL && $t->gst_attachment==NULL)
            {
                return back()->withInput()->withErrors(['gst_attachment'=>['The gst attachment field is required']]);
            }

            // Verification of GST Number
            if(!$request->has('gst_exempt') || $request->gst_exempt==NULL)
            {
                $master_data = DB::table('gst_check_masters')->select('*')->where(['gst_number'=>$request->input('gst_number')])->first();

                if($master_data !=null){
                    $is_gst_verified=1;
                }
                else
                {
                    //check from live API
                        // Setup request to send json via POST
                        $data = array(
                            'id_number'         => $request->input('gst_number'),
                            'filing_status_get' => true,
                            'async' => true
                        );
                        $payload = json_encode($data);
                        $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/corporate/gstin";

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                        curl_setopt ($ch, CURLOPT_POST, 1);
                        $authorization = "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MDcxNzI1MDIsIm5iZiI6MTYwNzE3MjUwMiwianRpIjoiZTA5YTc5MmEtMGQ5ZC00N2RjLTk1MTAtMzg4M2E3ODYxZDczIiwiZXhwIjoxOTIyNTMyNTAyLCJpZGVudGl0eSI6ImRldi50YWd3b3JsZEBhYWRoYWFyYXBpLmlvIiwiZnJlc2giOmZhbHNlLCJ0eXBlIjoiYWNjZXNzIiwidXNlcl9jbGFpbXMiOnsic2NvcGVzIjpbInJlYWQiXX19.0Ufgl7uOeTG7QVLvRR4VkRZMT06GsiGiK44jFa9-gdw"; // Prepare the authorisation token
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
                        curl_setopt($ch, CURLOPT_URL, $apiURL);
                        // Attach encoded JSON string to the POST fields
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                        $resp = curl_exec ( $ch );
                        curl_close ( $ch );
                        $array_data =  json_decode($resp,true);
                        
                        if($array_data['success'])
                        {
                            //check if ID number is new then insert into DB
                            $checkIDInDB= DB::table('gst_check_masters')->where(['gst_number'=>$request->input('gst_number')])->count();
                            if($checkIDInDB ==0)
                            {
                                $data = [
                                        'api_client_id'         =>$array_data['data']['client_id'],
                                        'gst_number'            =>$array_data['data']['gstin'],
                                        'business_name'         =>$array_data['data']['business_name'],
                                        'legal_name'            =>$array_data['data']['legal_name'],
                                        'center_jurisdiction'   =>$array_data['data']['center_jurisdiction'],
                                        'date_of_registration'  =>$array_data['data']['date_of_registration'],
                                        'constitution_of_business'=>$array_data['data']['constitution_of_business'],
                                        'field_visit_conducted'   =>$array_data['data']['field_visit_conducted'],
                                        'taxpayer_type'         =>$array_data['data']['taxpayer_type'],
                                        'gstin_status'          =>$array_data['data']['gstin_status'],
                                        'date_of_cancellation'  =>$array_data['data']['date_of_cancellation'],
                                        'address'               =>$array_data['data']['address'],
                                        'is_verified'           =>'1',
                                        'created_at'            =>date('Y-m-d H:i:s')
                                        ];

                                    DB::table('gst_check_masters')->insert($data);
                                
                                    $is_gst_verified=1;
                            }
                            
                        }else{
                            return back()->withInput()->withErrors(['gst_number'=>['It seems like GST number is not valid!']]);
                        }
                }
            }

            $s3_config = S3ConfigTrait::s3Config();

            $gst_attachment= $t->gst_attachment;
            $gst_attachment_file_platform = $t->gst_attachment_file_platform;
            if ($files = $request->file('gst_attachment')) 
            {
                $gst_attachment_file_platform = 'web';
                $destinationPath = public_path('uploads/gst-file/'); 
                $gstImage = time().'-'.$request->file('gst_attachment')->getClientOriginalName();
                $gst_attachment = $gstImage;
                if($s3_config!=NULL)
                {
                    $gst_attachment_file_platform = 's3';

                    $path = 'uploads/gst-file/';

                    if(!Storage::disk('s3')->exists($path))
                    {
                        Storage::disk('s3')->makeDirectory($path,0777, true, true);
                    }

                    Storage::disk('s3')->put($path.$gstImage, file_get_contents($files));
                }
                else
                {
                    $files->move($destinationPath, $gstImage);
                }

                
            }

            $gst_exempt=0;
            if($request->has('gst_exempt') || $request->gst_exempt!=NULL)
            {
                $gst_exempt=1;
            }

            //update business info
            $b_data = 
            [
                'company_name'  =>$request->input('company'),
                'address_line1' =>$request->input('address'),
                'zipcode'       =>$request->input('pincode'),
                'country_name'    =>$countries->name,
                'city_name'     =>$cities->name,
                'state_name'    =>$states->name,
                'country_id'    =>$request->input('country'),
                'city_id'     =>$request->input('city'),
                'state_id'    =>$request->input('state'),
                'email'         =>$request->input('business_email'),
                'phone'         =>$request->input('business_phone_number'),
                'gst_number'    =>$request->input('gst_number'),
                'gst_exempt'       => $gst_exempt,
                'gst_attachment'       => $gst_attachment,
                'gst_attachment_file_platform' => $gst_attachment_file_platform,
                'is_gst_verified'       => $is_gst_verified,
                'tin_number'    =>$request->input('tin_number'),
                'website'    =>$request->input('website'),
                'type_of_facility'    =>$request->input('type_of_facility'),
                'hr_name'       =>$request->input('hr_name'),
                // 'work_order_date'       => date('Y-m-d',strtotime($request->input('work_order_date'))),
                // 'work_operating_date'   => date('Y-m-d',strtotime($request->input('work_operating_date'))),
                // 'billing_detail'        => $request->input('billing_detail'),
                // 'contract_signed_by'    =>$request->input('contract_signed_by'),
                'updated_at'            => date('Y-m-d H:i:s')
            ];
            
            DB::table('user_businesses')->where(['business_id'=>$business_id])->update($b_data);

            DB::commit();
            return redirect()->back()->with('success', 'Business Info updated successfully.');
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 

    }

    /*** Show the business contacts
     * ** @return \Illuminate\Http\Response*/
    
    public function contactInfo()
    {
       $business_id =  Auth::user()->business_id;

        $profile = User::find(Auth::user()->id);

        $countries = DB::table('countries')->get();

        $owner = DB::table('user_business_contacts as b')
        ->select('b.*')
        ->where(['b.business_id'=>$business_id,'is_deleted'=>0,'contact_type'=>'owner'])
        ->first();

        $dealing = DB::table('user_business_contacts as b')
        ->select('b.*')
        ->where(['b.business_id'=>$business_id,'is_deleted'=>0,'contact_type'=>'dealing_officer'])
        ->first();

        $account = DB::table('user_business_contacts as b')
        ->select('b.*')
        ->where(['b.business_id'=>$business_id,'is_deleted'=>0,'contact_type'=>'account_officer'])
        ->first();

        $type = DB::table('user_business_contacts as b')
            ->select('b.*')
            ->where(['b.business_id'=>$business_id,'is_deleted'=>0])
            ->whereNotIn('contact_type',['owner','dealing_officer','account_officer'])
            ->get();

        return view('clients.accounts.contact-info', compact('profile','owner','dealing','account','type','countries'));
    }

    public function deleteContactType(Request $request)
    {
        $type_id = base64_decode($request->type_id);

        DB::beginTransaction();
        try{
            if(Auth::check()){

                DB::table('user_business_contacts')
                ->where('id', $type_id)
                ->whereNotIn('contact_type',['owner','dealing_officer','account_officer'])
                ->update(['is_deleted' => 1,'deleted_at'=>date('Y-m-d H:i:s'),'deleted_by'=>Auth::user()->id]);

                $is_deleted=TRUE;
                
                //return result 
                if($is_deleted){ 
                    DB::commit();  
                    return response()->json([
                    'status'=>'ok',
                    'message' => 'deleted',                
                    ], 200);
                }
                else{
                    return response()->json([
                    'status' =>'no',
                    ], 200);
                }
    
            }
            else{   
                return response()->json([
                    'status' =>'no',
                    ], 200);
            } 
        } 
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }         
    }

    public function updateContactInfo(Request $request)
    {
        $business_id = Auth::user()->business_id;
        // $this->validate($request, [
        //         'owner_first_name'          => 'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
        //         'owner_middle_name'          => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
        //         'owner_last_name'           => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
        //         'owner_email'               => 'required|email:rfc,dns',
        //         'owner_phone_number'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
        //         'owner_designation'         => 'required',
        //         'owner_landline_number'=>'nullable|numeric',
        //         'dealing_first_name'        => 'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
        //         'dealing_middle_name'          => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
        //         'dealing_last_name'         => 'nullable|regex:/^[a-zA-Z]+$/u|min:2|max:255',
        //         'dealing_email'             => 'required|email:rfc,dns',
        //         'dealing_phone_number'      => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
        //         'dealing_designation'       => 'required',
        //         'dealing_landline_number'   =>'nullable|numeric',
        //         'account_first_name'        => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
        //         'account_middle_name'          => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
        //         'account_last_name'         => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
        //         'account_email'             => 'nullable|email:rfc,dns',
        //         'account_phone_number'      => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
        //         'account_landline_number'=>'nullable|numeric',
        //         'type.*' => 'sometimes|required|min:1',
        //         'add_first_name.*' => 'sometimes|required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
        //         'add_middle_name.*'       => 'sometimes|nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
        //         'add_last_name.*'         => 'sometimes|nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
        //         'add_email.*'             => 'sometimes|nullable|email:rfc,dns',
        //         'add_phone.*'      => 'sometimes|nullable|regex:/^[0-9]{10}/',
        //         'add_landline_number.*'   =>'sometimes|nullable|numeric',
                
        // ],
        // [
        //     'owner_phone_number.regex' => 'Owner Phone Number Must be 10-digit Number !!',
        //     'owner_phone_number.min' => 'Owner Phone Number Must be 10-digit Number !!',
        //     'owner_phone_number.max' => 'Owner Phone Number Must be 10-digit Number !!',
        //     'dealing_phone_number.regex' => 'Dealing Phone Number Must be 10-digit Number !!',
        //     'dealing_phone_number.min' => 'Dealing Phone Number Must be 10-digit Number !!',
        //     'dealing_phone_number.max' => 'Dealing Phone Number Must be 10-digit Number !!',
        //     'account_phone_number.regex' => 'Account Phone Number Must be 10-digit Number !!',
        //     'account_phone_number.min' => 'Account Phone Number Must be 10-digit Number !!',
        //     'account_phone_number.max' => 'Account Phone Number Must be 10-digit Number !!',
        //     'type.*.required' => 'Contact Type Field is required',
        //     'type.*.min' => 'Contact Type Field has atleast 1 character',
        //     'add_first_name.*.required' => 'Additional First Name Field is required',
        //     'add_first_name.*.regex' => 'Additional First Name must be String',
        //     'add_first_name.*.min' => 'Additional First Name has atleast 1 character',
        //     'add_first_name.*.max' => 'Additional First Name has maximum 255 character allowed',
        //     'add_middle_name.*.regex' => 'Additional Middle Name must be String',
        //     'add_middle_name.*.min' => 'Additional Middle Name has atleast 1 character',
        //     'add_middle_name.*.max' => 'Additional Middle Name has maximum 255 character allowed',
        //     'add_last_name.*.required' => 'Additional Last Name Field is required',
        //     'add_last_name.*.regex' => 'Additional Last Name must be String',
        //     'add_last_name.*.min' => 'Additional Last Name has atleast 1 character',
        //     'add_last_name.*.max' => 'Additional Last Name has maximum 255 character allowed',
        //     'add_email.*.email' => 'Additional Email Must be an email address',
        //     'add_phone.*.regex' => 'Additional Phone Number Must be 10-digit Number !!',
        //     'add_landline_number.*.numeric' => 'Additional Landline Number Must be Numeric'
        // ]
        // );

        $rules= [
            'owner_first_name'          => 'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
                'owner_middle_name'          => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
                'owner_last_name'           => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
                'owner_email'               => 'required|email:rfc,dns',
                'owner_phone_number'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
                'owner_designation'         => 'required',
                'owner_landline_number'=>'nullable|numeric',
                'dealing_first_name'        => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
                'dealing_middle_name'          => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
                'dealing_last_name'         => 'nullable|regex:/^[a-zA-Z]+$/u|min:2|max:255',
                'dealing_email'             => 'nullable|email:rfc,dns',
                'dealing_phone_number'      => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
                'dealing_designation'       => 'nullable',
                'dealing_landline_number'   =>'nullable|numeric',
                'account_first_name'        => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
                'account_middle_name'          => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
                'account_last_name'         => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
                'account_email'             => 'nullable|email:rfc,dns',
                'account_phone_number'      => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
                'account_landline_number'=>'nullable|numeric',
                'type.*' => 'sometimes|required|min:1',
                'add_first_name.*' => 'sometimes|required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
                'add_middle_name.*'       => 'sometimes|nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
                'add_last_name.*'         => 'sometimes|nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
                'add_email.*'             => 'sometimes|nullable|email:rfc,dns',
                'add_phone.*'      => 'sometimes|nullable|regex:/^[0-9]{10}/',
                'add_landline_number.*'   =>'sometimes|nullable|numeric',
         ];

         $custom = [
            'owner_phone_number.regex' => 'Owner Phone Number Must be 10-digit Number !!',
            'owner_phone_number.min' => 'Owner Phone Number Must be 10-digit Number !!',
            'owner_phone_number.max' => 'Owner Phone Number Must be 10-digit Number !!',
            'dealing_phone_number.regex' => 'Dealing Phone Number Must be 10-digit Number !!',
            'dealing_phone_number.min' => 'Dealing Phone Number Must be 10-digit Number !!',
            'dealing_phone_number.max' => 'Dealing Phone Number Must be 10-digit Number !!',
            'account_phone_number.regex' => 'Account Phone Number Must be 10-digit Number !!',
            'account_phone_number.min' => 'Account Phone Number Must be 10-digit Number !!',
            'account_phone_number.max' => 'Account Phone Number Must be 10-digit Number !!',
            'type.*.required' => 'Contact Type Field is required',
            'type.*.min' => 'Contact Type Field has atleast 1 character',
            'add_first_name.*.required' => 'Additional First Name Field is required',
            'add_first_name.*.regex' => 'Additional First Name must be String',
            'add_first_name.*.min' => 'Additional First Name has atleast 1 character',
            'add_first_name.*.max' => 'Additional First Name has maximum 255 character allowed',
            'add_middle_name.*.regex' => 'Additional Middle Name must be String',
            'add_middle_name.*.min' => 'Additional Middle Name has atleast 1 character',
            'add_middle_name.*.max' => 'Additional Middle Name has maximum 255 character allowed',
            'add_last_name.*.required' => 'Additional Last Name Field is required',
            'add_last_name.*.regex' => 'Additional Last Name must be String',
            'add_last_name.*.min' => 'Additional Last Name has atleast 1 character',
            'add_last_name.*.max' => 'Additional Last Name has maximum 255 character allowed',
            'add_email.*.email' => 'Additional Email Must be an email address',
            'add_phone.*.regex' => 'Additional Phone Number Must be 10-digit Number !!',
            'add_landline_number.*.numeric' => 'Additional Landline Number Must be Numeric'
         ];

        
         $validator = Validator::make($request->all(), $rules,$custom);
          
         if ($validator->fails()){
             return response()->json([
                 'success' => false,
                 'error_type' => 'validation',
                 'errors' => $validator->errors()
             ]);
         }

        $owner_phone = preg_replace('/\D/', '', $request->input('owner_phone_number'));
        $dealing_phone = preg_replace('/\D/', '', $request->input('dealing_phone_number'));
        $account_phone = preg_replace('/\D/', '', $request->input('account_phone_number'));

        if(strlen($owner_phone)!=10)
        {
            // return back()->withInput()->withErrors(['owner_phone_number'=>['Owner Phone Number Must be 10-digit Number !!']]);

            return response()->json([
                'success' => false,
                'custom'=>'yes',
                'errors' => ['owner_phone_number'=>'Owner Phone Number must be 10-digit Number !!']
              ]);
            
        }
        else if(strlen($dealing_phone)!=10)
        {
            // return back()->withInput()->withErrors(['dealing_phone_number'=>['Dealing Phone Number Must be 10-digit Number !!']]);

            return response()->json([
                'success' => false,
                'custom'=>'yes',
                'errors' => ['dealing_phone_number'=>'Dealing Phone Number must be 10-digit Number !!']
            ]);
        }
        else if($request->input('account_phone_number') != "")
        {
            if(strlen($account_phone)!=10)
            {

                // return back()->withInput()->withErrors(['account_phone_number'=>['Account Phone Number Must be 10-digit Number !!']]);
                return response()->json([
                    'success' => false,
                    'custom'=>'yes',
                    'errors' => ['account_phone_number'=>'Account Phone Number must be 10-digit Number !!']
                  ]);
            }
        }

        DB::beginTransaction();
        try{
            //contact info
            //owner contact
            $b_data = 
            [
                'designation'   =>$request->input('owner_designation'),
                'first_name'    =>$request->input('owner_first_name'),
                'middle_name'    =>$request->input('owner_middle_name'),
                'last_name'     =>$request->input('owner_last_name'),
                'email'         =>$request->input('owner_email'),
                'phone'         =>$owner_phone,
                'landline_number'=>$request->input('owner_landline_number'),
                'updated_at'    => date('Y-m-d H:i:s')
            ];
            
            DB::table('user_business_contacts')->where(['business_id'=>$business_id,'contact_type'=>'owner'])->update($b_data);
            //dealing officer
            $b_data = 
            [
                'designation'   =>$request->input('dealing_designation'),
                'first_name'    =>$request->input('dealing_first_name'),
                'middle_name'    =>$request->input('dealing_middle_name'),
                'last_name'     =>$request->input('dealing_last_name'),
                'email'         =>$request->input('dealing_email'),
                'phone'         =>$dealing_phone,
                'landline_number'=>$request->input('dealing_landline_number'),
                'updated_at'    => date('Y-m-d H:i:s')
            ];
            
            DB::table('user_business_contacts')->where(['business_id'=>$business_id,'contact_type'=>'dealing_officer'])->update($b_data);

            //account officer
            $account_details=DB::table('user_business_contacts')->where(['business_id'=>$business_id,'contact_type'=>'account_officer'])->first();
            if($account_details!=NULL)
            {
                $b_data = 
                [
                    'designation'   =>$request->input('account_designation'),
                    'first_name'    =>$request->input('account_first_name'),
                    'middle_name'    =>$request->input('account_middle_name'),
                    'last_name'     =>$request->input('account_last_name'),
                    'email'         =>$request->input('account_email'),
                    'phone'         =>$account_phone,
                    'landline_number'=>$request->input('account_landline_number'),
                    'updated_at'     => date('Y-m-d H:i:s')
                ];
            
                
                DB::table('user_business_contacts')->where(['business_id'=>$business_id,'contact_type'=>'account_officer'])->update($b_data);
            }
            else
            {
                
                $b_data = 
                [
                    'business_id'   =>$business_id,
                    'contact_type'  =>'account_officer',
                    'designation'   =>$request->account_designation,
                    'first_name'    =>$request->account_first_name,
                    'middle_name'    =>$request->account_middle_name,
                    'last_name'     =>$request->account_last_name,
                    'email'         =>$request->account_email,
                    'phone'         =>$account_phone,
                    'phone_code'            => $request->primary_phone_code5,
                    'phone_iso'             => $request->primary_phone_iso5,
                    'landline_number'=>$request->account_landline_number,
                    'created_at'     => date('Y-m-d H:i:s')
                ];

                DB::table('user_business_contacts')->insertGetId($b_data);

            }

            $i=0;
            if(isset($request->input('type')[$i]))
            {
                foreach ($request->input('type') as $value) 
                {

                    if(isset($request->input('type_id')[$i]))
                    {

                        $type_id = $request->input('type_id')[$i];
                        $b_data = 
                        [
                            'business_id'   =>$business_id,
                            'contact_type'  =>$request->input('type')[$i],
                            'designation'   =>$request->input('add_designation')[$i],
                            'first_name'    =>$request->input('add_first_name')[$i],
                            'middle_name'    =>$request->input('add_middle_name')[$i],
                            'last_name'     =>$request->input('add_last_name')[$i],
                            'email'         =>$request->input('add_email')[$i],
                            'phone'         =>$request->input('add_phone')[$i],
                            'landline_number'=>$request->input('add_landline_number')[$i],
                            'updated_at'     => date('Y-m-d H:i:s')
                        ];

                        DB::table('user_business_contacts')->where(['id'=>$type_id])->whereNotIn('contact_type',['owner','dealing_officer','account_officer'])->update($b_data);
                    }
                    else
                    {
                        $b_data = 
                        [
                            'business_id'   =>$business_id,
                            'contact_type'  =>$request->input('type')[$i],
                            'designation'   =>$request->input('add_designation')[$i],
                            'first_name'    =>$request->input('add_first_name')[$i],
                            'middle_name'    =>$request->input('add_middle_name')[$i],
                            'last_name'     =>$request->input('add_last_name')[$i],
                            'email'         =>$request->input('add_email')[$i],
                            'phone'         =>$request->input('add_phone')[$i],
                            'landline_number'=>$request->input('add_landline_number')[$i],
                            'updated_at'     => date('Y-m-d H:i:s')
                        ];

                        DB::table('user_business_contacts')->insert($b_data);

                    }   
                
                    $i++;

                }
            }

            DB::commit();
            return response()->json([
                'success' =>true,
            ]);
            // return redirect()->back()->with('success', 'Contact Info updated successfully.');
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 


    }

    
    /*** Show the business contacts of Vendor('i.e; Admin')
     * ** @return \Illuminate\Http\Response*/
    
    public function vendorInfo()
    {
        $parent_id =Auth::user()->parent_id;
       $business_id =  Auth::user()->business_id;

        $profile = User::find(Auth::user()->id);

        $countries = DB::table('countries')->get();

        $owner = DB::table('user_business_contacts as b')
        ->select('b.*')
        ->where(['b.business_id'=>$parent_id,'contact_type'=>'owner'])
        ->first();

        $dealing = DB::table('user_business_contacts as b')
        ->select('b.*')
        ->where(['b.business_id'=>$parent_id,'contact_type'=>'dealing_officer'])
        ->first();

        $account = DB::table('user_business_contacts as b')
        ->select('b.*')
        ->where(['b.business_id'=>$parent_id,'contact_type'=>'account_officer'])
        ->first();

        return view('clients.accounts.vendor-info', compact('profile','owner','dealing','account','countries'));
    }

    
    
    public function userAPI(Request $request)
    {
        // dd(Auth::user()->id);
        $user_id=Auth::user()->id;
        $business_id=Auth::user()->business_id;

        // $services=DB::table('services')
        //         ->where(['verification_type'=>'Auto','status'=>'1'])
        //         ->pluck('name');
        // dd($services);

        $aadhar=DB::table('aadhar_checks as a')
        ->select('s.id as service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_reference'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        $pan=DB::table('pan_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        $voter_id=DB::table('voter_id_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_reference'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();
        
        $rc=DB::table('rc_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        $dl=DB::table('dl_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        $passport=DB::table('passport_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        $bank=DB::table('bank_account_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        $gst=DB::table('gst_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        $telecom=DB::table('telecom_check as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        $e_court=DB::table('e_court_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_type'=>'API','a.user_type'=>'coc','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        $upi=DB::table('upi_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_type'=>'API','a.user_type'=>'coc','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        $cin=DB::table('cin_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_type'=>'API','a.user_type'=>'coc','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        $items = $aadhar->merge($pan)
                    ->merge($voter_id)
                    ->merge($rc)
                    ->merge($dl)
                    ->merge($passport)
                    ->merge($bank)
                    ->merge($gst)
                    ->merge($telecom)
                    ->merge($e_court)
                    ->merge($upi)
                    ->merge($cin)
                    // ->sortBy('service_id')
                    ->paginate(5);
        // dd($items);

        if($request->ajax())
            return view('clients.accounts.api.user-api-ajax',compact('items'));
        else
            return view('clients.accounts.api.user-api',compact('items'));
            
        // dd($voter_id);
        //return view('clients.accounts.api.user-api',compact('aadhar','pan','voter_id','rc','dl','passport','bank','gst','telecom','e_court','upi','cin'));
    }

    public function apiDetails(Request $request,$id)
    {
        $service_id=base64_decode($id);
        $business_id=Auth::user()->business_id;
        $service_d=DB::table('services')->select('name','id','type_name')->where(['id'=>$service_id])->first();
        $data=NULL;
        if($service_id=='2')
        {
            $data=DB::table('aadhar_checks as a')
            ->select('s.name','a.aadhar_number','a.user_id','a.created_at','a.price')
            ->join('services as s','s.id','=','a.service_id')
            ->where(['source_reference'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
            ->orderBy('a.id','desc');
            if($request->get('from_date') !=""){
                $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
            }
              if($request->get('to_date') !=""){
                $data->whereDate('a.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
            }
            if($request->get('date') !=""){
                $type=base64_decode($request->get('date'));
                $today_date=date('Y-m-d');
                $prev_date=date('Y-m-d',strtotime($today_date.'- 7 days'));

                $data->whereDate('a.created_at','>=',$prev_date);
                $data->whereDate('a.created_at','<=',$today_date);
            }
            $data=$data->paginate(10);
        }
        elseif($service_id=='3')
        {
            $data=DB::table('pan_checks as a')
                ->select('s.name','a.pan_number','a.user_id','a.created_at','a.price','a.full_name','s.id')
                ->join('services as s','s.id','=','a.service_id')
                ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
                ->orderBy('a.id','desc');
                if($request->get('from_date') !=""){
                    $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                }
                  if($request->get('to_date') !=""){
                    $data->whereDate('a.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                }
                if($request->get('date') !=""){
                    $type=base64_decode($request->get('date'));
                    $today_date=date('Y-m-d');
                    $prev_date=date('Y-m-d',strtotime($today_date.'- 7 days'));
    
                    $data->whereDate('a.created_at','>=',$prev_date);
                    $data->whereDate('a.created_at','<=',$today_date);
                }
                $data=$data->paginate(10);
        }
        elseif($service_id=='4')
        {
            $data=DB::table('voter_id_checks as a')
                    ->select('s.name','a.voter_id_number','a.user_id','a.created_at','a.price','a.full_name')
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['source_reference'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
                    ->orderBy('a.id','desc');
                    if($request->get('from_date') !=""){
                        $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                    }
                      if($request->get('to_date') !=""){
                        $data->whereDate('a.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                    }
                    if($request->get('date') !=""){
                        $type=base64_decode($request->get('date'));
                        $today_date=date('Y-m-d');
                        $prev_date=date('Y-m-d',strtotime($today_date.'- 7 days'));
        
                        $data->whereDate('a.created_at','>=',$prev_date);
                        $data->whereDate('a.created_at','<=',$today_date);
                    }
                    $data=$data->paginate(10);
        }
        elseif($service_id=='7')
        {
            $data=DB::table('rc_checks as a')
                ->select('s.name','a.rc_number','a.user_id','a.created_at','a.price','a.owner_name')
                ->join('services as s','s.id','=','a.service_id')
                ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
                ->orderBy('a.id','desc');
                if($request->get('from_date') !=""){
                    $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                }
                  if($request->get('to_date') !=""){
                    $data->whereDate('a.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                }
                if($request->get('date') !=""){
                    $type=base64_decode($request->get('date'));
                    $today_date=date('Y-m-d');
                    $prev_date=date('Y-m-d',strtotime($today_date.'- 7 days'));
    
                    $data->whereDate('a.created_at','>=',$prev_date);
                    $data->whereDate('a.created_at','<=',$today_date);
                }
                $data=$data->paginate(10);
        }
        elseif($service_id=='9')
        {
            $data=DB::table('dl_checks as a')
                    ->select('s.name','a.dl_number','a.user_id','a.created_at','a.price','a.name')
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
                    ->orderBy('a.id','desc');
                    if($request->get('from_date') !=""){
                        $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                    }
                      if($request->get('to_date') !=""){
                        $data->whereDate('a.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                    }
                    if($request->get('date') !=""){
                        $type=base64_decode($request->get('date'));
                        $today_date=date('Y-m-d');
                        $prev_date=date('Y-m-d',strtotime($today_date.'- 7 days'));
        
                        $data->whereDate('a.created_at','>=',$prev_date);
                        $data->whereDate('a.created_at','<=',$today_date);
                    }
                    $data=$data->paginate(10);
        }
        elseif($service_id=='8')
        {
            $data=DB::table('passport_checks as a')
                    ->select('s.name','a.passport_number','a.user_id','a.created_at','a.price','a.full_name')
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
                    ->orderBy('a.id','desc');
                    if($request->get('from_date') !=""){
                        $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                    }
                      if($request->get('to_date') !=""){
                        $data->whereDate('a.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                    }
                    if($request->get('date') !=""){
                        $type=base64_decode($request->get('date'));
                        $today_date=date('Y-m-d');
                        $prev_date=date('Y-m-d',strtotime($today_date.'- 7 days'));
        
                        $data->whereDate('a.created_at','>=',$prev_date);
                        $data->whereDate('a.created_at','<=',$today_date);
                    }
                    $data=$data->paginate(10);
        }
        elseif($service_id=='12')
        {
            $data=DB::table('bank_account_checks as a')
                    ->select('s.name','a.account_number','a.user_id','a.created_at','a.price','a.full_name')
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
                    ->orderBy('a.id','desc');
                    if($request->get('from_date') !=""){
                        $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                    }
                      if($request->get('to_date') !=""){
                        $data->whereDate('a.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                    }
                    if($request->get('date') !=""){
                        $type=base64_decode($request->get('date'));
                        $today_date=date('Y-m-d');
                        $prev_date=date('Y-m-d',strtotime($today_date.'- 7 days'));
        
                        $data->whereDate('a.created_at','>=',$prev_date);
                        $data->whereDate('a.created_at','<=',$today_date);
                    }
                    $data=$data->paginate(10);

        }
        elseif($service_id=='14')
        {
            $data=DB::table('gst_checks as a')
                ->select('s.name','a.gst_number','a.user_id','a.created_at','a.price','a.legal_name')
                ->join('services as s','s.id','=','a.service_id')
                ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
                ->orderBy('a.id','desc');

                if($request->get('from_date') !=""){
                    $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                }
                  if($request->get('to_date') !=""){
                    $data->whereDate('a.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                }
                if($request->get('date') !=""){
                    $type=base64_decode($request->get('date'));
                    $today_date=date('Y-m-d');
                    $prev_date=date('Y-m-d',strtotime($today_date.'- 7 days'));
    
                    $data->whereDate('a.created_at','>=',$prev_date);
                    $data->whereDate('a.created_at','<=',$today_date);
                }
                $data=$data->paginate(10);
        }
        elseif($service_id=='19')
        {
            $data=DB::table('telecom_check as a')
                    ->select('s.name','a.mobile_no','a.user_id','a.created_at','a.price','a.full_name')
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
                    ->orderBy('a.id','desc');
                    if($request->get('from_date') !=""){
                        $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                    }
                      if($request->get('to_date') !=""){
                        $data->whereDate('a.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                    }
                    if($request->get('date') !=""){
                        $type=base64_decode($request->get('date'));
                        $today_date=date('Y-m-d');
                        $prev_date=date('Y-m-d',strtotime($today_date.'- 7 days'));
        
                        $data->whereDate('a.created_at','>=',$prev_date);
                        $data->whereDate('a.created_at','<=',$today_date);
                    }
                    $data=$data->paginate(10);
        }
        elseif(stripos($service_d->type_name,'e_court')!==false)
        {
            $data=DB::table('e_court_checks as a')
                    ->select('s.name as service_name','a.name','a.father_name','a.address','a.user_id','a.created_at','a.price')
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['a.source_type'=>'API','a.user_type'=>'coc','a.business_id'=>$business_id])
                    ->orderBy('a.id','desc');
                    if($request->get('from_date') !=""){
                        $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                    }
                    if($request->get('to_date') !=""){
                        $data->whereDate('a.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                    }
                    if($request->get('date') !=""){
                        $type=base64_decode($request->get('date'));
                        $today_date=date('Y-m-d');
                        $prev_date=date('Y-m-d',strtotime($today_date.'- 7 days'));

                        $data->whereDate('a.created_at','>=',$prev_date);
                        $data->whereDate('a.created_at','<=',$today_date);
                    }
                    $data=$data->paginate(10);
        }
        elseif(stripos($service_d->type_name,'upi')!==false)
        {
            $data=DB::table('upi_checks as a')
                    ->select('s.name as service_name','a.name','a.upi_id','a.user_id','a.created_at','a.price')
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['a.source_type'=>'API','a.user_type'=>'coc','a.business_id'=>$business_id])
                    ->orderBy('a.id','desc');
                    if($request->get('from_date') !=""){
                        $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                    }
                    if($request->get('to_date') !=""){
                        $data->whereDate('a.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                    }
                    if($request->get('date') !=""){
                        $type=base64_decode($request->get('date'));
                        $today_date=date('Y-m-d');
                        $prev_date=date('Y-m-d',strtotime($today_date.'- 7 days'));

                        $data->whereDate('a.created_at','>=',$prev_date);
                        $data->whereDate('a.created_at','<=',$today_date);
                    }
                    $data=$data->paginate(10);
        }
        elseif(stripos($service_d->type_name,'cin')!==false)
        {
            $data=DB::table('cin_checks as a')
                    ->select('s.name as service_name','a.company_name','a.cin_number','a.user_id','a.created_at','a.price')
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['a.source_type'=>'API','a.user_type'=>'coc','a.business_id'=>$business_id])
                    ->orderBy('a.id','desc');
                    if($request->get('from_date') !=""){
                        $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                    }
                    if($request->get('to_date') !=""){
                        $data->whereDate('a.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                    }
                    if($request->get('date') !=""){
                        $type=base64_decode($request->get('date'));
                        $today_date=date('Y-m-d');
                        $prev_date=date('Y-m-d',strtotime($today_date.'- 7 days'));

                        $data->whereDate('a.created_at','>=',$prev_date);
                        $data->whereDate('a.created_at','<=',$today_date);
                    }
                    $data=$data->paginate(10);
        }
        // else{
        //     $data="";
        // }
        if($request->ajax())
            return view('clients.accounts.api.api-details_ajax',compact('data','service_d'));
        else
            return view('clients.accounts.api.api-details',compact('data','service_d'));
    }

    public function downloadApiDetails(Request $request)
    {
        $service_id=base64_decode($request->service_id);
        $business_id=Auth::user()->business_id;
        $service_d=DB::table('services')->select('name','id','type_name')->where(['id'=>$service_id])->first();
        $rules= 
        [
            'type' => 'required'
            
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }


        if($service_id=='2')
        {
            if($request->type=="pdf")
            {
                $path=public_path().'/api/coc/pdf/aadhar/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }
                $file_name="aadhar-".date('Ymdhis').".pdf";
                $data=DB::table('aadhar_checks as a')
                        ->select('s.name','a.aadhar_number','a.user_id','a.created_at','a.price')
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['source_reference'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
                        ->orderBy('a.id','desc')
                        ->get();

                $pdf =new PDF;

                $pdf = PDF::loadView('clients.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                    'title' => 'API Details',
                    'margin_top' => 20,
                    'margin-header'=>20,
                    'margin_bottom' =>25,
                    'margin_footer'=>5,
                ])->save(public_path()."/api/coc/pdf/aadhar/".$file_name);
                
                // echo url('/').'/api/pdf/aadhar/'.$file_name;
                
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/pdf/aadhar/'.$file_name
                ]);

            }
            elseif($request->type=="excel")
            {
                $path=public_path().'/api/coc/excel/aadhar/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }
                $file_name='aadhar-'.date('Ymdhis').'.xlsx';
                Excel::store(new AadharExport($business_id), 'api/coc/excel/aadhar/'.$file_name, 'real_public');
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/excel/aadhar/'.$file_name
                ]);
            }
        }
        elseif($service_id=='3')
        {
            if($request->type=="pdf")
            {
                $path=public_path().'/api/coc/pdf/pan/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }
                $file_name="pan-".date('Ymdhis').".pdf";
                $data=DB::table('pan_checks as a')
                        ->select('s.name','a.pan_number','a.user_id','a.created_at','a.price','a.full_name')
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
                        ->orderBy('a.id','desc')
                        ->get();

                $pdf =new PDF;

                $pdf = PDF::loadView('clients.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                    'title' => 'API Details',
                    'margin_top' => 20,
                    'margin-header'=>20,
                    'margin_bottom' =>25,
                    'margin_footer'=>5,
                ])->save(public_path()."/api/coc/pdf/pan/".$file_name);
                
                // echo url('/').'/api/pdf/aadhar/'.$file_name;
                
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/pdf/pan/'.$file_name
                ]);

            }
            elseif($request->type=="excel")
            {
                $path=public_path().'/api/coc/excel/pan/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }
                $file_name='pan-'.date('Ymdhis').'.xlsx';
                Excel::store(new PanExport($business_id), 'api/coc/excel/pan/'.$file_name, 'real_public');
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/excel/pan/'.$file_name
                ]);
            }
        }
        elseif($service_id=='4')
        {
            if($request->type=="pdf")
            {
                $path=public_path().'/api/coc/pdf/voterid/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }
                $file_name="voterid-".date('Ymdhis').".pdf";
                $data=DB::table('voter_id_checks as a')
                        ->select('s.name','a.voter_id_number','a.user_id','a.created_at','a.price','a.full_name')
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['source_reference'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
                        ->orderBy('a.id','desc')
                        ->get();

                $pdf =new PDF;

                $pdf = PDF::loadView('clients.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                    'title' => 'API Details',
                    'margin_top' => 20,
                    'margin-header'=>20,
                    'margin_bottom' =>25,
                    'margin_footer'=>5,
                ])->save(public_path()."/api/coc/pdf/voterid/".$file_name);
                
                // echo url('/').'/api/pdf/aadhar/'.$file_name;
                
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/pdf/voterid/'.$file_name
                ]);

            }
            elseif($request->type=="excel")
            {
                $path=public_path().'/api/coc/excel/voterid/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }

                $file_name='voterid-'.date('Ymdhis').'.xlsx';
                Excel::store(new VoteridExport($business_id), 'api/coc/excel/voterid/'.$file_name, 'real_public');
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/excel/voterid/'.$file_name
                ]);
            }
            
        }
        elseif($service_id=='7')
        {
            if($request->type=="pdf")
            {
                $path=public_path().'/api/coc/pdf/rc/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }
                $file_name="rc-".date('Ymdhis').".pdf";
                $data=DB::table('rc_checks as a')
                        ->select('s.name','a.rc_number','a.user_id','a.created_at','a.price','a.owner_name')
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
                        ->orderBy('a.id','desc')
                        ->get();

                $pdf =new PDF;

                $pdf = PDF::loadView('clients.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                    'title' => 'API Details',
                    'margin_top' => 20,
                    'margin-header'=>20,
                    'margin_bottom' =>25,
                    'margin_footer'=>5,
                ])->save(public_path()."/api/coc/pdf/rc/".$file_name);
                
                // echo url('/').'/api/pdf/aadhar/'.$file_name;
                
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/pdf/rc/'.$file_name
                ]);

            }
            elseif($request->type=="excel")
            {
                $path=public_path().'/api/coc/excel/rc/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }

                $file_name='rc-'.date('Ymdhis').'.xlsx';
                Excel::store(new RcExport($business_id), 'api/coc/excel/rc/'.$file_name, 'real_public');
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/excel/rc/'.$file_name
                ]);
            }
            
        }
        elseif($service_id=='8')
        {
            if($request->type=="pdf")
            {
                $path=public_path().'/api/coc/pdf/passport/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }
                $file_name="passport-".date('Ymdhis').".pdf";
                $data=DB::table('passport_checks as a')
                        ->select('s.name','a.passport_number','a.user_id','a.created_at','a.price','a.full_name')
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
                        ->orderBy('a.id','desc')
                        ->get();

                $pdf =new PDF;

                $pdf = PDF::loadView('clients.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                    'title' => 'API Details',
                    'margin_top' => 20,
                    'margin-header'=>20,
                    'margin_bottom' =>25,
                    'margin_footer'=>5,
                ])->save(public_path()."/api/coc/pdf/passport/".$file_name);
                
                // echo url('/').'/api/pdf/aadhar/'.$file_name;
                
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/pdf/passport/'.$file_name
                ]);

            }
            elseif($request->type=="excel")
            {
                $path=public_path().'/api/coc/excel/passport/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }

                $file_name='passport-'.date('Ymdhis').'.xlsx';
                Excel::store(new PassportExport($business_id), 'api/coc/excel/passport/'.$file_name, 'real_public');
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/excel/passport/'.$file_name
                ]);
            }
            
        }
        elseif($service_id=='9')
        {
            if($request->type=="pdf")
            {
                $path=public_path().'/api/coc/pdf/driving/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }
                $file_name="driving-".date('Ymdhis').".pdf";
                $data=DB::table('dl_checks as a')
                        ->select('s.name','a.dl_number','a.user_id','a.created_at','a.price','a.name')
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
                        ->orderBy('a.id','desc')
                        ->get();

                $pdf =new PDF;

                $pdf = PDF::loadView('clients.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                    'title' => 'API Details',
                    'margin_top' => 20,
                    'margin-header'=>20,
                    'margin_bottom' =>25,
                    'margin_footer'=>5,
                ])->save(public_path()."/api/coc/pdf/driving/".$file_name);
                
                // echo url('/').'/api/pdf/aadhar/'.$file_name;
                
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/pdf/driving/'.$file_name
                ]);

            }
            elseif($request->type=="excel")
            {
                $path=public_path().'/api/coc/excel/driving/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }

                $file_name='driving-'.date('Ymdhis').'.xlsx';
                Excel::store(new DrivingExport($business_id), 'api/coc/excel/driving/'.$file_name, 'real_public');
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/excel/driving/'.$file_name
                ]);
            }
            
        }
        elseif($service_id=='12')
        {
            if($request->type=="pdf")
            {
                $path=public_path().'/api/coc/pdf/bank/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }
                $file_name="bank-".date('Ymdhis').".pdf";
                $data=DB::table('bank_account_checks as a')
                        ->select('s.name','a.account_number','a.user_id','a.created_at','a.price','a.full_name')
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
                        ->orderBy('a.id','desc')
                        ->get();

                $pdf =new PDF;

                $pdf = PDF::loadView('clients.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                    'title' => 'API Details',
                    'margin_top' => 20,
                    'margin-header'=>20,
                    'margin_bottom' =>25,
                    'margin_footer'=>5,
                ])->save(public_path()."/api/coc/pdf/bank/".$file_name);
                
                // echo url('/').'/api/pdf/aadhar/'.$file_name;
                
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/pdf/bank/'.$file_name
                ]);

            }
            elseif($request->type=="excel")
            {
                $path=public_path().'/api/coc/excel/bank/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }

                $file_name='bank-'.date('Ymdhis').'.xlsx';
                Excel::store(new BankExport($business_id), 'api/coc/excel/bank/'.$file_name, 'real_public');
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/excel/bank/'.$file_name
                ]);
            }
            
        }
        elseif($service_id=='14')
        {
            if($request->type=="pdf")
            {
                $path=public_path().'/api/coc/pdf/gst/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }
                $file_name="gst-".date('Ymdhis').".pdf";
                $data=DB::table('gst_checks as a')
                        ->select('s.name','a.gst_number','a.user_id','a.created_at','a.price','a.legal_name')
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
                        ->orderBy('a.id','desc')
                        ->first();

                $pdf =new PDF;

                $pdf = PDF::loadView('clients.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                    'title' => 'API Details',
                    'margin_top' => 20,
                    'margin-header'=>20,
                    'margin_bottom' =>25,
                    'margin_footer'=>5,
                ])->save(public_path()."/api/coc/pdf/gst/".$file_name);
                
                // echo url('/').'/api/pdf/aadhar/'.$file_name;
                
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/pdf/gst/'.$file_name
                ]);

            }
            elseif($request->type=="excel")
            {
                $path=public_path().'/api/excel/gst/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }

                $file_name='gst-'.date('Ymdhis').'.xlsx';
                Excel::store(new GstExport($business_id), 'api/coc/excel/gst/'.$file_name, 'real_public');
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/excel/gst/'.$file_name
                ]);
            }
            
        }
        elseif($service_id=='19')
        {
            if($request->type=="pdf")
            {
                $path=public_path().'/api/coc/pdf/telecom/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }
                $file_name="telecom-".date('Ymdhis').".pdf";
                $data=DB::table('telecom_check as a')
                        ->select('s.name','a.mobile_no','a.user_id','a.created_at','a.price','a.full_name')
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$business_id])
                        ->orderBy('a.id','desc')
                        ->get();

                $pdf =new PDF;

                $pdf = PDF::loadView('clients.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                    'title' => 'API Details',
                    'margin_top' => 20,
                    'margin-header'=>20,
                    'margin_bottom' =>25,
                    'margin_footer'=>5,
                ])->save(public_path()."/api/coc/pdf/telecom/".$file_name);
                
                // echo url('/').'/api/pdf/aadhar/'.$file_name;
                
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/pdf/telecom/'.$file_name
                ]);

            }
            elseif($request->type=="excel")
            {
                $path=public_path().'/api/coc/excel/telecom/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }

                $file_name='telecom-'.date('Ymdhis').'.xlsx';
                Excel::store(new TelecomExport($business_id), 'api/coc/excel/telecom/'.$file_name, 'real_public');
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/excel/telecom/'.$file_name
                ]);
            }
            
        }
        elseif(stripos($service_d->type_name,'e_court')!==false)
        {
            if($request->type=="pdf")
            {
                $path=public_path().'/api/coc/pdf/ecourt/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }
                $file_name="e_court-".date('Ymdhis').".pdf";

                $data=DB::table('e_court_checks as a')
                            ->select('s.name as service_name','a.name','a.father_name','a.address','a.user_id','a.created_at','a.price')
                            ->join('services as s','s.id','=','a.service_id')
                            ->where(['a.source_type'=>'API','a.user_type'=>'coc','a.business_id'=>$business_id])
                            ->orderBy('a.id','desc')
                            ->get();

                $pdf =new PDF;

                $pdf = PDF::loadView('clients.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                    'title' => 'API Details',
                    'margin_top' => 20,
                    'margin-header'=>20,
                    'margin_bottom' =>25,
                    'margin_footer'=>5,
                ])->save(public_path()."/api/coc/pdf/ecourt/".$file_name);
                
                // echo url('/').'/api/pdf/aadhar/'.$file_name;
                
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/pdf/ecourt/'.$file_name
                ]);

            }
            elseif($request->type=="excel")
            {
                $path=public_path().'/api/coc/excel/ecourt/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }

                $file_name='e_court-'.date('Ymdhis').'.xlsx';
                Excel::store(new EcourtExport($business_id), '/api/coc/excel/ecourt/'.$file_name, 'real_public');
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/excel/ecourt/'.$file_name
                ]);
            }
        }
        elseif(stripos($service_d->type_name,'upi')!==false)
        {
            if($request->type=="pdf")
            {
                $path=public_path().'/api/coc/pdf/upi/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }
                $file_name="upi-".date('Ymdhis').".pdf";

                $data=DB::table('upi_checks as a')
                            ->select('s.name as service_name','a.upi_id','a.name','a.user_id','a.created_at','a.price')
                            ->join('services as s','s.id','=','a.service_id')
                            ->where(['a.source_type'=>'API','a.user_type'=>'coc','a.business_id'=>$business_id])
                            ->orderBy('a.id','desc')
                            ->get();

                $pdf =new PDF;

                $pdf = PDF::loadView('clients.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                    'title' => 'API Details',
                    'margin_top' => 20,
                    'margin-header'=>20,
                    'margin_bottom' =>25,
                    'margin_footer'=>5,
                ])->save(public_path()."/api/coc/pdf/upi/".$file_name);
                
                // echo url('/').'/api/pdf/aadhar/'.$file_name;
                
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/pdf/upi/'.$file_name
                ]);

            }
            elseif($request->type=="excel")
            {
                $path=public_path().'/api/coc/excel/upi/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }

                $file_name='upi-'.date('Ymdhis').'.xlsx';
                Excel::store(new UPIExport($business_id), '/api/coc/excel/upi/'.$file_name, 'real_public');
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/excel/upi/'.$file_name
                ]);
            }
        }
        elseif(stripos($service_d->type_name,'cin')!==false)
        {
            if($request->type=="pdf")
            {
                $path=public_path().'/api/coc/pdf/cin/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }
                $file_name="cin-".date('Ymdhis').".pdf";

                $data=DB::table('cin_checks as a')
                            ->select('s.name as service_name','a.cin_number','a.company_name','a.user_id','a.created_at','a.price')
                            ->join('services as s','s.id','=','a.service_id')
                            ->where(['a.source_type'=>'API','a.user_type'=>'coc','a.business_id'=>$business_id])
                            ->orderBy('a.id','desc')
                            ->get();

                $pdf =new PDF;

                $pdf = PDF::loadView('clients.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                    'title' => 'API Details',
                    'margin_top' => 20,
                    'margin-header'=>20,
                    'margin_bottom' =>25,
                    'margin_footer'=>5,
                ])->save(public_path()."/api/coc/pdf/cin/".$file_name);
                
                // echo url('/').'/api/pdf/aadhar/'.$file_name;
                
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/pdf/cin/'.$file_name
                ]);

            }
            elseif($request->type=="excel")
            {
                $path=public_path().'/api/coc/excel/cin/';

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }

                $file_name='cin-'.date('Ymdhis').'.xlsx';
                Excel::store(new CINExport($business_id), '/api/coc/excel/cin/'.$file_name, 'real_public');
                return response()->json([
                    'success' => true,
                    'url' => url('/').'/api/coc/excel/cin/'.$file_name
                ]);
            }
        }
        else
        {
            return response()->json([
                'success' => false,
                'errors' => ['type'=>'Downloading the Api Usage Detail is not Available !']
            ]);
        }


    }

    // public function feedback()
    // {
    //     return view('clients.accounts.feedback');
    // }

    public function checkPriceMaster(Request $request)
    {
        $business_id=Auth::user()->business_id;
        $parent_id=Auth::user()->parent_id;
        $checkserviceprice=DB::table('services as s')
                            ->select('s.id','s.name','s.verification_type','s.business_id')
                            ->where(['status'=>'1','s.verification_type'=>'Auto']);
                            if(is_numeric($request->get('service_id'))){
                                $checkserviceprice->where('s.id',$request->get('service_id'));
                            }
        $items=$checkserviceprice->paginate(10);

        $services=DB::table('services as s')
        ->select('s.name','s.id','s.verification_type')
        ->where('status','1')
        ->where('verification_type','Auto')
        ->whereNotIn('s.type_name',['e_court'])
        ->get();
        
        // dd($checkserviceprice);
        if($request->ajax())
            return view('clients.accounts.checkprice.ajax',compact('items','services'));
        else
            return view('clients.accounts.checkprice.index',compact('items','services'));
    }
    
}
