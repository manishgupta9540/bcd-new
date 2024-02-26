@extends('layouts.admin')
@section('content')
<style>
   .sweet-alert button.cancel {
        background: #DD6B55 !important;
    }
</style>
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Account / Billing </h3>
               </div>
            </div>
         </div>
      </div> --}}
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/home') }}">Dashboard</a>
             </li>
             <li>
                 <a href="{{ url('/settings/general') }}">Accounts</a>
             </li>
             <li>Report</li>
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
                     <div class="formCover py-2" style="height: 100vh;">
                        <!-- section -->
                        <section>
                           @include('admin.accounts.reports.menu') 
                           <div class="col-sm-12 ">
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-6">
                                       <h4 class="card-title mb-1 mt-3">Report File Name Config </h4>
                                       <p class="pb-border">Note :- If we enable the file name cofigure feature, we can  change the name of the file</p>
                                    </div>
                                    <div class="col-md-6 mt-3 text-right">

                                       {{-- <a href="" class="mt-3 btn btn-sm btn-primary">Payment Method</a> --}}

                                       <div class="btn-group" style="float:right">     
                                          <a href="#" class="filter0search"><i class="fa fa-filter"></i></a> 
                                       </div>
                                    </div>
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
                                         {{-- <input class="form-control candidate_list" type="text" placeholder="name"> --}}
                                     </div>
                                       <div class="col-md-2">
                                       <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                       </div>
                                   </div>
                                 </div>
                                 
                                 <div class="row">
                                    <div class="col-md-12 pt-3">
                                       <div id="candidatesResult">
                                          @include('admin.accounts.reports.fileconfig-ajax')        
                                       </div>
                                    </div>
                                 </div>
                                 <!-- ./business detail -->
                                 
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

  <!-- Footer Start -->
  <div class="flex-grow-1"></div>
  
</div>
<div class="modal" id="file_config_modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">Report File Name Config</h4>
            <button type="button" class="close btn-disable" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{url('/reports/fileconfig/edit')}}" id="file_config_frm" enctype="multipart/form-data">
         @csrf
           <input type="hidden" name="customer_id" class="customer_id" id="customer_id">
            <div class="modal-body">
               <div class="row">
                  <div class="col-12">
                     <div class="form-group">
                        <label for="label_name"> Customer : </label>
                        <span class="cust_name"></span>
                    </div>
                  </div>
                  <div class="col-12">
                     <div class="result">
                        
                     </div>
                     <p style="margin-bottom:2px;" class="text-danger error-container error-all" id="error-all">
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
    //   $(".select1").select2();

      $('.filter0search').click(function(){
         $('.search-drop-field').toggle();
      });
   
      var uriNum = location.hash;
      pageNumber = uriNum.replace("#", "");
      // alert(pageNumber);
      getData(pageNumber);

      $(document).on('click','.filterBtn', function (e){    
        $("#overlay").fadeIn(300);　
        getData(0);
        e.preventDefault();
      });

      $(document).on('change','.customer_list', function (e){    
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

      $(document).on('click','.edit',function(){
            var id = $(this).attr('data-id');
            
            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);
            $('#file_config_modal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $.ajax({
               type:'GET',
               url: "{{ url('/')}}"+"/reports/fileconfig/edit",
               data: {'customer_id':id},        
               success: function (data) {        
               
                  if(data !='null')
                  { 
                     //check if primary data 

                     $('.customer_id').val(id);
                     $('.cust_name').html(data.result.company_name+' - '+data.result.name);
                     $('.result').html(data.form);
                  }
               },
               error: function (xhr, textStatus, errorThrown) {
                     // alert("Error: " + errorThrown);
               }
            });
      });

      $(document).on('submit', 'form#file_config_frm', function (event) {
         $("#overlay").fadeIn(300);　
            event.preventDefault();
            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var $btn = $(this);
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
            $('.error-container').html('');
            $('.form-control').removeClass('is-invalid');
            $('.btn-disable').attr('disabled',true);
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
               success: function (data) {        
                     // console.log(data);
                     window.setTimeout(function(){
                        $('.btn-disable').attr('disabled',false);
                        $('.submit_btn').html('Submit');
                     },2000);
                     if (data.fail && data.error_type == 'validation') {
                            //$("#overlay").fadeOut(300);
                            for (control in data.errors) {
                              var error_text = control.replace('.',"_");
                                $('#error-' + error_text).html(data.errors[control]);
                            }
                    } 
                    if (data.fail && data.error == 'yes') {
                        
                        $('#error-all').html(data.message);
                    }
                    if (data.fail == false) {
                        toastr.success("Report File Name Config Submitted Successfully !!");
                        window.setTimeout(function(){
                            location.reload();
                        },2000);
                        
                    }
                     
               },
               error: function (xhr, textStatus, errorThrown) {
                    //  alert("Error: " + errorThrown);
               }
            });

        

      });

      $(document).on('change','.file_list',function(){

         var file_id=$(this).attr("value");
         if(this.checked)
         {
            $('input[name=order-'+file_id).attr('readonly',false);
         }
         else
         {
            $('input[name=order-'+file_id).attr('readonly',true);
         }
      });
   
   });
      function getData(page){
         //set data
         var user_id     =    $(".customer_list").val();                
        //  var service_id     =    $(".service_list").val();                

         //   var from_date   =    $(".from_date").val(); 
         //   var to_date     =    $(".to_date").val();      

               $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);

               $.ajax(
               {
                  url: '?page=' + page+'&customer_id='+user_id,
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
         //   var check       =    $(".check option:selected").val();

         //   var from_date   =    $(".from_date").val(); 
         //   var to_date     =    $(".to_date").val();    
        //  var service_id     =    $(".service_list").val();         
               $.ajax(
               {
                  url: "{{ url('/') }}"+'/candidates/setData/?customer_id='+user_id,
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

    //when click on hide button
    $(document).on('click', '.hold', function (event) {
        
        var customer_id = $(this).attr('data-customer');
      //   if(confirm("Are you sure want to Disable File Renaming of this COC ?")){
      //   $.ajax({
      //       type:'POST',
      //       url: "{{url('/reports/fileconfig/status')}}",
      //       data: {"_token" : "{{ csrf_token() }}",'customer_id':customer_id,'status':0},        
      //       success: function (response) {        
      //       // console.log(response);
            
      //           if (response.status=='ok') {            
                
      //               $('table.customerTable tr').find("[data-customer='" + customer_id + "']").fadeOut("slow");
                    
      //               $('table.customerTable tr').find("[data-cust_id='" + customer_id + "']").fadeOut("slow");
      //               $('table.customerTable tr').find("[data-cus_id='" + customer_id + "']").removeClass("d-none").show();
      //               $('table.customerTable tr').find("[data-customer_id='" + customer_id + "']").removeClass("d-none").show();
                    

      //           } else {
                    
      //           }
      //       },
      //       error: function (xhr, textStatus, errorThrown) {
      //          //  alert("Error: " + errorThrown);
      //       }
      //   });

      // }
      //   return false;

        swal({
                  // icon: "warning",
                  type: "warning",
                  title: "Are you sure want to Disable File Renaming of this COC ?",
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
                                 url: "{{url('/reports/fileconfig/status')}}",
                                 data: {"_token" : "{{ csrf_token() }}",'customer_id':customer_id,'status':0},        
                                 success: function (response) {        
                                 // console.log(response);
                                 
                                    if (response.status=='ok') {            
                                    
                                       $('table.customerTable tr').find("[data-customer='" + customer_id + "']").fadeOut("slow");
                                       
                                       $('table.customerTable tr').find("[data-cust_id='" + customer_id + "']").fadeOut("slow");
                                       $('table.customerTable tr').find("[data-cus_id='" + customer_id + "']").removeClass("d-none").show();
                                       $('table.customerTable tr').find("[data-customer_id='" + customer_id + "']").removeClass("d-none").show();
                                       

                                    } else {
                                       
                                    }
                                 },
                                 error: function (xhr, textStatus, errorThrown) {
                                    //  alert("Error: " + errorThrown);
                                 }
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

    //when click on show button
    $(document).on('click', '.resume', function (event) {
        
        var customer_id = $(this).attr('data-customer_id');
      //   if(confirm("Are you sure want to Enable File Renaming of this COC ?")){
      //   $.ajax({
      //       type:'POST',
      //       url: "{{url('/reports/fileconfig/status')}}",
      //       data: {"_token" : "{{ csrf_token() }}",'customer_id':customer_id,'status':1},        
      //       success: function (response) {        
      //       // console.log(response);
            
      //           if (response.status=='ok') {            
      //               $('table.customerTable tr').find("[data-customer_id='" + customer_id + "']").fadeOut("slow");
                    
      //               $('table.customerTable tr').find("[data-cus_id='" + customer_id + "']").fadeOut("slow");
      //               $('table.customerTable tr').find("[data-cust_id='" + customer_id + "']").removeClass("d-none").show();
      //               $('table.customerTable tr').find("[data-customer='" + customer_id + "']").removeClass("d-none").show();

      //           } else {
                    
      //           }
      //       },
      //       error: function (xhr, textStatus, errorThrown) {
      //          //  alert("Error: " + errorThrown);
      //       }
      //   });

      //   }
      //   return false;

        swal({
                  // icon: "warning",
                  type: "warning",
                  title: "Are you sure want to Enable File Renaming of this COC ?",
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
                              url: "{{url('/reports/fileconfig/status')}}",
                              data: {"_token" : "{{ csrf_token() }}",'customer_id':customer_id,'status':1},        
                              success: function (response) {        
                              // console.log(response);
                              
                                 if (response.status=='ok') {            
                                    $('table.customerTable tr').find("[data-customer_id='" + customer_id + "']").fadeOut("slow");
                                    
                                    $('table.customerTable tr').find("[data-cus_id='" + customer_id + "']").fadeOut("slow");
                                    $('table.customerTable tr').find("[data-cust_id='" + customer_id + "']").removeClass("d-none").show();
                                    $('table.customerTable tr').find("[data-customer='" + customer_id + "']").removeClass("d-none").show();

                                 } else {
                                    
                                 }
                              },
                              error: function (xhr, textStatus, errorThrown) {
                                 //  alert("Error: " + errorThrown);
                              }
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
    
</script>
                     
 
@endsection
