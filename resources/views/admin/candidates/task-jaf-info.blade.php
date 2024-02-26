<style>
    .disabled-link{
       pointer-events: none;
    }
    .disabled-link-1{
       pointer-events: none;
    }
    .sweet-alert p {
       text-align: left !important; 
    }
 
    .overflow-modal
    {
     max-height: 400px;
     overflow-x: hidden;
     overflow-y: scroll;
    }
    #raise_modal {
     
     z-index: 99999!important;
    } 
    .modal {
     
     z-index: 9999!important;
    } 
    /* .Datepicker
    {
       z-index:20000000 !important;
    } */
 
    /* .tooltips {
     
     z-index: 99999!important;
    } */
 
    /* .modal-header .close {
     padding: 1rem;
     margin: -5rem -2rem -1rem auto;
    } */
   /* .bcd_loading{
    padding-top: 20%;
     text-align: center;
   } */
 
 /* .swal-button--confirm {
   padding: 7px 19px;
   border-radius: 2px;
   background-color: #4962B3;
   font-size: 15px;
   border: 1px solid #3e549a;
   text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.3);
 }
 .swal-button--cancel {
   padding: 7px 19px;
   border-radius: 2px;
   background-color: #4962B3;
   color: #cdd1d8;
   font-size: 15px;
   border: 1px solid #3e549a;
   text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.3);
 } */
 
 .sticky {
    position: fixed;
     top: 8%;
     width: 100%;
     z-index: 999;
     background: #eeeeee;
     border: 1px solid #eee;
     border-radius: 3px;
   
 }
 
 .sticky li{
    color: #fff !important;
 }
 .col-sm-11.breadcrum1 {
     position: relative;
     top: -9px;
 }
 
 .remove-image
     {
         padding: 0px 3px 0px !important;
     }
 
     .image-area img{
         height: 100px !important;
         width: 100px !important;
         padding: 8px !important;
     }
 
     .image-area{
         width: 90px !important;
     }
 
     .remove-image:hover
     {
         padding: 0px 3px 0px !important;
     }
 
     #myImageModal {
     display: none; /* Hidden by default */
     position: fixed; /* Stay in place */
     z-index: 1; /* Sit on top */
     padding-top: 100px; /* Location of the box */
     left: 0;
     top: 0;
     width: 100%; /* Full width */
     height: 100%; /* Full height */
     overflow: auto; /* Enable scroll if needed */
     background-color: rgb(0,0,0); /* Fallback color */
     background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
   }
   /* Modal Content (image) */
 .image-modal-content {
   margin: auto;
   display: block;
   width: 80%;
   max-width: 700px;
 }
 
 /* Caption of Modal Image */
 #caption {
   margin: auto;
   display: block;
   width: 80%;
   max-width: 700px;
   text-align: center;
   color: #ccc;
   padding: 10px 0;
   height: 150px;
 }
 
 /* Add Animation */
 .image-modal-content, #caption {  
   -webkit-animation-name: zoom;
   -webkit-animation-duration: 0.6s;
   animation-name: zoom;
   animation-duration: 0.6s;
 }
 
 @-webkit-keyframes zoom {
   from {-webkit-transform:scale(0)} 
   to {-webkit-transform:scale(1)}
 }
 
 @keyframes zoom {
   from {transform:scale(0)} 
   to {transform:scale(1)}
 }
 
 /* The Close Button */
 .closeImage {
   position: absolute;
   top: 60px;
   right: 20px;
   color: #f1f1f1;
   font-size: 40px;
   font-weight: bold;
   transition: 0.3s;
 }
 
 .closeImage:hover,
 .closeImage:focus {
   color: #bbb;
   text-decoration: none;
   cursor: pointer;
 }
 
 /* 100% Image Width on Smaller Screens */
 @media only screen and (max-width: 700px){
   .image-modal-content {
     width: 100%;
   }
 }
 .gallery ul{
     margin:0;
     padding:0;
     list-style-type:none;
 }
 .gallery ul li{
     padding:7px;
     border:2px solid #ccc;
     float:left;
     margin:10px 7px;
     background:none;
     width:auto;
     height:auto;
 }
 .modal-body.gallery-model {
     min-height: 400px;
     overflow:auto;
 }
 .gallery img{
     width:133px;
 }
 .modal-part1 {
     max-width: 72%!important;
    
 }
 .address_data{
    max-height: 300px;
     overflow-x: hidden;
     overflow-y: scroll;
 }
 /* .app_status .select2-container {
    z-index: 9999999 !important;
} */
 </style>
 @php
 use App\Traits\S3ConfigTrait;


// $ADD_ACCESS    = false;
$DIGITAL_VERIFICATION_ACCESS   = false;
$FORM_LINK_ACCESS   = false;
$DIGITAL_VERIFICATION_DATA_ACCESS =false;
$JAF_READY_TO_REPORT_ACCESS =false;

// dd($ADD_ACCESS);
$DIGITAL_VERIFICATION_ACCESS    = Helper::can_access('Digital Verification','');//passing action title and route group name
$FORM_LINK_ACCESS   = Helper::can_access('Form Link','');//passing action title and route group name
$DIGITAL_VERIFICATION_DATA_ACCESS   = Helper::can_access('Digital Verification Data','');//passing action title and route group name
$JAF_READY_TO_REPORT_ACCESS   = Helper::can_access('Jaf Ready To Report','');//passing action title and route group name

@endphp
@if ($candidate->jaf_status == 'filled')
    <div class="col-md-12">
        @if($task_for_verify!=NULL && $task_for_verify->tastatus=='1')
            <form class="mt-2" method="post" enctype="multipart/form-data" action="{{ url('/candidates/jafFormUpdate') }}" id="jaf_form">
                @csrf
        @endif
            <!-- candidate info  -->
            <input type="hidden" name="candidate_id" id="candidate_id" value="{{base64_encode( $candidate->id) }}">
            <input type="hidden" name="report_id" value="{{ base64_encode($report_id) }}">
            <input type="hidden" name="business_id" value="{{ base64_encode($candidate->business_id) }}">
            <div class="row">
                @if ($message = Session::get('success'))
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            <strong>{{ $message }}</strong>
                        </div>
                    </div>
                @endif
                <?php
                
                    $file_arr = [];
                    
                    $file_arr = Helper::get_jaf_attachFile($candidate->id);
                    
                    $url = '';
                    
                    $filename = null;
                    
                    $file_platform = null;
                    
                    if (count($file_arr) > 0) {
                        $filename = $file_arr['file_name'];
                    
                        $file_platform = $file_arr['file_platform'];
                        // $filename = Helper::get_jaf_attachFile($candidate->id);
                        $extension = pathinfo($filename, PATHINFO_EXTENSION);
                        //   dd($extension);
                    
                        if (stripos($file_platform, 's3') !== false) {
                            $filePath = 'uploads/jaf_details/';
                    
                            $s3_config = S3ConfigTrait::s3Config();
                    
                            $disk = \Storage::disk('s3');
                    
                            $command = $disk
                                ->getDriver()
                                ->getAdapter()
                                ->getClient()
                                ->getCommand('GetObject', [
                                    'Bucket' => \Config::get('filesystems.disks.s3.bucket'),
                                    'Key' => $filePath . $filename,
                                    'ResponseContentDisposition' => 'attachment;', //for download
                                ]);
                    
                            $req = $disk
                                ->getDriver()
                                ->getAdapter()
                                ->getClient()
                                ->createPresignedRequest($command, '+10 minutes');
                    
                            $url = $req->getUri();
                        } else {
                            $url = url('/') . '/uploads/jaf_details/' . $filename;
                        }
                    }
                ?>
                <div class="col-12">
                    <div class="row">
                        <div class="col-sm-9">
                            <h4 class="card-title mb-3 mt-2">Candidate Profile: <b> {{ $candidate->name }}
                                    ({{ Helper::candidate_reference_id($candidate->id) }}) </h4>
                        </div>
                        <div class="col-sm-3" style="float:right">
                            @if ($filename)
                                @if ($extension == 'zip')
                                    <a class="btn btn-link" href="{{ $url }}" title="download"
                                        style="float:right">Candidate's Document<i class="fas fa-download"></i></a>
                                @endif
                                @if ($extension == 'pdf' || $extension == 'xlsx' || $extension == 'csv' || $extension == 'docs' || $extension == 'docx')
                                    <a class="btn btn-link" href="{{ $url }}" title="download" target="_blank"
                                        style="float:right">Candidate's Document<i class="fas fa-download"></i></a>
                                @endif
                                @if ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')
                                    <a class="btn btn-link" href="{{ $url }}" download target="_blank"
                                        style="float:right">Candidate's Document<i class="fas fa-download"></i></a>
                                @endif
                                {{-- <a class="btn btn-link" href="{{url('/').'/uploads/jaf_details/'.$filename}}" title="download">JAF Details<i class="fas fa-download"></i></a> --}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if ($jaf_item)
                <?php $report_status = Helper::get_report_status($candidate->id);
                $readonly = '';
                $disabled_link = '';
                if ($report_status != null && ($report_status['status'] == 'completed' || $report_status == 'interim')) {
                    $readonly = 'readonly';
                
                    $disabled_link = 'disabled-link';
                }
                ?>
                {{-- @foreach ($jaf_items as $item) --}}

                {{-- @if ($check->checks == $jaf_item->service_id) --}}
                <?php
                //get sale item count
                $i = 0;
                $k = 0;
                $l = 0;
                $num = '';
                ?>
                <input type="hidden" value="{{ $jaf_item->id }}" name="jaf_id[]">
                <div class="row" style="padding: 10px 0; margin-top:10px; border:1px solid #ddd;">
                    <div class="col-md-6">

                        <h3 class=" mb-2 mt-2">Verification - {{ $jaf_item->service_name }}
                            {{ stripos($jaf_item->verification_type, 'Manual') !== false ? ' - ' . $jaf_item->check_item_number : '' }}
                        </h3>
                        <div class="row">
                            <div class="col-sm-6">
                                <p>Update the inputs </p>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <label class="check-inline {{ $disabled_link }}">

                                        @if($jaf_item->is_data_verified=='1')
                                            <button type="button" data-id="{{ $jaf_item->id }}" @if ($jaf_item->is_data_verified=='1') disabled @endif class="btn btn-success btn-sm verified_data verifiyed">Data Verified</button>
                                         @elseif($jaf_item->is_data_verified=='0')
                                            <button type="button" data-id="{{ $jaf_item->id }}" data-v="0" class="btn btn-info btn-sm verified_data unverifiyed">Data Verified ?</button>
                                            <input type="hidden" data-v="{{ $jaf_item->id }}" name="verified-input-checkbox-{{ $jaf_item->id}}" value="0" id="verified-input-checkbox-{{ $jaf_item->id}}" class="form-check-input verified_data">
                                         @endif 

                                            {{-- <input type="checkbox" data-id="{{ $jaf_item->id }}" name="verified-input-checkbox-{{ $jaf_item->id }}"class="form-check-input verified_data" @if ($jaf_item->is_data_verified == '1') checked  disabled @endif>Data Verified ? --}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- if check type is address  -->
                        @if ($jaf_item->service_id == '1')
                            @php
                                $digital_data = Helper::get_digital_data($jaf_item->id);
                                $address_ver = Helper::addressVerificationData($jaf_item->id);
                            @endphp
                            <div class="row">
                                @if($digital_data!=NULL)
                                    <div class="col-6">
                                        <label>Digital Verification: </label>
                                        <div class="form-group">
                                            <select class="form-control digital-select" name="digital-select-{{$jaf_item->id}}" id="digital-select-{{$jaf_item->id}}" data-id="{{$jaf_item->id}}">
                                                <option value="">--Select--</option>
                                                <option value="digital">Digital Verification</option>
                                            </select>
                                        </div>
                                    </div>
                                @endif
                             </div>
                            <div class="row">
                                <div class="col-sm-4 digital-check-div-{{$jaf_item->id}}">
                                    @if ($DIGITAL_VERIFICATION_ACCESS) 
                                        <div class="form-group">
                                            <div class="form-check">
                                                <label class="check-inline @if($digital_data!=NULL) disabled-link @endif {{$disabled_link}}">
                                                <input type="checkbox" data-digital_id="{{ $jaf_item->id }}" name="digital-verification-checkbox-{{ $jaf_item->id}}" class="form-check-input digital_verification" @if ($digital_data) checked disabled @endif>Digital Verification
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @if($digital_data!=NULL && ($address_ver==NULL || $address_ver->status==0))
                                    @if ($FORM_LINK_ACCESS)
                                        <div class="col-sm-3 form-link-div-{{$jaf_item->id}} d-none">
                                            <a href="{{url('/address-verification-form',['id'=>base64_encode($jaf_item->id)])}}" class="btn btn-sm btn-outline-success form-link" data-toggle="tooltip" data-original-title="Copy Link">Form Link</a>
                                        </div>
                                    @endif
                                @endif
                                @if(($address_ver!=NULL && $address_ver->status==1) && $digital_data!=NULL && $digital_data->status=='1')
                                    <div class="col-sm-5 digital-data-div-{{$jaf_item->id}} d-none">
                                        <div class="form-group">
                                            @if ($DIGITAL_VERIFICATION_DATA_ACCESS)
                                                <a href="{{url('/candidates/digital_address_verification',['id'=>base64_encode($jaf_item->id)])}}" class="btn btn-sm btn-outline-success" data-id="{{base64_encode($jaf_item->id)}}">Digital Verification Data</a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="row">
                                @if($digital_data!=NULL && $digital_data->status=='1')
                                    <div class="col-sm-4 digital-ver-div-{{$jaf_item->id}} d-none">
                                        <div class="form-group">
                                            <a href="{{url('/candidates/address-verification-form',['id'=>base64_encode($jaf_item->id)])}}" class="btn btn-sm btn-outline-info" data-id="{{base64_encode($jaf_item->id)}}">Digital Verification Form</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @if($digital_data!=NULL && ($address_ver==NULL || $address_ver->status==0))
                                @if ($FORM_LINK_ACCESS)
                                    <div class="row pl-3 pr-3 d-flex justify-content-between">
                                        {{-- <div class="col-sm-4">
                                            <div class="form-group">
                                                <a href="javascript:void(0);" class="btn btn-sm btn-outline-info send-form-link" data-id="{{base64_encode($jaf_item->id)}}"><i class="far fa-envelope"></i> Send Form Link</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <a href="javascript:void(0);" class="btn btn-sm btn-outline-info send-app-link" data-id="{{base64_encode($jaf_item->id)}}"><i class="far fa-envelope"></i> Send App Link</a>
                                            </div>
                                        </div> --}}
                                        <div class="send-sms-div-{{$jaf_item->id}} d-none">
                                            <div class="form-group">
                                               <a href="javascript:void(0);" class="btn btn-sm btn-outline-info send-link-sms" data-id="{{base64_encode($jaf_item->id)}}" data-candidate_id="{{base64_encode($jaf_item->candidate_id)}}"><i class="far fa-envelope"></i> Digital Address Verification through SMS</a>
                                            </div>
                                         </div>
                                         <div class="send-mail-div-{{$jaf_item->id}} d-none">
                                            <div class="form-group">
                                               <a href="javascript:void(0);" class="btn btn-sm btn-outline-info send-link-mail" data-id="{{base64_encode($jaf_item->id)}}" data-candidate_id="{{base64_encode($jaf_item->candidate_id)}}"><i class="far fa-envelope"></i> Digital Address Verification through Mail</a>
                                            </div>
                                         </div>
                                    </div>
                                    <div class="row pr-3 pl-3 d-flex justify-content-between">
                                        <div class="send-sms-mail-div-{{$jaf_item->id}} d-none">
                                            <div class="form-group">
                                                <a href="javascript:void(0);" class="btn btn-sm btn-outline-info send-link-sms-mail" data-id="{{base64_encode($jaf_item->id)}}" data-candidate_id="{{base64_encode($jaf_item->candidate_id)}}"><i class="far fa-envelope"></i> Digital Address Verification through SMS & Mail</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            @if($digital_data!=NULL)
                                @if ($DIGITAL_VERIFICATION_ACCESS)
                                    @php
                                        $digital_log = Helper::get_digital_verification_log($jaf_item->id);
                                    @endphp
                                    @if(count($digital_log)>0)
                                        <div class="row">
                                            <div class="col-6 digital-ver-log-div-{{$jaf_item->id}} d-none">
                                                <div class="form-group">
                                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-info digital-ver-log" data-id="{{base64_encode($jaf_item->id)}}" data-candidate_id="{{base64_encode($jaf_item->candidate_id)}}"> Digital Address Verification Log</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endif
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Address Type <span class="text-danger">*</span></label>
                                        <select class="form-control {{ $disabled_link }}"
                                            name="address-type-{{ $jaf_item->id }}" {{ $readonly }}>
                                            <option value="">- Select Type -</option>
                                            <option value="current"
                                                @if ($jaf_item->address_type != null) @if ($jaf_item->address_type == 'current') selected @endif
                                                @endif > Current </option>
                                            <option value="permanent"
                                                @if ($jaf_item->address_type != null) @if ($jaf_item->address_type == 'permanent') selected @endif
                                                @endif >Permanent</option>
                                        </select>

                                        {{-- @if ($errors->has('address-type-' . $jaf_item->id))
                                                   <div class="error pt-2 text-danger">
                                                      {{ $errors->first('address-type-'.$jaf_item->id) }}
                                                   </div>
                                                @endif --}}
                                        <p style="margin-bottom: 2px;" class="text-danger error-container"
                                            id="error-address-type-{{ $jaf_item->id }}"></p>

                                    </div>
                                </div>
                            </div>
                        @endif
                        <!--  -->

                        <!--  -->
                        <?php
                        $report_data = Helper::get_report_data($jaf_item->id, $jaf_item->service_id, $jaf_item->check_item_number);
                        $input_item_data = $report_data->jaf_data;
                        $reference_item_data = $report_data->reference_form_data;
                        $input_item_data_array = [];
                        if ($input_item_data != null) {
                            $input_item_data_array = json_decode($input_item_data, true);
                        }
                        ?>
                        @foreach ($input_item_data_array as $key => $input)
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <?php 
                                            $key_val = array_keys($input);
                                            $input_val = array_values($input);
                                            
                                            $university_board = $readonly = '';
                                            $university_board_id = '';
                                            $date_calss = '';
                                            $input_class = 'error-control';
                                            if ($report_status != null && ($report_status['status'] == 'completed' || $report_status == 'interim')) {
                                                $readonly = 'readonly';
                                            
                                                $disabled_link = 'disabled-link';
                                            }
                                            
                                            if ($key_val[0] == 'University Name / Board Name') {
                                                $university_board_id = '#searchUniversity_board';
                                                $university_board = 'searchUniversity_board';
                                            }
                                            //name
                                            if ($key_val[0] == 'First Name' || $key_val[0] == 'First name' || $key_val[0] == 'first name') {
                                                $name = $candidate->first_name;
                                                $readonly = 'readonly';
                                                $input_class = '';
                                            }
                                            if ($key_val[0] == 'Last Name' || $key_val[0] == 'Last name' || $key_val[0] == 'last name') {
                                                $name = $candidate->last_name;
                                                //$readonly = 'readonly';
                                                $input_class = '';
                                            }
                                            if ($key_val[0] == 'Date of Birth' || $key_val[0] == 'DOB' || $key_val[0] == 'dob') {
                                                // $dob = $candidate->dob;
                                                // if($dob !=NULL){
                                                //   $name = date('d-m-Y',strtotime($candidate->dob));
                                                // }
                                                $date_calss = 'commonDatepicker';
                                            }
                                            if (stripos($key_val[0], 'Date of Expire') !== false) {
                                                $date_calss = 'commonDatepicker';
                                            }
                                            
                                            if (stripos($key_val[0], 'Email Address') !== false) {
                                                $name = $candidate->email;
                                                $readonly = 'readonly';
                                                $input_class = '';
                                            }

                                            $check_input=Helper::check_item_input_name($jaf_item->service_id,$candidate->business_id,$key_val[0]);
                                        ?>
                                        @if ($jaf_item->service_id == 17)
                                            @if (stripos($key_val[0], 'Reference Type (Personal / Professional)') !== false)
                                                <label> {{ $key_val[0] }} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
                                                <input type="hidden"
                                                    name="service-input-label-{{ $jaf_item->id . '-' . $i }}"
                                                    value="{{ $key_val[0] }}">
                                                <select
                                                    class="form-control {{ $date_calss . '' . $university_board }} service-input-value-{{ $jaf_item->id . '-' . $i }} {{ $input_class }} reference_type {{ $disabled_link }}"
                                                    {{ $readonly }} id="{{ $university_board_id }}"
                                                    name="service-input-value-{{ $jaf_item->id . '-' . $i }}"
                                                    data-id="{{ base64_encode($report_data->id) }}"
                                                    data-jaf="{{ $jaf_item->id }}">
                                                    <option value="">--Select--</option>
                                                    <option @if (stripos($input_val[0], 'personal') !== false) selected @endif
                                                        value="personal">Personal</option>
                                                    <option @if (stripos($input_val[0], 'professional') !== false) selected @endif
                                                        value="professional">Professional</option>
                                                </select>
                                            @else
                                                <label> {{ $key_val[0] }} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
                                                <input type="hidden"
                                                    name="service-input-label-{{ $jaf_item->id . '-' . $i }}"
                                                    value="{{ $key_val[0] }}">
                                                <input
                                                    class="form-control {{ $university_board }} {{ $input_class }} {{ $disabled_link }} {{ $date_calss }}"
                                                    {{ $readonly }} id="{{ $university_board_id }}"
                                                    type="text"
                                                    name="service-input-value-{{ $jaf_item->id . '-' . $i }}"
                                                    value="{{ $input_val[0] }}">
                                            @endif
                                        @elseif(stripos($jaf_item->type_name, 'drug_test_5') !== false || stripos($jaf_item->type_name, 'drug_test_6') !== false || stripos($jaf_item->type_name, 'drug_test_7') !== false || stripos($jaf_item->type_name, 'drug_test_8') !== false || stripos($jaf_item->type_name, 'drug_test_9') !== false || stripos($jaf_item->type_name, 'drug_test_10') !== false)
                                            @if (stripos($key_val[0], 'Test Name') !== false)
                                                <label> {{ $key_val[0] }} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label><br>
                                                <input type="hidden"
                                                    name="service-input-label-{{ $jaf_item->id . '-' . $i }}"
                                                    value="{{ $key_val[0] }}">
                                                <input class="form-control" type="hidden"
                                                    name="service-input-value-{{ $jaf_item->id . '-' . $i }}"
                                                    value="{{ $input_val[0] }}">
                                                @php
                                                    $drug_test_name = Helper::drugTestName($jaf_item->service_id);
                                                @endphp
                                                @if (count($drug_test_name) > 0)
                                                    @foreach ($drug_test_name as $d_item)
                                                        <div class="form-check form-check-inline disabled-link-1">
                                                            <input
                                                                class="form-check-input test-name-{{ $jaf_item->id . '-' . $i }}"
                                                                type="checkbox"
                                                                name="test-name-{{ $jaf_item->id . '-' . $i }}[]"
                                                                value="{{ $d_item->test_name }}" checked readonly>
                                                            <label class="form-check-label"
                                                                for="inlineCheckbox-1">{{ $d_item->test_name }}</label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            @elseif(stripos($key_val[0], 'Result') !== false)
                                                <label> {{ $key_val[0] }} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
                                                <input type="hidden"
                                                    name="service-input-label-{{ $jaf_item->id . '-' . $i }}"
                                                    value="{{ $key_val[0] }}">
                                                <select
                                                    class="form-control {{ $date_calss . ' ' . $university_board }} service-input-value-{{ $jaf_item->id . '-' . $i }} {{ $input_class }} {{ $disabled_link }}"
                                                    name="service-input-value-{{ $jaf_item->id . '-' . $i }}"
                                                    {{ $readonly }}>
                                                    <option value="">--Select--</option>
                                                    <option @if (stripos($input_val[0], 'positive') !== false) selected @endif
                                                        value="positive">Positive</option>
                                                    <option @if (stripos($input_val[0], 'negative') !== false) selected @endif
                                                        value="negative">Negative</option>
                                                </select>
                                            @else
                                                <label> {{ $key_val[0] }} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
                                                <input type="hidden"
                                                    name="service-input-label-{{ $jaf_item->id . '-' . $i }}"
                                                    value="{{ $key_val[0] }}">
                                                <input
                                                    class="form-control {{ $university_board }} {{ $input_class }} {{ $disabled_link }} {{ $date_calss }}"
                                                    {{ $readonly }} id="{{ $university_board_id }}"
                                                    type="text"
                                                    name="service-input-value-{{ $jaf_item->id . '-' . $i }}"
                                                    value="{{ $input_val[0] }}">
                                            @endif
                                        @else
                                            <label> {{ $key_val[0] }} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
                                            <input type="hidden"
                                                name="service-input-label-{{ $jaf_item->id . '-' . $i }}"
                                                value="{{ $key_val[0] }}">
                                            <input
                                                class="form-control {{ $date_calss . '' . $university_board }} {{ $input_class }}"
                                                {{ $readonly }} id="{{ $university_board_id }}" type="text"
                                                name="service-input-value-{{ $jaf_item->id . '-' . $i }}"
                                                value="{{ $input_val[0] }}">
                                        @endif
                                        <p style="margin-bottom: 2px;" class="text-danger error-container"
                                            id="error-service-input-value-{{ $jaf_item->id . '-' . $i }}">
                                        </p>
                                    </div>
                                </div>
                                <!-- Remarks -->

                                <!--  -->
                                {{-- <div class="col-sm-8">
                                          <div class="form-group">
                                       
                                             <div class="form-check">
                                             <label class="check-inline {{$disabled_link}} error-control">
                                             <input type="checkbox" name="remarks-input-checkbox-{{ $jaf_item->id.'-'.$i}}"  @if (in_array('remarks', $key_val)) @if ($input['remarks'] == 'Yes') checked @endif @endif class="form-check-input" {{$readonly}}>Remarks
                                             </label>
                                             </div>
                                          </div>
                                       </div> --}}
                                <!-- check output -->
                                {{-- <div class="col-sm-10">
                                                <div class="form-group">
                                                <label class="checkbox-inline">
                                                <?php
                                                $is_executive_summary = '0';
                                                $is_executive_summary = Helper::get_is_executive_summary($jaf_item->service_id, $key_val[0]);
                                                $is_report_output = '0';
                                                // if(array_key_exists('is_executive_summary', $input_item_data_array[$i]))
                                                // {
                                                //     $is_executive_summary =  $input_item_data_array[$i]['is_executive_summary'];
                                                // }
                                                if (array_key_exists('is_report_output', $input_item_data_array[$i])) {
                                                    $is_report_output = $input_item_data_array[$i]['is_report_output'];
                                                }
                                                ?>
                                                   <input type="checkbox" name="executive-summary-{{ $jaf_item->id .'-'.$i}}" @if ($is_executive_summary)
                                                         
                                                      @if ($is_executive_summary->is_executive_summary == '1')  checked @endif @endif > Executive Summary Output (if yes: Check Mark)
                                                </label>
                                                </div>
                                                <div class="form-group">
                                                <label class="checkbox-inline">
                                                   <input type="checkbox" name="table-output-{{ $jaf_item->id.'-'.$i }}" @if ($is_report_output == '1')  checked @endif > Check's Table Output (if yes: Check Mark)
                                                </label>
                                                </div>
                                             </div> --}}
                                {{-- <div class="col-sm-6">
                                                
                                             </div> --}}

                                <!-- ./check outputs -->
                                <!-- end row -->
                            </div>
                            <?php $i++; ?>
                        @endforeach

                        @if ($report_data->service_id == 17)
                            <div class="reference_result" id="reference_result-{{ $jaf_item->id }}">
                                @php
                                    $reference_type = null;
                                    
                                    if ($report_data->reference_type != null) {
                                        $reference_type = $report_data->reference_type;
                                    } else {
                                        foreach ($input_item_data_array as $input) {
                                            $key_val = array_keys($input);
                                            $input_val = array_values($input);
                                    
                                            if (stripos($key_val[0], 'Reference Type (Personal / Professional)') !== false) {
                                                $reference_type = $input_val[0];
                                            }
                                        }
                                    }
                                @endphp
                                @if ($reference_type != null || $reference_type != '')
                                    <?php
                                        $reference_service_inputs = Helper::referenceServiceFormInputs($report_data->service_id, $reference_type);
                                    ?>
                                    @if ($reference_item_data != null)
                                        <?php
                                            $reference_item_data_array = json_decode($reference_item_data, true);
                                        ?>
                                        <div class="row"
                                            style="border:1px solid #ddd; padding:10px; margin-bottom:10px;">
                                            <h4 class="pt-2 pb-2">{{ ucwords($reference_type) }} Details
                                            </h4>
                                            @foreach ($reference_item_data_array as $key => $input)
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <?php
                                                            $key_val = array_keys($input);
                                                            $input_val = array_values($input);
                                                            if ($report_status != null && ($report_status['status'] == 'completed' || $report_status == 'interim')) {
                                                                $readonly = 'readonly';
                                                            }
                                                            $check_input=Helper::check_item_input_name($jaf_item->service_id,$candidate->business_id,$key_val[0]);
                                                        ?>
                                                        <label> {{ $key_val[0] }} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
                                                        <input type="hidden"
                                                            name="reference-input-label-{{ $jaf_item->id . '-' . $l }}"
                                                            value="{{ $key_val[0] }}">
                                                        <input class="form-control error-control" {{ $readonly }}
                                                            type="text"
                                                            name="reference-input-value-{{ $jaf_item->id . '-' . $l }}"
                                                            value="{{ $input_val[0] }}">
                                                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-reference-input-value-{{$jaf_item->id.'-'.$l}}"></p>
                                                    </div>
                                                </div>
                                                <?php $l++; ?>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="row"
                                            style="border:1px solid #ddd; padding:10px; margin-bottom:10px;">
                                            <h4 class="pt-2 pb-2">{{ ucwords($reference_type) }} Details
                                            </h4>
                                            @foreach ($reference_service_inputs as $key => $input)
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <?php
                                                            if ($report_status != null && ($report_status['status'] == 'completed' || $report_status == 'interim')) {
                                                                $readonly = 'readonly';
                                                            }
                                                            $check_input=Helper::check_item_input($input->id,$candidate->business_id);
                                                        ?>
                                                        <label> {{ $input->label_name }} @if($check_input!=NULL) <span class="text-danger">*</span> @endif</label>
                                                        <input type="hidden"
                                                            name="reference-input-label-{{ $jaf_item->id . '-' . $k }}"
                                                            value="{{ $input->label_name }}">
                                                        <input class="form-control error-control" {{ $readonly }}
                                                            type="text"
                                                            name="reference-input-value-{{ $jaf_item->id . '-' . $k }}">
                                                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-reference-input-value-{{$jaf_item->id.'-'.$k}}"></p>
                                                    </div>
                                                </div>
                                                <?php $k++; ?>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endif
                        <!--   -->
                        <!--   -->
                        <!-- comment  -->

                        <div class="row" style="border:1px solid #ddd; padding:10px; margin-bottom:10px;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4 class="card-title mb-2 mt-2">Approval Inputs </h4>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label> Verified By</label>
                                        <input class="form-control error-control" type="text"
                                            name="verified_by-{{ $jaf_item->id }}"
                                            value="{{ $report_data->verified_by }}" {{ $readonly }}>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label> Comments</label>
                                        <textarea class="form-control error-control" type="text" name="comments-{{ $jaf_item->id }}"
                                            {{ $readonly }}>{{ $report_data->comments }}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-8" style="">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3">Annexure
                                                Value</span>
                                        </div>
                                        <input type="text" class="form-control error-control"
                                            name="annexure_value-{{ $jaf_item->id }}"
                                            value="{{ $report_data->annexure_value }}"
                                            aria-describedby="basic-addon3" {{ $readonly }}>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Additional Comments</label>
                                        <textarea class="form-control error-control" type="text" name="additional-comments-{{ $jaf_item->id }}"
                                            {{ $readonly }}>{{ $report_data->additional_comments }}</textarea>
                                    </div>
                                </div>

                                <!--  -->
                                <div class="col-sm-12 app_status">
                                    
                                    <div class="form-group">
                                        <label>Approval Status</label>
                                        <select id="akiko" 
                                            class="form-control approval_status @if ($disabled_link == '') @endif error-control {{ $disabled_link }}"
                                            name="approval-status-{{ $jaf_item->id }}" {{ $readonly }}>
                                            @foreach ($status_list as $status)
                                                <option data-id="{{ $jaf_item->id }}"
                                                    value="{{ $status->id }}"
                                                    @if ($jaf_item->verification_status == 'success') @php
                                                $status->name == 'Verified Clear' 
                                             @endphp selected @endif
                                                    @if ($status->id == $report_data->approval_status_id) selected @endif>
                                                    {{ $status->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="new-tag"> </div>
                                    <input type="hidden" class="itemID" name="itemID"
                                        value="{{ $jaf_item->id }}">
                                </div>
                            </div>
                        </div>
                        <!--  -->
                        <!-- Court inpput start -->
                        <!-- Court inpput start -->
                        @if ($jaf_item->service_id == 15)
                            <div class="row mt-2">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label> <b> Court </b></label>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label> <b>Court Name </b></label>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label> <b>Result</b> </label>
                                    </div>
                                </div>
                                <!--  -->
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <p>District Court/Lower Court/Civil Court & Small Causes</p>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-6" style="padding-left:0px;padding-right:0px">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3">District
                                                Courts of</span>
                                        </div>
                                        <input type="text" class="form-control error-control"
                                            name="district_court_name-{{ $jaf_item->id }}"
                                            value="{{ $report_data->district_court_name }}"
                                            aria-describedby="basic-addon3" {{ $readonly }}>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="text" name="district_court_result-{{ $jaf_item->id }}"
                                            class="form-control error-control"
                                            value="{{ $report_data->district_court_result }}"
                                            {{ $readonly }}>
                                    </div>
                                </div>
                                <!--  -->
                            </div>
                            <!-- row. -->
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <p>High Court</p>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-6" style="padding-left:0px;padding-right:0px">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3">High Court of
                                                Jurisdiction at</span>
                                        </div>
                                        <input type="text" class="form-control error-control"
                                            name="high_court_name-{{ $jaf_item->id }}"
                                            value="{{ $report_data->high_court_name }}"
                                            aria-describedby="basic-addon3" {{ $readonly }}>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="text" name="high_court_result-{{ $jaf_item->id }}"
                                            class="form-control error-control"
                                            value="{{ $report_data->high_court_result }}" {{ $readonly }}>
                                    </div>
                                </div>
                                <!--  -->
                            </div>
                            <!-- ./row -->
                            <!-- row. -->
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <p>Supreme Court</p>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-6" style="padding-left:0px;padding-right:0px">
                                    <div class="form-group">
                                        <input type="text" name="supreme_court_name-{{ $jaf_item->id }}"
                                            class="form-control error-control"
                                            value="Supreme Court of India, New Delhi" readonly>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="text" name="supreme_court_result-{{ $jaf_item->id }}"
                                            class="form-control error-control"
                                            value="{{ $report_data->supreme_court_result }}" {{ $readonly }}>
                                    </div>
                                </div>
                                <!--  -->
                            </div>
                            <!-- ./row -->
                        @endif
                        <!-- ./ end court  -->
                        <!-- insufficiency -->
                        {{-- @if ($jaf_item->verification_status != 'success') --}}
                        @if ($jaf_item->is_insufficiency == 1)
                            <div class="row">
                                <div class="col-sm-12"
                                    style="border:1px solid #ddd; padding:10px; margin-bottom:10px;">
                                    <p>Insufficiency Status</p>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input style="margin-top: 1px;" type="checkbox"
                                                        class="form-check-input"
                                                        @if ($jaf_item->is_insufficiency == 1) checked @endif
                                                        name="insufficiency-{{ $jaf_item->id }}" disabled>Mark as
                                                    insufficiency
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>insufficiency Notes</label>
                                            <input type="text" class="form-control error-control"
                                                name="insufficiency-notes-{{ $jaf_item->id }}"
                                                value="{{ $jaf_item->insufficiency_notes }}" readonly>
                                        </div>
                                    </div>
                                    @if ($jaf_item->insuff_attachment != null)
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Insufficieny Attachment : </label>
                                                <a class="btn btn-link"
                                                    href="{{ url('/') . '/uploads/raise-insuff/' . $jaf_item->insuff_attachment }}"
                                                    title="download"><i class="fas fa-download"></i></a>
                                            </div>
                                        </div>
                                    @endif
                                    <!-- ./ -->
                                </div>
                            </div>
                        @endif
                        <!-- Autocheck Insuff -->
                        @if ($jaf_item->verification_type == 'Auto')
                            <div class="row">
                                <!--  -->
                                <div class="col-sm-12"
                                    style="border:1px solid red; padding:10px; margin-bottom:10px;">
                                    <div class="form-group">
                                        <label>Auto Check API Status:
                                            @if ($jaf_item->is_api_checked == '0')
                                                {{ $jaf_item->verification_status }}
                                            @elseif($jaf_item->verification_status == 'success')
                                                {{ $jaf_item->verification_status }}
                                                <div class="form-group">
                                                    <span class="text-success" style="font-size: 18px;">Insuff Cleared
                                                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                            @else
                                                {{ $jaf_item->verification_status }}

                                            @endif
                                        </label>

                                    </div>
                                </div>
                                <!-- ./ -->
                            </div>
                        @else
                            <!-- if manual  -->
                            @if ($jaf_item->verification_status == 'success')
                                <div class="form-group">
                                    <span class="text-success" style="font-size: 18px;">Insuff Cleared <i
                                            class="fa fa-check-circle" aria-hidden="true"></i>
                                    </span>
                                </div>
                            @else
                                {{ $jaf_item->verification_status }}
                            @endif
                            <!-- ./ if manual end -->
                        @endif
                        <!-- auto check insusff -->
                        <!-- clear insuff -->
                        {{-- @if ($jaf_item->is_insufficiency == 1) --}}
                        {{-- @if (($jaf_item->verification_status == null || $jaf_item->verification_status == 'failed') && $jaf_item->form_data != null) --}}
                        @if ($jaf_item->form_data != null)
                            {{-- @if ($jaf_item->is_insufficiency == 0 && $jaf_item->verfication_type == 'manual') --}}
                            <?php $report_status = Helper::get_report_status($jaf_item->candidate_id); ?>
                            @if ($report_status == null || $report_status['status'] == 'incomplete' || $report_status['status'] == 'interim')
                                <div class="row">
                                    {{-- <div class="col-sm-6">
                                             <div class="form-group"> 
                                             <a href="javascript:;" class=" btn btn-warning itemMarkAsCleared error-control" jaf-id="{{ base64_encode($jaf_item->id) }}" candidate-id="{{ base64_encode($candidate->id) }}" service-id="{{ base64_encode($jaf_item->service_id) }}" service-name="{{$jaf_item->service_name}}"> Mark as Insuff cleared </a>
                                             </div>
                                          </div> --}}
                                    {{-- @endif --}}
                                    @if ($jaf_item->is_insufficiency != 1)
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <a href="javascript:;"
                                                    class=" btn btn-danger raise_insuff error-control"
                                                    jaf-id="{{ base64_encode($jaf_item->id) }}"
                                                    candidate-id="{{ base64_encode($candidate->id) }}"
                                                    service-id="{{ base64_encode($jaf_item->service_id) }}"
                                                    service-name="{{ $jaf_item->service_name }}"> Raise
                                                    Insuff</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endif
                        <!-- clear insuff -->
                        <!-- ./insufficiency -->
                    </div>
                    <!-- attachment  -->
                    <div class="col-md-6">
                        <p>Attachments <i class="fa fa-info-circle tooltips" data-toggle="tooltip"
                                data-original-title="Only jpeg,png,jpg,pdf are accepted "></i> </p>
                        {{-- @if ($candidate->is_all_insuff_cleared == 0 && ($report_status == null || $report_status['status'] == 'incomplete')) --}}
                        @if (($report_status == null || $report_status['status'] == 'incomplete'))
                            <button class='btn btn-info clickReorder reorder_link' type="button"
                                add-imageId="{{ $jaf_item->id }}" data-imageType='main' style=' float:right; '><i
                                    class="fas fa-sync"></i> Re-Arrange </button>
                            <a class='btn-link clickSelectFile error-control' add-id="{{ $jaf_item->id }}"
                                data-number='1' data-result='fileResult1' data-type='main'
                                style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i
                                    class='fa fa-plus'></i> Add file</a>
                            <input type='file' class='fileupload' name="file-{{ $jaf_item->id }}[]"
                                id='file1-{{ $jaf_item->id }}' multiple="multiple" style='display:none' />
                        @endif
                        <div class="bcd_loading"></div>
                        <div class='row fileResult' id="fileResult1-{{ $jaf_item->id }}"
                            style='min-height: 20px; margin-top: 20px;'>
                            <?php $item_files = Helper::getJAFAttachFiles($jaf_item->id); //print_r($item_files); ?>
                            @foreach ($item_files as $file)
                                @if ($file['attachment_type'] == 'main')
                                    <div class="image-area">
                                        @if (stripos($file['file_name'], 'pdf') !== false)
                                            <img src="{{ url('/') . '/admin/images/icon_pdf.png' }}"
                                                alt="Preview" title="{{ $file['file_name'] }}">
                                        @else
                                            <img src="{{ $file['fileIcon'] }}" alt="Preview"
                                                title="{{ $file['file_name'] }}">
                                        @endif
                                        {{-- @if ($candidate->is_all_insuff_cleared == 0 && ($report_status == null || $report_status['status'] == 'incomplete')) --}}
                                        @if (($report_status == null || $report_status['status'] == 'incomplete'))
                                            <a class="remove-image" data-id="{{ $file['file_id'] }}"
                                                href="javascript:;" style="display: inline;">×</a>
                                        @endif
                                        <input type="hidden" name="fileID[]" value="{{ $file['file_id'] }}">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <p class="mt-2" style="margin-bottom:1px">Add Supportings: <i class="fa fa-info-circle"
                                data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i>
                        </p>
                        {{-- @if ($candidate->is_all_insuff_cleared == 0 && ($report_status == null || $report_status['status'] == 'incomplete' || $jaf_item->is_supplementary == '1')) --}}
                        @if (($report_status == null || $report_status['status'] == 'incomplete' || $jaf_item->is_supplementary == '1'))
                            <button class='btn btn-info clickReorder reorder_link' type="button"
                                add-imageId="{{ $jaf_item->id }}" data-imageType='supporting'
                                style=' float:right; '><i class="fas fa-sync"></i> Re-Arrange </button>
                            <a class='btn-link clickSelectFile error-control' add-id="{{ $jaf_item->id }}"
                                data-number='2' data-result='fileResult2' data-type='supporting'
                                style='color: #0056b3; font-size: 16px; ' href='javascript:;'><i
                                    class='fa fa-plus'></i> Add file</a>
                            <input type='file' class='fileupload' name="file-{{ $jaf_item->id }}[]"
                                id='file2-{{ $jaf_item->id }}' multiple="multiple" style='display:none' />
                        @endif
                        <div class="fileResult2-{{ $jaf_item->id }} text-center"></div>
                        <div class='row fileResult' id="fileResult2-{{ $jaf_item->id }}"
                            style='min-height: 20px; margin-top: 20px;'>
                            <?php $item_files = Helper::getJAFAttachFiles($jaf_item->id); //print_r($item_files); ?>
                            @foreach ($item_files as $file)
                                @if ($file['attachment_type'] == 'supporting')
                                    <div class="image-area">
                                        @if (stripos($file['file_name'], 'pdf') !== false)
                                            <img src="{{ url('/') . '/admin/images/icon_pdf.png' }}"
                                                alt="Preview" title="{{ $file['file_name'] }}">
                                        @else
                                            <img src="{{ $file['fileIcon'] }}" alt="Preview"
                                                title="{{ $file['file_name'] }}">
                                        @endif
                                        {{-- @if ($candidate->is_all_insuff_cleared == 0 && ($report_status == null || $report_status['status'] == 'incomplete' || $jaf_item->is_supplementary == '1')) --}}
                                        @if (($report_status == null || $report_status['status'] == 'incomplete' || $jaf_item->is_supplementary == '1'))
                                            <a class="remove-image" data-id="{{ $file['file_id'] }}"
                                                href="javascript:;" style="display: inline;">×</a>
                                        @endif
                                        <input type="hidden" name="fileID[]" value="{{ $file['file_id'] }}">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <!-- items loop closed -->
                </div>
                {{-- @endif --}}

                {{-- @endforeach --}}
                <!--<div class="row" style="padding: 10px 0; margin-top:10px; border:1px solid #ddd;">
                  <div class="col-sm-6">
                  <div class="form-group">
                     <label>Digital Signature </label>
                     <div class="custom-file error-control @if ($report_status['status'] == 'completed' || $report_status['status'] == 'interim') disabled-link @endif">
                        <input type="file" name="digital_signature" class="custom-file-input digital_signature" id="digital_signature" @if ($report_status['status'] == 'completed' || $report_status['status'] == 'interim') disabled @endif>
                        <label class="custom-file-label" id="digital_label" for="digital_signature">{{ $candidate->digital_signature != null || $candidate->digital_signature != '' ? \Str::limit($candidate->digital_signature, 30, '...') : 'Choose File...' }}</label>
                     </div>
                     {{-- <input class="form-control" type="file" name="digital_signature" id="digital_signature"> --}}
                     {{-- @if ($errors->has('digital_signature')) --}}
                        <div class="error text-danger">
                           {{-- {{ $errors->first('digital_signature') }} --}}
                        </div>
                        {{-- @endif --}}
                  </div>
                  </div>
                  @if ($candidate->digital_signature != null || $candidate->digital_signature != '')
                     {{-- @php
                        $digital_url = '';
                        if(stripos($candidate->digital_signature_file_platform,'s3')!==false)
                        {
                           $filePath = 'uploads/signatures/';

                           $s3_config = S3ConfigTrait::s3Config();

                           $disk = \Storage::disk('s3');

                           $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                 'Bucket'                     => \Config::get('filesystems.disks.s3.bucket'),
                                 'Key'                        => $filePath.$candidate->digital_signature,
                                 'ResponseContentDisposition' => 'attachment;'//for download
                           ]);

                           $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                           $digital_url = $req->getUri();
                        }
                        else
                        {
                           $digital_url = url('uploads/signatures/'.$candidate->digital_signature);
                        }
                     @endphp --}}
                  {{-- <div class="col-sm-6">
                     <div class="form-group">
                        <label for="company_logo"></label>
                        <span class="btn btn-link float-right text-dark close_btn">X</span>
                        <img id="preview_ds"  src="{{$digital_url}}" width="200" height="150"/>
                     </div>
                  </div> --}}
                @else
                <div class="col-sm-6">
                     <div class="form-group">
                        <label for="company_logo"></label>
                        <span class="d-none btn btn-link float-right text-dark close_btn">X</span>
                        <img id="preview_ds" width="200" height="150"/>
                     </div>
                  </div>
                  @endif
               </div>-->
                {{-- @if ($report_status == null || $report_status['status'] == 'incomplete') --}}
                @if (count($user_service_check) > 0)
                    <div class="row mt-3">
                        <div class="col-12">
                            <p class="text-danger">Note :- Please Make Sure About the Data Verified For Each Check Items..</p>
                        </div>
                        <div class="col-md-6">
                            {{-- <input class="btn btn-success jaf_info_submit" type="submit" value="Update" name="update"> --}}
                            @if($task_for_verify!=NULL && $task_for_verify->tastatus=='1')
                                <button class="btn btn-success jaf_info_submit" type="submit">Update</button>
                            @endif
                        </div>
                        {{-- <div class="col-md-3">
                            <input class="btn btn-success add_check" type="button" value="Add New Check"
                                name="add_check" data-candidate_id="{{ $candidate->id }}">
                        </div> --}}
                    </div>
                @endif
                {{-- @endif --}}
            @else
                <div class="col-sm-12"> JAF data is not Completed! </div>
            @endif

        @if($task_for_verify!=NULL && $task_for_verify->tastatus=='1')
            </form>
        @endif
    </div>
@else
    <div class="col-sm-12 text-center" style="color:red"> JAF data is not Completed! </div>
@endif

{{-- Raise Insuff --}}


 {{-- Clear Insuff --}}
 <div class="modal" id="clear_modal">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title" id="serv_name"></h4>
             {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
          </div>
          <!-- Modal body -->
          <form method="post" action="{{url('/candidates/jaf/clearCheckInsuff')}}" id="clear_insuff_form">
          @csrf
            <input type="hidden" name="cand_id" id="cand_id">
            <input type="hidden" name="serv_id" id="serv_id">
            <input type="hidden" name="jaf_f_id" id="jaf_f_id">
             <div class="modal-body">
             <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                <div class="form-group">
                      <label for="label_name"> Comments <span class="text-danger">*</span></label>
                      <textarea id="comment" name="comment" class="form-control comment" placeholder=""></textarea>
                      {{-- <input type="text" id="comments" name="comments" class="form-control comments" placeholder=""/> --}}
                      <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-comment"></p> 
                </div>
                <div class="form-group">
                   <label for="label_name"> Attachments: <i class="fa fa-info-circle tool" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i></label>
                   <input type="file" name="attachment[]" id="attachment" multiple class="form-control attachment">
                   <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-attachment"></p>  
                </div>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="submit" class="btn btn-info clear-submit">Submit </button>
                <button type="button" class="btn btn-danger closeraisemdl closeinsuffclear" >Close</button>
             </div>
          </form>
       </div>
    </div>
 </div>

 {{-- Images show and rearrange --}}

  <div id="myImageModal" class="modal" style="background-color: white; padding-top: 29px;" >
    <div class="modal-header">
        <span class="closeImage" style="color: red; top: 10px;">&times;</span><h5 class="modal-title">File-Preview </h5>     
     </div> 
     <div class="modal-body">
        <img class="image-modal-content" id="img01" style="background-color: white; border:1px solid black;">
        <div id="caption"></div>
     </div>
  </div>
   <!-- The Modal -->
  <div id="myDragModal" class="modal">
      
     <div class="modal-content modal-part1">
         <div class="modal-header">
             {{-- <button type="button" class="close"  aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button> --}}

             <h5 class="modal-title">Files- You can re-arrange order of the files by  drag the image.</h5>
             <button type="button" class=" closeDragImage " style="top: 10px;!important; color: red; font-size: 40px;font-weight: bold; transition: 0.3s; background:transparent; border:none;" >&times;</button>

        </div>
         <div class="modal-body gallery-model">
             <input type="hidden" name="itemId" id="jafImageId">
             <input type="hidden" name="itemType" id="jafImageType">
  
             <div class="gallery">
              
           </div>
               
         </div>
         <div class="modal-footer">
            {{-- <button type="submit" class="btn btn-info clear-submit">Submit </button> --}}
            <button type="button" class="btn btn-danger closeDragImage" >Close</button>
         </div>
     </div>
     
  </div>

  <div class="modal" id="candidate_email_mdl">
    <div class="modal-dialog modal-lg">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Digital Address Verification</h4>
             {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
          </div>
          <form method="post" action="{{ url('/candidates/address-verification-link-mail') }}" id="address_ver_mail_frm" enctype="multipart/form-data">
             @csrf
             <input type="hidden" name="jaf_id" id="jaf_id">
             <input type="hidden" name="candidate_id" id="candidate_id">
             <!-- Modal body -->
             <div class="modal-body">
                <div class="row">
                   <div class="col-12">
                      <div class="form-group">
                         <label for="label_name"> Candidate Name: </label>
                         <span class="candidate_name"></span>
                      </div>
                   </div>
                   <div class="col-6">
                      <div class="form-group">
                         <label for="label_name"> Email ID: <span class="text-danger">*</span></label>
                         <input type="text" name="email" id="email" class="form-control">
                         <p style="margin-bottom: 2px;" class="text-danger error-container error-email" id="error-email"></p> 
                      </div>
                   </div>
                   <div class="col-6">
                      <div class="form-group">
                         <label for="label_name"> Alternative Email: </label>
                         <input type="text" name="alternative_email" id="alternative_email" class="form-control">
                         <p style="margin-bottom: 2px;" class="text-danger error-container error-alternative_email" id="error-alternative_email"></p> 
                      </div>
                   </div>
                </div>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="submit" class="btn btn-info btn-disable btn-submit">Submit</button>
                <button type="button" class="btn btn-danger btn-disable btn-mail-close">Close</button>
             </div>
          </form>
       </div>
    </div>
  </div>

  <div class="modal" id="digital_ver_mdl">
    <div class="modal-dialog modal-lg">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Digital Address Verification</h4>
             {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
          </div>
             <!-- Modal body -->
             <div class="modal-body">
                <div class="row">
                   <div class="col-12">
                      <div class="form-group">
                         <label for="label_name"> Candidate Name: </label>
                         <span class="candidate_name"></span>
                      </div>
                   </div>
                   <div class="col-12">
                      <div class="form-group">
                         <label for="label_name"> Check Name: </label>
                         <span class="check_name"></span>
                      </div>
                   </div>
                   <div class="col-12 pt-2">
                      <h5 class="text-muted">Details:-</h5>
                      <p class="pb-border"></p>
                  </div>
                </div>
                <div class="action_data">
 
                </div>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-disable btn-digital-close">Close</button>
             </div>
          </form>
       </div>
    </div>
 </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script>
     $("#akiko").select2();
$( ".commonDatepicker" ).datepicker({
    changeMonth: true,
    changeYear: true,
    firstDay: 1,
    autoclose:true,
    todayHighlight: true,
    format: 'dd-mm-yyyy',
});
// $('.app_status').select2({
//         dropdownParent: $('#user_task_assign')
//     });

$('.tool').tooltip();
$('[data-toggle="tooltip"]').tooltip();


// $(document).on('click','.verified_data',function (event) {
//     var current_data = $(this);
//     var check_id = $(this).attr('data-id');
//     var status = $(this).prop('checked');
//     // console.log(current_data);
//     var r =swal({
//                 title: "Are you sure?",
//                 text: "While confirming this status, please make sure about Verification data or attachment submitted!",
//                 type: "warning",
//                 dangerMode: true,
//                 showCancelButton: true,
//                 confirmButtonColor: "#DD6B55",
//                 confirmButtonText: "YES",
//                 cancelButtonText: "CANCEL",
//                 closeOnConfirm: false,
//                 closeOnCancel: false
//                 },
//                 function(e){
//                     console.log(e);
//                     //Use the "Strict Equality Comparison" to accept the user's input "false" as string)
//                     // if check the checkox
//                     if (status== true) {
//                         if (e===false) {
//                             current_data.prop('checked',false);
//                             // toastr.success("New Check added  successfully");
//                                 // redirect to google after 5 seconds
//                                 swal.close();
//                         // console.log("Do here everything you want");
//                         } else {

//                             $.ajax({
//                                 type:'POST',
//                                 url: "{{ url('/')}}"+"/candidates/jaf/data-verified",
//                                 data: {"_token": "{{ csrf_token() }}",'id':check_id},        
//                                 success: function (response) {  
//                                     if(response.success==true)      
//                                         current_data.prop('checked',true);
//                                     else
//                                     {
//                                         toastr.error("Before Verifying the Data, Please Clear the Insufficiency First !!");
//                                         current_data.prop('checked',false);
//                                     }
//                                 },
//                                 error: function (xhr, textStatus, errorThrown) {
//                                         // alert("Error: " + errorThrown);
//                                 }
//                                 });
//                             swal.close();
//                             // swal("Oh no...");
//                             // console.log("The user says: ",e);
//                         }
                        
//                     } // if uncheck the checkox
//                     else {
//                         if (e===false) {
//                             current_data.prop('checked',true);
//                         // swal("Ok done!","!");
//                         swal.close();
//                         // console.log("Do here everything you want");
//                         } else {
                            
//                             current_data.prop('checked',false);
                            
//                             $('#jaf-ready-report').prop('checked',false);
//                             // swal("Oh no...");
//                             swal.close();
//                             // console.log("The user says: ",e);
//                         }
//                     }
//                 }
//             );
   
// });

$(document).on('click','.verified_data',function (event) {
         var current_data = $(this);
         var check_id =    $(this).attr('data-id');
         var status   =    $(this).attr('data-v');
            
         var r =swal({
                     title: "Are you sure?",
                     text: "While confirming this status, please make sure about Verification data or attachment submitted!",
                     type: "warning",
                     dangerMode: true,
                     showCancelButton: true,
                     confirmButtonColor: "#DD6B55",
                     confirmButtonText: "YES",
                     cancelButtonText: "CANCEL",
                     closeOnConfirm: false,
                     closeOnCancel: false
                     },
                     function(e){
                        if(e==true){
                           if(status == 0){
                              $.ajax({
                                 type:'POST',
                                 url: "{{ url('/')}}"+"/candidates/jaf/data-verified",
                                 data: {
                                    "_token": "{{ csrf_token() }}",
                                    'id':check_id,
                                 },        
                                 success: function (response) {  
                                    if(response.success==true)
                                    {     
                                       current_data.attr('data-v', '1');
                                       current_data.removeClass('btn-info');
                                       current_data.addClass('btn-success');
                                       current_data.text('Data Verified');
                                       $('#verified-input-checkbox-'+check_id).val('1'); 
                                       
                                    }       
                                    else
                                    {
                                       current_data.attr('data-v', '0');
                                       current_data.removeClass('btn-success');
                                       current_data.addClass('btn-info');
                                       current_data.text('Data Verified ?');
                                       $('#verified-input-checkbox-'+check_id).val('0');   
                                    
                                       toastr.error("Before Verifying the Data, Please Clear the Insufficiency First !!");
                                       //current_data.prop('checked',false);
                                    }
                                 },
                                 error: function (xhr, textStatus, errorThrown) {
                                       // alert("Error: " + errorThrown);
                                 }
                              });
                           }
                           else
                           {
                                 current_data.attr('data-v', '0');
                                 current_data.removeClass('btn-success');
                                 current_data.addClass('btn-info');
                                 current_data.text('Data Verified ?');
                                 $('#verified-input-checkbox-'+check_id).val('0');   
                                    
                           }
                           swal.close();
                          
                        }
                        else{
                           swal.close();
                        }
                  });
            // if (r == true){
            //    // $(this).attr('disabled','disabled');
            //    // alert('mil gyi id ?'+ check_id);
            // }
      });

$(document).on('click','.clickReorder',function(){ 
     imageId     = $(this).attr('add-imageId');
     imageType = $(this).attr('data-imageType');
     $('#jafImageId').val(imageId);
     $('#jafImageType').val(imageType);

     // alert(imageType);
     $.ajax({
           type:'GET',
           url: "{{url('/candidates/jaf/rearrange')}}",
            data: {'imageId':imageId,'imageType':imageType},        
           success: function (response) {        
           console.log(response);

           $('.gallery').html(response);
           $('#myDragModal').modal({
                 backdrop: 'static',
                 keyboard: false
             });
           // if (response.status=='ok') {            
             
             
           // } else {

           //    alert('No data found');

           // }
           $("ul.reorder-photos-list").sortable({   
             tolerance: 'pointer',
             update: function( event, ui ) {
                 updateOrder();
             }
         });  
           // $('.reorder_link').html('save reordering');
           // $('.reorder_link').attr("id","saveReorder");
           // $('#reorderHelper').slideDown('slow');
           $('.image_link').attr("href","javascript:void(0);");
           $('.image_link').css("cursor","move");
           // update: function( event, ui ) {
           //   updateOrder();
           // }
          
       },
       error: function (xhr, textStatus, errorThrown) {
           alert("Error: " + errorThrown);
       }
     });
     // $('#myDragModal').modal();

});

function updateOrder() {    
      //  console.log('good going');
       
      imageIds= $('#jafImageId').val(); 
      jafImageTypes=$('#jafImageType').val(); 
        var item_order = new Array();
        $('ul.reorder-photos-list li').each(function() {
          // console.log('good going');
            item_order.push($(this).attr("id"));
        });
        // var order_string =item_order;
        $.ajax({
            type: "GET",
            url: "{{url('/candidates/jaf/rearrange/save')}}",
            data: { "order_number":item_order,'imageIds':imageIds,'jafImageTypes':jafImageTypes },
            cache: false,
            success: function(data){ 
              if (data.fail == false) {
               if ( data.attachment_type=='main') {
                  $("#fileResult1"+"-"+data.jaf_id).html("");
                  var count = Object.keys(data.data).length;
                  // console.log(count);
                  for(var i=0; i < count; i++)
                  {
                  
                        // $("#"+fileResult+"-"+dynamicID).prepend("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['filePrev']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.data[i]['file_id']+"'></div>");
                        $("#fileResult1"+"-"+data.jaf_id).append("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['fileIcon']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.jaf_id+'-'+data.data[i]['file_id']+"'></div>");
                  
                  }
               }
               else
               {
                  $("#fileResult2"+"-"+data.jaf_id).html("");
                  var count = Object.keys(data.data).length;
                  // console.log(count);
                  for(var i=0; i < count; i++)
                  {
                  
                        // $("#"+fileResult+"-"+dynamicID).prepend("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['filePrev']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.data[i]['file_id']+"'></div>");
                        $("#fileResult2"+"-"+data.jaf_id).append("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['fileIcon']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.jaf_id+'-'+data.data[i]['file_id']+"'></div>");
                  
                  }
               }
              }
              
            }
        });
}

$(document).on('click','.image-area > img',function(){ 
            
    var img_src =  $(this).attr("src");
    
    $('.image-modal-content').attr('src',img_src);
    $('#myImageModal').modal();
    
});
$(document).on('click','.closeImage',function(){ 
    $('#myImageModal').modal('hide');
    // $('#myImageModal').css("display", "none");closeDragImage
});
$(document).on('click','.closeDragImage',function(){ 
    $('#myDragModal').modal('hide');
    // $('#myImageModal').css("display", "none");closeDragImage
});

$(document).on('click', '.raise_insuff', function (event) {
            $('#can_id').val("");
            $('#ser_name').text('Verification - '+"");
            $('#ser_id').val("");
            $('#jaf_id').val("");
            var can_id=$(this).attr('candidate-id');
            var ser_id=$(this).attr('service-id');
            var jaf_id=$(this).attr('jaf-id');
            var ser_name=$(this).attr('service-name');
            $('#can_id').val(can_id);
            $('#ser_name').text('Verification - '+ser_name);
            $('#ser_id').val(ser_id);
            $('#jaf_id').val(jaf_id);

            // alert(jaf_id);

            $.ajax(
            {
               url: "{{ url('/') }}"+'/candidates/setData/?jaf_id='+jaf_id+'&candidate_id='+can_id+'&service_id='+ser_id,
               type: "get",
               datatype: "html",
            })
            .done(function(data)
            {
               console.log(data);
               $('#raise_modal').modal({
                  backdrop: 'static',
                  keyboard: false
               });
               
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
               //alert('No response from server');
            });
            
         });

 $(document).on('click','.closeraisemdl',function(event){
            $("#comments").val("");
            $("#comment").val("");
            $("#attachments").val("");
            $("#attachment").val("");
            $('.error-container').html('');
            $('.form-control').removeClass('border-danger');

            // $.ajax(
            // {
            //    url: "{{ url('/') }}"+'/candidates/sessionForget',
            //    type: "get",
            //    datatype: "html",
            // })
            // .done(function(data)
            // {
            //    console.log(data);
            // })
            // .fail(function(jqXHR, ajaxOptions, thrownError)
            // {
            //    //alert('No response from server');
            // });
});

 $(document).on('submit', 'form#raise_insuff_form', function (event) {
                        
               $("#overlay").fadeIn(300);　
               event.preventDefault();
               var form = $(this);
               var data = new FormData($(this)[0]);
               var url = form.attr("action");
               var btn = $(this);
               var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
               $('.error-container').html('');
               $('.form-control').removeClass('border-danger');
               $('.raise_submit').attr('disabled',true);
               $('.closeinsuffraise').attr('disabled',true);
               if ($('.raise_submit').html() !== loadingText) {
                     $('.raise_submit').html(loadingText);
               }
               $.ajax({
                     type: form.attr('method'),
                     url: url,
                     data: data,
                     cache: false,
                     contentType: false,
                     processData: false,
                     success: function (data) {
                        console.log(data);
                        window.setTimeout(function(){
                           $('.raise_submit').attr('disabled',false);
                           $('.closeinsuffraise').attr('disabled',false);
                           $('.raise_submit').html('Submit');
                        },2000);
                        $('.error-container').html('');
                        if (data.fail && data.error_type == 'validation') {
                                 //$("#overlay").fadeOut(300);
                                 for (control in data.errors) {
                                 // $('textarea[comments=' + control + ']').addClass('is-invalid');
                                 $('.'+control).addClass('border-danger');
                                 $('#error-' + control).html(data.errors[control]);
                                 }
                        } 
                        //  if (data.fail && data.error == 'yes') {
                           
                        //      $('#error-all').html(data.message);
                        //  }
                        if (data.fail == false) {
                           // $('#send_otp').modal('hide');
                           // alert(data.id);
                           if(data.success){
                           toastr.success("Mail is Sent Successfully");
                           toastr.error("Insuff is Raised");
                              // redirect to google after 5 seconds
                              window.setTimeout(function() {
                              location.reload(); 
                              }, 2000);
                           // window.location.href='{{ Config::get('app.admin_url')}}/aadharchecks/show';
                           //  location.reload();
                           }
                           else
                           {
                              toastr.error("Something Went Wrong!!");
                           } 
                        }
                     },
                     error: function (xhr, textStatus, errorThrown) {
                        
                        alert("Error: " + errorThrown);

                     }
               });
               event.stopImmediatePropagation();
               return false;
            
 });

$(document).on('change','.reference_type',function(){
            var _this=$(this);
            var id = _this.attr('data-id');
            var jaf_id = _this.attr('data-jaf');
            var type = _this.val();
            if(type!='')
            {
               $.ajax({
                     type:'POST',
                     url: "{{route('/jaf/reference_form')}}",
                     data: {"_token": "{{ csrf_token() }}","id":id,"type":type},        
                     success: function (response) {        
                     // console.log(response);

                     $('#reference_result-'+jaf_id).html(response);
                  },
                  error: function (data) {
                     // alert("Error: " + errorThrown);
                  }
               });
            }
            else
            {

               swal({
                  title: "Please Select The Reference Type !!",
                  text: '',
                  type: 'warning',
                  buttons: true,
                  dangerMode: true,
                  confirmButtonColor:'#003473'
               });

               $('#reference_result-'+jaf_id).html('');

               // _this.attr('selectedIndex', '-1');
            }
});



 $(document).on('change','.new_ref_type',function(event){
            var _this=$(this);
            var id = _this.attr('data-id');
            var type = _this.val();
            
            if(type!='')
            {
               // alert(type);
               $.ajax({
                     type:'POST',
                     url: "{{route('/candidates/new_service/reference_form')}}",
                     data: {"_token": "{{ csrf_token() }}","id":id,"type":type},        
                     success: function (response) {        
                     // console.log(response);

                     $('.new_ref_data').html(response);
                  },
                  error: function (data) {
                     // alert("Error: " + errorThrown);
                  }
               });
            }
            else
            {
               swal({
                  title: "Please Select The Reference Type !!",
                  text: '',
                  type: 'warning',
                  buttons: true,
                  dangerMode: true,
                  confirmButtonColor:'#003473'
               });
               $('.new_ref_data').html('');
            }


});

$(document).on('click','.jaf-ready-report',function(event){
            var current_data = $(this);
            var status = $(this).prop('checked');
            var ver_check_length = $('.verified_data:checked').length;
         
            var r =swal({
                        title: "Are you sure?",
                        text: "While confirming this status, please make sure about Verification data or attachment submitted!",
                        type: "warning",
                        dangerMode: true,
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "YES",
                        cancelButtonText: "CANCEL",
                        closeOnConfirm: false,
                        closeOnCancel: false
                        },
                        function(e){
                           //Use the "Strict Equality Comparison" to accept the user's input "false" as string)
                           // if check the checkox
                           if (status== true) {
                              if (e===false) {
                                 current_data.prop('checked',false);
                                 // toastr.success("New Check added  successfully");
                                    // redirect to google after 5 seconds
                                    swal.close();
                              // console.log("Do here everything you want");
                              } else {

                                 if(ver_check_length >= 1)
                                    current_data.prop('checked',true);
                                 else
                                 {
                                    toastr.error("Before Marking for Report, Atleast One Check Must be Data Verified !!");
                                    current_data.prop('checked',false);
                                 }
                                 swal.close();
                                 // swal("Oh no...");
                                 // console.log("The user says: ",e);
                              }
                              
                           } // if uncheck the checkox
                           else {
                              if (e===false) {
                                 current_data.prop('checked',true);
                              // swal("Ok done!","!");
                              swal.close();
                              // console.log("Do here everything you want");
                              } else {
                                 current_data.prop('checked',false);
                                 // swal("Oh no...");
                                 swal.close();
                                 // console.log("The user says: ",e);
                              }
                           }
                     
                     }
                     );
});

          //Add Additional charges
$('.address-verification-data').click(function(){
    var _this = $(this);
    var id = _this.attr('data-id');
    $('#addr_ver_mdl').modal({
        backdrop: 'static',
        keyboard: false
    });
    $.ajax({
        type: 'POST',
        url: "{{ url('/candidates/address_verification_data')}}",
        data: {'id':id,'_token': '{{csrf_token()}}'},        
        success: function (data) {
            if(data !='null' &&  data.result!=null)
            { 
                var candidate_name = data.user!=null ? data.user.name+' ('+data.user.display_id+')' : 'N/A';
                var check_name = data.result.service_name+' - '+data.result.check_item_number;
                var submit_at = data.submitted_at;
                var first_name = data.result.first_name!=null ? data.result.first_name : 'N/A';
                var last_name = data.result.last_name!=null ? data.result.last_name : 'N/A';
                var email =  data.result.email!=null ? data.result.email : 'N/A';
                var phone = data.result.phone!=null ? data.result.phone : 'N/A';
                var address_1 = data.result.address_line1!=null ? data.result.address_line1 : 'N/A';
                var address_2 = data.result.address_line2!=null ? data.result.address_line2 : 'N/A';
                var pincode = data.result.zipcode!=null ? data.result.zipcode : 'N/A';
                var country = data.result.country_name!=null ? data.result.country_name : 'N/A';
                var state = data.result.state_name!=null ? data.result.state_name : 'N/A';
                var city = data.result.city_name!=null ? data.result.city_name : 'N/A';
                var address_type = data.result.address_type!=null ? data.result.address_type : 'N/A';
                var ownership_type = data.result.ownership_type!=null ? data.result.ownership_type : 'N/A';
                var landmark = data.result.landmark!=null ? data.result.landmark : 'N/A';
                // var nature_of_residence = data.result.nature_of_residence!=null ? data.result.nature_of_residence : 'N/A';
                // var verifier_name = data.result.verifier_name!=null ? data.result.verifier_name : 'N/A';
                // var relation_with_verifier = data.result.relation_with_verifier!=null ? data.result.relation_with_verifier : 'N/A';
                var period_stay_from = data.result.period_stay_from!=null && data.result.period_stay_from!='' ? data.result.period_stay_from : 'N/A';
                var period_stay_to = data.result.period_stay_to!=null && data.result.period_stay_to!='' ? data.result.period_stay_to : 'N/A';

                $('span.candidate_name').html(candidate_name);
                $('span.check_name').html(check_name);
                $('span.submit_at').html(submit_at);

                $('span.first_name').html(first_name);
                $('span.last_name').html(last_name);
                $('span.email').html(email);
                $('span.phone').html(phone);
                $('span.address_1').html(address_1);
                $('span.address_2').html(address_2);
                $('span.pincode').html(pincode);
                $('span.country').html(country);
                $('span.state').html(state);
                $('span.city').html(city);
                $('span.address_type').html(address_type);
                $('span.ownership_type').html(ownership_type);
                $('span.landmark').html(landmark);
                // $('span.nature_of_residence').html(nature_of_residence);
                // $('span.verifier_name').html(verifier_name);
                // $('span.relation_with_verifier').html(relation_with_verifier);
                $('span.period_stay_from').html(period_stay_from);
                $('span.period_stay_to').html(period_stay_to);

                $('div.add_attach_data').html(data.form);
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            // alert("Error: " + errorThrown);
        }
    });
});

$('.form-link').click(function (e) {
    e.preventDefault();
    var _this = $(this);
    var copyText = $(this).attr('href');

    document.addEventListener('copy', function(e) {
        e.clipboardData.setData('text/plain', copyText);
        e.preventDefault();
    }, true);

    document.execCommand('copy'); 
    _this.attr('data-original-title','Copied').tooltip('show');

});

$('.form-link').mouseout(function (e) {
    e.preventDefault();
    var _this = $(this);
    _this.attr('data-original-title','Copy Link');
});

          var curNum ='';
          var fileResult='fileResult1';
          var type = 'main';
          var number = '1';
          $(document).on('click','.clickSelectFile',function(){ 
            curNum     = $(this).attr('add-id');
            fileResult = $(this).attr('data-result');
            type = $(this).attr('data-type');
            number = $(this).attr('data-number');
            //  alert(fileResult);
            $(this).next('input[type="file"]').trigger('click');
          });
          //
          $(document).on('change','.fileupload',function(e){        
            uploadFile(curNum,fileResult,type,number);
          });

          //remove file
         $(document).on('click','.remove-image',function(){ 

            // var r = confirm("Are you want to remove?");
            // if (r == true) {
            // $('#fileupload-'+curNum).val("");
            // var current = $(this);
            // var file_id = $(this).attr('data-id');
            // //
            // var fd = new FormData();

            // fd.append('file_id',file_id);
            // fd.append('_token', '{{csrf_token()}}');
            // //
            // $.ajax({
            //       type: 'POST',
            //       url: "{{ url('/jaf/remove/file') }}",
            //       data: fd,
            //       processData: false,
            //       contentType: false,
            //       success: function(data) {
            //          console.log(data);
            //          if (data.fail == false) {
            //          //reset data
            //          $('.fileupload').val("");
            //          //append result
            //          $(current).parent('.image-area').detach();
            //          } else {
                     
            //          console.log("file error!");
                     
            //          }
            //       },
            //       error: function(error) {
            //          console.log(error);
            //          // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
            //       }
            // });

            // return false;

            // }
            var current = $(this);
            var file_id = $(this).attr('data-id');
            swal({
                  // icon: "warning",
                  type: "warning",
                  title: "Are You Want to Remove?",
                  text: "",
                  dangerMode: true,
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "YES",
                  cancelButtonText: "CANCEL",
                  closeOnConfirm: false,
                  closeOnCancel: false
                  },
                  function(e){
                     if(e==true)
                     {
                        var fd = new FormData();

                        fd.append('file_id',file_id);
                        fd.append('_token', '{{csrf_token()}}');

                        $.ajax({
                              type: 'POST',
                              url: "{{ url('/jaf/remove/file') }}",
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
                                 } else {
                                 
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
                  }
            );

         });

         $(document).on('click','.digital_verification',function (event) {
            var current_data = $(this);
            var check_id = $(this).attr('data-digital_id');
            var status = $(this).prop('checked');
            var r =swal({
                        // icon: "warning",
                        type: "warning",
                        title: "Are you sure?",
                        text: "How it will be work?\n 1. Address Verification Link will send on candidates email.\n 2. In case candidate does not have email then Text SMS will send with APP link.\n 3. Candidate will login and submit the require data.!",
                        
                        // buttons: ["Cancel", "Yes"],
                        dangerMode: true,
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "YES",
                        cancelButtonText: "CANCEL",
                        closeOnConfirm: false,
                        closeOnCancel: false
                        },
                        function(e){
                        //Use the "Strict Equality Comparison" to accept the user's input "false" as string)
                        // if check the checkox
                        if (status== true) {
                            if (e===false) {
                            current_data.prop('checked',false);
                            // toastr.success("New Check added  successfully");
                                // redirect to google after 5 seconds
                                swal.close();
                            // console.log("Do here everything you want");
                            } else {
                            current_data.prop('checked',true);
                            swal.close();
                            // swal("Oh no...");
                            // console.log("The user says: ",e);
                            }
                            
                        } // if uncheck the checkox
                        else {
                            if (e===false) {
                            current_data.prop('checked',true);
                            // swal("Ok done!","!");
                            swal.close();
                            // console.log("Do here everything you want");
                            } else {
                            current_data.prop('checked',false);
                            // swal("Oh no...");
                            swal.close();
                            // console.log("The user says: ",e);
                            }
                        }
                        
            });
                        // alert('mil gyi id ?');
            
         });

         $(document).on('click','.send-form-link',function(e){
            var _this = $(this);
            var jaf_id = _this.attr('data-id');
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            swal({
                  // icon: "warning",
                  type: "warning",
                  title: "Are You Sure Want to Send the Address Verification Form Link ?",
                  text: "",
                  dangerMode: true,
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "YES",
                  cancelButtonText: "CANCEL",
                  closeOnConfirm: false,
                  closeOnCancel: false
                  },
                  function(e){
                     if(e==true)
                     {
                        $.ajax({
                           type:'POST',
                           url: "{{route('/candidates/address-verification-form-link')}}",
                           data: {'_token' : '{{csrf_token()}}','jaf_id':jaf_id}, 
                           beforeSend: function() {
                                //something before send
                                _this.html(loadingText).fadeIn(300);
                                _this.attr('disabled',true);
                                _this.addClass('disabled-link');
                            },       
                           success: function (response) {        
                              if (response.status==true) {  

                                 _this.html('<i class="far fa-envelope"></i> Send Form Link');
                                 _this.attr('disabled',false);
                                 _this.removeClass('disabled-link');

                                 toastr.success("Message Has Been Sent Successfully !!");

                                 window.setTimeout(function(){
                                        window.location.reload();
                                 },2000);
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
                        swal.close();
                     }
                     else
                     {
                        swal.close();
                     }
                  }
            );
         });

         $(document).on('click','.send-app-link',function(e){
            var _this = $(this);
            var jaf_id = _this.attr('data-id');
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            swal({
                  // icon: "warning",
                  type: "warning",
                  title: "Are You Sure Want to Send the Address Verification App Link ?",
                  text: "",
                  dangerMode: true,
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "YES",
                  cancelButtonText: "CANCEL",
                  closeOnConfirm: false,
                  closeOnCancel: false
                  },
                  function(e){
                     if(e==true)
                     {
                        $.ajax({
                           type:'POST',
                           url: "{{route('/candidates/address-verification-app-link')}}",
                           data: {'_token' : '{{csrf_token()}}','jaf_id':jaf_id}, 
                           beforeSend: function() {
                                //something before send
                                _this.html(loadingText).fadeIn(300);
                                _this.attr('disabled',true);
                                _this.addClass('disabled-link');
                            },       
                           success: function (response) {        
                              if (response.status==true) {  

                                 _this.html('<i class="far fa-envelope"></i> Send App Link');
                                 _this.attr('disabled',false);
                                 _this.removeClass('disabled-link');

                                 toastr.success("Message Has Been Sent Successfully !!");

                                 window.setTimeout(function(){
                                        window.location.reload();
                                 },2000);
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
                        swal.close();
                     }
                     else
                     {
                        swal.close();
                     }
                  }
            );
         });

         $(document).on('click','.send-link-mail',function(e){
            var _this = $(this);
            var jaf_id = _this.attr('data-id');
            var candidate_id = _this.attr('data-candidate_id');
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            swal({
                  // icon: "warning",
                  type: "warning",
                  title: "Are You Sure Want to Send the Address Verification Link ?",
                  text: "",
                  dangerMode: true,
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "YES",
                  cancelButtonText: "CANCEL",
                  closeOnConfirm: false,
                  closeOnCancel: false
                  },
                  function(e){
                     if(e==true)
                     {
                        $.ajax({
                           type:'POST',
                           url: "{{route('/candidates/check-email-exist')}}",
                           data: {'_token' : '{{csrf_token()}}','id':candidate_id}, 
                           beforeSend: function() {
                                //something before send
                                _this.html(loadingText).fadeIn(300);
                                _this.attr('disabled',true);
                                _this.addClass('disabled-link');
                            },       
                           success: function (response) {        
                              if (response.status==true) {  

                                 if(response.alternative_email==false)
                                 {
                                    var url = "{{route('/candidates/address-verification-link-mail')}}";

                                    $('#address_ver_mail_frm').attr('action',url);

                                    $('#address_ver_mail_frm')[0].reset();
                                    
                                    $('#address_ver_mail_frm').find('#jaf_id').val(jaf_id);
                                    $('#address_ver_mail_frm').find('#candidate_id').val(candidate_id);

                                    $('#address_ver_mail_frm').find('.candidate_name').html(response.result.name + ' - (' + response.result.display_id+')');

                                    $('#address_ver_mail_frm').find('#email').val(response.result.email);

                                    if(response.result.email!=null)
                                    {
                                       $('#address_ver_mail_frm').find('#email').attr('readonly',true);
                                    }

                                    $('.error-container').html('');
                                    $('.form-control').removeClass('border-danger');

                                    $('#candidate_email_mdl').modal({
                                       backdrop: 'static',
                                       keyboard: false
                                    });

                                    _this.html('<i class="far fa-envelope"></i> Digital Address Verification through Mail');
                                    _this.attr('disabled',false);
                                    _this.removeClass('disabled-link');
                                 }
                                 else if(response.alternative_email==true)
                                 {  
                                    verificationLinkMail(_this,jaf_id,candidate_id);
                                 }

                                

                                 // window.setTimeout(function(){
                                 //       window.location.reload();
                                 // },2000);
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
                        swal.close();
                     }
                     else
                     {
                        swal.close();
                     }
                  }
            );
         });

         $(document).on('submit', 'form#address_ver_mail_frm', function (event) {
               $("#overlay").fadeIn(300);　
               event.preventDefault();
               var form = $(this);
               var data = new FormData($(this)[0]);
               var url = form.attr("action");
               var $btn = $(this);
               var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
               $('.error-container').html('');
               $('.form-control').removeClass('border-danger');
               $('.btn-disable').attr('disabled',true);
               if ($('.btn-submit').html() !== loadingText) {
                     $('.btn-submit').html(loadingText);
               }
               $.ajax({
                  type: form.attr('method'),
                  url: url,
                  data: data,
                  cache: false,
                  contentType: false,
                  processData: false,
                  success: function (data) {
                     window.setTimeout(function(){
                        $('.btn-disable').attr('disabled',false);
                        $('.btn-submit').html('Submit');
                     },2000);
                     if(data.status==true)
                     {
                        toastr.success("Mail Has Been Sent Successfully !!");

                        window.setTimeout(function(){
                              window.location.reload();
                        },2000);
                     }
                     else if(data.status==false)
                     {
                        for (control in data.errors) {
                           $('#address_ver_mail_frm').find('input[name='+control+']').addClass('border-danger');
                           $('#address_ver_mail_frm').find('.error-' + control).html(data.errors[control]);
                        }
                     }
                  },
                  error: function (data) {
                        
                     console.log(data);

                  }
                  // error: function (xhr, textStatus, errorThrown) {
                        
                  //       alert("Error: " + errorThrown);

                  // }
               });
               return false;
         });

         $(document).on('click','.send-link-sms',function(e){
            var _this = $(this);
            var jaf_id = _this.attr('data-id');
            var candidate_id = _this.attr('data-candidate_id');
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            swal({
                  // icon: "warning",
                  type: "warning",
                  title: "Are You Sure Want to Send the Address Verification Link ?",
                  text: "",
                  dangerMode: true,
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "YES",
                  cancelButtonText: "CANCEL",
                  closeOnConfirm: false,
                  closeOnCancel: false
                  },
                  function(e){
                     if(e==true)
                     {
                        $.ajax({
                           type:'POST',
                           url: "{{route('/candidates/address-verification-link-sms')}}",
                           data: {'_token' : '{{csrf_token()}}','jaf_id':jaf_id,'candidate_id':candidate_id}, 
                           beforeSend: function() {
                                //something before send
                                _this.html(loadingText).fadeIn(300);
                                 _this.attr('disabled',true);
                                 _this.addClass('disabled-link');
                            },       
                           success: function (response) {
                            
                                 _this.html('<i class="far fa-envelope"></i> Digital Address Verification through SMS');
                                 _this.attr('disabled',false);
                                 _this.removeClass('disabled-link');        
                              if (response.status==true) {  

                                 toastr.success("Message Has Been Sent Successfully !!");

                                 window.setTimeout(function(){
                                    window.location.reload();
                                 },2000);
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
                        swal.close();
                     }
                     else
                     {
                        swal.close();
                     }
                  }
            );
         });

         $(document).on('click','.btn-mail-close',function(){
            $('#candidate_email_mdl').modal('hide');
         });


         $(document).on('click','.digital-ver-log',function(e){
            var _this = $(this);
            var jaf_id = _this.attr('data-id');
            var candidate_id = _this.attr('data-candidate_id');
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            $.ajax({
                        type:'POST',
                        url: "{{route('/candidates/digital-address-verification-log')}}",
                        data: {'_token' : '{{csrf_token()}}','jaf_id':jaf_id,'candidate_id':candidate_id}, 
                        beforeSend: function() {
                           //something before send
                           _this.html(loadingText).fadeIn(300);
                           _this.attr('disabled',true);
                           _this.addClass('disabled-link');
                        },
                        success: function(response){
                           _this.html('Digital Address Verification Log');
                           _this.attr('disabled',false);
                           _this.removeClass('disabled-link');

                           if(response.success)
                           {
                              $('#digital_ver_mdl').find('.candidate_name').html(response.result.name+' ('+response.result.display_id+')');
                              $('#digital_ver_mdl').find('.check_name').html(response.result.service_name+' - '+response.result.check_item_number);
                              $('#digital_ver_mdl').find('.action_data').html(response.html);

                              $('#digital_ver_mdl').modal({
                                 backdrop: 'static',
                                 keyboard: false
                              });
                           }
                        },
                        error: function (xhr, textStatus, errorThrown) {
                              // alert("Error: " + errorThrown);
                              console.log(errorThrown);
                        }
                  });

         });

         $(document).on('click','.btn-digital-close',function(){
            $('#digital_ver_mdl').modal('hide');
         });

         $(document).on('click','.send-link-sms-mail',function(e){
            var _this = $(this);
            var jaf_id = _this.attr('data-id');
            var candidate_id = _this.attr('data-candidate_id');
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            swal({
                  // icon: "warning",
                  type: "warning",
                  title: "Are You Sure Want to Send the Address Verification Link ?",
                  text: "",
                  dangerMode: true,
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "YES",
                  cancelButtonText: "CANCEL",
                  closeOnConfirm: false,
                  closeOnCancel: false
                  },
                  function(e){
                     if(e==true)
                     {
                        $.ajax({
                           type:'POST',
                           url: "{{route('/candidates/check-email-exist')}}",
                           data: {'_token' : '{{csrf_token()}}','id':candidate_id}, 
                           beforeSend: function() {
                                //something before send
                                _this.html(loadingText).fadeIn(300);
                                 _this.attr('disabled',true);
                                 _this.addClass('disabled-link');
                           },       
                           success: function (response) {  
                               
                                 _this.html('<i class="far fa-envelope"></i> Digital Address Verification through SMS & Mail');
                                 _this.attr('disabled',false);
                                 _this.removeClass('disabled-link');
     
                              if (response.status==true) {  

                                 if(response.alternative_email==false)
                                 {
                                    var url = "{{route('/candidates/address-verification-link-sms-mail')}}";

                                    $('#address_ver_mail_frm').attr('action',url);

                                    $('#address_ver_mail_frm')[0].reset();
                                    
                                    $('#address_ver_mail_frm').find('#jaf_id').val(jaf_id);
                                    $('#address_ver_mail_frm').find('#candidate_id').val(candidate_id);

                                    $('#address_ver_mail_frm').find('.candidate_name').html(response.result.name + ' - (' + response.result.display_id+')');

                                    $('#address_ver_mail_frm').find('#email').val(response.result.email);

                                    if(response.result.email!=null)
                                    {
                                       $('#address_ver_mail_frm').find('#email').attr('readonly',true);
                                    }

                                    $('.error-container').html('');
                                    $('.form-control').removeClass('border-danger');

                                    $('#candidate_email_mdl').modal({
                                       backdrop: 'static',
                                       keyboard: false
                                    });

                                    _this.html('<i class="far fa-envelope"></i> Digital Address Verification through SMS & Mail');
                                    _this.attr('disabled',false);
                                    _this.removeClass('disabled-link');
                                 }
                                 else if(response.alternative_email==true)
                                 {  
                                    verificationLinkMailSMS(_this,jaf_id,candidate_id);
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
                        swal.close();
                     }
                     else
                     {
                        swal.close();
                     }
                  }
            );
         });

         $(document).on('change','.digital-select',function(){

            var _this = $(this);

            var id= _this.attr('data-id');

            if(_this.val()!='')
            {
            $('.form-link-div-'+id).removeClass('d-none');

            $('.digital-data-div-'+id).removeClass('d-none');

            $('.digital-ver-div-'+id).removeClass('d-none');

            $('.send-sms-div-'+id).removeClass('d-none');

            $('.send-mail-div-'+id).removeClass('d-none');

            $('.send-sms-mail-div-'+id).removeClass('d-none');

            $('.digital-ver-log-div-'+id).removeClass('d-none');
            }
            else
            {

            $('.form-link-div-'+id).addClass('d-none');

            $('.digital-data-div-'+id).addClass('d-none');

            $('.digital-ver-div-'+id).addClass('d-none');

            $('.send-sms-div-'+id).addClass('d-none');

            $('.send-mail-div-'+id).addClass('d-none');

            $('.send-sms-mail-div-'+id).addClass('d-none');

            $('.digital-ver-log-div-'+id).addClass('d-none');
            }

         });

function uploadFile(dynamicID,fileResult,type,number){
     
     $("#fileUploadProcess").html("<img src='{{asset('images/process-horizontal.gif')}}'  >"); 
     $('.bcd_loading').css('display', 'block');
     // die;
     var fd = new FormData();

     var jaf_id=$('#jaf_id').val();

     // alert(fd);
     var ins = document.getElementById("file"+number+"-"+dynamicID).files.length;
     // alert(ins);
     for (var x = 0; x < ins; x++) {
        fd.append("files[]", document.getElementById("file"+number+"-"+dynamicID).files[x]);
     }

     fd.append('candidate_id',"{{ base64_encode($candidate->id) }}");
     fd.append('business_id',"$candidate->business_id")
     fd.append('jaf_id',dynamicID);
     fd.append('type',type);
     fd.append('_token', '{{csrf_token()}}');
     //
     $.ajax({
           type: 'POST',
           url: "{{ url('/jaf/upload/file') }}",
           data: fd,
           processData: false,
           contentType: false,
           success: function(data) {
              window.setTimeout(function(){
                   $('.bcd_loading').css('display', 'none');
                     },2000);
           console.log(data);
           if (data.fail == false) {
           //reset data
           $('.fileupload').val("");
           $("#fileUploadProcess").html("");
           //append result

           var count = Object.keys(data.data).length;

           for(var i=0; i < count; i++)
           {
              if(data.data[i]['file_type']=='pdf')
              {
                 $.each(data.data[i]['file_id'],function(key,value){
                       // $("#"+fileResult+"-"+dynamicID).prepend("<div class='image-area' data-id='"+value+"'><img src='"+data.data[i]['filePrev'][key]+"'  alt='Preview' title='"+data.data[i]['file_name'][key]+"'><a class='remove-image' href='javascript:;' data-id='"+value+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+value+"'></div>");
                       $("#"+fileResult+"-"+dynamicID).append("<div class='image-area' data-id='"+value+"'><img src='"+data.data[i]['filePrev'][key]+"'  alt='Preview' title='"+data.data[i]['file_name'][key]+"'><a class='remove-image' href='javascript:;' data-id='"+value+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+value+"'></div>");
                 });
              }
              else
              {
                 // $("#"+fileResult+"-"+dynamicID).prepend("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['filePrev']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.data[i]['file_id']+"'></div>");
                 $("#"+fileResult+"-"+dynamicID).append("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['filePrev']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.data[i]['file_id']+"'></div>");
              }
           }

           // $.each(data.data, function(key, value) {
           //       $("#"+fileResult+"-"+dynamicID).append("<div class='image-area' ><img src='"+value.filePrev+"'  alt='Preview'><a class='remove-image' data-id='"+value.file_id+"' href='javascript:;' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+value.file_id+"'></div>");
           // });
                 
           } else {
              $("#fileUploadProcess").html("");
              // alert("Please upload valid file! allowed file type, Image JPG, PNG etc. ");
              swal({
                 title: "Oh no!",
                 text: 'Please upload valid file! allowed file type, Image JPG, PNG, PDF etc.',
                 type: 'error',
                 buttons: true,
                 dangerMode: true,
                 confirmButtonColor:'#003473'
              });
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

function verificationLinkMail(_this,jaf_id,candidate_id)
{
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';

        $.ajax({
                type:'POST',
                url: "{{route('/candidates/address-verification-link-mail')}}",
                data: {'_token' : '{{csrf_token()}}','jaf_id':jaf_id,'candidate_id':candidate_id}, 
                beforeSend: function() {
                    //something before send
                    _this.html(loadingText).fadeIn(300);
                    _this.attr('disabled',true);
                    _this.addClass('disabled-link');
                }, 
                success: function (response) {
                    if (response.status==true) {

                        toastr.success("Mail Has Been Sent Successfully !!");
                        _this.html('<i class="far fa-envelope"></i> Digital Address Verification through Mail');
                        _this.attr('disabled',false);
                        _this.removeClass('disabled-link');

                        window.setTimeout(function(){
                            window.location.reload();
                        },2000);
                    }
                    else
                    {
                        toastr.error("Something Went Wrong !!");
                    }   
                },
                error: function (response) {
                        // alert("Error: " + errorThrown);
                        console.log(response);
                }
        });
}

function verificationLinkMailSMS(_this,jaf_id,candidate_id)
{
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';

        $.ajax({
                type:'POST',
                url: "{{route('/candidates/address-verification-link-sms-mail')}}",
                data: {'_token' : '{{csrf_token()}}','jaf_id':jaf_id,'candidate_id':candidate_id}, 
                beforeSend: function() {
                    //something before send
                    _this.html(loadingText).fadeIn(300);
                    _this.attr('disabled',true);
                    _this.addClass('disabled-link');
                }, 
                success: function (response) {
                    _this.html('<i class="far fa-envelope"></i> Digital Address Verification through SMS & Mail');
                    _this.attr('disabled',false);
                    _this.removeClass('disabled-link');
            
                    if (response.status==true) {  
                        
                        toastr.success("Message Has Been Sent Successfully !!");

                        toastr.success("Mail Has Been Sent Successfully !!");

                        window.setTimeout(function(){
                            window.location.reload();
                        },2000);
                    }
                    else if(response.status==false)
                    {
                        toastr.error("Something Went Wrong !!");
                    }
                },
                error: function (response) {
                        // alert("Error: " + errorThrown);
                        console.log(response);
                }
        });
}

window.onscroll = function() {stickyFun()};

var navbar = document.getElementById("myPillTab");
var sticky = navbar.offsetTop;

function stickyFun() {
 if (window.pageYOffset >= sticky) {
   navbar.classList.add("sticky")
 } else {
   navbar.classList.remove("sticky");
 }
}



</script>

