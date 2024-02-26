{{-- @php
$ASSIGN_ACCESS    = false;
$REASSIGN_ACCESS   = false;
$VIEW_ACCESS   = false;
$ASSIGN_ACCESS    = Helper::can_access('Assign','');//passing action title and route group name
$REASSIGN_ACCESS    = Helper::can_access('Reassign','');//passing action title and route group name
$VIEW_ACCESS   = Helper::can_access('View Task','');//passing action title and route group name
@endphp --}}
{{-- <div class="row">
    <div class="col-md-12">
       <div class="table-responsive"> --}}
          {{-- @if ($VIEW_ACCESS) --}}
         <table id="table" class="table table-bordered taskTable" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true"  data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
             data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
             <thead>
                <tr>
                   <th scope="col" style="position:sticky; top:60px"><input  type="checkbox" name='showhide' onchange="checkAll(this)" ></th>
                   <th scope="col" style="position:sticky; top:60px">Candidate Name</th>
                   <th scope="col" style="position:sticky; top:60px">SLA</th>
                   <th scope="col" style="position:sticky; top:60px">Description </th>
                   <th scope="col" style="position:sticky; top:60px">Assigned To</th>
                   <th scope="col" style="position:sticky; top:60px">Assigned By</th>
                   <th scope="col" style="position:sticky; top:60px">Assigned Date</th>
                   <th scope="col" style="position:sticky; top:60px">Due Date</th>
                   <th scope="col" style="position:sticky; top:60px">Status</th>
                   <th scope="col"  style="position:sticky; top:60px">Action</th>
                </tr>
             </thead>
             <tbody class="taskList">
                 @php
                     $user_type= Auth::user()->user_type;
                 @endphp
                 @if($user_type=="vendor")
                    @if (count($tasks)>0)
                        @foreach ($tasks as $key=>$task) 
                            <tr>
                                <th scope="row"><input class="checks" type="checkbox" @if ($task->status != '2')  name="checks"  @endif value="{{ $task->id }}" onchange='checkChange();' @if ($task->status == '2')   disabled @endif></th>
                                <td>
                                    {{ Helper::candidate_user_name($task->candidate_id)}} <br>
                                    <small class="text-muted">Ref. No. <b>{{ Helper::candidate_reference_id($task->candidate_id)}}</b></small><br>
                                    (<small><strong> {{Helper::company_name($task->parent_id)}}</strong> </small>)
                                </td>
                                <td>
                                    {{ Helper::get_vendor_sla_name($task->vendor_sla_id)}} 
                                </td>
                                <td>
                                    <strong>  {{ Helper::get_service_name($task->service_id)}} Verification</strong>
                                </td>
                                <td>
                                    @if ($task->status == '2')
                                        <span class="badge badge-secondary"> {{ Helper::user_name($task->completed_by)}}</span>
                                    @else
                                    @php
                                        $vendor_task_assignment = Helper::get_vendor_task_assignment($task->id); 
                                        $assigned_task     = Helper::get_assigned_task($task->id);
                                        $reassigned_task     = Helper::get_reassigned_task($task->id);
                                        // print_r($vendor_task_assignment);
                                    @endphp
                                    @if ($vendor_task_assignment>0)
                                        <button class="assign_user" type="button" style="border-radius:1.2rem;"  data-task_id="{{$task->id}}" ><i class="fas fa-user-plus"></i></button>

                                    @endif
                                    @if($assigned_task)
                                        <span class="badge badge-success"> {{ Helper::user_name($assigned_task->assigned_to)}}</span>
                                    
                                    @elseif($reassigned_task)
                                        <span class="badge badge-primary"> {{ Helper::user_name($reassigned_task->reassigned_to)}}</span>
                                    @else
                                        @if($task->reassigned_to!=NULL)
                                            <span class="badge badge-primary"> {{ Helper::user_name($task->reassigned_to)}}</span>

                                        @else
                                            <span class="badge badge-success"> {{ Helper::user_name($task->assigned_to)}}</span>

                                        @endif
                                    @endif
                                    @endif
                                </td>
                                <td>
                                    @if($task->reassigned_by!=NULL)
                                        {{Helper::user_name($task->reassigned_by)}}
                                    @elseif($task->assigned_by!=NULL)
                                        {{Helper::user_name($task->assigned_by)}}
                                    @else
                                        <span>--</span>
                                    @endif
                                </td>
                                <td>
                                    @if($task->reassigned_by!=NULL)
                                        {{ date('d M Y', strtotime($task->reassigned_at)) }}
                                    @elseif($task->assigned_by!=NULL)
                                        {{ date('d M Y', strtotime($task->assigned_at)) }}
                                    @else
                                        <span>--</span>
                                    @endif
                                
                                </td>
                                <td>
                                    @php
                                        $diff_days= Helper::get_vendor_overdue_days($task->business_id,$task->vendor_sla_id,$task->service_id,$task->candidate_id);
                                    @endphp
                                    @if($diff_days)
                                        
                                    
                                        @if ($diff_days>=0)
                                            <span class="badge badge-success">{{$diff_days}} Days</span>
                                            <span ><strong>Remaining</strong> </span>

                                        @else
                                            <span class="badge badge-danger">{{abs($diff_days)}} Days</span>
                                            <span ><strong>Over Due</strong> </span>
                                        @endif
                                    @else
                                    <span>--</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($task->status == '2')
                                        <span class="badge badge-success"> <strong>Completed</strong> </span>
                                    @else
                                        <span class="badge badge-success"> <strong>Pending</strong> </span>
                                    @endif
                                </td> 
                                <td style="font-size: 12px; min-width:10%">
                                    @php
                                        $vendor_insuff_status = Helper::get_vendor_insuff($task->candidate_id,$task->service_id,$task->no_of_verification);
                                        $vendor_status = Helper::get_vendor_status_insuff($task->candidate_id,$task->service_id,$task->no_of_verification,$task->status);
                                    @endphp
                                   
                                    @if ($vendor_status)
                                       @if ($vendor_status->status == 'cleared')
                                            <a href="javascript:;" class="badge badge-success raise_insuff_show" candidate-id="{{ base64_encode($task->candidate_id) }}" vendor-id="{{base64_encode($task->id)}}" service-id="{{ base64_encode($task->service_id) }}" service-name="{{$task->servicename}}" number-ver="{{base64_encode($task->no_of_verification)}}"> <strong>Insuff Cleared</strong> </a>
                                       @else
                                          @if ($vendor_status->status == 'raise')
                                             <span class="badge badge-warning"> <strong>Insuff raised</strong> </span> 
                                          @else
                                            <a href="javascript:;" class=" btn btn-danger raise_insuff error-control btn-sm" candidate-id="{{ base64_encode($task->candidate_id) }}" vendor-id="{{base64_encode($task->id)}}" service-id="{{ base64_encode($task->service_id) }}" service-name="{{$task->servicename}}" number-ver="{{base64_encode($task->no_of_verification)}}"> Raise Insuff</a>
                                          @endif
                                       @endif
                                    @endif
                                    @if (!$vendor_status)
                                        <a href="javascript:;" class=" btn btn-danger raise_insuff error-control btn-sm" candidate-id="{{ base64_encode($task->candidate_id) }}" vendor-id="{{base64_encode($task->id)}}" service-id="{{ base64_encode($task->service_id) }}" service-name="{{$task->servicename}}" number-ver="{{base64_encode($task->no_of_verification)}}"> Raise Insuff</a>
                                    @endif
                                    
                                   <br>
                                    @php
                                        $verification_data = Helper::get_vendor_verification_status($task->id);
                                        $vendor_task_reassignment = Helper::get_vendor_task_reassignment($task->id); 
                                        $assigned_task     = Helper::get_assigned_task($task->id);
                                    @endphp
                                    <a href="{{ url('vendor/jaf-download',['id'=>base64_encode($task->candidate_id),'service_id'=>base64_encode($task->service_id),'check_number'=>base64_encode($task->no_of_verification)]) }}"  class="btn-lnk" >Download JAF </a> <br>
                                @if ($vendor_task_reassignment>0)
                                    <button class="reassign_user btn btn-sm btn-warning" type="button" style="border-radius:1.2rem;" data-task="{{$task->id}}"  ><i class="fas fa-user-plus"></i> Re-Assign</button>
                                @endif

                                    @if ($verification_data>0)
                                        <button class="btn btn-primary btn-sm preview_button" type="button" style="border-radius:1.2rem;"  data-vendor_tasks_id="{{ $task->id }}" > <i class='fa fa-eye'></i> Preview</button>

                                    @else
                                        <button class="btn btn-primary btn-sm upload_data " type="button"  style="border-radius:1.2rem;" data-vendor_task_id="{{ $task->id }}"  data-name="{{ Helper::user_name($task->candidate_id)}}"> <i class='fa fa-upload'></i> Upload data</button>

                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    @endif
                 @else
                    @if (count($vendor_tasks)>0)
                        @foreach ($vendor_tasks as $key=>$task) 
                            <tr>
                                <td>
                                    {{ Helper::user_name($task->candidate_id)}} <br>
                                    (<small><strong> {{Helper::company_name($task->parent_id)}}</strong> </small>)
                                </td>
                                <td>
                                    {{ Helper::get_vendor_sla_name($task->vendor_sla_id)}} 
                                </td>
                                <td>
                                <strong>  {{ Helper::get_service_name($task->service_id)}} Verification</strong>
                                </td>
                                <td>
                                    @if ($task->status == '2')
                                        <span class="badge badge-secondary"> {{ Helper::user_name($task->assigned_to)}}</span>
                                    @else
                                        @php
                                        
                                            $assigned_task     = Helper::get_assigned_task($task->vendor_task_id);
                                            $reassigned_task     = Helper::get_reassigned_task($task->vendor_task_id);
                                            // print_r($vendor_task_assignment);
                                        @endphp
                                   
                                        @if($assigned_task)
                                            <span class="badge badge-success"> {{ Helper::user_name($assigned_task->assigned_to)}}</span>
                                        
                                        @elseif($reassigned_task)
                                            <span class="badge badge-primary"> {{ Helper::user_name($reassigned_task->reassigned_to)}}</span>
                                        @else
                                            @if($task->reassigned_to!=NULL)
                                                <span class="badge badge-primary"> {{ Helper::user_name($task->reassigned_to)}}</span>

                                            @else
                                                <span class="badge badge-success"> {{ Helper::user_name($task->assigned_to)}}</span>

                                            @endif
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($task->reassigned_by!=NULL)
                                        {{Helper::user_name($task->reassigned_by)}}
                                    @elseif($task->assigned_by!=NULL)
                                        {{Helper::user_name($task->assigned_by)}}
                                    @else
                                        <span>--</span>
                                    @endif
                                </td>
                                <td>
                                    @if($task->reassigned_by!=NULL)
                                        {{ date('d M Y', strtotime($task->reassigned_at)) }}
                                    @elseif($task->assigned_by!=NULL)
                                        {{ date('d M Y', strtotime($task->assigned_at)) }}
                                    @else
                                        <span>--</span>
                                    @endif
                                
                                </td>
                                <td>
                                    @php
                                        $diff_days= Helper::get_vendor_overdue_days($task->business_id,$task->vendor_sla_id,$task->service_id,$task->candidate_id);
                                    @endphp
                                    @if($diff_days)
                                        
                                    
                                        @if ($diff_days>=0)
                                            <span class="badge badge-success">{{$diff_days}} Days</span>
                                            <span ><strong>Remaining</strong> </span>

                                        @else
                                            <span class="badge badge-danger">{{abs($diff_days)}} Days</span>
                                            <span ><strong>Over Due</strong> </span>
                                        @endif
                                    @else
                                    <span>--</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($task->status == '2')
                                        <span class="badge badge-success"> <strong>Completed</strong> </span>
                                    @else
                                        <span class="badge badge-success"> <strong>Pending</strong> </span>
                                    @endif
                                </td> 
                                <td style="font-size: 12px; min-width:10%">
                                    @php
                                        $verification_data = Helper::get_vendor_verification_status($task->vendor_task_id);
                                        $vendor_task_reassignment = Helper::get_vendor_task_reassignment($task->vendor_task_id); 
                                        $assigned_task     = Helper::get_assigned_task($task->vendor_task_id);
                                    @endphp
                                    <a href="{{ url('vendor/jaf-download',['id'=>base64_encode($task->candidate_id),'service_id'=>base64_encode($task->service_id),'check_number'=>base64_encode($task->no_of_verification)]) }}"  class="btn-lnk" >Download JAF </a> <br>
                                @if ($vendor_task_reassignment>0)
                                    <button class="reassign_user btn btn-sm btn-warning" type="button" style="border-radius:1.2rem;" data-task="{{$task->vendor_task_id}}"  ><i class="fas fa-user-plus"></i> Re-Assign</button>

                                @endif
                                    @if ($verification_data>0)
                                        <button class="btn btn-primary btn-sm preview_button" type="button" style="border-radius:1.2rem;"  data-vendor_tasks_id="{{ $task->vendor_task_id }}" > <i class='fa fa-eye'></i> Preview</button>

                                    @else
                                        <button class="btn btn-primary btn-sm upload_data " type="button"  style="border-radius:1.2rem;" data-vendor_task_id="{{ $task->vendor_task_id }}"  data-name="{{ Helper::user_name($task->candidate_id)}} > <i class='fa fa-upload'></i> Upload data</button>

                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    @endif
                 @endif
                        
                        
            </tbody>
         </table>
          {{-- @else
          <span><h3 class="text-center">You have no access to View Task lists</h3></span>
           @endif --}}
       {{-- </div>
    </div>
 </div> --}}
@if (count($tasks)>0)
    
 <div class="row">
   <div class="col-sm-12 col-md-5">
       <div class="dataTables_info" role="status" aria-live="polite"></div>
   </div>
   <div class="col-sm-12 col-md-7">
     <div class=" paging_simple_numbers" >            
         {!! $tasks->render() !!}
     </div>
   </div>
</div>
@endif
  

 <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
 {{-- <script src="{{asset('js/data-table/bootstrap-table.js')}}"></script> 
<script src="{{asset('js/data-table/tableExport.js')}}"></script>
<script src="{{asset('js/data-table/data-table-active.js')}}"></script>
<script src="{{asset('js/data-table/bootstrap-table-editable.js')}}"></script>
<script src="{{asset('js/data-table/bootstrap-editable.js')}}"></script>
<script src="{{asset('js/data-table/bootstrap-table-resizable.js')}}"></script>
<script src="{{asset('js/data-table/colResizable-1.5.source.js')}}"></script>
<script src="{{asset('js/data-table/bootstrap-table-export.js')}}"></script> --}}
 <script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
   
    }); 
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
  

</script>