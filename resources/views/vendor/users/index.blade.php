@extends('layouts.vendor')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
       <!-- ============Breadcrumb ============= -->
   <div class="row">
      <div class="col-sm-11">
          <ul class="breadcrumb">
          <li>
          <a href="{{ url('/vendor/home') }}">Dashboard</a>
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
                  <div class="alert alert-success">
                  <strong>{{ $message }}</strong> 
                  </div>
               </div>
               @endif
                  <div class="col-md-8">
                     <h4 class="card-title mb-1"> Users </h4>
                     <p> List of all Users </p>
                  </div>
                  <div class="col-md-4">
                     <div class="btn-group" style="float:right">
                        <a class="btn btn-success " href="{{ url('vendor/users/create') }}" > <i class="fa fa-plus"></i> Add New </a>             
                     </div>
                  </div>
               </div>
               <div id="candidatesResult">
                  @include('vendor.users.ajax')
               </div>
            </div>
         </div>
      </div>
   </div>
   </div>
</div>

<script>
//    //when click on delete button
$(document).on('click', '.deleteBtn', function (event) {
    
    var user_id = $(this).attr('data-user_id');
   //  alert(user_id);
    if(confirm("Are you sure want to delete this user ?")){
    $.ajax({
        type:'GET',
        url: "{{ url('/vendor/')}}"+"/users/delete",
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
            alert("Error: " + errorThrown);
        }
    });

    }
    return false;

});

//when click on Unblock button
$(document).on('click', '.unblockBtn', function (event) {
    
    var user = $(this).attr('data-user');
   //  alert(user_id);
    if(confirm("Are you sure want to Unblock this user ?")){
    $.ajax({
        type:'GET',
        url: "{{ url('/vendor/')}}"+"/user/unblock",
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
            alert("Error: " + errorThrown);
        }
    });

    }
    return false;

});
// $(document).on('click', '.toggle-class', function() {
//        var status = $(this).prop('checked') ==  true ? 1 : 0;
//        var id = $(this).data('id');
//       //  alert(status);
//         console.log(status);
//        $.ajax({
//            type: "GET",
//            dataType: "json",
//            url: '{{url('/vendor/user/status')}}',
//            data: {'status': status, 'id': id},
//            success: function(data){
//              console.log(data.success)
//            }
//        });
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
                  url: "{{url('/vendor/user/status')}}",
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

// });
</script>
@endsection
