@extends('layouts.admin')
<style>
    .disabled-link {
      pointer-events: none;
    }
    .bootstrap-select .dropdown-toggle .filter-option-inner-inner{
        color: black;
    }
/* .select2-search__field{
   width:100% !important;
} */
</style>
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
                <li>Candidates</li>
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
            <div class="card text-left">
               <div class="card-body">  
            
                    <div class="row">
                        {{-- <input type="hidden" name="active_case" id="active_case" value={{$filled}}> --}}
                        @if ($message = Session::get('success'))
                        <div class="col-md-12">   
                            <div class="alert alert-success">
                            <strong>{{ $message }}</strong> 
                            </div> 
                        </div>
                        @endif 

                        <div class="col-md-4">
                            <h4 class="card-title mb-1"> Candidates </h4> 
                            <p> List of all Candidates </p>        
                        </div>
                        <div class="col-md-3 text-center">
                            <h2>[Restricted]</h2>
                            
                        </div>
                        <div class="col-md-3 text-right"><span>Total Candidates: <span > {{ $tota_candidates }}</span> </span>          
                        </div>
                        <div class="col-md-2"> 
                              <div class="btn-group" style="float:right">     
                            <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>   
                            
                            @php
                            $ADD_ACCESS    = false;
                            // $EDIT_ACCESS   = false;
                            // $DELETE_ACCESS = false;
                            // dd($ADD_ACCESS);
                            $ADD_ACCESS    = Helper::can_access('Add Candidates','');
                            // $EDIT_ACCESS   = Helper::can_access('Edit ');
                            // $DELETE_ACCESS = Helper::can_access('Delete Category');
                          @endphp 

                          @if($ADD_ACCESS)
                          <a class="btn btn-success" href="{{ url('/candidates/create')}}" > <i class="fa fa-plus"></i> Add New </a>              

                          @endif  
   
                            {{-- <a class="btn btn-success " href="{{ url('/candidates/create')}}" > <i class="fa fa-plus"></i> Add New </a> --}}
                        </div>
                        </div>
                    </div>
                        <!-- search bar -->
                        <div class="search-drop-field" id="search-drop">
                            
                        </div>
                        {{-- <div class="row">
                            <div class="col-md-3 form-group mb-1">
                                <label for="picker1"> Customer </label>
                                <select class="form-control customer_list" name="customer" id="customer">
                                   <option>-Select-</option>
                                   @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}"> {{ $customer->first_name.'-'.$customer->company_name}} </option>
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
                             <div class="col-md-3 form-group mb-1">
                                <label for="picker1"> Candidate </label>
                                <select class="form-control candidate_list" name="candidate" id="candidate_list">
                                   <option value="">-Select-</option>
                                   
                                </select>
                             </div>
                             <div class="col-md-2">
                                <button class="btn btn-primary search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                             </div>
                        </div> --}}
                        <!-- export data -->
                        <div class="row">
                            
                            <div class="col-md-2 form-group mb-3">
                                <label for="picker1"><strong>Check</strong> <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-original-title="Select Check to export JAF Details"></i>  </label>
                                <select class="form-control check" name="check[]" id="check" data-actions-box="true" data-selected-text-format="count>1" multiple>
                                    {{-- <option value="">-Select-</option> --}}
                                    @foreach($services as $service)
                                        <option value="{{ $service->id}}">{{ $service->name  }}</option>   
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 form-group mt-4">
                                <a class="btn-link" id="exportExcel" href="javascript:;"> <i class="fa fa-file-excel-o"></i> Export Excel</a> <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-original-title="export JAF Details"></i> 
                                <p style="margin-bottom:2px;" class="load_container text-danger" id="loading"></p>
                            </div>
                            <div class="col-md-2 form-group mt-4">
                                <a class="btn-link" id="bulk_ready_report" href="javascript:;"> <i class="fa fa-file"></i> Bulk Ready to Report</a> <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-original-title="Mark ready to report in bulk"></i> 
                                <p style="margin-bottom:2px;" class="load_container text-danger" id="loading"></p>
                            </div>

                            <div class="col-md-3 form-group mt-4">
                                <a class="btn-link" id="refrence_number_export_excel" href="javascript:;"> <i class="fa fa-file"></i>Refrence Data</a> <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-original-title="Mark ready to excel in refrence number"></i> 
                                <p style="margin-bottom:2px;" class="load_container text-danger" id="loading"></p>
                            </div>

                            <div class="col-md-2 form-group mb-4" >
                                <label for="picker1" ><strong>Numbers of Rows:-</strong> <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-original-title="Select Number of rows "></i>  </label>
                             
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
                             <div class="col-md-1 form-group mb-3">
                                <label for="picker1"><strong> Priority </strong> <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-original-title="Select priority for candidate"></i> </label>
                                <select class="form-control check_p"  id="check_p">
                                    <option value="">-Select-</option>
                                    {{-- <option value="low">Low</option>    --}}
                                    <option value="normal">Normal</option>   
                                    <option value="high">High</option>   
                                
                                </select>
                             </div>
                             
                        </div>
                       
                        <!-- ./export data --> 
                        <input type="hidden" name="active_case" id="active_case" value={{$filled}}>
                        <input type="hidden" name="insuffs" id="insuffs" value={{$insuffs}}>
                        <input type="hidden" name="service" id="service" value={{$service_s}}>
                        <input type="hidden" name="sendto" id="sendto" value={{$send_to}}>
                        <input type="hidden" name="jafstatus" id="jafstatus" value={{$jafstatus}}>
                        <input type="hidden" name="jafstatus1" id="jafstatus1" value={{$pending}}>
                        <input type="hidden" name="jafstatus2" id="jafstatus2" value={{$draft}}>
                        <input type="hidden" name="verification_status" id="verification_status" value={{$verification_status}}>
                        <input type="hidden" name="verify_status" id="verify_status" value={{$verify_status}}>
                        <input type="hidden" name="candidates_id" id="candidates_id" value={{$candidates_id}}>
                        
                        <input type="hidden" name="candidate_kams_url" id="candidate_kams_url" value={{$candidate_kams_url}}>
                       

                        


                        <!-- data  -->
                        <div id="candidatesResult">
                            @include('admin.candidates.ajax')
                        </div> 
                        <!--  -->
                   </div>
             </div>
        </div>
   </div>
</div>
    
<!-- Script -->
<script type="text/javascript">

$(document).ready(function(){
    $('.tool').tooltip();
    $(".select").select2();
    $(".check_p").select2();
    $(".rows").select2();
    
    // $(".select2").select2();
    // $('.filter0search').click(function(){
    //         $('.search-drop-field').toggle();
    // });
    // Advance Aadhar Check
    // });
    
    $('.check').selectpicker();
    $('.verified22').selectpicker();

    // var path = "{{ url('/candidates/autocustomer') }}";
    //   $('#customer').typeahead({
    //         source:  function (search, process) {
    //         return $.get(path, { search: search }, function (data) {
    //         // console.log(data);
    //               return process(data);
    //            });
    //         }
    //   });
    var active_case =  $('#active_case').val();
    setTimeout(()=>{
        $.ajax({
            type:'GET',
            url: "{{url('/candidate/filter')}}",
            data:{'active_case':active_case} ,
            beforeSend: function() {
                $('.search-drop-field').html(loaderHtml());
            }, 
            success: function (response) {        
                //console.log(response);
                $('.search-drop-field').html(response.html);
                setTimeout(()=>{
                    $.ajax({
                        type:"POST",
                        url: "{{ url('/candidates/candidate_filter_reference_number') }}",
                        data: {"_token": "{{ csrf_token() }}"},      
                        success: function (response) {
                            console.log(response);
                            if(response.success==true ) {   
                                $('#ref').html(response.data);
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
                },500);
                
            },
            error: function (xhr, textStatus, errorThrown) {
                alert("Error: " + errorThrown);
            }
        });
    },500);

    $(document).on('click','.filter0search',function(){
         $('.search-drop-field').toggle();
    });

    $(document).on('click','.filter_close',function(){
        $('.search-drop-field').toggle();
    });

    $(document).on('click', '.resetBtn' ,function(){

        $("input[type=text], textarea").val("");
        //   $('.customer_list').val('');
        //    $('.candidate').val('');
        //    $('.user_list').val('');
        $('#candidate').val(null).trigger('change');
        $('#ref').val(null).trigger('change');
        // $('#customer').val(null).trigger('change');
        // $('#user').val(null).trigger('change');
        $('#remain').val('');
        $('#active_case').val('');
        $('#insuff_raised').val('');
        $('#service_name').val('');
        $('.email').val('');
        var uriNum = location.hash;
        pageNumber = uriNum.replace("#","");
        // alert(pageNumber);
        getData(pageNumber);
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
    $(document).on('change','.jaf_from_date',function() {

        var from = $('.jaf_from_date').datepicker('getDate');
        var to_date   = $('.jaf_to_date').datepicker('getDate');

        if($('.jaf_to_date').val() !=""){
            if (from > to_date) {
                alert ("Please select appropriate date range!");
                $('.jaf_from_date').val("");
                $('.jaf_to_date').val("");

            }
        }

    });
    //
    $(document).on('change','.jaf_to_date',function() {

        var to_date = $('.jaf_to_date').datepicker('getDate');
        var from   = $('.jaf_from_date').datepicker('getDate');
        if($('.jaf_from_date').val() !=""){
            if (from > to_date) {
                alert ("Please select appropriate date range!");
                $('.jaf_from_date').val("");
                $('.jaf_to_date').val("");
            
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
    $(document).on('change','.customer_list, .candidate_list, .from_date, .to_date,.mob,.ref,.email,.ref_list,#remain,#active_case,#candidates_id,#insuff_raised,.search,#rows,#service_name,#verified,#ref,.jaf_from_date, .jaf_to_date', function (e){    
        $("#overlay").fadeIn(300);　
        e.preventDefault();
        getData(0);
       
        // 

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
            // alert(customer_id);
            $.ajax({
                type:"POST",
                url: "{{ url('/customers/candidates/getlist') }}",
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


    // print visits  
    // $(document).on('click','#exportExcel',function(){
        // setData();
        // var _this=$(this);
        // var check = $(".check option:selected").val();
        // var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';
        // $('p.load_container').html("");
        // var candidate_arr = [];
        // var i = 0;
        
        // $('.check option:selected').each(function () {
        //     candidate_arr[i++] = $(this).val();
        // });

        // alert(candidate_id.length);

        

        // if(check!=''){
                
        //     //  
        //     _this.addClass('disabled-link');
        //     $('#loading').html(loadingText);
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
        //             window.setTimeout(function(){
        //                 _this.removeClass('disabled-link');
        //                 $('#loading').html("");
        //                 // _this.html('<i class="far fa-file-archive"></i> Download Zip');
        //             },2000);
                    
        //             console.log(data);
        //             var path = "{{ route('/jaf-export')}}";
        //                 window.open(path);
        //         })
        //         .fail(function(jqXHR, ajaxOptions, thrownError)
        //         {
        //             //alert('No response from server');
        //         });
        //     //
        
        // }else{
        //     alert('Please select a check to export! ');
        //     }
    // });

    // $(document).on('click','#exportExcel',function(){
    //     var _this=$(this);
    //     var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';
    //     $('p.load_container').html("");

    //     var check=0;  
    //     var select = document.getElementById("check");

    //     var export_service_id=[];

    //     var export_candidate_id=[];

    //     // var i=0;
    //     // $('.check option:selected').each(function () {
    //     //     export_service_id[i++] = $(this).val();
    //     // });

    //     // alert(export_service_id);

    //     // var j=0;
    //     // $('.priority:checked').each(function () {
    //     //     export_candidate_id[j++] = $(this).val();
    //     // });

    //     // alert(export_candidate_id);
        
    //     for(var i = 0; i < select.options.length; i++){
    //         if(select.options[i].selected){
    //             check++;
    //         }
    //     }
        
    //     if(check<=0)
    //     {
    //         alert("Please Select the Services first");
    //     }
    //     else
    //     {
    //         var candidate=document.querySelectorAll('.priority:checked').length;

    //         if(candidate<=0)
    //         {
    //             alert("Please Select the Candidate first");
    //         }
    //         else
    //         {
    //             var i=0;
    //             $('.check option:selected').each(function () {
    //                 export_service_id[i++] = $(this).val();
    //             });

    //             var j=0;
    //             $('.priority:checked').each(function () {
    //                 export_candidate_id[j++] = $(this).val();
    //             });

    //             _this.addClass('disabled-link');
    //             $('#loading').html(loadingText);
    //             var user_id     =    $(".customer_list").val();                
    //             var from_date   =    $(".from_date").val(); 
    //             var to_date     =    $(".to_date").val();  

    //             $.ajax(
    //             {
                    
    //                 url: "{{ url('/') }}"+'/candidates/setData/',
    //                 type: "get",
    //                 data: {'customer_id':user_id,'from_date':from_date,'to_date':to_date,'export_service_id':export_service_id,'export_candidate_id':export_candidate_id},
    //                 datatype: "html",

    //             })
    //             .done(function(data)
    //             {
    //                 window.setTimeout(function(){
    //                     _this.removeClass('disabled-link');
    //                     $('#loading').html("");
    //                     // _this.html('<i class="far fa-file-archive"></i> Download Zip');
    //                 },2000);
                    
    //                 // console.log(data);
    //                 var path = "{{ route('/jaf-export')}}";
    //                     window.open(path);
    //             })
    //             .fail(function(jqXHR, ajaxOptions, thrownError)
    //             {
    //                 //alert('No response from server');
    //             });

    //         }
            
    //     }


    // });
    $(document).on('click','#bulk_ready_report',function () {

        var bulk_this=$(this);
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';
        $('p.load_container').html("");

        var candidates_id=[];
        var j=0;
                $('.priority:checked').each(function () {
                    candidates_id[j++] = $(this).val();
                });
        console.log(candidates_id);
        $.ajax({
                  type:'POST',
                  url: "{{ url('/') }}"+'/bulk/ready-to-report',
                  data: {"_token" : "{{ csrf_token() }}",'candidates_id':candidates_id},        
                  success: function (response) {
                    window.setTimeout(function(){
                        bulk_this.removeClass('disabled-link');
                        $('#loading').html("");
                        // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                        window.location = "{{ url('/')}}"+"/candidates/";
                    },2000);
                    // window.setTimeout(function() {
                                
                    //        }, 2000);
                    // console.log(data);
                    // var path = "{{ route('/jaf-export')}}";
                    // window.open(path);
                    
                    if(response.success==true){
                        if (response.data==1) {
                            var candidate ="candidate";
                        } else if(response.data >1) {
                            var candidate ="candidates";
                        }

                        toastr.success("Ready to Report has been marked successfully in"+" "+response.data+" "+ candidate);
                    } else{
                        toastr.success("Ready to Report has not been marked to any candidate,Please check the Candidate's Data verified status!");
                    }
                  },
                  error: function (xhr, textStatus, errorThrown) {
                     // alert("Error: " + errorThrown);
                  }
            });
    });

    $(document).on('click','#exportExcel',function(){

        var _this=$(this);
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';
        $('p.load_container').html("");

        var check=0;  
        var select = document.getElementById("check");

        var export_service_id=[];

        var export_candidate_id=[];

        for(var i = 0; i < select.options.length; i++){
            if(select.options[i].selected){
                check++;
            }
        }
        
        if(check<=0)
        {
            alert("Please Select the Services first");
        }
        else
        {
            var i=0;
                $('.check option:selected').each(function () {
                    export_service_id[i++] = $(this).val();
                });

                var j=0;
                $('.priority:checked').each(function () {
                    export_candidate_id[j++] = $(this).val();
                });

                _this.addClass('disabled-link');
                $('#loading').html(loadingText);
                var user_id     =    $(".customer_list").val();                
                var from_date   =    $(".from_date").val(); 
                var to_date     =    $(".to_date").val();  

                $.ajax({
                  type:'POST',
                  url: "{{ url('/') }}"+'/jaf-export',
                  data: {"_token" : "{{ csrf_token() }}",'export_service_id':export_service_id,'export_candidate_id':export_candidate_id},        
                  success: function (response) {
                    window.setTimeout(function(){
                        _this.removeClass('disabled-link');
                        $('#loading').html("");
                        // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                    },2000);
                    
                    // console.log(data);
                    // var path = "{{ route('/jaf-export')}}";
                    // window.open(path);
                    
                    if(response.success)
                        window.open(response.url);
                    else
                        $('#loading').html(response.error);
                  },
                  error: function (xhr, textStatus, errorThrown) {
                     // alert("Error: " + errorThrown);
                  }
             });
        }

    });

    //export excel refrence number
    $(document).on('click','#refrence_number_export_excel',function () {
        
        var bulk_this=$(this);
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';
        $('p.load_container').html("");

        var refrence_id=[];
        var j=0;
        $('.priority:checked').each(function () {
            refrence_id[j++] = $(this).val();
        });


        if( $('.priority').is(':checked') >0){
            $.ajax({
                type:'POST',
                url: "{{ url('/') }}"+'/reference_number_export',
                data: {
                    "_token" : "{{ csrf_token() }}",
                    'refrence_id':refrence_id
                },        
                success: function (response) {
                    window.setTimeout(function(){
                        bulk_this.removeClass('disabled-link');
                        $('#loading').html("");
                        //window.location = "{{ url('/')}}"+"/candidates/";
                    },2000);
                    
                    if(response.success)
                        window.open(response.url);
                    else
                        $('#loading').html(response.error); 
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
            });
        }else{
            swal("Please check at least one checkbox");
        }
    });
});

    function getData(page,e){
        //set data
        // var selected=[];
        var i=0;
        //console.log($(".ref_list").val());
        // $('.ref_list option:selected').each(function () {
        //     selected[i++] = $(this).val()==undefined?'':$(".ref_list").val();
        // });
        //var ref         =    $(".ref_list").val()==undefined?'':$(".ref_list").val();
        var user_id     =    $(".customer_list").val()==undefined?'':$(".customer_list").val();                
        var check       =    $(".check option:selected").val()==undefined?'':$(".check option:selected").val();
        var type        =    $('#check_p').val();
        

        var from_date   =    $(".from_date").val()==undefined?'':$(".from_date").val(); 
        var to_date     =    $(".to_date").val()==undefined?'':$(".to_date").val();      
        var candidate_id=    $(".candidate_list option:selected").val()==undefined?'':$(".candidate_list option:selected").val();
        var rows        =    $("#rows option:selected").val();
        var mob        =     $('.mob').val()==undefined?'':$(".mob").val();
        var ref         =    $('.ref').val()==undefined?'':$(".ref").val();
        var email      =     $('.email').val()==undefined?'':$(".email").val();  
        var remain     =     $('#remain').val()==undefined?'':$("#remain").val();   
        var status     =     'pending';
        var active_case  =   $('#active_case').val()==undefined?'':$("#active_case").val();  

        var insuff_raised =  $('#insuff_raised').val()==undefined?'':$("#insuff_raised").val();    

        var search = $('.search').val();
        var insuff_status = '1';   

        var insuffs = $('#insuffs').val()==undefined?'':$("#insuffs").val();
        var service = $('#service').val();
        var sendto = $('#sendto').val();
        var jafstatus = $('#jafstatus').val();
        var jafstatus1 = $('#jafstatus1').val();
        var jafstatus2 = $('#jafstatus2').val();
        var insuff = $('#insuff').val()==undefined?'':$("#insuff").val();
        var verification_status = $('#verification_status').val();
        var verify_status = $('#verify_status').val();
        var candidates_id = $('#candidates_id').val();
        var service_name = $('#service_name').val()==undefined?'':$('#service_name').val();

        var candidate_kams_url = $('#candidate_kams_url').val();

        var jaf_from_date   =    $(".jaf_from_date").val()==undefined?'':$(".jaf_from_date").val(); 
        var jaf_to_date     =    $(".jaf_to_date").val()==undefined?'':$(".jaf_to_date").val();
      
        // var verified = [];
        // var i = 0;
        
        // var v_length=$('.verified option:selected').length;
        // $('.verified22 option:selected').each(function () {
        //     // if($(this).val()!='')
        //     verified[i++] = $(this).val();
        //     // verified += $(this).val();
           
        // });  
        
        
        // console.log(verified);
            $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);

            $.ajax(
            {
                url: '?page=' + page+'&customer_id='+user_id+'&status='+status+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&type='+type+'&check_id='+check+'&mob='+mob+'&email='+email+'&remain='+remain+'&active_case='+active_case+'&insuff_raised='+insuff_raised+'&insuff_status='+insuff_status+'&search='+search+'&insuffs='+insuffs+'&service='+service+'&sendto='+sendto+'&jafstatus='+jafstatus+'&insuff='+insuff+'&verification_status='+verification_status+'&verify_status='+verify_status+'&candidate='+candidates_id+'&jafstatus1='+jafstatus1+'&jafstatus2='+jafstatus2+'&rows='+rows+'&service_name='+service_name+'&ref='+ref+'&candidate_kams_url='+candidate_kams_url+'&jaf_from_date='+jaf_from_date+'&jaf_to_date='+jaf_to_date,
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
               // alert('No response from server');

            });
            // e.stopImmediatePropagation();
    }

    function setData(){

        // var selected=[];
        // var i=0;
        // $('.ref_list option:selected').each(function () {
        //     selected[i++] = $(this).val();
        // });

        var user_id     =    $(".customer_list").val();                
        var check       =    $(".check option:selected").val();

        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();    
        var candidate_id=    $(".candidate_list option:selected").val();                            
        var rows       =      $("#rows option:selected").val();
        var mob        =      $('.mob').val();
        var ref       =       $('.ref').val();

        var email = $('.email').val(); 

        var remain = $('#remain').val();  

        var active_case =  $('#active_case').val();   

        var insuff_raised = $('#insuff_raised').val();       

        var status = 'pending'; 

        var insuff_status = '1';

        var search = $('.search').val();

        var insuffs = $('#insuffs').val();
        var service = $('#service').val();

        var sendto = $('#sendto').val();
        var jafstatus = $('#jafstatus').val();
        var insuff = $('#insuff').val();
        var verification_status = $('#verification_status').val();
        var verify_status = $('#verify_status').val();
        var candidates_id = $('#candidates_id').val();
        var service_name = $('#service_name').val();
        var jafstatus1 = $('#jafstatus1').val();
        var jafstatus2 = $('#jafstatus2').val();
        // var candidate_arr = [];
        // var i = 0;
        

        // $('.check option:selected').each(function () {
        //     // if($(this).val()!='')
        //     candidate_arr[i++] = $(this).val();
        // });

        // alert(candidate_arr);
        
            $.ajax(
            {
                url: "{{ url('/') }}"+'/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&check_id='+check+'&candidate_id='+candidate_id+'&mob='+mob+'&ref='+ref+'&email='+email+'&remain='+remain+'&status='+status+'&active_case='+active_case+'&insuff_raised='+insuff_raised+'&insuff_status='+insuff_status+'&search='+search+'&insuffs='+insuffs+'&service='+service+'&sendto='+sendto+'&jafstatus='+jafstatus+'&insuff='+insuff+'&verification_status='+verification_status+'&verify_status='+verify_status+'&candidate='+candidates_id+'&jafstatus1='+jafstatus1+'&jafstatus2='+jafstatus2+'&rows='+rows+'&service_name='+service_name,
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

    //when click on hold button
    $(document).on('click', '.hold', function (event) {
        
        var candidate_id = $(this).attr('data-candidate');
        var business_id = $(this).attr('data-business');
        if(confirm("Are you sure want to hold this candidate ?")){
        $.ajax({
            type:'GET',
            url: "{{route('/candidates/hold')}}",
            data: {'candidate_id':candidate_id,'business_id':business_id},        
            success: function (response) {        
            console.log(response);
            
                if (response.status=='ok') {            
                
                    $('table.candidatesTable tr').find("[data-candidate='" + candidate_id + "']").fadeOut("slow");
                    
                    $('table.candidatesTable tr').find("[data-cand_id='" + candidate_id + "']").fadeOut("slow");
                    $('table.candidatesTable tr').find("[data-can_id='" + candidate_id + "']").removeClass("d-none").show();
                    $('table.candidatesTable tr').find("[data-candidate_id='" + candidate_id + "']").removeClass("d-none").show();

                    $('table.candidatesTable tr').find("[data-resend_m='" + candidate_id + "']").addClass('d-none').hide();        
                    $('table.candidatesTable tr').find("[data-resend_m='" + candidate_id + "']").fadeOut("slow");

                    $('table.candidatesTable tr').find("[data-resend='" + candidate_id + "']").addClass('d-none').hide();        
                    $('table.candidatesTable tr').find("[data-resend='" + candidate_id + "']").fadeOut("slow");

                } else {
                    
                }
            },
            error: function (response) {
               console.log(response);
            }
            // error: function (xhr, textStatus, errorThrown) {
            //     alert("Error: " + errorThrown);
            // }
        });

        }
        return false;

    });

    //when click on hold button
    $(document).on('click', '.resume', function (event) {
        
        var candidate_id = $(this).attr('data-candidate_id');
        var business_id = $(this).attr('data-business_id');
        if(confirm("Are you sure want to Resume this candidate ?")){
        $.ajax({
            type:'GET',
            url: "{{route('/candidates/resume')}}",
            data: {'candidate_id':candidate_id,'business_id':business_id},        
            success: function (response) {        
            console.log(response);
            
                if (response.status=='ok') {            
                
                    $('table.candidatesTable tr').find("[data-candidate_id='" + candidate_id + "']").fadeOut("slow");
                    
                    $('table.candidatesTable tr').find("[data-can_id='" + candidate_id + "']").fadeOut("slow");
                    $('table.candidatesTable tr').find("[data-cand_id='" + candidate_id + "']").removeClass("d-none").show();
                    $('table.candidatesTable tr').find("[data-candidate='" + candidate_id + "']").removeClass("d-none").show();

                    $('table.candidatesTable tr').find("[data-resend='" + candidate_id + "']").removeClass("d-none").show();
                    $('table.candidatesTable tr').find("[data-resend='" + candidate_id + "']").fadeIn("slow");

                    $('table.candidatesTable tr').find("[data-resend_m='" + candidate_id + "']").removeClass("d-none").show();
                    $('table.candidatesTable tr').find("[data-resend_m='" + candidate_id + "']").fadeIn("slow");
                } else {
                    
                }
            },
            error: function (response) {
               console.log(response);
            }
        });

        }
        return false;

    });

    //

    $(document).on('click', '.deleteRow', function (event) {
            var _this = $(this);
            var candidate_id = _this.attr('data-id');
            // if(confirm("Are you sure want to delete?")){
            //     $.ajax({
            //         type:'GET',
            //         url: "{{route('/candidates/delete')}}",
            //         data: {'candidate_id':candidate_id},        
            //         success: function (response) {        
            //         //console.log(response);
                    
            //             if (response.status=='ok') {            
                        
            //                 $('table.candidatesTable tr').find("[data-id='" + candidate_id + "']").parent().parent().fadeOut("slow");

            //                 // $('table.candidatesTable tbody').find("[candidate-d_id='" + candidate_id + "']").fadeOut("slow");

            //             } else {
                            
            //             }
            //         },
            //         error: function (response) {
            //             console.log(response);
            //         }
            //         // error: function (xhr, textStatus, errorThrown) {
            //         //     alert("Error: " + errorThrown);
            //         // }
            //     });

            // }
            // return false;

            swal({
            // icon: "warning",
            type: "warning",
            title: "Are you sure want to delete?",
            text: "",
            dangerMode: true,
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "YES",
            cancelButtonTexswalt: "CANCEL",
            closeOnConfirm: false,
            closeOnCancel: false,
            },
            function(e){
               if(e==true)
               {
                    $.ajax({
                        type:'GET',
                        url: "{{route('/candidates/delete')}}",
                        data: {'candidate_id':candidate_id},        
                        success: function (response) {        
                        //console.log(response);
                        
                            if (response.status=='ok') {            
                            
                                $('table.candidatesTable tr').find("[data-id='" + candidate_id + "']").parent().parent().fadeOut("slow");

                                // $('table.candidatesTable tbody').find("[candidate-d_id='" + candidate_id + "']").fadeOut("slow");

                                toastr.success("The candidate has been deleted successfully.");

                            } else {
                                
                            }

                            swal.close();
                        },
                        error: function (response) {
                            console.log(response);
                        }
                        // error: function (xhr, textStatus, errorThrown) {
                        //     alert("Error: " + errorThrown);
                        // }
                       
                    });
               }
               else
               {
                    swal.close();
               }
            });

    });

    $(document).on('change','.check_p',function(){
    
        var candidate_id = [];
        var i = 0;

        var type= $('#check_p').val();

        $('.priority:checked').each(function () {
            candidate_id[i++] = $(this).val();
        });

        var count = candidate_id.length;

        if(count>0)
        {
            $.ajax({
                type:"POST",
                url: "{{ url('/candidates/updateCandidate') }}",
                data:{"_token": "{{ csrf_token() }}",'candidate_id':candidate_id,'type':type},      
                success: function (response) {
                    
                    location.reload();

                },
                error: function (xhr, textStatus, errorThrown) {
                    
                }
            });  
        }

    });

      //when click on resendmail button
    $(document).on('click', '.resendMail', function (event) {
        
        // var customer_id = $(this).attr('data-customer_id');
        var _this =$(this);
        var candidate_id=$(this).attr('data-id');
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Sending...';
        _this.addClass('disabled-link');
        if (_this.html() !== loadingText) {
            _this.html(loadingText);
        }

        $.ajax({
            type:'GET',
            url: "{{route('/candidates/resend_mail')}}",
            data: {'candidate_id':candidate_id},        
            success: function (response) {        
            console.log(response);
                window.setTimeout(function(){
                    _this.removeClass('disabled-link');
                    _this.html('<i class="far fa-envelope"></i> Re-send Mail');
                },2000);
                if (response.status=='ok') {            
                    var name=response.name;
                    toastr.success("Mail Sent Succesfully to "+name);
                } 
                else {
                    toastr.error("Something Went Wrong !");
                }
            },
            error: function (response) {
               console.log(response);
            }
            // error: function (xhr, textStatus, errorThrown) {
            //     alert("Error: " + errorThrown);
            // }
        });

        // }
        return false;

    }); 



    $(document).on('click','.send-link',function(){
        var _this = $(this);
        var send_link_id = $(this).attr('candidate-id');
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
        if (_this.html() !== loadingText) {
               _this.html(loadingText);
         }
       // alert(reports_id);
        $.ajax({
               type: 'GET', 
               url:"{{ url('/candidates/send_link_mail') }}",
               data: {'send_link_id':send_link_id},  
               success: function (response) {
               if(response.success==true) {  
                    _this.html('Send Link');
                     toastr.success("Send Link on your registered mail id");
               }
               //show the form validates error
               if(response.success==false ) { 
                    _this.html('Send Link');
                    toastr.success("Please add your email id");                            
                    //  for (control in response.errors) {   
                    //     $('#error-'+control).html(response.errors[control]);
                    //  }
               }
            },
            
        });
    });

   
    
    function loaderHtml()
    {
        return "<div class='fa-3x' style='min-height:200px;display: flex;align-items: center;justify-content: center;'><i class='fas fa-spinner fa-pulse'></i></div>";
    }

</script>
<!--  -->

@endsection
