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
                        <li class="nav-item"><a href="{{url('/task/assign')}}" class="nav-link">Assigned Tasks</a></li>
                        <li class="nav-item"><a href="{{url('/task/unassign')}}" class="nav-link">Unassigned Tasks</a></li>
                        <li class="nav-item"><a href="{{url('/task/complete')}}" class="nav-link  active">Completed Tasks</a></li>
                        <li class="nav-item"><a href="{{url('/task/vendor')}}" class="nav-link">Vendor Tasks</a></li>
                        @else
                           <li class="nav-item"><a href="{{url('/task')}}" class="nav-link ">All Tasks</a></li>
                           <li class="nav-item"><a href="{{url('/task/assign')}}" class="nav-link ">Assigned Tasks</a></li>
                           <li class="nav-item"><a href="{{url('/task/complete')}}" class="nav-link active">Completed Tasks</a></li>
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
                  @include('admin.task.complete-ajax')
                  
               </div>
            </div>
         </div>
      {{-- </div> --}}
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
      $(document).on('change','.from_date, .to_date, .candidate_list,.sla_list,.user_list.ref_list,#rows,#service,#task_type,#ref', function (e){    
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
      var service_id = $("#service option:selected").val();
      var task_type = $("#task_type option:selected").val();                         
      // var mob = $('.mob').val();
      var ref = $('.ref').val();
      // var email = $('.email').val();

      var sla_id   =     $(".sla_list option:selected").val();

      var cus_user_id   =     $(".user_list option:selected").val();

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

