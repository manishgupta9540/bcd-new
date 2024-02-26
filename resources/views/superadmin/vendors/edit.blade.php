@extends('layouts.superadmin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
            <!-- ============ Body content start ============= -->
            <div class="main-content">				
 
                <div class="row">
                    
				<div class="card text-left">
               <div class="card-body" style="height: 100vh;">
               
               <div class="col-md-8 offset-md-2">
               <form class="mt-2" method="post" action="{{ url('/app/vendor/update') }}">
                @csrf
			   <div class="row">
			    <div class="col-md-8">
	              <h4 class="card-title mb-3">Add new vendor </h4> 
				    <p> Fill the required details </p>			
				</div>
				
			   <div class="col-md-10">		

                        <!--  <div class="form-group">
                            <label for="country">Country</label>
                            <select class="form-control" name="country" >
                                <option value="1">India</option>
                            </select>
                        </div>	 -->   
			
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter first name" value="{{ $vendor->first_name}}">
                            @if ($errors->has('first_name'))
                            <div class="error text-danger">
                                {{ $errors->first('first_name') }}
                            </div>
                            @endif
                        </div>

                        <input type="hidden" name="vendor_id" class="form-control" id="vendor_id"  value="{{ $id}}">


                         <input type="hidden" name="user_id" class="form-control" id="user_id"  value="{{  $vendor->user_id}}">


                        <div class="form-group">
                            <label for="name">Last Name</label>
                            <input type="text" name="last_name" class="form-control" id="last_name"  placeholder="Enter last name" value="{{ $vendor->last_name}}">
                            @if ($errors->has('last_name'))
                            <div class="error text-danger">
                                {{ $errors->first('last_name') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" class="form-control" id="phone" maxlength="10" placeholder="Enter phone" value="{{ $vendor->phone}}">
                            @if ($errors->has('phone'))
                            <div class="error text-danger">
                                {{ $errors->first('phone') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" value="{{ $vendor->email}}">
                            @if ($errors->has('email'))
                            <div class="error text-danger">
                                {{ $errors->first('email') }}
                            </div>
                            @endif
                            
                        </div>
                          <div class="form-group">
                        <label for="company">Service <span class="text-danger">*</span></label>
                        <input type="text" name="service" class="form-control" id="service" placeholder="service" value="{{ $vendor->service}}">
                        @if ($errors->has('service'))
                        <div class="error text-danger">
                           {{ $errors->first('service') }}
                        </div>
                        @endif
                     </div>
                          <div class="form-group">
                        <label for="company">Company or business name <span class="text-danger">*</span></label>
                        <input type="text" name="company" class="form-control" id="company" placeholder="Company" value="{{$vendor->company_name}}">
                        @if ($errors->has('company'))
                        <div class="error text-danger">
                           {{ $errors->first('company') }}
                        </div>
                        @endif
                     </div>
                     <div class="form-group">
                           <label>Country <span class="text-danger">*</span></label>
                           <select class="form-control" name="country">
                           <option value="">Select Country</option>
                            @foreach($countries as $country)
                              <option value="{{ $country->id }}" @if($country->id == $vendor->country_id) selected="" @endif >{{ $country->name }}</option>
                            @endforeach
                           </select>
                           @if ($errors->has('country'))
                            <div class="error text-danger">
                                {{ $errors->first('country') }}
                            </div>
                            @endif
                        </div>
                         <div class="form-group">
                           <label>State <span class="text-danger">*</span></label>
                           <input class="form-control " type="text" name="state" value="{{$vendor->state}}">
                           @if ($errors->has('state'))
                            <div class="error text-danger">
                                {{ $errors->first('state') }}
                            </div>
                            @endif
                        </div>
                        <div class="form-group">
                             <label>City/Town/District <span class="text-danger">*</span></label>
                             <input class="form-control " type="text" name="city" value="{{$vendor->city}}">
                             @if ($errors->has('city'))
                            <div class="error text-danger">
                                {{ $errors->first('city') }}
                            </div>
                            @endif
                          </div>
                           <div class="form-group">
                              <label>Pin Code<span class="text-danger">*</span></label>
                              <input class="form-control number_only" type="text" name="pincode" value="{{$vendor->pincode}}">
                              @if ($errors->has('pincode'))
                              <div class="error text-danger">
                                 {{ $errors->first('pincode') }}
                              </div>
                              @endif
                           </div>
                            <div class="form-group">
                             <label>Address (HO) <span class="text-danger">*</span></label>
                             <input class="form-control" type="text" name="address" value="{{$vendor->address}}">
                              @if ($errors->has('address'))
                              <div class="error text-danger">
                                 {{ $errors->first('address') }}
                              </div>
                              @endif
                          </div>
                           <div class="form-group">
                             <label>Status<span class="text-danger">*</span></label>
                             <input  type="radio" id="status" name="status" value="1" {{$vendor->status == 1 ? 'checked' : ''}}>Active
                             <input type="radio" id="status" name="status" value="0"
                             {{$vendor->status == 0 ? 'checked' : ''}} >Inactive
                              @if ($errors->has('address'))
                              <div class="error text-danger">
                                 {{ $errors->first('address') }}
                              </div>
                              @endif
                          </div>

                        <div class="col-md-10">             

                            <button type="submit" class="btn btn-primary">Submit</button>

				        </div>	
             </div>
            <!--  -->
          
             <!--  -->

             </form>
               </div>
            </div>
				
            </div><!-- Footer Start -->
            <div class="flex-grow-1"></div>
			
        </div>


@endsection