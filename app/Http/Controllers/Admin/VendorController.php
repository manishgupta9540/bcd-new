<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Mail;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Traits\EmailConfigTrait;
use Illuminate\Support\Facades\Config;
use App\Traits\S3ConfigTrait;

class VendorController extends Controller
{
    /**
    *@return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the general.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index(Request $request)
    {
        $user_id = Auth::user()->id;
        $business_id = Auth::user()->business_id; 
       
        $cam=[];
        $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
    
        foreach ($kams as $kam) {
           $cam[]= $kam->user_id;
        }
        
        $vendors = DB::table('users as u')
                    ->join('vendors as v','v.user_id','=','u.id')
                    ->join('vendor_businesses as vb','vb.business_id','=','v.user_id') 
                    ->select('u.id','u.name')
                    ->where(['v.business_id'=>$business_id,'u.is_deleted'=>0])->get();
                    $rows=10;
      
        if(Auth::user()->user_type=='customer'){

            $vendor = DB::table('vendors as v')
                    ->join('users as u','v.user_id','=','u.id')
                    ->join('vendor_businesses as vb','vb.business_id','=','v.user_id') 
                    ->select('v.id','v.company_name','v.first_name','v.last_name','v.email','v.phone','v.status','u.display_id','u.id as user_id')
                    ->where(['v.business_id'=>$business_id,'u.is_deleted'=>0]);
                   
        }
        else if(in_array($user_id,$cam))
        {
         
            $vendor = DB::table('vendors as v')
                    ->join('users as u','v.user_id','=','u.id')
                    ->join('vendor_businesses as vb','vb.business_id','=','v.user_id') 
                    ->select('v.id','v.company_name','v.first_name','v.last_name','v.email','v.phone','v.status','u.display_id','u.id as user_id')
                    ->where(['v.business_id'=>$business_id,'u.is_deleted'=>0]);
          
        }
        else
        {
         
            $users = User::from('users')->where('id',$user_id)->first();
            $vendorId = $users->vendor_id;
            $usersVendorid =   explode(',',$vendorId);

            $vendor = DB::table('vendors as v')
                    ->join('users as u','v.user_id','=','u.id')
                    ->join('vendor_businesses as vb','vb.business_id','=','v.user_id') 
                    ->select('v.id','v.company_name','v.first_name','v.last_name','v.email','v.phone','v.status','u.display_id','u.id as user_id')
                    ->where(['v.business_id'=>$business_id,'u.is_deleted'=>0])
                    ->whereIn('v.user_id',$usersVendorid);
    
        }

        if($request->get('from_date') !=""){
            $vendor->whereDate('v.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
        }
        if($request->get('to_date') !=""){
            $vendor->whereDate('v.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
        }
        if(is_numeric($request->get('vendor_id'))){
            
            $vendor->where('v.user_id',$request->get('vendor_id'));
        }
        if($request->get('ref')){
            $vendor->where('u.display_id',$request->get('ref'));
        }
        if($request->get('email')){
            $vendor->where('u.email',$request->get('email'));
        }
        if($request->get('mob')){
            $vendor->where('u.phone',$request->get('mob'));
        }
        if($request->get('business_type')){
            $vendor->where('vb.vendor_type',$request->get('business_type'));
        }
        
        $vendor=$vendor->paginate($rows);
        
        if($request->ajax())
            return view('admin.vendors.ajax',compact('vendor','vendors'));
        else
            return view('admin.vendors.index',compact('vendor','vendors'));
    }

     //
     public function create()
     {
         $countries        = DB::table('countries')->get();
         $state            = DB::table('states')->where('country_id','101')->get();
        
        $services = DB::table('services')->select('*')->where(['verification_type'=>'Manual','status'=>'1'])
        ->get();
         return view('admin.vendors.create',compact('countries','state','services'));

     }

     //
     public function save(Request $request)
     {
        $i=0;
        $business_id = Auth::user()->business_id;
        $company_logo=NULL;
        $is_gst_verified=0;
        $is_pan_verified=0;
        

        $rules= [
            'first_name' =>'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'last_name' => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'email'     => 'required|email:rfc,dns|unique:users',
            'phone'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|digits_between:10,10|numeric',
            'address'   => 'required',
            'pincode'   => 'required|numeric|digits_between:5,6',
            'city'      => 'required',
            'state'     => 'required',
            'country'   => 'required',
            'company'   => 'required_if:verifier,company',
            'individual'   => 'required_if:verifier,individual',
            'landline_number' => 'nullable|numeric',
            
            'password'      => 'required|regex:#.*^(?=.{10,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#|same:confirm-password',
            'confirm-password' => 'required|regex:#.*^(?=.{10,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#|same:password',
            'business_email'            => 'required|email:rfc,dns',
            'business_phone_number'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|digits_between:10,10|numeric',
            'gst_number'                => 'nullable|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/|min:15|max:15',
            'contract_signed_by'        => 'required',
            'pan_number'                => 'required_if:verifier,company|nullable|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/|min:10|max:10',
            'owner_first_name'          => 'required|regex:/^[a-zA-Z]+$/u|min:2|max:255',
            'owner_last_name'           => 'nullable|regex:/^[a-zA-Z]+$/u|min:2|max:255',
            'owner_email'               => 'required|email:rfc,dns',
            'owner_phone_number'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|digits_between:10,10|numeric',
            'owner_landline_number'        =>'nullable|numeric',
            'owner_designation'         => 'required',
            'tin_number'    =>          'nullable|numeric|digits:11',
            'type.*' => 'sometimes|required|min:1',
            'add_first_name.*' => 'sometimes|required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'add_last_name.*'         => 'sometimes|nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'add_email.*'             => 'sometimes|nullable|email:rfc,dns',
            'add_phone.*'      => 'sometimes|nullable|regex:/^[0-9]{10}/',
            'add_landline_number.*'   =>'sometimes|nullable|numeric',
           ];
            
            $customMessages = [

                'password.regex'=>'Password must be atleast 10 characters long including (Atleast 1 uppercase letter(A–Z), Lowercase letter(a–z), 1 number(0–9), 1 non-alphanumeric symbol(‘$@%£!’) !',
                'email.unique'  =>'Email id has already been taken',
                'owner_first_name.required' => 'The Contact Person First Name required',
                'owner_last_name.required' => 'The Contact Person Last Name required',
                'owner_email.required' => 'The Contact Person Email required',
                'owner_phone_number.required' => 'The Contact Person Phone Number required',
                'owner_designation.required' => 'The Contact Person Designation required',
                'type.*.required' => 'Contact Type Field is required',
                'type.*.min' => 'Contact Type Field has atleast 1 character',
                'add_first_name.*.required' => 'Additional First Name Field is required',
                'add_first_name.*.regex' => 'Additional First Name must be String',
                'add_first_name.*.min' => 'Additional First Name has atleast 1 character',
                'add_first_name.*.max' => 'Additional First Name has maximum 255 character allowed',
                'add_last_name.*.required' => 'Additional Last Name Field is required',
                'add_last_name.*.regex' => 'Additional Last Name must be String',
                'add_last_name.*.min' => 'Additional Last Name has atleast 1 character',
                'add_last_name.*.max' => 'Additional Last Name has maximum 255 character allowed',
                'add_email.*.email' => 'Additional Email Must be an email address',
                'add_phone.*.regex' => 'Additional Phone Number Must be 10-digit Number !!',
                'add_landline_number.*.numeric' => 'Additional Landline Number Must be Numeric',
            ];
    
         $validator = Validator::make($request->all(), $rules, $customMessages);
          
         if ($validator->fails()){
             return response()->json([
                 'success' => false,
                 'errors' => $validator->errors()
             ]);
         }
         $phone = preg_replace('/\D/', '', $request->input('phone'));
         $business_phone = preg_replace('/\D/', '', $request->input('business_phone_number'));
         $owner_phone = preg_replace('/\D/', '', $request->input('owner_phone_number'));
        

              // Verification of GST Number
        if($request->gst_number!=NULL)
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
        if($request->pan_number!=NULL)
        {
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

           $company_logo = $logoImage;
        }

        $country_name      = DB::table('countries')->where('id',$request->country)->first();
         $state_name          = DB::table('states')->where('id',$request->state)->first();
         $city_name          = DB::table('cities')->where('id',$request->city)->first();

         $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('last_name')));

        $user_data = 
        [
            'user_type'     =>'vendor',
            'parent_id'   =>$business_id,
            'first_name'    =>$request->input('first_name'),
            'last_name'     =>$request->input('last_name'),
            'name'          =>$name,
            'email'         =>$request->input('email'),
            'phone'         =>$phone,
            'password'      =>$hashed_random_password,
            'status'        =>1,
            'company_logo'  =>$company_logo,
            'company_logo_file_platform'    => $company_logo_file_platform,
            'created_by'    =>Auth::user()->id,
            'created_at'    =>date('Y-m-d H:i:s')
        ];
        
        $user_id = User::create($user_data);
        $user_id= $user_id->id;
        // dd();
        $user_update = DB::table('users')->where('id',$user_id)->update(['business_id'=>$user_id]);
        $pass_user= DB::table('users')->where(['id'=>$user_id])->first();
            $pass_store=[
                'business_id'   =>$pass_user->business_id,
                'user_id'   =>$pass_user->id,
                'parent_id' =>$pass_user->parent_id,
                'email' =>$pass_user->email,
                'password' => $pass_user->password,
            ];
            DB::table('password_logs')->insert($pass_store);
        $customer_company = DB::table('user_businesses')
         ->select('company_name')
         ->where(['business_id'=>$business_id])
         ->first();

        
         if ($request->input('company')!=null) {
            $name=$request->input('company');
         }
         else {
            $user_name = DB::table('users')->select('first_name')->where('id',$user_id)->first();
            $name=$user_name->first_name;
         }
        //  dd($customer_company);
        // $vendor_company = DB::table('vendor_businesses')
        //     ->select('company_name')
        //     ->where(['business_id'=>$user_id])
        //     ->first();
            
        $u_id = str_pad($user_id, 10, "0", STR_PAD_LEFT);
        $display_id =trim(strtoupper(str_replace(array(' ','-'),'',substr($customer_company->company_name,0,4)))).'-'.trim(strtoupper(substr($name,0,4))).'-'.$u_id;
        DB::table('users')->where(['id'=>$user_id])->update(['display_id'=>$display_id]);
        // trim(strtoupper(substr($customer_company->company_name,0,4))).'-'.trim(strtoupper(substr($name,0,4))).'-'.$u_id
        $vendor = 
        [
           
            'business_id'   =>$business_id,
            'user_id'       =>$user_id,
            'first_name'    =>$request->input('first_name'),
            'last_name'     =>$request->input('last_name'),
            'company_name'  =>$request->input('company'),
            'individual_name' =>$request->input('individual'),
            // 'service'       =>$request->input('service'),
            'state'         =>$request->input('state'),
            'city'          =>$request->input('city'),
            'pincode'       =>$request->input('pincode'),
            'address'       =>$request->input('address'),
            'country_id'    =>$request->input('country'),
            'email'         =>$request->input('email'),
            'phone'         =>$phone,
            'created_by'    =>Auth::user()->id,
            'created_at'    =>date('Y-m-d H:i:s')

        ];

       $vendor_id=DB::table('vendors')->insertGetId($vendor);

        //insert business info
        $b_data = 
        [
            'parent_id'         => $business_id,
            'business_id'           =>$user_id,
            'vendor_id'            =>$vendor_id,
            'vendor_type'            =>$request->verifier,
            'company_name'          =>$request->input('company'),
            'address_line1'         =>$request->input('address'),
            'zipcode'               =>$request->input('pincode'),
            'city_id'               =>$request->input('city'),
            'state_id'              =>$request->input('state'),
            'country_id'            =>$request->input('country'),
            'city_name'               =>$city_name->name,
            'state_name'              =>$state_name->name,
            'country_name'            =>$country_name->name,
            'email'                 =>$request->input('business_email'),
            'phone'                 =>$business_phone,
            'phone_code'            => $request->primary_phone_code2,
            'phone_iso'             => $request->primary_phone_iso2,
            'gst_number'            =>$request->input('gst_number'),
            'is_gst_verified'       =>$is_gst_verified,
            'tin_number'            =>$request->input('tin_number'),
            'pan_number'            => $request->input('pan_number'),
            'is_pan_verified'       => $is_pan_verified,
            'contract_signed_by'    =>$request->input('contract_signed_by'),
            'website'               => $request->input('website'),
            'created_at'            => date('Y-m-d H:i:s')
        ];
        
        DB::table('vendor_businesses')->insertGetId($b_data);


            //contact info
            // contact Person
            $b_data = 
            [
                'parent_id'     =>$business_id,
                'business_id'   =>$user_id,
                'contact_type'  =>'contact_person',
                'designation'   =>$request->input('owner_designation'),
                'first_name'    =>$request->input('owner_first_name'),
                'last_name'     =>$request->input('owner_last_name'),
                'email'         =>$request->input('owner_email'),
                'phone'         =>$owner_phone,
                'phone_code'    => $request->primary_phone_code3,
                'phone_iso'     => $request->primary_phone_iso3,
                'landline_number'=>$request->input('owner_landline_number'),
                'created_at'    => date('Y-m-d H:i:s')
            ];
            
            DB::table('vendor_business_contacts')->insertGetId($b_data);


            if(isset($request->input('type')[$i]))
            {
            
                foreach ($request->input('type') as $value) 
                {
                    $b_data = 
                    [
                        'parent_id'     =>$business_id,
                        'business_id'   =>$user_id,
                        'contact_type'  =>$request->input('type')[$i],
                        'designation'   =>$request->input('add_designation')[$i],
                        'first_name'    =>$request->input('add_first_name')[$i],
                        'last_name'     =>$request->input('add_last_name')[$i],
                        'email'         =>$request->input('add_email')[$i],
                        'phone'         =>$request->input('add_phone')[$i],
                        'landline_number'=>$request->input('add_landline_number')[$i],
                        'created_at'     => date('Y-m-d H:i:s')
                    ];
                
                    $i++;
                    DB::table('vendor_business_contacts')->insert($b_data);
            
                }
            }
            //contract files
            if($request->has('fileID'))
            {
                foreach ($request->input('fileID') as $value) 
                {
                    $file_data = 
                    [
                        'parent_id' => $business_id,
                        'business_id' =>$user_id,
                        'is_temp'     => '0'
                    ];
                
                    DB::table('vendor_business_attachments')->where(['id'=>$value])->update($file_data);
                }
            }

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

                return response()->json([
                    'success' => true,
                    'errors' => '',
                
                ]);
                //   return redirect()
                //          ->route('/admin/vendor')
                //          ->with('success', 'Vendor created successfully.');

     }
 
     public function edit($id)
     {

          $vendor_id = base64_decode($id);
        //   dd($vendor_id);

          $countries      = DB::table('countries')->get();


          $vendor = DB::table('vendors as v')
            ->select('v.*')
            ->where(['v.id'=>$vendor_id])
            ->first();

            $user =DB::table('users as u')
            ->select('u.company_logo','u.company_logo_file_platform')
            ->where(['u.id'=>$vendor->user_id])
            ->first();
            $business = DB::table('vendor_businesses as b')
            ->select('b.*')
            ->where(['b.business_id'=>$vendor->user_id])
            ->first();
            // dd($business);

            $owner = DB::table('vendor_business_contacts as b')
            ->select('b.*')
            ->where(['b.business_id'=>$vendor->user_id,'contact_type'=>'contact_person'])
            ->first();
            // dd($owner);
            $type = DB::table('vendor_business_contacts as b')
            ->select('b.*')
            ->where(['b.business_id'=>$vendor->user_id,'is_deleted'=>0])
            ->whereNotIn('contact_type',['contact_person'])
            ->get();

            $files = DB::table('vendor_business_attachments')
            ->select('*')
            ->where(['business_id'=>$vendor->user_id])
            ->get();
        
            $state          = DB::table('states')->where('country_id',$business->country_id)->get();
            $cities          = DB::table('cities')->where('state_id',$business->state_id)->get();
           


                    // dd($vendor);
         return view('admin.vendors.edit',compact('countries','vendor','vendor_id','state','cities','business','owner','type','files','user'));

     }

     public function update(Request $request)
     {
        
         $vendor_id = $request->input('vendor_id');
            //  dd($request->input('company'));

         $user_id   = $request->input('user_id');
         $business_id =Auth::user()->business_id;

        $rules= [
            'first_name' =>'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'last_name' => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'email'     => 'required|email:rfc,dns',
            'phone'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|digits_between:10,10|numeric',
            'address'   => 'required',
            'pincode'   => 'required|digits_between:5,6|numeric',
            'city'      => 'required',
            'state'     => 'required',
            'country'   => 'required',
            'company'   => 'required_if:verifier,company',
            'individual'   => 'required_if:verifier,individual',
            'landline_number' => 'nullable|numeric',
            
            // 'password' => 'required|regex:#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#|same:confirm-password',
            // 'confirm-password' => 'required|regex:#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#|same:password',
            'business_email'            => 'required|email:rfc,dns',
            'business_phone_number'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|digits_between:10,10|numeric',
            'gst_number'                => 'nullable|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/|min:15|max:15',
            'contract_signed_by'        => 'required',
            'pan_number'                => 'required_if:verifier,company|nullable|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/|min:10|max:10',
            'owner_first_name'          => 'required|regex:/^[a-zA-Z]+$/u|min:2|max:255',
            'owner_last_name'           => 'nullable|regex:/^[a-zA-Z]+$/u|min:2|max:255',
            'owner_email'               => 'required|email:rfc,dns',
            'owner_phone_number'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|digits_between:10,10|numeric',
            'owner_designation'         => 'required',
            'tin_number'                => 'nullable|numeric|digits:11',
            'type.*' => 'sometimes|required|min:1',
            'add_first_name.*' => 'sometimes|required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'add_last_name.*'         => 'sometimes|nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'add_email.*'             => 'sometimes|nullable|email:rfc,dns',
            'add_phone.*'      => 'sometimes|nullable|regex:/^[0-9]{10}/',
            'add_landline_number.*'   =>'sometimes|nullable|numeric',

           ];
            
            $customMessages = [

                'email.unique'  =>'Email id has already been taken',
                'owner_first_name.required' => 'The Contact Person First Name required',
                'owner_las_name.required' => 'The Contact Person Last Name required',
                'owner_email.required' => 'The Contact Person Email required',
                'owner_phone_number.required' => 'The Contact Person Phone Number required',
                'owner_designation.required' => 'The Contact Person Designation required',
                'type.*.required' => 'Contact Type Field is required',
                'type.*.min' => 'Contact Type Field has atleast 1 character',
                'add_first_name.*.required' => 'Additional First Name Field is required',
                'add_first_name.*.regex' => 'Additional First Name must be String',
                'add_first_name.*.min' => 'Additional First Name has atleast 1 character',
                'add_first_name.*.max' => 'Additional First Name has maximum 255 character allowed',
                'add_last_name.*.required' => 'Additional Last Name Field is required',
                'add_last_name.*.regex' => 'Additional Last Name must be String',
                'add_last_name.*.min' => 'Additional Last Name has atleast 1 character',
                'add_last_name.*.max' => 'Additional Last Name has maximum 255 character allowed',
                'add_email.*.email' => 'Additional Email Must be an email address',
                'add_phone.*.regex' => 'Additional Phone Number Must be 10-digit Number !!',
                'add_landline_number.*.numeric' => 'Additional Landline Number Must be Numeric',
            ];
    
         $validator = Validator::make($request->all(), $rules, $customMessages);
          
         if ($validator->fails()){
             return response()->json([
                 'success' => false,
                 'errors' => $validator->errors()
             ]);
         }

         $phone = preg_replace('/\D/', '', $request->input('phone'));
         $business_phone = preg_replace('/\D/', '', $request->input('business_phone_number'));
         $owner_phone = preg_replace('/\D/', '', $request->input('owner_phone_number'));
         $country_name      = DB::table('countries')->where('id',$request->country)->first();
         $state_name          = DB::table('states')->where('id',$request->state)->first();
         $city_name          = DB::table('cities')->where('id',$request->city)->first();
         
         $vendor_type = DB::table('vendor_businesses')->select('vendor_type')->where(['vendor_id'=>$vendor_id])->first();
            if($vendor_type)
            {
                if ($vendor_type->vendor_type=='individual' && $request->verifier=='company') {
                    //    dd('done');
                    // Verification of GST Number
                    if($request->gst_number!=NULL)
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
                    if($request->pan_number!=NULL)
                    {
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
                    }
                }
            }elseif ($request->verifier=='company') {
                    if($request->gst_number!=NULL)
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
                    if($request->pan_number!=NULL)
                    {
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
                    }
            }
            else{
                if($request->gst_number!=NULL)
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
                if($request->pan_number!=NULL)
                {
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
                }
            }
            
         $i=0;
 
         $t = DB::table('users')
             ->select('company_logo','company_logo_file_platform')
             ->where(['id'=>$user_id])
             ->first();
 
         $logoImage= $t->company_logo;

         $company_logo_file_platform = $t->company_logo_file_platform;

         if(isset($request->input('type')[$i]))
         {
 
             foreach ($request->input('type') as $value) 
             {
 
             
                 if(isset($request->input('type_id')[$i]))
                 {
 
                     $type_id = $request->input('type_id')[$i];
                     $b_data = 
                     [
                        'parent_id'     =>$business_id,
                        'business_id'   =>$user_id,
                         'contact_type'  =>$request->input('type')[$i],
                         'designation'   =>$request->input('add_designation')[$i],
                         'first_name'    =>$request->input('add_first_name')[$i],
                         'last_name'     =>$request->input('add_last_name')[$i],
                         'email'         =>$request->input('add_email')[$i],
                         'phone'         =>$request->input('add_phone')[$i],
                         'landline_number'=>$request->input('add_landline_number')[$i],
                         'updated_by'    =>Auth::user()->id, 
                         'updated_at'     => date('Y-m-d H:i:s')
                     ];
 
                     DB::table('vendor_business_contacts')->where(['id'=>$type_id])->whereNotIn('contact_type',['owner'])->update($b_data);
                 }
                 else
                 {
                     $b_data = 
                     [
                        'parent_id'     =>$business_id,
                        'business_id'   =>$user_id,
                         'contact_type'  =>$request->input('type')[$i],
                         'designation'   =>$request->input('add_designation')[$i],
                         'first_name'    =>$request->input('add_first_name')[$i],
                         'last_name'     =>$request->input('add_last_name')[$i],
                         'email'         =>$request->input('add_email')[$i],
                         'phone'         =>$request->input('add_phone')[$i],
                         'landline_number'=>$request->input('add_landline_number')[$i],
                         'created_by'    =>Auth::user()->id, 
                         'created_at'     => date('Y-m-d H:i:s')
                     ];
 
                     DB::table('vendor_business_contacts')->insert($b_data);
 
                 }   
             
                 $i++;
 
             }
         
         }
 
         
         $s3_config = S3ConfigTrait::s3Config();

         if ($files = $request->file('company_logo')) 
         {
            $company_logo_file_platform = 'web';
            $destinationPath = public_path('uploads/company-logo/'); 
            $logoImage = date('Ymdhis').$request->file('company_logo')->getClientOriginalName();
            $vendor=DB::table('users')->select('company_logo')->where('id',$user_id)->first();
 
             if($vendor!=NULL && stripos($t->company_logo_file_platform,'web')!==false)
             {
                 $vendor_img=$vendor->company_logo;
                 if(File::exists(public_path().'/uploads/company-logo/'.$vendor_img))
                 {
                     File::delete(public_path().'/uploads/company-logo/'.$vendor_img);
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
 
         $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('last_name')));
        $user_data = 
        [
            
            'first_name'    =>$request->input('first_name'),
            'last_name'     =>$request->input('last_name'),
            'name'          =>$name,
            'email'         =>$request->input('email'),
            'status'        =>$request->input('status'),
            'company_logo'  =>$logoImage,
            'company_logo_file_platform' => $company_logo_file_platform,
            'phone'         =>$request->input('phone'),
            'updated_at'    =>date('Y-m-d H:i:s')
        ];

        $update=  User::find($user_id);
        $update->update($user_data);
       
       $vendor = 
        [
          
            'first_name'    =>$request->input('first_name'),
            'last_name'     =>$request->input('last_name'),
            'company_name'  =>$request->input('company'),
            'individual_name' =>$request->input('individual'),
                // 'service'       =>$request->input('service'),
            'state'         =>$request->input('state'),
            'city'          =>$request->input('city'),
            'pincode'       =>$request->input('pincode'),
            'address'       =>$request->input('address'),
            'country_id'    =>$request->input('country'),
            'email'         =>$request->input('email'),
            'phone'         =>$request->input('phone'),
            'status'        =>$request->input('status'), 
            'updated_by'    =>Auth::user()->id,
            'updated_at'    =>date('Y-m-d H:i:s')

        ];
         DB::table('vendors')->where('id',$vendor_id)->update($vendor);

         if($request->verifier=="individual"){
            DB::table('vendors')->where('id',$vendor_id)->update(['company_name'=>NULL]);   
        }
        if($request->verifier=="company"){
            DB::table('vendors')->where('id',$vendor_id)->update(['individual_name'=>NULL]);   
        }
             
        //update business info
        $b_data = 
        [
            'vendor_type'       =>$request->verifier,
            'company_name'          =>$request->input('company'),
            'address_line1'         =>$request->input('address'),
            'zipcode'               =>$request->input('pincode'),
            'city_id'               =>$request->input('city'),
            'state_id'              =>$request->input('state'),
            'country_id'            =>$request->input('country'),
            'city_name'               =>$city_name->name,
            'state_name'              =>$state_name->name,
            'country_name'            =>$country_name->name,
            'email'                 =>$request->input('business_email'),
            'phone'                 =>$business_phone,
            'phone_code'            => $request->primary_phone_code2,
            'phone_iso'             => $request->primary_phone_iso2,
            'gst_number'            =>$request->input('gst_number'),
            'tin_number'            =>$request->input('tin_number'),
            'pan_number'            => $request->input('pan_number'),
            'contract_signed_by'    =>$request->input('contract_signed_by'),
            'website'               => $request->input('website'),
            'updated_by'             =>Auth::user()->id, 
            'updated_at'            => date('Y-m-d H:i:s')
        ];
        
        DB::table('vendor_businesses')->where(['business_id'=>$user_id])->update($b_data);
        $old_vendor_id = DB::table('vendor_businesses')->where(['business_id'=>$user_id])->first();
        if ($old_vendor_id->vendor_id==NULL) {
            DB::table('vendor_businesses')->where(['business_id'=>$user_id])->update(['vendor_id'=>$vendor_id]);
        }
        //contact info
        //owner contact
        $b_data = 
        [
            'designation'   =>$request->input('owner_designation'),
            'first_name'    =>$request->input('owner_first_name'),
            'last_name'     =>$request->input('owner_last_name'),
            'email'         =>$request->input('owner_email'),
            'phone'         =>$owner_phone,
            'phone_code'            => $request->primary_phone_code3,
            'phone_iso'             => $request->primary_phone_iso3,
            'landline_number'=>$request->input('owner_landline_number'),
            'updated_by'    =>Auth::user()->id, 
            'updated_at'    => date('Y-m-d H:i:s')
        ];
        
        DB::table('vendor_business_contacts')->where(['business_id'=>$user_id,'contact_type'=>'contact_person'])->update($b_data);
        
          //contract files
       if($request->has('fileID'))
       {
           foreach ($request->input('fileID') as $value) 
           {
               $file_data = 
               [
                   'parent_id' => $business_id,
                   'business_id' =>$user_id,
                   'is_temp'     => '0'
               ];
           
               DB::table('vendor_business_attachments')->where(['id'=>$value])->update($file_data);
           }
       }

       return response()->json([
            'success' => true,
            'errors' => '',
       
       ]);
        //  return redirect()
        //      ->route('/admin/vendor')
        //      ->with('success', 'Vendor updated successfully.'); 

     }

     public function vendorProfile($id)
     {
        $vendor_id = base64_decode($id);
        $profile = DB::table('vendors as v')
                ->join('users as u','v.user_id','=','u.id') 
                  ->select('v.id','v.company_name','v.first_name','v.last_name','v.email','v.phone','v.status','u.name','u.id as user_id')
                  ->where(['v.id'=>$vendor_id,'u.is_deleted'=>0])
                  ->first();

        return view('admin.vendors.profile',compact('profile'));
     }

     public function vendorBusinessInfo(Request $request,$id)
     {
        $vendor_id = base64_decode($id);

        $business_id =Auth::user()->business_id;

        if($request->isMethod('get'))
        {
            $profile = DB::table('vendors as v')
                ->select('v.*','u.id as user_id')
                ->join('users as u','v.user_id','=','u.id') 
                ->where(['v.id'=>$vendor_id])
                ->first();

            $business = DB::table('vendor_businesses as b')
                ->select('b.*')
                ->where(['b.business_id'=>$profile->user_id])
                ->first();

            $user =DB::table('users as u')
                ->select('u.company_logo','u.company_logo_file_platform')
                ->where(['u.id'=>$profile->user_id])
                ->first();

            $countries      = DB::table('countries')->get();

            $state          = DB::table('states')->where('country_id',$business->country_id)->get();
            $cities          = DB::table('cities')->where('state_id',$business->state_id)->get();

            return view('admin.vendors.business-info',compact('profile','vendor_id','business','user','state','cities','countries'));
        }

        $rules= [
            'address'   => 'required',
            'pincode'   => 'required|digits_between:5,6|numeric',
            'city'      => 'required',
            'state'     => 'required',
            'country'   => 'required',
            'company'   => 'required_if:verifier,company',
            'individual'   => 'required_if:verifier,individual',
            'business_email'            => 'required|email:rfc,dns',
            'business_phone_number'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|digits_between:10,10|numeric',
            'gst_number'                => 'nullable|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/|min:15|max:15',
            'contract_signed_by'        => 'required',
            'pan_number'                => 'required_if:verifier,company|nullable|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/|min:10|max:10',
            'tin_number'                => 'nullable|numeric|digits:11',

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
            $user_id   = $request->input('user_id');
            
            $business_phone = preg_replace('/\D/', '', $request->input('business_phone_number'));
            $country_name      = DB::table('countries')->where('id',$request->country)->first();
            $state_name          = DB::table('states')->where('id',$request->state)->first();
            $city_name          = DB::table('cities')->where('id',$request->city)->first();

            $vendor_type = DB::table('vendor_businesses')->select('vendor_type')->where(['vendor_id'=>$vendor_id])->first();
            if($vendor_type)
            {
                if ($vendor_type->vendor_type=='individual' && $request->verifier=='company') {
                    //    dd('done');
                    // Verification of GST Number
                    if($request->gst_number!=NULL)
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
                    if($request->pan_number!=NULL)
                    {
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
                    }
                }
            }
            elseif ($request->verifier=='company') {
                    if($request->gst_number!=NULL)
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
                    if($request->pan_number!=NULL)
                    {
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
                    }
            }
            else{
                if($request->gst_number!=NULL)
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
                if($request->pan_number!=NULL)
                {
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
                }
            }

            $i=0;
 
            $t = DB::table('users')
                ->select('company_logo','company_logo_file_platform')
                ->where(['id'=>$user_id])
                ->first();
    
            $logoImage= $t->company_logo;

            $company_logo_file_platform = $t->company_logo_file_platform;

            $s3_config = S3ConfigTrait::s3Config();

            if ($files = $request->file('company_logo')) 
            {
                $company_logo_file_platform = 'web';
                $destinationPath = public_path('uploads/company-logo/'); 
                $logoImage = date('Ymdhis').$request->file('company_logo')->getClientOriginalName();
                $vendor=DB::table('users')->select('company_logo')->where('id',$user_id)->first();
    
                if($vendor!=NULL && stripos($t->company_logo_file_platform,'web')!==false)
                {
                    $vendor_img=$vendor->company_logo;
                    if(File::exists(public_path().'/uploads/company-logo/'.$vendor_img))
                    {
                        File::delete(public_path().'/uploads/company-logo/'.$vendor_img);
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

            $user_data = 
            [
                'company_logo'  =>$logoImage,
                'company_logo_file_platform' => $company_logo_file_platform,
                'updated_at'    =>date('Y-m-d H:i:s')
            ];
    
            $update=  User::find($user_id);
            $update->update($user_data);
           
           $vendor = 
            [
                'company_name'  =>$request->input('company'),
                'individual_name' =>$request->input('individual'),
                'state'         =>$request->input('state'),
                'city'          =>$request->input('city'),
                'pincode'       =>$request->input('pincode'),
                'address'       =>$request->input('address'),
                'country_id'    =>$request->input('country'),
                'updated_by'    =>Auth::user()->id,
                'updated_at'    =>date('Y-m-d H:i:s')
    
            ];
             DB::table('vendors')->where('id',$vendor_id)->update($vendor);
    
            if($request->verifier=="individual"){
                DB::table('vendors')->where('id',$vendor_id)->update(['company_name'=>NULL]);   
            }
            if($request->verifier=="company"){
                DB::table('vendors')->where('id',$vendor_id)->update(['individual_name'=>NULL]);   
            }

            //update business info
            $b_data = 
            [
                'vendor_type'           =>$request->verifier,
                'company_name'          =>$request->input('company'),
                'address_line1'         =>$request->input('address'),
                'zipcode'               =>$request->input('pincode'),
                'city_id'               =>$request->input('city'),
                'state_id'              =>$request->input('state'),
                'country_id'            =>$request->input('country'),
                'city_name'             =>$city_name->name,
                'state_name'            =>$state_name->name,
                'country_name'          =>$country_name->name,
                'email'                 =>$request->input('business_email'),
                'phone'                 =>$business_phone,
                'phone_code'            => $request->primary_phone_code1,
                'phone_iso'             => $request->primary_phone_iso1,
                'gst_number'            =>$request->input('gst_number'),
                'tin_number'            =>$request->input('tin_number'),
                'pan_number'            => $request->input('pan_number'),
                'contract_signed_by'    =>$request->input('contract_signed_by'),
                'website'               => $request->input('website'),
                'updated_by'            =>Auth::user()->id, 
                'updated_at'            => date('Y-m-d H:i:s')
            ];
            
            DB::table('vendor_businesses')->where(['business_id'=>$user_id])->update($b_data);
            $old_vendor_id = DB::table('vendor_businesses')->where(['business_id'=>$user_id])->first();
            if ($old_vendor_id->vendor_id==NULL) {
                DB::table('vendor_businesses')->where(['business_id'=>$user_id])->update(['vendor_id'=>$vendor_id]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'errors' => '',
           ]);

         }
         catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
         } 


     }

     public function vendorSla($id)
     {
        $vendor_id = base64_decode($id);
        $profile = DB::table('vendors as v')
                ->join('users as u','v.user_id','=','u.id') 
                  ->select('v.id','v.company_name','v.first_name','v.last_name','v.email','v.phone','v.status','v.user_id','u.name')
                  ->where(['v.id'=>$vendor_id,'u.is_deleted'=>0])
                  ->first();
        $vendor_slas = DB::table('vendor_slas')
                    ->where(['business_id'=>$profile->user_id])->get();
                //   dd($profile);
         return view('admin.vendors.sla',compact('profile','vendor_slas'));
     }

     public function vendorCheckPrice($id)
     {
        $vendor_id = base64_decode($id);
        $profile = DB::table('vendors as v')
                ->join('users as u','v.user_id','=','u.id') 
                  ->select('v.id','v.company_name','v.first_name','v.last_name','v.email','v.phone','v.status','v.user_id','u.name')
                  ->where(['v.id'=>$vendor_id,'u.is_deleted'=>0])
                  ->first();
        $service_item = DB::table('vendor_service_items')->where('vendor_id',$vendor_id)->get();
        // $vendor_slas = DB::table('vendor_slas')
        //             ->where(['business_id'=>$profile->user_id])->get();
                //   dd($profile);
         return view('admin.vendors.check-price',compact('profile','service_item'));
     }
     public function vendorCheckPriceCreate($id)
     {
        
       $business_id = Auth::user()->business_id;
       $vendor_id = base64_decode($id);
       $profile = DB::table('vendors as v')
               ->join('users as u','v.user_id','=','u.id') 
                 ->select('v.id','v.company_name','v.first_name','v.last_name','v.email','v.phone','v.status','v.user_id','u.name')
                 ->where(['v.id'=>$vendor_id,'u.is_deleted'=>0])
                 ->first();
                 $service_id=[];
       $service_item = DB::table('vendor_service_items')->select('service_id')->where('vendor_id',$vendor_id)->get();
       foreach ($service_item as $item) {
          $service_id[]=$item->service_id;
       }
    

        $services = DB::table('services')->select('*')->where(['verification_type'=>'Manual','status'=>'1'])->whereNotIn('id',$service_id)
        ->get();
                //   dd($services);
         return view('admin.vendors.checkpricecreate',compact('profile','services'));
     }

      /**
     * store the data.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function vendorCheckPriceSave(Request $request)
    {

        $vendor_id =$request->vendor;
        $business_id = $request->business_id;

        $rules= 
        [
           
            'services'   => 'required',
            'price'        => 'required|numeric', 
            
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

         
        $parent_id = Auth::user()->business_id;

       
            $data = [
                        'business_id'=> $business_id,
                        'parent_id'  => Auth::user()->business_id,
                        'vendor_id' =>$vendor_id,
                        'service_id'      => $request->input('services'),
                        'price'        => $request->input('price'),
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s')
                    ];

            $sla_id = DB::table('vendor_service_items')->insertGetId($data);


        return response()->json([
            'success' =>true,
            'custom'  =>'yes',
            'errors'  =>[]
        ]);
    }

    public function vendorCheckPriceEdit($id,$service_item_id)
    {
       
      $business_id = Auth::user()->business_id;
      $vendor_id = base64_decode($id);
      $service_item_id = base64_decode($service_item_id);
      $profile = DB::table('vendors as v')
              ->join('users as u','v.user_id','=','u.id') 
                ->select('v.id','v.company_name','v.first_name','v.last_name','v.email','v.phone','v.status','v.user_id','u.name')
                ->where(['v.id'=>$vendor_id,'u.is_deleted'=>0])
                ->first();
        //             $service_id=[];
        $service_item = DB::table('vendor_service_items')->select('id','service_id','price')->where('id',$service_item_id)->first();
        //   foreach ($service_item as $item) {
        //      $service_id[]=$item->service_id;
        //   }
   

       $services = DB::table('services')->select('*')->where(['verification_type'=>'Manual','status'=>'1'])
       ->get();
               //   dd($services);
        return view('admin.vendors.checkpriceedit',compact('profile','services','service_item'));
    }

      /**
     * store the data.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function vendorCheckPriceUpdate(Request $request)
    {

        $vendor_id =$request->vendor;
        $service_item_id = base64_decode($request->service_item_id);
        // dd($service_item_id);

        $rules= 
        [
           
            'services'   => 'required',
            'price'        => 'required|numeric', 
            
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

         
        

       
            $data = [
                       
                        
                        'price'        => $request->input('price'),
                        'updated_by' => Auth::user()->id,
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    DB::table('vendor_service_items')->where(['id'=>$service_item_id])->update($data);

                                // $sla_id = DB::table('vendor_service_items')->insertGetId($data);
                    // $business_id = Auth::user()->business_id;
                    // $data = [
                    //     'tat'        =>$request->input('tat'),
                        
                    //     'title'      =>$request->input('name'),
                    //     'updated_by'    =>Auth::user()->id,
                    //     'updated_at' =>date('Y-m-d H:i:s')
                    // ];

                    // $sla_id = $request->input('sla_id');
                    // DB::table('vendor_slas')->where(['id'=>$request->input('sla_id')])->update($data);

        return response()->json([
            'success' =>true,
            'custom'  =>'yes',
            'errors'  =>[]
        ]);
    }

      // add file.
   public function uploadFile(Request $request)
   {        
     // dd($request);
     $extensions = array("pdf","jpg","png","jpeg","PNG","JPG","JPEG");
     $result = array($request->file('file')->getClientOriginalExtension());
 
        if($request->hasFile('file')) {
    
            if(in_array($result[0],$extensions))
            {                      
                    // $label_file_name  = $request->input('label_file_name');
                    $file_platform = 'web';
                    $attachment_file  = $request->file('file');
                    $orgi_file_name   = $attachment_file->getClientOriginalName();
                    
                    $fileName = pathinfo($orgi_file_name,PATHINFO_FILENAME);
            
                    $filename         = time().'-'.$fileName.'.'.$attachment_file->getClientOriginalExtension();
                    $dir              = public_path('/uploads/vendor-files/');            
                    

                    $s3_config = S3ConfigTrait::s3Config();

                    if($s3_config!=NULL)
                    {
                        $file_platform = 's3';

                        $path = 'uploads/vendor-files/';

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
                        
                    $business_id  = NULL;
                    $is_temp = 1;
            
                    //check if 
                    if($request->has('vendor_id')) {
                        $business_id = base64_decode($request->input('vendor_id'));
                    }
                    
                    $rowID = DB::table('vendor_business_attachments')            
                            ->insertGetId([
                                'parent_id' => Auth::user()->business_id,
                                'business_id'  => $business_id,                       
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
                        // if($ext == 'doc' || $ext == 'docx')
                        // {
                        //     $type = url('/').'/admin/images/icon_docx.png';
                        // }
                        // if($ext == 'xls' || $ext == 'xlsx' || $ext == 'csv')
                        // {
                        //     $type = url('/').'/admin/images/icon_xlsx.png';
                        // }
                        // if($ext == 'pptx' || $ext == 'ppt')
                        // {
                        //     $type = url('/').'/admin/images/icon_pptx.png';
                        // }
                        // if($ext == 'psd' || $ext == 'PSD')
                        // {
                        //     $type = url('/').'/admin/images/icon_psd.png';
                        // }
                        if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'JPG' || $ext == 'PNG' || $ext == 'JPEG'  || $ext == 'GIF')
                        { 
                            if(stripos($file_platform,'s3')!==false)
                            {
                                $filePath = 'uploads/vendor-files/';

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
                                $type = url('/').'/uploads/vendor-files/'.$filename;
                            }            
                        }
                    }           
        
                    return response()->json([
                        'fail' => false,
                        'file_id' => $rowID,
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

     /**
     * Show the general. 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function vendorSlaCreate($id)
    {
       $business_id = Auth::user()->business_id;
       $vendor_id = base64_decode($id);
       $profile = DB::table('vendors as v')
               ->join('users as u','v.user_id','=','u.id') 
                 ->select('v.id','v.company_name','v.first_name','v.last_name','v.email','v.phone','v.status','v.user_id','u.name')
                 ->where(['v.id'=>$vendor_id,'u.is_deleted'=>0])
                 ->first();
       

        $services = DB::table('services')
                    ->select('*')
                    ->where(['verification_type'=>'Manual','status'=>1])
                    ->get();
        //dd($services);            

        return view('admin.vendors.sla-create',compact('services','profile'));
    }

    
    /**
     * store the data.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function vendorSlaSave(Request $request)
    {

        $vendor_id =$request->vendor;
        $business_id = $request->business_id;

        $rules= 
        [
           
            'name'       => 'required', 
            'tat'        => 'required|numeric', 
            'services'   => 'required|array|min:1',
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

         //validation for no_of_verification and check_tat
         if( count($request->input('services') ) > 0 ){
            foreach($request->input('services') as $service){

                $rules=[
                    
                    'tat-'.$service => 'required|integer|min:1'
                  ];
                  $customMessages=[
                    
                    'tat-'.$service.'.required' => 'No of TAT is required',
                    'tat-'.$service.'.integer' => 'No of TAT should be numeric',
                    'tat-'.$service.'.min' => 'No of TAT should be atleast 1',
                  ];
                    $validator = Validator::make($request->all(), $rules,$customMessages);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ]);
                    }

            }
         }
        $parent_id = Auth::user()->business_id;

        $check_name = DB::table('vendor_slas')->where(['business_id'=>$business_id, 'title'=>$request->input('name')])->count();

        if($check_name > 0 ){

            return response()->json([
                'success' => false,
                'custom'=>'yes',
                'errors' => ['name'=>'SLA name is already exist!']
              ]);

        }

       
            $data = [
                        'business_id'=> $business_id,
                        'parent_id'  => Auth::user()->business_id,
                        'vendor_id' =>$vendor_id,
                        'title'      => $request->input('name'),
                        'tat'        => $request->input('tat'),
                        
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s')
                    ];

            $sla_id = DB::table('vendor_slas')->insertGetId($data);

            $i = 0;
            // $number_of_verifications =1;
    
            $no_of_tat=1;
           
            if( count($request->input('services') ) > 0 ){
                foreach($request->input('services') as $service){
    
                //    $number_of_verifications = $request->input('service_unit-'.$service);
                   $notes = $request->input('notes-'.$service);
    
                   $no_of_tat = $request->input('tat-'.$service);
    
                //    $service_d=DB::table('services')->where('id',$service)->first();
                    
                    $data = [
                        'business_id'=> $business_id,
                        'parent_id'  => Auth::user()->business_id,
                        'vendor_id' =>$vendor_id,
                        'sla_id'        =>$sla_id,
                       'service_id'    =>$service,
                       'notes'      =>$notes,
                       'tat'        =>$no_of_tat,
                       'created_by' => Auth::user()->id,
                        'created_at'    =>date('Y-m-d H:i:s')
                    ];
    
                    DB::table('vendor_sla_items')->insert($data);
                    $i++;
                }
            }
    

        return response()->json([
            'success' =>true,
            'custom'  =>'yes',
            'errors'  =>[]
        ]);
    }


     /**
     * Show the general. 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function vendorSlaEdit($id,$sla_id)
    {
       $business_id = Auth::user()->business_id;
       $vendor_id = base64_decode($id);
       $sla_id = base64_decode($sla_id);
        //    dd($sla_id);
       
       $profile = DB::table('vendors as v')
               ->join('users as u','v.user_id','=','u.id') 
                 ->select('v.id','v.company_name','v.first_name','v.last_name','v.email','v.phone','v.status','v.user_id','u.name')
                 ->where(['v.id'=>$vendor_id,'u.is_deleted'=>0])
                 ->first();
       

        $sla = DB::table('vendor_slas as vs')
        ->join('vendor_businesses as vb','vb.business_id','=','vs.business_id')
        ->select('vs.*','vb.company_name')
        ->where(['vs.vendor_id'=>$vendor_id,'vs.id'=>$sla_id])
        ->first();
        // dd($sla);
         $sla_items = DB::table('vendor_sla_items as sla')
         ->select('s.id','s.name','s.verification_type','sla.notes','sla.id as sla_item_id','sla.tat as check_tat')
         ->join('services as s','s.id','=','sla.service_id')
         ->where(['sla.vendor_id'=>$vendor_id,'sla.sla_id'=>$sla_id])
         ->get();
        //  dd($sla_items);
         $selected_services_id = [];
         foreach($sla_items as $item){
             $selected_services_id[] = $item->id;
         }
 

        $services = DB::table('services')->select('*')->where(['verification_type'=>'Manual','status'=>'1'])
        ->get();

        return view('admin.vendors.sla-edit',compact('services','profile','sla','sla_items','selected_services_id'));
    }

    public function vendorSlaUpdate(Request $request)
    {
        // dd($request);
        // $this->validate($request, 
        // [
        //     'name'      => 'required', 
        //     'services'   => 'required|array|min:1',
        //     'tat'        => 'required|numeric|lte:client_tat', 
        //     'client_tat' => 'required|numeric|gte:tat', 
        // ]);

        $vendor_id =$request->vendor;
        $business_id = $request->business;
        $rules= 
        [
            // 'customer'   => 'required', 
            'name'       => 'required', 
            'tat'        => 'required|numeric', 
           
            'services'   => 'required|array|min:1',
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $check_name = DB::table('vendor_slas')
        ->where(['business_id'=>$business_id, 'title'=>$request->input('name')])
        ->where('title','<>',$request->input('sla_name'))
        ->count();

        if($check_name > 0 ){

            return response()->json([
                'success' => false,
                'custom'=>'yes',
                'errors' => ['name'=>'SLA name is already exist!']
              ]);

        }

        //validation for no_of_verification and check_tat
        if( count($request->input('services') ) > 0 ){
            foreach($request->input('services') as $service){

                $rules=[
                   
                    'tat-'.$service => 'required|integer|min:1'
                  ];
                  $customMessages=[
                   
                    'tat-'.$service.'.required' => 'No of TAT is required',
                    'tat-'.$service.'.integer' => 'No of TAT should be numeric',
                    'tat-'.$service.'.min' => 'No of TAT should be atleast 1',
                  ];
                    $validator = Validator::make($request->all(), $rules,$customMessages);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ]);
                    }

            }
        }


        // $business_id = Auth::user()->business_id;
        $data = [
                    'tat'        =>$request->input('tat'),
                    
                    'title'      =>$request->input('name'),
                    'updated_by'    =>Auth::user()->id,
                    'updated_at' =>date('Y-m-d H:i:s')
                ];
        
        $sla_id = $request->input('sla_id');
        DB::table('vendor_slas')->where(['id'=>$request->input('sla_id')])->update($data);
                
        //update service items
        DB::table('vendor_sla_items')->where(['sla_id'=>$request->input('sla_id')])->delete();
        
        $i = 0;
        // $number_of_verifications =1;
        $no_of_tat=1;
        if( count($request->input('services') ) > 0 ){
            foreach($request->input('services') as $service){
 
            //    $number_of_verifications = $request->input('service_unit-'.$service);
               $notes = $request->input('notes-'.$service);
               $no_of_tat = $request->input('tat-'.$service);

               $service_d=DB::table('services')->where('id',$service)->first();
             
                    $data = [
                        'business_id'   =>$business_id,
                        'parent_id'  => Auth::user()->business_id,
                        'vendor_id' =>$vendor_id,
                        'sla_id'        =>$sla_id,
                        'service_id'    =>$service,
                        'notes'         =>$notes,
                        'tat'           => $no_of_tat,
                        'created_by'    =>Auth::user()->id,
                        'created_at'    =>date('Y-m-d H:i:s')
                    ];
                    DB::table('vendor_sla_items')->insert($data);
            
                $i++;
            }
        }



        // return redirect('/sla')
        //     ->with('success', 'SLA updated successfully.');

        return response()->json([
            'success' =>true,
            'custom'  =>'yes',
            'errors'  =>[]
        ]);
        
    }


}
