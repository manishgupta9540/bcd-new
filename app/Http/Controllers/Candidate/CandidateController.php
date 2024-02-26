<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Admin\KeyAccountManager;
use App\Models\Admin\UserCheck;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Imagick;
use App\Traits\S3ConfigTrait;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Config;
use App\Models\Admin\JafFormData;
use App\Events\CandidateApiCheck;
use App\Models\Admin\CandidateReinitiate;

class CandidateController extends Controller
{
      //JAF Form show
      public function jafForm(Request $request)
      {
          // dd('ms');
          $candidate_id =Auth::guard('candidate')->user()->id;
          $job_item_id= DB::table('job_items')->where('candidate_id',$candidate_id)->first();
          $id =$job_item_id->id;
          
          // $user_id = Auth::user()->id;
          // dd($id);
          $candidate_data=DB::table('candidate_reinitiates')->where(['id'=>$candidate_id,'is_deleted'=>'1'])->first();
          if($candidate_data!=NULL)
          {
            return redirect()->route('/candidate/candidates/error-403');
          }
          else
          {
            $hold_data = DB::table('candidate_hold_statuses')       
                        ->where(['candidate_id'=>$candidate_id])
                        ->where('hold_remove_by','=',null)
                        ->first();
            if($hold_data!=NULL)
            {
              return redirect()->route('/candidate/candidates/error-404');
            }
            else{
                $job_items=DB::table('job_items')->where('id',$id)->first();
                if($job_items!=NULL)
                {
                  $status=$job_items->jaf_status;
                  if($status=='filled')
                  return redirect()->route('/candidate/candidates/thank-you');
                }
            }
          }
          
          $jaf_form_data_check = DB::table('jaf_form_data')->where('job_item_id',$id)->get();
          $job_sla_items = DB::table('job_sla_items')->where('jaf_send_to','candidate')->where('candidate_id',$candidate_id)->get();
          // dd($jaf_form_data_check);
          $input_data = [];
          //
          if ( count($jaf_form_data_check) == 0) {
            
            foreach($job_sla_items as $service){
              
                // service-input-label-0-1-1
                // echo $request->input('service-input-label-0-1-1');
                // die('ok');
                $input_items = DB::table('service_form_inputs as sfi')
                            ->select('sfi.*')            
                            ->where(['sfi.service_id'=>$service->service_id,'status'=>1])
                            ->get();
                $numbers_of_items = $service->number_of_verifications;

              
              for($j=1; $j<=$numbers_of_items; $j++){
                  
                  $i=0;
                
                  $jaf_form_data = [
                                    'business_id' => $service->business_id,
                                    'job_id'      => $service->job_id,
                                    'job_item_id' => $service->job_item_id,
                                    'service_id'  => $service->service_id,
                                    'candidate_id' => $service->candidate_id,
                                    'check_item_number'=>$j,
                                    'sla_id'      =>$service->sla_id,
                                    'created_by'   => $service->candidate_id,
                                    'created_at'   => date('Y-m-d H:i:s')];
        
                $jaf_data= DB::table('jaf_form_data')->insert($jaf_form_data);
              
              }
            }
          }
          // dd($jaf_data);
          $candidate = DB::table('candidate_reinitiates as u')
          ->select('u.id','u.parent_id','u.business_id','u.client_emp_code','u.entity_code','u.first_name','u.dob','u.father_name','u.middle_name','u.last_name','u.name','u.email','u.phone','u.phone_code','u.gender','j.created_at','j.sla_id','u.user_type','u.aadhar_number','u.address','u.address_type')  
          ->leftjoin('job_items as j','j.candidate_id','=','u.id')
          ->where(['u.id'=>$candidate_id]) 
          ->first(); 

          // dd($candidate);

          // $jaf_items= DB::select("SELECT DISTINCT jf.id, jf.*,jsi.jaf_send_to,s.name as service_name,s.id as service_id FROM jaf_form_data as jf JOIN services as s ON s.id=jf.service_id JOIN job_sla_items as jsi ON jsi.job_id=jf.job_id WHERE jf.job_item_id=$id");

          $jaf_items = JafFormData::from('jaf_form_data as jf')
                      ->Distinct('jf.id')
                      ->select('jf.id', 'jf.*','jsi.jaf_send_to','s.name as service_name','s.id as service_id','s.type_name')
                      ->join('services as s','s.id','=','jf.service_id')
                      ->join('job_sla_items as jsi','jsi.job_id','=','jf.job_id')
                      ->where('jf.job_item_id',$id)
                      ->get();
          // dd($jaf_items);
          // $jaf_items = Db::table('jaf_form_data as jf')
          // ->select('jf.*','jsi.jaf_send_to','s.name as service_name','s.id as service_id')  
          // ->join('services as s','s.id','=','jf.service_id')
          // ->join('job_sla_items as jsi','jsi.job_id','=','jf.job_id' )
          // ->where(['jf.job_item_id'=>$id])
          // ->groupBy('jf.id') 
          // ->get(); 

          // dd($jaf_items); 
          // $checks = UserCheck::where('user_id',$user_id)->get();

          // $new_jaf_form_data = DB::table('jaf_form_data')->where('job_id',$id)->get();
          // dd($new_jaf_form_data);
          return view('candidate.candidates.jaf-form',compact('candidate','jaf_items','job_item_id'));
      }

      //JAF Form data save
      public function jafSave(Request $request)
      {
        // dd($request); 
        $case_id      = $request->input('case_id');
        $candidate_id = $request->input('candidate_id');
        $business_id  = $request->input('business_id');
        $business =   Auth::guard('candidate')->user()->parent_id;
        $candidate=base64_encode($request->input('candidate_id'));

          if ($request->type == 'formtype') 
          {
            
            $job_data=DB::table('job_items')->where(['id'=>$case_id])->first();
            if($job_data->jaf_status!='filled')
            {
              $jaf_items = DB::table('jaf_form_data')->where(['job_item_id'=>$case_id])->get();
              // dd($case_id);
              $input_data = [];
              
              foreach($jaf_items as $service){
                
                // service-input-label-0-1-1
                // echo $request->input('service-input-label-0-1-1');
                // die('ok');
                $input_items = DB::table('service_form_inputs as sfi')
                            ->select('sfi.*')            
                            ->where(['sfi.service_id'=>$service->service_id,'sfi.status'=>1])
                            ->get();
                // $j = 1;
                // $job_item_data = DB::table('job_sla_items')->where(['id'=>$case_id])->first();
                // $numbers_of_items = $job_item_data->number_of_verifications;
                // if($numbers_of_items > 1){
                //   $j++;
                // }
                  $reference_type = NULL;
                  $input_data = [];
                  $i=0;
                  foreach($input_items as $input){

                    if($input->service_id==17)
                    {
                        if($input->reference_type==NULL && !(stripos($input->label_name,'Mode of Verification')!==false || stripos($input->label_name,'Remarks')!==false))
                        { 
                          $input_data[] = [
                            $request->input('service-input-label-'.$service->id.'-'.$i)=>$request->input('service-input-value-'.$service->id.'-'.$i),
                            'is_report_output'=>$input->is_report_output 
                          ];

                          if(stripos($request->input('service-input-label-'.$service->id.'-'.$i),'Reference Type (Personal / Professional)')!==false)
                          {
                              $reference_type = $request->input('service-input-value-'.$service->id.'-'.$i);
                          }

                        }
                    }
                    else if($input->service_id==10){
                      
                      if($input->label_name=='Date of Joining (Employee Tenure)' || $input->label_name=='Date of Relieving (Employee Tenure)')
                      {
                          $input_data[] = [
                            $request->input('service-input-label-'.$service->id.'-'.$i)=>$request->input('service-input-value-'.$service->id.'-'.$i),
                            'is_report_output'=>$input->is_report_output,
                            'input_type' => 'date' 
                          ];
                      }
                      else
                      {
                        $input_data[] = [
                          $request->input('service-input-label-'.$service->id.'-'.$i)=>$request->input('service-input-value-'.$service->id.'-'.$i),
                          'is_report_output'=>$input->is_report_output 
                        ];
                      }
                      
                    }
                    else
                    {
                      $input_data[] = [
                        $request->input('service-input-label-'.$service->id.'-'.$i)=>$request->input('service-input-value-'.$service->id.'-'.$i),
                        'is_report_output'=>$input->is_report_output 
                      ];
                    }
                      $i++;
                  }
                
                  $jaf_data = json_encode($input_data);
                  
                  //insuff
                  $is_insufficiency = 0;
                  if($request->has('insufficiency-'.$service->id)){
                    $is_insufficiency = 1;
                  }
                  // $insufficiency_notes = $request->input('insufficiency-notes-'.$service->id.'-'.$i);
        
                  $address_type = $request->input('address-type-'.$service->id);
        
                  $jaf_form_data = 
                                  [
                                  
                                    'form_data'       => $jaf_data,
                                    'form_data_all'   => json_encode($request->all()),
                                    'is_insufficiency'=>$is_insufficiency,
                                    // 'insufficiency_notes'=>$insufficiency_notes,
                                    'address_type'  =>$address_type,
                                    'reference_type'  =>$reference_type,
                                    'created_by'    => $candidate_id,
                                    'updated_at'    => date('Y-m-d H:i:s')
                                  ];
        
                  DB::table('jaf_form_data')->where(['id'=>$service->id])->update($jaf_form_data);
        
                  DB::table('job_items')->where(['id'=>$case_id])->update(['jaf_status'=>'draft','filled_by_type'=>'customer','filled_by'=>Auth::user()->id,'filled_at'=>date('Y-m-d H:i:s')]);
  
                // 
              }
              return response()->json([
                'success' =>true,
                'status'  =>'no',
                'errors'  =>[]
              ]);
            }
            else{
              $users_d=DB::table('candidate_reinitiates')->where('id',$job_data->filled_by)->first();
              $name= $users_d->name;
                return response()->json([
                  'success' =>true,
                  'status'  =>'yes',
                  'filled_by' => $name,
                  'candidate_id'=>$candidate,
                ]);
            }
          }
          else {
            // DB::table('job_items')->where(['id'=>$case_id])->update(['jaf_status'=>'filled','filled_by'=>$candidate_id,'filled_at'=>date('Y-m-d H:i:s')]);
            $jaf_items_data = DB::table('jaf_form_data')->where(['job_item_id'=>$case_id])->get();
            // dd($request->all());
            // Validation for address
            foreach($jaf_items_data as $service)
            {
                if($service->service_id==1)
                {
                  $input_items = DB::table('service_form_inputs as sfi')
                  ->select('sfi.*')            
                  ->where(['sfi.service_id'=>$service->service_id,'sfi.status'=>1])
                  ->get();
                  $data=false;
                  $i=2;
                  foreach($input_items as $item)
                  {
                      if($request->input('service-input-value-'.$service->id.'-'.$i)=='' || $request->input('service-input-value-'.$service->id.'-'.$i)==NULL)
                      {
                          $data=true;
                      }
                      else
                      {
                          $data = false;
                          break;
                      }
                      $i++;
                  }
                  if($data)
                  {
                      $rules=[
                        'address-type-'.$service->id => 'required',
                      ];
  
                      $custom=[
                        'address-type-'.$service->id.'.required' => 'Address Type Field is required'
                      ];
          
                      $validator = Validator::make($request->all(), $rules,$custom);
                  
                      if ($validator->fails()){
                            return response()->json([
                                'fail' => true,
                                'errors' => $validator->errors(),
                                'error_type'=>'validation'
                            ]);
                      }
                  }
                  
                }
                else
                {
                  break;
                }
                
            }

             // Validation for Employment

            //  foreach($jaf_items_data as $service)
            //  {
            //    // Employment
            //    if($service->service_id==10)
            //    {
            //       $input_items = DB::table('service_form_inputs as sfi')
            //       ->select('sfi.*')            
            //       ->where(['sfi.service_id'=>$service->service_id,'status'=>1])
            //       ->whereNull('sfi.reference_type')
            //       ->whereNotIn('label_name',['Mode of Verification','Remarks'])
            //       ->get();

            //         $j=0;
            //         foreach($input_items as $item)
            //         {
            //             $name=$item->label_name;

            //             if($name=='Date of Joining (Employee Tenure)' || $name=='Date of Relieving (Employee Tenure)')
            //             {
            //                 $rules=[
            //                   'service-input-value-'.$service->id.'-'.$j=> 'required',
            //                 ];
    
            //                 $custom=[
            //                   'service-input-value-'.$service->id.'-'.$j.'.required' => $name.' Field is required',
            //                 ];
                
            //                 $validator = Validator::make($request->all(), $rules,$custom);
    
            //                 if ($validator->fails()){
            //                     return response()->json([
            //                         'fail' => true,
            //                         'errors' => $validator->errors(),
            //                         'error_type'=>'validation'
            //                     ]);
            //                 }
            //             }

            //             $j++;
            //         }
            //    }
            //  }

            // Validation for Check Control

            foreach($jaf_items_data as $service)
            {
                $input_items = DB::table('service_form_inputs as sfi')
                                      ->select('sfi.*')            
                                      ->where(['sfi.service_id'=>$service->service_id,'status'=>1])
                                      ->whereNull('sfi.reference_type')
                                      ->whereNotIn('label_name',['Mode of Verification','Remarks'])
                                      ->get();

                $j=0;
                foreach($input_items as $item)
                {
                    $name=$item->label_name;
                    $check_control_masters=DB::table('check_control_masters')->where(['check_control_coc_id'=>$service->business_id,'service_input_id'=>$item->id])->first();
                    if($check_control_masters!=NULL){

                        if($service->service_id==10)
                        { 
                            if($name=='Date of Joining (Employee Tenure)' || $name=='Date of Relieving (Employee Tenure)')
                            {
                                $rules=[
                                  'service-input-value-'.$service->id.'-'.$j.'-1'=> 'required_without:service-input-value-'.$service->id.'-'.$j.'-2',
                                  'service-input-value-'.$service->id.'-'.$j.'-2'=> 'required_without:service-input-value-'.$service->id.'-'.$j.'-1',
                                ];
  
                                $custom=[
                                  'service-input-value-'.$service->id.'-'.$j.'-1'.'.required_without' => $name.' Field is required',
                                  'service-input-value-'.$service->id.'-'.$j.'-2'.'.required_without' => $name.' Field is required'
                                ];
                            }
                            else
                            {
                                $rules=[
                                  'service-input-value-'.$service->id.'-'.$j=> 'required',
                                ];
  
                                $custom=[
                                  'service-input-value-'.$service->id.'-'.$j.'.required' => $name.' Field is required'
                                ];
                            }
                
                            $validator = Validator::make($request->all(), $rules,$custom);
                        
                            if ($validator->fails()){
                                return response()->json([
                                    'fail' => true,
                                    'errors' => $validator->errors(),
                                    'error_type'=>'validation'
                                ]);
                            }
                        }
                        else
                        {
                            $rules=[
                              'service-input-value-'.$service->id.'-'.$j=> 'required',
                            ];
    
                            $custom=[
                              'service-input-value-'.$service->id.'-'.$j.'.required' => $name.' Field is required'
                            ];
                
                            $validator = Validator::make($request->all(), $rules,$custom);
    
                            if ($validator->fails()){
                                return response()->json([
                                    'fail' => true,
                                    'errors' => $validator->errors(),
                                    'error_type'=>'validation'
                                ]);
                            }
                        }
                    }
                    $j++;
                }

            }

            // Validation for Employment & Educational

            foreach($jaf_items_data as $service)
            {
              // Employment
              if($service->service_id==10)
              {
                 $fake_employment = DB::table('fake_employment_lists')->where([
                  'status' => 1,
                  'type' => 'fake'
                 ])->where(DB::raw('lower(company_name)'), strtolower($request->input('service-input-value-'.$service->id.'-'.'2')))->first();

                 if($fake_employment!=NULL)
                 {
                      return response()->json([
                        'fail' => true,
                        'errors' => ['service-input-value-'.$service->id.'-'.'2'=>'System found, This company name seems fake.'],
                        'error_type'=>'validation'
                    ]);
                 }
                
              }
              // Educational
              else if($service->service_id==11)
              {
                $fake_educational = DB::table('fake_educational_lists')->where([
                  'status' => 1,
                  'type' => 'fake'
                 ])->where(DB::raw('lower(board_or_university_name)'),strtolower($request->input('service-input-value-'.$service->id.'-'.'2')))->first();
                if($fake_educational!=NULL)
                {
                     return response()->json([
                       'fail' => true,
                       'errors' => ['service-input-value-'.$service->id.'-'.'2'=>'System found, This university / board name seems fake.'],
                       'error_type'=>'validation'
                   ]);
                }
              }
            }

            // Validation for file attachment

            // Validation for Client (Rekrut) & Reference Check

              $client_rekrut = DB::table('users as u')
                                ->distinct('j.service_id')
                                ->select('u.*','j.service_id')
                                ->where('u.id',$business_id)
                                ->join('jaf_form_data as j','j.business_id','=','u.id')
                                ->where(['u.user_type'=>'client'])
                                ->where('j.candidate_id',$candidate_id)
                                //->where('j.service_id',17)
                                ->groupBy('j.service_id')
                                ->get();

              if($business_id!=2155 && !(count($client_rekrut)==1 && $client_rekrut->contains('service_id',17)))
              {
                  foreach($jaf_items_data as $service)
                  {
                      // if($service->service_id==1)
                      // {
                      //   $rules=[
                      //     'address-type-'.$service->id => 'required',
                      //     // 'file'-$service->id =>'required,'
                      //   ];
    
                      //   $custom=[
                      //     'address-type-'.$service->id.'.required' => 'Address Type Field is required',
                      //     // 'file-'.$service->id.'.required' => 'Address Type Field is required'
                      //   ];
            
                      //   $validator = Validator::make($request->all(), $rules,$custom);
                    
                      //   if ($validator->fails()){
                      //         return response()->json([
                      //             'fail' => true,
                      //             'errors' => $validator->errors(),
                      //             'error_type'=>'validation'
                      //         ]);
                      //   }
                        
                        
                      // }
                      $jaf_item_attach = DB::table('jaf_item_attachments')->where(['jaf_id'=>$service->id,'is_deleted'=>'0'])->first();
    
                      if($jaf_item_attach==null){
                        $rules=[
                        
                          'file'.'-'.$service->id=>'required,',
                        ];
    
                      
                              return response()->json([
                                'fail'=>true,
                                // 'success' => true,
                                  'errors' => ['file'.'-'.$service->id=>'Please select atleast one file'],
                                  'error_type'=>'validation'
                              ]);
                        
                      }
                      
                    
                  } 
              }
              else
              {
                  if(count($client_rekrut)>1 && $client_rekrut->contains('service_id',17))
                  {
                      foreach($jaf_items_data as $service)
                      {
                          // if($service->service_id==1)
                          // {
                          //   $rules=[
                          //     'address-type-'.$service->id => 'required',
                          //     // 'file'-$service->id =>'required,'
                          //   ];
        
                          //   $custom=[
                          //     'address-type-'.$service->id.'.required' => 'Address Type Field is required',
                          //     // 'file-'.$service->id.'.required' => 'Address Type Field is required'
                          //   ];
                
                          //   $validator = Validator::make($request->all(), $rules,$custom);
                        
                          //   if ($validator->fails()){
                          //         return response()->json([
                          //             'fail' => true,
                          //             'errors' => $validator->errors(),
                          //             'error_type'=>'validation'
                          //         ]);
                          //   }
                            
                            
                          // }
                          $jaf_item_attach = DB::table('jaf_item_attachments')->where(['jaf_id'=>$service->id,'is_deleted'=>'0'])->first();
        
                          if($jaf_item_attach==null){
                            $rules=[
                            
                              'file'.'-'.$service->id=>'required,',
                            ];
        
                          
                                  return response()->json([
                                    'fail'=>true,
                                    // 'success' => true,
                                      'errors' => ['file'.'-'.$service->id=>'Please select atleast one file'],
                                      'error_type'=>'validation'
                                  ]);
                            
                          }
                          
                        
                      }
                  }
              }
            $j = 0;
            $serviceId = [];

            foreach($jaf_items_data as $service)
            {
              $input_items = DB::table('service_form_inputs as sfi')
                            ->select('sfi.*')            
                            ->where(['sfi.service_id'=>$service->service_id,'sfi.status'=>1])
                            ->get();

              // if($service->form_data==NULL)
              // {
                  // $j = 1;
                  // $job_item_data = DB::table('job_sla_items')->where(['id'=>$case_id])->first();
                  // $numbers_of_items = $job_item_data->number_of_verifications;
                  // if($numbers_of_items > 1){
                  //   $j++;
                  // }


                  $input_data = [];
                  $reference_type = NULL;
                  $i=0;
                  foreach($input_items as $input){
                    if($input->service_id==17)
                    {
                        if($input->reference_type==NULL && !(stripos($input->label_name,'Mode of Verification')!==false || stripos($input->label_name,'Remarks')!==false))
                        { 
                          $input_data[] = [
                            $request->input('service-input-label-'.$service->id.'-'.$i)=>$request->input('service-input-value-'.$service->id.'-'.$i),
                            'is_report_output'=>$input->is_report_output 
                          ];

                          if(stripos($request->input('service-input-label-'.$service->id.'-'.$i),'Reference Type (Personal / Professional)')!==false)
                          {
                              $reference_type = $request->input('service-input-value-'.$service->id.'-'.$i);
                          }

                        }
                    }
                    else if($input->service_id==10){
                      
                      if($input->label_name=='Date of Joining (Employee Tenure)' || $input->label_name=='Date of Relieving (Employee Tenure)')
                      {
                          $input_data[] = [
                            $request->input('service-input-label-'.$service->id.'-'.$i)=>$request->input('service-input-value-'.$service->id.'-'.$i),
                            'is_report_output'=>$input->is_report_output,
                            'input_type' => 'date' 
                          ];
                      }
                      else
                      {
                        $input_data[] = [
                          $request->input('service-input-label-'.$service->id.'-'.$i)=>$request->input('service-input-value-'.$service->id.'-'.$i),
                          'is_report_output'=>$input->is_report_output 
                        ];
                      }
                      
                    }
                    else
                    {
                        $input_data[] = [
                          $request->input('service-input-label-'.$service->id.'-'.$i)=>$request->input('service-input-value-'.$service->id.'-'.$i),
                          'is_report_output'=>$input->is_report_output 
                        ];
                    }
                    
                      $i++;
                  }
                
                  $jaf_data = json_encode($input_data);

                  //insuff
                  $is_insufficiency = 0;
                  // if($request->has('insufficiency-'.$service->id)){
                  //   $is_insufficiency = 1;
                  // }

                  $insufficiency_notes = NULL;

                  $address_type = $request->input('address-type-'.$service->id);

                  $jaf_form_data = 
                  [
                                
                      'form_data'       => $jaf_data,
                      'form_data_all'   => json_encode($request->all()),
                      'is_insufficiency'=>$is_insufficiency,
                      'insufficiency_notes'=>$is_insufficiency==1?$insufficiency_notes:NULL,
                      'address_type'  =>$address_type,
                      'reference_type'  =>$reference_type,
                      'created_by'    => Auth::user()->id,
                      'is_filled' => '1',
                      'updated_at'    => date('Y-m-d H:i:s')
                  ];
  
                    DB::table('jaf_form_data')->where(['id'=>$service->id])->update($jaf_form_data);

                    // $report_items = DB::table('report_items')->where(['jaf_id'=>$service->id])->first();

                    // if($report_items!=NULL)
                    // {
                    //   if($report_items->jaf_data==NULL)
                    //   {
                    //     $report_item_data=[];
                    //     if ($item->verification_status == 'success') {
                    //       $report_item_data = 
                    //       [ 
                    //         'jaf_data'      =>$jaf_data,
                    //         'reference_type'  =>$reference_type,
                    //         'created_at'    =>date('Y-m-d H:i:s')
                    //       ];
                    //     } 
                    //     else {
                    //       $report_item_data = 
                    //       [    
                    //         'jaf_data'      =>$jaf_data,
                    //         'reference_type'  =>$reference_type,
                    //         'is_report_output' => '0',
                    //         'created_at'    =>date('Y-m-d H:i:s')
                    //       ]; 
                    //     }
                    //     DB::table('report_items')->where(['id'=>$report_items->id])->update($report_item_data);
                    //   }
                      
                    // }
                    
              // }
              $j++;
              $serviceId[] = [$service->service_id, $service->check_item_number];

            }


             // Signature Upload
             $candidate = base64_encode($candidate_id);
            //  dd($request->signed);
            //  if
            if($request->has('signed') && $request->signed!=null)
            {
                $s3_config = S3ConfigTrait::s3Config();
                $digital_signature_file_platform  = 'web';
                $folderPath = public_path('uploads/signatures/');
            
                $image_parts = explode(";base64,", $request->signed);
                      
                $image_type_aux = explode("image/", $image_parts[0]);
                // dd($image_type_aux);
                $image_type = $image_type_aux[1];
                   
                $image_base64 = base64_decode($image_parts[1]);
                
                $digital_signature =   $candidate . '.'.$image_type;
                $file = $folderPath . $candidate . '.'.$image_type;
                file_put_contents($file, $image_base64);

                if($s3_config!=NULL)
                {
                    $digital_signature_file_platform = 's3';

                    $path = 'uploads/signatures/';

                    if(!Storage::disk('s3')->exists($path))
                    {
                        Storage::disk('s3')->makeDirectory($path,0777, true, true);
                    }

                    Storage::disk('s3')->put($path.$digital_signature, file_get_contents($file));

                    if(File::exists($file))
                    {
                        File::delete($file);
                    }
                }

                $user_id = DB::table('candidate_reinitiates')
                            ->where('id',$candidate_id)
                            ->update(['digital_signature'=>$digital_signature,'digital_signature_file_platform'=>$digital_signature_file_platform]);
            }
            // return Redirect::back()
            //       ->with('success', 'Candidate JAF submitted.');

            $candidates= CandidateReinitiate::where('id',$candidate_id)->first();

            $users= DB::table('users')->where('id',$candidates->parent_id)->first();
            $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
             //dd(env('MAIL_FROM_NAME'));

            $name=$users->name;
            $email=$users->email;

            $msg= "JAF Has Been Filled Successfully By Candidate";
            $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'candidate'=>$candidates,'sender'=>$sender);
        
            // Mail::send(['html'=>'mails.jaf-fill-candidate'], $data, function($message) use($email,$name) { 
            //   $message->to($email, $name)->subject
            //       ('myBCD System - JAF Notification');
            //   $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
            // });

            dispatch(new \App\Jobs\Candidate\JafFilled($data,$email,$name));

            $serviceUpi = DB::table('services')->select('id','name','type_name')->where('type_name','upi')->first();

            DB::table('job_items')->where(['id'=>$case_id])->update(['jaf_status'=>'filled','filled_by'=>$candidate_id,'filled_at'=>date('Y-m-d H:i:s')]);

            $job_item =DB::table('job_items')->where(['candidate_id'=>$candidate_id,'jaf_status'=>'filled'])->first();
            //dd($job_item);
            if ( $job_item->jaf_status == 'filled') {

              $jfd_service= DB::table('jaf_form_data')->where('candidate_id',$candidate_id)->whereIn('service_id',['2','3','4','7','8','9','12',$serviceUpi->id])->get();
              $jfd_service=$jfd_service->toArray();
              //dd($jfd_service);
              event(new CandidateApiCheck($jfd_service));
              // task assign start
              $final_users = [];
              // $j = 0;
              $job_sla_items = DB::table('job_sla_items as jsi')
                                ->join('services as s','s.id','=','jsi.service_id')
                                ->select('jsi.*','s.name as service_name','s.verification_type')
                                ->where('candidate_id',$candidate_id)->get();
              $user = DB::table('candidate_reinitiates')->where('id',$candidate_id)->first();

              foreach ($job_sla_items as $job_sla_item) 
              {
                    //Get data of user of customer with 
                    $user_permissions = DB::table('users as u')
                    ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                    ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                    ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
                    ->where('u.business_id',$user->parent_id)
                    ->get();
                    // Get JAF FILLING data from Action table for matching checking permission
                    $action_master = DB::table('action_masters')
                    ->select('*')
                    ->where(['route_group'=>'','action_title'=>'JAF Filled'])
                    ->first(); 
                    // dd($action_master->id);
                    // Check condition if user_permission have any data or not
                    if(count($user_permissions)>0) {
                      $users=[];
                      foreach ($user_permissions as $user_permission) {  
                        if(in_array($action_master->id,json_decode($user_permission->permission_id)))
                        {
                          $users[]= $user_permission;
                        }
                      }
                    }
                    $final_users = [];
                    $numbers_of_items = $job_sla_item->number_of_verifications;
                    if($numbers_of_items > 0){
                      for ($i=1; $i <= $numbers_of_items; $i++) 
                      { 
                       $final_users = [];
                        //insert in task
                        $data = [
                          'name'          => $user->first_name.' '.$user->last_name,
                          'parent_id'=> Auth::guard('candidate')->user()->parent_id,
                          'business_id'   => $business_id, 
                          'description'   => 'Task for Verification',
                          'job_id'        => NULL, 
                          'priority'      => 'normal',
                          'candidate_id'  => $candidate_id,   
                          'service_id'    => $job_sla_item->service_id, 
                          'number_of_verifications' => $i,
                          'assigned_to'   => NULL,
                          // 'assigned_by'   => Auth::user()->id,
                          // 'assigned_at'   => date('Y-m-d H:i:s'),
                          // 'start_date'    => date('Y-m-d'),
                          'created_by'    => $candidate_id,
                          'created_at'    => date('Y-m-d H:i:s'),
                          'is_completed'  => '0',
                          // 'started_at'    => date('Y-m-d H:i:s')
                        ];
                        // // dd($data);
                        $task_id =  DB::table('tasks')->insertGetId($data); 

                        $taskdata = [
                          'parent_id'=> Auth::guard('candidate')->user()->parent_id,
                          'business_id'   => $business_id,
                          'candidate_id'  =>$candidate_id,   
                          'job_sla_item_id'  => $job_sla_item->id,
                          'task_id'       => $task_id,
                        //  'user_id'       =>  $task_user->id,
                          'service_id'    =>$job_sla_item->service_id,
                          'number_of_verifications' => $i,
                          'created_at'    => date('Y-m-d H:i:s')  
                        ];
                        
                        DB::table('task_assignments')->insertGetId($taskdata); 
                        // DB::table('task_assignments')->insertGetId($taskdata); 
                      
                        //send email to customer
                        
                            $candidates= CandidateReinitiate::where('id',$candidate_id)->first();
                        
                            $admin_email = $user->email;
                            $admin_name = $user->name;

                            $reference_number = $candidates->display_id;
                            
                            $email = $admin_email;
                            $name  = $admin_name;
                            $candidate_name = $candidates->name;
                            if($job_sla_item->verification_type == 'Manual'){
                              $service_name =  $job_sla_item->service_name.'-'.$i;
                            }else if($job_sla_item->verification_type == 'Auto'){
                              $service_name =  $job_sla_item->service_name;
                            }
                            $msg = "New JAF verification Task Created with candidate name";
                            $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                            $data  = array('name'=>$name,'email'=>$email,'service_name'=>$service_name,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
                            //dd($data);
                            // Mail::send(['html'=>'mails.task_notify_service'], $data, function($message) use($email,$name,$reference_number) {
                            //       $message->to($email, $name)->subject
                            //         ('myBCD System - Notification for JAF Filling Task ('.$reference_number.')');
                            //       $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                            // });

                            dispatch(new \App\Jobs\Candidate\Task\NotifyService($data,$email,$name,$reference_number));

                        // $kams  = KeyAccountManager::where('business_id',$business_id)->get();

                        // if (count($kams)>0) {
                        //   foreach ($kams as $kam) {
                        //     $candidates= DB::table('candidate_reinitiates')->where('id',$candidate_id)->first();
                        //     $user= User::where('id',$kam->user_id)->first();
                          
                        //     $email = $user->email;
                        //     $name  = $user->name;
                        //     $candidate_name = $candidates->name;
                        //     // $candidate_name = $request->input('first_name');
                        //     if($job_sla_item->verification_type == 'Manual'){
                        //       $service_name =  $job_sla_item->service_name.'-'.$i;
                        //     }else if($job_sla_item->verification_type == 'Auto'){
                        //       $service_name =  $job_sla_item->service_name;
                        //     }
                        //     $msg = "New JAF verification Task Created with candidate name";
                        //     $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                        //     $data  = array('name'=>$name,'email'=>$email,'service_name'=>$service_name,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);

                        //     Mail::send(['html'=>'mails.task_notify_service'], $data, function($message) use($email,$name,$sender) {
                        //           $message->to($email, $name)->subject
                        //             ('myBCD System - Notification for JAF Filling Task');
                        //           $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        //     });

                        //   }
                          
                        // }
                      }

                    }
              }

                   
                      
                        
                          //  update task 
                        $report = DB::table('reports')->where('candidate_id',$candidate_id)->first();
                        // dd($report);
                        if ($report==NULL) {
                          $report= '';
                        }
          
                         //check report items created or not
                         $report = DB::table('reports')->where('candidate_id',$candidate_id)->first();
                        // dd($report);
                        if ($report==NULL) {
                          $report= '';
                        }
              
                        //check report items created or not
                        $report_count = DB::table('reports')->where(['candidate_id'=>$candidate_id])->count(); 

                        $job = DB::table('job_items')->where(['candidate_id'=>$candidate_id])->first(); 
                         //dd($report_count);
                        if($report_count == 0)
                        {
                            $data = 
                            [
                              'parent_id'     =>$business,
                              'business_id'   =>$job->business_id,
                              'candidate_id'  =>$candidate_id,
                              'sla_id'        =>$job->sla_id,       
                              'created_at'    =>date('Y-m-d H:i:s')
                            ];
                            
                            $report_id = DB::table('reports')->insertGetId($data);
                        }
                        else
                        {
                            $report_id = $report->id;
                        }
                            
                        // add service items
                        $jaf_items_datas = DB::table('jaf_form_data')->where(['candidate_id'=>$candidate_id])->get(); 
                        
                          foreach($jaf_items_datas as $item){
                            $reference_type = NULL;
                            
                            // $l=0;
                            
                            // if($item->service_id==17)
                            // {
                            //   $input_data = $item->form_data;
                            //   $input_data_array = json_decode($input_data,true);

                            //   if($input_data_array!=NULL)
                            //   {
                            //     foreach($input_data_array as $key => $input)
                            //     {
                            //       $key_val = array_keys($input); $input_val = array_values($input);
                            //       if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                            //       {
                            //          $reference_type = $input_val[0];
                            //       }
                            //     }
                            //   }
                            // }
                            // $input_items = DB::table('service_form_inputs as sfi')
                            //               ->select('sfi.*')            
                            //               ->where(['sfi.service_id'=>$item->service_id,'status'=>1])
                            //               ->get();
                            // if($item->service_id==17)
                            // {
                            //   $m=0;
                            //   foreach($input_items as $input)
                            //   {
                            //     if($input->service_id==17)
                            //     {
                            //       if(stripos($request->input('service-input-label-'.$item->id.'-'.$m),'Reference Type (Personal / Professional)')!==false)
                            //       {
                            //           $reference_type = $request->input('service-input-value-'.$item->id.'-'.$m);
                            //       }
                            //     }
                            //     $m++;
                            //   }
                            // }
                            // if($item->service_id==17)
                            // {
                            //   $reference_type = $item->reference_type;
                            // }
                            $r_item = DB::table('report_items')->where('jaf_id',$item->id)->first();

                            if($r_item!=NULL)
                            {
                                if ($item->verification_status == 'success') {
                                  $data = 
                                  [
                                    'jaf_data'      =>$item->form_data,
                                    'updated_at'    =>date('Y-m-d H:i:s')
                                  ];
                                } 
                                else {
                                  $data = 
                                  [
                                    'jaf_data'      =>$item->form_data,
                                    'updated_at'    =>date('Y-m-d H:i:s')
                                  ]; 
                                }

                                DB::table('report_items')->where('jaf_id',$item->id)->update($data);

                                $report_item_id = $r_item->id;
                            }
                            else{
                              if ($item->verification_status == 'success') {
                                $data = 
                                [
                                  'report_id'     =>$report_id,
                                  'service_id'    =>$item->service_id,
                                  'service_item_number'=>$item->check_item_number,
                                  'candidate_id'  =>$candidate_id,      
                                  'jaf_data'      =>$item->form_data,
                                  'jaf_id'        =>$item->id,
                                  // 'reference_type' =>  $reference_type,
                                  'created_at'    =>date('Y-m-d H:i:s')
                                ];
                              } 
                              else {
                                $data = 
                                [
                                  'report_id'     =>$report_id,
                                  'service_id'    =>$item->service_id,
                                  'service_item_number'=>$item->check_item_number,
                                  'candidate_id'  =>$candidate_id,      
                                  'jaf_data'      =>$item->form_data,
                                  'jaf_id'        =>$item->id,
                                  'is_report_output' => '0',
                                  // 'reference_type' =>  $reference_type,
                                  'created_at'    =>date('Y-m-d H:i:s')
                                ]; 
                              }
                              $report_item_id = DB::table('report_items')->insertGetId($data);
                            }

                            
                            // dd($report_item_id);
                            // $jaf_item_attachments = DB::table('jaf_item_attachments as jf')
                            // ->select('jf.id','jf.jaf_id','jf.file_name','jf.created_at','jf.attachment_type')                        
                            // ->where(['jf.jaf_id'=>$item->id,'is_deleted'=>'0']) 
                            // ->orderBy('img_order','ASC')   
                            // ->get();  

                            $jaf_item_attachments= DB::table('jaf_item_attachments')->where(['jaf_id'=>$item->id,'is_deleted'=>'0'])->get();
                            
                            if(count($jaf_item_attachments)>0){
                              foreach($jaf_item_attachments as $attachment){
                                // dd(public_path('/uploads/report-files/'));
                                // dd($attachment);
                                // $dir  = public_path('/uploads/jaf-files/');
                                $file_platform = 'web'; 
                                if(stripos($attachment->file_platform,'s3')!==false)
                                {
                                  $s3_config = S3ConfigTrait::s3Config();

                                  $file_platform = 's3';
                                  $jaf_path = 'uploads/jaf-files/';
                                  $report_path = 'uploads/report-files/';
                                  if(!Storage::disk('s3')->exists($report_path))
                                  {
                                      Storage::disk('s3')->makeDirectory($report_path,0777, true, true);
                                  }

                                  if(Storage::disk('s3')->exists($report_path.$attachment->file_name))
                                  {
                                      Storage::disk('s3')->delete($report_path.$attachment->file_name);
                                  }

                                  Storage::disk('s3')->copy($jaf_path.$attachment->file_name,$report_path.$attachment->file_name);
                                }
                                else
                                {
                                    if(File::exists(public_path('/uploads/report-files/'.$attachment->file_name)))
                                    {
                                        File::delete(public_path('/uploads/report-files/'.$attachment->file_name));
                                    }

                                    File::copy(public_path('/uploads/jaf-files/'.$attachment->file_name),public_path('/uploads/report-files/'.$attachment->file_name)); 
                                }
                              
                                DB::table('report_item_attachments')->insert([
                                  'report_id'        => $report_id, 
                                  'report_item_id'   => $report_item_id,
                                  'jaf_item_attachment_id'=>$attachment->id,
                                  'file_name'        => $attachment->file_name,
                                  'attachment_type'  => $attachment->attachment_type,
                                  'file_platform'   => $file_platform,
                                  'img_order'       =>$attachment->img_order,
                                  'created_by'       => Auth::user()->id,
                                  'created_at'       => date('Y-m-d H:i:s'),
                                  'is_temp'          => $attachment->is_temp,
                                ]);
                              }
                            }
                            $report_item = DB::table('report_items')->where(['id'=>$report_item_id])->first();

                            if($report_item->service_id==17)
                            {
                              $input_data = $item->form_data;

                              $input_data_array = json_decode($input_data,true);

                              if($input_data_array!=NULL)
                              {
                                foreach($input_data_array as $key => $input)
                                {
                                  $key_val = array_keys($input); $input_val = array_values($input);
                                  if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                                  {
                                    $reference_type = $input_val[0];
                                  }
                                }
                              }
                            }

                            DB::table('report_items')->where(['id'=>$report_item_id])->update([
                                'reference_type' => $reference_type
                            ]);
                          }
                        
                      

                    $kams_canidateId = CandidateReinitiate::where('id',$candidate_id)->first();  
                    $kamscanidateId = $kams_canidateId->business_id;
                    $kams  = KeyAccountManager::where('business_id',$kamscanidateId)->get();
                   // dd($kams);
                    if (count($kams)>0) 
                    {
                      foreach ($kams as $kam) {
                        $candidates= CandidateReinitiate::where('id',$candidate_id)->first();
                        $user= User::where('id',$kam->user_id)->first();
                      
                        $email = $user->email;
                        $name  = $user->name;
                        $candidate_name = $candidates->name;
                        $reference_number = $candidates->display_id;
                        // $candidate_name = $request->input('first_name');
                       
                        $msg = "New JAF verification Task Created with candidate name";
                        $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                        $data  = array('name'=>$name,'email'=>$email,'serviceId'=>$serviceId,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
                        
                          // Mail::send(['html'=>'mails.task-notify-service'], $data, function($message) use($email,$name,$sender,$reference_number) {
                          //     $message->to($email, $name)->subject
                          //       ('myBCD System - Notification for JAF Filling Task ('.$reference_number.')');
                          //     $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                          // });

                          dispatch(new \App\Jobs\Admin\Task\NotifyService($data,$email,$name,$candidate_name,$reference_number,$frnid));
                        }
                    }  

                
                  // Notification for JAF Filled to Client
                  $candidate = DB::table('candidate_reinitiates')->where('id',$candidate_id)->first();
                  $company = DB::table('user_businesses')->select('company_name')->where(['business_id'=>$candidate->business_id])->first();
                  $sender = DB::table('users')->where(['id'=>$candidate->parent_id])->first();

                  $notification_controls = DB::table('notification_control_configs as nc')
                                            ->select('nc.*')
                                            ->join('notification_controls as n','n.business_id','=','nc.business_id')
                                            ->where(['n.status'=>1,'nc.status'=>1,'n.business_id'=>$candidate->business_id,'n.type'=>'jaf-filled','nc.type'=>'jaf-filled'])
                                            ->get();

                  if(count($notification_controls)>0)
                  {
                    foreach($notification_controls as $item)
                    {
                        $candidate_name = $candidate->name;
                        $reference_number = $candidate->display_id;
                        $frnid = $candidate->frnid ? $candidate->frnid.'-' : '';

                        $name = $item->name;
                        $email =  $item->email;
                        $company_name = $company->company_name;
                        $msg= 'Notification for Job Application Form Has Been Filled Successfully for Candidate ('.$candidate->name.' - '.$candidate->display_id.') at '.date('d-M-y h:i A').'';

                        $data  = array('name'=>$name,'email'=>$email,'company_name'=>$company_name,'sender'=>$sender,'msg'=>$msg);

                        // Mail::send(['html'=>'mails.jaf-filled'], $data, function($message) use($email,$name) {
                        //     $message->to($email, $name)->subject
                        //         ('myBCD System - JAF Notification');
                        //     $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        // });

                        dispatch(new \App\Jobs\Admin\Candidate\JafFilled($data,$email,$name,$candidate_name,$reference_number,$frnid));
                    }
                  }
            }
            // return redirect()->route('/user/thank-you');

            return response()->json([
              
              'success' => true,
              'status'  =>'first',
              'candidate_id' => base64_encode($candidate_id)
            ]);

          }
  
      }

      public function uploadFile(Request $request)
      {        
        //  echo count($request->file('files'));
        //  print_r($request->file('files')); 
        //  echo $request->input('service_id');
        //  die;
        // dd($request);
        $files=[];
        $i=0;
        $extensions = array("jpg","png","jpeg","PNG","JPG","JPEG","pdf");
        
        if($request->hasFile('files')) {

            foreach( $request->file('files') as $item){
            
                $result = array($request->file('files')[$i]->getClientOriginalExtension());

                if(in_array($result[0],$extensions))
                {                      
                    // $label_file_name  = $request->input('label_file_name');

                    $file_platform = 'web';

                    $s3_config = S3ConfigTrait::s3Config();
          
                    $attachment_file  = $request->file('files')[$i];
                    $orgi_file_name   = $attachment_file->getClientOriginalName();
                    
                    $fileName = pathinfo($orgi_file_name,PATHINFO_FILENAME);
          
                    $filename         = time().'-'.$fileName.'.'.$attachment_file->getClientOriginalExtension();
                    $dir              = public_path('/uploads/jaf-files/');            
                    $request->file('files')[$i]->move($dir, $filename);
                      
                    $candidate_id  = NULL;
                    $jaf_id  = NULL;
                    $is_temp         = 1;
                    $type            = 'main';
                    //check if report id 
                    if($request->has('jaf_id')) {
                        $candidate_id       = base64_decode($request->input('candidate_id'));
                        $jaf_id  = $request->input('jaf_id');
                        $business_id= $request->input('business_id'); 
                        $is_temp         = 0;
                        //get service item id
                        //  $type            = 'supporting';
                        //  if($request->has('type')){
                        //   $type           = $request->input('type');
                        //  }
                      }
                      // dd($candidate_id);
                    // $rowID = DB::table('jaf_item_attachments')            
                    //           ->insertGetId([
                    //               'jaf_id'        => $jaf_id, 
                    //               'business_id'   =>$business_id,    
                    //               'candidate_id' => $candidate_id,                 
                    //               'file_name'        => $filename,
                    //               'attachment_type'  => $type,
                    //               'updated_by'       => $candidate_id ,
                    //               'created_at'       => date('Y-m-d H:i:s'),
                    //               'is_temp'          => $is_temp,
                    //           ]);                                
          
                      // file type 
                      
                      $extArray = explode('.', $filename);
                      $ext = end($extArray);

                      $file_id_array = [];
                      $file_name_array = [];
                      $file_url_array = [];

                      if(stripos($ext,'pdf')!==false)
                      {
                        $file_platform = 'web';
                        if(File::exists($dir.$filename))
                        {
                            $pdf_file_name = $fileName.'-'.time();

                            $imagick = new Imagick();

                            $imagick->setResolution(300, 300);

                            $imagick->readImage($dir.$filename);

                            $imagick->setImageFormat("png");

                            $pages = $imagick->getNumberImages();

                            $imagick->writeImages($dir.$pdf_file_name.'.png', false);

                            if($pages)
                            {
                                if($pages==1)
                                {
                                  if($s3_config!=NULL)
                                  {
                                      $file_platform = 's3';

                                      $file_name = $pdf_file_name.'.png';

                                      $path = 'uploads/jaf-files/';

                                      if(!Storage::disk('s3')->exists($path))
                                      {
                                          Storage::disk('s3')->makeDirectory($path,0777, true, true);
                                      }

                                      $file = Helper::createFileObject($path.$file_name);

                                      Storage::disk('s3')->put($path.$file_name, file_get_contents($file));

                                  }
                                  $rowID = DB::table('jaf_item_attachments')
                                    ->insertGetId([
                                      'jaf_id'        => $jaf_id, 
                                      'business_id'   =>$business_id,    
                                      'candidate_id' => $candidate_id,                        
                                        'file_name'        => $pdf_file_name.'.png',
                                        'attachment_type'  => $type,
                                        'file_platform'     => $file_platform,
                                        'created_by'       => Auth::user()->id,
                                        'created_at'       => date('Y-m-d H:i:s'),
                                        'is_temp'          => $is_temp,
                                    ]);
                                    $report_count = DB::table('reports')->where(['candidate_id'=>$candidate_id])->count(); 
                                    if($report_count > 0){
                                      $report_items= DB::table('report_items')->where('jaf_id',$jaf_id)->first();
                                      if($report_items){
                                          // dd($report_items);
                                          File::copy(public_path('/uploads/jaf-files/'.$pdf_file_name.'.png'),public_path('/uploads/report-files/'.$pdf_file_name.'.png'));
                                          if(stripos($file_platform,'s3')!==false)
                                          {
                                            $path = 'uploads/jaf-files/';
                                            $report_path = 'uploads/report-files/';
                                            if(!Storage::disk('s3')->exists($report_path))
                                            {
                                                Storage::disk('s3')->makeDirectory($report_path,0777, true, true);
                                            }
  
                                            Storage::disk('s3')->copy($path.$pdf_file_name.'.png',$report_path.$pdf_file_name.'.png');
  
                                          }
                                        
                                          DB::table('report_item_attachments')->insert([
                                          'report_id'        => $report_items->report_id, 
                                          'report_item_id'   => $report_items->id,
                                          'jaf_item_attachment_id'=>$rowID,
                                          'file_name'        => $pdf_file_name.'.png',
                                          'attachment_type'  => $type,
                                          'file_platform'     => $file_platform,
                                          'created_by'       => Auth::user()->id,
                                          'created_at'       => date('Y-m-d H:i:s'),
                                          'is_temp'          => $is_temp,
                                        ]);
                                      }
                                    }
                                    $file_url = '';
                                    if(stripos($file_platform,'s3')!==false)
                                    {
                                      $filePath = 'uploads/jaf-files/';
                                      if(File::exists(public_path('/uploads/jaf-files/'.$pdf_file_name.'.png')))
                                      {
                                          File::delete(public_path('/uploads/jaf-files/'.$pdf_file_name.'.png'));
                                      }

                                      $disk = Storage::disk('s3');

                                      $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                          'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                          'Key'                        => $filePath.$pdf_file_name.'.png',
                                          'ResponseContentDisposition' => 'attachment;'//for download
                                      ]);

                                      $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                      $file_url = (string)$req->getUri();
                                    }
                                    else
                                    {
                                        $file_url = url('/').'/uploads/jaf-files/'.$pdf_file_name.'.png';
                                    }

                                    $file_id_array[] = $rowID;
                                    $file_name_array[] = $pdf_file_name.'.png';
                                    $file_url_array[] = $file_url;
                                }
                                else
                                {
                                    for($i=0;$i<$pages;$i++)
                                    {
                                        $file_platform = 'web';

                                        if($s3_config!=NULL)
                                        {
                                            $file_platform = 's3';

                                            $file_name = $pdf_file_name.'-'.$i.'.png';

                                            $path = 'uploads/jaf-files/';

                                            if(!Storage::disk('s3')->exists($path))
                                            {
                                                Storage::disk('s3')->makeDirectory($path,0777, true, true);
                                            }

                                            $file = Helper::createFileObject($path.$file_name);

                                            Storage::disk('s3')->put($path.$file_name, file_get_contents($file));

                                        }

                                        $rowID = DB::table('jaf_item_attachments')            
                                        ->insertGetId([
                                          'jaf_id'        => $jaf_id, 
                                          'business_id'   =>$business_id,    
                                          'candidate_id' => $candidate_id,                         
                                            'file_name'        => $pdf_file_name.'-'.$i.'.png',
                                            'attachment_type'  => $type,
                                            'file_platform'     => $file_platform,
                                            'created_by'       => Auth::user()->id,
                                            'created_at'       => date('Y-m-d H:i:s'),
                                            'is_temp'          => $is_temp,
                                        ]);
                                        $report_count = DB::table('reports')->where(['candidate_id'=>$candidate_id])->count(); 
                                        if($report_count > 0){
                                          $report_items= DB::table('report_items')->where('jaf_id',$jaf_id)->first();
                                          if($report_items){
                                              // dd($report_items);
                                              File::copy(public_path('/uploads/jaf-files/'.$pdf_file_name.'-'.$i.'.png'),public_path('/uploads/report-files/'.$pdf_file_name.'-'.$i.'.png'));
                                              if(stripos($file_platform,'s3')!==false)
                                              {
                                                $path = 'uploads/jaf-files/';
                                                $report_path = 'uploads/report-files/';
                                                if(!Storage::disk('s3')->exists($report_path))
                                                {
                                                    Storage::disk('s3')->makeDirectory($report_path,0777, true, true);
                                                }
  
                                                Storage::disk('s3')->copy($path.$pdf_file_name.'-'.$i.'.png',$report_path.$pdf_file_name.'-'.$i.'.png');
  
                                              }
                                              DB::table('report_item_attachments')->insert([
                                              'report_id'        => $report_items->report_id, 
                                              'report_item_id'   => $report_items->id,
                                              'jaf_item_attachment_id'=>$rowID,
                                              'file_name'        => $pdf_file_name.'-'.$i.'.png',
                                              'attachment_type'  => $type,
                                              'file_platform'     => $file_platform,
                                              'created_by'       => Auth::user()->id,
                                              'created_at'       => date('Y-m-d H:i:s'),
                                              'is_temp'          => $is_temp,
                                            ]);
                                          }
                                        }
                                        $file_url = '';
                                        if(stripos($file_platform,'s3')!==false)
                                        {
                                          $filePath = 'uploads/jaf-files/';
                                          if(File::exists(public_path('/uploads/jaf-files/'.$pdf_file_name.'-'.$i.'.png')))
                                          {
                                              File::delete(public_path('/uploads/jaf-files/'.$pdf_file_name.'-'.$i.'.png'));
                                          }

                                          $disk = Storage::disk('s3');

                                          $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                              'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                              'Key'                        => $filePath.$pdf_file_name.'-'.$i.'.png',
                                              'ResponseContentDisposition' => 'attachment;'//for download
                                          ]);

                                          $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                          $file_url = (string)$req->getUri();
                                        }
                                        else
                                        {
                                            $file_url = url('/').'/uploads/jaf-files/'.$pdf_file_name.'-'.$i.'.png';
                                        }

                                        $file_id_array[] = $rowID;
                                        $file_name_array[] = $pdf_file_name.'-'.$i.'.png';
                                        $file_url_array[] = $file_url;  
                                    }
                                }
                            }

                            File::delete($dir.$filename);

                        }
                      }
                      else
                      {

                        $file_platform = 'web';
                        if($s3_config!=NULL)
                        {
                            $file_platform = 's3';

                            $path = 'uploads/jaf-files/';

                            if(!Storage::disk('s3')->exists($path))
                            {
                                Storage::disk('s3')->makeDirectory($path,0777, true, true);
                            }

                            $file = Helper::createFileObject($path.$filename);

                            Storage::disk('s3')->put($path.$filename, file_get_contents($file));

                        }
                        $rowID = DB::table('jaf_item_attachments')            
                        ->insertGetId([
                            'jaf_id'        => $jaf_id, 
                            'business_id'   =>$business_id,    
                            'candidate_id' => $candidate_id,                   
                            'file_name'        => $filename,
                            'attachment_type'  => $type,
                            'file_platform'    => $file_platform,
                            'created_by'       => Auth::user()->id,
                            'created_at'       => date('Y-m-d H:i:s'),
                            'is_temp'          => $is_temp,
                        ]);
                        $report_count = DB::table('reports')->where(['candidate_id'=>$candidate_id])->count(); 
                        if($report_count > 0){
                          $report_items= DB::table('report_items')->where('jaf_id',$jaf_id)->first();
                          if($report_items){
                            File::copy(public_path('/uploads/jaf-files/'.$filename),public_path('/uploads/report-files/'.$filename)); 
                            if(stripos($file_platform,'s3')!==false)
                            {
                              $path = 'uploads/jaf-files/';
                              $report_path = 'uploads/report-files/';
                              if(!Storage::disk('s3')->exists($report_path))
                              {
                                  Storage::disk('s3')->makeDirectory($report_path,0777, true, true);
                              }
                              Storage::disk('s3')->copy($path.$filename,$report_path.$filename);
                            }
                            // $attachment= DB::table('report_items')->where('jaf_id',$jaf_id)->first();
                              DB::table('report_item_attachments')->insert([
                              'report_id'        => $report_items->report_id, 
                              'report_item_id'   => $report_items->id,
                              'jaf_item_attachment_id'=>$rowID,
                              'file_name'        => $filename,
                              'attachment_type'  => $type,
                              'file_platform'    => $file_platform,
                              'created_by'       => Auth::user()->id,
                              'created_at'       => date('Y-m-d H:i:s'),
                              'is_temp'          => $is_temp,
                            ]);
                          }
                        }
                        if(stripos($file_platform,'s3')!==false)
                        {
                            if(File::exists(public_path('/uploads/jaf-files/'.$filename)))
                            {
                                File::delete(public_path('/uploads/jaf-files/'.$filename));
                            }
                        }
                        
                      }
                      
                      $type = url('/').'/admin/images/file.jpg';
                        if($filename != NULL)
                        {
                            // if($ext == 'pdf')
                            // {
                            //   $type = url('/').'/admin/images/icon_pdf.png';
                            // } 
                            if($ext == 'doc' || $ext == 'docx')
                            {
                              $type = url('/').'/admin/images/icon_docx.png';
                            }
                            if($ext == 'xls' || $ext == 'xlsx' || $ext == 'csv')
                            {
                              $type = url('/').'/admin/images/icon_xlsx.png';
                            }
                            if($ext == 'pptx' || $ext == 'ppt')
                            {
                              $type = url('/').'/admin/images/icon_pptx.png';
                            }
                            if($ext == 'psd' || $ext == 'PSD')
                            {
                              $type = url('/').'/admin/images/icon_psd.png';
                            }
                            if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'JPG' || $ext == 'PNG' || $ext == 'JPEG'  || $ext == 'GIF')
                            {  
                              if(stripos($file_platform,'s3')!==false)
                              {
                                  $filePath = 'uploads/jaf-files/';

                                  $disk = Storage::disk('s3');

                                  $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                      'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                      'Key'                        => $filePath.$filename,
                                      //'ResponseContentDisposition' => 'attachment;'//for download
                                  ]);

                                  $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                  $type = (string)$req->getUri();  
                              }
                              else
                              {
                                $type = url('/').'/uploads/jaf-files/'.$filename;
                              } 
                            }
                            
                        }           
                        
                        //$files[] = ['filePrev'=> $type,'file_id'=>$rowID];

                        if($ext=='pdf')
                        {
                          $files[] = ['file_type'=>$ext,'filePrev'=> $file_url_array,'file_id'=>$file_id_array,'file_name'=>$file_name_array];
                        }
                        else
                        {
                          $files[] = ['file_type'=>$ext,'filePrev'=> $type,'file_id'=>$rowID,'file_name'=>$filename];
                        }

                    $i++;
                
                }else{
                      // Do something when it fails
                      return response()->json([
                          'fail' => true,
                          'errors' => 'File type error!'
                      ]);
                  }

            }

            //send file response
            return response()->json([
              'fail' => false,
              'errors' => 'no',
              'data'=>$files
            ]);
            
    
        }
 
      }

      /**
     * remove a resource .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

      public function removeFile(Request $request)
      {        
          $id =  $request->input('file_id');

          $is_done = DB::table('jaf_item_attachments')->where('id',$id)->update(['is_deleted'=>'1','deleted_at'=>date('Y-m-d H:i:s'),'deleted_by'=>Auth::user()->id]);
          DB::table('report_item_attachments')->where('jaf_item_attachment_id',$id)->update(['is_deleted'=>'1','deleted_at'=>date('Y-m-d H:i:s'),'deleted_by'=>Auth::user()->id]);

          // Do something when it fails
          return response()->json([
              'fail' => false,
              'message' => 'File removed!'
          ]);
      }

      public function thank_you()
      {
        return view('main-web.thank-you-candidate');
      }

      public function error()
      {
        return view('main-web.error404');
      }

      public function error403()
      {
        return view('main-web.permission-denied');
      }

      public function logout(Request $request)
      {
        Auth::guard('candidate')->logout();

        return redirect('/user_login');
      }

      public function fakeGenEduList(Request $request)
      {
          $data_result = [];

          $data = DB::table('fake_educational_lists')->where('status',1)->get();

          foreach($data as $d)
          {
              $data_result[] = ['id'=>$d->id,'name' => $d->board_or_university_name];
          }

          return response()->json($data_result);
      }

      public function fakeGenEmpList(Request $request)
      {
          $data_result = [];

          $data = DB::table('fake_employment_lists')->where('status',1)->get();

          foreach($data as $d)
          {
              $data_result[] = ['id'=>$d->id,'name' => $d->company_name];
          }

          return response()->json($data_result);
      }
}
