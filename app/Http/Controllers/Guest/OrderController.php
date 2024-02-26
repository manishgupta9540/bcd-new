<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\User;
use Carbon\Carbon;
use Razorpay\Api\Api;
use Illuminate\Support\Str;
use PDF;
use ZipArchive;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
// use App\Helpers\Helper;
class OrderController extends Controller
{
    
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        ini_set('max_execution_time', '0');
    }

    public function index(Request $request)
    {
        $business_id=Auth::user()->business_id;

        $query=DB::table('guest_verifications as g')
                ->select('g.*',DB::raw('group_concat(gs.service_id) as services'))
                ->join('guest_verification_services as gs','g.id','=','gs.gv_id')
                ->where(['g.business_id'=> $business_id])
                ->whereIn('g.status',['success','failed'])
                ->orderBy('g.id','desc')
                ->groupBy('gs.gv_id');
                // ->get();
                // dd($query);

                if(is_numeric($request->get('candidate_id'))){
                    $query->where('g.candidate_id',$request->get('candidate_id'));
                }
                if(is_numeric($request->get('service_id'))){
                    $query->where('gs.service_id',$request->get('service_id'));
                }
                if($request->get('from_date') !=""){
                    $query->whereDate('g.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                }
                if($request->get('to_date') !=""){
                $query->whereDate('g.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                }
                if($request->get('order_id')){
                    $query->where('g.order_id','like',$request->get('order_id').'%');
                }

        $items =  $query->paginate(5);


        $candidates = DB::table('users as u')
                        // ->DISTINCT('u.id')
                        ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'is_deleted'=>'0'])->get();

        $services=DB::table('services')->where(['verification_type'=>'Auto','status'=>1])->whereNotIn('name',['GSTIN','Telecom'])->get();
        
        if ($request->ajax())
            return view('guest.orders.ajax', compact('items','candidates','services'));
        else
            return view('guest.orders.index', compact('items','candidates','services'));   
    }

    public function instantOrder(Request $request)
    {
        $business_id=Auth::user()->business_id;

        $query=DB::table('guest_instant_masters as g')
                ->select('g.*',DB::raw('group_concat(gc.service_id) as services'),DB::raw('group_concat(gc.id) as g_c_id'))
                ->join('guest_instant_carts as gc','g.id','=','gc.giv_m_id')
                ->where(['g.business_id'=> $business_id])
                ->whereIn('g.status',['success','failed'])
                ->orderBy('g.id','desc')
                ->orderBy('g.business_id','desc')
                ->groupBy('gc.giv_m_id');
                // ->get();
                // dd($query);
                if(is_numeric($request->get('service_id'))){
                    $query->where('gc.service_id',$request->get('service_id'));
                }
                if($request->get('from_date') !=""){
                    $query->whereDate('g.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                }
                if($request->get('to_date') !=""){
                $query->whereDate('g.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                }
                if($request->get('order_id')){
                    $query->where('g.order_id','like',$request->get('order_id').'%');
                }
        // dd($query->get());
        $items =  $query->paginate(5);

        $services=DB::table('services')->where(['verification_type'=>'Auto','status'=>1])->whereNotIn('name',['GSTIN','Telecom'])->get();
         
        if ($request->ajax())
            return view('guest.orders.instant_order.ajax', compact('items','services'));
        else
            return view('guest.orders.instant_order.index', compact('items','services'));   
    }


    public function instantReports(Request $request)
    {
        $business_id=Auth::user()->business_id;

        $query=DB::table('guest_instant_masters as g')
                ->select('g.*',DB::raw('group_concat(gc.service_id) as services'),DB::raw('group_concat(gc.id) as g_c_id'))
                ->join('guest_instant_carts as gc','g.id','=','gc.giv_m_id')
                ->where(['g.business_id'=> $business_id])
                ->whereIn('g.status',['success','failed'])
                ->orderBy('g.id','desc')
                ->orderBy('g.business_id','desc')
                ->groupBy('gc.giv_m_id');
                // ->get();
                // dd($query);
                if(is_numeric($request->get('service_id'))){
                    $query->where('gc.service_id',$request->get('service_id'));
                }
                if($request->get('from_date') !=""){
                    $query->whereDate('g.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                }
                if($request->get('to_date') !=""){
                $query->whereDate('g.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                }
                if($request->get('order_id')){
                    $query->where('g.order_id','like',$request->get('order_id').'%');
                }
        // dd($query->get());
        $items =  $query->paginate(5);

        $services=DB::table('services')->where(['verification_type'=>'Auto','status'=>1])->whereNotIn('name',['GSTIN','Telecom'])->get();
         
        if ($request->ajax())
            return view('guest.reports.ajax', compact('items','services'));
        else
            return view('guest.reports.index', compact('items','services'));  
    }

    public function instantReportsDetails(Request $request ,$id)
    {
        $guest_cart_id=Crypt::decryptString($id);

        //dd($guest_cart_id);

        $query=DB::table('guest_instant_cart_services as gcs')
                            ->select('gcs.*','s.name')
                            ->join('services as s','s.id','=','gcs.service_id')
                            ->where(['giv_c_id'=>$guest_cart_id]);
                            if($request->get('from_date') !=""){
                                $query->whereDate('gcs.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                            }
                            if($request->get('to_date') !=""){
                                $query->whereDate('gcs.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                            }
        $items = $query->orderBy('gcs.service_id','asc')->paginate(10);
      // dd($items);

        if ($request->ajax())
            return view('guest.reports.checkdetails.details_ajax', compact('items'));
        else
            return view('guest.reports.checkdetails.details', compact('items'));   
    }

    public function instantReportsReportsDetails(Request $request ,$id)
    {
        $guest_master_id=Crypt::decryptString($id);

        //dd($guest_master_id);

        $query=DB::table('guest_instant_cart_services as gcs')
                            ->select('gcs.*','s.name')
                            ->join('services as s','s.id','=','gcs.service_id')
                            ->where(['giv_m_id'=>$guest_master_id]);
                            if($request->get('from_date') !=""){
                                $query->whereDate('gcs.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                            }
                            if($request->get('to_date') !=""){
                                $query->whereDate('gcs.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                            }
        $items = $query->orderBy('gcs.service_id','asc')->paginate(10);
      // dd($items);

        if ($request->ajax())
            return view('guest.reportsdetails.ajax', compact('items'));
        else
            return view('guest.reportsdetails.index', compact('items')); 
    }

    public function instantverificationResendReport(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $business_id = Auth::user()->id;
            $reports_id= base64_decode($request->reports_id);
            $guest_user=DB::table('guest_instant_masters')->where(['id'=>$reports_id])->first();
            
            $name = Auth::user()->name;
            $email = Auth::user()->email;
            $date=$guest_user->created_at;
        
            $msg = "Order Reports send Please Check";
            $sender = DB::table('users')->where(['id'=>$business_id])->first();
            $data  = array('name'=>$name,'email'=>$email,'reports_id'=>$reports_id,'date'=>$date,'guest_user'=> $guest_user,'msg'=>$msg,'sender'=>$sender);
            Mail::send(['html'=>'mails.order-report-resend'], $data, function($message) use($email,$name) {
                $message->to($email, $name)->subject
                ('myBCD System - Order Report Generated');
                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
            });

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

    public function instantverificationResendDetails(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $business_id = Auth::user()->id;
            $reports_id= base64_decode($request->details_id);
           
            $guest_user=DB::table('guest_instant_cart_services')->where(['id'=>$reports_id])->first();
        //dd($guest_user);
        
            $name = Auth::user()->name;
            $email = Auth::user()->email;
        
            $date=$guest_user->created_at;
            $msg = "Order Reports send Please Check";
            $sender = DB::table('users')->where(['id'=>$business_id])->first();
            $data  = array('name'=>$name,'email'=>$email,'reports_id'=>$reports_id,'date'=>$date,'guest_user'=> $guest_user,'msg'=>$msg,'sender'=>$sender);
        
            Mail::send(['html'=>'mails.order-report-details'], $data, function($message) use($email,$name) {
                $message->to($email, $name)->subject
                ('myBCD System - Order Report Generated');
                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
            });

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

    public function instantCheckDetails(Request $request)
    {
        $business_id=Auth::user()->business_id;

        $query=DB::table('guest_instant_masters as g')
                ->select('g.*',DB::raw('group_concat(gc.service_id) as services'),DB::raw('group_concat(gc.id) as g_c_id'))
                ->join('guest_instant_carts as gc','g.id','=','gc.giv_m_id')
                ->where(['g.business_id'=> $business_id])
                ->whereIn('g.status',['success','failed'])
                ->orderBy('g.id','desc')
                ->orderBy('g.business_id','desc')
                ->groupBy('gc.giv_m_id');
                // ->get();
                // dd($query);
                if(is_numeric($request->get('service_id'))){
                    $query->where('gc.service_id',$request->get('service_id'));
                }
                if($request->get('from_date') !=""){
                    $query->whereDate('g.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                }
                if($request->get('to_date') !=""){
                $query->whereDate('g.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                }
                if($request->get('order_id')){
                    $query->where('g.order_id','like',$request->get('order_id').'%');
                }
        // dd($query->get());
        $items =  $query->paginate(5);

        $services=DB::table('services')->where(['verification_type'=>'Auto','status'=>1])->whereNotIn('name',['GSTIN','Telecom'])->get();
         
        if ($request->ajax())
            return view('guest.checkdetails.ajax', compact('items','services'));
        else
            return view('guest.checkdetails.index', compact('items','services'));
    }

    public function instantCheckDetailsData(Request $request,$id)
    {
        $guest_cart_id=Crypt::decryptString($id);

        //dd($guest_cart_id);

        $query=DB::table('guest_instant_cart_services as gcs')
                            ->select('gcs.*','s.name')
                            ->join('services as s','s.id','=','gcs.service_id')
                            ->where(['giv_c_id'=>$guest_cart_id]);
                            if($request->get('from_date') !=""){
                                $query->whereDate('gcs.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                            }
                            if($request->get('to_date') !=""){
                                $query->whereDate('gcs.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                            }
        $items = $query->orderBy('gcs.service_id','asc')->paginate(10);
      // dd($items);

        if ($request->ajax())
            return view('guest.checkdetails.details_ajax', compact('items'));
        else
            return view('guest.checkdetails.details', compact('items'));   
    }

    public function instantCheckDetailsDataCheck(Request $request,$id)
    {
        $guest_master_id=Crypt::decryptString($id);

        //dd($guest_master_id);

        $query=DB::table('guest_instant_cart_services as gcs')
                            ->select('gcs.*','s.name')
                            ->join('services as s','s.id','=','gcs.service_id')
                            ->where(['giv_m_id'=>$guest_master_id]);
                            if($request->get('from_date') !=""){
                                $query->whereDate('gcs.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                            }
                            if($request->get('to_date') !=""){
                                $query->whereDate('gcs.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                            }
        $items = $query->orderBy('gcs.service_id','asc')->paginate(10);
      // dd($items);

        if ($request->ajax())
            return view('guest.checkdetails.details.ajax', compact('items'));
        else
            return view('guest.checkdetails.details.index', compact('items'));   

    }

    public function instantOrderDetails(Request $request,$id)
    {
        $guest_cart_id=Crypt::decryptString($id);

        //dd($guest_cart_id);

        $query=DB::table('guest_instant_cart_services as gcs')
                            ->select('gcs.*','s.name')
                            ->join('services as s','s.id','=','gcs.service_id')
                            ->where(['giv_c_id'=>$guest_cart_id]);
                            if($request->get('from_date') !=""){
                                $query->whereDate('gcs.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                            }
                            if($request->get('to_date') !=""){
                                $query->whereDate('gcs.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                            }
        $items = $query->orderBy('gcs.service_id','asc')->paginate(10);
      // dd($items);

        if ($request->ajax())
            return view('guest.orders.instant_order.details_ajax', compact('items'));
        else
            return view('guest.orders.instant_order.details', compact('items'));   

    }

    public function instantOrderDetailsCheck(Request $request,$id)
    {
        $guest_master_id=Crypt::decryptString($id);

        //dd($guest_master_id);

        $query=DB::table('guest_instant_cart_services as gcs')
                            ->select('gcs.*','s.name')
                            ->join('services as s','s.id','=','gcs.service_id')
                            ->where(['giv_m_id'=>$guest_master_id]);
                            if($request->get('from_date') !=""){
                                $query->whereDate('gcs.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                            }
                            if($request->get('to_date') !=""){
                                $query->whereDate('gcs.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                            }
        $items = $query->orderBy('gcs.service_id','asc')->paginate(10);
      // dd($items);

        if ($request->ajax())
            return view('guest.orders.instant_order_check.detailscheck_ajax', compact('items'));
        else
            return view('guest.orders.instant_order_check.detailscheck', compact('items'));   

    }

    public function instantOrderDetailsData(Request $request)
    {
        $d_id=base64_decode($request->g_id);

        // dd($d_id);

        $form='';
        $modal='';
        $guest_cart_service=DB::table('guest_instant_cart_services as gcs')
                                ->select('gcs.*','s.name')
                                ->join('services as s','s.id','=','gcs.service_id')
                                ->where(['gcs.id'=>$d_id])
                                ->first();
        if($guest_cart_service->service_data!=NULL)
        {
            if(stripos($guest_cart_service->status,'success')!==false)
            {
                $modal.='<div class="modal-header">
                            <h4 class="modal-title" id="service_name"></h4>
                        </div>
                        <!-- Modal body -->
                        <input type="hidden" name="g_id" id="g_id">
                            <div class="modal-body">
                                <div id="order_details">

                                </div>
                            </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                        
                        </div>';
            }
            else
            {
                if($guest_cart_service->refund_count>=3)
                {
                    $modal.='<div class="modal-header">
                            <h4 class="modal-title" id="service_name"></h4>
                        </div>
                        <!-- Modal body -->
                        <input type="hidden" name="g_id" id="g_id">
                            <div class="modal-body">
                                <div id="order_details">

                                </div>
                            </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                        
                        </div>';
                }
                else
                {
                    $modal.='<div class="modal-header">
                            <h4 class="modal-title" id="service_name"></h4>
                        </div>
                        <!-- Modal body -->
                        <form method="post" action="'.url('/verify/instantverification/orders/details/data/edit').'" id="order_data_edit">
                        '.csrf_field().'
                        <input type="hidden" name="g_id" id="g_id">
                            <div class="modal-body">
                                <div id="order_details">

                                </div>
                            </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                        
                        </div>
                        </form>';
                }
                
            }
            
            $service_data_array=json_decode($guest_cart_service->service_data,true); 

            $form.='<div class="row">';
                
            foreach ($service_data_array as $service_key => $service_value)
            {
                $i=0;
                $form.='<div class="col-md-12">';
                // if(stripos($service_key,'candidate')!==false)
                // {
                //     $form.='<h3>Candidate Info</h3>';
                // }
                // else
                // {
                    if(stripos($service_key,'check')!==false)
                    {
                        $form.='<h3>Checks Info</h3>';
                        $form.='<p class="pb-border"></p></div>';
                    }
                    
                // }

                foreach ($service_value as $key => $value)
                {
                    if(stripos($guest_cart_service->status,'success')!==false)
                    {
                        if(stripos($service_key,'check')!==false)
                        {
                            $form.='<div class="col-md-12">
                                    <div class="form-group">
                                        <label>'.$key.' : '.$value.'</label>
                                    </div>
                                </div>';
                        }
                    }
                    else
                    {
                        // if(stripos($service_key,'candidate')!==false)
                        // {
                        //     $data='';
                        //     if(stripos($key,'Phone')!==false)
                        //         $data.='<input type="tel" name="common_'.$guest_cart_service->id.'-'.$guest_cart_service->service_id.'-'.$i.'" class="number_only form-control" style="display:block;" value="'.$value.'" readonly>';
                        //     elseif(stripos($key,'Date of Birth')!==false)
                        //        $data.='<input type="date" class="form-control dob" name="common_'.$guest_cart_service->id.'-'.$guest_cart_service->service_id.'-'.$i.'" id="dob" value="'.date("Y-m-d",strtotime($value)).'" readonly>';
                        //     elseif (stripos($key,'Email')!==false)
                        //         $data.='<input type="email" class="form-control email" name="common_'.$guest_cart_service->id.'-'.$guest_cart_service->service_id.'-'.$i.'" id="email" value="'.$value.'" readonly>';
                        //     else
                        //         $data.='<input type="text" class="form-control" name="common_'.$guest_cart_service->id.'-'.$guest_cart_service->service_id.'-'.$i.'" value="'.$value.'" readonly>';

                        //     $form.='<div class="col-md-6">
                        //         <div class="form-group">
                        //             <label>'.$key.'</label>
                        //             <input type="hidden" name="common_label-'.$guest_cart_service->id.'-'.$guest_cart_service->service_id.'-'.$i.'" value="'.$key.'">
                        //             '.$data.'
                        //             <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-common_'.$guest_cart_service->id.'-'.$guest_cart_service->service_id.'-'.$i.'"></p>
                        //         </div>
                        //     </div>';
                        // }
                        // else
                        // {
                            if(stripos($service_key,'check')!==false)
                            {
                                if($guest_cart_service->refund_count>=3)
                                {
                                    $form.='<div class="col-md-12">
                                                <div class="form-group">
                                                    <label>'.$key.' : '.$value.'</label>
                                                </div>
                                            </div>';
                                }
                                else
                                {
                                    $data='';
                                    if(stripos($key,"Date of Birth")!==false) 
                                        $data.='<input type="date" class="form-control dob" name="check_'.$guest_cart_service->id.'-'.$guest_cart_service->service_id.'-'.$i.'" id="dob" value="'.date("Y-m-d",strtotime($value)).'">';
                                    else
                                        $data.='<input type="text" class="form-control" name="check_'.$guest_cart_service->id.'-'.$guest_cart_service->service_id.'-'.$i.'" value="'.$value.'">';
    
                                    $form.='<div class="col-md-12">
                                                <div class="form-group">
                                                    <label>'.$key.'</label>
                                                    <input type="hidden" name="check_label-'.$guest_cart_service->id.'-'.$guest_cart_service->service_id.'-'.$i.'" value="'.$key.'">
                                                    '.$data.'
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-check_'.$guest_cart_service->id.'-'.$guest_cart_service->service_id.'-'.$i.'"></p>
                                                </div>
                                            </div>';
                                }
                               
                            }
                            
                        // }

                        $i++;
                    }
                    
                }
            }
            // if(stripos($guest_cart_service->status,'success')!==false)
                $form.='</div>';
            // else
            //     $form.='</div>';
        }
        
        return response()->json([
            'data' => $guest_cart_service,
            'form' => $form,
            'modal' => $modal
        ]);
    }

    public function instantOrderDetailsDataEdit(Request $request)
    {
        $id=base64_decode($request->g_id);

        $refund_count = 0;

        DB::beginTransaction();
        try{

            $gcs=DB::table('guest_instant_cart_services as gcs')
                    ->select('gcs.*','s.name')
                    ->join('services as s','s.id','=','gcs.service_id')
                    ->where(['gcs.id'=>$id])
                    ->first();
            
            // if($request->has('common_'.$gcs->id.'-'.$gcs->service_id.'-'.'7')){
            //     $dob = date('Y-m-d',strtotime($request->input('common_'.$gcs->id.'-'.$gcs->service_id.'-'.'7')));
            // }
            // $date_of_b=Carbon::parse($dob)->format('Y-m-d');
            // $today=Carbon::now();
            // $today_date=Carbon::now()->format('Y-m-d');
            // $year=$today->diffInYears($date_of_b);
            // $rules=[
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'0' => 'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'1' => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'2' => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'3' => 'required|regex:/^[a-zA-Z ]+$/u|min:2|max:255',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'4' => 'required',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'5' => 'required|regex:/^(?=.*[0-9])[0-9]{10}$/',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'6' => 'required|email',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'7' => 'required|date',
            // ];

            // $custom=[
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.required' => 'First Name is Required',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'First Name should be in letters',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.min' => 'First Name should be atleast 1',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.max' => 'First Name should be maximum 255 characters',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.regex' => 'Middle Name should be in letters',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.min' => 'Middle Name should be atleast 1',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.max' => 'Middle Name should be maximum 255 characters',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'2.required' => 'Last Name is Required',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'2.regex' => 'Last Name should be in letters',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'2.min' => 'Last Name should be atleast 1',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'2.max' => 'Last Name should be maximum 255 characters',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'3.required' => 'Father Name is Required',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'3.regex' => 'Father Name should be in letters',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'3.min' => 'Father Name should be atleast 1',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'3.max' => 'Father Name should be maximum 255 characters',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'4.required' => 'Gender is Required',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'5.required' => 'Phone is Required',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'5.regex' => 'Phone must be 10-digit Number',
            //     // 'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'5.min' => 'Phone must be 10-digit Number',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'6.required' => 'Email is Required',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'6.email' => 'Email should be written in correct format',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'7.required' => 'Date of Birth is Required',
            //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'7.date' => 'Date of Birth must be in date format',
            // ];

            // $validator = Validator::make($request->all(), $rules,$custom);
    
            // if ($validator->fails()){
            //     return response()->json([
            //         'success' => false,
            //         'errors' => $validator->errors()
            //     ]);
            // }

            // if($year<18 || ($date_of_b >= $today_date))
            // {
            //     return response()->json([
            //         'success' => false,
            //         'custom'  => 'yes',
            //         'errors' =>['common_'.$gcs->id.'-'.$gcs->service_id.'-'.'7'=>'Age Must be 18 or older !']
            //     ]);
            // }

             //check data validation & update status
             if($gcs->name=='Aadhar')
             {
                 $rules= 
                 [
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'   => 'required|regex:/^((?!([0-1]))[0-9]{12})$/',
                 ];
                 $custom=[
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.required' => 'Aadhar Number is Required',
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'Enter a valid 12-digit Aadhar Number',
                 ];
                 $validator = Validator::make($request->all(), $rules,$custom);
                 
                 if ($validator->fails()){
                     return response()->json([
                         'success' => false,
                         'errors' => $validator->errors()
                     ]);
                 }

                 $this->idCheckAadhar($gcs->id,$gcs->service_id,$request->input('check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'));

             }
             else if($gcs->service_id==3)
             {
                 $rules= 
                 [
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'   => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{10}$/',
                 ];
                 $custom=[
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.required' => 'PAN Number is Required',
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'Enter a valid 10-digit PAN Number',
                 ];
                 $validator = Validator::make($request->all(), $rules,$custom);
                 
                 if ($validator->fails()){
                     return response()->json([
                         'success' => false,
                         'errors' => $validator->errors()
                     ]);
                 }

                $this->idCheckPan($gcs->id,$gcs->service_id,$request->input('check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'));
             }
             else if($gcs->name=='Voter ID')
             {
                 $rules= 
                 [
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'   => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{10}$/',
                 ];
                 $custom=[
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.required' => 'Voter ID Number is Required',
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'Enter a valid 10-digit Voter ID Number',
                 ];
                 $validator = Validator::make($request->all(), $rules,$custom);
                 
                 if ($validator->fails()){
                     return response()->json([
                         'success' => false,
                         'errors' => $validator->errors()
                     ]);
                 }

                $this->idCheckVoterID($gcs->id,$gcs->service_id,$request->input('check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'));
             }
             else if($gcs->name=='Driving')
             {
                $dob = NULL;       
       
                if($request->has('check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1')){
                    $dob = date('Y-m-d',strtotime($request->input('check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1')));
                }
                $date_of_b=Carbon::parse($dob)->format('Y-m-d');
                $today=Carbon::now();
                $today_date=Carbon::now()->format('Y-m-d');
                $year=$today->diffInYears($date_of_b);

                 $rules= 
                 [
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'   => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{8,}$/',
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1'   => 'required|date',
                 ];
                 $custom=[
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.required' => 'DL Number is Required',
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'Enter a valid DL Number',
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.required' => 'Date Of Birth is Required',
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.date' => 'Date of Birth Must Be In Date Format',
                 ];
                 $validator = Validator::make($request->all(), $rules,$custom);
                 
                 if ($validator->fails()){
                     return response()->json([
                         'success' => false,
                         'errors' => $validator->errors()
                     ]);
                 }

                if($year<18 || ($date_of_b >= $today_date))
                {
                    return response()->json([
                        'success' => false,
                        'custom'  => 'yes',
                        'errors' =>['check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1'=>'Age Must Be 18 Or Older !']
                    ]);
                }

                $this->idCheckDL($gcs->id,$gcs->service_id,$request->input('check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'),$request->input('check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1'));
             }
             else if($gcs->name=='RC')
             {
                 $rules= 
                 [
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'   => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{8,}$/',
                 ];
                 $custom=[
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.required' => 'RC Number is Required',
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'Enter a valid RC Number',
                 ];
                 $validator = Validator::make($request->all(), $rules,$custom);
                 
                 if ($validator->fails()){
                     return response()->json([
                         'success' => false,
                         'errors' => $validator->errors()
                     ]);
                 }

                $this->idCheckRC($gcs->id,$gcs->service_id,$request->input('check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'));
             }
             else if($gcs->name=='Passport')
             {

                 $dob = NULL;       

                 if($request->has('check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1')){
                     $dob = date('Y-m-d',strtotime($request->input('check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1')));
                 }
                 $date_of_b=Carbon::parse($dob)->format('Y-m-d');
                 $today=Carbon::now();
                 $today_date=Carbon::now()->format('Y-m-d');
                 $year=$today->diffInYears($date_of_b);

                 $rules= 
                 [
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'   => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{15,}$/',
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1'   => 'required|date',
                 ];
                 $custom=[
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.required' => 'File Number is Required',
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'Enter a valid File Number',
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.required' => 'Date of Birth is Required',
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.date' => 'Date of Birth must be in date format',
                 ];
                 $validator = Validator::make($request->all(), $rules,$custom);
                 
                 if ($validator->fails()){
                     return response()->json([
                         'success' => false,
                         'errors' => $validator->errors()
                     ]);
                 }

                 if($year<18 || ($date_of_b >= $today_date))
                 {
                     return response()->json([
                         'success' => false,
                         'custom'  => 'yes',
                         'errors' =>['check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1'=>'Age Must be 18 or older !']
                     ]);
                 }

                 $this->idCheckPassport($gcs->id,$gcs->service_id,$request->input('check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'),$request->input('check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1'));

             }
             else if($gcs->name=='Bank Verification')
             {
                 $rules= 
                 [
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'   => 'required|regex:/^(?=.*[0-9])[A-Z0-9]{9,18}$/',
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1'   => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{11,}$/',
                 ];
                 $custom=[
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.required' => 'Account Number is required',
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'Enter a valid Account Number',
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.required' => 'IFSC Code is Required',
                     'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.regex' => 'Enter a valid IFSC Code',
                 ];
                 $validator = Validator::make($request->all(), $rules,$custom);
                 
                 if ($validator->fails()){
                     return response()->json([
                         'success' => false,
                         'errors' => $validator->errors()
                     ]);
                 }

                 $this->idCheckBankAccount($gcs->id,$gcs->service_id,$request->input('check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'),$request->input('check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1'));
             }
             else if(stripos($gcs->name,'E-Court')!==false)
             {
                $rules= 
                [
                    'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'   => 'required|regex:/^[A-Za-z]+([A-Za-z]+\s)*[A-za-z]+$/u|min:3|max:255',
                    'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1'   => 'required|regex:/^[A-Za-z]+([A-Za-z]+\s)*[A-za-z]+$/u|min:3|max:255',
                    'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'2'   => 'required|min:4|max:255',
                ];
                $custom=[
                    'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.required' => 'Name Is Required',
                    'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'Name Must Be A String',
                    'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.min' => 'Name Must Be Atleast 3 Character Long',
                    'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.max' => 'Name Must Be Maximum 255 Character Long',
                    'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.required' => 'Father Name Is Required',
                    'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.regex' => 'Father Name Must Be A String',
                    'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.min' => 'Father Name Must Be Atleast 3 Character Long',
                    'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.max' => 'Father Name Must Be Maximum 255 Character Long',
                    'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'2.required' => 'Address Is Required',
                    'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'2.min' => 'Address Must Be Atleast 4 Character Long',
                    'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'2.max' => 'Address Must Be Maximum 255 Character Long',
                ];
                $validator = Validator::make($request->all(), $rules,$custom);
                
                if ($validator->fails()){
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ]);
                }

                $this->idCheckECourt($gcs->id,$gcs->service_id,$request->input('check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'),$request->input('check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1'),$request->input('check_'.$gcs->id.'-'.$gcs->service_id.'-'.'2'));
             }
             else if(stripos($gcs->name,'UPI Verification')!==false)
             {
                $rules= 
                [
                    'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'   => 'required|regex:/^[\w\.\-_]{3,}@[a-zA-Z]{3,}$/u',
                ];
                $custom=[
                    'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.required' => 'UPI ID Is Required',
                    'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'Enter A Valid UPI ID',
                ];
                $validator = Validator::make($request->all(), $rules,$custom);
                
                if ($validator->fails()){
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ]);
                }

                $this->idCheckUPI($gcs->id,$gcs->service_id,$request->input('check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'));
             }
             else if(stripos($gcs->name,'CIN Verification')!==false)
             {
                $rules= 
                [
                    'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'   => ['required','regex:/^([L|U]{1})([0-9]{5})([A-Za-z]{2})([0-9]{4})([A-Za-z]{3})([0-9]{6})$/u'],
                ];
                $custom=[
                    'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.required' => 'CIN Number is Required',
                    'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'Enter a Valid CIN Number',
                ];
                $validator = Validator::make($request->all(), $rules,$custom);
                
                if ($validator->fails()){
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ]);
                }

                $this->idCheckCIN($gcs->id,$gcs->service_id,$request->input('check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'));
             }

                $common_data=[];

                $checks_data=[];

                $form_data=[];
                
                $service_array=json_decode($gcs->service_data,true);

                $common_data=$service_array['candidate'];

                $guest_service_form_input=DB::table('guest_service_form_inputs')
                                            ->where(['service_id'=>$gcs->service_id])
                                            ->get();
                
                $i=0;
                foreach($guest_service_form_input as $input)
                {
                    $checks_data[$request->input('check_label-'.$gcs->id.'-'.$gcs->service_id.'-'.$i)]=$request->input('check_'.$gcs->id.'-'.$gcs->service_id.'-'.$i);
                    $i++;
                }

                $form_data=['candidate'=>$common_data,'check'=>$checks_data];

                $data=[
                    'service_data' => json_encode($form_data),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update($data);

                $guest_cart=DB::table('guest_instant_carts')
                            ->where(['giv_m_id'=>$gcs->giv_m_id])
                            ->orderBy('service_id','asc')
                            ->get();

                // generating the service_wise zip
                foreach($guest_cart as $gc)
                {
                    $guest_cart_services=DB::table('guest_instant_cart_services')
                                        ->where(['giv_c_id'=>$gc->id,'service_id'=>$gc->service_id])
                                        ->get();

                    $zipname="";
                    $services=DB::table('services')->where('id',$gc->service_id)->first();

                    if($services->name=='Aadhar')
                    {
                        $zipname = 'aadhar-'.date('Ymdhis').'.zip';
                        $path = public_path().'/guest/reports/zip/aadhar/';
                    }
                    else if($gc->service_id==3)
                    {
                        $zipname = 'pan-'.date('Ymdhis').'.zip';
                        $path = public_path().'/guest/reports/zip/pan/';
                    }
                    else if($services->name=='Voter ID')
                    {
                        $zipname = 'voter_id-'.date('Ymdhis').'.zip';
                        $path = public_path().'/guest/reports/zip/voterid/';
                    }
                    else if($services->name=='RC')
                    {
                        $zipname = 'rc-'.date('Ymdhis').'.zip';
                        $path = public_path().'/guest/reports/zip/rc/';
                    }
                    else if($services->name=='Passport')
                    {
                        $zipname = 'passport-'.date('Ymdhis').'.zip';
                        $path = public_path().'/guest/reports/zip/passport/';
                    }
                    else if($services->name=='Driving')
                    {
                        $zipname = 'driving-'.date('Ymdhis').'.zip';
                        $path = public_path().'/guest/reports/zip/driving/';
                    }
                    else if($services->name=='Bank Verification')
                    {
                        $zipname = 'bank-'.date('Ymdhis').'.zip';
                        $path = public_path().'/guest/reports/zip/bank/';
                    }
                    else if(stripos($services->name,'E-Court')!==false)
                    {
                        $zipname = 'e_court-'.date('Ymdhis').'.zip';
                        $path = public_path().'/guest/reports/zip/e-court/';
                    }
                    else if(stripos($services->type_name,'upi')!==false)
                    {
                        $zipname = 'upi-'.date('Ymdhis').'.zip';
                        $path = public_path().'/guest/reports/zip/upi/';
                    }
                    else if(stripos($services->type_name,'cin')!==false)
                    {
                        $zipname = 'cin-'.date('Ymdhis').'.zip';
                        $path = public_path().'/guest/reports/zip/cin/';
                    }

                    if(!File::exists($path))
                    {
                        File::makeDirectory($path, $mode = 0777, true, true);
                    }

                    $zip = new \ZipArchive();
                    $zip->open($path.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                    foreach($guest_cart_services as $gcs)
                    {
                        $path = public_path()."/guest/reports/pdf/".$gcs->file_name;
                        
                        $zip->addFile($path, '/reports/'.basename($path));  
                    }

                    $zip->close();

                    if($gc->zip_name!=NULL)
                    {
                        if(File::exists($path.$gc->zip_name))
                        {
                            File::delete($path.$gc->zip_name);
                        }
                    }
                    $gcs_fail=DB::table('guest_instant_cart_services')->where(['giv_c_id'=>$gc->id,'service_id'=>$gc->service_id,'status'=>'failed'])->get();

                    if(count($guest_cart_services)==count($gcs_fail))
                    {
                        DB::table('guest_instant_carts')->where(['id'=>$gc->id])->update([
                            'zip_name' => $zipname!=""?$zipname:NULL,
                            'status' => 'failed',
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                    else
                    {
                        DB::table('guest_instant_carts')->where(['id'=>$gc->id])->update([
                            'zip_name' => $zipname!=""?$zipname:NULL,
                            'status' => 'success',
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                    }

                }

                //generating the master zip
                $guest_cart=DB::table('guest_instant_carts')
                            ->where(['giv_m_id'=>$gcs->giv_m_id])
                            ->orderBy('service_id','asc')
                            ->get();
        
                $zipname="";
            
                $zipname = 'reports-'.date('Ymdhis').'.zip';
                $zip = new \ZipArchive();      
                $zip->open(public_path().'/guest/reports/zip/'.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                $guest_cart=DB::table('guest_instant_carts')
                                    ->where(['giv_m_id'=>$gcs->giv_m_id])
                                    ->orderBy('service_id','asc')
                                    ->get();

                foreach($guest_cart as $gc)
                {
                    $services=DB::table('services')->where('id',$gc->service_id)->first();
                    if($services->name=='Aadhar')
                    {
                        $path = public_path().'/guest/reports/zip/aadhar/'.$gc->zip_name;
                    }
                    else if($gc->service_id==3)
                    {
                        $path = public_path().'/guest/reports/zip/pan/'.$gc->zip_name;
                    }
                    else if($services->name=='Voter ID')
                    {
                        $path = public_path().'/guest/reports/zip/voterid/'.$gc->zip_name;
                    }
                    else if($services->name=='RC')
                    {
                        $path = public_path().'/guest/reports/zip/rc/'.$gc->zip_name;
                    }
                    else if($services->name=='Passport')
                    {
                        $path = public_path().'/guest/reports/zip/passport/'.$gc->zip_name;
                    }
                    else if($services->name=='Driving')
                    {
                        $path = public_path().'/guest/reports/zip/driving/'.$gc->zip_name;
                    }
                    else if($services->name=='Bank Verification')
                    {
                        $path = public_path().'/guest/reports/zip/bank/'.$gc->zip_name;
                    }
                    else if(stripos($services->name,'E-Court')!==false)
                    {
                        $path = public_path().'/guest/reports/zip/e-court/'.$gc->zip_name;
                    }
                    else if(stripos($services->type_name,'upi')!==false)
                    {
                        $path = public_path().'/guest/reports/zip/upi/'.$gc->zip_name;
                    }
                    else if(stripos($services->type_name,'cin')!==false)
                    {
                        $path = public_path().'/guest/reports/zip/cin/'.$gc->zip_name;
                    }

                    $zip->addFile($path, '/reports/'.basename($path));  
                }

                $zip->close();

                $gm=DB::table('guest_instant_masters')->where(['id'=>$gcs->giv_m_id])->first();
                if($gm->zip_name!=NULL)
                {
                    if(File::exists($path.$gc->zip_name))
                    {
                        File::delete($path.$gc->zip_name);
                    }
                }
                $gc_fail=DB::table('guest_instant_carts')
                ->where(['giv_m_id'=> $gcs->giv_m_id,'status'=>'failed'])
                ->orderBy('service_id','asc')
                ->get();

                if(count($guest_cart)==count($gc_fail))
                {
                    DB::table('guest_instant_masters')->where(['id'=> $gcs->giv_m_id])->update([
                        'zip_name' => $zipname!=""?$zipname:NULL,
                        'status' => 'failed',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                else
                {
                    DB::table('guest_instant_masters')->where(['id'=> $gcs->giv_m_id])->update([
                        'zip_name' => $zipname!=""?$zipname:NULL,
                        'status' => 'success',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }

                $guest_instant_service = DB::table('guest_instant_cart_services as gcs')
                                            ->select('gcs.*','s.name')
                                            ->join('services as s','s.id','=','gcs.service_id')
                                            ->where(['gcs.id'=>$gcs->id])
                                            ->first();

                if($guest_instant_service->refund_count >=3)
                {
                    $refund_count = $guest_instant_service->refund_count;

                    DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
                        'refund_request_date' =>date('Y-m-d H:i:s')
                    ]);

                    $user_d = DB::table('users')->where(['id'=>$guest_instant_service->parent_id])->first();

                    $guest_instant_master = DB::table('guest_instant_masters')->where(['id'=>$gcs->giv_m_id])->first();

                    $name=$user_d->name;

                    $email=$user_d->email;
            
                    $data=['name' =>$name,'email' => $email,'user'=>$user_d,'guest_instant_service'=>$guest_instant_service,'guest_master'=>$guest_instant_master];
            
                    Mail::send(['html'=>'mails.guest-refund'], $data, function($message) use($email,$name) {
                        $message->to($email, $name)->subject
                            ('myBCD System - Refund Notification');
                        $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                    });

                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'refund_count' => $refund_count,
                ]);

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;

        }
    }

   

    public function instantMasterZipReport(Request $request)
    {
        $zip_id=base64_decode($request->id);

        $guest_master=DB::table('guest_instant_masters')->where(['id'=>$zip_id])->first();
        if($guest_master->zip_name!=NULL && File::exists(public_path()."/guest/reports/zip/".$guest_master->zip_name))
        {
            $file = public_path()."/guest/reports/zip/".$guest_master->zip_name;
            $headers = array('Content-Type: application/zip');
            return response()->download($file, $guest_master->zip_name,$headers);
        }
        else
        {
            $guest_cart_services=DB::table('guest_instant_cart_services')
                                            ->where(['giv_m_id'=>$guest_master->id])
                                            ->orderBy('service_id','asc')
                                            ->get();
            // generate pdf report

            foreach($guest_cart_services as $gcs)
            {
                $data = NULL;
                $service = DB::table('services')->where(['id'=>$gcs->service_id])->first();
                $path=public_path().'/guest/reports/pdf/';
                $file_name = NULL;
                $arr_data = [];

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if($gcs->file_name==NULL || !(File::exists(public_path()."/guest/reports/pdf/".$gcs->file_name)))
                {
                    if(stripos($service->name,'Aadhar')!==false)
                    {

                        $file_name='aadhar-'.$gcs->id.date('Ymdhis').".pdf";

                        $service_data_array=json_decode($gcs->service_data,true);

                        $arr_data = $service_data_array['check'];
                
                        $aadhar_number = $arr_data['Aadhar Number'];

                        if(stripos($gcs->status,'success')!==false)
                        {
                            if($gcs->check_master_id!=NULL)
                            {
                                $data = DB::table('aadhar_check_masters')->select('*')->where(['id'=>$gcs->check_master_id])->first();
                            }
                            else
                            {

                                $data = DB::table('aadhar_check_masters')->select('*')->where(['aadhar_number'=>$aadhar_number])->first();
                            }

                            $master_data = $data;

                            $pdf = PDF::loadView('guest.instantverification.pdf.aadhar', compact('master_data'))
                                ->save($path.$file_name); 

                            DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
                                'check_master_id' => $master_data->id
                            ]);
                        }
                        else
                        {
                            $pdf = PDF::loadView('guest.instantverification.pdf.failed.aadhar', compact('aadhar_number'))
                                    ->save($path.$file_name);

                        }

                    }
                    else if(stripos($service->type_name,'pan')!==false)
                    {
                        $file_name='pan-'.$gcs->id.date('Ymdhis').".pdf";

                        $service_data_array=json_decode($gcs->service_data,true);

                        $data = $service_data_array['check'];

                        $pan_number=$data['PAN Number'];

                        if(stripos($gcs->status,'success')!==false)
                        {
                            if($gcs->check_master_id!=NULL)
                            {
                                $data = DB::table('pan_check_masters')->select('*')->where(['id'=>$gcs->check_master_id])->first();
                            }
                            else
                            {

                                $data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$pan_number])->first();
                            }

                            $master_data = $data;

                            $pdf = PDF::loadView('guest.instantverification.pdf.pan', compact('master_data'))
                                ->save($path.$file_name); 

                            DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
                                'check_master_id' => $master_data->id
                            ]);
                        }
                        else
                        {
                            $pdf = PDF::loadView('guest.instantverification.pdf.failed.pan', compact('pan_number'))
                                    ->save($path.$file_name);

                        }
                    }
                    else if(stripos($service->type_name,'voter_id')!==false)
                    {
                        $file_name='voter_id-'.$gcs->id.date('Ymdhis').".pdf";

                        $service_data_array=json_decode($gcs->service_data,true);

                        $data = $service_data_array['check'];

                        $voter_id_number=$data['Voter ID Number'];

                        if(stripos($gcs->status,'success')!==false)
                        {
                            if($gcs->check_master_id!=NULL)
                            {
                                $data = DB::table('voter_id_check_masters')->select('*')->where(['id'=>$gcs->check_master_id])->first();
                            }
                            else
                            {

                                $data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$voter_id_number])->first();
                            }

                            $master_data = $data;

                            $pdf = PDF::loadView('guest.instantverification.pdf.voter-id', compact('master_data'))
                                ->save($path.$file_name); 

                            DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
                                'check_master_id' => $master_data->id
                            ]);
                        }
                        else
                        {
                            $pdf = PDF::loadView('guest.instantverification.pdf.failed.voter-id', compact('voter_id_number'))
                                    ->save($path.$file_name);

                        }
                    }
                    else if(stripos($service->type_name,'rc')!==false)
                    {
                        $file_name='rc-'.$gcs->id.date('Ymdhis').".pdf";

                        $service_data_array=json_decode($gcs->service_data,true);

                        $data = $service_data_array['check'];

                        $rc_number=$data['RC Number'];

                        if(stripos($gcs->status,'success')!==false)
                        {
                            if($gcs->check_master_id!=NULL)
                            {
                                $data = DB::table('rc_check_masters')->select('*')->where(['id'=>$gcs->check_master_id])->first();
                            }
                            else
                            {

                                $data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$rc_number])->first();
                            }

                            $master_data = $data;

                            $pdf = PDF::loadView('guest.instantverification.pdf.rc', compact('master_data'))
                                ->save($path.$file_name); 

                            DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
                                'check_master_id' => $master_data->id
                            ]);
                        }
                        else
                        {
                            $pdf = PDF::loadView('guest.instantverification.pdf.failed.rc', compact('rc_number'))
                                    ->save($path.$file_name);

                        }
                    }
                    else if(stripos($service->type_name,'passport')!==false)
                    {
                        $file_name='passport-'.$gcs->id.date('Ymdhis').".pdf";

                        $service_data_array=json_decode($gcs->service_data,true);

                        $data = $service_data_array['check'];

                        $file_number=$data['File Number'];

                        $dob=$data['Date of Birth'];

                        if(stripos($gcs->status,'success')!==false)
                        {
                            if($gcs->check_master_id!=NULL)
                            {
                                $data = DB::table('passport_check_masters')->select('*')->where(['id'=>$gcs->check_master_id])->first();
                            }
                            else
                            {

                                $data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$file_number,'dob'=>$dob])->first();
                            }

                            $master_data = $data;

                            $pdf = PDF::loadView('guest.instantverification.pdf.passport', compact('master_data'))
                                ->save($path.$file_name); 

                            DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
                                'check_master_id' => $master_data->id
                            ]);
                        }
                        else
                        {
                            $pdf = PDF::loadView('guest.instantverification.pdf.failed.passport', compact('file_number','dob'))
                                    ->save($path.$file_name);

                        }
                    }
                    else if(stripos($service->name,'Driving')!==false)
                    {
                        $file_name='dl-'.$gcs->id.date('Ymdhis').".pdf";

                        $service_data_array=json_decode($gcs->service_data,true);

                        $data = $service_data_array['check'];

                        $dl_number=$data['DL Number'];

                        if(stripos($gcs->status,'success')!==false)
                        {
                            if($gcs->check_master_id!=NULL)
                            {
                                $data = DB::table('dl_check_masters')->select('*')->where(['id'=>$gcs->check_master_id])->first();
                            }
                            else
                            {

                                $data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$dl_number])->first();
                            }

                            $master_data = $data;

                            $pdf = PDF::loadView('guest.instantverification.pdf.dl', compact('master_data'))
                                ->save($path.$file_name); 

                            DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
                                'check_master_id' => $master_data->id
                            ]);
                        }
                        else
                        {
                            $pdf = PDF::loadView('guest.instantverification.pdf.failed.dl', compact('dl_number'))
                                    ->save($path.$file_name);

                        }
                    }
                    else if(stripos($service->name,'Bank Verification')!==false)
                    {
                        $file_name='bank-'.$gcs->id.date('Ymdhis').".pdf";

                        $service_data_array=json_decode($gcs->service_data,true);

                        $data = $service_data_array['check'];

                        $account_number=$data['Account Number'];

                        $ifsc_code=$data['IFSC Code'];

                        if(stripos($gcs->status,'success')!==false)
                        {
                            if($gcs->check_master_id!=NULL)
                            {
                                $data = DB::table('bank_account_check_masters')->select('*')->where(['id'=>$gcs->check_master_id])->first();
                            }
                            else
                            {

                                $data = DB::table('bank_account_check_masters')->select('*')->where(['account_number'=>$account_number])->first();
                            }

                            $master_data = $data;

                            $pdf = PDF::loadView('guest.instantverification.pdf.bank-verification', compact('master_data'))
                                ->save($path.$file_name); 

                            DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
                                'check_master_id' => $master_data->id
                            ]);
                        }
                        else
                        {
                            $pdf = PDF::loadView('guest.instantverification.pdf.failed.bank-verification', compact('account_number','ifsc_code'))
                                    ->save($path.$file_name);

                        }
                    }
                    else if(stripos($service->type_name,'e_court')!==false)
                    {
                        $file_name='e_court-'.$gcs->id.date('Ymdhis').".pdf";

                        $service_data_array=json_decode($gcs->service_data,true);

                        $data = $service_data_array['check'];

                        $name=$data['Name'];

                        $father_name=$data['Father Name'];

                        $address=$data['Address'];

                        if(stripos($gcs->status,'success')!==false)
                        {
                            if($gcs->check_master_id!=NULL)
                            {
                                $data = DB::table('e_court_check_masters')->select('*')->where(['id'=>$gcs->check_master_id])->first();
                            }
                            else
                            {

                                $data = DB::table('e_court_check_masters')->select('*')->where(['name'=>$name,'father_name'=>$father_name,'address'=>$address])->latest()->first();
                            }

                            $master_data = $data;

                            $pdf = PDF::loadView('guest.instantverification.pdf.e_court', compact('master_data'))
                                ->save($path.$file_name); 

                            DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
                                'check_master_id' => $master_data->id
                            ]);
                        }
                        else
                        {
                            $pdf = PDF::loadView('guest.instantverification.pdf.failed.e_court', compact('name','father_name','address'))
                                    ->save($path.$file_name);

                        }
                    }
                    else if(stripos($service->type_name,'upi')!==false)
                    {
                        $file_name='upi-'.$gcs->id.date('Ymdhis').".pdf";

                        $service_data_array=json_decode($gcs->service_data,true);

                        $data = $service_data_array['check'];

                        $upi_id=$data['UPI ID'];

                        if(stripos($gcs->status,'success')!==false)
                        {
                            if($gcs->check_master_id!=NULL)
                            {
                                $data = DB::table('upi_check_masters')->select('*')->where(['id'=>$gcs->check_master_id])->first();
                            }
                            else
                            {

                                $data = DB::table('upi_check_masters')->select('*')->where(['upi_id'=>$upi_id])->first();
                            }

                            $master_data = $data;

                            $pdf = PDF::loadView('guest.instantverification.pdf.upi', compact('master_data'))
                                ->save($path.$file_name); 

                            DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
                                'check_master_id' => $master_data->id
                            ]);
                        }
                        else
                        {
                            $pdf = PDF::loadView('guest.instantverification.pdf.failed.upi', compact('upi_id'))
                                    ->save($path.$file_name);

                        }
                    }
                    else if(stripos($service->type_name,'cin')!==false)
                    {
                        $file_name='cin-'.$gcs->id.date('Ymdhis').".pdf";

                        $service_data_array=json_decode($gcs->service_data,true);

                        $data = $service_data_array['check'];

                        $cin=$data['CIN Number'];

                        if(stripos($gcs->status,'success')!==false)
                        {
                            if($gcs->check_master_id!=NULL)
                            {
                                $data = DB::table('cin_check_masters')->select('*')->where(['id'=>$gcs->check_master_id])->first();
                            }
                            else
                            {

                                $data = DB::table('cin_check_masters')->select('*')->where(['cin_number'=>$cin])->first();
                            }

                            $master_data = $data;

                            $pdf = PDF::loadView('guest.instantverification.pdf.cin', compact('master_data'))
                                ->save($path.$file_name); 

                            DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
                                'check_master_id' => $master_data->id
                            ]);
                        }
                        else
                        {
                            $pdf = PDF::loadView('guest.instantverification.pdf.failed.cin', compact('cin_number'))
                                    ->save($path.$file_name);

                        }
                    }

                    DB::table('guest_instant_cart_services')->where(['id'=>$gcs->id])->update([
                        'file_name' => $file_name
                    ]);
                }
            }

            // generating the service_wise zip

            $guest_cart=DB::table('guest_instant_carts')
                                ->where(['giv_m_id'=>$guest_master->id])
                                ->orderBy('service_id','asc')
                                ->get();

            foreach($guest_cart as $gc)
            {
                $guest_cart_services=DB::table('guest_instant_cart_services')
                                    ->where(['giv_c_id'=>$gc->id])
                                    ->get();

                $zipname="";
                $services=DB::table('services')->where('id',$gc->service_id)->first();

                if($services->name=='Aadhar')
                {
                    $zipname = 'aadhar-'.date('Ymdhis').'.zip';
                    $path = public_path().'/guest/reports/zip/aadhar/';
                }
                else if($gc->service_id==3)
                {
                    $zipname = 'pan-'.date('Ymdhis').'.zip';
                    $path = public_path().'/guest/reports/zip/pan/';
                }
                else if($services->name=='Voter ID')
                {
                    $zipname = 'voter_id-'.date('Ymdhis').'.zip';
                    $path = public_path().'/guest/reports/zip/voterid/';
                }
                else if($services->name=='RC')
                {
                    $zipname = 'rc-'.date('Ymdhis').'.zip';
                    $path = public_path().'/guest/reports/zip/rc/';
                }
                else if($services->name=='Passport')
                {
                    $zipname = 'passport-'.date('Ymdhis').'.zip';
                    $path = public_path().'/guest/reports/zip/passport/';
                }
                else if($services->name=='Driving')
                {
                    $zipname = 'driving-'.date('Ymdhis').'.zip';
                    $path = public_path().'/guest/reports/zip/driving/';
                }
                else if($services->name=='Bank Verification')
                {
                    $zipname = 'bank-'.date('Ymdhis').'.zip';
                    $path = public_path().'/guest/reports/zip/bank/';
                }
                else if(stripos($services->name,'E-Court')!==false)
                {
                    $zipname = 'e_court-'.date('Ymdhis').'.zip';
                    $path = public_path().'/guest/reports/zip/e-court/';
                }
                else if(stripos($services->type_name,'upi')!==false)
                {
                    $zipname = 'upi-'.date('Ymdhis').'.zip';
                    $path = public_path().'/guest/reports/zip/upi/';
                }
                else if(stripos($services->type_name,'cin')!==false)
                {
                    $zipname = 'cin-'.date('Ymdhis').'.zip';
                    $path = public_path().'/guest/reports/zip/cin/';
                }

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                $zip = new \ZipArchive();
                $zip->open($path.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                foreach($guest_cart_services as $gcs)
                {
                    $path = public_path()."/guest/reports/pdf/".$gcs->file_name;
                    
                    $zip->addFile($path, '/reports/'.basename($path));  
                }
                $zip->close();
               
                DB::table('guest_instant_carts')->where(['id'=>$gc->id])->update([
                    'zip_name' => $zipname!=""?$zipname:NULL,
                    
                ]);

            }

            //generating the master zip

            if(!File::exists(public_path().'/guest/reports/zip/'))
            {
                File::makeDirectory(public_path().'/guest/reports/zip/', $mode = 0777, true, true);
            }

            $zipname1="";
            $path=''; 
            $zipname1 = 'reports-'.date('Ymdhis').'.zip';
            $zip1 = new \ZipArchive();      
            $zip1->open(public_path().'/guest/reports/zip/'.$zipname1, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            $guest_cart=DB::table('guest_instant_carts')
                                ->where(['giv_m_id'=>$guest_master->id])
                                ->orderBy('service_id','asc')
                                ->get();

            foreach($guest_cart as $gc)
            {
                $services=DB::table('services')->where('id',$gc->service_id)->first();
                if($services->name=='Aadhar')
                {
                    $path = public_path().'/guest/reports/zip/aadhar/';
                }
                else if($gc->service_id==3)
                {
                    $path = public_path().'/guest/reports/zip/pan/';
                }
                else if($services->name=='Voter ID')
                {
                    $path = public_path().'/guest/reports/zip/voterid/';
                }
                else if($services->name=='RC')
                {
                    $path = public_path().'/guest/reports/zip/rc/';
                }
                else if($services->name=='Passport')
                {
                    $path = public_path().'/guest/reports/zip/passport/';
                }
                else if($services->name=='Driving')
                {
                    $path = public_path().'/guest/reports/zip/driving/';
                }
                else if($services->name=='Bank Verification')
                {
                    $path = public_path().'/guest/reports/zip/bank/';
                }
                else if(stripos($services->name,'E-Court')!==false)
                {
                    $path = public_path().'/guest/reports/zip/e-court/';
                }
                else if(stripos($services->type_name,'upi')!==false)
                {
                    $path = public_path().'/guest/reports/zip/upi/';
                }
                else if(stripos($services->type_name,'cin')!==false)
                {
                    $path = public_path().'/guest/reports/zip/cin/';
                }

                if(!File::exists($path))
                {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                $zip1->addFile($path.$gc->zip_name, '/reports/'.basename($path.$gc->zip_name));  
            }

            $zip1->close();

            DB::table('guest_instant_masters')->where(['id'=> $guest_master->id])->update([
                'zip_name' => $zipname1!=""?$zipname1:NULL,
            ]);

            $guest_master = DB::table('guest_instant_masters')->where(['id'=>$zip_id])->first();

            $file = public_path()."/guest/reports/zip/".$guest_master->zip_name;
            $headers = array('Content-Type: application/zip');
            return response()->download($file, $guest_master->zip_name,$headers);
        }
    }

    public function instantCheckPDFReport(Request $request)
    {
        $gcs_id=base64_decode($request->id);

        $guest_cart_service=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id])->first();

        if($guest_cart_service->file_name!=NULL && File::exists(public_path()."/guest/reports/pdf/".$guest_cart_service->file_name))
        {
            $file = public_path()."/guest/reports/pdf/".$guest_cart_service->file_name;
            $headers = array('Content-Type: application/pdf');
            return response()->download($file, $guest_cart_service->file_name,$headers);
        }
        else
        {
            // generating pdf report
            $data = NULL;
            $service = DB::table('services')->where(['id'=>$guest_cart_service->service_id])->first();
            $path=public_path().'/guest/reports/pdf/';
            $file_name = NULL;
            $arr_data = [];

            if(!File::exists($path))
            {
                File::makeDirectory($path, $mode = 0777, true, true);
            }

            if(stripos($service->name,'Aadhar')!==false)
            {

                $file_name='aadhar-'.$guest_cart_service->id.date('Ymdhis').".pdf";

                $service_data_array=json_decode($guest_cart_service->service_data,true);

                $arr_data = $service_data_array['check'];
        
                $aadhar_number = $arr_data['Aadhar Number'];

                if(stripos($guest_cart_service->status,'success')!==false)
                {
                    if($guest_cart_service->check_master_id!=NULL)
                    {
                        $data = DB::table('aadhar_check_masters')->select('*')->where(['id'=>$guest_cart_service->check_master_id])->first();
                    }
                    else
                    {

                        $data = DB::table('aadhar_check_masters')->select('*')->where(['aadhar_number'=>$aadhar_number])->first();
                    }

                    $master_data = $data;

                    $pdf = PDF::loadView('guest.instantverification.pdf.aadhar', compact('master_data'))
                        ->save($path.$file_name); 

                    DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                        'check_master_id' => $master_data->id
                    ]);
                }
                else
                {
                    $pdf = PDF::loadView('guest.instantverification.pdf.failed.aadhar', compact('aadhar_number'))
                            ->save($path.$file_name);

                }

            }
            else if(stripos($service->type_name,'pan')!==false)
            {
                $file_name='pan-'.$guest_cart_service->id.date('Ymdhis').".pdf";

                $service_data_array=json_decode($guest_cart_service->service_data,true);

                $data = $service_data_array['check'];

                $pan_number=$data['PAN Number'];

                if(stripos($guest_cart_service->status,'success')!==false)
                {
                    if($guest_cart_service->check_master_id!=NULL)
                    {
                        $data = DB::table('pan_check_masters')->select('*')->where(['id'=>$guest_cart_service->check_master_id])->first();
                    }
                    else
                    {

                        $data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$pan_number])->first();
                    }

                    $master_data = $data;

                    $pdf = PDF::loadView('guest.instantverification.pdf.pan', compact('master_data'))
                        ->save($path.$file_name); 

                    DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                        'check_master_id' => $master_data->id
                    ]);
                }
                else
                {
                    $pdf = PDF::loadView('guest.instantverification.pdf.failed.pan', compact('pan_number'))
                            ->save($path.$file_name);

                }
            }
            else if(stripos($service->type_name,'voter_id')!==false)
            {
                $file_name='voter_id-'.$guest_cart_service->id.date('Ymdhis').".pdf";

                $service_data_array=json_decode($guest_cart_service->service_data,true);

                $data = $service_data_array['check'];

                $voter_id_number=$data['Voter ID Number'];

                if(stripos($guest_cart_service->status,'success')!==false)
                {
                    if($guest_cart_service->check_master_id!=NULL)
                    {
                        $data = DB::table('voter_id_check_masters')->select('*')->where(['id'=>$guest_cart_service->check_master_id])->first();
                    }
                    else
                    {

                        $data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$voter_id_number])->first();
                    }

                    $master_data = $data;

                    $pdf = PDF::loadView('guest.instantverification.pdf.voter-id', compact('master_data'))
                        ->save($path.$file_name); 

                    DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                        'check_master_id' => $master_data->id
                    ]);
                }
                else
                {
                    $pdf = PDF::loadView('guest.instantverification.pdf.failed.voter-id', compact('voter_id_number'))
                            ->save($path.$file_name);

                }
            }
            else if(stripos($service->type_name,'rc')!==false)
            {
                $file_name='rc-'.$guest_cart_service->id.date('Ymdhis').".pdf";

                $service_data_array=json_decode($guest_cart_service->service_data,true);

                $data = $service_data_array['check'];

                $rc_number=$data['RC Number'];

                if(stripos($guest_cart_service->status,'success')!==false)
                {
                    if($guest_cart_service->check_master_id!=NULL)
                    {
                        $data = DB::table('rc_check_masters')->select('*')->where(['id'=>$guest_cart_service->check_master_id])->first();
                    }
                    else
                    {

                        $data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$rc_number])->first();
                    }

                    $master_data = $data;

                    $pdf = PDF::loadView('guest.instantverification.pdf.rc', compact('master_data'))
                        ->save($path.$file_name); 

                    DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                        'check_master_id' => $master_data->id
                    ]);
                }
                else
                {
                    $pdf = PDF::loadView('guest.instantverification.pdf.failed.rc', compact('rc_number'))
                            ->save($path.$file_name);

                }
            }
            else if(stripos($service->type_name,'passport')!==false)
            {
                $file_name='passport-'.$guest_cart_service->id.date('Ymdhis').".pdf";

                $service_data_array=json_decode($guest_cart_service->service_data,true);

                $data = $service_data_array['check'];

                $file_number=$data['File Number'];

                $dob=$data['Date of Birth'];

                if(stripos($guest_cart_service->status,'success')!==false)
                {
                    if($guest_cart_service->check_master_id!=NULL)
                    {
                        $data = DB::table('passport_check_masters')->select('*')->where(['id'=>$guest_cart_service->check_master_id])->first();
                    }
                    else
                    {

                        $data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$file_number,'dob'=>$dob])->first();
                    }

                    $master_data = $data;

                    $pdf = PDF::loadView('guest.instantverification.pdf.passport', compact('master_data'))
                        ->save($path.$file_name); 

                    DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                        'check_master_id' => $master_data->id
                    ]);
                }
                else
                {
                    $pdf = PDF::loadView('guest.instantverification.pdf.failed.passport', compact('file_number','dob'))
                            ->save($path.$file_name);

                }
            }
            else if(stripos($service->name,'Driving')!==false)
            {
                $file_name='dl-'.$guest_cart_service->id.date('Ymdhis').".pdf";

                $service_data_array=json_decode($guest_cart_service->service_data,true);

                $data = $service_data_array['check'];

                $dl_number=$data['DL Number'];

                if(stripos($guest_cart_service->status,'success')!==false)
                {
                    if($guest_cart_service->check_master_id!=NULL)
                    {
                        $data = DB::table('dl_check_masters')->select('*')->where(['id'=>$guest_cart_service->check_master_id])->first();
                    }
                    else
                    {

                        $data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$dl_number])->first();
                    }

                    $master_data = $data;

                    $pdf = PDF::loadView('guest.instantverification.pdf.dl', compact('master_data'))
                        ->save($path.$file_name); 

                    DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                        'check_master_id' => $master_data->id
                    ]);
                }
                else
                {
                    $pdf = PDF::loadView('guest.instantverification.pdf.failed.dl', compact('dl_number'))
                            ->save($path.$file_name);

                }
            }
            else if(stripos($service->name,'Bank Verification')!==false)
            {
                $file_name='bank-'.$guest_cart_service->id.date('Ymdhis').".pdf";

                $service_data_array=json_decode($guest_cart_service->service_data,true);

                $data = $service_data_array['check'];

                $account_number=$data['Account Number'];

                $ifsc_code=$data['IFSC Code'];

                if(stripos($guest_cart_service->status,'success')!==false)
                {
                    if($guest_cart_service->check_master_id!=NULL)
                    {
                        $data = DB::table('bank_account_check_masters')->select('*')->where(['id'=>$guest_cart_service->check_master_id])->first();
                    }
                    else
                    {

                        $data = DB::table('bank_account_check_masters')->select('*')->where(['account_number'=>$account_number])->first();
                    }

                    $master_data = $data;

                    $pdf = PDF::loadView('guest.instantverification.pdf.bank-verification', compact('master_data'))
                        ->save($path.$file_name); 

                    DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                        'check_master_id' => $master_data->id
                    ]);
                }
                else
                {
                    $pdf = PDF::loadView('guest.instantverification.pdf.failed.bank-verification', compact('account_number','ifsc_code'))
                            ->save($path.$file_name);

                }
            }
            else if(stripos($service->type_name,'e_court')!==false)
            {
                $file_name='e_court-'.$guest_cart_service->id.date('Ymdhis').".pdf";

                $service_data_array=json_decode($guest_cart_service->service_data,true);

                $data = $service_data_array['check'];

                $name=$data['Name'];

                $father_name=$data['Father Name'];

                $address=$data['Address'];

                if(stripos($guest_cart_service->status,'success')!==false)
                {
                    if($guest_cart_service->check_master_id!=NULL)
                    {
                        $data = DB::table('e_court_check_masters')->select('*')->where(['id'=>$guest_cart_service->check_master_id])->first();
                    }
                    else
                    {

                        $data = DB::table('e_court_check_masters')->select('*')->where(['name'=>$name,'father_name'=>$father_name,'address'=>$address])->latest()->first();
                    }

                    $master_data = $data;

                    $pdf = PDF::loadView('guest.instantverification.pdf.e_court', compact('master_data'))
                        ->save($path.$file_name); 

                    DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                        'check_master_id' => $master_data->id
                    ]);
                }
                else
                {
                    $pdf = PDF::loadView('guest.instantverification.pdf.failed.e_court', compact('name','father_name','address'))
                            ->save($path.$file_name);

                }
            }
            else if(stripos($service->type_name,'upi')!==false)
            {
                $file_name='upi-'.$guest_cart_service->id.date('Ymdhis').".pdf";

                $service_data_array=json_decode($guest_cart_service->service_data,true);

                $data = $service_data_array['check'];

                $upi_id=$data['UPI ID'];

                if(stripos($guest_cart_service->status,'success')!==false)
                {
                    if($guest_cart_service->check_master_id!=NULL)
                    {
                        $data = DB::table('upi_check_masters')->select('*')->where(['id'=>$guest_cart_service->check_master_id])->first();
                    }
                    else
                    {

                        $data = DB::table('upi_check_masters')->select('*')->where(['upi_id'=>$upi_id])->first();
                    }

                    $master_data = $data;

                    $pdf = PDF::loadView('guest.instantverification.pdf.upi', compact('master_data'))
                        ->save($path.$file_name); 

                    DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                        'check_master_id' => $master_data->id
                    ]);
                }
                else
                {
                    $pdf = PDF::loadView('guest.instantverification.pdf.failed.upi', compact('upi_id'))
                            ->save($path.$file_name);

                }
            }
            else if(stripos($service->type_name,'cin')!==false)
            {
                $file_name='cin-'.$guest_cart_service->id.date('Ymdhis').".pdf";

                $service_data_array=json_decode($guest_cart_service->service_data,true);

                $data = $service_data_array['check'];

                $cin=$data['CIN Number'];

                if(stripos($guest_cart_service->status,'success')!==false)
                {
                    if($guest_cart_service->check_master_id!=NULL)
                    {
                        $data = DB::table('cin_check_masters')->select('*')->where(['id'=>$guest_cart_service->check_master_id])->first();
                    }
                    else
                    {

                        $data = DB::table('cin_check_masters')->select('*')->where(['cin_number'=>$cin])->first();
                    }

                    $master_data = $data;

                    $pdf = PDF::loadView('guest.instantverification.pdf.cin', compact('master_data'))
                        ->save($path.$file_name); 

                    DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                        'check_master_id' => $master_data->id
                    ]);
                }
                else
                {
                    $pdf = PDF::loadView('guest.instantverification.pdf.failed.cin', compact('cin'))
                            ->save($path.$file_name);

                }
            }

            DB::table('guest_instant_cart_services')->where(['id'=>$guest_cart_service->id])->update([
                'file_name' => $file_name
            ]);

             // generating the service_wise zip

             $guest_cart=DB::table('guest_instant_carts')
             ->where(['giv_m_id'=>$guest_cart_service->giv_m_id])
             ->orderBy('service_id','asc')
             ->get();

             $zip_c = new \ZipArchive();

            foreach($guest_cart as $gc)
            {
                $guest_c_s=DB::table('guest_instant_cart_services')
                                ->where(['giv_c_id'=>$gc->id])
                                ->get();

                $zipname="";
                $path_zip = "";
                $services=DB::table('services')->where('id',$gc->service_id)->first();

                if($services->name=='Aadhar')
                {
                    $zipname = 'aadhar-'.date('Ymdhis').'.zip';
                    $path_zip = public_path().'/guest/reports/zip/aadhar/';
                }
                else if(stripos($services->type_name,'pan')!==false)
                {
                    $zipname = 'pan-'.date('Ymdhis').'.zip';
                    $path_zip = public_path().'/guest/reports/zip/pan/';
                }
                else if($services->name=='Voter ID')
                {
                    $zipname = 'voter_id-'.date('Ymdhis').'.zip';
                    $path_zip = public_path().'/guest/reports/zip/voterid/';
                }
                else if($services->name=='RC')
                {
                    $zipname = 'rc-'.date('Ymdhis').'.zip';
                    $path_zip = public_path().'/guest/reports/zip/rc/';
                }
                else if($services->name=='Passport')
                {
                    $zipname = 'passport-'.date('Ymdhis').'.zip';
                    $path_zip = public_path().'/guest/reports/zip/passport/';
                }
                else if($services->name=='Driving')
                {
                    $zipname = 'driving-'.date('Ymdhis').'.zip';
                    $path_zip = public_path().'/guest/reports/zip/driving/';
                }
                else if($services->name=='Bank Verification')
                {
                    $zipname = 'bank-'.date('Ymdhis').'.zip';
                    $path_zip = public_path().'/guest/reports/zip/bank/';
                }
                else if(stripos($services->name,'E-Court')!==false)
                {
                    $zipname = 'e_court-'.date('Ymdhis').'.zip';
                    $path_zip = public_path().'/guest/reports/zip/e-court/';
                }
                else if(stripos($services->type_name,'upi')!==false)
                {
                    $zipname = 'upi-'.date('Ymdhis').'.zip';
                    $path_zip = public_path().'/guest/reports/zip/upi/';
                }
                else if(stripos($services->type_name,'cin')!==false)
                {
                    $zipname = 'cin-'.date('Ymdhis').'.zip';
                    $path_zip = public_path().'/guest/reports/zip/cin/';
                }

                if(!File::exists($path_zip))
                {
                    File::makeDirectory($path_zip, $mode = 0777, true, true);
                }

                
                $zip_c->open($path_zip.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                foreach($guest_c_s as $gcs)
                {
                    $path_r = public_path()."/guest/reports/pdf/";
                    
                    $zip_c->addFile($path_r.$gcs->file_name, '/reports/'.basename($path_r.$gcs->file_name));  
                }

                $zip_c->close();

                DB::table('guest_instant_carts')->where(['id'=>$gc->id])->update([
                    'zip_name' => $zipname!=""?$zipname:NULL,
                ]);

            }

            //generating the master zip

            $zipname1="";
            $path_m=''; 
            $zipname1 = 'reports-'.date('Ymdhis').'.zip';
            $zip1 = new \ZipArchive();      
            $zip1->open(public_path().'/guest/reports/zip/'.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            if(!File::exists(public_path().'/guest/reports/zip/'))
            {
                File::makeDirectory(public_path().'/guest/reports/zip/', $mode = 0777, true, true);
            }

            $guest_cart=DB::table('guest_instant_carts')
                            ->where(['giv_m_id'=>$guest_cart_service->giv_m_id])
                            ->orderBy('service_id','asc')
                            ->get();

            foreach($guest_cart as $gc)
            {
                $services=DB::table('services')->where('id',$gc->service_id)->first();
                if($services->name=='Aadhar')
                {
                    $path_m = public_path().'/guest/reports/zip/aadhar/';
                }
                else if($gc->service_id==3)
                {
                    $path_m = public_path().'/guest/reports/zip/pan/';
                }
                else if($services->name=='Voter ID')
                {
                    $path_m = public_path().'/guest/reports/zip/voterid/';
                }
                else if($services->name=='RC')
                {
                    $path_m = public_path().'/guest/reports/zip/rc/';
                }
                else if($services->name=='Passport')
                {
                    $path_m = public_path().'/guest/reports/zip/passport/';
                }
                else if($services->name=='Driving')
                {
                    $path_m = public_path().'/guest/reports/zip/driving/';
                }
                else if($services->name=='Bank Verification')
                {
                    $path_m = public_path().'/guest/reports/zip/bank/';
                }
                else if(stripos($services->name,'E-Court')!==false)
                {
                    $path_m = public_path().'/guest/reports/zip/e-court/';
                }
                else if(stripos($services->type_name,'upi')!==false)
                {
                    $path_m = public_path().'/guest/reports/zip/upi/';
                }
                else if(stripos($services->type_name,'cin')!==false)
                {
                    $path_m = public_path().'/guest/reports/zip/cin/';
                }

                if(!File::exists($path_m))
                {
                    File::makeDirectory($path_m, $mode = 0777, true, true);
                }

                $zip1->addFile($path_m.$gc->zip_name, '/reports/'.basename($path_m.$gc->zip_name));  
            }

            $zip1->close();

            DB::table('guest_instant_masters')->where(['id'=> $guest_cart_service->giv_m_id])->update([
                'zip_name' => $zipname1!=""?$zipname1:NULL,
            ]);

            $guest_cart_service=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id])->first();

            $file = public_path()."/guest/reports/pdf/".$guest_cart_service->file_name;
            $headers = array('Content-Type: application/pdf');
            return response()->download($file, $guest_cart_service->file_name,$headers);

        }
    }

     // check id - aadhar
     public function idCheckAadhar($gcs_id,$service_id,$aadhar_number)
     {  
         $parent_id=Auth::user()->parent_id;      
         $business_id = Auth::user()->business_id;
         $user_id=Auth::user()->id;
         $master_id = NULL;
 
         $price=25;
 
         $path=public_path().'/guest/reports/pdf/';
         
         $guest_c_s=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->first();
 
         $price=$guest_c_s->price;
 
         $file_name='aadhar-'.$guest_c_s->id.date('Ymdhis').".pdf";
 
         //check first into master table
         $master_data = DB::table('aadhar_check_masters')->select('*')->where(['aadhar_number'=>$aadhar_number])->first();
         
         if($master_data !=null){
             $master_id = $master_data->id;
                 // store log
                 $check_data = [
                 'parent_id'         =>$parent_id,
                 'business_id'       =>$business_id,
                 'service_id'        =>$service_id,
                 'aadhar_number'     =>$master_data->aadhar_number,
                 'age_range'         =>$master_data->age_range,
                 'gender'            =>$master_data->gender,
                 'state'             =>$master_data->state,
                 'last_digit'        =>$master_data->last_digit,
                 'is_verified'       =>'1',
                 'is_aadhar_exist'   =>'1',
                 'used_by'           =>'guest',
                 'user_id'            => $user_id,
                 'source_reference'  =>'SystemDB',
                 'price'             =>$price,
                 'created_at'        =>date('Y-m-d H:i:s')
             ]; 
 
             DB::table('aadhar_checks')->insert($check_data);

             if($guest_c_s->file_name!=NULL)
             {
                 if(File::exists($path.$guest_c_s->file_name))
                 {
                    File::delete($path.$guest_c_s->file_name);
                 }
             }
 
             $pdf = PDF::loadView('guest.instantverification.pdf.aadhar', compact('master_data'))
                 ->save($path.$file_name); 
             
             DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                 'price' => $price,
                 'status' => 'success',
                 'file_name' => $file_name,
                 'check_master_id' => $master_id,
                 'refund_count' => 0,
                 'updated_at' => date('Y-m-d H:i:s')
             ]);
             
             
             
         }
         else{
             //check from live API
             $api_check_status = false;
             // Setup request to send json via POST
             $data = array(
                 'id_number'    => $aadhar_number,
                 'async'        => true,
             );
             $payload = json_encode($data);
             $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/aadhaar-validation/aadhaar-validation";
 
             $ch = curl_init();
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
             curl_setopt ( $ch, CURLOPT_POST, 1 );
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
                $master_data ="";
                if($array_data['data']['state']==NULL || $array_data['data']['gender']==NULL || $array_data['data']['last_digits']==NULL)
                {
                    $refund_count = 0;

                    $refund_count = $guest_c_s->refund_count + 1;

                    if($guest_c_s->file_name!=NULL)
                    {
                        if(File::exists($path.$guest_c_s->file_name))
                        {
                           File::delete($path.$guest_c_s->file_name);
                        }
                    }
                     $pdf = PDF::loadView('guest.instantverification.pdf.failed.aadhar', compact('aadhar_number'))
                     ->save($path.$file_name);
     
                     DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                         'price' => $price,
                         'status' => 'failed',
                         'file_name' => $file_name,
                         'refund_count' => $refund_count,
                         'updated_at' => date('Y-m-d H:i:s')
                     ]);
                }
                else
                {
                     //check if ID number is new then insert into DB
                    $checkIDInDB= DB::table('aadhar_check_masters')->where(['aadhar_number'=>$aadhar_number])->count();
                    if($checkIDInDB ==0)
                    {
                        $gender = NULL;
                        if($array_data['data']['gender'] == 'F'){
                            $gender = 'Female';
                        }
                        elseif($array_data['data']['gender'] == 'M')
                        {
                            $gender = 'Male';
                        }
                        elseif($array_data['data']['gender'] == 'O')
                        {
                            $gender = 'Others';
                        }
                        $data = ['aadhar_number'    =>$array_data['data']['aadhaar_number'],
                                'age_range'         =>$array_data['data']['age_range'],
                                'gender'            =>$gender,
                                'state'             =>$array_data['data']['state'],
                                'last_digit'        =>$array_data['data']['last_digits'],
                                'is_verified'       =>'1',
                                'is_aadhar_exist'   =>'1',
                                'created_at'        =>date('Y-m-d H:i:s')
                                ];
                        $master_id=DB::table('aadhar_check_masters')->insertGetId($data);
                                
                        //insert into aadhar_checks table
                        $business_data = [
                                'parent_id'         =>$parent_id,
                                'business_id'       =>$business_id,
                                'service_id'        =>$service_id,
                                'aadhar_number'     =>$array_data['data']['aadhaar_number'],
                                'age_range'         =>$array_data['data']['age_range'],
                                'gender'            =>$gender,
                                'state'             =>$array_data['data']['state'],
                                'last_digit'        =>$array_data['data']['last_digits'],
                                'is_verified'       =>'1',
                                'is_aadhar_exist'   =>'1',
                                'used_by'           =>'guest',
                                'user_id'            => $user_id,
                                'source_reference'  =>'API',
                                'price'             =>$price,
                                'created_at'        =>date('Y-m-d H:i:s')
                                ]; 
                        DB::table('aadhar_checks')->insert($business_data);
                        
                        
    
                    }
                    else
                    {
                        $master_data = DB::table('aadhar_check_masters')->select('*')->where(['aadhar_number'=>$aadhar_number])->first();
                        $master_id = $master_data->id;
                    }

                    if($guest_c_s->file_name!=NULL)
                    {
                        if(File::exists($path.$guest_c_s->file_name))
                        {
                            File::delete($path.$guest_c_s->file_name);
                        }
                    }

                    $pdf = PDF::loadView('guest.instantverification.pdf.aadhar', compact('master_data'))
                    ->save($path.$file_name); 
                
                    DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                        'price' => $price,
                        'status' => 'success',
                        'file_name' => $file_name,
                        'check_master_id' => $master_id,
                        'refund_count' => 0,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                } 
             }
             else{

                $refund_count = 0;

                $refund_count = $guest_c_s->refund_count + 1;

                if($guest_c_s->file_name!=NULL)
                {
                    if(File::exists($path.$guest_c_s->file_name))
                    {
                       File::delete($path.$guest_c_s->file_name);
                    }
                }
                 $pdf = PDF::loadView('guest.instantverification.pdf.failed.aadhar', compact('aadhar_number'))
                 ->save($path.$file_name);
 
                 DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                     'price' => $price,
                     'status' => 'failed',
                     'file_name' => $file_name,
                     'refund_count' => $refund_count,
                     'updated_at' => date('Y-m-d H:i:s')
                 ]);
             }
             
         }
          
 
     }
 
     // check id - pan
     public function idCheckPan($gcs_id,$service_id,$pan_number)
     {    
         $parent_id=Auth::user()->parent_id;    
         $business_id = Auth::user()->business_id;
         $user_id=Auth::user()->id;
         $master_id = NULL;
         $price=25;
 
         $path=public_path().'/guest/reports/pdf/';
 
         // dd($gv_id);
        
         $guest_c_s=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->first();
 
         $price=$guest_c_s->price;
 
         // dd($guest_c_s);
 
         $file_name='pan-'.$guest_c_s->id.date('Ymdhis').".pdf";
         
         //check first into master table
         $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$pan_number])->first();
         
         if($master_data !=null){
            $master_id = $master_data->id;
             //store log
             $data = [
                 'parent_id'         =>$parent_id,
                 'category'          =>$master_data->category,
                 'pan_number'        =>$master_data->pan_number,
                 'full_name'         =>$master_data->full_name,
                 'is_verified'       =>'1',
                 'is_pan_exist'      =>'1',
                 'business_id'       => $business_id,
                 'service_id'        => $service_id,
                 'source_type'       =>'SystemDb',
                 'price'             =>$price,
                 'used_by'           =>'guest',
                 'user_id'            => $user_id,
                 'created_at'=>date('Y-m-d H:i:s')
                 ];
         
             DB::table('pan_checks')->insert($data);
             
             if($guest_c_s->file_name!=NULL)
             {
                if(File::exists($path.$guest_c_s->file_name))
                {
                    File::delete($path.$guest_c_s->file_name);
                }
             }
             $pdf = PDF::loadView('guest.instantverification.pdf.pan', compact('master_data'))
             ->save($path.$file_name); 
         
             DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                 'price' => $price,
                 'status' => 'success',
                 'file_name' => $file_name,
                 'check_master_id' => $master_id,
                 'refund_count' => 0,
                 'updated_at' => date('Y-m-d H:i:s')
             ]);
 
         }
         else{
             //check from live API
             $api_check_status = false;
             // Setup request to send json via POST
             $data = array(
                 'id_number'    => $pan_number,
             );
             $payload = json_encode($data);
             $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/pan/pan";
 
             $ch = curl_init();                
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
             curl_setopt ( $ch, CURLOPT_POST, 1 );
             $authorization = "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MDcxNzI1MDIsIm5iZiI6MTYwNzE3MjUwMiwianRpIjoiZTA5YTc5MmEtMGQ5ZC00N2RjLTk1MTAtMzg4M2E3ODYxZDczIiwiZXhwIjoxOTIyNTMyNTAyLCJpZGVudGl0eSI6ImRldi50YWd3b3JsZEBhYWRoYWFyYXBpLmlvIiwiZnJlc2giOmZhbHNlLCJ0eXBlIjoiYWNjZXNzIiwidXNlcl9jbGFpbXMiOnsic2NvcGVzIjpbInJlYWQiXX19.0Ufgl7uOeTG7QVLvRR4VkRZMT06GsiGiK44jFa9-gdw"; // Prepare the authorisation token
             curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
             curl_setopt($ch, CURLOPT_URL, $apiURL);
             // Attach encoded JSON string to the POST fields
             curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
 
             $resp = curl_exec ( $ch );
             curl_close ( $ch );
             
             $array_data =  json_decode($resp,true);
             // print_r($array_data); die;
             if($array_data['success'])
             {
                 //check if ID number is new then insert into DB
                 $checkIDInDB= DB::table('pan_check_masters')->where(['pan_number'=>$pan_number])->count();
                 if($checkIDInDB ==0)
                 {
                     $data = [
                             'category'=>$array_data['data']['category'],
                             'pan_number'=>$array_data['data']['pan_number'],
                             'full_name'=>$array_data['data']['full_name'],
                             'is_verified'=>'1',
                             'is_pan_exist'=>'1',
                             'created_at'=>date('Y-m-d H:i:s')
                             ];
                     
                     DB::table('pan_check_masters')->insert($data);
                     
                     //store log
                     $data = [
                         'parent_id'         =>$parent_id,
                         'category'          =>$array_data['data']['category'],
                         'pan_number'        =>$array_data['data']['pan_number'],
                         'full_name'         =>$array_data['data']['full_name'],
                         'is_verified'       =>'1',
                         'is_pan_exist'      =>'1',
                         'business_id'       =>$business_id,
                         'service_id'        => $service_id,
                         'source_type'       =>'API',
                         'price'             =>$price,
                         'used_by'           =>'guest',
                         'user_id'            => $user_id,
                         'created_at'=>date('Y-m-d H:i:s')
                         ];
                 
                     $master_id=DB::table('pan_checks')->insertGetId($data);
                     
                     $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$pan_number])->first();
                 }
                 else
                 {
                    $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$pan_number])->first();
                    $master_id = $master_data->id;
                 }


                 if($guest_c_s->file_name!=NULL)
                 {
                    if(File::exists($path.$guest_c_s->file_name))
                    {
                        File::delete($path.$guest_c_s->file_name);
                    }
                 }
                 $pdf = PDF::loadView('guest.instantverification.pdf.pan', compact('master_data'))
                         ->save($path.$file_name); 
                     
                 DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                     'price' => $price,
                     'status' => 'success',
                     'file_name' => $file_name,
                     'check_master_id' => $master_id,
                     'refund_count' => 0,
                     'updated_at' => date('Y-m-d H:i:s')
                 ]);
 
             }else{

                $refund_count = 0;

                $refund_count = $guest_c_s->refund_count + 1;

                if($guest_c_s->file_name!=NULL)
                {
                   if(File::exists($path.$guest_c_s->file_name))
                   {
                       File::delete($path.$guest_c_s->file_name);
                   }
                }
                 $pdf = PDF::loadView('guest.instantverification.pdf.failed.pan', compact('pan_number'))
                         ->save($path.$file_name);
                 DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                     'price' => $price,
                     'status' => 'failed',
                     'file_name' => $file_name,
                     'refund_count' => $refund_count,
                     'updated_at' => date('Y-m-d H:i:s')
                 ]);
             }
             
         }
 
 
 
 
     }
 
     // check id - Voter ID
     public function idCheckVoterID($gcs_id,$service_id,$voter_id_number)
     {   
 
         $business_id=Auth::user()->business_id;
         $user_id = Auth::user()->id;
         $master_id = NULL;
         $price=50;
 
         $parent_id=Auth::user()->parent_id;
 
 
             // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
             // {
             //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
             //     $parent_id=$users->parent_id;
             // }
 
         $path=public_path().'/guest/reports/pdf/';
 
        
         $guest_c_s=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->first();
 
         $price=$guest_c_s->price;
 
         $file_name='voter_id-'.$guest_c_s->id.date('Ymdhis').".pdf";
         
         //check first into master table
         $master_data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$voter_id_number])->first();
         if($master_data !=null){
             $data = $master_data;
             $master_id = $master_data->id;
                 //store log
                 $log_data = [
                 'parent_id'         =>$parent_id,
                 'api_client_id'     =>$master_data->api_client_id,
                 'relation_type'     =>$master_data->relation_type,
                 'voter_id_number'   =>$master_data->voter_id_number,
                 'relation_name'     =>$master_data->relation_name,
                 'full_name'         =>$master_data->full_name,
                 'gender'            =>$master_data->gender,
                 'age'               =>$master_data->age,
                 'dob'               =>$master_data->dob,
                 'house_no'          =>$master_data->house_no,
                 'area'              =>$master_data->area,
                 'state'             =>$master_data->state,
                 'is_verified'       =>'1',
                 'is_voter_id_exist' =>'1',
                 'business_id'       =>$business_id,
                 'service_id'        =>$service_id,
                 'source_reference'  =>'SystemDb',
                 'price'             =>$price,
                 'used_by'           =>'guest',
                 'user_id'            => $user_id,
                 'created_at'        =>date('Y-m-d H:i:s')
                 ];
 
             DB::table('voter_id_checks')->insert($log_data);

             if($guest_c_s->file_name!=NULL)
             {
                if(File::exists($path.$guest_c_s->file_name))
                {
                    File::delete($path.$guest_c_s->file_name);
                }
             }
 
             $pdf = PDF::loadView('guest.instantverification.pdf.voter-id', compact('master_data'))
                 ->save($path.$file_name); 
             
                 DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                     'price' => $price,
                     'status' => 'success',
                     'file_name' => $file_name,
                     'check_master_id' => $master_id,
                     'refund_count' => 0,
                     'updated_at' => date('Y-m-d H:i:s')
                 ]);
         }
         else{
             //check from live API
             // Setup request to send json via POST
             $data = array(
                 'id_number'    => $voter_id_number,
             );
             $payload = json_encode($data);
             $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/voter-id/voter-id";
 
             $ch = curl_init();
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
             curl_setopt ( $ch, CURLOPT_POST, 1 );
             $authorization = "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MDcxNzI1MDIsIm5iZiI6MTYwNzE3MjUwMiwianRpIjoiZTA5YTc5MmEtMGQ5ZC00N2RjLTk1MTAtMzg4M2E3ODYxZDczIiwiZXhwIjoxOTIyNTMyNTAyLCJpZGVudGl0eSI6ImRldi50YWd3b3JsZEBhYWRoYWFyYXBpLmlvIiwiZnJlc2giOmZhbHNlLCJ0eXBlIjoiYWNjZXNzIiwidXNlcl9jbGFpbXMiOnsic2NvcGVzIjpbInJlYWQiXX19.0Ufgl7uOeTG7QVLvRR4VkRZMT06GsiGiK44jFa9-gdw"; // Prepare the authorisation token
             curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
             curl_setopt($ch, CURLOPT_URL, $apiURL);
             // Attach encoded JSON string to the POST fields
             curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
             $resp = curl_exec ( $ch );
             curl_close ( $ch );
             $array_data =  json_decode($resp,true);
             // print_r($array_data); die;
 
             if($array_data['success'])
             {
                 //check if ID number is new then insert into DB
                 $checkIDInDB= DB::table('voter_id_check_masters')->where(['voter_id_number'=>$voter_id_number])->count();
                 if($checkIDInDB ==0)
                 {
                     $gender = 'Male';
                     if($array_data['data']['gender'] == 'F'){
                         $gender = 'Female';
                     }
                     //
                     $relation_type = NULL;
                     if($array_data['data']['relation_type'] == 'M'){
                         $relation_type = 'Mother';
                     }
                     if($array_data['data']['relation_type'] == 'F'){
                         $relation_type = 'Father';
                     }
                     if($array_data['data']['relation_type'] == 'W'){
                         $relation_type = 'Wife';
                     }
                     if($array_data['data']['relation_type'] == 'H'){
                         $relation_type = 'Husband';
                     }
 
                     $data = [
                             'api_client_id'     =>$array_data['data']['client_id'],
                             'relation_type'     =>$relation_type,
                             'voter_id_number'   =>$array_data['data']['epic_no'],
                             'relation_name'     =>$array_data['data']['relation_name'],
                             'full_name'         =>$array_data['data']['name'],
                             'gender'            =>$gender,
                             'age'               =>$array_data['data']['age'],
                             'dob'               =>$array_data['data']['dob'],
                             'house_no'          =>$array_data['data']['house_no'],
                             'area'              =>$array_data['data']['area'],
                             'state'             =>$array_data['data']['state'],
                             'is_verified'       =>'1',
                             'is_voter_id_exist' =>'1',
                             'created_at'        =>date('Y-m-d H:i:s')
                             ];
                     $master_id=DB::table('voter_id_check_masters')->insertGetId($data);
 
                     //store log
                     $log_data = [
                         'parent_id'         =>$parent_id,
                         'api_client_id'     =>$array_data['data']['client_id'],
                         'relation_type'     =>$relation_type,
                         'voter_id_number'   =>$array_data['data']['epic_no'],
                         'relation_name'     =>$array_data['data']['relation_name'],
                         'full_name'         =>$array_data['data']['name'],
                         'gender'            =>$gender,
                         'age'               =>$array_data['data']['age'],
                         'dob'               =>$array_data['data']['dob'],
                         'house_no'          =>$array_data['data']['house_no'],
                         'area'              =>$array_data['data']['area'],
                         'state'             =>$array_data['data']['state'],
                         'is_verified'       =>'1',
                         'is_voter_id_exist' =>'1',
                         'business_id'       =>$business_id,
                         'service_id'        =>$service_id,
                         'source_reference'  =>'API',
                         'price'             =>$price,
                         'used_by'           =>'guest',
                         'user_id'            => $user_id,
                         'created_at'        =>date('Y-m-d H:i:s')
                         ];
 
                     DB::table('voter_id_checks')->insert($log_data);
                     
                     $master_data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$voter_id_number])->first();
                 }
                 else
                 {
                    $master_data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$voter_id_number])->first();

                    $master_id = $master_data->id;
                 }
                 
                 if($guest_c_s->file_name!=NULL)
                 {
                    if(File::exists($path.$guest_c_s->file_name))
                    {
                        File::delete($path.$guest_c_s->file_name);
                    }
                 }

                 $pdf = PDF::loadView('guest.instantverification.pdf.voter-id', compact('master_data'))
                 ->save($path.$file_name); 
             
                 DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                     'price' => $price,
                     'status' => 'success',
                     'file_name' => $file_name,
                     'check_master_id' => $master_id,
                     'refund_count' => 0,
                     'updated_at' => date('Y-m-d H:i:s')
                 ]);
 
             }else{

                $refund_count = 0;

                $refund_count = $guest_c_s->refund_count + 1;

                if($guest_c_s->file_name!=NULL)
                {
                   if(File::exists($path.$guest_c_s->file_name))
                   {
                       File::delete($path.$guest_c_s->file_name);
                   }
                }

                 $pdf = PDF::loadView('guest.instantverification.pdf.failed.voter-id', compact('voter_id_number'))
                 ->save($path.$file_name);
                 DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                     'price' => $price,
                     'status' => 'failed',
                     'file_name' => $file_name,
                     'refund_count' => $refund_count,
                     'updated_at' => date('Y-m-d H:i:s')
                 ]);
             }
             
         }
 
          
     }
 
     // check id - RC
     public function idCheckRC($gcs_id,$service_id,$rc_number)
     {        
         $business_id=Auth::user()->business_id;
         $user_id = Auth::user()->id;
         $master_id=NULL;
         $price=50;
 
         $parent_id=Auth::user()->parent_id;
 
         // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
         // {
         //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
         //     $parent_id=$users->parent_id;
         // }
         
         $path=public_path().'/guest/reports/pdf/';
 
        
 
         $guest_c_s=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->first();
 
         $price = $guest_c_s->price;
 
         $file_name='rc-'.$guest_c_s->id.date('Ymdhis').".pdf";
 
         //check first into master table
         $master_data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$rc_number])->first();
         if($master_data !=null){
             $data = $master_data;
             $master_id=$master_data->id;
             $log_data = [
                 'parent_id'         =>$parent_id,
                 'business_id'       => $business_id,
                 'service_id'        =>$service_id,
                 'source_type'       => 'SystemDb',
                 'api_client_id'     =>$master_data->api_client_id,
                 'rc_number'         =>$master_data->rc_number,
                 'registration_date' =>$master_data->registration_date,
                 'owner_name'        =>$master_data->owner_name,
                 'present_address'   =>$master_data->present_address,
                 'permanent_address'    =>$master_data->permanent_address,
                 'mobile_number'        =>$master_data->mobile_number,
                 'vehicle_category'     =>$master_data->vehicle_category,
                 'vehicle_chasis_number' =>$master_data->vehicle_chasis_number,
                 'vehicle_engine_number' =>$master_data->vehicle_engine_number,
                 'maker_description'     =>$master_data->maker_description,
                 'maker_model'           =>$master_data->maker_model,
                 'body_type'             =>$master_data->body_type,
                 'fuel_type'             =>$master_data->fuel_type,
                 'color'                 =>$master_data->color,
                 'norms_type'            =>$master_data->norms_type,
                 'fit_up_to'             =>$master_data->fit_up_to,
                 'financer'              =>$master_data->financer,
                 'insurance_company'     =>$master_data->insurance_company,
                 'insurance_policy_number'=>$master_data->insurance_policy_number,
                 'insurance_upto'         =>$master_data->insurance_upto,
                 'manufacturing_date'     =>$master_data->manufacturing_date,
                 'registered_at'          =>$master_data->registered_at,
                 'latest_by'              =>$master_data->latest_by,
                 'less_info'              =>$master_data->less_info,
                 'tax_upto'               =>$master_data->tax_upto,
                 'cubic_capacity'         =>$master_data->cubic_capacity,
                 'vehicle_gross_weight'   =>$master_data->vehicle_gross_weight,
                 'no_cylinders'           =>$master_data->no_cylinders,
                 'seat_capacity'          =>$master_data->seat_capacity,
                 'sleeper_capacity'       =>$master_data->sleeper_capacity,
                 'standing_capacity'      =>$master_data->standing_capacity,
                 'wheelbase'              =>$master_data->wheelbase,
                 'unladen_weight'         =>$master_data->unladen_weight,
                 'vehicle_category_description'         =>$master_data->vehicle_category_description,
                 'pucc_number'               =>$master_data->pucc_number,
                 'pucc_upto'                 =>$master_data->pucc_upto,
                 'masked_name'           =>$master_data->masked_name,
                 'is_verified'           =>'1',
                 'is_rc_exist'           =>'1',
                 'price'             =>$price,
                 'used_by'               =>'guest',
                 'user_id'                =>  $user_id,
                 'created_at'            =>date('Y-m-d H:i:s')
                 ];
 
                 DB::table('rc_checks')->insert($log_data);

                 if($guest_c_s->file_name!=NULL)
                 {
                    if(File::exists($path.$guest_c_s->file_name))
                    {
                        File::delete($path.$guest_c_s->file_name);
                    }
                 }
 
             $pdf = PDF::loadView('guest.instantverification.pdf.rc', compact('master_data'))
             ->save($path.$file_name); 
         
             DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                 'price' => $price,
                 'status' => 'success',
                 'file_name' => $file_name,
                 'check_master_id' => $master_id,
                 'refund_count' => 0,
                 'updated_at' => date('Y-m-d H:i:s')
             ]);
         }
         else{
             //check from live API
             // Setup request to send json via POST
             $data = array(
                 'id_number'    => $rc_number,
             );
             $payload = json_encode($data);
             $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/rc/rc";
 
             $ch = curl_init();
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
             curl_setopt ( $ch, CURLOPT_POST, 1 );
             $authorization = "Authorization: Bearer ".env('SUREPASS_PRODUCTION_TOKEN'); // Prepare the authorisation token
             curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
             curl_setopt($ch, CURLOPT_URL, $apiURL);
             // Attach encoded JSON string to the POST fields
             curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
             $resp = curl_exec ( $ch );
             curl_close ( $ch );
             $array_data =  json_decode($resp,true);
             // print_r($array_data); die;
 
             if($array_data['success'])
             {
                 //check if ID number is new then insert into DB
                 $checkIDInDB= DB::table('rc_check_masters')->where(['rc_number'=>$rc_number])->count();
                 if($checkIDInDB ==0)
                 {
                 
                     $data = [
                             'api_client_id'     =>$array_data['data']['client_id'],
                             'rc_number'         =>$array_data['data']['rc_number'],
                             'registration_date' =>$array_data['data']['registration_date'],
                             'owner_name'        =>$array_data['data']['owner_name'],
                             'present_address'   =>$array_data['data']['present_address'],
                             'permanent_address'    =>$array_data['data']['permanent_address'],
                             'mobile_number'        =>$array_data['data']['mobile_number'],
                             'vehicle_category'     =>$array_data['data']['vehicle_category'],
                             'vehicle_chasis_number' =>$array_data['data']['vehicle_chasi_number'],
                             'vehicle_engine_number' =>$array_data['data']['vehicle_engine_number'],
                             'maker_description'     =>$array_data['data']['maker_description'],
                             'maker_model'           =>$array_data['data']['maker_model'],
                             'body_type'             =>$array_data['data']['body_type'],
                             'fuel_type'             =>$array_data['data']['fuel_type'],
                             'color'                 =>$array_data['data']['color'],
                             'norms_type'            =>$array_data['data']['norms_type'],
                             'fit_up_to'             =>$array_data['data']['fit_up_to'],
                             'financer'              =>$array_data['data']['financer'],
                             'insurance_company'     =>$array_data['data']['insurance_company'],
                             'insurance_policy_number'=>$array_data['data']['insurance_policy_number'],
                             'insurance_upto'         =>$array_data['data']['insurance_upto'],
                             'manufacturing_date'     =>$array_data['data']['manufacturing_date'],
                             'registered_at'          =>$array_data['data']['registered_at'],
                             'latest_by'              =>$array_data['data']['latest_by'],
                             'less_info'              =>$array_data['data']['less_info'],
                             'tax_upto'               =>$array_data['data']['tax_upto'],
                             'cubic_capacity'         =>$array_data['data']['cubic_capacity'],
                             'vehicle_gross_weight'   =>$array_data['data']['vehicle_gross_weight'],
                             'no_cylinders'           =>$array_data['data']['no_cylinders'],
                             'seat_capacity'          =>$array_data['data']['seat_capacity'],
                             'sleeper_capacity'       =>$array_data['data']['sleeper_capacity'],
                             'standing_capacity'      =>$array_data['data']['standing_capacity'],
                             'wheelbase'              =>$array_data['data']['wheelbase'],
                             'unladen_weight'         =>$array_data['data']['unladen_weight'],
                             'vehicle_category_description'         =>$array_data['data']['vehicle_category_description'],
                             'pucc_number'               =>$array_data['data']['pucc_number'],
                             'pucc_upto'                 =>$array_data['data']['pucc_upto'],
                             'masked_name'           =>$array_data['data']['masked_name'],
                             'is_verified'           =>'1',
                             'is_rc_exist'           =>'1',
                             'created_at'            =>date('Y-m-d H:i:s')
                             ];
 
                     $master_id=DB::table('rc_check_masters')->insertGetId($data);
                     
                     $master_data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$rc_number])->first();
 
                     $log_data = [
                         'parent_id'         =>$parent_id,
                         'business_id'       => $business_id,
                         'service_id'        =>$service_id,
                         'source_type'       => 'API',
                         'api_client_id'     =>$master_data->api_client_id,
                         'rc_number'         =>$master_data->rc_number,
                         'registration_date' =>$master_data->registration_date,
                         'owner_name'        =>$master_data->owner_name,
                         'present_address'   =>$master_data->present_address,
                         'permanent_address'    =>$master_data->permanent_address,
                         'mobile_number'        =>$master_data->mobile_number,
                         'vehicle_category'     =>$master_data->vehicle_category,
                         'vehicle_chasis_number' =>$master_data->vehicle_chasis_number,
                         'vehicle_engine_number' =>$master_data->vehicle_engine_number,
                         'maker_description'     =>$master_data->maker_description,
                         'maker_model'           =>$master_data->maker_model,
                         'body_type'             =>$master_data->body_type,
                         'fuel_type'             =>$master_data->fuel_type,
                         'color'                 =>$master_data->color,
                         'norms_type'            =>$master_data->norms_type,
                         'fit_up_to'             =>$master_data->fit_up_to,
                         'financer'              =>$master_data->financer,
                         'insurance_company'     =>$master_data->insurance_company,
                         'insurance_policy_number'=>$master_data->insurance_policy_number,
                         'insurance_upto'         =>$master_data->insurance_upto,
                         'manufacturing_date'     =>$master_data->manufacturing_date,
                         'registered_at'          =>$master_data->registered_at,
                         'latest_by'              =>$master_data->latest_by,
                         'less_info'              =>$master_data->less_info,
                         'tax_upto'               =>$master_data->tax_upto,
                         'cubic_capacity'         =>$master_data->cubic_capacity,
                         'vehicle_gross_weight'   =>$master_data->vehicle_gross_weight,
                         'no_cylinders'           =>$master_data->no_cylinders,
                         'seat_capacity'          =>$master_data->seat_capacity,
                         'sleeper_capacity'       =>$master_data->sleeper_capacity,
                         'standing_capacity'      =>$master_data->standing_capacity,
                         'wheelbase'              =>$master_data->wheelbase,
                         'unladen_weight'         =>$master_data->unladen_weight,
                         'vehicle_category_description'         =>$master_data->vehicle_category_description,
                         'pucc_number'               =>$master_data->pucc_number,
                         'pucc_upto'                 =>$master_data->pucc_upto,
                         'masked_name'           =>$master_data->masked_name,
                         'is_verified'           =>'1',
                         'is_rc_exist'           =>'1',
                         'price'             =>$price,
                         'used_by'               =>'guest',
                         'user_id'                =>  $user_id,
                         'created_at'            =>date('Y-m-d H:i:s')
                         ];
     
                         DB::table('rc_checks')->insert($log_data);
                 }
                 else
                 {
                    $master_data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$rc_number])->first();
                    $master_id = $master_data->id;
                 }
                 
                 
                 if($guest_c_s->file_name!=NULL)
                 {
                    if(File::exists($path.$guest_c_s->file_name))
                    {
                        File::delete($path.$guest_c_s->file_name);
                    }
                 }
                 $pdf = PDF::loadView('guest.instantverification.pdf.rc', compact('master_data'))
                         ->save($path.$file_name); 
         
                 DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                     'price' => $price,
                     'status' => 'success',
                     'file_name' => $file_name,
                     'check_master_id' => $master_id,
                     'refund_count' => 0,
                     'updated_at' => date('Y-m-d H:i:s')
                 ]);
 
             }else{

                $refund_count = 0;

                $refund_count = $guest_c_s->refund_count + 1;
                if($guest_c_s->file_name!=NULL)
                {
                   if(File::exists($path.$guest_c_s->file_name))
                   {
                       File::delete($path.$guest_c_s->file_name);
                   }
                }
                 $pdf = PDF::loadView('guest.instantverification.pdf.failed.rc', compact('rc_number'))
                         ->save($path.$file_name);
                 DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                     'price' => $price,
                     'status' => 'failed',
                     'file_name' => $file_name,
                     'refund_count'=> $refund_count,
                     'updated_at' => date('Y-m-d H:i:s')
                 ]);
             }
             
         }
           
 
 
     }
 
     // check id - Passport
     public function idCheckPassport($gcs_id,$service_id,$file_number,$dob){  
         $business_id=Auth::user()->business_id;
         $user_id = Auth::user()->id;
         $master_id = NULL;
 
         $price=50.00;
 
         $parent_id=Auth::user()->parent_id;
 
         // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
         // {
         //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
         //     $parent_id=$users->parent_id;
         // }
         $path=public_path().'/guest/reports/pdf/';
 
        
             $guest_c_s=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->first();
 
             $price=$guest_c_s->price;
 
             $file_name='passport-'.$guest_c_s->id.date('Ymdhis').".pdf";
 
             //check first into master table
             $master_data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$file_number,'dob'=>$dob])->first();
             if($master_data !=null){
                $master_id = $master_data->id;
                 $log_data = [
                     'parent_id'         =>$parent_id,
                     'business_id'       =>$business_id,
                     'service_id'        =>$service_id,
                     'source_type'       =>'SystemDb',
                     'api_client_id'     =>$master_data->api_client_id,
                     'passport_number'   =>$master_data->passport_number,
                     'full_name'         =>$master_data->full_name,
                     'file_number'       =>$master_data->file_number,
                     'dob'               =>$master_data->dob,
                     'date_of_application'=>$master_data->date_of_application,
                     'is_verified'       =>'1',
                     'is_passport_exist' =>'1',
                     'price'             =>$price,
                     'used_by'           => 'guest',
                     'user_id'            => $user_id,
                     'created_at'        =>date('Y-m-d H:i:s')
                     ];
 
                     DB::table('passport_checks')->insert($log_data);

                     if($guest_c_s->file_name!=NULL)
                     {
                        if(File::exists($path.$guest_c_s->file_name))
                        {
                            File::delete($path.$guest_c_s->file_name);
                        }
                     }
 
                     $pdf = PDF::loadView('guest.instantverification.pdf.passport', compact('master_data'))
                     ->save($path.$file_name); 
                 
                     DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                         'price' => $price,
                         'status' => 'success',
                         'file_name' => $file_name,
                         'check_master_id' => $master_id,
                         'refund_count' => 0,
                         'updated_at' => date('Y-m-d H:i:s')
                     ]);
             }
             else{
                 //check from live API
                 // Setup request to send json via POST
                 $data = array(
                     'id_number' => $file_number,
                     'dob'       => $dob,
                 );
                 $payload = json_encode($data);
                 $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/passport/passport/passport-details";
 
                 $ch = curl_init();
                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                 curl_setopt ($ch, CURLOPT_POST, 1);
                 $authorization = "Authorization: Bearer ".env('SUREPASS_PRODUCTION_TOKEN'); // Prepare the authorisation token
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
                     $checkIDInDB= DB::table('passport_check_masters')->where(['file_number'=>$file_number,'dob'=>$dob])->count();
                     if($checkIDInDB ==0)
                     {
                         
                         $data = [
                                 'api_client_id'     =>$array_data['data']['client_id'],
                                 'passport_number'   =>$array_data['data']['passport_number'],
                                 'full_name'         =>$array_data['data']['full_name'],
                                 'file_number'       =>$array_data['data']['file_number'],
                                 'date_of_application'=>$array_data['data']['date_of_application'],
                                 'dob'               =>date('Y-m-d',strtotime($dob)),
                                 'is_verified'       =>'1',
                                 'is_passport_exist' =>'1',
                                 'created_at'        =>date('Y-m-d H:i:s')
                                 ];
 
                         $master_id=DB::table('passport_check_masters')->insertGetId($data);
                         
                         $master_data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$file_number,'dob'=>$dob])->first();
 
                         $log_data = [
                             'parent_id'         =>$parent_id,
                             'business_id'       =>$business_id,
                             'service_id'        =>$service_id,
                             'source_type'       =>'API',
                             'api_client_id'     =>$master_data->api_client_id,
                             'passport_number'   =>$master_data->passport_number,
                             'full_name'         =>$master_data->full_name,
                             'file_number'       =>$master_data->file_number,
                             'dob'       =>$master_data->dob,
                             'date_of_application'=>$master_data->date_of_application,
                             'is_verified'       =>'1',
                             'is_passport_exist' =>'1',
                             'price'             =>$price,
                             'used_by'           => 'guest',
                             'user_id'            => $user_id,
                             'created_at'        =>date('Y-m-d H:i:s')
                             ];
 
                         DB::table('passport_checks')->insert($log_data);
                     }
                     else
                     {
                         $master_data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$file_number,'dob'=>$dob])->first();
                         $master_id = $master_data->id;
                     }
                     

                     if($guest_c_s->file_name!=NULL)
                     {
                        if(File::exists($path.$guest_c_s->file_name))
                        {
                            File::delete($path.$guest_c_s->file_name);
                        }
                     }
                     $pdf = PDF::loadView('guest.instantverification.pdf.passport', compact('master_data'))
                     ->save($path.$file_name); 
                 
                     DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                         'price' => $price,
                         'status' => 'success',
                         'file_name' => $file_name,
                         'check_master_id' => $master_id,
                         'refund_count' => 0,
                         'updated_at' => date('Y-m-d H:i:s')
                     ]);
 
                 }else{
                     $refund_count = 0;

                     $refund_count = $guest_c_s->refund_count + 1;
                    if($guest_c_s->file_name!=NULL)
                    {
                       if(File::exists($path.$guest_c_s->file_name))
                       {
                           File::delete($path.$guest_c_s->file_name);
                       }
                    }
                     $pdf = PDF::loadView('guest.instantverification.pdf.failed.passport', compact('file_number','dob'))
                     ->save($path.$file_name);
                     DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                         'price' => $price,
                         'status' => 'failed',
                         'file_name' => $file_name,
                         'refund_count' => $refund_count,
                         'updated_at' => date('Y-m-d H:i:s')
                     ]);
                 }
                 
             }
            
 
 
     }
 
     // check id - DL
     public function idCheckDL($gcs_id,$service_id,$dl_number,$dob)
     {        
         $business_id=Auth::user()->business_id;
         $user_id = Auth::user()->id;
         $price=50;
         $master_id = NULL;
 
         $parent_id=Auth::user()->parent_id;
 
         // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
         // {
         //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
         //     $parent_id=$users->parent_id;
         // }
 
            $path=public_path().'/guest/reports/pdf/';
 
        
             $guest_c_s=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->first();
 
             $price=$guest_c_s->price;
 
             $file_name='dl-'.$guest_c_s->id.date('Ymdhis').".pdf";
 
             // dd($dl_number);
             
             //check first into master table
             $master_data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$dl_number,'dob'=>$dob])->first();
             
             if($master_data !=null){
                $master_id = $master_data->id;
                 $log_data = [
                     'parent_id'         =>$parent_id,
                     'business_id'       => $business_id,
                     'service_id'        => $service_id,
                     'source_type'       =>'SystemDb',
                     'api_client_id'     =>$master_data->api_client_id,
                     'dl_number'         =>$master_data->dl_number,
                     'name'              =>$master_data->name,
                     'permanent_address' =>$master_data->permanent_address,
                     'temporary_address' =>$master_data->temporary_address,
                     'permanent_zip'     =>$master_data->permanent_zip,
                     'temporary_zip'     =>$master_data->temporary_zip,
                     'state'             =>$master_data->state,
                     'citizenship'       =>$master_data->citizenship,
                     'ola_name'          =>$master_data->ola_name,
                     'ola_code'          =>$master_data->ola_code,
                     'gender'            =>$master_data->gender,
                     'father_or_husband_name' =>$master_data->father_or_husband_name,
                     'dob'               =>$master_data->dob,
                     'doe'               =>$master_data->doe,
                     'transport_doe'     =>$master_data->transport_doe,
                     'doi'               =>$master_data->doi,
                     'is_verified'       =>'1',
                     'is_rc_exist'       =>'1',
                     'price'             =>$price,
                     'used_by'           =>'guest',
                     'user_id'            =>$user_id,
                     'created_at'        =>date('Y-m-d H:i:s')
                     ];
                 
                 DB::table('dl_checks')->insert($log_data);

                 if($guest_c_s->file_name!=NULL)
                 {
                    if(File::exists($path.$guest_c_s->file_name))
                    {
                        File::delete($path.$guest_c_s->file_name);
                    }
                 }
                 $pdf = PDF::loadView('guest.instantverification.pdf.dl', compact('master_data'))
                 ->save($path.$file_name); 
             
                 DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                     'price' => $price,
                     'status' => 'success',
                     'file_name' => $file_name,
                     'check_master_id' => $master_id,
                     'refund_count' => 0,
                     'updated_at' => date('Y-m-d H:i:s')
                 ]);
             }
             else{
                 //check from live API
                 // Setup request to send json via POST
                 $data = array(
                     'id_number'    => $dl_number,
                     'dob'       => $dob,
                 );
                 $payload = json_encode($data);
                 $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/driving-license/driving-license";
 
                 $ch = curl_init();
                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                 curl_setopt ( $ch, CURLOPT_POST, 1 );
                 $authorization = "Authorization: Bearer ".env('SUREPASS_PRODUCTION_TOKEN'); // Prepare the authorisation token
                 curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
                 curl_setopt($ch, CURLOPT_URL, $apiURL);
                 // Attach encoded JSON string to the POST fields
                 curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                 $resp = curl_exec ( $ch );
                 curl_close ( $ch );
                 $array_data =  json_decode($resp,true);
 
                 // dd($array_data);
                 // print_r($array_data); die;
 
                 if($array_data['success'])
                 {
                     //check if ID number is new then insert into DB
                     $checkIDInDB= DB::table('dl_check_masters')->where(['dl_number'=>$dl_number,'dob'=>$dob])->count();
                     // dd($checkIDInDB);
                     if($checkIDInDB ==0)
                     {
                         $gender = 'Male';
                         if($array_data['data']['gender'] == 'F'){
                             $gender = 'Female';
                         }
 
                         $dl_number      = $array_data['data']['license_number'];
                         $dl_raw         = preg_replace('/[^A-Za-z0-9\ ]/', '', $dl_number);
                         $final_number   = str_replace(' ', '', $dl_raw);
 
                         //
                         // DB::enableQueryLog();
                         $data = [
                                 'api_client_id'     =>$array_data['data']['client_id'],
                                 'dl_number'         =>$final_number,
                                 'name'              =>$array_data['data']['name'],
                                 'permanent_address' =>$array_data['data']['permanent_address'],
                                 'temporary_address' =>$array_data['data']['temporary_address'],
                                 'permanent_zip'     =>$array_data['data']['permanent_zip'],
                                 'temporary_zip'     =>$array_data['data']['temporary_zip'],
                                 'state'             =>$array_data['data']['state'],
                                 'citizenship'       =>$array_data['data']['citizenship'],
                                 'ola_name'          =>$array_data['data']['ola_name'],
                                 'ola_code'          =>$array_data['data']['ola_code'],
                                 'gender'            =>$gender,
                                 'father_or_husband_name' =>$array_data['data']['father_or_husband_name'],
                                 'dob'               =>$array_data['data']['dob'],
                                 'doe'               =>$array_data['data']['doe'],
                                 'transport_doe'     =>$array_data['data']['transport_doe'],
                                 'doi'               =>$array_data['data']['doi'],
                                 'is_verified'       =>'1',
                                 'is_rc_exist'       =>'1',
                                 'created_at'        =>date('Y-m-d H:i:s')
                                 ];
                             
                             $master_id=DB::table('dl_check_masters')->insertGetId($data);
 
                             // dd(DB::getQueryLog());
                         
                         $master_data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$dl_number,'dob'=>$dob])->first();
 
                         // dd($master_data);
 
                         $log_data = [
                             'parent_id'         =>$parent_id,
                             'business_id'       => $business_id,
                             'service_id'        =>$service_id,
                             'source_type'       =>'API',
                             'api_client_id'     =>$master_data->api_client_id,
                             'dl_number'         =>$master_data->dl_number,
                             'name'              =>$master_data->name,
                             'permanent_address' =>$master_data->permanent_address,
                             'temporary_address' =>$master_data->temporary_address,
                             'permanent_zip'     =>$master_data->permanent_zip,
                             'temporary_zip'     =>$master_data->temporary_zip,
                             'state'             =>$master_data->state,
                             'citizenship'       =>$master_data->citizenship,
                             'ola_name'          =>$master_data->ola_name,
                             'ola_code'          =>$master_data->ola_code,
                             'gender'            =>$master_data->gender,
                             'father_or_husband_name' =>$master_data->father_or_husband_name,
                             'dob'               =>$master_data->dob,
                             'doe'               =>$master_data->doe,
                             'transport_doe'     =>$master_data->transport_doe,
                             'doi'               =>$master_data->doi,
                             'is_verified'       =>'1',
                             'is_rc_exist'       =>'1',
                             'price'             =>$price,
                             'used_by'           =>'guest',
                             'user_id'            =>$user_id,
                             'created_at'        =>date('Y-m-d H:i:s')
                             ];
                         
                         DB::table('dl_checks')->insert($log_data);
                     }
                     else
                     {
                        $master_data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$dl_number,'dob'=>$dob])->first();
                        $master_id = $master_data->id; 
                     }
                     
                     if($guest_c_s->file_name!=NULL)
                     {
                        if(File::exists($path.$guest_c_s->file_name))
                        {
                            File::delete($path.$guest_c_s->file_name);
                        }
                     }

                     $pdf = PDF::loadView('guest.instantverification.pdf.dl', compact('master_data'))
                             ->save($path.$file_name); 
                         
                     DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                         'price' => $price,
                         'status' => 'success',
                         'file_name' => $file_name,
                         'check_master_id' => $master_id,
                         'refund_count' => 0,
                         'updated_at' => date('Y-m-d H:i:s')
                     ]);
 
                 }else{

                     $refund_count = 0;

                     $refund_count = $guest_c_s->refund_count + 1;

                     $pdf = PDF::loadView('guest.instantverification.pdf.failed.dl', compact('dl_number'))
                     ->save($path.$file_name);
                     DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                         'price' => $price,
                         'status' => 'failed',
                         'file_name' => $file_name,
                         'refund_count' => $refund_count,
                         'updated_at' => date('Y-m-d H:i:s')
                     ]);
                 }
                 
             }
 
         
 
 
     }
 
     // check id - bank
     public function idCheckBankAccount($gcs_id,$service_id,$account_number,$ifsc_code)
     {    
         $business_id=Auth::user()->business_id;
         $user_id = Auth::user()->id;
         $price=50;
         $master_id = NULL;
         $parent_id=Auth::user()->parent_id;
 
         // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
         // {
         //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
         //     $parent_id=$users->parent_id;
         // }
         $path=public_path().'/guest/reports/pdf/';
 
        
         $guest_c_s=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->first();
 
         $price=$guest_c_s->price;
 
         $file_name='bank-'.$guest_c_s->id.date('Ymdhis').".pdf";
 
         //check first into master table
         $master_data = DB::table('bank_account_check_masters')->select('*')->where(['account_number'=>$account_number])->first();
         if($master_data !=null){
             
            $master_id = $master_data->id;
             $log_data = [
                 'parent_id'         =>$parent_id,
                 'business_id'       =>$business_id,
                 'service_id'            => $service_id,
                 'source_type'       =>'SystemDb',
                 'api_client_id'     =>$master_data->api_client_id,
                 'account_number'    =>$master_data->account_number,
                 'full_name'         =>$master_data->full_name,
                 'ifsc_code'         =>$master_data->ifsc_code,
                 'is_verified'       =>'1',
                 'is_account_exist' =>'1',
                 'price'             =>$price,
                 'used_by'           =>'guest',
                 'user_id'            =>$user_id,
                 'created_at'        =>date('Y-m-d H:i:s')
                 ];
 
             DB::table('bank_account_checks')->insert($log_data);

             if($guest_c_s->file_name!=NULL)
             {
                if(File::exists($path.$guest_c_s->file_name))
                {
                    File::delete($path.$guest_c_s->file_name);
                }
             }
 
             $pdf = PDF::loadView('guest.instantverification.pdf.bank-verification', compact('master_data'))
                     ->save($path.$file_name); 
         
             DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                 'price' => $price,
                 'status' => 'success',
                 'file_name' => $file_name,
                 'check_master_id' => $master_id,
                 'refund_count' => 0,
                 'updated_at' => date('Y-m-d H:i:s')
             ]);
         }
         else{
             //check from live API
             // Setup request to send json via POST
             $data = array(
                 'id_number' => $account_number,
                 'ifsc'      => $ifsc_code,
             );
             $payload = json_encode($data);
             $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/bank-verification/";
 
             $ch = curl_init();
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
             curl_setopt ($ch, CURLOPT_POST, 1);
             $authorization = "Authorization: Bearer ".env('SUREPASS_PRODUCTION_TOKEN'); // Prepare the authorisation token
             curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
             curl_setopt($ch, CURLOPT_URL, $apiURL);
             // Attach encoded JSON string to the POST fields
             curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
             $resp = curl_exec ( $ch );
             curl_close ( $ch );
             $array_data =  json_decode($resp,true);
             // var_dump($resp); die;
             if($array_data['success'])
             {
                 //check if ID number is new then insert into DB
                 $checkIDInDB= DB::table('bank_account_check_masters')->where(['account_number'=>$account_number])->count();
                 if($checkIDInDB ==0)
                 {
                     
                     $data = [
                             'api_client_id'     =>$array_data['data']['client_id'],
                             'account_number'    =>$account_number,
                             'full_name'         =>$array_data['data']['full_name'],
                             'ifsc_code'         =>$ifsc_code,
                             'is_verified'       =>'1',
                             'is_account_exist' =>'1',
                             'created_at'        =>date('Y-m-d H:i:s')
                             ];
 
                     $master_id=DB::table('bank_account_check_masters')->insertGetId($data);
                     
                     $master_data = DB::table('bank_account_check_masters')->select('*')->where(['account_number'=>$account_number])->first();
 
                     $log_data = [
                         'parent_id'         =>$parent_id,
                         'business_id'       =>$business_id,
                         'service_id'         => $service_id,
                         'source_type'       =>'API',
                         'api_client_id'     =>$master_data->api_client_id,
                         'account_number'    =>$master_data->account_number,
                         'full_name'         =>$master_data->full_name,
                         'ifsc_code'         =>$master_data->ifsc_code,
                         'is_verified'       =>'1',
                         'is_account_exist' =>'1',
                         'price'             =>$price,
                         'used_by'           =>'guest',
                         'user_id'            =>$user_id,
                         'created_at'        =>date('Y-m-d H:i:s')
                         ];
     
                     DB::table('bank_account_checks')->insert($log_data);
                 }
                 else
                 {
                    $master_data = DB::table('bank_account_check_masters')->select('*')->where(['account_number'=>$account_number])->first();
                    $master_id = $master_data->id;
                 }
                 
                 if($guest_c_s->file_name!=NULL)
                 {
                    if(File::exists($path.$guest_c_s->file_name))
                    {
                        File::delete($path.$guest_c_s->file_name);
                    }
                 }
                 $pdf = PDF::loadView('guest.instantverification.pdf.bank-verification', compact('master_data'))
                         ->save($path.$file_name); 
                     
                 DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                     'price' => $price,
                     'status' => 'success',
                     'file_name' => $file_name,
                     'check_master_id' => $master_id,
                     'refund_count' => 0,
                     'updated_at' => date('Y-m-d H:i:s')
                 ]);
 
             }else{

                $refund_count = 0;

                $refund_count = $guest_c_s->refund_count + 1;

                if($guest_c_s->file_name!=NULL)
                {
                   if(File::exists($path.$guest_c_s->file_name))
                   {
                       File::delete($path.$guest_c_s->file_name);
                   }
                }
                 $pdf = PDF::loadView('guest.instantverification.pdf.failed.bank-verification', compact('account_number','ifsc_code'))
                         ->save($path.$file_name);
                 DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                     'price' => $price,
                     'status' => 'failed',
                     'file_name' => $file_name,
                     'refund_count' => $refund_count,
                     'updated_at' => date('Y-m-d H:i:s')
                 ]);
             }
             
         }
             
 
     }

    // check id - ecourt
    public function idCheckECourt($gcs_id,$service_id,$name,$father_name,$address)
    {    
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $price=50;

        $parent_id=Auth::user()->parent_id;

        // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        // {
        //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
        //     $parent_id=$users->parent_id;
        // }
        $path=public_path().'/guest/reports/pdf/';

        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

    
        $guest_c_s=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->first();

        $price=$guest_c_s->price;

        $file_name='e_court-'.$guest_c_s->id.date('Ymdhis').".pdf";
        

            //check from live API
            // Setup request to send json via POST
            $data = array(
                'name' => $name,
                'fatherName' => $father_name,
                'address' => $address
            );
            $payload = json_encode($data);
            $apiURL = "https://api.springscan.springverify.com/criminal/searchDirect";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
            curl_setopt ($ch, CURLOPT_POST, 1);
            $token_key = 'tokenKey: '.env('SPRING_TOKEN_KEY');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $token_key)); // Inject the token into the header
            curl_setopt($ch, CURLOPT_URL, $apiURL);
            // Attach encoded JSON string to the POST fields
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            $resp = curl_exec ( $ch );
            $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close ( $ch );
            $array_data =  json_decode($resp,true);

            // dd($array_data);
            // var_dump($resp); die;
            if($response_code==200)
            {
                $score_status = 0;

                // Check where any case report score is greater than or equal to 90%
                // if(count($array_data['reports'])>0)
                // {
                //     foreach($array_data['reports'] as $key => $value)
                //     {
                //         if($value['score'] >= 90)
                //         {
                //             $score_status = 1;
                //         }
                //     }
                // }

                // if($score_status == 1)
                // {
                    $master_data_id = DB::table('e_court_check_masters')->insertGetId([
                        'name' => $name,
                        'father_name' =>$father_name,
                        'address' => $address,
                        'created_at' =>date('Y-m-d H:i:s')
                    ]);
    
                    if(count($array_data['reports'])>0)
                    {
                        foreach($array_data['reports'] as $key => $value)
                        {
                            // if($value['score'] >= 90)
                            // {
                                DB::table('e_court_check_master_items')->insert([
                                    'e_court_master_id' => $master_data_id,
                                    'name_as_per_court_record' => $value['name'],
                                    'case_id' => $value['case_no'],
                                    'detail_link' => $value['link'],
                                    'score' => $value['score']
                                ]);
                            // }
                        }
                    }
    
                    $check_data_id = DB::table('e_court_checks')->insertGetId([
                        'parent_id' => $parent_id,
                        'business_id' => $business_id,
                        'service_id' => $service_id,
                        'source_type' =>  'API',
                        'name' => $name,
                        'father_name' =>$father_name,
                        'address' => $address,
                        'price' => $price,
                        'user_id' => $user_id,
                        'user_type' => 'guest',
                        'created_at' =>date('Y-m-d H:i:s')
                    ]);
                    
                    if(count($array_data['reports'])>0)
                    {
                        foreach($array_data['reports'] as $key => $value)
                        {
                            // if($value['score'] >= 90)
                            // {
                                DB::table('e_court_check_items')->insert([
                                    'e_court_check_id' => $check_data_id,
                                    'parent_id' => $parent_id,
                                    'business_id' => $business_id,
                                    'service_id' => $service_id,
                                    'name_as_per_court_record' => $value['name'],
                                    'case_id' => $value['case_no'],
                                    'detail_link' => $value['link'],
                                    'score' => $value['score'],
                                    'user_id' => $user_id,
                                    'user_type' => 'guest',
                                    'created_at' =>date('Y-m-d H:i:s')
                                ]);
                            // }
                        }
                    }
    
                    $master_data = DB::table('e_court_check_masters')->where(['id'=>$master_data_id])->first();
    
                    $pdf = PDF::loadView('guest.instantverification.pdf.e-court', compact('master_data'))
                            ->save($path.$file_name); 
                        
                    DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                        'price' => $price,
                        'status' => 'success',
                        'file_name' => $file_name,
                        'check_master_id' => $master_data_id,
                        'refund_count' => 0,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                // }
                // else
                // {
                //     $refund_count = 0;

                //     $refund_count = $guest_c_s->refund_count + 1;

                //     $pdf = PDF::loadView('guest.instantverification.pdf.failed.e-court', compact('name','father_name','address'))
                //             ->save($path.$file_name);
                //     DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                //         'price' => $price,
                //         'status' => 'failed',
                //         'file_name' => $file_name,
                //         'refund_count' => $refund_count,
                //         'updated_at' => date('Y-m-d H:i:s')
                //     ]);
                // }
            

            }else{

                $refund_count = 0;

                $refund_count = $guest_c_s->refund_count + 1;

                $pdf = PDF::loadView('guest.instantverification.pdf.failed.e-court', compact('name','father_name','address'))
                        ->save($path.$file_name);
                DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'failed',
                    'file_name' => $file_name,
                    'refund_count' => $refund_count,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
        
            

    }

    // check id - upi
     public function idCheckUPI($gcs_id,$service_id,$upi_id)
     {    
         $business_id=Auth::user()->business_id;
         $user_id = Auth::user()->id;
          $master_id = NULL;
         $price=50;
         
 
         $parent_id=Auth::user()->parent_id;
 
        //  if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        //  {
        //      $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
        //      $parent_id=$users->parent_id;
        //  }

        $path=public_path().'/guest/reports/pdf/';

        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        $guest_c_s=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->first();

        $price=$guest_c_s->price;

        $file_name='upi-'.$guest_c_s->id.date('Ymdhis').".pdf";

        //check from live API
        // Setup request to send json via GET
        // $data = array(
        //     'vpa' => $upi_id,
        // );
        // $payload = json_encode($data);
        $apiURL = "https://api.springscan.springverify.com/v2/user/person/validation/upiID/6156ac22899fc7001815b42a?vpa=".$upi_id;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
        // curl_setopt ($ch, CURLOPT_POST, 0);
        $token_key = 'tokenKey: '.env('SPRING_TOKEN_KEY');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $token_key)); // Inject the token into the header
        curl_setopt($ch, CURLOPT_URL, $apiURL);
        // Attach encoded JSON string to the POST fields
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $resp = curl_exec ( $ch );
        $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ( $ch );
        $array_data =  json_decode($resp,true);
        // var_dump($resp); die;
        // dd(env('SPRING_TOKEN_KEY'));
        if($response_code==200)
        {
            $data = [
                'upi_id'     =>$upi_id,
                'name'    =>$array_data['db_output']['name'],
                'created_at'        =>date('Y-m-d H:i:s')
                ];

            $master_data_id=DB::table('upi_check_masters')->insertGetId($data);

            $log_data = [
                'parent_id'         =>$parent_id,
                'business_id'       =>$business_id,
                'service_id'         => $service_id,
                'source_type'       =>'API',
                'upi_id'            =>$upi_id,
                'name'              =>$array_data['db_output']['name'],
                'is_verified'       =>'1',
                'price'             =>$price,
                'user_type'           =>'guest',
                'user_id'            =>$user_id,
                'created_at'        =>date('Y-m-d H:i:s')
                ];

            DB::table('upi_checks')->insert($log_data);

            $master_data = DB::table('upi_check_masters')->where(['id'=>$master_data_id])->first();
            $master_id=$master_data->id;
    
            $pdf = PDF::loadView('guest.instantverification.pdf.upi', compact('master_data'))
                    ->save($path.$file_name); 
                
            DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                'price' => $price,
                'status' => 'success',
                'file_name' => $file_name,
                'check_master_id' => $master_id,
                'refund_count' => 0,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            

        }else{

            $refund_count = 0;

            $refund_count = $guest_c_s->refund_count + 1;

            $pdf = PDF::loadView('guest.instantverification.pdf.failed.upi', compact('upi_id'))
            ->save($path.$file_name);
            DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                'price' => $price,
                'status' => 'failed',
                'file_name' => $file_name,
                'refund_count' => $refund_count,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
         
             
 
     }

    // check id - cin
    public function idCheckCIN($gcs_id,$service_id,$cin)
    {    
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;

        $price=50;
        $master_id = NULL;

        $parent_id=Auth::user()->parent_id;

            //  if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
            //  {
            //      $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
            //      $parent_id=$users->parent_id;
            //  }

            $path=public_path().'/guest/reports/pdf/';

            if(!File::exists($path))
            {
                File::makeDirectory($path, $mode = 0777, true, true);
            }

            $cin = NULL;

            $guest_c_s=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->first();

            $price=$guest_c_s->price;

            $file_name='cin-'.$guest_c_s->id.date('Ymdhis').".pdf";
            
            // $service_data_array=json_decode($guest_c_s->service_data,true);

            // $data = $service_data_array['check'];

            // $cin=$data['CIN Number'];

            //check from live API
            // Setup request to send json via GET
            // $data = array(
            //     'vpa' => $upi_id,
            // );
            // $payload = json_encode($data);

            $payload = '{
                "docType": "ind_mca",
                "personId": "6156ac22899fc7001815b42a",
                "success_parameters": [
                    "cin_number"
                ],
                "manual_input": {
                    "cin_number": "'.$cin.'"
                }
            }';

            $apiURL = "https://api.springscan.springverify.com/v4/databaseCheck";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
            curl_setopt ($ch, CURLOPT_POST, 1);
            $token_key = 'tokenKey: '.env('SPRING_TOKEN_KEY');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $token_key)); // Inject the token into the header
            curl_setopt($ch, CURLOPT_URL, $apiURL);
            // Attach encoded JSON string to the POST fields
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            $resp = curl_exec ( $ch );
            $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close ( $ch );
            $array_data =  json_decode($resp,true);
            // var_dump($resp); die;
            // dd(env('SPRING_TOKEN_KEY'));
            if($response_code==200)
            {
                $data = [
                    'cin_number'     =>$cin,
                    'registration_number'    =>$array_data['output']['source']['registration_number'],
                    'company_name'    =>$array_data['output']['source']['company_name'],
                    'registered_address'    =>$array_data['output']['source']['registered_address'],
                    'date_of_incorporation'    =>$array_data['output']['source']['date_of_incorporation']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_incorporation'])) : NULL,
                    'email_id'    =>$array_data['output']['source']['email_id'],
                    'paid_up_capital_in_rupees'    =>$array_data['output']['source']['paid_up_capital_in_rupees'],
                    'authorised_capital'    =>$array_data['output']['source']['authorised_capital'],
                    'company_category'    =>$array_data['output']['source']['company_category'],
                    'company_subcategory'    =>$array_data['output']['source']['company_subcategory'],
                    'company_class'    =>$array_data['output']['source']['company_class'],
                    'whether_company_is_listed'    =>$array_data['output']['source']['whether_company_is_listed'],
                    'company_efilling_status'    =>$array_data['output']['source']['company_efilling_status'],
                    'date_of_last_AGM'    =>$array_data['output']['source']['date_of_last_AGM']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_last_AGM'])) : NULL,
                    'date_of_balance_sheet'    =>$array_data['output']['source']['date_of_balance_sheet']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_balance_sheet'])) : NULL,
                    'another_maintained_address'    =>$array_data['output']['source']['another_maintained_address'],
                    'directors'    => $array_data['output']['source']['directors']!=NULL && count($array_data['output']['source']['directors']) > 0 ? json_encode($array_data['output']['source']['directors']) : NULL,
                    'created_at'        =>date('Y-m-d H:i:s')
                    ];

                $master_data_id=DB::table('cin_check_masters')->insertGetId($data);

                $log_data = [
                    'parent_id'         =>$parent_id,
                    'business_id'       =>$business_id,
                    'service_id'         => $service_id,
                    'source_type'       =>'API',
                    'cin_number'     =>$cin,
                    'registration_number'    =>$array_data['output']['source']['registration_number'],
                    'company_name'    =>$array_data['output']['source']['company_name'],
                    'registered_address'    =>$array_data['output']['source']['registered_address'],
                    'date_of_incorporation'    =>$array_data['output']['source']['date_of_incorporation']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_incorporation'])) : NULL,
                    'email_id'    =>$array_data['output']['source']['email_id'],
                    'paid_up_capital_in_rupees'    =>$array_data['output']['source']['paid_up_capital_in_rupees'],
                    'authorised_capital'    =>$array_data['output']['source']['authorised_capital'],
                    'company_category'    =>$array_data['output']['source']['company_category'],
                    'company_subcategory'    =>$array_data['output']['source']['company_subcategory'],
                    'company_class'    =>$array_data['output']['source']['company_class'],
                    'whether_company_is_listed'    =>$array_data['output']['source']['whether_company_is_listed'],
                    'company_efilling_status'    =>$array_data['output']['source']['company_efilling_status'],
                    'date_of_last_AGM'    =>$array_data['output']['source']['date_of_last_AGM']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_last_AGM'])) : NULL,
                    'date_of_balance_sheet'    =>$array_data['output']['source']['date_of_balance_sheet']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_balance_sheet'])) : NULL,
                    'another_maintained_address'    =>$array_data['output']['source']['another_maintained_address'],
                    'directors'    => $array_data['output']['source']['directors']!=NULL && count($array_data['output']['source']['directors']) > 0 ? json_encode($array_data['output']['source']['directors']) : NULL,
                    'is_verified'       =>'1',
                    'price'             =>$price,
                    'user_type'           =>'guest',
                    'user_id'            =>$user_id,
                    'created_at'        =>date('Y-m-d H:i:s')
                    ];

                DB::table('cin_checks')->insert($log_data);

                $master_data = DB::table('cin_check_masters')->where(['id'=>$master_data_id])->first();
                $master_id=$master_data->id;

                $pdf = PDF::loadView('guest.instantverification.pdf.cin', compact('master_data'))
                        ->save($path.$file_name); 
                    
                DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'success',
                    'check_master_id' => $master_id,
                    'file_name' => $file_name,
                    'refund_count' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                

            }else{

                $refund_count = 0;

                $refund_count = $guest_c_s->refund_count + 1;

                $pdf = PDF::loadView('guest.instantverification.pdf.failed.cin', compact('cin'))
                ->save($path.$file_name);
                DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'failed',
                    'file_name' => $file_name,
                    'refund_count' => $refund_count,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        
            

    }

    // public function downloadPDF(Request $request)
    // {
    //     $service_id=base64_decode($request->service_id);
    //     $gv_id=base64_decode($request->gv_id);

    //     // dd($gv_id);

    //     // $file = public_path()."/downloads/info.pdf";
    //     // $headers = array('Content-Type: application/pdf',);
    //     // return Response::download($file, 'info.pdf',$headers);

    // }

   
}
