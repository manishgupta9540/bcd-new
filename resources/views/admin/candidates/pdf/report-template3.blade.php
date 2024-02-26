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
    <body style="font-family: calibri; ">
        @php
            use App\Traits\S3ConfigTrait;
        @endphp
       
        <htmlpageheader name="page-header">
            <!--logo top table -->
            <table style="width:100%; border-bottom:1px solid #ddd;">
                <tbody>
                    <tr>
                        <td style="padding:7px; width:40%; text-align: left;"> <small><b>Registered Office:</b><br>
                            W-77, Lane No. 10,<br> Anupam Garden,<br> New Delhi - 110068</small></p> </td>
                        {{-- <td style="padding:7px; width:50%; text-align: right;"> {{ Helper::company_name($candidate->business_id) }} </td> --}}
                        <td style="padding:7px; width:50%; text-align: right;"><img src="{{ Helper::company_logo_path($business_id) }}" width="200" style="vertical-align:bottom"> </td>
                    </tr>
                </tbody>
            </table>
            <!--logo top table ends -->
        </htmlpageheader> 
        
   

    <htmlpagefooter name="page-footer">
      <footer style="height: 200px;">
        <table cellpadding="0" cellspacing="2" width="95%" ><tr><td align="right" style=" font-size:100px; margin-right:10px; "><img src="{{ asset('report_template/sign01.png') }}" width="310px" style="vertical-align:bottom"> </td> </tr></table>

        <table style="width:100%; border:none; border-collapse: collapse; text-align: center;">
            <tbody>
                <tr>
                    <td style=" font-size: 30px; border:none ; text-align: left;" ><small><u> Website:<a href="">{{ url('/')}}</a> </u></small></td>
                    {{-- <td style="font-size: 30px; border: none ; text-align: right;" ><small><u>Verifier Name:TASWAR HUSSAIN</u></small> </td> --}}
                    <td style="font-size: 30px; border: none ; text-align: right;" ><small><u>Verifier Name:Gayatri Kanojia </u></small> </td>
                    {{-- <td style="font-size: 30px; border: none ; text-align: center;" ><small> </small></td> --}}
                </tr>
                <tr>
                    {{-- <td style=" font-size: 30px; border:none ;  text-align: left;" ><small><u>Email: clientservices@premier-consultancy.com</u></small></td> --}}
                    <td style=" font-size: 30px; border:none ;  text-align: left;" ><small><u>Email: gayatri.pcil@premier-consultancy.com</u></small></td>
                    <td style=" font-size: 30px; border: none ; text-align: center; padding-left:80px;" > <small><u>Designation: CAM</u></small> </td>
                    {{-- <td style="font-size: 15px; border: none ; text-transform: uppercase;text-align: left;" ><small></small> </td> --}}

                </tr>
            </tbody>
        </table>
        @php 
        $businessId = $candidate->business_id;
        $footeraddress = Helper::get_footer_address($businessId);
        $defaultaddress = Helper::get_default_address($candidate->parent_id);
        @endphp
        <p style="font-size:20px;">
          {{-- <b>Confidential</b> --}}
           {{-- <!-- If Client Name is Cogent -->
           @if($candidate->business_id==1831) --}}
              <br><b>{{ Helper::company_name($candidate->parent_id) }}</b><br>
           {{-- @else
              <br><b>{{ Helper::company_name($candidate->business_id) }}</b><br>
           @endif --}}
          @if($footeraddress!=NULL){{$footeraddress}}@else {{ $defaultaddress->address_line1 }} {{ $defaultaddress->city_name }}-{{ $defaultaddress->zipcode }}@endif</p>
      </footer>
    </htmlpagefooter>
            
        <?php $x = 0; $z=1; $alpha = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        
    //    if (condition) {
    //        # code...
    //    }
         $registered_company_addr=Helper::get_registered_company_addr($candidate->parent_id);
        ?>
      
        
            <div class="body" style="padding:10px; background:#fff;">
                <table style="width:100%; border:4px solid #fff; border-collapse: collapse; margin-top: 15px; text-align: center;">
                    <tbody>
                        <tr>
                            <td style="padding:7px; font-size: 20px; text-align: center; "><u> {{ Helper::report_company_name($candidate->business_id) }} </u></td>
                        </tr>
                    </tbody>
                </table>
                <table  style="width:75%; border:2px solid #000; border-collapse: collapse; margin-top: 15px; text-align: center; margin-left:20%;">
                    <tbody>
                        <tr>
                            <td style="padding:5px; background: #ccc; font-size: 20px; border: 1px solid #666; text-transform: uppercase;text-align: center;"> {{ Session::get('reportType') }} Background Report</td>
                        </tr>
                    </tbody>
                </table>
                
                <table class="main-table" style="width:100%; border:2px solid #000; margin-top:15px; border-collapse: collapse;" autosize="1">
                    <tbody>
                        <tr>
                            <td style="padding:5px; border:1px solid #666; background:#ccc;"><strong>Name of Subject</strong></td>
                            <td colspan="3" style="padding:7px; border:1px solid #666;">{{ ucwords($candidate->name) }}</td>
                        </tr>
                        <tr>
                            <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Case Initiated Date</strong></td>
                            <td style="padding:7px; border:1px solid #666;">{{ $report_data->initiated_date && date('Y-m-d',strtotime($report_data->initiated_date))!='1970-01-01' ? date('d M Y',strtotime($report_data->initiated_date)):date('d M Y ',strtotime($candidate->initiated_date)) }}</td>
                            <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Date Of Report</strong></td>
                            <td style="padding:7px; border:1px solid #666;"> 
                                @php
                                    $hide = Helper::report_custom($candidate->business_id);
                                @endphp
                                @if($hide=='enable' && ($candidate->revised_date !="" && $candidate->revised_date != null))
                                    {{ date('d M Y', strtotime($candidate->revised_date)) }}
                                @elseif($candidate->is_report_complete == 1 && $candidate->report_complete_created_at != null)
                                    {{ date('d M Y', strtotime($candidate->report_complete_created_at)) }}
                                @elseif($report_data->generated_at !="" || $report_data->generated_at != null)
                                    {{ date('d M Y', strtotime($report_data->generated_at)) }}
                                @else
                                    {{ date('d M Y', strtotime($report_data->created_at)) }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Insuff raised date</strong></td>
                            <td style="padding:7px; border:1px solid #666;">{{ date('d M Y', strtotime($report_data->insuff_raised_date))!=null && date('d M Y', strtotime($report_data->insuff_raised_date))!='01 Jan 1970'?date('d M Y', strtotime($report_data->insuff_raised_date)):'Not Applicable' }}</td>
                            <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Insuff cleared date</strong></td>
                            <td style="padding:7px; border:1px solid #666;">{{ date('d M Y', strtotime($report_data->insuff_cleared_date))!=null && date('d M Y', strtotime($report_data->insuff_cleared_date))!='01 Jan 1970'?date('d M Y', strtotime($report_data->insuff_cleared_date)):'Not Applicable' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>
                                {{ Helper::report_company_first_name($candidate->id) }} Reference</strong></td>
                            <td style="padding:7px; border:1px solid #666;">{{ $candidate->display_id }}</td>
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
                            <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Level of Check</strong></td>
                            <td style="padding:7px; border:1px solid #666;">
                                @if($candidate->entity_code)
                                    {{ $candidate->entity_code }}
                                @else
                                    {{ $candidate->sla_name }}
                                @endif
                            </td>
                            <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Color Code</strong></td>
                            @if($candidate->is_manual_mark==1)
                                <td style="padding:7px; border:1px solid #666; color:green"> Green </td>
                            @elseif($candidate->is_manual_mark==2)
                                <td style="padding:7px; border:1px solid #666; color:rgb(83,83,83)"> Stopped </td>
                            @else
                                @if($candidate->approval_status_id==5 || $candidate->approval_status_id==6 || $candidate->approval_status_id==7)
                                    @php
                                        $approval_status_name = Helper::get_report_approval_status_name($candidate->report_id);
                                    @endphp
                                    <td style="padding:7px; border:1px solid #666;"> {{$approval_status_name}}  </td>
                                @else
                                    <td style="padding:7px; border:1px solid #666; color:{{Helper::get_approval_status_color_code($candidate->report_id)}}"> {!! Helper::get_approval_status_color_name($candidate->report_id) !!} </td>
                                @endif
                            @endif
                        </tr>
                    </tbody>
                </table>

                <table style="width:100%; border:2px solid #000; border-collapse: collapse; margin-top: 15px; vertical-align:middle">
                    <tbody>
                        <tr>
                            <td style="padding:5px; font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/red.png') }}" />&nbsp; Major Discrepancy </td>
                            <td style="padding:5px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/yellow.png') }}" />&nbsp;Minor Discrepancy</td>
                            <td style="padding:5px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/orange.png') }}" />&nbsp; Inaccessible for verification/Unable to verify/Inputs required</td>
                            <td style="padding:5px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/green.png') }}" />&nbsp; Clear Report</td>
                            {{-- <td style="padding:5px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/green.png') }}" />&nbsp; WIP</td>
                            <td style="padding:5px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/green.png') }}" />&nbsp; No Record Found</td>
                            <td style="padding:5px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/grey-box.png') }}" />&nbsp; Stopped</td> --}}
                        </tr>
                    </tbody>
                </table>
                <table style="width:100%; border:2px solid #000; border-collapse: collapse; margin-top: 15px; text-align: center;">
                    <tbody>
                        <tr>
                            <td style="padding:7px; background: #ccc; font-size: 30px; border: 1px solid #666; text-transform: uppercase;text-align: center;"> Executive Summary</td>
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
                <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center; margin-top: 15px;">
                    <tbody>
                    
                            <tr>
                                <td style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Components</strong></td>
                                <td style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Particulars</strong></td>
                                <td style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Status</strong></td>
                            </tr>
                            
                            @if( count($report_items) > 0 )
                                @php
                                    $check_no = 1;
                                    $r_item_id = NULL;
                                @endphp
                                @foreach($report_items as $r_key => $item)
                                <?php 
                                $approval_status_name   = Helper::get_approval_status_name($item->id); 
                                $input_item_data        = $item->jaf_data;
                                $input_item_data_array  = json_decode($input_item_data, true);
                                $verification_status    = Helper::get_report_verification_status($item->id);
                                $particular             = "";
                                $report_item_master = Helper::get_report_item_approval_status($item->id);
                    
                                $br = "";
                                $m=0;
                                foreach($input_item_data_array as $f_item){
                                    if( array_key_exists('is_executive_summary',$f_item) ){
                                        if($f_item['is_executive_summary'] == '1'){
                                            if($m > 0){
                                                $br = "<br/>";
                                            }
                                            $data       = array_keys($f_item); 
                                            $data1      = array_values($f_item); 
                                            $particular .= $br.$data1[0]." ";

                                            $m++;
                                        }   
                                    }
                                }
                                
                                    if($r_item_id!=NULL)
                                    {
                                        //dd($r_item_id);
                                        $previous = Helper::get_report_item($r_item_id);

                                        if($previous->service_id==$item->service_id)
                                        {
                                            $check_no++;
                                        }
                                        else {
                                            $check_no = 1;
                                        }
                                    }
                                ?>  
                                <tr>
                                    {{-- <td style="padding:7px; border:1px solid #666; text-align:center;"><strong> {{$item->service_name.'-'. Helper::get_check_item_number($item->report_id, $item->id) }}</strong></td> --}}
                                    {{-- <td style="padding:7px; border:1px solid #666; text-align:center;"><strong> {{$item->service_name.'-'. $item->service_item_order }}</strong></td> --}}
                                    <td style="padding:7px; border:1px solid #666; text-align:center;"><strong> {{$item->service_name.' - '. $check_no }}</strong></td>
                                    <td style="padding:7px; border:1px solid #666; text-align:center;"> 
                                        @if(stripos($item->type_name,'drug_test_5')!==false)
                                            5 Panel
                                        @elseif(stripos($item->type_name,'drug_test_6')!==false)
                                            6 Panel
                                        @elseif(stripos($item->type_name,'drug_test_7')!==false)
                                            7 Panel
                                        @elseif(stripos($item->type_name,'drug_test_8')!==false)
                                            8 Panel
                                        @elseif(stripos($item->type_name,'drug_test_9')!==false)
                                            9 Panel
                                        @elseif(stripos($item->type_name,'drug_test_10')!==false)
                                            10 Panel
                                        @else
                                            {!! $particular !!}
                                        @endif
                                    </td>
                                    <td style="padding:7px; border:1px solid #666; text-align:center;"> 
                                        
                                                @if( $item->service_id == 15 || $item->service_id == 16 ) 
                                                    @if($item->approval_status_id == 4)
                                                        No Record Found
                                                    @else
                                                        @if($verification_status && ($verification_status == "5" || $verification_status == "6" || $verification_status == "7"))
                                                            <span>{{ $approval_status_name }}</span>
                                                        @else
                                                            <span style="color: {!! Helper::get_approval_status_color_name($candidate->report_id) !!}" > {!! Helper::get_approval_status_color_name($candidate->report_id) !!}  </span> 
                                                        @endif

                                                        {{-- <span > {{ $approval_status_name }} </span>  --}}
                                                    @endif
                                                    {{-- @endif  --}}
                                                    {{-- @php
                                                        dd($verification_status);
                                                    @endphp --}}
                                                {{-- @elseif(stripos($item->type_name,'drug_test_5')!==false || stripos($item->type_name,'drug_test_6')!==false || stripos($item->type_name,'drug_test_7')!==false || stripos($item->type_name,'drug_test_8')!==false || stripos($item->type_name,'drug_test_9')!==false || stripos($item->type_name,'drug_test_10')!==false)
                                                    @if($report_item_master!=NULL)
                                                        <span style="color: {{$report_item_master->color_code}}">{{ucwords($report_item_master->color_name)}}</span>
                                                    @endif --}}
                                                @elseif($verification_status)
                                                    @if ( $verification_status == "4")

                                                    <span style="color: green;" > GREEN </span> 


                                                    @endif

                                                    @if ( $verification_status == "2")
                                                    
                                                        
                                                    <span style="color:yellow;" > YELLOW </span> 


                                                    @endif
                                                    @if ( $verification_status == "1")
                                                    
                                                    <span style="color: red;" > RED </span> 


                                                    @endif
                                                    @if ( $verification_status == "3")
                                                    
                                                    <span style="color: orange;" > ORANGE </span> 


                                                    @endif

                                                    @if ( $verification_status == "5")
                                                    
                                                    <span > WIP  </span> 
 

                                                    @endif

                                                    @if ( $verification_status == "6")
                                                    
                                                    <span > No Record Found </span> 


                                                    @endif
                                                    @if ( $verification_status == "7")
                                                    <span > Stopped  </span> 


                                                    @endif
                                                    @if ( $verification_status == "8")
                                                    <span >  Additional Information Required   </span> 

                                                    @endif

                                                    @if ( $verification_status == "9")
                                                    <span >   Additional Document Required   </span> 
                                                    
                                                    @endif

                                                    @if ( $verification_status == "10")
                                                    <span >   Amber   </span> 
                                                    
                                                    @endif
                                                @else
                                                    Verification Pending
                                                @endif
                                                    {{-- @if( $item->service_id == 15 || $item->service_id == 16 )  
                                                        @if($item->approval_status_id == 4)
                                                            No Record Found
                                                            @else
                                                            {{ $approval_status_name }} 
                                                        @endif
                                                    
                                                    @elseif ($verification_status=='success')
                                                        {{ $approval_status_name }} 
                                                    @else
                                                        Verification Pending
                                                    @endif --}}
                                                    
                                                    {{-- @endif --}}
                                    </td>
                                </tr>
                                <?php $r_item_id = $item->id;?>
                                @endforeach
                            @endif
                    
                    {{-- @endif --}}
                    
                    </tbody>
                </table>
            </div>
         
        <!-- Check item table started here... -->
        <?php  $i=1;  ?>

            @if( count($report_items) > 0 )
                @php
                    $check_no = 1;
                    $r_item_id = NULL;
                @endphp
                @foreach($report_items as $r_key => $item)
                    <?php 
                            $approval_status_name = Helper::get_approval_status_name($item->id); 
                            $input_item_data = $item->jaf_data;
                            $input_item_data_array =  (array) json_decode($input_item_data, true);   

                            if($r_item_id!=NULL)
                            {
                                //dd($r_item_id);
                                $previous = Helper::get_report_item($r_item_id);

                                if($previous->service_id==$item->service_id)
                                {
                                    $check_no++;
                                }
                                else {
                                    $check_no = 1;
                                }
                            }
                    ?>  
                    <!--  -->
                    <pagebreak />
                    <div class="page-break">
                    <table style="width:100%; border:4px solid #fff; border-collapse: collapse; margin-top: 1px;" autosize="1">
                        <tbody>
                            <tr>
                                <td style="padding:7px; border:1px solid #666;">
                                    {{-- <strong>{{$item->service_name.' '.Helper::get_check_item_number($item->report_id , $item->id)}}
                                Verification</strong> --}}
                                {{-- <strong>{{$item->service_name.' '.$item->service_item_order}}
                                    Verification</strong> --}}
                                    <strong>{{$item->service_name.' - '.$check_no}}
                                        Verification</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center;" autosize="1">
                        <tbody>
                        @if( $item->service_id == 15 )
                            <tr>
                                <td colspan="3" style="padding:7px; border:1px solid #666; background:#ccc; text-align:center"><strong> Criminal Check- (Law Firm)</strong></td>
                            </tr>
                        @elseif(stripos($item->type_name,'drug_test_5')!==false || stripos($item->type_name,'drug_test_6')!==false || stripos($item->type_name,'drug_test_7')!==false || stripos($item->type_name,'drug_test_8')!==false || stripos($item->type_name,'drug_test_9')!==false || stripos($item->type_name,'drug_test_10')!==false)
                            <tr>
                                <td colspan="2" style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Test Components Checked</strong></td>
                                <td style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Verification Remarks</strong></td>
                            </tr>       
                        @else
                            <tr>
                                <td colspan="2" style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Details as per Subjects Application Form</strong></td>
                                <td style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Verification Remarks</strong></td>
                            </tr>
                        @endif
                            <!-- dynamic items -->
                            <!-- check if not judis and criminal -->
                            @if( $item->service_id == 15 )
                            <tr>
                                <td style="padding:7px; border:1px solid #666;"><strong>Criminal Proceedings</strong></td>
                                <td colspan="2" style="padding:7px; border:1px solid #666;">Civil Proceedings: Original Suit, Miscellaneous Suit, Execution, Arbitration Cases & Criminal Proceedings: Criminal Petition, Criminal Appeal, Session Cases, Special Session Cases, Criminal, Miscellaneous Petition, Criminal Revision Appeal</td>
                            </tr>
                            @endif
                            
                            <!-- report table item -->
                            @if(stripos($item->type_name,'drug_test_5')!==false || stripos($item->type_name,'drug_test_6')!==false || stripos($item->type_name,'drug_test_7')!==false || stripos($item->type_name,'drug_test_8')!==false || stripos($item->type_name,'drug_test_9')!==false || stripos($item->type_name,'drug_test_10')!==false)
                                <tr>
                                    <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>  Drug Test </strong></td>
                                    <td style="padding:7px; border:1px solid #666; text-align:center;">
                                        @php
                                            $drug_test_name = Helper::drugTestName($item->service_id);
                                        @endphp
                                        @if(count($drug_test_name)>0)
                                            @foreach ($drug_test_name as $d_item)
                                                <p>{{$d_item->test_name}}</p>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td style="padding:7px; border:1px solid #666; text-align:center">
                                        @foreach($input_item_data_array  as $output_item)
                                            <?php
                                                $key_data = array_keys($output_item);
                                                $val_data = array_values($output_item);
                                            ?>
                                            @if(stripos($key_data[0],'Result')!==false)
                                                {{$val_data[0]!=NULL && $val_data[0]!='' ? ucwords($val_data[0]) : '--' }}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Test Date</strong></td>
                                    <td colspan="2" style="padding:7px; border:1px solid #666;">{{ $item->test_date!=NULL ? date('d F Y',strtotime($item->test_date)) : NULL }}</td>
                                </tr>
                            @else
                                @foreach($input_item_data_array  as $output_item)
                                    @if($output_item['is_report_output']=='1') 
                                        <tr>
                                            <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>  <?php $key_data = array_keys($output_item); ?> {{ $key_data[0] }} </strong></td>
                                            <td style="padding:7px; border:1px solid #666; text-align:center;">
                                            <?php $val_data = array_values($output_item); ?> {{ $val_data[0] }}
                                            </td>
                                            <td style="padding:7px; border:1px solid #666; text-align:center">
                                            <?php   //print_r($output_item); 
                                                    $remarks = "-";
                                                    
                                                    if( array_key_exists('remarks', $output_item)){
                                                        if($output_item['remarks'] !="-"){
                                                            $remarks = "Yes";
                                                        }  
                                                    }
                                                    if( array_key_exists('remarks_message', $output_item)){
                                                        if($output_item['remarks_message']!=""){
                                                            $remarks = $output_item['remarks_message'];
                                                            if ($remarks=='clear') {

                                                                $remarks = 'Verified Clear';
                                                            }
                                                            if ($remarks=='no_record') {

                                                                $remarks = 'No Record Found';
                                                            }
                                                            if ($remarks=='unable_verify') {

                                                                $remarks = 'Unable To Verify';
                                                            }
                                                            if ($remarks=='stop') {

                                                                $remarks = 'Stop';
                                                            }
                                                            if ($remarks=='custom') {
                                                                if (array_key_exists('remarks_custom_message', $output_item)) {
                                                                    if ($output_item['remarks_custom_message']!="") {
                                                                        $remarks = $output_item['remarks_custom_message'];
                                                                    }
                                                                
                                                                }
                                                            }
                                                        }
                                                    }    
                                            ?>
                                            {{ $remarks }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                            
                            <!-- dynamic items end -->

                            <tr>
                                <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Verified By</strong></td>
                                <td colspan="2" style="padding:7px; border:1px solid #666;">{{ Helper::get_report_verified_by($item->id) }}</td>
                            </tr>
                            @if( $item->service_id == 15 )
                            
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
                            {{-- <tr>
                                <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Comments</strong></td>
                                <td colspan="2" style="padding:7px; border:1px solid #666;"> <p style="background:yellow;">{{ Helper::get_report_comments($item->id).' '.$item->annexure_value.'.' }} </p></td>
                            </tr> --}}
                            <tr>
                                <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Additional Comments</strong></td>
                                <td colspan="2" style="padding:7px; border:1px solid #666;">{{ Helper::get_report_additional_comments($item->id) }}</td> 
                            </tr>
                        </tbody>
                    </table>

                    @if($item->service_id == 17)
                        @if($item->reference_form_data!=NULL && $item->reference_type!=NULL)
                            <pagebreak />
                            <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center;" autosize="1">
                                <tbody>
                                    <tr>
                                        <td colspan="2" style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Checks</strong></td>
                                        <td style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Status</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding:7px; border:1px solid #666;text-align:center;"><strong>Reference Check</strong></td>
                                        <td style="padding:7px; border:1px solid #666;background:#CAFF33;text-align:center;"><strong>Positive Feedback</strong></td>
                                    </tr> 
                                </tbody>
                            </table>
                            <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center; margin-top:5%;" autosize="1">
                                <tbody>
                                    <tr>
                                        <td colspan="3" style="padding:5px; border:1px solid #666; background:#ccc; text-align:center;"><strong> {{ ucwords($item->reference_type) }} Reference Check</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="padding:5px; border:1px solid #666;text-align:center;"><strong>Status</strong></td>
                                        <td colspan="2" style="padding:5px; border:1px solid #666;background:#CAFF33;text-align:center;"><strong>Positive Feedback</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="padding:5px; border:1px solid #666; background:#ccc; text-align:center;"><strong> Verified Data</strong></td>
                                        <td style="padding:5px; border:1px solid #666; background:#ccc; text-align:center;"><strong> Details Provided by the Subject </strong></td>
                                        <td style="padding:5px; border:1px solid #666; background:#ccc; text-align:center;"><strong> Verification Remarks </strong></td>
                                    </tr>
                                    @if(stripos($item->reference_type,'professional')!==false)
                                        <?php 
                                            $input_data=$item->jaf_data;
                                            $reference_input_data = $item->reference_form_data;
                                            $input_data_array = json_decode($input_data,true);
                                            $reference_input_data_array = json_decode($reference_input_data,true);
                                        ?>
                                        @foreach ($input_data_array as $key => $input)
                                            <?php 
                                                $key_val = array_keys($input); $input_val = array_values($input);

                                                $remarks = "-";

                                                if( array_key_exists('remarks', $input)){
                                                    if($input['remarks'] !="-"){
                                                        $remarks = "Yes";
                                                    }  
                                                }
                                            ?>

                                            @if(!(stripos($key_val[0],'First Name')!==false || stripos($key_val[0],'Last Name')!==false || stripos($key_val[0],'Reference Type (Personal / Professional)')!==false || stripos($key_val[0],'Referee Email id')!==false))
                                                <tr>
                                                    <td style="padding:5px; border:1px solid #666; text-align:center;"><strong> {{ $key_val[0] }}</strong></td>
                                                    <td style="padding:5px; border:1px solid #666; text-align:center;"> {{ $input_val[0] }} </td>
                                                    <td style="padding:5px; border:1px solid #666; text-align:center;"> {{ $remarks }} </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        <tr>
                                            <td colspan="2" style="padding:5px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Verified Data</strong></td>
                                            <td style="padding:5px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Referee Comments</strong></td>
                                        </tr>
                                        @foreach ($reference_input_data_array as $key => $input)
                                            <?php 
                                                $key_val = array_keys($input); $input_val = array_values($input);
                                            ?>
                                            @if(stripos($key_val[0],'Mode of Verification')!==false || stripos($key_val[0],'Remarks')!==false)
                                                <tr>
                                                    <td style="padding:5px; border:1px solid #666;text-align:center;"><strong>{{ $key_val[0] }}</strong></td>
                                                    <td colspan="2" style="padding:5px; border:1px solid #666;text-align:center;">{{ $input_val[0] }}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="2" style="padding:5px; border:1px solid #666;text-align:center;"><strong>{{ $key_val[0] }}</strong></td>
                                                    <td style="padding:5px; border:1px solid #666;text-align:center;">{{ $input_val[0] }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @elseif (stripos($item->reference_type,'personal')!==false)
                                        <?php 
                                            $input_data=$item->jaf_data;
                                            $reference_input_data = $item->reference_form_data;
                                            $input_data_array = json_decode($input_data,true);
                                            $reference_input_data_array = json_decode($reference_input_data,true);
                                        ?>

                                        @foreach ($input_data_array as $key => $input)
                                            <?php 
                                                $key_val = array_keys($input); $input_val = array_values($input);

                                                $remarks = "-";

                                                if( array_key_exists('remarks', $input)){
                                                    if($input['remarks'] !="-"){
                                                        $remarks = "Yes";
                                                    }  
                                                }
                                            ?>
                                            @if(stripos($key_val[0],'Referee Name')!==false)
                                                <tr>
                                                    <td style="padding:5px; border:1px solid #666; text-align:center;"><strong> {{ $key_val[0] }}</strong></td>
                                                    <td style="padding:5px; border:1px solid #666; text-align:center;"> {{ $input_val[0] }} </td>
                                                    <td style="padding:5px; border:1px solid #666; text-align:center;"> {{ $remarks }} </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($reference_input_data_array as $key => $input)
                                            <?php 
                                                $key_val = array_keys($input); $input_val = array_values($input);
                                            ?>
                                            @if(stripos($key_val[0],'Referee Relation')!==false)
                                                <tr>
                                                    <td style="padding:5px; border:1px solid #666; text-align:center;"><strong> {{ $key_val[0] }}</strong></td>
                                                    <td style="padding:5px; border:1px solid #666; text-align:center;"> {{ $input_val[0] }} </td>
                                                    <td style="padding:5px; border:1px solid #666; text-align:center;">Yes </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($input_data_array as $key => $input)
                                            <?php 
                                                $key_val = array_keys($input); $input_val = array_values($input);

                                                $remarks = "-";

                                                if( array_key_exists('remarks', $input)){
                                                    if($input['remarks'] !="-"){
                                                        $remarks = "Yes";
                                                    }  
                                                }
                                            ?>
                                            @if(stripos($key_val[0],'Referee Contact Number')!==false)
                                                <tr>
                                                    <td style="padding:5px; border:1px solid #666; text-align:center;"><strong> {{ $key_val[0] }}</strong></td>
                                                    <td style="padding:5px; border:1px solid #666; text-align:center;"> {{ $input_val[0] }} </td>
                                                    <td style="padding:5px; border:1px solid #666; text-align:center;"> {{ $remarks }} </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        
                                        <tr>
                                            <td colspan="2" style="padding:5px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Verified Data</strong></td>
                                            <td style="padding:5px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Referee Comments</strong></td>
                                        </tr>

                                        @foreach ($reference_input_data_array as $key => $input)
                                            <?php 
                                                $key_val = array_keys($input); $input_val = array_values($input);
                                            ?>
                                            @if(stripos($key_val[0],'Mode of Verification')!==false || stripos($key_val[0],'Remarks')!==false)
                                                <tr>
                                                    <td style="padding:5px; border:1px solid #666;text-align:center;"><strong>{{ $key_val[0] }}</strong></td>
                                                    <td colspan="2" style="padding:5px; border:1px solid #666;text-align:center;">{{ $input_val[0] }}</td>
                                                </tr>
                                            @else
                                                @if(!(stripos($key_val[0],'Referee Relation')!==false))
                                                    <tr>
                                                        <td colspan="2" style="padding:5px; border:1px solid #666;text-align:center;"><strong>{{ $key_val[0] }}</strong></td>
                                                        <td style="padding:5px; border:1px solid #666;text-align:center;">{{ $input_val[0] }}</td>
                                                    </tr>
                                                @endif
                                            @endif
                                        @endforeach

                                    @endif 
                                </tbody>
                            </table>
                        @endif
                    @endif

                    <!-- ./ check ite close here  -->
                    
                     <!-- additional Address -->
                     {{-- @if($item->service_id == 1) --}}
                        {{-- @if ($report_add_page)
                            @if ($report_add_page->status == 'enable')
                            <pagebreak />
                             @php
                                $additional_data =  Helper::get_additional_address_data($candidate->id,$item->id);
                             @endphp    
                     
                             <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center; margin-top:15px;" autosize="1">
                                 <tbody>
                                     <tr>
                                         <td  style="padding:7px; border:1px solid #666; background:#ccc; text-align: center;  width: 50%; "><strong>Details</strong></td>
                                         <td style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;  width: 50%;"><strong>Findings</strong></td>
                                     </tr>
                                     <tr>
                                         <td  style="padding:7px; border:1px solid #666; background:#ccc; "><strong>Name of the Person Contacted and
                                             Contact Number</strong></td>
                                         <td style="padding:7px; border:1px solid #666; text-align:center;"> @if ($additional_data) {{($additional_data->contact_person_name || $additional_data->contact_contact_no)  ? $additional_data->contact_person_name .','.' '. $additional_data->contact_contact_no :'N/A'}}@endif</td>
                                     </tr>
                                     <tr>
                                         <td  style="padding:7px; border:1px solid #666; background:#ccc; "><strong>Relationship with the associate</strong></td>
                                         <td style="padding:7px; border:1px solid #666; text-align:center;">@if ($additional_data){{ $additional_data->relation_with_associate ? $additional_data->relation_with_associate : 'N/A' }} @endif</td>
                                     </tr>
                                     <tr>
                                         <td  style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Residence Status</strong></td>
                                         <td style="padding:7px; border:1px solid #666; text-align:center;">@if ($additional_data){{$additional_data->residence_status ? $additional_data->residence_status:'N/A' }}@endif</td>
                                     </tr>
                                     <tr>
                                         <td  style="padding:7px; border:1px solid #666; background:#ccc; "><strong>Locality</strong></td>
                                         <td style="padding:7px; border:1px solid #666; text-align:center;">@if ($additional_data){{ $additional_data->locality ? $additional_data->locality : 'N/A' }} @endif</td>
                                     </tr>
                                     <tr>
                                         <td  style="padding:7px; border:1px solid #666; background:#ccc; "><strong>Additional Comments <small>(If any)</small> </strong></td>
                                         <td style="padding:7px; border:1px solid #666; text-align:center;">@if ($additional_data){{ $additional_data->comments ? $additional_data->comments : 'N/A' }} @endif</td>
                                     </tr>
                                     <tr>
                                         <td  style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Verified By</strong></td>
                                         <td style="padding:7px; border:1px solid #666; text-align:center;">@if ($additional_data){{ $additional_data->verified_by ? $additional_data->verified_by : 'N/A' }} @endif</strong></td>
                                     </tr>
                                     <tr>
                                         <td  style="padding:7px; border:1px solid #666; background:#ccc; "><strong>Mode of the Verification</strong></td>
                                         <td style="padding:7px; border:1px solid #666; text-align:center;">@if ($additional_data){{  $additional_data->mode_of_verification ? $additional_data->mode_of_verification :'N/A' }}@endif</td>
                                     </tr>
                                     <tr>
                                         <td  style="padding:7px; border:1px solid #666; background:#ccc; "><strong>Remarks </strong></td>
                                         <td style="padding:7px; border:1px solid #666; text-align:center;">@if ($additional_data){{ $additional_data->remarks ? $additional_data->remarks: 'N/A' }} @endif</td>
                                     </tr>
                                 </tbody>
                             </table>   
                            @endif
                        @endif  --}}
                    {{-- @endif   --}}
                    <!-- check if not judis -->
                    @if($item->service_id != 16)
                        <!-- imaages  -->
                        <?php $y =1; $num = 1; $count = ""; $item_files = Helper::getReportAttachFiles($item->id,'main'); //print_r($item_files); ?>
                        @if(count($item_files) > 0)
                    
                            <pagebreak />

                            @foreach($item_files as $file)
                                
                                @if($file['attachment_type'] == 'main')
                                    
                                        @if($num == 1)
                                            <span style=" padding:0px; background-color:yellow"><u>Verification Received</u></span>
                                        @elseif($num > 1)
                                        <pagebreak />
                                        <span style="padding:0px; margin:10px 0; background-color:yellow">Cont...</span>
                                        @endif
                                        @if(stripos($file['file_name'],'pdf')!==false)
                                            {!!Helper::reportPdfToImage($item->id,$file['file_name'],$user_id)!!}
                                            @if($item->service_id==1)
                                                <pagebreak />
                                            @endif
                                        @else
                                            <div style="border:solid 1px #333; text-align:center; margin-top:15px;">
                                                <img src="{{ $file['fileIcon'] }}" style='width:100%; height: 100%; margin:10px 0;'>
                                            </div>
                                        @endif
                                

                                @endif
                                <!-- main file -->
                                <?php $y++; $num++; $count++;?>
                            @endforeach
                        @endif
                                <?php  $snum=1; $item_file = Helper::getReportAttachFiles($item->id,'supporting'); ?>
                        @if(count($item_file) > 0)
                                
                            @foreach($item_file as $files)
                                <!-- supportings -->
                                @if($files['attachment_type'] == 'supporting')
                                       
                                    @if($snum == 1)
                                        <pagebreak />
                                        <span style=" padding:0px; background-color:yellow"><u> Documents Provided by the applicant</u> </span>
                                    @elseif($snum > 1)
                                        
                                        <pagebreak />
                                        <span style="padding:0px; margin:10px 0; background-color:yellow">Cont...</span>
                                    @endif
                                    @if(stripos($files['file_name'],'pdf')!==false)
                                        {!!Helper::reportPdfToImage($item->id,$files['file_name'],$user_id)!!}
                                        @if($item->service_id==1)
                                            <pagebreak />
                                        @endif
                                    @else
                                        <div style="border:solid 1px #333; text-align:center; margin-top:15px;">
                                            <img src="{{ $files['fileIcon'] }}" style='width:100%; height: 100%; margin:10px 0; ' >
                                        </div>
                                    @endif
                                    <?php  $snum++;?>
                                        
                                @endif
                                <!-- supporting -->
                              

                            @endforeach
                        @endif
                        <!-- images -->
                    @endif
                    <!-- if not judis -->
                    
                    <!-- if judis -->
                    @if($item->service_id == 16)
                        <!-- imaages  -->
                        <?php $y =1; $count = 1; $num = 1; $count = ""; $item_files = Helper::getReportAttachFiles($item->id,'main'); //print_r($item_files); ?>
                        @if(count($item_files) > 0)
                            <pagebreak />
                            @foreach($item_files as $file)
                                
                                @if($file['attachment_type'] == 'main')
                                
                                    @if($num == 1)
                                        <span style=" padding:0px; background-color:yellow"><u>Verification Received</u></span>
                                    @elseif($num > 1)
                                        @if( $num % 2 == 0)
                                            <pagebreak />
                                            <span style="padding:0px; margin:10px 0; background-color:yellow">Cont...</span>
                                            @endif
                                    @endif
                                    @if(stripos($file['file_name'],'pdf')!==false)
                                        {!!Helper::reportPdfToImage($item->id,$file['file_name'],$user_id)!!}
                                        @if($item->service_id==1)
                                            <pagebreak />
                                        @endif
                                    @else
                                        <div style="border:solid 1px #333; text-align:center; margin-top:15px;">
                                            <img src="{{ $file['fileIcon'] }}" style='width:100%; height: 100%; margin:10px 0; ' >
                                        </div>
                                    @endif

                                    {{-- <div style="border:solid 1px #333; text-align:center; margin-top:15px;">
                                        <img src="{{ $file['filePath'] }}" style="width: 160mm; height: 85mm; margin: 0;" /> 
                                    </div>   --}}
                                
                                @endif
                                <!-- main file -->
                                <?php $y++; $num++; $count++;?>
                            @endforeach
                        @endif
                            <?php  $snum=1; $item_file = Helper::getReportAttachFiles($item->id,'supporting'); ?>
                        @if(count($item_file) > 0)
                            <pagebreak />
                            @foreach($item_file as $files)
                                <!-- supportings -->
                                @if($files['attachment_type'] == 'supporting')
                                
                                        @if($snum == 1)
                                            <span style=" padding:0px; background-color:yellow"><u> Documents Provided by the applicant</u> </span>
                                        @elseif($snum > 1)
                                            @if( $snum % 2 == 0)
                                                <pagebreak />
                                                <span style="padding:0px; margin:10px 0; background-color:yellow">Cont...</span>
                                            @endif
                                        @else  
                                        
                                        @endif
                                        @if(stripos($files['file_name'],'pdf')!==false)
                                            {!!Helper::reportPdfToImage($item->id,$files['file_name'],$user_id)!!}
                                            @if($item->service_id==1)
                                                <pagebreak />
                                            @endif
                                        @else
                                            <div style="border:solid 1px #333; text-align:center; margin-top:15px;">
                                                <img src="{{ $files['fileIcon'] }}" style='width:100%; height: 100%; margin:10px 0; ' >
                                            </div>
                                        @endif
                                        {{-- <div style="border:solid 1px #333; text-align:center; margin-top:15px;">
                                            <img src="{{ $file['filePath'] }}" style="width: 160mm; height: 85mm; margin: 0;" /> 
                                        </div>   --}}  
                                        <?php  $snum++;?>
                                @endif
                                <!-- supporting -->
                                

                            @endforeach
                        @endif
                        <!-- images -->
                    @endif
                    <!-- ./ if judis -->
                    
                        </div>
                    <?php  $x++;  ?>
                    <!-- row close -->
                    <?php $r_item_id = $item->id;?>
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