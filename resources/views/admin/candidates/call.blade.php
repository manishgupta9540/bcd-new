@extends('layouts.admin')
<style>
   .sticky {
   position: fixed;
    top: 16% ;
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
                  <li class="nav-item"><a class="nav-link active show" id="contact-icon-pill" href="{{ url('/candidates/call',['id'=> base64_encode($candidate->id)]) }}" role="tab" > Call </a></li>
                  <li class="nav-item"><a class="nav-link" id="contact-icon-pill"  href="{{ url('/candidates/task',['id'=> base64_encode($candidate->id)]) }}" > Task </a></li>
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
                                 
                                <!-- start right sec -->
                                <div class="col-md-12 content-wrapper" style=" background:#fff;">
                                    <div class="formCover">
                                        <!-- section -->
                                        <section>
                                            <div class="col-sm-12 ">
                                                <!-- row -->
                                                <div class="row">
                                                    <div class="col-md-5">
                                                    <h4 class="card-title mb-1 mt-3">Calls</h4>
                                                    <p class="pb-border"> Your Candidates Calls  </p>
                                                    </div>
                                                    @php
                                                        $ADD_ACCESS    = false;
                                                        $VIEW_ACCESS   = false;
                                                        $EDIT_ACCESS = false;
                                                        $PDF_ACCESS   = false;
                                                        $SLA_ACCESS   = false;
                                                        $ADD_ACCESS    = Helper::can_access('SLA Create','');
                                                        $VIEW_ACCESS   = Helper::can_access('SLA View','');
                                                        $EDIT_ACCESS = Helper::can_access('SLA Edit','');
                                                        $PDF_ACCESS = Helper::can_access('SLA PDF download','');
                                                        $SLA_ACCESS = Helper::can_access('SLA','');
                                                    
                                                        
                                                        // $REPORT_ACCESS   = false;
                                                        // $VIEW_ACCESS   = false;SLA
                                                    @endphp 
                                                    
                                                    
                                                        <div class="col-md-6 text-right mt-3">
                                                            <div class="btn-group" style="float:right">
                                                                @if ($ADD_ACCESS)
                                                                <a class="btn btn-success call_modal_form" href="#"> <i class="fa fa-plus" data-id="{{base64_encode($candidate->id)}}"></i> Add New</a>
                                                                <!-- <a href="{{ url('/sla/create') }}" class="btn btn-sm btn-info"> <i class="fa fa-plus"></i> Create new</a> -->
                                                                @endif
                                                                <!-- <a href="#" class="filter0search"><i class="fa fa-filter"></i></a> -->
                                                            </div>
                                                        </div>  
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="slaResult">
                                                   <table class="table table-bordered table-hover candidatesTable">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Person Name</th>
                                                                <th scope="col">Calling Date</th>
                                                                <th scope="col">Check List</th>
                                                                <th scope="col">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="candidateList">
                                                        @if (count($candidateCalls) > 0)
                                                            @foreach($candidateCalls as $candidateCall)
                                                                <tr>
                                                                    <td>{{$candidateCall->person_name}}</td>
                                                                    <td>{{ $candidateCall->start_date }}</td>
                                                                    <td>{!!Helper::get_service_name_slot($candidateCall->service_id)!!}</td>
                                                                    <td>
                                                                        <a href="javascript:void(0)">
                                                                            <button class="btn btn-info btn-sm mb-1 call_edit_modal_form" type="button" data-id="{{base64_encode($candidateCall->id)}}" style="width: 40%;"> <i class='fa fa-edit'></i> Edit</button>
                                                                        </a>
                                                                        <a href="javascript:void(0)">
                                                                            <button class="btn btn-primary btn-sm mb-1 call_view_modal" type="button" data-id="{{base64_encode($candidateCall->id)}}" style="width: 40%;"> <i class="fa fa-eye"></i> View</button>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach    
                                                        @else
                                                            <td colspan="5" class="text-center"><b>No Calls  Found</b></td>
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                  <!-- end right sec -->
                              
                                
                              
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


<!-- add modal -->
<div class="modal" id="call_modal">
    <div class="modal-dialog" style="max-width: 80% !important;">
        <div class="modal-content">
            <!-- Modal Header -->
        <div class="modal-header">
                <h4 class="modal-title" id="serv_name"></h4>
                {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
            </div>
            <!-- Modal body -->
            <form method="post" action="{{ url('/candidates/callingdatasave') }}" id="call_save_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="candidate_id" value="{{base64_encode($candidate->id)}}" id="candidate_id">
            
                <div class="modal-body">
                    <div class="form-group">
                        <label for="label_name">Person Name: <span class="text-danger">*</span></label>
                        <input type="text" name="person_name" id="person_name " class="form-control person_name">
                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-person_name"></p>
                    </div>
                    
                    <div class="form-group">
                        <label for="label_name"> Remarks </label>
                        <textarea id="remarks" name="remarks" class="form-control remarks"placeholder=""></textarea>
                        <!-- <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-remarks"></p> -->
                    </div>

                    <div class="form-group">
                        <label>Start Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control datetimepicker start_date" name="start_date" id="StartDate" autocomplete="off"/>
                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-start_date"></p>
                    </div>

                    <div class="form-group">
                        <label>End Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control datetimepicker end_date" name="end_date" id="EndDate" autocomplete="off"/>
                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-end_date"></p>
                    </div>

                    <div class="form-group">
                        <label for="picker1"><strong>Check:</strong><span class="text-danger">*</span> </label>
                        <select class="form-control check" name="check[]" id="check" data-actions-box="true" data-selected-text-format="count>1" multiple>
                                @foreach($services as $service)
                                    <option value="{{ $service->id}}">{{ $service->name  }}</option>   
                                @endforeach
                        </select>
                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-check"></p>
                        
                    </div>

                    <div class="form-group">
                        <label for="label_name"> Attachments: <i class="fa fa-info-circle tool" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i></label>
                        <input type="file" name="attachment[]" id="attachment" multiple class="form-control attachment">
                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-attachment"></p>
                       
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info clear-submit submit">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- add modal end -->

<!-- edit modal -->

<div class="modal" id="call_edit_modal">
    <div class="modal-dialog" style="max-width: 80% !important;">
        <div class="modal-content">
            <div class="modal-header">
                    <h4 class="modal-title" id="serv_name"></h4>
                    {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}} 
            </div>
            
            <div id='data'></div>
        </div>
    </div>
</div>

<!-- edit modal end -->

<!-- show modal -->

<div class="modal" id="call_view_modal_show">
    <div class="modal-dialog" style="max-width: 80% !important;">
        <div class="modal-content">
            <div class="modal-header">
                    <h4 class="modal-title" id="serv_name"></h4>
                    {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}} 
            </div>
                <div class="modal-body">
                    <div id='datashow'></div>
            </div>
        </div>
    </div>
</div>

<!-- show modal end -->
<script>



 

$(document).ready(function(){

    $(".select2").select2();
    $('.check').selectpicker();
    $('.tool').tooltip();
    $('.check').selectpicker();

    $('.datetimepicker').datetimepicker({
        format:'d-m-Y H:i',
        inline:false,
        lang:'ru'
    });

    

    $(document).ready(function() {
        $(document).on('click', '.call_modal_form', function(event) {
            $('#call_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
        });
    });


    $(document).on('submit', 'form#call_save_form', function (event) {
      event.preventDefault();

      var candidate_id = $('#candidate_id').val();
      
      //clearing the error msg
      $('p.error_container').html("");
      $('.form-control').removeClass('border-danger');
      var form = $(this);
      var data = new FormData($(this)[0]);
      var url = form.attr("action");
      var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
      $('.submit').attr('disabled',true);
      $('.form-control').attr('readonly',true);
      if ($('.submit').html() !== loadingText) {
        $('.submit').html(loadingText);
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
                    $('.form-control').attr('readonly',false);
                    $('.submit').attr('disabled',false);
                    $('.submit').html('Submit');
                },2000);
               console.log(response);
               if(response.success==true) {          
                  toastr.success('Call Created Successfully');
                  window.setTimeout(function(){
                    window.location.reload();
                  },2000);
               }
               //show the form validates error
               if(response.success==false ) {                              
                  for (control in response.errors) {  
                     $('.'+control).addClass('border-danger'); 
                     $('#error-' + control).html(response.errors[control]);
                  }
               }
         },
         error: function (xhr, textStatus, errorThrown) {
               // alert("Error: " + errorThrown);
         }
      });
      return false;
    });

   
    //edit call modal ajax
    $(document).ready(function() {
        $(document).on('click', '.call_edit_modal_form', function(event) {
            
            var id = $(this).attr('data-id');
            
            $.ajax({
                type: "get",
                url: "{{url('/candidates/callingdataedit')}}"+'/'+id,
                cache: false,
                contentType: false,
                processData: false,      
                success: function (response) {
                    console.log(response)
                    if(response !='null')
                    {             
                        $('#data').html(response.html);
                    
                        $('#call_edit_modal').modal({
                            backdrop: 'static',
                            keyboard: false
                        }); 
                        
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
                });

                $('#call_edit_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
        });
    });

    //edit call savedata  modal ajax route
    $(document).on('submit', 'form#call_edit_form', function (event) {
      event.preventDefault();

      $('p.error_container').html("");
      $('.form-control').removeClass('border-danger');
      var form = $(this);
      var data = new FormData($(this)[0]);
      var url = form.attr("action");
      var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
      $('.submit').attr('disabled',true);
      $('.form-control').attr('readonly',true);
      if ($('.submit').html() !== loadingText) {
        $('.submit').html(loadingText);
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
                    $('.form-control').attr('readonly',false);
                    $('.submit').attr('disabled',false);
                    $('.submit').html('Submit');
                },2000);
               console.log(response);
               if(response.success==true) {          
                  toastr.success('Calls Updated Successfully');
                  window.setTimeout(function(){
                    window.location.reload();
                  },2000);
               }
               //show the form validates error
               if(response.success==false ) {                              
                  for (control in response.errors) {  
                     $('.'+control).addClass('border-danger'); 
                     $('#error-'+control).html(response.errors[control]);
                  }
               }
         },
         error: function (xhr, textStatus, errorThrown) {
               // alert("Error: " + errorThrown);
         }
      });
      return false;
    });

    //show modal popup
    $(document).on('click', '.call_view_modal', function(event) {
            var id = $(this).attr('data-id');
            
            $.ajax({
                type: "get",
                url: "{{url('/candidates/callingdatashow')}}"+'/'+id,
                cache: false,
                contentType: false,
                processData: false,      
                success: function (response) {
                    console.log(response)
                    if(response !='null')
                    {             
                        $('#datashow').html(response.html);
                        $('#call_view_modal_show').modal({
                            backdrop: 'static',
                            keyboard: false
                        }); 
                        
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
            });
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
