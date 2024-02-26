@extends('layouts.superadmin')
@section('content')
<style>
    span.badge{
        font-size: 13px;
    }
</style>
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Account / Billing </h3>
               </div>
            </div>
         </div>
      </div> --}}
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/app/home') }}">Dashboard</a>
             </li>
             <li>Promocode</li>
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
         
            
        <div class="col-md-3 content-container">
            <!-- left-sidebar -->
            @include('superadmin.settings.left-sidebar') 
        </div>
        <!-- start right sec -->
        <div class="col-md-9 content-wrapper" style="background:#fff">
            <div class="formCover" style="height: 100vh;">
            <!-- section -->
            <section>
                <div class="col-sm-12 ">
                    
                        <!-- row -->
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="card-title mb-1 mt-3">Promocode  </h4>
                                <p class="pb-border"> Promcode overview </p>
                            </div>
                            {{-- <div class="col-md-6 text-right">
                                <a href="" class="mt-3 btn btn-sm btn-primary">Payment Method</a>
                            </div> --}}
                            <div class="col-md-6 pt-3">
                                <div class="btn-group" style="float:right"> 
                                    <span><a href="#" class="filter0search"><i class="fa fa-filter"></i></a></span>
                                    <a class="pt-1" href="{{url('/app/settings/promocode/create')}}"><button class="btn btn-success"> <i class="fa fa-plus"></i> Add New </button></a>
                                </div>
                            </div>
                        </div>
                        <div class="search-drop-field pb-3" id="search-drop">
                            <div class="row">
                                <div class="col-md-3 form-group mb-1">
                                    <label> From date </label>
                                    <input class="form-control from_date commonDatepicker" type="text" placeholder="From date">
                                </div>
                                <div class="col-md-3 form-group mb-1">
                                    <label> To date </label>
                                    <input class="form-control to_date commonDatepicker" type="text" placeholder="To date">
                                </div>
                                <div class="col-md-3 form-group mb-1">
                                    <label> Code Name </label>
                                    <input class="form-control code_name" name="code_name" type="text" placeholder="Code Name">
                                </div>
                                <div class="col-md-3 form-group mb-1 level_selector">
                                    <label>Status</label><br>
                                    <select class="form-control status_list select " name="status" id="status">
                                        <option> All </option>
                                        <option value="1">Active</option>
                                        <option value="0">Deactive</option>
                                        <option value="2">Expired</option>
                                    </select>
                                    {{-- <input class="form-control candidate_list" type="text" placeholder="name"> --}}
                                </div>
                                <div class="col-md-2">
                                <button class="btn btn-primary search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                </div>
                            </div>
                        </div>
                    <div id="candidatesResult">
                        @include('superadmin.settings.promocode.ajax')
                    </div>
                        <!-- ./business detail -->
                        
                </div>
            </section>
            <!-- ./section -->
            <!--  -->
            <!-- ./section -->
            </div>
        </div>
        <!-- end right sec -->
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

    $(document).on('change','.status_list, .from_date, .to_date', function (e){    
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

    $(document).on('click', '.deleteBtn', function (event) {
    
        var id = $(this).attr('data-id');
        //  alert(user_id);
        if(confirm("Are you sure want to delete this promocode ?")){
        $.ajax({
            type:'POST',
            url: "{{ url('/app/')}}"+"/settings/promocode/delete",
            data: {"_token" : "{{ csrf_token() }}",'id':id},        
            success: function (response) {        
            console.log(response);
            
                if (response.status=='ok') { 

                    toastr.success("Promocode Deleted Successfully");
                    // window.setTimeout(function(){
                    //    location.reload();
                    // },2000);
                    $('table.promoTable tr').find("[data-id='" + id + "']").parent().parent().parent().fadeOut("slow");
                } 
                else {
                    
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                // alert("Error: " + errorThrown);
            }
        });

        }
        return false;

    });

    $(document).on('click', '.status', function (event) {
    
        var id = $(this).attr('data-id');
        var type =$(this).attr('data-type');
        //  alert(user_id);
        if(confirm("Are you sure want to change the status ?")){
            $.ajax({
                type:'POST',
                url: "{{ url('/app/')}}"+"/settings/promocode/status",
                data: {"_token" : "{{ csrf_token() }}",'id':id,'type':type},        
                success: function (response) {        
                console.log(response);
                
                    if (response.status=='ok') { 
                        // window.setTimeout(function(){
                        //    location.reload();
                        // },2000);
                        // toastr.success("Status Changed Successfully");

                        if(response.type=='active')
                        {
                            $('table.promoTable tr').find("[data-ac='" + id + "']").fadeIn("slow");
                            $('table.promoTable tr').find("[data-ac='" + id + "']").removeClass("d-none");

                            $('table.promoTable tr').find("[data-dc='" + id + "']").fadeOut("slow");

                            $('table.promoTable tr').find("[data-dc='" + id + "']").addClass("d-none");

                            $('table.promoTable tr').find("[data-a='" + id + "']").fadeOut("slow");
                            $('table.promoTable tr').find("[data-a='" + id + "']").addClass("d-none");

                            $('table.promoTable tr').find("[data-d='" + id + "']").fadeIn("slow");

                            $('table.promoTable tr').find("[data-d='" + id + "']").removeClass("d-none");

                            
                        }
                        else if(response.type=='deactive')
                        {
                            $('table.promoTable tr').find("[data-dc='" + id + "']").fadeIn("slow");
                            $('table.promoTable tr').find("[data-dc='" + id + "']").removeClass("d-none");

                            $('table.promoTable tr').find("[data-ac='" + id + "']").fadeOut("slow");

                            $('table.promoTable tr').find("[data-ac='" + id + "']").addClass("d-none");

                            $('table.promoTable tr').find("[data-d='" + id + "']").fadeOut("slow");
                            $('table.promoTable tr').find("[data-d='" + id + "']").addClass("d-none");

                            $('table.promoTable tr').find("[data-a='" + id + "']").fadeIn("slow");

                            $('table.promoTable tr').find("[data-a='" + id + "']").removeClass("d-none");
                        }
                    } 
                    else {
                        
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
            });

        }
        return false;

    });




    
});
    function getData(page){
        //set data
        var status     =    $(".status_list").val();                

        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();      

            $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);

            $.ajax(
            {
                url: '?page=' + page+'&status='+status+'&from_date='+from_date+'&to_date='+to_date,
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

        var status     =    $(".status_list").val();                
      //   var check       =    $(".check option:selected").val();

        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();    
        
            $.ajax(
            {
                url: "{{ url('/') }}"+'/candidates/setData/?status='+status+'&from_date='+from_date+'&to_date='+to_date,
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
