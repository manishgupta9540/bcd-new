<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Models\Admin\CandidateReinitiate;
use App\Traits\SMSTrait;

class CandidateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }
    
    //demo method
    public function list()
    {
        return response()->json(['data' => ['mihtilesh','priyanka']], 200);
    }

    // send sms otp
    public function sendSMSOTP(Request $request)
    {
        // var_dump($request->phone_number); die;

        $validator = Validator::make($request->all(), [
                    'phone_number'  => 'required|digits:10',
                ]);

        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }

        //input data
        $phone_number  = $request->get('phone_number');
       
        $login = DB::table('candidate_reinitiates as u')
        ->select('u.id','u.user_type','u.status','u.email','u.first_name','u.parent_id','u.phone')
        ->where(['u.phone'=>$phone_number,'u.user_type'=>'candidate'])
        ->latest()->first();

        if ($login) {
            
            $digital_addr_verification= DB::table('digital_address_verifications')->where(['candidate_id'=>$login->id,'status'=>'1'])->get();
         
        }

        $otp=mt_rand(1000,9999);

        $sms_otp = 1111;
        //print_r($login);

        // check login email and password with company id
        if($login ===null)
        {  
          return response()->json(['status' => 'error','error_message'=>'It seems like phone number is not exist!, please contact to your agency'], 200);
        }

        // check  user is active or not
        if($login->status == 0)
        {  
            return response()->json(['status' => 'error','error_message'=>'Your profile is not active, please contact to your agency'], 200);
        }

        if(count($digital_addr_verification)==0)
        {  
            return response()->json(['status' => 'error','error_message'=>'No Address Verification Required for this candidate!, please contact to your agency'], 200);
        }

        // Send the OTP to candidate's email & sms
        
        if($login->phone!='1354825933')
        {
            if($login->email!=NULL && $login->email!='')
            {
                $sms_otp = $otp;

                $email = $login->email;
                $name  = $login->first_name;
                $sender = CandidateReinitiate::where('id',$login->parent_id)->first();

                $data  = array('name'=>$name,'email'=>$email,'otp'=>$sms_otp,'sender'=>$sender);

                Mail::send(['html'=>'mails.address-login-otp'], $data, function($message) use($email,$name) {
                    $message->to($email, $name)->subject
                        ('myBCD System - Your account credential');
                    $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                });
            }

            $sms_otp = $otp;

            $form_sms = SMSTrait::sendOTP($login->phone,$sms_otp);

        }
        

        //update sms otp
        DB::table('candidate_reinitiates')->where(['id'=>$login->id])->update(['sms_otp'=>"$sms_otp",'sms_otp_sent_at'=>date('Y-m-d H:i:s')]);
       
        //send data
        $successResponse =  ['status'=>'success',
                             'message'=>'SMS OTP sent!',
                             'data'=>[
                                'candidate_id'=>"$login->id",
                                'sms_otp'=>"$sms_otp"
                                ]
                            ]; 

        return response()->json($successResponse, 200);
       
    }

    // account login with sms otp
    public function verifySMSOTP(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
                    'phone_number'  => 'required',
                    'sms_otp'       => 'required',
                    'device_type'   => 'required|in:A,I',
                    'device_token'  => 'required',
                ]);


        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }
        
        $status=false;

        $address=[];
        //input data
        $phone_number  = $request->get('phone_number');
        $sms_otp       = $request->get('sms_otp');
        $device_type   = $request->get('device_type');
        $device_token  = $request->get('device_token');

        $login = DB::table('candidate_reinitiates as u')
        ->select('u.id','u.user_type','u.status','u.name','u.first_name','u.last_name','u.email','u.alternative_email','u.phone')
        ->where(['u.phone'=>$phone_number,'u.sms_otp'=>$request->input('sms_otp')])
        ->latest()->first();

        //print_r($login);

        // check login email and password with company id
        if($login ===null)
        {  
          return response()->json(['status' => 'error','message'=>'Login failed!, Provide correct sms opt!'], 200);
        }
        // check  user is active or not
        if($login->status == 0)
        {  
          return response()->json(['status' => 'error','message'=>'Your profile is not active, please contact to your agency'], 200);
        }
        //check user type 
        if(!(stripos($login->user_type,'candidate')!==false))
        {
          return response()->json(['status' => 'error','message'=>'Sorry system does not recognized your profile'], 200);
        }

        //update device token //device_token = I, A = AndroidDeviceToken
        $device_token_data = array();
        if($device_type == 'A'){
            $device_token_data=array('android_token'=>$device_token,'device_type'=>'A','ios_token'=>NULL,'is_user_logged'=>1,'token_updated_at'=>date('Y-m-d H:i:s'));}
        if($device_type == 'I'){
            $device_token_data=array('ios_token'=>$device_token,'device_type'=>'I','android_token'=>NULL,'is_user_logged'=>1,'token_updated_at'=>date('Y-m-d H:i:s'));
        }

        $isUpdated = DB::table('candidate_reinitiates')->where('id', '=', $login->id)
            ->update($device_token_data);

        $jaf_items=DB::table('jaf_form_data as j')
                        ->join('digital_address_verifications as d','d.jaf_id','=','j.id')
                        ->where(['j.candidate_id'=>$login->id,'j.service_id'=>'1','d.status'=>'1'])
                        ->get();
        
        if(count($jaf_items)>0)
        {
            $jaf_form_count=count($jaf_items);

            $addr_ver=DB::table('address_verifications as a')
                        ->select('a.*')
                        ->join('jaf_form_data as j','j.id','=','a.jaf_id')
                        ->join('digital_address_verifications as d','d.jaf_id','=','j.id')
                        ->where(['a.candidate_id'=>$login->id,'d.status'=>'1'])
                        ->get();

            if(count($addr_ver)==0)
            {
                $status=true;
                $address=[];
            }
            else if(count($addr_ver) < $jaf_form_count)
            {
                $status=true;
                foreach($addr_ver as $addr)
                {
                    $address_type = 'others';

                    if($addr->address_type!=NULL)
                    {
                        $address_type = $addr->address_type;
                    }

                    $address[]=['id'=>$addr_ver,'address_type'=>$address_type,'full_address'=>$addr->full_address,'country_id'=>$addr->country_id,'state'=>$addr->state_name,'city'=>$addr->city_name,'zipcode'=>$addr->zipcode];
                }
            }
            else
            {
                $status=false;
                foreach($addr_ver as $addr)
                {
                    $address_type = 'others';

                    if($addr->address_type!=NULL)
                    {
                        $address_type = $addr->address_type;
                    }

                    $address[]=['id'=>$addr_ver,'address_type'=>$address_type,'full_address'=>$addr->full_address,'country_id'=>$addr->country_id,'state'=>$addr->state_name,'city'=>$addr->city_name,'zipcode'=>$addr->zipcode];
                }
            }
        }
        else
        {
            $successResponse=[
                'status' => 'failed',
                'message' => 'No Address Required for this candidate!'
            ];

            return response()->json($successResponse, 200);
        }
        
        //send data
        $successResponse = array('status'=>'success',
            'data'=>
            array(
                'candidate_id'=>"$login->id",
                'first_name'=>$login->first_name,
                'last_name'=>$login->last_name,
                'fullname'=>ucfirst($login->name),
                'email'=>$login->email,
                'alternative_email'=>$login->alternative_email,
                'phone'=>$login->phone,
                'user_type'=>'candidate'
            ),
            'new_address_required' => $status,
            'address' =>$address
            ); 

        return response()->json($successResponse, 200);
       
    }  

    // candidate profile
    public function profile(Request $request)
    {  
        $candidate_id = "";

        //validate 
        if(request()->has('candidate_id'))
        {
            //debug 
            //var_dump(request()->candidate_id);
            //var_dump(request()->filled('candidate_id'));

            if(request()->filled('candidate_id'))
            {
                $candidate_id = request()->candidate_id;
            }
            else{
                return response()->json(array('status'=>'error','message'=>'Parameter value is empty.'), 200);  
            }


        }
        else
        {
            return response()->json(array('status'=>'error','message'=>'Required parameter is missing.'), 200);  
        }

        $user = DB::table('candidate_reinitiates as u')
        ->select('u.*')
        ->where(['u.id'=>$candidate_id,'u.user_type'=>'candidate'])
        ->first();

        //check if user data not fetch
        if ($user === null) {
            return response()->json(array('status'=>'error','message'=>'Resource is not found.'), 200);  
        }

        //default image
        $profile_image = "https://formdox.com/assets/user-img.jpg";
        if($user->profile_image !=NULL || !empty($user->profile_image) )
        {
        $profile_image = "https://formdox.com/upload/users/".$user->profile_image;
        }

        //
        // $last_name ="";
        // $name_data = explode(' ', $user->name);
        // if(count($name_data) > 1){
        //     $last_name = $name_data[1];
        // }
   
        $successResponse = ['status'=>'success',
                            'data'=>[
                                'profile'=>[
                                    'candidate_id'  =>"$user->id",
                                    'first_name'    =>$user->first_name,
                                    'middle_name'   => $user->middle_name,
                                    'last_name'     =>$user->last_name,
                                    'fullname'      =>$user->name,
                                    'email'         =>$user->email,
                                    'alternative_email' =>$user->alternative_email,
                                    'mobile'        =>$user->phone,
                                    'profile_image' =>$profile_image,
                                    ],
                                    'address'=>[
                                    'address'       =>"",
                                    'zipcode'       =>"",
                                    'city'          =>"",
                                    'state'         =>"",
                                    'profile_status'=>""
                                ]
                            ]]; 
        
        return response()->json($successResponse, 200);     
       
    }

     // send sms otp
     public function candidateVerificationForm(Request $request)
     {
      
        $validator = Validator::make($request->all(), [
            'candidate_id'  => 'required|digits:10',
        ]);
 
         if ($validator->fails()) {            
             return response()->json(['status' => 'error',
                                     'message'=>'The given data was invalid.',
                                     'errors'=> $validator->errors()], 200);
         }
 
         //input data
         $phone_number  = $request->get('phone_number');
        
         $login = DB::table('candidate_reinitiates as u')
         ->select('u.id','u.user_type','u.status')
         ->where(['u.phone'=>$phone_number])
         ->latest()->first();
         
         $otp=mt_rand(1000,9999);
         //print_r($login);
 
         // check login email and password with company id
         if($login ===null)
         {  
           return response()->json(['status' => 'error','error_message'=>'It seems like phone number is not exist!, please contact to your agency'], 200);
         }
 
         // check  user is active or not
         if($login->status == 0)
         {  
           return response()->json(['status' => 'error','error_message'=>'Your profile is not active, please contact to your agency'], 200);
         }
 
         //update sms otp
         DB::table('candidate_reinitiates')->where(['id'=>$login->id])->update(['sms_otp'=>$otp,'sms_otp_sent_at'=>date('Y-m-d H:i:s')]);
        
         //send data
         $successResponse =  ['status'=>'success',
                              'message'=>'OTP SMS sent!',
                              'data'=>[
                                 'candidate_id'=>"$login->id",
                                 'sms_otp'=>"$otp"
                                 ]
                             ]; 
 
         return response()->json($successResponse, 200);
        
     }
     
    // candidate addrerss verification form
    public function address_verification(Request $request)
    {  
        if($request->missing('candidate_id')){

            return response()->json(['status' => 'error',
                                     'message'=>'The given data was invalid.',
                                     'errors'=> 'Parameter is missing!'], 200);
        }

        // check data addres verification is required
        $data = DB::table('job_items')->where(['candidate_id'=>$request->input('candidate_id')])->first();

        $candidate_id = "";

        $successResponse = ['status'=>'success',
                            'data'=>[
                                    'form_name'=>'Address Verification',
                                    'form_inputs'  =>[
                                        ['inputType'=>'text','inputLabel'=>'First Name','required'=>true],
                                        ['inputType'=>'text','inputLabel'=>'Last Name','required'=>true],
                                        ['inputType'=>'text','inputLabel'=>'Phone','required'=>true],
                                        ['inputType'=>'text','inputLabel'=>'Email','required'=>true],
                                        ['inputType'=>'text','inputLabel'=>'Street address','required'=>true],
                                        ['inputType'=>'text','inputLabel'=>'Apartment/House or Building','required'=>true],
                                        ['inputType'=>'text','inputLabel'=>'Pincode','required'=>true],
                                        ['inputType'=>'text','inputLabel'=>'City/Town/District','required'=>true],
                                        ['inputType'=>'text','inputLabel'=>'State','required'=>true],
                                        ['inputType'=>'text','inputLabel'=>'Country','required'=>true],
                                        ['inputType'=>'radioGroup',
                                        'type_values'=>[
                                            ['name'=>'Permanent'],['name'=>'Current']
                                        ],
                                        'inputLabel'=>'Address Type','required'=>true],
                                        ['inputType'=>'text','inputLabel'=>'Ownership','required'=>true],
                                        ['inputType'=>'photo','inputLabel'=>'Profile Image','required'=>true],
                                        ['inputType'=>'photo','inputLabel'=>'Address Proof','required'=>true],
                                        
                                       ],
                                
                                    ],                            
                        ]; 
      
        return response()->json($successResponse, 200);     
       
    }


    // Save verifaction address 

    public function saveVerificationAddress(Request $request)
    {
        //validates the input data
        // var_dump($request->get('form_data')); die();
        $arr = $request->get('form_data');

        $validator = Validator::make($request->all(), [
                    'form_data'  => 'required',
                ]);

        // $validator = Validator::make($request->all(), [
        //             'candidate_id'  => 'required',
        //             'street_address'=> 'required',
        //             'house_building'=> 'required',
        //             'zipcode'       => 'required|digits:6',
        //             'city'          => 'required',
        //             'state'         => 'required',
        //             'country'           => 'required',
        //             'address_type'      => 'required',
        //             'ownership_type'    => 'required',
        //             'geo_full_address'  => 'required',
        //             'geo_city'          => 'required',
        //             'geo_state'         => 'required',
        //             'geo_country'   => 'required',
        //             'geo_latitude'  => 'required',
        //             'geo_longitude' => 'required',
        //             'profile_photo' => 'required',
        //             'address_proof_photo' => 'required',
        //             'device_type'   => 'required|in:A,I',
        //             'device_token'  => 'required',
        //         ]);

        // if($validator->fails()) {            
        //     return response()->json(['status' => 'error',
        //                             'message'=>'The given data was invalid.',
        //                             'errors'=> $validator->errors()], 200);
        // }

        //json_decode($request->get('form_data'), true);


        //input data
        $first_name         = $arr['data'][0]['First Name'];
        $last_name          = $arr['data'][1]['Last Name'];
        $phone              = $arr['data'][2]['Phone'];
        $email              = $arr['data'][3]['Email'];
        $street_address     = $arr['data'][4]['Street address'];
        $house_building     = $arr['data'][5]['Apartment/House or Building'];
        $zipcode            = $arr['data'][6]['Pincode'];
        $city               = $arr['data'][7]['City/Town/District'];
        $state              = $arr['data'][8]['State'];
        $country            = $arr['data'][9]['Country'];
        $address_type       = $arr['data'][13]['Address Type'];
        $ownership_type     = $arr['data'][10]['Ownership'];
        $candidate_id       = $arr['data'][14]['candidate_id'];
        $geo_full_address   = "";
        $geo_city           = $arr['data'][15]['geo_city'];
        $geo_state          = $arr['data'][15]['geo_state'];
        $geo_country        = $arr['data'][15]['geo_country'];
        $geo_latitude       = $arr['data'][15]['geo_latitude'];
        $geo_longitude      = $arr['data'][15]['geo_longitude'];
        $device_type        = "";
        $device_token       = "";

        //check candidate id
        $user = DB::table('candidate_reinitiates as u')
        ->select('u.id','u.business_id','u.user_type','u.status','u.name','u.first_name','u.last_name')
        ->where(['u.id'=>$candidate_id])
        ->first();

        // check login email and password with company id
        if($user ===null)
        {  
          return response()->json(['status' => 'error','message'=>'candidate is not valid!'], 200);
        }
        //check if address verification is requred /* it will add later */
        //check user type 
        if($user->user_type != 'candidate')
        {
          return response()->json(['status' => 'error','message'=>'Sorry system does not recognized your profile'], 200);
        }

        $profile_photo = $address_proof_photo = "";
        //upload profile_photo 
        if($request->hasFile('profile_photo') && $request->file('profile_photo') !="" ){
                $imagePath = public_path('/uploads/candidate-selfie/');                
                $image = $request->file('profile_photo');
                $profile_photo  = $candidate_id.'-'.date('mdYHis').'-'.$request->file('profile_photo')->getClientOriginalName();        
                $data = $image->move($imagePath, $profile_photo);   
                                      
        }

         //upload address_proof_photo 
        if($request->hasFile('address_proof_photo') && $request->file('address_proof_photo') !=""){
                $imagePath = public_path('/uploads/address-proof/');                
                $image = $request->file('address_proof_photo');
                $address_proof_photo  = $candidate_id.'-'.date('mdYHis').'-'.$request->file('address_proof_photo')->getClientOriginalName();        
                $data = $image->move($imagePath, $address_proof_photo);   
                                      
        }

        //encoded image for profile
        $file = base64_decode($arr['data'][11]['Profile Image']);
        $selfi_photo = Str::random(10).'.'.'png';
        $success = file_put_contents(public_path('/uploads/candidate-selfie/').$selfi_photo, $file);

        //for address proof
        $file = base64_decode($arr['data'][12]['Address Proof']);
        $address_proof_1 = Str::random(10).'.'.'png';
        $success = file_put_contents(public_path('/uploads/address-proof/').$address_proof_1, $file);        
       

        //get lat and long from manual address
        $latitude   = 28.608721;
        $longitude  = 77.348900;
        //


        $address_data = [   
                            'first_name'    => $first_name,
                            'last_name'    => $last_name,
                            'email'         => $email,
                            'phone'         => $phone,
                            'business_id'   =>$user->business_id,
                            'candidate_id'  =>$candidate_id,
                            'selfi_photo'   =>$selfi_photo,
                            'address_proof_photo_1'=>$address_proof_1,
                            'address_line1' =>$street_address,
                            'address_line2' =>$house_building,
                            'zipcode'       =>$zipcode,
                            'city_name'     =>$city,
                            'state_name'    =>$state,
                            'latitude'      =>$latitude,
                            'longitude'     =>$longitude,
                            'geo_address'   =>$geo_full_address,
                            'geo_city'      =>$geo_city,
                            'geo_state'     =>$geo_state,
                            'geo_country'   =>$geo_country,
                            'geo_latitude'  =>$geo_latitude,
                            'geo_longitude' =>$geo_longitude,
                            'address_type'  =>$address_type,
                            'ownership_type'=>$ownership_type,
                            'created_at'    =>date('Y-m-d H:i:s'),
                            'form_json_data'=> json_encode($arr)
                        ];

        

                    DB::table('address_verifications')
                    ->insert($address_data);

        

        //send data
        $successResponse = ['status'=>'success',
                            'data'=>
                                [
                                'candidate_id'=>"$user->id",
                                ]
                            ]; 

        return response()->json($successResponse, 200);
       
    }

    // Address Verification
    public function addressSave(Request $request)
    {

        //print_r($request->file('profile_photo')); die;
        $custom=[
            'address_type.in' => 'Address Type would be permanent/Permanent or current/Current or others/Others',
            'candidate_id.integer' => 'Candidate ID Must be Numeric',
            'address_id.integer' => 'Address ID Must be Numeric',
        ];
        $validator = Validator::make($request->all(), [
            'candidate_id' => 'required|integer',
            'address_id'    => 'required|integer',
            'first_name'  => 'nullable',
            'last_name'     => 'nullable',
            'phone_number'  => 'nullable',
            'email_address'  => 'nullable|email:rfc,dns',
            'street_address'=> 'nullable',
            'house_building'=> 'nullable',
            'zipcode'       => 'nullable|digits:6',
            'city'          => 'nullable',
            'state'         => 'nullable',
            'country'           => 'nullable',
            'address_type'      => 'nullable|in:permanent,current,Permanent,Current,others,Others',
            'address'           => 'nullable',
            // 'nature_of_residence' => 'required',
            // 'period_stay_from'         => 'nullable|date',
            // 'period_stay_to'         => 'nullable|date',
            // 'verifier_name'         => 'required',
            // 'relation_with_verifier'         => 'required',
            // 'nearest_location'         => 'required',
            'ownership_type'    => 'nullable',
            // 'profile_photo' => 'required',
            // 'address_proof_photo' => 'required',
            // 'location_photo'         => 'required',
            // 'house_photo'         => 'required',
            'draw_signature'         => 'required',
            'draw_signature_latitude' => 'required',
            'draw_signature_longitude' => 'required',
            'device_type'   => 'required|in:A,I',
            'device_token'  => 'required',
        ],$custom);


        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }
         
 

        //input data
        $candidate_id  = $request->get('candidate_id');
        $address_id     = $request->get('address_id');
        $first_name  = $request->get('first_name');
        $last_name  = $request->get('last_name');
        $phone_number  = $request->get('phone_number');
        $email_address  = $request->get('email_address');
        $street_address  = $request->get('street_address');
        $house_building  = $request->get('house_building');
        $zipcode  = $request->get('zipcode');
        $city  = $request->get('city');
        $state  = $request->get('state');
        $country_id  = $request->get('country');
        $address_type  = $request->get('address_type');
        $address  = $request->get('address');
        $nature_of_residence  = $request->get('nature_of_residence');
        $period_stay_from  = $request->get('period_stay_from')!=NULL ? date('Y-m-d',strtotime($request->get('period_stay_from'))) : NULL;
        $period_stay_to  = $request->get('period_stay_to')!=NULL ? date('Y-m-d',strtotime($request->get('period_stay_to'))) : NULL;
        $verifier_name  = $request->get('verifier_name');
        $relation_with_verifier  = $request->get('relation_with_verifier');
        $nearest_location  = $request->get('nearest_location');
        $ownership_type  = $request->get('ownership_type');
        $geo_full_address   = $request->get('geo_address');
        $geo_city           = $request->get('geo_city');
        $geo_state          = $request->get('geo_state');
        $geo_country        = $request->get('geo_country');
        $geo_latitude       = $request->get('geo_latitude');
        $geo_longitude      = $request->get('geo_longitude');
        $device_type   = $request->get('device_type');
        $device_token  = $request->get('device_token');


        // $profile_photo = $address_proof_photo = "";
        //upload profile_photo 
        // $profile_photo_on_select=[];
        // $address_photo_on_select=[];
        // $location_photo_on_select=[];
        // $house_photo_on_select=[];
        // $sign_photo_on_select=[];
        $allowedextension=['jpg','jpeg','png','gif','svg','JPG','PDF','JPEG','PNG'];
        
        $user = DB::table('candidate_reinitiates as u')
        ->select('u.id','u.business_id','u.parent_id','u.user_type','u.status','u.name','u.first_name','u.last_name','u.display_id')
        ->where(['u.id'=>$candidate_id,'u.user_type'=>'candidate'])
        ->first();

        if($user!=NULL)
        {  
            DB::beginTransaction();
            try{

                $jaf_form_data = DB::table('jaf_form_data as j')
                                    ->select('j.*','s.name as service_name')
                                    ->join('services as s','s.id','=','j.service_id')
                                    ->where(['j.candidate_id'=>$candidate_id,'j.id'=> $address_id])
                                    ->first();
                
                if($jaf_form_data==NULL)
                {
                    return response()->json(['status' => 'error',
                                            'message'=>'The given data was invalid.',
                                            'errors'=> ['address_id'=>'Address ID Not Found !!']], 200);
                }

                $address_ver  = DB::table('address_verifications')->where(['candidate_id'=>$candidate_id,'jaf_id'=>$address_id])->first();

                if($address_ver!=NULL && $address_ver->status==1)
                {
                    return response()->json(['status' => 'error',
                                        'message'=>'The given data was invalid.',
                                        'errors'=> ['address_id'=>'Address Verification Form Has Already Been Submitted, Try Again with Some Other Check !!']], 200);
                }

                $country_name = NULL;

                $countries = DB::table('countries')->where('id',$country_id)->first();

                if($countries!=NULL)
                {
                    $country_name = $countries->name;
                }

                // if($request->hasFile('profile_photo') && $request->file('profile_photo') !="" ){
                //         $imagePath = public_path('/uploads/candidate-selfie/');  
                //         $files= $request->file('profile_photo');   
                //         // dd($files); 
                //         // $i=0;         
                //         foreach($files as $file)
                //         {
                //             $extension = $file->getClientOriginalExtension();
        
                //                 $check = in_array($extension,$allowedextension);

                //                 if($check)
                //                 {
                //                     $image = $file->getClientOriginalName();
                //                     $profile_photo  = $candidate_id.'-'.date('mdYHis').'-'.$image; 
                //                     $data = $file->move($imagePath, $profile_photo);       
                //                     $profile_photo_on_select[]=$profile_photo;
                //                 }
        
                //         }
                //         // $image = $request->file('profile_photo');
                //         // $profile_photo  = $candidate_id.'-'.date('mdYHis').'-'.$request->file('profile_photo')->getClientOriginalName();        
                //         // $data = $image->move($imagePath, $profile_photo);   
                                            
                // }

                // dd($profile_photo_on_select);

                //upload address_proof_photo 
                // if($request->hasFile('address_proof_photo') && $request->file('address_proof_photo') !=""){
                //         $imagePath = public_path('/uploads/address-proof/');
                //         $files= $request->file('address_proof_photo');   
                //         // dd($files); 
                //         // $i=0;         
                //         foreach($files as $file)
                //         {
                //             $extension = $file->getClientOriginalExtension();
        
                //                 $check = in_array($extension,$allowedextension);

                //                 if($check)
                //                 {
                //                     $image = $file->getClientOriginalName();
                //                     $address_proof_photo  = $candidate_id.'-'.date('mdYHis').'-'.$image; 
                //                     $data = $file->move($imagePath, $address_proof_photo);       
                //                     $address_photo_on_select[]=$address_proof_photo;
                //                 }
        
                //         }
                        
                //         // $image = $request->file('address_proof_photo');
                //         // $address_proof_photo  = $candidate_id.'-'.date('mdYHis').'-'.$request->file('address_proof_photo')->getClientOriginalName();        
                //         // $data = $image->move($imagePath, $address_proof_photo);   
                                            
                // }


                //upload location_photo 
                // if($request->hasFile('location_photo') && $request->file('location_photo') !="" ){
                //     $imagePath = public_path('/uploads/candidate-location/');
                //     $files= $request->file('location_photo');   
                //     foreach($files as $file)
                //     {
                //             $extension = $file->getClientOriginalExtension();

                //             $check = in_array($extension,$allowedextension);

                //             if($check)
                //             {
                //                 $image = $file->getClientOriginalName();
                //                 $location_photo  = $candidate_id.'-'.date('mdYHis').'-'.$image; 
                //                 $data = $file->move($imagePath, $location_photo);       
                //                 $location_photo_on_select[]=$location_photo;
                //             }

                //     }
                    
                //     // $image = $request->file('location_photo');
                //     // $location_photo  = $candidate_id.'-'.date('mdYHis').'-'.$request->file('location_photo')->getClientOriginalName();        
                //     // $data = $image->move($imagePath, $location_photo);   
                                        
                // }


                //upload house_photo 
                // if($request->hasFile('house_photo') && $request->file('house_photo') !="" ){
                //     $imagePath = public_path('/uploads/candidate-house/');
                //     $files= $request->file('house_photo');   
                //     foreach($files as $file)
                //     {
                //             $extension = $file->getClientOriginalExtension();

                //             $check = in_array($extension,$allowedextension);

                //             if($check)
                //             {
                //                 $image = $file->getClientOriginalName();
                //                 $house_photo  = $candidate_id.'-'.date('mdYHis').'-'.$image; 
                //                 $data = $file->move($imagePath, $house_photo);       
                //                 $house_photo_on_select[]=$house_photo;
                //             }

                //     }                
                //     // $image = $request->file('house_photo');
                //     // $house_photo  = $candidate_id.'-'.date('mdYHis').'-'.$request->file('house_photo')->getClientOriginalName();        
                //     // $data = $image->move($imagePath, $house_photo);   
                                        
                // }
                //upload draw_signature 
                // if($request->hasFile('draw_signature') && $request->file('draw_signature') !="" ){
                //     $imagePath = public_path('/uploads/candidate-signature/');
                //     $files= $request->file('draw_signature');   
                //     // foreach($files as $file)
                //     // {
                //             $extension = $files->getClientOriginalExtension();

                //             $check = in_array($extension,$allowedextension);

                //             if($check)
                //             {
                //                 $image = $files->getClientOriginalName();
                //                 $draw_signature  = $candidate_id.'-'.date('mdYHis').'-'.$image; 
                //                 $data = $files->move($imagePath, $draw_signature);       
                //                 $sign_photo_on_select[]=$draw_signature;
                //             }

                //     // }
                //     // $image = $request->file('draw_signature');
                //     // $draw_signature  = $candidate_id.'-'.date('mdYHis').'-'.$request->file('draw_signature')->getClientOriginalName();        
                //     // $data = $image->move($imagePath, $draw_signature);   
                                        
                // }
                // dd($sign_photo_on_select);

                //encoded image for profile
                // if(count($profile_photo_on_select)<=0){
                //     $file = base64_decode($request->file('profile_photo'));
                //     $selfi_photo = Str::random(10).'.'.'png';
                //     $success = file_put_contents(public_path('/uploads/candidate-selfie/').$selfi_photo, $file);
                // }

                //for address proof
                // if(count($address_photo_on_select)<=0)
                // {
                //     $file = base64_decode($request->file('address_proof_photo'));
                //     $address_proof_1 = Str::random(10).'.'.'png';
                //     $success = file_put_contents(public_path('/uploads/address-proof/').$address_proof_1, $file); 
                // }
            

                //for location_photo
                // if(count($location_photo_on_select)<=0)
                // {
                //     $file = base64_decode($request->file('location_photo'));
                //     $location_photo_1 = Str::random(10).'.'.'png';
                //     $success = file_put_contents(public_path('/uploads/candidate-location/').$location_photo_1, $file); 
                // }
            
                //for address proof
                // if(count($house_photo_on_select)<=0){
                //     $file = base64_decode($request->file('house_photo'));
                //     $house_photo_1 = Str::random(10).'.'.'png';
                //     $success = file_put_contents(public_path('/uploads/candidate-house/').$house_photo_1, $file);    
                // }
            
                //for Signature
                // if(count($sign_photo_on_select)<=0){
                //     $file = base64_decode($request->file('draw_signature'));
                //     $success = file_put_contents(public_path('/uploads/candidate-signature/').$draw_signature_1, $file); 
                // }

                if($request->draw_signature!=''){
                    // $file = base64_decode($request->draw_signature);
                    // $draw_signature_1 = date('Ymdhis').'-'.$candidate_id.'.png';
                    // $success = file_put_contents(public_path('/uploads/candidate-signature/').$draw_signature_1, $file);
                    
                    if(strpos($request->draw_signature,'data:image/') !==false)
                    {
                        $folderPath = public_path('uploads/candidate-signature/');
                
                        $image_parts = explode(";base64,", $request->draw_signature);
                            
                        $image_type_aux = explode("image/", $image_parts[0]);
                            
                        $image_type = $image_type_aux[1];
                            
                        $image_base64 = base64_decode($image_parts[1]);

                        // dd($image_base64);
                        
                        $draw_signature_1 = date('Ymdhis').'-'.$candidate_id.'.'.'jpeg';
                        $file = $folderPath . $draw_signature_1;
                        header('Content-Type: bitmap; charset=utf-8');
                        file_put_contents($file, $image_base64);
                    }
                    else
                    {
                        // $image = 'data:image/png;base64,'.$request->draw_signature;
                        // $image = str_replace(' ', '+', $image);
                        // $draw_signature_1 = date('Ymdhis').'-'.$candidate_id.'.'.'png';
                        // File::put(public_path().'/uploads/candidate-signature/' . $draw_signature_1, base64_decode($image)); 

                        $image = base64_decode($request->draw_signature);
                        $image_name ="";
                        $image_string   = $request->input('draw_signature');

                        //$filename = $filename;
                        $new_filename = date('Ymdhis').'-'.$candidate_id.'.jpeg';
                        // Decode Image
                        $binary=base64_decode($image_string);
                        header('Content-Type: bitmap; charset=utf-8');
                        // Images will be saved under 'www/uploads/users' folder
                        $file = fopen(public_path() . "/uploads/candidate-signature/".$new_filename, 'wb');
                        // Create File
                        fwrite($file, $binary);
                        $draw_signature_1 = $new_filename;
                    }


                }
            
                $draw_signature_latitude       = $request->get('draw_signature_latitude');
                $draw_signature_longitude      = $request->get('draw_signature_longitude');

                //get lat and long from manual address
                $latitude   = 28.608721;
                $longitude  = 77.348900;
                //check candidate id
                if($address_ver!=NULL)
                {
                    $address_data = [   
                        'first_name'    => $first_name,
                        'last_name'    => $last_name,
                        'email'         => $email_address,
                        'phone'         => $phone_number,
                        // 'selfi_photo'   =>count($profile_photo_on_select)>0?json_encode($profile_photo_on_select):$selfi_photo,
                        // 'address_proof_photo_1'=>count($address_photo_on_select)>0?json_encode($address_photo_on_select):$address_proof_1,
                        // 'location_photo'    => count($location_photo_on_select)>0?json_encode($location_photo_on_select):$location_photo_1,
                        // 'house_photo'       =>count($house_photo_on_select)>0?json_encode($house_photo_on_select):$house_photo_1,
                        // 'signature'         =>count($sign_photo_on_select)>0?json_encode($sign_photo_on_select):$draw_signature_1,
                        'signature'         =>$draw_signature_1!=""?$draw_signature_1:NULL,
                        'signature_latitude' => $draw_signature_latitude,
                        'signature_longitude' => $draw_signature_longitude,
                        'signature_file_platform' => 'app',
                        'address_line1' =>$street_address,
                        'address_line2' =>$house_building,
                        'full_address' =>$address,
                        'nature_of_residence' =>$nature_of_residence!=NULL ? strtolower($nature_of_residence) : NULL,
                        'period_stay_from'  => $period_stay_from,
                        'period_stay_to'   => $period_stay_to,
                        'verifier_name'   => $verifier_name,
                        'relation_with_verifier' => $relation_with_verifier,
                        'landmark'          => $nearest_location,
                        'zipcode'       =>$zipcode,
                        'country_id'       =>$country_id,
                        'country_name' => $country_name,
                        'city_name'     =>$city,
                        'state_name'    =>$state,
                        'latitude'      =>$latitude,
                        'longitude'     =>$longitude,
                        'geo_address'   =>$geo_full_address,
                        'geo_city'      =>$geo_city,
                        'geo_state'     =>$geo_state,
                        'geo_country'   =>$geo_country,
                        'geo_latitude'  =>$geo_latitude,
                        'geo_longitude' =>$geo_longitude,
                        'address_type'  =>$address_type,
                        'ownership_type'=>$ownership_type,
                        'status'  => 1,
                        'updated_at'    =>date('Y-m-d H:i:s')
                        
                    ];
            
                    DB::table('address_verifications')->where('id',$address_ver->id)->update($address_data);
                }
                else
                {
                    $address_data = [   
                        'first_name'    => $first_name,
                        'last_name'    => $last_name,
                        'email'         => $email_address,
                        'phone'         => $phone_number,
                        'business_id'   =>$user->business_id,
                        'candidate_id'  =>$candidate_id,
                        'jaf_id'        => $address_id,
                        // 'selfi_photo'   =>count($profile_photo_on_select)>0?json_encode($profile_photo_on_select):$selfi_photo,
                        // 'address_proof_photo_1'=>count($address_photo_on_select)>0?json_encode($address_photo_on_select):$address_proof_1,
                        // 'location_photo'    => count($location_photo_on_select)>0?json_encode($location_photo_on_select):$location_photo_1,
                        // 'house_photo'       =>count($house_photo_on_select)>0?json_encode($house_photo_on_select):$house_photo_1,
                        // 'signature'         =>count($sign_photo_on_select)>0?json_encode($sign_photo_on_select):$draw_signature_1,
                        'signature'         =>$draw_signature_1!=""?$draw_signature_1:NULL,
                        'signature_latitude' => $draw_signature_latitude,
                        'signature_longitude' => $draw_signature_longitude,
                        'signature_file_platform' => 'app',
                        'address_line1' =>$street_address,
                        'address_line2' =>$house_building,
                        'full_address' =>$address,
                        'nature_of_residence' =>$nature_of_residence,
                        'period_stay_from'  => $period_stay_from,
                        'period_stay_to'   => $period_stay_to,
                        'verifier_name'   => $verifier_name,
                        'relation_with_verifier' => $relation_with_verifier,
                        'landmark'          => $nearest_location,
                        'zipcode'       =>$zipcode,
                        'country_id'       =>$country_id,
                        'country_name' => $country_name,
                        'city_name'     =>$city,
                        'state_name'    =>$state,
                        'latitude'      =>$latitude,
                        'longitude'     =>$longitude,
                        'geo_address'   =>$geo_full_address,
                        'geo_city'      =>$geo_city,
                        'geo_state'     =>$geo_state,
                        'geo_country'   =>$geo_country,
                        'geo_latitude'  =>$geo_latitude,
                        'geo_longitude' =>$geo_longitude,
                        'address_type'  =>$address_type,
                        'ownership_type'=>$ownership_type,
                        'status'  => 1,
                        'created_at'    =>date('Y-m-d H:i:s'),
                        'created_by'   => $candidate_id
                        
                    ];
            
                    DB::table('address_verifications')
                    ->insert($address_data);
                }
                

                $kams = DB::table('users as u')
                            ->select('u.*')
                            ->join('key_account_managers as k','k.user_id','=','u.id')
                            ->where('k.business_id',$user->business_id)
                            ->get();

                if(count($kams)>0)
                {
                    $address_ver  = DB::table('address_verifications')->where(['candidate_id'=>$candidate_id,'jaf_id'=>$address_id])->first();

                    $sender = DB::table('users')->where('id',$user->parent_id)->first();
                    
                    foreach($kams as $kam)
                    {
                        $email = $kam->email;
                        $name = $kam->first_name;

                        $data = array('name'=>$name,'email'=>$email,'sender'=>$sender,'candidate'=>$user,'jaf_form'=>$jaf_form_data,'address_ver'=>$address_ver);

                        Mail::send(['html'=>'mails.address-save-notify'], $data, function($message) use($email,$name) {
                            $message->to($email, $name)->subject
                                ('myBCD System - Address Verification');
                            $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        });
                    }
                }

                DB::commit();

                //send data
                $successResponse = ['status'=>'success',
                'message'=>'Your address details has been submitted ']; 
            }
            catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return $e;
            }   
        }
        else
        {
            $successResponse = ['status'=>'failed',
            'message'=>'Candidate ID Not Found']; 
        }
        return response()->json($successResponse, 200);


    }

    //get address data of candidate
    public function get_address_verification_data(Request $request)
    {  
        if($request->missing('candidate_id')){

            return response()->json(['status' => 'error',
                                     'message'=>'The given data was invalid.',
                                     'errors'=> 'Parameter is missing!'], 200);
        }

        // check data addres verification is required
        $data = DB::table('address_verifications')
        ->where(['candidate_id'=>$request->input('candidate_id')])
        ->ordrBy('created_at','desc')
        ->first();

        if($data !=null){
            //append array data 
        
        $array1 = json_decode($data->form_json_data, true);

        $array1['data'][11]['Profile Image']= url('/')."/uploads/candidate-selfie/".$data->selfi_photo;

        $array1['data'][12]['Address Proof']= url('/')."/uploads/address-proof/".$data->address_proof_photo_1;


            $response = ['status'=>'success',
                        'data'=>$array1
                    ]; 
        }
        else{
        $response = ['status'=>'success',
                    'data'=>NULL
                    ];
        } 

        return response()->json($response, 200);
    }


    public function stateList(Request $request)
    {
       
        // $validator = Validator::make($request->all(), [
        //     'country_id'  => 'required',
            
        // ]);


        // if ($validator->fails()) {            
        //     return response()->json(['status' => 'error',
        //                             'message'=>'The given data was invalid.',
        //                             'errors'=> $validator->errors()], 200);
        // }

         //input data
        //  $country_id  = $request->get('country_id');

         $states = DB::table('states')->select('id','name')->where('country_id','101')->get();

         if($states !=null){
            //append array data 
        
      

            $response = ['status'=>true,
                        'data'=>$states
                    ]; 
        }
        else{
            $response = ['status'=>false,
                    'data'=>NULL
                    ];
        } 

        return response()->json($response, 200);
    }

    public function get_address_type_verification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'candidate_id'  => 'required',
            
        ]);


        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }

        $candidate_id  = $request->get('candidate_id');

        $array_result=[];

        $nature_of_residence = [];

        $nature_of_residence=[
                                array('name'=>'Rented'),
                                array('name'=>'Owned'),
                                array('name'=>'Others')
                            ];

        $candidates=DB::table('candidate_reinitiates as u')
                    ->select('j.id','s.name as service_name','j.check_item_number','j.address_type','j.form_data','u.display_id','ub.company_name','u.email','u.alternative_email')
                    ->join('jaf_form_data as j','j.candidate_id','=','u.id')
                    ->join('job_items as ji','j.candidate_id','=','ji.candidate_id')
                    ->join('digital_address_verifications as d','d.jaf_id','=','j.id')
                    ->join('services as s','s.id','=','j.service_id')
                    ->join('user_businesses as ub','ub.business_id','=','u.business_id')
                    ->where(['u.id'=>$candidate_id,'u.user_type'=>'candidate','ji.jaf_status'=>'filled','j.service_id'=>1,'d.status'=>'1'])
                    ->get();

        if(count($candidates)>0){
            //append array data 
            foreach($candidates as $item)
            {
                $address_ver = DB::table('address_verifications')->where(['candidate_id'=>$candidate_id,'jaf_id'=>$item->id])->first();

                $status = 'pending';

                $completed_date = NULL;

                $address_type = 'others';

                if($address_ver!=NULL && $address_ver->status==1)
                {
                    $status = 'completed';

                    $completed_date = $address_ver->updated_at!=NULL ? date('Y-m-d h:i A',strtotime($address_ver->updated_at)) : date('Y-m-d h:i A',strtotime($address_ver->created_at));
                }

                if($item->address_type!=NULL)
                {
                    $address_type = $item->address_type;
                }

                $candidate_address = $item->form_data;

                $addr = '';
                $zip = '';
                $state = '';
                $city = '';
                $first_name = '';
                $last_name = '';
                $contact_number = '';

                if($candidate_address!=null)
                {
                    $input_item_data_array =  json_decode($candidate_address, true);

                    foreach ($input_item_data_array as $key => $input) {
                        $key_val = array_keys($input);
                        $input_val = array_values($input);
                        // dd($key_val);
                        if(stripos($key_val[0],'Address')!==false){ 
                            
                            $addr =$input_val[0]!=NULL ? $input_val[0] : '';
                            // dd($addr);
                        }
                        if(stripos($key_val[0],'Pin Code')!==false){ 
                            // dd($input_val);
                            $zip =$input_val[0]!=NULL ? $input_val[0] : '';
                        }
                        if(stripos($key_val[0],'State')!==false){ 
                            
                            $state =$input_val[0]!=NULL ? $input_val[0] : '';
                        }
                        if(stripos($key_val[0],'City')!==false){ 
                            // dd($key_val);
                            $city =$input_val[0]!=NULL ? $input_val[0] : '';
                            // dd($city);
                        }
                        if(stripos($key_val[0],'First Name')!==false){ 
                            // dd($key_val);
                            $first_name =$input_val[0]!=NULL ? $input_val[0] : '';
                            // dd($city);
                        }
                        if(stripos($key_val[0],'Last Name')!==false){ 
                            // dd($key_val);
                            $last_name =$input_val[0]!=NULL ? $input_val[0] : '';
                            // dd($city);
                        }
                        if(stripos($key_val[0],'Contact Number')!==false){ 
                            // dd($key_val);
                            $contact_number =$input_val[0]!=NULL ? $input_val[0] : '';
                            // dd($city);
                        }
                    }
                }

                $array_result[]=[
                                    'reference_number' => $item->display_id,
                                    'client_name' => $item->company_name,
                                    'first_name' => $first_name,
                                    'last_name' => $last_name,
                                    'email' => $item->email!=NULL ? $item->email : '',
                                    'alternative_email' => $item->alternative_email!=NULL ? $item->alternative_email : '',
                                    'contact_number' => $contact_number,
                                    'address_id'=>$item->id,
                                    'address_type'=>ucfirst($address_type),
                                    // 'check_name'=>$item->service_name.' - '.$item->check_item_number,
                                    'status'=>$status,
                                    'completed_date_and_time'=>$completed_date,
                                    'check_name'=>$addr,
                                    'zipcode'=>$zip,
                                    'city'=>$city,
                                    'state'=>$state,
                                    'nature_of_residence' => $nature_of_residence
                                ];
            }
            $users=DB::table('candidate_reinitiates')->where(['id'=>$candidate_id,'user_type'=>'candidate'])->first();
            if($users!=NULL){
                $response = [   'status'=>true,
                                'candidate_name' => $users->name,
                                'data'=>$array_result,
                                
                            ]; 
            }
            else{
                $response = ['status'=>false,
                             'data'=>NULL
                            ];
            }
            
        }
        else{
            $response = ['status'=>false,
                         'data'=>NULL
                        ];
        }

        return response()->json($response, 200);

    }

    public function get_candidate_address_type_verification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'candidate_id'  => 'required',
            
        ]);


        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }

        $candidate_id  = $request->get('candidate_id');

        $array_result=[];

        $nature_of_residence = [];

        $nature_of_residence=[
                                array('name'=>'Rented'),
                                array('name'=>'Owned'),
                                array('name'=>'Others')
                            ];

        $candidates=DB::table('candidate_reinitiates as u')
                    ->select('j.id','s.name as service_name','j.check_item_number','j.address_type','j.form_data','u.display_id','ub.company_name','u.email','u.alternative_email')
                    ->join('jaf_form_data as j','j.candidate_id','=','u.id')
                    ->join('job_items as ji','j.candidate_id','=','ji.candidate_id')
                    ->join('digital_address_verifications as d','d.jaf_id','=','j.id')
                    ->join('services as s','s.id','=','j.service_id')
                    ->join('user_businesses as ub','ub.business_id','=','u.business_id')
                    ->where(['u.id'=>$candidate_id,'u.user_type'=>'candidate','ji.jaf_status'=>'filled','j.service_id'=>1,'d.status'=>'1'])
                    ->get();

        if(count($candidates)>0){
            //append array data 
            foreach($candidates as $item)
            {
                $address_ver = DB::table('address_verifications')->where(['candidate_id'=>$candidate_id,'jaf_id'=>$item->id])->first();

                $status = 'pending';

                $completed_date = NULL;

                $address_type = 'others';

                if($address_ver!=NULL && $address_ver->status==1)
                {
                    $status = 'completed';

                    $completed_date = $address_ver->updated_at ? date('Y-m-d h:i A',strtotime($address_ver->updated_at)) : date('Y-m-d h:i A',strtotime($address_ver->created_at));
                }

                if($item->address_type!=NULL)
                {
                    $address_type = $item->address_type;
                }

                $candidate_address = $item->form_data;

                $addr = '';
                $zip = '';
                $state = '';
                $city = '';
                $first_name = '';
                $last_name = '';
                $contact_number = '';

                if($candidate_address!=null)
                {
                    $input_item_data_array =  json_decode($candidate_address, true);

                    foreach ($input_item_data_array as $key => $input) {
                        $key_val = array_keys($input);
                        $input_val = array_values($input);
                        // dd($key_val);
                        if(stripos($key_val[0],'Address')!==false){ 
                            
                            $addr =$input_val[0]!=NULL ? $input_val[0] : '';
                            // dd($addr);
                        }
                        if(stripos($key_val[0],'Pin Code')!==false){ 
                            // dd($input_val);
                            $zip =$input_val[0]!=NULL ? $input_val[0] : '';
                        }
                        if(stripos($key_val[0],'State')!==false){ 
                            
                            $state =$input_val[0]!=NULL ? $input_val[0] : '';
                        }
                        if(stripos($key_val[0],'City')!==false){ 
                            // dd($key_val);
                            $city =$input_val[0]!=NULL ? $input_val[0] : '';
                            // dd($city);
                        }
                        if(stripos($key_val[0],'First Name')!==false){ 
                            // dd($key_val);
                            $first_name =$input_val[0]!=NULL ? $input_val[0] : '';
                            // dd($city);
                        }
                        if(stripos($key_val[0],'Last Name')!==false){ 
                            // dd($key_val);
                            $last_name =$input_val[0]!=NULL ? $input_val[0] : '';
                            // dd($city);
                        }
                        if(stripos($key_val[0],'Contact Number')!==false){ 
                            // dd($key_val);
                            $contact_number =$input_val[0]!=NULL ? $input_val[0] : '';
                            // dd($city);
                        }
                    }
                }

                $array_result[]=[
                                    'reference_number' => $item->display_id,
                                    'client_name' => $item->company_name,
                                    'first_name' => $first_name,
                                    'last_name' => $last_name,
                                    'email' => $item->email!=NULL ? $item->email : '',
                                    'alternative_email' => $item->alternative_email!=NULL ? $item->alternative_email : '',
                                    'contact_number' => $contact_number,
                                    'address_id'=>$item->id,
                                    'address_type'=>ucfirst($address_type),
                                    // 'check_name'=>$item->service_name.' - '.$item->check_item_number,
                                    'status'=>$status,
                                    'completed_date_and_time'=>$completed_date,
                                    'check_name'=>$addr,
                                    'zipcode'=>$zip,
                                    'city'=>$city,
                                    'state'=>$state,
                                    'nature_of_residence' => $nature_of_residence
                                ];
            }
            $users=DB::table('candidate_reinitiates')->where(['id'=>$candidate_id,'user_type'=>'candidate'])->first();
            if($users!=NULL){
                $response = [   'status'=>true,
                                'candidate_name' => $users->name,
                                'data'=>$array_result
                            ]; 
            }
            else{
                $response = ['status'=>false,
                             'data'=>NULL
                            ];
            }
            
        }
        else{
            $response = ['status'=>false,
                         'data'=>NULL
                        ];
        }

        return response()->json($response, 200);

    }

    public function addressFileUpload(Request $request)
    {
        $rules=[
            'candidate_id' => 'required|numeric',
            'address_id' => 'required|numeric',
            'file_type' => 'required|in:address_proof,profile_photo,house,location,front_door,id_proof',
            'image'  => 'required|mimes:jpg,jpeg,png,bmp,gif,svg|max:50000',
            'latitude' => 'required',
            'longitude' => 'required',
        ];
        $custom=[
            'file_type.in' => 'Select a file type such as address_proof, profile_photo, location, house, id_proof or front_door !',
            'image.max' => 'Image Size must be maximum 50 MB'
        ];
        $validator = Validator::make($request->all(),$rules,$custom);

        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }
        $candidate_id=$request->candidate_id;
        $address_id=$request->address_id;
        $user = DB::table('candidate_reinitiates as u')
        ->select('u.id','u.business_id','u.user_type','u.status','u.name','u.first_name','u.last_name')
        ->where(['u.id'=>$candidate_id,'u.user_type'=>'candidate'])
        ->first();

        if($user!=NULL)
        {   
            $jaf_form_data = DB::table('jaf_form_data')->where(['candidate_id'=>$candidate_id,'id'=> $address_id])->first();
            
            if($jaf_form_data==NULL)
            {
                return response()->json(['status' => 'error',
                                        'message'=>'The given data was invalid.',
                                        'errors'=> ['address_id'=>'Address ID Not Found !!']], 200);
            }

            // $address_ver  = DB::table('address_verifications')->where(['candidate_id'=>$candidate_id,'jaf_id'=>$address_id])->first();

            // if($address_ver!=NULL)
            // {
            //     return response()->json(['status' => 'error',
            //                         'message'=>'The given data was invalid.',
            //                         'errors'=> ['address_id'=>'Address Verification Form Has Already Been Submitted, Try Again with Some Other Check !!']], 200);
            // }

            $latitude       = $request->get('latitude');
            $longitude      = $request->get('longitude');

            if($request->file_type=='profile_photo')
            {
                $imagePath = public_path('/uploads/candidate-selfie/');  
                $image = $request->file('image');
                $profile_photo  = $candidate_id.'-'.date('mdYHis').'-'.$image->getClientOriginalName();        
                $data = $image->move($imagePath, $profile_photo);   

                $file_exist = DB::table('address_verification_file_uploads')
                                ->where(['jaf_id'=>$address_id,'file_type'=>'profile_photo'])
                                ->first();

                if($file_exist!=NULL)
                {
                    if(File::exists($imagePath.$file_exist->image))
                    {
                        File::delete($imagePath.$file_exist->image);
                    }

                    DB::table('address_verification_file_uploads')->where(['jaf_id'=>$address_id,'file_type'=>'profile_photo'])->delete();
                }

                $file_id=DB::table('address_verification_file_uploads')->insertGetID([
                    'business_id' =>$user->business_id,
                    'candidate_id' => $candidate_id,
                    'jaf_id' => $address_id,
                    'file_type' => 'profile_photo',
                    'image' => $profile_photo,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'file_platform' => 'app',
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $response=['status'=>true,'file_id'=>"$file_id",'message'=>'Image Uploaded Successfully !'];
            }
            else if($request->file_type=='address_proof')
            {
                    $imagePath = public_path('/uploads/address-proof/');
                    $image = $request->file('image');
                    $address_proof_photo  = $candidate_id.'-'.date('mdYHis').'-'.$image->getClientOriginalExtension();        
                    $data = $image->move($imagePath, $address_proof_photo); 

                    $file_exist = DB::table('address_verification_file_uploads')
                    ->where(['jaf_id'=>$address_id,'file_type'=>'address_proof'])
                    ->first();

                    if($file_exist!=NULL)
                    {
                        if(File::exists($imagePath.$file_exist->image))
                        {
                            File::delete($imagePath.$file_exist->image);
                        }
                        DB::table('address_verification_file_uploads')->where(['jaf_id'=>$address_id,'file_type'=>'address_proof'])->delete();
                    }
                    
                    $file_id=DB::table('address_verification_file_uploads')->insertGetID([
                        'business_id' =>$user->business_id,
                        'candidate_id' => $candidate_id,
                        'jaf_id' => $address_id,
                        'file_type' => 'address_proof',
                        'image' => $address_proof_photo,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'file_platform' => 'app',
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
    
                    $response=['status'=>true,'file_id'=>"$file_id",'message'=>'Image Uploaded Successfully !'];
            }
            else if($request->file_type=='id_proof')
            {
                    $imagePath = public_path('/uploads/id-proof/');
                    $image = $request->file('image');
                    $id_proof_photo  = $candidate_id.'-'.date('mdYHis').'-'.$image->getClientOriginalExtension();        
                    $data = $image->move($imagePath, $id_proof_photo); 

                    $file_exist = DB::table('address_verification_file_uploads')
                    ->where(['jaf_id'=>$address_id,'file_type'=>'id_proof'])
                    ->first();

                    if($file_exist!=NULL)
                    {
                        if(File::exists($imagePath.$file_exist->image))
                        {
                            File::delete($imagePath.$file_exist->image);
                        }
                        DB::table('address_verification_file_uploads')->where(['jaf_id'=>$address_id,'file_type'=>'id_proof'])->delete();
                    }
                    
                    $file_id=DB::table('address_verification_file_uploads')->insertGetID([
                        'business_id' =>$user->business_id,
                        'candidate_id' => $candidate_id,
                        'jaf_id' => $address_id,
                        'file_type' => 'id_proof',
                        'image' => $id_proof_photo,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'file_platform' => 'app',
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
    
                    $response=['status'=>true,'file_id'=>"$file_id",'message'=>'Image Uploaded Successfully !'];
            }
            else if($request->file_type=='location')
            {
                $imagePath = public_path('/uploads/candidate-location/');

                $image = $request->file('image');
                $location_photo  = $candidate_id.'-'.date('mdYHis').'-'.$image->getClientOriginalName();        
                $data = $image->move($imagePath, $location_photo); 

                $file_exist = DB::table('address_verification_file_uploads')
                    ->where(['jaf_id'=>$address_id,'file_type'=>'location'])
                    ->first();

                if($file_exist!=NULL)
                {
                    if(File::exists($imagePath.$file_exist->image))
                    {
                        File::delete($imagePath.$file_exist->image);
                    }
                    DB::table('address_verification_file_uploads')->where(['jaf_id'=>$address_id,'file_type'=>'location'])->delete();
                }
                
                $file_id=DB::table('address_verification_file_uploads')->insertGetID([
                    'business_id' =>$user->business_id,
                    'candidate_id' => $candidate_id,
                    'jaf_id' => $address_id,
                    'file_type' => 'location',
                    'image' => $location_photo,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'file_platform' => 'app',
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $response=['status'=>true,'file_id'=>"$file_id",'message'=>'Image Uploaded Successfully !'];
            }
            else if($request->file_type=='house')
            {
                $imagePath = public_path('/uploads/candidate-house/');

                $image = $request->file('image');
                $house_photo  = $candidate_id.'-'.date('mdYHis').'-'.$image->getClientOriginalName();        
                $data = $image->move($imagePath, $house_photo);
                
                $file_exist = DB::table('address_verification_file_uploads')
                    ->where(['jaf_id'=>$address_id,'file_type'=>'house'])
                    ->first();

                if($file_exist!=NULL)
                {
                    if(File::exists($imagePath.$file_exist->image))
                    {
                        File::delete($imagePath.$file_exist->image);
                    }
                    DB::table('address_verification_file_uploads')->where(['jaf_id'=>$address_id,'file_type'=>'house'])->delete();
                }
                
                $file_id=DB::table('address_verification_file_uploads')->insertGetID([
                    'business_id' =>$user->business_id,
                    'candidate_id' => $candidate_id,
                    'jaf_id' => $address_id,
                    'file_type' => 'house',
                    'image' => $house_photo,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'file_platform' => 'app',
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $response=['status'=>true,'file_id'=>"$file_id",'message'=>'Image Uploaded Successfully !'];
            }
            else if($request->file_type=='front_door')
            {
                $imagePath = public_path('/uploads/candidate-front-door/');

                if(!File::exists($imagePath))
                {
                    File::makeDirectory($imagePath, $mode = 0777, true, true);
                }

                $image = $request->file('image');
                $front_door_photo  = $candidate_id.'-'.date('mdYHis').'-'.$image->getClientOriginalName();        
                $data = $image->move($imagePath, $front_door_photo); 

                $file_exist = DB::table('address_verification_file_uploads')
                    ->where(['jaf_id'=>$address_id,'file_type'=>'front_door'])
                    ->first();

                if($file_exist!=NULL)
                {
                    if(File::exists($imagePath.$file_exist->image))
                    {
                        File::delete($imagePath.$file_exist->image);
                    }

                    DB::table('address_verification_file_uploads')->where(['jaf_id'=>$address_id,'file_type'=>'front_door'])->delete();
                }
    
                $file_id=DB::table('address_verification_file_uploads')->insertGetID([
                    'business_id' =>$user->business_id,
                    'candidate_id' => $candidate_id,
                    'jaf_id' => $address_id,
                    'file_type' => 'front_door',
                    'image' => $front_door_photo,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'file_platform' => 'app',
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $response=['status'=>true,'file_id'=>"$file_id",'message'=>'Image Uploaded Successfully !'];

            }
            // else if($request->file_type=='signature')
            // {
            //     $imagePath = public_path('/uploads/candidate-signature/');

            //     $image = $request->file('image');
            //     $sign_photo  = $candidate_id.'-'.date('mdYHis').'-'.$image->getClientOriginalName();        
            //     $data = $image->move($imagePath, $sign_photo); 
                
            //     $file_d=DB::table('address_verification_file_uploads')->where(['candidate_id'=>$candidate_id,'file_type'=>'signature'])->first();
            //     if($file_d!=NULL)
            //     {
            //         if(File::Exists($imagePath.$file_d->image))
            //         {
            //             File::delete($imagePath.$file_d->image);
            //         }
            //         DB::table('address_verification_file_uploads')->where(['candidate_id'=>$candidate_id,'file_type'=>'signature'])->update([
            //             'image' => $sign_photo,
            //             'updated_at' => date('Y-m-d h:i:s')
            //         ]);

            //         $file_id=$file_d->id;
            //     }
            //     else
            //     {
            //         $file_id=DB::table('address_verification_file_uploads')->insertGetID([
            //             'business_id' =>$user->business_id,
            //             'candidate_id' => $candidate_id,
            //             'file_type' => 'signature',
            //             'image' => $sign_photo,
            //             'created_at' => date('Y-m-d h:i:s')
            //         ]);
            //     }
                

            //     $response=['status'=>true,'file_id'=>"$file_id",'message'=>'Image Uploaded Successfully !'];
            // }
            else
            {
                $response=['status'=>false,'message' => 'Select a valid file type !'];
            }
        }
        else
        {
            $response = ['status'=>false,
            'message'=>'Candidate ID Not Found']; 
        }
        return response()->json($response,200);

    }

    public function addressFileDelete(Request $request)
    {
        $rules=[
            'file_id' => 'required|numeric',
        ];
        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }

        $file_data=DB::table('address_verification_file_uploads')->where(['id'=>$request->file_id])->first();
        if($file_data!=NULL)
        {
            if($file_data->file_type=='address_proof')
            {
                $imagePath = public_path('/uploads/address-proof/');

                if(File::exists($imagePath.$file_data->image))
                {
                    File::delete($imagePath.$file_data->image);
                }

                DB::table('address_verification_file_uploads')->where(['id'=>$request->file_id])->delete();
                
                $response=['status' => true, 'message' => 'Image Deleted Successfully !'];
            }
            else if($file_data->file_type=='profile_photo')
            {
                $imagePath = public_path('/uploads/candidate-selfie/');  

                if(File::exists($imagePath.$file_data->image))
                {
                    File::delete($imagePath.$file_data->image);
                }

                DB::table('address_verification_file_uploads')->where(['id'=>$request->file_id])->delete();

                $response=['status' => true, 'message' => 'Image Deleted Successfully !'];
            }
            else if($file_data->file_type=='house')
            {
                $imagePath = public_path('/uploads/candidate-house/'); 

                if(File::exists($imagePath.$file_data->image))
                {
                    File::delete($imagePath.$file_data->image);
                }

                DB::table('address_verification_file_uploads')->where(['id'=>$request->file_id])->delete();

                $response=['status' => true, 'message' => 'Image Deleted Successfully !'];
            }
            else if($file_data->file_type=='location')
            {
                $imagePath = public_path('/uploads/candidate-location/');

                if(File::exists($imagePath.$file_data->image))
                {
                    File::delete($imagePath.$file_data->image);
                }

                DB::table('address_verification_file_uploads')->where(['id'=>$request->file_id])->delete();

                $response=['status' => true, 'message' => 'Image Deleted Successfully !'];
            }
            else if($file_data->file_type=='front_door')
            {
                $imagePath = public_path('/uploads/candidate-front-door/');

                if(File::exists($imagePath.$file_data->image))
                {
                    File::delete($imagePath.$file_data->image);
                }

                DB::table('address_verification_file_uploads')->where(['id'=>$request->file_id])->delete();

                $response=['status' => true, 'message' => 'Image Deleted Successfully !'];
            }
            else if($file_data->file_type=='id_proof')
            {
                $imagePath = public_path('/uploads/id-proof/');

                if(File::exists($imagePath.$file_data->image))
                {
                    File::delete($imagePath.$file_data->image);
                }

                DB::table('address_verification_file_uploads')->where(['id'=>$request->file_id])->delete();
                
                $response=['status' => true, 'message' => 'Image Deleted Successfully !'];
            }
            else
            {
                $response=['status'=>false,'message' => 'File ID For Type Not Found !'];
            }
        }
        else
        {
            $response=[
                'status' =>false,
                'message' => 'File ID Not Found !'
            ];
        }
        return response()->json($response,200);
        
    }

    


}
