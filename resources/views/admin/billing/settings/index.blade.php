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
                                       <h4 class="card-title mb-1 mt-3">Billing Setting  </h4>
                                       <p class="pb-border">Setting for Billing Cycle Period</p>
                                    </div>
                                    <div class="col-md-6 mt-3 text-right">

                                       {{-- <a href="" class="mt-3 btn btn-sm btn-primary">Payment Method</a> --}}
                                       <div class="btn-group" style="float:right">
                                         @if(count($items)>0)     
                                            <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>
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
                                       </div>
                                       <div class="col-md-2">
                                          <button class="btn btn-danger resetBtn" style="width:100%;padding: 7px;margin: 18px 0px;"> <i class="fas fa-refresh"></i> Reset </button>
                                       </div>
                                       <div class="col-md-2">
                                        <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                       </div>
                                   </div>
                                 </div>
                                 
                                 <div class="row">
                                    <div class="col-md-12 pt-3">
                                       <div id="candidatesResult">
                                          @include('admin.billing.settings.ajax')        
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

<div class="modal" id="edit_bill_mdl">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Edit Customer Billing Cycle</h4>
             <button type="button" class="close btn_check" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
          </div>
          <!-- Modal body -->
          <form method="post" action="{{url('/billing/settings/edit')}}" id="bill_cycle_frm">
          @csrf
            <input type="hidden" name="id" class="id" id="id">
             <div class="modal-body">
             {{-- <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p>  --}}
             <div class="form-group">
                <label for="label_name"> Customer Name :</label>
                <span style="margin-bottom: 2px;" class="text-dark" id="cust_name"></span> 
             </div>
             <div class="form-group">
                <label for="label_name"> Cycle Period : <span class="text-danger">*</span></label>
                <select class="form-control cycle_period" name="cycle_period">
                    <option value="">--Select--</option>
                    <option value="half_monthly">15 Days</option>
                    <option value="monthly">Monthly</option>
                </select>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="submit" class="btn btn-info btn_check btn_submit">Update </button>
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

      $('.filter0search').click(function(){
         $('.search-drop-field').toggle();
      });

      $('.filter_close').click(function(){
         $('.search-drop-field').toggle();
      });

      $(document).on('click','.editBillCycle',function(){

        var _this = $(this);
        var id = _this.attr('data-id');
         $("#bill_cycle_frm")[0].reset();
         $('.form-control').removeClass('border-danger');
         $('.error-container').html('');

         $.ajax({
            type: 'GET',
            url: "{{ url('/billing/settings/edit') }}",
            data: {'id':id},        
            success: function (data) {
                    //   console.log(data);
                  // $("#service_update")[0].reset();
                  if(data !='null')
                  {             
                     //check if primary data 
                     $('.id').val(id);

                     $('#cust_name').html(data.result.company_name+' - '+data.result.name);
                  
                     $(".cycle_period option[value= '"+data.result.billing_cycle_period+"']").attr("selected", "selected");

                     $('#edit_bill_mdl').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                     
                  }
            },
            error: function (xhr, textStatus, errorThrown) {
                //   alert("Error: " + errorThrown);
            }
         });
         
      });

      $(document).on('submit', 'form#bill_cycle_frm', function (event) {
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
                //   console.log(data);
                  //  $('.error-container').html('');
                  window.setTimeout(function(){
                    $('.btn_check').attr('disabled',false);
                    $('.btn_submit').html('Update');
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
                     toastr.success("Record Updated Successfully");
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
</script>
                     
 
@endsection
