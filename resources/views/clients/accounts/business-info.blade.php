@extends('layouts.client')
@section('content')

<style>
   .disabled-link
   {
      pointer-events: none;
   }
</style>
@php
   use App\Traits\S3ConfigTrait;
@endphp
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
        <!-- ============Breadcrumb ============= -->
 <div class="row">
   <div class="col-sm-11">
       <ul class="breadcrumb">
       <li>
       <a href="{{ url('/my/home') }}">Dashboard</a>
       </li>
       <li>Business info</li>
       </ul>
   </div>
   <!-- ============Back Button ============= -->
   <div class="col-sm-1 back-arrow">
       <div class="text-right">
         <a href="{{ url()->previous() }}"> <i class="fas fa-arrow-circle-left fa-2x"></i></a>
       </div>
   </div>
 </div>   

      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Accounts/Business </h3>
               </div>
            </div>
         </div>
      </div> --}}
      <div class="row">
         
               <div class="col-md-3 content-container">
                  <!-- left-sidebar -->
                  @include('clients.accounts.sidebar') 
               </div>
               <!-- start right sec -->
                  <div class="col-md-9 content-wrapper" style="background-color: #fff;">
                     <div class="formCover">
                        <!-- section -->
                        
                           <div class="col-sm-12 ">
                             
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
                                    @if(stripos(Auth::user()->user_type,'client')!==false)
                                       <div class="col-md-12">
                                          <form class="mt-2" method="post" action="{{ url('/my/business_info/update') }}" enctype="multipart/form-data">
                                             @csrf
                                             <div class="row">
                                                <div class="col-sm-6">
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
                                                      <label>TIN Number  </label>
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
                                                      <label>Contract Signed By </label>
                                                      <input class="form-control" type="text" name="contract_signed_by" value="{{ $business->contract_signed_by }}" readonly>
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
                                                      <label>Contract Start Date </label>
                                                      <input class="form-control" type="text" name="contract_start_date" value="{{ date('d-m-Y',strtotime($business->work_order_date)) }}" readonly>
                                                      @if ($errors->has('contract_start_date'))
                                                      <div class="error text-danger">
                                                         {{ $errors->first('contract_start_date') }}
                                                      </div>
                                                      @endif
                                                   </div>
                                                </div>
                                                <div class="col-sm-6">
                                                   <div class="form-group">
                                                      <label>Contract End Date </label>
                                                      <input class="form-control" type="text" name="contract_end_date" value="{{ date('d-m-Y',strtotime($business->work_operating_date)) }}" readonly>
                                                      @if ($errors->has('contract_end_date'))
                                                      <div class="error text-danger">
                                                         {{ $errors->first('contract_end_date') }}
                                                      </div>
                                                      @endif
                                                   </div>
                                                </div>
                                             </div>
                                             {{-- <div class="row">
                                                <div class="col-sm-6">
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
                                                </div>
                                             </div> --}}
                                             <div class="row mt-2 mb-3">
                                                <div class="col-sm-12">
                                                   <div class="form-check form-check-inline error-control">
                                                      <input class="form-check-input gst_exempt" type="checkbox" name="gst_exempt" id="gst_exempt" @if($business->gst_exempt==1) checked @endif>
                                                      <label class="form-check-label" for="gst_exempt">GST Exempt</label>
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
                                                       {{-- <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-gst_number"></p> --}}
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="row">
                                                <div class="col-sm-6">
                                                   <div class="form-group">
                                                      <label>GST Attachment <span class="text-danger">*</span> <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,gif,svg,pdf"></i> <small>   </small></label>
                                                      <div class="custom-file error-control">
                                                         <input type="file" name="gst_attachment" class="custom-file-input gst_attachment" id="gst_attachment" data-pdf="{{url('/').'/admin/images/icon_pdf.png'}}" accept="image/*,.pdf">
                                                         <label class="custom-file-label" id="gst_label" for="gst_attachment">{{$business->gst_attachment!=NULL || $business->gst_attachment!=''?$business->gst_attachment:'Choose File...'}}</label>
                                                      </div>
                                                      @if ($errors->has('gst_attachment'))
                                                         <div class="error text-danger">
                                                            {{ $errors->first('gst_attachment') }}
                                                         </div>
                                                      @endif
                                                      {{-- <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-gst_attachment"></p> --}}
                                                   </div>
                                                </div>
                                                @if($business->gst_attachment!=NULL || $business->gst_attachment!='')
                                                   @php
                                                         $url = '';
                                                         if(stripos($business->gst_attachment_file_platform,'s3')!==false)
                                                         {
                                                            $filePath = 'uploads/gst-file/';

                                                            $s3_config = S3ConfigTrait::s3Config();

                                                            $disk = \Storage::disk('s3');

                                                            $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                                                  'Bucket'                     => \Config::get('filesystems.disks.s3.bucket'),
                                                                  'Key'                        => $filePath.$business->gst_attachment,
                                                                  'ResponseContentDisposition' => 'attachment;'//for download
                                                            ]);

                                                            $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                                            $url = $req->getUri();
                                                         }
                                                         else {
                                                            $url = url('/').'/uploads/gst-file/'.$business->gst_attachment;
                                                         }
                                                   @endphp
                                                   <div class="col-sm-6">
                                                      <div class="form-group">
                                                         <label for="gst_attachment"></label>
                                                         <span class="btn btn-link float-right text-dark close_gst_btn">X</span>
                                                         <img src="{{stripos($business->gst_attachment,'pdf')!==false ? url('/').'/admin/images/icon_pdf.png' : $url}}" id="preview_gst_img" width="200" height="150"/>
                                                      </div>
                                                   </div>
                                                @else
                                                   <div class="col-sm-6">
                                                      <div class="form-group">
                                                         <label for="gst_attachment"></label>
                                                         <span class="d-none btn btn-link float-right text-dark close_gst_btn">X</span>
                                                         <img id="preview_gst_img" width="200" height="150"/>
                                                      </div>
                                                   </div>
                                                @endif
                                             </div>
                                             <div class="row" style="margin-top: 10px;">
                                                <div class="col-sm-4">
                                                   <div class="form-group">
                                                      <label>Files (Contract files etc.)</label>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="row">
                                                <div class="col-sm-12">
                                                   <div class="form-group fileResult" id='fileResult-1'>
                                                      @if( count($files) > 0 )
                                                         @foreach($files as $item)
                                                            @php
                                                               $url = '';
                                                               if(stripos($item->file_platform,'s3')!==false)
                                                               {
                                                                  $filePath = 'uploads/customer-files/';
                        
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
                                                               else {
                                                                  $url = url('/').'/uploads/customer-files/'.$item->file_name;
                                                               }
                                                            @endphp
                                                            <a href="{{ $url }}" download>
                                                               <div class='image-area'>
                                                               <img style="height: 110px; width: 100%; object-fit: contain; font-size: 11px; padding-top: 10px" src="{{Helper::getCustomerFilePrev($item->id)}}" title="{{$item->file_name}}">
                                                               {{-- <p>{{$item->file_name}}</p>  --}}
                                                               </div>
                                                            </a>
                                                         @endforeach
                                                      @endif
                                                   </div>
                                                </div>
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
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Type of facility </label>
                                                   <input class="form-control " type="text" name="type_of_facility" value="{{ $business->type_of_facility }}" disabled>
                                                </div>
                                             </div>
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
                                                   <label>Contract Signed By </label>
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
                                          <div class="row">
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Contract Start Date </label>
                                                   <input class="form-control" type="text" name="contract_start_date" value="{{ $business->work_order_date }}" disabled>
                                                   @if ($errors->has('contract_start_date'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('contract_start_date') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Contract End Date </label>
                                                   <input class="form-control" type="text" name="contract_end_date" value="{{ $business->work_operating_date }}" disabled>
                                                   @if ($errors->has('contract_end_date'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('contract_end_date') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                          </div>
                                          {{-- <div class="row">
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>Billing Details </label>
                                                   <input class="form-control" type="text" name="billing_detail" value="{{ $business->billing_detail }}" disabled>
                                                   @if ($errors->has('billing_detail'))
                                                   <div class="error text-danger">
                                                      {{ $errors->first('billing_detail') }}
                                                   </div>
                                                   @endif
                                                </div>
                                             </div>
                                             <div class="col-sm-6">
                                             </div>
                                          </div> --}}
                                          <div class="row mt-2 mb-3">
                                             <div class="col-sm-12">
                                                <div class="form-check form-check-inline error-control disabled-link">
                                                   <input class="form-check-input gst_exempt" type="checkbox" name="gst_exempt" id="gst_exempt" @if($business->gst_exempt==1) checked @endif disabled>
                                                   <label class="form-check-label" for="gst_exempt">GST Exempt</label>
                                                </div>
                                             </div>
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
                                                    {{-- <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-gst_number"></p> --}}
                                                </div>
                                             </div>
                                          </div>
                                          <div class="row">
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label>GST Attachment <span class="text-danger">*</span> <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,gif,svg,pdf"></i> <small>   </small></label>
                                                   <div class="custom-file error-control disabled-link">
                                                      <input type="file" name="gst_attachment" class="custom-file-input gst_attachment" id="gst_attachment" data-pdf="{{url('/').'/admin/images/icon_pdf.png'}}" accept="image/*,.pdf" disabled>
                                                      <label class="custom-file-label" id="gst_label" for="gst_attachment">{{$business->gst_attachment!=NULL || $business->gst_attachment!=''?$business->gst_attachment:'Choose File...'}}</label>
                                                   </div>
                                                   @if ($errors->has('gst_attachment'))
                                                      <div class="error text-danger">
                                                         {{ $errors->first('gst_attachment') }}
                                                      </div>
                                                   @endif
                                                   {{-- <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-gst_attachment"></p> --}}
                                                </div>
                                             </div>
                                             @if($business->gst_attachment!=NULL || $business->gst_attachment!='')
                                                   @php
                                                         $url = '';
                                                         if(stripos($business->gst_attachment_file_platform,'s3')!==false)
                                                         {
                                                            $filePath = 'uploads/gst-file/';

                                                            $s3_config = S3ConfigTrait::s3Config();

                                                            $disk = \Storage::disk('s3');

                                                            $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                                                  'Bucket'                     => \Config::get('filesystems.disks.s3.bucket'),
                                                                  'Key'                        => $filePath.$business->gst_attachment,
                                                                  'ResponseContentDisposition' => 'attachment;'//for download
                                                            ]);

                                                            $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                                            $url = $req->getUri();
                                                         }
                                                         else {
                                                            $url = url('/').'/uploads/gst-file/'.$business->gst_attachment;
                                                         }
                                                   @endphp
                                                <div class="col-sm-6">
                                                   <div class="form-group">
                                                      <label for="gst_attachment"></label>
                                                      {{-- <span class="btn btn-link float-right text-dark close_gst_btn">X</span> --}}
                                                      <img src="{{stripos($business->gst_attachment,'pdf')!==false ? url('/').'/admin/images/icon_pdf.png' : $url}}" id="preview_gst_img" width="200" height="150"/>
                                                   </div>
                                                </div>
                                             @else
                                                <div class="col-sm-6">
                                                   <div class="form-group">
                                                      <label for="gst_attachment"></label>
                                                      {{-- <span class="d-none btn btn-link float-right text-dark close_gst_btn">X</span> --}}
                                                      <img id="preview_gst_img" width="200" height="150"/>
                                                   </div>
                                                </div>
                                             @endif
                                          </div>
                                          <div class="row" style="margin-top: 10px;">
                                             <div class="col-sm-4">
                                                <div class="form-group">
                                                   <label>Files (Contract files etc.)</label>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="row">
                                             <div class="col-sm-12">
                                                <div class="form-group fileResult" id='fileResult-1'>
                                                   @if( count($files) > 0 )
                                                      @foreach($files as $item)
                                                         @php
                                                            $url = '';
                                                            if(stripos($item->file_platform,'s3')!==false)
                                                            {
                                                               $filePath = 'uploads/customer-files/';
                     
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
                                                            else {
                                                               $url = url('/').'/uploads/customer-files/'.$item->file_name;
                                                            }
                                                         @endphp
                                                         <a href="{{ $url }}" download>
                                                            <div class='image-area'>
                                                            <img style="height: 110px; width: 100%; object-fit: contain; font-size: 11px; padding-top: 10px" src="{{Helper::getCustomerFilePrev($item->id)}}" title="{{$item->file_name}}">
                                                            {{-- <p>{{$item->file_name}}</p>  --}}
                                                            </div>
                                                         </a>
                                                      @endforeach
                                                   @endif
                                                </div>
                                             </div>
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
   
   });
                     
</script>  
@endsection
