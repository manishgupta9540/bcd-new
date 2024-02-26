<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use App\Helpers\Helper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BilldataExport;
use App\Traits\EmailConfigTrait;
use Illuminate\Support\Facades\Session;


use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class BillingController extends Controller
{
    //

    /*** Show the billing history
     * ** @return \Illuminate\Http\Response*/
    
    public function billing(Request $request)
    {
            $business_id=Auth::user()->business_id;
            $billings=DB::table('billings as b')
            ->select('b.*','u.name','ub.company_name')
            ->join('users as u','u.id','=','b.business_id')
            ->join('user_businesses as ub','ub.business_id','=','u.id')
            ->where(['b.parent_id'=>$business_id]);
            if(is_numeric($request->get('customer_id'))){
                $billings->where('b.business_id',$request->get('customer_id'));
            }
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

            $customers = DB::table('users as u')
            ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
            ->join('user_businesses as b','b.business_id','=','u.id')
            ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
            ->get();

        if($request->ajax())
            return view('admin.billing.ajax', compact('billings','customers'));
        else
            return view('admin.billing.index', compact('billings','customers'));
    }

    public function billingSendRequest(Request $request)
    {
        $user_id = Auth::user()->id;
        $business_id = Auth::user()->business_id;
        $billing_id = base64_decode($request->id);

        if ($request->isMethod('get'))
        {
            $duration = '';
            $form = '';
            $billing = DB::table('billings as b')
                        ->select('b.invoice_id','u.name','ub.company_name','b.start_date','b.end_date')
                        ->join('users as u','u.id','=','b.business_id')
                        ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                        ->where(['b.id'=>$billing_id])
                        ->first();

            $billing_approval = DB::table('billing_approvals')
                                ->select('request_sent_notes as comments','id','request_sent_by')
                                ->where(['billing_id'=>$billing_id])
                                ->latest()
                                ->first();

            if($billing_approval!=NULL)
            {
                $bill_app_attachments=DB::table('billing_approval_attachments')->where(['billing_approval_id'=>$billing_approval->id,'request_type'=>'sent'])->get();

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
            }
           

            $duration.='<div class="form-group">
                        <label for="label_name"> Duration: </label>
                        <span class="dur">('.date('d M',strtotime($billing->start_date)).' - '.date('d M',strtotime($billing->end_date)).') '.date('Y',strtotime($billing->start_date)).'</span>
                    </div>';
            
            return response()->json([                
                'result' => $billing,
                'result1' => $billing_approval,
                'duration' => $duration,
                'form'  => $form
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

            $billing= DB::table('billings')->where(['id'=>$billing_id])->first();

            $bill_approve_id=DB::table('billing_approvals')->insertGetId([
                                'billing_id'   => $billing_id,
                                'business_id'  => $billing->business_id,
                                'request_sent_by' => $user_id,
                                'request_sent_notes'   => $request->comments,
                                'request_sent_at' => date('Y-m-d H:i:s'),
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
            
            DB::table('billing_approval_actions')->insert([
                'billing_id'   => $billing_id,
                'business_id'  => $billing->business_id,
                'notes'        => $request->comments,
                'action_type'   => 'sent',
                'created_by'    => $user_id,
                'user_type'     => 'customer',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            DB::table('billings')->where(['id'=>$billing_id])->update([
                'status' => 'under_review',
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
                            'billing_id' => $billing->id,
                            'billing_approval_id' => $bill_approve_id,
                            'business_id' => $billing->business_id,
                            'file_name' => $tmp_data,
                            'request_type'  => 'sent',
                            'created_by' => $user_id,
                            'user_type' => 'customer',
                            'created_at' => date('Y-m-d H:i:s')
                        ]
                    );
                }
            }

            $user_d = DB::table('users')->where(['id'=>$billing->business_id])->first();

            // $name=$user_d->name;

            // $email=$user_d->email;

            // $sender = DB::table('users')->where(['id'=>$business_id])->first();
    
            // $data=['name' =>$name,'email' => $email,'user'=>$user_d,'billing'=>$billing,'comments'=>$request->comments,'sender'=>$sender];
    
            // Mail::send(['html'=>'mails.billing-sent_request'], $data, function($message) use($email,$name) {
            //     $message->to($email, $name)->subject
            //         ('myBCD System - Billing Send Request Notification');
            //     $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
            // });


            DB::commit();
            return response()->json([
                'fail' => false,
            ]);
         }
         catch(\Exception $e){
            DB::rollback();
            // something went wrong
            return $e;
         }

        
    }

    public function billingCancelRequest(Request $request)
    {
        $user_id = Auth::user()->id;
        $billing_id = base64_decode($request->id);
        $cust_name = '';

        $business_id = Auth::user()->business_id;

        if ($request->isMethod('get'))
        {
            $duration = '';
            $form = '';
            $billing = DB::table('billings as b')
                        ->select('b.invoice_id','u.name','ub.company_name','b.start_date','b.end_date')
                        ->join('users as u','u.id','=','b.business_id')
                        ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                        ->where(['b.id'=>$billing_id])
                        ->first();
            
            $billing_approval = DB::table('billing_approvals')
                                ->select('request_cancel_notes as comments','id','request_cancel_by')
                                ->where(['billing_id'=>$billing_id])
                                ->latest()
                                ->first();

            // Check Cancel By Whom
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
                        <span class="dur">('.date('d M',strtotime($billing->start_date)).' - '.date('d M',strtotime($billing->end_date)).') '.date('Y',strtotime($billing->start_date)).'</span>
                    </div>';
            
            return response()->json([
                'cust_name' => $cust_name,                
                'result' => $billing,
                'result1' => $billing_approval,
                'duration' => $duration,
                'form' => $form
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

            $billing= DB::table('billings')->where(['id'=>$billing_id])->first();

            $billing_approval = DB::table('billing_approvals')->where(['billing_id'=>$billing->id])->latest()->first();

            DB::table('billing_approvals')->where(['id'=>$billing_approval->id])->update([
                'request_cancel_by' => $user_id,
                'request_cancel_notes'   => $request->comments,
                'request_cancel_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            DB::table('billing_approval_actions')->insert([
                'billing_id'   => $billing_id,
                'business_id'  => $billing->business_id,
                'notes'        => $request->comments,
                'action_type'   => 'cancel',
                'created_by'    => $user_id,
                'user_type'     => 'customer',
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
                            'billing_id' => $billing->id,
                            'billing_approval_id' => $billing_approval->id,
                            'business_id' => $billing->business_id,
                            'file_name' => $tmp_data,
                            'request_type'  => 'cancel',
                            'created_by' => $user_id,
                            'user_type' => 'customer',
                            'created_at' => date('Y-m-d H:i:s')
                        ]
                    );
                }
            }

            // $user_d = DB::table('users')->where(['id'=>$billing->business_id])->first();

            // $name=$user_d->name;

            // $email=$user_d->email;

            // $msg = 'Customer ('.Helper::company_name($user_d->parent_id).') has been returned for review about the billing.';

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
            ]);
         }
         catch(\Exception $e){
            DB::rollback();
            // something went wrong
            return $e;
        }

        
    }
    
    public function billingStatus(Request $request)
    {
        $billing_id =  base64_decode($request->id);
        $user_id = Auth::user()->id;

        $business_id = Auth::user()->business_id;

        if ($request->isMethod('get'))
        {
            $duration = '';
            $form = '';
            $stars = '';
            $cust_name = '';
            $billing = DB::table('billings as b')
                        ->select('b.invoice_id','u.name','ub.company_name','b.start_date','b.end_date')
                        ->join('users as u','u.id','=','b.business_id')
                        ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                        ->where(['b.id'=>$billing_id])
                        ->first();
            
            $billing_approval = DB::table('billing_approvals')
                                ->select('request_approve_by_coc_notes as comments','id','request_approve_by_coc_id as request_approve_by','request_approve_by_coc_stars as stars')
                                ->where(['billing_id'=>$billing_id])
                                ->latest()
                                ->first();

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
           $bill_app_attachments=DB::table('billing_approval_attachments')->where(['billing_approval_id'=>$billing_approval->id,'user_type'=>'coc','request_type'=>'approve'])->get();

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
                        <span class="dur">('.date('d M',strtotime($billing->start_date)).' - '.date('d M',strtotime($billing->end_date)).') '.date('Y',strtotime($billing->start_date)).'</span>
                    </div>';
            
            return response()->json([  
                'cust_name' => $cust_name,              
                'result' => $billing,
                'result1' => $billing_approval,
                'duration' => $duration,
                'stars' => $stars,
                'form' => $form
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

            $billing= DB::table('billings')->where(['id'=>$billing_id])->first();

            DB::table('billings')->where(['id'=> $billing_id])->update([
                'status'     => 'completed',
                'completed_by' => $user_id,
                'completed_at' => date('Y-m-d H:i:s')
            ]);
    
            $billing_approval = DB::table('billing_approvals')
                                ->where(['billing_id'=>$billing_id])
                                ->latest()
                                ->first();
    
            if($billing_approval!=NULL)
            {
                if($billing_approval->request_cancel_by==NULL && $billing_approval->request_approve_by_cust_id==NULL)
                {
                    DB::table('billing_approvals')->where(['id'=>$billing_approval->id])->update(
                        [
                            'request_approve_by_cust_id' => $user_id,
                            'request_approve_by_cust_notes' => $request->comments,
                            'request_approve_by_cust_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]
                    );

                    DB::table('billing_approval_actions')->insert([
                        'billing_id'   => $billing_id,
                        'business_id'  => $billing->business_id,
                        'notes'        => $request->comments,
                        'action_type'   => 'approve',
                        'created_by'    => $user_id,
                        'user_type'     => 'customer',
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
                                    'billing_id' => $billing->id,
                                    'billing_approval_id' => $billing_approval->id,
                                    'business_id' => $billing->business_id,
                                    'file_name' => $tmp_data,
                                    'request_type'  => 'approve',
                                    'created_by' => $user_id,
                                    'user_type' => 'customer',
                                    'created_at' => date('Y-m-d H:i:s')
                                ]
                            );
                        }
                    }

                    // $user_d = DB::table('users')->where(['id'=>$billing->business_id])->first();

                    // $name=$user_d->name;

                    // $email=$user_d->email;

                    // $msg = 'Customer ('.Helper::company_name($user_d->parent_id).') has approved the request about the billing.';

                    // $sender = DB::table('users')->where(['id'=>$business_id])->first();
            
                    // $data=['name' =>$name,'email' => $email,'user'=>$user_d,'billing'=>$billing,'comments'=>$request->comments,'msg'=>$msg,'sender'=>$sender];
            
                    // Mail::send(['html'=>'mails.billing-approve_request'], $data, function($message) use($email,$name) {
                    //     $message->to($email, $name)->subject
                    //         ('myBCD System - Billing Approve Request Notification');
                    //     $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                    // });
                }
            }

            DB::commit();
            return response()->json([
                'fail' => false,
            ]);
        }
        catch(\Exception $e){
            DB::rollback();
            // something went wrong
            return $e;
         }
        
    }

    public function billingCompleteDetails(Request $request)
    {
            $billing_id =  base64_decode($request->id);
            $user_id = Auth::user()->id;

            $duration = '';
            $form = '';
            $cust_name = '';
            $billing = DB::table('billings as b')
                        ->select('b.invoice_id','u.name','ub.company_name','b.start_date','b.end_date')
                        ->join('users as u','u.id','=','b.business_id')
                        ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                        ->where(['b.id'=>$billing_id])
                        ->first();
            
            $billing_approval = DB::table('billing_approvals')
                                ->select('request_approve_by_cust_notes as comments','id','request_approve_by_cust_id as request_approve_by')
                                ->where(['billing_id'=>$billing_id])
                                ->latest()
                                ->first();

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
                        <span class="dur">('.date('d M',strtotime($billing->start_date)).' - '.date('d M',strtotime($billing->end_date)).') '.date('Y',strtotime($billing->start_date)).'</span>
                    </div>';
            
            return response()->json([
                'cust_name' => $cust_name,                
                'result' => $billing,
                'result1' => $billing_approval,
                'duration' => $duration,
                'form' => $form
            ]);

    }

    public function billingApprovalActionDetails(Request $request)
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
            $business_id=Auth::user()->business_id;

            $billing_id=base64_decode($id);

            $perPage = 10;

            $billing_detail_candidate=DB::table('billing_items as bi')
                                        ->DISTINCT('bi.candidate_id')
                                        ->select('bi.candidate_id','bi.business_id',DB::raw('sum(bi.quantity) as total_quantity'),DB::raw('sum(bi.additional_charges) as total_additional_charges'),DB::raw('sum(bi.final_total_check_price) as total_check_price'))
                                        ->groupBy('bi.candidate_id')
                                        ->where(['bi.billing_id'=>$billing_id])
                                        ->whereNotNull('bi.candidate_id');

            $billing_detail_candidate=$billing_detail_candidate->get();

            $billing_detail_api = DB::table('billing_items as bi')
                                    ->select('bi.business_id',DB::raw('sum(bi.quantity) as total_quantity'),DB::raw('sum(bi.additional_charges) as total_additional_charges'),DB::raw('sum(bi.final_total_check_price) as total_check_price'))
                                    ->groupBy('bi.candidate_id')
                                    ->where(['bi.billing_id'=>$billing_id])
                                    ->whereNull('bi.candidate_id');
            
            $billing_detail_api=$billing_detail_api->get();

            $items = $billing_detail_candidate->merge($billing_detail_api)->paginate(25);
            
            // dd($items);

            // dd($billing_detail_candidate);

            // dd($billings);

            // $customers = DB::table('users as u')
            // ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
            // ->join('user_businesses as b','b.business_id','=','u.id')
            // ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
            // ->get();
            $services=DB::table('services as s')
                    ->select('s.name','s.id','s.verification_type')
                    ->where('status','1')
                    ->whereNull('s.business_id')
                    ->orWhere('business_id',$business_id)
                    ->get();
        
            $billing=DB::table('billings as b')->where('id',$billing_id)->first();

            $billing_d = DB::table('billing_items')
                                ->select('service_id',DB::raw('sum(final_total_check_price) as total_check_price'),'service_name')
                                ->where('billing_id',$billing_id)
                                ->groupBy('service_id')
                                ->get();

        if($request->ajax())
            return view('admin.billing.billing_details_ajax', compact('items','billing','services','billing_d'));
        else
            return view('admin.billing.billing_details', compact('items','billing','services','billing_d'));
    }

    public function billingDetailsEdit(Request $request)
    {
        $id=base64_decode($request->id);

        if ($request->isMethod('get'))
        {
            $data = DB::table('billing_items as bi')
                        ->select('bi.*','verification_type')
                        ->join('services as s','s.id','=','bi.service_id')
                        ->where(['bi.id' => $id])        
                        ->first(); 
            $billing = DB::table('billings')->where(['id'=>$data->billing_id])->first();
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
                    $remove_img='';
                    $file=$path.$item->file_name;
                    if(strpos($item->file_name, 'pdf')!==false) {
                        $img='<img src="'.url("/")."/admin/images/icon_pdf.png".'" alt="preview" style="height:100px;"/>';
                    }
                    else
                    {
                        $img='<img src="'.$file.'" alt="preview" style="height:100px;"/>';
                    }

                    if($billing->status!='completed')
                    {
                        $remove_img = '<a class="remove-image" data-id="'.base64_encode($item->id).'" href="javascript:;" style="display: inline;">×</a>';
                    }

                    $form.='<div class="image-area">
                                <a href="'.$file.'" download> 
                                    '.$img.'
                                </a>
                                '.$remove_img.'
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

        $business_id =Auth::user()->business_id;
        $user_id =  Auth::user()->id;
        $new_subtotal = 0;
        $rules= [
            'additional_charges'    => 'required|numeric|min:0',
            'comments' => 'required|min:1',
            // 'attachment' => 'nullable',
            // 'attachment.*' => 'mimes:png,jpeg,jpg,gif,svg,pdf|max:20000'
         ];

        
         $validator = Validator::make($request->all(), $rules);
          
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type' => 'validation',
                 'errors' => $validator->errors()
             ]);
         }
        //  dd(1);
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

                // dd(1);

                $billing_item =  DB::table('billing_items')->where(['id'=>$id])->first();

                $total_price = 0;

                $tax_amount = 0;

                $total_price = number_format($billing_item->total_check_price + $request->additional_charges,2);


                DB::table('billing_items')->where(['id'=>$id])->update(
                    [
                        'additional_charges' => $request->additional_charges,
                        'additional_charge_notes' => $request->comments,
                        'final_total_check_price' => $total_price,
                        'updated_by' => $user_id,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                );

                $billing_data = DB::table('billing_items')
                ->select(DB::raw('sum(final_total_check_price) as total_price'))
                ->where(['billing_id'=>$billing_item->billing_id])
                ->first();

                $new_subtotal = $billing_data->total_price;

              

                $billing = DB::table('billings')->where(['id'=>$billing_item->billing_id])->first();

                // If Any Discount Exist
                $billing_d = DB::table('billing_discounts')->where(['billing_id'=>$billing->id])->first();
                
                if($billing_d!=NULL)
                {
                    $services = [];

                    $discount_amount = 0;

                    if(stripos($billing_d->discount_ref,'amount')!==false)
                    {
                        if(stripos($billing_d->discount_type,'flat')!==false)
                        {
                            $discount_amount = number_format($billing_d->discount,2);
                        }
                        else if(stripos($billing_d->discount_type,'percentage')!==false)
                        {
                            $discount_amount = number_format($new_subtotal * ($billing_d->discount / 100),2);
                        }

                        $new_subtotal = $new_subtotal - $discount_amount;
                    }
                    else if(stripos($billing_d->discount_ref,'check')!==false)
                    {

                        $total_check_price = 0;

                        $services = json_decode($billing_d->discount_checks,true);

                        $billing_dis = DB::table('billing_items')
                                    ->select('service_id',DB::raw('sum(final_total_check_price) as total_check_price'),'service_name')
                                    ->where('billing_id',$billing->id)
                                    ->whereIn('service_id',$services)
                                    ->groupBy('service_id')
                                    ->get();

                        foreach($billing_dis as $bd)
                        {
                            $total_check_price = number_format($total_check_price + $bd->total_check_price, 2);
                        }

                        if(stripos($billing_d->discount_type,'flat')!==false)
                        {
                            $discount_amount = number_format($billing_d->discount,2);
                        }
                        else if(stripos($billing_d->discount_type,'percentage')!==false)
                        {
                            $discount_amount = number_format($total_check_price * ($billing_d->discount / 100),2);
                        }

                        $new_subtotal = $new_subtotal - $discount_amount;

                    }

                    DB::table('billing_discounts')->where(['id'=>$billing_d->id])->update([
                        'discount_amt' => $discount_amount
                    ]);
                }

                $tax_amount = number_format(($new_subtotal * $billing->tax)/100,2);

                DB::table('billings')->where(['id'=>$billing_item->billing_id])->update([
                    'sub_total' => $billing_data->total_price,
                    'tax_amount' => $tax_amount,
                    'total_amount' => $new_subtotal + $tax_amount,
                    'updated_by'   => $user_id,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                if($request->hasFile('attachment') && $request->file('attachment') !="")
                {
                    $filePath = public_path('/uploads/billings/additional-charge/'); 
                    foreach($files as $file)
                    {
                        $file_data = $file->getClientOriginalName();
                        $tmp_data  = date('YmdHis').'-'.$file_data; 
                        $data = $file->move($filePath, $tmp_data);
                        
                        DB::table('billing_additional_charge_attachments')->insert(
                            [
                                'billing_id' => $billing_item->billing_id,
                                'billing_details_id' => $billing_item->id,
                                'business_id' => $billing_item->business_id,
                                'service_id'    => $billing_item->service_id,
                                'service_item_number'    => $billing_item->service_item_number,
                                'file_name' => $tmp_data,
                                'created_at' => date('Y-m-d H:i:s')
                            ]
                        );
                    }
                }

                DB::commit();
                return response()->json([
                    'fail' => false,
                ]);
         }
         catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
         }
    }

    public function billingRemoveFile(Request $request)
    {
      $id =  base64_decode($request->input('file_id'));
      $db=false;
       DB::beginTransaction();
       try{
           $path=public_path().'/uploads/billings/additional-charge/';
          $bill_data=DB::table('billing_additional_charge_attachments')->where('id',$id)->first();
          $is_done = DB::table('billing_additional_charge_attachments')->where('id',$id)->delete();

          if(File::exists($path.$bill_data->file_name))
          {
             File::delete($path.$bill_data->file_name);
          }

          DB::commit();

          $billing_additional_attach=DB::table('billing_additional_charge_attachments')->where('billing_details_id',$bill_data->billing_details_id)->get();
          if(count($billing_additional_attach)>0)
          {
                $db=true;
          }
          // Do something when it fails
          return response()->json([
              'fail' => false,
              'db' => $db,
              'message' => 'File removed!'
          ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    public function billingMailInvoice(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $billing_id = base64_decode($request->id);

        try{
            $bill=DB::table('billings')->where(['id'=>$billing_id])->first();
            if($bill!=NULL)
            {
                $users = DB::table('users as u')
                            ->where(['u.id'=>$bill->business_id,'u.user_type'=>'client'])
                            ->first();
                $email = $users->email;
                $name  = $users->first_name;
                $sender = DB::table('users')->where(['id'=>$business_id])->first();

                $data  = array('name'=>$name,'email'=>$email,'user'=>$users,'bill'=>$bill,'sender'=>$sender);
    
                Mail::send(['html'=>'mails.billing-notify'], $data, function($message) use($email,$name) {
                    $message->to($email, $name)->subject
                        ('BCD- Billing Notification');
                    $message->from(env('MAIL_FROM_ADDRESS'),'BCD System');
                });

                return response()->json([
                    'status' =>'ok',
                    'name' => $users->name,
                    'message' => 'Mail Send Successfully !'
                  ]);
            }

            return response()->json([
                'status' =>'no',
                'message' => 'Something Went Wrong !'
            ]);
        }
        catch (\Exception $e) {
            // something went wrong
            return $e;
        }    
    }

    public function billingDiscount(Request $request)
    {

        $services = [];

        $rules= [
            'discount_reference' => 'required|in:amount,check',
            'services' => 'required_if:discount_reference,check|array|min:1',
            'discount_type' => 'required|in:flat,percentage',
            'value' => 'required|numeric|min:0'
         ];

         $custom = [
             'discount_reference.in' => 'Discount Reference should be Amount/Check Wise !!',
             'services.required_if' => 'Select Atleast One Check Item !!',
             'discount_type.in' => 'Discount Reference should be Fixed Amount/Percentage',
         ];
        
         $validator = Validator::make($request->all(), $rules,$custom);
          
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type' => 'validation',
                 'errors' => $validator->errors()
             ]);
         }

         


         DB::beginTransaction();
         try{

            $billing_id = base64_decode($request->id);

            $new_subtotal = 0;

            $discount_amount = 0;

            $tax_amount = 0;

            $total_amount = 0;

            $billing = DB::table('billings')->where('id',$billing_id)->first();

            $tax = $billing->tax;

            $new_subtotal = $billing->sub_total;

            if(stripos($request->discount_type,'percentage')!==false)
            {
                $rules= [
                    'value' => 'required|numeric|min:0|max:100'
                 ];
        
                 $validator = Validator::make($request->all(), $rules);
                  
                 if ($validator->fails()){
                     return response()->json([
                         'fail' => true,
                         'error_type' => 'validation',
                         'errors' => $validator->errors()
                     ]);
                 }
            }

            if(stripos($request->discount_reference,'amount')!==false)
            {

                if(stripos($request->discount_type,'flat')!==false)
                {
                    $discount_amount = number_format($request->value,2);
                }
                else if(stripos($request->discount_type,'percentage')!==false)
                {
                    $discount_amount = number_format($new_subtotal * ($request->value / 100),2);
                }

                if($discount_amount >= $new_subtotal)
                {
                    return response()->json([
                        'fail' => true,
                        'error' => 'yes',
                        'message' => 'Discount Amount Should Be Less Than Sub Total'
                    ]);
                }

                $new_subtotal = $new_subtotal - $discount_amount;
            }
            else if(stripos($request->discount_reference,'check')!==false)
            {

                $total_check_price = 0;

                $billing_d = DB::table('billing_items')
                            ->select('service_id',DB::raw('sum(final_total_check_price) as total_check_price'),'service_name')
                            ->where('billing_id',$billing_id)
                            ->whereIn('service_id',$request->services)
                            ->groupBy('service_id')
                            ->get();

                foreach($billing_d as $bd)
                {
                    $total_check_price = number_format($total_check_price + $bd->total_check_price, 2);
                }

                if(stripos($request->discount_type,'flat')!==false)
                {
                    $discount_amount = number_format($request->value,2);
                }
                else if(stripos($request->discount_type,'percentage')!==false)
                {
                    $discount_amount = number_format($total_check_price * ($request->value / 100),2);
                }

                if($discount_amount >= $total_check_price)
                {
                    return response()->json([
                        'fail' => true,
                        'error' => 'yes',
                        'message' => 'Discount Amount Should Be Less Than Sum of Check Price !!'
                    ]);
                }

                $new_subtotal = $new_subtotal - $discount_amount;

            }

            $tax_amount = number_format($new_subtotal * ($tax/100),2);

            $total_amount = number_format($new_subtotal  + $tax_amount,2);

            $billing_discount = DB::table('billing_discounts')->where(['billing_id'=>$billing_id])->first();

            if(stripos($request->discount_reference,'check')!==false)
            {
                foreach($request->services as $service)
                {
                    $services[]=$service;
                }
            }

            // dd($services);

            if($billing_discount!=NULL)
            {
                DB::table('billing_discounts')->where(['billing_id'=>$billing_id])->update([
                    'discount' => $request->value,
                    'discount_amt' => $discount_amount,
                    'discount_type' => $request->discount_type,
                    'discount_ref' => $request->discount_reference,
                    'discount_checks' => count($services) > 0 ? json_encode($services) : NULL,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            else
            {
                DB::table('billing_discounts')->insert([
                    'billing_id' => $billing_id,
                    'discount' => $request->value,
                    'discount_amt' => $discount_amount,
                    'discount_type' => $request->discount_type,
                    'discount_ref' => $request->discount_reference,
                    'discount_checks' => count($services) > 0 ? json_encode($services) : NULL,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            DB::table('billings')->where('id',$billing_id)->update([
                'tax_amount' => $tax_amount,
                'total_amount' => $total_amount
            ]);

            DB::commit();

            return response()->json([
                'fail' => false,
            ]);

         }
         catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 

    }

    public function billDiscountRef(Request $request)
    {
        $type = $request->type;

        $billing_id = base64_decode($request->id);

        $form='';

        $discount_type = '';

        $billing_discount = DB::table('billing_discounts')->where(['billing_id'=>$billing_id])->first();

        $billing_d = DB::table('billing_items')
                        ->select('service_id',DB::raw('sum(final_total_check_price) as total_check_price'),'service_name')
                        ->where('billing_id',$billing_id)
                        ->groupBy('service_id')
                        ->get();

        
        if($billing_discount!=NULL)
        {
            if(stripos($billing_discount->discount_ref,$type)!==false)
            {
                if(stripos($type,'check')!==false)
                {
                    $bill_checks = [];

                    $bill_checks = json_decode($billing_discount->discount_checks,true);

                    $form.='<div class="row">
                                <div class="col-md-6">
                                    <h5 class="pl-4"><strong>Check Name</strong></h5>
                                </div>
                                <div class="col-md-6">
                                    <h5 class=""><strong>Price</strong></h5>
                                </div>
                            </div>';

                    foreach($billing_d as $bd)
                    {
                        $checked = '';
                        if(in_array($bd->service_id,$bill_checks))
                        { 
                            $checked = "checked";
                        }
                        $form.='<div class="row pt-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="checkbox-inline serviceverify cursor-pointer ">
                                                <input type="checkbox" class="services_list" name="services[]" value="'.$bd->service_id.'" id="service-'.$bd->service_id.'" '.$checked.'>
                                                <span class="selectservices pl-3">'.$bd->service_name.'</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" style="font-size: 18px;">
                                            <strong class=""><i class="fas fa-rupee-sign"></i></strong> <span id="price_result">'.$bd->total_check_price.'</span>
                                        </div>
                                    </div>
                                </div>';
                    }

                    $form.='<p style="margin-bottom: 2px;" class="text-danger error-container error-services" id="error-services"></p>';
        
                }

                if(stripos($billing_discount->discount_type,'flat')!==false)
                {
                    $discount_type.='<select class="discount_type form-control" name="discount_type">
                                        <option value="">--Select--</option>
                                        <option value="flat" selected>Fixed Amount</option>
                                        <option value="percentage">Percentage</option>
                                    </select>';
                }
                else if(stripos($billing_discount->discount_type,'percentage')!==false)
                {
                    $discount_type.='<select class="discount_type form-control" name="discount_type">
                                        <option value="">--Select--</option>
                                        <option value="flat">Fixed Amount</option>
                                        <option value="percentage" selected>Percentage</option>
                                    </select>';
                }
                else
                {
                    $discount_type.='<select class="discount_type form-control" name="discount_type">
                                        <option value="">--Select--</option>
                                        <option value="flat">Fixed Amount</option>
                                        <option value="percentage">Percentage</option>
                                    </select>';
                }

                $form.='<div class="row">
                            <div class="col-md-6">
                                <label>Discount Type <span class="text-danger">*</span></label>
                                '.$discount_type.'
                                <p style="margin-bottom: 2px;" class="text-danger error-container error-discount_type" id="error-discount_type"></p>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Value <span class="text-danger">*</span></label>
                                    <input class="form-control value" type="text" name="value" value="'.$billing_discount->discount.'">
                                    <p style="margin-bottom: 2px;" class="text-danger error-container error-value" id="error-value"></p>
                                </div>
                            </div>
                        </div>';

            }
            else
            {
                if(stripos($type,'check')!==false)
                {
                    $form.='<div class="row">
                        <div class="col-md-6">
                            <h5 class="pl-4"><strong>Check Name</strong></h5>
                        </div>
                        <div class="col-md-6">
                            <h5 class=""><strong>Price</strong></h5>
                        </div>
                        </div>';
                    foreach($billing_d as $bd)
                    {
                        $form.='<div class="row pt-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="checkbox-inline serviceverify cursor-pointer">
                                                <input type="checkbox" class="services_list" name="services[]" value="'.$bd->service_id.'" id="service-'.$bd->service_id.'">
                                                <span class="selectservices pl-3">'.$bd->service_name.'</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" style="font-size: 18px;">
                                            <strong class=""><i class="fas fa-rupee-sign"></i></strong> <span id="price_result">'.$bd->total_check_price.'</span>
                                        </div>
                                    </div>
                                </div>';
                    }

                    $form.='<p style="margin-bottom: 2px;" class="text-danger error-container error-services" id="error-services"></p>';
                }

                $discount_type.='<select class="discount_type form-control" name="discount_type">
                                        <option value="">--Select--</option>
                                        <option value="flat">Fixed Amount</option>
                                        <option value="percentage">Percentage</option>
                                    </select>';

                $form.='<div class="row">
                            <div class="col-md-6">
                                <label>Discount Type <span class="text-danger">*</span></label>
                               '.$discount_type.'
                                <p style="margin-bottom: 2px;" class="text-danger error-container error-discount_type" id="error-discount_type"></p>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Value <span class="text-danger">*</span></label>
                                    <input class="form-control value" type="text" name="value" value="'.$billing_discount->discount.'">
                                    <p style="margin-bottom: 2px;" class="text-danger error-container error-value" id="error-value"></p>
                                </div>
                            </div>
                        </div>';
            }
        }
        else
        {
            if(stripos($type,'check')!==false)
            {
                $form.='<div class="row">
                        <div class="col-md-6">
                            <h5 class="pl-4"><strong>Check Name</strong></h5>
                        </div>
                        <div class="col-md-6">
                            <h5 class=""><strong>Price</strong></h5>
                        </div>
                        </div>';
                foreach($billing_d as $bd)
                {
                    $form.='<div class="row pt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="checkbox-inline serviceverify cursor-pointer">
                                            <input type="checkbox" class="services_list" name="services[]" value="'.$bd->service_id.'" id="service-'.$bd->service_id.'">
                                            <span class="selectservices pl-3">'.$bd->service_name.'</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group" style="font-size: 18px;">
                                        <strong class=""><i class="fas fa-rupee-sign"></i></strong> <span id="price_result">'.$bd->total_check_price.'</span>
                                    </div>
                                </div>
                            </div>';
                }

                $form.='<p style="margin-bottom: 2px;" class="text-danger error-container error-services" id="error-services"></p>';
            }

            $discount_type.='<select class="discount_type form-control" name="discount_type">
                                <option value="">--Select--</option>
                                <option value="flat">Fixed Amount</option>
                                <option value="percentage">Percentage</option>
                            </select>';

                $form.='<div class="row">
                            <div class="col-md-6">
                                <label>Discount Type <span class="text-danger">*</span></label>
                                '.$discount_type.'
                                <p style="margin-bottom: 2px;" class="text-danger error-container error-discount_type" id="error-discount_type"></p>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Value <span class="text-danger">*</span></label>
                                    <input class="form-control value" type="text" name="value">
                                    <p style="margin-bottom: 2px;" class="text-danger error-container error-value" id="error-value"></p>
                                </div>
                            </div>
                        </div>';
        }

        return response()->json([
            'form'=>$form
        ]);
    }

    public function billingExcelExport(Request $request,$id)
    {
        $billing_id=base64_decode($id);

        $bill = DB::table('billings')->where('id',$billing_id)->first();

        $file_name=$bill->invoice_id.date('Ymdhis').'.xlsx';

        $items=DB::table('billing_items as bi')
                        ->select('bi.*','s.verification_type')
                        ->join('services as s','s.id','=','bi.service_id')
                        ->where(['bi.billing_id'=>$billing_id])
                        ->orderBy('bi.service_id','asc')
                        ->get();
        
        if(count($items)>0)
        {
            return Excel::download(new BilldataExport($billing_id), $file_name);
        }
        else
        {
            return redirect('/error-404-data');
        }

    }

    //Billing Config
    public function billingConfig(Request $request)
    {
        $business_id=Auth::user()->business_id;
        // dd($parent_id);
        $checkcocincentive=DB::table('check_coc_incentives as c')
                            ->select('s.id as service_id','s.name as service_name','c.id','s.verification_type','c.coc_id','c.incentive','c.penalty')
                            ->join('services as s','s.id','=','c.service_id')
                            // ->join('check_price_coc as cp','c.service_id','=','ci.service_id')
                            ->where(['c.business_id'=>$business_id,'s.status'=>'1']);
                            if(is_numeric($request->get('customer_id'))){
                                $checkcocincentive->where('c.coc_id',$request->get('customer_id'));
                            }
                            if(is_numeric($request->get('service_id'))){
                                $checkcocincentive->where('c.service_id',$request->get('service_id'));
                            }

        $items=$checkcocincentive->paginate(10);
        

        $customers = DB::table('users as u')
            ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
            ->join('user_businesses as b','b.business_id','=','u.id')
            ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
            ->whereNotIn('u.id',[$business_id])
            ->get();

        $services=DB::table('services as s')
                    ->select('s.name','s.id','s.verification_type')
                    ->where('status','1')
                    ->where('business_id',NULL)
                    ->whereNotIn('type_name',['e_court'])
                    ->orwhere('business_id',$business_id)
                    ->get();

        if($request->ajax())
            return view('admin.billing.config.ajax',compact('items','customers','services'));
        else
            return view('admin.billing.config.index',compact('items','customers','services'));
    }

    public function billingCOCWiseConfigStore(Request $request)
    {
        $business_id=Auth::user()->business_id;
        $user_id=Auth::user()->id;
        
        $parent_id=Auth::user()->parent_id;
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
          $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
          $parent_id=$users->parent_id;
        }

        $rules= 
        [
            'customer'   => 'required', 
            'services'    => 'required|array|min:1',
            'incentive'  => 'required|numeric|min:1', 
            'penalty'  => 'required|numeric|min:1', 
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'error_type'=> 'validation',
                'errors' => $validator->errors()
            ]);
        }

        DB::beginTransaction();
        try{
            $checkincentive=DB::table('check_coc_incentives')
                        ->where(['coc_id'=>$request->customer])
                        ->whereIn('service_id',$request->services)
                        ->count();
            
            // dd($checkincentive);

            if($checkincentive > 0)
            {
                return response()->json([
                    'fail' => true,
                    'error_type'=> 'validation',
                    'errors' => ['services'=>'Customer Selected Service is Already Exist!!']
                ]);
            }

            if(count($request->services)>0)
            {
                foreach($request->services as $service_id)
                {
                    DB::table('check_coc_incentives')->insert(
                        [
                            'parent_id' => $parent_id,
                            'business_id' => $business_id,
                            'coc_id' => $request->customer,
                            'service_id' => $service_id,
                            'created_by' => $user_id,
                            'used_by'  => 'customer',
                            'incentive' => $request->incentive,
                            'penalty' => $request->incentive,
                            'created_at' => date('Y-m-d H:i:s')
                        ]
                    );
                }
            }
            DB::commit();
            return response()->json([
                'fail' => false,
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    public function billingCOCWiseConfigUpdate(Request $request)
    {
        $id=base64_decode($request->id);

        $rules= [
            'incentive'         => 'required|numeric|min:1',
            'penalty'         => 'required|numeric|min:1'
         ];
        $validator = Validator::make($request->all(), $rules);
          
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type'=>'validation',
                 'errors' => $validator->errors()
             ]);
         }
         DB::beginTransaction();
         try{
            DB::table('check_coc_incentives')->where(['id'=>$id])->update([
                'incentive' =>$request->incentive,
                'penalty' =>$request->penalty,
                'updated_by'=> Auth::user()->id,
                'updated_at'=>date('Y-m-d H:i:s')
            ]);
            DB::commit();
            return response()->json([
                'fail' => false,
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    //Billing Setting

    public function billingSetting(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $items = DB::table('users as u')
                        ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                        ->join('user_businesses as b','b.business_id','=','u.id')
                        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                        ->whereNotIn('u.id',[$business_id]);
                        if(is_numeric($request->get('customer_id'))){
                            $items->where('u.id',$request->get('customer_id'));
                        }
        $items=$items->paginate(10);

        $customers = DB::table('users as u')
            ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
            ->join('user_businesses as b','b.business_id','=','u.id')
            ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
            ->whereNotIn('u.id',[$business_id])
            ->get();

        if($request->ajax())
            return view('admin.billing.settings.ajax',compact('items','customers'));
        else
            return view('admin.billing.settings.index',compact('items','customers'));
    }

    public function billingSettingEdit(Request $request)
    {
        $id = base64_decode($request->id);
        
        if ($request->isMethod('get'))
        {
            $customers = DB::table('users as u')
                            ->select('u.name','b.company_name','b.billing_cycle_period')
                            ->join('user_businesses as b','b.business_id','=','u.id')
                            ->where(['u.id'=>$id])
                            ->first();

            return response()->json([
                'result' =>$customers
            ]);
        }


        $rules= [
            'cycle_period' => 'required|in:half_monthly,monthly',
         ];

         $custom=[
            'cycle_period.required' => 'Billing Cycle Period is Required',
            'cycle_period.in' => 'Billing Cycle Period Must be in 15 days or monthly'
         ];

        
         $validator = Validator::make($request->all(), $rules,$custom);
          
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type' => 'validation',
                 'errors' => $validator->errors()
             ]);
         }

         DB::table('user_businesses')->where(['business_id'=>$id])->update([
             'billing_cycle_period' => $request->input('cycle_period')
         ]);

         return response()->json([
            'fail' =>false
         ]);
    }

    // Billing Action (i.e; Billing Approval Has Been Sent But doesn't perform any action by client)

    public function billingAction(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $items = DB::table('users as u')
                        ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                        ->join('user_businesses as b','b.business_id','=','u.id')
                        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                        ->whereNotIn('u.id',[$business_id]);
                        if(is_numeric($request->get('customer_id'))){
                            $items->where('u.id',$request->get('customer_id'));
                        }
        $items=$items->paginate(10);

        $customers = DB::table('users as u')
            ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
            ->join('user_businesses as b','b.business_id','=','u.id')
            ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
            ->whereNotIn('u.id',[$business_id])
            ->get();

        if($request->ajax())
            return view('admin.billing.action.ajax',compact('items','customers'));
        else
            return view('admin.billing.action.index',compact('items','customers'));
    }

    public function billingActionEdit(Request $request)
    {
        $id = base64_decode($request->id);
        
        if ($request->isMethod('get'))
        {
            $customers = DB::table('users as u')
                            ->select('u.name','b.company_name','b.bill_action_notify_days as no_of_days')
                            ->join('user_businesses as b','b.business_id','=','u.id')
                            ->where(['u.id'=>$id])
                            ->first();

            return response()->json([
                'result' =>$customers
            ]);
        }

        $rules= [
            'no_of_days' => 'required|integer|min:1|max:15',
         ];

         $custom=[
            'no_of_days.required' => 'No. of Days is Required',
            'no_of_days.integer' => 'No. of Days Must be in numbers',
            'no_of_days.min' => 'No. of Days Must be Atleast 1',
            'no_of_days.max' => 'No. of Days Must be Maximum 15 Days'
         ];

        
         $validator = Validator::make($request->all(), $rules,$custom);
          
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type' => 'validation',
                 'errors' => $validator->errors()
             ]);
         }

         DB::table('user_businesses')->where(['business_id'=>$id])->update([
            'bill_action_notify_days' => $request->input('no_of_days')
        ]);

        return response()->json([
           'fail' =>false
        ]);


    }
}
