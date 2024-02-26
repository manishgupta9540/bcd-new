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
            <li>Reports</li>
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
            <div class="card text-left">
               <div class="card-body">
            
            <div class="row">

              
              @if ($message = Session::get('success'))
              <div class="col-md-12">   
                <div class="alert alert-success">
                <strong>{{ $message }}</strong> 
                </div>
              </div>
              @endif

                <div class="col-md-8">
                    <h4 class="card-title mb-1"> Reports </h4> 
                    <p> List of all Report </p>        
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 form-group mb-3">
                    <label for="picker1"> Export </label>
                    <select class="form-control check"  id="check">
                        <option value="">-Select-</option>
                        <option value="pdf">PDF</option>   
                    </select>
                </div>
                <div class="col-md-4 form-group mt-4">
                    <a class="btn-link " id="downloadZip" href="javascript:;"> <i class="far fa-file-archive"></i> Download Zip</a> 
                </div>
            </div>
            {{-- <div class="table-box mt-40"> --}}
             <!-- include menu -->
             @include('admin.reports.menu')
             <!-- include menu -->
                {{-- <div class="col-md-12">           
                <div class="btn-group" style="float:right;  margin-top: 15px;">
                    <!-- <button class="btn btn-secondary btn-lg dropdown-toggle" id="btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">   Actions  </button>
                    <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div> -->
                    <a class="btn btn-success createJob" href="javascript:;" > <i class="fa fa-plus"></i> Add New </a>             
                </div>
                </div> --}}
            </div>
            <div id="candidatesResult">
                @include('admin.reports.candidate-report-ajax')
            </div>
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
   <!-- <div class="app-footer">
      <div class="footer-bottom border-top pt-3 d-flex flex-column flex-sm-row align-items-center">
           
      <p><strong> 2020 &copy; Admin ! All rights reserved</strong></p>
      
          <span class="flex-grow-1"></span>
          <div class="d-flex align-items-center">
              <div>
                  <p class="m-0"> design by techsaga </p>
              </div>
          </div>
      </div>
      </div> -->
   <!-- fotter end -->
</div>

<script>
    $(document).ready(function(){
        var uriNum = location.hash;
        pageNumber = uriNum.replace("#", "");
        // alert(pageNumber);
        getData(pageNumber);

        // filterBtn
        $(document).on('change','.search', function (e){    
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

        $(document).on('click','#downloadZip',function(){
        // setData();
            var check = $(".check option:selected").val();
            if(check !=''){
                //                  
                    var check       =    $(".check option:selected").val();
                    var report_id = [];
                    var i = 0;


                    $('.reports:checked').each(function () {
                        report_id[i++] = $(this).val();
                    });

                    var count = report_id.length;                          
                    if(count>0){
                        $.ajax({
                            type:"POST",
                            url: "{{ url('/report-export') }}",
                            data:{"_token": "{{ csrf_token() }}",'report_id':report_id,'type':check},   
                            success: function (response) {
                                
                                // location.reload();
                                window.location=response;

                            },
                            error: function (xhr, textStatus, errorThrown) {
                                
                            }
                        });
                    }
                    else{
                        alert('Please select a check to export! ');
                    }
                //
            
            }else{
                alert('Please select a export list! ');
                }
        });

        function getData(page){
            //set data
            // var user_id     =    $(".customer_list").val();                
            // // var check       =    $(".check option:selected").val();
            
        
            // var from_date   =    $(".from_date").val(); 
            // var to_date     =    $(".to_date").val();      
            // var candidate_id=    $(".candidate_list option:selected").val();
            // var mob = $('.mob').val();
            // var ref = $('.ref').val();
            // var email = $('.email').val();
            // var report_status=$('.report_status').val();               
            var search = $('.search').val();
                $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
        
                $.ajax(
                {
                    url: '?page=' + page+'&search='+search,
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
            // // var check       =    $(".check option:selected").val();
        
            // var from_date   =    $(".from_date").val(); 
            // var to_date     =    $(".to_date").val();    
            // var candidate_id=    $(".candidate_list option:selected").val();                            
            // var mob = $('.mob').val();
            // var ref = $('.ref').val();
            // var email = $('.email').val();
            var search = $('.search').val();
            var report_status=$('.report_status').val();
                $.ajax(
                {
                    url: "{{ url('/') }}"+'/candidates/setData/?search='+search,
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
    });
</script>
@endsection
