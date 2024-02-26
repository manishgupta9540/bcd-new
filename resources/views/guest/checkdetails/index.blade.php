@extends('layouts.guest')
@section('content')
<style>
    .filterBtn
    {
        color: #fff;
        background-color: #002e62;
        border-color: #002e62;
    }
    .filterBtn:hover
    {
        color: #fff;
        background-color: #002e62;
        border-color: #002e62;
    }
    .candidatesTable thead
    {
        background-color: #dee2e6;
    }
    

  
    </style>
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
            <div class="col-md-4 col-8">
                <h4 class="card-title mb-1"> Check Details </h4> 
                <p> List of all Check </p>        
            </div>
            {{-- <div class="col-md-3">
                <span>Total Candidates: <span > {{ $tota_candidates }}</span> </span>
            </div> --}}
            <div class="col-md-8 col-4">           
                <div class="btn-group" style="float:right">
                    @if(count($items)>0)     
                        <a href="#" class="filter0search m-bt-0"><i class="fa fa-filter"></i></a>   
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
                    <input type="text" class="form-control from_date commonDatepicker"  placeholder="From date">
                    <i class="date-icon fa fa-calendar" aria-hidden="true"></i>
                </div>
                <div class="col-md-2 form-group mb-1">
                    <label> To date </label>
                    <input type="text" class="form-control to_date commonDatepicker2" placeholder="To date">
                    <i class="date-icon2 fa fa-calendar" aria-hidden="true"></i>
                </div>
                <div class="col-md-2 form-group mb-1">
                <label>Order ID</label><br>
                <input class="form-control o_id" name="order_id" type="text" placeholder="Order ID">
                
                {{-- <input class="form-control candidate_list" type="text" placeholder="name"> --}}
                </div>
                <div class="col-md-2 form-group mb-1 level_selector">
                <label>Service Name</label><br>
                <select class="form-control service_list select1 " name="service" id="service">
                    <option> All </option>
                    @foreach($services as $service)
                        <option value="{{$service->id}}"> {{ $service->name}} </option>
                    @endforeach
                </select>
                
                {{-- <input class="form-control candidate_list" type="text" placeholder="name"> --}}
            </div>
                <div class="col-md-2">
                    <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                </div>
            </div>
        </div>
        <!-- data  -->
        <div id="candidatesResult">
            @include('guest.checkdetails.ajax')   
        </div> 
</div>
</div>
              
         
    

 {{-- <div class="modal" id="order_data_modal">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title" id="service_name"></h4>
          </div>
          <!-- Modal body -->
            <input type="hidden" name="giv_c_id" id="giv_c_id">
            <input type="hidden" name="service_id" id="service_id">
             <div class="modal-body">
                <div id="order_details">
 
                </div>
             </div>
          <!-- Modal footer -->
          <div class="modal-footer">
             <button type="button" class="btn btn-danger closeraisemdl" data-dismiss="modal">Close</button>
          </div>
       </div>
    </div>
 </div> --}}

 <script>
    
$(document).ready(function(){
    // $(".select").select2();
    $(".select1").select2();
    $('.filter0search').click(function(){
            $('.search-drop-field').toggle();
    });

   
    
  })
    $(document).on('change','.from_date',function() {

        var from = $('.from_date').datepicker('getDate');
        alert(from);
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