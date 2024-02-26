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
                                <li>
                                    <a href="{{ url('/settings/general') }}">Accounts</a>
                                </li>
                                <li>Business</li>
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
         
                            <div class="col-md-3 content-container">
                                <!-- left-sidebar -->
                                @include('admin.accounts.left-sidebar') 
                            </div>
                            <!-- start right sec -->
                            <div class="col-md-9 content-wrapper">
                                <div class="formCover">
                                    <!-- section -->
                                    
                                    <div class="col-sm-12 ">
                                            <!-- row -->
                                        <div class="row">
                                            <div class="col-md-12">
                                            <h4 class="card-title mb-1 mt-3">Email Configration  </h4>
                                            <p class="pb-border"> Your email config details  </p>
                                            </div>
                                                                                        
                                            @if ($message = Session::get('success'))
                                                    <div class="col-md-12">   
                                                        <div class="alert alert-success">
                                                        <strong>{{ $message }}</strong> 
                                                        </div>
                                                    </div>
                                            @endif
                                            <div class="col-md-12">
                                                <form action="{{url('/config/email/save')}}"  method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-body">
                                                        <div class="card radius shadow-sm">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    @php
                                                                     $company_name = '';
                                                                    if ($email_config) {
                                                                        $company_name = Helper::company_name($email_config->business_id);
                                                                    }
                                                                      
                                                                    @endphp
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="company">Company or business name </label>
                                                                            <input type="text" name="company" class="form-control" id="company" placeholder="Company" value="{{$company_name?$company_name:'' }}" readonly >
                                                                            @if ($errors->has('company'))
                                                                            <div class="error text-danger">
                                                                            {{ $errors->first('company') }}
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                        <label>Driver Name <span class="text-danger">*</span></label>
                                                                        <input class="form-control " type="text" name="driver" value="@if($email_config){{$email_config->driver?$email_config->driver:''}} @endif" >
                                                                        @if ($errors->has('driver'))
                                                                        <div class="error text-danger">
                                                                            {{ $errors->first('driver') }}
                                                                        </div>
                                                                        @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label>Encryption <span class="text-danger">*</span></label>
                                                                            <select class="form-control" name="encryption" >
                                                                            <option value="">Select</option>
                                                                            <option value="ssl"  @if ($email_config) @if($email_config->encryption =='ssl') selected @endif @endif>ssl</option>
                                                                            <option value="tls" @if ($email_config) @if($email_config->encryption =='tls') selected @endif @endif>tls</option>
                                                                            {{-- @foreach($countries as $country)
                                                                            <option value="{{ $country->id }}" @if($country->id == $business->country_id) selected="" @endif >{{ $country->name }}</option>
                                                                            @endforeach --}}
                                                                            </select>
                                                                            @if ($errors->has('encryption'))
                                                                            <div class="error text-danger">
                                                                            {{ $errors->first('encryption') }}
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label>Host Name <span class="text-danger">*</span></label>
                                                                            <input class="form-control " type="text" name="host" value="@if($email_config){{$email_config->host?$email_config->host:''}}@endif" >
                                                                            @if ($errors->has('host'))
                                                                            <div class="error text-danger">
                                                                            {{ $errors->first('host') }}
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                        <label>Port<span class="text-danger">*</span></label>
                                                                        <input class="form-control number_only" type="text" name="port" value="@if($email_config){{$email_config->port?$email_config->port:''}}@endif" >
                                                                        @if ($errors->has('port'))
                                                                        <div class="error text-danger">
                                                                            {{ $errors->first('port') }}
                                                                        </div>
                                                                        @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                 <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                        <label>Username  <span class="text-danger">*</span></label>
                                                                        <input class="form-control" type="text" name="username" value="@if($email_config){{$email_config->user_name?$email_config->user_name:''}} @endif" >
                                                                        @if ($errors->has('username'))
                                                                        <div class="error text-danger">
                                                                            {{ $errors->first('username') }}
                                                                        </div>
                                                                        @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label>password<span class="text-danger">*</span></label>
                                                                            <input class="form-control " type="text" name="password" value="@if($email_config){{$email_config->password?$email_config->password:''}}@endif" >
                                                                            @if ($errors->has('password'))
                                                                            <div class="error text-danger">
                                                                            {{ $errors->first('password') }}
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row"> 
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label>Sender Name <span class="text-danger">*</span></label>
                                                                            <input class="form-control " id="" type="text" name="sender_name" value="@if($email_config){{$email_config->sender_name?$email_config->sender_name:''}} @endif" >
                                                                            @if ($errors->has('sender_name'))
                                                                            <div class="error text-danger">
                                                                            {{ $errors->first('sender_name') }}
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group">
                                                                                <label>Sender Email <span class="text-danger">*</span></label>
                                                                                <input class="form-control" type="email" name="sender_email" value="@if($email_config){{$email_config->sender_email?$email_config->sender_email:''}}@endif" >
                                                                                @if ($errors->has('sender_email'))
                                                                                    <div class="error text-danger">
                                                                                        {{ $errors->first('sender_email') }}
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                            </div>
                                                            <div class="card-footer">
                                                                <div class="text-right">
                                                                    <button class="btn btn-success" type="submit">Save</button>
                                                                </div>
                                                                <div class="text-center">
                                                                    <div class="error"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <!--  -->
                       
                                            </div>
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
   //

   
   });
                     
</script>  
@endsection
