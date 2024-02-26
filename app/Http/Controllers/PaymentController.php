<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Razorpay\Api\Api;

use Session;

use Redirect;


class PaymentController extends Controller

{    

    public function pay()
    {        
        return view('pay');

    }

    //
    public function payment(Request $request)
    {

        $input = $request->all();

        print_r($request->all()); die();

        $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));

        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if(count($input)  && !empty($input['razorpay_payment_id'])) {

            try {

                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount'])); 

            } catch (\Exception $e) {

                return  $e->getMessage();

                \Session::put('error',$e->getMessage());

                return redirect()->back();

            }

        }

        \Session::put('success', 'Payment successful');

        return redirect()->back();

    }

    //create a plan 
    public function createPlan(Request $request)
    {   

        $username = "rzp_test_MZOe2gmaqBb32K";
        $password = "43pD66ZH7uztVqoAzNLMq5pC";
        // Setup request to send json via POST
        $data = array(
            'period'    => 'monthly',
            'interval'  => '1',
            'item'      =>['name1'=>'Basic Plan','amount'=>'99900','currency'=>'INR'],
        );
        $payload = json_encode($data);

        $apiURL = "https://api.razorpay.com/v1/plans";

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        // Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_URL, $apiURL);
        // Attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $resp = curl_exec ( $ch );
        curl_close ( $ch );
        
        echo $resp;

        //return response()->json(['success' => false,'error_type'=>'','next_action'=>'verify-otp','redirect'=>'']); 

    }

    //create a subscription
    //create a plan 
    public function createSubscription(Request $request)
    {   

        $username = "rzp_test_MZOe2gmaqBb32K";
        $password = "43pD66ZH7uztVqoAzNLMq5pC";
        // Setup request to send json via POST
        $data = array(
            'plan_id'    => 'plan_G8uhhlXY58A3eO',
            'total_count'  => '6',
            'notes'      =>['name'=>'Subscription of Basic Plan'],
        );
        $payload = json_encode($data);

        $apiURL = "https://api.razorpay.com/v1/subscriptions";

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        // Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_URL, $apiURL);
        // Attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $resp = curl_exec ( $ch );
        curl_close ( $ch );
        
        echo $resp;

        //return response()->json(['success' => false,'error_type'=>'','next_action'=>'verify-otp','redirect'=>'']); 

    }

}