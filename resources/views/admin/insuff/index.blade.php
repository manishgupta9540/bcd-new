@extends('layouts.admin')
@section('content')
<style>
   .modal {
    
    z-index: 9999!important;
   }
   .insuff-data {
    max-height: 300px;
    overflow-x: hidden;
    overflow-y: scroll;
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
             <li>Insufficiency</li>
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
                     <div class="col-md-5">
                        <h4 class="card-title mb-1"> Insufficiency</h4>
                        <p> List of all Insufficiency </p>
                     </div>
                     <div class="col-md-6"><h2 class="mb-0">[Restricted]</h2></div>
               </div>
               <div class="row" >
                  <div class="col-md-1 form-group">
                     <label for="picker1"> Export </label>
                     <select class="form-control check"  id="check">
                        <option value="">-Select-</option>
                        <option value="excel">Excel</option>   
                        <option value="csv">CSV</option>   
                     </select>
                  </div>
                  <div class="col-md-2 form-group mt-4">
                        <a class="btn-link " id="exportExcel" href="javascript:;"> <i class="far fa-file-archive"></i> Download Excel</a> <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-original-title="Download Insuff Data in bulk"></i>
                        <p style="margin-bottom:2px;" class="load_container text-danger" id="loading"></p>
                  </div>
                  <div class="col-md-7 form-group mt-4 text-right">
                     <label for="picker1" style="float:right;" ><strong>Numbers of Rows:-</strong> <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-original-title="Click to select number of rows"></i> </label>
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
                  <div class="col-md-1 form-group mt-3">
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
                     <div class="col-md-3 form-group mb-1 level_selector">
                           <label for="picker1"> Customer </label>
                           <select class="form-control customer_list select " name="customer" id="customer">
                           <option>-Select-</option>
                           @foreach($customers as $customer)
                              <option value="{{ $customer->id }}"> {{ $customer->company_name.' - '.$customer->name}} </option>
                           @endforeach
                           </select>
                     </div>
                     <div class="col-md-3 form-group mb-1 level_selector">
                        <label for="picker1"> Candidate Name</label>
                        <select class="form-control candidate_list select " name="candidate" id="candidate_list">
                        <option value="">-Select-</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group mb-1">
                           <label>Email id</label>
                           <input class="form-control email" type="email" placeholder="Email ID">
                     </div>
                     <div class="col-md-2 form-group mb-1">
                        <label>Phone number </label>
                        <input class="form-control mob" type="text" placeholder="Phone No.">
                    </div>
                    <div class="col-md-2 form-group mb-1">
                        <label>Reference number </label>
                        <input class="form-control ref" type="text" placeholder="Reference number">
                    </div>
                    <div class="col-md-2 form-group mb-1">
                        <label>Status </label>
                        <select class="form-control status" name="status" id="status">
                           <option value="">-Select-</option>
                           <option value="raised">Raise</option>
                           <option value="removed">Clear</option>
                        </select>
                     </div>
                 </div>
                 <div class="text-right">
                  <button class="btn btn-info search filterBtn" style="width:15%;padding: 7px;margin: 18px 0px;"> Filter </button>
                  </div>
               </div>
               {{-- <div class="row">
                  <div class="col-md-12">
                     <div class="table-responsive"> --}}
                        <div id="candidatesResult">
                           @include('admin.insuff.ajax')
                        </div>
                     {{-- </div> --}}
                  {{-- </div>
               </div> --}}
               
            </div>
         </div>
      </div>
   </div>
   </div>
</div>

<div class="modal" id="raise_detail_modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" id="ser_name"></h4>
            {{-- <button type="button" class="close closeraisemdl" data-dismiss="modal">&times;</button> --}}
         </div>
         <!-- Modal body -->
           <input type="hidden" name="can_id" id="can_id">
           <input type="hidden" name="ser_id" id="ser_id">
           <input type="hidden" name="jaf_id" id="jaf_id">
            <div class="modal-body">
               <div id="raise_data">

               </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="button" class="btn btn-danger closeraisemdl" data-dismiss="modal">Close</button>
            </div>
      </div>
   </div>
</div>

<div class="modal" id="clear_detail_modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" id="servic_name"></h4>
            {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
         </div>
         <!-- Modal body -->
           <input type="hidden" name="candid_id" id="candid_id">
           <input type="hidden" name="servic_id" id="servic_id">
           <input type="hidden" name="jaf_form_id" id="jaf_form_id">
            <div class="modal-body">
               <div id="clear_data">

               </div>
            </div>
            

         <!-- Modal footer -->
         
         <div class="modal-footer">
            <button type="button" class="btn btn-danger closeraisemdl" data-dismiss="modal" style="text-align: right;">Close</button>
         </div>
      </div>
   </div>
</div>

<div class="modal" id="clear_modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" id="serv_name"></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{url('/candidates/jaf/clearCheckInsuff')}}" id="clear_insuff_form">
         @csrf
           <input type="hidden" name="cand_id" id="cand_id">
           <input type="hidden" name="serv_id" id="serv_id">
           <input type="hidden" name="jaf_f_id" id="jaf_f_id">
            <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
               <div class="form-group">
                     <label for="label_name"> Comments </label>
                     <textarea id="comment" name="comment" class="form-control comment" placeholder=""></textarea>
                     {{-- <input type="text" id="comments" name="comments" class="form-control comments" placeholder=""/> --}}
                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-comment"></p> 
               </div>
               <div class="form-group">
                  <label for="label_name"> Attachments: </label>
                  <input type="file" name="attachment[]" id="attachment" multiple class="form-control attachment">
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-attachment"></p>  
               </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-info clear-submit">Submit </button>
               <button type="button" class="btn btn-danger closeraisemdl closeinsuffclear" id="clear_insuff_close" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>

<!-- insuff clear modal -->
<div class="modal" id="insuff_clear_data_modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" id="servic_name"></h4>
            {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
         </div>
         <!-- Modal body -->
            <div class="modal-body">

               <div id="insuff_clear_data">

               </div>
            </div>
            

         <!-- Modal footer -->
         
         <div class="modal-footer">
            <button type="button" class="btn btn-danger closeraisemdl" data-dismiss="modal" style="text-align: right;">Close</button>
         </div>
      </div>
   </div>
</div>


<!-- insuff clear end modal-->
<!-- Script -->
   {{-- <script src="{{asset('js/data-table/bootstrap-table.js')}}"></script>
    <script src="{{asset('js/data-table/tableExport.js')}}"></script>
    <script src="{{asset('js/data-table/data-table-active.js')}}"></script>
    <script src="{{asset('js/data-table/bootstrap-table-editable.js')}}"></script>
    <script src="{{asset('js/data-table/bootstrap-editable.js')}}"></script>
    <script src="{{asset('js/data-table/bootstrap-table-resizable.js')}}"></script>
    <script src="{{asset('js/data-table/colResizable-1.5.source.js')}}"></script>
    <script src="{{asset('js/data-table/bootstrap-table-export.js')}}"></script> --}}

    <script>
       $(document).ready(function(){

            $(".select").select2();
            $(".check").select2();
             $(".rows").select2();
            $('.filter0search').click(function(){
               $('.search-drop-field').toggle();
            });
            $('.filter_close').click(function(){
                  $('.search-drop-field').toggle();
            });

            var uriNum = location.hash;
            pageNumber = uriNum.replace("#", "");
            // alert(pageNumber);
            getData(pageNumber);

            $(document).on('change','.customer_list,.from_date, .to_date,.candidate_list,.email,.mob,.ref,.rows,.status', function (e){    
                  $("#overlay").fadeIn(300);　
                  getData(0);
                  e.preventDefault();
            });
      
            $(document).on('click','.filterBtn', function (e){    
               $("#overlay").fadeIn(300);　
               getData(0);
               e.preventDefault();
            });

            $(document).on('change','.customer_list',function(e) {
                     e.preventDefault();
                     $('.candidate_list').empty();
                     $('.candidate_list').append("<option value=''>-Select-</option>");
                     var customer_id = $('.customer_list option:selected').val();
                     $.ajax({
                     type:"POST",
                     url: "{{ url('/candidates/getlist') }}",
                     data: {"_token": "{{ csrf_token() }}",'customer_id':customer_id},      
                     success: function (response) {
                        // console.log(response);
                        if(response.success==true  ) {   
                           $.each(response.data, function (i, item) {
                              $(".candidate_list").append("<option value='"+item.id+"'> "+ item.name +" ("+ item.display_id+")</option>");
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
            
            $(document).on('click','.raise_detail',function(){
               $('#can_id').val("");
               $('#ser_name').text('Verification - '+"");
               $('#ser_id').val("");
               $('#jaf_id').val("");
               $('#raise_data').html("");
               var can_id=$(this).attr('data-candidate');
               var ser_id=$(this).attr('data-service');
               var jaf_id=$(this).attr('data-jaf');
               var ser_name=$(this).attr('data-service_name');
               var notes= $(this).attr('data-notes');
               $('#can_id').val(can_id);
               $('#ser_name').text('Verification - '+ser_name);
               $('#ser_id').val(ser_id);
               $('#jaf_id').val(jaf_id);

                  $.ajax({
                     type:'GET',
                     url: "{{route('/insuff_detail')}}",
                     data: {'candidate_id':can_id,'jaf_id':jaf_id,'service_id':ser_id,'service_name':ser_name,'type':'raised'},        
                     success: function (response) {        
                     // console.log(response);

                     $('#raise_data').html(response.form);
                     $('#ser_name').text('Verification - '+response.service_name);
                     $('#raise_detail_modal').modal({
                           backdrop: 'static',
                           keyboard: false
                        });
                     // if (response.status=='ok') {            
                        
                        
                     // } else {

                     //    alert('No data found');

                     // }
                  },
                  error: function (xhr, textStatus, errorThrown) {
                     // alert("Error: " + errorThrown);
                  }
               });

            });

            $(document).on('click','.clear_detail',function(){
               $('#candid_id').val("");
               $('#servic_name').text('Verification - '+"");
               $('#servic_id').val("");
               $('#jaf_form_id').val("");
               $('#clear_data').html("");

               var candidate_id = $(this).attr('data-candidate');
               var jaf_id       = $(this).attr('data-jaf');
               var service_id   = $(this).attr('data-service');
               var servi_name  = $(this).attr('data-service_name');
               // var notes= $(this).attr('data-notes');
               // alert(servi_name);

               $('#servic_name').text('Verification - '+servi_name);
               $('#servic_id').val(service_id);
               $('#jaf_form_id').val(jaf_id);
               $('#candid_id').val(candidate_id);

                  $.ajax({
                     type:'GET',
                     url: "{{route('/insuff_detail')}}",
                     data: {'candidate_id':candidate_id,'jaf_id':jaf_id,'service_id':service_id,'service_name':servi_name,'type':'removed'},        
                     success: function (response) {        
                     // console.log(response);

                     $('#clear_data').html(response.form);
                     $('#ser_name').text('Verification - '+response.service_name);
                     $('#clear_detail_modal').modal({
                        backdrop: 'static',
                        keyboard: false
                     });
                     // if (response.status=='ok') {            
                        
                        
                     // } else {

                     //    alert('No data found');

                     // }
                  },
                  error: function (xhr, textStatus, errorThrown) {
                     // alert("Error: " + errorThrown);
                  }
               });

            });

            $(document).on('click','.insuff_raise_clear',function(){
               var insuff_log_id = $(this).attr('data-log');
            
               $.ajax({
                     type:'GET',
                     url: "{{route('/clear_insuff_data')}}",
                     data: {'insuff_log_id':insuff_log_id},        
                     success: function (response) {        
         
                     $('#insuff_clear_data').html(response.form);
                     
                     $('#insuff_clear_data_modal').modal({
                        backdrop: 'static',
                        keyboard: false
                     });
                     
                  },
                  error: function (xhr, textStatus, errorThrown) {
                     // alert("Error: " + errorThrown);
                  }
               });
               
            });

      
            
            $(document).on('click', '.itemMarkAsCleared', function (event) {
         
                  var candidate_id = $(this).attr('candidate-id');
                  var jaf_id       = $(this).attr('jaf-id');
                  var service_id   = $(this).attr('service-id');
                  var servi_name  = $(this).attr('service-name');

                  // alert(servi_name);

                  $('#serv_name').text('Verification - '+servi_name);
                  $('#serv_id').val(service_id);
                  $('#jaf_f_id').val(jaf_id);
                  $('#cand_id').val(candidate_id);

                  $('#clear_modal').modal({
                     backdrop: 'static',
                     keyboard: false
                  });
                  // if(confirm("Are you sure want clear insuff staus?")){
                  // $.ajax({
                  //    type:'GET',
                  //    url: "{{route('/candidates/jaf/clearCheckInsuff')}}",
                  //    data: { 'candidate_id':candidate_id,'jaf_item_id':jaf_id,'service_id':service_id},        
                  //    success: function (response) {        
                  //    console.log(response);
                     
                  //       if (response.status=='ok') {            
                           
                  //          toastr.success("Insuff is Cleared successfully");
                  //             // redirect to google after 5 seconds
                  //             window.setTimeout(function() {
                  //             location.reload(); 
                  //             }, 2000);
                        
                  //       } else {

                  //          toastr.success("Check Insuff Status");
                  //             // redirect to google after 5 seconds
                  //             window.setTimeout(function() {
                  //             location.reload(); 
                  //             }, 2000);
                  //       }
                  //    },
                  //    error: function (xhr, textStatus, errorThrown) {
                  //       alert("Error: " + errorThrown);
                  //    }
                  // });

                  // }
                  // return false;
            });

            // $('.back').click(function(){
            //    $('#verify_assign_modal').hide();
            // });         
            // $('.clear-submit').on('click', function() {
            //    $('#clear_insuff_close').prop('disabled',true);
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

            // $('#verifyAssignBtn').click(function(e) {
            //       e.preventDefault();
            //       $("#verify_assign_form").submit();
            // });

            $(document).on('submit', 'form#clear_insuff_form', function (event) {
                  $("#overlay").fadeIn(300);　
                  event.preventDefault();
                  var form = $(this);
                  var data = new FormData($(this)[0]);
                  var url = form.attr("action");
                  var $btn = $(this);
                  var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
                  $('.error-container').html('');
                  $('.form-control').removeClass('border-danger');
                  $('.clear-submit').attr('disabled',true);
                  $('.closeinsuffclear').attr('disabled',true);
                  if($('.clear-submit').html()!==loadingText)
                  {
                     $('.clear-submit').html(loadingText);
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
                           $('.error-container').html('');
                           window.setTimeout(function(){
                              $('.clear-submit').attr('disabled',false);
                              $('.clear-submit').html('Submit');
                              $('.closeinsuffclear').attr('disabled',false);
                           },2000);
                           if (data.fail && data.error_type == 'validation') {
                                 //$("#overlay").fadeOut(300);
                                 for (control in data.errors) {
                                 // $('textarea[comment=' + control + ']').addClass('is-invalid');
                                 $('.'+control).addClass('border-danger');
                                 $('#error-' + control).html(data.errors[control]);
                                 }
                           } 
                        //  if (data.fail && data.error == 'yes') {
                              
                        //      $('#error-all').html(data.message);
                        //  }
                           if(data.fail && data.status=='no')
                           {
                              toastr.error("Insufficiency Failed");
                              // redirect to google after 5 seconds
                              window.setTimeout(function() {
                              location.reload(); 
                              }, 2000);
                           }
                           if (data.fail == false) {
                              // $('#send_otp').modal('hide');
                              // alert(data.id);
                              // if(data.success){
                                 // toastr.success("Mail is Sent Successfully");
                                 toastr.success("Insuff is Cleared successfully");
                                 // redirect to google after 5 seconds
                                 window.setTimeout(function() {
                                 location.reload(); 
                                 }, 2000);
                                 // window.location.href='{{ Config::get('app.admin_url')}}/aadharchecks/show';
                                 //  location.reload();
                              // }
                              // else
                              // {
                              //    toastr.error("Something Went Wrong!!");
                              // } 
                           }
                           if(data.fail && data.status=='error'){
                              toastr.error("Something Went Wrong!!");
                           }
                     },
                     error: function (data) {
                           
                        console.log(data);

                     }
                     // error: function (xhr, textStatus, errorThrown) {
                           
                     //       alert("Error: " + errorThrown);

                     // }
                  });
                  return false;
            });

            $(document).on('click','.closeraisemdl',function(event){
               $("#comments").val("");
               $("#comment").val("");
               $("#attachments").val("");
               $("#attachment").val("");
               $('.error-container').html('');
               $('.form-control').removeClass('border-danger');

               // $.ajax(
               // {
               //    url: "{{ url('/') }}"+'/candidates/sessionForget',
               //    type: "get",
               //    datatype: "html",
               // })
               // .done(function(data)
               // {
               //    console.log(data);
               // })
               // .fail(function(jqXHR, ajaxOptions, thrownError)
               // {
               //    //alert('No response from server');
               // });

            });

            $(document).on('click','#exportExcel',function(){
               var _this=$(this);
               var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';
               $('p.load_container').html("");

               var check=0;  
               var select = document.getElementById("check");

               var export_candidate_id=[];

               // var i=0;
               // $('.check option:selected').each(function () {
               //     export_service_id[i++] = $(this).val();
               // });

               // alert(export_service_id);

               // var j=0;
               // $('.priority:checked').each(function () {
               //     export_candidate_id[j++] = $(this).val();
               // });

               // alert(export_candidate_id);
               
               // for(var i = 0; i < select.options.length; i++){
               //       if(select.options[i].selected){
               //          check++;
               //       }
               // }
               
               if(select.value=='')
               {
                     alert("Please Select the Option first");
               }
               else
               {
                     var type = select.value;
                     var candidate=document.querySelectorAll('.checks:checked').length;

                     if(candidate<=0)
                     {
                        alert("Please Select the Candidate first");
                     }
                     else
                     {

                        var j=0;
                        $('.checks:checked').each(function () {
                           export_candidate_id[j++] = $(this).val();
                        });

                        _this.addClass('disabled-link');
                        $('#loading').html(loadingText);
                        var user_id     =    $(".customer_list").val();                
                        var from_date   =    $(".from_date").val(); 
                        var to_date     =    $(".to_date").val();  
                        var status = $('.status option:selected').val();

                        $.ajax(
                        {
                           
                           url: "{{ url('/') }}"+'/insuff/setData',
                           type: "get",
                           data: {'customer_id':user_id,'from_date':from_date,'to_date':to_date,'export_candidate_id':export_candidate_id,'type':type,'status':status},
                           datatype: "html",

                        })
                        .done(function(data)
                        {
                           window.setTimeout(function(){
                              _this.removeClass('disabled-link');
                              $('#loading').html("");
                              // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                           },2000);
                           
                           // console.log(data);
                           var path = "{{ url('/') }}"+'/insuff-export/';
                           window.open(path);
                        })
                        .fail(function(jqXHR, ajaxOptions, thrownError)
                        {
                           //alert('No response from server');
                        });

                     }
                     
               }


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

       });

       function getData(page){
         //set data
           var user_id     =    $(".customer_list").val();                
           var from_date   =    $(".from_date").val(); 
           var to_date     =    $(".to_date").val();      
           var candidate_id=    $(".candidate_list option:selected").val();
            var mob = $('.mob').val();
            var ref = $('.ref').val();
            var email = $('.email').val();
            var rows = $("#rows option:selected").val();
            var status = $('.status option:selected').val();
             $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
 
             $.ajax(
             {
                 url: '?page=' + page+'&customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&mob='+mob+'&ref='+ref+'&email='+email+'&rows='+rows+'&status='+status,
                 type: "get",
                 datatype: "html",
             })
             .done(function(data)
             {
                 $("#candidatesResult").empty().html(data);
                 $("#overlay").fadeOut(300);
                 //debug to check page number
                 location.hash = page;
             })
             .fail(function(jqXHR, ajaxOptions, thrownError)
             {
                 alert('No response from server');
 
             });
 
       }
   
       function setData(){

         var user_id     =    $(".customer_list").val();                
           var from_date   =    $(".from_date").val(); 
           var to_date     =    $(".to_date").val();      
           var candidate_id=    $(".candidate_list option:selected").val();
            var mob = $('.mob').val();
            var ref = $('.ref').val();
            var email = $('.email').val();   
            var rows = $("#rows option:selected").val();
         
               $.ajax(
               {
                  url: "{{ url('/') }}"+'/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&mob='+mob+'&ref='+ref+'&email='+email+'&rows='+rows,
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

       }

        // Select all check
      function checkAll(e) {
            var checkboxes = document.getElementsByClassName('checks');
            
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

            var totalCheckbox = document.querySelectorAll('.checks').length;
            var totalChecked = document.querySelectorAll('.checks:checked').length;
            // alert(totalCheckbox);
            // alert(totalChecked);
            // When total options equals to total checked option
            if(totalCheckbox == totalChecked) {
               document.getElementsByName("showhide")[0].checked=true;
            } else {
               document.getElementsByName("showhide")[0].checked=false;
            }
      }
   </script>
@endsection
