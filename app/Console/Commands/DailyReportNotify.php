<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DailyDataExport;

class DailyReportNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailyReport:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a Mail Notification Daily Report, Regarding Cases & Insufficiency';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $customers = DB::table('users')
        ->where('user_type','customer')
        ->get();

        if(count($customers)>0)
        {
            foreach($customers as $cust)
            {
                $business_id = $cust->id;

                $candidates = DB::table('candidate_reinitiates')
                                ->where(['parent_id'=>$business_id,'user_type'=>'candidate','is_deleted'=>0])
                                ->get();

                if(count($candidates) > 0)
                {   
                    $today_date = date('Y-m-d');

                    $path=public_path().'/uploads/daily-data-export/';

                    $file_name = 'daily-report-'.date('YmdHis').'.xlsx';

                    if(!File::exists($path))
                    {
                        File::makeDirectory($path, $mode = 0777, true, true);
                    }

                    // if (File::exists($path)) 
                    // {
                    //     File::cleanDirectory($path);
                    // }

                    //Excel::download(new DailyDataExport($today_date,$business_id),$file_name);

                    $previous_date = date('Y-m-d',strtotime($today_date.'-1 day'));

                    // Receiving Cases Yesterday

                    $receive_amount = 0.00;

                    $receiving_case = DB::table('candidate_reinitiates')
                                        ->where(['parent_id'=>$business_id,'user_type'=>'candidate'])
                                        ->whereDate('created_at',$previous_date)
                                        ->count();

                    $case_detail = DB::table('candidate_reinitiates as u')
                    ->select('u.parent_id','u.created_at as candidate_creation_date','j.tat_type','j.tat','j.client_tat','j.days_type','u.id as candidate_id','j.price_type','j.package_price')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->where(['u.parent_id'=>$business_id,'u.user_type'=>'candidate'])
                    ->whereDate('u.created_at',$previous_date)
                    ->get();

                    if(count($case_detail) > 0)
                    {
                        foreach($case_detail as $item)
                        {
                            $job_sla_item_sup = DB::table('job_sla_items')->where(['candidate_id'=>$item->candidate_id,'is_supplementary'=>'1'])->get();

                            if(stripos($item->price_type,'package')!==false)
                            {
                                $receive_amount = number_format(str_replace(',','',number_format($receive_amount + $item->package_price,2)),2,".","");

                                if(count($job_sla_item_sup)>0)
                                {
                                    foreach($job_sla_item_sup as $item_sup)
                                    {
                                        $receive_amount = number_format(str_replace(',','',number_format($receive_amount + $item_sup->price,2)),2,".","");
                                    }
                                }
                            }
                            else if(stripos($item->price_type,'check')!==false)
                            {
                                $job_sla_item_price = DB::table('job_sla_items')->where('candidate_id',$item->candidate_id)->sum('price');

                                $receive_amount = number_format(str_replace(',','',number_format($receive_amount + $job_sla_item_price,2)),2,".","");
                            }
                        }
                    }

                    // FR Delivered Yesterday

                    $report_delivered = DB::table('reports as r')
                                        ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                                        ->where(['r.parent_id'=>$business_id,'u.user_type'=>'candidate','r.is_report_complete'=>1])
                                        ->whereIn('r.status',['interim','completed'])
                                        ->whereDate('r.report_complete_created_at',$previous_date)
                                        ->count();

                    // FR Yesterday IN & Out TAT

                    $case_detail = DB::table('reports as r')
                    ->select('u.parent_id','u.created_at as candidate_creation_date','r.report_complete_created_at','j.tat_type','j.tat','j.client_tat','j.days_type','r.id as report_id','u.id as candidate_id','j.price_type','j.package_price')
                    ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->where(['r.parent_id'=>$business_id,'u.user_type'=>'candidate','r.is_report_complete'=>1])
                    ->whereIn('r.status',['interim','completed'])
                    ->whereDate('r.report_complete_created_at',$previous_date)
                    ->get();

                    $fr_in_tat_y = 0;

                    $fr_out_tat_y = 0;

                    $fr_total_amount = 0.00;

                    if(count($case_detail) > 0)
                    {
                        foreach($case_detail as $item)
                        {
                            $candidate_date = date('Y-m-d',strtotime($item->candidate_creation_date));

                            $completed_date = date('Y-m-d',strtotime($item->report_complete_created_at));

                            $job_sla_item_sup = DB::table('job_sla_items')->where(['candidate_id'=>$item->candidate_id,'is_supplementary'=>'1'])->get();

                            if(stripos($item->price_type,'package')!==false)
                            {
                                $fr_total_amount = number_format(str_replace(',','',number_format($fr_total_amount + $item->package_price,2)),2,".","");

                                if(count($job_sla_item_sup)>0)
                                {
                                    foreach($job_sla_item_sup as $item_sup)
                                    {
                                        $fr_total_amount = number_format(str_replace(',','',number_format($fr_total_amount + $item_sup->price,2)),2,".","");
                                    }
                                }
                            }
                            else if(stripos($item->price_type,'check')!==false)
                            {
                                $job_sla_item_price = DB::table('job_sla_items')->where('candidate_id',$item->candidate_id)->sum('price');

                                $fr_total_amount = number_format(str_replace(',','',number_format($fr_total_amount + $job_sla_item_price,2)),2,".","");
                            }

                            if(stripos($item->tat_type,'case')!==false)
                            {
                                $date_arr = [];

                                $tat = $item->client_tat - 1;

                                $incentive_tat = $item->client_tat - 1;

                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                }
                                else if(stripos($item->days_type,'calender')!==false)
                                {
                                    $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$item->parent_id,'status'=>1])
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

                                if( strtotime($completed_date) <=  strtotime($date_arr['inc_tat_date']))
                                {
                                    $fr_in_tat_y = $fr_in_tat_y + 1;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $fr_out_tat_y = $fr_out_tat_y + 1;
                                }

                            }
                            else if(stripos($item->tat_type,'check')!==false)
                            {
                                // $report_items=DB::table('report_items')
                                //                 ->where(['report_id'=>$item->report_id])
                                //                 ->get();

                                // if(count($report_items)>0)
                                // {
                                //     $in_check = 0;

                                //     $out_check = 0;

                                //     foreach($report_items as $r_item)
                                //     {
                                //         $job_sla_items=DB::table('job_sla_items')->where(['candidate_id'=>$r_item->candidate_id,'service_id'=>$r_item->service_id])->first();

                                //         if($job_sla_items!=NULL)
                                //         {
                                //             $date_arr=[];

                                //             $tat=$job_sla_items->tat - 1;
                                //             $incentive_tat = $job_sla_items->incentive_tat - 1;

                                //             // check if its a additional check
                                //             if($r_item->is_supplementary==1)
                                //             {
                                //                 $job_sla_i = DB::table('job_sla_items')->where(['candidate_id'=>$r_item->candidate_id,'service_id'=>$r_item->service_id,'number_of_verifications'=>$r_item->service_item_number,'is_supplementary'=>'1'])->first();

                                //                 if($job_sla_i!=NULL)
                                //                 {
                                //                     $tat = $job_sla_i->tat - 1;
                                //                     $incentive_tat = $job_sla_i->incentive_tat - 1;
                                //                 }
                                //             }

                                //             if(stripos($item->days_type,'working')!==false)
                                //             {
                                //                 $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                //             }
                                //             else if(stripos($item->days_type,'calender')!==false)
                                //             {
                                //                 $holiday_master=DB::table('customer_holiday_masters')
                                //                                 ->distinct('date')
                                //                                 ->select('date')
                                //                                 ->where(['business_id'=>$item->parent_id,'status'=>1])
                                //                                 ->orderBy('date','asc')
                                //                                 ->get();
                                //                 if(count($holiday_master)>0)
                                //                 {
                                //                     $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$incentive_tat);
                                //                 }
                                //                 else
                                //                 {
                                //                     $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                //                 }
                                //             }

                                //             //check if task completed date is less than or equal to incentive Date
                                //             if(strtotime($date_arr['inc_tat_date']) <= strtotime($completed_date))
                                //             {
                                //                 $in_check = $in_check + 1;

                                //             }
                                //             else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                //             {
                                //                 $out_check = $out_check + 1;
                                //             }
                                //         }
                                //     }

                                //     if($in_check > $out_check)
                                //     {
                                //         $fr_in_tat_y = $fr_in_tat_y + 1;
                                //     }
                                //     else if($out_check > $in_check)
                                //     {
                                //         $fr_out_tat_y = $fr_out_tat_y + 1;
                                //     }

                                
                                    
                                // }

                                $date_arr=[];

                                $tat=DB::table('job_sla_items')
                                                ->select('tat')
                                                ->where(['candidate_id'=>$item->candidate_id])
                                                ->max('tat');

                                // $incentive_tat=DB::table('job_sla_items')
                                //             ->select('incentive_tat')
                                //             ->where(['candidate_id'=>$item->candidate_id])
                                //             ->max('incentive_tat');
                                
                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = $this->workingDays($candidate_date,$tat,$tat);
                                }
                                else if(stripos($item->days_type,'calender')!==false)
                                {
                                    $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$item->parent_id,'status'=>1])
                                                    ->orderBy('date','asc')
                                                    ->get();
                                    if(count($holiday_master)>0)
                                    {
                                        $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$tat);
                                    }
                                    else
                                    {
                                        $date_arr = $this->workingDays($candidate_date,$tat,$tat);
                                    }
                                }

                                if( strtotime($completed_date) <=  strtotime($date_arr['inc_tat_date']))
                                {
                                    $fr_in_tat_y = $fr_in_tat_y + 1;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $fr_out_tat_y = $fr_out_tat_y + 1;
                                }


                            }
                        }
                    }

                    // FR Delivered in the Month

                    $from_date = date('Y-m-01',strtotime($previous_date));

                    $to_date = date('Y-m-d',strtotime($previous_date));

                    $fr_month = DB::table('reports as r')
                                    ->select('u.parent_id','u.created_at as candidate_creation_date','r.report_complete_created_at','j.tat_type','j.tat','j.client_tat','j.days_type','r.id as report_id','u.id as candidate_id','j.price_type','j.package_price')
                                    ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                                    ->join('job_items as j','j.candidate_id','=','u.id')
                                    ->where(['r.parent_id'=>$business_id,'u.user_type'=>'candidate','u.is_deleted'=>'0','r.is_report_complete'=>1])
                                    ->whereIn('r.status',['interim','completed'])
                                    ->whereDate('r.report_complete_created_at','>=',$from_date)
                                    ->whereDate('r.report_complete_created_at','<=',$to_date)
                                    ->count();

                    // FR Delivered in the Month In & Out TAT

                    $case_detail = DB::table('reports as r')
                    ->select('u.parent_id','u.created_at as candidate_creation_date','r.report_complete_created_at','j.tat_type','j.tat','j.client_tat','j.days_type','r.id as report_id','u.id as candidate_id','j.price_type','j.package_price')
                    ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->where(['r.parent_id'=>$business_id,'u.user_type'=>'candidate','u.is_deleted'=>'0','r.is_report_complete'=>1])
                    ->whereIn('r.status',['interim','completed'])
                    ->whereDate('r.report_complete_created_at','>=',$from_date)
                    ->whereDate('r.report_complete_created_at','<=',$to_date)
                    ->get();   
                            
                    $fr_in_tat_m = 0;

                    $fr_out_tat_m = 0;

                    $fr_m_total_amount = 0.00;

                    if(count($case_detail) > 0)
                    {
                        foreach($case_detail as $item)
                        {
                            $candidate_date = date('Y-m-d',strtotime($item->candidate_creation_date));

                            $completed_date = date('Y-m-d',strtotime($item->report_complete_created_at));

                            if(stripos($item->price_type,'package')!==false)
                            {
                                $fr_m_total_amount = number_format(str_replace(',','',number_format($fr_m_total_amount + $item->package_price,2)),2,".","");

                                if(count($job_sla_item_sup)>0)
                                {
                                    foreach($job_sla_item_sup as $item_sup)
                                    {
                                        $fr_m_total_amount = number_format(str_replace(',','',number_format($fr_m_total_amount + $item_sup->price,2)),2,".","");
                                    }
                                }
                            }
                            else if(stripos($item->price_type,'check')!==false)
                            {
                                $job_sla_item_price = DB::table('job_sla_items')->where('candidate_id',$item->candidate_id)->sum('price');

                                $fr_m_total_amount = number_format(str_replace(',','',number_format($fr_m_total_amount + $job_sla_item_price,2)),2,".","");
                            }

                            if(stripos($item->tat_type,'case')!==false)
                            {
                                $date_arr = [];

                                $tat = $item->client_tat - 1;

                                $incentive_tat = $item->client_tat - 1;

                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                }
                                else if(stripos($item->days_type,'calender')!==false)
                                {
                                    $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$item->parent_id,'status'=>1])
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

                                if( strtotime($completed_date) <=  strtotime($date_arr['inc_tat_date']))
                                {
                                    $fr_in_tat_m = $fr_in_tat_m + 1;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $fr_out_tat_m = $fr_out_tat_m + 1;
                                }

                            }
                            else if(stripos($item->tat_type,'check')!==false)
                            {
                                // $report_items=DB::table('report_items')
                                //                 ->where(['report_id'=>$item->report_id])
                                //                 ->get();

                                // if(count($report_items)>0)
                                // {
                                //     $in_check = 0;

                                //     $out_check = 0;

                                //     foreach($report_items as $r_item)
                                //     {
                                //         $job_sla_items=DB::table('job_sla_items')->where(['candidate_id'=>$r_item->candidate_id,'service_id'=>$r_item->service_id])->first();

                                //         if($job_sla_items!=NULL)
                                //         {
                                //             $date_arr=[];

                                //             $tat=$job_sla_items->tat - 1;
                                //             $incentive_tat = $job_sla_items->incentive_tat - 1;

                                //             // check if its a additional check
                                //             if($r_item->is_supplementary==1)
                                //             {
                                //                 $job_sla_i = DB::table('job_sla_items')->where(['candidate_id'=>$r_item->candidate_id,'service_id'=>$r_item->service_id,'number_of_verifications'=>$r_item->service_item_number,'is_supplementary'=>'1'])->first();

                                //                 if($job_sla_i!=NULL)
                                //                 {
                                //                     $tat = $job_sla_i->tat - 1;
                                //                     $incentive_tat = $job_sla_i->incentive_tat - 1;
                                //                 }
                                //             }

                                //             if(stripos($item->days_type,'working')!==false)
                                //             {
                                //                 $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                //             }
                                //             else if(stripos($item->days_type,'calender')!==false)
                                //             {
                                //                 $holiday_master=DB::table('customer_holiday_masters')
                                //                                 ->distinct('date')
                                //                                 ->select('date')
                                //                                 ->where(['business_id'=>$item->parent_id,'status'=>1])
                                //                                 ->orderBy('date','asc')
                                //                                 ->get();
                                //                 if(count($holiday_master)>0)
                                //                 {
                                //                     $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$incentive_tat);
                                //                 }
                                //                 else
                                //                 {
                                //                     $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                //                 }
                                //             }

                                //             //check if task completed date is less than or equal to incentive Date
                                //             if(strtotime($date_arr['inc_tat_date']) <= strtotime($completed_date))
                                //             {
                                //                 $in_check = $in_check + 1;

                                //             }
                                //             else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                //             {
                                //                 $out_check = $out_check + 1;
                                //             }
                                //         }
                                //     }

                                //     if($in_check > $out_check)
                                //     {
                                //         $fr_in_tat_m = $fr_in_tat_m + 1;
                                //     }
                                //     else if($out_check > $in_check)
                                //     {
                                //         $fr_out_tat_m = $fr_out_tat_m + 1;
                                //     }

                                
                                    
                                // }

                                $date_arr=[];

                                $tat=DB::table('job_sla_items')
                                                ->select('tat')
                                                ->where(['candidate_id'=>$item->candidate_id])
                                                ->max('tat');

                                // $incentive_tat=DB::table('job_sla_items')
                                //             ->select('incentive_tat')
                                //             ->where(['candidate_id'=>$item->candidate_id])
                                //             ->max('incentive_tat');
                                
                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = $this->workingDays($candidate_date,$tat,$tat);
                                }
                                else if(stripos($item->days_type,'calender')!==false)
                                {
                                    $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$item->parent_id,'status'=>1])
                                                    ->orderBy('date','asc')
                                                    ->get();
                                    if(count($holiday_master)>0)
                                    {
                                        $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$tat);
                                    }
                                    else
                                    {
                                        $date_arr = $this->workingDays($candidate_date,$tat,$tat);
                                    }
                                }

                                if( strtotime($completed_date) <=  strtotime($date_arr['inc_tat_date']))
                                {
                                    $fr_in_tat_m = $fr_in_tat_m + 1;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $fr_out_tat_m = $fr_out_tat_m + 1;
                                }


                            }
                        }
                    }

                    // Total Pending Case & Pending Cases In & Out

                    $wip_in = 0;

                    $wip_out = 0;

                    $wip_count = 0;

                    $wip_total_amount = 0.00;

                    $wip_1 = DB::table('candidate_reinitiates as u')
                                ->select('u.parent_id','u.id as candidate_id','j.price_type','j.package_price','u.created_at as candidate_creation_date','j.tat_type','j.tat','j.client_tat','j.days_type')
                                ->join('job_items as j','j.candidate_id','=','u.id')
                                ->where(['parent_id'=>$business_id,'user_type'=>'candidate','is_deleted'=>0])
                                ->where('j.jaf_status','<>','filled')
                                ->get();

                    if(count($wip_1)>0)
                    {
                        foreach($wip_1 as $item)
                        {

                            $candidate_date = date('Y-m-d',strtotime($item->candidate_creation_date));

                            $completed_date = date('Y-m-d',strtotime($previous_date));

                            $job_sla_item_sup = DB::table('job_sla_items')->where(['candidate_id'=>$item->candidate_id,'is_supplementary'=>'1'])->get();
    
                            if(stripos($item->price_type,'package')!==false)
                            {
                                $wip_total_amount = number_format(str_replace(',','',number_format($wip_total_amount + $item->package_price,2)),2,".","");
    
                                if(count($job_sla_item_sup)>0)
                                {
                                    foreach($job_sla_item_sup as $item_sup)
                                    {
                                        $wip_total_amount = number_format(str_replace(',','',number_format($wip_total_amount + $item_sup->price,2)),2,".","");
                                    }
                                }
                            }
                            else if(stripos($item->price_type,'check')!==false)
                            {
                                $job_sla_item_price = DB::table('job_sla_items')->where('candidate_id',$item->candidate_id)->sum('price');
    
                                $wip_total_amount = number_format(str_replace(',','',number_format($wip_total_amount + $job_sla_item_price,2)),2,".","");
                            }

                            if(stripos($item->tat_type,'case')!==false)
                            {
                                $date_arr = [];

                                $tat = $item->client_tat - 1;

                                $incentive_tat = $item->client_tat - 1;

                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                }
                                else if(stripos($item->days_type,'calender')!==false)
                                {
                                    $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$item->parent_id,'status'=>1])
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

                                if( strtotime($completed_date) <=  strtotime($date_arr['inc_tat_date']))
                                {
                                    $wip_in = $wip_in + 1;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $wip_out = $wip_out + 1;
                                }

                            }
                            else if(stripos($item->tat_type,'check')!==false)
                            {

                                $date_arr=[];

                                $tat=DB::table('job_sla_items')
                                                ->select('tat')
                                                ->where(['candidate_id'=>$item->candidate_id])
                                                ->max('tat');

                                // $incentive_tat=DB::table('job_sla_items')
                                //             ->select('incentive_tat')
                                //             ->where(['candidate_id'=>$item->candidate_id])
                                //             ->max('incentive_tat');
                                
                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = $this->workingDays($candidate_date,$tat,$tat);
                                }
                                else if(stripos($item->days_type,'calender')!==false)
                                {
                                    $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$item->parent_id,'status'=>1])
                                                    ->orderBy('date','asc')
                                                    ->get();
                                    if(count($holiday_master)>0)
                                    {
                                        $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$tat);
                                    }
                                    else
                                    {
                                        $date_arr = $this->workingDays($candidate_date,$tat,$tat);
                                    }
                                }

                                if( strtotime($completed_date) <=  strtotime($date_arr['inc_tat_date']))
                                {
                                    $wip_in = $wip_in + 1;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $wip_out = $wip_out + 1;
                                }


                            }
                        }
                    }
                    
                    $wip_2 = DB::table('reports as r')
                                ->select('u.parent_id','u.id as candidate_id','j.price_type','j.package_price','u.created_at as candidate_creation_date','j.tat_type','j.tat','j.client_tat','j.days_type')
                                ->join('candidate_reinitiates as u','r.candidate_id','=','u.id')
                                ->join('job_items as j','j.candidate_id','=','u.id')
                                ->where(['u.parent_id'=>$business_id,'u.user_type'=>'candidate','u.is_deleted'=>0,'j.jaf_status'=>'filled','r.status'=>'incomplete'])
                                ->get();

                    if(count($wip_2)>0)
                    {
                        foreach($wip_2 as $item)
                        {
                            $candidate_date = date('Y-m-d',strtotime($item->candidate_creation_date));

                            $completed_date = date('Y-m-d',strtotime($previous_date));
                            
                            $job_sla_item_sup = DB::table('job_sla_items')->where(['candidate_id'=>$item->candidate_id,'is_supplementary'=>'1'])->get();
    
                            if(stripos($item->price_type,'package')!==false)
                            {
                                $wip_total_amount = number_format(str_replace(',','',number_format($wip_total_amount + $item->package_price,2)),2,".","");
    
                                if(count($job_sla_item_sup)>0)
                                {
                                    foreach($job_sla_item_sup as $item_sup)
                                    {
                                        $wip_total_amount = number_format(str_replace(',','',number_format($wip_total_amount + $item_sup->price,2)),2,".","");
                                    }
                                }
                            }
                            else if(stripos($item->price_type,'check')!==false)
                            {
                                $job_sla_item_price = DB::table('job_sla_items')->where('candidate_id',$item->candidate_id)->sum('price');
    
                                $wip_total_amount = number_format(str_replace(',','',number_format($wip_total_amount + $job_sla_item_price,2)),2,".","");
                            }

                            if(stripos($item->tat_type,'case')!==false)
                            {
                                $date_arr = [];

                                $tat = $item->client_tat - 1;

                                $incentive_tat = $item->client_tat - 1;

                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                }
                                else if(stripos($item->days_type,'calender')!==false)
                                {
                                    $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$item->parent_id,'status'=>1])
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

                                if( strtotime($completed_date) <=  strtotime($date_arr['inc_tat_date']))
                                {
                                    $wip_in = $wip_in + 1;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $wip_out = $wip_out + 1;
                                }

                            }
                            else if(stripos($item->tat_type,'check')!==false)
                            {

                                $date_arr=[];

                                $tat=DB::table('job_sla_items')
                                                ->select('tat')
                                                ->where(['candidate_id'=>$item->candidate_id])
                                                ->max('tat');

                                // $incentive_tat=DB::table('job_sla_items')
                                //             ->select('incentive_tat')
                                //             ->where(['candidate_id'=>$item->candidate_id])
                                //             ->max('incentive_tat');
                                
                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = $this->workingDays($candidate_date,$tat,$tat);
                                }
                                else if(stripos($item->days_type,'calender')!==false)
                                {
                                    $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$item->parent_id,'status'=>1])
                                                    ->orderBy('date','asc')
                                                    ->get();
                                    if(count($holiday_master)>0)
                                    {
                                        $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$tat);
                                    }
                                    else
                                    {
                                        $date_arr = $this->workingDays($candidate_date,$tat,$tat);
                                    }
                                }

                                if( strtotime($completed_date) <=  strtotime($date_arr['inc_tat_date']))
                                {
                                    $wip_in = $wip_in + 1;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $wip_out = $wip_out + 1;
                                }


                            }
                            
                        }
                    }

                    $wip_count = count($wip_1) + count($wip_2);

                    // Insuff Added Yesterday

                    $insuff_total_amount_y = 0.00;

                    $insuff = DB::table('candidate_reinitiates as u')
                                ->select('u.id as candidate_id','j.price_type','j.package_price')
                                ->join('job_items as j','j.candidate_id','=','u.id')
                                ->join('jaf_form_data as jf','jf.candidate_id','=','u.id')
                                ->join('verification_insufficiency as v','v.jaf_form_data_id','=','jf.id')
                                ->where(['u.parent_id'=>$business_id,'u.user_type'=>'candidate','u.is_deleted'=>0,'jf.is_insufficiency'=>1])
                                ->whereIn('v.status',['raised','failed'])
                                ->whereDate('v.created_at',$previous_date)
                                ->get();

                    if(count($insuff)>0)
                    {
                        foreach($insuff as $item)
                        {
                            $job_sla_item_sup = DB::table('job_sla_items')->where(['candidate_id'=>$item->candidate_id,'is_supplementary'=>'1'])->get();
    
                            if(stripos($item->price_type,'package')!==false)
                            {
                                $insuff_total_amount_y = number_format(str_replace(',','',number_format($insuff_total_amount_y + $item->package_price,2)),2,".","");
    
                                if(count($job_sla_item_sup)>0)
                                {
                                    foreach($job_sla_item_sup as $item_sup)
                                    {
                                        $insuff_total_amount_y = number_format(str_replace(',','',number_format($insuff_total_amount_y + $item_sup->price,2)),2,".","");
                                    }
                                }
                            }
                            else if(stripos($item->price_type,'check')!==false)
                            {
                                $job_sla_item_price = DB::table('job_sla_items')->where('candidate_id',$item->candidate_id)->sum('price');
    
                                $insuff_total_amount_y = number_format(str_replace(',','',number_format($insuff_total_amount_y + $job_sla_item_price,2)),2,".","");
                            }
                        }
                    }

                    $insuff = count($insuff);

                    // Total Insuff

                    $insuff_total_amount = 0.00;

                    $total_insuff = DB::table('candidate_reinitiates as u')
                    ->select('u.id as candidate_id','j.price_type','j.package_price')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->join('jaf_form_data as jf','jf.candidate_id','=','u.id')
                    ->join('verification_insufficiency as v','v.jaf_form_data_id','=','jf.id')
                    ->where(['u.parent_id'=>$business_id,'u.user_type'=>'candidate','u.is_deleted'=>0,'jf.is_insufficiency'=>1])
                    ->whereIn('v.status',['raised','failed'])
                    ->get();

                    if(count($total_insuff)>0)
                    {
                        foreach($total_insuff as $item)
                        {
                            $job_sla_item_sup = DB::table('job_sla_items')->where(['candidate_id'=>$item->candidate_id,'is_supplementary'=>'1'])->get();

                            if(stripos($item->price_type,'package')!==false)
                            {
                                $insuff_total_amount = number_format(str_replace(',','',number_format($insuff_total_amount + $item->package_price,2)),2,".","");

                                if(count($job_sla_item_sup)>0)
                                {
                                    foreach($job_sla_item_sup as $item_sup)
                                    {
                                        $insuff_total_amount = number_format(str_replace(',','',number_format($insuff_total_amount + $item_sup->price,2)),2,".","");
                                    }
                                }
                            }
                            else if(stripos($item->price_type,'check')!==false)
                            {
                                $job_sla_item_price = DB::table('job_sla_items')->where('candidate_id',$item->candidate_id)->sum('price');

                                $insuff_total_amount = number_format(str_replace(',','',number_format($insuff_total_amount + $job_sla_item_price,2)),2,".","");
                            }
                        }
                    }

                    $total_insuff = count($total_insuff);

                    $name = $cust->name;

                    $email = $cust->email;

                    Excel::store(new DailyDataExport($today_date,$business_id),'/uploads/daily-data-export/'.$file_name,'real_public');

                    $url = url('/').'/uploads/daily-data-export/'.$file_name;

                    $sender = DB::table('users')->where(['id'=>$business_id])->first();

                    $data=[
                            'name' =>$name,
                            'email' => $email,
                            'url'=>$url,
                            'sender'=>$sender,
                            'receiving_case'=>$receiving_case,
                            'report_delivered'=>$report_delivered,
                            'receive_total_amount_y'=>$receive_amount,
                            'fr_total_amount_y'=>$fr_total_amount,
                            'fr_in_tat_y'=>$fr_in_tat_y,
                            'fr_out_tat_y'=>$fr_out_tat_y,
                            'fr_month'=>$fr_month,
                            'fr_total_amount_m' => $fr_m_total_amount,
                            'fr_in_tat_m'=>$fr_in_tat_m,
                            'fr_out_tat_m'=>$fr_out_tat_m,
                            'wip_count' => $wip_count,
                            'wip_total_amount' => $wip_total_amount,
                            'wip_in' => $wip_in,
                            'wip_out' => $wip_out,
                            'insuff' => $insuff,
                            'insuff_total_amount_y' => $insuff_total_amount_y,
                            'total_insuff' => $total_insuff,
                            'insuff_total_amount' => $insuff_total_amount
                        ];

                    // Mail::send(['html'=>'mails.demo-email'], $data, function($message) use($email,$name) {
                    //     $message->to($email, $name)->subject
                    //         ('myBCD System - Daily Report Notification');
                    //     $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                    // });

                    Mail::send(['html'=>'mails.daily-export-report'], $data, function($message) use($email,$name) {
                        $message->to($email, $name)->subject
                            ('myBCD System - Daily Report Notification');
                        $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                    });

                    if($business_id==94)
                    {
                        $mail_arr = [
                            ['name'=>'Jasjit','email'=>'jasjit@tagworld.in'],
                            ['name'=>'Pawan','email'=>'pawan@tagworld.in'],
                            ['name'=>'John Chenetra','email'=>'john.chenetra@premier-consultancy.com'],
                            ['name'=>'The Executive','email'=>'the-executive@premier-consultancy.com'],
                            ['name'=>'Akshay Kumar','email'=>'akshay.kumar@premier-consultancy.com'],
                            // ['name'=>'Neha','email'=>'neha.nivati@premier-consultancy.com'],
                            //['name'=>'Mithilesh Sah','email'=>'mithilesh.techsaga@gmail.com'],
                            ['name' => 'Ritu Rani', 'email' => 'ritu.rani@premier-consultancy.com']
                        ];

                        // $mail_arr = [
                        //     ['name'=>'Mithilesh Sah','email'=>'mithilesh.techsaga@gmail.com'],
                        // ];
                        
                        foreach($mail_arr as $item)
                        {
                            $name = $item['name'];
                            $email = $item['email'];

                            $url = url('/').'/uploads/daily-data-export/'.$file_name;

                            $sender = DB::table('users')->where(['id'=>$business_id])->first();

                            $data=[
                                    'name' =>$name,
                                    'email' => $email,
                                    'url'=>$url,
                                    'sender'=>$sender,
                                    'receiving_case'=>$receiving_case,
                                    'report_delivered'=>$report_delivered,
                                    'receive_total_amount_y'=>$receive_amount,
                                    'fr_total_amount_y'=>$fr_total_amount,
                                    'fr_in_tat_y'=>$fr_in_tat_y,
                                    'fr_out_tat_y'=>$fr_out_tat_y,
                                    'fr_month'=>$fr_month,
                                    'fr_total_amount_m' => $fr_m_total_amount,
                                    'fr_in_tat_m'=>$fr_in_tat_m,
                                    'fr_out_tat_m'=>$fr_out_tat_m,
                                    'wip_count' => $wip_count,
                                    'wip_total_amount' => $wip_total_amount,
                                    'wip_in' => $wip_in,
                                    'wip_out' => $wip_out,
                                    'insuff' => $insuff,
                                    'insuff_total_amount_y' => $insuff_total_amount_y,
                                    'total_insuff' => $total_insuff,
                                    'insuff_total_amount' => $insuff_total_amount
                                ];

                            Mail::send(['html'=>'mails.daily-export-report'], $data, function($message) use($email,$name) {
                                $message->to($email, $name)->subject
                                    ('myBCD System - Daily Report Notification');
                                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                            });
                        }
                    }
                }
            }

            $this->info('Daily Report Created Successfully for '.date('Y-m-d h:i A'));
        }
        

        return 0;
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
