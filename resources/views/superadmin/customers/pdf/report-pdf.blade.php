<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
    table tr td {font-family: 'Roboto-Regular', sans-serif; text-align: left;}
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
</style>
  </head>
    <body style="font-family: calibri;">
    <htmlpageheader name="page-header" ">
      <!--logo top table -->
      <table style="width:100%; border-bottom:1px solid #ddd;">
          <tbody>
              <tr>
                  <td style="padding:7px; width:50%; text-align: left;"> {!! Helper::company_logo($candidate->business_id) !!} </td>
                  <td style="padding:7px; width:50%; text-align: right;"> {{ Helper::company_name($candidate->business_id) }} </td>
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
            
        <?php $x = 0; $z=1; $alpha = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z']; ?>

        <div class="body" style="padding:10px; background:#fff;">
            
            <table class="main-table" style="width:100%; border:2px solid #000; margin-top:15px; border-collapse: collapse;" autosize="1">
                <tbody>
                    <tr>
                        <td style="padding:5px; border:1px solid #666; background:#ccc;"><strong>Name of Subject</strong></td>
                        <td colspan="3" style="padding:7px; border:1px solid #666;">{{ ucfirst($candidate->first_name).' '.ucfirst($candidate->last_name) }}</td>
                    </tr>
                    <tr>
                        <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Client Name</strong></td>
                        <td style="padding:7px; border:1px solid #666;">{{ Helper::company_first_name($candidate->business_id) }}</td>
                        <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Date Of Report</strong></td>
                        <td style="padding:7px; border:1px solid #666;">{{ date('d M Y', strtotime($candidate->created_at)) }}</td>
                    </tr>
                    <tr>
                        <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>{{ Helper::company_first_name(Auth::user()->business_id) }} Reference</strong></td>
                        <td style="padding:7px; border:1px solid #666;">{{ $candidate->id }}</td>
                        <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Client Reference</strong></td>
                        <td style="padding:7px; border:1px solid #666;">
                            @if($candidate->client_emp_code !="" || $candidate->client_emp_code != null)
                                {{ $candidate->client_emp_code }}
                            @else
                             Not Provided
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Level of check</strong></td>
                        <td style="padding:7px; border:1px solid #666;">{{ $candidate->sla_name }}</td>
                        <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Color Code</strong></td>
                        <td style="padding:7px; border:1px solid #666; color:{{Helper::get_approval_status_color_code($candidate->report_id)}}"> {!! Helper::get_approval_status_color_name($candidate->report_id) !!} </td>
                    </tr>
                </tbody>
            </table>

            <table style="width:100%; border:2px solid #000; border-collapse: collapse; margin-top: 15px; vertical-align:middle">
                <tbody>
                    <tr>
                        <td style="padding:7px; font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/red.png') }}" />&nbsp; Major Discrepancy </td>
                        <td style="padding:7px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/orange.png') }}" />&nbsp;Minor Discrepancy</td>
                        <td style="padding:7px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/yellow.png') }}" />&nbsp; Inaccessible for verification/Unable to verify/Inputs required</td>
                        <td style="padding:7px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/green.png') }}" />&nbsp; Clear Report</td>
                    </tr>
                </tbody>
            </table>

            <table style="width:100%; border:2px solid #000; border-collapse: collapse; margin-top: 15px; text-align: center;">
                <tbody>
                    <tr>
                        <td style="padding:7px; background: #ccc; font-size: 30px; border: 1px solid #666; text-transform: uppercase;text-align: center;"> {{ Session::get('reportType') }} Backgorund Report</td>
                    </tr>
                </tbody>
            </table>

            <table style="width:100%; border:4px solid #fff; border-collapse: collapse; margin-top: 15px; text-align: center;">
                <tbody>
                    <tr>
                        <td style="padding:7px; font-size: 30px;text-align: center; "> Executive Summary</td>
                    </tr>
                </tbody>
            </table>
            <?php 
                $service_item_arr=[];
                if( count($report_items) > 0 ){
                    foreach($report_items as $item){
                        $service_item_arr[] = $item->service_id;
                    }   
                }
                $service_item_num = array_count_values($service_item_arr);
                $i=1;
            ?>
            <div class="page-break">
            <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center;">
                <tbody>
                    <tr>
                        <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Components</strong></td>
                        <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Particulars</strong></td>
                        <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Status</strong></td>
                    </tr>
                    
                    @if( count($report_items) > 0 )
                        @foreach($report_items as $item)
                        <?php 
                        $approval_status_name = Helper::get_approval_status_name($item->id); 
                        $input_item_data = $item->jaf_data;
                        $input_item_data_array =  json_decode($input_item_data, true);
                        $particular="";
                        $not_empty = TRUE;
                        foreach($input_item_data_array as $f_item){
                            if($f_item['is_report_output'] == '1'){
                            
                                $data   = array_keys($f_item); 
                                $data1  = array_values($f_item); 
                                $particular .= $data1[0]." ";

                                if($data1[0] == ""){
                                    $not_empty = FALSE;
                                }
                                
                                }   
                            }
                            if($not_empty){
                        ?>  
                    <tr>
                        <td style="padding:7px; border:1px solid #666;"><strong> {{$item->service_name.'-'.$item->service_item_number}}</strong></td>
                        <td style="padding:7px; border:1px solid #666;"> 
                        {{$particular}}
                        </td>
                        <td style="padding:7px; border:1px solid #666;"> 
                                
                                @if( $item->service_id == 15 || $item->service_id == 16 )  
                                    @if($item->approval_status_id == 4)
                                        No Record Found
                                        @else
                                        {{ $approval_status_name }} 
                                    @endif
                                @else
                                {{ $approval_status_name }} 
                                @endif
                        </td>
                    </tr>
                    <?php } ?>
                        @endforeach
                    @endif
                   
                </tbody>
            </table>
            </div>
            <?php  $i=1;  ?>

            @if( count($report_items) > 0 )
                @foreach($report_items as $item)
                <?php 
                        $approval_status_name = Helper::get_approval_status_name($item->id); 
                        $input_item_data = $item->jaf_data;
                        $input_item_data_array =  json_decode($input_item_data, true);
                        $particular="";
                        $not_empty = TRUE;
                        foreach($input_item_data_array as $f_item){
                            if($f_item['is_report_output'] == '1'){
                            
                                $data   = array_keys($f_item); 
                                $data1  = array_values($f_item); 
                                $particular .= $data1[0]." ";

                                if($data1[0] == ""){
                                    $not_empty = FALSE;
                                }
                                
                                }   
                            }
                            if($not_empty){
                        ?>  
            <!--  -->
            <pagebreak />
            <div class="page-break">
            <table style="width:100%; border:4px solid #fff; border-collapse: collapse; margin-top: 1px;" autosize="1">
                <tbody>
                    <tr>
                        <td style="padding:7px; border:1px solid #666;">
                            <strong>{{$item->service_name}}<?php if($service_item_num[$item->service_id] >1){echo '-'.$i; $i++;}  ?>
                         Verification</strong>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center;" autosize="1">
                <tbody>
                    <tr>
                        <td colspan="2" style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Details as per Subjects Application Form</strong></td>
                        <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Verification Remarks</strong></td>
                    </tr>
                    <!-- dynamic items -->
                    <?php
                        $input_item_data = $item->jaf_data;
                        $input_item_data_array =  json_decode($input_item_data, true);
                        $jafArray         = json_decode($jaf->form_data_all,true); 
                        $form_items = Helper::get_report_form_inputs($item->service_id); 
                    ?>  
                    @foreach($input_item_data_array as $f_item)
                    @if($f_item['is_report_output'] == '1'){
                    <?php $remarks= '-'; 
                        if($f_item['remarks']=='Yes')
                        {
                            $remarks= 'Yes';
                        }
                        $data   = array_keys($f_item); 
                        $data1  = array_values($f_item); 
                    ?>
                    <tr>
                        <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong> {{ $data[0] }} </strong></td>
                        <td style="padding:7px; border:1px solid #666;">
                        {{$data1[0]}}
                        </td>
                        <td style="padding:7px; border:1px solid #666;">{{ $remarks }}</td>
                    </tr>
                   
                    @endif
                    @endforeach
                    <!-- dynamic items end -->
                    <tr>
                        <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Verified By</strong></td>
                        <td colspan="2" style="padding:7px; border:1px solid #666;">{{ Helper::get_report_verified_by($item->id) }}</td>
                    </tr>
                    @if($item->service_id == 15 || $item->service_id == 16)
                    
                        <tr>
                            <td width="30%" style="padding:7px; background:#ccc;border:1px solid #666;"><strong>Court</strong></td>
                            <td width="50%" style="padding:7px; background:#ccc;border:1px solid #666;"><strong>Court Name</strong></td>
                            <td width="20%" style="padding:7px; background:#ccc;border:1px solid #666;"><strong>Result</strong></td>
                        </tr>
                        <tr>
                            <td width="30%" style="padding:7px; border:1px solid #666;">District Court/ Lower Court/ Civil Court & Small Causes</td>
                            <td width="50%" style="padding:7px; border:1px solid #666;">District Courts of {{ $item->district_court_name }}</td>
                            <td width="20%" style="padding:7px; border:1px solid #666;">{{ $item->district_court_result }}</td>
                        </tr>
                        <tr>
                            <td width="30%" style="padding:7px; border:1px solid #666;">High Court</td>
                            <td width="50%" style="padding:7px; border:1px solid #666;">High Court of Jurisdiction at {{ $item->high_court_name }}</td>
                            <td width="20%" style="padding:7px; border:1px solid #666;">{{ $item->high_court_result }}</td>
                        </tr>
                        <tr>
                            <td width="30%" style="padding:7px; border:1px solid #666;">Supreme Court</td>
                            <td width="50%" style="padding:7px; border:1px solid #666;">Supreme Court of India, New Delhi</td>
                            <td width="20%" style="padding:7px; border:1px solid #666;">{{ $item->supreme_court_result }}</td>
                        </tr>
                       
                    @endif
                    <tr>
                        <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Comments</strong></td>
                        <td colspan="2" style="padding:7px; border:1px solid #666;"> <p style="background:yellow;">{{ Helper::get_report_comments($item->id) }} </p></td>
                    </tr>
                    <tr>
                        <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Additional Comments</strong></td>
                        <td colspan="2" style="padding:7px; border:1px solid #666;">{{ Helper::get_report_additional_comments($item->id) }}</td>
                    </tr>
                </tbody>
            </table>
                        
            
            <!-- imaages  -->
            <?php $y =1; $num = 1; $cont = ""; $item_files = Helper::getReportAttachFiles($item->id); //print_r($item_files); ?>
            @if(count($item_files) > 0)
            <pagebreak />
           
                @foreach($item_files as $file)
                    @if($num > 1)
                        <?php $cont = '<p style="padding:0px; background:yellow">Cont...</p>'; ?>
                    @endif

                    @if($file['attachment_type'] == 'main')
                        @if($item->service_id == 15 || $item->service_id == 16 || $item->service_id == 2)
                            @if($num == 1)
                                <span style=" padding:0px; background-color:yellow">Annexure "{{ $alpha[$x].'-'.$y }}-"</span>
                            @elseif($num > 1)
                            <span style="padding:0px; background-color:yellow">Cont...</span>
                            @endif
                            <div style="border:solid 1px #ccc; text-align:center; margin-top:15px;">
                                <img src="{{ $file['filePath'] }}" style="width: 160mm; height: 95mm; margin: 0;" /> 
                            </div>
                        @else
                            @if($num == 1)
                                <span style=" padding:0px; background-color:yellow">Annexure "{{ $alpha[$x].'-'.$y }}-"</span>
                            @elseif($num > 1)
                            <span style="padding:0px; background-color:yellow">Cont...</span>
                            @endif
                        <div style="border:solid 1px #ccc; text-align:center; margin-top:15px;">
                            <img src="{{ $file['filePath'] }}" style='width:160mm; height: 100%; margin:auto; margin-top:10px; padding:10px; ' >
                        </div>
                        @endif
                    @endif
                   
                     <!-- supportings -->
                    @if($file['attachment_type'] == 'supporting')
                        @if($item->service_id == 15 || $item->service_id == 16 || $item->service_id == 2)
                        
                            <div style="border:solid 1px #ccc; text-align:center; margin-top:15px;">
                                <img src="{{ $file['filePath'] }}" style="width: 160mm; height: 95mm; margin: 0;" /> 
                            </div>
                            @else
                            
                            <div style="border:solid 1px #ccc; text-align:center; margin-top:15px;">
                                <img src="{{ $file['filePath'] }}" style='width:160mm; height: 100%; margin:auto; margin-top:10px; padding:10px; ' >
                            </div>
                            @endif  
                    @endif
                    <?php $y++; $num++;?>

                @endforeach
                @endif
            <!-- images -->
            </div>
            <?php } $x++;  ?>
            <!-- row close -->
            @endforeach
            @endif
            <!-- particular block -->
            <pagebreak />
            <p><b>Restrictions & Limitations:</b></p>
            <p style="font-size: 14px; text-align:justify;">Our reports and comments are confidential in nature and are meant only for the internal use of the client to make an assessment of the background of the applicant. They are not intended for publication or circulation to or sharing with any other person including the applicant or are they to be reproduced or used for any other purpose, in whole or in part, without our prior written consent in each specific instance. We request you recognize that we are not the sources of the data gathered and our findings are based on the information made available to us. Should additional information or documentation become available to us, which impacts our conclusions reached in our reports, we reserve the right to amend our findings through our report accordingly. We expressly disclaim all responsibility or liability for any costs, damages, losses, liabilities, expenses incurred by anyone as a result of circulation, publication, reproduction or use of our reports contrary to the provisions of this paragraph. You will appreciate that due to factors beyond our control, it may be possible that we are unable to get all the necessary information. Because of the limitations mentioned above, the results of our work with respect to the background checks should be considered only as a guide. Our reports and comments should not be considered a definitive pronouncement on the individual.</p>
            <br><br>
            <h3 style="text-align:center;"> - End of Report - </h3>
            <!--  -->
          
        </div>
       
    </body>
</html>