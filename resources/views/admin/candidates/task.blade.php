@extends('layouts.admin')
<style>
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
 </style>
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
                           <p class="m-0 text-24"> {{ $candidate->name}} 
                            {{-- <a class="text-success mr-2" href="#"><i class="nav-icon i-Pen-2 font-weight-bold" style="font-size: 10px;"></i></a> --}}
                        </p>
                           <p class="text-muted m-0"> &nbsp; </p>
                           <ul class="nav nav-tabs profile-nav mb-4" id="profileTab" role="tablist">
                              <li class="nav-item"><a class="nav-link" id="timeline-tab" href="{{ url('/candidates/notes',['id'=> base64_encode($candidate->id)]) }}"> <i class="fa fa-tasks"> </i><br> Note</a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-envelope"></i><br> Email</a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-phone-square" aria-hidden="true"></i> <br> Call </a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-plus" aria-hidden="true"></i><br> Log </a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab" href="{{ url('/candidates/task',['id'=> base64_encode($candidate->id)]) }}"> <i class="fa fa-tasks" aria-hidden="true"></i><br> Task  </a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-calendar" aria-hidden="true"></i> <br> Meet </a></li>
                              {{-- <li class="nav-item"><a class="nav-link" id="timeline-tab" href="{{url('/candidate/profile/report',['id'=> base64_encode($candidate->id)])}}"> <i class="fa fa-window-maximize" aria-hidden="true"></i> <br> Report </a></li> --}}
                            @if ($report)
                              @if ($report->report_jaf_data != null)
                                <li class="nav-item"><a class="nav-link reportsPreviewBox" id="timeline-tab" data-id="{{ base64_encode($report->id) }}"  href="#"> <i class="fa fa-window-maximize" aria-hidden="true"></i> <br> Report </a></li>
                              @endif
                            @endif
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
                  <li class="nav-item"><a class="nav-link" id="notes-icon-pill"  href="{{ url('/candidates/notes',['id'=> base64_encode($candidate->id)]) }}" role="tab" > Notes </a></li>
                  <li class="nav-item"><a class="nav-link" id="contact-icon-pill" data-toggle="pill" href="#contactPIll" role="tab" > Emails </a></li>
                  <li class="nav-item"><a class="nav-link" id="contact-icon-pill" href="{{ url('/candidates/call',['id'=> base64_encode($candidate->id)]) }}" role="tab" > Call </a></li>
                  <li class="nav-item"><a class="nav-link active show" id="contact-icon-pill"  href="{{ url('/candidates/task',['id'=> base64_encode($candidate->id)]) }}" > Task </a></li>
                  <li class="nav-item"><a class="nav-link" id="contact-icon-pill" data-toggle="pill" href="#contactPIll" role="tab" > Meeting </a></li>
                  {{-- <li class="nav-item"><a class="nav-link" id="contact-icon-pill"  href="{{url('/candidate/profile/report',['id'=> base64_encode($candidate->id)])}}"  > Report </a></li>
                   --}}
                   @if ($report)
                   @if ($report->report_jaf_data != null)
                        <li class="nav-item"><a class="nav-link reportsPreviewBox" id="contact-icon-pill" data-id="{{ base64_encode($report->id) }}"  href="#"> Report </a></li>
                    @endif
                    @endif
               </ul>
               <div class="tab-content" id="myPillTabContent">
                  {{-- <div class="row" style="margin-bottom:15px">
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
                  </div> --}}
                  <div class="tab-pane fade active show" id="homePIll" role="tabpanel" aria-labelledby="home-icon-pill">
                     <div class="inbox-main-sidebar-container sidebar-container" data-sidebar-container="main">
                        <div class="inbox-main-content sidebar-content" data-sidebar-content="main">
                           <!-- SECONDARY SIDEBAR CONTAINER-->
                           <div class="inbox-secondary-sidebar-container box-shadow-1 sidebar-container" data-sidebar-container="secondary">
                              <!-- Secondary Inbox sidebar-->
                              <div class="inbox-secondary-sidebar perfect-scrollbar rtl-ps-none ps sidebar" data-sidebar="secondary" style="z-index:0;left: 0px;">
                                 <i class="sidebar-close i-Close" data-sidebar-toggle="secondary"></i>
                                 <div class="mail-item">
                                    <div class="avatar" style="width:10%"> <i class="fa fa-user" aria-hidden="true"></i></div>
                                    <div class="col-xs-6 details">
                                       <span class="name text-muted"><b> {{$candidate->name}} </b></span>
                                      @foreach ($users as $user)
                                      @if ($candidate->created_by == $user->id)
                                      <p class="m-0"> Created By {{$user->name}} </b></span>   
                                      @endif   
                                      @endforeach
                                       
                                       
                                          
                                    </div>
                                    <div class="col-xs-4 date"><span class="text-muted">Created Date & Time: {{ date('d-m-Y H:i:s', strtotime($candidate->created_at))}}  </span></div>
                                 </div>
                                 <div class="mail-item">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                        <table class="table table-bordered table-hover candidatesTable ">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">#</th>
                                                                    <th scope="col">Name</th>
                                                                    <th scope="col">Email ID</th>
                                                                    <th scope="col">Phone Number</th>
                                                                    <th scope="col">SLA</th>
                                                                    <th scope="col">JAF Status</th>
                                                                    <th scope="col">Auto-Check Status</th>
                                                                    <th scope="col">Created at</th>
                                                                    <th scope="col">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="candidateList">
                                                                <?php $users = Auth::user();
                                                                // dd($users);
                                                                ?>
                                                                @if ($users->user_type == 'customer')
                                                                    @if( count($items) > 0 )
                                                                    
                                                                        
                                                                        {{-- @endif --}}
                                                                        @foreach($items as $item)
                                                                            <tr data-id="{{ base64_encode($item->candidate_id) }}">
                                                                                <th scope="row">{{ $item->id }}</th>
                                                                                <td>{{ $item->name }}<br>
                                                                                    <small class="text-muted">Customer: <b>{{Helper::company_name($item->business_id)}}</b></small>
                                                                                </td>
                                                                                <td>{{ $item->email }}</td>
                                                                                <td>{{ $item->phone }}</td>
                                                                                <td>{{ Helper::get_sla_name($item->sla_id)}}</td>
                                                                                <td>
                                                                                    @if($item->jaf_status == 'pending')
                                                                                        <span class="badge badge-danger">Not Filled</span><br>
                                                                                        <a href="{{ url('candidates/jaf-fill',['case_id'=>  base64_encode($item->job_id) ]) }}" style='font-size:14px;' class="bnt-link">JAF Link</a>
                                                                                    @endif
                                        
                                                                                    @if($item->jaf_status == 'filled')
                                                                                        <span class="badge badge-success"> <a style="color:#fff;" href="{{ url('/candidates/jaf-info',['id'=> base64_encode($item->candidate_id)]) }}"> Filled </a></span><br>
                                                                                        <!-- get report status -->
                                                                                        <?php $report_status = Helper::get_report_status($item->candidate_id); ?>
                                                                                        @if($report_status != NULL && $report_status['status'] =='completed')    
                                                                                            
                                                                                            <a href="javascript:;" style="font-size: 14px;;" class="btn-link reportExportBox" data-id="{{  base64_encode($report_status['id']) }}" > PDF Report</a>
                                                                                        @else 
                                                                                            <a href="{{ url('candidate/report-generate',['id'=>  base64_encode($item->candidate_id) ]) }}" style='font-size:14px;' class="bnt-link">Generate Report</a>  
                                                                                        @endif
                                        
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    {!! Helper::get_jaf_auto_check_api_status($item->candidate_id) !!}
                                                                                </td>
                                                                                <td>{{ date('d-m-Y',strtotime($item->created_at)) }}</td>
                                                                                <td>
                                                                                {{-- <a href="{{ route('/candidates/show',['id'=>base64_encode($item->id)]) }}">
                                                                                <button class="btn btn-primary btn-sm" type="button"> <i class='fa fa-eye'></i> View</button>
                                                                                </a> --}}
                                        
                                                                                <a href="{{ route('/candidates/edit',['id'=>base64_encode($item->id)]) }}">
                                                                                <button class="btn btn-info btn-sm" type="button"> <i class='fa fa-edit'></i> Edit</button>
                                                                                </a>
                                                                                    @if($item->jaf_status == 'pending' || $item->jaf_status=='draft')
                                                                                    
                                                                                        <button class="btn btn-danger btn-sm deleteRow" type="button" data-id="{{ base64_encode($item->candidate_id) }}"> <i class='fa fa-trash'></i> Delete</button>
                                                                                
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr>
                                                                            <td scope="row" colspan="9"><h3 class="text-center">No record!</h3></td>
                                                                        </tr>
                                                                    @endif
                                                                @else
                                                               
                                                               
                                                                @if( count($permissions) > 0 )
                                                               
                                                                    @foreach ($items as $item)
                                                                    
                                                                
                                                                        @foreach ($permissions as $permission)
                                                                            @if ($permission->candidate_id == $item->candidate_id && $item->jaf_status == 'pending')
                                                                                    
                                                                                
                                                                                <tr data-id="{{ base64_encode($item->candidate_id) }}">
                                                                                    <th scope="row">{{ $item->id }}</th>
                                                                                    <td>{{ $item->name }}<br>
                                                                                        <small class="text-muted">Customer: <b>{{Helper::company_name($item->business_id)}}</b></small>
                                                                                    
                                                                                        
                                                                                    </td>
                                                                                    <td>{{ $item->email }}</td>
                                                                                    <td>{{ $item->phone }}</td>
                                                                                    <td>{{ Helper::get_sla_name($permission->sla_id)}}</td>
                                                                                    <td>
                                                                                        @if($permission->jaf_status == 'pending')
                                                                                            <span class="badge badge-danger">Not Filled</span><br>
                                                                                            <a href="{{ url('candidates/jaf-fill',['case_id'=>  base64_encode($permission->job_id) ]) }}" style='font-size:14px;' class="bnt-link">JAF Link</a>
                                                                                        @endif
                                                    
                                                                                        @if($item->jaf_status == 'filled')
                                                                                            <span class="badge badge-success"> <a style="color:#fff;" href="{{ url('/candidates/jaf-info',['id'=> base64_encode($permission->candidate_id)]) }}"> Filled </a></span><br>
                                                                                            <!-- get report status -->
                                                                                            <?php $report_status = Helper::get_report_status($item->candidate_id); ?>
                                                                                            @if($report_status != NULL && $report_status['status'] =='completed')    
                                                                                                
                                                                                                <a href="javascript:;" style="font-size: 14px;;" class="btn-link reportExportBox" data-id="{{  base64_encode($report_status['id']) }}" > PDF Report</a>
                                                                                            @else 
                                                                                                <a href="{{ url('candidate/report-generate',['id'=>  base64_encode($item->candidate_id) ]) }}" style='font-size:14px;' class="bnt-link">Generate Report</a>  
                                                                                            @endif
                                                    
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        {!! Helper::get_jaf_auto_check_api_status($item->candidate_id) !!}
                                                                                    </td>
                                                                                    <td>{{ date('d-m-Y',strtotime($item->created_at)) }}</td>
                                                                                    <td>
                                                                                    {{-- <a href="{{ route('/candidates/show',['id'=>base64_encode($item->id)]) }}">
                                                                                    <button class="btn btn-primary btn-sm" type="button"> <i class='fa fa-eye'></i> View</button>
                                                                                    </a>
                                                                                        --}}
                                                                                    <a href="{{ route('/candidates/edit',['id'=>base64_encode($item->id)]) }}">
                                                                                    <button class="btn btn-info btn-sm" type="button"> <i class='fa fa-edit'></i> Edit</button>
                                                                                    </a>
                                                                                        @if($item->jaf_status == 'pending')
                                                                                        
                                                                                            <button class="btn btn-danger btn-sm deleteRow" type="button" data-id="{{ base64_encode($item->candidate_id) }}"> <i class='fa fa-trash'></i> Delete</button>
                                                                                    
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                            
                                                                            @endif
                                                                        @endforeach 
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td scope="row" colspan="9"><h3 class="text-center">No record!</h3></td>
                                                                    </tr>
                                                                 @endif
                                                            @endif
                                                            </tbody>
                                                        </table>
                                                        </div>
                                                     </div>
                                                  </div>
                                        
                                                 
                                 </div>
                              
                                 <div class="row">
                                    <div class="col-sm-12 col-md-5">
                                        <div class="dataTables_info" role="status" aria-live="polite"></div>
                                    </div>
                                    <div class="col-sm-12 col-md-7">
                                      <div class=" paging_simple_numbers" >            
                                          {!! $items->render() !!}
                                      </div>
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
                  <div class="tab-pane fade" id="notesPIll" role="tabpanel" aria-labelledby="notes-icon-pill"> 
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
<script>
$(document).ready(function(){
    $('.reportsPreviewBox').click(function(){
        //   alert('ass');
            var report_id = $(this).attr('data-id');

            document.getElementById('preview_pdf').src="{{ url('/') }}"+"/candidate/report/preview/"+report_id;
        
            // alert(business_id);
            $('#preview').toggle();
    });

    $('.close').click(function(){
        $('#preview').hide();
    });
    $('.back').click(function(){
        $('#preview').hide();
    });
});
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
