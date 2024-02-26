<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

trait MSGWhatsappTrait
{
    public static function sendOTP($mobile_no,$otp)
    {
        $response_arr = [];

        // Status Code 200
        $success_response = '{
                    "status": "success",
                    "hasError": false,
                    "data": {
                        "message_uuid": "gBEGkYcAA1QmAgm2at2dyzxCA3A",
                        "status": "message submitted successfully"
                    },
                    "errors": null
                }';

        // Status Code 401 (IP Not Whitelist)

        $error_code = '{"status":"fail","hasError":true,"errors":"Unauthorized","code":"401","apiError":"418"}';

        // Status Code 401 (Parameter Missing / Authkey missing)

        $error_code = '{"status":"fail","hasError":true,"errors":"Unauthorized","code":"401","apiError":"201"}';

        $payload='{
                    "integrated_number": "917303389302",
                    "content_type": "template",
                    "payload": {
                        "to": "'.$mobile_no.'",
                        "type": "template",
                        "template": {
                        "name": "bcd_send_otp",
                        "language": {
                            "code": "en_US",
                            "policy": "deterministic"
                        },
                        "namespace": "f6addf0b_2920_48a5_8523_aaf5eb093b51",
                        "components": [
                            {
                            "type": "body",
                            "parameters": [
                                {
                                "type": "text",
                                "text": "'.$otp.'"
                                }
                            ]
                            }
                        ]
                        }
                    },
                    "authkey": "'.env('MSG_AUTH_KEY').'"
                }';

            $apiURL = "https://api.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
            curl_setopt ($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_URL, $apiURL);
            // Attach encoded JSON string to the POST fields
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            $resp = curl_exec ( $ch );
            $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close ( $ch );
            $array_data =  json_decode($resp,true);

            if($response_code==200)
            {
                $response_arr=[
                    'status' => true,
                    'status_code' => 200,
                    'msg' => 'OTP Sent Successfully'
                ];
                return $response_arr;
            }
            else if($response_code==401)
            {
                    $response_arr=[
                        'status' => false,
                        'status_code' => 401,
                        'api_code' => $array_data['apiError'],
                        'msg' => $array_data['errors']
                    ];
                    return $response_arr;
            }

        return $response_arr;


    }
    public static function sendAccountRegisterOTP($mobile_no,$otp)
    {
        $response_arr = [];

        // Status Code 200
        $success_response = '{
                    "status": "success",
                    "hasError": false,
                    "data": {
                        "message_uuid": "gBEGkYcAA1QmAgm2at2dyzxCA3A",
                        "status": "message submitted successfully"
                    },
                    "errors": null
                }';

        // Status Code 401 (IP Not Whitelist)

        $error_code = '{"status":"fail","hasError":true,"errors":"Unauthorized","code":"401","apiError":"418"}';

        // Status Code 401 (Parameter Missing / Authkey missing)

        $error_code = '{"status":"fail","hasError":true,"errors":"Unauthorized","code":"401","apiError":"201"}';

        // Status Code 500

        $error_code = '{
            "status": "fail",
            "hasError": true,
            "data": null,
            "errors": "Something went wrong"
        }';

        $payload='{
                    "integrated_number": "917303389302",
                    "content_type": "template",
                    "payload": {
                        "to": "'.$mobile_no.'",
                        "type": "template",
                        "template": {
                        "name": "bcd_send_account_register_otp",
                        "language": {
                            "code": "en_US",
                            "policy": "deterministic"
                        },
                        "namespace": "f6addf0b_2920_48a5_8523_aaf5eb093b51",
                        "components": [
                            {
                            "type": "body",
                            "parameters": [
                                {
                                "type": "text",
                                "text": "'.$otp.'"
                                }
                            ]
                            }
                        ]
                        }
                    },
                    "authkey": "'.env('MSG_AUTH_KEY').'"
                }';

          $apiURL = "https://api.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/";

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
          curl_setopt ($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
          curl_setopt($ch, CURLOPT_URL, $apiURL);
          // Attach encoded JSON string to the POST fields
          curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
          $resp = curl_exec ( $ch );
          $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          curl_close ( $ch );
          $array_data =  json_decode($resp,true);
        //  dd($array_data);
          if($response_code==200)
          {
              $response_arr=[
                  'status' => true,
                  'status_code' => 200,
                  'msg' => 'OTP Sent Successfully'
              ];
              return $response_arr;
          }
          else if($response_code==401)
          {
                $response_arr=[
                    'status' => false,
                    'status_code' => 401,
                    'api_code' => $array_data['apiError'],
                    'msg' => $array_data['errors']
                ];
                return $response_arr;
          }

          return $response_arr;

    }
    public static function instantReport($mobile_no,$order_id,$checks,$date,$url)
    {
        $response_arr = [];

        // Status Code 200
        $success_response = '{
                    "status": "success",
                    "hasError": false,
                    "data": {
                        "message_uuid": "gBEGkYcAA1QmAgm2at2dyzxCA3A",
                        "status": "message submitted successfully"
                    },
                    "errors": null
                }';

        // Status Code 401 (IP Not Whitelist)

        $error_code = '{"status":"fail","hasError":true,"errors":"Unauthorized","code":"401","apiError":"418"}';

        // Status Code 401 (Parameter Missing / Authkey missing)

        $error_code = '{"status":"fail","hasError":true,"errors":"Unauthorized","code":"401","apiError":"201"}';

        // Status Code 500

        $error_code = '{
            "status": "fail",
            "hasError": true,
            "data": null,
            "errors": "Something went wrong"
        }';

        $payload='{
                    "integrated_number": "917303389302",
                    "content_type": "template",
                    "payload": {
                        "to": "'.$mobile_no.'",
                        "type": "template",
                        "template": {
                        "name": "bcd_instant_report",
                        "language": {
                            "code": "en_US",
                            "policy": "deterministic"
                        },
                        "namespace": "f6addf0b_2920_48a5_8523_aaf5eb093b51",
                        "components": [
                            {
                            "type": "body",
                            "parameters": [
                                {
                                    "type": "text",
                                    "text": "'.$order_id.'"
                                  },
                                  {
                                    "type": "text",
                                    "text": "'.$checks.'"
                                  },
                                  {
                                    "type": "text",
                                    "text": "'.$date.'"
                                  },
                                  {
                                    "type": "text",
                                    "text": "'.$url.'"
                                  }
                            ]
                            }
                        ]
                        }
                    },
                    "authkey": "'.env('MSG_AUTH_KEY').'"
                }';

                $apiURL = "https://api.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                curl_setopt ($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
                curl_setopt($ch, CURLOPT_URL, $apiURL);
                // Attach encoded JSON string to the POST fields
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                $resp = curl_exec ( $ch );
                $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close ( $ch );
                $array_data =  json_decode($resp,true);
                //  dd($array_data);
                if($response_code==200)
                {
                    $response_arr=[
                        'status' => true,
                        'status_code' => 200,
                        'msg' => 'OTP Sent Successfully'
                    ];
                    return $response_arr;
                }
                else if($response_code==401)
                {
                        $response_arr=[
                            'status' => false,
                            'status_code' => 401,
                            'api_code' => $array_data['apiError'],
                            'msg' => $array_data['errors']
                        ];
                        return $response_arr;
                }

                return $response_arr;
    }
}