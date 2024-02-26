<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mail;
use App\Helpers\Helper;
use PDF;

class NotificationController extends Controller
{
    //

    public function notifications(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $items =  DB::table('users')
                            ->where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0,'status'=>'1']);
                            if(is_numeric($request->user_id))
                            {
                                $items->where(['id'=>$request->user_id]);
                            }

        $items = $items->orderBy('id','desc')->paginate(10);

        $users = DB::table('users')->where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])
                            ->orderBy('id','desc')
                            ->get();

        if($request->ajax())
        {
            return view('admin.accounts.notification.ajax', compact('items','users'));
        }
        else
        {
            return view('admin.accounts.notification.index', compact('items','users'));
        }
    }

    public function notificationStatus(Request $request)
    {
        $id=base64_decode($request->id);
        $type = base64_decode($request->type);
        // dd($id);

        if($type=='enable')
        {
            DB::table('users')->where(['id'=>$id])->update([
                'is_export_notify' => '1',
                'export_notify_at' => date('Y-m-d H:i:s'),
                'export_notify_at' => Auth::user()->id
            ]);
        }
        else
        {
            DB::table('users')->where(['id'=>$id])->update([
                'is_export_notify' => '0',
                'export_notify_at' => date('Y-m-d H:i:s'),
                'export_notify_at' => Auth::user()->id
            ]);
        }

        //     return redirect()
        //         ->route('users.index')
        //         ->with('success', 'User deleted successfully');
        // }
        return response()->json([
            'status' => 'ok',
            'type' => $type
        ]);
    }

    public function notificationSetting(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $items =  DB::table('users as u')
                            ->select('u.*','ub.company_name')
                            ->join('user_businesses as ub','ub.business_id','=','u.business_id')
                            ->where(['is_deleted'=>0,'status'=>'1']);
                            if($request->get('user_type') !='')
                            {
                                if(stripos($request->get('user_type'),'client')!==false)
                                {
                                    $items->where(['u.parent_id'=>$business_id,'u.user_type'=>'client']);
                                }
                                elseif(stripos($request->get('user_type'),'user')!==false)
                                    $items->where(['u.business_id'=>$business_id,'u.user_type'=>'user'])->orderBy('id','desc');
                                else
                                    $items->where(['u.business_id'=>$business_id,'u.user_type'=>'user'])->orderBy('id','desc');
                            }
                            else
                            {
                                $items->where(['u.business_id'=>$business_id,'u.user_type'=>'user'])->orderBy('id','desc');
                            }

                            if(is_numeric($request->user_id))
                            {
                                $items->where(['u.id'=>$request->user_id]);
                            }

        $items = $items->paginate(10);

        $users = DB::table('users')
                            ->where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])
                            ->orderBy('id','desc')
                            ->get();

        $user_type = $request->get('user_type');

        $notification_checks = DB::table('notification_check_types')->where(['status'=>1,'is_deleted'=>0])->get();

        if($request->ajax())
        {
            return view('admin.accounts.notification.setting_ajax', compact('items','users','user_type','notification_checks'));
        }
        else
        {
            return view('admin.accounts.notification.setting_index', compact('items','users','user_type','notification_checks'));
        }
    }

    public function notificationSettingStatus(Request $request)
    {
        $id=base64_decode($request->id);
        $type = base64_decode($request->type);
        // dd($id);
        if($type=='enable')
        {
            DB::table('users')->where(['id'=>$id])->update([
                'is_notify' => '1',
                'notify_at' => date('Y-m-d H:i:s'),
                'notify_at' => Auth::user()->id
            ]);
        }
        else
        {
            DB::table('users')->where(['id'=>$id])->update([
                'is_notify' => '0',
                'notify_at' => date('Y-m-d H:i:s'),
                'notify_at' => Auth::user()->id
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'type' => $type
        ]);
    }

    public function notificationSettingEdit(Request $request)
    {
        $user_id  = base64_decode($request->id);

        if($request->isMethod('get'))
        {
            $form = '';
            $users = DB::table('users as u')
                        ->select('u.id','u.name','ub.company_name','u.user_type')
                        ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                        ->where(['u.id'=>$user_id,'is_deleted'=>0])
                        ->first();

            $notification_config = DB::table('notification_config_users')->where(['user_id'=>$users->id])->get();

            if(count($notification_config)>0)
            {
                $check_type='';

                $status = '';

                $notification_checks = DB::table('notification_check_types')->where(['status'=>1,'is_deleted'=>0])->get();

                foreach($notification_config as $item)
                {
                    $check_type='';
                    
                    if(count($notification_checks)>0)
                    {
                        foreach($notification_checks as $check)
                        {
                            if($item->check_type_id!=NULL)
                            {
                                $check_arr = [];

                                $check_id = $item->check_type_id;

                                $check_arr = json_decode($check_id,true);

                                if(count($check_arr)>0 && in_array($check->id,$check_arr))
                                {
                                    $check_type.='<div class="form-check form-check-inline error-control"> 
                                            <input class="form-check-input type_list" type="checkbox" name="check_type[]" value="'.$check->id.'" checked> 
                                            <label class="form-check-label" for="type">'.$check->title.'</label> 
                                        </div> ';   
                                }
                                else
                                {
                                    $check_type.='<div class="form-check form-check-inline error-control"> 
                                                        <input class="form-check-input type_list" type="checkbox" name="check_type[]" value="'.$check->id.'"> 
                                                        <label class="form-check-label" for="type">'.$check->title.'</label> 
                                                    </div> ';
                                }
                            }
                            else
                            {
                                $check_type.='<div class="form-check form-check-inline error-control"> 
                                            <input class="form-check-input type_list" type="checkbox" name="check_type[]" value="'.$check->id.'"> 
                                            <label class="form-check-label" for="type">'.$check->title.'</label> 
                                        </div> ';
                            }
                        }
                    }
                    

                    $status = '<div class="form-group">
                                    <label> Status <span class="text-danger">*</span></label>
                                    <select class="form-control sts_r" name="status">
                                        <option value="1" selected>Enable</option>
                                        <option value="0">Disable</option>
                                    </select>
                                    <p style="margin-bottom: 2px;" class="text-danger error_container error-status" id="error-status"></p>
                                </div>';
                    
                    if($item->status==0)
                    {
                        $status = '<div class="form-group">
                                        <label> Status <span class="text-danger">*</span></label>
                                        <select class="form-control sts_r" name="status">
                                            <option value="1">Enable</option>
                                            <option value="0" selected>Disable</option>
                                        </select>
                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-status" id="error-status"></p>
                                    </div>';
                    }

                    $form.='<div class="cust_data" row-id="1">
                    <div class="form-group">
                       <div class="row">
                          <div class="col-md-10">
                             <div class="row">
                                <div class="col-md-6">
                                   <div class="form-group">
                                      <label> Name <span class="text-danger">*</span></label>
                                      <input class="form-control" type="text" name="name[]" value="'.$item->name.'">
                                      <p style="margin-bottom: 2px;" class="text-danger error_container error-name" id="error-name"></p>
                                   </div>
                                </div>
                                <div class="col-md-6">
                                   <div class="form-group">
                                      <label> Email <span class="text-danger">*</span></label>
                                      <input class="form-control" type="text" name="email[]" value="'.$item->email.'">
                                      <p style="margin-bottom: 2px;" class="text-danger error_container error-email" id="error-email"></p>
                                   </div>
                                </div>
                             </div>
                             <div class="row type_row">
                                <div class="col-10">
                                   <div class="form-group">
                                      <label>Check Type <span class="text-danger">*</span></label><br>
                                     '.$check_type.'
                                   </div>
                                   <p style="margin-bottom: 2px;" class="text-danger error_container error-checktype" id="error-checktype"></p>
                                </div>
                             </div>
                             <div class="row">
                                <div class="col-6">
                                   '.$status.'
                                </div>
                             </div>
                          </div>
                          <div class="col-md-2 mt-3">
                             <span class="btn btn-link text-danger delete_div" data-id="'.base64_encode($item->id).'" style="font-size:20px;"><i class="far fa-times-circle"></i></span>
                          </div>
                       </div>
                    </div>
                    <p class="pb-border"></p>
                 </div>
                 ';
                }
            }

            return response()->json([
                'result' => $users,
                'form' => $form,
                'count' => count($notification_config)
            ]);
        }

        $rules = [
            'name.*' => 'required|regex:/^[a-zA-Z ]+$/u|min:1',
            'email.*' => 'required|email:rfc,dns'
        ];

        $custom = [
            'name.*.required' => 'Name Field is required',
            'name.*.regex' => 'Name Field Must Be String',
            'email.*.required' => 'Email Field is required',
            'email.*.email' => 'Email Field Must Be Valid Email Address'
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

            if(count($request->email)>0)
            {
                foreach($request->email as $key => $value)
                {
                    $rules = [
                        'status-'.$key => 'required',
                        'checktype-'.$key => 'required|array|min:1'
                    ];
            
                    $custom = [
                        'status-'.$key.'.required' => 'Status Field is Required',
                        'checktype-'.$key.'.required' => 'Select Atleast One Check Type Field !!',
                    ];
            
                    $validator = Validator::make($request->all(), $rules,$custom);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ]);
                    }


                }
            }

            // Email Unique Validation
            $notify = DB::table('notification_config_users')->get();
            if(count($notify)>0)
            {
                $notify_user = DB::table('notification_config_users')->where(['user_id'=>$user_id])->get();

                if(count($notify_user)>0)
                {
                    if(count($request->email)>0)
                    {
                        foreach($request->email as $key => $value)
                        {
                            $notify_email = DB::table('notification_config_users')
                                            ->where(['email'=>$value])
                                            ->whereNotIn('user_id',[$user_id])
                                            ->first();

                            if($notify_email!=NULL)
                            {
                                return response()->json([
                                    'success' => false,
                                    'errors' => ['email.'.$key => 'Email Has Already Been Taken !!']
                                ]);
                            }
                        }
                    }
                }
                else
                {
                    if(count($request->email)>0)
                    {
                        foreach($request->email as $key => $value)
                        {
                            $notify_email = DB::table('notification_config_users')
                                            ->where(['email'=>$value])
                                            ->first();

                            if($notify_email!=NULL)
                            {
                                return response()->json([
                                    'success' => false,
                                    'errors' => ['email.'.$key => 'Email Has Already Been Taken !!']
                                ]);
                            }
                        }
                    }
                }
            }

            // dd($request->all());

            DB::table('notification_config_users')->where(['user_id'=>$user_id])->delete();

            if(count($request->email)>0)
            {
                $user = DB::table('users')->where(['id'=>$user_id])->first();

                foreach($request->email as $key => $value)
                {
                    $status = 0;

                    $check_arr = [];
                   
                    if($request->input('status-'.$key)==1)
                    {
                        $status = 1;
                    }

                    if($request->input('checktype-'.$key)!=NULL)
                    {
                        $check_arr = json_encode($request->input('checktype-'.$key));
                    }

                    DB::table('notification_config_users')->insert([
                        'parent_id' => $user->parent_id,
                        'business_id' => $user->business_id,
                        'user_id' => $user->id,
                        'user_type' => $user->user_type,
                        'name' => $request->name[$key],
                        'email' => $value,
                        'check_type_id' => $check_arr,
                        'status' => $status,
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                }
            }

            DB::commit();
            return response()->json([
                'success' => true
            ]);
         }
         catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
         

         

    }

    public function notificationContactDelete(Request $request)
    {
        $id = base64_decode($request->id);

        DB::beginTransaction();
        try{
            if(Auth::check()){

                DB::table('notification_config_users')->where(['id'=>$id])->delete();
                //return result 
                    
                DB::commit();
                return response()->json([
                'status'=>'ok',
                'message' => 'deleted',                
                ], 200);
    
            }
            else{   
                return response()->json([
                    'status' =>'no',
                    ], 200);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    public function notificationJaf(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $items = DB::table('users as u')
                    ->select('u.id','u.business_id','u.name','u.email','u.phone','b.company_name','u.created_at')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                    ->whereNotIn('u.id',[$business_id]);

                    if(is_numeric($request->get('customer_id'))){
                        $items->where('u.business_id',$request->get('customer_id'));
                    }

                    $items=$items->paginate(10);

        $customers = DB::table('users as u')
                        ->select('u.id','u.business_id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                        ->join('user_businesses as b','b.business_id','=','u.id')
                        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                        ->whereNotIn('u.id',[$business_id])
                        ->get();

        if($request->ajax())
            return view('admin.accounts.notification.jaf.ajax',compact('items','customers'));
        else
            return view('admin.accounts.notification.jaf.index',compact('items','customers'));
    }

    public function notificationJafEdit(Request $request)
    {
        $user_id  = base64_decode($request->id);
       // dd($user_id);
        if($request->isMethod('get'))
        {
            $form = '';
            $users = DB::table('users as u')
                        ->select('u.id','u.name','ub.company_name','u.user_type')
                        ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                        ->where(['u.id'=>$user_id,'is_deleted'=>0])
                        ->first();
                
            $notification_config = DB::table('notification_control_configs')->where(['business_id'=>$users->id,'type'=>'jaf-not-filled'])->get();

            $notification_control = DB::table('notification_controls')->where(['business_id'=>$users->id,'type'=>'jaf-not-filled'])->first();
            $remindervalue = $notification_control->reminder_count;
          

            if(count($notification_config)>0)
            {
                $status = '';

                foreach($notification_config as $key => $item)
                {
                    $status = '<div class="form-group">
                                    <label> Status <span class="text-danger">*</span></label>
                                    <select class="form-control sts_r" name="status-'.$key.'">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <p style="margin-bottom: 2px;" class="text-danger error_container error-status-'.$key.'" id="error-status-'.$key.'"></p>
                                </div>';

                    if($item->status==0)
                    {
                        $status = '<div class="form-group">
                                        <label> Status <span class="text-danger">*</span></label>
                                        <select class="form-control sts_r" name="status-'.$key.'">
                                            <option value="1">Active</option>
                                            <option value="0" selected>Inactive</option>
                                        </select>
                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-status_'.$key.'" id="error-status-'.$key.'"></p>
                                    </div>';
                    }


                    $form.='<div class="cust_data" row-id="1">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Name <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" name="name[]" value="'.$item->name.'">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-name" id="error-name"></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Email <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" name="email[]" value="'.$item->email.'">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-email" id="error-email"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                '.$status.'
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mt-3">
                                            <span class="btn btn-link text-danger delete_div" data-id="'.base64_encode($item->id).'" style="font-size:20px;"><i class="far fa-times-circle"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <p class="pb-border"></p>
                            </div>
                            ';

                    
                }
            }

            return response()->json([
                'result' => $users,
                'form' => $form,
                'reminder' => $remindervalue,
                'count' => count($notification_config)
            ]);
    
        }

        $rules = [
            'name.*' => 'required|regex:/^[a-zA-Z ]+$/u|min:1',
            'email.*' => 'required|email:rfc,dns'
        ];

        $custom = [
            'name.*.required' => 'Name Field is required',
            'name.*.regex' => 'Name Field Must Be String',
            'email.*.required' => 'Email Field is required',
            'email.*.email' => 'Email Field Must Be Valid Email Address'
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

            if(count($request->email)>0)
            {
                foreach($request->email as $key => $value)
                {
                    $rules = [
                        'status-'.$key => 'required',
                    ];
            
                    $custom = [
                        'status-'.$key.'.required' => 'Status Field is Required',
                    ];
            
                    $validator = Validator::make($request->all(), $rules,$custom);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ]);
                    }


                }
            }

            DB::table('notification_control_configs')->where(['business_id'=>$user_id,'type'=>'jaf-not-filled'])->delete();

            if(count($request->email)>0)
            {
                $user = DB::table('users')->where(['id'=>$user_id])->first();
               // dd($user);
                foreach($request->email as $key => $value)
                {
                    $status = 0;
                   
                    if($request->input('status-'.$key)==1)
                    {
                        $status = 1;
                    }

                    DB::table('notification_control_configs')->insert([
                        'parent_id' => $user->parent_id,
                        'business_id' => $user->business_id,
                        'name' => $request->name[$key],
                        'email' => $value,
                        'status' => $status,
                        'type' => 'jaf-not-filled',
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }

            
                 DB::table('notification_controls')->where(['business_id'=>$user_id,'type'=>'jaf-not-filled'])->update([
                    'reminder_count' => $request->reminder_count,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]);
                
            }

            DB::commit();
            return response()->json([
                'success' => true
            ]);
         }
         catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
    }

    public function notificationJafDelete(Request $request)
    {
        $id = base64_decode($request->id);

        DB::beginTransaction();
        try{
            if(Auth::check()){

                DB::table('notification_control_configs')->where(['id'=>$id])->delete();
                //return result 
                    
                DB::commit();
                return response()->json([
                'status'=>'ok',
                'message' => 'deleted',                
                ], 200);
    
            }
            else{   
                return response()->json([
                    'status' =>'no',
                    ], 200);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    public function notificationJafStatus(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $id=base64_decode($request->id);
        $type = base64_decode($request->type);
        // dd($id);

        if($type=='active')
        {
            $insuff = DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'jaf-not-filled'])->latest()->first();
            
            if($insuff!=NULL)
            {
                DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'jaf-not-filled'])->update([
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]);
            }
            else
            {
                DB::table('notification_controls')->insert([
                    'parent_id' => $business_id,
                    'business_id' => $id,
                    'status' => 1,
                    'type' => 'jaf-not-filled',
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        else
        {
            DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'jaf-not-filled'])->update([
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);
        }

       
        return response()->json([
            'status' => 'ok',
            'type' => $type
        ]);
    }

    public function notificationJafFill(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $items = DB::table('users as u')
                    ->select('u.id','u.business_id','u.name','u.email','u.phone','b.company_name','u.created_at')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                    ->whereNotIn('u.id',[$business_id]);

                    if(is_numeric($request->get('customer_id'))){
                        $items->where('u.business_id',$request->get('customer_id'));
                    }

                    $items=$items->paginate(10);

        $customers = DB::table('users as u')
                        ->select('u.id','u.business_id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                        ->join('user_businesses as b','b.business_id','=','u.id')
                        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                        ->whereNotIn('u.id',[$business_id])
                        ->get();

        if($request->ajax())
            return view('admin.accounts.notification.jaf.jaf-filled.ajax',compact('items','customers'));
        else
            return view('admin.accounts.notification.jaf.jaf-filled.index',compact('items','customers'));
    }

    public function notificationJafFillEdit(Request $request)
    {
        $user_id  = base64_decode($request->id);

        if($request->isMethod('get'))
        {
            $form = '';
            $users = DB::table('users as u')
                        ->select('u.id','u.name','ub.company_name','u.user_type')
                        ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                        ->where(['u.id'=>$user_id,'is_deleted'=>0])
                        ->first();

            $notification_config = DB::table('notification_control_configs')->where(['business_id'=>$users->id,'type'=>'jaf-filled'])->get();

            if(count($notification_config)>0)
            {
                $status = '';

                foreach($notification_config as $key => $item)
                {
                    
                    $status = '<div class="form-group">
                                    <label> Status <span class="text-danger">*</span></label>
                                    <select class="form-control sts_r" name="status-'.$key.'">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <p style="margin-bottom: 2px;" class="text-danger error_container error-status-'.$key.'" id="error-status-'.$key.'"></p>
                                </div>';
                    
                    if($item->status==0)
                    {
                        $status = '<div class="form-group">
                                        <label> Status <span class="text-danger">*</span></label>
                                        <select class="form-control sts_r" name="status-'.$key.'">
                                            <option value="1">Active</option>
                                            <option value="0" selected>Inactive</option>
                                        </select>
                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-status_'.$key.'" id="error-status-'.$key.'"></p>
                                    </div>';
                    }

                    $form.='<div class="cust_data" row-id="1">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Name <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" name="name[]" value="'.$item->name.'">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-name" id="error-name"></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Email <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" name="email[]" value="'.$item->email.'">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-email" id="error-email"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                '.$status.'
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mt-3">
                                            <span class="btn btn-link text-danger delete_div" data-id="'.base64_encode($item->id).'" style="font-size:20px;"><i class="far fa-times-circle"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <p class="pb-border"></p>
                            </div>
                            ';
                }
            }

            return response()->json([
                'result' => $users,
                'form' => $form,
                'count' => count($notification_config)
            ]);
        }

        $rules = [
            'name.*' => 'required|regex:/^[a-zA-Z ]+$/u|min:1',
            'email.*' => 'required|email:rfc,dns'
        ];

        $custom = [
            'name.*.required' => 'Name Field is required',
            'name.*.regex' => 'Name Field Must Be String',
            'email.*.required' => 'Email Field is required',
            'email.*.email' => 'Email Field Must Be Valid Email Address'
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

            if(count($request->email)>0)
            {
                foreach($request->email as $key => $value)
                {
                    $rules = [
                        'status-'.$key => 'required',
                    ];
            
                    $custom = [
                        'status-'.$key.'.required' => 'Status Field is Required',
                    ];
            
                    $validator = Validator::make($request->all(), $rules,$custom);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ]);
                    }


                }
            }

            // dd($request->all());

            DB::table('notification_control_configs')->where(['business_id'=>$user_id,'type'=>'jaf-filled'])->delete();

            if(count($request->email)>0)
            {
                $user = DB::table('users')->where(['id'=>$user_id])->first();

                foreach($request->email as $key => $value)
                {
                    $status = 0;
                   
                    if($request->input('status-'.$key)==1)
                    {
                        $status = 1;
                    }

                    DB::table('notification_control_configs')->insert([
                        'parent_id' => $user->parent_id,
                        'business_id' => $user->business_id,
                        'name' => $request->name[$key],
                        'email' => $value,
                        'status' => $status,
                        'type' => 'jaf-filled',
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                }
            }

            DB::commit();
            return response()->json([
                'success' => true
            ]);
         }
         catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
         

         

    }

    public function notificationJafFillDelete(Request $request)
    {
        $id = base64_decode($request->id);

        DB::beginTransaction();
        try{
            if(Auth::check()){

                DB::table('notification_control_configs')->where(['id'=>$id])->delete();
                //return result 
                    
                DB::commit();
                return response()->json([
                'status'=>'ok',
                'message' => 'deleted',                
                ], 200);
    
            }
            else{   
                return response()->json([
                    'status' =>'no',
                    ], 200);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    public function notificationJafFillStatus(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $id=base64_decode($request->id);
        $type = base64_decode($request->type);
        // dd($id);

        if($type=='active')
        {
            $insuff = DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'jaf-filled'])->latest()->first();
            
            if($insuff!=NULL)
            {
                DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'jaf-filled'])->update([
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]);
            }
            else
            {
                DB::table('notification_controls')->insert([
                    'parent_id' => $business_id,
                    'business_id' => $id,
                    'status' => 1,
                    'type' => 'jaf-filled',
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        else
        {
            DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'jaf-filled'])->update([
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);
        }

       
        return response()->json([
            'status' => 'ok',
            'type' => $type
        ]);
    }

    public function notificationInsuff(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $items = DB::table('users as u')
                    ->select('u.id','u.business_id','u.name','u.email','u.phone','b.company_name','u.created_at')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                    ->whereNotIn('u.id',[$business_id]);

                    if(is_numeric($request->get('customer_id'))){
                        $items->where('u.business_id',$request->get('customer_id'));
                    }

                    $items=$items->paginate(10);

        $customers = DB::table('users as u')
                        ->select('u.id','u.business_id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                        ->join('user_businesses as b','b.business_id','=','u.id')
                        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                        ->whereNotIn('u.id',[$business_id])
                        ->get();

        if($request->ajax())
            return view('admin.accounts.notification.insufficiency.ajax',compact('items','customers'));
        else
            return view('admin.accounts.notification.insufficiency.index',compact('items','customers'));
    }

    public function notificationInsuffEdit(Request $request)
    {
        $user_id  = base64_decode($request->id);

        if($request->isMethod('get'))
        {
            $form = '';
            $users = DB::table('users as u')
                        ->select('u.id','u.name','ub.company_name','u.user_type')
                        ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                        ->where(['u.id'=>$user_id,'is_deleted'=>0])
                        ->first();

            $notification_config = DB::table('notification_control_configs')->where(['business_id'=>$users->id,'type'=>'case-insuff'])->get();

            if(count($notification_config)>0)
            {
                $status = '';

                foreach($notification_config as $key => $item)
                {
                    
                    $status = '<div class="form-group">
                                    <label> Status <span class="text-danger">*</span></label>
                                    <select class="form-control sts_r" name="status-'.$key.'">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <p style="margin-bottom: 2px;" class="text-danger error_container error-status-'.$key.'" id="error-status-'.$key.'"></p>
                                </div>';
                    
                    if($item->status==0)
                    {
                        $status = '<div class="form-group">
                                        <label> Status <span class="text-danger">*</span></label>
                                        <select class="form-control sts_r" name="status-'.$key.'">
                                            <option value="1">Active</option>
                                            <option value="0" selected>Inactive</option>
                                        </select>
                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-status_'.$key.'" id="error-status-'.$key.'"></p>
                                    </div>';
                    }

                    $form.='<div class="cust_data" row-id="1">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Name <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" name="name[]" value="'.$item->name.'">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-name" id="error-name"></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Email <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" name="email[]" value="'.$item->email.'">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-email" id="error-email"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                '.$status.'
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mt-3">
                                            <span class="btn btn-link text-danger delete_div" data-id="'.base64_encode($item->id).'" style="font-size:20px;"><i class="far fa-times-circle"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <p class="pb-border"></p>
                            </div>
                            ';
                }
            }

            return response()->json([
                'result' => $users,
                'form' => $form,
                'count' => count($notification_config)
            ]);
        }

        $rules = [
            'name.*' => 'required|regex:/^[a-zA-Z ]+$/u|min:1',
            'email.*' => 'required|email:rfc,dns'
        ];

        $custom = [
            'name.*.required' => 'Name Field is required',
            'name.*.regex' => 'Name Field Must Be String',
            'email.*.required' => 'Email Field is required',
            'email.*.email' => 'Email Field Must Be Valid Email Address'
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

            if(count($request->email)>0)
            {
                foreach($request->email as $key => $value)
                {
                    $rules = [
                        'status-'.$key => 'required',
                    ];
            
                    $custom = [
                        'status-'.$key.'.required' => 'Status Field is Required',
                    ];
            
                    $validator = Validator::make($request->all(), $rules,$custom);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ]);
                    }


                }
            }

            DB::table('notification_control_configs')->where(['business_id'=>$user_id,'type'=>'case-insuff'])->delete();

            if(count($request->email)>0)
            {
                $user = DB::table('users')->where(['id'=>$user_id])->first();

                foreach($request->email as $key => $value)
                {
                    $status = 0;
                   
                    if($request->input('status-'.$key)==1)
                    {
                        $status = 1;
                    }

                    DB::table('notification_control_configs')->insert([
                        'parent_id' => $user->parent_id,
                        'business_id' => $user->business_id,
                        'name' => $request->name[$key],
                        'email' => $value,
                        'status' => $status,
                        'type' => 'case-insuff',
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true
            ]);
         }
         catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }



    }

    public function notificationInsuffDelete(Request $request)
    {
        $id = base64_decode($request->id);

        DB::beginTransaction();
        try{
            if(Auth::check()){

                DB::table('notification_control_configs')->where(['id'=>$id])->delete();
                //return result 
                    
                DB::commit();
                return response()->json([
                'status'=>'ok',
                'message' => 'deleted',                
                ], 200);
    
            }
            else{   
                return response()->json([
                    'status' =>'no',
                    ], 200);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    public function notificationInsuffStatus(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $id=base64_decode($request->id);
        $type = base64_decode($request->type);
        // dd($id);

        if($type=='active')
        {
            $insuff = DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'case-insuff'])->latest()->first();
            
            if($insuff!=NULL)
            {
                DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'case-insuff'])->update([
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]);
            }
            else
            {
                DB::table('notification_controls')->insert([
                    'parent_id' => $business_id,
                    'business_id' => $id,
                    'status' => 1,
                    'type' => 'case-insuff-schedule',
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        else
        {
            DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'case-insuff'])->update([
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);
        }

       
        return response()->json([
            'status' => 'ok',
            'type' => $type
        ]);
    }

    public function notificationInsuffCase(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $items = DB::table('users as u')
                    ->select('u.id','u.business_id','u.name','u.email','u.phone','b.company_name','u.created_at')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                    ->whereNotIn('u.id',[$business_id]);

                    if(is_numeric($request->get('customer_id'))){
                        $items->where('u.business_id',$request->get('customer_id'));
                    }

                    $items=$items->paginate(10);

        $customers = DB::table('users as u')
                        ->select('u.id','u.business_id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                        ->join('user_businesses as b','b.business_id','=','u.id')
                        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                        ->whereNotIn('u.id',[$business_id])
                        ->get();

        if($request->ajax())
            return view('admin.accounts.notification.insufficiency.case.ajax',compact('items','customers'));
        else
            return view('admin.accounts.notification.insufficiency.case.index',compact('items','customers'));
    }

    public function notificationInsuffCaseEdit(Request $request)
    {
        $user_id  = base64_decode($request->id);

        if($request->isMethod('get'))
        {
            $form = '';
            $users = DB::table('users as u')
                        ->select('u.id','u.name','ub.company_name','u.user_type')
                        ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                        ->where(['u.id'=>$user_id,'is_deleted'=>0])
                        ->first();

            $notification_config = DB::table('notification_control_configs')->where(['business_id'=>$users->id,'type'=>'case-insuff-raise'])->get();

            if(count($notification_config)>0)
            {
                $status = '';

                foreach($notification_config as $key => $item)
                {
                    
                    $status = '<div class="form-group">
                                    <label> Status <span class="text-danger">*</span></label>
                                    <select class="form-control sts_r" name="status-'.$key.'">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <p style="margin-bottom: 2px;" class="text-danger error_container error-status-'.$key.'" id="error-status-'.$key.'"></p>
                                </div>';
                    
                    if($item->status==0)
                    {
                        $status = '<div class="form-group">
                                        <label> Status <span class="text-danger">*</span></label>
                                        <select class="form-control sts_r" name="status-'.$key.'">
                                            <option value="1">Active</option>
                                            <option value="0" selected>Inactive</option>
                                        </select>
                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-status_'.$key.'" id="error-status-'.$key.'"></p>
                                    </div>';
                    }

                    $form.='<div class="cust_data" row-id="1">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Name <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" name="name[]" value="'.$item->name.'">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-name" id="error-name"></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Email <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" name="email[]" value="'.$item->email.'">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-email" id="error-email"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                '.$status.'
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mt-3">
                                            <span class="btn btn-link text-danger delete_div" data-id="'.base64_encode($item->id).'" style="font-size:20px;"><i class="far fa-times-circle"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <p class="pb-border"></p>
                            </div>
                            ';
                }
            }

            return response()->json([
                'result' => $users,
                'form' => $form,
                'count' => count($notification_config)
            ]);
        }

        $rules = [
            'name.*' => 'required|regex:/^[a-zA-Z ]+$/u|min:1',
            'email.*' => 'required|email:rfc,dns'
        ];

        $custom = [
            'name.*.required' => 'Name Field is required',
            'name.*.regex' => 'Name Field Must Be String',
            'email.*.required' => 'Email Field is required',
            'email.*.email' => 'Email Field Must Be Valid Email Address'
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

            if(count($request->email)>0)
            {
                foreach($request->email as $key => $value)
                {
                    $rules = [
                        'status-'.$key => 'required',
                    ];
            
                    $custom = [
                        'status-'.$key.'.required' => 'Status Field is Required',
                    ];
            
                    $validator = Validator::make($request->all(), $rules,$custom);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ]);
                    }


                }
            }

            // dd($request->all());

            DB::table('notification_control_configs')->where(['business_id'=>$user_id,'type'=>'case-insuff-raise'])->delete();

            if(count($request->email)>0)
            {
                $user = DB::table('users')->where(['id'=>$user_id])->first();

                foreach($request->email as $key => $value)
                {
                    $status = 0;
                   
                    if($request->input('status-'.$key)==1)
                    {
                        $status = 1;
                    }

                    DB::table('notification_control_configs')->insert([
                        'parent_id' => $user->parent_id,
                        'business_id' => $user->business_id,
                        'name' => $request->name[$key],
                        'email' => $value,
                        'status' => $status,
                        'type' => 'case-insuff-raise',
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                }
            }

            DB::commit();
            return response()->json([
                'success' => true
            ]);
         }
         catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
         

         

    }

    public function notificationInsuffCaseDelete(Request $request)
    {
        $id = base64_decode($request->id);

        DB::beginTransaction();
        try{
            if(Auth::check()){

                DB::table('notification_control_configs')->where(['id'=>$id])->delete();
                //return result 
                    
                DB::commit();
                return response()->json([
                'status'=>'ok',
                'message' => 'deleted',                
                ], 200);
    
            }
            else{   
                return response()->json([
                    'status' =>'no',
                    ], 200);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    public function notificationInsuffCaseStatus(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $id=base64_decode($request->id);
        $type = base64_decode($request->type);
        // dd($id);

        if($type=='active')
        {
            $insuff = DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'case-insuff-raise'])->latest()->first();
            
            if($insuff!=NULL)
            {
                DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'case-insuff-raise'])->update([
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]);
            }
            else
            {
                DB::table('notification_controls')->insert([
                    'parent_id' => $business_id,
                    'business_id' => $id,
                    'status' => 1,
                    'type' => 'case-insuff-raise',
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        else
        {
            DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'case-insuff-raise'])->update([
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);
        }

       
        return response()->json([
            'status' => 'ok',
            'type' => $type
        ]);
    }

    public function notificationJafCandidate(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $items = DB::table('users as u')
                    ->select('u.id','u.business_id','u.name','u.email','u.phone','b.company_name','u.created_at')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['u.user_type'=>'client','u.parent_id'=>$business_id,'u.is_deleted'=>0])
                    ->whereNotIn('u.id',[$business_id]);

                    if(is_numeric($request->get('customer_id'))){
                        $items->where('u.business_id',$request->get('customer_id'));
                    }

                    $items=$items->paginate(10);

        $customers = DB::table('users as u')
                        ->select('u.id','u.business_id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                        ->join('user_businesses as b','b.business_id','=','u.id')
                        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id,'u.is_deleted'=>0])
                        ->whereNotIn('u.id',[$business_id])
                        ->get();

        if($request->ajax())
            return view('admin.accounts.notification.jaf.jaf-to-candidate.ajax',compact('items','customers'));
        else
            return view('admin.accounts.notification.jaf.jaf-to-candidate.index',compact('items','customers'));
    }

    public function notificationJafCandidateEdit(Request $request)
    {
        $user_id = base64_decode($request->id);

        if($request->isMethod('get'))
        {
            $form = '';
            $users = DB::table('users as u')
                        ->select('u.id','u.name','ub.company_name','u.user_type')
                        ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                        ->where(['u.id'=>$user_id,'is_deleted'=>0])
                        ->first();

            $notification_config = DB::table('notification_control_configs')->where(['business_id'=>$users->id,'type'=>'jaf-sent-to-candidate'])->get();

            if(count($notification_config)>0)
            {
                $status = '';

                foreach($notification_config as $key => $item)
                {
                    $status = '<div class="form-group">
                                    <label> Status <span class="text-danger">*</span></label>
                                    <select class="form-control sts_r" name="status-'.$key.'">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <p style="margin-bottom: 2px;" class="text-danger error_container error-status-'.$key.'" id="error-status-'.$key.'"></p>
                               </div>';

                    if($item->status==0)
                    {
                        $status = '<div class="form-group">
                                    <label> Status <span class="text-danger">*</span></label>
                                    <select class="form-control sts_r" name="status-'.$key.'">
                                        <option value="1" >Active</option>
                                        <option value="0" selected>Inactive</option>
                                    </select>
                                    <p style="margin-bottom: 2px;" class="text-danger error_container error-status-'.$key.'" id="error-status-'.$key.'"></p>
                               </div>';
                    }

                    $form.='<div class="cust_data" row-id="1">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Name <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" name="name[]" value="'.$item->name.'">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-name" id="error-name"></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Email <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" name="email[]" value="'.$item->email.'">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-email" id="error-email"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                '.$status.'
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mt-3">
                                            <span class="btn btn-link text-danger delete_div" data-id="'.base64_encode($item->id).'" style="font-size:20px;"><i class="far fa-times-circle"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <p class="pb-border"></p>
                            </div>
                            ';
                }
            }

            return response()->json([
                'result' => $users,
                'form' => $form,
                'count' => count($notification_config)
            ]);
        }


        $rules = [
            'name.*' => 'required|regex:/^[a-zA-Z ]+$/u|min:1',
            'email.*' => 'required|email:rfc,dns'
        ];

        $custom = [
            'name.*.required' => 'Name Field is required',
            'name.*.regex' => 'Name Field Must Be String',
            'email.*.required' => 'Email Field is required',
            'email.*.email' => 'Email Field Must Be Valid Email Address'
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

            if(count($request->email)>0)
            {
                foreach($request->email as $key => $value)
                {
                    $rules = [
                        'status-'.$key => 'required',
                    ];
            
                    $custom = [
                        'status-'.$key.'.required' => 'Status Field is Required',
                    ];
            
                    $validator = Validator::make($request->all(), $rules,$custom);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ]);
                    }


                }
            }

            DB::table('notification_control_configs')->where(['business_id'=>$user_id,'type'=>'jaf-sent-to-candidate'])->delete();

            if(count($request->email)>0)
            {
                $user = DB::table('users')->where(['id'=>$user_id])->first();

                foreach($request->email as $key => $value)
                {
                    $status = 0;
                   
                    if($request->input('status-'.$key)==1)
                    {
                        $status = 1;
                    }

                    DB::table('notification_control_configs')->insert([
                        'parent_id' => $user->parent_id,
                        'business_id' => $user->business_id,
                        'name' => $request->name[$key],
                        'email' => $value,
                        'status' => $status,
                        'type' => 'jaf-sent-to-candidate',
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                }
            }

            DB::commit();
            return response()->json([
                'success' => true
            ]);

         }
         catch (\Exception $e) {
            DB::rollback(); 
            // something went wrong
            return $e;
        } 



    }

    public function notificationJafCandidateDelete(Request $request)
    {
        $id = base64_decode($request->id);

        DB::beginTransaction();
        try{
            if(Auth::check()){
                DB::table('notification_control_configs')->where(['id'=>$id])->delete();
                //return result 
                    
                DB::commit();
                return response()->json([
                'status'=>'ok',
                'message' => 'deleted',                
                ], 200);
            }
            else
            {
                return response()->json([
                    'status' =>'no',
                    ], 200);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    public function notificationJafCandidateStatus(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $id=base64_decode($request->id);
        $type = base64_decode($request->type);

        if($type=='active')
        {
            $jaf = DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'jaf-sent-to-candidate'])->latest()->first();

            if($jaf!=NULL)
            {
                DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'jaf-sent-to-candidate'])->update([
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]);
            }
            else
            {
                DB::table('notification_controls')->insert([
                    'parent_id' => $business_id,
                    'business_id' => $id,
                    'status' => 1,
                    'type' => 'jaf-sent-to-candidate',
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        else
        {
            DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'jaf-sent-to-candidate'])->update([
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'type' => $type
        ]);
    }

    public function notificationReport(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $items = DB::table('users as u')
                    ->select('u.id','u.business_id','u.name','u.email','u.phone','b.company_name','u.created_at','n.days')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->join('notification_controls as n','n.business_id','=','u.id')
                    ->where(['u.user_type'=>'client','u.parent_id'=>$business_id,'n.type'=>'report-share-complete'])
                    ->whereNotIn('u.id',[$business_id]);

                    if(is_numeric($request->get('customer_id'))){
                        $items->where('u.business_id',$request->get('customer_id'));
                    }

                    $items=$items->paginate(10);

        $notify= DB::table('notification_controls')->where('type','report-share-complete')->get();

        $customers = DB::table('users as u')
                ->select('u.id','u.business_id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                ->join('user_businesses as b','b.business_id','=','u.id')
                ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                ->whereNotIn('u.id',[$business_id])
                ->get();

        $notify_customers = DB::table('users as u')
                ->select('u.id','u.business_id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                ->join('user_businesses as b','b.business_id','=','u.id')
                ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                ->whereNotIn('u.id',[$business_id]);
                if(count($notify)>0)
                {
                    $notify_customers->whereNotIn('u.id',$notify->pluck('business_id')->all());
                }
                $notify_customers=$notify_customers->get();

        if($request->ajax())
            return view('admin.accounts.notification.report.ajax',compact('items','customers','notify_customers'));
        else
            return view('admin.accounts.notification.report.index',compact('items','customers','notify_customers'));

    }

    public function notificationReportStore(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $rules = [
            'customer'    => 'required',
            'no_of_days'  =>  'required|integer|min:1|max:30',
            'name.*' => 'required|regex:/^[a-zA-Z ]+$/u|min:1',
            'email.*' => 'required|email:rfc,dns'
        ];

        $custom = [
            'name.*.required' => 'Name Field is required',
            'name.*.regex' => 'Name Field Must Be String',
            'email.*.required' => 'Email Field is required',
            'email.*.email' => 'Email Field Must Be Valid Email Address'
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

            $notification_control_exists = DB::table('notification_controls')->where(['business_id'=>$request->customer,'type'=>'report-share-complete'])->count();

            if($notification_control_exists > 0)
            {
                return response()->json([
                    'success' => false,
                    'errors' => ['all'=>'Customer Name Already Exists !!']
                ]);
            }

            if(count($request->email)>0)
            {
                foreach($request->email as $key => $value)
                {
                    $rules = [
                        'status-'.$key => 'required',
                    ];
            
                    $custom = [
                        'status-'.$key.'.required' => 'Status Field is Required',
                    ];
            
                    $validator = Validator::make($request->all(), $rules,$custom);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ]);
                    }

                }
            }

            DB::table('notification_controls')->insert([
                'parent_id' => $business_id,
                'business_id' => $request->customer,
                'status' => 1,
                'days' => $request->no_of_days,
                'type' => 'report-share-complete',
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            if(count($request->email)>0)
            {
                $user = DB::table('users')->where(['id'=>$request->customer])->first();

                foreach($request->email as $key => $value)
                {
                    $status = 0;
                
                    if($request->input('status-'.$key)==1)
                    {
                        $status = 1;
                    }

                    DB::table('notification_control_configs')->insert([
                        'parent_id' => $user->parent_id,
                        'business_id' => $user->business_id,
                        'name' => $request->name[$key],
                        'email' => $value,
                        'status' => $status,
                        'type' => 'report-share-complete',
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                }
            }

            DB::commit();
            return response()->json([
                'success' => true
            ]);

         }
         catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
         } 
    }

    public function notificationReportEdit(Request $request)
    {
        $user_id  = base64_decode($request->id);

        if($request->isMethod('get'))
        {
            $form = '';
            $users = DB::table('users as u')
                        ->select('u.id','u.name','ub.company_name','u.user_type','n.days')
                        ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                        ->join('notification_controls as n','n.business_id','=','u.id')
                        ->where(['u.id'=>$user_id,'is_deleted'=>0,'n.type'=>'report-share-complete'])
                        ->first();

            $notification_config = DB::table('notification_control_configs')->where(['business_id'=>$users->id,'type'=>'report-share-complete'])->get();

            if(count($notification_config)>0)
            {
                $status = '';

                foreach($notification_config as $key => $item)
                {
                    
                    $status = '<div class="form-group">
                                    <label> Status <span class="text-danger">*</span></label>
                                    <select class="form-control sts_r" name="status-'.$key.'">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <p style="margin-bottom: 2px;" class="text-danger error_container error-status-'.$key.'" id="error-status-'.$key.'"></p>
                                </div>';
                    
                    if($item->status==0)
                    {
                        $status = '<div class="form-group">
                                        <label> Status <span class="text-danger">*</span></label>
                                        <select class="form-control sts_r" name="status-'.$key.'">
                                            <option value="1">Active</option>
                                            <option value="0" selected>Inactive</option>
                                        </select>
                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-status_'.$key.'" id="error-status-'.$key.'"></p>
                                    </div>';
                    }

                    $form.='<div class="cust_data" row-id="1">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Name <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" name="name[]" value="'.$item->name.'">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-name" id="error-name"></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Email <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" name="email[]" value="'.$item->email.'">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-email" id="error-email"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                '.$status.'
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mt-3">
                                            <span class="btn btn-link text-danger delete_div" data-id="'.base64_encode($item->id).'" style="font-size:20px;"><i class="far fa-times-circle"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <p class="pb-border"></p>
                            </div>
                            ';
                }
            }

            return response()->json([
                'result' => $users,
                'form' => $form,
                'count' => count($notification_config)
            ]);
        }

        $rules = [
            'no_of_days'  =>  'required|integer|min:1|max:30',
            'name.*' => 'required|regex:/^[a-zA-Z ]+$/u|min:1',
            'email.*' => 'required|email:rfc,dns'
        ];

        $custom = [
            'name.*.required' => 'Name Field is required',
            'name.*.regex' => 'Name Field Must Be String',
            'email.*.required' => 'Email Field is required',
            'email.*.email' => 'Email Field Must Be Valid Email Address'
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

            if(count($request->email)>0)
            {
                foreach($request->email as $key => $value)
                {
                    $rules = [
                        'status-'.$key => 'required',
                    ];
            
                    $custom = [
                        'status-'.$key.'.required' => 'Status Field is Required',
                    ];
            
                    $validator = Validator::make($request->all(), $rules,$custom);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ]);
                    }


                }
            }

            DB::table('notification_controls')->where(['business_id'=>$user_id,'type'=>'report-share-complete'])->update([
                'days' => $request->no_of_days
            ]);

            // dd($request->all());

            DB::table('notification_control_configs')->where(['business_id'=>$user_id,'type'=>'report-share-complete'])->delete();

            if(count($request->email)>0)
            {
                $user = DB::table('users')->where(['id'=>$user_id])->first();

                foreach($request->email as $key => $value)
                {
                    $status = 0;
                   
                    if($request->input('status-'.$key)==1)
                    {
                        $status = 1;
                    }

                    DB::table('notification_control_configs')->insert([
                        'parent_id' => $user->parent_id,
                        'business_id' => $user->business_id,
                        'name' => $request->name[$key],
                        'email' => $value,
                        'status' => $status,
                        'type' => 'report-share-complete',
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                }
            }

            DB::commit();
            return response()->json([
                'success' => true
            ]);
         }
         catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
         

         

    }

    public function notificationReportDelete(Request $request)
    {
        $id = base64_decode($request->id);

        DB::beginTransaction();
        try{
            if(Auth::check()){

                DB::table('notification_control_configs')->where(['id'=>$id])->delete();
                //return result 
                    
                DB::commit();
                return response()->json([
                'status'=>'ok',
                'message' => 'deleted',                
                ], 200);
    
            }
            else{   
                return response()->json([
                    'status' =>'no',
                    ], 200);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    public function notificationReportStatus(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $id=base64_decode($request->id);
        $type = base64_decode($request->type);
        // dd($id);

        if($type=='active')
        {
            $insuff = DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'report-share-complete'])->latest()->first();
            
            if($insuff!=NULL)
            {
                DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'report-share-complete'])->update([
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]);
            }
            else
            {
                DB::table('notification_controls')->insert([
                    'parent_id' => $business_id,
                    'business_id' => $id,
                    'status' => 1,
                    'type' => 'report-share-complete',
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        else
        {
            DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'report-share-complete'])->update([
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);
        }

       
        return response()->json([
            'status' => 'ok',
            'type' => $type
        ]);
    }

    public function notificationMarkReport(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $items = DB::table('users as u')
                    ->select('u.id','u.business_id','u.name','u.email','u.phone','b.company_name','u.created_at')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                    ->whereNotIn('u.id',[$business_id]);

                    if(is_numeric($request->get('customer_id'))){
                        $items->where('u.business_id',$request->get('customer_id'));
                    }

                    $items=$items->paginate(10);

        $customers = DB::table('users as u')
                        ->select('u.id','u.business_id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                        ->join('user_businesses as b','b.business_id','=','u.id')
                        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                        ->whereNotIn('u.id',[$business_id])
                        ->get();

        if($request->ajax())
            return view('admin.accounts.notification.report.mark-complete.ajax',compact('items','customers'));
        else
            return view('admin.accounts.notification.report.mark-complete.index',compact('items','customers'));

    }

    public function notificationMarkReportEdit(Request $request)
    {
        $user_id  = base64_decode($request->id);

        if($request->isMethod('get'))
        {
            $form = '';
            $users = DB::table('users as u')
                        ->select('u.id','u.name','ub.company_name','u.user_type')
                        ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                        ->where(['u.id'=>$user_id,'is_deleted'=>0])
                        ->first();

            $notification_config = DB::table('notification_control_configs')->where(['business_id'=>$users->id,'type'=>'report-complete'])->get();

            if(count($notification_config)>0)
            {
                $status = '';

                foreach($notification_config as $key => $item)
                {
                    
                    $status = '<div class="form-group">
                                    <label> Status <span class="text-danger">*</span></label>
                                    <select class="form-control sts_r" name="status-'.$key.'">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <p style="margin-bottom: 2px;" class="text-danger error_container error-status-'.$key.'" id="error-status-'.$key.'"></p>
                                </div>';
                    
                    if($item->status==0)
                    {
                        $status = '<div class="form-group">
                                        <label> Status <span class="text-danger">*</span></label>
                                        <select class="form-control sts_r" name="status-'.$key.'">
                                            <option value="1">Active</option>
                                            <option value="0" selected>Inactive</option>
                                        </select>
                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-status_'.$key.'" id="error-status-'.$key.'"></p>
                                    </div>';
                    }

                    $form.='<div class="cust_data" row-id="1">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Name <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" name="name[]" value="'.$item->name.'">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-name" id="error-name"></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Email <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" name="email[]" value="'.$item->email.'">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-email" id="error-email"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                '.$status.'
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mt-3">
                                            <span class="btn btn-link text-danger delete_div" data-id="'.base64_encode($item->id).'" style="font-size:20px;"><i class="far fa-times-circle"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <p class="pb-border"></p>
                            </div>
                            ';
                }
            }

            return response()->json([
                'result' => $users,
                'form' => $form,
                'count' => count($notification_config)
            ]);
        }

        $rules = [
            'name.*' => 'required|regex:/^[a-zA-Z ]+$/u|min:1',
            'email.*' => 'required|email:rfc,dns'
        ];

        $custom = [
            'name.*.required' => 'Name Field is required',
            'name.*.regex' => 'Name Field Must Be String',
            'email.*.required' => 'Email Field is required',
            'email.*.email' => 'Email Field Must Be Valid Email Address'
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

            if(count($request->email)>0)
            {
                foreach($request->email as $key => $value)
                {
                    $rules = [
                        'status-'.$key => 'required',
                    ];
            
                    $custom = [
                        'status-'.$key.'.required' => 'Status Field is Required',
                    ];
            
                    $validator = Validator::make($request->all(), $rules,$custom);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ]);
                    }


                }
            }

            // dd($request->all());

            DB::table('notification_control_configs')->where(['business_id'=>$user_id,'type'=>'report-complete'])->delete();

            if(count($request->email)>0)
            {
                $user = DB::table('users')->where(['id'=>$user_id])->first();

                foreach($request->email as $key => $value)
                {
                    $status = 0;
                   
                    if($request->input('status-'.$key)==1)
                    {
                        $status = 1;
                    }

                    DB::table('notification_control_configs')->insert([
                        'parent_id' => $user->parent_id,
                        'business_id' => $user->business_id,
                        'name' => $request->name[$key],
                        'email' => $value,
                        'status' => $status,
                        'type' => 'report-complete',
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                }
            }

            DB::commit();
            return response()->json([
                'success' => true
            ]);
         }
         catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
         

         

    }

    public function notificationMarkReportDelete(Request $request)
    {
        $id = base64_decode($request->id);

        DB::beginTransaction();
        try{
            if(Auth::check()){

                DB::table('notification_control_configs')->where(['id'=>$id])->delete();
                //return result 
                    
                DB::commit();
                return response()->json([
                'status'=>'ok',
                'message' => 'deleted',                
                ], 200);
    
            }
            else{   
                return response()->json([
                    'status' =>'no',
                    ], 200);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    public function notificationMarkReportStatus(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $id=base64_decode($request->id);
        $type = base64_decode($request->type);
        // dd($id);

        if($type=='active')
        {
            $insuff = DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'report-complete'])->latest()->first();
            
            if($insuff!=NULL)
            {
                DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'report-complete'])->update([
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]);
            }
            else
            {
                DB::table('notification_controls')->insert([
                    'parent_id' => $business_id,
                    'business_id' => $id,
                    'status' => 1,
                    'type' => 'report-complete',
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        else
        {
            DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'report-complete'])->update([
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);
        }

       
        return response()->json([
            'status' => 'ok',
            'type' => $type
        ]);
    }


    public function notificationsVendor(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $items = DB::table('users as u')
                    ->select('u.id','u.business_id', 'u.name')
                    ->join('vendors as v','v.user_id','=','u.id')
                    ->where(['u.user_type'=>'vendor','u.parent_id'=>$business_id]);

        // $items = DB::table('users as u')
        //             ->select('u.id','u.business_id','u.name','u.email','u.phone','b.company_name','u.created_at')
        //             ->join('user_businesses as b','b.business_id','=','u.id')
        //             ->where(['u.user_type'=>'vendor','u.parent_id'=>$business_id])
        //             ->whereNotIn('u.id',[$business_id]);   

                    if(is_numeric($request->get('customer_id'))){
                        $items->where('u.business_id',$request->get('customer_id'));
                    }
                    $items=$items->paginate(10);

        $customers = DB::table('users as u')
                        ->select('u.id','u.business_id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                        ->join('user_businesses as b','b.business_id','=','u.id')
                        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                        ->whereNotIn('u.id',[$business_id])
                        ->get();

        if($request->ajax()){
            return view('admin.accounts.notification.vendor.ajax',compact('items','customers'));
        }
        else{
            return view('admin.accounts.notification.vendor.index',compact('items','customers'));
        }
    }

    public function notificationVendorStatus(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $id=base64_decode($request->id);
        $type = base64_decode($request->type);
        //dd($id);

        if($type=='active')
        {
            $insuff = DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'vendor-revert-type-mail'])->latest()->first();
            
            if($insuff!=NULL)
            {
                DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'vendor-revert-type-mail'])->update([
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ]);
            }
            else
            {
                DB::table('notification_controls')->insert([
                    'parent_id' => $business_id,
                    'business_id' => $id,
                    'status' => 1,
                    'type' => 'vendor-revert-type-mail',
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        else
        {
            DB::table('notification_controls')->where(['business_id'=>$id,'type'=>'vendor-revert-type-mail'])->update([
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);
        }

       
        return response()->json([
            'status' => 'ok',
            'type' => $type
        ]);
    }

    public function notificationVendorEdit(Request $request)
    {
        //dd($request->all());
        $user_id  = base64_decode($request->id);
        //dd($user_id);
        if($request->isMethod('get'))
        {
            $form = '';
            $users = DB::table('users as u')
                        ->select('u.id','u.business_id', 'u.name','u.user_type')
                        ->join('vendors as v','v.user_id','=','u.id')
                        ->where(['u.id'=>$user_id,'is_deleted'=>0])
                        ->first();

                        // ->select('u.id','u.name','ub.company_name','u.user_type')
                        // ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                        // ->where(['u.id'=>$user_id,'is_deleted'=>0])
                        // ->first();
     
            $notification_config = DB::table('notification_control_configs')->where(['business_id'=>$users->id,'type'=>'vendor-revert-type-mail'])->get();

            if(count($notification_config)>0)
            {
                $status = '';

                foreach($notification_config as $key => $item)
                {
                    $status = '<div class="form-group">
                                    <label> Status <span class="text-danger">*</span></label>
                                    <select class="form-control sts_r" name="status-'.$key.'">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <p style="margin-bottom: 2px;" class="text-danger error_container error-status-'.$key.'" id="error-status-'.$key.'"></p>
                                </div>';

                    if($item->status==0)
                    {
                        $status = '<div class="form-group">
                                        <label> Status <span class="text-danger">*</span></label>
                                        <select class="form-control sts_r" name="status-'.$key.'">
                                            <option value="1">Active</option>
                                            <option value="0" selected>Inactive</option>
                                        </select>
                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-status_'.$key.'" id="error-status-'.$key.'"></p>
                                    </div>';
                    }


                    $form.='<div class="cust_data" row-id="1">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Name <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" name="name[]" value="'.$item->name.'">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-name" id="error-name"></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Email <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" name="email[]" value="'.$item->email.'">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container error-email" id="error-email"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                '.$status.'
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mt-3">
                                            <span class="btn btn-link text-danger delete_div" data-id="'.base64_encode($item->id).'" style="font-size:20px;"><i class="far fa-times-circle"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <p class="pb-border"></p>
                            </div>
                            ';

                    
                }
            }

            return response()->json([
                'result' => $users,
                'form' => $form,
                'count' => count($notification_config)
            ]);
    
        }

        $rules = [
            'name.*' => 'required|regex:/^[a-zA-Z ]+$/u|min:1',
            'email.*' => 'required|email:rfc,dns'
        ];

        $custom = [
            'name.*.required' => 'Name Field is required',
            'name.*.regex' => 'Name Field Must Be String',
            'email.*.required' => 'Email Field is required',
            'email.*.email' => 'Email Field Must Be Valid Email Address'
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

            if(count($request->email)>0)
            {
                foreach($request->email as $key => $value)
                {
                    $rules = [
                        'status-'.$key => 'required',
                    ];
            
                    $custom = [
                        'status-'.$key.'.required' => 'Status Field is Required',
                    ];
            
                    $validator = Validator::make($request->all(), $rules,$custom);
                    
                    if ($validator->fails()){
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ]);
                    }


                }
            }

            DB::table('notification_control_configs')->where(['business_id'=>$user_id,'type'=>'vendor-revert-type-mail'])->delete();

            if(count($request->email)>0)
            {
                $user = DB::table('users')->where(['id'=>$user_id])->first();

                foreach($request->email as $key => $value)
                {
                    $status = 0;
                   
                    if($request->input('status-'.$key)==1)
                    {
                        $status = 1;
                    }

                    DB::table('notification_control_configs')->insert([
                        'parent_id' => $user->parent_id,
                        'business_id' => $user->business_id,
                        'name' => $request->name[$key],
                        'email' => $value,
                        'status' => $status,
                        'type' => 'vendor-revert-type-mail',
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true
            ]);
         }
         catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
    }

    public function notificationVendorDelete(Request $request)
    {
        $id = base64_decode($request->id);

        DB::beginTransaction();
        try{
            if(Auth::check()){

                DB::table('notification_control_configs')->where(['id'=>$id])->delete();
                //return result 
                    
                DB::commit();
                return response()->json([
                'status'=>'ok',
                'message' => 'deleted',                
                ], 200);
    
            }
            else{   
                return response()->json([
                    'status' =>'no',
                    ], 200);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }



}
