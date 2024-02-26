<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Razorpay\Api\Api;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;


class WalletController extends Controller
{
    //
    public function userWallet(Request $request)
    {
        //
        $user_id=Auth::user()->id;
        $business_id=Auth::user()->business_id;
        $wallet=DB::table('wallets')->where(['business_id'=>$business_id])->first();
        $wallet_transactions=DB::table('wallet_transactions')->where(['business_id'=>$business_id,'is_payment_done'=>'1'])
        ->orderBy('id','Desc');

        if($request->get('t_id')){
            $wallet_transactions->where('transaction_user_id','like',$request->get('t_id').'%');
        }
        if($request->get('from_date') !=""){
            $wallet_transactions->whereDate('created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
        }
        if($request->get('to_date') !=""){
            $wallet_transactions->whereDate('created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
        }
        $wallet_transactions=$wallet_transactions->paginate(10);

        // dd($wallet_transactions);
        if($request->ajax())
            return view('clients.accounts.wallets.user-wallet_ajax',compact('wallet','wallet_transactions'));
        else
            return view('clients.accounts.wallets.user-wallet',compact('wallet','wallet_transactions'));
    }
    
    public function addMoney(Request $request)
    {
       $business_id=Auth::user()->business_id;
       $user_id=Auth::user()->id;
        $rules = [
            'amount'  => 'required|numeric|min:1|max:5000',                
        ];

        $customRules=[
            'amount.required' => 'Amount Should not be blank and Atleast Rs. 1 required'
        ];
        $validator = Validator::make($request->all(), $rules,$customRules);
        if ($validator->fails())
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors(),
                'error_type'=>'validation'
            ]);

            // $request->validate([
            //     'amount'  => 'required|numeric|min:1|max:5000',
            // ]);
            DB::beginTransaction();
            try
            {
                $user=DB::table('users')->where(['id'=>$business_id])->first();
                // Generate random receipt id
                $receiptId = Str::random(20);
                $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));

                // In razorpay you have to convert rupees into paise we multiply by 100
                // Currency will be INR
                // Creating order
                $order = $api->order->create(array(
                    'receipt' => $receiptId,
                    'amount' => $request->all()['amount'] * 100,
                    'currency' => 'INR',
                    'payment_capture' => true
                    )
                );
                
                
                // Return response on payment page
                // $response = [
                //     'orderId' => $order['id'],
                //     'razorpayId' => env('RAZOR_KEY'),
                //     'amount' => $request->all()['amount'] * 100,
                //     'name' => Auth::user()->name,
                //     'currency' => 'INR',
                //     'email' => Auth::user()->email,
                //     'contactNumber' => Auth::user()->phone,
                //     // 'address' => $request->all()['address'],
                //     'description' => 'Add Money to Wallet',
                // ];

                $wt_id=DB::table('wallet_transactions')->insertGetId([
                    'business_id'   => $business_id,
                    'user_id'       => $user_id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'contactNumber' => $user->phone,
                    'transaction_id'=> $order['id'],
                    'razorpay_id'   => env('RAZOR_KEY'),
                    'currency'      => 'INR',
                    'transaction_type'  =>'credit',
                    'amount'        => $request->all()['amount'],
                    'created_at'    => date('Y-m-d H:i:s')
                ]);

                // Insert into table which returns last_insert_id

                $userID = "TS";
                $transaction_user_id = strtoupper(substr($userID, 0, 3)).date("-ymds").$wt_id;

                DB::table('wallet_transactions')->where(['id'=>$wt_id])->update([
                    'transaction_user_id' => $transaction_user_id,
                ]);
                
                DB::commit();
                // Let's checkout payment page is it working
                return response()->json([
                    'fail'  => false,
                    'order_id'  => base64_encode($order['id']),
                ]);

                // return view('clients.accounts.wallets.payment-page',compact('response'));
            }
            catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return $e;
            }  
    }

    public function payment(Request $request){
        $business_id=Auth::user()->business_id;
        $user_id=Auth::user()->id;
        $order_id = base64_decode($request->order_id);
        // dd($order_id);
        $response=DB::table('wallet_transactions')->where(['business_id'=>$business_id,'transaction_id'=>$order_id])->first();
        // dd($response);
        return view('clients.accounts.wallets.payment-page',compact('response'));
    }

    public function Complete(Request $request)
    {
        $business_id=Auth::user()->business_id;
        $user_id=Auth::user()->id;

        DB::beginTransaction();
        try{
            // Now verify the signature is correct . We create the private function for verify the signature
            $signatureStatus = $this->SignatureVerify(
                $request->all()['rzp_signature'],
                $request->all()['rzp_paymentid'],
                $request->all()['rzp_orderid']
            );
            
            // dd($signatureStatus);
            // If Signature status is true We will save the payment response in our database
            // In this tutorial we send the response to Success page if payment successfully made
            if($signatureStatus == true)
            {
                $wallet=DB::table('wallets')->select('balance')->where(['business_id'=>$business_id])->first();
                // check if record is null
                    if($wallet == null){
                        DB::table('wallets')->insert([
                            'business_id'   => $business_id,
                            'user_id'       => $user_id,
                            'balance'       => '0',
                            'created_at'    =>  date('Y-m-d H:i:s'),
                        ]);
                        //
                        $wallet=DB::table('wallets')->select('balance')->where(['business_id'=>$business_id])->first();
                    }

                $update=DB::table('wallet_transactions')->where(['business_id'=>$business_id,'transaction_id'=>$request->all()['rzp_orderid'],'is_payment_done'=>'0'])->update([
                    'payment_id'    =>  $request->all()['rzp_paymentid'],
                    'payment_source' => 'online',
                    'is_payment_done'   => '1',
                    'payment_done_by' => Auth::user()->id,
                    'notes'             =>'Added Money to Wallet',
                    'updated_at'    => date('Y-m-d H:i:s')
                ]);
                if($update){
                    $wallet_transact=DB::table('wallet_transactions')->where(['business_id'=>$business_id,'transaction_id'=>$request->all()['rzp_orderid']])->first();
                    DB::table('wallets')->where(['business_id'=>$business_id])->update([
                            'balance' => $wallet->balance + $wallet_transact->amount,
                            'updated_at'=>date('Y-m-d H:i:s')]);
                    $wallet_b=DB::table('wallets')->where(['business_id'=>$business_id])->first();
                    $name=$wallet_transact->name;
                    $email=$wallet_transact->email;
                    $data  = array('name'=>$name,'email'=>$email,'amount'=>$wallet_transact->amount,'balance'=>$wallet_b->balance);
                    Mail::send('mails.wallet_notify', $data, function($message) use($email,$name) {
                        $message->to($email)->subject
                            ('myBCD System - Wallet Notification');
                                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                            });
                }
                
                DB::commit();
                // You can create this page
                return view('clients.accounts.wallets.payment-success');
            }
            else{
                // You can create this page
                return view('clients.accounts.wallets.payment-failed');
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
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
}
