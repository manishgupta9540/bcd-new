@extends('layouts.admin')
@section('content')
<style>
   #user_task_assign{
      /* overflow-x: hidden; */
      /* overflow-y: hidden; */
      z-index: 999;
      padding-top: 0px;
      /* margin:auto; */
   }
   #user_task_assign .modal-dialog.modal-lg{
  max-width: 90% !important;
  width: 100%;
  padding: 0px;
  left: 3.5%;
}
#user_task_assign .modal-content {
  margin: auto;
  display: block;
  width: 100%;
  max-width: 1270px;
  
}
.select2-search__field{
   width:100% !important;
}
/* .col-sm-12.app_status .select2.select2-container.select2-container--default.select2-container--below.select2-container--focus {
    z-index: 9999999!important;
    display: block;
} */
</style>
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/home') }}">Dashboard</a>
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
      <?php $user_type = Auth::user()->user_type;
            // dd($user_type);
            $users_id =Auth::user()->id;
               // $cam=in_array($user_id,$cam)
            $VENDOR_TASK_ACCESS   = false;
            $VENDOR_TASK_ACCESS    = Helper::can_access('Vendor Task','');//passing action title and route group name   
     ?>
      <div class="row">
         {{-- <div class="col-md-12"> --}}
         <div class="card text-left">
            <div class="card-body">
               <div class="row">
                  <div class="col-md-12 text-center"><h2 class="mb-0">[Restricted]</h2></div>

                  <div class="col-md-12">
                     <ul class="nav nav-tabs nav-tabs-bottom">
                        @if ($user_type == 'customer' || in_array($users_id,$cam) )
                           <li class="nav-item"><a href="{{url('/task')}}" class="nav-link ">All Tasks</a></li>
                           <li class="nav-item"><a href="{{url('/task/assign')}}" class="nav-link active">Assigned Tasks</a></li>
                           <li class="nav-item"><a href="{{url('/task/unassign')}}" class="nav-link">Unassigned Tasks</a></li>
                           <li class="nav-item"><a href="{{url('/task/complete')}}" class="nav-link">Completed Tasks</a></li>
                           <li class="nav-item"><a href="{{url('/task/vendor')}}" class="nav-link">Vendor Tasks</a></li>
                        @else
                           <li class="nav-item"><a href="{{url('/task')}}" class="nav-link ">All Tasks</a></li>
                           <li class="nav-item"><a href="{{url('/task/assign')}}" class="nav-link active">Assigned Tasks</a></li>
                           <li class="nav-item"><a href="{{url('/task/complete')}}" class="nav-link">Completed Tasks</a></li>
                           @if ($VENDOR_TASK_ACCESS)
                              <li class="nav-item"><a href="{{url('/task/vendor')}}" class="nav-link">Vendor Tasks</a></li>
                           @endif
                      @endif
                       </ul>
                  </div>
                  @if ($message = Session::get('success'))
                     <div class="col-md-12">   
                        <div class="alert alert-success">
                           <strong>{{ $message }}</strong> 
                        </div>
                     </div>
                  @endif
                     @php
                     // $ADD_ACCESS    = false;
                     $REASSIGN_ACCESS   = false;
                     $VIEW_ACCESS   = false;
                     // $ADD_ACCESS    = Helper::can_access('Create Task','');//passing action title and route group name
                     $REASSIGN_ACCESS    = Helper::can_access('Reassign','');//passing action title and route group name
                     $VIEW_ACCESS   = Helper::can_access('View Task','');//passing action title and route group name
                     
                     @endphp
                  <div class="col-md-8 mt-2">
                     {{-- <h4 class="card-title mb-1"> Tasks</h4>
                     <p> List of all Task </p> --}}
                  </div>
                 
                  
               </div>
               <div class="row">
                  <div class="col-md-1 form-group mb-3">
                     <label for="picker1"> Export </label>
                     <select class="form-control check"  id="check">
                        <option value="">-Select-</option>
                        <option value="pdf">Excel</option>   
                     </select>
                  </div>
                  <div class="col-md-7 form-group mt-4">
                        <a class="btn-link " id="exportExcel" href="javascript:;"> <i class="far fa-file-archive"></i> Download Excel</a> 
                        <p style="margin-bottom:2px;" class="load_container text-danger" id="loading"></p>
                  </div>
                     <div class="col-md-2 form-group mt-4" >
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
                     <div class="col-md-1 mt-2">
                        <div class="btn-group" style="float:right">
                         
                          <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>   
                        </div>
                     </div>
               </div>
               <div class="search-drop-field" id="search-drop">
                  
              </div>
              <input type="hidden" name="verify_status" id="verify_status" value={{$verify_status}}>
              <input type="hidden" name="t_type" id="t_type" value={{$t_type}}>
              <input type="hidden" name="task_start_date" id="task_start_date" value={{$task_start_date}}>
              <input type="hidden" name="task_end_date" id="task_end_date" value={{$task_end_date}}>
              <input type="hidden" name="insuff" id="insuff" value={{$insuff}}>
              <input type="hidden" name="in_tat" id="in_tat" value={{$in_tat}}>
              <input type="hidden" name="out_tat" id="out_tat" value={{$out_tat}}>
              <input type="hidden" name="task_service" id="task_service" value={{$task_service}}>
              <input type="hidden" name="task_user" id="task_user" value={{$task_user}}>
               <div id="taskResult">
                  @include('admin.task.assign.ajax')
                  
               </div>
            </div>
         </div>
      {{-- </div> --}}
   </div>
   </div>
</div>
{{-- Modal for Report generation  Reassign --}}
<div class="modal" id="report_reassign_task">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
         <h4 class="modal-title">Task Reassign</h4>
         <button type="button" class="close" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{ url('/task/report/reassign') }}" id="report_reassign_form">
            @csrf
            <input type="hidden" name="report_user_id" id="report_user_id">
            <input type="hidden" name="report_business_id" id="report_business_id">
            <input type="hidden" name="report_candidate_id" id="report_candidate_id" >
            {{-- <input type="hidden" name="service_id" id="service_id"> --}}
            <input type="hidden" name="report_task_id" id="report_task_id">
            <input type="hidden" name="report_job_sla_item_id" id="report_job_sla_item_id">
            <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
            {{-- <div class="form-group">
            <label for="tat">TAT</label>
            <input type="text" name="tat" class="form-control" id="tat" placeholder="Enter tat" value="{{ old('tat') }}">
            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-tat"></p> 
            </div> --}}
            <div class="form-group">
               <label for="label_name">Re-Assign To <span class="text-danger">*</span> </label>
               {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
               <div class="form-group">
                  <select class="select-option-field-7 report_user selectValue form-control" name="report_user" id="report_user" data-type="user" data-t="{{ csrf_token() }}">
                     <option value="">Select user</option>
                     {{-- @foreach ($users as $key => $user) {
                        @foreach ($action_master as $key => $am) {
                        
                           @if ( in_array($am->id,json_decode($user->permission_id)) && $am->action_title == 'JAF Link') {
                              <option value="{{$user->id}}" >{{$user->name}}</option>
                           @endif
                        @endforeach
                     @endforeach --}}
                     {{-- <option value="{{$user->id}}" >{{$user->name}}</option> --}}
                     {{-- @endforeach --}}
                  </select>
                  
                  {{-- @if ($errors->has('user'))
                     <div class="error text-danger">
                  {{ $errors->first('user') }}
                  </div>
                  @endif --}}
               </div>
                <p style="margin-bottom: 2px;" class="text-danger" id="error-report_user"></p>
            </div>
            
            </div>
            <!-- Modal successfooter -->
            <div class="modal-footer">
               <button type="button" class="btn btn-info report_reassign_submit"  id="report_reassign_submit">Submit </button>
               <button type="button" class="btn btn-danger report_back" id="report_reassign_back" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- End of Report generation Model --}}
{{-- Assign Task --}}
<div class="modal" id="assign_modal">
   <div class="modal-dialog">
      <div class="modal-content">
      <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" >Assign To</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
      <!-- Modal body -->
         <form method="post" action="{{url('/task/user/assign')}}" id="assign_form">
         @csrf
         {{-- <input type="hid den" name="user_id" id="users"> --}}
         <input type="hidden" name="business_id" id="businesss">
         <input type="hidden" name="candidate_id" id="candidates_id" >
         {{-- <input type="hidden" name="service_id" id="service_id"> --}}
         <input type="hidden" name="task_id" id="tasks">
         <input type="hidden" name="job_sla_item_id" id="job_sla_items">
         <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
            <div class="form-group">
               <label for="label_name">Assign To </label>
               {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
               <div class="form-group">
                  <select class="select-option-field-7 users selectValue form-control" name="users" data-type="users" data-t="{{ csrf_token() }}">
                     <option value="">Select user</option>
                     @foreach ($users as $key => $user) {
                        @foreach ($action_master as $key => $am) {
                           @if ( in_array($am->id,json_decode($user->permission_id)) && $am->action_title == 'JAF Link') {
                           <option value="{{$user->id}}" >{{$user->name}}</option>
                           @endif
                        @endforeach
                     @endforeach
                  </select>
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-users"></p>
               </div>
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-info btn-submit submit">Submit </button>
            <button type="button" class="btn btn-danger back " data-dismiss="modal">Close</button>
         </div>
         </form>
      </div>
   </div>
</div>
{{-- End of Assign Task Model --}}

{{-- Assign Verify Task --}}
<div class="modal" id="verify_assign_modal">
   <div class="modal-dialog">
      <div class="modal-content">
      <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" >Assign To</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
      <!-- Modal body -->
         <form method="post" action="{{url('/task/user/assign')}}" id="verify_assign_form">
         @csrf
         {{-- <input type="hid den" name="user_id" id="users"> --}}
         <input type="hidden" name="business_id" id="businesss_id">
         <input type="hidden" name="verify_candidate" id="verify_candidate" >
         {{-- <input type="hidden" name="service_id" id="service_id"> --}}
         <input type="hidden" name="verify_task_id" id="verify_task_id">
         <input type="hidden" name="job_sla_items_id" id="job_sla_items_id">
         <input type="hidden" name="type" id="settype">
         <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
            <div class="form-group">
               <label for="label_name">Assign To </label>
               {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
               <div class="form-group">
                  <select class="select-option-field-7 users selectValue form-control" name="users" data-type="users" data-t="{{ csrf_token() }}" required>
                     
                  </select>
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-users"></p>
               </div>
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-info btn-submit submit">Submit </button>
            <button type="button" class="btn btn-danger back " data-dismiss="modal">Close</button>
         </div>
         </form>
      </div>
   </div>
</div>
{{-- End of Assign Verify Task Model --}}

{{-- Modal for JAF FILLING Reassign --}}
<div class="modal" id="task">
      <div class="modal-dialog">
         <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
            <h4 class="modal-title">Task Reassign</h4>
            <button type="button" class="close" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <form method="post" action="{{url('/task/reassign')}}" id="task">
            @csrf
               <input type="hidden" name="user_id" id="user_id">
               <input type="hidden" name="business_id" id="business_id">
               <input type="hidden" name="candidate_id" id="candidate_id" >
               {{-- <input type="hidden" name="service_id" id="service_id"> --}}
               <input type="hidden" name="task_id" id="task_id">
               <input type="hidden" name="job_sla_item_id" id="job_sla_item_id">
               <div class="modal-body">
               <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
               {{-- <div class="form-group">
               <label for="tat">TAT</label>
               <input type="text" name="tat" class="form-control" id="tat" placeholder="Enter tat" value="{{ old('tat') }}">
               <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-tat"></p> 
               </div> --}}
               <div class="form-group">
               <label for="label_name">Re-Assign To <span class="text-danger">*</span> </label>
               {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
               <div class="form-group">
                  <select class="select-option-field-7 user selectValue form-control" name="user" id="filling_user_name" data-type="user" data-t="{{ csrf_token() }}">

               {{-- <select class="select-option-field-7 user selectValue form-control" name="user" data-type="user" data-t="{{ csrf_token() }}"> --}}
               <option value="">Select user</option>
               {{-- @foreach ($users as $key => $user) {
                  @foreach ($action_master as $key => $am) {
                  
                     @if ( in_array($am->id,json_decode($user->permission_id)) && $am->action_title == 'JAF Link') {
                        <option value="{{$user->id}}" >{{$user->name}}</option>
                     @endif
                  @endforeach
               @endforeach --}}
               {{-- <option value="{{$user->id}}" >{{$user->name}}</option> --}}
               {{-- @endforeach --}}
               </select>
               
               {{-- @if ($errors->has('user'))
               <div class="error text-danger">
               {{ $errors->first('user') }}
               </div>
               @endif --}}
               </div>
               {{-- </div> <p style="margin-bottom: 2px;" class="text-danger" id="error-assign"></p> --}}
               </div>
               
               </div>
               <!-- Modal successfooter -->
               <div class="modal-footer">
               <button type="submit" class="btn btn-info " >Submit </button>
               <button type="button" class="btn btn-danger back" data-dismiss="modal">Close</button>
               </div>
            </form>
         </div>
      </div>
</div>
{{-- End of JAF Filling Model --}}


{{-- Modal for JAF Verification Task Reassign --}}
<div class="modal" id="verify_reassign_task">
   <div class="modal-dialog">
      <div class="modal-content">
      <!-- Modal Header -->
         <div class="modal-header">
         <h4 class="modal-title">Task Reassign</h4>
         <button type="button" class="close" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{url('/task/verification/reassign')}}" id="verify_task_form">
         @csrf
            {{-- <input type="hidden" name="user" id="use"> --}}
            <input type="hidden" name="business" id="business">
            <input type="hidden" name="candidat_id" id="candidat_id" >
            <input type="hidden" name="service" id="services">
            <input type="hidden" name="tasks_id" id="tasks_id">
            <input type="hidden" name="job_sla_item" id="job_sla_item">
            <input type="hidden" name="no_of_verification" id="no_of_verification">
            <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-name"> </p>
            <div class="form-group">
               <label>User type<span class="text-danger">*</span></label>
            </div>
            <div class="form-group">
               <input  type="radio" class="reassign_user_status" id="reassign_user_status" name="reassign_user_status" value="user" ><label for="reassign_user_status"> User</label>
               <input type="radio" class="reassign_user_status" id="reassign_vendor_status" name="reassign_user_status" value="vendor"><label for="reassign_vendor_status"> Vendor</label>
               <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-reassign_user_status"></p>
           </div>
               <div class="form-group">
                  <label for="label_name">Re-Assign To <span class="text-danger">*</span> </label>
                  {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
                  <div class="form-group">
                     <select class="select-option-field-7 user selectValue form-control" name="user" id="user_name" data-type="user" data-t="{{ csrf_token() }}" required>
                     
                     </select>
                  </div>
                  <p style="margin-bottom: 2px;" class="text-danger" id="error-user"></p>
               {{-- </div> --}}
               </div>
               <div class="form-group reassign_vendor_sla d-none" >
                  <label for="label_name">Vendor Sla </label>
                  {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
                  <div class="form-group">
                     <select class="select-option-field-7 reassign_sla_id selectValue form-control" id="reassign_vendor_sla_id" name="reassign_sla_id" data-type="vendor_sla" data-t="{{ csrf_token() }}" >
                        
                     </select>
                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-reassign_sla_id"></p>
                  </div>
               </div>
            </div>
            <!-- Modal successfooter -->
            <div class="modal-footer">
            <button type="submit" class="btn btn-info verify_reassign_submit " >Submit </button>
            <button type="button" class="btn btn-danger back" id="verify_reassign_back" data-dismiss="modal">Close</button>
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
            <h4 class="modal-title" id="serv_name"></h4>
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

{{-- Modal to task verify data --}}
 <div class="modal"  id="user_task_assign">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" id="user_task_serv_name"></h4>
            <button type="button " class=" close_user_task_assign " style="top: 10px;!important; color: red; font-size: 40px;font-weight: bold; transition: 0.3s; background:transparent; border:none;" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         
            <div class="modal-body">
               <div id="user_task_assign_data">

               </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="button" class="btn btn-danger close_user_task_assign" data-dismiss="modal">Close</button>
            </div>
      </div>
   </div>
</div>
{{-- Modal for otp verification    --}}
 <div class="modal" id="send_otp">
   <div class="modal-dialog">
   <div class="modal-content">
       <!-- Modal Header -->
       <div class="modal-header">
           <h4 class="modal-title">OTP Verification to Generate Report</h4>
           {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
       </div>
       <!-- Modal body -->
       <form method="post" action="{{url('/candidates/verfiy_otp')}}" id="verify_otp">
       @csrf
           <input type="hidden" name="can_id" id="can_id" class="cand_id">
           <div class="modal-body">
               <div class="form-group">
                   <label for="label_name"> <strong> Candidate Name : </strong> <span class="c_name"></span> </label>
               </div>
               <div class="form-group pb-3">
                   <label for="label_name"> <strong> Reference No. : </strong> <span class="c_ref_no"></span> </label>
               </div>
               <div class="form-group">
                   <div class="row justify-content-center align-items-center">
                       <div class="col-sm-5 text-center">
                           <label for="label_name"> OTP </label>
                       </div>
                   </div>
                   <div class="row justify-content-center align-items-center">
                       <div class="col-sm-6 text-center">
                           <input name="otp[]" class="digit text-center otp" type="text" id="first_otp" size="1" maxlength="1" tabindex="0" >
                           <input name="otp[]" class="digit text-center otp" type="text" id="second_otp" size="1" maxlength="1" tabindex="1">
                           <input name="otp[]" class="digit text-center otp" type="text" id="third_otp" size="1" maxlength="1"  tabindex="2">
                           <input name="otp[]" class="digit text-center otp" type="text" id="fourth_otp" size="1" maxlength="1" tabindex="3">
                       </div>
                   </div>
                   <div class="row justify-content-center align-items-center">
                       <div class="col-sm-6 text-center">
                           <p style="margin-bottom: 2px;" class="text-danger error-container pt-2" id="error-otp"></p> 
                           <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                       </div>
                   </div>
               </div>
               {{-- <div class="form-group">
                   <label for="label_name"> OTP </label>
                   <input type="text" id="otp" name="otp" class="form-control otp" placeholder="Enter OTP"/>
                   <p style="margin-bottom: 2px;" class="text-danger" id="error-otp"></p> 
               </div> --}}
           </div>
           <!-- Modal footer -->
           <div class="modal-footer">
               <button type="submit" class="btn btn-info otp_submit btn_otp">Submit </button>
               <button type="button" class="btn btn-danger btn_otp" id="otp_close" data-dismiss="modal">Close</button>
           </div>
       </form>
   </div>
   </div>
</div>
<!-- Script -->


<script type="text/javascript">
      // Select all check
      function checkAll(e) {
            var checkboxes = document.getElementsByName('checks');
            
            if (e.checked) {
               for (var i = 0; i < checkboxes.length; i++) { 
               checkboxes[i].checked = true;
               }
            } else {
               for (var i = 0; i < checkboxes.length; i++) {
               checkboxes[i].checked = false;
               }
            }
      }
      function checkChange(){

            var totalCheckbox = document.querySelectorAll('input[name="checks"]').length;
            var totalChecked = document.querySelectorAll('input[name="checks"]:checked').length;

            // When total options equals to total checked option
            if(totalCheckbox == totalChecked) {
            document.getElementsByName("showhide")[0].checked=true;
            } else {
            document.getElementsByName("showhide")[0].checked=false;
            }
      }

   $(document).ready(function(){

      // $("#candidate").select2();
      // $("#customer").select2();
      // $("#sla").select2();
      // $("#user").select2();
      // $(".check").select2();
      // $(".rows").select2();
      // $("#ref").select2();
      // $('.filter0search').click(function(){
      //    $('.search-drop-field').toggle();
      // });
     
      // $('.filter_close').click(function(){
      //    $('.search-drop-field').toggle();
      // });

      setTimeout(()=>{
        $.ajax({
            type:'POST',
            url: "{{url('/task/assign/filter')}}",
            data:{'_token':"{{ csrf_token() }}"} ,
            beforeSend: function() {
                $('.search-drop-field').html(loaderHtml());
            }, 
            success: function (response) {        
                //console.log(response);

               $('.search-drop-field').html(response.html);
                
            },
            error: function (xhr, textStatus, errorThrown) {
                alert("Error: " + errorThrown);
            }
        });
      },500);
      
      $('.customer_list').on('select2:select', function (e){
        var data = e.params.data.id;
        //loader
        $("#overlay").fadeIn(300);　
        getData(0);
        setData();
        e.preventDefault();
      });
      $(document).on('click', '.resetBtn' ,function(){

         $("input[type=text], textarea").val("");
         //   $('.customer_list').val('');
         //    $('.candidate').val('');
         $('.user_list').empty();
         $('.user_typ').val('');
         $('#candidate').val(null).trigger('change');
         $('#customer').val(null).trigger('change');
         $('#ref').val(null).trigger('change');
         // $('#user').val(null).trigger('change');
         $('#service').val('');
         $('#task_type').val('');
         $('#assign_status').val('');
         $('#complete_status').val('');
         var uriNum = location.hash;
         pageNumber = uriNum.replace("#","");
         // alert(pageNumber);
         getData(pageNumber);
      });
      $(document).on('click','.task_verify',function(){
         var verify_candidate_id=$(this).attr('data-task_verify_can_id');
         var verify_service_id=$(this).attr('data-task_verify_service_id');
         var verify_number_id=$(this).attr('data-task_verify_nov_id');

         // console.log(task_id);data-task_verify_service_id
         // data-task_verify_nov_id
         // alert('abc');
         $.ajax({
            type:'GET',
            url: "{{url('/task/verify/info')}}",
            data: {'verify_candidate_id':verify_candidate_id,'verify_service_id':verify_service_id,'verify_number_id':verify_number_id},        
            success: function (response) {        
               console.log(response);

               $('#user_task_assign_data').html(response.html);
               $('#user_task_assign').modal({
                     backdrop: 'static',
                     keyboard: false
               });
            
            },
            error: function (xhr, textStatus, errorThrown) {
               alert("Error: " + errorThrown);
            }
         });
         // $('#user_task_assign').modal({
         //    backdrop: 'static',
         //    keyboard: false
         // });
      });

       //Task Verification Submit
       $(document).on('submit','form#jaf_form',function (event) {
         event.preventDefault();
         //clearing the error msg
         $('p.error-container').html("");

         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
         $('.jaf_info_submit').attr('disabled',true);
         // $('.form-control').attr('readonly',true);
         // $('.form-control').addClass('disabled-link');
         $('.error-control').attr('readonly',true);
         $('.error-control').addClass('disabled-link');
         if ($('.jaf_info_submit').html() !== loadingText) {
               $('.jaf_info_submit').html(loadingText);
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
                     $('.jaf_info_submit').attr('disabled',false);
                     // $('.form-control').attr('readonly',false);
                     // $('.form-control').removeClass('disabled-link');
                     $('.error-control').attr('readonly',false);
                     $('.error-control').removeClass('disabled-link');
                     $('.jaf_info_submit').html('Update');
                  },2000);
               console.log(response);
               if(response.success==true) {          
                     // var case_id = response.case_id;
                     //notify
                     toastr.success("Candidate JAF Updated Successfully");
                     // redirect to google after 5 seconds
                     $('#user_task_assign').modal('hide');
   
                     var uriNum = location.hash;
                     pageNumber = uriNum.replace("#","");
                     // alert(pageNumber);
                     getData(pageNumber);
                    
                     // window.setTimeout(function() {
                     //     //window.location = "{{ url('/')}}"+"/candidates/jaf-info/"+case_id;
                     //    window.location.reload();
                     // }, 2000);
               
               }
               //show the form validates error
               if(response.success==false ) {                              
                     for (control in response.errors) {   
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

      $(document).on('change','.user_typ',function(e){
         var user_typ = $(this).val();
         // alert(user_typ);
         e.preventDefault();
            $('.user_list').empty();
            $('.user_list').append("<option value=''>-All-</option>");
            // var customer_id = $('.customer_list option:selected').val();
            $.ajax({
               type:"POST",
               url: "{{ url('/task/getuserlist') }}",
               data: {"_token": "{{ csrf_token() }}",'user_type':user_typ},      
               success: function (response) {
                  console.log(response);
                  if(response.success==true  ) {   
                     $.each(response.data, function (i, item) {
                      
                        if (item.last_name==null) {
                           last_name ='';
                        } else {
                           last_name=item.last_name;
                        }
                       
                        $(".user_list").append("<option value='"+item.id+"'> "+item.id+"-" + item.first_name +' '+last_name+ "</option>");
                     });
                   
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
      $(document).on('change','.from_date, .to_date,.customer_list, .candidate_list,.sla_list,.user_list,.ref_list,#rows,#service,#task_type,.ref', function (e){    
         $("#overlay").fadeIn(300);　
         getData(0);
         e.preventDefault();
      });

      $(document).on('click','.filterBtn', function (e){    
        $("#overlay").fadeIn(300);　
        getData(0);
        e.preventDefault();
      });

      //
      $(document).on('change','.customer_list',function(e) {
               e.preventDefault();
               $('.candidate_list').empty();
               $('.candidate_list').append("<option value=''>-All-</option>");

               $('.sla_list').empty();
               $('.sla_list').append("<option value=''>-All-</option>");
               var customer_id = $('.customer_list option:selected').val();
                var last_name ='';
               $.ajax({
               type:"POST",
               url: "{{ url('/candidates/getslalist') }}",
               data: {"_token": "{{ csrf_token() }}",'customer_id':customer_id},      
               success: function (response) {
                  console.log(response);
                  if(response.success==true  ) {   
                     $.each(response.data, function (i, item) {
                        if (item.last_name==null) {
                           last_name ='';
                        } else {
                           last_name=item.last_name;
                        }
                        $(".candidate_list").append("<option value='"+item.id+"'> "+item.id+"-" + item.first_name +' '+last_name+ "</option>");
                     });
                     $.each(response.data1,function(i,item){
                        $(".sla_list").append("<option value='"+item.id+"'> " + item.title + "</option>");
                     });
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


          // print visits  
    $(document).on('click','#exportExcel',function(){
      //   setData();
      //   var candidate = $(".reports option:selected").val();
      var _this=$(this);
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';
        $('p.load_container').html("");
        var task_arr = [];
        var i = 0;
        
        $('.checks:checked').each(function () {
            task_arr[i++] = $(this).val();
        });

       
        
        if((task_arr.length)>0){
                  _this.addClass('disabled-link');
                  $('#loading').html(loadingText);
         // alert(candidate_arr);
            //
                              
               //  var check       =    $(".check option:selected").val();
                var from_date   =    $(".from_date").val(); 
                var to_date     =    $(".to_date").val();    
               //  var candidate_id=    candidate_arr;                           

                $.ajax(
                {
                    url: "{{ url('/') }}"+'/task/setData/',
                    type: "get",
                    data:{'task_id':task_arr,'from_date':from_date,'to_date':to_date},
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
                var path = "{{ url('task/checks-export')}}";
                    window.open(path);
                })
                .fail(function(jqXHR, ajaxOptions, thrownError)
                {
                    //alert('No response from server');
                });
            //
        
        }else{
            alert('Please select a check to export! ');
            }
    });
  // Reassign Task to one user to another for JAF filling
  $(document).on('click','.report_reaasign',function(){
         
         var report_user_id = $(this).attr('data-user');
         var report_business_id = $(this).attr('data-business');
         var report_candidate_id = $(this).attr('data-candidate');
         var report_service_id = $(this).attr('data-service');
         var report_task_id = $(this).attr('data-task');
         var report_job_sla_item_id = $(this).attr('data-jsi');

         $('#report_user_id').val(report_user_id);
         $('#report_business_id').val(report_business_id);
         $('#report_candidate_id').val(report_candidate_id);
         $('#report_service_id').val(report_service_id);        
         $('#report_task_id').val(report_task_id);
         $('#report_job_sla_item_id').val(report_job_sla_item_id);
         // alert(business_id);

         // $('#task').html("Submit");
         $('#report_reassign_task').modal({
                 backdrop: 'static',
                 keyboard: false
            });
         // $('#task').toggle();
        

         $.ajax({
            type: 'GET',
            url:"{{ url('/task/report_reassign_modal') }}",
            data: {'report_service_id':report_service_id,'report_candidate_id':report_candidate_id,'report_task_id':report_task_id},
                
            success: function (data) {
                  // console.log(data.success);
               $('.error-container').html('');
               if (data.fail && data.error == '') {
                     //console.log(data.success);
                        $('.error').html(data.message);
               }
               
               if (data.fail == false ) {
                     
                     $("#report_user").html(data.data);
                    
               }
            } 
         
         });
      });
      $(document).on('click','.report_reassign_submit',function(){
         var reportReassignFormData = new FormData($("#report_reassign_form")[0]);

         $('#report_reassign_back').prop('disabled',true);
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
         $('#report_reassign_submit').html("");
         $('#report_reassign_submit').html(loadingText);
         $.ajax(
               {
                  type: 'post',
                  url:"{{ url('/task/report/reassign') }}",
                  data:reportReassignFormData, 
                  processData: false,
                  contentType: false,
                  success: function (data) {
                     console.log(data.success);
                     $('.error-container').html('');
                     if ( data.success == false  ) {
                         
                        window.setTimeout(function(){
                              _this.removeClass('disabled-link');
                              $('#report_reassign_submit').html("Submit");
                              // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                        },1000);
                        for (control in data.errors) {
                           $('.' + control).addClass('border-danger');
                           $('#error-' + control).html(data.errors[control]);
                        }
                           //  console.log(data.success);
                              // $('.error').html(data.message);
                           //    toastr.success("Tasks has been Already Assigned ");
                           // // redirect to google after 5 seconds
                           // window.setTimeout(function() {
                           //       window.location = "{{ url('/')}}"+"/task/";
                           // }, 2000);
                     }
                     if (data.success == true ) {

                           window.setTimeout(function(){
                              _this.removeClass('disabled-link');
                              $('#report_reassign_submit').html("Submit");
                              // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                           },1000);
                        if (data.custom =='yes') {
                           toastr.success("Task has been Re-assigned successfully");
                        }
                        else{
                           toastr.success("Task has not been Re-assigned to any user,Please check the user permissions!");
                        }
                        // toastr.success("Task has been assigned successfully");
                           // redirect to google after 5 seconds
                           window.setTimeout(function() {
                                 window.location = "{{ url('/')}}"+"/task/";
                           }, 2000);
                        
                     }
                  },
                  error: function (response) {
                     console.log(response);
                  } 
                  
               });


      });

      // Assign Verification Task to one user to another for JAF filling
      //  $('.assign').click(function(){
      $(document).on('click','.assign',function(){

         var current = $(this);
         // var user_id = $(this).attr('data-user');
         var business_id = $(this).attr('data-business');
         var candidate_id = $(this).attr('data-candidate');
         var service = $(this).attr('data-service');
         var task_id = $(this).attr('data-task');
         var job_sla_item_id = $(this).attr('data-jsi');
         // var number = document.getElementById('no_of_user').value;
         // var user = $(this).attr('data-user_id');

         // $('#users').val(user_id);
         $('#businesss_id').val(business_id);
         $('#verify_candidate').val(candidate_id);
         // $('#service_id').val(service_id);        
         $('#verify_task_id').val(task_id);
         $('#job_sla_items_id').val(job_sla_item_id);
         $('#settype').val('verify_task');
         // alert(candidate_id);

         // var services = 


            $('#verify_assign_modal').toggle();


            $.ajax({
               type: 'GET',
               url:"{{ url('/task/assign_modal') }}",
               data: {'service_id':service},
                   
               success: function (data) {
                     // console.log(data.success);
                  $('.error-container').html('');
                  if (data.fail && data.error == '') {
                        //    console.log(data.success);
                           $('.error').html(data.message);
                  }
                  
                  
                  if (data.fail == false ) {
                        
                        $(".users").html(data.data);
                       
                  }
               } 
            
            });

            $('.back').click(function(){
               $('#verify_assign_modal').hide();
            });         
            $('.submit').on('click', function() {
                  var $this = $(this);
                  var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
                  if ($(this).html() !== loadingText) {
                     $this.data('original-text', $(this).html());
                     $this.html(loadingText);
                     // $this.prop('disabled',true);
                  }
                  setTimeout(function() {
                     $this.html($this.data('original-text'));
                     $this.prop('disabled',false);
                  }, 5000);
            });

            $('#verifyAssignBtn').click(function(e) {
                  e.preventDefault();
                  $("#verify_assign_form").submit();
            });

            $(document).on('submit', 'form#verify_assign_form', function (event) {
               event.preventDefault();
               //clearing the error msg
               $('p.error-container').html("");
               
            
               var form = $(this);
               var _this =$(this);
               var data = new FormData($(this)[0]);
               var url = form.attr("action");

               _this.find('.btn-submit').attr('disabled', true);

               $.ajax({
                     type: form.attr('method'),
                     url: url,
                     data: data,
                     cache: false,
                     contentType: false,
                     processData: false,      
                     success: function (response) {
            
                        console.log(response);
                        if(response.success==true) {          
                           // _this.find('.btn-submit').attr('disabled', false);
                           //notify
                           toastr.success("Task Assignment Successfully");
                           // redirect to google after 5 seconds
                           window.setTimeout(function() {
                                 window.location = "{{ url('/')}}"+"/task/";
                           }, 2000);
                           
                        }
                        //show the form validates error
                        if(response.success==false ) { 
                           // _this.find('.btn-submit').attr('disabled', false);                             
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
      });

      //Assign user for task
      $(document).on('click','.assign_user',function(){


         var current = $(this);
         // var user_id = $(this).attr('data-user');
         var business_id = $(this).attr('data-business');
         var candidate_id = $(this).attr('data-candidate');
         // var service_id = $(this).attr('data-service');
         var task_id = $(this).attr('data-task');
         var job_sla_item_id = $(this).attr('data-jsi');
         // var number = document.getElementById('no_of_user').value;
         // var user = $(this).attr('data-user_id');

         // $('#users').val(user_id);
         $('#businesss').val(business_id);
         $('#candidates_id').val(candidate_id);
         // $('#service_id').val(service_id);        
         $('#tasks').val(task_id);
         $('#job_sla_items').val(job_sla_item_id);
         // alert(candidate_id);



         $('#assign_modal').toggle();

        


         $('.back').click(function(){
            $('#assign_modal').hide();
         });         
         $('.submit').on('click', function() {
            var $this = $(this);
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            if ($(this).html() !== loadingText) {
               $this.data('original-text', $(this).html());
               $this.html(loadingText);
               // $this.prop('disabled',true);
            }
            setTimeout(function() {
               $this.html($this.data('original-text'));
               $this.prop('disabled',false);
            }, 5000);
         });

         $('#assignBtn').click(function(e) {
               e.preventDefault();
               $("#assign_form").submit();
         });

         $(document).on('submit', 'form#assign_form', function (event) {
            event.preventDefault();
            //clearing the error msg
            $('p.error-container').html("");
            

            var form = $(this);
            var _this =$(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");

            _this.find('.btn-submit').attr('disabled', true);

            $.ajax
            ({
                  type: form.attr('method'),
                  url: url,
                  data: data,
                  cache: false,
                  contentType: false,
                  processData: false,      
                  success: function (response) {

                     console.log(response);
                     if(response.success==true) {          
                        // _this.find('.btn-submit').attr('disabled', false);
                        //notify
                        toastr.success("Task Assignment Successfully");
                        // redirect to google after 5 seconds
                        window.setTimeout(function() {
                              window.location = "{{ url('/')}}"+"/task/";
                        }, 2000);
                        
                     }
                     //show the form validates error
                     if(response.success==false ) { 
                        // _this.find('.btn-submit').attr('disabled', false);                             
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
      });

      // Reassign Task to one user to another for JAF filling
      $(document).on('click','.reaasign',function(){
            var user_id = $(this).attr('data-user');
            var business_id = $(this).attr('data-business');
            var candidate_id = $(this).attr('data-candidate');
            var service_id = $(this).attr('data-service');
            var task_id = $(this).attr('data-task');
            var job_sla_item_id = $(this).attr('data-jsi');

            $('#user_id').val(user_id);
            $('#business_id').val(business_id);
            $('#candidate_id').val(candidate_id);
            $('#service_id').val(service_id);        
            $('#task_id').val(task_id);
            $('#job_sla_item_id').val(job_sla_item_id);
            // alert(business_id);
            // $('#task').toggle();
            $('#task').modal({
                    backdrop: 'static',
                    keyboard: false
                });

            $.ajax({
               type: 'GET',
               url:"{{ url('/task/filling_reassign_modal') }}",
               data: {'service_id':service_id,'candidate_id':candidate_id,'task_id':task_id},
                   
               success: function (data) {
                     // console.log(data.success);
                  $('.error-container').html('');
                  if (data.fail && data.error == '') {
                        //console.log(data.success);
                           $('.error').html(data.message);
                  }
                  
                  if (data.fail == false ) {
                        
                        $("#filling_user_name").html(data.data);
                       
                  }
               } 
            
            });
      });

      // $('.close').click(function(){
      //    $('#task').hide();
      // });
      // $('.back').click(function(){
      //    $('#task').hide();
      // });

    // Reassign Task to one user to another for JAF  Verification
    $(document).on('click','.verify_reaasign',function(){
         
         var user_id = $(this).attr('data-user_id');
         var business_id = $(this).attr('data-business_id');
         var candidate_id = $(this).attr('data-candidate_id');
         var service_id = $(this).attr('data-service_id');
         var task_id = $(this).attr('data-task_id');
         var job_sla_item_id = $(this).attr('data-jsi_id');
         var no_of_verification = $(this).attr('data-no_of_verification');
         // alert(service_id);
         $('#use').val(user_id);
         $('#business').val(business_id);
         $('#candidat_id').val(candidate_id);
         $('#services').val(service_id);        
         $('#tasks_id').val(task_id);
         $('#job_sla_item').val(job_sla_item_id);
         $('#no_of_verification').val(no_of_verification);
         // alert(business_id);
         $('#verify_reassign_task').toggle();
         $('#verify_reassign_back').click(function(){
            $('#verify_reassign_task').hide();
         });
         
});
$(document).on('change','.reassign_user_status',function(){
   var reassign_user_status = $("input[name=reassign_user_status]:checked").val();
   var reassign_service = $('#services').val();
   var reassign_candidate_id = $('#candidat_id').val();
   var reassign_no_of_verification = $('#no_of_verification').val();
   // alert(reassign_user_status);
      if (reassign_user_status == 'vendor') {
            $('.reassign_vendor_sla').removeClass("d-none");
      }
      else{
            $('.reassign_vendor_sla').addClass("d-none");
      }
      $.ajax({
         type: 'GET',
         url:"{{ url('/task/reassign_modal') }}",
         data: {'service_id':reassign_service,'candidate_id':reassign_candidate_id,'number_of_verifications':reassign_no_of_verification,'user_type':reassign_user_status},
             
         success: function (data) {
               // console.log(data.success);
            $('.error-container').html('');
            if (data.fail && data.error == '') {
                  //    console.log(data.success);
                     $('.error').html(data.message);
            }
            
            
            if (data.fail == false ) {
                  
                  $("#user_name").html(data.data);
                 
            }
         } 
      
      });



       //Sla List
       $('.user').on('change',function(){
         var assign_service =$('#services').val();
         var user_status = $("input[name=reassign_user_status]:checked").val();
         // $('#settype').val('verify_task');
         // e.preventDefault();
         // $('.users').empty();
         // $('.users').append("<option value=''>-All-</option>");
         var vendor_sla_id =$('#user_name option:selected').val();
         // alert (vendor_id);
         if (user_status == 'vendor') {
            // $('.vendor_sla_add').removeClass("d-none");
               $.ajax({
                  type: 'GET',
                  url:"{{ url('/task/reassign_vendor_sla') }}",
                  data: {'service_id':assign_service,'user_type':user_status,'vendor_id':vendor_sla_id},
                     
                  success: function (data) {
                        // console.log(data.success);
                     $('.error-container').html('');
                     if (data.fail && data.error == '') {
                           //    console.log(data.success);
                              $('.error').html(data.message);
                     }
                     if (data.fail == true) {
                        for (control in data.errors) {   
                              $('#error-' + control).html(data.errors[control]);
                        }
                     }
                     
                     if (data.fail == false ) {
                           
                           $("#reassign_vendor_sla_id").html(data.data);
                        
                     }
                  } 
      
               });
         }
         
         // else{
         //    $('.vendor_sla_add').addClass("d-none");
         // }

      });
         //verify reassign task submit
         $('.verify_reassign_submit').on('click', function() {
            $('#verify_reassign_back').prop('disabled',true);
            var $this = $(this);
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            if ($(this).html() !== loadingText) {
               $this.data('original-text', $(this).html());
               $this.html(loadingText);
            }
            setTimeout(function() {
               $this.html($this.data('original-text'));
            }, 5000);
         });
      
         $('#verifyTaskBtn').click(function(e) {
               e.preventDefault();
               $("#verify_task_form").submit();
         });
      
         $(document).on('submit', 'form#verify_task_form', function (event) {
            event.preventDefault();
            //clearing the error msg
            $('p.error_container').html("");
         
            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
      
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
                        toastr.success("Task has been Reassigned successfully");
                        // redirect to google after 5 seconds
                        window.setTimeout(function() {
                              window.location = "{{ url('/')}}"+"/task/";
                        }, 2000);
                        
                     }
                     //show the form validates error
                     if(response.success==false ) {                              
                        for (control in response.errors) {   
                              $('#error-' + control).html(response.errors[control]);
                        }
                     }
                  },
                  error: function (response) {
                     console.log(response);
                  }
                  // error: function (xhr, textStatus, errorThrown) {
                  //    // alert("Error: " + errorThrown);
                  // }
            });
            event.stopImmediatePropagation();
            return false;
         }); 

});

$('.close').click(function(){
   $('#user_name').val('');
   $('#verify_reassign_task').hide();
});
$('.back').click(function(){
   $('#user_name').val('');
   $('#verify_reassign_task').hide();
});
   });

   function getData(page){
      //set data
      var selected=[];
      var i=0;
      // $('.ref_list option:selected').each(function () {
      //    selected[i++] = $(this).val();
      // });

      var user_id     =    $(".customer_list").val()==undefined?'':$(".customer_list").val();                
      // var check       =    $(".check option:selected").val()==undefined?'':$(".check option:selected").val();
      var sla_id   =     $(".sla_list option:selected").val()==undefined?'':$(".sla_list option:selected").val();
      var cus_user_id   =     $(".user_list option:selected").val()==undefined?'':$(".user_list option:selected").val();
      var ref = $('.ref').val()==undefined?'':$(".ref").val();
   
      var from_date   =    $(".from_date").val()==undefined?'':$(".from_date").val(); 
      var to_date     =    $(".to_date").val()==undefined?'':$(".to_date").val();      
      var candidate_id=    $(".candidate_list option:selected").val()==undefined?'':$(".candidate_list option:selected").val();
      var rows = $("#rows option:selected").val()==undefined?'':$("#rows option:selected").val();
      var service_id = $("#service option:selected").val()==undefined?'':$("#service option:selected").val();
      var task_type = $("#task_type option:selected").val()==undefined?'':$("#task_type option:selected").val();
      //   var mob = $('.mob').val();
       // var ref = $('.ref').val();
        var verify_status = $("#verify_status").val()!=undefined && $('#verify_status').val()!=null ? $('#verify_status').val() : '';
        var t_type = $("#t_type").val()!=undefined && $('#t_type').val()!=null ? $('#t_type').val() : '';
        var task_start_date = $("#task_start_date").val()!=undefined && $('#task_start_date').val()!=null ? $('#task_start_date').val() : '';
        var task_end_date = $("#task_end_date").val()!=undefined && $('#task_end_date').val()!=null ? $('#task_end_date').val() : '';
        var insuff = $("#insuff").val()!=undefined && $('#insuff').val()!=null ? $('#insuff').val() : '';
        var in_tat = $("#in_tat").val()!=undefined && $('#in_tat').val()!=null ? $('#in_tat').val() : '';
        var out_tat = $("#out_tat").val()!=undefined && $('#out_tat').val()!=null ? $('#out_tat').val() : '';
        var task_service = $("#task_service").val()!=undefined && $('#task_service').val()!=null ? $('#task_service').val() : '';
        var task_user = $("#task_user").val()!=undefined && $('#task_user').val()!=null ? $('#task_user').val() : '';
      //   var email = $('.email').val();
      //   var report_status=$('.report_status').val();               
   
         $('#taskResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
   
         $.ajax(
         {
               url: '?page=' + page+'&customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&sla_id='+sla_id+'&user_id='+cus_user_id+'&rows='+rows+'&service_id='+service_id+'&task_type='+task_type+'&ref_list='+selected+'&verify_status='+verify_status+'&t_type='+t_type+'&task_start_date='+task_start_date+'&task_end_date='+task_end_date+'&insuff='+insuff+'&in_tat='+in_tat+'&out_tat='+out_tat+'&ref='+ref+'&task_service='+task_service+'&task_user='+task_user,
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
   
      var selected=[];
      var i=0;
      $('.ref_list option:selected').each(function () {
         selected[i++] = $(this).val();
      });

      var user_id     =    $(".customer_list").val();                
      // var check       =    $(".check option:selected").val();
   
      var from_date   =    $(".from_date").val(); 
      var to_date     =    $(".to_date").val();    
      var candidate_id=    $(".candidate_list option:selected").val(); 
      var rows = $("#rows option:selected").val();
      var service_id = $("#service option:selected").val();
      var task_type = $("#task_type option:selected").val();                           
      // var mob = $('.mob').val();
      // var ref = $('.ref').val();
      // var email = $('.email').val();

      var sla_id   =     $(".sla_list option:selected").val();

      var cus_user_id   =     $(".user_list option:selected").val();

      // var report_status=$('.report_status').val();
            $.ajax(
            {
               url: "{{ url('/') }}"+'/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&sla_id='+sla_id+'&user_id='+cus_user_id+'&rows='+rows+'&service_id='+service_id+'&task_type='+task_type+'&ref_list='+selected,
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

   //Preview modal
   $(document).on('click','.preview_button',function(){
      $('#task_id').val("");
      $('#serv_name').text('uploaded verification data - '+""+"");
      var task_id=$(this).attr('data-tasks_id');
      var ser_name=$(this).attr('data-service_name');
      var cand_name=$(this).attr('data-candidate_name');
      // alert(cand_name);
      $('#task_id').val(task_id);
      $('#serv_name').text('Uploaded Verification Data - '+cand_name+","+ser_name);
      $.ajax({
            type:'GET',
            url: "{{url('/task/preview')}}",
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

   $(document).on('click','.send_report_otp',function(){
      // $('.send_report_otp').click(function(){
      var _this=$(this);
      var report_cand_id=$(this).attr('data-id');
     
      // console.log(report_cand_id);
      var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
      _this.addClass('disabled-link');
      if (_this.html() !== loadingText) {
         _this.html(loadingText);
      }
      $.ajax({
         url:"{{ route('/candidates/send_otp') }}",
         method:"POST",
         data:{"_token": "{{ csrf_token() }}",'_id':report_cand_id},      
         success:function(data)
         {
               window.setTimeout(function(){
                  _this.removeClass('disabled-link');
                  _this.html('Generate Report');
               },2000);
               console.log(data);
               if(data.fail == false)
               {
                  //notify
                  $('#verify_otp')[0].reset();
                  $('.cand_id').val(report_cand_id);
                  $('.otp').removeClass('border-danger');
                  $('.error-container').html('');
                  $('.c_name').html(data.data.name);
                  $('.c_ref_no').html(data.data.ref_no);
                  $('#send_otp').modal({
                     backdrop: 'static',
                     keyboard: false
                  });
                  // console.log(data.id);
               }
               else
               {
                  // alert('not working');
               }
         }
      });
      
   });

   $(document).on('submit', 'form#verify_otp', function (event) {
    
      $("#overlay").fadeIn(300);　
      event.preventDefault();
      var form = $(this);
      var data = new FormData($(this)[0]);
      var url = form.attr("action");
      var $btn = $(this);
      $('.btn_otp').attr('disabled',true);
      $('.otp').removeClass('border-danger');
      var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
      if($('.otp_submit').html()!=loadingText)
      {
         $('.otp_submit').html(loadingText);
      }
      $('.error-container').html('');
      $.ajax({
         type: form.attr('method'),
         url: url,
         data: data,
         cache: false,
         contentType: false,
         processData: false,
         success: function (data) {
               // console.log(data);
               // $('.error-container').html('');
               window.setTimeout(function(){
                  $('.btn_otp').attr('disabled',false);
                  $('.otp_submit').html('Submit');
               },2000);
               if (data.fail && data.error_type == 'validation') {
                     
                     //$("#overlay").fadeOut(300);
                     for (control in data.errors) {
                           $('.' + control).addClass('border-danger');
                           $('#error-' + control).html(data.errors[control]);
                     }
               } 
               if (data.fail && data.error == 'yes') {
                  
                  $('#error-all').html(data.message);
               }
               if (data.fail == false) {
                  // $('#send_otp').modal('hide');
                  // alert(data.id);
                  var candidate_id=data.id;
                  // alert('abd');
                  window.location="{{ url('/') }}"+"/candidate/report-generate/"+candidate_id;
                  
                  // window.location.href='{{ Config::get('app.admin_url')}}/aadharchecks/show';
                  //  location.reload(); 
               }
         },
         error: function (data) {
               console.log(data);
         }
         // error: function (xhr, textStatus, errorThrown) {
         //     console.log("Error: " + errorThrown);
         //     // alert("Error: " + errorThrown);

         // }
      });
      event.stopImmediatePropagation();
      return false;

   });
   function OTPInput() {
         const inputs = document.querySelectorAll('.otp');
         // alert(inputs.length);
         for (let i = 0; i < inputs.length; i++) 
         { 
            inputs[i].addEventListener('keyup', function(event) 
            { 
               if (event.key==="Backspace" ) 
               { 
                     inputs[i].value='' ; 
                     if (i !==0) inputs[i - 1].focus();
                     
               } 
               else { 
                     if (i===inputs.length - 1 && inputs[i].value !=='' ) 
                     { return true; } 
                     else if (event.keyCode> 47 && event.keyCode < 58) 
                     { 
                        inputs[i].value=event.key; 
                        if (i !==inputs.length - 1) inputs[i + 1].focus(); event.preventDefault(); 
                        
                     } 
                     else if (event.keyCode> 95 && event.keyCode < 106) 
                     { 
                        inputs[i].value=event.key; 
                        if (i !==inputs.length - 1) 
                        inputs[i + 1].focus(); event.preventDefault(); 
                        
                     }
               } 
               
            }); 
            
         } 
         
   } 
    OTPInput(); 

    function loaderHtml()
    {
        return "<div class='fa-3x' style='min-height:200px;display: flex;align-items: center;justify-content: center;'><i class='fas fa-spinner fa-pulse'></i></div>";
    }
</script>

<style>
   .app_status .select2-container {
    box-sizing: border-box;
    display: inline-block;
    margin: 0;
    position: relative;
    vertical-align: middle;
    z-index: 9999!important;
}
.select2-container.select2-container--default.select2-container--open{z-index:11111111;}
</style>
@endsection

