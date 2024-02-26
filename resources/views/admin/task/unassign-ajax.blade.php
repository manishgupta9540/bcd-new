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
               <?php $user_type = Auth::user()->user_type;
               // dd($user_type);
               ?>
               {{-- if Login user is customer --}}
               {{-- @if (count($customer_task)>0 || count($customer_verify_task)>0) --}}
               @if ($user_type == 'customer')
                  @if (count($tasks)>0)
                     @foreach ($tasks as $key=>$task) 
                        <tr>
                           <th scope="row"><input class="checks" type="checkbox" name="checks" value="{{ $task->id }}" onchange='checkChange();'></th>
                           <?php $diff= Helper::get_diff_days($task->candidate_id,$task->job_sla_item_id); 
                           $tat = Helper::get_diff($task->candidate_id);

                           $hold = Helper::check_jaf_hold($task->candidate_id);
                           ?>
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
                              @if ($task->reassign_to == '' && $task->tastatus =='1' )
                              
                                 @if ($task->assigned_to == NULL)
                                    @if ($task->description == "JAF Filling")
                                        <button class="assign_user" type="button" style="border-radius:1.2rem;" data-user="{{$task->user}}" data-username="{{ Helper::user_name($task->candidate_id)}}" data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}"><i class="fas fa-user-plus"></i></button>
                                    @elseif ($task->description == "Report generation")
                                       <button class="assign_report" type="button" style="border-radius:1.2rem;" data-user="{{$task->user}}" data-username="{{ Helper::user_name($task->candidate_id)}}"  data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}"><i class="fas fa-user-plus"></i></button>
                                
                                    @else
                                    <button class="assign" type="button" style="border-radius:1.2rem;" data-user="{{$task->user}}" data-username="{{ Helper::user_name($task->candidate_id)}}" data-assign_service="{{ Helper::get_service_name($task->service_id)}}" data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}" data-service="{{$task->service_id}}" ><i class="fas fa-user-plus"></i></button>

                                    @endif
                                 
                                 @else
                                    <span class="badge badge-success">{{Helper::user_name($task->assigned_to)}} </span> <br>
                                    @if ($task->description == "JAF Filling")
                                       @if ($job_item)
                                          <a href="{{ url('candidates/jaf-fill',['case_id'=>  base64_encode($job_item->id),'id' =>  base64_encode($job_item->candidate_id)])}}"  style='font-size:14px;'  data-cand_id="{{ base64_encode($job_item->candidate_id)}}" class="bnt-link jaf">JAF Link</a>

                                       @endif
                                    @endif
                                 @endif
                              
                            
                              @else
                                 @if ($task->tastatus == '2')
                                    <span class="badge badge-info"> {{ Helper::user_name($task->assigned_to)}}</span>
                                 @else
                                    <span class="badge badge-info"> {{ Helper::user_name($task->reassign_to)}}</span><br>
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
                              
                             
                              @if ($task->assigned_to == NULL || $task->tastatus == '2')
                                 <span>--</span>
                              @else
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
                                       //$extension = pathinfo($filename, PATHINFO_EXTENSION);
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
                                 @if ($task->description == "JAF Filling")
                                    <button class="btn btn-info btn-sm reaasign" type="button" data-user="{{$task->user}}" data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}"> <i class='fa fa-tasks'></i> Re-Assign</button>
                                    @if ( $filename)
                                       <a class="btn btn-link" href="{{$url}}" title="download">JAF Details<i class="fas fa-download"></i></a>

                                    @endif

                                 @else
                                       <?php
                                       // $jaf = '1';
                                       $jaf= Helper::get_jaf_form_data($task->candidate_id,$task->service_id,$task->number_of_verifications) ;
                                       // echo '<pre>';
                                       // print_r($jaf);
                                       ?>
                             
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
                                    @if ($REASSIGN_ACCESS)
                                    <button class="btn btn-info btn-sm verify_reaasign" type="button" data-user_id="{{$task->user}}" data-candidate_id="{{$task->candidate_id}}" data-task_id="{{$task->id}}" data-business_id="{{$task->business_id}}" data-jsi_id="{{$task->job_sla_item_id}}" data-service_id="{{$task->service_id}}" data-no_of_verification="{{$task->number_of_verifications}}"> <i class='fa fa-tasks'></i> Re-Assign</button>
                                    @endif 
                                 @endif
                              @endif
                           
                           </td>
                        </tr>
                     @endforeach 
                  @endif
               @else 
                  @if (count($tasks)>0)
                     @foreach ($tasks as $task)
                        <?php $user_id = Auth::user()->id;
                           $kam = Helper::get_kam($user_id,$task->business_id)
                        ?>
                        @if ($kam)
                        <tr>
                           <th scope="row"><input class="checks" type="checkbox" name="checks" value="{{ $task->id }}" onchange='checkChange();'></th>
                           <?php $diff= Helper::get_diff_days($task->candidate_id,$task->job_sla_item_id); 
                           $tat = Helper::get_diff($task->candidate_id);
                           $hold = Helper::check_jaf_hold($task->candidate_id);
                           ?>
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

                              @if ($task->reassign_to == '' && $task->tastatus =='1' )
                              
                                 @if ($task->assigned_to == NULL)
                                    @if ($task->description == "JAF Filling")
                                        <button class="assign_user" type="button" style="border-radius:1.2rem;" data-user="{{$task->user}}" data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}"><i class="fas fa-user-plus"></i></button>
                                    @elseif ($task->description == "Report generation")
                                        <button class="assign_report" type="button" style="border-radius:1.2rem;" data-user="{{$task->user}}" data-username="{{ Helper::candidate_user_name($task->candidate_id)}}"  data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}"><i class="fas fa-user-plus"></i></button>
                                 
                                    @else
                                       <button class="assign" type="button" style="border-radius:1.2rem;" data-user="{{$task->user}}" data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}" data-service="{{$task->service_id}}"><i class="fas fa-user-plus"></i></button>
                                    @endif 
                                 @else
                                    <span class="badge badge-success">{{Helper::user_name($task->assigned_to)}} </span> <br>
                                    @if ($task->description == "JAF Filling") 
                                       @if ($job_item)
                                             <a href="{{ url('candidates/jaf-fill',['case_id'=>  base64_encode($job_item->id),'id' =>  base64_encode($job_item->candidate_id)])}}"  style='font-size:14px;'  data-cand_id="{{ base64_encode($job_item->candidate_id)}}" class="bnt-link jaf">JAF Link</a>

                                       @endif
                                    @endif   
                                 @endif
                              
                            
                              @else
                                 @if ($task->tastatus == '2')
                                     <span class="badge badge-info"> {{ Helper::user_name($task->assigned_to)}}</span>
                                 @else
                                    <span class="badge badge-info"> {{ Helper::user_name($task->reassign_to)}}</span>
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
                              
                              @if ($task->assigned_to == NULL || $task->tastatus == '2')
                                 <span>--</span>
                              @else
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
                                                //$extension = pathinfo($filename, PATHINFO_EXTENSION);
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
                                 @if ($task->description == "JAF Filling")
                                    <button class="btn btn-info btn-sm reaasign" type="button" data-user="{{$task->user}}" data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}"> <i class='fa fa-tasks'></i> Re-Assign</button>
                                    @if ( $filename)
                                       <a class="btn btn-link" href="{{$url}}" title="download">JAF Details<i class="fas fa-download"></i></a>

                                     @endif

                                  @else
                                       <?php
                                       // $jaf = '1';
                                       $jaf= Helper::get_jaf_form_data($task->candidate_id,$task->service_id,$task->number_of_verifications) ;
                                       // echo '<pre>';
                                       // print_r($jaf);
                                       ?>
                             
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
                                 {{-- <th scope="row"><input class="checks" type="checkbox" name="checks" value="{{ $task->id }}" onchange='checkChange();'></th> --}}

                                 <?php $diff= Helper::get_diff_days($task->candidate_id,$task->job_sla_item_id); 
                                 $tat = Helper::get_diff($task->candidate_id);
                                 ?>
                                 <td>
                                    <a href="{{ url('/candidates/jaf-info/'.base64_encode($task->candidate_id)) }}">
                                       {{ Helper::candidate_user_name($task->candidate_id)}}
                                     </a>
                                  </td>
                                 <td>
                                    @if ($task->user==0)
                                    {{ Helper::sla_name($task->job_sla_item_id)}}
                                       
                                    @else
                                    
                                    {{ Helper::get_task_sla($task->user,$task->job_sla_item_id)}}
                                    @endif
                                 </td>
                                 <td>{{$task->description}} <br>
                                    @if ($task->description == "JAF Filling")
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
                                       @endif   
                                       
                                    @endif
                                    @if ($task->reassign_to == Auth::user()->id && $task->tastatus == '1')
                                       <span class="badge badge-success">{{Helper::user_name($task->reassign_to)}} </span><br>
                                       @if ($task->description == "JAF Filling") 
                                          @if ($job_item)
                                                <a href="{{ url('candidates/jaf-fill',['case_id'=>  base64_encode($job_item->id),'id' =>  base64_encode($job_item->candidate_id)])}}"  style='font-size:14px;'  data-cand_id="{{ base64_encode($job_item->candidate_id)}}" class="bnt-link jaf">JAF Link</a>

                                          @endif
                                       @endif   
                                    @endif
                                   
                                    @if ($task->tastatus == '2')
                                       <span class="badge badge-info"> {{ Helper::user_name($task->assigned_to)}}</span>
                                    {{-- @else
                                       <span class="badge badge-info"> {{ Helper::user_name($task->assigned_to)}}</span> --}}
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
                                   
                                    @if ($task->assigned_to == NULL || $task->tastatus == '2')
                                        <span>--</span>
                                    @else
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
                                                //$extension = pathinfo($filename, PATHINFO_EXTENSION);
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
                                       @if ($task->description == "JAF Filling")
                                          <button class="btn btn-info btn-sm reaasign" type="button" data-user="{{$task->user}}" data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}"> <i class='fa fa-tasks'></i> Re-Assign</button> <br>
                                      
                                          @if ( $filename)
                                             <a class="btn btn-link" href="{{$url}}" title="download">JAF Details<i class="fas fa-download"></i></a>

                                          @endif
                                       @else
                                             <?php
                                             // $jaf = '1';
                                             $jaf= Helper::get_jaf_form_data($task->candidate_id,$task->service_id,$task->number_of_verifications) ;
                                             // echo '<pre>';
                                             // print_r($jaf);
                                             ?>
                                 
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
  

 <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
 {{-- <script src="{{asset('js/data-table/bootstrap-table.js')}}"></script>
<script src="{{asset('js/data-table/tableExport.js')}}"></script>
<script src="{{asset('js/data-table/data-table-active.js')}}"></script>
<script src="{{asset('js/data-table/bootstrap-table-editable.js')}}"></script>
<script src="{{asset('js/data-table/bootstrap-editable.js')}}"></script>
<script src="{{asset('js/data-table/bootstrap-table-resizable.js')}}"></script>
<script src="{{asset('js/data-table/colResizable-1.5.source.js')}}"></script>
<script src="{{asset('js/data-table/bootstrap-table-export.js')}}"></script> --}}
