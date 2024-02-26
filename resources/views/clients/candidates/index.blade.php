@extends('layouts.client')
<style>
    .disabled-link {
      pointer-events: none;
    }
</style>
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
            <li>Candidates</li>
            </ul>
        </div>
        <!-- ============Back Button ============= -->
        <div class="col-sm-1 back-arrow">
            <div class="text-right">
            <a href="{{ url('/my/home') }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
            </div>
        </div>
    </div>
    <!-- ./breadbrum --> 
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

                    <div class="col-md-4">
                        <h4 class="card-title mb-1"> Candidates </h4> 
                        <p> List of all Candidates </p>        
                    </div>
                    <div class="col-md-3">
                        <span>Total Candidates: <span > {{ $tota_candidates }}</span> </span>
                    </div>
                    <div class="col-md-5">           
                        <div class="btn-group " style="float:right">  
                            <a href="#" class="filter0search btn"><i class="fa fa-filter xyz"></i></a>
                            @php
                            $ADD_ACCESS    = false;
                            // $EDIT_ACCESS   = false;
                            // $DELETE_ACCESS = false;
                            // dd($ADD_ACCESS);
                            $ADD_ACCESS    = Helper::can_access('Add Candidates','/my');
                            // $EDIT_ACCESS   = Helper::can_access('Edit ');
                            // $DELETE_ACCESS = Helper::can_access('Delete Category');
                          @endphp 

                          @if($ADD_ACCESS)
                          <a class="btn btn-success" href="{{ url('my/candidates/create') }}" > <i class="fa fa-plus"></i> Add New </a>              

                          @endif  
                           
                            {{-- @if ($user_type== 'client') --}}                        
                
                            {{-- <a class="btn btn-success " href="{{ url('/my/candidates/create') }}" > <i class="fa fa-plus"></i> Add New </a>               --}}
                        </div>
                    </div>
                </div>
                    <!-- search bar -->
                    <div class="search-drop-field z" id="search_drop_ff">
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
                    
                    
                    <div class="col-md-2 form-group mb-1">
                        <label>JAF Send to</label>
                        <select class="form-control "  name="remain" id="remain">
                            <option value="">All</option>
                            <option value="customer" >Customer</option>
                            <option value="coc" >COC</option>
                            <option value="candidate" >Candidate</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group mb-1">
                        <label>JAF filled</label>
                        <select class="form-control" name="jaf_filled" id="active_case" >
                            <option value="">All</option>
                            <option  value="filled" <?=$filled=="filled"?"selected":""?>>Filled</option>
                            <option  value="draft" <?=$filled=="draft"?"selected":""?>>Draft</option>
                            <option  value="pending"  <?=$filled=="pending"?"selected":""?> >Pending</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group mb-1">
                        <label>Insuff Raised In</label>
                        <select class="form-control" name="insuff_raised" id="insuff_raised" >
                            <option value="">All</option>
                            @foreach($array_result as $result)
                             <option value="{{$result['check_id']}}"> {{$result['check_name']}} ({{$result['insuf']}})</option>
                             @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group mb-1">
                        <label>Candidate Name</label><br>
                        <select class="form-control candidate_list select" name="candidate" id="candidate">
                            <option> All </option>
                            @foreach($candidates as $candidate)
                             <option value="{{$candidate->id}}"> {{ $candidate->name}} </option>
                             @endforeach
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
                    <!-- export data -->
                    
                   
                    <!-- ./export data -->
            
                    <!-- data   --> 
                    <input type="hidden" name="active_case" id="active_case" value={{$filled}}>
                    <input type="hidden" name="active_case1" id="active_case1" value={{$pending}}>
                    <input type="hidden" name="active_case2" id="active_case2" value={{$draft}}>
                    <input type="hidden" name="sendto" id="sendto" value={{$send_to}}>
                    <input type="hidden" name="jafstatus" id="jafstatus" value={{$jafstatus}}>
                    <input type="hidden" name="service" id="service" value={{$service}}>
                    <input type="hidden" name="insuffs" id="insuffs" value={{$insuffs}}>
                    <input type="hidden" name="verification_status" id="verification_status" value={{$verification_status}}>
                    <input type="hidden" name="verify_status" id="verify_status" value={{$verify_status}}>

                    <input type="hidden" name="insuff" id="insuff" value={{$insuff}}>

                    <div id="candidatesResult">
                        @include('clients.candidates.ajax')   
                    </div> 
                    <!--  -->
               </div>
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
       
    //


    // $(window).click(function(e) {
    // // var x = document.getElementById('search_drop_ff');&& (!$(e.target).hasClass("filterButton"))


    // if ((!$(e.target).hasClass("xyz")) && (!$(e.target).hasClass("z")) ) {
    //     alert('abc');
       
    //     }
    // });
    $(document).on('click', '.resetBtn' ,function(){

        $("input[type=text], textarea").val("");
        //   $('.customer_list').val('');
        //    $('.candidate').val('');
        //    $('.user_list').val('');
        $('#candidate').val(null).trigger('change');
        // $('#customer').val(null).trigger('change');
        // $('#user').val(null).trigger('change');
        // $('#service').val('');
        $('#remain').val('');
        $('#insuff_raised').val('');
        $('#active_case').val('');
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
    //
    $('.customer_list').on('select2:select', function (e){
        var data = e.params.data.id;
        //loader
        $("#overlay").fadeIn(300);　
        getData(0);
        setData();
        event.preventDefault();
    });

    

    // $(document).on('change','', function (e){    
      
    // });
    
    // filterBtn
    $(document).on('change','.customer_list, .candidate_list, .from_date, .to_date,#active_case,#pending,#draft,.email,.mob,.ref,#remain,#jafstatus,#insuff_raised,.search', function (e){    
        $("#overlay").fadeIn(300);　
        getData(0);
        e.preventDefault();
        // alert("jkej");
    });
    var x = document.getElementById('search_drop_ff');
    $(document).on('click','.filterBtn', function (e){

        $("#overlay").fadeIn(300);　
        getData(0);
        e.preventDefault();
        x.style.display = 'none';
       
    });
     
    // $(document).on('change','#sendto',function() {
    
    // var sendto = $('#sendto').val();
    // var jafstatus = 'pending';
    
    
    
    // });
    
    // //
    $(document).on('change','.customer_list',function(e) {
            e.preventDefault();
            $('.candidate_list').empty();
            $('.candidate_list').append("<option value=''>-Select-</option>");
            var customer_id = $('.customer_list option:selected').val();
            $.ajax({
            type:"POST",
            url: "{{ url('/my/customers/candidates/getlist') }}",
            data: {"_token": "{{ csrf_token() }}",'customer_id':customer_id},      
            success: function (response) {
                console.log(response);
                if(response.success==true  ) {   
                    $.each(response.data, function (i, item) {
                      $(".candidate_list").append("<option value='"+item.id+"'> "+item.id+"-" + item.first_name +' '+item.last_name+ "</option>");
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
    $(document).on('click','#exportExcel',function(){
        setData();
     var check = $(".check option:selected").val();
      if(check !=''){
        //
            var user_id     =    $(".customer_list").val();                
            var check       =    $(".check option:selected").val();
            var from_date   =    $(".from_date").val(); 
            var to_date     =    $(".to_date").val();    
            var candidate_id=    $(".candidate_list option:selected").val();                            
    
            $.ajax(
            {
                url: "{{ url('/') }}"+'/my/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&check_id='+check+'&candidate_id='+candidate_id,
                type: "get",
                datatype: "html",
            })
            .done(function(data)
            {
               console.log(data);
               var path = "{{ route('/jaf-export')}}";
                window.open(path);
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                //alert('No response from server');
            });
        //
       
      }else{
          alert('Please select a check to export! ');
         }
      });
    
    });


    
    function getData(page){
        //set data
        var user_id     =    $(".customer_list").val();                
        var check       =    $(".check option:selected").val();
        var active_case =  $('#active_case').val();
        // alert(active_case);
        var active_case1 = $('#active_case1').val();
        var active_case2 = $('#active_case2').val();
        var sendto = $('#sendto').val();
        var jafstatus = $('#jafstatus').val();
        var email = $('.email').val();
        var mob = $('.mob').val();
        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();      
        var candidate_id=    $(".candidate_list option:selected").val();
        var ref = $('.ref').val();
        var insuff = $('#insuff').val();
        var insuffs = $('#insuffs').val();
        var service = $('#service').val();
        var remain = $('#remain').val();
        var status = 'pending';
        var insuff_raised = $('#insuff_raised').val();
        var verification_status = $('#verification_status').val();
        var verify_status = $('#verify_status').val();

        var insuff_status = '1';
        var search = $('.search').val();
        //    var pendi =  'ty'; insuff_raised   verification_status               
            // alert(111);
            $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
    
            $.ajax(
            {
                url: '?page=' + page+'&customer_id='+user_id+'&status='+status+'&remain='+remain+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&check_id='+check+'&active_case='+active_case+'&sendto='+sendto+'&jafstatus='+jafstatus+'&email='+email+'&mob='+mob+'&ref='+ref+'&insuff='+insuff+'&insuffs='+insuffs+'&service='+service+'&insuff_raised='+insuff_raised+'&insuff_status='+insuff_status+'&verification_status='+verification_status+'&verify_status='+verify_status+'&search='+search+'&active_case1='+active_case1+'&active_case2='+active_case2,
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
        var check       =    $(".check option:selected").val();
        var active_case =  $('#active_case').val();
        // alert(active_case);
        var active_case1 = $('#active_case1').val();
        var active_case2 = $('#active_case2').val();
        var email = $('.email').val();
        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();    
        var candidate_id=    $(".candidate_list option:selected").val();                            
        var sendto = $('#sendto').val();
        var jafstatus = $('#jafstatus').val();

        var mob = $('.mob').val();
        var ref = $('.ref').val();
        var insuff = $('#insuff').val();
        var insuffs = $('#insuffs').val();
        var service = $('#service').val();
        var remain = $('#remain').val();
        var status = 'pending';
        var insuff_raised = $('#insuff_raised').val();
        var verification_status = $('#verification_status').val();
        var verify_status = $('#verify_status').val();
        var search = $('.search').val();
        var insuff_status = '1';
            $.ajax(
            {
                url: "{{ url('/') }}"+'/my/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&check_id='+check+'&candidate_id='+candidate_id+'&active_case='+active_case+'&sendto='+sendto+'&jafstatus='+jafstatus+'&email='+email+'&mob='+mob+'&ref='+ref+'&insuff='+insuff+'&insuffs='+insuffs+'&service='+service+'&status='+status+'&remain='+remain+'&insuff_raised='+insuff_raised+'&insuff_status='+insuff_status+'&verification_status='+verification_status+'&verify_status='+verify_status+'&search='+search+'&active_case1='+active_case1+'&active_case2='+active_case2,
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
    $(document).on('click', '.deleteRow', function (event) {
        var _this = $(this);
        var candidate_id = _this.attr('data-id');
        // if(confirm("Are you sure want to delete?")){
        // $.ajax({
        //     type:'POST',
        //     url: "{{route('/my/candidates/delete')}}",
        //     data: {"_token": "{{ csrf_token() }}",'candidate_id':candidate_id},        
        //     success: function (response) {        
        //     console.log(response);
            
        //         if (response.status=='ok') {            
                
        //             $('table.candidatesTable tr').find("[data-id='" + candidate_id + "']").parent().parent().fadeOut("slow");
    
        //         } else {
                    
        //         }
        //     },
        //     error: function (response) {
        //         console.log(response);
        //     }
        // });
    
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
            cancelButtonText: "CANCEL",
            closeOnConfirm: false,
            closeOnCancel: false,
            },
            function(e){
               if(e==true)
               {
                    $.ajax({
                        type:'POST',
                        url: "{{route('/my/candidates/delete')}}",
                        data: {"_token": "{{ csrf_token() }}",'candidate_id':candidate_id},        
                        success: function (response) {        
                       // console.log(response);
                        
                            if (response.status=='ok') {            
                            
                                $('table.candidatesTable tr').find("[data-id='" + candidate_id + "']").parent().parent().fadeOut("slow");
                
                            } else {
                                
                            }

                            swal.close();
                        },
                        error: function (response) {
                            console.log(response);
                        }
                    });
               }
               else
               {
                    swal.close();
               }
            }
        );
    
    });
    
    
    // $(document).on('click','.check_p',function(){
        
    // var candidate_id = [];
    // var i = 0;
    
    // var type= $('#check_p').val();
    
    // $('.priority:checked').each(function () {
    //     candidate_id[i++] = $(this).val();
    // });
    
    // var count = candidate_id.length;
    
    //  if(count>0)
    //  {
    //   $.ajax({
    //         type:"POST",
    //         url: "{{ url('/candidates/updateCandidate') }}",
    //         data:{"_token": "{{ csrf_token() }}",'candidate_id':candidate_id,'type':type},      
    //         success: function (response) {
                
    //             location.reload();
    
    //         },
    //         error: function (xhr, textStatus, errorThrown) {
                
    //         }
    //   });  
    // }
    
    // });  

    //when click on hold button
$(document).on('click', '.hold', function (event) {
    
    var candidate_id = $(this).attr('data-candidate');
    var business_id = $(this).attr('data-business');
    if(confirm("Are you sure want to hold this candidate ?")){
    $.ajax({
        type:'GET',
        url: "{{route('/my/candidates/hold')}}",
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
        url: "{{route('/my/candidates/resume')}}",
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
            url: "{{route('/my/candidates/resend_mail')}}",
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
        });

        // }
        return false;

    });

    </script>
    

@endsection
