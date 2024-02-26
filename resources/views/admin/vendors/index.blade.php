@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
    <div class="main-content"> 
        <div class="row">
            <div class="col-sm-11">
                <ul class="breadcrumb">
                <li>
                <a href="{{ url('/home') }}">Dashboard</a>
                </li>
                <li>Vendor</li>
                </ul>
            </div>
            <!-- ============Back Button ============= -->
            <div class="col-sm-1 back-arrow">
                <div class="text-right">
                    <a href="{{url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            @if ($message = Session::get('success'))
              <div class="col-md-12">   
                <div class="alert alert-success">
                <strong>{{ $message }}</strong> 
                </div>
              </div>
              @endif
            <div class="card text-left">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h4 class="card-title mb-1"> Vendors </h4> 
                            <p> List of all Vendors </p>        
                        </div>
                        <div class="col-md-4 text-center">
                            <h2 class="text-center font-weight-bolder">[Confidential]</h2>   
            
                        </div>
                        @php
                            $ADD_ACCESS    = false;
                            $EDIT_ACCESS   = false;
                            $VIEW_PROFILE_ACCESS = false;
                            $VIEW_ACCESS = false;
                            // dd($ADD_ACCESS);
                            $ADD_ACCESS    = Helper::can_access('Add Vendors','');
                            $EDIT_ACCESS   = Helper::can_access('Edit Vendors','');
                            $VIEW_PROFILE_ACCESS = Helper::can_access('View Vendor profile','');
                            $VIEW_ACCESS = Helper::can_access('View Vendors List','');
                            // $REPORT_ACCESS   = false;
                            // $VIEW_ACCESS   = false;
                        @endphp 

                        {{-- <div class="col-md-1 mt-2">
                            <div class="btn-group" style="float:right">
                            
                                
                            </div>
                        </div>             --}}
                        <div class="col-md-4">           
                            <div class="btn-group" style="float:right"> 
                                <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>          
                                @if($ADD_ACCESS)
                                        <a class="btn btn-success " href="{{ route('/admin/vendor/create') }}" > <i class="fa fa-plus"></i> Add New </a>  

                                @endif 
                                            
                            </div>
                        </div>
                    </div>
                    <div class="search-drop-field" id="search-drop" style="z-index: 1">
                        <div class="row">
                           <div class="col-12">           
                               <div class="btn-group" style="float:right;font-size:24px;">   
                                   <a href="#" class="filter_close text-danger"><i class="far fa-times-circle"></i></a>        
                               </div>
                           </div>
                       </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-1">
                                <label> From date </label>
                                <input class="form-control from_date commonDatePicker" type="text" placeholder="From date">
                            </div>
                            <div class="col-md-2 form-group mb-1">
                                <label> To date </label>
                                <input class="form-control to_date commonDatePicker" type="text" placeholder="To date">
                            </div>
                            
                           <div class="col-md-2 form-group mb-1">
                                <label>Reference number </label>
                                <input class="form-control ref" type="text" placeholder="reference number">
                            </div>
                            <div class="col-md-2 form-group mb-1">
                                <label>Phone number </label>
                                <input class="form-control mob" type="text" placeholder="phone">
                            </div>
                            <div class="col-md-2 form-group mb-1">
                                <label>Email id</label>
                                <input class="form-control email" type="email" placeholder="email">
                            </div>
                            <div class="col-md-2 form-group mb-1">
                                <label>Business Type</label>
                                <select class="form-control" name="business_type" id="business_type" >
                                    <option value="">All</option>
                                    <option  value="individual">Individual</option>
                                    <option  value="company">Company</option>
                                </select>
                            </div>
                            <div class="col-md-3 form-group mb-1 level_selector">
                                <label>Vendor Name</label><br>
                                <select class="form-control vendor_list select" name="vendor" id="vendor">
                                   <option value=''>-All-</option>
                                    @foreach($vendors as $item)
                                    <option value="{{$item->id}}"> {{ ucfirst($item->name)}} </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-md-2 form-group mb-1 level_selector">
                                <label>Vendor Name</label><br>
                                <select class="form-control candidate_list select " name="candidate" id="candidate">
                                 <option value=''>-Select-</option>
                                </select>
                            </div> --}}
                            {{-- <div class="col-md-2 form-group mb-1 level_selector">
                              <label>SLA Name</label><br>
                              <select class="form-control sla_list select " name="sla" id="sla">
                                 <option value=''>-Select-</option>
      
                              </select>
                             
                          </div>  --}}
                          
                           
                            
                           <div class="col-md-1">
                              <button class="btn btn-danger  resetBtn" style="padding: 7px;margin: 18px 0px;"> <i class="fas fa-refresh"></i>  Reset </button>
                           </div>
                           <div class="col-md-1">
                              <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                           </div>
                        </div>
                    </div>
                    <div id="candidatesResult">
                        @include('admin.vendors.ajax')
                    </div>
                    
                </div>
            </div>
        </div>
            
    </div>
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
   
</div>
<script>
    $("#vendor").select2();
    $(document).ready(function(){
        $('.filter0search').click(function(){
            $('.search-drop-field').toggle();
        });
        $('.filter_close').click(function(){
                    $('.search-drop-field').toggle();
        });
    });
    $(document).on('click', '.resetBtn' ,function(){

        $("input[type=text], textarea").val("");
        //   $('#vendor').val('');
           $('.mob').val('');
           $('.email').val('');
           $('#business_type').val('');
        // $('#candidate').val(null).trigger('change');
        $('#vendor').val(null).trigger('change');
       
        var uriNum = location.hash;
        pageNumber = uriNum.replace("#","");
        // alert(pageNumber);
        getData(pageNumber);
    });

    $('.customer_list').on('select2:select', function (e){
        var data = e.params.data.id;
        //loader
        $("#overlay").fadeIn(300);　
        getData(0);
        setData();
        e.preventDefault();
    });


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
    
    $(document).on('click','.filterBtn', function (e){    
      $("#overlay").fadeIn(300);　
      getData(0);
      e.preventDefault();
    });
    
    $(document).on('change','.vendor_list, .from_date, .to_date,.status,.ref,.email,.mob,#business_type', function (e){    
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
    
    function getData(page){
            //set data
            var vendor_id     =    $(".vendor_list").val();               
            var ref = $('.ref').val();
            var email = $('.email').val(); 
            var from_date   =    $(".from_date").val(); 
            var to_date     =    $(".to_date").val();      
            var status      =    $('.status').val();
             var mob = $('.mob').val();
             var business_type = $('#business_type').val();
    
                $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
    
                $.ajax(
                {
                    url: '?page=' + page+'&vendor_id='+vendor_id+'&from_date='+from_date+'&to_date='+to_date+'&status='+status+'&ref='+ref+'&email='+email+'&mob='+mob+'&business_type='+business_type,
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
    
       var vendor_id     =    $(".vendor_list").val();                
       //   var check       =    $(".check option:selected").val();
       alert (vendor_id);
       var from_date   =    $(".from_date").val(); 
       var to_date     =    $(".to_date").val(); 
       var status      =    $('.status').val();   
       
          $.ajax(
          {
                url: "{{ url('/') }}"+'/candidates/setData/?vendor_id='+vendor_id+'&from_date='+from_date+'&to_date='+to_date+'&status='+status,
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
