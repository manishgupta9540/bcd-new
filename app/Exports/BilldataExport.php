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
use App\Helpers\Helper;
class BilldataExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */

 	
    protected $billing_id;

    function __construct($billing_id) {
        $this->billing_id      = $billing_id;
    }
    
    public function collection()
    {
        // $user=[]; 
        $query = DB::table('billing_items as bi')
                    ->select('bi.*','s.verification_type')
                    ->join('services as s','s.id','=','bi.service_id')
                    ->where('bi.billing_id',$this->billing_id)
                    ->orderBy('bi.service_id','asc');

        $bill = $query->get();  

        return $bill;

    }

    //
    public function map($bill): array
    {
        $new_arr = [Helper::company_name($bill->business_id)];

        //candidate name
        if($bill->candidate_id!=NULL)
        {
            $new_arr[]=Helper::user_name($bill->candidate_id);
        }
        else
        {
            $new_arr[]='Instant Verification (API)';
        }

        // Check Name

        if(stripos($bill->verification_type,'Manual')!==false)
        {
            $new_arr[]=$bill->service_name.' - '.$bill->service_item_number;
        }
        else
        {
            $new_arr[]=$bill->service_name;
        }

        // Check Price

        $new_arr[]=strval($bill->final_total_check_price);
        
        return $new_arr;

    }

    public function headings(): array
    {
        $columns = ['Client Name','Candidate Name','Check Name','Total Check Price'];
        
        return $columns;
       
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:ZZ1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];

        
    }
}
