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
            @php
                use App\Traits\CommonTrait;
                $edu_count = 5;
                $emp_count = 5;
                $ref_count = 5;
                $addr_count = 7;
                $pcc_count = 3;
                $law_count = 3;
                $cr_count = 5;
                $jud_count = 3;
                $id_count = 2;
                $db_count = 2;
            @endphp
            <tr>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Client</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Candidate</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Entity</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Department</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Employee Code</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Client Spokeman</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Reference No.</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Date of Receiving</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Month of Receiving</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Re - Initiate</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Actual Date Case Received</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>TAT</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Actual TAT</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Case Wise or Check Wise</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Calender Days or Working Days</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Internal Due Date</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Client Due Date</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Date of Submission</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Stop Date</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Current Aging of Case</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Status</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Today</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>1st Level Insufficiency Raised on</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>1st Level Insufficiency Cleared on</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Reason</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>2nd Level Insufficiency Raised on</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>2nd Level Insufficiency Cleared on</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Reason</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Case Status</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Case Status with TAT</th>
                {{-- Education Check --}}
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Education</th>
                @for($i=1;$i<=$edu_count;$i++) 
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>{{'EducationCheck - '.$i}}</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Amt</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>University</th>               
                @endfor
                {{--  Employment Check --}}
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>EMPLOYMENT</th>
                @for($i=1;$i<=$emp_count;$i++)
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>{{'EmploymentCheck - '.$i}}</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Amt</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Employer Name</th>
                @endfor
                {{--  REFERENCE --}}
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Reference Check</th>
                @for($i=1;$i<=$ref_count;$i++)
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>{{'ReferenceCheck - '.$i}}</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Amt</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Reference Name</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Reference Contact-No</th>
                @endfor
                {{--  Address Check --}}
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>ADDRESS</th>
                @for($i=1;$i<=$addr_count;$i++)
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>{{'AddressCheck - '.$i}}</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Amt</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Details</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>City</th>
                @endfor
                {{--  PCC / Police Check --}}
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>PCC / Police Record Check</th>
                @for($i=1;$i<=$pcc_count;$i++)
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>{{'PCC - '.$i.' / Police Record Check'}}</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Amt</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Details</th>
                @endfor
                {{--  Law Firm Check --}}
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>LAW FIRM</th>
                @for($i=1;$i<=$law_count;$i++)
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>{{'Law Firm - '.$i}}</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Amt</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Details</th>
                @endfor
                {{--  Criminal Check --}}
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>CRIMINAL</th>
                @for($i=1;$i<=$cr_count;$i++)
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>{{'Criminal - '.$i}}</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Amt</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Details</th>
                @endfor
                {{--  Judicial Check --}}
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>JUDIS</th>
                @for($i=1;$i<=$jud_count;$i++)
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>{{'Judis - '.$i}}</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Amt</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Details</th>
                @endfor
                {{--  Identify Check --}}
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>IDENTITY</th>
                @for($i=1;$i<=$id_count;$i++)
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>{{'IdentityCheck - '.$i}}</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Amt</th>
                @endfor
                {{--  Database Check --}}
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>DATABASE</th>
                @for($i=1;$i<=$db_count;$i++)
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>{{'DatabaseCheck - '.$i}}</th>
                    <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Amt</th>
                @endfor
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Total Components</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Final Amount</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Education Insuff</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Employment Insuff</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Ref Insuff</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Add Insuff</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>PCC/CRI Insuff</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Law Firm Insuff</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Criminal Insuff</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Judis Insuff</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Id Insuff</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Database Insuff</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Total Insuff Pending</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Education Wip</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Employment Wip</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Ref Wip</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Add Wip</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>PCC/CRI Wip</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Law Firm Wip</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Criminal Wip</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Judis Wip</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Id Wip</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Database Wip</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>DrugTest Wip</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Total Wip</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>mxa</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>With Insuff Report</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Report Not Received</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>Additional Remark's</th>
                <th class="text-center" scope="col" style='position: sticky;top:0px;background:#67B2DC;border:1px solid #000'>DOP</th>
            </tr>
        </thead>
        <tbody>
            @if(count($progress_list)>0)
                @foreach ($progress_list as $user)
                    @php
                        $candidate_date = date('Y-m-d',strtotime($user->created_at));
                    @endphp
                    <tr>
                        <td>{{$user->company_name}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->sla_name!=NULL ? $user->sla_name : 'N/A'}}</td>
                        <td>{{$user->department!=NULL ? $user->department : 'N/A'}}</td>
                        <td>{{$user->client_emp_code!=NULL ? $user->client_emp_code : 'N/A'}}</td>
                        @php
                            // Client Spokeman
                            $spokeman = '';
                            $spoke_arr=[];

                            if($user->client_spokeman!=NULL)
                            {
                                $spoke_arr = json_decode($user->client_spokeman,2);
                                if(count($spoke_arr)>0)
                                {
                                    $len = count($spoke_arr);
                                    $i=0;
                                    foreach($spoke_arr as $value)
                                    {
                                        if($len!=$i)
                                        {
                                            $spokeman = $spokeman.ucwords($value).', ';
                                        }
                                        else
                                        {
                                            $spokeman = $spokeman.ucwords($value);
                                        }

                                        $i++;
                                    }
                                }
                                else
                                {
                                    $spokeman = 'N/A';
                                }
                            }
                            else
                            {
                                $spokeman = 'N/A';
                            }
                        @endphp
                        <td>{{$spokeman}}</td>
                        <td>{{$user->display_id!=NULL ? $user->display_id : 'N/A'}}</td>
                        <td>{{$user->case_received_date!=NULL ? date('d-M-y',strtotime($user->case_received_date)) : 'N/A'}}</td>
                        <td>{{$user->case_received_date!=NULL ? date('d-M-y',strtotime($user->case_received_date)) : 'N/A'}}</td>
                        @php
                            // Re-initiate

                            $re_initiate = 'N/A';

                            $candidate_hold_resume = DB::table('candidate_hold_statuses')
                                                ->where(['candidate_id'=>$user->id])
                                                ->whereNotNull('hold_by')
                                                ->whereNotNull('hold_remove_by')
                                                ->latest()
                                                ->first();

                            if($candidate_hold_resume!=NULL)
                            {
                                $re_initiate = date('d-M-Y',strtotime($candidate_hold_resume->hold_remove_at));
                            }
                        @endphp 
                        <td>{{$re_initiate}}</td>
                        <td>{{$user->case_received_date!=NULL ? date('d-M-y',strtotime($user->case_received_date)) : 'N/A'}}</td>
                        <td>{{$user->tat}}</td>
                        <td>{{$user->client_tat}}</td>
                        <td>{{ucfirst($user->tat_type).' - Wise'}}</td>
                        <td>{{ucfirst($user->days_type).' - Days'}}</td>
                        @php

                            // Internal & Client Due Date

                            $date_arr = [];
                            $tat = $user->tat - 1;
                            $client_tat = $user->client_tat - 1;
                            $tat_date = 'N/A';
                            $client_tat_date = 'N/A';

                            if(stripos($user->days_type,'working')!==false)
                            {
                                $date_arr = CommonTrait::workingDays($candidate_date,$tat,$client_tat);

                                $tat_date = $date_arr['tat_date'];

                                $client_tat_date = $date_arr['inc_tat_date'];
                            }
                            else if(stripos($user->days_type,'calender')!==false)
                            {
                                $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$user->parent_id,'status'=>1])
                                                    ->orderBy('date','asc')
                                                    ->get();

                                if(count($holiday_master)>0)
                                {
                                    $date_arr = CommonTrait::calenderDays($candidate_date,$holiday_master,$tat,$client_tat);
                                }
                                else
                                {
                                    $date_arr = CommonTrait::workingDays($candidate_date,$tat,$client_tat);
                                }

                                $tat_date = $date_arr['tat_date'];

                                $client_tat_date = $date_arr['inc_tat_date'];
                            }
                        @endphp     
                        <td>{{$tat_date}}</td>
                        <td>{{$client_tat_date}}</td>
                        <td>{{$user->is_report_complete==1 && $user->case_completed_date!=NULL ? date('d-M-y',strtotime($user->case_completed_date)) : 'N/A'}}</td>
                        @php
                            // Stop Date

                            $stop_date  = 'N/A';

                            $candidate_hold = DB::table('candidate_hold_statuses')
                                                ->where(['candidate_id'=>$user->id])
                                                ->whereNotNull('hold_by')
                                                ->whereNull('hold_remove_by')
                                                ->latest()
                                                ->first();

                            if($candidate_hold!=NULL)
                            {
                                $stop_date = date('d-M-Y',strtotime($candidate_hold->hold_at));
                            }
                        @endphp  
                        <td>{{$stop_date}}</td>
                        <td>N/A</td>
                        @php

                            // Status & Today

                            $status = 'WIP';
        
                            if($candidate_hold!=NULL)
                            {
                                $status = 'STOP';
                            }
                            else if(stripos($user->report_status,'completed')!==false)
                            {
                                $status = 'Complete';
                            }
                        @endphp 
                        <td>{{$status}}</td>
                        <td>{{date('d-M-y')}}</td>
                        @php

                            // 1st level & 2nd insuff raise & clear 

                            $first_raise_date = 'N/A';

                            $second_raise_date = 'N/A';

                            $first_clear_date = 'N/A';

                            $first_clear_reason = 'N/A';

                            $second_clear_date = 'N/A';

                            $second_clear_reason = 'N/A';


                            $raise_insuff_all = DB::table('insufficiency_logs')->where(['candidate_id'=>$user->id])->whereIn('status',['raised','failed'])->orderBy('created_at','desc')->take(2)->get();

                            $clear_insuff_all = DB::table('insufficiency_logs')->where(['candidate_id'=>$user->id,'status'=>'removed'])->orderBy('created_at','desc')->take(2)->get();

                            if(count($raise_insuff_all) > 0)
                            {
                                $latest_raise_insuff = DB::table('insufficiency_logs')->where(['candidate_id'=>$user->id])->whereIn('status',['raised','failed'])->latest()->first();

                                if($latest_raise_insuff!=NULL)
                                {
                                    $first_raise_date = date('d-M-y',strtotime($latest_raise_insuff->created_at));
                                }

                                if(count($raise_insuff_all) >= 2)
                                {
                                    $second_latest_raise_insuff = DB::table('insufficiency_logs')
                                                                    ->where(['candidate_id'=>$user->id])
                                                                    ->whereIn('status',['raised','failed'])
                                                                    ->orderBy('created_at','desc')
                                                                    ->skip(1)
                                                                    ->take(1)
                                                                    ->first();

                                    if($second_latest_raise_insuff!=NULL)
                                        $second_raise_date = date('d-M-y',strtotime($second_latest_raise_insuff->created_at));
                                }
                            }

                            if(count($clear_insuff_all) > 0)
                            {
                                $latest_clear_insuff = DB::table('insufficiency_logs')->where(['candidate_id'=>$user->id,'status'=>'removed'])->latest()->first();

                                if($latest_clear_insuff!=NULL)
                                {
                                    $first_clear_date = date('d-M-y',strtotime($latest_clear_insuff->created_at));

                                    $first_clear_reason = $latest_clear_insuff->notes!=NULL ? $latest_clear_insuff->notes : 'N/A';
                                }

                                if(count($clear_insuff_all) >= 2)
                                {
                                    $second_latest_clear_insuff = DB::table('insufficiency_logs')
                                                                    ->where(['candidate_id'=>$user->id,'status'=>'removed'])
                                                                    ->orderBy('created_at','desc')
                                                                    ->skip(1)
                                                                    ->take(1)
                                                                    ->first();
                                                                    
                                    if($second_latest_clear_insuff!=NULL)
                                    {
                                        $second_clear_date = date('d-M-y',strtotime($second_latest_clear_insuff->created_at));

                                        $second_clear_reason = $second_latest_clear_insuff->notes!=NULL ? $second_latest_clear_insuff->notes : 'N/A';
                                    }
                                }
                            }

                        @endphp 
                        <td>{{$first_raise_date}}</td>
                        <td>{{$first_clear_date}}</td>
                        <td>{{$first_clear_reason}}</td>
                        <td>{{$second_raise_date}}</td>
                        <td>{{$second_clear_date}}</td>
                        <td>{{$second_clear_reason}}</td>

                        @php

                            // Case Status & Case Status with TAT

                            $case_status = 'Open';

                            $case_status_with_tat = 'Open BT';

                            if($candidate_hold!=NULL)
                            {
                                $case_status = "STOP";

                                $case_status_with_tat = "STOP";
                            }
                            else if(stripos($user->report_status,'completed')!==false)
                            {
                                $case_status = "Close";

                                if($client_tat_date!='N/A' && strtotime(date('Y-m-d',strtotime($user->case_completed_date)) <= strtotime(date('Y-m-d',strtotime($client_tat_date)))))
                                {
                                    $case_status_with_tat = 'Close WT';
                                }
                                else
                                {
                                    $case_status_with_tat = 'Close BT';
                                }
                            }
                        @endphp
                        <td>{{$case_status}}</td>
                        <td>{{$case_status_with_tat}}</td>
                       
                        @php
                            $status_arr = [];

                            $final_amt_arr = [];

                            // Education Check
                            $edu_report_items = Helper::get_check_report_items($user->id,'educational');

                            $edu_status = 'N/A';

                            $edu_status_arr = [];

                            $edu_amt = 0.00;

                            $edu_university = 'N/A';
                        @endphp

                        <td>EDUCATION</td>
                        @if(count($edu_report_items)>0)

                            @php
                                $remain = 0;
                                $j = 0;
                            @endphp

                            @foreach ($edu_report_items as $edu)
                                @php
                                    $edu_status = 'WIP';

                                    $edu_amt = 0.00;

                                    $edu_university = 'N/A';

                                    if(stripos($user->report_status,'completed')!==false)
                                    {
                                        $edu_status = 'Complete';
                                    }

                                    if($edu->is_supplementary==1)
                                    {
                                        $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$edu->service_id,'number_of_verifications'=>$edu->service_item_number,'is_supplementary'=>'1'])->first();

                                        if($job_sla_items!=NULL)
                                        {
                                            $edu_amt = $job_sla_items->price;
                                        }
                                    }
                                    else
                                    {
                                        if(stripos($user->price_type,'package')!==false)
                                        {
                                            $edu_report_item_sup = DB::table('report_items as ri')
                                                                    ->join('services as s','s.id','=','ri.service_id')
                                                                    ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'educational','ri.is_supplementary'=>'0'])
                                                                    ->get();

                                            $count = count($edu_report_item_sup) > 0 ? count($edu_report_item_sup) : 1;

                                            $edu_amt = CommonTrait::round_up($user->package_price/$count,2);
                                        }
                                        else if(stripos($user->price_type,'check')!==false)
                                        {
                                            $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$edu->service_id,'is_supplementary'=>'0'])->first();

                                            if($job_sla_items!=NULL)
                                                $edu_amt = $job_sla_items->price;
                                        }
                                    }

                                    if($edu->jaf_data!=NULL)
                                    {
                                        $input_item_data_array=[];
                                        $input_item_data_array = json_decode($edu->jaf_data,true);

                                        if(count($input_item_data_array)>0)
                                        {
                                            foreach($input_item_data_array as $key => $input)
                                            {
                                                $key_val = array_keys($input); $input_val = array_values($input);

                                                if(stripos($key_val[0],'University Name / Board Name')!==false){ 
                                                    $edu_university = $input_val[0];
                                                }
                                            }
                                        }
                                    }

                                    $status_arr[]=$edu_status;

                                    $final_amt_arr[]=$edu_amt;

                                    $edu_status_arr[]= $edu_status;

                                    $j++;

                                @endphp
                                <td>{{$edu_status}}</td>
                                <td>{{strval($edu_amt)}}</td>
                                <td>{{$edu_university!='' ? $edu_university : 'N/A'}}</td>
                            @endforeach

                            @php
                                $remain = $edu_count - $j;
                            @endphp

                            @if($remain > 0)
                                @php
                                    $edu_status = 'N/A';

                                    $edu_amt = 0.00;

                                    $edu_university = 'N/A';
                                @endphp

                                @for($i=1;$i<=$remain;$i++)
                                    @php
                                        $status_arr[]=$edu_status;
                
                                        $final_amt_arr[]=$edu_amt;
                                    @endphp 
                                    <td>{{$edu_status}}</td>
                                    <td>{{strval($edu_amt)}}</td>
                                    <td>{{$edu_university}}</td>
                                @endfor
                            @endif
                        @else
                            @for($i=1;$i<=$edu_count;$i++)
                                <td>{{$edu_status}}</td>
                                <td>{{strval($edu_amt)}}</td>
                                <td>{{$edu_university}}</td>
                            @endfor
                        @endif

                        @php
                            // Employment Check
                            $emp_report_items = Helper::get_check_report_items($user->id,'employment');

                            $emp_status = 'N/A';

                            $emp_status_arr=[];

                            $emp_amt = 0.00;

                            $emp_name = 'N/A';
                        @endphp

                        <td>EMPLOYMENT</td>
                        @if(count($edu_report_items)>0)
                            @php
                                $remain = 0;
                                $j = 0;
                            @endphp
                            @foreach($emp_report_items as $emp)
                                @php

                                    $emp_status = 'WIP';

                                    $emp_amt = 0.00;

                                    $emp_name = 'N/A';

                                    if(stripos($user->report_status,'completed')!==false)
                                    {
                                        $emp_status = 'Complete';
                                    }

                                    if($emp->is_supplementary==1)
                                    {
                                        $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$emp->service_id,'number_of_verifications'=>$emp->service_item_number,'is_supplementary'=>'1'])->first();

                                        if($job_sla_items!=NULL)
                                        {
                                            $emp_amt = $job_sla_items->price;
                                        }
                                    }
                                    else
                                    {
                                        if(stripos($user->price_type,'package')!==false)
                                        {
                                            $emp_report_item_sup = DB::table('report_items as ri')
                                                                    ->join('services as s','s.id','=','ri.service_id')
                                                                    ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'employment','ri.is_supplementary'=>'0'])
                                                                    ->get();

                                            $count = count($emp_report_item_sup) > 0 ? count($emp_report_item_sup) : 1;

                                            $emp_amt = CommonTrait::round_up($user->package_price/$count,2);
                                        }
                                        else if(stripos($user->price_type,'check')!==false)
                                        {
                                            $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$emp->service_id,'is_supplementary'=>'0'])->first();

                                            if($job_sla_items!=NULL)
                                                $emp_amt = $job_sla_items->price;
                                        }
                                    }

                                    if($emp->jaf_data!=NULL)
                                    {
                                        $input_item_data_array=[];
                                        $input_item_data_array = json_decode($emp->jaf_data,true);

                                        if(count($input_item_data_array)>0)
                                        {
                                            $emp_name = '';
                                            foreach($input_item_data_array as $key => $input)
                                            {
                                                $key_val = array_keys($input); $input_val = array_values($input);

                                                if(stripos($key_val[0],'First name')!==false){ 
                                                    $emp_name = $emp_name.$input_val[0];
                                                }

                                                if(stripos($key_val[0],'Last name')!==false){ 
                                                    $emp_name = $emp_name.$input_val[0];
                                                }
                                            }
                                        }
                                    }

                                    $status_arr[]=$emp_status;
                
                                    $final_amt_arr[]=$emp_amt;

                                    $emp_status_arr[]=$emp_status;

                                    $j++;

                                    

                                @endphp 

                                <td>{{$emp_status}}</td>
                                <td>{{strval($emp_amt)}}</td>
                                <td>{{$emp_name!='' ? $emp_name : $user->name}}</td>

                                
                            @endforeach

                            @php
                                $remain = $emp_count - $j;
                            @endphp

                            @if ($remain > 0)
                                                                
                                @php
                                    $emp_status = 'N/A';

                                    $emp_amt = 0.00;

                                    $emp_name = 'N/A';
                                @endphp 

                                @for($i=1;$i<=$remain;$i++)
                                    <td>{{$emp_status}}</td>
                                    <td>{{strval($emp_amt)}}</td>
                                    <td>{{$emp_name}}</td>    
                                @endfor

                            @endif
                        @else
                            @for($i=1;$i<=$emp_count;$i++)
                                <td>{{$emp_status}}</td>
                                <td>{{strval($emp_amt)}}</td>
                                <td>{{$emp_name}}</td>
                            @endfor
                        @endif

                        @php
                            // Reference Check
                            $ref_report_items = Helper::get_check_report_items($user->id,'reference');

                            $ref_status = 'N/A';

                            $ref_amt = 0.00;

                            $ref_status_arr=[];

                            $ref_name = 'N/A';

                            $ref_contact = 'N/A';
                        @endphp
                        <td>REFERENCE</td>
                        @if(count($ref_report_items)>0)
                            @php
                                $remain = 0;

                                $j = 0;
                            @endphp
                            @foreach($ref_report_items as $ref)
                                @php
                                    $ref_status = 'WIP';

                                    $ref_amt = 0.00;

                                    $ref_name = 'N/A';

                                    $ref_contact = 'N/A';

                                    if(stripos($user->report_status,'completed')!==false)
                                    {
                                        $ref_status = 'Complete';
                                    }

                                    if($ref->is_supplementary==1)
                                    {
                                        $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$ref->service_id,'number_of_verifications'=>$ref->service_item_number,'is_supplementary'=>'1'])->first();

                                        if($job_sla_items!=NULL)
                                        {
                                            $ref_amt = $job_sla_items->price;
                                        }
                                    }
                                    else
                                    {
                                        if(stripos($user->price_type,'package')!==false)
                                        {
                                            $ref_report_item_sup = DB::table('report_items as ri')
                                                                    ->join('services as s','s.id','=','ri.service_id')
                                                                    ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'reference','ri.is_supplementary'=>'0'])
                                                                    ->get();

                                            $count = count($ref_report_item_sup) > 0 ? count($ref_report_item_sup) : 1;

                                            $ref_amt = CommonTrait::round_up($user->package_price/$count,2);
                                        }
                                        else if(stripos($user->price_type,'check')!==false)
                                        {
                                            $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$ref->service_id,'is_supplementary'=>'0'])->first();

                                            if($job_sla_items!=NULL)
                                                $ref_amt = $job_sla_items->price;
                                        }
                                    }

                                    if($ref->jaf_data!=NULL)
                                    {
                                        $input_item_data_array=[];
                                        $input_item_data_array = json_decode($ref->jaf_data,true);

                                        if(count($input_item_data_array)>0)
                                        {
                                            foreach($input_item_data_array as $key => $input)
                                            {
                                                $key_val = array_keys($input); $input_val = array_values($input);

                                                if(stripos($key_val[0],'Referee Name')!==false){ 
                                                    $ref_name = $input_val[0];
                                                }

                                                if(stripos($key_val[0],'Referee Contact Number')!==false){ 
                                                    $ref_contact = $input_val[0];
                                                }
                                            }
                                        }
                                    }

                                    $status_arr[]=$ref_status;
                                    
                                    $final_amt_arr[]=$ref_amt;

                                    $ref_status_arr[]=$ref_status;

                                    $j++;

                                    
                                @endphp 
                                <td>{{$ref_status}}</td>
                                <td>{{strval($ref_amt)}}</td>
                                <td>{{$ref_name!='' ? $ref_name : 'N/A'}}</td>
                                <td>{{$ref_contact!='' ? $ref_contact : 'N/A'}}</td>
                            @endforeach
                            @php
                                $remain = $ref_count - $j;
                            @endphp
                            @if($remain > 0)
                                @php
                                    $ref_status = 'N/A';

                                    $ref_amt = 0.00;

                                    $ref_name = 'N/A';

                                    $ref_contact = 'N/A';
                                @endphp 
                                
                                @for($i=1;$i<=$remain;$i++)
                                    @php
                                        $status_arr[]=$ref_status;
                
                                        $final_amt_arr[]=$ref_amt;
                                    @endphp 

                                    <td>{{$ref_status}}</td>
                                    <td>{{strval($ref_amt)}}</td>
                                    <td>{{$ref_name}}</td>
                                    <td>{{$ref_contact}}</td>
                                @endfor
                            @endif
                        @else
                            @for($i=1;$i<=$ref_count;$i++)
                                @php
                                    $status_arr[]=$ref_status;
                    
                                    $final_amt_arr[]=$ref_amt;    
                                @endphp
                                <td>{{$ref_status}}</td>
                                <td>{{strval($ref_amt)}}</td>
                                <td>{{$ref_name}}</td>
                                <td>{{$ref_contact}}</td>
                            @endfor
                        @endif
                        
                        @php
                            // Address Check

                            $addr_report_items = Helper::get_check_report_items($user->id,'address');

                            $addr_status = 'N/A';

                            $addr_status_arr=[];

                            $addr_amt = 0.00;

                            $addr_detail = 'N/A';

                            $addr_city = 'N/A';

                        @endphp
                        <td>ADDRESS</td>
                       
                        @if(count($addr_report_items) > 0)
                            @php
                                $remain = 0;

                                $j = 0;
                            @endphp
                            
                            @foreach($addr_report_items as $addr)
                                @php
                                    $addr_status = 'WIP';

                                    $addr_amt = 0.00;

                                    $addr_detail = 'N/A';

                                    $addr_city = 'N/A';

                                    if(stripos($user->report_status,'completed')!==false)
                                    {
                                        $addr_status = 'Complete';
                                    }

                                    if($addr->is_supplementary==1)
                                    {
                                        $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$addr->service_id,'number_of_verifications'=>$addr->service_item_number,'is_supplementary'=>'1'])->first();

                                        if($job_sla_items!=NULL)
                                        {
                                            $addr_amt = $job_sla_items->price;
                                        }
                                    }
                                    else
                                    {
                                        if(stripos($user->price_type,'package')!==false)
                                        {
                                            $addr_report_item_sup = DB::table('report_items as ri')
                                                                    ->join('services as s','s.id','=','ri.service_id')
                                                                    ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'address','ri.is_supplementary'=>'0'])
                                                                    ->get();

                                            $count = count($addr_report_item_sup) > 0 ? count($addr_report_item_sup) : 1;

                                            $addr_amt = CommonTrait::round_up($user->package_price/$count,2);
                                        }
                                        else if(stripos($user->price_type,'check')!==false)
                                        {
                                            $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$addr->service_id,'is_supplementary'=>'0'])->first();

                                            if($job_sla_items!=NULL)
                                                $addr_amt = $job_sla_items->price;
                                        }
                                    }

                                    if($addr->jaf_data!=NULL)
                                    {
                                        $input_item_data_array=[];
                                        $input_item_data_array = json_decode($addr->jaf_data,true);

                                        if(count($input_item_data_array)>0)
                                        {
                                            $addr_detail = '';
                                            foreach($input_item_data_array as $key => $input)
                                            {
                                                $key_val = array_keys($input); $input_val = array_values($input);

                                                if(stripos($key_val[0],'Address')!==false){ 
                                                    $addr_detail .= $input_val[0];
                                                }

                                                if(stripos($key_val[0],'State')!==false){ 
                                                    $addr_detail .= ' '.$input_val[0];
                                                }

                                                if(stripos($key_val[0],'Pin code')!==false){ 
                                                    $addr_detail .= ' '.$input_val[0];
                                                }

                                                if(stripos($key_val[0],'City')!==false){ 
                                                    $addr_city = $input_val[0];
                                                }
                                            }
                                        }
                                    }

                                    $status_arr[]=$addr_status;
                
                                    $final_amt_arr[]=$addr_amt;

                                    $addr_status_arr[] = $addr_status;

                                    $j++;
                                @endphp

                                <td>{{$addr_status}}</td>
                                <td>{{strval($addr_amt)}}</td>
                                <td>{{str_replace(' ','',$addr_detail)!='' ? $addr_detail : 'N/A'}}</td>
                                <td>{{$addr_city!='' ? $addr_city : 'N/A'}}</td>
                            @endforeach

                            @php
                                $remain = $addr_count - $j;
                            @endphp

                            @if($remain > 0)
                                @php
                                    $addr_status = 'N/A';

                                    $addr_amt = 0.00;

                                    $addr_detail = 'N/A';

                                    $addr_city = 'N/A';
                                @endphp 

                                @for($i=1;$i<=$remain;$i++)
                                    @php
                                        $status_arr[]=$addr_status;
                
                                        $final_amt_arr[]=$addr_amt;
                                    @endphp 
                                    <td>{{$addr_status}}</td>
                                    <td>{{strval($addr_amt)}}</td>
                                    <td>{{$addr_detail}}</td>
                                    <td>{{$addr_city}}</td>
                                @endfor
                            @endif

                        @else
                            @for($i=1;$i<=$addr_count;$i++)
                                @php
                                    $status_arr[]=$addr_status;
                
                                    $final_amt_arr[]=$addr_amt;
                                @endphp 
                                 <td>{{$addr_status}}</td>
                                 <td>{{strval($addr_amt)}}</td>
                                 <td>{{$addr_detail}}</td>
                                 <td>{{$addr_city}}</td>
                            @endfor      
                        @endif

                        @php
                            // PCC Check
                            $pcc_status = 'N/A';

                            $pcc_status_arr=[];

                            $pcc_amt = 0.00;

                            $pcc_detail = 'N/A';
                        @endphp
                        <td>PCC</td>
                        @for($i=1;$i<=$pcc_count;$i++)
                            @php
                                $status_arr[]=$pcc_status;
                
                                $final_amt_arr[]=$pcc_amt;
                            @endphp
                             <td>{{$pcc_status}}</td>
                             <td>{{strval($pcc_amt)}}</td>
                             <td>{{$pcc_detail}}</td>
                        @endfor

                        @php
                            // Law Firm Check
                            $law_status = 'N/A';

                            $law_status_arr=[];

                            $law_amt = 0.00;

                            $law_detail = 'N/A';
                        @endphp
                        <td>LAW FIRM</td>
                        @for($i=1;$i<=$law_count;$i++)
                            @php
                                $status_arr[]=$law_status;
                
                                $final_amt_arr[]=$law_amt;
                            @endphp
                            <td>{{$law_status}}</td>
                            <td>{{strval($law_amt)}}</td>
                            <td>{{$law_detail}}</td>
                        @endfor

                        @php
                            // Criminal Check
                            $cr_report_items = Helper::get_check_report_items($user->id,'criminal');

                            $cr_status = 'N/A';

                            $cr_status_arr=[];

                            $cr_amt = 0.00;

                            $cr_detail = 'N/A';

                        @endphp

                        <td>CRIMINAL</td>
                        @if(count($cr_report_items) > 0)
                            @php
                                $remain = 0;

                                $j = 0;
                            @endphp 
                            @foreach($cr_report_items as $cr)
                                @php
                                    $cr_status = 'WIP';

                                    $cr_amt = 0.00;

                                    $cr_detail = 'N/A';

                                    if(stripos($user->report_status,'completed')!==false)
                                    {
                                        $cr_status = 'Complete';
                                    }

                                    if($user->is_supplementary==1)
                                    {
                                        $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$cr->service_id,'number_of_verifications'=>$cr->service_item_number,'is_supplementary'=>'1'])->first();

                                        if($job_sla_items!=NULL)
                                        {
                                            $cr_amt = $job_sla_items->price;
                                        }
                                    }
                                    else
                                    {
                                        if(stripos($user->price_type,'package')!==false)
                                        {
                                            $cr_report_item_sup = DB::table('report_items as ri')
                                                                    ->join('services as s','s.id','=','ri.service_id')
                                                                    ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'criminal','ri.is_supplementary'=>'0'])
                                                                    ->get();

                                            $count = count($cr_report_item_sup) > 0 ? count($cr_report_item_sup) : 1;

                                            $cr_amt = CommonTrait::round_up($user->package_price/$count,2);
                                        }
                                        else if(stripos($user->price_type,'check')!==false)
                                        {
                                            $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$cr->service_id,'is_supplementary'=>'0'])->first();

                                            if($job_sla_items!=NULL)
                                                $cr_amt = $job_sla_items->price;
                                        }
                                    }

                                    if($cr->jaf_data!=NULL)
                                    {
                                        $input_item_data_array=[];
                                        $input_item_data_array = json_decode($cr->jaf_data,true);

                                        if(count($input_item_data_array)>0)
                                        {
                                            foreach($input_item_data_array as $key => $input)
                                            {
                                                $key_val = array_keys($input); $input_val = array_values($input);

                                                if(stripos($key_val[0],'Address')!==false){ 
                                                    $cr_detail = $input_val[0];
                                                }
                                            }
                                        }
                                    }

                                    $status_arr[]=$cr_status;

                                    $final_amt_arr[]=$cr_amt;

                                    $cr_status_arr[] = $cr_status;

                                    $j++;
                                @endphp
                                 <td>{{$cr_status}}</td>
                                 <td>{{strval($cr_amt)}}</td>
                                 <td>{{$cr_detail}}</td>
                            @endforeach

                            @php
                                $remain = $cr_count - $j;
                            @endphp

                            @if ($remain > 0)
                                @php
                                    $cr_status = 'N/A';

                                    $cr_amt = 0.00;

                                    $cr_detail = 'N/A';
                                @endphp 
                                @for($i=1;$i<=$remain;$i++)
                                    @php
                                        $status_arr[]=$cr_status;
                
                                        $final_amt_arr[]=$cr_amt;
                                    @endphp 
                                    <td>{{$cr_status}}</td>
                                    <td>{{strval($cr_amt)}}</td>
                                    <td>{{$cr_detail}}</td>
                                @endfor
                            @endif
                        @else
                            @for($i=1;$i<=$cr_count;$i++)
                                @php
                                    $status_arr[]=$cr_status;
                
                                    $final_amt_arr[]=$cr_amt;
                                @endphp
                                 <td>{{$cr_status}}</td>
                                 <td>{{strval($cr_amt)}}</td>
                                 <td>{{$cr_detail}}</td>
                            @endfor
                        @endif

                        @php
                            // Judicial Check
                            $jud_report_items = Helper::get_check_report_items($user->id,'judicial');

                            $jud_status = 'N/A';

                            $jud_status_arr=[];

                            $jud_amt = 0.00;

                            $jud_detail = 'N/A';
                        @endphp
                        <td>JUDIS</td>
                        
                        @if (count($jud_report_items) > 0)
                            @php
                                $remain = 0;

                                $j = 0;
                            @endphp
                            @foreach($jud_report_items as $item)
                                @php
                                    $jud_status = 'WIP';

                                    $jud_amt = 0.00;

                                    $jud_detail = 'N/A';

                                    if(stripos($user->report_status,'completed')!==false)
                                    {
                                        $jud_status = 'Complete';
                                    }

                                    if($item->is_supplementary==1)
                                    {
                                        $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$item->service_id,'number_of_verifications'=>$item->service_item_number,'is_supplementary'=>'1'])->first();

                                        if($job_sla_items!=NULL)
                                        {
                                            $jud_amt = $job_sla_items->price;
                                        }
                                    }
                                    else
                                    {
                                        if(stripos($user->price_type,'package')!==false)
                                        {
                                            $jud_report_item_sup = DB::table('report_items as ri')
                                                                    ->join('services as s','s.id','=','ri.service_id')
                                                                    ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'judicial','ri.is_supplementary'=>'0'])
                                                                    ->get();

                                            $count = count($jud_report_item_sup) > 0 ? count($jud_report_item_sup) : 1;

                                            $jud_amt = $this->round_up($user->package_price/$count,2);
                                        }
                                        else if(stripos($user->price_type,'check')!==false)
                                        {
                                            $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$item->service_id,'is_supplementary'=>'0'])->first();

                                            if($job_sla_items!=NULL)
                                                $jud_amt = $job_sla_items->price;
                                        }
                                    }

                                    if($item->jaf_data!=NULL)
                                    {
                                        $input_item_data_array=[];
                                        $input_item_data_array = json_decode($item->jaf_data,true);

                                        if(count($input_item_data_array)>0)
                                        {
                                            foreach($input_item_data_array as $key => $input)
                                            {
                                                $key_val = array_keys($input); $input_val = array_values($input);

                                                if(stripos($key_val[0],'Address')!==false){ 
                                                    $jud_detail = $input_val[0];
                                                }
                                            }
                                        }
                                    }

                                    $status_arr[]=$jud_status;

                                    $final_amt_arr[]=$jud_amt;

                                    $jud_status_arr[] = $jud_status;

                                    $j++;
                                @endphp 
                                <td>{{$jud_status}}</td>
                                <td>{{strval($jud_amt)}}</td>
                                <td>{{$jud_detail}}</td>
                            @endforeach

                            @php
                                $remain = $jud_count - $j;
                            @endphp

                            @if($remain > 0)
                                @php
                                    $jud_status = 'N/A';

                                    $jud_amt = 0.00;

                                    $jud_detail = 'N/A';
                                @endphp 

                                @for($i=1;$i<=$remain;$i++)
                                    @php
                                        $status_arr[]=$jud_status;
                
                                        $final_amt_arr[]=$jud_amt;
                                    @endphp
                                    <td>{{$jud_status}}</td>
                                    <td>{{strval($jud_amt)}}</td>
                                    <td>{{$jud_detail}}</td>
                                @endfor
                            @endif

                        @else
                            @for($i=1;$i<=$jud_count;$i++)
                                @php
                                    $status_arr[]=$jud_status;
                
                                    $final_amt_arr[]=$jud_amt;
                                @endphp
                                <td>{{$jud_status}}</td>
                                <td>{{strval($jud_amt)}}</td>
                                <td>{{$jud_detail}}</td>   
                            @endfor
                        @endif
                        
                        @php
                            // Identity Check

                            $id_report_items = Helper::get_check_report_items($user->id,'identity_verification');

                            $id_status = 'N/A';

                            $id_status_arr = [];

                            $id_amt = 0.00;
                        @endphp

                        <td>IDENTITY</td>
                        @if(count($id_report_items) > 0)
                            @php
                                $remain = 0;

                                $j = 0;
                            @endphp
                            @foreach($id_report_items as $item)
                                @php

                                    $id_status = 'WIP';

                                    $id_amt = 0.00;

                                    if(stripos($user->report_status,'completed')!==false)
                                    {
                                        $id_status = 'Complete';
                                    }

                                    if($item->is_supplementary==1)
                                    {
                                        $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$item->service_id,'number_of_verifications'=>$item->service_item_number,'is_supplementary'=>'1'])->first();

                                        if($job_sla_items!=NULL)
                                        {
                                            $id_amt = $job_sla_items->price;
                                        }
                                    }
                                    else
                                    {
                                        if(stripos($user->price_type,'package')!==false)
                                        {
                                            $id_report_item_sup = DB::table('report_items as ri')
                                                                    ->join('services as s','s.id','=','ri.service_id')
                                                                    ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'identity_verification','ri.is_supplementary'=>'0'])
                                                                    ->get();

                                            $count = count($id_report_item_sup) > 0 ? count($id_report_item_sup) : 1;

                                            $id_amt = $this->round_up($user->package_price/$count,2);
                                        }
                                        else if(stripos($user->price_type,'check')!==false)
                                        {
                                            $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$item->service_id,'is_supplementary'=>'0'])->first();

                                            if($job_sla_items!=NULL)
                                                $id_amt = $job_sla_items->price;
                                        }
                                    }

                                    $status_arr[]=$id_status;

                                    $final_amt_arr[]=$id_amt;

                                    $id_status_arr[] = $id_status;

                                    $j++;
                                @endphp 
                                <td>{{$id_status}}</td>
                                <td>{{strval($id_amt)}}</td>
                            @endforeach

                            @php
                                $remain = $id_count - $j;
                            @endphp

                            @if($remain > 0)
                                @php
                                    $id_status = 'N/A';

                                    $id_amt = 0.00;
                                @endphp
                                @for($i=1;$i<=$remain;$i++)
                                    @php
                                        $status_arr[]=$id_status;
                
                                        $final_amt_arr[]=$id_amt;
                                    @endphp
                                    <td>{{$id_status}}</td>
                                    <td>{{strval($id_amt)}}</td>
                                @endfor
                            @endif
                        @else
                            @for($i=1;$i<=$id_count;$i++)
                                @php
                                    $status_arr[]=$id_status;
                
                                    $final_amt_arr[]=$id_amt;
                                @endphp
                                <td>{{$id_status}}</td>
                                <td>{{strval($id_amt)}}</td>
                            @endfor
                        @endif
                        
                        @php
                            // Database Check

                            $db_report_items = Helper::get_check_report_items($user->id,'database');

                            $db_status = 'N/A';

                            $db_status_arr=[];

                            $db_amt = 0.00;
                        @endphp
                        <td>DATABASE</td>

                        @if(count($db_report_items) > 0)
                            @php
                                $remain = 0;
                                $j = 0;
                            @endphp

                            @foreach($db_report_items as $item)
                                @php
                                    if(stripos($user->report_status,'completed')!==false)
                                    {
                                        $db_status = 'Complete';
                                    }

                                    if($item->is_supplementary==1)
                                    {
                                        $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$item->service_id,'number_of_verifications'=>$item->service_item_number,'is_supplementary'=>'1'])->first();

                                        if($job_sla_items!=NULL)
                                        {
                                            $db_amt = $job_sla_items->price;
                                        }
                                    }
                                    else
                                    {
                                        if(stripos($user->price_type,'package')!==false)
                                        {
                                            $db_report_item_sup = DB::table('report_items as ri')
                                                                    ->join('services as s','s.id','=','ri.service_id')
                                                                    ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'database','ri.is_supplementary'=>'0'])
                                                                    ->get();

                                            $count = count($db_report_item_sup) > 0 ? count($db_report_item_sup) : 1;

                                            $db_amt = CommonTrait::round_up($user->package_price/$count,2);
                                        }
                                        else if(stripos($user->price_type,'check')!==false)
                                        {
                                            $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$item->service_id,'is_supplementary'=>'0'])->first();

                                            if($job_sla_items!=NULL)
                                                $db_amt = $job_sla_items->price;
                                        }
                                    }

                                    $status_arr[]=$db_status;
                                    
                                    $final_amt_arr[]=$db_amt;

                                    $db_status_arr[]=$db_status;

                                    $j++;
                                @endphp 
                                <td>{{$db_status}}</td>
                                <td>{{strval($db_amt)}}</td>
                            @endforeach
                            
                            @php
                                $remain = $db_count - $j;
                            @endphp

                            @if($remain > 0)
                                @php
                                    $db_status = 'N/A';

                                    $db_amt = 0.00;
                                @endphp
                                @for($i=1;$i<=$remain;$i++)
                                    @php
                                        $status_arr[]=$db_status;
                
                                        $final_amt_arr[]=$db_amt;
                                    @endphp
                                    <td>{{$db_status}}</td>
                                    <td>{{strval($db_amt)}}</td>
                                @endfor
                            @endif
                        @else
                            @for($i=1;$i<=$db_count;$i++)
                                @php
                                    $status_arr[]=$db_status;
                
                                    $final_amt_arr[]=$db_amt;
                                @endphp
                                <td>{{$db_status}}</td>
                                <td>{{strval($db_amt)}}</td>
                            @endfor
                        @endif

                        @php
                            // Total Component & Final Amount

                            $total_c = 0;

                            $final_amt = 0.00;

                            if(count($status_arr)>0)
                            {
                                foreach($status_arr as $key => $value)
                                {
                                    if(stripos($value,'Complete')!==false)
                                    {
                                        $total_c +=1;
                                    }
                                }
                            }

                            if(count($final_amt_arr)>0)
                            {
                                $final_amt = number_format(array_sum($final_amt_arr),2);
                            }
                        @endphp

                        <td>{{$total_c}}</td>
                        <td>{{strval($final_amt)}}</td>

                        @php
                         
                            // Insuff Check Count

                            // Educational 
                            $edu_insuff_count = count(Helper::get_check_insuff_jaf_items($user->id,'educational'));

                            // Employment
                            $emp_insuff_count = count(Helper::get_check_insuff_jaf_items($user->id,'employment'));

                            // Reference
                            $ref_insuff_count = count(Helper::get_check_insuff_jaf_items($user->id,'reference'));

                            // Address
                            $addr_insuff_count = count(Helper::get_check_insuff_jaf_items($user->id,'address'));

                            // PCC Check

                            $pcc_insuff_count = 0;

                            // Law Firm Check

                            $law_insuff_count = 0;

                            // Criminal Check
                            $cr_insuff_count = count(Helper::get_check_insuff_jaf_items($user->id,'criminal'));

                            // Judicial Check
                            $jud_insuff_count = count(Helper::get_check_insuff_jaf_items($user->id,'judicial'));

                            // Identity Check
                            $id_insuff_count = count(Helper::get_check_insuff_jaf_items($user->id,'identity_verification'));

                            // Database Check
                            $db_insuff_count = count(Helper::get_check_insuff_jaf_items($user->id,'database'));

                        @endphp

                        <td>{{strval($edu_insuff_count)}}</td>
                        <td>{{strval($emp_insuff_count)}}</td>
                        <td>{{strval($ref_insuff_count)}}</td>
                        <td>{{strval($addr_insuff_count)}}</td>
                        <td>{{strval($pcc_insuff_count)}}</td>
                        <td>{{strval($law_insuff_count)}}</td>
                        <td>{{strval($cr_insuff_count)}}</td>
                        <td>{{strval($jud_insuff_count)}}</td>
                        <td>{{strval($id_insuff_count)}}</td>
                        <td>{{strval($db_insuff_count)}}</td>

                        @php
                            // Total Insufficiency Pending

                            $total_insuff = 0;

                            $total_insuff = $edu_insuff_count + $emp_insuff_count + $ref_insuff_count + $addr_insuff_count + $pcc_insuff_count + $law_insuff_count + $cr_insuff_count + $jud_insuff_count + $id_insuff_count + $db_insuff_count;

                        @endphp
                        
                        <td>{{strval($total_insuff)}}</td>

                        @php
                            // WIP Checks
            
                            $total_wip = 0;

                            // Educational

                            $edu_wip_count = 0;

                            if(count($edu_status_arr)>0)
                            {
                                foreach($edu_status_arr as $value)
                                {
                                    if(stripos($value,'WIP')!==false)
                                    {
                                        $edu_wip_count +=1;

                                        $total_wip+=1;
                                    }
                                }
                            }

                            // Employment

                            $emp_wip_count = 0;

                            if(count($emp_status_arr)>0)
                            {
                                foreach($emp_status_arr as $value)
                                {
                                    if(stripos($value,'WIP')!==false)
                                    {
                                        $emp_wip_count +=1;

                                        $total_wip+=1;
                                    }
                                }
                            }

                            // Reference

                            $ref_wip_count = 0;

                            if(count($ref_status_arr)>0)
                            {
                                foreach($ref_status_arr as $value)
                                {
                                    if(stripos($value,'WIP')!==false)
                                    {
                                        $ref_wip_count +=1;

                                        $total_wip+=1;
                                    }
                                }
                            }

                            // Address

                            $addr_wip_count = 0;

                            if(count($addr_status_arr)>0)
                            {
                                foreach($addr_status_arr as $value)
                                {
                                if(stripos($value,'WIP')!==false)
                                {
                                    $addr_wip_count +=1;

                                    $total_wip+=1;
                                }
                                }
                            }

                            // PCC

                            $pcc_wip_count = 0;

                            if(count($pcc_status_arr)>0)
                            {
                                foreach($pcc_status_arr as $value)
                                {
                                if(stripos($value,'WIP')!==false)
                                {
                                    $pcc_wip_count +=1;

                                    $total_wip+=1;
                                }
                                }
                            }

                            // Law Firm

                            $law_wip_count = 0;

                            if(count($law_status_arr)>0)
                            {
                                foreach($law_status_arr as $value)
                                {
                                    if(stripos($value,'WIP')!==false)
                                    {
                                        $law_wip_count +=1;

                                        $total_wip+=1;
                                    }
                                }
                            }

                            // Criminal 

                            $cr_wip_count = 0;

                            if(count($cr_status_arr)>0)
                            {
                                foreach($cr_status_arr as $value)
                                {
                                if(stripos($value,'WIP')!==false)
                                {
                                    $cr_wip_count +=1;

                                    $total_wip+=1;
                                }
                                }
                            }

                            // Judicial 

                            $jud_wip_count = 0;

                            if(count($jud_status_arr)>0)
                            {
                                foreach($jud_status_arr as $value)
                                {
                                if(stripos($value,'WIP')!==false)
                                {
                                    $jud_wip_count +=1;

                                    $total_wip+=1;
                                }
                                }
                            }

                            // Identity

                            $id_wip_count = 0;

                            if(count($id_status_arr)>0)
                            {
                                foreach($id_status_arr as $value)
                                {
                                if(stripos($value,'WIP')!==false)
                                {
                                    $id_wip_count +=1;

                                    $total_wip+=1;
                                }
                                }
                            }

                            // Database 

                            $db_wip_count = 0;

                            if(count($db_status_arr)>0)
                            {
                                foreach($db_status_arr as $value)
                                {
                                if(stripos($value,'WIP')!==false)
                                {
                                    $db_wip_count +=1;

                                    $total_wip+=1;
                                }
                                }
                            }

                            // Drug Test

                            $dg_wip_count = 0;

                        @endphp

                        <td>{{strval($edu_wip_count)}}</td>
                        <td>{{strval($emp_wip_count)}}</td>
                        <td>{{strval($ref_wip_count)}}</td>
                        <td>{{strval($addr_wip_count)}}</td>
                        <td>{{strval($pcc_wip_count)}}</td>
                        <td>{{strval($law_wip_count)}}</td>
                        <td>{{strval($cr_wip_count)}}</td>
                        <td>{{strval($jud_wip_count)}}</td>
                        <td>{{strval($id_wip_count)}}</td>
                        <td>{{strval($db_wip_count)}}</td>
                        <td>{{strval($dg_wip_count)}}</td>
                        <td>{{strval($total_wip)}}</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>{{date('d-M-Y',strtotime($user->created_at))}}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>