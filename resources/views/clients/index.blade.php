@extends('layouts.client')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content"> 
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
                        <div class="btn-group" style="float:right">  
                            
                            <?php 
                            $user_type = Auth::user()->user_type;
                            // dd($role);

                            ?>

                            <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>
                            @if ($user_type== 'client')

                     

                         <a class="btn btn-success" href="{{ url('my/candidates/create') }}" > <i class="fa fa-plus"></i> Add New </a>              
                        
                 @else
                        <?php $user = Auth::user()->role;
                        $business_id =Auth::user()->business_id;
                        // dd($user);
                        $childs =Helper::get_user_permission($user,$business_id);
                        $role = Helper::get_page_permission('28');?>
                                {{-- @if ($user=='') --}}
                        @foreach ($role as $key)

                        @if (in_array($key->id,json_decode($childs)) && $key->action_title == 'Add New' && $key->status == '1')


                        <a class="btn btn-success" href="{{ url('my/candidates/create') }}" > <i class="fa fa-plus"></i> Add New </a>              
                        @endif

                     @endforeach 
                        {{-- @endif --}}
                
                 @endif
        
                            {{-- <a class="btn btn-success " href="{{ url('/my/candidates/create') }}" > <i class="fa fa-plus"></i> Add New </a>               --}}
                        </div>
                    </div>
                </div>
                    <!-- search bar -->
                    <div class="search-drop-field">
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
                        <input class="form-control commonDatePicker" type="text" placeholder="phone">
                    </div>
                    <div class="col-md-2 form-group mb-1">
                        <label>Candidate reference number </label>
                        <input class="form-control commonDatePicker" type="text" placeholder="reference number">
                    </div>
                    <div class="col-md-2 form-group mb-1">
                        <label>Email id</label>
                        <input class="form-control commonDatePicker" type="email" placeholder="email">
                    </div>
                    <div class="col-md-2 form-group mb-1">
                        <label>Candidate Name</label>
                        <input class="form-control candidate_list" type="text" placeholder="name">
                    </div>
                    <div class="col-md-2 form-group mb-1">
                        <label>JAF filled by</label>
                        <select class="form-control">
                            <option>Customer</option>
                            <option>COC</option>
                            <option>Candidate</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group mb-1">
                        <label>JAF filled</label>
                        <select class="form-control">
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                
                    <div class="col-md-2">
                    <button class="btn btn-primary search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                    </div>
                </div>
            </div>
                    <!-- export data -->
                    
                   
                    <!-- ./export data -->
            
                    <!-- data  -->
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
    $(document).on('change','.customer_list, .candidate_list, .from_date, .to_date', function (e){    
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
        // var type        =    $('#check_p').val();
    
        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();      
        var candidate_id=    $(".candidate_list option:selected").val();
    
                              
    
            $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
    
            $.ajax(
            {
                url: '?page=' + page+'&customer_id='+user_id+'&status='+status+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&check_id='+check,
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
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                //alert('No response from server');
            });
    
    }
    
    //
    $(document).on('click', '.deleteRow', function (event) {
        
        var candidate_id = $('.deleteRow').attr('data-id');
        if(confirm("Are you sure want to delete?")){
        $.ajax({
            type:'GET',
            url: "{{route('/candidates/delete')}}",
            data: {'candidate_id':candidate_id},        
            success: function (response) {        
            console.log(response);
            
                if (response.status=='ok') {            
                
                    $('table.candidatesTable tr').find("[data-id='" + candidate_id + "']").parent().parent().fadeOut("slow");
    
                } else {
                    
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                alert("Error: " + errorThrown);
            }
        });
    
        }
        return false;
    
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
    
    </script>

@endsection
