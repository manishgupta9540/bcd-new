
{{-- <div class="row">
    <div class="col-md-12">
       <div class="table-responsive"> --}}
          {{-- @if ($VIEW_ACCESS) --}}
          @php
            use App\Traits\S3ConfigTrait;
         @endphp
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
            {{-- @if ($user_type == 'customer') --}}
               @if (count($tasks)>0)
                  @foreach ($tasks as $key=>$task) 
                     <tr>
                        <th scope="row"><input class="checks" type="checkbox" name="checks" value="{{ $task->id }}" onchange='checkChange();'></th>
                        @php
                          $vendor_task   =  Helper::get_vendor_completed_task($task->id,$task->service_id,$task->number_of_verifications);
                          $service_name  =  Helper::get_service_name($task->service_id);
                          $tat           =  Helper::get_diff($task->candidate_id);
                          $verify_data   =  Helper::get_vendor_verification_data($task->vtId);
                          //dd($verify_data);
                       @endphp
                      
                        <td>
                         {{ Helper::candidate_user_name($task->candidate_id)}} 
                         <br><small class="text-muted">Ref. No. <b>{{$task->display_id }}</b></small>
                            
                        </td>
                        <td>
                           {{ Helper::get_vendor_sla($task->vendor_sla_id)}}
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
                           @if ($task->reassigned_to == '' && $task->tastatus =='1' )
                           
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
                                 <span class="badge badge-info"> {{ Helper::user_name($task->reassigned_to)}}</span><br>
                                 @if ($task->description == "JAF Filling")
                                    @if ($job_item)
                                       <a href="{{ url('candidates/jaf-fill',['case_id'=>  base64_encode($job_item->id),'id' =>  base64_encode($job_item->candidate_id)])}}"  style='font-size:14px;'  data-cand_id="{{ base64_encode($job_item->candidate_id)}}" class="bnt-link jaf">JAF Link</a>

                                    @endif
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
                           @else
                              <span class="badge badge-warning"><strong>Unable to verify</strong></span><br>
                           @endif
                        </td>
                        <td>
                           @php
                              $vendor_insuff_status = Helper::get_vendor_insuff($task->candidate_id,$task->service_id,$task->number_of_verifications);
                              $vendor_status = Helper::get_vendor_status_insuff($task->candidate_id,$task->service_id,$task->number_of_verifications,$task->status);
                           @endphp

                            @if ($vendor_status)
                               @if ($vendor_status->status == 'cleared')
                                 <a href="javascript:;" class="badge badge-success raise_insuff_show" vendor-id="{{$task->assigned_to}}" candidate-id="{{$task->candidate_id }}"  service-id="{{ $task->service_id }}" service-name="{{$task->servicename}}" number-ver="{{$task->number_of_verifications}}"> <strong>Insuff Cleared</strong> </a>
                                 
                               @else
                                  @if ($vendor_status->status == 'raise')
                                    <a href="javascript:;" class="btn btn-success btn-sm text-wh raise_insuff_vendor" vendor-id="{{$task->assigned_to}}" candidate-id="{{$task->candidate_id }}"  service-id="{{ $task->service_id }}" service-name="{{$task->servicename}}" number-ver="{{$task->number_of_verifications}}">Insuff Cleared</a>
                                  @else
                                     <span class="badge badge-warning"> <strong>Insuff raised</strong> </span> 
                                  @endif
                               @endif
                            @endif
                           <br> 
                           @if ($verify_data != null)
                           @php
                            
                              $url = '';
                                 if(stripos($verify_data->file_platform,'s3')!==false)
                                 {
                                    $filePath = 'uploads/verification-file/';

                                    $s3_config = S3ConfigTrait::s3Config();

                                    $disk = \Storage::disk('s3');

                                    $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                          'Bucket'                     => \Config::get('filesystems.disks.s3.bucket'),
                                          'Key'                        => $filePath.$verify_data->zip_file,
                                          'ResponseContentDisposition' => 'attachment;'//for download
                                    ]);

                                    $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                    $url = $req->getUri();
                                 }
                                 else {
                                    $url = url('/').'/uploads/verification-file/'.$verify_data->zip_file;
                                 } 
                              @endphp
                             
                              @if ($task->assigned_to == NULL || $task->tastatus == '2')
                              
                                    <a class="btn btn-link" href="{{$url}}" title="download">Verified Data<i class="fas fa-download"></i></a><br>
                              
                                 <button class="btn btn-primary btn-sm preview_button" type="button" style="border-radius:1.2rem;"   data-tasks_id="{{ $task->id }}"  data-service_name="{{ $service_name }}" data-candidate_name="{{ $task->name }}" > <i class='fa fa-eye'></i> Preview  Data</button>

                                 {{-- <button class="btn btn-primary btn-sm preview_button" type="button" style="border-radius:1.2rem;"  data-vendor_tasks_id="{{ $task->id }}" > <i class='fa fa-eye'></i> Preview</button> --}}

                                 {{-- <span>--</span> --}}
                              @else
                                 <button class="btn btn-primary btn-sm preview_button" type="button" style="border-radius:1.2rem;"   data-tasks_id="{{ $task->id }}"  data-service_name="{{ $service_name }}" data-candidate_name="{{ $task->name }}"> <i class='fa fa-eye'></i> Preview  Data</button>
                              @endif
                           @endif
                        </td>
                     </tr>
                  @endforeach 
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

{{-- Insuff Raised vendor modal --}}
<div class="modal" id="raise_modal_vendor">
   <div class="modal-dialog" style="max-width: 70%;">
      <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
      <h4 class="modal-title" id="sername">Raise Insuff</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
        <div id="datashow" style="max-height: 300px; overflow-x: hidden; overflow-y: scroll;">
        
        </div>
      <!-- Modal body -->
         <form method="post" action="{{url('/vendor/task/raiseInsuff')}}" id="vendor_raise_insuff_form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="candidate_id" id="candidate_id">
            <input type="hidden" name="service_id" id="service_id">
            <input type="hidden" name="vendor_id" id="vendor_id">
            <input type="hidden" name="number_id" id="number_id">
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
            <button type="submit" class="btn btn-info raise_submit">Submit </button>
            <button type="button" class="btn btn-danger closeinsuffraise" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- End of Insuff Raised vendot Model --}}

{{-- Insuff Raised vendor modal data show --}}
<div class="modal" id="raise_modal_vendor_data_show">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
      <h4 class="modal-title" id="service_name">Raise Insuff</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div id="datashowmodal" style="max-height: 300px; overflow-x: hidden; overflow-y: scroll;">
      </div>
      <!-- Modal body -->
         <form method="post" action="{{url('/vendor/task/raiseInsuff')}}" id="vendor_raise_insuff_form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="candidate_id" id="candidate_id">
            <input type="hidden" name="service_id" id="service_id">
            <input type="hidden" name="vendor_id" id="vendor_id">
            <input type="hidden" name="number_id" id="number_id">
             <div class="modal-body overflow-modal">
               
               {{--<p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
               <div class="form-group">
                  <label for="label_name"> Comments </label>
                  <textarea id="comments" name="comments" class="form-control comments" placeholder=""></textarea>
                  <input type="text" id="comments" name="comments" class="form-control comments" placeholder=""/>
                  <p style="margin-bottom: 2px;" class="text-danger" id="error-comments"></p> 
               </div>
               <div class="form-group">
                  <label for="label_name"> Attachments: </label>
                  <input type="file" name="attachments[]" id="attachments" multiple class="form-control attachments">
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-attachments"></p>  
               </div>--}}
            </div> 
            <!-- Modal footer -->
            <div class="modal-footer">
            {{-- <button type="submit" class="btn btn-info raise_submit">Submit </button> --}}
            <button type="button" class="btn btn-danger closeinsuffraise" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- End of Insuff Raised vendot Model data show --}}

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


// $(document).on('click', '.raise_insuff', function (event) {
//    var can_id=$(this).attr('candidate-id');
//    var ser_id=$(this).attr('service-id');
//    var jaf_id=$(this).attr('jaf-id');
//    // var ser_name=$(this).attr('service-name');
//    $('#can_id').val(can_id);
//    // $('#ser_name').text('Verfication-'+ser_name);
//    $('#ser_id').val(ser_id);
//    $('#jaf_id').val(jaf_id);
//    $('#raise_modal').modal({
//       backdrop: 'static',
//       keyboard: false
//    });
// });

   $(document).on('click', '.raise_insuff_vendor', function (event) {
      var candidate_id=$(this).attr('candidate-id');
      var service_id=$(this).attr('service-id');
      var number_id=$(this).attr('number-ver');
      var venodr_id=$(this).attr('vendor-id')
      var service_name=$(this).attr('service-name');
      $('#candidate_id').val(candidate_id);
      $('#sername').text('Verfication-'+service_name);
      $('#service_id').val(service_id);
      $('#number_id').val(number_id);
      $('#vendor_id').val(venodr_id);
         $.ajax({
            type: "get",
            url: "{{url('/vendor/taskinsuffcleare')}}",
            data: {'service_id':service_id,'number_id':number_id,'candidate_id':candidate_id},
            success: function (response) {
               console.log(response)
               if(response !='null')
               {             
                  $('#datashow').html(response.html);
               
                  // $('#raise_modal_vendor').modal({
                  //       backdrop: 'static',
                  //       keyboard: false
                  // }); 
                  
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

//vendor data show 

$(document).on('click', '.raise_insuff_show', function (event) {
      var candidate_id=$(this).attr('candidate-id');
      var service_id=$(this).attr('service-id');
      var number_id=$(this).attr('number-ver');
      var venodr_id=$(this).attr('vendor-id');
      var service_name=$(this).attr('service-name');
      $('#candidate_id').val(candidate_id);
      $('#service_name').text('Verfication-'+service_name);
      $('#service_id').val(service_id);
      $('#number_id').val(number_id);
      $('#vendor_id').val(venodr_id);
         $.ajax({
            type: "get",
            url: "{{url('/vendor/taskinsuffcleare')}}",
            data: {'service_id':service_id,'number_id':number_id,'candidate_id':candidate_id},
            success: function (response) {
               console.log(response)
               if(response !='null')
               {             
                  $('#datashowmodal').html(response.html);
               
                  // $('#raise_modal_vendor_data_show').modal({
                  //       backdrop: 'static',
                  //       keyboard: false
                  // }); 
                  
               }
            },
            error: function (xhr, textStatus, errorThrown) {
               // alert("Error: " + errorThrown);
            }
            });
            event.stopImmediatePropagation();
           
            $('#raise_modal_vendor_data_show').modal({
               backdrop: 'static',
               keyboard: false
            });
   });





//vendor data show end
$(document).on('submit', 'form#vendor_raise_insuff_form', function (event) {
                 
      $("#overlay").fadeIn(300);　
      event.preventDefault();
      var form = $(this);
      var data = new FormData($(this)[0]);
      var url = form.attr("action");
      var $btn = $(this);
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
                  toastr.success("Insuff is Cleared");
                  // redirect to google after 5 seconds
                  window.setTimeout(function() {
                  location.reload(); 
                  }, 2000);
                  // window.location.href='{{ Config::get('app.admin_url')}}/aadharchecks/show';
                  //  location.reload(); 
               }
         },
         // error: function (xhr, textStatus, errorThrown) {
               
         //       alert("Error: " + errorThrown);

         // }
      });
      event.stopImmediatePropagation();
      return false;
     
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

</script>