@extends('layouts.superadmin')
<style>
    span.show-hide-password {
        position: absolute;
        top: 34px;
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
                    
				<div class="card text-left">
               <div class="card-body" style="height: 100vh;">
               
               <div class="col-md-8 offset-md-2">
               <form class="mt-2" method="post" action="{{ url('/app/vendor/save') }}">
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
                            <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter first name" value="{{ old('first_name') }}">
                            @if ($errors->has('first_name'))
                            <div class="error text-danger">
                                {{ $errors->first('first_name') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="name">Last Name</label>
                            <input type="text" name="last_name" class="form-control" id="last_name"  placeholder="Enter last name" value="{{ old('last_name') }}">
                            @if ($errors->has('last_name'))
                            <div class="error text-danger">
                                {{ $errors->first('last_name') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" class="form-control" id="phone" maxlength="10" placeholder="Enter phone" value="{{ old('phone') }}">
                            @if ($errors->has('phone'))
                            <div class="error text-danger">
                                {{ $errors->first('phone') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                            <div class="error text-danger">
                                {{ $errors->first('email') }}
                            </div>
                            @endif
                            
                        </div>
                        <div class="form-group">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" id="password" maxlength="10" placeholder="Enter password" value="{{ old('password') }}">
                            <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                            @if ($errors->has('password'))
                            <div class="error text-danger">
                                {{ $errors->first('password') }}
                            </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="confirm-password">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="confirm-password" class="form-control" id="confirm-password" maxlength="10" placeholder="Enter confirm-password" value="{{ old('confirm-password') }}">
                            <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                            @if ($errors->has('confirm-password'))
                            <div class="error text-danger">
                                {{ $errors->first('confirm-password') }}
                            </div>
                            @endif
                        </div>
                          <div class="form-group">
                        <label for="company">Service <span class="text-danger">*</span></label>
                        <input type="text" name="service" class="form-control" id="service" placeholder="service" value="{{ old('service') }}">
                        @if ($errors->has('service'))
                        <div class="error text-danger">
                           {{ $errors->first('service') }}
                        </div>
                        @endif
                     </div>
                          <div class="form-group">
                        <label for="company">Company or business name <span class="text-danger">*</span></label>
                        <input type="text" name="company" class="form-control" id="company" placeholder="Company" value="{{ old('company') }}">
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
                              <option value="{{ $country->id }}" @if($country->id == 101) selected="" @endif >{{ $country->name }}</option>
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
                           <input class="form-control " type="text" name="state" value="{{old('state')}}">
                           @if ($errors->has('state'))
                            <div class="error text-danger">
                                {{ $errors->first('state') }}
                            </div>
                            @endif
                        </div>
                        <div class="form-group">
                             <label>City/Town/District <span class="text-danger">*</span></label>
                             <input class="form-control " type="text" name="city" value="{{old('city')}}">
                             @if ($errors->has('city'))
                            <div class="error text-danger">
                                {{ $errors->first('city') }}
                            </div>
                            @endif
                          </div>
                           <div class="form-group">
                              <label>Pin Code<span class="text-danger">*</span></label>
                              <input class="form-control number_only" type="text" name="pincode" value="{{old('pincode')}}">
                              @if ($errors->has('pincode'))
                              <div class="error text-danger">
                                 {{ $errors->first('pincode') }}
                              </div>
                              @endif
                           </div>
                            <div class="form-group">
                             <label>Address (HO) <span class="text-danger">*</span></label>
                             <input class="form-control" type="text" name="address" value="{{old('address')}}">
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