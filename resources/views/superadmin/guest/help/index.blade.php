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
         <div class="card text-left">
            <div class="card-body">
                @include('superadmin.guest.menu')
               <div class="row pt-4">
                  <div class="col-md-8 ">
                     <h4 class="card-title mb-1"> Guest Help & Support</h4>
                     <p>List of Help & Support Query </p>
                  </div>
                  <div class="col-md-4">
                     <div class="btn-group" style="float:right">
                        <span><a href="#" class="filter0search"><i class="fa fa-filter"></i></a></span>
                     </div>
                  </div>
                  @if ($message = Session::get('success'))
                        <div class="col-12">
                            <div class="alert alert-success">
                                <strong>{{ $message }}</strong> 
                            </div>
                        </div>
                  @endif
               </div>
               <div class="search-drop-field" id="search-drop">
                    <div class="row">
                        <div class="col-md-2 form-group mb-1">
                            <label> From date </label>
                            <input class="form-control from_date commonDatepicker" type="text" placeholder="From date">
                        </div>
                        <div class="col-md-2 form-group mb-1">
                            <label> To date </label>
                            <input class="form-control to_date commonDatepicker" type="text" placeholder="To date">
                        </div>
                        <div class="col-md-2 form-group mb-1 level_selector">
                            <label>Guest Name</label><br>
                            <select class="form-control guest_list select" name="guest_name" id="guest_name">
                                <option> All </option>
                                @foreach($guests as $guest)
                                    <option value="{{$guest->id}}"> {{$guest->name}} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                        </div>
                    </div>
               </div>
                <div id="candidatesResult">
                   @include('superadmin.guest.help.ajax')
                </div>
            </div>
         </div>
      </div>
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

    $(document).on('change','.from_date, .to_date, .guest_list', function (e){    
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
                       

        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();
        var user_id=    $(".guest_list option:selected").val();

            $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);

            $.ajax(
            {
                url: '?page=' + page+'&from_date='+from_date+'&to_date='+to_date+'&user_id='+user_id,
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

                       
      //   var check       =    $(".check option:selected").val();

        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();    
        var user_id=    $(".guest_list option:selected").val(); 
        
            $.ajax(
            {
                url: "{{ url('/') }}"+'/candidates/setData/?from_date='+from_date+'&to_date='+to_date+'&user_id='+user_id,
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
