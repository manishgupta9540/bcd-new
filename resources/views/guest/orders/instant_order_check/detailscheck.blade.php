@extends('layouts.guest')
@section('content')

{{-- <div class="main-content-wrap sidenav-open d-flex flex-column">
    <!-- ============ Body content start ============= -->
    <div class="main-content"> 
         <div class="row"> --}}
             {{-- <div class="col-sm-11">
                 <ul class="breadcrumb">
                 <li>
                 <a href="{{ url('/verify/home') }}">Dashboard</a>
                 </li>
                 <li><a href="{{ url('/verify/instantverification/orders') }}">Orders</a></li>
                 <li>Details</li>
                 </ul>
             </div>
             <!-- ============Back Button ============= -->
             <div class="col-sm-1 back-arrow">
                 <div class="text-right">
                 <a href="{{url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
                 </div>
             </div>
         </div> --}}
         {{-- <div class="row"> --}}
             <div class="col-md-11 card">
                <div class="card-body">  
             
                     <div class="row">
 
                         @if ($message = Session::get('success'))
                         <div class="col-md-12">   
                             <div class="alert alert-success">
                             <strong>{{ $message }}</strong> 
                             </div> 
                         </div>
                         @endif 
 
                         <div class="col-md-4">
                             <h4 class="card-title mb-1"> Orders Details</h4> 
                             <p> List of all Orders </p>        
                         </div>
                         {{-- <div class="col-md-3">
                             <span>Total Candidates: <span > {{ $tota_candidates }}</span> </span>
                         </div> --}}
                         <div class="col-md-8">           
                         <div class="btn-group" style="float:right">
                            @if(count($items)>0)     
                             <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>   
                            @endif              
    
                             {{-- <a class="btn btn-success " href="{{ url('/candidates/create')}}" > <i class="fa fa-plus"></i> Add New </a>               --}}
                         </div>
                         </div>
                     </div>
                         <!-- search bar -->
                         <div class="search-drop-field" id="search-drop">
                             <div class="row">
                                 <div class="col-md-2 form-group mb-1">
                                     <label> From date </label>
                                     <input class="form-control from_date commonDatepicker" type="text" placeholder="From date">
                                     <i class="date-icon fa fa-calendar" aria-hidden="true"></i>
                                 </div>
                                 <div class="col-md-2 form-group mb-1">
                                     <label> To date </label>
                                     <input class="form-control to_date commonDatepicker2" type="text" placeholder="To date">
                                     <i class="date-icon2 fa fa-calendar" aria-hidden="true"></i>
                                 </div>
                                 <div class="col-md-2">
                                    <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                 </div>
                             </div>
                         </div>
                         

                         <!-- data  -->
                         <div id="candidatesResult">
                             @include('guest.orders.instant_order.details_ajax')   
                         </div> 
                         <!--  -->
                    </div>
              </div>
         {{-- </div> --}}
    {{-- </div>
 </div> --}}

 <div class="modal" id="order_data_modal">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          
       </div>
    </div>
 </div>

 <script>
$(document).ready(function(){
    // $(".select").select2();
    $(".select1").select2();
    $('.filter0search').click(function(){
            $('.search-drop-field').toggle();
    });

    //
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


    // send report on your email id
    $(document).on('click','.resenddetails',function(){
        var _this = $(this);
        var details_id = $(this).attr('data-id');
        
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
        if (_this.html() !== loadingText) {
               _this.html(loadingText);
         }
       // alert(reports_id);
        $.ajax({
               type: 'GET', 
               url:"{{ url('/verify/instantverification/resendreportdetails') }}",
               data: {'details_id':details_id},  
               success: function (response) {
               if(response.success==true) {  
                    _this.html('Resend Report');
                     toastr.success("Re-send report send on your registered mail");
               }
               //show the form validates error
               if(response.success==false ) {                              
                     for (control in response.errors) {   
                        $('#error-'+control).html(response.errors[control]);
                     }
               }
            },
            
        });
    });
    
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
    //
    var uriNum = location.hash;
    pageNumber = uriNum.replace("#", "");
    // alert(pageNumber);
    getData(pageNumber);

    // filterBtn
    $(document).on('change','.from_date, .to_date,.service_list,.o_id', function (e){    
        $("#overlay").fadeIn(300);　
        getData(0);
        e.preventDefault();
    });

    $(document).on('click','.filterBtn', function (e){    
        $("#overlay").fadeIn(300);　
        getData(0);
        e.preventDefault();
    });

    // 
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

    $(document).on('click','.detailBtn',function(){
        $('#g_id').val("");
        $('#service_name').text('Verification - '+"");
        var g_id = $(this).attr('data-id');
        // var notes= $(this).attr('data-notes');
        // alert(servi_name);

        $.ajax({
            type:'POST',
            url: "{{url('/verify/instantverification/orders/details/data')}}",
            data: {"_token": "{{ csrf_token() }}",'g_id':g_id},        
            success: function (response) {        
            // console.log(response);
            $('.modal-content').html(response.modal);
            $('#g_id').val(g_id);
            var service_name = response.data.name;
            if(service_name.toLowerCase()=='Driving'.toLowerCase())
            {
                service_name = 'Driving License';
            }
            $('#service_name').text('Verification - '+service_name);
            $('#order_details').html(response.form);

            if(response.data.status=='success')
            {
                $('.modal-footer').html(`<button type="button" class="btn btn-danger closeraisemdl" data-dismiss="modal">Close</button>`);
            }
            else
            {
                if(response.data.refund_count>=3)
                {
                    $('.modal-footer').html(`<button type="button" class="btn btn-danger closeraisemdl" data-dismiss="modal">Close</button>`);
                }
                else
                {
                    $('.modal-footer').html(`<button type="submit" class="btn btn-info order-submit btn-com">Submit </button>
                                        <button type="button" class="btn btn-danger btn-com" data-dismiss="modal">Close</button>`);
                    $('#order_data_edit')[0].reset();
                    $('.form-control').removeClass('is-invalid');
                    $('.error_container').html('');
                }
                
            }
            $('#order_data_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
            // if (response.status=='ok') {            
            
            
            // } else {

            //    alert('No data found');

            // }
            },
            error: function (xhr, textStatus, errorThrown) {
            //  alert("Error: " + errorThrown);
            }
        });

    });

    $(document).on('submit', 'form#order_data_edit', function (event) {
        $("#overlay").fadeIn(300);　
        event.preventDefault();
        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        var $btn = $(this);
        $('.error_container').html('');
        $('.form-control').removeClass('is-invalid');
        $('.btn-com').attr('disabled',true);
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
        if($('.order-submit').html!=loadingText)
        {
            $('.order-submit').html(loadingText);
        }
        $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response);
            //    $('.error-container').html('');
                window.setTimeout(function(){
                    $('.btn-com').attr('disabled',false);
                    $('.order-submit').html('Submit');
                },2000);
                if (response.success==false) {
                        //$("#overlay").fadeOut(300);
                        for (control in response.errors) {
                            // $('textarea[comment=' + control + ']').addClass('is-invalid');
                            // $('.'+control).addClass('is-invalid');
                            $('input[name=' + control + ']').addClass('is-invalid');
                            $('select[name=' + control + ']').addClass('is-invalid');
                            $('#error-' + control).html(response.errors[control]);
                        }
                } 

                if (response.success) {

                    if(response.refund_count >=3)
                    {
                        toastr.success("Your Refund Request Has Been Sent To System Administrator !!");
                    }
                    toastr.success("Record Updated Successfully");
                    // redirect to google after 5 seconds
                    window.setTimeout(function() {
                        location.reload(); 
                    }, 2000);
                    
                }
                if(response.success && response.status=='error'){
                    toastr.error("Something Went Wrong!!");
                }
            },
            error: function (response) {
                
                console.log(response);

            }
            // error: function (xhr, textStatus, errorThrown) {
                
            //       alert("Error: " + errorThrown);

            // }
        });
        return false;
    });


   
});

    function getData(page){
        //set data
        // var user_id     =    $(".customer_list").val();                
        // var check       =    $(".check option:selected").val();
        // var type        =    $('#check_p').val();

        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();      
        // var candidate_id=    $(".candidate_list option:selected").val();

        var service_id=    $(".service_list option:selected").val();

        var order_id   =    $(".o_id").val(); 
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
                url: '?page=' + page+'&from_date='+from_date+'&to_date='+to_date+'&service_id='+service_id+'&order_id='+order_id,
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
        // var candidate_id=    $(".candidate_list option:selected").val();                            
        var service_id=    $(".service_list option:selected").val();
        // var mob = $('.mob').val();
        // var ref = $('.ref').val();
        var order_id   =    $(".o_id").val(); 
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
                url: "{{ url('/') }}"+'/candidates/setData/?from_date='+from_date+'&to_date='+to_date+'&service_id='+service_id+'&order_id='+order_id,
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