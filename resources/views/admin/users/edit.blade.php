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
          <li>Edit</li>
          </ul>
      </div>
      <!-- ============Back Button ============= -->
      <div class="col-sm-1 back-arrow">
          <div class="text-right">
          <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
          </div>
      </div>
  </div> 
{{-- <div class="row">
   <div class="col-lg-12 margin-tb">
      <div class="pull-left">
         <h2>Edit New User</h2>
      </div>
      <div class="pull-right"><a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a></div>
   </div>
</div> --}}
{{-- @if (count($errors) > 0)
<div class="alert alert-danger">
   <strong>Whoops!</strong> There were some problems with your input.<br><br>
   <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
   </ul>
</div>
@endif{!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group"><strong>First Name:</strong>{!! Form::text('first_name', null, array('placeholder' => 'First Name','class' => 'form-control')) !!}</div>
   </div>
   <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group"><strong>Last Name:</strong>{!! Form::text('last_name', null, array('placeholder' => 'Last Name','class' => 'form-control')) !!}</div>
   </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
         <strong>Phone:</strong>
         <input type="hidden" id="code" name ="primary_phone_code" value="{{$user->phone_code}}" >
         <input type="hidden" id="iso" name ="primary_phone_iso" value="{{$user->phone_iso}}" >
         {!! Form::text('phone', null, array( 'id'=>'phone1', 'class' => 'form-control')) !!}
      </div>
   </div>
   <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group"><strong>Email:</strong>{!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control' ,'readonly')) !!}</div>
   </div>
   {{-- <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group"><strong>Password:</strong>{!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}</div>
   </div>
   <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group"><strong>Confirm Password:</strong>{!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}</div>
   </div> 
   <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
         <strong>Select Services:</strong> <span class="text-danger">*</span>
       
          <div class="col-sm-12">
         <div class="form-group">
            @foreach ($checks as $check)

@endif --}}
{!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', base64_encode($user->id)]]) !!}
<div class="form-body">
   <div class="card radius shadow-sm">
      <div class="card-body">
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 offset-md-3">
               <p class="card-title" style="font-size:20px;border-bottom:1px solid #ddd;">Edit an User</p>
               <div class="row">
                  <div class="col-12">
                     <div class="form-group"><strong>First Name:</strong> <span class="text-danger">*</span>{!! Form::text('first_name', null, array('placeholder' => 'First Name','class' => 'form-control')) !!}</div>
                     {{-- <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-first_name"></p> --}}
                     @if ($errors->has('first_name'))
                        <div class="error text-danger">
                           {{ $errors->first('first_name') }}
                        </div>
                     @endif
                  </div>
               </div>
               <div class="row">
                  <div class="col-12">
                     <div class="form-group"><strong>Last Name:</strong>{!! Form::text('last_name', null, array('placeholder' => 'Last Name','class' => 'form-control')) !!}</div>
                     @if ($errors->has('last_name'))
                        <div class="error text-danger">
                           {{ $errors->first('last_name') }}
                        </div>
                     @endif
                     {{-- <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-last_name"></p> --}}
                  </div>
               </div>
               <div class="row">
                  <div class="col-12">
                     <div class="form-group">
                        <strong>Phone: <span class="text-danger">*</span></strong>
                        <input type="hidden" id="code" name ="primary_phone_code" value="{{$user->phone_code}}" >
                        <input type="hidden" id="iso" name ="primary_phone_iso" value="{{$user->phone_iso}}" >
                        {!! Form::text('phone', str_replace(' ','',$user->phone), array( 'id'=>'phone1', 'class' => 'form-control')) !!}
                     </div>
                     @if ($errors->has('phone'))
                        <div class="error text-danger">
                           {{ $errors->first('phone') }}
                        </div>
                     @endif
                     {{-- <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-phone"></p> --}}
                  </div>
               </div>
               <div class="row">
                  <div class="col-12">
                     <div class="form-group"><strong>Email:</strong> <span class="text-danger">*</span>{!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}</div>
                     {{-- <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-email"></p> --}}
                     @if ($errors->has('email'))
                        <div class="error text-danger">
                           {{ $errors->first('email') }}
                        </div>
                     @endif
                  </div>
               </div>
               <div class="row">
                  <div class="col-12">
                     <div class="form-group">
                        <strong>Select Services:</strong> <span class="text-danger">*</span>
                     
                        <div class="col-sm-12">
                           <div class="form-group">
                              @foreach ($checks as $check)
      
                                 <?php  
                                 $user_check[]= ($check->checks);
                                    //  dd($services);
                                 ?>
                              @endforeach
                              @foreach($services as $service)
                                 <div class="form-check form-check-inline">
                                    <input class="form-check-input services_list" type="checkbox" name="services[]" value="{{ $service->id}}" {{ (in_array($service->id,$user_check) ) ? 'checked' : ''}}  >
                                    <label class="form-check-label" for="inlineCheckbox-{{ $service->id}}">{{ $service->name  }}</label>
                                 </div>
                              @endforeach
                        
                           </div>
                        </div>
                        @if ($errors->has('services'))
                           <div class="error text-danger">
                              {{ $errors->first('services') }}
                           </div>
                        @endif
                        {{-- <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p> --}}
                  
                        {{-- <div class="col-xs-12 col-sm-12 col-md-12">
                           <div class="form-group"><strong>Password:</strong>{!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}</div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                           <div class="form-group"><strong>Confirm Password:</strong>{!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}</div>
                        </div> --}}
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-12">
                     <div class="form-group">
                        <strong>Select a Role:</strong> <span class="text-danger">*</span>
                        <select class="select-option-field-7 role selectValue form-control" name="role" data-type="role" data-t="{{ csrf_token() }}">
                           <option value="">Select Role</option>
                           @foreach ($roles as $role)
                              <option value="{{$role->id}}" @if ($user->role == $role->id) selected @endif>{{$role->role}}</option>
                           @endforeach
                        </select>
                        @if ($errors->has('role'))
                           <div class="error text-danger">
                              {{ $errors->first('role') }}
                           </div>
                        @endif
                        {{-- <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-role"></p> --}}
                     </div>
                  </div>
               </div>

               @php
                     $values = explode(",",$user->vendor_id);
               @endphp

               <div class="form-group">
                  <strong>Vendor Name:</strong>
                  <div class="form-group">
                     <select class="select-option-field-7 role  form-control select" multiple name="vendor_id[]" id="vendor_id">
                        <option value="">Select Vendor Name</option>
                        @foreach ($vendors as $vendor)
                           <option value="{{$vendor->id}}" {{ in_array($vendor->id,$values) ? 'selected' : '' }}>{{$vendor->first_name}}</option>
                        @endforeach
                     </select>
                     <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-vendor_id"></p>
                  </div>
               </div>
            </div>
         </div>
         <div class="card-footer">
            <div class="text-center">
               <button type="submit" class="btn btn-info">Update</button>
            </div>
         </div>
      </div>
   </div>
</div>
{!! Form::close() !!}
</div>

<script>
   $(".select").select2();
</script>

@endsection
