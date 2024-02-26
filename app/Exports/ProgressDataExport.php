<?php

namespace App\Exports;

use App\User;
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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

use App\Helpers\Helper;

class ProgressDataExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithMapping, WithColumnFormatting
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */

 	protected $from_date;
    protected $to_date;
    protected $business_id;

    function __construct($type,$month,$year,$report_type,$business_id,$customer_id) {
        $this->type = $type;
        $this->month = $month;
        $this->year = $year;
        $this->report_type = $report_type;
        $this->business_id   = $business_id;
        $this->customer_id   = $customer_id;
    }

    public function collection()
    {
        // $user=[]; 

        $query = DB::table('candidate_reinitiates as u')
                    ->DISTINCT('ri.candidate_id')
                    ->select('u.*','ub.company_name','j.sla_title as sla_name','ub.department','ub.client_spokeman','j.tat','j.client_tat','j.tat_type','j.days_type','r.status as report_status','r.report_complete_created_at as case_completed_date','j.price_type','j.package_price','r.is_report_complete')
                    ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->join('reports as r','r.candidate_id','=','u.id')
                    ->join('report_items as ri','r.id','=','ri.report_id')
                    ->join('services as s','s.id','=','ri.service_id')
                    ->whereIn('s.type_name',['address','employment','educational','database','judicial','reference','identity_verification','criminal'])
                    ->where(['u.parent_id'=>$this->business_id,'u.user_type'=>'candidate','u.is_deleted'=>0,'j.jaf_status'=>'filled']);

                    if($this->customer_id!=NULL && count($this->customer_id)>0)
                    {
                        $query->whereIn('u.business_id',$this->customer_id);
                    }

                    if($this->report_type!=null && count($this->report_type)>0)
                    {
                        if(count($this->report_type) < 2)
                        {
                            if(stripos($this->report_type[0],'wip')!==false)
                            {
                                $query->whereIN('r.status',['interim','incomplete']);
                            }
                            else if(stripos($this->report_type[0],'close')!==false){

                                $query->whereIN('r.status',['completed']);
                            }
                        }
                    }
                    
                    if($this->type != '')
                    {
                        if(stripos($this->type,'daily')!==false)
                        {
                            $query=$query->whereDate('u.created_at',date('Y-m-d'));
                        }
                        else if(stripos($this->type,'weekly')!==false)
                        {
                            $query=$query->whereDate('u.created_at','>=',date('Y-m-d',strtotime('- 6 days')))->whereDate('u.created_at','<=',date('Y-m-d'));
                        }
                        else if(stripos($this->type,'monthly')!==false)
                        {
                            if($this->month!=null && count($this->month)>0)
                            {
                                $query=$query->whereIn(DB::raw('month(u.created_at)'),$this->month)->whereYear('u.created_at','=',$this->year);
                            }
                            else
                            {
                                $query=$query->whereIn(DB::raw('month(u.created_at)'),[date('m')])->whereYear('u.created_at','=',date('Y'));
                            }
                        }
                    }
                    else
                    {
                        if($this->month!=null && count($this->month)>0)
                        {

                            $query=$query->whereIn(DB::raw('month(u.created_at)'),$this->month)->whereYear('u.created_at','=',$this->year);
                        }
                        else
                        {
                            $query=$query->whereIn(DB::raw('month(u.created_at)'),[date('m')])->whereYear('u.created_at','=',date('Y'));
                        }
                    }

                   
                // $query->orderBy('u.id','desc');

        $user = $query->groupBy('ri.candidate_id')->get();  
        
        // dd($user);

        return $user;

    }

    public function map($user): array
    {
        $edu_count = 5;
        $emp_count = 5;
        $ref_count = 5;
        $addr_count = 7;
        $pcc_count = 3;
        $law_count = 3;
        $jud_count = 3;
        $cr_count = 5;
        $id_count = 2;
        $db_count = 2;

        $candidate_date = date('Y-m-d',strtotime($user->created_at));
        
        $data = [
                    $user->company_name,
                    $user->name,
                    $user->sla_name!=NULL ? $user->sla_name : 'N/A',
                    $user->department!=NULL ? $user->department : 'N/A',
                    $user->client_emp_code!=NULL ? $user->client_emp_code : 'N/A',

                ];
        
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
        array_push($data,$spokeman);

        // Ref no, Date Of Receiving, Month of Receiving

        array_push($data,
                        $user->display_id!=NULL ? $user->display_id : 'N/A',
                        $user->case_received_date!=NULL ? date('d-M-y',strtotime($user->case_received_date)) : 'N/A',
                        $user->case_received_date!=NULL ? date('M-Y',strtotime($user->case_received_date)) : 'N/A',
        );

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

        array_push($data,$re_initiate);

        // Actual Case Date Received

        array_push($data,$user->case_received_date!=NULL ? date('d-M-y',strtotime($user->case_received_date)) : 'N/A');

        // TAT & Client TAT

        array_push($data,strval($user->tat),strval($user->client_tat));

        // TAT Type & Days Type

        array_push($data,ucfirst($user->tat_type).' - Wise',ucfirst($user->days_type).' - Days');

        // Internal & Client Due Date

        $date_arr = [];
        $tat = $user->tat - 1;
        $client_tat = $user->client_tat - 1;
        $tat_date = 'N/A';
        $client_tat_date = 'N/A';

        if(stripos($user->days_type,'working')!==false)
        {
            $date_arr = $this->workingDays($candidate_date,$tat,$client_tat);

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
                $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$client_tat);
            }
            else
            {
                $date_arr = $this->workingDays($candidate_date,$tat,$client_tat);
            }

            $tat_date = $date_arr['tat_date'];

            $client_tat_date = $date_arr['inc_tat_date'];
        }

        array_push($data,$tat_date,$client_tat_date);

        // Date of Submission

        array_push($data,$user->is_report_complete==1 && $user->case_completed_date!=NULL ? date('d-M-y',strtotime($user->case_completed_date)) : 'N/A');

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

        array_push($data,$stop_date,'N/A');

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

        array_push($data,$status,date('d-M-y'));

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

        array_push($data,$first_raise_date,$first_clear_date,$first_clear_reason,$second_raise_date,$second_clear_date,$second_clear_reason);

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


        array_push($data,$case_status,$case_status_with_tat);

        $status_arr = [];

        $final_amt_arr = [];

        // Education Check

        array_push($data,'EDUCATION');

        $edu_report_items = DB::table('report_items as ri')
                        ->select('ri.*')
                        ->join('services as s','s.id','=','ri.service_id')
                        ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'educational'])
                        ->orderBy('ri.service_item_number','asc')
                        ->get();

        $edu_status = 'N/A';

        $edu_status_arr = [];

        $edu_amt = 0.00;

        $edu_university = 'N/A';

        if(count($edu_report_items) > 0)
        {
            $remain = 0;

            $j = 0;

            foreach($edu_report_items as $item)
            {
                $edu_status = 'WIP';

                $edu_amt = 0.00;
        
                $edu_university = 'N/A';

                if(stripos($user->report_status,'completed')!==false)
                {
                    $edu_status = 'Complete';
                }

                if($item->is_supplementary==1)
                {
                    $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$item->service_id,'number_of_verifications'=>$item->service_item_number,'is_supplementary'=>'1'])->first();

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

                        $edu_amt = $this->round_up($user->package_price/$count,2);
                    }
                    else if(stripos($user->price_type,'check')!==false)
                    {
                        $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$item->service_id,'is_supplementary'=>'0'])->first();

                        if($job_sla_items!=NULL)
                            $edu_amt = $job_sla_items->price;
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

                            if(stripos($key_val[0],'University Name / Board Name')!==false){ 
                                $edu_university = $input_val[0];
                            }
                        }
                    }
                }

                $status_arr[]=$edu_status;

                $final_amt_arr[]=$edu_amt;

                $edu_status_arr[]= $edu_status;

                array_push($data,$edu_status,strval($edu_amt),$edu_university!='' ? $edu_university : 'N/A');

                $j++;
                
            }

            $remain = $edu_count - $j;

            if($remain > 0)
            {
                $edu_status = 'N/A';

                $edu_amt = 0.00;
        
                $edu_university = 'N/A';

                for($i=1;$i<=$remain;$i++)
                {
                    $status_arr[]=$edu_status;
                
                    $final_amt_arr[]=$edu_amt;

                    array_push($data,$edu_status,strval($edu_amt),$edu_university);
                }
            }
        }
        else
        {
            for($i=1;$i<=$edu_count;$i++)
            {
                $status_arr[]=$edu_status;
                $final_amt_arr[]=$edu_amt;

                array_push($data,$edu_status,strval($edu_amt),$edu_university);
            }
        }

        // Employment Check
        array_push($data,'EMPLOYMENT');

        $emp_report_items = DB::table('report_items as ri')
                        ->select('ri.*')
                        ->join('services as s','s.id','=','ri.service_id')
                        ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'employment'])
                        ->orderBy('ri.service_item_number','asc')
                        ->get();

        $emp_status = 'N/A';

        $emp_status_arr=[];

        $emp_amt = 0.00;

        $emp_name = 'N/A';

        if(count($emp_report_items) > 0)
        {
            $remain = 0;

            $j = 0;

            foreach($emp_report_items as $item)
            {
                $emp_status = 'WIP';

                $emp_amt = 0.00;
        
                $emp_name = 'N/A';

                if(stripos($user->report_status,'completed')!==false)
                {
                    $emp_status = 'Complete';
                }

                if($item->is_supplementary==1)
                {
                    $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$item->service_id,'number_of_verifications'=>$item->service_item_number,'is_supplementary'=>'1'])->first();

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

                        $emp_amt = $this->round_up($user->package_price/$count,2);
                    }
                    else if(stripos($user->price_type,'check')!==false)
                    {
                        $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$item->service_id,'is_supplementary'=>'0'])->first();

                        if($job_sla_items!=NULL)
                            $emp_amt = $job_sla_items->price;
                    }
                }

                if($item->jaf_data!=NULL)
                {
                    $input_item_data_array=[];
                    $input_item_data_array = json_decode($item->jaf_data,true);

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

                $emp_status_arr[]= $emp_status;

                array_push($data,$emp_status,strval($emp_amt),$emp_name!='' ? $emp_name : $user->name);

                $j++;
                
            }

            $remain = $emp_count - $j;

            if($remain > 0)
            {
                $emp_status = 'N/A';

                $emp_amt = 0.00;
        
                $emp_name = 'N/A';

                for($i=1;$i<=$remain;$i++)
                {
                    $status_arr[]=$emp_status;
                
                    $final_amt_arr[]=$emp_amt;

                    array_push($data,$emp_status,strval($emp_amt),$emp_name);
                }
            }
        }
        else
        {
            for($i=1;$i<=$emp_count;$i++)
            {
                $status_arr[]=$emp_status;
                
                $final_amt_arr[]=$emp_amt;

                array_push($data,$emp_status,strval($emp_amt),$emp_name);
            }
        }

        // Reference Check

        array_push($data,'REFERENCE');

        $ref_report_items = DB::table('report_items as ri')
                        ->select('ri.*')
                        ->join('services as s','s.id','=','ri.service_id')
                        ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'reference'])
                        ->orderBy('ri.service_item_number','asc')
                        ->get();

        $ref_status = 'N/A';

        $ref_amt = 0.00;

        $ref_status_arr=[];

        $ref_name = 'N/A';

        $ref_contact = 'N/A';

        if(count($ref_report_items) > 0)
        {
            $remain = 0;

            $j = 0;

            foreach($ref_report_items as $item)
            {
                $ref_status = 'WIP';

                $ref_amt = 0.00;
        
                $ref_name = 'N/A';

                $ref_contact = 'N/A';

                if(stripos($user->report_status,'completed')!==false)
                {
                    $ref_status = 'Complete';
                }

                if($item->is_supplementary==1)
                {
                    $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$item->service_id,'number_of_verifications'=>$item->service_item_number,'is_supplementary'=>'1'])->first();

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

                        $ref_amt = $this->round_up($user->package_price/$count,2);
                    }
                    else if(stripos($user->price_type,'check')!==false)
                    {
                        $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$item->service_id,'is_supplementary'=>'0'])->first();

                        if($job_sla_items!=NULL)
                            $ref_amt = $job_sla_items->price;
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

                array_push($data,$ref_status,strval($ref_amt),$ref_name!='' ? $ref_name : 'N/A',$ref_contact!='' ? $ref_contact : 'N/A');

                $j++;
                
            }

            $remain = $ref_count - $j;

            if($remain > 0)
            {
                $ref_status = 'N/A';

                $ref_amt = 0.00;
        
                $ref_name = 'N/A';

                $ref_contact = 'N/A';

                for($i=1;$i<=$remain;$i++)
                {
                    $status_arr[]=$ref_status;
                
                    $final_amt_arr[]=$ref_amt;

                    array_push($data,$ref_status,strval($ref_amt),$ref_name,$ref_contact);
                }
            }
        }
        else
        {
            for($i=1;$i<=$ref_count;$i++)
            {
                $status_arr[]=$ref_status;
                
                $final_amt_arr[]=$ref_amt;

                array_push($data,$ref_status,strval($ref_amt),$ref_name,$ref_contact);
            }
        }

        // Address Check

        array_push($data,'ADDRESS');

        $addr_report_items = DB::table('report_items as ri')
                        ->select('ri.*')
                        ->join('services as s','s.id','=','ri.service_id')
                        ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'address'])
                        ->orderBy('ri.service_item_number','asc')
                        ->get();

        $addr_status = 'N/A';

        $addr_status_arr=[];

        $addr_amt = 0.00;

        $addr_detail = 'N/A';

        $addr_city = 'N/A';

        if(count($addr_report_items) > 0)
        {
            $remain = 0;

            $j = 0;

            foreach($addr_report_items as $item)
            {
                $addr_status = 'WIP';

                $addr_amt = 0.00;
        
                $addr_detail = 'N/A';

                $addr_city = 'N/A';

                if(stripos($user->report_status,'completed')!==false)
                {
                    $addr_status = 'Complete';
                }

                if($item->is_supplementary==1)
                {
                    $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$item->service_id,'number_of_verifications'=>$item->service_item_number,'is_supplementary'=>'1'])->first();

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

                        $addr_amt = $this->round_up($user->package_price/$count,2);
                    }
                    else if(stripos($user->price_type,'check')!==false)
                    {
                        $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$item->service_id,'is_supplementary'=>'0'])->first();

                        if($job_sla_items!=NULL)
                            $addr_amt = $job_sla_items->price;
                    }
                }

                if($item->jaf_data!=NULL)
                {
                    $input_item_data_array=[];
                    $input_item_data_array = json_decode($item->jaf_data,true);

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

                array_push($data,$addr_status,strval($addr_amt),str_replace(' ','',$addr_detail)!='' ? $addr_detail : 'N/A',$addr_city!='' ? $addr_city : 'N/A');

                $j++;
                
            }

            $remain = $addr_count - $j;

            if($remain > 0)
            {
                $addr_status = 'N/A';

                $addr_amt = 0.00;
        
                $addr_detail = 'N/A';

                $addr_city = 'N/A';

                for($i=1;$i<=$remain;$i++)
                {
                    $status_arr[]=$addr_status;
                
                    $final_amt_arr[]=$addr_amt;

                    array_push($data,$addr_status,strval($addr_amt),$addr_detail,$addr_city);
                }
            }
        }
        else
        {
            for($i=1;$i<=$addr_count;$i++)
            {
                $status_arr[]=$addr_status;
                
                $final_amt_arr[]=$addr_amt;

                array_push($data,$addr_status,strval($addr_amt),$addr_detail,$addr_city);
            }
        }

        // PCC Check

        array_push($data,'PCC');

        $pcc_status = 'N/A';

        $pcc_status_arr=[];

        $pcc_amt = 0.00;

        $pcc_detail = 'N/A';

        for($i=1;$i<=$pcc_count;$i++)
        {
            $status_arr[]=$pcc_status;
                
            $final_amt_arr[]=$pcc_amt;

            array_push($data,$pcc_status,strval($pcc_amt),$pcc_detail);
        }

        // Law Firm Check

        array_push($data,'LAW FIRM');

        $law_status = 'N/A';

        $law_status_arr=[];

        $law_amt = 0.00;

        $law_detail = 'N/A';

        for($i=1;$i<=$law_count;$i++)
        {
            $status_arr[]=$law_status;
                
            $final_amt_arr[]=$law_amt;

            array_push($data,$law_status,strval($law_amt),$law_detail);
        }

        // Criminal Check

        array_push($data,'CRIMINAL');

        $cr_report_items = DB::table('report_items as ri')
                        ->select('ri.*')
                        ->join('services as s','s.id','=','ri.service_id')
                        ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'criminal'])
                        ->orderBy('ri.service_item_number','asc')
                        ->get();

        $cr_status = 'N/A';

        $cr_status_arr=[];

        $cr_amt = 0.00;

        $cr_detail = 'N/A';

        if(count($cr_report_items) > 0)
        {
            $remain = 0;

            $j = 0;

            foreach($cr_report_items as $item)
            {
                $cr_status = 'WIP';

                $cr_amt = 0.00;
        
                $cr_detail = 'N/A';

                if(stripos($user->report_status,'completed')!==false)
                {
                    $cr_status = 'Complete';
                }

                if($item->is_supplementary==1)
                {
                    $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$item->service_id,'number_of_verifications'=>$item->service_item_number,'is_supplementary'=>'1'])->first();

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

                        $cr_amt = $this->round_up($user->package_price/$count,2);
                    }
                    else if(stripos($user->price_type,'check')!==false)
                    {
                        $job_sla_items = DB::table('job_sla_items')->where(['candidate_id'=>$user->id,'service_id'=>$item->service_id,'is_supplementary'=>'0'])->first();

                        if($job_sla_items!=NULL)
                            $cr_amt = $job_sla_items->price;
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
                                $cr_detail = $input_val[0];
                            }
                        }
                    }
                }

                $status_arr[]=$cr_status;
                
                $final_amt_arr[]=$cr_amt;

                $cr_status_arr[] = $cr_status;

                array_push($data,$cr_status,strval($cr_amt),$cr_detail!='' ? $cr_detail : 'N/A');

                $j++;
                
            }

            $remain = $cr_count - $j;

            if($remain > 0)
            {
                $cr_status = 'N/A';

                $cr_amt = 0.00;
        
                $cr_detail = 'N/A';

                for($i=1;$i<=$remain;$i++)
                {
                    $status_arr[]=$cr_status;
                
                    $final_amt_arr[]=$cr_amt;

                    array_push($data,$cr_status,strval($cr_amt),$cr_detail);
                }
            }
        }
        else
        {
            for($i=1;$i<=$cr_count;$i++)
            {
                $status_arr[]=$cr_status;
                
                $final_amt_arr[]=$cr_amt;

                array_push($data,$cr_status,strval($cr_amt),$cr_detail);
            }
        }

        // Judicial Check

        array_push($data,'JUDIS');

        $jud_report_items = DB::table('report_items as ri')
                        ->select('ri.*')
                        ->join('services as s','s.id','=','ri.service_id')
                        ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'judicial'])
                        ->orderBy('ri.service_item_number','asc')
                        ->get();

        $jud_status = 'N/A';

        $jud_status_arr=[];

        $jud_amt = 0.00;

        $jud_detail = 'N/A';

        if(count($jud_report_items) > 0)
        {
            $remain = 0;

            $j = 0;

            foreach($jud_report_items as $item)
            {
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

                array_push($data,$jud_status,strval($jud_amt),$jud_detail!='' ? $jud_detail : 'N/A');

                $j++;
                
            }

            $remain = $jud_count - $j;

            if($remain > 0)
            {
                $jud_status = 'N/A';

                $jud_amt = 0.00;
        
                $jud_detail = 'N/A';

                for($i=1;$i<=$remain;$i++)
                {
                    $status_arr[]=$jud_status;
                
                    $final_amt_arr[]=$jud_amt;

                    array_push($data,$jud_status,strval($jud_amt),$jud_detail);
                }
            }
        }
        else
        {
            for($i=1;$i<=$jud_count;$i++)
            {
                $status_arr[]=$jud_status;
                
                $final_amt_arr[]=$jud_amt;

                array_push($data,$jud_status,strval($jud_amt),$jud_detail);
            }
        }

        // Identity Check

        array_push($data,'IDENTITY');

        $id_report_items = DB::table('report_items as ri')
                        ->select('ri.*')
                        ->join('services as s','s.id','=','ri.service_id')
                        ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'identity_verification'])
                        ->orderBy('ri.service_item_number','asc')
                        ->get();

        $id_status = 'N/A';

        $id_status_arr = [];

        $id_amt = 0.00;

        if(count($id_report_items) > 0)
        {
            $remain = 0;

            $j = 0;

            foreach($id_report_items as $item)
            {
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

                array_push($data,$id_status,strval($id_amt));

                $j++;
                
            }

            $remain = $id_count - $j;

            if($remain > 0)
            {
                $id_status = 'N/A';

                $id_amt = 0.00;

                for($i=1;$i<=$remain;$i++)
                {
                    $status_arr[]=$id_status;
                
                    $final_amt_arr[]=$id_amt;

                    array_push($data,$id_status,strval($id_amt));
                }
            }
        }
        else
        {
            for($i=1;$i<=$id_count;$i++)
            {
                $status_arr[]=$id_status;
                
                $final_amt_arr[]=$id_amt;

                array_push($data,$id_status,strval($id_amt));
            }
        }

        // Database Check

        array_push($data,'DATABASE');

        $db_report_items = DB::table('report_items as ri')
                        ->select('ri.*')
                        ->join('services as s','s.id','=','ri.service_id')
                        ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'database'])
                        ->orderBy('ri.service_item_number','asc')
                        ->get();

        $db_status = 'N/A';

        $db_status_arr=[];

        $db_amt = 0.00;

        if(count($db_report_items) > 0)
        {
            $remain = 0;

            $j = 0;

            foreach($db_report_items as $item)
            {
                $db_status = 'WIP';

                $db_amt = 0.00;

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

                        $db_amt = $this->round_up($user->package_price/$count,2);
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

                array_push($data,$db_status,strval($db_amt));

                $j++;
                
            }

            $remain = $db_count - $j;

            if($remain > 0)
            {
                $db_status = 'N/A';

                $db_amt = 0.00;

                for($i=1;$i<=$remain;$i++)
                {
                    $status_arr[]=$db_status;
                
                    $final_amt_arr[]=$db_amt;

                    array_push($data,$db_status,strval($db_amt));
                }
            }
        }
        else
        {
            for($i=1;$i<=$db_count;$i++)
            {
                $status_arr[]=$db_status;
                
                $final_amt_arr[]=$db_amt;

                array_push($data,$db_status,strval($db_amt));
            }
        }

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
        
        array_push($data,strval($total_c),strval($final_amt));

        // Insuff Check Count

         // Educational 

         $edu_insuff_count = DB::table('jaf_form_data as j')
                                ->join('services as s','s.id','=','j.service_id')
                                ->where(['j.candidate_id'=>$user->id,'s.type_name'=>'educational','j.is_insufficiency'=>'1'])
                                ->get();

         $edu_insuff_count = count($edu_insuff_count);

         // Employment
         $emp_insuff_count = DB::table('jaf_form_data as j')
         ->join('services as s','s.id','=','j.service_id')
         ->where(['j.candidate_id'=>$user->id,'s.type_name'=>'employment','j.is_insufficiency'=>'1'])
         ->get();

         $emp_insuff_count = count($emp_insuff_count);


         // Reference

         $ref_insuff_count = DB::table('jaf_form_data as j')
         ->join('services as s','s.id','=','j.service_id')
         ->where(['j.candidate_id'=>$user->id,'s.type_name'=>'reference','j.is_insufficiency'=>'1'])
         ->get();

         $ref_insuff_count = count($ref_insuff_count);

         // Address

         $addr_insuff_count = DB::table('jaf_form_data as j')
         ->join('services as s','s.id','=','j.service_id')
         ->where(['j.candidate_id'=>$user->id,'s.type_name'=>'address','j.is_insufficiency'=>'1'])
         ->get();

         $addr_insuff_count = count($addr_insuff_count);

         // PCC Check

         $pcc_insuff_count = 0;

         // Law Firm Check

         $law_insuff_count = 0;

         // Criminal Check

         $cr_insuff_count = DB::table('jaf_form_data as j')
         ->join('services as s','s.id','=','j.service_id')
         ->where(['j.candidate_id'=>$user->id,'s.type_name'=>'criminal','j.is_insufficiency'=>'1'])
         ->get();

         $cr_insuff_count = count($cr_insuff_count);

         // Judicial Check

         $jud_insuff_count = DB::table('jaf_form_data as j')
         ->join('services as s','s.id','=','j.service_id')
         ->where(['j.candidate_id'=>$user->id,'s.type_name'=>'judicial','j.is_insufficiency'=>'1'])
         ->get();

         $jud_insuff_count = count($jud_insuff_count);

         // Identity Check

         $id_insuff_count = DB::table('jaf_form_data as j')
         ->join('services as s','s.id','=','j.service_id')
         ->where(['j.candidate_id'=>$user->id,'s.type_name'=>'identity_verification','j.is_insufficiency'=>'1'])
         ->get();

         $id_insuff_count = count($id_insuff_count);

         // Database Check

         $db_insuff_count = DB::table('jaf_form_data as j')
         ->join('services as s','s.id','=','j.service_id')
         ->where(['j.candidate_id'=>$user->id,'s.type_name'=>'database','j.is_insufficiency'=>'1'])
         ->get();

         $db_insuff_count = count($db_insuff_count);

         array_push($data,
                        strval($edu_insuff_count),
                        strval($emp_insuff_count),
                        strval($ref_insuff_count),
                        strval($addr_insuff_count),
                        strval($pcc_insuff_count),
                        strval($law_insuff_count),
                        strval($cr_insuff_count),
                        strval($jud_insuff_count),
                        strval($id_insuff_count),
                        strval($db_insuff_count),
                    );

        // Total Insufficiency Pending

        $total_insuff = 0;

        $total_insuff = $edu_insuff_count + $emp_insuff_count + $ref_insuff_count + $addr_insuff_count + $pcc_insuff_count + $law_insuff_count + $cr_insuff_count + $jud_insuff_count + $id_insuff_count + $db_insuff_count;

        array_push($data,strval($total_insuff));

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

         array_push($data,
                        strval($edu_wip_count),
                        strval($emp_wip_count),
                        strval($ref_wip_count),
                        strval($addr_wip_count),
                        strval($pcc_wip_count),
                        strval($law_wip_count),
                        strval($cr_wip_count),
                        strval($jud_wip_count),
                        strval($id_wip_count),
                        strval($db_wip_count),
                        strval($dg_wip_count),
                        strval($total_wip),

        );

        //

        array_push($data,NULL,NULL,NULL,NULL,date('d-M-Y',strtotime($user->created_at))); 



        return $data;

    }

    public function headings(): array
    {
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
        $columns = [
                    'Client',
                    'Candidate',
                    'Entity',
                    'Department',
                    'EmployeeCode',
                    'Client Spokeman',	
                    'ReferenceNo.',	
                    'Date Of Receiving',
                    'Month of Receiving',
                    'Re- initiate',	
                    'Actual Date Case Received',
                    'TAT',
                    'Actual Tat',
                    'Case Wise or Check Wise',
                    'Calendar Days or Working Days',
                    'Internal Due Date',
                    'Client Due Date',
                    'Date of Submission',
                    'Stop Date',
                    'Current Aging OF Case',
                    'Status',
                    'Today',
                    '1st Level Insufficiency Raised on',
                    '1st Level Insufficiency Cleared on',
                    'Reason',
                    '2nd Level Insufficiency Raised on',
                    '2nd Level Insufficiency Cleared on',	
                    'Reason',
                    'Case Status',
                    'Case Status with TAT',
                    'EDUCATION',
                    
        ];

        // Education Check

        for($i=1;$i<=$edu_count;$i++)
        {
            array_push($columns,'EducationCheck - '.$i,'Amt','University');
        }

        // Employment Check

        array_push($columns,'EMPLOYMENT');

        for($i=1;$i<=$emp_count;$i++)
        {
            array_push($columns,'EmploymentCheck - '.$i,'Amt','Employer Name');
        }

        // Reference Check

        array_push($columns,'REFERENCE');

        for($i=1;$i<=$ref_count;$i++)
        {
            array_push($columns,'ReferenceCheck - '.$i,'Amt','Reference Name','Reference Contact-No');
        }

        // Address Check

        array_push($columns,'ADDRESS');

        for($i=1;$i<=$addr_count;$i++)
        {
            array_push($columns,'AddressCheck - '.$i,'Amt','Details','City');
        }

        // PCC / Police Check

        array_push($columns,'PCC / Police Record Check');

        for($i=1;$i<=$pcc_count;$i++)
        {
            array_push($columns,'PCC '.$i.' / Police Record Check','Amt','Details');
        }

        // Law Firm Check

        array_push($columns,'LAW FIRM');

        for($i=1;$i<=$law_count;$i++)
        {
            array_push($columns,'Law Firm - '.$i,'Amt','Details');
        }

        // Criminal Check

        array_push($columns,'CRIMINAL');

        for($i=1;$i<=$cr_count;$i++)
        {
            array_push($columns,'Criminal - '.$i,'Amt','Details');
        }

        // Judicial Check

        array_push($columns,'JUDIS');

        for($i=1;$i<=$jud_count;$i++)
        {
            array_push($columns,'Judis - '.$i,'Amt','Details');
        }

        // Identify Check

        array_push($columns,'IDENTITY');

        for($i=1;$i<=$id_count;$i++)
        {
            array_push($columns,'IdentityCheck - '.$i,'Amt');
        }

        // Database Check

        array_push($columns,'DATABASE');

        for($i=1;$i<=$db_count;$i++)
        {
            array_push($columns,'DatabaseCheck - '.$i,'Amt');
        }

        //

        array_push($columns,'Total Components',
                            'Final Amount',
                            'Education Insuff',
                            'Employment Insuff',
                            'Ref Insuff',
                            'Add Insuff',
                            'PCC/CRI Insuff',
                            'Law Firm Insuff',
                            'Criminal Insuff',
                            'Judis Insuff',
                            'Id Insuff',
                            'Database Insuff',
                            'Total Insuff Pending',
                            'Education Wip',
                            'Employment Wip',
                            'Ref Wip',
                            'Add Wip',
                            'PCC/CRI Wip',
                            'Law Firm Wip',
                            'Criminal Wip',
                            'Judis Wip',
                            'Id Wip',
                            'Database Wip',
                            'DrugTest Wip',
                            'Total Wip',
                            'mxa',
                            'With Insuff Report',
                            'Report Not Received',
                            "Additional Remark's",
                            'DOP'
                        );
        


        return $columns;
        
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:ZZ1'; // All headers

                $query = DB::table('candidate_reinitiates as u')
                ->DISTINCT('ri.candidate_id')
                ->select('u.*','ub.company_name','j.sla_title as sla_name','ub.department','ub.client_spokeman','j.tat','j.client_tat','j.tat_type','j.days_type','r.status as report_status','r.complete_created_at as case_completed_date','j.price_type','j.package_price')
                ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                ->join('job_items as j','j.candidate_id','=','u.id')
                ->join('reports as r','r.candidate_id','=','u.id')
                ->join('report_items as ri','r.id','=','ri.report_id')
                ->join('services as s','s.id','=','ri.service_id')
                ->whereIn('s.type_name',['address','employment','educational','database','judicial','reference','identity_verification','criminal'])
                ->where(['u.parent_id'=>$this->business_id,'u.user_type'=>'candidate','u.is_deleted'=>0,'j.jaf_status'=>'filled']);

                if($this->customer_id!=NULL && count($this->customer_id)>0)
                {
                    $query->whereIn('u.business_id',$this->customer_id);
                }

                if($this->report_type!=null && count($this->report_type)>0)
                {
                    if(count($this->report_type) < 2)
                    {
                        if(stripos($this->report_type[0],'wip')!==false)
                        {
                            $query->whereIN('r.status',['interim','incomplete']);
                        }
                        else if(stripos($this->report_type[0],'close')!==false){

                            $query->whereIN('r.status',['completed']);
                        }
                    }
                }
                
                if($this->type != '')
                {
                    if(stripos($this->type,'daily')!==false)
                    {
                        $query=$query->whereDate('u.created_at',date('Y-m-d'));
                    }
                    else if(stripos($this->type,'weekly')!==false)
                    {
                        $query=$query->whereDate('u.created_at','>=',date('Y-m-d',strtotime('- 6 days')))->whereDate('u.created_at','<=',date('Y-m-d'));
                    }
                    else if(stripos($this->type,'monthly')!==false)
                    {
                        if($this->month!=null && count($this->month)>0)
                        {
                            $query=$query->whereIn(DB::raw('month(u.created_at)'),$this->month)->whereYear('u.created_at','=',$this->year);
                        }
                        else
                        {
                            $query=$query->whereIn(DB::raw('month(u.created_at)'),[date('m')])->whereYear('u.created_at','=',date('Y'));
                        }
                    }
                }
                else
                {
                    if($this->month!=null && count($this->month)>0)
                    {

                        $query=$query->whereIn(DB::raw('month(u.created_at)'),$this->month)->whereYear('u.created_at','=',$this->year);
                    }
                    else
                    {
                        $query=$query->whereIn(DB::raw('month(u.created_at)'),[date('m')])->whereYear('u.created_at','=',date('Y'));
                    }
                }


                $user = $query->groupBy('ri.candidate_id')->get();  

                $q_count = count($user);

                $event->sheet->getDelegate()->getStyle('A1:GP1')
                                            ->getAlignment()
                                            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                            ->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A1:GP1')->getFill()
                                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                        ->getStartColor()
                                        ->setRGB('6FCBDB');

                $event->sheet->getDelegate()->getStyle('A1:GP1')
                                            ->getFont()
                                            ->getColor()
                                            ->setRGB('000000');

                // for($i=65;$i<=90;$i++)
                // {
                //     $cell = chr($i)."1";

                //     $event->sheet->getDelegate()->getStyle($cell)->applyFromArray([
                //         'borders' => [
                //             'outline' => [
                //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                //                 'color' => ['argb' => '00000000'],
                //             ],
                //         ],
                //     ]);

                // $event->sheet->getDelegate()->getStyle('A2:FX'.($q_count + 1))
                //                             ->getAlignment()
                //                             ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                //                             ->setVertical(Alignment::VERTICAL_CENTER); 
                    
                // }
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(30);

                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);

                $event->sheet->getDelegate()->getStyle('AG2:AG'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('AJ2:AJ'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('AM2:AM'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('AP2:AP'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('AS2:AS'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                $event->sheet->getDelegate()->getStyle('AW2:AW'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('AZ2:AZ'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('BC2:BC'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('BF2:BF'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('BI2:BI'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                $event->sheet->getDelegate()->getStyle('BM2:BM'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('BQ2:BQ'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('BU2:BU'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('BY2:BY'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('CC2:CC'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                $event->sheet->getDelegate()->getStyle('CH2:CH'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('CL2:CL'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('CP2:CP'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('CT2:CT'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('CX2:CX'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('DB2:DB'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('DF2:DF'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                $event->sheet->getDelegate()->getStyle('DK2:DK'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('DN2:DN'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('DQ2:DQ'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                $event->sheet->getDelegate()->getStyle('DU2:DU'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('DX2:DX'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('EA2:EA'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                $event->sheet->getDelegate()->getStyle('EE2:EE'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('EH2:EH'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('EK2:EK'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('EN2:EN'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('EQ2:EQ'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                $event->sheet->getDelegate()->getStyle('EU2:EU'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('EX2:EX'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('FA2:FA'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                $event->sheet->getDelegate()->getStyle('FE2:FE'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('FG2:FG'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                $event->sheet->getDelegate()->getStyle('FJ2:FJ'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('FL2:FL'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                $event->sheet->getDelegate()->getStyle('FN2:FN'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            },
        ];

        
    }

    public function columnFormats(): array
    {
        return [
            'AG' => NumberFormat::FORMAT_NUMBER_00,
            'AJ' => NumberFormat::FORMAT_NUMBER_00,
            'AM' => NumberFormat::FORMAT_NUMBER_00,
            'AP' => NumberFormat::FORMAT_NUMBER_00,
            'AS' => NumberFormat::FORMAT_NUMBER_00,

            'AW' => NumberFormat::FORMAT_NUMBER_00,
            'AZ' => NumberFormat::FORMAT_NUMBER_00,
            'BC' => NumberFormat::FORMAT_NUMBER_00,
            'BF' => NumberFormat::FORMAT_NUMBER_00,
            'BI' => NumberFormat::FORMAT_NUMBER_00,

            'BM' => NumberFormat::FORMAT_NUMBER_00,
            'BQ' => NumberFormat::FORMAT_NUMBER_00,
            'BU' => NumberFormat::FORMAT_NUMBER_00,
            'BY' => NumberFormat::FORMAT_NUMBER_00,
            'CC' => NumberFormat::FORMAT_NUMBER_00,

            'CH' => NumberFormat::FORMAT_NUMBER_00,
            'CL' => NumberFormat::FORMAT_NUMBER_00,
            'CP' => NumberFormat::FORMAT_NUMBER_00,
            'CT' => NumberFormat::FORMAT_NUMBER_00,
            'CX' => NumberFormat::FORMAT_NUMBER_00,
            'DB' => NumberFormat::FORMAT_NUMBER_00,
            'DF' => NumberFormat::FORMAT_NUMBER_00,

            'DK' => NumberFormat::FORMAT_NUMBER_00,
            'DN' => NumberFormat::FORMAT_NUMBER_00,
            'DQ' => NumberFormat::FORMAT_NUMBER_00,

            'DU' => NumberFormat::FORMAT_NUMBER_00,
            'DX' => NumberFormat::FORMAT_NUMBER_00,
            'EA' => NumberFormat::FORMAT_NUMBER_00,

            'EE' => NumberFormat::FORMAT_NUMBER_00,
            'EH' => NumberFormat::FORMAT_NUMBER_00,
            'EK' => NumberFormat::FORMAT_NUMBER_00,
            'EN' => NumberFormat::FORMAT_NUMBER_00,
            'EQ' => NumberFormat::FORMAT_NUMBER_00,

            'EU' => NumberFormat::FORMAT_NUMBER_00,
            'EX' => NumberFormat::FORMAT_NUMBER_00,
            'FA' => NumberFormat::FORMAT_NUMBER_00,

            'FE' => NumberFormat::FORMAT_NUMBER_00,
            'FG' => NumberFormat::FORMAT_NUMBER_00,

            'FJ' => NumberFormat::FORMAT_NUMBER_00,
            'FL' => NumberFormat::FORMAT_NUMBER_00,

            'FN' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }

    public function workingDays($start_date,$tatwDays,$inc_tatwDays)
    {
        // using + weekdays excludes weekends
        $arr=[];

        $tat_new_date = date('d-F-Y', strtotime("{$start_date} +{$tatwDays} weekdays"));

        $inc_tat_new_date = date('d-F-Y', strtotime("{$start_date} +{$inc_tatwDays} weekdays"));

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
    
    public function round_up ( $value, $precision ) { 
        $pow = pow ( 10, $precision ); 
        return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow; 
    }
}