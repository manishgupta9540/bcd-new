@extends('layouts.app')
@section('content')

<style>
  #header{
    display:none;
}

</style>
  <main id="main">
  <section class="full-page">
    <div class="side-beta-image">
    <h1 class="logo mylogo"><a href="{{ url('/') }}"> BCD </a> </h1>
      
    </div>
    
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-8 offset-2">
            <form action="{{route('/business-info')}}" method="post" role="form" class=" login-form signup-do">
                @csrf
                <!-- <h1 class="logo text-center">BCD</h1>  -->
                <!-- <img class="logo" src=""> -->

                <h3 class="heading-form text-center">Complete your business profile</h3>
                
                <div class="row">
                  <div class="col-md-12">
                  <h4 class="card-title mb-3">Your login information </h4> 
                     <p>  </p>         
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>First Name <span class="text-danger">*</span></label>
                      <input class="form-control" type="text"  name="first_name" value="{{ $user->first_name }}">
                           @if ($errors->has('first_name'))
                            <div class="error text-danger">
                                {{ $errors->first('first_name') }}
                            </div>
                            @endif
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Last Name </label>
                      <input class="form-control" type="text" name="last_name" value="{{ $user->last_name }}">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Email <span class="text-danger">*</span></label>
                      <input class="form-control" type="text"  name="email" value="{{ $user->email }}" readonly>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    
                  </div>
                </div>

                <!-- business details -->
            <!-- business details -->
            <div class="row">
               <div class="col-md-12">
                 <h4 class="card-title mb-3 mt-3">Business Information </h4> 
                  <p>  </p>         
               </div>
            
                  <div class="col-md-12">          

                     <div class="form-group">
                            <label for="company">Company or business name <span class="text-danger">*</span></label>
                            <input type="text" name="company" class="form-control" id="company" placeholder="Company" value="{{ old('company') }}">
                            @if ($errors->has('company'))
                            <div class="error text-danger">
                                {{ $errors->first('company') }}
                            </div>
                            @endif
                     </div>

                     <div class="row">
                        <div class="col-sm-6">
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
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>State <span class="text-danger">*</span></label>
                           <input class="form-control " type="text" name="state" value="{{old('state')}}">
                           @if ($errors->has('state'))
                            <div class="error text-danger">
                                {{ $errors->first('state') }}
                            </div>
                            @endif
                        </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-sm-12">
                          <div class="form-group">
                             <label>City/Town/District <span class="text-danger">*</span></label>
                             <input class="form-control " type="text" name="city" value="{{old('city')}}">
                             @if ($errors->has('city'))
                            <div class="error text-danger">
                                {{ $errors->first('city') }}
                            </div>
                            @endif
                          </div>
                        
                        </div>
                        
                     </div>

                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Pin Code<span class="text-danger">*</span></label>
                           <input class="form-control number_only" type="text" name="pin_code" value="{{old('pin_code')}}">
                           @if ($errors->has('pin_code'))
                            <div class="error text-danger">
                                {{ $errors->first('pin_code') }}
                            </div>
                            @endif
                        </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                             <label>Address (HO) <span class="text-danger">*</span></label>
                             <input class="form-control" type="text" name="address" value="{{old('address')}}">
                             @if ($errors->has('address'))
                            <div class="error text-danger">
                                {{ $errors->first('address') }}
                            </div>
                            @endif
                          </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Email <span class="text-danger">*</span></label>
                           <input class="form-control" type="email" name="business_email" value="{{old('business_email')}}">
                           @if ($errors->has('business_email'))
                            <div class="error text-danger">
                                {{ $errors->first('business_email') }}
                            </div>
                            @endif
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Phone Number <span class="text-danger">*</span></label>
                           <input type="hidden"  id="code" name ="primary_phone_code" value="91" >
                           <input type="hidden"  id="iso" name ="primary_phone_iso" value="in" >
                           <input class="form-control number_only " id="phone1"  type="text" name="business_phone_number" value="{{ old('business_phone_number') }}">
                           @if ($errors->has('business_phone_number'))
                            <div class="error text-danger">
                                {{ $errors->first('business_phone_number') }}
                            </div>
                            @endif
                        </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Website </label>
                           <input class="form-control" type="text" name="website" value="{{old('website')}}">
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Type of facility </label>
                           <input class="form-control " type="text" name="type_of_facility" value="{{old('type_of_facility')}}">
                        </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>GST Number <span class="text-danger">*</span></label>
                           <input class="form-control" type="text" name="gst_number" value="{{old('gst_number')}}">
                           @if ($errors->has('gst_number'))
                            <div class="error text-danger">
                                {{ $errors->first('gst_number') }}
                            </div>
                            @endif
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>TIN Number <span class="text-danger">*</span> </label>
                           <input class="form-control" type="text" name="tin_number" value="{{old('tin_number')}}">
                           @if ($errors->has('tin_number'))
                            <div class="error text-danger">
                                {{ $errors->first('tin_number') }}
                            </div>
                            @endif
                        </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Contract Signed By <span class="text-danger">*</span></label>
                           <input class="form-control" type="text" name="contract_signed_by" value="{{old('contract_signed_by')}}">
                           <small class="text-muted">(Person name who signed the contract)</small>
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>HR name <span class="text-danger">*</span></label>
                           <input class="form-control" type="text" name="hr_name" value="{{old('hr_name')}}">

                           @if ($errors->has('hr_name'))
                            <div class="error text-danger">
                                {{ $errors->first('hr_name') }}
                            </div>
                            @endif
                        </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Work Order Date <span class="text-danger">*</span></label>
                           <input class="form-control commonDatepicker" type="text" name="work_order_date" value="{{ date('d-m-Y') }}">
                           @if ($errors->has('work_order_date'))
                            <div class="error text-danger">
                                {{ $errors->first('work_order_date') }}
                            </div>
                            @endif
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Work Operating Date <span class="text-danger">*</span></label>
                           <input class="form-control commonDatepicker" type="text" name="work_operating_date" value="{{ date('d-m-Y')  }}">
                           @if ($errors->has('work_operating_date'))
                            <div class="error text-danger">
                                {{ $errors->first('work_operating_date') }}
                            </div>
                            @endif
                        </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Billing Details </label>
                           <input class="form-control" type="text" name="billing_detail" value="{{old('billing_detail')}}">
                           @if ($errors->has('billing_detail'))
                            <div class="error text-danger">
                                {{ $errors->first('billing_detail') }}
                            </div>
                            @endif
                        </div>
                        </div>
                        <div class="col-sm-6">
                        
                        </div>
                     </div>

                  </div>
            </div>
            <!-- ./business detail -->

            <!-- Owner/HOD Information  -->
            <div class="row">
               <div class="col-md-12">
                 <h4 class="card-title mb-3 mt-3">Owner/HOD Information </h4> 
                  <p>  </p>         
               </div>
                  <div class="col-md-12">    
                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>First Name <span class="text-danger">*</span></label>
                           <input class="form-control" type="text"  name="owner_first_name" value="{{old('owner_first_name')}}">
                           @if ($errors->has('owner_first_name'))
                            <div class="error text-danger">
                                {{ $errors->first('owner_first_name') }}
                            </div>
                            @endif
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Last Name </label>
                           <input class="form-control" type="text" name="owner_last_name" value="{{old('owner_last_name')}}">
                        </div>
                        </div>
                     </div>      
                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Email <span class="text-danger">*</span></label>
                           <input class="form-control" type="email" name="owner_email" value="{{old('owner_email')}}">
                           @if ($errors->has('owner_email'))
                            <div class="error text-danger">
                                {{ $errors->first('owner_email') }}
                            </div>
                            @endif
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Phone Number <span class="text-danger">*</span></label>
                           <input class="form-control number_only"  id="phone2" type="text" name="owner_phone_number" value="{{old('owner_phone_number')}}">
                           @if ($errors->has('owner_phone_number'))
                            <div class="error text-danger">
                                {{ $errors->first('owner_phone_number') }}
                            </div>
                            @endif
                        </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Designation <span class="text-danger">*</span></label>
                           <input class="form-control" type="text" name="owner_designation" value="{{old('owner_designation')}}">
                           @if ($errors->has('owner_designation'))
                            <div class="error text-danger">
                                {{ $errors->first('owner_designation') }}
                            </div>
                            @endif
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Landline Number </label>
                           <input class="form-control number_only" type="text" name="owner_landline_number" value="{{old('owner_landline_number')}}">
                        </div>
                        </div>
                     </div>
                  </div>
            </div>
            <!-- ./ -->
            <!-- Owner/HOD Information  -->
            <div class="row">
               <div class="col-md-12">
                 <h4 class="card-title mb-3 mt-3">Dealing Officer Details</h4> 
                  <p>  </p>         
               </div>
                  <div class="col-md-12">    
                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>First Name <span class="text-danger">*</span></label>
                           <input class="form-control" type="text"  name="dealing_first_name" value="{{old('dealing_first_name')}}">
                           @if ($errors->has('dealing_first_name'))
                            <div class="error text-danger">
                                {{ $errors->first('dealing_first_name') }}
                            </div>
                            @endif
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Last Name </label>
                           <input class="form-control" type="text" name="dealing_last_name" value="{{old('dealing_last_name')}}">
                        </div>
                        </div>
                     </div>      
                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Email <span class="text-danger">*</span></label>
                           <input class="form-control" type="email" name="dealing_email" value="{{old('dealing_email')}}">
                           @if ($errors->has('dealing_email'))
                            <div class="error text-danger">
                                {{ $errors->first('dealing_email') }}
                            </div>
                            @endif
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Phone Number <span class="text-danger">*</span></label>
                           <input class="form-control number_only" type="text" id="phone3" name="dealing_phone_number" value="{{old('dealing_phone_number')}}">
                           @if ($errors->has('dealing_phone_number'))
                            <div class="error text-danger">
                                {{ $errors->first('dealing_phone_number') }}
                            </div>
                            @endif
                        </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Designation <span class="text-danger">*</span></label>
                           <input class="form-control" type="text" name="dealing_designation" value="{{old('dealing_designation')}}">
                           @if ($errors->has('dealing_designation'))
                            <div class="error text-danger">
                                {{ $errors->first('dealing_designation') }}
                            </div>
                            @endif
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Landline Number </label>
                           <input class="form-control number_only" type="text" name="dealing_landline_number" value="{{old('dealing_landline_number')}}">
                        </div>
                        </div>
                     </div>
                  </div>
            </div>
            <!-- ./ -->
            <!-- Owner/HOD Information  -->
            <div class="row">
               <div class="col-md-12">
                 <h4 class="card-title mb-3 mt-3">Account Officer Details </h4> 
                  <p>  </p>         
               </div>
                  <div class="col-md-12">    
                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>First Name </label>
                           <input class="form-control" type="text"  name="account_first_name" value="{{old('account_first_name')}}">
                           @if ($errors->has('account_first_name'))
                            <div class="error text-danger">
                                {{ $errors->first('account_first_name') }}
                            </div>
                            @endif
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Last Name </label>
                           <input class="form-control" type="text" name="account_last_name" value="{{old('account_last_name')}}">
                        </div>
                        </div>
                     </div>      
                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Email </label>
                           <input class="form-control" type="email" name="account_email" value="{{old('account_email')}}">
                           @if ($errors->has('account_email'))
                            <div class="error text-danger">
                                {{ $errors->first('account_email') }}
                            </div>
                            @endif
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Phone Number </label>
                           <input class="form-control number_only" id="phone4" type="text" name="account_phone_number" value="{{old('account_email')}}">
                           @if ($errors->has('account_phone_number'))
                            <div class="error text-danger">
                                {{ $errors->first('account_phone_number') }}
                            </div>
                            @endif
                        </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Designation </label>
                           <input class="form-control" type="text" name="account_designation" value="{{old('account_designation')}}">
                           @if ($errors->has('account_designation'))
                            <div class="error text-danger">
                                {{ $errors->first('account_designation') }}
                            </div>
                            @endif
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Landline Number </label>
                           <input class="form-control number_only" type="text" name="account_landline_number" value="{{old('account_landline_number')}}">
                        </div>
                        </div>
                     </div>
                  </div>
            </div>
            <!-- ./ -->
            <!-- ./ -->     
              
            <div class="text-center mt-30"><button type="submit" name="submit"  class="btn-submit">Submit</button></div>
             

            </form>
          </div>
        </div>
     
    </div>
  </section>
    
  </main><!-- End #main -->

@endsection
