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

class OPSExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithMapping, WithColumnFormatting
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */

 	protected $from_date;
    protected $to_date;
    protected $business_id;

    function __construct($from_date, $to_date, $business_id,$customer_id,$user_id) {
            $this->from_date        = $from_date;
            $this->to_date          = $to_date;
            $this->business_id      = $business_id;
            $this->customer_id = $customer_id;
            $this->user_id = $user_id;
    }
    
    public function collection()
    {
        // $user=[]; 
        DB::statement(DB::raw('set @row=0'));

        $query = DB::table('candidate_reinitiates as u')
                    ->select('u.*','ub.company_name',DB::raw('@row  := @row  + 1 AS s_no'),'s.id as service_id','s.name as service_name','jf.check_item_number as item_no','s.verification_type','j.id as job_item_id','j.tat_type','j.client_tat as case_tat','ri.additional_charges')
                    ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->join('jaf_form_data as jf','jf.job_item_id','=','j.id')
                    ->join('report_items as ri','ri.jaf_id','=','jf.id')
                    ->join('services as s','s.id','=','jf.service_id')
                    ->whereDate('u.created_at','>=',$this->from_date)
                    ->whereDate('u.created_at','<=',$this->to_date)
                    ->where(['u.parent_id'=>$this->business_id,'u.user_type'=>'candidate','j.jaf_status'=>'filled']);

                    if($this->customer_id!=NULL)
                    {
                        $query->whereIn('u.business_id',$this->customer_id);
                    }
                    if($this->user_id!=NULL)
                    {
                        $query->whereIn('u.created_by',$this->user_id);
                    }
                // $query->orderBy('u.id','desc');

        $user = $query->get();  
        
        // dd($user);

        return $user;

    }

    //
    public function map($user): array
    {
        
        $candidate_creation_date = date('Y-m-d',strtotime($user->created_at));

        $case_receive_date = date('Y-m-d',strtotime($user->case_received_date));

        $data = [
                    strval($user->s_no),
                    date('d-F-Y',strtotime($user->case_received_date)),
                    date('d-F-Y',strtotime($user->created_at)),
                    Helper::user_name($user->created_by),
                    $user->company_name,
                    $user->client_emp_code!=NULL ? $user->client_emp_code : 'N/A',
                    $user->name,
                    $user->display_id,
                    NULL,
                    date('d-F-Y',strtotime($user->created_at.'+ 14 weekdays')),
                    stripos($user->verification_type,'Manual')!==false ? $user->service_name.' - '.$user->item_no : $user->service_name,
                ];
        
        // check tat & feedback
        $check_tat = NULL;
        $date_of_feedback = NULL;
        if(stripos($user->tat_type,'check')!==false)
        {
            $date_arr = [];

            $job_sla_item = DB::table('job_sla_items')->where(['job_item_id'=>$user->job_item_id,'service_id'=>$user->service_id])->first();

            $check_tat = $job_sla_item->tat;

            $date_arr = $this->workingDays($candidate_creation_date,$check_tat,$job_sla_item->incentive_tat);

            $date_of_feedback = $date_arr['tat_date'];

        }

        array_push($data,strval($check_tat),$date_of_feedback);

        // case tat & delivery date

        $case_tat = NULL;

        $delivery_date = NULL;
        
        if(stripos($user->tat_type,'case')!==false)
        {
            $date_arr = [];

            $case_tat = $user->case_tat;

            // $date_arr = $this->workingDays($candidate_creation_date,$case_tat,$case_tat);

        }

        $delivery_date = date('d-F-Y',strtotime($case_receive_date.'+ 14 weekdays'));

        array_push($data,strval($case_tat),$delivery_date);

        // Vendor Name & Cost

        $vendor_name = NULL;

        $vendor_cost = 0.00;

        $vendor_task = DB::table('vendor_tasks')
                            ->where(['candidate_id'=>$user->id,'service_id'=>$user->service_id,'no_of_verification'=>$user->item_no])
                            ->where('status','<>','0')
                            ->first();

        if($vendor_task != NULL)
        {
            $vendor_name = Helper::user_name($vendor_task->business_id);

            $vendor_check_price = DB::table('vendor_service_items')->where(['business_id'=>$vendor_task->business_id,'service_id'=>$vendor_task->service_id])->first();

            if($vendor_check_price!=NULL)
            {
                $vendor_cost = $vendor_check_price->price;
            }
        }

        array_push($data,$vendor_name,$vendor_cost);

        // Misc Cost

        $add_charge = 0.00;

        $bill_service = DB::table('billing_items as bi')
                    ->select('bi.*')
                    ->join('billings as b','b.id','=','bi.billing_id')
                    ->where(['bi.candidate_id'=>$user->id,'bi.service_id'=>$user->service_id,'bi.service_item_number'=>$user->item_no])
                    ->latest()
                    ->first();

        if($bill_service!=NULL)
        {
            $add_charge = $bill_service->additional_charges;
        }
        else
        {
            $add_charge = $user->additional_charges;
        }

        array_push($data,strval($add_charge));

        // Client Penalty

        $penalty = 0.00;

        if($bill_service!=NULL)
        {
            $penalty = $bill_service->penalty;
        }

        array_push($data,strval($penalty));

        // Invoice Value

        $invoice_value = 0.00;

        if($bill_service!=NULL)
        {
            $invoice_value = $bill_service->final_total_check_price;
        }

        array_push($data,strval($invoice_value));
        
        return $data;

    }

    public function headings(): array
    {
        $columns = ['S.NO','DATE OF RECEIVING','DATE OF PUNCHING','PUNCHING INDIVIDUAL','CLIENT NAME','CLIENT CODE','CANDIDATE NAME','CASE REFERENCE NO.','BULK ID/PO NO.','BULK BILLING DATE','RECEIVING CHECKS','CHECK-WISE TAT (IN BUSINESS DAY)','VERIFICATION ON RECEIVING DATE','CASE WISE TAT','DELIVERY DATE','VENDOR NAME','VENDORS COST','MISC COST','CLIENT PENALTY','INVOICE VALUE','SUBMISSION DATE OF 1ST INVOICE','DUE DATE','SUBMISSION DATE OF 2ND INVOICE','SUBMISSION DATE OF 3RD INVOICE','PAYMENT RECEIPT DATE','QUICK BOOK UPDATION DATE'];

        return $columns;
        
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:ZZ1'; // All headers

                $q_count =  DB::table('candidate_reinitiates as u')
                                ->select('u.*','ub.company_name',DB::raw('@row  := @row  + 1 AS s_no'),'s.name as service_name','jf.check_item_number as item_no','s.verification_type')
                                ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                                ->join('jaf_form_data as jf','jf.candidate_id','=','u.id')
                                ->join('services as s','s.id','=','jf.service_id')
                                ->whereDate('u.created_at','>=',$this->from_date)
                                ->whereDate('u.created_at','<=',$this->to_date)
                                ->where(['u.parent_id'=>$this->business_id,'u.user_type'=>'candidate']);
                                if($this->customer_id!=NULL)
                                {
                                    $q_count->whereIn('u.business_id',$this->customer_id);
                                }
                                if($this->user_id!=NULL)
                                {
                                    $q_count->whereIn('u.created_by',$this->user_id);
                                }
                   $q_count=$q_count->get();

                $q_count = count($q_count);

                for($i=65;$i<=90;$i++)
                {
                    $cell = chr($i)."1";

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

                    $event->sheet->getDelegate()->getStyle($cell)->getFill()
                                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                            ->getStartColor()
                                            ->setARGB('615959');

                    $event->sheet->getDelegate()->getStyle($cell)
                                                ->getFont()
                                                ->getColor()
                                                ->setARGB('FFFFFF');
                    
                }
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(30);

                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A2:A'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('L2:L'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('Q2:Q'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('R2:R'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('S2:S'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('T2:T'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            },
        ];

        
    }

    public function columnFormats(): array
    {
        return [
            'Q' => NumberFormat::FORMAT_NUMBER_00,
            'R' => NumberFormat::FORMAT_NUMBER_00,
            'S' => NumberFormat::FORMAT_NUMBER_00,
            'T' => NumberFormat::FORMAT_NUMBER_00,
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
}
