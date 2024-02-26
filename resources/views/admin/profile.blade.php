@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
            <!-- ============ Body content start ============= -->
            <div class="main-content">				
 
                <div class="row">
				<div class="card text-left">
               <div class="card-body">
               
               <div class="col-md-8 offset-md-2">
               <form class="mt-2" method="post" action="">
                @csrf
			   <div class="row">
			    <div class="col-md-8">
	              <h4 class="card-title mb-3">Profile </h4> 
				    <p> Your profile information </p>			
				</div>
				
			   <div class="col-md-10">			   
			
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name" aria-describedby="emailHelp" placeholder="Enter full name" value="{{ $profile->name }}">
                            @if ($errors->has('name'))
                            <div class="error text-danger">
                                {{ $errors->first('name') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" class="form-control" maxlength="10" id="phone" placeholder="Enter phone" value="{{ $profile->phone }}">
                            @if ($errors->has('phone'))
                            <div class="error text-danger">
                                {{ $errors->first('phone') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email" value="{{ $profile->email }}" readonly="">
                           
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
                            <input type="text" name="company" class="form-control" id="company" placeholder="Company" value="{{ $business->company_name }}">
                        </div>
                   
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" name="address" class="form-control" id="address"  placeholder="Enter address" value="{{ $business->address_line1 }}">
                            <small id="address" class="form-text text-muted">(e.g. Link road, A-52..)</small>
                            @if ($errors->has('address'))
                            <div class="error text-danger">
                                {{ $errors->first('address') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="zipcode">Zipcode</label>
                            <input type="text" name="zipcode" class="form-control" id="zipcode" aria-describedby="zipcode" placeholder="Enter zipcode" value="{{ $business->zipcode }}">
                            @if ($errors->has('zipcode'))
                            <div class="error text-danger">
                                {{ $errors->first('zipcode') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="city" name="city" class="form-control" id="city" aria-describedby="cityHelp" placeholder="Enter city" value="{{ $business->city_name }}">
                            @if ($errors->has('city'))
                            <div class="error text-danger">
                                {{ $errors->first('city') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="state">State</label>
                            <input type="state" name="state" class="form-control" id="state" aria-describedby="stateHelp" placeholder="Enter state" value="{{ $business->state_name }}">
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

                    <button type="submit" class="btn btn-primary">Update</button>
                   
				</div>
						
             </div>
             </form>
               </div>
            </div>
				
            </div><!-- Footer Start -->
            <div class="flex-grow-1"></div>
			
			
		
        </div>
@endsection