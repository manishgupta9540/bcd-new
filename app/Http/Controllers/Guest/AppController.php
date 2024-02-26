<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\User;
use Carbon\Carbon;
use Razorpay\Api\Api;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use PDF;
use ZipArchive;
use Illuminate\Support\Facades\Crypt;
class AppController extends Controller
{
    //

    public function verificationServices(Request $request,$id)
    {
        $candidate_id=base64_decode($id);
        $business_id=Auth::user()->business_id;
        $services=DB::table('services as s')
                    ->select('s.*')
                    ->join('service_form_inputs as si','s.id','=','si.service_id')
                    ->where(['s.verification_type'=>'Auto','s.status'=>1,'business_id'=>NULL])
                    ->whereNotIn('s.name',['GSTIN','Telecom'])
                    ->groupBy('si.service_id')
                    ->get();

        $candidate=DB::table('users')->where(['id'=>$candidate_id,'user_type'=>'candidate'])->first();

        $guest_v=DB::table('guest_verifications')->where(['business_id'=>$business_id,'candidate_id'=>$candidate_id,'is_payment_done'=>0,'status'=>NULL])->first();

        // dd($services);

        return view('guest.verifications.index',compact('services','candidate','guest_v'));
    }

    public function verificationServicesStore(Request $request)
    {
        $parent_id=Auth::user()->parent_id;
        $business_id=Auth::user()->business_id;
        $user_id=Auth::user()->id;

        $candidate_id=base64_decode($request->candidate_id);
        $rules= 
        [
            'services'   => 'required|array|min:1',
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        //validation
        if(count($request->services)>0)
        {
            foreach($request->services as $service)
            {
                $services=DB::table('services')->where('id',$service)->first();


                // $name=$services->name.' '.'Number';
                if($services->name=='Aadhar')
                {
                    $rules= 
                    [
                        'service_unit-'.$service   => 'required|regex:/^((?!([0-1]))[0-9]{12})$/',
                    ];
                    $custom=[
                        'service_unit-'.$service.'.required' => 'Aadhar Number is Required',
                        'service_unit-'.$service.'.regex' => 'Enter a valid 12-digit Aadhar Number',
                    ];
                    $validator = Validator::make($request->all(), $rules,$custom);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ]);
                    }

                }
                else if($service==3)
                {
                    // echo $services->name;
                    $rules= 
                    [
                        'service_unit-'.$service   => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{10}$/',
                    ];
                    $custom=[
                        'service_unit-'.$service.'.required' => 'PAN Number is Required',
                        'service_unit-'.$service.'.regex' => 'Enter a valid 10-digit PAN Number',
                    ];
                    $validator = Validator::make($request->all(), $rules,$custom);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ]);
                    }
                }
                else if($services->name=='Voter ID')
                {
                    $rules= 
                    [
                        'service_unit-'.$service   => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{10}$/',
                    ];
                    $custom=[
                        'service_unit-'.$service.'.required' => 'Voter ID Number is Required',
                        'service_unit-'.$service.'.regex' => 'Enter a valid 10-digit Voter ID Number',
                    ];
                    $validator = Validator::make($request->all(), $rules,$custom);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ]);
                    }
                }
                else if($services->name=='RC')
                {
                    $rules= 
                    [
                        'service_unit-'.$service   => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{8,}$/',
                    ];
                    $custom=[
                        'service_unit-'.$service.'.required' => 'RC Number is Required',
                        'service_unit-'.$service.'.regex' => 'Enter a valid RC Number',
                    ];
                    $validator = Validator::make($request->all(), $rules,$custom);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ]);
                    }
                }
                else if($services->name=='Driving')
                {
                    $rules= 
                    [
                        'service_unit-'.$service   => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{8,}$/',
                    ];
                    $custom=[
                        'service_unit-'.$service.'.required' => 'DL Number is Required',
                        'service_unit-'.$service.'.regex' => 'Enter a valid DL Number',
                    ];
                    $validator = Validator::make($request->all(), $rules,$custom);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ]);
                    }
                    
                }
                else if($services->name=='Passport')
                {
                    $rules= 
                    [
                        'service_unit-'.$service   => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{15,}$/',
                        'notes-'.$service   => 'required|date',
                    ];
                    $custom=[
                        'service_unit-'.$service.'.required' => 'File Number is required',
                        'service_unit-'.$service.'.regex' => 'Enter a valid File Number',
                        'notes-'.$service.'.required' => 'DOB is Required',
                        'notes-'.$service.'.date' => 'DOB is not a valid date',
                    ];
                    $validator = Validator::make($request->all(), $rules,$custom);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ]);
                    }  
                    
                    $dob = date('Y-m-d',strtotime($request->input('notes-'.$service)));

                    $date_of_b=Carbon::parse($dob)->format('Y-m-d');
                    $today=Carbon::now();
                    $today_date=Carbon::now()->format('Y-m-d');
                    $year=$today->diffInYears($date_of_b);

                    if($year<18 || ($date_of_b >= $today_date))
                    {
                        return response()->json([
                            'success' => false,
                            'custom'  => 'yes',
                            'errors' =>['notes-'.$service =>'Age Must be 18 or older !']
                        ]);
                    }
                }
                else if($services->name=='Bank Verification')
                {
                    $rules= 
                    [
                        'service_unit-'.$service   => 'required|regex:/^(?=.*[0-9])[A-Z0-9]{9,18}$/',
                        'notes-'.$service   => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{11,}$/',
                    ];
                    $custom=[
                        'service_unit-'.$service.'.required' => 'Account Number is required',
                        'service_unit-'.$service.'.regex' => 'Enter a valid Account Number',
                        'notes-'.$service.'.required' => 'IFSC Code is Required',
                        'notes-'.$service.'.regex' => 'Enter a valid IFSC Code',
                    ];
                    $validator = Validator::make($request->all(), $rules,$custom);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ]);
                    }           
                }
                // else if($services->name=='Telecom')
                // {
                //     $rules= 
                //     [
                //         'service_unit-'.$service   => 'required|regex:/^(?=.*[0-9])[0-9]{10}$/',
                //     ];
                //     $custom=[
                //         'service_unit-'.$service.'.required' => 'Mobile Number is Required',
                //         'service_unit-'.$service.'.regex' => 'Enter a valid 10-digit Mobile Number',
                //     ];
                //     $validator = Validator::make($request->all(), $rules,$custom);
                    
                //     if ($validator->fails()){
                //         return response()->json([
                //             'success' => false,
                //             'errors' => $validator->errors()
                //         ]);
                //     }
                    
                // }   
            }
        }

        $user=DB::table('users')->where('id',$business_id)->first();
        // $receiptId = Str::random(20);
        // $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));

        // In razorpay you have to convert rupees into paise we multiply by 100
        // Currency will be INR
        // Creating order
        $total_price=0;
        if(count($request->services)>0)
        {
            foreach($request->services as $service)
            {
                $total_price=$total_price+50;
            }
        }

        // $order = $api->order->create(array(
        //     'receipt' => $receiptId,
        //     'amount' => $total_price * 100,
        //     'currency' => 'INR'
        //     )
        // );

        $guest_v=DB::table('guest_verifications')->where(['business_id'=>$business_id,'candidate_id'=>$candidate_id,'is_payment_done'=>0,'status'=>NULL])->first();

        if($guest_v!=NULL)
        {
            $guest_v_s=DB::table('guest_verification_services')->where(['gv_id'=>$guest_v->id])->delete();
            // $userID = "ORDER";
            // $order_id = strtoupper(substr($userID, 0, 3)).date("-ymds").str_pad($guest_v->id,10, '0', STR_PAD_LEFT);

            DB::table('guest_verifications')
            ->where(['business_id'=>$business_id,'candidate_id'=>$candidate_id,'is_payment_done'=>0,'status'=>NULL])
            ->update([
                'user_id'       => $user_id,
                // 'transaction_id'=> $order['id'],
                // 'order_id' => $order_id,
                // 'razorpay_id'   => env('RAZOR_KEY'),
                'currency'      => 'INR',
                'sub_total'     => $total_price,
                'total_price'   => $total_price,
                'promo_code_id' => NULL,
                'promo_code_title' => NULL,
            ]);
            $gv_id=$guest_v->id;
        }
        else
        {
            $gv_id=DB::table('guest_verifications')->insertGetId([
                'parent_id' => $parent_id,
                'business_id'   => $business_id,
                'candidate_id'   => $candidate_id,
                'user_id'       => $user_id,
                'name' => $user->name,
                'email' => $user->email,
                'contactNumber' => $user->phone,
                // 'transaction_id'=> $order['id'],
                // 'razorpay_id'   => env('RAZOR_KEY'),
                'currency'      => 'INR',
                'sub_total'     => $total_price,
                'total_price'   => $total_price,
                'created_at'    => date('Y-m-d H:i:s'),
                'promo_code_id' => NULL,
                'promo_code_title' => NULL,
            ]);

            // $userID = "ORDER";
            // $order_id = strtoupper(substr($userID, 0, 3)).date("-ymds").str_pad($gv_id,10, '0', STR_PAD_LEFT);

            // DB::table('guest_verifications')->where(['id'=>$gv_id])->update(['order_id'=>$order_id]);
        }
        

        //
        if(count($request->services)>0)
        {
            foreach($request->services as $service)
            {
                $services=DB::table('services')->where('id',$service)->first();

                $array_result=[];
                if($services->name=='Aadhar')
                {
                    $array_result=['aadhar_number'=>$request->input('service_unit-'.$service)];  
                }
                else if($service==3)
                {
                    $array_result=['pan_number'=>$request->input('service_unit-'.$service)];
                }
                else if($services->name=='Voter ID')
                {
                    $array_result=['voter_id_number'=>$request->input('service_unit-'.$service)];
                }
                else if($services->name=='RC')
                {
                    $array_result=['rc_number'=>$request->input('service_unit-'.$service)];
                }
                else if($services->name=='Driving')
                {
                    $array_result=['dl_number'=>$request->input('service_unit-'.$service)];
                }
                else if($services->name=='Passport')
                {
                    $array_result=['file_number'=>$request->input('service_unit-'.$service),'dob'=>$request->input('notes-'.$service)];

                }
                else if($services->name=='Bank Verification')
                {
                    $array_result=['account_number'=>$request->input('service_unit-'.$service),'ifsc_code'=>$request->input('notes-'.$service)];

                }

                DB::table('guest_verification_services')->insert([
                    'parent_id' => $parent_id,
                    'business_id'   => $business_id,
                    'candidate_id'   => $candidate_id,
                    'user_id'       => $user_id,
                    'gv_id'         => $gv_id,
                    'service_id'    => $service,
                    'service_number' => json_encode($array_result),
                    'price'   => 50,
                    'created_at'    => date('Y-m-d H:i:s')
                ]);

            }
        }

        // Let's checkout payment page is it working
        return response()->json([
            'success'  => true,
            'candidate_id' => base64_encode($candidate_id),
            // 'order_id'  => base64_encode($order['id']),
        ]);
    }

    public function verificationCheckout(Request $request,$id)
    {
        $business_id=Auth::user()->business_id;
        $candidate_id=base64_decode($id);

        $candidate=DB::table('users')->where(['id'=>$candidate_id,'user_type'=>'candidate'])->first();
        
        $guest_v=DB::table('guest_verifications')->where(['business_id'=>$business_id,'candidate_id'=>$candidate_id,'is_payment_done'=>0,'status'=>NULL])->first();
        
        if($guest_v!=NULL)
            return view('guest.verifications.checkout',compact('guest_v','candidate'));
        else
            return redirect('/guest/candidates');

    }

    public function verificationPromoCode(Request $request)
    {
        $guest_v_id=base64_decode($request->gv_id);
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

        // $promo=DB::table('promocodes')->where(DB::raw('BINARY `title`'),$request->promocode)->where(['status'=>'1','is_deleted'=>'0'])->first();

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

        $total_price=$request->total_price;

        $discount = $promo->discount;

        if($promo->discount_type =='fixed_amount')
        {
            $amount = $total_price - $discount;

            $amount=number_format($amount,2);
        }
        else
        {
            $amount=$total_price - ($total_price * ($discount/100));

            $amount=number_format($amount,2);
        }

        
        
        $guest_v=DB::table('guest_verifications')
                ->where(['id'=>$guest_v_id])
                ->update([
                    'promo_code_id' => $promo->id,
                    'promo_code_title' => $request->promocode,
                    'total_price' => $amount
                ]);
        
        return response()->json([
            'success' => true,
            'type' => '',
            'discount' => $discount,
            'total_price' => $amount,
            'title' => $request->promocode,
        ]);
        

    }

    public function verificationRemovePromoCode(Request $request)
    {
        $guest_v_id=base64_decode($request->gv_id);

        $guest_v=DB::table('guest_verifications')->where(['id'=>$guest_v_id])->first();
        
            DB::table('guest_verifications')
                ->where(['id'=>$guest_v_id])
                ->update([
                    'promo_code_id' => NULL,
                    'promo_code_title' => NULL,
                    'total_price' => $guest_v->sub_total
                ]);
        
        return response()->json([
            'success' => true,
            'total_price' => $guest_v->sub_total,
        ]);
        

    }

    public function verificationServicesCheckoutStore(Request $request)
    {
        $guest_v_id=base64_decode($request->gv_id);
        $candidate_id=base64_decode($request->candidate_id);
        $receiptId = Str::random(20);
        $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));

        $total_price=$request->total_price;

        $order = $api->order->create(array(
            'receipt' => $receiptId,
            'amount' => $total_price * 100,
            'currency' => 'INR',
            'payment_capture' => true
            )
        );


        $userID = "BCD";
        $order_id = strtoupper(substr($userID, 0, 3)).date("-ymds").$guest_v_id;

        DB::table('guest_verifications')
            ->where(['id'=>$guest_v_id])
            ->update([
                'transaction_id'=> $order['id'],
                'order_id' => $order_id,
                'razorpay_id'   => env('RAZOR_KEY'),
                'currency'      => 'INR',
                'total_price'   => $total_price,
            ]);

        return redirect('/guest/candidates/verification/payment-page/'.base64_encode($candidate_id).'/'.base64_encode($order['id']));

    }


    public function payment(Request $request){
        $business_id=Auth::user()->business_id;
        $user_id=Auth::user()->id;
        $candidate_id =base64_decode($request->candidate_id);
        $order_id = base64_decode($request->order_id);
        // dd($order_id);
        $response=DB::table('guest_verifications')->where(['business_id'=>$business_id,'transaction_id'=>$order_id])->first();
        // dd($response);
        return view('guest.verifications.payment.payment-page',compact('response'));
    }

    public function Complete(Request $request)
    {
        $business_id=Auth::user()->business_id;
        $user_id=Auth::user()->id;

        $candidate_id=$request->candidate_id;
        $order_id=base64_encode($request->all()['rzp_orderid']);
        // Now verify the signature is correct . We create the private function for verify the signature
        $signatureStatus = $this->SignatureVerify(
            $request->all()['rzp_signature'],
            $request->all()['rzp_paymentid'],
            $request->all()['rzp_orderid']
        );
        
        // dd($signatureStatus);
        
        // If Signature status is true We will save the payment response in our database
        // In this tutorial we send the response to Success page if payment successfully made
        $guest_v=DB::table('guest_verifications')->where(['business_id'=>$business_id,'transaction_id'=>$request->all()['rzp_orderid']])->first();
        if($guest_v->is_payment_done==1)
        {
            header("Refresh:0");
        }
        else
        {
            if($signatureStatus == true)
            {
                $guest_v=DB::table('guest_verifications')->where(['business_id'=>$business_id,'transaction_id'=>$request->all()['rzp_orderid']])->first();

                $guest_v_s=DB::table('guest_verification_services')->where(['gv_id'=>$guest_v->id])->get();

                // generate report
                foreach($guest_v_s as $guest)
                {
                    $services=DB::table('services')->where('id',$guest->service_id)->first();
                    if($services->name=='Aadhar')
                    {
                        $this->idCheckAadhar($guest->gv_id,$guest->service_id);
                    }
                    else if($guest->service_id==3)
                    {
                        // dd($guest->gv_id);
                        $this->idCheckPan($guest->gv_id,$guest->service_id);
                    }
                    else if($services->name=='Voter ID')
                    {
                        $this->idCheckVoterID($guest->gv_id,$guest->service_id);
                    }
                    else if($services->name=='RC')
                    {
                        $this->idCheckRC($guest->gv_id,$guest->service_id);
                    }
                    else if($services->name=='Passport')
                    {
                        $this->idCheckPassport($guest->gv_id,$guest->service_id);
                    }
                    else if($services->name=='Driving')
                    {
                        $this->idCheckDL($guest->gv_id,$guest->service_id);
                    }
                    else if($services->name=='Bank Verification')
                    {
                        $this->idCheckBankAccount($guest->gv_id,$guest->service_id);
                    }
                }

                $guest_v_s=DB::table('guest_verification_services')->where(['gv_id'=>$guest_v->id,'status'=>'success'])->get();
                $zipname="";
                if(count($guest_v_s)>0)
                {
                    $zipname = 'reports-'.date('Ymdhis').'.zip';
                    $zip = new \ZipArchive();      
                    $zip->open(public_path().'/guest/reports/zip/'.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                    foreach($guest_v_s as $guest)
                    {
                        $path = public_path()."/guest/reports/pdf/".$guest->file_name;
                        
                        $zip->addFile($path, '/reports/'.basename($path));  
                    }
                    $zip->close();

                    DB::table('guest_verifications')->where(['business_id'=> $business_id,'transaction_id'=>$request->all()['rzp_orderid']])->update([
                        'payment_id' =>  $request->all()['rzp_paymentid'],
                        'is_payment_done' => 1,
                        'zip_name' => $zipname!=""?$zipname:NULL,
                        'status' => 'success',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                    $guest_v=DB::table('guest_verifications')->where(['business_id'=>$business_id,'transaction_id'=>$request->all()['rzp_orderid']])->first();
                    
                    $email=$guest_v->email;
                    $name=$guest_v->name;

                    if($guest_v->updated_at!=NULL)
                        $date=$guest_v->updated_at;
                    else
                        $date=$guest_v->created_at;

                    $data  = array('name'=>$name,'email'=>$email,'date' => $date,'zip_id'=>base64_encode($guest_v->id),'guest_v'=>$guest_v);
            
                    Mail::send(['html'=>'mails.guest-payment'], $data, function($message) use($email,$name) {
                        $message->to($email, $name)->subject
                            ('BCD System - Payment Notification');
                        $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                    });
                    
                }
                else
                {
                    DB::table('guest_verifications')->where(['business_id'=> $business_id,'transaction_id'=>$request->all()['rzp_orderid']])->update([
                        'payment_id' =>  $request->all()['rzp_paymentid'],
                        'is_payment_done' => 1,
                        'status' => 'failed',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }

                // incrementing the usage limit of promocode
                if($guest_v->promo_code_id!=NULL)
                {
                    $promo = DB::table('promocodes')->where(['id' => $guest_v->promo_code_id])->first();
                    $is_expired=0;

                    $limit = 0;

                    $limit = $promo->used_limit + 1;

                    if($limit==$promo->uses_limit)
                    {
                        $is_expired = 1;

                        DB::table('promocodes')
                        ->where(['id' => $guest_v->promo_code_id])
                        ->update([
                            'is_expired' => $is_expired
                        ]);
                    }

                    DB::table('promocodes')
                            ->where(['id' => $guest_v->promo_code_id])
                            ->update([
                                'used_limit' => $limit
                            ]);
                }
                
                
                // You can create this page
                return redirect('/guest/candidates/verification/payment-success/'.Crypt::encryptString($zipname));
                // return view('guest.verifications.payment.payment-success',compact('zipname'));
            }
            else{
                DB::table('guest_verifications')->where(['business_id'=> $business_id,'transaction_id'=>$request->all()['rzp_orderid']])->update([
                    'status' => 'failed',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                // You can create this page
                return redirect('/guest/candidates/verification/payment-failed/'.Crypt::encryptString($candidate_id).'/'.Crypt::encryptString($order_id));
                // return view('guest.verifications.payment.payment-failed',compact('candidate_id','order_id'));
            }
        }
        
    }

    public function paymentSuccess(Request $request)
    {
        $zipname=Crypt::decryptString($request->zipname);

        return view('guest.verifications.payment.payment-success',compact('zipname'));
    }

    public function paymentFailed(Request $request)
    {
        $candidate_id=Crypt::decryptString($request->candidate_id);
        $order_id=Crypt::decryptString($request->order_id);

        return view('guest.verifications.payment.payment-failed',compact('candidate_id','order_id'));
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
    public function idCheckAadhar($gv_id,$service_id)
    {        
        $business_id = Auth::user()->business_id;
        $user_id=Auth::user()->id;
        
        $parent_id=Auth::user()->parent_id;

        $price=50;

        $path=public_path().'/guest/reports/pdf/';

        $guest_v_s=DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->first();

        $file_name='aadhar-'.$guest_v_s->candidate_id.date('Ymdhis').".pdf";
        
        $data=json_decode($guest_v_s->service_number,true);

        $aadhar_number=$data['aadhar_number'];
        //check first into master table
        $master_data = DB::table('aadhar_check_masters')->select('*')->where(['aadhar_number'=>$aadhar_number])->first();
        
        if($master_data !=null){
            
                // store log
                $check_data = [
                'parent_id'         =>$parent_id,
                'business_id'       =>$business_id,
                'candidate_id'      => $guest_v_s->candidate_id,
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

            $pdf = PDF::loadView('guest.verifications.pdf.aadhar', compact('master_data'))
                ->save($path.$file_name); 
            
            DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                'price' => $price,
                'status' => 'success',
                'file_name' => $file_name,
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
                //check if ID number is new then insert into DB
                $checkIDInDB= DB::table('aadhar_check_masters')->where(['aadhar_number'=>$aadhar_number])->count();
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
                            'created_at'        =>date('Y-m-d H:i:s')
                            ];
                    DB::table('aadhar_check_masters')->insert($data);
                            
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
                    
                    $master_data = DB::table('aadhar_check_masters')->select('*')->where(['aadhar_number'=>$aadhar_number])->first();

                }
                
                $pdf = PDF::loadView('guest.verifications.pdf.aadhar', compact('master_data'))
                ->save($path.$file_name); 
            
                DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'success',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            }
            else{
                DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'failed',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
        }

    }

    // check id - pan
    public function idCheckPan($gv_id,$service_id)
    {        
        $business_id = Auth::user()->business_id;
        $user_id=Auth::user()->id;

        $price=50;

        $parent_id=Auth::user()->parent_id;
        $path=public_path().'/guest/reports/pdf/';

        // dd($gv_id);

        $guest_v_s=DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->first();

        // dd($guest_v_s);

        $file_name='pan-'.$guest_v_s->candidate_id.date('Ymdhis').".pdf";
        
        $data=json_decode($guest_v_s->service_number,true);

        $pan_number=$data['pan_number'];
        
            //check first into master table
            $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$pan_number])->first();
            
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
                    'candidate_id'      => $guest_v_s->candidate_id,
                    'service_id'        => $service_id,
                    'source_type'       =>'SystemDb',
                    'price'             =>$price,
                    'used_by'           =>'guest',
                    'user_id'            => $user_id,
                    'created_at'=>date('Y-m-d H:i:s')
                    ];
            
                DB::table('pan_checks')->insert($data);

                $pdf = PDF::loadView('guest.verifications.pdf.pan', compact('master_data'))
                ->save($path.$file_name); 
            
                DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'success',
                    'file_name' => $file_name,
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
                            'candidate_id'      => $guest_v_s->candidate_id,
                            'service_id'        => $service_id,
                            'source_type'       =>'API',
                            'price'             =>$price,
                            'used_by'           =>'guest',
                            'user_id'            => $user_id,
                            'created_at'=>date('Y-m-d H:i:s')
                            ];
                    
                        DB::table('pan_checks')->insert($data);
                        
                        $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$pan_number])->first();
                    }

                    $pdf = PDF::loadView('guest.verifications.pdf.pan', compact('master_data'))
                            ->save($path.$file_name); 
                        
                    DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                        'price' => $price,
                        'status' => 'success',
                        'file_name' => $file_name,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                }else{
                    DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                        'price' => $price,
                        'status' => 'failed',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                
            }


    }

    // check id - Voter ID
    public function idCheckVoterID($gv_id,$service_id)
    {   

        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;

        $price=50;

        $parent_id=Auth::user()->parent_id;


            // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
            // {
            //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
            //     $parent_id=$users->parent_id;
            // }

        $path=public_path().'/guest/reports/pdf/';

        $guest_v_s=DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->first();

        $file_name='voter_id-'.$guest_v_s->candidate_id.date('Ymdhis').".pdf";
        
        $data=json_decode($guest_v_s->service_number,true);

        $voter_id_number=$data['voter_id_number'];
        //check first into master table
        $master_data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$voter_id_number])->first();
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
                'candidate_id'      => $guest_v_s->candidate_id,
                'service_id'        =>$service_id,
                'source_reference'  =>'SystemDb',
                'price'             =>$price,
                'used_by'           =>'guest',
                'user_id'            => $user_id,
                'created_at'        =>date('Y-m-d H:i:s')
                ];

            DB::table('voter_id_checks')->insert($log_data);

            $pdf = PDF::loadView('guest.verifications.pdf.voter-id', compact('master_data'))
                ->save($path.$file_name); 
            
                DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'success',
                    'file_name' => $file_name,
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
                        'candidate_id'      => $guest_v_s->candidate_id,
                        'service_id'        =>$service_id,
                        'source_reference'  =>'API',
                        'price'             =>$price,
                        'used_by'           =>'guest',
                        'user_id'            => $user_id,
                        'created_at'        =>date('Y-m-d H:i:s')
                        ];

                    DB::table('voter_id_checks')->insert($log_data);
                    
                    $master_data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$voter_id_number])->first();
                }

                $pdf = PDF::loadView('guest.verifications.pdf.voter-id', compact('master_data'))
                ->save($path.$file_name); 
            
                DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'success',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            }else{
                DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'failed',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
        }

        

    }

    // check id - RC
    public function idCheckRC($gv_id,$service_id)
    {        
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;

        $price=50;

        $parent_id=Auth::user()->parent_id;
        // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        // {
        //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
        //     $parent_id=$users->parent_id;
        // }
        
        $path=public_path().'/guest/reports/pdf/';

        $guest_v_s=DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->first();

        $file_name='rc-'.$guest_v_s->candidate_id.date('Ymdhis').".pdf";
        
        $data=json_decode($guest_v_s->service_number,true);

        $rc_number=$data['rc_number'];

            //check first into master table
            $master_data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$rc_number])->first();
            if($master_data !=null){
                $data = $master_data;
                $log_data = [
                    'parent_id'         =>$parent_id,
                    'business_id'       => $business_id,
                    'candidate_id'      => $guest_v_s->candidate_id,
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

                $pdf = PDF::loadView('guest.verifications.pdf.rc', compact('master_data'))
                ->save($path.$file_name); 
            
                DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'success',
                    'file_name' => $file_name,
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

                        DB::table('rc_check_masters')->insert($data);
                        
                        $master_data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$rc_number])->first();

                        $log_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       => $business_id,
                            'candidate_id'      => $guest_v_s->candidate_id,
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

                    $pdf = PDF::loadView('guest.verifications.pdf.rc', compact('master_data'))
                            ->save($path.$file_name); 
            
                    DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                        'price' => $price,
                        'status' => 'success',
                        'file_name' => $file_name,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                }else{
                    DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                        'price' => $price,
                        'status' => 'failed',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                
            }

    }

    // check id - Passport
    public function idCheckPassport($gv_id,$service_id){  
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        

        $price=50.00;

        $parent_id=Auth::user()->parent_id;
        // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        // {
        //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
        //     $parent_id=$users->parent_id;
        // }
        $path=public_path().'/guest/reports/pdf/';

        $guest_v_s=DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->first();

        $file_name='passport-'.$guest_v_s->candidate_id.date('Ymdhis').".pdf";
        
        $data=json_decode($guest_v_s->service_number,true);

        $file_number=$data['file_number'];

        $dob=$data['dob'];

            //check first into master table
            $master_data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$file_number])->first();
            if($master_data !=null){

                $log_data = [
                    'parent_id'         =>$parent_id,
                    'business_id'       =>$business_id,
                    'candidate_id'      => $guest_v_s->candidate_id,
                    'service_id'        =>$service_id,
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
                    'used_by'           => 'guest',
                    'user_id'            => $user_id,
                    'created_at'        =>date('Y-m-d H:i:s')
                    ];

                    $pdf = PDF::loadView('guest.verifications.pdf.passport', compact('master_data'))
                    ->save($path.$file_name); 
                
                    DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                        'price' => $price,
                        'status' => 'success',
                        'file_name' => $file_name,
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
                    $checkIDInDB= DB::table('passport_check_masters')->where(['file_number'=>$file_number])->count();
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

                        DB::table('passport_check_masters')->insert($data);
                        
                        $master_data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$file_number])->first();

                        $log_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       =>$business_id,
                            'candidate_id'      => $guest_v_s->candidate_id,
                            'service_id'        =>$service_id,
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
                            'used_by'           => 'guest',
                            'user_id'            => $user_id,
                            'created_at'        =>date('Y-m-d H:i:s')
                            ];
        
                        DB::table('passport_checks')->insert($log_data);
                    }

                    $pdf = PDF::loadView('guest.verifications.pdf.passport', compact('master_data'))
                    ->save($path.$file_name); 
                
                    DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                        'price' => $price,
                        'status' => 'success',
                        'file_name' => $file_name,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                }else{
                    DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                        'price' => $price,
                        'status' => 'failed',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                
            }


    }

    // check id - DL
    public function idCheckDL($gv_id,$service_id)
    {        
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $price=50;

        $parent_id=Auth::user()->parent_id;
        // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        // {
        //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
        //     $parent_id=$users->parent_id;
        // }

        $path=public_path().'/guest/reports/pdf/';

        $guest_v_s=DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->first();

        $file_name='dl-'.$guest_v_s->candidate_id.date('Ymdhis').".pdf";
        
        $data=json_decode($guest_v_s->service_number,true);

        $dl_number=$data['dl_number'];
        
            //check first into master table
            $master_data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$dl_number])->first();
            
            if($master_data !=null){

                $log_data = [
                    'parent_id'         =>$parent_id,
                    'business_id'       => $business_id,
                    'candidate_id'      => $guest_v_s->candidate_id,
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
                $pdf = PDF::loadView('guest.verifications.pdf.dl', compact('master_data'))
                ->save($path.$file_name); 
            
                DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'success',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            else{
                //check from live API
                // Setup request to send json via POST
                $data = array(
                    'id_number'    => $dl_number,
                    'async'         => true,
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
                    $checkIDInDB= DB::table('dl_check_masters')->where(['dl_number'=>$dl_number])->count();
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
                        
                        $master_data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$dl_number])->first();

                        $log_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       => $business_id,
                            'candidate_id'      => $guest_v_s->candidate_id,
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

                    $pdf = PDF::loadView('guest.verifications.pdf.dl', compact('master_data'))
                            ->save($path.$file_name); 
                        
                    DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                        'price' => $price,
                        'status' => 'success',
                        'file_name' => $file_name,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                }else{
                    DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                        'price' => $price,
                        'status' => 'failed',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                
            }


    }

    // check id - bank
    public function idCheckBankAccount($gv_id,$service_id)
    {    
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $price=50;

        $parent_id=Auth::user()->parent_id;
        // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        // {
        //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
        //     $parent_id=$users->parent_id;
        // }
        $path=public_path().'/guest/reports/pdf/';

        $guest_v_s=DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->first();

        $file_name='bank-'.$guest_v_s->candidate_id.date('Ymdhis').".pdf";
        
        $data=json_decode($guest_v_s->service_number,true);

        $account_number=$data['account_number'];

        $ifsc_code=$data['ifsc_code'];

            //check first into master table
            $master_data = DB::table('bank_account_check_masters')->select('*')->where(['account_number'=>$account_number])->first();
            if($master_data !=null){
               

                $log_data = [
                    'parent_id'         =>$parent_id,
                    'business_id'       =>$business_id,
                    'candidate_id'      => $guest_v_s->candidate_id,
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

                $pdf = PDF::loadView('guest.verifications.pdf.bank-verification', compact('master_data'))
                        ->save($path.$file_name); 
            
                DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                    'price' => $price,
                    'status' => 'success',
                    'file_name' => $file_name,
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

                        DB::table('bank_account_check_masters')->insert($data);
                        
                        $master_data = DB::table('bank_account_check_masters')->select('*')->where(['account_number'=>$account_number])->first();

                        $log_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       =>$business_id,
                            'candidate_id'      => $guest_v_s->candidate_id,
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

                    $pdf = PDF::loadView('guest.verifications.pdf.bank-verification', compact('master_data'))
                            ->save($path.$file_name); 
                        
                    DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                        'price' => $price,
                        'status' => 'success',
                        'file_name' => $file_name,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                }else{
                    DB::table('guest_verification_services')->where(['gv_id'=>$gv_id,'service_id'=>$service_id])->update([
                        'price' => $price,
                        'status' => 'failed',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                
            }

    }

    // Settings

    public function Settings(Request $request)
    {
        return view('guest.accounts.settings.index');
    }

    public function purgeDataUpdate(Request $request)
    {
        $user_id = Auth::user()->id;

        $business_id = Auth::user()->business_id;

        $rules= 
        [
            'purge_data' => 'required_if:purge_check,on|nullable|integer|gte:7',
        ];

        $custom=[
            'purge_data.required_if' => 'Purge Data Field is required',
            'purge_data.integer' => 'Purge Data must be numeric.',
            'purge_data.gte' => 'Purge Data must be atleast 7 days.'
        ];
        $validator = Validator::make($request->all(), $rules,$custom);
        
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        DB::beginTransaction();
        try{

            if($request->has('purge_check') || $request->purge_check!=NULL )
            {
                $purge_day = 7;
                
                if($request->has('purge_data') && $request->purge_data !='' && $request->purge_data >=7)
                {
                    $purge_day = $request->purge_data;
                }

                DB::table('users')->where(['id'=>$user_id])->update([
                    'is_purged' => '1',
                    'purge_days' => $purge_day,
                    'purge_date' => date('Y-m-d H:i:s')
                ]);
            }
            else
            {
                DB::table('users')->where(['id'=>$user_id])->update([
                    'is_purged' => '0',
                ]);

                DB::table('purge_data_logs')->where(['business_id'=>$business_id,'module_type'=>'purge-notify'])->delete();
            }

            DB::commit();
            return response()->json([
                'success' =>true,
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 

    }

    public function invoicePDF(Request $request)
    {
        $master_data=DB::table('guest_instant_masters')->where(['id'=>35])->first();

        $pdf = PDF::loadView('guest.instantverification.payment.invoice', compact('master_data'));

        return $pdf->stream('invoice.pdf');
    }

    


}
