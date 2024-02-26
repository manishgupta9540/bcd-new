@extends('layouts.vendor')
@section('content')

<div class="main-content-wrap sidenav-open d-flex flex-column">
<div class="main-content">  
   <!-- ============Breadcrumb ============= -->
   <div class="row">
      <div class="col-sm-11">
          <ul class="breadcrumb">
          <li>
          <a href="{{ url('/vendor/home') }}">Dashboard</a>
          </li>
          <li><a href="{{ url('/vendor/users') }}">User</a></li>
          <li>Create</li>
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

<div class="card text-left">
   <div class="card-body" >
   <div class="row">
   <div class="col-lg-12 margin-tb">
      <div class="text-center">
         <h2>Create new user</h2>
      </div>
      
   </div>
</div>
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

<form class="mt-2" method="post" id="addUserForm" action="{{ url('vendor/users/store') }}">
   @csrf
   <div class="col-xs-12 col-sm-8 col-md-8 offset-md-2">
    <div class="form-group">
        <label for="first_name">First Name <span class="text-danger">*</span></label>
        <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter first name" value="{{ old('first_name') }}">
        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-first_name"></p>
    </div>   
    </div>
    <div class="col-xs-12 col-sm-8 col-md-8 offset-md-2">
    <div class="form-group">
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Enter last name" value="{{ old('last_name') }}">
        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-last_name"></p>
    </div>   
    </div>
    <div class="col-xs-12 col-sm-8 col-md-8 offset-md-2">
    <div class="form-group">
        <label for="phone">Phone <span class="text-danger">*</span></label>
        <input type="hidden" id="code" name ="primary_phone_code" value="91" >
        <input type="hidden" id="iso" name ="primary_phone_iso" value="in" >
        <input type="text" name="phone" class="form-control number_only" id="phone1" value="{{ old('phone') }}">
        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-phone"></p>
    </div>   
    </div>
   <div class="col-xs-12 col-sm-8 col-md-8 offset-md-2">
    <div class="form-group">
        <label for="email">Email <span class="text-danger">*</span></label>
        <input type="text" name="email" class="form-control" id="email" placeholder="Enter Email Id" value="{{ old('email') }}">
        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-email"></p>
    </div>
   </div>
   

     
   
   {{-- <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group"><strong>Role:</strong>{!! Form::select('roles[]', $roles,[], array('class' => 'form-control','multiple')) !!}</div>
   </div> --}}
   <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-4"><button type="submit" class="btn btn-primary user_submit">Submit</button></div>
</div>

</form>
</div>
</div>
</div>
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
                        window.location = "{{ url('/vendor/')}}"+"/users/";
                     }, 2000);
                  
                  }
                  //show the form validates error
                  if(response.success==false ) {                              
                     for (control in response.errors) {   
                        $('#error-' + control).html(response.errors[control]);
                     }
                  }
            },
            error: function (xhr, textStatus, errorThrown) {
                  // alert("Error: " + errorThrown);
            }
         });
         return false;
      });
   });
   
</script>
@endsection