@extends('layouts.admin')
@section('content')
<style>
.select2-search__field{
   width:100% !important;
}
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
      <div class="row">
         {{-- <div class="col-md-12"> --}}
         <div class="card text-left">
            <div class="card-body">
               <div class="row">
                  <div class="col-md-12 text-center"><h2 class="mb-0">[Restricted]</h2></div>

                  <div class="col-md-12">
                     <ul class="nav nav-tabs nav-tabs-bottom">
                        <li class="nav-item"><a href="{{url('/task')}}" class="nav-link ">All Tasks</a></li>
                        <li class="nav-item"><a href="{{url('/task/assign')}}" class="nav-link">Assigned Tasks</a></li>
                        <li class="nav-item"><a href="{{url('/task/unassign')}}" class="nav-link active" active>Unassigned Tasks</a></li>
                        <li class="nav-item"><a href="{{url('/task/complete')}}" class="nav-link  ">Completed Tasks</a></li>
                        <li class="nav-item"><a href="{{url('/task/vendor')}}" class="nav-link">Vendor Tasks</a></li>
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
                  <div class="col-md-4 form-group mt-4">
                        <a class="btn-link " id="exportExcel" href="javascript:;"> <i class="far fa-file-archive"></i> Download Excel</a> 
                        <p style="margin-bottom:2px;" class="load_container text-danger" id="loading"></p>
                  </div>
                  <div class="col-md-3 form-group mt-4">
                     <a class="btn-link bulk_assign" id="bulkAssignPopup" href="javascript:;"> <i class="fas fa-user-plus"></i> Bulk Assign</a> <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-original-title="You can assign report generation case and task verification case in bulk from here"></i>
                     <p style="margin-bottom:2px;" class="load_container text-danger" id="bulk"></p>
                  </div>
                  {{-- <div class="col-md-3 form-group ">
                     <label for="picker1" ><strong>Assign Task</strong>  </label>
                     <select class="select-option-field-7 user selectValue form-control" name="user" data-type="user" >
                        <option value="">Select user</option>
                        @foreach ($users as $key => $user) {
                           <option value="{{$user->id}}" >{{$user->name}}</option>

                        @endforeach
                     </select>
                  </div>
                  <div class="col-md-1 form-group mt-4">
                     <a class="btn-link " id="bulkAssign" href="javascript:;"> <i class="fas fa-user-plus"></i> Assign</a> 
                     <p style="margin-bottom:2px;" class="load_container text-danger" id="bulk"></p>
                  </div> --}}
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
                      <div class="col-md-2 form-group mb-1">
                        <label>Reference number </label><br>
                        {{-- <select class="form-control ref_list select w-100" name="ref[]" id="ref" multiple="multiple">
                           @foreach ($candidates as $candidate)
                              <option value="{{$candidate->display_id }}"> {{$candidate->display_id }} </option>
                           @endforeach
                        </select> --}}
                        <input class="form-control ref" type="text" placeholder="reference number">
                     </div>
                      <div class="col-md-2 form-group mb-1 level_selector">
                        <label>Customer</label><br>
                        <select class="form-control customer_list select" name="customer" id="customer">
                           <option value=''>-All-</option>
                            @foreach($clients as $item)
                            <option value="{{$item->id}}"> {{ ucfirst($item->company_name)}} </option>
                            @endforeach
                        </select>
                     </div>
                      <div class="col-md-2 form-group mb-1 level_selector">
                          <label>Candidate Name</label><br>
                          <select class="form-control candidate_list select " name="candidate" id="candidate">
                           <option value=''>-Select-</option>
                          </select>
                      </div>
                      {{-- <div class="col-md-2 form-group mb-1 level_selector">
                        <label>SLA Name</label><br>
                        <select class="form-control sla_list select " name="sla" id="sla">
                           <option value=''>-Select-</option>

                        </select>
                       
                    </div>  --}}
                    <div class="col-md-2 form-group mb-1 level_selector">
                        <label>User's Name</label><br>
                        <select class="form-control user_list select" name="user" id="user">
                           <option value=''>-Select-</option>
                           @foreach($users_list as $item)
                           <option value="{{$item->id}}"> {{ ucfirst($item->name)}} </option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-2 form-group mb-1">
                        <label>Checks</label>
                        <select class="form-control "  name="service" id="service">
                           <option value="">Select</option>
                           @foreach ($services as $service)
                                 <option value="{{ $service->id }}" >{{ $service->name }}</option> 
                           @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 form-group mb-1">
                        <label>Task's Type</label>
                        <select class="form-control" name="task_type" id="task_type" >
                            <option value="">All</option>
                            <option  value="JAF Filling" >JAF Filling</option>
                            <option  value="Task for Verification " >Task Verification</option>
                            <option  value="Report generation" >Report Generation</option>
                        </select>
                    </div>
                     <div class="col-md-1">
                        <button class="btn btn-danger  resetBtn" style="padding: 7px;margin: 18px 0px;"> <i class="fas fa-refresh"></i>  Reset </button>
                     </div>
                      <div class="col-md-2">
                      <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                      </div>
                  </div>
              </div>
               <div id="taskResult">
                  @include('admin.task.unassign-ajax')
                  
               </div>
            </div>
         </div>
      {{-- </div> --}}
   </div>
   </div>
</div>
{{-- bulk Task --}}
<div class="modal" id="bulk_assign_modal">
   <div class="modal-dialog">
      <div class="modal-content">
      <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" id="assign_for" ></h4>
            {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
         </div>
         <!-- Modal body --> 
         <form   id="bulk_assign_form">
         @csrf
         <input type="hidden" name="bulk_task_time" id="bulk_task_time">
         <input type="hidden" name="bulk_business_id" id="bulk_businesss_id">
         <input type="hidden" name="bulk_verify_candidate" id="bulk_verify_candidate" >
         <input type="hidden" name="bulk_modal_service_id" id="bulk_modal_service_id">
         <input type="hidden" name="bulk_verify_task_id" id="bulk_verify_task_id">
         <input type="hidden" name="bulk_job_sla_items_id" id="bulk_job_sla_items_id">
         <input type="hidden" name="bulk_modal_task_time" id="bulk_modal_task_time">
         <input type="hidden" name="bulk_modal_created_time" id="bulk_modal_created_time">
         <input type="hidden" name="bulk_type" id="bulk_settype">
         <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
            {{-- <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p>  --}}
            <div class="form-group">
               <label>Task Type<span class="text-danger">*</span></label>
               <select class="form-control bulk_task_type" name="bulk_task_type" id="bulk_task_type" >
                  <option value="">Select</option>
                  <option  value="jaf">JAF Link</option>
                  <option  value="task" >Task for Verification</option>
                  <option  value="report" >Report Generation</option>
               </select>
            </div>
            <div class="form-group">
               <label>User type<span class="text-danger">*</span></label>
            </div>
            <div class="form-group" id="bulk_users_type">
               {{-- <input  type="radio" class="bulk_user_status" id="bulk_user_status" name="bulk_user_status" value="user" ><label for="bulk_user_status"> User</label>
               <input type="radio" class="bulk_user_status" id="bulk_vendor_status" name="bulk_user_status" value="vendor"><label for="bulk_vendor_status"> Vendor</label> --}}
               <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-user_status"></p>
           </div>
          
            <div class="form-group">
               <label for="label_name">Assign To </label>
               {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
               <div class="form-group">
                  <select class="select-option-field-7 bulk_users selectValue form-control" id="bulk_vendor_id" name="bulk_users" data-type="users" data-t="{{ csrf_token() }}" required>
                     
                  </select>
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-bulk_users"></p>
               </div>
            </div>
            <div class="form-group bulk_vendor_sla_add d-none" >
               
               {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
               <div class="form-group">
                  <label for="label_name">Vendor Sla </label>
                  <select class="select-option-field-7 bulk_vendor_sla selectValue form-control" id="bulk_vendor_sla" name="bulk_vendor_sla" data-type="vendor_sla" data-t="{{ csrf_token() }}" >
                     
                  </select>
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-bulk_vendor_sla"></p>
               </div>
               {{-- <div class="form-group">
                  <label for="label_name"> Attachments: </label>
                  <input type="file" name="attachment[]" id="attachment" multiple class="form-control attachment">
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-attachment"></p>  
               </div> --}}
            </div>

         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="button" class="btn btn-info  bulk_submit loading" id="bulk_loading">Submit </button>
            <button type="button" class="btn btn-danger back " id="bulk_assign_back" data-dismiss="modal">Close</button>
         </div>
         </form>
      </div>
   </div>
</div>

{{-- Report generation  --}}
<div class="modal" id="report_assign_modal">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
            <h4 class="modal-title " id="report_assign_for_jaf" ></h4>
           
         </div>
         <!-- Modal body -->
         <form method="post" action="{{url('/task/user/report/assign')}}" id="report_assign_form">
         @csrf
         {{-- <input type="hid den" name="user_id" id="users"> --}}
         <input type="hidden" name="report_business_id" id="report_businesss">
         <input type="hidden" name="report_candidate_id" id="report_candidates_id" >
         {{-- <input type="hidden" name="service_id" id="service_id"> --}}
         <input type="hidden" name="report_task_id" id="report_tasks">
         <input type="hidden" name="report_job_sla_item_id" id="report_job_sla_items">
         <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
            <div class="form-group">
               <label for="label_name">Assign To </label>
               {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
               <div class="form-group">
                  <select class="select-option-field-7 report_users selectValue form-control" id="report_users" name="report_users" data-type="users" data-t="{{ csrf_token() }}">
                     <option value="">Select user</option>
                     @foreach ($users as $key => $user) 
                        @foreach ($action_master as $key => $am) 
                           @if ( in_array($am->id,json_decode($user->permission_id)) && $am->action_title == 'Generate Candidate Reports') {
                              <option value="{{$user->id}}" >{{$user->name}}</option>
                           @endif
                        @endforeach
                     @endforeach
                  </select>
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-report_users"></p>
               </div>
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="button" class="btn btn-info btn-submit report_submit" id="report_submit">Submit </button>
            <button type="button" class="btn btn-danger report_back"  id="report_assign_back" data-dismiss="modal">Close</button>
         </div>
         </form>
      </div>
   </div>
</div>
{{-- End of Report generation  Model --}}
{{-- Assign Task --}}
<div class="modal" id="assign_modal">
   <div class="modal-dialog">
      <div class="modal-content">
      <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" id="assign_for_jaf" >Assign To</h4>
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
                     @foreach ($users as $key => $user) 
                        @foreach ($action_master as $key => $am) 
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
            <h4 class="modal-title" id="assign_for" >Assign To</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
      <!-- Modal body -->
         <form method="post" action="{{url('/task/user/assign')}}" id="verify_assign_form">
         @csrf
         {{-- <input type="hid den" name="user_id" id="users"> --}}
         <input type="hidden" name="task_time" id="task_time">
         <input type="hidden" name="business_id" id="businesss_id">
         <input type="hidden" name="verify_candidate" id="verify_candidate" >
         <input type="hidden" name="modal_service_id" id="modal_service_id">
         <input type="hidden" name="verify_task_id" id="verify_task_id">
         <input type="hidden" name="job_sla_items_id" id="job_sla_items_id">
         <input type="hidden" name="modal_task_time" id="modal_task_time">
         <input type="hidden" name="modal_created_time" id="modal_created_time">
         <input type="hidden" name="type" id="settype">
         <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
            {{-- <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p>  --}}
            <div class="form-group">
               <label>User type<span class="text-danger">*</span></label>
            </div>
            <div class="form-group">
               <input  type="radio" class="user_status" id="user_status" name="user_status" value="user" ><label for="user_status">User</label>
               <input type="radio" class="user_status" id="vendor_status" name="user_status" value="vendor"><label for="vendor_status"> Vendor</label>
               <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-user_status"></p>
           </div>
          
            <div class="form-group">
               <label for="label_name">Assign To </label>
               {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
               <div class="form-group">
                  <select class="select-option-field-7 users selectValue form-control" id="vendors_id" name="users" data-type="users" data-t="{{ csrf_token() }}" required>
                     
                  </select>
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-users"></p>
               </div>
            </div>
            <div class="form-group vendor_sla_add d-none" >
               <label for="label_name">Vendor Sla </label>
               {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
               <div class="form-group">
                  <select class="select-option-field-7 vendor_sla selectValue form-control" id="vendor_sla" name="vendor_sla" data-type="vendor_sla" data-t="{{ csrf_token() }}" >
                     
                  </select>
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-vendor_sla"></p>
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
               <select class="select-option-field-7 user selectValue form-control" name="user" data-type="user" data-t="{{ csrf_token() }}">
               <option value="">Select user</option>
               @foreach ($users as $key => $user) {
                  @foreach ($action_master as $key => $am) {
                  
                     @if ( in_array($am->id,json_decode($user->permission_id)) && $am->action_title == 'JAF Link') {
                        <option value="{{$user->id}}" >{{$user->name}}</option>
                     @endif
                  @endforeach
               @endforeach
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
            <input type="hidden" name="user" id="use">
            <input type="hidden" name="business" id="business">
            <input type="hidden" name="candidate" id="candidates" >
            <input type="hidden" name="service" id="service">
            <input type="hidden" name="tasks_id" id="tasks_id">
            <input type="hidden" name="job_sla_item" id="job_sla_item">
            <input type="hidden" name="no_of_verification" id="no_of_verification">
            <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
            {{-- <div class="form-group">
            <label for="tat">TAT <span class="text-danger">*</span></label>
            <input type="text" name="tat" class="form-control" id="tat" placeholder="Enter tat" value="{{ old('tat') }}">
            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-tat"></p> 
            </div> --}}
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

<!-- Script -->


<script type="text/javascript">
 $('.tool').tooltip();
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

      $("#candidate").select2();
      $("#customer").select2();
      $("#sla").select2();
      $("#user").select2();
      $(".check").select2();
      $(".rows").select2();
      $("#ref").select2();
      $('.filter0search').click(function(){
         $('.search-drop-field').toggle();
      });
     
      $('.filter_close').click(function(){
                  $('.search-drop-field').toggle();
         });

      $(document).on('click','.assign_report',function(){
         // alert('correct');
         var report_current = $(this);

         // var user_id = $(this).attr('data-user');
         var report_business_id = $(this).attr('data-business');
         var report_candidate_id = $(this).attr('data-candidate');
         // var service_id = $(this).attr('data-service');
         var report_task_id = $(this).attr('data-task');
         var report_job_sla_item_id = $(this).attr('data-jsi');
         // var number = document.getElementById('no_of_user').value;
         // var user = $(this).attr('data-user_id');
         $('#report_assign_for_jaf').text('Assign for- '+"");
         // $('#users').val(user_id);
         $('#report_businesss').val(report_business_id);
         $('#report_candidates_id').val(report_candidate_id);
         // $('#service_id').val(service_id);        
         $('#report_tasks').val(report_task_id);
         $('#report_job_sla_items').val(report_job_sla_item_id);
         var report_username = $(this).attr('data-username');
         // alert(candidate_id);
         $('#report_assign_for_jaf').text('Assign for- '+report_username);

         $('#report_assign_modal').modal({
            backdrop: 'static',
            keyboard: false
         });
         
      });
      $(document).on('click', '.resetBtn' ,function(){

         $("input[type=text], textarea").val("");
         //   $('.customer_list').val('');
         //    $('.candidate').val('');
         // $('.user_list').empty();
         $('.user_typ').val('');
         $('#candidate').val(null).trigger('change');
         $('#customer').val(null).trigger('change');
         $('#user').val(null).trigger('change');
         $('#ref').val(null).trigger('change');
         $('#service').val('');
         $('#task_type').val('');
         $('#assign_status').val('');
         $('#complete_status').val('');
         var uriNum = location.hash;
         pageNumber = uriNum.replace("#","");
         // alert(pageNumber);
         getData(pageNumber);
      });
      $(document).on('click','.report_submit',function(){
         var reportFormData = new FormData($("#report_assign_form")[0]);

         $('#report_assign_back').prop('disabled',true);
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
         $('#report_submit').html("");
         $('#report_submit').html(loadingText);
         $.ajax(
               {
                  type: 'post',
                  url:"{{ url('/task/user/report/assign') }}",
                  data:reportFormData, 
                  processData: false,
                  contentType: false,
                  success: function (data) {
                     console.log(data.success);
                     $('.error-container').html('');
                     if ( data.success == false  ) {
                         
                        window.setTimeout(function(){
                              _this.removeClass('disabled-link');
                              $('#report_submit').html("Submit");
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
                              $('#report_submit').html("Submit");
                              // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                           },1000);
                        if (data.custom =='yes') {
                           toastr.success("Task has been assigned successfully");
                        }
                        else{
                           toastr.success("Task has not been assigned to any user,Please check the user permissions!");
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
         //Bulk assignment pop up
   $(document).on('click','.bulk_assign',function(){

      var current = $(this);

      var task_arr = [];
      var user_id ="";
      var i = 0;

      $('.checks:checked').each(function () {
      
         task_arr[i++] = $(this).val();
         // alert(task_arr);
      });
      // var task_time = $(this).attr('data-time');
      // var created_time = $(this).attr('data-created');
      // var business_id = $(this).attr('data-business');
      // var candidate_id = $(this).attr('data-candidate');
      // var service = $(this).attr('data-service');
      // var task_id = $(this).attr('data-task');
      // var job_sla_item_id = $(this).attr('data-jsi');
      // var username = $(this).attr('data-username');
      // var assign_service =$(this).attr('data-assign_service');
      // var number = document.getElementById('no_of_user').value;
      // var user = $(this).attr('data-user_id');

      // $('#task_time').val(task_time);
      // $('#bulk_businesss_id').val(business_id);
      // $('#bulk_verify_candidate').val(candidate_id);
      // $('#bulk_modal_service_id').val(service);
      $('#bulk_verify_task_id').val(task_arr);
      // $('#bulk_job_sla_items_id').val(job_sla_item_id);
      // $('#bulk_modal_task_time').val(task_time);
      // $('#bulk_modal_created_time').val(created_time);
      // $('#bulk_settype').val('verify_task');

      // alert(candidate_id);

      // var services = 
      $('#bulk_loading').html("Submit");
      $('#bulk_assign_modal').modal({
               backdrop: 'static',
               keyboard: false
            });
         // $('#bulk_assign_modal').toggle(); 
         // $('.back').click(function(){
         //    $('#bulk_vendors_id').val('');
         //    $('#bulk_assign_modal').hide();

         // });   

   });
   $(document).on('change','.bulk_task_type',function(){
        
        var bulk_task_type =$('#bulk_task_type option:selected').val();
        if (bulk_task_type=='task') {
           $('#bulk_users_type').html("<input  type='radio' class='bulk_user_status' id='bulk_user_status' name='bulk_user_status' value='user' ><label for='bulk_user_status'> User</label> <input type='radio' class='bulk_user_status' id='bulk_vendor_status' name='bulk_user_status' value='vendor'><label for='bulk_vendor_status'> Vendor</label>").fadeIn(300);

        } else {
           $('#bulk_users_type').html("<input  type='radio' class='bulk_user_status' id='bulk_user_status' name='bulk_user_status' value='user' ><label for='bulk_user_status'> User</label> ").fadeIn(300);

        }

     });
   $(document).on('change','.bulk_user_status',function(){
      var bulk_task_type =$('#bulk_task_type option:selected').val();
      var assign_business_id = $('#bulk_business_id').val();
      var assign_verify_candidate =  $('#bulk_verify_candidate').val();
      var assign_verify_task_id =  $('#bulk_verify_task_id').val();
      var assign_job_sla_items_id =  $('#bulk_job_sla_items_id').val();
      var assign_settype =  $('#bulk_settype').val();
      var assign_service = $('#bulk_modal_service_id').val();
      var assign_task_time = $('#bulk_modal_task_time').val();
      var assign_created_time = $('#bulk_modal_created_time').val();
      var bulk_user_status = $("input[name=bulk_user_status]:checked").val();
      var vendor_id = '';

      // alert(user_status);
      if (bulk_user_status == 'vendor') {
            
            $('.bulk_vendor_sla_add').removeClass("d-none");
      }
      else{
            $('.bulk_vendor_sla_add').addClass("d-none");
      }

         $.ajax({
            type: 'GET', 
            url:"{{ url('/task/bulk_assign_modal') }}",
            data: {'service_id':assign_service,'task_time':assign_task_time,'created_time':assign_created_time,'user_type':bulk_user_status,'bulk_task_type':bulk_task_type},
               
            success: function (data) {
                  // console.log(data.success);
               $('.error-container').html('');
               if (data.fail && data.error == '') {
                     //    console.log(data.success);
                        $('.error').html(data.message);
               }
            
               
               if (data.fail == false ) {
                     
                     $(".bulk_users").html(data.data);
                  
               }
            } 
         
   });
   //Sla List
   $('.bulk_users').on('change',function(){
            var assign_service =$('#modal_service_id').val();
            var user_status = $("input[name=bulk_user_status]:checked").val();
         
            // e.preventDefault();
            // $('.users').empty();
            // $('.users').append("<option value=''>-All-</option>");
            var vendor_id =$('#bulk_vendor_id option:selected').val();
            // alert (vendor_id);
            if (user_status == 'vendor') {
               // $('.vendor_sla_add').removeClass("d-none");$(this).attr('');
               var vendor_sla_id =  $('option:selected', this).attr('data-bulk'); 
               //  alert(vendor_sla_id);
                  $.ajax({
                     type: 'GET',
                     url:"{{ url('/task/bulk_vendor_sla') }}",
                     data: {'service_id':assign_service,'user_type':user_status,'vendor_id':vendor_id,'vendor_sla_id':vendor_sla_id},
                        
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
                              
                              $("#bulk_vendor_sla").html(data.data);
                           
                        }
                     } 
         
                  });
            }
            
            // else{
            //    $('.vendor_sla_add').addClass("d-none");
            // }

         });
            
         // $('.submit').on('click', function() {
         //    $('#verify_assign_back').prop('disabled',true);
         //       var $this = $(this);
         //       var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
         //       if ($(this).html() !== loadingText) {
         //          $this.data('original-text', $(this).html());
         //          $this.html(loadingText);
         //          // $this.prop('disabled',true);
         //       }
         //       setTimeout(function() {
         //          $this.html($this.data('original-text'));
         //          $this.prop('disabled',false);
         //       }, 5000);
         // });

   });
      //Bulk Assign
      // $(document).on('click','.bulk_submit',function(){
      //       $("#bulk_assign_form").submit();
      // });

      //Assign Bulk Task
   $(document).on('click', '.bulk_submit', function (event) { 
         
      var task_arr = [];
      var user_id ="";
      var i = 0;
      var formData = new FormData($("#bulk_assign_form")[0]);
         // var vendor_id='';
         // var vendor_sla_id='';
         $('#bulk_assign_back').prop('disabled',true);
         // var $this = $(this);
         var task_bulk =$('#bulk_verify_task_id').val();
         var bulk_task =task_bulk.split(',');
         
         // var bulk_user = $(".bulk_users option:selected").val();
         // alert(bulk_user);
         var user_status = $("input[name=bulk_user_status]:checked").val();
         if (user_status=='vendor') {
         
            var bulk_user = $(".bulk_users option:selected").val();
            var vendor_sla_id = $('#bulk_vendor_sla option:selected').val(); 
            $('input[type="file"]').change(function(e){
               var fileName = e.target.files[0].name;
               alert('The file "' + fileName +'" has been selected.');
            });
         // var filename = $('input[type=file]').val().replace(/.*(\/|\\)/, '');
         // console.log(filename);
            // alert(filename);
         }
         else{
            var bulk_user = $(".bulk_users option:selected").val();
         }
         // var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
        
         // setTimeout(function() {
         //    $this.html($this.data('original-text'));
         //    $this.prop('disabled',false);
         // }, 5000);
      $('#bulk_loading').html("");
      if (bulk_user=='') {
         alert('Please select a user to Assign Task! ');
         
      }else{
         user_id = bulk_user;

         if((bulk_task.length)>0){
            // _this.addClass('disabled-link');
            
            $('#bulk_loading').html(loadingText);
            // alert(user_id);
         
            //  var check       =    $(".check option:selected").val();
            var from_date   =    $(".from_date").val(); 
            var to_date     =    $(".to_date").val();    
            //  var candidate_id=    candidate_arr;   {"_token": "{{ csrf_token() }}",'user_id':user_id,'vendor_sla_id':vendor_sla_id,'user_type':user_status,'bulk_task':bulk_task},                        

            $.ajax(
            {
               type: 'post',
               url:"{{ url('/task/bulk-assign') }}",
               data:formData, 
               processData: false,
               contentType: false,
               success: function (data) {
                  console.log(data.success);
                  $('.error-container').html('');
                  if ( data.fail == true  ) {
                     
                     window.setTimeout(function(){
                           _this.removeClass('disabled-link');
                           $('#bulk_loading').html("Submit");
                           // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                     },1000);
                        //  console.log(data.success);
                           $('.error').html(data.message);
                           toastr.success("Tasks has been Already Assigned ");
                        // redirect to google after 5 seconds
                        window.setTimeout(function() {
                              window.location = "{{ url('/')}}"+"/task/";
                        }, 2000);
                  }
                  
                  
                  if (data.fail == false ) {

                        window.setTimeout(function(){
                           _this.removeClass('disabled-link');
                           $('#bulk_loading').html("Submit");
                           // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                        },1000);
                     if (data.status =='ok') {
                        toastr.success("Task has been assigned successfully");
                     }
                     else{
                        toastr.success("Task has not been assigned to any user,Please check the user permissions!");
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
            event.stopImmediatePropagation();
            
               //
         
         }else{
               alert('Please select a check to Assign! ');
         }
      }
   });
      
      $('.customer_list').on('select2:select', function (e){
        var data = e.params.data.id;
        //loader
        $("#overlay").fadeIn(300);　
        getData(0);
        setData();
        e.preventDefault();
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
      $(document).on('change','.from_date, .to_date, .candidate_list,.sla_list,.user_list,.ref_list,#rows,#service,#task_type,#ref', function (e){    
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
        var task_arr = [];
        var i = 0;
        
        $('.checks:checked').each(function () {
            task_arr[i++] = $(this).val();
        });

       
        
        if((task_arr.length)>0){

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

     //Assign Bulk Task
     $(document).on('click','#bulkAssign',function(event){
     
    
         var _this=$(this);
         var task_arr = [];
         var user_id ="";
         var i = 0;
         
        $('.checks:checked').each(function () {
            task_arr[i++] = $(this).val();
        });
        user_id = $(".user option:selected").val();
       
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';
        $('p.load_container').html("");
        if (user_id=='') {
         alert('Please select a user to Assign Task! ');
          
        }else{
            user_id = user_id;

        
            if((task_arr.length)>0){
                        _this.addClass('disabled-link');
                        $('#bulk').html(loadingText);
                  // alert(user_id);
                  //
                                    
                     //  var check       =    $(".check option:selected").val();
                     var from_date   =    $(".from_date").val(); 
                     var to_date     =    $(".to_date").val();    
                     //  var candidate_id=    candidate_arr;                           

                     $.ajax(
                     {
                        type: 'GET',
                        url:"{{ url('/task/bulk-assign') }}",
                        data: {'task_id':task_arr,'from_date':from_date,'to_date':to_date,'user_id':user_id},

                        success: function (data) {
                           // console.log(data.success);
                           $('.error-container').html('');
                           if ( data.fail == true  ) {
                                 
                             
                              window.setTimeout(function(){
                                    _this.removeClass('disabled-link');
                                    $('#bulk').html("");
                                    // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                                 },1000);
                              //    console.log(data.success);
                                    $('.error').html(data.message);
                                    toastr.success("Tasks has been Already Assigned ");
                                 // redirect to google after 5 seconds
                                 window.setTimeout(function() {
                                       window.location = "{{ url('/')}}"+"/task/";
                                 }, 2000);
                           }
                           
                           
                           if (data.fail == false ) {

                                 window.setTimeout(function(){
                                    _this.removeClass('disabled-link');
                                    $('#bulk').html("");
                                    // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                                 },1000);
                              if (data.status =='ok') {
                                 toastr.success("Task has been assigned successfully");
                              }
                              else{
                                 toastr.success("Task has not been assigned to any user,Please check the user permissions!");
                              }
                              
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
                     event.stopImmediatePropagation();
                     
                  //
            
            }else{
                  alert('Please select a check to Assign!');
            }
      }
   });
   //end of bluk assigning task.
      // Assign Verification Task to one user to another for JAF filling
      //  $('.assign').click(function(){
       // Assign Verification Task to one user to another for JAF filling
      //  $('.assign').click(function(){
      $(document).on('click','.assign',function(){

         var current = $(this);
         $('#assign_for').text('Assign for- '+""+'-'+'');
         var task_time = $(this).attr('data-time');
         var created_time = $(this).attr('data-created');
         var business_id = $(this).attr('data-business');
         var candidate_id = $(this).attr('data-candidate');
         var service = $(this).attr('data-service');
         var task_id = $(this).attr('data-task');
         var job_sla_item_id = $(this).attr('data-jsi');
         var username = $(this).attr('data-username');
         var assign_service =$(this).attr('data-assign_service');
         // var number = document.getElementById('no_of_user').value;
         // var user = $(this).attr('data-user_id');

         // $('#task_time').val(task_time);
         $('#businesss_id').val(business_id);
         $('#verify_candidate').val(candidate_id);
         $('#modal_service_id').val(service);        
         $('#verify_task_id').val(task_id);
         $('#job_sla_items_id').val(job_sla_item_id);
         $('#modal_task_time').val(task_time);
         $('#modal_created_time').val(created_time);
         $('#settype').val('verify_task');
         
         // alert(candidate_id);
      $('#assign_for').text('Assign for- '+username+'-'+assign_service);
         // var services = 


            $('#verify_assign_modal').toggle();
            $('.back').click(function(){
               $('#vendors_id').val('');
               $('#verify_assign_modal').hide();

            });   

      });
      $(document).on('change','.user_status',function(){
         
       
         var assign_business_id = $('#businesss_id').val();
         var assign_verify_candidate =  $('#verify_candidate').val();
         var assign_verify_task_id =  $('#verify_task_id').val();
         var assign_job_sla_items_id =  $('#job_sla_items_id').val();
         var assign_settype =  $('#settype').val();
         var assign_service = $('#modal_service_id').val();
         var assign_task_time = $('#modal_task_time').val();
         var assign_created_time = $('#modal_created_time').val();
         var user_status = $("input[name=user_status]:checked").val();

         // alert(user_status);
         if (user_status == 'vendor') {
                  $('.vendor_sla_add').removeClass("d-none");
         }
         else{
            $('.vendor_sla_add').addClass("d-none");
         }

            $.ajax({
               type: 'GET', 
               url:"{{ url('/task/assign_modal') }}",
               data: {'service_id':assign_service,'task_time':assign_task_time,'created_time':assign_created_time,'user_type':user_status},
                   
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
            //Sla List
            $('.users').on('change',function(){
               var assign_service =$('#modal_service_id').val();
               var user_status = $("input[name=user_status]:checked").val();

               // e.preventDefault();
               // $('.users').empty();
               // $('.users').append("<option value=''>-All-</option>");
               var vendor_id =$('#vendors_id option:selected').val();
               // alert (vendor_id);
               if (user_status == 'vendor') {
                  // $('.vendor_sla_add').removeClass("d-none");
                     $.ajax({
                        type: 'GET',
                        url:"{{ url('/task/vendor_sla') }}",
                        data: {'service_id':assign_service,'user_type':user_status,'vendor_id':vendor_id},
                           
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
                                 
                                 $("#vendor_sla").html(data.data);
                              
                           }
                        } 
            
                     });
               }
               
               // else{
               //    $('.vendor_sla_add').addClass("d-none");
               // }

            });
                 
            $('.submit').on('click', function() {
               $('#verify_assign_back').prop('disabled',true);
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
                           toastr.success("Task has been assigned successfully");
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
                     error: function (response) {
                        console.log(response);
                     }
               });
               event.stopImmediatePropagation();
               return false;
            });
      });

      //Assign user for task
      $(document).on('click','.assign_user',function(){


         var current = $(this);
         $('#assign_for_jaf').text('Assign for- '+"");
         // var user_id = $(this).attr('data-user');
         var business_id = $(this).attr('data-business');
         var candidate_id = $(this).attr('data-candidate');
         // var service_id = $(this).attr('data-service');
         var task_id = $(this).attr('data-task');
         var job_sla_item_id = $(this).attr('data-jsi');
          var username = $(this).attr('data-username');
         // var number = document.getElementById('no_of_user').value;
         // var user = $(this).attr('data-user_id');

         // $('#users').val(user_id);
         $('#businesss').val(business_id);
         $('#candidates_id').val(candidate_id);
         // $('#service_id').val(service_id);        
         $('#tasks').val(task_id);
         $('#job_sla_items').val(job_sla_item_id);
         // alert(candidate_id);
         $('#assign_for_jaf').text('Assign for- '+username);


         $('#assign_modal').toggle();

        
 

         $('.back').click(function(){
            $('#filling_user').val('');
            $('#assign_modal').hide();
         });         
         $('.submit').on('click', function() {
            $('#assign_back').prop('disabled',true);
           
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
                        toastr.success("Task has been assigned successfully");
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
                  error: function (response) {
                     console.log(response);
                  }
            });
            event.stopImmediatePropagation();
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
            $('#task').toggle();
      });

      $('.close').click(function(){
         $('#task').hide();
      });
      $('.back').click(function(){
         $('#task').hide();
      });

      // Reassign Task to one user to another for JAF  Verification
      $(document).on('click','.verify_reaasign',function(){
         
               var user_id = $(this).attr('data-user_id');
               var business_id = $(this).attr('data-business_id');
               var candidate_id = $(this).attr('data-candidate_id');
               var service_id = $(this).attr('data-service_id');
               var task_id = $(this).attr('data-task_id');
               var job_sla_item_id = $(this).attr('data-jsi_id');
               var no_of_verification = $(this).attr('data-no_of_verification');
               // alert(candidate_id);
               $('#use').val(user_id);
               $('#business').val(business_id);
               $('#candidates').val(candidate_id);
               $('#service').val(service_id);        
               $('#tasks_id').val(task_id);
               $('#job_sla_item').val(job_sla_item_id);
               $('#no_of_verification').val(no_of_verification);
               // alert(business_id);
               $('#verify_reassign_task').toggle();

               $.ajax({
               type: 'GET',
               url:"{{ url('/task/assign_modal') }}",
               data: {'service_id':service_id},
                   
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

               $('.submit').on('click', function() {
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
                              toastr.success("Task Reassignment successfully");
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
                  });
                  event.stopImmediatePropagation();
                  return false;
               }); 

      });

      $('.close').click(function(){
         $('#verify_reassign_task').hide();
      });
      $('.back').click(function(){
         $('#verify_reassign_task').hide();
      });
   });

   function getData(page){
      //set data
      // var selected=[];
      // var i=0;
      // $('.ref_list option:selected').each(function () {
      //    selected[i++] = $(this).val();
      // });
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
      //   var mob = $('.mob').val();
      var ref = $('.ref').val();
      //   var email = $('.email').val();
      //   var report_status=$('.report_status').val();               
   
         $('#taskResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
   
         $.ajax(
         {
               url: '?page=' + page+'&customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&sla_id='+sla_id+'&user_id='+cus_user_id+'&rows='+rows+'&service_id='+service_id+'&task_type='+task_type+'&ref='+ref, 
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
   
      // var selected=[];
      // var i=0;
      // $('.ref_list option:selected').each(function () {
      //    selected[i++] = $(this).val();
      // });

      var user_id     =    $(".customer_list").val();                
      // var check       =    $(".check option:selected").val();
   
      var from_date   =    $(".from_date").val(); 
      var to_date     =    $(".to_date").val();    
      var candidate_id=    $(".candidate_list option:selected").val();      
      var rows = $("#rows option:selected").val();                      
      // var mob = $('.mob').val();
      var ref = $('.ref').val();
      // var email = $('.email').val();

      var sla_id   =     $(".sla_list option:selected").val();

      var cus_user_id   =     $(".user_list option:selected").val();
      var service_id = $("#service option:selected").val();
      var task_type = $("#task_type option:selected").val();

      // var report_status=$('.report_status').val();
            $.ajax(
            {
               url: "{{ url('/') }}"+'/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&sla_id='+sla_id+'&user_id='+cus_user_id+'&rows='+rows+'&service_id='+service_id+'&task_type='+task_type+'&ref='+ref,
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
  
</script>
@endsection

