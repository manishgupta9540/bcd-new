@extends('layouts.client')
@section('content')
<style>
   .sweet-alert button.cancel {
       background: #DD6B55 !important;
   }
</style>
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
       <!-- ============Breadcrumb ============= -->
   <div class="row">
      @php
         $ADD_ACCESS    = false;
         $EDIT_ACCESS   = false;
         $VIEW_ACCESS   = false;
         $STATUS_ACCESS   = false;
         $DELETE_ACCESS   = false;
         // dd($ADD_ACCESS);
         $ADD_ACCESS    = Helper::can_access('Add User','/my');//passing action title and route group name
         $EDIT_ACCESS    = Helper::can_access('Edit User','/my');//passing action title and route group name
         $VIEW_ACCESS   = Helper::can_access('View Users List','/my');//passing action title and route group name
         $STATUS_ACCESS    = Helper::can_access("User's Account Status",'/my');//passing action title and route group name
         $DELETE_ACCESS   = Helper::can_access('Delete User','/my');//passing action title and route group name
      @endphp
      <div class="col-sm-11">
          <ul class="breadcrumb">
          <li>
            <a href="{{ url('/my/home') }}">Dashboard</a>
          </li>
          <li>User</li>
         
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
      <div class="col-md-12">
         <div class="card text-left">
            <div class="card-body">
               <div class="row">
               @if ($message = Session::get('success'))
               <div class="col-md-12">   
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                     <strong>{{ $message }}</strong> 
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-top: -5px; font-size: 30px;">
                        <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
               </div>
               @endif
                  <div class="col-md-8">
                     <h4 class="card-title mb-1"> Users </h4>
                     <p> List of all Users </p>
                  </div>
                  @if($ADD_ACCESS)
                     <div class="col-md-4">
                        <div class="btn-group" style="float:right">
                           <a class="btn btn-success " href="{{ url('my/users/create') }}" > <i class="fa fa-plus"></i> Add New </a>             
                        </div>
                     </div>
                  @endif
               </div>
               <div id="candidatesResult">
                  @include('clients.users.ajax')
               </div>
            </div>
         </div>
      </div>
   </div>
   </div>
</div>

<script>

   $(document).ready(function(){
         //when click on delete button
         $(document).on('click', '.deleteBtn', function (event) {
            
            var user_id = $(this).attr('data-user_id');
            //  alert(user_id);
            //  if(confirm("Are you sure want to delete this user ?")){
            //  $.ajax({
            //      type:'GET',
            //      url: "{{ url('/my/')}}"+"/users/delete",
            //      data: {'user_id':user_id},        
            //      success: function (response) {        
            //      console.log(response);
               
            //          if (response.status=='ok') { 

            //             toastr.success("User Deleted Successfully");
            //             // window.setTimeout(function(){
            //             //    location.reload();
            //             // },2000);
            //             $('table.userTable tr').find("[data-user_id='" + user_id + "']").parent().parent().fadeOut("slow");
            //          } else {
            //             // toastr.error("Firstly, Complete or Assign Task to any other user ");
            //          }
            //      },
            //      error: function (xhr, textStatus, errorThrown) {
            //          // alert("Error: " + errorThrown);
            //      }
            //  });

            //  }
            //  return false;

            swal({
                     // icon: "warning",
                     type: "warning",
                     title: "Are you sure want to delete this user ?",
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
                                 type:'GET',
                                 url: "{{ url('/my/')}}"+"/users/delete",
                                 data: {'user_id':user_id},        
                                 success: function (response) {        
                                 console.log(response);
                                 
                                       if (response.status=='ok') { 

                                          toastr.success("User Deleted Successfully");
                                          // window.setTimeout(function(){
                                          //    location.reload();
                                          // },2000);
                                          $('table.userTable tr').find("[data-user_id='" + user_id + "']").parent().parent().fadeOut("slow");
                                       } else {
                                          // toastr.error("Firstly, Complete or Assign Task to any other user ");
                                       }
                                 },
                                 error: function (xhr, textStatus, errorThrown) {
                                       // alert("Error: " + errorThrown);
                                 }
                           });

                           swal.close();
                        }
                        else
                        {
                           swal.close();
                        }
                     }
                  );

         });

         //when click on Unblock button
         $(document).on('click', '.unblockBtn', function (event) {
            
            var user = $(this).attr('data-user');
            //  alert(user_id);
            //  if(confirm("Are you sure want to Unblock this user ?")){
            //    $.ajax({
            //       type:'GET',
            //       url: "{{ url('/my/')}}"+"/user/unblock",
            //       data: {'user_id':user},        
            //       success: function (response) {        
            //       console.log(response);
                  
            //             if (response.status=='ok') { 

            //                toastr.success("User Unblock Successfully");
            //                // window.setTimeout(function(){
            //                //    location.reload();
            //                // },2000);
            //                $('table.userTable tr').find("[data-user='" + user + "']").fadeOut("slow");
            //                $('table.userTable tr').find("[data-users_id='" + user + "']").fadeOut("slow");
            //             } else {
                           
            //             }
            //       },
            //       error: function (xhr, textStatus, errorThrown) {
            //             // alert("Error: " + errorThrown);
            //       }
            //    });

            //  }
            //  return false;
            swal({
                     // icon: "warning",
                     type: "warning",
                     title: "Are you sure want to Unblock this user ?",
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
                              type:'GET',
                              url: "{{ url('/my/')}}"+"/user/unblock",
                              data: {'user_id':user},        
                              success: function (response) {        
                              console.log(response);
                              
                                    if (response.status=='ok') { 

                                       toastr.success("User Unblock Successfully");
                                       // window.setTimeout(function(){
                                       //    location.reload();
                                       // },2000);
                                       $('table.userTable tr').find("[data-user='" + user + "']").fadeOut("slow");
                                       $('table.userTable tr').find("[data-users_id='" + user + "']").fadeOut("slow");
                                    } else {
                                       
                                    }
                              },
                              error: function (xhr, textStatus, errorThrown) {
                                    // alert("Error: " + errorThrown);
                              }
                           });
                           swal.close();
                        }
                        else
                        {
                           swal.close();
                        }
                     }
                  );
            

         });

         // $(document).on('click', '.toggle-class', function() {
         //          var _this=$(this);
         //        var status = $(this).prop('checked') ==  true ? 1 : 0;
         //        var id = $(this).data('id');
         //       //  alert(status);
         //       //   console.log(status);
         //       // if(confirm("Are you Change status of this user ?")){
         //       //  $.ajax({
         //       //      type: "GET",
         //       //      dataType: "json",
         //       //      url: '{{url('/my/user/status')}}',
         //       //      data: {'status': status, 'id': id},
         //       //      success: function(data){
         //       //        console.log(data.success)
         //       //        toastr.success("Status has been changed successfully");
         //       //      }
         //       //  });
         //       // }
         //       // return false;

         //       swal({
         //             // icon: "warning",
         //             type: "warning",
         //             title: "Are you Change status of this user ?",
         //             text: "",
         //             dangerMode: true,
         //             showCancelButton: true,
         //             confirmButtonColor: "#DD6B55",
         //             confirmButtonText: "YES",
         //             cancelButtonText: "CANCEL",
         //             closeOnConfirm: false,
         //             closeOnCancel: false
         //             },
         //             function(e){
         //                if(e==true)
         //                {
         //                   $.ajax({
         //                      type: "GET",
         //                      dataType: "json",
         //                      url: '{{url('/my/user/status')}}',
         //                      data: {'status': status, 'id': id},
         //                      success: function(data){
         //                         // console.log(data.success);
         //                         toastr.success("Status has been changed successfully");
         //                      }
         //                   });
         //                   swal.close();
         //                }
         //                else
         //                {
         //                   if(status==0)
         //                   {
         //                      _this.prop('checked',true);
         //                   }
         //                   else
         //                   {
         //                      _this.prop('checked',false);
         //                   }
         //                   swal.close();
         //                }
         //             }
         //          );
               
               
         // });

         $(document).on('click', '.status', function (event) {
            
            var id = $(this).attr('data-id');
            var type =$(this).attr('data-type');
            //  alert(user_id);
            var name = $(this).attr('data-name');
            swal({
            // icon: "warning",
            type: "warning",
            title: 'Are you Want to Change The Status for '+name+'?',
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
                        url: "{{url('/my/user/status')}}",
                        data: {"_token" : "{{ csrf_token() }}",'id':id,'type':type},        
                        success: function (response) {        
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

$(document).on('change','.customer_list, .from_date, .to_date,.status', function (e){    
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

function getData(page){
        //set data
        var user_id     =    $(".customer_list").val();                

        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();      
        var status      =    $('.status').val();

            $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);

            $.ajax(
            {
                url: '?page=' + page+'&customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&status='+status,
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
   //   var check       =    $(".check option:selected").val();

   var from_date   =    $(".from_date").val(); 
   var to_date     =    $(".to_date").val(); 
   var status      =    $('.status').val();   
   
      $.ajax(
      {
            url: "{{ url('/') }}"+'/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&status='+status,
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
