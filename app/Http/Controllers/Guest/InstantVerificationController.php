<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Razorpay\Api\Api;
use Illuminate\Support\Str;
use PDF;
use ZipArchive;
use Illuminate\Support\Facades\File;
use App\Traits\MSGWhatsappTrait;
use Illuminate\Support\Facades\Config;
class InstantVerificationController extends Controller
{
    //

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
        $services= DB::table('services as s')
                    ->select('s.*')
                    ->join('service_form_inputs as si','s.id','=','si.service_id')
                    ->where(['s.verification_type'=>'Auto','s.status'=>1,'business_id'=>NULL])
                    ->whereNotIn('s.name',['E-Court','GSTIN','Telecom','Covid-19 Certificate'])
                    ->whereNotIn('s.type_name',['court_check_v1'])
                    ->groupBy('si.service_id')
                    ->get();
        
        $guest_master=DB::table('guest_instant_masters')
                        ->where(['business_id'=>$business_id,'is_payment_done'=>0])
                        ->first();
                        //dd($guest_master);
       
        return view('guest.instantverification.index',compact('services','guest_master'));
    }

    public function store(Request $request)
    {
        $parent_id=Auth::user()->parent_id;
        $business_id=Auth::user()->business_id;

        $user_id=Auth::user()->id;
        $rules=[
            'services' => 'required|array|min:1'
        ];

        $custom=[
            'services.required' => 'Select Atleast One Service !!'
        ];

        $validator = Validator::make($request->all(), $rules, $custom);
        
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Validation
        if(count($request->services)>0)
        {
            foreach($request->services as $service)
            {
                $rules=[
                    'count-'.$service => 'required|integer|min:1|max:1000'
                ];
        
                $custom=[
                    'count-'.$service.'.required' => 'No. of Verification is Required',
                    'count-'.$service.'.integer' => 'No. of Verification Should be Numeric',
                    'count-'.$service.'.min' => 'No. of Verification Should be Atleast 1',
                    'count-'.$service.'.max' => 'No. of Verification Should Can Select Maximum 1000',
                ];

                $validator = Validator::make($request->all(), $rules, $custom);
        
                if ($validator->fails()){
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ]);
                }
            }
        }

        // dd(1);

        DB::beginTransaction();
        try
        {
            $sub_total=0;

            $tax = 18;

            if(count($request->services)>0)
            {
                foreach($request->services as $service)
                {
                    $price = 100;

                    if($service==2 || $service==3)
                    {
                        $price = 75;
                    }

                    $g_check_price = DB::table('guest_instant_check_prices')->where(['business_id'=>$parent_id,'service_id'=>$service])->first();

                    if($g_check_price!=NULL)
                    {
                        $price = $g_check_price->price;
                    }

                    $sub_total = $sub_total + ($price * $request->input('count-'.$service));
                }
            }

            $user=DB::table('users')->where('id',$business_id)->first();
        
            $guest_master=DB::table('guest_instant_masters')
                            ->where(['business_id'=>$business_id,'is_payment_done'=>0,'status'=>NULL])
                            ->first();

            if($guest_master!=NULL)
            {
                $data = [
                    'user_id'       => $user_id,
                    'currency'      => 'INR',
                    'sub_total'     => $sub_total,
                    'total_price'   => number_format($sub_total + (($sub_total * $tax) / 100),2),
                    'promo_code_id' => NULL,
                    'promo_code_title' => NULL,
                    'tax' => $tax,
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                DB::table('guest_instant_masters')->where(['business_id'=>$business_id,'is_payment_done'=>0,'status'=>NULL])->update($data);

                $guest_master_id=$guest_master->id;
            }
            else
            {
                $data=[
                    'parent_id' => $parent_id,
                    'business_id' => $business_id,
                    'user_id' => $user_id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'contactNumber' => $user->phone,
                    'currency'      => 'INR',
                    'sub_total'     => $sub_total,
                    'total_price'   => number_format($sub_total + (($sub_total * $tax) / 100),2),
                    'promo_code_id' => NULL,
                    'promo_code_title' => NULL,
                    'tax' => $tax,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $guest_master_id=DB::table('guest_instant_masters')->insertGetId($data);
            }

            if(count($request->services)>0)
            {
                DB::table('guest_instant_carts')
                    ->where(['giv_m_id'=>$guest_master_id])
                    ->whereNotIn('service_id',$request->services)
                    ->delete();

                DB::table('guest_instant_cart_services')
                    ->where(['giv_m_id'=>$guest_master_id])
                    ->whereNull('service_data')
                    ->whereNotIn('service_id',$request->services)
                    ->delete();

                foreach($request->services as $service)
                {
                    $no_of_verification=$request->input('count-'.$service);

                    $g_check_price = DB::table('guest_instant_check_prices')->where(['business_id'=>$parent_id,'service_id'=>$service])->first();

                    $total_check_price = 0;

                    $price = 100;

                    if($service==2 || $service==3)
                    {
                        $price = 75;
                    }

                    if($g_check_price!=NULL)
                    {
                        $price = $g_check_price->price;
                    }

                    $total_check_price = $total_check_price + ($no_of_verification * $price);

                    $guest_cart=DB::table('guest_instant_carts')
                                ->where(['giv_m_id'=>$guest_master_id,'service_id'=>$service])
                                ->first();

                    if($guest_cart!=NULL)
                    {
                        $data=[
                            'service_id' => $service,
                            'number_of_verification' => $no_of_verification,
                            'currency'      => 'INR',
                            'sub_total'     => $total_check_price,
                            'total_price'   => $total_check_price,
                            'updated_at'    => date('Y-m-d H:i:s'),
            
                        ];

                        DB::table('guest_instant_carts')->where(['giv_m_id'=>$guest_master_id,'service_id'=>$service])->update($data);

                        $guest_cart_id=$guest_cart->id;
                    }
                    else
                    {

                        $data=[
                            'parent_id' => $parent_id,
                            'business_id' => $business_id,
                            'user_id' => $user_id,
                            'giv_m_id' => $guest_master_id,
                            'service_id' => $service,
                            'number_of_verification' => $no_of_verification,
                            'currency'      => 'INR',
                            'sub_total'     => $total_check_price,
                            'total_price'   => $total_check_price,
                            'created_at'    => date('Y-m-d H:i:s'),
            
                        ];
            
                        $guest_cart_id=DB::table('guest_instant_carts')->insertGetId($data);

                    }

                    DB::table('guest_instant_cart_services')
                            ->where(['giv_m_id'=>$guest_master_id,'giv_c_id'=>$guest_cart_id,'service_id'=>$service])
                            ->whereNull('service_data')
                            ->delete();                           

                    $guest_cart_services=DB::table('guest_instant_cart_services')
                                        ->where(['giv_m_id'=>$guest_master_id,'giv_c_id'=>$guest_cart_id,'service_id'=>$service])
                                        ->get();
                    
                    if(count($guest_cart_services)>0)
                    {
                        $guest_cart_service_data=DB::table('guest_instant_cart_services')
                                                ->where(['giv_m_id'=>$guest_master_id,'giv_c_id'=>$guest_cart_id,'service_id'=>$service])
                                                ->whereNotNull('service_data')
                                                ->get();
                        
                        if(count($guest_cart_service_data)>0)
                        {
                            $n = count($guest_cart_service_data);

                            for($i=$n; $i < $no_of_verification; $i++)
                            {
                                $j = $i+1;
    
                                $data=[
                                    'parent_id' => $parent_id,
                                    'business_id' => $business_id,
                                    'user_id' => $user_id,
                                    'giv_m_id' => $guest_master_id,
                                    'giv_c_id' => $guest_cart_id,
                                    'service_id' => $service,
                                    'check_item_number' => $j,
                                    'price' => $price,
                                    'created_at'    => date('Y-m-d H:i:s'),
                                ];
    
                                DB::table('guest_instant_cart_services')->insert($data);
                            }
                        }
                        else
                        {
                            for($i=0; $i < $no_of_verification; $i++)
                            {
                                $j = $i+1;
    
                                $data=[
                                    'parent_id' => $parent_id,
                                    'business_id' => $business_id,
                                    'user_id' => $user_id,
                                    'giv_m_id' => $guest_master_id,
                                    'giv_c_id' => $guest_cart_id,
                                    'service_id' => $service,
                                    'check_item_number' => $j,
                                    'price' => $price,
                                    'created_at'    => date('Y-m-d H:i:s'),
                                ];
    
                                DB::table('guest_instant_cart_services')->insert($data);
                            }
                        }
                    }
                    else
                    {
                        for($i=0; $i < $no_of_verification; $i++)
                        {
                            $j = $i+1;

                            $data=[
                                'parent_id' => $parent_id,
                                'business_id' => $business_id,
                                'user_id' => $user_id,
                                'giv_m_id' => $guest_master_id,
                                'giv_c_id' => $guest_cart_id,
                                'service_id' => $service,
                                'check_item_number' => $j,
                                'price' => $price,
                                'created_at'    => date('Y-m-d H:i:s'),
                            ];

                            DB::table('guest_instant_cart_services')->insert($data);
                        }
                    }


                }
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'guest_master_id' => Crypt::encryptString($guest_master_id),
            ]);

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;

        }

    }

    public function addCartStore(Request $request)
    {
        $service_id =  $request->service_id;

        // dd($request->services);

        $parent_id=Auth::user()->parent_id;
        $business_id=Auth::user()->business_id;

        $final_total_price = 0;

        $user_id=Auth::user()->id;
        
        $rules=[
            // 'services' => 'required|array|min:1',
            'count-'.$service_id => 'required|integer|min:1|max:1000'
        ];

        $custom=[
            // 'services.required' => 'Select Atleast One Service !!',
            'count-'.$service_id.'.required' => 'No. of Verification is Required',
            'count-'.$service_id.'.integer' => 'No. of Verification Should be Numeric',
            'count-'.$service_id.'.min' => 'No. of Verification Should be Atleast 1',
            'count-'.$service_id.'.max' => 'No. of Verification Should Can Select Maximum 1000',
        ];

        $validator = Validator::make($request->all(), $rules, $custom);

        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        DB::beginTransaction();
        try
        {
            $user=DB::table('users')->where('id',$business_id)->first();

            $guest_master=DB::table('guest_instant_masters')
                            ->where(['business_id'=>$business_id,'is_payment_done'=>0,'status'=>NULL])
                            ->first();
            
            if($guest_master!=NULL)
            {
                $data = [
                    'user_id'       => $user_id,
                    'currency'      => 'INR',
                    'promo_code_id' => NULL,
                    'promo_code_title' => NULL,
                    'tax' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                DB::table('guest_instant_masters')->where(['business_id'=>$business_id,'is_payment_done'=>0,'status'=>NULL])->update($data);

                $guest_master_id=$guest_master->id;
            }
            else
            {
                $data=[
                    'parent_id' => $parent_id,
                    'business_id' => $business_id,
                    'user_id' => $user_id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'contactNumber' => $user->phone,
                    'currency'      => 'INR',
                    'promo_code_id' => NULL,
                    'promo_code_title' => NULL,
                    'tax' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $guest_master_id=DB::table('guest_instant_masters')->insertGetId($data);
            }

            // DB::table('guest_instant_carts')
            // ->where(['giv_m_id'=>$guest_master_id])
            // ->whereNotIn('service_id',$request->services)
            // ->delete();

            // DB::table('guest_instant_cart_services')
            //     ->where(['giv_m_id'=>$guest_master_id])
            //     ->whereNull('service_data')
            //     ->whereNotIn('service_id',$request->services)
            //     ->delete();
            
            $no_of_verification=$request->input('count-'.$service_id);

            $total_check_price = 0;

            $price = 100;

            if($service_id==2 || $service_id==3)
            {
                $price = 75;
            }

            $g_check_price = DB::table('guest_instant_check_prices')->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();

            if($g_check_price!=NULL)
            {
                $price = $g_check_price->price;
            }

            $total_check_price = $total_check_price + ($price * $no_of_verification);

            $guest_cart=DB::table('guest_instant_carts')
                                ->where(['giv_m_id'=>$guest_master_id,'service_id'=>$service_id])
                                ->first();

            if($guest_cart!=NULL)
            {
                $data=[
                    'service_id' => $service_id,
                    'number_of_verification' => $no_of_verification,
                    'currency'      => 'INR',
                    'sub_total'     => $total_check_price,
                    'total_price'   => $total_check_price,
                    'updated_at'    => date('Y-m-d H:i:s'),
    
                ];

                DB::table('guest_instant_carts')->where(['giv_m_id'=>$guest_master_id,'service_id'=>$service_id])->update($data);

                $guest_cart_id=$guest_cart->id;
            }
            else
            {

                $data=[
                    'parent_id' => $parent_id,
                    'business_id' => $business_id,
                    'user_id' => $user_id,
                    'giv_m_id' => $guest_master_id,
                    'service_id' => $service_id,
                    'number_of_verification' => $no_of_verification,
                    'currency'      => 'INR',
                    'sub_total'     => $total_check_price,
                    'total_price'   => $total_check_price,
                    'created_at'    => date('Y-m-d H:i:s'),
    
                ];
    
                $guest_cart_id=DB::table('guest_instant_carts')->insertGetId($data);

            }

            DB::table('guest_instant_cart_services')
                            ->where(['giv_m_id'=>$guest_master_id,'giv_c_id'=>$guest_cart_id,'service_id'=>$service_id])
                            ->whereNull('service_data')
                            ->delete();

            $guest_cart_services=DB::table('guest_instant_cart_services')
                                ->where(['giv_m_id'=>$guest_master_id,'giv_c_id'=>$guest_cart_id,'service_id'=>$service_id])
                                ->get();
            if(count($guest_cart_services)>0)
            {
                $guest_cart_service_data=DB::table('guest_instant_cart_services')
                                        ->where(['giv_m_id'=>$guest_master_id,'giv_c_id'=>$guest_cart_id,'service_id'=>$service_id])
                                        ->whereNotNull('service_data')
                                        ->get();
                
                if(count($guest_cart_service_data)>0)
                {
                    $n = count($guest_cart_service_data);

                    for($i=$n; $i < $no_of_verification; $i++)
                    {
                        $j = $i+1;

                        $data=[
                            'parent_id' => $parent_id,
                            'business_id' => $business_id,
                            'user_id' => $user_id,
                            'giv_m_id' => $guest_master_id,
                            'giv_c_id' => $guest_cart_id,
                            'service_id' => $service_id,
                            'check_item_number' => $j,
                            'price' => $price,
                            'created_at'    => date('Y-m-d H:i:s'),
                        ];

                        DB::table('guest_instant_cart_services')->insert($data);
                    }
                }
                else
                {
                    for($i=0; $i < $no_of_verification; $i++)
                    {
                        $j = $i+1;

                        $data=[
                            'parent_id' => $parent_id,
                            'business_id' => $business_id,
                            'user_id' => $user_id,
                            'giv_m_id' => $guest_master_id,
                            'giv_c_id' => $guest_cart_id,
                            'service_id' => $service_id,
                            'check_item_number' => $j,
                            'price' => $price,
                            'created_at'    => date('Y-m-d H:i:s'),
                        ];

                        DB::table('guest_instant_cart_services')->insert($data);
                    }
                }
            }
            else
            {
                for($i=0; $i < $no_of_verification; $i++)
                {
                    $j = $i+1;

                    $data=[
                        'parent_id' => $parent_id,
                        'business_id' => $business_id,
                        'user_id' => $user_id,
                        'giv_m_id' => $guest_master_id,
                        'giv_c_id' => $guest_cart_id,
                        'service_id' => $service_id,
                        'check_item_number' => $j,
                        'price' => $price,
                        'created_at'    => date('Y-m-d H:i:s'),
                    ];

                    DB::table('guest_instant_cart_services')->insert($data);
                }
            }

            $tax = 18;

            $final_total_price = DB::table('guest_instant_cart_services')->select('price')->where('giv_m_id',$guest_master_id)->sum('price');
            
            DB::table('guest_instant_masters')->where(['id'=>$guest_master_id])->update([
                'sub_total'     => $final_total_price,
                'total_price'   => number_format($final_total_price + (($final_total_price * $tax) / 100),2),
                'user_id'       => $user_id,
                'currency'      => 'INR',
                'promo_code_id' => NULL,
                'promo_code_title' => NULL,
                'tax' => $tax,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $guest_service_count = DB::table('guest_instant_cart_services')->where(['giv_m_id'=>$guest_master_id])->count();

            $guest_service_data_count = DB::table('guest_instant_cart_services')
                                    ->where(['giv_m_id'=>$guest_master_id])
                                    ->whereNull('service_data')
                                    ->count();
            
            DB::commit();
            return response()->json([
                'success' => true,
                'cart_count' => $guest_service_count,
                'service_data_count' => $guest_service_data_count
            ]);

        }
        catch(\Exception $e)
        {
            DB::rollback();
            // something went wrong
            return $e;
        }


    }

    public function servicesCreate(Request $request)
    {
        $business_id=Auth::user()->business_id;
        $guest_master_id=Crypt::decryptString($request->guest_master_id);
      
        $guest_cart=DB::table('guest_instant_carts')->where(['business_id'=>$business_id,'giv_m_id'=>$guest_master_id])->get();

        $guest_master=DB::table('guest_instant_masters')
                        ->where(['business_id'=>$business_id,'is_payment_done'=>0])
                        ->first();
                      
        // dd($guest_cart);
        if($guest_master!=NULL) 
            return view('guest.instantverification.create1',compact('guest_cart','guest_master_id'));
        else
            return redirect()->route('/verify/instant_verification');

    }

    public function servicesStore(Request $request)
    {

        DB::beginTransaction();

        try{

            $guest_master_id=Crypt::decryptString($request->guest_master_id);

            $guest_cart=DB::table('guest_instant_carts')->where(['giv_m_id'=>$guest_master_id])->get();

            $sub_total=0;

            $tax = 18;

            //Validation
            foreach($guest_cart as $gc)
            {
                $service=DB::table('services')->where(['id'=>$gc->service_id])->first();
                $guest_cart_services=DB::table('guest_instant_cart_services')
                                    ->where(['giv_c_id'=>$gc->id,'service_id'=>$gc->service_id])
                                    ->get();
                foreach($guest_cart_services as $gcs)
                {

                    // dd($request->input('check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'));
                    //validation for candidate info
                    $dob = NULL;       
       
                    // if($request->has('common_'.$gcs->id.'-'.$gcs->service_id.'-'.'7')){
                    //     $dob = date('Y-m-d',strtotime($request->input('common_'.$gcs->id.'-'.$gcs->service_id.'-'.'7')));
                    // }
                    // $date_of_b=Carbon::parse($dob)->format('Y-m-d');
                    // $today=Carbon::now();
                    // $today_date=Carbon::now()->format('Y-m-d');
                    // $year=$today->diffInYears($date_of_b);
                    // $rules=[
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'0' => 'required|regex:/^[A-Za-z]+([A-Za-z]+\s)*[A-za-z]+$/u|min:1|max:255',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'1' => 'nullable|regex:/^[A-Za-z]+([A-Za-z]+\s)*[A-za-z]+$/u|min:1|max:255',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'2' => 'nullable|regex:/^[A-Za-z]+([A-Za-z]+\s)*[A-za-z]+$/u|min:1|max:255',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'3' => 'required|regex:/^[A-Za-z]+([A-Za-z]+\s)*[A-za-z]+$/u|min:2|max:255',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'4' => 'required',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'5' => 'required|regex:/^(?=.*[0-9])[0-9]{10}$/',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'6' => 'nullable|email:rfc,dns',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'7' => 'required|date',
                    // ];

                    // $custom=[
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.required' => 'First Name Is Required',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'First Name Should Be In Letters',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.min' => 'First Name Should Be Atleast 1',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.max' => 'First Name Should Be Maximum 255 Characters',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.regex' => 'Middle Name Should Be In Letters',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.min' => 'Middle Name Should Be Atleast 1',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.max' => 'Middle Name Should Be Maximum 255 Characters',
                    //     // 'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'2.required' => 'Last Name is Required',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'2.regex' => 'Last Name Should Be In Letters',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'2.min' => 'Last Name Should Be Atleast 1',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'2.max' => 'Last Name Should Be Maximum 255 Characters',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'3.required' => 'Father Name Is Required',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'3.regex' => 'Father Name Should Be in Letters',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'3.min' => 'Father Name Should Be Atleast 1',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'3.max' => 'Father Name Should Be Maximum 255 Characters',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'4.required' => 'Gender Is Required',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'5.required' => 'Phone Number Is Required',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'5.regex' => 'Phone Number Must Be 10-Digit Number',
                    //     // 'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'5.min' => 'Phone must be 10-digit Number',
                    //     // 'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'6.required' => 'Email is Required',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'6.email' => 'Email Should Be Written In Correct Format',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'7.required' => 'Date of Birth Is Required',
                    //     'common_'.$gcs->id.'-'.$gcs->service_id.'-'.'7.date' => 'Date of Birth Must Be In Date Format',
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

                    //check data validation
                    if($service->name=='Aadhar')
                    {
                        $rules= 
                        [
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'   => 'required|regex:/^((?!([0-1]))[0-9]{12})$/',
                        ];
                        $custom=[
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.required' => 'Aadhar Number is Required',
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'Enter A Valid 12-Digit Aadhar Number',
                        ];
                        $validator = Validator::make($request->all(), $rules,$custom);
                        
                        if ($validator->fails()){
                            return response()->json([
                                'success' => false,
                                'errors' => $validator->errors()
                            ]);
                        }

                    }
                    else if($service->id==3)
                    {
                        $rules= 
                        [
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'   => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{10}$/',
                        ];
                        $custom=[
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.required' => 'PAN Number is Required',
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'Enter A Valid 10-Digit PAN Number',
                        ];
                        $validator = Validator::make($request->all(), $rules,$custom);
                        
                        if ($validator->fails()){
                            return response()->json([
                                'success' => false,
                                'errors' => $validator->errors()
                            ]);
                        }
                    }
                    else if($service->name=='Voter ID')
                    {
                        $rules= 
                        [
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'   => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{10}$/',
                        ];
                        $custom=[
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.required' => 'Voter ID Number is Required',
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'Enter a Valid 10-Digit Voter ID Number',
                        ];
                        $validator = Validator::make($request->all(), $rules,$custom);
                        
                        if ($validator->fails()){
                            return response()->json([
                                'success' => false,
                                'errors' => $validator->errors()
                            ]);
                        }
                    }
                    else if($service->name=='Driving')
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
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'Enter A Valid DL Number',
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
                    }
                    else if($service->name=='RC')
                    {
                        $rules= 
                        [
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'   => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{8,}$/',
                        ];
                        $custom=[
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.required' => 'RC Number is Required',
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'Enter A Valid RC Number',
                        ];
                        $validator = Validator::make($request->all(), $rules,$custom);
                        
                        if ($validator->fails()){
                            return response()->json([
                                'success' => false,
                                'errors' => $validator->errors()
                            ]);
                        }
                    }
                    else if($service->name=='Passport')
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
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'Enter A Valid File Number',
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

                    }
                    else if($service->name=='Bank Verification')
                    {
                        $rules= 
                        [
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'   => 'required|regex:/^(?=.*[0-9])[A-Z0-9]{9,18}$/',
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1'   => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{11,}$/',
                        ];
                        $custom=[
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.required' => 'Account Number is Required',
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'Enter A Valid Account Number',
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.required' => 'IFSC Code is Required',
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.regex' => 'Enter A Valid IFSC Code',
                        ];
                        $validator = Validator::make($request->all(), $rules,$custom);
                        
                        if ($validator->fails()){
                            return response()->json([
                                'success' => false,
                                'errors' => $validator->errors()
                            ]);
                        }
                    }
                    else if(stripos($service->name,'E-Court')!==false)
                    {
                        $rules= 
                        [
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'   => 'required|regex:/^[A-Za-z]+([A-Za-z]+\s)*[A-za-z]+$/u|min:3|max:255',
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1'   => 'required|regex:/^[A-Za-z]+([A-Za-z]+\s)*[A-za-z]+$/u|min:3|max:255',
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'2'   => 'required|min:4|max:255',
                        ];
                        $custom=[
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.required' => 'Name is Required',
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'Name Must Be A String',
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.min' => 'Name Must Be Atleast 3 Character Long',
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.max' => 'Name Must Be Maximum 255 Character Long',
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.required' => 'Father Name is Required',
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.regex' => 'Father Name Must Be A String',
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.min' => 'Father Name Must Be Atleast 3 Character Long',
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'1.max' => 'Father Name Must Be Maximum 255 Character Long',
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'2.required' => 'Address is Required',
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
                    }
                    else if(stripos($service->type_name,'upi')!==false)
                    {
                        $rules= 
                        [
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'   => 'required|regex:/^[\w\.\-_]{3,}@[a-zA-Z]{3,}$/u',
                        ];
                        $custom=[
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.required' => 'UPI ID is Required',
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'Enter A Valid UPI ID',
                        ];
                        $validator = Validator::make($request->all(), $rules,$custom);
                        
                        if ($validator->fails()){
                            return response()->json([
                                'success' => false,
                                'errors' => $validator->errors()
                            ]);
                        }
                    }
                    else if(stripos($service->type_name,'cin')!==false)
                    {
                        $rules= 
                        [
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0'   => ['required','regex:/^([L|U]{1})([0-9]{5})([A-Za-z]{2})([0-9]{4})([A-Za-z]{3})([0-9]{6})$/u'],
                        ];
                        $custom=[
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.required' => 'CIN Number is Required',
                            'check_'.$gcs->id.'-'.$gcs->service_id.'-'.'0.regex' => 'Enter A Valid CIN Number',
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

                $sub_total = $sub_total + $gc->total_price;

            }

            DB::table('guest_instant_masters')->where(['id'=>$guest_master_id])->update([
                'promo_code_id' => NULL,
                'promo_code_title' => NULL,
                'sub_total' => $sub_total,
                'tax' => $tax,
                'total_price' => number_format($sub_total + (($sub_total * $tax) / 100),2),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            foreach($guest_cart as $gc)
            {
                $guest_cart_services=DB::table('guest_instant_cart_services')
                                    ->where(['giv_c_id'=>$gc->id,'service_id'=>$gc->service_id])
                                    ->get();

                foreach($guest_cart_services as $gcs)
                {
                    $common_data=[];

                    $checks_data=[];

                    $form_data=[];
                    
                    // $guest_common_form_inputs=DB::table('guest_service_form_inputs')
                    //                         ->where(['service_id'=>0])
                    //                         ->get();
                    // $i=0;
                    // foreach($guest_common_form_inputs as $input)
                    // {
                    //     $common_data[$request->input('common_label-'.$gcs->id.'-'.$gcs->service_id.'-'.$i)]=$request->input('common_'.$gcs->id.'-'.$gcs->service_id.'-'.$i);
                    //     $i++;
                    // }

                    $guest_service_form_input=DB::table('guest_service_form_inputs')
                                                ->where(['service_id'=>$gc->service_id])
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
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'guest_master_id' => Crypt::encryptString($guest_master_id),
            ]);

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;

        }        


    }

    public function checkOut(Request $request)
    {
        $business_id=Auth::user()->business_id;
        $guest_master_id=Crypt::decryptString($request->guest_master_id);

        $guest_cart=DB::table('guest_instant_carts')->where(['business_id'=>$business_id,'giv_m_id'=>$guest_master_id])->get();
        // dd($guest_cart);
        $guest_master=DB::table('guest_instant_masters')
                        ->where(['id'=>$guest_master_id,'is_payment_done'=>0])
                        ->first();

        if($guest_master!=NULL)
            return view('guest.instantverification.checkout',compact('guest_cart','guest_master'));
        else
            return redirect()->route('/verify/instant_verification');
        
    }

    public function addPromoCode(Request $request)
    {
        $parent_id =  Auth::user()->parent_id;
        $guest_master_id=Crypt::decryptString($request->guest_master_id);
        $rules= 
        [
            'promocode'   => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        // dd($promocode);

        // $promo=DB::table('promocodes')->where(DB::raw('BINARY `title`'),$request->promocode)->where(['status'=>'1','is_deLeted'=>'0'])->first();
        $promo = NULL;
        $promos = DB::table('promocodes')->where('business_id',$parent_id)->get();

        if(count($promos)==0)
        {
            return response()->json([
                'success' => false,
                'errors' => ['promocode'=>'No Promocode Found !']
            ]);
        }
        else
        {
            $promo=DB::table('promocodes')
                ->where('title',$request->promocode)
                ->where(['status'=>'1','is_deleted'=>'0','is_expired'=>0])
                ->first();

            // dd($promo);

            if($promo==NULL)
            {
                return response()->json([
                    'success' => false,
                    'errors' => ['promocode'=>'Please enter a valid promocode !']
                ]);
            }
        }

        $guest_master = DB::table('guest_instant_masters')->where(['id'=>$guest_master_id])->first();

        // $total_price=$request->total_price;

        $sub_total = $guest_master->sub_total;

        $discount = $promo->discount;

        $discount_value = 0;

        $discount_type = '';

        $amount = 0;

        $total_price = 0;

        if($promo->discount_type == 'fixed_amount')
        {
            // $amount = $sub_total - $discount;

            // $amount=number_format($amount,2);

            // $discount_value = $discount;

            // $discount_type = 'Fixed Amount';

            $tax = 18;

            $amount = number_format($sub_total + (($sub_total * $tax) / 100),2);

            $amount = number_format($amount - $discount, 2);

            $discount_value = $discount;

            $discount_type = 'Fixed Amount';

            $total_price =  $amount;

        }
        else
        {
            // $amount=$sub_total - ($sub_total * ($discount/100));

            // $amount=number_format($amount,2);

            // $discount_value = number_format($sub_total * ($discount/100),2);

            // $discount_type = 'Percentage';

            // $tax = 18;

            // $total_price = number_format($amount + (($amount * $tax) / 100),2);

            $tax = 18;

            $amount = number_format($sub_total + (($sub_total * $tax) / 100),2);

            $discount_value = number_format($amount * ($discount/100),2);

            $amount = number_format($amount - ($amount * ($discount/100)),2);

            $discount_type = 'Percentage';

            $total_price = $amount;

        }

        // $tax = 18;

        // $total_price = number_format($amount + (($amount * $tax) / 100),2);
        
        $guest_v = DB::table('guest_instant_masters')
                ->where(['id'=>$guest_master_id])
                ->update([
                    'promo_code_id' => $promo->id,
                    'promo_code_title' => $request->promocode,
                    'total_price' => $total_price
                ]);
        
        
        
        return response()->json([
            'success' => true,
            'type' => $promo->discount_type,
            'discount' => $discount,
            'discount_value' => $discount_value,
            'tax' => $tax,
            'total_price' => $total_price,
            'title' => $request->promocode,
            'dis_type' => $discount_type
        ]);
        

    }

    public function removePromoCode(Request $request)
    {
        $guest_master_id=Crypt::decryptString($request->guest_master_id);

        $tax = 18;

        $guest_v=DB::table('guest_instant_masters')->where(['id'=>$guest_master_id])->first();
        
            DB::table('guest_instant_masters')
                ->where(['id'=>$guest_master_id])
                ->update([
                    'promo_code_id' => NULL,
                    'promo_code_title' => NULL,
                    'tax' => $tax,
                    'total_price' => number_format($guest_v->sub_total + (($guest_v->sub_total * $tax) / 100),2)
                ]);
        
        return response()->json([
            'success' => true,
            'total_price' => number_format($guest_v->sub_total + (($guest_v->sub_total * $tax) / 100),2),
        ]);
        

    }

    public function checkOutStore(Request $request)
    {
        $guest_master_id=Crypt::decryptString($request->guest_master_id);
        $receiptId = Str::random(20);
        $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));

        $guest_master = DB::table('guest_instant_masters')->where(['id'=>$guest_master_id])->first();

        $tax = 18;

        $amount = 0;

        $total_price = 0; 

        // $total_price=$request->total_price;

        $sub_total = $guest_master->sub_total;

        if($guest_master->promo_code_id!=NULL)
        {
            $promo=DB::table('promocodes')
                ->where('id',$guest_master->promo_code_id)
                ->first();

            $discount = $promo->discount;

            if($promo->discount_type == 'fixed_amount')
            {
                $amount = $sub_total - $discount;
    
                $amount=number_format($amount,2);
            }
            else
            {
                $amount=$sub_total - ($sub_total * ($discount/100));
    
                $amount=number_format($amount,2);
            }

            $total_price = number_format($amount + (($amount * $tax) / 100),2);
        }
        else
        {
            $total_price = number_format($sub_total + (($sub_total * $tax) / 100),2);
        }

        $order = $api->order->create(array(
            'receipt' => $receiptId,
            'amount' => $total_price * 100,
            'currency' => 'INR',
            'payment_capture' => true
            )
        );


        $userID = "BCD";
        $order_id = strtoupper(substr($userID, 0, 3)).date("-ymds").$guest_master_id;

        DB::table('guest_instant_masters')
            ->where(['id'=>$guest_master_id])
            ->update([
                'transaction_id'=> $order['id'],
                'order_id' => $order_id,
                'razorpay_id'   => env('RAZOR_KEY'),
                'currency'      => 'INR',
                'total_price'   => $total_price,
                'tax' => $tax
            ]);

        return redirect('/verify/instant_verification/payment-page/'.Crypt::encryptString($guest_master_id).'/'.Crypt::encryptString($order['id']));

    }

    public function deleteServiceByCheckItem(Request $request)
    {
        $parent_id=Auth::user()->parent_id;

        DB::beginTransaction();
        try
        {
            $price = 100;

            $gcs_id=base64_decode($request->id);

            $guest_cart_services=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id])->first();

            if($guest_cart_services->service_id==2 || $guest_cart_services->service_id==3)
            {
                $price = 75;
            }

            $guest_check_price=DB::table('guest_instant_check_prices')->where(['business_id'=>$parent_id,'service_id'=>$guest_cart_services->service_id])->first();

            if($guest_check_price!=NULL)
            {
                $price = $guest_check_price->price;
            }

            DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id])->delete();

            $guest_cart=DB::table('guest_instant_carts')->where(['id'=>$guest_cart_services->giv_c_id])->first();

            $no_of_verification = $guest_cart->number_of_verification - 1;

            if($no_of_verification==0)
            {
                DB::table('guest_instant_carts')->where(['id'=>$guest_cart->id])->delete();
            }
            else
            {
                DB::table('guest_instant_carts')
                        ->where(['id'=>$guest_cart->id])
                        ->update(
                            [
                                'sub_total' => $guest_cart->sub_total - $price,
                                'total_price' => $guest_cart->total_price - $price,
                                'number_of_verification' => $no_of_verification,
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
            }

            $guest_carts=DB::table('guest_instant_carts')->where(['giv_m_id'=>$guest_cart->giv_m_id])->get();

            if(count($guest_carts)>0)
            {
                $total_price = 0;

                $sub_total=$guest_carts->sum('total_price');

                $tax = 18;

                $total_price = number_format($sub_total + (($sub_total * $tax) / 100),2);

                DB::table('guest_instant_masters')
                        ->where(['id'=>$guest_cart->giv_m_id])
                        ->update(
                            [
                                'sub_total' => $sub_total,
                                'total_price' => $total_price,
                                'tax' => $tax,
                                'promo_code_id' => NULL,
                                'promo_code_title' => NULL,
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
            }
            else
            {
                DB::table('guest_instant_masters')->where(['id'=>$guest_cart->giv_m_id])->delete();
            }

            DB::commit();

            $guest_master=DB::table('guest_instant_masters')->where(['id'=>$guest_cart->giv_m_id])->first();

            if($guest_master!=NULL)
            {
                return response()->json([
                    'success'  => true,
                    'db' => false
                ]);
            }
            else
            {
                return response()->json([
                    'success'  => true,
                    'db' => true
                ]);
            }

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;

        }



    }

    public function deleteAllServices(Request $request)
    {
        $guest_master_id =Crypt::decryptString($request->id);

        DB::beginTransaction();
        try{

            DB::table('guest_instant_cart_services')->where(['giv_m_id'=>$guest_master_id])->delete();
            DB::table('guest_instant_carts')->where(['giv_m_id'=>$guest_master_id])->delete();
            DB::table('guest_instant_masters')->where(['id'=>$guest_master_id])->delete();

            DB::commit();

            return response()->json([
                'success' => true,
            ]);

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;

        }


    }

    public function deleteServices(Request $request)
    {
        $guest_cart_id=base64_decode($request->id);

        $service_id=base64_decode($request->service_id);

        $guest_master_id =Crypt::decryptString($request->guest_master_id);

        DB::beginTransaction();
        try{

            DB::table('guest_instant_cart_services')->where(['giv_c_id'=>$guest_cart_id,'service_id'=>$service_id])->delete();

            DB::table('guest_instant_carts')->where(['id'=>$guest_cart_id])->delete();

            $guest_cart=DB::table('guest_instant_carts')->where(['giv_m_id'=>$guest_master_id])->get();

            if(count($guest_cart)>0)
            {
                $total_price = 0;

                $sub_total=$guest_cart->sum('total_price');

                $tax = 18;

                $total_price = number_format($sub_total + (($sub_total * $tax) / 100),2);

                DB::table('guest_instant_masters')->where(['id'=>$guest_master_id])->update([
                    'sub_total' => $sub_total,
                    'total_price' =>$total_price,
                    'tax' => $tax,
                    'promo_code_id' => NULL,
                    'promo_code_title' => NULL,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);  

                DB::commit();

                return response()->json([
                    'success'  => true,
                    'db' => false
                ]);
            }
            else
            {
                DB::table('guest_instant_masters')->where(['id'=>$guest_master_id])->delete();

                DB::commit();

                return response()->json([
                    'success'  => true,
                    'db' => true
                ]);
            }

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;

        }

    }

    public function payment(Request $request){
        $business_id = Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $guest_master_id = Crypt::decryptString($request->guest_master_id);
        $order_id = Crypt::decryptString($request->order_id);
        // dd($order_id);
        $response=DB::table('guest_instant_masters')->where(['id'=>$guest_master_id,'transaction_id'=>$order_id,'is_payment_done'=>0])->first();
        // dd($response);
        if($response!=NULL)
            return view('guest.instantverification.payment.payment-page',compact('response'));
        else
            return redirect()->route('/verify/instant_verification');  
    }

    public function Complete(Request $request)
    {
        $business_id=Auth::user()->business_id;
        $user_id=Auth::user()->id;

        $guest_master_id=base64_decode($request->guest_master_id);


        $order_id=$request->all()['rzp_orderid'];
        // Now verify the signature is correct . We create the private function for verify the signature
        $signatureStatus = $this->SignatureVerify(
            $request->all()['rzp_signature'],
            $request->all()['rzp_paymentid'],
            $request->all()['rzp_orderid']
        );
        
        // dd($signatureStatus);
        
        // If Signature status is true We will save the payment response in our database
        // In this tutorial we send the response to Success page if payment successfully made
        DB::beginTransaction();

        try{

            $guest_master=DB::table('guest_instant_masters')->where(['id'=>$guest_master_id,'transaction_id'=>$request->all()['rzp_orderid']])->first();

            if($guest_master->is_payment_done==1)
            {
                header("Refresh:0");
            }
            else
            {
                if($signatureStatus == true)
                {
                    $guest_master=DB::table('guest_instant_masters')->where(['id'=>$guest_master_id,'transaction_id'=>$request->all()['rzp_orderid']])->first();

                    $guest_cart=DB::table('guest_instant_carts')
                                ->where(['giv_m_id'=>$guest_master->id])
                                ->orderBy('service_id','asc')
                                ->get();

                    //$item_arr = [];

                    // generate pdf report
                    foreach($guest_cart as $gc)
                    {
                        $guest_cart_services=DB::table('guest_instant_cart_services')->where(['giv_c_id'=>$gc->id,'service_id'=>$gc->service_id])->get();

                        foreach($guest_cart_services as $gcs)
                        {
                            $services=DB::table('services')->where('id',$gcs->service_id)->first();
                            if(stripos($services->name,'Aadhar')!==false)
                            {
                                $this->idCheckAadhar($gcs->id,$gcs->service_id);
                            }
                            else if(stripos($services->type_name,'pan')!==false)
                            {
                                // dd($gcs->id);
                                $this->idCheckPan($gcs->id,$gcs->service_id);
                            }
                            else if(stripos($services->type_name,'voter_id')!==false)
                            {
                                $this->idCheckVoterID($gcs->id,$gcs->service_id);
                            }
                            else if(stripos($services->type_name,'rc')!==false)
                            {
                                $this->idCheckRC($gcs->id,$gcs->service_id);
                            }
                            else if(stripos($services->type_name,'passport')!==false)
                            {
                                $this->idCheckPassport($gcs->id,$gcs->service_id);
                            }
                            else if(stripos($services->type_name,'driving_license')!==false)
                            {
                                $this->idCheckDL($gcs->id,$gcs->service_id);
                            }
                            else if(stripos($services->type_name,'bank_verification')!==false)
                            {
                                $this->idCheckBankAccount($gcs->id,$gcs->service_id);
                            }
                            else if(stripos($services->name,'E-Court')!==false)
                            {
                                $this->idCheckECourt($gcs->id,$gcs->service_id);
                            }
                            else if(stripos($services->type_name,'upi')!==false)
                            {
                                $this->idCheckUPI($gcs->id,$gcs->service_id);
                            }
                            else if(stripos($services->type_name,'cin')!==false)
                            {
                                $this->idCheckCIN($gcs->id,$gcs->service_id);
                            }
                        }

                        // $services=DB::table('services')->where('id',$gc->service_id)->first();

                        // $item_arr[]=array(
                        //     'name' => $services->name,
                        //     'quantity' => $gc->number_of_verification,
                        //     'currency' => 'INR',
                        //     'amount' => number_format(($gc->total_price / $gc->number_of_verification),2)
                        // );

                    }
                    
                    // generating the service_wise zip
                    foreach($guest_cart as $gc)
                    {
                        $guest_cart_services=DB::table('guest_instant_cart_services')
                                            ->where(['giv_c_id'=>$gc->id,'service_id'=>$gc->service_id])
                                            ->get();

                        // $zipname="";
                        // $services=DB::table('services')->where('id',$gc->service_id)->first();

                        // if(stripos($services->name,'Aadhar')!==false)
                        // {
                        //     $zipname = 'aadhar-'.date('Ymdhis').'.zip';
                        //     $path = public_path().'/guest/reports/zip/aadhar/';
                        // }
                        // else if(stripos($services->type_name,'pan')!==false)
                        // {
                        //     $zipname = 'pan-'.date('Ymdhis').'.zip';
                        //     $path = public_path().'/guest/reports/zip/pan/';
                        // }
                        // else if(stripos($services->type_name,'voter_id')!==false)
                        // {
                        //     $zipname = 'voter_id-'.date('Ymdhis').'.zip';
                        //     $path = public_path().'/guest/reports/zip/voterid/';
                        // }
                        // else if(stripos($services->type_name,'rc')!==false)
                        // {
                        //     $zipname = 'rc-'.date('Ymdhis').'.zip';
                        //     $path = public_path().'/guest/reports/zip/rc/';
                        // }
                        // else if(stripos($services->type_name,'passport')!==false)
                        // {
                        //     $zipname = 'passport-'.date('Ymdhis').'.zip';
                        //     $path = public_path().'/guest/reports/zip/passport/';
                        // }
                        // else if(stripos($services->type_name,'driving_license')!==false)
                        // {
                        //     $zipname = 'driving-'.date('Ymdhis').'.zip';
                        //     $path = public_path().'/guest/reports/zip/driving/';
                        // }
                        // else if(stripos($services->type_name,'bank_verification')!==false)
                        // {
                        //     $zipname = 'bank-'.date('Ymdhis').'.zip';
                        //     $path = public_path().'/guest/reports/zip/bank/';
                        // }
                        // else if(stripos($services->name,'E-Court')!==false)
                        // {
                        //     $zipname = 'e_court-'.date('Ymdhis').'.zip';
                        //     $path = public_path().'/guest/reports/zip/e-court/';
                        // }
                        // else if(stripos($services->type_name,'upi')!==false)
                        // {
                        //     $zipname = 'upi-'.date('Ymdhis').'.zip';
                        //     $path = public_path().'/guest/reports/zip/upi/';
                        // }
                        // else if(stripos($services->type_name,'cin')!==false)
                        // {
                        //     $zipname = 'cin-'.date('Ymdhis').'.zip';
                        //     $path = public_path().'/guest/reports/zip/cin/';
                        // }

                        // if(!File::exists($path))
                        // {
                        //     File::makeDirectory($path, $mode = 0777, true, true);
                        // }

                        // $zip = new \ZipArchive();
                        // $zip->open($path.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                        // foreach($guest_cart_services as $gcs)
                        // {
                        //     $path = public_path()."/guest/reports/pdf/".$gcs->file_name;
                            
                        //     $zip->addFile($path, '/reports/'.basename($path));  
                        // }

                        // $zip->close();

                        $gcs=DB::table('guest_instant_cart_services')->where(['giv_c_id'=>$gc->id,'service_id'=>$gc->service_id,'status'=>'failed'])->get();

                        if(count($guest_cart_services)==count($gcs))
                        {
                            DB::table('guest_instant_carts')->where(['id'=>$gc->id])->update([
                                // 'zip_name' => $zipname!=""?$zipname:NULL,
                                'status' => 'failed',
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                        }
                        else
                        {
                            DB::table('guest_instant_carts')->where(['id'=>$gc->id])->update([
                                // 'zip_name' => $zipname!=""?$zipname:NULL,
                                'status' => 'success',
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                        }

                    }

                    //generating the master zip
                    $guest_cart=DB::table('guest_instant_carts')
                            ->where(['giv_m_id'=>$guest_master->id])
                            ->orderBy('service_id','asc')
                            ->get();
                    
                    // $zipname="";
                
                    // $zipname = 'reports-'.date('Ymdhis').'.zip';
                    // $zip = new \ZipArchive();      
                    // $zip->open(public_path().'/guest/reports/zip/'.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                    // foreach($guest_cart as $gc)
                    // {
                    //     $services=DB::table('services')->where('id',$gc->service_id)->first();
                    //     if(stripos($services->name,'Aadhar')!==false)
                    //     {
                    //         $path = public_path().'/guest/reports/zip/aadhar/'.$gc->zip_name;
                    //     }
                    //     else if(stripos($services->type_name,'pan')!==false)
                    //     {
                    //         $path = public_path().'/guest/reports/zip/pan/'.$gc->zip_name;
                    //     }
                    //     else if(stripos($services->type_name,'voter_id')!==false)
                    //     {
                    //         $path = public_path().'/guest/reports/zip/voterid/'.$gc->zip_name;
                    //     }
                    //     else if(stripos($services->type_name,'rc')!==false)
                    //     {
                    //         $path = public_path().'/guest/reports/zip/rc/'.$gc->zip_name;
                    //     }
                    //     else if(stripos($services->type_name,'passport')!==false)
                    //     {
                    //         $path = public_path().'/guest/reports/zip/passport/'.$gc->zip_name;
                    //     }
                    //     else if(stripos($services->type_name,'driving_license')!==false)
                    //     {
                    //         $path = public_path().'/guest/reports/zip/driving/'.$gc->zip_name;
                    //     }
                    //     else if(stripos($services->type_name,'bank_verification')!==false)
                    //     {
                    //         $path = public_path().'/guest/reports/zip/bank/'.$gc->zip_name;
                    //     }
                    //     else if(stripos($services->name,'E-Court')!==false)
                    //     {
                    //         $path = public_path().'/guest/reports/zip/e-court/'.$gc->zip_name;
                    //     }
                    //     else if(stripos($services->type_name,'upi')!==false)
                    //     {
                    //         $path = public_path().'/guest/reports/zip/upi/'.$gc->zip_name;
                    //     }
                    //     else if(stripos($services->type_name,'cin')!==false)
                    //     {
                    //         $path = public_path().'/guest/reports/zip/cin/'.$gc->zip_name;
                    //     }

                    //     $zip->addFile($path, '/reports/'.basename($path));  
                    // }

                    // $zip->close();

                    $gc=DB::table('guest_instant_carts')
                    ->where(['giv_m_id'=>$guest_master->id,'status'=>'failed'])
                    ->orderBy('service_id','asc')
                    ->get();

                    if(count($guest_cart)==count($gc))
                    {
                        DB::table('guest_instant_masters')->where(['id'=> $guest_master_id,'transaction_id'=>$request->all()['rzp_orderid']])->update([
                            'payment_id' =>  $request->all()['rzp_paymentid'],
                            'is_payment_done' => 1,
                            // 'zip_name' => $zipname!=""?$zipname:NULL,
                            'status' => 'failed',
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                    else
                    {
                        DB::table('guest_instant_masters')->where(['id'=> $guest_master_id,'transaction_id'=>$request->all()['rzp_orderid']])->update([
                            'payment_id' =>  $request->all()['rzp_paymentid'],
                            'is_payment_done' => 1,
                            // 'zip_name' => $zipname!=""?$zipname:NULL,
                            'status' => 'success',
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                    }

                    $guest_master=DB::table('guest_instant_masters')->where(['id'=>$guest_master_id,'transaction_id'=>$request->all()['rzp_orderid']])->first();

                    // $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));

                    // $invoice = $api->invoice->create(array(
                    //     'type' => 'link',
                    //     'amount' => $guest_master->total_price * 100,  // amount in the smallest currency unit
                    //     'currency' => 'INR',
                    //     'description' => 'Invoice for Instant Verification',
                    //     'customer' => array(
                    //         'name' => $guest_master->name,
                    //         'email' => $guest_master->email,
                    //         'contact' => $guest_master->contactNumber
                    //     ),
                    //     'line_items' => $item_arr,
                    //     // "payment_id"=> $guest_master->payment_id,
                    //     'reminder_enable' => 1
                    // ));

                    $pdf = PDF::loadView('guest.instantverification.payment.invoice', ['master_data'=>$guest_master]);
                    
                    $email=$guest_master->email;
                    $name=$guest_master->name;

                    if($guest_master->updated_at!=NULL)
                        $date=$guest_master->updated_at;
                    else
                        $date=$guest_master->created_at;

                    $data  = array('name'=>$name,'email'=>$email,'date' => $date,'zip_id'=>base64_encode($guest_master->id),'guest_master'=>$guest_master);
            
                    Mail::send(['html'=>'mails.guest-instant-payment'], $data, function($message) use($email,$name,$pdf) {
                        $message->to($email, $name)->subject
                            ('myBCD System - Payment Notification');
                        $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        $message->attachData($pdf->output(), "invoice.pdf");
                    });

                    // incrementing the usage limit of promocode
                    if($guest_master->promo_code_id!=NULL)
                    {
                        $promo = DB::table('promocodes')->where(['id' => $guest_master->promo_code_id])->first();
                        $is_expired=0;

                        $limit = 0;

                        $limit = $promo->used_limit + 1;

                        if($limit==$promo->uses_limit)
                        {
                            $is_expired = 1;

                            DB::table('promocodes')
                            ->where(['id' => $guest_master->promo_code_id])
                            ->update([
                                'is_expired' => $is_expired
                            ]);
                        }

                        DB::table('promocodes')
                                ->where(['id' => $guest_master->promo_code_id])
                                ->update([
                                    'used_limit' => $limit
                                ]);
                    }

                    DB::commit();
                    // You can create this page
                    return redirect('/verify/instant_verification/payment-success/'.Crypt::encryptString($guest_master->id));
                    // return view('guest.instantverification.payment.payment-success',compact('zipname'));
                }
                else{
                    DB::table('guest_instant_masters')->where(['id'=> $guest_master_id,'transaction_id'=>$request->all()['rzp_orderid']])->update([
                        'status' => 'failed',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    DB::table('guest_instant_carts')->where(['giv_m_id'=>$guest_master_id])->update([
                        'status' => 'failed',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    DB::table('guest_instant_cart_services')->where(['giv_m_id'=>$guest_master_id])->update([
                        'status' => 'failed',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    DB::commit();
                    // You can create this page
                    return redirect('/verify/instant_verification/payment-failed/'.Crypt::encryptString($guest_master_id).'/'.Crypt::encryptString($order_id));
                    // return view('guest.instantverification.payment.payment-failed',compact('guest_master_id','order_id'));
                }
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }    

    }

    public function paymentSuccess(Request $request)
    {
        $guest_master_id=Crypt::decryptString($request->guest_master_id);
        
        $data_order = DB::table('guest_instant_cart_services')->where(['giv_m_id'=>$guest_master_id])->get();
    
        $ratingReviews = DB::table('guest_instant_masters')->where(['id'=>$guest_master_id])->get();
        // dd($ratingReviews);
        return view('guest.instantverification.payment.payment-success',compact('guest_master_id','data_order','ratingReviews'));
    }

    public function paymentFailed(Request $request)
    {
        $guest_master_id=Crypt::decryptString($request->guest_master_id);
        $order_id=Crypt::decryptString($request->order_id);

        return view('guest.instantverification.payment.payment-failed',compact('guest_master_id','order_id'));
    }

    public function instantWhatsappReport(Request $request)
    {
        $guest_master_id = base64_decode($request->id);

        DB::beginTransaction();
        try{
            $guest_master_data=DB::table('guest_instant_masters as g')
                                    ->select('g.*','u.phone','u.phone_code')
                                    ->join('users as u','u.id','=','g.business_id')
                                    ->where(['g.id'=>$guest_master_id])
                                    ->first();
            
            if($guest_master_data!=NULL)
            {
                $order_id = '';
                $checks = '';
                $date = NULL;
                $url='';
                $code = '';
                

                $mobile_number = preg_replace('/\D/', '', $guest_master_data->phone);

                $mobile_no_with_code = $guest_master_data->phone_code.''.$mobile_number;

                $order_id = $guest_master_data->order_id;

                $data = DB::table('guest_instant_carts')            
                    ->where('giv_m_id',$guest_master_id)
                    ->get();
                    
                $count=count($data);
                $i=0;
                if(count($data)>0){
                    foreach ($data as $key => $value) {
                        $services=DB::table('services')->where('id',$value->service_id)->first();
                        if(++$i==$count)
                            $checks .= $services->name;
                        else
                            $checks .= $services->name .', ';
                    }
                    
                }

                if($guest_master_data->updated_at!=NULL)
                    $date=date('d-F-Y h:i:s a',strtotime($guest_master_data->updated_at));
                else
                    $date=date('d-F-Y h:i:s a',strtotime($guest_master_data->created_at));

                
                $code = Str::random(6);

                $full_url=Config::get('app.user_url').'/user/downloadguestInstantReportZip/'.base64_encode($guest_master_data->id);

                $url = Config::get('app.user_url').'/user/'.$code;
                
                $response_otp = MSGWhatsappTrait::instantReport($mobile_no_with_code,$order_id,$checks,$date,$url);

                if(count($response_otp)>0)
                {
                    if($response_otp['status'])
                    {
                        DB::table('short_links')->insert([
                            'code' => $code,
                            'link' => $full_url,
                            'created_by' => Auth::user()->id,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);

                        DB::commit();
                        return response()->json([
                            'status' => true, 
                        ]);
                    }
                    else
                    {
                        return response()->json([
                            'status' => false,
                            'message' => 'Something Went Wrong !!'
                        ]);
                    }
                    
                }

                return response()->json([
                    'status' => false,
                    'message' => 'Something Went Wrong'
                ]);


            }

            return response()->json([
                'status' => false,
                'message' => 'Data Not Found !!'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return $e;
        }


    }

    // In this function we return boolean if signature is correct
    private function SignatureVerify($_signature,$_paymentId,$_orderId)
    {
        try
        {
            // Create an object of razorpay class
            $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));
            $attributes  = array('razorpay_signature'  => $_signature,  'razorpay_payment_id'  => $_paymentId ,  'razorpay_order_id' => $_orderId);
            $order  = $api->utility->verifyPaymentSignature($attributes);
            // if($order)
            return true;
        }
        catch(\Exception $e)
        {
            // If Signature is not correct its give a excetption so we use try catch
            return false;
        }
    }

    // check id - aadhar
    public function idCheckAadhar($gcs_id,$service_id)
    {  
        $parent_id=Auth::user()->parent_id;      
        $business_id = Auth::user()->business_id;
        $user_id=Auth::user()->id;
        $master_id = NULL;

        $price=75;

        // $path=public_path().'/guest/reports/pdf/';

        // if(!File::exists($path))
        // {
        //     File::makeDirectory($path, $mode = 0777, true, true);
        // }

        
        $guest_c_s=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->first();

        $price=$guest_c_s->price;

        $file_name='aadhar-'.$guest_c_s->id.date('Ymdhis').".pdf";
        
        $service_data_array=json_decode($guest_c_s->service_data,true);

        $data = $service_data_array['check'];

        $aadhar_number = $data['Aadhar Number'];

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

            // $pdf = PDF::loadView('guest.instantverification.pdf.aadhar', compact('master_data'))
            //     ->save($path.$file_name); 
            
            DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                'price' => $price,
                'status' => 'success',
                'check_master_id' => $master_id,
                // 'file_name' => $file_name,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            
            
        }
        else{
            //check from live API
            $api_check_status = false;
            // Setup request to send json via POST
            $data = array(
                'id_number'    => $aadhar_number,
                'async'         => true,
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
                    // $pdf = PDF::loadView('guest.instantverification.pdf.failed.aadhar', compact('aadhar_number'))
                    //             ->save($path.$file_name);

                        DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                            'price' => $price,
                            'status' => 'failed',
                            // 'file_name' => $file_name,
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

                    
                    
                    // $pdf = PDF::loadView('guest.instantverification.pdf.aadhar', compact('master_data'))
                    // ->save($path.$file_name); 
                
                    DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                        'price' => $price,
                        'status' => 'success',
                        'check_master_id' => $master_id,
                        // 'file_name' => $file_name,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
            else{
                // $pdf = PDF::loadView('guest.instantverification.pdf.failed.aadhar', compact('aadhar_number'))
                // ->save($path.$file_name);

                DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'failed',
                    // 'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
        }
         

    }

    // check id - pan
    public function idCheckPan($gcs_id,$service_id)
    {    
        $parent_id=Auth::user()->parent_id;    
        $business_id = Auth::user()->business_id;
        $user_id=Auth::user()->id;
        $master_id = NULL;
        $price=75;

        // $path=public_path().'/guest/reports/pdf/'; 

        // if(!File::exists($path))
        // {
        //     File::makeDirectory($path, $mode = 0777, true, true);
        // }

        // dd($gv_id);
       
        $guest_c_s=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->first();

        $price=$guest_c_s->price;

        // dd($guest_c_s);

        $file_name='pan-'.$guest_c_s->id.date('Ymdhis').".pdf";
        
        $service_data_array=json_decode($guest_c_s->service_data,true);

        $data = $service_data_array['check'];

        $pan_number=$data['PAN Number'];
        
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

            // $pdf = PDF::loadView('guest.instantverification.pdf.pan', compact('master_data'))
            // ->save($path.$file_name); 
        
            DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                'price' => $price,
                'status' => 'success',
                'check_master_id' => $master_id,
                // 'file_name' => $file_name,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        }
        else{
            //check from live API
            $api_check_status = false;
            // Setup request to send json via POST
            $data = array(
                'id_number'    => $pan_number,
                'async'         => true,
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
                    
                    $master_id=DB::table('pan_check_masters')->insertGetId($data);
                    
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
                
                    DB::table('pan_checks')->insert($data);
                    
                    
                }
                else
                {
                    $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$pan_number])->first();
                    $master_id = $master_data->id;
                }

                // $pdf = PDF::loadView('guest.instantverification.pdf.pan', compact('master_data'))
                //         ->save($path.$file_name); 
                    
                DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'success',
                    'check_master_id' => $master_id,
                    // 'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            }else{
                // $pdf = PDF::loadView('guest.instantverification.pdf.failed.pan', compact('pan_number'))
                //         ->save($path.$file_name);
                DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'failed',
                    // 'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
        }




    }

    // check id - Voter ID
    public function idCheckVoterID($gcs_id,$service_id)
    {   

        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $master_id = NULL;

        $price=100;

        $parent_id=Auth::user()->parent_id;


            // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
            // {
            //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
            //     $parent_id=$users->parent_id;
            // }

        // $path=public_path().'/guest/reports/pdf/';

        // if(!File::exists($path))
        // {
        //     File::makeDirectory($path, $mode = 0777, true, true);
        // }

       
        $guest_c_s=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->first();

        $price=$guest_c_s->price;

        $file_name='voter_id-'.$guest_c_s->id.date('Ymdhis').".pdf";
        
        $service_data_array=json_decode($guest_c_s->service_data,true);

        $data = $service_data_array['check'];

        $voter_id_number=$data['Voter ID Number'];
        //check first into master table
        $master_data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$voter_id_number])->first();
        if($master_data !=null){
            $master_id = $master_data->id;
            $data = $master_data;
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

            // $pdf = PDF::loadView('guest.instantverification.pdf.voter-id', compact('master_data'))
            //     ->save($path.$file_name); 
            
                DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'success',
                    'check_master_id' => $master_id,
                    // 'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
        else{
            //check from live API
            // Setup request to send json via POST
            $data = array(
                'id_number'    => $voter_id_number,
                'async'         => true,
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
                    
                    
                }
                else
                {
                    $master_data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$voter_id_number])->first();

                    $master_id = $master_data->id;
                }

                // $pdf = PDF::loadView('guest.instantverification.pdf.voter-id', compact('master_data'))
                // ->save($path.$file_name); 
            
                DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'success',
                    'check_master_id' => $master_id,
                    // 'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            }else{
                // $pdf = PDF::loadView('guest.instantverification.pdf.failed.voter-id', compact('voter_id_number'))
                // ->save($path.$file_name);
                DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'failed',
                    // 'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
        }

         
    }

    // check id - RC
    public function idCheckRC($gcs_id,$service_id)
    {        
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $master_id=NULL;

        $price=100;

        $parent_id=Auth::user()->parent_id;

        // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        // {
        //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
        //     $parent_id=$users->parent_id;
        // }
        
        // $path=public_path().'/guest/reports/pdf/';

        // if(!File::exists($path))
        // {
        //     File::makeDirectory($path, $mode = 0777, true, true);
        // }

        $guest_c_s=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->first();

        $price = $guest_c_s->price;

        $file_name='rc-'.$guest_c_s->id.date('Ymdhis').".pdf";
        
        $service_data_array=json_decode($guest_c_s->service_data,true);

        $data = $service_data_array['check'];

        $rc_number=$data['RC Number'];

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

            // $pdf = PDF::loadView('guest.instantverification.pdf.rc', compact('master_data'))
            // ->save($path.$file_name); 
        
            DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                'price' => $price,
                'status' => 'success',
                'check_master_id' => $master_id,
                // 'file_name' => $file_name,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        else{
            //check from live API
            // Setup request to send json via POST
            $data = array(
                'id_number'    => $rc_number,
                'async'         => true,
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
                
                // $pdf = PDF::loadView('guest.instantverification.pdf.rc', compact('master_data'))
                //         ->save($path.$file_name); 


        
                DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'success',
                    'check_master_id' => $master_id,
                    // 'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            }else{
                // $pdf = PDF::loadView('guest.instantverification.pdf.failed.rc', compact('rc_number'))
                //         ->save($path.$file_name);
                DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'failed',
                    // 'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
        }
          


    }

    // check id - Passport
    public function idCheckPassport($gcs_id,$service_id){  
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $master_id = NULL;

        $price=100.00;

        $parent_id=Auth::user()->parent_id;

        // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        // {
        //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
        //     $parent_id=$users->parent_id;
        // }
        // $path=public_path().'/guest/reports/pdf/';

        // if(!File::exists($path))
        // {
        //     File::makeDirectory($path, $mode = 0777, true, true);
        // }

       
            $guest_c_s=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->first();

            $price=$guest_c_s->price;

            $file_name='passport-'.$guest_c_s->id.date('Ymdhis').".pdf";
            
            $service_data_array=json_decode($guest_c_s->service_data,true);

            $data = $service_data_array['check'];

            $file_number=$data['File Number'];

            $dob=$data['Date of Birth'];

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

                    DB::table('passport_checks')->insertGetId($log_data);

                    // $pdf = PDF::loadView('guest.instantverification.pdf.passport', compact('master_data'))
                    // ->save($path.$file_name); 
                
                    DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                        'price' => $price,
                        'status' => 'success',
                        'check_master_id' => $master_id,
                        // 'file_name' => $file_name,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }
            else{
                //check from live API
                // Setup request to send json via POST
                $data = array(
                    'id_number' => $file_number,
                    'dob'       => $dob,
                    'async'         => true,
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

                        $master_id = DB::table('passport_check_masters')->insertGetId($data);
                        
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
                    }
                    else
                    {
                        $master_data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$file_number,'dob'=>$dob])->first();
                        $master_id = $master_data->id;
                    }

                    // $pdf = PDF::loadView('guest.instantverification.pdf.passport', compact('master_data'))
                    // ->save($path.$file_name); 
                
                    DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                        'price' => $price,
                        'status' => 'success',
                        'check_master_id' => $master_id,
                        // 'file_name' => $file_name,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                }else{

                    // $pdf = PDF::loadView('guest.instantverification.pdf.failed.passport', compact('file_number','dob'))
                    // ->save($path.$file_name);
                    DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                        'price' => $price,
                        'status' => 'failed',
                        'file_name' => $file_name,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                
            }
           


    }

    // check id - DL
    public function idCheckDL($gcs_id,$service_id)
    {        
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $price=100;
        $master_id = NULL;

        $parent_id=Auth::user()->parent_id;

        // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        // {
        //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
        //     $parent_id=$users->parent_id;
        // }

        // $path=public_path().'/guest/reports/pdf/';

        // if(!File::exists($path))
        // {
        //     File::makeDirectory($path, $mode = 0777, true, true);
        // }

       
            $guest_c_s=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->first();

            $price=$guest_c_s->price;

            $file_name='dl-'.$guest_c_s->id.date('Ymdhis').".pdf";
            
            $service_data_array=json_decode($guest_c_s->service_data,true);

            $data = $service_data_array['check'];

            $dl_number=$data['DL Number'];
            $dob=$data['Date of Birth'];

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
                // $pdf = PDF::loadView('guest.instantverification.pdf.dl', compact('master_data'))
                // ->save($path.$file_name); 
            
                DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'success',
                    'check_master_id' => $master_id,
                    // 'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            else{
                //check from live API
                // Setup request to send json via POST
                $data = array(
                    'id_number'    => $dl_number,
                    'dob'       => $dob,
                    'async'         => true,
                );
                // dd($data);
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
                        
                        $master_data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$dl_number])->first();

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

                    // $pdf = PDF::loadView('guest.instantverification.pdf.dl', compact('master_data'))
                    //         ->save($path.$file_name); 
                        
                    DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                        'price' => $price,
                        'status' => 'success',
                        'check_master_id' => $master_id,
                        // 'file_name' => $file_name,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                }else{
                    // $pdf = PDF::loadView('guest.instantverification.pdf.failed.dl', compact('dl_number'))
                    // ->save($path.$file_name);
                    DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                        'price' => $price,
                        'status' => 'failed',
                        // 'file_name' => $file_name,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                
            }

        


    }

    // check id - bank
    public function idCheckBankAccount($gcs_id,$service_id)
    {    
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $price=100;
        $master_id = NULL;
        $parent_id=Auth::user()->parent_id;

        // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        // {
        //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
        //     $parent_id=$users->parent_id;
        // }
        // $path=public_path().'/guest/reports/pdf/';

        // if(!File::exists($path))
        // {
        //     File::makeDirectory($path, $mode = 0777, true, true);
        // }

       
        $guest_c_s=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->first();

        $price=$guest_c_s->price;

        $file_name='bank-'.$guest_c_s->id.date('Ymdhis').".pdf";
        
        $service_data_array=json_decode($guest_c_s->service_data,true);

        $data = $service_data_array['check'];

        $account_number=$data['Account Number'];

        $ifsc_code=$data['IFSC Code'];

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

            // $pdf = PDF::loadView('guest.instantverification.pdf.bank-verification', compact('master_data'))
            //         ->save($path.$file_name); 
        
            DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                'price' => $price,
                'status' => 'success',
                'check_master_id' => $master_id,
                // 'file_name' => $file_name,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        else{
            //check from live API
            // Setup request to send json via POST
            $data = array(
                'id_number' => $account_number,
                'ifsc'      => $ifsc_code,
                'async'         => true,
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
                else{
                    $master_data = DB::table('bank_account_check_masters')->select('*')->where(['account_number'=>$account_number])->first();
                    $master_id = $master_data->id;
                }

                // $pdf = PDF::loadView('guest.instantverification.pdf.bank-verification', compact('master_data'))
                //         ->save($path.$file_name); 
                    
                DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'success',
                    // 'file_name' => $file_name,
                    'check_master_id' => $master_id,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            }else{
                // $pdf = PDF::loadView('guest.instantverification.pdf.failed.bank-verification', compact('account_number','ifsc_code'))
                //         ->save($path.$file_name);
                DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'failed',
                    // 'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
        }
            

    }

    // check id - ecourt
    public function idCheckECourt($gcs_id,$service_id)
    {    
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $price=100;
        $master_data_id = NULL;
        $parent_id=Auth::user()->parent_id;

        // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        // {
        //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
        //     $parent_id=$users->parent_id;
        // }
        // $path=public_path().'/guest/reports/pdf/';

        // if(!File::exists($path))
        // {
        //     File::makeDirectory($path, $mode = 0777, true, true);
        // }

       
        $guest_c_s=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->first();

        $price=$guest_c_s->price;

        $file_name='e_court-'.$guest_c_s->id.date('Ymdhis').".pdf";
        
        $service_data_array=json_decode($guest_c_s->service_data,true);

        $data = $service_data_array['check'];

        $name=$data['Name'];

        $father_name=$data['Father Name'];

        $address=$data['Address'];

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
            // var_dump($resp); die;
            if($response_code==200)
            {
                $score_status = 0;
                
                // Check where any report score is greater than or equal to 90%
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

                // if($score_status==1)
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
                    
                    // if(count($array_data['reports'])>0)
                    // {
                    //     foreach($array_data['reports'] as $key => $value)
                    //     {
                    //         if($value['score'] >= 90)
                    //         {
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
                    //         }
                    //     }
                    // }
    
                    $master_data = DB::table('e_court_check_masters')->where(['id'=>$master_data_id])->first();
                    
    
                    // $pdf = PDF::loadView('guest.instantverification.pdf.e-court', compact('master_data'))
                    //         ->save($path.$file_name); 
                        
                    DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                        'price' => $price,
                        'status' => 'success',
                        'check_master_id' => $master_data_id,
                        // 'file_name' => $file_name,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                // }
                // else
                // {
                //     $pdf = PDF::loadView('guest.instantverification.pdf.failed.e-court', compact('name','father_name','address'))
                //         ->save($path.$file_name);
                //     DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                //         'price' => $price,
                //         'status' => 'failed',
                //         'file_name' => $file_name,
                //         'updated_at' => date('Y-m-d H:i:s')
                //     ]);
                // }

                

            }else{
                // $pdf = PDF::loadView('guest.instantverification.pdf.failed.e-court', compact('name','father_name','address'))
                //         ->save($path.$file_name);
                DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'failed',
                    // 'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
        
            

    }

    // check id - upi
    public function idCheckUPI($gcs_id,$service_id)
    {    
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;

        $price=100;
        $master_id = NULL;

        $parent_id=Auth::user()->parent_id;

            //  if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
            //  {
            //      $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
            //      $parent_id=$users->parent_id;
            //  }

            // $path=public_path().'/guest/reports/pdf/';

            // if(!File::exists($path))
            // {
            //     File::makeDirectory($path, $mode = 0777, true, true);
            // }

            $upi_id = NULL;

            $guest_c_s=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->first();

            $price=$guest_c_s->price;

            $file_name='upi-'.$guest_c_s->id.date('Ymdhis').".pdf";
            
            $service_data_array=json_decode($guest_c_s->service_data,true);

            $data = $service_data_array['check'];

            $upi_id=$data['UPI ID'];

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

                // $pdf = PDF::loadView('guest.instantverification.pdf.upi', compact('master_data'))
                //         ->save($path.$file_name); 
                    
                DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'success',
                    'check_master_id' => $master_id,
                    // 'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                

            }else{

                // $pdf = PDF::loadView('guest.instantverification.pdf.failed.upi', compact('upi_id'))
                // ->save($path.$file_name);
                DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'failed',
                    // 'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        
            

    }

    // check id - cin
    public function idCheckCIN($gcs_id,$service_id)
    {    
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;

        $price=100;
        $master_id = NULL;

        $parent_id=Auth::user()->parent_id;

            //  if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
            //  {
            //      $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
            //      $parent_id=$users->parent_id;
            //  }

            // $path=public_path().'/guest/reports/pdf/';

            // if(!File::exists($path))
            // {
            //     File::makeDirectory($path, $mode = 0777, true, true);
            // }

            $cin = NULL;

            $guest_c_s=DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->first();

            $price=$guest_c_s->price;

            $file_name='cin-'.$guest_c_s->id.date('Ymdhis').".pdf";
            
            $service_data_array=json_decode($guest_c_s->service_data,true);

            $data = $service_data_array['check'];

            $cin=$data['CIN Number'];

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
          // dd($array_data);
            //var_dump($resp); die;
            // dd(env('SPRING_TOKEN_KEY'));
            if($response_code==200)
            {
                $data = [
                    'cin_number'             =>$cin,
                    'registration_number'    =>$array_data['output']['source']['registration_number'],
                    'company_name'           =>$array_data['output']['source']['company_name'],
                    'registered_address'     =>$array_data['output']['source']['registered_address'],
                    'date_of_incorporation'  =>$array_data['output']['source']['date_of_incorporation']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_incorporation'])) : NULL,
                    //'email_id'               =>$array_data['output']['source']['email_id']!=NULL ? ($array_data['output']['source']['email_id']) : NULL,
                    'paid_up_capital_in_rupees'    =>$array_data['output']['source']['paid_up_capital_in_rupees'],
                    'authorised_capital'           =>$array_data['output']['source']['authorised_capital'],
                    'company_category'             =>$array_data['output']['source']['company_category'],
                    'company_subcategory'          =>$array_data['output']['source']['company_subcategory'],
                    'company_class'                =>$array_data['output']['source']['company_class'],
                    'whether_company_is_listed'    =>$array_data['output']['source']['whether_company_is_listed'],
                    //'company_efilling_status'      =>$array_data['output']['source']['company_efilling_status']!=null ? $array_data['output']['source']['company_efilling_status'] : NULL,
                    'date_of_last_AGM'             =>$array_data['output']['source']['date_of_last_AGM']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_last_AGM'])) : NULL,
                    'date_of_balance_sheet'        =>$array_data['output']['source']['date_of_balance_sheet']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_balance_sheet'])) : NULL,
                    'another_maintained_address'   =>$array_data['output']['source']['another_maintained_address'],
                    'directors'                    => $array_data['output']['source']['directors']!=NULL && count($array_data['output']['source']['directors']) > 0 ? json_encode($array_data['output']['source']['directors']) : NULL,
                    'created_at'                   =>date('Y-m-d H:i:s')
                    ];
                    // dd($data);
                $master_data_id=DB::table('cin_check_masters')->insertGetId($data);

                $log_data = [
                    'parent_id'                         =>$parent_id,
                    'business_id'                       =>$business_id,
                    'service_id'                        => $service_id,
                    'source_type'                       =>'API',
                    'cin_number'                        =>$cin,
                    'registration_number'               =>$array_data['output']['source']['registration_number'],
                    'company_name'                      =>$array_data['output']['source']['company_name'],
                    'registered_address'                =>$array_data['output']['source']['registered_address'],
                    'date_of_incorporation'             =>$array_data['output']['source']['date_of_incorporation']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_incorporation'])) : NULL,
                    //'email_id'                          =>$array_data['output']['source']['email_id']!=null ? ($array_data['output']['source']['email_id']) : '',
                    'paid_up_capital_in_rupees'         =>$array_data['output']['source']['paid_up_capital_in_rupees'],
                    'authorised_capital'                =>$array_data['output']['source']['authorised_capital'],
                    'company_category'                  =>$array_data['output']['source']['company_category'],
                    'company_subcategory'               =>$array_data['output']['source']['company_subcategory'],
                    'company_class'                     =>$array_data['output']['source']['company_class'],
                    'whether_company_is_listed'         =>$array_data['output']['source']['whether_company_is_listed'],
                   // 'company_efilling_status'           =>$array_data['output']['source']['company_efilling_status']!=null ? $array_data['output']['source']['company_efilling_status'] : NULL,
                    'date_of_last_AGM'                  =>$array_data['output']['source']['date_of_last_AGM']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_last_AGM'])) : NULL,
                    'date_of_balance_sheet'             =>$array_data['output']['source']['date_of_balance_sheet']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_balance_sheet'])) : NULL,
                    'another_maintained_address'        =>$array_data['output']['source']['another_maintained_address'],
                    'directors'                         => $array_data['output']['source']['directors']!=NULL && count($array_data['output']['source']['directors']) > 0 ? json_encode($array_data['output']['source']['directors']) : NULL,
                    'is_verified'                       =>'1',
                    'price'                             =>$price,
                    'user_type'                         =>'guest',
                    'user_id'                           =>$user_id,
                    'created_at'                        =>date('Y-m-d H:i:s')
                    ];

                DB::table('cin_checks')->insert($log_data);

                $master_data = DB::table('cin_check_masters')->where(['id'=>$master_data_id])->first();
                $master_id=$master_data->id;

                // $pdf = PDF::loadView('guest.instantverification.pdf.cin', compact('master_data'))
                //         ->save($path.$file_name); 
                    
                DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'success',
                    'check_master_id' => $master_id,
                    // 'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                

            }else{

                // $pdf = PDF::loadView('guest.instantverification.pdf.failed.cin', compact('cin'))
                // ->save($path.$file_name);
                DB::table('guest_instant_cart_services')->where(['id'=>$gcs_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'failed',
                    // 'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        
            

    }

    public function idChecks()
    {
        $business_id = Auth::user()->business_id; 
        $services = DB::table('services')->where(['verification_type'=>'Auto','status'=>1])->whereIn('type_name',['covid_19_certificate'])->get();

        // dd($services);

        return view('guest.instantverification.idcheck',compact('services'));
    }

    // check Covid with OTP
    public function idCovid19Check(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $user_id =Auth::user()->id;
        $service_id=base64_decode($request->service_id);  
        $parent_id=Auth::user()->parent_id;
        
        DB::beginTransaction();
        try{
            // dd($service_id);
            if($request->id_number)
            {
                $id_number=preg_match('/^(?=.*[0-9])[0-9]{10}$/', $request->input('id_number'));
                if($request->input('id_number')=='' || !($id_number))
                {
                    return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'message'     =>"Please enter the valid Phone number!"
                    ]);
                }
                
                //check from live API
                $api_check_status = false;
                $response_code=0;
                    // Setup request to send json via POST
                $data = array(
                    'mobile'    => $request->id_number,
                );
                $payload = json_encode($data);
                $apiURL = "https://cdn-api.co-vin.in/api/v2/auth/public/generateOTP";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                // curl_setopt ( $ch, CURLOPT_POST, 1 );
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); // Inject the token into the header
                curl_setopt($ch, CURLOPT_URL, $apiURL);
                // Attach encoded JSON string to the POST fields
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

                $resp = curl_exec ($ch);
                $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close ($ch);

                // dd($resp);
                // dd($response_code);
            
                $array_data =  json_decode($resp,true);

                // dd($array_data);
                // if(!$array_data['success'])
                // {
                //     return response()->json([
                //         'fail'      =>  true,
                //         'message' => $array_data['message']
                //     ]);
                // }
                
                if($response_code==200)
                {
                    $data = [
                        'txnId'        =>$array_data['txnId'],
                        'business_id' => $business_id,
                        'mobile_no'   =>$request->id_number,
                        'created_by'        => $user_id,
                        'status'            => 1,
                        'created_at'       => date('Y-m-d H:i:s'),
                        ];
                        
                        $otp_id=DB::table('advance_covid19_otps')->insertGetId($data);
                        DB::commit();
                        return response()->json([
                            'fail'      => false,
                            'otp_id'    => base64_encode($otp_id),
                            'txnId'     => $array_data['txnId']
                        ]);
                }
                else
                {
                    // dd($resp);
                    if($response_code==400)
                    {
                        return response()->json([
                            'fail' => true,
                            'message' => $array_data!=NULL?$array_data['error'] : 'Please Try Again After Some Time !!',
                        ]);
                    }
                    else if($response_code==401)
                    {
                        return response()->json([
                            'fail' => true,
                            'message' => 'Enter a Valid Mobile Number ! Try Again !!'
                        ]);
                    }
                    else
                    {
                        return response()->json([
                            'fail' => true,
                            'message' => 'Something Went Wrong !!'
                        ]);
                    }
                }
                  
            }
            else
            {
                return response()->json([
                    'fail'      =>  true,
                    'message' => 'It seems like number is not valid!'
                ]);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 

    }

    public function idVerifyCovid19Check(Request $request)
    {
        $user_id=Auth::user()->id;
        $business_id = Auth::user()->business_id;
        $parent_id=Auth::user()->parent_id;

        

        // $rules = [
        //     'otp'  => 'required|integer|min:4',   
        // ];

        // $validator = Validator::make($request->all(), $rules);
        // if ($validator->fails())
        //     return response()->json([
        //         'fail' => true,
        //         'errors' => $validator->errors(),
        //         'error_type'=>'validation'
        //     ]);

        // Validation for OTP
        if(count($request->otp)==0)
        {
            return response()->json([
                'fail' => true,
                'errors' => ['otp'=>['The otp field is required']],
                'error_type'=>'validation'
            ]);
        }
        else
        {
            foreach($request->otp as $value)
            {
                if($value=='' || $value==NULL)
                {
                return response()->json([
                            'fail' => true,
                            'errors' => ['otp'=>['The otp field is required']],
                            'error_type'=>'validation'
                        ]);
                }
                else if(!is_numeric($value))
                {
                    return response()->json([
                        'fail' => true,
                        'errors' => ['otp'=>['The otp must be numeric']],
                        'error_type'=>'validation'
                    ]);
                }
            }
        }
        $otp=implode('',$request->otp);
        
        DB::beginTransaction();
        try{
                $mobile_number=$request->mob_c;
                $service_id=base64_decode($request->ser_id);
                $otp_id = base64_decode($request->otp_id);
                $txnId=$request->txnId;
                //check from live API
                $api_check_status = false;
                $response_code = 0;
                $master_data = DB::table('advance_covid19_otps')->where(['id'=>$otp_id])->first();
                // dd($master_data);
                // foreach($master_data as $master)
                // {
                //     $client_id = $master->client_id;
                // }

                // Setup request to send json via POST
                $data = array(
                    'otp'    => hash('sha256',$otp),
                    'txnId' => $master_data->txnId,
                );
                $payload = json_encode($data);
                $apiURL = "https://cdn-api.co-vin.in/api/v2/auth/public/confirmOTP";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                curl_setopt ( $ch, CURLOPT_POST, 1 );
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); // Inject the token into the header
                curl_setopt($ch, CURLOPT_URL, $apiURL);
                // Attach encoded JSON string to the POST fields
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

              
                $resp = curl_exec ( $ch );
                $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close ( $ch );
                
                $array_data= json_decode($resp,true);
                
                if($response_code==200)
                {
                    DB::table('advance_covid19_otps')->where(['id'=>$otp_id])->update(
                    [
                        'is_verified'   => '1',
                        'token' => $array_data['token'],
                        'updated_by'    => $user_id,
                        'updated_at'    => date('Y-m-d H:i:s'),
                    ]
                );

                    DB::commit();
                    return response()->json([
                        'fail'      =>false,
                        'id'        => $request->otp_id,
                        'service_id' => $request->ser_id,
                        'mobile_no'    => $mobile_number
                    ]);
                }
                else
                {
                    if($response_code==400)
                    {
                        return response()->json([
                            'fail' => true,
                            'error'=>'yes',
                            'message' => $array_data!=NULL?$array_data['error'] : 'Invalid OTP !!'
                        ]);
                    }
                    else if($response_code==401)
                    {
                        return response()->json([
                            'fail' => true,
                            'error'=>'yes',
                            'message' => 'OTP Session Timeout ! Try Again !!'
                        ]);
                    }
                    else
                    {
                        return response()->json([
                            'fail' => true,
                            'error'=>'yes',
                            'message' => 'Something Went Wrong !!'
                        ]);
                    }
                }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    public function idVerifyCovidRefCheck(Request $request)
    {
        $user_id=Auth::user()->id;
        $business_id = Auth::user()->business_id;
        $parent_id=Auth::user()->parent_id;

        $rules = [
            'reference_id'  => 'required|integer|min:14',   
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors(),
                'error_type'=>'validation'
            ]);

        DB::beginTransaction();
        try{
            $mob_c=$request->mob_c;
            $reference_id=$request->reference_id;
            $otp_id = base64_decode($request->otp_id);
            $service_id=base64_decode($request->ser_id);
            $api_check_status = false;
            $response_code = 0;
            $advance_otp = DB::table('advance_covid19_otps')->where(['id'=>$otp_id])->first();
            $path='';
            $file_name='';
            $content=NULL;
                // dd($advance_otp);
               $master_data=DB::table('covid19_check_masters')->where(['reference_id'=>$reference_id])->first();
                if($master_data!=NULL)
                {
                    $path= public_path().'/cowin/certificate/';
                    $file_name=date('Ymdhis').'-'.'cowin-certificate'.'.pdf';
                    $content=base64_decode($master_data->raw_data);
                    file_put_contents($path.$file_name, $content);

                    DB::table('covid19_checks')->insert([
                        'parent_id' => $parent_id,
                        'business_id'  => $business_id,
                        'service_id'    => $service_id,
                        'txnId' => $master_data->txnId,
                        'source_type'   => 'SystemDB',
                        'mobile_no' => $master_data->mobile_no,
                        'reference_id' => $reference_id,
                        'token' => $master_data->token,
                        'user_id'   => $user_id,
                        'used_by'   => 'guest',
                        'file_name' => $file_name,
                        'raw_data' => base64_encode($content),
                        'created_at'   => date('Y-m-d H:i:s')
                    ]);

                    $URL= url('/').'/cowin/certificate/'.$file_name;
                    DB::commit();
                    return response()->json([
                        'fail' => false,
                        'url' => $URL,
                        'data'=>$master_data
                    ]);

                }
                else
                {
                    // Setup request to send json via POST
                    // $data = array(
                    //     'beneficiary_reference_id'    => $reference_id,
                    // );
                    // $payload = json_encode($data);
                    $apiURL = "https://cdn-api.co-vin.in/api/v2/registration/certificate/public/download?beneficiary_reference_id=".$reference_id;

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                    // curl_setopt ( $ch, CURLOPT_POST, 1 );
                    $authorization = "Authorization: Bearer ".$advance_otp->token; // Prepare the authorisation token
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/pdf',$authorization)); // Inject the token into the header
                    curl_setopt($ch, CURLOPT_URL, $apiURL);
                    // Attach encoded JSON string to the POST fields
                    // curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

                
                    $resp = curl_exec ( $ch );
                    $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close ( $ch );
                    // var_dump($resp);die();
                    // $array_data= json_decode($resp,true);
                    $path='';
                    $file_name='';
                    $content=NULL;
                    if($response_code==200)
                    {
                        $path= public_path().'/cowin/certificate/';
                        $file_name=date('Ymdhis').'-'.'cowin-certificate'.'.pdf';
                        $content=$resp;
                        file_put_contents($path.$file_name, $content);


                        DB::table('advance_covid19_otps')->where(['mobile_no'=>$advance_otp->mobile_no])->update([
                            'status'    => 0,
                            'updated_by' => $user_id,
                            'updated_at'   => date('Y-m-d H:i:s')
                        ]);

                        $master_id = DB::table('covid19_check_masters')->insertGetId([
                                'txnId' => $advance_otp->txnId,
                                'source_type'   => 'API',
                                'mobile_no' => $advance_otp->mobile_no,
                                'reference_id' => $reference_id,
                                'token' => $advance_otp->token,
                                'file_name' => $file_name,
                                'raw_data'  => base64_encode($content),
                                'created_at'   => date('Y-m-d H:i:s')
                            ]);

                        DB::table('covid19_checks')->insert([
                            'parent_id' => $parent_id,
                            'business_id'  => $business_id,
                            'service_id'    => $service_id,
                            'txnId' => $advance_otp->txnId,
                            'source_type'   => 'API',
                            'mobile_no' => $advance_otp->mobile_no,
                            'reference_id' => $reference_id,
                            'token' => $advance_otp->token,
                            'user_id'   => $user_id,
                            'used_by'   => 'guest',
                            'file_name' => $file_name,
                            'raw_data'  => base64_encode($content),
                            'created_at'   => date('Y-m-d H:i:s')
                        ]);

                        $master_data=DB::table('covid19_check_masters')->where(['id'=>$master_id])->first();

                        DB::commit();
                        $URL= url('/').'/cowin/certificate/'.$file_name;
                        return response()->json([
                            'fail' => false,
                            'url'  => $URL,
                            'data' =>$master_data
                        ]);

                    }
                    else
                    {
                        if($response_code==400)
                        {
                            return response()->json([
                                'fail' => true,
                                'error'=>'yes',
                                'message' => 'Data Not Found !!'
                            ]);
                        }
                        else if($response_code==401)
                        {
                            return response()->json([
                                'fail' => true,
                                'error'=>'yes',
                                'message' => 'Timeout ! Try Again Later!!'
                            ]);
                        }
                        else
                        {
                            return response()->json([
                                'fail' => true,
                                'error'=>'yes',
                                'message' => 'Something Went Wrong !!'
                            ]);
                        }
                    }

                }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    public function feedback(Request $request)
    {
        $guest_master_id = base64_decode($request->id);

        $rules = [
            'rating' => 'required',
        ];
    
      
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        DB::beginTransaction();
        try{

            DB::table('guest_instant_masters')
                ->where('id',$guest_master_id)
                ->update([
                            'is_review' => 1,
                            'stars' => $request->rating,
                            'comments' => $request->comments,
                            'commented_by' => Auth::user()->id
                        ]);

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

}
