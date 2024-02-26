@extends('layouts.admin')
@section('content')

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
             <li>Check Control</li>
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
                            {{-- @include('admin.accounts.verifications.menu') --}}
                              @if ($message = Session::get('success'))
                                 <div class="col-md-12">   
                                    <div class="alert alert-success">
                                       <strong>{{ $message }}</strong> 
                                    </div>
                                 </div>
                              @elseif($message = Session::get('error'))
                                 <div class="col-md-12">   
                                    <div class="alert alert-danger">
                                       <strong>{{ $message }}</strong> 
                                    </div>
                                 </div>
                              @endif

                           @include('admin.accounts.sendlinksms.menu')
                           <div class="col-sm-12 ">
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-6">
                                       <h4 class="card-title mb-1 mt-3">Send Link Sms</h4>
                                       <p class="pb-border"></p>
                                    </div>
                                    <div class="col-md-6 mt-3 text-right">

                                       {{-- <a href="" class="mt-3 btn btn-sm btn-primary">Payment Method</a> --}}

                                       <div class="btn-group" style="float:right">     
                                          <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>
                                          <span><a class="btn btn-success add_new_send_link_btn" href="#" > <i class="fa fa-plus"></i> Add New </a></span> 
                                       </div>
                                    </div>
                                 </div>
                                 <div class="search-drop-field" id="search-drop">
                                    <div class="row">
                                       <div class="col-md-6 form-group mb-1 level_selector">
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
                                          @include('admin.accounts.sendlinksms.ajax')        
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

<div class="modal" id="add_send_link">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <div class="row">
               <div class="col-11">
                 <h4 class="modal-title">Add Send link sms follw candiadte Wise </h4>
               </div>
               <div class="col-1">
                 <button type="button" class="close btn-disable" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
               </div>
            </div>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{url('/settings/sendlinks/store')}}" id="send_link">
         @csrf
            <div class="modal-body">
            <div class="form-group">
               <label for="label_name">Customer Name: <span class="text-danger">*</span></label><br>
               <select class="form-control customer" name="customer" id="customer">
                 <option value="">-- Select-- </option>
                 @foreach($customers as $customer)
                   <option value="{{ $customer->id }}"> {{ $customer->company_name.' - '.$customer->first_name}} </option>
                 @endforeach
               </select>
               <p style="margin-bottom: 2px;" class="text-danger error-container error-customer" id="error-customer"></p> 
            </div>
            <div class="form-group">
               <label for="label_name">No of days :</label>
               <input type="text" id="no_of_days" name="no_of_days" class="form-control no_of_days" placeholder="Enter no of days"/>
               <p style="margin-bottom: 2px;" class="text-danger error-container error-no_of_days" id="error-no_of_days"></p> 
            </div>

            <div class="form-group">
               <label for="label_name">No of Follow Up:</label>
               <input type="text" id="days_follow_up" name="days_follow_up" class="form-control days_follow_up" placeholder="Enter days follow up"/>
               <p style="margin-bottom: 2px;" class="text-danger error-container error-days_follow_up" id="error-days_follow_up"></p> 
           </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-info btn-disable">Submit </button>
               <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
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

      //modal show add new send link
      $('.add_new_send_link_btn').click(function(){
         $("#send_link")[0].reset();
         $('.form-control').removeClass('border-danger');
         $('.error-container').html('');
         $('.btn-disable').attr('disabled',false);
         $('#add_send_link').modal({
                backdrop: 'static',
                keyboard: false
         });
       });
       // store send link
       $(document).on('submit', 'form#send_link', function (event) {
        
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

    //when click on hide button
    $(document).on('click', '.hold', function (event) {
        
        var customer_id = $(this).attr('data-customer');
        if(confirm("Are you sure want to disable verification of this COC ?")){
        $.ajax({
            type:'GET',
            url: "{{route('/check/customer_wise/hide')}}",
            data: {'customer_id':customer_id},        
            success: function (response) {        
            console.log(response);
            
                if (response.status=='ok') {            
                
                    $('table.customerTable tr').find("[data-customer='" + customer_id + "']").fadeOut("slow");
                    
                    $('table.customerTable tr').find("[data-cust_id='" + customer_id + "']").fadeOut("slow");
                    $('table.customerTable tr').find("[data-cus_id='" + customer_id + "']").removeClass("d-none").show();
                    $('table.customerTable tr').find("[data-customer_id='" + customer_id + "']").removeClass("d-none").show();
                    

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

    //when click on show button
    $(document).on('click', '.status', function (event) {

         var id = $(this).attr('data-id');
         var type =$(this).attr('data-type');
         //  alert(user_id);
         if(confirm("Are you sure want to change the status ?")){
         $.ajax({
               type:'POST',
               url: "{{ url('/')}}"+"/settings/send/links/status",
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
                           $('table.customerTable tr').find("[data-ac='" + id + "']").fadeIn("slow");
                           $('table.customerTable tr').find("[data-ac='" + id + "']").removeClass("d-none");

                           $('table.customerTable tr').find("[data-dc='" + id + "']").fadeOut("slow");

                           $('table.customerTable tr').find("[data-dc='" + id + "']").addClass("d-none");

                           $('table.customerTable tr').find("[data-a='" + id + "']").fadeOut("slow");
                           $('table.customerTable tr').find("[data-a='" + id + "']").addClass("d-none");

                           $('table.customerTable tr').find("[data-d='" + id + "']").fadeIn("slow");

                           $('table.customerTable tr').find("[data-d='" + id + "']").removeClass("d-none");

                           
                     }
                     else if(response.type=='deactive')
                     {
                           $('table.customerTable tr').find("[data-dc='" + id + "']").fadeIn("slow");
                           $('table.customerTable tr').find("[data-dc='" + id + "']").removeClass("d-none");

                           $('table.customerTable tr').find("[data-ac='" + id + "']").fadeOut("slow");

                           $('table.customerTable tr').find("[data-ac='" + id + "']").addClass("d-none");

                           $('table.customerTable tr').find("[data-d='" + id + "']").fadeOut("slow");
                           $('table.customerTable tr').find("[data-d='" + id + "']").addClass("d-none");

                           $('table.customerTable tr').find("[data-a='" + id + "']").fadeIn("slow");

                           $('table.customerTable tr').find("[data-a='" + id + "']").removeClass("d-none");
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
    
</script>
                     
 
@endsection
