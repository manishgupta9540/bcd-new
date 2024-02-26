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
use DB;

class UsersExport implements FromCollection
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */

 	protected $from_date;
    protected $to_date;
    protected $customer_id;

    function __construct($report_from_date, $report_to_date, $report_customer_id) {
            $this->from_date = $report_from_date;
            $this->to_date   = $report_to_date;
            $this->customer_id   = $report_customer_id;
    }
    
    public function collection()
    {
        $query = DB::table('candidate_reinitiates as u')
              ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id')      
              ->join('job_items as j','j.candidate_id','=','u.id')        
              ->where(['u.user_type'=>'candidate','u.parent_id'=>$business_id]);

                if( $this->report_customer_id !="" ){
                    $query->where('w.customer_id', $this->report_customer_id);
                }

                // both date is selected 
                if($this->report_from_date !="" && $this->report_to_date !=""){
                    $query->whereDate('w.report_date','>=',date('Y-m-d',strtotime($this->report_from_date)));
                    $query->whereDate('w.report_date','<=',date('Y-m-d',strtotime($this->report_to_date)));
                }
                else
                {
                  if($this->report_from_date !=""){
                    $query->whereDate('w.report_date','=',date('Y-m-d',strtotime($this->report_from_date)));
                  }
                }

                $query->orderBy('w.report_date','desc');

        return $user = $query->get();     
    }

    //
    public function map($user): array
    {
        $remarks   = "";
        $remark_by = "";
        $comment   = DB::table('work_report_comments as wc')
        ->select('wc.comment','wc.created_by','u.first_name','u.last_name')
        ->join('users as u','u.id','=','wc.created_by')
        ->where(['report_id'=>$user->id])->first(); 
        //
        if($comment != null){
            $remarks = strip_tags($comment->comment);
            $remark_by = $comment->first_name.' '.$comment->last_name;
        }
        //
        $designation_data   = DB::table('user_accounts as uc')
        ->select('d.name')
        ->join('designations as d','d.id','=','uc.designation_id')
        ->where(['uc.customer_id'=>$user->id])->first(); 
        //
        $designation = "";
        if($designation_data != null){
           $designation =  '('.$designation_data->name.')';
        }
        

        return [
                
                date('d-M-Y',strtotime($user->report_date)) ,
                $user->first_name.' '.$user->last_name.' '.$designation,
                $remarks,
                $remark_by,

        ];
    }

    public function headings(): array
    {
        return [
            'Date',
            'Name',
            'Remarks',
            'Remarks By'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }
}
