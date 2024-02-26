<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;

class JobController extends Controller
{
   
    //jobs 
    public function index()
    {

      $jobs = DB::table('jobs as j')
              ->select('j.id','j.title','j.total_candidates','j.created_at','j.created_by','j.status','s.name as verification_type','u.name as customer','u.id as customer_id','j.business_id')
              ->join('users as u','u.id','=','j.business_id')
              ->join('services as s','s.id','=','j.sla_id')
              ->where(['j.parent_id'=>Auth::user()->business_id])
              ->get();

      return view('superadmin.jobs.index', compact('jobs'));
    }

    //create job show the form
    public function createjob()
    {
      $business_id = Auth::user()->business_id;
      $customers = DB::table('users as u')
      ->select('u.id','u.name','u.email','u.phone','b.company_name')
      ->join('user_businesses as b','b.business_id','=','u.id')
      ->where(['user_type'=>'customer','parent_id'=>$business_id])
      ->get();

      return view('superadmin.jobs.create',compact('customers'));
      
    }

    //create job by import show the form
    public function importExcel()
    {
      $business_id = Auth::user()->business_id;
      $customers = DB::table('users as u')
      ->select('u.id','u.name','u.email','u.phone','b.company_name')
      ->join('user_businesses as b','b.business_id','=','u.id')
      ->where(['user_type'=>'customer','parent_id'=>$business_id])
      ->get();

    	return view('superadmin.jobs.import-excel',compact('customers'));
    }

    // store excel data 
    public function storeExcelData(Request $request)
    {
            // Form validation
            $this->validate($request, [
            'customer'=>'required',
            'job_name' => 'required',
            'verification_type' => 'required',
            'csv_file'     => 'required|mimes:csv,txt',
            ]);
            
            $tmpName = $_FILES['csv_file']['tmp_name'];
            $data = $this->csvToArray($tmpName, ',');
            //debug
            //die();

            $job_data = 
                [
                  'business_id' => $request->input('customer'),
                  'parent_id' => Auth::user()->business_id,
                  'title'     => $request->input('job_name'),
                  'service_id'=> $request->input('service'),
                  'total_candidates'=>1,
                  'status'=>0,
                  'created_by'=>Auth::user()->id,
                  'created_at'=> date('Y-m-d H:i:s')
                ];
              
            $job_id = DB::table('jobs')->insertGetId($job_data);

            //
            $j=0;
            for($i=0; $i< count($data); $i++) {     
              
              if( $i==1){

                //create vcandidate before adding into job item
                $user_data = 
                [
                  'user_type' => 'candidate',
                  'parent_id' => Auth::user()->id,
                  'name'      => $data[$i][0] ,
                  'email'     => $data[$i][1] ,
                  'phone'     => $data[$i][2] ,
                  'created_at'=> date('Y-m-d H:i:s')
                ];
                
                $user_id = DB::table('users')->insertGetId($user_data);
                
                //create job item
                $data = 
                [
                  'job_id'        => $job_id,
                  'candidate_id'  => $user_id,
                  'service_id'    => $request->input('service'),
                  'business_id'     => $request->input('customer'),
                  'created_at'    => date('Y-m-d H:i:s')
                ];

                DB::table('job_items')->insertGetId($data);

                $j++;

              }
            }

            //update total number of candidates
            DB::table('jobs')->where(['id'=>$job_id])->update(['total_candidates'=>$j]);

            return redirect()
            ->route('/app/jobs')
            ->with('success', 'Job created successfully.'); 

    }

    // Function to convert CSV into associative array
    public function csvToArray($file, $delimiter) { 
        if (($handle = fopen($file, 'r')) !== FALSE) { 
        $i = 0; 
        while (($lineArray = fgetcsv($handle, 4000, $delimiter, '"')) !== FALSE) { 
          for ($j = 0; $j < count($lineArray); $j++) { 
            $arr[$i][$j] = $lineArray[$j]; 
          } 
          $i++; 
        } 
        fclose($handle); 
        } 
        return $arr; 
    } 

    //store job
    public function store(Request $request)
    {
      // Form validation
      $this->validate($request, [
        'customer'=>'required',
        'job_name' => 'required',
        'service' => 'required',
        'first_name' => 'required',
        'email' => 'required|email',
        'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
      ]);

      //create a job
    	$data = 
    	[
        'business_id' => $request->input('customer'),
        'parent_id'   =>Auth::user()->business_id,
        'title'       => $request->input('job_name'),
        'service_id'  => $request->input('service'),
        'total_candidates'=>1,
        'status'=>0,
        'created_by'=>Auth::user()->id,
    		'created_at'=> date('Y-m-d H:i:s')
    	];
    	
    	$job_id = DB::table('jobs')->insertGetId($data);

      //create user before adding job item
      $user_data = 
    	[
        'user_type' => 'candidate',
        'parent_id' => Auth::user()->business_id,
    		'name'        => $request->input('first_name').' '.$request->input('last_name'),
        'first_name'      => $request->input('first_name'),
        'last_name'       => $request->input('last_name'),
    		'email'     => $request->input('email'),
    		'phone'     => $request->input('phone'),
    		'created_at'=> date('Y-m-d H:i:s')
      ];
      
      $user_id = DB::table('users')->insertGetId($user_data);
      
      //create job item
      $data = 
    	[
        'job_id'        => $job_id,
        'candidate_id'  => $user_id,
        'service_id'    => $request->input('service'),
        'business_id'   =>$request->input('customer'),
    		'created_at'    => date('Y-m-d H:i:s')
    	];

      DB::table('job_items')->insertGetId($data);

    	 return redirect()
            ->route('/app/jobs')
            ->with('success', 'Product created successfully.');

   	}

}
