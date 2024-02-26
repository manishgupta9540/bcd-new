@extends('layouts.superadmin')
<style>
   span.show-hide-password {
       position: absolute;
       top: 32px;
       right: 10px;
       font-size: 14px;
       color: #748a9c;
       cursor: pointer;
   }
</style>
@section('content')

<div class="main-content-wrap sidenav-open d-flex flex-column">
<!-- ============ Body content start ============= -->
<div class="main-content">          

<div class="row">
   <div class="col-lg-12 margin-tb">
      <div class="pull-left">
         <h2>Edit User</h2>
      </div>
      <div class="pull-right"><a class="btn btn-primary" href="{{ url('app/users') }}"> Back</a></div>
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

{!! Form::model($user, ['method' => 'POST','url' => ['/app/users/update', $user->id]]) !!}

<div class="row">
   <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group"><strong>First Name:</strong>{!! Form::text('first_name', null, array('placeholder' => 'First Name','class' => 'form-control')) !!}</div>
   </div>
   <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group"><strong>Last Name:</strong>{!! Form::text('last_name', null, array('placeholder' => 'Last Name','class' => 'form-control')) !!}</div>
   </div>
   <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group"><strong>Phone:</strong>{!! Form::text('phone', null, array('placeholder' => '','class' => 'form-control','id'=>'phone1')) !!}</div>
   </div>
   <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group"><strong>Email:</strong>{!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}</div>
   </div>
   <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
         <strong>Password:</strong>
         {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
         <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
      </div>
   </div>
   <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
         <strong>Confirm Password:</strong>
         {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
         <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
      </div>
   </div>
   <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group"><strong>Role:</strong>{!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control','multiple')) !!}</div>
   </div>
   <div class="col-xs-12 col-sm-12 col-md-12 text-center"><button type="submit" class="btn btn-primary">Submit</button></div>
</div>
{!! Form::close() !!}
</div>

</div>

<script>
   $(document).ready(function(){
       $(document).on('click','.js-show-hide',function (e) {

           e.preventDefault();

           var _this = $(this);

           if (_this.hasClass('has-show-hide'))
           {
               _this.parent().find('input').attr('type','text');
               _this.html('<i class="fa fa-eye"></i>');
               _this.removeClass('has-show-hide');
           }
           else
           {
               _this.addClass('has-show-hide');
               _this.parent().find('input').attr('type','password');
               _this.html('<i class="fa fa-eye-slash"></i>');
           }


       });
   });
</script>  
@endsection
