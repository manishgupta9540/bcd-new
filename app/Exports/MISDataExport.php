<?php

namespace App\Exports;

use App\User;
use Illuminate\Queue\NullQueue;
use Maatwebsite\Excel\Concerns\FromQuery;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
// use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
// use Maatwebsite\Excel\Concerns\WithStartRow;

// use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MISDataExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithMapping, WithColumnWidths, WithPreCalculateFormulas, WithCalculatedFormulas
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection 
    */

 	protected $from_date;
    protected $to_date;
    protected $business_id;

    function __construct($from_date, $to_date, $business_id) {
            $this->from_date        = $from_date;
            $this->to_date          = $to_date;
            $this->business_id      = $business_id;
    }
    
    public function collection()
    {
        // $user=[]; 
        $query = DB::table('users as u')
                    ->select('u.*','ub.company_name')
                    ->join('user_businesses as ub','ub.business_id','=','u.business_id')
                    ->where(['u.user_type'=>'client','u.parent_id'=>$this->business_id])
                    ->whereNotIn('u.id',[$this->business_id]);                    

        // $query->orderBy('u.id','asc');

        $user = $query->get();
        
        // $user->put('22',['company_name'=>'Total :']);

        // dd($user);

        return $user;

    }

    //
    public function map($user): array
    {
        // $data = [];

        $services = DB::table('services')
                        ->whereNull('business_id')
                        ->where('status',1)
                        ->whereNotIn('type_name',['e_court'])
                        ->orderBy('sort_number','asc')
                        ->get();
        
        $new_arr=[$user->company_name];

        // Case Received

        $grand_total = 0;

        for($i=1;$i<=12;$i++)
        {
            $month = date('n',strtotime($this->from_date.'+'.$i.'month'));

            // dd($start_date);

            $year = date('Y',strtotime($this->from_date.'+'.$i.'month'));

            // dd($end_date);

            $case_data = DB::table('candidate_reinitiates as u')
                            ->where(['u.business_id'=>$user->id,'u.user_type'=>'candidate','u.is_deleted'=>0])
                            ->whereMonth('u.created_at','=',$month)
                            ->whereYear('u.created_at','=',$year)
                            ->get();

            $case_count = count($case_data);

            // dd($case_data);

            $new_arr[] = strval($case_count);

            $grand_total = $grand_total + $case_count;
        }

        $new_arr[]= strval($grand_total);

        // Case Closed

        // $in_tat = 0;
        // $out_tat = 0;

        $grand_total=0;
        for($i=1;$i<=12;$i++)
        {
            $month = date('n',strtotime($this->from_date.'+'.$i.'month'));

            $year = date('Y',strtotime($this->from_date.'+'.$i.'month'));

            $reports = DB::table('reports as r')
                            ->select('r.id','r.created_at','r.parent_id','u.created_at as candidate_creation_date','u.id as candidate_id','r.report_complete_created_at as completed_date','j.tat_type','j.days_type','j.id as job_item_id','r.status as report_status','j.incentive as case_incentive','j.incentive as case_penalty','j.client_tat')  
                            ->join('candidate_reinitiates as u','r.candidate_id','=','u.id')
                            ->join('job_items as j','j.candidate_id','=','u.id')
                            ->where(['r.business_id'=>$user->id,'r.status'=>'completed'])
                            ->whereMonth('r.report_complete_created_at','=',$month)
                            ->whereYear('r.report_complete_created_at','=',$year)
                            ->get();
            
            if(count($reports)>0)
            {
                $in_tat = 0;
                $out_tat = 0;
                foreach($reports as $report)
                {
                    $candidate_date = date('Y-m-d',strtotime($report->candidate_creation_date));
                    $completed_date = date('Y-m-d',strtotime($report->completed_date));

                    if(stripos($report->tat_type,'case')!==false)
                    {
                        $tat = $report->client_tat - 1;
                        $incentive_tat = $report->client_tat - 1;
                        $date_arr=[];
                        // $in = 0;
                        // $out = 0;

                        if(stripos($report->days_type,'working')!==false)
                        {
                            $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                        }
                        else if(stripos($report->days_type,'calender')!==false)
                        {
                            $holiday_master=DB::table('customer_holiday_masters')
                                            ->distinct('date')
                                            ->select('date')
                                            ->where(['business_id'=>$report->parent_id,'status'=>1])
                                            ->orderBy('date','asc')
                                            ->get();
                            if(count($holiday_master)>0)
                            {
                                $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$incentive_tat);
                            }
                            else
                            {
                                $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                            }
                        }

                        if(strtotime($completed_date) <= strtotime($date_arr['inc_tat_date']))
                        {
                            $in_tat = $in_tat + 1;
                        }
                        else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                        {
                            $out_tat = $out_tat + 1;
                        }

                    }
                    else if(stripos($report->tat_type,'check')!==false)
                    {
                        $date_arr=[];

                        $tat=DB::table('job_sla_items')
                                        ->select('tat')
                                        ->where(['job_item_id'=>$report->job_item_id])
                                        ->max('tat');
                        // dd($tat);
                        $incentive_tat=DB::table('job_sla_items')
                                    ->select('incentive_tat')
                                    ->where(['job_item_id'=>$report->job_item_id])
                                    ->max('incentive_tat');

                        // dd($incentive_tat);
                        
                                // $tat = $item->tat - 1;
                                // $incentive_tat = $item->incentive_tat - 1;

                                if(stripos($report->days_type,'working')!==false)
                                {
                                    $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                }
                                else if(stripos($report->days_type,'calender')!==false)
                                {
                                    $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$report->parent_id,'status'=>1])
                                                    ->orderBy('date','asc')
                                                    ->get();
                                    if(count($holiday_master)>0)
                                    {
                                        $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$incentive_tat);
                                    }
                                    else
                                    {
                                        $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                    }

                                }

                                if(strtotime($completed_date) <= strtotime($date_arr['inc_tat_date']))
                                {
                                    $in_tat = $in_tat + 1;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $out_tat = $out_tat + 1;
                                }

                            // if($out > 0)
                            // {
                            //     $out_tat = $out_tat + 1;
                            // }
                            // else
                            // {
                            //     $in_tat = $in_tat + 1;
                            // }
                    }
                    
                }

                array_push($new_arr,strval($in_tat),strval($out_tat));

                $grand_total = $grand_total + $in_tat + $out_tat;
            }
            else
            {

                array_push($new_arr,strval(0),strval(0));

                // $grand_total = $grand_total + 0 + 0;
            }
            
        }

        $new_arr[]=strval($grand_total); 

        // WIP
            $start_date = date('Y-m-01',strtotime($this->from_date.'+1 month'));

            $end_date = date('Y-m-d',strtotime($this->to_date));

         //Pending
            $WIP_count = 0;

            $WIP1_count = 0;

            $WIP2_count = 0;

            // dd($start_date);

            $WIP1 = DB::table('candidate_reinitiates as u')
                        ->join('job_items as j','j.candidate_id','=','u.id')
                        ->where(['j.business_id'=>$user->id])
                        ->whereDate('j.created_at','>=',$start_date)
                        ->whereDate('j.created_at','<=',$end_date)
                        ->whereNotIn('j.jaf_status',['filled'])
                        ->get();
            
            $WIP1_count = count($WIP1);

            $WIP2 = DB::table('candidate_reinitiates as u')
                        ->join('reports as r','r.candidate_id','=','u.id')
                        ->where(['r.business_id'=>$user->id])
                        ->whereDate('r.created_at','>=',$start_date)
                        ->whereDate('r.created_at','<=',$end_date)
                        ->whereNotIn('r.status',['completed'])
                        ->get();
            
            $WIP2_count = count($WIP2);

            $WIP_count = $WIP_count + $WIP1_count + $WIP2_count;

            $new_arr[]=strval($WIP_count); 

            // Insuff

                $WIP_insuff = DB::table('candidate_reinitiates as u')
                                ->join('reports as r','r.candidate_id','=','u.id')
                                ->join('job_items as j','j.candidate_id','=','u.id')
                                ->join('jaf_form_data as jf','jf.job_item_id','=','j.id')
                                ->join('verification_insufficiency as v','v.jaf_form_data_id','=','jf.id')
                                ->where(['r.business_id'=>$user->id,'j.jaf_status'=>'filled','jf.is_insufficiency'=>1,'v.status'=>'raised'])
                                ->whereDate('v.created_at','>=',$start_date)
                                ->whereDate('v.created_at','<=',$end_date)
                                ->whereNotIn('r.status',['completed'])
                                ->get();

                $WIP_insuff_count = count($WIP_insuff);

                // dd($WIP_insuff_count);

                $new_arr[]=strval($WIP_insuff_count); 

                //  Stop Check

                $WIP_stop_check = DB::table('candidate_reinitiates as u')
                                ->join('candidate_hold_statuses as c','u.id','=','c.candidate_id')
                                ->where(['c.business_id'=>$user->id,'u.user_type'=>'candidate'])
                                ->whereNull('c.hold_remove_by')
                                ->whereDate('c.created_at','>=',$start_date)
                                ->whereDate('c.created_at','<=',$end_date)
                                ->get();

                $WIP_stop_check_count = count($WIP_stop_check);

                $new_arr[] = strval($WIP_stop_check_count);
        
        // Insufficiency
            $total_insuff = 0;

            for($i=2;$i>=0;$i--)
            {
                $month = date('n',strtotime($this->to_date.'-'.$i.'month'));

                $year = date('Y',strtotime($this->to_date.'-'.$i.'month'));

                $insuff = DB::table('candidate_reinitiates as u')
                                ->join('reports as r','r.candidate_id','=','u.id')
                                ->join('job_items as j','j.candidate_id','=','u.id')
                                ->join('jaf_form_data as jf','jf.job_item_id','=','j.id')
                                ->join('verification_insufficiency as v','v.jaf_form_data_id','=','jf.id')
                                ->where(['r.business_id'=>$user->id,'j.jaf_status'=>'filled','jf.is_insufficiency'=>1,'v.status'=>'raised'])
                                ->whereMonth('v.created_at','=',$month)
                                ->whereYear('v.created_at','=',$year)
                                ->get();

                $insuff_count = count($insuff);

                $new_arr[]=strval($insuff_count); 

                // $total_insuff = $total_insuff + $insuff_count;

            }

            // $new_arr[]=strval($total_insuff); 

            $total_insuff = DB::table('candidate_reinitiates as u')
                            ->join('reports as r','r.candidate_id','=','u.id')
                            ->join('job_items as j','j.candidate_id','=','u.id')
                            ->join('jaf_form_data as jf','jf.job_item_id','=','j.id')
                            ->join('verification_insufficiency as v','v.jaf_form_data_id','=','jf.id')
                            ->where(['r.business_id'=>$user->id,'j.jaf_status'=>'filled','jf.is_insufficiency'=>1,'v.status'=>'raised'])
                            ->get();

            $total_insuff_count = count($total_insuff);
            $new_arr[]=strval($total_insuff_count); 



        // Pending Cases

            $total_pending = 0;

            for($i=2;$i>=0;$i--)
            {
                $pending_c_count = 0;

                $month = date('n',strtotime($this->to_date.'-'.$i.'month'));

                $year = date('Y',strtotime($this->to_date.'-'.$i.'month'));

                // dd($month);

                $pending_c_1 = DB::table('candidate_reinitiates as u')
                        ->join('job_items as j','j.candidate_id','=','u.id')
                        ->where(['j.business_id'=>$user->id])
                        ->whereMonth('j.created_at','=',$month)
                        ->whereYear('j.created_at','=',$year)
                        ->whereNotIn('j.jaf_status',['filled'])
                        ->get();
            
                    $pending_c_1_count = count($pending_c_1);

                    // dd($pending_c_1_count);

                    $pending_c_2 = DB::table('candidate_reinitiates as u')
                                ->join('reports as r','r.candidate_id','=','u.id')
                                ->where(['r.business_id'=>$user->id])
                                ->whereMonth('r.created_at','=',$month)
                                ->whereYear('r.created_at','=',$year)
                                ->whereNotIn('r.status',['completed'])
                                ->get();
                    
                    $pending_c_2_count = count($pending_c_2);

                    $pending_c_count = $pending_c_count + $pending_c_1_count + $pending_c_2_count;

                    $new_arr[]=strval($pending_c_count); 

                    // $total_pending = $total_pending + $pending_c_count;

                
            }

            $pending_c_1 = DB::table('candidate_reinitiates as u')
                        ->join('job_items as j','j.candidate_id','=','u.id')
                        ->where(['j.business_id'=>$user->id])
                        ->whereNotIn('j.jaf_status',['filled'])
                        ->get();

            $pending_c_1_count = count($pending_c_1);

            $pending_c_2 = DB::table('candidate_reinitiates as u')
                            ->join('reports as r','r.candidate_id','=','u.id')
                            ->where(['r.business_id'=>$user->id])
                            ->whereNotIn('r.status',['completed'])
                            ->get();

            $pending_c_2_count = count($pending_c_2);

            $total_pending = $total_pending + $pending_c_1_count + $pending_c_2_count;

            $new_arr[]=strval($total_pending); 

            // Pending Checks
            $in_total = 0;
            $out_total = 0;
            foreach($services as $service)
            {
                $in_tat = 0;
                $out_tat = 0;
                
                $pending_checks = DB::table('report_items as ri')
                            ->select('ri.report_id as report_id','r.created_at','r.parent_id','u.created_at as candidate_creation_date','u.id as candidate_id','j.tat_type','j.days_type','j.id as job_item_id','j.incentive as case_incentive','j.incentive as case_penalty','j.client_tat','ri.service_id')  
                            ->join('reports as r','r.id','=','ri.report_id')
                            ->join('job_items as j','j.candidate_id','=','r.candidate_id')
                            ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                            ->where(['r.business_id'=>$user->id,'ri.service_id'=>$service->id])
                            ->whereNotIn('r.status',['completed'])
                            ->get();
                // dd($pending_checks);
                if(count($pending_checks)>0)
                {
                    foreach($pending_checks as $check)
                    {
                        $candidate_date = date('Y-m-d',strtotime($check->candidate_creation_date));

                        $completed_date = date('Y-m-d');

                        if(stripos($check->tat_type,'case')!==false)
                        {
                            $date_arr=[];

                            $tat = $check->client_tat - 1;
                            $incentive_tat = $check->client_tat - 1;

                            if(stripos($check->days_type,'working')!==false)
                            {
                                $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                            }
                            else if(stripos($check->days_type,'calender')!==false)
                            {
                                $holiday_master=DB::table('customer_holiday_masters')
                                                ->distinct('date')
                                                ->select('date')
                                                ->where(['business_id'=>$check->parent_id,'status'=>1])
                                                ->orderBy('date','asc')
                                                ->get();
                                if(count($holiday_master)>0)
                                {
                                    $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$incentive_tat);
                                }
                                else
                                {
                                    $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                }
                            }

                            if(strtotime($completed_date) <= strtotime($date_arr['inc_tat_date']))
                            {
                                $in_tat = $in_tat + 1;
                            }
                            else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                            {
                                $out_tat = $out_tat + 1;
                            }

                        }
                        else if(stripos($check->tat_type,'check')!==false)
                        {
                            $job_sla_items=DB::table('job_sla_items')->where(['job_item_id'=>$check->job_item_id,'service_id'=>$check->service_id])->first();

                            if($job_sla_items!=NULL)
                            {
                                $date_arr=[];
                                $tat=$job_sla_items->tat - 1;
                                $incentive_tat = $job_sla_items->incentive_tat - 1;

                                if(stripos($check->days_type,'working')!==false)
                                {
                                    $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                }
                                else if(stripos($check->days_type,'calender')!==false)
                                {
                                    $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$check->parent_id,'status'=>1])
                                                    ->orderBy('date','asc')
                                                    ->get();
                                    if(count($holiday_master)>0)
                                    {
                                        $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$incentive_tat);
                                    }
                                    else
                                    {
                                        $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                    }

                                }

                                if(strtotime($completed_date) <= strtotime($date_arr['inc_tat_date']))
                                {
                                    $in_tat = $in_tat + 1;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $out_tat = $out_tat + 1;
                                }

                            }
                        }
                    }

                    array_push($new_arr,strval($in_tat),strval($out_tat));

                    $in_total = $in_total + $in_tat;

                    $out_total = $out_total + $out_tat;
                }
                else
                {
                    array_push($new_arr,strval(0),strval(0));
                }
            }

            array_push($new_arr,strval($in_total),strval($out_total));

            // Insuff Checks

            $in_total = 0;
            $out_total = 0;
            foreach($services as $service)
            {
                $in_tat = 0;
                $out_tat = 0;
                $insuff_checks = DB::table('report_items as ri')
                                    ->select('ri.report_id as report_id','r.created_at','r.parent_id','u.created_at as candidate_creation_date','u.id as candidate_id','j.tat_type','j.days_type','j.id as job_item_id','j.incentive as case_incentive','j.incentive as case_penalty','j.client_tat','ri.service_id')  
                                    ->join('reports as r','r.id','=','ri.report_id')
                                    ->join('job_items as j','j.candidate_id','=','r.candidate_id')
                                    ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                                    ->join('jaf_form_data as jf','jf.id','=','ri.jaf_id')
                                    ->join('verification_insufficiency as v','v.jaf_form_data_id','=','jf.id')
                                    ->where(['r.business_id'=>$user->id,'ri.service_id'=>$service->id,'v.status'=>'raised','jf.is_insufficiency'=>1])
                                    ->whereNotIn('r.status',['completed'])
                                    ->get();

                if(count($insuff_checks)>0)
                {
                    foreach($insuff_checks as $check)
                    {
                        $candidate_date = date('Y-m-d',strtotime($check->candidate_creation_date));

                        $completed_date = date('Y-m-d');

                        if(stripos($check->tat_type,'case')!==false)
                        {
                            $date_arr=[];

                            $tat = $check->client_tat - 1;
                            $incentive_tat = $check->client_tat - 1;

                            if(stripos($check->days_type,'working')!==false)
                            {
                                $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                            }
                            else if(stripos($check->days_type,'calender')!==false)
                            {
                                $holiday_master=DB::table('customer_holiday_masters')
                                                ->distinct('date')
                                                ->select('date')
                                                ->where(['business_id'=>$check->parent_id,'status'=>1])
                                                ->orderBy('date','asc')
                                                ->get();
                                if(count($holiday_master)>0)
                                {
                                    $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$incentive_tat);
                                }
                                else
                                {
                                    $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                }
                            }

                            if(strtotime($completed_date) <= strtotime($date_arr['inc_tat_date']))
                            {
                                $in_tat = $in_tat + 1;
                            }
                            else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                            {
                                $out_tat = $out_tat + 1;
                            }

                        }
                        else if(stripos($check->tat_type,'check')!==false)
                        {
                            $job_sla_items=DB::table('job_sla_items')->where(['job_item_id'=>$check->job_item_id,'service_id'=>$check->service_id])->first();

                            if($job_sla_items!=NULL)
                            {
                                $date_arr=[];
                                $tat=$job_sla_items->tat - 1;
                                $incentive_tat = $job_sla_items->incentive_tat - 1;

                                if(stripos($check->days_type,'working')!==false)
                                {
                                    $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                }
                                else if(stripos($check->days_type,'calender')!==false)
                                {
                                    $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$check->parent_id,'status'=>1])
                                                    ->orderBy('date','asc')
                                                    ->get();
                                    if(count($holiday_master)>0)
                                    {
                                        $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$incentive_tat);
                                    }
                                    else
                                    {
                                        $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                    }

                                }

                                if(strtotime($completed_date) <= strtotime($date_arr['inc_tat_date']))
                                {
                                    $in_tat = $in_tat + 1;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $out_tat = $out_tat + 1;
                                }

                            }
                        }
                    }

                    array_push($new_arr,strval($in_tat),strval($out_tat));

                    $in_total = $in_total + $in_tat;

                    $out_total = $out_total + $out_tat;
                }
                else
                {
                    array_push($new_arr,strval(0),strval(0));
                }

            }

            array_push($new_arr,strval($in_total),strval($out_total));

            // WIP Bucket
                $grand_total = 0;
                // 0-5 days

                $start_date =  date('Y-m-d',strtotime($this->to_date.' '.'-4 days'));

                $end_date = date('Y-m-d',strtotime($this->to_date));

                $WIP_count = 0;

                $WIP1_count = 0;

                $WIP2_count = 0;

                // dd($start_date);

                $WIP1 = DB::table('candidate_reinitiates as u')
                            ->join('job_items as j','j.candidate_id','=','u.id')
                            ->where(['j.business_id'=>$user->id])
                            ->whereDate('j.created_at','>=',$start_date)
                            ->whereDate('j.created_at','<=',$end_date)
                            ->whereNotIn('j.jaf_status',['filled'])
                            ->get();
                
                $WIP1_count = count($WIP1);

                $WIP2 = DB::table('candidate_reinitiates as u')
                            ->join('reports as r','r.candidate_id','=','u.id')
                            ->where(['r.business_id'=>$user->id])
                            ->whereDate('r.created_at','>=',$start_date)
                            ->whereDate('r.created_at','<=',$end_date)
                            ->whereNotIn('r.status',['completed'])
                            ->get();
                
                $WIP2_count = count($WIP2);

                $WIP_count = $WIP_count + $WIP1_count + $WIP2_count;

                $grand_total = $grand_total + $WIP_count;

                $new_arr[]=strval($WIP_count);

                // 6-10 days

                $start_date =  date('Y-m-d',strtotime($this->to_date.' '.'-9 days'));

                $end_date = date('Y-m-d',strtotime($this->to_date.' '.'-5 days'));

                $WIP_count = 0;

                $WIP1_count = 0;

                $WIP2_count = 0;

                // dd($start_date);

                $WIP1 = DB::table('candidate_reinitiates as u')
                            ->join('job_items as j','j.candidate_id','=','u.id')
                            ->where(['j.business_id'=>$user->id])
                            ->whereDate('j.created_at','>=',$start_date)
                            ->whereDate('j.created_at','<=',$end_date)
                            ->whereNotIn('j.jaf_status',['filled'])
                            ->get();
                
                $WIP1_count = count($WIP1);

                $WIP2 = DB::table('candidate_reinitiates as u')
                            ->join('reports as r','r.candidate_id','=','u.id')
                            ->where(['r.business_id'=>$user->id])
                            ->whereDate('r.created_at','>=',$start_date)
                            ->whereDate('r.created_at','<=',$end_date)
                            ->whereNotIn('r.status',['completed'])
                            ->get();
                
                $WIP2_count = count($WIP2);

                $WIP_count = $WIP_count + $WIP1_count + $WIP2_count;

                $grand_total = $grand_total + $WIP_count;

                $new_arr[]=strval($WIP_count);

                // 11 - 15 days

                $start_date =  date('Y-m-d',strtotime($this->to_date.' '.'-14 days'));

                $end_date = date('Y-m-d',strtotime($this->to_date.' '.'-10 days'));

                $WIP_count = 0;

                $WIP1_count = 0;

                $WIP2_count = 0;

                // dd($start_date);

                $WIP1 = DB::table('candidate_reinitiates as u')
                            ->join('job_items as j','j.candidate_id','=','u.id')
                            ->where(['j.business_id'=>$user->id])
                            ->whereDate('j.created_at','>=',$start_date)
                            ->whereDate('j.created_at','<=',$end_date)
                            ->whereNotIn('j.jaf_status',['filled'])
                            ->get();
                
                $WIP1_count = count($WIP1);

                $WIP2 = DB::table('candidate_reinitiates as u')
                            ->join('reports as r','r.candidate_id','=','u.id')
                            ->where(['r.business_id'=>$user->id])
                            ->whereDate('r.created_at','>=',$start_date)
                            ->whereDate('r.created_at','<=',$end_date)
                            ->whereNotIn('r.status',['completed'])
                            ->get();
                
                $WIP2_count = count($WIP2);

                $WIP_count = $WIP_count + $WIP1_count + $WIP2_count;

                $grand_total = $grand_total + $WIP_count;

                $new_arr[]=strval($WIP_count);

                // 15 - 30 days

                $start_date =  date('Y-m-d',strtotime($this->to_date.' '.'-29 days'));

                $end_date = date('Y-m-d',strtotime($this->to_date.' '.'-15 days'));

                $WIP_count = 0;

                $WIP1_count = 0;

                $WIP2_count = 0;

                // dd($start_date);

                $WIP1 = DB::table('candidate_reinitiates as u')
                            ->join('job_items as j','j.candidate_id','=','u.id')
                            ->where(['j.business_id'=>$user->id])
                            ->whereDate('j.created_at','>=',$start_date)
                            ->whereDate('j.created_at','<=',$end_date)
                            ->whereNotIn('j.jaf_status',['filled'])
                            ->get();
                
                $WIP1_count = count($WIP1);

                $WIP2 = DB::table('candidate_reinitiates as u')
                            ->join('reports as r','r.candidate_id','=','u.id')
                            ->where(['r.business_id'=>$user->id])
                            ->whereDate('r.created_at','>=',$start_date)
                            ->whereDate('r.created_at','<=',$end_date)
                            ->whereNotIn('r.status',['completed'])
                            ->get();
                
                $WIP2_count = count($WIP2);

                $WIP_count = $WIP_count + $WIP1_count + $WIP2_count;

                $grand_total = $grand_total + $WIP_count;

                $new_arr[]=strval($WIP_count);

                // 60 days & above

                // $start_date =  date('Y-m-d',strtotime($this->to_date.' '.'-29 days'));

                $end_date = date('Y-m-d',strtotime($this->to_date.' '.'-30 days'));

                $WIP_count = 0;

                $WIP1_count = 0;

                $WIP2_count = 0;

                // dd($start_date);

                $WIP1 = DB::table('candidate_reinitiates as u')
                            ->join('job_items as j','j.candidate_id','=','u.id')
                            ->where(['j.business_id'=>$user->id])
                            ->whereDate('j.created_at','<=',$end_date)
                            ->whereNotIn('j.jaf_status',['filled'])
                            ->get();
                
                $WIP1_count = count($WIP1);

                $WIP2 = DB::table('candidate_reinitiates as u')
                            ->join('reports as r','r.candidate_id','=','u.id')
                            ->where(['r.business_id'=>$user->id])
                            ->whereDate('r.created_at','<=',$end_date)
                            ->whereNotIn('r.status',['completed'])
                            ->get();
                
                $WIP2_count = count($WIP2);

                $WIP_count = $WIP_count + $WIP1_count + $WIP2_count;

                $grand_total = $grand_total + $WIP_count;

                $new_arr[]=strval($WIP_count);

                // Grand Total

                $new_arr[]=strval($grand_total);


            
        return $new_arr;

    }

    public function headings(): array
    {

        $services = DB::table('services')
                        ->whereNull('business_id')
                        ->where('status',1)
                        ->whereNotIn('type_name',['e_court'])
                        ->orderBy('sort_number','asc')
                        ->get();

        
        $service_count = count($services);

        // dd($service_count);
        // Row 0
        $columns = [
                        ['Client Name','Case Received',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Cases Closed',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'WIP',NULL,NULL,'Insufficiency',NULL,NULL,NULL,'Pending Cases',NULL,NULL,NULL,'Pending Checks'],
                   ];

        //Insuff Checks
        for($i=1;$i<=$service_count + 1;$i++)
        {
            if($i==$service_count + 1)
            {
                array_push($columns[0],NULL);
            }
            else
            {
                array_push($columns[0],NULL,NULL);
            }
        }

        array_push($columns[0],'Insuff Checks');

        // WIP Bucket

        for($i=1;$i<=$service_count + 1;$i++)
        {
            if($i==$service_count + 1)
            {
                array_push($columns[0],NULL);
            }
            else
            {
                array_push($columns[0],NULL,NULL);
            }
        }

        array_push($columns[0],'WIP Bucket');
        
        //For Row 1
        $case = [];

        //Case Received
        $case[]=NULL;
        for ($i=1;$i<=12;$i++)
        {
            $case[]= date('M y',strtotime($this->from_date.'+'.$i.'month'));
        }

        $case[]='Grand Total';

        //Case Closed
        for($i=1;$i<=12;$i++)
        {
           
            array_push($case,date('M y',strtotime($this->from_date.'+'.$i.'month')),NULL);
            
        }

        array_push($case,'Grand Total');

        // WIP

        array_push($case,'Pending','Insuff','Stop Check');

        // Insufficiency

        array_push($case,'2nd Previous Month','Previous Month','Current Month','Total Insuff');

        // Pending Cases

        array_push($case,'2nd Previous Month','Previous Month','Current Month','Total Pending');

        // Pending Checks

        foreach($services as $service)
        {
            array_push($case,$service->name,NULL);
        }

        array_push($case,'In Checks Total','Out Checks Total');

        // Insuff Checks

        foreach($services as $service)
        {
            array_push($case,$service->name,NULL);
        }

        array_push($case,'In Checks Total','Out Checks Total');

        // WIP Bucket

        array_push($case,'0-5 days','6-10 days','11-15 days','15-30 days','60 Days & above','Grand Total');

        $columns[]=$case;


        // For Row 2
        $columns[]=[NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL];

        for($i=1;$i<=12;$i++)
        {
            array_push($columns[2],'IN','OUT');
        }

        // Pending Checks

        array_push($columns[2],NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
        foreach($services as $service)
        {
            array_push($columns[2],'IN','OUT');
        }

        // Insuff Checks

        array_push($columns[2],NULL,NULL);
        foreach($services as $service)
        {
            array_push($columns[2],'IN','OUT');
        }

        return $columns;
        
    }


    public function registerEvents(): array
    {
       

        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:ZZ1'; // All headers
                // $subcellRange1 = 'A2:ZZ2';
                // $subcellRange2 = 'A3:ZZ3';

                $query = DB::table('users as u')
                ->select('u.*','ub.company_name')
                ->join('user_businesses as ub','ub.business_id','=','u.business_id')
                ->where(['u.user_type'=>'client','u.parent_id'=>$this->business_id])
                ->whereNotIn('u.id',[$this->business_id])
                ->get();

                $q_count = count($query);

                $event->sheet->getDelegate()->setMergeCells(
                    [
                        'A1:A3',

                        'B1:N1',
                        'B2:B3',
                        'C2:C3',
                        'D2:D3',
                        'E2:E3',
                        'F2:F3',
                        'G2:G3',
                        'H2:H3',
                        'I2:I3',
                        'J2:J3',
                        'K2:K3',
                        'L2:L3',
                        'M2:M3',
                        'N2:N3',
                        'B'.($q_count + 5).':'.'N'.($q_count + 5),

                        'O1:AM1',
                        'O2:P2',
                        'Q2:R2',
                        'S2:T2',
                        'U2:V2',
                        'W2:X2',
                        'Y2:Z2',
                        'AA2:AB2',
                        'AC2:AD2',
                        'AE2:AF2',
                        'AG2:AH2',
                        'AI2:AJ2',
                        'AK2:AL2',
                        'AM2:AM3',

                        'AN1:AP1',
                        'AN2:AN3',
                        'AO2:AO3',
                        'AP2:AP3',
                        'AQ1:AT1',
                        'AQ2:AQ3',
                        'AR2:AR3',
                        'AS2:AS3',
                        'AT2:AT3',

                        'AU1:AX1',
                        'AU2:AU3',
                        'AV2:AV3',
                        'AW2:AW3',
                        'AX2:AX3',

                        // Pending Check

                        'AY1:DB1',
                        'AY2:AZ2',
                        'BA2:BB2',
                        'BC2:BD2',
                        'BE2:BF2',
                        'BG2:BH2',
                        'BI2:BJ2',
                        'BK2:BL2',
                        'BM2:BN2',
                        'BO2:BP2',
                        'BQ2:BR2',
                        'BS2:BT2',
                        'BU2:BV2',
                        'BW2:BX2',
                        'BY2:BZ2',
                        'CA2:CB2',
                        'CC2:CD2',
                        'CE2:CF2',
                        'CG2:CH2',
                        'CI2:CJ2',
                        'CK2:CL2',
                        'CM2:CN2',
                        'CO2:CP2',
                        'CQ2:CR2',
                        'CS2:CT2',
                        'CU2:CV2',
                        'CW2:CX2',
                        'CY2:CZ2',
                        'DA2:DA3',
                        'DB2:DB3',

                        // Insuff Check

                        'DC1:FF1',
                        'DC2:DD2',
                        'DE2:DF2',
                        'DG2:DH2',
                        'DI2:DJ2',
                        'DK2:DL2',
                        'DM2:DN2',
                        'DO2:DP2',
                        'DQ2:DR2',
                        'DS2:DT2',
                        'DU2:DV2',
                        'DW2:DX2',
                        'DY2:DZ2',
                        'EA2:EB2',
                        'EC2:ED2',
                        'EE2:EF2',
                        'EG2:EH2',
                        'EI2:EJ2',
                        'EK2:EL2',
                        'EM2:EN2',
                        'EO2:EP2',
                        'EQ2:ER2',
                        'ES2:ET2',
                        'EU2:EV2',
                        'EW2:EX2',
                        'EY2:EZ2',
                        'FA2:FB2',
                        'FC2:FD2',
                        'FE2:FE3',
                        'FF2:FF3',

                        // WIP Bucket

                        'FG1:FL1',
                        'FG2:FG3',
                        'FH2:FH3',
                        'FI2:FI3',
                        'FJ2:FJ3',
                        'FK2:FK3',
                        'FL2:FL3'
                    ]
                )->freezePane('B4');

                    // Grand Total Row 

                    $event->sheet->setCellValue('A'.($q_count + 4),'Grand Total');

                    $event->sheet->getDelegate()->getStyle('A'.($q_count + 4))->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('A8E3F4');

                    $event->sheet->getDelegate()->getStyle('A'.($q_count + 4))->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                    $event->sheet->getDelegate()->getStyle('A'.($q_count + 4))->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                ->setVertical(Alignment::VERTICAL_CENTER);

                    $event->sheet->getDelegate()->getStyle('A'.($q_count + 4))->getFont()->setSize(12);
                    $event->sheet->getDelegate()->getStyle('A'.($q_count + 4))->getFont()->setBold(true);

                    // Percentage row

                    $event->sheet->setCellValue('A'.($q_count + 5),'Percentages');

                    $event->sheet->getDelegate()->getStyle('A'.($q_count + 5))
                                ->getFont()
                                ->getColor()
                                ->setRGB('FF0000');

                    $event->sheet->getDelegate()->getStyle('A'.($q_count + 5).':'.'FL'.($q_count + 5))->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('8FCAF9');

                    $event->sheet->getDelegate()->getStyle('A'.($q_count + 5))->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                    $event->sheet->getDelegate()->getStyle('A'.($q_count + 5))->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                ->setVertical(Alignment::VERTICAL_CENTER);

                    $event->sheet->getDelegate()->getStyle('A'.($q_count + 5))->getFont()->setSize(12);
                    $event->sheet->getDelegate()->getStyle('A'.($q_count + 5))->getFont()->setBold(true);

                
                // Client Name
                $event->sheet->getDelegate()->getStyle('A1:A3')->getFill()
                                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()
                                    ->setRGB('A8E3F4');
                
                // Case Received
                $event->sheet->getDelegate()->getStyle('B1:H1')->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB('F7C50B');

                $event->sheet->getDelegate()->getStyle('B1:N1')->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->getStyle('B2:N2')->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB('67B2DC');

                    // Range from B2 to N2
                
                    for($i=66;$i<=78;$i++)
                    {
                        $cell = chr($i)."2";
                        $event->sheet->getDelegate()->getStyle($cell)->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);

                        $event->sheet->getDelegate()->getStyle($cell)
                                            ->getAlignment()
                                            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                            ->setVertical(Alignment::VERTICAL_CENTER);

                        $event->sheet->getDelegate()->getStyle($cell)->getFont()->setSize(12);

                        $event->sheet->getDelegate()->getStyle($cell)->getFont()->setBold(true);


                        // Grand Total from B to N

                        $event->sheet->setCellValue(chr($i).($q_count + 4),'=SUM('.chr($i).'4:'.chr($i).($q_count + 3).')');

                            $event->sheet->getDelegate()->getStyle(chr($i).($q_count + 4))->getFill()
                                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()
                                    ->setARGB('67B2DC');

                            $event->sheet->getDelegate()->getStyle(chr($i).($q_count + 4))->applyFromArray([
                                'borders' => [
                                    'outline' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                        'color' => ['argb' => '00000000'],
                                    ],
                                ],
                            ]);

                        $event->sheet->getDelegate()->getStyle(chr($i).($q_count + 4))->getFont()->setSize(12);

                        $event->sheet->getDelegate()->getStyle(chr($i).($q_count + 4))->getFont()->setBold(true);

                    }
                    // Border Range from B to N 

                    $event->sheet->getDelegate()->getStyle('B'.($q_count + 5).':'.'N'.($q_count + 5))->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                //Cases Closed

                $event->sheet->getDelegate()->getStyle('O1:Z1')->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB('C67EFA');

                $event->sheet->getDelegate()->getStyle('O1:Z1')->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->getStyle('O2:AM2')->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB('78D658');

                $event->sheet->getDelegate()->getStyle('O3:AL3')->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('78D658');

                    //Range from O2 to AM2
                    $i=79;
                    $j=90;
                    $status=0;
                    while($i<=$j)
                    {
                        if($status==1)
                        {
                            $cell = 'A'.chr($i).'2';

                            $g_t_cell = 'A'.chr($i);
                        }
                        else
                        {
                            $cell = chr($i).'2';

                            $g_t_cell = chr($i);
                        }

                        $event->sheet->getDelegate()->getStyle($cell)->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);

                        $event->sheet->getDelegate()->getStyle($cell)
                                            ->getAlignment()
                                            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                            ->setVertical(Alignment::VERTICAL_CENTER);

                        $event->sheet->getDelegate()->getStyle($cell)->getFont()->setSize(12);

                        $event->sheet->getDelegate()->getStyle($cell)->getFont()->setBold(true);

                        // Grand Total from O to AM

                        $event->sheet->setCellValue($g_t_cell.($q_count + 4),'=SUM('.$g_t_cell.'4:'.$g_t_cell.($q_count + 3).')');

                        $event->sheet->getDelegate()->getStyle($g_t_cell.($q_count + 4))->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB('78D658');

                        $event->sheet->getDelegate()->getStyle($g_t_cell.($q_count + 4))->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);

                        $event->sheet->getDelegate()->getStyle($g_t_cell.($q_count + 4))->getFont()->setSize(12);

                        $event->sheet->getDelegate()->getStyle($g_t_cell.($q_count + 4))->getFont()->setBold(true);

                        // Percentage from O to AM

                           
                            $event->sheet->getDelegate()->getStyle($g_t_cell.($q_count + 5))->applyFromArray([
                                'borders' => [
                                    'outline' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                        'color' => ['argb' => '00000000'],
                                    ],
                                ],
                            ]);

                            $event->sheet->getDelegate()->getStyle($g_t_cell.($q_count + 5))
                                            ->getFont()
                                            ->getColor()
                                            ->setRGB('FF0000');

                            $event->sheet->getDelegate()->getStyle($g_t_cell.($q_count + 5))->getFont()->setSize(12);

                            $event->sheet->getDelegate()->getStyle($g_t_cell.($q_count + 5))->getFont()->setBold(true);


                        $i++;

                        if($cell=='Z2')
                        {
                            $i=65;
                            $j=77;
                            $status=1;
                        }

                    }

                    //Range from O3 to AL3
                    $i=79;
                    $j=90;
                    $status=0;
                    $tat_status = 'in';
                    while($i<=$j)
                    {
                        if($status==1)
                        {
                            $cell = 'A'.chr($i).'3';

                            $tat_cell = 'A'.chr($i);

                            $tat_cell1 = 'A'.chr($i-1);
                        }
                        else
                        {
                            $cell = chr($i).'3';

                            $tat_cell = chr($i);

                            $tat_cell1 = chr($i-1);
                        }

                        $event->sheet->getDelegate()->getStyle($cell)->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);

                        $event->sheet->getDelegate()->getStyle($cell)
                                            ->getAlignment()
                                            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                            ->setVertical(Alignment::VERTICAL_CENTER);

                        $event->sheet->getDelegate()->getStyle($cell)->getFont()->setSize(12);

                        $event->sheet->getDelegate()->getStyle($cell)->getFont()->setBold(true);

                        if($tat_status=='out')
                        {
                            $event->sheet->getDelegate()->getStyle($tat_cell.'4'.':'.$tat_cell.($q_count + 4))
                                            ->getFont()
                                            ->getColor()
                                            ->setRGB('FF0000');
                            
                            // Percentage Calculate from Out from O to AL

                            // dd($tat_cell1.($q_count + 4));

                            $cell_array = [];

                            for($k=4;$k<=($q_count + 3);$k++)
                            {
                                $cell_array[] = $event->sheet->getCell($tat_cell.$k)->getValue();
                            }

                            if(array_sum($cell_array)>0)
                            {
                                $event->sheet->setCellValue($tat_cell.($q_count + 5),'='.'ROUND('.$tat_cell.($q_count + 4).'/'.'('.$tat_cell1.($q_count + 4).'+'.$tat_cell.($q_count + 4).')'.'* 100'.',2)');
                            }
                            else
                            {
                                $event->sheet->setCellValue($tat_cell.($q_count + 5),'0');
                            }

                        }

                        $i++;

                        if($cell=='Z3')
                        {
                            $i=65;
                            $j=76;
                            $status=1;
                        }

                        if($tat_status=='in')
                        {
                            $tat_status = 'out';
                        }
                        else
                        {
                            $tat_status = 'in';
                        }

                    }

                

                // WIP

                $event->sheet->getDelegate()->getStyle('AN1:AP1')->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB('58ACD6');

                $event->sheet->getDelegate()->getStyle('AN1:AP1')->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->getStyle('AN2:AP2')->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB('8FCAF9');

                    //Range from AN2 to AP2
                for($i=78;$i<=80;$i++)
                {
                    $cell = 'A'.chr($i).'2';

                    $gt_cell = 'A'.chr($i);

                    $event->sheet->getDelegate()->getStyle($cell)->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                    $event->sheet->getDelegate()->getStyle($cell)
                                        ->getAlignment()
                                        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                        ->setVertical(Alignment::VERTICAL_CENTER);

                    $event->sheet->getDelegate()->getStyle($cell)->getFont()->setSize(12);

                    $event->sheet->getDelegate()->getStyle($cell)->getFont()->setBold(true);

                    // Grand Total from AN to AP

                    $event->sheet->setCellValue($gt_cell.($q_count + 4),'=SUM('.$gt_cell.'4:'.$gt_cell.($q_count + 3).')');

                    $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('A8E3F4');

                    $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                    $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFont()->setSize(12);

                    $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFont()->setBold(true);

                    // Percentage from AN to AP

                   $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                }

                // Insufficiency

                $event->sheet->getDelegate()->getStyle('AQ1:AT1')->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB('F7C50B');

                $event->sheet->getDelegate()->getStyle('AQ1:AT1')->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->getStyle('AQ2:AT2')->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB('E2FD7C');
                
                    // Range from AQ2 to AT2
                for($i=81;$i<=84;$i++)
                {
                    
                    $cell = 'A'.chr($i).'2';

                    $gt_cell = 'A'.chr($i);
                    
                    $event->sheet->getDelegate()->getStyle($cell)->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                    $event->sheet->getDelegate()->getStyle($cell)
                                        ->getAlignment()
                                        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                        ->setVertical(Alignment::VERTICAL_CENTER);

                    $event->sheet->getDelegate()->getStyle($cell)->getFont()->setSize(12);

                    $event->sheet->getDelegate()->getStyle($cell)->getFont()->setBold(true);

                    // Grand Total from AQ to AT

                    $event->sheet->setCellValue($gt_cell.($q_count + 4),'=SUM('.$gt_cell.'4:'.$gt_cell.($q_count + 3).')');

                    $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('E2FD7C');

                    $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                    $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFont()->setSize(12);

                    $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFont()->setBold(true);

                    // Percentage from AQ to AT

                   $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                }

                // Pending Cases
                    $event->sheet->getDelegate()->getStyle('AU1:AX1')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('F59A5F');

                    $event->sheet->getDelegate()->getStyle('AU1:AX1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                    $event->sheet->getDelegate()->getStyle('AU2:AX2')->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB('F5A7D2');
                    
                        // Range from AU2 to AX2
                    for($i=85;$i<=88;$i++)
                    {
                        $cell = 'A'.chr($i).'2';

                        $gt_cell = 'A'.chr($i);

                        $gt_cell1 = 'A'.chr($i+1);

                        $event->sheet->getDelegate()->getStyle($cell)->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);
    
                        $event->sheet->getDelegate()->getStyle($cell)
                                            ->getAlignment()
                                            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                            ->setVertical(Alignment::VERTICAL_CENTER);
    
                        $event->sheet->getDelegate()->getStyle($cell)->getFont()->setSize(12);
    
                        $event->sheet->getDelegate()->getStyle($cell)->getFont()->setBold(true);

                        // Grand Total from AU to AX

                        $event->sheet->setCellValue($gt_cell.($q_count + 4),'=SUM('.$gt_cell.'4:'.$gt_cell.($q_count + 3).')');

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB('F5A7D2');

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFont()->setSize(12);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFont()->setBold(true);

                        // Percentage from AU to AX

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);

                        if($gt_cell=='AW')
                        {
                            $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))
                                            ->getFont()
                                            ->getColor()
                                            ->setRGB('FF0000');

                            $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))->getFont()->setSize(12);

                            $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))->getFont()->setBold(true);
                    

                            $cell_array = [];

                            for($k=4;$k<=($q_count + 3);$k++)
                            {
                                $cell_array[] = $event->sheet->getCell($gt_cell.$k)->getValue();
                            }

                            if(array_sum($cell_array)>0)
                            {
                                $event->sheet->setCellValue($gt_cell.($q_count + 5),'='.'ROUND('.'('.$gt_cell.($q_count + 4).'/'.$gt_cell1.($q_count + 4).')'.'*100'.',2)');
                            }
                            else
                            {
                                $event->sheet->setCellValue($gt_cell.($q_count + 5),'0');
                            }
                            
                        }

                    }

                    // Pending Checks

                    $event->sheet->getDelegate()->getStyle('AY1:CZ1')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('F59A5F');

                    $event->sheet->getDelegate()->getStyle('AY1:CZ1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                    $event->sheet->getDelegate()->getStyle('AY2:CZ2')->getFill()
                                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()
                                    ->setRGB('F3C185');

                    // Range from AY2 to CZ2 & AY3 to CZ3
                    $i=89;
                    $j=90;
                    $status=0;
                    $color_code = 'F3C185';
                    $tat_status = 'in';
                    while($i<=$j)
                    {
                        if($status==2)
                        {
                            $cell1 = 'C'.chr($i).'2';

                            $cell2 = 'C'.chr($i).'3';

                            $gt_cell = 'C'.chr($i);

                            $gt_cell1 = 'C'.chr($i-1);
                        }
                        else if($status==1)
                        {
                            $cell1 = 'B'.chr($i).'2';

                            $cell2 = 'B'.chr($i).'3';

                            $gt_cell = 'B'.chr($i);

                            $gt_cell1 = 'B'.chr($i-1);
                            
                        }
                        else
                        {
                            $cell1 = 'A'.chr($i).'2';

                            $cell2 = 'A'.chr($i).'3';

                            $gt_cell = 'A'.chr($i);

                            $gt_cell1 = 'A'.chr($i-1);
                        }

                        $event->sheet->getDelegate()->getStyle($cell1)->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);
    
                        $event->sheet->getDelegate()->getStyle($cell1)
                                            ->getAlignment()
                                            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                            ->setVertical(Alignment::VERTICAL_CENTER);
    
                        $event->sheet->getDelegate()->getStyle($cell1)->getFont()->setSize(12);
    
                        $event->sheet->getDelegate()->getStyle($cell1)->getFont()->setBold(true);


                        $event->sheet->getDelegate()->getStyle($cell2)->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);

                        $event->sheet->getDelegate()->getStyle($cell2)->getFill()
                                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()
                                    ->setRGB($color_code);
    
                        $event->sheet->getDelegate()->getStyle($cell2)
                                            ->getAlignment()
                                            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                            ->setVertical(Alignment::VERTICAL_CENTER);
    
                        $event->sheet->getDelegate()->getStyle($cell2)->getFont()->setSize(12);
    
                        $event->sheet->getDelegate()->getStyle($cell2)->getFont()->setBold(true);

                        // Grand Total from AY to CZ

                        $event->sheet->setCellValue($gt_cell.($q_count + 4),'=SUM('.$gt_cell.'4:'.$gt_cell.($q_count + 3).')');

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB($color_code);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFont()->setSize(12);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFont()->setBold(true);

                        // Percentage from AY to CZ

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))->getFont()->setSize(12);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))->getFont()->setBold(true);

                        if($tat_status=='out')
                        {
                            $event->sheet->getDelegate()->getStyle($gt_cell.'4'.':'.$gt_cell.($q_count + 5))
                                            ->getFont()
                                            ->getColor()
                                            ->setRGB('FF0000');
                            
                            // Percentage Calculate from Out from AY to CZ

                            // dd($gt_cell1.($q_count + 4));

                            $cell_array = [];

                            for($k=4;$k<=($q_count + 3);$k++)
                            {
                                $cell_array[] = $event->sheet->getCell($gt_cell.$k)->getValue();
                            }

                            if(array_sum($cell_array)>0)
                            {
                                $event->sheet->setCellValue($gt_cell.($q_count + 5),'='.'ROUND('.$gt_cell.($q_count + 4).'/'.'('.$gt_cell1.($q_count + 4).'+'.$gt_cell.($q_count + 4).')'.'* 100'.',2)');
                            }
                            else
                            {
                                $event->sheet->setCellValue($gt_cell.($q_count + 5),'0');
                            }

                        }


                        $i++;

                        if($cell1=='AZ2')
                        {
                            $i=65;
                            $j=90;
                            $status=1;
                        }
                        else if($cell1=='BZ2')
                        {
                            $i=65;
                            $j=90;
                            $status=2;
                        }

                        if($color_code=='F3C185')
                        {
                            $color_code = '78D658';
                        }
                        else if($color_code=='78D658')
                        {
                            $color_code = 'F3C185';
                        }

                        if($tat_status=='in')
                        {
                            $tat_status='out';
                        }
                        else
                        {
                            $tat_status='in';
                        }

                    }

                    $event->sheet->getDelegate()->getStyle('DA2:DB2')->getFill()
                                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()
                                    ->setRGB('F5A7D2');

                        // Range from DA2 to DB2
                        $color_code = 'F3C185';
                    for($i=65;$i<=66;$i++)
                    {
                        $cell = 'D'.chr($i).'2';

                        $gt_cell = 'D'.chr($i);

                        $gt_cell1 = 'D'.chr($i-1);

                        $event->sheet->getDelegate()->getStyle($cell)->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);
    
                        $event->sheet->getDelegate()->getStyle($cell)
                                            ->getAlignment()
                                            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                            ->setVertical(Alignment::VERTICAL_CENTER);

                        // Grand Total from DA to DB

                        $event->sheet->setCellValue($gt_cell.($q_count + 4),'=SUM('.$gt_cell.'4:'.$gt_cell.($q_count + 3).')');

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB($color_code);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFont()->setSize(12);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFont()->setBold(true);

                        // Percentage from DA to DB

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))->getFont()->setSize(12);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))->getFont()->setBold(true);

                        if($cell=='DB2')
                        {
                            $event->sheet->getDelegate()->getStyle($cell)
                                            ->getFont()
                                            ->getColor()
                                            ->setRGB('FF0000');
                            
                            $event->sheet->getDelegate()->getStyle($gt_cell.'4'.':'.$gt_cell.($q_count + 5))
                                        ->getFont()
                                        ->getColor()
                                        ->setRGB('FF0000');
                            
                            // Percentage Calculate for Out on CH

                            $cell_array = [];

                            for($k=4;$k<=($q_count + 3);$k++)
                            {
                                $cell_array[] = $event->sheet->getCell($gt_cell.$k)->getValue();
                            }

                            if(array_sum($cell_array)>0)
                            {
                                $event->sheet->setCellValue($gt_cell.($q_count + 5),'='.'ROUND('.$gt_cell.($q_count + 4).'/'.'('.$gt_cell1.($q_count + 4).'+'.$gt_cell.($q_count + 4).')'.'* 100'.',2)');
                            }
                            else
                            {
                                $event->sheet->setCellValue($gt_cell.($q_count + 5),'0');
                            }

                            if($color_code=='F3C185')
                            {
                                $color_code = '78D658';
                            }
                            else if($color_code=='78D658')
                            {
                                $color_code = 'F3C185';
                            }
                            
                        }
    
                        $event->sheet->getDelegate()->getStyle($cell)->getFont()->setSize(12);
    
                        $event->sheet->getDelegate()->getStyle($cell)->getFont()->setBold(true);


                    }

                    // Insuff Checks

                    $event->sheet->getDelegate()->getStyle('DC1:FF1')->getFill()
                                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()
                                    ->setRGB('F5A7D2');
                    
                    $event->sheet->getDelegate()->getStyle('DC1:FF1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                    $event->sheet->getDelegate()->getStyle('DC2:FD2')->getFill()
                                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()
                                    ->setRGB('5CCDEE');
                    
                     // Range from DC2 to FD2 & DC3 to FD3
                     $i=67;
                     $j=90;
                     $status=0;
                     $color_code = 'F3C185';
                     $tat_status = 'in';
                     while($i<=$j)
                     {
                         if($status==1)
                         {
                             $cell1 = 'E'.chr($i).'2';
 
                             $cell2 = 'E'.chr($i).'3';

                             $gt_cell = 'E'.chr($i);

                             $gt_cell1 = 'E'.chr($i-1);
                         }
                         else if($status==2)
                         {
                             $cell1 = 'F'.chr($i).'2';
 
                             $cell2 = 'F'.chr($i).'3';

                             $gt_cell = 'F'.chr($i);

                             $gt_cell1 = 'F'.chr($i-1);
                         }
                         else
                         {
                             $cell1 = 'D'.chr($i).'2';
 
                             $cell2 = 'D'.chr($i).'3';

                             $gt_cell = 'D'.chr($i);

                             $gt_cell1 = 'D'.chr($i-1);
                         }

 
                         $event->sheet->getDelegate()->getStyle($cell1)->applyFromArray([
                             'borders' => [
                                 'outline' => [
                                     'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                     'color' => ['argb' => '00000000'],
                                 ],
                             ],
                         ]);
     
                         $event->sheet->getDelegate()->getStyle($cell1)
                                             ->getAlignment()
                                             ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                             ->setVertical(Alignment::VERTICAL_CENTER);
     
                         $event->sheet->getDelegate()->getStyle($cell1)->getFont()->setSize(12);
     
                         $event->sheet->getDelegate()->getStyle($cell1)->getFont()->setBold(true);
 
 
                         $event->sheet->getDelegate()->getStyle($cell2)->applyFromArray([
                             'borders' => [
                                 'outline' => [
                                     'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                     'color' => ['argb' => '00000000'],
                                 ],
                             ],
                         ]);
 
                         $event->sheet->getDelegate()->getStyle($cell2)->getFill()
                                     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                     ->getStartColor()
                                     ->setRGB($color_code);
     
                         $event->sheet->getDelegate()->getStyle($cell2)
                                             ->getAlignment()
                                             ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                             ->setVertical(Alignment::VERTICAL_CENTER);
     
                         $event->sheet->getDelegate()->getStyle($cell2)->getFont()->setSize(12);
     
                         $event->sheet->getDelegate()->getStyle($cell2)->getFont()->setBold(true);

                          // Grand Total from DC to FD

                        $event->sheet->setCellValue($gt_cell.($q_count + 4),'=SUM('.$gt_cell.'4:'.$gt_cell.($q_count + 3).')');

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB($color_code);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFont()->setSize(12);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFont()->setBold(true);

                        // Percentage from DC to FD

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))->getFont()->setSize(12);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))->getFont()->setBold(true);

                        if($tat_status=='out')
                        {
                            $event->sheet->getDelegate()->getStyle($gt_cell.'4'.':'.$gt_cell.($q_count + 5))
                                            ->getFont()
                                            ->getColor()
                                            ->setRGB('FF0000');
                            
                            // Percentage Calculate for Out from DC to FD

                            $cell_array = [];

                            for($k=4;$k<=($q_count + 3);$k++)
                            {
                                $cell_array[] = $event->sheet->getCell($gt_cell.$k)->getValue();
                            }

                            if(array_sum($cell_array)>0)
                            {
                                $event->sheet->setCellValue($gt_cell.($q_count + 5),'='.'ROUND('.$gt_cell.($q_count + 4).'/'.'('.$gt_cell1.($q_count + 4).'+'.$gt_cell.($q_count + 4).')'.'* 100'.',2)');
                            }
                            else
                            {
                                $event->sheet->setCellValue($gt_cell.($q_count + 5),'0');
                            }

                        }

 
                         $i++;
 
                         if($cell1=='DZ2')
                         {
                            $i=65;
                            $j=90;
                            $status=1;
                         }
                         else if($cell1=='EZ2')
                         {
                            $i=65;
                            $j=68;
                            $status = 2;
                         }
 
                         if($color_code=='F3C185')
                         {
                             $color_code = '78D658';
                         }
                         else if($color_code=='78D658')
                         {
                             $color_code = 'F3C185';
                         }

                         if($tat_status=='in')
                         {
                            $tat_status='out';
                         }
                         else
                         {
                            $tat_status='in';
                         }
 
                     }

                     $event->sheet->getDelegate()->getStyle('FE2:FF2')->getFill()
                                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()
                                    ->setRGB('F5A7D2');

                        // Range from FE2 to FF2
                        $color_code = 'F3C185';
                    for($i=69;$i<=70;$i++)
                    {
                        $cell = 'F'.chr($i).'2';

                        $gt_cell = 'F'.chr($i);

                        $gt_cell1 = 'F'.chr($i-1);

                        $event->sheet->getDelegate()->getStyle($cell)->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);
    
                        $event->sheet->getDelegate()->getStyle($cell)
                                            ->getAlignment()
                                            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                            ->setVertical(Alignment::VERTICAL_CENTER);

                         // Grand Total from FE to FF

                         $event->sheet->setCellValue($gt_cell.($q_count + 4),'=SUM('.$gt_cell.'4:'.$gt_cell.($q_count + 3).')');

                         $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFill()
                                 ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                 ->getStartColor()
                                 ->setRGB($color_code);
 
                         $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->applyFromArray([
                             'borders' => [
                                 'outline' => [
                                     'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                     'color' => ['argb' => '00000000'],
                                 ],
                             ],
                         ]);
 
                         $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFont()->setSize(12);
 
                         $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFont()->setBold(true);
 
                         // Percentage from FE to FF
 
                         $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))->applyFromArray([
                             'borders' => [
                                 'outline' => [
                                     'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                     'color' => ['argb' => '00000000'],
                                 ],
                             ],
                         ]);
 
                         $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))->getFont()->setSize(12);
 
                         $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))->getFont()->setBold(true);
 
                         

                        if($cell=='FF2')
                        {
                            $event->sheet->getDelegate()->getStyle($cell)
                                             ->getFont()
                                             ->getColor()
                                             ->setRGB('FF0000');
                             
                             $event->sheet->getDelegate()->getStyle($gt_cell.'4'.':'.$gt_cell.($q_count + 5))
                                         ->getFont()
                                         ->getColor()
                                         ->setRGB('FF0000');
                             
                             // Percentage Calculate for Out on FF
 
                             $cell_array = [];
 
                             for($k=4;$k<=($q_count + 3);$k++)
                             {
                                 $cell_array[] = $event->sheet->getCell($gt_cell.$k)->getValue();
                             }
 
                             if(array_sum($cell_array)>0)
                             {
                                 $event->sheet->setCellValue($gt_cell.($q_count + 5),'='.'ROUND('.$gt_cell.($q_count + 4).'/'.'('.$gt_cell1.($q_count + 4).'+'.$gt_cell.($q_count + 4).')'.'* 100'.',2)');
                             }
                             else
                             {
                                 $event->sheet->setCellValue($gt_cell.($q_count + 5),'0');
                             }
                        }
    
                        $event->sheet->getDelegate()->getStyle($cell)->getFont()->setSize(12);
    
                        $event->sheet->getDelegate()->getStyle($cell)->getFont()->setBold(true);



                        if($color_code=='F3C185')
                        {
                            $color_code = '78D658';
                        }
                        else if($color_code=='78D658')
                        {
                            $color_code = 'F3C185';
                        }

                    }

                    // WIP Bucket

                    $event->sheet->getDelegate()->getStyle('FG1:FL1')->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB('58ACD6');

                    $event->sheet->getDelegate()->getStyle('FG1:FL1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                    $event->sheet->getDelegate()->getStyle('FG2:FL2')->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB('A8E3F4');
                    
                        // Range from FG2 to FL2
                    $i=71;
                    $j=76;
                    $status=0;
                    // $k=5;
                    while($i<=$j)
                    {

                        // if($status==1)
                        // {
                        //     $cell1 = 'E'.chr($i).'2';

                        //     $gt_cell = 'E'.chr($i);

                        //     $gt_cell1 = 'E'.chr($j);
                        // }
                        // else
                        // {
                            $cell1 = 'F'.chr($i).'2';

                            $gt_cell = 'F'.chr($i);
    
                            $gt_cell1 = 'F'.chr($j);
                        // }

                        $event->sheet->getDelegate()->getStyle($cell1)->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);
    
                        $event->sheet->getDelegate()->getStyle($cell1)
                                            ->getAlignment()
                                            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                            ->setVertical(Alignment::VERTICAL_CENTER);
    
                        $event->sheet->getDelegate()->getStyle($cell1)->getFont()->setSize(12);
    
                        $event->sheet->getDelegate()->getStyle($cell1)->getFont()->setBold(true);

                        // Grand Total from FG to FL

                        $event->sheet->setCellValue($gt_cell.($q_count + 4),'=SUM('.$gt_cell.'4:'.$gt_cell.($q_count + 3).')');

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB('A8E3F4');

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFont()->setSize(12);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 4))->getFont()->setBold(true);

                       // Percentage from FG to FL

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))->getFont()->setSize(12);

                        $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))->getFont()->setBold(true);

                        if($gt_cell!='FL')
                        {
                            $event->sheet->getDelegate()->getStyle($gt_cell.($q_count + 5))
                                         ->getFont()
                                         ->getColor()
                                         ->setRGB('FF0000');
                             
                             //Percentage Calculate from DW to EA
                            
                            //  if($gt_cell=='EA')
                            //  {

                            //  }
                            //  else
                            //  {
                                 
                            //  }
                             $cell_array = [];
 
                             for($k=4;$k<=($q_count + 3);$k++)
                             {
                                 $cell_array[] = $event->sheet->getCell($gt_cell.$k)->getValue();
                             }
 
                             if(array_sum($cell_array)>0)
                             {
                                 $event->sheet->setCellValue($gt_cell.($q_count + 5),'='.'ROUND('.$gt_cell.($q_count + 4).'/'.'('.$gt_cell1.($q_count + 4).')'.'* 100'.',2)');

                                 //$event->sheet->setCellValue($gt_cell.($q_count + 5),'='.'ROUND('.$gt_cell.($q_count + 4).'/'.'('.'FH'.($q_count + 4).')'.'* 100'.',2)');
                             }
                             else
                             {
                                 $event->sheet->setCellValue($gt_cell.($q_count + 5),'0');
                             }
                        }

                        $i++;

                        // if($gt_cell=='DZ')
                        // {
                        //     $i=65;
                        //     $j=66;
                        //     $status=1;
                        // }


                        // $k--;

                    }
                    
                

                $headers = $event->sheet->getDelegate()->getStyle($cellRange);

                $headers->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                        ->setVertical(Alignment::VERTICAL_CENTER);
                $headers->getFont()->setSize(12);
                $headers->getFont()->setBold(true);

                
            },
        ];

        
    }

    public function columnWidths(): array
    {
        $cell_size = [];
        $i=79;
        $j=90;
        $status=0;
        while($i<=$j)
        {
            if($status==1)
            {
                $cell= 'A'.chr($i);
            }
            else
            {
                $cell= chr($i);
            }
            
            $cell_size= $this->array_push_assoc($cell_size,$cell,5);

            $i++;

            if($cell=='Z')
            {
                $i=65;
                $j=76;
                $status=1;
            }
        }
        // $cell_size['X']=5;

        $i=89;
        $j=90;
        $status=0;
        while($i<=$j)
        { 
            if($status==2)
            {
                $cell= 'C'.chr($i);
            }  
            else if($status==1)
            {
                $cell= 'B'.chr($i);
            }
            else
            {
                $cell= 'A'.chr($i);
            }
            
            $cell_size= $this->array_push_assoc($cell_size,$cell,12);
            $i++;
            if($cell=='AZ')
            {
                $i=65;
                $j=90;
                $status=1;
            }
            else if($cell=='BZ')
            {
                // dd($cell);
                $i=65;
                $j=90;
                $status=2;
            }
        }

        // $cell_size['BM']=20;
        // $cell_size['BN']=20;

        $i=65;
        $j=90;
        $status=0;

        while($i<=$j)
        {
            if($status==1)
            {
                $cell= 'E'.chr($i);
            }
            elseif($status==2)
            {
                $cell= 'F'.chr($i);
            }
            else
            {
                $cell= 'D'.chr($i);
            }
            
            $cell_size= $this->array_push_assoc($cell_size,$cell,12);
            $i++;

            if($cell=='DZ')
            {
                // dd($cell);
                $i=65;
                $j=90;
                $status=1;
            }
            else if($cell=='EZ')
            {
                // dd($cell);
                $i=65;
                $j=76;
                $status=2;
            }
        }

        $cell_size['DA']=20;
        $cell_size['DB']=20;

        $cell_size['FE']=20;
        $cell_size['FF']=20;

        $cell_size['FJ']=20;
        $cell_size['FK']=20;
        $cell_size['FL']=20;

        return $cell_size;
    }

   

    // public function startRow(): int
    // {
    //     return 8;
    // }

    // public function headingRow(): int
    // {
    //     return 4;
    // }

    public function array_push_assoc($array, $key, $value)
    {
        $array[$key] = $value;
        return $array;
    }

    public function workingDays($start_date,$tatwDays,$inc_tatwDays)
    {
        // using + weekdays excludes weekends
        $arr=[];
        $tat_new_date = date('Y-m-d', strtotime("{$start_date} +{$tatwDays} weekdays"));

        $inc_tat_new_date = date('Y-m-d', strtotime("{$start_date} +{$inc_tatwDays} weekdays"));

        $arr=['tat_date'=>$tat_new_date,'inc_tat_date'=>$inc_tat_new_date];

        return $arr;
        
    }

    public function calenderDays($start_date,$holidays,$tatwDays,$inc_tatwDays)
    {
        // using + weekdays excludes weekends
        // $arr=[];
        $tat_new_date = date('Y-m-d', strtotime("{$start_date} +{$tatwDays} weekdays"));

        $inc_tat_new_date = date('Y-m-d', strtotime("{$start_date} +{$inc_tatwDays} weekdays"));

        foreach($holidays as $holiday)
        {
        
            $holiday_ts = strtotime($holiday->date);

            // if holiday falls between start date and new date, then account for it
            if ($holiday_ts >= strtotime($start_date) && $holiday_ts <= strtotime($tat_new_date)) {

                // check if the holiday falls on a working day
                $h = date('w', $holiday_ts);
                    if ($h != 0 && $h != 6 ) {
                    // holiday falls on a working day, add an extra working day
                    $tat_new_date = date('Y-m-d', strtotime("{$tat_new_date} + 1 weekdays"));
                }
            }

            // if holiday falls between start date and new date, then account for it
            if ($holiday_ts >= strtotime($start_date) && $holiday_ts <= strtotime($inc_tat_new_date)) {

                // check if the holiday falls on a working day
                $h = date('w', $holiday_ts);
                    if ($h != 0 && $h != 6 ) {
                    // holiday falls on a working day, add an extra working day
                    $inc_tat_new_date = date('Y-m-d', strtotime("{$inc_tat_new_date} + 1 weekdays"));
                }
            }
        }

        return array('tat_date'=>$tat_new_date,'inc_tat_date'=>$inc_tat_new_date);
    }
}
