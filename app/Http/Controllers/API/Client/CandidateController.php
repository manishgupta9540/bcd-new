<?php

namespace App\Http\Controllers\API\Client;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Traits\S3ConfigTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use App\Events\ApiCheckByRestApi;
use App\Models\Admin\KeyAccountManager;
use App\Models\Admin\TaskAssignment;
use App\Models\Admin\Task;
use App\User;
use App\Models\Admin\JafFormData;
use App\Models\Admin\Job;
use App\Models\Admin\JobItem;
use App\Models\Admin\JobSlaItem;
use Imagick;

class CandidateController extends Controller
{
    public function candidateStore(Request $request)
    {
        $max_service_tat=0;
        $ref_no=$request->reference_number;
       
        DB::beginTransaction();
        try{
            if ($ref_no) {
                # code...
                $users=DB::table('users')->where(DB::raw('BINARY `display_id`'),$ref_no)->first();

                if($users!=NULL)
                {
                    $case_received_date = NULL;
                    $current_date = date('d-m-Y');
                    $business_id=$users->business_id;
                    $parent_id=$users->parent_id;
                    $user_d=DB::table('users')->where('id',$business_id)->first();
                
                    if($user_d->user_type=='client')
                    { 
                        $validator = Validator::make($request->all(), [
                            // 'customer_id'  => 'required',
                            'first_name'  => 'required|regex:/^[a-zA-Z. ]+$/u|min:1|max:255',
                            'middle_name' =>  'nullable|regex:/^[a-zA-Z. ]+$/u|min:1|max:255',
                            'last_name'  => 'nullable|regex:/^[a-zA-Z. ]+$/u|min:1|max:255',
                            'sla_id'     => 'required|integer|min:1',
                            // 'sla_type'     => 'required|in:package,variable',
                            // 'days_type'     => 'required_if:sla_type,variable|in:working,calender',
                            'father_name' => 'required|regex:/^[a-zA-Z. ]+$/u|min:2|max:255',
                            'dob'         => 'required|date',
                            'email'       => 'nullable|email:rfc,dns',
                            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                            'jaf_access'  => 'required|in:vendor,self,candidate',
                            'aadhar_number'=> 'nullable|regex:/^((?!([0-1]))[0-9]{12})$/',
                            'gender'      => 'required',
                            'client_emp_code' => 'required'
                        ],
                        [
                            // 'days_type.required_if'       => 'The days type field is required',
                            'jaf_access.in' => 'JAF Filling Access should be in vendor/self/candidate'
                        ]);
                
                        if ($validator->fails()) {            
                            return response()->json(['status' => 'error',
                                                    'message'=>'The given data was invalid.',
                                                    'errors'=> $validator->errors()], 200);
                        }
                        $jaf_access='';
                        if(stripos($request->jaf_access,'vendor')!==false )
                        {
                            $jaf_access='customer';
                           
                        }
                        elseif (stripos($request->jaf_access,'self')!==false) {
                            $jaf_access='coc';
                        }
                        elseif (stripos($request->jaf_access,'candidate')!==false) {
                            $jaf_access='candidate';
                        }
                        $gender=null;
                        if(stripos($request->gender,'male')!==false){
                            $gender='Male';
                        }
                        elseif (stripos($request->gender,'female')!==false) {
                            $gender='Female';
                        }
                        else if (stripos($request->gender,'other')!==false){
                            $gender='Other';
                        }
                        
                        $dob = NULL;
                        if($request->has('dob')){
                            $dob = date('Y-m-d',strtotime($request->dob));
                        }
                        $date_of_b=Carbon::parse($dob)->format('Y-m-d');
                        $today=Carbon::now();
                        $today_date=Carbon::now()->format('Y-m-d');
                        $year=$today->diffInYears($date_of_b);
                        if($year<18 || ($date_of_b >= $today_date))
                        {
                            return response()->json(['status' => 'error',
                                                    'message'=>'The given data was invalid.',
                                                    'errors'=> ['dob'=>'Age Must be 18 or older !']], 200);
                        }

                        $phone = preg_replace('/\D/','', $request->input('phone'));

                        if(strlen($phone)!=10)
                        {
                            return response()->json([
                            'success' => 'error',
                            'message'=>'The given data was invalid.',
                            'errors' => ['phone'=>'Phone Number must be 10-digit Number !!']
                            ]);
                        }
                        if($request->jaf_access=='candidate')
                        {
                            $email_rules=[
                                'email' => 'required|email:rfc,dns'
                            ];
                    
                            $validator = Validator::make($request->all(), $email_rules);
                                
                            if ($validator->fails()){
                                return response()->json(['status' => 'error',
                                                    'message'=>'The given data was invalid.',
                                                    'errors'=> $validator->errors()], 200);
                            }
                        }
                        // // email  validation
                        // $is_user_exist = DB::table('candidate_reinitiates')
                        // ->where(['email'=>$request->email])
                        // ->count();

                        // if($is_user_exist != 0 && $request->email !=""){

                        //     return response()->json(['status' => 'error',
                        //                             'message'=>'The given data was invalid.',
                        //                             'errors'=> ['email'=>'This email is already exists!']], 200);                
                        // }
                        // // user validation
                        // $user_already_exist = DB::table('candidate_reinitiates')
                        // ->where(['first_name'=>$request->first_name, 'dob'=>date('Y-m-d', strtotime($request->dob)), 'father_name'=>$request->father_name ])
                        // ->count();

                        // if($user_already_exist > 0){

                        //     return response()->json(['status' => 'error',
                        //                             'message'=>'The given data was invalid.',
                        //                             'errors'=> ['user'=>'It Seems like the user is already exist!']], 200);                
                        // }

                        // Check for Client Emp Code exist or not

                        $client_emp_code = $request->client_emp_code;

                        if($client_emp_code!=NULL && $client_emp_code!='')
                        {
                            $candidate_exist = DB::table('candidate_reinitiates')->where('user_type','candidate')->where('client_emp_code',$client_emp_code)->count();

                            if($candidate_exist > 0)
                            {
                                return response()->json(['status' => 'error',
                                                    'message'=>'The given data was invalid.',
                                                    'errors'=> ['user'=>"It Seems like the candidate's employee code is already exist!"]], 200);
                            }
                        }

                        $is_send_jaf_link = 0;
                        if($request->is_send_jaf_link){
                            $is_send_jaf_link = '1';
                        }

                    

                        $sla_id=$request->sla_id;

                        $customer_sla=DB::table('customer_sla')->where(['id'=>$sla_id,'business_id'=>$business_id])->first();
                        if ($customer_sla==null) {
                            return response()->json(['status' => 'error',
                            'message'=>'The given data was invalid.',
                            'errors'=> ['sla_id'=>'It Seems like the sla_id is not exist!']], 200);  
                        }

                        $client_cogent_criminal=DB::table('users as u')
                                        ->distinct('cs.service_id')
                                        ->select('u.*','cs.service_id','s.type_name','cs.number_of_verifications')
                                        ->join('customer_sla as c','c.business_id','=','u.id')
                                        ->join('customer_sla_items as cs','cs.sla_id','=','c.id')
                                        ->join('services as s','s.id','=','cs.service_id')
                                        ->where(['u.user_type'=>'client'])
                                        ->where('c.id',$customer_sla->id)
                                        ->where('u.id',$business_id)
                                        ->groupBy('cs.service_id')
                                        ->get();

                        if($business_id==1831 && (count($client_cogent_criminal)==1 && $client_cogent_criminal->contains('type_name','criminal') && $client_cogent_criminal->contains('number_of_verifications',1)))
                        {
                            $validator = Validator::make($request->all(), [
                                'address'      => 'required',
                                'address_type' => 'required'
                            ]);
                    
                            if ($validator->fails()) {            
                                return response()->json(['status' => 'error',
                                                        'message'=>'The given data was invalid.',
                                                        'errors'=> $validator->errors()], 200);
                            }
                        }

                        $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->first_name.' '.$request->middle_name.' '.$request->last_name));
                        //create user 
                        $data = 
                        [
                            'business_id'   =>$business_id,
                            'user_type'     =>'candidate',
                            'client_emp_code'=>$request->client_emp_code,
                            'entity_code'   =>$request->entity_code,
                            'parent_id'     =>$parent_id,
                            'name'          =>$name,
                            'first_name'    =>$request->first_name,
                            'middle_name'   =>$request->middle_name,
                            'last_name'     =>$request->last_name,
                            'father_name'   =>$request->father_name,
                            'aadhar_number' =>$request->aadhar_number,
                            'dob'           =>$dob,
                            'gender'        =>$gender,
                            'email'         =>$request->email,
                            // 'password'       => Hash::make($request->input('password')),
                            'phone'         =>$phone,
                            'address'       => $request->address,
                            'address_type'       => $request->address_type,
                            'platform_reference' => 'api',
                            'created_by'    =>$users->id,
                            'case_received_date' => date('Y-m-d H:i:s'),
                            'created_at'    =>date('Y-m-d H:i:s') 
                        ];
                
                        $user_id = DB::table('candidate_reinitiates')->insertGetId($data);

                        $display_id = "";
                        //check customer config
                        $candidate_config = DB::table('candidate_config')
                        ->where(['client_id'=>$business_id,'business_id'=>$business_id])
                        ->first();

                        //check client 
                        $client_config = DB::table('user_businesses')
                        ->where(['business_id'=>$business_id])
                        ->first(); 
            
                        $latest_user = DB::table('candidate_reinitiates')
                        ->select('display_id')
                        ->where(['user_type'=>'candidate','id'=>$user_id])
                        ->orderBy('id','desc')
                        ->first();
                        // dd($latest_user);
                        $starting_number = $user_id;
            
                        if($candidate_config !=null){
                            if($latest_user != null){
                                if($latest_user->display_id !=null){
                                    $id_arr = explode('-',$latest_user->display_id);
                                    $starting_number = $id_arr[2]+1;  
                                }
                            }
                            $starting_number = str_pad($starting_number, 10, "0", STR_PAD_LEFT);
                            $display_id = $candidate_config->customer_prefix.'-'.$candidate_config->client_prefix.'-'.$starting_number;
                        }else{
                            $customer_company = DB::table('user_businesses')
                                ->select('company_name')
                                ->where(['business_id'=>$business_id])
                                ->first();
                            $client_company = DB::table('user_businesses')
                                ->select('company_name')
                                ->where(['business_id'=>$business_id])
                                ->first();
                                
                            $u_id = str_pad($user_id, 10, "0", STR_PAD_LEFT);
                            $display_id = trim(strtoupper(str_replace(array(' ','-'),'',substr($customer_company->company_name,0,4)))).'-'.trim(strtoupper(substr($client_company->company_name,0,4))).'-'.$u_id;
                
                            // $display_id = trim(strtoupper(substr($customer_company->company_name,0,4))).'-'.trim(strtoupper(substr($client_company->company_name,0,4))).'-'.$u_id;
                        }
                        //
                
                        $customer_company = DB::table('user_businesses')
                        ->select('company_name')
                        ->where(['business_id'=>$parent_id])
                        ->first();
                        //  dd($customer_company);
                        $client_company = DB::table('user_businesses')
                        ->select('company_name')
                        ->where(['business_id'=>$business_id])
                        ->first();
                
                        $u_id = str_pad($user_id, 10, "0", STR_PAD_LEFT);
                        $display_id = substr($customer_company->company_name,0,4).'-'.substr($client_company->company_name,0,4).'-'.$u_id;

                        DB::table('candidate_reinitiates')->where(['id'=>$user_id])->update(['display_id'=>$display_id]);

                        $job_data = 
                                [
                                    'business_id'  => $business_id,
                                    'parent_id'    => $parent_id,
                                    'title'        => NULL,
                                    'sla_id'       => $request->sla_id,
                                    'total_candidates'=>1,
                                    'send_jaf_link_required'=>$is_send_jaf_link,
                                    'status'       =>0,
                                    'created_by'   =>$users->id,
                                    'created_at'   => date('Y-m-d H:i:s') 
                                ];
                                
                                $job_id = DB::table('jobs')->insertGetId($job_data);

                            // job item items      
                            $data = ['business_id' =>$business_id, 
                                    'job_id'       =>$job_id, 
                                    'candidate_id' =>$user_id,
                                    'sla_id'       =>$request->sla_id,
                                    'sla_type'     => 'package',
                                    'days_type'    => $customer_sla->days_type,
                                    'tat_type'     => $customer_sla->tat_type,
                                    'incentive'     => $customer_sla->incentive,
                                    'penalty'     => $customer_sla->penalty,
                                    'package_price'   => $customer_sla->package_price,
                                    'sla_title' => $customer_sla->title,
                                    'jaf_status'   =>'pending',
                                    'created_at'   =>date('Y-m-d H:i:s')
                                    ];
                            $job_item_id = DB::table('job_items')->insertGetId($data);

                            // service items
                            // if($request->sla_type=='package')
                            // {
                                $cust_sla_items = DB::table('customer_sla_items')->where(['sla_id'=>$request->sla_id])->get();
                                // if( count($request->input('services')) > 0 ){
                                //   foreach($request->input('services') as $item){
                                
                                if(count($cust_sla_items)>0)
                                {
                                    foreach($cust_sla_items as $item)
                                    {
                                            $service_d=DB::table('services')->where('id',$item->service_id)->first();
                                            $number_of_verifications=1;
                                            $no_of_tat=1;
                                            $incentive_tat=1;
                                            $penalty_tat=1;
                                            $price = 0;
                                            $sal_item_data = DB::table('customer_sla_items')->select('number_of_verifications','tat','incentive_tat','penalty_tat','price')->where(['sla_id'=>$request->sla_id,'service_id'=>$item->service_id])->first(); 
                                            if($sal_item_data !=null){
                                            $number_of_verifications= $sal_item_data->number_of_verifications;
                                            $no_of_tat= $sal_item_data->tat;
                                            $incentive_tat=$sal_item_data->incentive_tat;
                                            $penalty_tat=$sal_item_data->penalty_tat;
                                            $price= $sal_item_data->price;
                                            }
                                            $data = ['business_id'=> $business_id, 
                                                    'job_id'      => $job_id, 
                                                    'job_item_id' => $job_item_id,
                                                    'candidate_id' =>$user_id,
                                                    'sla_id'      => $sla_id,
                                                    'jaf_send_to' => $jaf_access,
                                                    // 'jaf_filled_by' => Auth::user()->id,
                                                    'service_id'  => $item->service_id,
                                                    'number_of_verifications'=>$service_d->verification_type=='Manual' || $service_d->verification_type=='manual'?$number_of_verifications:1,
                                                    'tat'=>$no_of_tat,
                                                    'incentive_tat'=>$incentive_tat,
                                                    'penalty_tat'=>$penalty_tat,
                                                    'price'   => $price,
                                                    'sla_item_id' => $item->sla_id,
                                                    'created_at'  => date('Y-m-d H:i:s') 
                                                ];
                                            $jsi =  DB::table('job_sla_items')->insertGetId($data);  
                                    }
                                }
                        
                            if ($jaf_access == 'customer') {
                                // dd($jaf_access);
                                $data = [
                                    'name'   =>$request->first_name.' '.$request->last_name,
                                    'business_id'=> $business_id, 
                                    'parent_id'     => $parent_id,
                                    'description' => 'JAF Filling',
                                    'job_id'      => $job_id, 
                                    'priority' => 'normal',
                                    'candidate_id' =>$user_id,   
                                    // 'service_id'  => $item, 
                                    // 'assigned_to' =>$assigned_user_id,
                                    // 'assigned_by' => Auth::user()->id, 
                                    // 'assigned_at' => date('Y-m-d H:i:s'),
                                    // 'start_date' => date('Y-m-d'),
                                    'created_by'    =>$users->id,
                                    'created_at'  => date('Y-m-d H:i:s'),
                                    'is_completed' => '0',
                                    'status'=>'1',
                                    'started_at' => date('Y-m-d H:i:s')
                                ];
                                // dd($data);
                                $task =  DB::table('tasks')->insertGetId($data); 
                                // dd($task);
                                $taskdata = [
                    
                                    'business_id'=> $business_id,
                                    'parent_id'     => $parent_id,
                                    'candidate_id' =>$user_id,   
                                    'job_sla_item_id'  => $jsi,
                                    'task_id'=> $task,
                                    // 'user_id' => $user->id, 
                                    // 'service_id'  => $item,
                                    'created_at'  => date('Y-m-d H:i:s')
                                    
                                ];
                                DB::table('task_assignments')->insertGetId($taskdata); 

                                
                            }

                            $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->first_name.' '.$request->middle_name.' '.$request->last_name));

                            $data = 
                                [   'business_id'   =>$business_id,
                                    'candidate_id'  =>$user_id,
                                    'job_id'        =>$job_id,
                                    'name'          =>$name,
                                    'first_name'    =>$request->first_name,
                                    'middle_name'   =>$request->middle_name,
                                    'last_name'     =>$request->last_name,
                                    'email'         =>$request->email,
                                    'phone'         =>$phone,
                                    'address'       => $request->address,
                                    'address_type'       => $request->address_type,
                                    'created_by'    =>$users->id,
                                    'created_at'    =>date('Y-m-d H:i:s')
                                ];
                            
                                DB::table('candidates')->insertGetId($data);
                            
                                $attach_on_select=[];
                                // dd($request->jaf_file_attachments);
                                $allowedextension=['jpg','jpeg','png','svg','pdf','csv','zip','xlsx','docx','docs','xls','JPG','PDF','JPEG','PNG'];
                                $zip_name="";
                                if($request->hasFile('jaf_file_attachments') && $request->file('jaf_file_attachments') !="")
                                {
                                    $filePath = public_path('/uploads/jaf_details/'); 
                                    $files= $request->file('jaf_file_attachments');
                                    foreach($files as $file)
                                    {
                                            $extension = $file->getClientOriginalExtension();
                    
                                            $check = in_array(strtolower($extension),$allowedextension);
                    
                                            if(!$check)
                                            {
                                                return response()->json([
                                                'fail' => true,
                                                'errors' => ['jaf_file_attachments' => 'Only jpg,jpeg,png,pdf,csv,zip,xls,xlsx,docs are allowed !'],
                                                'error_type'=>'validation'
                                                ]);                        
                                            }
                                    }
                                    $zipname = 'jaf_details-'.date('Ymdhis').'-'.$user_id.'.zip';
                                    $zip = new \ZipArchive();      
                                    $zip->open(public_path().'/uploads/jaf_details/'.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                    
                                    foreach($files as $file)
                                    {
                                        $file_data = $file->getClientOriginalName();
                                        $tmp_data  = $user_id.'-'.date('mdYHis').'-'.$file_data; 
                                        $data = $file->move($filePath, $tmp_data);       
                                        $attach_on_select[]=$tmp_data;
                    
                                        $path=public_path()."/uploads/jaf_details/".$tmp_data;
                                        $zip->addFile($path, '/jaf_details/'.basename($path));  
                                    }
                    
                                    $zip->close();
                                }
                    
                                if(count($attach_on_select)>0)
                                {
                                    $i=0;
                                    $file_platform = 'web';
                                    $s3_config = S3ConfigTrait::s3Config();
                    
                                    if($s3_config!=NULL)
                                    {
                                        $file_platform = 's3';
                        
                                        $s3filePath='uploads/jaf_details/';
                        
                                        $path = public_path()."/uploads/jaf_details/";
                        
                                        if(!Storage::disk('s3')->exists($s3filePath))
                                        {
                                            Storage::disk('s3')->makeDirectory($s3filePath,0777, true, true);
                                        }
                        
                                        foreach($attach_on_select as $item)
                                        {
                                            $file = Helper::createFileObject($path.$attach_on_select[$i]);
                        
                                            Storage::disk('s3')->put($s3filePath.$attach_on_select[$i],file_get_contents($file));
                        
                                            $insuff_file=[
                                            'parent_id' => $parent_id,
                                            'business_id'   =>$business_id,    
                                            'candidate_id' => $user_id,                 
                                            'file_name'        =>  $attach_on_select[$i],
                                            'zip_file'        => $zipname!=""?$zipname:NULL, 
                                            'file_platform'   => $file_platform,
                                            'created_by'       =>$users->id,
                                            'created_at'       => date('Y-m-d H:i:s'),
                                            ];
                                    
                                            $file_id = DB::table('jaf_files')->insertGetId($insuff_file);
                        
                                            if(File::exists($path.$attach_on_select[$i]))
                                            {
                                                File::delete($path.$attach_on_select[$i]);
                                            }
                        
                                            $i++;
                        
                                        }
                        
                                        if($zipname!="")
                                        {
                                            $file = Helper::createFileObject($path.$zipname);
                        
                                            Storage::disk('s3')->put($s3filePath.$zipname,file_get_contents($file));
                        
                                            if(File::exists($path.$zipname))
                                            {
                                                File::delete($path.$zipname);
                                            }
                                            
                                        }
                    
                                    }
                                    else
                                    {
                                        foreach($attach_on_select as $item)
                                        {
                                            $insuff_file=[
                                            'parent_id' => $parent_id,
                                            'business_id'   => $business_id,    
                                            'candidate_id' => $user_id,                 
                                            'file_name'        =>  $attach_on_select[$i],
                                            'zip_file'        => $zipname!=""?$zipname:NULL, 
                                            'file_platform'   => $file_platform,
                                            'created_by'       =>$users->id ,
                                            'created_at'       => date('Y-m-d H:i:s'),
                                            ];
                                    
                                            $file_id = DB::table('jaf_files')->insertGetId($insuff_file);
                                            $i++;
                                        }
                                    }
                                    
                                }

                                $kams  = KeyAccountManager::where('business_id',$business_id)->get();
    
                                if (count($kams)>0) {
                                    $candidate_name = $name;
                                    foreach ($kams as $kam) {

                                        $kam_user= User::where('id',$kam->user_id)->first();
                                        
                                        $email = $kam_user->email;
                                        $name  = $kam_user->name;
                                        $candidate_name = $candidate_name;
                                        $displayed_id=$display_id;
                                        $msg = "New Candidate has been created with candidate name";
                                        $sender = User::where(['id'=>$parent_id])->first();
                                        $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender,'displayed_id'=>$displayed_id);

                                        // Mail::send(['html'=>'mails.client-candidate-notify'], $data, function($message) use($email,$name) {
                                        //         $message->to($email, $name)->subject
                                        //         ('myBCD System - Notification for JAF Filling Task');
                                        //         $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                        // });

                                        dispatch(new \App\Jobs\Client\Candidate\CreateNotifyJob($data,$email,$name));

                                    }
                                    
                                }

                                if ($request->jaf_access == 'candidate') {
            
                                    $randomPassword = Str::random(10);
                                    $hashed_random_password = Hash::make($randomPassword);
                                    $candidate = DB::table('candidate_reinitiates')->select('email','business_id','display_id')->where(['id'=>$user_id])->first();
                                    $company = DB::table('user_businesses')->select('company_name')->where(['business_id'=>$business_id])->first();
                                    if ($candidate) {
                            
                                        DB::table('candidate_reinitiates')->where(['id'=>$user_id])->update(['password'=>$hashed_random_password,'status'=>'1']);
                                    }
                        
                                    //send email to customer
                                    $email = $request->email;
                                    $ref_no = $candidate->display_id;
                                    $name  = $request->first_name;
                                    $company_name=$company->company_name;
                                    $id = $candidate->business_id;
                                    $data  = array('name'=>$name,'email'=>$email,'password'=>$randomPassword,'company_name'=>$company_name,'id'=>$id,'ref_no' => $ref_no);
                                    // Mail::send(['html'=>'mails.jaf_info_credential-candidate'], $data, function($message) use($email,$name) {
                                    //     $message->to($email, $name)->subject
                                    //         ('myBCD System - Your account credential');
                                    //     $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                    // });
                                    dispatch(new \App\Jobs\Admin\SendMailCandidateJob($data,$email,$name,$candidate,$company));
                                }

                                // Task JAF Filling with JAF filled, Task Verification & Report Generation...
                                    $cogent_criminal=DB::table('users as u')
                                    ->distinct('js.service_id')
                                    ->select('u.*','js.service_id','s.type_name','js.number_of_verifications')
                                    ->join('job_items as j','j.business_id','=','u.id')
                                    ->join('job_sla_items as js','js.job_item_id','=','j.id')
                                    ->join('services as s','s.id','=','js.service_id')
                                    ->where(['u.user_type'=>'client'])
                                    ->where('j.candidate_id',$user_id)
                                    ->where('u.id',$business_id)
                                    ->groupBy('js.service_id')
                                    ->get();

                                    if($business_id==1831 && (count($cogent_criminal)==1 && $cogent_criminal->contains('type_name','criminal') && $cogent_criminal->contains('number_of_verifications',1)))
                                    {
                                        $jaf_form_data_check = DB::table('jaf_form_data')->where('candidate_id',$user_id)->count();

                                        $job_sla_items = DB::table('job_sla_items')->where('candidate_id',$user_id)->get();

                                        $candidate = DB::table('candidate_reinitiates')->where('id',$user_id)->first();

                                        $job_item_filled =DB::table('job_items')->where(['candidate_id'=>$candidate->id,'jaf_status'=>'filled'])->first();

                                        $primary_kam = DB::table('key_account_managers')->where('business_id',$business_id)->where('is_primary','1')->first();

                                        // JAF Filling Task Complete & Task Verification Complete & Report Generation Task

                                        if($jaf_form_data_check==0)
                                        {
                                            foreach($job_sla_items as $service){

                                                $input_items = DB::table('service_form_inputs as sfi')
                                                                ->select('sfi.*','s.type_name')
                                                                ->join('services as s','s.id','=','sfi.service_id')            
                                                                ->where(['sfi.service_id'=>$service->service_id,'sfi.status'=>1])
                                                                ->whereNull('sfi.reference_type')
                                                                ->whereNotIn('sfi.label_name',['Mode of Verification','Remarks'])
                                                                ->get();

                                                $numbers_of_items = $service->number_of_verifications;

                                                for($j=1; $j<=$numbers_of_items; $j++){

                                                    $input_data = [];
                                                    $reference_type = NULL;
                                                    //$i=0;

                                                    foreach($input_items as $input){

                                                        $input_val = NULL;

                                                        if($input->label_name=='First Name' || $input->label_name=='First name' || $input->label_name=='first name'){ 
                                                            $input_val = $candidate->first_name;
                                                        }
                                                        else if($input->label_name=='Last Name' || $input->label_name=='Last name' || $input->label_name=='last name'){ 
                                                            $input_val = $candidate->last_name;
                                                        }
                                                        //father name
                                                        else if($input->label_name=='Father Name' || $input->label_name=='father name' || $input->label_name=='Father name'){ 
                                                            $input_val = $candidate->father_name;
                                                        }
                                                        //dob
                                                        else if($input->label_name=='Date of Birth' || $input->label_name=='DOB' || $input->label_name=='dob'){ 
                                                            $dob = $candidate->dob;
                                                            if($dob !=NULL){
                                                                $input_val = date('d-m-Y',strtotime($candidate->dob));
                                                            }
                                                        }
                                                        else if(stripos($input->label_name,'Email Address')!==false)
                                                        {
                                                            $input_val = $candidate->email;
                                                        }

                                                        if(stripos($input->type_name,'criminal')!==false)
                                                        {
                                                            if($input->label_name=='Address' || $input->label_name=='Address ')
                                                            {
                                                                $input_val = $candidate->address;
                                                            }
                                                            else if($input->label_name=='Address Type' || $input->label_name=='Address Type ')
                                                            {
                                                                $input_val = $candidate->address_type;
                                                            }
                                                        }

                                                        $input_data[] = [
                                                                $input->label_name=>$input_val,
                                                                'is_report_output'=>$input->is_report_output 
                                                        ];
                                                    }

                                                    $is_insufficiency = 0;

                                                    $jaf_data = json_encode($input_data);
                                                
                                                    $jaf_form_data = [
                                                                        'form_data'       => $jaf_data,
                                                                        'form_data_all'   => $jaf_data,
                                                                        'is_insufficiency'=>$is_insufficiency,
                                                                        'insufficiency_notes'=>NULL,
                                                                        'address_type'  =>NULL,
                                                                        'reference_type'  =>$reference_type,
                                                                        'business_id' => $service->business_id,
                                                                        'job_id'      => $service->job_id,
                                                                        'job_item_id' => $service->job_item_id,
                                                                        'service_id'  => $service->service_id,
                                                                        'candidate_id' => $service->candidate_id,
                                                                        'check_item_number'=>$j,
                                                                        'sla_id'      =>$service->sla_id,
                                                                        'is_filled' => '1',
                                                                        'created_by'   => $users->id,
                                                                        'created_at'   => date('Y-m-d H:i:s')
                                                                    ];
                                                                    
                                                    $jaf_data =JafFormData::create($jaf_form_data);
                                                        
                                                }
                                            }

                                            DB::table('job_items')->where(['candidate_id'=>$candidate->id])->update(['jaf_status'=>'filled','filled_by'=>$users->id,'filled_at'=>date('Y-m-d H:i:s')]);

                                            $report = DB::table('reports')->where('candidate_id',$candidate->id)->first();

                                            if($report==NULL)
                                            {
                                                $job = DB::table('job_items')->where(['candidate_id'=>$candidate->id])->first(); 
                      
                                                $data = 
                                                [
                                                    'parent_id'     =>$parent_id,
                                                    'business_id'   =>$job->business_id,
                                                    'candidate_id'  =>$candidate->id,
                                                    'sla_id'        =>$job->sla_id,       
                                                    'created_at'    =>date('Y-m-d H:i:s')
                                                ];
                                                $report_id = DB::table('reports')->insertGetId($data);

                                                $jaf_items_datas = DB::table('jaf_form_data')->where(['candidate_id'=>$candidate->id])->get();

                                                foreach($jaf_items_datas as $item){

                                                    if ($item->verification_status == 'success') {
                                                        $data = 
                                                        [
                                                          'report_id'     =>$report_id,
                                                          'service_id'    =>$item->service_id,
                                                          'service_item_number'=>$item->check_item_number,
                                                          'candidate_id'  =>$candidate->id,      
                                                          'jaf_data'      =>$item->form_data,
                                                          'jaf_id'        =>$item->id,
                                                          'created_at'    =>date('Y-m-d H:i:s')
                                                        ];
                                                    } 
                                                    else {
                                                        $data = 
                                                        [
                                                          'report_id'     =>$report_id,
                                                          'service_id'    =>$item->service_id,
                                                          'service_item_number'=>$item->check_item_number,
                                                          'candidate_id'  =>$candidate->id,      
                                                          'jaf_data'      =>$item->form_data,
                                                          'jaf_id'        =>$item->id,
                                                          'is_report_output' => '0',
                                                          'created_at'    =>date('Y-m-d H:i:s')
                                                        ]; 
                                                    }

                                                    $report_item_id = DB::table('report_items')->insertGetId($data);

                                                }
                                            }

                                            $job_item_filled =DB::table('job_items')->where(['candidate_id'=>$candidate->id,'jaf_status'=>'filled'])->first();

                                            if($job_item_filled!=NULL)
                                            {
                                                $task = Task::where(['business_id'=>$candidate->business_id,'candidate_id'=>$candidate->id,'description'=>'JAF Filling'])->first();

                                                $task_id='';
                                                if ($task) {
                                                    # code...
                                                    $task_id = $task->id;
                                                    $task->assigned_by = $primary_kam!=NULL ? $primary_kam->user_id : $parent_id;
                                                    $task->assigned_to = $primary_kam!=NULL ? $primary_kam->user_id : $parent_id;
                                                    $task->assigned_at = date('Y-m-d H:i:s');
                                                    $task->start_date = date('Y-m-d');
                                                    $task->is_completed= 1;
                                                    $task->completed_at = date('Y-m-d H:i:s');
                                                    $task->updated_at=date('Y-m-d H:i:s');
                                                    $task->save();
                                                    
                                                    //Change status of old task 
                                                    $task_assgn = TaskAssignment::where(['business_id'=>$candidate->business_id,'candidate_id'=>$candidate->id,'status'=>"1",'task_id'=>$task_id])->first();
                                                    
                                                    if($task_assgn)
                                                    {
                                                        $task_assgn->user_id = $primary_kam!=NULL ? $primary_kam->user_id : $parent_id;
                                                        $task_assgn->status= '2';
                                                        $task_assgn->updated_at = date('Y-m-d H:i:s');
                                                        $task_assgn->save();
                                                    }

                                                }

                                                foreach ($job_sla_items as $job_sla_item) {

                                                    $verify_task = Task::where(['business_id'=>$candidate->business_id,'candidate_id'=>$candidate->id,'service_id'=>$job_sla_item->service_id,'number_of_verifications'=>$job_sla_item->number_of_verifications,'description'=>'Task for Verification'])->first();
                                                    if($verify_task==NULL)
                                                    {
                                                        //Get data of user of customer with 
                                                        $user_permissions = DB::table('users as u')
                                                                            ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                                                                            ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                                                                            ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
                                                                            ->where('u.business_id',$parent_id)
                                                                            ->get();

                                                        // Get JAF FILLING data from Action table for matching checking permission
                                                        $action_master = DB::table('action_masters')
                                                                            ->select('*')
                                                                            ->where(['route_group'=>'','action_title'=>'JAF Filled'])
                                                                            ->first(); 

                                                        if(count($user_permissions)>0) {
                                                            $users_p=[];
                                                            foreach ($user_permissions as $user_permission) {  
                                                                if(in_array($action_master->id,json_decode($user_permission->permission_id)))
                                                                {
                                                                    $users_p[]= $user_permission;
                                                                }
                                                            }
                                                            $final_users = [];
                                                            $numbers_of_items = $job_sla_item->number_of_verifications;
                                                            if($numbers_of_items > 0){
                                                                for ($i=1; $i <= $numbers_of_items; $i++) { 
                                                                    $final_users = [];

                                                                    //insert in task
                                                                    $data = [
                                                                        'name'          => $candidate->first_name.' '.$candidate->last_name,
                                                                        'parent_id'=> $parent_id,
                                                                        'business_id'   => $business_id, 
                                                                        'description'   => 'Task for Verification',
                                                                        'job_id'        => NULL, 
                                                                        'priority'      => 'normal',
                                                                        'candidate_id'  => $candidate->id,   
                                                                        'service_id'    => $job_sla_item->service_id, 
                                                                        'number_of_verifications' => $i,
                                                                        'assigned_to'   => $primary_kam!=NULL ? $primary_kam->user_id : $parent_id,
                                                                        'assigned_by'   => $primary_kam!=NULL ? $primary_kam->user_id : $parent_id,
                                                                        'assigned_at'   => date('Y-m-d H:i:s'),
                                                                        'start_date'    => date('Y-m-d'),
                                                                        'created_by'    => $users->id,
                                                                        'created_at'    => date('Y-m-d H:i:s'),
                                                                        'updated_at'  => date('Y-m-d H:i:s'),
                                                                        'is_completed'  => 1,
                                                                        'started_at'    => date('Y-m-d H:i:s'),
                                                                        'completed_at'    => date('Y-m-d H:i:s'),
                                                                    ];
                                                                    
                                                                    $task_id =  DB::table('tasks')->insertGetId($data);

                                                                    $taskdata = [
                                                                        'parent_id'     => $parent_id,
                                                                        'business_id'   => $business_id,
                                                                        'candidate_id'  =>$candidate->id,   
                                                                        'job_sla_item_id'  => $job_sla_item->id,
                                                                        'task_id'       => $task_id,
                                                                        'user_id'       =>  $primary_kam!=NULL ? $primary_kam->user_id : $parent_id,
                                                                        'service_id'    =>$job_sla_item->service_id,
                                                                        'number_of_verifications' => $i,
                                                                        'created_at'    => date('Y-m-d H:i:s'),
                                                                        'updated_at'    => date('Y-m-d H:i:s'),
                                                                        'status'        => '2'  
                                                                      ];
                                                                      
                                                                      DB::table('task_assignments')->insertGetId($taskdata);
                                                                }
                                                            }

                                                        }


                                                    }
                                                    
                                                }

                                                //
                                                $verify_task = Task::where(['business_id'=>$candidate->business_id,'candidate_id'=>$candidate->id,'description'=>'Task for Verification'])->first();

                                                if($verify_task!=NULL)
                                                {
                                                    $report_status = DB::table('reports')->where(['candidate_id'=>$candidate->id,'status'=>'incomplete'])->first();

                                                    if($report_status!=NULL)
                                                    {
                                                        $job_item = JobItem::where('candidate_id',$candidate->id)->first();

                                                        $users_id = DB::table('candidate_reinitiates')->where('id',$candidate->id)->first();

                                                        $job_sla_item_id = DB::table('job_sla_items')->where('candidate_id',$candidate->id)->first();

                                                        JobItem::find($job_item->id)->update([
                                                            'is_jaf_ready_report' => 1,
                                                            'jaf_ready_report_by' => $primary_kam!=NULL ? $primary_kam->user_id : $parent_id,
                                                            'jaf_ready_report_at' => date('Y-m-d H:i:s')
                                                        ]);

                                                        $data = [
                                                            'name'   =>$users_id->name,
                                                            'parent_id'=> $parent_id,
                                                            'business_id'=> $business_id, 
                                                            'description' => 'Report generation',
                                                            'job_id'      => $job_item->job_id, 
                                                            'priority' => 'normal',
                                                            'candidate_id' =>$candidate->id,   
                                                            // 'service_id'  => $item, 
                                                            // 'assigned_to' =>$assigned_user_id,
                                                            // 'assigned_by' => Auth::user()->id, 
                                                            // 'assigned_at' => date('Y-m-d H:i:s'),
                                                            // 'start_date' => date('Y-m-d'),
                                                            'created_by'    =>$users->id,
                                                            'created_at'  => date('Y-m-d H:i:s'),
                                                            'is_completed' => 0,
                                                            'status'=>'1',
                                                            'started_at' => date('Y-m-d H:i:s'),
                                                            'updated_at'  => date('Y-m-d H:i:s')
                                                          ];
                                                          
                                                          $task =Task::create($data);
                                                          $task  =$task->id;

                                                          $taskdata = [
                                                            'parent_id'=> $parent_id,
                                                            'business_id'=> $users_id->business_id,
                                                            'candidate_id' =>$candidate->id, 
                                                            'task_id'=> $task,
                                                            'job_sla_item_id'=> $job_sla_item_id->id,
                                                            // 'user_id' => $user->id,
                                                            // 'service_id'  => $item,
                                                            'created_at'  => date('Y-m-d H:i:s'),
                                                            'updated_at'  => date('Y-m-d H:i:s')
                                                          
                                                          ];

                                                          TaskAssignment::create($taskdata);
                                                    }
                                                }

                                            }

                                        }
                                        
                                    }


                            DB::commit();
                            $response=['status'=>true,'message' => 'Candidate Created Successfully!!','candidate_id'=>$display_id];
                    }
                    else
                    {
                        $response=['status'=>false,
                                    'data'=>NULL,
                                    'message' => 'Something Went wrong!!'
                            ];
                    }
                }
                else
                {
                    $response=['status'=>false,
                            'data'=>NULL,
                            'message' => 'Wrong credentials!!'
                            ];
                }
            }
            else {
                $response=['status'=>false, 
                            'message' => 'The reference_number field is required!!'
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
     * SLA List
     *
     * This API is used for show the SLA list based on who logs in (i.e; if the user belongs to an Admin/COC).
     * 
     * @authenticated
     * 
     * @bodyParam  login_id int required Login ID for finding the user. Example: XX
     * 
     * @response 401 {"status":false,"data":null,"message":"User Not Found!!"}
     * 
     * @response 404 {"status":false,"data":null,"message":"No Data Found!!"}
     * 
     * @responseFile responses/sla/list/success.get.json
     * 
     */
    public function get_sla_list(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'login_id'  => 'required',
        //     // 'services' => 'required|array|min:1' 
        // ]);

        
        // if ($validator->fails()) {            
        //     return response()->json(['status' => 'error',
        //                             'message'=>'The given data was invalid.',
        //                             'errors'=> $validator->errors()], 200);
        // }

        $ref_no=$request->reference_number;
        // DB::beginTransaction();
        try{
            if($ref_no){
                $users=DB::table('users')->where(DB::raw('BINARY `display_id`'),$ref_no)->first();
                if($users!=NULL)
                {
                    $business_id=$users->business_id;
                    $parent_id=$users->parent_id;
                    $user_d=DB::table('users')->where('id',$business_id)->first();
                    $array_result=[];
                if($user_d->user_type=='client')
                    {
                        $sla = DB::table('customer_sla as sla')
                        ->select('sla.id','sla.title','sla.tat','sla.client_tat','ub.company_name','sla.sla_type','sla.days_type','sla.tat_type')
                        ->join('users as u','u.id','=','sla.business_id')
                        ->join('user_businesses as ub','ub.business_id','=','sla.business_id')
                        ->where(['u.business_id'=>$business_id,'sla.sla_type'=>'package'])
                        ->get();
                        // echo "<pre>";
                            // print_r($sla);
                        // echo "</pre>";

                        // die;

                        foreach($sla as $item)
                        {
                            $sla_items = DB::table('customer_sla_items as sla')
                                        ->select('s.id','s.name','s.verification_type','sla.number_of_verifications','sla.tat','sla.incentive_tat','sla.penalty_tat')
                                        ->join('services as s','s.id','=','sla.service_id')
                                        ->where(['sla.sla_id'=>$item->id])
                                        // ->groupBy('sla.sla_id')
                                        ->get();
                            $tat=$item->client_tat;
                            $service_array=[];
                            foreach($sla_items as $service)
                            {
                                $service_array[]=['service_name'=>$service->name,'no_of_verifications'=>$service->number_of_verifications,'service_tat'=>$service->tat,'service_incentive_tat'=>$service->incentive_tat,'service_penalty_tat'=>$service->penalty_tat];
                            }
                            
                            
                            $array_result[$item->id]=['sla_name'=>$item->title,'company_name'=>$item->company_name,'sla_type'=>$item->sla_type,'days_type'=>$item->days_type,'tat_type'=>$item->tat_type,'tat'=>$tat,'service_detail'=>$service_array];

                            // dd($array_result);
                        }
                        DB::commit();
                        $response=[
                            'status'=>true,
                            'data' => $array_result,
                        ];
                    }
                    else
                    {
                        $response=[
                            'status'=>false,
                            'data' => NULL,
                            'message' => 'No Data Found !!'
                        ];
                    }
                }
                else
                {
                    $response=['status'=>false,'message'=>'Wrong credentials!!'];
                }
            }
            else {
                $response=['status'=>false,'message'=>'The reference_number field is required!!'];
            }
            return response()->json($response,200);
        }
        catch (\Exception $e) {
            // DB::rollback();
            // something went wrong
            return $e;
        }
    }

    public function candidateJaf(Request $request)
    {
        $candidate_id = $request->candidate_id;
        // dd($candidate_id);
        $verification_array=[];
        $verification_data=[];
        $files=[];
        if ($candidate_id) {
            $users=DB::table('candidate_reinitiates')->where(DB::raw('BINARY `display_id`'),$candidate_id)->first();
            
            if($users!=NULL){
                $jaf_form_data_check = DB::table('jaf_form_data')->where('candidate_id',$users->id)->count();
                // dd($jaf_form_data_check);
                $job_sla_items = DB::table('job_sla_items')->where('jaf_send_to','coc')->where('candidate_id',$users->id)->get();
                // dd($job_sla_items);
                if ( count($job_sla_items) > 0) {
                    if ( $jaf_form_data_check == 0) {
                        $verification_array['candidate_id']= $candidate_id;
                        foreach($job_sla_items as $service){
                            $input_items = DB::table('service_form_inputs as sfi')
                            ->select('sfi.*')            
                            ->where(['sfi.service_id'=>$service->service_id,'status'=>1])
                            ->get();

                            $numbers_of_items = $service->number_of_verifications;
                            $input_data = [];
                            // $field_name=[];
                            for($j=1; $j<=$numbers_of_items; $j++){
                                $input_data=$input_items->pluck('label_name')->all();
                                if ($service->service_id==1) {
                                    // if ($service->service_id==1) {
                                        // array_merge(['address_type' => ''], $input_data);
                                        array_unshift($input_data, 'Address Type');
                                    // }
                                    // array_merge(['address_type' => ''], $input_data);
                                }
                                $field_name=[];
                                foreach ($input_data as $key => $value) {
                                    $field_name[$value]='';
                                }
                                $i=0;
                                $jaf_form_data = [
                                    'business_id' => $service->business_id,
                                    'job_id'      => $service->job_id,
                                    'job_item_id' => $service->job_item_id,
                                    'service_id'  => $service->service_id,
                                    'candidate_id' => $service->candidate_id,
                                    'check_item_number'=>$j,
                                    'sla_id'      =>$service->sla_id,
                                    'created_at'   => date('Y-m-d H:i:s')];

                                $jaf_data= DB::table('jaf_form_data')->insert($jaf_form_data);
                                $service_name= DB::table('services')->where('id',$service->service_id)->first();
                                $verification_name = $service_name->name.'-'.$j;
                                $verification_array[$verification_name]= ['data'=>$field_name,'file'=>$files];
                            }
                        }
                        
                        $response=[
                            'status'=>true,
                            'data' => $verification_array,
                        ];
                    }
                    else{
                        $verification_array['candidate_id']= $candidate_id;
                        foreach($job_sla_items as $service){
                            $input_items = DB::table('service_form_inputs as sfi')
                            ->select('sfi.*')            
                            ->where(['sfi.service_id'=>$service->service_id,'status'=>1])
                            ->get();

                            $numbers_of_items = $service->number_of_verifications;
                            $input_data = [];
                            // $field_name=[];
                            for($j=1; $j<=$numbers_of_items; $j++){
                                $input_data=$input_items->pluck('label_name')->all();
                                // echo $service->service_id;
                                if ($service->service_id==1) {
                                    // array_merge(['address_type' => ''], $input_data);
                                    array_unshift($input_data, 'Address Type');
                                }
                                // dd($input_data);
                                $field_name=[];
                                foreach ($input_data as $key => $value) {
                                    $field_name[$value]='';
                                }
                                $i=0;
                                // $jaf_form_data = [
                                //     'business_id' => $service->business_id,
                                //     'job_id'      => $service->job_id,
                                //     'job_item_id' => $service->job_item_id,
                                //     'service_id'  => $service->service_id,
                                //     'candidate_id' => $service->candidate_id,
                                //     'check_item_number'=>$j,
                                //     'sla_id'      =>$service->sla_id,
                                //     'created_at'   => date('Y-m-d H:i:s')];

                                // $jaf_data= DB::table('jaf_form_data')->insert($jaf_form_data);
                                $service_name= DB::table('services')->where('id',$service->service_id)->first();
                                $verification_name = $service_name->name.'-'.$j;
                                $verification_array[$verification_name]= ['data'=>$field_name,'file'=>$files];
                            }
                        }
                        // array_unshift($verification_array,$candidate_id);
                        $response=[
                            'status'=>true,
                            'data' => $verification_array,
                        ];
                    }
                }
                else{
                    $response=[
                        'status'=>false,
                        'data' => NULL,
                        'message' => 'JAF Filling Does Not Exist !!'
                    ];
                }
            }
            else {
                $response=[
                    'status'=>false,
                    'data' => NULL,
                    'message' => 'No Data Found !!'
                ];
            }
        }
        else {
            $response=['status'=>false,'message'=>'The Candidate ID is required!!'];
        }
        return response()->json($response,200);
    }

    public function fileUpload(Request $request)
    {
        $candidate_id = $request->candidate_id;
        $check_name = $request->check_name;
        $files=[];
        $i=0;
        if ( $candidate_id) {
            $user= DB::table('candidate_reinitiates')->where(DB::raw('BINARY `display_id`'),$candidate_id)->first();
            if ($user) {
                $jobitem= DB::table('job_items')->where('candidate_id',$user->id)->first();
                if ($jobitem->jaf_status!='filled') {
                   
                    $extensions = array("jpg","png","jpeg","PNG","JPG","JPEG","gif","pdf");
                    if($request->hasFile('files')) {
                        // $files['check_name']=$check_name;
                        foreach( $request->file('files') as $item){
                            $result = array($request->file('files')[$i]->getClientOriginalExtension());

                            if(in_array($result[0],$extensions))
                            {                      
                                $file_platform = 'web';

                                $s3_config = S3ConfigTrait::s3Config();
                    
                                $attachment_file  = $request->file('files')[$i];
                                $orgi_file_name   = $attachment_file->getClientOriginalName();
                                
                                $fileName = pathinfo($orgi_file_name,PATHINFO_FILENAME);
                    
                                $filename         = time().'-'.$fileName.'.'.$attachment_file->getClientOriginalExtension();
                                $dir              = public_path('/uploads/jaf-files/');
                                $request->file('files')[$i]->move($dir, $filename);

                                        
                                $extArray = explode('.', $filename);
                                $ext = end($extArray);

                                $file_id_array = [];
                                $file_name_array = [];
                                $file_url_array = [];
                               
                                if(stripos($ext,'pdf')!==false)
                                {
                                    $file_platform = 'web';
                                    if(File::exists($dir.$filename))
                                    {
                                        $pdf_file_name = $fileName.'-'.time();

                                        $imagick = new Imagick();

                                        $imagick->setResolution(300, 300);

                                        $imagick->readImage($dir.$filename);

                                        $imagick->setImageFormat("png");

                                        $pages = $imagick->getNumberImages();

                                        $imagick->writeImages($dir.$pdf_file_name.'.png', false);

                                        if($pages)
                                        {
                                            if($pages==1)
                                            {
                                                if($s3_config!=NULL)
                                                {
                                                    $file_platform = 's3';

                                                    $file_name = $pdf_file_name.'.png';

                                                    $path = 'uploads/jaf-files/';

                                                    if(!Storage::disk('s3')->exists($path))
                                                    {
                                                        Storage::disk('s3')->makeDirectory($path,0777, true, true);
                                                    }

                                                    $file = Helper::createFileObject($path.$file_name);

                                                    Storage::disk('s3')->put($path.$file_name, file_get_contents($file));

                                                }
                                                $file_url = '';
                                                if(stripos($file_platform,'s3')!==false)
                                                {
                                                    $filePath = 'uploads/jaf-files/';
                                                    if(File::exists(public_path('/uploads/jaf-files/'.$pdf_file_name.'.png')))
                                                    {
                                                        File::delete(public_path('/uploads/jaf-files/'.$pdf_file_name.'.png'));
                                                    }

                                                    $disk = Storage::disk('s3');

                                                    $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                                        'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                                        'Key'                        => $filePath.$pdf_file_name.'.png',
                                                        'ResponseContentDisposition' => 'attachment;'//for download
                                                    ]);

                                                    $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                                    $file_url = (string)$req->getUri();
                                                }
                                                else
                                                {
                                                    $file_url = url('/').'/uploads/jaf-files/'.$pdf_file_name.'.png';
                                                }

                                                //   $file_id_array[] = $rowID;
                                                // $file_name_array[] = $pdf_file_name.'.png';
                                                // $file_url_array[] = $file_url;

                                                $files[]=['file_name'=>$pdf_file_name.'.png','file_url'=>$file_url,'file_platform'=>$file_platform];
                                            }
                                            else
                                            {
                                                for($i=0;$i<$pages;$i++)
                                                {
                                                        $file_platform = 'web';

                                                        if($s3_config!=NULL)
                                                        {
                                                            $file_platform = 's3';

                                                            $file_name = $pdf_file_name.'-'.$i.'.png';

                                                            $path = 'uploads/jaf-files/';

                                                            if(!Storage::disk('s3')->exists($path))
                                                            {
                                                                Storage::disk('s3')->makeDirectory($path,0777, true, true);
                                                            }

                                                            $file = Helper::createFileObject($path.$file_name);

                                                            Storage::disk('s3')->put($path.$file_name, file_get_contents($file));

                                                        }
                                                    
                                                    $file_url = '';
                                                    if(stripos($file_platform,'s3')!==false)
                                                    {
                                                        $filePath = 'uploads/jaf-files/';
                                                        if(File::exists(public_path('/uploads/jaf-files/'.$pdf_file_name.'-'.$i.'.png')))
                                                        {
                                                            File::delete(public_path('/uploads/jaf-files/'.$pdf_file_name.'-'.$i.'.png'));
                                                        }

                                                        $disk = Storage::disk('s3');

                                                        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                                            'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                                            'Key'                        => $filePath.$pdf_file_name.'-'.$i.'.png',
                                                            'ResponseContentDisposition' => 'attachment;'//for download
                                                        ]);

                                                        $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                                        $file_url = (string)$req->getUri();
                                                    }
                                                    else
                                                    {
                                                        $file_url = url('/').'/uploads/jaf-files/'.$pdf_file_name.'-'.$i.'.png';
                                                    }
                    
                                                    //   $file_id_array[] = $rowID;
                                                    $file_name_array[] = $pdf_file_name.'-'.$i.'.png';
                                                    $file_url_array[] = $file_url;  
                                                    $files[]=['file_name'=>$pdf_file_name.'-'.$i.'.png','file_url'=>$file_url,'file_platform'=>$file_platform];
                                                }
                                            }
                                        }

                                        File::delete($dir.$filename);

                                    }
                                }
                                else
                                {
                                    $file_platform = 'web';
                                    if($s3_config!=NULL)
                                    {
                                        $file_platform = 's3';

                                        $path = 'uploads/jaf-files/';

                                        if(!Storage::disk('s3')->exists($path))
                                        {
                                            Storage::disk('s3')->makeDirectory($path,0777, true, true);
                                        }

                                        $file = Helper::createFileObject($path.$filename);

                                        Storage::disk('s3')->put($path.$filename, file_get_contents($file));

                                        $filePath = 'uploads/jaf-files/';
        
                                        $disk = Storage::disk('s3');
        
                                        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                            'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                            'Key'                        => $filePath.$filename,
                                            //'ResponseContentDisposition' => 'attachment;'//for download
                                        ]);
        
                                        $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');
        
                                        $type = (string)$req->getUri();

                                        $files[]=['file_name'=>$filename,'file_url'=>$type,'file_platform'=>$file_platform];

                                    }
                                    else {
                                        $type = url('/').'/uploads/jaf-files/'.$filename;
                                        $files[]=['file_name'=>$filename,'file_url'=>$type,'file_platform'=>$file_platform];
                                    }

                                    if(stripos($file_platform,'s3')!==false)
                                    {
                                        if(File::exists(public_path('/uploads/jaf-files/'.$filename)))
                                        {
                                            File::delete(public_path('/uploads/jaf-files/'.$filename));
                                        }
                                    }
                                }
                              
                                //$files[] = ['filePrev'=> $type,'file_id'=>$rowID];'check_name'=>$check_name,'check_name'=>$check_name,
        
                                // if($ext=='pdf')
                                // {
                                // $files[] = ['filePath'=> $file_url_array,'file_name'=>$file_name_array];
                                // }
                                // else
                                // {
                                // $files[] = ['filePath'=> $type,'file_name'=>$filename];
                                // }
        
                                $i++;
                            }
                            else{
                                // Do something when it fails
                                $response=[
                                    'fail' => true,
                                    'errors' => 'File type error!'
                                ];
                            }

                        }
                        
                        $response=[
                            'status'=>true,
                            'message' => 'Image has been uploaded successfully!!',
                            'check_name'=>$check_name,
                            'data'=>$files
                            
                        ];
                    }
                }
                else {
                    $response=[
                        'status'=>false,
                        'data' => NULL,
                        'message' => 'JAF has been already filled !!'
                    ];
                }
            }
            else {
                $response=[
                    'status'=>false,
                    'data' => NULL,
                    'message' => 'No Data Found !!'
                ];
            }
           
        }else {
            $response=['status'=>false,'message'=>'The Candidate ID is required!!'];
        }
       
        return response()->json($response,200);
    }

    public function JafUpload(Request $request)
    {
       
        $data = $request->all();
      
        $candidate_id =$data["candidate_id"];

         if ($candidate_id) {
            
            $user=DB::table('candidate_reinitiates')->where(DB::raw('BINARY `display_id`'),$candidate_id)->first();
            if($user!=NULL){
                $jaf_form_datas = DB::table('jaf_form_data')->where('candidate_id',$user->id)->get();
                // dd($users);
                // if (stripos($users[],'Address-')!==false) {

                //     dd($users["Address-1"]);
                // }
                $jobitem= DB::table('job_items')->where('candidate_id',$user->id)->first();
                if ($jobitem->jaf_status!='filled') {   
                    foreach ($jaf_form_datas as $key => $jaf_form_data) {
                        $services = DB::table('services')->where('id',$jaf_form_data->service_id)->first();
                        $service_name= $services->name.'-'.$jaf_form_data->check_item_number;
                        $input_items = DB::table('service_form_inputs as sfi')
                                                ->select('sfi.*')            
                                                ->where(['sfi.service_id'=>$services->id,'status'=>1])
                                                ->get();
                                                // dd($data[$service_name]['file']);
                        if(array_key_exists($service_name,$data)){
                            $input_data = [];
                            $reference_type = NULL;
                            $i=0;
                            $address_type='';
                            foreach($input_items as $input){
                            
                                if (array_key_exists($input->label_name,$data[$service_name]['data'])) {
                                    
                                    if($input->service_id==17)
                                    {
                                        if($input->reference_type==NULL && !(stripos($input->label_name,'Mode of Verification')!==false || stripos($input->label_name,'Remarks')!==false))
                                        { 
                                            $input_data[] = [
                                                // $input->label_name.'-'.$jaf_form_data->id.'-'.$jaf_form_data->check_item_number=>$data[$service_name][$input->label_name],
                                                $input->label_name=>$data[$service_name]['data'][$input->label_name],
                                                'is_report_output'=>$input->is_report_output 
                                            ];

                                            if(stripos($data[$service_name]['data'][$input->label_name],'Reference Type (Personal / Professional)')!==false)
                                            {
                                                $reference_type = strtolower($data[$service_name]['data'][$input->label_name]);
                                            }

                                        }
                                    }
                                    else
                                    {
                                        $input_data[] = [
                                            // $input->label_name.'-'.$jaf_form_data->id.'-'.$jaf_form_data->check_item_number=>$data[$service_name][$input->label_name],
                                            $input->label_name=>$data[$service_name]['data'][$input->label_name],
                                            'is_report_output'=>$input->is_report_output 
                                        ];
                                    }
                                    if($input->service_id==1){
                                        // dd($data[$service_name]['data']['Address Type']);
                                        $address_type= ($data[$service_name]['data']['Address Type']!="")?strtolower($data[$service_name]['data']['Address Type']):NULL;
                                    }
                                    else{
                                        $address_type= NULL;
                                    }
                                }
                                // $i++;
                            }
                        
                            $jaf_data = json_encode($input_data);

                            $jfd = [
                                    'form_data'       => $jaf_data,
                                    'form_data_all'   => $request->all(),
                                    'address_type'    =>$address_type,
                                    'reference_type'  =>$reference_type,
                                    'is_filled'       => '1',
                                    'updated_at'      => date('Y-m-d H:i:s')
                                ];
            
                                DB::table('jaf_form_data')->where(['id'=>$jaf_form_data->id])->update($jfd);
                            // echo $address_type'<br>~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ <pre>'; var_dump($input_data); 
                            // dd($jaf_data);
                            // dd($data[$service_name]['file']);
                            if(count($data[$service_name]['file'])>0){
                                
                                foreach ($data[$service_name]['file'] as $key => $jaf_file) {
                                    $rowID = DB::table('jaf_item_attachments')
                                    ->insertGetId([
                                    'jaf_id'        =>$jaf_form_data->id, 
                                    'business_id'   =>$jaf_form_data->business_id,    
                                    'candidate_id' => $user->id,                       
                                    'file_name'        => $jaf_file['file_name'],
                                    'attachment_type'  => 'main',
                                    'file_platform'     =>$jaf_file['file_platform'],
                                    // 'created_by'       => Auth::user()->id,
                                    'created_at'       => date('Y-m-d H:i:s'),
                                    'is_temp'          => 0,
                                ]);
                                }
                            
                            }
                        }
                        
                        // foreach ($data as $key => $value) {
                        //     // dd($key);
                        //     if (stripos($key,$service_name)!=false) {
                        //         dd($service_name);
                        //     }
                        //         // if (stripos($key,'Address-')!==false && (stripos($key,'-1')!==false || stripos($key,'-2')!==false)) {
                    
                        //         //     dd($users["Address-1"]);
                        //         // }
                        //         // dd($key);
                                
                    
                        //     }
                            
                    
                        // if ($jaf_form_data->service_id) {
                        //     # code...
                        // } else {
                        //     # code...
                        // }
                        
                        // dd($jaf_form_data->service_id);
                    }
                    $serviceUpi = DB::table('services')->select('id','name','type_name')->where('type_name','upi')->first();
                    DB::table('job_items')->where(['candidate_id'=>$user->id])->update(['jaf_status'=>'filled','filled_at'=>date('Y-m-d H:i:s')]);
                    $job_item =DB::table('job_items')->where(['candidate_id'=>$user->id,'jaf_status'=>'filled'])->first();
                    if ( $job_item->jaf_status == 'filled') {

                        $jfd_service= DB::table('jaf_form_data')->where('candidate_id',$user->id)->whereIn('service_id',['2','3','4','7','8','9','12',$serviceUpi->id])->get();
                        $jfd_service=$jfd_service->toArray();

                        // dd($jfd_service);
                        event(new ApiCheckByRestApi($jfd_service,$user->business_id));

                        $userDT   =  DB::table('candidate_reinitiates')->where(['id'=>$user->id])->first();

                        $task = Task::where(['business_id'=>$userDT->business_id,'candidate_id'=>$userDT->id,'is_completed'=>0,'description'=>'JAF Filling'])->first();
                            //   dd($task);
                            $task_id='';
                            if ($task) {
                                # code...
                                $task_id = $task->id;

                                    $task->is_completed= 1;
                                    $task->updated_at=date('Y-m-d H:i:s');
                                    $task->save();
                                
                                //Change status of old task 
                                $task_assgn = TaskAssignment::where(['business_id'=>$userDT->business_id,'candidate_id'=>$userDT->id,'status'=>"1",'task_id'=>$task_id])->first();
                                
                                if($task_assgn)
                                {
                                $task_assgn->status= '2';
                                $task_assgn->updated_at = date('Y-m-d H:i:s');
                                $task_assgn->save();
                                }
                            }
                                // task assign start
                                $final_users = [];
                                // $j = 0;
                                $job_sla_items = DB::table('job_sla_items')->where('candidate_id',$userDT->id)->whereNotIn('service_id',[2,3,4,7,8,9,12])->get();
                                // dd($job_sla_items);
                                // $user_name = DB::table('users')->where('id',$userDT->id)->first();
                                foreach ($job_sla_items as $job_sla_item) {
                                    //Get data of user of customer with 
                                    $verify_task = Task::where(['business_id'=>$userDT->business_id,'candidate_id'=>$userDT->id,'service_id'=>$job_sla_item->service_id,'number_of_verifications'=>$job_sla_item->number_of_verifications,'is_completed'=>0,'description'=>'Task for Verification'])->first();
                                    // dd($verify_task);
                                    if($verify_task==NULL){
                                    $user_permissions = DB::table('users as u')
                                    ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                                    ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                                    ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
                                    ->where('u.business_id',$userDT->business_id)
                                    ->get();
                                    // Get JAF FILLING data from Action table for matching checking permission
                                    $action_master = DB::table('action_masters')
                                    ->select('*')
                                    ->where(['route_group'=>'','action_title'=>'JAF Filled'])
                                    ->first(); 
                                    // dd($action_master->id);
                                    // Check condition if user_permission have any data or not
                                    if(count($user_permissions)>0) {
                                        $users=[];
                                        foreach ($user_permissions as $user_permission) {  
                                        if(in_array($action_master->id,json_decode($user_permission->permission_id)))
                                        {
                                            $users[]= $user_permission;
                                        }
                                        }
                                        $final_users = [];
                                        $numbers_of_items = $job_sla_item->number_of_verifications;
                                        if($numbers_of_items > 0){
                                        for ($i=1; $i <= $numbers_of_items; $i++) { 
                                            
                                            $final_users = [];
                                            //insert in task
                                            $data = [
                                                'name'          => $userDT->first_name.' '.$userDT->last_name,
                                                'parent_id'     => $userDT->parent_id,
                                                'business_id'   => $userDT->business_id, 
                                                'description'   => 'Task for Verification',
                                                'job_id'        => NULL, 
                                                'priority'      => 'normal',
                                                'candidate_id'  => $candidate_id,   
                                                'service_id'    => $job_sla_item->service_id, 
                                                'number_of_verifications' => $i,
                                                'assigned_to'   => NULL,
                                                // 'assigned_by'   => $userDT->id,
                                                // 'assigned_at'   => date('Y-m-d H:i:s'),
                                                // 'start_date'    => date('Y-m-d'),
                                                'created_by'    => $userDT->id,
                                                'created_at'    => date('Y-m-d H:i:s'),
                                                'updated_at'    => date('Y-m-d H:i:s'),
                                                'is_completed'  => 0,
                                                // 'started_at' => date('Y-m-d H:i:s')
                                            ];
                                            // // dd($data);
                                            $task_id =  DB::table('tasks')->insertGetId($data); 

                                            $taskdata = [
                                                'parent_id'=> $userDT->parent_id,
                                                'business_id'   => $userDT->business_id,
                                                'candidate_id'  =>$candidate_id,   
                                                'job_sla_item_id'  => $job_sla_item->id,
                                                'task_id'       => $task_id,
                                                //  'user_id'       =>  $task_user->id,
                                                'service_id'    =>$job_sla_item->service_id,
                                                'number_of_verifications' => $i,
                                                'created_at'    => date('Y-m-d H:i:s'),
                                                'updated_at'  => date('Y-m-d H:i:s') 
                                            ];
                                            
                                            DB::table('task_assignments')->insertGetId($taskdata); 
                                            // DB::table('task_assignments')->insertGetId($taskdata); 
                                            
                                            //send email to customer
                                            if ($userDT->user_type == 'customer') {
                                                
                                                $admin_email = $userDT->email;
                                                $admin_name = $userDT->first_name;
                                                
                                                $email = $admin_email;
                                                $name  = $admin_name;
                                                $candidate_name = $request->input('first_name');
                                                $msg = "New JAF Verification Task Created with candidate name";
                                                $sender = DB::table('users')->where(['id'=>$userDT->business_id])->first();
                                                $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
                                                Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                                                        $message->to($email, $name)->subject
                                                        ('myBCD System - Notification for JAF Filling task');
                                                        $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                                });
                                            }
                                            else
                                            {
                                                //If login user is normal user
                                                $login_user = $userDT->business_id;
                                                $user= User::where('id',$login_user)->first();
                                                $admin_email = $user->email;
                                                $admin_name = $user->first_name;
                                                //send email to customer
                                                $email = $admin_email;
                                                $name  = $admin_name;
                                                $candidate_name = $request->input('first_name');
                                                $msg = "New JAF Verification Task Created with candidate name";
                                                $sender = DB::table('users')->where(['id'=>$userDT->business_id])->first();
                                                $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);

                                                Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                                                    $message->to($email, $name)->subject
                                                        ('myBCD System - Notification for JAF Filling Task');
                                                    $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                                });
                                            }

                                            $kams  = KeyAccountManager::where('business_id',$request->input('customer'))->get();

                                            if (count($kams)>0) {
                                                foreach ($kams as $kam) {

                                                $user= User::where('id',$kam->user_id)->first();
                                                
                                                $email = $user->email;
                                                $name  = $user->name;
                                                $candidate_name = $request->input('first_name');
                                                $msg = "New JAF Verification Task Created with candidate name";
                                                $sender = DB::table('users')->where(['id'=>$userDT->business_id])->first();
                                                $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
                                                
                                                //   EmailConfigTrait::emailConfig();
                                                //get Mail config data
                                                //   $mail =null;
                                                //   $mail= Config::get('mail');
                                                
                                                    // dd($mail['from']['address']);
                                                    //   if (count($mail)>0) {
                                                    //       Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name,$mail) {
                                                    //           $message->to($email, $name)->subject
                                                    //             ('myBCD System - Notification for JAF Filling Task');
                                                    //           $message->from($mail['from']['address'],$mail['from']['name']);
                                                    //     });
                                                    //   } else {
                                                        Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                                                                $message->to($email, $name)->subject
                                                                ('myBCD System - Notification for JAF Filling Task');
                                                                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                                        });
                                                    //   }
                                                }
                                                
                                            }
                                            // //check number of users 
                                            // //find user and assigned into task
                                            // if(count($users)>0){
                                            //   foreach ($users as $task_user) {
                                                
                                            //      $user_checks =DB::table('user_checks')->where(['user_id'=> $task_user->id, 'checks'=>$job_sla_item->service_id ])->get();
                                            //      //check loop start
                                            //      foreach($user_checks as $user) {
                                            //        if($user->checks == $job_sla_item->service_id) {
                                            //          $final_users[] = $task_user->id;
                                                    
                                                            
                                                        //enter single user into task assignment
                                                        
                                                        
                                            //         }
                                            //        // $assigned_user_id =json_encode($task_users);
                                            //       }
                                                
                                            //   }
                                            
                                            // }
                                            // $unique_users = array_unique($final_users);
                                            // DB::table('tasks')->where(['id'=>$task_id])->update(['assigned_to'=>json_encode($unique_users)]);
                                        }
                                            //  update task 
                                            
                                        }
                                    
                                    }
                                    }
                                }
                            
                            //Send data to report section
                            $report_job_sla_items = DB::table('job_sla_items')->where('candidate_id',$userDT->id)->get();
                                // dd($report_job_sla_items);
                            foreach($report_job_sla_items as $report_job_sla_item){
                                $report = DB::table('reports')->where('candidate_id',$userDT->id)->first();
                                            // dd($report);
                                            if ($report==NULL) {
                                            $report= '';
                                            }
                                
                                            //check report items created or not
                                            $report_count = DB::table('reports')->where(['candidate_id'=>$userDT->id])->count(); 
                                            // dd($report_count);
                                            if($report_count == 0){
                                            
                                            $job = DB::table('job_items')->where(['candidate_id'=>$userDT->id])->first(); 
                                            
                                            $data = 
                                                [
                                                'parent_id'     =>$userDT->parent_id,
                                                'business_id'   =>$job->business_id,
                                                'candidate_id'  =>$userDT->id,
                                                'sla_id'        =>$job->sla_id,       
                                                'created_at'    =>date('Y-m-d H:i:s')
                                                ];
                                                $report_id = DB::table('reports')->insertGetId($data);
                                                // add service items
                                                $jaf_items_datas = DB::table('jaf_form_data')->where(['candidate_id'=>$userDT->id])->get(); 
                                            
                                                foreach($jaf_items_datas as $item){
                                                $reference_type = NULL;
                                                
                                                // $l=0;
                                                
                                                // if($item->service_id==17)
                                                // {
                                                //   $input_data = $item->form_data;
                                                //   $input_data_array = json_decode($input_data,true);
                
                                                //   if($input_data_array!=NULL)
                                                //   {
                                                //     foreach($input_data_array as $key => $input)
                                                //     {
                                                //       $key_val = array_keys($input); $input_val = array_values($input);
                                                //       if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                                                //       {
                                                //          $reference_type = $input_val[0];
                                                //       }
                                                //     }
                                                //   }
                                                // }
                                                // $input_items = DB::table('service_form_inputs as sfi')
                                                //               ->select('sfi.*')            
                                                //               ->where(['sfi.service_id'=>$item->service_id,'status'=>1])
                                                //               ->get();
                                                // if($item->service_id==17)
                                                // {
                                                //   $m=0;
                                                //   foreach($input_items as $input)
                                                //   {
                                                //     if($input->service_id==17)
                                                //     {
                                                //       if(stripos($request->input('service-input-label-'.$item->id.'-'.$m),'Reference Type (Personal / Professional)')!==false)
                                                //       {
                                                //           $reference_type = $request->input('service-input-value-'.$item->id.'-'.$m);
                                                //       }
                                                //     }
                                                //     $m++;
                                                //   }
                                                // }
                                                // if($item->service_id==17)
                                                // {
                                                //   $reference_type = $item->reference_type;
                                                // }
                                                if ($item->verification_status == 'success') {
                                                    $data = 
                                                    [
                                                    'report_id'     =>$report_id,
                                                    'service_id'    =>$item->service_id,
                                                    'service_item_number'=>$item->check_item_number,
                                                    'candidate_id'  =>$userDT->id,      
                                                    'jaf_data'      =>$item->form_data,
                                                    'jaf_id'        =>$item->id,
                                                    // 'reference_type' =>  $reference_type,
                                                    'created_at'    =>date('Y-m-d H:i:s')
                                                    ];
                                                } 
                                                else {
                                                    $data = 
                                                    [
                                                    'report_id'     =>$report_id,
                                                    'service_id'    =>$item->service_id,
                                                    'service_item_number'=>$item->check_item_number,
                                                    'candidate_id'  =>$userDT->id,      
                                                    'jaf_data'      =>$item->form_data,
                                                    'jaf_id'        =>$item->id,
                                                    'is_report_output' => '0',
                                                    // 'reference_type' =>  $reference_type,
                                                    'created_at'    =>date('Y-m-d H:i:s')
                                                    ]; 
                                                }
                                                $report_item_id = DB::table('report_items')->insertGetId($data);
                                                // dd($report_item_id);
                                                // $jaf_item_attachments = DB::table('jaf_item_attachments as jf')
                                                // ->select('jf.id','jf.jaf_id','jf.file_name','jf.created_at','jf.attachment_type')                        
                                                // ->where(['jf.jaf_id'=>$item->id,'is_deleted'=>'0']) 
                                                // ->orderBy('img_order','ASC')   
                                                // ->get();  

                                                $jaf_item_attachments= DB::table('jaf_item_attachments')->where(['jaf_id'=>$item->id,'is_deleted'=>'0'])->get();
                                                
                                                if(count($jaf_item_attachments)>0){
                                                    foreach($jaf_item_attachments as $attachment){
                                                    // dd(public_path('/uploads/report-files/'));
                                                    // dd($attachment);
                                                    // $dir  = public_path('/uploads/jaf-files/');
                                                    $file_platform = 'web'; 
                                                    if(stripos($attachment->file_platform,'s3')!==false)
                                                    {
                                                        $s3_config = S3ConfigTrait::s3Config();

                                                        $file_platform = 's3';
                                                        $jaf_path = 'uploads/jaf-files/';
                                                        $report_path = 'uploads/report-files/';
                                                        if(!Storage::disk('s3')->exists($report_path))
                                                        {
                                                            Storage::disk('s3')->makeDirectory($report_path,0777, true, true);
                                                        }
            
                                                        Storage::disk('s3')->copy($jaf_path.$attachment->file_name,$report_path.$attachment->file_name);
                                                    }
                                                    else
                                                    {
                                                        File::copy(public_path('/uploads/jaf-files/'.$attachment->file_name),public_path('/uploads/report-files/'.$attachment->file_name)); 
                                                    }
                                                    
                                                    DB::table('report_item_attachments')->insert([
                                                        'report_id'        => $report_id, 
                                                        'report_item_id'   => $report_item_id,
                                                        'jaf_item_attachment_id'=>$attachment->id,
                                                        'file_name'        => $attachment->file_name,
                                                        'attachment_type'  => $attachment->attachment_type,
                                                        'file_platform'   => $file_platform,
                                                        'img_order'       =>$attachment->img_order,
                                                        'created_by'       => $userDT->id,
                                                        'created_at'       => date('Y-m-d H:i:s'),
                                                        'is_temp'          => $attachment->is_temp,
                                                    ]);
                                                    }
                                                }
                                                $report_item = DB::table('report_items')->where(['id'=>$report_item_id])->first();

                                                if($report_item->service_id==17)
                                                {
                                                    $input_data = $item->form_data;

                                                    $input_data_array = json_decode($input_data,true);
                
                                                    if($input_data_array!=NULL)
                                                    {
                                                    foreach($input_data_array as $key => $input)
                                                    {
                                                        $key_val = array_keys($input); $input_val = array_values($input);
                                                        if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                                                        {
                                                        $reference_type = $input_val[0];
                                                        }
                                                    }
                                                    }
                                                }

                                                DB::table('report_items')->where(['id'=>$report_item_id])->update([
                                                    'reference_type' => $reference_type
                                                ]);
                                                }
                                            }
                            }
                                return response()->json([
                                                'status'=>true,
                                                'message' => 'JAF has been uploaded Successfully!!'
                                            
                                                ]);
                    }
                 }
                else{
                    return response()->json([
                        'status'=>false,
                        'data' => NULL,
                        'message' => 'JAF has been already filled !!'
                    ]);
                } 
            }
            else {
                return response()->json([
                    'status'=>false,
                    'data' => NULL,
                    'message' => 'No Data Found !!'
                ]);
            }
        }
        else {
            return response()->json(['status'=>false,'message'=>'The Candidate ID is required!!']);
        }
    }

}
