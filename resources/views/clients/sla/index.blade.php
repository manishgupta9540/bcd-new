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
                <li>SLA</li>
                </ul>
            </div>
            <!-- ============Back Button ============= -->
            <div class="col-sm-1 back-arrow">
                <div class="text-right">
                <a href="{{ url()->previous() }}"> <i class="fas fa-arrow-circle-left fa-2x"></i></a>
                </div>
            </div>
        </div>    
        <div class="row">
            <div class="card text-left">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="card-title mb-1"> SLA </h4> 
                            <p> Your SLA </p>        
                        </div>
                        <div class="col-md-4">           
                        {{-- <div class="btn-group" style="float:right">        
                            <a class="btn btn-success " href="{{ url('my/sla/create') }}" > <i class="fa fa-plus"></i> Add New </a>              
                        </div> --}}
                        </div>
                    </div>
                    
                    <div id="slaResult">
                        @include('clients.sla.ajax')
                    </div>
                    
                </div>
            </div>
        </div>
            
    </div>
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
   
</div>

<script>
    $(document).ready(function(){
        var uriNum = location.hash;
       pageNumber = uriNum.replace("#", "");
       // alert(pageNumber);
       getData(pageNumber);

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
        //  var user_id     =    $(".customer_list").val();                
 
         var from_date   =    $(".from_date").val(); 
         var to_date     =    $(".to_date").val();  
 
             $('#slaResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
 
             $.ajax(
             {
                 url: '?page=' + page+'&from_date='+from_date+'&to_date='+to_date,
                 type: "get",
                 datatype: "html",
             })
             .done(function(data)
             {
                 $("#slaResult").empty().html(data);
                 $("#overlay").fadeOut(300);
                 //debug to check page number
                 location.hash = page;
             })
             .fail(function(jqXHR, ajaxOptions, thrownError)
             {
                 alert('No response from server');
 
             });
 
   }
</script>

@endsection
