@extends('layouts.client')
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
                           {{-- <hr style="margin-top:10px; margin-bottom:10px;"> --}}
                           <!-- <ul class="nav nav-tabs profile-nav mb-4" id="profileTab" role="tablist">
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-tasks"> </i><br> Note</a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-envelope"></i><br> Email</a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-phone-square" aria-hidden="true"></i> <br> Call </a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-plus" aria-hidden="true"></i><br> Log </a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab" href="{{ url('/candidates/task',['id'=> base64_encode($candidate->id)]) }}"> <i class="fa fa-tasks" aria-hidden="true"></i><br> Task  </a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-calendar" aria-hidden="true"></i> <br> Meet </a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab" href="{{url('/candidate/profile/report',['id'=> base64_encode($candidate->id)])}}"> <i class="fa fa-window-maximize" aria-hidden="true"></i> <br> Report </a></li>

                           </ul> -->
                           <ul style="list-style: none; text-align: left; padding: 0">
                              
                           </ul>
                          @include('clients.candidates.profile-info')
                              <!-- <label>   </label> -->
                        </div>
                     </div>
                  </div>
               </div>
            </div> 
            <div class="col-md-9 col-12" style="background: #f6f8fc;">
               <h4 class="card-title mb-3"> </h4>
               <ul class="nav nav-pills" id="myPillTab" role="tablist" style="border-bottom: 1px solid #cdd1d8;">
                  <li class="nav-item"><a class="nav-link active show" id="home-icon-pill" href="{{ url('/my/candidates/show',['id'=> base64_encode($candidate->id)]) }}" > Activity </a></li>
                  <li class="nav-item"><a class="nav-link" id="profile-icon-pill"  href="{{ url('my/candidates/jaf-info',['id'=> base64_encode($candidate->id)]) }}" > JAF </a></li>
                  <li class="nav-item"><a class="nav-link" id="notes-icon-pill"  href="{{ url('/my/candidates/notes',['id'=> base64_encode($candidate->id)]) }}" role="tab" > Notes </a></li>
                  <!-- <li class="nav-item"><a class="nav-link" id="profile-icon-pill" data-toggle="pill" href="#profilePIll" role="tab" > Notes </a></li>
                  <li class="nav-item"><a class="nav-link" id="contact-icon-pill" data-toggle="pill" href="#contactPIll" role="tab" > Emails </a></li>
                  <li class="nav-item"><a class="nav-link" id="contact-icon-pill" data-toggle="pill" href="#contactPIll" role="tab" > Call </a></li>
                  <li class="nav-item"><a class="nav-link" id="contact-icon-pill"  href="{{ url('/candidates/task',['id'=> base64_encode($candidate->id)]) }}"  > Task </a></li>
                  <li class="nav-item"><a class="nav-link" id="contact-icon-pill" data-toggle="pill" href="#contactPIll" role="tab" > Meeting </a></li> -->
                @if ($report != '')
                <li class="nav-item"><a class="nav-link reportsPreviewBox" id="contact-icon-pill" data-id="{{ base64_encode($report->id) }}" href="#"  > Report </a></li>
                @endif

               </ul>
               <div class="tab-content" id="myPillTabContent" style="padding: 0.5rem;">
                  <!-- <div class="row" style="margin-bottom:15px">
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
                  </div> -->
                  <div class="tab-pane fade active show" id="homePIll" role="tabpanel" aria-labelledby="home-icon-pill">
                     <div class="inbox-main-sidebar-container sidebar-container" data-sidebar-container="main" style="padding:10px;">
                        <div class="inbox-main-content sidebar-content" data-sidebar-content="main">
                           <!-- SECONDARY SIDEBAR CONTAINER-->
                           <div class="inbox-secondary-sidebar-container box-shadow-1 sidebar-container" data-sidebar-container="secondary">
                              <!-- Secondary Inbox sidebar-->
                              <div class="inbox-secondary-sidebar perfect-scrollbar rtl-ps-none ps sidebar" data-sidebar="secondary" style="left: 0px;background: #fff;">
                                 <i class="sidebar-close i-Close" data-sidebar-toggle="secondary"></i>

                                          @if ($report)
                                                         
                                                   
                                          <div class="data-card">
                                             <div class="media">
                                                <div class="data-card-date">
                                                   <h3>{{ date('d',strtotime($report->created_at)) }}<br><span>{{ date('M',strtotime($report->created_at)) }}</span></h3>
                                                </div>
                                                <div class="media-body">
                                                   <h5 class="mt-2 mb-1">Report has been generated by {{ Helper::user_name($report->created_by)}}.</h6> 
                                                   <ul class="lister">
                                                      <li><small>By {{ Helper::user_name($report->created_by)}}</small></li>
                                                      <li><small class="text">Created Date & Time: {{ date('d-m-Y h:i ',strtotime($report->created_at)) }}@if (date('h',strtotime($report->created_at))>=12)
                                                         PM @else AM
                                                     @endif</small></li>
                                                   </ul> 
                                                   <span class="badge badge-success badger" style="font-size: 12px;"> Generated  </span>
                                                </div>
                                             </div>
                                          </div>
                                          @endif

                                          {{-- @if (count($insuffs)>0)
                                              
                                          <div class="data-card">
                                            
                                             <div class="media">
                                                <div class="data-card-date">
                                                   <h3>{{ date('d',strtotime($query->insuff_created_at)) }}<br><span>{{ date('M',strtotime($query->filled_at)) }}</span></h3>
                                                </div>
                                                   <div class="media-body">
                                                      <h5 class="mt-2 mb-1">Insufficiency has been raised. </h6> 
                                                         @foreach ($insuffs as $insuff)
                                                         <ul class="lister">
                                                         <li>Insufficiency raised By: {{Helper::user_name($insuff->insuff_created_by)}}</li>
                                                         <li>Service Name: {{ Helper::get_service_name($insuff->service_id)}} </li>
                                                         <li><small class="text">Created Date & Time: {{ date('d-m-Y h:i',strtotime($insuff->insuff_created_at)) }}@if (date('h',strtotime($report->created_at))>=12)
                                                            PM @else AM
                                                        @endif</small></li>
                                                      </ul>
                                                      @endforeach 
                                                      <span class="badge badge-success badger" style="font-size: 12px;">Insufficiency raised </span>
                                                   </div>
                                               
                                             </div>
                                            
                                          </div>
                                          @endif --}}

                                          @if (count($insuff_data)>0)
                                             @foreach ($insuff_data as $insuff)
                                                @if($insuff->status=='raised')
                                                   <div class="data-card">
                                                      <div class="media">
                                                         <div class="data-card-date">
                                                            <h3>{{ date('d',strtotime($insuff->created_at)) }}<br><span>{{ date('M',strtotime($insuff->created_at)) }}</span></h3>
                                                         </div>
                                                         <div class="media-body">
                                                            <h5 class="mt-2 mb-1">Insufficiency has been raised by {{Helper::user_name($insuff->created_by)}}</h6> 
                                                               <ul class="lister">
                                                               {{-- <li>Insufficiency raised By: </li> --}}
                                                               <li>
                                                                  @if($insuff->verification_type=='manual' || $insuff->verification_type=='Manual')
                                                                  <small class="text"> Check Name : {{ Helper::get_service_name($insuff->service_id)}} - {{$insuff->item_number}}</small>
                                                                  @else
                                                                  <small class="text">Check Name : {{ Helper::get_service_name($insuff->service_id)}}</small>
                                                                  @endif 
                                                               </li>
                                                               <li><small class="text">Notes : {{$insuff->notes!=NULL?$insuff->notes:'N/A'}}</small></li>
                                                               <li><small class="text">Created Date & Time: {{ date('d-m-Y h:i a',strtotime($insuff->created_at)) }}</small></li>
                                                            </ul>  
                                                            <span class="badge badge-danger badger" style="font-size: 12px;">Insufficiency raised </span>
                                                         </div>
                                                      </div>
                                                   </div>
                                                @elseif($insuff->status=='removed')
                                                   <div class="data-card">
                                                      <div class="media">
                                                         <div class="data-card-date">
                                                            <h3>{{ date('d',strtotime($insuff->created_at)) }}<br><span>{{ date('M',strtotime($insuff->created_at)) }}</span></h3>
                                                         </div>
                                                         <div class="media-body">
                                                            <h5 class="mt-2 mb-1">Insufficiency has been Cleared by {{Helper::user_name($insuff->created_by)}}</h6> 
                                                               <ul class="lister">
                                                                  {{-- <li>Insufficiency Cleared By: {{Helper::user_name($insuff->created_by)}}</li> --}}
                                                                  <li>
                                                                     @if($insuff->verification_type=='manual' || $insuff->verification_type=='Manual')
                                                                     <small class="text"> Check Name : {{ Helper::get_service_name($insuff->service_id)}} - {{$insuff->item_number}}</small>
                                                                     @else
                                                                     <small class="text">Check Name : {{ Helper::get_service_name($insuff->service_id)}}</small>
                                                                     @endif 
                                                                  </li>
                                                                  <li><small class="text">Notes : {{$insuff->notes!=NULL?$insuff->notes:'N/A'}}</small></li>
                                                                  <li><small class="text">Created Date & Time: {{ date('d-m-Y h:i a',strtotime($insuff->created_at)) }}</small></li>
                                                            </ul>  
                                                            <span class="badge badge-success badger" style="font-size: 12px;">Insufficiency Cleared </span>
                                                         </div>
                                                      </div>
                                                   </div>
                                                @else
                                                   <div class="data-card">
                                                      <div class="media">
                                                         <div class="data-card-date">
                                                            <h3>{{ date('d',strtotime($insuff->created_at)) }}<br><span>{{ date('M',strtotime($insuff->created_at)) }}</span></h3>
                                                         </div>
                                                         <div class="media-body">
                                                            <h5 class="mt-2 mb-1">Verification has been Attempted by {{Helper::user_name($insuff->created_by)}}</h6> 
                                                               <ul class="lister">
                                                                  {{-- <li>Insufficiency Cleared By: {{Helper::user_name($insuff->created_by)}}</li> --}}
                                                                  <li>
                                                                     @if($insuff->verification_type=='manual' || $insuff->verification_type=='Manual')
                                                                     <small class="text"> Check Name : {{ Helper::get_service_name($insuff->service_id)}} - {{$insuff->item_number}}</small>
                                                                     @else
                                                                     <small class="text">Check Name : {{ Helper::get_service_name($insuff->service_id)}}</small>
                                                                     @endif 
                                                                  </li>
                                                                  <li><small class="text">Notes : {{$insuff->notes!=NULL?$insuff->notes:'N/A'}}</small></li>
                                                                  <li><small class="text">Created Date & Time: {{ date('d-m-Y h:i a',strtotime($insuff->created_at)) }}</small></li>
                                                            </ul>  
                                                            <span class="badge badge-warning badger" style="font-size: 12px;">Verification Failed </span>
                                                         </div>
                                                      </div>
                                                   </div>
                                                @endif
                                             @endforeach
                                          @endif

                                          @if (count($candidate_hold_logs)>0)
                                             @foreach ($candidate_hold_logs as $item)
                                                @if($item->status=='hold')
                                                   <div class="data-card">
                                                      <div class="media">
                                                         <div class="data-card-date">
                                                            <h3>{{ date('d',strtotime($item->created_at)) }}<br><span>{{ date('M',strtotime($item->created_at)) }}</span></h3>
                                                         </div>
                                                         <div class="media-body">
                                                            <h5 class="mt-2 mb-1">JAF has been holded by {{Helper::user_name($item->user_id)}}</h6> 
                                                               <ul class="lister">
                                                               {{-- <li>Insufficiency raised By: </li> --}}
                                                               <li>
                                                                  <small class="text">By {{Helper::user_name($item->user_id)}} ({{Helper::company_name($item->business_id)}})</small>
                                                               </li>
                                                               <li><small class="text">Created Date & Time: {{ date('d-m-Y h:i a',strtotime($item->created_at)) }}</small></li>
                                                            </ul>  
                                                            <span class="badge badge-danger badger" style="font-size: 12px;">JAF On Hold </span>
                                                         </div>
                                                      </div>
                                                   </div>
                                                @else
                                                   <div class="data-card">
                                                      <div class="media">
                                                         <div class="data-card-date">
                                                            <h3>{{ date('d',strtotime($item->created_at)) }}<br><span>{{ date('M',strtotime($item->created_at)) }}</span></h3>
                                                         </div>
                                                         <div class="media-body">
                                                            <h5 class="mt-2 mb-1">JAF hold status has been Removed by {{Helper::user_name($item->user_id)}}</h6> 
                                                               <ul class="lister">
                                                                  {{-- <li>Insufficiency Cleared By: {{Helper::user_name($insuff->created_by)}}</li> --}}
                                                                  <li>
                                                                     <small class="text">By {{Helper::user_name($item->user_id)}} ({{Helper::company_name($item->business_id)}})</small>
                                                                  </li>
                                                                  <li><small class="text">Created Date & Time: {{ date('d-m-Y h:i a',strtotime($item->created_at)) }}</small></li>
                                                            </ul>  
                                                            <span class="badge badge-success badger" style="font-size: 12px;">JAF ON Hold Removed </span>
                                                         </div>
                                                      </div>
                                                   </div>
                                                @endif
                                             @endforeach
                                          @endif

                                          @if ($query !=null)
                                       
                                          <div class="data-card">
                                             <div class="media">
                                                <div class="data-card-date">
                                                   <h3>{{ date('d',strtotime($query->filled_at)) }}<br><span>{{ date('M',strtotime($query->filled_at)) }}</span></h3>
                                                </div>
                                                <div class="media-body">
                                                   <h5 class="mt-2 mb-1">JAF has been filled by {{ Helper::user_name($query->filled_by)}} . </h6>
                                                   <ul class="lister">
                                                      <li><small>By {{ Helper::user_name($query->filled_by)}} </small></li>
                                                      <li>
                                                         <small class="text">
                                                         Created Date & Time: {{ date('d-m-Y h:i',strtotime($query->filled_at)) }}
                                                         @if($report !='')
                                                            @if (date('h',strtotime($report->created_at))>=12)
                                                            PM @else AM
                                                            @endif
                                                         @endif
                                                         </small>
                                                      </li>
                                                   </ul> 
                                                   <span class="badge badge-success badger" style="font-size: 12px;">  {{strtoupper($query->jaf_status)}} </span>
                                                </div>
                                             </div>
                                          </div>
                                          @endif
                                          
                                          <div class="data-card">
                                                <div class="media">
                                                   <div class="data-card-date">
                                                      <h3>{{ date('d',strtotime($candidate->created_at)) }}<br><span>{{ date('M',strtotime($candidate->created_at)) }}</span></h3>
                                                   </div>
                                                   <div class="media-body">
                                                      <h5 class="mt-2 mb-1">Profile has been created by {{ Helper::user_name($candidate->created_by)}}.</h6> 
                                                      <ul class="lister">
                                                         <li><small>By {{ Helper::user_name($candidate->created_by)}}</small></li>

                                                         <li><small class="text">Created Date & Time: {{ $candidate->created_at!=NULL?date('d-m-Y h:i',strtotime($candidate->created_at)):'' }}
                                                            @if ($candidate->created_at!=NULL)
                                                                  @if (date('h',strtotime($candidate->created_at))>=12)
                                                                  PM @else AM
                                                                  @endif
                                                            @endif
                                                      </small></li>

                                                      </ul> 
                                                      <span class="badge badge-success badger" style="font-size: 12px;"> Created  </span>
                                                   </div>
                                                </div>
                                             </div>
                                            
                                            
                                             
            {{-- {{ Helper::get_service_name($query->filled_by)}} --}}
                              <!-- <div class="row m-0">
                                 <div class="col-md-4">
                                    <div class="data-card text-center">
                                       <h4>Call</h4>
                                       <h6>+91-8777623221</h6>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="data-card text-center">
                                       <h4>Email</h4>
                                       <h6>{{ $candidate->email }}</h6>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="data-card text-center">
                                       <h4>SMS</h4>
                                       <h6>+91-8777623221</h6>
                                    </div>
                                 </div>
                              </div> -->
                              <div class="modal"  id="preview">
                                 <div class="modal-dialog modal-lg" style="max-width: 80% !important; margin-top:5%;">
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
                                                 style="width:100%; height:600px; " 
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
