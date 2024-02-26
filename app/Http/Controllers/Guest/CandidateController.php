<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\User;
use Carbon\Carbon;

class CandidateController extends Controller
{
    //
    public function index(Request $request)
    {

        $business_id=Auth::user()->business_id;
        $query = DB::table('users as u')
            //   ->DISTINCT('u.id')
              ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'is_deleted'=>'0'])
              ->orderBy('u.id','desc');

        if(is_numeric($request->get('candidate_id'))){
            $query->where('u.id',$request->get('candidate_id'));
        }
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

        $items =    $query->paginate(15);

        $candidates = DB::table('users as u')
                        // ->DISTINCT('u.id')
                        ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'is_deleted'=>'0'])->get();
        if ($request->ajax())
            return view('guest.candidates.ajax', compact('items','candidates'));
        else
            return view('guest.candidates.index', compact('items','candidates'));   
        
    }

    public function create(Request $request)
    {
        return view('guest.candidates.create');
    }

    public function store(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $dob = NULL;       
       
        if($request->has('dob')){
          $dob = date('Y-m-d',strtotime($request->input('dob')));
        }
        $date_of_b=Carbon::parse($dob)->format('Y-m-d');
        $today=Carbon::now();
        $today_date=Carbon::now()->format('Y-m-d');
        $year=$today->diffInYears($date_of_b);
        
        $rules= [
            'first_name'  => 'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'middle_name' =>  'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'last_name'  => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'father_name' => 'required|regex:/^[a-zA-Z ]+$/u|min:2|max:255',
            'dob'         => 'required|date',
            'email'       => 'nullable|email',
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'aadhar'      => 'nullable|regex:/^((?!([0-1]))[0-9]{12})$/',
            'gender'      => 'required'
         ];
        
         $customMessages = [
          'aadhar.regex' => 'Please Enter a 12-digit valid Aadhar Number.',
        ];

         $validator = Validator::make($request->all(), $rules,$customMessages);
          
         if ($validator->fails()){
             return response()->json([
                 'success' => false,
                 'errors' => $validator->errors()
             ]);
         }

         if($year<18 || ($date_of_b >= $today_date))
         {
            return response()->json([
                'success' => false,
                'custom'  => 'yes',
                'errors' =>['dob'=>'Age Must be 18 or older !']
            ]);
         }

         $phone = preg_replace('/\D/', '', $request->input('phone'));

         $user_already_exist = DB::table('users')
         ->where(['first_name'=>$request->input('first_name'), 'dob'=>date('Y-m-d', strtotime($request->input('dob'))), 'father_name'=>$request->input('father_name') ])
         ->count();

         if($user_already_exist > 0){
             return response()->json([
               'success' => false,
               'custom'=>'yes',
               'errors' => ['user'=>'It Seems like the user is already exist!']
             ]);
         }

         //create user 
        $data = 
        [
            'business_id'   =>$business_id,
            'user_type'     =>'candidate',
            'client_emp_code'=>$request->input('client_emp_code'),
            'entity_code'   =>$request->input('entity_code'),
            'parent_id'     =>Auth::user()->parent_id,
            'name'          =>$request->input('first_name').' '.$request->input('middle_name').' '.$request->input('last_name'),
            'first_name'    =>$request->input('first_name'),
            'middle_name'   =>$request->input('middle_name'),
            'last_name'     =>$request->input('last_name'),
            'father_name'   =>$request->input('father_name'),
            'aadhar_number' =>$request->input('aadhar'),
            'dob'           =>$dob,
            'gender'        =>$request->input('gender'),
            'email'         =>$request->input('email'),
            'phone'         =>$phone,
            'created_by'    =>Auth::user()->id,
            'created_at'    =>date('Y-m-d H:i:s') 
        ];
      
        $user_id = DB::table('users')->insertGetId($data);

        return response()->json([
            'success' =>true,
            'candidate_id' => base64_encode($user_id),
            'custom'  =>'yes',
            'errors'  =>[]
        ]);
    }

    public function edit(Request $request)
    {
        $candidate_id=base64_decode($request->id);

        $user = DB::table('users as u')
        ->select('u.*')   
        ->where(['u.id' =>$candidate_id])        
        ->first();

        return view('guest.candidates.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $userID =  base64_decode($request->get('user_id'));
        $business_id = Auth::user()->business_id;
        $dob = NULL;       
       
        if($request->has('dob')){
          $dob = date('Y-m-d',strtotime($request->input('dob')));
        }
        $date_of_b=Carbon::parse($dob)->format('Y-m-d');
        $today=Carbon::now();
        $today_date=Carbon::now()->format('Y-m-d');
        $year=$today->diffInYears($date_of_b);
        
        $rules= [
            'first_name'  => 'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'middle_name' =>  'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'last_name'  => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'father_name' => 'required|regex:/^[a-zA-Z ]+$/u|min:2|max:255',
            'dob'         => 'required|date',
            'email'       => 'nullable|email',
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'aadhar'      => 'nullable|regex:/^((?!([0-1]))[0-9]{12})$/',
            'gender'      => 'required'
         ];
        
         $customMessages = [
          'aadhar.regex' => 'Please Enter a 12-digit valid Aadhar Number.',
        ];

         $validator = Validator::make($request->all(), $rules,$customMessages);
          
         if ($validator->fails()){
             return response()->json([
                 'success' => false,
                 'errors' => $validator->errors()
             ]);
         }

         if($year<18 || ($date_of_b >= $today_date))
         {
            return response()->json([
                'success' => false,
                'custom'  => 'yes',
                'errors' =>['dob'=>'Age Must be 18 or older !']
            ]);
         }

         $phone = preg_replace('/\D/', '', $request->input('phone'));

         $user_already_exist = DB::table('users')
         ->where(['first_name'=>$request->input('first_name'), 'dob'=>date('Y-m-d', strtotime($request->input('dob'))), 'father_name'=>$request->input('father_name') ])
         ->whereNotIn('id',[$userID])
         ->count();

         if($user_already_exist > 0){
             return response()->json([
               'success' => false,
               'custom'=>'yes',
               'errors' => ['user'=>'It Seems like the user is already exist!']
             ]);
         }

         $data = 
            [
                'business_id'   =>$business_id,
                'user_type'     =>'candidate',
                'client_emp_code'=>$request->input('client_emp_code'),
                'entity_code'   =>$request->input('entity_code'),
                'parent_id'     =>Auth::user()->parent_id,
                'name'          =>$request->input('first_name').' '.$request->input('middle_name').' '.$request->input('last_name'),
                'first_name'    =>$request->input('first_name'),
                'middle_name'   =>$request->input('middle_name'),
                'last_name'     =>$request->input('last_name'),
                'father_name'   =>$request->input('father_name'),
                'aadhar_number' =>$request->input('aadhar'),
                'dob'           =>$dob,
                'gender'        =>$request->input('gender'),
                'email'         =>$request->input('email'),
                'phone'         =>$phone,
                'updated_by'    =>Auth::user()->id,
                'updated_at'    =>date('Y-m-d H:i:s') 
            ];

            DB::table('users')->where('id',$userID)->update($data);

            return response()->json([
                'success' =>true,
                'candidate_id' => base64_encode($userID),
                'custom'  =>'yes',
                'errors'  =>[]
            ]);

    }
}
