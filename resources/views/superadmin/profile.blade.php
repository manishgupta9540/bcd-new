@extends('layout.superadmin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
            <!-- ============ Body content start ============= -->
            <div class="main-content">				
 
                <div class="row">
				<div class="card text-left">
               <div class="card-body">
               
               <div class="col-md-8 offset-md-2">
               <form class="mt-2" method="post" action="{{ route('/customers/store') }}">
                @csrf
			   <div class="row">
			    <div class="col-md-8">
	              <h4 class="card-title mb-3">Profile </h4> 
				    <p> Your profile information </p>			
				</div>
				
			   <div class="col-md-10">			   
			
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name" aria-describedby="emailHelp" placeholder="Enter full name" value="{{ old('name') }}">
                            @if ($errors->has('name'))
                            <div class="error text-danger">
                                {{ $errors->first('name') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" class="form-control" id="phone" aria-describedby="phone" placeholder="Enter phone" value="{{ old('phone') }}">
                            @if ($errors->has('phone'))
                            <div class="error text-danger">
                                {{ $errors->first('phone') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                            <div class="error text-danger">
                                {{ $errors->first('email') }}
                            </div>
                            @endif
                            <small id="emailHelp" class="form-text text-muted">Valid an email address</small>
                        </div>

				</div>
						
             </div>
             <!-- business details -->
             <div class="row">
			    <div class="col-md-8">
	              <h4 class="card-title mb-3">Business Information </h4> 
				    <p>  </p>			
				</div>
				
			   <div class="col-md-10">			   

                        <div class="form-group">
                            <label for="company">Company</label>
                            <input type="text" name="company" class="form-control" id="company" placeholder="Company" value="{{ old('email') }}">
                        </div>
                   
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" name="address" class="form-control" id="address"  placeholder="Enter address" value="{{ old('address') }}">
                            <small id="address" class="form-text text-muted">(e.g. Link road, A-52..)</small>
                            @if ($errors->has('address'))
                            <div class="error text-danger">
                                {{ $errors->first('address') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="zipcode">Zipcode</label>
                            <input type="text" name="zipcode" class="form-control" id="zipcode" aria-describedby="zipcode" placeholder="Enter zipcode" value="{{ old('zipcode') }}">
                            @if ($errors->has('zipcode'))
                            <div class="error text-danger">
                                {{ $errors->first('zipcode') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="city" name="city" class="form-control" id="city" aria-describedby="cityHelp" placeholder="Enter city" value="{{ old('city') }}">
                            @if ($errors->has('city'))
                            <div class="error text-danger">
                                {{ $errors->first('city') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="state">State</label>
                            <input type="state" name="state" class="form-control" id="state" aria-describedby="stateHelp" placeholder="Enter state" value="{{ old('state') }}">
                            @if ($errors->has('state'))
                            <div class="error text-danger">
                                {{ $errors->first('state') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="company">Country</label>
                            <select class="form-control" name="country" >
                                <option value="1">India</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                   
				</div>
						
             </div>
             </form>
               </div>
            </div>
				
            </div><!-- Footer Start -->
            <div class="flex-grow-1"></div>
			
			
		
        </div>
@endsection