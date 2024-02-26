@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
<!-- ============ Body content start ============= -->
<div class="main-content">
    <div class="row">
        <div class="col-sm-11">
            <ul class="breadcrumb">
            <li><a href="{{ url('/home') }}">Dashboard</a></li>
            <li><a href="{{ url('/candidates') }}">Candidate</a></li>
            <li>Detail</li>
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
      <div class="card-body" style="padding:0px">
         <div class="row">
            <div class="col-md-3 col-12">
               <div class="span10 offset1">
                  <div id="modalTab">
                     <div class="tab-content">
                        <div class="tab-pane active" id="about">
                           <img src="{{ asset('admin/images/profile-default-avtar.jpg') }}" name="aboutme" width="140" height="140" border="0" class="img-circle">
                           <p class="m-0 text-24"> {{ $candidate->name}} <a class="text-success mr-2" href="#"><i class="nav-icon i-Pen-2 font-weight-bold" style="font-size: 10px;"></i></a></p>
                           <p class="text-muted m-0"> &nbsp; </p>
                           <ul class="nav nav-tabs profile-nav mb-4" id="profileTab" role="tablist">
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-tasks"> </i><br> Note</a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-envelope"></i><br> Email</a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-phone-square" aria-hidden="true"></i> <br> Call </a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-plus" aria-hidden="true"></i><br> Log </a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab" href="{{ url('/candidates/task',['id'=> base64_encode($candidate->id)]) }}"> <i class="fa fa-tasks" aria-hidden="true"></i><br> Task  </a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-calendar" aria-hidden="true"></i> <br> Meet </a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-window-maximize" aria-hidden="true"></i> <br> Report </a></li>

                           </ul>
                           <ul style="list-style: none; text-align: left; padding: 0">
                              
                           </ul>
                           @include('admin.candidates.profile-info')
                        </div>
                     </div>
                  </div>
               </div>
            </div> 
            <div class="col-md-9 col-12" style="background: #f6f8fc;">
               <h4 class="card-title mb-3"> </h4>
               <ul class="nav nav-pills" id="myPillTab" role="tablist" style="border-bottom: 1px solid #cdd1d8;">
                  <li class="nav-item"><a class="nav-link " id="home-icon-pill" href="{{ url('/candidates/show',['id'=> base64_encode($candidate->id)]) }}" > Activity </a></li>
                  <li class="nav-item"><a class="nav-link" id="profile-icon-pill"  href="{{ url('/candidates/jaf-info',['id'=> base64_encode($candidate->id)]) }}" > JAF </a></li>
                  <li class="nav-item"><a class="nav-link" id="profile-icon-pill" data-toggle="pill" href="#profilePIll" role="tab" > Notes </a></li>
                  <li class="nav-item"><a class="nav-link" id="contact-icon-pill" data-toggle="pill" href="#contactPIll" role="tab" > Emails </a></li>
                  <li class="nav-item"><a class="nav-link" id="contact-icon-pill" data-toggle="pill" href="#contactPIll" role="tab" > Call </a></li>
                  <li class="nav-item"><a class="nav-link" id="contact-icon-pill" href="{{ url('/candidates/task',['id'=> base64_encode($candidate->id)]) }}" > Task </a></li>
                  <li class="nav-item"><a class="nav-link" id="contact-icon-pill" data-toggle="pill" href="#contactPIll" role="tab" > Meeting </a></li>
                  <li class="nav-item"><a class="nav-link active show" id="contact-icon-pill"  href="{{url('/candidate/profile/report',['id'=> base64_encode($candidate->id)])}}"  > Report </a></li>

               </ul>
               <div class="tab-content" id="myPillTabContent">
                  <div class="row" style="margin-bottom:15px">
                     <div class="col-md-2">
                        <div class="search-bar" style="padding: 10px 0px;">
                           Filter By :
                        </div>
                     </div>
                     <div class="col">
                        <button class="btn  btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #6a88c7!important;">  Filter Activity   </button>
                        <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                        <button class="btn  btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #6a88c7!important;">  All Users </button>
                        <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                        <button class="btn  btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #6a88c7!important;">  All Items </button>
                        <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                     </div>
                     <div class="search-bar" style="padding: 10px 0px;">
                        <i class="search-icon text-muted i-Magnifi-Glass1"></i>                   
                     </div>
                  </div>
                  <div class="tab-pane fade active show" id="homePIll" role="tabpanel" aria-labelledby="home-icon-pill">
                     <div class="inbox-main-sidebar-container sidebar-container" data-sidebar-container="main">
                        <div class="inbox-main-content sidebar-content" data-sidebar-content="main">
                           <!-- SECONDARY SIDEBAR CONTAINER-->
                           <div class="inbox-secondary-sidebar-container box-shadow-1 sidebar-container" data-sidebar-container="secondary">
                              <!-- Secondary Inbox sidebar-->
                              <div class="inbox-secondary-sidebar perfect-scrollbar rtl-ps-none ps sidebar" data-sidebar="secondary" style="left: 0px;">
                                 <i class="sidebar-close i-Close" data-sidebar-toggle="secondary"></i>
                                 <div class="mail-item">
                                @if ($report)
                                    
                               
                                    <form class="mt-2 col-12" method="post" action="{{ url('/candidate/report/update',['id'=> base64_encode($candidate->id)]) }}" id="report_form">
                                        @csrf
                                        <!-- candidate info -->
                                        <input type="hidden" name="report_id" value="{{ base64_encode($report_id) }}">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h4 class="card-title mb-3 mt-2">Candidate : <b> {{ $candidate->id.'-'.$candidate->first_name.' '.$candidate->last_name }} </b></h4>
                                               
                        
                        
                                            </div>
                                        </div>
                                        <div class="row">
                                             <div class="col-md-12">
                                                 <hr>
                                             </div>
                                        </div>   
                                        <!-- service item -->
                                        @if( count($report_items) >0   )
                                        @foreach($report_items as $item)
                                        <!--  -->
                                          <div class="row" style="border: 1px solid #ddd; margin:10px 0; padding: 10px 0;">
                                             <div class="col-md-6">
                                                <h3 class=" mb-2 mt-2">Verification - {{$item->service_name.' -'.$item->service_item_number}} </h3>
                                                
                                                <p>Provide the approval and comments (Remarks: Checked = Yes, Left Blank = -)</p>
                                                <!--  -->
                                                <?php 
                                                    $input_item_data = $item->jaf_data;
                                                    $input_item_data_array =  json_decode($input_item_data, true); 
                                                    $i=0;
                                                ?>
                                                @foreach($input_item_data_array as $key => $input)
                                                <!-- start row -->
                                                    
                                                    <div class="row" >
                                                        <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <?php $key_val = array_keys($input); 
                                                            // print_r($key_val);
                                                            $input_val = array_values($input); ?>
                                                            <label>  {{ $key_val[0] }} </label>
                                                            <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                            <input class="form-control " type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
                                                            </div>
                                                        </div>
                                                        <!-- Remarks -->
                                                        <div class="col-sm-1">
                                                            <div class="form-group">
                                                            <label> Remarks </label>
                                                                <div class="form-check">
                                                                <label class="form-check-label">
                                                                <input type="checkbox" name="remarks-input-checkbox-{{ $item->id.'-'.$i}}"  @if(in_array('remarks', $key_val)) @if($input['remarks']=='Yes') checked @endif @endif class="form-check-input" >
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
                                                                    if(array_key_exists('remarks_message', $input_item_data_array[$i]))
                                                                    {
                                                                        $remarks_message =  $input_item_data_array[$i]['remarks_message'];
                                                                    }
                                                                ?>
                                                                <input type="text" name="remarks-input-value-{{ $item->id.'-'.$i}}"  class="form-control" value="{{ $remarks_message }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                     <!-- check output -->
                                                     <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                <label class="checkbox-inline">
                                                                <?php
                                                                    $is_executive_summary ="0";
                                                                    $is_report_output ="0"; 
                                                                    if(array_key_exists('is_executive_summary', $input_item_data_array[$i]))
                                                                    {
                                                                        $is_executive_summary =  $input_item_data_array[$i]['is_executive_summary'];
                                                                    }
                                                                    if(array_key_exists('is_report_output', $input_item_data_array[$i]))
                                                                    {
                                                                        $is_report_output =  $input_item_data_array[$i]['is_report_output'];
                                                                    }
                                                                ?>
                                                                    <input type="checkbox" name="executive-summary-{{ $item->id .'-'.$i}}" @if($is_executive_summary == '1')  checked @endif > Executive Summary Output (if yes: Check Mark)
                                                                </label>
                                                                </div>
                                                                <div class="form-group">
                                                                <label class="checkbox-inline">
                                                                    <input type="checkbox" name="table-output-{{ $item->id.'-'.$i }}" @if($is_report_output == '1')  checked @endif > Check's Table Output (if yes: Check Mark)
                                                                </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                
                                                            </div>
                                                        </div>
                                                    <!-- ./check outputs -->
                                                    <!-- end row -->
                                                <?php $i++; ?>
                                                @endforeach
                                                <!-- comment  -->
                                                    <div class="row">
                                                        <div class="col-sm-12"> 
                                                            <h4 class="card-title mb-2 mt-2">Approval Inputs  </h4>
                                                        </div>   
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label> Verified By</label>
                                                                <input class="form-control " type="text" name="verified_by-{{ $item->id }}" value="{{ $item->verified_by }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label> Comments</label>
                                                                <textarea class="form-control " type="text" name="comments-{{ $item->id }}" >{{ $item->comments }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6" style="">
                                                            <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon3">Annexure Value</span>
                                                            </div>
                                                                <input type="text" class="form-control" name="annexure_value-{{$item->id}}"  value="{{ $item->annexure_value }}" aria-describedby="basic-addon3">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Additional Comments</label>
                                                                <textarea class="form-control " type="text" name="additional-comments-{{ $item->id }}" > {{ $item->additional_comments }} </textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--  -->
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Aproval Status</label>
                                                                <select class="form-control approval_status" name="approval-status-{{ $item->id }}" >
                                                                    @foreach($status_list as $status)
                                                                    <option data-id="{{ $item->id }}" value="{{ $status->id}}" @if($status->id == $item->approval_status_id) selected @endif> {{ $status->name}} </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="new-tag"> </div>
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
                                                                <input type="text" class="form-control" name="district_court_name-{{$item->id}}"  value="{{ $item->district_court_name }}" aria-describedby="basic-addon3">
                                                            </div>
                                                        </div>
                                                        <!--  -->
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <input type="text" name="district_court_result-{{$item->id}}" class="form-control" value="{{ $item->district_court_result }}">
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
                                                                <input type="text" class="form-control" name="high_court_name-{{$item->id}}" value="{{ $item->high_court_name }}" aria-describedby="basic-addon3">
                                                            </div>
                                                        </div>
                                                        <!--  -->
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <input type="text" name="high_court_result-{{$item->id}}" class="form-control" value="{{ $item->high_court_result }}">
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
                                                                <input type="text" name="supreme_court_name-{{$item->id}}" class="form-control" value="Supreme Court of India, New Delhi" readonly>
                                                            </div>
                                                        </div>
                                                        <!--  -->
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <input type="text" name="supreme_court_result-{{$item->id}}" class="form-control" value="{{ $item->supreme_court_result }}">
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
                                                            <label class="checkbox-inline text-danger">
                                                                <input type="checkbox" name="report-output-{{ $item->id }}" @if($item->is_report_output == '1')  checked @endif >  Include in Report Output (if yes: Check Mark)
                                                            </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--  -->
                        
                                             </div>
                                             <!-- attachment  -->
                                             <div class="col-md-6">
                                                <p>Attachments</p>
                                                <a class='btn-link clickSelectFile' add-id="{{$item->id}}" data-number='1' data-result='fileResult1' data-type='main' style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a>
                                                <input type='file' class='fileupload' name="file-{{$item->id}}[]" id='file1-{{$item->id}}' multiple="multiple" style='display:none'/>
                                                <div class='row fileResult' id="fileResult1-{{$item->id}}" style='min-height: 20px; margin-top: 20px;'>
                                                <?php $item_files = Helper::getReportAttachFiles($item->id); //print_r($item_files); ?>
                                                @foreach($item_files as $file)
                                                    @if($file['attachment_type'] == 'main')
                                                    <div class="image-area">
                                                        <img src="{{ $file['filePath'] }}" alt="Preview">
                                                        <a class="remove-image" data-id="{{ $file['file_id'] }}" href="javascript:;" style="display: inline;">×</a>
                                                        <input type="hidden" name="fileID[]" value="{{ $file['file_id'] }}">
                                                    </div>
                                                    @endif
                                                @endforeach
                                                </div>
                                                <p class="mt-2" style="margin-bottom:1px">Add Supportings</p>
                                                <a class='btn-link clickSelectFile' add-id="{{$item->id}}" data-number='2' data-result='fileResult2' data-type='supporting' style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a>
                                                <input type='file' class='fileupload' name="file-{{$item->id}}[]" id='file2-{{$item->id}}' multiple="multiple" style='display:none'/>
                                                <div class='row fileResult' id="fileResult2-{{$item->id}}" style='min-height: 20px; margin-top: 20px;'>
                                                <?php $item_files = Helper::getReportAttachFiles($item->id); //print_r($item_files); ?>
                                                @foreach($item_files as $file)
                                                    @if($file['attachment_type'] == 'supporting')
                                                    <div class="image-area">
                                                        <img src="{{ $file['filePath'] }}" alt="Preview">
                                                        <a class="remove-image" data-id="{{ $file['file_id'] }}" href="javascript:;" style="display: inline;">×</a>
                                                        <input type="hidden" name="fileID[]" value="{{ $file['file_id'] }}">
                                                    </div>
                                                    @endif
                                                @endforeach
                                                </div>
                        
                                             </div>
                                          </div>
                                          
                                        
                                         @endforeach
                                         @endif
                                         
                                          <button type="submit" class="btn btn-primary mt-3">Update</button>
                                          
                                       </form>
                                       @else
                                       <div class="col-sm-12" style="color: red" > <center><strong>Report is not Completed ! </strong></center></div>

                                       @endif
                                </div>
                                
                           
                           </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="profilePIll" role="tabpanel" aria-labelledby="profile-icon-pill">
                  </div>
                  <div class="tab-pane fade" id="contactPIll" role="tabpanel" aria-labelledby="contact-icon-pill"> 
                  </div>
               </div>
            </div>
            
         </div>
      </div>
   </div>
</div>
@endsection
