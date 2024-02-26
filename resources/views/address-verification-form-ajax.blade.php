<div class="card text-left">

    <div class="card-body mb-4">
        <form method="post" action="{{url('/address-verification-form',['id'=>base64_encode($jaf_data->id)])}}" id="address_frm">
            @csrf
                @php
                    $candidate = Helper::user_details($jaf_data->candidate_id);
                    $candidate_address = $jaf_data->form_data;

                    $addr = '';
                    $zip = '';
                    $contact_number = '';

                    $address_type = 'others';

                    if($address_ver!=NULL && $address_ver->address_type!=NULL)
                    {
                        $address_type = $address_ver->address_type;
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
                <div class="col-12 col-md-9 QC-data">

                    <h3 class="card-title mb-3"> Digital Address Verification </h3>
                </div>
                <div class="col-12 QC-data mb-3">
                    <div class="data">
                        <div class="row">
                            {{-- <div class="col-12 col-md-6 col-lg-6"><p><strong>Reference Number:</strong>
                                {{ Helper::user_reference_id($jaf_data->candidate_id) }}</p></div>
                            <div class="col-12 col-md-6 col-lg-6"><p>
                                <strong>Email:</strong> {{ $candidate->email ? $candidate->email : 'N/A' }}
                                <input type="hidden" name="email_address" value="{{ $candidate->email }}">
                            </p></div>
                            <div class="col-12 col-md-6 col-lg-6"><p>
                                <strong>Alternate Email:</strong> {{ $candidate->alternative_email ? $candidate->alternative_email:'N/A' }}
                                <input type="hidden" name="alternative_email_address" value="{{ $candidate->alternative_email }}">
                            </p></div> --}}
                            <div class="col-12 col-md-6 col-lg-6"><p><strong>Client Name:</strong>
                                {{ Helper::company_name($jaf_data->business_id) }}</p></div>
                            {{-- <div class="col-12 col-md-6 col-lg-6"><p>
                                <strong>Contact Number:</strong> {{$contact_number}}
                                <input type="hidden" name="phone_number" value="{{$contact_number}}">
                            </p></div> --}}
                            <div class="col-12 col-md-6 col-lg-6"><p><strong>Candidate Name:</strong>
                                {{ Helper::user_name($jaf_data->candidate_id) }}</p></div>
                            {{-- <div class="col-12 col-md-6 col-lg-6"><p>
                                <strong>Address:</strong> {{$addr}}
                                <input type="hidden" name="address" value="{{$addr}}">
                            </p></div>
                            <div class="col-12 col-md-6 col-lg-6"><p><strong>Check Name:</strong>
                                {{ $jaf_data != null ? $jaf_data->service_name . ' - ' . $jaf_data->check_item_number : '--' }}
                            </p></div>
                            <div class="col-12 col-md-6 col-lg-6"><p>
                                <strong>Pincode:</strong> {{$zip}}
                                <input type="hidden" name="zipcode" value="{{$zip}}">
                            </p></div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-30">
                <div class="col-md-12">
                    <h3 class="card-title mb-3"> Verification Details </h3>
                    <div class="table-responsive">
                        <table class="table table-bordered table-collapsed verification_details">
                            <tbody>
                                {{-- <tr>
                                    <td></td>
                                    <td></td>
                                </tr> --}}
                                <tr>
                                    <td><strong>Nature of Residence: <span class="text-danger">*</span></strong> </td>
                                    <td>
                                        {{-- <div class="row">
                                            <div class="col-12"> --}}
                                                <select class="form-control" name="nature_of_residence" id="nature_of_residence">
                                                    <option value="">--Select--</option>
                                                    <option value="rented" @if($address_ver!=NULL && $address_ver->nature_of_residence!=NULL && stripos($address_ver->nature_of_residence,'rented')!==false) selected @endif>Rented</option>
                                                    <option value="owned" @if($address_ver!=NULL && $address_ver->nature_of_residence!=NULL && stripos($address_ver->nature_of_residence,'owned')!==false) selected @endif>Owned</option>
                                                    <option value="pg" @if($address_ver!=NULL && $address_ver->nature_of_residence!=NULL && stripos($address_ver->nature_of_residence,'pg')!==false) selected @endif>PG</option>
                                                                        <option value="hostel" @if($address_ver!=NULL && $address_ver->nature_of_residence!=NULL && stripos($address_ver->nature_of_residence,'hostel')!==false) selected @endif>Hostel</option>
                                                                        <option value="others" @if($address_ver!=NULL && $address_ver->nature_of_residence!=NULL && !(stripos($address_ver->nature_of_residence,'rented')!==false || stripos($address_ver->nature_of_residence,'owned')!==false || stripos($address_ver->nature_of_residence,'pg')!==false || stripos($address_ver->nature_of_residence,'hostel')!==false)) selected @endif>Others</option>
                                                </select>
                                                
                                            {{-- </div>
                                            <div class="col-12 "> --}}
                                                <input class="form-control @if($address_ver==NULL || $address_ver->nature_of_residence==NULL || stripos($address_ver->nature_of_residence,'rented')!==false || stripos($address_ver->nature_of_residence,'owned')!==false) d-none @endif" id="other_residence" name="other_residence" type="text" placeholder="" value="{{$address_ver!=NULL ? $address_ver->nature_of_residence : ''}}"> 
                                                <p style="margin-bottom: 2px;" class="text-danger error-container error-nature_of_residence" id="error-nature_of_residence"></p>
                                                <p style="margin-bottom: 2px;" class="text-danger error-container error-other_residence" id="error-other_residence"></p>
                                            {{-- </div> --}}
                                        </div>
                                        {{-- <input class="form-control" name="nature_of_residence" type="text" placeholder="" value="{{$address_ver!=NULL ? $address_ver->nature_of_residence : ''}}">  --}}
                                        {{-- <p style="margin-bottom: 2px;" class="text-danger error-container error-nature_of_residence" id="error-nature_of_residence"></p> --}}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Period of Stay: <span class="text-danger">*</span></strong> </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="input-group mb-3 flex-sm-screen date_pick">
                                                    <input class="form-control commonDatepicker from_date" name="period_stay_from" type="text" placeholder="From" autocomplete="off" value="{{$address_ver!=NULL && $address_ver->period_stay_from!=NULL ? date('d-m-Y',strtotime($address_ver->period_stay_from)) : ''}}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                    </div>
                                                </div>
                                                {{-- <input class="form-control commonDatepicker from_date" name="period_stay_from" type="text" placeholder="From" autocomplete="off"> --}}
                                                <p style="margin-bottom: 2px;" class="text-danger error-container error-period_stay_from" id="error-period_stay_from"></p>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="input-group mb-3 flex-sm-screen date_pick">
                                                    <input class="form-control commonDatepicker to_date" name="period_stay_to" type="text" placeholder="To" autocomplete="off" value="{{$address_ver!=NULL && $address_ver->period_stay_to!=NULL ? date('d-m-Y',strtotime($address_ver->period_stay_to)) : ''}}">
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
                                    <td><strong>Candidate Name: <span class="text-danger">*</span></strong> </td>
                                    <td>
                                        <input class="form-control" name="verifier_name" type="text" placeholder="" value="{{$address_ver!=NULL ? $address_ver->verifier_name : ''}}">
                                        <p style="margin-bottom: 2px;" class="text-danger error-container error-verifier_name" id="error-verifier_name"></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Relation with the Verifier: <span class="text-danger">*</span></strong> </td>
                                    <td>
                                        <input class="form-control"name="relation_with_verifier" type="text" placeholder="" value="{{$address_ver!=NULL ? $address_ver->relation_with_verifier : ''}}">
                                        <p style="margin-bottom: 2px;" class="text-danger error-container error-relation_with_verifier" id="error-relation_with_verifier"></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Type of Address: <span class="text-danger">*</span></strong> </td>
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
                                    <td><strong>Nearest Landmark: <span class="text-danger">*</span></strong> </td>
                                    <td>
                                        <input class="form-control" name="landmark" type="text" placeholder="" value="{{$address_ver!=NULL ? $address_ver->landmark : ''}}"> 
                                        <p style="margin-bottom: 2px;" class="text-danger error-container error-landmark" id="error-landmark"></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row mt-30">
                <div class="col-md-12 mb-4">
                    <h3>Documents Uploaded :</h3>
                </div>
            </div>

            <input type="hidden" name="geo_latitude" id="geo_latitude">
            <input type="hidden" name="geo_longitude" id="geo_longitude">
            <input type="hidden" name="geo_address" id="geo_address">

            <div class="row mt-30">
                <div class="col-md-6 profile-card">
                    <div class="data-selfie">

                        <p class="label">Selfie with Front Door <span class="text-danger">*</span>
                           <br> <small>(Name Plate in Background)</small>
                        </p>

                        <div class="doc-upld">
                            <div class="row p-0 m-0">

                                <div class="col-md-12 right-upload font-doors">
                                    @php
                                        $front_door  = Helper::addressVerificationFile($jaf_data->id,'front_door');
                                    @endphp
                                    <h5>
                                        Upload Selfie with Front Door
                                    </h5>
                                    <input type="button" name="front-camera-open" class="text-right d-none d-lg-block error-btn" value="Open Camera" data-toggle="modal" data-target="#front-door-modal" style="float: right;">
                                    {{-- <label class="btn btn-sm btn-light d-lg-none text-dark border-dark" style="inline-block; float:right;"> --}}
                                        {{-- <input type="file" id="front_door" name="front_door" capture="user" accept="image/*" class="d-lg-none" style="width:22%; float:right;"> --}}
                                    {{-- </label> --}}
                                    <div class="file d-lg-none btn btn-sm btn-light text-dark border-dark error-control"  style=" float:right;">
                                        <label for="front_door" class="text-dark">Click Picture</label>
                                        <input type="file" id="front_door" class="error-control" name="front_door" capture="user" accept="image/*" class="" style=" float:right;">
                                    </div>
                                    <input type="hidden" name="front_door_cam" id="front_door_cam">
                                </div>
                                @if(count($front_door)>0)
                                    @php
                                        $path = url('/').'/uploads/candidate-front-door/';
                                    @endphp
                                    @foreach ($front_door as $item)
                                        <div class="front-div">
                                            <div class="col-md-12">
                                                <div class="Upload profile phototext-center profile-img">
                                                    <img src="{{ $path.$item->image }}" id="preview-front-door">
                                                    <a class="remove-image remove-front-door" id="remove-front-door" href="javascript:;" style="display: inline;">×</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="d-none front-div">
                                        <div class="col-md-12">
                                            <div class="Upload profile phototext-center profile-img">
                                                <img src="{{asset('admin/images/profile-default-avtar.jpg')}}" id="preview-front-door">
                                                <a class="remove-image remove-front-door" id="remove-front-door" href="javascript:;" style="display: inline;">×</a>
                                            </div>
                                        </div>
                                    </div>
                               @endif

                               <div class="col-12">
                                    <p style="margin-bottom: 2px;" class="text-danger error-container error-front_door" id="error-front_door"></p>
                                    <p style="margin-bottom: 2px;" class="text-danger error-container error-front_door_cam" id="error-front_door_cam"></p>
                               </div>
                            </div>
                            
                        </div>


                    </div>
                </div>
                <div class="col-md-6 profile-card">
                    <div class="data-selfie">

                        <p class="label">Profile Photo (Selfie) <span class="text-danger">*</span></p>

                        <div class="doc-upld">
                            <div class="row p-0 m-0">

                                <div class="col-md-12 right-upload font-doors">
                                    @php
                                        $profile_photo  = Helper::addressVerificationFile($jaf_data->id,'profile_photo');
                                    @endphp
                                    <h5>
                                        Upload Profile Photo (Selfie)
                                    </h5>
                                    <input type="button" name="profile-camera-open" class="text-right d-none d-lg-block error-btn" value="Open Camera" data-toggle="modal" data-target="#profile-photo-modal" style="float: right;">
                                    {{-- <label class="btn btn-sm btn-light d-lg-none text-dark border-dark" style="inline-block; float:right;">
                                        Click Picture<input type="file" id="profile_photo" class="d-none" capture="user" accept="image/*" name="profile_photo">
                                    </label> --}}
                                    <div class="file d-lg-none btn btn-sm btn-light text-dark border-dark error-control"  style=" float:right;">
                                        <label for="profile_photo" class="text-dark">Click Picture</label>
                                        <input type="file" id="profile_photo" class="error-control" capture="user" accept="image/*" name="profile_photo" style=" float:right;">
                                    </div>
                                    <input type="hidden" name="profile_photo_cam" id="profile_photo_cam">
                                </div>
                                @if(count($profile_photo)>0)
                                    @php
                                        $path = url('/').'/uploads/candidate-selfie/';
                                    @endphp
                                    @foreach ($profile_photo as $item)
                                        <div class="profile-div">
                                            <div class="col-md-12">
                                                <div class="Upload profile phototext-center profile-img">
                                                    <img src="{{ $path.$item->image }}" id="preview-profile">
                                                    <a class="remove-image remove-profile-photo" href="javascript:;" style="display: inline;" id="remove-profile-photo">×</a>
                                                </div>
                                            </div>
                                        </div>
                                     @endforeach
                                @else
                                    <div class="d-none profile-div">
                                        <div class="col-md-12">
                                            <div class="Upload profile phototext-center profile-img">
                                                <img src="{{asset('admin/images/profile-default-avtar.jpg')}}" id="preview-profile">
                                                <a class="remove-image remove-profile-photo" href="javascript:;" style="display: inline;" id="remove-profile-photo">×</a>
                                            </div>
                                        </div>
                                        {{-- <div class="text-center d-none" style="width: 100%; margin-bottom:20px;">
                                            <p class="pb-0 mb-0"><strong>Timestamp:</strong> 2022-09-22 07:25:40
                                            </p>
                                            <p class="pb-0 mb-0"><strong>Location :</strong> 28.5837055 ,
                                                77.3156656</p>
                                        </div> --}}
                                    </div>
                                @endif

                                 <div class="col-12">
                                    <p style="margin-bottom: 2px;" class="text-danger error-container error-profile_photo" id="error-profile_photo"></p>
                                    <p style="margin-bottom: 2px;" class="text-danger error-container error-profile_photo_cam" id="error-profile_photo_cam"></p>
                                </div>
                            </div>
                            <div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-md-6 profile-card">
                    <div class="data-selfie">

                        <p class="label">ID Document (Front) <span class="text-danger">*</span>
                        <br><small>(ID Document  with Photo (1))</small>
                        </p>

                        <div class="doc-upld">
                            <div class="row p-0 m-0">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Document Type <span class="text-danger">*</span></label>
                                        <select class="form-control document_type" name="document_type" id="document_type">
                                            <option value="">--Select--</option>
                                            <option value="pan">PAN</option>
                                            <option value="voter_id">Voter ID</option>
                                            <option value="passport">Passport</option>
                                            <option value="driving_license">Driving License</option>
                                        </select>
                                        <p style="margin-bottom: 2px;" class="text-danger error-container error-document_type" id="error-document_type"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row p-0 m-0">

                                <div class="col-md-12 right-upload font-doors">
                                    @php
                                        $id_proof  = Helper::addressVerificationFile($jaf_data->id,'id_proof');
                                    @endphp
                                    <h5>
                                        Upload ID Document (Front)
                                    </h5>
                                    <input type="button" name="id-camera-open" class="text-right d-none d-lg-block error-btn" value="Open Camera" data-toggle="modal" data-target="#id-front-proof-modal" style="float: right;">
                                    {{-- <label class="btn btn-sm btn-light d-lg-none text-dark border-dark" style="inline-block; float:right;">
                                        Click Picture<input type="file" id="id_proof" name="id_proof" class="d-none" capture="user" accept="image/*" style=" float:right;">
                                    </label> --}}
                                    <div class="file d-lg-none btn btn-sm btn-light text-dark border-dark error-control"  style=" float:right;">
                                        <label for="id_front_proof" class="text-dark">Click Picture</label>
                                        <input type="file" id="id_front_proof" class="error-control" name="id_front_proof" capture="user" accept="image/*" style=" float:right;">
                                    </div>
                                    <input type="hidden" name="id_front_proof_cam" id="id_front_proof_cam">
                                </div>

                                @if(count($id_proof)>0)
                                    @php
                                        $path = url('/').'/uploads/id-proof/';
                                    @endphp
                                    @foreach ($id_proof as $item)
                                        <div class="id-front-proof-div">
                                            <div class="col-md-12">
                                                <div class="Upload profile phototext-center profile-img" >
                                                    <img src="{{ $path.$item->image }}" id="preview-id-front-proof">
                                                    <a class="remove-image remove-id-front-proof" id="remove-id-front-proof" href="javascript:;" style="display: inline;">×</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="d-none id-front-proof-div">
                                        <div class="col-md-12">
                                            <div class="Upload profile phototext-center profile-img" >
                                                <img src="{{asset('admin/images/profile-default-avtar.jpg')}}" id="preview-id-front-proof">
                                                <a class="remove-image remove-id-front-proof" id="remove-id-front-proof" href="javascript:;" style="display: inline;">×</a>
                                            </div>
                                        </div>
                                        {{-- <div class="text-center" style="width: 100%; margin-bottom:20px;">
                                            <p class="pb-0 mb-0"><strong>Timestamp:</strong> 2022-09-22 07:25:40
                                            </p>
                                            <p class="pb-0 mb-0"><strong>Location :</strong> 28.5837055 ,
                                                77.3156656</p>
                                        </div> --}}
                                    </div>
                                @endif
                                <div class="col-12">
                                    <p style="margin-bottom: 2px;" class="text-danger error-container error-id_front_proof" id="error-id_front_proof"></p>
                                    <p style="margin-bottom: 2px;" class="text-danger error-container error-id_front_proof_cam" id="error-id_front_proof_cam"></p>
                                </div>
                            </div>
                            <div>
                            </div>
                            
                        </div>


                    </div>
                </div>
                <div class="col-md-6 profile-card">
                    <div class="data-selfie">

                        <p class="label">ID Document (Backside) <span class="text-danger">*</span>
                        <br><small>(ID Document Showing Address Photo (2))</small>
                        </p>

                        <div class="doc-upld">
                            <div class="row p-0 m-0">

                                <div class="col-md-12 right-upload font-doors">
                                    @php
                                        $address_proof  = Helper::addressVerificationFile($jaf_data->id,'address_proof');
                                    @endphp
                                    <h5>
                                        Upload ID Document (Backside)
                                    </h5>
                                    <input type="button" name="id-camera-open" class="text-right d-none d-lg-block error-btn" value="Open Camera" data-toggle="modal" data-target="#id-proof-modal" style="float: right;">
                                    {{-- <label class="btn btn-sm btn-light d-lg-none text-dark border-dark" style="inline-block; float:right;">
                                        Click Picture<input type="file" id="id_proof" name="id_proof" class="d-none" capture="user" accept="image/*" style=" float:right;">
                                    </label> --}}
                                    <div class="file d-lg-none btn btn-sm btn-light text-dark border-dark error-control"  style=" float:right;">
                                        <label for="id_proof" class="text-dark">Click Picture</label>
                                        <input type="file" id="id_proof" class="error-control" name="id_proof" capture="user" accept="image/*" style=" float:right;">
                                    </div>
                                    <input type="hidden" name="id_proof_cam" id="id_proof_cam">
                                </div>
                                @if(count($address_proof)>0)
                                    @php
                                        $path = url('/').'/uploads/address-proof/';
                                    @endphp
                                    @foreach ($address_proof as $item)
                                        <div class="id-proof-div">
                                            <div class="col-md-12">
                                                <div class="Upload profile phototext-center profile-img" >
                                                    <img src="{{ $path.$item->image }}" id="preview-id-proof">
                                                    <a class="remove-image remove-id-proof" id="remove-id-proof" href="javascript:;" style="display: inline;">×</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="d-none id-proof-div">
                                        <div class="col-md-12">
                                            <div class="Upload profile phototext-center profile-img" >
                                                <img src="{{asset('admin/images/profile-default-avtar.jpg')}}" id="preview-id-proof">
                                                <a class="remove-image remove-id-proof" id="remove-id-proof" href="javascript:;" style="display: inline;">×</a>
                                            </div>
                                        </div>
                                        {{-- <div class="text-center" style="width: 100%; margin-bottom:20px;">
                                            <p class="pb-0 mb-0"><strong>Timestamp:</strong> 2022-09-22 07:25:40
                                            </p>
                                            <p class="pb-0 mb-0"><strong>Location :</strong> 28.5837055 ,
                                                77.3156656</p>
                                        </div> --}}
                                    </div>
                                @endif
                                <div class="col-12">
                                    <p style="margin-bottom: 2px;" class="text-danger error-container error-id_proof" id="error-id_proof"></p>
                                    <p style="margin-bottom: 2px;" class="text-danger error-container error-id_proof_cam" id="error-id_proof_cam"></p>
                                </div>
                            </div>
                            <div>
                            </div>
                        </div>


                    </div>
                </div>  
                <div class="col-md-6 profile-card">
                    <div class="data-selfie">

                        <p class="label">Photo of Landmark <span class="text-danger">*</span>
                        <br><small>(Famous Place/Hotel/Cinema etc)</small>
                        </p>

                        <div class="doc-upld">
                            <div class="row p-0 m-0">

                                <div class="col-md-12 right-upload font-doors">
                                    @php
                                        $location  = Helper::addressVerificationFile($address_verification->jaf_id,'location');
                                    @endphp
                                    <h5>
                                        Upload Nearest Landmark Picture
                                    </h5>
                                    <input type="button" name="landmark-camera-open error-btn" class="text-right d-none d-lg-block error-btn" value="Open Camera" data-toggle="modal" data-target="#landmark-modal" style="float: right;">
                                    {{-- <label class="btn btn-sm btn-light d-lg-none text-dark border-dark" style="inline-block; float:right;">
                                        Click Picture<input type="file" id="nearest_landmark" name="nearest_landmark" class="d-none" capture="user" accept="image/*" style="width:22%; display:inline-block; float:right;">
                                    </label> --}}
                                    <div class="file d-lg-none btn btn-sm btn-light text-dark border-dark error-control"  style=" float:right;">
                                        <label for="nearest_landmark" class="text-dark">Click Picture</label>
                                        <input type="file" id="nearest_landmark" name="nearest_landmark" class="error-control" capture="user" accept="image/*" style="float:right;">
                                    </div>
                                    <input type="hidden" name="nearest_landmark_cam" id="nearest_landmark_cam">
                                </div>
                                @if(count($location)>0)
                                    @php
                                        $path = url('/').'/uploads/candidate-location/';
                                    @endphp
                                    @foreach ($location as $item)
                                        <div class="landmark-div">
                                            <div class="col-md-12">
                                                <div class="Upload profile phototext-center profile-img" >
                                                    <img src="{{ $path.$item->image }}" id="preview-landmark">
                                                    <a class="remove-image remove-landmark" id="remove-landmark" href="javascript:;" style="display: inline;">×</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="d-none landmark-div">
                                        <div class="col-md-12">
                                            <div class="Upload profile phototext-center profile-img" >
                                                <img src="{{asset('admin/images/profile-default-avtar.jpg')}}" id="preview-landmark">
                                                <a class="remove-image remove-landmark" id="remove-landmark" href="javascript:;" style="display: inline;">×</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-12">
                                    <p style="margin-bottom: 2px;" class="text-danger error-container error-nearest_landmark" id="error-nearest_landmark"></p>
                                    <p style="margin-bottom: 2px;" class="text-danger error-container error-nearest_landmark_cam" id="error-nearest_landmark_cam"></p>
                                    <p style="margin-bottom: 2px;" class="text-danger pt-3">Note:- Please upload the same image in the Landmark field which you entered in the Landmark Name field.</p>
                                </div>
                            </div>
                            <div>
                            </div>
                        </div>


                    </div>
                </div> 
                <div class="col-md-6 d-lg-none profile-card">
                    <div class="data-selfie">

                        <p class="label">Signature <span class="text-danger">*</span></p>

                        {{-- <div class="d-flex align-items-center justify-content-between pl-3 pr-3 pb-3"> --}}
                            <div class="doc-upld">
                                <div class="row p-0 m-0">
                                    <div class="col-12 right-upload">
                                        <h5>Digital Signature</h5>
                                        <label class="btn btn-sm btn-light text-dark border-dark error-control" style="inline-block; float:right;">
                                            Open <input type="button" id="btn-signature" name="sign-open" class="d-none text-right error-btn" value="Open" style="float: right;">
                                        </label>
                                    </div>

                                    <div class="col-md-11  mt-2 ml-4 d-none">
                                        <img id="sig-image-mob" class="d-none" src="" alt="Your signature will go here!"/>
                                    </div>
                                    
                                    <div class="col-12 mb-2">
                                        <p style="margin-bottom: 2px;" class="text-danger error-container error-signature" id="error-signature"></p>
                                    </div>
                                </div>
                            </div>
                        {{-- </div> --}}
                    </div>
                </div>
                {{-- <div class="col-md-6 profile-card" id="signature-area">
                    <div class="data-selfie">

                        <p class="label">Signature <span class="text-danger">*</span></p>

                        <div class="doc-upld">
                            <div class="row p-0 m-0">

                                <div class="col-md-12 right-upload">
                                    <h5>
                                       Digital Signature 
                                    </h5>
                                    <input type="button" id="sign-btn" name="sign-btn" value="Open"
                                        style="width:22%; display:inline-block; float:right;">
                                    <br/>
                                    <div id="sig" > </div>
                                    <br/>
                                    <div class="text-center">
                                        <button type="button" id="clear" class="btn btn-danger btn-sm my-2">Clear Signature</button>
                                    </div>
                                    <textarea id="signature64" name="signature" class="error-control" style="display: none"></textarea>
                                </div>

                              <div class="col-12">
                                <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-signature"></p>
                              </div>
                            </div>
                            <div>
                            </div>
                        </div>


                    </div>
                </div> --}}

                <div class="col-md-6 profile-card" id="signature-area">
                    
                    <div class="data-selfie">
                        {{-- <i class="fa fa-times-circle close-sing" id="close-sing" aria-hidden="true"></i> --}}
                        <p class="label">Signature <span class="text-danger">*</span></p>

                        <div class="doc-upld">
                            <div class="row p-0 m-0">

                                <div class="col-md-12 right-upload">
                                    <h5>
                                       Digital Signature 
                                    </h5>
                                    <canvas id="sig-canvas" width="400px" height="160" class="error-control">
                                        Get a better browser, bro.
                                    </canvas>
                                </div>
                                {{-- <div class="col-md-6">
                                    <button type="button"class="btn btn-info" id="sig-submitBtn">Submit Signature</button>
                                    
                                </div> --}}
                                
                                <div class="col-md-12">
                                    <textarea id="sig-dataUrl" name="signature" class="form-control" rows="5" style="display: none;"></textarea>
                                </div>
                                <div class="col-md-12  mt-2 mb-2 d-none">
                                    <img id="sig-image" class="d-none border" src="" alt="Your signature will go here!"/>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-info mt-2 mb-4 error-btn d-none" id="sig-submitBtn" style="background-color: #003473;">Submit Signature</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-danger mt-2 mb-4 error-btn" id="sig-clearBtn">Clear Signature</button>
                                </div>
                              <div class="col-12">
                                <p style="margin-bottom: 2px;" class="text-danger error-container error-signature" id="error-signature"></p>
                              </div>
                            </div>
                            
                        </div>


                    </div>
                </div>

                <div class="col-md-12">
                    <input type="hidden" name="s_width">
                    <input type="hidden" name="s_height">
                    <div id="map">

                    </div>
                    
                    <div class="form-group mt-3">
                        <div class="form-check">
                            <label class="check-inline">
                               <input type="checkbox" name="submit_req" class="form-check-input submit_req" ><span style="font-size: 16px;"><b>I hereby give my consent and declare that the details furnished above are true and complete to the best of my knowledge, information and belief.</b></span>
                            </label>
                         </div>
                    </div>
                    <p style="margin-bottom: 2px;" class="text-danger error-container error-submit_req" id="error-submit_req"></p>
                    <p style="margin-bottom: 2px;" class="text-danger error-container error-all" id="error-all"></p>
                    <div class="submit-btn">
                        <button type="submit" class="mb-2 submit width-100" style="background-color: #003473;">Submit</button>
                    </div> 
                </div>
            </div>  

           
        </form>
    </div>

</div>