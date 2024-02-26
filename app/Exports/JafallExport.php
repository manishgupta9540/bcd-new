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

class JafallExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */

 	// protected $from_date;
    // protected $to_date;
    // protected $customer_id;
    // protected $candidate_id;
    // protected $check_id;
    // protected $business_id;

    // function __construct($from_date, $to_date, $customer_id, $candidate_id, $check_id, $business_id) {
    //         $this->from_date        = $from_date;
    //         $this->to_date          = $to_date;
    //         $this->customer_id      = $customer_id;
    //         $this->candidate_id     = $candidate_id;
    //         $this->check_id         = $check_id;
    //         $this->business_id      = $business_id;
    // }
    
    public function collection()
    {
        // $query = DB::table('candidate_reinitiates as u')
        //       ->select('u.*','jf.id as jaf_id','jf.sla_id','jf.status','jf.job_id','jf.candidate_id')       
        //       ->join('jaf_form_data as jf','jf.candidate_id','=','u.id')     
        //       ->where(['u.user_type'=>'candidate','u.parent_id'=>$this->business_id,'jf.service_id'=>'1']);

        //         if( $this->candidate_id !="" ){
        //             $query->where('u.id', $this->candidate_id);
        //         }
        //         if( $this->customer_id !="" ){
        //             $query->where('u.business_id', $this->customer_id);
        //         }
        //         // both date is selected 
        //         if($this->from_date !="" && $this->to_date !=""){
        //             $query->whereDate('u.created_at','>=',date('Y-m-d',strtotime($this->from_date)));
        //             $query->whereDate('u.created_at','<=',date('Y-m-d',strtotime($this->to_date)));
        //         }
        //         else
        //         {
        //           if($this->from_date !=""){
        //             $query->whereDate('u.created_at','=',date('Y-m-d',strtotime($this->from_date)));
        //           }
        //         }

        //         $query->orderBy('u.created_at','desc'); 

        // return $user = $query->get();             

    }

    // 
    // public function map($user): array
    // {
        // $jaf = DB::table('jaf_form_data')->where(['id'=>$user->jaf_id,'service_id'=>'1'])->first();
        
        // $array1 = json_decode($jaf->form_data, true);

        // $new_arr=[$user->id,$user->first_name.' '.$user->last_name];
        // $i=1;
        // foreach ($array1 as $key => $value) {
        //     if($i !='1' && $i!= '2'){
        //         $data1 = array_values($value); 
        //         $new_arr[] =$data1[0];
        //         }
        //     $i++;
        // }
        // $data = Arr::flatten(json_decode($jaf->form_data,true));
        // return $new_arr;
    // }

    public function headings(): array
    {
        return [
            'SLNo',
            'Client Name',
            'Branch Name',
            'CompanyRefNo',
            'Type',
            'Candidate Name',
            'Employee Code',
            'Date Of Joining',
            'Date of Birth/Age',
            "Father's Name",
            'Spouse Name',
            'MobileNo',
            'LandLineNo',
            'Initiated To',
            'TAT',
            'TAT Type',
            'TAT Date',
            'Report Status',
            'Completed Date',
            'Final Report Color',
            'Invoice No',
            'Invoice Raised Status',
            'Invoice Raised Date',
            'Final Report Mail Send Status',
            'Interim Report Mail Send Status',
            'Final Report Shared On',
            'Interim Report Shared On',
            'RD-1 Address Type',
            'RD-1 Address',
            'RD-1 State Name',
            'RD-1 Verification Status',
            'RD-1 Verified By',
            'RD-1 Status',
            'RD-1 Color',
            'RD-1 Initiated To',
            'RD-1 Insufficiency',
            'RD-1 Insufficiency Date',
            'RD-1 Insufficiency ClearDate',
            'RD-1 IdleTime',
            'RD-1 Cost',
            'ED-1CertificateName',
            'ED-1CollegeName',
            'ED-1UniversityName',
            'ED-1Qualification',
            'ED-1DivisionPass',
            'ED-1Roll No',
            'ED-1Passing Year',
            'ED-1Verification Status',
            'ED-1Verified By',
            'ED-1Status',
            'ED-1Color',
            'ED-1Initiated To',
            'ED-1Insufficiency',
            'ED-1Insufficiency Date',
            'ED-1Insufficiency ClearDate',
            'ED-1IdleTime',
            'ED-1Cost',
            'ED-2CertificateName',
            'ED-2CollegeName',
            'ED-2UniversityName',
            'ED-2Qualification',
            'ED-2DivisionPass',
            'ED-2Roll No',
            'ED-2Passing Year',
            'ED-2Verification Status',
            'ED-2Verified By',
            'ED-2Status',
            'ED-2Color',
            'ED-2Initiated To',
            'ED-2Insufficiency',
            'ED-2Insufficiency Date',
            'ED-2Insufficiency ClearDate',
            'ED-2IdleTime',
            'ED-2Cost',
            'EMP-1 Organization Name',
            'EMP-1 Organization Location',
            'EMP-1 Designation',
            'EMP-1 Emp.Code',
            'EMP-1 Period of Employment',
            'EMP-1 Reporting Person',
            'EMP-1 Reason for leaving',
            'EMP-1 Verification Status',
            'EMP-1 Verified By',
            'EMP-1 Status',
            'EMP-1 Color',	
            'EMP-1 Initiated To',	
            'EMP-1 Insufficiency',	
            'EMP-1 Insufficiency Date',	
            'EMP-1 Insufficiency ClearDate',
            'EMP-1 IdleTime',	
            'EMP-1 Cost',
            'EMP-2 Organization Name',
            'EMP-2 Organization Location',
            'EMP-2 Designation',
            'EMP-2 Emp.Code',
            'EMP-2 Period of Employment',
            'EMP-2 Reporting Person',
            'EMP-2 Reason for leaving',
            'EMP-2 Verification Status',
            'EMP-2 Verified By',
            'EMP-2 Status',
            'EMP-2 Color',
            'EMP-2 Initiated To',
            'EMP-2 Insufficiency',
            'EMP-2 Insufficiency Date',
            'EMP-2 Insufficiency ClearDate',
            'EMP-2 IdleTime	EMP-2 Cost',
            'ID-1 Identity Type',
            'ID-1 Verification Status',
            'ID-1 Verified By',
            'ID-1 Status',
            'ID-1 Color',
            'ID-1 Initiated To',
            'ID-1 Insufficiency',
            'ID-1 Insufficiency Date',
            'ID-1 Insufficiency ClearDate',
            'ID-1 IdleTime',
            'ID-1 Cost',
            'CC-1 Verification Type',
            'CC-1 Address Type',
            'CC-1 Address',
            'CC-1 City Name',
            'CC-1 State Name',
            'CC-1 Country Name',
            'CC-1 Period of Stay',
            'CC-1 Verification Status',
            'CC-1 Verified By',
            'CC-1 Status',
            'CC-1 Color',
            'CC-1 Initiated To',
            'CC-1 Insufficiency',
            'CC-1 Insufficiency Date',
            'CC-1 Insufficiency ClearDate',
            'CC-1 IdleTime',
            'CC-1 Cost',
            'CR-1 Address Type',
            'CR-1 Address',
            'CR-1 Verification Status',
            'CR-1 Verified By',
            'CR-1 Status',
            'CR-1 Color',
            'CR-1 Initiated To',
            'CR-1 Insufficiency',
            'CR-1 Insufficiency Date',
            'CR-1 Insufficiency ClearDate',
            'CR-1 IdleTime',
            'CR-1 Cost',
            'RF-1 Company Name',
            'RF-1 Contact no/Email ID',
            'RF-1 Reference Name',
            'RF-1 Verification Status',
            'RF-1 Verified By',
            'RF-1 Status',
            'RF-1 Color',
            'RF-1 Initiated To',
            'RF-1 Insufficiency',
            'RF-1 Insufficiency Date',
            'RF-1 Insufficiency ClearDate',
            'RF-1 IdleTime',
            'RF-1 Cost',
            'RF-2 Company Name',
            'RF-2 Contact no/Email ID',
            'RF-2 Reference Name',
            'RF-2 Verification Status',
            'RF-2 Verified By',
            'RF-2 Status',	
            'RF-2 Color',
            'RF-2 Initiated To',
            'RF-2 Insufficiency',
            'RF-2 Insufficiency Date',
            'RF-2 Insufficiency ClearDate',
            'RF-2 IdleTime',
            'RF-2 Cost',
            'Total Cost'
            
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
