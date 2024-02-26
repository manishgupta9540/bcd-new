<?php

namespace App\Http\Controllers\Admin;

use App\Traits\S3ConfigTrait;
use App\Http\Controllers\Controller;
use App\Models\Admin\KeyAccountManager;
use App\Models\Admin\UserBusiness;
use App\Models\Admin\UserBusinessContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Traits\EmailConfigTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
class CustomerController extends Controller
{
   
   //get the customer's data
    public function index(Request $request)
    {
        $user_id = Auth::user()->id;
        $business_id = Auth::user()->business_id;
    
        $kams = DB::table('key_account_managers')
                    ->select('*')
                    ->where('user_id',$user_id)
                    ->get()->pluck('business_id');
                    
        $items = DB::table('users as u')
                ->select('u.id','u.name','u.email','u.phone','u.phone_iso','u.phone_code','b.company_name','u.created_at','u.display_id','u.status')
                ->join('user_businesses as b','b.business_id','=','u.id')
                ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                ->whereNotIn('u.id',[$business_id]);

        if($request->get('kams_user')){
            $items->whereIn('u.id',$kams);
        }
    
        if($request->get('active_case')!=''){
            $items->where(['u.status'=>$request->get('active_case')]);
        }

        if($request->get('from_date') !=""){
            $items->whereDate('u.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
          }
          if($request->get('to_date') !=""){
            $items->whereDate('u.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
          }
          if(is_numeric($request->get('customer_id'))){
            $items->where('u.business_id',$request->get('customer_id'));
          }
          if(is_numeric($request->get('contact_id'))){
            $items->where('u.id',$request->get('contact_id'));
          }
          if($request->get('email')){
            $items->where('u.email',$request->get('email'));
          }
          


        $items=$items->orderBy('b.company_name')->paginate(10);
        // dd($items);
        $kams_user=$request->get('kams_user');

        $active_case=$request->get('active_case');

        $clients = DB::table('users as u')
                ->select('u.id','u.name','u.email','u.phone','u.phone_iso','u.phone_code','b.company_name','u.created_at','u.display_id','u.status')
                ->join('user_businesses as b','b.business_id','=','u.id')
                ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                ->whereNotIn('u.id',[$business_id])->get();

        if($request->ajax())
            return view('admin.customers.ajax',compact('items','active_case','clients','kams','kams_user'));
        else
            return view('admin.customers.index',compact('items','active_case','clients','kams','kams_user'));
    }

    //show the form to create customer
    public function create()
    {
        $plans          = DB::table('subscription_plans')->get();
        $sla            = DB::table('sla_masters')->get();
        $countries      = DB::table('countries')->get();
        $state          = DB::table('states')->where('country_id','101')->get();
        $users          = DB::table('users')->where('user_type','user')->where('business_id',Auth::user()->business_id)->get();
        // dd($users);

    	return view('admin.customers.create', compact('plans','sla','countries','state','users'));
    }

    //show the form to create customer
    public function createStep()
    {
        $user_board = NULL;
        $session_id = NULL;

        if(session()->exists('board_session_id'))
        {
            $session_id = session()->get('board_session_id');
        }

        $business_id = Auth::user()->business_id;
        $plans          = DB::table('subscription_plans')->get();
        $sla            = DB::table('sla_masters')->get();
        $countries      = DB::table('countries')->get();
        $state          = DB::table('states')->where('country_id','101')->get();
        $users          = DB::table('users')->where('user_type','user')->where('business_id',Auth::user()->business_id)->get();

        $services = DB::table('services as s')
        ->select('s.*')
        ->join('service_form_inputs as si','s.id','=','si.service_id')
        ->where('s.status','1')
        ->where('s.business_id',NULL)
        ->whereNotIn('s.type_name',['gstin'])
        ->orwhere('s.business_id',$business_id)
        ->groupBy('si.service_id')
        ->get();

        $user_board = DB::table('user_onboardings')
                        ->where(['parent_id'=>$business_id,'user_type'=>'client','status'=>'draft'])
                        ->where('session_id',$session_id)
                        ->first();
        // dd($users);

    	return view('admin.customers.create-step', compact('plans','sla','countries','state','users','services','user_board'));
    }

    //store customer data
    public function store(Request $request)
    {

        $i=0;
        $business_id = Auth::user()->business_id;
        $company_logo=NULL;
        $gst_attachment=NULL;
        $is_gst_verified=0;
        $is_pan_verified=0;
        $client_spokeman=[];

        // dd($request->type);
        // Form validation

        // if($request->gst_exempt==NULL)
        // {
        //     dd(1);
        // }
        // else
        // {
        //     dd(0);
        // }
        
        // $this->validate($request, [
        //     'first_name'   => 'required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
        //     'last_name'     => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
        //     'email'        => 'required|email|unique:users',
        //     'phone'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
        //     'address'      => 'required',
        //     'pincode'      => 'required|numeric|digits:6',
        //     'country_id'    => 'required',
        //     'city_id'      => 'required',
        //     'company_logo' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        //     'state_id'     => 'required',
        //     'company'      => 'required',
        //     // 'kams'          =>'required',
        //     'business_email'            => 'required|email',
        //     'business_phone_number'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
        //     'gst_number'                => 'required|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/|min:15|max:15',
        //     'contract_signed_by'        => 'required',
        //     'hr_name'                   => 'required|regex:/^[a-zA-Z ]+$/u|min:2|max:255',
        //     'work_order_date'           => 'required|date',
        //     'work_operating_date'       => 'required|date|after:work_order_date',
        //     'pan_number'                => 'required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/|min:10|max:11',
        //     'owner_first_name'          => 'required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
        //     'owner_last_name'           => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
        //     'owner_email'               => 'required|email',
        //     'owner_phone_number'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
        //     'owner_designation'         => 'required',
        //     'dealing_first_name'        => 'required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
        //     'dealing_last_name'         => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
        //     'dealing_email'             => 'required|email',
        //     'dealing_phone_number'      => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
        //     'dealing_designation'       => 'required',
        //     'user'                      =>'required',
        //     'secondary'                 =>'required|different:user',
        //     'account_first_name'        => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
        //     'account_last_name'         => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
        //     'account_email'             => 'nullable|email',
        //     'account_phone_number'      => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
        //     'dealing_landline_number'   =>'nullable|numeric',
        //     'account_landline_number'=>'nullable|numeric',
        //     'owner_landline_number'=>'nullable|numeric',
        //     'tin_number'    => 'nullable|numeric|digits:11',

        //     ],
        //     [
        //         'email.unique'  =>'Email id has already been taken',
        //         'secondary.different' => 'Primary and Secondary CAM must be different',
        //         'phone.regex' => 'Phone Number Must be 10-digit Number !!',
        //         'phone.min' => 'Phone Number Must be 10-digit Number !!',
        //         'phone.max' => 'Phone Number Must be 10-digit Number !!',
        //         'business_phone_number.regex' => 'Business Phone Number Must be 10-digit Number !!',
        //         'business_phone_number.min' => 'Business Phone Number Must be 10-digit Number !!',
        //         'business_phone_number.max' => 'Business Phone Number Must be 10-digit Number !!',
        //         'owner_phone_number.regex' => 'Owner Phone Number Must be 10-digit Number !!',
        //         'owner_phone_number.min' => 'Owner Phone Number Must be 10-digit Number !!',
        //         'owner_phone_number.max' => 'Owner Phone Number Must be 10-digit Number !!',
        //         'dealing_phone_number.regex' => 'Dealing Phone Number Must be 10-digit Number !!',
        //         'dealing_phone_number.min' => 'Dealing Phone Number Must be 10-digit Number !!',
        //         'dealing_phone_number.max' => 'Dealing Phone Number Must be 10-digit Number !!',
        //         'account_phone_number.regex' => 'Account Phone Number Must be 10-digit Number !!',
        //         'account_phone_number.min' => 'Account Phone Number Must be 10-digit Number !!',
        //         'account_phone_number.max' => 'Account Phone Number Must be 10-digit Number !!'
        //     ]
        // );

        $rules= [
            'first_name'   => 'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'middle_name'   => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'last_name'     => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'email'        => 'required|email:rfc,dns|unique:users',
            'phone'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
            'address'      => 'required',
            'pincode'      => 'required|numeric|digits:6',
            'country_id'    => 'required',
            'city_id'      => 'required',
            'company_logo' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'state_id'     => 'required',
            'company'      => 'required',
            'company_short_name'      => 'required|min:1|max:30',
            // 'kams'          =>'required',
            'business_email'            => 'nullable|email:rfc,dns',
            'business_phone_number'     => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
            'gst_number'                => 'required_without:gst_exempt|nullable|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/|min:15|max:15',
            'gst_attachment'            => 'required|mimes:jpg,jpeg,png,jpg,gif,svg,pdf|max:200000',
            'contract_signed_by'        => 'required',
            'hr_name'                   => 'required|regex:/^[a-zA-Z ]+$/u|min:2|max:255',
            'contract_start_date'           => 'required|date',
            'contract_end_date'       => 'required|date|after_or_equal:contract_start_date',
            'pan_number'                => 'required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/|min:10|max:11',
            'owner_first_name'          => 'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'owner_middle_name'         => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'owner_last_name'           => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'owner_email'               => 'required|email:rfc,dns',
            'owner_phone_number'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
            'owner_designation'         => 'required',
            'dealing_first_name'        => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'dealing_middle_name'       => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'dealing_last_name'         => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'dealing_email'             => 'nullable|email:rfc,dns',
            'dealing_phone_number'      => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
            'dealing_designation'       => 'nullable',
            'user'                      => 'required',
            'secondary'                 => 'required|different:user',
            'account_first_name'        => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'account_middle_name'       => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'account_last_name'         => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'account_email'             => 'nullable|email:rfc,dns',
            'account_phone_number'      => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
            'dealing_landline_number'   =>'nullable|numeric',
            'account_landline_number'=>'nullable|numeric',
            'owner_landline_number'=>'nullable|numeric',
            'tin_number'    => 'nullable|numeric|digits:11',
            'type.*' => 'sometimes|required|min:1',
            'add_first_name.*' => 'sometimes|required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'add_middle_name.*'       => 'sometimes|nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'add_last_name.*'         => 'sometimes|nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'add_email.*'             => 'sometimes|nullable|email:rfc,dns',
            'add_phone.*'      => 'sometimes|nullable|regex:/^[0-9]{10}/',
            'add_landline_number.*'   =>'sometimes|nullable|numeric',
            'spoke_name.*' => 'sometimes|required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'cam.*' => 'sometimes|required',
            // 'add_first_name' => 'sometimes|array|min:1',
            // 'add_first_name.*'=>'nullable|regex:/^[a-zA-Z]+$/u|min:0|max:255'
            // 'revenue'   => 'required'
         ];
        
         $customMessages = [
            'email.unique'  =>'Email id has already been taken',
            'secondary.different' => 'Primary and Secondary CAM must be different',
            'phone.regex' => 'Phone Number Must be 10-digit Number !!',
            'phone.min' => 'Phone Number Must be 10-digit Number !!',
            'phone.max' => 'Phone Number Must be 10-digit Number !!',
            'business_phone_number.regex' => 'Business Phone Number Must be 10-digit Number !!',
            'business_phone_number.min' => 'Business Phone Number Must be 10-digit Number !!',
            'business_phone_number.max' => 'Business Phone Number Must be 10-digit Number !!',
            'owner_phone_number.regex' => 'Owner Phone Number Must be 10-digit Number !!',
            'owner_phone_number.min' => 'Owner Phone Number Must be 10-digit Number !!',
            'owner_phone_number.max' => 'Owner Phone Number Must be 10-digit Number !!',
            'dealing_phone_number.regex' => 'Dealing Phone Number Must be 10-digit Number !!',
            'dealing_phone_number.min' => 'Dealing Phone Number Must be 10-digit Number !!',
            'dealing_phone_number.max' => 'Dealing Phone Number Must be 10-digit Number !!',
            'account_phone_number.regex' => 'Account Phone Number Must be 10-digit Number !!',
            'account_phone_number.min' => 'Account Phone Number Must be 10-digit Number !!',
            'account_phone_number.max' => 'Account Phone Number Must be 10-digit Number !!',
            // 'revenue.required'      => 'Select at least one company revenue category ',
            'country_id.required' =>'The country field is required',
            'state_id.required' =>'The state field is required',
            'city_id.required' =>'The city field is required',
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
            'add_landline_number.*.numeric' => 'Additional Landline Number Must be Numeric',
            'spoke_man.*.required' => 'Spokeman Name Field is Required',
            'spoke_man.*.regex' => 'Spokeman Name Field must be String',
            'spoke_man.*.min' => 'Spokeman Name Field has atleast 1 character',
            'spoke_man.*.max' => 'Spokeman Name Field has atleast maximum 255 character allowed',
            'cam.*.required' => 'Additional CAM is Required',
        ];

         $validator = Validator::make($request->all(), $rules,$customMessages);
          
         if ($validator->fails()){
             return response()->json([
                 'success' => false,
                 'errors' => $validator->errors()
             ]);
         }
        // dd($request);
        DB::beginTransaction();
        try{
            $phone = preg_replace('/\D/', '', $request->input('phone'));
            $business_phone = preg_replace('/\D/', '', $request->input('business_phone_number'));
            $owner_phone = preg_replace('/\D/', '', $request->input('owner_phone_number'));
            $dealing_phone = preg_replace('/\D/', '', $request->input('dealing_phone_number'));
            $account_phone = preg_replace('/\D/', '', $request->input('account_phone_number'));

            if(strlen($phone)!=10)
            {
                // return back()->withInput()->withErrors(['phone'=>['Phone Number Must be 10-digit Number !!']]);
                return response()->json([
                    'success' => false,
                    'custom'=>'yes',
                    'errors' => ['phone'=>'Phone Number must be 10-digit Number !!']
                  ]);
            }
            else if($request->input('business_phone_number') != "" )
            {
                if ( strlen($business_phone)!=10) {
                   
                // return back()->withInput()->withErrors(['business_phone_number'=>['Business Phone Number Must be 10-digit Number !!']]);
                    return response()->json([
                        'success' => false,
                        'custom'=>'yes',
                        'errors' => ['business_phone_number'=>'Business Phone Number must be 10-digit Number !!']
                    ]);
                }
            }
            else if($request->input('owner_phone_number') != ""  )
            { 
                if (strlen($owner_phone)!=10) {
                # code...
            
                // return back()->withInput()->withErrors(['owner_phone_number'=>['Owner Phone Number Must be 10-digit Number !!']]);
                return response()->json([
                    'success' => false,
                    'custom'=>'yes',
                    'errors' => ['owner_phone_number'=>'Owner Phone Number must be 10-digit Number !!']
                  ]);
                }
            }
            else if($request->input('dealing_phone_number') != "" )
            {
                if (strlen($dealing_phone)!=10) {
                   
                    // return back()->withInput()->withErrors(['dealing_phone_number'=>['Dealing Phone Number Must be 10-digit Number !!']]);
                    return response()->json([
                        'success' => false,
                        'custom'=>'yes',
                        'errors' => ['dealing_phone_number'=>'Dealing Phone Number must be 10-digit Number !!']
                    ]);
                }
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
                            return response()->json([
                                'success' => false,
                                'custom'=>'yes',
                                'errors' => ['gst_number'=>'It seems like GST number is not valid!']
                            ]);
                        }
                }
            }

            //Verification of PAN Number

            //check first into master table
            $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$request->input('pan_number')])->first();

            if($master_data !=null){
                $is_pan_verified=1;
            }
            else
            {
                //check from live API
                $api_check_status = false;
                // Setup request to send json via POST
                $data = array(
                    'id_number'    => $request->input('pan_number'),
                    'async' => true
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
                    $checkIDInDB= DB::table('pan_check_masters')->where(['pan_number'=>$request->input('pan_number')])->count();
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
                        
                        $is_pan_verified=1;
                    }

                }else{
                    return response()->json([
                        'success' => false,
                        'custom'=>'yes',
                        'errors' => ['pan_number'=>'It seems like PAN number is not valid!!']
                    ]);
                }
            }


            $randomPassword = Str::random(10);
            $hashed_random_password = Hash::make($randomPassword);
            //
            if($request->has('password') && !empty($request->input('password')) ){
                $randomPassword = $request->input('password');
                $hashed_random_password = Hash::make($request->input('password'));
            }

            $company_logo_file_platform = 'web';

            $s3_config = S3ConfigTrait::s3Config();
        
            if ($files = $request->file('company_logo')) 
            {
                $destinationPath = public_path('uploads/company-logo/'); 
                $logoImage = time().$request->file('company_logo')->getClientOriginalName();

                $company_logo = $logoImage;

                if($s3_config!=NULL)
                {
                    $company_logo_file_platform = 's3';

                    $path = 'uploads/company-logo/';

                    if(!Storage::disk('s3')->exists($path))
                    {
                        Storage::disk('s3')->makeDirectory($path,0777, true, true);
                    }

                    Storage::disk('s3')->put($path.$logoImage, file_get_contents($files));
                }
                else
                {
                    $files->move($destinationPath, $logoImage);
                }
            }

            $gst_attachment_file_platform = 'web';

            if ($files = $request->file('gst_attachment')) 
            {
                $destinationPath = public_path('uploads/gst-file/'); 
                $gstImage = time().$request->file('gst_attachment')->getClientOriginalName();
                $gst_attachment= $gstImage;

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

            $primary = $request->user;
            $cam = $request->cam;
            $secondary = $request->secondary;
            
            // Cam check unique validation
            if($request->input('cam')!=NULL && count($request->cam) > 0){
                foreach ($request->input('cam') as $value) 
                {
                    if($value == $primary){
                        return response()->json([
                            'success' => false,
                            'custom'=>'yes',
                            'errors' => ['all'=>'Please Select unique CAM !!']
                        ]);
                    }
                    if($value == $secondary){
                        return response()->json([
                            'success' => false,
                            'custom'=>'yes',
                            'errors' => ['all'=>'Please Select Defrent CAM !!']
                        ]);
                    }
                }
                //array unique multiple validation
                if(count(array_unique($cam))<count($cam))
                {
                    return response()->json([
                        'success' => false,
                        'custom'=>'yes',
                        'errors' => ['all'=>'Please Select Deferent CAM !!']
                    ]);
                }
            }
            
            
     

            $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('middle_name').' '.$request->input('last_name')));

            $user_data = 
                    [
                        'user_type'     =>'client',
                        'parent_id'     =>$business_id,
                        'first_name'    =>ucwords($request->input('first_name')),
                        'middle_name'    =>$request->input('middle_name')!=NULL && $request->input('middle_name')!='' ?  ucwords($request->input('middle_name')) : NULL,
                        'last_name'     =>$request->input('last_name')!=NULL && $request->input('last_name')!='' ? ucwords($request->input('last_name')) : NULL,
                        'name'          =>ucwords($name),
                        'email'         =>$request->input('email'),
                        'password'      =>$hashed_random_password,
                        'phone'         =>$phone,
                        'phone_code'    => $request->primary_phone_code,
                        'phone_iso'    => $request->primary_phone_iso,
                        'company_logo'  =>$company_logo,
                        'company_logo_file_platform'    => $company_logo_file_platform,
                        // 'coc_revenue_category'       =>$request->revenue,
                        'created_by'    =>Auth::user()->id,
                        'created_at'    =>date('Y-m-d H:i:s')
                    ];

            $user = User::create($user_data);
        
            //  $kams=  $request->kams;
            // //  dd($kams);
            //  foreach($kams as $kam){

            DB::table('wallets')->insert([
                'business_id'  =>  $user->id,
                'user_id'   =>   $user->id,
                'created_at'    => date('Y-m-d H:i:s')
            ]);
                
           
            //Primary KAM
            if ($request->user) {
                $key_manager =
                        [
                            'business_id'  => $user->id,
                            'user_id'       => $request->user,
                            'customer_id'  => $user->id,
                            'is_primary' =>'1',
                            'status' => '1',
                            'created_at'=> date('Y-m-d H:i:s'),
                            'created_by' => Auth::user()->id
                        ];
    
                $kmr = KeyAccountManager::create($key_manager);

            }

            //Secondary KAM
            if ($request->secondary) {
                $secondary_key_manager =
                [
                    'business_id'  => $user->id,
                    'user_id'       => $request->secondary,
                    'customer_id'  => $user->id,
                    'is_primary' =>'0',
                    'status' => '1',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id

                ];
                
                $kmr_sec = KeyAccountManager::create($secondary_key_manager);
            }

            //Additional KAM
            if($request->input('cam')!=NULL && count($request->cam) > 0)
            {
                foreach ($request->input('cam') as $value) 
                {
                    $kam_addinal = 
                    [
                        'business_id'   =>$user->id,
                        'user_id'       => $value,
                        'customer_id'  => $user->id,
                        'is_primary' =>'2',
                        'status' => '1',
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => Auth::user()->id
                    ];
                
                    $kmr_addi = KeyAccountManager::create($kam_addinal);
            
                }
                
            }

            $countries=DB::table('countries')->where(['id'=>$request->input('country_id')])->first();

            $states=DB::table('states')->where(['id'=>$request->input('state_id')])->first();

            $cities=DB::table('cities')->where(['id'=>$request->input('city_id')])->first();

            if($request->has('spoke_name'))
            {
                if(count($request->spoke_name)>0)
                {
                    foreach($request->spoke_name as $name)
                    {
                        $client_spokeman[]=$name;
                    }
                }
            }

            //insert business info
            $b_data = 
            [
                'business_id'           =>$user->id,
                'company_name'          =>$request->input('company'),
                'company_short_name'     =>$request->input('company_short_name'),
                'address_line1'         =>$request->input('address'),
                'zipcode'               =>$request->input('pincode'),
                'city_id'               =>$request->input('city_id'),
                'state_id'              =>$request->input('state_id'),
                'country_id'            =>$request->input('country_id'),
                'country_name'          =>$countries->name,
                'city_name'             =>$cities->name,
                'state_name'            =>$states->name,
                'email'                 =>$request->input('business_email'),
                'phone'                 => $business_phone,
                'phone_code'            => $request->primary_phone_code2,
                'phone_iso'             => $request->primary_phone_iso2,
                'gst_number'            => $request->input('gst_number'),
                'gst_attachment'        => $gst_attachment,
                'gst_attachment_file_platform'  => $gst_attachment_file_platform,
                'gst_exempt'            => $gst_exempt,
                'is_gst_verified'       => $is_gst_verified,
                'tin_number'            => $request->input('tin_number'),
                'hr_name'               => $request->input('hr_name'),
                'work_order_date'       => date('Y-m-d',strtotime($request->input('contract_start_date'))),
                'work_operating_date'   => date('Y-m-d',strtotime($request->input('contract_end_date'))),
                'billing_detail'        => $request->input('billing_detail'),
                'pan_number'            => $request->input('pan_number'),
                'is_pan_verified'       => $is_pan_verified,
                'contract_signed_by'    => $request->input('contract_signed_by'),
                'website'               => $request->input('website'),
                'department'            => $request->input('department'),
                'client_spokeman'       => count($client_spokeman) > 0 ? json_encode($client_spokeman) : NULL,
                'created_at'            => date('Y-m-d H:i:s')
            ];
            UserBusiness::create($b_data);
            // DB::table('user_businesses')->insertGetId($b_data);

            $user_business = DB::table('user_businesses')->where(['business_id'=>$user->id])->first();

            $u_id = str_pad($user->id, 10, "0", STR_PAD_LEFT);
            $display_id = trim(strtoupper(str_replace(array(' ','-'),'',substr($user_business->company_name,0,4)))).'-'.$u_id;

            DB::table('users')->where('id',$user->id)->update([
                'display_id' => $display_id
            ]);
            //contact info
            //owner contact
            $b_data = 
            [
                'business_id'   =>$user->id,
                'contact_type'  =>'owner',
                'designation'   =>$request->input('owner_designation'),
                'first_name'    =>$request->input('owner_first_name')!=NULL && $request->input('owner_first_name')!='' ? ucwords($request->input('owner_first_name')) : NULL,
                'middle_name'    =>$request->input('owner_middle_name')!=NULL && $request->input('owner_middle_name')!='' ? ucwords($request->input('owner_middle_name')) : NULL,
                'last_name'     =>$request->input('owner_last_name')!=NULL && $request->input('owner_last_name')!='' ? ucwords($request->input('owner_last_name')) : NULL,
                'email'         =>$request->input('owner_email'),
                'phone'         =>$owner_phone,
                'phone_code'    => $request->primary_phone_code3,
                'phone_iso'     => $request->primary_phone_iso3,
                'landline_number'=>$request->input('owner_landline_number'),
                'created_at'    => date('Y-m-d H:i:s')
            ];
            UserBusinessContact::create($b_data);
            // DB::table('user_business_contacts')->insertGetId($b_data);
            
            //dealing officer
            $b_data = 
            [
                'business_id'   =>$user->id,
                'contact_type'  =>'dealing_officer',
                'designation'   =>$request->input('dealing_designation'),
                'first_name'    =>$request->input('dealing_first_name')!=NULL && $request->input('dealing_first_name')!='' ? ucwords($request->input('dealing_first_name')) : NULL,
                'middle_name'    =>$request->input('dealing_middle_name')!=NULL && $request->input('dealing_middle_name')!='' ? ucwords($request->input('dealing_middle_name')) : NULL,
                'last_name'     =>$request->input('dealing_last_name')!=NULL && $request->input('dealing_last_name')!='' ? ucwords($request->input('dealing_last_name')) : NULL,
                'email'         =>$request->input('dealing_email'),
                'phone'         =>$dealing_phone,
                'phone_code'    => $request->primary_phone_code4,
                'phone_iso'     => $request->primary_phone_iso4,
                'landline_number'=>$request->input('dealing_landline_number'),
                'created_at'    => date('Y-m-d H:i:s')
            ];
            
            UserBusinessContact::create($b_data);

            //acount officer
            // if($request->input('account_first_name') != "" && $request->input('account_email') !=""){
                
                $b_data = 
                [
                    'business_id'   =>$user->id,
                    'contact_type'  =>'account_officer',
                    'designation'   =>$request->input('account_designation'),
                    'first_name'    =>$request->input('account_first_name')!=NULL && $request->input('account_first_name')!='' ? ucwords($request->input('account_first_name')) : NULL,
                    'middle_name'    =>$request->input('account_middle_name')!=NULL && $request->input('account_middle_name')!='' ? ucwords($request->input('account_middle_name')) : NULL,
                    'last_name'     =>$request->input('account_last_name')!=NULL && $request->input('account_last_name')!='' ? ucwords($request->input('account_last_name')) : NULL,
                    'email'         =>$request->input('account_email'),
                    'phone'         =>$account_phone,
                    'phone_code'    => $request->primary_phone_code5,
                    'phone_iso'     => $request->primary_phone_iso5,
                    'landline_number'=>$request->input('account_landline_number'),
                    'created_at'     => date('Y-m-d H:i:s')
                ];

                UserBusinessContact::create($b_data);

            // }

            if(isset($request->input('type')[$i]))
            {
            
                foreach ($request->input('type') as $value) 
                {
                    $b_data = 
                    [
                        'business_id'   =>$user->id,
                        'contact_type'  =>$request->input('type')[$i],
                        'designation'   =>$request->input('add_designation')[$i],
                        'first_name'    =>$request->input('add_first_name')[$i]!=NULL && $request->input('add_first_name')[$i]!='' ? ucwords($request->input('add_first_name')[$i]) : NULL,
                        'middle_name'    =>$request->input('add_middle_name')[$i]!=NULL && $request->input('add_middle_name')[$i]!='' ? ucwords($request->input('add_middle_name')[$i]) : NULL,
                        'last_name'     =>$request->input('add_last_name')[$i]!=NULL && $request->input('add_last_name')[$i]!='' ? ucwords($request->input('add_last_name')[$i]) : NULL,
                        'email'         =>$request->input('add_email')[$i],
                        'phone'         =>$request->input('add_phone')[$i],
                        'landline_number'=>$request->input('add_landline_number')[$i],
                        'created_at'     => date('Y-m-d H:i:s')
                    ];
                
                    $i++;
                    UserBusinessContact::create($b_data);
            
                }
            }

            //contract files
            if($request->has('fileID'))
            {
                foreach ($request->input('fileID') as $value) 
                {
                    $file_data = 
                    [
                        'business_id' =>$user->id,
                        'is_temp'     => '0'
                    ];
                
                    DB::table('user_business_attachments')->where(['id'=>base64_decode($value)])->update($file_data);
                }
            }

            //Update business ID 
            DB::table('users')->where(['id'=>$user->id])->update(['business_id'=>$user->id,'is_business_data_completed'=>'1']);
            // Default Report template selected
            $report_data=[
                'parent_id' => Auth::user()->parent_id,
                'business_id' =>$business_id,
                'coc_id' => $user->id, 
                'status' => 'enable',
                'template_type' =>'1',
                'created_at' => date('Y-m-d H:i:s'),
            ];
            DB::table('report_add_page_statuses')->insert($report_data);
            $pass_user= DB::table('users')->where(['id'=>$user->id])->first();
            $pass_store=[
                'business_id'   =>$pass_user->business_id,
                'user_id'   =>$pass_user->id,
                'parent_id' =>$pass_user->parent_id,
                'email' =>$pass_user->email,
                'password' => $pass_user->password,
            ];
            DB::table('password_logs')->insert($pass_store);
            //     if (Auth::user()->user_type == 'customer') {
            //         # code...
            //        $admin_email = Auth::user()->email;
            //        $admin_name = Auth::user()->first_name;
            //        //send email to customer
            //        $email = $admin_email;
            //        $name  = $admin_name;
            //        $candidate_name = $request->input('first_name');
            //        $msg = "New JAF Filling Task Created with candidate name";
            //        $sender = DB::table('users')->where(['id'=>$business_id])->first();
            //        $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
    
            //        Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
            //              $message->to($email, $name)->subject
            //                ('myBCD System - Notification for JAF Filling Task');
            //              $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
            //        });
            //    }
            //    else
            //    {
    
            //      $login_user = Auth::user()->business_id;
            //      $user= User::where('id',$login_user)->first();
            //      $admin_email = $user->email;
            //      $admin_name = $user->first_name;
            //      //send email to customer
            //      $email = $admin_email;
            //      $name  = $admin_name;
            //      $candidate_name = $request->input('first_name');
            //      $msg = "New JAF Filling Task Created with candidate name";
            //      $sender = DB::table('users')->where(['id'=>$business_id])->first();
            //      $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
    
            //      Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
            //            $message->to($email, $name)->subject
            //              ('myBCD System - Notification for JAF Filling Task');
            //            $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
            //      });
            //    }
    
            //     //  $kams  = KeyAccountManager::where('business_id',$request->input('customer'))->get();
            //    $primary= $request->user;
            //    $secondary = $request->secondary;
            //      if ($primary) {
            //     //    foreach ($kams as $kam) {
    
            //          $user= User::where('id',$primary)->first();
                    
            //          $email = $user->email;
            //          $name  = $user->name;
            //          $candidate_name = $request->input('first_name');
            //          $msg = "New JAF Filling Task Created with candidate name";
            //          $sender = DB::table('users')->where(['id'=>$business_id])->first();
            //          $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
    
            //          Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
            //                $message->to($email, $name)->subject
            //                  ('myBCD System - Notification JAF Filling Task');
            //                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
            //          });
    
            //     //    }
                
            //      }
                
            //      if ($secondary) {
            //         //    foreach ($kams as $kam) {
        
            //              $user= User::where('id',$secondary)->first();
                        
            //              $email = $user->email;
            //              $name  = $user->name;
            //              $candidate_name = $request->input('first_name');
            //              $msg = "New JAF Filling Task Created with candidate name";
            //              $sender = DB::table('users')->where(['id'=>$business_id])->first();
            //              $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
        
            //              Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
            //                    $message->to($email, $name)->subject
            //                      ('myBCD System - Notification for JAF Filling Task');
            //                    $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
            //              });
        
            //         //    }
                    
            //          }

            //send email to customer
            $email = $request->input('email');
            $name  = $request->input('first_name');
            $sender = DB::table('users')->where(['id'=>$business_id])->first();

            $data  = array('name'=>$name,'email'=>$email,'password'=>$randomPassword,'sender'=>$sender);

            EmailConfigTrait::emailConfig();
                //get Mail config data
                    //   $mail =null;
                    $mail= Config::get('mail');
                    // dd($mail['from']['address']);

            if (count($mail)>0) {
                Mail::send(['html'=>'mails.account-info'], $data, function($message) use($email,$name,$mail) {
                    $message->to($email, $name)->subject
                    ('myBCD System - Your account credential');
                    $message->from($mail['from']['address'],$mail['from']['name']);
                });
            }
            else
            {
                Mail::send(['html'=>'mails.account-info'], $data, function($message) use($email,$name) {
                    $message->to($email, $name)->subject
                        ('myBCD System - Your account credential');
                    $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                });
            }

            DB::commit();

            // return redirect()
            //     ->route('/customers')
            //     ->with('success', 'Customer created successfully.');

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

    public function storeStep(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $user_id = Auth::user()->id;

        $company_logo=NULL;
        $gst_attachment=NULL;
        $is_gst_verified=0;
        $is_pan_verified=0;
        $client_spokeman=[];

        $session_id = NULL;

        $prev_session_status  = 0;

        $step = 1;

        $step_1_arr=[];
        $step_2_arr=[];
        $step_3_arr=[];
        $step_4_arr=[];
        $step_5_arr=[];

        $step_err=[];

        DB::beginTransaction();
        try
        {
            $step = $request->step;

            if(session()->exists('board_session_id'))
            {
                $session_id = session()->get('board_session_id');
            }
            else
            {
                session()->put('board_session_id',Str::random(30));
    
                $session_id = session()->get('board_session_id');
            }

            if($step==1)
            {

                $rules=[
                    'first_name'   => 'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
                    'middle_name'   => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
                    'last_name'     => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
                    'email'        => 'required|email:rfc,dns|unique:users',
                    'phone'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
                ];
        
                $customMessages = [
                    'email.unique'  =>'Email id has already been taken',
                    'phone.regex' => 'Phone Number Must be 10-digit Number !!',
                    'phone.min' => 'Phone Number Must be 10-digit Number !!',
                    'phone.max' => 'Phone Number Must be 10-digit Number !!',
                ];
        
                $validator = Validator::make($request->all(), $rules,$customMessages);
                
                if ($validator->fails()){
                    return response()->json([
                        'success' => false,
                        'error_type'=> 'validation',
                        'errors' => $validator->errors()
                    ]);
                }
        
                $phone = preg_replace('/\D/', '', $request->input('phone'));

                if(strlen($phone)!=10)
                {
                    // return back()->withInput()->withErrors(['phone'=>['Phone Number Must be 10-digit Number !!']]);
                    return response()->json([
                        'success' => false,
                        'custom'=>'yes',
                        'error_type'=> 'validation',
                        'errors' => ['phone'=>'Phone Number must be 10-digit Number !!']
                    ]);
                }

                $randomPassword = Str::random(10);
                $hashed_random_password = Hash::make($randomPassword);
                //
                if($request->has('password') && !empty($request->input('password')) ){
                    $randomPassword = $request->input('password');
                    $hashed_random_password = Hash::make($request->input('password'));
                }

                // Store the Step 1 Data
                $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('middle_name').' '.$request->input('last_name')));
                //$name = $request->input('middle_name') != NULL ? $request->input('first_name').' '.$request->input('middle_name').' '.$request->input('last_name') : $request->input('first_name').' '.$request->input('last_name');

                $step_1_arr = [
                    'first_name' => $request->input('first_name'),
                    'middle_name' => $request->input('middle_name'),
                    'last_name' => $request->input('last_name'),
                    'name'      => $name,
                    'email'         =>$request->input('email'),
                    'password'      =>$request->input('password'),
                    'hash_password'      =>$hashed_random_password,
                    'phone'         =>$phone,
                    'phone_code'    => $request->primary_phone_code,
                    'phone_iso'    => $request->primary_phone_iso,
                ];

                $user_b = DB::table('user_onboardings')->where(['parent_id'=>$business_id,'status'=>'draft','user_type'=>'client'])->first();

                if($user_b!=NULL)
                {
                    DB::table('user_onboardings')->where(['id'=>$user_b->id])->update([
                        'session_id' => $session_id,
                        'step_1' => count($step_1_arr)>0 ? json_encode($step_1_arr) : NULL,
                        'updated_by' => $user_id,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                else
                {
                    DB::table('user_onboardings')->insert([
                        'parent_id' => $business_id,
                        'session_id' => $session_id,
                        'step_1' => count($step_1_arr)>0 ? json_encode($step_1_arr) : NULL,
                        'user_type' => 'client',
                        'status' => 'draft',
                        'created_by' => $user_id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }

                DB::commit();
                return response()->json([
                    'success' =>true,
                    'session_status' => $prev_session_status
                ]);

            }
            else if($step==2)
            {
                $rules=[
                    'address'      => 'required',
                    'pincode'      => 'required|numeric|digits:6',
                    'country_id'    => 'required',
                    'city_id'      => 'required',
                    'state_id'     => 'required',
                    'company'      => 'required',
                    'company_logo' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'business_email'            => 'nullable|email:rfc,dns',
                    'business_phone_number'     => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
                    'gst_number'                => 'required_without:gst_exempt|nullable|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/|min:15|max:15',
                    'gst_attachment'            => 'required|mimes:jpg,jpeg,png,jpg,gif,svg,pdf|max:200000',
                    'contract_signed_by'        => 'required',
                    'hr_name'                   => 'required|regex:/^[a-zA-Z ]+$/u|min:2|max:255',
                    'contract_start_date'           => 'required|date',
                    'contract_end_date'       => 'required|date|after:contract_start_date',
                    'pan_number'                => 'required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/|min:10|max:11',
                    'tin_number'    => 'nullable|numeric|digits:11',
                ];
        
                $customMessages = [
                    'business_phone_number.regex' => 'Business Phone Number Must be 10-digit Number !!',
                    'business_phone_number.min' => 'Business Phone Number Must be 10-digit Number !!',
                    'business_phone_number.max' => 'Business Phone Number Must be 10-digit Number !!',
                    'country_id.required' =>'The country field is required',
                    'state_id.required' =>'The state field is required',
                    'city_id.required' =>'The city field is required',
                    'spoke_man.*.required' => 'Spokeman Name Field is Required',
                    'spoke_man.*.regex' => 'Spokeman Name Field must be String',
                    'spoke_man.*.min' => 'Spokeman Name Field has atleast 1 character',
                    'spoke_man.*.max' => 'Spokeman Name Field has atleast maximum 255 character allowed'
                ];
        
                $validator = Validator::make($request->all(), $rules,$customMessages);
                
                if ($validator->fails()){
                    return response()->json([
                        'success' => false,
                        'error_type'=> 'validation',
                        'errors' => $validator->errors()
                    ]);
                }

                $business_phone = preg_replace('/\D/', '', $request->input('business_phone_number'));

                if($request->input('business_phone_number') != "" )
                {
                    if (strlen($business_phone)!=10) {
                    
                    // return back()->withInput()->withErrors(['business_phone_number'=>['Business Phone Number Must be 10-digit Number !!']]);
                        return response()->json([
                            'success' => false,
                            'custom'=>'yes',
                            'error_type'=> 'validation',
                            'errors' => ['business_phone_number'=>'Business Phone Number must be 10-digit Number !!']
                        ]);
                    }
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
                                return response()->json([
                                    'success' => false,
                                    'custom'=>'yes',
                                    'errors' => ['gst_number'=>'It seems like GST number is not valid!']
                                ]);
                            }
                    }
                }

                // Verification of PAN Number

                //check first into master table
                $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$request->input('pan_number')])->first();

                if($master_data !=null){
                    $is_pan_verified=1;
                }
                else
                {
                    //check from live API
                    $api_check_status = false;
                    // Setup request to send json via POST
                    $data = array(
                        'id_number'    => $request->input('pan_number'),
                        'async' => true
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
                        $checkIDInDB= DB::table('pan_check_masters')->where(['pan_number'=>$request->input('pan_number')])->count();
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
                            
                            $is_pan_verified=1;
                        }

                    }else{
                        return response()->json([
                            'success' => false,
                            'custom'=>'yes',
                            'error_type'=> 'validation',
                            'errors' => ['pan_number'=>'It seems like PAN number is not valid!!']
                        ]);
                    }
                }

                if ($files = $request->file('company_logo')) 
                {
                    $destinationPath = public_path('uploads/company-logo/'); 
                    $logoImage = $request->file('company_logo')->getClientOriginalName();
                    $files->move($destinationPath, $logoImage);

                    $company_logo= $request->file('company_logo')->getClientOriginalName();
                }

                if ($files = $request->file('gst_attachment')) 
                {
                    $destinationPath = public_path('uploads/gst-file/'); 
                    $gstImage = $request->file('gst_attachment')->getClientOriginalName();
                    $files->move($destinationPath, $gstImage);

                    $gst_attachment= $request->file('gst_attachment')->getClientOriginalName();
                }

                $gst_exempt=0;
                if($request->has('gst_exempt') || $request->gst_exempt!=NULL)
                {
                    $gst_exempt=1;
                }

                $countries=DB::table('countries')->where(['id'=>$request->input('country_id')])->first();

                $states=DB::table('states')->where(['id'=>$request->input('state_id')])->first();

                $cities=DB::table('cities')->where(['id'=>$request->input('city_id')])->first();

                if($request->has('spoke_name'))
                {
                    if(count($request->spoke_name)>0)
                    {
                        foreach($request->spoke_name as $name)
                        {
                            $client_spokeman[]=$name;
                        }
                    }
                }
 
                // Store the Step 2 Data

                $step_2_arr = [
                    'company_name'          =>$request->input('company'),
                    'company_logo'          => $company_logo,
                    'address_line1'         =>$request->input('address'),
                    'zipcode'               =>$request->input('pincode'),
                    'city_id'               =>$request->input('city_id'),
                    'state_id'              =>$request->input('state_id'),
                    'country_id'            =>$request->input('country_id'),
                    'country_name'          =>$countries->name,
                    'city_name'             =>$cities->name,
                    'state_name'            =>$states->name,
                    'email'                 =>$request->input('business_email'),
                    'phone'                 => $business_phone,
                    'phone_code'            => $request->primary_phone_code2,
                    'phone_iso'             => $request->primary_phone_iso2,
                    'gst_number'            => $request->input('gst_number'),
                    'gst_attachment'        => $gst_attachment,
                    'gst_exempt'            => $gst_exempt,
                    'is_gst_verified'       => $is_gst_verified,
                    'tin_number'            => $request->input('tin_number'),
                    'hr_name'               => $request->input('hr_name'),
                    'work_order_date'       => date('Y-m-d',strtotime($request->input('contract_start_date'))),
                    'work_operating_date'   => date('Y-m-d',strtotime($request->input('contract_end_date'))),
                    'billing_detail'        => $request->input('billing_detail'),
                    'pan_number'            => $request->input('pan_number'),
                    'is_pan_verified'       => $is_pan_verified,
                    'contract_signed_by'    => $request->input('contract_signed_by'),
                    'website'               => $request->input('website'),
                    'type_of_facility'      => $request->input('type_of_facility'),
                    'department'            => $request->input('department'),
                    'client_spokeman'       => count($client_spokeman) > 0 ? $client_spokeman : NULL,
                ];

                $user_b = DB::table('user_onboardings')->where(['parent_id'=>$business_id,'status'=>'draft','user_type'=>'client'])->first();

                if($user_b!=NULL)
                {
                    if($user_b->step_1==NULL || count($step_err=json_decode($user_b->step_1,true))==0)
                    {
                        return response()->json([
                            'success' => false,
                            'error_type'=> 'message',
                            'message' => 'First Complete Your Step 1 Form !!'
                        ]);
                    }
                    else
                    {
                        DB::table('user_onboardings')->where(['id'=>$user_b])->update([
                            'session_id' => $session_id,
                            'step_2' => count($step_2_arr)>0 ? json_encode($step_2_arr) : NULL,
                            'updated_by' => $user_id,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
                else
                {
                    return response()->json([
                        'success' => false,
                        'error_type'=> 'message',
                        'message' => 'First Complete Your Step 1 Form !!'
                    ]);
                }

                DB::commit();
                return response()->json([
                    'success' =>true,
                    'session_status' => $prev_session_status
                ]);

            }
            else if($step==3)
            {
                dd(1);
            }

            dd(1);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }    
       
    }

    //show the form to Edit customer
    public function edit($id)
    {

        $customer_id = base64_decode($id);
    
        $customer = DB::table('users as u')
        ->select('u.*')
        ->where(['u.id'=>$customer_id])
        ->first();

        $business = DB::table('user_businesses as b')
        ->select('b.*')
        ->where(['b.business_id'=>$customer_id])
        ->first();

        $owner = DB::table('user_business_contacts as b')
        ->select('b.*')
        ->where(['b.business_id'=>$customer_id,'contact_type'=>'owner'])
        ->first();

        $dealing = DB::table('user_business_contacts as b')
        ->select('b.*')
        ->where(['b.business_id'=>$customer_id,'contact_type'=>'dealing_officer'])
        ->first();

        $account = DB::table('user_business_contacts as b')
        ->select('b.*')
        ->where(['b.business_id'=>$customer_id,'contact_type'=>'account_officer'])
        ->first();

        $type = DB::table('user_business_contacts as b')
        ->select('b.*')
        ->where(['b.business_id'=>$customer_id,'is_deleted'=>0])
        ->whereNotIn('contact_type',['owner','dealing_officer','account_officer'])
        ->get();

        // dd($type);

        $files = DB::table('user_business_attachments')
                    ->select('*')
                    ->where(['business_id'=>$customer_id,'is_deleted'=>0])
                    ->get();

        //print_r($type);die;

        $countries  = DB::table('countries')->get();
        $state          = DB::table('states')->where('country_id',$business->country_id)->get();
        $cities          = DB::table('cities')->where('state_id',$business->state_id)->get();
        $users          =DB::table('users')->where('user_type','user')->where('business_id',Auth::user()->business_id)->get();
        $kams = DB::table('key_account_managers')->where(['customer_id'=>$customer_id,'is_primary'=>'1'])->first();
        $secondary_kam =DB::table('key_account_managers')->where(['customer_id'=>$customer_id,'is_primary'=>'0'])->first();
        $additional_kam =DB::table('key_account_managers')->where(['customer_id'=>$customer_id,'is_primary'=>'2'])->get();
        // dd($kams);
        return view('admin.customers.edit', compact('customer','business','owner','dealing','account','countries','type','state','cities','files','users','kams','secondary_kam','additional_kam'));
    }

    //update data 
    public function update(Request $request)
    {
       $business_id = $request->input('customer_id');
        $is_gst_verified=0;
        $is_pan_verified=0;
        $logoImage=NULL;
        $gst_attachment=NULL;
        $client_spokeman = [];
           // Form validation
        //    $this->validate($request, [
        //     'first_name'   => 'required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
        //     'last_name'     => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
        //     'phone'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
        //     'address'      => 'required',
        //     'pincode'      => 'required|numeric|digits:6',
        //     'city_id'      => 'required',
        //     'state_id'     => 'required',
        //     'country_id'   => 'required',
        //     'company'      => 'required',
        //     'company_logo' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        //     'digital_signature' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        //     'business_email'            => 'required|email',
        //     'business_phone_number'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
        //     'gst_number'                => 'required|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/|min:15|max:15',
        //     'contract_signed_by'        => 'required',
        //     'hr_name'                   => 'required|regex:/^[a-zA-Z ]+$/u|min:2|max:255',
        //     'work_order_date'           => 'required|date',
        //     'work_operating_date'       => 'required|date|after:work_order_date',
        //     'pan_number'                => 'required||regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/|min:11|max:11',
        //     'owner_first_name'          => 'required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
        //     'owner_last_name'           => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
        //     'owner_email'               => 'required|email',
        //     'owner_phone_number'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
        //     'owner_designation'         => 'required',
        //     'dealing_first_name'        => 'required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
        //     'dealing_last_name'         => 'nullable|regex:/^[a-zA-Z]+$/u|min:2|max:255',
        //     'dealing_email'             => 'required|email',
        //     'dealing_phone_number'      => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
        //     'dealing_designation'       => 'required',
        //     'user'                      => 'required',
        //     'secondary'                 => 'required',
        //     'account_first_name'        => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
        //     'account_last_name'         => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
        //     'account_email'             => 'nullable|email',
        //     'account_phone_number'      => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
        //     'dealing_landline_number'   =>'nullable|numeric',
        //     'account_landline_number'=>'nullable|numeric',
        //     'owner_landline_number'=>'nullable|numeric',
        //     'tin_number'    => 'nullable|numeric|digits:11'
        //    ],
        //    [
        //     'phone.regex' => 'Phone Number Must be 10-digit Number !!',
        //     'phone.min' => 'Phone Number Must be 10-digit Number !!',
        //     'phone.max' => 'Phone Number Must be 10-digit Number !!',
        //     'business_phone_number.regex' => 'Business Phone Number Must be 10-digit Number !!',
        //     'business_phone_number.min' => 'Business Phone Number Must be 10-digit Number !!',
        //     'business_phone_number.max' => 'Business Phone Number Must be 10-digit Number !!',
        //     'owner_phone_number.regex' => 'Owner Phone Number Must be 10-digit Number !!',
        //     'owner_phone_number.min' => 'Owner Phone Number Must be 10-digit Number !!',
        //     'owner_phone_number.max' => 'Owner Phone Number Must be 10-digit Number !!',
        //     'dealing_phone_number.regex' => 'Dealing Phone Number Must be 10-digit Number !!',
        //     'dealing_phone_number.min' => 'Dealing Phone Number Must be 10-digit Number !!',
        //     'dealing_phone_number.max' => 'Dealing Phone Number Must be 10-digit Number !!',
        //     'account_phone_number.regex' => 'Account Phone Number Must be 10-digit Number !!',
        //     'account_phone_number.min' => 'Account Phone Number Must be 10-digit Number !!',
        //     'account_phone_number.max' => 'Account Phone Number Must be 10-digit Number !!'
        //    ]
        // );

        $rules= [
            'first_name'   => 'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'middle_name'     => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'last_name'     => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'email'        => 'required|email:rfc,dns',
            'phone'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
            'address'      => 'required',
            'pincode'      => 'required|integer|digits:6',
            'city_id'      => 'required',
            'company_logo' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'state_id'     => 'required',
            'company'      => 'required',
            'company_short_name'      => 'required|min:1|max:30',
            // 'kams'          =>'required',
            'business_email'            => 'nullable|email:rfc,dns',
            'business_phone_number'     => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
            'gst_number'                => 'required_without:gst_exempt|nullable|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/|min:15|max:15',
            'gst_attachment'            => 'nullable|mimes:jpg,jpeg,png,jpg,gif,svg,pdf|max:200000',
            'contract_signed_by'        => 'required',
            'hr_name'                   => 'required|regex:/^[a-zA-Z ]+$/u|min:2|max:255',
            'contract_start_date'           => 'required|date',
            'contract_end_date'       => 'required|date|after:contract_start_date',
            'pan_number'                => 'required||regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/|min:10|max:11',
            'owner_first_name'          => 'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'owner_middle_name'           => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'owner_last_name'           => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'owner_email'               => 'required|email:rfc,dns',
            'owner_phone_number'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
            'owner_designation'         => 'required',
            'dealing_first_name'        => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'dealing_middle_name'       => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'dealing_last_name'         => 'nullable|regex:/^[a-zA-Z]+$/u|min:2|max:255',
            'dealing_email'             => 'nullable|email:rfc,dns',
            'dealing_phone_number'      => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
            'dealing_designation'       => 'nullable',
            'user'                      => 'required',
            'secondary'                 => 'required|different:user',
            'account_first_name'        => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'account_middle_name'       => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'account_last_name'         => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'account_email'             => 'nullable|email:rfc,dns',
            'account_phone_number'      => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
            'dealing_landline_number'   =>'nullable|numeric',
            'account_landline_number'=>'nullable|numeric',
            'owner_landline_number'=>'nullable|numeric',
            'tin_number'    => 'nullable|integer|digits:11',
            'type.*' => 'sometimes|required|min:1',
            'add_first_name.*' => 'sometimes|required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'add_middle_name.*'       => 'sometimes|nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'add_last_name.*'         => 'sometimes|nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'add_email.*'             => 'sometimes|nullable|email:rfc,dns',
            'add_phone.*'      => 'sometimes|nullable|regex:/^[0-9]{10}/',
            'add_landline_number.*'   =>'sometimes|nullable|numeric',
            'spoke_name.*' => 'sometimes|required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            // 'revenue'       => 'required'
            'cam.*'     =>    'sometimes|required',
         ];
        
         $customMessages = [
            'email.unique'  =>'Email id has already been taken',
            'secondary.different' => 'Primary and Secondary CAM must be different',
            'phone.regex' => 'Phone Number Must be 10-digit Number !!',
            'phone.min' => 'Phone Number Must be 10-digit Number !!',
            'phone.max' => 'Phone Number Must be 10-digit Number !!',
            'business_phone_number.regex' => 'Business Phone Number Must be 10-digit Number !!',
            'business_phone_number.min' => 'Business Phone Number Must be 10-digit Number !!',
            'business_phone_number.max' => 'Business Phone Number Must be 10-digit Number !!',
            'owner_phone_number.regex' => 'Owner Phone Number Must be 10-digit Number !!',
            'owner_phone_number.min' => 'Owner Phone Number Must be 10-digit Number !!',
            'owner_phone_number.max' => 'Owner Phone Number Must be 10-digit Number !!',
            'dealing_phone_number.regex' => 'Dealing Phone Number Must be 10-digit Number !!',
            'dealing_phone_number.min' => 'Dealing Phone Number Must be 10-digit Number !!',
            'dealing_phone_number.max' => 'Dealing Phone Number Must be 10-digit Number !!',
            'account_phone_number.regex' => 'Account Phone Number Must be 10-digit Number !!',
            'account_phone_number.min' => 'Account Phone Number Must be 10-digit Number !!',
            'account_phone_number.max' => 'Account Phone Number Must be 10-digit Number !!',
            // 'revenue.required'      => 'Select at least one company revenue category ',
            'country_id.required' =>'The country field is required',
            'state_id.required' =>'The state field is required',
            'city_id.required' =>'The city field is required',
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
            'add_landline_number.*.numeric' => 'Additional Landline Number Must be Numeric',
            'spoke_name.*.required' => 'Spokeman Name Field is Required',
            'spoke_name.*.regex' => 'Spokeman Name Field must be String',
            'spoke_name.*.min' => 'Spokeman Name Field has atleast 1 character',
            'spoke_name.*.max' => 'Spokeman Name Field has atleast maximum 255 character allowed',
            'cam.*.required' => 'Additional CAM is Required',
        ];

         $validator = Validator::make($request->all(), $rules,$customMessages);
          
         if ($validator->fails()){
             return response()->json([
                 'success' => false,
                 'errors' => $validator->errors()
             ]);
         }
        DB::beginTransaction();
        try{

            $business=DB::table('user_businesses')->where('business_id',$business_id)->first();

            // if($business->gst_attachment==NULL || $business->gst_attachment=='')
            // {
            //     $rules = [
            //         'gst_attachment'   => 'required|mimes:jpg,jpeg,png,jpg,gif,svg,pdf|max:200000',
            //     ];

            //     $validator = Validator::make($request->all(), $rules);
            
            //     if ($validator->fails()){
            //         return response()->json([
            //             'success' => false,
            //             'errors' => $validator->errors()
            //         ]);
            //     }
            // }

            $phone = preg_replace('/\D/', '', $request->input('phone'));
            $business_phone = preg_replace('/\D/', '', $request->input('business_phone_number'));
            $owner_phone = preg_replace('/\D/', '', $request->input('owner_phone_number'));
            $dealing_phone = preg_replace('/\D/', '', $request->input('dealing_phone_number'));
            $account_phone = preg_replace('/\D/', '', $request->input('account_phone_number'));

            if(strlen($phone)!=10)
            {
                // return back()->withInput()->withErrors(['phone'=>['Phone Number Must be 10-digit Number !!']]);
                return response()->json([
                    'success' => false,
                    'custom'=>'yes',
                    'errors' => ['phone'=>'Phone Number must be 10-digit Number !!']
                  ]);
            }
            else if($request->input('business_phone_number') != "" )
            {
                if ( strlen($business_phone)!=10) {
                   
                // return back()->withInput()->withErrors(['business_phone_number'=>['Business Phone Number Must be 10-digit Number !!']]);
                    return response()->json([
                        'success' => false,
                        'custom'=>'yes',
                        'errors' => ['business_phone_number'=>'Business Phone Number must be 10-digit Number !!']
                    ]);
                }
            }
            else if($request->input('owner_phone_number') != ""  )
            { 
                if (strlen($owner_phone)!=10) {
                # code...
            
                // return back()->withInput()->withErrors(['owner_phone_number'=>['Owner Phone Number Must be 10-digit Number !!']]);
                return response()->json([
                    'success' => false,
                    'custom'=>'yes',
                    'errors' => ['owner_phone_number'=>'Owner Phone Number must be 10-digit Number !!']
                  ]);
                }
            }
            else if($request->input('dealing_phone_number') != "" )
            {
                if (strlen($dealing_phone)!=10) {
                   
                    // return back()->withInput()->withErrors(['dealing_phone_number'=>['Dealing Phone Number Must be 10-digit Number !!']]);
                    return response()->json([
                        'success' => false,
                        'custom'=>'yes',
                        'errors' => ['dealing_phone_number'=>'Dealing Phone Number must be 10-digit Number !!']
                    ]);
                }
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
                            'async'         => true
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
                            return response()->json([
                                'success' => false,
                                'custom'=>'yes',
                                'errors' => ['gst_number'=>'It seems like GST number is not valid!']
                            ]);
                        }
                }
            }

            //Verification of PAN Number

            //check first into master table
            $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$request->input('pan_number')])->first();

            if($master_data !=null){
                $is_pan_verified=1;
            }
            else
            {
                //check from live API
                $api_check_status = false;
                // Setup request to send json via POST
                $data = array(
                    'id_number'    => $request->input('pan_number'),
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
                    $checkIDInDB= DB::table('pan_check_masters')->where(['pan_number'=>$request->input('pan_number')])->count();
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
                        
                        $is_pan_verified=1;
                    }

                }else{
                    return response()->json([
                        'success' => false,
                        'custom'=>'yes',
                        'errors' => ['pan_number'=>'It seems like PAN number is not valid!!']
                    ]);
                }
            }
            
            $i=0;

            $t = DB::table('users as u')
                ->select('u.company_logo','u.company_logo_file_platform','u.digital_signature','u.digital_signature_file_platform','ub.gst_attachment','ub.gst_attachment_file_platform')
                ->join('user_businesses as ub','u.id','=','ub.business_id')
                ->where(['u.id'=>$business_id])
                ->first();

            $logoImage= $t->company_logo;
            $company_logo_file_platform = $t->company_logo_file_platform;

            //print_r($_POST);die;
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

        
            //update data
            $business_id =  $request->input('customer_id');

            $s3_config = S3ConfigTrait::s3Config();

            if ($files = $request->file('company_logo')) 
            {
                $company_logo_file_platform = 'web';
                $destinationPath = public_path('uploads/company-logo/'); 
                $logoImage = date('Ymdhis').$request->file('company_logo')->getClientOriginalName();
                $customer=DB::table('users')->select('company_logo')->where('id',$business_id)->first();

                if($customer!=NULL && stripos($t->company_logo_file_platform,'web')!==false)
                {
                    $customer_img=$customer->company_logo;
                    if(File::exists(public_path().'/uploads/company-logo/'.$customer_img))
                    {
                        File::delete(public_path().'/uploads/company-logo/'.$customer_img);
                    } 
                }

                if($s3_config!=NULL)
                {
                    $company_logo_file_platform = 's3';

                    $path = 'uploads/company-logo/';

                    if(!Storage::disk('s3')->exists($path))
                    {
                        Storage::disk('s3')->makeDirectory($path,0777, true, true);
                    }

                    Storage::disk('s3')->put($path.$logoImage, file_get_contents($files));
                }
                else
                {
                    $files->move($destinationPath, $logoImage);
                }
            }

            $dsImage = NULL;
            $dsImage= $t->digital_signature;
            $digital_signature_file_platform = $t->digital_signature_file_platform;
            if ($files = $request->file('digital_signature')) 
            {
                $digital_signature_file_platform = 'web';
                $destinationPath = public_path('uploads/company-digital-signature/'); 
                $dsImage = date('Ymdhis').$request->file('digital_signature')->getClientOriginalName();
                $customer=DB::table('users')->select('digital_signature')->where('id',$request->customer_id)->first();

                    if($customer!=NULL && stripos($t->digital_signature_file_platform,'web')!==false)
                    {
                        $customer_img=$customer->digital_signature;
                        if(File::exists(public_path().'/uploads/company-digital-signature/'.$customer_img))
                        {
                            File::delete(public_path().'/uploads/company-digital-signature/'.$customer_img);
                        } 
                    }

                    if($s3_config!=NULL)
                    {
                        $digital_signature_file_platform = 's3';

                        $path = 'uploads/company-digital-signature/';

                        if(!Storage::disk('s3')->exists($path))
                        {
                            Storage::disk('s3')->makeDirectory($path,0777, true, true);
                        }

                        Storage::disk('s3')->put($path.$dsImage, file_get_contents($files));
                    }
                    else
                    {
                        $files->move($destinationPath, $dsImage);
                    }
                
            }

            $gst_attachment= $t->gst_attachment;
            $gst_attachment_file_platform = $t->gst_attachment_file_platform;

            if ($files = $request->file('gst_attachment')) 
            {
                $gst_attachment_file_platform = 'web';

                $destinationPath = public_path('uploads/gst-file/'); 
                $gstImage = time().'-'.$request->file('gst_attachment')->getClientOriginalName();
                $gst_attachment= $gstImage;
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

            $primary = $request->user;
            $secondary = $request->secondary;
            $cam = $request->cam;
            
            // Cam check unique validation
            if($request->input('cam')!=NULL && count($request->cam) > 0){
                foreach ($request->input('cam') as $value) 
                {
                    if($value == $primary){
                        return response()->json([
                            'success' => false,
                            'custom'=>'yes',
                            'errors' => ['all'=>'Please Select unique CAM !!']
                        ]);
                    }
                    if($value == $secondary){
                        return response()->json([
                            'success' => false,
                            'custom'=>'yes',
                            'errors' => ['all'=>'Please Select Defrent CAM !!']
                        ]);
                    }
                }
                //array unique multiple validation
                if(count(array_unique($cam))<count($cam))
                {
                    return response()->json([
                        'success' => false,
                        'custom'=>'yes',
                        'errors' => ['all'=>'Please Select Deferent CAM !!']
                    ]);
                }
            }

            //
            if($request->has('password') && !empty($request->input('password')) ){
                DB::table('users')->where(['id'=>$business_id])->update(['password'=>bcrypt($request->input('password'))]);
            }

            $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('middle_name').' '.$request->input('last_name')));

            // 
            $b_data = 
            [
                'first_name'    =>$request->input('first_name'),
                'middle_name'    =>$request->input('middle_name'),
                'last_name'     =>$request->input('last_name'),
                'name'          => $name,
                'phone'     =>$phone,
                'company_logo'  =>$logoImage,
                'company_logo_file_platform' => $company_logo_file_platform,
                'report_company_name'=>$request->input('report_company_name'),
                // 'coc_revenue_category'       =>$request->revenue,
                'digital_signature'=>$dsImage,
                'digital_signature_file_platform' => $digital_signature_file_platform,
                'updated_at'    => date('Y-m-d H:i:s')
            ];
        
            // DB::table('users')->where(['id'=>$business_id])->update($b_data);
            $update=  User::find($business_id);
            $update->update($b_data);

            // update Key account Manager
            $kam = DB::table('key_account_managers')->where(['customer_id'=>$business_id])->count();
            // dd($business_id);
            // $kams=  $request->kams;
            //  dd($kams);
            
            if ($kam == 0) {
                    //Primary KAM
                if ($request->user) {
                    $key_manager =
                    [
                        'business_id'  => $business_id,
                        'user_id'       => $request->user,
                        'customer_id'  => $business_id,
                        'is_primary' =>'1',
                        'status' => '1'
                    ];
                    
                    $kmr = KeyAccountManager::create($key_manager);

                }
                    
                //Secondary KAM
                if ($request->secondary) {
                    $secondary_key_manager =
                    [
                        'business_id'  => $business_id,
                        'user_id'       => $request->secondary,
                        'customer_id'  => $business_id,
                        'is_primary' =>'0',
                        'status' => '1'
                    ];
                    
                    $kmr_sec = KeyAccountManager::create($secondary_key_manager);
                    
                    }
            } 
            else {

                if ($request->user) {
                    $key_manager =
                    [
                        'business_id'  => $business_id,
                        'user_id'       => $request->user,
                        'customer_id'  => $business_id,
                        'is_primary' =>'1',
                        'status' => '1'
                    ];
                    
                    DB::table('key_account_managers')->where(['customer_id'=>$business_id,'is_primary'=>'1'])->update($key_manager);

                }
                
                //Secondary KAM
                $sec = DB::table('key_account_managers')->where(['customer_id'=>$business_id,'is_primary'=>'0'])->count();
                if ($sec==0) {
                if ($request->secondary) {
                    $secondary_key_manager =
                    [
                        'business_id'  => $business_id,
                        'user_id'       => $request->secondary,
                        'customer_id'  => $business_id,
                        'is_primary' =>'0',
                        'status' => '1'
                    ];
                    
                    KeyAccountManager::create($secondary_key_manager);
                    
                    }
                } else {
                    if ($request->secondary) {
                        $secondary_key_manager =
                        [
                            'business_id'  => $business_id,
                            'user_id'       => $request->secondary,
                            'customer_id'  => $business_id,
                            'is_primary' =>'0',
                            'status' => '1'
                        ];
                        
                        DB::table('key_account_managers')->where(['customer_id'=>$business_id,'is_primary'=>'0'])->update($secondary_key_manager);
                
                    }
        
                }

            }
            
            //additional cam update
            //$additional = DB::table('key_account_managers')->where(['customer_id'=>$business_id])->count();
            
            DB::table('key_account_managers')->where(['customer_id'=>$business_id])->where('is_primary','2')->delete();

          
            if($request->input('cam')!=NULL && count($request->cam) > 0)
            {
                foreach ($request->input('cam') as $value) 
                {
                    $kam_addinal = 
                    [
                        'business_id'   =>$business_id,
                        'user_id'       => $value,
                        'customer_id'  => $business_id,
                        'is_primary' =>'2',
                        'status' => '1',
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => Auth::user()->id
                    ];
                
                    $kmr_addi = KeyAccountManager::create($kam_addinal);
            
                }
                
            }
            

        
            $countries=DB::table('countries')->where(['id'=>$request->input('country_id')])->first();

            $states=DB::table('states')->where(['id'=>$request->input('state_id')])->first();

            $cities=DB::table('cities')->where(['id'=>$request->input('city_id')])->first();

            if($request->has('spoke_name'))
            {
                if(count($request->spoke_name)>0)
                {
                    foreach($request->spoke_name as $name)
                    {
                        $client_spokeman[]=$name;
                    }
                }
            }
    
            //update business info
            $b_data = 
            [
                'company_name'  =>$request->input('company'),
                'company_short_name'  =>$request->input('company_short_name'),
                'address_line1' =>$request->input('address'),
                'zipcode'       =>$request->input('pincode'),
                'city_id'       =>$request->input('city_id'),
                'state_id'      =>$request->input('state_id'),
                'country_id'    =>$request->input('country_id'),
                'country_name'    =>$countries->name,
                'city_name'     =>$cities->name,
                'state_name'    =>$states->name,
                'email'         =>$request->input('business_email'),
                'phone'         =>$business_phone,
                'phone_code'            => $request->primary_phone_code2,
                'phone_iso'             => $request->primary_phone_iso2,
                'gst_number'    =>$request->input('gst_number'),
                'gst_exempt'       => $gst_exempt,
                'gst_attachment'       => $gst_attachment,
                'gst_attachment_file_platform' => $gst_attachment_file_platform,
                'is_gst_verified'       => $is_gst_verified,
                'tin_number'    =>$request->input('tin_number'),
                'website'       =>$request->input('website'),
                'type_of_facility'      =>$request->input('type_of_facility'),
                'hr_name'               =>$request->input('hr_name'),
                'work_order_date'       => date('Y-m-d',strtotime($request->input('contract_start_date'))),
                'work_operating_date'   => date('Y-m-d',strtotime($request->input('contract_end_date'))),
                'billing_detail'        => $request->input('billing_detail'),
                'pan_number'            => $request->input('pan_number'),
                'is_pan_verified'       => $is_pan_verified,
                'billing_mode'          =>$request->input('billing_mode'),
                'contract_signed_by'    =>$request->input('contract_signed_by'),
                'website'               => $request->input('website'),
                'department'            => $request->input('department'),
                'client_spokeman'       => count($client_spokeman) > 0 ? json_encode($client_spokeman) : NULL,
                'updated_at'            => date('Y-m-d H:i:s')
            ];
            
            DB::table('user_businesses')->where(['business_id'=>$business_id])->update($b_data);

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
                'phone_code'            => $request->primary_phone_code3,
                'phone_iso'             => $request->primary_phone_iso3,
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
                'phone_code'            => $request->primary_phone_code4,
                'phone_iso'             => $request->primary_phone_iso4,
                'landline_number'=>$request->input('dealing_landline_number'),
                'updated_at'    => date('Y-m-d H:i:s')
            ];
        
            DB::table('user_business_contacts')->where(['business_id'=>$business_id,'contact_type'=>'dealing_officer'])->update($b_data);
            
            //acount officer
            $account_details=DB::table('user_business_contacts')->where(['business_id'=>$business_id,'contact_type'=>'account_officer'])->first();
            if($account_details!=NULL)
            {
                $b_data = 
                [
                    'designation'   =>$request->account_designation,
                    'first_name'    =>$request->account_first_name,
                    'middle_name'    =>$request->account_middle_name,
                    'last_name'     =>$request->account_last_name,
                    'email'         =>$request->account_email,
                    'phone'         =>$account_phone,
                    'phone_code'            => $request->primary_phone_code5,
                    'phone_iso'             => $request->primary_phone_iso5,
                    'landline_number'=>$request->account_landline_number,
                    'updated_at'     => date('Y-m-d H:i:s')
                ];
                
                DB::table('user_business_contacts')->where(['business_id'=>$business_id,'contact_type'=>'account_officer'])->update($b_data);
            }
            else
            {
                // if($request->account_first_name != "" && $request->account_email !=""){
                
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

                // }
            }
            // $b_data = 
            // [
            //     'designation'   =>$request->input('account_designation'),
            //     'first_name'    =>$request->input('account_first_name'),
            //     'last_name'     =>$request->input('account_last_name'),
            //     'email'         =>$request->input('account_email'),
            //     'phone'         =>$account_phone,
            //     'landline_number'=>$request->input('account_landline_number'),
            //     'updated_at'     => date('Y-m-d H:i:s')
            // ];
            
            // DB::table('user_business_contacts')->where(['business_id'=>$business_id,'contact_type'=>'account_officer'])->update($b_data);

            //

            DB::commit();
            // return redirect('/customers')
            //     ->with('success', 'Customer updated successfully.');
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

    public function deleteContactType(Request $request)
    {
        $type_id = base64_decode($request->type_id);

        DB::beginTransaction();
        try{
            if(Auth::check()){

                $is_deleted =  DB::table('user_business_contacts')
                ->where('id', $type_id)
                ->whereNotIn('contact_type',['owner','dealing_officer','account_officer'])
                ->update(['is_deleted' => 1,'deleted_at'=>date('Y-m-d H:i:s'),'deleted_by'=>Auth::user()->id]);
                
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

   

    public function deleteSpokeman(Request $request)
    {
        $id = base64_decode($request->id);

        $customer_id = base64_decode($request->customer_id);

        DB::beginTransaction();
        try{
            if(Auth::check()){

                $user_business = DB::table('user_businesses')->where(['business_id'=>$customer_id])->first();

                $spoke_arr = [];

                $spoke_arr = json_decode($user_business->client_spokeman,2);

                unset($spoke_arr[$id]);

                DB::table('user_businesses')->where(['business_id'=>$customer_id])->update([
                    'client_spokeman' => count($spoke_arr) > 0 ? json_encode($spoke_arr) : NULL
                ]);
                
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

    /**
     * Get the State based on country selection.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getstate(Request $request)
    {
       $country_id = $request->country_id; 

       $state = DB::table('states')->where('country_id',$country_id)->get();

       return response()->json($state);
     
    }
    /**
     * Get the City based on state selection.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getcity(Request $request)
    {
       $state_id = $request->state_id; 

       $city = DB::table('cities')->where('state_id',$state_id)->get();

       return response()->json($city);
     
    }
    

    //show jobs of customers
    public function jobs(Request $request,$id)
    {
        $customer_id = base64_decode($id);

        $item = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name','b.contact_person','b.address_line1','b.zipcode','b.city_name','b.state_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.id'=>$customer_id])
        ->first();

        $jobs = DB::table('jobs as j')
              ->select('j.id','j.title','j.total_candidates','j.created_at','j.created_by','j.status','u.name as customer','u.id as customer_id','j.sla_id')
              ->join('users as u','u.id','=','j.business_id')
              ->join('customer_sla as s','s.id','=','j.sla_id')
              ->where(['j.business_id'=>$customer_id,'s.sla_type'=>'package']);
              if ($request->get('search')) {
                $jobs->where('s.title','LIKE',"{$request->get('search')}%");
              }
              $jobs=$jobs->get();
        if($request->ajax())     
            return view('admin.customers.job_ajax',compact('item','jobs'));
        else
            return view('admin.customers.job',compact('item','jobs'));
    }

    //show sla of customers
    public function slas(Request $request,$id)
    {
        $customer_id = base64_decode($id);

        $item = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name','b.contact_person','b.address_line1','b.zipcode','b.city_name','b.state_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.id'=>$customer_id])
        ->first();

        $sla = DB::table('customer_sla as sla')
                ->select('sla.*')
                ->where(['sla.business_id'=>$customer_id]);
                if ($request->get('search')) {
                    $sla->where('sla.title','LIKE',"{$request->get('search')}%");
                }
                $sla=$sla->get();
        if($request->ajax())
            return view('admin.customers.sla_ajax',compact('item','sla'));
        else
        return view('admin.customers.sla',compact('item','sla'));
    }

    //show the customer detail
    public function show(Request $request,$id)
    {
        $customer_id = base64_decode($id);
        // dd($customer_id);
        $item = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','u.phone_code','u.phone_iso','b.company_name','b.contact_person','b.address_line1','b.zipcode','b.city_name','b.state_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.id'=>$customer_id])
        ->first();

        // dd($item);

        //candidates
        $candidates = DB::table('candidate_reinitiates as u')
        ->select('u.id','u.name','u.email','u.phone','u.phone_code','u.phone_iso')
        ->where(['user_type'=>'candidate','business_id'=>$customer_id]);
            if($request->get('from_date') !=""){
                $candidates->whereDate('u.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
            }
          if($request->get('to_date') !=""){
            $candidates->whereDate('u.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
          }
          if($request->get('mob')){
            $candidates->where('u.phone',$request->get('mob'));
          }
          if($request->get('ref')){
            $candidates->where('u.display_id',$request->get('ref'));
          }
          if($request->get('email')){
            $candidates->where('u.email',$request->get('email'));
          }
          if(is_numeric($request->get('candidate_id'))){
            $candidates->where('u.id',$request->get('candidate_id'));
          }
        $candidates=$candidates->get();
        // dd($candidates);
          if($request->ajax())
            return view('admin.customers.candidate_ajax',compact('item','candidates'));
          else
            return view('admin.customers.show',compact('item','candidates'));
    }
 
    /**
     * Get the sla.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getSlaList(Request $request)
    {
        $business_id = $request->input('customer_id');

        $sla = DB::table('customer_sla as sla')
                ->select('sla.*')
                ->where(['sla.business_id'=>$business_id])
                // ->orwhere(['title'=>'Global Variable'])
                ->get();
        
        return response()->json([
            'success'   =>true,
            'data'      =>$sla 
        ]);

    }

    /**
     * Get the University List.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function universityBoardList(Request $request)
    {
        $business_id = $request->input('customer_id');
        $search_key = $request->input('search'); 
        // educational 11 
        $arra_data = [];
        $data = DB::table('jaf_form_data')
                ->select('form_data')
                ->where(['service_id'=>'11'])
                ->get();

        $array1 = [];
        foreach($data as $item){
            // print_r(json_decode($item->form_data,true));
            foreach( json_decode($item->form_data,true) as $itemD){
                // echo "<pre>";
                // print_r($itemD);
                if (array_key_exists('University Name / Board Name', $itemD)) {
                    
                    $array1[] = ['label'=>$itemD['University Name / Board Name']];
                }
            }
        }
        $a = $array1;
        // print_r($a);
        //print_r(array_unique($a)); die;

        return response()->json([
            'success'   =>true,
            'data'      =>$array1
        ]);

    }

    //
    /**
     * Get the sla's items.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getSlaItemList(Request $request)
    {
        $sla_id = $request->input('sla_id');

        $sla_items = DB::table('customer_sla_items as sla')
                ->select('sla.id as sla_item_id','s.id as service_id','s.name as service_name','s.verification_type')
                ->join('services as s','s.id','=','sla.service_id')
                ->where(['sla.sla_id'=>$sla_id])
                ->get();
        
        return response()->json([
            'success'   =>true,
            'data'      =>$sla_items 
        ]);

    }

    /**
     * Get the Mix sla's items.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getMixSlaItemList(Request $request)
    {
        $business_id=Auth::user()->business_id;
        $sla_id = $request->input('sla_id');

        // dd($sla_id);
        $slaItems =[];
        $existItem=[];

        try{
            $sla = DB::table('customer_sla as sla')
                    ->select('sla.*','u.company_name')
                    ->join('user_businesses as u','u.business_id','=','sla.business_id')
                    ->where(['sla.id'=>$sla_id])
                    ->first();

            $sla_items = DB::table('customer_sla_items as sla')
                        ->select('sla.id as sla_item_id','s.id as service_id','sla.number_of_verifications','sla.tat as check_tat','sla.incentive_tat','sla.penalty_tat','sla.price','s.name as service_name','s.verification_type')
                        ->join('services as s','s.id','=','sla.service_id')
                        ->where(['sla.sla_id'=>$sla_id])
                        ->whereNotIn('s.name',['GSTIN'])
                        ->get();

            // dd($sla_items);

            $slaItems = (array) json_decode(json_encode($sla_items),true);
            // dd($slaItems);
            foreach($slaItems as $item){
                    $existItem[]= $item['service_id'];
            }
            // dd($existItem);

            $all_services = DB::table('services as s')
                            ->select('s.id as service_id','s.name as service_name','s.verification_type')
                            ->join('service_form_inputs as si','s.id','=','si.service_id')
                            ->where('s.business_id',NULL)
                            ->where('s.status',1)
                            ->whereNotIn('s.type_name',['gstin'])
                            ->orwhere('s.business_id',$business_id)
                            ->groupBy('si.service_id')
                            ->orderBy('s.sort_number','asc')
                            ->get();

            $total_checks = DB::table('customer_sla_items')
                            ->select('number_of_verifications')
                            ->where(['sla_id'=>$sla_id])
                            ->sum('number_of_verifications');

            $total_check_price = DB::table('customer_sla_items')
                                ->select('price')
                                ->where(['sla_id'=>$sla_id])
                                ->sum('price');                
            $all_service_items = (array) json_decode(json_encode($all_services),true);

            $accumulated_list = [];

            
            // dd($all_service_items);
            foreach($all_services as $item){
                $checked_atatus = FALSE;
                if(in_array($item->service_id,$existItem)){
                    $checked_atatus = TRUE;
                }            
                $accumulated_list[] = ['checked_atatus'=>$checked_atatus,'service_id'=>$item->service_id,'service_name'=>$item->service_name];
            }
            
            return response()->json([
                'success'   =>true,
                'data'      =>$accumulated_list,
                'sladata' => $sla_items,
                'total_check_price' => $total_check_price,
                'total_checks' => $total_checks,
                'sla' => $sla
            ]);
        }
        catch (\Exception $e) {
            // something went wrong
            return $e;
        }  

    }

    /**
     * Get the candidate list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCandidatesList(Request $request)
    {
        $business_id = $request->input('customer_id');

        $candidates = DB::table('candidate_reinitiates')
                ->select('id','display_id','first_name','middle_name','last_name','phone','name')
                ->where(['business_id'=>$business_id,'user_type'=>'candidate'])
                ->get();
        
        return response()->json([
            'success'   =>true,
            'data'      =>$candidates 
        ]);

    }

    // add file.
   public function uploadFile(Request $request)
   {        
     // dd($request);
     $extensions = array("doc","pdf","docx","jpg","png","jpeg","PNG","JPG","JPEG",'xlsx','xls','docx','txt');
     $result = array($request->file('file')->getClientOriginalExtension());
     $business_id = Auth::user()->business_id;

        DB::beginTransaction();
        try{
            $filename='';
            if($request->hasFile('file')) {
        
                if(in_array($result[0],$extensions))
                {                      
                        // $label_file_name  = $request->input('label_file_name');
                        $file_platform = 'web';

                        $attachment_file  = $request->file('file');
                        $orgi_file_name   = $attachment_file->getClientOriginalName();
                        
                        $fileName = pathinfo($orgi_file_name,PATHINFO_FILENAME);
                
                        $filename         = time().'-'.$fileName.'.'.$attachment_file->getClientOriginalExtension();
                        $dir              = public_path('/uploads/customer-files/'); 

                        $s3_config = S3ConfigTrait::s3Config();

                        if($s3_config!=NULL)
                        {
                            $file_platform = 's3';

                            $path = 'uploads/customer-files/';

                            if(!Storage::disk('s3')->exists($path))
                            {
                                Storage::disk('s3')->makeDirectory($path,0777, true, true);
                            }

                            Storage::disk('s3')->put($path.$filename, file_get_contents($attachment_file));
                        }
                        else
                        {
                            $request->file('file')->move($dir, $filename);
                        }         
                            
                        $customer_id  = NULL;
                        $is_temp = 1;
            
                        //check if 
                        if($request->has('customer_id')) {
                            $customer_id = base64_decode($request->input('customer_id'));
                        }
                        
                        $rowID = DB::table('user_business_attachments')            
                                ->insertGetId([
                                    // 'parent_id' => $business_id,
                                    'business_id'  => $customer_id,                       
                                    'file_name'    => $filename,
                                    'file_platform' => $file_platform,
                                    'created_by'   => Auth::user()->id,
                                    'created_at'   => date('Y-m-d H:i:s'),
                                    'is_temp'      => $is_temp,
                                ]);                                
            
                        // file type 
                        $type = url('/').'/admin/images/file.jpg';
                        $extArray = explode('.', $filename);
                        $ext = end($extArray);
                        
                        if($filename != NULL)
                        {
                            if($ext == 'pdf')
                            {
                                $type = url('/').'/admin/images/icon_pdf.png';
                            } 
                            if($ext == 'doc' || $ext == 'docx' )
                            {
                                $type = url('/').'/admin/images/icon_docx.png';
                            }
                            if($ext == 'xls' || $ext == 'xlsx' || $ext == 'csv')
                            {
                                $type = url('/').'/admin/images/icon_xlsx.png';
                            }
                            if($ext == 'pptx' || $ext == 'ppt')
                            {
                                $type = url('/').'/admin/images/icon_pptx.png';
                            }
                            if($ext == 'psd' || $ext == 'PSD')
                            {
                                $type = url('/').'/admin/images/icon_psd.png';
                            }
                            if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'JPG' || $ext == 'PNG' || $ext == 'JPEG'  || $ext == 'GIF')
                            {  
                                if(stripos($file_platform,'s3')!==false)
                                {
                                    $filePath = 'uploads/customer-files/';

                                    $disk = Storage::disk('s3');

                                    $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                        'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                        'Key'                        => $filePath.$filename,
                                        //'ResponseContentDisposition' => 'attachment;'//for download
                                    ]);

                                    $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                    $type = (string)$req->getUri();  
                                }
                                else
                                {
                                    $type = url('/').'/uploads/customer-files/'.$filename;
                                }
                            }
                        }           
                        DB::commit();
                        return response()->json([
                            'fail' => false,
                            'file_id' => base64_encode($rowID),
                            'filename' => $filename,
                            'filePrev'=>$type
                        ]); 
            
                }
                else{
                    // Do something when it fails
                    return response()->json([
                        'fail' => true,
                        'errors' => 'File type error!'
                    ]);
                }
        
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $file_path='';
            $file_path=url('/').'/uploads/customer-files/'.$filename;
            if(File::exists($file_path))
            {
                File::delete($file_path);
            }
            return $e;
        }  
 
   }

   public function removeFile(Request $request)
   {
        $id =  base64_decode($request->input('file_id'));
        DB::beginTransaction();
        try{
 
           $is_done = DB::table('user_business_attachments')->where('id',$id)->update(['is_deleted'=>'1','deleted_at'=>date('Y-m-d H:i:s'),'deleted_by'=>Auth::user()->id]);
 
           DB::commit();
           // Do something when it fails
           return response()->json([
               'fail' => false,
               'message' => 'File removed!'
           ]);
         }
         catch (\Exception $e) {
             DB::rollback();
             // something went wrong
             return $e;
         }
   }

    // add file.
    public function uploadStepFile(Request $request)
    {        
      // dd($request);
      $extensions = array("doc","pdf","docx","jpg","png","jpeg","PNG","JPG","JPEG");
      $result = array($request->file('file')->getClientOriginalExtension());
      $business_id = Auth::user()->business_id;
 
         DB::beginTransaction();
         try{
             $filename='';
             if($request->hasFile('file')) {
         
                 if(in_array($result[0],$extensions))
                 {                      
                         // $label_file_name  = $request->input('label_file_name');
             
                         $attachment_file  = $request->file('file');
                         $orgi_file_name   = $attachment_file->getClientOriginalName();
                         
                         $fileName = pathinfo($orgi_file_name,PATHINFO_FILENAME);
                 
                         $filename         = $fileName.'-'.time().'.'.$attachment_file->getClientOriginalExtension();
                         $dir              = public_path('/uploads/customer-files/'); 
                         
                         $file_platform = 'web';

                         $s3_config = S3ConfigTrait::s3Config();

                        if($s3_config!=NULL)
                        {
                            $file_platform = 's3';

                            $path = 'uploads/customer-files/';

                            if(!Storage::disk('s3')->exists($path))
                            {
                                Storage::disk('s3')->makeDirectory($path,0777, true, true);
                            }

                            Storage::disk('s3')->put($path.$filename, file_get_contents($attachment_file));
                        }
                        else
                        {
                            $request->file('file')->move($dir, $filename);
                        } 
                             
                         $customer_id  = NULL;
                         $is_temp = 1;
             
                         //check if 
                         if($request->has('customer_id')) {
                             $customer_id = base64_decode($request->input('customer_id'));
                         }
                         
                         $rowID = DB::table('user_business_attachments')            
                                 ->insertGetId([
                                     'parent_id' => $business_id,
                                     'business_id'  => $customer_id,                       
                                     'file_name'    => $filename,
                                     'file_platform' => $file_platform,
                                     'created_by'   => Auth::user()->id,
                                     'created_at'   => date('Y-m-d H:i:s'),
                                     'is_temp'      => $is_temp,
                                 ]);                                
             
                         // file type 
                         $type = url('/').'/admin/images/file.jpg';
                         $extArray = explode('.', $filename);
                         $ext = end($extArray);
                         
                         if($filename != NULL)
                         {
                             if($ext == 'pdf')
                             {
                                 $type = url('/').'/admin/images/icon_pdf.png';
                             } 
                             if($ext == 'doc' || $ext == 'docx')
                             {
                                 $type = url('/').'/admin/images/icon_docx.png';
                             }
                             if($ext == 'xls' || $ext == 'xlsx' || $ext == 'csv')
                             {
                                 $type = url('/').'/admin/images/icon_xlsx.png';
                             }
                             if($ext == 'pptx' || $ext == 'ppt')
                             {
                                 $type = url('/').'/admin/images/icon_pptx.png';
                             }
                             if($ext == 'psd' || $ext == 'PSD')
                             {
                                 $type = url('/').'/admin/images/icon_psd.png';
                             }
                             if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'JPG' || $ext == 'PNG' || $ext == 'JPEG'  || $ext == 'GIF')
                             {              
                                if(stripos($file_platform,'s3')!==false)
                                {
                                    $filePath = 'uploads/customer-files/';

                                    $disk = Storage::disk('s3');

                                    $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                        'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                        'Key'                        => $filePath.$filename,
                                        //'ResponseContentDisposition' => 'attachment;'//for download
                                    ]);

                                    $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                    $type = (string)$req->getUri();  
                                }
                                else
                                {
                                    $type = url('/').'/uploads/customer-files/'.$filename;
                                }

                             }
                         }           
                         DB::commit();
                         return response()->json([
                             'fail' => false,
                             'file_id' => base64_encode($rowID),
                             'filename' => $filename,
                             'filePrev'=>$type
                         ]); 
             
                 }
                 else{
                     // Do something when it fails
                     return response()->json([
                         'fail' => true,
                         'errors' => 'File type error!'
                     ]);
                 }
         
             }
         }
         catch (\Exception $e) {
             DB::rollback();
             // something went wrong
             $file_path='';
             $file_path=url('/').'/uploads/customer-files/'.$filename;
             if(File::exists())
             {
                 File::delete($file_path);
             }
             return $e;
        }  
  
    }

    public function customerStatus(Request $request)
    {
        $user_id=base64_decode($request->id);
        $user = User::find($user_id);
        $type = base64_decode($request->type);

        if(stripos($type,'disable')!==false)
        {
            // if($user->status==1){
            //     Session::getHandler()->destroy($user->session_id);
            //     // $request->session()->regenerateToken();
            // }
            $user->status = 0;
            $user->save();
            $users = DB::table('users')->where(['business_id'=>$user_id,'user_type'=>'user','status'=>1])->get();
            if(count($users)>0){
                foreach ($users as $item)
                {
                   DB::table('users')->where('id',$item->id)->update(['status'=>0]);
                }
            }
            
            return response()->json([
                'success'=>true,
                'type' => $type,
                'message'=>'Status change successfully.'
            ]);
        }
        elseif(stripos($type,'enable')!==false)
        {
            $user->status = 1;
            $user->save();
            $users = DB::table('users')->where(['business_id'=>$user_id,'user_type'=>'user','status'=>0])->get();
            if(count($users)>0){
                foreach ($users as $item)
                {
                   DB::table('users')->where('id',$item->id)->update(['status'=>1]);
                }
            }
            return response()->json([
                'success'=>true,
                'type' => $type,
                'message'=>'Status change successfully.'
            ]);
        }
        
    }
}
