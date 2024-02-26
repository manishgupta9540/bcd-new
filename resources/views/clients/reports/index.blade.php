@extends('layouts.client')

@section('content')
<style>
    .disabled-link{
        pointer-events: none;
    }
</style>
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
            <li>Reports</li>
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
                    @php 
                    // $ADD_ACCESS    = false;
                    $REPORT_ACCESS   = false;
                    $VIEW_ACCESS   = false;
                    // $EDIT_ACCESS =false;
                    // $GENERATE_REPORT_ACCESS = false;
                    // dd($ADD_ACCESS);
                    $EXPORT_ACCESS = false;
                    $REPORT_ACCESS    = Helper::can_access('Download Report pdf','/my');//passing action title and route group name
                    $VIEW_ACCESS   = Helper::can_access('View Report List','/my');//passing action title and route group name
                    // $EDIT_ACCESS   = Helper::can_access('Edit Reports','');//passing action title and route group name
                    $EXPORT_ACCESS = Helper::can_access('Export Reports','/my');//passing action title and route group name
                    // $GENERATE_REPORT_ACCESS = Helper::can_access('Generate Reports ','');
                    @endphp
                    @if ($message = Session::get('success'))
                    <div class="col-md-12">   
                        <div class="alert alert-success">
                        <strong>{{ $message }}</strong> 
                        </div>
                    </div>
                    @endif 

                    <div class="col-md-8">
                        <h4 class="card-title mb-1"> Reports </h4> 
                        <p> Reports of verifications done. </p> 
                    </div>
                    <div class="col-md-4">
                        <div class="btn-group" style="float:right">  
                            <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>
                        </div>       
                    </div>
                    
                </div>
                @if ($EXPORT_ACCESS)
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
                            <p style="margin-bottom:2px;" class="load_container text-danger" id="loading"></p>
                        </div>
                    </div>
                @endif

                @if ($VIEW_ACCESS)
                    <!-- search bar -->
                    <div class="search-drop-field" id="search_drop_ff">
                        <div class="row">
                            <div class="col-md-2 form-group mb-1">
                                <label for="from_date"> From date </label>
                                <input class="form-control from_date commonDatePicker" id="from_date" type="text" placeholder="From date">
                            </div>
                            <div class="col-md-2 form-group mb-1">
                                <label for="to_date"> To date </label>
                                <input class="form-control to_date commonDatePicker" id="to_date" type="text" placeholder="To date">
                            </div>
                            <div class="col-md-2 form-group mb-1">
                                <label>Phone number </label>
                                <input class="form-control mob" type="text" placeholder="phone">
                            </div>
                        
                            <div class="col-md-3 form-group mb-1">
                                <label>Email id</label>
                                <input class="form-control email" type="text" placeholder="email">
                            </div>
                            <div class="col-md-3 form-group mb-1">
                                <label>Candidate reference number </label>
                                <input class="form-control ref" type="text" placeholder="reference number">
                            </div>
                            <div class="col-md-2 form-group mb-1 level_selector">
                                <label for="picker1"> Candidate </label>
                                <select class="form-control candidate_list select" name="candidate" id="candidate_list">
                                <option value="">All</option>
                                @if( count($candidates) >0 )
                                        @foreach($candidates as $item)
                                            <option value="{{ $item->id }}"> {{ $item->first_name.' '.$item->last_name }} </option>
                                        @endforeach
                                @endif
                                </select>
                            </div>
                            <div class="col-md-2 form-group mb-1 level_selector">
                                <label for="picker1"> Emp Code </label>
                                <select class="form-control emp_code select" name="candidate" id="emp_code">
                                <option value="">All</option>
                                @if( count($candidates) >0 )
                                        @foreach($candidates as $item)
                                            @if($item->client_emp_code!=NULL || $item->client_emp_code!='')
                                                <option value="{{ $item->id }}"> {{ $item->client_emp_code }} </option>
                                            @endif
                                        @endforeach
                                @endif
                                </select>
                            </div>
                            
                            <div class="col-md-3 form-group mb-1">
                                <label>Status</label><br>
                                <select class="form-control r_status select" name="candidate" id="candidate">
                                    <option value=""> All </option>
                                    <option value="incomplete">Pending</option>
                                    <option value="interim">Interim</option>
                                    <option value="completed">Completed</option>
                                </select>
                                {{-- <input class="form-control candidate_list" type="text" placeholder="name"> --}}
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-danger  resetBtn" style="padding: 7px;margin: 18px 0px;"> <i class="fas fa-refresh"></i>  Reset </button>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                            </div>
                        </div>
                    </div>

                <input type="hidden" name="report_status" id="report_status" value={{$incomplete}}>
                <input type="hidden" name="report_status1" id="report_status1" value={{$completed}}>
                <input type="hidden" name="report_status2" id="report_status2" value={{$interim}}>
                   
                    <!--  -->
                    <div id="reportsResult">
                        @include('clients.reports.ajax')   
                    </div> 
                    <!--  -->
                </div>
                @else
                <span class="text-center"> <h3>You have no access for view Report Lists </h3> </span>
            @endif
            </div>
        </div>
            
    </div>
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
   
</div>




<!-- Script -->

<script type="text/javascript">

    $(document).ready(function(){
        $(".select").select2();
        
        $('.filter0search').click(function(){
            $('.search-drop-field').toggle();
        });
    
        $(document).on('click', '.resetBtn' ,function(){

            $("input[type=text], textarea").val("");
            //   $('.customer_list').val('');
            //    $('.candidate').val('');
            //    $('.user_list').val('');
            $('#candidate').val(null).trigger('change');
            $('#emp_code').val(null).trigger('change');
            $('#candidate_list').val(null).trigger('change');
            // $('#service').val('');
            // $('#remain').val('');
            // $('#insuff_raised').val('');
            // $('#active_case').val('');
            var uriNum = location.hash;
            pageNumber = uriNum.replace("#","");
            // alert(pageNumber);
            getData(pageNumber);
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
    $(document).on('change keyup','.candidate_list, .from_date, .to_date,.email,.mob,.ref,.search,.emp_code,#report_status,#report_status1,#report_status2,.r_status', function (e){    
        $("#overlay").fadeIn(300);　
        getData(0);
        e.preventDefault();
    });
    var x = document.getElementById('search_drop_ff');
    $(document).on('click','.filterBtn', function (e){    
        $("#overlay").fadeIn(300);　
        getData(0);
        e.preventDefault();
        x.style.display = 'none';
    });
     
        


    // print visits  
    $(document).on('click','#downloadZip',function(){
        // setData();
        var _this =$(this);
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';
        var check = $(".check option:selected").val();
        $('p.load_container').html("");
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
                    _this.addClass('disabled-link');
                    $('#loading').html(loadingText);
                    // if (_this.html() !== loadingText) {
                    //     _this.html(loadingText);
                    // }
                    $.ajax({
                        type:"POST",
                        url: "{{ url('/my/report-export') }}",
                        data:{"_token": "{{ csrf_token() }}",'report_id':report_id,'type':check},   
                        success: function (response) {
                            
                            // location.reload();
                            // window.location=response;

                            window.setTimeout(function(){
                                _this.removeClass('disabled-link');
                                $('#loading').html("");
                                // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                            },2000);

                            if(response.success){
                                toastr.success('Mail Send Successfully');
                                // toastr.success('Zip Created Successfully');
                                window.setTimeout(function(){
                                    location.reload();
                                },2000);
                            }
                            else if(response.success==false && response.status=='no')
                            {
                                toastr.error('Select only completed and interim report !');
                            }
                            else if(response.success==false){
                                toastr.error('Something went wrong!!')
                            }

                        },
                        error: function (response) {
                            console.log(response);
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
    
    function getData(page){
        //set data
        var user_id     =    $(".customer_list").val();                
        // var check       =    $(".check option:selected").val();
        var email = $('.email').val();
        var mob = $('.mob').val();
        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();      
        var candidate_id=    $(".candidate_list option:selected").val();
        var emp_code =    $(".emp_code option:selected").val();
        var ref = $('.ref').val();
        var search = $('.search').val();

        var report_status=$('#report_status').val();
        var report_status1 =$('#report_status1').val();
        var report_status2=$('#report_status2').val();

        var r_status=$('.r_status').val();
            $('#reportsResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
    
            $.ajax(
            {
                url: '?page=' + page+'&customer_id='+user_id+'&status='+status+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&email='+email+'&mob='+mob+'&ref='+ref+'&search='+search+'&emp_code='+emp_code+'&report_status='+report_status+'&report_status1='+report_status1+'&report_status2='+report_status2+'&r_status='+r_status,
                type: "get",
                datatype: "html",
            })
            .done(function(data)
            {
                $("#reportsResult").empty().html(data);
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
        // var check    =    $(".check option:selected").val();
        var email = $('.email').val();
        var mob = $('.mob').val();
        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();    
        var candidate_id=    $(".candidate_list option:selected").val();  
        var emp_code =    $(".emp_code option:selected").val();                          
        var ref = $('.ref').val();
        var search = $('.search').val();
        var report_status=$('#report_status').val();
        var report_status1 =$('#report_status1').val();
        var report_status2=$('#report_status2').val();
        var r_status=$('.r_status').val();

            $.ajax(
            {
                url: "{{ url('/') }}"+'/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&email='+email+'&mob='+mob+'&ref='+ref+'&search='+search+'&emp_code='+emp_code+'&report_status='+report_status+'&report_status1='+report_status1+'&report_status2='+report_status2+'&r_status='+r_status,
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

    //
    });
    
    </script>

@endsection
