<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Model\Job;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class JobController extends Controller
{
    
    function __construct()
    {
        
    } 

    //jobs 
    public function index()
    {
      $business_id = Auth::user()->business_id;
      $user_id = Auth::user()->id;
      // $jobs = DB::table('jobs as j')
      //         ->select('j.business_id','j.id','j.title','j.total_candidates','j.created_at','j.created_by','j.status','j.sla_id')
      //         ->where(['parent_id'=>$business_id])
      //         ->get();

      $kams = DB::table('key_account_managers')
                ->select('*')
                ->where('user_id',$user_id)
                ->get()->pluck('business_id');

      $services = DB::table('services')
              ->select('name','id')
              ->where(['status'=>'1'])
              ->where('business_id',NULL)
              ->whereNotIn('type_name',['e_court','gstin'])
              ->orwhere('business_id',$business_id)
              ->orderBy('sort_number','asc')
              ->get();

        $array_result = [];

        if(count($kams)>0){
          foreach ($services as $key => $value) {
              
              $completed = DB::table('jaf_form_data as jf')
                    ->DISTINCT('u.id')
                    ->join('candidate_reinitiates as u','u.id','=','jf.candidate_id')
                    ->where(['jf.service_id'=> $value->id, 'u.parent_id'=>Auth::user()->business_id,'jf.verification_status'=>'success'])
                    ->whereIn('u.business_id',$kams)
                    ->count();

              $pending = DB::table('jaf_form_data as jf')
                      ->DISTINCT('u.id')
                      ->join('candidate_reinitiates as u','u.id','=','jf.candidate_id')
                      ->where(['jf.service_id'=> $value->id,'u.parent_id'=>Auth::user()->business_id])
                      ->where(function($q){
                        $q->where('jf.verification_status','failed')
                        ->orWhereNull('jf.verification_status');
                    })
                      ->whereIn('u.business_id',$kams)
                      ->count();
                    

              $insuff = DB::table('jaf_form_data as jf')
                      ->DISTINCT('u.id')
                      ->join('candidate_reinitiates as u','u.id','=','jf.candidate_id')
                      ->where(['jf.service_id'=> $value->id,'u.parent_id'=>Auth::user()->business_id,'jf.is_insufficiency'=>'1'])
                      ->whereIn('u.business_id',$kams)
                      ->count();

              $array_result[] = ['check_id'=>$value->id,'check_name'=> $value->name, 'completed'=>$completed, 'pending'=> $pending,'insuff'=>$insuff]; 
                  // 
          }
        }else{
          foreach ($services as $key => $value) {
              
            $completed = DB::table('jaf_form_data as jf')
                  ->DISTINCT('u.id')
                  ->join('candidate_reinitiates as u','u.id','=','jf.candidate_id')
                  ->where(['jf.service_id'=> $value->id, 'u.parent_id'=>Auth::user()->business_id,'jf.verification_status'=>'success'])
                  ->count();

            $pending = DB::table('jaf_form_data as jf')
                    ->DISTINCT('u.id')
                    ->join('candidate_reinitiates as u','u.id','=','jf.candidate_id')
                    ->where(['jf.service_id'=> $value->id,'u.parent_id'=>Auth::user()->business_id])
                    ->where(function($q){
                        $q->where('jf.verification_status','failed')
                        ->orWhereNull('jf.verification_status');
                    })
                    ->count();

            $insuff = DB::table('jaf_form_data as jf')
                    ->DISTINCT('u.id')
                    ->join('candidate_reinitiates as u','u.id','=','jf.candidate_id')
                    ->where(['jf.service_id'=> $value->id,'u.parent_id'=>Auth::user()->business_id,'jf.is_insufficiency'=>'1'])
                    ->count();

            $array_result[] = ['check_id'=>$value->id,'check_name'=> $value->name, 'completed'=>$completed, 'pending'=> $pending,'insuff'=>$insuff]; 
                // 
          }
        }
     
           // dd($array_result);
      return view('admin.jobs.index', compact('array_result'));
    }

    //create job show the form
    public function create() 
    {
      
      $business_id = Auth::user()->business_id;

        $customers = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['user_type'=>'client','parent_id'=>$business_id])
        ->get();

        $services = DB::table('user_services as us')
                  ->select('s.id','s.name')
                  ->join('services as s','s.id','=','us.service_id')
                  ->where(['us.business_id'=>$business_id])
                  ->orderBy('s.name','asc')->get();


    	return view('admin.jobs.create',compact('services','customers'));

    }

    //create job by import show the form
    public function importExcel()
    {
      $business_id = Auth::user()->business_id;
      $customers = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['user_type'=>'client','parent_id'=>$business_id])
        ->get();

    	return view('admin.jobs.import-excel',compact('customers'));
    }

    // store excel data 
    public function storeExcelData(Request $request)
    {
        // echo "jkhd"; die();

          $business_id = Auth::user()->business_id;

            // Form validation
            $this->validate($request, [
            'customer'    =>'required',
            'job_name'    => 'required',
            'verification_type' => 'required',
            'csv_file'    => 'required',
            ]);
            
            $tmpName = $_FILES['csv_file']['tmp_name'];
           
            // if($data[0][0] !="Name" && $data[0][1] !="Phone" && $data[0][2] !="Email"){

            //     return back()->with('error','CSV file is not valid!');
            // }

           $file = $request->file('csv_file');

            $parsed_array = Excel::toArray([], $file);
            // echo "<pre/>";
            // print_r($parsed_array);
             // die('test ok');
            //Remove header row
            $imported_data = array_splice($parsed_array[0], 1);

            // echo "<br>";
            // print_r($imported_data);

            //echo count($data);
            
            //echo "<pre>";
            //debug
            // print_r($data);
            // echo "<br>";
            // echo $data[2][0];
            // print_r($imported_data);
            // die();//

            $job_data = 
                [
                  'business_id' => $request->input('customer'),
                  'parent_id'   =>Auth::user()->business_id,
                  'title'     => $request->input('job_name'),
                  'service_id'=> $request->input('verification_type'),
                  'total_candidates'=>1,
                  'status'=>0,
                  'created_by'=>Auth::user()->id,
                  'created_at'=> date('Y-m-d H:i:s')
                ];
              
            $job_id = DB::table('jobs')->insertGetId($job_data);

            //
            $j=0;
            $i=0;

           foreach ($imported_data as $value)
           {

              //echo $data[$i][0];                
              // echo $i."<br>";
              // echo $data[$i][0];
                //create vcandidate before adding into job item
                $user_data = 
                [
                  'user_type' => 'candidate',
                  'business_id' => $business_id,
                  'parent_id'   =>Auth::user()->business_id,
                  'parent_id' => $business_id,
                  'name'      => $value[0] ,
                  'phone'     => $value[1] ,
                  'email'     => $value[2] ,
                  'created_at'=> date('Y-m-d H:i:s')
                ];
                
                $user_id = DB::table('candidate_reinitiates')->insertGetId($user_data);

                //do entry into candidates table
                //create vcandidate before adding into job item
                $user_data = 
                [
                  'business_id' => $business_id,
                  'name'      => $value[0] ,
                  'phone'     => $value[1] ,
                  'email'     => $value[2] ,
                  'created_at'=> date('Y-m-d H:i:s')
                ];
                
                DB::table('candidates')->insertGetId($user_data);
                
                //create job item
                $data = 
                [
                  'job_id'        => $job_id,
                  'candidate_id'  => $user_id,
                  'service_id'    => $request->input('verification_type'),
                  'business_id'   => $business_id,
                  'created_at'    => date('Y-m-d H:i:s')
                ];

                DB::table('job_items')->insertGetId($data);

                $j++;

            }

            //update total number of candidates
            DB::table('jobs')->where(['id'=>$job_id])->update(['total_candidates'=>$j]);

            return redirect()
            ->route('/jobs')
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
      $business_id = Auth::user()->business_id;

      // Form validation
      $this->validate($request, [
        'customer'  => 'required',
        'job_name'  => 'required',
        'sla'       => 'required',
        'first_name'=> 'required',
        'email'     => 'required|email',
        'phone'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
      ]);
      
      // Start transaction
      DB::beginTransaction();
      try {

      //create a job
      $send_jaf_link_required = 0;
      if($request->has('is_send_jaf_link')){
        $send_jaf_link_required = 1;
      }
    
    	$data = 
    	[
        'business_id'     =>$request->input('customer'),
        'title'           =>$request->input('job_name'),
        'sla_id'          =>$request->input('sla'),
        'total_candidates'=>1,
        'status'          =>0,
        'created_by'      =>Auth::user()->id,
        'parent_id'       =>Auth::user()->business_id,
        'send_jaf_link_required' => $send_jaf_link_required,
    		'created_at'      =>date('Y-m-d H:i:s')
      ];
      
      $job_id = DB::table('jobs')->insertGetId($data);

      // service items
      foreach($request->input('services') as $item){
        $data = ['business_id'=>$request->input('customer'), 
                'job_id'      => $job_id, 
                'sla_item_id'=> $item,
                'created_at'=>date('Y-m-d H:i:s')
              ];
          DB::table('job_sla_items')->insertGetId($data);  

      }
      
      //create user before adding job item
      $user_data = 
    	[
        'user_type'   => 'candidate',
        'business_id' => $business_id,
    		'name'        => $request->input('first_name').' ' .$request->input('middle_name').' '.$request->input('last_name'),
        'first_name'  => $request->input('first_name'),
        'middle_name' => $request->input('middle_name'),
        'last_name'   => $request->input('last_name'),
    		'email'       => $request->input('email'),
    		'phone'       => $request->input('phone'),
    		'created_at'  => date('Y-m-d H:i:s')
      ];
      
      $user_id = DB::table('candidate_reinitiates')->insertGetId($user_data);

      //create candidate before adding into job item
      $user_data = 
      [
        'business_id' => $business_id,
        'candidate_id'=>$user_id,
        'name'        => $request->input('first_name').' '. $request->input('middle_name').' '.$request->input('last_name'),
        'first_name'  => $request->input('first_name'),
        'middle_name' => $request->input('middle_name'),
        'last_name'   => $request->input('last_name'),
        'email'       => $request->input('email') ,
        'phone'       => $request->input('phone') ,
        'created_at'  => date('Y-m-d H:i:s')
      ];
      
      $candidate = DB::table('candidates')->insertGetId($user_data);

      // job item items      
      $data = ['business_id' =>$request->input('customer'), 
                'job_id'       =>$job_id, 
                'candidate_id' =>$user_id,
                'sla_id'       =>$request->input('sla'),
                'created_at'   =>date('Y-m-d H:i:s')
              ];
      DB::table('job_items')->insertGetId($data);  
            
        // commit 
        DB::commit();

        } catch (\Exception $e) {
          DB::rollback();
          // something went wrong
      }

    	return redirect()
            ->route('/jobs')
            ->with('success', 'Case created successfully.');

   	}

     public function candidateChecks()
     {
      return view('admin.jobs.candidate-jobs');

     }

     public function slaChecks()
     {
      return view('admin.jobs.sla-jobs');

     }

}
