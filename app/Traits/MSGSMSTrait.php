<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

trait MSGSMSTrait
{

    public function sendOTP($mobile_no,$otp)
    {
        /*
            Note:- Mobile No. Should merged with country code ex:- 919876543210
        */

        $response_arr = [];

        // Success Code 200
        $success_response = '{"message":"316c716e514b363931323933","type":"success"}';

        $payload='{
            "flow_id": "61bc67283e20222117085206",
            "sender": "PCILSM",
            "mobiles": "'.$mobile_no.'",
            "otp": "'.$otp.'"
        }';

        $apiURL = "https://api.msg91.com/api/v5/flow/";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
        curl_setopt ($ch, CURLOPT_POST, 1);
        $auth_key = 'authkey : '.env('MSG_AUTH_KEY');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($auth_key,'Content-Type: application/json')); 
        curl_setopt($ch, CURLOPT_URL, $apiURL);
        // Attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $resp = curl_exec ( $ch );
        $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ( $ch );
        $array_data =  json_decode($resp,true);

        if($response_code==200)
        {
            if(stripos($array_data['type'],'success')!==false)
            {
                $response_arr=[
                    'status' => true,
                    'status_code' => 200,
                    'msg' => 'OTP Sent Successfully'
                ];
            }
            else if(stripos($array_data,'error')!==false)
            {
                $response_arr=[
                    'status' => false,
                    'status_code' => 200,
                    'msg' => $array_data['message']
                ];
            }

            return $response_arr;
        }

        return $response_arr;
    }

    public static function sendAccountRegisterOTP($mobile_no,$otp)
    {
        /*
            Note:- Mobile No. Should merged with country code ex:- 919876543210
        */

        $response_arr = [];

        // Success Code 200
        $success_response = '{"message":"316c716e514b363931323933","type":"success"}';

        // error response 200

        $error_response = '{"message":"template id missing","type":"error"}';

        $payload='{
            "flow_id": "61bc67283e20222117085206",
            "sender": "PCILSM",
            "mobiles": "'.$mobile_no.'",
            "otp": "'.$otp.'"
        }';

        $apiURL = "https://api.msg91.com/api/v5/flow/";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
        curl_setopt ($ch, CURLOPT_POST, 1);
        $auth_key = 'authkey : '.env('MSG_AUTH_KEY');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($auth_key,'Content-Type: application/json')); 
        curl_setopt($ch, CURLOPT_URL, $apiURL);
        // Attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $resp = curl_exec ( $ch );
        $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ( $ch );
        $array_data =  json_decode($resp,true);

        if($response_code==200)
        {
            if(stripos($array_data['type'],'success')!==false)
            {
                $response_arr=[
                    'status' => true,
                    'status_code' => 200,
                    'msg' => 'OTP Sent Successfully'
                ];
            }
            else if(stripos($array_data,'error')!==false)
            {
                $response_arr=[
                    'status' => false,
                    'status_code' => 200,
                    'msg' => $array_data['message']
                ];
            }
            return $response_arr;
        }

        return $response_arr;
    }

    public static function instantReport($mobile_no,$order_id,$checks,$date,$url)
    {
        /*
            Note:- Mobile No. Should merged with country code ex:- 919876543210
        */

        $response_arr = [];

        // Success Code 200
        $success_response = '{"message":"316c716e514b363931323933","type":"success"}';

        // error response 200

        $error_response = '{"message":"template id missing","type":"error"}';

        $payload='{
            "flow_id": "61bc7e192b54b16b000056b7",
            "sender": "PCILSM",
            "mobiles": "'.$mobile_no.'",
            "order_id": "'.$order_id.'",
            "checks" : "'.$checks.'",
            "date_time" : "'.$date.'",
            "url" : "'.$url.'",
        }';

        $apiURL = "https://api.msg91.com/api/v5/flow/";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
        curl_setopt ($ch, CURLOPT_POST, 1);
        $auth_key = 'authkey : '.env('MSG_AUTH_KEY');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($auth_key,'Content-Type: application/json')); 
        curl_setopt($ch, CURLOPT_URL, $apiURL);
        // Attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $resp = curl_exec ( $ch );
        $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ( $ch );
        $array_data =  json_decode($resp,true);

        if($response_code==200)
        {
            if(stripos($array_data['type'],'success')!==false)
            {
                $response_arr=[
                    'status' => true,
                    'status_code' => 200,
                    'msg' => 'OTP Sent Successfully'
                ];
            }
            else if(stripos($array_data,'error')!==false)
            {
                $response_arr=[
                    'status' => false,
                    'status_code' => 200,
                    'msg' => $array_data['message']
                ];
            }

            return $response_arr;
        }

        return $response_arr;
    }
}