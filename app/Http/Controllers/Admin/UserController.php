<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\RoleMaster;
use App\Models\Admin\UserCheck;
use App\Models\EmailConfigMaster;
use App\User;
use App\Models\Vendor;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Traits\EmailConfigTrait;
use Illuminate\Support\Facades\Config;
use PDF;
use Illuminate\Support\Facades\File;
use App\Helpers\Helper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BilldataExport;

class UserController extends Controller
{
    
    /*** Show the active package
     * ** @return \Illuminate\Http\Response*/
    
    public function package()
    {
        $profile = User::find(Auth::user()->id);
        
        $package = DB::table('user_subscriptions as us')
                    ->select('sp.*')
                    ->join('subscription_plans as sp','sp.id','=','us.subscription_id')
                    ->where(['us.business_id'=>Auth::user()->business_id])->first();

        return view('admin.accounts.package', compact('profile','package'));
    } 

    /*** Show the profile data
     * ** @return \Illuminate\Http\Response*/
    
    public function profile()
    {
        $profile = User::find(Auth::user()->id);
        
        $business = DB::table('user_businesses')->where(['business_id'=>Auth::user()->business_id])->first();

        $action_route_count = DB::table('role_permissions')->where(['role_id'=>$profile->role,'status'=>'1','business_id'=>Auth::user()->business_id])->count();         
        $action_route = DB::table('role_permissions')->where(['role_id'=>$profile->role,'status'=>'1','business_id'=>Auth::user()->business_id])->first();        
        $permission  = DB::table('action_masters')->where(['route_group'=>'','status'=>'1','parent_id'=>'0'])->orderBy('display_order','ASC')->get();

        return view('admin.accounts.profile', compact('profile','business','action_route_count','action_route','permission'));
    }
    
    /*** Update the profile
     * ** @return \Illuminate\Http\Response*/
    public function update_profile(Request $request)
    {
        $id=Auth::user()->id;
        $this->validate($request, [
            'first_name'   => 'required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'last_name'     => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'phone'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
        ]
       );
        $phone = preg_replace('/\D/', '', $request->input('phone'));
        if(strlen($phone)!=10)
        {
            return back()->withInput()->withErrors(['phone'=>['Phone Number Must be 10-digit Number !!']]);
        }

        // $name = $request->input('first_name').' '.$request->input('last_name');

        // if($request->input('last_name')==null || $request->input('last_name')=='')
        // {
        //     $name = $request->input('first_name');
        // }
        $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('last_name')));
        DB::table('users')->where('id',$id)->update([
            'first_name'    =>ucwords($request->input('first_name')),
            'last_name'     =>ucwords($request->input('last_name')),
            'name'          =>ucwords($name),
            'phone'         =>$phone,
            'updated_at'    => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /*** Show the business info
     * ** @return \Illuminate\Http\Response*/
    
    public function business_info()
    {
        $profile = User::find(Auth::user()->id);

        $business_id = Auth::user()->business_id;

        $countries = DB::table('countries')->get();

        $business = DB::table('user_businesses as b')
        ->select('b.*')
        ->where(['b.business_id'=>$business_id])
        ->first();

        $states  = DB::table('states')->where(['country_id'=>$business->country_id])->get();

        $cities = DB::table('cities')->where(['state_id'=>$business->state_id])->get();

        // $countries  = DB::table('countries')->get();

        return view('admin.accounts.business-info', compact('profile','business','countries','states','cities'));
    } 
    /*** Update the Business Info
     * ** @return \Illuminate\Http\Response*/
    public function updateBusinessInfo(Request $request)
    {
        $business_id=Auth::user()->business_id;

            $this->validate($request, [
                    'address'   => 'required',
                    'pincode'   => 'required|numeric|digits:6',
                    'city'      => 'required',
                    'state'     => 'required',
                    'country'     => 'required',
                    'company'   => 'required',
                    'business_email'            => 'required|email:rfc,dns',
                    'business_phone_number'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
                    'gst_number'                => 'required|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/|min:15|max:15',
                    'contract_signed_by'        => 'required',
                    'hr_name'                   => 'required|regex:/^[a-zA-Z ]+$/u|min:2|max:255',
                    // 'work_order_date'           => 'required|date',
                    // 'work_operating_date'       => 'required|date|after:work_order_date',
                    'tin_number'    => 'nullable|numeric|digits:11',
                    'hsn' => 'nullable|integer|digits:6',
                    'bank_name' => 'required|regex:/^[a-zA-Z ]+$/u|min:1',
                    'account_number' => 'required|regex:/^(?=.*[0-9])[A-Z0-9]{9,18}$/',
                    'ifsc_code' => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{11,}$/'
                ],
                [
                    'business_phone_number.regex' => 'Business Phone Number Must be 10-digit Number !!',
                    'business_phone_number.min' => 'Business Phone Number Must be 10-digit Number !!',
                    'business_phone_number.max' => 'Business Phone Number Must be 10-digit Number !!',
                    'hsn.integer' => 'HSN/SAC must be numeric',
                    'hsn.digits' => 'HSN/SAC must be of 6 digits',
                    'bank_name.regex' => 'Bank Name Must be in letters',
                    'account_number.regex' => 'Enter A Valid Account Number',
                    'ifsc_code.regex' => 'Enter A Valid IFSC Code',
                ]
            );

            $business_phone = preg_replace('/\D/', '', $request->input('business_phone_number'));
            if(strlen($business_phone)!=10)
            {
                return back()->withInput()->withErrors(['business_phone_number'=>['Business Phone Number Must be 10-digit Number !!']]);
            }

            DB::beginTransaction();
            try{
                $countries=DB::table('countries')->where(['id'=>$request->input('country')])->first();

                $states=DB::table('states')->where(['id'=>$request->input('state')])->first();

                $cities=DB::table('cities')->where(['id'=>$request->input('city')])->first();
                
                //update business info
                $b_data = 
                [
                    'company_name'  =>$request->input('company'),
                    'company_short_name'  =>$request->input('company_short_name'),
                    'address_line1' =>$request->input('address'),
                    'zipcode'       =>$request->input('pincode'),
                    'country_name'    =>$countries->name,
                    'city_name'     =>$cities->name,
                    'state_name'    =>$states->name,
                    'country_id'    =>$request->input('country'),
                    'city_id'     =>$request->input('city'),
                    'state_id'    =>$request->input('state'),
                    'email'         =>$request->input('business_email'),
                    'phone'         =>$business_phone,
                    'gst_number'    =>$request->input('gst_number'),
                    'tin_number'    =>$request->input('tin_number'),
                    'hsn_or_sac'            =>$request->input('hsn'),
                    'bank_name'            =>$request->input('bank_name'),
                    'account_number'            =>$request->input('account_number'),
                    'ifsc_code'            =>$request->input('ifsc_code'),
                    'website'    =>$request->input('website'),
                    // 'type_of_facility'    =>$request->input('type_of_facility'),
                    'hr_name'       =>$request->input('hr_name'),
                    // 'work_order_date'       => date('Y-m-d',strtotime($request->input('work_order_date'))),
                    // 'work_operating_date'   => date('Y-m-d',strtotime($request->input('work_operating_date'))),
                    // 'billing_detail'        => $request->input('billing_detail'),
                    'contract_signed_by'    =>$request->input('contract_signed_by'),
                    'updated_at'            => date('Y-m-d H:i:s')
                ];
                
                DB::table('user_businesses')->where(['business_id'=>$business_id])->update($b_data);

                DB::commit();
                return redirect()->back()->with('success', 'Business Info updated successfully.');
            }
            catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return $e;
            } 

    }

    /*** Show the business contacts
     * ** @return \Illuminate\Http\Response*/
    
    public function business_contacts()
    {
       $business_id =  Auth::user()->business_id;

        $profile = User::find(Auth::user()->id);

        $countries = DB::table('countries')->get();

        $owner = DB::table('user_business_contacts as b')
        ->select('b.*')
        ->where(['b.business_id'=>$business_id,'contact_type'=>'owner'])
        ->first();

        $dealing = DB::table('user_business_contacts as b')
        ->select('b.*')
        ->where(['b.business_id'=>$business_id,'contact_type'=>'dealing_officer'])
        ->first();

        $account = DB::table('user_business_contacts as b')
        ->select('b.*')
        ->where(['b.business_id'=>$business_id,'contact_type'=>'account_officer'])
        ->first();

        return view('admin.accounts.contact-info', compact('profile','owner','dealing','account','countries'));
    } 
    //Configration Email page 
    public function emailConfig()
    {

        $email_config = DB::table('email_config_masters')->where('business_id',Auth::user()->business_id)->first();
        return view('admin.accounts.config.email-config',compact('email_config'));
    }

    public function emailConfigSave(Request $request)
    {

        $this->validate($request, [
            'driver'      => 'required',            
            'host'     => 'required',
            'port'     => 'required', 
            'password'  => 'required',
            'encryption'      => 'required',            
            'sender_name'     => 'required',
            'username'     => 'required', 
            'sender_email'  => 'required|email'
         
         ]);
        //  dd($request);



        $business_id = Auth::user()->business_id;
        $email_config = DB::table('email_config_masters')->where('business_id',$business_id)->first();
       
        if ($email_config) {
            // dd($email_config);
            DB::table('email_config_masters')->where('business_id',$business_id)->update([ 'business_id'   =>$business_id,
            'driver'    =>$request->driver,
            'host'      =>$request->host,
            'port'      =>$request->port,
            'encryption' =>$request->encryption,
            'sender_name'=>$request->sender_name,
            'user_name' =>$request->username,
            'password'=>$request->password,
            'sender_email'=>$request->sender_email,
            'updated_at' =>date('Y-m-d H:i:s'), 'updated_by' =>Auth::user()->id]);
           
        } else {
            $cofig_data = [
            
                'business_id'   =>   $business_id,
                'driver'    =>$request->driver,
                'host'      =>$request->host,
                'port'      =>$request->port,
                'encryption' =>$request->encryption,
                'sender_name'=>$request->sender_name,
                'user_name' =>$request->username,
                'password'=>$request->password,
                'sender_email'=>$request->sender_email,
                'created_at' =>date('Y-m-d H:i:s'),
                'created_by'=>Auth::user()->id
                
             ];
            //  dd($user_data);
            DB::table('email_config_masters')->insertGetId($cofig_data);
        }
        
        return redirect()
            ->back()
            ->with('success', 'Email configured successfully');
       
    // }
    }
     /*** Update the business contact Info
     * ** @return \Illuminate\Http\Response*/
    public function updateContactInfo(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $this->validate($request, [
                'owner_first_name'          => 'required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
                'owner_last_name'           => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
                'owner_email'               => 'required|email:rfc,dns',
                'owner_phone_number'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
                'owner_designation'         => 'required',
                'owner_landline_number'=>'nullable|numeric',
                'dealing_first_name'        => 'required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
                'dealing_last_name'         => 'nullable|regex:/^[a-zA-Z]+$/u|min:2|max:255',
                'dealing_email'             => 'required|email:rfc,dns',
                'dealing_phone_number'      => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
                'dealing_designation'       => 'required',
                'dealing_landline_number'   =>'nullable|numeric',
                'account_first_name'        => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
                'account_last_name'         => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
                'account_email'             => 'nullable|email:rfc,dns',
                'account_phone_number'      => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
                'account_landline_number'=>'nullable|numeric',
            ],
            [
                'owner_phone_number.regex' => 'Owner Phone Number Must be 10-digit Number !!',
                'owner_phone_number.min' => 'Owner Phone Number Must be 10-digit Number !!',
                'owner_phone_number.max' => 'Owner Phone Number Must be 10-digit Number !!',
                'dealing_phone_number.regex' => 'Dealing Phone Number Must be 10-digit Number !!',
                'dealing_phone_number.min' => 'Dealing Phone Number Must be 10-digit Number !!',
                'dealing_phone_number.max' => 'Dealing Phone Number Must be 10-digit Number !!',
                'account_phone_number.regex' => 'Account Phone Number Must be 10-digit Number !!',
                'account_phone_number.min' => 'Account Phone Number Must be 10-digit Number !!',
                'account_phone_number.max' => 'Account Phone Number Must be 10-digit Number !!',
            ]
        );

        $owner_phone = preg_replace('/\D/', '', $request->input('owner_phone_number'));
        $dealing_phone = preg_replace('/\D/', '', $request->input('dealing_phone_number'));
        $account_phone = preg_replace('/\D/', '', $request->input('account_phone_number'));

        if(strlen($owner_phone)!=10)
        {
            return back()->withInput()->withErrors(['owner_phone_number'=>['Owner Phone Number Must be 10-digit Number !!']]);
        }
        else if(strlen($dealing_phone)!=10)
        {
            return back()->withInput()->withErrors(['dealing_phone_number'=>['Dealing Phone Number Must be 10-digit Number !!']]);
        }
        else if($request->input('account_phone_number') != "")
        {
            if(strlen($account_phone)!=10)
                return back()->withInput()->withErrors(['account_phone_number'=>['Account Phone Number Must be 10-digit Number !!']]);
        }
        DB::beginTransaction();
        try{
            //contact info
            //owner contact
            $b_data = 
            [
                'designation'   =>$request->input('owner_designation'),
                'first_name'    =>$request->input('owner_first_name'),
                'last_name'     =>$request->input('owner_last_name'),
                'email'         =>$request->input('owner_email'),
                'phone'         =>$owner_phone,
                'landline_number'=>$request->input('owner_landline_number'),
                'updated_at'    => date('Y-m-d H:i:s')
            ];
            
            DB::table('user_business_contacts')->where(['business_id'=>$business_id,'contact_type'=>'owner'])->update($b_data);
            //dealing officer
            $b_data = 
            [
                'designation'   =>$request->input('dealing_designation'),
                'first_name'    =>$request->input('dealing_first_name'),
                'last_name'     =>$request->input('dealing_last_name'),
                'email'         =>$request->input('dealing_email'),
                'phone'         =>$dealing_phone,
                'landline_number'=>$request->input('dealing_landline_number'),
                'updated_at'    => date('Y-m-d H:i:s')
            ];
            
            DB::table('user_business_contacts')->where(['business_id'=>$business_id,'contact_type'=>'dealing_officer'])->update($b_data);

            if($request->input('account_first_name') != "" && $request->input('account_email') !=""){
                //account officer
                $b_data = 
                [
                    'designation'   =>$request->input('account_designation'),
                    'first_name'    =>$request->input('account_first_name'),
                    'last_name'     =>$request->input('account_last_name'),
                    'email'         =>$request->input('account_email'),
                    'phone'         =>$account_phone,
                    'landline_number'=>$request->input('account_landline_number'),
                    'updated_at'     => date('Y-m-d H:i:s')
                ];
                
                DB::table('user_business_contacts')->where(['business_id'=>$business_id,'contact_type'=>'account_officer'])->update($b_data);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Contact Info updated successfully.');
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 


    }

       
    /*** Display a listing of the resource.
     *** @return \Illuminate\Http\Response*/
    
    public function index(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $rows=10;
        $query = DB::table('users as u')
                ->where(['u.business_id'=>$business_id,'u.user_type'=>'user','u.is_deleted'=>0]);
                
                    if($request->get('from_date') !=""){
                        $query->whereDate('u.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                    }
                    if($request->get('to_date') !=""){
                        $query->whereDate('u.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                    }
                    if($request->get('email')){
                        $query->where('u.email',$request->get('email'));
                    }
                    if($request->get('mob')){
                     $query->where('u.phone',$request->get('mob'));
                    }
                    if($request->get('ref')){
                        $query->where('u.display_id',$request->get('ref'));
                    }
                    if($request->get('role')){
                        $query->where('u.role',$request->get('role'));
                    }
                    if($request->get('user')){
                     $query->where('u.id',$request->get('user'));
                    }
                    if($request->get('status')){
                        $query->where('u.status',$request->get('status'));
                    }
                    // if($request->get('service')){
                    //     $query->join('user_checks as uc','uc.user_id','=','u.id')->where(['uc.checks'=>$request->get('service')]);
                    // }
                    $query->groupBy('u.id')->orderBy('u.created_at','desc');
                    // dd($query->get());
                    $users =    $query->paginate($rows);

                    // dd($users);
                            // ->orderBy('name','asc')
                            // ->paginate(5);
        // dd($users);
        $checks = UserCheck::where(['business_id'=>$business_id])->get();
        // dd($checks);
        $services = DB::table('services')
                    ->select('*')
                    ->where('status',1)
                    ->whereNull('business_id')
                    ->whereNotIn('type_name',['e_court'])
                    ->orWhere('business_id',$business_id)
                    ->orderBy('sort_number','asc')
                    ->get(); 
        $users_name=User::where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0,'status'=>'1'])->select('id','name')->orderBy('name','asc')->get();
        $roles = RoleMaster::whereIn('status',['0','1'])->where(['business_id'=>Auth::user()->business_id])->orderBy('id', 'DESC')->get();
        if($request->ajax())
            return view('admin.users.ajax', compact('users','checks','services','users_name','roles'));
        else
            return view('admin.users.index', compact('users','checks','services','users_name','roles'));
    }

    /**
     * set the session data
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function setSessionData( Request $request)
    {   
        //clear session data 
        Session()->forget('customer_id');
        Session()->forget('candidate_id');
        Session()->forget('to_date');
        Session()->forget('from_date');
        

        Session()->forget('bulk_bill_id');

        // Session()->forget('jaf_id');
        // Session()->forget('service_id');

        if( is_numeric($request->get('customer_id')) ){             
            session()->put('customer_id', $request->get('customer_id'));
        }
        if( is_numeric($request->get('candidate_id')) ){             
          session()->put('candidate_id', $request->get('candidate_id'));
        }
        // both date is selected 
        if($request->get('report_date') !="" && $request->get('to_date') !=""){
            session()->put('report_from_date', $request->get('report_date'));
            session()->put('report_to_date', $request->get('to_date'));
        }
        else
        {
          if($request->get('from_date') !=""){
            session()->put('from_date', $request->get('from_date'));
          }
          if($request->get('to_date') !=""){
            session()->put('to_date', $request->get('to_date'));
          }
        }
        //
        

        // if($request->get('jaf_id')!="")
        // {
        //   session()->put('jaf_id', $request->get('jaf_id'));
        // }

        // if($request->get('service_id')!="")
        // {
        //   session()->put('service_id', $request->get('service_id'));
        // }

        if($request->get('bulk_bill_id'))
        {
            session()->put('bulk_bill_id', $request->get('bulk_bill_id'));
        }

        // dd(session()->get('export_candidate_id'));

        echo '1';
    }
    
    /*** Show the form for creating a new resource.
     * ** @return \Illuminate\Http\Response*/
    
    public function create()
    {
        $business_id = Auth::user()->business_id;

        // $roles = Role::pluck('name', 'name')->all();
                    $services = DB::table('services as s')
                    ->select('s.*')
                    ->join('service_form_inputs as si','s.id','=','si.service_id')
                    ->where('s.business_id',NULL)
                    ->whereNotIn('s.type_name',['gstin'])
                    ->orwhere('s.business_id',$business_id)
                    ->where('s.status',1)
                    ->orderBy('s.sort_number','asc')
                    ->groupBy('si.service_id')
                    ->get();
                    // dd($services);
        $roles =DB::table('role_masters')->where(['business_id'=>$business_id,'status'=>'1'])->where('role_type','customer')->get();
        $vendors =Vendor::where(['status'=>'1','business_id'=>$business_id])->get();
    
        return view('admin.users.create', compact('roles','services','vendors'));
    } 
    
    /*** Store a newly created resource in storage.
    *** @param  \Illuminate\Http\Request  $request* @return \Illuminate\Http\Response*/

    public function store(Request $request)
    {
        $rules = [
        'first_name' => 'required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
        'last_name' => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255', 
        'email' => 'required|email:rfc,dns|unique:users,email',
        'phone'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11',
        // 'password' => 'required|same:confirm-password',
        'services' => 'required|array|min:1',
        'role' => 'required',
        // 'vendor_' => 'required'
        ];

        $customMessages = 
        [
            'services.required' => 'Select at least one Check or Service item.',
            'phone.regex' => 'Phone Number Must be 10-digit Number !!',
            'phone.min' => 'Phone Number Must be 10-digit Number !!',
            'phone.max' => 'Phone Number Must be 10-digit Number !!'
        ];
  
           $validator = Validator::make($request->all(), $rules,$customMessages);
            
           if ($validator->fails()){
               return response()->json([
                   'success' => false,
                   'errors' => $validator->errors()
               ]);
           }
  
        // 'roles'     => 'required'
        // $pwd = $request->input('password');
        // if (!preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $pwd)){
          
        //         return response()->json([
        //             'success' => false,
        //             'custom'=>'yes',
        //             'errors' => ['password'=>'Password should be strong ( Atleast 1 capital alphabet,1 small alphabet,1 numeric , 1 special character)  and length should be min. 8 characters !']
        //           ]);
        //     }
        $business_id = Auth::user()->business_id;
        $parent_id = Auth::user()->parent_id;

        $token=mt_rand(100000000000000,9999999999999999);

        $phone = preg_replace('/\D/', '', $request->input('phone'));
        
        if(strlen($phone)!=10){
            return response()->json([
                'success' => false,
                'custom'=>'yes',
                'errors' => ['phone'=>'Phone Number must be 10-digit Number !!']
              ]);
        }
        DB::beginTransaction();
        try{

            $vendors = [];
            $vendor_id = $request->input('vendor_id');
            $vendors = implode(',',$vendor_id ?? []);
            // implode($separator, $array ?? []);
            //dd($vendors);
            // $service_id =json_encode($data);
            $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('last_name')));
            $user_data = [
                        'name'          =>   ucwords($name),
                        'first_name'    =>   ucwords($request->input('first_name')),
                        'last_name'     =>   ucwords($request->input('last_name')),
                        'phone'         =>   $phone,
                        'phone_code'    => $request->primary_phone_code,
                        'phone_iso'     => $request->primary_phone_iso,
                        'email'         =>   $request->input('email'),
                        // 'password'      =>   Hash::make($request->input('password')),
                        'email_verification_token' =>base64_encode($token),
                        'email_verification_sent_at' => date('Y-m-d H:i:s'),
                        'user_type'     =>   'user',
                        'role'          =>  $request->input('role'),
                        'business_id'   =>   $business_id,
                        'parent_id'     => $parent_id,
                        'vendor_id'   => $vendors,
                     ];
                    //  dd($user_data);
                    $user = User::create($user_data);
                    // $user_id =  $user_id->id;
                   
                    $u_id = str_pad($user->id, 10, "0", STR_PAD_LEFT);
                    $display_id = trim(strtoupper(str_replace(array(' ','-'),'',substr(Helper::company_name($user->business_id),0,4)))).'-'.$u_id;
                    DB::table('users')->where(['id'=>$user->id])->update([
                        'display_id' => $display_id
                    ]);
                    foreach($request->services as $service){
                        // $data[] = $service;
                    
                           
                            $user_checks = [
                            'business_id' => $business_id,
                            'user_id' => $user->id,
                            'checks' => $service,
                            'created_by' =>Auth::user()->id,
                            'created_at' => date('Y-m-d h:i:s')
                            ];
                            UserCheck::create($user_checks);
                    
                    }
            //send email to User
            // $email = $request->input('email');
            // $name  = $request->input('first_name');
            // $sender = DB::table('users')->where(['id'=>$business_id])->first();
            // $data  = array('name'=>$name,'email'=>$email,'user_id'=>base64_encode($user->id),'business_id'=>base64_encode($business_id),'token_no'=>base64_encode($token),'sender'=>$sender);

            // Mail::send(['html'=>'mails.user-link'], $data, function($message) use($email,$name) {
            //     $message->to($email, $name)->subject
            //         ('myBCD System - Your account credential');
            //     $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
            // });
            //     // return redirect()
            //     //     ->route('users.index')
            //     //     ->with('success', 'User created successfully');
            //     DB::commit();
            //     return response()->json([
            //         'success' =>true,
            //         'custom'  =>'yes',
            //         'email' => $email,
            //         'errors'  =>[]
            //     ]);
       
            //send email to User
            $email = $request->input('email');
            $name  = $request->input('first_name');
            $sender = DB::table('users')->where(['id'=>$business_id])->first();
            $data  = array('name'=>$name,'email'=>$email,'user_id'=>base64_encode($user->id),'business_id'=>base64_encode($business_id),'token_no'=>base64_encode($token),'sender'=>$sender);
            // Set Email Config Trait
            EmailConfigTrait::emailConfig();
            //get Mail config data
            //   $mail =null;
            $mail= Config::get('mail');
        
            // dd($mail['from']['address']);
            if (count($mail)>0) {
                Mail::send(['html'=>'mails.user-link'], $data, function($message) use($email,$name,$mail) {
                    $message->to($email, $name)->subject
                    ('myBCD System - Your account credential');
                    $message->from($mail['from']['address'],$mail['from']['name']);
            });
            } else {
                Mail::send(['html'=>'mails.user-link'], $data, function($message) use($email,$name) {
                    $message->to($email, $name)->subject
                    ('myBCD System - Your account credential');
                    $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                    });
            }
            
        
            // return redirect()
            //     ->route('users.index')
            //     ->with('success', 'User created successfully');
            DB::commit();
            return response()->json([
                'success' =>true,
                'custom'  =>'yes',
                'email' => $email,
                'errors'  =>[]
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }  

    } 
    
    /*** Display the specified resource.** 
     * @param  int  $id* @return \Illuminate\Http\Response*/

    public function show($id)
    {
        $user = User::find($id);
        return view('admin.users.show', compact('user'));
    } 
    

    /*** Show the form for editing the specified resource.** 
     * @param  int  $id* @return \Illuminate\Http\Response*/

    public function changePassword()
    {
        return view('admin.change-password');
    } 
    
    /**
     * Update password
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $user_id = Auth::user()->id;

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

            // $this->validate($request, [
            //     'old_password' => 'required',
            //     'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
            //     'password_confirmation' => 'min:8'
                
            // ]);
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
            ->select('u.id','u.first_name','u.last_name','u.password','u.email','u.name')    
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
            if($pStatus === true) 
            {  
                DB::table('users')
                ->where('id', $user_id)
                ->update(['password'=>$password]);

                $email = $login->email;
                $name  = $login->name;
                $sender = DB::table('users')->where(['id'=>$business_id])->first();
                $data  = array('name'=>$name,'email'=>$email,'sender'=>$sender);
                EmailConfigTrait::emailConfig();
                    //get Mail config data
                        //   $mail =null;
                        $mail= Config::get('mail');
                        // dd($mail['from']['address']);
                    if (count($mail)>0) {
                            Mail::send(['html'=>'mails.changed-password'], $data, function($message) use($email,$name,$mail) {
                                $message->to($email, $name)->subject
                                ('myBCD System - Your account credential');
                                $message->from($mail['from']['address'],$mail['from']['name']);
                            });
                    }else {
                        Mail::send(['html'=>'mails.changed-password'], $data, function($message) use($email,$name) {
                        $message->to($email, $name)->subject
                            ('myBCD System - Your account credential');
                        $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        });
                    }

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


    // SELECT u.id,u.name,u.email, GROUP_CONCAT(DISTINCT s.name) AS alot_services FROM `users` AS u LEFT JOIN user_checks AS uc ON u.id = uc.user_id LEFT JOIN job_sla_items AS jsi ON uc.checks = jsi.service_id LEFT JOIN services AS s ON jsi.service_id = s.id where u.business_id = '102' and u.user_type='user' GROUP BY u.id
    /*** Show the form for editing the specified resource.** 
     * @param  int  $id* @return \Illuminate\Http\Response*/

    public function edit($id)
    {
        $business_id = Auth::user()->business_id;

        $user = User::find(base64_decode($id));
        // dd($user);
        $roles =DB::table('role_masters')->where(['business_id'=>$business_id,'status'=>'1'])->where('role_type','customer')->get();
        
        // $userRole = $user
        //     ->roles
        //     ->pluck('name', 'name')
        //     ->all(); 

        $services = DB::table('services as s')
        ->select('s.*')
        ->join('service_form_inputs as si','s.id','=','si.service_id')
        ->where('s.business_id',NULL)
        ->whereNotIn('s.type_name',['gstin'])
        ->orwhere('s.business_id',$business_id)
        ->where('s.status',1)
        ->orderBy('s.sort_number','asc')
        ->groupBy('si.service_id')
        ->get();
                        // dd($services);
        $checks = UserCheck::where('user_id',base64_decode($id))->get();

        //$vendors =User::where(['status'=>'1'])->where('user_type','vendor')->get();
        $vendors =Vendor::where(['status'=>'1','business_id'=>$business_id])->get();
        return view('admin.users.edit', compact('user', 'roles','services','checks','vendors'));
    } 
    
    /*** Update the specified resource in storage.** 
     * @param  \Illuminate\Http\Request  
     * $request* @param  int  $id* @return \Illuminate\Http\Response
    */

    public function update(Request $request, $id)
    {
        $id=base64_decode($id);
        
        $this->validate($request, [
                'first_name' => 'required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
                'last_name' => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255', 
                'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11', 
                'email' => 'required|email:rfc,dns|unique:users,email,' . $id, 
                // 'password' => 'same:confirm-password', 
                'services' => 'required|array|min:1',
                'role' => 'required'
            ],
            [
                'services.required' => 'Select at least one Check or Service item.',
                'phone.regex' => 'Phone Number Must be 10-digit Number !!',
                'phone.min' => 'Phone Number Must be 10-digit Number !!',
                'phone.max' => 'Phone Number Must be 10-digit Number !!'
            ]
        );
        $business_id = Auth::user()->business_id;
       
        
        // $service_id =json_encode($data);
        
        $input = $request->all();
        // if (!empty($input['password']))
        // {
        //     $password = Hash::make($input['password']);
        //     DB::table('users')->where(['id'=>$id])->update(['password'=>$password]);
        // }
        $phone = preg_replace('/\D/', '', $request->input('phone'));

        if(strlen($phone)!=10)
        {
            return back()->withInput()->withErrors(['phone'=>['Phone Number Must be 10-digit Number !!']]);
        }
        
        DB::beginTransaction();
        try{

            $vendors = [];
            $vendor_id = $request->input('vendor_id');
            $vendors = implode(',',$vendor_id ?? []);

                $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('last_name')));        
                $user_data = [
                    'first_name'=> ucwords($request->input('first_name')),
                    'last_name' => ucwords($request->input('last_name')),
                    'name'      => ucwords($name),
                    'email'     => $request->input('email'),
                    'role'      =>  $request->input('role'),
                    'phone'     =>  $phone,
                    'phone_code'    => $request->primary_phone_code,
                    'phone_iso'     => $request->primary_phone_iso,
                    'updated_by'    =>Auth::user()->id,
                    'updated_at'    =>date('Y-m-d h:i:s'),
                    'vendor_id'   => $vendors,
                ];
                
                    $update=  User::find($id);
                    $update->update($user_data);

                    if($update->display_id==NULL)
                    {
                        $u_id = str_pad($update->id, 10, "0", STR_PAD_LEFT);
                        $display_id = trim(strtoupper(str_replace(array(' ','-'),'',substr(Helper::company_name($update->business_id),0,4)))).'-'.$u_id;
                        DB::table('users')->where(['id'=>$update->id])->update([
                            'display_id' => $display_id
                        ]);

                    }
                   

                    $user_checks = UserCheck::where('user_id', $id)->whereNotIn('checks',$request->services)->get();

                    foreach ($user_checks as $user_check) {
                        
                        $delete= UserCheck::find($user_check->id);
                        $delete->delete();
                    }
                    

                foreach($request->services as $service){
                    // $data[] = $service;
                    // dd($id);
                    $user = User::find($id);
                    
                    $update_check =UserCheck::where(['user_id'=>$id,'checks'=>$service])->first();
                    // print_r($update_check->checks);
                    // die;
                    if (!$update_check) {

                        $user_checks = [
                            'business_id' => $business_id,
                            'user_id' => $user->id,
                            'checks' => $service,
                            'created_by' =>Auth::user()->id,
                            'created_at' => date('Y-m-d h:i:s')
                            ];
                            UserCheck::create($user_checks);
                       
                    }
                    
                
                    // $user->assignRole($request->input('roles'));
                }
                // DB::table('user_checks')->where('user_id', $id)->whereNotIn('checks',$request->services)->delete();

                DB::commit();
                return redirect()
                    ->route('users.index')
                    ->with('success', 'User updated successfully');
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 

    } 
    
    /*** Remove the specified resource from storage.** 
     * @param  int  $id* @return \Illuminate\Http\Response*/

    public function deleteUser(Request $request)
    {
        // User::find($id)->delete();
        
        $user_id=base64_decode($request->user_id);

        $task=  DB::table('task_assignments')->where(['user_id'=>$user_id,'status'=>'1'])->get();
        if (count($task)>0) {
            return response()->json([
                'status' => 'task incomplete',
            ]);
        } else {
            // dd($user_id);

            // DB::table('users')->where(['id'=>$user_id])->update(

               $user_data= [
                    'is_deleted' => 1,
                    'deleted_at' => date('Y-m-d h:i:s'),
                    'deleted_by' => Auth::user()->id
                ];
                $update=  User::find($user_id);
                $update->update($user_data);
            //     return redirect()
            //         ->route('users.index')
            //         ->with('success', 'User deleted successfully');
            // }
            return response()->json([
                'status' => 'ok',
            ]);
        }
    }

    //When user  attempts multiple tines wrong password  
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
    //Change status
    public function userStatus(Request $request)
    {
        $user_id=base64_decode($request->id);
        $user = User::find($user_id);
        $task = DB::table('task_assignments')->where(['user_id'=>$user_id,'status'=>'1'])->first();
        $type = base64_decode($request->type);
        
        // if ($task) {
        //     if($request->status==0)
        //     {
        //         return response()->json([
        //             'success'=>false
        //         ]);
        //     }
        //     if($request->status==1)
        //     {
        //         $user->status = $request->status;
        //         $user->save();

        //         return response()->json([
        //             'success'=>true,
        //             'message'=>'Status change successfully.'
        //         ]);
        //     }
        // }else {
        //     # code...
       
        //     if($request->status==0)
        //     {
        //         if($user->status==1){
        //             Session::getHandler()->destroy($user->session_id);
        //             // $request->session()->regenerateToken();
        //         }
        //     }
        //     $user->status = $request->status;
        //     $user->save();

        //     return response()->json([
        //         'success'=>true,
        //         'message'=>'Status change successfully.'
        //     ]);
        // }

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
    }

}

