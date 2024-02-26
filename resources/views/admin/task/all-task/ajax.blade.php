@php
use App\Traits\S3ConfigTrait;
$ASSIGN_ACCESS    = false;
$REASSIGN_ACCESS   = false;
$VIEW_ACCESS   = false;
// $ASSIGN_ACCESS    = Helper::can_access('Assign','');//passing action title and route group name
$REASSIGN_ACCESS    = Helper::can_access('Reassign','');//passing action title and route group name
$VIEW_ACCESS   = Helper::can_access('View Task','');//passing action title and route group name
@endphp
{{-- <div class="row">
    <div class="col-md-12">
       <div class="table-responsive"> --}}
          @if ($VIEW_ACCESS)
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
               <?php 
               $user_type = Auth::user()->user_type;
               
               // dd($user_type);
               ?>
               {{-- if Login user is customer --}}
               {{-- @if (count($customer_task)>0 || count($customer_verify_task)>0) --}}
               @if ($user_type == 'customer')
                  @if (count($tasks)>0)
                     @foreach ($tasks as $key=>$task) 
                        <tr>
                           <?php $diff= Helper::get_diff_days($task->candidate_id,$task->job_sla_item_id); 
                           $tat = Helper::get_diff($task->candidate_id);

                           $hold = Helper::check_jaf_hold($task->candidate_id);
                           ?>
                           <th scope="row"><input class="checks" type="checkbox" name="checks" value="{{ $task->id }}" onchange='checkChange();'></th>
                           <td>
                              @if ($task->description == "JAF Filling" || $task->description == "Report generation")
                                 {{ Helper::candidate_user_name($task->candidate_id)}} 
                              @else
                                 @if ($hold)
                                    {{ Helper::candidate_user_name($task->candidate_id)}} 
                                 @else
                                    <a href="{{ url('/candidates/jaf-info/'.base64_encode($task->candidate_id)) }}">
                                       {{ Helper::candidate_user_name($task->candidate_id)}} 
                                     </a>  
                                 @endif
                              
                              @endif
                              <br><small class="text-muted">Ref. No. <b>{{$task->display_id }}</b></small>
                               
                           </td>
                           <td>
                              @if ($task->description == "JAF Filling" || $task->description == "Report generation")
                                 {{ Helper::sla_name($task->job_sla_item_id)}}
                              @else

                                 @if ($task->user==0)
                                 {{ Helper::sla_name($task->job_sla_item_id)}}
                                    
                                 @else

                                 {{ Helper::get_task_sla($task->user,$task->job_sla_item_id)}}
                                 @endif
                               @endif
                               {{-- @php
                                   dd($task->job_sla_item_id);
                              @endphp --}}
                             
                              
                           </td>
                           <td>{{$task->description}} <br>
                              @if ($task->description == "JAF Filling" || $task->description == "Report generation")
                                 <small>of <strong> {{Helper::company_name($task->business_id)}}</strong> </small>
                             @else
                                 <small> <strong>{{ Helper::get_service_name($task->service_id)}} {{$task->number_of_verifications}}</strong> of <strong>{{Helper::company_name($task->business_id)}} </strong> </small>
                              @endif
                           </td>
                           <td>
                              
                              @php
                                 $job_item = Helper::check_jaf_item($task->candidate_id,$task->business_id); 
                                 $vendor_task = Helper::get_vendor_task($task->id,$task->service_id,$task->number_of_verifications);
                              
                              @endphp
                              @if ($task->reassign_to =='' && $task->tastatus =='1' )
                              
                                 @if ($task->assigned_to == NULL)
                                    @if ($task->description == "JAF Filling")
                                       <button class="assign_user" type="button" style="border-radius:1.2rem;" data-user="{{$task->user}}" data-username="{{ Helper::candidate_user_name($task->candidate_id)}}"  data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}"><i class="fas fa-user-plus"></i></button>
                                    @elseif ($task->description == "Report generation")
                                       <button class="assign_report" type="button" style="border-radius:1.2rem;" data-user="{{$task->user}}" data-username="{{ Helper::candidate_user_name($task->candidate_id)}}"  data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}"><i class="fas fa-user-plus"></i></button>
                                    @else
                                       <button class="assign" type="button" style="border-radius:1.2rem;" data-user="{{$task->user}}" data-username="{{ Helper::candidate_user_name($task->candidate_id)}}" data-assign_service="{{ Helper::get_service_name($task->service_id)}}" data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}" data-service="{{$task->service_id}}" data-time="{{ $diff }}" data-created="{{ $task->created_at }}" ><i class="fas fa-user-plus"></i></button>
                                    @endif
                                 
                                 @else
                                    <span class="badge badge-success">{{Helper::user_name($task->user)}} </span> <small>{{ $vendor_task?"(Vendor)":"" }}</small><br>
                                    @if ($task->description == "JAF Filling")
                                       @if ($job_item)
                                          <a href="{{ url('candidates/jaf-fill',['case_id'=>  base64_encode($job_item->id),'id' =>  base64_encode($job_item->candidate_id)])}}"  style='font-size:14px;'  data-cand_id="{{ base64_encode($job_item->candidate_id)}}" class="bnt-link jaf">JAF Link</a>
                                       @endif
                                    @elseif($task->description == "Report generation")
                                       <a style='font-size:14px;' class="btn-lnk send_report_otp cursor-pointer" data-id={{ base64_encode($task->candidate_id) }}>Generate Report</a> 
                                    @endif
                                 @endif
                              @else
                                 @if ($task->tastatus == '2')
                                    <span class="badge badge-info"> {{ Helper::user_name($task->user)}} </span> <small>{{ $vendor_task?"(Vendor)":"" }}</small>
                                 @else
                                    <span class="badge badge-info"> {{ Helper::user_name($task->reassign_to)}} </span><small>{{ $vendor_task?"(Vendor)":"" }}</small><br>
                                    @if ($task->description == "JAF Filling")
                                       @if ($job_item)
                                          <a href="{{ url('candidates/jaf-fill',['case_id'=>  base64_encode($job_item->id),'id' =>  base64_encode($job_item->candidate_id)])}}"  style='font-size:14px;'  data-cand_id="{{ base64_encode($job_item->candidate_id)}}" class="bnt-link jaf">JAF Link</a>
                                       @endif
                                    @endif
                                 @endif
                              @endif 
                           </td>
                           <td>
                              @if($task->reassign_by!=NULL)
                                 {{Helper::user_name($task->reassign_by)}}
                              @elseif($task->assigned_by!=NULL)
                                 {{Helper::user_name($task->assigned_by)}}
                              @else
                                 <span>--</span>
                              @endif
                           </td>
                           <td>
                              @if ($task->start_date)
                                 {{ date('d M Y', strtotime($task->start_date)) }}
                              @endif
                               
                           </td>
                           <td>
                                 <?php $diff= Helper::get_diff_days($task->candidate_id,$task->job_sla_item_id); 
                                    $tat = Helper::get_diff($task->candidate_id);
                                 ?>
                              @if ($task->description == "JAF Filling" || ($task->tastatus == '2'))
                                 <span>--</span>
                              @else
                                 @if ($task->tastatus == '1' && $task->tat!='')
                                    @if ($tat>=0)
                                       <span class="badge badge-success">{{$tat}} Days</span>
                                       <span ><strong>Remaining</strong> </span>
                                    @else
                                       <span class="badge badge-danger">{{abs($tat)}} Days</span>
                                       <span ><strong>Over Due</strong> </span>
                                    @endif
                                 @else
                                    @if ($diff>=0)
                                       <span class="badge badge-success">{{$diff}} Days</span>
                                       <span ><strong>Remaining</strong> </span>
                                    @else
                                       <span class="badge badge-danger">{{abs($diff)}} Days</span>
                                       <span ><strong>Over Due</strong> </span>
                                    @endif
                                 @endif
                              @endif
                           </td>
                           <td>
                              @if ($task->tastatus == '2')
                                  <span class="badge badge-success"> <strong>Completed</strong> </span>
                              @else
                                 <span class="badge badge-success"> <strong>Pending</strong> </span>
                              @endif
                           </td>
                           <td>
                              <?php 

                                    $file_arr = [];

                                    $url = '';

                                    $filename = NULL;

                                    $file_platform = NULL;

                                    $file_arr = Helper::get_jaf_attachFile($task->candidate_id);

                                    if(count($file_arr)>0)
                                    {
                                       $filename = $file_arr['file_name'];

                                       $file_platform = $file_arr['file_platform'];
                                       // $filename = Helper::get_jaf_attachFile($task->candidate_id);
                                       $extension = pathinfo($filename, PATHINFO_EXTENSION);
                                       //   dd($extension);

                                       if(stripos($file_platform,'s3')!==false)
                                       {
                                          $filePath = 'uploads/jaf_details/';

                                          $s3_config = S3ConfigTrait::s3Config();

                                          $disk = \Storage::disk('s3');

                                          $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                             'Bucket'                     => \Config::get('filesystems.disks.s3.bucket'),
                                             'Key'                        => $filePath.$filename,
                                             'ResponseContentDisposition' => 'attachment;'//for download
                                          ]);

                                          $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                          $url = $req->getUri();
                                       }
                                       else {
                                          $url = url('/').'/uploads/jaf_details/'.$filename;
                                       }  
                                    }
                                            
                                 ?>
                             
                              @if ($task->assigned_to == NULL || $task->tastatus == '2')
                                 
                                 @php
                                    $vendor_task = Helper::get_vendor_completed_task($task->id,$task->service_id,$task->number_of_verifications);
                                    $service_name =  Helper::get_service_name($task->service_id);
                                 @endphp
                                 @if ($task->description == "JAF Filling")
                                     <span>--</span>
                                 @else
                                    @if ( $filename)
                                       @if ($extension=='zip')
                                          <a class="btn btn-link" href="{{$url}}" title="download">JAF Details <i class="fas fa-download"></i></a>
                                       @endif
                                       @if ($extension=='pdf' || $extension=='xls' || $extension=='xlsx' || $extension=='csv' || $extension=='docs' || $extension=='docx')
                                          <a class="btn btn-link" href="{{$url}}" title="download"   target="_blank">JAF Details <i class="fas fa-download"></i></a>
                                       @endif
                                       @if ( $extension=='png' || $extension=='jpeg' || $extension=='jpg')
                                          <a class="btn btn-link" href="{{$url}}"  download  target="_blank">JAF Details <i class="fas fa-download"></i></a>
                                       @endif
                                    @endif

                                 @php
                                    $taskAssigns = '';
                                    if($task->reassign_to!=NULL){
                                      $taskAssigns = $task->reassign_to;
                                    }elseif($task->assigned_to!=NULL){
                                       $taskAssigns = $task->assigned_to;
                                    }
                                    $taskAssign = Helper::get_single_data('users','user_type','id',$taskAssigns);
                                 @endphp

                                 {{-- @if ($taskAssign != 'vendor')
                                    @if ($vendor_task)
                                       <button class="btn btn-info btn-sm preview_button" type="button"  data-tasks_id="{{ $task->id }}"  data-service_name="{{ $service_name }}" data-candidate_name="{{ $task->name }}" > <i class='fa fa-eye'></i> Verified Data</button>
                                    @endif
                                    @endif--}}
                                 @endif 
                              @else
                                 
                                 @if ($task->description == "JAF Filling")
                                    {{-- @if ($REASSIGN_ACCESS) --}}
                                       <button class="btn btn-info btn-sm reaasign" type="button" data-user="{{$task->user}}" data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}"> <i class='fa fa-tasks'></i> Re-Assign</button>
                                    {{-- @endif --}}
                                    @if ( $filename)
                                       @if ($extension=='zip')
                                          <a class="btn btn-link" href="{{$url}}" title="download">JAF Details <i class="fas fa-download"></i></a>
                                       @endif
                                       @if ($extension=='pdf' || $extension=='xls' || $extension=='xlsx' || $extension=='csv' || $extension=='docs' || $extension=='docx')
                                          <a class="btn btn-link" href="{{$url}}" title="download"   target="_blank">JAF Details <i class="fas fa-download"></i></a>
                                       @endif
                                       @if ( $extension=='png' || $extension=='jpeg' || $extension=='jpg')
                                          <a class="btn btn-link" href="{{$url}}"  download  target="_blank">JAF Details <i class="fas fa-download"></i></a>
                                       @endif
                                    @endif
                                 @elseif($task->description == "Report generation")  
                                    {{-- @if ($REASSIGN_ACCESS) --}}
                                       <button class="btn btn-info btn-sm report_reaasign" type="button" data-user="{{$task->user}}" data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}"> <i class='fa fa-tasks'></i> Re-Assign</button>
                                    {{-- @endif --}}
                                 @else
                                    <?php
                                       // $jaf = '1';
                                       $jaf= Helper::get_jaf_form_data($task->candidate_id,$task->service_id,$task->number_of_verifications) ;
                                       $vendor_insuff_status = Helper::get_vendor_insuff($task->candidate_id,$task->service_id,$task->number_of_verifications);
                                       $vendor_status = Helper::get_vendor_status_insuff($task->candidate_id,$task->service_id,$task->number_of_verifications,$task->status);
                                    ?>
                                    {{-- @if($vendor_status) --}}
                                    @php
                                       $taskAssigns = '';
                                       if($task->reassign_to!=NULL){
                                         $taskAssigns = $task->reassign_to;
                                       }elseif($task->assigned_to!=NULL){
                                          $taskAssigns = $task->assigned_to;
                                       }
                                       
                                       $taskAssign = Helper::get_single_data('users','user_type','id',$taskAssigns);
                                       
                                    @endphp
                                       @if ($taskAssign != 'vendor')
                                          @if ($jaf)
                                             {{-- Check insufficiency is raised or not --}}
                                             @if ($jaf->verification_status == 'success')
                                             <span class="badge badge-success"> <strong>Insuff Cleared</strong> </span> 
                                             @else
                                                @if ($jaf->is_insufficiency == '0')
                                                   <a href="javascript:;" class="btn btn-danger btn-sm text-wh raise_insuff" jaf-id="{{ base64_encode($jaf->id) }}" candidate-id="{{ base64_encode($task->candidate_id) }}" service-id="{{ base64_encode($task->service_id) }}" > Raise Insuff</a>
                                                @else
                                                   <span class="badge badge-warning"> <strong>Insuff raised</strong> </span> 
                                                @endif
                                             @endif
                                          @endif
                                       @endif
                                    {{-- @endif --}}
                                    
                                    
                                    {{-- @if ($REASSIGN_ACCESS) --}}
                                       <button class="btn btn-info btn-sm verify_reaasign" type="button" data-user_id="{{$task->user}}" data-candidate_id="{{$task->candidate_id}}" data-task_id="{{$task->id}}" data-business_id="{{$task->business_id}}" data-jsi_id="{{$task->job_sla_item_id}}" data-service_id="{{$task->service_id}}" data-no_of_verification="{{$task->number_of_verifications}}"> <i class='fa fa-tasks'></i> Re-Assign</button>
                                    {{-- @endif  --}}

                                    @if ( $filename)
                                       @if ($extension=='zip')
                                       <a class="btn btn-link" href="{{$url}}" title="download">JAF Details <i class="fas fa-download"></i></a>
                                       @endif
                                       @if ($extension=='pdf' || $extension=='xls' || $extension=='xlsx' || $extension=='csv' || $extension=='docs' || $extension=='docx')
                                          <a class="btn btn-link" href="{{$url}}" title="download"   target="_blank">JAF Details <i class="fas fa-download"></i></a>
                                       @endif
                                       @if ( $extension=='png'  || $extension=='jpeg' || $extension=='jpg')
                                          <a class="btn btn-link" href="{{$url}}"  download  target="_blank">JAF Details <i class="fas fa-download"></i></a>
                                       @endif
                                       {{-- <a class="btn btn-link" href="{{url('/').'/uploads/jaf_details/'.$filename}}" title="download">JAF Details<i class="fas fa-download"></i></a> --}}

                                    @endif
                                 @endif
                              @endif
                           
                           </td>
                        </tr>
                     @endforeach 
                  @else
                     <tr>
                        <td colspan="11" class="text-center">No Data Found</td>
                     </tr>
                  @endif
               @else 
                  @if (count($tasks)>0)
                     @foreach ($tasks as $task)
                        <?php $user_id = Auth::user()->id;
                           $kam = Helper::get_kam($user_id,$task->business_id)
                        ?>
                        @if ($kam)
                        <tr>
                           <?php $diff= Helper::get_diff_days($task->candidate_id,$task->job_sla_item_id); 
                           $tat = Helper::get_diff($task->candidate_id);
                           $hold = Helper::check_jaf_hold($task->candidate_id);
                           ?>
                           <th scope="row"><input class="checks" type="checkbox" name="checks" value="{{ $task->id }}" onchange='checkChange();'></th>
                           <td>
                              @if ($task->description == "JAF Filling" || $task->description == "Report generation")
                                 {{ Helper::candidate_user_name($task->candidate_id)}} 
                              @else
                                 @if ($hold)
                                    {{ Helper::candidate_user_name($task->candidate_id)}} 
                                 @else
                                    <a href="{{ url('/candidates/jaf-info/'.base64_encode($task->candidate_id)) }}">
                                       {{ Helper::candidate_user_name($task->candidate_id)}} 
                                    </a>  
                                 @endif
                              @endif
                              <br><small class="text-muted">Ref. No. <b>{{$task->display_id }}</b></small>
                              {{-- <a href="{{ url('/candidates/jaf-info/'.base64_encode($task->candidate_id)) }}">
                                  {{ Helper::user_name($task->candidate_id)}}
                              </a> --}}
                           </td>
                           <td>
                              @if ($task->description == "JAF Filling" || $task->description == "Report generation")
                                 {{ Helper::sla_name($task->job_sla_item_id)}}
                              @else
                                 @if ($task->user==0)
                                    {{ Helper::sla_name($task->job_sla_item_id)}}
                                    
                                 @else
                                    {{ Helper::get_task_sla($task->user,$task->job_sla_item_id)}}
                                 @endif
                              @endif
                             
                           </td>
                           <td>{{$task->description}} <br>
                              @if ($task->description == "JAF Filling" || $task->description == "Report generation")
                                 <small>of <strong> {{Helper::company_name($task->business_id)}}</strong> </small>
                             @else
                                 <small> <strong>{{ Helper::get_service_name($task->service_id)}} {{$task->number_of_verifications}}</strong> of <strong>{{Helper::company_name($task->business_id)}} </strong> </small>
                              @endif  
                           </td>
                           <td>
                              <?php
                                  $job_item = Helper::check_jaf_item($task->candidate_id,$task->business_id);
                                  $vendor_task = Helper::get_vendor_task($task->id,$task->service_id,$task->number_of_verifications);

                              ?>

                              @if ($task->reassign_to == '' && $task->tastatus =='1' )
                                 {{-- <span class="badge badge-success">{{Helper::user_name($task->user)}} </span><br> --}}
                                 @if ($task->assigned_to == NULL)
                                    @if ($task->description == "JAF Filling")
                                       <button class="assign_user" type="button" style="border-radius:1.2rem;" data-user="{{$task->user}}" data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}"><i class="fas fa-user-plus"></i></button>
                                    @elseif ($task->description == "Report generation")
                                       <button class="assign_report" type="button" style="border-radius:1.2rem;" data-user="{{$task->user}}" data-username="{{ Helper::candidate_user_name($task->candidate_id)}}"  data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}"><i class="fas fa-user-plus"></i></button>
                                  
                                    @else
                                       <button class="assign" type="button" style="border-radius:1.2rem;" data-user="{{$task->user}}" data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}" data-service="{{$task->service_id}}"><i class="fas fa-user-plus"></i></button>

                                    @endif
                                 @else
                                    <span class="badge badge-success">{{Helper::user_name($task->user)}} </span> <br>
                                    @if ($task->description == "JAF Filling") 
                                       @if ($job_item)
                                             <a href="{{ url('candidates/jaf-fill',['case_id'=>  base64_encode($job_item->id),'id' =>  base64_encode($job_item->candidate_id)])}}"  style='font-size:14px;'  data-cand_id="{{ base64_encode($job_item->candidate_id)}}" class="bnt-link jaf">JAF Link</a>

                                       @endif
                                    @elseif($task->description == "Report generation")
                                       <a style='font-size:14px;' class="btn-lnk send_report_otp cursor-pointer" data-id={{ base64_encode($task->candidate_id) }}>Generate Report</a> 
                                    
                                    @endif   
                                 @endif
                              @elseif ($task->reassign_to != '' && $task->tastatus =='1')
                                 <span class="badge badge-success">{{Helper::user_name($task->reassign_to)}} </span><br>
                                 @if ($task->description == "JAF Filling") 
                                    @if ($job_item)
                                          <a href="{{ url('candidates/jaf-fill',['case_id'=>  base64_encode($job_item->id),'id' =>  base64_encode($job_item->candidate_id)])}}"  style='font-size:14px;'  data-cand_id="{{ base64_encode($job_item->candidate_id)}}" class="bnt-link jaf">JAF Link</a>

                                    @endif
                                 @elseif($task->description == "Report generation")
                                    <a style='font-size:14px;' class="btn-lnk send_report_otp cursor-pointer" data-id={{ base64_encode($task->candidate_id) }}>Generate Report</a> 
                                 
                                 @endif 
                              @else
                                 @if ($task->tastatus == '2')
                                    <span class="badge badge-info"> {{ Helper::user_name($task->user)}} </span> <small>{{ $vendor_task?"(Vendor)":"" }}</small>
                                 @else
                                    <span class="badge badge-info"> {{ Helper::user_name($task->reassign_to)}} </span><small>{{ $vendor_task?"(Vendor)":"" }}</small><br>
                                 @endif
                              @endif 
                           </td>
                           <td>
                              @if($task->reassign_by!=NULL)
                                 {{Helper::user_name($task->reassign_by)}}
                              @elseif($task->assigned_by!=NULL)
                                 {{Helper::user_name($task->assigned_by)}}
                              @else
                                 <span>--</span>
                              @endif
                           </td>
                           <td>
                              @if ($task->start_date)
                              {{ date('d M Y', strtotime($task->start_date)) }}
                              @endif
                           </td>
                           <td>
                              @if ($task->description == "JAF Filling" || ($task->tastatus == '2'))
                                 <span>--</span>
                              @else
                                 @if ($task->tastatus == '1' && $task->tat!='')
                                    @if ($tat>=0)
                                       <span class="badge badge-success">{{$tat}} Days</span>
                                       <span ><strong>Remaining</strong> </span>

                                    @else
                                       <span class="badge badge-danger">{{abs($tat)}} Days</span>
                                       <span ><strong>Over Due</strong> </span>
                                    @endif
                                 @else
                                    @if ($diff>=0)
                                       <span class="badge badge-success">{{$diff}} Days</span>
                                       <span ><strong>Remaining</strong> </span>

                                    @else
                                       <span class="badge badge-danger">{{abs($diff)}} Days</span>
                                       <span ><strong>Over Due</strong> </span>
                                    @endif
                                 @endif
                              @endif
                           </td>
                           <td>
                              @if ($task->tastatus == '2')
                                 <span class="badge badge-success"> <strong>Completed</strong> </span>
                              @else
                               <span class="badge badge-success"> <strong>Pending</strong> </span>
                              @endif
                           </td>
                           <td>
                              <?php 
                                          
                                 $file_arr = [];

                                    $url = '';

                                    $filename = NULL;

                                    $file_platform = NULL;

                                    $file_arr = Helper::get_jaf_attachFile($task->candidate_id);

                                    if(count($file_arr)>0)
                                    {
                                       $filename = $file_arr['file_name'];

                                       $file_platform = $file_arr['file_platform'];
                                       // $filename = Helper::get_jaf_attachFile($task->candidate_id);
                                       $extension = pathinfo($filename, PATHINFO_EXTENSION);
                                       //   dd($extension);

                                       if(stripos($file_platform,'s3')!==false)
                                       {
                                          $filePath = 'uploads/jaf_details/';

                                          $s3_config = S3ConfigTrait::s3Config();

                                          $disk = \Storage::disk('s3');

                                          $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                             'Bucket'                     => \Config::get('filesystems.disks.s3.bucket'),
                                             'Key'                        => $filePath.$filename,
                                             'ResponseContentDisposition' => 'attachment;'//for download
                                          ]);

                                          $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                          $url = $req->getUri();
                                       }
                                       else {
                                          $url = url('/').'/uploads/jaf_details/'.$filename;
                                       }  
                                    }
                                                
                              ?>
                              @if ($task->assigned_to == NULL || $task->tastatus == '2')
                                 @php
                                 $vendor_task = Helper::get_vendor_completed_task($task->id,$task->service_id,$task->number_of_verifications);
                                 $service_name =  Helper::get_service_name($task->service_id);
                                 @endphp
                                 @if ($task->description == "JAF Filling")
                                    <span>--</span>
                                 @else
                                    @if ( $filename)
                                       @if ($extension=='zip')
                                          <a class="btn btn-link" href="{{$url}}" title="download">JAF Details <i class="fas fa-download"></i></a>
                                       @endif
                                       @if ($extension=='pdf' || $extension=='xls' || $extension=='xlsx' || $extension=='csv' || $extension=='docs' || $extension=='docx')
                                          <a class="btn btn-link" href="{{$url}}" title="download"   target="_blank">JAF Details <i class="fas fa-download"></i></a>
                                       @endif
                                       @if ( $extension=='png' || $extension=='jpeg' || $extension=='jpg')
                                          <a class="btn btn-link" href="{{$url}}"  download  target="_blank">JAF Details <i class="fas fa-download"></i></a>
                                       @endif
                                    @endif
                                    @php
                                       $taskAssigns = '';
                                       if($task->reassign_to!=NULL){
                                       $taskAssigns = $task->reassign_to;
                                       }elseif($task->assigned_to!=NULL){
                                          $taskAssigns = $task->assigned_to;
                                       }
                                       $taskAssign = Helper::get_single_data('users','user_type','id',$taskAssigns);
                                    @endphp

                                    {{-- @if ($taskAssign !=null)
                                       @if ($vendor_task)
                                          <button class="btn btn-info btn-sm preview_button" type="button"  data-tasks_id="{{ $task->id }}"  data-service_name="{{ $service_name }}" data-candidate_name="{{ $task->name }}" > <i class='fa fa-eye'></i> Verified Data</button> 
                                       @endif
                                    @endif --}}
                                    
                                 @endif
                              @else
                                    
                                 @if ($task->description == "JAF Filling")
                                    @if ($REASSIGN_ACCESS)
                                       <button class="btn btn-info btn-sm reaasign" type="button" data-user="{{$task->user}}" data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}"> <i class='fa fa-tasks'></i> Re-Assign</button>
                                    @endif
                                    @if ( $filename)
                                       @if ($extension=='zip')
                                        <a class="btn btn-link" href="{{$url}}" title="download">JAF Details <i class="fas fa-download"></i></a>
                                       @endif
                                       @if ($extension=='pdf' || $extension=='xls' || $extension=='xlsx' || $extension=='csv' || $extension=='docs' || $extension=='docx')
                                          <a class="btn btn-link" href="{{$url}}" title="download"   target="_blank">JAF Details <i class="fas fa-download"></i></a>
                                       @endif
                                       @if ( $extension=='png' || $extension=='jpeg' || $extension=='jpg')
                                          <a class="btn btn-link" href="{{$url}}"  download  target="_blank">JAF Details <i class="fas fa-download"></i></a>
                                       @endif
                                       {{-- <a class="btn btn-link" href="{{url('/').'/uploads/jaf_details/'.$filename}}" title="download">JAF Details<i class="fas fa-download"></i></a> --}}

                                     @endif
                                 @elseif($task->description == "Report generation")  
                                     @if ($REASSIGN_ACCESS)
                                        <button class="btn btn-info btn-sm report_reaasign" type="button" data-user="{{$task->user}}" data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}"> <i class='fa fa-tasks'></i> Re-Assign</button>
                                     @endif
                                  @else
                                       <?php
                                       // $jaf = '1';
                                       $jaf= Helper::get_jaf_form_data($task->candidate_id,$task->service_id,$task->number_of_verifications) ;
                                       // echo '<pre>';
                                       // print_r($jaf);
                                       ?>
                                       @if ( $filename)
                                          @if ($extension=='zip')
                                          <a class="btn btn-link" href="{{$url}}" title="download">JAF Details <i class="fas fa-download"></i></a>
                                          @endif
                                          @if ($extension=='pdf' || $extension=='xls' || $extension=='xlsx' || $extension=='csv' || $extension=='docs' || $extension=='docx')
                                             <a class="btn btn-link" href="{{$url}}" title="download"   target="_blank">JAF Details <i class="fas fa-download"></i></a>
                                          @endif
                                          @if ( $extension=='png' || $extension=='jpeg' || $extension=='jpg')
                                             <a class="btn btn-link" href="{{$url}}"  download  target="_blank">JAF Details <i class="fas fa-download"></i></a>
                                          @endif
                                       {{-- <a class="btn btn-link" href="{{url('/').'/uploads/jaf_details/'.$filename}}" title="download">JAF Details<i class="fas fa-download"></i></a> --}}

                                     @endif
                                    
                                     @php
                                          $taskAssigns = '';
                                          if($task->reassign_to!=NULL){
                                          $taskAssigns = $task->reassign_to;
                                          }elseif($task->assigned_to!=NULL){
                                             $taskAssigns = $task->assigned_to;
                                          }
                                          
                                          $taskAssign = Helper::get_single_data('users','user_type','id',$taskAssigns);
                                  
                                    @endphp

                                    @if ($taskAssign != 'vendor')
                                       @if ($jaf)
                                          {{-- Check insufficiency is raised or not --}}
                                          @if ($jaf->verification_status == 'success')
                                          <span class="badge badge-success"> <strong>Insuff Cleared</strong> </span> 

                                          @else
                                             @if ($jaf->is_insufficiency == '0')
                                                <a href="javascript:;" class="btn btn-danger btn-sm text-wh raise_insuff" jaf-id="{{ base64_encode($jaf->id) }}" candidate-id="{{ base64_encode($task->candidate_id) }}" service-id="{{ base64_encode($task->service_id) }}" > Raise Insuff</a>
                                             @else
                                                <span class="badge badge-warning"> <strong>Insuff raised</strong> </span> 
                                             @endif
                                          @endif
                                       @endif
                                    @endif
                                    @if ($REASSIGN_ACCESS)
                                    <button class="btn btn-info btn-sm verify_reaasign" type="button" data-user_id="{{$task->user}}" data-candidate_id="{{$task->candidate_id}}" data-task_id="{{$task->id}}" data-business_id="{{$task->business_id}}" data-jsi_id="{{$task->job_sla_item_id}}" data-service_id="{{$task->service_id}}" data-no_of_verification="{{$task->number_of_verifications}}"> <i class='fa fa-tasks'></i> Re-Assign</button>
                                    @endif 
                                 @endif
                              @endif
                           
                           </td>
                        </tr>
                        @else
                           @if (($task->reassign_to == '' && $task->assigned_to == Auth::user()->id  && ($task->tastatus =='1' || $task->tastatus =='2')) || ($task->reassign_to == Auth::user()->id && ( $task->tastatus == '1' ||  $task->tastatus == '2' )) )
                              <tr>
                                 <?php $diff= Helper::get_diff_days($task->candidate_id,$task->job_sla_item_id); 
                                 $tat = Helper::get_diff($task->candidate_id);
                                 $hold = Helper::check_jaf_hold($task->candidate_id);
                                 ?>
                                 <th scope="row"><input class="checks" type="checkbox" name="checks" value="{{ $task->id }}" onchange='checkChange();'></th>
                                 <td>
                                    @if ($task->description == "JAF Filling" || $task->description == "Report generation")
                                       {{ Helper::candidate_user_name($task->candidate_id)}} 
                                    @else
                                       {{-- @if ($hold) --}}
                                          {{ Helper::candidate_user_name($task->candidate_id)}} 
                                       {{-- @else
                                          <a href="{{ url('/candidates/jaf-info/'.base64_encode($task->candidate_id)) }}">
                                             {{ Helper::candidate_user_name($task->candidate_id)}} 
                                          </a>  
                                       @endif --}}
                                    @endif
                                    {{-- <a href="{{ url('/candidates/jaf-info/'.base64_encode($task->candidate_id)) }}">
                                       {{ Helper::user_name($task->candidate_id)}}
                                    </a> --}}
                                    <br><small class="text-muted">Ref. No. <b>{{$task->display_id }}</b></small>
                                 </td>
                                 <td>
                                    @if ($task->description == "JAF Filling" || $task->description == "Report generation")
                                       {{ Helper::sla_name($task->job_sla_item_id)}}
                                    @else
                                       @if ($task->user==0)
                                          {{ Helper::sla_name($task->job_sla_item_id)}}
                                       @else
                                          {{ Helper::get_task_sla($task->user,$task->job_sla_item_id)}}
                                       @endif
                                    @endif
                                 </td>
                                 <td>{{$task->description}} <br>
                                    @if ($task->description == "JAF Filling" || $task->description == "Report generation")
                                       <small>of <strong> {{Helper::company_name($task->business_id)}}</strong> </small>
                                    @else
                                       <small> <strong>{{ Helper::get_service_name($task->service_id)}} {{$task->number_of_verifications}}</strong> of <strong>{{Helper::company_name($task->business_id)}} </strong> </small>
                                    @endif
                                 </td>
                                 <td>
                                    <?php $job_item = Helper::check_jaf_item($task->candidate_id,$task->business_id) ?> 
                                    {{--Get Job item table data --}}
                                    @if ( $task->assigned_to == Auth::user()->id && $task->reassign_to == '' && $task->tastatus =='1'  )
                                       <span class="badge badge-success">{{Helper::user_name($task->user)}} </span><br>
                                       @if ($task->description == "JAF Filling") 
                                          @if ($job_item)
                                             <a href="{{ url('candidates/jaf-fill',['case_id'=>  base64_encode($job_item->id),'id' =>  base64_encode($job_item->candidate_id)])}}"  style='font-size:14px;'  data-cand_id="{{ base64_encode($job_item->candidate_id)}}" class="bnt-link jaf">JAF Link</a>
                                          @endif
                                       @elseif($task->description == "Report generation")
                                          <a style='font-size:14px;' class="btn-lnk send_report_otp cursor-pointer" data-id={{ base64_encode($task->candidate_id) }}>Generate Report</a> 
                                       @else
                                          <a style='font-size:14px;' class="btn-lnk task_verify cursor-pointer" data-task_verify_can_id={{ base64_encode($task->candidate_id) }} data-task_verify_service_id={{ base64_encode($task->service_id) }} data-task_verify_nov_id={{ base64_encode($task->number_of_verifications) }}>Task for Verification</a> 
                                       @endif 
                                    @endif
                                    @if ($task->reassign_to == Auth::user()->id && $task->tastatus == '1')
                                    
                                       <span class="badge badge-success">{{Helper::user_name($task->reassign_to)}} </span><br>
                                       @if ($task->description == "JAF Filling") 
                                          @if ($job_item)
                                             <a href="{{ url('candidates/jaf-fill',['case_id'=>  base64_encode($job_item->id),'id' =>  base64_encode($job_item->candidate_id)])}}"  style='font-size:14px;'  data-cand_id="{{ base64_encode($job_item->candidate_id)}}" class="bnt-link jaf">JAF Link</a>
                                          @endif
                                       @elseif($task->description == "Report generation")
                                          <a style='font-size:14px;' class="btn-lnk send_report_otp cursor-pointer" data-id={{ base64_encode($task->candidate_id) }}>Generate Report</a> 
                                       @else
                                          <a style='font-size:14px;' class="btn-lnk task_verify cursor-pointer" data-task_verify_can_id={{ base64_encode($task->candidate_id) }} data-task_verify_service_id={{ base64_encode($task->service_id) }} data-task_verify_nov_id={{ base64_encode($task->number_of_verifications) }}>Task for Verification</a> 
                                       @endif
                                    @endif

                                    <div class="text-content">
                                       @if ($task->tastatus == 1)
                                          @if($task->service_id == '1')  
                                                @php
                                                   $jaf_form_details = Helper::get_jaf_form_data($task->candidate_id,$task->service_id,$task->number_of_verifications);
                                                
                                                   $addr_detail = '';
                                                   if($jaf_form_details->form_data!=NULL)
                                                   {
                                                      $input_item_data_array=[];
                                                      $input_item_data_array = json_decode($jaf_form_details->form_data,true);

                                                      $addr_detail = '';

                                                      if(count($input_item_data_array)>0)
                                                      {
                                                            $addr_detail = '';
                                                            foreach($input_item_data_array as $key => $input)
                                                            {
                                                               $key_val = array_keys($input); $input_val = array_values($input);
                                                               
                                                               if(stripos($key_val[0],'Address')!==false){ 
                                                                  $addr_detail .='Address :- ';
                                                                  $addr_detail .= $input_val[0].', '.'<br/>';
                                                               }
                                                            
                                                               if(stripos($key_val[0],'State')!==false){ 
                                                                  $addr_detail .='State :- ';
                                                                  $addr_detail .= ' '.$input_val[0].', '.'<br/>';
                                                               }

                                                               
                                                               if(stripos($key_val[0],'City')!==false){ 
                                                                  $addr_detail .='City :- ';
                                                                  $addr_detail.= $input_val[0].', '.'<br/>';
                                                               }
                                                            }
                                                      }
                                                   }

                                                @endphp  
                                             
                                             @if($addr_detail!='')
                                                <div class="col-md-2 form-group">
                                                   <i class="fa fa-map-marker tooltips map-loc" style="font-size: 15px; margin-left: 88px; margin-top: -37px; left: 20px !important;"  data-toggle="tooltip" data-id="{{ base64_encode($task->id) }}" data-original-title="{{$addr_detail}}" data-html="true"></i> 
                                                </div>
                                             @endif
                                          @endif

                                          @if ($task->service_id == '11')
                                             @php 
                                                $jaf_form_details = Helper::get_jaf_form_data($task->candidate_id,$task->service_id,$task->number_of_verifications);
                                                //dd($jaf_form_details);
                                                $education_detail = '';
                                                if($jaf_form_details->form_data!=NULL)
                                                {
                                                   $input_item_data_array=[];
                                                   $input_item_data_array = json_decode($jaf_form_details->form_data,true);

                                                   $education_detail = '';

                                                   if(count($input_item_data_array)>0)
                                                   {
                                                         $education_detail = '';
                                                         foreach($input_item_data_array as $key => $input)
                                                         {
                                                            $key_val = array_keys($input); $input_val = array_values($input);
                                                            
                                                            if(stripos($key_val[0],'University Name')!==false){ 
                                                               $education_detail .='University / Board Name :- ';
                                                               $education_detail .= $input_val[0].', '.'<br/>';
                                                            }
                                                         
                                                            if(stripos($key_val[0],'Degree')!==false){ 
                                                               $education_detail .='Degree :- ';
                                                               $education_detail .= $input_val[0].',  '.'<br/>';
                                                            
                                                            }
                                                            
                                                            
                                                            if(stripos($key_val[0],'Registration')!==false){ 
                                                               $education_detail .='Registration Number :- ';
                                                               $education_detail.= $input_val[0].',  '.'<br/>';
                                                            }

                                                            if(stripos($key_val[0],'Year Of Qualification')!==false){ 
                                                               $education_detail .='Year Of Qualification :- ';
                                                               $education_detail.= $input_val[0].',  ';
                                                            }
                                                         }
                                                   }
                                                }
                                             @endphp
                                    
                                             @if($education_detail!='')
                                                <div class="col-md-2 form-group">
                                                   <i class="fa fa-graduation-cap tooltips map-loc" style="font-size: 15px; margin-left: 88px; margin-top: -34px;left: 20px !important;" data-toggle="tooltip" data-id="{{ base64_encode($task->id) }}" data-original-title="{{$education_detail}}" data-html="true"></i> 
                                                </div>
                                             @endif
                                          @endif
                                       @endif
                                    </div>  

                                    <div class="test-content">

                                          @if ($task->tastatus == '2')
                                             <span class="badge badge-info"> {{ Helper::user_name($task->user)}}</span>

                                             @if ($task->service_id == '11')
                                             @php 
                                                $jaf_form_details = Helper::get_jaf_form_data($task->candidate_id,$task->service_id,$task->number_of_verifications);
                                                //dd($jaf_form_details);
                                                $education_detail = '';
                                                if($jaf_form_details->form_data!=NULL)
                                                {
                                                   $input_item_data_array=[];
                                                   $input_item_data_array = json_decode($jaf_form_details->form_data,true);

                                                   $education_detail = '';

                                                   if(count($input_item_data_array)>0)
                                                   {
                                                         $education_detail = '';
                                                         foreach($input_item_data_array as $key => $input)
                                                         {
                                                            $key_val = array_keys($input); $input_val = array_values($input);
                                                            
                                                            if(stripos($key_val[0],'University Name')!==false){ 
                                                               $education_detail .='University / Board Name :- ';
                                                               $education_detail .= $input_val[0].', '.'<br/>';
                                                            }
                                                         
                                                            if(stripos($key_val[0],'Degree')!==false){ 
                                                               $education_detail .='Degree :- ';
                                                               $education_detail .= $input_val[0].',  '.'<br/>';
                                                            
                                                            }
                                                            
                                                            
                                                            if(stripos($key_val[0],'Registration')!==false){ 
                                                               $education_detail .='Registration Number :- ';
                                                               $education_detail.= $input_val[0].',  '.'<br/>';
                                                            }

                                                            if(stripos($key_val[0],'Year Of Qualification')!==false){ 
                                                               $education_detail .='Year Of Qualification :- ';
                                                               $education_detail.= $input_val[0].',  ';
                                                            }
                                                         }
                                                   }
                                                }
                                             @endphp
                                    
                                             @if($education_detail!='')
                                                <div class="col-md-2 form-group">
                                                   <i class="fa fa-graduation-cap tooltips map-loc" style='font-size:15px; margin-left:47px; margin-bottom: -84px; left: 20px !important;' data-toggle="tooltip" data-id="{{ base64_encode($task->id) }}" data-original-title="{{$education_detail}}" data-html="true"></i> 
                                                </div>
                                             @endif
                                          @endif

                                             @if($task->service_id == '1')  
                                                @php
                                                   $jaf_form_details = Helper::get_jaf_form_data($task->candidate_id,$task->service_id,$task->number_of_verifications);
                                                
                                                   $addr_detail = '';
                                                   if($jaf_form_details->form_data!=NULL)
                                                   {
                                                      $input_item_data_array=[];
                                                      $input_item_data_array = json_decode($jaf_form_details->form_data,true);

                                                      $addr_detail = '';

                                                      if(count($input_item_data_array)>0)
                                                      {
                                                            $addr_detail = '';
                                                            foreach($input_item_data_array as $key => $input)
                                                            {
                                                               $key_val = array_keys($input); $input_val = array_values($input);
                                                               
                                                               if(stripos($key_val[0],'Address')!==false){ 
                                                                  $addr_detail .='Address :- ';
                                                                  $addr_detail .= $input_val[0].', '.'<br/>';
                                                               }
                                                            
                                                               if(stripos($key_val[0],'State')!==false){ 
                                                                  $addr_detail .='State :- ';
                                                                  $addr_detail .= ' '.$input_val[0].', '.'<br/>';
                                                               }

                                                               
                                                               if(stripos($key_val[0],'City')!==false){ 
                                                                  $addr_detail .='City :- ';
                                                                  $addr_detail.= $input_val[0].', '.'<br/>';
                                                               }
                                                            }
                                                      }
                                                   }

                                                @endphp  
                                             
                                             @if($addr_detail!='')
                                                <div class="col-md-2 form-group">
                                                   <i class="fa fa-map-marker tooltips map-loc" style='font-size:15px; margin-left:35px; margin-bottom: -84px;left: 37px !important;' data-toggle="tooltip" data-id="{{ base64_encode($task->id) }}" data-original-title="{{$addr_detail}}" data-html="true"></i> 
                                                </div>
                                             @endif
                                          @endif
                                    </div>
                                       {{-- @else
                                          <span class="badge badge-primary"> {{ Helper::user_name($task->assigned_to)}}</span> --}}
                                       @if($task->description == "Task for Verification")
                                          @if($task->user==Auth::user()->id || $task->reassign_to == Auth::user()->id)
                                             <br><a class="btn-lnk task_verify cursor-pointer" data-task_verify_can_id={{ base64_encode($task->candidate_id) }} data-task_verify_service_id={{ base64_encode($task->service_id) }} data-task_verify_nov_id={{ base64_encode($task->number_of_verifications) }}>Task for Verification</a> 
                                          @endif
                                       @endif
                                    @endif
                                 </td>
                                 <td>
                                    @if($task->reassign_by!=NULL)
                                       {{Helper::user_name($task->reassign_by)}}
                                    @elseif($task->assigned_by!=NULL)
                                       {{Helper::user_name($task->assigned_by)}}
                                    @else
                                       <span>--</span>
                                    @endif
                                 </td>
                                 <td>
                                    @if ($task->start_date)
                                       {{ date('d M Y', strtotime($task->start_date)) }}
                                    @endif 
                                 </td>
                                 <td>
                                    @if ($task->description == "JAF Filling" || ($task->tastatus == '2'))
                                        <span>--</span>
                                    @else
                                       @if ($task->tastatus == '1' && $task->tat!='')
                                          @if ($tat>=0)
                                             <span class="badge badge-success">{{$tat}} Days</span>
                                             <span ><strong>Remaining</strong> </span>
         
                                          @else
                                             <span class="badge badge-danger">{{abs($tat)}} Days</span>
                                             <span ><strong>Over Due</strong> </span>
                                          @endif
                                       @else
                                          @if ($diff>=0)
                                             <span class="badge badge-success">{{$diff}} Days</span>
                                             <span ><strong>Remaining</strong> </span>
         
                                          @else
                                             <span class="badge badge-danger">{{abs($diff)}} Days</span>
                                             <span ><strong>Over Due</strong> </span>
                                          @endif
                                       @endif
                                    @endif
                                 </td>
                                 <td>
                                    @if ($task->tastatus == '2')
                                       <span class="badge badge-success"> <strong>Completed</strong> </span>
                                    @else
                                       <span class="badge badge-success"> <strong>Pending</strong> </span>
                                    @endif
                                 </td>
                                 <td>
                                    <?php 
                                      
                                          $file_arr = [];

                                          $url = '';

                                          $filename = NULL;

                                          $file_platform = NULL;

                                          $file_arr = Helper::get_jaf_attachFile($task->candidate_id);

                                          if(count($file_arr)>0)
                                          {
                                             $filename = $file_arr['file_name'];

                                             $file_platform = $file_arr['file_platform'];
                                             // $filename = Helper::get_jaf_attachFile($task->candidate_id);
                                             $extension = pathinfo($filename, PATHINFO_EXTENSION);
                                             //   dd($extension);

                                             if(stripos($file_platform,'s3')!==false)
                                             {
                                                $filePath = 'uploads/jaf_details/';

                                                $s3_config = S3ConfigTrait::s3Config();

                                                $disk = \Storage::disk('s3');

                                                $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                                   'Bucket'                     => \Config::get('filesystems.disks.s3.bucket'),
                                                   'Key'                        => $filePath.$filename,
                                                   'ResponseContentDisposition' => 'attachment;'//for download
                                                ]);

                                                $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                                $url = $req->getUri();
                                             }
                                             else {
                                                $url = url('/').'/uploads/jaf_details/'.$filename;
                                             }  
                                          }
                                    ?>
                                    @if ($task->assigned_to == NULL || $task->tastatus == '2')
                                       @php
                                       $vendor_task = Helper::get_vendor_completed_task($task->id,$task->service_id,$task->number_of_verifications);
                                       $service_name =  Helper::get_service_name($task->service_id);
                                       @endphp
                                       @if ($task->description == "JAF Filling")
                                          <span>--</span>
                                       @else
                                          @if ( $filename)
                                             @if ($extension=='zip')
                                             <a class="btn btn-link" href="{{$url}}" title="download">JAF Details <i class="fas fa-download"></i></a>
                                             @endif
                                             @if ($extension=='pdf' || $extension=='xls' || $extension=='xlsx' || $extension=='csv' || $extension=='docs' || $extension=='docx')
                                                <a class="btn btn-link" href="{{$url}}" title="download"   target="_blank">JAF Details <i class="fas fa-download"></i></a>
                                             @endif
                                             @if ( $extension=='png' || $extension=='jpeg' || $extension=='jpg')
                                                <a class="btn btn-link" href="{{$url}}"  download  target="_blank">JAF Details <i class="fas fa-download"></i></a>
                                             @endif
                                             {{-- <a class="btn btn-link" href="{{url('/').'/uploads/jaf_details/'.$filename}}" title="download">JAF Details<i class="fas fa-download"></i></a> --}}

                                          @endif
                                          @php
                                             $taskAssigns = '';
                                             if($task->reassign_to!=NULL){
                                             $taskAssigns = $task->reassign_to;
                                             }elseif($task->assigned_to!=NULL){
                                                $taskAssigns = $task->assigned_to;
                                             }
                                             $taskAssign = Helper::get_single_data('users','user_type','id',$taskAssigns);
                                          @endphp
                                          {{-- @if ($taskAssign != 'vendor')
                                             @if ($vendor_task)
                                                <button class="btn btn-info btn-sm preview_button" type="button"  data-tasks_id="{{ $task->id }}"  data-service_name="{{ $service_name }}" data-candidate_name="{{ $task->name }}" > <i class='fa fa-eye'></i> Verified Data</button>

                                             @else
                                                <span>--</span>
                                             @endif
                                          @endif --}}
                                       @endif
                                    @else 
                                       @if ($task->description == "JAF Filling")
                                          @if ($REASSIGN_ACCESS)
                                             <button class="btn btn-info btn-sm reaasign" type="button" data-user="{{$task->user}}" data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}"> <i class='fa fa-tasks'></i> Re-Assign</button> <br>
                                          @endif
                                          @if ( $filename)
                                             @if ($extension=='zip')
                                                <a class="btn btn-link" href="{{$url}}" title="download">JAF Details <i class="fas fa-download"></i></a>
                                             @endif
                                             @if ($extension=='pdf' || $extension=='xls' || $extension=='xlsx' || $extension=='csv' || $extension=='docs' || $extension=='docx')
                                                <a class="btn btn-link" href="{{$url}}" title="download"   target="_blank">JAF Details <i class="fas fa-download"></i></a>
                                             @endif
                                             @if ( $extension=='png' || $extension=='jpeg' || $extension=='jpg')
                                                <a class="btn btn-link" href="{{$url}}"  download  target="_blank">JAF Details <i class="fas fa-download"></i></a>
                                             @endif
                                          @endif
                                       @elseif($task->description == "Report generation")  
                                          @if ($REASSIGN_ACCESS)
                                             <button class="btn btn-info btn-sm report_reaasign" type="button" data-user="{{$task->user}}" data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}"> <i class='fa fa-tasks'></i> Re-Assign</button>
                                          @endif
                                       @else
                                             <?php
                                                // $jaf = '1';
                                                $jaf= Helper::get_jaf_form_data($task->candidate_id,$task->service_id,$task->number_of_verifications) ;
                                                // echo '<pre>';
                                                // print_r($jaf);
                                             ?>
                                          @if ( $filename)
                                             @if ($extension=='zip')
                                             <a class="btn btn-link" href="{{$url}}" title="download">JAF Details <i class="fas fa-download"></i></a>
                                             @endif
                                             @if ($extension=='pdf'|| $extension=='xls' || $extension=='xlsx' || $extension=='csv' || $extension=='docs' || $extension=='docx')
                                                <a class="btn btn-link" href="{{$url}}" title="download"   target="_blank">JAF Details <i class="fas fa-download"></i></a>
                                             @endif
                                             @if ( $extension=='png' || $extension=='jpeg' || $extension=='jpg')
                                                <a class="btn btn-link" href="{{$url}}"  download  target="_blank">JAF Details <i class="fas fa-download"></i></a>
                                             @endif

                                          @endif

                                          @php
                                             $taskAssigns = '';
                                             if($task->reassign_to!=NULL){
                                             $taskAssigns = $task->reassign_to;
                                             }elseif($task->assigned_to!=NULL){
                                                $taskAssigns = $task->assigned_to;
                                             }
                                             
                                             $taskAssign = Helper::get_single_data('users','user_type','id',$taskAssigns);
                                          
                                          @endphp

                                          @if ($taskAssign != 'vendor')
                                             @if ($jaf)
                                             {{-- Check insufficiency is raised or not --}}
                                                @if ($jaf->verification_status == 'success')
                                                <span class="badge badge-success"> <strong>Insuff Cleared</strong> </span> 
         
                                                @else
                                                   @if ($jaf->is_insufficiency == '0')
                                                      <a href="javascript:;" class="btn btn-danger btn-sm text-wh raise_insuff" jaf-id="{{ base64_encode($jaf->id) }}" candidate-id="{{ base64_encode($task->candidate_id) }}" service-id="{{ base64_encode($task->service_id) }}" > Raise Insuff</a>
                                                   @else
                                                      <span class="badge badge-warning"> <strong>Insuff raised</strong> </span> 
                                                   @endif
                                                @endif
                                             @endif
                                          @endif
                                          @if ($REASSIGN_ACCESS)
                                             <button class="btn btn-info btn-sm verify_reaasign" type="button" data-user_id="{{$task->user}}" data-candidate_id="{{$task->candidate_id}}" data-task_id="{{$task->id}}" data-business_id="{{$task->business_id}}" data-jsi_id="{{$task->job_sla_item_id}}" data-service_id="{{$task->service_id}}" data-no_of_verification="{{$task->number_of_verifications}}"> <i class='fa fa-tasks'></i> Re-Assign</button>
                                          @endif 
                                       @endif
                                    @endif
                                 </td>
                              </tr> 
                           @endif
                        @endif 
                        {{-- Checking, is kam belongs to COC or not --}}
                        {{-- @if ($kam->business_id == $task->business_id)
                        @endif --}}
                     @endforeach
                  @else
                     <tr>
                        <td colspan="11" class="text-center">No Data Found</td>
                     </tr>
                  @endif
                      
               @endif 
            </tbody>
         </table>
          @else
          <span><h3 class="text-center">You have no access to View Task lists</h3></span>
           @endif
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
   {{-- Insuff Raised modal --}}
   <div class="modal" id="raise_modal">
      <div class="modal-dialog">
         <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
         <h4 class="modal-title" id="ser_name">Raise Insuff</h4>
         <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body --> 
            <form method="post" action="{{url('/candidates/jaf/raiseInsuff')}}" id="raise_insuff_form" enctype="multipart/form-data">
               @csrf
               <input type="hidden" name="can_id" id="can_id">
               <input type="hidden" name="ser_id" id="ser_id">
               <input type="hidden" name="jaf_id" id="jaf_id">
               <div class="modal-body">
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                  <div class="form-group">
                     <label for="label_name"> Comments <span class="text-danger">*</span></label>
                     <textarea id="comments" name="comments" class="form-control comments" placeholder=""></textarea>
                     {{-- <input type="text" id="comments" name="comments" class="form-control comments" placeholder=""/> --}}
                     <p style="margin-bottom: 2px;" class="text-danger" id="error-comments"></p> 
                  </div>
                  <div class="form-group">
                     <label for="label_name"> Attachments:  <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,gif,pdf are accepted "></i></label>
                     <input type="file" name="attachments[]" id="attachments" multiple class="form-control attachments">
                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-attachments"></p>  
                  </div>
                  <div class="form-group pt-2">
                     <label class="check-inline " style="font-size: 14px;">
                        <input type="checkbox" class="is_send_mail" id="is_send_mail" name="is_send_mail"> Send Mail to Candidate
                     </label>
                  </div>
                  <div class="form-group">
                     <label for="label_name"> No of Days: <span class="text-danger">*</span></label>
                     <input type="text" id="no_of_days" name="no_of_days" class="form-control no_of_days" placeholder=""/>
                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-no_of_days"></p> 
                  </div>
                  <div class="form-group">
                     <label for="label_name"> No of Follow Up: <span class="text-danger">*</span></label>
                     <input type="text" id="number_of_follow" name="number_of_follow" class="form-control number_of_follow" placeholder=""/>
                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-number_of_follow"></p> 
                  </div>
               </div>
               <!-- Modal footer -->
               <div class="modal-footer">
               <button type="submit" class="btn btn-info insuff_submit">Submit </button>
               <button type="button" class="btn btn-danger" id="raise_insuff_back" data-dismiss="modal">Close</button>
               </div>
            </form>
         </div>
      </div>
   </div>
   {{-- End of Insuff Raised Model --}}


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
      // end All Check
   $(document).on('click', '.raise_insuff', function (event) {
      var can_id=$(this).attr('candidate-id');
      var ser_id=$(this).attr('service-id');
      var jaf_id=$(this).attr('jaf-id');
      // var ser_name=$(this).attr('service-name');
      $('#can_id').val(can_id);
      // $('#ser_name').text('Verfication-'+ser_name);
      $('#ser_id').val(ser_id);
      $('#jaf_id').val(jaf_id);
      $('#raise_modal').modal({
         backdrop: 'static',
         keyboard: false
      });
  
      
   }); 

   // $('.insuff_submit').on('click', function() {
      //    $('#raise_insuff_back').prop('disabled',true);
         
      //    var $this = $(this);
      //    var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
      //    if ($(this).html() !== loadingText) {
      //       $this.data('original-text', $(this).html());
      //       $this.html(loadingText);
      //       // $this.prop('disabled',true);
      //    }
      //    setTimeout(function() {
      //       $this.html($this.data('original-text'));
      //       $this.prop('disabled',false);
      //    }, 5000);
      // });

      $('#raiseinsuffBtn').click(function(e) {
            e.preventDefault();
            $("#raise_insuff_form").submit();
      });

      $(document).on('submit', 'form#raise_insuff_form', function (event) {
                    
         $("#overlay").fadeIn(300);　
         event.preventDefault();
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var $btn = $(this);
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
         $('.insuff_submit').attr('disabled',true);
         $('#raise_insuff_back').attr('disabled',true);
         if($('.insuff_submit').html()!==loadingText)
         {
            $('.insuff_submit').html(loadingText);
         }

         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                  console.log(data);
                  window.setTimeout(function(){
                     $('.insuff_submit').attr('disabled',false);
                     $('#raise_insuff_back').attr('disabled',false);
                     $('.insuff_submit').html('Submit');
                  },2000);
                  $('.error-container').html('');
                  if (data.fail && data.error_type == 'validation') {
                        //$("#overlay").fadeOut(300);
                        for (control in data.errors) {
                        $('textarea[comments=' + control + ']').addClass('is-invalid');
                        $('#error-' + control).html(data.errors[control]);
                        }
                  } 
               //  if (data.fail && data.error == 'yes') {
                     
               //      $('#error-all').html(data.message);
               //  }
                  if (data.fail == false) {
                     // $('#send_otp').modal('hide');
                     // alert(data.id);
                     toastr.error("Insuff is Raised");
                     // redirect to google after 5 seconds
                     window.setTimeout(function() {
                     location.reload(); 
                     }, 2000);
                     // window.location.href='{{ Config::get('app.admin_url')}}/aadharchecks/show';
                     //  location.reload(); 
                  }
            },
            error: function (xhr, textStatus, errorThrown) {
                  
                  alert("Error: " + errorThrown);

            }
         });
         event.stopImmediatePropagation();
         return false;
         
      });
</script>