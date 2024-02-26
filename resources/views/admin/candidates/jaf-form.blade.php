@extends('layouts.admin')
<style>
  .disabled-link{
    pointer-events: none;
  }

  .disabled-link-1{
    pointer-events: none;
  }

  .remove-image
    {
        padding: 0px 3px 0px !important;
    }

    .image-area img{
        height: 100px !important;
        width: 100px !important;
        padding: 8px !important;
    }

    .image-area{
        width: 90px !important;
    }

    .remove-image:hover
    {
        padding: 0px 3px 0px !important;
    }

  #myImageModal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
  }
  /* Modal Content (image) */
.image-modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

/* Caption of Modal Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation */
.image-modal-content, #caption {  
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
}

/* The Close Button */
.closeImage {
  position: absolute;
  top: 60px;
  right: 20px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.closeImage:hover,
.closeImage:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .image-modal-content {
    width: 100%;
  }
}
/* .gallery{
    width:100%;
    float:left;
    margin-top:15px;
}*/
.gallery ul{
    margin:0;
    padding:0;
    list-style-type:none;
}
.gallery ul li{
    padding:7px;
    border:2px solid #ccc;
    float:left;
    margin:10px 7px;
    background:none;
    width:auto;
    height:auto;
}
.modal-body.gallery-model {
    min-height: 400px;
    overflow:auto;
}
.gallery img{
    width:133px;
}
.modal-part1 {
    max-width: 72%!important;
   
}
 
.select2-container--default .select2-selection--multiple .select2-selection__rendered{
   height: auto;
}
/* 
  select.form-control[size], select.form-control[multiple] {
    height: calc(5rem + 2px) !important;
  clip-path: inset(0%) !important;
  position:  relative !important;
  overflow: visible !important;
  width: 100% !important;
  padding: 0px !important
} */
</style>
@section('content')
@php
  use App\Traits\S3ConfigTrait;
@endphp
<div class="main-content-wrap sidenav-open d-flex flex-column">
<!-- ============ Body content start ============= -->
<div class="main-content">
  <div class="row">
    <div class="col-sm-11">
        <ul class="breadcrumb">
        <li>
        <a href="{{ url('/home') }}">Dashboard</a>
        </li>
        <li>
        <a href="{{ url('/candidates') }}">Candidate</a>
        </li>
        <li>JAF</li>
        </ul>
    </div>
    <!-- ============Back Button ============= -->
    <div class="col-sm-1 back-arrow">
        <div class="text-right">
        <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
        </div>
    </div>
</div>
<div class="row">
   <div class="card text-left">
      <div class="card-body">
         <div class="row">
            <div class="col-md-12 text-center">
               <h3 class=" mb-1 ">JAF - Job Application Form </h3>
               <p>Submit candidate JAF inputs.</p>
            </div>
            <div class="col-md-12">
                <?php $job_item_id = Request::segment(3); ?>
               <form class="mt-2" method="post" enctype="multipart/form-data" action="{{ url('/candidates/jafFormSave/') }}" id="jafFrm">
                @csrf
                <!-- candidate info -->
                <input type="hidden" name="case_id" value="{{ $job_item_id }}" >
                <input type="hidden" name="candidate_id" value="{{ $candidate->id }}" >
                <input type="hidden" name="business_id" value="{{ $candidate->business_id }}" >
                <div class="row">
                    <div class="col-md-12">
                      <?php 

                        $file_arr = [];

                        $file_arr = Helper::get_jaf_attachFile($candidate->id);

                        $url = '';

                        $filename = NULL;

                        $file_platform = NULL;

                        if(count($file_arr)>0)
                        {
                            $filename = $file_arr['file_name'];

                            $file_platform = $file_arr['file_platform'];
                            // $filename = Helper::get_jaf_attachFile($candidate->id);
                            $extension = pathinfo($filename, PATHINFO_EXTENSION);
                            //   dd($extension);

                            if(stripos($file_platform,'s3')!==false)
                            {
                                $filePath = 'uploads/jaf_details/';

                                $s3_config = S3ConfigTrait::s3Config();

                                $disk = \Storage::disk('s3');

                                $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                    'Bucket'                     => \Config::get('filesystems.disks.s3.bucket'),
                                    'Key'                        => $filePath.$filename,
                                    'ResponseContentDisposition' => 'attachment;'//for download
                                ]);

                                $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                $url = $req->getUri();
                            }
                            else {
                              $url = url('/').'/uploads/jaf_details/'.$filename;
                            }
                        }



                      ?>
                      <div class="row">
                        <div class="col-sm-6">
                          <h4 class="card-title mb-1 mt-2">Profile Info</h4>
                        </div>
                        <div class="col-sm-6">
                          @if ( $filename)
                            @if ($extension=='zip')
                            <a class="btn btn-link" href="{{$url}}" title="download" style="float:right">Candidate's Document<i class="fas fa-download"></i></a>
                            @endif
                            @if ($extension=='pdf' || $extension=='xlsx' || $extension=='csv' || $extension=='docs' || $extension=='docx')
                              <a class="btn btn-link" href="{{$url}}" title="download"   target="_blank" style="float:right">Candidate's Document<i class="fas fa-download"></i></a>
                            @endif
                            @if ( $extension=='png' || $extension=='jpeg' || $extension=='jpg')
                              <a class="btn btn-link" href="{{$url}}"  download  target="_blank" style="float:right">Candidate's Document<i class="fas fa-download"></i></a>
                            @endif
                            {{-- <a class="btn btn-link" href="{{url('/').'/uploads/jaf_details/'.$filename}}" title="download">JAF Details<i class="fas fa-download"></i></a> --}}
                          @endif
                        </div>
                      </div>
                        
                        <p class="pb-border"></p>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                  <label>First name: <strong>{{ $candidate->first_name }}</strong> </label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group">
                              <label>Middle name: <strong>{{ $candidate->middle_name!=NULL ? $candidate->middle_name : 'N/A' }}</strong></label>
                              </div>
                          </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                <label>Last name: <strong>{{ $candidate->last_name ? $candidate->last_name : 'N/A' }}</strong></label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group">
                              <label>Father name: <strong>{{ $candidate->father_name }}</strong></label>
                              </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group">
                            <label>DOB: <strong>{{ date('d-m-Y',strtotime($candidate->dob)) }}</strong></label>
                            {{-- <input class="form-control dob commonDatepicker" type="text" name="dob" value="{{ date('d-m-Y',strtotime($candidate->dob)) }}" readonly> --}}
                            {{-- <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dob"></p> --}}
                            </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group">
                            <label>Gender: <strong>{{ $candidate->gender }}</strong> </label>
                            {{-- <input class="form-control " type="text" name="gender" value="{{ $candidate->gender }}" readonly> --}}
                            </div>
                          </div>
                       
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                              <div class="form-group">
                              <label>Aadhar Number: <strong>{{ $candidate->aadhar_number!=NULL ? $candidate->aadhar_number : 'N/A' }}</strong> </label>
                              {{-- <input class="form-control " type="text" name="aadhar_number" value="{{ $candidate->aadhar_number }}" readonly> --}}
                              </div>
                          </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                <label>Email: <strong>{{ $candidate->email!=NULL ? $candidate->email : 'N/A' }}</strong> </label>
                                {{-- <input class="form-control " type="text" name="email" value="{{ $candidate->email }}" readonly> --}}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                <label>Alternative Email: <strong>{{ $candidate->alternative_email!=NULL ? $candidate->alternative_email : 'N/A' }}</strong> </label>
                                {{-- <input class="form-control " type="text" name="email" value="{{ $candidate->alternative_email }}" readonly> --}}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                <label>Phone: <strong>+{{$candidate->phone_code}}-{{ $candidate->phone }}</strong></label>
                                {{-- <input class="form-control number_only" type="text" name="phone" value="{{ $candidate->phone }}" readonly> --}}
                                </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group">
                              <label>Client emp code: <strong>{{ $candidate->client_emp_code!=NULL ? $candidate->client_emp_code : 'N/A'}}</strong>  </label>
                              {{-- <input class="form-control " type="text" name="client_emp_code" value="{{ $candidate->client_emp_code }}"> --}}
                              </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group">
                            <label>Entity code: <strong>{{ $candidate->entity_code!=NULL ? $candidate->entity_code : 'N/A' }}</strong></label>
                            {{-- <input class="form-control " type="text" name="entity_code" value="{{ $candidate->entity_code }}"> --}}
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="row">
                     <div class="col-md-12">
                         <hr>
                     </div>
                </div>    --}}
                <!-- service item -->
                <?php $user = Auth::user()->user_type ;
                ?>
                {{-- @if ($user == 'customer') --}}
        
                @if( count($jaf_items) >0  )
                  @php
                    $cogent_criminal_result = Helper::cogentCriminalSLA($candidate->business_id,$candidate->id);
                  @endphp
                  @foreach($jaf_items as $item)
                    <?php
                      $j=1;
                      $num ="";
                    ?>
                    <div class="row" style="padding: 10px 0; margin-top:10px; border:1px solid #ddd;">
                      <div class="col-md-6">

                          <h3 class=" mb-2 mt-2">Verification - {{$item->service_name.' - '.$item->check_item_number}}</h3>
                          <p>Provide the inputs data</p>
                            {{-- @php
                              $serviceId = $item->service_id;

                              $disclaimers = DB::table('disclaimers')->select('service_id','disclaimer')
                                              ->where('status','1')
                                              ->where(['business_id'=>$item->business_id,'service_id'=>$serviceId])
                                              ->first();  
                                                      
                            @endphp
                            @if($disclaimers)
                                  <strong style="color: red">Disclaimer:- </strong>{{$disclaimers->disclaimer}}
                            @endif --}}
                          <!-- if check type is address  -->
                          @if($item->service_id == '1')
                            <div class="row" >
                              <div class="col-sm-12">
                                  <div class="form-group">
                                  <label>Address Type <span class="text-danger">*</span></label>
                                    <select class="form-control address-type-{{$item->id}}" name="address-type-{{$item->id}}" >
                                      <option value="">- Select Type -</option>
                                      <option value="current" @if($item->address_type !=null) @if($item->address_type=='current') selected @endif @endif>Current</option>
                                      <option value="permanent" @if($item->address_type !=null) @if($item->address_type=='permanent') selected @endif @endif>Permanent</option>
                                    </select>
                                    <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-address-type-{{$item->id}}"></p>
                                  </div>
                              </div>
                            </div>
                          @endif
                           <!-- if check type is address  -->
                           {{-- @if($item->service_id == '17')
                            <div class="row" >
                              <div class="col-sm-10">
                                  <div class="form-group">
                                    <label>Reference Type <span class="text-danger">*</span></label>
                                    <select class="form-control reference-type-{{$item->id}}" name="reference-type-{{$item->id}}" data-id="{{$item->id}}">
                                      <option value="">- Select Type -</option>
                                      <option value="personal" @if($item->reference_type !=null) @if($item->reference_type=='personal') selected @endif @endif>Personal</option>
                                      <option value="professional" @if($item->reference_type !=null) @if($item->reference_type=='professional') selected @endif @endif>Professional</option>
                                    </select>
                                    <input type="hidden" name="reference-type-{{$item->id}}" value="{{$item->reference_type}}">
                                    <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-reference-type-{{$item->id}}"></p>
                                  </div>
                              </div>
                            </div>
                          @endif --}}
                          <!--  -->
                          <?php

                          $i=0;
                          
                          if($item->service_id==17)
                          {
                            $form_items = Helper::get_check_item_inputs($item->service_id);
                          } 
                          else
                          {
                            $form_items= Helper::get_sla_item_inputs($item->service_id); 
                            //dd($form_items);
                          }
                          $serviceVitalsName = Helper::serviceVitalsName($item->service_id);
                          // dd($serviceVitalsName);
                          $input_item_data = $item->form_data;
                         

                          $input_item_data_array =  json_decode($input_item_data, true); 
                          //  dd($input_item_data_array);
                          ?>
                          {{-- Check Form data column is not null then show filled data --}}
                          @if ($input_item_data_array != null)
                            @foreach($input_item_data_array as $key => $input)
                              <?php $key_val = array_keys($input); $input_val = array_values($input); 
                                    // $vital_selected = explode(',',$input_val[0]);

                                    if (is_array($input_val[0])) {
                                        $string_input = implode(",", $input_val[0]);
                                        $vital_selected = explode(",", $string_input);
                                    } else {
                                        // Proceed with the original operation
                                        $vital_selected = explode(",", $input_val[0]);
                                    }
                                    //dd($vital_selected);
                                    $university_board =  $readonly= "";
                                    $university_board_id="";
                                    $date_calss='';
                                    $show_calender='';
                                    $input_class='error-control';
                                    $edu_auto_cls = '';
                                    $emp_auto_cls = '';
                                    if($key_val[0] =='University Name / Board Name'){ 
                                        $university_board_id = "#searchUniversity_board";
                                        $university_board = "searchUniversity_board";

                                        $edu_auto_cls = 'edu_auto_cls';
                                    }
                                  //name
                                  if($key_val[0]=='First Name' || $key_val[0]=='First name' || $key_val[0]=='first name'){ 
                                    $name = $candidate->first_name;
                                    $readonly ="readonly";
                                    $input_class='';
                                  }
                                  if($key_val[0]=='Last Name' || $key_val[0]=='Last name' || $key_val[0]=='last name'){ 
                                    $name = $candidate->last_name;
                                    //$readonly ="readonly";
                                    $input_class='';
                                  }
                                  if($key_val[0]=='Date of Birth' || $key_val[0]=='DOB' || $key_val[0]=='dob'){ 
                                    // $dob = $candidate->dob;
                                    // if($dob !=NULL){
                                    //   $name = date('d-m-Y',strtotime($candidate->dob));
                                    // }
                                    $date_calss = 'commonDatepicker';
                                    $show_calender = '<span class="show-calender-icon"><i class="fas fa-calendar-alt"></i></span>';

                                  }
                                  if(stripos($key_val[0],'Employee Tenure')!==false)
                                  {
                                    $date_calss = 'commonDatepicker';
                                    $show_calender = '<span class="show-calender-icon"><i class="fas fa-calendar-alt"></i></span>';
                                  }

                                  if(stripos($key_val[0],'Date of Joining (Employee Tenure)')!==false)
                                  {
                                    $date_calss = 'commonDatepicker';
                                    $show_calender = '<span class="show-calender-icon"><i class="fas fa-calendar-alt"></i></span>';
                                  }

                                  if(stripos($key_val[0],'Date of Relieving (Employee Tenure)')!==false)
                                  {
                                    $date_calss = 'commonDatepicker';
                                    $show_calender = '<span class="show-calender-icon"><i class="fas fa-calendar-alt"></i></span>';
                                  }


                                  if(stripos($key_val[0],'Email Address')!==false)
                                  {
                                    $name = $candidate->email;
                                    $readonly ="readonly";
                                    $input_class='';
                                  }

                                  if($item->service_id==10 && $key_val[0]=='Company name')
                                  {
                                    $emp_auto_cls='emp_auto_cls';
                                  }

                                  $check_input=Helper::check_item_input_name($item->service_id,$item->business_id,$key_val[0]);
                              ?>
                              {{-- @if(!(stripos($key_val[0],'Referee Designation')!==false || stripos($key_val[0],'Referee Company')!==false)) --}}
                                  <div class="row">
                                    <div class="col-sm-12">
                                          <div class="form-group">
                                              <label>{{$key_val[0]}} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label><br>
                                              <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{$key_val[0]}}">
                                              @if($item->service_id==17)
                                                @if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                                                  <select class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}">
                                                    <option value="">--Select--</option>
                                                    <option @if(stripos($input_val[0],'personal')!==false) selected @endif value="personal">Personal</option>
                                                    <option @if(stripos($input_val[0],'professional')!==false) selected @endif value="professional">Professional</option>
                                                  </select>
                                                @else
                                                  <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
                                                @endif
                                              @elseif(stripos($item->type_name,'drug_test_5')!==false || stripos($item->type_name,'drug_test_6')!==false || stripos($item->type_name,'drug_test_7')!==false || stripos($item->type_name,'drug_test_8')!==false || stripos($item->type_name,'drug_test_9')!==false || stripos($item->type_name,'drug_test_10')!==false)
                                                @if(stripos($key_val[0],'Test Name')!==false)
                                                  <input class="form-control service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{$input_val[0]}}">
                                                  @php
                                                    $drug_test_name = Helper::drugTestName($item->service_id);
                                                  @endphp
                                                  @if(count($drug_test_name)>0)
                                                    @foreach ($drug_test_name as $d_item)
                                                      <div class="form-check form-check-inline disabled-link-1">
                                                          <input class="form-check-input test-name-{{$item->id.'-'.$i}}" type="checkbox" name="test-name-{{$item->id.'-'.$i}}[]" value="{{$d_item->test_name}}" checked readonly>
                                                          <label class="form-check-label" for="inlineCheckbox-1">{{$d_item->test_name}}</label>
                                                      </div>
                                                    @endforeach
                                                  @endif
                                                @elseif(stripos($key_val[0],'Result')!==false)
                                                  <select class="form-control {{$date_calss.' '.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" name="service-input-value-{{ $item->id.'-'.$i }}" >
                                                      <option value="">--Select--</option>
                                                      <option @if(stripos($input_val[0],'positive')!==false) selected @endif value="positive">Positive</option>
                                                      <option @if(stripos($input_val[0],'negative')!==false) selected  @endif value="negative">Negative</option>
                                                  </select> 
                                                @else
                                                  <input class="form-control {{$date_calss.' '.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}" {{$readonly}}>
                                                @endif
                                                
                                                @elseif(stripos($key_val[0],'Vital Categoryes')!==false)
                                                <select  {{ $readonly }} class="form-control vital_list {{$date_calss.' '.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" name="service-input-value-{{ $item->id.'-'.$i }}[]" multiple>
                                                  <option value="all">-All-</option>
                                                  @foreach ($serviceVitalsName as $serviceVitals)
                                                    <option  value="{{ $serviceVitals->vital_name }}" {{ in_array($serviceVitals->vital_name,$vital_selected) ? 'selected' : '' }}>{{ $serviceVitals->vital_name }} ({{$serviceVitals->vital_full_name}})</option>
                                                  @endforeach
                                              </select>
                                              <p style="margin-bottom: 5px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i}}"></p>

                                              @else
                                                @if($key_val[0]=='Date of Joining (Employee Tenure)' || $key_val[0]=='Date of Relieving (Employee Tenure)')
                                                  <div class="row">
                                                    <div class="col-6">
                                                      <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i.'-1' }}" value="{{ array_key_exists(2,$input_val) && $input_val[2]=='date' ? $input_val[0] : NULL }}" >
                                                      {!! $show_calender !!}
                                                    </div>
                                                    <div class="col-6">
                                                      <input class="form-control {{$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i.'-2' }}" value="{{ array_key_exists(2,$input_val) && $input_val[2]!='date' ? $input_val[0] : NULL }}" >
                                                    </div>
                                                    <div class="col-12">
                                                      <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i.'-1'}}"></p>
                                                      <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i.'-2'}}"></p>
                                                    </div>
                                                  </div>
                                                @else
                                                  <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} {{$edu_auto_cls}} {{$emp_auto_cls}}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}" >
                                                  {!! $show_calender !!}
                                                @endif
                                              @endif
                                              <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i}}"></p>
                                          </div>
                                    </div>
                                  </div>
                              {{-- @endif --}}
                              <?php $i++; ?>
                            @endforeach 
                          @else
                            @foreach($form_items as $input)
                                @php
                                  $serviceVitalsName = Helper::serviceVitalsName($input->service_id);
                                  // dd($serviceVitalsName);          
                                  $check_input= Helper::check_item_input($input->id,$candidate->business_id);
                                  $parent_variable_inputs = Helper::getVariableInput($input->id,0);
                                @endphp
                              @if($input->service_id==17)
                                @if($input->reference_type==NULL && !(stripos($input->label_name,'Mode of Verification')!==false || stripos($input->label_name,'Remarks')!==false))
                                  <div class="row" >
                                    <div class="col-sm-10">
                                        <div class="form-group">
                                          <label> {{ $input->label_name }} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
                                          <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $input->label_name }}">
                                          <input type="hidden" name="jaf_id" id="jaf_id" value="{{$item->id}}">
                                          <?php $input_type=""; $input_type = Helper::get_sla_item_input_type($input->form_input_type_id);
                                          //  $input_item_data = $input->form_data;

                                          //   $input_item_data_array =  json_decode($input_item_data, true); 
                                          //  $key_val = array_keys($input_item_data_array); $input_val = array_values($input_item_data_array); 
                                          // dd($input);
                                          $date_calss = ''; 
                                          // if($input_type == 'date'){
                                          //   $date_calss = 'commonDatepicker';
                                          // }
                                          $name =$lname = $father_name= $dob= "";
                                          $readonly ="";
                                          $placeholder ="";
                                          $input_class='error-control';
                                          //name
                                          if($input->label_name=='First Name' || $input->label_name=='First name' || $input->label_name=='first name'){ 
                                            $name = $candidate->first_name;
                                            $readonly ="readonly";
                                            $input_class='';
                                          }
                                          if($input->label_name=='Last Name' || $input->label_name=='Last name' || $input->label_name=='last name'){ 
                                            $name = $candidate->last_name;
                                            $readonly ="readonly";
                                            $input_class='';
                                          }
                                          //father name
                                          if($input->label_name=='Father Name' || $input->label_name=='father name' || $input->label_name=='Father name'){ 
                                            $name = $candidate->father_name;
                                          }
                                          //dob
                                          if($input->label_name=='Date of Birth' || $input->label_name=='DOB' || $input->label_name=='dob'){ 
                                            $dob = $candidate->dob;
                                            if($dob !=NULL){
                                              $name = date('d-m-Y',strtotime($candidate->dob));
                                            }
                                            $date_calss = 'commonDatepicker';
                                          }
                                          if(stripos($input->label_name,'Date of Expire')!==false)
                                          {
                                            $date_calss = 'commonDatepicker';
                                          }
                                          if(stripos($input->label_name,'Employee Tenure')!==false)
                                          {
                                            $date_calss = 'commonDatepicker';
                                          }
                                          if($input->label_name=='Period of Stay' || $input->label_name=='Period of stay' || $input->label_name=='period of stay'){
                                            $placeholder ="ex- No of days ";
                                          
                                          }
                                          $university_board_name = "";
                                          if($input->label_name=='University Name / Board Name'){ 
                                            $university_board_name = "searchUniversity_board";
                                          }
                                          
                                          if(stripos($input->label_name,'Email Address')!==false)
                                          {
                                            $name = $candidate->email;
                                            $readonly ="readonly";
                                            $input_class='';

                                          }

                                        
                                          ?>
                                          {{-- <label>  {{ $key_val[0]}} </label> 
                                          <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}"> --}}
                                          @if(stripos($input->label_name,'Reference Type (Personal / Professional)')!==false)
                                            <select {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" name="service-input-value-{{ $item->id.'-'.$i }}" placeholder="{{ $placeholder }}">
                                                <option value="">--Select--</option>
                                                <option @if(stripos($name,'personal')!==false) selected @endif value="personal">Personal</option>
                                                <option @if(stripos($name,'professional')!==false) selected  @endif value="professional">Professional</option>
                                            </select>
                                          @else
                                           <input {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $name }}" placeholder="{{ $placeholder }}">
                                          @endif
                                          <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i}}"></p>
                                        </div>
                                    </div>
                                      
                                  </div>
                                @endif
                              @else
                                <div class="row"> 
                                  <div class="col-sm-12">
                                      <div class="form-group">
                                        <?php $input_type=""; $input_type = Helper::get_sla_item_input_type($input->form_input_type_id);
                                          //  $input_item_data = $input->form_data;

                                          //   $input_item_data_array =  json_decode($input_item_data, true); 
                                          //  $key_val = array_keys($input_item_data_array); $input_val = array_values($input_item_data_array); 
                                          // dd($input);
                                          $date_calss = ''; 
                                          $show_calender='';
                                          // if($input_type == 'date'){
                                          //   $date_calss = 'commonDatepicker';
                                          // }
                                          $name =$lname = $father_name= $dob= "";
                                          $readonly ="";
                                          $placeholder ="";
                                          $input_class='error-control';
                                          $edu_auto_cls = '';
                                          $emp_auto_cls = '';
                                          //name
                                          if($input->label_name=='First Name' || $input->label_name=='First name' || $input->label_name=='first name'){ 
                                            $name = $candidate->first_name;
                                            $readonly ="readonly";
                                            $input_class='';
                                          }
                                          if($input->label_name=='Last Name' || $input->label_name=='Last name' || $input->label_name=='last name'){ 
                                            $name = $candidate->last_name;
                                            //$readonly ="readonly";
                                            $input_class='';
                                          }
                                          //father name
                                          if($input->label_name=='Father Name' || $input->label_name=='father name' || $input->label_name=='Father name'){ 
                                            $name = $candidate->father_name;
                                          }
                                          //dob
                                          if($input->label_name=='Date of Birth' || $input->label_name=='DOB' || $input->label_name=='dob'){ 
                                            $dob = $candidate->dob;
                                            if($dob !=NULL){
                                              $name = date('d-m-Y',strtotime($candidate->dob));
                                            }
                                            $date_calss = 'commonDatepicker';
                                            $show_calender = '<span class="show-calender-icon"><i class="fas fa-calendar-alt"></i></span>';
                                          }
                                          if(stripos($input->label_name,'Date of Expire')!==false)
                                          {
                                            $date_calss = 'commonDatepicker';
                                          }
                                          if(stripos($input->label_name,'Employee Tenure')!==false)
                                          {
                                            $date_calss = 'commonDatepicker';
                                            $show_calender = '<span class="show-calender-icon"><i class="fas fa-calendar-alt"></i></span>';
                                          }

                                          if($input->label_name=='Date of Joining (Employee Tenure)')
                                          {
                                            $date_calss = 'commonDatepicker';
                                            $show_calender = '<span class="show-calender-icon"><i class="fas fa-calendar-alt"></i></span>';
                                          }

                                          if($input->label_name=='Date of Relieving (Employee Tenure)')
                                          {
                                            $date_calss = 'commonDatepicker';
                                            $show_calender = '<span class="show-calender-icon"><i class="fas fa-calendar-alt"></i></span>';
                                          }

                                          if($input->label_name=='Period of Stay' || $input->label_name=='Period of stay' || $input->label_name=='period of stay'){
                                            $placeholder ="ex- No of days ";
                                          
                                          }
                                          $university_board_name = "";
                                          if($input->label_name=='University Name / Board Name'){ 
                                            $university_board_name = "searchUniversity_board";

                                            $edu_auto_cls = 'edu_auto_cls';
                                          }

                                          if(stripos($input->label_name,'Email Address')!==false)
                                          {
                                            $name = $candidate->email;
                                            $readonly ="readonly";
                                            $input_class='';

                                          }

                                          if($input->service_id==10 && $input->label_name=='Company name'){ 

                                            $emp_auto_cls = 'emp_auto_cls';
                                          }

                                          if($cogent_criminal_result==true && stripos($input->type_name,'criminal')!==false)
                                          {
                                              if($input->label_name=='Address' || $input->label_name=='Address ')
                                              {
                                                  $name = $candidate->address;
                                              }
                                              else if($input->label_name=='Address Type' || $input->label_name=='Address Type ')
                                              {
                                                  $name = $candidate->address_type;
                                              }
                                          }
                                        ?>
                                        <label> {{ $input->label_name }} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label><br>
                                        <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $input->label_name }}">
                                        <input type="hidden" name="jaf_id" id="jaf_id" value="{{$item->id}}">

                                        {{-- <label>  {{ $key_val[0]}} </label>
                                        <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}"> --}}
                                        @if($input_type!='' && $input_type=='drop down')
                                          <select {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" name="service-input-value-{{ $item->id.'-'.$i }}" >
                                            @if(count($parent_variable_inputs)>0)
                                              @foreach ($parent_variable_inputs as $input_data)
                                                  <option value="{{$input_data->label_name}}">{{$input_data->label_name}}</option>
                                              @endforeach
                                            @endif
                                          </select>
                                        @else
                                          @if(stripos($input->type_name,'drug_test_5')!==false || stripos($item->type_name,'drug_test_6')!==false || stripos($item->type_name,'drug_test_7')!==false || stripos($item->type_name,'drug_test_8')!==false || stripos($item->type_name,'drug_test_9')!==false || stripos($input->type_name,'drug_test_10')!==false)
                                            @if(stripos($input->label_name,'Test Name')!==false)
                                              <input class="form-control service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}">
                                              @php
                                                $drug_test_name = Helper::drugTestName($input->service_id);
                                              @endphp
                                              @if(count($drug_test_name)>0)
                                                @foreach ($drug_test_name as $d_item)
                                                  <div class="form-check form-check-inline disabled-link-1">
                                                      <input class="form-check-input test-name-{{$item->id.'-'.$i}}" type="checkbox" name="test-name-{{$item->id.'-'.$i}}[]" value="{{$d_item->test_name}}" checked readonly>
                                                      <label class="form-check-label" for="inlineCheckbox-1">{{$d_item->test_name}}</label>
                                                  </div>
                                                @endforeach
                                              @endif
                                              <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i}}"></p>
                                            @elseif(stripos($input->label_name,'Result')!==false)
                                              <select {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" name="service-input-value-{{ $item->id.'-'.$i }}" placeholder="{{ $placeholder }}">
                                                  <option value="">--Select--</option>
                                                  <option @if(stripos($name,'positive')!==false) selected @endif value="positive">Positive</option>
                                                  <option @if(stripos($name,'negative')!==false) selected  @endif value="negative">Negative</option>
                                              </select> 
                                              <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i}}"></p>
                                            @else
                                              <input {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $name }}"   placeholder="{{ $placeholder }}">
                                              <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i}}"></p>
                                            @endif
                                              
                                            @elseif(stripos($input->label_name,'Vital Categoryes')!==false)
                                              <select  {{ $readonly }} class="form-control vital_list {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" name="service-input-value-{{ $item->id.'-'.$i }}[]" placeholder="{{ $placeholder }}" multiple>
                                                <option value="all">-All-</option>
                                                @foreach ($serviceVitalsName as $serviceVitals)
                                                  <option  value="{{ $serviceVitals->vital_name }}">{{ $serviceVitals->vital_name }} ({{$serviceVitals->vital_full_name}})</option>
                                                @endforeach
                                            </select>
                                            <p style="margin-bottom: 5px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i}}"></p>
                            
                                          @else
                                            @if($input->label_name=='Date of Joining (Employee Tenure)' || $input->label_name=='Date of Relieving (Employee Tenure)')
                                            <div class="row">
                                              <div class="col-6">
                                                <input {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="text" name="service-input-value-{{ $item->id.'-'.$i.'-1' }}" value="{{ $name }}"   placeholder="{{ $placeholder }}">
                                                {!! $show_calender !!}
                                              </div>
                                              <div class="col-6">
                                                <input {{ $readonly }} class="form-control {{$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="text" name="service-input-value-{{ $item->id.'-'.$i.'-2' }}" value="{{ $name }}"   placeholder="{{ $placeholder }}">
                                              </div>
                                              <div class="col-12">
                                                <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i.'-1'}}"></p>
                                                <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i.'-2'}}"></p>
                                              </div>
                                            </div>
                                            @else
                                              <input {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} {{$edu_auto_cls}} {{$emp_auto_cls}}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $name }}"   placeholder="{{ $placeholder }}">
                                              {!! $show_calender !!}
                                            @endif
                                            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i}}"></p>
                                          @endif
                                          
                                        @endif
                                        </div>
                                  </div>
                                </div>
                              @endif
                              <?php $i++; ?>
                            @endforeach
                          @endif
                          
                              <!-- insufficiency -->
                              <div class="row">
                                  <div class="col-sm-10">
                                      <div class="form-group">
                                        <div class="form-check">
                                          <label class="form-check-label error-control">
                                            <input style="margin-top: 1px;" type="checkbox" class="form-check-input error-control check_insuff "  data-insuff_id="{{ $item->id }}" name="insufficiency-{{ $item->id }}" @if($item->is_insufficiency==1) checked @endif >Mark as insufficiency
                                          </label>
                                        </div>
                                      </div>
                                  </div>
                                  <!--  -->
                                  <div class="col-sm-10">
                                      <div class="form-group">
                                          <label>insufficiency Notes</label>
                                          <input type="text" class="form-control error-control" name="insufficiency-notes-{{ $item->id }}" id="insufficiency-notes-{{ $item->id }}"  @if($item->insufficiency_notes!=NULL) value="{{$item->insufficiency_notes}}" @endif readonly >
                                      </div>
                                  </div>
                                  <div class="col-sm-10">
                                      <div class="form-group">
                                        <label for="label_name">Insufficiency Attachments: <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i></label>
                                        <input type="file" name="attachments-{{$item->id}}[]" id="attachments-{{$item->id}}" multiple    class="form-control error-control attachments-{{$item->id}} disabled-link"  readonly>
                                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-attachments-{{$item->id}}"></p>  
                                    </div>
                                  </div>
                                  <!-- ./ -->
                              </div>
                              <!-- ./insufficiency -->
                      </div>
                      <!-- attachment  -->
                      
                      <div class="col-md-6">

                          <p>Attachments <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i> </p>
                          <button class='btn btn-info clickReorder reorder_link' type="button" add-imageId="{{$item->id}}" data-imageType='main' style=' float:right; '><i class="fas fa-sync"></i> Re-Arrange </button>
                          <a class='btn-link clickSelectFile' add-id="{{$item->id}}" data-number='1' data-result='fileResult1' data-type='main' style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i class='fa fa-plus'></i> Add file </a>
                          <input type='file' class='fileupload' name="file-{{$item->id}}[]" id='file1-{{$item->id}}' accept=".jpg,.jpeg,.png,.pdf" multiple="multiple" style='display:none'/>
                          <div class="bcd_loading"></div>
                          <div class='row fileResult' id="fileResult1-{{$item->id}}" style='min-height: 20px; margin-top: 20px;'>
                          <?php $item_files = Helper::getJAFAttachFiles($item->id); //print_r($item_files); ?>
                          @foreach($item_files as $file)
                              @if($file['attachment_type'] == 'main')
                              <div class="image-area">
                                @if(stripos($file['file_name'],'pdf')!==false)
                                      <img src="{{url('/').'/admin/images/icon_pdf.png'}}" alt="Preview" title="{{$file['file_name']}}">
                                @else
                                      <img src="{{ $file['fileIcon'] }}" alt="Preview" title="{{$file['file_name']}}">
                                @endif
                                  {{-- <img src="{{ $file['filePath'] }}" alt="Preview"> --}}
                                  <a class="remove-image" data-id="{{ $file['file_id'] }}" href="javascript:;" style="display: inline;">×</a>
                                  <input type="hidden" name="fileID[]" value="{{ $file['file_id'] }}">
                              </div>
                              @endif
                          @endforeach
                          </div>
                          <p class="mt-2" style="margin-bottom:1px">Add Supportings: <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i></p>
                          <button class='btn btn-info clickReorder reorder_link' type="button" add-imageId="{{$item->id}}" data-imageType='supporting' style=' float:right; '><i class="fas fa-sync"></i> Re-Arrange </button>    
                          <a class='btn-link clickSelectFile error-control' add-id="{{$item->id}}" data-number='2' data-result='fileResult2' data-type='supporting' style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a>
                          <input type='file' class='fileupload' name="file-{{$item->id}}[]" id='file2-{{$item->id}}' multiple="multiple" style='display:none'/>
                          <div class="fileResult2-{{$item->id}} text-center"></div>
                          <div class='row fileResult' id="fileResult2-{{$item->id}}" style='min-height: 20px; margin-top: 20px;'>
                              <?php $item_files = Helper::getJAFAttachFiles($item->id); //print_r($item_files); ?>
                              @foreach($item_files as $file)
                                  @if($file['attachment_type'] == 'supporting')
                                  <div class="image-area">
                                      @if(stripos($file['file_name'],'pdf')!==false)
                                          <img src="{{url('/').'/admin/images/icon_pdf.png'}}" alt="Preview" title="{{$file['file_name']}}">
                                      @else
                                          <img src="{{ $file['filePath'] }}" alt="Preview" title="{{$file['file_name']}}">
                                      @endif
                                      <a class="remove-image" data-id="{{ $file['file_id'] }}" href="javascript:;" style="display: inline;">×</a>
                                      <input type="hidden" name="fileID[]" value="{{ $file['file_id'] }}">
                                  </div>
                                  @endif
                              @endforeach
                          </div>
                      </div>
                      
                    </div>
                    <!-- row close -->
                  @endforeach
                @endif
                 
                 <div class="row" style="padding: 10px 0; margin-top:10px; border:1px solid #ddd;">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Digital Signature </label>
                      <input class="form-control digital_signature error-control" type="file" name="digital_signature" id="digital_signature">
                      <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-digital_signature"></p> 
                      {{-- @if ($errors->has('digital_signature'))
                        <div class="error text-danger">
                            {{ $errors->first('digital_signature') }}
                        </div>
                        @endif --}}
                    </div>
                  </div>
                  @if($candidate->digital_signature!=NULL || $candidate->digital_signature!='')
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="company_logo"></label>
                        <span class="btn btn-link float-right text-dark close_btn">X</span>
                        <img id="preview_ds"  src="{{url('uploads/signatures/')}}/{{$candidate->digital_signature}}" width="200" height="150"/>
                      </div>
                    </div>
                  @else
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="company_logo"></label>
                        <span class="d-none btn btn-link float-right text-dark close_btn">X</span>
                        <img id="preview_ds" width="200" height="150"/>
                      </div>
                    </div>
                  @endif
                </div>
                 <button type="submit" class="btn btn-info mt-3 jaf_submit">Save</button>
                {{-- @endif --}}
               </form>
            </div>
         </div>
      </div>
   </div>
</div>

<div id="myImageModal" class="modal">
  <span class="closeImage">&times;</span>
  <img class="image-modal-content" id="img01">
  <div id="caption"></div>
</div>

<!-- The Modal -->
<div id="myDragModal" class="modal">
    
  <div class="modal-content modal-part1">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h5 class="modal-title">Files- You can re-arrange order of the files by  drag the image.</h5>
          
        </div>
      <div class="modal-body gallery-model">
          <input type="hidden" name="itemId" id="jafImageId">
          <input type="hidden" name="itemType" id="jafImageType">
          <div class="gallery">
           
        </div>
            
      </div>
  </div>
  
</div>
@stack('scripts')

<script type="text/javascript">
    //
  $(document).ready(function() {
      $(".vital_list").select2();

      var isPaused = true;

      $(document).on('click','.clickReorder',function(){ 
        imageId     = $(this).attr('add-imageId');
        imageType = $(this).attr('data-imageType');
        $('#jafImageId').val(imageId);
        $('#jafImageType').val(imageType);
        // alert(imageType);
        $.ajax({
              type:'GET',
              url: "{{url('/candidates/jaf/rearrange')}}",
              data: {'imageId':imageId,'imageType':imageType},        
              success: function (response) {        
              console.log(response);

              $('.gallery').html(response);
              $('#myDragModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
              // if (response.status=='ok') {            
                
                
              // } else {

              //    alert('No data found');

              // }
              $("ul.reorder-photos-list").sortable({   
                tolerance: 'pointer',
                update: function( event, ui ) {
                    updateOrder();
                }
            });  
              // $('.reorder_link').html('save reordering');
              // $('.reorder_link').attr("id","saveReorder");
              // $('#reorderHelper').slideDown('slow');
              $('.image_link').attr("href","javascript:void(0);");
              $('.image_link').css("cursor","move");
              // update: function( event, ui ) {
              //   updateOrder();
              // }
             
          },
          error: function (xhr, textStatus, errorThrown) {
              alert("Error: " + errorThrown);
          }
        });
        // $('#myDragModal').modal();

      });
      //image reordering
      // $("ul.reorder-photos-list").sortable({
      //    tolerance: 'pointer' 
      //    update: function( event, ui ) {
      //         updateOrder();
      //     }
      //   });
      //
      function updateOrder() {    
        //  console.log('good going');
        
        imageIds= $('#jafImageId').val();
        jafImageTypes=$('#jafImageType').val();
          var item_order = new Array();
          $('ul.reorder-photos-list li').each(function() {
            // console.log('good going');
              item_order.push($(this).attr("id"));
          });
          // var order_string =item_order;
          $.ajax({
              type: "GET",
              url: "{{url('/candidates/jaf/rearrange/save')}}",
              data: { "order_number":item_order,'imageIds':imageIds,'jafImageTypes':jafImageTypes},
              cache: false,
              success: function(data){ 
                if (data.fail == false) {
                  console.log(data.attachment_type);
                if ( data.attachment_type=='main') {
                    $("#fileResult1"+"-"+data.jaf_id).html("");
                    var count = Object.keys(data.data).length;
                    // console.log(count);
                    for(var i=0; i < count; i++)
                    {
                    
                          // $("#"+fileResult+"-"+dynamicID).prepend("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['filePrev']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.data[i]['file_id']+"'></div>");
                          $("#fileResult1"+"-"+data.jaf_id).append("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['fileIcon']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.jaf_id+'-'+data.data[i]['file_id']+"'></div>");
                    
                    }
                }
                else
                {
                  $("#fileResult2"+"-"+data.jaf_id).html("");
                    var count = Object.keys(data.data).length;
                    // console.log(count);
                    for(var i=0; i < count; i++)
                    {
                    
                          // $("#"+fileResult+"-"+dynamicID).prepend("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['filePrev']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.data[i]['file_id']+"'></div>");
                          $("#fileResult2"+"-"+data.jaf_id).append("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['fileIcon']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.jaf_id+'-'+data.data[i]['file_id']+"'></div>");
                    
                    }
                }
                
                }
                
              }
          });
      }
         
          var curNum ='';
          var fileResult='fileResult1';
          var type = 'main';
          var number = '1';
          $(document).on('click','.clickSelectFile',function(){ 
            curNum     = $(this).attr('add-id');
            fileResult = $(this).attr('data-result');
            type = $(this).attr('data-type');
            number = $(this).attr('data-number');
            //  alert(fileResult);
            $(this).next('input[type="file"]').trigger('click');
          });
          //
          $(document).on('change','.fileupload',function(e){        
            uploadFile(curNum,fileResult,type,number);
          });

          //remove file
          $(document).on('click','.remove-image',function(){ 

              // var r = confirm("Are you want to remove?");
              // if (r == true) {
              // $('#fileupload-'+curNum).val("");
              // var current = $(this);
              // var file_id = $(this).attr('data-id');
              // //
              // var fd = new FormData();
              // fd.append('file_id',file_id);
              // fd.append('_token', '{{csrf_token()}}');
              // //
              // $.ajax({
              //       type: 'POST',
              //       url: "{{ url('/jaf/remove/file') }}",
              //       data: fd,
              //       processData: false,
              //       contentType: false,
              //       success: function(data) {
              //          console.log(data);
              //          if (data.fail == false) {
              //          //reset data
              //          $('.fileupload').val("");
              //          //append result
              //          $(current).parent('.image-area').detach();
              //          } else {
                      
              //          console.log("file error!");
                      
              //          }
              //       },
              //       error: function(error) {
              //          console.log(error);
              //          // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
              //       }
              // });

              // return false;

              // }
              var current = $(this);
              var file_id = $(this).attr('data-id');
              swal({
                    // icon: "warning",
                    type: "warning",
                    title: "Are You Want to Remove?",
                    text: "",
                    dangerMode: true,
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "YES",
                    cancelButtonText: "CANCEL",
                    closeOnConfirm: false,
                    closeOnCancel: false
                    },
                    function(e){
                      if(e==true)
                      {
                          var fd = new FormData();

                          fd.append('file_id',file_id);
                          fd.append('_token', '{{csrf_token()}}');

                          $.ajax({
                                type: 'POST',
                                url: "{{ url('/jaf/remove/file') }}",
                                data: fd,
                                processData: false,
                                contentType: false,
                                success: function(data) {
                                  // console.log(data);
                                  if (data.fail == false) {
                                  //reset data
                                  $('.fileupload').val("");
                                  //append result
                                  $(current).parent('.image-area').detach();
                                  } else {
                                  
                                  console.log("file error!");
                                  
                                  }
                                },
                                error: function(error) {
                                  console.log(error);
                                  // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
                                }
                          });
                          swal.close();
                      }
                      else
                      {
                          swal.close();
                      }
                    }
              );

          });


          //Fields on  Readonly mode before check insuff button.

             $(document).on('change', '.check_insuff', function (event) {

                  var form= $(this);
                  var id     = $(this).attr('data-insuff_id');
                  if (this.checked) {
                     
                    $('#insufficiency-notes-'+id).prop('readonly', false);
                     $('#attachments-'+id).prop('readonly', false);
                     $('#attachments-'+id).removeClass('disabled-link');
                  }
                 else{
                  $('#insufficiency-notes-'+id).prop('readonly', true);
                     $('#attachments-'+id).prop('readonly', true);
                     $('#attachments-'+id).addClass('disabled-link');
                 }

             });

             $(".vital_list").on("select2:select", function (e) { 
              var data = e.params.data.id;
              
              
              if(data=="all"){
                  $(".vital_list > option").prop("selected","selected");
                  $(".vital_list").trigger("change");
                }
              });

          $(document).on('submit', 'form#jafFrm', function (event) {
            isPaused = false; 
            $("#overlay").fadeIn(300);　
            event.preventDefault();
            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var btn = $(this);
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            $('.error-container').html('');
            $('.form-control').removeClass('border-danger');
            $('.jaf_submit').attr('disabled',true);
            $('.error-control').attr('readonly',true);
            $('.error-control').addClass('disabled-link');
            if ($('.jaf_submit').html() !== loadingText) {
              $('.jaf_submit').html(loadingText);
            }
            $.ajax({
                  type: form.attr('method'),
                  url: url,
                  data: data,
                  cache: false,
                  contentType: false,
                  processData: false,
                  success: function (data) {
                      console.log(data);
                      window.setTimeout(function(){
                        $('.jaf_submit').attr('disabled',false);
                        $('.jaf_submit').html('Save'); 
                        $('.error-control').attr('readonly',false);
                        $('.error-control').removeClass('disabled-link');
                      },2000);
                      $('.error-container').html('');
                      if (data.fail && data.error_type == 'validation') {
                        isPaused = true; 
                        //$("#overlay").fadeOut(300);
                        for (control in data.errors) {
                        // $('textarea[comments=' + control + ']').addClass('is-invalid');
                        $('.'+control).addClass('border-danger');
                        $('#error-' + control).html(data.errors[control]);

                          $('input[name='+control+']').focus();
                          $('select[name='+control+']').focus();
                          $('textarea[name='+control+']').focus();

                        }
                      } 
                      //  if (data.fail && data.error == 'yes') {
                        
                      //      $('#error-all').html(data.message);
                      //  }
                      if (data.fail == false) {
                        // $('#send_otp').modal('hide');
                        // alert(data.id);
                        if(data.success){
                          
                            toastr.success("JAF has been filled Successfully");
                            // redirect to google after 5 seconds
                            var candidate_id=data.candidate_id;
                            window.setTimeout(function() {
                              window.location="{{url('/')}}"+'/candidates/jaf-info/'+candidate_id;    
                            }, 2000);
                           
                          // setInterval();
                        // window.location.href='{{ Config::get('app.admin_url')}}/aadharchecks/show';
                        //  location.reload();
                        }
                        else
                        {
                            toastr.error("Something Went Wrong!!");
                        } 
                      }
                  },
                  error: function (xhr, textStatus, errorThrown) {
                      
                      // alert("Error: " + errorThrown);

                  }
            });
            return false;
                     
          });

          $(document).on('click','.image-area > img',function(){ 
            
            var img_src =  $(this).attr("src");
            
             $('.image-modal-content').attr('src',img_src);
             $('#myImageModal').modal();
            
          });
          $(document).on('click','.closeImage',function(){ 
            $('#myImageModal').modal('hide');
            // $('#myImageModal').css("display", "none");
          });
          

   


      function uploadFile(dynamicID,fileResult,type,number){

        $("#fileUploadProcess").html("<img src='{{asset('images/process-horizontal.gif')}}' >"); 
        $('.bcd_loading').css('display', 'block');
        var fd = new FormData();

        var jaf_id=$('#jaf_id').val();
      
        // alert(fd);
        var ins = document.getElementById("file"+number+"-"+dynamicID).files.length;
        // alert(ins);
        for (var x = 0; x < ins; x++) {
            fd.append("files[]", document.getElementById("file"+number+"-"+dynamicID).files[x]);
        }

        fd.append('candidate_id',"{{ base64_encode($candidate->id) }}");
        fd.append('business_id',"$candidate->business_id")
        fd.append('jaf_id',dynamicID);
        fd.append('type',type);
        fd.append('_token', '{{csrf_token()}}');
        //
        $.ajax({
              type: 'POST',
              url: "{{ url('/jaf/upload/file') }}",
              data: fd,
              processData: false,
              contentType: false,
              success: function(data) {
                    window.setTimeout(function(){
                      $('.bcd_loading').css('display', 'none');
                        },2000);
                console.log(data);
                if (data.fail == false) {
                
                //reset data
                $('.fileupload').val("");
                $("#fileUploadProcess").html("");
                //append result

                var count = Object.keys(data.data).length;

                  for(var i=0; i < count; i++)
                  {
                    if(data.data[i]['file_type']=='pdf')
                    {
                        $.each(data.data[i]['file_id'],function(key,value){
                              // $("#"+fileResult+"-"+dynamicID).prepend("<div class='image-area' data-id='"+value+"'><img src='"+data.data[i]['filePrev'][key]+"'  alt='Preview' title='"+data.data[i]['file_name'][key]+"'><a class='remove-image' href='javascript:;' data-id='"+value+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+value+"'></div>");
                              $("#"+fileResult+"-"+dynamicID).append("<div class='image-area' data-id='"+value+"'><img src='"+data.data[i]['filePrev'][key]+"'  alt='Preview' title='"+data.data[i]['file_name'][key]+"'><a class='remove-image' href='javascript:;' data-id='"+value+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+value+"'></div>");
                        });
                    }
                    else
                    {
                        // $("#"+fileResult+"-"+dynamicID).prepend("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['filePrev']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.data[i]['file_id']+"'></div>");
                        $("#"+fileResult+"-"+dynamicID).append("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['filePrev']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.data[i]['file_id']+"'></div>");
                    }
                  }
                  $("."+fileResult+"-"+dynamicID).html("");
                // $.each(data.data, function(key, value) {
                //     $("#"+fileResult+"-"+dynamicID).append("<div class='image-area'><img src='"+value.filePrev+"'  alt='Preview'><a class='remove-image' data-id='"+value.file_id+"' href='javascript:;' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+value.file_id+"'></div>");
                // });
                    
                } else {
                  $("#fileUploadProcess").html("");
                  // alert("Please upload valid file! allowed file type, Image JPG, PNG, PDF etc. ");
                  swal({
                    title: "Oh no!",
                    text: 'Please upload valid file! allowed file type, Image JPG, PNG, PDF etc.',
                    type: 'error',
                    buttons: true,
                    dangerMode: true,
                    confirmButtonColor:'#003473'
                });
                $("."+fileResult+"-"+dynamicID).html("");
                  console.log("file error!");
                  
                }
              },
              error: function(data) {
                  console.log(data);
                  // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
              }
          });
        
        return false;
      }

  
      function autoSave()  
      {  
              var form = $(this);
              // var data = new FormData($(this));
              // alert(data);
              var formData = document.getElementById('jafFrm');
              var data = new FormData(formData);
              data.append('type','formtype');
            // var url = form.attr("action");
            // if(post_title != '' && post_description != '')  
            // {  
                  $.ajax({  
                      url:"{{ url('/candidates/jafFormSave/') }}",  
                      type:"POST",  
                      data:data,  
                      cache: false,
                      contentType: false,
                      processData: false,  
                      success:function(response)  
                      { 
                        if(response.success==true  && response.status=='hold')
                        {
                            var candidate_id = response.candidate_id;
                            var hold = response.hold_by;
                            // alert(hold);
                            toastr.success("JAF On Hold by "+hold);
                            window.setTimeout(function(){
                              window.location="{{url('/')}}"+'/candidates/';
                            },2000);
                        } 
                        if(response.success==true && response.status=='filled') 
                        {  
                          // alert("hi");
                          var candidate_id = response.candidate_id;
                          var filled = response.filled_by;
                          // alert(filled);
                          toastr.success("JAF is Filled by "+filled);
                          window.setTimeout(function(){
                            window.location="{{url('/')}}"+'/candidates/jaf-info/'+candidate_id;
                          },2000);
                          
                        }     
                      }  
                  });  
            // }            
      }  
      
      
        setInterval(function(){   
          // console.log('setinterval me');
          if(isPaused) {
            // console.log('autosave me');
            autoSave();  
          } 
        }, 10000);  
 
   

    $('#digital_signature').change(function(){
            
      $('#preview_ds').attr('src','')
      let reader = new FileReader();
      reader.onload = (e) => { 
          $('.close_btn').removeClass('d-none');
          $('#preview_ds').attr('src', e.target.result); 
      }
      reader.readAsDataURL(this.files[0]); 
      
    });
    $(document).on('click','.close_btn',function(){
          $('#preview_ds').removeAttr('src'); 
          $(this).addClass('d-none');
          $(this).parents().eq(2).find('#digital_signature').val("");
    });

    var edu_path = "{{ url('/candidates/fake_gen_edu_list/autocomplete') }}";
    $('.edu_auto_cls').typeahead({
          source:  function (search, process) {
          return $.get(edu_path, { search: search }, function (data) {
          // alert(data);
                return process(data);
              });
          }
    });

    var emp_path = "{{ url('/candidates/fake_gen_emp_list/autocomplete') }}";
    $('.emp_auto_cls').typeahead({
          source:  function (search, process) {
          return $.get(emp_path, { search: search }, function (data) {
          // alert(data);
                return process(data);
              });
          }
    });

  });
</script>
@endsection