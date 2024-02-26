<style>
.table-bordered thead th, .table-bordered thead td {
    border-bottom-width: 2px !important;
}
table.table.table-bordered > thead  > tr >th {
    /* max-width: 200px; */
    min-width: 100px;
}
</style>
<div class="table-responsive" style="max-height: 500px;"> 
    <table class="table table-bordered" style="z-index: 1;">
        <thead>
            @php
                $s_count = count($services);
            @endphp
            <tr>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#A8E3F4;border:1px solid #000' rowspan="3">Client Name</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#F7C50B;border:1px solid #000' colspan="13">Case Received</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#ca9fe9;border:1px solid #000' colspan="25">Case Closed</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#58ACD6;border:1px solid #000' colspan="3">WIP</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#F7C50B;border:1px solid #000' colspan="4">Insufficiency</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#F59A5F;border:1px solid #000' colspan="4">Pending Cases</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#F59A5F;border:1px solid #000' colspan="{{($s_count * 2)+2}}">Pending Checks</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#ca9fe9;border:1px solid #000' colspan="{{($s_count * 2)+2}}">Insuff Checks</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#58ACD6;border:1px solid #000' colspan="6">WIP Bucket</th>
            </tr>
            <tr>
                {{-- Case Received --}}
                @for ($i=1;$i<=12;$i++)
                    @php 
                        $m= date('M y',strtotime($start_date.'+'.$i.'month')); 
                    @endphp
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#58ACD6;border:1px solid #000' rowspan="2">{{$m}}</th>
                @endfor
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#58ACD6;border:1px solid #000' rowspan="2">Grand Total</th>

                {{-- Case Closed --}}
                @for ($i=1;$i<=12;$i++)
                    @php 
                        $m= date('M y',strtotime($start_date.'+'.$i.'month')); 
                    @endphp
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#78D658;border:1px solid #000' colspan="2">{{$m}}</th>
                @endfor
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#78D658;border:1px solid #000' rowspan="2">Grand Total</th>

                {{-- WIP --}}
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#A8E3F4;border:1px solid #000' rowspan="2">Pending</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#A8E3F4;border:1px solid #000' rowspan="2">Insuff</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#A8E3F4;border:1px solid #000' rowspan="2">Stop Check</th>

                {{-- Insufficiency --}}
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#E2FD7C;border:1px solid #000; min-width:150px;' rowspan="2">2nd Previous Month</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#E2FD7C;border:1px solid #000; min-width:150px;' rowspan="2">Previous Month</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#E2FD7C;border:1px solid #000; min-width:150px;' rowspan="2">Current Month</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#E2FD7C;border:1px solid #000; min-width:150px;' rowspan="2">Total Insuff</th>

                {{-- Pending Cases --}}
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#ca9fe9;border:1px solid #000; min-width:150px;' rowspan="2">2nd Previous Month</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#ca9fe9;border:1px solid #000; min-width:150px;' rowspan="2">Previous Month</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#ca9fe9;border:1px solid #000; min-width:150px;' rowspan="2">Current Month</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#ca9fe9;border:1px solid #000; min-width:150px;' rowspan="2">Total Insuff</th>

                {{-- Pending Check --}}
                @if(count($services)>0)
                    @foreach($services as $service)
                        <th class="text-center" scope="col" style='position: sticky;top:0px;background:#f3b389;border:1px solid #000' colspan="2">{{$service->name}}</th>
                    @endforeach
                @endif
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#78D658;border:1px solid #000;min-width:150px;' rowspan="2">In Checks Total</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#78D658;border:1px solid #000;color:red;min-width:150px;' rowspan="2">Out Checks Total</th>

                {{-- Insuff Check --}}
                @if(count($services)>0)
                    @foreach($services as $service)
                        <th class="text-center" scope="col" style='position: sticky;top:0px;background:#58ACD6;border:1px solid #000' colspan="2">{{$service->name}}</th>
                    @endforeach
                @endif
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#ca9fe9;border:1px solid #000;min-width:150px;' rowspan="2">In Checks Total</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#ca9fe9;border:1px solid #000;color:red;min-width:150px;' rowspan="2">Out Checks Total</th>

                {{-- WIP Bucket --}}
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#A8E3F4;border:1px solid #000' rowspan="2">0-5 days</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#A8E3F4;border:1px solid #000' rowspan="2">6-10 days</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#A8E3F4;border:1px solid #000' rowspan="2">11-15 days</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#A8E3F4;border:1px solid #000' rowspan="2">15-30 days</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#A8E3F4;border:1px solid #000;min-width:125px;' rowspan="2">60 days & above</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#A8E3F4;border:1px solid #000' rowspan="2">Grand Total</th>

            </tr>
            <tr>
                {{-- Case Closed --}}
                @for ($i=1;$i<=12;$i++)
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#78D658;border:1px solid #000'>IN</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#78D658;border:1px solid #000'>OUT</th> 
                @endfor

                {{-- Pending Check --}}
                @if(count($services)>0)
                    @foreach($services as $service)
                        <th class="text-center" scope="col" style='position: sticky;top:0px;background:#f3b389;border:1px solid #000'>IN</th>
                        <th class="text-center" scope="col" style='position: sticky;top:0px;background:#78D658;border:1px solid #000'>OUT</th> 
                    @endforeach
                @endif

                {{-- Insuff Check --}}
                @if(count($services)>0)
                    @foreach($services as $service)
                        <th class="text-center" scope="col" style='position: sticky;top:0px;background:#f3b389;border:1px solid #000'>IN</th>
                        <th class="text-center" scope="col" style='position: sticky;top:0px;background:#78D658;border:1px solid #000'>OUT</th> 
                    @endforeach
                @endif
            </tr>
        </thead>
        <tbody id="dummy_data" class="excel-table-data">
            @if(count($imported_data)>0)
                @foreach ($imported_data as $key => $value)
                    @if($value[0]=='' || $value[0]==null)
                        <?php continue; ?>
                    @endif
                    @if($key < count($clients))
                        <tr>
                            @for($i=0; $i<168; $i++)
                                <td class="text-right">{{$value[$i]}}</td>
                            @endfor
                        </tr>
                    @else
                        <tr>
                            @for($i=0; $i<168; $i++)
                                @if($key == count($clients))
                                    @if($i==0)
                                        <td class="text-center" style="background:#A8E3F4;border:1px solid #000;"><b>{{$value[$i]}}</b></td>
                                    @else
                                        <td class="text-right" style="background:#A8E3F4;border:1px solid #000;"><b>{{$value[$i]}}</b></td>
                                    @endif
                                @else
                                    @if($i==0)
                                        <td class="text-center text-danger" style="background:#58ACD6;border:1px solid #000;"><b>{{$value[$i]}}</b></td>
                                    @else
                                        <td class="text-right text-danger" style="background:#58ACD6;border:1px solid #000;"><b>{{$value[$i]}}</b></td>
                                    @endif
                                @endif
                            @endfor
                        </tr>
                    @endif
                @endforeach
            @endif
        </tbody>
    </table>
</div>