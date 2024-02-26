@extends('layouts.admin')
@section('content')
<style>
    .disabled-link{
       pointer-events: none;
    }
    .disabled-link-1{
       pointer-events: none;
    }
    .sweet-alert button.cancel {
        background: #DD6B55 !important;
    }
    .remove-image
    {
        padding: 0px 3px 0px;
    }

    .image-area img{
        height: 100px;
        width: 100px;
        padding: 8px;
    }

    .image-area{
        width: 90px;
    }

    .remove-image:hover
    {
        padding: 0px 3px 0px;
    }
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
  background: white;
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
.calender{
    margin-left:-10px;
}
.select2-container--default .select2-selection--multiple .select2-selection__rendered{
   height: auto;
}
 </style>
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
            <a href="{{ url('/reports') }}">Reports</a>
            </li>
            <li>Generate Report</li>
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
            <div class="col-md-6">
               <h4 class="card-title mb-1">Generate Report: <b> {{ $candidate->name }} ({{Helper::candidate_reference_id($candidate->id)}}) </b> </h4>
               <p>Add your comment and supportings. (Remarks: Checked = Yes, Left Blank = -)</p>
            </div>
            <div class="col-md-6">
                <p class="text-danger" style="font-size: 12px;">Note :- Please ensure about the data verified for each check's data. because it will be count in billing items, if "Data Verified" is check marked then it will count in Billing-Invoice.</p>
            </div>
            <div class="col-md-12">
               <form class="mt-2" method="post" action="{{ url('/reports/output-process/save') }}" id="report_form">
                @csrf
                <!-- candidate info -->
                <input type="hidden" name="report_id" value="{{ base64_encode($report_id) }}">
                <div class="row">
                    <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                            <label>Name: <strong>{{ $candidate->name }} </strong></label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Phone: <strong>+{{$candidate->phone_code}}-{{ $candidate->phone }}</strong></label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                            <label>Email: <strong>{{ $candidate->email!=NULL ? $candidate->email : 'N/A' }}</strong> </label>
                            </div>
                        </div>
                    </div>
                    <p class="pb-border"></p>
                    @php
                        $job_item = Helper::get_job_items($candidate->id,$candidate->business_id);
                    @endphp
                    <div class="row">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="card-title pt-2">SLA Details</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                    <label>SLA Name: <strong>{{ $job_item->sla_title}} </strong></label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                    <label>Internal TAT: <strong>{{ $job_item->tat}} @if($job_item->tat > 1) days @else day @endif </strong></label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Client TAT: <strong>{{ $job_item->client_tat}} @if($job_item->client_tat > 1) days @else day @endif</strong></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                    <label>Price Type: <strong>{{ ucfirst($job_item->price_type.'-'.'Wise') }} </strong></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="card-title pt-2">Case Details</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Case Initiated: <strong>{{date('d-M-Y h:i A',strtotime($candidate->created_at))}}</strong></label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>JAF Filled: <strong>{{date('d-M-Y h:i A',strtotime($job_item->filled_at))}}</strong></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    </div>
                </div>
                
                <!-- service item -->
                @if( count($report_items) >0  )
                @foreach($report_items as $item)
                <!--  -->
                <?php
                    //get sale item count
                    $j=1;
                    $num ="";                    
                ?>
                  <div class="row" style="padding: 10px 0; margin-top:10px; border:1px solid #ddd;">
                     <div class="col-md-6">
                        <h3 class=" mb-2 mt-2">Verification - {{$item->service_name.' -'.$item->service_item_number}}</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <p>Provide the inputs and Comments</p>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                   <div class="form-check">
                                      <label class="check-inline">
                                    @if($item->is_data_verified=='1')
                                        <button type="button" data-id="{{ $item->id }}" @if ($item->is_data_verified=='1') disabled @endif class="btn btn-success btn-sm verified_data verifiyed">Data Verified </button>
                                    @elseif($item->is_data_verified=='0')
                                        <button type="button" data-id="{{ $item->id }}" data-v="0" class="btn btn-info btn-sm verified_data unverifiyed">Data Verified ?</button>
                                        <input type="hidden" data-v="{{ $item->id }}" name="verified-input-checkbox-{{ $item->id}}" value="0" id="verified-input-checkbox-{{ $item->id}}" class="form-check-input verified_data">
                                    @endif 
                                         {{-- <input type="checkbox" data-id="{{ $item->id }}" name="verified-input-checkbox-{{ $item->id}}" class="form-check-input verified_data" @if ($item->is_data_verified=='1') checked  disabled @endif><span style="font-size: 14px;">Data Verified ?</span> --}}
                                      </label>
                                   </div>
                                </div>
                             </div>
                        </div>
                        <!--  -->
                        <?php 
                            $input_item_data = $item->jaf_data;
                            $reference_item_data = $item->reference_form_data;
                            $serviceVitalsName = Helper::serviceVitalsName($item->service_id);
                            
                            $input_item_data_array =  json_decode($input_item_data, true); 
                            $i=0;
                            $k=0;
                            $l=0;

                            
                        ?>
                        @foreach($input_item_data_array as $key => $input)
                            {{-- @php
                                dd($input);
                            @endphp --}}
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <?php 
                                            $key_val = array_keys($input);
                                           // dd($key_val); 
                                            $input_val = array_values($input); 

                                            if (is_array($input_val[0])) {
                                                $string_input = implode(",", $input_val[0]);
                                                $vital_selected = explode(",", $string_input);
                                            } else {
                                                // Proceed with the original operation
                                                $vital_selected = explode(",", $input_val[0]);
                                            }

                                            $date_calss='';
                                            $show_calender='';

                                            if(stripos($key_val[0],'Employee Tenure')!==false)
                                            {
                                                $date_calss = 'commonDatepicker';
                                                $show_calender = '<span class="show-calender-icon calender"><i class="fas fa-calendar-alt"></i></span>';
                                            }

                                            if(stripos($key_val[0],'Date of Joining (Employee Tenure)')!==false)
                                            {
                                                $date_calss = 'commonDatepicker';
                                                $show_calender = '<span class="show-calender-icon calender"><i class="fas fa-calendar-alt"></i></span>';
                                            }

                                            if(stripos($key_val[0],'Date of Relieving (Employee Tenure)')!==false)
                                            {
                                                $date_calss = 'commonDatepicker';
                                                $show_calender = '<span class="show-calender-icon calender"><i class="fas fa-calendar-alt"></i></span>';
                                            }

                                            $check_input=Helper::check_item_input_name($item->service_id,$candidate->business_id,$key_val[0]);

                                            
                                        ?>
                                         
                                        @if($item->service_id==17)
                                            @if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                                                <label>  {{ $key_val[0]}} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
                                                <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                <select class="form-control service-input-value-{{$item->id.'-'.$i}} reference_type error-control" name="service-input-value-{{ $item->id.'-'.$i }}" data-id="{{base64_encode($item->id)}}" data-report="{{$item->id}}">
                                                    <option value="">--Select--</option>
                                                    <option @if(stripos($input_val[0],'personal')!==false) selected @endif value="personal">Personal</option>
                                                    <option @if(stripos($input_val[0],'professional')!==false) selected @endif value="professional">Professional</option>
                                                </select>
                                            @else
                                                <label>  {{ $key_val[0]}} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
                                                <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                <input class="form-control error-control {{$date_calss}}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
                                            @endif
                                        @elseif(stripos($item->type_name,'drug_test_5')!==false || stripos($item->type_name,'drug_test_6')!==false || stripos($item->type_name,'drug_test_7')!==false || stripos($item->type_name,'drug_test_8')!==false || stripos($item->type_name,'drug_test_9')!==false || stripos($item->type_name,'drug_test_10')!==false)
                                            @if (stripos($key_val[0],'Test Name')!==false)
                                                <label>  {{ $key_val[0]}} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label><br>
                                                <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                <input class="form-control error-control" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
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
                                                <label>  {{ $key_val[0]}} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
                                                <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                <select class="form-control service-input-value-{{$item->id.'-'.$i}}" name="service-input-value-{{ $item->id.'-'.$i }}" >
                                                    <option value="">--Select--</option>
                                                    <option @if(stripos($input_val[0],'positive')!==false) selected @endif value="positive">Positive</option>
                                                    <option @if(stripos($input_val[0],'negative')!==false) selected  @endif value="negative">Negative</option>
                                                 </select>     
                                            @else
                                                <label>  {{ $key_val[0]}} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
                                                <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                <input class="form-control error-control {{$date_calss}}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">         
                                            @endif
                                            
                                            @elseif(stripos($key_val[0],'Vital Categoryes')!==false)
                                            <label>  {{ $key_val[0]}} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
                                            <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                            <select class="form-control vital_list service-input-value-{{$item->id.'-'.$i}}" name="service-input-value-{{ $item->id.'-'.$i }}[]" multiple>
                                                <option value="all">-All-</option>
                                                @foreach ($serviceVitalsName as $serviceVitals)
                                                   <option value="{{ $serviceVitals->vital_name }}" {{ in_array($serviceVitals->vital_name,$vital_selected) ? 'selected' : '' }}>{{ $serviceVitals->vital_name }} ({{$serviceVitals->vital_full_name}})</option>
                                                @endforeach
                                             </select>

                                        @else
                                            <label>  {{ $key_val[0]}} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
                                            <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                            @if($key_val[0]=='Date of Joining (Employee Tenure)' || $key_val[0]=='Date of Relieving (Employee Tenure)')
                                            <div class="row">
                                                <div class="col-6">
                                                    <input class="form-control error-control {{$date_calss}}" type="text" name="service-input-value-{{ $item->id.'-'.$i.'-1' }}" value="{{ array_key_exists(2,$input_val) && $input_val[2]=='date' ? $input_val[0] : NULL }}">  
                                                    {!! $show_calender !!}
                                                </div>
                                                <div class="col-6">
                                                    <input class="form-control error-control" type="text" name="service-input-value-{{ $item->id.'-'.$i.'-2' }}" value="{{ array_key_exists(2,$input_val) && $input_val[2]!='date' ? $input_val[0] : NULL }}">
                                                </div>
                                                <div class="col-12">
                                                    <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i.'-1'}}"></p>
                                                    <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i.'-2'}}"></p>
                                                </div>
                                            </div>
                                            @else
                                            <input class="form-control error-control" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">  
                                            {{-- {!! $show_calender !!} --}}
                                            @endif     

                                            {{-- <label>  {{ $key_val[0]}} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
                                            <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                            <input class="form-control error-control {{$date_calss}}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
                                            {!!$show_calender !!} --}}
                                        @endif
                                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{ $item->id.'-'.$i }}"></p>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-3">
                                    <div class="form-group">
                                    <label> Remarks </label>
                                        <div class="form-check error-control">
                                        <label class="form-check-label">
                                            <input type="checkbox" name="remarks-input-checkbox-{{ $item->id.'-'.$i}}"  @if(in_array('remarks', $key_val)) @if($input['remarks']=='Yes') checked @endif @endif class="form-check-input" >
                                        </label>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                            </div>
                            <?php $i++; ?>
                        @endforeach
                        
                        @if($item->service_id==17)
                            <div class="reference_result" id="reference_result-{{$item->id}}">
                                @php
                                    $reference_type = NULL;

                                    if($item->reference_type!=NULL)
                                    {
                                        $reference_type = $item->reference_type;
                                    }
                                    else
                                    {
                                        foreach($input_item_data_array as $input)
                                        {
                                            $key_val = array_keys($input); $input_val = array_values($input);

                                            if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                                            {
                                                $reference_type = $input_val[0];
                                            }
                                        }
                                    }
                                @endphp
                                @if($reference_type!=NULL || $reference_type!='')
                                    <?php 
                                        $reference_service_inputs=Helper::referenceServiceFormInputs($item->service_id,$reference_type);
                                    ?>
                                    @if($reference_item_data!=NULL)
                                        <?php 
                                            $reference_item_data_array=json_decode($reference_item_data,true);
                                        ?>
                                        <div class="row" style="border:1px solid #ddd; padding:10px; margin-bottom:10px;"> 
                                            <h4 class="pt-2 pb-2">{{ ucwords($reference_type) }} Details</h4>
                                            @foreach ($reference_item_data_array as $key => $input)
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                    <?php
                                                        $key_val = array_keys($input); $input_val = array_values($input);
                                                        $check_input=Helper::check_item_input_name($item->service_id,$candidate->business_id,$key_val[0]);
                                                    ?>
                                                    <label>  {{ $key_val[0]}}  @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
                                                    <input type="hidden" name="reference-input-label-{{ $item->id.'-'.$l }}" value="{{ $key_val[0] }}">
                                                    <input class="form-control error-control" type="text" name="reference-input-value-{{ $item->id.'-'.$l }}" value="{{$input_val[0]}}">
                                                    <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-reference-input-value-{{$item->id.'-'.$l}}"></p>
                                                    </div>
                                                </div>
                                                <?php $l++; ?>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="row" style="border:1px solid #ddd; padding:10px; margin-bottom:10px;"> 
                                            <h4 class="pt-2 pb-2">{{ ucwords($reference_type) }} Details</h4>
                                            @foreach($reference_service_inputs as $key => $input)
                                                @php
                                                    $check_input=Helper::check_item_input($input->id,$candidate->business_id);
                                                @endphp
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label> {{ $input->label_name }}  @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
                                                        <input type="hidden" name="reference-input-label-{{ $item->id.'-'.$k }}" value="{{ $input->label_name }}">
                                                        <input class="form-control error-control" type="text" name="reference-input-value-{{ $item->id.'-'.$k }}">
                                                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-reference-input-value-{{$item->id.'-'.$k}}"></p>
                                                    </div>
                                                </div>
                                                <?php $k++; ?>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endif

                        <!-- Additional Address  -->
                        @if ($item->service_name=="Address")
                            @php
                                //Helper to get report_add_page_statuses Data
                                $report_add_page =  Helper::get_report_page($candidate->business_id);
                            @endphp
                            @if ($report_add_page)
                            
                                @if ($report_add_page->status == 'enable')
                                    <div class="row">
                                        <div class="col-sm-12"> 
                                            <h4 class="card-title mb-2 mt-2">Additional Address verification  </h4>
                                        </div>   
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label> Contact Person Name</label>
                                                <input class="form-control error-control" type="text" name="contact_person_name-{{ $item->id }}"  value=""  >
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>  Contact Person Number</label>
                                                <input class="form-control error-control" type="text" name="contact_person_no-{{ $item->id }}"  value=""  >
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Residence Status</label>
                                                <input class="form-control error-control" type="text" name="residence_status-{{ $item->id }}"  value=""  >
                                            </div>
                                        </div><div class="col-sm-6">
                                            <div class="form-group">
                                                <label> Relation with Associate</label>
                                                <input class="form-control error-control" type="text" name="relation_with_associate-{{ $item->id }}"  value=""  >
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>  Locality</label>
                                                <input class="form-control error-control" type="text" name="locality-{{ $item->id }}"  value=""  >
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Mode of Verification</label>
                                                <input class="form-control error-control" type="text" name="verification_mode-{{ $item->id }}"  value=""  >
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>  Remarks</label>
                                                <input class="form-control error-control" type="text" name="additional_remark-{{ $item->id }}"  value=""  >
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label> Verified By</label>
                                                <input class="form-control error-control" type="text" name="Additional_verified_by-{{ $item->id }}"  value=""  >
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Additional Comments <small>(If any)</small></label>
                                                <textarea class="form-control error-control" type="text" name="additional_verification_comments-{{ $item->id }}" ></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!--  -->
                                @endif
                            @endif
                        @endif
                        <!--  -->
                        
                        <!-- comment  -->
                            <div class="row">
                                <div class="col-sm-12"> 
                                    <h4 class="card-title mb-2 mt-2">Approval Inputs  </h4>
                                </div>
                                @if(stripos($item->type_name,'drug_test_5')!==false || stripos($item->type_name,'drug_test_6')!==false || stripos($item->type_name,'drug_test_7')!==false || stripos($item->type_name,'drug_test_8')!==false || stripos($item->type_name,'drug_test_9')!==false || stripos($item->type_name,'drug_test_10')!==false)   
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label> Test Date</label>
                                            <input class="form-control error-control test_date commonDatepicker" type="text" name="test_date-{{ $item->id }}" value="{{ $item->test_date!=NULL ? date('d-m-Y',strtotime($item->test_date)) : NULL }}">
                                        </div>
                                    </div>
                                @endif   
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label> Verified By</label>
                                        <input class="form-control error-control" type="text" name="verified_by-{{ $item->id }}"  value="{{$item->verified_by }}"  >
                                    </div>
                                </div>
                            </div>
                            @php
                                $report_show = Helper::report_show($candidate->business_id,'3');
                            @endphp
                            @if ($report_show==null)
                                <div class="row">
                                
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label> Comments</label>
                                            <textarea class="form-control " type="text" name="comments-{{ $item->id }}" >{{ $item->comments? $item->comments:"The copy of confirmation is attached herewith as Annexure."}}</textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6" style="">
                                        <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3">Annexure Value</span>
                                        </div>
                                            <input type="text" class="form-control error-control" name="annexure_value-{{$item->id}}"  value="{{ $item->annexure_value }}" aria-describedby="basic-addon3">
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Additional Comments</label>
                                        <textarea class="form-control error-control" type="text" name="additional-comments-{{ $item->id }}" >{{ $item->additional_comments }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Approval Status</label>
                                        <select class="form-control error-control" name="approval-status-{{ $item->id }}" >
                                            @foreach($status_list as $status)
                                            <option value="{{ $status->id}}" @if($status->id == $item->approval_status_id) selected @endif > {{ $status->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <!-- Court inpput start -->
                            @if( $item->service_id == 15  )  
                                <div class="row mt-2">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                        <label> <b> Court </b></label>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label> <b>Court Name </b></label>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                        <label> <b>Result</b> </label>
                                        </div>
                                    </div>
                                    <!--  -->
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                        <p>District Court/ Lower Court/ Civil Court & Small Causes</p>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-6" style="padding-left:0px;padding-right:0px">
                                        <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3">District Courts of</span>
                                        </div>
                                            <input type="text" class="form-control error-control" name="district_court_name-{{$item->id}}"  value="{{ $item->district_court_name }}"  aria-describedby="basic-addon3">
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <input type="text" name="district_court_result-{{$item->id}}" class="form-control error-control" value="{{ $item->district_court_result }}" >
                                        </div>
                                    </div>
                                    <!--  -->
                                </div>
                                <!-- row. -->
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                        <p>High Court</p>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-6" style="padding-left:0px;padding-right:0px">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3">High Court of Jurisdiction at</span>
                                        </div>
                                            <input type="text" class="form-control error-control" name="high_court_name-{{$item->id}}" value="{{ $item->high_court_name }}" aria-describedby="basic-addon3">
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <input type="text" name="high_court_result-{{$item->id}}" class="form-control error-control" value="{{ $item->high_court_result }}">
                                        </div>
                                    </div>
                                    <!--  -->
                                </div>
                                <!-- ./row -->
                                <!-- row. -->
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                        <p>Supreme Court</p>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-6" style="padding-left:0px;padding-right:0px">
                                        <div class="form-group">
                                            <input type="text" name="supreme_court_name-{{$item->id}}" class="form-control error-control"  value="Supreme Court of India, New Delhi" readonly >
                                        </div>
                                    </div>
                                    <!--  --> 
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <input type="text" name="supreme_court_result-{{$item->id}}" class="form-control error-control" value="{{ $item->supreme_court_result }}" >
                                        </div>
                                    </div>
                                    <!--  -->
                                </div>
                                <!-- ./row -->
                            @endif
                        <!-- ./ end court  -->
                        <!--  -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                    <label class="checkbox-inline error-control">
                                        <input type="checkbox" name="report-output-{{ $item->id }}" @if($item->is_report_output == '1')  checked @endif >  Include in Report Output (if yes: Check Mark)
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                     </div>
                     <!-- attachment  -->
                     <div class="col-md-6">
                        <p>Attachments: <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i></p>
                        <button class='btn btn-info clickReorder reorder_link' type="button" add-imageId="{{$item->id}}" data-imageType='main' style=' float:right;'><i class="fas fa-sync"></i> Re-Arrange </button>
                        <a class='btn-link clickSelectFile error-control' add-id="{{$item->id}}" data-number='1' data-result='fileResult1' data-type='main' style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a>
                        <input type='file' class='fileupload' name="file-{{$item->id}}[]" id='file1-{{$item->id}}' multiple="multiple" style='display:none'/>
                        <div class="fileResult1-{{$item->id}} text-center"></div>
                        <div class='row fileResult' id="fileResult1-{{$item->id}}" style='min-height: 20px; margin-top: 20px;'>
                        <?php $item_files = Helper::getReportAttachFiles($item->id,'main'); //print_r($item_files); ?>
                        @foreach($item_files as $file)
                            @if($file['attachment_type'] == 'main')
                            <div class="image-area">
                                @if(stripos($file['file_name'],'pdf')!==false)
                                    <img src="{{url('/').'/admin/images/icon_pdf.png'}}" alt="Preview" title="{{$file['file_name']}}">
                                @elseif(stripos($file['file_name'],'zip')!==false)
                                    <a href="{{ url('/vitals-report/zip',['id'=>base64_encode($item->id)]) }}">
                                       <img src="{{url('/').'/admin/images/zip.png'}}" title="{{$file['file_name']}}">
                                    </a>
                                @else
                                    <img src="{{ $file['fileIcon'] }}" alt="Preview" title="{{$file['file_name']}}">
                                @endif
                                <a class="remove-image" data-id="{{ $file['file_id'] }}" href="javascript:;" style="display: inline;">×</a>
                                <input type="hidden" name="fileID[]" value="{{ $file['file_id'] }}">
                            </div>
                            @endif
                        @endforeach
                        </div>
                        <p class="mt-2" style="margin-bottom:1px">Add Supportings: <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i></p>
                        <button class='btn btn-info clickReorder reorder_link' type="button" add-imageId="{{$item->id}}" data-imageType='supporting' style=' float:right;'><i class="fas fa-sync"></i> Re-Arrange </button>
                        <a class='btn-link clickSelectFile error-control' add-id="{{$item->id}}" data-number='2' data-result='fileResult2' data-type='supporting' style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a>
                        <input type='file' class='fileupload' name="file-{{$item->id}}[]" id='file2-{{$item->id}}' multiple="multiple" style='display:none'/>
                        <div class="fileResult2-{{$item->id}} text-center"></div>
                        <div class='row fileResult' id="fileResult2-{{$item->id}}" style='min-height: 20px; margin-top: 20px;'>
                            <?php $item_files = Helper::getReportAttachFiles($item->id,'supporting'); //print_r($item_files); ?>
                            @foreach($item_files as $file)
                                @if($file['attachment_type'] == 'supporting')
                                <div class="image-area">
                                    @if(stripos($file['file_name'],'pdf')!==false)
                                        <img src="{{url('/').'/admin/images/icon_pdf.png'}}" alt="Preview" title="{{$file['file_name']}}">
                                    @else
                                        <img src="{{ $file['fileIcon'] }}" alt="Preview" title="{{$file['file_name']}}">
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
                 <div class="row">
                    <div class="col-4">
                        <div class="form-group mt-3">
                            {{-- <div class="form-check">
                                <label class="check-inline">
                                <input type="checkbox" name="check_green" class="form-check-input check_green" ><span style="font-size: 14px;">Want to Mark the Color Code as Green Anyway</span>
                                </label>
                            </div> --}}

                            <label>Mark the Color Code As</label>
                            <select class="form-control manual_check" name="manual_check">
                                <option>None</option>
                                <option value="1">Green</option>
                                <option value="2">Grey (Stopped)</option>
                            </select>
                        </div>
                    </div>
                 </div>
                 <div class="text-center mt-1">
                  <button type="submit" class="btn btn-success report_submit">Save</button>
                 </div>
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
   $(".vital_list").select2();

   $(document).ready(function() {
         var isPaused = true;
        $(document).on('click','.clickReorder',function(){ 
            imageId     = $(this).attr('add-imageId');
            imageType = $(this).attr('data-imageType');
            $('#jafImageId').val(imageId);
            $('#jafImageType').val(imageType);
            // alert(imageType);
            $.ajax({
                type:'GET',
                url: "{{url('/report/image/rearrange')}}",
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
        //   console.log(imageIds);
        var item_order = new Array();
        $('ul.reorder-photos-list li').each(function() {
          // console.log('good going');
            item_order.push($(this).attr("id"));
        });
        // var order_string =item_order;
        $.ajax({
            type: "GET",
            url: "{{url('/report/image/rearrange/save')}}",
            data: { "order_number":item_order,'imageIds':imageIds,'jafImageTypes':jafImageTypes},
            cache: false,
            success: function(data){ 
                if (data.fail == false) {
                    console.log(data.attachment_type);
                    if ( data.attachment_type=='main') {
                        $("#fileResult1"+"-"+data.report_item_id).html("");
                        var count = Object.keys(data.data).length;
                        // console.log(count);
                        for(var i=0; i < count; i++)
                        {
                        
                            // $("#"+fileResult+"-"+dynamicID).prepend("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['filePrev']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.data[i]['file_id']+"'></div>");
                            $("#fileResult1"+"-"+data.report_item_id).append("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['fileIcon']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.report_item_id+'-'+data.data[i]['file_id']+"'></div>");
                        
                        }
                    }
                    else
                    {
                        $("#fileResult2"+"-"+data.report_item_id).html("");
                        var count = Object.keys(data.data).length;
                        // console.log(count);
                        for(var i=0; i < count; i++)
                        {
                        
                            // $("#"+fileResult+"-"+dynamicID).prepend("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['filePrev']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.data[i]['file_id']+"'></div>");
                            $("#fileResult2"+"-"+data.report_item_id).append("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['fileIcon']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.report_item_id+'-'+data.data[i]['file_id']+"'></div>");
                        
                        }
                    }
                }
            }
        });
    }

        $(document).on('click','.image-area > img',function(){ 
            
            var img_src =  $(this).attr("src");
            
             $('.image-modal-content').attr('src',img_src);
             $('#myImageModal').modal();
            
          });
          $(document).on('click','.closeImage',function(){ 
            $('#myImageModal').modal('hide');
            // $('#myImageModal').css("display", "none");
          });
            var curNum ='';
            var fileResult='fileResult2';
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
            //     $('#fileupload-'+curNum).val("");
            //     var current = $(this);
            //     var file_id = $(this).attr('data-id');
            //     //
            //     var fd = new FormData();

            //     fd.append('file_id',file_id);
            //     fd.append('_token', '{{csrf_token()}}');
            //     //
            //     $.ajax({
            //         type: 'POST',
            //         url: "{{ url('/reports/remove/file') }}",
            //         data: fd,
            //         processData: false,
            //         contentType: false,
            //         success: function(data) {
            //             console.log(data);
            //             if (data.fail == false) {
            //             //reset data
            //             $('.fileupload').val("");
            //             //append result
            //             $(current).parent('.image-area').detach();
            //             } else {
                        
            //             console.log("file error!");
                        
            //             }
            //         },
            //         error: function(error) {
            //             console.log(error);
            //             // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
            //         }
            //     });

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
               confirmButtonColor: "#007358",
               confirmButtonText: "YES",
               cancelButtonText: "CANCEL",
               closeOnConfirm: false,
               closeOnCancel: false
               },
               function(e){
                  if(e==true)
                  {
                    $('#fileupload-'+curNum).val("");
                        
                        //
                        var fd = new FormData();

                        fd.append('file_id',file_id);
                        fd.append('_token', '{{csrf_token()}}');
                        //
                        $.ajax({
                            type: 'POST',
                            url: "{{ url('/reports/remove/file') }}",
                            data: fd,
                            processData: false,
                            contentType: false,
                            success: function(data) {
                                console.log(data);
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

        $(document).on('change','.reference_type',function(){
                var _this=$(this);
                var id = _this.attr('data-id');
                var report_id = _this.attr('data-report');
                var type = _this.val();
                if(type!='')
                {
                    $.ajax({
                            type:'POST',
                            url: "{{route('/report/reference_form')}}",
                            data: {"_token": "{{ csrf_token() }}","id":id,"type":type},        
                            success: function (response) {        
                            // console.log(response);

                            $('#reference_result-'+report_id).html(response);
                        },
                        error: function (data) {
                            // alert("Error: " + errorThrown);
                        }
                    });
                }
                else
                {

                    swal({
                        title: "Please Select The Reference Type !!",
                        text: '',
                        type: 'warning',
                        buttons: true,
                        dangerMode: true,
                        confirmButtonColor:'#003473'
                    });

                    $('#reference_result-'+report_id).html('');

                    // _this.attr('selectedIndex', '-1');
                }
        });

        $(".vital_list").on("select2:select", function (e) { 
              var data = e.params.data.id;
            //   alert(data);
   
            if(data=="all"){
               $(".vital_list > option").prop("selected","selected");
               $(".vital_list").trigger("change");
               }
        });

        $(document).on('submit','form#report_form',function (event) {
            event.preventDefault();
            //clearing the error msg
            $('p.error-container').html("");

            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            $('.report_submit').attr('disabled',true);
            // $('.form-control').attr('readonly',true);
            // $('.form-control').addClass('disabled-link');
            $('.error-control').attr('readonly',true);
            $('.error-control').addClass('disabled-link');
            if ($('.report_submit').html() !== loadingText) {
                    $('.report_submit').html(loadingText);
            }
            $.ajax({
                type: form.attr('method'),
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,      
                success: function (response) {
                    window.setTimeout(function(){
                        $('.report_submit').attr('disabled',false);
                        // $('.form-control').attr('readonly',false);
                        // $('.form-control').removeClass('disabled-link');
                        $('.error-control').attr('readonly',false);
                        $('.error-control').removeClass('disabled-link');
                        $('.report_submit').html('Save');
                    },2000);
                    console.log(response);
                    if(response.success==true) {          
                        // var case_id = response.case_id;
                        //notify
                        toastr.success("Report Has Been Generated Successfully");
                        // redirect to google after 5 seconds
                        window.setTimeout(function() {
                            window.location = "{{ url('/')}}"+"/reports/";
                        //   window.location.reload();
                        }, 2000);
                    
                    }
                    //show the form validates error
                    if(response.success==false ) {  
                        var i = 0;                            
                        for (control in response.errors) {   
                            $('#error-' + control).html(response.errors[control]);
                            if(i==0)
                            {
                            $('select[name='+control+']').focus();
                            $('input[name='+control+']').focus(); 
                            $('textarea[name='+control+']').focus();
                            }
                            i++;
                        }
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
            });
            return false;
        });
 
        function autoSave()  
        {  
            var form = $(this);
            // var data = new FormData($(this));
            // alert(data);
            var formData = document.getElementById('report_form');
            var data = new FormData(formData);
            data.append('type','formtype');
                // var url = form.attr("action");
                // if(post_title != '' && post_description != '')  
                // {  
            $.ajax({  
                url:"{{ url('/reports/output-process/save') }}",
                type:"POST",
                data:data,
                cache: false,
                contentType: false,
                processData: false,
                success:function(response)
                { 
                    // if(response.success==true  && response.status=='hold')
                    // {
                    //     var candidate_id = response.candidate_id;
                    //     var hold = response.hold_by;
                    //     // alert(hold);
                    //     toastr.success("JAF On Hold by "+hold);
                    //     window.setTimeout(function(){
                    //         window.location="{{url('/')}}"+'/candidates/';
                    //     },2000);
                    // } 
                    if(response.success==true && response.custom=='yes') {
                        // var case_id = response.case_id;
                        //notify
                        toastr.success("Report Has Been Generated Successfully");
                        // redirect to google after 5 seconds
                        window.setTimeout(function() {
                            window.location = "{{ url('/')}}"+"/reports/";
                        //   window.location.reload();
                        }, 2000);
                    
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

        

    $(document).on('click','.verified_data',function (event) {
         var current_data = $(this);
         var check_id = $(this).attr('data-id');
        // var status = $(this).prop('checked');
         var status   =    $(this).attr('data-v');
         var r =swal({
                     title: "Are you sure?",
                     text: "While confirming this status, please make sure about Verification data or attachment submitted!",
                     type: "warning",
                     dangerMode: true,
                     showCancelButton: true,
                     confirmButtonColor: "#007358",
                     confirmButtonText: "YES",
                     cancelButtonText: "CANCEL",
                     closeOnConfirm: false,
                     closeOnCancel: false
                     },
                     function(e){
                        //Use the "Strict Equality Comparison" to accept the user's input "false" as string)
                        // if check the checkox
                        if(e==true){
                           if(status == 0){
                                current_data.attr('data-v', '1');
                                current_data.removeClass('btn-info');
                                current_data.addClass('btn-success');
                                current_data.text('Data Verified');
                                $('#verified-input-checkbox-'+check_id).val('1'); 
                            
                                 swal.close();
                           // console.log("Do here everything you want");
                           } else {
                                current_data.attr('data-v', '0');
                                current_data.removeClass('btn-success');
                                current_data.addClass('btn-info');
                                current_data.text('Data Verified ?');
                                $('#verified-input-checkbox-'+check_id).val('0');   
                                
                                swal.close();
                                //toastr.error("Before Verifying the Data, Please Clear the Insufficiency First !!");
                              // swal("Oh no...");
                              // console.log("The user says: ",e);
                           }
                           
                        } // if uncheck the checkox
                        else {
                           if (e===false) {
                            // current_data.attr('data-v', '1');
                            // current_data.removeClass('btn-info');
                            // current_data.addClass('btn-success');
                            // $('#verified-input-checkbox-'+check_id).val('1'); 
                           // swal("Ok done!","!");
                           swal.close();
                           // console.log("Do here everything you want");
                           } else {
                              //current_data.prop('checked',false);
                              // swal("Oh no...");
                              swal.close();
                              // console.log("The user says: ",e);
                           }
                        }
                    
                  }
                  );
            // if (r == true){
            //    // $(this).attr('disabled','disabled');
            //    // alert('mil gyi id ?'+ check_id);
            // }
        });

    });

    function uploadFile(dynamicID,fileResult,type,number){

        $("#fileUploadProcess").html("<img src='{{asset('images/process-horizontal.gif')}}' >"); 

        var fd = new FormData();
        var ins = document.getElementById("file"+number+"-"+dynamicID).files.length;
        // alert(ins);
        for (var x = 0; x < ins; x++) {
            fd.append("files[]", document.getElementById("file"+number+"-"+dynamicID).files[x]);
        }

        fd.append('report_id',"{{ base64_encode($report_id) }}");
        fd.append('report_item_id',dynamicID);
        fd.append('type',type);
        fd.append('_token', '{{csrf_token()}}');
        //
        $("."+fileResult+"-"+dynamicID).html('<div class="fa-3x"><i class="fas fa-spinner fa-pulse text-info"></i></div>');
            $.ajax({
            type: 'POST',
            url: "{{ url('/reports/upload/file') }}",
            data: fd,
            processData: false,
            contentType: false,
            success: function(data) {
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
                //     $("#"+fileResult+"-"+dynamicID).prepend("<div class='image-area' data-id='"+value.file_id+"'><img src='"+value.filePrev+"'  alt='Preview' title='"+value.file_name+"'><a class='remove-image' href='javascript:;' data-id='"+value.file_id+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+value.file_id+"'></div>");
                // });
                    
                } else {
                    $("#fileUploadProcess").html("");
                    //   alert("Please upload valid file! allowed file type, Image JPG, PNG etc. ");
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
            error: function(error) {
                console.log(error);
                // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
            }
            });
        
        return false;
    }

</script>  
@endsection