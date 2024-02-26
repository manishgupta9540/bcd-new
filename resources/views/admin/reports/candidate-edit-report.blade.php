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

#preview{
        /* overflow-x: hidden; */
        /* overflow-y: hidden; */
        z-index: 999;
        padding-top: 0px;
        /* margin:auto; */
    }
#preview .modal-dialog.modal-lg{
  max-width: 90% !important;
  width: 100%;
  padding: 0px;
  left: 3.5%;
}

#preview .modal-content {
  margin: auto;
  display: block;
  width: 100%;
  max-width: 1270px;
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
            <li><a href="{{ url('/home') }}">Dashboard</a></li> 
            <li><a href="{{ url('/reports') }}">Reports</a></li>
            <li>Edit</li>
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
               <h4 class="card-title mb-1">Update Report: <b> {{ $candidate->name }} ({{Helper::candidate_reference_id($candidate->id)}}) </b> </h4>
               <p>Update report items- comments and supportings etc.</p>
            </div>
            <div class="col-md-6">
                <p class="text-danger" style="font-size: 12px;">Note :- Please ensure about the data verified for each check's data. because it will be count in billing items, if "Data Verified" is check marked then it will count in Billing-Invoice.</p>
            </div>
            <div class="col-md-12">
               <form class="mt-2" method="post" action="{{ url('/reports/item/update') }}" id="report_form">
                @csrf
                <!-- candidate info -->
                <input type="hidden" name="report_id" value="{{ base64_encode($report_id) }}">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                <label>Name : <strong>{{ $candidate->name }} </strong></label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Phone : <strong>+{{$candidate->phone_code}}-{{ $candidate->phone }}</strong></label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                <label>Email : <strong>{{ $candidate->email!=NULL ? $candidate->email : 'N/A' }}</strong> </label>
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
                                        <label>Internal TAT : <strong>{{ $job_item->tat}} @if($job_item->tat > 1) days @else day @endif </strong></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Client TAT : <strong>{{ $job_item->client_tat}} @if($job_item->client_tat > 1) days @else day @endif</strong></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label>Price Type : <strong>{{ ucfirst($job_item->price_type.'-'.'Wise') }} </strong></label>
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
                                            <label>Case Initiated : <strong>{{date('d-M-Y h:i A',strtotime($candidate->created_at))}}</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>JAF Filled : <strong>{{date('d-M-Y h:i A',strtotime($job_item->filled_at))}}</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Report Generated : <strong>{{$report->complete_created_at!=NULL ? date('d-M-Y h:i A',strtotime($report->complete_created_at)) : date('d-M-Y h:i A',strtotime($report->generated_at))}}</strong></label>
                                        </div>
                                    </div>
                                    @if($report->is_report_complete==1)
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Report Completed : <strong>{{$report->report_complete_created_at!=NULL ? date('d-M-Y h:i A',strtotime($report->report_complete_created_at)) : '--'}}</strong></label>
                                        </div>
                                    </div>
                                    @endif
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
                @if( count($report_items) >0  )
                    @php
                        $check_no = 1;
                        $r_item_id = NULL;
                    @endphp
                @foreach($report_items as $r_key => $item)
                    <!--  -->
                  <div class="row" style="border: 1px solid #ddd; margin:10px 0; padding: 10px 0;">
                     <div class="col-md-6">
                         <div class="row">
                            @php
                                $report_check = Helper::get_report_item_services($item->candidate_id,$item->service_id);
                                $report_check_count = count($report_check);
                            @endphp
                             <div class="col-9">
                                {{-- <h3 class="mb-2 mt-2">Verification - {{$item->service_name.' - '.$item->service_item_number}} </h3> --}}
                                {{-- <h3 class="mb-2 mt-2">Verification - {{$item->service_name.' - '.$item->service_item_order}} </h3> --}}
                                    @php
                                        if($r_item_id!=NULL)
                                        {
                                            //dd($r_item_id);
                                            $previous = Helper::get_report_item($r_item_id);

                                            if($previous->service_id==$item->service_id)
                                            {
                                                $check_no++;
                                            }
                                            else {
                                                $check_no = 1;
                                            }
                                        }
                                    @endphp
                                    <h3 class="mb-2 mt-2">Verification - {{$item->service_name.' - '.$check_no}} </h3>
                             </div>
                             <div class="col-2">
                                 
                                <label> Check Order </label>
                                <select class="form-control check-order" name="check-order-{{$item->id}}">
                                    {{-- <option value="">--Select--</option> --}}
                                    @for ($c=1; $c<=$report_check_count;$c++)
                                        <option value="{{$c}}" @if($c==$item->service_item_order) selected @endif>{{$c}}</option>
                                    @endfor
                                </select>
                             </div>
                         </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <p>Provide the approval and comments (Remarks: Checked = Yes, Left Blank = -)</p>
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
                                    @if($item->is_data_verified=='1')
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group text-muted">
                                                    <span>(<strong>{{Helper::user_name($item->verified_data_submitted_by)}}</strong>, <strong>{{$item->data_verified_date!=NULL ? date('d-M-Y h:i A',strtotime($item->data_verified_date)) : '--'}}</strong>)</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
                            // echo "<pre>"; print_r($input_item_data_array);
                            // dd($input_item_data);
                        ?>
                        @foreach($input_item_data_array as $key => $input)
                            <!-- start row -->
                            <?php 
                            //  dd($input);
                            // echo "<pre>"; print_r($input); ?>
                            <div class="row" >
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <?php 
                                            $key_val = array_keys($input); 
                                            // print_r($key_val);
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

                                            $is_executive_summary ="0";
                                            // $is_executive_summary = Helper::get_is_executive_summary($item->service_id,$key_val[0]);
                                            if(array_key_exists('is_executive_summary',$input))
                                            {
                                                $is_executive_summary = $input['is_executive_summary'];
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
                                                <input class="form-control error-control {{$date_calss}}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
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

                                        {{-- @if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                                            <label>  {{ $key_val[0]}} <span class="text-danger">*</span></label>
                                            <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                            <select class="form-control service-input-value-{{$item->id.'-'.$i}} reference_type error-control" name="service-input-value-{{ $item->id.'-'.$i }}" data-id="{{base64_encode($item->id)}}" data-report="{{$item->id}}">
                                                <option value="">--Select--</option>
                                                <option @if(stripos($input_val[0],'personal')!==false) selected @endif value="personal">Personal</option>
                                                <option @if(stripos($input_val[0],'professional')!==false) selected @endif value="professional">Professional</option>
                                            </select>
                                        @else
                                            <label>  {{ $key_val[0] }} </label>
                                            <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                            <input class="form-control error-control" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
                                        @endif --}}
                                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{ $item->id.'-'.$i }}"></p>
                                    </div>
                                </div>
                                <!-- Remarks -->
                                <div class="col-sm-1">
                                    <div class="form-group">
                                    <label> Remarks </label>
                                        <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" id="remarks-input-checkbox-{{ $item->id.'-'.$i}}" name="remarks-input-checkbox-{{ $item->id.'-'.$i}}"  @if(in_array('remarks', $key_val)) @if($input['remarks']=='Yes') checked @endif @endif class="form-check-input" >
                                        </label>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-5">
                                    <div class="form-group">
                                    <label> Remarks Message</label>
                                        <?php
                                            $remarks_message =""; 
                                            $remarks_custom_message="";
                                            if(array_key_exists('remarks_message', $input_item_data_array[$i]))
                                            {
                                                $remarks_message =  $input_item_data_array[$i]['remarks_message'];
                                            }
                                            if(array_key_exists('remarks_custom_message', $input_item_data_array[$i]))
                                            {
                                                $remarks_custom_message =  $input_item_data_array[$i]['remarks_custom_message'];
                                                // dd($remarks_custom_message);
                                            }
                                        ?>
                                         <select class="form-control remark_msg" name="remarks-input-value-{{ $item->id.'-'.$i}}" data-item_id="{{  $item->id }}" data-id="{{ $i }}">
                                            <option value="">-Select-</option>
                                            <option value="clear" {{ $remarks_message == 'clear' ? 'selected' : '' }}>Verified Clear</option>
                                            <option value="no_record" {{ $remarks_message == 'no_record' ? 'selected' : '' }}>No Record Found</option>
                                            <option value="unable_verify" {{ $remarks_message == 'unable_verify' ? 'selected' : '' }}>Unable to Verify</option>
                                            <option value="stop" {{ $remarks_message == 'stop' ? 'selected' : '' }}>Stop</option>
                                            <option value="custom" {{ $remarks_message == 'custom' ? 'selected' : '' }}>Custom</option>
                                        </select>
                                        {{--<input type="text" name="remarks-input-value-{{ $item->id.'-'.$i}}"  class="form-control" value="{{ $remarks_message }}"> --}}
                                    </div>
                                </div>
                            </div>
                             <!-- check output -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="checkbox-inline error-control">
                                        <?php
                                            
                                            $is_report_output ="0"; 
                                            // if(array_key_exists('is_executive_summary', $input_item_data_array[$i]))
                                            // {
                                            //     $is_executive_summary =  $input_item_data_array[$i]['is_executive_summary'];
                                            // }
                                            if(array_key_exists('is_report_output', $input_item_data_array[$i]))
                                            {
                                                $is_report_output =  $input_item_data_array[$i]['is_report_output'];
                                            }
                                        ?>
                                            {{-- <input type="checkbox" name="executive-summary-{{ $item->id .'-'.$i}}" @if ($is_executive_summary)
                                                
                                                @if($is_executive_summary->is_executive_summary == '1')  checked @endif @endif > Executive Summary Output (if yes: Check Mark) --}}

                                                <input type="checkbox" name="executive-summary-{{ $item->id .'-'.$i}}"
                                                
                                                @if($is_executive_summary == '1')  checked @endif > Executive Summary Output (if yes: Check Mark)
                                        </label>
                                        </div>
                                        <div class="form-group">
                                        <label class="checkbox-inline error-control">
                                            <input type="checkbox" name="table-output-{{ $item->id.'-'.$i }}" @if($is_report_output == '1')  checked @endif > Check's Table Output (if yes: Check Mark)
                                        </label>
                                        </div>
                                    </div> 
                                    <div class="col-sm-6">
                                        <div class="form-group @if($remarks_custom_message=='') d-none @endif " id="msg-{{ $item->id.'-'.$i }}" >
                                            <input type="text" name="remarks-msg-{{ $item->id.'-'.$i}}"  class="form-control" value="{{ $remarks_custom_message }}">
                                        </div>
                                    </div>
                                </div>
                            <!-- ./check outputs -->
                            <!-- end row -->
                            <?php $i++; ?>
                        @endforeach

                            <!-- Reference Type inputs  -->
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
                                                                <label> {{ $input->label_name }} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
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
                            <!--  -->
                          <!-- Additional Address  -->
                            @if ($item->service_name=="Address")
                                @php
                                    //Helper to get report_add_page_statuses Data
                                    $report_add_page =  Helper::get_report_page($candidate->business_id);
                                    $additional_data =  Helper::get_additional_address_data($candidate->id,$item->id);
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
                                                    <input class="form-control error-control" type="text" name="contact_person_name-{{ $item->id }}"  value="{{ $additional_data ? $additional_data->contact_person_name : ''}}"  >
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>  Contact Person Number</label>
                                                    <input class="form-control error-control" type="text" name="contact_person_no-{{ $item->id }}"  value="{{ $additional_data ? $additional_data->contact_contact_no : '' }}"  >
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Residence Status</label>
                                                    <input class="form-control error-control" type="text" name="residence_status-{{ $item->id }}"  value="{{ $additional_data ? $additional_data->residence_status : ''  }}"  >
                                                </div>
                                            </div><div class="col-sm-6">
                                                <div class="form-group">
                                                    <label> Relation with Associate</label>
                                                    <input class="form-control error-control" type="text" name="relation_with_associate-{{ $item->id }}"  value="{{  $additional_data ? $additional_data->relation_with_associate : ''  }}"  >
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>  Locality</label>
                                                    <input class="form-control error-control" type="text" name="locality-{{ $item->id }}"  value="{{  $additional_data ? $additional_data->locality : '' }}"  >
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Mode of Verification</label>
                                                    <input class="form-control error-control" type="text" name="verification_mode-{{ $item->id }}"  value="{{   $additional_data ? $additional_data->mode_of_verification : '' }}"  >
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>  Remarks</label>
                                                    <input class="form-control error-control" type="text" name="additional_remark-{{ $item->id }}"  value="{{  $additional_data ? $additional_data->remarks : '' }} "  >
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label> Verified By</label>
                                                    <input class="form-control error-control" type="text" name="Additional_verified_by-{{ $item->id }}"  value="{{   $additional_data ? $additional_data->verified_by : ''}}"  >
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Additional Comments <small>(If any)</small></label>
                                                    <textarea class="form-control error-control" type="text" name="additional_verification_comments-{{ $item->id }}" >@if ($additional_data){{ $additional_data->comments }} @endif</textarea>
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
                                        <input class="form-control error-control" type="text" name="verified_by-{{ $item->id }}" value="{{ $item->verified_by }}">
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
                                            <textarea class="form-control " type="text" name="comments-{{ $item->id }}" >{{ $item->comments?$item->comments:"The copy of confirmation is attached herewith as Annexure." }}</textarea>
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
                                        <textarea class="form-control error-control" type="text" name="additional-comments-{{ $item->id }}" > {{ $item->additional_comments }} </textarea>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Approval Status</label>
                                        <select class="form-control approval_status error-control" name="approval-status-{{ $item->id }}" >
                                            @foreach($status_list as $status)
                                            <option data-id="{{ $item->id }}" value="{{ $status->id}}" @if($status->id == $item->approval_status_id) selected @endif> {{ $status->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="new-tag">
                                        @if ($item->approval_status_id!='4')
                                        <div class='form-group '><label class='text-danger'>Add Your Insuff Notes </label>
                                            <input class='form-control' type='text' value="{{ $item->report_insufficiency_notes }}" readonly></div>
                                        @endif   
                                    </div>
                                    <input type="hidden" class="itemID" name="itemID" value="{{ $item->id }}">
                                </div>
                            </div>
                            <!--  -->
                             <!-- Court inpput start -->
                             @if( $item->service_id == 15 )  
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
                                        <p>District Court/Lower Court/Civil Court & Small Causes</p>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-6" style="padding-left:0px;padding-right:0px">
                                        <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3">District Courts of</span>
                                        </div>
                                            <input type="text" class="form-control error-control" name="district_court_name-{{$item->id}}"  value="{{ $item->district_court_name }}" aria-describedby="basic-addon3">
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <input type="text" name="district_court_result-{{$item->id}}" class="form-control error-control" value="{{ $item->district_court_result }}">
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
                                        <div class="form-group" >
                                            <input type="text" name="supreme_court_name-{{$item->id}}" class="form-control error-control" value="Supreme Court of India, New Delhi" readonly>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <input type="text" name="supreme_court_result-{{$item->id}}" class="form-control error-control" value="{{ $item->supreme_court_result }}">
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
                                    <label class="checkbox-inline text-danger error-control">
                                        <input type="checkbox" name="report-output-{{ $item->id }}" @if($item->is_report_output == '1')  checked @endif >  Include in Report Output (if yes: Check Mark)
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <!--  -->

                     </div>
                     <!-- attachment  -->
                     <div class="col-md-6">
                        <p>Attachments: <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,gif,pdf are accepted "></i></p>
                        <button class='btn btn-info clickReorder reorder_link' type="button" add-imageId="{{$item->id}}" data-imageType='main' style=' float:right;'><i class="fas fa-sync"></i> Re-Arrange </button>
                        <a class='btn-link clickSelectFile error-control' add-id="{{$item->id}}" data-number='1' data-result='fileResult1' data-type='main' style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a>
                        <input type='file' class='fileupload' name="file-{{$item->id}}[]" id='file1-{{$item->id}}' multiple="multiple" style='display:none'/><br>
                        <div class="fileResult1-{{$item->id}} text-center"></div>
                        <div class='row fileResult' id="fileResult1-{{$item->id}}" style='min-height: 20px; margin-top: 20px;'>
                        <?php $item_files = Helper::getReportAttachFiles($item->id,'main'); //print_r($item_files); ?>
                        <?php $i = 0; ?>
                        @foreach($item_files as $file)
                            @if($file['attachment_type'] == 'main')
                            <div class="image-area" data-id="<?=$i;?>">
                                @if(stripos($file['file_name'],'pdf')!==false)
                                    <img src="{{url('/').'/admin/images/icon_pdf.png'}}" class="fileItem" alt="Preview" id="zoom<?=$i;?>" title="{{$file['file_name']}}">
                                @elseif (stripos($file['file_name'],'zip')!==false)
                                    <a href="{{ url('/vitals-report/zip',['id'=>base64_encode($item->id)]) }}">
                                        <img src="{{url('/').'/admin/images/zip.png'}}" title="{{$file['file_name']}}">
                                    </a>
                                @else
                                    <img src="{{ $file['fileIcon'] }}" class="fileItem" alt="Preview" id="zoom<?=$i;?>" title="{{$file['file_name']}}">
                                @endif
                                <input type="hidden" name="zoom_id"  id="a_id" value="<?=$i;?>">
                                <a class="remove-image" data-id="{{ $file['file_id'] }}" href="javascript:;" style="display: inline;">×</a>
                                <input type="hidden" name="fileID[]" value="{{ $file['file_id'] }}">
                            </div>
                            
                            @endif
                            <?php $i++; ?>
                        @endforeach
                        </div>
                        <p class="mt-2" style="margin-bottom:1px">Add Supportings: <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,gif,pdf are accepted "></i></p>
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
                  

                  <?php $r_item_id = $item->id;?>
                 @endforeach
                 @endif
                    <!-- Verifier Details -->
                    <div class="row" style="border: 1px solid #ddd; margin:10px 0; padding: 10px 0;">
                        <div class="col-md-12">
                            <h4 class="card-title mb-0" style="margin-bottom:0px;">Report Verifier Details </h4>
                            <hr style="margin-top:2px;">
                        </div>
                    
                        <div class="col-sm-4">
                            <div class="form-group">
                            <label>Verifier name </label>
                                <input class="form-control " type="text" name="verifier_name" value="{{$report->verifier_name}}">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                            <label>Verifier Email </label>
                                <input class="form-control " type="text" name="verifier_email" value="{{$report->verifier_email}}">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                            <label>Verifier Designation </label>
                                <input class="form-control " type="text" name="verifier_designation" value="{{$report->verifier_designation}}" >
                            </div>
                        </div>
                    </div>
                    
                    <!-- Insuff Details -->
                    <div class="row" style="border: 1px solid #ddd; margin:10px 0; padding: 10px 0;">
                        <div class="col-md-12">
                            <h4 class="card-title mb-0" style="margin-bottom:0px;">Insuff Details </h4>
                            <hr style="margin-top:2px;">
                        </div>
                    
                        <div class="col-sm-4">
                            <div class="form-group">
                            <label>Insuff raised date </label>
                            <input type="text" name="insuff_raised_date" class="form-control insuff_raised_date commonDatepicker"  placeholder="" value="{{$report->insuff_raised_date!=NULL?date('d-m-Y', strtotime($report->insuff_raised_date)):old('insuff_raised_date') }}" autocomplete="off">
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-insuff_raised_date"></p>
                        </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                            <label>Insuff cleared date</label>
                            <input type="text" name="insuff_cleared_date" class="form-control insuff_cleared_date commonDatepicker"  placeholder="" value="{{$report->insuff_cleared_date!=null?date('d-m-Y', strtotime($report->insuff_cleared_date)):old('insuff_cleared_date') }}" autocomplete="off">
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-insuff_cleared_date"></p>
                        </div>
                        </div>
                       
                    </div>
                    <!-- Revised date Update -->
                    @php
                        $hide = Helper::report_custom($report->business_id);
                    @endphp
                   
                   
                        <div class="row" style="border: 1px solid #ddd; margin:10px 0; padding: 10px 0;">
                            <div class="col-md-12">
                                <h4 class="card-title mb-0" style="margin-bottom:0px;">Add Custom Date </h4>
                                <hr style="margin-top:2px;">
                            </div>
                            @if($hide=='enable')
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Revised Date </label>
                                        <input class="form-control commonDatepicker revised_date" type="text" name="revised_date" value="{{ $report_revised_date && date('Y-m-d',strtotime($report_revised_date))!='1970-01-01' ? date('d-m-Y',strtotime($report_revised_date)):NULL }}" autocomplete="off">
                                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-revised_date"></p>
                                    </div>
                                </div>
                            @endif
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Case Initiated Date </label>
                                    <input class="form-control commonDatepicker initiated_date" type="text" name="initiated_date" value="{{$report->initiated_date!=null?date('d-m-Y', strtotime($report->initiated_date)):old('insuff_cleared_date') }}" autocomplete="off">
                                    <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-initiated_date"></p>
                                </div>
                            </div>
                        
                        </div> 

                  {{-- </div> --}}
                   
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group mt-3">
                                {{-- <div class="form-check">
                                    <label class="check-inline">
                                    <input type="checkbox" name="check_green" class="form-check-input check_green" @if($report->is_manual_mark==1) checked @endif><span style="font-size: 14px;">Want to Mark the Color Code as Green Anyway</span>
                                    </label>
                                </div> --}}
                                <label>Mark the Color Code As</label>
                                <select class="form-control manual_check" name="manual_check">
                                    <option>None</option>
                                    <option @if($report->is_manual_mark==1) selected @endif value="1">Green</option>
                                    <option @if($report->is_manual_mark==2) selected @endif value="2">Grey (Stopped)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                     <div class="form-group mt-3">
                        <div class="form-check">
                            <label class="check-inline">
                               <input type="checkbox" name="report_complete" class="form-check-input report_complete" @if($report->is_report_complete==1) checked disabled @endif><span style="font-size: 16px;"><b>Mark as Report Completed</b></span>
                            </label>
                         </div>
                     </div>
                    {{-- <div class="row update-btn"> --}}
                        <div class="text-center">
                            <button type="submit" class="btn btn-success"><i class="far fa-edit"></i> Update</button>
                            <button type="button" class="btn btn-dark reportPreviewBox" data-id="{{ base64_encode($report->id) }}"><i class="fas fa-eye"></i> Preview</button>
                            <a href="{{url('/reports')}}"><button type="button" class="btn btn-info"><i class="fas fa-arrow-left"></i> Back to Report </button></a>
                        </div>
                  {{-- </div> --}}
               </form>
            </div>
         </div>
      </div>
   </div>
</div>


<!-- The Modal -->
<div id="myModal" class="modal">
    
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <h5 class="modal-title">File </h5>
        
      </div>
        <div class="modal-body">
                                            
            <img class=" showImg" id="img01">
            
        </div>
    </div>
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
<!-- model end -->

<div class="modal"  id="preview">
    <div class="modal-dialog modal-lg">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Report Preview</h4>
             <button type="button" class="close" style="top: 12px;!important; color: red;" data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
          
             <div class="modal-body">
             <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                <iframe 
                    src="" 
                    style="width:100%; height:600px;" 
                    frameborder="0" id="preview_pdf">
                </iframe>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="button" class="btn btn-danger back" data-dismiss="modal">Close</button>
             </div>
       </div>
    </div>
</div>

@stack('scripts')
<script type="text/javascript">
 $(document).ready(function() {

    $(".vital_list").select2();
    
    $(".manual_check").select2();

    $(".approval_status").select2();

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

    // Preview Report
    $(document).on('click','.reportPreviewBox',function(){
        // alert('ads');
        var report_id = $(this).attr('data-id');

        document.getElementById('preview_pdf').src="{{ url('/') }}"+"/candidate/report/preview/"+report_id;
    
        $('#preview').toggle();
    });

    $('.close').click(function(){
        $('#preview').hide();
    });
    $('.back').click(function(){
        $('#preview').hide();
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
                console.log(data);
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
    $(document).on('change', '.remark_msg', function (e) {
        e.preventDefault();  //stop the browser from following
            var _current =$(this);
            var id=_current.val();
            var data_id = $(this).attr('data-id');
            var data_item_id = $(this).attr('data-item_id');
            // alert(data_id);
            if (id =='custom') {
                $("#msg-"+data_item_id+'-'+data_id).removeClass('d-none');
                // $(".multiple").hide();
            }
            else {
                $("#msg-"+data_item_id+'-'+data_id).addClass('d-none');
                // $(".multiple").show();addClass
            }
        
    });
   $(document).on('click','.image-area > img',function(){ 
            
               var img_src =  $(this).attr("src");
               
                $('.showImg').attr('src',img_src);
                $('#myModal').modal();
               
   });
 });
   
   $(document).on('change','.approval_status',function(e){    
       var newVal = $(this).val(); 
       var itemId = $(this).parent().next().next('.itemId').val(); 
       
       var currentEl = $(this); 
        if(newVal != '4'){
           var t= $(currentEl).parent().next('.new-tag').html("<div class='form-group '><label class='text-danger'>Add Your Insuff Notes </label><input class='form-control' type='text' name='insuf_notes-"+itemId+"' ></div>");
        }else{
            $(currentEl).parent().next('.new-tag').html("");
        }
   });

   $(document).ready(function() {
      var curNum ='';
      var fileResult='fileResult2';
      var type = 'main';
      var number = '1';
      $(document).on('click','.clickSelectFile',function(){ 
         curNum     = $(this).attr('add-id');
         fileResult = $(this).attr('data-result');
         type       = $(this).attr('data-type');
         number     = $(this).attr('data-number');
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
            //             var current = $(this);
            //             var file_id = $(this).attr('data-id');
            //             //
            //             var fd = new FormData();

            //             fd.append('file_id',file_id);
            //             fd.append('_token', '{{csrf_token()}}');
            //             //
            //             $.ajax({
            //                 type: 'POST',
            //                 url: "{{ url('/reports/remove/file') }}",
            //                 data: fd,
            //                 processData: false,
            //                 contentType: false,
            //                 success: function(data) {
            //                     console.log(data);
            //                     if (data.fail == false) {
            //                     //reset data
            //                     $('.fileupload').val("");
            //                     //append result
            //                     $(current).parent('.image-area').detach();
            //                     } else {
                                
            //                     console.log("file error!");
                                
            //                     }
            //                 },
            //                 error: function(error) {
            //                     console.log(error);
            //                     // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
            //                 }
            //             });
            //     return false;
                
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
                    //  console.log(response);
                     if(response.success==true) {          
                           // var case_id = response.case_id;
                           //notify
                           toastr.success("Report Has Been Updated Successfully");
                           // redirect to google after 5 seconds
                           window.setTimeout(function() {
                              //window.location = "{{ url('/')}}"+"/reports/";
                              window.location.reload();
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

        $(document).on('click','.verified_data',function (event) {
         var current_data = $(this);
         var check_id = $(this).attr('data-id');
         //var status = $(this).prop('checked');
         var status   =  $(this).attr('data-v');  
       
         var r =swal({
                     title: "Are you sure?",
                     text: "While confirming this status, please make sure about Verification data or attachment submitted!",
                     type: "warning",
                     dangerMode: true,
                     showCancelButton: true,
                    //  cancelButtonColor: "#DD6B55",
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
                              //current_data.prop('checked',true);
                                current_data.attr('data-v', '0');
                                current_data.removeClass('btn-success');
                                current_data.addClass('btn-info');
                                current_data.text('Data Verified ?');
                                $('#verified-input-checkbox-'+check_id).val('0');  
                                swal.close();
                              // swal("Oh no...");
                              // console.log("The user says: ",e);
                           }
                           
                        } // if uncheck the checkox
                        else {
                           if (e===false) {
                              //current_data.prop('checked',true);
                           // swal("Ok done!","!");
                           swal.close();
                           // console.log("Do here everything you want");
                           } else {
                             // current_data.prop('checked',false);
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

        $(document).on('click','.report_complete',function (event) {
         var current_data = $(this);
         var status = $(this).prop('checked');
         var r =swal({
                     title: "Are You Sure?",
                     text: "While confirming this status, please make sure about Verification data or attachment submitted!",
                     type: "warning",
                     dangerMode: true,
                     showCancelButton: true,
                    //  cancelButtonColor: "#DD6B55",
                     confirmButtonColor: "#007358",
                     confirmButtonText: "YES",
                     cancelButtonText: "CANCEL",
                     closeOnConfirm: false,
                     closeOnCancel: false
                     },
                     function(e){
                        //Use the "Strict Equality Comparison" to accept the user's input "false" as string)
                        // if check the checkox
                        if (status== true) {
                           if (e===false) {
                              current_data.prop('checked',false);
                              // toastr.success("New Check added  successfully");
                                 // redirect to google after 5 seconds
                                 swal.close();
                           // console.log("Do here everything you want");
                           } else {
                              current_data.prop('checked',true);
                              swal.close();
                              // swal("Oh no...");
                              // console.log("The user says: ",e);
                           }
                           
                        } // if uncheck the checkox
                        else {
                           if (e===false) {
                              current_data.prop('checked',true);
                           // swal("Ok done!","!");
                           swal.close();
                           // console.log("Do here everything you want");
                           } else {
                              current_data.prop('checked',false);
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
        // console.log(data);
        if (data.fail == false) {
        //reset data
        $('.fileupload').val("");
        $("#fileUploadProcess").html("");
        //append result
        // console.log(data.data);

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
            //   alert("Please upload valid file! allowed file type, Image JPG, PNG, PDF etc. ");
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

//remove file
$(document).on('change','.remark_msg',function(){ 
    var _this = $(this);
    var msg     = $(this).attr('data-item_id');
    var id     = $(this).attr('data-id');
    var value = $(this).val();
    //  alert(value);
    if(value ==''){
    $('#remarks-input-checkbox-'+msg+'-'+id).prop('checked',false);
    }
    else{
    $('#remarks-input-checkbox-'+msg+'-'+id).prop('checked',true);
    }   
    // remarks-input-checkbox-{{ $item->id.'-'.$i}}
});
</script>  
@endsection