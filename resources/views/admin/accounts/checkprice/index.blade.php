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
             <li>Check Price</li>
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
                            @include('admin.accounts.checkprice.menu')
                           <div class="col-sm-12">
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-6">
                                       <h4 class="card-title mb-1 mt-3">Check Price </h4>
                                       <p class="pb-border"></p>
                                    </div>
                                    @if(count($items)>0)
                                       <div class="col-md-6 mt-3 text-right">
                                          <div class="btn-group" style="float:right">     
                                             <a href="#" class="filter0search"><i class="fa fa-filter"></i></a> 
                                                <a class="btn btn-success add_new_price_btn" href="#" > <i class="fa fa-plus"></i> Add New </a>
                                          </div>
                                       </div>
                                    @endif
                                 </div>
                                 <div class="search-drop-field" id="search-drop">
                                    <div class="row">
                                       <div class="col-md-3 form-group mb-1 level_selector">
                                          <label>Service Name</label><br>
                                          <select class="form-control service_list select " name="service_name" id="service_name">
                                             <option> All </option>
                                             @foreach($services as $service)
                                                <option value="{{ $service->id }}"> {{ $service->name }} </option>
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
                                           @include('admin.accounts.checkprice.ajax')        
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
<div class="modal" id="add_custom_price">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">Add Customer Price</h4>
            <button type="button" class="close close_btn" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{url('/checkprice/customer/store')}}" id="checkpriceadd">
         @csrf
            <div class="modal-body">
               <label for="label_name"> Service Name : <span class="text-danger">*</span></label>
               <div class="form-group">
                     @foreach($services as $service)
                        <div class="form-check form-check-inline">
                           <input class="form-check-input services" type="checkbox" name="services[]" value="{{ $service->id}}" data-string="{{ $service->name  }}" id="inlineCheckbox-{{ $service->id}}">
                           <label class="form-check-label" for="inlineCheckbox-{{ $service->id}}">{{ $service->name  }}</label>
                        </div>
                     @endforeach
               </div>
               <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-services"></p> 
               <div class="form-group">
                     <label for="label_name">Price : <span class="text-danger">*</span></label>
                     <input type="text" name="new_price" class="form-control new_price" placeholder="Enter Price"/>
                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-new_price"></p> 
               </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-info">Submit </button>
               <button type="button" class="btn btn-danger close_btn" data-dismiss="modal">Close</button>
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

       $('.add_new_price_btn').click(function(){
         $('#add_custom_price').modal({
                backdrop: 'static',
                keyboard: false
         });
      });

      $(document).on('submit', 'form#checkpriceadd', function (event) {
        
        $("#overlay").fadeIn(300);　
        event.preventDefault();
        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        var $btn = $(this);
        $('.error-container').html('');
        $('.form-control').removeClass('border-danger');
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
                  if (data.fail && data.error_type == 'validation') {
                        for (control in data.errors) {
                           $('.'+control).addClass('border-danger'); 
                           $('#error-' + control).text(data.errors[control]);
                        }
                  } 
                  if (data.fail && data.error == 'yes') {
                     
                     $('#error-all').html(data.message);
                  }
                  if (data.fail == false) {
                     toastr.success("Price Added Successfully");
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

      $(document).on('click','.close_btn',function(){
         $('#error-services').html("");
         $('#error-new_price').html("");
         $('#error-price').html("");
         $('.new_price').val("");
         $('.services').each(function() {
            this.checked = false;
         });
         $('.form-control').removeClass("border-danger");
      });
       var uriNum = location.hash;
       pageNumber = uriNum.replace("#", "");
       // alert(pageNumber);
       getData(pageNumber);

       $(document).on('change','.service_list', function (e){    
            $("#overlay").fadeIn(300);　
            getData(0);
            e.preventDefault();
      });
 
       $(document).on('click','.filterBtn', function (e){    
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
         // var user_id     =    $(".customer_list").val();                
         var service_id     =    $(".service_list").val();
       //   var from_date   =    $(".from_date").val(); 
       //   var to_date     =    $(".to_date").val();      
 
             $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
 
             $.ajax(
             {
                 url: '?page=' + page+'&service_id='+service_id,
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

      //   var user_id     =    $(".customer_list").val();   
        var service_id     =    $(".service_list").val();             
        //   var check       =    $(".check option:selected").val();

        //   var from_date   =    $(".from_date").val(); 
        //   var to_date     =    $(".to_date").val();    
        
            $.ajax(
            {
                url: "{{ url('/') }}"+'/candidates/setData/?service_id='+service_id,
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
