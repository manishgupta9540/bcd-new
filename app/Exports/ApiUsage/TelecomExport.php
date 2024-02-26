<?php

namespace App\Exports\ApiUsage;

use App\User;
use Maatwebsite\Excel\Concerns\FromQuery;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use App\Helpers\Helper;

class TelecomExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */

    protected $business_id;

    function __construct($business_id) {
        $this->business_id  = $business_id;
    }
    
    public function collection()
    {
        $users=DB::table('users')->where(['id'=>$this->business_id])->first();
        if($users->user_type=='customer')
        {
            $data=DB::table('telecom_check as a')
                            ->select('s.name','a.mobile_no','a.user_id','a.created_at','a.price','a.full_name')
                            ->join('services as s','s.id','=','a.service_id')
                            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$this->business_id])
                            ->orderBy('a.id','desc')
                            ->get();
        }
        else if($users->user_type=='client')
        {
            $data=DB::table('telecom_check as a')
                            ->select('s.name','a.mobile_no','a.user_id','a.created_at','a.price','a.full_name')
                            ->join('services as s','s.id','=','a.service_id')
                            ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$this->business_id])
                            ->orderBy('a.id','desc')
                            ->get();
        }
        return $data;             

    }

    // 
    public function map($data): array
    {
        $new_arr=[$data->mobile_no,ucfirst($data->full_name),Helper::user_name($data->user_id),date('d-F-Y h:i:s',strtotime($data->created_at)),'₹ '.$data->price];
        // $data = Arr::flatten(json_decode($jaf->form_data,true));
        return $new_arr;
    }

    public function headings(): array
    {
        return [
            'Phone Number',
            'Name',
            'Used By',
            'Date & Time',
            'Price',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A2:W5000')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);;
            },
        ];
    }
}
