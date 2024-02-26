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
             <li>Billing</li>
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
               {{-- <div class="col-md-3 content-container">
                  <!-- left-sidebar -->
                  @include('admin.accounts.left-sidebar') 
               </div> --}}
                  <!-- start right sec -->
                  <div class="col-md-12 content-wrapper" style="background:#fff">
                     <div class="formCover py-2" style="height: 100vh;">
                        <!-- section -->
                        <section>
                            @include('admin.billing.menu')
                           <div class="col-sm-12 ">
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-6">
                                       <h4 class="card-title mb-1 mt-3">Billing Config  </h4>
                                       <p class="pb-border">Config for Customer Wise Incentives & Penalties</p>
                                    </div>
                                    @php
                                       $ADD_ACCESS    = false;
                                       
                                       $ADD_ACCESS    = Helper::can_access('Create Billing Config','');
                                    @endphp
                                    <div class="col-md-6 mt-3 text-right">

                                       {{-- <a href="" class="mt-3 btn btn-sm btn-primary">Payment Method</a> --}}
                                       <div class="btn-group" style="float:right">
                                        @if(count($items)>0)     
                                          <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>
                                          @endif 
                                             @if ($ADD_ACCESS)
                                                <a class="btn btn-success add_new_inc_btn" href="#" > <i class="fa fa-plus"></i> Add New </a>
                                             @endif
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
                                       <div class="col-md-3 form-group mb-1 level_selector">
                                          <label>Customer Name</label><br>
                                          <select class="form-control customer_list select " name="customer_name" id="customer_name">
                                                <option> All </option>
                                                @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}"> {{ $customer->company_name.' - '.$customer->name}} </option>
                                                @endforeach
                                          </select>
                                          {{-- <input class="form-control candidate_list" type="text" placeholder="name"> --}}
                                       </div>
                                       <div class="col-md-3 form-group mb-1 level_selector">
                                          <label>Service Name</label><br>
                                          <select class="form-control service_list select1 " name="service_name" id="service_name">
                                             <option> All </option>
                                             @foreach($services as $service)
                                                <option value="{{ $service->id }}"> {{ $service->name }} </option>
                                             @endforeach
                                          </select>
                                          {{-- <input class="form-control candidate_list" type="text" placeholder="name"> --}}
                                       </div>
                                       <div class="col-md-2">
                                          <button class="btn btn-danger resetBtn" style="width:100%;padding: 7px;margin: 18px 0px;"> <i class="fas fa-refresh"></i>  Reset </button>
                                       </div>
                                          <div class="col-md-2">
                                          <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                          </div>
                                    </div>
                                 </div>
                                 
                                 <div class="row">
                                    <div class="col-md-12 pt-3">
                                       <div id="candidatesResult">
                                          @include('admin.billing.config.ajax')        
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

<div class="modal" id="add_custom_inc">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">Add Customer Wise Incentive and Penalty</h4>
            <button type="button" class="close btn_check" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{url('/billing/config/cocwise/store')}}" id="checkincadd">
         @csrf
            <div class="modal-body">
            <div class="form-group">
               <label for="label_name"> Customer Name : <span class="text-danger">*</span></label>
               <select class="form-control customer" name="customer" id="customer">
                  <option value=""> - Select -</option>
                  @foreach($customers as $customer)
                    <option value="{{ $customer->id }}"> {{ $customer->company_name.' - '.$customer->first_name}} </option>
                  @endforeach
              </select>
              <p style="margin-bottom: 2px;" class="text-danger error-container error-customer" id="error-customer"></p> 
            </div>
            <div class="form-group">
               <label for="label_name"> Service Name : <span class="text-danger">*</span></label><br>
               @foreach($services as $service)
                  <div class="form-check form-check-inline">
                     <input class="form-check-input services" type="checkbox" name="services[]" value="{{ $service->id}}" data-string="{{ $service->name  }}" id="inlineCheckbox-{{ $service->id}}">
                     <label class="form-check-label" for="inlineCheckbox-{{ $service->id}}">{{ $service->name  }}</label>
                  </div>
               @endforeach
              <p style="margin-bottom: 2px;" class="text-danger error-container error-services" id="error-services"></p> 
            </div>
           <div class="form-group">
               <label for="label_name">Incentive <small>(in %)</small> : <span class="text-danger">*</span></label>
               <input type="text" name="incentive" class="form-control incentive" placeholder="Enter Incentive"/>
               <p style="margin-bottom: 2px;" class="text-danger error-container error-incentive" id="error-incentive"></p> 
           </div>
           <div class="form-group">
                <label for="label_name">Penalty <small>(in %)</small> : <span class="text-danger">*</span></label>
                <input type="text" name="penalty" class="form-control penalty" placeholder="Enter Penalty"/>
                <p style="margin-bottom: 2px;" class="text-danger error-container error-penalty" id="error-penalty"></p> 
            </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-info btn_check btn_submit">Submit</button>
               <button type="button" class="btn btn-danger btn_check" data-dismiss="modal">Close</button>
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
      $(".select1").select2();

      $('.filter0search').click(function(){
         $('.search-drop-field').toggle();
      });
      $('.filter_close').click(function(){
         $('.search-drop-field').toggle();
      });

      $('.add_new_inc_btn').click(function(){
         $("#checkincadd")[0].reset();
         $('.form-control').removeClass('border-danger');
         $('.error-container').html('');
         $('#checkincadd')[0].reset();
         $('#add_custom_inc').modal({
                backdrop: 'static',
                keyboard: false
         });
      });

      $(document).on('submit', 'form#checkincadd', function (event) {
        $("#overlay").fadeIn(300);　
        event.preventDefault();
        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        var $btn = $(this);
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
        $('.error-container').html('');
        $('.form-control').removeClass('border-danger');
        $('.btn_check').attr('disabled',true);
        if ($('.btn_submit').html() !== loadingText) {
            $('.btn_submit').html(loadingText);
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
                  //  $('.error-container').html('');
                  window.setTimeout(function(){
                    $('.btn_check').attr('disabled',false);
                    $('.btn_submit').html('Submit');
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
               // error: function (xhr, textStatus, errorThrown) {
                  
               //    alert("Error: " + errorThrown);

               // }
         });
        event.stopImmediatePropagation();
        return false;

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

      $(document).on('change','.customer_list,.service_list', function (e){    
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

      $(document).on('click', '.resetBtn' ,function(){

         $('.customer_list').val(null).trigger('change');

         $('.service_list').val(null).trigger('change');

         var uriNum = location.hash;
         pageNumber = uriNum.replace("#","");
         // alert(pageNumber);
         getData(pageNumber);
      });
   
   });
      function getData(page){
         //set data
         var user_id     =    $(".customer_list").val();                
         var service_id     =    $(".service_list").val();                

         //   var from_date   =    $(".from_date").val(); 
         //   var to_date     =    $(".to_date").val();      

               $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);

               $.ajax(
               {
                  url: '?page=' + page+'&customer_id='+user_id+'&service_id='+service_id,
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
         var service_id     =    $(".service_list").val();         
               $.ajax(
               {
                  url: "{{ url('/') }}"+'/candidates/setData/?customer_id='+user_id+'&service_id='+service_id,
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
