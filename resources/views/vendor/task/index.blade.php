@extends('layouts.vendor')
@section('content')
<style>
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
  width: 100%;
  border:1px solid #222;
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

.closeImage {
  /* position: absolute;
  top: 60px;
  right: 20px;
  color: #f1f1f1; */
  font-size: 24px;
  /* font-weight: bold;
  transition: 0.3s; */
}
#myImageModal .modal-header {
    flex-direction: row-reverse;
}
.closeImage:hover,
.closeImage:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}
</style>

<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/vendor/home') }}">Dashboard</a>
             </li>
             <li>Task</li>
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
         {{-- <div class="col-md-12"> --}}
         <div class="card text-left">
            <div class="card-body">
               <div class="row">
                  {{-- <div class="col-md-12">
                     <ul class="nav nav-tabs nav-tabs-bottom">
                        <li class="nav-item"><a href="" class="nav-link active">All Tasks</a></li>
                        <li class="nav-item"><a href="{{url('/task/assign')}}" class="nav-link">Assigned Tasks</a></li>
                        <li class="nav-item"><a href="{{url('/task/unassign')}}" class="nav-link">Unassigned Tasks</a></li>
                        <li class="nav-item"><a href="{{url('/task/complete')}}" class="nav-link  ">Completed Tasks</a></li>
                     </ul>
                  </div> --}}
                  @if ($message = Session::get('success'))
                     <div class="col-md-12 mt-2">   
                        <div class="alert alert-success">
                           <strong>{{ $message }}</strong> 
                        </div>
                     </div>
                  @endif
                     {{-- @php
                     $ADD_ACCESS    = false;
                     $REASSIGN_ACCESS   = false;
                     $VIEW_ACCESS   = false;
                     $ADD_ACCESS    = Helper::can_access('Create Task','');//passing action title and route group name
                     $REASSIGN_ACCESS    = Helper::can_access('Reassign','');//passing action title and route group name
                     $VIEW_ACCESS   = Helper::can_access('View Task','');//passing action title and route group name
                     @endphp --}}
                  <div class="col-md-8 mt-2">
                     <h4 class="card-title mb-1"> Tasks</h4>
                     <p> List of all Task </p>
                  </div>
                 
                     
                  
                  <div class="col-md-4 mt-2">
                     <div class="btn-group" style="float:right">
                      
                       <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>   
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-3 form-group mb-3">
                     <label for="picker1"> Export </label>
                     <select class="form-control check"  id="check">
                        <option value="">-Select-</option>
                        <option value="details">Candidate Details</option> 
                        <option value="attachment">Attactments</option>
                     </select>
                  </div>
                  <div class="col-md-2 form-group mt-4">
                        <a class="btn-link " id="exportExcel" href="javascript:;"> <i class="far fa-file-archive"></i> Download </a> 
                        <p style="margin-bottom:2px;" class="load_container text-danger" id="loading"></p>
                  </div>
                  <div class="col-md-3"></div>
                  {{-- <div class="col-md-3 form-group ">
                     <label for="picker1" ><strong>Assign Task</strong>  </label>
                     <select class="select-option-field-7 user selectValue form-control" name="user" data-type="user" >
                        <option value="">Select user</option>
                        {{-- @foreach ($users as $key => $user) {
                           <option value="{{$user->id}}" >{{$user->name}}</option>

                        @endforeach 
                     </select>
                  </div> --}}
                  {{-- <div class="col-md-1 form-group mt-4">
                     <a class="btn-link " id="bulkAssign" href="javascript:;"> <i class="fas fa-user-plus"></i> Assign</a> 
                     <p style="margin-bottom:2px;" class="load_container text-danger" id="bulk"></p>
                  </div> --}}
                     <div class="col-md-3 form-group mt-4" >
                        <label for="picker1" style="float: right;"><strong>Numbers of Rows:-</strong>  </label>
                     </div>
                     <div class="col-md-1 form-group mt-3" >
                        <select class="form-control rows"  id="rows">
                           <option value="">-Select-</option>
                           <option value="25">25</option>   
                           <option value="50">50</option> 
                           <option value="100">100</option> 
                           <option value="150">150</option> 
                           <option value="200">200</option> 
                           <option value="250">250</option> 
                           <option value="300">300</option> 
                           <option value="350">350</option> 
                           <option value="400">400</option> 
                           <option value="450">450</option> 
                           <option value="500">500</option> 
                        </select>
                     </div>
               </div>
               <div class="search-drop-field" id="search-drop" style="z-index: 1">
                  <div class="row">
                     <div class="col-12">           
                         <div class="btn-group" style="float:right;font-size:24px;">   
                             <a href="#" class="filter_close text-danger"><i class="far fa-times-circle"></i></a>        
                         </div>
                     </div>
                 </div>
                 <div class="row">
                  <div class="col-md-2 form-group mb-1">
                      <label> From date </label>
                      <input class="form-control from_date commonDatePicker" type="text" placeholder="From date">
                  </div>
                  <div class="col-md-2 form-group mb-1">
                      <label> To date </label>
                      <input class="form-control to_date commonDatePicker" type="text" placeholder="To date">
                  </div>
                  {{-- <div class="col-md-3 form-group mb-1 level_selector">
                    <label>Customer</label><br>
                    <select class="form-control customer_list select" name="customer" id="customer">
                       <option value=''>-All-</option>
                        @foreach($clients as $item)
                        <option value="{{$item->id}}"> {{ ucfirst($item->company_name)}} </option>
                        @endforeach
                    </select>
                 </div> --}}
                  <div class="col-md-2 form-group mb-1 level_selector">
                      <label>Candidate Name</label><br>
                      <select class="form-control candidate_list select " name="candidate" id="candidate">
                       <option value=''>-Select-</option>
                           @foreach ($user_name as $key => $user) 
                        
                              <option value="{{$user->name}}" >{{$user->name}}</option>
                           
                           @endforeach
                      </select>
                  </div>
                  {{-- <div class="col-md-2 form-group mb-1 level_selector">
                    <label>SLA Name</label><br>
                    <select class="form-control sla_list select " name="sla" id="sla">
                       <option value=''>-Select-</option>

                    </select>
                   
                </div>  --}}
                {{-- <div class="col-md-2 form-group mb-1 level_selector">
                    <label>User's Name</label><br>
                    <select class="form-control user_list select" name="user" id="user">
                       <option value=''>-Select-</option>
                       @foreach($task_users as $item)
                       <option value="{{$item->id}}"> {{ ucfirst($item->name)}} </option>
                       @endforeach
                    </select>
                 </div> --}}
                 <div class="col-md-2 form-group mb-1">
                    <label>Checks</label>
                    <select class="form-control "  name="service" id="service">
                       <option value="">Select</option>
                       @foreach ($services as $service)
                             <option value="{{ $service->id }}" >{{ $service->name }}</option> 
                       @endforeach
                    </select>
                </div>
                  {{-- <div class="col-md-2 form-group mb-1">
                    <label>Task's Type</label>
                    <select class="form-control" name="task_type" id="task_type" >
                        <option value="">All</option>
                        <option  value="JAF Filling" >JAF Filling</option>
                        <option  value="Task for Verification " >Task Verification</option>
                    </select>
                 </div> --}}
                  {{-- <div class="col-md-3 form-group mb-1">
                    <label>Assign Status</label>
                    <select class="form-control" name="assign_status" id="assign_status" >
                       <option value="">All</option>
                       <option  value="assigned" >Assigned</option>
                       <option  value="unassigned" >Unassign</option>
                    </select>
                 </div> --}}
                 <div class="col-md-2 form-group mb-1">
                    <label>Complete Status</label>
                    <select class="form-control" name="complete_status" id="complete_status" >
                       <option value="">All</option>
                       <option  value="1" >Pending</option>
                       <option  value="2" >Completed</option>
                    </select>
                 </div>
                 <div class="col-md-1">
                    <button class="btn btn-danger  resetBtn" style="padding: 7px;margin: 18px 0px;"> <i class="fas fa-refresh"></i>  Reset </button>
                 </div>
                 <div class="col-md-1">
                    <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                 </div>
              </div>
              </div>
              <input type="hidden" name="in_tat" id="in_tat" value={{$in_tat}}>
              <input type="hidden" name="out_tat" id="out_tat" value={{$out_tat}}>
               <div id="taskResult">
                  @include('vendor.task.ajax')
                  
               </div>
            </div>
         </div> 
      {{-- </div> --}}
   </div>
   </div>
</div>

{{-- Modal for upload files --}}

<div class="modal" id="upload_files">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title" id="serv_name"> Verification Data</h4>
             <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
          <form method="post" action="{{ url('/vendor/task/upload') }}"  id="vendor_verification_data" enctype="multipart/form-data">
          @csrf
            <input type="hidden" name="vendor_task_id" id="vendor_task_id">
            {{-- <input type="hidden" name="serv_id" id="serv_id">
            <input type="hidden" name="jaf_f_id " id="jaf_f_id"> --}}
             <div class="modal-body">
             <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
               <div class="form-group">
                  <label for="verification_status"> Status <span class="text-danger">*</span>  </label><br>
                  <input  type="radio" class="verification_status" id="verification_status" name="verification_status" value="done" > Done
                  <input type="radio" class="verification_status" id="verification_status" name="verification_status" value="unable_to_verify"> Unable to verify
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-verification_status"></p>
               </div>
                <div class="form-group">
                      <label for="remark"> Remarks </label>
                      <textarea id="remark" name="remark" class="form-control remark" placeholder=""></textarea>
                      {{-- <input type="text" id="comments" name="comments" class="form-control comments" placeholder=""/> --}}
                      <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-remark"></p> 
                </div>
                <div class="form-group">
                   <label for="label_name"> Attachments:<span class="text-danger">*</span><i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i> </label>
                   <input type="file" name="attachment[]" accept=".jpg,.jpeg,.png,.pdf" id="attachment" multiple class="form-control attachment">
                   <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-attachment"></p>  
                </div>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="submit" class="btn btn-primary verification-submit" id="verification_submit">Submit </button>
                <button type="button" class="btn btn-danger closeraisemdl" id="clear_insuff_close" data-dismiss="modal">Close</button>
             </div>
          </form>
       </div>
    </div>
 </div>

 {{-- Modal to preview data --}}
 <div class="modal" id="preview_modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" id="ser_name">Preview Upload data</h4>
            {{-- <button type="button" class="close closeraisemdl" data-dismiss="modal">&times;</button> --}}
         </div>
         <!-- Modal body -->
           <input type="hidden" name="task_id" id="task_id">
          
            <div class="modal-body">
               <div id="preview_data">

               </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="button" class="btn btn-danger closepreview" data-dismiss="modal">Close</button>
            </div>
      </div>
   </div>
</div>
<!-- Script -->
{{-- Re-Assign Task --}}
<div class="modal" id="assign_modal">
   <div class="modal-dialog">
      <div class="modal-content">
      <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" >Assign To</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{url('/vendor/task/user/assign')}}" id="assign_form">
            @csrf
         
            <input type="hidden" name="vendors_task_id" id="vendors_task_id">
            
            <div class="modal-body">
               <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
               <div class="form-group">
                  <label for="label_name">Assign To </label>
                  {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
                  <div class="form-group">
                     <select class="select-option-field-7 users selectValue form-control"  name="users" data-type="users" data-t="{{ csrf_token() }}">
                        <option value="">Select user</option>
                        @foreach ($task_users as $key => $user) 
                        
                              <option value="{{$user->id}}" >{{$user->name}}</option>
                           
                        @endforeach
                        
                     </select>
                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-users"></p>
                  </div>
               </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-primary btn-submit submit">Submit </button>
               <button type="button" class="btn btn-danger back"  id="assign_back" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- End of Assign Task Model --}}
{{-- Assign Task --}}
<div class="modal" id="reassign_modal">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" >Re-Assign To</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{url('/vendor/task/user/reassign')}}" id="reassign_form">
         @csrf
        
         <input type="hidden" name="reassign_task_id" id="reassign_task_id">
         
         <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
            <div class="form-group">
               <label for="label_name">Assign To </label>
               {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
               <div class="form-group">
                  <select class="select-option-field-7 users selectValue form-control"  name="users" data-type="users" data-t="{{ csrf_token() }}">
                     <option value="">Select user</option>
                     @foreach ($task_users as $key => $user) 
                           <option value="{{$user->id}}" >{{$user->name}}</option>
                     @endforeach
                  </select>
                  <p style="margin-bottom: 2px;" class="text-danger error-container error-users" id="error-users"></p>
               </div>
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-submit reassign_submit">Submit </button>
            <button type="button" class="btn btn-danger back "  id="assign_back" data-dismiss="modal">Close</button>
         </div>
         </form>
      </div>
   </div>
</div>
{{-- End of Re-Assign Task Model --}}

{{-- Rase modal insuff --}}
<div class="modal" id="raise_modal">
   <div class="modal-dialog" style="max-width: 70%">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" id="servicename"></h4>
            {{-- <button type="button" class="close closeraisemdl" data-dismiss="modal">&times;</button> --}}
         </div>
      
         <div id="datavendor" style="max-height: 300px; overflow-x: hidden; overflow-y: scroll;"></div>    
         <!-- Modal body -->
         <form method="post" action="{{url('/vendor/jaf/raiseInsuff')}}" enctype="multipart/form-data" id="raise_insuff_form">
            @csrf
            <input type="hidden" name="ver_id" id="ver_id">
           <input type="hidden" name="can_id" id="can_id">
           <input type="hidden" name="ser_id" id="ser_id">
           <input type="hidden" name="number_ver" id="number_ver">
           {{-- <input type="hidden" name="jaf_id" id="jaf_id"> --}}
            <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
               <div class="form-group">
                     <label for="label_name"> Comments: <span class="text-danger">*</span></label>
                     <textarea id="comments" name="comments" class="form-control comments" placeholder=""></textarea>
                     {{-- <input type="text" id="comments" name="comments" class="form-control comments" placeholder=""/> --}}
                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-comments"></p> 
               </div>
               <div class="form-group">
                  <label for="label_name"> Attachments: <i class="fa fa-info-circle tool" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i> </label>
                  <input type="file" name="attachments[]" id="attachments" multiple class="form-control attachments">
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-attachments"></p>  
               </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-info raise_submit">Submit </button>
               <button type="button" class="btn btn-danger closeraisemdl closeinsuffraise" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>

{{-- Rase insuff modal--}}

{{-- Rase modal insuff --}}
<div class="modal" id="raise_modal_vendor">
   <div class="modal-dialog" style="max-width: 70%">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" id="service_name"></h4>
            {{-- <button type="button" class="close closeraisemdl" data-dismiss="modal">&times;</button> --}}
         </div>
      
         <div id="datavendorshow" style="max-height: 300px; overflow-x: hidden; overflow-y: scroll;"></div>    
         <!-- Modal body -->
         <form method="post" action="{{url('/vendor/jaf/raiseInsuff')}}" enctype="multipart/form-data" id="raise_insuff_form">
            @csrf
            <input type="hidden" name="ver_id" id="ver_id">
           <input type="hidden" name="can_id" id="can_id">
           <input type="hidden" name="ser_id" id="ser_id">
           <input type="hidden" name="number_ver" id="number_ver">
           {{-- <input type="hidden" name="jaf_id" id="jaf_id"> --}}
            <div class="modal-body">
               {{-- <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
               <div class="form-group">
                     <label for="label_name"> Comments: <span class="text-danger">*</span></label>
                     <textarea id="comments" name="comments" class="form-control comments" placeholder=""></textarea>
                     <input type="text" id="comments" name="comments" class="form-control comments" placeholder=""/>
                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-comments"></p> 
               </div>
               <div class="form-group">
                  <label for="label_name"> Attachments: <i class="fa fa-info-circle tool" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i> </label>
                  <input type="file" name="attachments[]" id="attachments" multiple class="form-control attachments">
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-attachments"></p>  
               </div> --}}
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               {{-- <button type="submit" class="btn btn-info raise_submit">Submit </button> --}}
               <button type="button" class="btn btn-danger closeraisemdl closeinsuffraise" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>

{{-- Rase insuff modal--}}

<div id="myImageModal" class="modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
               <span class="closeImage">&times;</span>
               <h5 class="modal-title">File- </h5>
         </div> 
         <div class="modal-body">
            <img class="image-modal-content" id="img01">
            <div id="caption"></div>
         </div>
      </div>
   </div>      
</div>

<input type="hidden" name="completed_task" id="completed_task" value="{{$completed_task}}">
<input type="hidden" name="raiseinsuff" id="raiseinsuff" value="{{$raiseinsuff}}">

<script type="text/javascript">
   $("#candidate").select2();
      $("#customer").select2();
      $("#sla").select2();
      $("#user").select2();
   $('.filter0search').click(function(){
      $('.search-drop-field').toggle();
   });
   $('.filter_close').click(function(){
      $('.search-drop-field').toggle();
   });
   // print visits  
   $(document).on('click','#exportExcel',function(){
      
         //   setData();
         //   var candidate = $(".reports option:selected").val();
         var _this=$(this);
         var task_arr = [];
         var i = 0;
         var export_type = $('.check').val();

         $('.checks:checked').each(function () {
               task_arr[i++] = $(this).val();
         });
        
         // alert(expot_type);
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';
         $('p.load_container').html("");
         
         if((task_arr.length)>0){
               _this.addClass('disabled-link');
               $('#loading').html(loadingText);
            // alert(candidate_arr);
            //  var check  =    $(".check option:selected").val();
            var from_date   =    $(".from_date").val(); 
            var to_date     =    $(".to_date").val();    
            //  var candidate_id=    candidate_arr;   {{ url('/') }}"+'/vendor/task/setData'
            $.ajax(
            {
               url: "{{ url('/') }}"+'/vendor/task/setData',
               type: "get",
               data:{'task_id':task_arr,'from_date':from_date,'to_date':to_date,'export_type':export_type},
               datatype: "html",
            })
            .done(function(data)
            {
               window.setTimeout(function(){
                  _this.removeClass('disabled-link');
                  $('#loading').html("");
                  // _this.html('<i class="far fa-file-archive"></i> Download Zip');
               },2000);
               console.log(data);
               var path = "{{ url('/vendor/task/pdf/export')}}";
                  window.open(path);
               })
               .fail(function(jqXHR, ajaxOptions, thrownError)
               {
                  //alert('No response from server');
               });
               // .done(function(data)
               // {
               //    window.setTimeout(function(){
               //       _this.removeClass('disabled-link');
               //       $('#loading').html("");
               //       // _this.html('<i class="far fa-file-archive"></i> Download Zip');
               //    },2000);
               //    console.log(data);
               //    // var path = "{{ url('task/checks-export')}}";
               //    //    window.open(path);
               //    // })
               // }
               // .fail(function(jqXHR, ajaxOptions, thrownError)
               // {
               //    //alert('No response from server');
               // });
            }else{
                  alert('Please select a check to export! ');
            }
   });
   //Reset  filter Data
   $(document).on('click', '.resetBtn' ,function(){

      $("input[type=text], textarea").val("");
      //   $('.customer_list').val('');
      //    $('.candidate').val('');
      //    $('.user_list').val('');
      $('#candidate').val(null).trigger('change');
      $('#customer').val(null).trigger('change');
      $('#user').val(null).trigger('change');
      $('#service').val('');
      $('#task_type').val('');
      $('#assign_status').val('');
      $('#complete_status').val('');
      var uriNum = location.hash;
      pageNumber = uriNum.replace("#","");
      // alert(pageNumber);
      getData(pageNumber);
   });
   $(document).on('change','.from_date',function() {

      var from = $('.from_date').datepicker('getDate');
      var to_date   = $('.to_date').datepicker('getDate');

      if($('.to_date').val() !=""){
         if (from > to_date) {
            alert ("Please select appropriate date range!");
            $('.from_date').val("");
            $('.to_date').val("");

         }
      }

   });
      //
      // 
      var uriNum = location.hash;
      pageNumber = uriNum.replace("#","");
      // alert(pageNumber);
      getData(pageNumber);

   $(document).on('change','.to_date',function() {

      var to_date = $('.to_date').datepicker('getDate');
      var from   = $('.from_date').datepicker('getDate');
      if($('.from_date').val() !=""){
         if (from > to_date) {
         alert ("Please select appropriate date range!");
         $('.from_date').val("");
         $('.to_date').val("");

         }
      }

   });
    // filterBtn
    $(document).on('change','.from_date, .to_date, .candidate_list,.sla_list,.user_list,#rows,#service,#task_type,#assign_status,#complete_status', function (e){    
         $("#overlay").fadeIn(300);　
         getData(0);
         e.preventDefault();
   });
   $(document).on('click','.filterBtn', function (e){    
        $("#overlay").fadeIn(300);　
        getData(0);
        e.preventDefault();
   });
   $(document).on('click', '.pagination a,.searchBtn',function(event){
        //loader
        $("#overlay").fadeIn(300);　
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        event.preventDefault();
        var myurl = $(this).attr('href');
        var page  = $(this).attr('href').split('page=')[1];
        getData(page);
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

   function getData(page){
      //set data
      var user_id     =    $(".customer_list").val();                
      // var check       =    $(".check option:selected").val();
      var sla_id   =     $(".sla_list option:selected").val();
      var cus_user_id   =     $(".user_list option:selected").val();
      var from_date   =    $(".from_date").val(); 
      var to_date     =    $(".to_date").val();      
      var candidate_id=    $(".candidate_list option:selected").val();
      var rows = $("#rows option:selected").val();
      var service_id = $("#service option:selected").val();
      var task_type = $("#task_type option:selected").val();
      var assign_status = $("#assign_status option:selected").val();
      var complete_status = $("#complete_status option:selected").val();
      var completed_task  = $('#completed_task').val();
      var raiseinsuff   = $('#raiseinsuff').val();
      var in_tat = $("#in_tat").val()!=undefined && $('#in_tat').val()!=null ? $('#in_tat').val() : '';
      var out_tat = $("#out_tat").val()!=undefined && $('#out_tat').val()!=null ? $('#out_tat').val() : '';
      //   var mob = $('.mob').val();complete_status
      //   var ref = $('.ref').val();
      //   var email = $('.email').val();
      //   var report_status=$('.report_status').val();
   
         $('#taskResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
   
         $.ajax(
         {
               url: '?page=' + page+'&customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&sla_id='+sla_id+'&user_id='+cus_user_id+'&rows='+rows+'&service_id='+service_id+'&task_type='+task_type+'&assign_status='+assign_status+'&complete_status='+complete_status+'&completed_task='+completed_task+'&raiseinsuff='+raiseinsuff+'&in_tat='+in_tat+'&out_tat='+out_tat,
               type: "get",
               datatype: "html",
         })
         .done(function(data)
         {
               $("#taskResult").empty().html(data);
               $("#overlay").fadeOut(300);
               //debug to check page number
               location.hash = page;
         })
         .fail(function(jqXHR, ajaxOptions, thrownError)
         {
               alert('No response from server');
         });

         return false;
   
   }
   function setData(){
   
      var user_id     =    $(".customer_list").val(); 
      // var check       =    $(".check option:selected").val();

      var from_date   = $(".from_date").val(); 
      var to_date     = $(".to_date").val();    
      var candidate_id= $(".candidate_list option:selected").val();  
      // var mob = $('.mob').val();
      // var ref = $('.ref').val();
      // var email = $('.email').val();

      var sla_id   = $(".sla_list option:selected").val(); 
      var cus_user_id   = $(".user_list option:selected").val();
      var rows = $("#rows option:selected").val();
      var service_id = $("#service option:selected").val();
      var task_type = $("#task_type option:selected").val();
      var assign_status = $("#assign_status option:selected").val();
      var complete_status = $("#complete_status option:selected").val();
      var completed_task  = $('#completed_task').val();
      var raiseinsuff   = $('#raiseinsuff').val();
      // var report_status=$('.report_status').val();
            $.ajax(
            {
               url: "{{ url('/') }}"+'/vendor/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&sla_id='+sla_id+'&user_id='+cus_user_id+'&rows='+rows+'&service_id='+service_id+'&task_type='+task_type+'&assign_status='+assign_status+'&complete_status='+complete_status+'&completed_task='+completed_task+'&raiseinsuff='+raiseinsuff,
               type: "get",
               datatype: "html",
            })
            .done(function(data)
            {
               console.log(data);
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
               //alert('No response from server');
            });

            return false;

   }
   //Upload Verified data Modal
   $(document).on('click', '.upload_data', function (event) {
         
         var vendor_task_id = $(this).attr('data-vendor_task_id');
        //  var jaf_id       = $(this).attr('jaf-id');
        //  var service_id   = $(this).attr('service-id');
         var servi_name  = $(this).attr('data-name');

         // alert(servi_name);
         $('#vendor_verification_data')[0].reset();
         $('#serv_name').text('Verification Data - '+servi_name);
         $('#vendor_task_id').val(vendor_task_id);
        //  $('#jaf_f_id').val(jaf_id);
        //  $('#cand_id').val(candidate_id);
         $('#upload_files').modal({
            backdrop: 'static',
            keyboard: false
         });
    });
    // $('#verification_submit').click(function(e) {
    //             e.preventDefault();
                
    //             $("#vendor_verification_data").submit();
    // });
    $(document).on('submit', 'form#vendor_verification_data', function (event) {
        $("#overlay").fadeIn(300);　
            event.preventDefault();
            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var $btn = $(this);
            $('.error-container').html('');
            $('.form-control').removeClass('border-danger');

            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i>  loading...';
            $('.verification-submit').attr('disabled',true);

            if ($('.verification-submit').html() !== loadingText) {
               $('.verification-submit').html(loadingText);
            }

            $.ajax({
                type: form.attr('method'),
                url: url,
                data: data,  
                cache: false,
                contentType: false,
                processData: false,   
                success: function (response) {
               
               console.log(response);
               if(response.success==true  ) {          
                  
                  //notify
                  toastr.success("Data Uploaded successfully");
                  // redirect to google after 5 seconds
                  window.setTimeout(function() {
                     $('.verification-submit').attr('disabled',false);
                     $('.submit').html('Submit');
                        window.location = "{{ url('/')}}"+"/vendor/task";
                  }, 2000);
                  
               }
               //show the form validates error
               if(response.success==false ) {                              
                  for (control in response.errors) {   
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

   //Preview modal
   $(document).on('click','.preview_button',function(){
      $('#task_id').val("");
      
      var task_id=$(this).attr('data-vendor_tasks_id');
      
      $('#task_id').val(task_id);
      
      $.ajax({
            type:'GET',
            url: "{{url('/vendor/task/preview')}}",
            data: {'task_id':task_id},        
            success: function (response) {        
            console.log(response);

            $('#preview_data').html(response);
            $('#preview_modal').modal({
                  backdrop: 'static',
                  keyboard: false
               });
            // if (response.status=='ok') {
            // } else {
            //    alert('No data found');
            // }
         },
         error: function (xhr, textStatus, errorThrown) {
            alert("Error: " + errorThrown);
         }
      });
   });

   //Assign to users
   $(document).on('click', '.assign_user', function (event) {
         
      var vendors_task_id = $(this).attr('data-task_id');
      //  var jaf_id       = $(this).attr('jaf-id');
      //  var service_id   = $(this).attr('service-id');
      //  var servi_name  = $(this).attr('service-name');
      // alert(vendors_task_id);
      $('#assign_form')[0].reset();
      //  $('#serv_name').text('Verification - '+servi_name);
      $('#vendors_task_id').val(vendors_task_id);
      //  $('#jaf_f_id').val(jaf_id);
      //  $('#cand_id').val(candidate_id);
      $('#assign_modal').modal({
         backdrop: 'static',
         keyboard: false
      });
   });

   $(document).on('submit', 'form#assign_form', function (event) {
      event.preventDefault();
      //clearing the error msg
      $('p.error_container').html("");
   
      var form = $(this);
      var data = new FormData($(this)[0]);
      var url = form.attr("action");
      var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
         $('.submit').attr('disabled',true);
         if($('.submit').html()!=loadingText)
         {
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
                     $('.submit').attr('disabled',false);
                     $('.submit').html('Submit');
                  },2000);
               console.log(response);
               if(response.success==true  ) {          
                  
                  //notify
                  toastr.success("Task has been assigned successfully");
                  // redirect to google after 5 seconds
                  window.setTimeout(function() {
                        window.location = "{{ url('/')}}"+"/vendor/task/";
                  },2000);
                  
               }
               //show the form validates error
               if(response.success==false ) {                              
                  for (control in response.errors) {   
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

      //Assign to users
   $(document).on('click', '.reassign_user', function (event) {
         
      var reassign_task_id = $(this).attr('data-task');
      //  var jaf_id       = $(this).attr('jaf-id');
      //  var service_id   = $(this).attr('service-id');
      //  var servi_name  = $(this).attr('service-name');

      // alert(vendors_task_id);
      $('#reassign_form')[0].reset();
      //  $('#serv_name').text('Verification - '+servi_name);
      $('#reassign_task_id').val(reassign_task_id);
      //  $('#jaf_f_id').val(jaf_id);
      //  $('#cand_id').val(candidate_id);
      $('#reassign_modal').modal({
         backdrop: 'static',
         keyboard: false
      });



   });

   $(document).on('submit', 'form#reassign_form', function (event) {
      event.preventDefault();
      //clearing the error msg
      $('p.error_container').html("");
   
      var form = $(this);
      var data = new FormData($(this)[0]);
      var url = form.attr("action");
      var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
         $('.reassign_submit').attr('disabled',true);
         if($('.reassign_submit').html()!=loadingText)
         {
            $('.reassign_submit').html(loadingText);
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
                     $('.reassign_submit').attr('disabled',false);
                     $('.reassign_submit').html('Submit');
                  },2000);
               console.log(response);
               if(response.success==true  ) { 
                  
                  //notify
                  toastr.success("Task Assignment successfully");
                  // redirect to google after 5 seconds
                  window.setTimeout(function() {
                        window.location = "{{ url('/')}}"+"/vendor/task/";
                  },2000);
                  
               }
               //show the form validates error
               if(response.success==false ) {                              
                  for (control in response.errors) {   
                        $('.error-' + control).html(response.errors[control]);
                  }
               }
            },
            error: function (xhr, textStatus, errorThrown) {
               // alert("Error: " + errorThrown);
            }
      });
      return false;
   }); 

   //Raise insuff data edit
   $(document).on('click', '.raise_insuff', function (event) {
         $('#can_id').val("");
         $('#servicename').text('Verification - '+"");
         $('#ser_id').val("");
         $('#ver_id').val("");
         $('#number_ver').val();
         var ver_id=$(this).attr('vendor-id');
         var can_id=$(this).attr('candidate-id');
         var ser_id=$(this).attr('service-id');
         var number_id=$(this).attr('number-ver');
         var ser_name=$(this).attr('service-name');
         
         $('#can_id').val(can_id);
         $('#servicename').text('Verification - '+ser_name);
         $('#ser_id').val(ser_id);
         $('#ver_id').val(ver_id);
         $('#number_ver').val(number_id);
         $.ajax({
            type: "get",
            url: "{{route('/vendor/taskinsuffshow')}}",
            data: {'service_id':ser_id,'number_id':number_id,'cand_id':can_id},
            success: function (response) {
               console.log(response)
               if(response !='null')
               {             
                  $('#datavendor').html(response.html);
               
                  $('#raise_modal').modal({
                        backdrop: 'static',
                        keyboard: false
                  }); 
                  
               }
            },
            error: function (xhr, textStatus, errorThrown) {
               // alert("Error: " + errorThrown);
            }
            });
            event.stopImmediatePropagation();
         
            $('#raise_modal').modal({
               backdrop: 'static',
               keyboard: false
            });
   });

   //raie insuff data show 
   $(document).on('click', '.raise_insuff_show', function (event) {
         $('#can_id').val("");
         $('#service_name').text('Verification - '+"");
         $('#ser_id').val("");
         $('#ver_id').val("");
         $('#number_ver').val();
         var ver_id=$(this).attr('vendor-id');
         var can_id=$(this).attr('candidate-id');
         var ser_id=$(this).attr('service-id');
         var number_id=$(this).attr('number-ver');
         var ser_name=$(this).attr('service-name');
         
         $('#can_id').val(can_id);
         $('#service_name').text('Verification - '+ser_name);
         $('#ser_id').val(ser_id);
         $('#ver_id').val(ver_id);
         $('#number_ver').val(number_id);
         $.ajax({
            type: "get",
            url: "{{route('/vendor/taskinsuffshow')}}",
            data: {'service_id':ser_id,'number_id':number_id,'cand_id':can_id},
            success: function (response) {
               console.log(response)
               if(response !='null')
               {             
                  $('#datavendorshow').html(response.html);
               
                  $('#raise_modal_vendor').modal({
                        backdrop: 'static',
                        keyboard: false
                  }); 
                  
               }
            },
            error: function (xhr, textStatus, errorThrown) {
               // alert("Error: " + errorThrown);
            }
            });
            event.stopImmediatePropagation();
         
            $('#raise_modal_vendor').modal({
               backdrop: 'static',
               keyboard: false
            });
   });

   //Raise insuff form submit
   $(document).on('submit', 'form#raise_insuff_form', function (event) {  
      $("#overlay").fadeIn(300);　
      event.preventDefault();
      var form = $(this);
      var data = new FormData($(this)[0]);
      var url = form.attr("action");
      var btn = $(this);
      var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
      $('.error-container').html('');
      $('.form-control').removeClass('border-danger');
      $('.raise_submit').attr('disabled',true);
      $('.closeinsuffraise').attr('disabled',true);
      if ($('.raise_submit').html() !== loadingText) {
            $('.raise_submit').html(loadingText);
      }
      $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
               console.log(response);
               window.setTimeout(function(){
                  $('.raise_submit').attr('disabled',false);
                  $('.closeinsuffraise').attr('disabled',false);
                  $('.raise_submit').html('Submit');
               },2000);
               $('.error-container').html('');
               if (response.fail && response.error_type == 'validation') {
                        //$("#overlay").fadeOut(300);
                        for (control in response.errors) {
                        // $('textarea[comments=' + control + ']').addClass('is-invalid');
                        $('.'+control).addClass('border-danger');
                        $('#error-' + control).html(response.errors[control]);
                        }
               } 
               //  if (data.fail && data.error == 'yes') {
                  
               //      $('#error-all').html(data.message);
               //  }
               //if (response.fail == false) {
                  // $('#send_otp').modal('hide');
                  // alert(data.id);
                  if(response.success){
                     toastr.success("Mail is Sent Successfully");
                     //toastr.error("Insuff is Raised");
                        // redirect to google after 5 seconds
                        window.setTimeout(function() {
                        location.reload(); 
                        }, 2000);
                     // window.location.href='{{ Config::get('app.admin_url')}}/aadharchecks/show';
                     //  location.reload();
                  }
                  else
                  {
                     toastr.error("Something Went Wrong!!");
                  } 
               // }
            },
            // error: function (xhr, textStatus, errorThrown) {
               
            //    alert("Error: " + errorThrown);

            // }
      });
      return false;
   
});



</script>
@endsection

