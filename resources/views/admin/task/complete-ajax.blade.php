
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
                   <th scope="col" style="position:sticky; top:60px">Action</th>
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
                         
                           ?>
                           <td>
                            {{ Helper::candidate_user_name($task->candidate_id)}} 
                            <br><small class="text-muted">Ref. No. <b>{{$task->display_id }}</b></small>
                               
                           </td>
                           <td>
                              {{ Helper::sla_name($task->job_sla_item_id)}}
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
                              @if ($task->reassign_to == '' && $task->tastatus =='1' )
                              
                                 @if ($task->assigned_to == NULL)
                                    @if ($task->description == "JAF Filling")
                                    <button class="assign_user" type="button" style="border-radius:1.2rem;" data-user="{{$task->user}}" data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}"><i class="fas fa-user-plus"></i></button>
                                  

                                    @else
                                    <button class="assign" type="button" style="border-radius:1.2rem;" data-user="{{$task->user}}" data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}" data-service="{{$task->service_id}}" ><i class="fas fa-user-plus"></i></button>

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
                            <span>--</span>
                           </td>
                           <td>
                            <span class="badge badge-success"> <strong>Completed</strong> </span>
                           </td>
                           <td>
                              
                             
                              @if ($task->assigned_to == NULL || $task->tastatus == '2')
                              {{-- <a href="{{ url('/uploads/verification-file/'. ) }}" class="btn-lnk" >Download Verification Data </a>  --}}
                              {{-- <br> --}}
                                 <span>--</span>
                             
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
                           ?>
                           <td>
                            {{ Helper::candidate_user_name($task->candidate_id)}}
                            <br><small class="text-muted">Ref. No. <b>{{$task->display_id }}</b></small>
                           </td>
                           <td>
                              {{ Helper::sla_name($task->job_sla_item_id)}}
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

                              @if ($task->reassign_to == '' && $task->tastatus =='1' )
                              
                                 @if ($task->assigned_to == NULL)
                                    @if ($task->description == "JAF Filling")
                                        <button class="assign_user" type="button" style="border-radius:1.2rem;" data-user="{{$task->user}}" data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}"><i class="fas fa-user-plus"></i></button>

                                    @else
                                       <button class="assign" type="button" style="border-radius:1.2rem;" data-user="{{$task->user}}" data-candidate="{{$task->candidate_id}}" data-task="{{$task->id}}" data-business="{{$task->business_id}}" data-jsi="{{$task->job_sla_item_id}}"><i class="fas fa-user-plus"></i></button>

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
                            <span>--</span>
                           </td>
                           <td>
                            <span class="badge badge-success"> <strong>Completed</strong> </span>
                           </td>
                           <td>
                              
                              @if ($task->assigned_to == NULL || $task->tastatus == '2')
                                 <span>--</span>
                        
                              @endif
                           
                           </td>
                        </tr>
                        @else
                           @if (($task->reassign_to == '' && $task->assigned_to == Auth::user()->id  && ($task->tastatus =='2')) || ($task->reassign_to == Auth::user()->id && ( $task->tastatus == '2' )) )
                              <tr>
                                <th scope="row"><input class="checks" type="checkbox" name="checks" value="{{ $task->id }}" onchange='checkChange();'></th>

                                 <?php $diff= Helper::get_diff_days($task->candidate_id,$task->job_sla_item_id); 
                                 $tat = Helper::get_diff($task->candidate_id);
                                 ?>
                                 <td>
                                    {{ Helper::candidate_user_name($task->candidate_id)}}
                                    <br><small class="text-muted">Ref. No. <b>{{$task->display_id }}</b></small>
                                  </td>
                                 <td>
                                    {{ Helper::sla_name($task->job_sla_item_id)}}
                                 </td>
                                 <td>{{$task->description}} <br>
                                    @if ($task->description == "JAF Filling")
                                       <small>of <strong> {{Helper::company_name($task->business_id)}}</strong> </small>
                                    @else
                                       <small> <strong>{{ Helper::get_service_name($task->service_id)}} {{$task->number_of_verifications}}</strong> of <strong>{{Helper::company_name($task->business_id)}} </strong> </small>
                                    @endif
                                 </td>
                                 <td>
                                    
                                   
                                    @if ($task->tastatus == '2')
                                       <span class="badge badge-info"> {{ Helper::user_name($task->user)}}</span>
                                    {{-- @else
                                       <span class="badge badge-info"> {{ Helper::user_name($task->assigned_to)}}</span> --}}
                                       @if($task->description == "Task for Verification")
                                          @if($task->user==Auth::user()->id || $task->reassign_to == Auth::user()->id)
                                             <br><a style='font-size:14px;' class="btn-lnk task_verify cursor-pointer" data-task_verify_can_id={{ base64_encode($task->candidate_id) }} data-task_verify_service_id={{ base64_encode($task->service_id) }} data-task_verify_nov_id={{ base64_encode($task->number_of_verifications) }}>Task for Verification</a> 
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
                                    <span>--</span>
                                 </td>
                                 <td>
                                    @if ($task->tastatus == '2')
                                       <span class="badge badge-success"> <strong>Completed</strong> </span>
                                   
                                    @endif
                                 </td>
                                 <td>
                                   
                                    @if ($task->assigned_to == NULL || $task->tastatus == '2')
                                        <span>--</span>
                                  
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
                     <label for="label_name"> Comments </label>
                     <textarea id="comments" name="comments" class="form-control comments" placeholder=""></textarea>
                     {{-- <input type="text" id="comments" name="comments" class="form-control comments" placeholder=""/> --}}
                     <p style="margin-bottom: 2px;" class="text-danger" id="error-comments"></p> 
                  </div>
                  <div class="form-group">
                     <label for="label_name"> Attachments: </label>
                     <input type="file" name="attachments[]" id="attachments" multiple class="form-control attachments">
                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-attachments"></p>  
                  </div>
               </div>
               <!-- Modal footer -->
               <div class="modal-footer">
               <button type="submit" class="btn btn-info">Submit </button>
               <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
      // $(document).ready(function(){

      // });


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

   $(document).on('submit', 'form#raise_insuff_form', function (event) {
                    
                    $("#overlay").fadeIn(300);　
                    event.preventDefault();
                    var form = $(this);
                    var data = new FormData($(this)[0]);
                    var url = form.attr("action");
                    var $btn = $(this);
        
                    $.ajax({
                        type: form.attr('method'),
                        url: url,
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            console.log(data);
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
                    return false;
        
   });

</script>