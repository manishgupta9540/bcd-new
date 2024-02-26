@extends('layouts.vendor')

@section('content')

<style>
   .disabled-link{
      pointer-events: none;
   }
   .sweet-alert button.cancel {
        background: #DD6B55 !important;
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
             <li>Roles</li>
             </ul>
         </div>
         <!-- ============Back Button ============= -->
         <div class="col-sm-1 back-arrow">
             <div class="text-right">
             <a href="{{url()->previous()}}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
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
                     <div class="alert alert-success">
                        <strong>{{ $message }}</strong> 
                     </div>
                  </div>
               @elseif($message = Session::get('error'))
                  <div class="col-md-12">   
                     <div class="alert alert-danger">
                        <strong>{{ $message }}</strong> 
                     </div>
                  </div>
               @endif
               @php
               $ADD_ACCESS    = false;
               $EDIT_ACCESS   = false;
               $STATUS_ACCESS   = false;
               $DELETE_ACCESS   = false;
               $PERMISSION_ACCESS   = false;
               $ADD_ACCESS    = Helper::can_access('Create Role','');//passing action title and route group name
               $EDIT_ACCESS   =  Helper::can_access('Edit Role','');//passing action title and route group name
               $STATUS_ACCESS   =  Helper::can_access('Role Status','');//passing action title and route group name
               $DELETE_ACCESS   =  Helper::can_access('Delete Role','');//passing action title and route group name
               $PERMISSION_ACCESS   =  Helper::can_access('Permissions','');//passing action title and route group name
               @endphp
                  <div class="col-md-8">
                     <h4 class="card-title mb-1"> Roles </h4>
                     <p> List of all Roles </p>
                  </div>
                  <div class="col-md-4">
                     <div class="btn-group" style="float:right">
                        
                            <a class="btn btn-success " href=" {{url('vendor/roles/create')}}" > <i class="fa fa-plus"></i> Add Role </a>             

                        
                     </div>
                  </div>
               </div>
               <div id="candidatesResult">
                  @include('vendor.roles.ajax')
               </div>
            </div>
         </div>
      </div>
   </div>
   </div>
</div>


<script>
   // function del()
   // {
   //    var result=confirm("Are You Sure You Want to Delete?");
   //    if(result){
   //       return true;
   //    }
   //    else{
   //       return false;
   //    }
   // }
$(document).ready(function(){

   //active and deactive
   $(document).on('click', '.status', function (event) {
    
      var id = $(this).attr('data-id');
      var type =$(this).attr('data-type');
      //  alert(user_id);

      swal({
      // icon: "warning",
      type: "warning",
      title: "Are you Want to Change The Status of This Role?",
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
                  url: "{{url('vendor/roles/rolestatus')}}",
                  data: {"_token" : "{{ csrf_token() }}",'id':id,'type':type},        
                  success: function (response) {        
                  
                        if(response.success==false)
                        {
                           toastr.error('This Role is Already Assigned to The Users');
                        }
                     if (response.success) { 
                           // window.setTimeout(function(){
                           //    location.reload();
                           // },2000);
                           // toastr.success("Status Changed Successfully");

                           if(response.type=='enable')
                           {
                              $('table.roleTable tr').find("[data-ac='" + id + "']").fadeIn("slow");
                              $('table.roleTable tr').find("[data-ac='" + id + "']").removeClass("d-none");

                              $('table.roleTable tr').find("[data-dc='" + id + "']").fadeOut("slow");

                              $('table.roleTable tr').find("[data-dc='" + id + "']").addClass("d-none");

                              $('table.roleTable tr').find("[data-a='" + id + "']").fadeOut("slow");
                              $('table.roleTable tr').find("[data-a='" + id + "']").addClass("d-none");

                              $('table.roleTable tr').find("[data-d='" + id + "']").fadeIn("slow");

                              $('table.roleTable tr').find("[data-d='" + id + "']").removeClass("d-none");

                              
                           }
                           else if(response.type=='disable')
                           {
                              $('table.roleTable tr').find("[data-dc='" + id + "']").fadeIn("slow");
                              $('table.roleTable tr').find("[data-dc='" + id + "']").removeClass("d-none");

                              $('table.roleTable tr').find("[data-ac='" + id + "']").fadeOut("slow");

                              $('table.roleTable tr').find("[data-ac='" + id + "']").addClass("d-none");

                              $('table.roleTable tr').find("[data-d='" + id + "']").fadeOut("slow");
                              $('table.roleTable tr').find("[data-d='" + id + "']").addClass("d-none");

                              $('table.roleTable tr').find("[data-a='" + id + "']").fadeIn("slow");

                              $('table.roleTable tr').find("[data-a='" + id + "']").removeClass("d-none");
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
   
   //deleted roles 
   $(document).on('click','.deleteBtn',function(){
      var _this=$(this);
      var id = $(this).data('id');

      swal({
            // icon: "warning",
            type: "warning",
            title: "Are You Sure You Want to Delete?",
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
                  _this.addClass('disabled-link');
                  $.ajax({
                     type: "POST",
                     dataType: "json",
                     url: "{{url('vendor/roles/delete')}}",
                     data: {"_token": "{{ csrf_token() }}",'id': id},
                     success: function(data){
                        console.log(data);
                        window.setTimeout(function(){
                           _this.removeClass('disabled-link');
                        },2000);
                        if(data.success==false)
                        {
                           toastr.error('This Role is Already Assigned to The Users');
                        }
                        else if(data.success==true)
                        {
                           toastr.success('Role Deleted Successfully');
                           window.setTimeout(function(){
                                 window.location.reload();
                           },2000);
                        }
                     },
                     error : function(response)
                     {
                           console.log(response);
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
});


</script>

@endsection
