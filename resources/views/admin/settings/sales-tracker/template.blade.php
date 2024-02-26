<style>
    .table-bordered thead th, .table-bordered thead td {
        border-bottom-width: 2px !important;
    }
    table.table.table-bordered > thead  > tr >th {
        /* max-width: 200px; */
        min-width: 200px;
    }
</style>
<div class="table-responsive" style="max-height: 400px;"> 
    <table class="table table-bordered" style="z-index: 1;">
        <thead>
            <tr>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000' colspan="8">Client Details</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#A8E3F4;border:1px solid #000' colspan="5">Old History</th>
                @if(stripos($type,'weekly')!==false)
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#FFFF;border:1px solid #000' colspan="7">Week-1 / Number of cases that we received from Client</th>
                @elseif(stripos($type,'monthly')!==false)
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#FFFF;border:1px solid #000' colspan="7">Week-1 / Number of cases that we received from Client</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#FFFF;border:1px solid #000' colspan="7">Week-2</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#FFFF;border:1px solid #000' colspan="7">Week-3</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#FFFF;border:1px solid #000' colspan="7">Week-4</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#FFFF;border:1px solid #000' colspan="3">Week-5</th>
                @elseif (stripos($type,'quaterly')!==false)
                    @for($i=0;$i<3;$i++)
                        @php
                            $month = date('F',strtotime($start_date.' + '.$i.' month'));
                        @endphp
                        @if($i==0)
                            <th class="text-center" scope="col" style='position: sticky;top:0px;background:#FFFF;border:1px solid #000' colspan="5">{{$month}} / Number of cases that we received from Client</th>
                        @else                       
                            <th class="text-center" scope="col" style='position: sticky;top:0px;background:#FFFF;border:1px solid #000' colspan="5">{{$month}}</th>
                        @endif
                    @endfor
                @elseif (stripos($type,'yearly')!==false)
                    @for($i=0;$i<12;$i++)
                        @php
                            $month = date('F',strtotime($start_date.' + '.$i.' month'));
                        @endphp
                        @if($i==0)
                            <th class="text-center" scope="col" style='position: sticky;top:0px;background:#FFFF;border:1px solid #000' colspan="5">{{$month}} / Number of cases that we received from Client</th>
                        @else                       
                            <th class="text-center" scope="col" style='position: sticky;top:0px;background:#FFFF;border:1px solid #000' colspan="5">{{$month}}</th>
                        @endif
                    @endfor
                @endif
            </tr>
            <tr>
              <th class="text-center"  scope="col" style='position: sticky;top:0px;background:#A8E3F4;border:1px solid #000'>Client Name</th>
              <th class="text-center" scope="col" style='position: sticky;top:0px;background:#A8E3F4;border:1px solid #000'>Agreement Expiry Date</th>
              <th class="text-center" scope="col" style='position: sticky;top:0px;background:#A8E3F4;border:1px solid #000;min-width: 525px;'>Existing Customer category A=Rev more than 1cr B=Rev 50lak to 1cr C < 50lak</th>
              <th class="text-center" scope="col" style='position: sticky;top:0px;background:#A8E3F4;border:1px solid #000'>Avg No of cases weekly</th>
              <th class="text-center" scope="col" style='position: sticky;top:0px;background:#A8E3F4;border:1px solid #000'>Avg No of cases monthly</th>
              <th class="text-center" scope="col" style='position: sticky;top:0px;background:#A8E3F4;border:1px solid #000'>Avg Rs Per Case</th>
              <th class="text-center" scope="col" style='position: sticky;top:0px;background:#A8E3F4;border:1px solid #000'>Contact Details</th>
              <th class="text-center" scope="col" style='position: sticky;top:0px;background:#A8E3F4;border:1px solid #000'>Contact Person</th>
              <th class="text-center" scope="col" style='position: sticky;top:0px;background:#FFFF;border:1px solid #000'>Avg Order Size</th>
              <th class="text-center" scope="col" style='position: sticky;top:0px;background:#FFFF;border:1px solid #000'>Order Frequency</th>
              <th class="text-center" scope="col" style='position: sticky;top:0px;background:#FFFF;border:1px solid #000'>Usual Order Date</th>
              <th class="text-center" scope="col" style='position: sticky;top:0px;background:#FFFF;border:1px solid #000'>Date for Calling</th>
              <th class="text-center" scope="col" style='position: sticky;top:0px;background:#FFFF;border:1px solid #000'>Frequency of Calling</th>
              @if(stripos($type,'weekly')!==false)
                @for($i=0;$i<7; $i++)
                    @php
                        $day=date('d',strtotime($start_date.'+'.$i.'days'));
                    @endphp
                    <th class="text-center" scope="col" style='position: sticky;top:0px;border:1px solid #000'>{{$day}}</th>
                @endfor
              @elseif (stripos($type,'monthly')!==false)
                @php
                    $last_day = date('t',strtotime($start_date)); 
                    $l = 31;
                    $day_diff = $l - $last_day;
                @endphp
                @for ($i=0;$i<$last_day; $i++)
                    @php
                        $day=date('d',strtotime($start_date.'+'.$i.'days'));
                    @endphp
                    <th class="text-center" scope="col" style='position: sticky;top:0px;border:1px solid #000'>{{$day}}</th>
                @endfor
                @if($day_diff > 0)
                    @for ($i=0;$i<$day_diff; $i++)
                        <th class="text-center" scope="col" style='position: sticky;top:0px;border:1px solid #000'>&nbsp;</th>
                    @endfor
                @endif
              @elseif (stripos($type,'quaterly')!==false)
                @for($i=0;$i<3;$i++)
                    @php
                        $k=0;
                    @endphp
                    @for($j=0;$j<5;$j++)
                        @php
                            $k= $j + 1;
                        @endphp
                        <th class="text-center" scope="col" style='position: sticky;top:0px;border:1px solid #000;'>{{'Week - '.$k}}</th>
                    @endfor
                @endfor
              @elseif (stripos($type,'yearly')!==false)
                @for($i=0;$i<12;$i++)
                    @php
                        $k=0;
                    @endphp
                    @for($j=0;$j<5;$j++)
                        @php
                            $k= $j + 1;
                        @endphp
                        <th class="text-center" scope="col" style='position: sticky;top:0px;border:1px solid #000'>{{'Week - '.$k}}</th>
                    @endfor
                @endfor
              @endif
            </tr>
        </thead> 
        <tbody id="dummy_data" class="excel-table-data">
          @if(count($imported_data)>0)
            @foreach ($imported_data as $value)
                @if($value[0]=='' || $value[0]==null)
                    <?php continue; ?>
                @endif
                <tr>
                    <td>{{$value[0]}}</td>
                    <td>{{$value[1]}}</td>
                    <td>{{$value[2]}}</td>
                    <td>{{$value[3]}}</td>
                    <td>{{$value[4]}}</td>
                    <td>{{$value[5]}}</td>
                    <td>{{$value[6]}}</td>
                    <td>{{$value[7]}}</td>
                    <td>{{$value[8]}}</td>
                    <td>{{$value[9]}}</td>
                    <td>{{$value[10]}}</td>
                    <td>{{$value[11]}}</td>
                    <td>{{$value[12]}}</td>
                    @if(stripos($type,'weekly')!==false)
                        <td>{{$value[13]}}</td>
                        <td>{{$value[14]}}</td>
                        <td>{{$value[15]}}</td>
                        <td>{{$value[16]}}</td>
                        <td>{{$value[17]}}</td>
                        <td>{{$value[18]}}</td>
                        <td>{{$value[19]}}</td>
                    @elseif(stripos($type,'monthly')!==false)
                        <td>{{$value[13]}}</td>
                        <td>{{$value[14]}}</td>
                        <td>{{$value[15]}}</td>
                        <td>{{$value[16]}}</td>
                        <td>{{$value[17]}}</td>
                        <td>{{$value[18]}}</td>
                        <td>{{$value[19]}}</td>
                        <td>{{$value[20]}}</td>
                        <td>{{$value[21]}}</td>
                        <td>{{$value[22]}}</td>
                        <td>{{$value[23]}}</td>
                        <td>{{$value[24]}}</td>
                        <td>{{$value[25]}}</td>
                        <td>{{$value[26]}}</td>
                        <td>{{$value[27]}}</td>
                        <td>{{$value[28]}}</td>
                        <td>{{$value[29]}}</td>
                        <td>{{$value[30]}}</td>
                        <td>{{$value[31]}}</td>
                        <td>{{$value[32]}}</td>
                        <td>{{$value[33]}}</td>
                        <td>{{$value[34]}}</td>
                        <td>{{$value[35]}}</td>
                        <td>{{$value[36]}}</td>
                        <td>{{$value[37]}}</td>
                        <td>{{$value[38]}}</td>
                        <td>{{$value[39]}}</td>
                        <td>{{$value[40]}}</td>
                        <td>{{$value[41]}}</td>
                        <td>{{$value[42]}}</td>
                        <td>{{$value[43]}}</td>
                    @elseif (stripos($type,'quaterly')!==false)
                        <td>{{$value[13]}}</td>
                        <td>{{$value[14]}}</td>
                        <td>{{$value[15]}}</td>
                        <td>{{$value[16]}}</td>
                        <td>{{$value[17]}}</td>
                        <td>{{$value[18]}}</td>
                        <td>{{$value[19]}}</td>
                        <td>{{$value[20]}}</td>
                        <td>{{$value[21]}}</td>
                        <td>{{$value[22]}}</td>
                        <td>{{$value[23]}}</td>
                        <td>{{$value[24]}}</td>
                        <td>{{$value[25]}}</td>
                        <td>{{$value[26]}}</td>
                        <td>{{$value[27]}}</td>
                    @elseif (stripos($type,'yearly')!==false)
                        <td>{{$value[13]}}</td>
                        <td>{{$value[14]}}</td>
                        <td>{{$value[15]}}</td>
                        <td>{{$value[16]}}</td>
                        <td>{{$value[17]}}</td>
                        <td>{{$value[18]}}</td>
                        <td>{{$value[19]}}</td>
                        <td>{{$value[20]}}</td>
                        <td>{{$value[21]}}</td>
                        <td>{{$value[22]}}</td>
                        <td>{{$value[23]}}</td>
                        <td>{{$value[24]}}</td>
                        <td>{{$value[25]}}</td>
                        <td>{{$value[26]}}</td>
                        <td>{{$value[27]}}</td>
                        <td>{{$value[28]}}</td>
                        <td>{{$value[29]}}</td>
                        <td>{{$value[30]}}</td>
                        <td>{{$value[31]}}</td>
                        <td>{{$value[32]}}</td>
                        <td>{{$value[33]}}</td>
                        <td>{{$value[34]}}</td>
                        <td>{{$value[35]}}</td>
                        <td>{{$value[36]}}</td>
                        <td>{{$value[37]}}</td>
                        <td>{{$value[38]}}</td>
                        <td>{{$value[39]}}</td>
                        <td>{{$value[40]}}</td>
                        <td>{{$value[41]}}</td>
                        <td>{{$value[42]}}</td>
                        <td>{{$value[43]}}</td>
                        <td>{{$value[44]}}</td>
                        <td>{{$value[45]}}</td>
                        <td>{{$value[46]}}</td>
                        <td>{{$value[47]}}</td>
                        <td>{{$value[48]}}</td>
                        <td>{{$value[49]}}</td>
                        <td>{{$value[50]}}</td>
                        <td>{{$value[51]}}</td>
                        <td>{{$value[52]}}</td>
                        <td>{{$value[53]}}</td>
                        <td>{{$value[54]}}</td>
                        <td>{{$value[55]}}</td>
                        <td>{{$value[56]}}</td>
                        <td>{{$value[57]}}</td>
                        <td>{{$value[58]}}</td>
                        <td>{{$value[59]}}</td>
                        <td>{{$value[60]}}</td>
                        <td>{{$value[61]}}</td>
                        <td>{{$value[62]}}</td>
                        <td>{{$value[63]}}</td>
                        <td>{{$value[64]}}</td>
                        <td>{{$value[65]}}</td>
                        <td>{{$value[66]}}</td>
                        <td>{{$value[67]}}</td>
                        <td>{{$value[68]}}</td>
                        <td>{{$value[69]}}</td>
                        <td>{{$value[70]}}</td>
                        <td>{{$value[71]}}</td>
                        <td>{{$value[72]}}</td>
                    @endif
                </tr>
            @endforeach
          @endif
        </tbody>
    </table>
</div>