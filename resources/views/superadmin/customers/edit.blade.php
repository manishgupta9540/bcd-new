@extends('layouts.superadmin')
@section('content')

<div class="main-content-wrap sidenav-open d-flex flex-column">
            <!-- ============ Body content start ============= -->
            <div class="main-content">          
                <div class="row">
                    <div class="col-sm-11">
                        <ul class="breadcrumb">
                        <li><a href="{{ url('app/home') }}">Dashboard</a></li>
                        <li><a href="{{ url('app/customers') }}">Customer</a></li>
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
                <div class="row">
            <div class="card text-left">
               <div class="card-body">
               
               <div class="col-md-8 offset-md-2">

                
               <form class="mt-2" method="post" action="{{ url('/app/customers/update') }}">
                @csrf
            <!-- section -->
            <div class="row">
               <div class="col-md-12">
                  <h3 style="font-size: 22px; border-bottom:1px solid #ddd; padding-bottom:6px;" class="card-title mb-1">Edit a customer ( {{ $customer->first_name.' '.$customer->last_name}} ) </h3> 
                  <p> Fill the required details </p>        
               </div>
            
               <div class="col-md-12">       

               <div class="row">
                  <div class="col-md-12">
                  <h4 class="card-title mb-3">Login information </h4> 
                     <p>  </p>         
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>First Name <span class="text-danger">*</span></label>
                      <input class="form-control" type="text"  name="first_name" value="{{ $customer->first_name }}">
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
                      <input class="form-control" type="text" name="last_name" value="{{ $customer->last_name }}">
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
                      <label>Email <span class="text-danger">*</span></label>
                      <input class="form-control" type="email" name="email" value="{{ $customer->email }}" readonly>
                          @if ($errors->has('email'))
                            <div class="error text-danger">
                                {{ $errors->first('email') }}
                            </div>
                          @endif
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Phone Number <span class="text-danger">*</span></label>
                      <input class="form-control number_only" id="phone1" type="text" name="phone" value="{{ $customer->phone }}">
                           @if ($errors->has('phone'))
                            <div class="error text-danger">
                                {{ $errors->first('phone') }}
                            </div>
                            @endif
                    </div>
                  </div>
                </div>
               
                       
               </div>   
             </div>
            <!-- ./ -->
            <!-- business details -->
            <div class="row">
               <div class="col-md-12">
                 <h4 class="card-title mb-3 mt-3">Business Information </h4> 
                  <p>  </p>         
               </div>
            
                  <div class="col-md-12">          
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                    <label for="company">Company or business name <span class="text-danger">*</span></label>
                                    <input type="text" name="company" class="form-control" id="company" placeholder="Company" value="{{ $business->company_name }}">
                                    @if ($errors->has('company'))
                                    <div class="error text-danger">
                                        {{ $errors->first('company') }}
                                    </div>
                                    @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company">Company Short name <span class="text-danger">*</span></label>
                                <input type="text" name="company_short_name" class="form-control" id="company_short_name" placeholder="Company Short Name" value="{{ $business->company_short_name }}">
                                @if ($errors->has('company_short_name'))
                                <div class="error text-danger">
                                    {{ $errors->first('company_short_name') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Country <span class="text-danger">*</span></label>
                           <select class="form-control country" name="country" id="country_id">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" @if($country->id == $business->country_id) selected @endif >{{ $country->name }}</option>
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
                           <select class="form-control state" name="state" id="state_id">
                            <option value="">Select State</option>
                                @if(count($states)>0)
                                    @foreach($states as $states)
                                        <option value="{{ $states->id }}" @if($states->id==$business->state_id) selected @endif>{{ $states->name }}</option>
                                    @endforeach
                                @endif
                            </select>
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
                             <select class="form-control" name="city" id="city_id">
                                @if(count($cities)>0)
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" @if($city->id==$business->city_id) selected @endif>{{ $city->name }}</option>
                                    @endforeach
                                @endif
                            </select>
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
                           <input class="form-control number_only" type="text" name="pincode" value="{{ $business->zipcode }}">
                           @if ($errors->has('pincode'))
                            <div class="error text-danger">
                                {{ $errors->first('pincode') }}
                            </div>
                            @endif
                        </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                             <label>Address (HO) <span class="text-danger">*</span></label>
                             <input class="form-control" type="text" name="address" value="{{ $business->address_line1 }}">
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
                           <input class="form-control" type="email" name="business_email" value="{{ $business->email }}">
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
                           <input class="form-control number_only" id="phone2" type="text" name="business_phone_number" value="{{ $business->phone }}">
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
                           <input class="form-control" type="text" name="website" value="{{ $business->website }}">
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Type of facility </label>
                           <input class="form-control " type="text" name="type_of_facility" value="{{ $business->type_of_facility }}">
                        </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>GST Number <span class="text-danger">*</span></label>
                           <input class="form-control" type="text" name="gst_number" value="{{ $business->gst_number }}">
                           @if ($errors->has('gst_number'))
                            <div class="error text-danger">
                                {{ $errors->first('gst_number') }}
                            </div>
                            @endif
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>TIN Number </label>
                           <input class="form-control" type="text" name="tin_number" value="{{ $business->tin_number }}">
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
                           <input class="form-control" type="text" name="contract_signed_by" value="{{ $business->contract_signed_by }}">
                           <small class="text-muted">(Person name who signed the contract)</small>
                           @if ($errors->has('contract_signed_by'))
                           <div class="error text-danger">
                               {{ $errors->first('contract_signed_by') }}
                           </div>
                           @endif
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>HR name <span class="text-danger">*</span></label>
                           <input class="form-control" type="text" name="hr_name" value="{{ $business->hr_name }}">

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
                           <input class="form-control commonDatepicker" type="text" name="work_order_date" value="{{ $business->work_order_date }}">
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
                           <input class="form-control commonDatepicker" type="text" name="work_operating_date" value="{{ $business->work_operating_date }}">
                           @if ($errors->has('work_operating_date'))
                            <div class="error text-danger">
                                {{ $errors->first('work_operating_date') }}
                            </div>
                            @endif
                        </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-sm-12">
                        <div class="form-group">
                           <label>Billing Details </label>
                           <input class="form-control" type="text" name="billing_detail" value="{{ $business->billing_detail }}">
                           @if ($errors->has('billing_detail'))
                            <div class="error text-danger">
                                {{ $errors->first('billing_detail') }}
                            </div>
                            @endif
                        </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>HSN/SAC </label>
                                <input class="form-control" type="text" name="hsn" value="{{ $business->hsn_or_sac }}">
                                @if ($errors->has('hsn'))
                                    <div class="error text-danger">
                                        {{ $errors->first('hsn') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Bank Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="bank_name" value="{{ $business->bank_name }}">
                                @if ($errors->has('bank_name'))
                                    <div class="error text-danger">
                                        {{ $errors->first('bank_name') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Account Number <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="account_number" value="{{ $business->account_number }}">
                                @if ($errors->has('account_number'))
                                    <div class="error text-danger">
                                        {{ $errors->first('account_number') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>IFSC Code <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="ifsc_code" value="{{ $business->ifsc_code }}">
                                @if ($errors->has('ifsc_code'))
                                    <div class="error text-danger">
                                        {{ $errors->first('ifsc_code') }}
                                    </div>
                                @endif
                            </div>
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
                           <input class="form-control" type="text"  name="owner_first_name" value="{{ $owner->first_name }}">
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
                           <input class="form-control" type="text" name="owner_last_name" value="{{ $owner->last_name }}">
                           @if ($errors->has('owner_last_name'))
                           <div class="error text-danger">
                               {{ $errors->first('owner_last_name') }}
                           </div>
                           @endif
                        </div>
                        </div>
                     </div>      
                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Email <span class="text-danger">*</span></label>
                           <input class="form-control" type="email" name="owner_email" value="{{ $owner->email }}">
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
                           <input class="form-control number_only" id="phone3" type="text" name="owner_phone_number" value="{{ $owner->phone }}">
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
                           <input class="form-control" type="text" name="owner_designation" value="{{ $owner->designation }}">
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
                           <input class="form-control number_only" type="text" name="owner_landline_number" value="{{ $owner->landline_number }}">
                           @if ($errors->has('owner_landline_number'))
                           <div class="error text-danger">
                               {{ $errors->first('owner_landline_number') }}
                           </div>
                           @endif
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
                           <input class="form-control" type="text"  name="dealing_first_name" value="{{ $dealing->first_name }}">
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
                           <input class="form-control" type="text" name="dealing_last_name" value="{{ $dealing->last_name }}">
                           @if ($errors->has('dealing_last_name'))
                           <div class="error text-danger">
                               {{ $errors->first('dealing_last_name') }}
                           </div>
                           @endif
                        </div>
                        </div>
                     </div>      
                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Email <span class="text-danger">*</span></label>
                           <input class="form-control" type="email" name="dealing_email" value="{{ $dealing->email }}">
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
                           <input class="form-control number_only" id="phone4" type="text" name="dealing_phone_number" value="{{ $dealing->phone }}">
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
                           <input class="form-control" type="text" name="dealing_designation" value="{{ $dealing->designation }}">
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
                           <input class="form-control number_only" type="text" name="dealing_landline_number" value="{{ $dealing->landline_number }}">
                           @if ($errors->has('dealing_landline_number'))
                           <div class="error text-danger">
                               {{ $errors->first('dealing_landline_number') }}
                           </div>
                           @endif
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
                           <input class="form-control" type="text"  name="account_first_name" value="@if($account !=""){{ $account->first_name }}@endif">
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
                           <input class="form-control" type="text" name="account_last_name" value="@if($account !=""){{ $account->last_name }}@endif">
                           @if ($errors->has('account_last_name'))
                           <div class="error text-danger">
                               {{ $errors->first('account_last_name') }}
                           </div>
                           @endif
                        </div>
                        </div>
                     </div>      
                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Email </label>
                           <input class="form-control" type="email" name="account_email" value="@if($account !=""){{ $account->email }}@endif">
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
                           <input class="form-control number_only" type="text" id="phone5" name="account_phone_number" value="@if($account !=""){{ $account->phone }}@endif">
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
                           <input class="form-control" type="text" name="account_designation" value="@if($account !=""){{ $account->designation }}@endif">
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
                           <input class="form-control number_only" type="text" name="account_landline_number" value="@if($account !=""){{ $account->landline_number }}@endif">
                           @if ($errors->has('account_landline_number'))
                           <div class="error text-danger">
                               {{ $errors->first('account_landline_number') }}
                           </div>
                           @endif
                        </div>
                        </div>
                     </div>
                  </div>
            </div>
            <!-- ./ -->

            <!-- Plan details -->
             <div class="row">
                <div class="col-md-8">
                  <h4 class="card-title mb-3 mt-3">Subscription package and Services details</h4> 
                    <p>  </p>           
                </div>
                
                    <div class="col-md-10">             

                        <div class="form-group">
                            <label for="company">Billing Mode <span class="text-danger">*</span></label>
                            <select name="billing_mode" class="form-control ">
                                <option value="">-Select-</option>
                                <option value="offline" @if( $business->billing_mode == 'offline') selected @endif > Offline </option>
                                <option value="online" @if( $business->billing_mode == 'online') selected @endif> Online </option>
                            </select>
                            @if ($errors->has('billing_mode'))
                            <div class="error text-danger">
                                {{ $errors->first('billing_mode') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="company">Service Type <span class="text-danger">*</span></label>
                            <select name="service_type" class="form-control ">
                                <option value="">-Select-</option>
                                <option value="offline" @if( $business->service_type == 'offline') selected @endif> Offline/Manual </option>
                                <option value="services" @if( $business->service_type == 'services') selected @endif> Services/SLA </option>
                            </select>
                            @if ($errors->has('service_type'))
                            <div class="error text-danger">
                                {{ $errors->first('service_type') }}
                            </div>
                            @endif
                        </div>
                        
                        <div class="form-group  ">
                            <label for="company">Subscription Package <span class="text-danger">*</span></label>
                            <select name="subscription_package" class="form-control">
                                <option value="">-Select-</option>
                                @if( count($plans) > 0 )
                                @foreach($plans as $item)
                                    <option value="{{ $item->id }}" @if( $customer_subscription->subscription_id == $item->id ) selected @endif >{{ $item->name.' - '.$item->currency.' '.$item->price.'/Month' }}</option>
                                @endforeach
                                @endif
                            </select>
                            @if ($errors->has('subscription_package'))
                            <div class="error text-danger">
                                {{ $errors->first('subscription_package') }}
                            </div>
                            @endif
                        </div>
                      
                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                    <button type="submit" class="btn btn-primary mt-2">Update</button>
                   
                </div>
                        
             </div>
             <!--  -->

             </form>
               </div>
            </div>
            
            </div><!-- Footer Start -->
            <div class="flex-grow-1"></div>
         
        </div>

<script>
$(function(){

    $(document).on('change','.customer_type',function(e) {
        e.preventDefault();
        var selVal = $('.customer_type option:selected').val();
        if(selVal =='with_subscription')
        {
            $('.subscription_list').removeClass('d-none');
            $('.subscription_list').addClass('d-block');
            $('.sla_list').addClass('d-none');
            $('.sla_list').removeClass('d-block');
        }

        if(selVal =='with_sla')
        {   $('.subscription_list').removeClass('d-block');
            $('.subscription_list').addClass('d-none');
            $('.sla_list').removeClass('d-none');
            $('.sla_list').addClass('d-block');
        }
        
    });

   $(document).on('submit', 'form#addContactForm', function (event) {
        event.preventDefault();
        //clearing the error msg
        $('p.error_container').html("");
        $("#coupon-error").html("");
        $("#otp_error").html("");   
        $('#guest-address-error').html("");

        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");

        $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,      
            success: function (response) {

                console.log(response);
                if(response.success==true  ) {          
                $("tbody.contactList").prepend("<tr><td scope='row'><input type='checkbox'></td><td><a href=''>"+response.data.name+"</a></td><td> "+response.data.email+"</td><td>"+response.data.phone+"</td><td> </td><td>"+response.data.associated_company+"</td></tr>");
                }
                //show the form validates error
                if(response.success==false ) {                              
                    for (control in response.errors) {   
                        $('#error-' + control).html(response.errors[control]);
                    }
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                // alert("Error: " + errorThrown);
            }
        });
        return false;
   });

   //on change country
   $(document).on('change','.country',function(){ 
      var id = $('#country_id').val();
      $.ajax({
            type:"post",
            url:"{{ url('/app/customers/getstate') }}", 
            data:{'country_id':id,"_token": "{{ csrf_token() }}"},
               success:function(data)
            {       
                  $("#state_id").empty();
                  $("#city_id").empty();
                  $("#state_id").html('<option>Select State</option>');
                  $.each(data,function(key,value){
                  $("#state_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                  });
            }
         });
   });

   // on change state
   $(document).on('change','.state',function(){ 
      var id = $('#state_id').val();
      $.ajax({
            type:"post",
            url:"{{ url('/app/customers/getcity')}}", 
            data:{'state_id':id,"_token": "{{ csrf_token() }}"},
            success:function(data)
            {       
                  $("#city_id").empty();
                  $("#city_id").html('<option>Select City</option>');
                  $.each(data,function(key,value){
                  $("#city_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                  }); 
            }

         });
   });
   
});


</script>


@endsection