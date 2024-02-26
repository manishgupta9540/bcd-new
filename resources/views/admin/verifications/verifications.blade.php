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
             <li>Instant Verification</li>
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

                     <div class="col-md-12">
                        @include('admin.verifications.menu')
                    </div>

                     <div class="col-md-8">
                        <h4 class="card-title mt-2 mb-1"> Verifications </h4>
                        <p> Available Verifications</p>
                     </div>

                     <div class="col-md-4">
                        <div class="btn-group" style="float:right">
                            <a href="javascript:;" class="mt-3 btn btn-sm btn-info VarificationFormInput"><i class="fa fa-plus"></i> Create Verification</a>
                           <!-- <button class="btn btn-secondary btn-lg dropdown-toggle" id="btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  Actions  </button>
                              <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#"> Else Here</a></div> -->
                           <!-- <a class="btn btn-success btn-lg" href="{!! url('/app/customers/create') !!} " > Add New </a>             -->
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        {{-- <div class="table-responsive"> --}}
                           <table class="table table-bordered verificationTable">
                              <thead>
                                 <tr>
                                    {{-- <th scope="col" style="position:sticky; top:60px">#</th> --}}
                                    <th scope="col" style="position:sticky; top:60px"> Name</th>
                                    <th scope="col" style="position:sticky; top:60px"> Type</th>
                                    <th scope="col" style="position:sticky; top:60px"> Status</th>
                                    <th scope="col" width="20%" style="position:sticky; top:60px"> Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if( count($services) > 0 )
                                 @foreach($services as $item)
                                 <tr>
                                    {{-- <th scope="row">{{ $item->id }}</th> --}}
                                    <td> <b>{{ $item->name }} </b><br>
                                       <small class="text-muted">  </small>
                                    </td>
                                    <td>
                                       @if($item->business_id!=NULL) 
                                          <span class="badge badge-custom">Custom</span>
                                       @else
                                          <span class="badge badge-info">Default</span>
                                       @endif
                                    </td>
                                    <td>
                                       @if($item->status==0)
                                          <span data-dc="{{base64_encode($item->id)}}" class="badge badge-danger">Inactive</span>
                                          <span data-ac="{{base64_encode($item->id)}}" class="badge badge-success d-none">Active</span>
                                       @else
                                          <span data-dc="{{base64_encode($item->id)}}" class="badge badge-danger d-none">Inactive</span>
                                          <span data-ac="{{base64_encode($item->id)}}" class="badge badge-success">Active</span>
                                       @endif
                                       {{-- <span class="badge badge-success">ACTIVE</span> --}}
                                    </td>
                                    <td>
                                       @if($item->business_id!=NULL)
                                          <span class=""><a href="javascript:;" data-id="{{$item->id}}" class="editservice btn btn-md btn-outline-info"><i class="far fa-edit"></i> Edit</a></span>
                                          <a href="{{ url('/verifications/view',['id'=> base64_encode($item->id) ] ) }}" class="btn btn-md btn-outline-info"> <i class="far fa-copyright"></i> Config</a>
                                       @else
                                          --
                                       @endif

                                       @if($item->business_id!=NULL)
                                          @if($item->status==1)
                                             <span data-a="{{base64_encode($item->id)}}" class="d-none"><a href="javascript:;" class="btn btn-sm btn-success status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('enable')}}" title="Active"><i class="far fa-check-circle"></i> Active</a></span> 
                                             <span data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-sm btn-dark status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('disable')}}" title="Deactivate"><i class="far fa-times-circle"></i> Deactivate</a></span>
                                          @else
                                             <span class="d-none" data-d="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-sm btn-dark status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('disable')}}" title="Deactivate"><i class="far fa-times-circle"></i> Deactivate</a></span>
                                             <span data-a="{{base64_encode($item->id)}}"><a href="javascript:;" class="btn btn-sm btn-success status" data-id="{{base64_encode($item->id)}}" data-type="{{base64_encode('enable')}}" title="Active"><i class="far fa-check-circle"></i> Active</a></span>
                                          @endif
                                      @endif

                                    </td>
                                 </tr>
                                 @endforeach
                                 @else
                                 <tr class="no_record">
                                    <td scope="row" colspan="7">
                                       <h3 class="text-center">No record!</h3>
                                    </td>
                                 </tr>
                                 @endif
                              </tbody>
                           </table>
                        {{-- </div> --}}
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
</div>
 <div class="modal" id="Varification_input_modal">
      <div class="modal-dialog">
         <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
               <h4 class="modal-title">Create a new verification </h4>
               {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
            </div>
            <!-- Modal body -->
            <form method="post" action="{{ url('/verifications/add/new') }}" id="service_inputs">
            @csrf
               <div class="modal-body">

               <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 

                  <div class="form-group">
                        <label for="label_name"> Verification Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter Verification Name"/>
                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-name"></p> 
                  </div>

                  <div class="form-group">
                     <label for="label_name"> Verification Type <span class="text-danger">*</span></label>
                        <select class="form-control" name="is_multiple_type">
                           <option value=""> -Select- </option>
                           <option value="1"> Multiple </option>
                           <option value="0" selected> Single </option>
                        </select>
                        <span class="text-muted">Multiple or Single </span>
                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-is_multiple_type"></p>
                  </div>

                  <div class="form-group">
                     <label for="label_name"> Default Price <span class="text-danger">*</span></label>
                     <input type="text" id="price" name="price" class="form-control" placeholder="Enter Price"/>
                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-price"></p> 
                  </div>
                  <!-- dynamic table  -->
                  <!-- <div class="form-group">
                     <label for="label_name"> Table name (which is required)  </label>
                     <input type="text" name="tablename" class="form-control tablename" readonly />   
                  </div>       -->
                  <!--  -->
               </div>
               <!-- Modal footer -->
               <div class="modal-footer">
                  <button type="submit" class="btn btn-info">  Save </button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
               </div>
            </form>
         </div>
      </div>
</div>

 <div class="modal"  id="edit_service_modal">
      <div class="modal-dialog">
         <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
               <h4 class="modal-title">Edit Service</h4>
               {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
            </div>
            <!-- Modal body -->
            <form method="post" action="{{ route('/verifications/update') }}" id="service_update">
            @csrf
               <div class="modal-body">
               <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-edit"> </p> 
                  <div class="form-group">
                        <label for="label_name"> Verification Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control name" placeholder="Enter Verification Name"/>
                        <p style="margin-bottom: 2px;" class="text-danger error-container error-name" id="error-name"></p> 
                  </div>

                  <div class="form-group">
                     <label for="label_name"> Verification Type <span class="text-danger">*</span> </label>
                        <select class="select form-control is_multiple_type"  name="is_multiple_type">
                           <option value=""> -Select- </option>
                           <option value="1"> Muliple </option>
                           <option value="0"> Single </option>
                        </select>
                        <p style="margin-bottom: 2px;" class="text-danger error-container error-is_multiple_type" id="error-is_multiple_type"></p> 
                  </div>
                  <div class="form-group">
                     <label for="label_name"> Default Price <span class="text-danger">*</span></label>
                     <input type="text" id="price" name="price" class="form-control price" placeholder="Enter Price"/>
                     <p style="margin-bottom: 2px;" class="text-danger error-container error-price" id="error-price"></p> 
                  </div>
                  <!-- <label class="checkbox-inline"><input type="radio" class="jobEntryType" name="jobEntryType" value="single" checked="checked"> Single Entry</label>
                  <label class="checkbox-inline"><input type="radio" class="jobEntryType" name="jobEntryType" value="multiple"> Multiple Entry</label> -->
               </div>
               <!-- Modal footer -->
               <div class="modal-footer">
                  
                  <input type="hidden" class="service_id" name="service_id" >
                  <button type="submit" class="btn btn-info " >  Save </button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
               </div>
            </form>
         </div>
      </div>
</div>
<script type="text/javascript">
   //
   $(document).ready(function() {

      //open modal
      $(document).on('click','.VarificationFormInput',function(){
            $('#service_inputs')[0].reset();
            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('#Varification_input_modal').modal({
               backdrop: 'static',
               keyboard: false
            });
      }); 

      $(document).on('submit', 'form#service_inputs', function (event) {

         $("#overlay").fadeIn(300);　
         event.preventDefault();
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var $btn = $(this);
         $('.form-control').removeClass('is-invalid');
         $('.error-container').html('');
         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
               console.log(data);
               $('.error-container').html('');
               if (data.fail && data.error_type == 'validation') {
                     
                     //$("#overlay").fadeOut(300);
                     for (control in data.errors) {
                        $('input[name=' + control + ']').addClass('is-invalid');
                        $('select[name=' + control + ']').addClass('is-invalid');
                        $('#error-' + control).html(data.errors[control]);
                     }
               } 
               if (data.fail && data.error == 'yes') {
                  
                  $('#error-all').html(data.message);
               }
               if (data.fail == false) {
                  $('#Varification_input_modal').modal('hide');
                  location.reload(); 
               }
            },
            error: function (data) {
            
               console.log(data);

            }
            // error: function (xhr, textStatus, errorThrown) {
            
            //    alert("Error: " + errorThrown);

            // }
         });
         return false;

      });
      //edit services item
      $(document).on('click','.editservice',function(){
       var id = $(this).attr("data-id");
         // $('#service_update')[0].reset();
         $('.form-control').removeClass('is-invalid');
         $('.error-container').html('');
         $.ajax({
            type: 'post',
            url: "{{ url('/verifications/edit') }}",
            data: {"_token": "{{ csrf_token() }}",'service_id':id},        
            success: function (data) {
                  console.log(data);
                  // $("#service_update")[0].reset();
                  $('.form-control').removeClass('is-invalid');
                  $('.error-container').html('');
                  if(data !='null')
                  {             
                     //check if primary data 
                     $('.name').val(data.result.name);
                     $('.service_id').val(data.result.id);
                  
                     $(".is_multiple_type option[value= '"+data.result.is_multiple_type+"']").attr("selected", "selected");

                     $(".price").val(data.price);

                     $('#edit_service_modal').modal({
                        backdrop: 'static',
                        keyboard: false
                     }); 
                     
                  }
            },
            error: function (xhr, textStatus, errorThrown) {
                  alert("Error: " + errorThrown);
            }
         });

      });

      $(document).on('submit', 'form#service_update', function (event) {

         $("#overlay").fadeIn(300);　
         event.preventDefault();
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var $btn = $(this);
         $('.form-control').removeClass('is-invalid');
         $('.error-container').html('');

         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
               console.log(data);
               $('.error-container').html('');
               if (data.fail && data.error_type == 'validation') {
                     
                     //$("#overlay").fadeOut(300);
                     for (control in data.errors) {
                        $('input[name=' + control + ']').addClass('is-invalid');
                        $('select[name=' + control + ']').addClass('is-invalid');
                        $('.error-' + control).html(data.errors[control]);
                     }
               } 
               if (data.fail && data.error == 'yes') {
                  
                  $('#error-edit').html(data.message);
               }
               if (data.fail == false) {

                  $('#edit_service_modal').modal('hide');
                  toastr.success('Service Updated Successfully');
                  window.setTimeout(function(){
                     location.reload(); 
                  },2000);
               }
            },
            error: function (data) {
               
               console.log(data);

            }
         });
         return false;

      });


   $(document).on('click', '.status', function (event) {
      
      var id = $(this).attr('data-id');
      var type =$(this).attr('data-type');
       
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
         title: 'Are you sure want to change the status ?',
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
               url: "{{url('/manual/verification/status')}}",
               data: {"_token" : "{{ csrf_token() }}",'id':id,'type':type},        
               success: function (response) {        
               
                     if(response.success==false)
                     {
                        toastr.error("Firstly, Complete or Assign Task to any other user ");
                     }
                  if (response.success) { 
                     if(response.type=='enable')
                     {
                        $('table.verificationTable tr').find("[data-ac='" + id + "']").fadeIn("slow");
                        $('table.verificationTable tr').find("[data-ac='" + id + "']").removeClass("d-none");

                        $('table.verificationTable tr').find("[data-dc='" + id + "']").fadeOut("slow");
                        $('table.verificationTable tr').find("[data-dc='" + id + "']").addClass("d-none");

                        $('table.verificationTable tr').find("[data-a='" + id + "']").fadeOut("slow");
                        $('table.verificationTable tr').find("[data-a='" + id + "']").addClass("d-none");

                        $('table.verificationTable tr').find("[data-d='" + id + "']").fadeIn("slow");
                        $('table.verificationTable tr').find("[data-d='" + id + "']").removeClass("d-none");
                     }
                     else if(response.type=='disable')
                     {
                        $('table.verificationTable tr').find("[data-dc='" + id + "']").fadeIn("slow");
                        $('table.verificationTable tr').find("[data-dc='" + id + "']").removeClass("d-none");

                        $('table.verificationTable tr').find("[data-ac='" + id + "']").fadeOut("slow");
                        $('table.verificationTable tr').find("[data-ac='" + id + "']").addClass("d-none");

                        $('table.verificationTable tr').find("[data-d='" + id + "']").fadeOut("slow");
                        $('table.verificationTable tr').find("[data-d='" + id + "']").addClass("d-none");

                        $('table.verificationTable tr').find("[data-a='" + id + "']").fadeIn("slow");
                        $('table.verificationTable tr').find("[data-a='" + id + "']").removeClass("d-none");
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
         }else
         {
            swal.close();
         }
      });
   });
}); 
</script>

@endsection
