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
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InsuffdataExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
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

    function __construct($from_date, $to_date, $customer_id, $candidate_id, $business_id,$type,$status) {
            $this->from_date        = $from_date;
            $this->to_date          = $to_date;
            $this->customer_id      = $customer_id;
            $this->candidate_id     = $candidate_id;
            $this->business_id      = $business_id;
            $this->type      = $type;
            $this->status      = $status;
    }
    
    public function collection()
    {
        // $user=[]; 
        $query = DB::table('candidate_reinitiates as u')
                    ->select('u.display_id','u.name','ub.company_name','u.id','vs.*','s.verification_type','s.name as service_name')
                    ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                    ->join('jaf_form_data as jf','jf.candidate_id','=','u.id')
                    ->join('verification_insufficiency as vs','jf.id','=','vs.jaf_form_data_id')
                    ->join('services as s','s.id','=','vs.service_id')
                    ->whereIn('u.id',$this->candidate_id);
                    // ->where(['vs.status'=>'raised']);
                    

                if($this->customer_id !="" ){
                    $query->where('u.business_id', $this->customer_id);
                }

                // both date is selected 
                if($this->from_date !="" && $this->to_date !=""){
                    // $query->whereDate('u.created_at','>=',date('Y-m-d',strtotime($this->from_date)));
                    // $query->whereDate('u.created_at','<=',date('Y-m-d',strtotime($this->to_date)));

                    $query->where(function($q) {
                        // echo(date('Y-m-d',strtotime($this->input('from_date'))));
                        $q->whereDate('vs.created_at','>=',date('Y-m-d',strtotime($this->from_date)))
                        ->orWhereDate('vs.updated_at','>=',date('Y-m-d',strtotime($this->from_date)));
                    });

                    $query->where(function($q) {
                        // echo(date('Y-m-d',strtotime($this->input('to_date'))));
                        $q->whereDate('vs.created_at','<=',date('Y-m-d',strtotime($this->to_date)))
                        ->orWhereDate('vs.updated_at','<=',date('Y-m-d',strtotime($this->to_date)));
                    });
                }
                else
                {
                  if($this->from_date !=""){
                    //$query->whereDate('u.created_at','=',date('Y-m-d',strtotime($this->from_date)));

                    $query->where(function($q) {
                        // echo(date('Y-m-d',strtotime($this->input('from_date'))));
                        $q->whereDate('vs.created_at','>=',date('Y-m-d',strtotime($this->from_date)))
                        ->orWhereDate('vs.updated_at','>=',date('Y-m-d',strtotime($this->from_date)));
                    });

                  }
                }

                if($this->status!='')
                {
                    $query->whereIn('vs.status',[$this->status]);
                }
                else
                {
                    $query->whereIn('vs.status',['raised']);
                }

                $query->orderBy('u.id','desc')->orderBy('vs.service_id');

        $user = $query->get();  
        
        // dd($user);

        return $user;

    }

    //
    public function map($user): array
    {
        $data = [];
        
        $new_arr=[$user->display_id,$user->name,$user->company_name];

        // Check Type

        $check_type = '';

        $insuff_date = NULL;

        $insuff_details = NULL;

        if($user->updated_at==NULL)
            $insuff_date=$user->created_at;
        else
            $insuff_date=$user->updated_at;

        if(stripos($user->verification_type,'Manual')!==false)
        {
            $insuff_details=$user->service_name."-".$user->item_number;
        }
        else
        {

            $insuff_details=$user->service_name;
        }

        $check_type = $user->service_name;


        array_push($new_arr,
                    $insuff_details,
                    $check_type,
                    $user->notes!=NULL ? $user->notes : 'N/A',
                    $insuff_date!=NULL ? date('d-M-Y h:i:s A',strtotime($insuff_date)) : 'N/A',
                );

        // $insuff_data=DB::table('verification_insufficiency as vs')
        //                 ->select('vs.*','s.verification_type','s.name')
        //                 ->join('jaf_form_data as jf','jf.id','=','vs.jaf_form_data_id')
        //                 ->join('services as s','s.id','=','vs.service_id')
        //                 ->where(['vs.candidate_id'=>$user->id,'vs.status'=>'raised','jf.is_insufficiency'=>1])
        //                 ->orderBy('vs.service_id')
        //                 ->get();
            
        // if(count($insuff_data)>0)
        // {
        //     $insuff_details='';

        //     $check_type = '';

        //     $check_remarks = '';

        //     $ins_date = NULL;

        //     foreach($insuff_data as $insuff)
        //     {
        //         if($insuff->updated_at==NULL)
        //             $insuff_date=$insuff->created_at;
        //         else
        //             $insuff_date=$insuff->updated_at;
                
        //         if(stripos($insuff->verification_type,'Manual')!==false)
        //         {
        //             $check_type.= $insuff->name."-".$insuff->item_number.', ';

        //             $insuff_details.=$insuff->name."-".$insuff->item_number." : ".date('d-M-Y h:i:s A',strtotime($insuff_date)).', ';
        //         }
        //         else
        //         {
        //             $check_type.=$insuff->name.', ';

        //             $insuff_details.=$insuff->name." : ".date('d-M-Y h:i:s A',strtotime($insuff_date)).", ";
        //         }

        //         $check_remarks.=$insuff->notes!=NULL ? $insuff->notes : 'N/A'.', ';

        //         $ins_date.= date('d-M-Y h:i:s A',strtotime($insuff_date)).", ";

        //         if(stripos($this->type,'csv')!==false)
        //         {
        //             $check_type.="\n";

        //             $check_remarks.= "\n";

        //             $insuff_details.="\n";

        //             $ins_date.="\n";
        //         }
        //     }
        //     // dd($insuff_details);
        //     // $new_arr[]=$insuff_details;

        //     array_push($new_arr,$check_type,$check_remarks,$ins_date!=NULL ? $ins_date : 'N/A',$insuff_details);
        // }

        return $new_arr;

    }

    public function headings(): array
    {
        $columns = ['REF ID','Candidate Name','Company Name','Checks','Check Type'];
        // $items = DB::table('service_form_inputs')->where(['service_id'=>$this->check_id])->get();
        // $i=1;
        // foreach($items as $item){
            
        //     $columns[] = $item->label_name;
        // }
        // return $columns;

        if($this->status!='')
        {
            if($this->status=='raised')
            {
                array_push($columns,'Check Remarks','Insuff Raise Date');
            }
            else if($this->status=='removed')
            {
                array_push($columns,'Check Remarks','Insuff Cleared Date');
            }
        }   
        else
        {
            array_push($columns,'Check Remarks','Insuff Raise Date');
        }

        return $columns;
        // return [
        //     'ID',
        //     'Candidate Name',
        //     'Address',
        //     'Pincode',
        //     'City',
        //     'State',
        //     'Father Name',
        //     'Date of Birth',
        //     'Period of Stay',
        //     'Contact Number',

        // ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class  => function(AfterSheet $event) {
                $cellRange = 'A1:ZZ1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];

        
    }
}
