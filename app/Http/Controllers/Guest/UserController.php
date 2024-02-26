<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\GuestHelp;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
class UserController extends Controller
{

    /*** Show the profile data
     * ** @return \Illuminate\Http\Response*/
    
    public function profile()
    {
        $profile = User::find(Auth::user()->id);

        return view('guest.accounts.profile', compact('profile'));
    }

    public function update_profile(Request $request)
    {
        $id=Auth::user()->id;
        $business_id =Auth::user()->id;

        $this->validate($request, [
            'first_name'   => 'required|regex:/^[A-Za-z]+([A-Za-z]+\s)*[A-za-z]+$/u|min:1|max:255',
            'last_name'     => 'nullable|regex:/^[A-Za-z]+([A-Za-z]+\s)*[A-za-z]+$/u|min:1|max:255',
            'phone'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:11',
            'job_title'   => 'nullable|regex:/^[A-Za-z]+([A-Za-z]+\s)*[A-za-z]+$/u|min:2|max:255',
        ],
        [
            'first_name.regex' => 'First Name Must Be A String',
            'last_name.regex' => 'Last Name Name Must Be A String',
            'phone.required' => 'Phone Number Is Required',
            'phone.regex' => 'Phone Number Must Be 10-digit Number',
            'phone.min' => 'Phone Number Must Be 10-digit Number',
            'phone.max' => 'Phone Number Must Be 10-digit Number',
            'job_title.regex' => 'Job Title Must Be A String',
        ]
       );
        $phone = preg_replace('/\D/', '', $request->input('phone'));

        $user_phone=DB::table('users')->where(['phone'=>$phone])->whereNotIn('id',[$id])->count();

        if($user_phone > 0)
        {
            return back()->withInput()->withErrors(['phone'=>['Phone Number Already Exists !!']]);
        }

        $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('last_name')));
        
        DB::table('users')->where('id',$id)->update([
            'first_name'    =>$request->input('first_name'),
            'last_name'     =>$request->input('last_name'),
            'name'          =>$name,
            'phone'         =>$phone,
            'updated_at'    => date('Y-m-d H:i:s')
        ]);

        if($request->has('company_name') && $request->company_name!='')
        {
            $guest_business=DB::table('user_businesses')->where(['business_id'=>$business_id])->first();

            if($guest_business!=NULL)
            {
                DB::table('user_businesses')->where(['business_id'=>$business_id])->update(
                    [ 
                        'company_name' => $request->company_name,
                        'job_title' => $request->company_name!=''?$request->job_title:NULL,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                );
            }
            else
            {
                DB::table('user_businesses')->insert(
                    [ 
                     'business_id' => $business_id,
                     'company_name' => $request->company_name,
                     'job_title' => $request->company_name!=''?$request->job_title:NULL,
                     'created_at' => date('Y-m-d H:i:s')
                    ]
                );
            }
        }
        else
        {
            DB::table('user_businesses')->where(['business_id'=>$business_id])->delete();
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function deleteAccount(Request $request)
    {
        $user_id =Auth::user()->id;

        $business_id = Auth::user()->business_id;

        $parent_id = Auth::user()->parent_id;

        $guest_masters = DB::table('guest_instant_masters')->where(['is_payment_done'=>'0','business_id'=>$business_id])->get();

        if(count($guest_masters)>0)
        {
            return response()->json([
                'success' => false,
                'message' => 'Firstly, Complete Your Pending Orders !!'
            ]);
        }

        DB::beginTransaction();
        try{

            $modules = [];

            $tables = [];

            $module_all = [];

            // Help & Support
            $guest_help = DB::table('guest_helps')->where(['business_id'=>$business_id])->get();

            if(count($guest_help)>0)
            {
                $modules[]='Help & Support';

                array_push($tables,'guest_helps','guest_help_and_support_responses');

                $module_all['Help & Support'] = ['guest_helps','guest_help_and_support_responses'];

                // DB::table('guest_helps')->where(['business_id'=>$business_id])->delete();

                // DB::table('guest_help_and_support_responses')->where(['business_id'=>$business_id])->delete();
            }

            // Instant Verification & Orders

            $guest_masters = DB::table('guest_instant_masters')->where(['business_id'=>$business_id])->get();
            
            if(count($guest_masters)>0)
            {
                $modules[]='Instant Verification & Orders';

                array_push($tables,'guest_instant_masters','guest_instant_carts','guest_instant_cart_services');

                $module_all['Instant Verification & Orders'] = ['guest_instant_masters','guest_instant_carts','guest_instant_cart_services'];

                //Delete the report pdf & zip
                // foreach($guest_masters as $item)
                // {
                //     // Cart Services

                //     $guest_cart_services =DB::table('guest_instant_cart_services')->where(['giv_m_id'=>$item->id])->get();

                //     if(count($guest_cart_services)>0)
                //     {
                //         $path=public_path().'/guest/reports/pdf/';

                //         foreach($guest_cart_services as $gcs)
                //         {
                //             if($gcs->file_name!=NULL && File::exists($path.$gcs->file_name))
                //             {
                //                 File::delete($path.$gcs->file_name);
                //             }
                //         }
                //     }

                //     // Carts 

                //     $guest_carts =DB::table('guest_instant_carts')->where(['giv_m_id'=>$item->id])->get();

                //     if(count($guest_carts)>0)
                //     {
                //         $path = public_path();

                //         foreach($guest_carts as $gc)
                //         {
                //             $services=DB::table('services')->where('id',$gc->service_id)->first();

                //             if($services->name=='Aadhar')
                //             {
                //                 $path = public_path().'/guest/reports/zip/aadhar/';
                //             }
                //             else if($gc->service_id==3)
                //             {
                //                 $path = public_path().'/guest/reports/zip/pan/';
                //             }
                //             else if($services->name=='Voter ID')
                //             {
                //                 $path = public_path().'/guest/reports/zip/voterid/';
                //             }
                //             else if($services->name=='RC')
                //             {
                //                 $path = public_path().'/guest/reports/zip/rc/';
                //             }
                //             else if($services->name=='Passport')
                //             {
                //                 $path = public_path().'/guest/reports/zip/passport/';
                //             }
                //             else if($services->name=='Driving')
                //             {
                //                 $path = public_path().'/guest/reports/zip/driving/';
                //             }
                //             else if($services->name=='Bank Verification')
                //             {
                //                 $path = public_path().'/guest/reports/zip/bank/';
                //             }
                //             else if(stripos($services->name,'E-Court')!==false)
                //             {
                //                 $path = public_path().'/guest/reports/zip/e-court/';
                //             }

                //             if($gc->zip_name!=NULL && File::exists($path.$gc->zip_name))
                //             {
                //                 File::delete($path.$gc->zip_name);
                //             }
                //         }
                //     }

                //     // masters
                //     $path = public_path().'/guest/reports/zip/';

                //     if($item->zip_name!=NULL && File::exists($path.$item->zip_name))
                //     {
                //         File::delete($path.$item->zip_name);
                //     }

                // }
                

                // DB::table('guest_instant_cart_services')->where(['business_id'=>$business_id])->delete();

                // DB::table('guest_instant_carts')->where(['business_id'=>$business_id])->delete();

                // DB::table('guest_instant_masters')->where(['business_id'=>$business_id])->delete();
            }

            // Verification Checks

                // Aadhar 
                
                $aadhar_checks = DB::table('aadhar_checks')->where(['business_id'=>$business_id])->get();

                if(count($aadhar_checks)>0)
                {
                    $tables[]='aadhar_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'aadhar_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['aadhar_checks'];
                    }

                    // DB::table('aadhar_checks')->where(['business_id'=>$business_id])->delete();
                }

                // PAN

                $pan_checks = DB::table('pan_checks')->where(['business_id'=>$business_id])->get();

                if(count($pan_checks)>0)
                {
                    $tables[]='pan_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'pan_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['pan_checks'];
                    }

                    // DB::table('pan_checks')->where(['business_id'=>$business_id])->delete();
                }

                // CIN

                $cin_check=DB::table('cin_checks')->where('business_id',$business_id)->get();

                if(count($cin_check)>0)
                {
                    $tables[]='cin_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'cin_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['cin_checks'];
                    }

                   // DB::table('cin_checks')->where('business_id',$business_id)->delete();
                    
                }

                // Voter ID

                $voter_id_checks = DB::table('voter_id_checks')->where(['business_id'=>$business_id])->get();

                if(count($voter_id_checks)>0)
                {
                    $tables[]='voter_id_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'voter_id_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['voter_id_checks'];
                    }

                    // DB::table('voter_id_checks')->where(['business_id'=>$business_id])->delete();
                }

                // RC

                $rc_checks = DB::table('rc_checks')->where(['business_id'=>$business_id])->get();

                if(count($rc_checks)>0)
                {
                    $tables[]='rc_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'rc_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['rc_checks'];
                    }

                    // DB::table('rc_checks')->where(['business_id'=>$business_id])->delete();
                }

                 // DL

                 $dl_checks = DB::table('dl_checks')->where(['business_id'=>$business_id])->get();

                 if(count($dl_checks)>0)
                 {
                     $tables[]='dl_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'dl_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['dl_checks'];
                    }
 
                    //  DB::table('dl_checks')->where(['business_id'=>$business_id])->delete();
                 }

                 // Passport

                 $passport_checks = DB::table('passport_checks')->where(['business_id'=>$business_id])->get();

                 if(count($passport_checks)>0)
                 {
                     $tables[]='passport_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'passport_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['passport_checks'];
                    }
 
                    //  DB::table('passport_checks')->where(['business_id'=>$business_id])->delete();
                 }

                 // Bank 

                 $bank_account_checks = DB::table('bank_account_checks')->where(['business_id'=>$business_id])->get();

                 if(count($bank_account_checks)>0)
                 {
                     $tables[]='bank_account_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'bank_account_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['bank_account_checks'];
                    }
 
                    //  DB::table('bank_account_checks')->where(['business_id'=>$business_id])->delete();
                 }

                 // Covid-19 Certificate

                 $advance_covid19_otps = DB::table('advance_covid19_otps')->where(['business_id'=>$business_id])->get();

                 if(count($advance_covid19_otps)>0)
                 {
                     $tables[]='advance_covid19_otps';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'advance_covid19_otps');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['advance_covid19_otps'];
                    }
 
                    //  DB::table('advance_covid19_otps')->where(['business_id'=>$business_id])->delete();
                 }

                 $covid19_checks = DB::table('covid19_checks')->where(['business_id'=>$business_id])->get();

                 if(count($covid19_checks)>0)
                 {
                     $tables[]='covid19_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'covid19_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['covid19_checks'];
                    }
 
                    //  DB::table('covid19_checks')->where(['business_id'=>$business_id])->delete();
                 }

                 // E-court

                 $e_court_checks = DB::table('e_court_checks')->where(['business_id'=>$business_id])->get();

                 if(count($e_court_checks)>0)
                 {
                     $tables[]='e_court_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'e_court_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['e_court_checks'];
                    }
 
                    //  DB::table('e_court_checks')->where(['business_id'=>$business_id])->delete();
                 }

                 $e_court_check_items = DB::table('e_court_check_items')->where(['business_id'=>$business_id])->get();

                 if(count($e_court_check_items)>0)
                 {
                     $tables[]='e_court_check_items';

                     if(array_key_exists('Verification Checks',$module_all))
                     {
                         array_push($module_all['Verification Checks'],'e_court_check_items');
                     }
                     else
                     {
                         $module_all['Verification Checks']=['e_court_check_items'];
                     }
 
                    //  DB::table('e_court_check_items')->where(['business_id'=>$business_id])->delete();
                 }

                 
                 if(count($aadhar_checks)>0 || count($pan_checks)>0 || count($voter_id_checks)>0 || count($rc_checks)>0 || count($dl_checks)>0 || count($passport_checks)>0 || count($bank_account_checks)>0 || count($covid19_checks)>0 || count($e_court_checks)>0 || count($cin_check)>0)
                 {
                    $modules[]='Verification Checks';
                 }



                 $modules[]='User';

                 $tables[]='users';
 
                 $module_all['User']=['users'];

                 $user_businesses = DB::table('user_businesses')->where(['business_id'=>$business_id])->get();

                 if(count($user_businesses)>0)
                 {
                    $modules[]='User Businesses';

                    $tables[]='user_businesses';
    
                    $module_all['User Businesses']=['user_businesses'];

                    // DB::table('user_businesses')->where(['business_id'=>$business_id])->delete();

                 }

                 DB::table('purge_data_logs')->insert([
                    'parent_id' => $parent_id,
                    'business_id' => $business_id,
                    'user_id' => $user_id,
                    'name' => Auth::user()->name,
                    'modules' => json_encode($modules),
                    'tables' => json_encode($tables),
                    'module_all' => json_encode($module_all),
                    'module_type' => 'account-deleted',
                    'user_type' => 'guest',
                    'created_at' => date('Y-m-d H:i:s')
                 ]);

                // User 

                // DB::table('users')->where(['id'=>$user_id])->delete();



            DB::commit();

            // Auth::logout();

            // $request->session()->invalidate();

            // $request->session()->regenerateToken();

            return response()->json([
                'success' => true,
                'message' => 'Your Account Has Been Deleted Successfully !!'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 

    }

    public function changePassword(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $user_id = Auth::user()->id;

        if ($request->isMethod('get'))
        {
            return view('guest.change-password');
        }

        $rules = [
            'old_password' => 'required',
            'password' => 'min:10|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:10'
            
        ];
    
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        DB::beginTransaction();
        try
        {
            $raw_pass =$request->input('password');
            
            
                if (!preg_match("/^(?=.{10,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[$@%£!]).*$/", $raw_pass)){
                
                        return response()->json([
                            'success' => false,
                            'custom'=>'yes',
                            'errors' => ['password'=>'Password must be atleast 10 characters long including (Atleast 1 uppercase letter(A–Z), Lowercase letter(a–z), 1 number(0–9), 1 non-alphanumeric symbol(‘$%£!’) !']
                        ]);
                }

            // hash password
            $password = bcrypt($request->input('password'));


            $login = DB::table('users as u')
            ->select('u.id','u.first_name','u.last_name','u.password','u.email')    
            ->where(['u.id'=>Auth::user()->id])
            ->first();

            $pStatus = Hash::check($request->get('old_password'),$login->password);
             // check password 
            if($pStatus === false)
            {    
                return response()->json([
                    'success' => false,
                    'custom'=>'yes',
                    'errors' => ['old_password'=>'Please enter your correct old password']
                ]);   
            }
            else if($pStatus === true)
            {
                DB::table('users')
                ->where('id', $user_id)
                ->update(['password'=>$password]);

                $email = $login->email;
                $name  = $login->first_name.' '.$login->last_name;
                $sender = DB::table('users')->where(['id'=>$business_id])->first();
                $data  = array('name'=>$name,'email'=>$email,'sender'=>$sender);

                Mail::send(['html'=>'mails.changed-password'], $data, function($message) use($email,$name) {
                    $message->to($email, $name)->subject
                        ('myBCD System - Your account credential');
                    $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                });

                Session::getHandler()->destroy(Auth::user()->session_id);

                DB::table('users')->where(['id' =>$user_id])->update(['session_id'=>NULL]);

                Auth::logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                DB::commit();
                return response()->json([
                    'success' =>true,
                    'custom'  =>'yes',
                    'errors'  =>[]
                ]);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }

    }

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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

   
}
