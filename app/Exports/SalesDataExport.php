<?php

namespace App\Exports;
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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\FromQuery;

class SalesDataExport implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents, WithMapping, WithColumnWidths, WithPreCalculateFormulas, WithColumnFormatting
{
    use Exportable;

    protected $from_date;
    protected $to_date;
    protected $business_id;

    function __construct($from_date, $to_date, $business_id,$customer_id,$type) {
            $this->from_date        = $from_date;
            $this->to_date          = $to_date;
            $this->business_id      = $business_id;
            $this->customer_id      = $customer_id;
            $this->type = $type;
    }
    public function query()
    {
        // $user=[]; 
        $query = DB::table('users as u')
                    ->select('u.*','ub.company_name','ub.work_operating_date')
                    ->join('user_businesses as ub','ub.business_id','=','u.business_id')
                    ->where(['u.user_type'=>'client','u.parent_id'=>$this->business_id])
                    ->whereNotIn('u.id',[$this->business_id]); 

                    if($this->customer_id!=NULL)
                    {
                        $query->whereIn('u.id',$this->customer_id);
                    }
        // $query->orderBy('u.id','asc');

        //$user = $query->get();

        $user = $query->orderBy('u.id','asc');
        
        // $user->put('22',['company_name'=>'Total :']);

        // dd($this->customer_id);

        //dd($user);

        return $user;

    }
     //
    public function map($user): array
    {
        $data = [];
        //dd($data);
        // $services = DB::table('services')
        // ->whereNull('business_id')
        // ->where('status',1)
        // ->get();

        $new_arr=[$user->company_name, date('d-M-Y',strtotime($user->work_operating_date)),NULL];

        // Avg. no of case weekly

        $avg_week = 0.00;
        if(stripos($this->type,'weekly')!==false)
        {
            $avg_week = DB::table('candidate_reinitiates')
                            ->where(['business_id'=>$user->business_id,'user_type'=>'candidate','is_deleted'=>'0'])
                            ->whereDate('created_at','>=',$this->from_date)
                            ->whereDate('created_at','<=',$this->to_date)
                            ->count();

        }
        else if(stripos($this->type,'monthly')!==false)
        {
            $no_of_week = 1;

            for($i=1;$i<=date('d',strtotime($this->to_date));$i++)
            {
                if($i==8 || $i==15 || $i==22 || $i==29)
                    $no_of_week++;
            }

            $monthly_case = DB::table('candidate_reinitiates')
                        ->where(['business_id'=>$user->business_id,'user_type'=>'candidate','is_deleted'=>'0'])
                        ->whereDate('created_at','>=',$this->from_date)
                        ->whereDate('created_at','<=',$this->to_date)
                        ->count();
                
            $avg_week = number_format($monthly_case/$no_of_week,2);

        }
        else if(stripos($this->type,'quaterly')!==false)
        {
            $avg_m_week = 0.00;
            
            for($i=0;$i<3;$i++)
            {
                $start_date = date('Y-m-01',strtotime($this->from_date.' + '.$i.' month'));

                $end_date = date('Y-m-t',strtotime($this->from_date.' + '.$i.' month'));

                $no_of_week = 1;

                for($j=1;$j<=date('d',strtotime($end_date));$j++)
                {
    
                    if($j==8 || $j==15 || $j==22 || $j==29)
                        $no_of_week++;
                }

                // dd($no_of_week);

                $monthly_case = DB::table('candidate_reinitiates')
                            ->where(['business_id'=>$user->business_id,'user_type'=>'candidate','is_deleted'=>'0'])
                            ->whereDate('created_at','>=',$start_date)
                            ->whereDate('created_at','<=',$end_date)
                            ->count();
                // dd($monthly_case);
                
                $avg_m_week = number_format($avg_m_week + ($monthly_case / $no_of_week),2);
                // dd($avg_m_week);
            }

            $avg_week = $avg_m_week;
        }
        else if(stripos($this->type,'yearly')!==false)
        {
            $avg_m_week = 0;

            $no_of_month = date('n',strtotime($this->to_date));
            // dd($no_of_month);
            for($i=0;$i<$no_of_month;$i++)
            {
                $start_date = date('Y-m-01',strtotime($this->from_date.' + '.$i.' month'));

                $end_date = date('Y-m-t',strtotime($this->from_date.' + '.$i.' month'));

                $no_of_week = 1;

                for($j=1;$j<=date('d',strtotime($end_date));$j++)
                {
    
                    if($j==8 || $j==15 || $j==22 || $j==29)
                        $no_of_week++;
                }
    
                $monthly_case = DB::table('candidate_reinitiates')
                            ->where(['business_id'=>$user->business_id,'user_type'=>'candidate','is_deleted'=>'0'])
                            ->whereDate('created_at','>=',$start_date)
                            ->whereDate('created_at','<=',$end_date)
                            ->count();
                    
                $avg_m_week = $avg_m_week + number_format($monthly_case/$no_of_week,2);
            }

            $avg_week = $avg_m_week;
        }

        array_push($new_arr,strval($avg_week));

        

        // Avg. no of case monthly

        $monthly_case = 0.00;

        if(stripos($this->type,'weekly')!==false || stripos($this->type,'monthly')!==false)
        {
            $monthly_case = DB::table('candidate_reinitiates')
                    ->where(['business_id'=>$user->business_id,'user_type'=>'candidate','is_deleted'=>'0'])
                    ->whereDate('created_at','>=',$this->from_date)
                    ->whereDate('created_at','<=',$this->to_date)
                    ->count();
        }
        else if (stripos($this->type,'quaterly')!==false)
        {
            $monthly_case = DB::table('candidate_reinitiates')
                    ->where(['business_id'=>$user->business_id,'user_type'=>'candidate','is_deleted'=>'0'])
                    ->whereDate('created_at','>=',$this->from_date)
                    ->whereDate('created_at','<=',$this->to_date)
                    ->count();

            $monthly_case = number_format($monthly_case / 3, 2);
        }
        else if (stripos($this->type,'yearly')!==false)
        {
            $no_of_month = date('n',strtotime($this->to_date));

            $monthly_case = DB::table('candidate_reinitiates')
                    ->where(['business_id'=>$user->business_id,'user_type'=>'candidate','is_deleted'=>'0'])
                    ->whereDate('created_at','>=',$this->from_date)
                    ->whereDate('created_at','<=',$this->to_date)
                    ->count();

            $monthly_case = number_format($monthly_case / $no_of_month, 2);
        }

        array_push($new_arr,strval($monthly_case));
        

        // Avg. Rs per case

        $avg_per_case = 0.00;
        
        // if(stripos($this->type,'weekly')!==false || stripos($this->type,'monthly')!==false)
        // {
            $billing = DB::table('billings')
            ->where(['business_id'=>$user->business_id])
            ->whereDate('created_at','>=',$this->from_date)
            ->whereDate('created_at','<=',$this->to_date)
            ->get();

            if(count($billing)>0)
            {
                $bill_total = DB::table('billings')
                                ->select('total_amount')
                                ->where(['business_id'=>$user->business_id])
                                ->whereDate('created_at','>=',$this->from_date)
                                ->whereDate('created_at','<=',$this->to_date)
                                ->sum('total_amount');
                
                $no_of_case = DB::table('billings as b')
                                ->join('billing_items as bi','b.id','=','bi.billing_id')
                                ->where(['b.business_id'=>$user->business_id])
                                ->whereNotNull('bi.candidate_id')
                                ->whereDate('b.created_at','>=',$this->from_date)
                                ->whereDate('b.created_at','<=',$this->to_date)
                                ->groupBy('bi.billing_id','bi.candidate_id')
                                ->get();

                $no_of_case = count($no_of_case) >= 0 ? 1 : 1;

                $avg_per_case = number_format($bill_total / $no_of_case,2);
            }

        // }

        array_push($new_arr,strval($avg_per_case),$user->phone,$user->name);

        // Avg. Order Case

        $monthly_case = 0;

        if(stripos($this->type,'weekly')!==false || stripos($this->type,'monthly')!==false)
        {
            $monthly_case = DB::table('candidate_reinitiates')
                        ->where(['business_id'=>$user->business_id,'user_type'=>'candidate','is_deleted'=>'0'])
                        ->whereDate('created_at','>=',$this->from_date)
                        ->whereDate('created_at','<=',$this->to_date)
                        ->count();
        }
        else if (stripos($this->type,'quaterly')!==false)
        {
            $monthly_case = DB::table('candidate_reinitiates')
                    ->where(['business_id'=>$user->business_id,'user_type'=>'candidate','is_deleted'=>'0'])
                    ->whereDate('created_at','>=',$this->from_date)
                    ->whereDate('created_at','<=',$this->to_date)
                    ->count();

            $monthly_case = number_format($monthly_case / 3, 2);
        }
        else if (stripos($this->type,'yearly')!==false)
        {
            $no_of_month = date('n',strtotime($this->to_date));

            $monthly_case = DB::table('candidate_reinitiates')
                    ->where(['business_id'=>$user->business_id,'user_type'=>'candidate','is_deleted'=>'0'])
                    ->whereDate('created_at','>=',$this->from_date)
                    ->whereDate('created_at','<=',$this->to_date)
                    ->count();

            $monthly_case = number_format($monthly_case / $no_of_month, 2);
        }

        array_push($new_arr,strval($monthly_case));

        // Order Frequency & Usual Order date

        $order_frequency = 0;

        $order_date = NULL;

        // if(stripos($this->type,'weekly')!==false || stripos($this->type,'monthly')!==false)
        // {
            $cases = DB::table('candidate_reinitiates')
                    ->select(DB::raw('DATE(created_at) as case_date'),DB::raw('COUNT(id) as count'))
                    ->where(['business_id'=>$user->business_id,'user_type'=>'candidate','is_deleted'=>'0'])
                    ->whereDate('created_at','>=',$this->from_date)
                    ->whereDate('created_at','<=',$this->to_date)
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('count','desc')
                    ->orderBy('created_at','desc')
                    ->pluck(DB::raw('COUNT(id) as count'),DB::raw('DATE(created_at) as case_date'));

            $case_arr = $cases->all();

            // dd($case_arr);

            if(count($case_arr) > 0)
            {
                $order_frequency = array_values($case_arr)[0];

                $order_date = date('d-F-Y',strtotime(array_keys($case_arr)[0]));
            }
        // }
        

        array_push($new_arr,strval($order_frequency),$order_date,'N/A','N/A');

        // Week wise no of cases

        $date = date('Y-m-d',strtotime($this->from_date));

        if(stripos($this->type,'weekly')!==false)
        {
            for ($i=0; $i < 7; $i++) { 

                $date = date('Y-m-d',strtotime($this->from_date.'+'.$i.'days'));
    
                $candidate = DB::table('candidate_reinitiates')
                            ->where(['business_id'=>$user->business_id,'user_type'=>'candidate','is_deleted'=>'0'])
                            ->whereDate('created_at','=',$date)
                            ->count();
                           
                array_push($new_arr,strval($candidate));
            }
        }
        else if(stripos($this->type,'monthly')!==false)
        {
            for ($i=0; $i < date('t',strtotime($this->from_date)); $i++) { 

                $date = date('Y-m-d',strtotime($this->from_date.'+'.$i.'days'));
    
                $candidate = DB::table('candidate_reinitiates')
                            ->where(['business_id'=>$user->business_id,'user_type'=>'candidate','is_deleted'=>'0'])
                            ->whereDate('created_at','=',$date)
                            ->count();
                           
                array_push($new_arr,strval($candidate));
            }
        }
        else if(stripos($this->type,'quaterly')!==false)
        {
            for($i=0;$i<3;$i++)
            {
                $start_date = date('Y-m-01',strtotime($this->from_date.' + '.$i.' month'));

                $end_date = date('Y-m-t',strtotime($this->from_date.' + '.$i.' month'));

                $no_of_week = 1;

                for($j=1;$j<=date('d',strtotime($end_date));$j++)
                {
                    if($j==8 || $j==15 || $j==22 || $j==29)
                        $no_of_week++;
                }

                for($k=1;$k<=$no_of_week;$k++)
                {
                    if($k==1)
                    {
                        $week_start_date = date('Y-m-01',strtotime($start_date));

                        $week_end_date = date('Y-m-07',strtotime($end_date));
                    }
                    else if($k==2)
                    {
                        $week_start_date = date('Y-m-08',strtotime($start_date));

                        $week_end_date = date('Y-m-14',strtotime($end_date));
                    }
                    else if($k==3)
                    {
                        $week_start_date = date('Y-m-15',strtotime($start_date));

                        $week_end_date = date('Y-m-21',strtotime($end_date));
                    }
                    else if($k==4)
                    {
                        $week_start_date = date('Y-m-22',strtotime($start_date));

                        $week_end_date = date('Y-m-28',strtotime($end_date));
                    }
                    else if($k==5)
                    {
                        $week_start_date = date('Y-m-29',strtotime($start_date));

                        $week_end_date = date('Y-m-t',strtotime($end_date));
                    }

                    $candidate = DB::table('candidate_reinitiates')
                            ->where(['business_id'=>$user->business_id,'user_type'=>'candidate','is_deleted'=>'0'])
                            ->whereDate('created_at','>=',$week_start_date)
                            ->whereDate('created_at','<=',$week_end_date)
                            ->count();

                    array_push($new_arr,strval($candidate));

                }

                if($no_of_week < 5)
                {
                    $remain_week = abs(5 - $no_of_week);

                    for($l=0;$l < $remain_week;$l++)
                    {
                        array_push($new_arr,NULL);
                    }
                }

            }
        }
        else if(stripos($this->type,'yearly')!==false)
        {
            $no_of_month = date('n',strtotime($this->to_date));

            for($i=0;$i<$no_of_month;$i++)
            {

                $start_date = date('Y-m-01',strtotime($this->from_date.' + '.$i.' month'));

                $end_date = date('Y-m-t',strtotime($this->from_date.' + '.$i.' month'));

                $current_month = date('n',strtotime($start_date));

                if($current_month == date('n') && date('Y') == date('Y',strtotime($start_date)))
                {
                    $end_date = date('Y-m-d',strtotime($this->to_date));

                    // dd($end_date);
                }

                $no_of_week = 1;

                for($j=1;$j<=date('d',strtotime($end_date));$j++)
                {
                    if($j==8 || $j==15 || $j==22 || $j==29)
                        $no_of_week++;
                }

                for($k=1;$k<=$no_of_week;$k++)
                {
                    if($k==1)
                    {
                        $week_start_date = date('Y-m-01',strtotime($start_date));

                        $week_end_date = date('Y-m-07',strtotime($end_date));
                    }
                    else if($k==2)
                    {
                        $week_start_date = date('Y-m-08',strtotime($start_date));

                        $week_end_date = date('Y-m-14',strtotime($end_date));
                    }
                    else if($k==3)
                    {
                        $week_start_date = date('Y-m-15',strtotime($start_date));

                        $week_end_date = date('Y-m-21',strtotime($end_date));
                    }
                    else if($k==4)
                    {
                        $week_start_date = date('Y-m-22',strtotime($start_date));

                        $week_end_date = date('Y-m-28',strtotime($end_date));
                    }
                    else if($k==5)
                    {
                        $week_start_date = date('Y-m-29',strtotime($start_date));

                        $week_end_date = date('Y-m-t',strtotime($end_date));
                    }

                    $candidate = DB::table('candidate_reinitiates')
                            ->where(['business_id'=>$user->business_id,'user_type'=>'candidate','is_deleted'=>'0'])
                            ->whereDate('created_at','>=',$week_start_date)
                            ->whereDate('created_at','<=',$week_end_date)
                            ->count();

                    array_push($new_arr,strval($candidate));

                }

                if($no_of_week < 5)
                {
                    $remain_week = abs(5 - $no_of_week);

                    for($l=0;$l < $remain_week;$l++)
                    {
                        array_push($new_arr,NULL);
                    }
                }

            }
        }
        

        return $new_arr;
    }
 
     public function headings(): array
     {
 
         $services = DB::table('services')
                         ->whereNull('business_id')
                         ->where('status',1)
                         ->get();
         $service_count = count($services);
         // Row 0
         $columns = [
                         ['Client Details',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Old History',NULL,NULL,NULL,NULL],
                    ];

         if(stripos($this->type,'weekly')!==false)
         {
            array_push($columns[0],'Week-1 / Number of cases that we received from Client');
         }
         else if(stripos($this->type,'monthly')!==false)
         {
            array_push($columns[0],'Week-1 / Number of cases that we received from Client',NULL,NULL,NULL,NULL,NULL,NULL,'Week-2',NULL,NULL,NULL,NULL,NULL,NULL,'Week-3',NULL,NULL,NULL,NULL,NULL,NULL,'Week-4',NULL,NULL,NULL,NULL,NULL,NULL,'Week-5');
         }
         else if(stripos($this->type,'quaterly')!==false)
         {
             for($i=0;$i<3;$i++)
             {
                $month = date('F',strtotime($this->from_date.' + '.$i.' month'));

                if($i==0)
                {
                    array_push($columns[0],$month.' / Number of cases that we received from Client',NULL,NULL,NULL,NULL);
                }
                else
                {
                    array_push($columns[0],$month,NULL,NULL,NULL,NULL);
                }

             }
         }
         else if(stripos($this->type,'yearly')!==false)
         {
             for($i=0;$i<12;$i++)
             {
                $month = date('F',strtotime($this->from_date.' + '.$i.' month'));

                if($i==0)
                {
                    array_push($columns[0],$month.' / Number of cases that we received from Client',NULL,NULL,NULL,NULL);
                }
                else
                {
                    array_push($columns[0],$month,NULL,NULL,NULL,NULL);
                }

             }
         }

         //Row 1
         $case = [];

         //Clients Details
        
         array_push($case,'Client name','Agreement expiry date','Existing Customer category A=Rev more than 1cr B=Rev 50lak to 1cr C < 50lak','Avg No of cases weekly','Avg No of cases monthly','Average Rs per case','Contact details','Contact person');
 
         //Old History
         array_push($case,'Average Order Size','Order Frequency','Usual Order Date','Date for Calling','Frequency of Calling'); 
         
         if(stripos($this->type,'weekly')!==false)
         {
            for ($i=0;$i<7; $i++) { 
                // dd($this->from_date);
                $case[]= date('d',strtotime($this->from_date.'+'.$i.'days'));
             }
         }
         else if(stripos($this->type,'monthly')!==false)
         {
            for ($i=0;$i<date('t',strtotime($this->to_date)); $i++) { 
                // dd($this->from_date);
                $case[]= date('d',strtotime($this->from_date.'+'.$i.'days'));
             }
         }
         else if(stripos($this->type,'quaterly')!==false)
         {
            for($i=0;$i<3;$i++)
            {
                $k=0;
                for($j=0;$j<5;$j++)
                {
                    $k= $j + 1;
                    $case[]='Week - '.$k;
                }
            }
         }
         else if(stripos($this->type,'yearly')!==false)
         {
            for($i=0;$i<12;$i++)
            {
                $k=0;
                for($j=0;$j<5;$j++)
                {
                    $k= $j + 1;
                    $case[]='Week - '.$k;
                }
            }
         }
         
         $columns[]=$case;
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

                $merge_arr = [
                    'A1:H1',
                    'A2:A2',
                    'B2:B2',
                    'C2:C2',
                    'D2:D2',
                    'E2:E2',
                    'F2:F2',
                    'G2:G2',
                    'H2:H2',

                    'I1:M1',
                    'I2:I2',
                    'J2:J2',
                    'K2:K2',
                    'L2:L2',
                    'M2:M2',
                   
                ];

                if(stripos($this->type,'weekly')!==false)
                {
                    array_push($merge_arr,
                                    'N1:T1',
                                    'N2:N2',
                                    'O2:O2',
                                    'P2:P2',
                                    'Q2:Q2',
                                    'R2:R2',
                                    'S2:S2',
                                    'T2:T2',
                           );
                }
                else if(stripos($this->type,'monthly')!==false)
                {
                    array_push($merge_arr,
                                    'N1:T1',
                                    'N2:N2',
                                    'O2:O2',
                                    'P2:P2',
                                    'Q2:Q2',
                                    'R2:R2',
                                    'S2:S2',
                                    'T2:T2',

                                    'U1:AA1',
                                    'U2:U2',
                                    'V2:V2',
                                    'W2:W2',
                                    'X2:X2',
                                    'Y2:Y2',
                                    'Z2:Z2',
                                    'AA2:AA2',

                                    'AB1:AH1',
                                    'AB2:AB2',
                                    'AC2:AC2',
                                    'AD2:AD2',
                                    'AE2:AE2',
                                    'AF2:AF2',
                                    'AG2:AG2',
                                    'AH2:AH2',

                                    'AI1:AO1',
                                    'AI2:AI2',
                                    'AJ2:AJ2',
                                    'AK2:AK2',
                                    'AL2:AL2',
                                    'AM2:AM2',
                                    'AN2:AN2',
                                    'AO2:AO2',

                                    'AP1:AR1',
                                    'AP2:AP2',
                                    'AQ2:AQ2',
                                    'AR2:AR2'
                           );
                }
                else if(stripos($this->type,'quaterly')!==false)
                {
                    array_push($merge_arr,
                                    'N1:R1',
                                    'N2:N2',
                                    'O2:O2',
                                    'P2:P2',
                                    'Q2:Q2',
                                    'R2:R2',
                                    

                                    'S1:W1',
                                    'S2:S2',
                                    'T2:T2',
                                    'U2:U2',
                                    'V2:V2',
                                    'W2:W2',

                                    'X1:AB1',
                                    'X2:X2',
                                    'Y2:Y2',
                                    'Z2:Z2',
                                    'AA2:AA2',
                                    'AB2:AB2',

                        );
                }
                else if(stripos($this->type,'yearly')!==false)
                {
                    array_push($merge_arr,
                                    'N1:R1',
                                    'N2:N2',
                                    'O2:O2',
                                    'P2:P2',
                                    'Q2:Q2',
                                    'R2:R2',
                                    

                                    'S1:W1',
                                    'S2:S2',
                                    'T2:T2',
                                    'U2:U2',
                                    'V2:V2',
                                    'W2:W2',

                                    'X1:AB1',
                                    'X2:X2',
                                    'Y2:Y2',
                                    'Z2:Z2',
                                    'AA2:AA2',
                                    'AB2:AB2',

                                    'AC1:AG1',
                                    'AC2:AC2',
                                    'AD2:AD2',
                                    'AE2:AE2',
                                    'AF2:AF2',
                                    'AG2:AG2',

                                    'AH1:AL1',
                                    'AH2:AH2',
                                    'AI2:AI2',
                                    'AJ2:AJ2',
                                    'AK2:AK2',
                                    'AL2:AL2',

                                    'AM1:AQ1',
                                    'AM2:AM2',
                                    'AN2:AN2',
                                    'AO2:AO2',
                                    'AP2:AP2',
                                    'AQ2:AQ2',

                                    'AR1:AV1',
                                    'AR2:AR2',
                                    'AS2:AS2',
                                    'AT2:AT2',
                                    'AU2:AU2',
                                    'AV2:AV2',

                                    'AW1:BA1',
                                    'AW2:AW2',
                                    'AX2:AX2',
                                    'AY2:AY2',
                                    'AZ2:AZ2',
                                    'BA2:BA2',

                                    'BB1:BF1',
                                    'BB2:BB2',
                                    'BC2:BC2',
                                    'BD2:BD2',
                                    'BE2:BE2',
                                    'BF2:BF2',

                                    'BG1:BK1',
                                    'BG2:BG2',
                                    'BH2:BH2',
                                    'BI2:BI2',
                                    'BJ2:BJ2',
                                    'BK2:BK2',

                                    'BL1:BP1',
                                    'BL2:BL2',
                                    'BM2:BM2',
                                    'BN2:BN2',
                                    'BO2:BO2',
                                    'BP2:BP2',

                                    'BQ1:BU1',
                                    'BQ2:BQ2',
                                    'BR2:BR2',
                                    'BS2:BS2',
                                    'BT2:BT2',
                                    'BU2:BU2',

                        );
                }
                

                $event->sheet->getDelegate()->setMergeCells($merge_arr);
                $event->sheet->getDelegate()->getStyle('A1:H1')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('I1:M1')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('N1:T1')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('U1:AA1')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
                
                $event->sheet->getDelegate()->getStyle('AB1:AH1')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('AI1:AO1')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('AP1:AR1')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('AS1:BU1')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A2:L2')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

                // Client Details
                $event->sheet->getDelegate()->getStyle('A1:H1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('67B2DC');

                // Client Details Sub header
                $event->sheet->getDelegate()->getStyle('A2:H2')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('A8E3F4');

                // Range from A1 to G1
                $event->sheet->getDelegate()->getStyle('A1:H1')->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                ]);

                // Range from H1 to M1
                $event->sheet->getDelegate()->getStyle('I1:M1')->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                ]);

                // cell border based on type 

                if(stripos($this->type,'weekly')!==false)
                {
                    // Range from N1 to T1
                    $event->sheet->getDelegate()->getStyle('N1:T1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);
                }
                else if(stripos($this->type,'monthly')!==false)
                {
                     // Range from N1 to T1
                    $event->sheet->getDelegate()->getStyle('N1:T1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                    // Range from U1 to AA1
                    $event->sheet->getDelegate()->getStyle('U1:AA1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);
                    // Range from AB1 to AH1
                    $event->sheet->getDelegate()->getStyle('AB1:AH1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                    // Range from AI1 to AO1
                    $event->sheet->getDelegate()->getStyle('AI1:AO1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                    // Range from AP1 to AR1
                    $event->sheet->getDelegate()->getStyle('AP1:AR1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);
                }
                else if(stripos($this->type,'quaterly')!==false)
                {
                    // Range from N1 to R1
                    $event->sheet->getDelegate()->getStyle('N1:R1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                    // Range from S1 to W1
                    $event->sheet->getDelegate()->getStyle('S1:W1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);
                    // Range from X1 to AB1
                    $event->sheet->getDelegate()->getStyle('X1:AB1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);
                }
                else if(stripos($this->type,'yearly')!==false)
                {
                    // Range from N1 to R1
                    $event->sheet->getDelegate()->getStyle('N1:R1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                    // Range from S1 to W1
                    $event->sheet->getDelegate()->getStyle('S1:W1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);
                    // Range from X1 to AB1
                    $event->sheet->getDelegate()->getStyle('X1:AB1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);
                    // Range from AC1 to AG1
                    $event->sheet->getDelegate()->getStyle('AC1:AG1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);
                    // Range from AC1 to AG1
                    $event->sheet->getDelegate()->getStyle('AC1:AG1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);
                    // Range from AH1 to AL1
                    $event->sheet->getDelegate()->getStyle('AH1:AL1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);
                     // Range from AM1 to AQ1
                     $event->sheet->getDelegate()->getStyle('AM1:AQ1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);
                     // Range from AR1 to AV1
                     $event->sheet->getDelegate()->getStyle('AR1:AV1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);
                    // Range from AW1 to BA1
                    $event->sheet->getDelegate()->getStyle('AW1:BA1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);
                     // Range from AW1 to BA1
                     $event->sheet->getDelegate()->getStyle('AW1:BA1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);
                    // Range from BB1 to BF1
                    $event->sheet->getDelegate()->getStyle('BB1:BF1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                     // Range from BG1 to BK1
                     $event->sheet->getDelegate()->getStyle('BG1:BK1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                    // Range from BL1 to BK1
                    $event->sheet->getDelegate()->getStyle('BL1:BP1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);

                    // Range from BQ1 to BU1
                    $event->sheet->getDelegate()->getStyle('BQ1:BU1')->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]);
                }
                 

                // Range from A2 to M2
                for($i=65;$i<=77;$i++)
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
                }

                $event->sheet->getDelegate()->getStyle('I1:M1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('A8E3F4');
                
                //Word Wrapp C2
                $event->sheet->getDelegate()->getStyle('C2')->getAlignment()
                ->setWrapText(true);

                // Word Wrap based on type
                if(stripos($this->type,'weekly')!==false || stripos($this->type,'monthly')!==false)
                {
                     //Word Wrapp N1 to T1
                    $event->sheet->getDelegate()->getStyle('N1:T1')->getAlignment()
                    ->setWrapText(true);
                }
                else if(stripos($this->type,'quaterly')!==false || stripos($this->type,'yearly')!==false)
                {
                     //Word Wrapp N1 to T1
                     $event->sheet->getDelegate()->getStyle('N1:R1')->getAlignment()
                     ->setWrapText(true);
                }

                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(30);
                $event->sheet->getDelegate()->getRowDimension('2')->setRowHeight(55);
                $event->sheet->getDelegate()->getStyle('A1:BU2')->getFont()->setBold(true);

                // cell design based on type for row 2
                if(stripos($this->type,'weekly')!==false)
                {
                     // Range from N to T
                    $i=78;
                    $j=84;
                    while($i<=$j)
                    {

                        $cell = chr($i).'2';
                        

                        $event->sheet->getDelegate()->getStyle($cell)->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);
                        $event->sheet->getDelegate()->getStyle($cell)->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB('968D81');

                        $event->sheet->getDelegate()->getStyle($cell)
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                        ->setVertical(Alignment::VERTICAL_CENTER);
                        $i++;

                    }
                }
                else if(stripos($this->type,'monthly')!==false)
                {
                     // Range from N to AR
                     $i=78;
                     $j=90;
                     $status=0;
                     while($i<=$j)
                     {
                         if($status==1)
                         {
                             $cell = 'A'.chr($i).'2';
                         }
                         else
                         {
                             $cell = chr($i).'2';
                         }
 
                         $event->sheet->getDelegate()->getStyle($cell)->applyFromArray([
                             'borders' => [
                                 'outline' => [
                                     'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                     'color' => ['argb' => '00000000'],
                                 ],
                             ],
                         ]);
                         $event->sheet->getDelegate()->getStyle($cell)->getFill()
                         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                         ->getStartColor()
                         ->setRGB('968D81');
 
                         $event->sheet->getDelegate()->getStyle($cell)
                         ->getAlignment()
                         ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                         ->setVertical(Alignment::VERTICAL_CENTER);
                         $i++;
                         if($cell=='Z2')
                         {
                             $i=65;
                             $j=82;
                             $status=1;
                         }
 
                     }
                }
                else if(stripos($this->type,'quaterly')!==false)
                {
                    // Range from N to AB
                    $i=78;
                    $j=90;
                    $status=0;
                    while($i<=$j)
                    {
                        if($status==1)
                        {
                            $cell = 'A'.chr($i).'2';
                        }
                        else
                        {
                            $cell = chr($i).'2';
                        }

                        $event->sheet->getDelegate()->getStyle($cell)->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);
                        $event->sheet->getDelegate()->getStyle($cell)->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB('968D81');

                        $event->sheet->getDelegate()->getStyle($cell)
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                        ->setVertical(Alignment::VERTICAL_CENTER);
                        $i++;
                        if($cell=='Z2')
                        {
                            $i=65;
                            $j=66;
                            $status=1;
                        }

                    }
                }
                else if(stripos($this->type,'yearly')!==false)
                {
                    // Range from N to BU
                    $i=78;
                    $j=90;
                    $status=0;
                    while($i<=$j)
                    {
                        if($status==2)
                        {
                            $cell = 'B'.chr($i).'2';
                        }
                        else if($status==1)
                        {
                            $cell = 'A'.chr($i).'2';
                        }
                        else
                        {
                            $cell = chr($i).'2';
                        }

                        $event->sheet->getDelegate()->getStyle($cell)->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]);
                        $event->sheet->getDelegate()->getStyle($cell)->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB('968D81');

                        $event->sheet->getDelegate()->getStyle($cell)
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                        ->setVertical(Alignment::VERTICAL_CENTER);
                        $i++;
                        if($cell=='Z2')
                        {
                            $i=65;
                            $j=90;
                            $status=1;
                        }
                        if($cell=='AZ2')
                        {
                            $i=65;
                            $j=85;
                            $status=2;
                        }

                    }
                }
            
                // $event->sheet->getDelegate()->getStyle('M2:Z3')->getFill()
                // ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                // ->getStartColor()
                // ->setARGB('968D81');

                $event->sheet->getDelegate()->getStyle('A3:BU1000')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }
         ];
 
         
     }
 
     public function columnWidths(): array
     {
         $cell_size = [];

         $i=66;
         $j=77;
         $status=0;
         while($i<=$j)
         { 
             
             $cell= chr($i);
             $cell_size= $this->array_push_assoc($cell_size,$cell,25);
             $i++;
            //  if($cell=='AZ')
            //  {
            //      $i=65;
            //      $j=90;
            //      $status=1;
            //  }
            
         }

         if(stripos($this->type,'weekly')!==false || stripos($this->type,'monthly')!==false)
         {
            $i=78;
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
                    $j=82;
                    $status=1;
                }
            }
         }
         
         // $cell_size['X']=5;

 
         return $cell_size;
     }

     public function columnFormats(): array
     {
         return [
             'F' => NumberFormat::FORMAT_NUMBER_00,
         ];
     }

     public function array_push_assoc($array, $key, $value)
     {
         $array[$key] = $value;
         return $array;
     }

    public function chunkSize(): int
    {
        return 200;
    }

}
