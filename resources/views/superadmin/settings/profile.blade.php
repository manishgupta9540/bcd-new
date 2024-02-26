@extends('layouts.superadmin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
<!-- ============ Body content start ============= -->
<div class="main-content">				
    {{-- <div class="row">

        <div class="page-header ">

        <div class=" align-items-center">
            <div class="col">
                <h3 class="page-title">Settings / General</h3>                               
            </div>
        </div>
        </div>
    </div> --}}
    <div class="row">
        <div class="col-sm-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ url('/app/home') }}">Dashboard</a>
                </li>
                <li>Profile</li>
            </ul>
        </div>
        <div class="col-sm-1 back-arrow">
            <div class="text-right">
               <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
            </div>
      </div>
    </div>
    <div class="row">
		<div class="col-md-3 content-container">
             
            <!-- left-sidebar -->
            @include('superadmin.settings.left-sidebar') 
            <!-- ./  -->
        </div>
         <!-- start right sec -->
         <div class="col-sm-9 content-wrapper bg-white">
                
            <div class="formCover" style="height: 100vh;">
                <!-- section -->
                <section>
                   <!-- row -->
                   <div class="row">
                    <div class="col-md-12">
                       <h4 class="card-title mb-1 mt-3">Profile Information </h4>
                       <p class="pb-border"> Your primary account info  </p>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="col-md-12">   
                           <div class="alert alert-success">
                           <strong>{{ $message }}</strong> 
                           </div>
                        </div>
                     @endif
                    <div class="col-md-12">
                     <form class="mt-2" method="post" enctype="multipart/form-data" action="{{ url('app/settings/profile/update') }}">
                        @csrf
                       <div class="row">
                          <div class="col-sm-6">
                             <div class="form-group">
                                <label>First name <span class="text-danger">*</span></label>
                                <input class="form-control " type="text" name="first_name" value="{{ $profile->first_name }}">
                                @if ($errors->has('first_name'))
                                <div class="error text-danger">
                                   {{ $errors->first('first_name') }}
                                </div>
                                @endif
                             </div>
                          </div>
                          <div class="col-sm-6">
                             <div class="form-group">
                                <label>Last name<span class="text-danger">*</span></label>
                                <input class="form-control number_only" type="text" name="last_name" value="{{ $profile->last_name }}">
                                @if ($errors->has('last_name'))
                                <div class="error text-danger">
                                   {{ $errors->first('last_name') }}
                                </div>
                                @endif
                             </div>
                          </div>
                       </div>
                       
                       <div class="row">
                          <div class="col-sm-6">
                             <div class="form-group">
                                <label>Phone <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="phone1" name="phone" value="{{ $profile->phone }}">
                                @if ($errors->has('phone'))
                                <div class="error text-danger">
                                   {{ $errors->first('phone') }}
                                </div>
                                @endif
                             </div>
                          </div>
                          <div class="col-sm-6">
                             <div class="form-group">
                                <label> Email </label>
                                <input class="form-control" type="email" name="email" value="{{ $profile->email }}" readonly>
                                @if ($errors->has('email'))
                                <div class="error text-danger">
                                   {{ $errors->first('email') }}
                                </div>
                                @endif
                             </div>
                          </div>
                       </div>
                       
                       <div class="row">
                          <div class="col-sm-6">
                             <div class="form-group">
                                
                             </div>
                          </div>
                         
                       </div>

                       <div class="text-center">
                           <button type="submit" class="btn btn-md btn-primary">Update</button>
                       </div>
                     </form>
                    </div>
                 </div>
                 <!-- ./business detail -->
                </section>
                <!-- ./section -->
                <!--  -->
                <!-- ./section -->
            </div>
        </div>
        <!-- end right sec -->
    </div>
</div>
</div>



@endsection