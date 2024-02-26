@extends('layouts.vendor')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/vendor/home') }}">Dashboard</a>
             </li>
             <li>
                 <a href="{{ url('/vendor/profile') }}">Accounts</a>
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
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Account / Business </h3>
               </div>
            </div>
         </div>
      </div> --}}
      <div class="row">
         
               <div class="col-md-3 content-container">
                  <!-- left-sidebar -->
                  @include('vendor.accounts.sidebar') 
               </div>
               <!-- start right sec -->
                  <div class="col-md-9 content-wrapper bg-white">
                     <div class="formCover">
                        <!-- section -->
                        
                           <div class="col-sm-12 pt-2">
                             
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-7">
                                       <h4 class="card-title mb-1 mt-3">Business Information </h4>
                                       <p class="pb-border"> Your business infomation  </p>
                                    </div>
                                    <div class="col-md-5 float-right text-right">
                                       <?php
                                          $display_id =NULL;
                                          if($profile->display_id!=NULL)
                                          {
                                             $display_id = $profile->display_id;
                                          }
                                          else {
                                             $u_id = str_pad($profile->id, 10, "0", STR_PAD_LEFT);
                                             $display_id = trim(strtoupper(str_replace(array(' ','-'),'',substr(Helper::company_name($profile->business_id),0,4)))).'-'.$u_id;
                                          }
                                       ?>
                                       <p class="mb-1 mt-3"><strong> Company Name : </strong> {{Helper::company_name($profile->business_id)}}</p>
                                       <p><strong> Reference No : </strong>{{$display_id}}</p>
                                    </div>
                                    @if ($message = Session::get('success'))
                                       <div class="col-md-12">   
                                          <div class="alert alert-success">
                                          <strong>{{ $message }}</strong> 
                                          </div>
                                       </div>
                                    @endif
                                    @if(stripos(Auth::user()->user_type,'customer')!==false)
                                       <div class="col-md-12">
                                          <form class="mt-2" method="post" action="{{ url('/business_info/update') }}">
                                             @csrf
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
                                                      <input class="form-control number_only" id="phone1" type="text" name="business_phone_number" value="{{ $business->phone }}">
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
                                                {{-- <div class="col-sm-6">
                                                   <div class="form-group">
                                                      <label>Type of facility </label>
                                                      <input class="form-control " type="text" name="type_of_facility" value="{{ $business->type_of_facility }}">
                                                   </div>
                                                </div> --}}
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
                                             {{-- <div class="row">
                                                <div class="col-sm-6">
                                                   <div class="form-group">
                                                      <label>Work Order Date <span class="text-danger">*</span></label>
                                                      <input class="form-control commonDatepicker" type="text" name="work_order_date" value="{{ date('d-m-Y',strtotime($business->work_order_date)) }}">
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
                                                      <input class="form-control commonDatepicker" type="text" name="work_operating_date" value="{{ date('d-m-Y',strtotime($business->work_operating_date)) }}">
                                                      @if ($errors->has('work_operating_date'))
                                                      <div class="error text-danger">
                                                         {{ $errors->first('work_operating_date') }}
                                                      </div>
                                                      @endif
                                                   </div>
                                                </div>
                                             </div> --}}
                                             <div class="row">
                                                {{-- <div class="col-sm-6">
                                                   <div class="form-group">
                                                      <label>Billing Details </label>
                                                      <input class="form-control" type="text" name="billing_detail" value="{{ $business->billing_detail }}">
                                                      @if ($errors->has('billing_detail'))
                                                      <div class="error text-danger">
                                                         {{ $errors->first('billing_detail') }}
                                                      </div>
                                                      @endif
                                                   </div>
                                                </div> --}}
                                                {{-- <div class="col-sm-6">
                                                   <div class="form-group">
                                                       <label>HSN/SAC </label>
                                                       <input class="form-control" type="text" name="hsn" value="{{ $business->hsn_or_sac }}">
                                                       @if ($errors->has('hsn'))
                                                           <div class="error text-danger">
                                                               {{ $errors->first('hsn') }}
                                                           </div>
                                                       @endif
                                                   </div>
                                               </div> --}}
                                             </div>
                                             <div class="row">
                                                <div class="col-12 pt-2">
                                                   <h5>Address Information</h5>
                                                   <p class="pb-border"></p>
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
                                                <div class="col-sm-6">
                                                   <div class="form-group">
                                                      <label>Pin Code<span class="text-danger">*</span></label>
                                                      <input class="form-control number_only" type="text" name="pincode" value="{{  $business->zipcode  }}">
                                                      @if ($errors->has('pincode'))
                                                      <div class="error text-danger">
                                                         {{ $errors->first('pincode') }}
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
                                                            <option value="{{ $country->id }}" @if($country->id == $business->country_id) selected="" @endif >{{ $country->name }}</option>
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
                                                <div class="col-sm-6">
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
                                                <div class="col-12 pt-2">
                                                   <h5>Banking Information</h5>
                                                   <p class="pb-border"></p>
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
                                             <div class="text-center">
                                                <button type="submit" class="btn btn-md btn-info">Update</button>
                                             </div>
                                          </form>
                                       </div>
                                    @else
                                    <div class="col-md-12">
                                       <div class="form-group">
                                          <label for="company">Company or business name <span class="text-danger">*</span></label>
                                          <input type="text" name="company" class="form-control" id="company" placeholder="Company" value="{{ $business->company_name }}" disabled>
                                          @if ($errors->has('company'))
                                          <div class="error text-danger">
                                             {{ $errors->first('company') }}
                                          </div>
                                          @endif
                                       </div>
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Email <span class="text-danger">*</span></label>
                                                <input class="form-control" type="email" name="business_email" value="{{ $business->email }}" disabled>
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
                                                <input class="form-control number_only" id="phone1" type="text" name="business_phone_number" value="{{ $business->phone }}" disabled>
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
                                                <input class="form-control" type="text" name="website" value="{{ $business->website }}" disabled>
                                             </div>
                                          </div>
                                          {{-- <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Type of facility </label>
                                                <input class="form-control " type="text" name="type_of_facility" value="{{ $business->type_of_facility }}" disabled>
                                             </div>
                                          </div> --}}
                                       </div>
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>GST Number <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="gst_number" value="{{ $business->gst_number }}" disabled>
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
                                                <input class="form-control" type="text" name="tin_number" value="{{ $business->tin_number }}" disabled>
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
                                                <input class="form-control" type="text" name="contract_signed_by" value="{{ $business->contract_signed_by }}" disabled>
                                                <small class="text-muted">(Person name who signed the contract)</small>
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>HR name <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="hr_name" value="{{ $business->hr_name }}" disabled>
                                                @if ($errors->has('hr_name'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('hr_name') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                       </div>
                                       {{-- <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Work Order Date <span class="text-danger">*</span></label>
                                                <input class="form-control commonDatepicker" type="text" name="work_order_date" value="{{ $business->work_order_date }}" disabled>
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
                                                <input class="form-control commonDatepicker" type="text" name="work_operating_date" value="{{ $business->work_operating_date }}" disabled>
                                                @if ($errors->has('work_operating_date'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('work_operating_date') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                       </div> --}}
                                       <div class="row">
                                          {{-- <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Billing Details </label>
                                                <input class="form-control" type="text" name="billing_detail" value="{{ $business->billing_detail }}" disabled>
                                                @if ($errors->has('billing_detail'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('billing_detail') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div> --}}
                                          {{-- <div class="col-sm-6">
                                             <div class="form-group">
                                                 <label>HSN/SAC </label>
                                                 <input class="form-control" type="text" name="hsn" value="{{ $business->hsn_or_sac }}" disabled>
                                                 @if ($errors->has('hsn'))
                                                     <div class="error text-danger">
                                                         {{ $errors->first('hsn') }}
                                                     </div>
                                                 @endif
                                             </div>
                                         </div> --}}
                                       </div>
                                       <div class="row">
                                          <div class="col-12 pt-2">
                                             <h5>Address Information</h5>
                                             <p class="pb-border"></p>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Address (HO) <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="address" value="{{ $business->address_line1 }}" disabled>
                                                @if ($errors->has('address'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('address') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Pin Code<span class="text-danger">*</span></label>
                                                <input class="form-control number_only" type="text" name="pincode" value="{{  $business->zipcode  }}" disabled>
                                                @if ($errors->has('pincode'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('pincode') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Country <span class="text-danger">*</span></label>
                                                <select class="form-control" name="country" disabled>
                                                   <option value="">Select Country</option>
                                                   @foreach($countries as $country)
                                                   <option value="{{ $country->id }}" @if($country->id == $business->country_id) selected="" @endif >{{ $country->name }}</option>
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
                                                <input class="form-control " type="text" name="state" value="{{ $business->state_name }}" disabled>
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
                                                <input class="form-control " type="text" name="city" value="{{  $business->city_name  }}" disabled>
                                                @if ($errors->has('city'))
                                                <div class="error text-danger">
                                                   {{ $errors->first('city') }}
                                                </div>
                                                @endif
                                             </div>
                                          </div>
                                       </div>
                                       {{-- <div class="row"> --}}
                                          {{-- <div class="col-12">
                                             <h5>Banking Information</h5>
                                             <p class="pb-border"></p>
                                          </div> --}}
                                          {{-- <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Bank Name <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="bank_name" value="{{ $business->bank_name }}" disabled>
                                                @if ($errors->has('bank_name'))
                                                   <div class="error text-danger">
                                                         {{ $errors->first('bank_name') }}
                                                   </div>
                                                @endif
                                             </div>
                                          </div> --}}
                                          {{-- <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Account Number <span class="text-danger">*</span></label>
                                                   <input class="form-control" type="text" name="account_number" value="{{ $business->account_number }}" disabled>
                                                   @if ($errors->has('account_number'))
                                                      <div class="error text-danger">
                                                            {{ $errors->first('account_number') }}
                                                      </div>
                                                   @endif
                                                </div>
                                          </div> --}}
                                          {{-- <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>IFSC Code <span class="text-danger">*</span></label>
                                                   <input class="form-control" type="text" name="ifsc_code" value="{{ $business->ifsc_code }}" disabled>
                                                   @if ($errors->has('ifsc_code'))
                                                      <div class="error text-danger">
                                                            {{ $errors->first('ifsc_code') }}
                                                      </div>
                                                   @endif
                                                </div>
                                          </div> --}}
                                       {{-- </div> --}}
                                   
                                    </div>
                                    @endif
                                 <!-- ./business detail -->
                                 <!-- row -->
                              </div>
                           
                        <!--  -->
                        <!-- ./section -->
                     </div>
                  </div>
                  <!-- end right sec -->
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
   $(document).on('click','#clickSelectFile',function(){ 
   
       $('#fileupload').trigger('click');
       
   });
   
   $(document).on('click','.remove-image',function(){ 
       
       $('#fileupload').val("");
       $(this).parent('.image-area').detach();
   
   });
   
   $(document).on('change','#fileupload',function(e){ 
      // alert('test');
      //show process 
      // $("").html("Uploading...");
      $("#fileUploadProcess").html("<img src='{{asset('images/process-horizontal.gif')}}' >"); 
      
      var fd = new FormData();
      var inputFile = $('#fileupload')[0].files[0];
      fd.append('file',inputFile);
      fd.append('_token', '{{csrf_token()}}');
      //
      
      $.ajax({
               type: 'POST',
               url: "{{ url('/company/upload/logo') }}",
               data: fd,
               processData: false,
               contentType: false,
               success: function(data) {
                  console.log(data);
                  if (data.fail == false) {
                  
                  //reset data
                  $('#fileupload').val("");
                  $("#fileUploadProcess").html("");
                  //append result
                  $("#fileResult").html("<div class='image-area'><img src='"+data.filePrev+"'  alt='Preview'><a class='remove-image' href='javascript:;' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.file_id+"'></div>");
      
                  } else {
      
                  $("#fileUploadProcess").html("");
                  alert("please upload valida file! allowed file type , Image, PDF, Doc, Xls and txt ");
                  console.log("file error!");
                  
                  }
               },
               error: function(error) {
                  console.log(error);
                  // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
               }
      }); 
         return false;
      
   });
   
   $('.country').on('change',function()
   { 
     var id = $('#country_id').val();
      // alert(id);
      $.ajax({
            type:"post",
            url:"{{route('/customers/getstate')}}", 
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


   $('.state').on('change',function()
   { 
      var id = $('#state_id').val();

      $.ajax({
            type:"post",
            url:"{{route('/customers/getcity')}}", 
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
