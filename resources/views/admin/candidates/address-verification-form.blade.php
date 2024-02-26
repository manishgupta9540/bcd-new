@extends('layouts.admin')
@section('content')
<style>
    .disabled-link{
        pointer-events: none;
    }
    .data-selfie img {
        max-width: 400px;
        height: 100%;
    }
    .data-selfie {
        border: 1px solid #ccc;
        overflow: hidden;
    }

    .data-selfie img {
        /* max-width: 400px; */
        height: 200px;
        width: 100%;
        padding: 10px;
        object-fit: contain;
    }
    .data-selfie .img-data {
        padding: 10px;
        /*position: absolute; */
        /* bottom: 30px; */
        text-align: center;
    }
</style>
<div class="main-content-wrap sidenav-open d-flex flex-column mt-80">
    <div class="main-content">
        <div class="row">
            <div class="col-sm-11 breadcrum1">
                <ul class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li><a href="{{ url('/candidates') }}">Candidate</a></li>
                <li><a href="{{ url('/candidates/jaf-info',['id'=>base64_encode($jaf_data->candidate_id)]) }}">JAF</a></li>
                <li>Digital Address Verification</li>
                </ul>
            </div>
            <!-- ============Back Button ============= -->
            <div class="col-sm-1 back-arrow">
                <div class="text-right">
                <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
                </div>
            </div>
        </div>
        <div class="print_this location-div">
            <div class="card text-left">
                <div class="card-body mb-4">
                    <form method="post" action="{{url('/candidates/address-verification-form',['id'=>base64_encode($jaf_data->id)])}}" id="address_frm">
                        @csrf
                            @php
                                $candidate = Helper::candidate_details($jaf_data->candidate_id);
                                $candidate_address = $jaf_data->form_data;

                                $addr = '';
                                $zip = '';
                                $contact_number = '';

                                $address_type = 'others';

                                if($address_verification!=NULL && $address_verification->address_type!=NULL)
                                {
                                    $address_type = $address_verification->address_type;
                                }
                                else if($jaf_data->address_type!=NULL)
                                {
                                    $address_type = $jaf_data->address_type;
                                }
                                
                                if($candidate_address!=null)
                                {
                                    $input_item_data_array =  json_decode($candidate_address, true);

                                    foreach ($input_item_data_array as $key => $input) {
                                        $key_val = array_keys($input);
                                        $input_val = array_values($input);
                                        // dd($key_val);
                                        if(stripos($key_val[0],'Address')!==false){ 
                                            
                                            $addr =$input_val[0]!=NULL ? $input_val[0] : '';
                                            // dd($addr);
                                        }
                                        if(stripos($key_val[0],'Pin Code')!==false){ 
                                            // dd($input_val);
                                            $zip =$input_val[0]!=NULL ? $input_val[0] : '';
                                        }
                                        if(stripos($key_val[0],'Contact Number')!==false){ 
                                            // dd($key_val);
                                            $contact_number =$input_val[0]!=NULL ? $input_val[0] : '';
                                            // dd($city);
                                        }
                                    }
                                }

                            @endphp
                        <div class="row">
                            <div class="col-9 QC-data">
                                <h3 class="card-title mb-3"> Digital Address Verification </h3>
                            </div>
                            @if ($verification_decision!=NULL)
                                <div class="col-3 QC-data text-right mb-2">
                                    @if($verification_decision->qc_decision!=0 && $verification_decision->qc_decision!=1)
                                        <label>Status :-</label>
                                        @if($verification_decision->qc_decision==2)
                                            <span class="text-danger">Fail Resend Has Been Sent By (SMS)</span>
                                        @elseif ($verification_decision->qc_decision==3)
                                            <span class="text-danger">Fail Resend Has Been Sent By (Mail)</span>
                                        @elseif ($verification_decision->qc_decision==4)
                                            <span class="text-danger">Fail Resend Has Been Sent By (SMS & Mail)</span>
                                        @endif
                                    @endif
                                </div>
                            @endif
                            <div class="col-12 QC-data mb-3">
                                <div class="data">
                                    <div class="row">
                                        <div class="col-12 col-md-6 col-lg-6"><p><strong>Reference Number:</strong>
                                            {{ Helper::candidate_reference_id($jaf_data->candidate_id) }}</p></div>
                                        <div class="col-12 col-md-6 col-lg-6"><p>
                                            <strong>Email:</strong> {{ $candidate->email ? $candidate->email : 'N/A' }}
                                            <input type="hidden" name="email_address" value="{{ $candidate->email }}">
                                        </p></div>
                                        <div class="col-12 col-md-6 col-lg-6"><p>
                                            <strong>Alternate Email:</strong> {{ $candidate->alternative_email?$candidate->alternative_email:'N/A' }}
                                            <input type="hidden" name="alternative_email_address" value="{{ $candidate->alternative_email }}">
                                        </p></div>
                                        <div class="col-12 col-md-6 col-lg-6"><p><strong>Client Name:</strong>
                                            {{ Helper::company_name($jaf_data->business_id) }}</p></div>
                                        <div class="col-12 col-md-6 col-lg-6"><p>
                                            <strong>Contact Number:</strong> {{$contact_number}}
                                            <input type="hidden" name="phone_number" value="{{$contact_number}}">
                                        </p></div>
                                        <div class="col-12 col-md-6 col-lg-6"><p><strong>Candidate Name:</strong>
                                            {{ Helper::candidate_user_name($jaf_data->candidate_id) }}</p></div>
                                        <div class="col-12 col-md-6 col-lg-6"><p>
                                            <strong>Address:</strong> {{$addr}}
                                            <input type="hidden" name="address" value="{{$addr}}">
                                        </p></div>
                                        <div class="col-12 col-md-6 col-lg-6"><p><strong>Check Name:</strong>
                                            {{ $jaf_data != null ? $jaf_data->service_name . ' - ' . $jaf_data->check_item_number : '--' }}
                                        </p></div>
                                        <div class="col-12 col-md-6 col-lg-6"><p>
                                            <strong>Pincode:</strong> {{$zip}}
                                            <input type="hidden" name="zipcode" value="{{$zip}}">
                                        </p></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-30">
                            <div class="col-md-12">
                                <h3 class="card-title mb-3"> Verification Details </h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-collapsed">
                                        <tbody>
                                            {{-- <tr>
                                                <td></td>
                                                <td></td>
                                            </tr> --}}
                                            <tr>
                                                <td><strong>Nature of Residence: </strong> </td>
                                                <td>
                                                    <select class="form-control" name="nature_of_residence" id="nature_of_residence">
                                                        <option value="">--Select--</option>
                                                        <option value="rented" @if($address_verification!=NULL && $address_verification->nature_of_residence!=NULL && stripos($address_verification->nature_of_residence,'rented')!==false) selected @endif>Rented</option>
                                                        <option value="owned" @if($address_verification!=NULL && $address_verification->nature_of_residence!=NULL && stripos($address_verification->nature_of_residence,'owned')!==false) selected @endif>Owned</option>
                                                        <option value="others" @if($address_verification!=NULL && $address_verification->nature_of_residence!=NULL && !(stripos($address_verification->nature_of_residence,'rented')!==false || stripos($address_verification->nature_of_residence,'owned')!==false)) selected @endif>Others</option>
                                                    </select>
                                                    <input class="form-control my-2 @if($address_verification==NULL || $address_verification->nature_of_residence==NULL || stripos($address_verification->nature_of_residence,'rented')!==false || stripos($address_verification->nature_of_residence,'owned')!==false) d-none @endif" id="other_residence" name="other_residence" type="text" placeholder="" value="{{$address_verification!=NULL ? $address_verification->nature_of_residence : ''}}"> 
                                                    {{-- <input class="form-control" name="nature_of_residence" type="text" placeholder="" value="{{$address_verification!=NULL ? $address_verification->nature_of_residence : ''}}">  --}}
                                                    <p style="margin-bottom: 2px;" class="text-danger error-container error-nature_of_residence" id="error-nature_of_residence"></p>
                                                    <p style="margin-bottom: 2px;" class="text-danger error-container error-other_residence" id="error-other_residence"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Period of Stay: </strong> </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-12 col-md-6">
                                                            <div class="input-group mb-3 flex-sm-screen">
                                                                <input class="form-control commonDatepicker from_date" name="period_stay_from" type="text" placeholder="From" autocomplete="off" value="{{$address_verification!=NULL && $address_verification->period_stay_from!=NULL ? date('d-m-Y',strtotime($address_verification->period_stay_from)) : ''}}">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                </div>
                                                            </div>
                                                            {{-- <input class="form-control commonDatepicker from_date" name="period_stay_from" type="text" placeholder="From" autocomplete="off"> --}}
                                                            <p style="margin-bottom: 2px;" class="text-danger error-container error-period_stay_from" id="error-period_stay_from"></p>
                                                        </div>
                                                        <div class="col-12 col-md-6">
                                                            <div class="input-group mb-3 flex-sm-screen">
                                                                <input class="form-control commonDatepicker to_date" name="period_stay_to" type="text" placeholder="To" autocomplete="off" value="{{$address_verification!=NULL && $address_verification->period_stay_to!=NULL ? date('d-m-Y',strtotime($address_verification->period_stay_to)) : ''}}">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                </div>
                                                            </div>
                                                            <p style="margin-bottom: 2px;" class="text-danger error-container error-period_stay_to" id="error-period_stay_to"></p>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Candidate Name: </strong> </td>
                                                <td>
                                                    <input class="form-control" name="verifier_name" type="text" placeholder="" value="{{$address_verification!=NULL ? $address_verification->verifier_name : ''}}">
                                                    <p style="margin-bottom: 2px;" class="text-danger error-container error-verifier_name" id="error-verifier_name"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Relation with the Candidate: </strong> </td>
                                                <td>
                                                    <input class="form-control"name="relation_with_verifier" type="text" placeholder="" value="{{$address_verification!=NULL ? $address_verification->relation_with_verifier : ''}}">
                                                    <p style="margin-bottom: 2px;" class="text-danger error-container error-relation_with_verifier" id="error-relation_with_verifier"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Type of Address: </strong> </td>
                                                <td>
                                                    <select class="form-control" name="address_type">
                                                        <option value="">--Select--</option>
                                                        <option @if(stripos($address_type,'current')!==false) selected @endif value="current">Current</option>
                                                        <option @if(stripos($address_type,'permanent')!==false) selected @endif value="permanent">Permanent</option>
                                                        <option @if(stripos($address_type,'others')!==false) selected @endif value="others">Others</option>
                                                    </select>
                                                    <p style="margin-bottom: 2px;" class="text-danger error-container error-address_type" id="error-address_type"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Nearest Landmark: </strong> </td>
                                                <td>
                                                    <input class="form-control" name="landmark" type="text" placeholder="" value="{{$address_verification!=NULL ? $address_verification->landmark : ''}}"> 
                                                    <p style="margin-bottom: 2px;" class="text-danger error-container error-landmark" id="error-landmark"></p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @if($address_verification!=NULL)

                            @php
                                $front_door  = Helper::addressVerificationFile($address_verification->jaf_id,'front_door');
                                $profile_photo  = Helper::addressVerificationFile($address_verification->jaf_id,'profile_photo');
                                $id_proof  = Helper::addressVerificationFile($address_verification->jaf_id,'id_proof');
                                $address_proof  = Helper::addressVerificationFile($address_verification->jaf_id,'address_proof');
                                $location  = Helper::addressVerificationFile($address_verification->jaf_id,'location');
                            @endphp

                            @if(count($front_door)>0 || count($profile_photo)>0 || count($address_proof)>0 || count($location)>0 || count($id_proof)>0 || $address_verification->signature!=NULL)
                                <div class="row mt-30">
                                    <div class="col-md-12">
                                        <h3 class="card-title mb-3"> Documents Uploaded </h3>
                                    </div>
                                </div>
                                <div class="row">

                                    @if(count($front_door)>0)
                                        @php
                                            $path = url('/').'/uploads/candidate-front-door/';
                                        @endphp
                                        <div class="col-md-6 front-card">
                                            <div class="data-selfie vh-100">
                                                <p class="label">Selfie with Front Door 
                                                    <br> <small>(Name Plate/House Number in Background)</small>
                                                 </p>
                                                
                                                @if(count($front_door)==1)
                                                    @foreach ($front_door as $item)
                                                        <div class="text-center"><img src="{{ $path.$item->image }}" class="img-responsive"></div>
                                                    @endforeach
                                                @else
                                                    <div class="row">
                                                        @foreach ($front_door as $item)
                                                            <div class="col-6 small-img">
                                                                <img src="{{$path.$item->image}}" class="img-thumbnail img-fluid" style="width:100%;height: 200px;">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    @if(count($profile_photo)>0)
                                        @php
                                            $path = url('/').'/uploads/candidate-selfie/';
                                        @endphp
                                        <div class="col-md-6 profile-card">
                                            <div class="data-selfie vh-100">
                                                <p class="label">Profile Photo 
                                                    <br><small>(Selfie with ID Proof)</small>
                                                </p>
                                            
                                                @if(count($profile_photo)==1)
                                                    @foreach ($profile_photo as $item)
                                                        <div class="text-center"><img src="{{ $path.$item->image }}" class="img-responsive"></div>
                                                    @endforeach
                                                @else
                                                    <div class="row">
                                                        @foreach ($profile_photo as $item)
                                                            <div class="col-6 small-img">
                                                                <img src="{{$path.$item->image}}" class="img-thumbnail img-fluid" style="width:100%;height: 200px;">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if(count($id_proof)>0)
                                        @php
                                            $path = url('/').'/uploads/id-proof/';
                                        @endphp
                                        <div class="col-md-6 id-card">
                                            <div class="data-selfie">
                                                <p class="label">ID Document (Front)
                                                    <br><small>(ID Document  with Photo (1))</small>
                                                </p>
                                                @if(count($id_proof)==1)
                                                    @foreach ($id_proof as $item)
                                                        <div class="text-center"><img src="{{ $path.$item->image }}" class="img-responsive"></div>
                                                    @endforeach
                                                @else
                                                    <div class="row">
                                                        @foreach ($id_proof as $item)
                                                            <div class="col-6 small-img">
                                                                <img src="{{$path.$item->image}}" class="img-thumbnail img-fluid" style="width:100%;height: 200px;">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if(count($address_proof)>0)
                                        @php
                                            $path = url('/').'/uploads/address-proof/';
                                        @endphp
                                        <div class="col-md-6 address-card">
                                            <div class="data-selfie">
                                                <p class="label">ID Document (Backside)
                                                    <br><small>(ID Document Showing Address Photo (2))</small>
                                                </p>
                                                @if(count($address_proof)==1)
                                                    @foreach ($address_proof as $item)
                                                        <div class="text-center"><img src="{{ $path.$item->image }}" class="img-responsive"></div>
                                                    @endforeach
                                                @else
                                                    <div class="row">
                                                        @foreach ($address_proof as $item)
                                                            <div class="col-6 small-img">
                                                                <img src="{{$path.$item->image}}" class="img-thumbnail img-fluid" style="width:100%;height: 200px;">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if(count($location)>0)
                                        @php
                                            $path = url('/').'/uploads/candidate-location/';
                                        @endphp
                                        <div class="col-md-6 pt-2 location-card">
                                            <div class="data-selfie">
                                                <p class="label">Photo of Landmark 
                                                    <br><small>(Famous Place/Hotel/Cinema etc)</small>
                                                </p>
                                                @if(count($location)==1)
                                                    @foreach ($location as $item)
                                                        <div class="text-center"><img src="{{ $path.$item->image }}" class="img-responsive"></div>
                                                    @endforeach
                                                @else
                                                    <div class="row">
                                                        @foreach ($location as $item)
                                                            <div class="col-6 small-img">
                                                                <img src="{{$path.$item->image}}" class="img-thumbnail img-fluid" style="width:100%;height: 200px;">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    @if($address_verification->signature!=NULL)
                                        @php
                                            $path=url('/').'/uploads/candidate-signature/';
                                        @endphp
                                        <div class="col-md-6 signature-card">
                                            <div class="data-selfie">
                                                <p class="label">Signature</p>
                                                <div class="text-center"> <img src="{{ $path.$address_verification->signature }}"></div>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            @endif

                        @endif
                        <div class="row mt-30 mb-50">
                            <div class="col-md-6 offset-3">
                                <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"></p>
                                <button type="submit" class="btn btn-info mb-2 submit width-100">Submit</button> 
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $(document).on('change','.from_date',function() {

            var from = $('.from_date').datepicker('getDate');
            var to_date   = $('.to_date').datepicker('getDate');

            if($('.to_date').val() !=""){
                if (from > to_date) {
                    alert ("Please select appropriate date range!");
                    $('.from_date').val("");
                    $('.to_date').val("");

                }
            }  

        });
        //
        $(document).on('change','.to_date',function() {

            var to_date = $('.to_date').datepicker('getDate');
            var from   = $('.from_date').datepicker('getDate');
            if($('.from_date').val() !=""){
                if (from > to_date) {
                    alert ("Please select appropriate date range!");
                    $('.from_date').val("");
                    $('.to_date').val("");
                
                }
            }

        });

        $(document).on('submit', 'form#address_frm', function (event) {
            event.preventDefault();
            //clearing the error msg
            $('p.error-container').html("");

            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i>  loading...';
            $('.submit').attr('disabled',true);
            $('.form-control').attr('readonly',true);
            $('.form-control').addClass('disabled-link');
            $('.submit').addClass('btn-opacity');
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
                        $('.submit').removeClass('btn-opacity');
                        $('.submit').html('Submit');
                        },2000);
                    if(response.success==true) {          
                    
                        //notify
                        toastr.success("Address Verification Form Submitted Successfully...!");
                        // redirect to google after 5 seconds
                        window.setTimeout(function() {
                            
                            //location.reload();
                            window.location="{{ url('/candidates/jaf-info',['id'=>base64_encode($jaf_data->candidate_id)]) }}";
                        }, 2000);
                    
                    }
                    //show the form validates error
                    if(response.success==false ) {                              
                        for (control in response.errors) {  
                            var error_text = control.replace('.',"_");
                            $('.error-'+error_text).html(response.errors[control]);
                        }
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

        $(document).on('change','#nature_of_residence',function(){

            var _this = $(this);

            var value = _this.val();

            if(value.toLowerCase()=='others'.toLowerCase())
            {
                $('#other_residence').removeClass('d-none');
            }
            else
            {
                $('#other_residence').addClass('d-none');
            }
        });
    });
</script>
@endsection