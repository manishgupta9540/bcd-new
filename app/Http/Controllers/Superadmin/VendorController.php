<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use App\User;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    /**
    *@return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the general.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $business_id = Auth::user()->business_id; 
        $vendor = DB::table('vendors')
                  ->select('id','company_name','first_name','last_name','email','phone','status')
                  ->where(['business_id'=>$business_id])
                  ->get();

        return view('superadmin.vendors.index',compact('vendor'));
     }

     //
     public function create()
     {
         $countries      = DB::table('countries')->get();
         
         return view('superadmin.vendors.create',compact('countries'));

     }

     //
     public function save(Request $request)
     {
        $business_id = Auth::user()->business_id;

         $this->validate($request,[

            'first_name' =>'required',
            'last_name' => 'required',
            'email'     => 'required|email|unique:users',
            'phone'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'address'   => 'required',
            'pincode'   => 'required',
            'city'      => 'required',
            'state'     => 'required',
            'country'   => 'required',
            'company'   => 'required',
            'service'   => 'required',
            'password' => 'required|regex:#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#|same:confirm-password',
            'confirm-password' => 'required|regex:#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#|same:password'

         ]);
         $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('last_name')));
        $user_data = 
        [
            'user_type'     =>'vendor',
           
            'business_id'   =>$business_id,
            'first_name'    =>$request->input('first_name'),
            'last_name'     =>$request->input('last_name'),
            'name'          =>$name,
            'email'         =>$request->input('email'),
            'phone'         =>$request->input('phone'),
            'password'      =>Hash::make($request->input('password')),
            'created_by'    =>Auth::user()->id,
            'created_at'    =>date('Y-m-d H:i:s')
        ];
        
        $user_id = DB::table('users')->insertGetId($user_data);


        $vendor = 
        [
           
            'business_id'   =>$business_id,
            'user_id'       =>$user_id,
            'first_name'    =>$request->input('first_name'),
            'last_name'     =>$request->input('last_name'),
            'company_name'  =>$request->input('company'),
            'service'       =>$request->input('service'),
            'state'         =>$request->input('state'),
            'city'          =>$request->input('city'),
            'pincode'       =>$request->input('pincode'),
            'address'       =>$request->input('address'),
            'country_id'    =>$request->input('country'),
            'email'         =>$request->input('email'),
            'phone'         =>$request->input('phone'),
            'created_by'    =>Auth::user()->id,
            'created_at'    =>date('Y-m-d H:i:s')

        ];

       DB::table('vendors')->insertGetId($vendor);

      return redirect('/app/vendor')
             ->with('success', 'Vendor created successfully.');

     }
 
     public function edit($id)
     {

          $id = base64_decode($id);
          
          $countries      = DB::table('countries')->get();

          $vendor = DB::table('vendors')
                    ->select('*')
                    ->where('id',$id)
                    ->first();

         return view('superadmin.vendors.edit',compact('countries','vendor','id'));

     }

     public function update(Request $request)
     {
        
         $vendor_id = $request->input('vendor_id');

         $user_id   = $request->input('user_id');

         $this->validate($request, 
         [

            'first_name' =>'required',
            'last_name' => 'required',
            'email'     => 'required',
            'phone'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'address'   => 'required',
            'pincode'   => 'required',
            'city'      => 'required',
            'state'     => 'required',
            'country'   => 'required',
            'company'   => 'required',
            'service'   => 'required',

         ]);

        $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('last_name')));
        $user_data = 
        [
            
            'first_name'    =>$request->input('first_name'),
            'last_name'     =>$request->input('last_name'),
            'name'          =>$name,
            'email'         =>$request->input('email'),
            'status'        =>$request->input('status'),
            'phone'         =>$request->input('phone'),
            'updated_at'    =>date('Y-m-d H:i:s')
        ];

       DB::table('users')->where('id',$user_id)->update($user_data);   


       $vendor = 
        [
          
            'first_name'    =>$request->input('first_name'),
            'last_name'     =>$request->input('last_name'),
            'company_name'  =>$request->input('company'),
            'service'       =>$request->input('service'),
            'state'         =>$request->input('state'),
            'city'          =>$request->input('city'),
            'pincode'       =>$request->input('pincode'),
            'address'       =>$request->input('address'),
            'country_id'    =>$request->input('country'),
            'email'         =>$request->input('email'),
            'phone'         =>$request->input('phone'),
            'status'        =>$request->input('status'),           
            'updated_at'    =>date('Y-m-d H:i:s')

        ];

         DB::table('vendors')->where('id',$vendor_id)->update($vendor);  

         return redirect('/app/vendor')
             ->with('success', 'Vendor updated successfully.'); 

     }

}
