<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class CandidateListExport implements FromCollection,WithHeadingRow,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $business_id;

    function __construct($business_id) {
        $this->business_id      = $business_id;
    } 

    public function collection()
    {

        $query = DB::table('candidate_reinitiates as u')
                        ->select('u.display_id','u.name','u.email','u.phone','b.company_name','u.created_at')
                        ->join('user_businesses as b','b.business_id','=','u.business_id') 
                        ->where(['parent_id'=>$this->business_id,'user_type'=>'candidate']);
                      //  ->orderBy('id','DESC');

        return $candidatelist =  $query->get();
    }

    public function headings(): array
    {
        return ["Reference Number", "Name", "Email","Phone", "Company Name",'Created Date'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(20);
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(16);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(30);
            },
        ];
    }
}
