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
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB as FacadesDB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class VendorTaskExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents,WithMapping,WithTitle
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */

 	protected $from_date;
    protected $to_date;
    protected $export_type;
    protected $task_id;
    protected $service_id;
    // protected $no_of_verifications;

    function __construct($from_date, $to_date,$export_type,$task_id,$service_id) {
        $this->from_date        = $from_date;
        $this->to_date          = $to_date;
        $this->export_type      = $export_type;
        $this->task_id     = $task_id;
        $this->service_id         = $service_id;
        // $this->no_of_verifications = $no_of_verifications;
    }
    
     /**
     * @return array
     */
   
    public function collection()
    {
        // dd($this->service_id);
        // $tasks = DB::table('users') 
        $query = DB::table('candidate_reinitiates as u')
              ->select('u.*','vt.id','vta.service_id','vta.candidate_id','vta.no_of_verification')
              ->join('vendor_tasks as vt','vt.candidate_id','=','u.id')
              ->join('vendor_task_assignments as vta','vta.vendor_task_id','=','vt.id')
              ->where('vt.service_id',$this->service_id)
              ->whereIn('vt.id',$this->task_id);
            //   ->whereIn('vta.status',[0,1]);
              
                // if( $this->candidate_id !="" ){
                //     $query->where('u.id', $this->candidate_id);
                // }
                // if( $this->customer_id !="" ){
                //     $query->where('u.business_id', $this->customer_id);
                // }
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
                $query->groupBy('vt.id');
                $query->orderBy('vt.service_id','asc');
                // dd($query->get());
        return $query->get();

    }

    // 
    public function map($user): array
    {
        // $jaf = DB::table('jaf_form_data')->where(['id'=>$user->jaf_id])->first();
        // dd($jaf);
        // die;
        // $array1 = json_decode($jaf->form_data, true);
        //dd($array1);
        // dd($user);
        $new_arr=[$user->display_id,$user->first_name,$user->last_name,$user->father_name, date('d M Y', strtotime($user->dob))];

        // $key_account_manager = DB::table('key_account_managers as k')
        //                         ->select('u.*')
        //                         ->join('users as u','u.id','=','k.user_id')
        //                         ->where(['k.business_id'=>$user->business_id])
        //                         ->get();
        // Key Manager Name
        // if(count($key_account_manager)>0)
        // {
        //     $user_name = '';
        //     $c=0;
        //     $count = count($key_account_manager);
        //     foreach($key_account_manager as $key => $kam)
        //     {
        //         if(++$c==$count)
        //             $user_name = $user_name.' '.$kam->name;
        //         else
        //             $user_name = $user_name.' '.$kam->name.',';
        //     }

        //     $new_arr[]=$user_name;
        // }
        // else
        // {
            // $new_arr[]=NULL;
        // }

        //Report Status
        // if(stripos($user->report_status,'incomplete')!==false)
        // {
        //     $new_arr[] = 'Pending';
        // }
        // else
        // {
        //     $new_arr[] = ucwords($user->report_status);
        // }
            
        $i=1;
        // foreach($this->service_id as $service_id)
        // {
            $no_of_verification=1;
            // $max_verification=1;
            // $max_verification=DB::table('job_sla_items as js')
            //                 ->select('js.number_of_verifications')
            //                 ->join('job_items as j','j.id','=','js.job_item_id')
            //                 ->where('j.jaf_status','filled')
            //                 ->where(['js.service_id'=>$service_id,'js.no_of_verifications'=>$user->no_of_verification,'js.candidate_id'=>$user->candidate_id]);

            $jaf_form=DB::table('jaf_form_data')
            ->where(['candidate_id'=>$user->candidate_id,'service_id'=>$user->service_id,'check_item_number'=>$user->no_of_verification])
            ->orderBy('check_item_number','asc')
            ->first();
           
            
            $service_items = DB::table('service_form_inputs')
                        ->where(['service_id'=>$user->service_id,'status'=>1])
                        ->whereNull('reference_type')
                        ->whereNotIn('label_name',['First Name','Last Name','Father Name','Date of Birth','Mode of Verification','Remarks'])
                        ->get();
                        
            $services=DB::table('services')->where('id',$user->service_id)->first();
            
            if(stripos($services->verification_type,'Manual')!==false)
            {
                if($jaf_form)
                {
                    // dd($jaf_form);
                    $form_data_arr=json_decode($jaf_form->form_data,true);
                    foreach($form_data_arr as $key => $input)
                    {
                        $key_val = array_keys($input); $input_val = array_values($input);
                        if(!(stripos($key_val[0],'First Name')!==false || stripos($key_val[0],'Last Name')!==false || stripos($key_val[0],'Father Name')!==false || stripos($key_val[0],'Date of Birth')!==false))
                        {
                            $new_arr[]=$input_val[0];
                        }
                    }
                }
                    
                
               
            }
           
            
        

        $new_arr[]='+'.$user->phone_code.'-'.$user->phone;
         
        $new_arr[]=$user->email;
        // dd($new_arr);
        return $new_arr;
    }

    public function headings(): array
    {
        $columns=[];
        if ($this->service_id==10) {
            $columns = ['Reference ID','First Name','Last Name','Father Name','Date of Birth','Company name','Employee Code','Employee Designation','Employee Tenure','Employment Type (Full Time / Part Time)'];

        }
        elseif ($this->service_id==11 ) {
            $columns = ['Reference ID','First Name','Last Name','Father Name','Date of Birth','University Name / Board Name','Degree','Registration / Roll / Enrollment Number','Year Of Qualification'];

        }
        elseif ($this->service_id==15 ) {
            $columns = ['Reference ID','First Name','Last Name','Father Name','Date of Birth','Address','Address Type'];

        }
        elseif ($this->service_id==16) {
            $columns = ['Reference ID','First Name','Last Name','Father Name','Date of Birth','Address','Address Type'];

        }
        elseif ($this->service_id==18) {
            $columns = ['Reference ID','First Name','Last Name','Father Name','Date of Birth','Address','Gender','Type'];

        }
        // else{
        //     $columns = ['Client Name','Reference ID','First Name','Last Name','Father Name','Date of Birth','Client Location','Case Manager','Overall Case Status'];
        // }
        // $items = DB::table('service_form_inputs')->where(['service_id'=>$this->check_id])->get();
        // $i=1;
        // foreach($items as $item){
            
        //     $columns[] = $item->label_name;
        // }
        // return $columns;

        // foreach($this->service_id as $service_id)
        // {
        //     $job_sla_items=DB::table('job_sla_items as js')
        //                     ->select('js.*')
        //                     ->join('job_items as j','j.id','=','js.job_item_id')
        //                     ->where('j.jaf_status','filled')
        //                     ->where('js.service_id',$service_id)
        //                     // ->whereIn('js.candidate_id',$this->candidate_id)
        //                     ->get();
        //     $no_of_verification=[];
        //     if(count($job_sla_items)>0)
        //     {
        //         $max=1;
        //         // $j=1;
        //         foreach($job_sla_items as $item) 
        //         {
        //             $no_of_verification[]=$item->number_of_verifications;
        //         }

        //         $max=max($no_of_verification);

        //         // dd($max);

        //         // dd($items);

        //         for($i = 0; $i < $max; $i++)
        //         {
        //             // $i=1;

        //             $items = DB::table('service_form_inputs')
        //                         ->where(['service_id'=>$service_id,'status'=>1])
        //                         ->whereNull('reference_type')
        //                         ->whereNotIn('label_name',['First Name','Last Name','Father Name','Date of Birth','Mode of Verification','Remarks'])
        //                         ->get();
                    
        //             if($service_id==2)
        //             {
        //                 $columns[] = 'Aadhar Number';
        //             }
        //             else if($service_id==3)
        //             {
        //                 $columns[] = 'PAN Number';
        //             }
        //             else if($service_id==4)
        //             {
        //                 $columns[] = 'Voter ID Number';
        //             }
        //             else if($service_id==7)
        //             {
        //                 $columns[] = 'RC Number';
        //             }
        //             else
        //             {
                        
        //                 foreach($items as $item){

        //                     $service_input=DB::table('service_form_inputs as si')
        //                                     ->select('si.*','s.name as service_name')
        //                                     ->join('services as s','s.id','=','si.service_id')
        //                                     ->where('si.id',$item->id)
        //                                     ->first();
                                            
        //                     if($item->service_id==1)
        //                     {
        //                         if($service_input->label_name=='Address')
        //                         {
        //                             $j=$i+1;
        //                             $columns[]='Address - '.$j;
        //                         }
        //                         else
        //                         {
        //                             $columns[]=$item->label_name;
        //                         }
        //                     }
        //                     else if($item->service_id==10)
        //                     {
        //                         if(stripos($service_input->label_name,'Company name')!==false)
        //                         {
        //                             $k=$i+1;
        //                             $columns[]='Company name - '.$k;
        //                         }
        //                         else
        //                         {
        //                             $columns[]=$item->label_name;
        //                         }
        //                     }
        //                     else if($item->service_id==11)
        //                     {
        //                         if(stripos($service_input->label_name,'University Name / Board Name')!==false)
        //                         {
        //                             $l=$i+1;
        //                             $columns[]='University Name / Board Name - '.$l;
        //                         }
        //                         else
        //                         {
        //                             $columns[]=$item->label_name;
        //                         }
        //                     }
        //                     else if($item->service_id==15)
        //                     {
        //                         if(stripos($service_input->label_name,'Address')!==false)
        //                         {
        //                             $m = $i+1;
        //                             $columns[]=$service_input->service_name.' '.'Address - '.$m;
        //                         }
        //                         else
        //                         {
        //                             $columns[]=$service_input->service_name.' '.$item->label_name;
        //                         }
        //                     }
        //                     else if($item->service_id==16)
        //                     {
        //                         if(stripos($service_input->label_name,'Address')!==false)
        //                         {
        //                             $n = $i+1;
        //                             $columns[]=$service_input->service_name.' '.'Address - '.$n;
        //                         }
        //                         else
        //                         {
        //                             $columns[]=$service_input->service_name.' '.$item->label_name;
        //                         }
        //                     }
        //                     else if($item->service_id==17)
        //                     {
        //                         if(stripos($service_input->label_name,'Reference Type (Personal / Professional)')!==false)
        //                         {
        //                             $o=$i+1;
        //                             $columns[]='Reference Type (Personal / Professional) - '.$o;
        //                         }
        //                         else
        //                         {
        //                             $columns[]=$item->label_name;
        //                         }
        //                     }
        //                     else if($item->service_id==21)
        //                     {
        //                         if(stripos($service_input->label_name,'Antigen')!==false)
        //                         {
        //                             $p = $i+1;
        //                             $columns[]=$service_input->service_name.' '.'Antigen - '.$p;
        //                         }
        //                         else
        //                         {
        //                             $columns[]=$service_input->service_name.' '.$item->label_name;
        //                         }
        //                     }
        //                     else
        //                     {
        //                         $columns[]=$item->label_name;
        //                     }
        //                 }
                       
                        
        //             }
        //         }

        //     }

        // }

        $columns[]='Candidate Mobile Number';
        $columns[]='Candidate Email ID';
            // dd($columns);
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

    public function title(): string
    {
        $service_name = DB::table('services')->where('id',$this->service_id)->first();
        return $service_name->name;
    }
}
