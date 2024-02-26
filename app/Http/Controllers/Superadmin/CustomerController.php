<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mail;
use PDF;
 
class CustomerController extends Controller
{
   
    public function index()
    {
        $business_id = Auth::user()->business_id;

        $items = DB::table('users as u')
        ->select('u.id','u.first_name','u.last_name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'customer','u.parent_id'=>$business_id])
        ->get();

        return view('superadmin.customers.index',compact('items'));
    }

    //show the form to create customer
    public function create()
    {
        $plans      = DB::table('subscription_plans')->get();
        $sla        = DB::table('sla_masters')->get();
        $countries  = DB::table('countries')->get();
        $state    = DB::table('states')->where(['country_id'=>'101'])->get();

    	return view('superadmin.customers.create', compact('plans','sla','countries','state'));
    }

    //store 
    public function store(Request $request)
    {
        // dd($request);
        $business_id = Auth::user()->business_id;
        $countries=NULL;
        $states=NULL;
        $cities=NULL;
        // Form validation
        $this->validate($request, [
            'first_name'   => 'required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'last_name'     => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'email'     => 'required|email:rfc,dns|unique:users',
            'phone'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:11',
            'address'   => 'required',
            'pincode'   => 'required|numeric|digits:6',
            'city'      => 'required',
            'state'     => 'required',
            'country'   => 'required',
            'company'   => 'required',
            'company_short_name'   => 'required',
            'business_email'            => 'required|email:rfc,dns',
            'business_phone_number'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:11',
            'gst_number'                => 'required|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/|min:15|max:15',
            'contract_signed_by'        => 'required',
            'hr_name'                   => 'required|regex:/^[a-zA-Z ]+$/u|min:2|max:255',
            'work_order_date'           => 'required|date',
            'work_operating_date'       => 'required|date|after:work_order_date',
            'billing_detail'            => 'required',
            'owner_first_name'          => 'required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'owner_last_name'           => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'owner_email'               => 'required|email:rfc,dns',
            'owner_phone_number'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:11',
            'owner_designation'         => 'required',
            'owner_landline_number'     =>'nullable|numeric',
            'dealing_first_name'        => 'required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'dealing_last_name'         => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'dealing_email'             => 'required|email:rfc,dns',
            'dealing_phone_number'      => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:11',
            'dealing_designation'       => 'required',
            'dealing_landline_number'   =>'nullable|numeric',
            'account_first_name'        => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'account_last_name'         => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'account_email'             => 'nullable|email:rfc,dns',
            'account_phone_number'      => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:11',
            'account_landline_number'=>'nullable|numeric',
            'billing_mode'              => 'required',
            'subscription_package'      => 'required',
            'service_type'              => 'required',
            'tin_number'    => 'nullable|numeric|digits:11',
            'hsn' => 'nullable|integer|digits:6',
            'bank_name' => 'required|regex:/^[a-zA-Z ]+$/u|min:1',
            'account_number' => 'required|regex:/^(?=.*[0-9])[A-Z0-9]{9,18}$/',
            'ifsc_code' => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{11,}$/'
        ],
         [
            'hsn.integer' => 'HSN/SAC must be numeric',
            'hsn.digits' => 'HSN/SAC must be of 6 digits',
            'bank_name.regex' => 'Bank Name Must be in letters',
            'account_number.regex' => 'Enter A Valid Account Number',
            'ifsc_code.regex' => 'Enter A Valid IFSC Code',
         ]
        );

        // Start transaction
      DB::beginTransaction();
      try 
      {
                    //insert data
                    $randomPassword         = Str::random(10);
                    $hashed_random_password = bcrypt($randomPassword);
                    //
                    if($request->has('password') && !empty($request->input('password')) ){
                        $randomPassword = $request->input('password');
                        $hashed_random_password = bcrypt($request->input('password'));
                    }

                    $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('last_name')));

                    $user_data = 
                    [
                        'user_type'     =>'customer',
                        'parent_id'     =>$business_id,
                        'first_name'    =>$request->input('first_name'),
                        'last_name'     =>$request->input('last_name'),
                        'name'          =>$name,
                        'email'         =>$request->input('email'),
                        'password'      =>$hashed_random_password,
                        'phone'         =>$request->input('phone'),
                        'customer_type' =>$request->input('customer_type'),
                        'country_id'    =>$request->input('country'),
                        'created_by'    =>Auth::user()->id,
                        'created_at'    =>date('Y-m-d H:i:s')
                    ];
                    
                    $user = User::create($user_data);

                    $countries=DB::table('countries')->where(['id'=>$request->input('country')])->first();

                    $states=DB::table('states')->where(['id'=>$request->input('state')])->first();

                    $cities=DB::table('cities')->where(['id'=>$request->input('city')])->first();
                    
                    //insert business info
                    $b_data = 
                    [
                        'business_id'   =>$user->id,
                        'company_name'  =>$request->input('company'),
                        'company_short_name'  =>$request->input('company_short_name'),
                        'address_line1' =>$request->input('address'),
                        'zipcode'       =>$request->input('pincode'),
                        'country_id'    =>$request->input('country'),
                        'country_name'    =>$countries->name,
                        'city_name'     =>$cities->name,
                        'state_name'    =>$states->name,
                        'city_id'     =>$request->input('city'),
                        'state_id'    =>$request->input('state'),
                        'email'         =>$request->input('business_email'),
                        'phone'         =>$request->input('business_phone_number'),
                        'gst_number'    =>$request->input('gst_number'),
                        'tin_number'    =>$request->input('tin_number'),
                        'hr_name'       =>$request->input('hr_name'),
                        'work_order_date'       => date('Y-m-d',strtotime($request->input('work_order_date'))),
                        'work_operating_date'   => date('Y-m-d',strtotime($request->input('work_operating_date'))),
                        'billing_detail'        => $request->input('billing_detail'),
                        'billing_mode'          =>$request->input('billing_mode'),
                        'service_type'          =>$request->input('service_type'),
                        'contract_signed_by'    =>$request->input('contract_signed_by'),
                        'hsn_or_sac'            =>$request->input('hsn'),
                        'bank_name'            =>$request->input('bank_name'),
                        'account_number'            =>$request->input('account_number'),
                        'ifsc_code'            =>$request->input('ifsc_code'),
                        'created_at'            => date('Y-m-d H:i:s')
                    ];
                    
                    DB::table('user_businesses')->insertGetId($b_data);

                    //contact info
                    //owner contact
                    $b_data = 
                    [
                        'business_id'   =>$user->id,
                        'contact_type'  =>'owner',
                        'designation'   =>$request->input('owner_designation'),
                        'first_name'    =>$request->input('owner_first_name'),
                        'last_name'     =>$request->input('owner_last_name'),
                        'email'         =>$request->input('owner_email'),
                        'phone'         =>$request->input('owner_phone_number'),
                        'landline_number'=>$request->input('owner_landline_number'),
                        'created_at'    => date('Y-m-d H:i:s')
                    ];
                    
                    DB::table('user_business_contacts')->insertGetId($b_data);
                    //dealing officer
                    $b_data = 
                    [
                        'business_id'   =>$user->id,
                        'contact_type'  =>'dealing_officer',
                        'designation'   =>$request->input('dealing_designation'),
                        'first_name'    =>$request->input('dealing_first_name'),
                        'last_name'     =>$request->input('dealing_last_name'),
                        'email'         =>$request->input('dealing_email'),
                        'phone'         =>$request->input('dealing_phone_number'),
                        'landline_number'=>$request->input('dealing_landline_number'),
                        'created_at'    => date('Y-m-d H:i:s')
                    ];
                    
                    DB::table('user_business_contacts')->insertGetId($b_data);

                    //acount officer
                    if( $request->input('account_first_name') != "" && $request->input('account_email') !="")
                    {
                        $b_data = 
                        [
                            'business_id'   =>$user->id,
                            'contact_type'  =>'account_officer',
                            'designation'   =>$request->input('account_designation'),
                            'first_name'    =>$request->input('account_first_name'),
                            'last_name'     =>$request->input('account_last_name'),
                            'email'         => $request->input('account_email'),
                            'phone'         =>$request->input('account_phone_number'),
                            'landline_number'=>$request->input('account_landline_number'),
                            'created_at'     => date('Y-m-d H:i:s')
                        ];

                        DB::table('user_business_contacts')->insertGetId($b_data);
                    }
                    

                        //Update business ID 
                        DB::table('users')->where(['id'=>$user->id])->update(['business_id'=>$user->id,'is_business_data_completed'=>'1']);

                        // Attach customer subscription 
                        DB::table('user_subscriptions')->insertGetId(['business_id'=>$user->id,'subscription_id'=>$request->input('subscription_package'),'status'=>'1','created_at'=>date('Y-m-d H:i:s')]);
                        //Subscription services
                        DB::table('user_services')->insertGetId(['business_id'=>$user->id,'service_id'=>'1','status'=>'1','start_date'=>date('Y-m-d')]);

                        //send email to customer
                        $email = $request->input('email');
                        $name  = $request->input('first_name');
                        $data  = array('name'=>$name,'email'=>$email,'password'=>$randomPassword);
            
                        Mail::send(['html'=>'mails.customer-account-info'], $data, function($message) use($email,$name) {
                            $message->to($email, $name)->subject
                                ('myBCD System - Your account credential');
                            $message->from(env('MAIL_FROM_ADDRESS'),'myBCD System');
                        });

                        
                    
                        // commit 
                        DB::commit();
                        return redirect('/app/customers')
                        ->with('success', 'Customer created successfully.');

      } 
      catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }


   	}

    //show the form to create customer
    public function edit($id)
    { 
        $customer_id = base64_decode($id);
        $plans  = DB::table('subscription_plans')->get();
        $sla    = DB::table('sla_masters')->get();

        $customer_subscription  = DB::table('user_subscriptions')->where('business_id',$customer_id)->first();

        $customer = DB::table('users as u')
        ->select('u.*')
        ->where(['u.id'=>$customer_id])
        ->first();

        $business = DB::table('user_businesses as b')
        ->select('b.*')
        ->where(['b.business_id'=>$customer_id])
        ->first();

        $owner = DB::table('user_business_contacts as b')
        ->select('b.*')
        ->where(['b.business_id'=>$customer_id,'contact_type'=>'owner'])
        ->first();

        $dealing = DB::table('user_business_contacts as b')
        ->select('b.*')
        ->where(['b.business_id'=>$customer_id,'contact_type'=>'dealing_officer'])
        ->first();

        $account = DB::table('user_business_contacts as b')
        ->select('b.*')
        ->where(['b.business_id'=>$customer_id,'contact_type'=>'account_officer'])
        ->first();

        $countries  = DB::table('countries')->get();

        $states  = DB::table('states')->where(['country_id'=>$business->country_id])->get();

        $cities = DB::table('cities')->where(['state_id'=>$business->state_id])->get();

        return view('superadmin.customers.edit', compact('customer','customer_subscription','business','owner','dealing','account','plans','sla','countries','states','cities'));
    }

     //update data 
    public function update(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $countries=NULL;
        $states=NULL;
        $cities=NULL;
        // Form validation
         $this->validate($request, [
            'first_name'=> 'required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'last_name'     => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'address'   => 'required',
            'pincode'   => 'required|numeric|digits:6',
            'city'      => 'required',
            'state'     => 'required',
            'country'   => 'required',
            'company'   => 'required',
            'company_short_name'   => 'required',
            'business_email'            => 'required|email:rfc,dns',
            'business_phone_number'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:11',
            'gst_number'                => 'required|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/|min:15|max:15',
            'contract_signed_by'        => 'required',
            'hr_name'                   => 'required|regex:/^[a-zA-Z ]+$/u|min:2|max:255',
            'work_order_date'           => 'required|date',
            'work_operating_date'       => 'required|date|after:work_order_date',
            'billing_detail'            => 'required',
            'owner_first_name'          => 'required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'owner_last_name'           => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'owner_email'               => 'required|email:rfc,dns',
            'owner_phone_number'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:11',
            'owner_designation'         => 'required',
            'owner_landline_number'=>'nullable|numeric',
            'dealing_first_name'        => 'required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'dealing_last_name'         => 'nullable|regex:/^[a-zA-Z]+$/u|min:2|max:255',
            'dealing_email'             => 'required|email:rfc,dns',
            'dealing_phone_number'      => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:11',
            'dealing_designation'       => 'required',
            'dealing_landline_number'   =>'nullable|numeric',
            'account_first_name'        => 'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'account_last_name'         => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'account_email'             => 'nullable|email:rfc,dns',
            'account_phone_number'      => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:11',
            'account_landline_number'=>'nullable|numeric',
            'billing_mode'              => 'required',
            'subscription_package'      => 'required',
            'service_type'              => 'required',
            'tin_number'    => 'nullable|numeric|digits:11',
            'hsn' => 'nullable|integer|digits:6',
            'bank_name' => 'required|regex:/^[a-zA-Z ]+$/u|min:1',
            'account_number' => 'required|regex:/^(?=.*[0-9])[A-Z0-9]{9,18}$/',
            'ifsc_code' => 'required|regex:/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{11,}$/'
         ],
         [
            'hsn.integer' => 'HSN/SAC must be numeric',
            'hsn.digits' => 'HSN/SAC must be of 6 digits',
            'bank_name.regex' => 'Bank Name Must be in letters',
            'account_number.regex' => 'Enter A Valid Account Number',
            'ifsc_code.regex' => 'Enter A Valid IFSC Code',
         ]
        );

        DB::beginTransaction();
        try 
        {

            //update data
            $business_id =  $request->input('customer_id');

            $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('last_name')));
            //
            $b_data = 
            [
                'first_name'    =>$request->input('first_name'),
                'last_name'     =>$request->input('last_name'),
                'name' => $name,
                'updated_at'    => date('Y-m-d H:i:s')
            ];
            
            DB::table('users')->where(['business_id'=>$business_id])->update($b_data);

            // dd($request->input('country'));

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
                'phone'         =>$request->input('business_phone_number'),
                'gst_number'    =>$request->input('gst_number'),
                'tin_number'    =>$request->input('tin_number'),
                'website'    =>$request->input('website'),
                'type_of_facility'    =>$request->input('type_of_facility'),
                'hr_name'       =>$request->input('hr_name'),
                'work_order_date'       => date('Y-m-d',strtotime($request->input('work_order_date'))),
                'work_operating_date'   => date('Y-m-d',strtotime($request->input('work_operating_date'))),
                'billing_detail'        => $request->input('billing_detail'),
                'billing_mode'          =>$request->input('billing_mode'),
                'service_type'          =>$request->input('service_type'),
                'contract_signed_by'    =>$request->input('contract_signed_by'),
                'hsn_or_sac'            =>$request->input('hsn'),
                'bank_name'            =>$request->input('bank_name'),
                'account_number'       =>$request->input('account_number'),
                'ifsc_code'            =>$request->input('ifsc_code'),
                'updated_at'            => date('Y-m-d H:i:s')
            ];
            
            DB::table('user_businesses')->where(['business_id'=>$business_id])->update($b_data);

            //contact info
            //owner contact
            $b_data = 
            [
                'designation'   =>$request->input('owner_designation'),
                'first_name'    =>$request->input('owner_first_name'),
                'last_name'     =>$request->input('owner_last_name'),
                'email'         =>$request->input('owner_email'),
                'phone'         =>$request->input('owner_phone_number'),
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
                'phone'         =>$request->input('dealing_phone_number'),
                'landline_number'=>$request->input('dealing_landline_number'),
                'updated_at'    => date('Y-m-d H:i:s')
            ];
            
            DB::table('user_business_contacts')->where(['business_id'=>$business_id,'contact_type'=>'dealing_officer'])->update($b_data);
            //acount officer
            $b_data = 
            [
                'designation'   =>$request->input('account_designation'),
                'first_name'    =>$request->input('account_first_name'),
                'last_name'     =>$request->input('account_last_name'),
                'email'         =>$request->input('account_email'),
                'phone'         =>$request->input('account_phone_number'),
                'landline_number'=>$request->input('account_landline_number'),
                'updated_at'     => date('Y-m-d H:i:s')
            ];
            
            DB::table('user_business_contacts')->where(['business_id'=>$business_id,'contact_type'=>'account_officer'])->update($b_data);

            //
            DB::commit();
            return redirect('/app/customers')
                ->with('success', 'Customer updated successfully.');
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }

    }

    //
    public function jobs($id)
    {
        $customer_id = base64_decode($id);

        $item = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name','b.contact_person','b.address_line1','b.zipcode','b.city_name','b.state_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.id'=>$customer_id])
        ->first();

        $jobs = DB::table('jobs as j')
              ->select('j.id','j.title','j.total_candidates','j.created_at','j.created_by','j.status','s.name as verification_type','u.name as customer','u.id as customer_id')
              ->join('users as u','u.id','=','j.business_id')
              ->join('services as s','s.id','=','j.service_id')
              ->where(['j.parent_id'=>$customer_id])
              ->get();

        return view('superadmin.customers.job',compact('item','jobs'));
    }

    //show sla of customers
    public function slas($id)
    {
        $customer_id = base64_decode($id);

        $item = DB::table('users as u')
        ->select('u.id','u.first_name','u.last_name','u.email','u.phone','b.company_name','b.contact_person','b.address_line1','b.zipcode','b.city_name','b.state_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.id'=>$customer_id])
        ->first();

        $sla = DB::table('customer_sla as sla')
                ->select('sla.*')
                ->where(['sla.parent_id'=>$customer_id])
                ->get();

        return view('superadmin.customers.sla',compact('item','sla'));
    }

    //show Sla for candidates
    public function candidateSlas($id)
    {
        $candidate_id = base64_decode($id);

        $item = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name','b.contact_person','b.address_line1','b.zipcode','b.city_name','b.state_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.id'=>$candidate_id])
        ->first();

        $sla = DB::table('customer_sla as sla')
                ->select('sla.*')
                ->where(['sla.business_id'=>$candidate_id])
                ->get();

        return view('superadmin.customers.candidate-sla',compact('item','sla'));
    }
    //show Reports of customer
    public function reportShow($id)
    {
        $customer_id = base64_decode($id);

        $item = DB::table('users as u')
        ->select('u.id','u.first_name','u.last_name','u.email','u.phone','b.company_name','b.contact_person','b.address_line1','b.zipcode','b.city_name','b.state_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.id'=>$customer_id])
        ->first();

        $data = Db::table('reports as r')
        ->select('r.*') 
        ->where(['r.parent_id'=>$customer_id])
        ->get(); 

        return view('superadmin.customers.reports',compact('item','data'));

    }

    //Edit customer's Report
    public function reportEdit($id)
    {
        $id = base64_decode($id);
        // $business_id = Auth::user()->business_id;
        $report_id ="";
        $job = DB::table('job_items')->where(['candidate_id'=>$id])->first(); 
        $sla_id = $job->sla_id;
        //check report items created or not
          $report = DB::table('reports')->where(['candidate_id'=>$id])->first(); 
          $report_id = $report->id;
          $report_status = $report->status;
    
        $candidate = [];
        $report_items = [];
        $candidate =    Db::table('users as u')
                           ->select('u.id','u.business_id','u.first_name','u.last_name','u.name','u.email','u.phone','r.created_at')  
                           ->leftjoin('reports as r','r.candidate_id','=','u.id')
                           ->where(['u.id'=>$id]) 
                           ->first(); 
        
        $report_items = Db::table('report_items as ri')
                           ->select('ri.*','s.name as service_name','s.id as service_id')  
                           ->join('services as s','s.id','=','ri.service_id')
                           ->where(['ri.report_id'=>$report_id]) 
                           ->get(); 
    
          //get JAF data - 
          $jaf = DB::table('jaf_form_data')->select('form_data_all')->where(['candidate_id'=>$id])->first(); 
    
          $status_list = DB::table('report_status_masters')->get();             
    
          return view('superadmin.customers.report-edit', compact('candidate','report','report_items','jaf','report_id','report_status','sla_id','status_list'));
      
    }

    //update customer's report
    public function reportUpdate(Request $request)
    {
        // dd($request);
        $report_id = base64_decode($request->input('report_id'));
        //get report items
        $report_items = DB::table('report_items')->where(['report_id'=>$report_id])->get();
        $i = 0;
        foreach($report_items as $item){
    
            //update report
            $verified_by          = $request->input('verified_by-'.$item->id);
            $comments             = $request->input('comments-'.$item->id);
            $additional_comments  = $request->input('additional-comments-'.$item->id);
            $status_id            = $request->input('approval-status-'.$item->id);
            $district_court_name  = $request->input('district_court_name-'.$item->id);
            $district_court_result= $request->input('district_court_result-'.$item->id);
            $high_court_name      = $request->input('high_court_name-'.$item->id);
            $high_court_result    = $request->input('high_court_result-'.$item->id);
            $supreme_court_name   = $request->input('supreme_court_name-'.$item->id);
            $supreme_court_result = $request->input('supreme_court_result-'.$item->id);
            
            $input_items = DB::table('service_form_inputs as sfi')
                        ->select('sfi.*')            
                        ->where(['sfi.service_id'=>$item->service_id])
                        ->get();
    
            //   dd($input_items);
              $input_data = [];
              $j=0;
              foreach($input_items as $input){
                $remarks     = '-';
                if($request->has('remarks-input-checkbox-'.$item->id.'-'.$j)){
                  $remarks     = 'Yes';
                }
    
                $input_data[] = [
                                  $request->input('service-input-label-'.$item->id.'-'.$j)=>$request->input('service-input-value-'.$item->id.'-'.$j),
                                  'remarks'=>$remarks,
                                  'is_report_output'=>$input->is_report_output
                                ];
                  $j++;
              }
            
              $jaf_data = json_encode($input_data);
              $insuf_notes = NULL;
              if($request->has('insuf_notes-'.$item->id)){
                $insuf_notes     = $request->input('insuf_notes-'.$item->id);
              }
    
            $is_updated = DB::table('report_items')
                          ->where(['report_id'=>$report_id,'id'=>$item->id])
                          ->update(['jaf_data'=>$jaf_data,
                                    'verified_by'=>$verified_by,
                                    'comments'=>$comments,
                                    'additional_comments'=>$additional_comments,
                                    'approval_status_id'=>$status_id,
                                    'report_insufficiency_notes'=>$insuf_notes,
                                    'district_court_name'=>$district_court_name,
                                    'district_court_result'=>$district_court_result,
                                    'high_court_name'=>$high_court_name,
                                    'high_court_result'=>$high_court_result,
                                    'supreme_court_name'=>$supreme_court_name,
                                    'supreme_court_result'=>$supreme_court_result
                                    ]
                                  );
            $i++;
        }
    
        //color status
        $approval_status_id= NULL;
        $report_item_data = DB::table('report_items')->where(['report_id'=>$report_id])->whereNotNull('approval_status_id')->orderBy('approval_status_id','asc')->first();
        if($report_item_data !=null){
          $approval_status_id= $report_item_data->approval_status_id;
        }
    
        //update report status
        DB::table('reports')
          ->where(['id'=>$report_id])
          ->update(['is_verified'=>'1','status'=>'completed','manual_input_status'=>'completed','approval_status_id'=>$approval_status_id,'report_jaf_data'=>json_encode($request->all())]);
    
          $request_id = $request->input('old_id');
            //     $links = session()->has('links') ? session('links') : [];
            //      $currentLink = request()->path(); // Getting current URI like 'category/books/'
            // array_unshift($links, $currentLink); // Putting it in the beginning of links array
            // session(['links' => $links]); // Saving links array to the session //session('links')[2]
        //redirect to reports
        return redirect('/app/customers/reports/show/'.$request_id)
        ->with('success', 'Report updated Successfully. download now');
    
    }

    //candidate Report Show
    public function candidateReportShow($id)
    {
        
        $customer_id = base64_decode($id);

        $item = DB::table('users as u')
        ->select('u.id','u.first_name','u.last_name','u.email','u.phone','b.company_name','b.contact_person','b.address_line1','b.zipcode','b.city_name','b.state_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.id'=>$customer_id])
        ->first();

        $data = Db::table('reports as r')
        ->select('r.*') 
        ->where(['r.business_id'=>$customer_id])
        ->get(); 

        return view('superadmin.customers.candidate-reports',compact('item','data'));

    }
    /**
     * set the session data
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function setSessionData( Request $request)
    { 
         //clear session data 
         Session()->forget('report_id');
         Session()->forget('candidate_id');
         Session()->forget('reportType');
         Session()->forget('customer_id');
      
        Session()->forget('to_date');
        Session()->forget('from_date');
        Session()->forget('check_id');
         if( is_numeric($request->get('report_id')) ){             
             session()->put('report_id', $request->get('report_id'));
         }
         if( is_numeric($request->get('candidate_id')) ){             
           session()->put('candidate_id', $request->get('candidate_id'));
         }
         // both date is selected 
         if($request->get('reportType') !="" ){
             session()->put('reportType', $request->get('reportType'));
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
        if($request->get('check_id') !=""){
        session()->put('check_id', $request->get('check_id'));
        }
         //store log of report exporting data
         $report_data = DB::table('reports')->select('*')->where(['id'=>base64_decode($request->get('report_id'))])->first(); 
 
         $data= ['report_id'=>base64_decode($request->get('report_id')),'report_type'=>$request->get('reportType'),'candidate_id'=>$report_data->candidate_id,'created_at'=>date('Y-m-d H:i:s'),'created_by'=>Auth::user()->id];
         DB::table('report_exports')->insert($data);
 
         echo "1";
    }

    //Customer pdf reports
    public function customerFullReport(Request $request)
    {
    
        $report_id = $request->segment(5); 

        $pdf =new  PDF;
        // echo $report_id; die('tested');
        $data = [];
        //get report items
        $report_id = base64_decode($report_id);

        $report_items = Db::table('report_items as ri')
        ->select('ri.*','s.name as service_name','s.id as service_id')  
        ->join('services as s','s.id','=','ri.service_id')
        ->where(['ri.report_id'=>$report_id]) 
        ->orderBy('s.sort_number','asc')
        ->get(); 

        // get candidate_id
        $report_data = DB::table('reports')->select('candidate_id')->where(['id'=>$report_id])->first(); 

        $candidate =    Db::table('users as u')
                       ->select('u.id','u.business_id','u.client_emp_code','u.first_name','u.last_name','u.name','u.email','u.phone','r.id as report_id','r.created_at','r.approval_status_id','r.sla_id','cs.title as sla_name')  
                       ->leftjoin('reports as r','r.candidate_id','=','u.id')
                       ->join('customer_sla as cs','cs.id','=','r.sla_id')
                       ->where(['r.id'=>$report_id]) 
                       ->first();

        $jaf = DB::table('jaf_form_data')->select('form_data_all')->where(['candidate_id'=>$report_data->candidate_id])->first(); 
        
        $pdf = PDF::loadView('superadmin.customers.pdf.report-pdf', compact('data','report_items','data','jaf','candidate'),[],[
            'title' => 'Report',
            'margin_top' => 20,
            'margin-header'=>20,
            'margin_bottom' =>25,
            'margin_footer'=>5,
            
          ] );

        return $pdf->download('manualReport.pdf');

    }

    //generate a report of caniddate 
  public function generateReport($id){

    $id = base64_decode($id);
    $business_id = Auth::user()->business_id;
    $report_id ="";
    $job = DB::table('job_items')->where(['candidate_id'=>$id])->first(); 
    $sla_id = $job->sla_id;
    //check report items created or not
    $report_count = DB::table('reports')->where(['candidate_id'=>$id])->count(); 
    if($report_count == 0){
      
      $job = DB::table('job_items')->where(['candidate_id'=>$id])->first(); 
    
      $data = 
        [
          'parent_id'     =>$business_id,
          'business_id'   =>$job->business_id,
          'candidate_id'  =>$id,
          'sla_id'        =>$job->sla_id,       
          'created_at'    =>date('Y-m-d H:i:s')
        ];
        
        $report_id = DB::table('reports')->insertGetId($data);
        
        // add service items
        $jaf_items = DB::table('jaf_form_data')->where(['candidate_id'=>$id])->get(); 

        foreach($jaf_items as $item){
          
          $data = 
            [
              'report_id'     =>$report_id,
              'service_id'    =>$item->service_id,
              'service_item_number'=>$item->check_item_number,
              'candidate_id'  =>$id,      
              'jaf_data'      =>$item->form_data,
              'jaf_id'        =>$item->id,
              'created_at'    =>date('Y-m-d H:i:s')
            ];
            
          $report_item_id = DB::table('report_items')->insertGetId($data);
        }
    }
    
      $report = DB::table('reports')->where(['candidate_id'=>$id])->first(); 
      $report_id = $report->id;
      $report_status = $report->status;

    $candidate = [];
    $report_items = [];
    $candidate =    Db::table('users as u')
                       ->select('u.id','u.business_id','u.first_name','u.last_name','u.name','u.email','u.phone','r.created_at')  
                       ->leftjoin('reports as r','r.candidate_id','=','u.id')
                       ->where(['u.id'=>$id]) 
                       ->first(); 
    
    $report_items = Db::table('report_items as ri')
                       ->select('ri.*','s.name as service_name','s.id as service_id')  
                       ->join('services as s','s.id','=','ri.service_id')
                       ->where(['ri.report_id'=>$report_id]) 
                       ->get(); 

      //get JAF data - 
      $jaf = DB::table('jaf_form_data')->select('form_data_all')->where(['candidate_id'=>$id])->first(); 

      $status_list = DB::table('report_status_masters')->get();             

      return view('superadmin.customers.generate-report', compact('candidate','report_items','jaf','report_id','report_status','sla_id','status_list'));
  }
    //show payments of customers
    public function payments($id)
    {
        $customer_id = base64_decode($id);

        $item = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name','b.contact_person','b.address_line1','b.zipcode','b.city_name','b.state_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.id'=>$customer_id])
        ->first();

        $sla = DB::table('customer_sla as sla')
                ->select('sla.*')
                ->where(['sla.parent_id'=>$customer_id])
                ->get();

        return view('superadmin.customers.payments',compact('item','sla'));
    }

    // show the customer data
    public function show($id)
    {
        $customer_id = base64_decode($id);

        $item = DB::table('users as u')
        ->select('u.id','u.first_name','u.last_name','u.email','u.phone','b.company_name','b.contact_person','b.address_line1','b.zipcode','b.city_name','b.state_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.id'=>$customer_id])
        ->first();

        //candidates
        $candidates =DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$customer_id])
        ->whereNotIn('u.id',[$customer_id])
        ->get();
        
        
        // DB::table('users as u')
        // ->select('u.id','u.name','u.email','u.phone')
        // ->where(['user_type'=>'candidate','parent_id'=>$customer_id])
        // ->get();

        return view('superadmin.customers.show',compact('item','candidates'));
    } 

    //show  all Clients
    public function candidateShow($id)
    {
        $candidate_id = base64_decode($id);
       
        $item = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name','b.contact_person','b.address_line1','b.zipcode','b.city_name','b.state_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.id'=>$candidate_id])
        ->first();

        //candidates
        $candidates = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone')
        ->where(['user_type'=>'candidate','business_id'=>$candidate_id])
        ->get();

        
        $customers = DB::table('users as u')
        ->select('u.id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.business_id'=>$candidate_id])
        ->get();
        $query = DB::table('users as u')
              ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
              ->join('job_items as j','j.candidate_id','=','u.id')        
              ->where(['u.user_type'=>'client','u.parent_id'=>$candidate_id]);

        // dd($query);
        $services = DB::table('services')->get();
        return view('superadmin.customers.candidate',compact('item','candidates','customers' ,'services'));
    }
 
    /**
     * Get the candidates.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCandidatesList(Request $request)
    {
        // dd($request);
        $business_id = $request->input('customer_id');
        // dd($business_id);
        $candidates = DB::table('users')
                ->select('id','first_name','middle_name','last_name','phone')
                ->where(['business_id'=>$business_id,'user_type'=>'candidate'])
                ->get();
                // dd($candidates);
        return response()->json([
            'success'   =>true,
            'data'      =>$candidates 
        ]);

    } 
    public function getstate(Request $request)
    {
       $country_id = $request->country_id; 

       $state = DB::table('states')->where('country_id',$country_id)->get();

       return response()->json($state);
     
    }

    public function getcity(Request $request)
    {
       $state_id = $request->state_id; 

       $city = DB::table('cities')->where('state_id',$state_id)->get();

       return response()->json($city);
     
    }




}
