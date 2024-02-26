@extends('layouts.client')
<style>
   .disabled-link{
      pointer-events: none;
   }
   .disabled-link-1{
      pointer-events: none;
   }
   .sticky {
   position: fixed;
    top: 8%;
    width: 100%;
    z-index: 999;
    background: #eeeeee;
    border: 1px solid #eee;
    border-radius: 3px;
  
}

.sticky li{
   color: #fff !important;
}
.col-sm-11.breadcrum1 {
    position: relative;
    top: -9px;
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
</style>
@section('content')
@php
   use App\Traits\S3ConfigTrait;
@endphp
<div class="main-content-wrap sidenav-open d-flex flex-column">
<!-- ============ Body content start ============= -->
<div class="main-content">
<!-- ============Breadcrumb ============= -->
<div class="row">
        <div class="col-sm-11">
            <ul class="breadcrumb">
            <li><a href="{{ url('/my/home') }}">Dashboard</a></li>
            <li><a href="{{ url('/my/candidates') }}">Candidate</a></li>
            <li>Detail</li>
            </ul>
        </div>
        <!-- ============Back Button ============= -->
        <div class="col-sm-1 back-arrow">
            <div class="text-right">
            <a href="{{ url('/my/candidates') }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
            </div>
        </div>
</div>
<!-- ./breadbrum -->
<div class="row">
   <div class="card text-left">
      <div class="card-body" style="padding:0px">
         <div class="row">
            <div class="col-md-3 col-12">
               <div class="span10 offset1">
                  <div id="modalTab">
                     <div class="tab-content">
                        <div class="tab-pane active" id="about">
                           <img src="{{ asset('admin/images/profile-default-avtar.jpg') }}" name="aboutme" width="140" height="140" border="0" class="img-circle">
                           <p class="m-0 text-24"> {{ $candidate->name}} 
                              {{-- <a class="text-success mr-2" href="#"><i class="nav-icon i-Pen-2 font-weight-bold" style="font-size: 10px;"></i></a> --}}
                           </p>
                           {{-- <p class="text-muted m-0"> &nbsp; </p> --}}
                           {{-- <ul class="nav nav-tabs profile-nav mb-4" id="profileTab" role="tablist">
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-tasks"> </i><br> JAF</a></li>
                               <li class="nav-item"><a class="nav-link" id="timeline-tab" href=""> <i class="fa fa-window-maximize" aria-hidden="true"></i> <br> Report </a></li>

                           </ul> --}}
                           {{-- <hr style="margin-top:10px; margin-bottom:10px;"> --}}
                           <ul style="list-style: none; text-align: left; padding: 0">
                             
                           </ul>
                           @include('clients.candidates.profile-info')
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-9 col-12" style="background: #f6f8fc;">
               <h4 class="card-title mb-3"> </h4>
               <ul class="nav nav-pills" id="myPillTab" role="tablist" style="border-bottom: 1px solid #cdd1d8;">
                  <li class="nav-item"><a class="nav-link " id="home-icon-pill" href="{{ url('my/candidates/show',['id'=> base64_encode($candidate->id)]) }}" > Activity </a></li>
                  <li class="nav-item"><a class="nav-link active show" id="profile-icon-pill" data-toggle="pill" href="#profilePIll" > JAF </a></li>
                  <li class="nav-item"><a class="nav-link" id="notes-icon-pill"  href="{{ url('/my/candidates/notes',['id'=> base64_encode($candidate->id)]) }}" role="tab" > Notes </a></li>
                  @if ($report != '')
                     <li class="nav-item"><a class="nav-link reportsPreviewBox" id="contact-icon-pill" data-id="{{ base64_encode($report->id) }}"  href="#"> Report </a></li>
                  @endif
  

               </ul> 
               <div class="tab-content" id="myPillTabContent" style="background: #fff;">
                  
                  <div class="tab-pane fade active show" id="homePIll" role="tabpanel" aria-labelledby="home-icon-pill">
                     
                        <div class="inbox-main-content sidebar-content" data-sidebar-content="main">
                           {{-- @if($candidate->jaf_status!='pending') --}}
                           @if($candidate->jaf_status=='filled')
                              <div class="col-md-12">
                                 <form class="mt-2" method="post" enctype="multipart/form-data" action="{{ url('/my/candidates/jafFormUpdate') }}" id="jaf_form">
                                    @csrf
                                    <!-- candidate info -->
                                    <input type="hidden" name="candidate_id" value="{{ Request::segment(4) }}">
                                 
                                    <div class="row">
                                       @if ($message = Session::get('success'))
                                          <div class="col-md-12">
                                             <div class="alert alert-success">
                                                <strong>{{ $message }}</strong> 
                                             </div>
                                          </div>
                                       @endif
                                       <div class="col-12">
                                          <h4 class="card-title mb-3 mt-2">Candidate Profile: <b> {{ $candidate->name }} ({{Helper::candidate_reference_id($candidate->id)}}) </h4>
                                       </div>
                                    </div>
                                    {{-- <div class="row">
                                       <div class="col-md-8">
                                             <div class="row">
                                                <div class="col-sm-6">
                                                   <div class="form-group">
                                                   <label>First name </label>
                                                         <input class="form-control" type="text" name="first_name" value="{{ $candidate->first_name }}" readonly>
                                                   </div>
                                                </div>
                                                <div class="col-sm-6">
                                                   <div class="form-group">
                                                   <label>Last name</label>
                                                   <input class="form-control" type="text" name="last_name" value="{{ $candidate->last_name }}" readonly>
                                                   </div>
                                                </div>
                                             </div>

                                             <div class="row">
                                                <div class="col-sm-6">
                                                   <div class="form-group">
                                                   <label>Email </label>
                                                   <input class="form-control " type="text" name="email" value="{{ $candidate->email }}" readonly>
                                                   </div>
                                                </div>
                                                <div class="col-sm-6">
                                                   <div class="form-group">
                                                   <label>Phone</label>
                                                   <input class="form-control number_only" type="text" name="phone" value="{{ $candidate->phone }}" readonly>
                                                   </div>
                                                </div>
                                             </div>

                                             <div class="row">
                                                <div class="col-sm-6">
                                                   <div class="form-group">
                                                      <label>Client emp code </label>
                                                      <input class="form-control " type="text" name="client_emp_code" value="{{ $candidate->client_emp_code }}" readonly>
                                                   </div>
                                                </div>
                                                <div class="col-sm-6">
                                                <div class="form-group">
                                                      <label>Entity code </label>
                                                      <input class="form-control " type="text" name="entity_code" value="{{ $candidate->entity_code }}" readonly>
                                                   </div>
                                                </div>
                                             </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-12">
                                          <hr>
                                       </div>
                                    </div>    --}}
                                    <?php $user = Auth::user()->user_type; ?>
                                       @if ($user == 'customer')
                                          <!-- service item -->
                                          @if( count($jaf_items) >0  )
                                          @foreach($jaf_items as $item)
                                             <?php
                                                //get sale item count
                                                $i=0;
                                                $num ="";
                                             ?>
                                             <input type="hidden" value="{{ $item->id }}" name="jaf_id[]">
                                             <div class="row" style="padding: 10px 0; margin-top:10px; border:1px solid #ddd;">
                                                <div class="col-md-6">
                                                   
                                                   <h3 class=" mb-2 mt-2">Verification - {{ $item->service_name }} {{stripos($item->verification_type,'Manual')!==false ? ' - '.$item->check_item_number : ''}}</h3>
                                                   <p>Update the inputs </p>

                                                   <!-- if check type is address  -->
                                                      @if($item->service_id == '1')
                                                         <div class="row" >
                                                            <div class="col-sm-12">
                                                                  <div class="form-group">
                                                                  <label>Address Type <span class="text-danger">*</span></label>
                                                                     <select class="form-control address-type-{{$item->id}}" name="address-type-{{$item->id}}" >
                                                                        <option value="">- Select Type -</option>
                                                                        <option value="current" @if($item->address_type !=null) @if($item->address_type=='current') selected @endif @endif > Current </option>
                                                                        <option value="permanent" @if($item->address_type !=null) @if($item->address_type=='permanent') selected @endif @endif >Permanent</option>
                                                                     </select>
                                                                        @if ($errors->has('address-type-'.$item->id))
                                                                           <div class="error pt-2 text-danger">
                                                                              {{ $errors->first('address-type-'.$item->id) }}
                                                                           </div>
                                                                        @endif
                                                                  </div>
                                                            </div>
                                                         </div>
                                                      @endif
                                                      <!--  -->

                                                   <!--  -->
                                                   <?php 
                                                         $input_item_data = $item->form_data;
                                                         $input_item_data_array =  json_decode($input_item_data, true); 
                                                   ?>
                                                   @foreach($input_item_data_array as $key => $input)
                                                      <div class="row">
                                                         <div class="col-sm-12">
                                                               <div class="form-group">
                                                               <?php $key_val = array_keys($input); $input_val = array_values($input); 

                                                                     $university_board =  $readonly= "";
                                                                     $university_board_id="";
                                                                     $date_calss='';
                                                                     $show_calender='';
                                                                     if($key_val[0] =='University Name / Board Name'){ 
                                                                        $university_board_id = "#searchUniversity_board";
                                                                        $university_board = "searchUniversity_board";
                                                                     }
                                                                  //name
                                                                  if($key_val[0]=='First Name' || $key_val[0]=='First name' || $key_val[0]=='first name'){ 
                                                                     $name = $candidate->first_name;
                                                                     $readonly ="readonly";
                                                                  }
                                                                  if($key_val[0]=='Last Name' || $key_val[0]=='Last name' || $key_val[0]=='last name'){ 
                                                                     $name = $candidate->last_name;
                                                                    // $readonly ="readonly";
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
                                                               ?>
                                                               <label>  {{ $key_val[0]}} </label><br>
                                                               <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                               @if($item->service_id==17)
                                                                  @if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                                                                     <select class="form-control {{$university_board }} service-input-value-{{$item->id.'-'.$i}}" id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}">
                                                                        <option value="">--Select--</option>
                                                                        <option @if(stripos($input_val[0],'personal')!==false) selected @endif value="personal">Personal</option>
                                                                        <option @if(stripos($input_val[0],'professional')!==false) selected @endif value="professional">Professional</option>
                                                                     </select>
                                                                  @else
                                                                     <input class="form-control {{ $university_board }}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
                                                                  @endif
                                                               @elseif(stripos($item->type_name,'drug_test_5')!==false || stripos($item->type_name,'drug_test_6')!==false || stripos($item->type_name,'drug_test_7')!==false || stripos($item->type_name,'drug_test_8')!==false || stripos($item->type_name,'drug_test_9')!==false || stripos($item->type_name,'drug_test_10')!==false)
                                                                     @if (stripos($key_val[0],'Test Name')!==false)
                                                                        <input class="form-control" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
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
                                                                        <select class="form-control {{$university_board }} service-input-value-{{$item->id.'-'.$i}}" name="service-input-value-{{ $item->id.'-'.$i }}">
                                                                           <option value="">--Select--</option>
                                                                           <option @if(stripos($input_val[0],'positive')!==false) selected @endif value="positive">Positive</option>
                                                                           <option @if(stripos($input_val[0],'negative')!==false) selected  @endif value="negative">Negative</option>
                                                                        </select> 
                                                                     @else
                                                                        <input class="form-control {{ $university_board }}" id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">     
                                                                     @endif
                                                               @else
                                                                  <input class="form-control {{$date_calss.' '.$university_board }}" id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">  
                                                                  {!! $show_calender !!}
                                                               @endif
                                                               </div>
                                                         </div>
                                                      </div>
                                                      <?php $i++; ?>
                                                   @endforeach 
                                                   <!--   -->
                                                   <!-- insufficiency -->
                                                   <div class="row">
                                                   <div class="col-sm-12" style="border:1px solid #ddd; padding:10px; margin-bottom:10px;">
                                                      <p>Manual insufficiency Status</p>
                                                      <div class="col-sm-12">
                                                            <div class="form-group">
                                                            <div class="form-check">
                                                               <label class="form-check-label">
                                                                  <input style="margin-top: 1px;" type="checkbox" class="form-check-input"  @if($item->is_insufficiency == 1) checked @endif name="insufficiency-{{ $item->id }}" >Mark as insufficiency
                                                               </label>
                                                            </div>
                                                            </div>
                                                      </div>
                                                      <!--  -->
                                                      <div class="col-sm-12">
                                                            <div class="form-group">
                                                               <label>insufficiency Notes</label>
                                                               <input type="text" class="form-control" name="insufficiency-notes-{{$item->id }}" value="{{ $item->insufficiency_notes}}">
                                                            </div>
                                                      </div>
                                                      <!-- ./ -->
                                                      </div>
                                                   </div>
                                                   <!-- Autocheck Insuff -->
                                                   @if($item->verification_type =='Auto')
                                                   <div class="row">
                                                      <!--  -->
                                                      <div class="col-sm-12" style="border:1px solid red; padding:10px; margin-bottom:10px;">
                                                            <div class="form-group">
                                                               <label>Auto Check API Status: 
                                                               @if($item->is_api_checked == '0')
                                                                  {{ $item->verification_status }}
                                                               @elseif( $item->verification_status == 'success')
                                                                  {{ $item->verification_status }}
                                                                  <div class="form-group">
                                                                     <span class="text-success" style="font-size: 18px;">Insuff Cleared <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                                  </span>
                                                                  </div>
                                                               @else
                                                                  {{ $item->verification_status }}
                                                               @endif
                                                               </label>
                                                               
                                                            </div>
                                                      </div>
                                                      <!-- ./ -->
                                                   </div>
                                                   @else
                                                            <!-- if manual  -->
                                                               @if( $item->verification_status == 'success')
                                                                  <div class="form-group">
                                                                     <span class="text-success" style="font-size: 18px;">Insuff Cleared <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                                  </span>
                                                                  </div>
                                                               @else
                                                                  {{ $item->verification_status }}
                                                               @endif
                                                            <!-- ./ if manual end -->

                                                   @endif
                                                   <!-- auto check insusff -->
                                                   <!-- clear insuff -->
                                                   @if( $item->verification_status == null)
                                                   <div class="row">
                                                      <div class="col-sm-6">
                                                         <div class="form-group">
                                                         <a href="javascript:;" class=" btn btn-warning itemMarkAsCleared" jaf-id="{{ base64_encode($item->id) }}" candidate-id="{{ base64_encode($candidate->id) }}" service-id="{{ base64_encode($item->service_id) }}" > Mark as Insuff cleared </a>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-6"></div>
                                                   </div>
                                                   @endif
                                                   <!-- clear insuff -->
                                             <!-- ./insufficiency -->
                                                   
                                                </div>
                                                <!-- attachment  -->
                                                <div class="col-md-6">
                                                   <p>Attachments</p>
                                                   {{-- <a class='btn-link clickSelectFile' add-id="{{$item->id}}" data-number='1' data-result='fileResult1' data-type='main' style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a>
                                                   <input type='file' class='fileupload' name="file-{{$item->id}}[]" id='file1-{{$item->id}}' multiple="multiple" style='display:none'/>
                                                   <div class='row fileResult' id="fileResult1-{{$item->id}}" style='min-height: 20px; margin-top: 20px;'> --}}
                                                   <?php $item_files = Helper::getJAFAttachFiles($item->id); //print_r($item_files); ?>
                                                   @foreach($item_files as $file)
                                                      @if($file['attachment_type'] == 'main')
                                                      <div class="image-area">
                                                         <img src="{{ $file['fileIcon'] }}" alt="Preview">
                                                         {{-- <a class="remove-image" data-id="{{ $file['file_id'] }}" href="javascript:;" style="display: inline;">×</a> --}}
                                                         <input type="hidden" name="fileID[]" value="{{ $file['file_id'] }}">
                                                      </div>
                                                      @endif
                                                   @endforeach
                                                   </div>
                                                {{-- </div> --}}
                                                   <!-- items loop closed -->
                                             </div>
                                             
                                          @endforeach
                                          <div class="row mt-3">
                                             <div class="col-md-6">
                                                <input class="btn btn-success" type="submit" value="Update" name="update">
                                             </div>  
                                          </div>  

                                          @else
                                          <div class="col-sm-12"> JAF data is not Completed! </div>
                                          @endif
                                       @else

                                          @if(count($jaf_items) >0)
                                             <?php $report_status = Helper::get_report_status($candidate->id); ?>

                                             {{-- {{dd($report_status)}} --}}
                                             @foreach($jaf_items as $item)
                                                {{-- @foreach ($checks as  $check)
                                                @if ($check->checks == $item->service_id) --}}
                                                   <?php
                                                      //get sale item count
                                                      $i=0;
                                                      $num ="";
                                                   ?>

                                                   <input type="hidden" value="{{ $item->id }}" name="jaf_id[]">
                                                   <div class="row" style="padding: 10px 0; margin-top:10px; border:1px solid #ddd;">
                                                      <div class="col-md-6">
                                                         
                                                         <h3 class=" mb-2 mt-2">Verification - {{ $item->service_name }} {{stripos($item->verification_type,'Manual')!==false ? ' - '.$item->check_item_number : ''}}</h3>
                                                         <p>Update the inputs </p>

                                                         <?php
                                                            $disabled='';
                                                            $readonly='';
                                                            $disabled_link='';
                                                            if($report_status!=NULL && ($report_status['status']=='completed' || $report_status=='interim'))
                                                            {
                                                               $disabled='disabled';
                                                               $readonly="readonly"; 

                                                               $disabled_link="disabled-link";
                                                            }
                                                         ?>

                                                         <!-- if check type is address  -->
                                                            @if($item->service_id == '1')
                                                               <div class="row" >
                                                                  <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                        <label>Address Type <span class="text-danger">*</span></label>
                                                                        <select class="form-control address-type-{{$item->id}}" name="address-type-{{$item->id}}" {{$disabled}}>
                                                                           <option value="">- Select Type -</option>
                                                                           <option value="current" @if($item->address_type !=null) @if($item->address_type=='current') selected @endif @endif > Current </option>
                                                                           <option value="permanent" @if($item->address_type !=null) @if($item->address_type=='permanent') selected @endif @endif >Permanent</option>
                                                                        </select>
                                                                        @if ($errors->has('address-type-'.$item->id))
                                                                           <div class="error pt-2 text-danger">
                                                                              {{ $errors->first('address-type-'.$item->id) }}
                                                                           </div>
                                                                        @endif
                                                                        </div>
                                                                  </div>
                                                               </div>
                                                            @endif
                                                            <!--  -->

                                                         <!--  -->
                                                         <?php 
                                                               $input_item_data = $item->form_data;
                                                               $input_item_data_array=[];
                                                               $input_item_data_array =  json_decode($input_item_data, true); 
                                                         ?>
                                                         @foreach($input_item_data_array as $key => $input)
                                                            <div class="row">
                                                               <div class="col-sm-12">
                                                                     <div class="form-group">
                                                                     <?php $key_val = array_keys($input); $input_val = array_values($input); 

                                                                           $university_board =  $readonly= "";
                                                                           $university_board_id="";
                                                                           $date_calss='';
                                                                           if($key_val[0] =='University Name / Board Name'){ 
                                                                              $university_board_id = "#searchUniversity_board";
                                                                              $university_board = "searchUniversity_board";
                                                                           }
                                                                           if($report_status!=NULL && ($report_status['status']=='completed' || $report_status=='interim'))
                                                                           {
                                                                              $readonly="readonly";
                                                                           }
                                                                        //name
                                                                        if($key_val[0]=='First Name' || $key_val[0]=='First name' || $key_val[0]=='first name'){ 
                                                                           $name = $candidate->first_name;
                                                                           $readonly ="readonly";
                                                                        }
                                                                        if($key_val[0]=='Last Name' || $key_val[0]=='Last name' || $key_val[0]=='last name'){ 
                                                                           $name = $candidate->last_name;
                                                                           //$readonly ="readonly";
                                                                        }
                                                                        if(stripos($key_val[0],'Date of Expire')!==false)
                                                                        {
                                                                           $date_calss = 'commonDatepicker';
                                                                        }

                                                                        $check_input=Helper::check_item_input_name($item->service_id,$candidate->business_id,$key_val[0]);
                                                                     ?>
                                                                     @if($item->service_id==17)
                                                                        @if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                                                                           <label>  {{ $key_val[0]}} @if($check_input!=NULL) <span class="text-danger">*</span> @endif </label>
                                                                           <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                           <select class="form-control {{$university_board }} service-input-value-{{$item->id.'-'.$i}} disabled-link" id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" readonly>
                                                                              <option value="">--Select--</option>
                                                                              <option @if(stripos($input_val[0],'personal')!==false) selected @endif value="personal">Personal</option>
                                                                              <option @if(stripos($input_val[0],'professional')!==false) selected @endif value="professional">Professional</option>
                                                                           </select>
                                                                        @else
                                                                           <label>  {{ $key_val[0]}} @if($check_input!=NULL) <span class="text-danger">*</span> @endif </label>
                                                                           <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                           <input class="form-control {{ $university_board }} {{$date_calss}}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
                                                                        @endif
                                                                     @elseif(stripos($item->type_name,'drug_test_5')!==false || stripos($item->type_name,'drug_test_6')!==false || stripos($item->type_name,'drug_test_7')!==false || stripos($item->type_name,'drug_test_8')!==false || stripos($item->type_name,'drug_test_9')!==false || stripos($item->type_name,'drug_test_10')!==false)
                                                                        @if(stripos($key_val[0],'Test Name')!==false)
                                                                           <label>  {{ $key_val[0]}} @if($check_input!=NULL) <span class="text-danger">*</span> @endif </label><br>
                                                                           <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                           <input class="form-control" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
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
                                                                           <label>  {{ $key_val[0]}} @if($check_input!=NULL) <span class="text-danger">*</span> @endif </label>
                                                                           <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                           <select class="form-control {{$date_calss.' '.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$disabled_link}}" name="service-input-value-{{ $item->id.'-'.$i }}" {{$readonly}}>
                                                                              <option value="">--Select--</option>
                                                                              <option @if(stripos($input_val[0],'positive')!==false) selected @endif value="positive">Positive</option>
                                                                              <option @if(stripos($input_val[0],'negative')!==false) selected  @endif value="negative">Negative</option>
                                                                           </select> 
                                                                        @else
                                                                           <label>  {{ $key_val[0]}} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
                                                                           <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                           <input class="form-control {{ $university_board }} {{$disabled_link}} {{$date_calss}}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">  
                                                                        @endif
                                                                     @else
                                                                        <label>  {{ $key_val[0]}} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
                                                                        <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                        <input class="form-control {{ $university_board }} {{$date_calss}}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
                                                                     @endif
                                                                     </div>
                                                               </div>
                                                            </div>
                                                            <?php $i++; ?>
                                                         @endforeach 
                                                         <!--   -->
                                                         {{-- <!-- insufficiency -->
                                                         <div class="row">
                                                         <div class="col-sm-12" style="border:1px solid #ddd; padding:10px; margin-bottom:10px;">
                                                            <p>Manual insufficiency Status</p>
                                                            <div class="col-sm-12">
                                                                  <div class="form-group">
                                                                  <div class="form-check">
                                                                     <label class="form-check-label">
                                                                        <input style="margin-top: 1px;" type="checkbox" class="form-check-input"  @if($item->is_insufficiency == 1) checked @endif name="insufficiency-{{ $item->id }}" >Mark as insufficiency
                                                                     </label>
                                                                  </div>
                                                                  </div>
                                                            </div>
                                                            <!--  -->
                                                            <div class="col-sm-12">
                                                                  <div class="form-group">
                                                                     <label>insufficiency Notes</label>
                                                                     <input type="text" class="form-control" name="insufficiency-notes-{{$item->id }}" value="{{ $item->insufficiency_notes}}">
                                                                  </div>
                                                            </div>
                                                            <!-- ./ -->
                                                            </div>
                                                         </div>
                                                         <!-- Autocheck Insuff -->
                                                         @if($item->verification_type =='Auto')
                                                         <div class="row">
                                                            <!--  -->
                                                            <div class="col-sm-12" style="border:1px solid red; padding:10px; margin-bottom:10px;">
                                                                  <div class="form-group">
                                                                     <label>Auto Check API Status:  
                                                                     @if($item->is_api_checked == '0')
                                                                        {{ $item->verification_status }}
                                                                     @elseif( $item->verification_status == 'success')
                                                                        {{ $item->verification_status }}
                                                                        <div class="form-group">
                                                                           <span class="text-success" style="font-size: 18px;">Insuff Cleared <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                                        </span>
                                                                        </div>
                                                                     @else
                                                                        {{ $item->verification_status }}
                                                                        
                                                                     @endif
                                                                     </label>
                                                                     
                                                                  </div>
                                                            </div>
                                                            <!-- ./ -->
                                                         </div>
                                                         @endif
                                                         <!-- auto check insusff -->
                                                         <!-- clear insuff -->
                                                         <div class="row">
                                                            <div class="col-sm-6">
                                                               <div class="form-group">
                                                               <a href="javascript:;" class=" btn btn-warning itemMarkAsCleared" jaf-id="{{ base64_encode($item->id) }}" candidate-id="{{ base64_encode($candidate->id) }}" service-id="{{ base64_encode($item->service_id) }}" > Mark as Insuff cleared </a>
                                                               </div>
                                                            </div>
                                                            <div class="col-sm-6"></div>
                                                               
                                                         </div>
                                                         <!-- clear insuff -->
                                                         <!-- ./insufficiency -->
                                                            --}}
                                                      </div>
                                                      <!-- attachment  -->
                                                      <div class="col-md-6">
                                                         <p>Attachments <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,gif,pdf are accepted "></i></p>
                                                         @if($candidate->is_all_insuff_cleared ==0 && ($report_status==NULL || $report_status['status']=='incomplete'))
                                                            <a class='btn-link clickSelectFile' add-id="{{$item->id}}" data-number='1' data-result='fileResult1' data-type='main' style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a>
                                                            <input type='file' class='fileupload' name="file-{{$item->id}}[]" id='file1-{{$item->id}}' multiple="multiple" style='display:none'/>
                                                         @endif
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
                                                                  @if($candidate->is_all_insuff_cleared ==0 && ($report_status==NULL || $report_status['status']=='incomplete'))
                                                                     <a class="remove-image" data-id="{{ $file['file_id'] }}" href="javascript:;" style="display: inline;">×</a>
                                                                  @endif
                                                                  <input type="hidden" name="fileID[]" value="{{ $file['file_id'] }}">
                                                               </div>
                                                               @endif
                                                            @endforeach
                                                            <p class="error-container text-danger" id="file-{{$item->id}}"></p>
                                                         </div>
                                                         <!-- items loop closed -->
                                                      </div>
                                                   </div>
                                                   {{-- @endif
                                                @endforeach --}}
                                             @endforeach
                                          
                                             <div class="row" style="padding: 10px 0; margin-top:10px; border:1px solid #ddd;">
                                                <div class="col-sm-6">
                                                   <div class="form-group">
                                                      <label>Digital Signature </label>
                                                      <div class="custom-file @if($report_status!=NULL && ($report_status['status']=='completed'  || $report_status['status']=='interim')) disabled-link @endif">
                                                         <input type="file" name="digital_signature" class="custom-file-input digital_signature" id="digital_signature" @if($report_status!=NULL && ($report_status['status']=='completed' || $report_status['status']=='interim')) disabled @endif>
                                                         <label class="custom-file-label" id="digital_label" for="digital_signature">{{$candidate->digital_signature!=NULL || $candidate->digital_signature!='' ? $candidate->digital_signature : 'Choose File...'}}</label>
                                                      </div>
                                                      {{-- <input class="form-control" type="file" name="digital_signature" id="digital_signature"> --}}
                                                      @if ($errors->has('digital_signature'))
                                                         <div class="error text-danger">
                                                            {{ $errors->first('digital_signature') }}
                                                         </div>
                                                         @endif
                                                   </div>
                                                </div>
                                                @if($candidate->digital_signature!=NULL || $candidate->digital_signature!='')
                                                   @php
                                                      $digital_url = '';
                                                      if(stripos($candidate->digital_signature_file_platform,'s3')!==false)
                                                      {
                                                         $filePath = 'uploads/signatures/';

                                                         $s3_config = S3ConfigTrait::s3Config();

                                                         $disk = \Storage::disk('s3');

                                                         $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                                               'Bucket'                     => \Config::get('filesystems.disks.s3.bucket'),
                                                               'Key'                        => $filePath.$candidate->digital_signature,
                                                               'ResponseContentDisposition' => 'attachment;'//for download
                                                         ]);

                                                         $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                                         $digital_url = $req->getUri();
                                                      }
                                                      else
                                                      {
                                                         $digital_url = url('uploads/signatures/'.$candidate->digital_signature);
                                                      }
                                                   @endphp
                                                   <div class="col-sm-6">
                                                      <div class="form-group">
                                                         <label for="company_logo"></label>
                                                         @if($report_status!=NULL && ($report_status['status']=='completed'  || $report_status['status']=='interim')) 
                                                            <span class="btn btn-link float-right text-dark close_btn">X</span>
                                                         @endif
                                                         <img id="preview_ds"  src="{{$digital_url}}" width="200" height="150"/>
                                                      </div>
                                                   </div>
                                                @else
                                                   <div class="col-sm-6">
                                                      <div class="form-group">
                                                         <label for="company_logo"></label>
                                                         @if($report_status!=NULL && ($report_status['status']=='completed'  || $report_status['status']=='interim')) 
                                                            <span class="d-none btn btn-link float-right text-dark close_btn">X</span>
                                                         @endif
                                                         <img id="preview_ds" width="200" height="150"/>
                                                      </div>
                                                   </div>
                                                @endif
                                             </div>
                                             @if ($report_status==NULL || $report_status['status']=='incomplete')
                                                <div class="row mt-3">
                                                   <div class="col-md-6">
                                                      <input class="btn btn-success" type="submit" value="Update" name="update">
                                                   </div>  
                                                </div>
                                             @endif
                                          @else
                                          <div class="col-sm-12"> JAF data is not Completed! </div>
                                          @endif
                                       
                                       @endif
                                       <!--  -->
                                 </form>
                              </div>
                           @else
                              <div class="col-sm-12 text-center" style="color:red"> JAF data is not Completed! </div>
                           @endif
                     </div>
                  </div>
                 
               </div>
            </div>
            
         </div>
      </div>
   </div>
</div>

<div class="modal"  id="preview">
   <div class="modal-dialog modal-lg" style="max-width: 80% !important;">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">Report Preview</h4>
            <button type="button" class="close" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         
            <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
               <iframe 
                   src="" 
                   style="width:100%; height:620px; " 
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
<!-- Script -->
<script type="text/javascript">

$(document).ready(function(){

      $('.reportsPreviewBox').click(function(){
         //   alert('ass');
            var report_id = $(this).attr('data-id');

            document.getElementById('preview_pdf').src="{{ url('/') }}"+"/my/candidate/report/preview/"+report_id;
         
            // alert(business_id);
            $('#preview').toggle();
      });

       $('.close').click(function(){
           $('#preview').hide();
       });
       $('.back').click(function(){
           $('#preview').hide();
       });

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
            //    type: 'POST',
            //    url: "{{ url('my/jaf/remove/file') }}",
            //    data: fd,
            //    processData: false,
            //    contentType: false,
            //    success: function(data) {
            //       console.log(data);
            //       if (data.fail == false) {
            //          //reset data
            //          $('.fileupload').val("");
            //          //append result
            //          $(current).parent('.image-area').detach();
            //       }
            //       else if(data.fail == true)
            //       {
            //          toastr.error('You are not authorise to delete this file ! This file was uploaded by an Admin');
            //       } 
            //       else {
                  
            //       console.log("file error!");
                  
            //       }
            //    },
            //    error: function(error) {
            //       console.log(error);
            //       // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
            //    }
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
                                url: "{{ url('/my/jaf/remove/file') }}",
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
      


      $(document).on('click', '.itemMarkAsCleared', function (event) {
         
         var candidate_id = $(this).attr('candidate-id');
         var jaf_id       = $(this).attr('jaf-id');
         var service_id   = $(this).attr('service-id');
         if(confirm("Are you sure want clear insuff status?")){
         $.ajax({
            type:'GET',
            url: "{{route('/candidates/jaf/clearCheckInsuff')}}",
            data: { 'candidate_id':candidate_id,'jaf_item_id':jaf_id,'service_id':service_id},        
            success: function (response) {        
            console.log(response);
            
                  if (response.status=='ok') {            
                     
                     toastr.success("Insuff is Cleared successfully");
                     // redirect to google after 5 seconds
                     window.setTimeout(function() {
                        location.reload(); 
                     }, 2000);
               
                  } else {

                     toastr.success("Check Insuff Status");
                     // redirect to google after 5 seconds
                     window.setTimeout(function() {
                        location.reload(); 
                     }, 2000);
                  }
            },
            error: function (xhr, textStatus, errorThrown) {
                  alert("Error: " + errorThrown);
            }
         });

         }
         return false;
      });

      // clear all 
      $(document).on('click', '.all_insuff_clear_btn', function (event) {
      
         var candidate_id = $(this).attr('data-id');
         if(confirm("Are you sure want clear insuff staus?")){
         $.ajax({
            type:'GET',
            url: "{{route('/candidates/jaf/clearAllChecksInsuff')}}",
            data: {'candidate_id':candidate_id},        
            success: function (response) {        
            console.log(response);
            
                  if (response.status=='ok') {            
                     
                     toastr.success("Insuff is Cleared successfully");
                     // redirect to google after 5 seconds
                     window.setTimeout(function() {
                        location.reload(); 
                     }, 2000);

                  } else {
                     
                  }
            },
            error: function (xhr, textStatus, errorThrown) {
                  alert("Error: " + errorThrown);
            }
         });
         return false;
         }
      });

});

   $('#digital_signature').change(function(){
            var file = this.files[0].name;
            $('#preview_ds').attr('src','')
            let reader = new FileReader();
            reader.onload = (e) => { 
               $('.close_btn').removeClass('d-none');
               $('#preview_ds').attr('src', e.target.result); 
            }
            reader.readAsDataURL(this.files[0]);

            $('#digital_label').html(file);
            
   });
   $(document).on('click','.close_btn',function(){
         $('#preview_ds').removeAttr('src'); 
         $(this).addClass('d-none');
         $('#digital_label').html('Choose File...');
         $(this).parents().eq(2).find('#digital_signature').val("");
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
            url: "{{ url('my/jaf/upload/file') }}",
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
            // $.each(data.data, function(key, value) {
            //       $("#"+fileResult+"-"+dynamicID).append("<div class='image-area'><img src='"+value.filePrev+"'  alt='Preview'><a class='remove-image' data-id='"+value.file_id+"' href='javascript:;' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+value.file_id+"'></div>");
            // });
                  
            } else {
               $("#fileUploadProcess").html("");
               //alert("Please upload valid file! allowed file type, Image JPG, PNG etc. ");
               swal({
                  title: "Oh no!",
                  text: 'Please upload valid file! allowed file type, Image JPG, PNG, PDF etc.',
                  type: 'error',
                  buttons: true,
                  dangerMode: true,
                  confirmButtonColor:'#003473'
               });
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

   window.onscroll = function() {stickyFun()};

var navbar = document.getElementById("myPillTab");
var sticky = navbar.offsetTop;

function stickyFun() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}

</script>

@endsection
