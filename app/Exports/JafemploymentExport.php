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
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class JafemploymentExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */

 	protected $from_date;
    protected $to_date;
    protected $customer_id;
    protected $candidate_id;
    protected $check_id;
    protected $business_id;

    function __construct($from_date, $to_date, $customer_id, $candidate_id, $check_id, $business_id) {
            $this->from_date        = $from_date;
            $this->to_date          = $to_date;
            $this->customer_id      = $customer_id;
            $this->candidate_id     = $candidate_id;
            $this->check_id         = $check_id;
            $this->business_id      = $business_id;
    }
    
    public function collection()
    {
        $query = DB::table('candidate_reinitiates as u')
              ->select('u.*','jf.id as jaf_id','jf.sla_id','jf.status','jf.job_id','jf.candidate_id')       
              ->join('jaf_form_data as jf','jf.candidate_id','=','u.id')     
              ->where(['u.user_type'=>'candidate','u.parent_id'=>$this->business_id,'jf.service_id'=>'10'])->whereNotNull('form_data');

                if( $this->candidate_id !="" ){
                    $query->where('u.id', $this->candidate_id);
                }
                if( $this->customer_id !="" ){
                    $query->where('u.business_id', $this->customer_id);
                }
                // both date is selected 
                if($this->from_date !="" && $this->to_date !=""){
                    $query->whereDate('u.created_at','>=',date('Y-m-d',strtotime($this->from_date)));
                    $query->whereDate('u.created_at','<=',date('Y-m-d',strtotime($this->to_date)));
                }
                else
                {
                  if($this->from_date !=""){
                    $query->whereDate('u.created_at','=',date('Y-m-d',strtotime($this->from_date)));
                  }
                }

                $query->orderBy('u.created_at','desc');

        return $users = $query->get();             

    }

    //
    public function map($users): array
    {
        $jaf = DB::table('jaf_form_data')->where(['id'=>$users->jaf_id,'service_id'=>'10'])->first();
        
        $array1 = json_decode($jaf->form_data, true);

        $new_arr=[$users->id,$users->first_name.' '.$users->last_name];
        $i=1;
        foreach ($array1 as $key => $value) {
            if($i !='1' && $i!= '2'){
                $data1 = array_values($value); 
                $new_arr[] =$data1[0];
                }
            $i++;
        }
        // $data = Arr::flatten(json_decode($jaf->form_data,true));
        return $new_arr;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Candidate Name',
            'Company Name',
            'Employee Code',
            'Employee Designation',
            'Employee Tenure',
            'Employment Type (Full Time/Part Time)',

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
