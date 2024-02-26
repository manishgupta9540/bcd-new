@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      {{-- 
      <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Account / Billing </h3>
               </div>
            </div>
         </div>
      </div>
      --}}
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
                           <h4 class="card-title mb-1 mt-3">Footer Address </h4>
                           <p class="pb-border">Note :- If we can add  address and when enable this feature this address will reflect on the report.</p>
                        </div>
                        <div class="col-md-6 mt-3 text-right">
                           {{-- <a href="" class="mt-3 btn btn-sm btn-primary">Payment Method</a> --}}
                           <div class="btn-group" style="float:right">     
                              <a href="#" class="filter0search"><i class="fa fa-filter"></i></a> 
                           </div>
                           {{-- <div class="btn-group" style="float:right">     
                              <button type="button" class="addAddress btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i> Default Address</button>
                           </div> --}}
                        </div>
                     </div>
                     <div class="search-drop-field" id="search-drop">
                        <div class="row">
                           <div class="col-md-3 form-group mb-1 level_selector">
                              <label>Customer Name</label><br>
                              <select class="form-control customer_list select " name="customer_name" id="customer_name">
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
                              @include('admin.accounts.address.custom-ajax')        
                           </div>
                        </div>
                     </div>
                     <!-- ./business detail -->
                  </div>
               </section>
            </div>
         </div>
         <!-- end right sec -->
      </div>
   </div>
</div>
<div class="modal" id="add_address_modal">
   <div class="modal-dialog">
      <div class="modal-content">
      <!-- Modal Header -->
         <div class="modal-header">
         <h4 class="modal-title" id="address_header">Add Address</h4>
         <button type="button" class="close" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{url('/reports/customer/address')}}" id="add_address">
         @csrf
            {{-- <input type="hidden" name="user" id="use"> --}}
            <input type="hidden" name="customer_id" id="customer_id">
            <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-name"> </p>
           
               <div class="form-group" >
                  <label for="label_name">Address </label>
                  {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
                  <div class="form-group">
                     <input class="form-control address" type="text" name="address" value="{{old('address')}}">
                      
                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-address"></p>
                  </div>
               </div>
            </div>
            <!-- Modal successfooter -->
            <div class="modal-footer">
            <button type="submit" class="btn btn-info add_address_submit " >Submit </button>
            <button type="button" class="btn btn-danger add_address_back" id="add_address_back" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- Footer Start -->
<div class="flex-grow-1"></div>
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
   
      $(document).on('click', '.edit', function () {
         $('.address').val("");
         var customers_id = $(this).attr('data-customers_id');
         var address = $(this).attr('data-address');
         // console.log(address);
         if (address=='') {
            $('#address_header').text('Add Address');
         } else {
            $('#address_header').text('Edit Address');
         }
         $('#customer_id').val(customers_id);
         $('.address').val(address);
         
         $('#add_address_modal').modal({
                    backdrop: 'static',
                    keyboard: false
         });
         // $.ajax({
         //    type:'GET',
         //    url: "{{url('/reports/address')}}",
         //    data: {'customer_id':customer_id},        
         //    success: function (response) {    

         //    }
         // });
      });

      $(".add_address_submit").on('click',function(){
         // event.preventDefault();
         //clearing the error msg
         $('p.error_container').html("");
      
         // var form = $(this);
         var address = $(".address").val();
         var customer_id     =   $("#customer_id").val();
         // alert(customer_id);
         $.ajaxSetup({
            headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
         })
         $.ajax({
               method:'POST',
               url: "{{url('/reports/customer/address')}}",
               data: {'address':address,'customer_id':customer_id},
                   
               success: function (response) {
      
                  console.log(response);
                  if(response.success==true  ) {          
                     
                     //notify
                     toastr.success("Address has been updated successfully");
                     // redirect to google after 5 seconds
                     window.setTimeout(function() {
                           window.location = "{{ url('/')}}"+"/reports/address/";
                     }, 2000);
                     
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
               // error: function (xhr, textStatus, errorThrown) {
               //    // alert("Error: " + errorThrown);
               // }
         });
         event.stopImmediatePropagation();
         return false;
      }); 

    //when click on hide button
    $(document).on('click', '.hold', function (event) {
        
      var customer_id = $(this).attr('data-customer');
      swal({
         // icon: "warning",
         type: "warning",
         title: "Are you sure you want to change the status?",
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
                  type:'GET',
                  url: "{{url('/reports/address/disable')}}",
                  data: {'customer_id':customer_id},        
                  success: function (response) {        
                  console.log(response);
                  
                     if (response.status=='ok') {            
                     
                        $('table.customerTable tr').find("[data-customer='" + customer_id + "']").fadeOut("slow");
                        
                        $('table.customerTable tr').find("[data-cust_id='" + customer_id + "']").fadeOut("slow");
                        //   $('table.customerTable tr').find("[data-customers_id='" + customer_id + "']").fadeOut("slow");
                        $('table.customerTable tr').find("[data-cus_id='" + customer_id + "']").removeClass("d-none").show();
                        $('table.customerTable tr').find("[data-customer_id='" + customer_id + "']").removeClass("d-none").show();
                        

                     } else {
                        
                     }
                  },
                  error: function (xhr, textStatus, errorThrown) {
                     alert("Error: " + errorThrown);
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
        return false;

    });

    //when click on show button
    $(document).on('click', '.resume', function (event) {
        
        var customer_id = $(this).attr('data-customer_id');
            swal({
                    // icon: "warning",
                    type: "warning",
                    title: "Are you sure you want to change the status?",
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
                              type:'GET',
                              url: "{{url('/reports/address/enable')}}",
                              data: {'customer_id':customer_id},        
                              success: function (response) {        
                              console.log(response);
                              
                                 if (response.status=='ok') {            
                                    $('table.customerTable tr').find("[data-customer_id='" + customer_id + "']").fadeOut("slow");
                                    
                                    $('table.customerTable tr').find("[data-cus_id='" + customer_id + "']").fadeOut("slow");
                                    $('table.customerTable tr').find("[data-cust_id='" + customer_id + "']").removeClass("d-none").show();
                                    $('table.customerTable tr').find("[data-customer='" + customer_id + "']").removeClass("d-none").show();
                                    //   $('table.customerTable tr').find("[data-customers_id='" + customer_id + "']").removeClass("d-none").show();
                                 } else {
                                    
                                 }
                              },
                              error: function (xhr, textStatus, errorThrown) {
                                 alert("Error: " + errorThrown);
                              }
                        });

                     swal.close();
                  }
                  else
                  {
                     swal.close();
                  }
               });
        return false;

    });
   //  //when click on hide button
   //  $(document).on('click', '.hold', function (event) {
        
   //      var customer_id = $(this).attr('data-customer');
   //      if(confirm("Are you sure want to Disable Custom PDF page of this COC ?")){
   //      $.ajax({
   //          type:'GET',
   //          url: "{{url('/reports/custom/disable')}}",
   //          data: {'customer_id':customer_id},        
   //          success: function (response) {        
   //          console.log(response);
            
   //              if (response.status=='ok') {            
                
   //                  $('table.customerTable tr').find("[data-customer='" + customer_id + "']").fadeOut("slow");
                    
   //                  $('table.customerTable tr').find("[data-cust_id='" + customer_id + "']").fadeOut("slow");
   //                  $('table.customerTable tr').find("[data-cus_id='" + customer_id + "']").removeClass("d-none").show();
   //                  $('table.customerTable tr').find("[data-customer_id='" + customer_id + "']").removeClass("d-none").show();
                    
   
   //              } else {
                    
   //              }
   //          },
   //          error: function (xhr, textStatus, errorThrown) {
   //              alert("Error: " + errorThrown);
   //          }
   //      });
   
   //    }
   //      return false;
   
   //  });
   
   //  //when click on show button
   //    $(document).on('click', '.resume', function (event) {
        
   //      var customer_id = $(this).attr('data-customer_id');
   //      $("#customerId").val(customer_id);
   //      $('#add_pdf_address').modal('show');
   //    });
   //    $(document).on('click','.editaddress',function(){
   //      var customerpdfid = $(this).attr('data-id'); 
   //      var address = $(this).attr('data-address');
   //      $('#add_pdf_address').modal('show');
   //      $("#add_address").val(address);
   //      $("#update_id").val(customerpdfid);
   //    });

      // $(".add_pdf_address").on('click',function(){
      //    var formData = new FormData($("#footerAddressForm")[0]);
      //   if(confirm("Are you sure want to add address?")){
      //   $.ajax({
      //       type:'POST',
      //       url: "{{url('/reports/customer/address')}}",
      //       processData: false,
      //       contentType: false,
      //       data: formData,        
      //       success: function (response) {        
            
      //           if(response.success==true){
      //             toastr.success("Footer address added successfully");
      //             setTimeout(() => {
      //               location.reload();
      //             }, 5000);
      //           }else if(response.success=='update'){
      //             toastr.success("Footer address updated successfully");
      //             setTimeout(() => {
      //               location.reload();
      //             }, 5000);
      //           }
      //       },
      //       error: function (xhr, textStatus, errorThrown) {
      //           alert("Error: " + errorThrown);
      //       }
      //   });
   
      //   }
      //   return false;
   
   //  });    
</script>
@endsection
