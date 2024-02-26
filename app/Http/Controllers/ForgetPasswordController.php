<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\User;
use Illuminate\Support\Facades\Session;
use App\Models\Admin\CandidateReinitiate;
use Illuminate\Support\Facades\Crypt;
use App\PasswordLog;

class ForgetPasswordController extends Controller
{
     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $id = $request->id;
        $token_no = $request->token_no;
        // $user_id = $request->user_id;
        // $token_no = $request->token_no;
        // echo $business_id;
        // echo date('Y-m-d H:i:s');
        $user=User::find(base64_decode($id));

        if ($user->user_type =='candidate') {
            $user_type = DB::table('users as u')
                ->select('u.id','u.user_type')        
                ->where(['u.id' =>$user->id])        
                ->first();
        }
        else
        {
            $user_type = DB::table('users as u')
            ->select('u.id','u.user_type')        
            ->where(['u.id' =>$user->business_id])        
            ->first();
        }

        if($user->email_verification_token!=NULL)
        {
            $form_url = url('/forget/password/update');
            return view('forget-password',compact('id','token_no','form_url'));
        }
        else
        {
            if(Auth::check())
            {
                if($user_type->user_type == 'customer'){
                    $redirect = env('APP_ADMIN_URL');
                }

                if($user_type->user_type == 'client'){
                    $redirect = env('APP_CLIENT_HOME');
                }

                if($user_type->user_type == 'vendor'){
                    $redirect = env('APP_VENDOR_URL');
                }
                if($user_type->user_type == 'superadmin'){
                    $redirect = env('APP_SUPERADMIN_URL');
                }
                
                if($user_type->user_type == 'guest'){
                    $redirect = env('APP_INSTANT_HOME');
                }

                if($user_type->user_type == 'candidate'){
                    $redirect = env('APP_CANDIDATE_URL');
                }

                return redirect()->to($redirect);

            }
            else
            {
                $url=env('APP_LOGIN_URL');
                return redirect()->to($url);
            }
        }
    }

    /**
     * Update password
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
        $id = base64_decode($request->id);
        // dd($id);
        $user_id = DB::table('users')->where('id',$id)->first();
         

        $token_no = base64_decode($request->token_no);
        // dd($id);
        $rules = [
            
            'new_password' => 'required|min:10|same:confirm_password',
            'confirm_password' => 'required|min:10|same:new_password'
            
            ];
    
               $validator = Validator::make($request->all(), $rules);
                
               if ($validator->fails()){
                   return response()->json([
                       'success' => false,
                       'error_type' => 'validation',
                       'errors' => $validator->errors()
                   ]);
               }    //   dd($login);
               //    $pStatus = Hash::check($request->get('old_password'),$login->password);

        // $this->validate($request, [
        //     'old_password' => 'required',
        //     'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
        //     'password_confirmation' => 'min:8'
            
        // ]);
        
       $raw_pass =$request->input('new_password');
       
       
        if (!preg_match("/^(?=.{10,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[$@%£!]).*$/", $raw_pass)){
          
                return response()->json([
                    'success' => false,
                    'custom'=>'yes',
                    'error_type' => 'validation',
                    'errors' => ['new_password'=>'Password must be atleast 10 characters long including (Atleast 1 uppercase letter(A–Z), Lowercase letter(a–z), 1 number(0–9), 1 non-alphanumeric symbol(‘$@%£!’) !']
                  ]);
        }
        // hash password
       $password = bcrypt($request->input('new_password'));

       

       $login = DB::table('users as u')
                ->where(['u.id'=>$id])
                ->first();

        //   dd($login);
        //    $pStatus = Hash::check($request->get('old_password'),$login->password);
      
      // check password 
        
       if ($login) {

            if($login->status == 0){         
                return response()->json(['success' => false,'error_type'=>'account-inactive', 'next_action'=>'','redirect'=>'']); 
            } 

            if($login->is_deleted == 1)
            {
                return response()->json(['success' => false,'error_type'=>'account-deleted', 'next_action'=>'','redirect'=>'']); 
            }

            if($login->is_email_verified==0 && $login->user_type=='guest')
            {
                return response()->json(['success' => false,'error_type'=>'account-email', 'next_action'=>'','redirect'=>'']); 
            }
            
            $password_logs = PasswordLog::from('password_logs')->where('user_id',$id)->orderBy('id','desc')->take(3)->get();
            
            foreach($password_logs as $passworddata){
                 $namepassword = $passworddata->password;
              
                $check = Hash::check($raw_pass,$namepassword);
            
                if($check == true)
                {    
                    return response()->json([
                        'success' => false,
                        'error_type'=>'password-not-match', 
                        'next_action'=>'',
                        'redirect'=>''
                    ]);   
                }
               
            }


          
            DB::table('users')->where('id', $id)->update(['password'=>$password,'email_verification_token' => NULL]);
            
            PasswordLog::from('password_logs')->create([
                'parent_id' => $user_id->parent_id,
                'business_id' => $user_id->business_id,
                'user_id'  => $user_id->id,
                'email' => $user_id->email,
                'password' => $password,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            

            if(Auth::check())
            {

                Session::getHandler()->destroy(Auth::user()->session_id);

                DB::table('users')->where(['id' =>$id])->update(['session_id'=>NULL]);

                Auth::logout();

                $request->session()->invalidate();
    
                $request->session()->regenerateToken();
            }

            Auth::loginUsingId($login->id);
            $user_id = $login->id;    
                    
    
            $request->session()->regenerate();
            $previous_session = Auth::User()->session_id;
            if ($previous_session) {
                Session::getHandler()->destroy($previous_session);
            }
            Auth::user()->session_id = Session::getId();
            Auth::user()->save();
    
                //find user type guest and redirect


                //find the user type and redirect 

                if ($login->user_type =='candidate') {
                    $user_type = DB::table('users as u')
                        ->select('u.id','u.user_type')        
                        ->where(['u.id' =>$login->id])        
                        ->first();
                }
                else
                {
                    $user_type = DB::table('users as u')
                    ->select('u.id','u.user_type')        
                    ->where(['u.id' =>$login->business_id])        
                    ->first();
                }
                
                if($user_type->user_type == 'customer'){
                    $redirect = env('APP_ADMIN_URL');
                }

                if($user_type->user_type == 'client'){
                    $redirect = env('APP_CLIENT_HOME');
                }

                if($user_type->user_type == 'vendor'){
                    $redirect = env('APP_VENDOR_URL');
                }
                if($user_type->user_type == 'superadmin'){
                    $redirect = env('APP_SUPERADMIN_URL');
                }
                
                if($user_type->user_type == 'guest'){
                    $redirect = env('APP_INSTANT_HOME');
                }

                if($user_type->user_type == 'candidate'){
                    $redirect = env('APP_CANDIDATE_URL');
                }
                
                
                return response()->json(['success' => true,'error_type'=>$user_type, 'next_action'=>'','redirect'=>$redirect]); 


            // return response()->json([
            //     'success' =>true,
            //     'custom'  =>'yes',
            //     'errors'  =>[]
            // ]);
       }

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function candidateCreate(Request $request)
    {
        $id = $request->id;
        $token_no = $request->token_no;
        // $user_id = $request->user_id;
        // $token_no = $request->token_no;
        // echo $business_id;
        // echo date('Y-m-d H:i:s');
        $user=CandidateReinitiate::find(base64_decode($id));

        
        $user_type = DB::table('candidate_reinitiates as u')
            ->select('u.id','u.user_type')        
            ->where(['u.id' =>$user->id])        
            ->first();
        

        if($user->email_verification_token!=NULL)
        {
            $form_url = url('/candidate/forget/password/update');
            return view('forget-password',compact('id','token_no'));
        }
        else
        {
            if(Auth::guard('candidate')->check())
            {
                $redirect = env('APP_CANDIDATE_URL');

                return redirect()->to($redirect);
            }
            else
            {
                $url=env('APP_CANDIDATE_LOGIN_URL');
                return redirect()->to($url);
            }
        }
    }

    /**
     * Update password
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function candidateUpdate(Request $request)
    {
        
        $id = base64_decode($request->id);
        $token_no = base64_decode($request->token_no);
        // dd($id);
        $rules = [
            
            'new_password' => 'required|min:10|same:confirm_password',
            'confirm_password' => 'required|min:10|same:new_password'
            
            ];
    
               $validator = Validator::make($request->all(), $rules);
                
               if ($validator->fails()){
                   return response()->json([
                       'success' => false,
                       'error_type' => 'validation',
                       'errors' => $validator->errors()
                   ]);
               }    //   dd($login);
               //    $pStatus = Hash::check($request->get('old_password'),$login->password);

        // $this->validate($request, [
        //     'old_password' => 'required',
        //     'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
        //     'password_confirmation' => 'min:8'
            
        // ]);
        
       $raw_pass =$request->input('new_password');
       
       
        if (!preg_match("/^(?=.{10,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[$@%£!]).*$/", $raw_pass)){
          
                return response()->json([
                    'success' => false,
                    'custom'=>'yes',
                    'error_type' => 'validation',
                    'errors' => ['new_password'=>'Password must be atleast 10 characters long including (Atleast 1 uppercase letter(A–Z), Lowercase letter(a–z), 1 number(0–9), 1 non-alphanumeric symbol(‘$@%£!’) !']
                  ]);
        }
        // hash password
       $password = bcrypt($request->input('new_password'));

       $login = DB::table('candidate_reinitiates as u')
                ->where(['u.id'=>$id])
                ->first();

        //   dd($login);
        //    $pStatus = Hash::check($request->get('old_password'),$login->password);
      
      // check password 
        
       if ($login) {

            if($login->status == 0){         
                return response()->json(['success' => false,'error_type'=>'account-inactive', 'next_action'=>'','redirect'=>'']); 
            } 

            if($login->is_deleted == 1)
            {
                return response()->json(['success' => false,'error_type'=>'account-deleted', 'next_action'=>'','redirect'=>'']); 
            }

            // if($login->is_email_verified==0 && $login->user_type=='guest')
            // {
            //     return response()->json(['success' => false,'error_type'=>'account-email', 'next_action'=>'','redirect'=>'']); 
            // }
          
            DB::table('candidate_reinitiates')
            ->where('id', $id)
            ->update(['password'=>$password,'email_verification_token' => NULL]);

            if(Auth::guard('candidate')->check())
            {

                Session::getHandler()->destroy(Auth::guard('candidate')->user()->session_id);

                DB::table('candidate_reinitiates')->where(['id' =>$id])->update(['session_id'=>NULL]);

                Auth::guard('candidate')->logout();

                // $request->session()->invalidate();
    
                // $request->session()->regenerateToken();
            }

            Auth::guard('candidate')->loginUsingId($login->id);
            $user_id = $login->id;    
                    
    
            $request->session()->regenerate();
            $previous_session = Auth::guard('candidate')->user()->session_id;
            if ($previous_session) {
                Session::getHandler()->destroy($previous_session);
            }
            Auth::guard('candidate')->user()->session_id = Session::getId();
            Auth::guard('candidate')->user()->save();
    
            //find the user type and redirect 

            
            $user_type = DB::table('candidate_reinitiates as u')
                ->select('u.id','u.user_type')        
                ->where(['u.id' =>$login->id])        
                ->first();

            
            $redirect = env('APP_CANDIDATE_URL');
            
            return response()->json(['success' => true,'error_type'=>$user_type, 'next_action'=>'','redirect'=>$redirect]); 

       }

    }
}
