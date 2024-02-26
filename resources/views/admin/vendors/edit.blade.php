@extends('layouts.admin')
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
@php
   use App\Traits\S3ConfigTrait;
@endphp
<div class="main-content-wrap sidenav-open d-flex flex-column">
    <!-- ============ Body content start ============= -->
    <div class="main-content">	
        <div class="row">
            <div class="col-sm-11">
                <ul class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li><a href="{{ url('/admin/vendor') }}">Vendor</a></li>
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
                <div class="card-body" >
               
                    <div class="col-md-8 offset-md-2">
                        <form class="mt-2" method="post" action="{{ url('/admin/vendor/update') }}"  id="updateVendorfrm" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <h4 class="card-title mb-3">Edit vendor </h4> 
                                    <p> Fill the required details </p>			
                                </div>
                            </div>
                            <div class="col-md-10">		

                                <!--  <div class="form-group">
                                    <label for="country">Country</label>
                                    <select class="form-control" name="country" >
                                        <option value="1">India</option>
                                    </select>
                                </div>	 -->   
                               
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="first_name">First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter first name" value="{{$vendor->first_name}}">
                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-first_name"></p>
                                        </div>

                                    <input type="hidden" name="vendor_id" class="form-control" id="vendor_id"  value="{{ $vendor_id}}">


                                    <input type="hidden" name="user_id" class="form-control" id="user_id"  value="{{  $vendor->user_id}}">
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Last Name</label>
                                            <input type="text" name="last_name" class="form-control" id="last_name"  placeholder="Enter last name" value="{{ $vendor->last_name}}">
                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-last_name"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="phone">Phone<span class="text-danger">*</span></label>
                                            <input type="text" name="phone" class="form-control" id="phone" maxlength="10" placeholder="Enter phone" value="{{ $vendor->phone}}">
                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-phone"></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email">Email<span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" value="{{ $vendor->email}}">
                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-email"></p>
                                            
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row"> --}}
                                    {{-- <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="company">Service <span class="text-danger">*</span></label>
                                            <input type="text" name="service" class="form-control" id="service" placeholder="service" value="{{ $vendor->service}}">
                                            @if ($errors->has('service'))
                                            <div class="error text-danger">
                                            {{ $errors->first('service') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="company">Company or business name <span class="text-danger">*</span></label>
                                            <input type="text" name="company" class="form-control" id="company" placeholder="Company" value="{{$vendor->company_name}}">
                                            @if ($errors->has('company'))
                                            <div class="error text-danger">
                                            {{ $errors->first('company') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div> --}}
                                {{-- </div> --}}

                                <!-- business details -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="card-title mb-3 mt-3">Business Information </h4> 
                                                <p>  </p>         
                                    </div>
                                   @php
                                //    dd($vendor_id);
                                       $company_status = Helper::company_type($vendor_id);
                                   @endphp 
                                        <div class="col-md-12"> 
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <input  type="radio" class="verifier" id="verifier" name="verifier" value="company" @if($company_status) @if($company_status->vendor_type=='company') checked @endif @endif> Company
                                                        <input type="radio" class="verifier" id="verifier" name="verifier" value="individual" @if($company_status)@if($company_status->vendor_type=='individual') checked @endif @endif> Individual
                                                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-verifier"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($company_status->vendor_type=='company')   
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group bussiness_name">
                                                            <label for="company">Company or business name  <span class="text-danger  "></span> @if($company_status) @if($company_status->vendor_type=='company')<span class="text-danger">*</span>@endif @endif</label>
                                                            <input type="text" name="company" class="form-control" id="company" placeholder="Company" value="{{$vendor->company_name}}">
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-company"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row d-none individual_name">
                                                    <div class="col-sm-12 ">
                                                        <div class="form-group">
                                                            <label for="individual">Full name <span class="text-danger  ">*</span></label>
                                                            <input type="text" name="individual" class="form-control individual" id="individual" placeholder="Full name" value="{{$vendor->individual_name}}">
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-individual"></p>
                                                        </div>
                                                    </div> 
                                                </div>
                                            @else
                                                <div class="row d-none bussiness">
                                                    <div class="col-sm-12">
                                                        <div class="form-group bussiness_name">
                                                            <label for="company">Company or business name  <span class="text-danger  "></span> @if($company_status) @if($company_status->vendor_type=='company')<span class="text-danger">*</span>@endif @endif</label>
                                                            <input type="text" name="company" class="form-control" id="company" placeholder="Company" value="{{$vendor->company_name}}">
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-company"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row individual_name">
                                                    <div class="col-sm-12 ">
                                                        <div class="form-group">
                                                            <label for="individual">Full name <span class="text-danger  ">*</span></label>
                                                            <input type="text" name="individual" class="form-control individual" id="individual" placeholder="Full name" value="{{$vendor->individual_name}}">
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-individual"></p>
                                                        </div>
                                                    </div> 
                                                </div>
                                            @endif
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Country <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="country" id="country">
                                                    <option value="">Select Country</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->id }}" @if($country->id == $vendor->country_id) selected="" @endif >{{ $country->name }}</option>
                                                    @endforeach
                                                    </select>
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-country"></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>State <span class="text-danger">*</span></label>
                                                    <select class="form-control state" name="state" id="state">
                                                        <option value="">Select State</option>
                                                         @foreach($state as $states)
                                                           <option value="{{ $states->id }}" @if($states->id == $vendor->state) selected @endif>{{ $states->name }}</option>
                                                         @endforeach
                                                        </select>
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-state"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>City/Town/District <span class="text-danger">*</span></label>
                                                    <select class="form-control city" name="city" id="city">
                                                    @foreach($cities as $city)
                                                        <option value="{{ $city->id }}" @if($city->id == $vendor->city) selected @endif>{{ $city->name }}</option>
                                                    @endforeach
                                                    </select>
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-city"></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Pin Code<span class="text-danger">*</span></label>
                                                    <input class="form-control number_only" type="text" name="pincode" value="{{$vendor->pincode}}">
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-pincode"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Address (HO) <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text" name="address" value="{{$vendor->address}}">
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-address"></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Email <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="email" name="business_email" value="{{ $business->email}}">
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-business_email"></p>
                                                </div>
                                            </div>
                                        </div>
                    
                                        <div class="row">
                                            
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Phone Number <span class="text-danger">*</span></label>
                                                    <input type="hidden" id="code2" name ="primary_phone_code2" value="91" >
                                                    <input type="hidden" id="iso2" name ="primary_phone_iso2" value="in" >
                                                    <input class="form-control number_only" id="phone2" type="text" maxlength="10" name="business_phone_number" value="{{ $business->phone}}">
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-business_phone_number"></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                <label>Website </label>
                                                <input class="form-control" type="text" name="website" value="{{ $business->website}}">
                                                </div>
                                            </div>
                                        </div>
                    
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    {{-- <span class="text-danger star d-none ">*</span> @if($company_status) @if($company_status->vendor_type=='company')<span class="text-danger star ">*</span>@endif @endif --}}
                                                <label>GST Number  </label>
                                                <input class="form-control" type="text" name="gst_number" value="{{ $business->gst_number}}" placeholder="Ex:- 22AAAAA4444A1Z5">
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-gst_number"></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    {{-- @if($company_status) @if($company_status->vendor_type=='company')<span class="text-danger star ">*</span>@endif @endif --}}
                                                    <label>TIN Number </label>
                                                    <input class="form-control" type="text" name="tin_number" value="{{ $business->tin_number}}">
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-tin_number"></p>
                                                </div>
                                            </div>
                                        </div>
                    
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Contract Signed By <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text" name="contract_signed_by" value="{{ $business->contract_signed_by}}">
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-contract_signed_by"></p>
                                                    <small class="text-muted">(Person name who signed the contract)</small>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>PAN Number  <span class="text-danger star d-none ">*</span> @if($company_status) @if($company_status->vendor_type=='company')<span class="text-danger star ">*</span>@endif @endif</label>
                                                    <input class="form-control" type="text" name="pan_number" value="{{ $business->pan_number}}" placeholder="Ex:- DPAGA4875J">
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-pan_number"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                <label>Company Logo   <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,gif,svg "></i> <small>   </small></label>
                                                <input class="form-control" type="file" name="company_logo" id="company_logo" accept=".jpeg,.png,.jpg,.gif,.svg">
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-company_logo"></p>
                                                </div>
                                            </div>
                                            @if($user->company_logo!=NULL || $user->company_logo!='')
                                                @php
                                                   $url = '';
                                                    if(stripos($user->company_logo_file_platform,'s3')!==false)
                                                    {
                                                        $filePath = 'uploads/company-logo/';

                                                        $s3_config = S3ConfigTrait::s3Config();

                                                        $disk = \Storage::disk('s3');

                                                        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                                            'Bucket'                     => \Config::get('filesystems.disks.s3.bucket'),
                                                            'Key'                        => $filePath.$user->company_logo,
                                                            'ResponseContentDisposition' => 'attachment;'//for download
                                                        ]);

                                                        $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                                        $url = $req->getUri();
                                                    }
                                                    else {
                                                        $url = url('/').'/uploads/company-logo/'.$user->company_logo;
                                                    } 
                                                @endphp
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="company_logo"></label>
                                                        <span class="btn btn-link float-right text-dark close_btn">X</span>
                                                        <img id="preview_img"  src="{{$url}}" width="200" height="150"/>
                                                    </div>
                                                </div>
                                            @else
                                               <div class="col-sm-6">
                                                  <div class="form-group">
                                                  <label for="company_logo"></label>
                                                  <span class="d-none btn btn-link float-right text-dark close_btn">X</span>
                                                  <img id="preview_img"  width="200" height="150"/>
                                                  </div>
                                               </div>
                                            @endif
                                         
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-5">
                                                <div class="form-group">
                                                    <label>Files (Contract files etc.)</label>
                                                    <a class='btn-link clickSelectFile' add-id='1' style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a>
                                                    <input type='file' class='fileupload' name='file' style='display:none'/>
                                                
                                                </div>
                                            </div>
                                            <div class="col-sm-7">
                                                <div class="form-group fileResult" id='fileResult-1' >
                                                
                                                    @if( count($files) > 0 )
                                                        @foreach($files as $item)
                                                            @php
                                                                $url = '';
                                                                if(stripos($item->file_platform,'s3')!==false)
                                                                {
                                                                    $filePath = 'uploads/vendor-files/';

                                                                    $s3_config = S3ConfigTrait::s3Config();

                                                                    $disk = \Storage::disk('s3');

                                                                    $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                                                            'Bucket'                     => \Config::get('filesystems.disks.s3.bucket'),
                                                                            'Key'                        => $filePath.$item->file_name,
                                                                            'ResponseContentDisposition' => 'attachment;'//for download
                                                                    ]);

                                                                    $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                                                    $url = $req->getUri();
                                                                }
                                                                else
                                                                {
                                                                    $url = url('/').'/uploads/vendor-files/'.$item->file_name;
                                                                }
                                                            @endphp
                                                            <a href="{{ $url }}" download>
                                                                <div class='image-area'>
                                                                    <img style=" height: 110px; width: 100%; object-fit: contain; font-size: 11px; padding-top: 10px" src="{{Helper::getVendorFilePrev($item->id)}}">
                                                                    <p>{{$item->file_name}}</p>  
                                                                </div>
                                                            </a>
                                                        @endforeach
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
                                    <h4 class="card-title mb-3 mt-3">Contact Person Information </h4> 
                                    <p>  </p>         
                                    </div>
                                    <div class="col-md-12">    
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>First Name <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text"  name="owner_first_name" value="{{ $owner->first_name }}">
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-owner_first_name"></p>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Last Name </label>
                                                <input class="form-control" type="text" name="owner_last_name" value="{{ $owner->last_name }}">
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-owner_last_name"></p>
                                            </div>
                                            </div>
                                        </div>      
                                        <div class="row">
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Email <span class="text-danger">*</span></label>
                                                <input class="form-control" type="email" name="owner_email" value="{{ $owner->email }}">
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-owner_email"></p>
                                            </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Phone Number <span class="text-danger">*</span></label>
                                                <input type="hidden" id="code3" name ="primary_phone_code3" value="91" >
                                                <input type="hidden" id="iso3" name ="primary_phone_iso3" value="in" >
                                                <input class="form-control number_only" id="phone3" type="text" maxlength="10" name="owner_phone_number" value="{{ $owner->phone }}">
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-owner_phone_number"></p>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Designation <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text" name="owner_designation" value="{{ $owner->designation }}">
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-owner_designation"></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Landline Number </label>
                                                    <input class="form-control number_only owner_landline_number" type="text" maxlength="10" name="owner_landline_number" value="{{ $owner->landline_number }}">
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-owner_landline_number"></p>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ./ -->
                                <span class="contact_div">
                                    @if (count($type)>0)
                                        
                                   
                                        @foreach($type as $key => $types)
                                         <!-- Plan details -->
                                     
                                            <div class='projectReport' style='padding: 20px;margin-top:15px; border:1px solid #ddd; background:#fff;'>
                                                <span class="btn btn-link float-right delete_contact_type" data-type_id="{{base64_encode($types->id)}}">X</span>
                                                <input class='form-control' type='hidden' name='type_id[]' value='{{ $types->id }}'>
                                                <h3 style='padding: 10px;background:#eee;'>New contact </h3>
                                                <div class='row'>
                                                    <div class='col-sm-6'>
                                                        <div class='form-group'>
                                                            <label style='font-size: 16px;'> Contact Type </label>
                                                            <input class='form-control' type='text' name='type[]' value='{{ $types->contact_type }}'>
                                                            <small class='text-muted'>Add you contact title (Example: Manager)</small>
                                                            <p style='margin-bottom: 2px;' class='text-danger error_container error-type' id="error-type_{{$key}}"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='row'>
                                                    <div class='col-sm-6'>
                                                        <div class='form-group'>
                                                            <label>First name</label>
                                                            <input class='form-control' type='text'  name='add_first_name[]' value='{{$types->first_name }}'>
                                                            <p style='margin-bottom: 2px;' class='text-danger error_container error-add_first_name' id="error-add_first_name_{{$key}}"></p>
                                                        </div>
                                                    </div>
                                                    <div class='col-sm-6'>
                                                        <div class='form-group'>
                                                            <label>Last name </label>
                                                            <input class='form-control' type='text'  name='add_last_name[]' value='{{$types->last_name }}'>
                                                            <p style='margin-bottom: 2px;' class='text-danger error_container error-add_last_name' id="error-add_last_name_{{$key}}"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='row'>
                                                    <div class='col-sm-6'>
                                                        <div class='form-group'>
                                                            <label>Email </label>
                                                            <input class='form-control' type='text'  name='add_email[]' value='{{$types->email }}'>
                                                            <p style='margin-bottom: 2px;' class='text-danger error_container error-add_email' id="error-add_email_{{$key}}"></p>
                                                        </div>
                                                    </div>
                                                    <div class='col-sm-6'>
                                                        <div class='form-group'>
                                                            <label>Phone Number </label>
                                                            <input class='form-control number_only' maxlength='10' type='text' name='add_phone[]' value='{{$types->phone }}'>
                                                            <p style='margin-bottom: 2px;' class='text-danger error_container error-add_phone' id="error-add_phone_{{$key}}"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='row'>
                                                    <div class='col-sm-6'>
                                                        <div class='form-group'>
                                                            <label>Designation </label>
                                                            <input class='form-control' type='text' name='add_designation[]' value='{{$types->designation }}'>
                                                            <p style='margin-bottom: 2px;' class='text-danger error_container error-add_designation' id="error-add_designation_{{$key}}"></p>
                                                        </div>
                                                    </div>
                                                    <div class='col-sm-6'>
                                                        <div class='form-group'>
                                                            <label> Landline number </label>
                                                            <input class='form-control number_only' maxlength='10' type='text' name='add_landline_number[]' value='{{$types->landline_number }}'>
                                                            <p style='margin-bottom: 2px;' class='text-danger error_container error-add_landline_number' id="error-add_landline_number_{{$key}}"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='row'>
                                                    <div class='col-sm-6'>
                                                    </div>
                                                </div>
                                            </div>
                        
                                        @endforeach
                                    @endif
                                </span>
                                <span class="addDiv"></span>
                                
                                <a href="javascript:;" class="add"> Add <i class="fa fa-plus"></i></a>

                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span> : </label>
                                    <input type="radio" id="status" name="status" value="1" {{$vendor->status == 1 ? 'checked' : ''}}> Active
                                    <input type="radio" id="status" name="status" value="0" {{$vendor->status == 0 ? 'checked' : ''}} > Inactive
                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-status"></p>
                                </div>
                               
                                <div class="col-md-10">             

                                    <button type="submit" class="btn btn-info submit">Update</button>

                                </div>	
                           
                                    <!--  -->
                    
                            </div>  <!--  -->
                            
                        </form>
                    </div>
                </div>
				
            </div><!-- Footer Start -->
            <div class="flex-grow-1"></div>
			
        </div>
    </div>

    <script>
        $(document).ready(function(){
        
            $(document).on('submit', 'form#updateVendorfrm', function (event) {
                event.preventDefault();
                //clearing the error msg
                $('p.error_container').html("");
                $('.form-control').removeClass('border-danger');
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
                            $('.submit').html('Update');
                        },2000);

                        console.log(response);
                        if(response.success==true) {          
                            // window.location = "{{ url('/')}}"+"/sla/?created=true";
                            toastr.success('Vendor has been updated successfully.');
                            window.setTimeout(function(){
                                window.location = "{{ url('/')}}"+"/admin/vendor/";
                            },2000);
                        }
                        //show the form validates error
                        if(response.success==false ) {                              
                            for (control in response.errors) {  
                                var error_text = control.replace('.',"_");
                                // $('.'+control).addClass('border-danger'); 
                                // $('#error-' + control).html(response.errors[control]);
                                $('#error-'+error_text).html(response.errors[control]);
                            }
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        // alert("Error: " + errorThrown);
                    }
                });
                return false;
            });

            //hide and show import data
            $(document).on('change', '.verifier', function (e) {
                e.preventDefault();  //stop the browser from following
                var _current =$(this);
                var id=_current.val();
                // alert(id);
                if (id =='company') {
                    $(".star").removeClass('d-none');
                    $(".bussiness").removeClass('d-none');
                    $(".bussiness_name").removeClass('d-none');
                    $(".individual_name").addClass('d-none');
                    // $(".multiple").hide();
                }
                else {
                    $(".star").addClass('d-none');
                
                    $(".bussiness_name").addClass('d-none');
                    $(".individual_name").removeClass('d-none');
                    // $(".multiple").show();bussiness
                }
            });
                //on change country
            $(document).on('change','.country',function(){ 
                var id = $('#country').val();
                $.ajax({
                        type:"post",
                        url:"{{route('/customers/getstate')}}", 
                        data:{'country_id':id,"_token": "{{ csrf_token() }}"},
                        success:function(data)
                        {       
                            $("#state").empty();
                            $("#state").html('<option>Select State</option>');
                            $.each(data,function(key,value){
                            $("#state").append('<option value="'+value.id+'">'+value.name+'</option>');
                            });
                        }
                    });
            });
            
            //    on change state
            $(document).on('change','.state',function(){ 
                var id = $('#state').val();
                $.ajax({
                        type:"post",
                        url:"{{route('/customers/getcity')}}", 
                        data:{'state_id':id,"_token": "{{ csrf_token() }}"},
                        success:function(data)
                        {       
                            $("#city").empty();
                            $("#city").html('<option>Select City</option>');
                            $.each(data,function(key,value){
                            $("#city").append('<option value="'+value.id+'">'+value.name+'</option>');
                            }); 
                        }
            
                    });
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
    
    
            //Add other contact person page
            $(document).on('click','.add',function(){ 
                $(".addDiv").append(
                    `<div class='projectReport' row-id='1' style='padding: 20px;margin-top:15px; border:1px solid #ddd; background:#fff;'>
                    <span class="btn btn-link float-right close_div">X</span>
                    <h3 style='padding: 10px;background:#eee;'>Add a new contact </h3>
                    <div class='row'>
                    <div class='col-sm-6'>
                    <div class='form-group'>
                    <label style='font-size: 16px;'> Contact Type </label>
                    <input class='form-control' type='text' name='type[]' value=''>
                    <small class='text-muted'>Add you contact title (Example: Manager)</small>
                    <p style='margin-bottom: 2px;' class='text-danger error_container error-type' id="error-type"></p>
                    </div>
                    </div>
                    </div>
                    <div class='row'>
                    <div class='col-sm-6'>
                    <div class='form-group'>
                    <label>First name</label>
                    <input class='form-control' type='text'  name='add_first_name[]' >
                    <p style='margin-bottom: 2px;' class='text-danger error_container error-add_first_name' id="error-add_first_name"></p>
                    </div>
                    </div>
                    <div class='col-sm-6'>
                    <div class='form-group'>
                    <label>Last name </label>
                    <input class='form-control' type='text'  name='add_last_name[]'>
                    <p style='margin-bottom: 2px;' class='text-danger error_container error-add_last_name' id="error-add_last_name"></p>
                    </div>
                    </div>
                    </div>
                    <div class='row'>
                    <div class='col-sm-6'>
                    <div class='form-group'>
                    <label>Email </label>
                    <input class='form-control' type='text'  name='add_email[]' >
                    <p style='margin-bottom: 2px;' class='text-danger error_container error-add_email' id="error-add_email"></p>
                    </div>
                    </div>
                    <div class='col-sm-6'>
                    <div class='form-group'>
                    <label>Phone Number </label>
                    <input class='form-control number_only' maxlength='10' type='text' name='add_phone[]' >
                    <p style='margin-bottom: 2px;' class='text-danger error_container error-add_phone' id="error-add_phone"></p>
                    </div>
                    </div>
                    </div>
                    <div class='row'>
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
                validateContact();
            });

            $(document).on('click','.close_div',function(){
                var _this=$(this);
                _this.parent().fadeOut("slow", function(){ 
                    _this.parent().remove();
                    validateContact();
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
        
            // 
            var curNum ='';
            //
            $(document).on('click','.clickSelectFile',function(){ 
                curNum = $(this).attr('add-id');
                $('.fileupload').trigger('click');
            });
        
            $(document).on('click','.remove-image',function(){ 
                $('#fileupload-'+curNum).val("");
                $(this).parent('.image-area').detach();
            });
        
            $(document).on('change','.fileupload',function(e){          
                uploadFile(curNum);
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
                        url: "{{ url('/admin/vendor/upload/contractFile') }}",
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
                        $("#fileResult-"+dynamicID).prepend("<div class='image-area'><img src='"+data.filePrev+"'  alt='Preview'><a class='remove-image' href='javascript:;' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.file_id+"'><p style='font-size:12px;'>"+data.filename+"</p></div>");
                        } else {
                            $("#fileUploadProcess").html("");
                            alert("Please upload valid file! allowed file type, Image, PDF only. ");
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

            function validateContact()
            {
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
            }
        
        });
    
    </script>
@endsection