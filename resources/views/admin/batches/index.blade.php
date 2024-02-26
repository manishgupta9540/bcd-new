@extends('layouts.admin')

@section('content')
<style>
.export ul.dropdown-menu.show {
    text-align: center;
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
             <li>Batches</li>
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
      <div class="col-md-12">
         <div class="card text-left">
            <div class="card-body">
               <div class="row">
               @if ($message = Session::get('success'))
               <div class="col-md-12">   
                  <div class="alert alert-success">
                  <strong>{{ $message }}</strong> 
                  </div>
               </div>
               @endif
                  <div class="col-md-4">
                     <h4 class="card-title mb-1"> Batches</h4>
                     <p> List of all Batches </p>
                  </div>
                  @php
                  $ADD_ACCESS    = false;
                  // $REASSIGN_ACCESS   = false;
                  $VIEW_ACCESS   = false;
                  $ADD_ACCESS    = Helper::can_access('Add Batches','');//passing action title and route group name
                  // $REASSIGN_ACCESS    = Helper::can_access('Reassign','');//passing action title and route group name
                  $VIEW_ACCESS   = Helper::can_access('View Batches','');//passing action title and route group name
                  @endphp
                  <div class="col-md-4 text-center"><h2 class="mb-0">[Restricted]</h2></div>
                  <div class="col-md-4">
                     <div class="btn-group" style="float:right">
                        @if ($ADD_ACCESS)
                        <a class="btn btn-success " href="{{url('/batches/create')}}" > <i class="fa fa-plus"></i> Add New </a>             

                        @endif
                     </div>
                  </div>
               </div>
               {{-- <div class="row">
                  <div class="col-md-12">
                     <div class="table-responsive"> --}}
                    
                <div class="sparkline13-graph">
                    <div class="datatable-dashv1-list custom-datatable-overright">
                        <div id="toolbar">
                            <select class="form-control dt-tb">
                                <option value="">Export Basic</option>
                                <option value="all">Export All</option>
                                <option value="selected">Export Selected</option>
                            </select>
                        </div>
                        @if ($VIEW_ACCESS)
                        <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                        data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar" class="table batchTable">
                           <thead>
                              <tr>
                                 <th data-field="state" data-checkbox="true"></th>
                                 <th scope="col">Company Name</th>
                                 <th scope="col">Batch Name</th>
                                 <th scope="col">SLA</th>
                                 <th scope="col">No. of Candidates </th>
                                 <th scope="col">TAT </th>
                                 <th scope="col">Upload Date</th>
                                 <th scope="col">Created By </th>
                                 <th>Assign to</th>
                                 <th scope="col">Status</th>
                                 
                                 <th scope="col">Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              @if (count($batches) > 0 )
                                      @foreach ($batches as $batch)
                                          
                                <tr>
                                  <td></td>
                                   <td>{{Helper::company_name($batch->business_id)}}</td>
                                    <td>{{$batch->batch_name}}</td>
                                    <td>{{ Helper::get_sla_name($batch->sla_id)}}</td>
                                    <td>{{$batch->no_of_candidates}}</td>
                                    <td>{{$batch->tat}} Days</td>
                                    <td>{{ date('d-m-Y',strtotime($batch->created_at))}}</td>
                                    <td>{{Helper::user_name($batch->created_by)}}</td>
                                    <td>
                                       <??>
                                       @if ($batch->assign_to == '')
                                       <button class="btn  btn-sm assign" type="button" style="border-radius:1.2rem;" data-candidate ="{{$batch->no_of_candidates}}" data-tat="{{$batch->tat}}" data-batch="{{$batch->batch_name}}"  data-customer="{{$batch->business_id}}" data-sla="{{$batch->sla_id}}" data-service="{{$batch->alot_services}}" data-id ="{{$batch->id}}" > <i class="fas fa-user-plus"></i></button>
                                       @else
                                       {{Helper::user_name($batch->assign_to)}}
                                       @endif
                                    </td>
                                    <td>active</td>
                                    <td>
                                        <?php 
                                          $status ='';
                                          $status = Helper::get_batch_data($batch->id)
                                          ?> 
                                       <a href="{{ url('/batches/zip',['id'=>base64_encode($batch->id)]) }}">
                                       <button class="btn btn-sm btn-info "  type="button" style="border-radius:1.2rem;"> <i class="fa fa-download"></i> Download</button>
                                    </a><br>
                                       @if ($status)
                                            <button class="btn btn-success btn-sm approve" type="button" data-id="{{ base64_encode($batch->id) }}" > <i class='fa fa-check'></i></button>
                                            <button class="btn btn-danger btn-sm notApprove" type="button" data-batch_id="{{ base64_encode($batch->id) }}" > <i class='fa fa-close'></i></button>
                                       @endif

                                       
                                    </td>
                                   
                                </tr>
                                @endforeach 
                                @endif


                                
                                <div class="modal"  id="task">
                                 <div class="modal-dialog">
                                    <div class="modal-content">
                                       <!-- Modal Header -->
                                       <div class="modal-header">
                                          <h4 class="modal-title">Assign User</h4>
                                          <button type="button" class="close" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button>
                                       </div>
                                       <!-- Modal body -->
                                       <form action="{{ url('/batches/update') }}" id="batchForm">
                                       @csrf
                                       <input type="hidden" name="batch_id"  id="batch_id">
                                       <input type="hidden" name="service_id"  id="service_id">
                                       <input type="hidden" name="customer"  id="customer">

                                    
                                          <div class="modal-body">
                                          <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                                          <p style="margin-bottom: 2px;" class="text-success success-container" id="succes-data"> </p> 

                                          {{-- <div class="col-md-10">	 --}}
                       
                       
                                             <!-- select a customer  -->
                                             <div class="form-group">
                                                 <label for="service">Select a Customer <span class="text-danger">*</span></label>
                                                 <select class="form-control customer" name="customer_id" id="customer_id" disabled >
                                                     <option value="">-Select-</option>
                                                     @if( count($customers) > 0 )
                                                         @foreach($customers as $item)
                                                         <option value="{{ $item->id }}">{{ ucfirst($item->company_name).' '.'('.$item->name.')' }}</option>
                                                        
                                                         @endforeach
                                                     @endif
                                                 </select>
                                                 
                                                 <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-customer"></p>
                                             </div>
                                            
                                             <!-- select a SLA of customer  -->
                                             <div class="form-group">
                                                <label for="service">Select a SLA <span class="text-danger">*</span></label>
                                                <select class="form-control slaList" name="sla" id="sla">
                                                    {{-- <option value="">-Select-</option> --}}
                                                </select>
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-sla"></p>
                                            </div>
                                            
                                            <div class="form-group SLAResult">
                                            
                                            </div>
                     
                                             <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>
                     
                                                 {{-- <div class="row"> --}}
                                                     {{-- <div class="col-md-4"> --}}
                                                         <div class="form-group">
                                                             <label for="batch">Batch Name</label>
                                                             <input type="text" name="batch_name" class="form-control" id="batch_name" placeholder="Enter batch name" value="">
                                                             <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-batch"></p>  
                                                         </div>
                                                     {{-- </div> --}}
                                                 {{-- </div> --}}
                                                     {{-- <div class="col-md-4"> --}}
                                                         <div class="form-group">
                                                             <label>Number of Candidates <span class="text-danger">*</span></label>
                                                             
                                                             <input type="text" name ="no_of_candidates" id="no_of_candidates" class=" form-control">
                                                             <small class="text-muted"></small>
                                                             <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-no_of_candidates"></p>
                                                         </div>
                                                      {{-- </div> --}}
                                                     {{-- <div class="col-md-4"> --}}
                                                         <div class="form-group">
                                                             <label for="tat">TAT</label>
                                                             <input type="text" name="tat" class="form-control" id="tat" placeholder="Enter tat" value="{{ old('tat') }}">
                                                             <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-tat"></p>  
                                                         </div>
                                                     {{-- </div> --}}
                                                 
                                                
                                                <div class="form-group">
                                                   <label for="label_name">Assign To <span class="text-danger">*</span> </label>
                                                   {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
                                                      <div class="form-group">
                                                         <select class="select-option-field-7 user selectValue form-control" name="user" data-type="user" data-t="{{ csrf_token() }}">
                                                            <option value="">Select user</option>
                                                         
                                                            @foreach ($users as $user)
                                                            
                                                               <option value="{{$user->id}}" >{{$user->name}}</option>
                                                            @endforeach
                                                      </select>
                                                      </div>
                                                      <p style="margin-bottom: 2px;" class="text-danger" id="error-user"></p>
                                             {{-- </div>                                                 --}}
                                       </div>
                                             

                                          
                                          {{-- </div> --}}
                                          <!-- Modal footer -->
                                          <div class="modal-footer">
                                             
                                             <button type="submit" class="btn btn-info assign_submit  " >Submit </button>
                                             <button type="button" class="btn btn-danger back" id="assign_close" data-dismiss="modal">Close</button>
                                          </div>
                                       </form>
                                    </div>
                                 </div>
                              </div>
                           </tbody>
                        </table>
                        @else
                        <span><h3 class="text-center">You have no access to View Batches lists</h3></span>
                         @endif
                     </div>
                  </div>
               {{-- </div> --}}
            </div>
         </div>
      </div>
   </div>
   </div>
</div>
<!-- Script -->
    <script src="{{asset('js/data-table/bootstrap-table.js')}}"></script>
    <script src="{{asset('js/data-table/tableExport.js')}}"></script>
    <script src="{{asset('js/data-table/data-table-active.js')}}"></script>
    <script src="{{asset('js/data-table/bootstrap-table-editable.js')}}"></script>
    <script src="{{asset('js/data-table/bootstrap-editable.js')}}"></script>
    {{-- <script src="{{asset('js/data-table/bootstrap-table-resizable.js')}}"></script> --}}
    {{-- <script src="{{asset('js/data-table/colResizable-1.5.source.js')}}"></script> --}}
    <script src="{{asset('js/data-table/bootstrap-table-export.js')}}"></script>

    <!-- Script -->
<script type="text/javascript">

   $(document).ready(function(){

       $('.assign').click(function(){

        //   
         var batch_name = $(this).attr('data-batch');
         var batch_id = $(this).attr('data-id');

         var customer_id = $(this).attr('data-customer');
         var candidate_number = $(this).attr('data-candidate');
         var service_id = $(this).attr('data-service');
         var tat = $(this).attr('data-tat');
         var sla_id = $(this).attr('data-sla');

         $('#batch_name').val(batch_name);
         $('#batch_id').val(batch_id);

         $('#customer_id').val(customer_id);
         $('#customer').val(customer_id);

         $('#no_of_candidates').val(candidate_number);
         $('#service_id').val(service_id);        
         $('#tat').val(tat);
         $('#sla').val(sla_id);
         // $('#sla').val(sla_id);
        //  alert(service_id);

         // Open Model Pop up

           $('#task').toggle();

           $(".slaList").html("");
        $('.slaList').append("<option value=''>-Select-</option>");
        $(".SLAResult").html("");

      //   var customer = $('.customer option:selected').val();
        $.ajax({
        type:"POST",
        url: "{{ url('/customers/sla/getlist') }}",
        data: {"_token": "{{ csrf_token() }}",'customer_id':customer_id},      
        success: function (response) {
            console.log(response);
            if(response.success==true  ) {   
                $.each(response.data, function (i, item) {
                   
                    $(".slaList").append("<option value='"+item.id+"'  >" + item.title + "</option>");
                });

                $('.slaList').val(sla_id).change();
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

       $('.close').click(function(){
           $('#task').hide();
       });
       $('.back').click(function(){
           $('#task').hide();
       });
   });
   </script>

<script>
    
   $(function(){
       $('.switch').on('change.bootstrapSwitch', function(e) {
       console.log(e.target.checked);
   });

   // getting selected services checks
//    $(document).on('change','.services_list',function() {
//     var val = [];
    
//     $('.services_list:checked').each(function(i){
//           val[i] = $(this).val();
        
//         });
//         $('#service_id').val(val.join(','));
//    });
  
   //on select sla item
   $(document).on('change','.slaList',function(e) {
       e.preventDefault();
       $(".SLAResult").html("");
       var sla_id = $('.slaList option:selected').val();

    //    alert(sla_id);
    //    var val = [];
        // $('.services_list:checked').each(function(i){
        //   val[i] = $(this).val();
        // });
    //    alert(val);
       $.ajax({ 
       type:"POST",
       url: "{{ url('/batches/mixSla/serviceItems') }}",
       data: {"_token": "{{ csrf_token() }}",'sla_id':sla_id},      
       success: function (response) {
           console.log(response);
           if(response.success==true  ) {   
               $.each(response.data, function (i, item) {
                   
                 if(item.checked_atatus){
                     $(".SLAResult").append("<div class='form-check form-check-inline'><input class='form-check-input services_list' type='checkbox' checked name='services[]' value='"+item.service_id+"' id='"+item.service_id+"' ><label class='form-check-label' for='"+item.service_id+"'>"+item.service_name+"</label></div>");
                 }else{
                   $(".SLAResult").append("<div class='form-check form-check-inline'><input class='form-check-input services_list' type='checkbox' name='services[]' value='"+item.service_id+"' id='"+item.service_id+"' ><label class='form-check-label' for='"+item.service_id+"'>"+item.service_name+"</label></div>");
                 }

               });
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
   return false;
   });

//custom submit
// this is the id of the form

// $('.assign_submit').on('click', function() {
//     $('#assign_close').prop('disabled',true);

//     var $this = $(this);
//     var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
//     if ($(this).html() !== loadingText) {
//     $this.data('original-text', $(this).html());
//     $this.html(loadingText);
//     // $this.prop('disabled',true);
//     }
//     setTimeout(function() {
//     $this.html($this.data('original-text'));
//     $this.prop('disabled',false);
//     }, 5000);
// });

                // $('#assign_submit').click(function(e) {
                //     e.preventDefault();
                //     $("#batchForm").submit();
                // });
    
$("#batchForm").submit(function(e) {

e.preventDefault(); // avoid to execute the actual submit of the form.
$('p.error_container').html("");
      $('.form-control').removeClass('border-danger');
      var form = $(this);
      var data = new FormData($(this)[0]);
      var url = form.attr("action");
      var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
        $('.assign_submit').attr('disabled',true);
        $('#assign_close').prop('disabled',true);
        $('.form-control').attr('readonly',true);
        $('.form-control').addClass('disabled-link');
        $('.error-control').addClass('disabled-link');
        if ($('.assign_submit').html() !== loadingText) {
              $('.assign_submit').html(loadingText);
        }
$.ajax({
       type: "POST",
       url: url,
       data: form.serialize(), // serializes the form's elements.
       success: function(data)
       {
        window.setTimeout(function(){
                $('.assign_submit').attr('disabled',false);
                $('.form-control').attr('readonly',false);
                $('.form-control').removeClass('disabled-link');
                $('.error-control').removeClass('disabled-link');
                $('.assign_submit').html('Submit');
                $('#assign_close').prop('disabled',false);
            },2000);
        // $('.error-container').html('');
        if (data.fail == true) {
                //$("#overlay").fadeOut(300);
                for (control in data.errors) {
                    // $('textarea[assign=' + control + ']').addClass('is-invalid');
                    $('#error-' + control).html(data.errors[control]);
                }
        } 
        if (data.fail == false) {
            toastr.success("Batch has been assigned successfully");
                           // redirect to batches after 5 seconds
                window.setTimeout(function() {
                window.location.href='{{ Config::get('app.admin_url')}}/batches';
                }, 2000);
        // $('#success-data').html(data.success);
        }
       },
       error: function (response) {
            console.log(response);
        }
     });

 
});


});

</script>

<script>
    $(document).on('click', '.approve', function (event) {
            
       var batch_id = $(this).attr('data-id');
       if(confirm("Are you sure want to approve deletion ?")){
       $.ajax({
           type:'GET',
           url: "{{url('/batches/delete')}}",
           data: {'batch_id':batch_id,'type':'approve'},        
           success: function (response) {        
           console.log(response);
           
               if (response.status=='ok') {            
               
               
                   $('table.batchTable tr').find("[data-id='" + batch_id + "']").parent().parent().fadeOut("slow");
               
               } else {
                   
               }
           },
           error: function (response) {
                console.log(response);
            }
       });
    
       }
       return false;
    
    });


    // Not approved

    $(document).on('click', '.notApprove', function (event) {
            
            var batch_id = $(this).attr('data-batch_id');
            if(confirm("Are you sure want to Cancel deletion Request?")){
            $.ajax({
                type:'GET',
                url: "{{url('/batches/delete')}}",
                data: {batch_id:batch_id},        
                success: function (response) {        
                console.log(response);
                
                    if (response.status=='ok') {            
                    
                    
                        $('table.batchTable tr').find("[data-id='" + batch_id + "']").fadeOut("slow");
                        $('table.batchTable tr').find("[data-batch_id='" + batch_id + "']").fadeOut("slow");

                    } else {
                        
                    }
                },
                error: function (response) {
                    console.log(response);
                }
                // error: function (xhr, textStatus, errorThrown) {
                //     alert("Error: " + errorThrown);
                // }
            });
         
            }
            return false;
         
         });
    </script>
<!--end Script-->
@endsection
