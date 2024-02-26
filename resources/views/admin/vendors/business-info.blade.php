@extends('layouts.admin')
@section('content')
<style type="text/css">
   ul,li
   {
     list-style-type: none;
   }
   .disabled-link{
      pointer-events: none;
   }
   </style>
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             <li>
             <a href="{{ url('/home') }}">Dashboard</a>
             </li>
             <li>
                 <a href="{{ url('/admin/vendor') }}">Vendors</a>
             </li>
             <li>Business Info</li>
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
           
               <div class="col-md-3 content-container">
                  <!-- left-sidebar -->
                  @include('admin.vendors.left-sidebar') 
               </div>
                  <!-- start right sec -->
                  <div class="col-md-9 content-wrapper bg-white">
                     <div class="formCover" style="height: 100vh;">
                        <!-- section -->
                        <section>
                           <div class="col-sm-12 ">
                                <form class="mt-2" method="post" action="{{ route('/admin/vendor/business-info',['id'=>base64_encode($vendor_id)]) }}"  id="updateVendorfrm" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="user_id" class="form-control" id="user_id"  value="{{ $profile->user_id}}">
                                    <!-- row -->
                                    <div class="row">
                                        <div class="col-md-12">
                                        <h4 class="card-title mb-1 mt-3">Business Information </h4> 
                                        <p class="pb-border"> Your Vendor Business info  </p>
                                        </div>
                                        <div class="col-md-12">
                                            @php
                                                $company_status = Helper::company_type($vendor_id);
                                            @endphp 
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <input type="radio" class="verifier" id="verifier" name="verifier" value="company" @if($company_status) @if($company_status->vendor_type=='company') checked @endif @endif> <label for="verifier"> Company</label> 
                                                        <input type="radio" class="verifier" id="individual" name="verifier" value="individual" @if($company_status)@if($company_status->vendor_type=='individual') checked @endif @endif> <label for="individual"> Individual</label> 
                                                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-verifier"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            @if($company_status->vendor_type=='company')   
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group bussiness_name">
                                                            <label for="company">Company or business name  <span class="text-danger  "></span> @if($company_status) @if($company_status->vendor_type=='company')<span class="text-danger">*</span>@endif @endif</label>
                                                            <input type="text" name="company" class="form-control" id="company" placeholder="Company" value="{{$profile->company_name}}">
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-company"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row d-none individual_name">
                                                    <div class="col-sm-12 ">
                                                        <div class="form-group">
                                                            <label for="individual">Full name <span class="text-danger  ">*</span></label>
                                                            <input type="text" name="individual" class="form-control individual" id="individual" placeholder="Full name" value="{{$profile->individual_name}}">
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-individual"></p>
                                                        </div>
                                                    </div> 
                                                </div>
                                            @else
                                                <div class="row d-none bussiness">
                                                    <div class="col-sm-12">
                                                        <div class="form-group bussiness_name">
                                                            <label for="company">Company or business name  <span class="text-danger  "></span> @if($company_status) @if($company_status->vendor_type=='company')<span class="text-danger">*</span>@endif @endif</label>
                                                            <input type="text" name="company" class="form-control" id="company" placeholder="Company" value="{{$profile->company_name}}">
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-company"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row individual_name">
                                                    <div class="col-sm-12 ">
                                                        <div class="form-group">
                                                            <label for="individual">Full name <span class="text-danger  ">*</span></label>
                                                            <input type="text" name="individual" class="form-control individual" id="individual" placeholder="Full name" value="{{$profile->individual_name}}">
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-individual"></p>
                                                        </div>
                                                    </div> 
                                                </div>
                                            @endif

                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Email <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="email" name="business_email" value="{{$business->email}}">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-business_email"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Phone Number <span class="text-danger">*</span></label>
                                                        <input type="hidden" id="code2" name ="primary_phone_code1" value="91" >
                                                        <input type="hidden" id="iso2" name ="primary_phone_iso1" value="in" >
                                                        <input class="form-control number_only" id="phone1" type="text" name="business_phone_number" value="{{$business->phone}}">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-business_phone_number"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>Website </label>
                                                    <input class="form-control" type="text" name="website" value="{{$business->website}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    <label>GST Number  </label>
                                                    <input class="form-control" type="text" name="gst_number" value="{{ $business->gst_number}}" placeholder="Ex:- 22AAAAA4444A1Z5">
                                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-gst_number"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>TIN Number </label>
                                                        <input class="form-control" type="text" name="tin_number" value="{{$business->tin_number}}">
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
                                                        <label>PAN Number <span class="text-danger star @if($company_status) @if($company_status->vendor_type!='company') d-none @endif @endif">*</span></label>
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
                                                <div class="col-12 pt-2">
                                                <h5>Address Information</h5>
                                                <p class="pb-border"></p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Address (HO) <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" name="address" value="{{$profile->address}}">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-address"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Pin Code<span class="text-danger">*</span></label>
                                                        <input class="form-control number_only" type="text" name="pincode" value="{{$profile->pincode}}">
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-pincode"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Country <span class="text-danger">*</span></label>
                                                        <select class="form-control" name="country" id="country">
                                                        <option value="">Select Country</option>
                                                        @foreach($countries as $country)
                                                            <option value="{{ $country->id }}" @if($country->id == $profile->country_id) selected="" @endif >{{ $country->name }}</option>
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
                                                            <option value="{{ $states->id }}" @if($states->id == $profile->state) selected @endif>{{ $states->name }}</option>
                                                            @endforeach
                                                            </select>
                                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-state"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>City/Town/District <span class="text-danger">*</span></label>
                                                        <select class="form-control city" name="city" id="city">
                                                        @foreach($cities as $city)
                                                            <option value="{{ $city->id }}" @if($city->id == $profile->city) selected @endif>{{ $city->name }}</option>
                                                        @endforeach
                                                        </select>
                                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-city"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-md btn-info">Update</button>
                                     </div>
                                </form>
                           </div>
                        </section>
                        <!-- ./section -->
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
                        toastr.success('Vendor Business Info has been updated successfully.');
                        window.setTimeout(function(){
                            window.location.reload;
                        },2000);
                    }
                    //show the form validates error
                    if(response.success==false ) {                              
                        for (control in response.errors) {  
                            $('.'+control).addClass('border-danger'); 
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
   
   
   });
                     
</script>  
@endsection
