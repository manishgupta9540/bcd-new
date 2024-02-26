<?php

namespace App\Http\Controllers\API\AddressVerification\V1\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
   
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
        // $digital_addr_verification =null;

        $login = DB::table('candidate_reinitiates as u')
        ->select('u.id','u.user_type','u.status')
        ->where(['u.phone'=>$phone_number])
        ->whereIn('u.user_type',['vendor','vendor_user','candidate'])
        ->latest()->first();
       
        if ($login) {
            
            $digital_addr_verification= DB::table('digital_address_verifications')->where(['candidate_id'=>$login->id,'status'=>'1'])->first();
         
        }
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
        // Check  Digital address verification required or not 
        if($digital_addr_verification === null)
        {  
        return response()->json(['status' => 'error','error_message'=>'No Address Verification Required for this candidate!, please contact to your agency'], 200);
        }
        //update sms otp
        DB::table('candidate_reinitiates')->where(['id'=>$login->id])->update(['sms_otp'=>'1111','sms_otp_sent_at'=>date('Y-m-d H:i:s')]);

        //send data
        $successResponse =  ['status'=>'success',
                            'message'=>'SMS OTP sent!',
                            'data'=>[
                                'candidate_id'=>"$login->id",
                                'sms_otp'=>"1111"
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
        ->select('u.id','u.user_type','u.status','u.name','u.first_name','u.last_name','email','phone')
        ->where(['u.phone'=>$phone_number,'sms_otp'=>$request->input('sms_otp')])
        ->whereIn('user_type',['vendor','vendor_user','candidate'])
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
        if($login->user_type != 'candidate' && $login->user_type != 'vendor' && $login->user_type != 'vendor_user')
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
            //When Login user is candidate
            
        //send data
        $successResponse = array('status'=>'success',
        'data'=>
                array(
                    'user_id'=>"$login->id",
                    'first_name'=>$login->first_name,
                    'last_name'=>$login->last_name,
                    'fullname'=>ucfirst($login->name),
                    'user_type'=>$login->user_type=='candidate'?'candidate':'vendor'
                ),
               
        ); 

        return response()->json($successResponse, 200);
        
       
    
    }


}
