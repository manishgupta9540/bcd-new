@extends('layouts.admin') 
@section('content')
<style>
    .disabled-link
    {
        pointer-events: none;
    }

    #preview{
        /* overflow-x: hidden; */
        /* overflow-y: hidden; */
        z-index: 999;
        padding-top: 0px;
        /* margin:auto; */
    }
    #preview .modal-dialog.modal-lg{
  max-width: 90% !important;
  width: 100%;
  padding: 0px;
  left: 3.5%;
}

#preview .modal-content {
  margin: auto;
  display: block;
  width: 100%;
  max-width: 1270px;
}
</style>
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
                        <div class="col-md-5">
                            <h4 class="card-title mb-1"> Report </h4> 
                            <p> Reports of verification. you can see auto-generated reports and create manual report. </p>        
                        </div>
                        <div class="col-md-6"><h2 class="mb-0">[Restricted]</h2></div>
                        {{-- <div class="col-md-3">
                            <span>Total Candidates: <span > {{ $tota_candidates }}</span> </span>
                        </div> --}}
                    </div>
                    <div class="row">
                        <div class="col-md-1 form-group mb-3">
                            <label for="picker1"> Export </label>
                            <select class="form-control check"  id="check">
                                <option value="">-Select-</option>
                                <option value="pdf">PDF</option>   
                            </select>
                        </div>
                        <div class="col-md-2 form-group mt-4" >
                            <a class="btn-link" id="downloadZip" href="javascript:;"> <i class="far fa-file-archive"></i> Download Zip</a> <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-original-title="Download Reports in bulk"></i>  
                            <p style="margin-bottom:2px;" class="load_container text-danger" id="loading"></p>
                        </div>
                        <div class="col-md-7 form-group mt-4"  >
                            <label for="picker1" style="float:right;" ><strong>Numbers of Rows:-</strong> <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-original-title="Show number of rows in a page"></i> </label>
                        </div>
                        <div class="col-md-1 form-group mt-3" >
                            <select class="form-control rows"  id="rows">
                               <option value="">-Select-</option>
                               <option value="25">25</option>   
                               <option value="50">50</option> 
                               <option value="100">100</option> 
                               <option value="150">150</option> 
                               <option value="200">200</option> 
                               <option value="250">250</option> 
                               <option value="300">300</option> 
                               <option value="350">350</option> 
                               <option value="400">400</option> 
                               <option value="450">450</option> 
                               <option value="500">500</option> 
                            </select>
                         </div>
                         <div class="col-md-1">
                            <div class="btn-group" style="float:right; margin-top: 15px;">
                                <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>   
                            </div>
                        </div>
                            <!-- include menu -->
                            {{-- @include('admin.reports.menu') --}}
                            <!-- include menu -->

                         <div class="search-drop-field" id="search-drop">
                              <div class="row">
                                    <div class="col-md-3 form-group mb-1 level_selector">
                                        <label for="picker1"> Customer </label>
                                        <select class="form-control customer_list select2 " name="customer" id="customer">
                                            <option>-Select-</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}"> {{ $customer->company_name.' - '.$customer->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
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
                                        <input class="form-control mob" type="text" placeholder="Phone No.">
                                    </div>
                                    <div class="col-md-2 form-group mb-1">
                                        <label>Reference number </label>
                                        <input class="form-control ref" type="text" placeholder="Reference number">
                                    </div>
                                    <div class="col-md-2 form-group mb-1">
                                        <label>Email id</label>
                                        <input class="form-control email" type="email" placeholder="Email ID">
                                    </div>
                                    <div class="col-md-3 form-group mb-1 level_selector">
                                        <label for="picker1"> Candidate </label>
                                        <select class="form-control candidate_list select2" name="candidate" id="candidate_list">
                                        <option value="">-Select-</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 form-group mb-1">
                                        <label>Status</label><br>
                                        <select class="form-control r_status select" name="candidate" id="candidate">
                                            <option value=""> All </option>
                                            <option value="incomplete">Incomplete</option>
                                            <option value="interim">Interim</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                        {{-- <input class="form-control candidate_list" type="text" placeholder="name"> --}}
                                    </div>
                                    <div class="col-md-2 form-group mb-1">
                                        <label>Colour Code</label><br>
                                        <select class="form-control color_code select" name="color_code" id="color_code">
                                            <option value=""> All </option>
                                            <option value="1">Red</option>
                                            <option value="2">Yellow</option>
                                            <option value="3">Orange</option>
                                            <option value="4">Green</option>
                                        </select>
                                        {{-- <input class="form-control candidate_list" type="text" placeholder="name"> --}}
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                    </div>

                                 </div>
                            </div>
                            <div class="col-md-12">           
                               
                                    <!-- {{-- <a class="btn btn-success " href="{{ url('/reports/create') }}" > <i class="fa fa-plus"></i> Create </a>              --}} -->
                                <!-- search bar -->
                                

                                    {{-- </div> --}}
                                    {{-- </div> --}}
                                    {{-- <div class="row">
                                    <div class="col-md-4">
                                        <div class="btn-group" style="float:right">  
                                            <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>
                                        </div>       
                                    </div>
                                    <div class="search-drop-field" id="search_drop_ff">
                                    
                                    </div>
                                </div> --}}

                                <!-- data  -->
                                <div id="candidatesResult">
                                @include('admin.reports.ajax')   
                                </div>
                                                <!--  -->
                            </div>
                        </div>
                    </div>
                </div>
            
             </div>
                <input type="hidden" name="report_status" id="report_status" value={{$incomplete}}>
                <input type="hidden" name="report_status1" id="report_status1" value={{$completed}}>
                <input type="hidden" name="report_status2" id="report_status2" value={{$interim}}>
                <input type="hidden" name="report_card_url" id="report_card_url" value={{$report_card_url}}>
        <!-- Footer Start -->
        <div class="flex-grow-1"></div>
        
        {{-- </div> --}}
        {{-- Modal for otp verification    --}}


 
            {{-- <!-- Footer Start -->
            <div class="flex-grow-1"></div> --}}
   
    </div>
    

<!-- Script -->

<script type="text/javascript">

    $(document).ready(function(){
        // $(".select").select2();
        
        $(".select2").select2();
        $(".check").select2();
        $(".rows").select2();
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
    //
    $('.customer_list').on('select2:select', function (e){
        var data = e.params.data.id;
        //loader
        $("#overlay").fadeIn(300);　
        getData(0);
        setData();
        event.preventDefault();
    });
    
    // filterBtn
    $(document).on('change','.customer_list, .candidate_list, .from_date, .to_date,.mob,.ref,.email,.r_status,.search,#report_status,#report_status1,#report_status2,#rows,.color_code', function (e){    
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
    $(document).on('change','.customer_list',function(e) {
            e.preventDefault();
            $('.candidate_list').empty();
            $('.candidate_list').append("<option value=''>-Select-</option>");
            var customer_id = $('.customer_list option:selected').val();
            $.ajax({
            type:"POST",
            url: "{{ url('/candidates/getlist') }}",
            data: {"_token": "{{ csrf_token() }}",'customer_id':customer_id},      
            success: function (response) {
                console.log(response);
                if(response.success==true  ) {   
                    $.each(response.data, function (i, item) {
                      $(".candidate_list").append("<option value='"+item.id+"'> "+ item.name +" ("+ item.display_id+")</option>");
                    });
                }
                //show the form validates error
                if(response.success==false ) {                              
                    for (control in response.errors) {   
                        $('#error-' + control).html(response.errors[control]);
                    }
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                // alert("Error: " + errorThrown);
            }
        });
        return false;
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

    $(document).on('click','#downloadZip',function(){
        // setData();
        var _this=$(this);
        var check = $(".check option:selected").val();
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';
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
                    $.ajax({
                        type:"POST",
                        url: "{{ url('/report-export') }}",
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
                                toastr.success('Mail has been sent successfully');
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
    
    
    // print visits  
    // $(document).on('click','#exportExcel',function(){
    // setData();
    // var check = $(".check option:selected").val();
    //   if(check !=''){
    //     //
    //         var user_id     =    $(".customer_list").val();                
    //         var check       =    $(".check option:selected").val();
    //         var from_date   =    $(".from_date").val(); 
    //         var to_date     =    $(".to_date").val();    
    //         var candidate_id=    $(".candidate_list option:selected").val();                            
    
    //         $.ajax(
    //         {
    //             url: "{{ url('/') }}"+'/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&check_id='+check+'&candidate_id='+candidate_id,
    //             type: "get",
    //             datatype: "html",
    //         })
    //         .done(function(data)
    //         {
    //            console.log(data);
    //            var path = "{{ route('/jaf-export')}}";
    //             window.open(path);
    //         })
    //         .fail(function(jqXHR, ajaxOptions, thrownError)
    //         {
    //             //alert('No response from server');
    //         });
    //     //
       
    //   }else{
    //       alert('Please select a check to export! ');
    //      }
    //   });
    
    // }); 
    
    function getData(page){
        //set data
        var user_id     =    $(".customer_list").val();                
        // var check       =    $(".check option:selected").val();
        
    
        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();      
        var candidate_id=    $(".candidate_list option:selected").val();
        var mob = $('.mob').val();
        var ref = $('.ref').val();
        var email = $('.email').val();
        var r_status=$('.r_status').val();               
        var search = $('.search').val();

        var report_status=$('#report_status').val();
        var report_status1 =$('#report_status1').val();
        var report_status2=$('#report_status2').val();
        var rows = $("#rows option:selected").val();
        var report_card_url = $("#report_card_url").val();
        var color_code = $(".color_code option:selected").val();

            $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
    
            $.ajax(
            {
                url: '?page=' + page+'&customer_id='+user_id+'&status='+status+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&mob='+mob+'&ref='+ref+'&email='+email+'&r_status='+r_status+'&search='+search+'&report_status='+report_status+'&report_status1='+report_status1+'&report_status2='+report_status2+'&rows='+rows+'&color_code='+color_code+'&report_card_url='+report_card_url,
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
        // var check       =    $(".check option:selected").val();
    
        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();    
        var candidate_id=    $(".candidate_list option:selected").val();                            
        var mob = $('.mob').val();
        var ref = $('.ref').val();
        var email = $('.email').val();
        var search = $('.search').val();
        var r_status=$('.r_status').val();

        var report_status=$('#report_status').val();
        var report_status1 =$('#report_status1').val();
        var report_status2=$('#report_status2').val();
        var rows = $("#rows option:selected").val();
        var color_code = $(".color_code option:selected").val();
            $.ajax(
            {
                url: "{{ url('/') }}"+'/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&mob='+mob+'&ref='+ref+'&email='+email+'&r_status='+r_status+'&search='+search+'&report_status='+report_status+'&report_status1='+report_status1+'&report_status2='+report_status2+'&rows='+rows+'&color_code='+color_code,
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
    <!--  -->
    {{-- <script type="text/javascript">
        $(document).ready(function(){
    
            // $('.send_otp').click(function(){
            //     // var id=$(this).attr('data-id');
            //     // $('#can_id').val(id);
            //     alert("hrllo");
            //     // $.ajax({
            //     //     url:"{{ route('/candidates/send_otp') }}",
            //     //     method:"POST",
            //     //     data:{"_token": "{{ csrf_token() }}",'_id':id},      
            //     //     success:function(data)
            //     //     {
            //     //         console.log(data);
            //     //         if(data.fail == false)
            //     //         {
            //     //         //notify
            //     //             $('#send_otp').modal({
            //     //                 backdrop: 'static',
            //     //                 keyboard: false
            //     //             });
            //     //             // console.log(data.id);
            //     //         }
            //     //         else
            //     //         {
            //     //             alert('not working');
            //     //         }
            //     //     }
            //     // });
                
            // });
    
            // $(document).on('submit', 'form#verify_otp', function (event) {
    
            //     $("#overlay").fadeIn(300);　
            //     event.preventDefault();
            //     var form = $(this);
            //     var data = new FormData($(this)[0]);
            //     var url = form.attr("action");
            //     var $btn = $(this);
    
            //     $.ajax({
            //         type: form.attr('method'),
            //         url: url,
            //         data: data,
            //         cache: false,
            //         contentType: false,
            //         processData: false,
            //         success: function (data) {
            //             console.log(data);
            //             $('.error-container').html('');
            //             if (data.fail && data.error_type == 'validation') {
                                
            //                     //$("#overlay").fadeOut(300);
            //                     for (control in data.errors) {
            //                     $('input[otp=' + control + ']').addClass('is-invalid');
            //                     $('#error-' + control).html(data.errors[control]);
            //                     }
            //             } 
            //             if (data.fail && data.error == 'yes') {
                            
            //                 $('#error-all').html(data.message);
            //             }
            //             if (data.fail == false) {
            //                 // $('#send_otp').modal('hide');
            //                 // alert(data.id);
            //                 var candidate_id=data.id;
    
            //                 window.location.href="{{ url('candidate/report-generate') }}/"+candidate_id;
            //                 // window.location.href='{{ Config::get('app.admin_url')}}/aadharchecks/show';
            //                 //  location.reload(); 
            //             }
            //         },
            //         error: function (xhr, textStatus, errorThrown) {
                        
            //             alert("Error: " + errorThrown);
    
            //         }
            //     });
            //     return false;
    
            // });
    }); 
    </script> --}}
@endsection
