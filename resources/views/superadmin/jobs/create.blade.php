@extends('layouts.superadmin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
<!-- ============ Body content start ============= -->
<div class="main-content">
<div class="row">
   <div class="card text-left">
      <div class="card-body">
         <div class="row">
            <div class="col-md-8">
               <h4 class="card-title mb-3">Create a job </h4>
               <p>Customer and Candidate detail </p>
            </div>
            <div class="col-md-10">
               <form class="mt-2" method="post" action="{{ route('/job/store') }}">
                  @csrf
                  <!-- select customer  -->
                  <div class="form-group">
                     <label for="service">Customer</label>
                     <select class="form-control" name="customer">
                        <option value="">-Select-</option>
                        <option value="{{ Auth::user()->business_id }}">Your Own</option>
                        @if( count($customers) > 0 )
                        @foreach($customers as $item)
                        <option value="{{ $item->id }}">{{ $item->company_name.' '.'('.$item->name.')' }}</option>
                        @endforeach
                        @endif
                     </select>
                     @if ($errors->has('customer'))
                     <div class="error text-danger">
                        {{ $errors->first('customer') }}
                     </div>
                     @endif
                  </div>
                  <div class="form-group">
                     <label for="job_name">Job Name</label>
                     <input type="text" name="job_name" class="form-control" placeholder="Job name" value="{{ old('job_name') }}">
                     <small class="form-text text-muted">(e.g Job-023, Job-Aadhar-21)</small>
                     @if ($errors->has('job_name'))
                     <div class="error text-danger">
                        {{ $errors->first('job_name') }}
                     </div>
                     @endif
                  </div>
                  <div class="form-group">
                     <label for="service">Verifiction Type</label>
                     <select class="form-control" name="service">
                        <option value="">-Select-</option>
                        <option value="1">Address Verification</option>
                        <option value="2">Pan Verification</option>
                     </select>
                     @if ($errors->has('service'))
                     <div class="error text-danger">
                        {{ $errors->first('service') }}
                     </div>
                     @endif
                  </div>
                  <div class="row">
                     <div class="col-md-8">
                        <h4 class="card-title mb-3">Enter the candidate detail</h4>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="name">First name</label>
                     <input type="text" name="first_name" class="form-control"  placeholder="Enter first name" value="{{ old('first_name') }}">
                     @if ($errors->has('first_name'))
                     <div class="error text-danger">
                        {{ $errors->first('first_name') }}
                     </div>
                     @endif
                  </div>
                  <div class="form-group">
                     <label for="name">Last name</label>
                     <input type="text" name="last_name" class="form-control" placeholder="Enter last name" value="{{ old('last_name') }}">
                     @if ($errors->has('last_name'))
                     <div class="error text-danger">
                        {{ $errors->first('last_name') }}
                     </div>
                     @endif
                  </div>
                  <div class="form-group">
                     <label for="phone">Phone</label>
                     <input type="text" name="phone" class="form-control" id="phone" maxlength="10"  placeholder="Enter phone" value="{{ old('phone') }}">
                     @if ($errors->has('phone'))
                     <div class="error text-danger">
                        {{ $errors->first('phone') }}
                     </div>
                     @endif
                  </div>
                  <div class="form-group">
                     <label for="email">Email</label>
                     <input type="email" name="email" class="form-control" id="email"  placeholder="Enter email" value="{{ old('email') }}">
                     @if ($errors->has('email'))
                     <div class="error text-danger">
                        {{ $errors->first('email') }}
                     </div>
                     @endif
                  </div>
                  <button type="submit" class="btn btn-primary">Submit</button>
               </form>
            </div>
         </div>
      </div>
   </div>
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
</div>
@endsection
