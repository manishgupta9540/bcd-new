<!DOCTYPE html>
<html>
<head>
{{-- <title>MY BCD</title> --}}
<style>
     @page {
      header: page-header;
      footer: page-footer;
    }
    @font-face {
        font-family: "Roboto-Regular";
        font-weight: normal;
        font-style: normal;
        src: url( "{{ asset('admin/fonts/OpenSans-Regular.ttf') }}" ) format('truetype');
     }
     @font-face {
        font-family: "Roboto-Bold";
        font-weight: normal;
        font-style: normal;
        src: url( "{{ asset('admin/fonts/OpenSans-Regular.ttf') }}" ) format('truetype');
     }
    body {font-family: 'Roboto-Regular', sans-serif;}
    table tr td {font-family: 'Roboto-Regular', sans-serif; text-align: left;}
    #map{
            height:500px;
        }
    footer {
            position: fixed; 
            bottom: 0px; 
            left: -50px; 
            right: -50px;
            height: 80px;
            padding: 0px 30px;

            /** Extra personal styles **/
            /*background-color: #03a9f4;*/
            color: #000000;
            text-align: center;
            line-height: 20px;
        }

        .greenzone {
            background-color: green;
            color: green;
        }

        .redzone {
            background-color: red;
            color: red;
        }

        .zone {
            height: 0px;
            box-shadow: 1px 0px 6px -2px #000;
            padding: 0.5px 8px;
            border-radius: 100%;
        }
        
</style>
</head>
<body>
@php
    $rotate_style = '-ms-transform: rotate(90deg);transform: rotate(90deg);';
@endphp
    <!-- Header table -->
<htmlpageheader name="page-header">
    <table width="100%" style="border-bottom:1px solid #ccc;">
        <tr>
            <td>
                {!! Helper::company_logo($business_id) !!}
            </td>
            <td style="width:50%; text-align:right;">{{Helper::company_sort_name($business_id)}}</td>
        </tr>
    </table>
</htmlpageheader> 
<table width="100%" border="0" style="margin-top: 20px;border-collapse:collapse;">
    <tr>
        <td colspan="4" style="background-color:#eeeeee;font-size: 20px;padding:10px;text-align: center;">Employee Residential Address Verification Report </td>
    </tr>
    <tr>
        <td style="background-color:#105cb0;color:#fff; margin:0px; padding:10px;border:1px solid #ccc;width: 20%;font-size:13px;">Profile Name</td>
        <td colspan="3" style="margin:0px;padding:10px;border:1px solid #ccc;font-size:13px;">{{ Helper::candidate_user_name($address_verification->candidate_id) }}</td>
    </tr>
    <tr>
        <td style="background-color:#105cb0;color: #fff; margin:0px; padding:10px;border:1px solid #ccc;width: 20%;font-size:13px;">Address</td>
        <td colspan="3" style="margin:0px;padding:10px;border:1px solid #ccc;font-size:13px;">{{ $address_verification->full_address}}</td>
    </tr>
    <tr>
        <td style="background-color:#105cb0;color: #fff; margin:0px; padding:10px;border:1px solid #ccc;width: 20%;font-size:13px;">Client Name</td>
        <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 30%;font-size:13px;">{{ Helper::company_name($address_verification->business_id) }}</td>
        <td style="background-color:#105cb0;color: #fff; margin:0px; padding:10px;border:1px solid #ccc;font-size:13px;width:150px">Relation With Candidate</td>
        <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 30%;font-size:13px;">{{ $address_verification->relation_with_verifier!=NULL ? $address_verification->relation_with_verifier : '--'}}</td>
    </tr>
    <tr>
        <td style="background-color:#105cb0;color: #fff; margin:0px; padding:10px;border:1px solid #ccc;font-size:13px;width:150px">Nearest Landmark</td>
        <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 30%;font-size:13px;">{{ $address_verification->landmark!=NULL ? $address_verification->landmark : '--'}}</td>
        <td style="background-color:#105cb0;color: #fff; margin:0px; padding:10px;border:1px solid #ccc;width: 20%;font-size:13px;">Mobile</td>
        <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 30%;font-size:13px;">{{ $address_verification->phone}}</td>
    </tr>
    <tr>
        <td style="background-color:#105cb0;color: #fff; margin:0px; padding:10px;border:1px solid #ccc;width: 20%;font-size:13px;">Reference ID</td>
        <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 30%;font-size:13px;">{{ Helper::candidate_reference_id($address_verification->candidate_id) }}</td>
        <td style="background-color:#105cb0;color: #fff; margin:0px; padding:10px;border:1px solid #ccc;width: 20%;font-size:13px;">Period of Stay</td>
        <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 30%;font-size:13px;">{{$address_verification->period_stay_from!=NULL || $address_verification->period_stay_from!='' ? date('Y-m-d',strtotime($address_verification->period_stay_from)) : '--'}} to {{$address_verification->period_stay_from!=NULL || $address_verification->period_stay_to!='' ? date('Y-m-d',strtotime($address_verification->period_stay_to)) : '--'}}</td>
    </tr>
    <tr>
        <td style="background-color:#105cb0;color: #fff; margin:0px; padding:10px;border:1px solid #ccc;width: 20%;font-size:13px;">Verification Date</td>
        <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 30%;font-size:13px;">{{ date('d-M-Y',strtotime($address_verification->created_at)) }}</td>
        <td style="background-color:#105cb0;color: #fff; margin:0px; padding:10px;border:1px solid #ccc;width: 20%;font-size:13px;">Candidate Name</td>
        <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 30%;font-size:13px;">{{ $address_verification->verifier_name!=NULL ? $address_verification->verifier_name : '--'}}</td>
    </tr>
    <tr>
        <td style="background-color:#105cb0;color: #fff; margin:0px; padding:10px;border:1px solid #ccc;width: 20%;font-size:13px;">Nature of Residence</td>
        <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 30%;font-size:13px;">{{$address_verification->nature_of_residence!=NULL && $address_verification->nature_of_residence!='' ? ucwords($address_verification->nature_of_residence) : '--'}}</td>
        <td style="background-color:#105cb0;color: #fff; margin:0px; padding:10px;border:1px solid #ccc;width: 20%;font-size:13px;">Type of Address</td>
        <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 30%;font-size:13px;">{{$address_verification->address_type!=NULL && $address_verification->address_type!='' ? ucwords($address_verification->address_type) : '--'}}</td>
    </tr>
    <tr>
        <td style="background-color:#105cb0;color: #fff; margin:0px; padding:10px;border:1px solid #ccc;width: 20%;font-size:13px;">Status</td>
        <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 30%;font-size:13px;">
            @if ($verification_decision!=NULL && $verification_decision->qc_decision==1)
                Clear
            @elseif($verification_decision!=NULL && ($verification_decision->qc_decision==0 || $verification_decision->qc_decision==2 || $verification_decision->qc_decision==3 || $verification_decision->qc_decision==4))
                Not Clear
            @else
                Pending
            @endif
        </td>
    </tr>
</table>
<table width="100%" border="0" style="margin-top: 10px;">
    <tr>
        <td colspan="5" style="background-color:#eeeeee;font-size: 20px;padding:10px;text-align: center;">Address Show on the map</td>
    </tr>
    <tr>
        <td class="blue" style="background-color:#105cb0;color:#fff; margin:0px; padding:10px;border:1px solid #ccc;width: 40%;font-size:13px;">Address</td>
        <td class="blue" style="background-color:#105cb0;color:#fff; margin:0px; padding:10px;border:1px solid #ccc;width: 15%;font-size:13px;">Source</td>
        <td class="blue" style="background-color:#105cb0;color:#fff; margin:0px; padding:10px;border:1px solid #ccc;width: 20%;font-size:13px;">Location API</td>
        <td class="blue" style="background-color:#105cb0;color:#fff; margin:0px; padding:10px;border:1px solid #ccc;width: 15%;font-size:13px;">Distance</td>
        <td class="blue" style="background-color:#105cb0;color:#fff; margin:0px; padding:10px;border:1px solid #ccc;width: 10%;font-size:13px;">Legend</td>
    </tr>
    <tr>
        <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 40%;font-size:13px;">
            {{$address_verification->full_address}}
        </td>
        <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 15%;font-size:13px;">Input address</td>
        <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 20%;font-size:13px;">Google Location API</td>
        <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 15%;text-align:center;font-size:13px;" rowspan="2">{{$address_verification->distance}}km</td>
        <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 10%;text-align:center;font-size:13px;"><img src="{{asset('admin/images/red-circle-11.png')}}" width="4.5%"></td>
    </tr>
    <tr>
        <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 40%;font-size:13px;">
            @if($address_verification->map_address!=NULL)
                {{$address_verification->map_address}}<br>
                ({{$address_verification->geo_latitude!=NULL && $address_verification->geo_longitude!=NULL ? $address_verification->geo_latitude.', '.$address_verification->geo_longitude : '--'}})  
            @else
                {{$address_verification->geo_latitude!=NULL && $address_verification->geo_longitude!=NULL ? $address_verification->geo_latitude.', '.$address_verification->geo_longitude : '--'}} 
            @endif
        </td>
        <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 15%;font-size:13px;">GPS</td>
        <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 20%;font-size:13px;">Device Location Logic</td>
        {{-- <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 15%;font-size:13px;">0.459km</td> --}}
        <td style="margin:0px;padding:10px;border:1px solid #ccc;width: 10%;text-align:center;font-size:13px;"><img src="{{asset('admin/images/blue-circle-11.png')}}" width="4.5%"></td>
    </tr>
    
</table>
<table width="100%" border="0" style="margin-top: 0px;">
    <tr>
        <td style="width:100%;">
            {{-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7641.768557269481!2d82.20779852957887!3d16.732624870591742!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a381fd8b6eac763%3A0x641f9ea065f6c4d9!2sYanam%20(Puducherry%20State)%20Post%20Office!5e0!3m2!1sen!2sin!4v1662806713159!5m2!1sen!2sin"  height="300" style="border:0px;width:100%;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> --}}
           
            <img src='{{asset('/uploads/map-image/'.$address_verification->map_image)}}' style="width:100%;height:390px;" />
            
        </td>
    </tr>
 {{--   <tr>
        <td>&nbsp;</td>
    </tr>--}}
</table>
<table width="100%" border="0" style="vertical-align: top;">
    <tr>
        <td colspan="2" style="background-color:#eeeeee;font-size: 40px;padding:10px;text-align: center;">Photographic Evidence</td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0">
                <tr>
                    <td style="background-color:#eeeeee;font-size:35px;padding:10px;text-align:center;">
                        Selfie with Front Door
                        <br> <p style="font-size:25px;">(Name Plate/House Number in Background)</p>
                    </td>
                </tr>
                <tr>
                    @php
                        $front_door  = Helper::addressVerificationFile($address_verification->jaf_id,'front_door');
                    @endphp
                     @if(count($front_door)>0)
                        @php
                            $path = url('/').'/uploads/candidate-front-door/';
                        @endphp
                        @if(count($front_door)==1)
                            @foreach ($front_door as $item)
                                <td style="padding:10px;overflow:hidden;border:1px solid #ccc;display:block;">
                                    {{-- <img src="{{ $path.$item->image }}" style="height:150px;width:200px;margin-bottom:20px;-ms-transform: rotate(90deg);transform: rotate(90deg);overflow:hidden;"><br><br><br> --}}
                                    <img src="{{ $path.$item->image }}" style="height:100%;width:100%;"><br><br>
                                    <span style="font-size:28px;margin-bottom: 6px;">Date and Time: {{date('Y-m-d H:i:s',strtotime($item->created_at))}}</span><br>
                                    <span style="font-size:28px;">Location: {{$item->latitude!=NULL ? $item->latitude : '--'}} , {{$item->longitude!=NULL ? $item->longitude : '--'}}</span>
                                </td>
                            @endforeach
                        @else
                            @foreach ($front_door as $item)
                                <td style="padding:10px;border:1px solid #ccc;text-align:center;width:50%;">
                                    {{-- <img src="{{ $path.$item->image }}" style="height:150px;width:200px;margin-bottom:20px;-ms-transform: rotate(90deg);transform: rotate(90deg);overflow:hidden;"><br><br><br> --}}
                                    <img src="{{ $path.$item->image }}" style="height:150px;width:200px;margin-bottom:20px;overflow:hidden;"><br><br><br>
                                    <span style="font-size:14px;margin-bottom: 6px;">Date and Time: {{date('Y-m-d H:i:s',strtotime($item->created_at))}}</span><br>
                                    <span style="font-size:14px;">Location: {{$item->latitude!=NULL ? $item->latitude : '--'}} , {{$item->longitude!=NULL ? $item->longitude : '--'}}</span>
                                </td>
                            @endforeach
                        @endif
                     @else
                        <td style="padding:10px;border:1px solid #ccc; text-align:center;">
                            <img src="{{ asset('admin/images/profile-default-avtar.jpg') }}" style="height:150px;width:200px;margin-bottom:20px;overflow:hidden;">
                            {{-- <span style="font-size:14px;margin-bottom: 6px;">Date and Time: 10-09-2022</span><br>
                            <span style="font-size:14px;">Location: 28.608721 , 77.3489</span> --}}
                        </td>
                     @endif
                </tr>
              
            </table>
        </td>
        <td>
            <table width="100%" border="0">
                <tr>
                    <td style="background-color:#eeeeee;font-size: 35px;padding:10px;text-align: center;height:93px;">
                        Profile Photo (Selfie)
                        <br> <p style="font-size:25px;">(Selfie with ID Proof)</p>
                    </td>
                </tr>
                <tr>
                    @php
                        $profile_photo  = Helper::addressVerificationFile($address_verification->jaf_id,'profile_photo');
                    @endphp
                     @if(count($profile_photo)>0)
                        @php
                            $path = url('/').'/uploads/candidate-selfie/';
                        @endphp
                        @if(count($profile_photo)==1)
                            @foreach ($profile_photo as $item)
                                <td style="border:1px solid #ccc;overflow:hidden;padding:10px;">
                                    {{-- <img src="{{ $path.$item->image }}" style="width:150px;margin-bottom:20px;-ms-transform: rotate(90deg);transform: rotate(90deg);overflow:hidden;"><br><br><br> --}}
                                    <img src="{{ $path.$item->image }}" style="height:100%;width:100%;"><br><br><br>
                                   <span style="font-size:28px;margin-bottom: 6px;">Date and Time: {{date('Y-m-d H:i:s',strtotime($item->created_at))}}</span><br>
                                    <span style="font-size:28px;">Location: {{$item->latitude!=NULL ? $item->latitude : '--'}} , {{$item->longitude!=NULL ? $item->longitude : '--'}}</span>
                                </td>
                            @endforeach
                        @else
                            @foreach ($profile_photo as $item)
                                <td style="padding:10px;border:1px solid #ccc;text-align:center;width:50%;">
                                    {{-- <img src="{{ $path.$item->image }}" style="height:150px;width:200px;margin-bottom:20px;-ms-transform: rotate(90deg);transform: rotate(90deg);overflow:hidden;"><br><br><br> --}}
                                    <img src="{{ $path.$item->image }}" style="height:150px;width:200px;margin-bottom:20px;overflow:hidden;"><br><br><br>
                                    <span style="font-size:14px;margin-bottom: 6px;">Date and Time: {{date('Y-m-d H:i:s',strtotime($item->created_at))}}</span><br>
                                    <span style="font-size:14px;">Location: {{$item->latitude!=NULL ? $item->latitude : '--'}} , {{$item->longitude!=NULL ? $item->longitude : '--'}}</span>
                                </td>
                            @endforeach
                        @endif
                     @else
                        <td style="padding:10px;border:1px solid #ccc; text-align:center;">
                            <img src="{{ asset('admin/images/profile-default-avtar.jpg') }}" style="height:150px;width:200px;margin-bottom:20px;overflow:hidden;">
                            {{-- <span style="font-size:14px;margin-bottom: 6px;">Date and Time: 10-09-2022</span><br>
                            <span style="font-size:14px;">Location: 28.608721 , 77.3489</span> --}}
                        </td>
                     @endif
                </tr>
              
            </table>
        </td>
    </tr>
</table>
<table width="100%" border="0" style="">
    @php
        $address_proof  = Helper::addressVerificationFile($address_verification->jaf_id,'address_proof');
    @endphp
    {{-- <tr>
        
        <td colspan="2" style="background-color:#eeeeee;font-size: 20px;padding:10px;text-align: center;">&nbsp;</td>
    </tr> --}}
    <tr>
        <td style="width:50%;">
            <table width="100%" border="0">
                
                <tr>
                    <td colspan="1" style="background-color:#eeeeee;font-size: 35px;padding:10px;text-align: center;">
                        ID Document (Front)
                        <br><p style="font-size: 25px;">(ID Document  with Photo (1))</p>
                    </td>
                </tr>
                <tr>
                    @php
                        $id_proof  = Helper::addressVerificationFile($address_verification->jaf_id,'id_proof');
                    @endphp
                    @if(count($id_proof)>0)
                        @php
                            $path = url('/').'/uploads/id-proof/';
                        @endphp
                        @if(count($id_proof)==1)
                            @foreach ($id_proof as $item)
                                <td style="padding:10px;border:1px solid #ccc;display:block;overflow:hidden;">
                                    {{-- <img src="{{ $path.$item->image }}" style="height:150px;width:200px;margin-bottom:20px;-ms-transform: rotate(90deg);transform: rotate(90deg);overflow:hidden;"><br><br><br> --}}
                                    <img src="{{ $path.$item->image }}" style="height:100%;width:100%;"><br><br><br>
                                    <span style="font-size:28px;margin-bottom: 6px;">Date and Time: {{date('Y-m-d H:i:s',strtotime($item->created_at))}}</span><br>
                                    <span style="font-size:28px;">Location: {{$item->latitude!=NULL ? $item->latitude : '--'}} , {{$item->longitude!=NULL ? $item->longitude : '--'}}</span>
                                </td>
                            @endforeach
                        @else
                            @foreach ($id_proof as $item)
                                <td style="padding:10px;border:1px solid #ccc;text-align:center;width:50%;height:250px;">
                                    {{-- <img src="{{ $path.$item->image }}" style="height:150px;width:200px;margin-bottom:20px;-ms-transform: rotate(90deg);transform: rotate(90deg);overflow:hidden;"><br><br><br> --}}
                                    <img src="{{ $path.$item->image }}" style="height:150px;width:200px;margin-bottom:20px;overflow:hidden;"><br><br><br>
                                    <span style="font-size:14px;margin-bottom: 6px;">Date and Time: {{date('Y-m-d H:i:s',strtotime($item->created_at))}}</span><br>
                                    <span style="font-size:14px;">Location: {{$item->latitude!=NULL ? $item->latitude : '--'}} , {{$item->longitude!=NULL ? $item->longitude : '--'}}</span>
                                </td>
                            @endforeach
                        @endif
                    @else
                        <td style="padding:10px;border:1px solid #ccc;text-align:center;height:250px;">
                            <img src="{{ asset('admin/images/profile-default-avtar.jpg') }}" style="height:150px;width:200px;margin-bottom:20px;overflow:hidden;">
                                {{-- <span style="font-size:14px;margin-bottom: 6px;">Date and Time: 10-09-2022</span><br>
                                <span style="font-size:14px;">Location: 28.608721 , 77.3489</span> --}}
                        </td>
                        
                    @endif
                </tr>
            </table>

        </td>
        <td style="width:50%;">
            <table width="100%" border="0">
                
                <tr>
                    <td colspan="{{count($address_proof)>1 ? '2' : '1'}}" style="background-color:#eeeeee;font-size: 35px;padding:10px;text-align: center;">
                        ID Document (Backside)
                        <br><p style="font-size: 25px;">(ID Document Showing Address Photo (2))</p>
                    </td>
                </tr>
                <tr>
                    @php
                        $address_proof  = Helper::addressVerificationFile($address_verification->jaf_id,'address_proof');
                    @endphp
                    @if(count($address_proof)>0)
                        @php
                            $path = url('/').'/uploads/address-proof/';
                        @endphp
                        @if(count($address_proof)==1)
                            @foreach ($address_proof as $item)
                                <td style="padding:10px;border:1px solid #ccc;display:block;overflow:hidden;">
                                    {{-- <img src="{{ $path.$item->image }}" style="height:150px;width:200px;margin-bottom:20px;-ms-transform: rotate(90deg);transform: rotate(90deg);overflow:hidden;"><br><br><br> --}}
                                    <img src="{{ $path.$item->image }}" style="height:100%;width:100%;"><br><br><br>
                                    <span style="font-size:28px;margin-bottom: 6px;">Date and Time: {{date('Y-m-d H:i:s',strtotime($item->created_at))}}</span><br>
                                    <span style="font-size:28px;">Location: {{$item->latitude!=NULL ? $item->latitude : '--'}} , {{$item->longitude!=NULL ? $item->longitude : '--'}}</span>
                                </td>
                            @endforeach
                        @else
                            @foreach ($address_proof as $item)
                                <td style="padding:10px;border:1px solid #ccc;text-align:center;width:50%;height:250px;">
                                    {{-- <img src="{{ $path.$item->image }}" style="height:150px;width:200px;margin-bottom:20px;-ms-transform: rotate(90deg);transform: rotate(90deg);overflow:hidden;"><br><br><br> --}}
                                    <img src="{{ $path.$item->image }}" style="height:150px;width:200px;margin-bottom:20px;overflow:hidden;"><br><br><br>
                                    <span style="font-size:14px;margin-bottom: 6px;">Date and Time: {{date('Y-m-d H:i:s',strtotime($item->created_at))}}</span><br>
                                    <span style="font-size:14px;">Location: {{$item->latitude!=NULL ? $item->latitude : '--'}} , {{$item->longitude!=NULL ? $item->longitude : '--'}}</span>
                                </td>
                            @endforeach
                        @endif
                    @else
                        <td style="padding:10px;border:1px solid #ccc;text-align:center;height:250px;">
                            <img src="{{ asset('admin/images/profile-default-avtar.jpg') }}" style="height:150px;width:200px;margin-bottom:20px;overflow:hidden;">
                                {{-- <span style="font-size:14px;margin-bottom: 6px;">Date and Time: 10-09-2022</span><br>
                                <span style="font-size:14px;">Location: 28.608721 , 77.3489</span> --}}
                        </td>
                        
                    @endif
                </tr>
            </table>

        </td>
    </tr>
</table>
<table width="100%" border="0" style="vertical-align:top;">
    <tr>
        <td style="width:50%;">
            <table width="100%" border="0">
                <tr>
                    <td style="background-color:#eeeeee;font-size:35px;padding:10px;text-align: center;">
                        Photo of Landmark
                        <br><p style="font-size: 25px;">(Famous Place/Hotel/Cinema etc)</p> 
                    </td>
                </tr>
                <tr>
                    @php
                        $location  = Helper::addressVerificationFile($address_verification->jaf_id,'location');
                    @endphp
                    @if(count($location)>0)
                        @php
                            $path = url('/').'/uploads/candidate-location/';
                        @endphp
                        @if(count($location)==1)
                            @foreach ($location as $item)
                                <td style="padding:10px;border:1px solid #ccc;display:block;overflow:hidden;">
                                    {{-- <img src="{{ $path.$item->image }}" style="height:150px;width:200px;margin-bottom:20px;-ms-transform: rotate(90deg);transform: rotate(90deg);overflow:hidden;"><br><br><br> --}}
                                    <img src="{{ $path.$item->image }}" style="height:100%;width:100%;"><br><br><br>
                                    <span style="font-size:28px;margin-bottom: 6px;">Date and Time: {{date('Y-m-d H:i:s',strtotime($item->created_at))}}</span><br>
                                    <span style="font-size:28px;">Location: {{$item->latitude!=NULL ? $item->latitude : '--'}} , {{$item->longitude!=NULL ? $item->longitude : '--'}}</span>
                                </td>
                            @endforeach
                        @else
                            @foreach ($location as $item)
                                <td style="padding:10px;border:1px solid #ccc;text-align:center;width:50%;height:250px;">
                                    {{-- <img src="{{ $path.$item->image }}" style="height:150px;width:200px;margin-bottom:20px;-ms-transform: rotate(90deg);transform: rotate(90deg);overflow:hidden;"><br><br><br> --}}
                                    <img src="{{ $path.$item->image }}" style="height:150px;width:200px;margin-bottom:20px;overflow:hidden;"><br><br><br>
                                    <span style="font-size:14px;margin-bottom: 6px;">Date and Time: {{date('Y-m-d H:i:s',strtotime($item->created_at))}}</span><br>
                                    <span style="font-size:14px;">Location: {{$item->latitude!=NULL ? $item->latitude : '--'}} , {{$item->longitude!=NULL ? $item->longitude : '--'}}</span>
                                </td>
                            @endforeach
                        @endif
                    @else
                        <td style="padding:10px;border:1px solid #ccc;text-align:center;height:250px;">
                            <img src="{{ asset('admin/images/profile-default-avtar.jpg') }}" style="height:150px;width:200px;margin-bottom:20px;overflow:hidden;">
                                {{-- <span style="font-size:14px;margin-bottom: 6px;">Date and Time: 10-09-2022</span><br>
                                <span style="font-size:14px;">Location: 28.608721 , 77.3489</span> --}}
                        </td>
                        
                    @endif
                </tr>
            </table>
        </td>
        <td style="width:50%;">
            <table width="100%" border="0">
                <tr>
                    <td style="background-color:#eeeeee;font-size: 35px;padding:10px;text-align: center;height:95px;border">
                        Signature
                    </td>
                </tr>
                <tr>
                    @if($address_verification->signature!=NULL)
                        @php
                            $path = url('/').'/uploads/candidate-signature/';
                        @endphp
                        <td style="padding:10px;border:1px solid #ccc;display:block;overflow:hidden;">
                            <img src="{{ $path.$address_verification->signature }}" style="height:100%;width:100%;"><br><br><br><br>
                            <span style="font-size:28px;margin-bottom: 6px;">Date and Time: {{date('Y-m-d H:i:s',strtotime($address_verification->created_at))}}</span>,<br>
                            <span style="font-size:28px;">Location: {{$address_verification->signature_latitude!=NULL ? $address_verification->signature_latitude : $address_verification->latitude}} , {{$address_verification->signature_longitude!=NULL ? $address_verification->signature_longitude : $address_verification->longitude}}</span>
                        </td>  
                    @else
                        <td style="padding:10px;border:1px solid #ccc;text-align:center;height:250px;">
                            <img src="{{ asset('admin/images/profile-default-avtar.jpg') }}" style="height:100px; margin-bottom:20px;">
                            {{-- <br>
                                <span style="font-size:14px;margin-bottom: 6px;">Date and Time: 10-09-2022</span><br>
                                <span style="font-size:14px;">Location: 28.608721 , 77.3489</span> --}}
                        </td>
                    @endif
                </tr>
            </table>
        </td>
    </tr>
</table>
  <!-- Footer table -->
<htmlpagefooter name="page-footer">
    <footer>
        @php 
            $defaultaddress = Helper::get_default_address($business_id);
        @endphp
        {{-- <table width="100%" style="margin-top: 30px;border-top:1px solid #ccc; padding-top:20px;">
            <tr>
                <td style="width:10%">Powered By: <img src="{{ Helper::company_logo_path($business_id) }}" width="18%"></td>
                <td style="font-weight:600; text-align:center;width:70%;">{{Helper::company_sort_name($business_id)}}<br>
                    {{ $defaultaddress->address_line1 }} {{ $defaultaddress->city_name }}-{{ $defaultaddress->zipcode }}
                </td>
            </tr>
            
        </table> --}}
        <p style="font-size:14px;">
        <br><b>{{ Helper::company_sort_name($business_id) }}</b><br>
        <span style="">{{ $defaultaddress->address_line1 }} {{ $defaultaddress->city_name }}-{{ $defaultaddress->zipcode }}</span>
        </p>
        <table cellpadding="0" cellspacing="2" width="100%" ><tr><td align="left" style=" font-size:14px;">Powered By: <img src="{{ Helper::company_logo_path($business_id) }}" width="80" style="vertical-align:bottom"> </td><td align="right">{PAGENO} of {nb}</td> </tr></table>
    </footer>
</htmlpagefooter>

</body>
</html>
