@extends('layouts.admin')
@section('content')

<div class="main-content-wrap sidenav-open d-flex flex-column">
<div class="main-content">  
   <div class="row pb-2">
      <div class="col-sm-11">
          <ul class="breadcrumb">
          <li>
          <a href="{{ url('/home') }}">Dashboard</a>
          </li>
          <li>
            <a href="{{ url('/users') }}">Users</a>
            </li>
          <li>Create New</li>
          </ul>
      </div>
      <!-- ============Back Button ============= -->
      <div class="col-sm-1 back-arrow">
          <div class="text-right">
          <a href="{{ url()->previous()}}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
          </div>
      </div>
   </div>
   {{-- <div class="row">
      <div class="col-lg-12 margin-tb">
         <div class="pull-left">
            <h2>Create New User</h2>
         </div>
         <div class="pull-right"><a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a></div>
      </div>
   </div> --}}
   @if (count($errors) > 0)
      <div class="alert alert-danger">
         <strong>Whoops!</strong> There were some problems with your input.<br><br>
         <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
         </ul>
      </div>
   @endif
   {!! Form::open(array('route' => 'users.store','method'=>'POST','id'=>'addUserForm','class' => 'form-horizontal')) !!}
   <div class="form-body">
      <div class="card radius shadow-sm">
         <div class="card-body">
            <div class="row">
               <div class="col-xs-12 col-sm-12 col-md-6 offset-md-3">
                  <p class="card-title" style="font-size:20px;border-bottom:1px solid #ddd;">Create a New User</p>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group"><strong>First Name:</strong> <span class="text-danger">*</span>{!! Form::text('first_name', null, array('placeholder' => 'First Name','class' => 'form-control')) !!}</div>
                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-first_name"></p>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group"><strong>Last Name:</strong>{!! Form::text('last_name', null, array('placeholder' => 'Last Name','class' => 'form-control')) !!}</div>
                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-last_name"></p>
                     </div>
                  </div>
                 <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                           <strong>Phone: <span class="text-danger">*</span></strong>
                           <input type="hidden" id="code" name ="primary_phone_code" value="91" >
                           <input type="hidden" id="iso" name ="primary_phone_iso" value="in" >
                           {!! Form::text('phone', null, array( 'id'=>'phone1', 'class' => 'form-control')) !!}
                        </div>
                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-phone"></p>
                    </div>
                 </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group"><strong>Email:</strong> <span class="text-danger">*</span>{!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}</div>
                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-email"></p>
                     </div>
                  </div>

                 <div class="row">
                    <div class="col-md-12">
                     <div class="form-group">
                        <strong>Select Services:</strong> <span class="text-danger">*</span>
                     
                        <div class="col-sm-12">
                           <div class="form-group">
                              @foreach($services as $service)
                                 <div class="form-check form-check-inline">
                                    <input class="form-check-input services_list" type="checkbox" name="services[]" value="{{ $service->id}}" id="inlineCheckbox-{{ $service->id}}" >
                                    <label class="form-check-label" for="inlineCheckbox-{{ $service->id}}">{{ $service->name  }}</label>
                                 </div>
                              @endforeach
                           </div>
                           <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>
                        </div>
   
                        <div class="form-group">
                           <strong>Select a Role:</strong> <span class="text-danger">*</span>
                           <div class="form-group">
                              <select class="select-option-field-7 role selectValue form-control" name="role" data-type="role" data-t="{{ csrf_token() }}">
                                 <option value="">Select Role</option>
                                 @foreach ($roles as $role)
                                    <option value="{{$role->id}}">{{$role->role}}</option>
                                 @endforeach
                              </select>
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-role"></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <strong>Vendor Name:</strong>
                           <div class="form-group">
                              <select class="select-option-field-7 role  form-control select" multiple name="vendor_id[]" id="vendor_id">
                                 <option value="">Select Vendor Name</option>
                                 @foreach ($vendors as $vendor)
                                    <option value="{{$vendor->id}}">{{$vendor->first_name}}</option>
                                 @endforeach
                              </select>
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-vendor_id"></p>
                           </div>
                        </div>
                     </div>
                    </div>
                 </div>

               </div>
               
               {{-- <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group"><strong>Password:</strong>{!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control ','id'=>'Password')) !!}</div>
                  <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-password"></p>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group"><strong>Confirm Password:</strong>{!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}</div>
                  
               </div> --}}
            

               {{-- <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group"><strong>Role:</strong>{!! Form::select('roles[]', $roles,[], array('class' => 'form-control','multiple')) !!}</div>
               </div> --}}
            </div>
         </div>
         <div class="card-footer">
            <div class="text-center">
               <button type="submit" class="btn btn-info user_submit">Submit</button>
            </div>
         </div>
      </div>
   </div>
   {!! Form::close() !!}
</div>


<script>
   $(function(){
   
      // $('.submit').on('click', function() {
      //     var $this = $(this);
      //     var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
      //     if ($(this).html() !== loadingText) {
      //       $this.data('original-text', $(this).html());
      //       $this.html(loadingText);
      //     }
      //     setTimeout(function() {
      //       $this.html($this.data('original-text'));
      //     }, 5000);
      // });
      
      //    $('#createUserBtn').click(function(e) {
      //         e.preventDefault();
      //         $("#addUserForm").submit();
      //     });
      $(".select").select2();

      $(document).on('submit', 'form#addUserForm', function (event) {
         event.preventDefault();
         //clearing the error msg
         $('p.error_container').html("");
      
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
         $('.user_submit').attr('disabled',true);
         if($('.user_submit').html()!=loadingText)
         {
            $('.user_submit').html(loadingText);
         }
         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,      
            success: function (response) {
                  window.setTimeout(function(){
                     $('.user_submit').attr('disabled',false);
                     $('.user_submit').html('Submit');
                  },2000);
                  console.log(response);
                  if(response.success==true  ) {          
                     
                     var email = response.email;
                     //notify
                     toastr.success("A Password Reset Link has been sent to "+email);
                     toastr.success("User created successfully");
                     // redirect to google after 5 seconds
                     window.setTimeout(function() {
                        window.location = "{{ url('/')}}"+"/users/";
                     }, 2000);
                  
                  }
                  //show the form validates error
                  if(response.success==false ) {                              
                     for (control in response.errors) {   
                        $('#error-' + control).html(response.errors[control]);
                     }
                  }
            },
            error: function (response) {
                  // alert("Error: " + errorThrown);
                  console.log(data);
            }
            // error: function (xhr, textStatus, errorThrown) {
            //       // alert("Error: " + errorThrown);
            // }
         });
         return false;
      });
   });
   
</script>
@endsection
