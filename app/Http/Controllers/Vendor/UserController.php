<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Admin\UserCheck;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $business_id = Auth::user()->id;

        $users = User::where(['business_id'=>$business_id,'user_type'=>'vendor_user','is_deleted'=>'0'])
        ->orderBy('id','desc')
        ->paginate(5);
        $checks = UserCheck::all();
        // $services = DB::table('services')->select('*')
        // ->get(); 
        // dd($users);
        if($request->ajax())
            return view('vendor.users.ajax', compact('users','checks'));
        else
            return view('vendor.users.index', compact('users','checks'));
        // return view('vendor.users.index', compact('users','checks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $business_id = Auth::user()->business_id;
        // $services = DB::table('services')->select('*')
        // ->get();
        // $roles =DB::table('role_masters')->where('business_id',$business_id)->where('role_type','client')->get();
        return view('vendor.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'first_name' =>'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'last_name' => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'email' => 'required|email:rfc,dns|unique:users',
            'phone'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
            // 'password' => 'required|same:confirm-password',
            
            ];
       
        // $this->validate($request, [
        //     'first_name' => 'required',
        //     'last_name' => 'required', 
        //     'email' => 'required|email|unique:users,email',
        //     // 'password' => 'required|same:confirm-password',
        //     'role'=>'required'
            
        //     ]);
            // 'roles'     => 'required'

            $validator = Validator::make($request->all(), $rules);
            
           if ($validator->fails()){
               return response()->json([
                   'success' => false,
                   'errors' => $validator->errors()
               ]);
           }
            
            $business_id = Auth::user()->business_id;
            $parent_id =Auth::user()->parent_id;

            // $randomPassword = Str::random(10);
            // $hashed_random_password = Hash::make($randomPassword);
            // //
            // if($request->has('password') && !empty($request->input('password')) ){
            //     $randomPassword = $request->input('password');
            //     $hashed_random_password = Hash::make($request->input('password'));
            // }
            
            // $service_id =json_encode($data);
            $token=mt_rand(100000000000000,9999999999999999);
            $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('last_name')));
            $user_data = [
                            'name'          =>   $name,
                            'first_name'    =>   $request->input('first_name'),
                            'last_name'     =>   $request->input('last_name'),
                            'phone'         =>   $request->input('phone'),
                            'phone_code'    =>   $request->primary_phone_code,
                            'phone_iso'     =>   $request->primary_phone_iso,
                            'email'         =>   $request->input('email'),
                            'email_verification_token' =>base64_encode($token),
                            'email_verification_sent_at' => date('Y-m-d H:i:s'),
                            'user_type'     =>   'vendor_user',
                            'status'        =>  '1',
                            'business_id'   =>   $business_id,
                            'parent_id'     =>   $parent_id
                         ];
                        //  dd($user_data);
            $user = User::create($user_data);
            // foreach($request->services as $service){
            //     // $data[] = $service;
            
            // $user_checks= new UserCheck();
            // $user_checks->business_id = $business_id;
            // $user_checks->user_id = $user->id;
            // // $user_checks->checks = $service;
            // $user_checks->save();
            // $user->assignRole($request->input('roles'));
            // }
            
            //send email to customer
            $email = $request->input('email');
            $name  = $request->input('first_name');
            // $data  = array('name'=>$name,'email'=>$email,'password'=>$randomPassword);
            $sender = DB::table('users')->where(['id'=>$business_id])->first();
            $data  = array('name'=>$name,'email'=>$email,'user_id'=>base64_encode($user->id),'business_id'=>base64_encode($business_id),'token_no'=>base64_encode($token),'sender'=>$sender);

            // Mail::send(['html'=>'mails.account-info'], $data, function($message) use($email,$name) {
            //     $message->to($email, $name)->subject
            //         ('BCD- Your account credential');
            //     $message->from(env('MAIL_FROM_ADDRESS'),'BCD System');
            // });

            Mail::send(['html'=>'mails.user-link'], $data, function($message) use($email,$name) {
                $message->to($email, $name)->subject
                   ('myBCD System - Your account credential');
                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
           });

            // return redirect('my/users')
            //     ->with('success', 'User created successfully');

            return response()->json([
                'success' =>true,
                'custom'  =>'yes',
                'email' => $email,
                'errors'  =>[]
              ]);
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    // public function changePassword()
    // {
    //     return view('vendor.change-password');
    // } 
    
    /**
     * Update password
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function updatePassword(Request $request)
    // {
        
    //     $rules = [
    //         'old_password' => 'required',
    //         'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
    //         'password_confirmation' => 'min:8'
            
    //         ];
    
      
    //            $validator = Validator::make($request->all(), $rules);
                
    //            if ($validator->fails()){
    //                return response()->json([
    //                    'success' => false,
    //                    'errors' => $validator->errors()
    //                ]);
    //            }
    
    //     // $this->validate($request, [
    //     //     'old_password' => 'required',
    //     //     'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
    //     //     'password_confirmation' => 'min:8'
            
    //     // ]);
        
    //    $raw_pass =$request->input('password');
       
       
    //     if (!preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $raw_pass)){
          
    //             return response()->json([
    //                 'success' => false,
    //                 'custom'=>'yes',
    //                 'errors' => ['password'=>'Password must be atleast 8 characters long including (Atleast 1 uppercase letter(A–Z), Lowercase letter(a–z), 1 number(0–9), 1 non-alphanumeric symbol(‘$%£!’) !']
    //               ]);
    //     }
    //     // hash password
    //    $password = bcrypt($request->input('password'));

    //    $login = DB::table('users as u')
    //   ->select('u.id','u.first_name','u.last_name','u.password','u.email')    
    //   ->where(['u.id'=>Auth::user()->id])
    //   ->first();

    //    $pStatus = Hash::check($request->get('old_password'),$login->password);
      
    //   // check password 
    //   if($pStatus === false)
    //   {    
    //     return response()->json([
    //         'success' => false,
    //         'custom'=>'yes',
    //         'errors' => ['old_password'=>'Please enter your correct old password']
    //       ]);   
    //   }    
    //   if($pStatus === true) 
    //   {  
    //     DB::table('users')
    //     ->where('id', Auth::user()->id)
    //     ->update(['password'=>$password]);


        
    //     $email = $login->email;
    //     $name  = $login->first_name.' '.$login->last_name;
        
    //     $data  = array('name'=>$name,'email'=>$email);

    //     Mail::send(['html'=>'mails.changed-password'], $data, function($message) use($email,$name) {
    //         $message->to($email, $name)->subject
    //         ('BCD System - Your account credential');
    //         $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
    //         });
        
    //         Auth::logout();

    //         $request->session()->invalidate();

    //         $request->session()->regenerateToken();

    //     return response()->json([
    //         'success' =>true,
    //         'custom'  =>'yes',
    //         'errors'  =>[]
    //     ]);
    //   }

    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $business_id = Auth::user()->business_id;

        $user = User::find($id);
        // $roles =DB::table('role_masters')->where('business_id',$business_id)->where('role_type','client')->get();
        
        // $userRole = $user
        //     ->roles
        //     ->pluck('name', 'name')
        //     ->all(); 

            // $services = DB::table('services')->select('*')
            // ->get();
            // $checks = UserCheck::where('user_id',$id)->get();

        return view('vendor.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $this->validate($request, [
        //     'first_name' =>'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
        //     'last_name' => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
        //     'email' => 'required|email:rfc,dns|unique:users,email,' . $id,
        //     'phone'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
        //     // 'first_name' => 'required', 
        //     // 'phone' => 'required', 
        //     // 'email' => 'required|email|, 
        //     // 'password' => 'same:confirm-password', 
        //     // 'services' => 'required'
        //     ]);
        $rules = [
            'first_name' =>'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'last_name' => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'email' => 'required|email:rfc,dns|unique:users,email,' . $id,
            'phone'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
            // 'password' => 'required|same:confirm-password',
            
            ];

            $validator = Validator::make($request->all(), $rules);
            
           if ($validator->fails()){
               return response()->json([
                   'success' => false,
                   'errors' => $validator->errors()
               ]);
           }
            $business_id = Auth::user()->business_id;
           
            
            // $service_id =json_encode($data);
            
            $input = $request->all();
            // if (!empty($input['password']))
            // {
            //     $password = Hash::make($input['password']);
            //     DB::table('users')->where(['id'=>$id])->update(['password'=>$password]);
            // }
            $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('last_name')));
            $user_data = [
                'first_name'=>$request->input('first_name'),
                'last_name' =>$request->input('last_name'),
                'name'      =>$name,
                'email'     =>$request->input('email'),
                'role'      =>  $request->input('role'),
                'phone'     =>$request->input('phone'),
                'phone_code'    => $request->primary_phone_code,
                'phone_iso'     => $request->primary_phone_iso,
             ];
            
            DB::table('users')->where(['id'=>$id])->update($user_data);
            // foreach($request->services as $service){
            //     // $data[] = $service;
            
            // $user = User::find($id);
            
            // $update_check =UserCheck::where('user_id',$user->id)->first();
            // $update_check->business_id = $business_id;
            // $update_check->user_id = $user->id;
            // $update_check->checks = $service;
            // $update_check->save();
            // DB::table('user_checks')->where('user_id', $id)->delete();
            // $user->assignRole($request->input('roles'));
            // }
            return response()->json([
                'success' =>true,
                'custom'  =>'yes',
                'errors'  =>[]
              ]);
            // return redirect('vendor/users')
            // ->with('success', 'User Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function deleteUser(Request $request)
    {
        // User::find($id)->delete();
        
        $user_id=base64_decode($request->user_id);

    //   $task=  DB::table('task_assignments')->where(['user_id'=>$user_id,'status'=>'1'])->get();
    //     if (count($task)>0) {
    //         return response()->json([
    //             'status' => '',
    //         ]);
    //     } else {
        // dd($user_id);
            DB::table('users')->where(['id'=>$user_id])->update([
                'is_deleted' => 1,
                'deleted_at' => date('Y-m-d h:i:s'),
                'deleted_by' => Auth::user()->id
            ]);

            //     return redirect()
            //         ->route('users.index')
            //         ->with('success', 'User deleted successfully');
            // }
            return response()->json([
                'status' => 'ok',
            ]);
        // }
    }

    public function userStatus(Request $request)
    {
        $user_id=base64_decode($request->id);
        $user = User::find($user_id);
        $task = DB::table('vendor_task_assignments')->where(['assigned_to'=>$user_id,'status'=>'1'])->first();
        $type = base64_decode($request->type);

        if($task)
        {
            if(stripos($type,'disable')!==false)
            {
                return response()->json([
                    'success'=>false
                ]);
            }
            elseif(stripos($type,'enable')!==false)
            {

                $user->status = 1;
                $user->save();

                return response()->json([
                    'success'=>true,
                    'type' => $type,
                    'message'=>'Status change successfully.'
                ]);
            }
        }
        else
        {
            if(stripos($type,'disable')!==false)
            {
                if($user->status==1){
                    Session::getHandler()->destroy($user->session_id);
                    // $request->session()->regenerateToken();
                }
                $user->status = 0;
                $user->save();

                return response()->json([
                    'success'=>true,
                    'type' => $type,
                    'message'=>'Status change successfully.'
                ]);
            }
            elseif(stripos($type,'enable')!==false)
            {
                $user->status = 1;
                $user->save();

                return response()->json([
                    'success'=>true,
                    'type' => $type,
                    'message'=>'Status change successfully.'
                ]);
            }
        }
    // {
    //     $user_id=base64_decode($request->id);
        
    //     $user = User::find($user_id);
    //     if($request->status==0)
    //     {
    //         if($user->status==1){
    //             Session::getHandler()->destroy($user->session_id);
    //             // $request->session()->regenerateToken();
    //         }
    //     }
    //     $user->status = $request->status;
    //     $user->save();

    //     return response()->json(['success'=>'Status change successfully.']);
    }
    
    public function unblockUser(Request $request)
    {
        // User::find($id)->delete();
        
        $user_id=base64_decode($request->user_id);
        // dd($user_id);
        DB::table('users')->where(['id'=>$user_id])->update([
            'is_blocked' => '0',
            'unblocked_at' => date('Y-m-d h:i:s'),
            'attempts' => NULL
        ]);

        //     return redirect()
        //         ->route('users.index')
        //         ->with('success', 'User deleted successfully');
        // }
        return response()->json([
            'status' => 'ok',
        ]);
    }
}
