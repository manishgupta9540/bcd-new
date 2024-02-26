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
            <li>Customer</li>
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

            @if ($message = Session::get('success'))
              <div class="col-md-12">   
                <div class="alert alert-success">
                <strong>{{ $message }}</strong> 
                </div>
              </div>
              @endif
              
             <div class="col-md-4">
                 <h4 class="card-title mb-1"> Customers </h4> 
                <p> List of all customers </p>        
            </div>
            <div class="col-md-4 text-center">
                <h2 class="text-center font-weight-bolder">[Confidential]</h2>   

            </div>
                <div class="col-md-4">           
                <div class="btn-group" style="float:right">        
                    <!-- <button class="btn btn-secondary btn-lg dropdown-toggle" id="btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">   Actions  </button>
                     <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div> -->
                   
                     @php
                     $ADD_ACCESS    = false;
                     $EDIT_ACCESS   = false;
                     $VIEW_ACCESS   = false;
                     $VIEW_CUSTOMER_ACCESS =false;
                    //  $DELETE_ACCESS = false;
                     // dd($ADD_ACCESS);
                     $ADD_ACCESS    = Helper::can_access('Add Customers','');

                     $EDIT_ACCESS    = Helper::can_access('Edit Customers','');
                     $VIEW_ACCESS   = Helper::can_access('View Candidates List','');
                     $VIEW_CUSTOMER_ACCESS =Helper::can_access('View Customers List','');
                     // $DELETE_ACCESS = Helper::can_access('Delete Category');
                   @endphp 
                    @if($ADD_ACCESS)

                     

                         <a class="btn btn-success" href="{{ route('/customers/create') }}" > <i class="fa fa-plus"></i> Add New </a>              
                         @endif
              
                        {{-- @endif --}}
                
                 {{-- @endif --}}
                   
                     {{-- <a class="btn btn-success" href="{{ route('/customers/create') }}" > <i class="fa fa-plus"></i> Add New </a>               --}}
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
                    <label>Company Name</label><br>
                    <select class="form-control customer_list select" name="candidate" id="candidate">
                        <option> All </option>
                        @foreach($clients as $item)
                            <option value="{{$item->id}}"> {{ ucfirst($item->company_name)}} </option>
                        @endforeach
                    </select>
                    
                    {{-- <input class="form-control candidate_list" type="text" placeholder="name"> --}}
                </div>
                <div class="col-md-2 form-group mb-1">
                    <label>Contact Person</label><br>
                    <select class="form-control contact_list select1" name="candidate" id="candidate2">
                        <option> All </option>
                        @foreach($clients as $item)
                            <option value="{{$item->id}}"> {{ $item->name}} </option>
                        @endforeach
                    </select>
                    
                    {{-- <input class="form-control candidate_list" type="text" placeholder="name"> --}}
                </div>
                <div class="col-md-2 form-group mb-1">
                    <label>Phone number </label>
                    <input class="form-control mob" type="text" placeholder="Phone">
                </div>
                <div class="col-md-2 form-group mb-1">
                    <label>Email id</label>
                    <input class="form-control email" type="email" placeholder="Email">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-danger  resetBtn" style="padding: 7px;margin: 18px 0px;"> <i class="fas fa-refresh"></i>  Reset </button>
                </div>
                <div class="col-md-1">
                <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                </div>
            </div>

            <div id="candidatesResult">
                @include('admin.customers.ajax')
            </div>
            
            </div>
            
            <input type="hidden" name="active_case" id="active_case" value="{{$active_case}}">
            <input type="hidden" name="kams_user" id="kams_user" value="{{$kams_user}}">
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
  
</div>
<script type="text/javascript">
$(document).ready(function(){
    $("#candidate").select2();
    $("#candidate2").select2();
    
    
    // $('.filter0search').click(function(){
    //         $('.search-drop-field').toggle();
    //     });
    $(document).on('click', '.status', function (event) {
      
      var id = $(this).attr('data-id');
      var type =$(this).attr('data-type');
       
      var name = $(this).attr('data-name');
      var action = '';
      var type_decode =atob(type);
        //   alert(type_decode);
      if (type_decode== 'enable') {
          var action = 'activate';
      }
      if (type_decode== 'disable') {
          var action = 'deactivate';
      }
      swal({
      // icon: "warning",
      type: "warning",
      title: 'Are you want to '+ action +' account for '+name+'?',
      text: "",
      dangerMode: true,
      showCancelButton: true,
      confirmButtonColor: "#007358",
      confirmButtonText: "YES",
      cancelButtonText: "CANCEL",
      closeOnConfirm: false,
      closeOnCancel: false
      },
      function(e){
         if(e==true)
         {
               $.ajax({
                  type:'POST',
                  url: "{{url('/customers/status')}}",
                  data: {"_token" : "{{ csrf_token() }}",'id':id,'type':type},        
                  success: function (response) {        
                  
                        if(response.success==false)
                        {
                           toastr.error("Firstly, Complete or Assign Task to any other user ");
                        }
                     if (response.success) { 
                           // window.setTimeout(function(){
                           //    location.reload();
                           // },2000);
                           // toastr.success("Status Changed Successfully");

                           if(response.type=='enable')
                           {
                              $('table.userTable tr').find("[data-ac='" + id + "']").fadeIn("slow");
                              $('table.userTable tr').find("[data-ac='" + id + "']").removeClass("d-none");

                              $('table.userTable tr').find("[data-dc='" + id + "']").fadeOut("slow");

                              $('table.userTable tr').find("[data-dc='" + id + "']").addClass("d-none");

                              $('table.userTable tr').find("[data-a='" + id + "']").fadeOut("slow");
                              $('table.userTable tr').find("[data-a='" + id + "']").addClass("d-none");

                              $('table.userTable tr').find("[data-d='" + id + "']").fadeIn("slow");

                              $('table.userTable tr').find("[data-d='" + id + "']").removeClass("d-none");

                              
                           }
                           else if(response.type=='disable')
                           {
                              $('table.userTable tr').find("[data-dc='" + id + "']").fadeIn("slow");
                              $('table.userTable tr').find("[data-dc='" + id + "']").removeClass("d-none");

                              $('table.userTable tr').find("[data-ac='" + id + "']").fadeOut("slow");

                              $('table.userTable tr').find("[data-ac='" + id + "']").addClass("d-none");

                              $('table.userTable tr').find("[data-d='" + id + "']").fadeOut("slow");
                              $('table.userTable tr').find("[data-d='" + id + "']").addClass("d-none");

                              $('table.userTable tr').find("[data-a='" + id + "']").fadeIn("slow");

                              $('table.userTable tr').find("[data-a='" + id + "']").removeClass("d-none");
                           }
                     } 
                     else {
                           
                     }

                     swal.close();
                     
                  },
                  error: function (xhr, textStatus, errorThrown) {
                     // alert("Error: " + errorThrown);
                  }
                  
               });
         }
         else
         {
               swal.close();
         }
      });

    });
    $(document).on('click', '.resetBtn' ,function(){

        $("input[type=text], textarea").val("");
        //   $('.customer_list').val('');
        //    $('.candidate').val('');
        //    $('.user_list').val('');
        $('#candidate').val(null).trigger('change');
        $('#candidate2').val(null).trigger('change');
        // $('#user').val(null).trigger('change');
        // $('#remain').val('');
        // $('#active_case').val('');
        // $('#insuff_raised').val('');
        $('.email').val('');
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

    var uriNum = location.hash;
    pageNumber = uriNum.replace("#", "");
    // alert(pageNumber);
    getData(pageNumber);

    // $('.customer_list').on('select2:select', function (e){
    //     var data = e.params.data.id;
    //     //loader
    //     $("#overlay").fadeIn(300);　
    //     getData(0);
    //     setData();
    //     event.preventDefault();
    // });

    // filterBtn
    $(document).on('change','.customer_list, .contact_list, .from_date, .to_date, .mob,.ref,.email', function (e){    
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
    var cust_id     =    $(".customer_list").val();                
    // var check       =    $(".check option:selected").val();
    // var type        =    $('#check_p').val();
    var kamsuser    =    $('#kams_user').val();

    var from_date   =    $(".from_date").val(); 
    var to_date     =    $(".to_date").val();      
    var contact_id=    $(".contact_list option:selected").val();

    var mob = $('.mob').val();
    // var ref = $('.ref').val();
    var email = $('.email').val();  

    var active_case = $('#active_case').val();  
    

        $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);

        $.ajax(
        {
            url: '?page=' + page+'&customer_id='+cust_id+'&status='+status+'&from_date='+from_date+'&to_date='+to_date+'&contact_id='+contact_id+'&mob='+mob+'&email='+email+'&active_case='+active_case+'&kams_user='+kamsuser,
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

    var cust_id     =    $(".customer_list").val();                
    var check       =    $(".check option:selected").val();

    var from_date   =    $(".from_date").val(); 
    var to_date     =    $(".to_date").val();    
    var contact_id=    $(".contact_list option:selected").val();                            

    var mob = $('.mob').val();
    // var ref = $('.ref').val();

    var email = $('.email').val(); 

    // var remain = $('#remain').val();  

    var active_case =  $('#active_case').val();   
    var kamsuser    =    $('#kams_user').val();

    // var insuff_raised = $('#insuff_raised').val();       

    // var status = 'pending'; 

    // var insuff_status = '1';
        $.ajax(
        {
            url: "{{ url('/') }}"+'/candidates/setData/?customer_id='+cust_id+'&from_date='+from_date+'&to_date='+to_date+'&contact_id='+contact_id+'&mob='+mob+'&email='+email+'&active_case='+active_case+'&kams_user='+kamsuser,
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