@extends('layouts.admin')
@section('content')
<style>
   .sweet-alert button.cancel {
        background: #DD6B55 !important;
    }
   .addDataDiv
   {
      max-height: 300px;
      overflow-x: hidden;
      overflow-y: scroll;
   }
   .disabled-link
   {
      pointer-events: none;
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
             <li>
                 <a href="{{ url('/app/settings/general') }}">Accounts</a>
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
               <div class="col-md-3 content-container">
                  <!-- left-sidebar -->
                  @include('admin.accounts.left-sidebar') 
               </div>
                  <!-- start right sec -->
                  <div class="col-md-9 content-wrapper" style="background:#fff">
                     <div class="formCover" style="height: 100vh;">
                        <!-- section -->
                        <section>
                           @include('admin.accounts.insufficiency.menu')
                           <div class="col-sm-12 ">
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-9">
                                       <h4 class="card-title mb-1 mt-4">Insufficiency Notification Control </h4>
                                       <span>
                                          Note:- If there is any insufficiency at the time of report generation then enable that customer to send the mail.(Enter the email address via edit button to which you want to send the mail).
                                       </span>
                                       <p class="pb-border"></p>
                                    </div>
                                    <div class="col-md-3 text-right mt-3">
                                      <div class="btn-group" style="float:right">
                                        @if(count($items)>0)     
                                          <span><a href="#" class="filter0search"><i class="fa fa-filter"></i></a></span>
                                        @endif
                                       </div>
                                    </div>
                                 </div>
                                 <!-- ./business detail -->
                                 
                           </div>
                           <div class="search-drop-field" id="search-drop">
                            <div class="row">
                              <div class="col-md-3 form-group mb-1 level_selector">
                                 <label>Customer Name</label><br>
                                 <select class="form-control customer_list select" name="customer_name" id="customer_name">
                                     <option> All </option>
                                     @foreach($customers as $customer)
                                       <option value="{{ $customer->id }}"> {{ $customer->company_name.' - '.$customer->first_name}} </option>
                                     @endforeach
                                 </select>
                              </div>
                               <div class="col-md-2">
                                    <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                               </div>
                           </div>
                         </div>
                           <div class="row">
                            <div class="col-md-12 pt-3">
                                <div id="candidatesResult">
                                    @include('admin.accounts.insufficiency.report.ajax')        
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

<div class="modal" id="notify_modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">Notification</h4>
            <button type="button" class="close btn-disable" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{url('/settings/insuff_control/report/edit')}}" id="notify_frm" enctype="multipart/form-data">
            @csrf
           <input type="hidden" name="id" class="id" id="id">
            <div class="modal-body">
               <div class="row">
                  <div class="col-12">
                     <div class="form-group">
                        <label for="label_name" class="cust_lbl"> Company Name : </label>
                        <span class="cust_name"></span>
                    </div>
                  </div>
                  <div class="col-12">
                     <h5 class="text-muted">Details:-</h5>
                     <p class="pb-border"></p>
                     <div class="addDataDiv">
                     </div>
                     <a href="javascript:;" class="add_data"><i class="fa fa-plus my-4"></i> Add</a>
                  </div>
               </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-info btn-disable submit_btn">Submit </button>
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
 
       $(document).on('change','.from_date,.to_date,.customer_list', function (e){    
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

     

      $(document).on('click', '.status', function (event) {

         var id = $(this).attr('data-id');
         var type =$(this).attr('data-type');
         //  alert(user_id);
         // if(confirm("Are you sure want to change the status ?")){
         //    $.ajax({
         //          type:'POST',
         //          url: "{{ url('/')}}"+"/settings/insuff_control/report/status",
         //          data: {"_token" : "{{ csrf_token() }}",'id':id,'type':type},        
         //          success: function (response) {        
         //          // console.log(response);
                  
         //             if (response.status=='ok') { 
         //                // window.setTimeout(function(){
         //                //    location.reload();
         //                // },2000);
         //                // toastr.success("Status Changed Successfully");

         //                if(response.type=='active')
         //                {
         //                      $('table.insuffTable tr').find("[data-ac='" + id + "']").fadeIn("slow");
         //                      $('table.insuffTable tr').find("[data-ac='" + id + "']").removeClass("d-none");

         //                      $('table.insuffTable tr').find("[data-dc='" + id + "']").fadeOut("slow");

         //                      $('table.insuffTable tr').find("[data-dc='" + id + "']").addClass("d-none");

         //                      $('table.insuffTable tr').find("[data-a='" + id + "']").fadeOut("slow");
         //                      $('table.insuffTable tr').find("[data-a='" + id + "']").addClass("d-none");

         //                      $('table.insuffTable tr').find("[data-d='" + id + "']").fadeIn("slow");

         //                      $('table.insuffTable tr').find("[data-d='" + id + "']").removeClass("d-none");

                              
         //                }
         //                else if(response.type=='deactive')
         //                {
         //                      $('table.insuffTable tr').find("[data-dc='" + id + "']").fadeIn("slow");
         //                      $('table.insuffTable tr').find("[data-dc='" + id + "']").removeClass("d-none");

         //                      $('table.insuffTable tr').find("[data-ac='" + id + "']").fadeOut("slow");

         //                      $('table.insuffTable tr').find("[data-ac='" + id + "']").addClass("d-none");

         //                      $('table.insuffTable tr').find("[data-d='" + id + "']").fadeOut("slow");
         //                      $('table.insuffTable tr').find("[data-d='" + id + "']").addClass("d-none");

         //                      $('table.insuffTable tr').find("[data-a='" + id + "']").fadeIn("slow");

         //                      $('table.insuffTable tr').find("[data-a='" + id + "']").removeClass("d-none");
         //                }
         //             } 
         //             else {
                        
         //             }
         //          },
         //          error: function (xhr, textStatus, errorThrown) {
         //             // alert("Error: " + errorThrown);
         //          }
         //    });

         // }
         // return false;

         swal({
            // icon: "warning",
            type: "warning",
            title: "Are you sure want to change the status ?",
            text: "",
            dangerMode: true,
            showCancelButton: true,
            confirmButtonColor: "#007358",
            confirmButtonText: "YES",
            cancelButtonText: "CANCEL",
            closeOnConfirm: false,
            closeOnCancel: false
            },
            function(e){
               if(e==true)
               {
                      $.ajax({
                           type:'POST',
                           url: "{{ url('/')}}"+"/settings/insuff_control/report/status",
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
                                       $('table.insuffTable tr').find("[data-ac='" + id + "']").fadeIn("slow");
                                       $('table.insuffTable tr').find("[data-ac='" + id + "']").removeClass("d-none");

                                       $('table.insuffTable tr').find("[data-dc='" + id + "']").fadeOut("slow");

                                       $('table.insuffTable tr').find("[data-dc='" + id + "']").addClass("d-none");

                                       $('table.insuffTable tr').find("[data-a='" + id + "']").fadeOut("slow");
                                       $('table.insuffTable tr').find("[data-a='" + id + "']").addClass("d-none");

                                       $('table.insuffTable tr').find("[data-d='" + id + "']").fadeIn("slow");

                                       $('table.insuffTable tr').find("[data-d='" + id + "']").removeClass("d-none");

                                       
                                 }
                                 else if(response.type=='deactive')
                                 {
                                       $('table.insuffTable tr').find("[data-dc='" + id + "']").fadeIn("slow");
                                       $('table.insuffTable tr').find("[data-dc='" + id + "']").removeClass("d-none");

                                       $('table.insuffTable tr').find("[data-ac='" + id + "']").fadeOut("slow");

                                       $('table.insuffTable tr').find("[data-ac='" + id + "']").addClass("d-none");

                                       $('table.insuffTable tr').find("[data-d='" + id + "']").fadeOut("slow");
                                       $('table.insuffTable tr').find("[data-d='" + id + "']").addClass("d-none");

                                       $('table.insuffTable tr').find("[data-a='" + id + "']").fadeIn("slow");

                                       $('table.insuffTable tr').find("[data-a='" + id + "']").removeClass("d-none");
                                 }
                              } 
                              else {
                                 
                              }

                              swal.close();
                           },
                           error: function (xhr, textStatus, errorThrown) {
                              // alert("Error: " + errorThrown);
                           }
                     });
                   
               }
               else
               {
                    swal.close();
               }
        });

      });

      //when click on Edit button
      $(document).on('click', '.editBtn', function (event) {
            var id = $(this).attr('data-id');
            
            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);
            $('#notify_modal').modal({
               backdrop: 'static',
               keyboard: false
            });

            $.ajax({
               type: 'GET',
               url: "{{ url('/settings/insuff_control/report/edit') }}",
               data: {'id':id},        
               success: function (data) {
                  //   console.log(data);
                  $("#notify_frm")[0].reset();
                  if(data !='null')
                  { 
                        //check if primary data 
                        $('.id').val(id);

                        $('.addDataDiv').html(data.form);

                        $('.cust_name').html(data.result.company_name+' - '+data.result.name);

                        $('.modal-footer').removeClass('d-none');

                        if(data.count<=0)
                        {
                           $('.modal-footer').addClass('d-none');
                        }

                        addData();
                  }
               },
               error: function (xhr, textStatus, errorThrown) {
                  // alert("Error: " + errorThrown);
               }
            });

      });

      $(document).on('click','.add_data',function(){ 
         var s_len = $('.cust_data').length;
         if(s_len + 1 > 15)
         {
            swal({
                  title: "You Can Include Maximum 15 Contacts !!",
                  text: '',
                  type: 'warning',
                  buttons: true,
                  dangerMode: true,
                  confirmButtonColor:'#003473'
               });
         }
         else
         {
            $(".addDataDiv").append(
            `<div class='cust_data' row-id='1'>
               <div class='form-group'>
                  <div class="row">
                     <div class="col-md-10">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label> Name <span class="text-danger">*</span></label>
                                 <input class='form-control name' type='text' name='name[]' value='' id="name">
                                 <p style='margin-bottom: 2px;' class='text-danger error_container error-name' id="error-name"></p>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label> Email <span class="text-danger">*</span></label>
                                 <input class='form-control email' type='text' name='email[]' value='' id="email">
                                 <p style='margin-bottom: 2px;' class='text-danger error_container error-email' id="error-email"></p>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-6">
                              <div class="form-group">
                                 <label> Status <span class="text-danger">*</span></label>
                                 <select class="form-control sts_r" name="status">
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                 </select>
                                 <p style='margin-bottom: 2px;' class='text-danger error_container error-status' id="error-status"></p>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-2 mt-3">
                        <span class="btn btn-link text-danger close_div" style="font-size:20px;"><i class="far fa-times-circle"></i></span>
                     </div>
                  </div>
               </div>
               <p class="pb-border"></p>
            </div>
            `
            );
            addData();


            $('.modal-footer').removeClass('d-none');
         }
            
      });

      $(document).on('click','.close_div',function(){
         var _this=$(this);
         
         _this.parent().parent().parent().parent().fadeOut("slow", function(){ 
            _this.parent().parent().parent().parent().remove();
               addData();
               var s_len = $('.cust_data').length;
               if(s_len<=0)
               {
                  $('.modal-footer').addClass('d-none');
               }
         });

        

      });

      $(document).on('submit', 'form#notify_frm', function (event) {
            event.preventDefault();
            //clearing the error msg
            $('p.error_container').html("");

            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
            $('.btn-disable').attr('disabled',true);
            $('.form-control').attr('readonly',true);
            $('.form-control').addClass('disabled-link');
            $('.error-control').addClass('disabled-link');
            if ($('.submit_btn').html() !== loadingText) {
               $('.submit_btn').html(loadingText);
            }
               $.ajax({
                  type: form.attr('method'),
                  url: url,
                  data: data,
                  cache: false,
                  contentType: false,
                  processData: false,      
                  success: function (response) {
                     window.setTimeout(function(){
                           $('.btn-disable').attr('disabled',false);
                           $('.form-control').attr('readonly',false);
                           $('.form-control').removeClass('disabled-link');
                           $('.error-control').removeClass('disabled-link');
                           $('.submit_btn').html('Submit');
                        },2000);
                     // console.log(response);
                     if(response.success==true) {          
                     
                           //notify
                           toastr.success("Notification Setting Has Been Submitted Successfully !!");
                           // redirect to google after 5 seconds
                           window.setTimeout(function() {
                              // window.location.reload();
                              $('#notify_modal').modal('hide');
                           }, 2000);
                     
                     }
                     //show the form validates error
                     if(response.success==false ) {                              
                           for (control in response.errors) {
                              var error_text = control.replace('.',"_");   
                              $('#error-' + error_text).html(response.errors[control]);
                           }
                     }
                  },
                  error: function (response) {
                     // alert("Error: " + errorThrown);
                     console.log(response);
                  }
               });
               event.stopImmediatePropagation();
               return false;
      });


      $(document).on('click','.delete_div',function(){
            
            var id = $(this).attr('data-id');
            var _this=$(this);

            swal({
               // icon: "warning",
               type: "warning",
               title: "Are You Sure Want To Delete ?",
               text: "",
               dangerMode: true,
               showCancelButton: true,
               confirmButtonColor: "#007358",
               confirmButtonText: "YES",
               cancelButtonText: "CANCEL",
               closeOnConfirm: false,
               closeOnCancel: false
               },
               function(e){
                  if(e==true)
                  {
                     $.ajax({
                           type:'POST',
                           url: "{{route('/settings/insuffControl/report/delete')}}",
                           data: {"_token": "{{ csrf_token() }}",'id':id},        
                           success: function (response) {        
                           // console.log(response);
                           
                              if (response.status=='ok') {    

                                 _this.parent().parent().parent().parent().fadeOut("slow", function(){ 
                                    _this.parent().parent().parent().parent().remove();
                                       addData();
                                       var s_len = $('.cust_data').length;
                                       if(s_len<=0)
                                       {
                                          $('.modal-footer').addClass('d-none');
                                       }
                                 });
                                                            
                              } else {

                                 toastr.error("Something Went Wrong !!");
                                    
                              }
                           },
                           error: function (response) {
                              console.log(response);
                           }
                           // error: function (xhr, textStatus, errorThrown) {
                           //    alert("Error: " + errorThrown);
                           // }
                        });

                        swal.close();
                  }
                  else
                  {
                     swal.close();
                  }
               }
            );
      });
      
    });
    
    function getData(page){
         //set data
        //  var user_id     =    $(".customer_list").val();                
 
         var from_date   =    $(".from_date").val(); 
         var to_date     =    $(".to_date").val();  

        var cust_id = $('.customer_list').val();   
 
             $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
 
             $.ajax(
             {
                 url: '?page=' + page+'&from_date='+from_date+'&to_date='+to_date+'&customer_id='+cust_id,
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
              var cust_id = $('.customer_list').val();   
        
            $.ajax(
            {
                url: "{{ url('/') }}"+'/candidates/setData/?from_date='+from_date+'&to_date='+to_date+'&customer_id='+cust_id,
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
