<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {{-- <link href="{{ asset('admin/gull/dist-assets/css/themes/lite-purple.min.css') }}" rel="stylesheet" /> --}}
    {{-- <link href="{{ asset('admin/css/style.css?ver=1.7') }}" rel="stylesheet" /> --}}
    
    <style type="text/css">
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
        table {width: 100%; border-right: 1px solid black; border-top: 1px solid black; border-left: 1px solid black;}
        table tr td {font-family: 'Roboto-Regular', sans-serif; text-align: center;}
        footer {
                position: fixed; 
                bottom: -10px; 
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
        .pb-border{border-bottom: 1px solid #ddd; padding-top: 0px;padding-bottom: 6px;}
    </style>
    
</head>
<body>
    <htmlpageheader name="page-header" >
        <!--logo top table -->
        <table style="width:100%; border-bottom:1px solid #ddd;">
            <tbody>
                <tr>
                    <td style="padding:7px; width:50%; text-align: left;"> {!! Helper::company_logo(Auth::user()->business_id) !!} </td>
                    <td style="padding:7px; width:50%; text-align: right;"> {{ Helper::company_name(Auth::user()->business_id) }} </td>
                </tr>
            </tbody>
        </table>
        <!--logo top table ends -->
    </htmlpageheader>

    <htmlpagefooter name="page-footer">
        <footer>
            <p style="font-size:13px;">
              <b>Confidential</b>
              <br><b>Premier Consultancy & Investigation Private Limited</b><br>
              W-77, Lane No. 10, Mandir Marg, Anupam Garden, Near Sainik Farms, New Delhi - 110068 | INDIA</p>
            <table cellpadding="0" cellspacing="2" width="100%" ><tr><td align="left" style=" font-size:14px;">Powered By: <img src="{{ Helper::company_logo_path(Auth::user()->business_id) }}" width="110" style="vertical-align:bottom"> </td><td align="right">{PAGENO} of {nb}</td> </tr></table>
          </footer>
    </htmlpagefooter>
    <div class="body" style="padding:10px; background:#fff;">
        <h4 class="card-title mb-1 mt-3">{{$service_d->name}}</h4>
            <p class="pb-border">  API Usage Details </p>
            <table class="main-table">
                <thead class="thead-light" style="text-align: center;">
                    <tr>
                        <th style="border-right: 1px solid black;border-bottom: 1px solid black;">#</th>
                        @if($service_d->name=='Aadhar' || $service_d->name=='aadhar')
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Aadhar No.</th>
                        @elseif($service_d->name=='PAN' || $service_d->name=='pan')
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">PAN No.</th>
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Name</th>
                        @elseif($service_d->name=='Voter ID' || $service_d->name=='voter id')
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Voter ID No.</th>
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Name</th>
                        @elseif($service_d->name=='RC' || $service_d->name=='rc')
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">RC No.</th>
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Name</th>
                        @elseif($service_d->name=='Passport' || $service_d->name=='passport')
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Passport No.</th>
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Name</th>
                        @elseif($service_d->name=='Driving' || $service_d->name=='driving')
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">DL No.</th>
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Name</th>
                        @elseif($service_d->name=='Bank Verification' || $service_d->name=='bank verification')
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Bank Account No.</th>
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Name</th>
                        @elseif($service_d->name=='GSTIN' || $service_d->name=='gstin')
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">GST No.</th>
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Name</th>
                        @elseif($service_d->name=='Telecom' || $service_d->name=='telecom')
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Phone No.</th>
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Name</th>
                        @elseif(stripos($service_d->type_name,'e_court')!==false)
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Name</th>
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Father Name</th>
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Address</th>
                        @elseif(stripos($service_d->type_name,'upi')!==false)
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">UPI ID</th>
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Name</th>
                        @elseif(stripos($service_d->type_name,'cin')!==false)
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">CIN Number</th>
                            <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Company Name</th>
                        @endif
                        <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Used By</th>
                        <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Date & Time</th>
                        <th style="border-bottom: 1px solid black;width:10%">Price</th>
                    </tr>
                </thead>
                <tbody style="text-align: center">
                    @if($data!="" && count($data)>0)
                        @foreach ($data as $key => $d)
                            <tr>
                                <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{$key + 1}}</td>
                                @if($service_d->name=='Aadhar' || $service_d->name=='aadhar')
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{$d->aadhar_number}}</td>
                                @elseif($service_d->name=='PAN' || $service_d->name=='pan')
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{$d->pan_number}}</td>
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{ucfirst($d->full_name)}}</td>
                                @elseif($service_d->name=='Voter ID' || $service_d->name=='voter id')
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{$d->voter_id_number}}</td>
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{ucfirst($d->full_name)}}</td>
                                @elseif($service_d->name=='RC' || $service_d->name=='rc')
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{$d->rc_number}}</td>
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{ucfirst($d->owner_name)}}</td>
                                @elseif($service_d->name=='Passport' || $service_d->name=='passport')
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{$d->passport_number}}</td>
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{ucfirst($d->full_name)}}</td>
                                @elseif($service_d->name=='Driving' || $service_d->name=='driving')
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{$d->dl_number}}</td>
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{ucfirst($d->name)}}</td>
                                @elseif($service_d->name=='Bank Verification' || $service_d->name=='bank verification')
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{$d->account_number}}</td>
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{ucfirst($d->full_name)}}</td>
                                @elseif($service_d->name=='GSTIN' || $service_d->name=='gstin')
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{$d->gst_number}}</td>
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{ucfirst($d->legal_name)}}</td>
                                @elseif($service_d->name=='Telecom' || $service_d->name=='telecom')
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{$d->mobile_no}}</td>
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{ucfirst($d->full_name)}}</td>
                                @elseif(stripos($service_d->type_name,'e_court')!==false)
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{ucfirst($d->name)}}</td>
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{ucfirst($d->father_name)}}</td>
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{$d->address}}</td>
                                @elseif(stripos($service_d->type_name,'upi')!==false)
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{$d->upi_id}}</td>
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{ucfirst($d->name)}}</td>
                                @elseif(stripos($service_d->type_name,'cin')!==false)
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{$d->cin_number}}</td>
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{ucfirst($d->company_name)}}</td>
                                @else
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;"></td>
                                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;"></td>
                                @endif
                                <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{Helper::user_name($d->user_id)}}</td>
                                <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{date('d-F-Y h:i A',strtotime($d->created_at))}}</td>
                                <td style="border-bottom: 1px solid black;">₹ {{$d->price}}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="text-center">
                            <td style="border-bottom: 1px solid black;" colspan="6">No Data found</td>
                        </tr>
                    @endif                   
                </tbody>
            </table>
     </div>
</body>
</html>