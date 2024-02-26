<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\User;
use DB;


class ClientListExport implements FromCollection,WithHeadingRow,WithHeadings,WithEvents
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
        $query = DB::table('users as u')
                    ->select('u.display_id','u.name','u.email','u.phone','b.company_name','u.created_at')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['u.user_type'=>'client','u.parent_id'=>$this->business_id])
                    ->whereNotIn('u.id',[$this->business_id]);

        $clientlist = $query->get();

       return $clientlist;
    }

    public function headings(): array
    {
        return ["Reference Number", "Name", "Email","Phone","Company Name",'Created Date'];
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