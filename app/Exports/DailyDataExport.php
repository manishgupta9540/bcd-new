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
// use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;
// use Maatwebsite\Excel\Concerns\WithStartRow;

// use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DailyDataExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection 
    */
    protected $today_date;
    protected $business_id;

    function __construct($today_date, $business_id) {
        $this->today_date       = $today_date;
        $this->business_id      = $business_id;
    }

    public function collection()
    {
        $user = collect([]);

        return $user;
    }

    public function map($user): array
    {
        $data = [];

        return $data;
    }

    public function headings(): array
    {
        $columns = [
            ['Daily Report',date('d M Y')],
            [],
            [NULL,'Cases','Amount'],
            ['Receiving Cases Yesterday'],
            ['FR Delivered Yesterday'],
            ['FR out TAT'],
            ['FR in TAT'],
            // ['FR Total Amount'],
            ['FR in the Month'],
            ['FR in the Month in TAT'],
            ['FR in the Month out TAT'],
            ['Total Pending Cases'],
            ['Pending Cases in TAT'],
            ['Pending Cases Out TAT'],
            ['Insuff Added Yesterday'],
            ['Total Insuff'],
            [],
            ['*FR - Final Report'],
            ['This is a Computer Generated Report'],
        ];

        return $columns;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:ZZ1'; // All headers

                $previous_date = date('Y-m-d',strtotime($this->today_date.'-1 day'));

                // Receiving Cases Yesterday
                
                $receive_amount = 0.00;

                $receiving_case = DB::table('candidate_reinitiates')
                                    ->where(['parent_id'=>$this->business_id,'user_type'=>'candidate'])
                                    ->whereDate('created_at',$previous_date)
                                    ->count();

                $case_detail = DB::table('candidate_reinitiates as u')
                ->select('u.parent_id','u.created_at as candidate_creation_date','j.tat_type','j.tat','j.client_tat','j.days_type','u.id as candidate_id','j.price_type','j.package_price')
                ->join('job_items as j','j.candidate_id','=','u.id')
                ->where(['u.parent_id'=>$this->business_id,'u.user_type'=>'candidate'])
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

                $event->sheet->setCellValue('C4',strval($receive_amount));

                $event->sheet->getDelegate()->getStyle('C4')->getNumberFormat()->setFormatCode('0.00');

                $event->sheet->setCellValue('B4',strval($receiving_case));

                // FR Delivered Yesterday

                $report_delivered = DB::table('reports as r')
                                    ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                                    ->where(['r.parent_id'=>$this->business_id,'u.user_type'=>'candidate','r.is_report_complete'=>1])
                                    ->whereIn('r.status',['interim','completed'])
                                    ->whereDate('r.report_complete_created_at',$previous_date)
                                    ->count();

                $event->sheet->setCellValue('B5',strval($report_delivered));

                // FR Yesterday IN & Out TAT

                $case_detail = DB::table('reports as r')
                                ->select('u.parent_id','u.created_at as candidate_creation_date','r.report_complete_created_at','j.tat_type','j.tat','j.client_tat','j.days_type','r.id as report_id','u.id as candidate_id','j.price_type','j.package_price')
                                ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                                ->join('job_items as j','j.candidate_id','=','u.id')
                                ->where(['r.parent_id'=>$this->business_id,'u.user_type'=>'candidate','r.is_report_complete'=>1])
                                ->whereIn('r.status',['interim','completed'])
                                ->whereDate('r.report_complete_created_at',$previous_date)
                                ->get();
                $in_tat = 0;

                $out_tat = 0;

                $total_amount = 0.00;

                if(count($case_detail) > 0)
                {
                    foreach($case_detail as $item)
                    {
                        $candidate_date = date('Y-m-d',strtotime($item->candidate_creation_date));

                        $completed_date = date('Y-m-d',strtotime($item->report_complete_created_at));

                        $job_sla_item_sup = DB::table('job_sla_items')->where(['candidate_id'=>$item->candidate_id,'is_supplementary'=>'1'])->get();

                            if(stripos($item->price_type,'package')!==false)
                            {
                                $total_amount = number_format(str_replace(',','',number_format($total_amount + $item->package_price,2)),2,'.','');

                                if(count($job_sla_item_sup)>0)
                                {
                                    foreach($job_sla_item_sup as $item_sup)
                                    {
                                        $total_amount = number_format(str_replace(',','',number_format($total_amount + $item_sup->price,2)),2,'.','');
                                    }
                                }
                            }
                            else if(stripos($item->price_type,'check')!==false)
                            {
                                $job_sla_item_price = DB::table('job_sla_items')->where('candidate_id',$item->candidate_id)->sum('price');

                                $total_amount = number_format(str_replace(',','',number_format($total_amount + $job_sla_item_price,2)),2,'.','');
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
                                $in_tat = $in_tat + 1;
                            }
                            else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                            {
                                $out_tat = $out_tat + 1;
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
                            //         $in_tat = $in_tat + 1;
                            //     }
                            //     else if($out_check > $in_check)
                            //     {
                            //         $out_tat = $out_tat + 1;
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
                                $in_tat = $in_tat + 1;
                            }
                            else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                            {
                                $out_tat = $out_tat + 1;
                            }


                        }
                    }
                }

                $event->sheet->setCellValue('B6',strval($out_tat));

                $event->sheet->setCellValue('B7',strval($in_tat));

                $event->sheet->setCellValue('C5',strval($total_amount));

                $event->sheet->getDelegate()->getStyle('C5')->getNumberFormat()->setFormatCode('0.00');

                // $event->sheet->setCellValue('B8',strval($total_amount));

                // $event->sheet->getDelegate()->getStyle('B8')->getNumberFormat()->setFormatCode('0.00');

                // FR Delivered in the Month

                $from_date = date('Y-m-01',strtotime($previous_date));

                $to_date = date('Y-m-d',strtotime($previous_date));

                $report_delivered = DB::table('reports as r')
                                ->select('u.parent_id','u.created_at as candidate_creation_date','r.report_complete_created_at','j.tat_type','j.tat','j.client_tat','j.days_type','r.id as report_id','u.id as candidate_id')
                                ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                                ->join('job_items as j','j.candidate_id','=','u.id')
                                ->where(['r.parent_id'=>$this->business_id,'u.user_type'=>'candidate','u.is_deleted'=>'0','r.is_report_complete'=>1])
                                ->whereIn('r.status',['interim','completed'])
                                ->whereDate('r.report_complete_created_at','>=',$from_date)
                                ->whereDate('r.report_complete_created_at','<=',$to_date)
                                ->count();

                $event->sheet->setCellValue('B8',strval($report_delivered));

                // FR Delivered in the Month In & Out TAT

                $case_detail = DB::table('reports as r')
                                    ->select('u.parent_id','u.created_at as candidate_creation_date','r.report_complete_created_at','j.tat_type','j.tat','j.client_tat','j.days_type','r.id as report_id','u.id as candidate_id','j.price_type','j.package_price')
                                    ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                                    ->join('job_items as j','j.candidate_id','=','u.id')
                                    ->where(['r.parent_id'=>$this->business_id,'u.user_type'=>'candidate','u.is_deleted'=>'0','r.is_report_complete'=>1])
                                    ->whereIn('r.status',['interim','completed'])
                                    ->whereDate('r.report_complete_created_at','>=',$from_date)
                                    ->whereDate('r.report_complete_created_at','<=',$to_date)
                                    ->get();            
                $in_tat = 0;

                $out_tat = 0;

                $fr_m_total_amount = 0.00;

                if(count($case_detail) > 0)
                {
                    foreach($case_detail as $item)
                    {
                        $candidate_date = date('Y-m-d',strtotime($item->candidate_creation_date));

                        $completed_date = date('Y-m-d',strtotime($item->report_complete_created_at));

                        $job_sla_item_sup = DB::table('job_sla_items')->where(['candidate_id'=>$item->candidate_id,'is_supplementary'=>'1'])->get();

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
                                $in_tat = $in_tat + 1;
                            }
                            else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                            {
                                $out_tat = $out_tat + 1;
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
                            //         $in_tat = $in_tat + 1;
                            //     }
                            //     else if($out_check > $in_check)
                            //     {
                            //         $out_tat = $out_tat + 1;
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
                                $in_tat = $in_tat + 1;
                            }
                            else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                            {
                                $out_tat = $out_tat + 1;
                            }


                        }
                    }
                }

                $event->sheet->setCellValue('B9',strval($in_tat));

                $event->sheet->setCellValue('B10',strval($out_tat));

                $event->sheet->setCellValue('C8',strval($fr_m_total_amount));

                $event->sheet->getDelegate()->getStyle('C8')->getNumberFormat()->setFormatCode('0.00');

                // Total Pending Case

                $wip_in = 0;

                $wip_out = 0;

                $wip_count = 0;

                $wip_total_amount = 0.00;

                $wip_1 = DB::table('candidate_reinitiates as u')
                            ->select('u.parent_id','u.id as candidate_id','j.price_type','j.package_price','u.created_at as candidate_creation_date','j.tat_type','j.tat','j.client_tat','j.days_type')
                            ->join('job_items as j','j.candidate_id','=','u.id')
                            ->where(['parent_id'=>$this->business_id,'user_type'=>'candidate','is_deleted'=>0])
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
                            ->where(['u.parent_id'=>$this->business_id,'u.user_type'=>'candidate','u.is_deleted'=>0,'j.jaf_status'=>'filled','r.status'=>'incomplete'])
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

                $event->sheet->setCellValue('B11',strval($wip_count));

                $event->sheet->setCellValue('C11',strval($wip_total_amount));

                $event->sheet->getDelegate()->getStyle('C11')->getNumberFormat()->setFormatCode('0.00');

                // Pending Case In & Out TAT

                $event->sheet->setCellValue('B12',strval($wip_in));

                $event->sheet->setCellValue('B13',strval($wip_out));

                // Insuff Added Yesterday

                $insuff_total_amount_y = 0.00;

                $insuff = DB::table('candidate_reinitiates as u')
                            ->select('u.id as candidate_id','j.price_type','j.package_price')
                            ->join('job_items as j','j.candidate_id','=','u.id')
                            ->join('jaf_form_data as jf','jf.candidate_id','=','u.id')
                            ->join('verification_insufficiency as v','v.jaf_form_data_id','=','jf.id')
                            ->where(['u.parent_id'=>$this->business_id,'u.user_type'=>'candidate','u.is_deleted'=>0,'jf.is_insufficiency'=>1])
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

                $event->sheet->setCellValue('B14',strval($insuff));

                $event->sheet->setCellValue('C14',strval($insuff_total_amount_y));

                $event->sheet->getDelegate()->getStyle('C14')->getNumberFormat()->setFormatCode('0.00');
                
                // Total Insuff

                $insuff_total_amount = 0.00;

                $insuff = DB::table('candidate_reinitiates as u')
                            ->select('u.id as candidate_id','j.price_type','j.package_price')
                            ->join('job_items as j','j.candidate_id','=','u.id')
                            ->join('jaf_form_data as jf','jf.candidate_id','=','u.id')
                            ->join('verification_insufficiency as v','v.jaf_form_data_id','=','jf.id')
                            ->where(['u.parent_id'=>$this->business_id,'u.user_type'=>'candidate','u.is_deleted'=>0,'jf.is_insufficiency'=>1])
                            ->whereIn('v.status',['raised','failed'])
                            ->get();

                if(count($insuff)>0)
                {
                    foreach($insuff as $item)
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

                $insuff = count($insuff);

                $event->sheet->setCellValue('B15',strval($insuff));

                $event->sheet->setCellValue('C15',strval($insuff_total_amount));

                $event->sheet->getDelegate()->getStyle('C15')->getNumberFormat()->setFormatCode('0.00');
                
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);

                $cellRange1 = 'B3';

                $event->sheet->getDelegate()->getStyle($cellRange1)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange1)->getFont()->setBold(true);

                $cellRange2 = 'C3';

                $event->sheet->getDelegate()->getStyle($cellRange2)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange2)->getFont()->setBold(true);

                $cellRange2 = 'A2:A18';

                $event->sheet->getDelegate()->getStyle($cellRange2)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange2)->getFont()->setBold(true);

                $event->sheet->getDelegate()->getStyle('B4:B16')->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                $event->sheet->getDelegate()->getStyle('C4:C16')->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                
            }
        ];
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