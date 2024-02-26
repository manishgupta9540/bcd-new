<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;

trait SMSTrait
{
    public static function sendOTP($mobile_no,$otp)
    {
        
        $text="";

        $response_arr = [];

        $curl = curl_init();

        $text = "Your OTP is ".$otp." for PCIL account verification. Do not share with anyone.";

        $apiURL = 'http://bhashsms.com/api/sendmsg.php?user='.env("BHAS_USER").'&pass='.env("BHAS_PASS").'&sender=PCILSM&phone='.$mobile_no.'&text='.urlencode($text).'&stype=normal&priority=ndnd';

        curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        $response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        //dd($response);

        if($response_code==200)
        {
            $response_arr=[
                'status' => true,
                'status_code' => 200,
                'msg' => 'OTP Sent Successfully'
            ];
        }

        return $response_arr;
    }

    public static function sendAddressVerifyFormLink($mobile,$name,$check_name,$address,$link)
    {

        $response_arr = [];
        //$link = "http://mybcd.live/add/xIkiRl";
        //dd($link);
        // $text="Hello ".$name."!, 
        //         You Have to Receive the Notification for Digital Address Verification, Click the Link to Start Verification. 

        //         Check Name: ".$check_name.", 

        //         Address: ".$address.", 

        //         Link: ".$link." 

        //         Regards,
        //         PCIL System 
        //         https://my-bcd.com/";

        //       //  dd(env('BHAS_PASS'));

        // $apiURL = 'http://bhashsms.com/api/sendmsg.php?user='.env("BHAS_USER").'&pass='.env("BHAS_PASS").'&sender=PCILSM&phone='.$mobile.'&text='.$text.'&stype=normal&priority=ndnd';

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_URL, $apiURL);
        // curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'GET');
        // $resp = curl_exec ( $ch );
        // $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // curl_close ( $ch );
        // dd($resp);
        // if($response_code==200)
        // {
        //     $response_arr=[
        //         'status' => true,
        //         'status_code' => 200,
        //         'msg' => 'OTP Sent Successfully'
        //     ];
        // }

        // $text = 'Hello '.$name.'!, 
        // You Have to Receive the Notification for Digital Address Verification, Click the Link to Start Verification. 
        
        // Check Name: e, 
        
        // Address: s, 
        
        // Link: t 
        
        // Regards,
        // PCIL System 
        // https://my-bcd.com/';

        $text="Hello ".$name." !,
You Have to Receive the Notification for Digital Address Verification, Click the Link to Start Verification. 
        
Check Name: ".$check_name.", 
        
Address: ".$address.", 
        
Link: ".$link." 

Regards,
PCIL System 
https://my-bcd.com/";

        $curl = curl_init();

        $apiURL = 'http://bhashsms.com/api/sendmsg.php?user='.env("BHAS_USER").'&pass='.env("BHAS_PASS").'&sender=PCILSM&phone='.$mobile.'&text='.urlencode($text).'&stype=normal&priority=ndnd';

        curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        $response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        //dd($response);

        if($response_code==200)
        {
            $response_arr=[
                'status' => true,
                'status_code' => 200,
                'msg' => 'OTP Sent Successfully'
            ];
        }

        return $response_arr;

    }

    public static function sendAddressVerifyAppLink($mobile,$name,$check_name,$address,$link)
    {
        $response_arr = [];

        $text="Hello ".$name." !,
You Have to Receive the Notification for Digital Address Verification, Click the Link to Start Verification. 
        
Check Name: ".$check_name.", 
        
Address: ".$address.", 
        
App Link: ".$link." 

Regards,
PCIL System 
https://my-bcd.com/";

        $curl = curl_init();

        $apiURL = 'http://bhashsms.com/api/sendmsg.php?user='.env("BHAS_USER").'&pass='.env("BHAS_PASS").'&sender=PCILSM&phone='.$mobile.'&text='.urlencode($text).'&stype=normal&priority=ndnd';

        curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        $response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if($response_code==200)
        {
            $response_arr=[
                'status' => true,
                'status_code' => 200,
                'msg' => 'OTP Sent Successfully'
            ];
        }

        return $response_arr;

    }

    public static function sendAddressVerifyLink($mobile,$name,$check_name,$address,$link,$parent_id,$business_id)
    {
        $response_arr = [];

        $kam_email = '';

        $parent_company_name = Helper::companyShortName($parent_id)!='' ? Helper::companyShortName($parent_id) : " ";

        $business_company_name = Helper::companyShortName($business_id)!='' ? Helper::companyShortName($business_id) : " ";

        $link_arr = [];

        $link_base = '';

        $link_remain = '';
        
        if(strpos($link,'https://')!==false)
        {
            $link_base = substr($link,0,8);
            $link_remain = substr($link,8);
        }
        else if(strpos($link,'http://')!==false)
        {
            $link_base = substr($link,0,7);
            $link_remain = substr($link,7);
        }

        // $text="Hello (".$name.")

        // Greetings from ".$parent_company_name.". 
                
        // I hope you are doing well.
                
        // We are reaching out to you on behalf of our customer (".$business_company_name."), to complete your BGV formalities. 
                
        // This message is a notification for the Digital Address Verification, kindly click the link to complete your verification.
                
        // Address - ".$address."
                
        // Web Link - ".$link."
                
        // App Link - ".$app_link."
                
        // If you are facing any issues with the link, please email (".$kam_email.") or reach out to your HR to resolve your queries at the earliest. 
                
        // Best Regards 
        // PCIL System";

        // $text="Hello (".$name.")

        // Greetings from ".$parent_company_name.". 
                
        // I hope you are doing well.
                
        // We are reaching out to you on behalf of our customer (".$business_company_name."), to complete your BGV formalities. 
                
        // This message is a notification for the Digital Address Verification, kindly click the link to complete your verification.
                
        // Address - ".$address."
                
        // Web Link - ".$link."
                
        // App Link - ".$app_link."
                
        // If you are facing any issues with the link, please email (".$primary_cam_email." and ".$sec_cam_email.") or reach out to your HR to resolve your queries at the earliest. 
                
        // Best Regards 
        // PCIL System";

        // $text="Hello (".$name.")

        // Greetings from ".$parent_company_name.". 
                
        // I hope you are doing well.
                
        // We are reaching out to you on behalf of our customer (".$business_company_name."), to complete your BGV formalities. 
                
        // This message is a notification for the Digital Address Verification, kindly click the link to complete your verification.
                
        // Address - ".$address."
                
        // Web Link - ".$link."
                
        // App Link - ".$app_link."
                
        // If you are facing any issues with the link, please email (help@my-bcd.com) or reach out to your HR to resolve your queries at the earliest. 
                
        // Best Regards 
        // PCIL System";


        // $text="Hello (".$name.")

        // Greetings from ".$parent_company_name.". 
                
        // I hope you are doing well.
                
        // We are reaching out to you on behalf of our customer (".$business_company_name."), to complete your BGV formalities. 
                
        // This message is a notification for the Digital Address Verification, kindly click the link to complete your verification.
                
        // Web Link - ".$link_base."".$link_remain."
                
        // If you are facing any issues with the link, please email (help@my-bcd.com) or reach out to your HR to resolve your queries at the earliest. 
                
        // Best Regards 
        // PCIL System";


        // $text="Hello ".$name.", 

        // Greeting from ".$parent_company_name."

        // I hope you are doing well.

        // We are Premier Consultancy and Investigation Private Limited reaching out to you on behalf of ".$business_company_name." to complete your BGV formalities

        // This message is a notification for the Digital Address Verification, kindly click the link to complete your verification.

        // Details as:

        // Web Link: ".$link_base."".$link_remain."

        // If you are facing any issues with the link, please contact 7303495237, help@my-bcd.com, or reach out to your HR to resolve your queries at the earliest.

        // Best Regards,
        // PCIL System
        // https://my-bcd.com";

        // $curl = curl_init();

        // $apiURL = 'http://bhashsms.com/api/sendmsg.php?user='.env("BHAS_USER").'&pass='.env("BHAS_PASS").'&sender=PCILSM&phone='.$mobile.'&text='.urlencode($text).'&stype=normal&priority=ndnd';

        // curl_setopt_array($curl, array(
        // CURLOPT_URL => $apiURL,
        // CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_ENCODING => '',
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // CURLOPT_CUSTOMREQUEST => 'GET',
        // ));

        // $response = curl_exec($curl);
        // $response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // curl_close($curl);

        // //dd($response);

        // if($response_code==200)
        // {
        //     $response_arr=[
        //         'status' => true,
        //         'status_code' => 200,
        //         'msg' => 'OTP Sent Successfully'
        //     ];
        // }

        // $text="Hello ".$name.", 

        // Greeting from ".$parent_company_name."

        // I hope you are doing well.

        // We are Premier Consultancy and Investigation Private Limited reaching out to you on behalf of ".$business_company_name." to complete your BGV formalities

        // This message is a notification for the Digital Address Verification, kindly click the link to complete your verification.

        // Details as:

        // Web Link: ".$link_base."".$link_remain."

        // If you are facing any issues with the link, please contact 7303495237, help@my-bcd.com, or reach out to your HR to resolve your queries at the earliest.

        // Best Regards,
        // PCIL System
        // https://my-bcd.com";

        $t='Hello '.$name.', %n %nGreeting from '.$parent_company_name.' %n %nI hope you are doing well.%n %nWe are Premier Consultancy and Investigation Private Limited reaching out to you on behalf of '.$business_company_name.' to complete your BGV formalities%n %nThis message is a notification for the Digital Address Verification, kindly click the link to complete your verification.%n %nDetails as:%n %nWeb Link: '.$link_base.''.$link_remain.' %n %nIf you are facing any issues with the link, please contact 7303495237, help@my-bcd.com, or reach out to your HR to resolve your queries at the earliest.%n %nBest Regards,%nPCIL System%n https://my-bcd.com';

        $curl = curl_init();

        $apiURL = 'https://api.textlocal.in/send/?apikey='.env("TEXTLOCAL_KEY").'&sender=PCILSM&numbers='.'91'.$mobile.'&message='.urlencode($t);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiURL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        $response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $array_data = json_decode($response,true);

        if($response_code==200 && ($array_data!=NULL && count($array_data)>0 && stripos($array_data['status'],'success')!==false))
        {
            $response_arr=[
                'status' => true,
                'status_code' => 200,
                'msg' => 'OTP Sent Successfully'
            ];
        }

        return $response_arr;


    }

    
}