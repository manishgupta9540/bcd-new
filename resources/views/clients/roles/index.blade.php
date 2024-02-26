@extends('layouts.client')
<style>
   .disabled-link{
      pointer-events: none;
   }
   .sweet-alert button.cancel {
        background: #DD6B55 !important;
    }
</style>
@section('content')
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
      $PERMISSION_ACCESS = false;
      // dd($ADD_ACCESS);
      $ADD_ACCESS    = Helper::can_access('Add Roles','/my');//passing action title and route group name
      $EDIT_ACCESS    = Helper::can_access('Edit Roles','/my');//passing action title and route group name
      $VIEW_ACCESS   = Helper::can_access('View Role List','/my');//passing action title and route group name
      $STATUS_ACCESS    = Helper::can_access("Role's Status",'/my');//passing action title and route group name
      $DELETE_ACCESS   = Helper::can_access('Delete Roles','/my');//passing action title and route group name
      $PERMISSION_ACCESS   = Helper::can_access('Permissions','/my');//passing action title and route group name

   @endphp
   <div class="col-sm-11">
       <ul class="breadcrumb">
       <li>
       <a href="{{ url('/my/home') }}">Dashboard</a>
       </li>
       <li>Roles</li>
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
                     <h4 class="card-title mb-1">Manage Roles </h4>
                     <p> List of all Roles </p>
                  </div>
                  @if ($ADD_ACCESS)
                     <div class="col-md-4">
                        <div class="btn-group" style="float:right">
                           <a class="btn btn-success " href=" {{url('my/roles/create')}}" > <i class="fa fa-plus"></i> Add Role </a>             
                        </div>
                     </div>
                  @endif
               </div>
               <div class="row">
                  <div class="col-md-12">
                     <div class="table-responsive">
                        @if ($VIEW_ACCESS)
                        
                        <table class="table table-bordered roleTable">
                           <thead>
                              <tr>
                                  <th>Role name</th>
                                  <th>Status</th>
                                  <th width="40%">Action</th>
                              </tr>
                          </thead>
                          <tbody>

                              @forelse ($roles as $item)

                                  <tr>
                                    <td>{{$item->role}}</td>
                                      
                                    <td>
                                       {{-- <input data-id="{{base64_encode($item->id)}}"  class="toggle-class" id="check-{{base64_encode($item->id)}}" name="status" type="checkbox"   {{ $item->status ? 'checked' : '' }}> --}}
                                       @if($item->status==0)
                                          <span data-dc="{{base64_encode($item->id)}}" class="badge badge-danger">Inactive</span>
                                          <span data-ac="{{base64_encode($item->id)}}" class="badge badge-success d-none">Active</span>
                                       @else
                                          <span data-dc="{{base64_encode($item->id)}}" class="badge badge-danger d-none">Inactive</span>
                                          <span data-ac="{{base64_encode($item->id)}}" class="badge badge-success">Active</span>
                                       @endif
                                    </td>
                                      <td>
                                          @if ($EDIT_ACCESS)
                                          <a class="btn btn-outline-info" href="{{url('my/roles/edit',['id'=>base64_encode($item->id)])}}" title="Edit" ><i class="far fa-edit"></i></a>   
                                          @endif
                                          
                                          @if ($DELETE_ACCESS)
                                          <a class="btn btn-outline-danger deleteBtn" href="javascript:;" title="Delete" data-id="{{base64_encode($item->id)}}"><i class="fa fa-trash"></i></a>
                                          @endif
                                        
                                          @if ($STATUS_ACCESS)

                                             @if($item->status==1)
                                                <span data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-outline-dark status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('disable')}}" title="Deactivate"><i class="far fa-times-circle"></i></a></span>
                                                <span data-a="{{base64_encode($item->id)}}" class="d-none"><a href="javascript:;" class="btn btn-outline-success status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('enable')}}" title="Active"><i class="far fa-check-circle"></i></a></span>
                                             @else
                                                <span class="d-none" data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-outline-dark status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('disable')}}" title="Deactivate"><i class="far fa-times-circle"></i></a></span>
                                                <span data-a="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-outline-success status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('enable')}}"  title="Active"><i class="far fa-check-circle"></i></a></span>
                                             @endif
                                          @endif
                                          @if ($PERMISSION_ACCESS)
                                             <a class="btn btn-outline-info" href="{{url('my/roles/permission',['id'=>base64_encode($item->id)])}}" title="Permission"><i class="fa fa-key" aria-hidden="true"></i></a>
                                          @endif

                                       </td>
                                    </tr>
                                 @empty
                                    <tr>
                                       <td colspan="4"><h6 class="text-center">No record found</h6></td>
                                    </tr>
                                 @endforelse

                           </tbody>                       
                           </table>
                        @else
                           <span><h3 class="text-center">You have no access to View Roles list</h3></span>
                        @endif 
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   </div>
</div>


<script>
$(document).ready(function(){
   // $(document).on('click', '.toggle-class', function() {
   //    var _this=$(this);
   //     var status = $(this).prop('checked') ==  true ? 1 : 0;
   //     var id = $(this).data('id');
   //     if(confirm("Are you Change status of this role ?")){
   //       _this.addClass('disabled-link');
   //       //   console.log(status);
   //       $.ajax({
   //          type: "POST",
   //          dataType: "json",
   //          url: '{{url('my/roles/roleStatus')}}',
   //          data: {"_token": "{{ csrf_token() }}",'status': status, 'id': id},
   //          success: function(data){
   //             console.log(data);
   //             window.setTimeout(function(){
   //                _this.removeClass('disabled-link');
   //             },2000);
   //             if(data.success==false)
   //             {
   //                   _this.prop('checked',true);
   //                   toastr.error('This Role is Already Assigned to the users');
   //             }
   //          },
   //          error : function(data)
   //          {
   //             console.log(data);
   //          }
   //       });
   //     }
   //     else
   //     {
   //       return false;
   //     }
   // });

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
                     url: "{{url('my/roles/roleStatus')}}",
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

   $(document).on('click','.deleteBtn',function(){
      var _this=$(this);
      var result=confirm("Are You Sure You Want to Delete?");
      var id = $(this).data('id');
      if(result){
         _this.addClass('disabled-link');
         // return true;
         $.ajax({
           type: "POST",
           dataType: "json",
           url: '{{url('/my/roles/delete')}}',
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
           }
         });
      }
      else{
         return false;
      }
   });
});


</script>

@endsection
