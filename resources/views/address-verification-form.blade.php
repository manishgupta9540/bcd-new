@extends('layouts.app')
@section('content')
<style>
    .sweet-alert button.cancel {
        background: #DD6B55 !important;
        width: auto;
    }
    .sa-confirm-button-container button.confirm{
        width:100%
    }
    .data p {
        color: #000;
    }
        .disabled-link {
            pointer-events: none;
        }

        .data-selfie img {
            max-width: 100%;
            height: 100%;
        }

        .data-selfie {
            border: 1px solid #ccc;
            overflow: hidden;
            padding-top: 0px;
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
        div#signature-area canvas#sig-canvas {
            width: 100%;
        }
        #close-sing {
            display: none;
            color: red;
            position: absolute;
            top: 3px;
            font-size: 30px;
            background-color: #fff;
            border-radius: 56%;
            right: 10px;
        }
        @media(max-width:991px){
                    #signature-area{display:none;}
                    #signature-area .data-selfie{    position: relative;}
                    #signature-area.open-sign{
                        display:flex;
                        position: fixed;
                        top: 0px;
                        bottom: 0px;
                        padding: 0px;
                        height: 100vh;
                        width: 100%;
                        left: 0;
                        overflow: hidden;
                        right: 0;
                        background-color:#00000096;
                        z-index: 111;
                        justify-content: center;
                        align-items: center;
                    }
                    #signature-area.open-sign #sig canvas {
                        width: 100% !important;
                        height: 100%;
                    }
                    body.sign-over{overflow: hidden;}
                    #signature-area.open-sign .data-selfie {
                        width: 92%;
                        background-color: #fff;
                    }
                    #signature-area.open-sign #close-sing {
                        display: block;
                    }
                    .font-doors {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                    }
                    .font-doors .file input{height:0px;}
                    .font-doors .right-upload label{width:auto!important;margin-bottom:0px;}
        }
        @media only screen and (min-width: 768px) and (max-width: 991px){
            .right-upload.font-doors h5 {
                font-size: 13px;
            }
            .font-doors .file {
                width: 42%!important;
                margin-bottom: 11px;
            }
            .font-doors.right-upload label {
                width: auto!important;
                margin-bottom: 0px;
            }

        }



        /* #map{
                        height:500px;
                    } */
        @media print {
            .submit {
                display: none;
            }

            #print {
                display: none;
            }

            .printable,
            .printable * {
                visibility: visible;
            }

            .location-card,
            .signature-card {
                padding-top: 20.5rem !important;
            }

            .data-selfie .text-center {
                text-align: center !important;
            }

            .data-selfie img {
                display: block;
                height: 100%;
            }

            .data-selfie {
                height: 430px;
            }

            .small-img img {
                height: 150px !important;
                width: 100% !important;
            }

            #map {
                height: 425px;
            }
        }



        .instant-verify {
            background-color: #F9F9F9;
            padding: 55px 225px;
            margin: 50px 0px;
        }

        .instant-verify h3 {
            font-size: 30px;
            color: #0000ff;
            margin: 15px 0px;
            text-align: center;

        }

        .verifiy-form {
            padding: 10px 15px;
            margin: 0px 20px;

        }

        .custom-input {
            margin: 0px 0px 15px 0px;
            padding: 5px;
            width: 100%;
            height: auto;

        }

        .verifiy-btn {
            background: #E10813;
            max-width: 200px;
            color: #fff;
            padding: 10px 15px;
            font-size: 12px;
            border-radius: 5px;
            margin-top: 25px;
        }

        .fw-600 {
            font-weight: 600;
        }

        .advance-feature {
            text-align: center;
        }

        .advance-feature h2 {
            color: #142550;
            font-weight: 600;
            padding: 25px 180px;
            margin: 20px 0px;
        }

        .advance-feature h6 {
            font-size: 18px;
            color: #142550;
            font-weight: 600;
            padding: 15px 0px;
        }

        .advance-feature p {
            color: #474747;
            line-height: 29px;
            text-align: justify;
            padding: 0px 20px;
        }

        .text-blue {
            color: #142550;
        }

        .text-para {
            color: #474747;
        }

        .para-custom {
            line-height: 29px;
            color: #444444;
        }

        .mt-80 {
            margin-top: 80px;
        }

        .hiring-process {
            background-color: #F9F9F9;
            padding: 50px 0px;
        }

        .first-container {
            /*background-image: url('images/verification_banner_no_text.jpg');
                background-size: cover;
                background-repeat: no-repeat;
                height: 590px;
                box-shadow: 0px 5px 10px #ddd;*/
            box-shadow: 0px 5px 10px #999;
            position: sticky;
            top: 0px;
            background: #fff;
            z-index: 4;

        }

        .sec-one-left h6 {
            font-size: 26px;
            color: #000;
            font-weight: 600;
            margin-top: 13px;
        }

        .banner-section {
            background-image: url('admin/images/verification_banner_no_text.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            height: 590px;
            box-shadow: 0px 5px 10px #ddd;

        }

        .registration-menu {
            list-style-type: none;
            padding: 0px;
            /*position: absolute;
                right:130px;
                top:20px;
                z-index:2;*/
        }

        .registration-menu li {
            display: inline;
            margin: 5px 10px;
        }

        .registration-menu li a {
            font-weight: 500;
            line-height: 1;
            font-size: 17px;
            font-family: 'Ruda', sans-serif;
            color: #002e62 !important;
            text-decoration: none;

        }

        .registration-menu li a:hover {
            color: #ff0000 !important;
        }

        .sec-one-left {
            /* margin: 50px 0;
                padding: 50px 0; */
            position: absolute;
            top: 200px;
        }

        .sec-one-left h4 {
            color: #F63A55;
            font-size: 44px;
            font-weight: 600;
        }

        .form-banner {
            background: rgba(241, 164, 175, 0.55);
            color: #585858;
            text-align: center;
            padding: 10px;
        }

        .form-banner h6 {
            font-size: 18px;
            line-height: 24px;
        }

        .form-banner h6 span {
            color: #E11E26;
            font-weight: 600;
        }

        .registration-nav {
            position: relative;
            top: 0px;
            left: 0px;
            padding: 0px;

        }

        .verification-footer {
            /* background-color: #002E62; */
            background-color: #ACACAC;
            text-align: center;
            color: #fff !important;
            padding: 10px;
        }

        .sec-six-heading {
            text-align: center;
            padding: 50px 380px;
        }

        .sec-six-heading h4 {
            color: #171C3A;
            font-size: 30px;
            font-weight: 600;
        }

        .sec-six-heading h6 {
            font-size: 18px;
            color: #447C8D;
            line-height: 24px;
        }

        /*slider*/
        .slick-slide {
            margin: 0px 20px;
        }

        .slick-slide img {
            width: 100%;
        }

        .customer-logos img {
            max-width: 130px;
        }

        .slick-slider {
            position: relative;
            display: block;
            box-sizing: border-box;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-touch-callout: none;
            -khtml-user-select: none;
            -ms-touch-action: pan-y;
            touch-action: pan-y;
            -webkit-tap-highlight-color: transparent;
        }

        .slick-list {
            position: relative;
            display: block;
            overflow: hidden;
            margin: 0;
            padding: 0;
        }

        .slick-list:focus {
            outline: none;
        }

        .slick-list.dragging {
            cursor: pointer;
            cursor: hand;
        }

        .slick-slider .slick-track,
        .slick-slider .slick-list {
            -webkit-transform: translate3d(0, 0, 0);
            -moz-transform: translate3d(0, 0, 0);
            -ms-transform: translate3d(0, 0, 0);
            -o-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
        }

        .slick-track {
            position: relative;
            top: 0;
            left: 0;
            display: block;
        }

        .slick-track:before,
        .slick-track:after {
            display: table;
            content: '';
        }

        .slick-track:after {
            clear: both;
        }

        .slick-loading .slick-track {
            visibility: hidden;
        }

        .slick-slide {
            display: none;
            float: left;
            height: 100%;
            min-height: 1px;
        }

        [dir='rtl'] .slick-slide {
            float: right;
        }

        .slick-slide img {
            display: block;
        }

        .slick-slide.slick-loading img {
            display: none;
        }

        .slick-slide.dragging img {
            pointer-events: none;
        }

        .slick-initialized .slick-slide {
            display: block;
        }

        .slick-loading .slick-slide {
            visibility: hidden;
        }

        .slick-vertical .slick-slide {
            display: block;
            height: auto;
            border: 1px solid transparent;
        }

        .slick-arrow.slick-hidden {
            display: none;
        }

        .btn-opacity {
            opacity: .65;
        }

        @media only screen and (min-width: 320px) and (max-width: 767px) {

            /*.first-container{
                background-image: url('images/verification_banner_no_text.jpg');
                background-size: cover;
                background-repeat: no-repeat;
                background-position: 0% 100%;
                height: 310px;
            }*/
            .banner-section {
                background-image: url('admin/images/verification_banner_no_text.jpg');
                background-size: cover;
                background-repeat: no-repeat;
                background-position: 0% 100%;
                height: 158px;
            }

            .registration-menu {

                background-color: #eacccc;
                top: 0px;
                position: relative;

            }

            .advance-feature h2 {
                color: #142550;
                font-weight: 600;
                padding: 25px 65px;
                margin: 20px 0px;
            }

            .instant-verify {
                background-color: #F9F9F9;
                padding: 38px;
                margin: 50px 0px;
            }

            .sec-six-heading {
                text-align: center;
                padding: 20px;
            }

            .sec-one-left {
                /* margin: 0px;
                padding: 10px 0px 50px 0px; */

                position: absolute;
                top: 30px;
            }
        }

        .fixed-box {
            border-radius: 8px;
            background-color: #fff;
            padding: 19px;
            width: 417px;
            position: fixed;
            bottom: 4%;
            right: 16px;
            box-shadow: rgb(60 64 67 / 30%) 0px 1px 2px 0px, rgb(60 64 67 / 15%) 0px 2px 6px 2px;
            border: 1px solid #fff;

        }

        .btn1 {
            border-radius: 26px;
        }

        p {
            color: #002e62;
        }

        /* input[type="text"] {
            width: 100%;
            border-radius: 4px;
            border: 1px solid #aba3a3;
            padding: 6px;
        } */

        .profile-img {
            border: 1px solid #d2d7db;
            border-radius: 4px;
            width: 80%;
            margin: 25px auto;
        }

        .right-upload h5 {
            color: #000;
            margin-bottom: 15px;
            display: inline-block;
            font-size: 15px;
        }



        .profile-card p.label {
            font-size: 17px;
            text-align: center;
            background-color: #d2d7db;
            padding: 8px;
        }

        .data-selfie {
            border: 1px solid #ccc;
            overflow: hidden;
            padding-top: 0px;
        }

        .submit-btn {
            width: 25%;
            float: right;
        }

        .profile-card {
            margin-bottom: 25px;
        }

        body p {
            color: #002e62;
            font-size: 13px;
        }

        h3 {
            font-size: 20px;
            font-weight: 700;
            color: #003473;
        }

        table td strong {
            font-size: 13px;
            font-weight: 700;
        }
        .main-content-wrap.sidenav-open.d-flex.flex-column.mt-80 {
            margin-bottom: 30px;
        }
        .submit-btn button:before {
            content: "";
            position: absolute;
            z-index: -1;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #003473 !important;
        }

        @media (max-width: 767px){
		    .verification_details tr>td{width:100%!important;display:flex;}
            .font-doors .file{width:32%!important;}

            .font-doors .file{margin-bottom:11px;}
            .font-doors.right-upload label{width:auto!important;margin-bottom:0px;}
            .flex-sm-screen{    flex-wrap: inherit;}
            .main-content-wrap .main-content {
                margin-top: 40px !important;
            }

            h3 {
                font-size: 18px;
                font-weight: 700;
                color: #003473;
            }

            .right-upload input[type="file"]{
                width: 32% !important;
                font-size: 13px;
            }

            .right-upload label{
                width: 32% !important;
                font-size: 11px;
            }



            .right-upload h5 {
                font-size: 13px;
                width: 68%;
            }
            .profile-card p.label {
                font-size: 15px;
            }
            .submit-btn {
                width: 38%;
            }
            .submit-btn button{
                font-size: 15px;
            }
            .table-bordered td, .table-bordered th {
                border: 1px solid #dee2e6;
                min-width: 200px;
            
            }
            .table-responsive input[type="text"] {
                width: 100%;
                border-radius: 4px;
                border: 1px solid #aba3a3;
                padding: 3px;
            }

            #other_residence{
                margin-left: 10px;
            }
        }

        .remove-image {
            position: absolute;
            top: -10px;
            right: 10px;
            border-radius: 10em;
            padding: 3px 6px 3px;
            text-decoration: none;
            font: 700 21px/20px sans-serif;
            background: #555;
            border: 3px solid #fff;
            color: #FFF;
            box-shadow: 0 2px 6px rgba(0,0,0,0.5), inset 0 2px 4px rgba(0,0,0,0.3);
                text-shadow: 0 1px 2px rgba(0,0,0,0.5);
                -webkit-transition: background 0.5s;
                transition: background 0.5s;
        }

        .remove-image:hover {
            background: #E54E4E;
            padding: 3px 7px 5px;
            top: -11px;
            right: 10px;
            color: #fff;
        }
        .remove-image:active {
            background: #E54E4E;
            top: -10px;
            right: 10px;
        }

        .remove-image
        {
            padding: 0px 3px 0px !important;
        }

        /* .profile-img img{
            height: 100px !important;
            width: 100px !important;
            padding: 8px !important;
        } */

        .image-area{
            width: 90px !important;
        }

        .remove-image:hover
        {
            padding: 0px 3px 0px !important;
        }

        /* #map{
                height:200px;
            } */

        .kbw-signature { width: 100%; height: 250px;}
        #sig canvas{
            width: 100% !important;
            height: auto;
        }

        #sig-canvas {
            border: 2px dotted #CCCCCC;
            border-radius: 15px;
            cursor: crosshair;
        }

        .capturevideo{width:100%;height:100%;}
        .camera-box { height: 281px;}
        canvas.capturecanvas { width: 100%;height: 100%;}

        .modal-close-btn
        {
            width:20%;
        }
        .modal-close-btn::before
        {
            background: transparent;
        }
        .modal-close-btn:hover{
            background: transparent;
        }
        .border-dark {
            border: 1px solid #343a40!important;
        }

        .btn-opacity
        {
            opacity: .65;
        }

        .file {
        position: relative;
        /* height: 30px;
        width: 100px; */
        }

        .file > input[type="file"] {
        position: absoulte;
        opacity: 0;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0
        }
        .date_pick .input-group-append {
        position: absolute;
        top: 0px;
        right: 0px;
        pointer-events: none;
        cursor: pointer;
        color: #aaa;
        height: 38px;
        width: 40px;
        }
        .date_pick .input-group-append .input-group-text {
        background-color: transparent;
        border: 0px;
        }
        /* .file > label {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        bottom: 0;
        background-color: #666;
        color: #fff;
        border-radius: 5px;
        line-height: 30px;
        text-align: center;
        cursor: pointer
        } */

        @media(min-width:767px){
            #other_residence{
                margin-top: 10px;
            }
        }
   
</style>
    <div class="container">
        <div class="main-content-wrap sidenav-open d-flex flex-column mt-80">


            <div class="main-content">
                <div class="print_this location-div">
                    <div class="card text-left">

                        <div class="card-body mb-4">
                            <form method="post" action="{{url('/address-verification-form',['id'=>base64_encode($jaf_data->id)])}}" id="address_frm">
                                @csrf
                                    @php
                                        $candidate = Helper::candidate_user_details($jaf_data->candidate_id);
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
                                                    {{ Helper::user_reference_id($jaf_data->candidate_id) }}</p></div> --}}
                                                {{-- <div class="col-12 col-md-6 col-lg-6"><p>
                                                    <strong>Email:</strong> {{ $candidate->email ? $candidate->email : 'N/A' }} --}}
                                                    <input type="hidden" name="email_address" value="{{ $candidate->email }}">
                                                {{-- </p></div> --}}
                                                {{-- <div class="col-12 col-md-6 col-lg-6"><p>
                                                    <strong>Alternate Email:</strong> {{ $candidate->alternative_email ? $candidate->alternative_email:'N/A' }} --}}
                                                    <input type="hidden" name="alternative_email_address" value="{{ $candidate->alternative_email }}">
                                                {{-- </p></div> --}}
                                                <div class="col-12 col-md-6 col-lg-6"><p><strong>Client Name:</strong>
                                                    {{ Helper::company_name($jaf_data->business_id) }}</p></div>
                                                {{-- <div class="col-12 col-md-6 col-lg-6"><p>
                                                    <strong>Contact Number:</strong> {{$contact_number}} --}}
                                                    <input type="hidden" name="phone_number" value="{{$contact_number}}">
                                                {{-- </p></div> --}}
                                                <div class="col-12 col-md-6 col-lg-6"><p><strong>Candidate Name:</strong>
                                                    {{ Helper::user_name($jaf_data->candidate_id) }}</p></div>
                                                {{-- <div class="col-12 col-md-6 col-lg-6"><p>
                                                    <strong>Address:</strong> {{$addr}} --}}
                                                    <input type="hidden" name="address" value="{{$addr}}">
                                                {{-- </p></div> --}}
                                                {{-- <div class="col-12 col-md-6 col-lg-6"><p><strong>Check Name:</strong>
                                                    {{ $jaf_data != null ? $jaf_data->service_name . ' - ' . $jaf_data->check_item_number : '--' }}
                                                </p></div> --}}
                                                {{-- <div class="col-12 col-md-6 col-lg-6"><p>
                                                    <strong>Pincode:</strong> {{$zip}} --}}
                                                    <input type="hidden" name="zipcode" value="{{$zip}}">
                                                {{-- </p></div> --}}
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
                                                                    <input class="form-control @if($address_ver==NULL || $address_ver->nature_of_residence==NULL || stripos($address_ver->nature_of_residence,'rented')!==false || stripos($address_ver->nature_of_residence,'owned')!==false || stripos($address_ver->nature_of_residence,'pg')!==false || stripos($address_ver->nature_of_residence,'hostel')!==false) ||  d-none @endif" id="other_residence" name="other_residence" type="text" placeholder="" value="{{$address_ver!=NULL ? $address_ver->nature_of_residence : ''}}"> 
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
                                                        <td><strong>Relation with the Candidate: <span class="text-danger">*</span></strong> </td>
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
                                               <br> <small>(Name Plate/House Number in Background)</small>
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

                                            <p class="label">Profile Photo <span class="text-danger">*</span>
                                                <br> <small>(Selfie with ID Proof)</small>
                                            </p>

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

                                            <p class="label">ID Document (Backside) <span class="text-danger back_lbl">*</span>
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
                                                            $location  = Helper::addressVerificationFile($jaf_data->id,'location');
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
                </div>

            </div>
        </div>

        <!-- Footer Start -->
        <div class="flex-grow-1"></div>

    </div>



    </div>
    
<div class="modal" id="sign_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" id="name" style="color: #000;">Digital Signature</h4>
                {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <br/>
                            <div id="sig">
                            
                            </div>
                            <br/>
                            <textarea id="signature64" name="signature" class="error-control" style="display: none"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Save </button>
                <button type="button" id="clear" class="btn btn-danger">Clear</button>
            </div>
        </div>
    </div>
</div> 
 <!-- The Modal -->
 <div class="modal" id="front-door-modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title text-dark w-100">Selfie with Front Door 
            <br><small>(Name Plate/House Number in Background)</small>
          </h4>
          <button type="button" class="close modal-close-btn" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-3">
                    <button type="button" class="btn btn-info mt-0 mb-3 btnActivateCamera" id="btnFrontActivateCamera" style="background-color: #003473;">Activate</button>
                    <button type="button" class="btn btn-dark mt-0 mb-3 d-none btnDeactivateCamera" id="btnFrontDeactivateCamera">Deactivate</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="camera-box border p-2 d-none" style="transform: scaleX(-1) !important;">
                        <video id="capture-front-video" width="230" height="230" class="capturevideo d-none"></video>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="camera-box border p-2 d-none">
                        <canvas id="capture-front-canvas" width="230" height="230" class="capturecanvas d-none"></canvas>
                    </div>
                </div>
            </div>
          
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer d-block">
            <div class="row">
                <div class="col-6">
                    <button type="button" class="btn btn-info d-none mt-0 btnCapture" id="btnFrontCapture" style="background-color: #003473;">Capture</button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-danger d-none mt-0 btnClear" id="btnFrontClear">Clear</button>
                </div>
            </div>
        </div>
        
      </div>
    </div>
 </div>

 <div class="modal" id="profile-photo-modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title text-dark w-100">Profile Photo 
            <br><small>(Selfie with ID Proof)</small>
          </h4>
          <button type="button" class="close modal-close-btn" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-3">
                    <button type="button" class="btn btn-info mt-0 mb-3 btnActivateCamera" id="btnProfileActivateCamera" style="background-color: #003473;">Activate</button>
                    <button type="button" class="btn btn-dark mt-0 mb-3 d-none btnDeactivateCamera" id="btnProfileDeactivateCamera">Deactivate</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="camera-box border p-2 d-none" style="transform: scaleX(-1) !important;" >
                        <video id="capture-profile-video" width="230" height="230" class="capturevideo d-none"></video>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="camera-box border p-2 d-none">
                        <canvas id="capture-profile-canvas" width="230" height="230" class="capturecanvas d-none"></canvas>
                    </div>
                </div>
            </div>
          
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer d-block">
            <div class="row">
                <div class="col-6">
                    <button type="button" class="btn btn-info d-none mt-0 btnCapture" id="btnProfileCapture" style="background-color: #003473;">Capture</button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-danger d-none mt-0 btnClear" id="btnProfileClear">Clear</button>
                </div>
            </div>
        </div>
        
      </div>
    </div>
 </div>

 <div class="modal" id="id-proof-modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title text-dark w-100">ID Document (Backside) 
            <br><small>(ID Document Showing Address Photo (2))</small>
          </h4>
          <button type="button" class="close modal-close-btn" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-3">
                    <button type="button" class="btn btn-info mt-0 mb-3 btnActivateCamera" id="btnIdActivateCamera" style="background-color: #003473;">Activate</button>
                    <button type="button" class="btn btn-dark mt-0 mb-3 d-none btnDeactivateCamera" id="btnIdDeactivateCamera">Deactivate</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="camera-box border p-2 d-none" style="transform: scaleX(-1) !important;" >
                        <video id="capture-id-video" width="230" height="230" class="capturevideo d-none"></video>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="camera-box border p-2 d-none">
                        <canvas id="capture-id-canvas" width="230" height="230" class="capturecanvas d-none"></canvas>
                    </div>
                </div>
            </div>
          
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer d-block">
            <div class="row">
                <div class="col-6">
                    <button type="button" class="btn btn-info d-none mt-0 btnCapture" id="btnIdCapture" style="background-color: #003473;">Capture</button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-danger d-none mt-0 btnClear" id="btnIdClear">Clear</button>
                </div>
            </div>
        </div>
        
      </div>
    </div>
 </div>

 <div class="modal" id="id-front-proof-modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title text-dark w-100">ID Document (Front) 
            <br><small>(ID Document  with Photo (1))</small>
          </h4>
          <button type="button" class="close modal-close-btn" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-3">
                    <button type="button" class="btn btn-info mt-0 mb-3 btnActivateCamera" id="btnIdFrontActivateCamera" style="background-color: #003473;">Activate</button>
                    <button type="button" class="btn btn-dark mt-0 mb-3 d-none btnDeactivateCamera" id="btnIdFrontDeactivateCamera">Deactivate</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="camera-box border p-2 d-none" style="transform: scaleX(-1) !important;" >
                        <video id="capture-id-front-video" width="230" height="230" class="capturevideo d-none"></video>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="camera-box border p-2 d-none">
                        <canvas id="capture-id-front-canvas" width="230" height="230" class="capturecanvas d-none"></canvas>
                    </div>
                </div>
            </div>
          
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer d-block">
            <div class="row">
                <div class="col-6">
                    <button type="button" class="btn btn-info d-none mt-0 btnCapture" id="btnIdFrontCapture" style="background-color: #003473;">Capture</button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-danger d-none mt-0 btnClear" id="btnIdFrontClear">Clear</button>
                </div>
            </div>
        </div>
        
      </div>
    </div>
 </div>

 <div class="modal" id="landmark-modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title text-dark w-100">Photo of Landmark (Famous Place/Hotel/Cinema etc)</h4>
          <button type="button" class="close modal-close-btn" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-3">
                    <button type="button" class="btn btn-info mt-0 mb-3 btnActivateCamera" id="btnLandmarkActivateCamera" style="background-color: #003473;">Activate</button>
                    <button type="button" class="btn btn-dark mt-0 mb-3 d-none btnDeactivateCamera" id="btnLandmarkDeactivateCamera">Deactivate</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="camera-box border p-2 d-none" style="transform: scaleX(-1) !important;" >
                        <video id="capture-landmark-video" width="230" height="230" class="capturevideo d-none"></video>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="camera-box border p-2 d-none">
                        <canvas id="capture-landmark-canvas" width="230" height="230" class="capturecanvas d-none"></canvas>
                    </div>
                </div>
            </div>
          
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer d-block">
            <div class="row">
                <div class="col-6">
                    <button type="button" class="btn btn-info d-none mt-0 btnCapture" id="btnLandmarkCapture" style="background-color: #003473;">Capture</button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-danger d-none mt-0 btnClear" id="btnLandmarkClear">Clear</button>
                </div>
            </div>
        </div>
        
      </div>
    </div>
 </div>

<script src="{{asset('js/geoPosition.js')}}" type="text/javascript" charset="utf-8"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{env("GOOGLE_MAP_KEY")}}&libraries=geometry"></script>
<script src="{{asset('js/digital-signature.js?ver=1.1')}}"></script>
<script src = "https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script>

    $("#btn-signature").click(function(){
        $("body").addClass("sign-over");
        $("#signature-area").addClass("open-sign");
    });
    // $(".close-sing").click(function(){
    //     $("body").removeClass("sign-over");
    //     $("#signature-area").removeClass("open-sign");
    // });

// fetch('https://jsonip.com', { mode: 'cors' })
//   .then((resp) => resp.json())
//   .then((ip) => {
//     console.log(ip);
//   });
var isPaused = true;

function getLocation()
{
    // if (navigator.geolocation)
    // {
    //     navigator.geolocation.getCurrentPosition(showPosition,showError);
    // }
    // else{
    //     alert("Geolocation is not supported by this browser.");
    // }

    if (geoPosition.init())
    {
        geoPosition.getCurrentPosition(showPosition,showError,{enableHighAccuracy:true});
    }
    else{
        alert("Geolocation is not supported by this browser.");
    }
}

function showPosition(position)
{
    lat=position.coords.latitude;
    lon=position.coords.longitude;

    
    // Add current latitude & longitude

    // deleteCookie('add_lat');
    // setCookie('add_lat', lat, 30);

    // deleteCookie('add_long');
    // setCookie('add_long', lon, 30);

    // addressFormAjax();
    
   

    //window.setTimeout(()=>{

        latlon=new google.maps.LatLng(lat, lon);
        mapholder=document.getElementById('map');
        mapholder.style.height='250px';
        mapholder.style.width='100%';

        $('#geo_latitude').val(lat);
        $('#geo_longitude').val(lon);
        var geocoder = new google.maps.Geocoder();

        geocoder.geocode({ 'latLng': latlon },  (results, status) =>{
            if (status !== google.maps.GeocoderStatus.OK) {
                alert(status);
            }
            // This is checking to see if the Geoeode Status is OK before proceeding
            if (status == google.maps.GeocoderStatus.OK) {
                //console.log(results[0].formatted_address);
                var address = (results[0].formatted_address);
                $('#geo_address').val(address);
            }
        });

        var address = $('#geo_address').val();

        var myOptions={
            center:latlon,zoom:14,
            mapTypeId:google.maps.MapTypeId.ROADMAP,
            mapTypeControl:false,
            navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
        };
        var map=new google.maps.Map(document.getElementById("map"),myOptions);
        var marker=new google.maps.Marker({position:latlon,map:map,title:address});
    //},3000);

    
}

function showError(error)
{
    switch(error.code) 
    {
        case error.PERMISSION_DENIED:
            console.log("User denied the request for Geolocation.");
           locationErrorAjax();
        break;
        case error.POSITION_UNAVAILABLE:
            console.log("Location information is unavailable.");
            locationErrorAjax();
        break;
        case error.TIMEOUT:
            console.log("The request to get user location timed out.");
            locationErrorAjax();
        break;
        case error.UNKNOWN_ERROR:
            console.log("An unknown error occurred.");
            locationErrorAjax();
        break;
    }
}

function handlePermission() {
  navigator.permissions.query({ name: 'geolocation' }).then((result) => {
    if (result.state === 'granted') {
      report(result.state);
    } else if (result.state === 'prompt') {
      report(result.state);
      locationErrorAjax();
    } else if (result.state === 'denied') {
      report(result.state);
      locationErrorAjax();
    }
    result.addEventListener('change', () => {
      report(result.state);
    });
  });
}

function report(state) {
  console.log(`Permission ${state}`);
}

handlePermission();

window.setTimeout(()=>{
    getLocation();
},2000);

function locationErrorAjax()
{
    var _token = "{{ csrf_token() }}";
    $.ajax({
            url: "{{ route('/error-geolocation') }}",
            type: 'POST',
            cache: false,
            data: {'_token': _token },
            datatype: 'html',
            success: function(data) {
                //console.log(data);
                $('.location-div').html(data.html);
            }
        });
}

function addressFormAjax()
{
    var _token = "{{ csrf_token() }}";

    var jaf_id = "{{base64_encode($jaf_data->id)}}";

    $.ajax({
        url: "{{ route('/address-form-ajax') }}",
        type: 'POST',
        cache: false,
        data: {'_token': _token,'id':jaf_id},
        datatype: 'html',
        success: function(data) {
            //console.log(data);
            $('.location-div').html(data.html);
        }
    });
}

$(document).ready(function(){

    var s_width = screen.width;
    var s_height = screen.height;

    $('input[name=s_width]').val(s_width);
    $('input[name=s_height]').val(s_height);

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

    $(document).on('change','#front_door, #profile_photo, #id_proof, #id_front_proof, #nearest_landmark',function(){
        var _this = $(this);
        
        var fileTypes = ['jpg', 'jpeg', 'png', 'bmp', 'svg'];

        var file = this.files[0].name;

        var extension = file.split('.').pop().toLowerCase();

        var file_id = _this.attr('id');

        isSuccess = fileTypes.indexOf(extension) > -1;

        if(isSuccess)
        {
            let reader = new FileReader();
            if(file_id=='front_door')
            {
                //     reader.onload = (e) => { 
                //       $('.front-div').removeClass('d-none');
                //       $('#preview-front-door').attr('src', e.target.result); 
                //    }
                //    reader.readAsDataURL(this.files[0]);

                fileUpload('front_door','file',this.files[0],'');
            }
            else if(file_id=='profile_photo')
            {
                //     reader.onload = (e) => { 
                //       $('.profile-div').removeClass('d-none');
                //       $('#preview-profile').attr('src', e.target.result); 
                //    }
                //    reader.readAsDataURL(this.files[0]);

                fileUpload('profile_photo','file',this.files[0],'');
            }
            else if(file_id=='id_proof')
            {
                //     reader.onload = (e) => { 
                //       $('.id-proof-div').removeClass('d-none');
                //       $('#preview-id-proof').attr('src', e.target.result); 
                //    }
                //    reader.readAsDataURL(this.files[0]);

                fileUpload('id_proof','file',this.files[0],'');
            }
            else if(file_id=='id_front_proof')
            {
                //     reader.onload = (e) => { 
                //       $('.id-front-proof-div').removeClass('d-none');
                //       $('#preview-id-front-proof').attr('src', e.target.result); 
                //    }
                //    reader.readAsDataURL(this.files[0]);

               fileUpload('id_front_proof','file',this.files[0],'');
            }
            else if(file_id=='nearest_landmark')
            {
                //     reader.onload = (e) => { 
                //       $('.landmark-div').removeClass('d-none');
                //       $('#preview-landmark').attr('src', e.target.result); 
                //    }
                //    reader.readAsDataURL(this.files[0]);
                fileUpload('nearest_landmark','file',this.files[0],'');
            }
            // else if(file_id=='signature')
            // {
            //     reader.onload = (e) => { 
            //       $('.signature-div').removeClass('d-none');
            //       $('#preview-signature').attr('src', e.target.result); 
            //    }
            //    reader.readAsDataURL(this.files[0]);
            // }
        }
        else
        {
            alert('Select Only jpg, jpeg, png, bmp, svg, file');
            _this.val("");
            if(file_id=='front_door')
            {
                $('#preview-front-door').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
                $('.front-div').addClass('d-none');
            }
            else if(file_id=='profile_photo')
            {
                $('#preview-profile').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
                $('.profile-div').addClass('d-none');
            }
            else if(file_id=='id_proof')
            {
                $('#preview-id-proof').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
                $('.id-proof-div').addClass('d-none');
            }
            else if(file_id=='id_front_proof')
            {
                $('#preview-id-front-proof').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
                $('.id-front-proof-div').addClass('d-none');
            }
            else if(file_id=='nearest_landmark')
            {
                $('#preview-landmark').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
                $('.landmark-div').addClass('d-none');
            }
            // else if(file_id=='signature')
            // {
            //     $('#preview-signature').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
            //     $('.signature-div').addClass('d-none');
            // }
        }

    });

    $(document).on('click','.remove-front-door, .remove-profile-photo, .remove-id-proof, .remove-id-front-proof, .remove-landmark',function(){
        var _this = $(this);
        file_id = _this.attr('id');

        if(file_id=='remove-front-door')
        {
            swal({
                // icon: "warning",
                type: "warning",
                title: "Are you sure want to Remove the Front Door Picture?",
                text: "",
                dangerMode: true,
                showCancelButton: true,
                confirmButtonColor: "#003473",
                confirmButtonText: "YES",
                cancelButtonText: "CANCEL",
                closeOnConfirm: false,
                closeOnCancel: false,
            },
            function(e){
                if(e==true){
                    // $('#preview-front-door').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
                    // $('.front-div').addClass('d-none');
                    // $('#front_door').val('');
                    // $('#front_door_cam').val('');

                    removeFile('front_door','file');

                    swal.close();
                }
                else
                {
                    swal.close();
                }
            });
        }
        else if(file_id=='remove-profile-photo')
        {
            swal({
                // icon: "warning",
                type: "warning",
                title: "Are you sure want to Remove the Profile Picture?",
                text: "",
                dangerMode: true,
                showCancelButton: true,
                confirmButtonColor: "#003473",
                confirmButtonText: "YES",
                cancelButtonText: "CANCEL",
                closeOnConfirm: false,
                closeOnCancel: false,
            },
            function(e){
                if(e==true){
                    // $('#preview-profile').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
                    // $('.profile-div').addClass('d-none');
                    // $('#profile_photo').val('');
                    // $('#profile_photo_cam').val('');

                    removeFile('profile_photo','file');

                    swal.close();
                }
                else{
                    swal.close();
                }
            });
        }
        else if(file_id=='remove-id-proof')
        {
            swal({
                // icon: "warning",
                type: "warning",
                title: "Are you sure want to Remove the ID Proof (Backside) Picture?",
                text: "",
                dangerMode: true,
                showCancelButton: true,
                confirmButtonColor: "#003473",
                confirmButtonText: "YES",
                cancelButtonText: "CANCEL",
                closeOnConfirm: false,
                closeOnCancel: false,
            },
            function(e){
                if(e==true){
                    // $('#preview-id-proof').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
                    // $('.id-proof-div').addClass('d-none');
                    // $('#id_proof').val('');
                    // $('#id_proof_cam').val('');

                    removeFile('id_proof','file');

                    swal.close();
                }
                else
                {
                    swal.close();
                }
            });
        }
        else if(file_id=='remove-id-front-proof')
        {
            swal({
                // icon: "warning",
                type: "warning",
                title: "Are you sure want to Remove the ID Proof (Front) Picture?",
                text: "",
                dangerMode: true,
                showCancelButton: true,
                confirmButtonColor: "#003473",
                confirmButtonText: "YES",
                cancelButtonText: "CANCEL",
                closeOnConfirm: false,
                closeOnCancel: false,
            },
            function(e){
                if(e==true){
                    // $('#preview-id-front-proof').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
                    // $('.id-front-proof-div').addClass('d-none');
                    // $('#id_front_proof').val('');
                    // $('#id_front_proof_cam').val('');

                    removeFile('id_front_proof','file');

                    swal.close();
                }
                else
                {
                    swal.close();
                }
            });
        }
        else if(file_id=='remove-landmark')
        {
            swal({
                // icon: "warning",
                type: "warning",
                title: "Are you sure want to Remove the Nearest Landmark Picture?",
                text: "",
                dangerMode: true,
                showCancelButton: true,
                confirmButtonColor: "#003473",
                confirmButtonText: "YES",
                cancelButtonText: "CANCEL",
                closeOnConfirm: false,
                closeOnCancel: false,
            },
            function(e){
                if(e==true){
                    // $('#preview-landmark').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
                    // $('.landmark-div').addClass('d-none');
                    // $('#nearest_landmark').val('');
                    // $('#nearest_landmark_cam').val('');

                    removeFile('nearest_landmark','file');

                    swal.close();
                }
                else{
                    swal.close();
                }
            });
        }
        // else if(file_id=='remove-signature')
        // {
        //     $('#preview-signature').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
        //     $('.signature-div').addClass('d-none');
        //     $('#signature').val();
        // }

        
    });

    $(document).on('submit', 'form#address_frm', function (event) {
        event.preventDefault();
        //clearing the error msg
        $('p.error-container').html("");
        isPaused = false; 

        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i>  loading...';
        $('.submit').attr('disabled',true);
        $('.form-control').attr('readonly',true);
        $('.form-control').addClass('disabled-link');
        $('.error-control').attr('readonly',true);
        $('.error-control').addClass('disabled-link');
        $('.submit').addClass('btn-opacity');
        $('.error-btn').attr('disabled',true);
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
                    $('.error-control').attr('readonly',false);
                    $('.error-control').removeClass('disabled-link');
                    $('.submit').removeClass('btn-opacity');
                    $('.error-btn').attr('disabled',false);
                    $('.submit').html('Submit');
                    },2000);
                if(response.success==true) {          
                
                    //notify
                    toastr.success("Address Verification Form Submitted Successfully...!");
                    // redirect to google after 5 seconds
                    window.setTimeout(function() {
                        // window.location = "{{ url('/')}}"+"/customers/";
                        location.reload();
                    }, 2000);
                
                }
                //show the form validates error
                if(response.success==false ) { 
                    isPaused = true;

                    var i = 0;                             
                    for (control in response.errors) {  
                        var error_text = control.replace('.',"_");
                        $('.error-'+error_text).html(response.errors[control]);
                        if(i==0)
                        {
                            $('select[name='+control+']').focus();
                            $('input[name='+control+']').focus(); 
                            $('textarea[name='+control+']').focus();
                        }
                        i++;
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
    
    $(document).on('click','#sign-btn',function(){
        $('#sign_modal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $(window).resize(function(){
        var s_width = screen.width;
        var s_height = screen.height;

        $('input[name=s_width]').val(s_width);
        $('input[name=s_height]').val(s_height);
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

    // $(document).on('click','.geoRetryBtn',function(){
    //     //getLocation();
        
    // });

});

// Read cookie
function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

// Create cookie
function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// Delete cookie
function deleteCookie(cname) {
    const d = new Date();
    d.setTime(d.getTime() + (24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=;" + expires + ";path=/";
}

function fileUpload(file_name,file_type,file,file_url)
{
    var _token = "{{ csrf_token() }}";

    var jaf_id = "{{base64_encode($jaf_data->id)}}";

    var lat = $('#geo_latitude').val();

    var long = $('#geo_longitude').val();

    var document_type = null;

    if(file_type=='file')
    {
        if(file_name=='id_front_proof' || file_name=='id_proof')
        {
            document_type = $('#document_type option:selected').val();
        }

        //alert(document_type);

        var fd = new FormData();
        fd.append('_token',_token);
        fd.append('id',jaf_id);
        fd.append('file_name',file_name);
        fd.append('file_type',file_type);
        fd.append('file',file);
        fd.append('latitude',lat);
        fd.append('longitude',long);
        fd.append('document_type',document_type);
        $.ajax({
            url: "{{ route('/address-form-file-upload') }}",
            type: 'POST',
            cache: false,
            //data: {'_token': _token,'id':jaf_id,'file_name':file_name,'file_type':file_type,'file':file,'latitude':lat,'longitude':long,'document_type':document_type},
            data: fd,
            processData: false,
            contentType: false,
            success: function(data) {
                if(data.status)
                {
                    let reader = new FileReader();

                    if(file_name=='front_door')
                    {
                        reader.onload = (e) => { 
                            $('.front-div').removeClass('d-none');
                            $('#preview-front-door').attr('src', e.target.result); 
                        }
                        reader.readAsDataURL(file);
                    }
                    else if(file_name=='profile_photo')
                    {
                        reader.onload = (e) => { 
                            $('.profile-div').removeClass('d-none');
                            $('#preview-profile').attr('src', e.target.result); 
                        }
                        reader.readAsDataURL(file);
                    }
                    else if(file_name=='id_proof')
                    {
                        reader.onload = (e) => { 
                            $('.id-proof-div').removeClass('d-none');
                            $('#preview-id-proof').attr('src', e.target.result); 
                        }
                        reader.readAsDataURL(file);
                    }
                    else if(file_name=='id_front_proof')
                    {
                        reader.onload = (e) => { 
                            $('.id-front-proof-div').removeClass('d-none');
                            $('#preview-id-front-proof').attr('src', e.target.result); 
                        }
                        reader.readAsDataURL(file);
                    }
                    else if(file_name=='nearest_landmark')
                    {
                        reader.onload = (e) => { 
                            $('.landmark-div').removeClass('d-none');
                            $('#preview-landmark').attr('src', e.target.result); 
                        }
                        reader.readAsDataURL(file);
                    }

                }
                else if(data.status==false)
                {
                    toastr.error(data.message);
                }
                // else if(data.status==false)
                // {
                //     var i = 0;                             
                //     for (control in response.errors) {  
                //         var error_text = control.replace('.',"_");
                //         $('.error-'+error_text).html(response.errors[control]);
                //         if(i==0)
                //         {
                //             $('select[name='+control+']').focus();
                //             $('input[name='+control+']').focus(); 
                //             $('textarea[name='+control+']').focus();
                //         }
                //         i++;
                //     }
                // }
            }
        });

    }
    else if(file_type=='cam')
    {
        if(file_name=='id_front_proof' || file_name=='id_proof')
        {
            document_type = $('#document_type option:selected').val();
        }

        $.ajax({
            url: "{{ route('/address-form-file-upload') }}",
            type: 'POST',
            cache: false,
            data: {'_token': _token,'id':jaf_id,'file_name':file_name,'file_type':file_type,'file_url':file_url,'latitude':lat,'longitude':long,'document_type':document_type},
            success: function(data) {
                if(data.status)
                {
                    if(file_name=='front_door')
                    {
                        $('#preview-front-door').attr('src',file_url);

                        $('#front_door_cam').val(file_url);
                    }
                    else if(file_name=='profile_photo')
                    {
                        $('#preview-profile').attr('src',file_url);

                        $('#profile_photo_cam').val(file_url); 

                    }
                    else if(file_name=='id_proof')
                    {
                        $('#preview-id-proof').attr('src',file_url);

                        $('#id_proof_cam').val(file_url);
                    }
                    else if(file_name=='id_front_proof')
                    {
                        $('#preview-id-front-proof').attr('src',file_url);

                        $('#id_front_proof_cam').val(file_url);
                    }
                    else if(file_name=='nearest_landmark')
                    {
                        $('#preview-landmark').attr('src',file_url);

                        $('#nearest_landmark_cam').val(file_url);
                    }
                }
                else if(data.status==false)
                {
                    toastr.error(data.message);
                }
            }
        });

    }
}

function removeFile(file_name,file_type)
{
    var _token = "{{ csrf_token() }}";

    var jaf_id = "{{base64_encode($jaf_data->id)}}";

    $.ajax({
            url: "{{ route('/address-form-file-delete') }}",
            type: 'POST',
            cache: false,
            data: {'_token': _token,'id':jaf_id,'file_name':file_name,'file_type':file_type},
            success: function(data) {
                if(data.status)
                {
                    if(file_name=='front_door')
                    {
                        $('#preview-front-door').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
                        $('.front-div').addClass('d-none');
                        $('#front_door').val('');
                        $('#front_door_cam').val('');
                    }
                    else if(file_name=='profile_photo')
                    {
                        $('#preview-profile').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
                        $('.profile-div').addClass('d-none');
                        $('#profile_photo').val('');
                        $('#profile_photo_cam').val('');
                    }
                    else if(file_name=='id_proof')
                    {
                        $('#preview-id-proof').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
                        $('.id-proof-div').addClass('d-none');
                        $('#id_proof').val('');
                        $('#id_proof_cam').val('');
                    }
                    else if(file_name=='id_front_proof')
                    {
                        $('#preview-id-front-proof').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
                        $('.id-front-proof-div').addClass('d-none');
                        $('#id_front_proof').val('');
                        $('#id_front_proof_cam').val('');
                    }
                    else if(file_name=='nearest_landmark')
                    {
                        $('#preview-landmark').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
                        $('.landmark-div').addClass('d-none');
                        $('#nearest_landmark').val('');
                        $('#nearest_landmark_cam').val('');
                    }
                }
            }
        });
}

function autoSave()
{
    var form = $(this);

    var formData = document.getElementById('address_frm');
    if(typeof(formData) != 'undefined' && formData != null)
    {
        var data = new FormData(formData);
        data.append('type','formtype');

        $.ajax({  
            url:"{{url('/address-verification-form',['id'=>base64_encode($jaf_data->id)])}}",  
            type:"POST",  
            data:data,  
            cache: false,
            contentType: false,
            processData: false,  
            success:function(response)  
            {
                if(response.status==false ) { 
                    
                    toastr.success(response.message);

                    window.setTimeout(()=>{
                        window.location.reload();
                    },2000);
                }
            }
        });
    }
} 

setInterval(function(){   
    // console.log('setinterval me');
    if(isPaused) {
    // console.log('autosave me');
    autoSave();  
    } 
}, 10000);


    window.setTimeout(()=>{
        if(isPaused) {
            window.location.reload();
        }
    },120000);

</script>
<script>
    
    $(document).on('click', '.btnActivateCamera', function () {
        var _this = $(this);

        var videoCapture;

        if(_this.attr('id')=='btnFrontActivateCamera')
        {
            videoCapture = document.getElementById("capture-front-video");
        }
        else if(_this.attr('id')=='btnProfileActivateCamera')
        {
            videoCapture = document.getElementById("capture-profile-video");
        }
        else if(_this.attr('id')=='btnIdActivateCamera')
        {
            videoCapture = document.getElementById("capture-id-video");
        }
        else if(_this.attr('id')=='btnIdFrontActivateCamera')
        {
            videoCapture = document.getElementById("capture-id-front-video");
        }
        else if(_this.attr('id')=='btnLandmarkActivateCamera')
        {
            videoCapture = document.getElementById("capture-landmark-video");
        }
        
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            // access video stream from webcam
            //videoCapture = document.getElementById("capturevideo");
            navigator.mediaDevices.getUserMedia({ video: true }).then(function (stream) {
                // on success, stream it in video tag 
                window.localStream = stream;
                videoCapture.srcObject = stream;
                videoCapture.play();
                activateCamera();
            }).catch(e => {
                // on failure/error, alert message. 
                alert("Please Allow: Use Your Camera!");
            });
        }
    });

    $(document).on('click', '.btnDeactivateCamera,.modal-close-btn', function () {
        // stop video streaming if any
        localStream.getTracks().forEach(function (track) {
            if (track.readyState == 'live' && track.kind === 'video') {
                track.stop();
                deactivateCamera();
            }
        });
    });

    $(document).on('click', '.btnCapture', function () {

        var _this = $(this);

        if(_this.attr('id')=='btnFrontCapture')
        {
            var videoCapture; 

            videoCapture = document.getElementById('capture-front-video');

            document.getElementById('capture-front-canvas').getContext('2d').drawImage(videoCapture, 0, 0, 230, 230);

            html2canvas($("#capture-front-canvas"), {
                useCORS: true,
                onrendered: function(canvas) {

                    let image_data_url = canvas.toDataURL('image/jpeg');

                    // $('#preview-front-door').attr('src',image_data_url);

                    // $('#front_door_cam').val(image_data_url);

                    fileUpload('front_door','cam',null,image_data_url);
                    
                }

            });

            $('.front-div').removeClass('d-none');
        }
        else if(_this.attr('id')=='btnProfileCapture')
        {
            var videoCapture; 

            videoCapture = document.getElementById('capture-profile-video');

            document.getElementById('capture-profile-canvas').getContext('2d').drawImage(videoCapture, 0, 0, 230, 230);

            html2canvas($("#capture-profile-canvas"), {
                useCORS: true,
                onrendered: function(canvas) {

                    let image_data_url = canvas.toDataURL('image/jpeg');

                    // $('#preview-profile').attr('src',image_data_url);

                    // $('#profile_photo_cam').val(image_data_url);

                    fileUpload('profile_photo','cam',null,image_data_url);
                    
                }

            });

            $('.profile-div').removeClass('d-none');
        }
        else if(_this.attr('id')=='btnIdCapture')
        {
            var videoCapture; 

            videoCapture = document.getElementById('capture-id-video');

            document.getElementById('capture-id-canvas').getContext('2d').drawImage(videoCapture, 0, 0, 230, 230);

            html2canvas($("#capture-id-canvas"), {
                useCORS: true,
                onrendered: function(canvas) {

                    let image_data_url = canvas.toDataURL('image/jpeg');

                    // $('#preview-id-proof').attr('src',image_data_url);

                    // $('#id_proof_cam').val(image_data_url);

                    fileUpload('id_proof','cam',null,image_data_url);
                    
                }

            });

            $('.id-proof-div').removeClass('d-none');
        }
        else if(_this.attr('id')=='btnIdFrontCapture')
        {
            var videoCapture; 

            videoCapture = document.getElementById('capture-id-front-video');

            document.getElementById('capture-id-front-canvas').getContext('2d').drawImage(videoCapture, 0, 0, 230, 230);

            html2canvas($("#capture-id-front-canvas"), {
                useCORS: true,
                onrendered: function(canvas) {

                    let image_data_url = canvas.toDataURL('image/jpeg');

                    //$('#preview-id-front-proof').attr('src',image_data_url);

                    //$('#id_front_proof_cam').val(image_data_url);

                    fileUpload('id_front_proof','cam',null,image_data_url);
                    
                }

            });

            $('.id-front-proof-div').removeClass('d-none');
        }
        else if(_this.attr('id')=='btnLandmarkCapture')
        {
            var videoCapture; 

            videoCapture = document.getElementById('capture-landmark-video');

            document.getElementById('capture-landmark-canvas').getContext('2d').drawImage(videoCapture, 0, 0, 230, 230);

            html2canvas($("#capture-landmark-canvas"), {
                useCORS: true,
                onrendered: function(canvas) {

                    let image_data_url = canvas.toDataURL('image/jpeg');

                    // $('#preview-landmark').attr('src',image_data_url);

                    // $('#nearest_landmark_cam').val(image_data_url);

                    fileUpload('nearest_landmark','cam',null,image_data_url);
                    
                }

            });

            $('.landmark-div').removeClass('d-none');
        }
        
    });

    $(document).on('click','.btnClear',function(){
        var _this = $(this);

        if(_this.attr('id')=='btnFrontClear')
        {
            // $('#preview-front-door').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
            // $('.front-div').addClass('d-none');
            // $('#front_door').val('');
            // $('#front_door_cam').val('');
            removeFile('front_door','cam');
        }
        else if(_this.attr('id')=='btnProfileClear')
        {
            // $('#preview-profile').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
            // $('.profile-div').addClass('d-none');
            // $('#profile_photo').val('');
            // $('#profile_photo_cam').val('');

            removeFile('profile_photo','cam');
        }
        else if(_this.attr('id')=='btnIdClear')
        {
            // $('#preview-id-proof').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
            // $('.id-proof-div').addClass('d-none');
            // $('#id_proof').val('');
            // $('#id_proof_cam').val('');

            removeFile('id_proof','cam');
        }
        else if(_this.attr('id')=='btnIdFrontClear')
        {
            // $('#preview-id-front-proof').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
            // $('.id-front-proof-div').addClass('d-none');
            // $('#id_front_proof').val('');
            // $('#id_front_proof_cam').val('');

            removeFile('id_front_proof','cam');
        }
        else if(_this.attr('id')=='btnLandmarkClear')
        {
            // $('#preview-landmark').attr('src',"{{asset('admin/images/profile-default-avtar.jpg')}}");
            // $('.landmark-div').addClass('d-none');
            // $('#nearest_landmark').val('');
            // $('#nearest_landmark_cam').val('');

            removeFile('nearest_landmark','cam');
        }
    });

    function activateCamera() {
        $(".btnActivateCamera").addClass("d-none");
        $(".btnDeactivateCamera").removeClass("d-none");
        $(".capturevideo").removeClass("d-none");
        $(".capturevideo").parent().removeClass("d-none");
        $(".btnCapture").removeClass("d-none");
        $(".capturecanvas").removeClass("d-none");
        $(".capturecanvas").parent().removeClass("d-none");
        $(".btnClear").removeClass("d-none");
    }
    function deactivateCamera() {
        $(".btnDeactivateCamera").addClass("d-none");
        $(".btnActivateCamera").removeClass("d-none");
        $(".capturevideo").addClass("d-none");
        $(".capturevideo").parent().addClass("d-none");
        $(".btnCapture").addClass("d-none");
        $(".capturecanvas").addClass("d-none");
        $(".capturecanvas").parent().addClass("d-none");
        $(".btnClear").addClass("d-none");
    }

    $(document).on('change','.document_type',function(){
        var _this = $(this);

        if(_this.val()=='pan')
        {
            $('.back_lbl').html('');
        }
        else{
            $('.back_lbl').html('*');
        }
    });
    
</script>
@endsection
