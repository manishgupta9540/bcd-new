<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; color: #74787E; height: 100%; hyphens: auto; line-height: 1.4; margin: 0; -moz-hyphens: auto; -ms-word-break: break-all; width: 100% !important; -webkit-hyphens: auto; -webkit-text-size-adjust: none; word-break: break-word;">
    <style>
        @media  only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }
            .footer {
                width: 100% !important;
            }
        }
        @media  only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
        <tr>
            <td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                    <tr>
                        <td class="header" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 25px 0; text-align: center;">
                            <a href="{{Config::get('app.admin_url')}}/" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 19px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;">
                                {{-- <img src="{{asset('admin/images/BCD-Logo2.png')}}"> --}}
                                {!! Helper::company_logo($sender->business_id) !!}
                            </a>
                        </td>
                    </tr>
                    <!-- Email Body -->
                    <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #FFFFFF; border-bottom: 1px solid #EDEFF2; border-top: 1px solid #EDEFF2; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #FFFFFF; margin: 0 auto; padding: 0; width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px;">
                                <!-- Body content -->
                                <tr>
                                    <td class="content-cell" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px;">
                                        <h1 style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #2F3133; font-size: 19px; font-weight: bold; margin-top: 0; text-align: left;">Hello {{$name}},</h1>

                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">You have received Daily Report, Regarding Cases & Insufficiency </p>

                                        {{-- <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">If you have seen already ignore it.</p> --}}

                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 10px; text-align: left;">Details are :-</p>

                                        <hr class="w-25">

                                        {{-- <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 30px auto; padding: 0; text-align: left; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                                            <tr>
                                                <th style="border-bottom 1px solid #333;">Daily Report</th>
                                                <th style="border 1px solid #333;">{{date('d M Y')}}</th>
                                            </tr>
                                            <tr>
                                                <td style="border 1px solid #ccc;">&nbsp;</td>
                                                <td style="border 1px solid #ccc;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td style="border 1px solid #ccc;"></td>
                                                <td style="border 1px solid #ccc;">Cases</td>
                                            </tr>
                                            <tr>
                                                <td style="border 1px solid #ccc;">Receiving Cases</td>
                                                <td style="border 1px solid #ccc;"></td>
                                            </tr>
                                            <tr>
                                                <td style="border 1px solid #ccc;">FR Delivered Yesterday</td>
                                                <td style="border 1px solid #ccc;"></td>
                                            </tr>
                                            <tr>
                                                <td style="border 1px solid #ccc;">FR out TAT</td>
                                                <td style="border 1px solid #ccc;"></td>
                                            </tr>
                                            <tr>
                                                <td style="border 1px solid #ccc;">FR in TAT</td>
                                                <td style="border 1px solid #ccc;"></td>
                                            </tr>
                                            <tr>
                                                <td style="border 1px solid #ccc;">FR in the Month</td>
                                                <td style="border 1px solid #ccc;"></td>
                                            </tr>
                                            <tr>
                                                <td style="border 1px solid #ccc;">FR in the Month in TAT</td>
                                                <td style="border 1px solid #ccc;"></td>
                                            </tr>
                                            <tr>
                                                <td style="border 1px solid #ccc;">FR in the Month out TAT</td>
                                                <td style="border 1px solid #ccc;"></td>
                                            </tr>
                                            <tr>
                                                <td style="border 1px solid #ccc;">Total Pending Cases</td>
                                                <td style="border 1px solid #ccc;"></td>
                                            </tr>
                                            <tr>
                                                <td style="border 1px solid #ccc;">Pending Cases in TAT</td>
                                                <td style="border 1px solid #ccc;"></td>
                                            </tr>
                                            <tr>
                                                <td style="border 1px solid #ccc;">Pending Cases Out TAT</td>
                                                <td style="border 1px solid #ccc;"></td>
                                            </tr>
                                            <tr>
                                                <td style="border 1px solid #ccc;">Insuff Added Yesterday</td>
                                                <td style="border 1px solid #ccc;"></td>
                                            </tr>
                                            <tr>
                                                <td style="border 1px solid #ccc;">Total Insuff</td>
                                                <td style="border 1px solid #ccc;"></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="border 1px solid #ccc;">*FR - Final Report</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="border 1px solid #000;">This is a Computer Generated Report</td>
                                            </tr>
                                        </table> --}}

                                        <div>

                                            <table class="action" width="100%" cellpadding="0" cellspacing="0"
                                                style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 30px auto 0; padding: 0; text-align: center; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                                                <tr>
                                                    <th
                                                        style="background-color: #eee; text-align: left; padding: 8px 12px; color: #333; font-size: 14px;">
                                                        Daily Report </th>

                                                    <th style="background-color: #eee; text-align: left; padding: 8px 12px; color: #333; font-size: 14px;">
                                                        {{date('d M Y')}}
                                                    </th>
                                                </tr>
                                                {{-- <tr>
                                                    <td style="padding: 12px;">
                                                        &nbsp;
                                                    </td>
                                                    <td style="padding: 12px; color: #333; font-weight: 600; font-size: 13px; text-align: left;">
                                                        &nbsp;
                                                    </td>
                                                </tr> --}}
                                                
                                            </table>

                                            <table class="action" width="100%" cellpadding="0" cellspacing="0"
                                                style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0 auto 0; padding: 0; text-align: center; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                                                <tr>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #888; font-size: 13px; min-width: 50px; text-align: left;">
                                                        &nbsp;
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:340px; text-align: left;">
                                                        Cases
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:340px; text-align: left;">
                                                        Amount
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-size: 13px; min-width: 340px; text-align: left;">
                                                        Received cases Yesterday
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        {{$receiving_case}}
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        ₹ {{number_format(str_replace(',','',number_format($receive_total_amount_y,2)),2,'.','')}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-size: 13px; min-width: 340px; text-align: left;">
                                                        FR Delivered Yesterday
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        {{$report_delivered}}
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-size: 13px; min-width: 340px; text-align: left;">
                                                        ₹ {{str_replace(',','',number_format($fr_total_amount_y,2))}}
                                                    </td>
                                                </tr>
                                                <tr>

                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-size: 13px; min-width: 340px; text-align: left;">
                                                        FR Out TAT
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        {{$fr_out_tat_y}}
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        &nbsp;
                                                    </td>
                                                </tr>
                                                <tr>

                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-size: 13px; min-width: 340px; text-align: left;">
                                                        FR In TAT
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        {{$fr_in_tat_y}}
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        &nbsp;
                                                    </td>
                                                </tr>
                                                <tr>

                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-size: 13px; min-width: 340px; text-align: left;">
                                                        FR in the Month
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        {{$fr_month}}
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        ₹ {{number_format(str_replace(',','',number_format($fr_total_amount_m,2)),2,'.','')}}
                                                    </td>
                                                </tr>
                                                <tr>

                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-size: 13px; min-width: 340px; text-align: left;">
                                                        FR in the Month in TAT
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        {{$fr_in_tat_m}}
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        &nbsp;
                                                    </td>
                                                </tr>
                                                <tr>

                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-size: 13px; min-width: 340px; text-align: left;">
                                                        FR in the Month out TAT
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        {{$fr_out_tat_m}}
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        &nbsp;
                                                    </td>
                                                </tr>
                                                <tr>

                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-size: 13px; min-width: 340px; text-align: left;">
                                                        Total pending cases
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        {{$wip_count}}
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        ₹ {{number_format(str_replace(',','',number_format($wip_total_amount,2)),2,'.','')}}
                                                    </td>
                                                </tr>
                                                <tr>

                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-size: 13px; min-width: 340px; text-align: left;">
                                                        Pending Cases in TAT
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        {{$wip_in}}
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        &nbsp;
                                                    </td>
                                                </tr>
                                                <tr>

                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-size: 13px; min-width: 340px; text-align: left;">
                                                        Pending Cases Out TAT
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        {{$wip_out}}
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        &nbsp;
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-size: 13px; min-width: 340px; text-align: left;">
                                                        Insuff Added Yesterday
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        {{$insuff}}
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        ₹ {{number_format(str_replace(',','',number_format($insuff_total_amount_y,2)),2,'.','')}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-size: 13px; min-width: 340px; text-align: left;">
                                                        Total Insuff
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        {{$total_insuff}}
                                                    </td>
                                                    <td
                                                        style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        ₹ {{number_format(str_replace(',','',number_format($insuff_total_amount,2)),2,'.','')}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="padding: 12px color: #333; font-size: 13px; min-width: 340px; text-align: left;">
                                                        &nbsp;
                                                    </td>
                                                    <td
                                                        style="padding: 12px color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        &nbsp;
                                                    </td>
                                                    <td
                                                        style="padding: 12px color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        &nbsp;
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="padding: 12px; color: #333; font-size: 13px; min-width: 340px; text-align: left;">
                                                        *FR - Final Report
                                                    </td>
                                                    <td
                                                        style="padding: 12px; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        &nbsp;
                                                    </td>
                                                    <td
                                                        style="padding: 12px; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                        &nbsp;
                                                    </td>
                                                </tr>

                                            </table>

                                        {{-- <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;"><b>Date & Time : </b> {{date('Y-m-d h:i A')}}</p> --}}

                                        <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 30px auto; padding: 0; text-align: left; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;"> 
                                            <tr>
                                                <td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                        <tr>
                                                            <td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                                <table border="0" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                                    <tr>
                                                                        <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                                            {{-- <span style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0;">For more details login to your account:</span> --}}
                                                                            <a href="{{$url}}" class="button button-blue" target="_blank" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-radius: 3px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16); color: #FFF; display: inline-block; text-decoration: none; -webkit-text-size-adjust: none; background-color: #3097D1; border-top: 10px solid #3097D1; border-right: 18px solid #3097D1; border-bottom: 10px solid #3097D1; border-left: 18px solid #3097D1;">Click Here to Download the Excel Report</a>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                        {{-- <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;"><b>Password: </b> {{$password}}</p> --}}
                                        
                                        {{-- <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Enjoy the best features of BCD System.</p> --}}
                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Regards, <br> {{Helper::company_name($sender->business_id)}} <br> Thank you <br>
                                            <a href="https://my-bcd.com">https://my-bcd.com</a>
                                        </p>
                                        <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-top: 1px solid #EDEFF2; margin-top: 25px; padding-top: 25px;">
                                            <tr>
                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #db1f1f; line-height: 1.5em; margin-top: 0; text-align: left; font-size: 12px;">
                                                        Note: Please don't reply on this email, this mail is generated by system .
                                                    </p>
                                                </td>
                                            </tr>
                                            {{-- <tr>
                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; line-height: 1.5em; margin-top: 0; text-align: left; font-size: 12px;">
                                                        If you’re having trouble clicking the "Access here" button, copy and paste the URL below
                                                        into your web browser: <a href="{{Config::get('app.user_url')}}/login" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #3869D4;"></a>
                                                        <a href="{{Config::get('app.admin_url')}}/login" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #3869D4;">{{Config::get('app.admin_url')}}/login
                                                        </a>
                                                    </p>
                                                </td>
                                            </tr> --}}
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                            <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0 auto; padding: 0; text-align: center; width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px;">
                                <tr>
                                    <td class="content-cell" align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px;">
                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #AEAEAE; font-size: 12px; text-align: center;">© {{date('Y')}} {{env('MAIL_FROM_NAME')}}. All rights reserved.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html> 