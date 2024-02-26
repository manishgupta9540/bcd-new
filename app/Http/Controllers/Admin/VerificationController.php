<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ImportBulkCriminal;
use App\Models\Admin\ImportBulkEducation;
use App\Models\Admin\JafFormData;
use App\Models\Admin\KeyAccountManager;
use Illuminate\Http\Request;
use Mail;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
// use Validator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use PDF;
use App\Models\CourtCheckMasterV1;
use App\Models\CourtCheckV1;
class VerificationController extends Controller
{
    /**
    *@return void
     */

    /**
     * Show the id checks.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
 
    // public function index()
    // {
    //     $business_id = Auth::user()->business_id; 
    //     $services = DB::table('services')
    //                 ->where(['verification_type'=>'Auto','status'=>1])
    //                 // ->whereNotIn('type_name',['e_court'])
    //                 ->get();

    //     return view('admin.verifications.index',compact('services'));
    //  }

     /**
     * Show the id checks.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
 
    public function idChecks()
    {
        $business_id = Auth::user()->business_id; 
        $services = DB::table('services')
                    ->where(['verification_type'=>'Auto','status'=>1])
                    // ->whereNotIn('type_name',['e_court'])
                    ->get();

        return view('admin.verifications.index',compact('services'));
    }

    /**
     * Show the id check Form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

     public function instantIdCheckForm(Request $request)
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
                                    ->where('parent_id','<>',0)
                                    ->where('status',1)
                                    ->whereNotIn('vital_name',['V4CRI','V4CRT','Vital4Trace'])
                                    ->get();

                    $countries      = DB::table('countries')->get();
                    $state          = DB::table('states')->where('country_id','101')->get();

                    foreach($serviceVital as $servicev){
                        $vtal_cate.= '<option value="'.$servicev->vital_name.'">'.$servicev->vital_name.'('.$servicev->vital_full_name.')</option>';
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
                                <span class="text-muted" style="font-size:10px; id="error">Record Existing or Not<span class="text-danger">*</span></span> 
                                    <select class="form-control record_check" name="record_check" id="record_check"> 
                                        <option value="">-Select-</option> 
                                        <option value="exiting_record">Existing Record</option> 
                                        <option value="fresh_record">Fresh Record</option> 
                                    </select>
                                </div>
                                <div class="col-md-6">
                                <span class="text-muted" style="font-size:10px; id="error">Select Vital Categoryes <span class="text-danger">*</span></span> 
                                <select class="select-option-field-7 form-control vital_list" name="vital_name[]" multiple id="vital_name"> 
                                    <option value="all">-All-</option> 
                                        '.$vtal_cate.'
                                    </select>
                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-vital_name"></p>
                                </div>
                                
                                <div class="col-md-6">
                                    <small class="text-muted" style="font-size:10px; id="error">Name.<span class="text-danger">*</span></small>
                                    <input type="text" name="name" class="form-control name" placeholder="Name."> 
                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-name"></p>
                                </div>
                                <br>

                                <div class="col-md-6 mt-4">
                                    <small class="text-muted" style="font-size:10px;">Id Number </small>
                                    <input type="text" name="subjectId" class="form-control subjectId" placeholder="Id Number"> 
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted" style="font-size:10px; id="error">Relevancy Score (Percentage).</small>
                                    <input type="text" name="relevancyScore" class="form-control relevancyScore" placeholder="Relevancy Score"> 
                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-relevancyScore"></p>
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
                                 
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted" style="font-size:10px;">Address Line 1.</small>
                                    <input type="text" name="address_line_1" class="form-control address_line_1" placeholder="Address Line 1."> 
                                    
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted" style="font-size:10px;">Postal Code.</small>
                                    <input type="text" name="postal_code" class="form-control postal_code" placeholder="Postal Code."> 
                                    
                                </div>
                            </div>
                            <script>
                               $(".vital_list").select2();
                               $(".record_check").select2();
                               $(".vital_list").on("select2:select", function (e) { 
                                var data = e.params.data.id;
                                
                                if(data=="all"){
                                   $(".vital_list > option").prop("selected","selected");
                                   $(".vital_list").trigger("change");
                                }
                             });
                            </script>';
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

     public function instatntVerificationVitallist(Request $request)
     {
        $service_id = $request->vital_id;
    
        $vitalCate = DB::table('service_vitals')
                    ->where(['parent_id'=>$service_id])
                    ->get();
                
        return response()->json([
            'success'  =>true,
            'data'     => $vitalCate,
        ]);
     }

     /**
     * Show the custom verifications.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function bulkVerifications()
    {
        $business_id = Auth::user()->business_id; 
        $services = DB::table('services')->where(['verification_type'=>'Auto','status'=>1])->whereNotIn('type_name',['e_court','covid_19_certificate','telecom'])->get();

        return view('admin.verifications.bulk_verification',compact('services'));
    }

           /**
     * Show the custom verifications.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // public function bulkEducation()
    // {
    //     $business_id = Auth::user()->business_id; 
    //     $services = DB::table('services')->where(['verification_type'=>'Auto','status'=>1])->whereNotIn('type_name',['e_court','covid_19_certificate','telecom'])->get();

    //     return view('admin.verifications.bulk_education',compact('services'));
    // }

    /**
     * Import Excel to insert Candidate Details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function importEducationExcel(Request $request)
    // {
    //     // dd($request->all());
         

    //         DB::beginTransaction();
    //         try
    //         {

    //             $file = $request->file;
    //             if ($file=='undefined') {
    //                 // dd('xyz');
    //                 return response()->json([
    //                     'fail'      =>true,
    //                     'error' => 'empty_file'
                       
    //                 ]);
    //             }
               
               
    //             // dd($file);
    //             $parsed_array = Excel::toArray([], $file);
    //             $imported_data = array_splice($parsed_array[0], 1);
    //             //    dd($imported_data);
    //             //   foreach($request->services as $service){
    //             //     $data[] = $service;
    //             // }
    //             // $service_id =json_encode($data);

    //         $unique = uniqid();

    //         foreach ($imported_data as $value)
    //         {
    //             $display_id = $value[0];
    //             $excel_dob=$value[8];
    //             if($excel_dob!=''){

    //                 $dummy_dob = date('Y-m-d', strtotime($value[8]));
    //             }
    //             else{
    //                 $dummy_dob=NULL;
    //             }
    //           $user=  DB::table('users')->select('business_id','parent_id')->where('display_id',$display_id)->first();

    //           $sla= DB::table('customer_sla')->where('parent_id','0')->first();
    //         //   dd($value[7]);
    //             $name = NULL;
    //             $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value[3].' '.$value[4].' '.$value[5]));
    //             $user_data = 
    //                     [
    //                         'business_id' => $user? $user->business_id:'',
    //                         'parent_id'   =>$user? $user->parent_id:'',
    //                         'unique_id' => $unique,
    //                         'client_display_id' =>$value[0],
    //                         'sla_id'  =>  $sla->id,
    //                         'service_id'=> '15',
    //                         'client_emp_code'  => $value[1] ,
    //                         'entity_code' => $value[2] ,
    //                         'name'  =>  $name,
    //                         'first_name' => $value[3] ,
    //                         'middle_name' => $value[4],
    //                         'last_name'=> $value[5],
    //                         'father_name' => $value[6],
    //                         'aadhar_number' => $value[7],
    //                         'dob' => $dummy_dob,
    //                         'gender'=>$value[9],
    //                         'phone'=>$value[10],
    //                         'email' => $value[11],
    //                         'no_of_verification'=>$value[12],
    //                         'price'=>$value[13],
    //                         'address_1' => $value[14],
    //                         'address_type_1'=>$value[15],
    //                         'address_2' => $value[16],
    //                         'address_type_2'=>$value[17],
    //                         'address_3' => $value[18],
    //                         'address_type_3'=>$value[19],
    //                         'address_4' => $value[20],
    //                         'address_type_4'=>$value[21],
    //                         'address_5' => $value[22],
    //                         'address_type_5'=>$value[23],
    //                         'created_at'=> date('Y-m-d H:i:s')
    //                     ];
                        
    //                     $user_id = DB::table('import_bulk_criminals')->insertGetId($user_data);

    //         }
    //         $excel_dummy = ImportBulkCriminal::where('unique_id',$unique)->get();
    //         // dd($excel_dummy);
    //         $data ='';
            
    //         if (count($excel_dummy)>0) {
            
    //             foreach ($excel_dummy as $dummy) {
    //                 //check condition for first name 
    //                 $client_display_id = $dummy->client_display_id;
    //                 if($dummy->business_id!=0)
    //                 {
    //                     $cl_display_id = $client_display_id;

    //                 } else{
    //                     $cl_display_id = "<span class='text-danger exceldata' contenteditable='true'><input type='hidden' value='".$dummy->id."'>".$client_display_id."</span>";
    //                 }
    //                 if ($dummy->first_name !='') {
    //                     $first_name = $dummy->first_name; 
    //                     $regex = '/^([A-Za-z ]+)$/'; 
    //                     if (preg_match($regex, $first_name)) {
    //                         $first = $first_name ;
    //                     } else { 
    //                         $first = "<span class='text-danger exceldata' contenteditable='true'><input type='hidden' value='".$dummy->id."'>".$first_name ."</span>";
    //                     }
    //                 }
    //                 else{
    //                     $first ="<span class='text-danger exceldata' contenteditable='true'>Required</span>";
    //                 }
    //                 $middle = "";
    //                 //check condition for first name
    //                 if ($dummy->middle_name != '') {
                    
    //                     $middle_name = $dummy->middle_name; 
    //                     $regex = '/^([a-zA-Z ]+)$/'; 
    //                     if (preg_match($regex, $middle_name)) {
    //                         $middle = $middle_name ;
    //                     } else { 
    //                         $middle = "<span class='text-danger exceldata' contenteditable='true'><input type='hidden' value='".$dummy->id."'>".$middle_name ."</span>";
                        
    //                     }
    //                 }
    //                 //   // Last Name Check
    //                 $last='';
    //                 if ($dummy->last_name != '') {
    //                     $last_name = $dummy->last_name; 
    //                     $regex = '/^([a-zA-Z ]+)$/'; 
    //                     if (preg_match($regex, $last_name)) {
    //                         $last = $last_name ;
    //                     } else { 
    //                         $last ="<span class='text-danger exceldata' contenteditable='true'> <input type='hidden' value='".$dummy->id."'>". $last_name ."</span>";
    //                     }           
    //                 }

    //                 if ($dummy->father_name !='') {
    //                      $father_name = $dummy->father_name; 
    //                     $regex = '/^([a-zA-Z ]+)$/'; 
    //                     if (preg_match($regex, $father_name)) {
    //                         $father = $father_name ;
    //                     } else { 
    //                         $father ="<span class='text-danger exceldata' contenteditable='true'><input type='hidden' value='".$dummy->id."'>". $father_name ."</span>";
    //                     } 
    //                 }
    //                 else{

    //                     $father ="<span class='text-danger exceldata' contenteditable='true'><input type='hidden' value='".$dummy->id."'>Required</span>";
    //                 }
    //                 //check Aadhar number
    //                 $aadhar='';
    //                 if ($dummy->aadhar_number != '') {
                        
    //                     $aadhar_number = $dummy->aadhar_number; 
    //                     $regex = ' /^[1-9]{1}[0-9]{11}$/'; 
    //                     if (preg_match($regex, $aadhar_number)) {
    //                         $aadhar = $aadhar_number;
    //                     } else { 
    //                         $aadhar ="<span class='text-danger exceldata' contenteditable='true'><input type='hidden' value='".$dummy->id."'>". $aadhar_number ."</span>";
    //                     }  
    //                 }   

    //                 //Check DOB validation
    //                 if ($dummy->dob !=NULL) {
    //                     $dob = $dummy->dob; 
    //                     $regex = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/'; 
    //                     if (preg_match($regex, $dob)) {
    //                         $birth_date = $dob ;
    //                     } else { 
    //                         $birth_date ="<span class='text-danger exceldata' contenteditable='true'> <input type='hidden' value='".$dummy->id."'>". $dob ."</span>";
    //                     } 
    //                 }
    //                 else{
    //                     $birth_date ="<span class='text-danger exceldata' contenteditable='true'><input type='hidden' value='".$dummy->id."'>Required</span>";
    //                 }

    //                     // Check Gender
    //                 $genders="N/A";
    //                 if ($dummy->gender != '') {
                        
    //                     if ($dummy->gender == 'male' || $dummy->gender == 'female' || $dummy->gender == 'others' ||  $dummy->gender == 'other' || $dummy->gender == 'Male' || $dummy->gender == 'Female' || $dummy->gender == 'Others' ||  $dummy->gender == 'Other'  ) {
    //                         $genders =  $dummy->gender;
    //                     } 
    //                     else {
    //                         $genders = "<span class='text-danger exceldata' contenteditable='true'> <input type='hidden' value='".$dummy->id."'>". $dummy->gender ."</span>";
    //                     }
    //                 }

    //                 // Check Mobile nummber 
    //                  $mob =null;
    //                 if ($dummy->phone != '') {
    //                     $phone = $dummy->phone; 
    //                     $regex = ' /^[1-9]{1}[0-9]{9}$/'; 
    //                     if (preg_match($regex, $phone)) {
    //                         $mob = $phone ;
    //                     } else { 
    //                         $mob = "<span class='text-danger exceldata' contenteditable='true'> <input type='hidden' value='".$dummy->id."'>".$phone ."</span>";
    //                     }  
    //                  }

    //                 //check condition for Email
    //                $email_id ='';
    //                $check_email='';
    //                 if ($dummy->email != '') {
    //                     $email =strtolower($dummy->email);
    //                     $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
    //                     $user_email = DB::table('users')->select('email')->where('email',$email)->first();
    //                     if($user_email){
    //                         $check_email = $user_email->email;
    //                     }
    //                     // dd($user_email);
    //                     if (preg_match($regex, $email) && ($check_email!=$email)) {
    //                         $email_id = $email ;
    //                     } else { 
    //                         $email_id ="<span class='text-danger exceldata' contenteditable='true'> <input type='hidden' value='".$dummy->id."'>". $email ."</span>";
    //                     }   
    //                 }
    //                $no_of_check= $dummy->no_of_verification;
    //                if ($no_of_check<=5) {
    //                   $check=$no_of_check;
    //                } else { 
    //                 $check ="<span class='text-danger exceldata' contenteditable='true'>". $no_of_check ."</span>";
    //                 }   

    //                 $address_1=$dummy->address_1 !=NULL?$dummy->address_1:'-';
    //                 $address_type_1=$dummy->address_type_1 !=NULL?$dummy->address_type_1:'-';
                   
    //                 $address_2=$dummy->address_2 !=NULL?$dummy->address_2:'-';
    //                 $address_type_2=$dummy->address_type_2 !=NULL?$dummy->address_type_2:'-';
                   
    //                 $address_3=$dummy->address_3 !=NULL?$dummy->address_3:'-';
    //                 $address_type_3=$dummy->address_type_3 !=NULL?$dummy->address_type_3:'-';
                    
    //                 $address_4=$dummy->address_4 !=NULL?$dummy->address_4:'-';
    //                 $address_type_4=$dummy->address_type_4 !=NULL?$dummy->address_type_4:'-';
                    
    //                 $address_5=$dummy->address_5 !=NULL?$dummy->address_5:'-';
    //                 $address_type_5=$dummy->address_type_5 !=NULL?$dummy->address_type_5:'-';
                    

    //                 $data .= '<tr><td data-value="client_display_id">'.$cl_display_id.'</td><td data-value="client_emp_code">'.$dummy->client_emp_code.'</td><td data-value="entity_code">'.$dummy->entity_code.'</td><td data-value="first_name">'.$first.'</td><td data-value="middle_name">'.$middle.'</td><td data-value="last_name">'.$last.'</td><td data-value="father_name">'.$father.'</td><td data-value="aadhar_number">'.$aadhar.'</td><td data-value="dob">'.$birth_date.'</td><td data-value="gender">'.$genders.'</td><td data-value="email">'.$email_id .'</td><td data-value="phone">'.$mob.'</td><td data-value="no_of_verification"> '.$check.'</td><td data-value="price"> '.$dummy->price.'</td><td data-value="address_1">'.$address_1.'</td><td data-value="address_type_1">'.$address_type_1.'</td><td data-value="address_2">'.$address_2.'</td><td data-value="address_type_2">'.$address_type_2.'</td><td data-value="address_3">'.$address_3.'</td><td data-value="address_type_3">'.$address_type_3.'</td><td data-value="address_4">'.$address_4.'</td><td data-value="address_type_4">'.$address_type_4.'</td><td data-value="address_5">'.$address_5.'</td><td data-value="address_type_5">'.$address_type_5.'</td></tr>';

    //             }
    //             // dd($data);
    //         }
    //         else
    //         {
    //             $data .= '<tr><td>'.'No Data Found'.'</td></tr>';
    //         }

    //         DB::commit();
    //         return response()->json([
    //             'fail'      =>false,
    //             'excel' => $data,
    //             'unique_excel_id' =>$unique
            
    //         ]);
    //         }
    //         catch (\Exception $e) {
    //             DB::rollback();
    //             // something went wrong
    //             return $e;
    //         }       
    // }

    /**
     * Show the custom verifications.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function bulkCriminal()
    {
        $business_id = Auth::user()->business_id; 
        $services = DB::table('services')->where(['status'=>1])->whereIn('type_name',['educational','criminal'])->get();

        return view('admin.verifications.bulk_criminal',compact('services'));
    }

      /**
     * Import Excel to insert Candidate Details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function importExcel(Request $request)
    {
        // dd($request->all());
         

            DB::beginTransaction();
            try
            {

                $file = $request->file;
                if ($file=='undefined') {
                    // dd('xyz');
                    return response()->json([
                        'fail'      =>true,
                        'error' => 'empty_file'
                       
                    ]);
                }
               
               
                // dd($file);
                $parsed_array = Excel::toArray([], $file);
                $heading_data = array_splice($parsed_array[0], 0);
                $parsed_array = Excel::toArray([], $file);
                $imported_data = array_splice($parsed_array[0], 1);
                $service_id =$request->service_id;
                //    dd($service_id);
                //   foreach($request->services as $service){
                //     $data[] = $service;
                // }$heading_data[0][1]
                // $service_id =json_encode($data);

                $unique = uniqid();
                if (stripos($heading_data[0][14],"full_address-1")!==false && $service_id=='15') {
                    foreach ($imported_data as $value)
                    {
                        if ($value[3]==NULL && $value[6]==NULL && $value[8]==NULL) {
                            continue;
                        }
                           
                        $display_id = $value[0];
                        $excel_dob=$value[8];
                        if($excel_dob!=''){

                            $dummy_dob = date('Y-m-d', strtotime($value[8]));
                        }
                        else{
                            $dummy_dob=NULL;
                            }
                        $user=  DB::table('users')->select('business_id','parent_id')->where('display_id',$display_id)->first();

                        $sla= DB::table('customer_sla')->where('parent_id','0')->first();
                        //   dd($value[7]);
                        $name = NULL;
                        $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value[3].' '.$value[4].' '.$value[5]));
                        $user_data = 
                                [
                                    'business_id' => $user? $user->business_id:'',
                                    'parent_id'   =>$user? $user->parent_id:'',
                                    'unique_id' => $unique,
                                    'client_display_id' =>$value[0],
                                    'sla_id'  =>  $sla->id,
                                    'service_id'=> '15',
                                    'client_emp_code'  => $value[1] ,
                                    'entity_code' => $value[2] ,
                                    'name'  =>  $name,
                                    'first_name' => $value[3] ,
                                    'middle_name' => $value[4],
                                    'last_name'=> $value[5],
                                    'father_name' => $value[6],
                                    'aadhar_number' => $value[7],
                                    'dob' => $dummy_dob,
                                    'gender'=>$value[9],
                                    'phone'=>$value[10],
                                    'email' => $value[11],
                                    'no_of_verification'=>$value[12],
                                    'price'=>$value[13],
                                    'address_1' => $value[14],
                                    'address_type_1'=>$value[15],
                                    'address_2' => $value[16],
                                    'address_type_2'=>$value[17],
                                    'address_3' => $value[18],
                                    'address_type_3'=>$value[19],
                                    'address_4' => $value[20],
                                    'address_type_4'=>$value[21],
                                    'address_5' => $value[22],
                                    'address_type_5'=>$value[23],
                                    'created_at'=> date('Y-m-d H:i:s')
                                ];
                                
                                $user_id = DB::table('import_bulk_criminals')->insertGetId($user_data);
                            // }
                    }
                    $excel_dummy = ImportBulkCriminal::where('unique_id',$unique)->get();
                
                    // dd($excel_dummy);
                    $data ='';
                    $data.='<thead>
                                <tr>
                                    <th scope="col">Client ID</th>
                                    <th scope="col">Client emp code</th>
                                    <th scope="col">Entity code </th>
                                    <th scope="col">First Name</th>
                                    <th scope="col">Middle Name</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">Father Name</th>
                                    <th scope="col">Aadhar Number</th>
                                    <th scope="col">DOB</th>
                                    <th scope="col">Gender</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">No. of checks</th>
                                    <th scope="col">Price <small>(price is in Rupee) </small></th>
                                    <th scope="col">Address-1</th>
                                    <th scope="col">Address Type-1</th>
                                    <th scope="col">Address-2</th>
                                    <th scope="col">Address Type-2</th>
                                    <th scope="col">Address-3</th>
                                    <th scope="col">Address Type-3</th>
                                    <th scope="col">Address-4</th>
                                    <th scope="col">Address Type-4</th>
                                    <th scope="col">Address-5</th>
                                    <th scope="col">Address Type-5</th>
                                </tr>
                            </thead> <tbody >';
                    if (count($excel_dummy)>0) {
                    
                        foreach ($excel_dummy as $dummy) {
                            //check condition for first name 
                            $client_display_id = $dummy->client_display_id;
                            if($dummy->business_id!=0)
                            {
                                $cl_display_id = $client_display_id;

                            } else{
                                $cl_display_id = "<span class='text-danger exceldata' contenteditable='true'><input type='hidden' value='".$dummy->id."'>".$client_display_id."</span>";
                            }
                            if ($dummy->first_name !='') {
                                $first_name = $dummy->first_name; 
                                $regex = '/^([A-Za-z ]+)$/'; 
                                if (preg_match($regex, $first_name)) {
                                    $first = $first_name ;
                                } else { 
                                    $first = "<span class='text-danger exceldata' contenteditable='true'><input type='hidden' value='".$dummy->id."'>".$first_name ."</span>";
                                }
                            }
                            else{
                                $first ="<span class='text-danger exceldata' contenteditable='true'>Required</span>";
                            }
                            $middle = "";
                            //check condition for first name
                            if ($dummy->middle_name != '') {
                            
                                $middle_name = $dummy->middle_name; 
                                $regex = '/^([a-zA-Z ]+)$/'; 
                                if (preg_match($regex, $middle_name)) {
                                    $middle = $middle_name ;
                                } else { 
                                    $middle = "<span class='text-danger exceldata' contenteditable='true'><input type='hidden' value='".$dummy->id."'>".$middle_name ."</span>";
                                
                                }
                            }
                            //   // Last Name Check
                            $last='';
                            if ($dummy->last_name != '') {
                                $last_name = $dummy->last_name; 
                                $regex = '/^([a-zA-Z ]+)$/'; 
                                if (preg_match($regex, $last_name)) {
                                    $last = $last_name ;
                                } else { 
                                    $last ="<span class='text-danger exceldata' contenteditable='true'> <input type='hidden' value='".$dummy->id."'>". $last_name ."</span>";
                                }           
                            }

                            if ($dummy->father_name !='') {
                                $father_name = $dummy->father_name; 
                                $regex = '/^([a-zA-Z ]+)$/'; 
                                if (preg_match($regex, $father_name)) {
                                    $father = $father_name ;
                                } else { 
                                    $father ="<span class='text-danger exceldata' contenteditable='true'><input type='hidden' value='".$dummy->id."'>". $father_name ."</span>";
                                } 
                            }
                            else{

                                $father ="<span class='text-danger exceldata' contenteditable='true'><input type='hidden' value='".$dummy->id."'>Required</span>";
                            }
                            //check Aadhar number
                            $aadhar='';
                            if ($dummy->aadhar_number != '') {
                                
                                $aadhar_number = $dummy->aadhar_number; 
                                $regex = ' /^[1-9]{1}[0-9]{11}$/'; 
                                if (preg_match($regex, $aadhar_number)) {
                                    $aadhar = $aadhar_number;
                                } else { 
                                    $aadhar ="<span class='text-danger exceldata' contenteditable='true'><input type='hidden' value='".$dummy->id."'>". $aadhar_number ."</span>";
                                }  
                            }   

                            //Check DOB validation
                            if ($dummy->dob !=NULL) {
                                $dob = $dummy->dob; 
                                $regex = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/'; 
                                if (preg_match($regex, $dob)) {
                                    $birth_date = $dob ;
                                } else { 
                                    $birth_date ="<span class='text-danger exceldata' contenteditable='true'> <input type='hidden' value='".$dummy->id."'>". $dob ."</span>";
                                } 
                            }
                            else{
                                $birth_date ="<span class='text-danger exceldata' contenteditable='true'><input type='hidden' value='".$dummy->id."'>Required</span>";
                            }

                                // Check Gender
                            $genders="N/A";
                            if ($dummy->gender != '') {
                                
                                if ($dummy->gender == 'male' || $dummy->gender == 'female' || $dummy->gender == 'others' ||  $dummy->gender == 'other' || $dummy->gender == 'Male' || $dummy->gender == 'Female' || $dummy->gender == 'Others' ||  $dummy->gender == 'Other'  ) {
                                    $genders =  $dummy->gender;
                                } 
                                else {
                                    $genders = "<span class='text-danger exceldata' contenteditable='true'> <input type='hidden' value='".$dummy->id."'>". $dummy->gender ."</span>";
                                }
                            }

                            // Check Mobile nummber 
                            $mob =null;
                            if ($dummy->phone != '') {
                                $phone = $dummy->phone; 
                                $regex = ' /^[1-9]{1}[0-9]{9}$/'; 
                                if (preg_match($regex, $phone)) {
                                    $mob = $phone ;
                                } else { 
                                    $mob = "<span class='text-danger exceldata' contenteditable='true'> <input type='hidden' value='".$dummy->id."'>".$phone ."</span>";
                                }  
                            }

                            //check condition for Email
                            $email_id ='';
                            $check_email='';
                            if ($dummy->email != '') {
                                $email =strtolower($dummy->email);
                                $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
                                // $user_email = DB::table('candidate_reinitiates')->select('email')->where('email',$email)->first();
                                // if($user_email){
                                //     $check_email = $user_email->email;
                                // }
                                // dd($user_email);
                                // if (preg_match($regex, $email) && ($check_email!=$email)) {
                                if (preg_match($regex, $email)) {
                                    $email_id = $email ;
                                } else { 
                                    $email_id ="<span class='text-danger exceldata' contenteditable='true'> <input type='hidden' value='".$dummy->id."'>". $email ."</span>";
                                }   
                            }
                            $no_of_check= $dummy->no_of_verification;
                            if ($no_of_check<=5) {
                                $check=$no_of_check;
                            } else { 
                                $check ="<span class='text-danger exceldata' contenteditable='true'>". $no_of_check ."</span>";
                            }   

                            $address_1=$dummy->address_1 !=NULL?$dummy->address_1:'-';
                            $address_type_1=$dummy->address_type_1 !=NULL?$dummy->address_type_1:'-';
                        
                            $address_2=$dummy->address_2 !=NULL?$dummy->address_2:'-';
                            $address_type_2=$dummy->address_type_2 !=NULL?$dummy->address_type_2:'-';
                        
                            $address_3=$dummy->address_3 !=NULL?$dummy->address_3:'-';
                            $address_type_3=$dummy->address_type_3 !=NULL?$dummy->address_type_3:'-';
                            
                            $address_4=$dummy->address_4 !=NULL?$dummy->address_4:'-';
                            $address_type_4=$dummy->address_type_4 !=NULL?$dummy->address_type_4:'-';
                            
                            $address_5=$dummy->address_5 !=NULL?$dummy->address_5:'-';
                            $address_type_5=$dummy->address_type_5 !=NULL?$dummy->address_type_5:'-';
                            

                            $data .= '<tr><td data-value="client_display_id">'.$cl_display_id.'</td><td data-value="client_emp_code">'.$dummy->client_emp_code.'</td><td data-value="entity_code">'.$dummy->entity_code.'</td><td data-value="first_name">'.$first.'</td><td data-value="middle_name">'.$middle.'</td><td data-value="last_name">'.$last.'</td><td data-value="father_name">'.$father.'</td><td data-value="aadhar_number">'.$aadhar.'</td><td data-value="dob">'.$birth_date.'</td><td data-value="gender">'.$genders.'</td><td data-value="email">'.$email_id .'</td><td data-value="phone">'.$mob.'</td><td data-value="no_of_verification"> '.$check.'</td><td data-value="price"> '.$dummy->price.'</td><td data-value="address_1">'.$address_1.'</td><td data-value="address_type_1">'.$address_type_1.'</td><td data-value="address_2">'.$address_2.'</td><td data-value="address_type_2">'.$address_type_2.'</td><td data-value="address_3">'.$address_3.'</td><td data-value="address_type_3">'.$address_type_3.'</td><td data-value="address_4">'.$address_4.'</td><td data-value="address_type_4">'.$address_type_4.'</td><td data-value="address_5">'.$address_5.'</td><td data-value="address_type_5">'.$address_type_5.'</td></tr>';

                        }
                        // dd($data);
                    }
                    else
                    {
                        $data .= '<tr><td>'.'No Data Found'.'</td></tr>';
                    }
                    $data .='</tbody>';
                }
                else if (stripos($heading_data[0][14],"University/Board-1")!==false && $service_id=='11') {
                    foreach ($imported_data as $value)
                    {
                        if ($value[3]==NULL && $value[6]==NULL && $value[8]==NULL) {
                            continue;
                        }
                        
                        $display_id = $value[0];
                        $excel_dob=$value[8];
                        if($excel_dob!=''){

                            $dummy_dob = date('Y-m-d', strtotime($value[8]));
                        }
                        else{
                            $dummy_dob=NULL;
                        }
                        $user=  DB::table('users')->select('business_id','parent_id')->where('display_id',$display_id)->first();

                    $sla= DB::table('customer_sla')->where('parent_id','0')->first();
                        //   dd($value[7]);
                        $name = NULL;
                        $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value[3].' '.$value[4].' '.$value[5]));
                        $user_data = 
                                [
                                    'business_id' => $user? $user->business_id:'',
                                    'parent_id'   =>$user? $user->parent_id:'',
                                    'unique_id' => $unique,
                                    'client_display_id' =>$value[0],
                                    'sla_id'  =>  $sla->id,
                                    'service_id'=> '11',
                                    'client_emp_code'  => $value[1] ,
                                    'entity_code' => $value[2] ,
                                    'name'  =>  $name,
                                    'first_name' => $value[3] ,
                                    'middle_name' => $value[4],
                                    'last_name'=> $value[5],
                                    'father_name' => $value[6],
                                    'aadhar_number' => $value[7],
                                    'dob' => $dummy_dob,
                                    'gender'=>$value[9],
                                    'phone'=>$value[10],
                                    'email' => $value[11],
                                    'no_of_checks'=>$value[12],
                                    'price'=>$value[13],
                                    'university_board_name_1' => $value[14],
                                    'degree_1'=>$value[15],
                                    'registration_enroll_1' => $value[16],
                                    'year_of_qualification_1'=>$value[17],
                                    'university_board_name_2' => $value[18],
                                    'degree_2'=>$value[19],
                                    'registration_enroll_2' => $value[20],
                                    'year_of_qualification_2'=>$value[21],
                                    'university_board_name_3' => $value[22],
                                    'degree_3'=>$value[23],
                                    'registration_enroll_3' => $value[24],
                                    'year_of_qualification_3'=>$value[25],
                                    'university_board_name_4' => $value[26],
                                    'degree_4'=>$value[27],
                                    'registration_enroll_4' => $value[28],
                                    'year_of_qualification_4'=>$value[29],
                                    'university_board_name_5' => $value[30],
                                    'degree_5'=>$value[31],
                                    'registration_enroll_5' => $value[32],
                                    'year_of_qualification_5'=>$value[33],
                                    'created_at'=> date('Y-m-d H:i:s')
                                ];
                                
                                $user_id = DB::table('import_bulk_education')->insertGetId($user_data);

                    }
                    $excel_dummy = ImportBulkEducation::where('unique_id',$unique)->get();
                    // dd($excel_dummy);
                    $data ='';
                    $data.='<thead>
                                <tr>
                                    <th scope="col">Client ID</th>
                                    <th scope="col">Client emp code</th>
                                    <th scope="col">Entity code </th>
                                    <th scope="col">First Name</th>
                                    <th scope="col">Middle Name</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">Father Name</th>
                                    <th scope="col">Aadhar Number</th>
                                    <th scope="col">DOB</th>
                                    <th scope="col">Gender</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">No. of checks</th>
                                    <th scope="col">Price <small>(price is in Rupee) </small></th>
                                    <th scope="col">University/Board-1</th>
                                    <th scope="col">degree-1</th>
                                    <th scope="col">Enrollment/Registration No-1</th>
                                    <th scope="col">year of verification-1</th>
                                    <th scope="col">University/Board-2</th>
                                    <th scope="col">degree-2</th>
                                    <th scope="col">Enrollment/Registration No-2</th>
                                    <th scope="col">year of verification-2</th>
                                    <th scope="col">University/Board-3</th>
                                    <th scope="col">degree-3</th>
                                    <th scope="col">Enrollment/Registration No-3</th>
                                    <th scope="col">year of verification-3</th>
                                    <th scope="col">University/Board-4</th>
                                    <th scope="col">degree-4</th>
                                    <th scope="col">Enrollment/Registration No-4</th>
                                    <th scope="col">year of verification-4</th>
                                    <th scope="col">University/Board-5</th>
                                    <th scope="col">degree-5</th>
                                    <th scope="col">Enrollment/Registration No-5</th>
                                    <th scope="col">year of verification-5</th>
                                </tr>
                            </thead> <tbody >';
                    if (count($excel_dummy)>0) {
                    
                        foreach ($excel_dummy as $dummy) {
                            //check condition for first name 
                            $client_display_id = $dummy->client_display_id;
                            if($dummy->business_id!=0)
                            {
                                $cl_display_id = $client_display_id;

                            } else{
                                $cl_display_id = "<span class='text-danger exceldata' contenteditable='false'><input type='hidden' value='".$dummy->id."'>".$client_display_id."</span>";
                            }
                            if ($dummy->first_name !='') {
                                $first_name = $dummy->first_name; 
                                $regex = '/^([A-Za-z ]+)$/'; 
                                if (preg_match($regex, $first_name)) {
                                    $first = $first_name ;
                                } else { 
                                    $first = "<span class='text-danger exceldata' contenteditable='false'><input type='hidden' value='".$dummy->id."'>".$first_name ."</span>";
                                }
                            }
                            else{
                                $first ="<span class='text-danger exceldata' contenteditable='false'>Required</span>";
                            }
                            $middle = "";
                            //check condition for first name
                            if ($dummy->middle_name != '') {
                            
                                $middle_name = $dummy->middle_name; 
                                $regex = '/^([a-zA-Z ]+)$/'; 
                                if (preg_match($regex, $middle_name)) {
                                    $middle = $middle_name ;
                                } else { 
                                    $middle = "<span class='text-danger exceldata' contenteditable='false'><input type='hidden' value='".$dummy->id."'>".$middle_name ."</span>";
                                
                                }
                            }
                            //   // Last Name Check
                            $last='';
                            if ($dummy->last_name != '') {
                                $last_name = $dummy->last_name; 
                                $regex = '/^([a-zA-Z ]+)$/'; 
                                if (preg_match($regex, $last_name)) {
                                    $last = $last_name ;
                                } else { 
                                    $last ="<span class='text-danger exceldata' contenteditable='false'> <input type='hidden' value='".$dummy->id."'>". $last_name ."</span>";
                                }           
                            }

                            if ($dummy->father_name !='') {
                                $father_name = $dummy->father_name; 
                                $regex = '/^([a-zA-Z ]+)$/'; 
                                if (preg_match($regex, $father_name)) {
                                    $father = $father_name ;
                                } else { 
                                    $father ="<span class='text-danger exceldata' contenteditable='false'><input type='hidden' value='".$dummy->id."'>". $father_name ."</span>";
                                } 
                            }
                            else{

                                $father ="<span class='text-danger exceldata' contenteditable='false'><input type='hidden' value='".$dummy->id."'>Required</span>";
                            }
                            //check Aadhar number
                            $aadhar='';
                            if ($dummy->aadhar_number != '') {
                                
                                $aadhar_number = $dummy->aadhar_number; 
                                $regex = ' /^[1-9]{1}[0-9]{11}$/'; 
                                if (preg_match($regex, $aadhar_number)) {
                                    $aadhar = $aadhar_number;
                                } else { 
                                    $aadhar ="<span class='text-danger exceldata' contenteditable='false'><input type='hidden' value='".$dummy->id."'>". $aadhar_number ."</span>";
                                }  
                            }   

                            //Check DOB validation
                            if ($dummy->dob !=NULL) {
                                $dob = $dummy->dob; 
                                $regex = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/'; 
                                if (preg_match($regex, $dob)) {
                                    $birth_date = $dob ;
                                } else { 
                                    $birth_date ="<span class='text-danger exceldata' contenteditable='false'> <input type='hidden' value='".$dummy->id."'>". $dob ."</span>";
                                } 
                            }
                            else{
                                $birth_date ="<span class='text-danger exceldata' contenteditable='false'><input type='hidden' value='".$dummy->id."'>Required</span>";
                            }

                                // Check Gender
                            $genders="N/A";
                            if ($dummy->gender != '') {
                                
                                if ($dummy->gender == 'male' || $dummy->gender == 'female' || $dummy->gender == 'others' ||  $dummy->gender == 'other' || $dummy->gender == 'Male' || $dummy->gender == 'Female' || $dummy->gender == 'Others' ||  $dummy->gender == 'Other'  ) {
                                    $genders =  $dummy->gender;
                                } 
                                else {
                                    $genders = "<span class='text-danger exceldata' contenteditable='false'> <input type='hidden' value='".$dummy->id."'>". $dummy->gender ."</span>";
                                }
                            }

                            // Check Mobile nummber 
                            $mob =null;
                            if ($dummy->phone != '') {
                                $phone = $dummy->phone; 
                                $regex = ' /^[1-9]{1}[0-9]{9}$/'; 
                                if (preg_match($regex, $phone)) {
                                    $mob = $phone ;
                                } else { 
                                    $mob = "<span class='text-danger exceldata' contenteditable='false'> <input type='hidden' value='".$dummy->id."'>".$phone ."</span>";
                                }  
                            }

                            //check condition for Email
                            $email_id ='';
                            $check_email='';
                                if ($dummy->email != '') {
                                    $email =strtolower($dummy->email);
                                    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
                                    // $user_email = DB::table('users')->select('email')->where('email',$email)->first();
                                    // if($user_email){
                                    //     $check_email = $user_email->email;
                                    // }
                                    // dd($user_email);
                                    // if (preg_match($regex, $email) && ($check_email!=$email)) {
                                    if (preg_match($regex, $email)) {
                                        $email_id = $email ;
                                    } else { 
                                        $email_id ="<span class='text-danger exceldata' contenteditable='false'> <input type='hidden' value='".$dummy->id."'>". $email ."</span>";
                                    }   
                                }
                            $no_of_check= $dummy->no_of_checks;
                            if ($no_of_check<=5) {
                                $check=$no_of_check;
                            } else { 
                                $check ="<span class='text-danger exceldata' contenteditable='false'>". $no_of_check ."</span>";
                            }   

                            $university_board_name_1=$dummy->university_board_name_1 !=NULL?$dummy->university_board_name_1:'-';
                            $degree_1=$dummy->degree_1 !=NULL?$dummy->degree_1:'-';
                            $registration_enroll_1=$dummy->registration_enroll_1 !=NULL?$dummy->registration_enroll_1:'-';
                            $year_of_qualification_1=$dummy->year_of_qualification_1 !=NULL?$dummy->year_of_qualification_1:'-';
                        
                            $university_board_name_2=$dummy->university_board_name_2 !=NULL?$dummy->university_board_name_2:'-';
                            $degree_2=$dummy->degree_2 !=NULL?$dummy->degree_2:'-';
                            $registration_enroll_2=$dummy->registration_enroll_2 !=NULL?$dummy->registration_enroll_2:'-';
                            $year_of_qualification_2=$dummy->year_of_qualification_2 !=NULL?$dummy->year_of_qualification_2:'-';
                        
                            $university_board_name_3=$dummy->university_board_name_3 !=NULL?$dummy->university_board_name_3:'-';
                            $degree_3=$dummy->degree_3 !=NULL?$dummy->degree_3:'-';
                            $registration_enroll_3=$dummy->registration_enroll_3 !=NULL?$dummy->registration_enroll_3:'-';
                            $year_of_qualification_3=$dummy->year_of_qualification_3 !=NULL?$dummy->year_of_qualification_3:'-';
                            
                            $university_board_name_4=$dummy->university_board_name_4 !=NULL?$dummy->university_board_name_4:'-';
                            $degree_4=$dummy->degree_4 !=NULL?$dummy->degree_4:'-';
                            $registration_enroll_4=$dummy->registration_enroll_4 !=NULL?$dummy->registration_enroll_4:'-';
                            $year_of_qualification_4=$dummy->year_of_qualification_4 !=NULL?$dummy->year_of_qualification_4:'-';

                            $university_board_name_5=$dummy->university_board_name_5 !=NULL?$dummy->university_board_name_5:'-';
                            $degree_5=$dummy->degree_5 !=NULL?$dummy->degree_5:'-';
                            $registration_enroll_5=$dummy->registration_enroll_5 !=NULL?$dummy->registration_enroll_5:'-';
                            $year_of_qualification_5=$dummy->year_of_qualification_5 !=NULL?$dummy->year_of_qualification_5:'-';
                            

                            $data .= '<tr><td data-value="client_display_id">'.$cl_display_id.'</td><td data-value="client_emp_code">'.$dummy->client_emp_code.'</td><td data-value="entity_code">'.$dummy->entity_code.'</td><td data-value="first_name">'.$first.'</td><td data-value="middle_name">'.$middle.'</td><td data-value="last_name">'.$last.'</td><td data-value="father_name">'.$father.'</td><td data-value="aadhar_number">'.$aadhar.'</td><td data-value="dob">'.$birth_date.'</td><td data-value="gender">'.$genders.'</td><td data-value="email">'.$email_id .'</td><td data-value="phone">'.$mob.'</td><td data-value="no_of_verification"> '.$check.'</td><td data-value="price"> '.$dummy->price.'</td><td data-value="university_board_name_1">'.$university_board_name_1.'</td><td data-value="degree_1">'.$degree_1.'</td><td data-value="registration_enroll_1">'.$registration_enroll_1.'</td><td data-value="year_of_qualification_1">'.$year_of_qualification_1.'</td><td data-value="university_board_name_2">'.$university_board_name_2.'</td><td data-value="degree_2">'.$degree_2.'</td><td data-value="registration_enroll_2">'.$registration_enroll_2.'</td><td data-value="year_of_qualification_2">'.$year_of_qualification_2.'</td><td data-value="university_board_name_3">'.$university_board_name_3.'</td><td data-value="degree_3">'.$degree_3.'</td><td data-value="registration_enroll_3">'.$registration_enroll_3.'</td><td data-value="year_of_qualification_3">'.$year_of_qualification_3.'</td><td data-value="university_board_name_4">'.$university_board_name_4.'</td><td data-value="degree_4">'.$degree_4.'</td><td data-value="registration_enroll_4">'.$registration_enroll_4.'</td><td data-value="year_of_qualification_4">'.$year_of_qualification_4.'</td><td data-value="university_board_name_5">'.$university_board_name_5.'</td><td data-value="degree_5">'.$degree_5.'</td><td data-value="registration_enroll_5">'.$registration_enroll_5.'</td><td data-value="year_of_qualification_5">'.$year_of_qualification_5.'</td></tr>';

                        }
                        
                    }
                    else
                    {
                        $data .= '<tr><td>'.'No Data Found'.'</td></tr>';
                    }
                    $data .='</tbody>';
                }
                else{
                    return response()->json([
                        'fail'      =>true,
                        'error' => 'wrong'
                    
                    ]);
                }

                DB::commit();
                return response()->json([
                    'fail'      =>false,
                    'excel' => $data,
                    'unique_excel_id' =>$unique
                
                ]);
            }
            catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return $e;
            }       
    }

    public function updateMultipleCriminal(Request $request)
    {
        $id= $request->id;
        $value = $request->field_value;
        $name = $request->field_name;
       $import_data= DB::table('import_bulk_criminals')->where('id',$id)->first();
       if ($id) {
            
        DB::table('import_bulk_criminals')->where('id',$id)->update([$name=>$value]);
       
        }
        if($name){
            if($name=="client_display_id"){
                if($import_data->business_id==0)
                {
                    return response()->json([
                        'fail'      =>true,
                    ]);
                }
            }
            if($name=="first_name"){
                if ($value!='') {
                   
                    $regex = '/^([A-Za-z ]+)$/'; 
                    if (preg_match($regex, $value)) {
                        return response()->json([
                            'fail'      =>false,
                           
                        ]);

                    } else { 
                        return response()->json([
                            'fail'      =>true,
                        ]);
                    }
                }
                else{
                    return response()->json([
                        'fail'      =>true,
                        'error' =>'required',
                    ]);
                }
            }
            if($name=="middle_name"){
                if ($value!='') {
                   
                    $regex = '/^([A-Za-z ]+)$/'; 
                    if (preg_match($regex, $value)) {
                        return response()->json([
                            'fail'      =>false,
                           
                        ]); 
                    } else { 
                        return response()->json([
                            'fail'      =>true,
                        ]);
                    }
                }
            }
            if($name=="last_name"){
                if ($value!='') {
                   
                    $regex = '/^([A-Za-z ]+)$/'; 
                    if (preg_match($regex, $value)) {
                        return response()->json([
                            'fail'      =>false,
                           
                        ]);
                    } else { 
                        return response()->json([
                            'fail'      =>true,
                        ]);
                    }
                }
               
            }
            if($name=="father_name"){
                if ($value!='') {
                   
                    $regex = '/^([A-Za-z ]+)$/'; 
                    if (preg_match($regex, $value)) {
                        return response()->json([
                            'fail'      =>false,
                           
                        ]);
                    } else { 
                        return response()->json([
                            'fail'      =>true,
                        ]);
                    }
                }
                else{
                    return response()->json([
                        'fail'      =>true,
                        'error' =>'required',
                    ]);
                }
            }
            if($name=="aadhar_number"){
                if ($value!='') {
                   
                    $regex = '/^[1-9]{1}[0-9]{11}$/'; 
                    if (preg_match($regex, $value)) {
                        return response()->json([
                            'fail'      =>false,
                           
                        ]);
                    } else { 
                        return response()->json([
                            'fail'      =>true,
                        ]);
                    }
                }
               
            }
            if($name=="dob"){
                if ($value!='') {
                   
                    $regex = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/'; 
                    if (preg_match($regex, $value)) {
                        return response()->json([
                            'fail'      =>false,
                           
                        ]);
                    } else { 
                        return response()->json([
                            'fail'      =>true,
                        ]);
                    }
                }
                else{
                    return response()->json([
                        'fail'      =>true,
                        'error' =>'required',
                    ]);
                }
            }
            if($name=="gender"){
                if ($value!='') {
                    if ($value == 'male' || $value == 'female' || $value == 'others' ||  $value == 'other' || $value == 'Male' || $value == 'Female' || $value == 'Others' ||  $value == 'Other' || $value =='N/A' ) {
                        return response()->json([
                            'fail'      =>false,
                           
                        ]);
                    } else { 
                        return response()->json([
                            'fail'      =>true,
                        ]);
                    }
                }
               
            }
            if($name=="email"){
                if ($value!='') {
                    $email =strtolower($value);
                    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
                    //  $user_email = DB::table('users')->select('email')->where('email',$email)->first();
                    // if($user_email){
                    //     return response()->json([
                    //         'fail'=>true,
                    //         'error' => 'unique',
                    //     ]);
                    // }
                    if (preg_match($regex, $value)) {
                        return response()->json([
                            'fail'      =>false,
                           
                        ]);
                    } else { 
                        return response()->json([
                            'fail'      =>true,
                        ]);
                    }
                }
               
            }

        }
       
        // dd($request->field_value);
      
    }
    //store Multiple Candidate
    public function storeMultiple(Request $request)
    {
        // dd();
        // $tat = $request->input('tats');
        // $tats = explode(',', $tat);
        $service_id=$request->service_id;
        $unique = $request->input('unique_id');
        // $customer_id = $request->input('customer_id');
        // $sla_id = $request->input('sla_id');
        // $sla_type = $request->input('sla_type');
        // $service_id = $request->input('service_id');
        // $service_unit = $request->input('service_units');
        // $tat = $request->input('tats');
        // $incentive = $request->input('incentives');
        // $penalty = $request->input('penalties');
        // $check_price = $request->input('check_prices');
        // $days_type = $request->input('days_types');
        // $price_type = $request->input('price_types');
        // $package_price = $request->package_price;
        $business_id = Auth::user()->business_id;
        // $services = explode(',', $service_id);
        // $service_unit = explode(',', $service_unit);
        // $tats = explode(',', $tat);
        // $incentives = explode(',', $incentive);
        // $penalties = explode(',', $penalty);
        // $check_prices = explode(',', $check_price);
        // $max_service_tat = max($tats);
        // dd($key);
        $customer_sla=DB::table('customer_sla')->where('parent_id','0')->first();
            // DB::beginTransaction();
            // try{
         
         
           if ($service_id==15) {
                $excel_dummy = ImportBulkCriminal::where('unique_id',$unique)->get();
                $data ='';
                $i=0;
                if (count($excel_dummy)>0) {
                    foreach ($excel_dummy as $dummy) {
                        $client_display_id ='';
                        $client_display_id = $dummy->client_display_id;
                        if($dummy->business_id!=null)
                        {
                            $cl_display_id = $client_display_id;
                            // dd($cl_display_id);

                        } else{
                            
                            continue;
                        }

                        //check condition for first name
                        if ($dummy->first_name !='') {    
                            $first_name = $dummy->first_name; 
                            $regex = '/^([a-zA-Z ]+)$/'; 
                            if (preg_match($regex, $first_name)) {
                            $first = $first_name ;
                            } else { 
                            continue;
                            } 
                        }
                        else{

                        continue;
                        }
                        $middle = "";
                        //check condition for middle name
                        if ($dummy->middle_name != '') {
                            
                            $middle_name = $dummy->middle_name; 
                            $regex = '/^([a-zA-Z ]+)$/'; 
                            if (preg_match($regex, $middle_name)) {
                            $middle = $middle_name ;
                            } else { 
                            continue;
                            }           
                        }

                        //   // Last Name Check
                        $last='';
                        if ($dummy->last_name != '') {
                            
                            $last_name = $dummy->last_name; 
                            $regex = '/^([a-zA-Z ]+)$/'; 
                            if (preg_match($regex, $last_name)) {
                            $last = $last_name ;
                            } else { 
                            continue;
                            }           
                        }

                        if ($dummy->father_name !='') {   

                            $father_name = $dummy->father_name; 
                            $regex = '/^([a-zA-Z ]+)$/'; 
                            if (preg_match($regex, $father_name)) {
                                $father = $father_name ;
                            } else { 
                                continue;
                            } 
                        }
                        else{
                        continue;
                        }

                    //check Aadhar number
                    $aadhar='';
                    if ($dummy->aadhar_number != '') {
                        
                        $aadhar_number = $dummy->aadhar_number; 
                        $regex = '/^[1-9]{1}[0-9]{11}$/'; 
                        if (preg_match($regex, $aadhar_number)) {
                            $aadhar = $aadhar_number ;
                        } else { 
                            continue;
                        }  
                    }   

                    //Check DOB validation
                    $birth_date =NULL;
                    if ($dummy->dob !=NULL) {
                            $dob = $dummy->dob; 
                        
                            $regex = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/'; 
                            if (preg_match($regex,$dob)) {
                                // dd($dob);
                                $birth_date = $dob;
                            } else { 
                                continue;
                            } 
                        }
                        else{

                        continue;
                        }
                        // Check Gender
                        $genders ="N/A";
                        if ($dummy->gender != '') {
                        
                            if ($dummy->gender == 'male' || $dummy->gender == 'female' || $dummy->gender == 'others' ||  $dummy->gender == 'other' || $dummy->gender == 'Male' || $dummy->gender == 'Female' || $dummy->gender == 'Others' ||  $dummy->gender == 'Other'  ) {
                            $genders =  $dummy->gender;
                            } 
                            else {
                            continue;
                            }
                        }

                        // Check Mobile nummber 
                        $mob =null;
                        if ($dummy->phone != '') {
                            $phone = $dummy->phone; 
                            $regex = '/^[1-9]{1}[0-9]{9}$/'; 
                            if (preg_match($regex, $phone)) {
                                $mob = $phone ;
                            } else { 
                            continue;
                            }  
                        }

                        //check condition for Email
                        $email_id =null;
                        $check_email='';
                        if ($dummy->email !='') {
                            $email = strtolower($dummy->email); 
                            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
                            // $user_email = DB::table('users')->select('email')->where('email',$email)->first();
                            // if($user_email){
                            //     $check_email = $user_email->email;
                            // }
                            // if (preg_match($regex, $email) && ($check_email!=$email)) {
                            if (preg_match($regex, $email)) {
                                $email_id = $email ;
                            } else { 
                                continue;
                            } 
                        }
                        $no_of_check= $dummy->no_of_verification!=NULL?$dummy->no_of_verification:1;
                        if ($no_of_check<=5) {
                        $check=$no_of_check;
                        }else { 
                            continue;
                        } 
                        // $is_send_jaf_link = 0;
                        // if($request->input('is_send_jaf_link')){
                            //   $is_send_jaf_link = '1';
                            // }
                            //  $pre_user =  DB::table('users')->where(['first_name'=>$first,'father_name'=>$father,'dob'=>$birth_date])->first();
                            //  if ($pre_user=='') {
                                
                            //  }

                        $customer_id = $dummy->business_id;
                        $business_id = $dummy->parent_id;
                        // dd($business_id);
                    //create user 
                    $name = NULL;
                    $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $first.' '.$middle.' '.$last));
                    $data = 
                    [
                        'business_id'   =>$customer_id,
                        'user_type'     =>'candidate',
                        'client_emp_code'=>$dummy->client_emp_code,
                        'entity_code'   =>$dummy->entity_code,
                        'parent_id'     =>$business_id,
                        'name'          =>$name,
                        'first_name'    =>$first,
                        'middle_name'   =>$middle,
                        'last_name'     =>$last,
                        'father_name'   =>$father,
                        'aadhar_number' =>$aadhar,
                        'dob'           => $birth_date,
                        'gender'        =>$genders,
                        'email'         =>$email_id,
                        'status'         =>1,
                        //'password'       => Hash::make($request->input('password')),
                        'phone_code'   => $mob!=null?'91':'-',
                        'phone'         =>$mob,
                        'created_by'    =>Auth::user()->id,
                        'created_at'    =>date('Y-m-d H:i:s') 
                    ];
                    
                    $user_id = DB::table('candidate_reinitiates')->insertGetId($data);
                        //   dd($user_id);
                    $display_id = "";
                        //check customer config
                    $candidate_config = DB::table('candidate_config')
                    ->where(['client_id'=>$customer_id,'business_id'=>$business_id])
                    ->first();
                    //check client 
                    $client_config = DB::table('user_businesses')
                    ->where(['business_id'=>$customer_id])
                    ->first();

                    $latest_user = DB::table('candidate_reinitiates')
                    ->select('display_id')
                    ->where(['user_type'=>'candidate','id'=>$user_id])
                    ->orderBy('id','desc')
                    ->first();
                    // dd($latest_user);
                    $starting_number = $user_id;

                    if($candidate_config !=null){
                        if($latest_user != null){
                        if($latest_user->display_id !=null){
                            $id_arr = explode('-',$latest_user->display_id);
                            $starting_number = $id_arr[2]+1;  
                        }
                        }
                        $starting_number = str_pad($starting_number, 10, "0", STR_PAD_LEFT);
                        
                        $display_id = $candidate_config->customer_prefix.'-'.$candidate_config->client_prefix.'-'.$starting_number;
                    }else{
                        $customer_company = DB::table('user_businesses')
                        ->select('company_name')
                        ->where(['business_id'=>$business_id])
                        ->first();
                        
                        $client_company = DB::table('user_businesses')
                        ->select('company_name')
                        ->where(['business_id'=>$customer_id])
                        ->first();
                        
                        $u_id = str_pad($user_id, 10, "0", STR_PAD_LEFT);
                        $display_id = trim(strtoupper(str_replace(array(' ','-'),'',substr($customer_company->company_name,0,4)))).'-'.trim(strtoupper(substr($client_company->company_name,0,4))).'-'.$u_id;

                        //   $display_id = substr($customer_company->company_name,0,4).'-'.substr($client_company->company_name,0,4).'-'.$u_id;
                    }
                    //
                    DB::table('candidate_reinitiates')->where(['id'=>$user_id])->update(['display_id'=>$display_id]);
                    
                    //
                    $job_data = 
                    [
                        'business_id'  => $customer_id,
                        'parent_id'    => $business_id,
                        'title'        => NULL,
                        'sla_id'       => $dummy->sla_id,
                        'total_candidates'=>1,
                        // 'send_jaf_link_required'=>$is_send_jaf_link,
                        'status'       =>0,
                        'created_by'   =>Auth::user()->id,
                        'created_at'   => date('Y-m-d H:i:s')
                    ];
                    
                    $job_id = DB::table('jobs')->insertGetId($job_data);

                    // job item items      
                    $data = [
                        'business_id' =>$customer_id, 
                    'job_id'       =>$job_id, 
                    'candidate_id' =>$user_id,
                    'sla_id'       =>$dummy->sla_id,
                    'days_type'    => "working",
                    'price_type'    => "check",
                    'tat_type'     => "check",
                    'sla_title'   =>  "Variable SLA",
                    'filled_by_type'=>"customer",
                    'incentive'     => $customer_sla->incentive,
                    'penalty'     => $customer_sla->penalty,
                    'tat'     => $customer_sla->tat,
                    'client_tat'     => $customer_sla->client_tat,
                    'jaf_status'   =>'filled',
                    'filled_by'   =>Auth::user()->id,
                    'created_by'   =>Auth::user()->id,
                    'filled_at'   =>date('Y-m-d H:i:s'),
                    'created_at'   =>date('Y-m-d H:i:s')
                    ];
                    $job_item_id = DB::table('job_items')->insertGetId($data);  

                        //   
                    $no_of_verificaton=$check;
                        //   dd($no_of_verificaton);
                        $j=1;
                        if($no_of_verificaton!=''){
                            for($i=1;$i<=$no_of_verificaton;$i++){
                                // print_r($services[$i]);
                                // print_r($service_unit[$i]);incentives
                                //  for($j=$i;$j<=sizeof($service_unit);$j++){
                                // if ($services[$i]== $service_unit[$i]) {
                                // $number_of_verifications=1;
                                $no_of_tat=1;
                                $incentive_tat=1;
                                $penalty_tat=1;
                                $price=0;
                                $number_of_verifications=$i;
                                $no_of_tat=$customer_sla->tat;
                                $incentive_tat=$customer_sla->incentive;
                                $penalty_tat=$customer_sla->penalty;
                                $price=$dummy->price;
                                // dd($number_of_verifications);
                                $service_d=DB::table('services')->where('id',15)->first();
                                // dd($service_d);
                                $data = ['business_id'=>  $customer_id, 
                                        'job_id'      => $job_id, 
                                        'job_item_id' => $job_item_id,
                                        'candidate_id' =>$user_id,
                                        'sla_id'      => $dummy->sla_id,
                                        'service_id'  => $dummy->service_id,
                                        'jaf_send_to' => 'customer',
                                        'jaf_filled_by' => Auth::user()->id,
                                        'number_of_verifications'=>$service_d->verification_type=='Manual' || $service_d->verification_type=='manual'?$number_of_verifications:1,
                                        'tat' => $no_of_tat,
                                        'incentive_tat' => $incentive_tat,
                                        'penalty_tat' => $penalty_tat,
                                        'price'   => $price,
                                        'sla_item_id' => $dummy->sla_id,
                                        'created_at'  => date('Y-m-d H:i:s')
                                    ]; 
                                $jsi =  DB::table('job_sla_items')->insertGetId($data);  
                                // }
                                //service input data
                                $input_items = DB::table('service_form_inputs as sfi')
                                ->select('sfi.*')            
                                ->where(['sfi.service_id'=>$dummy->service_id,'status'=>1])
                                ->get();
                                // $j=1;
                                // $address='';
                                // $address_type='';
                                
                                if($j==$i){
                                    
                                    $ss = 'address_'.$j;
                                    $address = $dummy->$ss;
                                    $at='address_type_'.$j;
                                    $address_type =$dummy->$at;
                                    // dd($address);
                                }
                                $j++;
                                $input_data = [];
                                // $i=0;
                                foreach($input_items as $input){
                                    switch($input->label_name){
                                        case 'First name':
                                            $input_data[] = [

                                                $input->label_name=>$dummy->first_name,
                                                'is_report_output'=>$input->is_report_output 
                                                ];
                                            break;
                                        case 'Last Name':
                                            $input_data[] = [

                                                $input->label_name=>$dummy->last_name,
                                                'is_report_output'=>$input->is_report_output 
                                                ];
                                            break;
                                        case 'Father Name':
                                            $input_data[] = [

                                                $input->label_name=>$dummy->father_name,
                                                'is_report_output'=>$input->is_report_output 
                                                ];
                                            break;
                                        case 'Date of Birth':
                                            $input_data[] = [

                                                $input->label_name=>date('d-m-Y', strtotime($birth_date)),
                                                'is_report_output'=>$input->is_report_output 
                                                ];
                                            break;
                                        case 'Address':
                                            $input_data[] = [

                                                $input->label_name=>$address,
                                                'is_report_output'=>$input->is_report_output 
                                                ];
                                            break;
                                        case 'Address Type':
                                            $input_data[] = [

                                                $input->label_name=>$address_type,
                                                'is_report_output'=>$input->is_report_output 
                                                ];
                                            break;
                                        default:
                                            $input_data[] = [
                                                'label_name'=>'',
                                                'is_report_output'=>0 
                                            ];
                                    }
                                
                                }
                                $jaf_data = json_encode($input_data);
                            
                                $jaf_form_data = [
                                    'business_id'=>  $customer_id, 
                                    'job_id'      => $job_id, 
                                    'job_item_id' => $job_item_id,
                                    'candidate_id' =>$user_id,
                                    'sla_id'      => $dummy->sla_id,
                                    'service_id'  => $dummy->service_id,
                                    'check_item_number'=>$i,
                                    'form_data'       => $jaf_data,
                                    
                                    // 'form_data_all'   => json_encode($request->all()),
                                    // 'is_insufficiency'=>$is_insufficiency,
                                    // 'insufficiency_notes'=>$is_insufficiency==1?$insufficiency_notes:NULL,
                                    'address_type'  =>$address_type,
                                    'is_filled' => '1',
                                    'created_by'   => Auth::user()->id,
                                    'created_at'   => date('Y-m-d H:i:s')];
                                
                                $jfd =JafFormData::create($jaf_form_data);
                                // dd($jaf_data);
                                //check report items created or not
                                
                                //Get data of user of customer with 
                                $data = [
                                    'name'     => $dummy->first_name.' '.$dummy->last_name,
                                    'parent_id' => $business_id,
                                    'business_id'   => $customer_id, 
                                    'description'   => 'Task for Verification',
                                    'job_id'        => NULL,
                                    'priority'      => 'normal',
                                    'candidate_id'  => $user_id,
                                    'service_id'    => $dummy->service_id, 
                                    'number_of_verifications' => $i,
                                    'assigned_to'   => NULL,
                                    // 'assigned_by'   => Auth::user()->id,
                                    // 'assigned_at'   => date('Y-m-d H:i:s'),
                                    // 'start_date'    => date('Y-m-d'),
                                    'created_by'    => Auth::user()->id,
                                    'created_at'    => date('Y-m-d H:i:s'),
                                    'updated_at'  => date('Y-m-d H:i:s'),
                                    'is_completed'  => 0,
                                    // 'started_at'    => date('Y-m-d H:i:s')
                                ];
                                // // dd($data);
                                $task_id =  DB::table('tasks')->insertGetId($data); 

                                $taskdata = [

                                    'parent_id'=> $business_id,
                                    'business_id'   => $customer_id,
                                    'candidate_id'  =>$user_id,
                                    'job_sla_item_id'  => $jsi,
                                    'task_id'       => $task_id,
                                    //'user_id'    =>  $task_user->id,
                                    'service_id'    =>$dummy->service_id,
                                    'number_of_verifications' => $i,
                                    'created_at'    => date('Y-m-d H:i:s'),
                                    'updated_at'  => date('Y-m-d H:i:s') 
                                ];
                                
                                DB::table('task_assignments')->insertGetId($taskdata);  
                            }
                        }
                        
                        $report_count = DB::table('reports')->where(['candidate_id'=>$user_id])->count(); 
                        // 
                            if($report_count == 0){
                                
                                $job = DB::table('job_items')->where(['candidate_id'=>$user_id])->first(); 
                                
                                $data = 
                                    [
                                    'parent_id'     =>$business_id,
                                    'business_id'   =>$customer_id,
                                    'candidate_id'  =>$user_id,
                                    'sla_id'        =>$dummy->sla_id,       
                                    'created_at'    =>date('Y-m-d H:i:s')
                                    ];
                                    
                                $report_id = DB::table('reports')->insertGetId($data);
                                
                                // add service items
                                $jaf_items_datas = DB::table('jaf_form_data')->where(['candidate_id'=>$user_id])->get(); 
                                // dd($jaf_items_datas);
                                foreach($jaf_items_datas as $item){

                                    $reference_type = NULL;
                                    
                                    // $l=0;
                                
                                    if ($item->verification_status == 'success') {
                                        $data = 
                                        [
                                        'report_id'     =>$report_id,
                                        'service_id'    =>$item->service_id,
                                        'service_item_number'=>$item->check_item_number,
                                        'candidate_id'  =>$user_id,
                                        'jaf_data'      =>$item->form_data,
                                        'jaf_id'        =>$item->id,
                                        'reference_type' =>  $reference_type,
                                        'created_at'    =>date('Y-m-d H:i:s')
                                        ];
                                    } 
                                    else {
                                        $data = 
                                        [
                                        'report_id'     =>$report_id,
                                        'service_id'    =>$item->service_id,
                                        'service_item_number'=>$item->check_item_number,
                                        'candidate_id'  =>$user_id,      
                                        'jaf_data'      =>$item->form_data,
                                        'jaf_id'        =>$item->id,
                                        'is_report_output' => '0',
                                        'reference_type' =>  $reference_type,
                                        'created_at'    =>date('Y-m-d H:i:s')
                                        ]; 
                                    }
                                    
                                    $report_item_id = DB::table('report_items')->insertGetId($data);
                                }
                            }
                        // die;
                    
                    $checks= 0;
                        // service  items uses in  task table
                        // if ($dummy->jaf_filling_access == 'customer' || $dummy->jaf_filling_access == 'Customer') {
                        

                        //     if (Auth::user()->user_type == 'customer') {
                        //       # code...
                        //         $admin_email = Auth::user()->email;
                        //         $admin_name = Auth::user()->first_name;
                        //         //send email to customer
                        //         $email = $admin_email;
                        //         $name  = $admin_name;
                        //         $candidate_name = $first;
                        //         $msg = "New JAF Filling Task Created with candidate name";
                        //          $sender = DB::table('users')->where(['id'=>$business_id])->first();
                        //         $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);

                        //         Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                        //               $message->to($email, $name)->subject
                        //                 ('myBCD System - Notification for JAF Filling Task');
                        //               $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        //         });
                        //     }
                        //     else
                        //     {

                        //       $login_user = Auth::user()->business_id;
                        //       $user= User::where('id',$login_user)->first();
                        //       $admin_email = $user->email;
                        //       $admin_name = $user->first_name;
                        //       //send email to customer
                        //       $email = $admin_email;
                        //       $name  = $admin_name;
                        //       $candidate_name = $first;
                        //       $msg = "New JAF Filling Task Created with candidate name";
                        //       $sender = DB::table('users')->where(['id'=>$business_id])->first();
                        //       $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg);

                        //       Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                        //             $message->to($email, $name)->subject
                        //               ('myBCD System - Notification for JAF Filling Task');
                        //             $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        //       });
                        //     }

                        //   $kams  = KeyAccountManager::where('business_id',$customer_id)->get();

                        //   if (count($kams)>0) {
                        //     foreach ($kams as $kam) {

                        //       $user= User::where('id',$kam->user_id)->first();
                            
                        //       $email = $user->email;
                        //       $name  = $user->name;
                        //       $candidate_name = $first;
                        //       $msg = "New JAF Filling Task Created with candidate name";
                        //      $sender = DB::table('users')->where(['id'=>$business_id])->first();
                        //       $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);

                        //       Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                        //             $message->to($email, $name)->subject
                        //               ('myBCD System - Notification for JAF Filling Task');
                        //             $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        //       });

                        //     }
                            
                        //   }

                        // }
                        //
                        $name = NULL;
                        $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $first.' '.$middle.' '.$last));
                        $data = 
                        [     'business_id'   =>$customer_id,
                            'candidate_id'  =>$user_id,
                            'job_id'        =>$job_id,
                            'name'          =>$name,
                            'first_name'    =>$first,
                            'middle_name'   =>$middle,
                            'last_name'     =>$last,
                            'email'         =>$email_id,
                            'phone'         =>$mob,
                            'created_by'    =>Auth::user()->id,
                            'created_at'    =>date('Y-m-d H:i:s')
                        ];
                    
                        DB::table('candidates')->insertGetId($data);

                        // // Mail Send to COC
                        // $user= User::where('id',$customer_id)->first();

                        // $email = $user->email;
                        // $name  = $user->name;
                        // $candidate_name = $first;
                        // $msg = "New Candidate Has Been Created with candidate name";
                        // $sender = DB::table('users')->where(['id'=>$business_id])->first();
                        // $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);

                        // Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                        //       $message->to($email, $name)->subject
                        //         ('myBCD System - Notification for New Candidate Created');
                        //       $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        // });

                        // if ($jaf_send_to == 'candidate' || $jaf_send_to == 'Candidate') {
                        
                        //       //send email to candidate
                        //       $email = $email_id;
                        //       $name  = $first;
                        //       $data  = array('name'=>$name,'email'=>$email,'case_id'=>base64_encode($job_item_id),'c_id'=>base64_encode($user_id));

                        //         Mail::send(['html'=>'mails.jaf-info'], $data, function($message) use($email,$name) {
                        //             $message->to($email, $name)->subject
                        //                 ('BCD System - Your account credential');
                        //             $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        //         });
                        // }
                    }
                    // dd('value');
                }
            } else {
                $excel_dummy = ImportBulkEducation::where('unique_id',$unique)->get();
                $data ='';
                $i=0;
                if (count($excel_dummy)>0) {
                    foreach ($excel_dummy as $dummy) {
                        $client_display_id ='';
                        $client_display_id = $dummy->client_display_id;
                        if($dummy->business_id!=null)
                        {
                            $cl_display_id = $client_display_id;
                            // dd($cl_display_id);

                        } else{
                            
                            continue;
                        }

                        //check condition for first name
                        if ($dummy->first_name !='') {    
                            $first_name = $dummy->first_name; 
                            $regex = '/^([a-zA-Z ]+)$/'; 
                            if (preg_match($regex, $first_name)) {
                            $first = $first_name ;
                            } else { 
                            continue;
                            } 
                        }
                        else{

                        continue;
                        }
                        $middle = "";
                        //check condition for middle name
                        if ($dummy->middle_name != '') {
                            
                            $middle_name = $dummy->middle_name; 
                            $regex = '/^([a-zA-Z ]+)$/'; 
                            if (preg_match($regex, $middle_name)) {
                            $middle = $middle_name ;
                            } else { 
                            continue;
                            }           
                        }

                        //   // Last Name Check
                        $last='';
                        if ($dummy->last_name != '') {
                            
                            $last_name = $dummy->last_name; 
                            $regex = '/^([a-zA-Z ]+)$/'; 
                            if (preg_match($regex, $last_name)) {
                            $last = $last_name ;
                            } else { 
                            continue;
                            }           
                        }

                        if ($dummy->father_name !='') {   

                            $father_name = $dummy->father_name; 
                            $regex = '/^([a-zA-Z ]+)$/'; 
                            if (preg_match($regex, $father_name)) {
                                $father = $father_name ;
                            } else { 
                                continue;
                            } 
                        }
                        else{
                        continue;
                        }

                    //check Aadhar number
                    $aadhar='';
                    if ($dummy->aadhar_number != '') {
                        
                        $aadhar_number = $dummy->aadhar_number; 
                        $regex = '/^[1-9]{1}[0-9]{11}$/'; 
                        if (preg_match($regex, $aadhar_number)) {
                            $aadhar = $aadhar_number ;
                        } else { 
                            continue;
                        }  
                    }   

                    //Check DOB validation
                    $birth_date =NULL;
                    if ($dummy->dob !=NULL) {
                            $dob = $dummy->dob; 
                        
                            $regex = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/'; 
                            if (preg_match($regex,$dob)) {
                                // dd($dob);
                                $birth_date = $dob;
                            } else { 
                                continue;
                            } 
                        }
                        else{

                        continue;
                        }
                        // Check Gender
                        $genders ="N/A";
                        if ($dummy->gender != '') {
                        
                            if ($dummy->gender == 'male' || $dummy->gender == 'female' || $dummy->gender == 'others' ||  $dummy->gender == 'other' || $dummy->gender == 'Male' || $dummy->gender == 'Female' || $dummy->gender == 'Others' ||  $dummy->gender == 'Other'  ) {
                            $genders =  $dummy->gender;
                            } 
                            else {
                            continue;
                            }
                        }

                        // Check Mobile nummber 
                        $mob =null;
                        if ($dummy->phone != '') {
                            $phone = $dummy->phone; 
                            $regex = '/^[1-9]{1}[0-9]{9}$/'; 
                            if (preg_match($regex, $phone)) {
                                $mob = $phone ;
                            } else { 
                            continue;
                            }  
                        }

                        //check condition for Email
                        $email_id =null;
                        $check_email='';
                        if ($dummy->email !='') {
                            $email = strtolower($dummy->email); 
                            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
                            // $user_email = DB::table('users')->select('email')->where('email',$email)->first();
                            // if($user_email){
                            //     $check_email = $user_email->email;
                            // }
                            //if (preg_match($regex, $email) && ($check_email!=$email)) {
                            if (preg_match($regex, $email)) {
                                $email_id = $email ;
                            } else { 
                                continue;
                            } 
                        }
                        $no_of_check= $dummy->no_of_checks!=NULL?$dummy->no_of_checks:1;
                        if ($no_of_check<=5) {
                        $check=$no_of_check;
                        }else { 
                            continue;
                        } 
                        // $is_send_jaf_link = 0;
                        // if($request->input('is_send_jaf_link')){
                            //   $is_send_jaf_link = '1';
                            // }
                            //  $pre_user =  DB::table('users')->where(['first_name'=>$first,'father_name'=>$father,'dob'=>$birth_date])->first();
                            //  if ($pre_user=='') {
                                
                            //  }

                        $customer_id = $dummy->business_id;
                        $business_id = $dummy->parent_id;
                        // dd($business_id);
                    //create user 
                    $name = NULL;
                    $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $first.' '.$middle.' '.$last));
                    $data = 
                    [
                        'business_id'   =>$customer_id,
                        'user_type'     =>'candidate',
                        'client_emp_code'=>$dummy->client_emp_code,
                        'entity_code'   =>$dummy->entity_code,
                        'parent_id'     =>$business_id,
                        'name'          =>$name,
                        'first_name'    =>$first,
                        'middle_name'   =>$middle,
                        'last_name'     =>$last,
                        'father_name'   =>$father,
                        'aadhar_number' =>$aadhar,
                        'dob'           => $birth_date,
                        'gender'        =>$genders,
                        'email'         =>$email_id,
                        'status'         =>1,
                        //'password'       => Hash::make($request->input('password')),
                        'phone_code'   => $mob!=null?'91':'-',
                        'phone'         =>$mob,
                        'created_by'    =>Auth::user()->id,
                        'created_at'    =>date('Y-m-d H:i:s') 
                    ];
                    
                    $user_id = DB::table('candidate_reinitiates')->insertGetId($data);
                        //   dd($user_id);
                    $display_id = "";
                        //check customer config
                    $candidate_config = DB::table('candidate_config')
                    ->where(['client_id'=>$customer_id,'business_id'=>$business_id])
                    ->first();
                    //check client 
                    $client_config = DB::table('user_businesses')
                    ->where(['business_id'=>$customer_id])
                    ->first();

                    $latest_user = DB::table('candidate_reinitiates')
                    ->select('display_id')
                    ->where(['user_type'=>'candidate','id'=>$user_id])
                    ->orderBy('id','desc')
                    ->first();
                    // dd($latest_user);
                    $starting_number = $user_id;

                    if($candidate_config !=null){
                        if($latest_user != null){
                        if($latest_user->display_id !=null){
                            $id_arr = explode('-',$latest_user->display_id);
                            $starting_number = $id_arr[2]+1;  
                        }
                        }
                        $starting_number = str_pad($starting_number, 10, "0", STR_PAD_LEFT);
                        
                        $display_id = $candidate_config->customer_prefix.'-'.$candidate_config->client_prefix.'-'.$starting_number;
                    }else{
                        $customer_company = DB::table('user_businesses')
                        ->select('company_name')
                        ->where(['business_id'=>$business_id])
                        ->first();
                        
                        $client_company = DB::table('user_businesses')
                        ->select('company_name')
                        ->where(['business_id'=>$customer_id])
                        ->first();
                        
                        $u_id = str_pad($user_id, 10, "0", STR_PAD_LEFT);
                        $display_id = trim(strtoupper(str_replace(array(' ','-'),'',substr($customer_company->company_name,0,4)))).'-'.trim(strtoupper(substr($client_company->company_name,0,4))).'-'.$u_id;

                        //   $display_id = substr($customer_company->company_name,0,4).'-'.substr($client_company->company_name,0,4).'-'.$u_id;
                    }
                    //
                    DB::table('candidate_reinitiates')->where(['id'=>$user_id])->update(['display_id'=>$display_id]);
                    
                    //
                    $job_data = 
                    [
                        'business_id'  => $customer_id,
                        'parent_id'    => $business_id,
                        'title'        => NULL,
                        'sla_id'       => $dummy->sla_id,
                        'total_candidates'=>1,
                        // 'send_jaf_link_required'=>$is_send_jaf_link,
                        'status'       =>0,
                        'created_by'   =>Auth::user()->id,
                        'created_at'   => date('Y-m-d H:i:s')
                    ];
                    
                    $job_id = DB::table('jobs')->insertGetId($job_data);

                    // job item items      
                    $data = [
                        'business_id' =>$customer_id, 
                    'job_id'       =>$job_id, 
                    'candidate_id' =>$user_id,
                    'sla_id'       =>$dummy->sla_id,
                    'days_type'    => "working",
                    'price_type'    => "check",
                    'tat_type'     => "check",
                    'sla_title'   =>  "Variable SLA",
                    'filled_by_type'=>"customer",
                    'incentive'     => $customer_sla->incentive,
                    'penalty'     => $customer_sla->penalty,
                    'tat'     => $customer_sla->tat,
                    'client_tat'     => $customer_sla->client_tat,
                    'jaf_status'   =>'filled',
                    'filled_by'   =>Auth::user()->id,
                    'created_by'   =>Auth::user()->id,
                    'filled_at'   =>date('Y-m-d H:i:s'),
                    'created_at'   =>date('Y-m-d H:i:s')
                    ];
                    $job_item_id = DB::table('job_items')->insertGetId($data);  

                        //   
                    $no_of_verificaton=$check;
                        //   dd($no_of_verificaton);
                        $j=1;
                        if($no_of_verificaton!=''){
                            for($i=1;$i<=$no_of_verificaton;$i++){
                                // print_r($services[$i]);
                                // print_r($service_unit[$i]);incentives
                                //  for($j=$i;$j<=sizeof($service_unit);$j++){
                                // if ($services[$i]== $service_unit[$i]) {
                                // $number_of_verifications=1;
                                $no_of_tat=1;
                                $incentive_tat=1;
                                $penalty_tat=1;
                                $price=0;
                                $number_of_verifications=$i;
                                $no_of_tat=$customer_sla->tat;
                                $incentive_tat=$customer_sla->incentive;
                                $penalty_tat=$customer_sla->penalty;
                                $price=$dummy->price;
                                // dd($number_of_verifications);
                                $service_d=DB::table('services')->where('id',11)->first();
                                // dd($service_d);
                                $data = ['business_id'=>  $customer_id, 
                                        'job_id'      => $job_id, 
                                        'job_item_id' => $job_item_id,
                                        'candidate_id' =>$user_id,
                                        'sla_id'      => $dummy->sla_id,
                                        'service_id'  => $dummy->service_id,
                                        'jaf_send_to' => 'customer',
                                        'jaf_filled_by' => Auth::user()->id,
                                        'number_of_verifications'=>$service_d->verification_type=='Manual' || $service_d->verification_type=='manual'?$number_of_verifications:1,
                                        'tat' => $no_of_tat,
                                        'incentive_tat' => $incentive_tat,
                                        'penalty_tat' => $penalty_tat,
                                        'price'   => $price,
                                        'sla_item_id' => $dummy->sla_id,
                                        'created_at'  => date('Y-m-d H:i:s')
                                    ]; 
                                $jsi =  DB::table('job_sla_items')->insertGetId($data);  
                                // }
                                //service input data
                                $input_items = DB::table('service_form_inputs as sfi')
                                ->select('sfi.*')            
                                ->where(['sfi.service_id'=>$dummy->service_id,'status'=>1])
                                ->get();
                                // $j=1;
                                // $address='';
                                // $address_type='';
                                
                                if($j==$i){
                                    
                                    $ubn = 'university_board_name_'.$j;
                                    $university = $dummy->$ubn;
                                    $d='degree_'.$j;
                                    $degree =$dummy->$d;
                                    $re = 'registration_enroll_'.$j;
                                    $registration = $dummy->$re;
                                    $yq='year_of_qualification_'.$j;
                                    $year_of_qualification =$dummy->$yq;
                                    // dd($address);
                                }
                                $j++;
                                $input_data = [];
                                // $i=0;
                                foreach($input_items as $input){
                                    switch($input->label_name){
                                        case 'First name':
                                            $input_data[] = [

                                                $input->label_name=>$dummy->first_name,
                                                'is_report_output'=>$input->is_report_output 
                                                ];
                                            break;
                                        case 'Last Name':
                                            $input_data[] = [

                                                $input->label_name=>$dummy->last_name,
                                                'is_report_output'=>$input->is_report_output 
                                                ];
                                            break;
                                        case 'Father Name':
                                            $input_data[] = [

                                                $input->label_name=>$dummy->father_name,
                                                'is_report_output'=>$input->is_report_output 
                                                ];
                                            break;
                                        case 'Date of Birth':
                                            $input_data[] = [

                                                $input->label_name=>date('d-m-Y', strtotime($birth_date)),
                                                'is_report_output'=>$input->is_report_output 
                                                ];
                                            break;
                                        case 'University Name / Board Name':
                                            $input_data[] = [

                                                $input->label_name=>$university,
                                                'is_report_output'=>$input->is_report_output 
                                                ];
                                            break;
                                        case 'Degree':
                                            $input_data[] = [

                                                $input->label_name=>$degree,
                                                'is_report_output'=>$input->is_report_output 
                                                ];
                                            break;
                                        case 'Registration / Roll / Enrollment Number':
                                            $input_data[] = [

                                                $input->label_name=>$registration,
                                                'is_report_output'=>$input->is_report_output 
                                                ];
                                            break;
                                        case 'Year Of Qualification':
                                                $input_data[] = [
    
                                                    $input->label_name=>$year_of_qualification,
                                                    'is_report_output'=>$input->is_report_output 
                                                    ];
                                                break;
                                        default:
                                            $input_data[] = [
                                                'label_name'=>'',
                                                'is_report_output'=>0 
                                            ];
                                    }
                                
                                }
                                $jaf_data = json_encode($input_data);
                            
                                $jaf_form_data = [
                                    'business_id'=>  $customer_id, 
                                    'job_id'      => $job_id, 
                                    'job_item_id' => $job_item_id,
                                    'candidate_id' =>$user_id,
                                    'sla_id'      => $dummy->sla_id,
                                    'service_id'  => $dummy->service_id,
                                    'check_item_number'=>$i,
                                    'form_data'       => $jaf_data,
                                    
                                    // 'form_data_all'   => json_encode($request->all()),
                                    // 'is_insufficiency'=>$is_insufficiency,
                                    // 'insufficiency_notes'=>$is_insufficiency==1?$insufficiency_notes:NULL,
                                    'address_type'  =>NULL,
                                    'is_filled' => '1',
                                    'created_by'   => Auth::user()->id,
                                    'created_at'   => date('Y-m-d H:i:s')];
                                
                                $jfd =JafFormData::create($jaf_form_data);
                                // dd($jaf_data);
                                //check report items created or not
                                
                                //Get data of user of customer with 
                                $data = [
                                    'name'     => $dummy->first_name.' '.$dummy->last_name,
                                    'parent_id' => $business_id,
                                    'business_id'   => $customer_id, 
                                    'description'   => 'Task for Verification',
                                    'job_id'        => NULL,
                                    'priority'      => 'normal',
                                    'candidate_id'  => $user_id,
                                    'service_id'    => $dummy->service_id, 
                                    'number_of_verifications' => $i,
                                    'assigned_to'   => NULL,
                                    // 'assigned_by'   => Auth::user()->id,
                                    // 'assigned_at'   => date('Y-m-d H:i:s'),
                                    // 'start_date'    => date('Y-m-d'),
                                    'created_by'    => Auth::user()->id,
                                    'created_at'    => date('Y-m-d H:i:s'),
                                    'updated_at'  => date('Y-m-d H:i:s'),
                                    'is_completed'  => 0,
                                    // 'started_at'    => date('Y-m-d H:i:s')
                                ];
                                // // dd($data);
                                $task_id =  DB::table('tasks')->insertGetId($data); 

                                $taskdata = [

                                    'parent_id'=> $business_id,
                                    'business_id'   => $customer_id,
                                    'candidate_id'  =>$user_id,
                                    'job_sla_item_id'  => $jsi,
                                    'task_id'       => $task_id,
                                    //'user_id'    =>  $task_user->id,
                                    'service_id'    =>$dummy->service_id,
                                    'number_of_verifications' => $i,
                                    'created_at'    => date('Y-m-d H:i:s'),
                                    'updated_at'  => date('Y-m-d H:i:s') 
                                ];
                                
                                DB::table('task_assignments')->insertGetId($taskdata);  
                            }
                        }
                        
                        $report_count = DB::table('reports')->where(['candidate_id'=>$user_id])->count(); 
                        // 
                            if($report_count == 0){
                                
                                $job = DB::table('job_items')->where(['candidate_id'=>$user_id])->first(); 
                                
                                $data = 
                                    [
                                    'parent_id'     =>$business_id,
                                    'business_id'   =>$customer_id,
                                    'candidate_id'  =>$user_id,
                                    'sla_id'        =>$dummy->sla_id,       
                                    'created_at'    =>date('Y-m-d H:i:s')
                                    ];
                                    
                                $report_id = DB::table('reports')->insertGetId($data);
                                
                                // add service items
                                $jaf_items_datas = DB::table('jaf_form_data')->where(['candidate_id'=>$user_id])->get(); 
                                // dd($jaf_items_datas);
                                foreach($jaf_items_datas as $item){

                                    $reference_type = NULL;
                                    
                                    // $l=0;
                                
                                    if ($item->verification_status == 'success') {
                                        $data = 
                                        [
                                        'report_id'     =>$report_id,
                                        'service_id'    =>$item->service_id,
                                        'service_item_number'=>$item->check_item_number,
                                        'candidate_id'  =>$user_id,
                                        'jaf_data'      =>$item->form_data,
                                        'jaf_id'        =>$item->id,
                                        'reference_type' =>  $reference_type,
                                        'created_at'    =>date('Y-m-d H:i:s')
                                        ];
                                    } 
                                    else {
                                        $data = 
                                        [
                                        'report_id'     =>$report_id,
                                        'service_id'    =>$item->service_id,
                                        'service_item_number'=>$item->check_item_number,
                                        'candidate_id'  =>$user_id,      
                                        'jaf_data'      =>$item->form_data,
                                        'jaf_id'        =>$item->id,
                                        'is_report_output' => '0',
                                        'reference_type' =>  $reference_type,
                                        'created_at'    =>date('Y-m-d H:i:s')
                                        ]; 
                                    }
                                    
                                    $report_item_id = DB::table('report_items')->insertGetId($data);
                                }
                            }
                        // die;
                    
                    $checks= 0;
                        // service  items uses in  task table
                        // if ($dummy->jaf_filling_access == 'customer' || $dummy->jaf_filling_access == 'Customer') {
                        

                        //     if (Auth::user()->user_type == 'customer') {
                        //       # code...
                        //         $admin_email = Auth::user()->email;
                        //         $admin_name = Auth::user()->first_name;
                        //         //send email to customer
                        //         $email = $admin_email;
                        //         $name  = $admin_name;
                        //         $candidate_name = $first;
                        //         $msg = "New JAF Filling Task Created with candidate name";
                        //          $sender = DB::table('users')->where(['id'=>$business_id])->first();
                        //         $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);

                        //         Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                        //               $message->to($email, $name)->subject
                        //                 ('myBCD System - Notification for JAF Filling Task');
                        //               $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        //         });
                        //     }
                        //     else
                        //     {

                        //       $login_user = Auth::user()->business_id;
                        //       $user= User::where('id',$login_user)->first();
                        //       $admin_email = $user->email;
                        //       $admin_name = $user->first_name;
                        //       //send email to customer
                        //       $email = $admin_email;
                        //       $name  = $admin_name;
                        //       $candidate_name = $first;
                        //       $msg = "New JAF Filling Task Created with candidate name";
                        //       $sender = DB::table('users')->where(['id'=>$business_id])->first();
                        //       $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg);

                        //       Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                        //             $message->to($email, $name)->subject
                        //               ('myBCD System - Notification for JAF Filling Task');
                        //             $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        //       });
                        //     }

                        //   $kams  = KeyAccountManager::where('business_id',$customer_id)->get();

                        //   if (count($kams)>0) {
                        //     foreach ($kams as $kam) {

                        //       $user= User::where('id',$kam->user_id)->first();
                            
                        //       $email = $user->email;
                        //       $name  = $user->name;
                        //       $candidate_name = $first;
                        //       $msg = "New JAF Filling Task Created with candidate name";
                        //      $sender = DB::table('users')->where(['id'=>$business_id])->first();
                        //       $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);

                        //       Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                        //             $message->to($email, $name)->subject
                        //               ('myBCD System - Notification for JAF Filling Task');
                        //             $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        //       });

                        //     }
                            
                        //   }

                        // }
                        //
                        $name = NULL;
                        $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $first.' '.$middle.' '.$last));
                        $data = 
                        [     'business_id'   =>$customer_id,
                            'candidate_id'  =>$user_id,
                            'job_id'        =>$job_id,
                            'name'          =>$name,
                            'first_name'    =>$first,
                            'middle_name'   =>$middle,
                            'last_name'     =>$last,
                            'email'         =>$email_id,
                            'phone'         =>$mob,
                            'created_by'    =>Auth::user()->id,
                            'created_at'    =>date('Y-m-d H:i:s')
                        ];
                    
                        DB::table('candidates')->insertGetId($data);

                        // // Mail Send to COC
                        // $user= User::where('id',$customer_id)->first();

                        // $email = $user->email;
                        // $name  = $user->name;
                        // $candidate_name = $first;
                        // $msg = "New Candidate Has Been Created with candidate name";
                        // $sender = DB::table('users')->where(['id'=>$business_id])->first();
                        // $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);

                        // Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                        //       $message->to($email, $name)->subject
                        //         ('myBCD System - Notification for New Candidate Created');
                        //       $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        // });

                        // if ($jaf_send_to == 'candidate' || $jaf_send_to == 'Candidate') {
                        
                        //       //send email to candidate
                        //       $email = $email_id;
                        //       $name  = $first;
                        //       $data  = array('name'=>$name,'email'=>$email,'case_id'=>base64_encode($job_item_id),'c_id'=>base64_encode($user_id));

                        //         Mail::send(['html'=>'mails.jaf-info'], $data, function($message) use($email,$name) {
                        //             $message->to($email, $name)->subject
                        //                 ('BCD System - Your account credential');
                        //             $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        //         });
                        // }
                    }
                    // dd('value');
                }
            }
            //   DB::commit();
          return response()->json([
            'fail'      =>false,
            'error' => '',
            
          ]);
          // return redirect('/candidates')
          //             ->with('success', 'All candidates have been successfully created.');
        // }
        // catch (\Exception $e) {
        //     DB::rollback();
        //     // something went wrong
        //     return $e;
        // } 



    }
    /**
     * Show the custom verifications.
     *
     * @return \Illuminate\Contracts\Support\Renderable
    */

    public function verifications()
    {
        $business_id = Auth::user()->business_id; 
        $services = DB::table('services')->where(['is_common'=>'1'])->orWhere('business_id',$business_id)->get();

        return view('admin.verifications.verifications',compact('services'));
    }

   
    //active and enactive 

    public function manualVerificationStatus(Request $request)
    {
        $service_id=base64_decode($request->id);
        // $service =  DB::table('services')->where('id',$service_id)->first();
        //    dd($service->id);
        $type = base64_decode($request->type);
        // dd($type);
    
        if(stripos($type,'disable')!==false)
        {
            DB::table('services')->where('id',$service_id)->update(['status'=>0]);
            
            return response()->json([
                'success'=>true,
                'type' => $type,
                'message'=>'Status change successfully.'
            ]);
        }
        elseif(stripos($type,'enable')!==false)
        {
            DB::table('services')->where('id',$service_id)->update(['status'=>1]);

            return response()->json([
                'success'=>true,
                'type' => $type,
                'message'=>'Status change successfully.'
            ]);
        }
        
    }


     //Import Bulk Verification Excel
     public function importBulkVerifications(Request $request)
     {
            $business_id=Auth::user()->business_id;
            $parent_id =Auth::user()->parent_id;
            $user_id = Auth::user()->id;
            $service_id =$request->service_id;
            
            $unique = uniqid();
            $file = $request->file;
            
            if ($file=='undefined') {
                
                return response()->json([
                    'fail'      =>true,
                    'error' => 'empty_file'
                   
                ]);
            }
            $parsed_array = Excel::toArray([], $file);
            $heading_data = array_splice($parsed_array[0], 0);
            $parsed_array = Excel::toArray([], $file);
            $imported_data = array_splice($parsed_array[0], 1);

            $path = public_path().'/bulk_verification/reports/pdf/'.$user_id.'/';
            if(File::exists($path))
            {
                File::cleanDirectory($path);
            }

            //dd($heading_data);
            // dd($imported_data);
            $service = DB::table('services')->where('id',$service_id)->first();
            
            if (stripos($heading_data[0][1],"Aadhaar Number")!==false && $service_id=='2') {
                $input_data = [];
                // dd($imported_data);
                foreach ($imported_data as $value)
                {
                    $input_data['aadhaar'] = ['aadhaar_number'=>$value[1]];
                    $aadhar_data = json_encode($input_data);
                    $user_data = 
                    [
                        'business_id' => $business_id,
                        'parent_id'   =>$parent_id,
                        'unique_id' => $unique,
                        'service_id'=> $service_id,
                        'service_data' =>$aadhar_data,
                    ];
                    DB::table('instant_verification_demos')->insertGetId($user_data);

                }
            }

            elseif (stripos($heading_data[0][1],"PAN Number")!==false && $service_id=='3') {
                $input_data = [];
                // dd($imported_data);
                foreach ($imported_data as $value)
                {
                    $input_data['pan'] = ['pan_number'=>$value[1]];
                    $pan_data = json_encode($input_data);
                    $user_data = 
                    [
                        'business_id' => $business_id,
                        'parent_id'   =>$parent_id,
                        'unique_id' => $unique,
                        'service_id'=> $service_id,
                        'service_data' =>$pan_data,
                    ];
                    DB::table('instant_verification_demos')->insertGetId($user_data);

                }
            }
            elseif (stripos($heading_data[0][1],"Voter ID")!==false && $service_id=='4') {
                $input_data = [];
                // dd($imported_data);
                foreach ($imported_data as $value)
                {
                    $input_data['voter_id'] = ['voter_id'=>$value[1]];
                    $voter_data = json_encode($input_data);
                    $user_data = 
                    [
                        'business_id' => $business_id,
                        'parent_id'   =>$parent_id,
                        'unique_id' => $unique,
                        'service_id'=> $service_id,
                        'service_data' =>$voter_data,
                    ];
                    DB::table('instant_verification_demos')->insertGetId($user_data);

                }
            }
            
            elseif (stripos($heading_data[0][1],"RC Number")!==false && $service_id=='7') {
                $input_data = [];
                // dd($imported_data);
                foreach ($imported_data as $value)
                {
                    $input_data['rc'] = ['rc_number'=>$value[1]];
                    $rc_data = json_encode($input_data);
                    $user_data = 
                    [
                        'business_id' => $business_id,
                        'parent_id'   =>$parent_id,
                        'unique_id' => $unique,
                        'service_id'=> $service_id,
                        'service_data' =>$rc_data,
                    ];
                    DB::table('instant_verification_demos')->insertGetId($user_data);

                }
            }
            elseif (stripos($heading_data[0][1],"File Number")!==false && $service_id=='8') {
                $input_data = [];
                // dd($imported_data);
                foreach ($imported_data as $value)
                {
                    $input_data['passport'] = ['file_number'=>$value[1],'dob'=>$value[2]];
                    $passport_data = json_encode($input_data);
                    $user_data = 
                    [
                        'business_id' => $business_id,
                        'parent_id'   =>$parent_id,
                        'unique_id' => $unique,
                        'service_id'=> $service_id,
                        'service_data' =>$passport_data,
                    ];
                    DB::table('instant_verification_demos')->insertGetId($user_data);

                }
            }
            elseif (stripos($heading_data[0][1],"DL number")!==false && $service_id=='9') {
                $input_data = [];
                //dd($imported_data);
                foreach ($imported_data as $value)
                {
                    $input_data['dl'] = ['dl_number'=>$value[1],'dob'=>$value[2]];
                    $dl_data = json_encode($input_data);
                    $user_data = 
                    [
                        'business_id' => $business_id,
                        'parent_id'   =>$parent_id,
                        'unique_id' => $unique,
                        'service_id'=> $service_id,
                        'service_data' =>$dl_data,
                    ];
                    DB::table('instant_verification_demos')->insertGetId($user_data);

                }
            }
            elseif (stripos($heading_data[0][1],"Account Number ")!==false && $service_id=='12') {
                $input_data = [];
                // dd($imported_data);
                foreach ($imported_data as $value)
                {
                    $input_data['account'] = ['account_number'=>$value[1],'IFSC_code'=>$value[2]];
                    $account_data = json_encode($input_data);
                    $user_data = 
                    [
                        'business_id' => $business_id,
                        'parent_id'   =>$parent_id,
                        'unique_id' => $unique,
                        'service_id'=> $service_id,
                        'service_data' =>$account_data,
                    ];
                    DB::table('instant_verification_demos')->insertGetId($user_data);

                }
            }
            elseif (stripos($heading_data[0][1],"GSTIN Number")!==false && $service_id=='14') {
                $input_data = [];
                // dd($imported_data);
                foreach ($imported_data as $value)
                {
                    $input_data['gst'] = ['gst_number'=>$value[1],'Filling_Record_Needed?'=>$value[2]];
                    $gst_data = json_encode($input_data);
                    $user_data = 
                    [
                        'business_id' => $business_id,
                        'parent_id'   =>$parent_id,
                        'unique_id' => $unique,
                        'service_id'=> $service_id,
                        'service_data' =>$gst_data,
                    ];
                    DB::table('instant_verification_demos')->insertGetId($user_data);

                }
            }
            elseif (stripos($heading_data[0][1],"UPI Number")!==false && $service->type_name=='upi') {
                $input_data = [];
                // dd($imported_data);
                foreach ($imported_data as $value)
                {
                    $input_data['upi'] = ['upi_number'=>$value[1]];
                    $upi_data = json_encode($input_data);
                    $user_data = 
                    [
                        'business_id' => $business_id,
                        'parent_id'   =>$parent_id,
                        'unique_id' => $unique,
                        'service_id'=> $service_id,
                        'service_data' =>$upi_data,
                    ];
                    DB::table('instant_verification_demos')->insertGetId($user_data);

                }
            }
            elseif (stripos($heading_data[0][1],"CIN Number")!==false && $service->type_name=='cin') {
                $input_data = [];
                // dd($imported_data);
                foreach ($imported_data as $value)
                {
                    $input_data['cin'] = ['cin_number'=>$value[1]];
                    $cin_data = json_encode($input_data);
                    $user_data = 
                    [
                        'business_id' => $business_id,
                        'parent_id'   =>$parent_id,
                        'unique_id' => $unique,
                        'service_id'=> $service_id,
                        'service_data' =>$cin_data,
                    ];
                    DB::table('instant_verification_demos')->insertGetId($user_data);

                }
            }
            elseif(stripos($heading_data[0][1],"Name")!==false && $service->type_name=='court_check_v1')
            {
                $input_data = [];
                // dd($imported_data);
                foreach ($imported_data as $value)
                {
                    $input_data['court_check_v1'] = ['name'=>$value[1],'father_name'=>$value[2],'address'=>$value[3],'state'=>$value[4],'type'=>$value[5]];
                    $court_check_v1_data = json_encode($input_data);
                    $user_data = 
                    [
                        'business_id' => $business_id,
                        'parent_id'   =>$parent_id,
                        'unique_id' => $unique,
                        'service_id'=> $service_id,
                        'service_data' =>$court_check_v1_data,
                    ];
                    DB::table('instant_verification_demos')->insertGetId($user_data);

                }
            }
            else {
                
                return response()->json([
                    'fail' => true,
                    'error' => 'yes',
                    
                ]);
            }
         $excel_dummy= DB::table('instant_verification_demos')->where('unique_id',$unique)->get();
        if (count($excel_dummy)>0) {
          
            foreach ($excel_dummy as $dummy) {

                $service = $dummy->service_id;

                $service_data = DB::table('services')->where('id',$service)->first();
               
                if ($service=='2') {
                   
                    $input_item_data_array= json_decode($dummy->service_data,true);
                    foreach($input_item_data_array as $f_item){
                        
                        if( array_key_exists('aadhaar_number',$f_item) ){
                           
                               
                                $data       = array_keys($f_item); 
                                $data1      = array_values($f_item); 
                                $aadhaar_no =$data1[0];
                                $this->idCheckAadhar1($aadhaar_no,$dummy->id);
                        }
                    }       
                }
                elseif ($service=='3') {

                    $input_item_data_array= json_decode($dummy->service_data,true);
                    foreach($input_item_data_array as $f_item){
                        
                        if( array_key_exists('pan_number',$f_item) ){
                              
                            $data       = array_keys($f_item); 
                            $data1      = array_values($f_item); 
                            $pan_no =$data1[0];
                            $this->idCheckPan1($pan_no,$dummy->id);
                        }
                    }       
                }
                elseif ($service=='4') {

                    $input_item_data_array= json_decode($dummy->service_data,true);
                    foreach($input_item_data_array as $f_item){
                        
                      
                        if( array_key_exists('voter_id',$f_item) ){
                           
                            $data       = array_keys($f_item); 
                            $data1      = array_values($f_item); 
                            $voter_id =$data1[0];
                            $this->idCheckVoterID1($voter_id,$dummy->id);
                        }
                    }       
                }
                elseif ($service=='7') {

                    $input_item_data_array= json_decode($dummy->service_data,true);
                    foreach($input_item_data_array as $f_item){
                        
                      
                        if( array_key_exists('rc_number',$f_item) ){
                           
                            $data       = array_keys($f_item); 
                            $data1      = array_values($f_item); 
                            $rc_number =$data1[0];
                            $this->idCheckRC1($rc_number,$dummy->id);
                        }
                    }       
                }
                elseif ($service=='8') {

                    $input_item_data_array= json_decode($dummy->service_data,true);
                    foreach($input_item_data_array as $f_item){

                        if( array_key_exists('file_number',$f_item) &&  array_key_exists('dob',$f_item)){
                           
                            $data       = array_keys($f_item); 
                            $data1      = array_values($f_item); 
                            $file_number =$data1[0];
                            $dob1 = $data1[1];
                            $dob= date('d-m-Y',strtotime($dob1));
                            $this->idCheckPassport1($file_number,$dob,$dummy->id);
                        }
                    }       
                }
                elseif ($service=='9') {
                    $input_item_data_array= json_decode($dummy->service_data,true);
                    //dd($input_item_data_array);
                    foreach($input_item_data_array as $f_item){
                        
                        if( array_key_exists('dl_number',$f_item) && array_key_exists('dob',$f_item)){
                           
                            $data       = array_keys($f_item); 
                            $data1      = array_values($f_item); 
                            $dl_number =$data1[0];
                            $dob1 = $data1[1];
                            $dob= date('d-m-Y',strtotime($dob1));
                          
                            $this->idCheckDL1($dl_number,$dob,$dummy->id);
                        }
                    }       
                }
                elseif ($service=='12') {

                    $input_item_data_array= json_decode($dummy->service_data,true);
                    foreach($input_item_data_array as $f_item){
                        
                        if( array_key_exists('account_number',$f_item) &&  array_key_exists('IFSC_code',$f_item) ){
                           
                            $data       = array_keys($f_item); 
                            $data1      = array_values($f_item); 
                            $account_number =$data1[0];
                            $ifsc = $data1[1];
                            // dd($ifsc);
                            $this->idCheckBankAccount1($account_number,$ifsc,$dummy->id);
                        }
                    }       
                }
               
                elseif ($service=='14') {

                    $input_item_data_array= json_decode($dummy->service_data,true);
                    foreach($input_item_data_array as $f_item){
                        // dd($f_item);
                        if( array_key_exists('gst_number',$f_item) &&  array_key_exists('Filling_Record_Needed?',$f_item) ){
                        //    dd($f_item);
                            $data       = array_keys($f_item); 
                            $data1      = array_values($f_item); 
                            $gst_number =$data1[0];
                            $record_needed = $data1[1];
                            
                            $this->idCheckGSTIN1($gst_number,$record_needed,$dummy->id);
                        }
                    }       
                }

                elseif ($service_data->type_name=='upi') {
                   
                    $input_item_data_array= json_decode($dummy->service_data,true);
                    foreach($input_item_data_array as $f_item){
                        
                        if( array_key_exists('upi_number',$f_item) ){
                           
                               
                                $data       = array_keys($f_item); 
                                $data1      = array_values($f_item); 
                                $upi_no =$data1[0];
                                $this->idCheckUPI1($upi_no,$dummy->id);
                        }
                    }       
                }
                elseif ($service_data->type_name=='cin') {
                   
                    $input_item_data_array= json_decode($dummy->service_data,true);
                    foreach($input_item_data_array as $f_item){
                        
                        if( array_key_exists('cin_number',$f_item) ){
                           
                               
                                $data       = array_keys($f_item); 
                                $data1      = array_values($f_item); 
                                $cin_no =$data1[0];
                                $this->idCheckCIN1($cin_no,$dummy->id);
                        }
                    }       
                }
                elseif ($service_data->type_name=='court_check_v1') {
                   
                    $input_item_data_array= json_decode($dummy->service_data,true);
                    foreach($input_item_data_array as $f_item){
                        
                        if( array_key_exists('court_check_v1',$f_item) ){
                           
                               
                                $data       = array_keys($f_item); 
                                $data1      = array_values($f_item); 
                                $name =$data1[0];
                                $father_name =$data1[1];
                                $address =$data1[2];
                                $state =$data1[3];
                                $type =$data1[4];
                                $this->idCheckCourtV11($name,$father_name,$address,$state,$type,$dummy->id);
                        }
                    }       
                }
               

            }
            $excel= DB::table('instant_verification_demos')->where('unique_id',$unique)->get();
            // generating the service_wise zip
             
            $zipname="";
            // $service = $zip_data->service_id;

            $services=DB::table('services')->where('id',$service_id)->first();
             //dd($services);
           
            if($services->name=='Aadhar')
            {
                $zipname = 'aadhar-'.date('Ymdhis').'.zip';
                $path = public_path().'/admin/reports/zip/aadhar/';
                $url=url('/').'/admin/reports/zip/aadhar/';
            }
            else if($services->id==3)
            {
                $zipname = 'pan-'.date('Ymdhis').'.zip';
                $path = public_path().'/admin/reports/zip/pan/';
                $url=url('/').'/admin/reports/zip/pan/';
            }
            else if($services->name=='Voter ID')
            {
                $zipname = 'voter_id-'.date('Ymdhis').'.zip';
                $path = public_path().'/admin/reports/zip/voterid/';
                $url=url('/').'/admin/reports/zip/voterid/';
            }
            else if($services->name=='RC')
            {
                $zipname = 'rc-'.date('Ymdhis').'.zip';
                $path = public_path().'/admin/reports/zip/rc/';
                $url=url('/').'/admin/reports/zip/rc/';
            }
            else if($services->name=='Passport')
            {
                $zipname = 'passport-'.date('Ymdhis').'.zip';
                $path = public_path().'/admin/reports/zip/passport/';
                $url=url('/').'/admin/reports/zip/passport/';
            }
            else if($services->name=='Driving')
            {
                $zipname = 'driving-'.date('Ymdhis').'.zip';
                // dd($zipname);
                $path = public_path().'/admin/reports/zip/driving/';
                $url=url('/').'/admin/reports/zip/driving/';
            }
            else if($services->name=='Bank Verification')
            {
                $zipname = 'bank-'.date('Ymdhis').'.zip';
                $path = public_path().'/admin/reports/zip/bank/';
                $url=url('/').'/admin/reports/zip/bank/';
            }
            else if(stripos($services->name,'GSTIN')!==false)
            {
                $zipname = 'gstin-'.date('Ymdhis').'.zip';
                $path = public_path().'/admin/reports/zip/gstin/';
                $url=url('/').'/admin/reports/zip/gstin/';
            }
            else if(stripos($services->type_name,'upi')!==false)
            {
                $zipname = 'upi-'.date('Ymdhis').'.zip';
                $path = public_path().'/admin/reports/zip/upi/';
                $url=url('/').'/admin/reports/zip/upi/';
            }
            else if(stripos($services->type_name,'cin')!==false)
            {
                $zipname = 'cin-'.date('Ymdhis').'.zip';
                $path = public_path('/admin/reports/zip/cin/');
                $url=url('/').'/admin/reports/zip/cin/';
            }
            else if(stripos($services->type_name,'court_check_v1')!==false)
            {
                $zipname = 'court_check_v1-'.date('Ymdhis').'.zip';
                $path = public_path('/admin/reports/zip/court_check_v1/');
                $url=url('/').'/admin/reports/zip/court_check_v1/';
            }

            if(!File::exists($path))
            {
                File::makeDirectory($path,$mode = 0777,true,true);
            }

           

            $zip = new \ZipArchive();
            $zip->open($path.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            foreach($excel as $zip_data)
            {
               
                // foreach($excel_dummy as $dummy)
                // {
                    $path1 = public_path()."/bulk_verification/reports/pdf/".$user_id.'/'.$zip_data->file_name;
                    // dd($path1);
                    $zip->addFile($path1, '/reports/'.basename($path1));  
                // }

            }
            $zip->close();

            return response()->json([
                'fail' => false,
                'error' => '',
                'zip'=> $url.$zipname,
            ]);
        }
            
     }

     // save new service
    public function saveVerification(Request $request)
    {
        
        $business_id = Auth::user()->business_id; 
        $parent_id=Auth::user()->parent_id;
        if(stripos(Auth::user()->user_type,'user')!==false)
        {
            $user=DB::table('users')->where('id',$business_id)->first();
            $parent_id=$user->parent_id;
        }
        

         $rules = [
            'name'                => 'required|regex:/^[A-Za-z0-9]+([A-Za-z0-9]+\s)*[A-za-z0-9]+$/u|min:1',
            'is_multiple_type'    => 'required',
            'price'               => 'required|numeric|min:5',
        ];

        $custom=[
            'name.required' => 'The verification name field is required',
            'name.regex' => 'Verification Name With No Extra Space or Special Character Allowed',
            'is_multiple_type.required' => 'The multiple type field is required'
        ];

        $validator = Validator::make($request->all(), $rules, $custom);
        if ($validator->fails())
            return response()->json([
                'fail'      => true,
                'errors'    => $validator->errors(),
                'error_type'=>'validation'
            ]);

        DB::beginTransaction();
        try{

            //check 
            $check = DB::table('services')
                    ->select('*')        
                    ->where(['name'=>$request->input('name'),'business_id'=>$business_id])        
                    ->first();         

            if($check ===null)
            {
                $type_name = str_replace(array(' '),'_',strtolower($request->name));
                $storeData =[   
                    'business_id'         => $business_id,         
                    "name"                => $request->input('name'),
                    "is_multiple_type"    => $request->input('is_multiple_type'), 
                    "is_common"           =>       '0',    
                    'verification_type'     => 'Manual',
                    'type_name' =>     $type_name,       
                    "created_at"          => date('Y-m-d H:i:s')
                ];

                //insert data
                $insertedID = DB::table('services')->insertGetId( $storeData ); 

                DB::table('services')->where('id',$insertedID)->update([
                    'sort_number' => $insertedID
                ]);

                $data=[
                    'parent_id' => $parent_id,
                    'business_id' => $business_id,
                    'service_id' => $insertedID,
                    'price' => $request->price,
                    'created_by' =>Auth::user()->id,
                    'used_by' => 'customer',
                    'created_at' => date('Y-m-d H:i:s')
                ];

                DB::table('check_price_masters')->insert($data);

                DB::commit();
                return response()->json([
                    'fail' => false,
                    'error'=>'no'
                ]);

            }else{
            
                return response()->json([
                    'fail' => true,
                    'error' => 'yes',
                    'message'=>'Verification name is already created !'
                ]);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
        
    }

    public function editVerification(Request $request)
    {
        $service_id=$request->service_id;

        $business_id = Auth::user()->id;

        $price=0;

        $services=DB::table('services')->where(['id'=>$service_id])->first();

        $check_price_master=DB::table('check_price_masters')->where(['service_id'=>$service_id,'business_id'=>$business_id])->first();

        if($check_price_master!=NULL)
        {
            $price=$check_price_master->price;
        }


        return response()->json([                
            'result' => $services,
            'price' => $price
        ]);
    }

    public function updateVerification(Request $request)
    {
        $business_id = Auth::user()->business_id; 
        $parent_id=Auth::user()->parent_id;
        if(stripos(Auth::user()->user_type,'user')!==false)
        {
            $user=DB::table('users')->where('id',$business_id)->first();
            $parent_id=$user->parent_id;
        }

         $rules = [
            'name'                => 'required|regex:/^[A-Za-z0-9]+([A-Za-z0-9]+\s)*[A-za-z0-9]+$/u|min:1',                
            'is_multiple_type'    => 'required',
            'price'               => 'required|numeric|min:5'
        ];

        $custom=[
            'name.required' => 'The verification name field is required',
            'name.regex' => 'Verification Name With No Extra Space or Special Character Allowed',
            'is_multiple_type.required' => 'The multiple type field is required'
        ];

        $validator = Validator::make($request->all(), $rules,$custom);
        if ($validator->fails())
            return response()->json([
                'fail'      => true,
                'errors'    => $validator->errors(),
                'error_type'=>'validation'
            ]);

        try{

            $service_id = $request->service_id;

            $check = DB::table('services')
                    ->select('*')        
                    ->where(['name'=>$request->input('name'),'business_id'=>$business_id])  
                    ->whereNotIn('id',[$service_id])      
                    ->first();

            if($check==NULL)
            {
                $type_name = str_replace(array(' '),'_',strtolower($request->name));

                DB::table('services')->where(['id'=>$service_id])->update([
                    'name' => $request->name,
                    'is_multiple_type' => $request->is_multiple_type,
                    'type_name' => $type_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $check_price_master=DB::table('check_price_masters')->where(['business_id'=>$business_id,'service_id'=>$service_id])->first();

                if($check_price_master==NULL)
                {
                    $data=[
                        'parent_id' => $parent_id,
                        'business_id' => $business_id,
                        'service_id' => $service_id,
                        'price' => $request->price,
                        'created_by' =>Auth::user()->id,
                        'used_by' => 'customer',
                        'created_at' => date('Y-m-d H:i:s')
                    ];
        
                    DB::table('check_price_masters')->insert($data);
                }
                else
                {
                    $data=[
                        'price' => $request->price,
                        'updated_by' => Auth::user()->id,
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    DB::table('check_price_masters')->where(['business_id'=>$business_id,'service_id'=>$service_id])->update($data);
                }
                
                DB::commit();
                return response()->json([
                    'fail' => false,
                    'message' => 'updated',
                    'error'=>'no',
                ]);
            }
            else
            {
                return response()->json([
                    'fail' => true,
                    'error' => 'yes',
                    'message'=>'Verification name is already created !'
                ]);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    /**
     * Show the verifictions config.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function verificationConfig($id, Request $request)
    {   
        $get_id = base64_decode($id);
        // dd($get_id);
        $service = DB::table('services')->where(['id'=>$get_id])->first();

        $form_input_masters = DB::table('form_input_masters')->get();

        $form_inputs = DB::table('service_form_inputs as sf')
            ->select('sf.label_name','sf.id','fm.name as type','sf.is_report_output','sf.is_executive_summary') 
            ->join('form_input_masters as fm','fm.id','=','sf.form_input_type_id')       
            ->where(['sf.service_id' => $get_id])        
            ->get(); 

        return view('admin.verifications.view', compact('service','form_input_masters','form_inputs'));

    }

    /**
     * add form input
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveFormInput(Request $request)
    {
        // dd($request);

        $rules = [
            'type'          => 'required',                
            'label_name'    => 'required',
            'mandatory'    => 'required',
            'report_output'    => 'required',
            'executive_output'    => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors(),
                'error_type'=>'validation'
            ]);

        $service_id = $request->input('service_id');
        DB::beginTransaction();
        try{
            //check 
            $check = DB::table('service_form_inputs')
            ->select('*')        
            ->where(['service_id' => $service_id,'label_name'=>$request->input('label_name')])        
            ->first();         

            if($check ===null)
            {
                $storeData =[
                    "service_id"               => $service_id,
                    "form_input_type_id"       => $request->input('type'),
                    "label_name"               => $request->input('label_name'),  
                    "is_mandatory"             => $request->input('mandatory'),   
                    "is_report_output"         => $request->input('report_output'),
                    "is_executive_summary"     => $request->input('executive_output'),                        
                    "created_at"               => date('Y-m-d H:i:s')
                ];
                
                //insert data
                $insertedID = DB::table('service_form_inputs')->insertGetId($storeData);    

                if ($request->type=='6') {
                   $option=$request->no_of_option;
                   
                   for ($i=1; $i <= $option; $i++) { 
                    $option_name=$request->input('option_name'.$i);
                        //service_form_variable_inputs 
                        $optionData=[
                            "service_id"               => $service_id,
                            "parent_id"                 =>'0',
                            "service_form_input_id"       => $insertedID,
                            "form_input_type_id"       => $request->input('type'),
                            "label_name"               => $option_name,                
                            "created_at"               => date('Y-m-d H:i:s')
                        ];
                        DB::table('service_form_variable_inputs')->insertGetId($optionData);  
                   }
                }
                    
                $input_data = DB::table('service_form_inputs as sf')
                ->select('sf.label_name','sf.id','fm.name as type','sf.is_report_output as report_output','sf.is_executive_summary as executive_summary') 
                ->join('form_input_masters as fm','fm.id','=','sf.form_input_type_id')       
                ->where(['sf.id' => $insertedID])        
                ->first();  
                
                DB::commit();
                return response()->json([
                    'fail' => false,
                    'data' => $input_data,
                    'error'=>'no'
                ]);
            }else{
            
                return response()->json([
                    'fail' => true,
                    'error' => 'yes',
                    'message'=>'Label name is already created for this service!'
                ]);

            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
        
    }

    public function verificationDropDownConfig($id,$service_id,Request $request)
    {
        $get_id = base64_decode($id);
        $service_id=base64_decode($service_id);

        $service = DB::table('services')->where(['id'=>$service_id])->first();
        $input_data = DB::table('service_form_inputs')->where('id',$get_id)->first();
        // /dd($input_data);
       $service_form_variable_inputs= DB::table('service_form_variable_inputs')->where(['service_form_input_id'=>$get_id,'parent_id'=>'0'])->get();
       
       return view('admin.verifications.form_input', compact('service_form_variable_inputs','service','input_data'));
    
    }

    public function verificationDropDownConfigSave(Request $request)
    {
       $no_of_field=$request->no_of_field; 
            //    dd($request->all());
       for ($i=1; $i <= $no_of_field; $i++) { 
            $rules = [
                'type-'.$i          => 'required',                
                'label_name-'.$i    => 'required',
                'mandatory-'.$i    => 'required',
                'executive_output-'.$i    => 'required',
                'report_output-'.$i    => 'required',
               
            ];
            
            // $input_id = $request->input('input_id');

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return response()->json([
                    'fail' => true,
                    'errors' => $validator->errors(),
                    'error_type'=>'validation'
            ]);

       }
       
        $parent_id = $request->service_variable;
        // dd($parent_id);
       $service= DB::table('service_form_variable_inputs')->where('id',$parent_id)->first();
        //    dd($service);
        //    $service_id=$service->service_id;
        DB::beginTransaction();
        try{
            // $option_name=$request->input('option_name'.$i);
            for ($j=1; $j <= $no_of_field; $j++) { 
               $service_form_input_id=$service->service_form_input_id;
               $form_input_type_id=$request->input('type-'.$j);
               $label_name=$request->input('label_name-'.$j);
               $is_mandatory=$request->input('mandatory-'.$j);
               $is_report_output=$request->input('report_output-'.$j);
               $is_executive_summary=$request->input('executive_output-'.$j);
            //    dd($form_input_type_id);
                //service_form_variable_inputs form_input_type_id
                $optionData=[
                    "service_id"               => $service->service_id,
                    "parent_id"                 =>$parent_id,
                    "service_form_input_id"       => $service_form_input_id,
                    "form_input_type_id"       => $form_input_type_id,
                    "label_name"               =>  $label_name,  
                    "is_mandatory"             => $is_mandatory,   
                    "is_report_output"         => $is_report_output,
                    "is_executive_summary"     => $is_executive_summary,              
                    "created_at"               => date('Y-m-d H:i:s')
                ];
                DB::table('service_form_variable_inputs')->insertGetId($optionData);  
            }

            DB::commit();
            return response()->json([
                'fail' => false,
                // 'data' => $input_data,
                'error'=>'no'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    
    }

    public function serviceFormInputEdit(Request $request)
    {
        $input_id = $request->input('input_id');

            $data = DB::table('service_form_inputs as sf')
            ->select('sf.label_name','sf.id as input_id','sf.is_mandatory','fm.name as type','fm.id','sf.is_report_output','sf.is_executive_summary') 
            ->join('form_input_masters as fm','fm.id','=','sf.form_input_type_id')
            ->where(['sf.id' => $input_id])        
            ->first(); 
        
            return response()->json([                
                'result' => $data
            ]);
        
    }

    //update forminput item
    public function serviceFormInputUpdte(Request $request)
    {
        $rules = [
            'type'          => 'required',                
            'label_name'    => 'required',
            'mandatory'    => 'required',
            'executive_output'    => 'required',
            'report_output'    => 'required',
        ];
        
        $input_id = $request->input('input_id');

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors(),
                'error_type'=>'validation'
            ]);

        $service_id = $request->input('service_id');
        DB::beginTransaction();
        try{
            //check 
            $check = DB::table('service_form_inputs')
            ->select('*')        
            ->where(['service_id' => $service_id,'label_name'=>$request->input('label_name')])
            ->whereNotIn('id',[$input_id])        
            ->first();         

            if($check ===null)
            {
                $storeUpdate =[
                "form_input_type_id"    => $request->input('type'),
                "label_name"            => $request->input('label_name'),
                "is_mandatory"          => $request->input('mandatory'),   
                "is_report_output"      => $request->input('report_output'),     
                "is_executive_summary"  => $request->input('executive_output'),       
                "updated_at"            => date('Y-m-d H:i:s')];
                
                //insert data
                $is_saved = DB::table('service_form_inputs')
                            ->where('id',$input_id)
                            ->update($storeUpdate);
                
                $data = DB::table('service_form_inputs as sf')
                            ->select('sf.label_name','sf.id as input_id','sf.is_mandatory','fm.name as type','fm.id','sf.is_report_output as report_output','sf.is_executive_summary as executive_summary') 
                            ->join('form_input_masters as fm','fm.id','=','sf.form_input_type_id')
                            ->where(['sf.id' => $input_id])        
                            ->first(); 
                DB::commit();
                return response()->json([
                    'fail' => false,
                    'message' => 'updated',
                    'error'=>'no',
                    'data'=>$data
                ]);
            }
            else{
            
                return response()->json([
                    'fail' => true,
                    'error' => 'yes',
                    'message'=>'Label name is already created for this service!'
                ]);

            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
           
    }

    public function serviceFormInputDelete(Request $request)
    {
        $id=$request->id;

        $data=DB::table('service_form_inputs')->where(['id'=>$id])->first();

        DB::table('service_form_inputs')->where(['id'=>$id])->delete();

        $service_form_inputs=DB::table('service_form_inputs')->where(['service_id'=>$data->service_id])->get();

        if(count($service_form_inputs)>0)
        {
            return response()->json([
                'status' => 'ok',
                'db' => true
            ]);
        }
        else
        {
            return response()->json([
                'status' => 'ok',
                'db' => false
            ]);
        }
    }
    

    // check id - aadhar
    public function idCheckAadhar(Request $request)
    {        
        $business_id = Auth::user()->business_id;
        $user_id=Auth::user()->id;
        $service_id    = base64_decode($request->service_id);

        $response_code = 200;
        $parent_id=Auth::user()->parent_id;
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
          $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
          $parent_id=$users->parent_id;
        }

        $price=20;
        DB::beginTransaction();
        try{
            $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();
            if( $request->has('id_number') ) {
                
                // $rules=[
                //     'id_number' => 'required|numeric|digits:12'
                // ];
                // $validator = Validator::make($request->all(), $rules);
            
                // if ($validator->fails()){
                //     return response()->json([
                //         'success' => false,
                //         'errors' => $validator->errors()
                //     ]);
                // }
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
                        'used_by'           =>'customer',
                        'user_id'            => $user_id,
                        'source_reference'  =>'SystemDB',
                        'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
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
                    $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $resp = curl_exec ( $ch );
                    curl_close ( $ch );
                    
                    $array_data =  json_decode($resp,true);

                    // dd($array_data);

                    if($array_data['success'])
                    {
                        $master_data ="";

                        if($array_data['data']['state']==NULL || $array_data['data']['gender']==NULL || $array_data['data']['last_digits']==NULL)
                        {
                            return response()->json([
                                'fail'      =>true,
                                'error'     =>"yes",
                                'error_message'     =>"It seems like ID number is not valid!"
                            ]);
                        }
                        //check if ID number is new then insert into DB
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
                                    'created_by'            => $user_id,
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
                                    'used_by'           =>'customer',
                                    'user_id'            => $user_id,
                                    'source_reference'  =>'API',
                                    'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
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

                    }
                    else{
                        return response()->json([
                            'fail'      =>true,
                            'error'     =>"yes",
                            'error_message'     =>"It seems like ID number is not valid!"
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
            try{
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
        $business_id = Auth::user()->business_id;
        $user_id=Auth::user()->id;
        $service_id=base64_decode($request->serv_id);
        $client_id=NULL;

        $price=20;

        $parent_id=Auth::user()->parent_id;
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
          $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
          $parent_id=$users->parent_id;
        }

        $checkprice_db=DB::table('check_price_masters')
                            ->select('price')
                            ->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();
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
                //check from live API
                $api_check_status = false;
                $master_data = DB::table('advance_aadhar_otps')->where('business_id',$business_id)->get();
                foreach($master_data as $master)
                {
                    $client_id = $master->client_id;
                }
                // Setup request to send json via POST
                $data = array(
                    'otp'    =>$otp,
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
                    'used_by'           =>'customer',
                    'user_id'            => $user_id,
                    'source_reference'  =>'API',
                    'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
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
        $aadhar=NULL;
        $client_id= Crypt::decryptString($request->client_id);

        $advance_aadhar =  DB::table('aadhar_check_v2s')
        ->select('*')
        ->where('client_id',$client_id)
        ->first();
        // foreach($advance_aadhar as $ad)
        // {
        //     $aadhar = $ad;
        // }

        $aadhar=$advance_aadhar;
                
                // dd($aadhar);
        return view('admin.verifications.v2_aadhar',compact('aadhar'));
    }

    // check id - pan
    public function idCheckPan(Request $request)
    {        
        $business_id = Auth::user()->business_id;
        $user_id=Auth::user()->id;
        $service_id    = base64_decode($request->service_id);

        $price=20;

        $parent_id=Auth::user()->parent_id;
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
          $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
          $parent_id=$users->parent_id;
        }
        DB::beginTransaction();
        try{
            $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();
                                
            if( $request->has('id_number') ) 
            {
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
                
                if($master_data !=null)
                {
                    //store log
                    $data = [
                        'parent_id'         =>$parent_id,
                        'category'          =>$master_data->category,
                        'pan_number'        =>$master_data->pan_number,
                        'full_name'         =>$master_data->full_name,
                        'is_verified'       =>'1',
                        'is_pan_exist'      =>'1',
                        'business_id'       => $business_id,
                        'service_id'        => $service_id,
                        'source_type'       =>'SystemDb',
                        'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                        'used_by'           =>'customer',
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
                                    'business_id'       =>$business_id,
                                    'category'=>$array_data['data']['category'],
                                    'pan_number'=>$array_data['data']['pan_number'],
                                    'full_name'=>$array_data['data']['full_name'],
                                    'is_verified'=>'1',
                                    'is_pan_exist'=>'1',
                                    'created_by'    => $user_id,
                                    'created_at'=>date('Y-m-d H:i:s')
                                    ];
                            
                            DB::table('pan_check_masters')->insert($data);
                            
                            //store log
                            $data = [
                                'parent_id'         =>$parent_id,
                                'category'          =>$array_data['data']['category'],
                                'pan_number'        =>$array_data['data']['pan_number'],
                                'full_name'         =>$array_data['data']['full_name'],
                                'is_verified'       =>'1',
                                'is_pan_exist'      =>'1',
                                'business_id'       =>$business_id,
                                'service_id'        => $service_id,
                                'source_type'       =>'API',
                                'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                                'used_by'           =>'customer',
                                'user_id'            => $user_id,
                                'created_at'=>date('Y-m-d H:i:s')
                                ];
                        
                            DB::table('pan_checks')->insert($data);
                            
                            $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$request->input('id_number')])->first();
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

        $business_id=Auth::user()->business_id;
            $user_id = Auth::user()->id;
            $service_id    = base64_decode($request->service_id);

            $price=20;

            $parent_id=Auth::user()->parent_id;
            if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
            {
                $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
                $parent_id=$users->parent_id;
            }
        DB::beginTransaction();
        try{
                $checkprice_db=DB::table('check_price_masters')
                                    ->select('price')
                                    ->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();

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
                    //store log
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
                        'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                        'used_by'           =>'customer',
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

                            //store log
                            $log_data = [
                                'parent_id'         =>$parent_id,
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
                                'business_id'       =>$business_id,
                                'service_id'        =>$service_id,
                                'source_reference'  =>'API',
                                'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                                'used_by'           =>'customer',
                                'user_id'            => $user_id,
                                'created_at'        =>date('Y-m-d H:i:s')
                                ];

                            DB::table('voter_id_checks')->insert($log_data);
                            
                            $master_data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$request->input('id_number')])->first();
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
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $service_id    = base64_decode($request->service_id);

        $price=20;

        $parent_id=Auth::user()->parent_id;
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }
        
        DB::beginTransaction();
        try{
            $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();
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
                        'vehicle_category_description'  =>$master_data->vehicle_category_description,
                        'pucc_number'               =>$master_data->pucc_number,
                        'pucc_upto'                 =>$master_data->pucc_upto,
                        'masked_name'           =>$master_data->masked_name,
                        'is_verified'           =>'1',
                        'is_rc_exist'           =>'1',
                        'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                        'used_by'               =>'customer',
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
                    $authorization = "Authorization: Bearer ".env('SUREPASS_PRODUCTION_TOKEN'); // Prepare the authorisation token
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
                                    'created_by'            => $user_id,
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
                                'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                                'used_by'               =>'customer',
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
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $service_id    = base64_decode($request->service_id);   

        $price=20.00;

        $parent_id=Auth::user()->parent_id;
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }
        DB::beginTransaction();
        try{
            $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();
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
                        'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                        'used_by'           => 'customer',
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
                    $authorization = "Authorization: Bearer ".env('SUREPASS_PRODUCTION_TOKEN'); // Prepare the authorisation token
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
                                    'business_id'       => $business_id,
                                    'api_client_id'     =>$array_data['data']['client_id'],
                                    'passport_number'   =>$array_data['data']['passport_number'],
                                    'full_name'         =>$array_data['data']['full_name'],
                                    'file_number'       =>$array_data['data']['file_number'],
                                    'date_of_application'=>$array_data['data']['date_of_application'],
                                    'dob'               =>date('Y-m-d',strtotime($request->input('dob'))),
                                    'is_verified'       =>'1',
                                    'is_passport_exist' =>'1',
                                    'created_by'        => $user_id,
                                    'created_at'        =>date('Y-m-d H:i:s')
                                    ];

                            DB::table('passport_check_masters')->insert($data);
                            
                            $master_data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$passport_file_no])->first();

                            $log_data = [
                                'parent_id'         =>$parent_id,
                                'business_id'       =>$business_id,
                                'service_id'        =>$service_id,
                                'source_type'       =>'API',
                                'api_client_id'     =>$master_data->api_client_id,
                                'passport_number'   =>$master_data->passport_number,
                                'full_name'         =>$master_data->full_name,
                                'file_number'       =>$master_data->file_number,
                                'dob'               => $master_data->dob,
                                'date_of_application'=>$master_data->date_of_application,
                                'is_verified'       =>'1',
                                'is_passport_exist' =>'1',
                                'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                                'used_by'           => 'customer',
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
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $service_id=base64_decode($request->service_id);
        $price=20;
        $current_date = date('d-m-Y');

        $response_code = 200;

        $parent_id=Auth::user()->parent_id;
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }

        DB::beginTransaction();
        try{
            $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();
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

                $rules = [
                    'dob'  => 'required|date|before_or_equal:'.$current_date,   
                ];
        
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails())
                {
                    return response()->json([
                        'fail' => true,
                        'errors' => $validator->errors(),
                        'error_type'=>'validation'
                    ]);
                }

                // dd(1);
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
                        'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                        'used_by'           =>'customer',
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
                        'dob'       => date('Y-m-d',strtotime($request->input('dob'))),
                        'async'         => true,
                    );
                    $payload = json_encode($data);
                    $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/driving-license/driving-license";

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                    curl_setopt ( $ch, CURLOPT_POST, 1 );
                    $authorization = "Authorization: Bearer ".env('SUREPASS_PRODUCTION_TOKEN'); // Prepare the authorisation token
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
                    curl_setopt($ch, CURLOPT_URL, $apiURL);
                    // Attach encoded JSON string to the POST fields
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                    $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
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
                                    'created_by'        => $user_id,
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
                                'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                                'used_by'           =>'customer',
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
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $service_id=base64_decode($request->service_id);
        $price=20;

        $parent_id=Auth::user()->parent_id;
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }

        DB::beginTransaction();
        try{
            $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();
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
                        'parent_id'             =>$parent_id,
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
                        'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                        'used_by'               =>'customer',
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
                                    'business_id'           =>$business_id,
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
                                    'created_by'                =>$user_id,
                                    'created_at'            =>date('Y-m-d H:i:s')
                                    ];

                                DB::table('gst_check_masters')->insert($data);
                            
                            $master_data = DB::table('gst_check_masters')->select('*')->where(['gst_number'=>$gstin_number])->first();

                            $log_data = [
                                'parent_id'         =>$parent_id,
                                'business_id'           =>$business_id,
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
                                'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                                'used_by'               =>'customer',
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

    //
    // check id - bank
    public function idCheckBankAccount(Request $request)
    {    
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $service_id=base64_decode($request->service_id);       
        $price=20;

        $parent_id=Auth::user()->parent_id;
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }

        $checkprice_db=DB::table('check_price_masters')
                            ->select('price')
                            ->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();
        DB::beginTransaction();
        try{
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
                        'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                        'used_by'           =>'customer',
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
                    // dd($array_data);
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
                                'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                                'used_by'           =>'customer',
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

    // check instafinance

    public function InstaDetailedCompanyCIN(Request $request)
    {        
        if( $request->has('cin_number') ) {
            
            $cin_number = $request->input('cin_number');

            //check first into master table
            $master_data = DB::table('instafinance_masters')->select('*')->where(['input_number'=>$cin_number])->first();
            
                //check from live API
                
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => "https://instafinancials.com/api/InstaDetailed/V1/json/CompanyCIN/".$cin_number."/OrderReport",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'user-key: 9cEncVB71f+Lj6MYnLQxkzMWz2GV3N2c7VpFqnx1kxdTmOuLlyFPyw==',
                    'Cookie: ASP.NET_SessionId=payyb2c3rluix43tibnow4sp'
                ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                // echo $response;
                $array_data =  json_decode($response,true);
                if (array_key_exists("Order",$array_data))
                {
                    
                        //check if ID number is new then insert into DB
                        $checkIDInDB= DB::table('instafinance_masters')->where(['input_number'=>$cin_number])->count();
                        if($checkIDInDB ==0)
                        {
                            $data = [
                                    'business_id'         =>Auth::user()->id,
                                    'input_number'        =>$cin_number,
                                    'order_id'            =>$array_data['Order']['OrderID'],
                                    'is_verified'         =>'0',
                                    'response_data'       =>$response,
                                    'created_at'          =>date('Y-m-d H:i:s')
                                    ];

                                DB::table('instafinance_masters')->insert($data);
                            
                            $master_data = DB::table('instafinance_masters')->select('*')->where(['input_number'=>$cin_number])->first();
                        }

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


    }

    // check instafinance

    public function InstaFinanceStatus(Request $request)
    {        
        $items = DB::table('instafinance_masters')->where(['is_status_checked'=>'0'])->get();
        foreach($items as $item){
                
            $order_number = $item->order_id;
            
                //check from live API
                // https://instafinancials.com/api/InstaDetailed/V1/{ResponseType}/OrderID/{OrderID}/Status
                
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => "https://instafinancials.com/api/InstaDetailed/V1/json/OrderID/".$order_number."/Status",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'user-key: 9cEncVB71f+Lj6MYnLQxkzMWz2GV3N2c7VpFqnx1kxdTmOuLlyFPyw==',
                    'Cookie: ASP.NET_SessionId=payyb2c3rluix43tibnow4sp'
                ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                // echo $response; die;
                $array_data =  json_decode($response,true);
                
                if($array_data['Order'])
                {
                    
                        $data = [
                                'business_id'         =>Auth::user()->id,
                                'order_id'            =>$array_data['Order']['OrderID'],
                                'is_verified'         =>'0',
                                'status'              =>$array_data['Order']['Status'],
                                'response_data'       =>$response,
                                'created_at'          =>date('Y-m-d H:i:s')
                                ];

                        DB::table('instafinance_master_status')->insert($data);
                        
                   
                    return response()->json([
                        'fail'      =>false,
                        'data'      =>'' 
                    ]);

                }else{
                    return response()->json([
                        'fail'      =>true,
                        'error'     =>"yes",
                        'error'     =>"It seems like ID number is not valid!"
                    ]);
                }
            }
    }

     // Download Report

     public function InstaFinanceDownloadReport(Request $request)
     {        
         $items = DB::table('instafinance_masters')->where(['is_status_checked'=>'0'])->get();
         foreach($items as $item){
                 
             $order_number = $item->order_id;
             
                 $curl = curl_init();
                 curl_setopt_array($curl, array(
                 CURLOPT_URL => "https://instafinancials.com/api/InstaDetailed/V1/json/OrderID/".$order_number."/DownloadReport",
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => '',
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 0,
                 CURLOPT_FOLLOWLOCATION => true,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => 'GET',
                 CURLOPT_HTTPHEADER => array(
                     'user-key: 9cEncVB71f+Lj6MYnLQxkzMWz2GV3N2c7VpFqnx1kxdTmOuLlyFPyw==',
                     'Cookie: ASP.NET_SessionId=payyb2c3rluix43tibnow4sp'
                 ),
                 ));
 
                 $response = curl_exec($curl);
                 curl_close($curl);
                //  echo $response; die;
                 $array_data =  json_decode($response,true);
                 
                 if($array_data['InstaDetailed'])
                 {
                     
                         $data = [
                                 'business_id'         =>Auth::user()->id,
                                 'order_id'            =>$order_number,
                                 'response_data'       =>$response,
                                 'created_at'          =>date('Y-m-d H:i:s')
                                 ];
 
                         DB::table('instafinance_master_status')->insert($data);
                         
                    
                     return response()->json([
                         'fail'      =>false,
                         'data'      =>'' 
                     ]);
 
                 }else{
                     return response()->json([
                         'fail'      =>true,
                         'error'     =>"yes",
                         'error'     =>"It seems like ID number is not valid!"
                     ]);
                 }
             }
     }

    // check Telecom with OTP
    public function idTelecomCheck(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $user_id =Auth::user()->id;
        $service_id=base64_decode($request->service_id);  
        $price=20;

        $parent_id=Auth::user()->parent_id;
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }

        DB::beginTransaction();
        try{

            $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();
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
                //check first into master table
                $master_data = DB::table('telecom_check_master')->select('*')->where(['mobile_no'=>$request->id_number])->first();
                
                if($master_data !=null){
            
                    // store log
                    $check_data = [
                        'parent_id'         =>$parent_id,
                        'business_id'       =>$business_id,
                        'service_id'        =>$service_id,
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
                        'mobile_no'     => $master_data->mobile_no,
                        'is_verified'       =>'1',
                        'is_mobile_exist'   =>'1',
                        'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                        'used_by'           =>'customer',
                        'user_id'           =>$user_id,
                        'source_type'  =>    'SystemDB',
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
                    
                        $data = [
                            'client_id'        =>$array_data['data']['client_id'],
                            'otp_sent'     => $array_data['data']['otp_sent'],
                            'operator' => $array_data['data']['operator'],
                            'if_number' => $array_data['data']['if_number'],
                            'business_id' => $business_id,
                            'mobile_no'   =>$request->id_number,
                            'price'        => $checkprice_db!=NULL?$checkprice_db->price:$price,
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
                            'client_id' => $array_data['data']['client_id']

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
        $user_id=Auth::user()->id;
        $business_id = Auth::user()->business_id;
        $service_id=base64_decode($request->ser_id);
        $price=20;
        $parent_id=Auth::user()->parent_id;

        $count=1;

        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }

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
        try{
                $checkprice_db=DB::table('check_price_masters')
                                    ->select('price')
                                    ->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();
            
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
                            'created_by'    => $user_id,
                            'created_at'       => date('Y-m-d H:i:s'),
                        ];
            
                 
                $insert_id=DB::table('telecom_check_master')->insertGetId($data);

                $master_data=DB::table('telecom_check_master')->where(['id'=>$insert_id])->first();
                
                if($count>1)
                {
                    for($i=0;$i<$count;$i++)
                    {
                        // store log
                        $check_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       =>$business_id,
                            'service_id'        =>$service_id,
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
                            'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                            'used_by'           =>'customer',
                            'user_id'           => $user_id,
                            'source_type'       =>   'API',
                            'created_at'        =>date('Y-m-d H:i:s'),
                            'updated_at'        =>date('Y-m-d H:i:s')
                        ]; 

                        DB::table('telecom_check')->insert($check_data);
                    }
                }
                else
                {
                    // store log
                    $check_data = [
                        'parent_id'         =>$parent_id,
                        'business_id'       =>$business_id,
                        'service_id'        =>$service_id,
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
                        'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                        'used_by'           =>'customer',
                        'user_id'           => $user_id,
                        'source_type'       =>   'API',
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
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }

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
                            'message' => $array_data!=NULL?$array_data['error'] : 'Please Try Again After Some Time !!',
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

        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }

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

        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }

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
            //             'used_by'   => 'customer',
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
                                'created_by' => $user_id,
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
                            'used_by'   => 'customer',
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

        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
            $parent_id=$users->parent_id;
        }
        
        $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();
        if($checkprice_db!=NULL)
        {
            $price = $checkprice_db->price;
        }

        DB::beginTransaction();
        try{

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

                $father_name_match=preg_match('/^[A-Za-z]+([A-Za-z]+\s)*[A-za-z]{3,}$/u', $request->input('fathername'));

                if($request->input('fathername')=='' || !($father_name_match))
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
            //curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            //curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            $token_key = 'tokenKey: '.env('SPRING_TOKEN_KEY');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $token_key)); // Inject the token into the header
            curl_setopt($ch, CURLOPT_URL, $apiURL);
            // Attach encoded JSON string to the POST fields
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            $resp = curl_exec ( $ch );
            $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close ( $ch );
            $array_data =  json_decode($resp,true);
            //dd($array_data);
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
                        'created_by'  => $user_id,
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
                        'user_type' => 'customer',
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

        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
            $parent_id=$users->parent_id;
        }
        
        $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();
        if($checkprice_db!=NULL)
        {
            $price = $checkprice_db->price;
        }

        DB::beginTransaction();
        try{

                $upi_id = $request->input('id_number');
                $id_number=preg_match('/^[\w\.\-_]{3,}@[a-zA-Z]{3,}$/u', $upi_id);

                // dd($id_number);
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
           //dd();
            // var_dump($resp); die;
            // dd(env('SPRING_TOKEN_KEY'));
            if($array_data['db_output']['accountExists']== 'YES')
            {
                $data = [
                    'parent_id' => $parent_id,
                    'business_id' => $business_id,
                    'upi_id'     =>$upi_id,
                    'name'    =>$array_data['db_output']['name'],
                    'created_by'    => $user_id,
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
                    'user_type'           =>'customer',
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

        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
            $parent_id=$users->parent_id;
        }
        
        $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();
        if($checkprice_db!=NULL)
        {
            $price = $checkprice_db->price;
        }

        DB::beginTransaction();
        try{

                $cin = $request->input('id_number');
                //dd($cin);
                $id_number=preg_match('/^([L|U]{1})([0-9]{5})([A-Za-z]{2})([0-9]{4})([A-Za-z]{3})([0-9]{6})$/u', $cin);

                // dd($id_number);

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
            //dd($array_data);
            // dd(env('SPRING_TOKEN_KEY'));
            if($response_code==200)
            {
                $data = [
                    'parent_id'                     =>$parent_id,
                    'business_id'                   =>$business_id,
                    'cin_number'                    =>$cin,
                    'registration_number'           =>$array_data['output']['source']['registration_number'],
                    'company_name'                  =>$array_data['output']['source']['company_name'],
                    'registered_address'            =>$array_data['output']['source']['registered_address'],
                    'date_of_incorporation'         =>$array_data['output']['source']['date_of_incorporation']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_incorporation'])) : NULL,
                    'email_id'                      =>array_key_exists("email_id",$array_data['output']['source']) ? $array_data['output']['source']['email_id'] : NULL,
                    'paid_up_capital_in_rupees'     =>$array_data['output']['source']['paid_up_capital_in_rupees'],
                    'authorised_capital'            =>$array_data['output']['source']['authorised_capital'],
                    'company_category'              =>$array_data['output']['source']['company_category'],
                    'company_subcategory'           =>$array_data['output']['source']['company_subcategory'],
                    'company_class'                 =>$array_data['output']['source']['company_class'],
                    'whether_company_is_listed'     =>$array_data['output']['source']['whether_company_is_listed'],
                    'company_efilling_status'       =>array_key_exists("company_efilling_status",$array_data['output']['source']) ? $array_data['output']['source']['company_efilling_status'] : NULL,
                    'date_of_last_AGM'              =>$array_data['output']['source']['date_of_last_AGM']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_last_AGM'])) : NULL,
                    'date_of_balance_sheet'         =>$array_data['output']['source']['date_of_balance_sheet']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_balance_sheet'])) : NULL,
                    'another_maintained_address'    =>$array_data['output']['source']['another_maintained_address'],
                    'directors'                     => $array_data['output']['source']['directors']!=NULL && count($array_data['output']['source']['directors']) > 0 ? json_encode($array_data['output']['source']['directors']) : NULL,
                    'created_by'                    => $user_id,
                    'created_at'                    =>date('Y-m-d H:i:s')
                    ];

                DB::table('cin_check_masters')->insert($data);
                
                $master_data = DB::table('cin_check_masters')->select('*')->where(['cin_number'=>$cin])->latest()->first();

                $log_data = [
                    'parent_id'                     =>$parent_id,
                    'business_id'                   =>$business_id,
                    'service_id'                    => $service_id,
                    'source_type'                   =>'API',
                    'cin_number'                    =>$cin,
                    'registration_number'           =>$array_data['output']['source']['registration_number'],
                    'company_name'                  =>$array_data['output']['source']['company_name'],
                    'registered_address'            =>$array_data['output']['source']['registered_address'],
                    'date_of_incorporation'         =>$array_data['output']['source']['date_of_incorporation']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_incorporation'])) : NULL,
                    'email_id'                      =>array_key_exists("email_id",$array_data['output']['source']) ? $array_data['output']['source']['email_id'] : NULL,
                    'paid_up_capital_in_rupees'     =>$array_data['output']['source']['paid_up_capital_in_rupees'],
                    'authorised_capital'            =>$array_data['output']['source']['authorised_capital'],
                    'company_category'              =>$array_data['output']['source']['company_category'],
                    'company_subcategory'           =>$array_data['output']['source']['company_subcategory'],
                    'company_class'                 =>$array_data['output']['source']['company_class'],
                    'whether_company_is_listed'     =>$array_data['output']['source']['whether_company_is_listed'],
                    'company_efilling_status'       =>array_key_exists("company_efilling_status",$array_data['output']['source']) ? $array_data['output']['source']['company_efilling_status'] : NULL,
                    'date_of_last_AGM'              =>$array_data['output']['source']['date_of_last_AGM']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_last_AGM'])) : NULL,
                    'date_of_balance_sheet'         =>$array_data['output']['source']['date_of_balance_sheet']!=NULL ? date('Y-m-d',strtotime($array_data['output']['source']['date_of_balance_sheet'])) : NULL,
                    'another_maintained_address'    =>$array_data['output']['source']['another_maintained_address'],
                    'directors'                     => $array_data['output']['source']['directors']!=NULL && count($array_data['output']['source']['directors']) > 0 ? json_encode($array_data['output']['source']['directors']) : NULL,
                    'is_verified'                   =>'1',
                    'price'                         =>$price,
                    'user_type'                     =>'customer',
                    'user_id'                       =>$user_id,
                    'created_at'                    =>date('Y-m-d H:i:s')
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

     // check id - upi
     public function idCheckVital(Request $request)
     {    
        $rules = [
            'vital_name'      => 'required',
            'name'            => 'required',
            'relevancyScore'  =>  'integer'
          ];

        $validator = Validator::make($request->all(), $rules);
          
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        $selected       = $request->vital_name;
      
        $vitalParents = DB::table('service_vitals')
                        ->where(['vital_name'=>$selected])
                        ->orWhere('parent_id','0')
                        ->where('status',1)
                        ->whereNotIn('vital_name',['V4CRI','V4CRT','Vital4Trace'])
                        ->get();

        $selected_vital_name = [];
        foreach($vitalParents as $item){
            $selected_vital_name[] = $item->vital_name;
        }                
        //dd($selected_vital_name);

        //$vitalNameScore = $vitalParents->vital_name;   
        //dd($vitalNameScore);
        unset($selected[0]);
        
         $business_id=Auth::user()->business_id;
         $user_id = Auth::user()->id;
        
         $price=50;
         $service_id   =base64_decode($request->service_id); 
         $vital_name   = $request->vital_name;
         $name        = $request->name;
         $cuntry      = $request->country_id!='-select-' && $request->country_id!='select' && $request->country_id!='Select' ? $request->country_id : null;
         $state       = $request->state_name!='-select-' && $request->state_name!='select' && $request->state_name!='Select' ? $request->state_name : null;
         $city        = $request->city_name!='-select-' && $request->city_name!='select' && $request->city_name!='Select' ? $request->city_name : null;
         $vital_id    = $request->vital_id;
         $Id_number   = $request->Id_number;
         $dateOfBirth = $request->dateOfBirth;
         $address_line_1 = $request->address_line_1;
         $postal_code    = $request->postal_code;
         $selected       = implode(',',$selected);
         $parent_id=Auth::user()->parent_id;

         $relevancyScore  = $request->relevancyScore;
         $decimal = $relevancyScore / 100;
         //dd($decimal);
        
         if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
         {
             $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
             $parent_id=$users->parent_id;
         }
         
         $checkprice_db=DB::table('check_price_masters')
                                 ->select('price')
                                 ->where(['business_id'=>$parent_id,'service_id'=>$service_id])
                                 ->first();
         if($checkprice_db!=NULL)
         {
             $price = $checkprice_db->price;
         }
 
         DB::beginTransaction();
         try{
               
                $selected       = $request->vital_name;
                unset($selected[0]);
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

                $exists = $request->record_check;
                if($exists == 'exiting_record')
                {
                    $selected       = $request->vital_name;
                    unset($selected[0]);
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

                    $master_data = DB::table('vital_check_masters')->select('*')->where(['subject' => $request->name,'subjectId' => $request->Id_number])->first();
                    
                    if($master_data !=null)
                    {
                        $log_data = 
                            [
                                'parent_id'      => $parent_id,
                                'business_id'    => $business_id,
                                'service_id'     => $service_id,
                                'source_type'    =>  'API',
                                'billingCodeId'  =>  env('VITAL4_BILLING_CODE'),
                                //'product_type'   =>  implode(',',$selected),
                                'subject'        =>  $request->name,
                                'categories'     =>  implode(',',$selected),
                                'subjectId'         =>  $request->Id_number,
                                'dateOfBirth'       =>  $request->input('dateOfBirth'),
                                'address_line_1'    =>  $request->input('address_line_1'),
                                'city_district'     =>  $city,
                                "country"           => $cuntry,
                                "state"             => $state,
                                'postal_code'       =>  $request->input('postal_code'),
                                'is_verified'       => '1',
                                'price'             => $price,
                                'user_type'         => 'customer',
                                'user_id'           => $user_id,
                                'data_response'     =>  $master_data->data_response,
                                'relevancy_score'   =>  $decimal,
                                'categories'        => implode(',',$selected_vital_name),
                                'created_at'        => date('Y-m-d H:i:s')
                            ];

                        DB::table('vital_checks')->insert($log_data);
                    

                        $vital_json = $master_data->data_response; 
                        $vital_decode =  json_decode($vital_json, true);
                    
                        $form = '';

                        if(isset($vital_decode['wlsResults']) && $vital_decode['wlsResults']!=null && count($vital_decode['wlsResults'])>0)
                        {
                            $decimalValue = number_format($vital_decode['wlsResults'][0]['score'],2);
                            $percentageValue = $decimalValue * 100;

                            $form .='<p class="pt-1 pb-border"></p><strong>Reports</strong><div class="col-6 col-md-4 py-1">    
                                        Score : <span><strong>'.$percentageValue.'%'.'</strong></span><br>
                                        Source List Type :<span><strong>'.$vital_decode['wlsResults'][0]['sourceListType'].'</strong></span><br>
                                        Source Agency Name :<span><strong>'. $vital_decode['wlsResults'][0]['sourceAgencyName'].'</strong></span><br>
                                        Source Parent Agency :<span><strong>'.$vital_decode['wlsResults'][0]['sourceParentAgency'].'</strong></span><br>
                                        Source Region :<span><strong>'.$vital_decode['wlsResults'][0]['sourceRegion'].'</strong></span><br>
                                        Remarks :<span><strong>'.$vital_decode['wlsResults'][0]['remarks'].'</strong></span><br>
                                    </div>';                 
                                
                        }
                    
                        else if(isset($vital_decode['nmResults']) && $vital_decode['nmResults']!=null && count($vital_decode['nmResults'])>0)
                        {
                            $form .='<p class="pt-1 pb-border"></p><strong>Reports</strong><div class="col-6 col-md-4 py-1">    
                                        Score :<span><strong>'.$vital_decode['nmResults'][0]['score'].'%'.'</strong></span><br>
                                        Subject Matched :<span><strong>'.$vital_decode['nmResults'][0]['subjectMatched'].'</strong></span><br>
                                        url :<span><strong>'. $vital_decode['nmResults'][0]['url'].'</strong></span><br>
                                    </div>';
                                
                        }
                        
                        else if(isset($vital_decode['pepResults']) && $vital_decode['pepResults']!=null && count($vital_decode['pepResults'])>0)
                        {
                            $form .='<p class="pt-1 pb-border"></p><strong>Reports</strong><div class="col-6 col-md-4 py-1">    
                                Professional History :<span><strong>'.$vital_decode['pepResults'][0]['professionalHistory'][0].'</strong></span><br>
                                    </div>';                 
                                
                        }
                        
                        DB::commit();
                        return response()->json([
                            'fail' =>false,
                            'data' =>$master_data,
                            'form' => $form
                        ]);
                    }
                    else
                    {
                      
                        $data = 
                        [
                            "billingCodeId" => env('VITAL4_BILLING_CODE'),
                            "subject"       => $request->name,
                            "categories"    => implode(',',$selected),
                            "subjectId"     => $request->Id_number,
                            "parameters" => [
                            "dateOfBirth"     => $request->input('dateOfBirth'),
                            "address line 1"  => $request->input('address_line_1'),
                            "city/district"   => $city,
                            "country"         => $cuntry,
                            "state"           => $state,
                            "postal code"     => $request->input('postal_code'),
                            ],
                        ];
                    
                        $payload = json_encode($data);
                        //    dd($payload);
                
                        //create token response 
                        $apiTokenUrl = "https://vitalsearchapi.azurewebsites.net/api/v3/token";
                        
                        $ch = curl_init();
                    
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
                        curl_setopt($ch,CURLOPT_ENCODING, '',);
                        curl_setopt($ch, CURLOPT_MAXREDIRS,10);             
                        curl_setopt ($ch, CURLOPT_POST, 1);
                        curl_setopt($ch,  CURLOPT_TIMEOUT, 0);
                        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
                        curl_setopt($ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                        curl_setopt($ch,  CURLOPT_POSTFIELDS, 'username='.env("VITAL4_USERNAME").'&password='.env("VITAL4_PASSWORD"));
                        // Set the content type to application/json
                        curl_setopt($ch,CURLOPT_HTTPHEADER , array('Content-Type: application/x-www-form-urlencoded'));
                        curl_setopt($ch, CURLOPT_URL, $apiTokenUrl);
                        $resp1 = curl_exec ( $ch );
                        $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        curl_close ( $ch );
                    
                        $array_data1 =  json_decode($resp1,true);
                        //  dd($array_data1);
                        //token response end 
                        
                        //api response data
                        $apiURL = "https://vitalsearchapi.azurewebsites.net/api/v3/search/searchfcra";
        
                        $ch1 = curl_init();
                        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);  
                        curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Bearer '.$array_data1['access_token']));
                        curl_setopt ($ch1, CURLOPT_POST, 1);
                        
                        curl_setopt($ch1, CURLOPT_URL, $apiURL);
                        // Attach encoded JSON string to the POST fields
                        curl_setopt($ch1, CURLOPT_POSTFIELDS,$payload);
                        $resp = curl_exec ( $ch1 );
                        $response_code = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
                        curl_close ($ch1);
                        $array_data =  json_decode($resp,true);
                        
                        $data_response = json_encode($array_data['results']['results']);
                        // dd($data_response);
                
                        if($array_data)
                        {
                            $selected       = $request->vital_name;
                            unset($selected[0]);
                            //$data_response = json_encode($input_data);
                            $data = 
                                [
                                'parent_id'      =>  $parent_id,
                                'business_id'    =>  $business_id,
                                'billingCodeId'  =>  env('VITAL4_BILLING_CODE'),
                                //'product_type'   =>  implode(',',$selected),
                                'subject'        =>  $request->name,
                                'categories'     =>  implode(',',$selected),
                                'subjectId'      =>  $request->Id_number,
                                'dateOfBirth'    =>  $request->input('dateOfBirth'),
                                'address_line_1' =>  $request->input('address_line_1'),
                                'city_district'  =>  $city,
                                "country"         => $cuntry,
                                "state"           => $state,
                                'postal_code'     =>  $request->input('postal_code'),
                                'created_by'     =>  $user_id,
                                'data_response'  =>  $data_response,
                                'created_at'     =>  date('Y-m-d H:i:s')
                                ];
                                
                            $vital_id = DB::table('vital_check_masters')->insertGetId($data);
                            //  dd($vital_id);
                            $master_data = DB::table('vital_check_masters')->select('*')->where(['id' => $vital_id])->first();
                        
        
                        $log_data = 
                            [
                                'parent_id'      => $parent_id,
                                'business_id'    => $business_id,
                                'service_id'     => $service_id,
                                'source_type'    =>  'API',
                                'billingCodeId'  =>  env('VITAL4_BILLING_CODE'),
                                //'product_type'   =>  implode(',',$selected),
                                'subject'        =>  $request->name,
                                'categories'     =>  implode(',',$selected),
                                'subjectId'      =>  $request->Id_number,
                                'dateOfBirth'    =>  $request->input('dateOfBirth'),
                                'address_line_1' =>  $request->input('address_line_1'),
                                'city_district'  =>  $city,
                                "country"         => $cuntry,
                                "state"           => $state,
                                'postal_code'     =>  $request->input('postal_code'),
                                'is_verified'       =>'1',
                                'price'             =>$price,
                                'user_type'           =>'customer',
                                'user_id'            =>$user_id,
                                'data_response'  =>  $data_response,
                                'created_at'        =>date('Y-m-d H:i:s')
                            ];
        
                        DB::table('vital_checks')->insert($log_data);
                        
        
                            $vital_json = $master_data->data_response; 
                            $vital_decode =  json_decode($vital_json, true);
                        
                            $form = '';
            
                            if(isset($vital_decode['wlsResults']) && $vital_decode['wlsResults']!=null && count($vital_decode['wlsResults'])>0)
                            {
                                $decimalValue = number_format($vital_decode['wlsResults'][0]['score'],2);
                                $percentageValue = $decimalValue * 100;

                                $form .='<p class="pt-1 pb-border"></p><strong>Reports</strong><div class="col-6 col-md-4 py-1">    
                                            Score : <span><strong>'.$percentageValue.'%'.'</strong></span><br>
                                            Source List Type :<span><strong>'.$vital_decode['wlsResults'][0]['sourceListType'].'</strong></span><br>
                                            Source Agency Name :<span><strong>'. $vital_decode['wlsResults'][0]['sourceAgencyName'].'</strong></span><br>
                                            Source Parent Agency :<span><strong>'.$vital_decode['wlsResults'][0]['sourceParentAgency'].'</strong></span><br>
                                            Source Region :<span><strong>'.$vital_decode['wlsResults'][0]['sourceRegion'].'</strong></span><br>
                                            Remarks :<span><strong>'.$vital_decode['wlsResults'][0]['remarks'].'</strong></span><br>
                                        </div>';                 
                                    
                            }
                        
                            else if(isset($vital_decode['nmResults']) && $vital_decode['nmResults']!=null && count($vital_decode['nmResults'])>0)
                            {
                                $form .='<p class="pt-1 pb-border"></p><strong>Reports</strong><div class="col-6 col-md-4 py-1">    
                                            Score :<span><strong>'.$vital_decode['nmResults'][0]['score'].'%'.'</strong></span><br>
                                            Subject Matched :<span><strong>'.$vital_decode['nmResults'][0]['subjectMatched'].'</strong></span><br>
                                            url :<span><strong>'. $vital_decode['nmResults'][0]['url'].'</strong></span><br>
                                        </div>';
                                    
                            }
                            
                            else if(isset($vital_decode['pepResults']) && $vital_decode['pepResults']!=null && count($vital_decode['pepResults'])>0)
                            {
                                $form .='<p class="pt-1 pb-border"></p><strong>Reports</strong><div class="col-6 col-md-4 py-1">    
                                    Professional History :<span><strong>'.$vital_decode['pepResults'][0]['professionalHistory'][0].'</strong></span><br>
                                        </div>';                 
                                    
                            }
            
                            DB::commit();
                            return response()->json([
                                'fail' =>false,
                                'data' =>$master_data,
                                'form' => $form
                            ]);
                        }else{
            
                            return response()->json([
                                'fail'      =>true,
                                'error'     =>"yes",
                                'error'     =>"It seems like Name is not Valid, Please try again later!"
                            ]);
                        }   
                    }
                }
                else if($exists == 'fresh_record')
                {
                    $selected       = $request->vital_name;
                    unset($selected[0]);
                    $name = $request->input('name');

                    $data = 
                    [
                        "billingCodeId" => env('VITAL4_BILLING_CODE'),
                        "subject"       => $request->name,
                        "categories"    => implode(',',$selected),
                        "subjectId"     => $request->Id_number,
                        "parameters" => [
                        "dateOfBirth"     => $request->input('dateOfBirth'),
                        "address line 1"  => $request->input('address_line_1'),
                        "city/district"   => $city,
                        "country"         => $cuntry,
                        "state"           => $state,
                        "postal code"     => $request->input('postal_code'),
                        //"relevancyScore"  => $request->relevancyScore,
                        ],
                    ];
                
                    $payload = json_encode($data);
                    //dd($payload);
            
                    //create token response 
                    $apiTokenUrl = "https://vitalsearchapi.azurewebsites.net/api/v3/token";
                    
                    $ch = curl_init();
                
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
                    curl_setopt($ch,CURLOPT_ENCODING, '',);
                    curl_setopt($ch, CURLOPT_MAXREDIRS,10);             
                    curl_setopt ($ch, CURLOPT_POST, 1);
                    curl_setopt($ch,  CURLOPT_TIMEOUT, 0);
                    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
                    curl_setopt($ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                    curl_setopt($ch,  CURLOPT_POSTFIELDS, 'username='.env("VITAL4_USERNAME").'&password='.env("VITAL4_PASSWORD"));
                    // Set the content type to application/json
                    curl_setopt($ch,CURLOPT_HTTPHEADER , array('Content-Type: application/x-www-form-urlencoded'));
                    curl_setopt($ch, CURLOPT_URL, $apiTokenUrl);
                    $resp1 = curl_exec ( $ch );
                 
                    $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close ( $ch );
                
                    $array_data1 =  json_decode($resp1,true);
                    // dd($array_data1);
                    //token response end 


                    //score relevancyScore api start
                    $data2 = [
                        "productRelevancyScores" => [
                            [
                                "productid" => $selected_vital_name, 
                                "relevancyScore" => $decimal
                            ],
                        ],
                    ];
                    
                    $payload1 = json_encode($data2);
                    //dd($payload1);

                    $relevancyScore = "https://vitalsearchapi.azurewebsites.net/api/v3/relevancyScores/{30d0ff5c-6f89-45a9-b564-93e770d2d914}";

                    $ch2 = curl_init();
                    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);   
                    curl_setopt($ch2,CURLOPT_ENCODING, '',);
                    curl_setopt($ch2, CURLOPT_MAXREDIRS,10);             
                    //curl_setopt ($ch2, CURLOPT_POST, 1);
                    curl_setopt($ch2,  CURLOPT_TIMEOUT, 0);
                    curl_setopt($ch2,CURLOPT_FOLLOWLOCATION,true);
                    curl_setopt($ch2, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1);
                    curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, 'PUT');
                    curl_setopt($ch2, CURLOPT_POSTFIELDS,$payload1);
                    curl_setopt($ch2, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Bearer '.$array_data1['access_token']));
                    //curl_setopt ($ch2, CURLOPT_POST, 1);
                    
                    curl_setopt($ch2, CURLOPT_URL, $relevancyScore);
                    // Attach encoded JSON string to the POST fields
                   
                    $resp2 = curl_exec ( $ch2 );
                    $response_code = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
                    curl_close ($ch2);
                    $array_data2 =  json_decode($resp2,true);
                    //dd($array_data2);
                   //score relevancyScore api end



                    //api response data
                    $apiURL = "https://vitalsearchapi.azurewebsites.net/api/v3/search/searchfcra";
    
                    $ch1 = curl_init();
                    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);  
                    curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Bearer '.$array_data1['access_token']));
                    curl_setopt ($ch1, CURLOPT_POST, 1);
                    
                    curl_setopt($ch1, CURLOPT_URL, $apiURL);
                    // Attach encoded JSON string to the POST fields
                    curl_setopt($ch1, CURLOPT_POSTFIELDS,$payload);
                    $resp = curl_exec ( $ch1 );
                    $response_code = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
                    curl_close ($ch1);
                    $array_data =  json_decode($resp,true);
                    
                    $data_response = json_encode($array_data['results']['results']);
                    // dd($data_response);
            
                   
                    if($array_data)
                    {
                        $selected       = $request->vital_name;
                        unset($selected[0]);
                        //$data_response = json_encode($input_data);
                        $data = 
                            [
                                'parent_id'      =>  $parent_id,
                                'business_id'    =>  $business_id,
                                'billingCodeId'  =>  env('VITAL4_BILLING_CODE'),
                                //'product_type'   =>  implode(',',$selected),
                                'subject'        =>  $request->name,
                                'categories'     =>  implode(',',$selected),
                                'subjectId'      =>  $request->Id_number,
                                'dateOfBirth'    =>  $request->input('dateOfBirth'),
                                'address_line_1' =>  $request->input('address_line_1'),
                                'city_district'  =>  $city,
                                "country"         => $cuntry,
                                "state"           => $state,
                                'postal_code'     =>  $request->input('postal_code'),
                                'created_by'     =>  $user_id,
                                'data_response'  =>  $data_response,
                                'relevancy_score' =>  $decimal,
                                'categories'      => implode(',',$selected_vital_name),
                                'created_at'     =>  date('Y-m-d H:i:s')
                            ];
                            
                        $vital_id = DB::table('vital_check_masters')->insertGetId($data);
                        //  dd($vital_id);
                        $master_data = DB::table('vital_check_masters')->select('*')->where(['id' => $vital_id])->first();
                    
    
                    $log_data = 
                        [
                            'parent_id'      => $parent_id,
                            'business_id'    => $business_id,
                            'service_id'     => $service_id,
                            'source_type'    =>  'API',
                            'billingCodeId'  =>  env('VITAL4_BILLING_CODE'),
                            //'product_type'   =>  implode(',',$selected),
                            'subject'        =>  $request->name,
                            'categories'     =>  implode(',',$selected),
                            'subjectId'      =>  $request->Id_number,
                            'dateOfBirth'    =>  $request->input('dateOfBirth'),
                            'address_line_1' =>  $request->input('address_line_1'),
                            'city_district'  =>  $city,
                            "country"         => $cuntry,
                            "state"           => $state,
                            'postal_code'     =>  $request->input('postal_code'),
                            'is_verified'       =>'1',
                            'price'             =>$price,
                            'user_type'           =>'customer',
                            'user_id'            =>$user_id,
                            'data_response'  =>  $data_response,
                            'relevancy_score' =>  $decimal,
                            'categories'      => implode(',',$selected_vital_name),
                            'created_at'        =>date('Y-m-d H:i:s')
                        ];
    
                    DB::table('vital_checks')->insert($log_data);
                    
    
                        $vital_json = $master_data->data_response; 
                        $vital_decode =  json_decode($vital_json, true);
                    
                        $form = '';
        
                        if(isset($vital_decode['wlsResults']) && $vital_decode['wlsResults']!=null && count($vital_decode['wlsResults'])>0)
                        {
                            $decimalValue = number_format($vital_decode['wlsResults'][0]['score'],2);
                            $percentageValue = $decimalValue * 100;

                            $form .='<p class="pt-1 pb-border"></p><strong>Reports</strong><div class="col-6 col-md-4 py-1">    
                                        Score : <span><strong>'.$percentageValue.'%'.'</strong></span><br>
                                        Source List Type :<span><strong>'.$vital_decode['wlsResults'][0]['sourceListType'].'</strong></span><br>
                                        Source Agency Name :<span><strong>'. $vital_decode['wlsResults'][0]['sourceAgencyName'].'</strong></span><br>
                                        Source Parent Agency :<span><strong>'.$vital_decode['wlsResults'][0]['sourceParentAgency'].'</strong></span><br>
                                        Source Region :<span><strong>'.$vital_decode['wlsResults'][0]['sourceRegion'].'</strong></span><br>
                                        Remarks :<span><strong>'.$vital_decode['wlsResults'][0]['remarks'].'</strong></span><br>
                                    </div>';                 
                                
                        }
                    
                        else if(isset($vital_decode['nmResults']) && $vital_decode['nmResults']!=null && count($vital_decode['nmResults'])>0)
                        {
                            $form .='<p class="pt-1 pb-border"></p><strong>Reports</strong><div class="col-6 col-md-4 py-1">    
                                        Score :<span><strong>'.$vital_decode['nmResults'][0]['score'].'%'.'</strong></span><br>
                                        Subject Matched :<span><strong>'.$vital_decode['nmResults'][0]['subjectMatched'].'</strong></span><br>
                                        url :<span><strong>'. $vital_decode['nmResults'][0]['url'].'</strong></span><br>
                                    </div>';
                                
                        }
                        
                        else if(isset($vital_decode['pepResults']) && $vital_decode['pepResults']!=null && count($vital_decode['pepResults'])>0)
                        {
                            $form .='<p class="pt-1 pb-border"></p><strong>Reports</strong><div class="col-6 col-md-4 py-1">    
                                Professional History :<span><strong>'.$vital_decode['pepResults'][0]['professionalHistory'][0].'</strong></span><br>
                                    </div>';                 
                                
                        }
        
                        DB::commit();
                        return response()->json([
                            'fail' =>false,
                            'data' =>$master_data,
                            'form' => $form
                        ]);
                    }else{
        
                        return response()->json([
                            'fail'      =>true,
                            'error'     =>"yes",
                            'error'     =>"It seems like Name is not Valid, Please try again later!"
                        ]);
                    }   
                }
                else
                {
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

    // check id - court check v1
    public function idCheckCourtV1(Request $request)
    {    
        
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;

        $price=50;
        $service_id=base64_decode($request->service_id); 

        $parent_id=Auth::user()->parent_id;

        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
            $parent_id=$users->parent_id;
        }
        
        $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();
        if($checkprice_db!=NULL)
        {
            $price = $checkprice_db->price;
        }

        DB::beginTransaction();
        try{

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
                    'user_type' => 'customer',
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
                        'user_type' => 'customer',
                        'created_at' =>date('Y-m-d H:i:s')
                    ]);

                    $master_data = CourtCheckMasterV1::from('court_check_masters_v1')->where(['id'=>$master_data_id])->first();

                    DB::commit();
                    return response()->json([
                        'fail'      =>false,
                        'data'      =>$master_data 
                    ]);

                }else{

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

    
    // check id - aadhar
    public function idCheckAadhar1($aadhar_number,$id)
    {  
        $parent_id=Auth::user()->parent_id;      
        $business_id = Auth::user()->business_id;
        $user_id=Auth::user()->id;
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }
        $price=20;
        $path=public_path().'/bulk_verification/reports/pdf/'.$user_id.'/';
        $file_name='aadhar-'.$aadhar_number.date('Ymdhis').".pdf";
        $checkprice_db=DB::table('check_price_masters')
        ->select('price')
        ->where(['business_id'=>$parent_id,'service_id'=>'2'])->first();

        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        //check first into master table
        $master_data = DB::table('aadhar_check_masters')->select('*')->where(['aadhar_number'=>$aadhar_number])->first();
        
        if($master_data !=null){
            
                // store log
                $check_data = [
                'parent_id'         =>$parent_id,
                'business_id'       =>$business_id,
                'service_id'        =>'2',
                'aadhar_number'     =>$master_data->aadhar_number,
                'age_range'         =>$master_data->age_range,
                'gender'            =>$master_data->gender,
                'state'             =>$master_data->state,
                'last_digit'        =>$master_data->last_digit,
                'is_verified'       =>'1',
                'is_aadhar_exist'   =>'1',
                'used_by'           =>'customer',
                'user_id'            => $user_id,
                'source_reference'  =>'SystemDB',
                'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                'created_at'        =>date('Y-m-d H:i:s')
            ]; 

            DB::table('aadhar_checks')->insert($check_data);

            $pdf = PDF::loadView('admin.instantverification.pdf.aadhar', compact('master_data'))
                ->save($path.$file_name); 
            
            DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'2'])->update([
                'status' => 'success',
                'file_name' => $file_name,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            
            
        }
        else{
            //check from live API
            $api_check_status = false;
            // Setup request to send json via POST
            $data = array(
                'id_number'    => $aadhar_number,
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

                if($array_data['data']['state']==NULL || $array_data['data']['gender']==NULL || $array_data['data']['last_digits']==NULL)
                {
                    $pdf = PDF::loadView('admin.instantverification.pdf.failed.aadhar', compact('aadhar_number'))
                                ->save($path.$file_name);

                        DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'2'])->update([
                        
                            'status' => 'failed',
                            'file_name' => $file_name,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                }
                else
                {
                    //check if ID number is new then insert into DB
                    $checkIDInDB= DB::table('aadhar_check_masters')->where(['aadhar_number'=>$aadhar_number])->count();
                    if($checkIDInDB ==0)
                    {
                        $gender = 'Male';
                        if($array_data['data']['gender'] == 'F'){
                            $gender = 'Female';
                        }
                        $data = [
                                'parent_id'  => $parent_id,
                                'business_id'  => $business_id,
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
                                'service_id'        =>'2',
                                'aadhar_number'     =>$array_data['data']['aadhaar_number'],
                                'age_range'         =>$array_data['data']['age_range'],
                                'gender'            =>$gender,
                                'state'             =>$array_data['data']['state'],
                                'last_digit'        =>$array_data['data']['last_digits'],
                                'is_verified'       =>'1',
                                'is_aadhar_exist'   =>'1',
                                'used_by'           =>'customer',
                                'user_id'            => $user_id,
                                'source_reference'  =>'API',
                                'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                                'created_at'        =>date('Y-m-d H:i:s')
                                ]; 
                        DB::table('aadhar_checks')->insert($business_data);
                        
                        $master_data = DB::table('aadhar_check_masters')->select('*')->where(['aadhar_number'=>$aadhar_number])->first();

                    }
                    
                    $pdf = PDF::loadView('admin.instantverification.pdf.aadhar', compact('master_data'))
                    ->save($path.$file_name); 
                
                    DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'2'])->update([
                        'status' => 'success',
                        'file_name' => $file_name,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
            else{
                $pdf = PDF::loadView('admin.instantverification.pdf.failed.aadhar', compact('aadhar_number'))
                ->save($path.$file_name);

                DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'2'])->update([
                    'status' => 'failed',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
        }
        

    }

    // check id - pan
    public function idCheckPan1($pan_number,$id)
    {    
        $parent_id=Auth::user()->parent_id;    
        $business_id = Auth::user()->business_id;
        $user_id=Auth::user()->id;
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }
        $price=20;
        $checkprice_db=DB::table('check_price_masters')
        ->select('price')
        ->where(['business_id'=>$parent_id,'service_id'=>'3'])->first();
        $path=public_path().'/bulk_verification/reports/pdf/'.$user_id.'/';

        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

      
        $file_name='pan-'.$pan_number.date('Ymdhis').".pdf";
        
       
        //check first into master table
        $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$pan_number])->first();
        
        if($master_data !=null){
            //store log
            $data = [
                'parent_id'         =>$parent_id,
                'category'          =>$master_data->category,
                'pan_number'        =>$master_data->pan_number,
                'full_name'         =>$master_data->full_name,
                'is_verified'       =>'1',
                'is_pan_exist'      =>'1',
                'business_id'       => $business_id,
                'service_id'        => '3',
                'source_type'       =>'SystemDb',
                'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                'used_by'           =>'customer',
                'user_id'            => $user_id,
                'created_at'=>date('Y-m-d H:i:s')
                ];
        
            DB::table('pan_checks')->insert($data);

            $pdf = PDF::loadView('admin.instantverification.pdf.pan', compact('master_data'))
            ->save($path.$file_name); 
        
            DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'3'])->update([
                
                'status' => 'success',
                'file_name' => $file_name,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        }
        else{
            //check from live API
            $api_check_status = false;
            // Setup request to send json via POST
            $data = array(
                'id_number'    => $pan_number,
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
            // dd($array_data);
            // print_r($array_data); die;
            if($array_data['success'])
            {
                //check if ID number is new then insert into DB
                $checkIDInDB= DB::table('pan_check_masters')->where(['pan_number'=>$pan_number])->count();
                if($checkIDInDB ==0)
                {
                    $data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       =>$business_id,
                            'category'=>$array_data['data']['category'],
                            'pan_number'=>$array_data['data']['pan_number'],
                            'full_name'=>$array_data['data']['full_name'],
                            'is_verified'=>'1',
                            'is_pan_exist'=>'1',
                            'created_by'            => $user_id,
                            'created_at'=>date('Y-m-d H:i:s')
                            ];
                    
                    DB::table('pan_check_masters')->insert($data);
                    
                    //store log
                    $data = [
                        'parent_id'         =>$parent_id,
                        'category'          =>$array_data['data']['category'],
                        'pan_number'        =>$array_data['data']['pan_number'],
                        'full_name'         =>$array_data['data']['full_name'],
                        'is_verified'       =>'1',
                        'is_pan_exist'      =>'1',
                        'business_id'       =>$business_id,
                        'service_id'        => '3',
                        'source_type'       =>'API',
                        'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                        'used_by'           =>'customer',
                        'user_id'            => $user_id,
                        'created_at'=>date('Y-m-d H:i:s')
                        ];
                
                    DB::table('pan_checks')->insert($data);
                    
                    $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$pan_number])->first();
                }

                $pdf = PDF::loadView('admin.instantverification.pdf.pan', compact('master_data'))
                        ->save($path.$file_name); 
                    
                DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'3'])->update([
                   
                    'status' => 'success',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            }else{
                $pdf = PDF::loadView('admin.instantverification.pdf.failed.pan', compact('pan_number'))
                        ->save($path.$file_name);
                DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'3'])->update([
                    
                    'status' => 'failed',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
        }




    }

    // check id - Voter ID
    public function idCheckVoterID1($voter_id,$id)
    {   

        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        
        $price=20;
        
        $parent_id=Auth::user()->parent_id;
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }
        $checkprice_db=DB::table('check_price_masters')
        ->select('price')
        ->where(['business_id'=>$parent_id,'service_id'=>'4'])->first();

            // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
            // {
            //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
            //     $parent_id=$users->parent_id;
            // }

        $path=public_path().'/bulk_verification/reports/pdf/'.$user_id.'/';

        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

    
       
        $file_name='voter_id-'.$voter_id.date('Ymdhis').".pdf";
        
      
        //check first into master table
        $master_data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$voter_id])->first();
        if($master_data !=null){
            $data = $master_data;
                //store log
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
                'service_id'        =>'4',
                'source_reference'  =>'SystemDb',
                'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                'used_by'           =>'customer',
                'user_id'            => $user_id,
                'created_at'        =>date('Y-m-d H:i:s')
                ];

            DB::table('voter_id_checks')->insert($log_data);

            $pdf = PDF::loadView('admin.instantverification.pdf.voter-id', compact('master_data'))
                ->save($path.$file_name); 
            
                DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'4'])->update([
                   
                    'status' => 'success',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
        else{
            //check from live API
            // Setup request to send json via POST
            $data = array(
                'id_number'    => $voter_id,
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
                $checkIDInDB= DB::table('voter_id_check_masters')->where(['voter_id_number'=>$voter_id])->count();
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

                    //store log
                    $log_data = [
                        'parent_id'         =>$parent_id,
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
                        'business_id'       =>$business_id,
                        'service_id'        =>'4',
                        'source_reference'  =>'API',
                        'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                        'used_by'           =>'customer',
                        'user_id'            => $user_id,
                        'created_at'        =>date('Y-m-d H:i:s')
                        ];

                    DB::table('voter_id_checks')->insert($log_data);
                    
                    $master_data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$voter_id_number])->first();
                }

                $pdf = PDF::loadView('admin.instantverification.pdf.voter-id', compact('master_data'))
                ->save($path.$file_name); 
            
                DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'4'])->update([
                   
                    'status' => 'success',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            }else{
                $voter_id_number =$voter_id;
                $pdf = PDF::loadView('admin.instantverification.pdf.failed.voter-id', compact('voter_id_number'))
                ->save($path.$file_name);
                DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'4'])->update([
                    
                    'status' => 'failed',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
        }

        
    }

    // check id - RC
    public function idCheckRC1($rc_number,$id)
    {        
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;

        $price=20;

        $parent_id=Auth::user()->parent_id;
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }
        $checkprice_db=DB::table('check_price_masters')
        ->select('price')
        ->where(['business_id'=>$parent_id,'service_id'=>'7'])->first();
        // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        // {
        //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
        //     $parent_id=$users->parent_id;
        // }
        
        $path=public_path().'/bulk_verification/reports/pdf/'.$user_id.'/';

        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

       
        $file_name='rc-'.$rc_number.date('Ymdhis').".pdf";
       
        //check first into master table
        $master_data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$rc_number])->first();
        if($master_data !=null){
            $data = $master_data;
            $log_data = [
                'parent_id'         =>$parent_id,
                'business_id'       => $business_id,
                'service_id'        =>'7',
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
                'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                'used_by'               =>'customer',
                'user_id'                =>  $user_id,
                'created_at'            =>date('Y-m-d H:i:s')
                ];

                DB::table('rc_checks')->insert($log_data);

            $pdf = PDF::loadView('admin.instantverification.pdf.rc', compact('master_data'))
            ->save($path.$file_name); 
        
            DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'7'])->update([
                
                'status' => 'success',
                'file_name' => $file_name,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        else{
            //check from live API
            // Setup request to send json via POST
            $data = array(
                'id_number'    => $rc_number,
                'async'         => true,
            );
            $payload = json_encode($data);
            $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/rc/rc";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
            curl_setopt ( $ch, CURLOPT_POST, 1 );
            $authorization = "Authorization: Bearer ".env('SUREPASS_PRODUCTION_TOKEN'); // Prepare the authorisation token
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
                            'created_by'            => $user_id,
                            'created_at'            =>date('Y-m-d H:i:s')
                            ];

                    DB::table('rc_check_masters')->insert($data);
                    
                    $master_data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$rc_number])->first();

                    $log_data = [
                        'parent_id'         =>$parent_id,
                        'business_id'       => $business_id,
                        'service_id'        =>'7',
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
                        'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                        'used_by'               =>'customer',
                        'user_id'                =>  $user_id,
                        'created_at'            =>date('Y-m-d H:i:s')
                        ];

                        DB::table('rc_checks')->insert($log_data);
                }

                $pdf = PDF::loadView('admin.instantverification.pdf.rc', compact('master_data'))
                        ->save($path.$file_name); 
        
                DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'7'])->update([
                    
                    'status' => 'success',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            }else{
                $pdf = PDF::loadView('admin.instantverification.pdf.failed.rc', compact('rc_number'))
                        ->save($path.$file_name);
                DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'7'])->update([
                    
                    'status' => 'failed',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
        }
        


    }

    // check id - Passport
    public function idCheckPassport1($file_number,$dob,$id){  
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        

        $price=20.00;

        $parent_id=Auth::user()->parent_id;
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }
        $checkprice_db=DB::table('check_price_masters')
        ->select('price')
        ->where(['business_id'=>$parent_id,'service_id'=>'8'])->first();
        // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        // {
        //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
        //     $parent_id=$users->parent_id;
        // }
        $path=public_path().'/bulk_verification/reports/pdf/'.$user_id.'/';

        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

    
         

            $file_name='passport-'.$file_number.date('Ymdhis').".pdf";
            
            
            //check first into master table
            $master_data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$file_number])->first();
            if($master_data !=null){

                $log_data = [
                    'parent_id'         =>$parent_id,
                    'business_id'       =>$business_id,
                    'service_id'        =>'8',
                    'source_type'       =>'SystemDb',
                    'api_client_id'     =>$master_data->api_client_id,
                    'passport_number'   =>$master_data->passport_number,
                    'full_name'         =>$master_data->full_name,
                    'file_number'       =>$master_data->file_number,
                    'dob'       =>$master_data->dob,
                    'date_of_application'=>$master_data->date_of_application,
                    'is_verified'       =>'1',
                    'is_passport_exist' =>'1',
                    'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                    'used_by'           => 'customer',
                    'user_id'            => $user_id,
                    'created_at'        =>date('Y-m-d H:i:s')
                    ];

                    DB::table('passport_checks')->insert($log_data);

                    $pdf = PDF::loadView('admin.instantverification.pdf.passport', compact('master_data'))
                    ->save($path.$file_name); 
                
                    DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'8'])->update([
                       
                        'status' => 'success',
                        'file_name' => $file_name,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }
            else{
                //check from live API
                // Setup request to send json via POST
                $data = array(
                    'id_number' => $file_number,
                    'dob'       => $dob,
                    'async'         => true,
                );
                $payload = json_encode($data);
                $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/passport/passport/passport-details";

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
                
                if($array_data['success'])
                {
                    //check if ID number is new then insert into DB
                    $checkIDInDB= DB::table('passport_check_masters')->where(['file_number'=>$file_number])->count();
                    if($checkIDInDB ==0)
                    {
                        
                        $data = [
                                'parent_id'     => $parent_id,
                                'business_id'     => $business_id,
                                'api_client_id'     =>$array_data['data']['client_id'],
                                'passport_number'   =>$array_data['data']['passport_number'],
                                'full_name'         =>$array_data['data']['full_name'],
                                'file_number'       =>$array_data['data']['file_number'],
                                'date_of_application'=>$array_data['data']['date_of_application'],
                                'dob'               =>date('Y-m-d',strtotime($dob)),
                                'is_verified'       =>'1',
                                'is_passport_exist' =>'1',
                                'created_by'        => $user_id,
                                'created_at'        =>date('Y-m-d H:i:s')
                                ];

                        DB::table('passport_check_masters')->insert($data);
                        
                        $master_data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$file_number])->first();

                        $log_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       =>$business_id,
                            'service_id'        =>'8',
                            'source_type'       =>'API',
                            'api_client_id'     =>$master_data->api_client_id,
                            'passport_number'   =>$master_data->passport_number,
                            'full_name'         =>$master_data->full_name,
                            'file_number'       =>$master_data->file_number,
                            'dob'               =>$master_data->dob,
                            'date_of_application'=>$master_data->date_of_application,
                            'is_verified'       =>'1',
                            'is_passport_exist' =>'1',
                            'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                            'used_by'           => 'customer',
                            'user_id'            => $user_id,
                            'created_at'        =>date('Y-m-d H:i:s')
                            ];

                        DB::table('passport_checks')->insert($log_data);
                    }

                    $pdf = PDF::loadView('admin.instantverification.pdf.passport', compact('master_data'))
                    ->save($path.$file_name); 
                
                    DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'8'])->update([
                       
                        'status' => 'success',
                        'file_name' => $file_name,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                }else{

                    $pdf = PDF::loadView('admin.instantverification.pdf.failed.passport', compact('file_number','dob'))
                    ->save($path.$file_name);
                    DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'8'])->update([
                        
                        'status' => 'failed',
                        'file_name' => $file_name,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                
            }
        


    }

    // check id - DL
    public function idCheckDL1($dl_number,$dob,$id)
    {        
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $price=20;

        $parent_id=Auth::user()->parent_id;
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }
        $checkprice_db=DB::table('check_price_masters')
        ->select('price')
        ->where(['business_id'=>$parent_id,'service_id'=>'9'])->first();
        // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        // {
        //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
        //     $parent_id=$users->parent_id;
        // }

        $path=public_path().'/bulk_verification/reports/pdf/'.$user_id.'/';

        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

    
            
            $file_name='dl-'.$dl_number.date('Ymdhis').".pdf";
            
           

            // dd($dl_number);
            
            //check first into master table
            $master_data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$dl_number])->first();
            
            if($master_data !=null){

                $log_data = [
                    'parent_id'         =>$parent_id,
                    'business_id'       => $business_id,
                    'service_id'        => '9',
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
                    'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                    'used_by'           =>'customer',
                    'user_id'            =>$user_id,
                    'created_at'        =>date('Y-m-d H:i:s')
                    ];
                
                DB::table('dl_checks')->insert($log_data);
                $pdf = PDF::loadView('admin.instantverification.pdf.dl', compact('master_data'))
                ->save($path.$file_name); 
            
                DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'9'])->update([
                   
                    'status' => 'success',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            else{
                //check from live API
                // Setup request to send json via POST
                $data = array(
                    'id_number'    => $dl_number,
                    'dob'          => $dob,
                    'async'         => true,
                );
                $payload = json_encode($data);
                $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/driving-license/driving-license";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                curl_setopt ( $ch, CURLOPT_POST, 1 );
                $authorization = "Authorization: Bearer ".env('SUREPASS_PRODUCTION_TOKEN'); // Prepare the authorisation token
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
                curl_setopt($ch, CURLOPT_URL, $apiURL);
                // Attach encoded JSON string to the POST fields
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                $resp = curl_exec ( $ch );
                curl_close ( $ch );
                $array_data =  json_decode($resp,true);

            //    dd($array_data);
                // print_r($array_data); 
                // die;

                if($array_data['success'])
                {
                    //check if ID number is new then insert into DB
                    $checkIDInDB= DB::table('dl_check_masters')->where(['dl_number'=>$dl_number])->count();
                    // dd($checkIDInDB);
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
                        // DB::enableQueryLog();
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
                                //'dob'               =>$array_data['data']['dob'],
                                'dob'               =>date('Y-m-d',strtotime($dob)),
                                'doe'               =>$array_data['data']['doe'],
                                'transport_doe'     =>$array_data['data']['transport_doe'],
                                'doi'               =>$array_data['data']['doi'],
                                'is_verified'       =>'1',
                                'is_rc_exist'       =>'1',
                                'created_by'        => $user_id,
                                'created_at'        =>date('Y-m-d H:i:s')
                                ];
                            
                            DB::table('dl_check_masters')->insert($data);

                            // dd(DB::getQueryLog());
                        
                        $master_data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$dl_number])->first();

                        // dd($master_data);

                        $log_data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       => $business_id,
                            'service_id'        =>'9',
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
                            'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                            'used_by'           =>'customer',
                            'user_id'            =>$user_id,
                            'created_at'        =>date('Y-m-d H:i:s')
                            ];
                        
                        DB::table('dl_checks')->insert($log_data);
                    }

                    $pdf = PDF::loadView('admin.instantverification.pdf.dl', compact('master_data'))
                            ->save($path.$file_name); 
                        
                    DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'9'])->update([
                       
                        'status' => 'success',
                        'file_name' => $file_name,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                }else{
                    // dd($id);
                    $pdf = PDF::loadView('admin.instantverification.pdf.failed.dl',compact('dl_number'))
                    ->save($path.$file_name);

                    DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'9'])->update([
                       
                        'status' => 'failed',
                        'file_name' => $file_name,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                
            }

        


    }

    // check id - bank
    public function idCheckBankAccount1($account_number,$ifsc_code,$id)
    {    
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $price=20;

        $parent_id=Auth::user()->parent_id;
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }
        $checkprice_db=DB::table('check_price_masters')
        ->select('price')
        ->where(['business_id'=>$parent_id,'service_id'=>'12'])->first();

        // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        // {
        //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
        //     $parent_id=$users->parent_id;
        // }
        $path=public_path().'/bulk_verification/reports/pdf/'.$user_id.'/';

        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

    

        $file_name='bank-'.$account_number.date('Ymdhis').".pdf";
        
      

        //check first into master table
        $master_data = DB::table('bank_account_check_masters')->select('*')->where(['account_number'=>$account_number])->first();
        if($master_data !=null){
            

            $log_data = [
                'parent_id'         =>$parent_id,
                'business_id'       =>$business_id,
                'service_id'            => '12',
                'source_type'       =>'SystemDb',
                'api_client_id'     =>$master_data->api_client_id,
                'account_number'    =>$master_data->account_number,
                'full_name'         =>$master_data->full_name,
                'ifsc_code'         =>$master_data->ifsc_code,
                'is_verified'       =>'1',
                'is_account_exist' =>'1',
                'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                'used_by'           =>'customer',
                'user_id'            =>$user_id,
                'created_at'        =>date('Y-m-d H:i:s')
                ];

            DB::table('bank_account_checks')->insert($log_data);

            $pdf = PDF::loadView('admin.instantverification.pdf.bank-verification', compact('master_data'))
                    ->save($path.$file_name); 
        
            DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'12'])->update([
                
                'status' => 'success',
                'file_name' => $file_name,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        else{
            //check from live API
            // Setup request to send json via POST
            $data = array(
                'id_number' => $account_number,
                'ifsc'      => $ifsc_code,
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
                $checkIDInDB= DB::table('bank_account_check_masters')->where(['account_number'=>$account_number])->count();
                if($checkIDInDB ==0)
                {
                    
                    $data = [
                            'parent_id'         =>$parent_id,
                            'business_id'       =>$business_id,
                            'api_client_id'     =>$array_data['data']['client_id'],
                            'account_number'    =>$account_number,
                            'full_name'         =>$array_data['data']['full_name'],
                            'ifsc_code'         =>$ifsc_code,
                            'is_verified'       =>'1',
                            'is_account_exist' =>'1',
                            'created_by'            =>$user_id,
                            'created_at'        =>date('Y-m-d H:i:s')
                            ];

                    DB::table('bank_account_check_masters')->insert($data);
                    
                    $master_data = DB::table('bank_account_check_masters')->select('*')->where(['account_number'=>$account_number])->first();

                    $log_data = [
                        'parent_id'         =>$parent_id,
                        'business_id'       =>$business_id,
                        'service_id'         => '12',
                        'source_type'       =>'API',
                        'api_client_id'     =>$master_data->api_client_id,
                        'account_number'    =>$master_data->account_number,
                        'full_name'         =>$master_data->full_name,
                        'ifsc_code'         =>$master_data->ifsc_code,
                        'is_verified'       =>'1',
                        'is_account_exist' =>'1',
                        'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                        'used_by'           =>'customer',
                        'user_id'            =>$user_id,
                        'created_at'        =>date('Y-m-d H:i:s')
                        ];

                    DB::table('bank_account_checks')->insert($log_data);
                }

                $pdf = PDF::loadView('admin.instantverification.pdf.bank-verification', compact('master_data'))
                        ->save($path.$file_name); 
                    
                DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'12'])->update([
                    
                    'status' => 'success',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            }else{
                $pdf = PDF::loadView('admin.instantverification.pdf.failed.bank-verification', compact('account_number','ifsc_code'))
                        ->save($path.$file_name);
                DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'12'])->update([
                   
                    'status' => 'failed',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
        }
            

    }

    // check id - GSTIN

    public function idCheckGSTIN1($gstin_number,$filling_status,$id)
    {   
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $price=20;
    
        $parent_id=Auth::user()->parent_id;
        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
        }
        $checkprice_db=DB::table('check_price_masters')
        ->select('price')
        ->where(['business_id'=>$parent_id,'service_id'=>'14'])->first();

        // if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        // {
        //     $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
        //     $parent_id=$users->parent_id;
        // }
        $path=public_path().'/bulk_verification/reports/pdf/'.$user_id.'/';

        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }



        $file_name='gstin-'.$gstin_number.date('Ymdhis').".pdf";
        
    

        //check first into master table
        $master_data = DB::table('gst_check_masters')->select('*')->where(['gst_number'=>$gstin_number])->first();
        if($master_data !=null){

            $log_data = [
                'parent_id'             =>$parent_id,
                'business_id'           =>$business_id,
                'service_id'            => '14',
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
                'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                'used_by'               =>'customer',
                'user_id'                =>$user_id,
                'created_at'            =>date('Y-m-d H:i:s')
                ];

            DB::table('gst_checks')->insert($log_data);

            $pdf = PDF::loadView('admin.instantverification.pdf.gstin', compact('master_data'))
                    ->save($path.$file_name); 
        
            DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'14'])->update([
                
                'status' => 'success',
                'file_name' => $file_name,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        else{
            //check from live API
            // Setup request to send json via POST
            $data = array(
                'id_number' => $gstin_number,
                'filing_status_get' => $filling_status,
                'async' => true,
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
                            'business_id'       =>$business_id,
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
                            'created_by'            => $user_id,
                            'created_at'            =>date('Y-m-d H:i:s')
                            ];

                        DB::table('gst_check_masters')->insert($data);
                    
                    $master_data = DB::table('gst_check_masters')->select('*')->where(['gst_number'=>$gstin_number])->first();

                    $log_data = [
                        'parent_id'         =>$parent_id,
                        'business_id'           =>$business_id,
                        'service_id'            => '14',
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
                        'price'             =>$checkprice_db!=NULL?$checkprice_db->price:$price,
                        'used_by'               =>'customer',
                        'user_id'                =>$user_id,
                        'created_at'            =>date('Y-m-d H:i:s')
                        ];

                    DB::table('gst_checks')->insert($log_data);
                }

                $pdf = PDF::loadView('admin.instantverification.pdf.gstin', compact('master_data'))
                        ->save($path.$file_name); 
                    
                DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'14'])->update([
                    
                    'status' => 'success',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            }else{
                $pdf = PDF::loadView('admin.instantverification.pdf.failed.gstin', compact('gstin_number','filling_status'))
                        ->save($path.$file_name);
                DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>'14'])->update([
                
                    'status' => 'failed',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
        }
            

    }

    public function idCheckUPI1($upi_number,$id)
    {    
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;

        $price=50;

        $parent_id=Auth::user()->parent_id;

        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
            $parent_id=$users->parent_id;
        }

        $path=public_path().'/bulk_verification/reports/pdf/'.$user_id.'/';
        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        $file_name='upi-'.$upi_number.date('Ymdhis').".pdf";

        $instant_v_d = DB::table('instant_verification_demos')->where('id',$id)->first();

        $service_id = $instant_v_d->service_id;
        
        $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();
        if($checkprice_db!=NULL)
        {
            $price = $checkprice_db->price;
        }

        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        

                
            //check from live API
            // Setup request to send json via GET
            // $data = array(
            //     'vpa' => $upi_number,
            // );
            // $payload = json_encode($data);
            $apiURL = "https://api.springscan.springverify.com/v2/user/person/validation/upiID/6156ac22899fc7001815b42a?vpa=".$upi_number;

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
            if($response_code==200)
            {
                $data = [
                    'parent_id' => $parent_id,
                    'business_id' => $business_id,
                    'upi_id'     =>$upi_number,
                    'name'    =>$array_data['db_output']['name'],
                    'created_by'    => $user_id,
                    'created_at'        =>date('Y-m-d H:i:s')
                    ];

                DB::table('upi_check_masters')->insert($data);
                
                $master_data = DB::table('upi_check_masters')->select('*')->where(['upi_id'=>$upi_number])->first();

                $log_data = [
                    'parent_id'         =>$parent_id,
                    'business_id'       =>$business_id,
                    'service_id'         => $service_id,
                    'source_type'       =>'API',
                    'upi_id'            =>$upi_number,
                    'name'              =>$array_data['db_output']['name'],
                    'is_verified'       =>'1',
                    'price'             =>$price,
                    'user_type'           =>'customer',
                    'user_id'            =>$user_id,
                    'created_at'        =>date('Y-m-d H:i:s')
                    ];

                DB::table('upi_checks')->insert($log_data);

                    
                    $pdf = PDF::loadView('admin.instantverification.pdf.upi', compact('master_data'))
                    ->save($path.$file_name); 
                
                    DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>$service_id])->update([
                        'status' => 'success',
                        'file_name' => $file_name,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                

            }else{

                $pdf = PDF::loadView('admin.instantverification.pdf.failed.upi', compact('upi_number'))
                ->save($path.$file_name);

                DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>$service_id])->update([
                    'status' => 'failed',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
       
        
            

    }

    // check id - cin
    public function idCheckCIN1($cin_number,$id)
    {    
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;

        $price=50;
        

        $parent_id=Auth::user()->parent_id;

        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
            $parent_id=$users->parent_id;
        }

        $path=public_path().'/bulk_verification/reports/pdf/'.$user_id.'/';
        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        $file_name='cin-'.$cin_number.date('Ymdhis').".pdf";

        $instant_v_d = DB::table('instant_verification_demos')->where('id',$id)->first();

        $service_id = $instant_v_d->service_id;
        
        $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();
        if($checkprice_db!=NULL)
        {
            $price = $checkprice_db->price;
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
                    "cin_number": "'.$cin_number.'"
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
                    'cin_number'     =>$cin_number,
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
                    'created_by'  => $user_id,
                    'created_at'        =>date('Y-m-d H:i:s')
                    ];

                DB::table('cin_check_masters')->insert($data);
                
                $master_data = DB::table('cin_check_masters')->select('*')->where(['cin_number'=>$cin_number])->latest()->first();

                $log_data = [
                    'parent_id'         =>$parent_id,
                    'business_id'       =>$business_id,
                    'service_id'         => $service_id,
                    'source_type'       =>'API',
                    'cin_number'     =>$cin_number,
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
                    'user_type'           =>'customer',
                    'user_id'            =>$user_id,
                    'created_at'        =>date('Y-m-d H:i:s')
                    ];

                DB::table('cin_checks')->insert($log_data);

                    
                $pdf = PDF::loadView('admin.instantverification.pdf.cin', compact('master_data'))
                ->save($path.$file_name); 
            
                DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>$service_id])->update([
                    'status' => 'success',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                

            }
            else if($response_code==404)
            {
                $pdf = PDF::loadView('admin.instantverification.pdf.failed.cin', compact('cin_number'))
                ->save($path.$file_name);

                DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>$service_id])->update([
                    'status' => 'failed',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            else{

                $pdf = PDF::loadView('admin.instantverification.pdf.failed.cin', compact('cin_number'))
                ->save($path.$file_name);

                DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>$service_id])->update([
                    'status' => 'failed',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
       
        
            

    }

    // check id - court check v1
    public function idCheckCourtV11($name,$father_name,$address,$state,$type,$id)
    {    
        $business_id=Auth::user()->business_id;
        $user_id = Auth::user()->id;

        $price=50;
        $service_id=base64_decode($request->service_id); 

        $parent_id=Auth::user()->parent_id;

        if(Auth::user()->user_type=='user' || Auth::user()->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id')->where('business_id',$business_id)->first();
            $parent_id=$users->parent_id;
        }

        $path=public_path().'/bulk_verification/reports/pdf/'.$user_id.'/';
        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        $file_name='court_check_v1-'.$user_id.date('Ymdhis').".pdf";

        $instant_v_d = DB::table('instant_verification_demos')->where('id',$id)->first();

        $service_id = $instant_v_d->service_id;
        
        $checkprice_db=DB::table('check_price_masters')
                                ->select('price')
                                ->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();
        if($checkprice_db!=NULL)
        {
            $price = $checkprice_db->price;
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
                'user_type' => 'customer',
                'created_at' =>date('Y-m-d H:i:s')
            ]);

            $pdf = PDF::loadView('admin.instantverification.pdf.court_check_v1', compact('master_data'))
            ->save($path.$file_name); 
        
            DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>$service_id])->update([
                'status' => 'success',
                'file_name' => $file_name,
                'updated_at' => date('Y-m-d H:i:s')
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
                    'user_type' => 'customer',
                    'created_at' =>date('Y-m-d H:i:s')
                ]);

                $master_data = CourtCheckMasterV1::from('court_check_masters_v1')->where(['id'=>$master_data_id])->first();

                $pdf = PDF::loadView('admin.instantverification.pdf.court_check_v1', compact('master_data'))
                    ->save($path.$file_name); 
                
                DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>$service_id])->update([
                    'status' => 'success',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            }
            else
            {
                $pdf = PDF::loadView('admin.instantverification.pdf.failed.court_check_v1', compact('name','father_name','address','state','type'))
                        ->save($path.$file_name);

                DB::table('instant_verification_demos')->where(['id'=>$id,'service_id'=>$service_id])->update([
                    'status' => 'failed',
                    'file_name' => $file_name,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }    

    }

}
