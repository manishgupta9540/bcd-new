<?php

namespace App\Http\Controllers\User;
// date_default_timezone_set('Asia/Kolkata');
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $business_id = $request->business_id;
        $user_id = $request->user_id;
        $token_no = $request->token_no;
        // $nextday =null;
        $id = base64_decode( $request->user_id);
        // echo $business_id;
        // echo date('Y-m-d H:i:s');

        $user=User::find(base64_decode($user_id));

        $user_type = DB::table('users as u')
                ->select('u.id','u.user_type')        
                ->where(['u.id' =>$user->business_id])        
                ->first();

              
                    $users = DB::table('users')->where(['id'=>$id])->first();
                    // dd($id);
                    if ($users) {
                    //    $add_days= ->format('d-m-Y H:i:s');
                      
                        $nextday =Carbon::parse($users->email_verification_sent_at)->addHours(24);
                        
                    $now = Carbon::now(); 
                    // dd($now);
                    }
                   $expiry_date= $nextday->diffInHours($now, false); 
                //    dd($expiry_date);
                    // elseif () {
           
                    // }
        if ($expiry_date < 0) {
            // dd($nextday);
           
                  
               
            if($user->password==NULL )
                return view('user.user',compact('business_id','user_id','token_no'));
        
            else{
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

                    return redirect()->to($redirect);

                }
                else
                {
                    $url=env('APP_LOGIN_URL');
                    return redirect()->to($url);
                }
            }
        }
        
        else {
            return view('main-web.expiry');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $business_id = base64_decode($request->business_id);
        $user_id = base64_decode($request->user_id);
        $token_no = $request->token_no;


        $rules = [
            
            'password' => 'required|same:confirm-password|min:10',
            'confirm_password' => 'nullable|same:password|min:10'
           
            ];
    
            // $customMessages = [
            //     'password.required' => 'Please fill the .',
                
            //   ];
      
               $validator = Validator::make($request->all(), $rules);
                
               if ($validator->fails()){
                   return response()->json([
                       'success' => false,
                       'error_type' => 'validation',
                       'errors' => $validator->errors()
                   ]);
               }
      
            // 'roles'     => 'required'
            $pwd = $request->input('password');
            if (!preg_match("/^(?=.{10,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[$@%£!]).*$/", $pwd)){
              
                    return response()->json([
                        'success' => false,
                        'custom'=>'yes',
                        'error_type' => 'validation',
                        'errors' => ['password'=>'Password must be atleast 10 characters long including (Atleast 1 uppercase letter(A–Z), Lowercase letter(a–z), 1 number(0–9), 1 non-alphanumeric symbol(‘$@%£!’) !']
                      ]);
                }

                $user=User::find($user_id);
            
                if($user->status == 0){         
                    return response()->json(['success' => false,'error_type'=>'account-inactive', 'next_action'=>'','redirect'=>'']); 
                } 

                if($user->is_deleted == 1)
                {
                    return response()->json(['success' => false,'error_type'=>'account-deleted', 'next_action'=>'','redirect'=>'']); 
                }

                DB::table('users')
                ->where(['id'=> $user_id,'business_id'=>$business_id,'email_verification_token'=>$token_no])
                ->update(['password'=> Hash::make($request->input('password')),'is_email_verified'=>'1','email_verified_at'=>date('Y-m-d H:i:s'),'email_verification_token'=>NULL]);

                if( $user->status == 1  ){

                    if(Auth::check())
                    {
                        
                        Session::getHandler()->destroy(Auth::user()->session_id);

                        Auth::logout();

                        $request->session()->invalidate();
    
                        $request->session()->regenerateToken();

                    }

                    Auth::loginUsingId($user->id);
                    $user_id = $user->id;    
                    
    
                    $request->session()->regenerate();
                    $previous_session = Auth::User()->session_id;
                    if ($previous_session) {
                        Session::getHandler()->destroy($previous_session);
                    }
                    Auth::user()->session_id = Session::getId();
                    Auth::user()->save();
    
                    //find user type guest and redirect
    
    
                    //find the user type and redirect 
                    $user_type = DB::table('users as u')
                    ->select('u.id','u.user_type')        
                    ->where(['u.id' =>$user->business_id])        
                    ->first();
                    
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
                   
                    
                    return response()->json(['success' => true,'error_type'=>$user_type, 'next_action'=>'','redirect'=>$redirect]); 
                }


                // return response()->json([
                //     'success' =>true,
                //     'custom'  =>'yes',
                //     'errors'  =>[]
                //   ]);
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

    public function thankyou()
    {
        return view('main-web.thank-you');
    }
}
