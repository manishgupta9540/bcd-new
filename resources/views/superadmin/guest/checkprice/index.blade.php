@extends('layouts.superadmin')
@section('content')
<style>
    .disabled-link
    {
        pointer-events: none;
    }
</style>
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
      <div class="col-md-12">

        <div class="row">
            <div class="col-sm-11">
                <ul class="breadcrumb">
                <li>
                    <a href="{{ url('/app/home') }}">Dashboard</a>
                </li>
                <li>Guest Users</li>
                </ul>
            </div>
            <!-- ============Back Button ============= -->
            <div class="col-sm-1 back-arrow">
                <div class="text-right">
                <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
                </div>
            </div>
        </div>

         @if ($message = Session::get('success'))
            
               <div class="alert alert-success">
               <strong>{{ $message }}</strong> 
               </div>
            
         @endif
         <div class="card text-left">
            <div class="card-body">
                @include('superadmin.guest.menu')
               <div class="row pt-3">
                  <div class="col-md-8">
                     <h4 class="card-title mb-1"> Guest Check Price</h4>
                     <p> List of all Check Price</p>
                  </div>
                  <div class="col-md-4">
                     <div class="btn-group" style="float:right">
                        <span><a href="#" class="filter0search"><i class="fa fa-filter"></i></a></span>
                     </div>
                  </div>
               </div>
               <div class="search-drop-field pb-3" id="search-drop">
                    <div class="row">
                        {{-- <div class="col-md-2 form-group mb-1">
                            <label> From date </label>
                            <input class="form-control from_date commonDatepicker" type="text" placeholder="From date">
                        </div>
                        <div class="col-md-2 form-group mb-1">
                            <label> To date </label>
                            <input class="form-control to_date commonDatepicker" type="text" placeholder="To date">
                        </div> --}}
                        <div class="col-md-2 form-group mb-1 level_selector">
                            <label> Service Name </label>
                            <select class="form-control service_list select" name="service" id="service">
                                <option> All </option>
                                @foreach ($services as $service)
                                    <option value="{{$service->id}}">{{$service->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-md-3 form-group mb-1 level_selector">
                            <label>Status</label><br>
                            <select class="form-control status_list select " name="status" id="status">
                                <option> All </option>
                                <option value="1">Active</option>
                                <option value="0">Deactive</option>
                            </select>
                        </div> --}}
                        <div class="col-md-2">
                            <button class="btn btn-primary search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                        </div>
                    </div>
               </div>
                <div id="candidatesResult">
                   @include('superadmin.guest.checkprice.ajax')
                </div>
            </div>
         </div>
      </div>
   </div>
   </div>
</div>

<div class="modal" id="edit_price">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Edit Price</h4>
             <button type="button" class="close" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
          </div>
          <!-- Modal body -->
          <form method="post" action="{{url('app/guest/checkprice/edit')}}" id="checkpriceupdate">
          @csrf
            <input type="hidden" name="id" id="id">
             <div class="modal-body">
             <div class="form-group">
                <label for="label_name"> Service Name :</label>
                <span style="margin-bottom: 2px;" class="text-dark service_name" id="service_name"></span> 
             </div>
                <div class="form-group">
                      <label for="label_name">New Price :</label>
                      <input type="text" id="price" name="price" class="form-control price" placeholder="Enter Price"/>
                      <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-price"></p> 
                </div>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-disable">Submit </button>
                <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
             </div>
          </form>
       </div>
    </div>
 </div>
@stack('scripts')
<script type="text/javascript">
   //
$(document).ready(function() {
 

    $(".select").select2();
    $('.filter0search').click(function(){
         $('.search-drop-field').toggle();
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

    $(document).on('change','.from_date, .to_date, .service_list', function (e){    
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

    $(document).on('click','.editpricebtn',function(){
        var id=$(this).attr('data-id');
        $('.form-control').removeClass('is-invalid');
        $('.error-container').html('');
        $('#edit_price').modal({
            backdrop: 'static',
            keyboard: false
        });
        $.ajax({
            type: 'GET',
            url: "{{ url('/app/guest/checkprice/edit') }}",
            data: {'id':id},        
            success: function (data) {
                // console.log(data);
                $("#checkpriceupdate")[0].reset();
                if(data !='null')
                {              
                    //check if primary data 
                    $('#id').val(id);
                    $('.service_name').html(data.result.name);
                    $('#price').val(data.result.price);
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                // alert("Error: " + errorThrown);
            }
        });
    });

    $(document).on('submit', 'form#checkpriceupdate', function (event) {
    
        $("#overlay").fadeIn(300);　
        event.preventDefault();
        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        var $btn = $(this);
        $('.error-container').html('');
        $('.form-control').removeClass('is-invalid');
        $('.btn-disable').attr('disabled',true);
        $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                // console.log(data);
                $('.error-container').html('');
                
                window.setTimeout(function(){
                    $('.btn-disable').attr('disabled',false);
                },2000);
                if (data.fail && data.error_type == 'validation') {
                        
                        //$("#overlay").fadeOut(300);
                        for (control in data.errors) {
                        $('input[name='+control+']').addClass('is-invalid');
                        $('#error-' + control).html(data.errors[control]);
                        }
                } 
                if (data.fail && data.error == 'yes') {
                    
                    $('#error-all').html(data.message);
                }
                if (data.fail == false) {
                    toastr.success("Price Updated Successfully");
                    window.setTimeout(function(){
                        location.reload();
                    },2000);
                    
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                
                alert("Error: " + errorThrown);

            }
        });
        event.stopImmediatePropagation();
        return false;

    });
     
    
});
    function getData(page){
        //set data
        // var status     =    $(".status_list").val();                

        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();
        var service_id  =    $(".service_list option:selected").val();
          

            $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);

            $.ajax(
            {
                url: '?page=' + page+'&status='+status+'&from_date='+from_date+'&to_date='+to_date+'&service_id='+service_id,
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

        // var status     =    $(".status_list").val();                
      //   var check       =    $(".check option:selected").val();

        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();  
        var service_id  =    $(".service_list option:selected").val();  
        
            $.ajax(
            {
                url: "{{ url('/') }}"+'/candidates/setData/?from_date='+from_date+'&to_date='+to_date+'&service_id='+service_id,
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
