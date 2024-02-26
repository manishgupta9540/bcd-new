<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Admin\CandidateReinitiate;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;


class CandidateRefrenceDataExport implements FromCollection,WithHeadingRow,WithHeadings,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $business_id;
    protected $refrence_id;

    function __construct($business_id,$refrence_id) {
        $this->business_id   = $business_id;
        $this->refrence_id   = $refrence_id;
    }

    public function collection()
    {
        $query = CandidateReinitiate::select('display_id','name','frnid') 
                        ->where(['parent_id'=>$this->business_id,'user_type'=>'candidate'])
                        ->whereIn('id',$this->refrence_id)
                        ->orderBy('id','DESC');
    
        return $candidatelist =  $query->get();
    }

    public function headings(): array
    {
        return ["Refrence Number", "Candidate Name", "FRN ID"];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }
}
