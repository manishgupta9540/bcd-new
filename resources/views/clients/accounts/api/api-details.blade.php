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
         <li>
            <a href="{{ url('/my/api-usage') }}">Instant Verification</a>
        </li>
         <li>Details</li>
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
                  <h3 class="page-title">Accounts/Instant Verification/Details </h3>
               </div>
            </div>
         </div>
      </div> --}}
      <div class="row">
         
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
                              
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-6">
                                       <h4 class="card-title mb-1 mt-3">{{$service_d->name}}</h4>
                                       <p class="pb-border"> Usage Details </p>
                                    </div>
                                    <div class="col-md-6 mt-3 text-right">
                                       <!-- <a href="" class="mt-3 btn btn-sm btn-primary">Payment Method</a> -->
                                       <div class="btn-group" style="float:right">     
                                            <a href="#" class="filter0search"><i class="fa fa-filter"></i></a> 
                                        </div>
                                    </div>
                                 </div>
                                 <div class="search-drop-field" id="search-drop">
                                    <div class="row">
                                        <div class="col-md-3 form-group mb-1">
                                            <label> From date </label>
                                            <input class="form-control from_date commonDatePicker" type="text" placeholder="From date">
                                        </div>
                                        <div class="col-md-3 form-group mb-1">
                                            <label> To date </label>
                                            <input class="form-control to_date commonDatePicker" type="text" placeholder="To date">
                                        </div>
                                        <div class="col-md-3 form-group mb-1 level_selector">
                                            <label>Duration</label><br>
                                            <select class="form-control duration" name="duration" id="duration">
                                                <option value=""> All </option>
                                                <option value="{{base64_encode('seven')}}" selected>Last 7 days</option>
                                            </select>
                                            {{-- <input class="form-control candidate_list" type="text" placeholder="name"> --}}
                                        </div>
                                       <div class="col-md-2">
                                            <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                       </div>
                                   </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-12">
                                        <div id="candidatesResult">
                                            @include('clients.accounts.api.api-details_ajax')  
                                        </div>
                                    </div>
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
@stack('scripts')
<script type="text/javascript">
   //
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

        $(document).on('change','.from_date, .to_date,.duration', function (e){    
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
            //  var service_id     =    $(".service_list").val();                

              var from_date   =    $(".from_date").val(); 
              var to_date     =    $(".to_date").val();  

              var date=$(".duration").val();    

            $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);

            $.ajax(
            {
                url: '?page=' + page+'&from_date='+from_date+'&to_date='+to_date+'&date='+date,
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

        //  var user_id     =    $(".customer_list").val();                
            //   var check       =    $(".check option:selected").val();

            var from_date   =    $(".from_date").val(); 
            var to_date     =    $(".to_date").val();  
            var date= $(".duration").val();  
        //  var service_id     =    $(".service_list").val();         
            $.ajax(
            {
                url: "{{ url('/') }}"+'/candidates/setData/?from_date='+from_date+'&to_date='+to_date+'&date='+date,
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
