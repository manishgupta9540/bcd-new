<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
// use DB;
use Illuminate\Support\Facades\DB;
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
use App\Traits\CommonTrait;
use App\Disclaimer;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        ini_set('max_execution_time', '0');
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    public function index() 
    {
        $user_id = Auth::user()->id;
        $business_id=Auth::user()->business_id;
        $user_type = Auth::user()->user_type;

        $kams = DB::table('key_account_managers')
                    ->select('*')
                    ->where('user_id',$user_id)
                    ->get();

        if(stripos($user_type,'customer')!==false || count($kams)>0)
        {
            $customers = DB::table('users as u')
                    ->select('u.*','b.company_name')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['user_type'=>'client','parent_id'=>Auth::user()->business_id])
                    ->whereNotIn('u.id',[Auth::user()->business_id])
                    ->get();

            $users = DB::table('users')
                        ->where(['business_id'=>Auth::user()->business_id,'user_type'=>'user','is_deleted'=>'0','status'=>'1'])
                        ->get();
                        
            
            return view('admin.home', compact('customers','users','kams'));
        }
        else
        {
            return view('admin.dashboard.employee.home');   
        }
        
    }

    // public function dashboardCardRender(Request $request)
    // {
    //     $business_id=Auth::user()->business_id;

    //     //$time_start = microtime(true); 
    //     $customers_count  = DB::table('users as u')
    //     ->select('u.id','u.name','u.email','u.phone')
    //     ->join('user_businesses as b','b.business_id','=','u.id')
    //     ->where(['user_type'=>'client','parent_id'=>Auth::user()->business_id])
    //     ->whereNotIn('u.id',[Auth::user()->business_id])
    //     ->count();
    //     // dd($customers_count);
    //     // echo "<pre>";
    //     //     print_r($customers_count);
    //     // echo "</pre>";die;
    //     $customers_active  = DB::table('users as u')
    //     ->join('user_businesses as b','b.business_id','=','u.id')
    //     ->where(['user_type'=>'client','parent_id'=>Auth::user()->business_id,'is_deleted'=>0,'status'=>'1'])
    //     ->whereNotIn('u.id',[Auth::user()->business_id])
    //     ->count();
    //     $customers_inactive  = DB::table('users as u')
    //     ->join('user_businesses as b','b.business_id','=','u.id')
    //     ->where(['user_type'=>'client','parent_id'=>Auth::user()->business_id,'is_deleted'=>1,'status'=>'0'])
    //     ->whereNotIn('u.id',[Auth::user()->business_id])
    //     ->count();

    //     $candidate_count =DB::table('candidate_reinitiates as u')
    //     ->DISTINCT('u.id')
    //     ->select('u.*','j.sla_id','jsi.jaf_send_to','j.jaf_status','j.job_id','j.candidate_id','jsi.jaf_send_to','j.id as job_item_id')      
    //     ->join('job_items as j','j.candidate_id','=','u.id') 
    //     ->join('job_sla_items as jsi','jsi.candidate_id','=','j.candidate_id' )             
    //     ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'u.is_deleted'=>'0'])
    //     ->groupBy('jsi.candidate_id')
    //     ->get();
    //     $candidate_count=count($candidate_count);
    //     // dd($candidate_count);

    //     $jaf_send_to_customer =DB::table('candidate_reinitiates as u')
    //     ->DISTINCT('u.id')
    //     ->select('u.*','jsi.jaf_send_to','jsi.job_id','jsi.candidate_id') 
    //     ->join('job_items as j','j.candidate_id','=','u.id')     
    //     ->join('job_sla_items as jsi','jsi.candidate_id','=','u.id')        
    //     ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0','jsi.jaf_send_to'=>'customer'])
    //     ->groupBy('jsi.candidate_id')
    //     ->get();
    //     // dd($jaf_send_to_customer->get());
    //     $jaf_send_to_customer=count($jaf_send_to_customer);
    //     //dd($jaf_send_to_customer);

    //     $jaf_send_to_candidate =DB::table('candidate_reinitiates as u')
    //     ->DISTINCT('u.id')
    //     ->select('u.*','jsi.jaf_send_to','jsi.job_id','jsi.candidate_id') 
    //     ->join('job_items as j','j.candidate_id','=','u.id')      
    //     ->join('job_sla_items as jsi','jsi.candidate_id','=','u.id')        
    //     ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0','jsi.jaf_send_to'=>'candidate'])
    //     ->groupBy('jsi.candidate_id')
    //     ->get();

    //     // dd($jaf_send_to_candidate);

    //     $jaf_send_to_candidate=count($jaf_send_to_candidate);

    //     // dd($jaf_send_to_candidate);

    //     $jaf_send_to_cocs =DB::table('candidate_reinitiates as u')
    //     ->distinct('jsi.candidate_id')
    //     ->select('u.*','jsi.jaf_send_to','jsi.job_id','jsi.candidate_id')
    //     ->join('job_items as j','j.candidate_id','=','u.id')       
    //     ->join('job_sla_items as jsi','jsi.candidate_id','=','u.id')        
    //     ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0','jsi.jaf_send_to'=>'coc'])
    //     ->groupBy('jsi.candidate_id')
    //     ->get();

    //     // dd($jaf_send_to_coc);
    //     $jaf_send_to_coc=count($jaf_send_to_cocs);
      
    //     $candidate_inactive =DB::table('candidate_reinitiates')->where(['user_type'=>'candidate','parent_id'=>Auth::user()->business_id,'status'=>'0'])->count();


    //     $reports = DB::table('reports')->where(['parent_id'=>Auth::user()->business_id])->count();

    //     $pending_report=DB::table('reports')->where(['parent_id'=>$business_id,'status'=>'incomplete'])->count();

    //     $complete_report=DB::table('reports')
    //                     ->where(['parent_id'=>$business_id])
    //                     ->whereIn('status',['completed','interim'])
    //                     ->count();


    //     $completed_jaf = DB::table('candidate_reinitiates as u')
    //     ->DISTINCT('u.id')
    //     ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
    //     ->join('job_items as j','j.candidate_id','=','u.id')  
    //     // ->join('jaf_form_data as jfd','jfd.job_item_id','=','j.id') 
    //     ->join('job_sla_items as jsi','jsi.candidate_id','=','j.candidate_id' )           
    //     ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0','j.jaf_status' =>'filled'])
    //     // ->groupBy('jfd.candidate_id')
    //     ->get();
        
    //     $completed_jaf=count($completed_jaf);
    //     // dd($completed_jaf);

    //     $completed_jaf_by_customer = DB::table('candidate_reinitiates as u')
    //     ->distinct('jfd.candidate_id')
    //     ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
    //     ->join('job_items as j','j.candidate_id','=','u.id')
    //     ->join('job_sla_items as jsi','jsi.candidate_id','=','u.id')  
    //     // ->join('jaf_form_data as jfd','jfd.job_item_id','=','j.id')      
    //     ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0', 'jsi.jaf_send_to'=>'customer'])
    //     // ->groupBy('jsi.candidate_id')
    //     ->whereIn('j.jaf_status',['pending','draft'])
    //     ->get();
    //     $completed_jaf_by_customer=count($completed_jaf_by_customer);

    //     $completed_jaf_by_candidate = DB::table('candidate_reinitiates as u')
    //     ->distinct('jsi.candidate_id')
    //     ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
    //     ->join('job_items as j','j.candidate_id','=','u.id')  
    //     ->join('job_sla_items as jsi','jsi.candidate_id','=','u.id') 
    //     // ->join('jaf_form_data as jfd','jfd.job_item_id','=','j.id')      
    //     ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0', 'jsi.jaf_send_to'=>'candidate'])
    //     ->whereIn('j.jaf_status',['pending','draft'])
    //     // ->groupBy('jsi.candidate_id')
    //     ->get();

    //     $completed_jaf_by_candidate=count($completed_jaf_by_candidate);
        
    //     $completed_jaf_by_coc = DB::table('candidate_reinitiates as u')
    //     ->distinct('jsi.candidate_id')
    //     ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
    //     ->join('job_items as j','j.candidate_id','=','u.id') 
    //     ->join('job_sla_items as jsi','jsi.candidate_id','=','u.id')  
    //     // ->join('jaf_form_data as jfd','jfd.job_item_id','=','j.id')      
    //     ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0','jsi.jaf_send_to'=>'coc'])
    //     ->whereIn('j.jaf_status',['pending','draft'])
    //     // ->groupBy('jsi.candidate_id')
    //     ->get();

    //     $completed_jaf_by_coc=count($completed_jaf_by_coc);

    //     $incompleted_jaf_insuff = DB::table('candidate_reinitiates as u')
    //     ->distinct('u.id')
    //     ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
    //     ->join('job_items as j','j.candidate_id','=','u.id')  
    //     ->join('jaf_form_data as jfd','jfd.job_item_id','=','j.id')      
    //     ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0','j.jaf_status' =>'filled','jfd.is_insufficiency'=>'0' ])
    //     ->groupBy('jfd.candidate_id')
    //     ->get();
    //     $incompleted_jaf_insuff=count($incompleted_jaf_insuff);

    //     $total_checks = DB::table('candidate_reinitiates as u')
    //     // ->distinct('u.id')
    //     ->select('u.*','jfd.candidate_id')      
    //     // ->join('job_items as j','j.candidate_id','=','u.id')  
    //     ->join('jaf_form_data as jfd','jfd.candidate_id','=','u.id')      
    //     ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'u.is_deleted'=>'0' ])->count();        
    //     // dd($total_checks);

    //     $completed_checks = DB::table('candidate_reinitiates as u')
    //     ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
    //     // ->join('job_items as j','j.candidate_id','=','u.id')  
    //     ->join('jaf_form_data as jfd','jfd.candidate_id','=','u.id')      
    //     ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0','jfd.verification_status'=>'success','jfd.is_insufficiency'=>'0' ])
    //     ->count();

    //     // dd($completed_checks);

    //     $incompleted_checks = DB::table('candidate_reinitiates as u') 
    //     ->join('jaf_form_data as jfd','jfd.candidate_id','=','u.id')      
    //     ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'u.is_deleted'=>'0','jfd.verification_status'=>NULL ])
    //     ->orWhere('jfd.verification_status','failed')
    //     ->count();

        
        
    //     // print_r(Auth::user()->session_id);
    //     // $time_end = microtime(true);
    //     // $execution_time = ($time_end - $time_start);
    //     // echo '<b>Total Execution Time:</b> '.($execution_time).'Seconds';

    //     $viewRender = view('admin.dashboard.home-dashboard',compact('customers_count','reports','pending_report','complete_report','completed_jaf','customers_active','customers_inactive','candidate_count','jaf_send_to_customer','jaf_send_to_candidate','jaf_send_to_coc','completed_jaf_by_customer','completed_jaf_by_coc','completed_jaf_by_candidate','incompleted_jaf_insuff','completed_checks','total_checks','incompleted_checks'))->render();
	//     return response()->json(array('success' => true, 'html'=>$viewRender));
    // }

    public function dashboardCustomerCard(Request $request)
    {
        $user_id = Auth::user()->id;
        
        $business_id=Auth::user()->business_id;

        $kams = DB::table('key_account_managers')
                    ->select('*')
                    ->where('user_id',$user_id)
                    ->get()->pluck('business_id');

        if(count($kams)>0)
        {
            //$time_start = microtime(true); 
            $customers_count  = DB::table('users as u')
            ->select('u.id','u.name','u.email','u.phone')
            ->join('user_businesses as b','b.business_id','=','u.id')
            ->where(['user_type'=>'client','parent_id'=>Auth::user()->business_id])
            ->whereIn('u.id',$kams)
            ->count();
            
            $customers_active  = DB::table('users as u')
            ->join('user_businesses as b','b.business_id','=','u.id')
            ->where(['user_type'=>'client','parent_id'=>Auth::user()->business_id,'is_deleted'=>0,'status'=>'1'])
            ->whereIn('u.id',$kams)
            ->count();

            $customers_inactive  = DB::table('users as u')
            ->join('user_businesses as b','b.business_id','=','u.id')
            ->where(['user_type'=>'client','parent_id'=>Auth::user()->business_id])
            ->whereIn('u.id',$kams)
            ->where(function($q){
                $q->where('status','0')
                ->orWhere('is_deleted',1);
            })
            ->count();
        }
        else
        {
            //$time_start = microtime(true); 
            $customers_count  = DB::table('users as u')
            ->select('u.id','u.name','u.email','u.phone')
            ->join('user_businesses as b','b.business_id','=','u.id')
            ->where(['user_type'=>'client','parent_id'=>Auth::user()->business_id])
            ->whereNotIn('u.id',[Auth::user()->business_id])
            ->count();
            
            $customers_active  = DB::table('users as u')
            ->join('user_businesses as b','b.business_id','=','u.id')
            ->where(['user_type'=>'client','parent_id'=>Auth::user()->business_id,'is_deleted'=>0,'status'=>'1'])
            ->whereNotIn('u.id',[Auth::user()->business_id])
            ->count();

            $customers_inactive  = DB::table('users as u')
            ->join('user_businesses as b','b.business_id','=','u.id')
            ->where(['user_type'=>'client','parent_id'=>Auth::user()->business_id])
            ->whereNotIn('u.id',[Auth::user()->business_id])
            ->where(function($q){
                $q->where('status','0')
                ->orWhere('is_deleted',1);
            })
            ->count();    
        }

        $viewRender = view('admin.dashboard.customer-card',compact('customers_count','customers_active','customers_inactive','kams'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));
    }

    // public function dashboardCandidateCard(Request $request)
    // {
    //     $user_id = Auth::user()->id;

    //     $business_id = Auth::user()->business_id;

    //     $kams = DB::table('key_account_managers')
    //                 ->select('*')
    //                 ->where('user_id',$user_id)
    //                 ->get()->pluck('business_id');

    //     if(count($kams)>0)
    //     {
    //         $candidate_count =DB::table('candidate_reinitiates as u')
    //                             ->DISTINCT('u.id')
    //                             ->select('u.*','j.sla_id','jsi.jaf_send_to','j.jaf_status','j.job_id','j.candidate_id','jsi.jaf_send_to','j.id as job_item_id')      
    //                             ->join('job_items as j','j.candidate_id','=','u.id') 
    //                             ->join('job_sla_items as jsi','jsi.job_item_id','=','j.id' )             
    //                             ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'u.is_deleted'=>'0'])
    //                             ->whereIn('u.business_id',$kams)
    //                             ->groupBy('jsi.candidate_id')
    //                             ->get();
    //         $candidate_count=count($candidate_count);
    //         // dd($candidate_count);

    //         $jaf_send_to_customer =DB::table('candidate_reinitiates as u')
    //                         ->DISTINCT('u.id')
    //                         ->select('u.*','jsi.jaf_send_to','jsi.job_id','jsi.candidate_id') 
    //                         ->join('job_items as j','j.candidate_id','=','u.id')     
    //                         ->join('job_sla_items as jsi','jsi.job_item_id','=','j.id')        
    //                         ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0','jsi.jaf_send_to'=>'customer'])
    //                         ->whereIn('u.business_id',$kams)
    //                         ->groupBy('jsi.candidate_id')
    //                         ->get();
    //         // dd($jaf_send_to_customer->get());
    //         $jaf_send_to_customer=count($jaf_send_to_customer);
    //         //dd($jaf_send_to_customer);

    //         $jaf_send_to_candidate =DB::table('candidate_reinitiates as u')
    //                             ->DISTINCT('u.id')
    //                             ->select('u.*','jsi.jaf_send_to','jsi.job_id','jsi.candidate_id') 
    //                             ->join('job_items as j','j.candidate_id','=','u.id')      
    //                             ->join('job_sla_items as jsi','jsi.job_item_id','=','j.id')        
    //                             ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0','jsi.jaf_send_to'=>'candidate'])
    //                             ->whereIn('u.business_id',$kams)
    //                             ->groupBy('jsi.candidate_id')
    //                             ->get();

    //         // dd($jaf_send_to_candidate);

    //         $jaf_send_to_candidate=count($jaf_send_to_candidate);

    //         // dd($jaf_send_to_candidate);

    //         $jaf_send_to_cocs =DB::table('candidate_reinitiates as u')
    //                     ->distinct('jsi.candidate_id')
    //                     ->select('u.*','jsi.jaf_send_to','jsi.job_id','jsi.candidate_id')
    //                     ->join('job_items as j','j.candidate_id','=','u.id')       
    //                     ->join('job_sla_items as jsi','jsi.job_item_id','=','j.id')        
    //                     ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0','jsi.jaf_send_to'=>'coc'])
    //                     ->whereIn('u.business_id',$kams)
    //                     ->groupBy('jsi.candidate_id')
    //                     ->get();

    //         // dd($jaf_send_to_coc);
    //         $jaf_send_to_coc=count($jaf_send_to_cocs);
    //     }
    //     else
    //     {
    //         $candidate_count =DB::table('candidate_reinitiates as u')
    //                             ->DISTINCT('jsi.candidate_id')
    //                             ->select('u.*','j.sla_id','jsi.jaf_send_to','j.jaf_status','j.job_id','j.candidate_id','jsi.jaf_send_to','j.id as job_item_id')      
    //                             ->join('job_items as j','j.candidate_id','=','u.id') 
    //                             ->join('job_sla_items as jsi','jsi.job_item_id','=','j.id' )             
    //                             ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'u.is_deleted'=>'0'])
    //                             ->groupBy('jsi.candidate_id')
    //                             ->get();
    //         $candidate_count=count($candidate_count);
    //         // dd($candidate_count);

    //         $jaf_send_to_customer =DB::table('candidate_reinitiates as u')
    //                                 ->DISTINCT('jsi.candidate_id')
    //                                 ->select('u.*','jsi.jaf_send_to','jsi.job_id','jsi.candidate_id') 
    //                                 ->join('job_items as j','j.candidate_id','=','u.id')     
    //                                 ->join('job_sla_items as jsi','jsi.job_item_id','=','j.id')        
    //                                 ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0','jsi.jaf_send_to'=>'customer'])
    //                                 ->groupBy('jsi.candidate_id')
    //                                 ->get();
    //         // dd($jaf_send_to_customer->get());
    //         $jaf_send_to_customer=count($jaf_send_to_customer);
    //         //dd($jaf_send_to_customer);

    //         $jaf_send_to_candidate =DB::table('candidate_reinitiates as u')
    //                                     ->DISTINCT('jsi.candidate_id')
    //                                     ->select('u.*','jsi.jaf_send_to','jsi.job_id','jsi.candidate_id') 
    //                                     ->join('job_items as j','j.candidate_id','=','u.id')      
    //                                     ->join('job_sla_items as jsi','jsi.job_item_id','=','j.id')        
    //                                     ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0','jsi.jaf_send_to'=>'candidate'])
    //                                     ->groupBy('jsi.candidate_id')
    //                                     ->get();

    //         // dd($jaf_send_to_candidate);

    //         $jaf_send_to_candidate=count($jaf_send_to_candidate);

    //         // // dd($jaf_send_to_candidate);

    //         $jaf_send_to_cocs =DB::table('candidate_reinitiates as u')
    //                             ->distinct('jsi.candidate_id')
    //                             ->select('u.*','jsi.jaf_send_to','jsi.job_id','jsi.candidate_id')
    //                             ->join('job_items as j','j.candidate_id','=','u.id')       
    //                             ->join('job_sla_items as jsi','jsi.job_item_id','=','j.id')        
    //                             ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0','jsi.jaf_send_to'=>'coc'])
    //                             ->groupBy('jsi.candidate_id')
    //                             ->get();

    //         // dd($jaf_send_to_coc);
    //         $jaf_send_to_coc=count($jaf_send_to_cocs);

            
    //     }

    //     $viewRender = view('admin.dashboard.candidate-card',compact('candidate_count','jaf_send_to_customer','jaf_send_to_candidate','jaf_send_to_coc'))->render();
	//     return response()->json(array('success' => true, 'html'=>$viewRender));
    // }

    public function dashboardCandidateCountCard(Request $request)
    {
        $user_id = Auth::user()->id;

        $business_id = Auth::user()->business_id;

        $kams = DB::table('key_account_managers')
                    ->select('*')
                    ->where('user_id',$user_id)
                    ->get()->pluck('business_id');

        $html = '';

        if(count($kams)>0)
        {
            $candidate_count = DB::table('candidate_reinitiates as u')
                                // ->DISTINCT('u.id')
                                ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
                                ->join('job_items as j','j.candidate_id','=','u.id') 
                                // ->join('job_sla_items as jsi','jsi.job_item_id','=','j.id' )             
                                ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'u.is_deleted'=>'0'])
                                ->whereIn('u.business_id',$kams)
                                // ->groupBy('jsi.candidate_id')
                                ->count();
            //$candidate_count=count($candidate_count);
        }
        else
        {
            $candidate_count = DB::table('candidate_reinitiates as u')
                                // ->DISTINCT('jsi.candidate_id')
                                ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
                                ->join('job_items as j','j.candidate_id','=','u.id') 
                                // ->join('job_sla_items as jsi','jsi.job_item_id','=','j.id' )             
                                ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'u.is_deleted'=>'0'])
                                // ->groupBy('jsi.candidate_id')
                                ->count();
            //$candidate_count=count($candidate_count);
        }

        $html=$candidate_count;

        return response()->json(array('success' => true, 'html'=>$html));
    }

    public function dashboardCandidateJafCustomerCountCard(Request $request)
    {
        $user_id = Auth::user()->id;

        $business_id = Auth::user()->business_id;

        $kams = DB::table('key_account_managers')
                    ->select('*')
                    ->where('user_id',$user_id)
                    ->get()->pluck('business_id');

        $html = '';

        if(count($kams)>0)
        {
            $jaf_send_to_customer =DB::table('candidate_reinitiates as u')
                            ->DISTINCT('jsi.candidate_id')
                            ->select('u.*','jsi.jaf_send_to','jsi.job_id','jsi.candidate_id') 
                            ->join('job_items as j','j.candidate_id','=','u.id')     
                            ->join('job_sla_items as jsi','jsi.job_item_id','=','j.id')        
                            ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'u.is_deleted'=>'0','jsi.jaf_send_to'=>'customer'])
                            ->whereIn('u.business_id',$kams)
                            //->groupBy('jsi.candidate_id')
                            ->count();
            
            //$jaf_send_to_customer=count($jaf_send_to_customer);
        }
        else
        {
            $jaf_send_to_customer =DB::table('candidate_reinitiates as u')
                                    ->DISTINCT('jsi.candidate_id')
                                    ->select('u.*','jsi.jaf_send_to','jsi.job_id','jsi.candidate_id') 
                                    ->join('job_items as j','j.candidate_id','=','u.id')     
                                    ->join('job_sla_items as jsi','jsi.job_item_id','=','j.id')        
                                    ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'u.is_deleted'=>'0','jsi.jaf_send_to'=>'customer'])
                                    //->groupBy('jsi.candidate_id')
                                    ->count();
            
            //$jaf_send_to_customer=count($jaf_send_to_customer);
        }

        $html=$jaf_send_to_customer;

        return response()->json(array('success' => true, 'html'=>$html));
    }

    public function dashboardCandidateJafCocCountCard(Request $request)
    {
        $user_id = Auth::user()->id;

        $business_id = Auth::user()->business_id;

        $kams = DB::table('key_account_managers')
                    ->select('*')
                    ->where('user_id',$user_id)
                    ->get()->pluck('business_id');

        $html = '';

        if(count($kams)>0)
        {
            $jaf_send_to_cocs =DB::table('candidate_reinitiates as u')
                        ->distinct('jsi.candidate_id')
                        ->select('u.*','jsi.jaf_send_to','jsi.job_id','jsi.candidate_id')
                        ->join('job_items as j','j.candidate_id','=','u.id')       
                        ->join('job_sla_items as jsi','jsi.job_item_id','=','j.id')        
                        ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'u.is_deleted'=>'0','jsi.jaf_send_to'=>'coc'])
                        ->whereIn('u.business_id',$kams)
                        ->groupBy('jsi.candidate_id')
                        ->get();

            $jaf_send_to_coc=count($jaf_send_to_cocs);
        }
        else
        {
            $jaf_send_to_cocs =DB::table('candidate_reinitiates as u')
                                ->distinct('jsi.candidate_id')
                                ->select('u.*','jsi.jaf_send_to','jsi.job_id','jsi.candidate_id')
                                ->join('job_items as j','j.candidate_id','=','u.id')       
                                ->join('job_sla_items as jsi','jsi.job_item_id','=','j.id')        
                                ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'u.is_deleted'=>'0','jsi.jaf_send_to'=>'coc'])
                                ->groupBy('jsi.candidate_id')
                                ->get();

            $jaf_send_to_coc=count($jaf_send_to_cocs);
        }

        $html=$jaf_send_to_coc;

        return response()->json(array('success' => true, 'html'=>$html));
    }

    public function dashboardCandidateJafCandidateCountCard(Request $request)
    {
        $user_id = Auth::user()->id;

        $business_id = Auth::user()->business_id;

        $kams = DB::table('key_account_managers')
                    ->select('*')
                    ->where('user_id',$user_id)
                    ->get()->pluck('business_id');

        $html = '';

        if(count($kams)>0)
        {
            $jaf_send_to_candidate =DB::table('candidate_reinitiates as u')
                                ->DISTINCT('jsi.candidate_id')
                                ->select('u.*','jsi.jaf_send_to','jsi.job_id','jsi.candidate_id') 
                                ->join('job_items as j','j.candidate_id','=','u.id')      
                                ->join('job_sla_items as jsi','jsi.job_item_id','=','j.id')        
                                ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'u.is_deleted'=>'0','jsi.jaf_send_to'=>'candidate'])
                                ->whereIn('u.business_id',$kams)
                                ->groupBy('jsi.candidate_id')
                                ->get();

            $jaf_send_to_candidate=count($jaf_send_to_candidate);
        }
        else
        {
            $jaf_send_to_candidate =DB::table('candidate_reinitiates as u')
                                        ->DISTINCT('jsi.candidate_id')
                                        ->select('u.*','jsi.jaf_send_to','jsi.job_id','jsi.candidate_id') 
                                        ->join('job_items as j','j.candidate_id','=','u.id')      
                                        ->join('job_sla_items as jsi','jsi.job_item_id','=','j.id')        
                                        ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'u.is_deleted'=>'0','jsi.jaf_send_to'=>'candidate'])
                                        ->groupBy('jsi.candidate_id')
                                        ->get();

            $jaf_send_to_candidate=count($jaf_send_to_candidate);
        }

        $html=$jaf_send_to_candidate;

        return response()->json(array('success' => true, 'html'=>$html));
    }

    public function dashboardJafCard(Request $request)
    {
        $user_id = Auth::user()->id;

        $kams = DB::table('key_account_managers')
                    ->select('*')
                    ->where('user_id',$user_id)
                    ->get()->pluck('business_id');

        if(count($kams)>0)
        {
            $completed_jaf = DB::table('candidate_reinitiates as u')
                            ->DISTINCT('u.id')
                            ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
                            ->join('job_items as j','j.candidate_id','=','u.id')  
                            // ->join('jaf_form_data as jfd','jfd.job_item_id','=','j.id') 
                            ->join('job_sla_items as jsi','jsi.candidate_id','=','j.candidate_id' )           
                            ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0','j.jaf_status' =>'filled'])
                            // ->groupBy('jfd.candidate_id')
                            ->whereIn('u.business_id',$kams)
                            ->count();
                            
            //$completed_jaf=count($completed_jaf);
            // dd($completed_jaf);

            $completed_jaf_by_customer = DB::table('candidate_reinitiates as u')
                                        ->distinct('jsi.candidate_id')
                                        ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
                                        ->join('job_items as j','j.candidate_id','=','u.id')
                                        ->join('job_sla_items as jsi','jsi.candidate_id','=','u.id')  
                                        // ->join('jaf_form_data as jfd','jfd.job_item_id','=','j.id')      
                                        ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0', 'jsi.jaf_send_to'=>'customer'])
                                        // ->groupBy('jsi.candidate_id')
                                        ->whereIn('j.jaf_status',['pending','draft'])
                                        ->whereIn('u.business_id',$kams)
                                        ->count();
            //$completed_jaf_by_customer=count($completed_jaf_by_customer);

            $completed_jaf_by_candidate = DB::table('candidate_reinitiates as u')
                                            ->distinct('jsi.candidate_id')
                                            ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
                                            ->join('job_items as j','j.candidate_id','=','u.id')  
                                            ->join('job_sla_items as jsi','jsi.candidate_id','=','u.id') 
                                            // ->join('jaf_form_data as jfd','jfd.job_item_id','=','j.id')      
                                            ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0', 'jsi.jaf_send_to'=>'candidate'])
                                            ->whereIn('j.jaf_status',['pending','draft'])
                                            ->whereIn('u.business_id',$kams)
                                            // ->groupBy('jsi.candidate_id')
                                            ->count();

            //$completed_jaf_by_candidate=count($completed_jaf_by_candidate);
            
            $completed_jaf_by_coc = DB::table('candidate_reinitiates as u')
                                    ->distinct('jsi.candidate_id')
                                    ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
                                    ->join('job_items as j','j.candidate_id','=','u.id') 
                                    ->join('job_sla_items as jsi','jsi.candidate_id','=','u.id')  
                                    // ->join('jaf_form_data as jfd','jfd.job_item_id','=','j.id')      
                                    ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0','jsi.jaf_send_to'=>'coc'])
                                    ->whereIn('j.jaf_status',['pending','draft'])
                                    ->whereIn('u.business_id',$kams)
                                    // ->groupBy('jsi.candidate_id')
                                    ->count();

            //$completed_jaf_by_coc=count($completed_jaf_by_coc);
        }
        else
        {
            $completed_jaf = DB::table('candidate_reinitiates as u')
                ->DISTINCT('u.id')
                ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
                ->join('job_items as j','j.candidate_id','=','u.id')  
                // ->join('jaf_form_data as jfd','jfd.job_item_id','=','j.id') 
                ->join('job_sla_items as jsi','jsi.candidate_id','=','j.candidate_id' )           
                ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0','j.jaf_status' =>'filled'])
                // ->groupBy('jfd.candidate_id')
                ->count();
                
                //$completed_jaf=count($completed_jaf);
                // dd($completed_jaf);

                $completed_jaf_by_customer = DB::table('candidate_reinitiates as u')
                ->distinct('jsi.candidate_id')
                ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
                ->join('job_items as j','j.candidate_id','=','u.id')
                ->join('job_sla_items as jsi','jsi.candidate_id','=','u.id')  
                // ->join('jaf_form_data as jfd','jfd.job_item_id','=','j.id')      
                ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0', 'jsi.jaf_send_to'=>'customer'])
                // ->groupBy('jsi.candidate_id')
                ->whereIn('j.jaf_status',['pending','draft'])
                ->count();
                //$completed_jaf_by_customer=count($completed_jaf_by_customer);

                $completed_jaf_by_candidate = DB::table('candidate_reinitiates as u')
                ->distinct('jsi.candidate_id')
                ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
                ->join('job_items as j','j.candidate_id','=','u.id')  
                ->join('job_sla_items as jsi','jsi.candidate_id','=','u.id') 
                // ->join('jaf_form_data as jfd','jfd.job_item_id','=','j.id')      
                ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0', 'jsi.jaf_send_to'=>'candidate'])
                ->whereIn('j.jaf_status',['pending','draft'])
                // ->groupBy('jsi.candidate_id')
                ->count();

                //$completed_jaf_by_candidate=count($completed_jaf_by_candidate);
                
                $completed_jaf_by_coc = DB::table('candidate_reinitiates as u')
                ->distinct('jsi.candidate_id')
                ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
                ->join('job_items as j','j.candidate_id','=','u.id') 
                ->join('job_sla_items as jsi','jsi.candidate_id','=','u.id')  
                // ->join('jaf_form_data as jfd','jfd.job_item_id','=','j.id')      
                ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0','jsi.jaf_send_to'=>'coc'])
                ->whereIn('j.jaf_status',['pending','draft'])
                // ->groupBy('jsi.candidate_id')
                ->count();

                //$completed_jaf_by_coc=count($completed_jaf_by_coc);
        }

        $viewRender = view('admin.dashboard.jaf-card',compact('completed_jaf','completed_jaf_by_customer','completed_jaf_by_candidate','completed_jaf_by_coc','kams'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));
    }

    public function dashboardCheckCard(Request $request)
    {
        $user_id = Auth::user()->id;

        $business_id = Auth::user()->business_id;

        $kams = DB::table('key_account_managers')
                    ->select('*')
                    ->where('user_id',$user_id)
                    ->get()->pluck('business_id');

        if(count($kams)>0)
        {
            $total_checks = DB::table('candidate_reinitiates as u')
                            // ->distinct('u.id')
                            ->select('u.*','jfd.candidate_id')      
                            // ->join('job_items as j','j.candidate_id','=','u.id')  
                            ->join('jaf_form_data as jfd','jfd.candidate_id','=','u.id')      
                            ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'u.is_deleted'=>'0' ])
                            ->whereIn('u.business_id',$kams)
                            ->get();
            $total_checks = count($total_checks);        
             //dd($total_checks);
    
            $completed_checks = DB::table('candidate_reinitiates as u')
                                ->select('u.*')      
                                // ->join('job_items as j','j.candidate_id','=','u.id')  
                                ->join('jaf_form_data as jfd','jfd.candidate_id','=','u.id')      
                                ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0','jfd.verification_status'=>'success','jfd.is_insufficiency'=>'0' ])
                                ->whereIn('u.business_id',$kams)
                                ->get();
            $completed_checks = count($completed_checks);
           // dd($completed_checks);
    
            $incompleted_checks = DB::table('candidate_reinitiates as u') 
                                    ->select('u.*')
                                    ->join('jaf_form_data as jfd','jfd.candidate_id','=','u.id')      
                                    ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'u.is_deleted'=>'0'])
                                    ->whereIn('u.business_id',$kams)
                                    ->where(function($q){
                                        $q->where('jfd.verification_status','failed')
                                          ->orWhereNull('jfd.verification_status');
                                    })
                                    ->get();
            // dd($incompleted_checks);
    
            $incompleted_checks = count($incompleted_checks);
        }
        else
        {
            $total_checks = DB::table('candidate_reinitiates as u')
            // ->distinct('u.id')
            ->select('u.*','jfd.candidate_id')      
            // ->join('job_items as j','j.candidate_id','=','u.id')  
            ->join('jaf_form_data as jfd','jfd.candidate_id','=','u.id')      
            ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'u.is_deleted'=>'0' ])->get();
            $total_checks = count($total_checks);        
            // dd($total_checks);

            $completed_checks = DB::table('candidate_reinitiates as u')
            ->select('u.*')      
            // ->join('job_items as j','j.candidate_id','=','u.id')  
            ->join('jaf_form_data as jfd','jfd.candidate_id','=','u.id')      
            ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'is_deleted'=>'0','jfd.verification_status'=>'success','jfd.is_insufficiency'=>'0' ])
            ->get();
            $completed_checks = count($completed_checks);
            // dd($completed_checks);

            $incompleted_checks = DB::table('candidate_reinitiates as u') 
            ->select('u.*')
            ->join('jaf_form_data as jfd','jfd.candidate_id','=','u.id')      
            ->where(['u.user_type'=>'candidate','u.parent_id'=>Auth::user()->business_id,'u.is_deleted'=>'0'])
            ->where(function($q){
                $q->where('jfd.verification_status','failed')
                ->orWhereNull('jfd.verification_status');
            })
            ->get();
            // dd($incompleted_checks);

            $incompleted_checks = count($incompleted_checks);
        }

        $viewRender = view('admin.dashboard.check-card',compact('total_checks','completed_checks','incompleted_checks'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));
    }

    public function dashboardReportCard(Request $request)
    {
        $user_id = Auth::user()->id;
        $business_id = Auth::user()->business_id;

        $kams = DB::table('key_account_managers')
                    ->select('*')
                    ->where('user_id',$user_id)
                    ->get()->pluck('business_id');

        if(count($kams)>0)
        {
            $reports = DB::table('reports as r')
                        ->select('r.*')
                        ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                        ->where(['r.parent_id'=>Auth::user()->business_id])
                        ->whereIn('u.business_id',$kams)
                        ->count();

            $pending_report=DB::table('reports as r')
                            ->select('r.*')
                            ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                            ->where(['r.parent_id'=>$business_id,'r.status'=>'incomplete'])
                            ->whereIn('u.business_id',$kams)
                            ->count();

            $complete_report=DB::table('reports as r')
                            ->select('r.*')
                            ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                            ->where(['r.parent_id'=>$business_id])
                            ->whereIn('u.business_id',$kams)
                            ->whereIn('r.status',['completed','interim'])
                            ->count();
        }
        else
        {
            $reports = DB::table('reports as r')
                        ->select('r.*')
                        ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                        ->where(['r.parent_id'=>Auth::user()->business_id])
                        ->count();

            $pending_report=DB::table('reports as r')
                            ->select('r.*')
                            ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                            ->where(['r.parent_id'=>$business_id,'r.status'=>'incomplete'])
                            ->count();

            $complete_report=DB::table('reports as r')
                            ->select('r.*')
                            ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                            ->where(['r.parent_id'=>$business_id])
                            ->whereIn('r.status',['completed','interim'])
                            ->count();
        }
        

        $viewRender = view('admin.dashboard.report-card',compact('reports','pending_report','complete_report','kams'))->render();
        return response()->json(array('success' => true, 'html'=>$viewRender));
    }

    public function dashboardCheckRender(Request $request)
    {
        $business_id=Auth::user()->business_id;

        $services = DB::table('services')
                    ->select('name','id')
                    ->where(['status'=>'1'])
                    ->where('business_id',NULL)
                    ->whereNotIn('type_name',['e_court','gstin'])
                    ->orwhere('business_id',Auth::user()->business_id)
                    ->orderBy('sort_number','asc')
                    ->get();

        $array_result = [];

        foreach ($services as $key => $value) {
            
            $completed = DB::table('jaf_form_data as jf')
            ->DISTINCT('u.id')
            ->join('candidate_reinitiates as u','u.id','=','jf.candidate_id')
            ->where(['jf.service_id'=> $value->id, 'u.parent_id'=>Auth::user()->business_id,'jf.verification_status'=>'success'])
            ->count();

            $pending = DB::table('jaf_form_data as jf')
            ->DISTINCT('u.id')
            ->join('candidate_reinitiates as u','u.id','=','jf.candidate_id')
            ->where(['jf.service_id'=> $value->id,'u.parent_id'=>Auth::user()->business_id,'jf.verification_status'=>null])
            ->count();
            $insuff = DB::table('jaf_form_data as jf')
            ->DISTINCT('u.id')
            ->join('candidate_reinitiates as u','u.id','=','jf.candidate_id')
            ->where(['jf.service_id'=> $value->id,'u.parent_id'=>Auth::user()->business_id,'jf.is_insufficiency'=>'1'])
            ->count();

            $array_result[] = ['check_id'=>$value->id,'check_name'=> $value->name, 'completed'=>$completed, 'pending'=> $pending,'insuff'=>$insuff]; 
                // 
        }

        $viewRender = view('admin.dashboard.home-check-dashboard',compact('array_result'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));
    }

    public function dashboardTeamLeadRender(Request $request)
    {
        $user_id = Auth::user()->id;
        $business_id=Auth::user()->business_id;
        $user_type = Auth::user()->user_type;

        $kams = DB::table('key_account_managers')
                    ->select('*')
                    ->where('user_id',$user_id)
                    ->get()->pluck('business_id')->all();

        $services = DB::table('services')
                    ->select('name','id')
                    ->where(['status'=>'1'])
                    ->where('business_id',NULL)
                    ->whereNotIn('type_name',['e_court','gstin'])
                    ->orwhere('business_id',Auth::user()->business_id)
                    ->orderBy('sort_number','asc')
                    ->get();
        
        $customer = DB::table('users')
                    ->where(['id'=>$business_id,'user_type'=>'customer'])
                    ->first();


        $user_arr = DB::table('users')
                    ->where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])
                    ->orderBy('name','asc')
                    ->pluck('name','id')->all();
        
        $user_arr = array($customer->id => $customer->name.' (Customer)') + $user_arr;

        //dd($user_arr);

        $task_jaf_result = [];

        $task_verification_result = [];

        $task_report_result = [];

        if(count($kams)>0)
        {
            if(count($user_arr)>0)
            {
                foreach($user_arr as $u_id => $u_name)
                {
                    // Task JAF Result

                    $total_allocated_task = DB::table('tasks as t')
                    ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                    ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                    ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'JAF Filling'])
                    ->whereIn('ta.status',['1','2'])
                    ->whereNotNull('t.assigned_to')
                    ->whereIn('t.business_id',$kams)
                    ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                    if($request->input('from_date') !=""){
                        // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                        $total_allocated_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                             ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                         });
                    }
                    if($request->input('to_date') !=""){
                        // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                        $total_allocated_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                             ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                         });
                    }
    
                    $total_allocated_task=$total_allocated_task->get();
    
                    $total_done_task_in = 0;
    
                    $total_done_task_out = 0;
    
                    $total_done_task =DB::table('tasks as t')
                        ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                        ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                        ->join('job_items as j','j.candidate_id','=','u.id')
                        ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                        ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'2','t.description'=>'JAF Filling'])
                        ->whereNotNull('t.assigned_to')
                        ->whereIn('t.business_id',$kams)
                        ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                        if($request->input('from_date') !=""){
                            // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                            $total_done_task->where(function($q) use ($request){
                                 $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                 ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                             });
                        }
                        if($request->input('to_date') !=""){
                            // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                            $total_done_task->where(function($q) use ($request){
                                 $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                 ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                             });
                        }
    
                    $total_done_task=$total_done_task->get();
    
                    // // Total Task Done
                    if(count($total_done_task)>0)
                    {
                        foreach($total_done_task as $item)
                        {
                            $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));
    
                            $end_date = $item->completed_at!=NULL ? date('Y-m-d',strtotime($item->completed_at)) : date('Y-m-d',strtotime($item->end_date_at));
    
                            
                                $date_arr = [];
    
                                $tat = $item->client_tat - 1;
    
                                $incentive_tat = $item->client_tat - 1;
    
                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                }
                                else if(stripos($item->days_type,'calender')!==false)
                                {
                                    $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                    ->orderBy('date','asc')
                                                    ->get();
                                    if(count($holiday_master)>0)
                                    {
                                        $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                    }
                                    else
                                    {
                                        $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                    }
                                }
    
                                
                                if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                {
                                    $total_done_task_out = $total_done_task_out + 1;
                                }
                                else
                                {
                                    $total_done_task_in = $total_done_task_in + 1;
                                }
    
                            
                        }
                    }
    
                    $total_pending_task_in = 0;
    
                    $total_pending_task_out = 0;
    
                    $total_pending_task =DB::table('tasks as t')
                    ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                    ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                    ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'JAF Filling','ta.status'=>'1'])
                    ->whereNotNull('t.assigned_to')
                    ->whereIn('t.business_id',$kams)
                    ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                    if($request->input('from_date') !=""){
                        // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                        $total_pending_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                             ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                         });
                    }
                    if($request->input('to_date') !=""){
                        // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                        $total_pending_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                             ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                         });
                    }
    
                    $total_pending_task=$total_pending_task->get();
    
                    // Total Task Pending
                    if(count($total_pending_task)>0)
                    {
                        foreach($total_pending_task as $item)
                        {
                            $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));
    
                            $end_date = date('Y-m-d');
    
                            
                            $date_arr = [];
    
                            $tat = $item->client_tat - 1;
    
                            $incentive_tat = $item->client_tat - 1;
    
                            if(stripos($item->days_type,'working')!==false)
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                            }
                            else if(stripos($item->days_type,'calender')!==false)
                            {
                                $holiday_master=DB::table('customer_holiday_masters')
                                                ->distinct('date')
                                                ->select('date')
                                                ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                ->orderBy('date','asc')
                                                ->get();
                                if(count($holiday_master)>0)
                                {
                                    $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                }
                                else
                                {
                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                }
                            }
    
                            
                            if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                            {
                                $total_pending_task_out = $total_pending_task_out + 1;
                            }
                            else
                            {
                                $total_pending_task_in = $total_pending_task_in + 1;
                            }
    
                        }
                    }

                    $task_jaf_result[]=[
                        'service'=>$u_name,
                        'user_id' => $u_id,
                        'allocated'=>count($total_allocated_task),
                        'completed'=>count($total_done_task),
                        'completed_in'=>$total_done_task_in,
                        'completed_out'=>$total_done_task_out,
                        'pending'   => count($total_pending_task),
                        'pending_in' => $total_pending_task_in,
                        'pending_out'   => $total_pending_task_out
                    ];

                    // Task Verification Result

                    $total_allocated_task = DB::table('tasks as t')
                                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                                ->join('job_items as j','j.candidate_id','=','u.id')
                                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Task for Verification'])
                                ->whereIn('ta.status',['1','2'])
                                ->whereNotNull('t.assigned_to')
                                ->whereIn('t.business_id',$kams)
                                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                                if($request->input('from_date') !=""){
                                    // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                                    $total_allocated_task->where(function($q) use ($request){
                                         $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                         ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                                     });
                                }
                                if($request->input('to_date') !=""){
                                    // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                                    $total_allocated_task->where(function($q) use ($request){
                                         $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                         ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                                     });
                                }

                            $total_allocated_task=$total_allocated_task->get();

                            $total_done_task_in = 0;

                            $total_done_task_out = 0;

                            $total_done_task =DB::table('tasks as t')
                                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                                ->join('job_items as j','j.candidate_id','=','u.id')
                                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'2','t.description'=>'Task for Verification'])
                                ->whereNotNull('t.assigned_to')
                                ->whereIn('t.business_id',$kams)
                                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                                if($request->input('from_date') !=""){
                                    // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                                    $total_done_task->where(function($q) use ($request){
                                         $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                         ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                                     });
                                }
                                if($request->input('to_date') !=""){
                                    // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                                    $total_done_task->where(function($q) use ($request){
                                         $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                         ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                                     });
                                }

                            $total_done_task=$total_done_task->get();

                            // Total Task Done
                            if(count($total_done_task)>0)
                            {
                                foreach($total_done_task as $item)
                                {
                                    $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                                    $end_date = $item->completed_at!=NULL ? date('Y-m-d',strtotime($item->completed_at)) : date('Y-m-d',strtotime($item->end_date_at));

                                    if(stripos($item->tat_type,'case')!==false)
                                    {
                                        $date_arr = [];

                                        $tat = $item->client_tat - 1;

                                        $incentive_tat = $item->client_tat - 1;

                                        if(stripos($item->days_type,'working')!==false)
                                        {
                                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                        }
                                        else if(stripos($item->days_type,'calender')!==false)
                                        {
                                            $holiday_master=DB::table('customer_holiday_masters')
                                                            ->distinct('date')
                                                            ->select('date')
                                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                            ->orderBy('date','asc')
                                                            ->get();
                                            if(count($holiday_master)>0)
                                            {
                                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                            }
                                            else
                                            {
                                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                            }
                                        }

                                        
                                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                        {
                                            $total_done_task_out = $total_done_task_out + 1;
                                        }
                                        else
                                        {
                                            $total_done_task_in = $total_done_task_in + 1;
                                        }

                                    }
                                    else if(stripos($item->tat_type,'check')!==false)
                                    {
                                        $date_arr=[];

                                        $tat = 1;

                                        $incentive_tat=1;

                                        $job_sla_item=DB::table('job_sla_items')
                                                        ->select('tat','incentive_tat')
                                                        ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                                        ->first();

                                        if($job_sla_item!=NULL)
                                        {
                                            $tat = $job_sla_item->tat;
                                            $incentive_tat = $job_sla_item->incentive_tat;
                                        }
                                        
                                        if(stripos($item->days_type,'working')!==false)
                                        {
                                            $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                        }
                                        else if(stripos($item->days_type,'calender')!==false)
                                        {
                                            $holiday_master=DB::table('customer_holiday_masters')
                                                            ->distinct('date')
                                                            ->select('date')
                                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                            ->orderBy('date','asc')
                                                            ->get();
                                            if(count($holiday_master)>0)
                                            {
                                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                                            }
                                            else
                                            {
                                                $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                            }
                                        }

                                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                        {
                                            $total_done_task_out = $total_done_task_out + 1;
                                        }
                                        else
                                        {
                                            $total_done_task_in = $total_done_task_in + 1;
                                        }


                                    }
                                }
                            }

                            $total_pending_task_in = 0;

                            $total_pending_task_out = 0;

                            $total_pending_task =DB::table('tasks as t')
                            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                            ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                            ->join('job_items as j','j.candidate_id','=','u.id')
                            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Task for Verification','ta.status'=>'1'])
                            ->whereNotNull('t.assigned_to')
                            ->whereIn('t.business_id',$kams)
                            ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                            if($request->input('from_date') !=""){
                                // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                                $total_pending_task->where(function($q) use ($request){
                                     $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                     ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                                 });
                            }
                            if($request->input('to_date') !=""){
                                // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                                $total_pending_task->where(function($q) use ($request){
                                     $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                     ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                                 });
                            }

                            $total_pending_task=$total_pending_task->get();

                            // Total Task Pending
                            if(count($total_pending_task)>0)
                            {
                                foreach($total_pending_task as $item)
                                {
                                    $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                                    $end_date = date('Y-m-d');

                                    if(stripos($item->tat_type,'case')!==false)
                                    {
                                        $date_arr = [];

                                        $tat = $item->client_tat - 1;

                                        $incentive_tat = $item->client_tat - 1;

                                        if(stripos($item->days_type,'working')!==false)
                                        {
                                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                        }
                                        else if(stripos($item->days_type,'calender')!==false)
                                        {
                                            $holiday_master=DB::table('customer_holiday_masters')
                                                            ->distinct('date')
                                                            ->select('date')
                                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                            ->orderBy('date','asc')
                                                            ->get();
                                            if(count($holiday_master)>0)
                                            {
                                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                            }
                                            else
                                            {
                                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                            }
                                        }

                                        
                                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                        {
                                            $total_pending_task_out = $total_pending_task_out + 1;
                                        }
                                        else
                                        {
                                            $total_pending_task_in = $total_pending_task_in + 1;
                                        }

                                    }
                                    else if(stripos($item->tat_type,'check')!==false)
                                    {
                                        $date_arr=[];

                                        $tat = 1;

                                        $incentive_tat=1;

                                        $job_sla_item=DB::table('job_sla_items')
                                                        ->select('tat','incentive_tat')
                                                        ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                                        ->first();

                                        if($job_sla_item!=NULL)
                                        {
                                            $tat = $job_sla_item->tat;
                                            $incentive_tat = $job_sla_item->incentive_tat;
                                        }
                                        
                                        if(stripos($item->days_type,'working')!==false)
                                        {
                                            $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                        }
                                        else if(stripos($item->days_type,'calender')!==false)
                                        {
                                            $holiday_master=DB::table('customer_holiday_masters')
                                                            ->distinct('date')
                                                            ->select('date')
                                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                            ->orderBy('date','asc')
                                                            ->get();
                                            if(count($holiday_master)>0)
                                            {
                                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                                            }
                                            else
                                            {
                                                $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                            }
                                        }

                                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                        {
                                            $total_pending_task_out = $total_pending_task_out + 1;
                                        }
                                        else
                                        {
                                            $total_pending_task_in = $total_pending_task_in + 1;
                                        }


                                    }

                                }
                            }

                            // Total Task Pending Insuff

                            $total_insuff_task = 0;

                            $total_insuff_task_in = 0;

                            $total_insuff_task_out = 0;

                            if(count($total_pending_task)>0)
                            {
                                foreach($total_pending_task as $item)
                                {
                                    $jaf_form_data = DB::table('jaf_form_data')->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id,'check_item_number'=>$item->number_of_verifications])->where('is_insufficiency','1')->first();

                                    if($jaf_form_data!=NULL)
                                    {
                                        $total_insuff_task = $total_insuff_task + 1;

                                        $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                                        $end_date = date('Y-m-d');

                                        if(stripos($item->tat_type,'case')!==false)
                                        {
                                            $date_arr = [];

                                            $tat = $item->client_tat - 1;

                                            $incentive_tat = $item->client_tat - 1;

                                            if(stripos($item->days_type,'working')!==false)
                                            {
                                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                            }
                                            else if(stripos($item->days_type,'calender')!==false)
                                            {
                                                $holiday_master=DB::table('customer_holiday_masters')
                                                                ->distinct('date')
                                                                ->select('date')
                                                                ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                                ->orderBy('date','asc')
                                                                ->get();
                                                if(count($holiday_master)>0)
                                                {
                                                    $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                                }
                                                else
                                                {
                                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                                }
                                            }

                                            
                                            if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                            {
                                                $total_insuff_task_out = $total_insuff_task_out + 1;
                                            }
                                            else
                                            {
                                                $total_insuff_task_in = $total_insuff_task_in + 1;
                                            }

                                        }
                                        else if(stripos($item->tat_type,'check')!==false)
                                        {
                                            $date_arr=[];

                                            $tat = 1;

                                            $incentive_tat=1;

                                            $job_sla_item=DB::table('job_sla_items')
                                                            ->select('tat','incentive_tat')
                                                            ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                                            ->first();

                                            if($job_sla_item!=NULL)
                                            {
                                                $tat = $job_sla_item->tat;
                                                $incentive_tat = $job_sla_item->incentive_tat;
                                            }
                                            
                                            if(stripos($item->days_type,'working')!==false)
                                            {
                                                $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                            }
                                            else if(stripos($item->days_type,'calender')!==false)
                                            {
                                                $holiday_master=DB::table('customer_holiday_masters')
                                                                ->distinct('date')
                                                                ->select('date')
                                                                ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                                ->orderBy('date','asc')
                                                                ->get();
                                                if(count($holiday_master)>0)
                                                {
                                                    $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                                                }
                                                else
                                                {
                                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                                }
                                            }

                                            if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                            {
                                                $total_insuff_task_out = $total_insuff_task_out + 1;
                                            }
                                            else
                                            {
                                                $total_insuff_task_in = $total_insuff_task_in + 1;
                                            }


                                        }

                                    }
                                }
                            }

                            $task_verification_result[]=[
                                            'user_id'=>$u_id,
                                            'allocated'=>count($total_allocated_task),
                                            'completed'=>count($total_done_task),
                                            'completed_in'=>$total_done_task_in,
                                            'completed_out'=>$total_done_task_out,
                                            'pending'   => count($total_pending_task),
                                            'pending_in' => $total_pending_task_in,
                                            'pending_out'   => $total_pending_task_out,
                                            'insuff'    => $total_insuff_task,
                                            'insuff_in' => $total_insuff_task_in,
                                            'insuff_out'  => $total_insuff_task_out
                                        ];

                            // Task Report Report

                            $total_allocated_task = DB::table('tasks as t')
                            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                            ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                            ->join('job_items as j','j.candidate_id','=','u.id')
                            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Report generation'])
                            ->whereIn('ta.status',['1','2'])
                            ->whereNotNull('t.assigned_to')
                            ->whereIn('t.business_id',$kams)
                            ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                            if($request->input('from_date') !=""){
                                // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                                $total_allocated_task->where(function($q) use ($request){
                                     $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                     ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                                 });
                            }
                            if($request->input('to_date') !=""){
                                // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                                $total_allocated_task->where(function($q) use ($request){
                                     $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                     ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                                 });
                            }
        
                            $total_allocated_task=$total_allocated_task->get();
        
                            $total_done_task_in = 0;
        
                            $total_done_task_out = 0;
        
                            $total_done_task =DB::table('tasks as t')
                                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                                ->join('job_items as j','j.candidate_id','=','u.id')
                                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'2','t.description'=>'Report generation'])
                                ->whereNotNull('t.assigned_to')
                                ->whereIn('t.business_id',$kams)
                                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                                if($request->input('from_date') !=""){
                                    // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                                    $total_done_task->where(function($q) use ($request){
                                         $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                         ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                                     });
                                }
                                if($request->input('to_date') !=""){
                                    // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                                    $total_done_task->where(function($q) use ($request){
                                         $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                         ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                                     });
                                }
        
                            $total_done_task=$total_done_task->get();

                            // Total Task Done
                            if(count($total_done_task)>0)
                            {
                                foreach($total_done_task as $item)
                                {
                                    $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                                    $end_date = $item->completed_at!=NULL ? date('Y-m-d',strtotime($item->completed_at)) : date('Y-m-d',strtotime($item->end_date_at));

                                    
                                        $date_arr = [];

                                        $tat = $item->client_tat - 1;

                                        $incentive_tat = $item->client_tat - 1;

                                        if(stripos($item->days_type,'working')!==false)
                                        {
                                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                        }
                                        else if(stripos($item->days_type,'calender')!==false)
                                        {
                                            $holiday_master=DB::table('customer_holiday_masters')
                                                            ->distinct('date')
                                                            ->select('date')
                                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                            ->orderBy('date','asc')
                                                            ->get();
                                            if(count($holiday_master)>0)
                                            {
                                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                            }
                                            else
                                            {
                                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                            }
                                        }

                                        
                                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                        {
                                            $total_done_task_out = $total_done_task_out + 1;
                                        }
                                        else
                                        {
                                            $total_done_task_in = $total_done_task_in + 1;
                                        }

                                
                                    
                                }
                            }
        
                            $total_pending_task_in = 0;
        
                            $total_pending_task_out = 0;
        
                            $total_pending_task =DB::table('tasks as t')
                            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                            ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                            ->join('job_items as j','j.candidate_id','=','u.id')
                            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Report generation','ta.status'=>'1'])
                            ->whereNotNull('t.assigned_to')
                            ->whereIn('t.business_id',$kams)
                            ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                            if($request->input('from_date') !=""){
                                // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                                $total_pending_task->where(function($q) use ($request){
                                     $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                     ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                                 });
                            }
                            if($request->input('to_date') !=""){
                                // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                                $total_pending_task->where(function($q) use ($request){
                                     $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                     ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                                 });
                            }
        
                            $total_pending_task=$total_pending_task->get();
        
                            // // Total Task Pending
                            if(count($total_pending_task)>0)
                            {
                                foreach($total_pending_task as $item)
                                {
                                    $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));
        
                                    $end_date = date('Y-m-d');
        
                                    
                                        $date_arr = [];
        
                                        $tat = $item->client_tat - 1;
        
                                        $incentive_tat = $item->client_tat - 1;
        
                                        if(stripos($item->days_type,'working')!==false)
                                        {
                                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                        }
                                        else if(stripos($item->days_type,'calender')!==false)
                                        {
                                            $holiday_master=DB::table('customer_holiday_masters')
                                                            ->distinct('date')
                                                            ->select('date')
                                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                            ->orderBy('date','asc')
                                                            ->get();
                                            if(count($holiday_master)>0)
                                            {
                                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                            }
                                            else
                                            {
                                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                            }
                                        }
        
                                        
                                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                        {
                                            $total_pending_task_out = $total_pending_task_out + 1;
                                        }
                                        else
                                        {
                                            $total_pending_task_in = $total_pending_task_in + 1;
                                        }
        
                                    
        
                                }
                            }
        
                            // // Total Task Pending Insuff
        
                            // $total_insuff_task = 0;
        
                            // $total_insuff_task_in = 0;
        
                            // $total_insuff_task_out = 0;
        
                            // if(count($total_pending_task)>0)
                            // {
                            //     foreach($total_pending_task as $item)
                            //     {
                            //         $jaf_form_data = DB::table('jaf_form_data')->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id,'check_item_number'=>$item->number_of_verifications])->where('is_insufficiency','1')->first();
        
                            //         if($jaf_form_data!=NULL)
                            //         {
                            //             $total_insuff_task = $total_insuff_task + 1;
        
                            //             $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));
        
                            //             $end_date = date('Y-m-d');
        
                                        
                            //                 $date_arr = [];
        
                            //                 $tat = $item->client_tat - 1;
        
                            //                 $incentive_tat = $item->client_tat - 1;
        
                            //                 if(stripos($item->days_type,'working')!==false)
                            //                 {
                            //                     $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                            //                 }
                            //                 else if(stripos($item->days_type,'calender')!==false)
                            //                 {
                            //                     $holiday_master=DB::table('customer_holiday_masters')
                            //                                     ->distinct('date')
                            //                                     ->select('date')
                            //                                     ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                            //                                     ->orderBy('date','asc')
                            //                                     ->get();
                            //                     if(count($holiday_master)>0)
                            //                     {
                            //                         $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                            //                     }
                            //                     else
                            //                     {
                            //                         $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                            //                     }
                            //                 }
        
                                            
                            //                 if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                            //                 {
                            //                     $total_insuff_task_out = $total_insuff_task_out + 1;
                            //                 }
                            //                 else
                            //                 {
                            //                     $total_insuff_task_in = $total_insuff_task_in + 1;
                            //                 }
                                        
                            //         }
                            //     }
                            // }
        
                            $task_report_result[]=[
                                'service'=>$u_name,
                                'allocated'=>count($total_allocated_task),
                                'completed'=>count($total_done_task),
                                'completed_in'=>$total_done_task_in,
                                'completed_out'=>$total_done_task_out,
                                'pending'   => count($total_pending_task),
                                'pending_in' => $total_pending_task_in,
                                'pending_out'   => $total_pending_task_out,
                                // 'insuff'    => $total_insuff_task,
                                // 'insuff_in' => $total_insuff_task_in,
                                // 'insuff_out'  => $total_insuff_task_out
                            ];
                }
            }
            
        }
        else
        {
            // Task JAF Result

            $total_allocated_task = DB::table('tasks as t')
                    ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                    ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                    ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'JAF Filling'])
                    ->whereIn('ta.status',['1','2'])
                    ->whereNotNull('t.assigned_to')
                    ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                    if($request->input('from_date') !=""){
                        // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                        $total_allocated_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                             ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                         });
                    }
                    if($request->input('to_date') !=""){
                        // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                        $total_allocated_task->where(function($q) use ($request){
                            $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                            ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                        });
                    }

            $total_allocated_task=$total_allocated_task->get();

            $total_done_task_in = 0;

            $total_done_task_out = 0;

            $total_done_task =DB::table('tasks as t')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                ->join('job_items as j','j.candidate_id','=','u.id')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'2','t.description'=>'JAF Filling'])
                ->whereNotNull('t.assigned_to')
                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                if($request->input('from_date') !=""){
                    // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                    $total_done_task->where(function($q) use ($request){
                        $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                        ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                    });
                }
                if($request->input('to_date') !=""){
                    // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                    $total_done_task->where(function($q) use ($request){
                        $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                        ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                    });
                }

            $total_done_task=$total_done_task->get();

            // // Total Task Done
            if(count($total_done_task)>0)
            {
                foreach($total_done_task as $item)
                {
                    $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                    $end_date = $item->completed_at!=NULL ? date('Y-m-d',strtotime($item->completed_at)) : date('Y-m-d',strtotime($item->end_date_at));

                    
                        $date_arr = [];

                        $tat = $item->client_tat - 1;

                        $incentive_tat = $item->client_tat - 1;

                        if(stripos($item->days_type,'working')!==false)
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                        }
                        else if(stripos($item->days_type,'calender')!==false)
                        {
                            $holiday_master=DB::table('customer_holiday_masters')
                                            ->distinct('date')
                                            ->select('date')
                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                            ->orderBy('date','asc')
                                            ->get();
                            if(count($holiday_master)>0)
                            {
                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                            }
                            else
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                            }
                        }

                        
                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                        {
                            $total_done_task_out = $total_done_task_out + 1;
                        }
                        else
                        {
                            $total_done_task_in = $total_done_task_in + 1;
                        }

                    
                }
            }

            $total_pending_task_in = 0;

            $total_pending_task_out = 0;

            $total_pending_task =DB::table('tasks as t')
            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
            ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
            ->join('job_items as j','j.candidate_id','=','u.id')
            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'JAF Filling','ta.status'=>'1'])
            ->whereNotNull('t.assigned_to')
            ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
            if($request->input('from_date') !=""){
                // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                $total_pending_task->where(function($q) use ($request){
                    $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                    ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                });
            }
            if($request->input('to_date') !=""){
                // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                $total_pending_task->where(function($q) use ($request){
                    $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                    ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                });
            }

            $total_pending_task=$total_pending_task->get();

            // Total Task Pending
            if(count($total_pending_task)>0)
            {
                foreach($total_pending_task as $item)
                {
                    $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                    $end_date = date('Y-m-d');

                    
                    $date_arr = [];

                    $tat = $item->client_tat - 1;

                    $incentive_tat = $item->client_tat - 1;

                    if(stripos($item->days_type,'working')!==false)
                    {
                        $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                    }
                    else if(stripos($item->days_type,'calender')!==false)
                    {
                        $holiday_master=DB::table('customer_holiday_masters')
                                        ->distinct('date')
                                        ->select('date')
                                        ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                        ->orderBy('date','asc')
                                        ->get();
                        if(count($holiday_master)>0)
                        {
                            $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                        }
                        else
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                        }
                    }

                    
                    if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                    {
                        $total_pending_task_out = $total_pending_task_out + 1;
                    }
                    else
                    {
                        $total_pending_task_in = $total_pending_task_in + 1;
                    }

                }
            }

            $task_jaf_result[]=[
                'service'=>'JAF Filling',
                'allocated'=>count($total_allocated_task),
                'completed'=>count($total_done_task),
                'completed_in'=>$total_done_task_in,
                'completed_out'=>$total_done_task_out,
                'pending'   => count($total_pending_task),
                'pending_in' => $total_pending_task_in,
                'pending_out'   => $total_pending_task_out
            ];

            $services = [];

            $user_check=DB::table('user_checks')->where('user_id',$user_id)->get()->pluck('checks')->all();

            $services = DB::table('services as s')->select('s.name','s.id')->whereIn('s.id',$user_check)->orderBy('s.sort_number','asc')->get();

            // Task Verification Result
            foreach($services as $service)
            {
                $total_allocated_task = DB::table('tasks as t')
                    ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                    ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                    ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Task for Verification','ta.service_id'=>$service->id])
                    ->whereIn('ta.status',['1','2'])
                    ->whereNotNull('t.assigned_to')
                    ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                    if($request->input('from_date') !=""){
                        // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                        $total_allocated_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                             ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                         });
                     }
                     if($request->input('to_date') !=""){
                        // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                        $total_allocated_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                             ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                         });
                     }
                $total_allocated_task=$total_allocated_task->get();

                $total_done_task_in = 0;

                $total_done_task_out = 0;

                $total_done_task =DB::table('tasks as t')
                    ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                    ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                    ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'2','t.description'=>'Task for Verification','ta.service_id'=>$service->id])
                    ->whereNotNull('t.assigned_to')
                    ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                    if($request->input('from_date') !=""){
                        // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                        $total_done_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                             ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                         });
                     }
                     if($request->input('to_date') !=""){
                        // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                        $total_done_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                             ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                         });
                     }

                $total_done_task=$total_done_task->get();

                // Total Task Done
                if(count($total_done_task)>0)
                {
                    foreach($total_done_task as $item)
                    {
                        $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                        $end_date = $item->completed_at!=NULL ? date('Y-m-d',strtotime($item->completed_at)) : date('Y-m-d',strtotime($item->end_date_at));

                        if(stripos($item->tat_type,'case')!==false)
                        {
                            $date_arr = [];

                            $tat = $item->client_tat - 1;

                            $incentive_tat = $item->client_tat - 1;

                            if(stripos($item->days_type,'working')!==false)
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                            }
                            else if(stripos($item->days_type,'calender')!==false)
                            {
                                $holiday_master=DB::table('customer_holiday_masters')
                                                ->distinct('date')
                                                ->select('date')
                                                ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                ->orderBy('date','asc')
                                                ->get();
                                if(count($holiday_master)>0)
                                {
                                    $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                }
                                else
                                {
                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                }
                            }

                            
                            if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                            {
                                $total_done_task_out = $total_done_task_out + 1;
                            }
                            else
                            {
                                $total_done_task_in = $total_done_task_in + 1;
                            }

                        }
                        else if(stripos($item->tat_type,'check')!==false)
                        {
                            $date_arr=[];

                            $tat = 1;

                            $incentive_tat=1;

                            $job_sla_item=DB::table('job_sla_items')
                                            ->select('tat','incentive_tat')
                                            ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                            ->first();

                            if($job_sla_item!=NULL)
                            {
                                $tat = $job_sla_item->tat;
                                $incentive_tat = $job_sla_item->incentive_tat;
                            }
                            
                            if(stripos($item->days_type,'working')!==false)
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                            }
                            else if(stripos($item->days_type,'calender')!==false)
                            {
                                $holiday_master=DB::table('customer_holiday_masters')
                                                ->distinct('date')
                                                ->select('date')
                                                ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                ->orderBy('date','asc')
                                                ->get();
                                if(count($holiday_master)>0)
                                {
                                    $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                                }
                                else
                                {
                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                }
                            }

                            if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                            {
                                $total_done_task_out = $total_done_task_out + 1;
                            }
                            else
                            {
                                $total_done_task_in = $total_done_task_in + 1;
                            }


                        }
                    }
                }

                $total_pending_task_in = 0;

                $total_pending_task_out = 0;

                $total_pending_task =DB::table('tasks as t')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                ->join('job_items as j','j.candidate_id','=','u.id')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Task for Verification','ta.status'=>'1','ta.service_id'=>$service->id])
                ->whereNotNull('t.assigned_to')
                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                if($request->input('from_date') !=""){
                    // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                    $total_pending_task->where(function($q) use ($request){
                         $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                         ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                     });
                 }
                 if($request->input('to_date') !=""){
                    // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                    $total_pending_task->where(function($q) use ($request){
                         $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                         ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                     });
                 }

                $total_pending_task=$total_pending_task->get();

                // Total Task Pending
                if(count($total_pending_task)>0)
                {
                    foreach($total_pending_task as $item)
                    {
                        $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                        $end_date = date('Y-m-d');

                        if(stripos($item->tat_type,'case')!==false)
                        {
                            $date_arr = [];

                            $tat = $item->client_tat - 1;

                            $incentive_tat = $item->client_tat - 1;

                            if(stripos($item->days_type,'working')!==false)
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                            }
                            else if(stripos($item->days_type,'calender')!==false)
                            {
                                $holiday_master=DB::table('customer_holiday_masters')
                                                ->distinct('date')
                                                ->select('date')
                                                ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                ->orderBy('date','asc')
                                                ->get();
                                if(count($holiday_master)>0)
                                {
                                    $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                }
                                else
                                {
                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                }
                            }

                            
                            if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                            {
                                $total_pending_task_out = $total_pending_task_out + 1;
                            }
                            else
                            {
                                $total_pending_task_in = $total_pending_task_in + 1;
                            }

                        }
                        else if(stripos($item->tat_type,'check')!==false)
                        {
                            $date_arr=[];

                            $tat = 1;

                            $incentive_tat=1;

                            $job_sla_item=DB::table('job_sla_items')
                                            ->select('tat','incentive_tat')
                                            ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                            ->first();

                            if($job_sla_item!=NULL)
                            {
                                $tat = $job_sla_item->tat;
                                $incentive_tat = $job_sla_item->incentive_tat;
                            }
                            
                            if(stripos($item->days_type,'working')!==false)
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                            }
                            else if(stripos($item->days_type,'calender')!==false)
                            {
                                $holiday_master=DB::table('customer_holiday_masters')
                                                ->distinct('date')
                                                ->select('date')
                                                ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                ->orderBy('date','asc')
                                                ->get();
                                if(count($holiday_master)>0)
                                {
                                    $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                                }
                                else
                                {
                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                }
                            }

                            if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                            {
                                $total_pending_task_out = $total_pending_task_out + 1;
                            }
                            else
                            {
                                $total_pending_task_in = $total_pending_task_in + 1;
                            }


                        }

                    }
                }

                // Total Task Pending Insuff

                $total_insuff_task = 0;

                $total_insuff_task_in = 0;

                $total_insuff_task_out = 0;

                if(count($total_pending_task)>0)
                {
                    foreach($total_pending_task as $item)
                    {
                        $jaf_form_data = DB::table('jaf_form_data')->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id,'check_item_number'=>$item->number_of_verifications])->where('is_insufficiency','1')->first();

                        if($jaf_form_data!=NULL)
                        {
                            $total_insuff_task = $total_insuff_task + 1;

                            $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                            $end_date = date('Y-m-d');

                            if(stripos($item->tat_type,'case')!==false)
                            {
                                $date_arr = [];

                                $tat = $item->client_tat - 1;

                                $incentive_tat = $item->client_tat - 1;

                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                }
                                else if(stripos($item->days_type,'calender')!==false)
                                {
                                    $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                    ->orderBy('date','asc')
                                                    ->get();
                                    if(count($holiday_master)>0)
                                    {
                                        $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                    }
                                    else
                                    {
                                        $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                    }
                                }

                                
                                if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                {
                                    $total_insuff_task_out = $total_insuff_task_out + 1;
                                }
                                else
                                {
                                    $total_insuff_task_in = $total_insuff_task_in + 1;
                                }

                            }
                            else if(stripos($item->tat_type,'check')!==false)
                            {
                                $date_arr=[];

                                $tat = 1;

                                $incentive_tat=1;

                                $job_sla_item=DB::table('job_sla_items')
                                                ->select('tat','incentive_tat')
                                                ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                                ->first();

                                if($job_sla_item!=NULL)
                                {
                                    $tat = $job_sla_item->tat;
                                    $incentive_tat = $job_sla_item->incentive_tat;
                                }
                                
                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                }
                                else if(stripos($item->days_type,'calender')!==false)
                                {
                                    $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                    ->orderBy('date','asc')
                                                    ->get();
                                    if(count($holiday_master)>0)
                                    {
                                        $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                                    }
                                    else
                                    {
                                        $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                    }
                                }

                                if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                {
                                    $total_insuff_task_out = $total_insuff_task_out + 1;
                                }
                                else
                                {
                                    $total_insuff_task_in = $total_insuff_task_in + 1;
                                }


                            }

                        }
                    }
                }

                $task_verification_result[]=[
                                'service'=>$service->name,
                                'service_id' => $service->id,
                                'allocated'=>count($total_allocated_task),
                                'completed'=>count($total_done_task),
                                'completed_in'=>$total_done_task_in,
                                'completed_out'=>$total_done_task_out,
                                'pending'   => count($total_pending_task),
                                'pending_in' => $total_pending_task_in,
                                'pending_out'   => $total_pending_task_out,
                                'insuff'    => $total_insuff_task,
                                'insuff_in' => $total_insuff_task_in,
                                'insuff_out'  => $total_insuff_task_out
                            ];
            }

            // Task Report Report

            $total_allocated_task = DB::table('tasks as t')
                    ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                    ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                    ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Report generation'])
                    ->whereIn('ta.status',['1','2'])
                    ->whereNotNull('t.assigned_to')
                    ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                    if($request->input('from_date') !=""){
                        // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                        $total_allocated_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                             ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                         });
                     }
                     if($request->input('to_date') !=""){
                        // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                        $total_allocated_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                             ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                         });
                     }

            $total_allocated_task=$total_allocated_task->get();

            $total_done_task_in = 0;

            $total_done_task_out = 0;

            $total_done_task =DB::table('tasks as t')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                ->join('job_items as j','j.candidate_id','=','u.id')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'2','t.description'=>'Report generation'])
                ->whereNotNull('t.assigned_to')
                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                    if($request->input('from_date') !=""){
                    // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                    $total_done_task->where(function($q) use ($request){
                            $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                            ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                        });
                    }
                    if($request->input('to_date') !=""){
                    // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                    $total_done_task->where(function($q) use ($request){
                            $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                            ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                        });
                    }

            $total_done_task=$total_done_task->get();

            // // Total Task Done
            if(count($total_done_task)>0)
            {
                foreach($total_done_task as $item)
                {
                    $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                    $end_date = $item->completed_at!=NULL ? date('Y-m-d',strtotime($item->completed_at)) : date('Y-m-d',strtotime($item->end_date_at));

                    
                        $date_arr = [];

                        $tat = $item->client_tat - 1;

                        $incentive_tat = $item->client_tat - 1;

                        if(stripos($item->days_type,'working')!==false)
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                        }
                        else if(stripos($item->days_type,'calender')!==false)
                        {
                            $holiday_master=DB::table('customer_holiday_masters')
                                            ->distinct('date')
                                            ->select('date')
                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                            ->orderBy('date','asc')
                                            ->get();
                            if(count($holiday_master)>0)
                            {
                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                            }
                            else
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                            }
                        }

                        
                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                        {
                            $total_done_task_out = $total_done_task_out + 1;
                        }
                        else
                        {
                            $total_done_task_in = $total_done_task_in + 1;
                        }

                    
                }
            }

            $total_pending_task_in = 0;

            $total_pending_task_out = 0;

            $total_pending_task =DB::table('tasks as t')
            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
            ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
            ->join('job_items as j','j.candidate_id','=','u.id')
            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Report generation','ta.status'=>'1'])
            ->whereNotNull('t.assigned_to')
            ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                if($request->input('from_date') !=""){
                // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                $total_pending_task->where(function($q) use ($request){
                        $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                        ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                    });
                }
                if($request->input('to_date') !=""){
                // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                $total_pending_task->where(function($q) use ($request){
                        $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                        ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                    });
                }

            $total_pending_task=$total_pending_task->get();

            // // Total Task Pending
            if(count($total_pending_task)>0)
            {
                foreach($total_pending_task as $item)
                {
                    $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                    $end_date = date('Y-m-d');

                    
                        $date_arr = [];

                        $tat = $item->client_tat - 1;

                        $incentive_tat = $item->client_tat - 1;

                        if(stripos($item->days_type,'working')!==false)
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                        }
                        else if(stripos($item->days_type,'calender')!==false)
                        {
                            $holiday_master=DB::table('customer_holiday_masters')
                                            ->distinct('date')
                                            ->select('date')
                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                            ->orderBy('date','asc')
                                            ->get();
                            if(count($holiday_master)>0)
                            {
                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                            }
                            else
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                            }
                        }

                        
                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                        {
                            $total_pending_task_out = $total_pending_task_out + 1;
                        }
                        else
                        {
                            $total_pending_task_in = $total_pending_task_in + 1;
                        }

                    

                }
            }

            // // Total Task Pending Insuff

            // $total_insuff_task = 0;

            // $total_insuff_task_in = 0;

            // $total_insuff_task_out = 0;

            // if(count($total_pending_task)>0)
            // {
            //     foreach($total_pending_task as $item)
            //     {
            //         $jaf_form_data = DB::table('jaf_form_data')->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id,'check_item_number'=>$item->number_of_verifications])->where('is_insufficiency','1')->first();

            //         if($jaf_form_data!=NULL)
            //         {
            //             $total_insuff_task = $total_insuff_task + 1;

            //             $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

            //             $end_date = date('Y-m-d');

                        
            //                 $date_arr = [];

            //                 $tat = $item->client_tat - 1;

            //                 $incentive_tat = $item->client_tat - 1;

            //                 if(stripos($item->days_type,'working')!==false)
            //                 {
            //                     $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
            //                 }
            //                 else if(stripos($item->days_type,'calender')!==false)
            //                 {
            //                     $holiday_master=DB::table('customer_holiday_masters')
            //                                     ->distinct('date')
            //                                     ->select('date')
            //                                     ->where(['business_id'=>$item->user_parent_id,'status'=>1])
            //                                     ->orderBy('date','asc')
            //                                     ->get();
            //                     if(count($holiday_master)>0)
            //                     {
            //                         $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
            //                     }
            //                     else
            //                     {
            //                         $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
            //                     }
            //                 }

                            
            //                 if(strtotime($end_date) > strtotime($date_arr['tat_date']))
            //                 {
            //                     $total_insuff_task_out = $total_insuff_task_out + 1;
            //                 }
            //                 else
            //                 {
            //                     $total_insuff_task_in = $total_insuff_task_in + 1;
            //                 }

                        
                        

            //         }
            //     }
            // }

            $task_report_result[]=[
                'service'=>'Report Writing',
                'allocated'=>count($total_allocated_task),
                'completed'=>count($total_done_task),
                'completed_in'=>$total_done_task_in,
                'completed_out'=>$total_done_task_out,
                'pending'   => count($total_pending_task),
                'pending_in' => $total_pending_task_in,
                'pending_out'   => $total_pending_task_out,
                // 'insuff'    => $total_insuff_task,
                // 'insuff_in' => $total_insuff_task_in,
                // 'insuff_out'  => $total_insuff_task_out
            ];
        }

        $viewRender = view('admin.dashboard.team-lead-dashboard',compact('task_jaf_result','task_verification_result','task_report_result','user_arr','business_id','kams'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));
    }

    public function dashboardTeamLeadJafFill(Request $request)
    {
        $user_id = Auth::user()->id;
        $business_id=Auth::user()->business_id;
        $user_type = Auth::user()->user_type;

        $kams = DB::table('key_account_managers')
                    ->select('*')
                    ->where('user_id',$user_id)
                    ->get()->pluck('business_id')->all();

        $services = DB::table('services')
                    ->select('name','id')
                    ->where(['status'=>'1'])
                    ->where('business_id',NULL)
                    ->whereNotIn('type_name',['e_court','gstin'])
                    ->orwhere('business_id',Auth::user()->business_id)
                    ->orderBy('sort_number','asc')
                    ->get();
        
        $customer = DB::table('users')
                    ->where(['id'=>$business_id,'user_type'=>'customer'])
                    ->first();

        $user_arr = DB::table('users')
                    ->where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])
                    ->orderBy('name','asc')
                    ->pluck('name','id')->all();
        
        $user_arr = array($customer->id => $customer->name.' (Customer)') + $user_arr;
        $task_jaf_result = [];

        if(count($kams)>0)
        {
            if(count($user_arr)>0)
            {
                foreach($user_arr as $u_id => $u_name)
                {
                    // Task JAF Result

                    $total_allocated_task = DB::table('tasks as t')
                    ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                    ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                    ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'JAF Filling'])
                    ->whereIn('ta.status',['1','2'])
                    ->whereNotNull('t.assigned_to')
                    ->whereIn('t.business_id',$kams)
                    ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                    if($request->input('from_date') !=""){
                        // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                        $total_allocated_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                             ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                         });
                    }
                    if($request->input('to_date') !=""){
                        // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                        $total_allocated_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                             ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                         });
                    }
    
                    $total_allocated_task=$total_allocated_task->get();
    
                    $total_done_task_in = 0;
    
                    $total_done_task_out = 0;
    
                    $total_done_task =DB::table('tasks as t')
                        ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                        ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                        ->join('job_items as j','j.candidate_id','=','u.id')
                        ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                        ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'2','t.description'=>'JAF Filling'])
                        ->whereNotNull('t.assigned_to')
                        ->whereIn('t.business_id',$kams)
                        ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                        if($request->input('from_date') !=""){
                            // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                            $total_done_task->where(function($q) use ($request){
                                 $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                 ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                             });
                        }
                        if($request->input('to_date') !=""){
                            // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                            $total_done_task->where(function($q) use ($request){
                                 $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                 ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                             });
                        }
    
                    $total_done_task=$total_done_task->get();
    
                    // // Total Task Done
                    if(count($total_done_task)>0)
                    {
                        foreach($total_done_task as $item)
                        {
                            $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));
    
                            $end_date = $item->completed_at!=NULL ? date('Y-m-d',strtotime($item->completed_at)) : date('Y-m-d',strtotime($item->end_date_at));
    
                            
                                $date_arr = [];
    
                                $tat = $item->client_tat - 1;
    
                                $incentive_tat = $item->client_tat - 1;
    
                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                }
                                else if(stripos($item->days_type,'calender')!==false)
                                {
                                    $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                    ->orderBy('date','asc')
                                                    ->get();
                                    if(count($holiday_master)>0)
                                    {
                                        $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                    }
                                    else
                                    {
                                        $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                    }
                                }
    
                                
                                if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                {
                                    $total_done_task_out = $total_done_task_out + 1;
                                }
                                else
                                {
                                    $total_done_task_in = $total_done_task_in + 1;
                                }
    
                            
                        }
                    }
    
                    $total_pending_task_in = 0;
    
                    $total_pending_task_out = 0;
    
                    $total_pending_task =DB::table('tasks as t')
                    ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                    ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                    ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'JAF Filling','ta.status'=>'1'])
                    ->whereNotNull('t.assigned_to')
                    ->whereIn('t.business_id',$kams)
                    ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                    if($request->input('from_date') !=""){
                        // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                        $total_pending_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                             ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                         });
                    }
                    if($request->input('to_date') !=""){
                        // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                        $total_pending_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                             ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                         });
                    }
    
                    $total_pending_task=$total_pending_task->get();
    
                    // Total Task Pending
                    if(count($total_pending_task)>0)
                    {
                        foreach($total_pending_task as $item)
                        {
                            $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));
    
                            $end_date = date('Y-m-d');
    
                            
                            $date_arr = [];
    
                            $tat = $item->client_tat - 1;
    
                            $incentive_tat = $item->client_tat - 1;
    
                            if(stripos($item->days_type,'working')!==false)
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                            }
                            else if(stripos($item->days_type,'calender')!==false)
                            {
                                $holiday_master=DB::table('customer_holiday_masters')
                                                ->distinct('date')
                                                ->select('date')
                                                ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                ->orderBy('date','asc')
                                                ->get();
                                if(count($holiday_master)>0)
                                {
                                    $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                }
                                else
                                {
                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                }
                            }
    
                            
                            if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                            {
                                $total_pending_task_out = $total_pending_task_out + 1;
                            }
                            else
                            {
                                $total_pending_task_in = $total_pending_task_in + 1;
                            }
    
                        }
                    }

                    $task_jaf_result[]=[
                        'service'=>$u_name,
                        'user_id' => $u_id,
                        'allocated'=>count($total_allocated_task),
                        'completed'=>count($total_done_task),
                        'completed_in'=>$total_done_task_in,
                        'completed_out'=>$total_done_task_out,
                        'pending'   => count($total_pending_task),
                        'pending_in' => $total_pending_task_in,
                        'pending_out'   => $total_pending_task_out
                    ];
                }
            }
        }
        else if($user_type == 'customer')
        {
            
            if(count($user_arr)>0)
            {
                foreach($user_arr as $u_id => $u_name)
                {
                    // Task JAF Result

                    $total_allocated_task = DB::table('tasks as t')
                            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                            ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                            ->join('job_items as j','j.candidate_id','=','u.id')
                            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'JAF Filling'])
                            ->whereIn('ta.status',['1','2'])
                            ->whereNotNull('t.assigned_to')
                            ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');

                    if($request->input('from_date') !=""){
                        // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                        $total_allocated_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                             ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                         });
                    }

                    if($request->input('to_date') !=""){
                        // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                        $total_allocated_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                             ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                         });
                    }
    
                    $total_allocated_task=$total_allocated_task->get();
    
                    $total_done_task_in = 0;
    
                    $total_done_task_out = 0;
    
                    $total_done_task =DB::table('tasks as t')
                        ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                        ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                        ->join('job_items as j','j.candidate_id','=','u.id')
                        ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                        ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'2','t.description'=>'JAF Filling'])
                        ->whereNotNull('t.assigned_to')
                        ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                        if($request->input('from_date') !=""){
                            // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                            $total_done_task->where(function($q) use ($request){
                                 $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                 ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                             });
                        }
                        if($request->input('to_date') !=""){
                            // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                            $total_done_task->where(function($q) use ($request){
                                 $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                 ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                             });
                        }
    
                    $total_done_task=$total_done_task->get();
    
                    // // Total Task Done
                    if(count($total_done_task)>0)
                    {
                        foreach($total_done_task as $item)
                        {
                            $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));
    
                            $end_date = $item->completed_at!=NULL ? date('Y-m-d',strtotime($item->completed_at)) : date('Y-m-d',strtotime($item->end_date_at));
    
                            
                                $date_arr = [];
    
                                $tat = $item->client_tat - 1;
    
                                $incentive_tat = $item->client_tat - 1;
    
                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                }
                                else if(stripos($item->days_type,'calender')!==false)
                                {
                                    $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                    ->orderBy('date','asc')
                                                    ->get();
                                    if(count($holiday_master)>0)
                                    {
                                        $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                    }
                                    else
                                    {
                                        $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                    }
                                }
    
                                
                                if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                {
                                    $total_done_task_out = $total_done_task_out + 1;
                                }
                                else
                                {
                                    $total_done_task_in = $total_done_task_in + 1;
                                }
                        }
                    }
    
                    $total_pending_task_in = 0;
    
                    $total_pending_task_out = 0;
    
                    $total_pending_task =DB::table('tasks as t')
                    ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                    ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                    ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'JAF Filling','ta.status'=>'1'])
                    ->whereNotNull('t.assigned_to')
                    ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                    if($request->input('from_date') !=""){
                        // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                        $total_pending_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                             ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                         });
                    }
                    if($request->input('to_date') !=""){
                        // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                        $total_pending_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                             ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                         });
                    }
    
                    $total_pending_task=$total_pending_task->get();
    
                    // Total Task Pending
                    if(count($total_pending_task)>0)
                    {
                        foreach($total_pending_task as $item)
                        {
                            $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));
    
                            $end_date = date('Y-m-d');
    
                            
                            $date_arr = [];
    
                            $tat = $item->client_tat - 1;
    
                            $incentive_tat = $item->client_tat - 1;
    
                            if(stripos($item->days_type,'working')!==false)
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                            }
                            else if(stripos($item->days_type,'calender')!==false)
                            {
                                $holiday_master=DB::table('customer_holiday_masters')
                                                ->distinct('date')
                                                ->select('date')
                                                ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                ->orderBy('date','asc')
                                                ->get();
                                if(count($holiday_master)>0)
                                {
                                    $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                }
                                else
                                {
                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                }
                            }
    
                            
                            if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                            {
                                $total_pending_task_out = $total_pending_task_out + 1;
                            }
                            else
                            {
                                $total_pending_task_in = $total_pending_task_in + 1;
                            }
    
                        }
                    }

                    $task_jaf_result[]=[
                        'service'=>$u_name,
                        'user_id' => $u_id,
                        'allocated'=>count($total_allocated_task),
                        'completed'=>count($total_done_task),
                        'completed_in'=>$total_done_task_in,
                        'completed_out'=>$total_done_task_out,
                        'pending'   => count($total_pending_task),
                        'pending_in' => $total_pending_task_in,
                        'pending_out'   => $total_pending_task_out
                    ];
                }
            }
        }
        else
        {
            // Task JAF Result

            $total_allocated_task = DB::table('tasks as t')
                    ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                    ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                    ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'JAF Filling'])
                    ->whereIn('ta.status',['1','2'])
                    ->whereNotNull('t.assigned_to')
                    ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                    if($request->input('from_date') !=""){
                        // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                        $total_allocated_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                             ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                         });
                     }
                     if($request->input('to_date') !=""){
                        // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                        $total_allocated_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                             ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                         });
                     }

            $total_allocated_task=$total_allocated_task->get();

            $total_done_task_in = 0;

            $total_done_task_out = 0;

            $total_done_task =DB::table('tasks as t')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                ->join('job_items as j','j.candidate_id','=','u.id')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'2','t.description'=>'JAF Filling'])
                ->whereNotNull('t.assigned_to')
                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                if($request->input('from_date') !=""){
                    // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                    $total_done_task->where(function($q) use ($request){
                            $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                            ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                        });
                    }
                    if($request->input('to_date') !=""){
                    // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                    $total_done_task->where(function($q) use ($request){
                            $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                            ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                        });
                    }

            $total_done_task=$total_done_task->get();

            // // Total Task Done
            if(count($total_done_task)>0)
            {
                foreach($total_done_task as $item)
                {
                    $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                    $end_date = $item->completed_at!=NULL ? date('Y-m-d',strtotime($item->completed_at)) : date('Y-m-d',strtotime($item->end_date_at));

                    
                        $date_arr = [];

                        $tat = $item->client_tat - 1;

                        $incentive_tat = $item->client_tat - 1;

                        if(stripos($item->days_type,'working')!==false)
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                        }
                        else if(stripos($item->days_type,'calender')!==false)
                        {
                            $holiday_master=DB::table('customer_holiday_masters')
                                            ->distinct('date')
                                            ->select('date')
                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                            ->orderBy('date','asc')
                                            ->get();
                            if(count($holiday_master)>0)
                            {
                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                            }
                            else
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                            }
                        }

                        
                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                        {
                            $total_done_task_out = $total_done_task_out + 1;
                        }
                        else
                        {
                            $total_done_task_in = $total_done_task_in + 1;
                        }

                    
                }
            }

            $total_pending_task_in = 0;

            $total_pending_task_out = 0;

            $total_pending_task =DB::table('tasks as t')
            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
            ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
            ->join('job_items as j','j.candidate_id','=','u.id')
            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'JAF Filling','ta.status'=>'1'])
            ->whereNotNull('t.assigned_to')
            ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
            if($request->input('from_date') !=""){
                // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                $total_pending_task->where(function($q) use ($request){
                        $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                        ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                    });
            }
            if($request->input('to_date') !=""){
                // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                $total_pending_task->where(function($q) use ($request){
                        $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                        ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                    });
            }

            $total_pending_task=$total_pending_task->get();

            // Total Task Pending
            if(count($total_pending_task)>0)
            {
                foreach($total_pending_task as $item)
                {
                    $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                    $end_date = date('Y-m-d');

                    
                    $date_arr = [];

                    $tat = $item->client_tat - 1;

                    $incentive_tat = $item->client_tat - 1;

                    if(stripos($item->days_type,'working')!==false)
                    {
                        $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                    }
                    else if(stripos($item->days_type,'calender')!==false)
                    {
                        $holiday_master=DB::table('customer_holiday_masters')
                                        ->distinct('date')
                                        ->select('date')
                                        ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                        ->orderBy('date','asc')
                                        ->get();
                        if(count($holiday_master)>0)
                        {
                            $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                        }
                        else
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                        }
                    }

                    
                    if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                    {
                        $total_pending_task_out = $total_pending_task_out + 1;
                    }
                    else
                    {
                        $total_pending_task_in = $total_pending_task_in + 1;
                    }

                }
            }

            $task_jaf_result[]=[
                'service'=>'JAF Filling',
                'allocated'=>count($total_allocated_task),
                'completed'=>count($total_done_task),
                'completed_in'=>$total_done_task_in,
                'completed_out'=>$total_done_task_out,
                'pending'   => count($total_pending_task),
                'pending_in' => $total_pending_task_in,
                'pending_out'   => $total_pending_task_out
            ];
        }

        $viewRender = view('admin.dashboard.team-lead.jaf-fill',compact('task_jaf_result','user_arr','business_id','kams'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));

    }

    public function dashboardTeamLeadVer(Request $request)
    {
        $user_id = Auth::user()->id;
        $business_id=Auth::user()->business_id;
        $user_type = Auth::user()->user_type;

        $kams = DB::table('key_account_managers')
                    ->select('*')
                    ->where('user_id',$user_id)
                    ->get()->pluck('business_id')->all();

        $services = DB::table('services')
                    ->select('name','id')
                    ->where(['status'=>'1'])
                    ->where('business_id',NULL)
                    ->whereNotIn('type_name',['e_court','gstin'])
                    ->orwhere('business_id',Auth::user()->business_id)
                    ->orderBy('sort_number','asc')
                    ->get();
        
        $customer = DB::table('users')
                    ->where(['id'=>$business_id,'user_type'=>'customer'])
                    ->first();

        $user_arr = DB::table('users')
                    ->where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])
                    ->orderBy('name','asc')
                    ->pluck('name','id')->all();
        
        $user_arr = array($customer->id => $customer->name.' (Customer)') + $user_arr;

        $task_verification_result = [];

        if(count($kams)>0)
        {
            if(count($user_arr)>0)
            {
                foreach($user_arr as $u_id => $u_name)
                {
                    // Task Verification Result

                    $total_allocated_task = DB::table('tasks as t')
                                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                                ->join('job_items as j','j.candidate_id','=','u.id')
                                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Task for Verification'])
                                ->whereIn('ta.status',['1','2'])
                                ->whereNotNull('t.assigned_to')
                                ->whereIn('t.business_id',$kams)
                                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                                if($request->input('from_date') !=""){
                                    // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                                    $total_allocated_task->where(function($q) use ($request){
                                         $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                         ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                                     });
                                }
                                if($request->input('to_date') !=""){
                                    // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                                    $total_allocated_task->where(function($q) use ($request){
                                         $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                         ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                                     });
                                }

                            $total_allocated_task=$total_allocated_task->get();

                            $total_done_task_in = 0;

                            $total_done_task_out = 0;

                            $total_done_task =DB::table('tasks as t')
                                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                                ->join('job_items as j','j.candidate_id','=','u.id')
                                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'2','t.description'=>'Task for Verification'])
                                ->whereNotNull('t.assigned_to')
                                ->whereIn('t.business_id',$kams)
                                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                                if($request->input('from_date') !=""){
                                    // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                                    $total_done_task->where(function($q) use ($request){
                                         $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                         ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                                     });
                                }
                                if($request->input('to_date') !=""){
                                    // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                                    $total_done_task->where(function($q) use ($request){
                                         $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                         ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                                     });
                                }

                            $total_done_task=$total_done_task->get();

                            // Total Task Done
                            if(count($total_done_task)>0)
                            {
                                foreach($total_done_task as $item)
                                {
                                    $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                                    $end_date = $item->completed_at!=NULL ? date('Y-m-d',strtotime($item->completed_at)) : date('Y-m-d',strtotime($item->end_date_at));

                                    if(stripos($item->tat_type,'case')!==false)
                                    {
                                        $date_arr = [];

                                        $tat = $item->client_tat - 1;

                                        $incentive_tat = $item->client_tat - 1;

                                        if(stripos($item->days_type,'working')!==false)
                                        {
                                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                        }
                                        else if(stripos($item->days_type,'calender')!==false)
                                        {
                                            $holiday_master=DB::table('customer_holiday_masters')
                                                            ->distinct('date')
                                                            ->select('date')
                                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                            ->orderBy('date','asc')
                                                            ->get();
                                            if(count($holiday_master)>0)
                                            {
                                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                            }
                                            else
                                            {
                                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                            }
                                        }

                                        
                                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                        {
                                            $total_done_task_out = $total_done_task_out + 1;
                                        }
                                        else
                                        {
                                            $total_done_task_in = $total_done_task_in + 1;
                                        }

                                    }
                                    else if(stripos($item->tat_type,'check')!==false)
                                    {
                                        $date_arr=[];

                                        $tat = 1;

                                        $incentive_tat=1;

                                        $job_sla_item=DB::table('job_sla_items')
                                                        ->select('tat','incentive_tat')
                                                        ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                                        ->first();

                                        if($job_sla_item!=NULL)
                                        {
                                            $tat = $job_sla_item->tat;
                                            $incentive_tat = $job_sla_item->incentive_tat;
                                        }
                                        
                                        if(stripos($item->days_type,'working')!==false)
                                        {
                                            $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                        }
                                        else if(stripos($item->days_type,'calender')!==false)
                                        {
                                            $holiday_master=DB::table('customer_holiday_masters')
                                                            ->distinct('date')
                                                            ->select('date')
                                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                            ->orderBy('date','asc')
                                                            ->get();
                                            if(count($holiday_master)>0)
                                            {
                                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                                            }
                                            else
                                            {
                                                $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                            }
                                        }

                                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                        {
                                            $total_done_task_out = $total_done_task_out + 1;
                                        }
                                        else
                                        {
                                            $total_done_task_in = $total_done_task_in + 1;
                                        }


                                    }
                                }
                            }

                            $total_pending_task_in = 0;

                            $total_pending_task_out = 0;

                            $total_pending_task =DB::table('tasks as t')
                            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                            ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                            ->join('job_items as j','j.candidate_id','=','u.id')
                            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Task for Verification','ta.status'=>'1'])
                            ->whereNotNull('t.assigned_to')
                            ->whereIn('t.business_id',$kams)
                            ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                            if($request->input('from_date') !=""){
                                // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                                $total_pending_task->where(function($q) use ($request){
                                     $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                     ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                                 });
                            }
                            if($request->input('to_date') !=""){
                                // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                                $total_pending_task->where(function($q) use ($request){
                                     $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                     ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                                 });
                            }

                            $total_pending_task=$total_pending_task->get();

                            // Total Task Pending
                            if(count($total_pending_task)>0)
                            {
                                foreach($total_pending_task as $item)
                                {
                                    $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                                    $end_date = date('Y-m-d');

                                    if(stripos($item->tat_type,'case')!==false)
                                    {
                                        $date_arr = [];

                                        $tat = $item->client_tat - 1;

                                        $incentive_tat = $item->client_tat - 1;

                                        if(stripos($item->days_type,'working')!==false)
                                        {
                                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                        }
                                        else if(stripos($item->days_type,'calender')!==false)
                                        {
                                            $holiday_master=DB::table('customer_holiday_masters')
                                                            ->distinct('date')
                                                            ->select('date')
                                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                            ->orderBy('date','asc')
                                                            ->get();
                                            if(count($holiday_master)>0)
                                            {
                                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                            }
                                            else
                                            {
                                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                            }
                                        }

                                        
                                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                        {
                                            $total_pending_task_out = $total_pending_task_out + 1;
                                        }
                                        else
                                        {
                                            $total_pending_task_in = $total_pending_task_in + 1;
                                        }

                                    }
                                    else if(stripos($item->tat_type,'check')!==false)
                                    {
                                        $date_arr=[];

                                        $tat = 1;

                                        $incentive_tat=1;

                                        $job_sla_item=DB::table('job_sla_items')
                                                        ->select('tat','incentive_tat')
                                                        ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                                        ->first();

                                        if($job_sla_item!=NULL)
                                        {
                                            $tat = $job_sla_item->tat;
                                            $incentive_tat = $job_sla_item->incentive_tat;
                                        }
                                        
                                        if(stripos($item->days_type,'working')!==false)
                                        {
                                            $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                        }
                                        else if(stripos($item->days_type,'calender')!==false)
                                        {
                                            $holiday_master=DB::table('customer_holiday_masters')
                                                            ->distinct('date')
                                                            ->select('date')
                                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                            ->orderBy('date','asc')
                                                            ->get();
                                            if(count($holiday_master)>0)
                                            {
                                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                                            }
                                            else
                                            {
                                                $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                            }
                                        }

                                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                        {
                                            $total_pending_task_out = $total_pending_task_out + 1;
                                        }
                                        else
                                        {
                                            $total_pending_task_in = $total_pending_task_in + 1;
                                        }


                                    }

                                }
                            }

                            // Total Task Pending Insuff

                            $total_insuff_task = 0;

                            $total_insuff_task_in = 0;

                            $total_insuff_task_out = 0;

                            if(count($total_pending_task)>0)
                            {
                                foreach($total_pending_task as $item)
                                {
                                    $jaf_form_data = DB::table('jaf_form_data')->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id,'check_item_number'=>$item->number_of_verifications])->where('is_insufficiency','1')->first();

                                    if($jaf_form_data!=NULL)
                                    {
                                        $total_insuff_task = $total_insuff_task + 1;

                                        $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                                        $end_date = date('Y-m-d');

                                        if(stripos($item->tat_type,'case')!==false)
                                        {
                                            $date_arr = [];

                                            $tat = $item->client_tat - 1;

                                            $incentive_tat = $item->client_tat - 1;

                                            if(stripos($item->days_type,'working')!==false)
                                            {
                                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                            }
                                            else if(stripos($item->days_type,'calender')!==false)
                                            {
                                                $holiday_master=DB::table('customer_holiday_masters')
                                                                ->distinct('date')
                                                                ->select('date')
                                                                ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                                ->orderBy('date','asc')
                                                                ->get();
                                                if(count($holiday_master)>0)
                                                {
                                                    $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                                }
                                                else
                                                {
                                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                                }
                                            }

                                            
                                            if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                            {
                                                $total_insuff_task_out = $total_insuff_task_out + 1;
                                            }
                                            else
                                            {
                                                $total_insuff_task_in = $total_insuff_task_in + 1;
                                            }

                                        }
                                        else if(stripos($item->tat_type,'check')!==false)
                                        {
                                            $date_arr=[];

                                            $tat = 1;

                                            $incentive_tat=1;

                                            $job_sla_item=DB::table('job_sla_items')
                                                            ->select('tat','incentive_tat')
                                                            ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                                            ->first();

                                            if($job_sla_item!=NULL)
                                            {
                                                $tat = $job_sla_item->tat;
                                                $incentive_tat = $job_sla_item->incentive_tat;
                                            }
                                            
                                            if(stripos($item->days_type,'working')!==false)
                                            {
                                                $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                            }
                                            else if(stripos($item->days_type,'calender')!==false)
                                            {
                                                $holiday_master=DB::table('customer_holiday_masters')
                                                                ->distinct('date')
                                                                ->select('date')
                                                                ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                                ->orderBy('date','asc')
                                                                ->get();
                                                if(count($holiday_master)>0)
                                                {
                                                    $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                                                }
                                                else
                                                {
                                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                                }
                                            }

                                            if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                            {
                                                $total_insuff_task_out = $total_insuff_task_out + 1;
                                            }
                                            else
                                            {
                                                $total_insuff_task_in = $total_insuff_task_in + 1;
                                            }


                                        }

                                    }
                                }
                            }

                            $task_verification_result[]=[
                                            'user_id'=>$u_id,
                                            'allocated'=>count($total_allocated_task),
                                            'completed'=>count($total_done_task),
                                            'completed_in'=>$total_done_task_in,
                                            'completed_out'=>$total_done_task_out,
                                            'pending'   => count($total_pending_task),
                                            'pending_in' => $total_pending_task_in,
                                            'pending_out'   => $total_pending_task_out,
                                            'insuff'    => $total_insuff_task,
                                            'insuff_in' => $total_insuff_task_in,
                                            'insuff_out'  => $total_insuff_task_out
                                        ];
                }
            }

            
        }
        else if($user_type == 'customer')
        {
            if(count($user_arr)>0)
            {
                foreach($user_arr as $u_id => $u_name)
                {
                    // Task Verification Result

                    $total_allocated_task = DB::table('tasks as t')
                                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                                ->join('job_items as j','j.candidate_id','=','u.id')
                                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Task for Verification'])
                                ->whereIn('ta.status',['1','2'])
                                ->whereNotNull('t.assigned_to')
                                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');

                                if($request->input('from_date') !=""){
                                    // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                                    $total_allocated_task->where(function($q) use ($request){
                                         $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                         ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                                     });
                                }
                                if($request->input('to_date') !=""){
                                    // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                                    $total_allocated_task->where(function($q) use ($request){
                                         $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                         ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                                     });
                                }

                            $total_allocated_task=$total_allocated_task->get();

                            $total_done_task_in = 0;

                            $total_done_task_out = 0;

                            $total_done_task =DB::table('tasks as t')
                                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                                ->join('job_items as j','j.candidate_id','=','u.id')
                                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'2','t.description'=>'Task for Verification'])
                                ->whereNotNull('t.assigned_to')
                                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                                if($request->input('from_date') !=""){
                                    // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                                    $total_done_task->where(function($q) use ($request){
                                         $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                         ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                                     });
                                }
                                if($request->input('to_date') !=""){
                                    // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                                    $total_done_task->where(function($q) use ($request){
                                         $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                         ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                                     });
                                }

                            $total_done_task=$total_done_task->get();

                            // Total Task Done
                            if(count($total_done_task)>0)
                            {
                                foreach($total_done_task as $item)
                                {
                                    $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                                    $end_date = $item->completed_at!=NULL ? date('Y-m-d',strtotime($item->completed_at)) : date('Y-m-d',strtotime($item->end_date_at));

                                    if(stripos($item->tat_type,'case')!==false)
                                    {
                                        $date_arr = [];

                                        $tat = $item->client_tat - 1;

                                        $incentive_tat = $item->client_tat - 1;

                                        if(stripos($item->days_type,'working')!==false)
                                        {
                                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                        }
                                        else if(stripos($item->days_type,'calender')!==false)
                                        {
                                            $holiday_master=DB::table('customer_holiday_masters')
                                                            ->distinct('date')
                                                            ->select('date')
                                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                            ->orderBy('date','asc')
                                                            ->get();
                                            if(count($holiday_master)>0)
                                            {
                                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                            }
                                            else
                                            {
                                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                            }
                                        }

                                        
                                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                        {
                                            $total_done_task_out = $total_done_task_out + 1;
                                        }
                                        else
                                        {
                                            $total_done_task_in = $total_done_task_in + 1;
                                        }

                                    }
                                    else if(stripos($item->tat_type,'check')!==false)
                                    {
                                        $date_arr=[];

                                        $tat = 1;

                                        $incentive_tat=1;

                                        $job_sla_item=DB::table('job_sla_items')
                                                        ->select('tat','incentive_tat')
                                                        ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                                        ->first();

                                        if($job_sla_item!=NULL)
                                        {
                                            $tat = $job_sla_item->tat;
                                            $incentive_tat = $job_sla_item->incentive_tat;
                                        }
                                        
                                        if(stripos($item->days_type,'working')!==false)
                                        {
                                            $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                        }
                                        else if(stripos($item->days_type,'calender')!==false)
                                        {
                                            $holiday_master=DB::table('customer_holiday_masters')
                                                            ->distinct('date')
                                                            ->select('date')
                                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                            ->orderBy('date','asc')
                                                            ->get();
                                            if(count($holiday_master)>0)
                                            {
                                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                                            }
                                            else
                                            {
                                                $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                            }
                                        }

                                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                        {
                                            $total_done_task_out = $total_done_task_out + 1;
                                        }
                                        else
                                        {
                                            $total_done_task_in = $total_done_task_in + 1;
                                        }


                                    }
                                }
                            }

                            $total_pending_task_in = 0;

                            $total_pending_task_out = 0;

                            $total_pending_task =DB::table('tasks as t')
                            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                            ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                            ->join('job_items as j','j.candidate_id','=','u.id')
                            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Task for Verification','ta.status'=>'1'])
                            ->whereNotNull('t.assigned_to')
                            ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                            if($request->input('from_date') !=""){
                                // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                                $total_pending_task->where(function($q) use ($request){
                                     $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                     ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                                 });
                            }
                            if($request->input('to_date') !=""){
                                // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                                $total_pending_task->where(function($q) use ($request){
                                     $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                     ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                                 });
                            }

                            $total_pending_task=$total_pending_task->get();

                            // Total Task Pending
                            if(count($total_pending_task)>0)
                            {
                                foreach($total_pending_task as $item)
                                {
                                    $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                                    $end_date = date('Y-m-d');

                                    if(stripos($item->tat_type,'case')!==false)
                                    {
                                        $date_arr = [];

                                        $tat = $item->client_tat - 1;

                                        $incentive_tat = $item->client_tat - 1;

                                        if(stripos($item->days_type,'working')!==false)
                                        {
                                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                        }
                                        else if(stripos($item->days_type,'calender')!==false)
                                        {
                                            $holiday_master=DB::table('customer_holiday_masters')
                                                            ->distinct('date')
                                                            ->select('date')
                                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                            ->orderBy('date','asc')
                                                            ->get();
                                            if(count($holiday_master)>0)
                                            {
                                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                            }
                                            else
                                            {
                                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                            }
                                        }

                                        
                                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                        {
                                            $total_pending_task_out = $total_pending_task_out + 1;
                                        }
                                        else
                                        {
                                            $total_pending_task_in = $total_pending_task_in + 1;
                                        }

                                    }
                                    else if(stripos($item->tat_type,'check')!==false)
                                    {
                                        $date_arr=[];

                                        $tat = 1;

                                        $incentive_tat=1;

                                        $job_sla_item=DB::table('job_sla_items')
                                                        ->select('tat','incentive_tat')
                                                        ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                                        ->first();

                                        if($job_sla_item!=NULL)
                                        {
                                            $tat = $job_sla_item->tat;
                                            $incentive_tat = $job_sla_item->incentive_tat;
                                        }
                                        
                                        if(stripos($item->days_type,'working')!==false)
                                        {
                                            $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                        }
                                        else if(stripos($item->days_type,'calender')!==false)
                                        {
                                            $holiday_master=DB::table('customer_holiday_masters')
                                                            ->distinct('date')
                                                            ->select('date')
                                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                            ->orderBy('date','asc')
                                                            ->get();
                                            if(count($holiday_master)>0)
                                            {
                                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                                            }
                                            else
                                            {
                                                $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                            }
                                        }

                                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                        {
                                            $total_pending_task_out = $total_pending_task_out + 1;
                                        }
                                        else
                                        {
                                            $total_pending_task_in = $total_pending_task_in + 1;
                                        }


                                    }

                                }
                            }

                            // Total Task Pending Insuff

                            $total_insuff_task = 0;

                            $total_insuff_task_in = 0;

                            $total_insuff_task_out = 0;

                            if(count($total_pending_task)>0)
                            {
                                foreach($total_pending_task as $item)
                                {
                                    $jaf_form_data = DB::table('jaf_form_data')->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id,'check_item_number'=>$item->number_of_verifications])->where('is_insufficiency','1')->first();

                                    if($jaf_form_data!=NULL)
                                    {
                                        $total_insuff_task = $total_insuff_task + 1;

                                        $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                                        $end_date = date('Y-m-d');

                                        if(stripos($item->tat_type,'case')!==false)
                                        {
                                            $date_arr = [];

                                            $tat = $item->client_tat - 1;

                                            $incentive_tat = $item->client_tat - 1;

                                            if(stripos($item->days_type,'working')!==false)
                                            {
                                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                            }
                                            else if(stripos($item->days_type,'calender')!==false)
                                            {
                                                $holiday_master=DB::table('customer_holiday_masters')
                                                                ->distinct('date')
                                                                ->select('date')
                                                                ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                                ->orderBy('date','asc')
                                                                ->get();
                                                if(count($holiday_master)>0)
                                                {
                                                    $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                                }
                                                else
                                                {
                                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                                }
                                            }

                                            
                                            if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                            {
                                                $total_insuff_task_out = $total_insuff_task_out + 1;
                                            }
                                            else
                                            {
                                                $total_insuff_task_in = $total_insuff_task_in + 1;
                                            }

                                        }
                                        else if(stripos($item->tat_type,'check')!==false)
                                        {
                                            $date_arr=[];

                                            $tat = 1;

                                            $incentive_tat=1;

                                            $job_sla_item=DB::table('job_sla_items')
                                                            ->select('tat','incentive_tat')
                                                            ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                                            ->first();

                                            if($job_sla_item!=NULL)
                                            {
                                                $tat = $job_sla_item->tat;
                                                $incentive_tat = $job_sla_item->incentive_tat;
                                            }
                                            
                                            if(stripos($item->days_type,'working')!==false)
                                            {
                                                $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                            }
                                            else if(stripos($item->days_type,'calender')!==false)
                                            {
                                                $holiday_master=DB::table('customer_holiday_masters')
                                                                ->distinct('date')
                                                                ->select('date')
                                                                ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                                ->orderBy('date','asc')
                                                                ->get();
                                                if(count($holiday_master)>0)
                                                {
                                                    $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                                                }
                                                else
                                                {
                                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                                }
                                            }

                                            if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                            {
                                                $total_insuff_task_out = $total_insuff_task_out + 1;
                                            }
                                            else
                                            {
                                                $total_insuff_task_in = $total_insuff_task_in + 1;
                                            }


                                        }

                                    }
                                }
                            }

                            $task_verification_result[]=[
                                            'user_id'=>$u_id,
                                            'allocated'=>count($total_allocated_task),
                                            'completed'=>count($total_done_task),
                                            'completed_in'=>$total_done_task_in,
                                            'completed_out'=>$total_done_task_out,
                                            'pending'   => count($total_pending_task),
                                            'pending_in' => $total_pending_task_in,
                                            'pending_out'   => $total_pending_task_out,
                                            'insuff'    => $total_insuff_task,
                                            'insuff_in' => $total_insuff_task_in,
                                            'insuff_out'  => $total_insuff_task_out
                                        ];
                }
            }
            else{

            }
        }
        else
        {
            $services = [];

            $user_check=DB::table('user_checks')->where('user_id',$user_id)->get()->pluck('checks')->all();

            $services = DB::table('services as s')->select('s.name','s.id')->whereIn('s.id',$user_check)->orderBy('s.sort_number','asc')->get();

            // Task Verification Result
            foreach($services as $service)
            {
                $total_allocated_task = DB::table('tasks as t')
                    ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                    ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                    ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Task for Verification','ta.service_id'=>$service->id])
                    ->whereIn('ta.status',['1','2'])
                    ->whereNotNull('t.assigned_to')
                    ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                    if($request->input('from_date') !=""){
                        // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                        $total_allocated_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                             ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                         });
                     }
                     if($request->input('to_date') !=""){
                        // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                        $total_allocated_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                             ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                         });
                     }
                $total_allocated_task=$total_allocated_task->get();

                $total_done_task_in = 0;

                $total_done_task_out = 0;

                $total_done_task =DB::table('tasks as t')
                    ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                    ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                    ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'2','t.description'=>'Task for Verification','ta.service_id'=>$service->id])
                    ->whereNotNull('t.assigned_to')
                    ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                    if($request->input('from_date') !=""){
                        // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                        $total_done_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                             ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                         });
                     }
                     if($request->input('to_date') !=""){
                        // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                        $total_done_task->where(function($q) use ($request){
                             $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                             ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                         });
                     }

                $total_done_task=$total_done_task->get();

                // Total Task Done
                if(count($total_done_task)>0)
                {
                    foreach($total_done_task as $item)
                    {
                        $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                        $end_date = $item->completed_at!=NULL ? date('Y-m-d',strtotime($item->completed_at)) : date('Y-m-d',strtotime($item->end_date_at));

                        if(stripos($item->tat_type,'case')!==false)
                        {
                            $date_arr = [];

                            $tat = $item->client_tat - 1;

                            $incentive_tat = $item->client_tat - 1;

                            if(stripos($item->days_type,'working')!==false)
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                            }
                            else if(stripos($item->days_type,'calender')!==false)
                            {
                                $holiday_master=DB::table('customer_holiday_masters')
                                                ->distinct('date')
                                                ->select('date')
                                                ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                ->orderBy('date','asc')
                                                ->get();
                                if(count($holiday_master)>0)
                                {
                                    $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                }
                                else
                                {
                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                }
                            }

                            
                            if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                            {
                                $total_done_task_out = $total_done_task_out + 1;
                            }
                            else
                            {
                                $total_done_task_in = $total_done_task_in + 1;
                            }

                        }
                        else if(stripos($item->tat_type,'check')!==false)
                        {
                            $date_arr=[];

                            $tat = 1;

                            $incentive_tat=1;

                            $job_sla_item=DB::table('job_sla_items')
                                            ->select('tat','incentive_tat')
                                            ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                            ->first();

                            if($job_sla_item!=NULL)
                            {
                                $tat = $job_sla_item->tat;
                                $incentive_tat = $job_sla_item->incentive_tat;
                            }
                            
                            if(stripos($item->days_type,'working')!==false)
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                            }
                            else if(stripos($item->days_type,'calender')!==false)
                            {
                                $holiday_master=DB::table('customer_holiday_masters')
                                                ->distinct('date')
                                                ->select('date')
                                                ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                ->orderBy('date','asc')
                                                ->get();
                                if(count($holiday_master)>0)
                                {
                                    $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                                }
                                else
                                {
                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                }
                            }

                            if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                            {
                                $total_done_task_out = $total_done_task_out + 1;
                            }
                            else
                            {
                                $total_done_task_in = $total_done_task_in + 1;
                            }


                        }
                    }
                }

                $total_pending_task_in = 0;

                $total_pending_task_out = 0;

                $total_pending_task =DB::table('tasks as t')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                ->join('job_items as j','j.candidate_id','=','u.id')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Task for Verification','ta.status'=>'1','ta.service_id'=>$service->id])
                ->whereNotNull('t.assigned_to')
                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                if($request->input('from_date') !=""){
                    // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                    $total_pending_task->where(function($q) use ($request){
                         $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                         ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                     });
                 }
                 if($request->input('to_date') !=""){
                    // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                    $total_pending_task->where(function($q) use ($request){
                         $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                         ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                     });
                 }

                $total_pending_task=$total_pending_task->get();

                // Total Task Pending
                if(count($total_pending_task)>0)
                {
                    foreach($total_pending_task as $item)
                    {
                        $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                        $end_date = date('Y-m-d');

                        if(stripos($item->tat_type,'case')!==false)
                        {
                            $date_arr = [];

                            $tat = $item->client_tat - 1;

                            $incentive_tat = $item->client_tat - 1;

                            if(stripos($item->days_type,'working')!==false)
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                            }
                            else if(stripos($item->days_type,'calender')!==false)
                            {
                                $holiday_master=DB::table('customer_holiday_masters')
                                                ->distinct('date')
                                                ->select('date')
                                                ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                ->orderBy('date','asc')
                                                ->get();
                                if(count($holiday_master)>0)
                                {
                                    $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                }
                                else
                                {
                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                }
                            }

                            
                            if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                            {
                                $total_pending_task_out = $total_pending_task_out + 1;
                            }
                            else
                            {
                                $total_pending_task_in = $total_pending_task_in + 1;
                            }

                        }
                        else if(stripos($item->tat_type,'check')!==false)
                        {
                            $date_arr=[];

                            $tat = 1;

                            $incentive_tat=1;

                            $job_sla_item=DB::table('job_sla_items')
                                            ->select('tat','incentive_tat')
                                            ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                            ->first();

                            if($job_sla_item!=NULL)
                            {
                                $tat = $job_sla_item->tat;
                                $incentive_tat = $job_sla_item->incentive_tat;
                            }
                            
                            if(stripos($item->days_type,'working')!==false)
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                            }
                            else if(stripos($item->days_type,'calender')!==false)
                            {
                                $holiday_master=DB::table('customer_holiday_masters')
                                                ->distinct('date')
                                                ->select('date')
                                                ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                ->orderBy('date','asc')
                                                ->get();
                                if(count($holiday_master)>0)
                                {
                                    $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                                }
                                else
                                {
                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                }
                            }

                            if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                            {
                                $total_pending_task_out = $total_pending_task_out + 1;
                            }
                            else
                            {
                                $total_pending_task_in = $total_pending_task_in + 1;
                            }


                        }

                    }
                }

                // Total Task Pending Insuff

                $total_insuff_task = 0;

                $total_insuff_task_in = 0;

                $total_insuff_task_out = 0;

                if(count($total_pending_task)>0)
                {
                    foreach($total_pending_task as $item)
                    {
                        $jaf_form_data = DB::table('jaf_form_data')->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id,'check_item_number'=>$item->number_of_verifications])->where('is_insufficiency','1')->first();

                        if($jaf_form_data!=NULL)
                        {
                            $total_insuff_task = $total_insuff_task + 1;

                            $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                            $end_date = date('Y-m-d');

                            if(stripos($item->tat_type,'case')!==false)
                            {
                                $date_arr = [];

                                $tat = $item->client_tat - 1;

                                $incentive_tat = $item->client_tat - 1;

                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                }
                                else if(stripos($item->days_type,'calender')!==false)
                                {
                                    $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                    ->orderBy('date','asc')
                                                    ->get();
                                    if(count($holiday_master)>0)
                                    {
                                        $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                    }
                                    else
                                    {
                                        $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                    }
                                }

                                
                                if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                {
                                    $total_insuff_task_out = $total_insuff_task_out + 1;
                                }
                                else
                                {
                                    $total_insuff_task_in = $total_insuff_task_in + 1;
                                }

                            }
                            else if(stripos($item->tat_type,'check')!==false)
                            {
                                $date_arr=[];

                                $tat = 1;

                                $incentive_tat=1;

                                $job_sla_item=DB::table('job_sla_items')
                                                ->select('tat','incentive_tat')
                                                ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                                ->first();

                                if($job_sla_item!=NULL)
                                {
                                    $tat = $job_sla_item->tat;
                                    $incentive_tat = $job_sla_item->incentive_tat;
                                }
                                
                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                }
                                else if(stripos($item->days_type,'calender')!==false)
                                {
                                    $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                    ->orderBy('date','asc')
                                                    ->get();
                                    if(count($holiday_master)>0)
                                    {
                                        $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                                    }
                                    else
                                    {
                                        $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                    }
                                }

                                if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                {
                                    $total_insuff_task_out = $total_insuff_task_out + 1;
                                }
                                else
                                {
                                    $total_insuff_task_in = $total_insuff_task_in + 1;
                                }


                            }

                        }
                    }
                }

                $task_verification_result[]=[
                                'service'=>$service->name,
                                'service_id' => $service->id,
                                'allocated'=>count($total_allocated_task),
                                'completed'=>count($total_done_task),
                                'completed_in'=>$total_done_task_in,
                                'completed_out'=>$total_done_task_out,
                                'pending'   => count($total_pending_task),
                                'pending_in' => $total_pending_task_in,
                                'pending_out'   => $total_pending_task_out,
                                'insuff'    => $total_insuff_task,
                                'insuff_in' => $total_insuff_task_in,
                                'insuff_out'  => $total_insuff_task_out
                            ];
            }
        }

        $viewRender = view('admin.dashboard.team-lead.task-for-verify',compact('task_verification_result','user_arr','business_id','kams','services'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));

    }

    public function dashboardTeamLeadReport(Request $request)
    {
        $user_id = Auth::user()->id;
        $business_id=Auth::user()->business_id;
        $user_type = Auth::user()->user_type;

        $kams = DB::table('key_account_managers')
                    ->select('*')
                    ->where('user_id',$user_id)
                    ->get()->pluck('business_id')->all();

        $services = DB::table('services')
                    ->select('name','id')
                    ->where(['status'=>'1'])
                    ->where('business_id',NULL)
                    ->whereNotIn('type_name',['e_court','gstin'])
                    ->orwhere('business_id',Auth::user()->business_id)
                    ->orderBy('sort_number','asc')
                    ->get();
        
        $customer = DB::table('users')
                    ->where(['id'=>$business_id,'user_type'=>'customer'])
                    ->first();

        $user_arr = DB::table('users')
                    ->where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])
                    ->orderBy('name','asc')
                    ->pluck('name','id')->all();
        
        $user_arr = array($customer->id => $customer->name.' (Customer)') + $user_arr;

        $task_report_result = [];

        if(count($kams)>0)
        {
            if(count($user_arr)>0)
            {
                foreach($user_arr as $u_id => $u_name)
                {
                        // Task Report Report

                        $total_allocated_task = DB::table('tasks as t')
                        ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                        ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                        ->join('job_items as j','j.candidate_id','=','u.id')
                        ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                        ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Report generation'])
                        ->whereIn('ta.status',['1','2'])
                        ->whereNotNull('t.assigned_to')
                        ->whereIn('t.business_id',$kams)
                        ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                        if($request->input('from_date') !=""){
                            // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                            $total_allocated_task->where(function($q) use ($request){
                                 $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                 ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                             });
                        }
                        if($request->input('to_date') !=""){
                            // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                            $total_allocated_task->where(function($q) use ($request){
                                 $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                 ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                             });
                        }
    
                        $total_allocated_task=$total_allocated_task->get();
    
                        $total_done_task_in = 0;
    
                        $total_done_task_out = 0;
    
                        $total_done_task =DB::table('tasks as t')
                            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                            ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                            ->join('job_items as j','j.candidate_id','=','u.id')
                            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'2','t.description'=>'Report generation'])
                            ->whereNotNull('t.assigned_to')
                            ->whereIn('t.business_id',$kams)
                            ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                            if($request->input('from_date') !=""){
                                // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                                $total_done_task->where(function($q) use ($request){
                                     $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                     ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                                 });
                            }
                            if($request->input('to_date') !=""){
                                // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                                $total_done_task->where(function($q) use ($request){
                                     $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                     ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                                 });
                            }
    
                        $total_done_task=$total_done_task->get();

                        // Total Task Done
                        if(count($total_done_task)>0)
                        {
                            foreach($total_done_task as $item)
                            {
                                $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                                $end_date = $item->completed_at!=NULL ? date('Y-m-d',strtotime($item->completed_at)) : date('Y-m-d',strtotime($item->end_date_at));

                                
                                    $date_arr = [];

                                    $tat = $item->client_tat - 1;

                                    $incentive_tat = $item->client_tat - 1;

                                    if(stripos($item->days_type,'working')!==false)
                                    {
                                        $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                    }
                                    else if(stripos($item->days_type,'calender')!==false)
                                    {
                                        $holiday_master=DB::table('customer_holiday_masters')
                                                        ->distinct('date')
                                                        ->select('date')
                                                        ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                        ->orderBy('date','asc')
                                                        ->get();
                                        if(count($holiday_master)>0)
                                        {
                                            $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                        }
                                        else
                                        {
                                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                        }
                                    }

                                    
                                    if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                    {
                                        $total_done_task_out = $total_done_task_out + 1;
                                    }
                                    else
                                    {
                                        $total_done_task_in = $total_done_task_in + 1;
                                    }

                            
                                
                            }
                        }
    
                        $total_pending_task_in = 0;
    
                        $total_pending_task_out = 0;
    
                        $total_pending_task =DB::table('tasks as t')
                        ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                        ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                        ->join('job_items as j','j.candidate_id','=','u.id')
                        ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                        ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Report generation','ta.status'=>'1'])
                        ->whereNotNull('t.assigned_to')
                        ->whereIn('t.business_id',$kams)
                        ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                        if($request->input('from_date') !=""){
                            // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                            $total_pending_task->where(function($q) use ($request){
                                 $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                 ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                             });
                        }
                        if($request->input('to_date') !=""){
                            // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                            $total_pending_task->where(function($q) use ($request){
                                 $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                 ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                             });
                        }
    
                        $total_pending_task=$total_pending_task->get();
    
                        // // Total Task Pending
                        if(count($total_pending_task)>0)
                        {
                            foreach($total_pending_task as $item)
                            {
                                $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));
    
                                $end_date = date('Y-m-d');
    
                                
                                    $date_arr = [];
    
                                    $tat = $item->client_tat - 1;
    
                                    $incentive_tat = $item->client_tat - 1;
    
                                    if(stripos($item->days_type,'working')!==false)
                                    {
                                        $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                    }
                                    else if(stripos($item->days_type,'calender')!==false)
                                    {
                                        $holiday_master=DB::table('customer_holiday_masters')
                                                        ->distinct('date')
                                                        ->select('date')
                                                        ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                        ->orderBy('date','asc')
                                                        ->get();
                                        if(count($holiday_master)>0)
                                        {
                                            $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                        }
                                        else
                                        {
                                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                        }
                                    }
    
                                    
                                    if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                    {
                                        $total_pending_task_out = $total_pending_task_out + 1;
                                    }
                                    else
                                    {
                                        $total_pending_task_in = $total_pending_task_in + 1;
                                    }
    
                                
    
                            }
                        }
    
                        $task_report_result[]=[
                            'service'=>$u_name,
                            'user_id' => $u_id,
                            'allocated'=>count($total_allocated_task),
                            'completed'=>count($total_done_task),
                            'completed_in'=>$total_done_task_in,
                            'completed_out'=>$total_done_task_out,
                            'pending'   => count($total_pending_task),
                            'pending_in' => $total_pending_task_in,
                            'pending_out'   => $total_pending_task_out
                        ];
                       
                }
            }
        }
        else if($user_type == 'customer')
        {
            if(count($user_arr)>0)
            {
                foreach($user_arr as $u_id => $u_name)
                {
                        // Task Report Report

                        $total_allocated_task = DB::table('tasks as t')
                        ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                        ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                        ->join('job_items as j','j.candidate_id','=','u.id')
                        ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                        ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Report generation'])
                        ->whereIn('ta.status',['1','2'])
                        ->whereNotNull('t.assigned_to')
                        ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                        if($request->input('from_date') !=""){
                            // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                            $total_allocated_task->where(function($q) use ($request){
                                 $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                 ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                             });
                        }
                        if($request->input('to_date') !=""){
                            // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                            $total_allocated_task->where(function($q) use ($request){
                                 $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                 ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                             });
                        }
    
                        $total_allocated_task=$total_allocated_task->get();
    
                        $total_done_task_in = 0;
    
                        $total_done_task_out = 0;
    
                        $total_done_task =DB::table('tasks as t')
                            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                            ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                            ->join('job_items as j','j.candidate_id','=','u.id')
                            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'2','t.description'=>'Report generation'])
                            ->whereNotNull('t.assigned_to')
                            ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                            if($request->input('from_date') !=""){
                                // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                                $total_done_task->where(function($q) use ($request){
                                     $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                     ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                                 });
                            }
                            if($request->input('to_date') !=""){
                                // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                                $total_done_task->where(function($q) use ($request){
                                     $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                     ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                                 });
                            }
    
                        $total_done_task=$total_done_task->get();

                        // Total Task Done
                        if(count($total_done_task)>0)
                        {
                            foreach($total_done_task as $item)
                            {
                                $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                                $end_date = $item->completed_at!=NULL ? date('Y-m-d',strtotime($item->completed_at)) : date('Y-m-d',strtotime($item->end_date_at));

                                
                                    $date_arr = [];

                                    $tat = $item->client_tat - 1;

                                    $incentive_tat = $item->client_tat - 1;

                                    if(stripos($item->days_type,'working')!==false)
                                    {
                                        $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                    }
                                    else if(stripos($item->days_type,'calender')!==false)
                                    {
                                        $holiday_master=DB::table('customer_holiday_masters')
                                                        ->distinct('date')
                                                        ->select('date')
                                                        ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                        ->orderBy('date','asc')
                                                        ->get();
                                        if(count($holiday_master)>0)
                                        {
                                            $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                        }
                                        else
                                        {
                                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                        }
                                    }

                                    
                                    if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                    {
                                        $total_done_task_out = $total_done_task_out + 1;
                                    }
                                    else
                                    {
                                        $total_done_task_in = $total_done_task_in + 1;
                                    }

                            
                                
                            }
                        }
    
                        $total_pending_task_in = 0;
    
                        $total_pending_task_out = 0;
    
                        $total_pending_task =DB::table('tasks as t')
                        ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                        ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                        ->join('job_items as j','j.candidate_id','=','u.id')
                        ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                        ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Report generation','ta.status'=>'1'])
                        ->whereNotNull('t.assigned_to')
                        ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$u_id.', ta.user_id='.$u_id.')');
                        if($request->input('from_date') !=""){
                            // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                            $total_pending_task->where(function($q) use ($request){
                                 $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                                 ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                             });
                        }
                        if($request->input('to_date') !=""){
                            // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                            $total_pending_task->where(function($q) use ($request){
                                 $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                                 ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                             });
                        }
    
                        $total_pending_task=$total_pending_task->get();
    
                        // // Total Task Pending
                        if(count($total_pending_task)>0)
                        {
                            foreach($total_pending_task as $item)
                            {
                                $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));
    
                                $end_date = date('Y-m-d');
    
                                
                                    $date_arr = [];
    
                                    $tat = $item->client_tat - 1;
    
                                    $incentive_tat = $item->client_tat - 1;
    
                                    if(stripos($item->days_type,'working')!==false)
                                    {
                                        $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                    }
                                    else if(stripos($item->days_type,'calender')!==false)
                                    {
                                        $holiday_master=DB::table('customer_holiday_masters')
                                                        ->distinct('date')
                                                        ->select('date')
                                                        ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                        ->orderBy('date','asc')
                                                        ->get();
                                        if(count($holiday_master)>0)
                                        {
                                            $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                        }
                                        else
                                        {
                                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                        }
                                    }
    
                                    
                                    if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                    {
                                        $total_pending_task_out = $total_pending_task_out + 1;
                                    }
                                    else
                                    {
                                        $total_pending_task_in = $total_pending_task_in + 1;
                                    }
    
                                
    
                            }
                        }
    
                        $task_report_result[]=[
                            'service'=>$u_name,
                            'user_id' => $u_id,
                            'allocated'=>count($total_allocated_task),
                            'completed'=>count($total_done_task),
                            'completed_in'=>$total_done_task_in,
                            'completed_out'=>$total_done_task_out,
                            'pending'   => count($total_pending_task),
                            'pending_in' => $total_pending_task_in,
                            'pending_out'   => $total_pending_task_out
                        ];
                       
                }
            }
        }
        else
        {
             // Task Report Report

             $total_allocated_task = DB::table('tasks as t')
             ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
             ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
             ->join('job_items as j','j.candidate_id','=','u.id')
             ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
             ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Report generation'])
             ->whereIn('ta.status',['1','2'])
             ->whereNotNull('t.assigned_to')
             ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
             if($request->input('from_date') !=""){
                 // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                 $total_allocated_task->where(function($q) use ($request){
                      $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                      ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                  });
              }
              if($request->input('to_date') !=""){
                 // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                 $total_allocated_task->where(function($q) use ($request){
                      $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                      ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                  });
              }

            $total_allocated_task=$total_allocated_task->get();

            $total_done_task_in = 0;

            $total_done_task_out = 0;

            $total_done_task =DB::table('tasks as t')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                ->join('job_items as j','j.candidate_id','=','u.id')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'2','t.description'=>'Report generation'])
                ->whereNotNull('t.assigned_to')
                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                    if($request->input('from_date') !=""){
                    // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                    $total_done_task->where(function($q) use ($request){
                            $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                            ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                        });
                    }
                    if($request->input('to_date') !=""){
                    // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                    $total_done_task->where(function($q) use ($request){
                            $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                            ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                        });
                    }

            $total_done_task=$total_done_task->get();

            // // Total Task Done
            if(count($total_done_task)>0)
            {
                foreach($total_done_task as $item)
                {
                    $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                    $end_date = $item->completed_at!=NULL ? date('Y-m-d',strtotime($item->completed_at)) : date('Y-m-d',strtotime($item->end_date_at));

                    
                        $date_arr = [];

                        $tat = $item->client_tat - 1;

                        $incentive_tat = $item->client_tat - 1;

                        if(stripos($item->days_type,'working')!==false)
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                        }
                        else if(stripos($item->days_type,'calender')!==false)
                        {
                            $holiday_master=DB::table('customer_holiday_masters')
                                            ->distinct('date')
                                            ->select('date')
                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                            ->orderBy('date','asc')
                                            ->get();
                            if(count($holiday_master)>0)
                            {
                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                            }
                            else
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                            }
                        }

                        
                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                        {
                            $total_done_task_out = $total_done_task_out + 1;
                        }
                        else
                        {
                            $total_done_task_in = $total_done_task_in + 1;
                        }

                    
                }
            }

            $total_pending_task_in = 0;

            $total_pending_task_out = 0;

            $total_pending_task =DB::table('tasks as t')
            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
            ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
            ->join('job_items as j','j.candidate_id','=','u.id')
            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Report generation','ta.status'=>'1'])
            ->whereNotNull('t.assigned_to')
            ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                if($request->input('from_date') !=""){
                // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                $total_pending_task->where(function($q) use ($request){
                        $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                        ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                    });
                }
                if($request->input('to_date') !=""){
                // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                $total_pending_task->where(function($q) use ($request){
                        $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                        ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                    });
                }

            $total_pending_task=$total_pending_task->get();

            // // Total Task Pending
            if(count($total_pending_task)>0)
            {
                foreach($total_pending_task as $item)
                {
                    $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                    $end_date = date('Y-m-d');

                    
                        $date_arr = [];

                        $tat = $item->client_tat - 1;

                        $incentive_tat = $item->client_tat - 1;

                        if(stripos($item->days_type,'working')!==false)
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                        }
                        else if(stripos($item->days_type,'calender')!==false)
                        {
                            $holiday_master=DB::table('customer_holiday_masters')
                                            ->distinct('date')
                                            ->select('date')
                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                            ->orderBy('date','asc')
                                            ->get();
                            if(count($holiday_master)>0)
                            {
                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                            }
                            else
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                            }
                        }

                        
                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                        {
                            $total_pending_task_out = $total_pending_task_out + 1;
                        }
                        else
                        {
                            $total_pending_task_in = $total_pending_task_in + 1;
                        }

                    

                }
            }

            $task_report_result[]=[
                'service'=>'Report Writing',
                'allocated'=>count($total_allocated_task),
                'completed'=>count($total_done_task),
                'completed_in'=>$total_done_task_in,
                'completed_out'=>$total_done_task_out,
                'pending'   => count($total_pending_task),
                'pending_in' => $total_pending_task_in,
                'pending_out'   => $total_pending_task_out,
                // 'insuff'    => $total_insuff_task,
                // 'insuff_in' => $total_insuff_task_in,
                // 'insuff_out'  => $total_insuff_task_out
            ];
        }

        $viewRender = view('admin.dashboard.team-lead.report-writing',compact('task_report_result','user_arr','business_id','kams'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));
    }

    public function dashboardAllottedTask(Request $request)
    {
        $user_id = Auth::user()->id;
        $business_id=Auth::user()->business_id;
        $user_type = Auth::user()->user_type;

        $total_allocated_task = DB::table('tasks as t')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                ->join('job_items as j','j.candidate_id','=','u.id')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Task for Verification'])
                ->whereIn('ta.status',['1','2'])
                ->whereNotNull('t.assigned_to')
                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                if($request->input('from_date') !=""){
                   // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                   $total_allocated_task->where(function($q) use ($request){
                        $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                        ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                    });
                }
                if($request->input('to_date') !=""){
                   // $total_allocated_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                   $total_allocated_task->where(function($q) use ($request){
                        $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                        ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                    });
                }

        $total_allocated_task=$total_allocated_task->get();

        //dd($total_allocated_task);

        $viewRender = view('admin.dashboard.employee.allotted-task-dashboard',compact('total_allocated_task'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));
    }

    public function dashboardCompletedTask(Request $request)
    {
        $user_id = Auth::user()->id;
        $business_id=Auth::user()->business_id;
        $user_type = Auth::user()->user_type;

        $total_done_task_in = 0;

        $total_done_task_out = 0;

        $total_done_task =DB::table('tasks as t')
            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
            ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
            ->join('job_items as j','j.candidate_id','=','u.id')
            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'2','t.description'=>'Task for Verification'])
            ->whereNotNull('t.assigned_to')
            ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
            if($request->input('from_date') !=""){
                // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                $total_done_task->where(function($q) use ($request){
                     $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                     ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                 });
             }
             if($request->input('to_date') !=""){
                // $total_done_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                $total_done_task->where(function($q) use ($request){
                     $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                     ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                 });
             }

        $total_done_task=$total_done_task->get();

        // Total Task Done
        if(count($total_done_task)>0)
        {
            foreach($total_done_task as $item)
            {
                $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                $end_date = $item->completed_at!=NULL ? date('Y-m-d',strtotime($item->completed_at)) : date('Y-m-d',strtotime($item->end_date_at));

                if(stripos($item->tat_type,'case')!==false)
                {
                    $date_arr = [];

                    $tat = $item->client_tat - 1;

                    $incentive_tat = $item->client_tat - 1;

                    if(stripos($item->days_type,'working')!==false)
                    {
                        $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                    }
                    else if(stripos($item->days_type,'calender')!==false)
                    {
                        $holiday_master=DB::table('customer_holiday_masters')
                                        ->distinct('date')
                                        ->select('date')
                                        ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                        ->orderBy('date','asc')
                                        ->get();
                        if(count($holiday_master)>0)
                        {
                            $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                        }
                        else
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                        }
                    }

                    
                    if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                    {
                        $total_done_task_out = $total_done_task_out + 1;
                    }
                    else
                    {
                        $total_done_task_in = $total_done_task_in + 1;
                    }

                }
                else if(stripos($item->tat_type,'check')!==false)
                {
                    $date_arr=[];

                    $tat = 1;

                    $incentive_tat=1;

                    $job_sla_item=DB::table('job_sla_items')
                                    ->select('tat','incentive_tat')
                                    ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                    ->first();

                    if($job_sla_item!=NULL)
                    {
                        $tat = $job_sla_item->tat;
                        $incentive_tat = $job_sla_item->incentive_tat;
                    }
                    
                    if(stripos($item->days_type,'working')!==false)
                    {
                        $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                    }
                    else if(stripos($item->days_type,'calender')!==false)
                    {
                        $holiday_master=DB::table('customer_holiday_masters')
                                        ->distinct('date')
                                        ->select('date')
                                        ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                        ->orderBy('date','asc')
                                        ->get();
                        if(count($holiday_master)>0)
                        {
                            $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                        }
                        else
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                        }
                    }

                    if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                    {
                        $total_done_task_out = $total_done_task_out + 1;
                    }
                    else
                    {
                        $total_done_task_in = $total_done_task_in + 1;
                    }


                }
            }
        }

        $viewRender = view('admin.dashboard.employee.completed-task-dashboard',compact('total_done_task','total_done_task_in','total_done_task_out'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));
    }

    public function dashboardPendingTask(Request $request)
    {
        $user_id = Auth::user()->id;
        $business_id=Auth::user()->business_id;
        $user_type = Auth::user()->user_type;

        $total_pending_task_in = 0;

        $total_pending_task_out = 0;

        $total_pending_task =DB::table('tasks as t')
        ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
        ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
        ->join('job_items as j','j.candidate_id','=','u.id')
        ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
        ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Task for Verification','ta.status'=>'1'])
        ->whereNotNull('t.assigned_to')
        ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
        if($request->input('from_date') !=""){
            // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
            $total_pending_task->where(function($q) use ($request){
                 $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                 ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
             });
         }
         if($request->input('to_date') !=""){
            // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
            $total_pending_task->where(function($q) use ($request){
                 $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                 ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
             });
         }

        $total_pending_task=$total_pending_task->get();

        // Total Task Pending
        if(count($total_pending_task)>0)
        {
            foreach($total_pending_task as $item)
            {
                $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                $end_date = date('Y-m-d');

                if(stripos($item->tat_type,'case')!==false)
                {
                    $date_arr = [];

                    $tat = $item->client_tat - 1;

                    $incentive_tat = $item->client_tat - 1;

                    if(stripos($item->days_type,'working')!==false)
                    {
                        $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                    }
                    else if(stripos($item->days_type,'calender')!==false)
                    {
                        $holiday_master=DB::table('customer_holiday_masters')
                                        ->distinct('date')
                                        ->select('date')
                                        ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                        ->orderBy('date','asc')
                                        ->get();
                        if(count($holiday_master)>0)
                        {
                            $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                        }
                        else
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                        }
                    }

                    
                    if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                    {
                        $total_pending_task_out = $total_pending_task_out + 1;
                    }
                    else
                    {
                        $total_pending_task_in = $total_pending_task_in + 1;
                    }

                }
                else if(stripos($item->tat_type,'check')!==false)
                {
                    $date_arr=[];

                    $tat = 1;

                    $incentive_tat=1;

                    $job_sla_item=DB::table('job_sla_items')
                                    ->select('tat','incentive_tat')
                                    ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                    ->first();

                    if($job_sla_item!=NULL)
                    {
                        $tat = $job_sla_item->tat;
                        $incentive_tat = $job_sla_item->incentive_tat;
                    }
                    
                    if(stripos($item->days_type,'working')!==false)
                    {
                        $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                    }
                    else if(stripos($item->days_type,'calender')!==false)
                    {
                        $holiday_master=DB::table('customer_holiday_masters')
                                        ->distinct('date')
                                        ->select('date')
                                        ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                        ->orderBy('date','asc')
                                        ->get();
                        if(count($holiday_master)>0)
                        {
                            $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                        }
                        else
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                        }
                    }

                    if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                    {
                        $total_pending_task_out = $total_pending_task_out + 1;
                    }
                    else
                    {
                        $total_pending_task_in = $total_pending_task_in + 1;
                    }


                }

            }
        }

        $viewRender = view('admin.dashboard.employee.pending-task-dashboard',compact('total_pending_task','total_pending_task_in','total_pending_task_out'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));
    }

    public function dashboardInsuffTask(Request $request)
    {
        $user_id = Auth::user()->id;
        $business_id=Auth::user()->business_id;
        $user_type = Auth::user()->user_type;

        // Total Task Pending Insuff

        $total_insuff_task = 0;

        $total_insuff_task_in = 0;

        $total_insuff_task_out = 0;

        $total_pending_task =DB::table('tasks as t')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                ->join('job_items as j','j.candidate_id','=','u.id')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Task for Verification','ta.status'=>'1'])
                ->whereNotNull('t.assigned_to')
                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                if($request->input('from_date') !=""){
                    // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('from_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('from_date'))).' END');
                    $total_pending_task->where(function($q) use ($request){
                         $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('from_date'))))
                         ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('from_date'))));
                     });
                 }
                 if($request->input('to_date') !=""){
                    // $total_pending_task->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('to_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('to_date'))).' END');
                    $total_pending_task->where(function($q) use ($request){
                         $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('to_date'))))
                         ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('to_date'))));
                     });
                 }

        $total_pending_task=$total_pending_task->get();

        if(count($total_pending_task)>0)
        {
            foreach($total_pending_task as $item)
            {
                $jaf_form_data = DB::table('jaf_form_data')->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id,'check_item_number'=>$item->number_of_verifications])->where('is_insufficiency','1')->first();

                if($jaf_form_data!=NULL)
                {
                    $total_insuff_task = $total_insuff_task + 1;

                    $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));

                    $end_date = date('Y-m-d');

                    if(stripos($item->tat_type,'case')!==false)
                    {
                        $date_arr = [];

                        $tat = $item->client_tat - 1;

                        $incentive_tat = $item->client_tat - 1;

                        if(stripos($item->days_type,'working')!==false)
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                        }
                        else if(stripos($item->days_type,'calender')!==false)
                        {
                            $holiday_master=DB::table('customer_holiday_masters')
                                            ->distinct('date')
                                            ->select('date')
                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                            ->orderBy('date','asc')
                                            ->get();
                            if(count($holiday_master)>0)
                            {
                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                            }
                            else
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                            }
                        }

                        
                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                        {
                            $total_insuff_task_out = $total_insuff_task_out + 1;
                        }
                        else
                        {
                            $total_insuff_task_in = $total_insuff_task_in + 1;
                        }

                    }
                    else if(stripos($item->tat_type,'check')!==false)
                    {
                        $date_arr=[];

                        $tat = 1;

                        $incentive_tat=1;

                        $job_sla_item=DB::table('job_sla_items')
                                        ->select('tat','incentive_tat')
                                        ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                        ->first();

                        if($job_sla_item!=NULL)
                        {
                            $tat = $job_sla_item->tat;
                            $incentive_tat = $job_sla_item->incentive_tat;
                        }
                        
                        if(stripos($item->days_type,'working')!==false)
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                        }
                        else if(stripos($item->days_type,'calender')!==false)
                        {
                            $holiday_master=DB::table('customer_holiday_masters')
                                            ->distinct('date')
                                            ->select('date')
                                            ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                            ->orderBy('date','asc')
                                            ->get();
                            if(count($holiday_master)>0)
                            {
                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                            }
                            else
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                            }
                        }

                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                        {
                            $total_insuff_task_out = $total_insuff_task_out + 1;
                        }
                        else
                        {
                            $total_insuff_task_in = $total_insuff_task_in + 1;
                        }


                    }

                }
            }
        }

        $viewRender = view('admin.dashboard.employee.insuff-task-dashboard',compact('total_insuff_task','total_insuff_task_in','total_insuff_task_out'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));
    }

    public function userAPI(Request $request)
    {
        // dd(Auth::user()->id);
        // $user_id=Auth::user()->id;
        $business_id=Auth::user()->business_id;

        // $services=DB::table('services')
        //         ->where(['verification_type'=>'Auto','status'=>'1'])
        //         ->pluck('name');
        // dd($services);

        $aadhar=DB::table('aadhar_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_reference'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        // dd($aadhar);
        $pan=DB::table('pan_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        $voter_id=DB::table('voter_id_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_reference'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();
        
        $rc=DB::table('rc_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        $dl=DB::table('dl_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        $passport=DB::table('passport_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        $bank=DB::table('bank_account_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','a.service_id','=','a.service_id')
        ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        $gst=DB::table('gst_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        $telecom=DB::table('telecom_check as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        $e_court=DB::table('e_court_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_type'=>'API','a.user_type'=>'customer','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        $upi=DB::table('upi_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_type'=>'API','a.user_type'=>'customer','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();

        $cin=DB::table('cin_checks as a')
        ->select('s.id as  service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'),'s.type_name')
        ->join('services as s','s.id','=','a.service_id')
        // ->join('check_prices as c','c.service_id','=','a.service_id')
        ->where(['source_type'=>'API','a.user_type'=>'customer','a.business_id'=>$business_id])
        ->groupBy('a.service_id')
        ->get();
        // dd($telecom);
        
        // dd($voter_id);'

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
            return view('admin.accounts.api.user-api-ajax',compact('items'));
        else
            return view('admin.accounts.api.user-api',compact('items'));

        // return view('admin.accounts.api.user-api',compact('aadhar','pan','voter_id','rc','dl','passport','bank','gst','telecom','e_court','upi','cin'));
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
            ->where(['a.source_reference'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
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
                ->where(['a.source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
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
                    ->where(['a.source_reference'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
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
                ->where(['a.source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
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
                    ->where(['a.source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
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
                    ->where(['a.source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
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
                    ->where(['a.source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
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
                ->where(['a.source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
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
                    ->where(['a.source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
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
                    ->where(['a.source_type'=>'API','a.user_type'=>'customer','a.business_id'=>$business_id])
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
                    ->where(['a.source_type'=>'API','a.user_type'=>'customer','a.business_id'=>$business_id])
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
                    ->where(['a.source_type'=>'API','a.user_type'=>'customer','a.business_id'=>$business_id])
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
            return view('admin.accounts.api.api-details_ajax',compact('data','service_d'));
        else
            return view('admin.accounts.api.api-details',compact('data','service_d'));
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

        // try{
            if($service_id=='2')
            {
                if($request->type=="pdf")
                {
                    $path=public_path().'/api/admin/pdf/aadhar/';

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
                            ->where(['source_reference'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
                            ->orderBy('a.id','desc')
                            ->get();

                    $pdf =new PDF;

                    $pdf = PDF::loadView('admin.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                        'title' => 'API Details',
                        'margin_top' => 20,
                        'margin-header'=>20,
                        'margin_bottom' =>25,
                        'margin_footer'=>5,
                    ])->save(public_path()."/api/admin/pdf/aadhar/".$file_name);
                    
                    // echo url('/').'/api/pdf/aadhar/'.$file_name;
                    
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/pdf/aadhar/'.$file_name
                    ]);

                }
                elseif($request->type=="excel")
                {
                    $path=public_path().'/api/admin/excel/aadhar/';

                    if(!File::exists($path))
                    {
                        File::makeDirectory($path, $mode = 0777, true, true);
                    }

                    if (File::exists($path)) 
                    {
                        File::cleanDirectory($path);
                    }
                    $file_name='aadhar-'.date('Ymdhis').'.xlsx';
                    Excel::store(new AadharExport($business_id), '/api/admin/excel/aadhar/'.$file_name, 'real_public');
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/excel/aadhar/'.$file_name
                    ]);
                }
            }
            elseif($service_id=='3')
            {
                if($request->type=="pdf")
                {
                    $path=public_path().'/api/admin/pdf/pan/';

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
                            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
                            ->orderBy('a.id','desc')
                            ->get();

                    $pdf =new PDF;

                    $pdf = PDF::loadView('admin.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                        'title' => 'API Details',
                        'margin_top' => 20,
                        'margin-header'=>20,
                        'margin_bottom' =>25,
                        'margin_footer'=>5,
                    ])->save(public_path()."/api/admin/pdf/pan/".$file_name);
                    
                    // echo url('/').'/api/pdf/aadhar/'.$file_name;
                    
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/pdf/pan/'.$file_name
                    ]);

                }
                elseif($request->type=="excel")
                {
                    $path=public_path().'/api/admin/excel/pan/';

                    if(!File::exists($path))
                    {
                        File::makeDirectory($path, $mode = 0777, true, true);
                    }

                    if (File::exists($path)) 
                    {
                        File::cleanDirectory($path);
                    }
                    $file_name='pan-'.date('Ymdhis').'.xlsx';
                    Excel::store(new PanExport($business_id), '/api/admin/excel/pan/'.$file_name, 'real_public');
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/excel/pan/'.$file_name
                    ]);
                }
            }
            elseif($service_id=='4')
            {
                if($request->type=="pdf")
                {
                    $path=public_path().'/api/admin/pdf/voterid/';

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
                            ->where(['source_reference'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
                            ->orderBy('a.id','desc')
                            ->get();

                    $pdf =new PDF;

                    $pdf = PDF::loadView('admin.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                        'title' => 'API Details',
                        'margin_top' => 20,
                        'margin-header'=>20,
                        'margin_bottom' =>25,
                        'margin_footer'=>5,
                    ])->save(public_path()."/api/admin/pdf/voterid/".$file_name);
                    
                    // echo url('/').'/api/pdf/aadhar/'.$file_name;
                    
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/pdf/voterid/'.$file_name
                    ]);

                }
                elseif($request->type=="excel")
                {
                    $path=public_path().'/api/admin/excel/voterid/';

                    if(!File::exists($path))
                    {
                        File::makeDirectory($path, $mode = 0777, true, true);
                    }

                    if (File::exists($path)) 
                    {
                        File::cleanDirectory($path);
                    }

                    $file_name='voterid-'.date('Ymdhis').'.xlsx';
                    Excel::store(new VoteridExport($business_id), '/api/admin/excel/voterid/'.$file_name, 'real_public');
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/excel/voterid/'.$file_name
                    ]);
                }
                
            }
            elseif($service_id=='7')
            {
                if($request->type=="pdf")
                {
                    $path=public_path().'/api/admin/pdf/rc/';

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
                            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
                            ->orderBy('a.id','desc')
                            ->get();

                    $pdf =new PDF;

                    $pdf = PDF::loadView('admin.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                        'title' => 'API Details',
                        'margin_top' => 20,
                        'margin-header'=>20,
                        'margin_bottom' =>25,
                        'margin_footer'=>5,
                    ])->save(public_path()."/api/admin/pdf/rc/".$file_name);
                    
                    // echo url('/').'/api/pdf/aadhar/'.$file_name;
                    
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/pdf/rc/'.$file_name
                    ]);

                }
                elseif($request->type=="excel")
                {
                    $path=public_path().'/api/admin/excel/rc/';

                    if(!File::exists($path))
                    {
                        File::makeDirectory($path, $mode = 0777, true, true);
                    }

                    if (File::exists($path)) 
                    {
                        File::cleanDirectory($path);
                    }

                    $file_name='rc-'.date('Ymdhis').'.xlsx';
                    Excel::store(new RcExport($business_id), '/api/admin/excel/rc/'.$file_name, 'real_public');
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/excel/rc/'.$file_name
                    ]);
                }
                
            }
            elseif($service_id=='8')
            {
                if($request->type=="pdf")
                {
                    $path=public_path().'/api/admin/pdf/passport/';

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
                            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
                            ->orderBy('a.id','desc')
                            ->get();

                    $pdf =new PDF;

                    $pdf = PDF::loadView('admin.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                        'title' => 'API Details',
                        'margin_top' => 20,
                        'margin-header'=>20,
                        'margin_bottom' =>25,
                        'margin_footer'=>5,
                    ])->save(public_path()."/api/admin/pdf/passport/".$file_name);
                    
                    // echo url('/').'/api/pdf/aadhar/'.$file_name;
                    
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/pdf/passport/'.$file_name
                    ]);

                }
                elseif($request->type=="excel")
                {
                    $path=public_path().'/api/admin/excel/passport/';

                    if(!File::exists($path))
                    {
                        File::makeDirectory($path, $mode = 0777, true, true);
                    }

                    if (File::exists($path)) 
                    {
                        File::cleanDirectory($path);
                    }

                    $file_name='passport-'.date('Ymdhis').'.xlsx';
                    Excel::store(new PassportExport($business_id), '/api/admin/excel/passport/'.$file_name, 'real_public');
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/excel/passport/'.$file_name
                    ]);
                }
                
            }
            elseif($service_id=='9')
            {
                if($request->type=="pdf")
                {
                    $path=public_path().'/api/admin/pdf/driving/';

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
                            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
                            ->orderBy('a.id','desc')
                            ->get();

                    $pdf =new PDF;

                    $pdf = PDF::loadView('admin.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                        'title' => 'API Details',
                        'margin_top' => 20,
                        'margin-header'=>20,
                        'margin_bottom' =>25,
                        'margin_footer'=>5,
                    ])->save(public_path()."/api/admin/pdf/driving/".$file_name);
                    
                    // echo url('/').'/api/pdf/aadhar/'.$file_name;
                    
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/pdf/driving/'.$file_name
                    ]);

                }
                elseif($request->type=="excel")
                {
                    $path=public_path().'/api/admin/excel/driving/';

                    if(!File::exists($path))
                    {
                        File::makeDirectory($path, $mode = 0777, true, true);
                    }

                    if (File::exists($path)) 
                    {
                        File::cleanDirectory($path);
                    }

                    $file_name='driving-'.date('Ymdhis').'.xlsx';
                    Excel::store(new DrivingExport($business_id), '/api/admin/excel/driving/'.$file_name, 'real_public');
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/excel/driving/'.$file_name
                    ]);
                }
                
            }
            elseif($service_id=='12')
            {
                if($request->type=="pdf")
                {
                    $path=public_path().'/api/admin/pdf/bank/';

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
                            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
                            ->orderBy('a.id','desc')
                            ->get();

                    $pdf =new PDF;

                    $pdf = PDF::loadView('admin.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                        'title' => 'API Details',
                        'margin_top' => 20,
                        'margin-header'=>20,
                        'margin_bottom' =>25,
                        'margin_footer'=>5,
                    ])->save(public_path()."/api/admin/pdf/bank/".$file_name);
                    
                    // echo url('/').'/api/pdf/aadhar/'.$file_name;
                    
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/pdf/bank/'.$file_name
                    ]);

                }
                elseif($request->type=="excel")
                {
                    $path=public_path().'/api/admin/excel/bank/';

                    if(!File::exists($path))
                    {
                        File::makeDirectory($path, $mode = 0777, true, true);
                    }

                    if (File::exists($path)) 
                    {
                        File::cleanDirectory($path);
                    }

                    $file_name='bank-'.date('Ymdhis').'.xlsx';
                    Excel::store(new BankExport($business_id), '/api/admin/excel/bank/'.$file_name, 'real_public');
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/excel/bank/'.$file_name
                    ]);
                }
                
            }
            elseif($service_id=='14')
            {
                if($request->type=="pdf")
                {
                    $path=public_path().'/api/admin/pdf/gst/';

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
                            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
                            ->orderBy('a.id','desc')
                            ->first();

                    $pdf =new PDF;

                    $pdf = PDF::loadView('admin.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                        'title' => 'API Details',
                        'margin_top' => 20,
                        'margin-header'=>20,
                        'margin_bottom' =>25,
                        'margin_footer'=>5,
                    ])->save(public_path()."/api/admin/pdf/gst/".$file_name);
                    
                    // echo url('/').'/api/pdf/aadhar/'.$file_name;
                    
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/pdf/gst/'.$file_name
                    ]);

                }
                elseif($request->type=="excel")
                {
                    $path=public_path().'/api/admin/excel/gst/';

                    if(!File::exists($path))
                    {
                        File::makeDirectory($path, $mode = 0777, true, true);
                    }

                    if (File::exists($path)) 
                    {
                        File::cleanDirectory($path);
                    }

                    $file_name='gst-'.date('Ymdhis').'.xlsx';
                    Excel::store(new GstExport($business_id), '/api/admin/excel/gst/'.$file_name, 'real_public');
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/excel/gst/'.$file_name
                    ]);
                }
                
            }
            elseif($service_id=='19')
            {
                if($request->type=="pdf")
                {
                    $path=public_path().'/api/admin/pdf/telecom/';

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
                            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$business_id])
                            ->orderBy('a.id','desc')
                            ->get();

                    $pdf =new PDF;

                    $pdf = PDF::loadView('admin.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                        'title' => 'API Details',
                        'margin_top' => 20,
                        'margin-header'=>20,
                        'margin_bottom' =>25,
                        'margin_footer'=>5,
                    ])->save(public_path()."/api/admin/pdf/telecom/".$file_name);
                    
                    // echo url('/').'/api/pdf/aadhar/'.$file_name;
                    
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/pdf/telecom/'.$file_name
                    ]);

                }
                elseif($request->type=="excel")
                {
                    $path=public_path().'/api/admin/excel/telecom/';

                    if(!File::exists($path))
                    {
                        File::makeDirectory($path, $mode = 0777, true, true);
                    }

                    if (File::exists($path)) 
                    {
                        File::cleanDirectory($path);
                    }

                    $file_name='telecom-'.date('Ymdhis').'.xlsx';
                    Excel::store(new TelecomExport($business_id), '/api/admin/excel/telecom/'.$file_name, 'real_public');
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/excel/telecom/'.$file_name
                    ]);
                }
                
            }
            elseif(stripos($service_d->type_name,'e_court')!==false)
            {
                if($request->type=="pdf")
                {
                    $path=public_path().'/api/admin/pdf/ecourt/';

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
                                ->where(['a.source_type'=>'API','a.user_type'=>'customer','a.business_id'=>$business_id])
                                ->orderBy('a.id','desc')
                                ->get();

                    $pdf =new PDF;

                    $pdf = PDF::loadView('admin.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                        'title' => 'API Details',
                        'margin_top' => 20,
                        'margin-header'=>20,
                        'margin_bottom' =>25,
                        'margin_footer'=>5,
                    ])->save(public_path()."/api/admin/pdf/ecourt/".$file_name);
                    
                    // echo url('/').'/api/pdf/aadhar/'.$file_name;
                    
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/pdf/ecourt/'.$file_name
                    ]);

                }
                elseif($request->type=="excel")
                {
                    $path=public_path().'/api/admin/excel/ecourt/';

                    if(!File::exists($path))
                    {
                        File::makeDirectory($path, $mode = 0777, true, true);
                    }

                    if (File::exists($path)) 
                    {
                        File::cleanDirectory($path);
                    }

                    $file_name='e_court-'.date('Ymdhis').'.xlsx';
                    Excel::store(new EcourtExport($business_id), '/api/admin/excel/ecourt/'.$file_name, 'real_public');
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/excel/ecourt/'.$file_name
                    ]);
                }
            }
            elseif(stripos($service_d->type_name,'upi')!==false)
            {
                if($request->type=="pdf")
                {
                    $path=public_path().'/api/admin/pdf/upi/';

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
                                ->where(['a.source_type'=>'API','a.user_type'=>'customer','a.business_id'=>$business_id])
                                ->orderBy('a.id','desc')
                                ->get();

                    $pdf =new PDF;

                    $pdf = PDF::loadView('admin.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                        'title' => 'API Details',
                        'margin_top' => 20,
                        'margin-header'=>20,
                        'margin_bottom' =>25,
                        'margin_footer'=>5,
                    ])->save(public_path()."/api/admin/pdf/upi/".$file_name);
                    
                    // echo url('/').'/api/pdf/aadhar/'.$file_name;
                    
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/pdf/upi/'.$file_name
                    ]);

                }
                elseif($request->type=="excel")
                {
                    $path=public_path().'/api/admin/excel/upi/';

                    if(!File::exists($path))
                    {
                        File::makeDirectory($path, $mode = 0777, true, true);
                    }

                    if (File::exists($path)) 
                    {
                        File::cleanDirectory($path);
                    }

                    $file_name='upi-'.date('Ymdhis').'.xlsx';
                    Excel::store(new UPIExport($business_id), '/api/admin/excel/upi/'.$file_name, 'real_public');
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/excel/upi/'.$file_name
                    ]);
                }
            }
            elseif(stripos($service_d->type_name,'cin')!==false)
            {
                if($request->type=="pdf")
                {
                    $path=public_path().'/api/admin/pdf/cin/';

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
                                ->where(['a.source_type'=>'API','a.user_type'=>'customer','a.business_id'=>$business_id])
                                ->orderBy('a.id','desc')
                                ->get();

                    $pdf =new PDF;

                    $pdf = PDF::loadView('admin.accounts.api.pdf-api_details', compact('data','service_d'),[],[
                        'title' => 'API Details',
                        'margin_top' => 20,
                        'margin-header'=>20,
                        'margin_bottom' =>25,
                        'margin_footer'=>5,
                    ])->save(public_path()."/api/admin/pdf/cin/".$file_name);
                    
                    // echo url('/').'/api/pdf/aadhar/'.$file_name;
                    
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/pdf/cin/'.$file_name
                    ]);

                }
                elseif($request->type=="excel")
                {
                    $path=public_path().'/api/admin/excel/cin/';

                    if(!File::exists($path))
                    {
                        File::makeDirectory($path, $mode = 0777, true, true);
                    }

                    if (File::exists($path)) 
                    {
                        File::cleanDirectory($path);
                    }

                    $file_name='cin-'.date('Ymdhis').'.xlsx';
                    Excel::store(new CINExport($business_id), '/api/admin/excel/cin/'.$file_name, 'real_public');
                    return response()->json([
                        'success' => true,
                        'url' => url('/').'/api/admin/excel/cin/'.$file_name
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
        // }
        // catch (\Exception $e) {
        //     // something went wrong
        //     return $e;
        // } 


    }


    public function checkPriceMaster(Request $request)
    {
        // $parent_id=Auth::user()->parent_id;
        // dd($parent_id);
        $business_id=Auth::user()->business_id;
        $parent_id=Auth::user()->parent_id;

        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users_d=DB::table('users')->where(['id'=>$business_id])->first();
            $parent_id=$users_d->parent_id;
        }
        
        $checkserviceprice= DB::table('services as s')
                            ->select('s.name as service_name','s.verification_type','s.id as service_id','s.business_id')
                            // DB::table('check_price_masters as cm')
                            // ->select('s.name as service_name','cm.price as default_price','s.verification_type','cm.service_id as service_id')
                            // ->join('services as s','s.id','=','cm.service_id')
                            // ->join('check_prices as c','s.id','=','c.service_id')
                            ->whereNotIn('s.type_name',['e_court'])
                            ->where(['s.status'=>'1','s.business_id'=>NULL,'s.verification_type'=>'Auto']);
                            // ->orwhere('s.business_id',$business_id);
                            if(is_numeric($request->get('service_id'))){
                                $checkserviceprice->where('s.id',$request->get('service_id'));
                            }
                            $items=$checkserviceprice->paginate(10);


        $services=DB::table('services as s')
        ->select('s.name','s.id','s.verification_type')
        ->where('status','1')
        ->whereNotIn('type_name',['e_court'])
        ->where('verification_type','Auto')
        // ->orwhere('business_id',$business_id)
        ->get();
        // dd($checkserviceprice);
        if($request->ajax())
            return view('admin.accounts.checkprice.ajax',compact('items','services','parent_id'));
        else
            return view('admin.accounts.checkprice.index',compact('items','services','parent_id'));
    }

    // public function checkPriceAdmin()
    // {
    //     $business_id=Auth::user()->business_id;
    //     // dd($parent_id);
    //     $checkserviceprice=DB::table('check_prices as c')
    //                         ->select('s.name as service_name','c.price')
    //                         ->join('services as s','s.id','=','c.service_id')
    //                         ->where(['c.business_id'=>$business_id])
    //                         ->get();
    //     // dd($checkserviceprice);
    //     return view('admin.accounts.checkprice.admin',compact('checkserviceprice'));
    // }

    public function checkPriceCustomerStore(Request $request)
    {
         $business_id=Auth::user()->business_id;
         $user_id=Auth::user()->id;
         
         $parent_id=Auth::user()->parent_id;
         if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
         {
             $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
             $parent_id=$users->parent_id;
         }
 
         $rules= 
         [
             'services'    => 'required|array|min:1', 
             'new_price'  => 'required|numeric', 
         ];
         $validator = Validator::make($request->all(), $rules);
         
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type'=> 'validation',
                 'errors' => $validator->errors()
             ]);
         }
         DB::beginTransaction();
         try{
             if( count($request->input('services') ) > 0 ){
                 foreach($request->input('services') as $service){
                     $check_price=DB::table('check_prices as c')
                     ->select('s.id','s.name')
                     ->join('services as s','c.service_id','=','s.id')
                     ->where(['c.business_id'=>$business_id,'c.service_id'=>$service])
                     ->first();
                     $service_name='';
                     if($check_price!=NULL)
                     {
                         $service_name.=$check_price->name;
 
                         return response()->json([
                             'fail' => true,
                             'error_type'=> 'validation',
                             'errors' => ['services'=> $service_name.' is already exist !']
                         ]);
                     }
                 }
             }
 
             if( count($request->input('services') ) > 0 ){
                 foreach($request->input('services') as $service){
 
                     $data=[
                         'parent_id' => $parent_id,
                         'business_id' => $business_id,
                         'service_id' => $service,
                         'created_by' => $user_id,
                         'used_by'  => 'customer',
                         'price' => $request->new_price,
                         'created_at' => date('Y-m-d H:i:s')
                     ];
 
                     DB::table('check_prices')->insert($data);
                 }
 
                 DB::commit();
                 return response()->json([
                     'fail' => false,
                 ]);
 
             }
         }
         catch (\Exception $e) {
             DB::rollback();
             // something went wrong
             return $e;
         }  
 
 
    }

   public function checkPriceUpdate(Request $request){
        
        $id=base64_decode($request->id);

        $rules= [
            'price'         => 'required|numeric'
            
         ];
        $validator = Validator::make($request->all(), $rules);
          
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type'=>'validation',
                 'errors' => $validator->errors()
             ]);
         }

        DB::table('check_prices')->where(['id'=>$id])->update([
            'price' =>$request->price,
            'updated_by'=> Auth::user()->id,
            'updated_at'=>date('Y-m-d H:i:s')
        ]);
        
        return response()->json([
            'fail' => false,
        ]);

   }
   
   public function checkPriceCustomerWise(Request $request)
   {
        $business_id=Auth::user()->business_id;
        // dd($parent_id);
        $checkserviceprice=DB::table('check_price_cocs as c')
                            ->select('s.name as service_name','c.price','c.id','s.verification_type','c.coc_id')
                            ->join('services as s','s.id','=','c.service_id')
                            ->where(['c.business_id'=>$business_id,'s.status'=>'1','s.verification_type'=>'Auto']);
                            if(is_numeric($request->get('customer_id'))){
                                $checkserviceprice->where('c.coc_id',$request->get('customer_id'));
                            }
                            if(is_numeric($request->get('service_id'))){
                                $checkserviceprice->where('c.service_id',$request->get('service_id'));
                            }

        $items=$checkserviceprice->paginate(10);
        

        $customers = DB::table('users as u')
            ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
            ->join('user_businesses as b','b.business_id','=','u.id')
            ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
            ->whereNotIn('u.id',[$business_id])
            ->get();

        $services=DB::table('services as s')
        ->select('s.name','s.id','s.verification_type')
        ->where('status','1')
        ->where('verification_type','Auto')
        ->whereNotIn('type_name',['e_court'])
        ->get();

        if($request->ajax())
            return view('admin.accounts.checkprice.check_price_coc_ajax',compact('items','customers','services'));
        else
            return view('admin.accounts.checkprice.check_price_coc',compact('items','customers','services'));
   }

   public function checkPriceCustomerWiseStore(Request $request)
   {
        $business_id=Auth::user()->business_id;
        $user_id=Auth::user()->id;
        
        $parent_id=Auth::user()->parent_id;
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }

        $rules= 
        [
            'customer'   => 'required', 
            'services'    => 'required|array|min:1',
            'new_price'  => 'required|numeric', 
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'error_type'=> 'validation',
                'errors' => $validator->errors()
            ]);
        }

        DB::beginTransaction();
        try{
            $checkprice=DB::table('check_price_cocs')
                        ->where(['coc_id'=>$request->customer])
                        ->whereIn('service_id',$request->services)
                        ->count();
            
            // dd($checkprice);

            if($checkprice > 0)
            {
                return response()->json([
                    'fail' => true,
                    'error_type'=> 'validation',
                    'errors' => ['services'=>'Customer Selected Service Price is Already Exist!!']
                ]);
            }

            if(count($request->services)>0)
            {
                foreach($request->services as $service_id)
                {
                    DB::table('check_price_cocs')->insert(
                        [
                            'parent_id' => $parent_id,
                            'business_id' => $business_id,
                            'coc_id' => $request->customer,
                            'service_id' => $service_id,
                            'user_id' => $user_id,
                            'used_by'  => 'customer',
                            'price' => $request->new_price,
                            'created_at' => date('Y-m-d H:i:s')
                        ]
                    );
                }
            }
            DB::commit();
            return response()->json([
                'fail' => false,
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }  

   }

    public function checkPriceCustomerUpdate(Request $request){
        
        $id=base64_decode($request->id);

        $rules= [
            'price'         => 'required|numeric'
            
         ];
        $validator = Validator::make($request->all(), $rules);
          
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type'=>'validation',
                 'errors' => $validator->errors()
             ]);
         }

        DB::table('check_price_cocs')->where(['id'=>$id])->update([
            'price' =>$request->price,
            'updated_by'=> Auth::user()->id,
            'updated_at'=>date('Y-m-d H:i:s')
        ]);
        
        return response()->json([
            'fail' => false,
        ]);

    }

    public function checkPriceSetting(Request $request)
    {
        $business_id=Auth::user()->business_id;

        $items = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name','u.created_at')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id]);

        if(is_numeric($request->get('customer_id'))){
            $items->where('u.business_id',$request->get('customer_id'));
        }
        $items=$items->paginate(10);

        $customers = DB::table('users as u')
        ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id])
        ->get();

        if($request->ajax())
            return view('admin.accounts.checkprice.setting_ajax',compact('items','customers'));
        else
            return view('admin.accounts.checkprice.setting',compact('items','customers'));
        
    }

    // Create Function for hide a check price for coc
    public function hideCheckPriceCOC(Request $request)
    {
      $parent_id=Auth::user()->parent_id;
      $business_id=Auth::user()->business_id;
      $user_id=Auth::user()->id;
      $customer_id = base64_decode($request->get('customer_id'));  
      if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }
      // echo('abc');
      // dd($candidate_id);
      $hold_data= DB::table('customer_check_price_showing_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->first();
        if($hold_data!=NULL)
        {
            $data=[
                'hide_by' => $user_id,
                'hide_at' => date('Y-m-d H:i:s'),
                'shown_by' => NULL,
                'shown_at' => NULL,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            DB::table('customer_check_price_showing_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->update($data);
        }
        else
        {
            $data=[
                'parent_id' => $parent_id,
                'business_id' => $business_id,
                'coc_id' => $customer_id,
                'hide_by' => $user_id,
                'hide_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            DB::table('customer_check_price_showing_statuses')->insert($data);
        }
        
      $hold_data= DB::table('customer_check_price_showing_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id,'shown_at'=>null])->first();

      $hold_log_data=DB::table('customer_check_price_showing_status_logs')->insert([
        'parent_id'=>$parent_id,
        'business_id'=> $business_id,
        'coc_id' => $customer_id,
        'user_id' => $user_id,
        'status' => 'hide',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
        ]);

      if ($hold_data) {
        return response()->json([
          'status'=>'ok',
          'message' => 'Hold',                
          ], 200);
      }else{
        return response()->json([
        'status' =>'no',
        ], 200);
      }
      
    }

     // Update show check price for coc
    public function showCheckPriceCOC(Request $request)
    {
        $parent_id=Auth::user()->parent_id;
        $business_id=Auth::user()->business_id;
        $user_id=Auth::user()->id;
        $customer_id = base64_decode($request->get('customer_id'));   

        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }
        
        $data=[
            'shown_by' => $user_id,
            'shown_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
    
        $hold_data=DB::table('customer_check_price_showing_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id,'shown_at'=>null])->update($data);

        $hold_log_data=DB::table('customer_check_price_showing_status_logs')->insert([
            'parent_id'=>$parent_id,
            'business_id'=> $business_id,
            'coc_id' => $customer_id,
            'user_id' => $user_id,
            'status' => 'show',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        if ($hold_data) {
            return response()->json([
            'status'=>'ok',
            'message' => 'removed',                
            ], 200);
        }else{
            return response()->json([
            'status' =>'no',
            ], 200);
        }
    
    }

    public function verificationCustomerWise(Request $request)
    {
        $business_id=Auth::user()->business_id;

        $items = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name','u.created_at')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id]);

        if(is_numeric($request->get('customer_id'))){
            $items->where('u.business_id',$request->get('customer_id'));
        }
        $items=$items->paginate(10);

        $customers = DB::table('users as u')
        ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id])
        ->get();

        if($request->ajax())
            return view('admin.accounts.verifications.ajax',compact('items','customers'));
        else
            return view('admin.accounts.verifications.index',compact('items','customers'));
    }


    
    // Create Function for hide a verification for coc
    public function hideVerificationCOCWise(Request $request)
    {
      $parent_id=Auth::user()->parent_id;
      $business_id=Auth::user()->business_id;
      $user_id=Auth::user()->id;
      $customer_id = base64_decode($request->get('customer_id'));  

      if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }
        DB::beginTransaction();
        try{
            // echo('abc');
            // dd($candidate_id);
            $hold_data= DB::table('customer_verification_showing_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->first();
            if($hold_data!=NULL)
            {
                $data=[
                    'hide_by' => $user_id,
                    'hide_at' => date('Y-m-d H:i:s'),
                    'shown_by' => NULL,
                    'shown_at' => NULL,
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                DB::table('customer_verification_showing_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->update($data);
            }
            else
            {
                $data=[
                    'parent_id' => $parent_id,
                    'business_id' => $business_id,
                    'coc_id' => $customer_id,
                    'hide_by' => $user_id,
                    'hide_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            
                DB::table('customer_verification_showing_statuses')->insert($data);
            }
                
            DB::table('customer_verification_showing_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id,'shown_at'=>null])->first();

            $hold_log_data=DB::table('customer_verification_showing_status_logs')->insert([
                'parent_id'=>$parent_id,
                'business_id'=> $business_id,
                'coc_id' => $customer_id,
                'user_id' => $user_id,
                'status' => 'hide',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                ]);

                $hold_data = TRUE;
            if ($hold_data) {
                DB::commit();
                return response()->json([
                'status'=>'ok',
                'message' => 'Hold',                
                ], 200);
            }else{
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

     // Update show verification for coc
    public function showVerificationCOCWise(Request $request)
    {
        $parent_id=Auth::user()->parent_id;
        $business_id=Auth::user()->business_id;
        $user_id=Auth::user()->id;
        $customer_id = base64_decode($request->get('customer_id'));
        
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }
        
        DB::beginTransaction();
        try{
                $data=[
                    'shown_by' => $user_id,
                    'shown_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                
                DB::table('customer_verification_showing_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id,'shown_at'=>null])->update($data);

                $hold_log_data=DB::table('customer_verification_showing_status_logs')->insert([
                'parent_id'=>$parent_id,
                'business_id'=> $business_id,
                'coc_id' => $customer_id,
                'user_id' => $user_id,
                'status' => 'show',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                ]);

                $hold_data = TRUE;
                
                if ($hold_data) {
                    DB::commit();
                    return response()->json([
                    'status'=>'ok',
                    'message' => 'removed',                
                    ], 200);
                }else{
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

    public function verificationCustomerServiceWise(Request $request)
    {
        $business_id=Auth::user()->business_id;

        $items = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name','u.created_at')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id]);

        if(is_numeric($request->get('customer_id'))){
            $items->where('u.business_id',$request->get('customer_id'));
        }
        $items=$items->paginate(10);

        $customers = DB::table('users as u')
        ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id])
        ->get();

        $services=DB::table('services')->where(['status'=>'1','verification_type'=>'Auto'])->whereNotIn('type_name',['e_court'])->get();

        if($request->ajax())
            return view('admin.accounts.verifications.service_wise_ajax',compact('items','customers','services'));
        else
            return view('admin.accounts.verifications.service_wise',compact('items','customers','services'));
    }

    public function showhideVerificationServiceWise(Request $request)
    {

        $check_status=$request->services;
        $parent_id=Auth::user()->parent_id;
        $business_id=Auth::user()->business_id;
        $user_id=Auth::user()->id;
        $customer_id = base64_decode($request->get('customer_id'));   
        $service_id=base64_decode($request->get('service_id'));

        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }

      DB::beginTransaction();
      try
      {
        if($check_status==0)
        {
            $hold_data= DB::table('customer_verification_service_showing_statuses')->where(['coc_id'=>$customer_id,'service_id'=>$service_id,'business_id'=>$business_id])->first();
            if($hold_data!=NULL)
            {
                $data=[
                    'hide_by' => $user_id,
                    'hide_at' => date('Y-m-d H:i:s'),
                    'shown_by' => NULL,
                    'shown_at' => NULL,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                DB::table('customer_verification_service_showing_statuses')->where(['coc_id'=>$customer_id,'service_id'=>$service_id,'business_id'=>$business_id])->update($data);
            }
            else
            {
                $data=[
                    'parent_id' => $parent_id,
                    'business_id' => $business_id,
                    'coc_id' => $customer_id,
                    'service_id' => $service_id,
                    'hide_by' => $user_id,
                    'hide_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                DB::table('customer_verification_service_showing_statuses')->insert($data);
            }
            
            $hold_data= DB::table('customer_verification_service_showing_statuses')->where(['coc_id'=>$customer_id,'service_id'=>$service_id,'business_id'=>$business_id,'shown_at'=>null])->first();

            $hold_log_data=DB::table('customer_verification_service_showing_status_logs')->insert([
                'parent_id'=>$parent_id,
                'business_id'=> $business_id,
                'coc_id' => $customer_id,
                'service_id'=>$service_id,
                'user_id' => $user_id,
                'status' => 'hide',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        else if($check_status==1)
        {
            $data=[
                'shown_by' => $user_id,
                'shown_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        
            $hold_data=DB::table('customer_verification_service_showing_statuses')->where(['coc_id'=>$customer_id,'service_id'=>$service_id,'business_id'=>$business_id,'shown_at'=>null])->update($data);
    
            $hold_log_data=DB::table('customer_verification_service_showing_status_logs')->insert([
            'parent_id'=>$parent_id,
            'business_id'=> $business_id,
            'coc_id' => $customer_id,
            'service_id'=>$service_id,
            'user_id' => $user_id,
            'status' => 'show',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        if ($hold_data) {
            DB::commit();
            return response()->json([
              'status'=>'ok',
              'message' => 'Verification Check Done Successfully !',                
              ], 200);
          }else{
            return response()->json([
            'status' =>'no',
            ], 200);
        }
      }
      catch (\Exception $e)
      {
            DB::rollback();
            // something went wrong
            return $e;
      }  

    }
    
    public function templateThreeReport(Request $request)
    {
        $business_id=Auth::user()->business_id;
        $customers = DB::table('users as u')
                ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                ->join('user_businesses as b','b.business_id','=','u.id')
                ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                ->whereNotIn('u.id',[$business_id])
                ->get();
        $items = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name','u.created_at')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id]);

        if(is_numeric($request->get('customer_id'))){
            $items->where('u.business_id',$request->get('customer_id'));
        }
        $items=$items->paginate(10);
        if($request->ajax())
            return view('admin.accounts.reports.report_template3-ajax',compact('items','customers'));
        else
            return view('admin.accounts.reports.report_template3',compact('items','customers'));
    }

     // Create Function for hide a pdf page  for coc
     public function templateThreeReportHide(Request $request)
     {
       $parent_id=Auth::user()->parent_id;
       $business_id=Auth::user()->business_id;
       $user_id=Auth::user()->id;
       $customer_id = base64_decode($request->get('customer_id'));  
       // echo('abc');
       // dd($candidate_id);
       $check_hold_data= DB::table('report_add_page_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id,'template_type' =>'3'])->first();
 
         if ($check_hold_data) {
 
         $data=[
                 'template_type' =>'1',
                 ];
            DB::table('report_add_page_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->update($data);
 
         }
         // else{
         //     $data=[
         //         'parent_id' => $parent_id,
         //         'business_id' => $business_id,
         //         'coc_id' => $customer_id,
         //         'status' => 'disable',
         //         'template_type' =>'1',
               
         //     ];
           
         //   DB::table('report_add_page_statuses')->insert($data);
         // }
         
       $hold_data= DB::table('report_add_page_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id,'template_type' =>'1'])->first();
 
       if ($hold_data) {
         return response()->json([
           'status'=>'ok',
           'message' => 'Hold',                
           ], 200);
       }else{
         return response()->json([
         'status' =>'no',
         ], 200);
       }
       
     }
 
      // Update show verification a pdf page for coc
     public function templateThreeReportShow(Request $request)
     {
         $parent_id=Auth::user()->parent_id;
         $business_id=Auth::user()->business_id;
         $user_id=Auth::user()->id;
         $customer_id = base64_decode($request->get('customer_id'));   
         
         $check_hold_data= DB::table('report_add_page_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->whereIn('template_type',['1','2','4'])->first();
         if ($check_hold_data) {
             $data=[
                 'enable_by' => $user_id,
                 'template_type' =>'3',
                 'updated_at' => date('Y-m-d H:i:s'),
             ];
         
             $hold_data=DB::table('report_add_page_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->update($data);
     
         } 
         // else {
         //     $data=[
 
         //         'parent_id' => $parent_id,
         //         'business_id' => $business_id,
         //         'coc_id' => $customer_id,
         //         'status' => 'enable',
         //         'enable_by' => $user_id,
         //         'template_type' =>'2',
         //         'created_at' => date('Y-m-d H:i:s'),
         //     ];
           
         //     $hold_data=  DB::table('report_add_page_statuses')->insert($data);
         // }
         
 
         // $data=[
         //     'enable_by' => $user_id,
         //     'updated_at' => date('Y-m-d H:i:s'),
         // ];
     
         // $hold_data=DB::table('report_add_page_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->update($data);
 
        
         
         if ($hold_data) {
             return response()->json([
             'status'=>'ok',
             'message' => 'removed',                
             ], 200);
         }else{
             return response()->json([
             'status' =>'no',
             ], 200);
         }
     
     }

    public function templateFourReport(Request $request)
    {
        $business_id=Auth::user()->business_id;
        $customers = DB::table('users as u')
                ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                ->join('user_businesses as b','b.business_id','=','u.id')
                ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                ->whereNotIn('u.id',[$business_id])
                ->get();
        $items = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name','u.created_at')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id]);

        if(is_numeric($request->get('customer_id'))){
            $items->where('u.business_id',$request->get('customer_id'));
        }
        $items=$items->paginate(10);
        if($request->ajax())
            return view('admin.accounts.reports.report_template4-ajax',compact('items','customers'));
        else
            return view('admin.accounts.reports.report_template4',compact('items','customers'));
    }

    // Create Function for hide a pdf page  for coc
    public function templateFourReportHide(Request $request)
    {
       $parent_id=Auth::user()->parent_id;
       $business_id=Auth::user()->business_id;
       $user_id=Auth::user()->id;
       $customer_id = base64_decode($request->get('customer_id'));  
       // echo('abc');
       // dd($candidate_id);
       $check_hold_data= DB::table('report_add_page_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id,'template_type' =>'4'])->first();
 
        if ($check_hold_data) {
 
         $data=[
                 'template_type' =>'1',
                 ];
            DB::table('report_add_page_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->update($data);
 
        }
         
         
        $hold_data= DB::table('report_add_page_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id,'template_type' =>'1'])->first();
    
        if ($hold_data) {
            return response()->json([
            'status'=>'ok',
            'message' => 'Hold',                
            ], 200);
        }else{
            return response()->json([
            'status' =>'no',
            ], 200);
        }
       
    }

     // Update show verification a pdf page for coc
     public function templateFourReportShow(Request $request)
     {
         $parent_id=Auth::user()->parent_id;
         $business_id=Auth::user()->business_id;
         $user_id=Auth::user()->id;
         $customer_id = base64_decode($request->get('customer_id'));   
         
         $check_hold_data= DB::table('report_add_page_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->whereIn('template_type',['1','2','3'])->first();
         if ($check_hold_data) {
             $data=[
                 'enable_by' => $user_id,
                 'template_type' =>'4',
                 'updated_at' => date('Y-m-d H:i:s'),
             ];
         
             $hold_data=DB::table('report_add_page_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->update($data);
     
         } 
         // else {
         //     $data=[
 
         //         'parent_id' => $parent_id,
         //         'business_id' => $business_id,
         //         'coc_id' => $customer_id,
         //         'status' => 'enable',
         //         'enable_by' => $user_id,
         //         'template_type' =>'2',
         //         'created_at' => date('Y-m-d H:i:s'),
         //     ];
           
         //     $hold_data=  DB::table('report_add_page_statuses')->insert($data);
         // }
         
 
         // $data=[
         //     'enable_by' => $user_id,
         //     'updated_at' => date('Y-m-d H:i:s'),
         // ];
     
         // $hold_data=DB::table('report_add_page_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->update($data);
 
        
         
         if ($hold_data) {
             return response()->json([
             'status'=>'ok',
             'message' => 'removed',                
             ], 200);
         }else{
             return response()->json([
             'status' =>'no',
             ], 200);
         }
     
     }

    public function defaultReport()
    {
        return view('admin.accounts.reports.default-report');
    }

    public function reportCustomerWise(Request $request)
    {
        $business_id=Auth::user()->business_id;

        $items = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name','u.created_at')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id]);

        if(is_numeric($request->get('customer_id'))){
            $items->where('u.business_id',$request->get('customer_id'));
        }
        $items=$items->paginate(10);

        $customers = DB::table('users as u')
        ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id])
        ->get();

        if($request->ajax())
            return view('admin.accounts.reports.ajax',compact('items','customers'));
        else
            return view('admin.accounts.reports.index',compact('items','customers'));
    }

    public function reportAddress(Request $request){
        $business_id=Auth::user()->business_id;
        $items = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name','u.created_at','u.business_id')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id]);

        if(is_numeric($request->get('customer_id'))){
            $items->where('u.business_id',$request->get('customer_id'));
        }
        $items=$items->paginate(10);

        $customers = DB::table('users as u')
        ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id])
        ->get();

        if($request->ajax())
            return view('admin.accounts.address.custom-ajax',compact('items','customers'));
        else
            return view('admin.accounts.address.custom-index',compact('items','customers'));
    }

      // Create Function for hide a pdf page  for coc
      public function hideReportAddress(Request $request)
      {
        $parent_id=Auth::user()->parent_id;
        $business_id=Auth::user()->business_id;
        $user_id=Auth::user()->id;
        $customer_id = base64_decode($request->get('customer_id'));  
        // echo('abc');
        // dd($candidate_id);
        $check_hold_data= DB::table('customer_p_d_f_addresses')->where(['business_id'=>$customer_id,'status'=>'1'])->first();
  
          if ($check_hold_data) {
  
          $data=[
                  'status' =>'0',
                  ];
             DB::table('customer_p_d_f_addresses')->where(['business_id'=>$customer_id,'status'=>'1'])->update($data);
  
          }
          // else{
          //     $data=[
          //         'parent_id' => $parent_id,
          //         'business_id' => $business_id,
          //         'coc_id' => $customer_id,
          //         'status' => 'disable',
          //         'template_type' =>'1',
                
          //     ]; 
            
          //   DB::table('report_add_page_statuses')->insert($data);
          // }
          
        $hold_data= DB::table('customer_p_d_f_addresses')->where(['business_id'=>$customer_id,'status'=>'0'])->first();
  
        if ($hold_data) {
          return response()->json([
            'status'=>'ok',
            'message' => 'Hold',                
            ], 200);
        }else{
          return response()->json([
          'status' =>'no',
          ], 200);
        }
        
      }
  
       // Update show verification a pdf page for coc
      public function showReportAddress(Request $request)
      {
          $parent_id=Auth::user()->parent_id;
          $business_id=Auth::user()->business_id;
          $user_id=Auth::user()->id;
          $customer_id = base64_decode($request->get('customer_id')); 
          $hold_data=null;  
          
          $check_hold_data= DB::table('customer_p_d_f_addresses')->where(['business_id'=>$customer_id ,'status'=>'0'])->first();
         
          if ($check_hold_data) {
            if ($check_hold_data->status=='0') {
                $data=[
                 
                    'status' =>'1',
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            
                $hold_data=DB::table('customer_p_d_f_addresses')->where(['business_id'=>$customer_id,'status'=>'0'])->update($data);
                // dd('xyz');
            } 
          } 
          else {
            // dd('xyz');
            $data=[
                'business_id' => $customer_id,
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
            ];
          
            $hold_data=  DB::table('customer_p_d_f_addresses')->insert($data);
          }
          
  
          // $data=[
          //     'enable_by' => $user_id,
          //     'updated_at' => date('Y-m-d H:i:s'),
          // ];
      
          // $hold_data=DB::table('report_add_page_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->update($data);
  
          $check_view_data= DB::table('customer_p_d_f_addresses')->where(['business_id'=>$customer_id,'status'=>'1'])->first();
          
          if ($check_view_data) {
              return response()->json([
              'status'=>'ok',
              'message' => 'removed',                
              ], 200);
          }else{
              return response()->json([
              'status' =>'no',
              ], 200);
          }
      
      }
    public function customerAddAddress(Request $request){
        // dd($request->customer_id);
       
        // $rules= [
        //     'address'  =>'required',
             
        //   ];
         
        //   $customMessages = [
        //     'address.required' => 'Address field is required',
           
        //  ];
   
        //   $validator = Validator::make($request->all(), $rules,$customMessages);
           
        //   if ($validator->fails()){
        //       return response()->json([
        //           'success' => false,
        //           'errors' => $validator->errors()
        //       ]);
        //   }

          $customer_id = base64_decode($request->get('customer_id')); 
        $address_pdf=DB::table('customer_p_d_f_addresses')->where(['business_id'=>$customer_id])->first();
        if ($address_pdf) {
            DB::table('customer_p_d_f_addresses')->where(['business_id'=>$customer_id])->update([
                'address'         => $request->address,
            ]);
            return response()->json([
                'success' =>true,
                'custom'  =>'yes',
                'errors'  =>[]
              ]); 
        }
        else{
            $data=[
                'business_id' => $customer_id,
                'address'         => $request->address,
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
            ];
          
            $hold_data=  DB::table('customer_p_d_f_addresses')->insert($data);
            return response()->json([
                'success' =>true,
                'custom'  =>'yes',
                'errors'  =>[]
              ]); 
        }
    }

    // Create Function for hide a pdf page  for coc
    public function hideReportCOCWise(Request $request)
    {
      $parent_id=Auth::user()->parent_id;
      $business_id=Auth::user()->business_id;
      $user_id=Auth::user()->id;
      $customer_id = base64_decode($request->get('customer_id'));  
      // echo('abc');
      // dd($candidate_id);
      $check_hold_data= DB::table('report_add_page_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id,'template_type' =>'2'])->first();

        if ($check_hold_data) {

        $data=[
                'template_type' =>'1',
                ];
           DB::table('report_add_page_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->update($data);

        }
        // else{
        //     $data=[
        //         'parent_id' => $parent_id,
        //         'business_id' => $business_id,
        //         'coc_id' => $customer_id,
        //         'status' => 'disable',
        //         'template_type' =>'1',
              
        //     ];
          
        //   DB::table('report_add_page_statuses')->insert($data);
        // }
        
      $hold_data= DB::table('report_add_page_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id,'template_type' =>'1'])->first();

      if ($hold_data) {
        return response()->json([
          'status'=>'ok',
          'message' => 'Hold',                
          ], 200);
      }else{
        return response()->json([
        'status' =>'no',
        ], 200);
      }
      
    }

     // Update show verification a pdf page for coc
    public function showReportCOCWise(Request $request)
    {
        $parent_id=Auth::user()->parent_id;
        $business_id=Auth::user()->business_id;
        $user_id=Auth::user()->id;
        $customer_id = base64_decode($request->get('customer_id'));   
        
        $check_hold_data= DB::table('report_add_page_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->whereIn('template_type',['1','3','4'])->first();
        if ($check_hold_data) {
            $data=[
                'enable_by' => $user_id,
                'template_type' =>'2',
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        
            $hold_data=DB::table('report_add_page_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->update($data);
    
        } 
        // else {
        //     $data=[

        //         'parent_id' => $parent_id,
        //         'business_id' => $business_id,
        //         'coc_id' => $customer_id,
        //         'status' => 'enable',
        //         'enable_by' => $user_id,
        //         'template_type' =>'2',
        //         'created_at' => date('Y-m-d H:i:s'),
        //     ];
          
        //     $hold_data=  DB::table('report_add_page_statuses')->insert($data);
        // }
        

        // $data=[
        //     'enable_by' => $user_id,
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ];
    
        // $hold_data=DB::table('report_add_page_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->update($data);

       
        
        if ($hold_data) {
            return response()->json([
            'status'=>'ok',
            'message' => 'removed',                
            ], 200);
        }else{
            return response()->json([
            'status' =>'no',
            ], 200);
        }
    
    }


    public function reportCustomPage(Request $request)
    {
        $business_id=Auth::user()->business_id;

        $items = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name','u.created_at')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id]);

        if(is_numeric($request->get('customer_id'))){
            $items->where('u.business_id',$request->get('customer_id'));
        }
        $items=$items->paginate(10);

        $customers = DB::table('users as u')
        ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id])
        ->get();

        if($request->ajax())
            return view('admin.accounts.reports.custom-ajax',compact('items','customers'));
        else
            return view('admin.accounts.reports.custom-index',compact('items','customers'));
    }

     // Create Function for hide a pdf page  for coc
    public function hideReportCustomPage(Request $request)
    {
      $parent_id=Auth::user()->parent_id;
      $business_id=Auth::user()->business_id;
      $user_id=Auth::user()->id;
      $customer_id = base64_decode($request->get('customer_id'));  
      // echo('abc');
      // dd($candidate_id);
      $check_hold_data= DB::table('report_custom_pages')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->first();

        if ($check_hold_data) {

        $data=[
                'status' => 'disable',
                'disable_by' => $user_id,       
                'updated_at' => date('Y-m-d H:i:s'),
                ];
           DB::table('report_custom_pages')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->update($data);

        }else{
            $data=[
                'parent_id' => $parent_id,
                'business_id' => $business_id,
                'coc_id' => $customer_id,
                'status' => 'disable',
                'disable_by' => $user_id,
                'created_at' => date('Y-m-d H:i:s'),
            ];
          
          DB::table('report_custom_pages')->insert($data);
        }
        
      $hold_data= DB::table('report_custom_pages')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->first();

      

      if ($hold_data) {
        return response()->json([
          'status'=>'ok',
          'message' => 'Hold',                
          ], 200);
      }else{
        return response()->json([
        'status' =>'no',
        ], 200);
      }
      
    }

     // Update show verification a pdf page for coc
    public function showReportCustomPage(Request $request)
    {
        $parent_id=Auth::user()->parent_id;
        $business_id=Auth::user()->business_id;
        $user_id=Auth::user()->id;
        $customer_id = base64_decode($request->get('customer_id'));   
        
        $check_hold_data= DB::table('report_custom_pages')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->first();
        if ($check_hold_data) {
            $data=[
                'enable_by' => $user_id,
                'status' => 'enable',
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        
            $hold_data=DB::table('report_custom_pages')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->update($data);
    
        } else {
            $data=[
                'parent_id' => $parent_id,
                'business_id' => $business_id,
                'coc_id' => $customer_id,
                'status' => 'enable',
                'enable_by' => $user_id,
                
                'created_at' => date('Y-m-d H:i:s'),
            ];
          
            $hold_data=  DB::table('report_custom_pages')->insert($data);
        }
        

        // $data=[
        //     'enable_by' => $user_id,
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ];
    
        // $hold_data=DB::table('report_add_page_statuses')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->update($data);

       
        
        if ($hold_data) {
            return response()->json([
            'status'=>'ok',
            'message' => 'removed',                
            ], 200);
        }else{
            return response()->json([
            'status' =>'no',
            ], 200);
        }
    }

    public function reportFileConfig(Request $request)
    {
        $business_id=Auth::user()->business_id;

        $items = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name','u.created_at','b.is_report_file_config')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id]);

        if(is_numeric($request->get('customer_id'))){
            $items->where('u.business_id',$request->get('customer_id'));
        }
        $items=$items->paginate(10);

        $customers = DB::table('users as u')
        ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id])
        ->get();

        if($request->ajax())
            return view('admin.accounts.reports.fileconfig-ajax',compact('items','customers'));
        else
            return view('admin.accounts.reports.fileconfig-index',compact('items','customers'));
    }

    public function reportFileConfigStatus(Request $request)
    {
        $customer_id = base64_decode($request->customer_id);

        $status = $request->status;

        DB::beginTransaction();
        try{

            DB::table('user_businesses')->where('business_id',$customer_id)->update([
                'is_report_file_config' => $status
            ]);

            DB::commit();
            return response()->json([
                'status'=>'ok',
            ]);
        }
        catch (\Exception $e) {
            DB::rollBack();
            // something went wrong
            return $e;
        } 
    }

    public function reportFileConfigEdit(Request $request)
    {
        $user_id = Auth::user()->id;
        $business_id = Auth::user()->business_id;
        $customer_id = base64_decode($request->customer_id);

        if($request->isMethod('get'))
        {
            $form='';

            $customer_d = DB::table('users as u')
                            ->select('u.name','ub.company_name')
                            ->join('user_businesses as ub','ub.business_id','=','u.id')
                            ->where(['u.user_type'=>'client','u.id'=>$customer_id])
                            ->first();
                    
            $customer = DB::table('users as u')
                            ->select('u.name','ub.company_name','ub.report_file_config_details')
                            ->join('user_businesses as ub','ub.business_id','=','u.id')
                            ->where(['u.user_type'=>'client','u.id'=>$customer_id])
                            ->first();

            if($customer!=NULL && $customer->report_file_config_details!=NULL)
            {
                // $form.='<h4>Details</h4>
                //         <p class="pb-border pb-1"></p>';
                $file_detail = $customer->report_file_config_details;

                $file_detail_arr = json_decode($file_detail,true);

                $reference_no = '';

                $emp_code = '';

                $candidate_name = '';

                $status = '';

                $date = '';

                if($file_detail_arr!=NULL && count($file_detail_arr)>0)
                {
                    if(array_key_exists("reference_no",$file_detail_arr))
                    {
                        $value = '';

                        $value = $file_detail_arr['reference_no'];

                        $reference_no='<div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="checkbox-inline serviceverify cursor-pointer">
                                                        <input type="checkbox" class="file_list" name="file_name[]" value="1" checked>
                                                        <span class="selectservices pl-3"><strong>Reference No.</strong></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <input type="number" name="order-1" value="'.$value.'" class="form-control" min="1" max="5">
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <p class="text-danger error-container error-order" id="error-order-1"></p>
                                                </div>
                                            </div>
                                        </div>';
                    }
                    else
                    {
                        $reference_no = '<div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="checkbox-inline serviceverify cursor-pointer">
                                                        <input type="checkbox" class="file_list" name="file_name[]" value="1">
                                                        <span class="selectservices pl-3"><strong>Reference No.</strong></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <input type="number" name="order-1" value="1" class="form-control" min="1" max="5" readonly>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <p class="text-danger error-container error-order" id="error-order-1"></p>
                                                </div>
                                            </div>
                                        </div>';
                    }

                    if(array_key_exists("emp_code",$file_detail_arr))
                    {
                        $value = '';

                        $value = $file_detail_arr['emp_code'];

                        $emp_code=' <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="checkbox-inline serviceverify cursor-pointer">
                                                    <input type="checkbox" class="file_list" name="file_name[]" value="2" checked>
                                                    <span class="selectservices pl-3"><strong>Emp Code.</strong></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <input type="number" name="order-2" value="'.$value.'" class="form-control" min="1" max="5">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <p class="text-danger error-container error-order" id="error-order-2"></p>
                                            </div>
                                        </div>
                                    </div>';
                    }
                    else
                    {
                        $emp_code=' <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="checkbox-inline serviceverify cursor-pointer">
                                                    <input type="checkbox" class="file_list" name="file_name[]" value="2">
                                                    <span class="selectservices pl-3"><strong>Emp Code.</strong></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <input type="number" name="order-2" value="2" class="form-control" min="1" max="5" readonly>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <p class="text-danger error-container error-order" id="error-order-2"></p>
                                            </div>
                                        </div>
                                    </div>';
                    }

                    if(array_key_exists('candidate_name',$file_detail_arr))
                    {
                        $value = '';

                        $value = $file_detail_arr['candidate_name'];

                        $candidate_name = '<div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="checkbox-inline serviceverify cursor-pointer">
                                                            <input type="checkbox" class="file_list" name="file_name[]" value="3" checked>
                                                            <span class="selectservices pl-3"><strong>Candidate Name</strong></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="form-group">
                                                        <input type="number" name="order-3" value="'.$value.'" class="form-control" min="1" max="5">
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="form-group">
                                                        <p class="text-danger error-container error-order" id="error-order-3"></p>
                                                    </div>
                                                </div>
                                            </div>';
                    }
                    else
                    {
                        $candidate_name = '<div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="checkbox-inline serviceverify cursor-pointer">
                                                            <input type="checkbox" class="file_list" name="file_name[]" value="3">
                                                            <span class="selectservices pl-3"><strong>Candidate Name</strong></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="form-group">
                                                        <input type="number" name="order-3" value="3" class="form-control" min="1" max="5" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="form-group">
                                                        <p class="text-danger error-container error-order" id="error-order-3"></p>
                                                    </div>
                                                </div>
                                            </div>';
                    }

                    if(array_key_exists('status',$file_detail_arr))
                    {
                        $value = '';

                        $value = $file_detail_arr['status'];

                        $status = '<div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="checkbox-inline serviceverify cursor-pointer">
                                                    <input type="checkbox" class="file_list" name="file_name[]" value="4" checked>
                                                    <span class="selectservices pl-3"><strong>Status</strong></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <input type="number" name="order-4" value="'.$value.'" class="form-control" min="1" max="5">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <p class="text-danger error-container error-order" id="error-order-4"></p>
                                            </div>
                                        </div>
                                    </div>';
                    }
                    else
                    {
                        $status = '<div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="checkbox-inline serviceverify cursor-pointer">
                                                    <input type="checkbox" class="file_list" name="file_name[]" value="4">
                                                    <span class="selectservices pl-3"><strong>Status</strong></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <input type="number" name="order-4" value="4" class="form-control" min="1" max="5" readonly>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <p class="text-danger error-container error-order" id="error-order-4"></p>
                                            </div>
                                        </div>
                                    </div>';
                    }

                    if(array_key_exists('date',$file_detail_arr))
                    {
                        $value = '';
                        $value = $file_detail_arr['date'];

                        $date = ' <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="checkbox-inline serviceverify cursor-pointer">
                                                    <input type="checkbox" class="file_list" name="file_name[]" value="5" checked>
                                                    <span class="selectservices pl-3"><strong>Date</strong></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <input type="number" name="order-5" value="'.$value.'" class="form-control" min="1" max="5">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <p class="text-danger error-container error-order" id="error-order-5"></p>
                                            </div>
                                        </div>
                                    </div>';
                    }
                    else
                    {
                        $date = ' <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="checkbox-inline serviceverify cursor-pointer">
                                                <input type="checkbox" class="file_list" name="file_name[]" value="5">
                                                <span class="selectservices pl-3"><strong>Date</strong></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <input type="number" name="order-5" value="5" class="form-control" min="1" max="5" readonly>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <p class="text-danger error-container error-order" id="error-order-5"></p>
                                        </div>
                                    </div>
                                </div>';
                        
                    }
                }
                else
                {
                    $reference_no = '<div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="checkbox-inline serviceverify cursor-pointer">
                                                        <input type="checkbox" class="file_list" name="file_name[]" value="1">
                                                        <span class="selectservices pl-3"><strong>Reference No.</strong></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <input type="number" name="order-1" value="1" class="form-control" min="1" max="5" readonly>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <p class="text-danger error-container error-order" id="error-order-1"></p>
                                                </div>
                                            </div>
                                        </div>';

                    
                    $emp_code=' <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="checkbox-inline serviceverify cursor-pointer">
                                                    <input type="checkbox" class="file_list" name="file_name[]" value="2">
                                                    <span class="selectservices pl-3"><strong>Emp Code.</strong></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <input type="number" name="order-2" value="2" class="form-control" min="1" max="5" readonly>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <p class="text-danger error-container error-order" id="error-order-2"></p>
                                            </div>
                                        </div>
                                    </div>';

                    $candidate_name = '<div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="checkbox-inline serviceverify cursor-pointer">
                                                <input type="checkbox" class="file_list" name="file_name[]" value="3">
                                                <span class="selectservices pl-3"><strong>Candidate Name</strong></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <input type="number" name="order-3" value="3" class="form-control" min="1" max="5" readonly>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <p class="text-danger error-container error-order" id="error-order-3"></p>
                                        </div>
                                    </div>
                                </div>';

                    $status = '<div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="checkbox-inline serviceverify cursor-pointer">
                                            <input type="checkbox" class="file_list" name="file_name[]" value="4">
                                            <span class="selectservices pl-3"><strong>Status</strong></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <input type="number" name="order-4" value="4" class="form-control" min="1" max="5" readonly>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <p class="text-danger error-container error-order" id="error-order-4"></p>
                                    </div>
                                </div>
                            </div>';

                    $date = ' <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="checkbox-inline serviceverify cursor-pointer">
                                        <input type="checkbox" class="file_list" name="file_name[]" value="5">
                                        <span class="selectservices pl-3"><strong>Date</strong></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <input type="number" name="order-5" value="5" class="form-control" min="1" max="5" readonly>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <p class="text-danger error-container error-order" id="error-order-5"></p>
                                </div>
                            </div>
                        </div>';

                    
                }
                

                $form.='<div class="row py-2">
                            <div class="col-md-6">
                                <h5 class="pl-4"><strong>File Name</strong></h5>
                            </div>
                            <div class="col-md-6">
                                <h5 class=""><strong>Order</strong></h5>
                            </div>
                        </div>
                        '.$reference_no.'
                        '.$emp_code.'
                        '.$candidate_name.'
                        '.$status.'
                        '.$date.'
                        <p style="margin-bottom:2px;" class="text-danger error-container error-file_name" id="error-file_name"></p>';
            }
            else
            {
                // $form.='<h4>Details</h4>
                //         <p class="pb-border pb-1"></p>';
                $form.='<div class="row py-2">
                            <div class="col-md-6">
                                <h5 class="pl-4"><strong>File Name</strong></h5>
                            </div>
                            <div class="col-md-6">
                                <h5 class=""><strong>Order</strong></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="checkbox-inline serviceverify cursor-pointer">
                                        <input type="checkbox" class="file_list" name="file_name[]" value="1">
                                        <span class="selectservices pl-3"><strong>Reference No.</strong></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <input type="number" name="order-1" value="1" class="form-control" min="1" max="5" readonly>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <p class="text-danger error-container error-order" id="error-order-1"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="checkbox-inline serviceverify cursor-pointer">
                                        <input type="checkbox" class="file_list" name="file_name[]" value="2">
                                        <span class="selectservices pl-3"><strong>Emp Code.</strong></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <input type="number" name="order-2" value="2" class="form-control" min="1" max="5" readonly>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <p class="text-danger error-container error-order" id="error-order-2"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="checkbox-inline serviceverify cursor-pointer">
                                        <input type="checkbox" class="file_list" name="file_name[]" value="3">
                                        <span class="selectservices pl-3"><strong>Candidate Name</strong></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <input type="number" name="order-3" value="3" class="form-control" min="1" max="5" readonly>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <p class="text-danger error-container error-order" id="error-order-3"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="checkbox-inline serviceverify cursor-pointer">
                                        <input type="checkbox" class="file_list" name="file_name[]" value="4">
                                        <span class="selectservices pl-3"><strong>Status</strong></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <input type="number" name="order-4" value="4" class="form-control" min="1" max="5" readonly>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <p class="text-danger error-container error-order" id="error-order-4"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="checkbox-inline serviceverify cursor-pointer">
                                        <input type="checkbox" class="file_list" name="file_name[]" value="5">
                                        <span class="selectservices pl-3"><strong>Date</strong></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <input type="number" name="order-5" value="5" class="form-control" min="1" max="5" readonly>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <p class="text-danger error-container error-order" id="error-order-5"></p>
                                </div>
                            </div>
                        </div>
                        <p style="margin-bottom:2px;" class="text-danger error-container error-file_name" id="error-file_name"></p>';
            }
            
            return response()->json([
                'form' => $form,
                'result' => $customer_d
            ]);
        }

        $file_name_arr = ['reference_no','emp_code','candidate_name','status','date'];

        // dd($file_name_arr);

        $order_arr = [];

        $detail_arr = [];

        $rules= [
            'file_name' => 'required|array|min:1',
            // 'order.*' => 'required|integer|min:1|max:5'
         ];

         $custom = [
            'file_name.required' => 'Select Atleast One File Name !!',
            // 'order.*.required' => 'Order No. is Required',
            // 'order.*.integer' => 'Order No. must be numeric',
            // 'order.*.min' => 'Order No. must be Atleast 1',
            // 'order.*.max' => 'Order No. must be Maximum 5',
         ];

         $validator = Validator::make($request->all(), $rules,$custom);
          
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type' => 'validation',
                 'errors' => $validator->errors()
             ]);
         }

         DB::beginTransaction();
         try{

            if(count($request->file_name) > 0)
            {
                foreach($request->file_name as $item)
                {
                    $rules= [
                        'order-'.$item => 'required|integer|min:1|max:5',
                        // 'order.*' => 'required|integer|min:1|max:5'
                    ];
            
                    $custom = [
                        'order-'.$item.'.required' => 'Order No. is Required',
                        'order-'.$item.'.integer' => 'Order No. must be numeric',
                        'order-'.$item.'.min' => 'Order No. must be Atleast 1',
                        'order-'.$item.'.max' => 'Order No. must be Maximum 5',
                    ];
            
                    $validator = Validator::make($request->all(), $rules,$custom);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'fail' => true,
                            'error_type' => 'validation',
                            'errors' => $validator->errors()
                        ]);
                    }

                    array_push($order_arr,$request->input('order-'.$item));
                }
            }

            
            // Validation for duplicacy of order No.
            if(count($order_arr)>0)
            {
                $error = [];

                $error = Helper::get_duplicates_array($order_arr);

                if(count($error)>0)
                {
                    return response()->json([
                        'fail' => true,
                        'error' => 'yes',
                        'message' => 'Select File Order No. Must be Unique !!'

                    ]);
                }
            }
            else
            {
                return response()->json([
                    'fail' => true,
                    'error' => 'yes',
                    'message' => 'Order No. is Required !!'

                ]);
            }

            if(count($request->file_name)>0)
            {
                foreach($request->file_name as $item)
                {
                    $str='';
                    $str = $file_name_arr[(int)$item - 1];
                    $detail_arr[$str]=$request->input('order-'.$item);
                }
            }
            
            $user_businesses = DB::table('user_businesses')->where(['business_id'=>$customer_id])->first();

            if($user_businesses->report_file_config_created_by==NULL)
            {
                DB::table('user_businesses')->where(['business_id'=>$customer_id])->update([
                    'report_file_config_details' => count($detail_arr) > 0 ? json_encode($detail_arr) : NULL,
                    'report_file_config_created_by' => $user_id,
                    'report_file_config_created_at'=>date('Y-m-d H:i:s')
                ]);
            }
            else
            {
                DB::table('user_businesses')->where(['business_id'=>$customer_id])->update([
                    'report_file_config_details' => count($detail_arr) > 0 ? json_encode($detail_arr) : NULL,
                    'report_file_config_updated_by' => $user_id,
                    'report_file_config_updated_at'=>date('Y-m-d H:i:s')
                ]);
            }
            

            DB::commit();
            return response()->json([
                'fail' => false
            ]);

         }
         catch (\Exception $e) {
            DB::rollBack();
            // something went wrong
            return $e;
         } 
    }
    
    public function holidays(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $holiday_master=DB::table('customer_holiday_masters')
                    ->where('business_id',$business_id)
                    ->whereYear('date',date('Y'));
                    if($request->get('from_date') !=""){
                        $holiday_master->whereDate('date','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                    }
                    if($request->get('to_date') !=""){
                        $holiday_master->whereDate('date','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                    }
                    if($request->get('type') !=""){
                        $holiday_master->where('type',$request->get('type'));
                    }
                    if(is_numeric($request->get('holiday_id'))){
                        $holiday_master->where('id',$request->get('holiday_id'));
                    }
        $items = $holiday_master->orderBy('date')->paginate(10);

        $holidays=DB::table('customer_holiday_masters')->orderBy('date')->where('business_id',$business_id)->get();
        
        if($request->ajax())
            return view('admin.accounts.holiday.ajax',compact('items','holidays'));
        else
            return view('admin.accounts.holiday.index',compact('items','holidays'));
    }

    public function holidayStore(Request $request)
    {
        $business_id =Auth::user()->business_id;
        $user_id =  Auth::user()->id;
        $rules= [
            'name'    => 'required|regex:/^[a-zA-Z][A-Za-z\/()\' ]+$/u|min:2|max:255',
            'date'    =>  'required|date',
         ];

         $custom=[
             'name.regex' => 'Name should be String',
         ];
        
         $validator = Validator::make($request->all(), $rules, $custom);
          
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type' => 'validation',
                 'errors' => $validator->errors()
             ]);
         }

         // check if Holiday name already exist
         $holidaycount=DB::table('customer_holiday_masters')->where(DB::raw('BINARY `name`'),$request->name)->count();

         if($holidaycount > 0)
         {
            return response()->json([
                'fail' => false,
                'error_type' => 'validation',
                'errors' => ['name'=> 'Holiday Name is Already Exist !!']
            ]);
         }

         if(date('Y')!=date('Y',strtotime($request->date)))
         {
            return response()->json([
                'fail' => false,
                'error_type' => 'validation',
                'errors' => ['date'=> 'Date Must be in Current year !!']
            ]);
         }

        //  dd($end_datetime);

        $data=[
            'business_id' => $business_id,
            'name' => $request->name,
            'date' => date('Y-m-d',strtotime($request->date)),
            'type' => 'custom',
            'created_by' => $user_id,
            'created_at' => date('Y-m-d H:i:s')
        ];

        DB::table('customer_holiday_masters')->insert($data);

        return response()->json([
            'fail' => false,
        ]);


    }

    public function holidayEdit(Request $request)
    {
        $id=base64_decode($request->id);

        if ($request->isMethod('get'))
        {
            $data = DB::table('customer_holiday_masters')
            ->where(['id' =>$id])        
            ->first(); 
        
            return response()->json([                
                'result' => $data
            ]);
        }

        $business_id =Auth::user()->business_id;
        $user_id =  Auth::user()->id;
        $rules= [
            'name'    => 'required|regex:/^[a-zA-Z][A-Za-z\/()\' ]+$/u|min:2|max:255',
            'date'    =>  'required|date',
         ];

         $custom=[
             'name.regex' => 'Name should be String',
         ];
        
         $validator = Validator::make($request->all(), $rules, $custom);
          
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type' => 'validation',
                 'errors' => $validator->errors()
             ]);
         }

         // check if Holiday name already exist
         $holidaycount=DB::table('customer_holiday_masters')
                        ->where(DB::raw('BINARY `name`'),$request->name)
                        ->whereNotIn('id',[$id])
                        ->count();

         if($holidaycount > 0)
         {
            return response()->json([
                'fail' => false,
                'error_type' => 'validation',
                'errors' => ['name'=> 'Holiday Name is Already Exist !!']
            ]);
         }

         if(date('Y')!=date('Y',strtotime($request->date)))
         {
            return response()->json([
                'fail' => false,
                'error_type' => 'validation',
                'errors' => ['date'=> 'Date Must be in Current year !!']
            ]);
         }

         DB::table('customer_holiday_masters')->where(['id'=>$id])->update([
             'name' => $request->name,
             'date' => date('Y-m-d',strtotime($request->date)),
             'updated_by' => $user_id,
             'updated_at' => date('Y-m-d H:i:s')
         ]);

         return response()->json([
            'fail' => false,
         ]);

    }

    public function holidayDelete(Request $request)
    {
        $id=base64_decode($request->id);
        // dd($id);
        DB::table('customer_holiday_masters')->where(['id'=>$id])->delete();

        //     return redirect()
        //         ->route('users.index')
        //         ->with('success', 'User deleted successfully');
        // }
        return response()->json([
            'status' => 'ok',
        ]);
    }

    public function holidayStatus(Request $request)
    {
        $id=base64_decode($request->id);
        $type = base64_decode($request->type);
        // dd($id);

        if($type=='active')
        {
            DB::table('customer_holiday_masters')->where(['id'=>$id])->update([
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);
        }
        else
        {
            DB::table('customer_holiday_masters')->where(['id'=>$id])->update([
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);
        }

        //     return redirect()
        //         ->route('users.index')
        //         ->with('success', 'User deleted successfully');
        // }
        return response()->json([
            'status' => 'ok',
            'type' => $type
        ]);
    }

    public function insuffControl(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $insuff_control=DB::table('coc_insuff_controls')
                    ->where('parent_id',$business_id);
                    if($request->get('from_date') !=""){
                        $insuff_control->whereDate('created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                    }
                    if($request->get('to_date') !=""){
                        $insuff_control->whereDate('created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                    }
                    if(is_numeric($request->get('customer_id'))){
                        $insuff_control->where('business_id',$request->get('customer_id'));
                    }
        $items = $insuff_control->orderBy('id','desc')->paginate(10);

        $customers=DB::table('users as u')
                    ->select('u.*','b.company_name')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                    ->whereNotIn('u.id',[$business_id])
                    ->get();
        
        if($request->ajax())
            return view('admin.accounts.insufficiency.ajax',compact('items','customers'));
        else
            return view('admin.accounts.insufficiency.index',compact('items','customers'));
    }

    public function insuffControlStore(Request $request)
    {
        $business_id =Auth::user()->business_id;
        $user_id =  Auth::user()->id;

        $rules= [
            'customer'    => 'required|unique:coc_insuff_controls,business_id',
            'no_of_days'  =>  'required|integer|min:1|max:30',
         ];

         $custom=[
             'customer.unique' => 'Customer Name is Already Exist !!'
         ];

         $validator = Validator::make($request->all(), $rules,$custom);
          
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type' => 'validation',
                 'errors' => $validator->errors()
             ]);
         }

         $data=[
            'parent_id' => $business_id,
            'business_id' => $request->customer,
            'days' => $request->no_of_days,
            'created_by' => $user_id,
            'created_at' => date('Y-m-d H:i:s')
        ];

        DB::table('coc_insuff_controls')->insert($data);

        return response()->json([
            'fail' => false,
        ]); 


    }

    public function insuffControlEdit(Request $request)
    {
        $id=base64_decode($request->id);
        $user_id = Auth::user()->id;
        if ($request->isMethod('get'))
        {
            $data = DB::table('coc_insuff_controls as c')
                        ->select('c.*','ub.company_name','u.first_name')
                        ->join('users as u','u.id','=','c.business_id')
                        ->join('user_businesses as ub','u.id','=','ub.business_id')
                        ->where(['c.id' =>$id])        
                        ->first(); 
        
            return response()->json([                
                'result' => $data
            ]);
        }

        $rules= [
            'no_of_days'  =>  'required|integer|min:1|max:30',
         ];

         $validator = Validator::make($request->all(), $rules);
          
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type' => 'validation',
                 'errors' => $validator->errors()
             ]);
         }

         DB::table('coc_insuff_controls')->where(['id'=>$id])->update(
             [
                 'days' => $request->no_of_days,
                 'updated_by' => $user_id,
                 'updated_at' => date('Y-m-d H:i:s')
             ]
        );

         return response()->json([
            'fail' => false,
         ]);

    }

    public function insuffControlStatus(Request $request)
    {
        $id=base64_decode($request->id);
        $type = base64_decode($request->type);
        // dd($id);

        if($type=='active')
        {
            DB::table('coc_insuff_controls')->where(['id'=>$id])->update([
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);
        }
        else
        {
            DB::table('coc_insuff_controls')->where(['id'=>$id])->update([
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);
        }

       
        return response()->json([
            'status' => 'ok',
            'type' => $type
        ]);
    }

    public function insuffControlReport(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $items = DB::table('users as u')
                    ->select('u.id','u.business_id','u.name','u.email','u.phone','b.company_name','u.created_at')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                    ->whereNotIn('u.id',[$business_id]);

                    if(is_numeric($request->get('customer_id'))){
                        $items->where('u.business_id',$request->get('customer_id'));
                    }

                    $items=$items->paginate(10);

        $customers = DB::table('users as u')
                        ->select('u.id','u.business_id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                        ->join('user_businesses as b','b.business_id','=','u.id')
                        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                        ->whereNotIn('u.id',[$business_id])
                        ->get();

        if($request->ajax())
            return view('admin.accounts.insufficiency.report.ajax',compact('items','customers'));
        else
            return view('admin.accounts.insufficiency.report.index',compact('items','customers'));
    }

    public function insuffControlReportEdit(Request $request)
    {
        $user_id  = base64_decode($request->id);

        if($request->isMethod('get'))
        {
            $form = '';
            $users = DB::table('users as u')
                        ->select('u.id','u.name','ub.company_name','u.user_type')
                        ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                        ->where(['u.id'=>$user_id,'is_deleted'=>0])
                        ->first();

            $notification_config = DB::table('notification_control_configs')->where(['business_id'=>$users->id,'type'=>'insuff-report-generate'])->get();

            if(count($notification_config)>0)
            {
                $status = '';

                foreach($notification_config as $key => $item)
                {
                    
                    $status = '<div class="form-group">
                                    <label> Status <span class="text-danger">*</span></label>
                                    <select class="form-control sts_r" name="status-'.$key.'">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <p style="margin-bottom: 2px;" class="text-danger error_container error-status-'.$key.'" id="error-status-'.$key.'"></p>
                                </div>';
                    
                    if($item->status==0)
                    {
                        $status = '<div class="form-group">
                                        <label> Status <span class="text-danger">*</span></label>
                                        <select class="form-control sts_r" name="status-'.$key.'">
                                            <option value="1">Active</option>
                                            <option value="0" selected>Inactive</option>
                                        </select>
                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-status_'.$key.'" id="error-status-'.$key.'"></p>
                                    </div>';
                    }

                    $form.='<div class="cust_data" row-id="1">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Name <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" name="name[]" value="'.$item->name.'">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-name" id="error-name"></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Email <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" name="email[]" value="'.$item->email.'">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-email" id="error-email"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                '.$status.'
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mt-3">
                                            <span class="btn btn-link text-danger delete_div" data-id="'.base64_encode($item->id).'" style="font-size:20px;"><i class="far fa-times-circle"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <p class="pb-border"></p>
                            </div>
                            ';
                }
            }

            return response()->json([
                'result' => $users,
                'form' => $form,
                'count' => count($notification_config)
            ]);
        }

        $rules = [
            'name.*' => 'required|regex:/^[a-zA-Z ]+$/u|min:1',
            'email.*' => 'required|email:rfc,dns'
        ];

        $custom = [
            'name.*.required' => 'Name Field is required',
            'name.*.regex' => 'Name Field Must Be String',
            'email.*.required' => 'Email Field is required',
            'email.*.email' => 'Email Field Must Be Valid Email Address'
        ];

        $validator = Validator::make($request->all(), $rules,$custom);
          
         if ($validator->fails()){
             return response()->json([
                 'success' => false,
                 'errors' => $validator->errors()
             ]);
         }

         DB::beginTransaction();
         try{

            if(count($request->email)>0)
            {
                foreach($request->email as $key => $value)
                {
                    $rules = [
                        'status-'.$key => 'required',
                    ];
            
                    $custom = [
                        'status-'.$key.'.required' => 'Status Field is Required',
                    ];
            
                    $validator = Validator::make($request->all(), $rules,$custom);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ]);
                    }


                }
            }

            // dd($request->all());

            DB::table('notification_control_configs')->where(['business_id'=>$user_id,'type'=>'insuff-report-generate'])->delete();

            if(count($request->email)>0)
            {
                $user = DB::table('users')->where(['id'=>$user_id])->first();

                foreach($request->email as $key => $value)
                {
                    $status = 0;
                   
                    if($request->input('status-'.$key)==1)
                    {
                        $status = 1;
                    }

                    DB::table('notification_control_configs')->insert([
                        'parent_id' => $user->parent_id,
                        'business_id' => $user->business_id,
                        'name' => $request->name[$key],
                        'email' => $value,
                        'status' => $status,
                        'type' => 'insuff-report-generate',
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                }
            }

            DB::commit();
            return response()->json([
                'success' => true
            ]);
         }
         catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
         

         

    }

    public function insuffControlReportDelete(Request $request)
    {
        $id = base64_decode($request->id);

        DB::beginTransaction();
        try{
            if(Auth::check()){

                DB::table('notification_control_configs')->where(['id'=>$id])->delete();
                //return result 
                    
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
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    public function insuffControlReportStatus(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $id=base64_decode($request->id);
        $type = base64_decode($request->type);
        // dd($id);

        if($type=='active')
        {
            $insuff = DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'insuff-report-generate'])->latest()->first();
            
            if($insuff!=NULL)
            {
                DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'insuff-report-generate'])->update([
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]);
            }
            else
            {
                DB::table('notification_controls')->insert([
                    'parent_id' => $business_id,
                    'business_id' => $id,
                    'status' => 1,
                    'type' => 'insuff-report-generate',
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        else
        {
            DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'insuff-report-generate'])->update([
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);
        }

       
        return response()->json([
            'status' => 'ok',
            'type' => $type
        ]);
    }

    public function checkControl(Request $request)
    {
        $business_id=Auth::user()->business_id;

        $items = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name','u.created_at')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id]);

        // if(is_numeric($request->get('customer_id'))){
        //     $items->where('u.business_id',$request->get('customer_id'));
        // }
        $items=$items->paginate(10);

        $customers = DB::table('users as u')
        ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id])
        ->get();

        if($request->ajax())
            return view('admin.accounts.checkcontrol.ajax',compact('items','customers'));
        else
            return view('admin.accounts.checkcontrol.index',compact('items','customers'));
    }


    public function serviceInputControl(Request $request)
    {
        $id = base64_decode($request->id);
        $services = DB::table('services')
                    ->select('name','id','type_name')
                    ->where(['status'=>'1'])
                    ->where('business_id',NULL)
                    ->whereNotIn('type_name',['e_court'])
                    ->orwhere('business_id',$id)
                    ->orderBy('sort_number','asc')
                    ->get();
       
        return view('admin.accounts.checkcontrol.edit',compact('services','id'));
    }


    public function jafcontrollinkControl(Request $request)
    {
        $business_id = Auth::user()->business_id;
        
        $items=DB::table('jaf_send_video_links')->select('id','business_id','video_jaf_links','status');
                              
        $items = $items->orderBy('id','desc')->paginate(10);
        
        $customers = User::from('users as u')
                    ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['u.user_type'=>'client'])
                    //->whereNotIn('u.id',[$business_id])
                    ->get();
                   // dd($customers);
                    // dd($items);

        if($request->ajax())
            return view('admin.accounts.jafcontrollink.ajax',compact('items','customers'));
        else
            return view('admin.accounts.jafcontrollink.index',compact('items','customers'));
    }

    public function JafVideoLinksStore(Request $request)
    {
        // $business_id =Auth::user()->business_id;
        $user_id =  Auth::user()->id;

        $rules= [
            'customer'          => 'required|unique:jaf_send_video_links,business_id',
            'video_jaf_links'   => 'required|url',
         ];

         $custom=[
             'customer.unique' => 'Customer Name is Already Exist !!'
         ];

         $validator = Validator::make($request->all(), $rules,$custom);
          
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type' => 'validation',
                 'errors' => $validator->errors()
             ]);
         }

         $data=[
            'business_id' => $request->customer,
            'video_jaf_links' => $request->video_jaf_links,
            'created_by' => $user_id,
            'status' => '1',
            'created_at' => date('Y-m-d H:i:s')
        ];

        DB::table('jaf_send_video_links')->insert($data);

        return response()->json([
            'fail' => false,
        ]); 
    }

    public function jafVideoLinksEdit(Request $request)
    {
        $id=base64_decode($request->id);
        // dd($id);
        $user_id = Auth::user()->id;
        if ($request->isMethod('get'))
        {
            $data = DB::table('jaf_send_video_links as c')
                    ->select('c.*','ub.company_name','u.first_name')
                    ->join('users as u','u.id','=','c.business_id')
                    ->join('user_businesses as ub','u.id','=','ub.business_id')
                    ->where(['c.id' =>$id])        
                    ->first();
                // dd($data);
        
            return response()->json([                
                'result' => $data,
            ]);
        }

        $rules= [
            'video_jaf_links' => 'required|url',
         ];

         $validator = Validator::make($request->all(), $rules);
          
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type' => 'validation',
                 'errors' => $validator->errors()
             ]);
         }

         DB::table('jaf_send_video_links')->where('id',$id)->update(
             [
                'business_id'       => $request->business_id,
                'video_jaf_links'   => $request->video_jaf_links,
                'created_by'        => $user_id,
                'status'            => '1',
                'created_at'        => date('Y-m-d H:i:s')
             ]
        );

         return response()->json([
            'fail' => false,
         ]);
    }

    public function jafVideoLinksStatus(Request $request)
    {
        $id=base64_decode($request->id);
        $type = base64_decode($request->type);
        // dd($id);

        if($type=='active')
        {
            DB::table('jaf_send_video_links')->where(['id'=>$id])->update([
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
               // 'updated_by' => Auth::user()->id
            ]);
        }
        else
        {
            DB::table('jaf_send_video_links')->where(['id'=>$id])->update([
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
                //'updated_by' => Auth::user()->id
            ]);
        }

       
        return response()->json([
            'status' => 'ok',
            'type' => $type
        ]);
    }


    public function checkControlDisclaimer(Request $request)
    {
        $business_id=Auth::user()->business_id;

        $items = DB::table('users as u')
                    ->select('u.id','u.name','u.email','u.phone','b.company_name','u.parent_id','u.created_at')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                    ->whereNotIn('u.id',[$business_id]);
        // if(is_numeric($request->get('customer_id'))){
        //     $items->where('u.business_id',$request->get('customer_id'));
        // }
        $items=$items->paginate(10);

        $customers = DB::table('users as u')
                    ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                    ->whereNotIn('u.id',[$business_id])
                    ->get();
                    // dd($items);

        if($request->ajax())
            return view('admin.accounts.disclaimer.ajax',compact('items','customers'));
        else
            return view('admin.accounts.disclaimer.index',compact('items','customers'));
    }


    public function controlDisclaimer(Request $request)
    {
        $id = base64_decode($request->id);
        // dd($id);
        $items = DB::table('users')->where('id',$id)->first();
        $parentData = $items->parent_id;
        
        $services = DB::table('services')
                    ->select('name','id','type_name')
                    ->where(['status'=>'1'])
                    ->where('business_id',NULL)
                    ->whereNotIn('type_name',['e_court'])
                    // ->orwhere('business_id',$id)
                    ->orderBy('sort_number','asc')
                    ->get();

        $disclaimers = Disclaimer::where('business_id',$id)->get();
        // dd($disclaimers);    
        $serviceId=[];
        foreach ($services as $as) {
            $serviceId[] = $as->id;
        }

        $dsiclaimer_service=[];
        foreach ($disclaimers as $dis) {
            $dsiclaimer_service[]=$dis->service_id; 
        }
        
        $arr_service=[];
        foreach ($dsiclaimer_service as $ds) {
           if (in_array($ds,$serviceId)) {
                $arr_service[]= $ds;
           }
        }
        $newcheck_services= DB::table('services')->select('name','id')
                            ->whereNotIn('id',$arr_service)
                            ->where(['status'=>'1'])
                            ->whereNull('business_id')
                            ->whereNotIn('type_name',['gstin'])
                            ->orwhere('business_id',$id)
                            ->get();
                            //dd($newcheck_services);
        return view('admin.accounts.disclaimer.edit',compact('services','id','newcheck_services','parentData','disclaimers'));
    }

    public function editDisclaimerData($id)
    {
        $editdata = DB::table('disclaimers')->where('id',$id)->first();
        return response()->json(['editdata'=>$editdata]);
    }

    public function discliamerUpdated(Request $request)
    {
        $rules= [
            'service_id'    => 'required',
            'disclaimer' => 'required',

         ];
        
         $customMessages = [
          'service_id.required_if'       => 'Check Name field is required',
        ];

         $validator = Validator::make($request->all(), $rules,$customMessages);
          
         if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
         }

         $customer_id = $request->business_id;
         $parent_id = $request->parent_id;
        
        DB::beginTransaction();
        try
        {
            $data = ([
                'business_id' => $customer_id,
                'disclaimer' => $request->disclaimer,
                'service_id' => $request->service_id,
                'parent_id' => $parent_id,
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            DB::table('disclaimers')->where('id',$request->disc_id)->update($data);

            DB::commit();
            return response()->json([
                'success' =>true,
                'custom'  =>'yes',
                'errors'  =>[]
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
    }

    

     // Create Function for hide a verification for coc
     public function hideServiceInputCOCWise(Request $request)
     {
       $parent_id=Auth::user()->parent_id;
       $business_id=Auth::user()->business_id;
       $user_id=Auth::user()->id;
       $customer_id = base64_decode($request->get('customer_id'));  
 
       if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
         {
             $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
             $parent_id=$users->parent_id;
         }
         DB::beginTransaction();
         try{
             // echo('abc');
             // dd($candidate_id);
             $hold_data= DB::table('check_coc_controls')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->first();
             if($hold_data!=NULL)
             {
                 $data=[
                     'hide_by' => $user_id,
                     'hide_at' => date('Y-m-d H:i:s'),
                     'shown_by' => NULL,
                     'shown_at' => NULL,
                     'updated_at' => date('Y-m-d H:i:s'),
                 ];
                 DB::table('check_coc_controls')->where(['coc_id'=>$customer_id,'business_id'=>$business_id])->update($data);
             }
             else
             {
                 $data=[
                     'parent_id' => $parent_id,
                     'business_id' => $business_id,
                     'coc_id' => $customer_id,
                     'hide_by' => $user_id,
                     'hide_at' => date('Y-m-d H:i:s'),
                     'created_at' => date('Y-m-d H:i:s'),
                 ];
             
                 DB::table('check_coc_controls')->insert($data);
             }
                 
             DB::table('check_coc_controls')->where(['coc_id'=>$customer_id,'business_id'=>$business_id,'shown_at'=>null])->first();
 
            //  $hold_log_data=DB::table('customer_verification_showing_status_logs')->insert([
            //      'parent_id'=>$parent_id,
            //      'business_id'=> $business_id,
            //      'coc_id' => $customer_id,
            //      'user_id' => $user_id,
            //      'status' => 'hide',
            //      'created_at' => date('Y-m-d H:i:s'),
            //      'updated_at' => date('Y-m-d H:i:s')
            //      ]);
 
                 $hold_data = TRUE;
             if ($hold_data) {
                 DB::commit();
                 return response()->json([
                 'status'=>'ok',
                 'message' => 'Hold',                
                 ], 200);
             }else{
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
 
      // Update show verification for coc
     public function showServiceInputCOCWise(Request $request)
     {
         $parent_id=Auth::user()->parent_id;
         $business_id=Auth::user()->business_id;
         $user_id=Auth::user()->id;
         $customer_id = base64_decode($request->get('customer_id'));
         
         if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
         {
             $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
             $parent_id=$users->parent_id;
         }
         
         DB::beginTransaction();
         try{
                 $data=[
                     'shown_by' => $user_id,
                     'shown_at' => date('Y-m-d H:i:s'),
                     'updated_at' => date('Y-m-d H:i:s'),
                 ];
                 
                 DB::table('check_coc_controls')->where(['coc_id'=>$customer_id,'business_id'=>$business_id,'shown_at'=>null])->update($data);
 
                //  $hold_log_data=DB::table('check_coc_controls')->insert([
                //  'parent_id'=>$parent_id,
                //  'business_id'=> $business_id,
                //  'coc_id' => $customer_id,
                //  'user_id' => $user_id,
                //  'status' => 'show',
                //  'created_at' => date('Y-m-d H:i:s'),
                //  'updated_at' => date('Y-m-d H:i:s')
                //  ]);
 
                 $hold_data = TRUE;
                 
                 if ($hold_data) {
                     DB::commit();
                     return response()->json([
                     'status'=>'ok',
                     'message' => 'removed',                
                     ], 200);
                 }else{
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

    public function saveServiceInput(Request $request)
    {
        $this->validate($request, [
            'check'      => 'required',
         ]);
        $customer_id = base64_decode($request->id);
        // dd($customer_id);
        $checks=$request->check;
        if (count($checks)>0) {
            $check_control=DB::table('check_control_masters')->where(['check_control_coc_id'=>$customer_id])->delete();
           foreach ($checks as $check) {
            $service=DB::table('service_form_inputs')->select('service_id')->where('id',$check)->first();
           
                DB::table('check_control_masters')->insert([
                    'check_control_coc_id' => $customer_id,
                    'service_id' => $service->service_id,
                    'service_input_id' => $check,
                    'is_required' => '1',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
          
           }
           return redirect('check/control')
           ->with('success', 'Required permissions updated successfully');
        }
        
    }

    public function saveServiceDisclaimer(Request $request)
    {
    
        $rules= [
            'service_id'    => 'required',
            'disclaimer' => 'required',
         ];
        
         $customMessages = [
          'service_id.required_if'       => 'Check Name field is required',
        ];

         $validator = Validator::make($request->all(), $rules,$customMessages);
          
         if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
         }

        $customer_id = base64_decode($request->id);
        $service_id = $request->service_id;
        $disclaimer = $request->disclaimer;
        $parent_id  = $request->parent_id;

        DB::beginTransaction();
        try
        {
            Disclaimer::create([
                'business_id' => $customer_id,
                'service_id' => $service_id,
                'disclaimer' => $disclaimer,
                'parent_id' => $parent_id,
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return response()->json([
                'success' =>true,
                'custom'  =>'yes',
                'errors'  =>[]
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
        
    }


    public function serviceDisclaimerStatus(Request $request)
    {
        $id=base64_decode($request->id);
        $type = base64_decode($request->type);
        
        if($type=='active')
        {
            DB::table('disclaimers')->where(['id'=>$id])->update([
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                // 'updated_by' => Auth::user()->id
            ]);
        }
        else
        {
            DB::table('disclaimers')->where(['id'=>$id])->update([
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
                // 'updated_by' => Auth::user()->id
            ]);
        }

       
        return response()->json([
            'status' => 'ok',
            'type' => $type
        ]);
    }


    public function sendLinkSms(Request $request)
    {
        $business_id = Auth::user()->business_id;
        
        $items=DB::table('send_link_settings')->select('id','business_id','days','days_follow_up','status');
                              
        $items = $items->orderBy('id','desc')->paginate(10);
        
        $customers = User::from('users as u')
                    ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['u.user_type'=>'client'])
                    //->whereNotIn('u.id',[$business_id])
                    ->get();
                   // dd($customers);
                    // dd($items);

        if($request->ajax())
            return view('admin.accounts.sendlinksms.ajax',compact('items','customers'));
        else
            return view('admin.accounts.sendlinksms.index',compact('items','customers'));
    }

    public function settingsSendLinksStore(Request $request)
    {
        // $business_id =Auth::user()->business_id;
        $user_id =  Auth::user()->id;

        $rules= [
            'customer'    => 'required|unique:coc_insuff_controls,business_id',
            'days_follow_up' => 'required|integer|min:1|max:30',
            'no_of_days'  =>  'required|integer|min:1|max:30',
         ];

         $custom=[
             'customer.unique' => 'Customer Name is Already Exist !!'
         ];

         $validator = Validator::make($request->all(), $rules,$custom);
          
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type' => 'validation',
                 'errors' => $validator->errors()
             ]);
         }

         $data=[
            'business_id' => $request->customer,
            'days' => $request->no_of_days,
            'days_follow_up'=> $request->days_follow_up,
            'start_date'=> date('Y-m-d'),
            'created_by' => $user_id,
            'status' => '1',
            'created_at' => date('Y-m-d H:i:s')
        ];

        DB::table('send_link_settings')->insert($data);

        return response()->json([
            'fail' => false,
        ]); 


    }

    public function sendLinkSmsEdit(Request $request)
    {
        $id=base64_decode($request->id);
        // dd($id);
        $user_id = Auth::user()->id;
        if ($request->isMethod('get'))
        {
            $data = DB::table('send_link_settings as c')
                    ->select('c.*','ub.company_name','u.first_name')
                    ->join('users as u','u.id','=','c.business_id')
                    ->join('user_businesses as ub','u.id','=','ub.business_id')
                    ->where(['c.id' =>$id])        
                    ->first();
                // dd($data);
        
            return response()->json([                
                'result' => $data,
            ]);
        }

        $rules= [
            'days_follow_up' => 'required|integer|min:1|max:30',
            'no_of_days'  =>  'required|integer|min:1|max:30',
         ];

         $validator = Validator::make($request->all(), $rules);
          
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type' => 'validation',
                 'errors' => $validator->errors()
             ]);
         }

         DB::table('send_link_settings')->where('id',$id)->update(
             [
                 'business_id' => $request->business_id,
                 'days' => $request->no_of_days,
                 'days_follow_up'=> $request->days_follow_up,
                 'start_date'=> date('Y-m-d'),
                 'created_by' => $user_id,
                 'status' => '1',
                 'created_at' => date('Y-m-d H:i:s')
             ]
        );

         return response()->json([
            'fail' => false,
         ]);
    }

    public function sendLinksStatus(Request $request)
    {
        $id=base64_decode($request->id);
        $type = base64_decode($request->type);
        // dd($id);

        if($type=='active')
        {
            DB::table('send_link_settings')->where(['id'=>$id])->update([
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
               // 'updated_by' => Auth::user()->id
            ]);
        }
        else
        {
            DB::table('send_link_settings')->where(['id'=>$id])->update([
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
                //'updated_by' => Auth::user()->id
            ]);
        }

       
        return response()->json([
            'status' => 'ok',
            'type' => $type
        ]);
    }
}


 
