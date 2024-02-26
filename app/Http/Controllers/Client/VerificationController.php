<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use Validator;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Models\CourtCheckMasterV1;
use App\Models\CourtCheckV1;

class VerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $business_id = Auth::user()->business_id;
        $query = DB::table('candidate_reinitiates as u')
        ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
        ->join('job_items as j','j.candidate_id','=','u.id')        
        ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'is_deleted'=>'0','j.jaf_status'=>'filled']);

        // dd($query);
       
        $items =    $query->paginate(15); 

        return view('clients.verification.index',compact('items'));
    }


    public function idChecks()
    {
        $business_id = Auth::user()->business_id; 
        $services = DB::table('services')
                ->where(['verification_type'=>'Auto','status'=>1])
                // ->whereNotIn('type_name',['e_court'])
                ->get();

        // dd($services);

        // Check Whether An Admin Hide the Verification for Client
        $items = DB::table('customer_verification_showing_statuses')
                    ->where(['coc_id'=>$business_id])
                    ->where('shown_by','=',null)
                    ->first();
        if($items==null)
            return view('clients.verification.idcheck',compact('services'));
        else
            return redirect('/my/home');
    }

    public function termAccept(Request $request)
    {
        $parent_id=Auth::user()->parent_id;
        $business_id=Auth::user()->business_id;
        $user_id=Auth::user()->id;

        DB::table('verification_term_logs')->insert([
            'parent_id' => $parent_id,
            'business_id' => $business_id,
            'user_id'   => $user_id,
            'created_at'   => date('Y-m-d H:i:s')
        ]);

        echo "1";
    }

    /**
     * Show the id check Form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function idCheckForm(Request $request)
    {
        $service_id = $request->id;

        $form='';

        $services = DB::table('services')->where(['id'=>$service_id])->get();

        if(count($services)>0)
        {
           $aadhar_advance = '';

           $input='';

           $placeholder='';

           foreach($services as $item)
           {
               if($item->type_name=='covid_19_certificate')
               {
                   $placeholder = 'Enter The Mobile No.';
               }
               else if($item->type_name=='upi')
               {
                   $placeholder = 'Enter the UPI ID';
               }
               else if($item->type_name=='cin')
               {
                    $placeholder = 'Enter the CIN Number';
               }
               else if($item->type_name =='aadhaar_validation')
               {
                   $aadhar_advance.='<button type="button" class="btn btn-sm btn-info advance_check" data-service="'.base64_encode($item->id).'" ><i class="fa fa-hand-point-right"></i> Advance</button>';
               }

               if($item->type_name == 'passport')
               {
                   $input.='<div class="row">
                               <div class="col-md-6">
                                   <input type="text" name="" class="form-control passportNumber" placeholder="File No."> 
                                   <small class="text-muted" style="font-size:10px;">File No.</small>
                               </div>
                               <div class="col-md-6">
                                   <input type="text" name="" class="form-control commonDatepicker passportDOB" placeholder="DOB"> 
                                   <small class="text-muted" style="font-size:10px;">DOB (DD-MM-YYYY)</small>
                               </div>
                           </div>';
               }
               else if($item->type_name == 'bank_verification')
               {
                   $input.='<div class="row">
                               <div class="col-md-6">
                                   <input type="text" name="" class="form-control bankACNumber" placeholder="Bank AC No."> 
                                   <small class="text-muted" style="font-size:10px;">A/C Number</small>
                               </div>
                               <div class="col-md-6">
                                   <input type="text" name="" class="form-control  bankIFSC" placeholder="IFSC"> 
                                   <small class="text-muted" style="font-size:10px;">IFSC Code</small>
                               </div>
                           </div>';
               }
               else if($item->type_name == 'electricity')
               {
                   $input.='<div class="row">
                               <div class="col-md-6">
                                   <input type="text" name="" class="form-control electricNo" placeholder="CA No."> 
                                   <small class="text-muted" style="font-size:10px;">CA Number</small>
                               </div>
                               <div class="col-md-6">
                                   <input type="text" name="" class="form-control  electricOPCode" placeholder="Operator Code"> 
                                   <small class="text-muted" style="font-size:10px;">Operator Code</small>
                               </div>
                           </div>';
               }
               elseif($item->type_name == 'gstin')
               {
                   $input.='<div class="row">
                               <div class="col-md-6">
                                   <input type="text" name="" class="form-control gstNumber" placeholder="GSTIN Number"> 
                                   <small class="text-muted" style="font-size:10px;">GSTIN Number</small>
                               </div>
                               <div class="col-md-6">
                                   <select class="form-control  gstFilling" > 
                                       <option value="false">-Select-</option>
                                       <option value="false">No</option>
                                       <option value="true">Yes</option>
                                   </select>
                                   <small class="text-muted" style="font-size:10px;">Filling Record Needed?</small>
                               </div>
                           </div>';
               }
               elseif($item->type_name=='e_court')
               {
                   $input.='<div class="row">
                               <div class="col-md-6">
                                   <input type="text" name="" class="form-control name" placeholder="Enter Your name"> 
                                   <small class="text-muted" style="font-size:10px;">Name</small>
                               </div>
                               <div class="col-md-6">
                                   <input type="text" name="" class="form-control fathername" placeholder="Enter Your father name"> 
                                   <small class="text-muted" style="font-size:10px;">Father Name</small>
                               </div>
                               <div class="col-md-12 pt-2">
                                   <input type="text" name="" class="form-control address" placeholder="Enter Your address"> 
                                   <small class="text-muted" style="font-size:10px;">Address</small>
                               </div>
                           </div>';
               }
               elseif($item->type_name=='driving_license')
               {
                    $input.='<div class="row">
                                <div class="col-md-6">
                                    <input type="text" name="id_number" class="form-control drivingNumber" placeholder="DL No."> 
                                    <small class="text-muted" style="font-size:10px;">DL No.</small>
                                    <p class="error error-id_number" style="font-size:12px;color:red"></p>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="dob" class="form-control commonDatepicker drivingDOB" placeholder="DOB"> 
                                    <small class="text-muted" style="font-size:10px;">DOB (DD-MM-YYYY)</small>
                                    <p class="error error-dob" style="font-size:12px;color:red"></p>
                                </div>
                            </div>';
               }
               elseif($item->type_name=='court_check_v1')
               {
                    $input.='<div class="row">
                                <div class="col-md-6">
                                    <input type="text" name="" class="form-control name" placeholder="Enter Your name"> 
                                    <small class="text-muted" style="font-size:10px;">Name <b class="text-danger">*</b></small>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="" class="form-control fathername" placeholder="Enter Your father name"> 
                                    <small class="text-muted" style="font-size:10px;">Father Name</small>
                                </div>
                                <div class="col-md-12 pt-2">
                                    <input type="text" name="" class="form-control address" placeholder="Enter Your address"> 
                                    <small class="text-muted" style="font-size:10px;">Address</small>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control type"> 
                                        <option value="">-Select-</option>
                                        <option value="individual">Individual</option>
                                        <option value="entity">Entity</option>
                                    </select>
                                    <small class="text-muted" style="font-size:10px;">Type <b class="text-danger">*</b></small>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="" class="form-control state" placeholder="Enter Your State(ISO Code), ex:- MP"> 
                                    <small class="text-muted" style="font-size:10px;">State</small>
                                </div>
                            </div>';
               }
               else if($item->type_name == 'vital_4')
               {
                    $selected_value = '';
                    $country_name = '';
                    $vtal_categorye = '';
                    $vtal_cate = '';
                    $state_name = '';
                    $serviceVital = DB::table('service_vitals')
                                    ->where(['service_id'=>$item->id])
                                    ->where('parent_id',0)
                                    ->where('status',1)
                                    ->get();

                    $countries      = DB::table('countries')->get();
                    $state          = DB::table('states')->where('country_id','101')->get();
                    foreach($serviceVital as $servicev){
                        $vtal_cate.= '<option value="'.$servicev->id.'">'.$servicev->vital_name.'</option>';
                    }

                    foreach($countries as $country){
                        $selected_value = $country->id == 101 ? 'selected':'';
                        $country_name.= '<option value="'.$country->name.'" '.$selected_value.'>'.$country->name.'</option>';
                    }

                    foreach($state as $states){
                        $state_name.= '<option value="'.$states->id.'">'.$states->name.' </option>';
                    }
       
                    $input.='<div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted" style="font-size:10px;">Vital 4</small>
                                    <select class="form-control  vital" name"vital" id="vital"> 
                                        <option value="">-Select-</option>
                                        '.$vtal_cate.'
                                    </select>
                                </div>
                                <div class="col-md-6 vital_type">
                                    <div class="form-group">
                                        <span class="text-muted" style="font-size:10px;">Select Vital Categoryes <span class="text-danger">*</span></span> 
                                            <select class="select-option-field-7 role  form-control vital_list" name="vital_name[]" multiple id="vital_name"> 
                                                <option value="">-Select-</option>
                                            </select> 
                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-vital_name"></p> 
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted" style="font-size:10px;">Name.<span class="text-danger">*</span></small>
                                    <input type="text" name="subject" class="form-control subject" placeholder="Name."> 
                                    <p class="error error-subject" style="font-size:12px;color:red"></p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted" style="font-size:10px;">Id Number </small>
                                    <input type="text" name="subjectId" class="form-control subjectId" placeholder="Id Number"> 
                                    <p class="error error-subjectId" style="font-size:12px;color:red"></p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted" style="font-size:10px;">Country</small>
                                    <select class="form-control  country" name="country_id" id="country_id"> 
                                        <option value="">-Select-</option>
                                        '.$country_name.'
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted" style="font-size:10px;">State</small>
                                    <select class="form-control  state" name="state_id" id="state_id"> 
                                        <option value="">-Select-</option>
                                       '.$state_name.'
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted" style="font-size:10px;">City</small>
                                    <select class="form-control city_id" name="city_id" id="city_id"> 
                                        <option value="">-Select-</option>
                                        
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted" style="font-size:10px;">Date Of Birth (DD-MM-YYYY)</small>
                                    <input type="text" name="dateOfBirth" class="form-control dateOfBirth" placeholder="Date Of Birth"> 
                                    <p class="error error-dateOfBirth" style="font-size:12px;color:red"></p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted" style="font-size:10px;">Address Line 1.</small>
                                    <input type="text" name="address_line_1" class="form-control address_line_1" placeholder="Address Line 1."> 
                                    <p class="error error-address_line_1" style="font-size:12px;color:red"></p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted" style="font-size:10px;">Postal Code.</small>
                                    <input type="text" name="postal_code" class="form-control postal_code" placeholder="Postal Code."> 
                                    <p class="error error-postal_code" style="font-size:12px;color:red"></p>
                                </div>
                            </div>';
               }
               else
               {
                   $input.='<input type="text" name="" class="form-control IdNumber" placeholder="'.$placeholder.'"> ';
               }

               $form.='<tr>';

               $form.='<td> <b> '.$item->name.' </b>&nbsp;&nbsp; '.$aadhar_advance.' <br>
                                   <small class="text-muted">  </small>
                       </td>
                       <td>
                           '.$input.'
                           <span class="error" style="font-size:12px;color:red"></span>
                       </td>
                       <td>
                           <button type="button" class="btn btn-sm btn-info checkButton" id="'.$item->type_name.'" data-service="'.base64_encode($item->id).'" ><i class="fa fa-hand-point-right"></i> Go</button>
                       </td>';

               $form.='</tr>';
           }
        }
        else
        {
            $form.='<tr><td scope="row" colspan="7">
                       <h3 class="text-center">No record!</h3>
                   </td></tr>';
        }

        return response()->json([
           'form' => $form
        ]);
    }


     // check id - aadhar
     public function idCheckAadhar(Request $request)
     {        
         $business_id   = Auth::user()->business_id;
         $parent_id     = Auth::user()->parent_id;
         $user_id=Auth::user()->id;
         $service_id    = base64_decode($request->service_id);

         $price=20;

         DB::beginTransaction();
         try
         {
            $data = DB::table('check_price_cocs')->where(['service_id'=>$service_id,'coc_id'=>$business_id])->first();
                if($data!=NULL)
                {
                    $price=$data->price;
                }
                else{
                    $data=DB::table('check_prices')->where(['service_id'=>$service_id,'business_id'=>$parent_id])->first();
                    if($data!=NULL)
                    {
                        $price=$data->price;
                    }
                    // else{
                    //     $data=DB::table('check_price_masters')->where(['service_id'=>$service_id])->first();
                    //     if($data!=NULL)
                    //     {
                    //         $price=$data->price;
                    //     }
                    // }
                }

            if( $request->has('id_number') ) {
            
                $id_number=preg_match('/^((?!([0-1]))[0-9]{12})$/', $request->input('id_number'));
                if($request->input('id_number')=='' || !($id_number))
                {
                    return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'error'     =>"It seems like ID number is not valid!"
                    ]);
                }
                //check first into master table
                $master_data = DB::table('aadhar_check_masters')->select('*')->where(['aadhar_number'=>$request->input('id_number')])->first();
                
                if($master_data !=null){

                    
                    // store log
                    $check_data = [
                        'parent_id'         =>$parent_id,
                        'business_id'       =>$business_id,
                        'service_id'        =>$service_id,
                        'aadhar_number'     =>$master_data->aadhar_number,
                        'age_range'         =>$master_data->age_range,
                        'gender'            =>$master_data->gender,
                        'state'             =>$master_data->state,
                        'last_digit'        =>$master_data->last_digit,
                        'is_verified'       =>'1',
                        'is_aadhar_exist'   =>'1',
                        'used_by'           =>'coc',
                        'user_id'           => $user_id,
                        'source_reference'  =>'SystemDB',
                        'price'             =>$price,
                        'created_at'        =>date('Y-m-d H:i:s')
                    ]; 

                    DB::table('aadhar_checks')->insert($check_data);

                    DB::commit();
                    return response()->json([
                        'fail'      =>false,
                        'data'      =>$master_data 
                    ]);
                }
                else{
                    //check from live API
                    $api_check_status = false;
                    // Setup request to send json via POST
                    $data = array(
                        'id_number'    => $request->input('id_number'),
                        'async'         => true,
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
                        if($array_data['data']['state']==NULL || $array_data['data']['gender']==NULL || $array_data['data']['last_digits']==NULL)
                        {
                            return response()->json([
                                'fail'      =>true,
                                'error'     =>"yes",
                                'error_message'     =>"It seems like ID number is not valid!"
                            ]);
                        }

                        $checkIDInDB= DB::table('aadhar_check_masters')->where(['aadhar_number'=>$request->input('id_number')])->count();
                        if($checkIDInDB ==0)
                        {
                            $gender = NULL;
                            if($array_data['data']['gender'] == 'F'){
                                $gender = 'Female';
                            }
                            elseif($array_data['data']['gender'] == 'M')
                            {
                                $gender = 'Male';
                            }
                            elseif($array_data['data']['gender'] == 'O')
                            {
                                $gender = 'Others';
                            }
                            $data = [
                                    'parent_id'         =>$parent_id,
                                    'business_id'       =>$business_id,
                                    'aadhar_number'    =>$array_data['data']['aadhaar_number'],
                                    'age_range'         =>$array_data['data']['age_range'],
                                    'gender'            =>$gender,
                                    'state'             =>$array_data['data']['state'],
                                    'last_digit'        =>$array_data['data']['last_digits'],
                                    'is_verified'       =>'1',
                                    'is_aadhar_exist'   =>'1',
                                    'created_by'        => $user_id,
                                    'created_at'        =>date('Y-m-d H:i:s')
                                    ];
                            DB::table('aadhar_check_masters')->insert($data);
                                    
                            //insert into aadhar_checks table
                            $business_data = [
                                'parent_id'         =>$parent_id,
                                'business_id'       =>$business_id,
                                'service_id'        =>$service_id,
                                'aadhar_number'     =>$array_data['data']['aadhaar_number'],
                                'age_range'         =>$array_data['data']['age_range'],
                                'gender'            =>$gender,
                                'state'             =>$array_data['data']['state'],
                                'last_digit'        =>$array_data['data']['last_digits'],
                                'is_verified'       =>'1',
                                'is_aadhar_exist'   =>'1',
                                'used_by'           =>'coc',
                                'user_id'           =>$user_id,
                                'source_reference'  =>'API',
                                'price'             =>$price,
                                'created_at'        =>date('Y-m-d H:i:s')
                                ]; 
                            DB::table('aadhar_checks')->insert($business_data);
                            
                            $master_data = DB::table('aadhar_check_masters')->select('*')->where(['aadhar_number'=>$request->input('id_number')])->first();
    
    
                        }

                        DB::commit();
                        return response()->json([
                            'fail'      =>false,
                            'data'      =>$master_data 
                        ]);
    
                    }else{
                        return response()->json([
                            'fail'      =>true,
                            'error'     =>"yes",
                            'error'     =>"It seems like ID number is not valid!"
                        ]);
                    }
                    
                }
            }else{
                    return response()->json([
                    'fail'          =>true,
                    'error'         =>"yes",
                    'error_message' =>"It seems like ID number is not valid!",
                    
                ]); 
            }
         }
         catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
 
     }

     
     // check id - pan
    public function idCheckPan(Request $request)
    {        
        $parent_id=Auth::user()->parent_id;
        $business_id=Auth::user()->business_id;
        $user_id=Auth::user()->id;
        $service_id    = base64_decode($request->service_id);

        $price=20;
        DB::beginTransaction();
        try
        {

            $data = DB::table('check_price_cocs')->where(['service_id'=>$service_id,'coc_id'=>$business_id])->first();
                if($data!=NULL)
                {
                    $price=$data->price;
                }
                else{
                    $data=DB::table('check_prices')->where(['service_id'=>$service_id,'business_id'=>$parent_id])->first();
                    if($data!=NULL)
                    {
                        $price=$data->price;
                    }
                    // else{
                    //     $data=DB::table('check_price_masters')->where(['service_id'=>$service_id])->first();
                    //     if($data!=NULL)
                    //     {
                    //         $price=$data->price;
                    //     }
                    // }
                }
            if( $request->has('id_number') ) {
                
                $id_number=preg_match('/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{10}$/', $request->input('id_number'));
                if($request->input('id_number')=='' || !($id_number))
                {
                    return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'error'     =>"It seems like ID number is not valid!"
                    ]);
                }
                //check first into master table
                $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$request->input('id_number')])->first();
                
                if($master_data !=null){
                    $data = [
                        'parent_id'         =>$parent_id,
                        'category'          =>$master_data->category,
                        'pan_number'        =>$master_data->pan_number,
                        'full_name'         =>$master_data->full_name,
                        'is_verified'       =>'1',
                        'is_pan_exist'      =>'1',
                        'business_id'       => $business_id,
                        'service_id'        => $service_id,
                        'source_type'       => 'SystemDb',
                        'price'             =>$price,
                        'used_by'           =>'COC',
                        'user_id'            => $user_id,
                        'created_at'=>date('Y-m-d H:i:s')
                        ];
                
                    DB::table('pan_checks')->insert($data);
                    
                    DB::commit();
                    return response()->json([
                        'fail'      =>false,
                        'data'      =>$master_data 
                    ]);
                }
                else{
                    //check from live API
                    $api_check_status = false;

                    // Setup request to send json via POST
                    $data = array(
                        'id_number'    => $request->input('id_number'),
                        'async'         => true,
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
                        $checkIDInDB= DB::table('pan_check_masters')->where(['pan_number'=>$request->input('id_number')])->count();
                        if($checkIDInDB ==0)
                        {
                            $data = [
                                    'parent_id'         =>$parent_id,
                                    'business_id'       => $business_id,
                                    'category'=>$array_data['data']['category'],
                                    'pan_number'=>$array_data['data']['pan_number'],
                                    'full_name'=>$array_data['data']['full_name'],
                                    'is_verified'=>'1',
                                    'is_pan_exist'=>'1',
                                    'created_by'  => $user_id,
                                    'created_at'=>date('Y-m-d H:i:s')
                                    ];

                            DB::table('pan_check_masters')->insert($data);
                            
                            $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$request->input('id_number')])->first();

                            $data = [
                                'parent_id'         =>$parent_id,
                                'category'          =>$master_data->category,
                                'pan_number'        =>$master_data->pan_number,
                                'full_name'         =>$master_data->full_name,
                                'is_verified'       =>'1',
                                'is_pan_exist'      =>'1',
                                'business_id'       => $business_id,
                                'service_id'        => $service_id,
                                'source_type'       =>'API',
                                'price'             =>$price,
                                'used_by'           =>'COC',
                                'user_id'            => $user_id,
                                'created_at'=>date('Y-m-d H:i:s')
                                ];
                        
                            DB::table('pan_checks')->insert($data);
            
                        }
                        DB::commit();
                        return response()->json([
                            'fail'      =>false,
                            'data'      =>$master_data 
                        ]);

                    }else{
                        return response()->json([
                            'fail'      =>true,
                            'error'     =>"yes",
                            'error'     =>"It seems like ID number is not valid!"
                        ]);
                    }
                    
                }

            }else{
                    return response()->json([
                    'fail'          =>true,
                    'error'         =>"yes",
                    'error_message' =>"It seems like ID number is not valid!",
                    
                ]); 
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }

    }

     // check id - Voter ID
     public function idCheckVoterID(Request $request)
     {        
            $parent_id=Auth::user()->parent_id;
            $business_id=Auth::user()->business_id;
            $user_id = Auth::user()->id;
            $service_id    = base64_decode($request->service_id);

            $price=20;

            DB::beginTransaction();
            try
            {
                $data = DB::table('check_price_cocs')->where(['service_id'=>$service_id,'coc_id'=>$business_id])->first();
                if($data!=NULL)
                {
                    $price=$data->price;
                }
                else{
                    $data=DB::table('check_prices')->where(['service_id'=>$service_id,'business_id'=>$parent_id])->first();
                    if($data!=NULL)
                    {
                        $price=$data->price;
                    }
                    // else{
                    //     $data=DB::table('check_price_masters')->where(['service_id'=>$service_id])->first();
                    //     if($data!=NULL)
                    //     {
                    //         $price=$data->price;
                    //     }
                    // }
                }
                if( $request->has('id_number') ) {
                    $id_number=preg_match('/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{10}$/', $request->input('id_number'));
                    if($request->input('id_number')=='' || !($id_number))
                    {
                        return response()->json([
                            'fail'      =>true,
                            'error'     =>"yes",
                            'error'     =>"It seems like ID number is not valid!"
                        ]);
                    }
                    //check first into master table
                    $master_data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$request->input('id_number')])->first();
                    if($master_data !=null){
                        $data = $master_data;
                        $log_data = [
                            'parent_id'         =>$parent_id,
                            'api_client_id'     =>$master_data->api_client_id,
                            'relation_type'     =>$master_data->relation_type,
                            'voter_id_number'   =>$master_data->voter_id_number,
                            'relation_name'     =>$master_data->relation_name,
                            'full_name'         =>$master_data->full_name,
                            'gender'            =>$master_data->gender,
                            'age'               =>$master_data->age,
                            'dob'               =>$master_data->dob,
                            'house_no'          =>$master_data->house_no,
                            'area'              =>$master_data->area,
                            'state'             =>$master_data->state,
                            'is_verified'       =>'1',
                            'is_voter_id_exist' =>'1',
                            'business_id'       =>$business_id,
                            'service_id'        =>$service_id,
                            'source_reference'  =>'SystemDb',
                            'price'             =>$price,
                            'used_by'           =>'COC',
                            'user_id'            => $user_id,
                            'created_at'        =>date('Y-m-d H:i:s')
                            ];

                        DB::table('voter_id_checks')->insert($log_data);
                        
                        DB::commit();
                        return response()->json([
                            'fail'      =>false,
                            'data'      =>$master_data 
                        ]);
                    }
                    else{
                        //check from live API
                        // Setup request to send json via POST
                        $data = array(
                            'id_number'    => $request->input('id_number'),
                            'async'         => true,
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
                            $checkIDInDB= DB::table('voter_id_check_masters')->where(['voter_id_number'=>$request->input('id_number')])->count();
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
                                        'parent_id'         =>$parent_id,
                                        'business_id'       =>$business_id,
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
                                        'is_verified'       =>'1',
                                        'is_voter_id_exist' =>'1',
                                        'created_by'            => $user_id,
                                        'created_at'        =>date('Y-m-d H:i:s')
                                        ];
                                DB::table('voter_id_check_masters')->insert($data);
                                
                                $master_data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$request->input('id_number')])->first();

                                $log_data = [
                                    'parent_id'         =>$parent_id,
                                    'api_client_id'     =>$master_data->api_client_id,
                                    'relation_type'     =>$master_data->relation_type,
                                    'voter_id_number'   =>$master_data->voter_id_number,
                                    'relation_name'     =>$master_data->relation_name,
                                    'full_name'         =>$master_data->full_name,
                                    'gender'            =>$master_data->gender,
                                    'age'               =>$master_data->age,
                                    'dob'               =>$master_data->dob,
                                    'house_no'          =>$master_data->house_no,
                                    'area'              =>$master_data->area,
                                    'state'             =>$master_data->state,
                                    'is_verified'       =>'1',
                                    'is_voter_id_exist' =>'1',
                                    'business_id'       =>$business_id,
                                    'service_id'        =>$service_id,
                                    'source_reference'  =>'API',
                                    'price'             =>$price,
                                    'used_by'           =>'COC',
                                    'user_id'            => $user_id,
                                    'created_at'        =>date('Y-m-d H:i:s')
                                    ];
                
                                DB::table('voter_id_checks')->insert($log_data);
                            }
                            
                            DB::commit();
                            return response()->json([
                                'fail'      =>false,
                                'data'      =>$master_data 
                            ]);
        
                        }else{
                            return response()->json([
                                'fail'      =>true,
                                'error'     =>"yes",
                                'error'     =>"It seems like ID number is not valid!"
                            ]);
                        }
                        
                    }
        
                }else{
                        return response()->json([
                        'fail'          =>true,
                        'error'         =>"yes",
                        'error_message' =>"It seems like ID number is not valid!",
                        
                    ]); 
                }
            }
            catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return $e;
            } 
 
     }

      // check id - RC
    public function idCheckRC(Request $request)
    { 
        $parent_id=Auth::user()->parent_id;       
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $service_id    = base64_decode($request->service_id);

        $price=20;
        DB::beginTransaction();
        try{
            $data = DB::table('check_price_cocs')->where(['service_id'=>$service_id,'coc_id'=>$business_id])->first();
            if($data!=NULL)
            {
                $price=$data->price;
            }
            else{
                $data=DB::table('check_prices')->where(['service_id'=>$service_id,'business_id'=>$parent_id])->first();
                if($data!=NULL)
                {
                    $price=$data->price;
                }
                // else{
                //     $data=DB::table('check_price_masters')->where(['service_id'=>$service_id])->first();
                //     if($data!=NULL)
                //     {
                //         $price=$data->price;
                //     }
                // }
            }

            if( $request->has('id_number') ) {
                $id_number=preg_match('/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{8,}$/', $request->input('id_number'));
                if($request->input('id_number')=='' || !($id_number))
                {
                    return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'error'     =>"It seems like ID number is not valid!"
                    ]);
                }
                //check first into master table
                $master_data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$request->input('id_number')])->first();
                if($master_data !=null){
                    $data = $master_data;

                    $log_data = [
                        'parent_id'         =>$parent_id,
                        'business_id'       => $business_id,
                        'service_id'        =>$service_id,
                        'source_type'       => 'SystemDb',
                        'api_client_id'     =>$master_data->api_client_id,
                        'rc_number'         =>$master_data->rc_number,
                        'registration_date' =>$master_data->registration_date,
                        'owner_name'        =>$master_data->owner_name,
                        'present_address'   =>$master_data->present_address,
                        'permanent_address'    =>$master_data->permanent_address,
                        'mobile_number'        =>$master_data->mobile_number,
                        'vehicle_category'     =>$master_data->vehicle_category,
                        'vehicle_chasis_number' =>$master_data->vehicle_chasis_number,
                        'vehicle_engine_number' =>$master_data->vehicle_engine_number,
                        'maker_description'     =>$master_data->maker_description,
                        'maker_model'           =>$master_data->maker_model,
                        'body_type'             =>$master_data->body_type,
                        'fuel_type'             =>$master_data->fuel_type,
                        'color'                 =>$master_data->color,
                        'norms_type'            =>$master_data->norms_type,
                        'fit_up_to'             =>$master_data->fit_up_to,
                        'financer'              =>$master_data->financer,
                        'insurance_company'     =>$master_data->insurance_company,
                        'insurance_policy_number'=>$master_data->insurance_policy_number,
                        'insurance_upto'         =>$master_data->insurance_upto,
                        'manufacturing_date'     =>$master_data->manufacturing_date,
                        'registered_at'          =>$master_data->registered_at,
                        'latest_by'              =>$master_data->latest_by,
                        'less_info'              =>$master_data->less_info,
                        'tax_upto'               =>$master_data->tax_upto,
                        'cubic_capacity'         =>$master_data->cubic_capacity,
                        'vehicle_gross_weight'   =>$master_data->vehicle_gross_weight,
                        'no_cylinders'           =>$master_data->no_cylinders,
                        'seat_capacity'          =>$master_data->seat_capacity,
                        'sleeper_capacity'       =>$master_data->sleeper_capacity,
                        'standing_capacity'      =>$master_data->standing_capacity,
                        'wheelbase'              =>$master_data->wheelbase,
                        'unladen_weight'         =>$master_data->unladen_weight,
                        'vehicle_category_description'         =>$master_data->vehicle_category_description,
                        'pucc_number'               =>$master_data->pucc_number,
                        'pucc_upto'                 =>$master_data->pucc_upto,
                        'masked_name'           =>$master_data->masked_name,
                        'is_verified'           =>'1',
                        'is_rc_exist'           =>'1',
                        'price'             =>$price,
                        'used_by'               =>'COC',
                        'user_id'                =>  $user_id,
                        'created_at'            =>date('Y-m-d H:i:s')
                        ];

                        DB::table('rc_checks')->insert($log_data);
                        DB::commit();
                    return response()->json([
                        'fail'      =>false,
                        'data'      =>$master_data 
                    ]);
                }
                else{
                    //check from live API
                    // Setup request to send json via POST
                    $data = array(
                        'id_number'    => $request->input('id_number'),
                        'async'         => true,
                    );
                    $payload = json_encode($data);
                    $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/rc/rc";

                    

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
                        $checkIDInDB= DB::table('rc_check_masters')->where(['rc_number'=>$request->input('id_number')])->count();
                        if($checkIDInDB ==0)
                        {
                        
                            $data = [
                                    'parent_id'         =>$parent_id,
                                    'business_id'       => $business_id,
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
                                    'is_verified'           =>'1',
                                    'is_rc_exist'           =>'1',
                                    'created_by'                =>  $user_id,
                                    'created_at'            =>date('Y-m-d H:i:s')
                                    ];

                            DB::table('rc_check_masters')->insert($data);
                            
                            $master_data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$request->input('id_number')])->first();

                            $log_data = [
                                'parent_id'         =>$parent_id,
                                'business_id'       => $business_id,
                                'service_id'        =>$service_id,
                                'source_type'       => 'API',
                                'api_client_id'     =>$master_data->api_client_id,
                                'rc_number'         =>$master_data->rc_number,
                                'registration_date' =>$master_data->registration_date,
                                'owner_name'        =>$master_data->owner_name,
                                'present_address'   =>$master_data->present_address,
                                'permanent_address'    =>$master_data->permanent_address,
                                'mobile_number'        =>$master_data->mobile_number,
                                'vehicle_category'     =>$master_data->vehicle_category,
                                'vehicle_chasis_number' =>$master_data->vehicle_chasis_number,
                                'vehicle_engine_number' =>$master_data->vehicle_engine_number,
                                'maker_description'     =>$master_data->maker_description,
                                'maker_model'           =>$master_data->maker_model,
                                'body_type'             =>$master_data->body_type,
                                'fuel_type'             =>$master_data->fuel_type,
                                'color'                 =>$master_data->color,
                                'norms_type'            =>$master_data->norms_type,
                                'fit_up_to'             =>$master_data->fit_up_to,
                                'financer'              =>$master_data->financer,
                                'insurance_company'     =>$master_data->insurance_company,
                                'insurance_policy_number'=>$master_data->insurance_policy_number,
                                'insurance_upto'         =>$master_data->insurance_upto,
                                'manufacturing_date'     =>$master_data->manufacturing_date,
                                'registered_at'          =>$master_data->registered_at,
                                'latest_by'              =>$master_data->latest_by,
                                'less_info'              =>$master_data->less_info,
                                'tax_upto'               =>$master_data->tax_upto,
                                'cubic_capacity'         =>$master_data->cubic_capacity,
                                'vehicle_gross_weight'   =>$master_data->vehicle_gross_weight,
                                'no_cylinders'           =>$master_data->no_cylinders,
                                'seat_capacity'          =>$master_data->seat_capacity,
                                'sleeper_capacity'       =>$master_data->sleeper_capacity,
                                'standing_capacity'      =>$master_data->standing_capacity,
                                'wheelbase'              =>$master_data->wheelbase,
                                'unladen_weight'         =>$master_data->unladen_weight,
                                'vehicle_category_description'         =>$master_data->vehicle_category_description,
                                'pucc_number'               =>$master_data->pucc_number,
                                'pucc_upto'                 =>$master_data->pucc_upto,
                                'masked_name'           =>$master_data->masked_name,
                                'is_verified'           =>'1',
                                'is_rc_exist'           =>'1',
                                'price'             =>$price,
                                'used_by'               =>'COC',
                                'user_id'                =>  $user_id,
                                'created_at'            =>date('Y-m-d H:i:s')
                                ];
            
                                DB::table('rc_checks')->insert($log_data);
                        }
                        DB::commit();
                        return response()->json([
                            'fail'      =>false,
                            'data'      =>$master_data 
                        ]);

                    }else{
                        return response()->json([
                            'fail'      =>true,
                            'error'     =>"yes",
                            'error'     =>"It seems like ID number is not valid!"
                        ]);
                    }
                    
                }

            }else{
                    return response()->json([
                    'fail'          =>true,
                    'error'         =>"yes",
                    'error_message' =>"It seems like ID number is not valid!",
                    
                ]); 
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 

    }


    // check id - Passport
    public function idCheckPassport(Request $request)
    {
        $parent_id=Auth::user()->parent_id;        
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $service_id    = base64_decode($request->service_id);

        $price=20.00;

        DB::beginTransaction();
        try{
            $data = DB::table('check_price_cocs')->where(['service_id'=>$service_id,'coc_id'=>$business_id])->first();
            if($data!=NULL)
            {
                $price=$data->price;
            }
            else{
                $data=DB::table('check_prices')->where(['service_id'=>$service_id,'business_id'=>$parent_id])->first();
                if($data!=NULL)
                {
                    $price=$data->price;
                }
                // else{
                //     $data=DB::table('check_price_masters')->where(['service_id'=>$service_id])->first();
                //     if($data!=NULL)
                //     {
                //         $price=$data->price;
                //     }
                // }
            }
            if( $request->has('id_number') ) {
                $id_number=preg_match('/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{15,}$/', $request->input('id_number'));
                if($request->input('id_number')=='' || !($id_number))
                {
                    return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'error'     =>"It seems like ID number is not valid!"
                    ]);
                }
                $passport_file_no = $request->input('id_number');

                //check first into master table
                $master_data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$passport_file_no])->first();
                if($master_data !=null){

                    $log_data = [
                        'parent_id'         =>$parent_id,
                        'business_id'       =>$business_id,
                        'service_id'        =>$service_id,
                        'source_type'       =>'SystemDb',
                        'api_client_id'     =>$master_data->api_client_id,
                        'passport_number'   =>$master_data->passport_number,
                        'full_name'         =>$master_data->full_name,
                        'file_number'       =>$master_data->file_number,
                        'dob'               => $master_data->dob,
                        'date_of_application'=>$master_data->date_of_application,
                        'is_verified'       =>'1',
                        'is_passport_exist' =>'1',
                        'price'             =>$price,
                        'used_by'           => 'COC',
                        'user_id'            => $user_id,
                        'created_at'        =>date('Y-m-d H:i:s')
                        ];

                    DB::table('passport_checks')->insert($log_data);

                    DB::commit();
                    return response()->json([
                        'fail'      =>false,
                        'data'      =>$master_data 
                    ]);
                }
                else{
                    //check from live API
                    // Setup request to send json via POST
                    $data = array(
                        'id_number' => $request->input('id_number'),
                        'dob'       => date('Y-m-d',strtotime($request->input('dob'))),
                        'async'         => true,
                    );
                    $payload = json_encode($data);
                    $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/passport/passport/passport-details";

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                    curl_setopt ($ch, CURLOPT_POST, 1);
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
                        //check if ID number is new then insert into DB
                        $checkIDInDB= DB::table('passport_check_masters')->where(['file_number'=>$passport_file_no])->count();
                        if($checkIDInDB ==0)
                        {
                            
                            $data = [
                                    'parent_id'         =>$parent_id,
                                    'business_id'       =>$business_id,
                                    'api_client_id'     =>$array_data['data']['client_id'],
                                    'passport_number'   =>$array_data['data']['passport_number'],
                                    'full_name'         =>$array_data['data']['full_name'],
                                    'file_number'       =>$array_data['data']['file_number'],
                                    'date_of_application'=>$array_data['data']['date_of_application'],
                                    'is_verified'       =>'1',
                                    'is_passport_exist' =>'1',
                                    'created_by'            => $user_id,
                                    'created_at'        =>date('Y-m-d H:i:s')
                                    ];

                            DB::table('passport_check_masters')->insert($data);
                            
                            $master_data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$passport_file_no])->first();

                            $log_data = [
                                'parent_id'         =>$parent_id,
                                'business_id'       =>$business_id,
                                'service_id'        =>$service_id,
                                'source_type'       => 'API',
                                'api_client_id'     =>$master_data->api_client_id,
                                'passport_number'   =>$master_data->passport_number,
                                'full_name'         =>$master_data->full_name,
                                'file_number'       =>$master_data->file_number,
                                'dob'               => $master_data->dob,
                                'date_of_application'=>$master_data->date_of_application,
                                'is_verified'       =>'1',
                                'is_passport_exist' =>'1',
                                'price'             =>$price,
                                'used_by'           => 'COC',
                                'user_id'            => $user_id,
                                'created_at'        =>date('Y-m-d H:i:s')
                                ];
            
                            DB::table('passport_checks')->insert($log_data);
                        }

                        DB::commit();
                        return response()->json([
                            'fail'      =>false,
                            'data'      =>$master_data 
                        ]);

                    }else{
                        return response()->json([
                            'fail'      =>true,
                            'error'     =>"yes",
                            'error'     =>"It seems like ID number is not valid!"
                        ]);
                    }
                    
                }

            }else{
                    return response()->json([
                    'fail'          =>true,
                    'error'         =>"yes",
                    'error_message' =>"It seems like ID number is not valid!",
                    
                ]); 
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }

    }

     // check id - DL
     public function idCheckDL(Request $request)
     {
        $parent_id=Auth::user()->parent_id;        
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $service_id=base64_decode($request->service_id);

        $price=20;

        DB::beginTransaction();
        try
        {
            $data = DB::table('check_price_cocs')->where(['service_id'=>$service_id,'coc_id'=>$business_id])->first();
            if($data!=NULL)
            {
                $price=$data->price;
            }
            else{
                $data=DB::table('check_prices')->where(['service_id'=>$service_id,'business_id'=>$parent_id])->first();
                if($data!=NULL)
                {
                    $price=$data->price;
                }
                // else{
                //     $data=DB::table('check_price_masters')->where(['service_id'=>$service_id])->first();
                //     if($data!=NULL)
                //     {
                //         $price=$data->price;
                //     }
                // }
            }
            if( $request->has('id_number') ) {
            
                $dl_number      = $request->input('id_number');
                $dl_raw         = preg_replace('/[^A-Za-z0-9\ ]/', '', $dl_number);
                $final_dl_number   = str_replace(' ', '', $dl_raw);

                $id_number=preg_match('/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{15,}$/', $final_dl_number);
                if($request->input('id_number')=='' || !($id_number))
                {
                    return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'error'     =>"It seems like ID number is not valid!"
                    ]);
                }
                //check first into master table
                $master_data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$final_dl_number])->first();
                
                if($master_data !=null){

                    $log_data = [
                        'parent_id'         =>$parent_id,
                        'business_id'       => $business_id,
                        'service_id'        => $service_id,
                        'source_type'       =>'SystemDb',
                        'api_client_id'     =>$master_data->api_client_id,
                        'dl_number'         =>$master_data->dl_number,
                        'name'              =>$master_data->name,
                        'permanent_address' =>$master_data->permanent_address,
                        'temporary_address' =>$master_data->temporary_address,
                        'permanent_zip'     =>$master_data->permanent_zip,
                        'temporary_zip'     =>$master_data->temporary_zip,
                        'state'             =>$master_data->state,
                        'citizenship'       =>$master_data->citizenship,
                        'ola_name'          =>$master_data->ola_name,
                        'ola_code'          =>$master_data->ola_code,
                        'gender'            =>$master_data->gender,
                        'father_or_husband_name' =>$master_data->father_or_husband_name,
                        'dob'               =>$master_data->dob,
                        'doe'               =>$master_data->doe,
                        'transport_doe'     =>$master_data->transport_doe,
                        'doi'               =>$master_data->doi,
                        'is_verified'       =>'1',
                        'is_rc_exist'       =>'1',
                        'price'             =>$price,
                        'used_by'           =>'COC',
                        'user_id'            =>$user_id,
                        'created_at'        =>date('Y-m-d H:i:s')
                        ];
                    
                    DB::table('dl_checks')->insert($log_data);

                    DB::commit();
                    return response()->json([
                        'fail'      =>false,
                        'data'      =>$master_data 
                    ]);
                }
                else{
                    //check from live API
                    // Setup request to send json via POST
                    $data = array(
                        'id_number'    => $request->input('id_number'),
                        'async'         => true,
                    );
                    $payload = json_encode($data);
                    $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/driving-license/driving-license";
    
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
                        $checkIDInDB= DB::table('dl_check_masters')->where(['dl_number'=>$request->input('id_number')])->count();
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
                                    'parent_id'         =>$parent_id,
                                    'business_id'       => $business_id,
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
                                    'is_verified'       =>'1',
                                    'is_rc_exist'       =>'1',
                                    'created_by'            =>$user_id,
                                    'created_at'        =>date('Y-m-d H:i:s')
                                    ];
                                
                                DB::table('dl_check_masters')->insert($data);
                            
                            $master_data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$final_dl_number])->first();

                            $log_data = [
                                'parent_id'         =>$parent_id,
                                'business_id'       => $business_id,
                                'service_id'        =>$service_id,
                                'source_type'       =>'API',
                                'api_client_id'     =>$master_data->api_client_id,
                                'dl_number'         =>$master_data->dl_number,
                                'name'              =>$master_data->name,
                                'permanent_address' =>$master_data->permanent_address,
                                'temporary_address' =>$master_data->temporary_address,
                                'permanent_zip'     =>$master_data->permanent_zip,
                                'temporary_zip'     =>$master_data->temporary_zip,
                                'state'             =>$master_data->state,
                                'citizenship'       =>$master_data->citizenship,
                                'ola_name'          =>$master_data->ola_name,
                                'ola_code'          =>$master_data->ola_code,
                                'gender'            =>$master_data->gender,
                                'father_or_husband_name' =>$master_data->father_or_husband_name,
                                'dob'               =>$master_data->dob,
                                'doe'               =>$master_data->doe,
                                'transport_doe'     =>$master_data->transport_doe,
                                'doi'               =>$master_data->doi,
                                'is_verified'       =>'1',
                                'is_rc_exist'       =>'1',
                                'price'             =>$price,
                                'used_by'           =>'COC',
                                'user_id'            =>$user_id,
                                'created_at'        =>date('Y-m-d H:i:s')
                                ];
                            
                            DB::table('dl_checks')->insert($log_data);
                        }
                        
                        DB::commit();
                        return response()->json([
                            'fail'      =>false,
                            'data'      =>$master_data 
                        ]);
    
                    }else{
                        return response()->json([
                            'fail'      =>true,
                            'error'     =>"yes",
                            'error'     =>"It seems like ID number is not valid!"
                        ]);
                    }
                    
                }
    
            }else{
                    return response()->json([
                    'fail'          =>true,
                    'error'         =>"yes",
                    'error_message' =>"It seems like ID number is not valid!",
                    
                ]); 
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
 
     }

      // check id - GSTIN

    public function idCheckGSTIN(Request $request)
    {
        $parent_id=Auth::user()->parent_id;
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $service_id=base64_decode($request->service_id);  
        
        $price=20;
        DB::beginTransaction();
        try{
            $data = DB::table('check_price_cocs')->where(['service_id'=>$service_id,'coc_id'=>$business_id])->first();
            if($data!=NULL)
            {
                $price=$data->price;
            }
            else{
                $data=DB::table('check_prices')->where(['service_id'=>$service_id,'business_id'=>$parent_id])->first();
                if($data!=NULL)
                {
                    $price=$data->price;
                }
                // else{
                //     $data=DB::table('check_price_masters')->where(['service_id'=>$service_id])->first();
                //     if($data!=NULL)
                //     {
                //         $price=$data->price;
                //     }
                // }
            }
            if( $request->has('id_number') ) {
                
                $gstin_number = $request->input('id_number');
                $filling_status = $request->input('filling_status');

                $id_number=preg_match('/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{15,}$/', $gstin_number);
                if($request->input('id_number')=='' || !($id_number))
                {
                    return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'error'     =>"It seems like ID number is not valid!"
                    ]);
                }
                //check first into master table
                $master_data = DB::table('gst_check_masters')->select('*')->where(['gst_number'=>$gstin_number])->first();
                if($master_data !=null){

                    $log_data = [
                        'parent_id'         =>$parent_id,
                        'business_id'           =>$business_id,
                        'service_id'            => $service_id,
                        'source_type'           => 'SystemDb',
                        'api_client_id'         =>$master_data->api_client_id,
                        'gst_number'            =>$master_data->gst_number,
                        'business_name'         =>$master_data->business_name,
                        'legal_name'            =>$master_data->legal_name,
                        'center_jurisdiction'   =>$master_data->center_jurisdiction,
                        'date_of_registration'  =>$master_data->date_of_registration,
                        'constitution_of_business'=>$master_data->constitution_of_business,
                        'field_visit_conducted'   =>$master_data->field_visit_conducted,
                        'taxpayer_type'         =>$master_data->taxpayer_type,
                        'gstin_status'          =>$master_data->gstin_status,
                        'date_of_cancellation'  =>$master_data->date_of_cancellation,
                        'address'               =>$master_data->address,
                        'is_verified'           =>'1',
                        'price'             =>$price,
                        'used_by'               =>'coc',
                        'user_id'                =>$user_id,
                        'created_at'            =>date('Y-m-d H:i:s')
                        ];

                    DB::table('gst_checks')->insert($log_data);


                    DB::commit();
                    return response()->json([
                        'fail'      =>false,
                        'data'      =>$master_data 
                    ]);
                }
                else{
                    //check from live API
                    // Setup request to send json via POST
                    $data = array(
                        'id_number'         => $request->input('id_number'),
                        'filing_status_get' => $filling_status,
                        'async'         => true,
                    );
                    $payload = json_encode($data);
                    $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/corporate/gstin";

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                    curl_setopt ($ch, CURLOPT_POST, 1);
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
                        //check if ID number is new then insert into DB
                        $checkIDInDB= DB::table('gst_check_masters')->where(['gst_number'=>$gstin_number])->count();
                        if($checkIDInDB ==0)
                        {
                            $data = [
                                    'parent_id'         =>$parent_id,
                                    'business_id'           => $business_id,
                                    'api_client_id'         =>$array_data['data']['client_id'],
                                    'gst_number'            =>$array_data['data']['gstin'],
                                    'business_name'         =>$array_data['data']['business_name'],
                                    'legal_name'            =>$array_data['data']['legal_name'],
                                    'center_jurisdiction'   =>$array_data['data']['center_jurisdiction'],
                                    'date_of_registration'  =>$array_data['data']['date_of_registration'],
                                    'constitution_of_business'=>$array_data['data']['constitution_of_business'],
                                    'field_visit_conducted'   =>$array_data['data']['field_visit_conducted'],
                                    'taxpayer_type'         =>$array_data['data']['taxpayer_type'],
                                    'gstin_status'          =>$array_data['data']['gstin_status'],
                                    'date_of_cancellation'  =>$array_data['data']['date_of_cancellation'],
                                    'address'               =>$array_data['data']['address'],
                                    'is_verified'           =>'1',
                                    'created_by'            =>$user_id,
                                    'created_at'            =>date('Y-m-d H:i:s')
                                    ];

                                DB::table('gst_check_masters')->insert($data);
                            
                            $master_data = DB::table('gst_check_masters')->select('*')->where(['gst_number'=>$gstin_number])->first();

                            $log_data = [
                                'parent_id'         =>$parent_id,
                                'business_id'           => $business_id,
                                'service_id'            => $service_id,
                                'source_type'           => 'API',
                                'api_client_id'         =>$master_data->api_client_id,
                                'gst_number'            =>$master_data->gst_number,
                                'business_name'         =>$master_data->business_name,
                                'legal_name'            =>$master_data->legal_name,
                                'center_jurisdiction'   =>$master_data->center_jurisdiction,
                                'date_of_registration'  =>$master_data->date_of_registration,
                                'constitution_of_business'=>$master_data->constitution_of_business,
                                'field_visit_conducted'   =>$master_data->field_visit_conducted,
                                'taxpayer_type'         =>$master_data->taxpayer_type,
                                'gstin_status'          =>$master_data->gstin_status,
                                'date_of_cancellation'  =>$master_data->date_of_cancellation,
                                'address'               =>$master_data->address,
                                'is_verified'           =>'1',
                                'price'             =>$price,
                                'used_by'               =>'coc',
                                'user_id'                =>$user_id,
                                'created_at'            =>date('Y-m-d H:i:s')
                                ];
            
                            DB::table('gst_checks')->insert($log_data);
                        }

                        DB::commit();
                        return response()->json([
                            'fail'      =>false,
                            'data'      =>$master_data 
                        ]);

                    }else{
                        return response()->json([
                            'fail'      =>true,
                            'error'     =>"yes",
                            'error'     =>"It seems like ID number is not valid!"
                        ]);
                    }
                    
                }

            }else{
                    return response()->json([
                    'fail'          =>true,
                    'error'         =>"yes",
                    'error_message' =>"It seems like ID number is not valid!",
                    
                ]); 
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 

    }

     // check aadhaar  with OTP
     public function idAdvanceCheck(Request $request)
     {
         $business_id = Auth::user()->id;
 
        $service_id = $request->service_id;
          $rules = [
             'aadhaar_id'          => 'required|regex:/^((?!([0-1]))[0-9]{12})$/',                
         ];

         $custommessages=[
             'aadhaar_id.regex' => 'Please enter a 12-digit valid aadhar number !'
         ];
 
         $validator = Validator::make($request->all(), $rules,$custommessages);
         if ($validator->fails())
             return response()->json([
                 'fail' => true,
                 'errors' => $validator->errors(),
                 'error_type'=>'validation'
             ]);

             DB::beginTransaction();
            try
            {
                //check from live API
                 $api_check_status = false;
                 // Setup request to send json via POST
                 $data = array(
                     'id_number'    =>$request->input('aadhaar_id'),
                     'async'         => true,
                 );
                 $payload = json_encode($data);
                 $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/aadhaar-v2/generate-otp";
 
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

                 if($array_data['success']==false)
                 {
                    return response()->json([
                        'fail'      =>true,
                        'error' => 'yes'
                    ]);   
                 }
                    
                 
                     
                 $data = [
                         'client_id'        =>$array_data['data']['client_id'],
                          'if_number'     => $array_data['data']['if_number'],
                         'otp_sent'     => $array_data['data']['otp_sent'],
                         'business_id' => $business_id,
                          'aadhar_number'   =>$request->input('aadhaar_id'),
                          'created_at'       =>date('Y-m-d H:i:s')
                         ];
                         
                 DB::table('advance_aadhar_otps')->insert($data);
                
                 DB::commit();
                  return response()->json([
                         'fail'      =>false,
                        'service_id' => $service_id
                     ]);
            }
            catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return $e;
            } 
 
 
     }
 
     // Aadhar Advance otp Check
     public function idAdvanceCheckOtp(Request $request)
     {
         $parent_id=Auth::user()->parent_id;
         $business_id = Auth::user()->business_id;
         $user_id=Auth::user()->id;
         $service_id=base64_decode($request->serv_id);
         $client_id=NULL;
         $price=20;

         $rules = [
            // 'otp'  => 'required|numeric|min:4',   
            'mob' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:11',             
            
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors(),
                'error_type'=>'validation'
            ]);

        // Validation for OTP
        if(count($request->otp)==0)
        {
            return response()->json([
                'fail' => true,
                'errors' => ['otp'=>['The otp field is required']],
                'error_type'=>'validation'
            ]);
        }
        else
        {
            foreach($request->otp as $value)
            {
                if($value=='' || $value==NULL)
                {
                return response()->json([
                            'fail' => true,
                            'errors' => ['otp'=>['The otp field is required']],
                            'error_type'=>'validation'
                        ]);
                }
                else if(!is_numeric($value))
                {
                    return response()->json([
                        'fail' => true,
                        'errors' => ['otp'=>['The otp must be numeric']],
                        'error_type'=>'validation'
                    ]);
                }
            }
        }
        $otp=implode('',$request->otp);
        
        DB::beginTransaction();
        try{

                $data = DB::table('check_price_cocs')->where(['service_id'=>$service_id,'coc_id'=>$business_id])->first();
                if($data!=NULL)
                {
                    $price=$data->price;
                }
                else{
                    $data=DB::table('check_prices')->where(['service_id'=>$service_id,'business_id'=>$parent_id])->first();
                    if($data!=NULL)
                    {
                        $price=$data->price;
                    }
                    //  else{
                    //      $data=DB::table('check_price_masters')->where(['service_id'=>$service_id])->first();
                    //      if($data!=NULL)
                    //      {
                    //          $price=$data->price;
                    //      }
                    //  }
                }
        
                //check from live API
                 $api_check_status = false;
                 $master_data = DB::table('advance_aadhar_otps')->where('business_id',$business_id)->get();
                 foreach($master_data as $master)
                 {
                     $client_id = $master->client_id;
                 }
                 // Setup request to send json via POST
                 $data = array(
                     'otp'    => $otp,
                     'client_id'=>$client_id,
                     'mobile_number' => $request->input('mob')
                 
                 );
                 $payload = json_encode($data);
                 $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/aadhaar-v2/submit-otp";
 
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

                 if($array_data['success']==false)
                 {
                    return response()->json([
                        'fail'      =>true,
                        'error' => 'yes'
                    ]);   
                 }
                 
                 $country = $array_data['data']['address']['country'];
                 $state = $array_data['data']['address']['state'];
                 $dist = $array_data['data']['address']['dist'];
                 $po =$array_data['data']['address']['po'];
                 $loc = $array_data['data']['address']['loc'];
                 $vtc =$array_data['data']['address']['vtc'];
                 $subdist =$array_data['data']['address']['subdist'];
                 $house =$array_data['data']['address']['house'];
                 $street =$array_data['data']['address']['street'];
 
 
                 $data = [
                     'business_id' => $business_id,
                     'client_id'        =>$array_data['data']['client_id'],
                     'dob'        =>$array_data['data']['dob'],
                     'full_name'     => $array_data['data']['full_name'],
                     'gender'     => $array_data['data']['gender'],
                     'aadhar_number'   =>$array_data['data']['aadhaar_number'],
                     'zip'        =>$array_data['data']['zip'],
                     'address'     => $house.','.$street.','.$vtc.','.$loc.','.$po.','.$subdist.','.$dist.','.$state.','.$country,
                     'profile_image'     => $array_data['data']['profile_image'],
                     'zip_data'   =>$array_data['data']['zip_data'],
                     'raw_xml'   =>$array_data['data']['raw_xml'],
                     'share_code'   =>$array_data['data']['share_code'],
                     'care_of'   =>$array_data['data']['care_of'],
                     'mobile_verified'   =>$array_data['data']['mobile_verified'],
                     'reference_id'   =>$array_data['data']['reference_id'],
                     'source_type' => 'API'
                      
                 ];
                     
             DB::table('aadhar_check_v2s')->insert($data);
             $gender = 'Male';
             if($array_data['data']['gender'] == 'F'){
                 $gender = 'Female';
             }
             $business_data = [
                'parent_id'         =>$parent_id,
                'business_id'       =>$business_id,
                'service_id'        =>$service_id,
                'client_id'        =>$array_data['data']['client_id'],
                'dob'        =>$array_data['data']['dob'],
                'full_name'     => $array_data['data']['full_name'],
                'aadhar_number'     =>$array_data['data']['aadhaar_number'],
                'age_range'         =>NULL,
                'gender'            =>$gender,
                'state'             =>$state,
                'zip'        =>$array_data['data']['zip'],
                'address'     => $house.','.$street.','.$vtc.','.$loc.','.$po.','.$subdist.','.$dist.','.$state.','.$country,
                'profile_image'     => $array_data['data']['profile_image'],
                'zip_data'   =>$array_data['data']['zip_data'],
                'raw_xml'   =>$array_data['data']['raw_xml'],
                'share_code'   =>$array_data['data']['share_code'],
                'care_of'   =>$array_data['data']['care_of'],
                'mobile_verified'   =>$array_data['data']['mobile_verified'],
                'reference_id'   =>$array_data['data']['reference_id'],
                'last_digit'        =>NULL,
                'is_verified'       =>'1',
                'is_aadhar_exist'   =>'1',
                'used_by'           =>'coc',
                'user_id'            => $user_id,
                'source_reference'  =>'API',
                'price'             =>$price,
                'created_at'        =>date('Y-m-d H:i:s')
                ]; 
                DB::table('aadhar_checks')->insert($business_data);
                
                DB::commit();
             return response()->json([
                 'fail'      =>false,
                 'client_id' =>  Crypt::encryptString($array_data['data']['client_id'])
                
             ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
 
     }

           //show report after verifications
    public function advanceAadharReport(Request $request)
    {
        $client_id= Crypt::decryptString($request->client_id);
        $aadhar=NULL;
        $advance_aadhar =  DB::table('aadhar_check_v2s')
                            ->select('*')
                            ->where('client_id',$client_id)
                            ->first();
        // foreach($advance_aadhar as $ad)
        //         {
        //             $aadhar = $ad;
        //         }

        $aadhar=$advance_aadhar;
                
                // dd($aadhar);
        return view('clients.verification.v2_aadhar',compact('aadhar'));
    }

    // check id - bank
    public function idCheckBankAccount(Request $request)
    {  
        $parent_id=Auth::user()->parent_id;
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $service_id=base64_decode($request->service_id); 
        
        $price=20;

        DB::beginTransaction();
        try{
            $data = DB::table('check_price_cocs')->where(['service_id'=>$service_id,'coc_id'=>$business_id])->first();
            if($data!=NULL)
            {
                $price=$data->price;
            }
            else{
                $data=DB::table('check_prices')->where(['service_id'=>$service_id,'business_id'=>$parent_id])->first();
                if($data!=NULL)
                {
                    $price=$data->price;
                }
                // else{
                //     $data=DB::table('check_price_masters')->where(['service_id'=>$service_id])->first();
                //     if($data!=NULL)
                //     {
                //         $price=$data->price;
                //     }
                // }
            }
            if( $request->has('id_number') ) {
            
                $account_no = $request->input('id_number');
                $id_number=preg_match('/^(?=.*[0-9])[A-Z0-9]{9,18}$/', $account_no);
                if($request->input('id_number')=='' || !($id_number))
                {
                    return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'error'     =>"It seems like ID number is not valid!"
                    ]);
                }

                $id_number=preg_match('/^(?=.*[A-Z])(?=.*[0-9])[A-Z0-9]{11,}$/', $request->input('ifsc'));
                if($request->input('id_number')=='' || !($id_number))
                {
                    return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'error'     =>"It seems like ID number is not valid!"
                    ]);
                }

                //check first into master table
                $master_data = DB::table('bank_account_check_masters')->select('*')->where(['account_number'=>$account_no,'ifsc_code'=>$request->input('ifsc')])->first();
                if($master_data !=null){
                    $log_data = [
                        'parent_id'         =>$parent_id,
                        'business_id'       =>$business_id,
                        'service_id'            => $service_id,
                        'source_type'       =>'SystemDb',
                        'api_client_id'     =>$master_data->api_client_id,
                        'account_number'    =>$master_data->account_number,
                        'full_name'         =>$master_data->full_name,
                        'ifsc_code'         =>$master_data->ifsc_code,
                        'is_verified'       =>'1',
                        'is_account_exist' =>'1',
                        'price'             =>$price,
                        'used_by'           =>'coc',
                        'user_id'            =>$user_id,
                        'created_at'        =>date('Y-m-d H:i:s')
                        ];

                    DB::table('bank_account_checks')->insert($log_data);
                    DB::commit();
                    return response()->json([

                        'fail'      =>false,
                        'data'      =>$master_data 
                    ]);
                }
                else{
                    //check from live API
                    // Setup request to send json via POST
                    $data = array(
                        'id_number' => $request->input('id_number'),
                        'ifsc'      => $request->input('ifsc'),
                        'async'         => true,
                    );
                    $payload = json_encode($data);
                    $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/bank-verification/";

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                    curl_setopt ($ch, CURLOPT_POST, 1);
                    $authorization = "Authorization: Bearer ".env('SUREPASS_PRODUCTION_TOKEN'); // Prepare the authorisation token
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
                    curl_setopt($ch, CURLOPT_URL, $apiURL);
                    // Attach encoded JSON string to the POST fields
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                    $resp = curl_exec ( $ch );
                    curl_close ( $ch );
                    $array_data =  json_decode($resp,true);
                    // var_dump($resp); die;
                    if($array_data['success'])
                    {
                        //check if ID number is new then insert into DB
                        $checkIDInDB= DB::table('bank_account_check_masters')->where(['account_number'=>$account_no,'ifsc_code'=>$request->input('ifsc')])->count();
                        if($checkIDInDB ==0)
                        {
                            
                            $data = [
                                    'parent_id'         =>$parent_id,
                                    'business_id'       =>$business_id,
                                    'api_client_id'     =>$array_data['data']['client_id'],
                                    'account_number'    =>$account_no,
                                    'full_name'         =>$array_data['data']['full_name'],
                                    'ifsc_code'         =>$request->input('ifsc'),
                                    'is_verified'       =>'1',
                                    'is_account_exist' =>'1',
                                    'created_by'            =>$user_id,
                                    'created_at'        =>date('Y-m-d H:i:s')
                                    ];

                            DB::table('bank_account_check_masters')->insert($data);
                            
                            $master_data = DB::table('bank_account_check_masters')->select('*')->where(['account_number'=>$account_no,'ifsc_code'=>$request->input('ifsc')])->first();

                            $log_data = [
                                'parent_id'         =>$parent_id,
                                'business_id'       =>$business_id,
                                'service_id'         => $service_id,
                                'source_type'       =>'API',
                                'api_client_id'     =>$master_data->api_client_id,
                                'account_number'    =>$master_data->account_number,
                                'full_name'         =>$master_data->full_name,
                                'ifsc_code'         =>$master_data->ifsc_code,
                                'is_verified'       =>'1',
                                'is_account_exist' =>'1',
                                'price'             =>$price,
                                'used_by'           =>'coc',
                                'user_id'            =>$user_id,
                                'created_at'        =>date('Y-m-d H:i:s')
                                ];
            
                            DB::table('bank_account_checks')->insert($log_data);
                        }

                        DB::commit();
                        return response()->json([
                            'fail'      =>false,
                            'data'      =>$master_data 
                        ]);

                    }else{
                        return response()->json([
                            'fail'      =>true,
                            'error'     =>"yes",
                            'error'     =>"It seems like ID number is not valid!"
                        ]);
                    }
                    
                }

            }else{
                    return response()->json([
                    'fail'          =>true,
                    'error'         =>"yes",
                    'error_message' =>"It seems like ID number is not valid!",
                    
                ]); 
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 

    }

    // check Telecom with OTP
    public function idTelecomCheck(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $parent_id     = Auth::user()->parent_id;
        $user_id =Auth::user()->id;
        $service_id=base64_decode($request->service_id); 
        
        $price=20;

        DB::beginTransaction();
        try{
            $data = DB::table('check_price_cocs')->where(['service_id'=>$service_id,'coc_id'=>$business_id])->first();
            if($data!=NULL)
            {
                $price=$data->price;
            }
            else{
                $data=DB::table('check_prices')->where(['service_id'=>$service_id,'business_id'=>$parent_id])->first();
                if($data!=NULL)
                {
                    $price=$data->price;
                }
                // else{
                //     $data=DB::table('check_price_masters')->where(['service_id'=>$service_id])->first();
                //     if($data!=NULL)
                //     {
                //         $price=$data->price;
                //     }
                // }
            }
            if($request->id_number)
            {
                $id_number=preg_match('/^(?=.*[0-9])[0-9]{10}$/', $request->input('id_number'));
                if($request->input('id_number')=='' || !($id_number))
                {
                    return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'message'     =>"Please enter the valid Phone number!"
                    ]);
                }
                //check first into master table
                $master_data = DB::table('telecom_check_master')->select('*')->where(['mobile_no'=>$request->id_number])->first();
                
                if($master_data !=null){
            
                    // store log
                    $check_data = [
                        'parent_id'         =>$parent_id,
                        'business_id'       =>$business_id,
                        'service_id'        => $service_id,
                        'billing_type' => $master_data->billing_type,
                        'full_name' => $master_data->full_name,
                        'dob' => $master_data->dob,
                        'alternative_phone' =>$master_data->alternative_phone,
                        'address' => $master_data->address,
                        'city' => $master_data->city,
                        'state' => $master_data->state,
                        'pin_code' => $master_data->pin_code,
                        'email' => $master_data->email,
                        'mobile_no'     => $master_data->mobile_no,
                        'is_verified'       =>'1',
                        'is_mobile_exist'   =>'1',
                        'used_by'           =>'coc',
                        'user_id'    => $user_id,
                        'source_type'  =>    'SystemDB',
                        'price'             =>$price,
                        'created_at'        =>date('Y-m-d H:i:s'),
                        'updated_at'        =>date('Y-m-d H:i:s')
                    ]; 

                    DB::table('telecom_check')->insert($check_data);

                    DB::commit();
                    return response()->json([
                        'fail'      =>false,
                        'db' => true,
                        'id' => $master_data->id,
                        'name' =>   $master_data->full_name,
                        'dob' => $master_data->dob,
                        'address' => $master_data->address,
                        'mobile' => $master_data->mobile_no,
                        'alternative' => $master_data->alternative_phone==NULL?'N/A':$master_data->alternative_phone,
                        'operator' => $master_data->operator,
                        'billing_type' => $master_data->billing_type,
                        'email' => $master_data->email==NULL?'N/A':$master_data->email,
                        'city' => $master_data->city,
                        'state' => $master_data->state,
                        'pin_code' => $master_data->pin_code,
                    ]);
                    
                }
                else{
                    //check from live API
                    $api_check_status = false;
                        // Setup request to send json via POST
                    $data = array(
                        'id_number'    => $request->id_number,
                        'async'         => true,
                    );
                    $payload = json_encode($data);
                    $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/telecom/generate-otp";

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
                    if(!$array_data['success'])
                    {
                        return response()->json([
                            'fail'      =>  true,
                            'message' => $array_data['message']
                        ]);
                    }
                    $master_data ="";
                    //check if ID number is new then insert into DB
                    $checkIDInDB= DB::table('telecom_check_master')->where(['mobile_no'=>$request->id_number])->count();
                    if($checkIDInDB==0)
                    {
                        // store log
                        $data = [
                            'client_id'        =>$array_data['data']['client_id'],
                            'otp_sent'     => $array_data['data']['otp_sent'],
                            'operator' => $array_data['data']['operator'],
                            'if_number' => $array_data['data']['if_number'],
                            'business_id' => $user_id,
                            'mobile_no'   =>$request->id_number,
                            'price'        => $price,
                            'created_by'        => $user_id,
                            'status'            => 1,
                            'created_at'       => date('Y-m-d H:i:s'),
                            'updated_at'       => date('Y-m-d H:i:s')
                            ];
                            
                            DB::table('advance_telecom_otps')->insert($data);
                    }

                    DB::commit();
                    return response()->json([
                            'fail'      =>false,
                            'db' => false,
                            'client_id' => $array_data['data']['client_id'],

                    ]);
                }  
            }
            else
            {
                return response()->json([
                    'fail'      =>  true,
                    'message' => 'It seems like number is not valid!'
                ]);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 

    }
    public function idVerifyTelcomCheck(Request $request)
    {
        $parent_id=Auth::user()->parent_id;
        $user_id=Auth::user()->id;
        $business_id = Auth::user()->business_id;
        $service_id=base64_decode($request->ser_id);

        $price=20;
        $count=1;

        // $rules = [
        //     'otp'  => 'required|numeric|min:4',   
        // ];

        // $validator = Validator::make($request->all(), $rules);
        // if ($validator->fails())
        //     return response()->json([
        //         'fail' => true,
        //         'errors' => $validator->errors(),
        //         'error_type'=>'validation'
        //     ]);

        // Validation for OTP
        if(count($request->otp)==0)
        {
            return response()->json([
                'fail' => true,
                'errors' => ['otp'=>['The otp field is required']],
                'error_type'=>'validation'
            ]);
        }
        else
        {
            foreach($request->otp as $value)
            {
                if($value=='' || $value==NULL)
                {
                return response()->json([
                            'fail' => true,
                            'errors' => ['otp'=>['The otp field is required']],
                            'error_type'=>'validation'
                        ]);
                }
                else if(!is_numeric($value))
                {
                    return response()->json([
                        'fail' => true,
                        'errors' => ['otp'=>['The otp must be numeric']],
                        'error_type'=>'validation'
                    ]);
                }
            }
        }
        $otp=implode('',$request->otp);

            DB::beginTransaction();
            try
            {
                $data = DB::table('check_price_cocs')->where(['service_id'=>$service_id,'coc_id'=>$business_id])->first();
                if($data!=NULL)
                {
                    $price=$data->price;
                }
                else{
                    $data=DB::table('check_prices')->where(['service_id'=>$service_id,'business_id'=>$parent_id])->first();
                    if($data!=NULL)
                    {
                        $price=$data->price;
                    }
                    // else{
                    //     $data=DB::table('check_price_masters')->where(['service_id'=>$service_id])->first();
                    //     if($data!=NULL)
                    //     {
                    //         $price=$data->price;
                    //     }
                    // }
                }

                $advance_otp=DB::table('advance_telecom_otps')->where(['business_id'=>$business_id,'mobile_no' => $request->mob_t,'status'=>1])->get();
                    
                if(count($advance_otp)>0)
                {
                    $count=count($advance_otp);
                }
                    $client_id=$request->client_id;
                    //check from live API
                    $api_check_status = false;
                    $master_data = DB::table('advance_telecom_otps')->where(['business_id'=>$business_id,'client_id'=>$client_id])->first();
                    // foreach($master_data as $master)
                    // {
                    //     $client_id = $master->client_id;
                    // }
                    // Setup request to send json via POST
                    $data = array(
                        'otp'    =>$otp,
                        'client_id'=> $master_data->client_id,
                    
                    );
                    $payload = json_encode($data);
                    $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/telecom/submit-otp";

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
                    
                    $array_data= json_decode($resp,true);

                    if($array_data['success']==false)
                    {
                        if(array_key_exists('status_code',$array_data))
                        {
                            if($array_data['status_code']==422)
                            {
                                return response()->json([
                                    'fail'      =>  true,
                                    'error' => 'yes',
                                    'message' => 'It seems like OTP TimeOut! Try again'
                                ]);        
                            }
                            
                        }
                        return response()->json([
                            'fail'      =>  true,
                            'error' => 'yes',
                            'message' => 'It seems like OTP is invalid! Try again'
                        ]);
                    }
                    
                    $data = [
                                'parent_id'         =>$parent_id,
                                'business_id'       =>$business_id,
                                'client_id'  =>$array_data['data']['client_id'],
                                'business_id' => $business_id,
                                'operator' => $array_data['data']['operator'],
                                'billing_type' => $array_data['data']['billing_type'],
                                'full_name' => $array_data['data']['full_name'],
                                'dob' => $array_data['data']['dob'],
                                'mobile_no'   => $array_data['data']['mobile_number'],
                                'alternative_phone' =>$array_data['data']['alternate_phone'],
                                'address' => $array_data['data']['address'],
                                'city' => $array_data['data']['city'],
                                'state' => $array_data['data']['state'],
                                'pin_code' => $array_data['data']['pin_code'],
                                'email' => $array_data['data']['user_email'],
                                'is_verified' => '1',
                                'created_by' => $user_id,
                                'created_at'       => date('Y-m-d H:i:s'),
                            ];
                
                    
                    $insert_id= DB::table('telecom_check_master')->insertGetId($data);

                    $master_data=DB::table('telecom_check_master')->where(['id'=>$insert_id])->first();

                    if($count>1)
                    {
                        for($i=0;$i<$count;$i++)
                        {
                            $check_data = [
                                'parent_id'         =>$parent_id,
                                'business_id'       =>$business_id,
                                'service_id'        => $service_id,
                                'operator' => $master_data->operator,
                                'billing_type' => $master_data->billing_type,
                                'full_name' => $master_data->full_name,
                                'dob' => $master_data->dob,
                                'alternative_phone' =>$master_data->alternative_phone,
                                'address' => $master_data->address,
                                'city' => $master_data->city,
                                'state' => $master_data->state,
                                'pin_code' => $master_data->pin_code,
                                'email' => $master_data->email,
                                'mobile_no'     => $request->mob_t,
                                'is_verified'       =>'1',
                                'is_mobile_exist'   =>'1',
                                'price'             =>$price,
                                'used_by'           =>'coc',
                                'user_id' => $user_id,
                                'source_type'  =>    'API',
                                'created_at'        =>date('Y-m-d H:i:s'),
                                'updated_at'        =>date('Y-m-d H:i:s')
                            ]; 
            
                            DB::table('telecom_check')->insert($check_data);
                        }
                    }
                    else
                    {
                        $check_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       =>$business_id,
                            'service_id'        => $service_id,
                            'operator' => $master_data->operator,
                            'billing_type' => $master_data->billing_type,
                            'full_name' => $master_data->full_name,
                            'dob' => $master_data->dob,
                            'alternative_phone' =>$master_data->alternative_phone,
                            'address' => $master_data->address,
                            'city' => $master_data->city,
                            'state' => $master_data->state,
                            'pin_code' => $master_data->pin_code,
                            'email' => $master_data->email,
                            'mobile_no'     => $request->mob_t,
                            'is_verified'       =>'1',
                            'is_mobile_exist'   =>'1',
                            'price'             =>$price,
                            'used_by'           =>'coc',
                            'user_id' => $user_id,
                            'source_type'  =>    'API',
                            'created_at'        =>date('Y-m-d H:i:s'),
                            'updated_at'        =>date('Y-m-d H:i:s')
                        ]; 
        
                        DB::table('telecom_check')->insert($check_data);
                    }
                    

                    DB::commit();
                    return response()->json([
                        'fail'      =>false,
                        'id' => $insert_id,
                        'name' =>   $array_data['data']['full_name'],
                        'dob' => $array_data['data']['dob'],
                        'address' => $array_data['data']['address'],
                        'mobile' => $array_data['data']['mobile_number'],
                        'alternative' => $array_data['data']['alternate_phone']==NULL?'N/A':$array_data['data']['alternate_phone'],
                        'operator' => $array_data['data']['operator'],
                        'billing_type' => $array_data['data']['billing_type'],
                        'email' => $array_data['data']['user_email']==NULL?'N/A':$array_data['data']['user_email'],
                        'city' => $array_data['data']['city'],
                        'state' => $array_data['data']['state'],
                        'pin_code' => $array_data['data']['pin_code'],
                    ]);
            }
            catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return $e;
            } 
    }

    // check Covid with OTP
    public function idCovid19Check(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $user_id =Auth::user()->id;
        $service_id=base64_decode($request->service_id);  
        $parent_id=Auth::user()->parent_id;
        
        DB::beginTransaction();
        try{
            // dd($service_id);
            if($request->id_number)
            {
                $id_number=preg_match('/^(?=.*[0-9])[0-9]{10}$/', $request->input('id_number'));
                if($request->input('id_number')=='' || !($id_number))
                {
                    return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'message'     =>"Please enter the valid Phone number!"
                    ]);
                }
                
                //check from live API
                $api_check_status = false;
                $response_code=0;
                    // Setup request to send json via POST
                $data = array(
                    'mobile'    => $request->id_number,
                    'async'         => true, 
                );
                $payload = json_encode($data);
                $apiURL = "https://cdn-api.co-vin.in/api/v2/auth/public/generateOTP";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                // curl_setopt ( $ch, CURLOPT_POST, 1 );
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); // Inject the token into the header
                curl_setopt($ch, CURLOPT_URL, $apiURL);
                // Attach encoded JSON string to the POST fields
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

                $resp = curl_exec ($ch);
                $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close ($ch);

                // dd($resp);
                // dd($response_code);
            
                $array_data =  json_decode($resp,true);

                // dd($array_data);
                // if(!$array_data['success'])
                // {
                //     return response()->json([
                //         'fail'      =>  true,
                //         'message' => $array_data['message']
                //     ]);
                // }
                
                if($response_code==200)
                {
                    $data = [
                        'txnId'        =>$array_data['txnId'],
                        'business_id' => $business_id,
                        'mobile_no'   =>$request->id_number,
                        'created_by'        => $user_id,
                        'status'            => 1,
                        'created_at'       => date('Y-m-d H:i:s'),
                        ];
                        
                        $otp_id=DB::table('advance_covid19_otps')->insertGetId($data);
                        DB::commit();
                        return response()->json([
                            'fail'      => false,
                            'otp_id'    => base64_encode($otp_id),
                            'txnId'     => $array_data['txnId']
                        ]);
                }
                else
                {
                    // dd($resp);
                    if($response_code==400)
                    {
                        return response()->json([
                            'fail' => true,
                            'message' => $array_data!=NULL?$array_data['error'] : 'Invalid Mobile Number ! Try Again !!',
                        ]);
                    }
                    else if($response_code==401)
                    {
                        return response()->json([
                            'fail' => true,
                            'message' => 'Enter a Valid Mobile Number ! Try Again !!'
                        ]);
                    }
                    else
                    {
                        return response()->json([
                            'fail' => true,
                            'message' => 'Something Went Wrong !!'
                        ]);
                    }
                }
                  
            }
            else
            {
                return response()->json([
                    'fail'      =>  true,
                    'message' => 'It seems like number is not valid!'
                ]);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 

    }

    public function idVerifyCovid19Check(Request $request)
    {
        $user_id=Auth::user()->id;
        $business_id = Auth::user()->business_id;
        $parent_id=Auth::user()->parent_id;

        

        // $rules = [
        //     'otp'  => 'required|integer|min:4',   
        // ];

        // $validator = Validator::make($request->all(), $rules);
        // if ($validator->fails())
        //     return response()->json([
        //         'fail' => true,
        //         'errors' => $validator->errors(),
        //         'error_type'=>'validation'
        //     ]);

        // Validation for OTP
        if(count($request->otp)==0)
        {
            return response()->json([
                'fail' => true,
                'errors' => ['otp'=>['The otp field is required']],
                'error_type'=>'validation'
            ]);
        }
        else
        {
            foreach($request->otp as $value)
            {
                if($value=='' || $value==NULL)
                {
                return response()->json([
                            'fail' => true,
                            'errors' => ['otp'=>['The otp field is required']],
                            'error_type'=>'validation'
                        ]);
                }
                else if(!is_numeric($value))
                {
                    return response()->json([
                        'fail' => true,
                        'errors' => ['otp'=>['The otp must be numeric']],
                        'error_type'=>'validation'
                    ]);
                }
            }
        }
        $otp=implode('',$request->otp);
        
        DB::beginTransaction();
        try{
                $mobile_number=$request->mob_c;
                $service_id=base64_decode($request->ser_id);
                $otp_id = base64_decode($request->otp_id);
                $txnId=$request->txnId;
                //check from live API
                $api_check_status = false;
                $response_code = 0;
                $master_data = DB::table('advance_covid19_otps')->where(['id'=>$otp_id])->first();
                // dd($master_data);
                // foreach($master_data as $master)
                // {
                //     $client_id = $master->client_id;
                // }

                // Setup request to send json via POST
                $data = array(
                    'otp'    => hash('sha256',$otp),
                    'txnId' => $master_data->txnId,
                );
                $payload = json_encode($data);
                $apiURL = "https://cdn-api.co-vin.in/api/v2/auth/public/confirmOTP";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                curl_setopt ( $ch, CURLOPT_POST, 1 );
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); // Inject the token into the header
                curl_setopt($ch, CURLOPT_URL, $apiURL);
                // Attach encoded JSON string to the POST fields
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

              
                $resp = curl_exec ( $ch );
                $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close ( $ch );
                
                $array_data= json_decode($resp,true);
                
                if($response_code==200)
                {
                    DB::table('advance_covid19_otps')->where(['id'=>$otp_id])->update(
                    [
                        'is_verified'   => '1',
                        'token' => $array_data['token'],
                        'updated_by'    => $user_id,
                        'updated_at'    => date('Y-m-d H:i:s'),
                    ]
                );

                    DB::commit();
                    return response()->json([
                        'fail'      =>false,
                        'id'        => $request->otp_id,
                        'service_id' => $request->ser_id,
                        'mobile_no'    => $mobile_number
                    ]);
                }
                else
                {
                    if($response_code==400)
                    {
                        return response()->json([
                            'fail' => true,
                            'error'=>'yes',
                            'message' => $array_data!=NULL?$array_data['error'] : 'Invalid OTP !!'
                        ]);
                    }
                    else if($response_code==401)
                    {
                        return response()->json([
                            'fail' => true,
                            'error'=>'yes',
                            'message' => 'OTP Session Timeout ! Try Again !!'
                        ]);
                    }
                    else
                    {
                        return response()->json([
                            'fail' => true,
                            'error'=>'yes',
                            'message' => 'Something Went Wrong !!'
                        ]);
                    }
                }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    public function idVerifyCovidRefCheck(Request $request)
    {
        $user_id=Auth::user()->id;
        $business_id = Auth::user()->business_id;
        $parent_id=Auth::user()->parent_id;

        $rules = [
            'reference_id'  => 'required|integer|min:14',   
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors(),
                'error_type'=>'validation'
            ]);

        DB::beginTransaction();
        try{
            $mob_c=$request->mob_c;
            $reference_id=$request->reference_id;
            $otp_id = base64_decode($request->otp_id);
            $service_id=base64_decode($request->ser_id);
            $api_check_status = false;
            $response_code = 0;
            $advance_otp = DB::table('advance_covid19_otps')->where(['id'=>$otp_id])->first();
            $path='';
            $file_name='';
            $content=NULL;
                // dd($advance_otp);
            //    $master_data=DB::table('covid19_check_masters')->where(['reference_id'=>$reference_id])->first();
            //     if($master_data!=NULL)
            //     {
            //         $path= public_path().'/cowin/certificate/';
            //         $file_name=date('Ymdhis').'-'.'cowin-certificate'.'.pdf';
            //         $content=base64_decode($master_data->raw_data);
            //         file_put_contents($path.$file_name, $content);

            //         DB::table('covid19_checks')->insert([
            //             'parent_id' => $parent_id,
            //             'business_id'  => $business_id,
            //             'service_id'    => $service_id,
            //             'txnId' => $master_data->txnId,
            //             'source_type'   => 'SystemDB',
            //             'mobile_no' => $master_data->mobile_no,
            //             'reference_id' => $reference_id,
            //             'token' => $master_data->token,
            //             'user_id'   => $user_id,
            //             'used_by'   => 'coc',
            //             'file_name' => $file_name,
            //             'raw_data' => base64_encode($content),
            //             'created_at'   => date('Y-m-d H:i:s')
            //         ]);

            //         $URL= url('/').'/cowin/certificate/'.$file_name;
            //         DB::commit();
            //         return response()->json([
            //             'fail' => false,
            //             'url' => $URL,
            //             'data'=>$master_data
            //         ]);

            //     }
            //     else
            //     {
                    // Setup request to send json via POST
                    // $data = array(
                    //     'beneficiary_reference_id'    => $reference_id,
                    // );
                    // $payload = json_encode($data);
                    $apiURL = "https://cdn-api.co-vin.in/api/v2/registration/certificate/public/download?beneficiary_reference_id=".$reference_id;

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                    // curl_setopt ( $ch, CURLOPT_POST, 1 );
                    $authorization = "Authorization: Bearer ".$advance_otp->token; // Prepare the authorisation token
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/pdf',$authorization)); // Inject the token into the header
                    curl_setopt($ch, CURLOPT_URL, $apiURL);
                    // Attach encoded JSON string to the POST fields
                    // curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

                
                    $resp = curl_exec ( $ch );
                    $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close ( $ch );
                    // var_dump($resp);die();
                    // $array_data= json_decode($resp,true);
                    $path='';
                    $file_name='';
                    $content=NULL;
                    if($response_code==200)
                    {
                        $path= public_path().'/cowin/certificate/';
                        $file_name=date('Ymdhis').'-'.'cowin-certificate'.'.pdf';
                        $content=$resp;
                        file_put_contents($path.$file_name, $content);


                        DB::table('advance_covid19_otps')->where(['mobile_no'=>$advance_otp->mobile_no])->update([
                            'status'    => 0,
                            'updated_by' => $user_id,
                            'updated_at'   => date('Y-m-d H:i:s')
                        ]);

                        $master_id = DB::table('covid19_check_masters')->insertGetId([
                                'parent_id' => $parent_id,
                                'business_id'  => $business_id,
                                'txnId' => $advance_otp->txnId,
                                'source_type'   => 'API',
                                'mobile_no' => $advance_otp->mobile_no,
                                'reference_id' => $reference_id,
                                'token' => $advance_otp->token,
                                'file_name' => $file_name,
                                'raw_data'  => base64_encode($content),
                                'created_by'    => $user_id,
                                'created_at'   => date('Y-m-d H:i:s')
                            ]);

                        DB::table('covid19_checks')->insert([
                            'parent_id' => $parent_id,
                            'business_id'  => $business_id,
                            'service_id'    => $service_id,
                            'txnId' => $advance_otp->txnId,
                            'source_type'   => 'API',
                            'mobile_no' => $advance_otp->mobile_no,
                            'reference_id' => $reference_id,
                            'token' => $advance_otp->token,
                            'user_id'   => $user_id,
                            'used_by'   => 'coc',
                            'file_name' => $file_name,
                            'raw_data'  => base64_encode($content),
                            'created_at'   => date('Y-m-d H:i:s')
                        ]);

                        $master_data=DB::table('covid19_check_masters')->where(['id'=>$master_id])->first();

                        DB::commit();
                        $URL= url('/').'/cowin/certificate/'.$file_name;
                        return response()->json([
                            'fail' => false,
                            'url'  => $URL,
                            'data' =>$master_data
                        ]);

                    }
                    else
                    {
                        if($response_code==400)
                        {
                            return response()->json([
                                'fail' => true,
                                'error'=>'yes',
                                'message' => 'Data Not Found !!'
                            ]);
                        }
                        else if($response_code==401)
                        {
                            return response()->json([
                                'fail' => true,
                                'error'=>'yes',
                                'message' => 'Timeout ! Try Again Later!!'
                            ]);
                        }
                        else
                        {
                            return response()->json([
                                'fail' => true,
                                'error'=>'yes',
                                'message' => 'Something Went Wrong !!'
                            ]);
                        }
                    }

                // }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    // check id - ecourt
    public function idCheckECourt(Request $request)
    {    
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;

        $price=50;
        $service_id=base64_decode($request->service_id); 

        $parent_id=Auth::user()->parent_id;
        
        
        DB::beginTransaction();
        try{

            $data = DB::table('check_price_cocs')->where(['service_id'=>$service_id,'coc_id'=>$business_id])->first();
            if($data!=NULL)
            {
                $price=$data->price;
            }
            else{
                $data=DB::table('check_prices')->where(['service_id'=>$service_id,'business_id'=>$parent_id])->first();
                if($data!=NULL)
                {
                    $price=$data->price;
                }
            }


                $name = $request->input('name');

                $name=preg_match('/^[A-Za-z]+([A-Za-z]+\s)*[A-za-z]{3,}$/u', $name);

                if($request->input('name')=='' || !($name))
                {
                    return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'error'     =>"It seems like Name is not valid!"
                    ]);
                }

                $father_name=preg_match('/^[A-Za-z]+([A-Za-z]+\s)*[A-za-z]{3,}$/u', $request->input('fathername'));

                if($request->input('fathername')=='' || !($father_name))
                {
                    return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'error'     =>"It seems like Father Name is not valid!"
                    ]);
                }

                $address = $request->input('address');

                if($address=='' || strlen($address) < 4)
                {
                    return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'error'     =>"It seems like Address should not be blank or atleast require 4 characters !"
                    ]);
                }

            //check from live API
            // Setup request to send json via POST
            $data = array(
                'name' => $name,
                'fatherName' => $father_name,
                'address' => $address
            );
            $payload = json_encode($data);
            $apiURL = "https://api.springscan.springverify.com/criminal/searchDirect";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
            curl_setopt ($ch, CURLOPT_POST, 1);
            $token_key = 'tokenKey: '.env('SPRING_TOKEN_KEY');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $token_key)); // Inject the token into the header
            curl_setopt($ch, CURLOPT_URL, $apiURL);
            // Attach encoded JSON string to the POST fields
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            $resp = curl_exec ( $ch );
            $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close ( $ch );
            $array_data =  json_decode($resp,true);
            // var_dump($resp); die;
            // dd(env('SPRING_TOKEN_KEY'));
            if($response_code==200)
            {
                $score_status = 0;
                
                //Check where any report score is greater than or equal to 90%
                // if(count($array_data['reports'])>0)
                // {
                //     foreach($array_data['reports'] as $key => $value)
                //     {
                //         if($value['score'] >= 90)
                //         {
                //             $score_status = 1;
                //         }
                //     }
                // }

                // if($score_status==1)
                // {
                    $master_data_id = DB::table('e_court_check_masters')->insertGetId([
                        'parent_id' => $parent_id,
                        'business_id' => $business_id,
                        'name' => $name,
                        'father_name' =>$father_name,
                        'address' => $address,
                        'created_by' => $user_id,
                        'created_at' =>date('Y-m-d H:i:s')
                    ]);
    
                    if(count($array_data['reports'])>0)
                    {
                        foreach($array_data['reports'] as $key => $value)
                        {
                            // if($value['score'] >= 90)
                            // {
                                DB::table('e_court_check_master_items')->insert([
                                    'e_court_master_id' => $master_data_id,
                                    'name_as_per_court_record' => $value['name'],
                                    'case_id' => $value['case_no'],
                                    'detail_link' => $value['link'],
                                    'score' => $value['score'],
                                ]);
                            // }
                        }
                    }
    
                    $check_data_id = DB::table('e_court_checks')->insertGetId([
                        'parent_id' => $parent_id,
                        'business_id' => $business_id,
                        'service_id' => $service_id,
                        'source_type' =>  'API',
                        'name' => $name,
                        'father_name' =>$father_name,
                        'address' => $address,
                        'price' => $price,
                        'user_id' => $user_id,
                        'user_type' => 'coc',
                        'created_at' =>date('Y-m-d H:i:s')
                    ]);
                    
                    if(count($array_data['reports'])>0)
                    {
                        foreach($array_data['reports'] as $key => $value)
                        {
                            // if($value['score'] >= 90)
                            // {
                                DB::table('e_court_check_items')->insert([
                                    'e_court_check_id' => $check_data_id,
                                    'parent_id' => $parent_id,
                                    'business_id' => $business_id,
                                    'service_id' => $service_id,
                                    'name_as_per_court_record' => $value['name'],
                                    'case_id' => $value['case_no'],
                                    'detail_link' => $value['link'],
                                    'score' => $value['score'],
                                    'user_id' => $user_id,
                                    'user_type' => 'customer',
                                    'created_at' =>date('Y-m-d H:i:s')
                                ]);
                            // }
                        }
                    }
    
                    $master_data = DB::table('e_court_check_masters')->where(['id'=>$master_data_id])->first();

                    DB::commit();
                    return response()->json([
                        'fail'      =>false,
                        'data'      =>$master_data 
                    ]);
                // }
                // else
                // {
                //     return response()->json([
                //         'fail'      =>true,
                //         'error'     =>"yes",
                //         'error'     =>"It seems like data not found, Please try again later!"
                //     ]);
                // }

            }else{

                return response()->json([
                    'fail'      =>true,
                    'error'     =>"yes",
                    'error'     =>"It seems like data not found, Please try again later!"
                ]);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }  
        
            

    }

    // check id - upi
    public function idCheckUPI(Request $request)
    {    
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;

        $price=50;
        $service_id=base64_decode($request->service_id); 

        $parent_id=Auth::user()->parent_id;

        DB::beginTransaction();
        try{

            $data = DB::table('check_price_cocs')->where(['service_id'=>$service_id,'coc_id'=>$business_id])->first();
            if($data!=NULL)
            {
                $price=$data->price;
            }
            else{
                $data=DB::table('check_prices')->where(['service_id'=>$service_id,'business_id'=>$parent_id])->first();
                if($data!=NULL)
                {
                    $price=$data->price;
                }
            }

                $upi_id = $request->input('id_number');

                $id_number=preg_match('/^[\w\.\-_]{3,}@[a-zA-Z]{3,}$/u', $upi_id);

               //dd($id_number);

                if($request->input('id_number')=='' || !($id_number))
                {
                    return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'error'     =>"It seems like UPI ID is not valid!"
                    ]);
                }

            //check from live API
            // Setup request to send json via GET
            // $data = array(
            //     'vpa' => $upi_id,
            // );
            // $payload = json_encode($data);
            $apiURL = "https://api.springscan.springverify.com/v2/user/person/validation/upiID/6156ac22899fc7001815b42a?vpa=".$upi_id;


            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
            // curl_setopt ($ch, CURLOPT_POST, 0);
            $token_key = 'tokenKey: '.env('SPRING_TOKEN_KEY');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $token_key)); // Inject the token into the header
            curl_setopt($ch, CURLOPT_URL, $apiURL);
            // Attach encoded JSON string to the POST fields
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            $resp = curl_exec ( $ch );
            $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close ( $ch );
            $array_data =  json_decode($resp,true);
            // var_dump($resp); die;
            // dd(env('SPRING_TOKEN_KEY'));
            if($array_data['db_output']['accountExists']=='YES')
            {
                $data = [
                    'parent_id' => $parent_id,
                    'business_id' => $business_id,
                    'upi_id'     =>$upi_id,
                    'name'    =>$array_data['db_output']['name'],
                    'created_by' => $user_id,
                    'created_at'        =>date('Y-m-d H:i:s')
                    ];

                DB::table('upi_check_masters')->insert($data);
                
                $master_data = DB::table('upi_check_masters')->select('*')->where(['upi_id'=>$upi_id])->first();

                $log_data = [
                    'parent_id'         =>$parent_id,
                    'business_id'       =>$business_id,
                    'service_id'         => $service_id,
                    'source_type'       =>'API',
                    'upi_id'            =>$upi_id,
                    'name'              =>$array_data['db_output']['name'],
                    'is_verified'       =>'1',
                    'price'             =>$price,
                    'user_type'           =>'coc',
                    'user_id'            =>$user_id,
                    'created_at'        =>date('Y-m-d H:i:s')
                    ];

                DB::table('upi_checks')->insert($log_data);

                    DB::commit();
                    return response()->json([
                        'fail'      =>false,
                        'data'      =>$master_data 
                    ]);
                

            }else{

                return response()->json([
                    'fail'      =>true,
                    'error'     =>"yes",
                    'error'     =>"It seems like UPI ID is not Valid, Please try again later!"
                ]);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }  
        
            

    }

     // check id - cin
     public function idCheckCIN(Request $request)
     {    
         $business_id=Auth::user()->business_id;
         $user_id = Auth::user()->id;
 
         $price=50;
         $service_id=base64_decode($request->service_id); 
 
         $parent_id=Auth::user()->parent_id;
 
         DB::beginTransaction();
         try{
 
             $data = DB::table('check_price_cocs')->where(['service_id'=>$service_id,'coc_id'=>$business_id])->first();
             if($data!=NULL)
             {
                 $price=$data->price;
             }
             else{
                 $data=DB::table('check_prices')->where(['service_id'=>$service_id,'business_id'=>$parent_id])->first();
                 if($data!=NULL)
                 {
                     $price=$data->price;
                 }
             }
 
 
             $cin = $request->input('id_number');

             $id_number=preg_match('/^([L|U]{1})([0-9]{5})([A-Za-z]{2})([0-9]{4})([A-Za-z]{3})([0-9]{6})$/u', $cin);
 
                 if($request->input('id_number')=='' || !($id_number))
                 {
                    return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'error_msg'     =>"It seems like CIN Number is Not Valid!"
                    ]);
                 }
 
             //check from live API
            // Setup request to send json via GET
            // $data = array(
            //     'vpa' => $upi_id,
            // );
            $payload = '{
                "docType": "ind_mca",
                "personId": "6156ac22899fc7001815b42a",
                "success_parameters": [
                    "cin_number"
                ],
                "manual_input": {
                    "cin_number": "'.$cin.'"
                }
            }';

            $apiURL = "https://api.springscan.springverify.com/v4/databaseCheck";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
            curl_setopt ($ch, CURLOPT_POST, 1);
            $token_key = 'tokenKey: '.env('SPRING_TOKEN_KEY');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $token_key)); // Inject the token into the header
            curl_setopt($ch, CURLOPT_URL, $apiURL);
            // Attach encoded JSON string to the POST fields
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            $resp = curl_exec ( $ch );
            $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close ( $ch );
            $array_data =  json_decode($resp,true);
            // var_dump($resp); die;
            // dd(env('SPRING_TOKEN_KEY'));
             if($response_code==200)
             {
                $data = [
                    'parent_id'         =>$parent_id,
                    'business_id'       =>$business_id,
                    'cin_number'     =>$cin,
                    'registration_number'    =>$array_data['output']['source']['registration_number'],
                    'company_name'    =>$array_data['output']['source']['company_name'],
                    'registered_address'    =>$array_data['output']['source']['registered_address'],
                    'date_of_incorporation'    =>$array_data['output']['source']['date_of_incorporation']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_incorporation'])) : NULL,
                    'email_id'    =>array_key_exists("email_id",$array_data['output']['source']) ? $array_data['output']['source']['email_id'] : NULL,
                    'paid_up_capital_in_rupees'    =>$array_data['output']['source']['paid_up_capital_in_rupees'],
                    'authorised_capital'    =>$array_data['output']['source']['authorised_capital'],
                    'company_category'    =>$array_data['output']['source']['company_category'],
                    'company_subcategory'    =>$array_data['output']['source']['company_subcategory'],
                    'company_class'    =>$array_data['output']['source']['company_class'],
                    'whether_company_is_listed'    =>$array_data['output']['source']['whether_company_is_listed'],
                    'company_efilling_status'    =>array_key_exists("company_efilling_status",$array_data['output']['source']) ? $array_data['output']['source']['company_efilling_status'] : NULL,
                    'date_of_last_AGM'    =>$array_data['output']['source']['date_of_last_AGM']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_last_AGM'])) : NULL,
                    'date_of_balance_sheet'    =>$array_data['output']['source']['date_of_balance_sheet']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_balance_sheet'])) : NULL,
                    'another_maintained_address'    =>$array_data['output']['source']['another_maintained_address'],
                    'directors'    => $array_data['output']['source']['directors']!=NULL && count($array_data['output']['source']['directors']) > 0 ? json_encode($array_data['output']['source']['directors']) : NULL,
                    'created_by'            =>$user_id,
                    'created_at'        =>date('Y-m-d H:i:s')
                    ];

                DB::table('cin_check_masters')->insert($data);
                 
                 $master_data = DB::table('cin_check_masters')->select('*')->where(['cin_number'=>$cin])->latest()->first();
 
                 $log_data = [
                    'parent_id'         =>$parent_id,
                    'business_id'       =>$business_id,
                    'service_id'         => $service_id,
                    'source_type'       =>'API',
                    'cin_number'     =>$cin,
                    'registration_number'    =>$array_data['output']['source']['registration_number'],
                    'company_name'    =>$array_data['output']['source']['company_name'],
                    'registered_address'    =>$array_data['output']['source']['registered_address'],
                    'date_of_incorporation'    =>$array_data['output']['source']['date_of_incorporation']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_incorporation'])) : NULL,
                    'email_id'    =>array_key_exists("email_id",$array_data['output']['source']) ? $array_data['output']['source']['email_id'] : NULL,
                    'paid_up_capital_in_rupees'    =>$array_data['output']['source']['paid_up_capital_in_rupees'],
                    'authorised_capital'    =>$array_data['output']['source']['authorised_capital'],
                    'company_category'    =>$array_data['output']['source']['company_category'],
                    'company_subcategory'    =>$array_data['output']['source']['company_subcategory'],
                    'company_class'    =>$array_data['output']['source']['company_class'],
                    'whether_company_is_listed'    =>$array_data['output']['source']['whether_company_is_listed'],
                    'company_efilling_status'    =>array_key_exists("company_efilling_status",$array_data['output']['source']) ? $array_data['output']['source']['company_efilling_status'] : NULL,
                    'date_of_last_AGM'    =>$array_data['output']['source']['date_of_last_AGM']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_last_AGM'])) : NULL,
                    'date_of_balance_sheet'    =>$array_data['output']['source']['date_of_balance_sheet']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_balance_sheet'])) : NULL,
                    'another_maintained_address'    =>$array_data['output']['source']['another_maintained_address'],
                    'directors'    => $array_data['output']['source']['directors']!=NULL && count($array_data['output']['source']['directors']) > 0 ? json_encode($array_data['output']['source']['directors']) : NULL,
                    'is_verified'       =>'1',
                    'price'             =>$price,
                    'user_type'           =>'coc',
                    'user_id'            =>$user_id,
                    'created_at'        =>date('Y-m-d H:i:s')
                    ];
 
                    DB::table('cin_checks')->insert($log_data);
 
                     DB::commit();
                     return response()->json([
                         'fail'      =>false,
                         'data'      =>$master_data 
                     ]);
                 
 
             }
             else if($response_code==404)
             {
                return response()->json([
                    'fail'      =>true,
                    'error'     =>"yes",
                    'error_msg'     =>"It seems like Data Has Not Been Found !!"
                ]);
             }
             else{
 
                return response()->json([
                    'fail'      =>true,
                    'error'     =>"yes",
                    'error_msg'     =>"It seems like CIN Number is Not Valid, Please Try Again later!"
                ]);
             }
         }
         catch (\Exception $e) {
             DB::rollback();
             // something went wrong
             return $e;
         }  
         
             
 
     }

     // check id - court check v1
    public function idCheckCourtV1(Request $request)
    {
         $business_id=Auth::user()->business_id;
         $user_id = Auth::user()->id;
 
         $price=50;
         $service_id=base64_decode($request->service_id); 
 
         $parent_id=Auth::user()->parent_id;
 
         DB::beginTransaction();
         try{

            $data = DB::table('check_price_cocs')->where(['service_id'=>$service_id,'coc_id'=>$business_id])->first();
            if($data!=NULL)
            {
                $price=$data->price;
            }
            else{
                $data=DB::table('check_prices')->where(['service_id'=>$service_id,'business_id'=>$parent_id])->first();
                if($data!=NULL)
                {
                    $price=$data->price;
                }
            }

            $name = $request->input('name');

            $name_match=preg_match('/^[A-Za-z]+([A-Za-z]+\s)*[A-za-z]{3,}$/u', $name);

            if($request->input('name')=='' || !($name_match))
            {
                return response()->json([
                    'fail'      =>true,
                    'error'     =>"yes",
                    'error'     =>"It seems like Name is not valid!"
                ]);
            }

            $father_name = $request->input('fathername');

            if($father_name!='')
            {
                $father_name_match=preg_match('/^[A-Za-z]+([A-Za-z]+\s)*[A-za-z]{3,}$/u', $request->input('fathername'));

                if($request->input('fathername')=='' || !($father_name_match))
                {
                    return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'error'     =>"It seems like Father Name is not valid!"
                    ]);
                }
            }

            $address = $request->input('address');

            if($address!='')
            {
                if($address=='' || strlen($address) < 4)
                {
                    return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'error'     =>"It seems like Address should not be blank or atleast require 4 characters !"
                    ]);
                }
            }

            $state = $request->input('state');

            $type = $request->input('type');

            if($type!='')
            {
                return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'error'     =>"It seems like Type should not be blank !"
                    ]);
            }

            //check first into master table
            $master_data = CourtCheckMasterV1::from('court_check_masters_v1')
                            ->select('*')
                            ->where('name',$name)
                            ->where('type',$type)
                            ->where(function($q){
                                $q->where('father_name',$father_name)
                                ->orWhere('address',$address)
                                ->orWhere('state',$state);
                            })
                            ->first();

            if($master_data !=null){

                CourtCheckV1::create([
                    'parent_id' => $parent_id,
                    'business_id' => $business_id,
                    'service_id' => $service_id,
                    'source_type' =>  'SystemDB',
                    'name' => $master_data->name,
                    'father_name' =>$master_data->father_name,
                    'address' => $master_data->address,
                    'state' => $master_data->state,
                    'type' => $master_data->type,
                    'cases'=>$master_data->cases,
                    'price' => $price,
                    'user_id' => $user_id,
                    'user_type' => 'coc',
                    'created_at' =>date('Y-m-d H:i:s')
                ]);

                DB::commit();
                return response()->json([
                    'fail'      =>false,
                    'data'      =>$master_data 
                ]);
            }
            else
            {
                //check from live API
                // Setup request to send json via POST
                $data = array(
                    'name' => $name,
                    'fatherName' => $father_name,
                    'address' => $address,
                    'state' => $state,
                    'type' => $type,
                    "caseFilter"=> "",
                    "caseCategory"=> "",
                    "caseType"=> "",
                    "caseYear"=> ""
                );
                $payload = json_encode($data);
                
                $apiURL = "https://api.emptra.com/emptra/courtCase";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                curl_setopt ($ch, CURLOPT_POST, 1);
                //curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                //curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                $client_id = 'clientId: '.env('INVINCIBLE_CLIENT_ID');
                $secret_key = 'secretKey: '.env('INVINCIBLE_CLIENT_SECRET');
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $client_id, $secret_key)); // Inject the token into the header
                curl_setopt($ch, CURLOPT_URL, $apiURL);
                // Attach encoded JSON string to the POST fields
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                $resp = curl_exec ( $ch );
                $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close ( $ch );
                $array_data =  json_decode($resp,true);
                //dd($array_data);
                // var_dump($resp); die;
                // dd(env('INVINCIBLE_CLIENT_ID'));

                
                if($response_code==200)
                {
                    $master_data_id = CourtCheckMasterV1::create([
                        'parent_id' => $parent_id,
                        'business_id' => $business_id,
                        'name' => $name,
                        'father_name' =>$father_name,
                        'address' => $address,
                        'state' => $state,
                        'type' => $type,
                        'cases'=>json_encode($array_data['result']['result']['cases']),
                        'created_by'  => $user_id,
                        'created_at' =>date('Y-m-d H:i:s')
                    ]);

                    $master_data_id = $master_data_id->id;

                    $check_data_id = CourtCheckV1::create([
                        'parent_id' => $parent_id,
                        'business_id' => $business_id,
                        'service_id' => $service_id,
                        'source_type' =>  'API',
                        'name' => $name,
                        'father_name' =>$father_name,
                        'address' => $address,
                        'state' => $state,
                        'type' => $type,
                        'cases'=>json_encode($array_data['result']['result']['cases']),
                        'price' => $price,
                        'user_id' => $user_id,
                        'user_type' => 'coc',
                        'created_at' =>date('Y-m-d H:i:s')
                    ]);

                    $master_data = CourtCheckMasterV1::from('court_check_masters_v1')->where(['id'=>$master_data_id])->first();

                    DB::commit();
                    return response()->json([
                        'fail'      =>false,
                        'data'      =>$master_data 
                    ]);
                }
                else{

                    return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'error'     =>"It seems like data not found, Please try again later!"
                    ]);
                }
            }

         }
         catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }  
        
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
