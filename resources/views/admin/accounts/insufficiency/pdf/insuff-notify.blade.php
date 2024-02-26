<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="shortcut icon" type="image/x-icon" href="{{url('/').'/admin/images/BCD-favicon.png'}}">
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
        {{-- <table style="width:100%; border-bottom:1px solid #ddd;">
            <tbody>
                <tr>
                    <td style="padding:7px; width:50%; text-align: left;"> {!! Helper::company_logo(Auth::user()->business_id) !!} </td>
                    <td style="padding:7px; width:50%; text-align: right;"> {{ Helper::company_name(Auth::user()->business_id) }} </td>
                </tr>
            </tbody>
        </table> --}}
        <!--logo top table ends -->
    </htmlpageheader>

    <htmlpagefooter name="page-footer">
        <footer>
            <p style="font-size:13px;">
              <b>Confidential</b>
              <br><b>Premier Consultancy & Investigation Private Limited</b><br>
              W-77, Lane No. 10, Mandir Marg, Anupam Garden, Near Sainik Farms, New Delhi - 110068 | INDIA</p>
            <table cellpadding="0" cellspacing="2" width="100%" ><tr><td align="left" style=" font-size:14px;">Powered By: </td><td align="right">{PAGENO} of {nb}</td> </tr></table>
          </footer>
    </htmlpagefooter>
    <div class="body" style="padding:10px; background:#fff;">
        <h4 class="card-title mb-1 mt-3">Insufficiency Details</h4>
            <p class="pb-border"> </p>
            <table class="main-table">
                <thead class="thead-light" style="text-align: center;">
                    <tr>
                        <th style="border-right: 1px solid black;border-bottom: 1px solid black;">#</th>
                        <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Candidate Name</th>
                        <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Check Name</th>
                        <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Check Type</th>
                        <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Activity Type</th>
                        <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Status</th>
                        <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Notes</th>
                        <th style="border-right: 1px solid black;border-bottom: 1px solid black;">Date & Time</th>
                    </tr>
                </thead>
                <tbody style="text-align: center">
                    @if($insuff_log!="" || count($insuff_log)>0)
                        @foreach ($insuff_log as $key => $d)
                            <tr>
                                <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{$key + 1}}</td>
                                <td style="border-right: 1px solid black;border-bottom: 1px solid black;">
                                    {{Helper::user_name($d->candidate_id)}}<br>
                                    <small><strong>Ref No.:</strong>{{Helper::user_reference_id($d->candidate_id)}}</small>
                                </td>
                                <td style="border-right: 1px solid black;border-bottom: 1px solid black;">
                                    {{stripos($d->verification_type,'Manual')!==false ? $d->name.'-'.$d->item_number : $d->name}}
                                </td>
                                <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{$d->verification_type}}</td>
                                <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{$d->activity_type}}</td>
                                <td style="border-right: 1px solid black;border-bottom: 1px solid black;">Raised</td>
                                <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{$d->notes!=NULL?$d->notes:'N/A'}}</td>
                                <td style="border-right: 1px solid black;border-bottom: 1px solid black;">{{date('d-F-Y h:i:s A',strtotime($d->created_at))}}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="text-center">
                            <td style="border-bottom: 1px solid black;" colspan="9">No Data found</td>
                        </tr>
                    @endif                   
                </tbody>
            </table>
     </div>
</body>
</html>