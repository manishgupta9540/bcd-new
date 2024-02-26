<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use App\Helpers\Helper;
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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SlaExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    // protected $business_id;
    // public $service_id;
    public $slaData;
    public $id;


    function __construct($slaData,$id) {
        $this->slaData   = $slaData;
        $this->id   = $id;
    }

    public function collection()
    {
        //$value = '';
        $users[] = [
            'client_emp_code'       =>'Optional' ,
            'entity_code'           =>'Optional',
            'first_name'            =>'Baryan',
            'middle_name'           =>'Jones',
            'last_name'             =>'patch',
            'father_name'           =>'Lynard',
            'aadhaar_number'        =>'Optional',
            'dob'                   =>'14-07-1992',
            'gender'                =>'Male',
            'phone'                 =>'9023415263',
            'email'                 =>'Optional',
            'frnid'                 => 'Optional',
            'recruiter'             => 'Optional',
            'source'                => 'Optional',
            'cost_code'             => 'Optional',
            'grade'                 => 'Optional',
        ];
        
        return collect($users);
    }

    public function map($users): array
    {
        
        $data = [
            $users['client_emp_code'],
            $users['entity_code'],
            $users['first_name'],
            $users['middle_name'],
            $users['last_name'],
            $users['father_name'],
            $users['aadhaar_number'],
            $users['dob'],
            $users['gender'],
            $users['phone'],
            $users['email'],
            $users['frnid'],
            $users['recruiter'],
            $users['source'],
            $users['cost_code'],
            $users['grade'],

        ];

        $sla_data  = DB::table('customer_sla_items as csi')
                    ->select('csi.*')
                    ->join('customer_sla as c','c.id','=','csi.sla_id')
                    ->where(['c.id'=>$this->id])
                    ->get();

                    foreach($sla_data as $service)
                    {
                        $service_data = DB::table('service_form_inputs as si')
                                        ->select('si.*','s.name as service_name','s.type_name','s.verification_type')
                                        ->join('services as s','s.id','=','si.service_id')
                                        ->where(['si.service_id'=>$service->service_id,'si.status'=>1])
                                        ->whereNull('si.reference_type')
                                        //->whereNotIn('si.label_name',['First Name','Last Name','Father Name','Date of Birth','Mode of Verification','Remarks'])
                                        ->get();
                        
                        $no_of_verification=$service->number_of_verifications;
            
                    
                        for($i = 0; $i < $no_of_verification; $i++)
                        {
                            $items = DB::table('service_form_inputs as si')
                                    ->select('si.*','s.name as service_name','s.type_name','s.verification_type')
                                    ->join('services as s','s.id','=','si.service_id')
                                    ->where(['si.service_id'=>$service->service_id,'si.status'=>1])
                                    ->whereNull('si.reference_type')
                                    //->whereNotIn('si.label_name',['First Name','Last Name','Father Name','Date of Birth','Mode of Verification','Remarks'])
                                    ->get();
            
                                $insuff_details = 'N/A';
            
                                foreach($items as $key => $item)
                                {
                                    if(stripos($item->verification_type,'Manual')!==false)
                                    {
                                        $j=$i+1;
                                        if($key == '0'){
                                            $data[]=''; 
                                        }
                                        else
                                        { 
                                           if($item->type_name == 'reference'){
                                                if($item->label_name != 'Mode of Verification' && $item->label_name != 'Remarks'){
                                                    $data[]=''; 
                                                }
                                           }
                                           else
                                           {
                                                $data[]=''; 
                                           }
                                        }
                                    }
                                    else
                                    {
                                        if($key == '0'){
                                            $data[]=''; 
                                        }
                                        else
                                        {
                                            $data[]=''; 
                                            
                                        }
                                    }
                                }
                                 $data[]=$insuff_details ;
                                  
                        }
                    }
   
       
                    //dd($data);
        return $data;
       
    }



    public function headings(): array
    {
        $sla_data  = DB::table('customer_sla_items as csi')
                    ->select('csi.*')
                    ->join('customer_sla as c','c.id','=','csi.sla_id')
                    ->where(['c.id'=>$this->id])
                    ->get();
        //dd($sla_data);

        $columns = [
            'client_emp_code',
            'entity_code',
            'first_name',
            'middle_name',
            'last_name',
            'father name',
            'aadhaar_number',
            'dob',
            'gender',
            'phone',
            'email',
            'frnid',
            'recruiter',
            'source',
            'cost_code',
            'grade'
        ];

        foreach($sla_data as $service)
        {
            $service_data = DB::table('service_form_inputs as si')
                            ->select('si.*','s.name as service_name','s.type_name','s.verification_type')
                            ->join('services as s','s.id','=','si.service_id')
                            ->where(['si.service_id'=>$service->service_id,'si.status'=>1])
                            ->whereNull('si.reference_type')
                            //->whereNotIn('si.label_name',['First Name','Last Name','Father Name','Date of Birth','Mode of Verification','Remarks'])
                            ->get();
            
            $no_of_verification=$service->number_of_verifications;

        
            for($i = 0; $i < $no_of_verification; $i++)
            {
                $items = DB::table('service_form_inputs as si')
                        ->select('si.*','s.name as service_name','s.type_name','s.verification_type')
                        ->join('services as s','s.id','=','si.service_id')
                        ->where(['si.service_id'=>$service->service_id,'si.status'=>1])
                        ->whereNull('si.reference_type')
                        //->whereNotIn('si.label_name',['First Name','Last Name','Father Name','Date of Birth','Mode of Verification','Remarks'])
                        ->get();

                    $insuff_details = 'Insuff_Details';

                    foreach($items as $key => $item)
                    {
                        if(stripos($item->verification_type,'Manual')!==false)
                        {
                            $j=$i+1;
                            if($key == '0'){
                                $columns[]=$item->label_name.' ('.$item->service_name.'- '.$j.')'; 
                            }
                            else
                            { 
                               if($item->type_name == 'reference'){
                                    if($item->label_name != 'Mode of Verification' && $item->label_name != 'Remarks'){
                                      $columns[]=$item->label_name;
                                    }
                               }
                               else
                               {
                                    $columns[]=$item->label_name;
                               }
                            }
                        }
                        else
                        {
                            if($key == '0'){
                                $columns[]=$item->label_name.' ('.$item->service_name.')';
                            }
                            else
                            {
                                $columns[]=$item->label_name; 
                            }
                        }
                    }
                    $columns[]=$insuff_details ;
                      
            }
        }
         //dd($columns);
        return $columns;
        
    }


    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:ZZ1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
                // $event->sheet->setCellValue('A2','Optional');
                // $event->sheet->setCellValue('B2','Optional');
                // $event->sheet->setCellValue('C2','Baryan');
                // $event->sheet->setCellValue('D2','Jones');
                // $event->sheet->setCellValue('E2','patch');
                // $event->sheet->setCellValue('F2','Lynard');
                // $event->sheet->setCellValue('G2','123245873215');
                // $event->sheet->setCellValue('H2','14-07-1992');
                // $event->sheet->setCellValue('I2','Male');
                // $event->sheet->setCellValue('J2','9023415263');
                // $event->sheet->setCellValue('K2','abc@yopmail.com');
            },
            
        ]; 
    }
}
