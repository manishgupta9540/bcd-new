<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class JafController extends Controller
{

    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    /**
     * Show the general.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function jaf_form()
    {
        $company= DB::table('users')->select('company_logo')->where(['business_id'=>Auth::user()->business_id])->first();

        return view('admin.jaf.form', compact('company'));
    }

    public function jaf_store(Request $request)
    {
        
        $business_id = Auth::user()->business_id;

        //      $this->validate($request, 
        //   [
        //     'college_name[]'               => 'required',
        //     'affilated_university[]'       => 'required',
        //     'course_attended[]'            => 'required',
        //     'percentage[]'                 => 'required',
        //     'year_of_enrolment[]'          => 'required',
        //     'roll_no[]'                    => 'required',
        //     'year_of_passing[]'            => 'required'
         
        //  ],
        //  [
        //     'college_name[].required'            => 'Name and Address is Required',
        //     'affilated_university[].required'    => 'Name and Address is Required',
        //     'course_attended[].required'         => 'Course Attended is Required',
        //     'percentage[].required'              => 'Percentage is Required',
        //     'year_of_enrolment[].required'       => 'Enrolment Year is Required',
        //     'roll_no[].required'                 => 'Roll No is Required',
        //     'year_of_passing[].required'         => 'Passing year is Required'
          
        // ]

        //);


        //print_r($_POST);die;

        foreach ($request as $key=>$value) 
        {

          print_r($request->qualification_type);die;

           $data = 

         [
            'business_id'            =>$business_id,
            'user_id'                =>'1',
            'qualification_type'     =>$value->qualification_type,
            'college_name'           =>$value->college_name,
            'affilated_university'   =>$value->affilated_university,
            'course_attended'        =>$value->course_attended,
            'percentage'             =>$value->percentage,
            'year_of_enrolment'      =>$value->year_of_enrolment,
            'roll_no'                =>$value->roll_no,
            'year_of_passing'        =>$value->year_of_passing,
            'created_at'             =>date('Y-m-d H:i:s')
        ];
           
          DB::table('jaf_educational_details')->insert($data);
        }

      

      // DB::table('jaf_educational_details')->insert($data);

    

     //      $this->validate($request, 
     //      [
     //        'r_full_address'    => 'required',
     //        'r_city_name'       => 'required',
     //        'r_state_name'      => 'required',
     //        'r_zipcode'         => 'required',
     //        'r_address_from'    => 'required',
     //        'r_address_to'      => 'required',
     //        'r_nature_location' => 'required',
     //        'r_phone'           => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
     //        'c_full_address'    => 'required',
     //        'c_city_name'       => 'required',
     //        'c_state_name'      => 'required',
     //        'c_zipcode'         => 'required',
     //        'c_address_from'    => 'required',
     //        'c_address_to'      => 'required',
     //        'c_nature_location' => 'required',
     //        'c_phone'           => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10'
     //     ],
     //     [
     //        'r_full_address.required'    => 'Address is Required',
     //        'r_city_name.required'       => 'City is Required',
     //        'r_state_name.required'      => 'State is Required',
     //        'r_zipcode.required'         => 'Zipcode is Required',
     //        'r_address_from.required'    => 'Duration of stay is Required',
     //        'r_address_to.required'      => 'Duration of stay is Required',
     //        'r_nature_location.required' => 'Location is Required',
     //         'r_phone.required'          => 'Number is Required', 
     //        'r_phone.min'                => 'Number Should be Minimum of 10 Digits', 
     //        'r_phone.regex'              => 'Please enter number',
     //        'c_full_address.required'    => 'Your Address is Required',
     //        'c_city_name.required'       => 'City is Required',
     //        'c_state_name.required'      => 'State is Required',
     //        'c_zipcode.required'         => 'Zipcode is Required',
     //        'c_address_from.required'    => 'Duration of stay is Required',
     //        'c_address_to.required'      => 'Duration of stay is Required',
     //        'c_nature_location.required' => 'Location is Required',
     //        'c_phone.required'           => 'Number is Required', 
     //        'c_phone.min'                => 'Number Should be Minimum of 10 Digits', 
     //        'c_phone.regex'              => 'Please enter number'
     //    ]

     //   );

     //   $r_address = 

     //   [
     //        'business_id'      =>$business_id,
     //        'candidate_id'     =>'1',
     //        'address_type'     =>'residential_address',
     //        'full_address'     =>$request->input('r_full_address'),
     //        'city_name'        =>$request->input('r_city_name'),
     //        'state_name'       =>$request->input('r_state_name'),
     //        'zipcode'          =>$request->input('r_zipcode'),
     //        'address_from'     =>$request->input('r_address_from'),
     //        'address_to'       =>$request->input('r_address_to'),
     //        'nature_location'  =>$request->input('r_nature_location'),
     //        'phone'            =>$request->input('r_phone'),
     //        'created_by'       =>Auth::user()->id,
     //        'created_at'       =>date('Y-m-d H:i:s')
     //  ];



     // DB::table('jaf_residential_address')->insert($r_address);

     //  $c_address = 

     //   [
     //        'business_id'      =>$business_id,
     //        'candidate_id'     =>'1',
     //        'address_type'     =>'current_address',
     //        'full_address'     =>$request->input('c_full_address'),
     //        'city_name'        =>$request->input('c_city_name'),
     //        'state_name'       =>$request->input('c_state_name'),
     //        'zipcode'          =>$request->input('c_zipcode'),
     //        'address_from'     =>$request->input('c_address_from'),
     //        'address_to'       =>$request->input('c_address_to'),
     //        'nature_location'  =>$request->input('c_nature_location'),
     //        'phone'            =>$request->input('c_phone'),
     //        'created_by'       =>Auth::user()->id,
     //        'created_at'       =>date('Y-m-d H:i:s')
     //  ];

     //  DB::table('jaf_residential_address')->insert($c_address);

      //   $this->validate($request, [
      //       'first_name'  => 'required',
      //       'last_name'   => 'required',
      //       'dob'         => 'required',
      //       'birth_place' => 'required',
      //       'sex'         => 'required',
      //       'nationality' => 'required',
      //       'father_name' => 'required',
      //       'office_phone'=> 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
      //       'home_phone'  => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
      //       'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
      //    ]);


      // $data = 
      // [
      //       'business_id'      =>$business_id,
      //       'parent_id'        =>$business_id,
      //       'user_type'        =>'candidate',
      //       'name'             =>$request->input('first_name').' '.$request->input('last_name'),
      //       'first_name'       =>$request->input('first_name'),
      //       'middle_name'      =>$request->input('middle_name'),
      //       'last_name'        =>$request->input('last_name'),
      //       'phone'            =>$request->input('phone'),
      //       'created_by'       =>Auth::user()->id,
      //       'created_at'       =>date('Y-m-d H:i:s')
      // ];
      
      //   $user_id = DB::table('users')->insertGetId($data);


      //  $data = 
      // [
      //       'business_id'      =>$business_id,
      //       'user_id'          =>$user_id,
            
      //       'first_name'       =>$request->input('first_name'),
      //       'middle_name'      =>$request->input('middle_name'),
      //       'last_name'        =>$request->input('last_name'),
      //       'dob'              =>$request->input('dob'),
      //       'birth_place'      =>$request->input('birth_place'),
      //       'sex'              =>$request->input('sex'),
      //       'father_name'      =>$request->input('father_name'),
      //       'office_phone'     =>$request->input('office_phone'),
      //       'passport_no'      =>$request->input('passport_no'),
      //       'home_phone'       =>$request->input('home_phone'),
      //       'nationality'      =>$request->input('nationality'),
      //       'phone'            =>$request->input('phone'),
      //       'created_at'       =>date('Y-m-d H:i:s')
      // ];
        
      //      DB::table('jaf_personal_details')->insert($data);

     return redirect()
            ->route('/candidates')
            ->with('success', 'Candidate created successfully.');


    }

    // check aadhar
    public function checkAadhar(Request $request)
    {        
        $business_id = Auth::user()->business_id;

        $jaf_aadhaar = DB::table('jaf_form_data')->select('id','business_id','form_data','service_id')->where(['is_api_checked'=>'0','service_id'=>'2'])->get();
        // dd($jaf_aadhaar);
        $i = 0;
        if( count($jaf_aadhaar) > 0 ) {

          foreach( $jaf_aadhaar as $item ) {
            $aaddhaar_number = "";
            $business_id = $item->business_id; 
            $jaf_array = json_decode($item->form_data, true);
            // print_r($jaf_array);
            foreach($jaf_array as $input){
                if(array_key_exists('Aadhar Number',$input)){
                  $aaddhaar_number = $input['Aadhar Number'];
                }
            }
            
            //check first into master table
            $master_data = DB::table('aadhar_check_masters')->select('*')->where(['aadhar_number'=>$aaddhaar_number])->first();
            
            if($master_data !=null){
              //update case
              DB::table('jaf_form_data')->where(['id'=>$item->id])->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s')]); 
                
            }
            else{
                //check from live API
                $api_check_status = false;
                // Setup request to send json via POST
                $data = array(
                    'id_number'    => $aaddhaar_number,
                );
                $payload = json_encode($data);
                $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/aadhaar-validation/aadhaar-validation";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                curl_setopt ( $ch, CURLOPT_POST, 1 );
                $authorization = "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MDcxNzI1MDIsIm5iZiI6MTYwNzE3MjUwMiwianRpIjoiZTA5YTc5MmEtMGQ5ZC00N2RjLTk1MTAtMzg4M2E3ODYxZDczIiwiZXhwIjoxOTIyNTMyNTAyLCJpZGVudGl0eSI6ImRldi50YWd3b3JsZEBhYWRoYWFyYXBpLmlvIiwiZnJlc2giOmZhbHNlLCJ0eXBlIjoiYWNjZXNzIiwidXNlcl9jbGFpbXMiOnsic2NvcGVzIjpbInJlYWQiXX19.0Ufgl7uOeTG7QVLvRR4VkRZMT06GsiGiK44jFa9-gdw"; // Prepare the authorisation token
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
                curl_setopt($ch, CURLOPT_URL, $apiURL);
                // Attach encoded JSON string to the POST fields
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                $resp = curl_exec ( $ch );
                curl_close ( $ch );
                
                $array_data =  json_decode($resp,true);

                if($array_data['success'])
                {
                    $master_data ="";
                    //check if ID number is new then insert into DB
                    $checkIDInDB= DB::table('aadhar_check_masters')->where(['aadhar_number'=>$aaddhaar_number])->count();
                    if($checkIDInDB ==0)
                    {
                        $gender = 'Male';
                        if($array_data['data']['gender'] == 'F'){
                            $gender = 'Female';
                        }
                        $data = ['aadhar_number'    =>$array_data['data']['aadhaar_number'],
                                'age_range'         =>$array_data['data']['age_range'],
                                'gender'            =>$gender,
                                'state'             =>$array_data['data']['state'],
                                'last_digit'        =>$array_data['data']['last_digits'],
                                'is_api_verified'       =>'1',
                                'is_aadhar_exist'   =>'1',
                                'created_at'        =>date('Y-m-d H:i:s')
                                ];
                        DB::table('aadhar_check_masters')->insert($data);
                                
                        //insert into business table
                        $business_data = [
                                'business_id'       =>$business_id,
                                'aadhar_number'     =>$array_data['data']['aadhaar_number'],
                                'age_range'         =>$array_data['data']['age_range'],
                                'gender'            =>$gender,
                                'state'             =>$array_data['data']['state'],
                                'last_digit'        =>$array_data['data']['last_digits'],
                                'is_api_verified'       =>'1',
                                'is_aadhar_exist'   =>'1',
                                'created_at'        =>date('Y-m-d H:i:s')
                                ]; 
                        DB::table('aadhar_checks')->insert($business_data);
                        
                        $master_data = DB::table('aadhar_check_masters')->select('*')->where(['aadhar_number'=>$aaddhaar_number])->first();
                        // update the status
                        DB::table('jaf_form_data')->where(['id'=>$item->id])->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s')]); 
                    }
                
                }else{
                    //update insuff
                    DB::table('jaf_form_data')->where(['id'=>$item->id])->update(['is_api_checked'=>'1','is_api_verified'=>'0','verification_status'=>'Verification failed','verified_at'=>date('Y-m-d H:i:s')]); 
                    
                }
              }
            $i++;
            }
            //loop
            echo $i;
        }
    }

    // check pan
    public function checkPan(Request $request)
    {        
      $jaf_pan = DB::table('jaf_form_data')->select('id','business_id','form_data','service_id')->where(['is_api_checked'=>'0','service_id'=>'3'])->get();

      if( count($jaf_pan) > 0 ) {
        
        foreach( $jaf_pan as $item ) {
            $pan_number = "";
            $business_id = $item->business_id; 
            $jaf_array = json_decode($item->form_data, true);
            // print_r($jaf_array);
            foreach($jaf_array as $input){
                if(array_key_exists('PAN Number',$input)){
                  $pan_number = $input['PAN Number'];
                }
            }

            //check first into master table
            $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$pan_number])->first();
            
            if($master_data !=null){
                // update the status
                DB::table('jaf_form_data')->where(['id'=>$item->id])->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s')]);

            }
            else{
                //check from live API
                $api_check_status = false;
                // Setup request to send json via POST
                $data = array(
                    'id_number'    => $pan_number,
                );
                $payload = json_encode($data);
                $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/pan/pan";

                $ch = curl_init();                
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                curl_setopt ( $ch, CURLOPT_POST, 1 );
                $authorization = "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MDcxNzI1MDIsIm5iZiI6MTYwNzE3MjUwMiwianRpIjoiZTA5YTc5MmEtMGQ5ZC00N2RjLTk1MTAtMzg4M2E3ODYxZDczIiwiZXhwIjoxOTIyNTMyNTAyLCJpZGVudGl0eSI6ImRldi50YWd3b3JsZEBhYWRoYWFyYXBpLmlvIiwiZnJlc2giOmZhbHNlLCJ0eXBlIjoiYWNjZXNzIiwidXNlcl9jbGFpbXMiOnsic2NvcGVzIjpbInJlYWQiXX19.0Ufgl7uOeTG7QVLvRR4VkRZMT06GsiGiK44jFa9-gdw"; // Prepare the authorisation token
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
                curl_setopt($ch, CURLOPT_URL, $apiURL);
                // Attach encoded JSON string to the POST fields
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

                $resp = curl_exec ( $ch );
                curl_close ( $ch );
                
                $array_data =  json_decode($resp,true);
                // print_r($array_data); die;
                if($array_data['success'])
                {
                    //check if ID number is new then insert into DB
                    $checkIDInDB= DB::table('pan_check_masters')->where(['pan_number'=>$pan_number])->count();
                    if($checkIDInDB ==0)
                    {
                        $data = [
                                'category'=>$array_data['data']['category'],
                                'pan_number'=>$array_data['data']['pan_number'],
                                'full_name'=>$array_data['data']['full_name'],
                                'is_api_verified'=>'1',
                                'is_pan_exist'=>'1',
                                'created_at'=>date('Y-m-d H:i:s')
                                ];
                        DB::table('pan_check_masters')->insert($data);
                        
                        $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$pan_number])->first();
                    }

                    // update the status
                    DB::table('jaf_form_data')->where(['id'=>$item->id])->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s')]); 

                }else{
                    //update insuff
                    DB::table('jaf_form_data')->where(['id'=>$item->id])->update(['is_api_checked'=>'1','is_api_verified'=>'0','verification_status'=>'failed','verified_at'=>date('Y-m-d H:i:s')]); 
                    
                }
                
            }

            }

        }

    }


    // check id - Voter ID
    public function checkVoterID(Request $request)
    {      
        $jaf_voterid = DB::table('jaf_form_data')->select('id','business_id','form_data','service_id')->where(['is_api_checked'=>'0','service_id'=>'4'])->get();

      if( count($jaf_voterid) > 0 ) {
        
        foreach( $jaf_voterid as $item ) {
            $voterid_number = "";
            $business_id = $item->business_id; 
            $jaf_array = json_decode($item->form_data, true);
            // print_r($jaf_array);
            foreach($jaf_array as $input){
                if(array_key_exists('Voter ID Number',$input)){
                  $voterid_number = $input['Voter ID Number'];
                }
            }
        
            //check first into master table
            $master_data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$voterid_number])->first();
            if($master_data !=null){
                $data = $master_data;
                // update the status
                DB::table('jaf_form_data')->where(['id'=>$item->id])->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s')]); 
                return response()->json([
                    'fail'      =>false,
                    'data'      =>$master_data 
                ]);
            }
            else{
                //check from live API
                // Setup request to send json via POST
                $data = array(
                    'id_number'    => $voterid_number,
                );
                $payload = json_encode($data);
                $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/voter-id/voter-id";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                curl_setopt ( $ch, CURLOPT_POST, 1 );
                $authorization = "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MDcxNzI1MDIsIm5iZiI6MTYwNzE3MjUwMiwianRpIjoiZTA5YTc5MmEtMGQ5ZC00N2RjLTk1MTAtMzg4M2E3ODYxZDczIiwiZXhwIjoxOTIyNTMyNTAyLCJpZGVudGl0eSI6ImRldi50YWd3b3JsZEBhYWRoYWFyYXBpLmlvIiwiZnJlc2giOmZhbHNlLCJ0eXBlIjoiYWNjZXNzIiwidXNlcl9jbGFpbXMiOnsic2NvcGVzIjpbInJlYWQiXX19.0Ufgl7uOeTG7QVLvRR4VkRZMT06GsiGiK44jFa9-gdw"; // Prepare the authorisation token
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
                curl_setopt($ch, CURLOPT_URL, $apiURL);
                // Attach encoded JSON string to the POST fields
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                $resp = curl_exec ( $ch );
                curl_close ( $ch );
                $array_data =  json_decode($resp,true);
                // print_r($array_data); die;

                if($array_data['success'])
                {
                    //check if ID number is new then insert into DB
                    $checkIDInDB= DB::table('voter_id_check_masters')->where(['voter_id_number'=>$voterid_number])->count();
                    if($checkIDInDB ==0)
                    {
                        $gender = 'Male';
                        if($array_data['data']['gender'] == 'F'){
                            $gender = 'Female';
                        }
                        //
                        $relation_type = NULL;
                        if($array_data['data']['relation_type'] == 'M'){
                            $relation_type = 'Mother';
                        }
                        if($array_data['data']['relation_type'] == 'F'){
                            $relation_type = 'Father';
                        }
                        if($array_data['data']['relation_type'] == 'W'){
                            $relation_type = 'Wife';
                        }
                        if($array_data['data']['relation_type'] == 'H'){
                            $relation_type = 'Husband';
                        }

                        $data = [
                                'api_client_id'     =>$array_data['data']['client_id'],
                                'relation_type'     =>$relation_type,
                                'voter_id_number'   =>$array_data['data']['epic_no'],
                                'relation_name'     =>$array_data['data']['relation_name'],
                                'full_name'         =>$array_data['data']['name'],
                                'gender'            =>$gender,
                                'age'               =>$array_data['data']['age'],
                                'dob'               =>$array_data['data']['dob'],
                                'house_no'          =>$array_data['data']['house_no'],
                                'area'              =>$array_data['data']['area'],
                                'state'             =>$array_data['data']['state'],
                                'is_api_verified'       =>'1',
                                'is_voter_id_exist' =>'1',
                                'created_at'        =>date('Y-m-d H:i:s')
                                ];
                        DB::table('voter_id_check_masters')->insert($data);
                        
                        $master_data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$voterid_number])->first();
                    }

                    // update the status
                    DB::table('jaf_form_data')->where(['id'=>$item->id])->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s')]); 
                    

                }else{

                    //update insuff
                    DB::table('jaf_form_data')->where(['id'=>$item->id])->update(['is_api_checked'=>'1','is_api_verified'=>'0','verification_status'=>'failed','verified_at'=>date('Y-m-d H:i:s')]); 
                    
                }
                
            }
        }

        }
    
    }

    // check id - RC
    public function checkRC(Request $request)
    {        
    
        $jaf_rc = DB::table('jaf_form_data')->select('id','business_id','form_data','service_id')->where(['is_api_checked'=>'0','service_id'=>'7'])->get();

        if( count($jaf_rc) > 0 ) {
        
        foreach( $jaf_rc as $item ) {
            $rc_number = "";
            $business_id = $item->business_id; 
            $jaf_array = json_decode($item->form_data, true);
            // print_r($jaf_array);
            foreach($jaf_array as $input){
                if(array_key_exists('RC Number',$input)){
                  $rc_number = $input['RC Number'];
                }
            }
        
            //check first into master table
            $master_data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$rc_number])->first();
            if($master_data !=null){
                // update the status
                DB::table('jaf_form_data')->where(['id'=>$item->id])->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s')]); 
                $data = $master_data;
                
            }
            else{
                //check from live API
                // Setup request to send json via POST
                $data = array(
                    'id_number'    => $rc_number,
                );
                $payload = json_encode($data);
                $apiURL = "https://sandbox.aadhaarkyc.io/api/v1/rc/rc";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                curl_setopt ( $ch, CURLOPT_POST, 1 );
                $authorization = "Authorization: Bearer ".env('SUREPASS_SANDBOX_TOKEN'); // Prepare the authorisation token
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
                curl_setopt($ch, CURLOPT_URL, $apiURL);
                // Attach encoded JSON string to the POST fields
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                $resp = curl_exec ( $ch );
                curl_close ( $ch );
                $array_data =  json_decode($resp,true);
                // print_r($array_data); die;

                if($array_data['success'])
                {
                    //check if ID number is new then insert into DB
                    $checkIDInDB= DB::table('rc_check_masters')->where(['rc_number'=>$rc_number])->count();
                    if($checkIDInDB ==0)
                    {
                    
                        $data = [
                                'api_client_id'     =>$array_data['data']['client_id'],
                                'rc_number'         =>$array_data['data']['rc_number'],
                                'registration_date' =>$array_data['data']['registration_date'],
                                'owner_name'        =>$array_data['data']['owner_name'],
                                'present_address'   =>$array_data['data']['present_address'],
                                'permanent_address'    =>$array_data['data']['permanent_address'],
                                'mobile_number'        =>$array_data['data']['mobile_number'],
                                'vehicle_category'     =>$array_data['data']['vehicle_category'],
                                'vehicle_chasis_number' =>$array_data['data']['vehicle_chasi_number'],
                                'vehicle_engine_number' =>$array_data['data']['vehicle_engine_number'],
                                'maker_description'     =>$array_data['data']['maker_description'],
                                'maker_model'           =>$array_data['data']['maker_model'],
                                'body_type'             =>$array_data['data']['body_type'],
                                'fuel_type'             =>$array_data['data']['fuel_type'],
                                'color'                 =>$array_data['data']['color'],
                                'norms_type'            =>$array_data['data']['norms_type'],
                                'fit_up_to'             =>$array_data['data']['fit_up_to'],
                                'financer'              =>$array_data['data']['financer'],
                                'insurance_company'     =>$array_data['data']['insurance_company'],
                                'insurance_policy_number'=>$array_data['data']['insurance_policy_number'],
                                'insurance_upto'         =>$array_data['data']['insurance_upto'],
                                'manufacturing_date'     =>$array_data['data']['manufacturing_date'],
                                'registered_at'          =>$array_data['data']['registered_at'],
                                'latest_by'              =>$array_data['data']['latest_by'],
                                'less_info'              =>$array_data['data']['less_info'],
                                'tax_upto'               =>$array_data['data']['tax_upto'],
                                'cubic_capacity'         =>$array_data['data']['cubic_capacity'],
                                'vehicle_gross_weight'   =>$array_data['data']['vehicle_gross_weight'],
                                'no_cylinders'           =>$array_data['data']['no_cylinders'],
                                'seat_capacity'          =>$array_data['data']['seat_capacity'],
                                'sleeper_capacity'       =>$array_data['data']['sleeper_capacity'],
                                'standing_capacity'      =>$array_data['data']['standing_capacity'],
                                'wheelbase'              =>$array_data['data']['wheelbase'],
                                'unladen_weight'         =>$array_data['data']['unladen_weight'],
                                'vehicle_category_description'         =>$array_data['data']['vehicle_category_description'],
                                'pucc_number'               =>$array_data['data']['pucc_number'],
                                'pucc_upto'                 =>$array_data['data']['pucc_upto'],
                                'masked_name'           =>$array_data['data']['masked_name'],
                                'is_api_verified'           =>'1',
                                'is_rc_exist'           =>'1',
                                'created_at'            =>date('Y-m-d H:i:s')
                                ];

                        DB::table('rc_check_masters')->insert($data);
                        
                        $master_data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$rc_number])->first();
                    }

                    // update the status
                    DB::table('jaf_form_data')->where(['id'=>$item->id])->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s')]); 
                    

                }else{
                    //update insuff
                    DB::table('jaf_form_data')->where(['id'=>$item->id])->update(['is_api_checked'=>'1','is_api_verified'=>'0','verification_status'=>'failed','verified_at'=>date('Y-m-d H:i:s')]); 
                    
                }
                
            }
            }
        }

    }

    // check id - DL
    public function checkDL(Request $request)
    {        
    
        $jaf_dl = DB::table('jaf_form_data')->select('id','business_id','form_data','service_id')->where(['is_api_checked'=>'0','service_id'=>'9'])->get();

        if( count($jaf_dl) > 0 ) {
        
        foreach( $jaf_dl as $item ) {
            $dl_number = "";
            $business_id = $item->business_id; 
            $jaf_array = json_decode($item->form_data, true);
            // print_r($jaf_array);
            foreach($jaf_array as $input){
                if(array_key_exists('DL Number',$input)){
                  $dl_number = $input['DL Number'];
                }
            }
        
            $dl_number_input      = $dl_number;
            $dl_raw         = preg_replace('/[^A-Za-z0-9\ ]/', '', $dl_number_input);
            $final_dl_number   = str_replace(' ', '', $dl_raw);

            //check first into master table
            $master_data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$final_dl_number])->first();
            
            if($master_data !=null){

                // update the status
                DB::table('jaf_form_data')->where(['id'=>$item->id])->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s')]); 

            }
            else{
                //check from live API
                // Setup request to send json via POST
                $data = array(
                    'id_number'    => $dl_number,
                );
                $payload = json_encode($data);
                $apiURL = "https://sandbox.aadhaarkyc.io/api/v1/driving-license/driving-license";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                curl_setopt ( $ch, CURLOPT_POST, 1 );
                $authorization = "Authorization: Bearer ".env('SUREPASS_SANDBOX_TOKEN'); // Prepare the authorisation token
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
                curl_setopt($ch, CURLOPT_URL, $apiURL);
                // Attach encoded JSON string to the POST fields
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                $resp = curl_exec ( $ch );
                curl_close ( $ch );
                $array_data =  json_decode($resp,true);
                // print_r($array_data); die;

                if($array_data['success'])
                {
                    //check if ID number is new then insert into DB
                    $checkIDInDB= DB::table('dl_check_masters')->where(['dl_number'=>$final_dl_number])->count();
                    if($checkIDInDB ==0)
                    {
                        $gender = 'Male';
                        if($array_data['data']['gender'] == 'F'){
                            $gender = 'Female';
                        }

                        $dl_number      = $array_data['data']['license_number'];
                        $dl_raw         = preg_replace('/[^A-Za-z0-9\ ]/', '', $dl_number);
                        $final_number   = str_replace(' ', '', $dl_raw);

                        //
                        $data = [
                                'api_client_id'     =>$array_data['data']['client_id'],
                                'dl_number'         =>$final_number,
                                'name'              =>$array_data['data']['name'],
                                'permanent_address' =>$array_data['data']['permanent_address'],
                                'temporary_address' =>$array_data['data']['temporary_address'],
                                'permanent_zip'     =>$array_data['data']['permanent_zip'],
                                'temporary_zip'     =>$array_data['data']['temporary_zip'],
                                'state'             =>$array_data['data']['state'],
                                'citizenship'       =>$array_data['data']['citizenship'],
                                'ola_name'          =>$array_data['data']['ola_name'],
                                'ola_code'          =>$array_data['data']['ola_code'],
                                'gender'            =>$gender,
                                'father_or_husband_name' =>$array_data['data']['father_or_husband_name'],
                                'dob'               =>$array_data['data']['dob'],
                                'doe'               =>$array_data['data']['doe'],
                                'transport_doe'     =>$array_data['data']['transport_doe'],
                                'doi'               =>$array_data['data']['doi'],
                                'is_api_verified'       =>'1',
                                'is_rc_exist'       =>'1',
                                'created_at'        =>date('Y-m-d H:i:s')
                                ];
                            
                            DB::table('dl_check_masters')->insert($data);
                        
                        $master_data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$final_dl_number])->first();
                    }
                    // update the status
                    DB::table('jaf_form_data')->where(['id'=>$item->id])->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s')]); 
                    

                }else{
                    //update insuff
                    DB::table('jaf_form_data')->where(['id'=>$item->id])->update(['is_api_checked'=>'1','is_api_verified'=>'0','verification_status'=>'failed','verified_at'=>date('Y-m-d H:i:s')]); 
                    
                }
                
            }

        }

        }

    }

    // check id - Passport
    public function checkPassport(Request $request)
    {        
        if( $request->has('id_number') ) {
        
            $passport_file_no = $request->input('id_number');

            //check first into master table
            $master_data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$passport_file_no])->first();
            if($master_data !=null){
                // update the status
                DB::table('jaf_form_data')->where(['id'=>$item->id])->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s')]); 
                
            }
            else{
                //check from live API
                // Setup request to send json via POST
                $data = array(
                    'id_number' => $request->input('id_number'),
                    'dob'       => date('Y-m-d',strtotime($request->input('dob'))),
                );
                $payload = json_encode($data);
                $apiURL = "https://sandbox.aadhaarkyc.io/api/v1/passport/passport/passport-details";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                curl_setopt ($ch, CURLOPT_POST, 1);
                $authorization = "Authorization: Bearer ".env('SUREPASS_SANDBOX_TOKEN'); // Prepare the authorisation token
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
                curl_setopt($ch, CURLOPT_URL, $apiURL);
                // Attach encoded JSON string to the POST fields
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                $resp = curl_exec ( $ch );
                curl_close ( $ch );
                $array_data =  json_decode($resp,true);
                
                if($array_data['success'])
                {
                    //check if ID number is new then insert into DB
                    $checkIDInDB= DB::table('passport_check_masters')->where(['file_number'=>$passport_file_no])->count();
                    if($checkIDInDB ==0)
                    {
                        
                        $data = [
                                'api_client_id'     =>$array_data['data']['client_id'],
                                'passport_number'   =>$array_data['data']['passport_number'],
                                'full_name'         =>$array_data['data']['full_name'],
                                'file_number'       =>$array_data['data']['file_number'],
                                'date_of_application'=>$array_data['data']['date_of_application'],
                                'is_api_verified'       =>'1',
                                'is_passport_exist' =>'1',
                                'created_at'        =>date('Y-m-d H:i:s')
                                ];

                        DB::table('passport_check_masters')->insert($data);
                        
                        $master_data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$passport_file_no])->first();
                    }
                    // update the status
                    DB::table('jaf_form_data')->where(['id'=>$item->id])->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s')]); 
                    

                }else{
                    
                }
                
            }

        }else{
            
            

        }

    }



}
