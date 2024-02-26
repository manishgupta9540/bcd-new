<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class BillingController extends Controller
{
    //

    /*** Show the business info 
     * ** @return \Illuminate\Http\Response*/
    
    public function billing(Request $request)
    {
        // $profile = User::find(Auth::user()->id);

        // $business_id = Auth::user()->business_id;

        // $countries = DB::table('countries')->get();

        // $business = DB::table('user_businesses as b')
        // ->select('b.*')
        // ->where(['b.business_id'=>$business_id])
        // ->first();

            $business_id=Auth::user()->business_id;
            $billings=DB::table('billings as b')
            ->select('b.*','u.name','ub.company_name')
            ->join('users as u','u.id','=','b.business_id')
            ->join('user_businesses as ub','ub.business_id','=','u.id')
            ->where(['b.business_id'=>$business_id])
            ->whereNotIn('b.status',['draft']);
            if($request->get('from_date') !=""){
                $billings->whereDate('b.start_date','>=',date('Y-m-d',strtotime($request->get('from_date'))));
            }
            if($request->get('to_date') !=""){
                $billings->whereDate('b.end_date','<=',date('Y-m-d',strtotime($request->get('to_date'))));
            }
            if($request->get('status') !=""){
                $billings->where('b.status',$request->get('status'));
            }
            $billings=$billings->orderBy('b.created_at','desc')->paginate(10);
        
        if($request->ajax())
            return view('clients.billing.ajax', compact('billings'));
        else
            return view('clients.billing.index', compact('billings'));
    }

    public function billApproveSendRequestDetails(Request $request)
    {
            $billing_approval_id = base64_decode($request->id);

            $billing_approval = DB::table('billing_approvals as ba')
                                ->select('b.invoice_id','u.name','ub.company_name','b.start_date','b.end_date','ba.request_sent_notes as comments')
                                ->join('billings as b','b.id','=','ba.billing_id')
                                ->join('users as u','u.id','=','b.parent_id')
                                ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                                ->where(['ba.id'=>$billing_approval_id])
                                ->first();

            $duration = '';

            $form = '';

            
            $duration.='<div class="form-group">
                        <label for="label_name"> Duration: </label>
                        <span class="dur">('.date('d M',strtotime($billing_approval->start_date)).' - '.date('d M',strtotime($billing_approval->end_date)).') '.date('Y',strtotime($billing_approval->start_date)).'</span>
                    </div>';

            $bill_app_attachments=DB::table('billing_approval_attachments')->where(['billing_approval_id'=>$billing_approval_id,'request_type'=>'sent'])->get();

            if(count($bill_app_attachments)>0)
            {
                $path=url('/').'/uploads/billings/approval-attachment/';
                $form.='<div class="form-group">
                        <label><strong>Attachments Files: </strong></label>
                        <div class="row mt-2" style="min-height: 20px;">';
                foreach($bill_app_attachments as $item)
                {
                    $img='';
                    $file=$path.$item->file_name;
                    if(strpos($item->file_name, 'pdf')!==false) {
                        $img='<img src="'.url("/")."/admin/images/icon_pdf.png".'" alt="preview" style="height:100px;"/>';
                    }
                    else
                    {
                        $img='<img src="'.$file.'" alt="preview" style="height:100px;"/>';
                    }
                    $form.='<div class="image-area">
                                <a href="'.$file.'" download> 
                                    '.$img.'
                                </a>
                            </div>';
                }

                $form.='</div>
                        </div>';
            }
            
            return response()->json([                
                'result' => $billing_approval,
                'duration' => $duration,
                'form' => $form
            ]);
    }

    public function billingApproveStatus(Request $request)
    {
        $user_id = Auth::user()->id;
        $id = base64_decode($request->id);
        $type = base64_decode($request->type);
        $business_id = Auth::user()->business_id;
        $cust_name = '';
        
            if($type=='approve')
            {
                if ($request->isMethod('get'))
                {
                    $duration = '';
                    $form = '';
                    $stars = '';

                    $billing_approval = DB::table('billing_approvals as ba')
                                ->select('b.invoice_id','u.name','ub.company_name','b.start_date','b.end_date','ba.request_approve_by_coc_notes as comments','ba.request_approve_by_coc_stars as stars','ba.id','ba.request_approve_by_coc_id as request_approve_by')
                                ->join('billings as b','b.id','=','ba.billing_id')
                                ->join('users as u','u.id','=','b.business_id')
                                ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                                ->where(['ba.id'=>$id])
                                ->first();

                    // Check for Approval By COC
                    if($billing_approval!=NULL)
                    {
                        
                        $user = DB::table('users as u')
                                    ->select('u.*','ub.company_name')
                                    ->join('user_businesses as ub','ub.business_id','=','u.business_id')
                                    ->where('u.id',$billing_approval->request_approve_by)
                                    ->first();
                        if($user!=NULL)
                            $cust_name = $user->company_name;
                    }

                    for($i=0;$i<5;$i++)
                    {
                        if($billing_approval->stars == $i + .5)
                            $stars.= '<i class="fa fa-star-half" aria-hidden="true" style="color: green;"></i>';
                        else if($billing_approval->stars <= $i)
                            $stars.='<i class="fa fa-star-o" aria-hidden="true" style="color: green;"></i>';
                        else
                            $stars.='<i class="fa fa-star" aria-hidden="true" style="color: green;"></i>'; 
                    }
        
                    $bill_app_attachments=DB::table('billing_approval_attachments')->where(['billing_approval_id'=>$billing_approval->id,'request_type'=>'approve','user_type'=>'coc'])->get();
        
                    if(count($bill_app_attachments)>0)
                    {
                        $path = url('/').'/uploads/billings/approval-attachment/';
                        $form.='<div class="form-group">
                                <label><strong>Attachments Files: </strong></label>
                                <div class="row mt-2" style="min-height: 20px;">';
                        foreach($bill_app_attachments as $item)
                        {
                            $img='';
                            $file=$path.$item->file_name;
                            if(strpos($item->file_name, 'pdf')!==false) {
                                $img='<img src="'.url("/")."/admin/images/icon_pdf.png".'" alt="preview" style="height:100px;"/>';
                            }
                            else
                            {
                                $img='<img src="'.$file.'" alt="preview" style="height:100px;"/>';
                            }
                            $form.='<div class="image-area">
                                        <a href="'.$file.'" download> 
                                            '.$img.'
                                        </a>
                                    </div>';
                        }
        
                        $form.='</div>
                                </div>';
                    }
                    
                    $duration.='<div class="form-group">
                                <label for="label_name"> Duration: </label>
                                <span class="dur">('.date('d M',strtotime($billing_approval->start_date)).' - '.date('d M',strtotime($billing_approval->end_date)).') '.date('Y',strtotime($billing_approval->start_date)).'</span>
                            </div>';
                    
                    return response()->json([   
                        'cust_name' => $cust_name,             
                        'result' => $billing_approval,
                        'duration' => $duration,
                        'stars' => $stars,
                        'form' => $form,
                        'type' => $type
                    ]);
                }

                $rules= [
                    'rating' => 'required|numeric|min:0.5|max:5',
                    'comments' => 'required|min:1',
                 ];
        
                
                 $validator = Validator::make($request->all(), $rules);
                  
                 if ($validator->fails()){
                     return response()->json([
                         'fail' => true,
                         'error_type' => 'validation',
                         'errors' => $validator->errors()
                     ]);
                 }

                DB::beginTransaction();
                try{

                     // Validation For Attachement
                     $allowedextension=['jpg','jpeg','png','gif','svg','pdf','JPG','PDF','JPEG','PNG'];

                     if($request->hasFile('attachment') && $request->file('attachment') !="")
                     { 
                         $files= $request->file('attachment');
                         foreach($files as $file)
                         {
                                 $extension = $file->getClientOriginalExtension();
 
                                 $check = in_array($extension,$allowedextension);
 
                                 if(!$check)
                                 {
                                     return response()->json([
                                         'fail' => true,
                                         'errors' => ['attachment' => 'Only jpg,jpeg,png,pdf are allowed !'],
                                         'error_type'=>'validation'
                                     ]);                        
                                 }
                         }
                     }

                    $bill_app= DB::table('billing_approvals')->where(['id'=>$id])->first();

                    $billing = DB::table('billings')->where(['id'=>$bill_app->billing_id])->first();

                    if($bill_app->request_approve_by_coc_id==NULL && $bill_app->request_cancel_by==NULL)
                    {
                        DB::table('billing_approvals')->where(['id'=>$id])->update([
                            'request_approve_by_coc_id' => $user_id,
                            'request_approve_by_coc_stars' => $request->rating,
                            'request_approve_by_coc_notes' => $request->comments,
                            'request_approve_by_coc_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);

                        DB::table('billing_approval_actions')->insert([
                            'billing_id'   => $bill_app->billing_id,
                            'business_id'  => $bill_app->business_id,
                            'notes'        => $request->comments,
                            'stars'        => $request->rating,
                            'action_type'   => 'approve',
                            'created_by'    => $user_id,
                            'user_type'     => 'coc',
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
    
                        if($request->hasFile('attachment') && $request->file('attachment') !="")
                        {
                            $filePath = public_path('/uploads/billings/approval-attachment/');
                            
                            if (!File::exists($filePath)) {
                                File::makeDirectory($filePath, $mode = 0777, true, true);
                            }
    
                            foreach($files as $file)
                            {
                                $file_data = $file->getClientOriginalName();
                                $tmp_data  = date('YmdHis').'-'.$file_data; 
                                $data = $file->move($filePath, $tmp_data);
                                
                                DB::table('billing_approval_attachments')->insert(
                                    [
                                        'billing_id' => $bill_app->billing_id,
                                        'billing_approval_id' => $bill_app->id,
                                        'business_id' => $bill_app->business_id,
                                        'file_name' => $tmp_data,
                                        'request_type'  => 'approve',
                                        'created_by' => $user_id,
                                        'user_type' => 'coc',
                                        'created_at' => date('Y-m-d H:i:s')
                                    ]
                                );
                            }
                        }

                        // $user_d = DB::table('users')->where(['id'=>$billing->parent_id])->first();

                        // $name=$user_d->name;
            
                        // $email=$user_d->email;
            
                        // $msg = 'Customer ('.Helper::company_name($billing->business_id).') has approved the request about the billing.';

                        // $sender = DB::table('users')->where(['id'=>$business_id])->first();
                
                        // $data=['name' =>$name,'email' => $email,'user'=>$user_d,'billing'=>$billing,'comments'=>$request->comments,'msg'=>$msg,'sender'=>$sender];
                
                        // Mail::send(['html'=>'mails.billing-approve_request'], $data, function($message) use($email,$name) {
                        //     $message->to($email, $name)->subject
                        //         ('myBCD System - Billing Approve Request Notification');
                        //     $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        // });
        
                        DB::commit();
    
                        return response()->json([
                            'fail' => false,
                            'type' => $type
                        ]);
    

                        // return response()->json([
                        //     'status'    => 'ok',
                        //     'success'    => true,
                        //     'type' => $type
                        // ]);
                    }
                }
                catch(\Exception $e){
                    DB::rollback();
                    // something went wrong
                    return $e;
                }

               
            }
            else
            {
                if ($request->isMethod('get'))
                {
                    $duration = '';
                    $form = '';

                    $billing_approval = DB::table('billing_approvals as ba')
                                ->select('b.invoice_id','b.start_date','b.end_date','ba.request_cancel_notes as comments','ba.id','ba.request_cancel_by','u.name','ub.company_name')
                                ->join('billings as b','b.id','=','ba.billing_id')
                                ->join('users as u','b.business_id','=','u.business_id')
                                ->join('user_businesses as ub','ub.business_id','=','u.business_id')
                                ->where(['ba.id'=>$id])
                                ->first();

                    // Check for Cancel By Whom
                    if($billing_approval!=NULL)
                    {
                        $user = DB::table('users as u')
                                    ->select('u.*','ub.company_name')
                                    ->join('user_businesses as ub','ub.business_id','=','u.business_id')
                                    ->where('u.id',$billing_approval->request_cancel_by)
                                    ->first();
                        if($user!=NULL)
                            $cust_name = $user->company_name;
                    }
        
                    $bill_app_attachments=DB::table('billing_approval_attachments')->where(['billing_approval_id'=>$billing_approval->id,'request_type'=>'cancel'])->get();
        
                    if(count($bill_app_attachments)>0)
                    {
                        $path = url('/').'/uploads/billings/approval-attachment/';
                        $form.='<div class="form-group">
                                <label><strong>Attachments Files: </strong></label>
                                <div class="row mt-2" style="min-height: 20px;">';
                        foreach($bill_app_attachments as $item)
                        {
                            $img='';
                            $file=$path.$item->file_name;
                            if(strpos($item->file_name, 'pdf')!==false) {
                                $img='<img src="'.url("/")."/admin/images/icon_pdf.png".'" alt="preview" style="height:100px;"/>';
                            }
                            else
                            {
                                $img='<img src="'.$file.'" alt="preview" style="height:100px;"/>';
                            }
                            $form.='<div class="image-area">
                                        <a href="'.$file.'" download> 
                                            '.$img.'
                                        </a>
                                    </div>';
                        }
        
                        $form.='</div>
                                </div>';
                    }
                    
                    $duration.='<div class="form-group">
                                <label for="label_name"> Duration: </label>
                                <span class="dur">('.date('d M',strtotime($billing_approval->start_date)).' - '.date('d M',strtotime($billing_approval->end_date)).') '.date('Y',strtotime($billing_approval->start_date)).'</span>
                            </div>';
                    
                    return response()->json([ 
                        'cust_name' => $cust_name,               
                        'result' => $billing_approval,
                        'duration' => $duration,
                        'form' => $form,
                        'type' => $type
                    ]);
                }

                 $rules= [
                    'comments' => 'required|min:1',
                 ];
        
                
                 $validator = Validator::make($request->all(), $rules);
                  
                 if ($validator->fails()){
                     return response()->json([
                         'fail' => true,
                         'error_type' => 'validation',
                         'errors' => $validator->errors()
                     ]);
                 }

                DB::beginTransaction();
                try{
                    // Validation For Attachement
                    $allowedextension=['jpg','jpeg','png','gif','svg','pdf','JPG','PDF','JPEG','PNG'];

                    if($request->hasFile('attachment') && $request->file('attachment') !="")
                    { 
                        $files= $request->file('attachment');
                        foreach($files as $file)
                        {
                                $extension = $file->getClientOriginalExtension();

                                $check = in_array($extension,$allowedextension);

                                if(!$check)
                                {
                                    return response()->json([
                                        'fail' => true,
                                        'errors' => ['attachment' => 'Only jpg,jpeg,png,pdf are allowed !'],
                                        'error_type'=>'validation'
                                    ]);                        
                                }
                        }
                    }

                    $billing_approval = DB::table('billing_approvals')->where(['id'=>$id])->latest()->first();

                    $billing = DB::table('billings')->where(['id'=>$billing_approval->billing_id])->first();

                    DB::table('billing_approvals')->where(['id'=>$id])->update([
                        'request_cancel_by' => $user_id,
                        'request_cancel_notes'   => $request->comments,
                        'request_cancel_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                    DB::table('billing_approval_actions')->insert([
                        'billing_id'   => $billing_approval->billing_id,
                        'business_id'  => $billing_approval->business_id,
                        'notes'        => $request->comments,
                        'action_type'   => 'cancel',
                        'created_by'    => $user_id,
                        'user_type'     => 'coc',
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    if($request->hasFile('attachment') && $request->file('attachment') !="")
                    {
                        $filePath = public_path('/uploads/billings/approval-attachment/');
                        
                        if (!File::exists($filePath)) {
                            File::makeDirectory($filePath, $mode = 0777, true, true);
                        }

                        foreach($files as $file)
                        {
                            $file_data = $file->getClientOriginalName();
                            $tmp_data  = date('YmdHis').'-'.$file_data; 
                            $data = $file->move($filePath, $tmp_data);
                            
                            DB::table('billing_approval_attachments')->insert(
                                [
                                    'billing_id' => $billing_approval->billing_id,
                                    'billing_approval_id' => $billing_approval->id,
                                    'business_id' => $billing_approval->business_id,
                                    'file_name' => $tmp_data,
                                    'request_type'  => 'cancel',
                                    'created_by' => $user_id,
                                    'user_type' => 'coc',
                                    'created_at' => date('Y-m-d H:i:s')
                                ]
                            );
                        }
                    }

                    // $user_d = DB::table('users')->where(['id'=>$billing->parent_id])->first();

                    // $name=$user_d->name;
        
                    // $email=$user_d->email;
        
                    // $msg = 'Customer ('.Helper::company_name($billing->business_id).') has been returned for review about the billing.';

                    // $sender = DB::table('users')->where(['id'=>$business_id])->first();
            
                    // $data=['name' =>$name,'email' => $email,'user'=>$user_d,'billing'=>$billing,'comments'=>$request->comments,'msg'=>$msg,'sender'=>$sender];
            
                    // Mail::send(['html'=>'mails.billing-cancel_request'], $data, function($message) use($email,$name) {
                    //     $message->to($email, $name)->subject
                    //         ('myBCD System - Billing Return Review Notification');
                    //     $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                    // });

                    DB::commit();

                    return response()->json([
                        'fail' => false,
                        'type' => $type
                    ]);

                    // return response()->json([
                    //     'status'    => 'ok',
                    //     'success'    => true,
                    //     'type' => $type
                    // ]);
                }
                catch(\Exception $e){
                    DB::rollback();
                    // something went wrong
                    return $e;
                }

            }

        // return response()->json([
        //     'status'    => 'no',
        //     'message' => 'Something Went Wrong !!'
        // ]);

    }

    public function billingCompleteDetails(Request $request)
    {
            $id =  base64_decode($request->id);
            $user_id = Auth::user()->id;
            $cust_name = '';
            $duration = '';
            $form = '';
            $billing_approval = DB::table('billing_approvals as ba')
                        ->select('b.invoice_id','u.name','ub.company_name','b.start_date','b.end_date','ba.request_approve_by_cust_notes as comments','ba.id','ba.request_approve_by_cust_id as request_approve_by')
                        ->join('billings as b','b.id','=','ba.billing_id')
                        ->join('users as u','u.id','=','b.business_id')
                        ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                        ->where(['ba.id'=>$id])
                        ->first();
            // Check for Approval By Admin
            if($billing_approval!=NULL)
            {
                $user = DB::table('users as u')
                                    ->select('u.*','ub.company_name')
                                    ->join('user_businesses as ub','ub.business_id','=','u.business_id')
                                    ->where('u.id',$billing_approval->request_approve_by)
                                    ->first();
                        if($user!=NULL)
                            $cust_name = $user->company_name;
            }
            
           $bill_app_attachments=DB::table('billing_approval_attachments')->where(['billing_approval_id'=>$billing_approval->id,'user_type'=>'customer','request_type'=>'approve'])->get();

            if(count($bill_app_attachments)>0)
            {
                $path = url('/').'/uploads/billings/approval-attachment/';
                $form.='<div class="form-group">
                        <label><strong>Attachments Files: </strong></label>
                        <div class="row mt-2" style="min-height: 20px;">';
                foreach($bill_app_attachments as $item)
                {
                    $img='';
                    $file=$path.$item->file_name;
                    if(strpos($item->file_name, 'pdf')!==false) {
                        $img='<img src="'.url("/")."/admin/images/icon_pdf.png".'" alt="preview" style="height:100px;"/>';
                    }
                    else
                    {
                        $img='<img src="'.$file.'" alt="preview" style="height:100px;"/>';
                    }
                    $form.='<div class="image-area">
                                <a href="'.$file.'" download> 
                                    '.$img.'
                                </a>
                            </div>';
                }

                $form.='</div>
                        </div>';
            }
            
            $duration.='<div class="form-group">
                        <label for="label_name"> Duration: </label>
                        <span class="dur">('.date('d M',strtotime($billing_approval->start_date)).' - '.date('d M',strtotime($billing_approval->end_date)).') '.date('Y',strtotime($billing_approval->start_date)).'</span>
                    </div>';
            
            return response()->json([ 
                'cust_name' => $cust_name,               
                'result' => $billing_approval,
                'duration' => $duration,
                'form' => $form
            ]);

    }

    public function billingActionDetails(Request $request)
    {
            $billing_id =  base64_decode($request->id);
            $user_id = Auth::user()->id;

            $duration = '';
            $form = '';
            $billing = DB::table('billings as b')
                        ->select('b.invoice_id','u.name','ub.company_name','b.start_date','b.end_date')
                        ->join('users as u','u.id','=','b.business_id')
                        ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                        ->where(['b.id'=>$billing_id])
                        ->first();

            $billing_actions = DB::table('billing_approval_actions')->where(['billing_id'=>$billing_id])->get();

            if(count($billing_actions)>0)
            {
                foreach($billing_actions as $item)
                {
                    $user_d = DB::table('users')->where(['id'=>$item->created_by])->first();
                    if(stripos($item->action_type,'sent')!==false)
                    {
                        $form.='<div class="row">
                                    <div class="col-12">
                                        <h5 class="text-muted" style="font-weight: bold;">Send Request Details:-</h5>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Sent By: </label>
                                            <span>'.Helper::company_name($user_d->business_id).'</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Comments: </label>
                                            <span class="text-justify">'.$item->notes.'</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Date & Time: </label>
                                            <span class="text-justify">'.date('d-M-y h:i A',strtotime($item->created_at)).'</span>
                                        </div>
                                    </div>
                                </div>
                                <p class="pb-border"></p>';
                    }
                    else if(stripos($item->action_type,'cancel')!==false)
                    {
                        $form.='<div class="row">
                                    <div class="col-12">
                                        <h5 class="text-muted" style="font-weight: bold;">Return Review Details:-</h5>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Return Review By: </label>
                                            <span>'.Helper::company_name($user_d->business_id).'</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Comments: </label>
                                            <span class="text-justify">'.$item->notes.'</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Date & Time: </label>
                                            <span class="text-justify">'.date('d-M-y h:i A',strtotime($item->created_at)).'</span>
                                        </div>
                                    </div>
                                </div>
                                <p class="pb-border"></p>';
                    }
                    else if(stripos($item->action_type,'approve')!==false)
                    {
                        if(stripos($item->user_type,'coc')!==false)
                        {
                            $stars = '';

                            for($i=0;$i<5;$i++)
                            {
                                if($item->stars == $i + .5)
                                    $stars.= '<i class="fa fa-star-half" aria-hidden="true" style="color: green;"></i>';
                                else if($item->stars <= $i)
                                    $stars.='<i class="fa fa-star-o" aria-hidden="true" style="color: green;"></i>';
                                else
                                    $stars.='<i class="fa fa-star" aria-hidden="true" style="color: green;"></i>'; 
                            }

                            $form.='<div class="row">
                                        <div class="col-12">
                                            <h5 class="text-muted" style="font-weight: bold;">Approve Details:-</h5>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Approve By: </label>
                                                <span>'.Helper::company_name($user_d->business_id).'</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="label_name"> Rating: </label>
                                                <span class="stars">'.$stars.'</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Comments: </label>
                                                <span class="text-justify">'.$item->notes.'</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Date & Time: </label>
                                                <span class="text-justify">'.date('d-M-y h:i A',strtotime($item->created_at)).'</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="pb-border"></p>';
                        }
                        else
                        {
                            $form.='<div class="row">
                                        <div class="col-12">
                                            <h5 class="text-muted" style="font-weight: bold;">Approve Details:-</h5>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Approve By: </label>
                                                <span>'.Helper::company_name($user_d->business_id).'</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Comments: </label>
                                                <span class="text-justify">'.$item->notes.'</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Date & Time: </label>
                                                <span class="text-justify">'.date('d-M-y h:i A',strtotime($item->created_at)).'</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="pb-border"></p>';
                        }
                    }
                }
            }

            $duration.='<div class="form-group">
                            <label for="label_name"> Duration: </label>
                            <span class="dur">('.date('d M',strtotime($billing->start_date)).' - '.date('d M',strtotime($billing->end_date)).') '.date('Y',strtotime($billing->start_date)).'</span>
                        </div>';

            return response()->json([                
                'result' => $billing,
                'duration' => $duration,
                'form' => $form
            ]);
    }

    public function billing_details(Request $request,$id)
    {
            $parent_id = Auth::user()->parent_id;

            $business_id=Auth::user()->business_id;

            $billing_id=base64_decode($id);

            $billing_detail_candidate=DB::table('billing_items as bi')
                                        ->DISTINCT('bi.candidate_id')
                                        ->select('bi.candidate_id','bi.business_id',DB::raw('sum(bi.quantity) as total_quantity'),DB::raw('sum(bi.additional_charges) as total_additional_charges'),DB::raw('sum(bi.final_total_check_price) as total_check_price'))
                                        ->groupBy('bi.candidate_id')
                                        ->where(['bi.billing_id'=>$billing_id])
                                        ->whereNotNull('bi.candidate_id');
            // if(is_numeric($request->get('customer_id'))){
            //     $billing_details->where('bi.service_id',$request->get('customer_id'));
            // }
            // if($request->get('from_date') !=""){
            //     $billing_details->whereDate('bi.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
            // }
            //   if($request->get('to_date') !=""){
            //     $billing_details->whereDate('bi.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
            // }
            $billing_detail_candidate=$billing_detail_candidate->get();

            $billing_detail_api = DB::table('billing_items as bi')
                                    ->select('bi.business_id',DB::raw('sum(bi.quantity) as total_quantity'),DB::raw('sum(bi.additional_charges) as total_additional_charges'),DB::raw('sum(bi.final_total_check_price) as total_check_price'))
                                    ->groupBy('bi.candidate_id')
                                    ->where(['bi.billing_id'=>$billing_id])
                                    ->whereNull('bi.candidate_id');
            
            $billing_detail_api=$billing_detail_api->get();

            $items = $billing_detail_candidate->merge($billing_detail_api)->paginate(25);

            // dd($billings);

            // $customers = DB::table('users as u')
            // ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
            // ->join('user_businesses as b','b.business_id','=','u.id')
            // ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
            // ->get();
            $services=DB::table('services as s')
                    ->select('s.name','s.id','s.verification_type')
                    ->where('status','1')
                    ->whereNull('business_id')
                    ->orWhere('business_id',$parent_id)
                    ->get();
            $billing=DB::table('billings as b')->where('id',$billing_id)->first();

        if($request->ajax())
            return view('clients.billing.billing_details_ajax', compact('items','billing','services'));
        else
            return view('clients.billing.billing_details', compact('items','billing','services'));
    }

    public function billingDetailsAdditional(Request $request)
    {
        $id=base64_decode($request->id);

        $data = DB::table('billing_items as bi')
            ->select('bi.*','verification_type')
            ->join('services as s','s.id','=','bi.service_id')
            ->where(['bi.id' => $id])        
            ->first(); 
            $form = '';
            $add_details = '';
            if($data->candidate_id!=NULL)
            {
                $user = DB::table('candidate_reinitiates')->where(['id'=>$data->candidate_id])->first();

                $add_details.='<div class="form-group">
                                    <label for="label_name">Candidate Name :</label>
                                    <span class="c_name" id="c_name">'.$user->name.'</span>
                                </div>
                                <div class="form-group">
                                    <label for="label_name">Ref No. :</label>
                                    <span class="c_no" id="c_no">'.$user->display_id.'</span>
                                </div>';
            }

            $add_chrge_attachments=DB::table('billing_additional_charge_attachments')->where(['billing_details_id'=>$id])->get();

            if(count($add_chrge_attachments)>0)
            {
                $path=url('/').'/uploads/billings/additional-charge/';
                $form.='<div class="form-group">
                        <label><strong>Attachments Files: </strong></label>
                        <div class="row mt-2" style="min-height: 20px;">';
                foreach($add_chrge_attachments as $item)
                {
                    $img='';
                    $file=$path.$item->file_name;
                    if(strpos($item->file_name, 'pdf')!==false) {
                        $img='<img src="'.url("/")."/admin/images/icon_pdf.png".'" alt="preview" style="height:100px;"/>';
                    }
                    else
                    {
                        $img='<img src="'.$file.'" alt="preview" style="height:100px;"/>';
                    }
                    $form.='<div class="image-area">
                                <a href="'.$file.'" download> 
                                    '.$img.'
                                </a>
                            </div>';
                }

                $form.='</div>
                        </div>';
            }
        
            return response()->json([                
                'result' => $data,
                'form' =>  $form,
                'add_detail' => $add_details
            ]);
    }
}
