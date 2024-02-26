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
        height:100%;
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
    .img-main{height:450px;}
    .data-selfie .label{
	height:52px;
    }
    #map{
            height:500px;
        }
    @media print{
        .submit{
            display: none;
        }
        #print{
            display: none;
        }
        .printable, .printable * {
            visibility: visible; 
        }
        .location-card,.signature-card{
            padding-top: 20.5rem !important;
        }
        .data-selfie .text-center{text-align:center!important;}
        .data-selfie img{display:block;height:100%;}
        .data-selfie{height:430px;}
        .small-img img{height:150px!important;width:100%!important;}
        #map{
            height:425px;
        }
    }

    .custom-redzone
    {
        background-color: #ff00008c;
        color: red;
    }

    .custom-bluezone
    {
        color: #175185;
        background-color: #0034738f;
    }
    
    
</style>
 <div class="main-content-wrap sidenav-open d-flex flex-column">

 <div class="main-content">             
    <div class="row">
        <div class="col-sm-11 breadcrum1">
            <ul class="breadcrumb">
            <li><a href="{{ url('/home') }}">Dashboard</a></li>
            <li><a href="{{ url('/candidates') }}">Candidate</a></li>
            <li><a href="{{ url('/candidates/jaf-info',['id'=>base64_encode($address_verification->candidate_id)]) }}">JAF</a></li>
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
    <div class="row print_this">
        <div class="card text-left">
            <div class="card-body">
                <form method="post" action="{{url('/candidates/digital_address_verification',['id'=>base64_encode($address_verification->jaf_id)])}}" id="qc_frm">
                    @csrf
                    <input type="hidden" name="candidate_id" value="{{base64_encode($address_verification->candidate_id)}}" id="candidate_id">
                    <div class="row">
                        @php
                            $jaf_data = Helper::get_jaf_data($address_verification->jaf_id);
                            $addr = '';
                            if($address_verification->full_address!=NULL)
                            {
                                $addr = $address_verification->full_address;
                            }
                            else {
                                $form_data = $jaf_data->form_data;

                                if($form_data!=null)
                                {
                                    $input_item_data_array =  json_decode($form_data, true);

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
                            }
                        @endphp
                        <div class="col-9 QC-data">
             
                            <h3 class="card-title mb-3"> Digital Address Verification </h3>
                        </div>
                        @if ($verification_decision!=NULL)
                            <div class="col-3 QC-data text-right mb-2">
                                @if($verification_decision->qc_decision==0 || $verification_decision->qc_decision==1)
                                    {{-- <button type="button" class="btn btn-outline-info mb-2" id="print"><i class="fas fa-print"></i> Print</button> --}}
                                    {{-- @if ($verification_decision->is_send_report!=1) --}}
                                        <a class="btn btn-outline-info addToReport" href="{{url('/candidates/digital_address_add_report',['id'=>base64_encode($address_verification->jaf_id)])}}" title="Add to Report"><i class="fas fa-plus"></i> Add to Report </a>
                                    {{-- @endif --}}
                                    <a class="btn btn-outline-info" href="{{url('/candidates/address_verification_report',['id'=>base64_encode($address_verification->jaf_id)])}}" title="Download Report"><i class="fas fa-download"></i> Download Report </a>
                                @else
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
                                    <div class="col-6">
                                        <p><strong>Reference Number:</strong> {{ Helper::candidate_reference_id($address_verification->candidate_id) }}</p>
                                        <p><strong>Client Name:</strong> {{ Helper::company_name($address_verification->business_id) }}</p>
                                        <p><strong>Candidate Name :</strong> {{ Helper::candidate_user_name($address_verification->candidate_id) }}</p>
                                        <p><strong>Check Name :</strong> {{ $jaf_data!=NULL ? $jaf_data->service_name.' - '.$jaf_data->check_item_number : '--' }}</p>
                                        <p><strong>Submitted At :</strong> {{ date('d-M-Y h:i A',strtotime($address_verification->created_at)) }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p><strong>Email :</strong> {{ $address_verification->email}}</p>
                                        <p><strong>Contact Number :</strong> {{ $address_verification->phone}}</p>
                                        <div class="row">
                                            <div class="col-8">
                                                <p><strong>Address :</strong> {{$addr}}</p>
                                            </div>
                                            <div class="col-4">
                                                <p><strong>Pincode :</strong> {{ $address_verification->zipcode}}</p>
                                            </div>
                                            {{-- <div class="col-4">
                                                <p><strong>Landmark :</strong> {{ $address_verification->nearest_location!=NULL && $address_verification->nearest_location!='' ? $address_verification->nearest_location : 'N/A'}}</p>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-30">
                        <div class="col-md-12">
                            <h3 class="card-title mb-3"> Verification Details </h3>
                            <table class="table table-bordered table-collapsed">
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td>Correct</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nature of Residence:</strong> {{$address_verification->nature_of_residence!=NULL && $address_verification->nature_of_residence!='' ? ucwords($address_verification->nature_of_residence) : '--'}}</td>
                                        @if($verification_decision!=NULL && $verification_decision->ownership!=NULL)
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="nature_of_residence" id="nature_of_residence_1" @if($verification_decision->ownership=='yes') checked @endif value="yes">
                                                    <label class="form-check-label" for="nature_of_residence_1">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="nature_of_residence" id="nature_of_residence_2" @if($verification_decision->ownership=='no') checked @endif value="no">
                                                    <label class="form-check-label" for="nature_of_residence_2">No</label>
                                                </div>
                                                <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-nature_of_residence" id="error-nature_of_residence"></p>
                                            </td>
                                        @else
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="nature_of_residence" id="nature_of_residence_1" value="yes">
                                                    <label class="form-check-label" for="nature_of_residence_1">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="nature_of_residence" id="nature_of_residence_2" value="no">
                                                    <label class="form-check-label" for="nature_of_residence_2">No</label>
                                                </div>
                                                <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-nature_of_residence" id="error-nature_of_residence"></p>
                                            </td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td><strong>Period of stay:</strong> {{$address_verification->period_stay_from!=NULL || $address_verification->period_stay_from!='' ? date('Y-m-d',strtotime($address_verification->period_stay_from)) : '--'}} to {{$address_verification->period_stay_from!=NULL || $address_verification->period_stay_to!='' ? date('Y-m-d',strtotime($address_verification->period_stay_to)) : '--'}}</td>
                                        @if($verification_decision!=NULL && $verification_decision->stay!=NULL)
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="stay" id="stay-1" @if($verification_decision->stay=='yes') checked @endif value="yes">
                                                    <label class="form-check-label" for="stay-1">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="stay" id="stay-2" @if($verification_decision->stay=='no') checked @endif value="no">
                                                    <label class="form-check-label" for="stay-2">No</label>
                                                </div>
                                                <p style="margin-top:2px; margin-bottom: 2px;" class="text-danger error-container error-stay" id="error-stay"></p>
                                            </td>
                                        @else
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="stay" id="stay-1" value="yes">
                                                    <label class="form-check-label" for="stay-1">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="stay" id="stay-2" value="no">
                                                    <label class="form-check-label" for="stay-2">No</label>
                                                </div>
                                                <p style="margin-top:2px; margin-bottom: 2px;" class="text-danger error-container error-stay" id="error-stay"></p>
                                            </td> 
                                        @endif
                                    </tr>
                                    <tr>
                                        <td><strong>Candidate Name:</strong> {{ $address_verification->verifier_name!=NULL ? $address_verification->verifier_name : '--'}}</td>
                                        @if($verification_decision!=NULL && $verification_decision->verifier_name!=NULL)
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="verifier-name" id="verifier-name-1" @if($verification_decision->verifier_name=='yes') checked @endif value="yes">
                                                    <label class="form-check-label" for="verifier-name-1">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="verifier-name" id="verifier-name-2" @if($verification_decision->verifier_name=='no') checked @endif value="no">
                                                    <label class="form-check-label" for="verifier-name-2">No</label>
                                                </div>
                                                <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-verifier-name" id="error-verifier-name"></p>
                                            </td>
                                        @else
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="verifier-name" id="verifier-name-1" value="yes">
                                                    <label class="form-check-label" for="verifier-name-1">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="verifier-name" id="verifier-name-2" value="no">
                                                    <label class="form-check-label" for="verifier-name-2">No</label>
                                                </div>
                                                <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-verifier-name" id="error-verifier-name"></p>
                                            </td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td><strong>Relation with the Candidate:</strong> {{ $address_verification->relation_with_verifier!=NULL ? $address_verification->relation_with_verifier : '--'}}</td>
                                        @if($verification_decision!=NULL && $verification_decision->relation_with_verifier!=NULL)
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="relation-with-verifier" id="relation-with-verifier-1" @if($verification_decision->relation_with_verifier=='yes') checked @endif value="yes">
                                                    <label class="form-check-label" for="relation-with-verifier-1">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="relation-with-verifier" id="relation-with-verifier-2" @if($verification_decision->relation_with_verifier=='no') checked @endif value="no">
                                                    <label class="form-check-label" for="relation-with-verifier-2">No</label>
                                                </div>
                                                <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-relation-with-verifier" id="error-relation-with-verifier"></p>
                                            </td>
                                        @else
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="relation-with-verifier" id="relation-with-verifier-1" value="yes">
                                                    <label class="form-check-label" for="relation-with-verifier-1">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="relation-with-verifier" id="relation-with-verifier-2" value="no">
                                                    <label class="form-check-label" for="relation-with-verifier-2">No</label>
                                                </div>
                                                <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-relation-with-verifier" id="error-relation-with-verifier"></p>
                                            </td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td><strong>Type of address:</strong> {{$address_verification->address_type!=NULL && $address_verification->address_type!='' ? ucwords($address_verification->address_type) : '--'}}</td>
                                        @if($verification_decision!=NULL && $verification_decision->address_type!=NULL)
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="address-type" id="address-type-1" @if($verification_decision->address_type=='yes') checked @endif value="yes">
                                                    <label class="form-check-label" for="address-type-1">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="address-type" id="address-type-2" @if($verification_decision->address_type=='no') checked @endif value="no">
                                                    <label class="form-check-label" for="address-type-2">No</label>
                                                </div>
                                                <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-address-type" id="error-address-type"></p>
                                            </td>
                                        @else
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="address-type" id="address-type-1" value="yes">
                                                    <label class="form-check-label" for="address-type-1">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="address-type" id="address-type-2" value="no">
                                                    <label class="form-check-label" for="address-type-2">No</label>
                                                </div>
                                                <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-address-type" id="error-address-type"></p>
                                            </td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td><strong>Nearest Landmark: </strong> {{ $address_verification->landmark!=NULL ? $address_verification->landmark : '--'}} </td>
                                        @if($verification_decision!=NULL && $verification_decision->nearest_landmark!=NULL)
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="nearest-landmark" id="nearest-landmark-1" @if($verification_decision->nearest_landmark=='yes') checked @endif value="yes">
                                                    <label class="form-check-label" for="nearest-landmark-1">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="nearest-landmark" id="nearest-landmark-2" @if($verification_decision->nearest_landmark=='no') checked @endif value="no">
                                                    <label class="form-check-label" for="nearest-landmark-2">No</label>
                                                </div>
                                                <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-nearest-landmark" id="error-nearest-landmark"></p>
                                            </td>
                                        @else
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="nearest-landmark" id="nearest-landmark-1" value="yes">
                                                    <label class="form-check-label" for="nearest-landmark-1">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="nearest-landmark" id="nearest-landmark-2" value="no">
                                                    <label class="form-check-label" for="nearest-landmark-2">No</label>
                                                </div>
                                                <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-nearest-landmark" id="error-nearest-landmark"></p>
                                            </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-30">
                        <div class="col-md-12">
                            <h3>Documents Uploaded :</h3>
                        </div>
                    </div>

                    <div class="row mt-30">
                        <div class="col-md-6 front-card">
                            <div class="data-selfie vh-100">
                                @php
                                    $front_door  = Helper::addressVerificationFile($address_verification->jaf_id,'front_door');
                                @endphp
                                <p class="label">Selfie with Front Door
                                    <br> <small>(Name Plate/House Number in Background)</small>
                                 </p>
                                @if(count($front_door)>0)
                                    @php
                                        $path = url('/').'/uploads/candidate-front-door/';
                                    @endphp
                                    @if(count($front_door)==1)
                                        @foreach ($front_door as $item)
                                            <div class="text-center img-main"><img src="{{ $path.$item->image }} " class="img-responsive"></div>
                                            <div class="img-data">
                                                <p><strong>Timestamp :</strong>{{date('Y-m-d H:i:s',strtotime($item->created_at))}}</p>
                                                <p><strong>Location :</strong>{{$item->latitude!=NULL ? $item->latitude : '--'}} , {{$item->longitude!=NULL ? $item->longitude : '--'}}</p>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="row">
                                            @foreach ($front_door as $item)
                                                <div class="col-6 small-img">
                                                    <img src="{{$path.$item->image}} " class="img-thumbnail img-fluid" style="width:100%;height: 200px;">
                                                    <div class="img-data">
                                                        <p><strong>Timestamp :</strong>{{date('Y-m-d H:i:s',strtotime($item->created_at))}}</p>
                                                        <p><strong>Location :</strong>{{$item->latitude!=NULL ? $item->latitude : '--'}} , {{$item->longitude!=NULL ? $item->longitude : '--'}}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center img-main"><img src="{{ asset('admin/images/profile-default-avtar.jpg') }} "></div>
                                @endif
                                {{-- <div class="img-data">
                                    <p class="text-dark"><strong>Timestamp :</strong>2020-07-09 16:00:05</p>
                                    <p><strong>Location :</strong>28.00778866 , 25.00886766</p>
                                </div> --}}
                            </div>
                            @if($verification_decision!=NULL && $verification_decision->front!=NULL)
                                <div class="checked-docs">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="front-correct" id="front-correct-1" @if($verification_decision->front=='yes') checked @endif value="yes">
                                        <label class="form-check-label" for="front-correct-1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="front-correct" id="front-correct-2" @if($verification_decision->front=='no') checked @endif value="no">
                                        <label class="form-check-label" for="front-correct-2">No</label>
                                    </div>
                                    <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-front-correct" id="error-front-correct"></p>
                                </div>
                            @else
                                <div class="checked-docs">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="front-correct" id="front-correct-1" value="yes">
                                        <label class="form-check-label" for="front-correct-1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="front-correct" id="front-correct-2" value="no">
                                        <label class="form-check-label" for="front-correct-2">No</label>
                                    </div>
                                    <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-front-correct" id="error-front-correct"></p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 profile-card">
                            <div class="data-selfie vh-100">
                                @php
                                    $profile_photo  = Helper::addressVerificationFile($address_verification->jaf_id,'profile_photo');
                                @endphp
                                <p class="label">Profile Photo 
                                    <br><small>(Selfie with ID Proof)</small>
                                </p>
                                @if(count($profile_photo)>0)
                                    @php
                                        $path = url('/').'/uploads/candidate-selfie/';
                                    @endphp
                                    @if(count($profile_photo)==1)
                                        @foreach ($profile_photo as $item)
                                            <div class="text-center img-main"><img src="{{ $path.$item->image }} " class="img-responsive"></div>
                                            <div class="img-data">
                                                <p><strong>Timestamp :</strong>{{date('Y-m-d H:i:s',strtotime($item->created_at))}}</p>
                                                <p><strong>Location :</strong>{{$item->latitude!=NULL ? $item->latitude : '--'}} , {{$item->longitude!=NULL ? $item->longitude : '--'}}</p>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="row">
                                            @foreach ($profile_photo as $item)
                                                <div class="col-6 small-img">
                                                    <img src="{{$path.$item->image}} " class="img-thumbnail img-fluid" style="width:100%;height: 200px;">
                                                    <div class="img-data">
                                                        <p><strong>Timestamp :</strong>{{date('Y-m-d H:i:s',strtotime($item->created_at))}}</p>
                                                        <p><strong>Location :</strong>{{$item->latitude!=NULL ? $item->latitude : '--'}} , {{$item->longitude!=NULL ? $item->longitude : '--'}}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center img-main"><img src="{{ asset('admin/images/profile-default-avtar.jpg') }} "></div>
                                @endif
                                {{-- <div class="img-data">
                                    <p class="text-dark"><strong>Timestamp :</strong>2020-07-09 16:00:05</p>
                                    <p><strong>Location :</strong>28.00778866 , 25.00886766</p>
                                </div> --}}
                            </div>
                            @if($verification_decision!=NULL && $verification_decision->profile!=NULL)
                                <div class="checked-docs">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="profile-correct" id="profile-correct-1" @if($verification_decision->profile=='yes') checked @endif value="yes">
                                        <label class="form-check-label" for="profile-correct-1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="profile-correct" id="profile-correct-2" @if($verification_decision->profile=='no') checked @endif value="no">
                                        <label class="form-check-label" for="profile-correct-2">No</label>
                                    </div>
                                    <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-profile-correct" id="error-profile-correct"></p>
                                </div>
                            @else
                                <div class="checked-docs">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="profile-correct" id="profile-correct-1" value="yes">
                                        <label class="form-check-label" for="profile-correct-1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="profile-correct" id="profile-correct-2" value="no">
                                        <label class="form-check-label" for="profile-correct-2">No</label>
                                    </div>
                                    <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-profile-correct" id="error-profile-correct"></p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 id-card pt-2">
                            <div class="data-selfie">
                                <p class="label">ID Document (Front)
                                    <br><small>(ID Document with Photo (1))</small>
                                </p>
                                @php
                                    $id_proof  = Helper::addressVerificationFile($address_verification->jaf_id,'id_proof');
                                @endphp

                                    @if(count($id_proof)>0)
                                        @php
                                            $path = url('/').'/uploads/id-proof/';
                                        @endphp
                                        @if(count($id_proof)==1)
                                            @foreach ($id_proof as $item)
                                                <div class="text-center img-main"><img src="{{ $path.$item->image }}" class="img-responsive"></div>
                                                <div class="img-data">
                                                    <p><strong>Timestamp :</strong>{{date('Y-m-d H:i:s',strtotime($item->created_at))}}</p>
                                                    <p><strong>Location :</strong>{{$item->latitude!=NULL ? $item->latitude : '--'}} , {{$item->longitude!=NULL ? $item->longitude : '--'}}</p>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row">
                                                @foreach ($id_proof as $item)
                                                    <div class="col-6 small-img">
                                                        <img src="{{$path.$item->image}}" class="img-thumbnail img-fluid" style="width:100%;height: 200px;">
                                                        <div class="img-data">
                                                            <p><strong>Timestamp :</strong>{{date('Y-m-d H:i:s',strtotime($item->created_at))}}</p>
                                                            <p><strong>Location :</strong>{{$item->latitude!=NULL ? $item->latitude : '--'}} , {{$item->longitude!=NULL ? $item->longitude : '--'}}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-center img-main"><img src="{{ asset('admin/images/profile-default-avtar.jpg') }} "></div>
                                    @endif
                                {{-- <div class="img-data">
                                    <p><strong>Timestamp :</strong>2020-07-09 16:00:05</p>
                                    <p><strong>Location :</strong>28.00778866 , 25.00886766</p>
                                </div> --}}
                                
                            </div>
                            @if($verification_decision!=NULL && $verification_decision->id_proof!=NULL)
                                <div class="checked-docs">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="id-correct" id="id-correct-1" @if($verification_decision->id_proof=='yes') checked @endif value="yes">
                                        <label class="form-check-label" for="id-correct-1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="id-correct" id="id-correct-2" @if($verification_decision->id_proof=='no') checked @endif value="no">
                                        <label class="form-check-label" for="id-correct-2">No</label>
                                    </div>
                                    <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-id-correct" id="error-id-correct"></p>
                                </div>
                            @else
                                <div class="checked-docs">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="id-correct" id="id-correct-1" value="yes">
                                        <label class="form-check-label" for="id-correct-1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="id-correct" id="id-correct-2" value="no">
                                        <label class="form-check-label" for="id-correct-2">No</label>
                                    </div>
                                    <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-id-correct" id="error-id-correct"></p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 address-card pt-2">
                            <div class="data-selfie">
                                <p class="label">ID Document (Backside) 
                                    <br><small>(ID Document Showing Address Photo (2))</small>
                                </p>
                                @php
                                    $address_proof  = Helper::addressVerificationFile($address_verification->jaf_id,'address_proof');
                                @endphp
                                @if(count($address_proof)>0)
                                    @php
                                        $path = url('/').'/uploads/address-proof/';
                                    @endphp
                                    @if(count($address_proof)==1)
                                        @foreach ($address_proof as $item)
                                            <div class="text-center img-main"><img src="{{ $path.$item->image }}" class="img-responsive"></div>
                                            <div class="img-data">
                                                <p><strong>Timestamp :</strong>{{date('Y-m-d H:i:s',strtotime($item->created_at))}}</p>
                                                <p><strong>Location :</strong>{{$item->latitude!=NULL ? $item->latitude : '--'}} , {{$item->longitude!=NULL ? $item->longitude : '--'}}</p>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="row">
                                            @foreach ($address_proof as $item)
                                                <div class="col-6 small-img">
                                                    <img src="{{$path.$item->image}}" class="img-thumbnail img-fluid" style="width:100%;height: 200px;">
                                                    <div class="img-data">
                                                        <p><strong>Timestamp :</strong>{{date('Y-m-d H:i:s',strtotime($item->created_at))}}</p>
                                                        <p><strong>Location :</strong>{{$item->latitude!=NULL ? $item->latitude : '--'}} , {{$item->longitude!=NULL ? $item->longitude : '--'}}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center img-main"><img src="{{ asset('admin/images/profile-default-avtar.jpg') }} "></div>
                                @endif
                                {{-- <div class="img-data">
                                    <p><strong>Timestamp :</strong>2020-07-09 16:00:05</p>
                                    <p><strong>Location :</strong>28.00778866 , 25.00886766</p>
                                </div> --}}
                                
                            </div>
                            @if($verification_decision!=NULL && $verification_decision->address_proof!=NULL)
                                <div class="checked-docs">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="address-correct" id="address-correct-1" @if($verification_decision->address_proof=='yes') checked @endif value="yes">
                                        <label class="form-check-label" for="address-correct-1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="address-correct" id="address-correct-2" @if($verification_decision->address_proof=='no') checked @endif value="no">
                                        <label class="form-check-label" for="address-correct-2">No</label>
                                    </div>
                                    <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-address-correct" id="error-address-correct"></p>
                                </div>
                            @else
                                <div class="checked-docs">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="address-correct" id="address-correct-1" value="yes">
                                        <label class="form-check-label" for="address-correct-1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="address-correct" id="address-correct-2" value="no">
                                        <label class="form-check-label" for="address-correct-2">No</label>
                                    </div>
                                    <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-address-correct" id="error-address-correct"></p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 pt-2 location-card">
                            <div class="data-selfie">
                                <p class="label">Photo of Landmark 
                                    <br><small>(Famous Place/Hotel/Cinema etc)</small>
                                </p>
                                @php
                                    $location  = Helper::addressVerificationFile($address_verification->jaf_id,'location');
                                @endphp

                                    @if(count($location)>0)
                                        @php
                                            $path = url('/').'/uploads/candidate-location/';
                                        @endphp
                                        @if(count($location)==1)
                                            @foreach ($location as $item)
                                                <div class="text-center img-main"><img src="{{ $path.$item->image }} " class="img-responsive"></div>
                                                <div class="img-data">
                                                    <p><strong>Timestamp :</strong>{{date('Y-m-d H:i:s',strtotime($item->created_at))}}</p>
                                                    <p><strong>Location :</strong>{{$item->latitude!=NULL ? $item->latitude : '--'}} , {{$item->longitude!=NULL ? $item->longitude : '--'}}</p>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row">
                                                @foreach ($location as $item)
                                                    <div class="col-6 small-img">
                                                        <img src="{{$path.$item->image}} " class="img-thumbnail img-fluid" style="width:100%;height: 200px;">
                                                        <div class="img-data">
                                                            <p><strong>Timestamp :</strong>{{date('Y-m-d H:i:s',strtotime($item->created_at))}}</p>
                                                            <p><strong>Location :</strong>{{$item->latitude!=NULL ? $item->latitude : '--'}} , {{$item->longitude!=NULL ? $item->longitude : '--'}}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-center img-main"><img src="{{ asset('admin/images/profile-default-avtar.jpg') }} "></div>
                                    @endif
                                {{-- <div class="img-data">
                                    <p><strong>Timestamp :</strong>2020-07-09 16:00:05</p>
                                    <p><strong>Location :</strong>28.00778866 , 25.00886766</p>
                                </div> --}}
                                
                            </div>
                            @if($verification_decision!=NULL && $verification_decision->location!=NULL)
                                <div class="checked-docs">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="location-correct" id="location-correct-1" @if($verification_decision->location=='yes') checked @endif value="yes">
                                        <label class="form-check-label" for="location-correct-1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="location-correct" id="location-correct-2" @if($verification_decision->location=='no') checked @endif value="no">
                                        <label class="form-check-label" for="location-correct-2">No</label>
                                    </div>
                                    <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-location-correct" id="error-location-correct"></p>
                                </div>
                            @else
                                <div class="checked-docs">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="location-correct" id="location-correct-1" value="yes">
                                        <label class="form-check-label" for="location-correct-1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="location-correct" id="location-correct-2" value="no">
                                        <label class="form-check-label" for="location-correct-2">No</label>
                                    </div>
                                    <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-location-correct" id="error-location-correct"></p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 signature-card pt-2">
                            <div class="data-selfie">
                                <p class="label">Signature</p>
                                @if($address_verification->signature!=NULL)
                                    @php
                                        $path=url('/').'/uploads/candidate-signature/';
                                    @endphp
                                   <div class="text-center img-main"> <img src="{{ $path.$address_verification->signature }}"></div>
                                @else
                                    <div class="text-center img-main"><img src="{{ asset('admin/images/profile-default-avtar.jpg') }}"></div>
                                @endif
                                <div class="img-data">
                                    <p><strong>Timestamp :</strong>{{date('Y-m-d H:i:s',strtotime($address_verification->created_at))}}</p>
                                    <p><strong>Location :</strong>{{$address_verification->signature_latitude!=NULL ? $address_verification->signature_latitude : $address_verification->latitude}} , {{$address_verification->signature_longitude!=NULL ? $address_verification->signature_longitude : $address_verification->longitude}}</p>
                                </div>
                            </div>
                            @if($verification_decision!=NULL && $verification_decision->signature!=NULL)
                                <div class="checked-docs">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="signature-correct" id="signature-correct-1" @if($verification_decision->signature=='yes') checked @endif value="yes">
                                        <label class="form-check-label" for="signature-correct-1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="signature-correct" id="signature-correct-2" @if($verification_decision->signature=='no') checked @endif value="no">
                                        <label class="form-check-label" for="signature-correct-2">No</label>
                                    </div>
                                    <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-signature-correct" id="error-signature-correct"></p>
                                </div>
                            @else
                                <div class="checked-docs">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="signature-correct" id="signature-correct-1" value="yes">
                                        <label class="form-check-label" for="signature-correct-1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="signature-correct" id="signature-correct-2" value="no">
                                        <label class="form-check-label" for="signature-correct-2">No</label>
                                    </div>
                                    <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-signature-correct" id="error-signature-correct"></p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-30 ">
                        <div class="col-md-12">
                            <h3>Address shown on the map (Radius: 500m)</h3>
                            <table class="table table-bordered table-collapsed">
                                <tbody>
                                    <tr style="background:#ececec;">
                                        <th>Description</th>
                                        <th>Source</th>
                                        <th>Location Resolution Logic</th>
                                        <th>Distance</th>
                                        <th>Proximity Status</th>
                                        <th>Legend</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                    <tr>
                                        <td>
                                            {{$addr}}
                                            <input type="hidden" id="full_address" value="{{$addr}}">
                                        </td>
                                        <td>Input Address</td>
                                        <td>Google Location Api</td>
                                        <td class="text-center" rowspan="2">
                                            <span class="distance">0 Km</span>
                                            <input type="hidden" name="distance" value="0">
                                        </td>
                                        <td class="text-center legend" rowspan="2"><span class="zone redzone">.</span></td>
                                        <td class="text-center"><span class="zone custom-redzone">.</span></td>
                                        {{-- <td></td> --}}
                                    </tr>
                                    <tr>
                                        <td>
                                            @php
                                                $front_door_first  = Helper::addressVerificationFileFirst($address_verification->jaf_id,'front_door');
                                            @endphp
                                            {{-- <span class="geo_lat_lng">{{$address_verification->geo_latitude!=NULL && $address_verification->geo_longitude!=NULL ? $address_verification->geo_latitude.', '.$address_verification->geo_longitude : '--'}} </span>
                                            <input type="hidden" id="lat" value="{{$address_verification->geo_latitude}}">
                                            <input type="hidden" id="long" value="{{$address_verification->geo_longitude}}"> --}}
                                            <span class="geo_lat_lng">
                                                {{$front_door_first!=NULL && $front_door_first->latitude!=NULL && $front_door_first->longitude!=NULL ? $front_door_first->latitude.', '.$front_door_first->longitude : '--'}} 
                                            </span>
                                            <input type="hidden" id="lat" value="{{$front_door_first!=NULL ? $front_door_first->latitude: ''}}">
                                            <input type="hidden" id="long" value="{{$front_door_first!=NULL ? $front_door_first->longitude : ''}}">
                                            <input type="hidden" id="lat_lng_address" name="lat_lng_address">
                                        </td>
                                        <td>GPS</td>
                                        {{-- <td>0.00 km.</td> --}}
                                        <td>Device Location Logic</td>
                                        <td class="text-center"><span class="zone custom-bluezone">.</span></td>
                                        {{-- <td><span class="zone greenzone">.</span></td> --}}
                                        {{-- <td></td> --}}
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                        {{-- <form class="form-inline"> --}}
                                            <p>Map QC</p>
                                            {{-- <div class="form-group mx-sm-3 mb-2">
                                                <label for="address1" class="sr-only">Password</label>
                                                <input type="text" class="form-control" id="address1" placeholder="Add Document Address" style="width:235px;">
                                            </div>
                                            <button type="submit" class="btn btn-info mb-2">Show On Map</button> --}}
                                        {{-- </form>  --}}
                                        </td>
                                        <td colspan="2">
                                            @if($verification_decision!=NULL && $verification_decision->map_qc!=NULL)
                                                <div class="">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="maper" id="maper-1" @if($verification_decision->map_qc=='yes') checked @endif value="yes">
                                                        <label class="form-check-label" for="maper-1">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="maper" id="maper-2" @if($verification_decision->map_qc=='no') checked @endif value="no">
                                                        <label class="form-check-label" for="maper-2">No</label>
                                                    </div>
                                                    <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-maper" id="error-maper"></p>
                                                </div>
                                            @else
                                                <div class="">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="maper" id="maper-1" value="yes">
                                                        <label class="form-check-label" for="maper-1">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="maper" id="maper-2" value="no">
                                                        <label class="form-check-label" for="maper-2">No</label>
                                                    </div>
                                                    <p style="margin-top:2px;margin-bottom: 2px;" class="text-danger error-container error-maper" id="error-maper"></p>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="maps print_data">
                                <div id="map">
                                
                                </div>
                            </div>
                            <div id="show_img">
                                {{-- <img src="" name="map_image" id="map_image"/> --}}
                                <input type="hidden" name="map_image" />
                            </div>
                        </div>
                    </div>

                    <div class="row mt-30 mb-50">
                        <div class="col-md-6 offset-3">
                            <h3>QC Decision</h3>
                            <div class="form-group">
                                {{-- <label for="sel">Example select</label> --}}
                                <select class="form-control qc_decision" name="qc_decision" id="sel">
                                    <option @if($verification_decision!=NULL && $verification_decision->qc_decision==1) selected @endif value="1">Pass</option>
                                    <option @if($verification_decision!=NULL && $verification_decision->qc_decision==0) selected @endif value="0">Fail</option>
                                    <option @if($verification_decision!=NULL && $verification_decision->qc_decision==2) selected @endif value="2">Fail Resend (SMS)</option>
                                    <option @if($verification_decision!=NULL && $verification_decision->qc_decision==3) selected @endif value="3">Fail Resend (Mail)</option>
                                    <option @if($verification_decision!=NULL && $verification_decision->qc_decision==4) selected @endif value="4">Fail Resend (SMS & Mail)</option>
                                </select>
                                <p style="margin-bottom: 2px;" class="text-danger error-container error-qc_decision" id="error-qc_decision"></p>
                            </div>
                            <div class="form-group">
                                <label for="comment">Comments</label>
                                <textarea class="form-control" name="comment" rows="4">{{$verification_decision!=NULL ? $verification_decision->comment : NULL}}</textarea>
                                <p style="margin-bottom: 2px;" class="text-danger error-container error-comment" id="error-comment"></p>
                            </div>
                            <div class="form-group email-div d-none">
                               
                            </div>
                            <button type="submit" class="btn btn-info mb-2 submit width-100">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div><!-- Footer Start -->
<div class="flex-grow-1"></div>
           
</div>
    

<script type="text/javascript">

var latlng;
var marker;
function initialize () {

    var geocoder = new google.maps.Geocoder();
    var address = "";

    address=$('#full_address').val();
    
    var input_lat = "";
    var input_long = "";

    var lat = "28.589029";

    var long = "77.301613";

    geocoder.geocode( { 'address': address}, function(results, status) {
    
      if (status == google.maps.GeocoderStatus.OK) {
        input_lat = results[0].geometry.location.lat();
        input_long = results[0].geometry.location.lng();
       // alert(input_lat);
      }
       
    
    
        lat = $('#lat').val();
     long = $('#long').val();

     //alert(lat);

        var mapOptions = { 
            zoom: 15, 
            center: new google.maps.LatLng(lat,long), 
            mapTypeId: google.maps.MapTypeId.TERRAIN
        };

        var map = new google.maps.Map(document.getElementById("map"),mapOptions  );
        // console.log("alex");

        var data = [
                    {"Latitude":input_lat,"Longitude":input_long},
                    {"Latitude":lat,"Longitude":long}
                ];
        //console.log(latlngArray);
        var populationOptions = {
            strokeColor: '#FF0000',
            strokeOpacity: 0.2,
            strokeWeight: 6,
            fillColor: '#FF0000',
            fillOpacity: 0.35,
            map: map,
            center: latlng,
            radius: 500,
        } 

        var populationOptions1 = {
            strokeColor: '#003473',
            strokeOpacity: 0.2,
            strokeWeight: 6,
            fillColor: '#003473',
            fillOpacity: 0.35,
            map: map,
            center: latlng,
            radius: 500,
        } 

        for (var i = 0; i < data.length; i++) {
            if(i==1)
            {
                populationOptions1.center = new google.maps.LatLng(data[i].Latitude,data[i].Longitude);
                cityCircle = new google.maps.Circle(populationOptions1);  
            }
            else
            {
                populationOptions.center = new google.maps.LatLng(data[i].Latitude,data[i].Longitude);
                cityCircle = new google.maps.Circle(populationOptions);  
            }

        }

        // var rad = function(x) {
        //     return x * Math.PI / 180;
        // };

        // var getDistance = function(lat, long,input_lat,input_long) {
        //     var R = 500; // Earth’s mean radius in meter
        //     var dLat = rad(lat - input_lat);
        //     var dLong = rad(long - input_long);
        //     var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        //         Math.cos(rad(lat)) * Math.cos(rad(input_lat)) *
        //         Math.sin(dLong / 2) * Math.sin(dLong / 2);
        //     var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        //     var d = R * c;
        //     return d; // returns the distance in meter
        // };

        // console.log(getDistance(lat,long,input_lat,input_long));

        var distance = google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(input_lat, input_long), new google.maps.LatLng(lat, long));
        // console.log(distance);
        if(distance>1000)
        {
            $('.legend').html('<span class="zone redzone print_data">.</span>');
        }
        else
        {
            $('.legend').html('<span class="zone greenzone print_data">.</span>');
        }

        var zeroPad = function(num, pad){
            var pd = Math.pow(10,pad);
            return Math.floor(num*pd)/pd; 
        }

        $('.distance').html(zeroPad(distance/1000,3)+' Km');
        $("input[name=distance]").val(zeroPad(distance/1000,3));
        //console.log(zeroPad(distance/1000,3));
       // console.log(distance);

    });

    // Getting Address from Lat & Long
    lat = $('#lat').val();
    long = $('#long').val();

    var latlng = new google.maps.LatLng(lat, long);
    // This is making the Geocode request
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'latLng': latlng },  (results, status) =>{
        if (status !== google.maps.GeocoderStatus.OK) {
            alert(status);
        }
        // This is checking to see if the Geoeode Status is OK before proceeding
        if (status == google.maps.GeocoderStatus.OK) {
            //console.log(results[0].formatted_address);
            var address = (results[0].formatted_address);
            $('.geo_lat_lng').html(`${address}<br>(${lat},${long})`);
            $('input[name=lat_lng_address]').val(address);
        }
    });

  
 
}

function loadScript() {
  var script = document.createElement('script');
  script.type = 'text/javascript';
  script.src = 'https://maps.googleapis.com/maps/api/js?key={{env("GOOGLE_MAP_KEY")}}'+'&sensor=false&'+'callback=initialize'+'&libraries=geometry';
  document.body.appendChild(script);

}

window.onload = loadScript;

function autoSave()
{
    $('.gmnoprint').attr('data-html2canvas-ignore',true);
    $('.gm-fullscreen-control').attr('data-html2canvas-ignore',true);
    scrollPos = document.body.scrollTop;
    html2canvas($(".maps"), {
        useCORS: true,
        onrendered: function(canvas) {
            //console.log(canvas.toDataURL("image/png"));
            //$('#img_val').val(canvas.toDataURL("image/png"));
            //$("#show_img").append(canvas);
            //$('#map_image').attr('src',canvas.toDataURL("image/png"));

            $('input[name=map_image]').val(canvas.toDataURL("image/png"));
            window.scrollTo(0, scrollPos);
        }
        
    });  
    //console.log($('input[name=map_image]').val());

    window.setTimeout(()=>{
        var map_image = $('input[name=map_image]').val();
        var distance = $('input[name=distance]').val();
        var lat_lng_address = $('input[name=lat_lng_address]').val();

        var fd = new FormData();
        fd.append('map_image',map_image);
        fd.append('distance',distance);
        fd.append('_token', '{{csrf_token()}}');
        fd.append('type','formtype');
        fd.append('map_address',lat_lng_address);

        $.ajax({
            type: 'POST',
            url: "{{url('/candidates/digital_address_verification',['id'=>base64_encode($address_verification->jaf_id)])}}",
            data: fd,
            processData: false,
            contentType: false,
            success: function(data) {
                
            },
            error: function(error) {
                console.log(error);
            }
        });

        
    },1000);
    
    
}

setTimeout(function(){   
    autoSave();  
}, 10000);
        // window.setTimeout(() => {
        //         $('.gmnoprint').attr('data-html2canvas-ignore',true);
        //         $('.gm-fullscreen-control').attr('data-html2canvas-ignore',true);
        //         html2canvas($(".maps"), {
        //             useCORS: true,
        //             onrendered: function(canvas) {
        //                 //console.log(canvas.toDataURL("image/png"));
        //                 //$('#img_val').val(canvas.toDataURL("image/png"));
        //                 //$("#show_img").append(canvas);
        //                 //$('#map_image').attr('src',canvas.toDataURL("image/png"));

        //                 $('input[name=map_image]').val(canvas.toDataURL("image/png"));
        //             }
                    
        //         });   
        //         //$('.totalimage').css('display','none'); 
        //     }, 3000);
  $(document).ready(function(){
    $(document).on('submit','form#qc_frm',function (event) {
            event.preventDefault();
            //clearing the error msg
            $('p.error-container').html("");

            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            $('.submit').attr('disabled',true);
            $('.form-control').attr('readonly',true);
            $('.form-control').addClass('disabled-link');
            //    $('.error-control').attr('readonly',true);
            //    $('.error-control').addClass('disabled-link');
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
                    //    $('.error-control').attr('readonly',false);
                    //    $('.error-control').removeClass('disabled-link');
                        $('.submit').html('Submit');
                    },2000);
                    if(response.success==true) {          
                        // var case_id = response.case_id;
                        //notify
                        toastr.success("Address QC Form Submitted Successfully !!");

                        if(response.message_status)
                        {
                            toastr.success("Message Has Been Sent Successfully !!");
                        }

                        if(response.mail_status)
                        {
                            toastr.success("Mail Has Been Sent Successfully !!");
                        }
                        // redirect to google after 5 seconds
                        window.setTimeout(function() {
                            // window.location = "{{ url('/')}}"+"/candidates/jaf-info/"+case_id;
                            window.location.reload();
                        }, 2000);
                    
                    }
                    //show the form validates error
                    if(response.success==false ) {                              
                        for (control in response.errors) {   
                            $('#error-'+control).html(response.errors[control]);
                        }
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
            });
            event.stopImmediatePropagation();
            return false;
    });

    $(document).on('click','#print',function(event){
        event.preventDefault();
        var _this = $(this);
        $('.print_this').attr('id','print-area');
        $('.print_data').addClass('printable');

       //var mapElement = $("#map")[0];

        // perform the conversion
        // html2canvas(mapElement, {
        //     // required otherwise the map will be blank
        //     useCORS: true,
        //     onrendered: function(canvas) {
        //         var url = canvas.toDataURL();
        //         // $("<a>", {
        //         //     href: url,
        //         //     download: "Map"
        //         // })
        //         // .on("click", function() {$(this).remove()})
        //         // .appendTo("body")[0].click()
        //     }
        // });
        window.print();
    });

    $(document).on('click','.addToReport',function(event){
        event.preventDefault();
        var _this = $(this);
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
        swal({
            // icon: "warning",
            type: "warning",
            title: "Are You Sure Want To Add To The Report?",
            text: "",
            dangerMode: true,
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "YES",
            cancelButtonText: "CANCEL",
            closeOnConfirm: false,
            closeOnCancel: false,
            },
            function(e){
               if(e==true)
               {
                    swal.close();
                    $.ajax({
                            type:'POST',
                            url: _this.attr('href'),
                            data: {"_token" : "{{ csrf_token() }}"}, 
                            beforeSend: function() {
                                //something before send
                                _this.html(loadingText).fadeIn(300);
                                _this.attr('disabled',true);
                            },       
                            success: function (response) {
                                if(response.success)
                                {
                                    _this.html('<i class="fas fa-plus"></i> Add to Report');
                                    _this.attr('disabled',false);
                                    
                                    toastr.success("Report Attachment Added Successfully");

                                    window.setTimeout(function(){
                                        window.location.reload();
                                    },2000);
                                }
                            },
                            error: function (xhr, textStatus, errorThrown) {
                                // alert("Error: " + errorThrown);
                            }
                    });
                    event.stopImmediatePropagation();
               }
               else
               {
                    swal.close();
               }
        });
    });


    $(document).on('change','.qc_decision',function(event){
        event.preventDefault();

        var _this = $(this);

        var candidate_id = $('#candidate_id').val();

        $('.email-div').addClass('d-none');

        $('.email-div').html('');

        //alert(_this.val());
        // Check if qc decision is Fail Resend (Mail) or Fail Resend (SMS & Mail) is Selected

        if(_this.val()==3 || _this.val()==4)
        {
            $.ajax({
                    type:'POST',
                    url: "{{route('/candidates/check-email-exist')}}",
                    data: {'_token' : '{{csrf_token()}}','id':candidate_id},        
                    success: function (response) {        
                        if (response.status==true) {  

                            if(response.alternative_email==false)
                            {
                                $('.email-div').removeClass('d-none');

                                var readonly = '';

                                if(response.result.email!=null)
                                {
                                    readonly = 'readonly';
                                }

                                $('.email-div').html(`<div class="row">
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label for="label_name"> Email ID: <span class="text-danger">*</span></label>
                                                                <input type="text" name="email" id="email" class="form-control" value="${response.result.email}" ${readonly}>
                                                                <p style="margin-bottom: 2px;" class="text-danger error-container error-email" id="error-email"></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label for="label_name"> Alternative Email: </label>
                                                                <input type="text" name="alternative_email" id="alternative_email" class="form-control" value="${response.result.alternative_email!=null ? response.result.alternative_email : ''}">
                                                                <p style="margin-bottom: 2px;" class="text-danger error-container error-alternative_email" id="error-alternative_email"></p> 
                                                            </div>
                                                        </div>
                                                      </div>`);

                                

                            }

                        }
                        else if(response.status==false)
                        {
                            toastr.error("Something Went Wrong !!");
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        // alert("Error: " + errorThrown);
                        console.log(errorThrown);
                    }
                });
        }

    });

  });

</script>
@endsection
