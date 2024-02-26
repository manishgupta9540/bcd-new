@extends('layouts.superadmin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/app/home') }}">Dashboard</a>
             </li>
             {{-- <li>
                 <a href="{{ url('/app/settings/general') }}">Settings</a>
             </li> --}}
             <li>Holidays</li>
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
               <div class="col-md-3 content-container">
                  <!-- left-sidebar -->
                  @include('superadmin.settings.left-sidebar') 
               </div>
                  <!-- start right sec -->
                  <div class="col-md-9 content-wrapper" style="background:#fff">
                     <div class="formCover" style="height: 100vh;">
                        <!-- section -->
                        <section>
                            {{-- @include('superadmin.settings.checkprice.menu') --}}
                           <div class="col-sm-12 ">
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-6">
                                       <h4 class="card-title mt-3">Holidays </h4>
                                       <p class="pb-border"></p>
                                    </div>
                                    <div class="col-md-6 text-right mt-3">
                                      <div class="btn-group" style="float:right">     
                                          <span><a href="#" class="filter0search"><i class="fa fa-filter"></i></a></span>
                                          <span class="mr-1"><button class="btn btn-dark add_new_public_holiday_btn" title="Refresh Holiday"> <i class="fas fa-sync-alt"></i> Refresh </button></span>
                                          <span><a class="btn btn-success add_new_holiday_btn" href="#" > <i class="fa fa-plus"></i> Add New </a></span>
                                       </div>
                                    </div>
                                 </div>
                                 <!-- ./business detail -->
                                 
                           </div>
                           <div class="search-drop-field" id="search-drop">
                            <div class="row">
                              <div class="col-md-3 form-group mb-1">
                                    <label> From date </label>
                                    <input class="form-control from_date commonDatepicker" type="text" placeholder="From date">
                              </div>
                              <div class="col-md-3 form-group mb-1">
                                    <label> To date </label>
                                    <input class="form-control to_date commonDatepicker" type="text" placeholder="To date">
                              </div>
                              <div class="col-md-3 form-group mb-1">
                                 <label>Type</label><br>
                                 <select class="form-control holiday_type" name="holiday_type" id="holiday_type">
                                    <option value="">-- Select --</option>
                                    <option value="public">Public</option>
                                    <option value="custom">Custom</option>
                                 </select>
                              </div>
                              <div class="col-md-3 form-group mb-1 level_selector">
                                 <label>Name</label><br>
                                 <select class="form-control holiday_name select" name="holiday_name" id="holiday_name">
                                    <option> All </option>
                                    @foreach($holidays as $holiday)
                                       <option value="{{ $holiday->id }}"> {{ $holiday->name }} </option>
                                    @endforeach
                                 </select>
                              </div>
                               <div class="col-md-2">
                               <button class="btn btn-primary search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                               </div>
                           </div>
                         </div>
                           <div class="row">
                            <div class="col-md-12 pt-3">
                                <div id="candidatesResult">
                                    @include('superadmin.settings.holiday.ajax')        
                                 </div>
                            </div>
                         </div>
                        </section>
                        <!-- ./section -->
                        <!--  -->
                        <!-- ./section -->
                     </div>
                  </div>
                  <!-- end right sec -->
      </div>
   </div>
</div>
<div class="modal" id="add_holiday">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Add Holiday</h4>
             <button type="button" class="close btn-disable" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
          </div>
          <!-- Modal body -->
          <form method="post" action="{{url('app/settings/holiday/store')}}" id="holidayadd">
          @csrf
             <div class="modal-body">
             <div class="form-group">
                <label for="label_name"> Name: <span class="text-danger">*</span></label><br>
                <input type="text" name="name" class="form-control name" placeholder="Enter Holiday Name"/>
               <p style="margin-bottom: 2px;" class="text-danger error-container error-name" id="error-name"></p> 
             </div>
            <div class="form-group">
                <label for="label_name">Date : <span class="text-danger">*</span></label>
                <input type="text" name="date" class="form-control date datePicker" placeholder="Enter Date"/>
                <p style="margin-bottom: 2px;" class="text-danger error-container error-date" id="error-date"></p> 
            </div>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-disable">Submit </button>
                <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
             </div>
          </form>
       </div>
    </div>
</div>

@stack('scripts')
                     
<script>
    $(document).ready(function() {
       $(".select").select2();
       $('.filter0search').click(function(){
          $('.search-drop-field').toggle();
       });
       var uriNum = location.hash;
       pageNumber = uriNum.replace("#", "");
       // alert(pageNumber);
       getData(pageNumber);

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
 
       $(document).on('click','.filterBtn', function (e){    
         $("#overlay").fadeIn(300);　
         getData(0);
         e.preventDefault();
       });
 
       $(document).on('change','.holiday_name,.holiday_type,.from_date,.to_date', function (e){    
          $("#overlay").fadeIn(300);　
          getData(0);
          e.preventDefault();
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

       $('.add_new_public_holiday_btn').click(function(){
            if(confirm("Do You Want to Refresh Holidays ? ")){
               $(this).attr('disabled',true);
               var loadingText = '<i class="fas fa-sync fa-spin"></i> Loading...';
               if($(this).html!=loadingText)
               {
                  $(this).html(loadingText);
               }
                $.ajax({
                    type: 'GET',
                    url: "{{ url('/app/calender') }}",
                    data: {},        
                    success: function (response) {
                        // console.log(response);
                        window.setTimeout(function(){
                           $(this).attr('disabled',false);
                           $(this).html('<i class="fas fa-sync-alt"></i> Refresh');
                        },2000);
                        if(response.success)
                        {               
                           toastr.success('Record Added Successfully');
                           window.setTimeout(function(){
                                 location.reload();
                           },2000);
                        }
                    },
                    error: function (response) {
                        // alert("Error: " + errorThrown);
                        console.log(response);
                    }
                });
            }
            return false;
       });

       $('.add_new_holiday_btn').click(function(){
         $("#holidayadd")[0].reset();
         $('.form-control').removeClass('border-danger');
         $('.error-container').html('');
         $('.btn-disable').attr('disabled',false);
         $('#add_holiday').modal({
                backdrop: 'static',
                keyboard: false
         });
      });

      $(document).on('submit', 'form#holidayadd', function (event) {
        
        $("#overlay").fadeIn(300);　
        event.preventDefault();
        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        var $btn = $(this);
        $('.error-container').html('');
        $('.form-control').removeClass('border-danger');
        $('.btn-disable').attr('disabled',true);
         $.ajax({
               type: form.attr('method'),
               url: url,
               data: data,
               cache: false,
               contentType: false,
               processData: false,
               success: function (data) {
                  window.setTimeout(function(){
                     $('.btn-disable').attr('disabled',false);
                  },2000);
                  if (data.fail && data.error_type == 'validation') {
                        for (control in data.errors) {
                           $('.'+control).addClass('border-danger'); 
                           $('.error-' + control).text(data.errors[control]);
                        }
                  } 
                  if (data.fail && data.error == 'yes') {
                     
                     $('#error-all').html(data.message);
                  }
                  if (data.fail == false) {
                     toastr.success("Record Added Successfully");
                     window.setTimeout(function(){
                           location.reload();
                     },2000);
                     
                  }
               },
               error: function (data) {
                  
                  console.log(data);

               }
         });
        event.stopImmediatePropagation();
        return false;

      });

      $(document).on('click', '.deleteBtn', function (event) {
    
         var id = $(this).attr('data-id');
         //  alert(user_id);
         if(confirm("Are you sure want to delete this record ?")){
         $.ajax({
            type:'POST',
            url: "{{ url('/app/')}}"+"/settings/holiday/delete",
            data: {"_token" : "{{ csrf_token() }}",'id':id},        
            success: function (response) {        
            console.log(response);
            
                  if (response.status=='ok') { 

                     toastr.success("Record Deleted Successfully");
                     // window.setTimeout(function(){
                     //    location.reload();
                     // },2000);
                     $('table.holidayTable tr').find("[data-id='" + id + "']").parent().parent().parent().fadeOut("slow");
                  } 
                  else {
                     
                  }
            },
            error: function (xhr, textStatus, errorThrown) {
                  // alert("Error: " + errorThrown);
            }
         });

         }
         return false;

      });

      $(document).on('click', '.status', function (event) {

         var id = $(this).attr('data-id');
         var type =$(this).attr('data-type');
         //  alert(user_id);
         if(confirm("Are you sure want to change the status ?")){
            $.ajax({
                  type:'POST',
                  url: "{{ url('/app/')}}"+"/settings/holiday/status",
                  data: {"_token" : "{{ csrf_token() }}",'id':id,'type':type},        
                  success: function (response) {        
                  // console.log(response);
                  
                     if (response.status=='ok') { 
                        // window.setTimeout(function(){
                        //    location.reload();
                        // },2000);
                        // toastr.success("Status Changed Successfully");

                        if(response.type=='active')
                        {
                              $('table.holidayTable tr').find("[data-ac='" + id + "']").fadeIn("slow");
                              $('table.holidayTable tr').find("[data-ac='" + id + "']").removeClass("d-none");

                              $('table.holidayTable tr').find("[data-dc='" + id + "']").fadeOut("slow");

                              $('table.holidayTable tr').find("[data-dc='" + id + "']").addClass("d-none");

                              $('table.holidayTable tr').find("[data-a='" + id + "']").fadeOut("slow");
                              $('table.holidayTable tr').find("[data-a='" + id + "']").addClass("d-none");

                              $('table.holidayTable tr').find("[data-d='" + id + "']").fadeIn("slow");

                              $('table.holidayTable tr').find("[data-d='" + id + "']").removeClass("d-none");

                              
                        }
                        else if(response.type=='deactive')
                        {
                              $('table.holidayTable tr').find("[data-dc='" + id + "']").fadeIn("slow");
                              $('table.holidayTable tr').find("[data-dc='" + id + "']").removeClass("d-none");

                              $('table.holidayTable tr').find("[data-ac='" + id + "']").fadeOut("slow");

                              $('table.holidayTable tr').find("[data-ac='" + id + "']").addClass("d-none");

                              $('table.holidayTable tr').find("[data-d='" + id + "']").fadeOut("slow");
                              $('table.holidayTable tr').find("[data-d='" + id + "']").addClass("d-none");

                              $('table.holidayTable tr').find("[data-a='" + id + "']").fadeIn("slow");

                              $('table.holidayTable tr').find("[data-a='" + id + "']").removeClass("d-none");
                        }
                     } 
                     else {
                        
                     }
                  },
                  error: function (xhr, textStatus, errorThrown) {
                     // alert("Error: " + errorThrown);
                  }
            });

         }
         return false;

      });

        var year = (new Date).getFullYear();
        $( ".datePicker" ).datepicker({
            changeMonth: true,
            changeYear: false,
            firstDay: 1,
            autoclose:true,
            todayHighlight: true,
            format: 'dd-mm-yyyy',
            startDate: new Date(year,0,1),
            endDate : new Date(year,11,31)
        });
      
    });
    
    function getData(page){
         //set data
        //  var user_id     =    $(".customer_list").val();                
 
         var from_date   =    $(".from_date").val(); 
         var to_date     =    $(".to_date").val();  

         var holiday_id     =    $(".holiday_name").val();  

         var type = $('.holiday_type').val();   
 
             $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
 
             $.ajax(
             {
                 url: '?page=' + page+'&holiday_id='+holiday_id+'&from_date='+from_date+'&to_date='+to_date+'&type='+type,
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

            // var user_id     =    $(".customer_list").val();                
            //   var check       =    $(".check option:selected").val();

              var from_date   =    $(".from_date").val(); 
              var to_date     =    $(".to_date").val();   
              var holiday_id        =    $(".holiday_name").val();  
              var type = $('.holiday_type').val();
        
            $.ajax(
            {
                url: "{{ url('/') }}"+'/candidates/setData/?holiday_id='+holiday_id+'&from_date='+from_date+'&to_date='+to_date+'&type='+type,
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
 </script>


@endsection
