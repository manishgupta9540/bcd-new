<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;

class ContactController extends Controller
{
   
    //show the list
    public function index()
    {
        $business_id = Auth::user()->business_id;
        //
        $items = DB::table('contacts')->where(['business_id'=>$business_id])->orderBy('id','desc')->get();
        
        $users = DB::table('users')->select('id','name','first_name','last_name')->where(['business_id'=>$business_id])->whereIn('user_type',['user','customer'])->get();

        return view('superadmin.contacts.index',compact('items','users'));
    }

    //show the list
    public function myContacts()
    {
        $business_id = Auth::user()->business_id;
        $items = DB::table('contacts')->where(['business_id'=>$business_id,'contact_owner'=>Auth::user()->id])->orderBy('id','desc')->get();

        return view('superadmin.contacts.my-contacts',compact('items'));
    }

    //create 
    public function create()
    {
        return view('superadmin.contacts.create');
    }

    //store 
    public function store(Request $request)
    {
        $business_id = Auth::user()->business_id;
        // Form validation
       
         $rules = [
            'first_name'    => 'required',
            'company'       => 'required',
            'type'          => 'required',
            'status'        => 'required',
            'email'         => 'required|email',
            'phone'         => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
          ];

        $validator = Validator::make($request->all(), $rules);
          
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
  
        $data = 
        [
            'business_id'=>$business_id,
            'name'=>$request->input('first_name').' '.$request->input('last_name'),
            'first_name'=>$request->input('first_name'),
            'last_name'=>$request->input('last_name'),
            'email'=>$request->input('email'),
            'phone'=>$request->input('phone'),
            'associated_company'=>$request->input('company'),
            'lead_status'=>$request->input('lead_status'),
            'type'=>$request->input('type'),
            'contact_owner'=>$request->input('contact_owner'),
            'created_by'=> Auth::user()->id,
            'created_at'=> date('Y-m-d H:i:s')
        ];
        
        $id = DB::table('contacts')->insertGetId($data);
        
        //get last record 
        $contact = DB::table('contacts')->where(['id'=>$id])->first();
        $is_owner = FALSE;
        //join if contact owner is not empty
        if($request->input('contact_owner') !=""){
            $contact = DB::table('contacts as c')
                        ->select('c.*','u.name as contact_owner','u.first_name as f_name','u.last_name as l_name')
                        ->join('users as u','c.contact_owner','=','u.id')
                        ->where(['c.id'=>$id])
                        ->first();
            $is_owner = TRUE;
        }
        

        return response()->json([
            'success' => true,
            'data'    => $contact,
            'is_owner'=> $is_owner
        ]);


    }

}

