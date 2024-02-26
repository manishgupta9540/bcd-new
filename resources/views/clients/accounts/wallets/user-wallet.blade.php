@extends('layouts.client')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">

         <!-- ============Breadcrumb ============= -->
   <div class="row">
      <div class="col-sm-11">
         <ul class="breadcrumb">
         <li>
         <a href="{{ url('/my/home') }}">Dashboard</a>
         </li>
         <li>Wallet</li>
         </ul>
      </div>
      <!-- ============Back Button ============= -->
      <div class="col-sm-1 back-arrow">
         <div class="text-right">
            <a href="{{ url()->previous() }}"> <i class="fas fa-arrow-circle-left fa-2x"></i></a>
         </div>
      </div>
   </div>   
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Accounts/Wallet </h3>
               </div>
            </div>
         </div>
      </div> --}}
      <div class="row">
         @php
         
         $ADD_ACCESS   = false;
        
         $ADD_ACCESS   = Helper::can_access('Add Money','/my');
      @endphp
            <div class="col-md-3 content-container">
                  <!-- left-sidebar -->
                  @include('clients.accounts.sidebar') 
               </div>
                  <!-- start right sec -->
                  <div class="col-md-9 content-wrapper" style="background:#fff">
                     <div class="formCover" style="height: 100vh;">
                        <!-- section -->
                        <section>
                           <div class="col-sm-12 ">
                                 <h2 class="text-center pt-3">Wallet</h2>
                                 <p class="pb-border"></p>
                                 <!-- row -->
                                 <div class="row">
                                     <div class="col-12 px-0">
                                        <div class="container d-flex justify-content-center pt-2">
                                            <div class="card p-3">
                                                {{-- <div class="d-flex flex-row justify-content-between text-align-center"> <img src="{{url('/').'/uploads/company-logo/techsaga.png'}}"></div> --}}
                                                {{-- <p class="text-dark px-3">{{Helper::user_name(Auth::user()->business_id)}}</p> --}}
                                                <div class="card-bottom pt-3 px-3 mb-2">
                                                    <div class="d-flex flex-row justify-content-between text-align-center">
                                                        <div class="d-flex flex-column"><span>Available amount</span>
                                                            <h4><i class="fas fa-rupee-sign"></i> <b><span class="text-dark">{{$wallet!=NULL?$wallet->balance:'0.00'}}</span></b></h4>
                                                        </div> 
                                                        @if ($ADD_ACCESS)
                                                            <button class="btn btn-outline-info add_money" title="Add Money"><i class="fas fa-wallet"></i> Add Money</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                     </div>
                                 </div>
                                 <div class="row pt-3">
                                    <div class="col-md-4">
                                       {{-- <h4 class="card-title mb-1 mt-3">Transaction History</h4> --}}
                                       <p class="pb-border"> Your Tranction History overview </p>
                                    </div>
                                    <div class="col-md-8">
                                       <!-- <a href="" class="mt-3 btn btn-sm btn-primary">Payment Method</a> -->
                                       <div class="btn-group py-3" style="float: right;">
                                          @if(count($wallet_transactions)>0)     
                                             <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>   
                                          @endif
                                       </div>
                                    </div>
                                 </div>

                                 <!-- search bar -->
                                 <div class="search-drop-field" id="search-drop" style="margin-top: 150px;">
                                    <div class="row">
                                       <div class="col-md-3 form-group mb-1">
                                          <label> From date </label>
                                          <input class="form-control from_date commonDatePicker" type="text" placeholder="From date">
                                       </div>
                                       <div class="col-md-3 form-group mb-1">
                                          <label> To date </label>
                                          <input class="form-control to_date commonDatePicker" type="text" placeholder="To date">
                                       </div>
                                       <div class="col-md-3 form-group mb-1">
                                          <label>Transaction ID</label><br>
                                          <input class="form-control t_id" name="transaction_id" type="text" placeholder="Transaction ID">
                                          
                                          {{-- <input class="form-control candidate_list" type="text" placeholder="name"> --}}
                                       </div>
                                       <div class="col-md-2">
                                       <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                       </div>
                                    </div>
                                 </div>
                                
                                 <div id="candidatesResult">
                                    @include('clients.accounts.wallets.user-wallet_ajax')
                                 </div>
                                 <!-- ./billing detail -->
                                 
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

<div class="modal pt-5" id="add_money">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">Add Money to Wallet</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{url('/my/wallet/add-money')}}" id="add_money_form">
         @csrf
            <div class="modal-body">
               <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
               <div class="form-group">
                  <label for="label_name"> Available Balance : <i class="fas fa-rupee-sign"></i>  {{$wallet!=NULL?$wallet->balance:'0.00'}}</label>
               </div>
               <div class="form-group">
                  <label for="label_name"> Amount  </label>
                  <input type="text" id="amount " name="amount" class="form-control amount @error('amount') is-invalid @enderror" placeholder="Enter Amount" autocomplete="off"/>
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-amount"></p> 
                  {{-- @error('amount')
                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-amount">{{$message}}</p>
                  @enderror --}}
               </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-info ">Proceed </button>
               <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>

@stack('scripts')

<script type="text/javascript">
   //
$(document).ready(function() {
   $(".select").select2();
   $('.filter0search').click(function(){
            $('.search-drop-field').toggle();
    });
    $('.t_id').val("");
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
      var uriNum = location.hash;
      pageNumber = uriNum.replace("#", "");
      // alert(pageNumber);
      getData(pageNumber);

       // filterBtn
      $(document).on('change','.t_id, .from_date, .to_date', function (e){    
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

      $(document).on('click',".add_money",function(){
         $("#add_money").fadeIn(600);　
            $('#add_money').modal({
                     backdrop: 'static',
                     keyboard: false
            });
      });

      $(document).on('submit','form#add_money_form',function(){

         $("#overlay").fadeIn(300);　
         event.preventDefault();
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var $btn = $(this);
         $('.error-container').html('');
         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
               console.log(data.amount);
               $('.error-container').html('');
               if (data.fail && data.error_type == 'validation') {
                     
                     for (control in data.errors) {
                        $('input[amount=' + control + ']').addClass('is-invalid');
                        $('#error-' + control).html(data.errors[control]);
                     }
               } 
               
               if (data.fail == false) {
                  console.log(data.response);
                  window.location="{{url('/')}}"+"/my/wallet/payment-page/"+data.order_id;
                  
               }
            },
            error: function (data) {
            
               console.log(data);

            }
         });
         
      });

});

   function getData(page){
      //set data
      // var user_id     =    $(".customer_list").val();                
      // var check       =    $(".check option:selected").val();
      // var type        =    $('#check_p').val();

      var from_date   =    $(".from_date").val(); 
      var to_date     =    $(".to_date").val();      
      var wt_id=    $(".t_id").val();

     // var service_id=    $(".service_list option:selected").val();

      // var mob = $('.mob').val();
      // var ref = $('.ref').val();
      // var email = $('.email').val();  
      
      
      

      // var candidate_arr = [];
      // var i = 0;
      

      // $('.check option:selected').each(function () {
      //     // if($(this).val()!='')
      //     candidate_arr[i++] = $(this).val();
      // });    

         $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);

         $.ajax(
         {
               url: '?page=' + page+'&from_date='+from_date+'&to_date='+to_date+'&t_id='+wt_id,
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
        // var check       =    $(".check option:selected").val();

        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();    
        var wt_id=    $(".t_id").val();
      //   var service_id=    $(".service_list option:selected").val();
        // var mob = $('.mob').val();
        // var ref = $('.ref').val();

        // var email = $('.email').val(); 

       
        // var candidate_arr = [];
        // var i = 0;
        

        // $('.check option:selected').each(function () {
        //     // if($(this).val()!='')
        //     candidate_arr[i++] = $(this).val();
        // });

        // alert(candidate_arr);
        
            $.ajax(
            {
                url: "{{ url('/') }}"+'/candidates/setData/?from_date='+from_date+'&to_date='+to_date+'&t_id='+wt_id,
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

