@extends('layouts.admin')
@section('content')

<style>
   .image-area img{
      height:90px;
      width:85px;
   }
   .image-area{
      width: 150px;
      height: 135px;
      display: inline-block;
   }

   span.show-hide-password {
            position: absolute;
            top: 34px;
            right: 10px;
            font-size: 14px;
            color: #748a9c;
            cursor: pointer;
   }
   img#preview_img{
      width:100% !important;
   }

   .disabled-link{
      pointer-events: none;
    }

    .sweet-alert button.cancel {
        background: #DD6B55 !important;
    }
</style>
<div class="main-content-wrap sidenav-open d-flex flex-column">
      <!-- ============ Body content start ============= -->
      <div class="main-content">          
         <div class="row">
            <div class="col-sm-11">
                <ul class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li><a href="{{ url('/customers') }}">Customer</a></li>
                <li>Create New</li>
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

               {{-- @foreach ($errors->all() as $error)
               {{  $error }}
               @endforeach --}}
                
            <form class="mt-2" method="post" enctype="multipart/form-data" action="{{ url('/customers/store') }}" id="addCustomerFrm">
            @csrf
            <!-- section -->
            <div class="row">
               <div class="col-md-12">
                  <h3 style="font-size: 22px; border-bottom:1px solid #ddd; padding-bottom:6px;" class="card-title mb-3">Create a new customer </h3> 
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
                      <input class="form-control" type="text"  name="first_name" value="{{old('first_name')}}">
                          {{-- @if ($errors->has('first_name'))
                            <div class="error text-danger">
                                {{ $errors->first('first_name') }}
                            </div>
                          @endif --}}
                          <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-first_name"></p>
                    </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                       <label>Middle Name </label>
                       <input class="form-control" type="text"  name="middle_name" value="{{old('middle_name')}}">
                           {{-- @if ($errors->has('middle_name'))
                             <div class="error text-danger">
                                 {{ $errors->first('middle_name') }}
                             </div>
                           @endif --}}
                           <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-middle_name"></p>
                     </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                       <label>Last Name</label>
                       <input class="form-control" type="text" name="last_name" value="{{old('last_name')}}">
                       {{-- @if ($errors->has('last_name'))
                             <div class="error text-danger">
                                 {{ $errors->first('last_name') }}
                             </div>
                         @endif --}}
                         <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-last_name"></p>
                     </div>
                   </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Email <span class="text-danger">*</span></label>
                      <input class="form-control" type="email" name="email" value="{{old('email')}}">
                          {{-- @if ($errors->has('email'))
                            <div class="error text-danger">
                                {{ $errors->first('email') }}
                            </div>
                          @endif --}}
                          <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-email"></p>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                       <label>Phone Number <span class="text-danger">*</span></label>
                       <input type="hidden" id="code" name ="primary_phone_code" value="91" >
                       <input type="hidden" id="iso" name ="primary_phone_iso" value="in" >
                       <input class="form-control number_only" id="phone1" type="text" name="phone" value="{{old('phone')}}">
                            {{-- @if ($errors->has('phone'))
                             <div class="error text-danger">
                                 {{ $errors->first('phone') }}
                             </div>
                             @endif --}}
                             <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-phone"></p>
                     </div>
                   </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Password </label>
                      <input class="form-control " type="password" name="password" value="{{old('password')}}">
                      <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                      <small class="text-muted">(If left blank system will send auto-generated password.)</small>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    
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
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label for="company">Company or business name <span class="text-danger">*</span></label>
                              <input type="text" name="company" class="form-control" id="company" placeholder="Company" value="{{ old('company') }}">
                              {{-- @if ($errors->has('company'))
                              <div class="error text-danger">
                                 {{ $errors->first('company') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-company"></p>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label for="company">Company Short Name <span class="text-danger">*</span> <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Note:- Company short name should not be more than 30 character"></i> </label>
                              <input type="text" name="company_short_name" class="form-control" id="company_short_name" placeholder="Company Short Name" value="{{ old('company_short_name') }}">
                              {{-- @if ($errors->has('company'))
                              <div class="error text-danger">
                                 {{ $errors->first('company') }}
                              </div>
                              @endif --}}
                              
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-company_short_name"></p>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>Country <span class="text-danger">*</span></label>
                              <select class="form-control country" name="country_id" id="country_id">
                              <option value="">Select Country</option>
                              @foreach($countries as $country)
                                 <option value="{{ $country->id }}" @if($country->id == 101) selected="" @endif >{{ $country->name }}</option>
                              @endforeach
                              </select>
                              {{-- @if ($errors->has('country_id'))
                              <div class="error text-danger">
                                 {{ $errors->first('country_id') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-country_id"></p>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>State <span class="text-danger">*</span></label>
                              <select class="form-control state" name="state_id" id="state_id">
                              <option value="">Select State</option>
                              @foreach($state as $states)
                                 <option value="{{ $states->id }}">{{ $states->name }}</option>
                              @endforeach
                              </select>
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-state_id"></p>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>City/Town/District <span class="text-danger">*</span></label>
                              <select class="form-control" name="city_id" id="city_id">
                              </select>
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-city_id"></p>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>Pin Code<span class="text-danger">*</span></label>
                              <input class="form-control number_only" type="text" name="pincode" value="{{old('pincode')}}">
                              {{-- @if ($errors->has('pincode'))
                              <div class="error text-danger">
                                 {{ $errors->first('pincode') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-pincode"></p>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                             <label>Address (HO) <span class="text-danger">*</span></label>
                             <input class="form-control" type="text" name="address" value="{{old('address')}}">
                              {{-- @if ($errors->has('address'))
                              <div class="error text-danger">
                                 {{ $errors->first('address') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-address"></p>
                           </div>
                        </div>
                      
                     </div>
                     <div class="row">
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>Email <span class="text-danger">*</span></label>
                              <input class="form-control" type="email" name="business_email" value="{{old('business_email')}}">
                              {{-- @if ($errors->has('business_email'))
                              <div class="error text-danger">
                                 {{ $errors->first('business_email') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-business_email"></p>
                           </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Phone Number <span class="text-danger">*</span></label>
                           <input type="hidden" id="code2" name ="primary_phone_code2" value="91" >
                           <input type="hidden" id="iso2" name ="primary_phone_iso2" value="in" >
                           <input class="form-control number_only" id="phone2" type="text" name="business_phone_number" value="{{old('business_phone_number')}}">
                           {{-- @if ($errors->has('business_phone_number'))
                            <div class="error text-danger">
                                {{ $errors->first('business_phone_number') }}
                            </div>
                            @endif --}}
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-business_phone_number"></p>
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
                              <label>TIN Number </label>
                              <input class="form-control" type="text" name="tin_number" value="{{old('tin_number')}}">
                              {{-- @if ($errors->has('tin_number'))
                              <div class="error text-danger">
                                 {{ $errors->first('tin_number') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-tin_number"></p>
                           </div>
                        </div>
                        {{-- <div class="col-sm-6">
                           <div class="form-group">
                              <label>Company Revenue <span class="text-danger">*</span></label>
                              <select class="form-control revenue" name="revenue" id="revenue">
                                 <option value="">Select Revenue</option>
                                 <option value="A" >More than 1 Cr.</option>
                                 <option value="B">50 lakhs to 1 Cr.</option>
                                 <option value="C">Less than 50 lakhs</option>
                              </select>
                             
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-revenue"></p>
                           </div>
                        </div> --}}
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>Department </label>
                              <input class="form-control" type="text" name="department">
                              {{-- @if ($errors->has('department'))
                              <div class="error text-danger">
                                 {{ $errors->first('department') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-department"></p>
                           </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Contract Signed By <span class="text-danger">*</span></label>
                           <input class="form-control" type="text" name="contract_signed_by" value="{{old('contract_signed_by')}}">
                           {{-- @if ($errors->has('contract_signed_by'))
                            <div class="error text-danger">
                                {{ $errors->first('contract_signed_by') }}
                            </div>
                            @endif --}}
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-contract_signed_by"></p>
                           <small class="text-muted">(Person name who signed the contract)</small>
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>HR Name <span class="text-danger">*</span></label>
                           <input class="form-control" type="text" name="hr_name" value="{{old('hr_name')}}">
                           {{-- @if ($errors->has('hr_name'))
                            <div class="error text-danger">
                                {{ $errors->first('hr_name') }}
                            </div>
                            @endif --}}
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-hr_name"></p>
                        </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Contract Start Date <span class="text-danger">*</span></label>
                           <input class="form-control commonDatepicker contract_start_date" type="text" name="contract_start_date" value="{{old('contract_start_date')}}" autocomplete="off">
                           {{-- @if ($errors->has('contract_start_date'))
                            <div class="error text-danger">
                                {{ $errors->first('contract_start_date') }}
                            </div>
                            @endif --}}
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-contract_start_date"></p>
                        </div>
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Contract End Date <span class="text-danger">*</span></label>
                           <input class="form-control commonDatepicker contract_end_date" type="text" name="contract_end_date" value="{{old('contract_end_date')}}" autocomplete="off">
                           {{-- @if ($errors->has('contract_end_date'))
                            <div class="error text-danger">
                                {{ $errors->first('contract_end_date') }}
                            </div>
                            @endif --}}
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-contract_end_date"></p>
                        </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Billing Details </label>
                           <input class="form-control" type="text" name="billing_detail" value="{{old('billing_detail')}}">
                          
                        </div>
                        </div>
                         <div class="col-sm-6">
                        <div class="form-group">
                           <label>Pan Number <span class="text-danger">*</span></label>
                           <input class="form-control" type="text" name="pan_number" value="{{old('pan_number')}}" placeholder="Ex:- DPAGA4875J">
                           {{-- @if ($errors->has('pan_number'))
                            <div class="error text-danger">
                                {{ $errors->first('pan_number') }}
                            </div>
                            @endif --}}
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-pan_number"></p>
                        </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-sm-6">
                        <div class="form-group">
                           <label>Company Logo   <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,gif,svg "></i> <small>   </small></label>
                           <input class="form-control" type="file" name="company_logo" id="company_logo" accept=".jpeg,.png,.jpg,.gif,.svg">
                           {{-- @if ($errors->has('company_logo'))
                            <div class="error text-danger">
                                {{ $errors->first('company_logo') }}
                            </div>
                            @endif --}}
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-company_logo"></p>
                        </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                           <label for="company_logo"></label>
                           <span class="d-none btn btn-link float-right text-dark close_btn">X</span>
                           <img id="preview_img"   width="200" height="150"/>
                           </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-sm-4">
                        <div class="form-group">
                           <label>Files (Contract files etc.)</label>
                           <a class='btn-link clickSelectFile' add-id='1' style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a>
                           <input type='file' class='fileupload' name='file' style='display:none'/>
                           
                        </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-sm-12">
                           <div class="form-group fileResult" id='fileResult-1'>
                           </div>
                        </div>
                     </div>
                     
                     <div class="row mt-2 mb-3">
                        <div class="col-sm-12">
                           <div class="form-check form-check-inline error-control">
                              <input class="form-check-input gst_exempt" type="checkbox" name="gst_exempt" id="gst_exempt">
                              <label class="form-check-label" for="gst_exempt">GST Exempt</label>
                           </div>
                        </div>
                     </div>  
                     <div class="row">
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>GST Number <span class="text-danger">*</span></label>
                              <input class="form-control" type="text" name="gst_number" value="{{old('gst_number')}}" placeholder="Ex:- 22AAAAA4444A1Z5">
                              {{-- @if ($errors->has('gst_number'))
                               <div class="error text-danger">
                                   {{ $errors->first('gst_number') }}
                               </div>
                               @endif --}}
                               <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-gst_number"></p>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>GST Attachment <span class="text-danger">*</span> <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,gif,svg,pdf"></i> <small>   </small></label>
                              <div class="custom-file error-control">
                                 <input type="file" name="gst_attachment" class="custom-file-input gst_attachment" id="gst_attachment" data-pdf="{{url('/').'/admin/images/icon_pdf.png'}}" accept="image/*,.pdf">
                                 <label class="custom-file-label" id="gst_label" for="gst_attachment">Choose File...</label>
                              </div>
                              {{-- @if ($errors->has('gst_attachment'))
                                 <div class="error text-danger">
                                    {{ $errors->first('gst_attachment') }}
                                 </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-gst_attachment"></p>
                           </div>
                        </div>
                        <div class="col-sm-6 w-100">
                           <div class="form-group">
                              <label for="gst_attachment"></label>
                              <span class="d-none btn btn-link float-right text-dark close_gst_btn">X</span>
                              <img id="preview_gst_img" width="200" height="150"/>
                           </div>
                        </div>
                     </div>

                     <a href="javascript:;" class="add_spoke"><i class="fa fa-plus mb-3"></i> Add Spokeman</a>
                     <span class="addSpokeDiv"></span><br>
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
                              {{-- @if ($errors->has('owner_first_name'))
                              <div class="error text-danger">
                                 {{ $errors->first('owner_first_name') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-owner_first_name"></p>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                             <label>Middle Name </label>
                             <input class="form-control" type="text"  name="owner_middle_name" value="{{old('owner_middle_name')}}">
                                 {{-- @if ($errors->has('middle_name'))
                                   <div class="error text-danger">
                                       {{ $errors->first('middle_name') }}
                                   </div>
                                 @endif --}}
                                 <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-owner_middle_name"></p>
                           </div>
                        </div>
                     </div>      
                     <div class="row">
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>Last Name </label>
                              <input class="form-control" type="text" name="owner_last_name" value="{{old('owner_last_name')}}">
                              {{-- @if ($errors->has('owner_last_name'))
                              <div class="error text-danger">
                                 {{ $errors->first('owner_last_name') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-owner_last_name"></p>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>Email <span class="text-danger">*</span></label>
                              <input class="form-control" type="email" name="owner_email" value="{{old('owner_email')}}">
                              {{-- @if ($errors->has('owner_email'))
                              <div class="error text-danger">
                                 {{ $errors->first('owner_email') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-owner_email"></p>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>Phone Number <span class="text-danger">*</span></label>
                              <input type="hidden" id="code3" name ="primary_phone_code3" value="91" >
                              <input type="hidden" id="iso3" name ="primary_phone_iso3" value="in" >
                              <input class="form-control number_only" id="phone3" type="text" name="owner_phone_number" value="{{old('owner_phone_number')}}">
                              {{-- @if ($errors->has('owner_phone_number'))
                              <div class="error text-danger">
                                 {{ $errors->first('owner_phone_number') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-owner_phone_number"></p>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>Designation <span class="text-danger">*</span></label>
                              <input class="form-control" type="text" name="owner_designation" value="{{old('owner_designation')}}">
                              {{-- @if ($errors->has('owner_designation'))
                              <div class="error text-danger">
                                 {{ $errors->first('owner_designation') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-owner_designation"></p>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>Landline Number </label>
                              <input class="form-control number_only owner_landline_number" type="text" maxlength="10" name="owner_landline_number" value="{{old('owner_landline_number')}}">
                              {{-- @if ($errors->has('owner_landline_number'))
                                    <div class="error text-danger">
                                       {{ $errors->first('owner_landline_number') }}
                                    </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-owner_landline_number"></p>
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
                              <label>First Name </label>
                              <input class="form-control" type="text"  name="dealing_first_name" value="{{old('dealing_first_name')}}">
                              {{-- @if ($errors->has('dealing_first_name'))
                              <div class="error text-danger">
                                 {{ $errors->first('dealing_first_name') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dealing_first_name"></p>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                             <label>Middle Name </label>
                             <input class="form-control" type="text"  name="dealing_middle_name" value="{{old('dealing_middle_name')}}">
                                 {{-- @if ($errors->has('middle_name'))
                                   <div class="error text-danger">
                                       {{ $errors->first('middle_name') }}
                                   </div>
                                 @endif --}}
                                 <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dealing_middle_name"></p>
                           </div>
                        </div>
                     </div>      
                     <div class="row">
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>Last Name </label>
                              <input class="form-control" type="text" name="dealing_last_name" value="{{old('dealing_last_name')}}">
                              {{-- @if ($errors->has('dealing_last_name'))
                              <div class="error text-danger">
                                 {{ $errors->first('dealing_last_name') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dealing_last_name"></p>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>Email </label>
                              <input class="form-control" type="email" name="dealing_email" value="{{old('dealing_email')}}">
                              {{-- @if ($errors->has('dealing_email'))
                              <div class="error text-danger">
                                 {{ $errors->first('dealing_email') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dealing_email"></p>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>Phone Number </label>
                              <input type="hidden" id="code4" name ="primary_phone_code4" value="91" >
                              <input type="hidden" id="iso4" name ="primary_phone_iso4" value="in" >
                              <input class="form-control number_only" type="text" id="phone4" name="dealing_phone_number" value="{{old('dealing_phone_number')}}">
                              {{-- @if ($errors->has('dealing_phone_number'))
                              <div class="error text-danger">
                                 {{ $errors->first('dealing_phone_number') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dealing_phone_number"></p>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>Designation </label>
                              <input class="form-control" type="text" name="dealing_designation" value="{{old('dealing_designation')}}">
                              {{-- @if ($errors->has('dealing_designation'))
                              <div class="error text-danger">
                                 {{ $errors->first('dealing_designation') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dealing_designation"></p>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>Landline Number </label>
                              <input class="form-control number_only" type="text" maxlength="10" name="dealing_landline_number" value="{{old('dealing_landline_number')}}">
                              {{-- @if ($errors->has('dealing_landline_number'))
                                    <div class="error text-danger">
                                       {{ $errors->first('dealing_landline_number') }}
                                    </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dealing_landline_number"></p>
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
                              <label>First Name</label>
                              <input class="form-control" type="text"  name="account_first_name" value="{{old('account_first_name')}}">
                              {{-- @if ($errors->has('account_first_name'))
                              <div class="error text-danger">
                                 {{ $errors->first('account_first_name') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-account_first_name"></p>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                             <label>Middle Name </label>
                             <input class="form-control" type="text"  name="account_middle_name" value="{{old('account_middle_name')}}">
                                 {{-- @if ($errors->has('middle_name'))
                                   <div class="error text-danger">
                                       {{ $errors->first('middle_name') }}
                                   </div>
                                 @endif --}}
                                 <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-account_middle_name"></p>
                           </div>
                        </div>
                     </div>      
                     <div class="row">
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>Last Name </label>
                              <input class="form-control" type="text" name="account_last_name" value="{{old('account_last_name')}}">
                              {{-- @if ($errors->has('account_last_name'))
                              <div class="error text-danger">
                                 {{ $errors->first('account_last_name') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-account_last_name"></p>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>Email </label>
                              <input class="form-control" type="email" name="account_email" value="{{old('account_email')}}">
                              {{-- @if ($errors->has('account_email'))
                              <div class="error text-danger">
                                 {{ $errors->first('account_email') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-account_email"></p>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>Phone Number</label>
                              <input type="hidden" id="code5" name ="primary_phone_code5" value="91" >
                              <input type="hidden" id="iso5" name ="primary_phone_iso5" value="in" >
                              <input class="form-control number_only" type="text" id="phone5" name="account_phone_number" value="{{old('account_phone_number')}}">
                              {{-- @if ($errors->has('account_phone_number'))
                              <div class="error text-danger">
                                 {{ $errors->first('account_phone_number') }}
                              </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-account_phone_number"></p>
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>Designation</label>
                              <input class="form-control" type="text" name="account_designation" value="{{old('account_designation')}}">
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                              <label>Landline Number </label>
                              <input class="form-control number_only account_landline_number" type="text" maxlength="10" name="account_landline_number" value="{{old('account_landline_number')}}">
                              {{-- @if ($errors->has('account_landline_number'))
                                    <div class="error text-danger">
                                       {{ $errors->first('account_landline_number') }}
                                    </div>
                              @endif --}}
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-account_landline_number"></p>
                           </div>
                        </div>
                     </div>
                     <span class="addDiv"></span>

                     <a href="javascript:;" class="add">Add <i class="fa fa-plus"></i></a><br>
                     
                      <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-12">
                           <div class="form-group">
                              <strong>Select Primary CAM:</strong> <span class="text-danger">*</span>
                           
                              <div class="col-sm-12 col-md-12 col-12">
                                 <div class="form-group">
                                    <select class="select-option-field-7 user selectValue form-control" name="user" data-type="user" data-t="{{ csrf_token() }}">
                                          <option value="">Select CAM</option>
                                          @foreach ($users as $user)
                                             <option value="{{$user->id}}">{{$user->name}}</option>
                                          @endforeach
                                    </select>
                                    {{-- @if ($errors->has('user'))
                                       <div class="error text-danger">
                                          {{ $errors->first('user') }}
                                       </div>
                                    @endif --}}
                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-user"></p>
                                 </div>
                           </div>
                           {{-- <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-user"></p> --}}
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-12">
                           <div class="form-group">
                              <strong>Select Secondary CAM:</strong> <span class="text-danger">*</span>
                           
                              <div class="col-sm-12 col-md-12 col-12">
                                 <div class="form-group">
                                    <select class="select-option-field-7 secondary selectValue form-control" name="secondary" data-type="secondary" data-t="{{ csrf_token() }}">
                                          <option value="">Select CAM</option>
                                          @foreach ($users as $user)
                                             <option value="{{$user->id}}">{{$user->name}}</option>
                                          @endforeach
                                    </select>
                                    {{-- @if ($errors->has('secondary'))
                                       <div class="error text-danger">
                                          {{ $errors->first('secondary') }}
                                       </div>
                                    @endif --}}
                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-secondary"></p>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>

                     <span class="addCameDiv"></span><br>
                     <a href="javascript:;" class="add_Cam">Add <i class="fa fa-plus"></i></a>

                  
                     {{-- <div class="row">
                     <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                           <strong>Select KAM:</strong> <span class="text-danger">*</span>
                        
                              <div class="col-sm-12">
                                    <div class="form-group">
                                          @foreach($users as $user)
                                          <div class="form-check form-check-inline">
                                             <input class="form-check-input " type="checkbox" name="kams[]" value="{{$user->id}}" id="inlineCheckbox-{{ $user->id}}" >
                                             <label class="form-check-label" for="inlineCheckbox-{{ $user->id}}">{{ $user->name  }}</label>
                                          </div>
                                          @endforeach
                                    </div>
                              </div>
                              <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>
                        </div>
                     </div>
                  </div> --}}
                     <p style='margin-bottom: 2px;' class='text-danger error_container error-all' id="error-all"></p>
                     <button type="submit"  class="btn btn-info mt-2 Submit">Save</button>
                     
                  </div>
            </div>
               
               <!-- ./ -->

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

   $(document).on('change','.contract_start_date',function() {

      var from = $('.contract_start_date').datepicker('getDate');
      var to_date   = $('.contract_end_date').datepicker('getDate');

      if($('.contract_end_date').val() !=""){
         if (from > to_date) {
            alert ("Please select appropriate date range!");
            $('.contract_start_date').val("");
            $('.contract_end_date').val("");
         }

      }

      // alert(from);
      // alert(to_date);

      // alert("heello");
     
   });

   $(document).on('change','.contract_end_date',function() {

      var to_date = $('.contract_end_date').datepicker('getDate');
      var from   = $('.contract_start_date').datepicker('getDate');
      if($('.contract_start_date').val() !=""){
         if (from > to_date) {
         alert ("Please select appropriate date range!");
         $('.contract_start_date').val("");
         $('.contract_end_date').val("");
         
         }
      }


   });

   $(document).on('change','.gst_exempt',function(){

      //alert('hi');

      if(this.checked)
      {
         $("input[name='gst_number']").attr('readonly',true);
      }
      else
      {
         $("input[name='gst_number']").attr('readonly',false);
      }

   });


});
//

</script>
<script>

   $(document).ready(function(){
      $(document).on('submit', 'form#addCustomerFrm', function (event) {
        event.preventDefault();
        //clearing the error msg
        $('p.error_container').html("");

        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
        $('.submit').attr('disabled',true);
        $('.form-control').attr('readonly',true);
        $('.form-control').addClass('disabled-link');
        $('.error-control').addClass('disabled-link');
        if ($('.submit').html() !== loadingText) {
              $('.submit').html(loadingText);
        }
            $.ajax({
                type: form.attr('method'),
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,      
                success: function (response) {
                    window.setTimeout(function(){
                        $('.submit').attr('disabled',false);
                        $('.form-control').attr('readonly',false);
                        $('.form-control').removeClass('disabled-link');
                        $('.error-control').removeClass('disabled-link');
                        $('.submit').html('Save');
                      },2000);
                    console.log(response);
                    if(response.success==true) {          
                    
                        //notify
                        toastr.success("Customer Created Successfully");
                        // redirect to google after 5 seconds
                        window.setTimeout(function() {
                            window.location = "{{ url('/')}}"+"/customers/";
                        }, 2000);
                    
                    }
                    //show the form validates error
                    if(response.success==false ) {                              
                        for (control in response.errors) {  
                           var error_text = control.replace('.',"_");
                           $('#error-'+error_text).html(response.errors[control]);
                           // $('#error-'+error_text).html(response.errors[error_text][0]);
                           // console.log('#error-'+error_text);
                        }
                        // console.log(response.errors);
                    }
                },
                error: function (response) {
                    // alert("Error: " + errorThrown);
                    console.log(response);
                }
            });
            event.stopImmediatePropagation();
            return false;
      });
   });

   var count=0;
   $(document).on('click','.add',function(){ 
      $(".addDiv").append(
         `<div class='projectReport' row-id='1' style='padding: 20px;margin-top:15px; border:1px solid #ddd; background:#fff;'>
         <span class="btn btn-link float-right text-danger close_div" style="font-size:20px;"><i class="far fa-times-circle"></i></span>
         <h3 style='padding: 10px;background:#eee;'>Add a new contact </h3>
         <div class='row'>
         <div class='col-sm-6'>
         <div class='form-group'>
         <label style='font-size: 16px;'> Contact Type <span class="text-danger">*</span></label>
         <input class='form-control' type='text' name='type[]' value=''>
         <small class='text-muted'>Add you contact title (Example: Manager)</small></div>
         <p style='margin-bottom: 2px;' class='text-danger error_container error-type' id="error-type"></p>
         </div>
         </div>
         <div class='row'>
         <div class='col-sm-6'>
         <div class='form-group'>
         <label>First name <span class="text-danger">*</span></label>
         <input class='form-control' type='text' name='add_first_name[]' >
         <p style='margin-bottom: 2px;' class='text-danger error_container error-add_first_name' id="error-add_first_name"></p>
         </div>
         </div>
         <div class='col-sm-6'>
         <div class='form-group'>
         <label>Middle name</label>
         <input class='form-control' type='text'  name='add_middle_name[]' >
         <p style='margin-bottom: 2px;' class='text-danger error_container error-add_middle_name' id="error-add_middle_name"></p>
         </div>
         </div>
         </div>
         <div class='row'>
         <div class='col-sm-6'>
         <div class='form-group'>
         <label>Last name </label>
         <input class='form-control' type='text'  name='add_last_name[]'>
         <p style='margin-bottom: 2px;' class='text-danger error_container error-add_last_name' id="error-add_last_name"></p>
         </div>
         </div>
         <div class='col-sm-6'>
         <div class='form-group'>
         <label>Email </label>
         <input class='form-control' type='text'  name='add_email[]' >
         <p style='margin-bottom: 2px;' class='text-danger error_container error-add_email' id="error-add_email"></p>
         </div>
         </div>
         </div>
         <div class='row'>
         <div class='col-sm-6'>
         <div class='form-group'>
         <label>Phone Number </label>
         <input class='form-control number_only' maxlength='10' type='text' name='add_phone[]' >
         <p style='margin-bottom: 2px;' class='text-danger error_container error-add_phone' id="error-add_phone"></p>
         </div>
         </div>
         <div class='col-sm-6'>
         <div class='form-group'>
         <label>Designation </label>
         <input class='form-control' type='text' name='add_designation[]' >
         <p style='margin-bottom: 2px;' class='text-danger error_container error-add_designation' id="error-add_designation"></p>
         </div>
         </div>
         <div class='col-sm-6'>
         <div class='form-group'>
         <label> Landline number </label>
         <input class='form-control number_only' maxlength='10' type='text' name='add_landline_number[]' >
         <p style='margin-bottom: 2px;' class='text-danger error_container error-add_landline_number' id="error-add_landline_number"></p>
         </div>
         </div>
         </div>
         <div class='row'>
         <div class='col-sm-6'>
         </div>
         </div>
         </div>`
         );

         var i=0;
         $('.error-type').each(function(){
            $(this).attr('id','error-type_'+i);
            i++;
         });
         var i=0;
         $('.error-add_first_name').each(function(){
            $(this).attr('id','error-add_first_name_'+i);
            i++;
         });
         var i=0;
         $('.error-add_middle_name').each(function(){
            $(this).attr('id','error-add_middle_name_'+i);
            i++;
         });
         var i=0;
         $('.error-add_last_name').each(function(){
            $(this).attr('id','error-add_last_name_'+i);
            i++;
         });
         var i=0;
         $('.error-add_email').each(function(){
            $(this).attr('id','error-add_email_'+i);
            i++;
         });
         var i=0;
         $('.error-add_phone').each(function(){
            $(this).attr('id','error-add_phone_'+i);
            i++;
         });
         var i=0;
         $('.error-add_designation').each(function(){
            $(this).attr('id','error-add_designation_'+i);
            i++;
         });
         var i=0;
         $('.error-add_landline_number').each(function(){
            $(this).attr('id','error-add_landline_number_'+i);
            i++;
         });
   });


   $(document).on('click','.close_div',function(){
      var _this=$(this);
      _this.parent().fadeOut("slow", function(){ 
         _this.parent().remove();
            var i=0;
            $('.error-type').each(function(){
               $(this).attr('id','error-type_'+i);
               i++;
            });

            var i=0;
            $('.error-add_first_name').each(function(){
               $(this).attr('id','error-add_first_name_'+i);
               i++;
            });

            var i=0;
            $('.error-add_middle_name').each(function(){
               $(this).attr('id','error-add_middle_name_'+i);
               i++;
            });

            var i=0;
            $('.error-add_last_name').each(function(){
               $(this).attr('id','error-add_last_name_'+i);
               i++;
            });

            var i=0;
            $('.error-add_email').each(function(){
               $(this).attr('id','error-add_email_'+i);
               i++;
            });

            var i=0;
            $('.error-add_phone').each(function(){
               $(this).attr('id','error-add_phone_'+i);
               i++;
            });


            var i=0;
            $('.error-add_designation').each(function(){
               $(this).attr('id','error-add_designation_'+i);
               i++;
            });

            var i=0;
            $('.error-add_landline_number').each(function(){
               $(this).attr('id','error-add_landline_number_'+i);
               i++;
            });
      });
      
         
         
   });

   $(document).on('click','.add_spoke',function(){ 
      var s_len = $('.spokeReport').length;
      if(s_len + 1 > 5)
      {

         swal({
                  title: "You Can Include Maximum 5 Spokeman !!",
                  text: '',
                  type: 'warning',
                  buttons: true,
                  dangerMode: true,
                  confirmButtonColor:'#003473'
               });
      }
      else
      {
         $(".addSpokeDiv").append(
         `<div class='spokeReport' row-id='1'>
            <div class='form-group'>
            <div class="row">
            <div class="col-md-6">
            <label style='font-size: 16px;'> Name <span class="text-danger">*</span></label>
            <input class='form-control' type='text' name='spoke_name[]' value=''>
            <p style='margin-bottom: 2px;' class='text-danger error_container error-spoke_name' id="error-spoke_name"></p>
            </div>
            <div class="col-md-6 mt-4">
               <span class="btn btn-link text-danger close_spoke_div" style="font-size:20px;"><i class="far fa-times-circle"></i></span>
            </div>
            </div>
            </div>
         </div>`
         );
      }
         var i=0;
         $('.error-spoke_name').each(function(){
            $(this).attr('id','error-spoke_name_'+i);
            i++;
         });
        
   });

   $(document).on('click','.close_spoke_div',function(){
      var _this=$(this);
      
      _this.parent().parent().parent().parent().fadeOut("slow", function(){ 
         _this.parent().parent().parent().parent().remove();
            var i=0;
            $('.error-spoke_name').each(function(){
               $(this).attr('id','error-spoke_name_'+i);
               i++;
            });
      });  
   });

   //add multiple came
   var count=0;
   $(document).on('click','.add_Cam',function(){ 
         $(".addCameDiv").append(
         `<div class='camReport' row-id='1'>
            <div class='form-group'>
            <div class="row">
            <div class="col-md-11">
            <label style='font-size: 14px;'> <strong>Select CAM:</strong> <span class="text-danger">*</span></label>
            <select class="select-option-field-7 cam selectValue form-control" name="cam[]" data-type="cam">
                  <option value="">Select CAM</option>
                  @foreach ($users as $user)
                     <option value="{{$user->id}}">{{$user->name}}</option>
                  @endforeach
            </select>
            <p style='margin-bottom: 2px;' class='text-danger error_container error-cam' id="error-cam"></p>
            </div>
            <div class="col-md-1 mt-4">
               <span class="btn btn-link text-danger close_cam_div" style="font-size:20px;"><i class="far fa-times-circle"></i></span>
            </div>
            </div>
            </div>
         </div>`
         );
      
         var i=0;
         $('.error-cam').each(function(){
            $(this).attr('id','error-cam_'+i);
            i++;
         });
        
   });

   $(document).on('click','.close_cam_div',function(){
      var _this=$(this);    
         swal({
               // icon: "warning",
               type: "warning",
               title: "Are You Want to Remove This File?",
               text: "",
               dangerMode: true,
               showCancelButton: true,
               confirmButtonColor: "#007358",
               confirmButtonText: "YES",
               cancelButtonText: "CANCEL",
               closeOnConfirm: false,
               closeOnCancel: false
               },
               function(e){
                  if(e==true)
                  {
                     _this.parent().parent().parent().parent().fadeOut("slow", function(){ 
                        _this.parent().parent().parent().parent().remove();
                           var i=0;
                           $('.error-cam').each(function(){
                              $(this).attr('id','error-cam_'+i);
                              i++;
                           });
                     });  
                    
                     swal.close();
                  }
                  else
                  {
                     swal.close();
                  }
            });
       
   });

   //on change country
   $(document).on('change','.country',function(){ 
      var id = $('#country_id').val();
      $.ajax({
            type:"post",
            url:"{{route('/customers/getstate')}}", 
            data:{'country_id':id,"_token": "{{ csrf_token() }}"},
               success:function(data)
            {       
                  $("#state_id").empty();
                  $("#state_id").html('<option value="">Select State</option>');
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
            url:"{{route('/customers/getcity')}}", 
            data:{'state_id':id,"_token": "{{ csrf_token() }}"},
            success:function(data)
            {       
                  $("#city_id").empty();
                  $("#city_id").html('<option value="">Select City</option>');
                  $.each(data,function(key,value){
                  $("#city_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                  }); 
            }

         });
   });

   //on change 
   $(document).on('change','#company_logo',function(){
     
      let reader = new FileReader();
      reader.onload = (e) => { 
         $('#preview_img').attr('src', e.target.result); 
         $('.close_btn').removeClass('d-none');
      }
      reader.readAsDataURL(this.files[0]); 
 
   });

   $(document).on('click','.close_btn',function(){
      $('#preview_img').removeAttr('src'); 
      $(this).addClass('d-none');
      $(this).parents().eq(2).find('#company_logo').val("");
   });

   //on change 
   $('#gst_attachment').change(function(){
         var fileTypes = ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg', 'pdf'];
         var pdf_url=$(this).attr('data-pdf');
         var file = this.files[0].name;
         var extension = file.split('.').pop().toLowerCase();
         // console.log(pdf_url);
         // console.log(file);
         // console.log(extension);
         isSuccess = fileTypes.indexOf(extension) > -1;
          if(isSuccess)
          {
            $('#preview_gst_img').attr('src','');
            let reader = new FileReader();
            if(extension=="pdf")
            {
               $('#preview_gst_img').attr('src', pdf_url); 
               $('.close_gst_btn').removeClass('d-none');
            }
            else
            {
               reader.onload = (e) => { 
                  $('.close_gst_btn').removeClass('d-none');
                  $('#preview_gst_img').attr('src', e.target.result); 
               }
               reader.readAsDataURL(this.files[0]);
            }

            $('#gst_label').html(file);
          }
          else
          {
             alert('Select Only jpg, jpeg, png, bmp, gif, svg, pdf file');
             $(this).val("");
             $('#gst_label').html('Choose File...');
             $('#preview_gst_img').removeAttr('src');
          }
           
   });

   $(document).on('click','.close_gst_btn',function(){
      $('#preview_gst_img').removeAttr('src'); 
      $(this).addClass('d-none');
      $('#gst_label').html('Choose File...');
      $(this).parents().eq(2).find('#gst_attachment').val("");
   });
    // 
    var curNum ='';
      //
      $(document).on('click','.clickSelectFile',function(){ 
         curNum = $(this).attr('add-id');
         $('.fileupload').trigger('click');
      });

      $(document).on('click','.remove-image',function(){ 
         var current = $(this);
         var file_id = $(this).attr('data-id');
         
         swal({
               // icon: "warning",
               type: "warning",
               title: "Are You Want to Remove This File?",
               text: "",
               dangerMode: true,
               showCancelButton: true,
               confirmButtonColor: "#007358",
               confirmButtonText: "YES",
               cancelButtonText: "CANCEL",
               closeOnConfirm: false,
               closeOnCancel: false
               },
               function(e){
                  if(e==true)
                  {
                     //
                     var fd = new FormData();

                     fd.append('file_id',file_id);
                     fd.append('_token', '{{csrf_token()}}');
                     //
                     $.ajax({
                           type: 'POST',
                           url: "{{ url('/customers/remove/contractFile') }}",
                           data: fd,
                           processData: false,
                           contentType: false,
                           success: function(data) {
                              // console.log(data);
                              if (data.fail == false) {
                                 //reset data
                                 $('.fileupload').val("");
                                 //append result
                                 $(current).parent('.image-area').detach();
                              } 
                              else {
                              
                              console.log("file error!");
                              
                              }
                           },
                           error: function(error) {
                              console.log(error);
                              // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
                           }
                     });
                     swal.close();
                  }
                  else
                  {
                     swal.close();
                  }
            });
         // $('#fileupload-'+curNum).val("");
         // $(this).parent('.image-area').detach();
      });

      $(document).on('change','.fileupload',function(e){          
         uploadFile(curNum);
      });

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

//


function uploadFile(dynamicID){

   $("#fileUploadProcess").html("<img src='{{asset('images/process-horizontal.gif')}}' >"); 

   var fd = new FormData();
   var file = $('.fileupload')[0].files[0];
   fd.append('file',file);
   fd.append('_token', '{{csrf_token()}}');
   //
   $.ajax({
         type: 'POST',
         url: "{{ url('/customers/upload/contractFile') }}",
         data: fd,
         processData: false,
         contentType: false,
         success: function(data) {
         console.log(data);
         if (data.fail == false) {
         //reset data
         $('.fileupload').val("");
         $("#fileUploadProcess").html("");
         //append result
         $("#fileResult-"+dynamicID).prepend("<div class='image-area'><img style='height: 110px; width: 100%; object-fit: contain; font-size: 11px; padding-top: 10px' src='"+data.filePrev+"'  alt='Preview' title='"+data.filename+"'><a class='remove-image' data-id='"+data.file_id+"' href='javascript:;' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.file_id+"'></div>");
         } else {
            $("#fileUploadProcess").html("");
            alert("Please upload valid file! allowed file type, Image, PDF, Doc, Xlsx and Txt etc. ");
            console.log("file error!");
            
         }
         },
         error: function(error) {
            console.log(error);
            // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
         }
   });
  
  return false;
}
  
</script>

@endsection
