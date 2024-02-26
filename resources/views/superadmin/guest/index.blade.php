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
                     <h4 class="card-title mb-1"> Guest Users</h4>
                     <p> List of all Guest Users</p>
                  </div>
                  <div class="col-md-4">
                     <div class="btn-group" style="float:right">
                        <span><a href="#" class="filter0search"><i class="fa fa-filter"></i></a></span>
                     </div>
                  </div>
               </div>
               <div class="search-drop-field pb-3" id="search-drop">
                    <div class="row">
                        <div class="col-md-2 form-group mb-1">
                            <label> From date </label>
                            <input class="form-control from_date commonDatepicker" type="text" placeholder="From date">
                        </div>
                        <div class="col-md-2 form-group mb-1">
                            <label> To date </label>
                            <input class="form-control to_date commonDatepicker" type="text" placeholder="To date">
                        </div>
                        <div class="col-md-2 form-group mb-1">
                            <label> Name </label>
                            <input class="form-control name" name="name" type="text" placeholder="Name">
                        </div>
                        <div class="col-md-2 form-group mb-1">
                           <label> Email ID</label>
                           <input class="form-control email" name="email" type="email" placeholder="Email">
                       </div>
                       <div class="col-md-2 form-group mb-1">
                           <label> Phone Number</label>
                           <input class="form-control phone" name="phone" type="text" placeholder="Phone Number">
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
                   @include('superadmin.guest.ajax')
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

    $(document).on('change','.from_date, .to_date, .name, .email, .phone', function (e){    
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
        if(confirm("Are you sure want to delete ?")){
        $.ajax({
            type:'POST',
            url: "{{ url('/app/')}}"+"/guest/delete",
            data: {"_token" : "{{ csrf_token() }}",'id':id},        
            success: function (response) {        
            console.log(response);
            
                if (response.status=='ok') { 

                    toastr.success("Record Deleted Successfully");
                    // window.setTimeout(function(){
                    //    location.reload();
                    // },2000);
                    $('table.guestTable tr').find("[data-id='" + id + "']").parent().parent().parent().fadeOut("slow");
                } 
                else {
                    
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                alert("Error: " + errorThrown);
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
                url: "{{ url('/app/')}}"+"/guest/status",
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
                            $('table.guestTable tr').find("[data-ac='" + id + "']").fadeIn("slow");
                            $('table.guestTable tr').find("[data-ac='" + id + "']").removeClass("d-none");

                            $('table.guestTable tr').find("[data-dc='" + id + "']").fadeOut("slow");

                            $('table.guestTable tr').find("[data-dc='" + id + "']").addClass("d-none");

                            $('table.guestTable tr').find("[data-a='" + id + "']").fadeOut("slow");
                            $('table.guestTable tr').find("[data-a='" + id + "']").addClass("d-none");

                            $('table.guestTable tr').find("[data-d='" + id + "']").fadeIn("slow");

                            $('table.guestTable tr').find("[data-d='" + id + "']").removeClass("d-none");

                            
                        }
                        else if(response.type=='deactive')
                        {
                            $('table.guestTable tr').find("[data-dc='" + id + "']").fadeIn("slow");
                            $('table.guestTable tr').find("[data-dc='" + id + "']").removeClass("d-none");

                            $('table.guestTable tr').find("[data-ac='" + id + "']").fadeOut("slow");

                            $('table.guestTable tr').find("[data-ac='" + id + "']").addClass("d-none");

                            $('table.guestTable tr').find("[data-d='" + id + "']").fadeOut("slow");
                            $('table.guestTable tr').find("[data-d='" + id + "']").addClass("d-none");

                            $('table.guestTable tr').find("[data-a='" + id + "']").fadeIn("slow");

                            $('table.guestTable tr').find("[data-a='" + id + "']").removeClass("d-none");
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

     //when click on resendmail button
     $(document).on('click', '.resendMail', function (event) {
        
        var _this =$(this);
        var id=$(this).attr('data-id');
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Sending...';
        _this.addClass('disabled-link');
        if (_this.html() !== loadingText) {
            _this.html(loadingText);
        }

        $.ajax({
            type:'POST',
            url: "{{ url('/app/')}}"+"/guest/resend_mail",
            data: {"_token": "{{ csrf_token() }}",'id':id},        
            success: function (response) {        
            console.log(response);
                window.setTimeout(function(){
                    _this.removeClass('disabled-link');
                    _this.html('<i class="far fa-envelope"></i> Re-send Mail');
                },2000);
                if (response.status=='ok') {            
                    var name=response.name;

                    if(response.mail_verify==1)
                        toastr.success("Mail Verification Is Already Done By "+name);
                    else
                        toastr.success("Mail Sent Succesfully to "+name);
                } 
                else {
                    toastr.error("Something Went Wrong !");
                }
            },
            error: function (response) {
            //    console.log(response);
            }
            // error: function (xhr, textStatus, errorThrown) {
            //     alert("Error: " + errorThrown);
            // }
        });

        // }
        return false;

    }); 

    
});
    function getData(page){
        //set data
        var status     =    $(".status_list").val();                

        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();
        var name     =    $(".name").val();      
        var email     =    $(".email").val();      
        var phone     =    $(".phone").val();      

            $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);

            $.ajax(
            {
                url: '?page=' + page+'&status='+status+'&from_date='+from_date+'&to_date='+to_date+'&name='+name+'&email='+email+'&phone='+phone,
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
                url: "{{ url('/') }}"+'/candidates/setData/?status='+status+'&from_date='+from_date+'&to_date='+to_date+'&name='+name+'&email='+email+'&phone='+phone,
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
