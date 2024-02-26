<?php

namespace App\Http\Controllers\API\V1\InstantVerification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\User;

/**
 * @group  Instant Verification APIs
 *
 * APIs for managing Instant Verification checks
 */
class VerificationController extends Controller
{
    
    /**
     * Aadhar API
     *
     * This API is used for show the Aadhar details based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.
     * 
     * @authenticated
     * 
     * @bodyParam  login_id integer required Login ID for finding the user. Example: 94
     * 
     * @bodyParam  aadhar_number integer required Aadhar Number to run check on (digits:12). Example: 986018457823
     * 
     * @response 401 {"status":false,"message":"Permission Denied!!"}
     * 
     * @response 404 {"status":false,"data":null,"message":"User Not Found!!"}
     * 
     * @response 406 {"status":false,"data":null,"message":"It seems like ID number is not valid!"}
     * 
     * @responseFile responses/verification/aadhar/success.json
     * 
     */
    public function idCheckAadhar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_id'  => 'required',

        ]);

        
        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }
        DB::beginTransaction();
        try{
            $users=DB::table('users')->where(['id'=>$request->login_id])->whereNotIn('user_type',['candidate','guest'])->first();
            if($users!=NULL)
            {
                $business_id=$users->business_id;
                $parent_id=$users->parent_id;
                $user_d=DB::table('users')->where('id',$business_id)->first();

                if($users->user_type=='user' || $users->user_type=='User')
                {
                    $parent_id=$user_d->parent_id;
                }

                $rules=[
                    'aadhar_number'  => 'required|regex:/^((?!([0-1]))[0-9]{12})$/',
                ];
                $custommessages=[
                    'aadhar_number.regex' => 'Please enter a 12-digit valid aadhar number !'
                ];
                $validator = Validator::make($request->all(), $rules,$custommessages);
                    
                
                if ($validator->fails()) {            
                    return response()->json(['status' => 'error',
                                            'message'=>'The given data was invalid.',
                                            'errors'=> $validator->errors()], 200);
                }

                if($user_d->user_type=='customer')
                {
                    
                    $price=20;

                    $checkprice_db=DB::table('check_price_masters')
                                        ->select('price')
                                        ->where(['business_id'=>$parent_id,'service_id'=>'2'])->first();
                    $array_result=[];
                    //check first into master table
                    $master_data = DB::table('aadhar_check_masters')->select('*')->where(['aadhar_number'=>$request->aadhar_number])->first();
                    
                    if($master_data !=null){
                        
                        // store log
                        $check_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       =>$business_id,
                            'service_id'        => 2,
                            'aadhar_number'     =>$master_data->aadhar_number,
                            'age_range'         =>$master_data->age_range,
                            'gender'            =>$master_data->gender,
                            'state'             =>$master_data->state,
                            'last_digit'        =>$master_data->last_digit,
                            'is_verified'       =>'1',
                            'is_aadhar_exist'   =>'1',
                            'used_by'           =>'customer',
                            'user_id'            => $request->login_id,
                            'source_reference'  =>'SystemDB',
                            'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                            'platform_reference' => 'api',
                            'created_at'        =>date('Y-m-d H:i:s')
                        ]; 

                        DB::table('aadhar_checks')->insert($check_data);

                        DB::commit();
                        $array_result=[
                                        'aadhar_number'=>$master_data->aadhar_number,
                                        'aadhar_validity'=>'valid',
                                        'verification_check'=>'completed',
                                        'result' => [
                                                    'aadhar_number_exist'=>$master_data->aadhar_number,
                                                    'age_bond' => $master_data->age_range,
                                                    'gender' => $master_data->gender,
                                                    'state' => $master_data->state,
                                                    'mobile_last_digits' => $master_data->last_digit,
                                                    ]
                                    ];

                        $response=['status'=>true,
                                    'data'=>$array_result,
                                    'initiated_date'=>date('d-m-Y'),
                                    'completed_date'=>date('d-m-Y'),
                                    'message' => 'Verification Done Successfully !!'
                                ];
                    
                    }
                    else
                    {
                        //check from live API
                        $api_check_status = false;
                        // Setup request to send json via POST
                        $data = array(
                            'id_number'    => $request->aadhar_number,
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

                        // dd($array_data);

                        if($array_data['success'])
                        {
                            $master_data ="";
                            //check if ID number is new then insert into DB
                            $checkIDInDB= DB::table('aadhar_check_masters')->where(['aadhar_number'=>$request->aadhar_number])->count();
                            if($checkIDInDB ==0)
                            {
                                $gender = 'Male';
                                if($array_data['data']['gender'] == 'F'){
                                    $gender = 'Female';
                                }
                                $data = ['aadhar_number'    =>$array_data['data']['aadhaar_number'],
                                        'age_range'         =>$array_data['data']['age_range'],
                                        'gender'            =>$gender,
                                        'state'             =>$array_data['data']['state'],
                                        'last_digit'        =>$array_data['data']['last_digits'],
                                        'is_verified'       =>'1',
                                        'is_aadhar_exist'   =>'1',
                                        'created_at'        =>date('Y-m-d H:i:s'),
                                        'platform_reference' => 'api'
                                        ];
                                DB::table('aadhar_check_masters')->insert($data);
                                        
                                //insert into aadhar_checks table
                                $business_data = [
                                        'parent_id'         =>$parent_id,
                                        'business_id'       =>$business_id,
                                        'service_id'        =>2,
                                        'aadhar_number'     =>$array_data['data']['aadhaar_number'],
                                        'age_range'         =>$array_data['data']['age_range'],
                                        'gender'            =>$gender,
                                        'state'             =>$array_data['data']['state'],
                                        'last_digit'        =>$array_data['data']['last_digits'],
                                        'is_verified'       =>'1',
                                        'is_aadhar_exist'   =>'1',
                                        'used_by'           =>'customer',
                                        'user_id'            => $request->login_id,
                                        'source_reference'  =>'API',
                                        'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                                        'platform_reference' => 'api',
                                        'created_at'        =>date('Y-m-d H:i:s')
                                        ]; 
                                DB::table('aadhar_checks')->insert($business_data);
                                
                                $master_data = DB::table('aadhar_check_masters')->select('*')->where(['aadhar_number'=>$request->aadhar_number])->first();

                            }
                                DB::commit();
                                $array_result=[
                                    'aadhar_number'=>$master_data->aadhar_number,
                                    'aadhar_validity'=>'valid',
                                    'verification_check'=>'completed',
                                    'result' => [
                                                'aadhar_number_exist'=>$master_data->aadhar_number,
                                                'age_bond' => $master_data->age_range,
                                                'gender' => $master_data->gender,
                                                'state' => $master_data->state,
                                                'mobile_last_digits' => $master_data->last_digit,
                                                ]
                                ];

                                $response=['status'=>true,
                                            'data'=>$array_result,
                                            'initiated_date'=>date('d-m-Y'),
                                            'completed_date'=>date('d-m-Y'),
                                            'message' => 'Verification Done Successfully !!'
                                        ];
                            

                        }
                        else{

                            $response=['status'=>false,
                                        'data'=>NULL,
                                        'message' => 'It seems like ID number is not valid!'
                                    ];
                        }
                    }
                    
                }
                else if($user_d->user_type=='client')
                {
                    $price=20;

                    $data = DB::table('check_price_cocs')->where(['service_id'=>2,'coc_id'=>$business_id])->first();
                    if($data!=NULL)
                    {
                        $price=$data->price;
                    }
                    else{
                        $data=DB::table('check_prices')->where(['service_id'=>2,'business_id'=>$parent_id])->first();
                        if($data!=NULL)
                        {
                            $price=$data->price;
                        }
                        // else{
                        //     $data=DB::table('check_price_masters')->where(['service_id'=>2])->first();
                        //     if($data!=NULL)
                        //     {
                        //         $price=$data->price;
                        //     }
                        // }
                    }

                    //check first into master table
                    $master_data = DB::table('aadhar_check_masters')->select('*')->where(['aadhar_number'=>$request->aadhar_number])->first();

                    if($master_data !=null){
                        
                        // store log
                        $check_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       =>$business_id,
                            'service_id'        =>2,
                            'aadhar_number'     =>$master_data->aadhar_number,
                            'age_range'         =>$master_data->age_range,
                            'gender'            =>$master_data->gender,
                            'state'             =>$master_data->state,
                            'last_digit'        =>$master_data->last_digit,
                            'is_verified'       =>'1',
                            'is_aadhar_exist'   =>'1',
                            'used_by'           =>'coc',
                            'user_id'           => $request->login_id,
                            'source_reference'  =>'SystemDB',
                            'price'             =>$price,
                            'created_at'        =>date('Y-m-d H:i:s'),
                            'platform_reference' => 'api'
                        ]; 

                        DB::table('aadhar_checks')->insert($check_data);

                        DB::commit();
                        $array_result=[
                                        'aadhar_number'=>$master_data->aadhar_number,
                                        'aadhar_validity'=>'valid',
                                        'verification_check'=>'completed',
                                        'result' => [
                                                    'aadhar_number_exist'=>$master_data->aadhar_number,
                                                    'age_bond' => $master_data->age_range,
                                                    'gender' => $master_data->gender,
                                                    'state' => $master_data->state,
                                                    'mobile_last_digits' => $master_data->last_digit,
                                                    ]
                                    ];

                        $response=['status'=>true,
                                    'data'=>$array_result,
                                    'initiated_date'=>date('d-m-Y'),
                                    'completed_date'=>date('d-m-Y'),
                                    'message' => 'Verification Done Successfully !!'
                                ];
                    
                    }
                    else
                    {
                        //check from live API
                        $api_check_status = false;
                        // Setup request to send json via POST
                        $data = array(
                            'id_number'    => $request->aadhar_number,
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

                        // dd($array_data);

                        if($array_data['success'])
                        {
                            $master_data ="";
                            //check if ID number is new then insert into DB
                            $checkIDInDB= DB::table('aadhar_check_masters')->where(['aadhar_number'=>$request->aadhar_number])->count();
                            if($checkIDInDB ==0)
                            {
                                $gender = 'Male';
                                if($array_data['data']['gender'] == 'F'){
                                    $gender = 'Female';
                                }
                                $data = ['aadhar_number'    =>$array_data['data']['aadhaar_number'],
                                        'age_range'         =>$array_data['data']['age_range'],
                                        'gender'            =>$gender,
                                        'state'             =>$array_data['data']['state'],
                                        'last_digit'        =>$array_data['data']['last_digits'],
                                        'is_verified'       =>'1',
                                        'is_aadhar_exist'   =>'1',
                                        'created_at'        =>date('Y-m-d H:i:s'),
                                        'platform_reference' => 'api'
                                        ];
                                DB::table('aadhar_check_masters')->insert($data);
                                        
                                //insert into aadhar_checks table
                                $business_data = [
                                    'parent_id'         =>$parent_id,
                                    'business_id'       =>$business_id,
                                    'service_id'        =>2,
                                    'aadhar_number'     =>$array_data['data']['aadhaar_number'],
                                    'age_range'         =>$array_data['data']['age_range'],
                                    'gender'            =>$gender,
                                    'state'             =>$array_data['data']['state'],
                                    'last_digit'        =>$array_data['data']['last_digits'],
                                    'is_verified'       =>'1',
                                    'is_aadhar_exist'   =>'1',
                                    'used_by'           =>'coc',
                                    'user_id'           =>$request->login_id,
                                    'source_reference'  =>'API',
                                    'price'             =>$price,
                                    'created_at'        =>date('Y-m-d H:i:s'),
                                    'platform_reference' => 'api'
                                    ]; 
                                DB::table('aadhar_checks')->insert($business_data);
                                
                                $master_data = DB::table('aadhar_check_masters')->select('*')->where(['aadhar_number'=>$request->aadhar_number])->first();

                            }
                                DB::commit();
                                $array_result=[
                                    'aadhar_number'=>$master_data->aadhar_number,
                                    'aadhar_validity'=>'valid',
                                    'verification_check'=>'completed',
                                    'result' => [
                                                'aadhar_number_exist'=>$master_data->aadhar_number,
                                                'age_bond' => $master_data->age_range,
                                                'gender' => $master_data->gender,
                                                'state' => $master_data->state,
                                                'mobile_last_digits' => $master_data->last_digit,
                                                ]
                                ];

                                $response=['status'=>true,
                                            'data'=>$array_result,
                                            'initiated_date'=>date('d-m-Y'),
                                            'completed_date'=>date('d-m-Y'),
                                            'message' => 'Verification Done Successfully !!'
                                        ];
                            

                        }
                        else{

                            $response=['status'=>false,
                                        'data'=>NULL,
                                        'message' => 'It seems like ID number is not valid!'
                                    ];
                        }   
                    }

                }
                else
                {
                    $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'Permission Denied !!'
                        ];
                }
            }
            else
            {
                $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'User Not Found'
                        ];
            }

            return response()->json($response, 200);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
    }

    /**
     * PAN API
     *
     * This API is used for show the PAN details based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.
     * 
     * @authenticated
     * 
     * @bodyParam  login_id integer required Login ID for finding the user. Example: 94
     * 
     * @bodyParam  pan_number string required PAN Number to run check on (PAN Format & digits:10). Example: "GPWPS3116F"
     * 
     * @response 401 {"status":false,"message":"Permission Denied!!"}
     * 
     * @response 404 {"status":false,"data":null,"message":"User Not Found!!"}
     * 
     * @response 406 {"status":false,"data":null,"message":"It seems like ID number is not valid!"}
     * 
     * @responseFile responses/verification/pan/success.json
     * 
     */
    public function idCheckPan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_id'  => 'required',

        ]);

        
        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }
        DB::beginTransaction();
        try{
            $users=DB::table('users')->where(['id'=>$request->login_id])->whereNotIn('user_type',['candidate','guest'])->first();
            if($users!=NULL)
            {
                $business_id=$users->business_id;
                $parent_id=$users->parent_id;
                $user_d=DB::table('users')->where('id',$business_id)->first();

                if($users->user_type=='user' || $users->user_type=='User')
                {
                    $parent_id=$user_d->parent_id;
                }

                $rules=[
                    'pan_number'  => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{10}$/',
                ];
                $custommessages=[
                    'pan_number.regex' => 'Please enter a valid PAN number !'
                ];
                $validator = Validator::make($request->all(), $rules,$custommessages);
                    
                
                if ($validator->fails()) {            
                    return response()->json(['status' => 'error',
                                            'message'=>'The given data was invalid.',
                                            'errors'=> $validator->errors()], 200);
                }

                if($user_d->user_type=='customer')
                {
                    $price=20;
                    $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>3])->first();
                    //check first into master table
                    $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$request->pan_number])->first();
                    
                    if($master_data !=null){
                        //store log
                        $data = [
                            'parent_id'         =>$parent_id,
                            'category'          =>$master_data->category,
                            'pan_number'        =>$master_data->pan_number,
                            'full_name'         =>$master_data->full_name,
                            'is_verified'       =>'1',
                            'is_pan_exist'      =>'1',
                            'business_id'       => $business_id,
                            'service_id'        => 3,
                            'source_type'       =>'SystemDb',
                            'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                            'used_by'           =>'customer',
                            'user_id'            => $request->login_id,
                            'created_at'=>date('Y-m-d H:i:s'),
                            'platform_reference' => 'api'
                            ];
                    
                            DB::table('pan_checks')->insert($data);
                            DB::commit();
                            $array_result=[
                                'pan_number'=>$master_data->pan_number,
                                'pan_validity'=>'valid',
                                'verification_check'=>'completed',
                                'result' => [
                                            'pan_number_exist'=>$master_data->pan_number,
                                            'full_name' => $master_data->full_name
                                            ]
                            ];

                            $response=['status'=>true,
                                        'data'=>$array_result,
                                        'initiated_date'=>date('d-m-Y'),
                                        'completed_date'=>date('d-m-Y'),
                                        'message' => 'Verification Done Successfully !!'
                                    ];
                    }
                    else
                    {
                        //check from live API
                        $api_check_status = false;
                        // Setup request to send json via POST
                        $data = array(
                            'id_number'    => $request->pan_number,
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
                            $checkIDInDB= DB::table('pan_check_masters')->where(['pan_number'=>$request->pan_number])->count();
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
                                    'service_id'        => 3,
                                    'source_type'       =>'API',
                                    'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                                    'used_by'           =>'customer',
                                    'user_id'            => $request->login_id,
                                    'created_at'=>date('Y-m-d H:i:s'),
                                    'platform_reference' => 'api'
                                    ];
                            
                                DB::table('pan_checks')->insert($data);
                                
                                $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$request->pan_number])->first();
                            }
                            DB::commit();
                            $array_result=[
                                'pan_number'=>$master_data->pan_number,
                                'pan_validity'=>'valid',
                                'verification_check'=>'completed',
                                'result' => [
                                            'pan_number_exist'=>$master_data->pan_number,
                                            'full_name' => $master_data->full_name
                                            ]
                            ];

                            $response=['status'=>true,
                                        'data'=>$array_result,
                                        'initiated_date'=>date('d-m-Y'),
                                        'completed_date'=>date('d-m-Y'),
                                        'message' => 'Verification Done Successfully !!'
                                    ];

                        }else{
                            $response=['status'=>false,
                                        'data'=>NULL,
                                        'message' => 'It seems like ID number is not valid!'
                                    ];
                        }
                    }
                }
                else if($user_d->user_type=='client')
                {
                    $price=20;

                    $data = DB::table('check_price_cocs')->where(['service_id'=>3,'coc_id'=>$business_id])->first();
                    if($data!=NULL)
                    {
                        $price=$data->price;
                    }
                    else{
                        $data=DB::table('check_prices')->where(['service_id'=>3,'business_id'=>$parent_id])->first();
                        if($data!=NULL)
                        {
                            $price=$data->price;
                        }
                        // else{
                        //     $data=DB::table('check_price_masters')->where(['service_id'=>3])->first();
                        //     if($data!=NULL)
                        //     {
                        //         $price=$data->price;
                        //     }
                        // }
                    }

                    //check first into master table
                    $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$request->pan_number])->first();
                    
                    if($master_data !=null){
                        //store log
                        $data = [
                            'parent_id'         =>$parent_id,
                            'category'          =>$master_data->category,
                            'pan_number'        =>$master_data->pan_number,
                            'full_name'         =>$master_data->full_name,
                            'is_verified'       =>'1',
                            'is_pan_exist'      =>'1',
                            'business_id'       => $business_id,
                            'service_id'        => 3,
                            'source_type'       =>'SystemDb',
                            'price'             =>$price,
                            'used_by'           =>'coc',
                            'user_id'            => $request->login_id,
                            'created_at'=>date('Y-m-d H:i:s'),
                            'platform_reference' => 'api'
                            ];
                    
                            DB::table('pan_checks')->insert($data);
                            DB::commit();

                            $array_result=[
                                'pan_number'=>$master_data->pan_number,
                                'pan_validity'=>'valid',
                                'verification_check'=>'completed',
                                'result' => [
                                            'pan_number_exist'=>$master_data->pan_number,
                                            'full_name' => $master_data->full_name
                                            ]
                            ];

                            $response=['status'=>true,
                                        'data'=>$array_result,
                                        'initiated_date'=>date('d-m-Y'),
                                        'completed_date'=>date('d-m-Y'),
                                        'message' => 'Verification Done Successfully !!'
                                    ];
                    }
                    else
                    {
                        //check from live API
                        $api_check_status = false;
                        // Setup request to send json via POST
                        $data = array(
                            'id_number'    => $request->pan_number,
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
                            $checkIDInDB= DB::table('pan_check_masters')->where(['pan_number'=>$request->pan_number])->count();
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
                                    'service_id'        => 3,
                                    'source_type'       =>'API',
                                    'price'             =>$price,
                                    'used_by'           =>'coc',
                                    'user_id'            => $request->login_id,
                                    'created_at'=>date('Y-m-d H:i:s'),
                                    'platform_reference' => 'api'
                                    ];
                            
                                DB::table('pan_checks')->insert($data);
                                
                                $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$request->pan_number])->first();
                            }
                            DB::commit();
                            $array_result=[
                                'pan_number'=>$master_data->pan_number,
                                'pan_validity'=>'valid',
                                'verification_check'=>'completed',
                                'result' => [
                                            'pan_number_exist'=>$master_data->pan_number,
                                            'full_name' => $master_data->full_name
                                            ]
                            ];

                            $response=['status'=>true,
                                        'data'=>$array_result,
                                        'initiated_date'=>date('d-m-Y'),
                                        'completed_date'=>date('d-m-Y'),
                                        'message' => 'Verification Done Successfully !!'
                                    ];

                        }else{
                            $response=['status'=>false,
                                        'data'=>NULL,
                                        'message' => 'It seems like ID number is not valid!'
                                    ];
                        }
                    }
                }
                else
                {
                    $response=['status'=>false,
                        'message' => 'Permission Denied !!'
                        ];
                }
            }
            else
            {
                $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'User Not Found'
                        ];
            }

            return response()->json($response, 200);

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
    }

    /**
     * Voter ID API
     *
     * This API is used for show the Voter ID details based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.
     * 
     * @authenticated
     * 
     * @bodyParam  login_id integer required Login ID for finding the user. Example: 94
     * 
     * @bodyParam  voter_id_number string required Voter ID Number to run check on (Voter ID Format & digits:10). Example: "BCQ5016258"
     * 
     * @response 401 {"status":false,"message":"Permission Denied!!"}
     * 
     * @response 404 {"status":false,"data":null,"message":"User Not Found!!"}
     * 
     * @response 406 {"status":false,"data":null,"message":"It seems like ID number is not valid!"}
     * 
     * @responseFile responses/verification/voter/success.json
     * 
     */
    public function idCheckVoterID(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_id'  => 'required',

        ]);

        
        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }
        DB::beginTransaction();
        try{
            $users=DB::table('users')->where(['id'=>$request->login_id])->whereNotIn('user_type',['candidate','guest'])->first();
            if($users!=NULL)
            {
                $business_id=$users->business_id;
                $parent_id=$users->parent_id;
                $user_d=DB::table('users')->where('id',$business_id)->first();

                if($users->user_type=='user' || $users->user_type=='User')
                {
                    $parent_id=$user_d->parent_id;
                }

                $rules=[
                    'voter_id_number'  => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{10}$/',
                ];
                $custommessages=[
                    'voter_id_number.regex' => 'Please enter a valid VoterID number !'
                ];
                $validator = Validator::make($request->all(), $rules,$custommessages);
                    
                
                if ($validator->fails()) {            
                    return response()->json(['status' => 'error',
                                            'message'=>'The given data was invalid.',
                                            'errors'=> $validator->errors()], 200);
                }

                if($user_d->user_type=='customer')
                {
                    $price=20;
                    $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>4])->first();
                    
                    //check first into master table
                    $master_data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$request->voter_id_number])->first();
                    if($master_data !=null){
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
                            'service_id'        =>4,
                            'source_reference'  =>'SystemDb',
                            'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                            'used_by'           =>'customer',
                            'user_id'            => $request->login_id,
                            'created_at'        =>date('Y-m-d H:i:s'),
                            'platform_reference' => 'api'
                            ];

                        DB::table('voter_id_checks')->insert($log_data);
                        DB::commit();
                        $array_result=[
                            'voter_id_number'=>$master_data->voter_id_number,
                            'voter_id_validity'=>'valid',
                            'verification_check'=>'completed',
                            'result' => [
                                        'voter_id_number_exist'=>$master_data->voter_id_number,
                                        'full_name' => $master_data->full_name,
                                        'gender'            =>$master_data->gender,
                                        'age'               =>$master_data->age,
                                        'dob'               =>$master_data->dob,
                                        'house_no'          =>$master_data->house_no,
                                        'area'              =>$master_data->area,
                                        'state'             =>$master_data->state,
                                        ]
                        ];

                        $response=['status'=>true,
                                    'data'=>$array_result,
                                    'initiated_date'=>date('d-m-Y'),
                                    'completed_date'=>date('d-m-Y'),
                                    'message' => 'Verification Done Successfully !!'
                                ];
                    }
                    else
                    {
                        //check from live API
                        // Setup request to send json via POST
                        $data = array(
                            'id_number'    => $request->voter_id_number,
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
                            $checkIDInDB= DB::table('voter_id_check_masters')->where(['voter_id_number'=>$request->voter_id_number])->count();
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
                                DB::table('voter_id_check_masters')->insert($data);

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
                                    'service_id'        =>4,
                                    'source_reference'  =>'API',
                                    'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                                    'used_by'           =>'customer',
                                    'user_id'            => $request->login_id,
                                    'created_at'        =>date('Y-m-d H:i:s'),
                                    'platform_reference' => 'api'
                                    ];

                                DB::table('voter_id_checks')->insert($log_data);
                                
                                $master_data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$request->voter_id_number])->first();
                            }
                            DB::commit();
                            $array_result=[
                                'voter_id_number'=>$master_data->voter_id_number,
                                'voter_id_validity'=>'valid',
                                'verification_check'=>'completed',
                                'result' => [
                                            'voter_id_number_exist'=>$master_data->voter_id_number,
                                            'full_name' => $master_data->full_name,
                                            'gender'            =>$master_data->gender,
                                            'age'               =>$master_data->age,
                                            'dob'               =>$master_data->dob,
                                            'house_no'          =>$master_data->house_no,
                                            'area'              =>$master_data->area,
                                            'state'             =>$master_data->state,
                                            ]
                            ];
        
                            $response=['status'=>true,
                                        'data'=>$array_result,
                                        'initiated_date'=>date('d-m-Y'),
                                        'completed_date'=>date('d-m-Y'),
                                        'message' => 'Verification Done Successfully !!'
                                    ];

                        }else{
                            $response=['status'=>false,
                                        'data'=>NULL,
                                        'message' => 'It seems like ID number is not valid!'
                                    ];
                        }
                    }
                }
                else if($user_d->user_type=='client')
                {
                    $price=20;

                    $data = DB::table('check_price_cocs')->where(['service_id'=>4,'coc_id'=>$business_id])->first();
                    if($data!=NULL)
                    {
                        $price=$data->price;
                    }
                    else{
                        $data=DB::table('check_prices')->where(['service_id'=>4,'business_id'=>$parent_id])->first();
                        if($data!=NULL)
                        {
                            $price=$data->price;
                        }
                        // else{
                        //     $data=DB::table('check_price_masters')->where(['service_id'=>4])->first();
                        //     if($data!=NULL)
                        //     {
                        //         $price=$data->price;
                        //     }
                        // }
                    }
                    //check first into master table
                    $master_data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$request->voter_id_number])->first();
                    if($master_data !=null){
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
                            'service_id'        =>4,
                            'source_reference'  =>'SystemDb',
                            'price'             =>$price,
                            'used_by'           =>'coc',
                            'user_id'            => $request->login_id,
                            'created_at'        =>date('Y-m-d H:i:s'),
                            'platform_reference' => 'api'
                            ];

                        DB::table('voter_id_checks')->insert($log_data);
                        DB::commit();
                        $array_result=[
                            'voter_id_number'=>$master_data->voter_id_number,
                            'voter_id_validity'=>'valid',
                            'verification_check'=>'completed',
                            'result' => [
                                        'voter_id_number_exist'=>$master_data->voter_id_number,
                                        'full_name' => $master_data->full_name,
                                        'gender'            =>$master_data->gender,
                                        'age'               =>$master_data->age,
                                        'dob'               =>$master_data->dob,
                                        'house_no'          =>$master_data->house_no,
                                        'area'              =>$master_data->area,
                                        'state'             =>$master_data->state,
                                        ]
                        ];

                        $response=['status'=>true,
                                    'data'=>$array_result,
                                    'initiated_date'=>date('d-m-Y'),
                                    'completed_date'=>date('d-m-Y'),
                                    'message' => 'Verification Done Successfully !!'
                                ];
                    }
                    else
                    {
                        //check from live API
                        // Setup request to send json via POST
                        $data = array(
                            'id_number'    => $request->voter_id_number,
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
                            $checkIDInDB= DB::table('voter_id_check_masters')->where(['voter_id_number'=>$request->voter_id_number])->count();
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
                                DB::table('voter_id_check_masters')->insert($data);

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
                                    'service_id'        =>4,
                                    'source_reference'  =>'API',
                                    'price'             =>$price,
                                    'used_by'           =>'coc',
                                    'user_id'            => $request->login_id,
                                    'created_at'        =>date('Y-m-d H:i:s'),
                                    'platform_reference' => 'api'
                                    ];

                                DB::table('voter_id_checks')->insert($log_data);
                                
                                $master_data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$request->voter_id_number])->first();
                            }
                            DB::commit();
                            $array_result=[
                                'voter_id_number'=>$master_data->voter_id_number,
                                'voter_id_validity'=>'valid',
                                'verification_check'=>'completed',
                                'result' => [
                                            'voter_id_number_exist'=>$master_data->voter_id_number,
                                            'full_name' => $master_data->full_name,
                                            'gender'            =>$master_data->gender,
                                            'age'               =>$master_data->age,
                                            'dob'               =>$master_data->dob,
                                            'house_no'          =>$master_data->house_no,
                                            'area'              =>$master_data->area,
                                            'state'             =>$master_data->state,
                                            ]
                            ];
        
                            $response=['status'=>true,
                                        'data'=>$array_result,
                                        'initiated_date'=>date('d-m-Y'),
                                        'completed_date'=>date('d-m-Y'),
                                        'message' => 'Verification Done Successfully !!'
                                    ];

                        }else{
                            $response=['status'=>false,
                                        'data'=>NULL,
                                        'message' => 'It seems like ID number is not valid!'
                                    ];
                        }                        
                    } 
                }
                else
                {
                    $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'Permission Denied !!'
                        ];
                }
            }
            else
            {
                $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'User Not Found'
                        ];
            }

            return response()->json($response,200);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
    }

    /**
     * RC API
     *
     * This API is used for show the RC details based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.
     * 
     * @authenticated
     * 
     * @bodyParam  login_id integer required Login ID for finding the user. Example: 94
     * 
     * @bodyParam  rc_number string required RC Number to run check on (RC Format & min:8). Example: "UP82AE1242"
     * 
     * @response 401 {"status":false,"message":"Permission Denied!!"}
     * 
     * @response 404 {"status":false,"data":null,"message":"User Not Found!!"}
     * 
     * @response 406 {"status":false,"data":null,"message":"It seems like ID number is not valid!"}
     * 
     * @responseFile responses/verification/rc/success.json
     * 
     */
    public function idCheckRC(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_id'  => 'required',

        ]);

        
        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }
        DB::beginTransaction();
        try{
            $users=DB::table('users')->where(['id'=>$request->login_id])->whereNotIn('user_type',['candidate','guest'])->first();
            if($users!=NULL)
            {
                $business_id=$users->business_id;
                $parent_id=$users->parent_id;
                $user_d=DB::table('users')->where('id',$business_id)->first();

                if($users->user_type=='user' || $users->user_type=='User')
                {
                    $parent_id=$user_d->parent_id;
                }

                $rules=[
                    'rc_number'  => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{8,}$/',
                ];
                $custommessages=[
                    'rc_number.regex' => 'Please enter a valid RC number !'
                ];
                $validator = Validator::make($request->all(), $rules,$custommessages);
                    
                
                if ($validator->fails()) {            
                    return response()->json(['status' => 'error',
                                            'message'=>'The given data was invalid.',
                                            'errors'=> $validator->errors()], 200);
                }

                if($user_d->user_type=='customer')
                {
                    $price=20;
                    $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>7])->first();
                    
                    //check first into master table
                    $master_data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$request->rc_number])->first();
                    if($master_data !=null){
                        $data = $master_data;
                        $log_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       => $business_id,
                            'service_id'        =>7,
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
                            'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                            'used_by'               =>'customer',
                            'user_id'                =>  $request->login_id,
                            'created_at'            =>date('Y-m-d H:i:s'),
                            'platform_reference'   =>  'api'
                            ];

                            DB::table('rc_checks')->insert($log_data);
                            DB::commit();
                            $array_result=[
                                'rc_number'=>$master_data->rc_number,
                                'rc_validity'=>'valid',
                                'verification_check'=>'completed',
                                'result' => [
                                            'rc_number_exist'=>$master_data->rc_number,
                                            'registration_date' =>$master_data->registration_date,
                                            'owner_name'        =>$master_data->owner_name,
                                            'vehicle_chasis_number' =>$master_data->vehicle_chasis_number,
                                            'vehicle_engine_number' =>$master_data->vehicle_engine_number,
                                            'fuel_type'             =>$master_data->fuel_type,
                                            'norms_type'            =>$master_data->norms_type,
                                            'insurance_company'     =>$master_data->insurance_company,
                                            'insurance_policy_number'=>$master_data->insurance_policy_number,
                                            'insurance_upto'         =>$master_data->insurance_upto,
                                            'registered_at'          =>$master_data->registered_at,
                                            ]
                            ];
        
                            $response=['status'=>true,
                                        'data'=>$array_result,
                                        'initiated_date'=>date('d-m-Y'),
                                        'completed_date'=>date('d-m-Y'),
                                        'message' => 'Verification Done Successfully !!'
                                    ];
                    }
                    else
                    {
                        //check from live API
                        // Setup request to send json via POST
                        $data = array(
                            'id_number'    => $request->rc_number,
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
                            $checkIDInDB= DB::table('rc_check_masters')->where(['rc_number'=>$request->rc_number])->count();
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
                                        'created_at'            =>date('Y-m-d H:i:s'),
                                        'platform_reference'   =>  'api'
                                        ];

                                DB::table('rc_check_masters')->insert($data);
                                
                                $master_data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$request->rc_number])->first();

                                $log_data = [
                                    'parent_id'         =>$parent_id,
                                    'business_id'       => $business_id,
                                    'service_id'        =>7,
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
                                    'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                                    'used_by'               =>'customer',
                                    'user_id'                =>  $request->login_id,
                                    'created_at'            =>date('Y-m-d H:i:s'),
                                    'platform_reference'   =>  'api'
                                    ];
                
                                    DB::table('rc_checks')->insert($log_data);
                            }
                            DB::commit();
                            $array_result=[
                                'rc_number'=>$master_data->rc_number,
                                'rc_validity'=>'valid',
                                'verification_check'=>'completed',
                                'result' => [
                                            'rc_number_exist'=>$master_data->rc_number,
                                            'registration_date' =>$master_data->registration_date,
                                            'owner_name'        =>$master_data->owner_name,
                                            'vehicle_chasis_number' =>$master_data->vehicle_chasis_number,
                                            'vehicle_engine_number' =>$master_data->vehicle_engine_number,
                                            'fuel_type'             =>$master_data->fuel_type,
                                            'norms_type'            =>$master_data->norms_type,
                                            'insurance_company'     =>$master_data->insurance_company,
                                            'insurance_policy_number'=>$master_data->insurance_policy_number,
                                            'insurance_upto'         =>$master_data->insurance_upto,
                                            'registered_at'          =>$master_data->registered_at,
                                            ]
                            ];
        
                            $response=['status'=>true,
                                        'data'=>$array_result,
                                        'initiated_date'=>date('d-m-Y'),
                                        'completed_date'=>date('d-m-Y'),
                                        'message' => 'Verification Done Successfully !!'
                                    ];

                        }else{
                            $response=['status'=>false,
                                        'data'=>NULL,
                                        'message' => 'It Seems like ID Number is Invalid !'
                                    ];
                        }
                    }
                }
                else if($user_d->user_type=='client')
                {
                    $price=20;

                    $data = DB::table('check_price_cocs')->where(['service_id'=>7,'coc_id'=>$business_id])->first();
                    if($data!=NULL)
                    {
                        $price=$data->price;
                    }
                    else{
                        $data=DB::table('check_prices')->where(['service_id'=>7,'business_id'=>$parent_id])->first();
                        if($data!=NULL)
                        {
                            $price=$data->price;
                        }
                        else{
                            $data=DB::table('check_price_masters')->where(['service_id'=>7])->first();
                            if($data!=NULL)
                            {
                                $price=$data->price;
                            }
                        }
                    }
                    //check first into master table
                    $master_data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$request->rc_number])->first();
                    if($master_data !=null){
                        $data = $master_data;
                        $log_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       => $business_id,
                            'service_id'        =>7,
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
                            'used_by'               =>'coc',
                            'user_id'                =>  $request->login_id,
                            'created_at'            =>date('Y-m-d H:i:s'),
                            'platform_reference'   =>  'api'
                            ];

                            DB::table('rc_checks')->insert($log_data);
                            DB::commit();
                            $array_result=[
                                'rc_number'=>$master_data->rc_number,
                                'rc_validity'=>'valid',
                                'verification_check'=>'completed',
                                'result' => [
                                            'rc_number_exist'=>$master_data->rc_number,
                                            'registration_date' =>$master_data->registration_date,
                                            'owner_name'        =>$master_data->owner_name,
                                            'vehicle_chasis_number' =>$master_data->vehicle_chasis_number,
                                            'vehicle_engine_number' =>$master_data->vehicle_engine_number,
                                            'fuel_type'             =>$master_data->fuel_type,
                                            'norms_type'            =>$master_data->norms_type,
                                            'insurance_company'     =>$master_data->insurance_company,
                                            'insurance_policy_number'=>$master_data->insurance_policy_number,
                                            'insurance_upto'         =>$master_data->insurance_upto,
                                            'registered_at'          =>$master_data->registered_at,
                                            ]
                            ];
        
                            $response=['status'=>true,
                                        'data'=>$array_result,
                                        'initiated_date'=>date('d-m-Y'),
                                        'completed_date'=>date('d-m-Y'),
                                        'message' => 'Verification Done Successfully !!'
                                    ];
                    }
                    else
                    {
                        //check from live API
                        // Setup request to send json via POST
                        $data = array(
                            'id_number'    => $request->rc_number,
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
                            $checkIDInDB= DB::table('rc_check_masters')->where(['rc_number'=>$request->rc_number])->count();
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
                                        'created_at'            =>date('Y-m-d H:i:s'),
                                        'platform_reference'   =>  'api'
                                        ];

                                DB::table('rc_check_masters')->insert($data);
                                
                                $master_data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$request->rc_number])->first();

                                $log_data = [
                                    'parent_id'         =>$parent_id,
                                    'business_id'       => $business_id,
                                    'service_id'        =>7,
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
                                    'used_by'               =>'coc',
                                    'user_id'                =>  $request->login_id,
                                    'created_at'            =>date('Y-m-d H:i:s'),
                                    'platform_reference'   =>  'api'
                                    ];
                
                                    DB::table('rc_checks')->insert($log_data);
                            }
                            DB::commit();
                            $array_result=[
                                'rc_number'=>$master_data->rc_number,
                                'rc_validity'=>'valid',
                                'verification_check'=>'completed',
                                'result' => [
                                            'rc_number_exist'=>$master_data->rc_number,
                                            'registration_date' =>$master_data->registration_date,
                                            'owner_name'        =>$master_data->owner_name,
                                            'vehicle_chasis_number' =>$master_data->vehicle_chasis_number,
                                            'vehicle_engine_number' =>$master_data->vehicle_engine_number,
                                            'fuel_type'             =>$master_data->fuel_type,
                                            'norms_type'            =>$master_data->norms_type,
                                            'insurance_company'     =>$master_data->insurance_company,
                                            'insurance_policy_number'=>$master_data->insurance_policy_number,
                                            'insurance_upto'         =>$master_data->insurance_upto,
                                            'registered_at'          =>$master_data->registered_at,
                                            ]
                            ];
        
                            $response=['status'=>true,
                                        'data'=>$array_result,
                                        'initiated_date'=>date('d-m-Y'),
                                        'completed_date'=>date('d-m-Y'),
                                        'message' => 'Verification Done Successfully !!'
                                    ];

                        }else{
                            $response=['status'=>false,
                                        'data'=>NULL,
                                        'message' => 'It Seems like ID Number is Invalid !'
                                    ];
                        }
                    }
                }
                else
                {
                    $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'Permission Denied !!'
                        ];
                }
            }
            else
            {
                $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'User Not Found'
                        ];
            }
            return response()->json($response,200);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
    }

    /**
     * Passport API
     *
     * This API is used for show the Passport details based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.
     * 
     * @authenticated
     * 
     * @bodyParam  login_id integer required Login ID for finding the user. Example: 94
     * 
     * @bodyParam  file_number string required File Number to run check on (alpha-numeric & min:8). Example: "BP8063370822817"
     * 
     * @bodyParam  dob date required Date of Birth to run check on. Example: "1991-07-05"
     * 
     * @response 401 {"status":false,"message":"Permission Denied!!"}
     * 
     * @response 404 {"status":false,"data":null,"message":"User Not Found!!"}
     * 
     * @response 406 {"status":false,"data":null,"message":"It seems like ID number is not valid!"}
     * 
     * @responseFile responses/verification/passport/success.json
     * 
     */
    public function idCheckPassport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_id'  => 'required',

        ]);

        
        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }
        DB::beginTransaction();
        try{
            $users=DB::table('users')->where(['id'=>$request->login_id])->whereNotIn('user_type',['candidate','guest'])->first();
            if($users!=NULL)
            {
                $business_id=$users->business_id;
                $parent_id=$users->parent_id;
                $user_d=DB::table('users')->where('id',$business_id)->first();

                if($users->user_type=='user' || $users->user_type=='User')
                {
                    $parent_id=$user_d->parent_id;
                }

                $rules=[
                    'file_number'  => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{15,}$/',
                    'dob' => 'required|date'
                ];
                $custommessages=[
                    'file_number.regex' => 'Please enter a valid Passport File number !'
                ];
                $validator = Validator::make($request->all(), $rules,$custommessages);
                    
                
                if ($validator->fails()) {            
                    return response()->json(['status' => 'error',
                                            'message'=>'The given data was invalid.',
                                            'errors'=> $validator->errors()], 200);
                }
                $dob = date('Y-m-d',strtotime($request->dob));
                if($user_d->user_type=='customer')
                {
                    $price=20.00;
                    $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>8])->first();
                    //check first into master table
                    $master_data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$request->file_number,'dob'=>$dob])->first();
                    if($master_data !=null){

                        $log_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       =>$business_id,
                            'service_id'        =>8,
                            'source_type'       =>'SystemDb',
                            'api_client_id'     =>$master_data->api_client_id,
                            'passport_number'   =>$master_data->passport_number,
                            'full_name'         =>$master_data->full_name,
                            'file_number'       =>$master_data->file_number,
                            'dob'       =>$master_data->dob,
                            'date_of_application'=>$master_data->date_of_application,
                            'is_verified'       =>'1',
                            'is_passport_exist' =>'1',
                            'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                            'used_by'           => 'customer',
                            'user_id'            => $request->login_id,
                            'platform_reference'   =>  'api',
                            'created_at'        =>date('Y-m-d H:i:s')
                            ];

                        DB::table('passport_checks')->insert($log_data);
                        DB::commit();
                        $array_result=[
                            'passport_number'=>$master_data->passport_number,
                            'passport_validity'=>'valid',
                            'verification_check'=>'completed',
                            'result' => [
                                        'passport_number_exist'=>$master_data->passport_number,
                                        'name'         =>$master_data->full_name,
                                        'dob'           =>$master_data->dob,
                                        'file_number'       =>$master_data->file_number,
                                        'date_of_application'=>$master_data->date_of_application
                                        ]
                        ];

                        $response=['status'=>true,
                                    'data'=>$array_result,
                                    'initiated_date'=>date('d-m-Y'),
                                    'completed_date'=>date('d-m-Y'),
                                    'message' => 'Verification Done Successfully !!'
                                ];

                    }
                    else
                    {
                        //check from live API
                        // Setup request to send json via POST
                        $data = array(
                            'id_number' => $request->file_number,
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
                            $checkIDInDB= DB::table('passport_check_masters')->where(['file_number'=>$request->file_number,'dob'=>$dob])->count();
                            if($checkIDInDB ==0)
                            {
                                
                                $data = [
                                        'api_client_id'     =>$array_data['data']['client_id'],
                                        'passport_number'   =>$array_data['data']['passport_number'],
                                        'full_name'         =>$array_data['data']['full_name'],
                                        'file_number'       =>$array_data['data']['file_number'],
                                        'date_of_application'=>$array_data['data']['date_of_application'],
                                        'dob'               =>$dob,
                                        'is_verified'       =>'1',
                                        'is_passport_exist' =>'1',
                                        'created_at'        =>date('Y-m-d H:i:s')
                                        ];

                                DB::table('passport_check_masters')->insert($data);
                                
                                $master_data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$request->file_number])->first();

                                $log_data = [
                                    'parent_id'         =>$parent_id,
                                    'business_id'       =>$business_id,
                                    'service_id'        =>8,
                                    'source_type'       =>'API',
                                    'api_client_id'     =>$master_data->api_client_id,
                                    'passport_number'   =>$master_data->passport_number,
                                    'full_name'         =>$master_data->full_name,
                                    'file_number'       =>$master_data->file_number,
                                    'dob'       =>$master_data->dob,
                                    'date_of_application'=>$master_data->date_of_application,
                                    'is_verified'       =>'1',
                                    'is_passport_exist' =>'1',
                                    'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                                    'used_by'           => 'customer',
                                    'user_id'            => $request->login_id,
                                    'platform_reference'   =>  'api',
                                    'created_at'        =>date('Y-m-d H:i:s')
                                    ];
                
                                DB::table('passport_checks')->insert($log_data);
                            }
                            DB::commit();
                            $array_result=[
                                'passport_number'=>$master_data->passport_number,
                                'passport_validity'=>'valid',
                                'verification_check'=>'completed',
                                'result' => [
                                            'passport_number_exist'=>$master_data->passport_number,
                                            'name'         =>$master_data->full_name,
                                            'dob'           =>$master_data->dob,
                                            'file_number'       =>$master_data->file_number,
                                            'date_of_application'=>$master_data->date_of_application
                                            ]
                            ];
        
                            $response=['status'=>true,
                                        'data'=>$array_result,
                                        'initiated_date'=>date('d-m-Y'),
                                        'completed_date'=>date('d-m-Y'),
                                        'message' => 'Verification Done Successfully !!'
                                    ];

                        }
                        else{
                            $response=['status'=>false,
                                        'data'=>NULL,
                                        'message' => 'It Seems like ID Number is Invalid !'
                                    ];
                        }
                    }
                }
                else if($user_d->user_type=='client')
                {
                    $price=20.00;

                    $data = DB::table('check_price_cocs')->where(['service_id'=>8,'coc_id'=>$business_id])->first();
                    if($data!=NULL)
                    {
                        $price=$data->price;
                    }
                    else{
                        $data=DB::table('check_prices')->where(['service_id'=>8,'business_id'=>$parent_id])->first();
                        if($data!=NULL)
                        {
                            $price=$data->price;
                        }
                        // else{
                        //     $data=DB::table('check_price_masters')->where(['service_id'=>8])->first();
                        //     if($data!=NULL)
                        //     {
                        //         $price=$data->price;
                        //     }
                        // }
                    }

                    //check first into master table
                    $master_data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$request->file_number,'dob'=>$dob])->first();
                    if($master_data !=null){

                        $log_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       =>$business_id,
                            'service_id'        =>8,
                            'source_type'       =>'SystemDb',
                            'api_client_id'     =>$master_data->api_client_id,
                            'passport_number'   =>$master_data->passport_number,
                            'full_name'         =>$master_data->full_name,
                            'file_number'       =>$master_data->file_number,
                            'dob'       =>$master_data->dob,
                            'date_of_application'=>$master_data->date_of_application,
                            'is_verified'       =>'1',
                            'is_passport_exist' =>'1',
                            'price'             =>$price,
                            'used_by'           => 'coc',
                            'user_id'            => $request->login_id,
                            'platform_reference'   =>  'api',
                            'created_at'        =>date('Y-m-d H:i:s')
                            ];

                        DB::table('passport_checks')->insert($log_data);
                        DB::commit();
                        $array_result=[
                            'passport_number'=>$master_data->passport_number,
                            'passport_validity'=>'valid',
                            'verification_check'=>'completed',
                            'result' => [
                                        'passport_number_exist'=>$master_data->passport_number,
                                        'name'         =>$master_data->full_name,
                                        'dob'           =>$master_data->dob,
                                        'file_number'       =>$master_data->file_number,
                                        'date_of_application'=>$master_data->date_of_application
                                        ]
                        ];

                        $response=['status'=>true,
                                    'data'=>$array_result,
                                    'initiated_date'=>date('d-m-Y'),
                                    'completed_date'=>date('d-m-Y'),
                                    'message' => 'Verification Done Successfully !!'
                                ];

                    }
                    else
                    {
                        //check from live API
                        // Setup request to send json via POST
                        $data = array(
                            'id_number' => $request->file_number,
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
                            $checkIDInDB= DB::table('passport_check_masters')->where(['file_number'=>$request->file_number,'dob'=>$dob])->count();
                            if($checkIDInDB ==0)
                            {
                                
                                $data = [
                                        'api_client_id'     =>$array_data['data']['client_id'],
                                        'passport_number'   =>$array_data['data']['passport_number'],
                                        'full_name'         =>$array_data['data']['full_name'],
                                        'file_number'       =>$array_data['data']['file_number'],
                                        'date_of_application'=>$array_data['data']['date_of_application'],
                                        'dob'               =>$dob,
                                        'is_verified'       =>'1',
                                        'is_passport_exist' =>'1',
                                        'created_at'        =>date('Y-m-d H:i:s')
                                        ];

                                DB::table('passport_check_masters')->insert($data);
                                
                                $master_data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$request->file_number])->first();

                                $log_data = [
                                    'parent_id'         =>$parent_id,
                                    'business_id'       =>$business_id,
                                    'service_id'        =>8,
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
                                    'used_by'           => 'coc',
                                    'user_id'            => $request->login_id,
                                    'platform_reference'   =>  'api',
                                    'created_at'        =>date('Y-m-d H:i:s')
                                    ];
                
                                DB::table('passport_checks')->insert($log_data);
                            }
                            DB::commit();
                            $array_result=[
                                'passport_number'=>$master_data->passport_number,
                                'passport_validity'=>'valid',
                                'verification_check'=>'completed',
                                'result' => [
                                            'passport_number_exist'=>$master_data->passport_number,
                                            'name'         =>$master_data->full_name,
                                            'dob'           =>$master_data->dob,
                                            'file_number'       =>$master_data->file_number,
                                            'date_of_application'=>$master_data->date_of_application
                                            ]
                            ];
        
                            $response=['status'=>true,
                                        'data'=>$array_result,
                                        'initiated_date'=>date('d-m-Y'),
                                        'completed_date'=>date('d-m-Y'),
                                        'message' => 'Verification Done Successfully !!'
                                    ];

                        }
                        else{
                            $response=['status'=>false,
                                        'data'=>NULL,
                                        'message' => 'It Seems like ID Number is Invalid !'
                                    ];
                        }
                    }

                }
                else
                {
                    $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'Permission Denied !!'
                        ];
                }
            }
            else
            {
                $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'User Not Found'
                        ];
            }

            return response()->json($response,200);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
    }

    /**
     * DL API
     *
     * This API is used for show the Driving License details based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.
     * 
     * @authenticated
     * 
     * @bodyParam  login_id integer required Login ID for finding the user. Example: 94
     * 
     * @bodyParam  dl_number string required DL Number to run check on (alpha-numeric & min:8). Example: "DL0520160307903"
     * 
     * @response 401 {"status":false,"message":"Permission Denied!!"}
     * 
     * @response 404 {"status":false,"data":null,"message":"User Not Found!!"}
     * 
     * @response 406 {"status":false,"data":null,"message":"It seems like ID number is not valid!"}
     * 
     * @responseFile responses/verification/dl/success.json
     * 
     */
    public function idCheckDL(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_id'  => 'required',

        ]);

        
        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }
        DB::beginTransaction();
        try{
            $users=DB::table('users')->where(['id'=>$request->login_id])->whereNotIn('user_type',['candidate','guest'])->first();
            if($users!=NULL)
            {
                $business_id=$users->business_id;
                $parent_id=$users->parent_id;
                $user_d=DB::table('users')->where('id',$business_id)->first();

                if($users->user_type=='user' || $users->user_type=='User')
                {
                    $parent_id=$user_d->parent_id;
                }

                $rules=[
                    'dl_number'  => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{15,}$/',
                ];
                $custommessages=[
                    'dl_number.regex' => 'Please enter a valid DL number !'
                ];
                $validator = Validator::make($request->all(), $rules,$custommessages);
                    
                
                if ($validator->fails()) {            
                    return response()->json(['status' => 'error',
                                            'message'=>'The given data was invalid.',
                                            'errors'=> $validator->errors()], 200);
                }
                if($user_d->user_type=='customer')
                {
                    $price=20.00;
                    $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>9])->first();
                    //check first into master table
                    $master_data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$request->dl_number])->first();
                    
                    if($master_data !=null){

                        $log_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       => $business_id,
                            'service_id'        => 9,
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
                            'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                            'used_by'           =>'customer',
                            'user_id'            =>$request->login_id,
                            'platform_reference'   =>  'api',
                            'created_at'        =>date('Y-m-d H:i:s')
                            ];
                        
                        DB::table('dl_checks')->insert($log_data);
                        DB::commit();
                        $array_result=[
                            'dl_number'=>$master_data->dl_number,
                            'dl_validity'=>'valid',
                            'verification_check'=>'completed',
                            'result' => [
                                        'dl_number_exist'=>$master_data->dl_number,
                                        'name'         =>$master_data->name,
                                        'gender'            =>$master_data->gender,
                                        'dob'               =>$master_data->dob,
                                        'father_or_husband_name' =>$master_data->father_or_husband_name,
                                        'permanent_address' =>$master_data->permanent_address,
                                        'state'             =>$master_data->state,
                                        'citizenship'       =>$master_data->citizenship,
                                        'dto'          =>$master_data->ola_name,
                                        'date_of_expiry'    =>$master_data->doe,
                                        ]
                        ];

                        $response=['status'=>true,
                                    'data'=>$array_result,
                                    'initiated_date'=>date('d-m-Y'),
                                    'completed_date'=>date('d-m-Y'),
                                    'message' => 'Verification Done Successfully !!'
                                ];
                        
                    }
                    else
                    {
                        //check from live API
                        // Setup request to send json via POST
                        $data = array(
                            'id_number'    => $request->dl_number,
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
                        // print_r($array_data); die;

                        if($array_data['success'])
                        {
                            //check if ID number is new then insert into DB
                            $checkIDInDB= DB::table('dl_check_masters')->where(['dl_number'=>$request->dl_number])->count();
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
                                    
                                    DB::table('dl_check_masters')->insert($data);
                                
                                $master_data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$request->dl_number])->first();

                                $log_data = [
                                    'parent_id'         =>$parent_id,
                                    'business_id'       => $business_id,
                                    'service_id'        =>9,
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
                                    'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                                    'used_by'           =>'customer',
                                    'user_id'            =>$request->login_id,
                                    'platform_reference'   =>  'api',
                                    'created_at'        =>date('Y-m-d H:i:s')
                                    ];
                                
                                DB::table('dl_checks')->insert($log_data);
                            }
                            DB::commit();
                            $array_result=[
                                'dl_number'=>$master_data->dl_number,
                                'dl_validity'=>'valid',
                                'verification_check'=>'completed',
                                'result' => [
                                            'dl_number_exist'=>$master_data->dl_number,
                                            'name'         =>$master_data->name,
                                            'gender'            =>$master_data->gender,
                                            'dob'               =>$master_data->dob,
                                            'father_or_husband_name' =>$master_data->father_or_husband_name,
                                            'permanent_address' =>$master_data->permanent_address,
                                            'state'             =>$master_data->state,
                                            'citizenship'       =>$master_data->citizenship,
                                            'dto'          =>$master_data->ola_name,
                                            'date_of_expiry'    =>$master_data->doe,
                                            ]
                            ];
        
                            $response=['status'=>true,
                                        'data'=>$array_result,
                                        'initiated_date'=>date('d-m-Y'),
                                        'completed_date'=>date('d-m-Y'),
                                        'message' => 'Verification Done Successfully !!'
                                    ];

                            

                        }else{
                            $response=['status'=>false,
                                        'data'=>NULL,
                                        'message' => 'It Seems like ID Number is Invalid !'
                                    ];
                        }
                    }
                    
                }
                else if($user_d->user_type=='client')
                {
                    $price=20;

                    $data = DB::table('check_price_cocs')->where(['service_id'=>9,'coc_id'=>$business_id])->first();
                    if($data!=NULL)
                    {
                        $price=$data->price;
                    }
                    else{
                        $data=DB::table('check_prices')->where(['service_id'=>9,'business_id'=>$parent_id])->first();
                        if($data!=NULL)
                        {
                            $price=$data->price;
                        }
                        else{
                            $data=DB::table('check_price_masters')->where(['service_id'=>9])->first();
                            if($data!=NULL)
                            {
                                $price=$data->price;
                            }
                        }
                    }

                    //check first into master table
                    $master_data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$request->dl_number])->first();
                    
                    if($master_data !=null){

                        $log_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       => $business_id,
                            'service_id'        => 9,
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
                            'used_by'           =>'coc',
                            'user_id'            =>$request->login_id,
                            'platform_reference'   =>  'api',
                            'created_at'        =>date('Y-m-d H:i:s')
                            ];
                        
                        DB::table('dl_checks')->insert($log_data);
                        DB::commit();
                        $array_result=[
                            'dl_number'=>$master_data->dl_number,
                            'dl_validity'=>'valid',
                            'verification_check'=>'completed',
                            'result' => [
                                        'dl_number_exist'=>$master_data->dl_number,
                                        'name'         =>$master_data->name,
                                        'gender'            =>$master_data->gender,
                                        'dob'               =>$master_data->dob,
                                        'father_or_husband_name' =>$master_data->father_or_husband_name,
                                        'permanent_address' =>$master_data->permanent_address,
                                        'state'             =>$master_data->state,
                                        'citizenship'       =>$master_data->citizenship,
                                        'dto'          =>$master_data->ola_name,
                                        'date_of_expiry'    =>$master_data->doe,
                                        ]
                        ];

                        $response=['status'=>true,
                                    'data'=>$array_result,
                                    'initiated_date'=>date('d-m-Y'),
                                    'completed_date'=>date('d-m-Y'),
                                    'message' => 'Verification Done Successfully !!'
                                ];
                        
                    }
                    else
                    {
                        //check from live API
                        // Setup request to send json via POST
                        $data = array(
                            'id_number'    => $request->dl_number,
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
                        // print_r($array_data); die;

                        if($array_data['success'])
                        {
                            //check if ID number is new then insert into DB
                            $checkIDInDB= DB::table('dl_check_masters')->where(['dl_number'=>$request->dl_number])->count();
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
                                    
                                    DB::table('dl_check_masters')->insert($data);
                                
                                $master_data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$request->dl_number])->first();

                                $log_data = [
                                    'parent_id'         =>$parent_id,
                                    'business_id'       => $business_id,
                                    'service_id'        =>9,
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
                                    'used_by'           =>'coc',
                                    'user_id'            =>$request->login_id,
                                    'platform_reference'   =>  'api',
                                    'created_at'        =>date('Y-m-d H:i:s')
                                    ];
                                
                                DB::table('dl_checks')->insert($log_data);
                            }
                            DB::commit();
                            $array_result=[
                                'dl_number'=>$master_data->dl_number,
                                'dl_validity'=>'valid',
                                'verification_check'=>'completed',
                                'result' => [
                                            'dl_number_exist'=>$master_data->dl_number,
                                            'name'         =>$master_data->name,
                                            'gender'            =>$master_data->gender,
                                            'dob'               =>$master_data->dob,
                                            'father_or_husband_name' =>$master_data->father_or_husband_name,
                                            'permanent_address' =>$master_data->permanent_address,
                                            'state'             =>$master_data->state,
                                            'citizenship'       =>$master_data->citizenship,
                                            'dto'          =>$master_data->ola_name,
                                            'date_of_expiry'    =>$master_data->doe,
                                            ]
                            ];
        
                            $response=['status'=>true,
                                        'data'=>$array_result,
                                        'initiated_date'=>date('d-m-Y'),
                                        'completed_date'=>date('d-m-Y'),
                                        'message' => 'Verification Done Successfully !!'
                                    ];

                            

                        }else{
                            $response=['status'=>false,
                                        'data'=>NULL,
                                        'message' => 'It Seems like ID Number is Invalid !'
                                    ];
                        }
                    }
                    
                }
                else
                {
                    $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'Permission Denied !!'
                        ];   
                }


            }
            else
            {
                $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'User Not Found'
                        ];
            }
            return response()->json($response,200);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
    }

    /**
     * GSTIN API
     *
     * This API is used for show the GST details based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.
     * 
     * @authenticated
     * 
     * @bodyParam  login_id integer required Login ID for finding the user. Example: 94
     * 
     * @bodyParam  gst_number string required GST Number to run check on (alpha-numeric & min:15). Example: "37AAACI4403L1ZN"
     * 
     * @bodyParam  filling_status boolean required Filling Status to run check on. Example: true
     * 
     * @response 401 {"status":false,"message":"Permission Denied!!"}
     * 
     * @response 404 {"status":false,"data":null,"message":"User Not Found!!"}
     * 
     * @response 406 {"status":false,"data":null,"message":"It seems like ID number is not valid!"}
     * 
     * @responseFile responses/verification/gstin/success.json
     * 
     */
    public function idCheckGSTIN(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_id'  => 'required',

        ]);

        
        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }
        DB::beginTransaction();
        try{
            $users=DB::table('users')->where(['id'=>$request->login_id])->whereNotIn('user_type',['candidate','guest'])->first();
            if($users!=NULL)
            {
                $business_id=$users->business_id;
                $parent_id=$users->parent_id;
                $user_d=DB::table('users')->where('id',$business_id)->first();

                if($users->user_type=='user' || $users->user_type=='User')
                {
                    $parent_id=$user_d->parent_id;
                }

                $rules=[
                    'gst_number'  => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{15,}$/',
                    'filling_status' => 'required|in:true,false'
                ];
                $custommessages=[
                    'gst_number.regex' => 'Please enter a valid GST number !'
                ];
                $validator = Validator::make($request->all(), $rules,$custommessages);
                    
                
                if ($validator->fails()) {            
                    return response()->json(['status' => 'error',
                                            'message'=>'The given data was invalid.',
                                            'errors'=> $validator->errors()], 200);
                }
                $filling_status=$request->filling_status;
                if($user_d->user_type=='customer')
                {
                    $price=20.00;
                    $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>14])->first();

                    //check first into master table
                    $master_data = DB::table('gst_check_masters')->select('*')->where(['gst_number'=>$request->gst_number])->first();
                    if($master_data !=null){

                        $log_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'           =>$business_id,
                            'service_id'            => 14,
                            'source_type'           => 'SystemDb',
                            'api_client_id'         =>$master_data->api_client_id,
                            'gst_number'            =>$master_data->gst_number,
                            'business_name'         =>$master_data->business_name,
                            'legal_name'            =>$master_data->legal_name,
                            'center_jurisdiction'   =>$master_data->center_jurisdiction,
                            'date_of_registration'  =>$master_data->date_of_registration,
                            'constitution_of_business'=>$master_data->constitution_of_business,
                            'field_visit_conducted'   =>$master_data->field_visit_conducted,
                            'taxpayer_type'         =>$master_data->taxpayer_type,
                            'gstin_status'          =>$master_data->gstin_status,
                            'date_of_cancellation'  =>$master_data->date_of_cancellation,
                            'address'               =>$master_data->address,
                            'is_verified'           =>'1',
                            'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                            'used_by'               =>'customer',
                            'user_id'                =>$request->login_id,
                            'platform_reference'   =>  'api',
                            'created_at'            =>date('Y-m-d H:i:s')
                            ];

                        DB::table('gst_checks')->insert($log_data);
                        DB::commit();
                        $array_result=[
                            'gst_number'=>$master_data->gst_number,
                            'gst_validity'=>'valid',
                            'verification_check'=>'completed',
                            'result' => [
                                        'gst_number_exist'=>$master_data->gst_number,
                                        'business_name'         =>$master_data->business_name,
                                        'address'               =>$master_data->address,
                                        'center_jurisdiction'   =>$master_data->center_jurisdiction,
                                        'date_of_registration'  =>$master_data->date_of_registration,
                                        'constitution_of_business'=>$master_data->constitution_of_business,
                                        'taxpayer_type'         =>$master_data->taxpayer_type,
                                        'gstin_status'          =>$master_data->gstin_status,
                                        ]
                        ];

                        $response=['status'=>true,
                                    'data'=>$array_result,
                                    'initiated_date'=>date('d-m-Y'),
                                    'completed_date'=>date('d-m-Y'),
                                    'message' => 'Verification Done Successfully !!'
                                ];
                        
                    }
                    else
                    {
                        //check from live API
                        // Setup request to send json via POST
                        $data = array(
                            'id_number'         => $request->gst_number,
                            'filing_status_get' => $filling_status,
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
                                $checkIDInDB= DB::table('gst_check_masters')->where(['gst_number'=>$request->gst_number])->count();
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
                                    
                                    $master_data = DB::table('gst_check_masters')->select('*')->where(['gst_number'=>$request->gst_number])->first();

                                    $log_data = [
                                        'parent_id'         =>$parent_id,
                                        'business_id'           =>$business_id,
                                        'service_id'            => 14,
                                        'source_type'           => 'API',
                                        'api_client_id'         =>$master_data->api_client_id,
                                        'gst_number'            =>$master_data->gst_number,
                                        'business_name'         =>$master_data->business_name,
                                        'legal_name'            =>$master_data->legal_name,
                                        'center_jurisdiction'   =>$master_data->center_jurisdiction,
                                        'date_of_registration'  =>$master_data->date_of_registration,
                                        'constitution_of_business'=>$master_data->constitution_of_business,
                                        'field_visit_conducted'   =>$master_data->field_visit_conducted,
                                        'taxpayer_type'         =>$master_data->taxpayer_type,
                                        'gstin_status'          =>$master_data->gstin_status,
                                        'date_of_cancellation'  =>$master_data->date_of_cancellation,
                                        'address'               =>$master_data->address,
                                        'is_verified'           =>'1',
                                        'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                                        'used_by'               =>'customer',
                                        'user_id'                =>$request->login_id,
                                        'platform_reference'   =>  'api',
                                        'created_at'            =>date('Y-m-d H:i:s')
                                        ];
                    
                                    DB::table('gst_checks')->insert($log_data);
                                }
                                DB::commit();
                                $array_result=[
                                    'gst_number'=>$master_data->gst_number,
                                    'gst_validity'=>'valid',
                                    'verification_check'=>'completed',
                                    'result' => [
                                                'gst_number_exist'=>$master_data->gst_number,
                                                'business_name'         =>$master_data->business_name,
                                                'address'               =>$master_data->address,
                                                'center_jurisdiction'   =>$master_data->center_jurisdiction,
                                                'date_of_registration'  =>$master_data->date_of_registration,
                                                'constitution_of_business'=>$master_data->constitution_of_business,
                                                'taxpayer_type'         =>$master_data->taxpayer_type,
                                                'gstin_status'          =>$master_data->gstin_status,
                                                ]
                                ];
            
                                $response=[
                                            'status'=>true,
                                            'data'=>$array_result,
                                            'initiated_date'=>date('d-m-Y'),
                                            'completed_date'=>date('d-m-Y'),
                                            'message' => 'Verification Done Successfully !!'
                                        ];

                            }else{
                                $response=['status'=>false,
                                        'data'=>NULL,
                                        'message' => 'It Seems like ID Number is Invalid !'
                                    ];
                            }
                    }

                }
                else if($user_d->user_type=='client')
                {
                    $price=20;

                    $data = DB::table('check_price_cocs')->where(['service_id'=>14,'coc_id'=>$business_id])->first();
                    if($data!=NULL)
                    {
                        $price=$data->price;
                    }
                    else{
                        $data=DB::table('check_prices')->where(['service_id'=>14,'business_id'=>$parent_id])->first();
                        if($data!=NULL)
                        {
                            $price=$data->price;
                        }
                        else{
                            $data=DB::table('check_price_masters')->where(['service_id'=>14])->first();
                            if($data!=NULL)
                            {
                                $price=$data->price;
                            }
                        }
                    }

                    //check first into master table
                    $master_data = DB::table('gst_check_masters')->select('*')->where(['gst_number'=>$request->gst_number])->first();
                    if($master_data !=null){

                        $log_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'           =>$business_id,
                            'service_id'            => 14,
                            'source_type'           => 'SystemDb',
                            'api_client_id'         =>$master_data->api_client_id,
                            'gst_number'            =>$master_data->gst_number,
                            'business_name'         =>$master_data->business_name,
                            'legal_name'            =>$master_data->legal_name,
                            'center_jurisdiction'   =>$master_data->center_jurisdiction,
                            'date_of_registration'  =>$master_data->date_of_registration,
                            'constitution_of_business'=>$master_data->constitution_of_business,
                            'field_visit_conducted'   =>$master_data->field_visit_conducted,
                            'taxpayer_type'         =>$master_data->taxpayer_type,
                            'gstin_status'          =>$master_data->gstin_status,
                            'date_of_cancellation'  =>$master_data->date_of_cancellation,
                            'address'               =>$master_data->address,
                            'is_verified'           =>'1',
                            'price'             =>$price,
                            'used_by'               =>'coc',
                            'user_id'                =>$request->login_id,
                            'platform_reference'   =>  'api',
                            'created_at'            =>date('Y-m-d H:i:s')
                            ];

                        DB::table('gst_checks')->insert($log_data);
                        DB::commit();
                        $array_result=[
                            'gst_number'=>$master_data->gst_number,
                            'gst_validity'=>'valid',
                            'verification_check'=>'completed',
                            'result' => [
                                        'gst_number_exist'=>$master_data->gst_number,
                                        'business_name'         =>$master_data->business_name,
                                        'address'               =>$master_data->address,
                                        'center_jurisdiction'   =>$master_data->center_jurisdiction,
                                        'date_of_registration'  =>$master_data->date_of_registration,
                                        'constitution_of_business'=>$master_data->constitution_of_business,
                                        'taxpayer_type'         =>$master_data->taxpayer_type,
                                        'gstin_status'          =>$master_data->gstin_status,
                                        ]
                        ];

                        $response=['status'=>true,
                                    'data'=>$array_result,
                                    'initiated_date'=>date('d-m-Y'),
                                    'completed_date'=>date('d-m-Y'),
                                    'message' => 'Verification Done Successfully !!'
                                ];
                        
                    }
                    else
                    {
                        //check from live API
                        // Setup request to send json via POST
                        $data = array(
                            'id_number'         => $request->gst_number,
                            'filing_status_get' => $filling_status,
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
                                $checkIDInDB= DB::table('gst_check_masters')->where(['gst_number'=>$request->gst_number])->count();
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
                                    
                                    $master_data = DB::table('gst_check_masters')->select('*')->where(['gst_number'=>$request->gst_number])->first();

                                    $log_data = [
                                        'parent_id'         =>$parent_id,
                                        'business_id'           =>$business_id,
                                        'service_id'            => 14,
                                        'source_type'           => 'API',
                                        'api_client_id'         =>$master_data->api_client_id,
                                        'gst_number'            =>$master_data->gst_number,
                                        'business_name'         =>$master_data->business_name,
                                        'legal_name'            =>$master_data->legal_name,
                                        'center_jurisdiction'   =>$master_data->center_jurisdiction,
                                        'date_of_registration'  =>$master_data->date_of_registration,
                                        'constitution_of_business'=>$master_data->constitution_of_business,
                                        'field_visit_conducted'   =>$master_data->field_visit_conducted,
                                        'taxpayer_type'         =>$master_data->taxpayer_type,
                                        'gstin_status'          =>$master_data->gstin_status,
                                        'date_of_cancellation'  =>$master_data->date_of_cancellation,
                                        'address'               =>$master_data->address,
                                        'is_verified'           =>'1',
                                        'price'             =>$price,
                                        'used_by'               =>'coc',
                                        'user_id'                =>$request->login_id,
                                        'platform_reference'   =>  'api',
                                        'created_at'            =>date('Y-m-d H:i:s')
                                        ];
                    
                                    DB::table('gst_checks')->insert($log_data);
                                }
                                DB::commit();
                                $array_result=[
                                    'gst_number'=>$master_data->gst_number,
                                    'gst_validity'=>'valid',
                                    'verification_check'=>'completed',
                                    'result' => [
                                                'gst_number_exist'=>$master_data->gst_number,
                                                'business_name'         =>$master_data->business_name,
                                                'address'               =>$master_data->address,
                                                'center_jurisdiction'   =>$master_data->center_jurisdiction,
                                                'date_of_registration'  =>$master_data->date_of_registration,
                                                'constitution_of_business'=>$master_data->constitution_of_business,
                                                'taxpayer_type'         =>$master_data->taxpayer_type,
                                                'gstin_status'          =>$master_data->gstin_status,
                                                ]
                                ];
            
                                $response=[
                                            'status'=>true,
                                            'data'=>$array_result,
                                            'initiated_date'=>date('d-m-Y'),
                                            'completed_date'=>date('d-m-Y'),
                                            'message' => 'Verification Done Successfully !!'
                                        ];

                            }else{
                                $response=['status'=>false,
                                        'data'=>NULL,
                                        'message' => 'It Seems like ID Number is Invalid !'
                                    ];
                            }
                    }

                }
                else
                {
                    $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'Permission Denied !!'
                        ];   
                }  
            }
            else
            {
                $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'User Not Found'
                        ];
            }

            return response()->json($response,200);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
    }

    /**
     * Bank Verification API
     *
     * This API is used for show the Bank Account details based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.
     * 
     * @authenticated
     * 
     * @bodyParam  login_id integer required Login ID for finding the user. Example: 94
     * 
     * @bodyParam  account_number string required Account Number to run check on (alpha-numeric & min:9,max:18). Example: "164001502522"
     * 
     * @bodyParam  ifsc_code string required IFSC Code to run check on. Example: "ICIC0002644"
     * 
     * @response 401 {"status":false,"message":"Permission Denied!!"}
     * 
     * @response 404 {"status":false,"data":null,"message":"User Not Found!!"}
     * 
     * @response 406 {"status":false,"data":null,"message":"It seems like ID number is not valid!"}
     * 
     * @responseFile responses/verification/bank/success.json
     * 
     */
    public function idCheckBankAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_id'  => 'required',

        ]);

        
        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }
        DB::beginTransaction();
        try{
            $users=DB::table('users')->where(['id'=>$request->login_id])->whereNotIn('user_type',['candidate','guest'])->first();
            if($users!=NULL)
            {
                $business_id=$users->business_id;
                $parent_id=$users->parent_id;
                $user_d=DB::table('users')->where('id',$business_id)->first();

                if($users->user_type=='user' || $users->user_type=='User')
                {
                    $parent_id=$user_d->parent_id;
                }

                $rules=[
                    'account_number'  => 'required|regex:/^(?=.*[0-9])[A-Z0-9]{9,18}$/',
                    'ifsc_code' => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{11,}$/'
                ];
                $custommessages=[
                    'account_number.regex' => 'Please enter a valid bank account number !',
                    'ifsc_code.regex' => 'Please enter a valid IFSC Code !',
                ];
                $validator = Validator::make($request->all(), $rules,$custommessages);
                    
                
                if ($validator->fails()) {            
                    return response()->json(['status' => 'error',
                                            'message'=>'The given data was invalid.',
                                            'errors'=> $validator->errors()], 200);
                }
                if($user_d->user_type=='customer')
                {
                    $price=20.00;
                    $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>12])->first();

                    //check first into master table
                    $master_data = DB::table('bank_account_check_masters')->select('*')->where(['account_number'=>$request->account_number,'ifsc_code'=>$request->ifsc_code])->first();
                    if($master_data !=null){
                    

                        $log_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       =>$business_id,
                            'service_id'            => 12,
                            'source_type'       =>'SystemDb',
                            'api_client_id'     =>$master_data->api_client_id,
                            'account_number'    =>$master_data->account_number,
                            'full_name'         =>$master_data->full_name,
                            'ifsc_code'         =>$master_data->ifsc_code,
                            'is_verified'       =>'1',
                            'is_account_exist' =>'1',
                            'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                            'used_by'           =>'customer',
                            'user_id'            =>$request->login_id,
                            'platform_reference' => 'api',
                            'created_at'        =>date('Y-m-d H:i:s')
                            ];
                        DB::table('bank_account_checks')->insert($log_data);
                        DB::commit();
                        
                        $array_result=[
                            'account_number'=>$master_data->account_number,
                            'account_validity'=>'valid',
                            'verification_check'=>'completed',
                            'result' => [
                                        'name' => $master_data->full_name,
                                        'account_number'=>$master_data->account_number,
                                        'ifsc_code' => $master_data->ifsc_code,
                                        ]
                        ];

                        $response=['status'=>true,
                                    'data'=>$array_result,
                                    'initiated_date'=>date('d-m-Y'),
                                    'completed_date'=>date('d-m-Y'),
                                    'message' => 'Verification Done Successfully !!'
                                ];
                        
                    }
                    else
                    {
                        //check from live API
                        // Setup request to send json via POST
                        $data = array(
                            'id_number' => $request->account_number,
                            'ifsc'      => $request->ifsc_code,
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
                            $checkIDInDB= DB::table('bank_account_check_masters')->where(['account_number'=>$request->account_number,'ifsc_code'=>$request->ifsc_code])->count();
                            if($checkIDInDB ==0)
                            {
                                
                                $data = [
                                        'api_client_id'     =>$array_data['data']['client_id'],
                                        'account_number'    =>$request->account_number,
                                        'full_name'         =>$array_data['data']['full_name'],
                                        'ifsc_code'         =>$request->ifsc_code,
                                        'is_verified'       =>'1',
                                        'is_account_exist' =>'1',
                                        'created_at'        =>date('Y-m-d H:i:s')
                                        ];

                                DB::table('bank_account_check_masters')->insert($data);
                                
                                $master_data = DB::table('bank_account_check_masters')->select('*')->where(['account_number'=>$request->account_number])->first();

                                $log_data = [
                                    'parent_id'         =>$parent_id,
                                    'business_id'       =>$business_id,
                                    'service_id'         => 12,
                                    'source_type'       =>'API',
                                    'api_client_id'     =>$master_data->api_client_id,
                                    'account_number'    =>$master_data->account_number,
                                    'full_name'         =>$master_data->full_name,
                                    'ifsc_code'         =>$master_data->ifsc_code,
                                    'is_verified'       =>'1',
                                    'is_account_exist' =>'1',
                                    'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                                    'used_by'           =>'customer',
                                    'user_id'            =>$request->login_id,
                                    'platform_reference'    =>'api',
                                    'created_at'        =>date('Y-m-d H:i:s')
                                    ];
                
                                DB::table('bank_account_checks')->insert($log_data);
                            }
                            DB::commit();
                            $array_result=[
                                'account_number'=>$master_data->account_number,
                                'account_validity'=>'valid',
                                'verification_check'=>'completed',
                                'result' => [
                                            'name' => $master_data->full_name,
                                            'account_number'=>$master_data->account_number,
                                            'ifsc_code' => $master_data->ifsc_code,
                                            ]
                            ];
        
                            $response=['status'=>true,
                                        'data'=>$array_result,
                                        'initiated_date'=>date('d-m-Y'),
                                        'completed_date'=>date('d-m-Y'),
                                        'message' => 'Verification Done Successfully !!'
                                    ];

                        }else{
                            $response=['status'=>false,
                                        'data'=>NULL,
                                        'message' => 'It Seems like ID Number is Invalid !'
                                    ];
                        }
                        
                    }
                }
                else if($user_d->user_type=='client')
                {
                    $price=20;

                    $data = DB::table('check_price_cocs')->where(['service_id'=>12,'coc_id'=>$business_id])->first();
                    if($data!=NULL)
                    {
                        $price=$data->price;
                    }
                    else{
                        $data=DB::table('check_prices')->where(['service_id'=>12,'business_id'=>$parent_id])->first();
                        if($data!=NULL)
                        {
                            $price=$data->price;
                        }
                        else{
                            $data=DB::table('check_price_masters')->where(['service_id'=>12])->first();
                            if($data!=NULL)
                            {
                                $price=$data->price;
                            }
                        }
                    }

                    //check first into master table
                    $master_data = DB::table('bank_account_check_masters')->select('*')->where(['account_number'=>$request->account_number,'ifsc_code'=>$request->ifsc_code])->first();
                    if($master_data !=null){
                    

                        $log_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       =>$business_id,
                            'service_id'            => 12,
                            'source_type'       =>'SystemDb',
                            'api_client_id'     =>$master_data->api_client_id,
                            'account_number'    =>$master_data->account_number,
                            'full_name'         =>$master_data->full_name,
                            'ifsc_code'         =>$master_data->ifsc_code,
                            'is_verified'       =>'1',
                            'is_account_exist' =>'1',
                            'price'             =>$price,
                            'used_by'           =>'coc',
                            'user_id'            =>$request->login_id,
                            'platform_reference' => 'api',
                            'created_at'        =>date('Y-m-d H:i:s')
                            ];
                        
                        DB::table('bank_account_checks')->insert($log_data);
                        DB::commit();
                        
                        $array_result=[
                            'account_number'=>$master_data->account_number,
                            'account_validity'=>'valid',
                            'verification_check'=>'completed',
                            'result' => [
                                        'name' => $master_data->full_name,
                                        'account_number'=>$master_data->account_number,
                                        'ifsc_code' => $master_data->ifsc_code,
                                        ]
                        ];

                        $response=['status'=>true,
                                    'data'=>$array_result,
                                    'initiated_date'=>date('d-m-Y'),
                                    'completed_date'=>date('d-m-Y'),
                                    'message' => 'Verification Done Successfully !!'
                                ];
                        
                    }
                    else
                    {
                        //check from live API
                        // Setup request to send json via POST
                        $data = array(
                            'id_number' => $request->account_number,
                            'ifsc'      => $request->ifsc_code,
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
                            $checkIDInDB= DB::table('bank_account_check_masters')->where(['account_number'=>$request->account_number,'ifsc_code'=>$request->ifsc_code])->count();
                            if($checkIDInDB ==0)
                            {
                                
                                $data = [
                                        'api_client_id'     =>$array_data['data']['client_id'],
                                        'account_number'    =>$request->account_number,
                                        'full_name'         =>$array_data['data']['full_name'],
                                        'ifsc_code'         =>$request->ifsc_code,
                                        'is_verified'       =>'1',
                                        'is_account_exist' =>'1',
                                        'created_at'        =>date('Y-m-d H:i:s')
                                        ];

                                DB::table('bank_account_check_masters')->insert($data);
                                
                                $master_data = DB::table('bank_account_check_masters')->select('*')->where(['account_number'=>$request->account_number])->first();

                                $log_data = [
                                    'parent_id'         =>$parent_id,
                                    'business_id'       =>$business_id,
                                    'service_id'         => 12,
                                    'source_type'       =>'API',
                                    'api_client_id'     =>$master_data->api_client_id,
                                    'account_number'    =>$master_data->account_number,
                                    'full_name'         =>$master_data->full_name,
                                    'ifsc_code'         =>$master_data->ifsc_code,
                                    'is_verified'       =>'1',
                                    'is_account_exist' =>'1',
                                    'price'             =>$price,
                                    'used_by'           =>'coc',
                                    'user_id'            =>$request->login_id,
                                    'platform_reference'    =>'api',
                                    'created_at'        =>date('Y-m-d H:i:s')
                                    ];
                
                                DB::table('bank_account_checks')->insert($log_data);
                            }
                            DB::commit();
                            $array_result=[
                                'account_number'=>$master_data->account_number,
                                'account_validity'=>'valid',
                                'verification_check'=>'completed',
                                'result' => [
                                            'name' => $master_data->full_name,
                                            'account_number'=>$master_data->account_number,
                                            'ifsc_code' => $master_data->ifsc_code,
                                            ]
                            ];
        
                            $response=['status'=>true,
                                        'data'=>$array_result,
                                        'initiated_date'=>date('d-m-Y'),
                                        'completed_date'=>date('d-m-Y'),
                                        'message' => 'Verification Done Successfully !!'
                                    ];

                        }else{
                            $response=['status'=>false,
                                        'data'=>NULL,
                                        'message' => 'It Seems like ID Number is Invalid !'
                                    ];
                        }
                        
                    }

                }
                else
                {
                    $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'Permission Denied !!'
                        ];   
                }
            }
            else
            {
                $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'User Not Found'
                        ];
            }

            return response()->json($response,200);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
    }

    /**
     * ID Check Telecom API
     *
     * This API is used for show the Telecom details or Sends an OTP based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.
     * 
     * @authenticated
     * 
     * @bodyParam  login_id integer required Login ID for finding the user. Example: 94
     * 
     * @bodyParam  mobile_number integer required Mobile Number to run check on (digits:10). Example: 9876543216
     * 
     * @response 404 {"status":false,"data":null,"message":"User Not Found!!"}
     * 
     * @responseFile responses/verification/telecom/success.json
     * 
     * @responseFile responses/verification/telecom/send_otp.json
     * 
     */
    public function idTelecomCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_id'  => 'required',

        ]);

        
        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }
        DB::beginTransaction();
        try{
            $users=DB::table('users')->where(['id'=>$request->login_id])->whereNotIn('user_type',['candidate','guest'])->first();
            if($users!=NULL)
            {
                $business_id=$users->business_id;
                $parent_id=$users->parent_id;
                $user_d=DB::table('users')->where('id',$business_id)->first();

                if($users->user_type=='user' || $users->user_type=='User')
                {
                    $parent_id=$user_d->parent_id;
                }

                $rules=[
                    'mobile_number'  => 'required|regex:/^(?=.*[0-9])[0-9]{10}$/',
                ];
                $custommessages=[
                    'mobile_number.regex' => 'Please enter a valid mobile number !',
                ];
                $validator = Validator::make($request->all(), $rules,$custommessages);
                    
                
                if ($validator->fails()) {            
                    return response()->json(['status' => 'error',
                                            'message'=>'The given data was invalid.',
                                            'errors'=> $validator->errors()], 200);
                }
                if($user_d->user_type=='customer')
                {
                    $price=20.00;
                    $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>19])->first();
                    
                    //check first into master table
                    $master_data = DB::table('telecom_check_master')->select('*')->where(['mobile_no'=>$request->mobile_number])->first();
                    
                    if($master_data !=null){
                
                        // store log
                        $check_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       =>$business_id,
                            'service_id'        =>19,
                            'operator'      => $master_data->operator,
                            'billing_type' => $master_data->billing_type,
                            'full_name' => $master_data->full_name,
                            'dob' => $master_data->dob,
                            'alternative_phone' =>$master_data->alternative_phone,
                            'address' => $master_data->address,
                            'city' => $master_data->city,
                            'state' => $master_data->state,
                            'pin_code' => $master_data->pin_code,
                            'email' => $master_data->email,
                            'mobile_no'     => $master_data->mobile_no,
                            'is_verified'       =>'1',
                            'is_mobile_exist'   =>'1',
                            'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                            'used_by'           =>'customer',
                            'user_id'           =>$request->login_id,
                            'source_type'  =>    'SystemDB',
                            'platform_reference'    => 'api',
                            'created_at'        =>date('Y-m-d H:i:s'),
                            'updated_at'        =>date('Y-m-d H:i:s')
                        ]; 

                        DB::table('telecom_check')->insert($check_data);
                        DB::commit();
                        $array_result=[
                            'mobile_number'=>$master_data->mobile_no,
                            'mobile_validity'=>'valid',
                            'verification_check'=>'completed',
                            'result' => [
                                            'name' =>   $master_data->full_name,
                                            'dob' => $master_data->dob,
                                            'address' => $master_data->address,
                                            'mobile' => $master_data->mobile_no,
                                            'alternative' => $master_data->alternative_phone==NULL?'N/A':$master_data->alternative_phone,
                                            'operator' => $master_data->operator,
                                            'billing_type' => $master_data->billing_type,
                                            'email' => $master_data->email==NULL?'N/A':$master_data->email,
                                            'city' => $master_data->city,
                                            'state' => $master_data->state,
                                            'pin_code' => $master_data->pin_code,
                                        ]
                        ];

                        $response=['status'=>true,
                                    'db' => true,
                                    'data'=>$array_result,
                                    'initiated_date'=>date('d-m-Y'),
                                    'completed_date'=>date('d-m-Y'),
                                    'message' => 'Verification Done Successfully !!'
                        ];
                        
                    }
                    else
                    {
                            //check from live API
                            $api_check_status = false;
                            // Setup request to send json via POST
                            $data = array(
                                'id_number'    => $request->mobile_number,
                            );
                            $payload = json_encode($data);
                            $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/telecom/generate-otp";

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
                            if(!$array_data['success'])
                            {
                                $response=[
                                        'status' => false,
                                        'data'=>NULL,
                                        'message' => 'It Seems like ID Number is Invalid !'
                                    ];
                            }
                            $master_data ="";
                            //check if ID number is new then insert into DB
                            $checkIDInDB= DB::table('telecom_check_master')->where(['mobile_no'=>$request->mobile_number])->count();
                            if($checkIDInDB==0)
                            {
                            

                                $data = [
                                    'client_id'        =>$array_data['data']['client_id'],
                                    'otp_sent'     => $array_data['data']['otp_sent'],
                                    'operator' => $array_data['data']['operator'],
                                    'if_number' => $array_data['data']['if_number'],
                                    'business_id' => $business_id,
                                    'mobile_no'   =>$request->mobile_number,
                                    'price'        => $checkprice_db!=NULL?$checkprice_db->price:$price,
                                    'platform_reference' => 'api',
                                    'created_by'        => $request->login_id,
                                    'status'            => 1,
                                    'created_at'       => date('Y-m-d H:i:s'),
                                    'updated_at'       => date('Y-m-d H:i:s')
                                    ];
                                    
                                    DB::table('advance_telecom_otps')->insert($data);
                            }
                            DB::commit();
                            $array_result=[
                                'mobile_number'=>$request->mobile_number,
                                'mobile_validity'=>'valid',
                                'verification_check'=>'pending',
                                'result' => [
                                                'client_id'  =>$array_data['data']['client_id'],
                                                'otp_sent'   => $array_data['data']['otp_sent'],
                                                'operator' => $array_data['data']['operator'],
                                                'if_number' => $array_data['data']['if_number'],
                                            ]
                            ];
        
                            $response=['status'=>true,
                                        'db' => false,
                                        'data'=>$array_result,
                                        'message' => 'SMS Sent to your mobile Number !'
                            ];
                            
                    }
                }
                else if($user_d->user_type=='client')
                {
                    $price=20;

                    $data = DB::table('check_price_cocs')->where(['service_id'=>19,'coc_id'=>$business_id])->first();
                    if($data!=NULL)
                    {
                        $price=$data->price;
                    }
                    else{
                        $data=DB::table('check_prices')->where(['service_id'=>19,'business_id'=>$parent_id])->first();
                        if($data!=NULL)
                        {
                            $price=$data->price;
                        }
                        else{
                            $data=DB::table('check_price_masters')->where(['service_id'=>19])->first();
                            if($data!=NULL)
                            {
                                $price=$data->price;
                            }
                        }
                    }

                    //check first into master table
                    $master_data = DB::table('telecom_check_master')->select('*')->where(['mobile_no'=>$request->mobile_number])->first();
                    
                    if($master_data !=null){
                
                        // store log
                        $check_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       =>$business_id,
                            'service_id'        =>19,
                            'operator'      => $master_data->operator,
                            'billing_type'      => $master_data->billing_type,
                            'full_name' => $master_data->full_name,
                            'dob' => $master_data->dob,
                            'alternative_phone' =>$master_data->alternative_phone,
                            'address' => $master_data->address,
                            'city' => $master_data->city,
                            'state' => $master_data->state,
                            'pin_code' => $master_data->pin_code,
                            'email' => $master_data->email,
                            'mobile_no'     => $master_data->mobile_no,
                            'is_verified'       =>'1',
                            'is_mobile_exist'   =>'1',
                            'price'             =>$price,
                            'used_by'           =>'coc',
                            'user_id'           =>$request->login_id,
                            'source_type'  =>    'SystemDB',
                            'platform_reference'    => 'api',
                            'created_at'        =>date('Y-m-d H:i:s'),
                            'updated_at'        =>date('Y-m-d H:i:s')
                        ]; 

                        DB::table('telecom_check')->insert($check_data);
                        DB::commit();

                        $array_result=[
                            'mobile_number'=>$master_data->mobile_no,
                            'mobile_validity'=>'valid',
                            'verification_check'=>'completed',
                            'result' => [
                                            'name' =>   $master_data->full_name,
                                            'dob' => $master_data->dob,
                                            'address' => $master_data->address,
                                            'mobile' => $master_data->mobile_no,
                                            'alternative' => $master_data->alternative_phone==NULL?'N/A':$master_data->alternative_phone,
                                            'operator' => $master_data->operator,
                                            'billing_type' => $master_data->billing_type,
                                            'email' => $master_data->email==NULL?'N/A':$master_data->email,
                                            'city' => $master_data->city,
                                            'state' => $master_data->state,
                                            'pin_code' => $master_data->pin_code,
                                        ]
                        ];

                        $response=['status'=>true,
                                    'db' => true,
                                    'data'=>$array_result,
                                    'initiated_date'=>date('d-m-Y'),
                                    'completed_date'=>date('d-m-Y'),
                                    'message' => 'Verification Done Successfully !!'
                        ];
                        
                    }
                    else
                    {
                            //check from live API
                            $api_check_status = false;
                            // Setup request to send json via POST
                            $data = array(
                                'id_number'    => $request->mobile_number,
                            );
                            $payload = json_encode($data);
                            $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/telecom/generate-otp";

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
                            if(!$array_data['success'])
                            {
                                $response=[
                                        'status' => false,
                                        'data'=>NULL,
                                        'message' => 'It Seems like ID Number is Invalid !'
                                    ];
                            }
                            $master_data ="";
                            //check if ID number is new then insert into DB
                            $checkIDInDB= DB::table('telecom_check_master')->where(['mobile_no'=>$request->mobile_number])->count();
                            if($checkIDInDB==0)
                            {
                            

                                $data = [
                                    'client_id'        =>$array_data['data']['client_id'],
                                    'otp_sent'     => $array_data['data']['otp_sent'],
                                    'operator' => $array_data['data']['operator'],
                                    'if_number' => $array_data['data']['if_number'],
                                    'business_id' => $business_id,
                                    'mobile_no'   =>$request->mobile_number,
                                    'price'        => $price,
                                    'platform_reference' => 'api',
                                    'created_by'        => $request->login_id,
                                    'status'            => 1,
                                    'created_at'       => date('Y-m-d H:i:s'),
                                    'updated_at'       => date('Y-m-d H:i:s')
                                    ];
                                    
                                    DB::table('advance_telecom_otps')->insert($data);
                            }
                            DB::commit();
                            $array_result=[
                                'mobile_number'=>$request->mobile_number,
                                'mobile_validity'=>'valid',
                                'verification_check'=>'pending',
                                'result' => [
                                                'client_id'  =>$array_data['data']['client_id'],
                                                'otp_sent'   => $array_data['data']['otp_sent'],
                                                'operator' => $array_data['data']['operator'],
                                                'if_number' => $array_data['data']['if_number'],
                                            ]
                            ];
        
                            $response=['status'=>true,
                                        'db' => false,
                                        'data'=>$array_result,
                                        'message' => 'SMS Sent to your mobile Number !'
                            ];
                            
                    }

                }
                else
                {
                    $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'Permission Denied !!'
                        ];   
                }
            }
            else
            {
                $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'User Not Found !!'
                        ];   
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }

        return response()->json($response,200);
    }

    /**
     * Verify Check Telecom API
     *
     * This API is used for show the Telecom details based on who logs in (i.e; if the user belongs to an Admin/COC) and Verification details send by ID Check Telecom API.
     * 
     * @authenticated
     * 
     * @bodyParam  login_id integer required Login ID for finding the user. Example: 94
     * 
     * @bodyParam  mobile_number integer required Mobile Number to run check on (digits:10). Example: 9876543216
     * 
     * @bodyParam  client_id string required Client ID to run check on (alpha-numeric). Example: "telecom_CaoEgfyNCELFgdiulUco"
     * 
     * @bodyParam  sms_otp integer required SMS OTP to run check on (min:4,max:6). Example: 8754
     * 
     * @response 404 {"status":false,"data":null,"message":"User Not Found!!"}
     * 
     * @response 406 {"status":false,"data":null,"mobile_number":"Please enter a valid mobile number !"}
     * 
     * @responseFile responses/verification/telecom/success.json
     * 
     */
    public function idVerifyTelcomCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_id'  => 'required',

        ]);

        
        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }
        DB::beginTransaction();
        try{
            $users=DB::table('users')->where(['id'=>$request->login_id])->whereNotIn('user_type',['candidate','guest'])->first();
            if($users!=NULL)
            {
                $business_id=$users->business_id;
                $parent_id=$users->parent_id;
                $user_d=DB::table('users')->where('id',$business_id)->first();

                if($users->user_type=='user' || $users->user_type=='User')
                {
                    $parent_id=$user_d->parent_id;
                }

                $rules = [
                    'mobile_number' => 'required|regex:/^(?=.*[0-9])[0-9]{10}$/',
                    'client_id' => 'required',
                    'sms_otp'  => 'required|numeric|min:4',   
                ];
                $validator = Validator::make($request->all(), $rules);
                    
                
                if ($validator->fails()) {            
                    return response()->json(['status' => 'error',
                                            'message'=>'The given data was invalid.',
                                            'errors'=> $validator->errors()], 200);
                }
                $count=1;
                if($user_d->user_type=='customer')
                {
                    $price=20.00;
                    $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>19])->first();
                    
                    $advance_otp=DB::table('advance_telecom_otps')->where(['business_id'=>$business_id,'mobile_no' => $request->mobile_number,'status'=>1])->get();
                    
                    if(count($advance_otp)>0)
                    {
                        $count=count($advance_otp);
                    }
                    else
                    {
                        return response()->json(['status' => 'error',
                                            'message'=>'The given data was invalid.',
                                            'errors'=> ['mobile_number' => 'Please enter a valid mobile number !']], 200);
                    }


                    $otp_db=DB::table('advance_telecom_otps')->where(['client_id' => $request->client_id])->first();

                    if($otp_db==NULL)
                    {
                        return response()->json(['status' => 'error',
                                            'message'=>'The given data was invalid.',
                                            'errors'=> ['client_id' => 'Client id not match !!']], 200);
                    }

                    $data = array(
                        'otp'    =>$request->sms_otp,
                        'client_id'=> $request->client_id,
                    
                    );
                    $payload = json_encode($data);
                    $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/telecom/submit-otp";

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
                    
                    $array_data= json_decode($resp,true);

                    // dd($array_data);

                    if($array_data['success']==false)
                    {
                        if(array_key_exists('status_code',$array_data))
                        {
                            if($array_data['status_code']==422)
                            {
                                $response=[
                                    'status' => false,
                                    'data'=>NULL,
                                    'message' => 'It seems like OTP Timeout! Try again !'
                                ];
                            }
                            else
                            {
                                $response=[
                                    'status' => false,
                                    'data'=>NULL,
                                    'message' => 'It seems like OTP is invalid! Try again !'
                                ];
                            }
                        }
                        else
                        {
                            $response=[
                                'status' => false,
                                'data'=>NULL,
                                'message' => 'It seems like OTP is invalid! Try again !'
                            ];
                        }
                        
                        
                    }
                    else
                    {
                        $data = [
                            'client_id'  =>$array_data['data']['client_id'],
                            'business_id' => $business_id,
                            'operator' => $array_data['data']['operator'],
                            'billing_type' => $array_data['data']['billing_type'],
                            'full_name' => $array_data['data']['full_name'],
                            'dob' => $array_data['data']['dob'],
                            'mobile_no'   => $array_data['data']['mobile_number'],
                            'alternative_phone' =>$array_data['data']['alternate_phone'],
                            'address' => $array_data['data']['address'],
                            'city' => $array_data['data']['city'],
                            'state' => $array_data['data']['state'],
                            'pin_code' => $array_data['data']['pin_code'],
                            'email' => $array_data['data']['user_email'],
                            'is_verified' => '1',
                            'created_at'       => date('Y-m-d H:i:s'),
                            'updated_at'       => date('Y-m-d H:i:s')
                        ];
            
                
                            $insert_id=DB::table('telecom_check_master')->insertGetId($data);

                            $master_data=DB::table('telecom_check_master')->where(['id'=>$insert_id])->first();

                            if($count>1)
                            {
                                for($i=0;$i<$count;$i++)
                                {
                                    // store log
                                    $check_data = [
                                        'parent_id'         =>$parent_id,
                                        'business_id'       =>$business_id,
                                        'service_id'        =>19,
                                        'operator' => $master_data->operator,
                                        'billing_type' => $master_data->billing_type,
                                        'full_name' => $master_data->full_name,
                                        'dob' => $master_data->dob,
                                        'alternative_phone' =>$master_data->alternative_phone,
                                        'address' => $master_data->address,
                                        'city' => $master_data->city,
                                        'state' => $master_data->state,
                                        'pin_code' => $master_data->pin_code,
                                        'email' => $master_data->email,
                                        'mobile_no'     => $request->mobile_number,
                                        'is_verified'       =>'1',
                                        'is_mobile_exist'   =>'1',
                                        'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                                        'used_by'           =>'customer',
                                        'user_id'           => $request->login_id,
                                        'source_type'       =>   'API',
                                        'platform_reference' => 'api',
                                        'created_at'        =>date('Y-m-d H:i:s'),
                                        'updated_at'        =>date('Y-m-d H:i:s')
                                    ]; 

                                    DB::table('telecom_check')->insert($check_data);
                                }
                            }
                            else
                            {
                                // store log
                                $check_data = [
                                    'parent_id'         =>$parent_id,
                                    'business_id'       =>$business_id,
                                    'service_id'        =>19,
                                    'operator' => $master_data->operator,
                                    'billing_type' => $master_data->billing_type,
                                    'full_name' => $master_data->full_name,
                                    'dob' => $master_data->dob,
                                    'alternative_phone' =>$master_data->alternative_phone,
                                    'address' => $master_data->address,
                                    'city' => $master_data->city,
                                    'state' => $master_data->state,
                                    'pin_code' => $master_data->pin_code,
                                    'email' => $master_data->email,
                                    'mobile_no'     => $request->mobile_number,
                                    'is_verified'       =>'1',
                                    'is_mobile_exist'   =>'1',
                                    'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                                    'used_by'           =>'customer',
                                    'user_id'           => $request->login_id,
                                    'source_type'       =>   'API',
                                    'platform_reference' => 'api',
                                    'created_at'        =>date('Y-m-d H:i:s'),
                                    'updated_at'        =>date('Y-m-d H:i:s')
                                ]; 

                                DB::table('telecom_check')->insert($check_data);
                            }
                            

                            DB::table('advance_telecom_otps')->where(['business_id'=>$business_id,'mobile_no' => $request->mobile_number])->update([
                                'status' => 0
                            ]);

                            DB::commit();
                            $array_result=[
                                'mobile_number'=>$array_data['data']['mobile_number'],
                                'mobile_validity'=>'valid',
                                'verification_check'=>'completed',
                                'result' => [
                                                'name' =>   $array_data['data']['full_name'],
                                                'dob' => $array_data['data']['dob'],
                                                'address' => $array_data['data']['address'],
                                                'mobile' => $array_data['data']['mobile_number'],
                                                'alternative' => $array_data['data']['alternate_phone']==NULL?'N/A':$array_data['data']['alternate_phone'],
                                                'operator' => $array_data['data']['operator'],
                                                'billing_type' => $array_data['data']['billing_type'],
                                                'email' => $array_data['data']['user_email']==NULL?'N/A':$array_data['data']['user_email'],
                                                'city' => $array_data['data']['city'],
                                                'state' => $array_data['data']['state'],
                                                'pin_code' => $array_data['data']['pin_code'],
                                            ]
                            ];

                            $response=['status'=>true,
                                        'db' => true,
                                        'data'=>$array_result,
                                        'initiated_date'=>date('d-m-Y'),
                                        'completed_date'=>date('d-m-Y'),
                                        'message' => 'Verification Done Successfully !!'
                            ];
                    }
                    
                }
                else if($user_d->user_type=='client')
                {
                    $price=20.00;

                    $data = DB::table('check_price_cocs')->where(['service_id'=>19,'coc_id'=>$business_id])->first();
                    if($data!=NULL)
                    {
                        $price=$data->price;
                    }
                    else{
                        $data=DB::table('check_prices')->where(['service_id'=>19,'business_id'=>$parent_id])->first();
                        if($data!=NULL)
                        {
                            $price=$data->price;
                        }
                        else{
                            $data=DB::table('check_price_masters')->where(['service_id'=>19])->first();
                            if($data!=NULL)
                            {
                                $price=$data->price;
                            }
                        }
                    }

                    $advance_otp=DB::table('advance_telecom_otps')->where(['business_id'=>$business_id,'mobile_no' => $request->mobile_number,'status'=>1])->get();

                    if(count($advance_otp)>0)
                    {
                        $count=count($advance_otp);
                    }
                    else
                    {
                        return response()->json(['status' => 'error',
                                            'message'=>'The given data was invalid.',
                                            'errors'=> ['mobile_number' => 'Please enter a valid mobile number !']], 200);
                    }


                    $otp_db=DB::table('advance_telecom_otps')->where(['client_id' => $request->client_id])->first();

                    if($otp_db==NULL)
                    {
                        return response()->json(['status' => 'error',
                                            'message'=>'The given data was invalid.',
                                            'errors'=> ['client_id' => 'Client id not match !!']], 200);
                    }

                    $data = array(
                        'otp'    =>$request->sms_otp,
                        'client_id'=> $request->client_id,
                    
                    );
                    $payload = json_encode($data);
                    $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/telecom/submit-otp";

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
                    
                    $array_data= json_decode($resp,true);

                    if($array_data['success']==false)
                    {
                        if(array_key_exists('status_code',$array_data))
                        {
                            if($array_data['status_code']==422)
                            {
                                $response=[
                                    'status' => false,
                                    'data'=>NULL,
                                    'message' => 'It seems like OTP Timeout! Try again !'
                                ];
                            }
                            else
                            {
                                $response=[
                                    'status' => false,
                                    'data'=>NULL,
                                    'message' => 'It seems like OTP is invalid! Try again !'
                                ];
                            }
                        }
                        else
                        {
                            $response=[
                                'status' => false,
                                'data'=>NULL,
                                'message' => 'It seems like OTP is invalid! Try again !'
                            ];
                        }
                    }
                    else
                    {
                        $data = [
                            'client_id'  =>$array_data['data']['client_id'],
                            'business_id' => $business_id,
                            'operator' => $array_data['data']['operator'],
                            'billing_type' => $array_data['data']['billing_type'],
                            'full_name' => $array_data['data']['full_name'],
                            'dob' => $array_data['data']['dob'],
                            'mobile_no'   => $array_data['data']['mobile_number'],
                            'alternative_phone' =>$array_data['data']['alternate_phone'],
                            'address' => $array_data['data']['address'],
                            'city' => $array_data['data']['city'],
                            'state' => $array_data['data']['state'],
                            'pin_code' => $array_data['data']['pin_code'],
                            'email' => $array_data['data']['user_email'],
                            'is_verified' => '1',
                            'created_at'       => date('Y-m-d H:i:s'),
                            'updated_at'       => date('Y-m-d H:i:s')
                        ];
            
                
                        $insert_id=DB::table('telecom_check_master')->insertGetId($data);

                        $master_data=DB::table('telecom_check_master')->where(['id'=>$insert_id])->first();

                        if($count>1)
                        {
                            for($i=0;$i<$count;$i++)
                            {
                                // store log
                                $check_data = [
                                    'parent_id'         =>$parent_id,
                                    'business_id'       =>$business_id,
                                    'service_id'        =>19,
                                    'operator' => $master_data->operator,
                                    'billing_type' => $master_data->billing_type,
                                    'full_name' => $master_data->full_name,
                                    'dob' => $master_data->dob,
                                    'alternative_phone' =>$master_data->alternative_phone,
                                    'address' => $master_data->address,
                                    'city' => $master_data->city,
                                    'state' => $master_data->state,
                                    'pin_code' => $master_data->pin_code,
                                    'email' => $master_data->email,
                                    'mobile_no'     => $request->mobile_number,
                                    'is_verified'       =>'1',
                                    'is_mobile_exist'   =>'1',
                                    'price'             =>$price,
                                    'used_by'           =>'coc',
                                    'user_id'           => $request->login_id,
                                    'source_type'       =>   'API',
                                    'platform_reference' => 'api',
                                    'created_at'        =>date('Y-m-d H:i:s'),
                                    'updated_at'        =>date('Y-m-d H:i:s')
                                ]; 

                                DB::table('telecom_check')->insert($check_data);
                            }
                        }
                        else
                        {
                            // store log
                            $check_data = [
                                'parent_id'         =>$parent_id,
                                'business_id'       =>$business_id,
                                'service_id'        =>19,
                                'operator' => $master_data->operator,
                                'billing_type' => $master_data->billing_type,
                                'full_name' => $master_data->full_name,
                                'dob' => $master_data->dob,
                                'alternative_phone' =>$master_data->alternative_phone,
                                'address' => $master_data->address,
                                'city' => $master_data->city,
                                'state' => $master_data->state,
                                'pin_code' => $master_data->pin_code,
                                'email' => $master_data->email,
                                'mobile_no'     => $request->mobile_number,
                                'is_verified'       =>'1',
                                'is_mobile_exist'   =>'1',
                                'price'             =>$price,
                                'used_by'           =>'coc',
                                'user_id'           => $request->login_id,
                                'source_type'       =>   'API',
                                'platform_reference' => 'api',
                                'created_at'        =>date('Y-m-d H:i:s'),
                                'updated_at'        =>date('Y-m-d H:i:s')
                            ]; 

                            DB::table('telecom_check')->insert($check_data);
                        }

                        DB::table('advance_telecom_otps')->where(['business_id'=>$business_id,'mobile_no' => $request->mobile_number])->update([
                            'status' => 0
                        ]);
                        DB::commit();
                        $array_result=[
                            'mobile_number'=>$array_data['data']['mobile_number'],
                            'mobile_validity'=>'valid',
                            'verification_check'=>'completed',
                            'result' => [
                                            'name' =>   $array_data['data']['full_name'],
                                            'dob' => $array_data['data']['dob'],
                                            'address' => $array_data['data']['address'],
                                            'mobile' => $array_data['data']['mobile_number'],
                                            'alternative' => $array_data['data']['alternate_phone']==NULL?'N/A':$array_data['data']['alternate_phone'],
                                            'operator' => $array_data['data']['operator'],
                                            'billing_type' => $array_data['data']['billing_type'],
                                            'email' => $array_data['data']['user_email']==NULL?'N/A':$array_data['data']['user_email'],
                                            'city' => $array_data['data']['city'],
                                            'state' => $array_data['data']['state'],
                                            'pin_code' => $array_data['data']['pin_code'],
                                        ]
                        ];

                        $response=['status'=>true,
                                    'db' => true,
                                    'data'=>$array_result,
                                    'initiated_date'=>date('d-m-Y'),
                                    'completed_date'=>date('d-m-Y'),
                                    'message' => 'Verification Done Successfully !!'
                        ];
                    }

                }
                else
                {
                    $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'Permission Denied !!'
                        ];   
                }
            }
            else
            {
                $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'User Not Found !!'
                        ];   
            }

            return response()->json($response,200);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
    }

    /**
     * ID Check Covid 19 Generate OTP API
     *
     * This API is used for Send an OTP based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.
     * 
     * @authenticated
     * 
     * @bodyParam  login_id integer required Login ID for finding the user. Example: 94
     * 
     * @bodyParam  mobile_number integer required Mobile Number to run check on (digits:10). Example: 9876543216
     * 
     * @response 404 {"status":false,"data":null,"message":"User Not Found!!"}
     * 
     * @responseFile responses/verification/covid19/generateOTP/success.json
     * 
     * @response 400 {"status":false,"data":null,"message":"Invalid Mobile Number ! Try Again !!"}
     * 
     * @response 401 {"status":false,"data":null,"message":"Permission Denied !!"}
     * 
     * @response 401 {"status":false,"data":null,"message":"Enter a Valid Mobile Number ! Try Again !!"}
     * 
     * @response 412 {"status":false,"data":null,"message":"Something Went Wrong !!"}
     * 
     */
    public function idCovid19OTPCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_id'  => 'required',

        ]);

        
        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }

        DB::beginTransaction();
        try{
            $users=DB::table('users')->where(['id'=>$request->login_id])->whereNotIn('user_type',['candidate','guest'])->first();
            if($users!=NULL)
            {
                $business_id=$users->business_id;
                $parent_id=$users->parent_id;
                $user_d=DB::table('users')->where('id',$business_id)->first();

                if($users->user_type=='user' || $users->user_type=='User')
                {
                    $parent_id=$user_d->parent_id;
                }

                $rules=[
                    'mobile_number'  => 'required|regex:/^(?=.*[0-9])[0-9]{10}$/',
                ];
                $custommessages=[
                    'mobile_number.regex' => 'Please enter a valid mobile number !',
                ];
                $validator = Validator::make($request->all(), $rules,$custommessages);
                    
                
                if ($validator->fails()) {            
                    return response()->json(['status' => 'error',
                                            'message'=>'The given data was invalid.',
                                            'errors'=> $validator->errors()], 200);
                }

                if($user_d->user_type=='customer')
                {
                     //check from live API
                    $api_check_status = false;
                    $response_code=0;
                        // Setup request to send json via POST
                    $data = array(
                        'mobile'    => $request->mobile_number,
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

                    if($response_code==200)
                    {
                        $data = [
                            'txnId'        =>$array_data['txnId'],
                            'business_id' => $business_id,
                            'mobile_no'   =>$request->id_number,
                            'created_by'        => $request->login_id,
                            'status'            => 1,
                            'platform_reference' => 'api',
                            'created_at'       => date('Y-m-d H:i:s'),
                            ];
                            
                            $otp_id=DB::table('advance_covid19_otps')->insertGetId($data);
                            DB::commit();

                            $array_result=[
                                'mobile_number'=>$request->mobile_number,
                                'mobile_validity'=>'valid',
                                'verification_check'=>'pending',
                                'result' => [
                                                'otp_id'    => base64_encode($otp_id),
                                                'txnId'     => $array_data['txnId']
                                            ]
                            ];
        
                            $response=['status'=>true,
                                        'db' => false,
                                        'data'=>$array_result,
                                        'message' => 'SMS Sent to your mobile Number !'
                            ];

                    }
                    else
                    {
                        // dd($resp);
                        if($response_code==400)
                        {
                            $response=[
                                'status' => false,
                                'data'=>NULL,
                                'message' => $array_data!=NULL?$array_data['error'] : 'Please Try Again After Some Time !!',
                            ];
                            
                        }
                        else if($response_code==401)
                        {
                            $response=[
                                'status' => false,
                                'data'=>NULL,
                                'message' => 'Enter a Valid Mobile Number ! Try Again !!'
                            ];
                            
                        }
                        else
                        {
                            $response=[
                                'status' => false,
                                'data'=>NULL,
                                'message' => 'Something Went Wrong !!'
                            ];

                        }
                    }

                }
                else if($user_d->user_type=='client')
                {
                      //check from live API
                      $api_check_status = false;
                      $response_code=0;
                          // Setup request to send json via POST
                      $data = array(
                          'mobile'    => $request->mobile_number,
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
  
                      if($response_code==200)
                      {
                          $data = [
                              'txnId'        =>$array_data['txnId'],
                              'business_id' => $business_id,
                              'mobile_no'   =>$request->id_number,
                              'created_by'        => $request->login_id,
                              'status'            => 1,
                              'platform_reference' => 'api',
                              'created_at'       => date('Y-m-d H:i:s'),
                              ];
                              
                              $otp_id=DB::table('advance_covid19_otps')->insertGetId($data);
                              DB::commit();
  
                              $array_result=[
                                  'mobile_number'=>$request->mobile_number,
                                  'mobile_validity'=>'valid',
                                  'verification_check'=>'pending',
                                  'result' => [
                                                  'otp_id'    => base64_encode($otp_id),
                                                  'txnId'     => $array_data['txnId']
                                              ]
                              ];
          
                              $response=['status'=>true,
                                          'db' => false,
                                          'data'=>$array_result,
                                          'message' => 'SMS Sent to your mobile Number !'
                              ];
  
                      }
                      else
                      {
                          // dd($resp);
                          if($response_code==400)
                          {
                              $response=[
                                  'status' => false,
                                  'data'=>NULL,
                                  'message' => $array_data!=NULL?$array_data['error'] : 'Invalid Mobile Number ! Try Again !!',
                              ];
                              
                          }
                          else if($response_code==401)
                          {
                              $response=[
                                  'status' => false,
                                  'data'=>NULL,
                                  'message' => 'Enter a Valid Mobile Number ! Try Again !!'
                              ];
                              
                          }
                          else
                          {
                              $response=[
                                  'status' => false,
                                  'data'=>NULL,
                                  'message' => 'Something Went Wrong !!'
                              ];
  
                          }
                      }
                }
                else
                {
                    $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'Permission Denied !!'
                        ];   
                }

            }
            else
            {
                $response=[
                    'status'=>false,
                    'data'=>NULL,
                    'message' => 'User Not Found !!'
                ];
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }

        return response()->json($response,200);

    }

     /**
     * ID Check Covid 19 Verify OTP API
     *
     * This API is used for Verify the Mobile Number based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.
     * 
     * @authenticated
     * 
     * @bodyParam  login_id integer required Login ID for finding the user. Example: 94
     * 
     * @bodyParam  txnId string required txn ID to run check on (min:1). Example: "975567a0-558e-453f-80bd-3dacffd16d58"
     * 
     * @bodyParam  otp_id string required OTP ID to run check on (min:1). Example: "OQ=="
     * 
     * @bodyParam  otp integer required SMS OTP to run check on (min:4,max:6). Example: 875485
     * 
     * @response 404 {"status":false,"data":null,"message":"User Not Found!!"}
     * 
     * @responseFile responses/verification/covid19/verifyOTP/success.json
     * 
     * @response 400 {"status":false,"data":null,"message":"Invalid OTP ! Try Again !!"}
     * 
     * @response 401 {"status":false,"data":null,"message":"Permission Denied !!"}
     * 
     * @response 401 {"status":false,"data":null,"message":"Enter a Valid OTP ! Try Again !!"}
     * 
     * @response 412 {"status":false,"data":null,"message":"Something Went Wrong !!"}
     * 
     */
    public function idCovid19VerifyOTPCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_id'  => 'required',

        ]);

        
        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }

        DB::beginTransaction();
        try{
        
            $users=DB::table('users')->where(['id'=>$request->login_id])->whereNotIn('user_type',['candidate','guest'])->first();
            if($users!=NULL)
            {
                $business_id=$users->business_id;
                $parent_id=$users->parent_id;
                $user_d=DB::table('users')->where('id',$business_id)->first();

                if($users->user_type=='user' || $users->user_type=='User')
                {
                    $parent_id=$user_d->parent_id;
                }

                $rules = [
                    'txnId' => 'required|regex:/^[a-zA-Z0-9-]+$/u|min:1',
                    'otp_id' => 'required|string|min:1',
                    'otp'  => 'required|integer|min:4',   
                ];
        
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails())
                    return response()->json(['status' => 'error',
                                         'message'=>'The given data was invalid.',
                                         'errors'=> $validator->errors()], 200);
                
                if($user_d->user_type=='customer')
                {
                    $otp_id = base64_decode($request->otp_id);
                    $txnId=$request->txnId;
                    //check from live API
                    $api_check_status = false;
                    $response_code = 0;
                    $master_data = DB::table('advance_covid19_otps')->where(['id'=>$otp_id])->first();
                    // dd($master_data);

                    $data = array(
                        'otp'    => hash('sha256',$request->otp),
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
                            'updated_by'    => $request->login_id,
                            'updated_at'    => date('Y-m-d H:i:s'),
                            ]
                        );
    
                        DB::commit();
                        $array_result=[
                            'mobile_number'=>$master_data->mobile_no,
                            'mobile_validity'=>'valid',
                            'verification_check'=>'pending',
                            'result' => [
                                            'token' => $array_data['token'],
                                        ]
                        ];
    
                        $response=['status'=>true,
                                    'db' => false,
                                    'data'=>$array_result,
                                    'message' => 'Mobile Number Verified !'
                        ];
                    }
                    else
                    {
                        if($response_code==400)
                        {
                            $response=[
                                'status' => false,
                                'data'=>NULL,
                                'message' => $array_data!=NULL?$array_data['error'] : 'Invalid OTP !!'
                            ];
                            
                        }
                        else if($response_code==401)
                        {
                            $response=[
                                'status' => false,
                                'data'=>NULL,
                                'message' => 'OTP Session Timeout ! Try Again !!'
                            ];
                            
                        }
                        else
                        {
                            $response=[
                                'status' => false,
                                'data'=>NULL,
                                'message' => 'Something Went Wrong !!'
                            ];

                        }
                    }  


                }
                else if($user_d->user_type=='client')
                {
                    $otp_id = base64_decode($request->otp_id);
                    $txnId=$request->txnId;
                    //check from live API
                    $api_check_status = false;
                    $response_code = 0;
                    $master_data = DB::table('advance_covid19_otps')->where(['id'=>$otp_id])->first();
                    // dd($master_data);

                    $data = array(
                        'otp'    => hash('sha256',$request->otp),
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
                            'updated_by'    => $request->login_id,
                            'updated_at'    => date('Y-m-d H:i:s'),
                            ]
                        );
    
                        DB::commit();
                        $array_result=[
                            'mobile_number'=>$master_data->mobile_no,
                            'mobile_validity'=>'valid',
                            'verification_check'=>'pending',
                            'result' => [
                                            'token' => $array_data['token'],
                                        ]
                        ];
    
                        $response=['status'=>true,
                                    'db' => false,
                                    'data'=>$array_result,
                                    'message' => 'Mobile Number Verified !'
                        ];
                    }
                    else
                    {
                        if($response_code==400)
                        {
                            $response=[
                                'status' => false,
                                'data'=>NULL,
                                'message' => $array_data!=NULL?$array_data['error'] : 'Invalid OTP !!'
                            ];
                            
                        }
                        else if($response_code==401)
                        {
                            $response=[
                                'status' => false,
                                'data'=>NULL,
                                'message' => 'OTP Session Timeout ! Try Again !!'
                            ];
                            
                        }
                        else
                        {
                            $response=[
                                'status' => false,
                                'data'=>NULL,
                                'message' => 'Something Went Wrong !!'
                            ];

                        }
                    }  
                }
                else
                {
                    $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'Permission Denied !!'
                        ];   
                }
            }
            else
            {
                $response=[
                    'status'=>false,
                    'data'=>NULL,
                    'message' => 'User Not Found !!'
                ];
            }

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }

        return response()->json($response,200);

    }

    /**
     * ID Check Covid 19 Get Certificate
     *
     * This API is used for Get the Certificate for whom is vaccinated based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.
     * 
     * @authenticated
     * 
     * @bodyParam  login_id integer required Login ID for finding the user. Example: 94
     * 
     * @bodyParam  token string required token to run check on (min:1). Example: "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiI0NDAyMTUzOS1iYWFiLTQ3YzQtOGZlYi1jZmNmMzQ2MDE5YjciLCJ1c2VyX3R5cGUiOiJCRU5FRklDSUFSWSIsInVzZXJfaWQiOiI0NDAyMTUzOS1iYWFiLTQ3YzQtOGZlYi1jZmNmMzQ2MDE5YjciLCJtb2JpbGVfbnVtYmVyIjo4NzAwMDM1NDI2LCJiZW5lZmljaWFyeV9yZWZlcmVuY2VfaWQiOjE0MzcxODk2NDEzOTMsInR4bklkIjoiOGQ3Y2Q1M2UtZWEwOC00ZGJiLWI0YTktODU5Mzg5Yjk4ZTAxIiwiaWF0IjoxNjI4NTc2NTQ0LCJleHAiOjE2Mjg1Nzc0NDR9.Qtc0O1pWVADR5Q5ezLynddiPKcK9SH3mPmPZymZtlEY"
     * 
     * @bodyParam  reference_id integer required Reference ID to run check on, which is linked to mobile number you have entered at the time of Generate OTP API, To Get this Id you have to visit the cowin site (https://selfregistration.cowin.gov.in/) & Get Logged In (min:1). Example: 53965833337440
     * 
     * @response 404 {"status":false,"data":null,"message":"User Not Found!!"}
     * 
     * @response 404 {"status":false,"data":null,"message":"The Given Data is Invalid!!"}
     * 
     * @responseFile responses/verification/covid19/reference/success.json
     * 
     * @response 400 {"status":false,"data":null,"message":"Data Not Found !!"}
     * 
     * @response 401 {"status":false,"data":null,"message":"Permission Denied !!"}
     * 
     * @response 401 {"status":false,"data":null,"message":"Timeout ! Try Again Later!!"}
     * 
     * @response 412 {"status":false,"data":null,"message":"Something Went Wrong !!"}
     * 
     */
    public function idVerifyCovid19RefCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_id'  => 'required',

        ]);

        
        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }

        DB::beginTransaction();
        try{
            $users=DB::table('users')->where(['id'=>$request->login_id])->whereNotIn('user_type',['candidate','guest'])->first();
            if($users!=NULL)
            {
                $business_id=$users->business_id;
                $parent_id=$users->parent_id;
                $user_d=DB::table('users')->where('id',$business_id)->first();

                if($users->user_type=='user' || $users->user_type=='User')
                {
                    $parent_id=$user_d->parent_id;
                }

                $rules = [
                    'reference_id' => 'required|integer|min:1',
                    'token' => 'required|string|min:1',
                ];
        
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails())
                    return response()->json(['status' => 'error',
                                         'message'=>'The given data was invalid.',
                                         'errors'=> $validator->errors()], 200);
                                         
                    $reference_id=$request->reference_id;
                    $api_check_status = false;
                    $response_code = 0;
                    $advance_otp = DB::table('advance_covid19_otps')->where(['token'=>$request->token])->first();
                    $service=DB::table('services')->where(['type_name'=>'covid_19_certificate'])->first();
                    if($advance_otp==NULL)
                    {
                        return response()->json(['status' => false,
                                                'data' => NULL,
                                                'message'=>'The given data was invalid !!',
                                                ], 404);
                    }

                    $path='';
                    $file_name='';
                    $content=NULL;
                    $headers = [
                        'Content-Type' => 'application/pdf',
                     ];
                                        
                if($user_d->user_type=='customer')
                {
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
                            'service_id'    => $service->id,
                            'txnId' => $master_data->txnId,
                            'source_type'   => 'SystemDB',
                            'mobile_no' => $master_data->mobile_no,
                            'reference_id' => $reference_id,
                            'token' => $master_data->token,
                            'user_id'   => $request->login_id,
                            'used_by'   => 'customer',
                            'file_name' => $file_name,
                            'raw_data' => base64_encode($content),
                            'platform_reference' => 'api',
                            'created_at'   => date('Y-m-d H:i:s')
                        ]);

                        $URL= url('/').'/cowin/certificate/'.$file_name;
                        DB::commit();
                        $array_result=[
                            'mobile_number'=>$master_data->mobile_no,
                            'reference_id' => $reference_id,
                            'mobile_validity'=>'valid',
                            'verification_check'=>'completed',
                            'result' => [
                                            'url' => $URL,
                                        ]
                        ];
    
                        $response=['status'=>true,
                                    'db' => true,
                                    'data'=>$array_result,
                                    'message' => 'Verification Done !'
                        ];
                        // return response()->download($path.$file_name,$file_name,$headers);

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
                                'updated_by' => 94,
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
                                    'platform_reference' => 'api',
                                    'created_at'   => date('Y-m-d H:i:s')
                                ]);

                            DB::table('covid19_checks')->insert([
                                'parent_id' => $parent_id,
                                'business_id'  => $business_id,
                                'service_id'    => $service->id,
                                'txnId' => $advance_otp->txnId,
                                'source_type'   => 'API',
                                'mobile_no' => $advance_otp->mobile_no,
                                'reference_id' => $reference_id,
                                'token' => $advance_otp->token,
                                'user_id'   => $request->login_id,
                                'used_by'   => 'customer',
                                'file_name' => $file_name,
                                'raw_data'  => base64_encode($content),
                                'platform_reference' => 'api',
                                'created_at'   => date('Y-m-d H:i:s')
                            ]);

                            DB::commit();
                            $URL= url('/').'/cowin/certificate/'.$file_name;
                            $array_result=[
                                'mobile_number'=>$advance_otp->mobile_no,
                                'reference_id' => $reference_id,
                                'mobile_validity'=>'valid',
                                'verification_check'=>'completed',
                                'result' => [
                                                'url' => $URL,
                                            ]
                            ];
        
                            $response=['status'=>true,
                                        'db' => true,
                                        'data'=>$array_result,
                                        'message' => 'Verification Done !'
                            ];
                            // return response()->download($path.$file_name,$file_name,$headers);

                        }
                        else
                        {
                            if($response_code==400)
                            {
                                $response=['status'=>false,
                                            'data'=>NULL,
                                            'message' => 'Data Not Found !!'
                                          ];  
                               
                            }
                            else if($response_code==401)
                            {
                                $response=['status'=>false,
                                            'data'=>NULL,
                                            'message' => 'Timeout ! Try Again Later!!'
                                          ];  
                            }
                            else
                            {
                                $response=['status'=>false,
                                            'data'=>NULL,
                                            'message' => 'Something Went Wrong !!'
                                          ]; 
                            }
                        }

                    }

                }
                else if($user_d->user_type=='client')
                {
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
                            'service_id'    => $service->id,
                            'txnId' => $master_data->txnId,
                            'source_type'   => 'SystemDB',
                            'mobile_no' => $master_data->mobile_no,
                            'reference_id' => $reference_id,
                            'token' => $master_data->token,
                            'user_id'   => $request->login_id,
                            'used_by'   => 'coc',
                            'file_name' => $file_name,
                            'raw_data' => base64_encode($content),
                            'platform_reference' => 'api',
                            'created_at'   => date('Y-m-d H:i:s')
                        ]);

                        $URL= url('/').'/cowin/certificate/'.$file_name;
                        DB::commit();
                        $array_result=[
                            'mobile_number'=>$advance_otp->mobile_no,
                            'reference_id' => $reference_id,
                            'mobile_validity'=>'valid',
                            'verification_check'=>'completed',
                            'result' => [
                                            'url' => $URL,
                                        ]
                        ];
    
                        $response=['status'=>true,
                                    'db' => true,
                                    'data'=>$array_result,
                                    'message' => 'Verification Done !'
                        ];
                        // return response()->download($path.$file_name,$file_name,$headers);

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
                                'updated_by' => 94,
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
                                    'platform_reference' => 'api',
                                    'created_at'   => date('Y-m-d H:i:s')
                                ]);

                            DB::table('covid19_checks')->insert([
                                'parent_id' => $parent_id,
                                'business_id'  => $business_id,
                                'service_id'    => $service->id,
                                'txnId' => $advance_otp->txnId,
                                'source_type'   => 'API',
                                'mobile_no' => $advance_otp->mobile_no,
                                'reference_id' => $reference_id,
                                'token' => $advance_otp->token,
                                'user_id'   => $request->login_id,
                                'used_by'   => 'coc',
                                'file_name' => $file_name,
                                'raw_data'  => base64_encode($content),
                                'platform_reference' => 'api',
                                'created_at'   => date('Y-m-d H:i:s')
                            ]);

                            DB::commit();
                            $URL= url('/').'/cowin/certificate/'.$file_name;
                            $array_result=[
                                'mobile_number'=>$advance_otp->mobile_no,
                                'reference_id' => $reference_id,
                                'mobile_validity'=>'valid',
                                'verification_check'=>'completed',
                                'result' => [
                                                'url' => $URL,
                                            ]
                            ];
        
                            $response=['status'=>true,
                                        'db' => true,
                                        'data'=>$array_result,
                                        'message' => 'Verification Done !'
                            ];
                            // return response()->download($path.$file_name,$file_name,$headers);

                        }
                        else
                        {
                            if($response_code==400)
                            {
                                $response=['status'=>false,
                                            'data'=>NULL,
                                            'message' => 'Data Not Found !!'
                                          ];  
                               
                            }
                            else if($response_code==401)
                            {
                                $response=['status'=>false,
                                            'data'=>NULL,
                                            'message' => 'Timeout ! Try Again Later!!'
                                          ];  
                            }
                            else
                            {
                                $response=['status'=>false,
                                            'data'=>NULL,
                                            'message' => 'Something Went Wrong !!'
                                          ]; 
                            }
                        }

                    }
                }
                else
                {
                    $response=['status'=>false,
                        'data'=>NULL,
                        'message' => 'Permission Denied !!'
                        ];  
                }
                
            }
            else
            {
                $response=[
                    'status'=>false,
                    'data'=>NULL,
                    'message' => 'User Not Found !!'
                ];
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }

        return response()->json($response,200);

    }
}
